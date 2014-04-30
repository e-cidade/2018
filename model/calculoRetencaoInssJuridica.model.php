<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

class calculoRetencaoInssJuridica implements iCalculoRetencao {
  
  /**
   * tipo do calculo
   * @var integer
   */
  private $iTipo = 3;
  
  /**
   * Valor da base de calculo
   *
   * @var float
   */
  private $nValorBaseCalculo = 0;
  
  /** Valor da Dedução informado pelo usuário.
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
  private $oTabelaInss = null;
  
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
  
  /**
   * Códigos Auxiliares doe Movimentos 
   *
   * @var array
   */
  private $aCodigoMovimentos = array();
  
  /** metodo construtor da classe
   * @param integer $iTipo tipo do calculo 1 - pessoa fisica
   */
  function __construct($iCgcCpf, $iTipo = 3) {

    $this->iTipo   = $iTipo;
    $this->iCgcCpf = (string)$iCgcCpf;
    
  }
  
  /**
   * 
   * @see iCalculoRetencao::calculaBasedeCalculo()
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
    
    if ($this->nValorNota > 0) {
      $nValorBaseCalculo = $this->nValorNota - $this->nValorDeducao;
    } else {  
      $nValorBaseCalculo = $this->getValorBaseCalculo() - $this->nValorDeducao;
    }
    if ($nValorBaseCalculo < 0) {
      $nValorBaseCalculo = 0;
    }
    $this->nValorBaseCalculo = $nValorBaseCalculo;
    return $nValorBaseCalculo;
  
  }
  
  /**
   * 
   * @see iCalculoRetencao::calcularRetencao()
   */
  function calcularRetencao() {

    $this->nValorBaseCalculo = $this->calculaBasedeCalculo();
    $nValorRetido = 0;
    /**
     * Calculamos o valor da retencao, multiplicando  a base de calculo pela aliquota;
     */
   if (empty($this->nAliquota) || $this->nAliquota == 0) {
      throw  new Exception("Erro [1] Valor da Aliquota Inválido!");
    }
    
    $nValorRetido = $this->nValorBaseCalculo * ($this->nAliquota/100);
    if ($nValorRetido < 0) {
      $nValorRetido = 0;
    }
    return $nValorRetido;
  }
  
  /**
   * 
   * @see iCalculoRetencao::getAliquota()
   */
  function getAliquota() {
    return $this->nAliquota;
  }
  
  /**
   * 
   * @see iCalculoRetencao::getValorBaseCalculo()
   */
  function getValorBaseCalculo() {
    return $this->nValorBaseCalculo;
  }
  
  /**
   * 
   * @param float $nValorAliquota 
   * @see iCalculoRetencao::setAliquota()
   */
  function setAliquota($nValorAliquota) {
    $this->nAliquota = $nValorAliquota; 
  }
  
  /**
   * 
   * @param $nValorDeducao 
   * @see iCalculoRetencao::setDeducao()
   */
  function setDeducao($nValorDeducao) {
    $this->nValorDeducao = $nValorDeducao;
  }
  
  /** Retorna a tabela de Inss conforme base de calculo  
   * @return boolean;
   */
  function getTabelaInss() {
  	
    /*
     * Buscamos o codigo da tabela a ser Deduzida
     * devemos somar +2 no codigo da tabela encontrada,
     * sempre pegando o ultimo registro
     * 
     */
     $oDaoCfPess    = db_utils::getDao("cfpess");
     $sSqlCodTabela = $oDaoCfPess->sql_query(null,
                                             null,
                                             null,
                                             "r11_tbprev",
                                             "r11_anousu desc,
                                             r11_mesusu desc
                                             limit 1",
                                             "r11_instit = ".db_getsession("DB_instit")
                                             );
    $rsCodTabela  = $oDaoCfPess->sql_record($sSqlCodTabela);
    if ($oDaoCfPess->numrows == 0) {

      throw new Exception("Não há nenhuma configuracao de tabela de Inss!\nConfira.");
    }
    
    $iCodigoTabelaInss = (db_utils::fieldsMemory($rsCodTabela,0)->r11_tbprev+2);
    /*
     * Retorna a tabela do imposto de renda que devemos usar.
     */
    $oDaoInssIrf     = db_utils::getDao("inssirf");
    $sSqlTabelaINSS  = "select r33_inic,                                               ";
    $sSqlTabelaINSS .= "       r33_fim,                                                ";
    $sSqlTabelaINSS .= "       r33_deduzi,                                             ";
    $sSqlTabelaINSS .= "       r33_perc,                                               ";
    $sSqlTabelaINSS .= "       (select max(r33_fim)                                    ";
    $sSqlTabelaINSS .= "          from inssirf  a                                      ";  
    $sSqlTabelaINSS .= "         where a.r33_anousu = inssirf.r33_anousu               ";  
    $sSqlTabelaINSS .= "           and a.r33_mesusu = inssirf.r33_mesusu               ";  
    $sSqlTabelaINSS .= "           and a.r33_instit = inssirf.r33_instit               ";
    $sSqlTabelaINSS .= "           and a.r33_codtab = {$iCodigoTabelaInss}) as teto    ";  
    $sSqlTabelaINSS .= "  from inssirf                                                 ";
    $sSqlTabelaINSS .= " where {$this->nValorBaseCalculo} between r33_inic and r33_fim ";
    $sSqlTabelaINSS .= "   and r33_codtab = {$iCodigoTabelaInss}                       ";
    $sSqlTabelaINSS .= "   and r33_instit = ".db_getsession("DB_instit");
    $sSqlTabelaINSS .= " order by r33_anousu desc,                                     ";
    $sSqlTabelaINSS .= "          r33_mesusu desc  limit 1                             ";
    $rsTabelaInss    = $oDaoInssIrf->sql_record($sSqlTabelaINSS);
    if ($oDaoInssIrf->numrows == 1) {
      
      $this->oTabelaInss = db_utils::fieldsMemory($rsTabelaInss, 0);
      
    } else {
      throw new Exception("Erro [1] -Não Foi encontrado nenhuma tabela de cáculo de INSS.\nOperacão cancelada"); 
    }
    return true;
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