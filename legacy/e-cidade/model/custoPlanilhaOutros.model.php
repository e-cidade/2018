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


require_once ('interfaces/iCustoPlanilha.interface.php');

/**
 * custos de Consumo de almoxarifado
 * @package Custos
 */

final class custoPlanilhaOutros implements iCustoPlanilha {
  
  /**
   * Filtros
   *
   * @var string
   */
  protected $sWhere = null;
  
  /**
   * Ano base
   *
   * @var integer
   */
  protected $iAnoUsu = null;
  
  /**
   * Mes base 
   *
   * @var integer
   */
  protected $iMesUsu = null;
  
  
  /**
   * custos Calculados pela categoria
   *
   * @var array
   */
  protected $aCustos = array();
  /**
   *  
   * 
   */
  public function __construct() {

  }
  
  /**
   * 
   * @see iCustoPlanilha::getCustos()
   */
  public function getCustos() {

    return $this->aCustos;
  }
  
  /**
   * Processa dos dados da planilha de custo
   * @param integer $iMesBase mes base para processamento 
   * @param integer $iAnoBase ano base para  processamento 
   * @see iCustoPlanilha::processarDados()
   * 
   * @return custoPlanilhaConsumoAlmox
   */
  public function processarDados($iMesBase, $iAnoBase) {

    
    $this->iAnoUsu = $iAnoBase;
    $this->iMesUsu = $iMesBase;
    $sSqlCustos  = "SELECT m52_numemp, ";
    $sSqlCustos .= "       m52_sequen, ";
    $sSqlCustos .= "       cc11_custocriteriorateio, ";
    $sSqlCustos .= "       cc11_sequencial, "; 
    $sSqlCustos .= "       sum(case when c53_tipo = 20 then c70_valor else c70_valor *-1 end) as valorliq, ";
    $sSqlCustos .= "       e64_codele, ";
    $sSqlCustos .= "       o56_elemento, ";
    $sSqlCustos .= "       m52_codordem, ";
    $sSqlCustos .= "       m52_codlanc, ";
    $sSqlCustos .= "       m52_valor- coalesce((select sum(coalesce(m36_vrlanu,0)) ";
    $sSqlCustos .= "                              from matordemitemanu "; 
    $sSqlCustos .= "                             where m36_matordemitem = m52_codlanc ),0) as valorcusto, ";
    $sSqlCustos .= "       m52_quant- coalesce((select sum(coalesce(m36_qtd,0)) ";
    $sSqlCustos .= "                              from matordemitemanu ";
    $sSqlCustos .= "                             where m36_matordemitem = m52_codlanc ),0) as qtdcusto ";
    $sSqlCustos .= "  from matordemitem ";
    $sSqlCustos .= "       inner join empempitem   on m52_numemp  = e62_numemp ";
    $sSqlCustos .= "                              and m52_sequen  = e62_sequen ";
    $sSqlCustos .= "       inner join empelemento  on e62_numemp  = e64_numemp ";
    $sSqlCustos .= "       inner join orcelemento  on e64_codele  = o56_codele ";
    $sSqlCustos .= "                              and o56_anousu  = {$this->iAnoUsu} ";
    $sSqlCustos .= "       inner join conlancamemp on c75_numemp  = e62_numemp ";
    $sSqlCustos .= "       inner join conlancam    on c70_codlan  = c75_codlan ";
    $sSqlCustos .= "       inner join conlancamdoc on c71_codlan  = c70_codlan ";
    $sSqlCustos .= "       inner join conhistdoc   on c71_coddoc  = c53_coddoc ";
    $sSqlCustos .= "       inner join matordemitemcustocriterio on m52_codlanc = cc11_matordemitem ";
    $sSqlCustos .= "       left  join matestoqueitemoc  on m73_codmatordemitem = m52_codlanc";
    $sSqlCustos .= "                                   and m73_cancelado is false";
    $sSqlCustos .= " where extract (year from c70_data)::integer = {$this->iAnoUsu} ";
    $sSqlCustos .= "   and extract(month from c70_data)::integer = {$this->iMesUsu}";
    $sSqlCustos .= "   and c53_tipo in(20,21) ";
    $sSqlCustos .= "   and (o56_elemento not like '34%' and o56_elemento not like '3339014%' and o56_elemento not like '3339036%'";
    $sSqlCustos .= "        and o56_elemento not like '3339039%')";
    $sSqlCustos .= "   and m73_codmatordemitem is null";
    $sSqlCustos .= " group by m52_numemp, ";
    $sSqlCustos .= "          m52_sequen, ";
    $sSqlCustos .= "          e64_codele, ";
    $sSqlCustos .= "          o56_elemento, ";
    $sSqlCustos .= "          m52_codordem, ";
    $sSqlCustos .= "          m52_valor,  ";
    $sSqlCustos .= "          m52_quant, ";
    $sSqlCustos .= "          cc11_sequencial, ";
    $sSqlCustos .= "          m52_codlanc, ";
    $sSqlCustos .= "          cc11_custocriteriorateio "; 
    $sSqlCustos .= " order by m52_numemp, ";
    $sSqlCustos .= "          m52_sequen ";
    $rsCustos      = db_query($sSqlCustos);
    $iTotalCustos  = pg_num_rows($rsCustos);
    require_once("model/custoPlanilhaLinha.model.php");
    require_once("model/custorateio.model.php");
    require_once("model/custoRegraRateio.model.php");
    /**
     * Percorremos todos os custos encontrados e Incluimos na planilha
     */
    for ($iCusto = 0; $iCusto < $iTotalCustos; $iCusto++) {
      
      $oCustoConsultado = db_utils::fieldsMemory($rsCustos, $iCusto);
      if ($oCustoConsultado->valorcusto == 0) {
        continue;
      }
      /**
       * aplicamos as regras do rateio 
       */
      $oCriterioRateio  = new custorateio($oCustoConsultado->cc11_custocriteriorateio);
      $aRateioaPlicado  = $oCriterioRateio->aplicarRegras($oCustoConsultado->qtdcusto, $oCustoConsultado->valorcusto);
      foreach ($aRateioaPlicado as $oRateioAplicado) {
        
        $oCustoLinha   = new custoPlanilhaLinha(null,
                                                $oRateioAplicado->nQuantidade,
                                                $oRateioAplicado->nValor,
                                                $oRateioAplicado->iContaPlano,
                                                $oCustoConsultado->e64_codele
                                               );
        $oCustoLinha->setOrigem(7);
        $oCustoLinha->setCodigoOrigem($oCustoConsultado->cc11_sequencial);
        $oCustoLinha->setAutomatico(true);
        $this->addCusto($oCustoLinha);                                        

      }
      
    }
    return $this;
    
  }
  
  /**
   * Define filtros para retorno de dados 
   * @param string $sWhere string com filtro para  os metodos de retorno de informacoes 
   * @return void 
   * @see iCustoPlanilha::setFilter()
   * @return custoPlanilhaConsumoAlmox
   */
  public function setFilter($sWhere) {
    
    if (!empty($sWhere)) {
      $this->sWhere = $sWhere;
    }
    
    return $this;
  }
  
  /**
   * adiciona custos a planilha
   *
   * @see iCustoPlanilha::addCusto() 
   * @param custoPlanilhaLinha $oCusto custos da planilha
   */
  public function addCusto(custoPlanilhaLinha $oCusto) {
    $this->aCustos[] = $oCusto;
  }
  
  
  public function save($iPlanilha) {
    
    /**
     * Consultamos e excluimos todos os custos do tipo no mes
     */
    $oDaoCustoLinha  = db_utils::getDao("custoplanilhaapuracao");
    require_once("model/custoPlanilhaLinha.model.php");
    $sWhere          = "cc17_custoplanilha = {$iPlanilha} ";      
    $sWhere         .= "and cc17_custoplanilhaorigem  = 7";      
    $sSqlCustosProcessadosMes = $oDaoCustoLinha->sql_query(null, "*", null, $sWhere);
    $rsCustosProcessadosMes   = $oDaoCustoLinha->sql_record($sSqlCustosProcessadosMes);
    $iNumRowsCustos = $oDaoCustoLinha->numrows;
    for ($iCusto = 0; $iCusto < $iNumRowsCustos; $iCusto++) {
      
      $oCustoConsulta = db_utils::fieldsMemory($rsCustosProcessadosMes, $iCusto);
      $oCustoRemover = new custoPlanilhaLinha($oCustoConsulta->cc17_sequencial);
      $oCustoRemover->setOrigem(7)->remover();
      
    }
    foreach ($this->aCustos as $oCusto) {
      $oCusto->save($iPlanilha);
    }
  }
  /**
   * 
   */
  public function __destruct() {

    
  }
}

?>