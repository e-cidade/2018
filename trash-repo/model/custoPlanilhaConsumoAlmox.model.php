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

final class custoPlanilhaConsumoAlmox implements iCustoPlanilha {
  
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
    $sSqlCustos    = "SELECT custoapropria.*, ";
    $sSqlCustos   .= "       m52_sequen, ";
    $sSqlCustos   .= "       m52_numemp, ";
    $sSqlCustos   .= "       e64_codele ";
    $sSqlCustos   .= "  from custoapropria ";
    $sSqlCustos   .= "       inner join matestoqueinimei on m82_codigo            = cc12_matestoqueinimei ";
    $sSqlCustos   .= "       inner join matestoqueini    on m82_matestoqueini     = m80_codigo ";
    $sSqlCustos   .= "       inner join matestoqueitem   on m71_codlanc           = m82_matestoqueitem ";
    $sSqlCustos   .= "       left  join matestoqueitemoc on m73_codmatestoqueitem = m71_codlanc ";
    $sSqlCustos   .= "       left  join matordemitem     on m73_codmatordemitem   = m52_codlanc ";
    $sSqlCustos   .= "       left  join empempitem       on m52_numemp            = e62_numemp ";
    $sSqlCustos   .= "                                  and m52_sequen            = e62_sequen ";
    $sSqlCustos   .= "       left join empelemento      on e62_numemp            = e64_numemp ";
    $sSqlCustos   .= " where extract(year from m80_data)::integer  = {$this->iAnoUsu} ";
    $sSqlCustos   .= "   and extract(month from m80_data)::integer = {$this->iMesUsu} ";
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
      
      /**
       * aplicamos as regras do rateio 
       */
      $oCriterioRateio  = new custorateio($oCustoConsultado->cc12_custocriteriorateio);
      $aRateioaPlicado  = $oCriterioRateio->aplicarRegras($oCustoConsultado->cc12_qtd, $oCustoConsultado->cc12_valor);
      foreach ($aRateioaPlicado as $oRateioAplicado) {
        
        $oCustoLinha   = new custoPlanilhaLinha(null,
                                                $oRateioAplicado->nQuantidade,
                                                $oRateioAplicado->nValor,
                                                $oRateioAplicado->iContaPlano,
                                                $oCustoConsultado->e64_codele
                                               );
        $oCustoLinha->setOrigem(3);
        $oCustoLinha->setCodigoOrigem($oCustoConsultado->cc12_sequencial);
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
    $sWhere         .= "and cc17_custoplanilhaorigem  = 3";      
    $sSqlCustosProcessadosMes = $oDaoCustoLinha->sql_query(null, "*", null, $sWhere);
    $rsCustosProcessadosMes   = $oDaoCustoLinha->sql_record($sSqlCustosProcessadosMes);
    $iNumRowsCustos = $oDaoCustoLinha->numrows;
    for ($iCusto = 0; $iCusto < $iNumRowsCustos; $iCusto++) {
      
      $oCustoConsulta = db_utils::fieldsMemory($rsCustosProcessadosMes, $iCusto);
      $oCustoRemover = new custoPlanilhaLinha($oCustoConsulta->cc17_sequencial);
      $oCustoRemover->setOrigem(3)->remover();
      
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