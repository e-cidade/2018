<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require_once ('interfaces/calculoRetencao.interface.php');
/**
 * Calcula o IRRF pessoa física.
 * @author Iuri Guntchnigg
 * @version $Revision: 1.22 $ 
 */
class calculoRetencaoIrrfFisica implements iCalculoRetencao {

  /**
   * tipo do calculo
   * @var integer
   */
  private $iTipo = 1;
  
  /**
   * Valor da base de calculo
   *
   * @var float
   */
  private $nValorBaseCalculo = 0;
  
  /**
   * Valor da Dedução informado pelo usuário.
   *
   * @var float
   */ 
  private $nValorDeducao = 0;
  
  /**
   * cpf ou cnpj a calcular o imposto.
   *
   * @var integer
   */
  private $iCgcCpf = null;
  
  /**
   * Tabela do IRRF a ser usada
   *
   * @var object
   */
  private $oTabelaIrrf = null;
  
  /**
   * Valor da Aliquota
   *
   * @var float
   */
  private $nAliquota  = 0;
  
  /**
   * Valor a ser adicionado da nota
   *
   * @var float
   */
  private $nValorNota= 0;
  
  private $dtBaseCalculo;
  
  /**
   * Códigos Auxiliares doe Movimentos 
   *
   * @var array
   */
  private $aCodigoMovimentos = array();
  
  /**
   * metodo construtor da classe
   * @param integer $iTipo tipo do calculo 1 - pessoa fisica
   */
  function __construct($iCgcCpf, $iTipo = 1) {

    $this->iTipo   = 1;
    $this->iCgcCpf = (string)$iCgcCpf;
    
  }
  
  /**
   * 
   * @see iCalculoRetencao::calcularetencao()
   */
  function calcularRetencao() {
    
    $this->nValorBaseCalculo = $this->calculaBasedeCalculo();
    $this->getTabelaIRRF();
    $this->nAliquota = $this->oTabelaIrrf->r33_perc;
    $nValorRetido = 0;
    /**
     * Calculamos o valor da retencao, multiplicando  a base de calculo pela aliquota;
     */
    $nValorRetido = $this->nValorBaseCalculo * ($this->oTabelaIrrf->r33_perc/100);
    /*
     * Deduzimos do resultado o valor a deduzir da tabela de IRRF
     */
    $nValorRetido = $nValorRetido - $this->oTabelaIrrf->r33_deduzi;  
    /**
     * Buscamos o valor já retido no mes e deduzimos do valor retido.
     */
    $oDaoNota         = db_utils::getDao("empnota");
    $iMesBase         = date("m", db_getsession("DB_datausu"));
    $iAnoBase         = db_getsession("DB_anousu");
    $iInstit          = db_getsession("DB_instit");
    $sSqlValorRetido  = "select sum(e23_valorretencao) as valorRetido                               ";
    $sSqlValorRetido .= "  from retencaoreceitas                                                    ";
    $sSqlValorRetido .= "       inner join retencaopagordem on e23_retencaopagordem = e20_sequencial";
    $sSqlValorRetido .= "       inner join retencaotiporec  on e23_retencaotiporec  = e21_sequencial";
    $sSqlValorRetido .= "       inner join pagordemnota     on e20_pagordem         = e71_codord    ";
    $sSqlValorRetido .= "       inner join empnota          on e71_codnota          = e69_codnota   ";
    $sSqlValorRetido .= "       inner join empempenho       on e69_numemp           = e60_numemp    ";
    $sSqlValorRetido .= "       inner join cgm              on e60_numcgm           = cgm.z01_numcgm    ";
    $sSqlValorRetido .= "       left  join pagordemconta    on e49_codord           = e20_pagordem  ";
    $sSqlValorRetido .= "       left  join cgm cgmordem     on e49_numcgm           = cgmordem.z01_numcgm  ";
    $sSqlValorRetido .= " where e69_anousu                       = {$iAnoBase}                     ";
    $sSqlValorRetido .= "   and extract(month from e23_dtcalculo) = {$iMesBase}                     ";
    $sSqlValorRetido .= "   and (case when e49_numcgm is null then cgm.z01_cgccpf = '{$this->iCgcCpf}' ";
    $sSqlValorRetido .= "        else cgmordem.z01_cgccpf = '{$this->iCgcCpf}' end)                 ";
    $sSqlValorRetido .= "   and e23_recolhido is true                                               ";
    $sSqlValorRetido .= "   and e23_ativo is true                                               ";
    $sSqlValorRetido .= "   and e71_anulado  is false                                               ";
    $sSqlValorRetido .= "   and e21_retencaotipocalc = {$this->iTipo}                               ";
    $rsValorRetido    = $oDaoNota->sql_record($sSqlValorRetido);
    if ($oDaoNota->numrows > 0) {

      $nValorJaRetido = db_utils::fieldsMemory($rsValorRetido, 0)->valorretido;
      $nValorRetido  -= $nValorJaRetido;
      
    }
   /**
     * caso o usuário marcou alguma outra retencao, e caso ela possuir retencao, 
     * calculamos o valor ja retido desses calculos
     * Buscamos o valor já retido no mes e deduzimos do valor retido.
     */
    if (count($this->aCodigoMovimentos) > 0) {
    
      $sWhereIn         = implode(",",$this->aCodigoMovimentos);
      $sSqlValorRetido  = "select sum(e23_valorretencao) as valorRetido                               ";
      $sSqlValorRetido .= "  from retencaoreceitas                                                    ";
      $sSqlValorRetido .= "       inner join retencaopagordem  on e23_retencaopagordem = e20_sequencial";
      $sSqlValorRetido .= "       inner join retencaotiporec   on e23_retencaotiporec  = e21_sequencial";
      $sSqlValorRetido .= "       inner join pagordemnota      on e20_pagordem         = e71_codord    ";
      $sSqlValorRetido .= "       inner join empnota           on e71_codnota          = e69_codnota   ";
      $sSqlValorRetido .= "       inner join empempenho        on e69_numemp           = e60_numemp    ";
      $sSqlValorRetido .= "       inner join cgm              on e60_numcgm           = cgm.z01_numcgm    ";
      $sSqlValorRetido .= "       left  join pagordemconta    on e49_codord           = e20_pagordem  ";
      $sSqlValorRetido .= "       left  join cgm cgmordem     on e49_numcgm           = cgmordem.z01_numcgm  ";
      $sSqlValorRetido .= "       inner join retencaoempagemov on e23_sequencial       = e27_retencaoreceitas ";
      $sSqlValorRetido .= " where e69_anousu                       = {$iAnoBase}                     ";
      $sSqlValorRetido .= "   and extract(month from e23_dtcalculo) = {$iMesBase}                     ";
      $sSqlValorRetido .= "   and (case when e49_numcgm is null then cgm.z01_cgccpf = '{$this->iCgcCpf}' ";
      $sSqlValorRetido .= "        else cgmordem.z01_cgccpf = '{$this->iCgcCpf}' end)                 ";
      $sSqlValorRetido .= "   and e23_recolhido is false                                              ";
      $sSqlValorRetido .= "   and e23_ativo     is true                                               ";
      $sSqlValorRetido .= "   and e71_anulado  is false                                               ";
      $sSqlValorRetido .= "   and e27_principal is true                                               ";
      $sSqlValorRetido .= "   and e21_retencaotipocalc = {$this->iTipo}                               ";
      $sSqlValorRetido .= "   and e27_empagemov in({$sWhereIn})                                        ";
      $rsValorRetido    = $oDaoNota->sql_record($sSqlValorRetido);
      if ($oDaoNota->numrows > 0) {
      
        $nValorJaRetido = db_utils::fieldsMemory($rsValorRetido, 0)->valorretido;
        $nValorRetido  -= $nValorJaRetido;
      
      }
    }
    if ($nValorRetido < 0) {
      $nValorRetido = 0;
    }
    return $nValorRetido;

  }
  
  /**
   * 
   * @see iCalculoRetencao::setDeducao()
   */
  function setDeducao($nValorDeducao) {
    $this->nValorDeducao = $nValorDeducao;
  }
  
  /**
   * Retorna a tabela de IRRF conforme a 
   * @return boolean;
   */
  function getTabelaIRRF() {
  	
    /*
     * Retorna a tabela do imposto de renda que devemos usar.
     */
    $oDaoInssIrf     = db_utils::getDao("inssirf");
    $sSqlTabelaIRRF  = "select r33_inic,                                               ";
    $sSqlTabelaIRRF .= "       r33_fim,                                                ";
    $sSqlTabelaIRRF .= "       r33_deduzi,                                             ";
    $sSqlTabelaIRRF .= "       r33_perc                                                ";
    $sSqlTabelaIRRF .= "  from inssirf                                                 ";
    $sSqlTabelaIRRF .= " where {$this->nValorBaseCalculo} between r33_inic and r33_fim ";
    $sSqlTabelaIRRF .= "   and r33_codtab = 1                                          ";
    $sSqlTabelaIRRF .= "   and r33_instit = " . db_getsession("DB_instit");    
    $sSqlTabelaIRRF .= " order by r33_anousu desc,                                     ";
    $sSqlTabelaIRRF .= "          r33_mesusu desc  limit 1                             ";
    $rsTabelaIrrf    = $oDaoInssIrf->sql_record($sSqlTabelaIRRF);
    if ($oDaoInssIrf->numrows == 1) {
      
      $this->oTabelaIrrf  = db_utils::fieldsMemory($rsTabelaIrrf, 0);
      
    } else {
      throw new Exception("Erro [1] -Não Foi encontrado nenhuma tabela de cáculo de IRRF.\nOperacão cancelada"); 
    }
    return true;
  }
  /**
   * Realiza o calculo da base de calculo da retencao
   *
   */
  function calculaBasedeCalculo() {
    
    /* 
     * calculo da base calculo;
     * 1 - Buscamos todos as notas liquidadas do CGM(cpf) dentro do mes e
     *     somamos todas elas, e usamos como base de calculo inicial.
     * 2 - depois deduzimos as deducoes já cadastradas.
     */
    
    $nValorBaseCalculo = 0;
    if (empty($this->iCgcCpf)) {
      throw new Exception("Erro [2] CPF/CNPJ não Informado!\nOperação cancelada");
    }
    
    $oDaoNota   = db_utils::getDao("empnota");
    $iMesBase   = date("m", db_getsession("DB_datausu"));
    $iAnoBase   = db_getsession("DB_anousu");
    $iInstit    = db_getsession("DB_instit");
    if ($this->dtBaseCalculo != "") {
      
      
      $iMesBase  = date("m", strtotime($this->dtBaseCalculo));
      $iAnoBase  = date("Y", strtotime($this->dtBaseCalculo));
      
      
    }
    
    //Calculamos todos os valores já retidos de inss para o mes.
    $sSqlInssRetido  = "select sum(e23_valorretencao) as valorRetido                               ";
    $sSqlInssRetido .= "  from retencaoreceitas                                                    ";
    $sSqlInssRetido .= "       inner join retencaopagordem on e23_retencaopagordem = e20_sequencial";
    $sSqlInssRetido .= "       inner join retencaotiporec  on e23_retencaotiporec  = e21_sequencial";
    $sSqlInssRetido .= "       inner join pagordemnota     on e20_pagordem         = e71_codord    ";
    $sSqlInssRetido .= "       inner join empnota          on e71_codnota          = e69_codnota   ";
    $sSqlInssRetido .= "       inner join empempenho       on e69_numemp           = e60_numemp    ";
    $sSqlInssRetido .= "       inner join cgm              on e60_numcgm           = cgm.z01_numcgm    ";
    $sSqlInssRetido .= "       left  join pagordemconta    on e49_codord           = e20_pagordem  ";
    $sSqlInssRetido .= "       left  join cgm cgmordem     on e49_numcgm           = cgmordem.z01_numcgm  ";
    $sSqlInssRetido .= " where e69_anousu                       = {$iAnoBase}                     ";
    $sSqlInssRetido .= "   and extract(month from e23_dtcalculo) = {$iMesBase}                     ";
    $sSqlInssRetido .= "   and (case when e49_numcgm is null then cgm.z01_cgccpf = '{$this->iCgcCpf}'  ";
    $sSqlInssRetido .= "        else cgmordem.z01_cgccpf = '{$this->iCgcCpf}' end)                 ";
    $sSqlInssRetido .= "   and e23_ativo is true                                                   ";
    $sSqlInssRetido .= "   and e71_anulado  is false                                               ";
    $sSqlInssRetido .= "   and e21_retencaotipocalc in(3,7)                                         ";
    //die($sSqlInssRetido);
    $rsInssRetido    = $oDaoNota->sql_record($sSqlInssRetido);
    if ($oDaoNota->numrows > 0) {
      $this->nValorDeducao += db_utils::fieldsMemory($rsInssRetido, 0)->valorretido;
    }
    
    /*
     * Buscamos nessa consulta, todos os valores pagos no mes ao credor.
     */
    $sSqlNotas  = "select coalesce(sum((case when c71_coddoc = 5 then c70_valor when c71_coddoc = 6 then c70_valor *-1 end)),0) as total";
    $sSqlNotas .= "  from conlancamord                                          ";
    $sSqlNotas .= "       inner join conlancam    on c70_codlan =  c80_codlan      ";
    $sSqlNotas .= "       inner join conlancamdoc on c70_codlan = c71_codlan    ";
    $sSqlNotas .= "       inner join pagordem on c80_codord = e50_codord        ";
    $sSqlNotas .= "       inner join empempenho   on e50_numemp = e60_numemp      ";
    $sSqlNotas .= "       inner join cgm              on e60_numcgm           = cgm.z01_numcgm    ";
    $sSqlNotas .= "       left  join pagordemconta    on e49_codord           = e50_codord  ";
    $sSqlNotas .= "       left  join cgm cgmordem     on e49_numcgm           = cgmordem.z01_numcgm  ";
    $sSqlNotas .= " where extract (year from c70_data)  = {$iAnoBase}        ";
    $sSqlNotas .= "   and extract (month from c70_data)  = {$iMesBase}        "; 
    $sSqlNotas .= "   and (case when e49_numcgm is null then cgm.z01_cgccpf = '{$this->iCgcCpf}'  ";
    $sSqlNotas .= "        else cgmordem.z01_cgccpf = '{$this->iCgcCpf}' end)                      ";
    $rsNotas    = $oDaoNota->sql_record($sSqlNotas);
    
    if ($oDaoNota->numrows > 0) {

      $oNotas            = db_utils::fieldsMemory($rsNotas, 0);
      $nValorBaseCalculo = $oNotas->total;
      
    }
    if ($nValorBaseCalculo < 0) {
      $nValorBaseCalculo = 0;
    }
    $this->nValorBaseCalculo = ($nValorBaseCalculo+$this->nValorNota)-$this->nValorDeducao;
    return $this->nValorBaseCalculo;
  }
  /**
   *
   * @see iCalculoRetencao::setAliquota()
   */
  function setAliquota($nValorAliquota) {
    $this->nValorAliquota = $nValorAliquota; 
  }
  
  function getValorBaseCalculo() {
    return $this->nValorBaseCalculo;
  }
  
  function getAliquota() {
    return $this->nAliquota;
  }

/**
   * Seta o valor da base de calculo
   *
   * @param float8 $nValorBaseCalculo valor da base de calculo
   */
  function setBaseCalculo($nValorBaseCalculo) {
    $this->nValorBaseCalculo = $nValorBaseCalculo;
  }
  
    /**
   * Define o valor da nota
   *
   * @param float $nValorNota valor da nota a ser contabilizado na retencao.
   */
  function setValorNota($nValorNota) {
    
    $this->nValorNota = $nValorNota;
  }
  
  /**
   * Define a data base para calculo das retencoes;
   *
   * @param string $dtDataBase data base para caculo formato dd/mm/YYY
   */
  function setDataBase($dtDataBase) {
    
    $dtDataBase = implode("-", array_reverse(explode("/", $dtDataBase)));
    $this->dtBaseCalculo = $dtDataBase;
    
  }
  
  /**
   * Define  o Codigo dos Movimentos
   *
   * @param unknown_type $aCodigosMovimentos
   */
  function setCodigoMovimentos($aCodigosMovimentos) {
     $this->aCodigoMovimentos = $aCodigosMovimentos;  
  }
}
?>