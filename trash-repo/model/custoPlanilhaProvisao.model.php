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


require_once ('interfaces/iCustoPlanilha.interface.php');

/**
 * custos e pagamento de 13º
 * @package Custos
 */

final class custoPlanilhaProvisao implements iCustoPlanilha {
  
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
    $sSqlCusto  = "select rh02_seqpes, "; 
    $sSqlCusto .= "       tipo, ";
    $sSqlCusto .= "       r35_mesusu, ";
    $sSqlCusto .= "       rh23_codele, ";
    $sSqlCusto .= "       sum(case when r35_pd = 2 then r35_valor*-1 else r35_valor end) as valor "; 
    $sSqlCusto .= "  from ( ";
    $sSqlCusto .= "        SELECT r35_valor, ";
    $sSqlCusto .= "               r35_mesusu, ";
    $sSqlCusto .= "               r35_rubric, ";
    $sSqlCusto .= "               r35_pd, ";
    $sSqlCusto .= "               rh27_tipo, ";
    $sSqlCusto .= "               rh23_codele, ";
    $sSqlCusto .= "               'r35'::text as tipo, ";
    $sSqlCusto .= "                rh02_seqpes ";
    $sSqlCusto .= "          from gerfs13";
    $sSqlCusto .= "               inner join rhrubricas    on rh27_rubric = r35_rubric ";
    $sSqlCusto .= "                                       and rh27_instit = r35_instit ";
    $sSqlCusto .= "               inner join rhrubelemento on rh23_rubric = rh27_rubric ";
    $sSqlCusto .= "                                       and rh23_instit = rh27_instit ";
    $sSqlCusto .= "               inner join rhpessoalmov  on rh02_regist = r35_regist ";
    $sSqlCusto .= "         where r35_anousu  = {$this->iAnoUsu} ";
    $sSqlCusto .= "           and r35_mesusu  = {$this->iMesUsu} ";
    $sSqlCusto .= "           and rh02_mesusu = {$this->iMesUsu} ";
    $sSqlCusto .= "           and rh02_anousu = {$this->iAnoUsu} ";
    $sSqlCusto .= "           and rh27_pd != 3 '";
    $sSqlCusto .= "         order by rh02_seqpes, rh23_codele ";
    $sSqlCusto .= " ) as x ";
    $sSqlCusto .= " group by rh02_seqpes, "; 
    $sSqlCusto .= "      tipo,"; 
    $sSqlCusto .= "      r35_mesusu, ";
    $sSqlCusto .= "      rh23_codele ";
    $sSqlCusto .= " order by rh02_seqpes, ";
    $sSqlCusto .= "      tipo ";
    $rsCustos      = db_query($sSqlCusto);
    $iTotalCustos  = pg_num_rows($rsCustos);
    
    require_once("model/custoPlanilhaLinha.model.php");
    require_once("model/custorateio.model.php");
    require_once("model/custoRegraRateio.model.php");
    $oDaoPesLocalTrab = db_utils::getDao("rhpeslocaltrab");
    /**
     * Percorremos todos os custos encontrados e Incluimos na planilha
     */
    for ($iCusto = 0; $iCusto < $iTotalCustos; $iCusto++) {
      
      $oCustoFolha = db_utils::fieldsMemory($rsCustos, $iCusto);
      
      /**
       * Procuramos todos os locais de trabalho pelo seqpes.
       * apenas funcionarios com local de trabalho sera calculado o custo
       */
      $sSqlLocalTrab = $oDaoPesLocalTrab->sql_query_custocriterio(null,"*",null,
                                                                  "rh56_seqpes = {$oCustoFolha->rh02_seqpes}"
                                                                 );
      $rsLocalTrab  = $oDaoPesLocalTrab->sql_record($sSqlLocalTrab);
      if ($oDaoPesLocalTrab->numrows > 0) {                                                           
        
        for ($iLocal = 0; $iLocal < $oDaoPesLocalTrab->numrows; $iLocal++) {
          
          $oLocalTrab  = db_utils::fieldsMemory($rsLocalTrab, $iLocal);
          $nQuantidade = $oLocalTrab->rh56_quantidadecusto;
          $nValor      = round(($oCustoFolha->valor*$oLocalTrab->rh56_percentualcusto)/100,2);
          /**
           * aplicamos as regras do rateio 
           */
          $oCriterioRateio  = new custorateio($oLocalTrab->rh86_criteriorateio);
          $aRateioaPlicado  = $oCriterioRateio->aplicarRegras($nQuantidade, $nValor);
          foreach ($aRateioaPlicado as $oRateioAplicado) {
            
            $oCustoLinha   = new custoPlanilhaLinha(null,
                                                    $oRateioAplicado->nQuantidade,
                                                    $oRateioAplicado->nValor,
                                                    $oRateioAplicado->iContaPlano,
                                                    $oCustoFolha->rh23_codele
                                                   );
            $oCustoLinha->setOrigem(2);
            $oCustoLinha->setCodigoOrigem($oLocalTrab->rh56_seq);
            $oCustoLinha->setAutomatico(true);
            $this->addCusto($oCustoLinha);                                        
          }
        }
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
    $sWhere         .= "and cc17_custoplanilhaorigem  = 2";      
    $sSqlCustosProcessadosMes = $oDaoCustoLinha->sql_query(null, "*", null, $sWhere);
    $rsCustosProcessadosMes   = $oDaoCustoLinha->sql_record($sSqlCustosProcessadosMes);
    $iNumRowsCustos = $oDaoCustoLinha->numrows;
    for ($iCusto = 0; $iCusto < $iNumRowsCustos; $iCusto++) {
      
      $oCustoConsulta = db_utils::fieldsMemory($rsCustosProcessadosMes, $iCusto);
      $oCustoRemover = new custoPlanilhaLinha($oCustoConsulta->cc17_sequencial);
      $oCustoRemover->setOrigem(2)->remover();
      
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