<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("model/relatorioContabil.model.php");
require_once ("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");
require_once ("classes/db_orcprojativ_classe.php");
 

class AnexoSeguridadeSocial extends RelatoriosLegaisBase {
  
  /**
   * Guarda o código da "OrigemFase"
   * 1 - Orçamento
   * 2 - Empenhado 
   * 3 - Liquidado
   * 4 - Pago
   * 
   * @var Integer
   */
  protected $iOrigemFase;
  
  /**
   * Array de faz um cache dos tipos de processos para cada Processo. 
   * Se Processo encontra-se no array não ha necessidade de se fazer a query novamente 
   *
   * @var array
   */
  protected $aTipoProcesso = array();
  
  protected $iCodigoUsuario;
  
  /**
   * Método Construtor
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  /**
   * Seta a Variável Origem
   *
   * @param Integer $iOrigem
   */
  public function setOrigem($iOrigemFase) {    
    $this->iOrigemFase = $iOrigemFase;
  }
  
  /**
   * Retorna o valor da Propriedade $iOrigem
   *
   * @return Integer
   */
  public function getOrigem() {    
    return $this->iOrigemFase;
  }

  public function setUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }
  
  public function getUsuario() {
    return $this->iCodigoUsuario;
  }
  
  /**
   * Retorna as Linhas do Relatório
   *
   */
  public function getDados() { 
    
    /**
     * Se for selecionado "Origem/Fase" como Orçamento deve selecionar como padrão o mes de janeiro
     */
    $sDataInicial     = "{$this->iAnoUsu}-01-01";
    
    /**
     * Para descobrir data Final
     */
    $oDaoPeriodo      = db_utils::getDao("periodo");
    $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo        = db_query($sSqlDadosPeriodo);
    $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0);
    $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
    $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
    
    /**
     * Objeto Com os Filtros salvo para o relatório
     */
    $oDadosFiltro     = $this->oRelatorioLegal->getParametrosUsuario($this->getUsuario());
    /**
     * Condição para busca no banco
     */
    $sWhereDespesa    = " o58_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa   .= $this->getWhereFiltro($oDadosFiltro);
    
    $rsDotacao        = db_dotacaosaldo(6 ,2, 2, true, $sWhereDespesa, $this->iAnoUsu,$sDataInicial, $sDataFinal );  
    $aRetorno         = array();
    
    /**
     * Percorre o ResultSet organizando os dados dentro do array $aRetorno
     */
    for ($iRow = 0; $iRow < pg_num_rows($rsDotacao); $iRow++) {
      
      $oDespesa    = db_utils::fieldsMemory($rsDotacao, $iRow);
      $nValorTotal = 0;
     
      
      switch ($this->getOrigem()) {
        
        case '1': //"Orçamento"
          
          $nValorTotal = $oDespesa->dot_ini;
          break;
        case '2'://"Empenhado"

          $nValorTotal = ($oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado);
          break;
        case '3': //"Liquidado"
          
          $nValorTotal = $oDespesa->liquidado_acumulado;
          break;
        case '4':  //"Pago")
          
          $nValorTotal = $oDespesa->pago_acumulado;
          break;
      }
      
      /**
       * Verifica se o Função atual já está setada dentro do array de retorno ($aRetorno)
       */
      if (!isset($aRetorno[$oDespesa->o58_funcao])) {
        
        $oFuncao                    = new stdClass();
        $oFuncao->codigoFuncao      = $oDespesa->o58_funcao;
        $oFuncao->descr             = $oDespesa->o52_descr;
        $oFuncao->nProjeto          = 0;
        $oFuncao->nAtividade        = 0;
        $oFuncao->nOperacaoEspecial = 0;
        $oFuncao->nTotal            = 0;
        $oFuncao->subfuncao         = array();
        $aRetorno[$oDespesa->o58_funcao] = $oFuncao;
      } else {
        $oFuncao = $aRetorno[$oDespesa->o58_funcao];
      }
      
      /**
       * Verifica se a Sub-Função atual já está setada dentro do array de retorno ($aRetorno) na possição 
       * $oDespesa->o58_funcao
       */
      if (!isset($aRetorno[$oDespesa->o58_funcao]->subfuncao[$oDespesa->o58_subfuncao])) {

        $oSubFuncao                    = new stdClass();
        $oSubFuncao->codigoSubFuncao   = $oDespesa->o58_subfuncao;
        $oSubFuncao->descr             = $oDespesa->o53_descr;
        $oSubFuncao->nProjeto          = 0;
        $oSubFuncao->nAtividade        = 0;
        $oSubFuncao->nOperacaoEspecial = 0;
        $oSubFuncao->nTotal            = 0;
        $oSubFuncao->projetos          = array();
        
        $aRetorno[$oDespesa->o58_funcao]->subfuncao[$oDespesa->o58_subfuncao] = $oSubFuncao;
      } else {
        $oSubFuncao = $aRetorno[$oDespesa->o58_funcao]->subfuncao[$oDespesa->o58_subfuncao];
      }
      
      /**
       * Verifica se o projeto atual já está setada dentro do array de retorno ($aRetorno) na possição 
       * $oDespesa->o58_projativ
       */
      if (!isset($aRetorno[$oDespesa->o58_funcao]->subfuncao[$oDespesa->o58_subfuncao]->projetos[$oDespesa->o58_projativ])) {

        $oProjeto                    = new stdClass();
        $oProjeto->codigoProjeto     = $oDespesa->o58_projativ;
        $oProjeto->descr             = $oDespesa->o55_descr;
        $oProjeto->nProjeto          = 0;
        $oProjeto->nAtividade        = 0;
        $oProjeto->nOperacaoEspecial = 0;
        $oProjeto->nTotal            = 0;
        $oProjeto->iTipoProcesso     = $this->getTipoProcesso($oDespesa->o58_projativ);
        $aRetorno[$oDespesa->o58_funcao]->subfuncao[$oDespesa->o58_subfuncao]->projetos[$oDespesa->o58_projativ] = $oProjeto;
      } else {
        $oProjeto = $aRetorno[$oDespesa->o58_funcao]->subfuncao[$oDespesa->o58_subfuncao]->projetos[$oDespesa->o58_projativ];
      }
      switch ($oProjeto->iTipoProcesso) {
      
        case 1:
          
          $oFuncao->nProjeto             += $nValorTotal;
          $oFuncao->nTotal               += $nValorTotal;
          $oSubFuncao->nProjeto          += $nValorTotal;
          $oSubFuncao->nTotal            += $nValorTotal;
          $oProjeto->nProjeto            += $nValorTotal;
          $oProjeto->nTotal              += $nValorTotal;
          break;
        case 2:
          
          $oFuncao->nAtividade           += $nValorTotal;
          $oFuncao->nTotal               += $nValorTotal;
          $oSubFuncao->nAtividade        += $nValorTotal;
          $oSubFuncao->nTotal            += $nValorTotal;
          $oProjeto->nAtividade          += $nValorTotal;
          $oProjeto->nTotal              += $nValorTotal;
          break;
        case 3:
          
          $oFuncao->nOperacaoEspecial    += $nValorTotal;
          $oFuncao->nTotal               += $nValorTotal;
          $oSubFuncao->nOperacaoEspecial += $nValorTotal;
          $oSubFuncao->nTotal            += $nValorTotal;
          $oProjeto->nOperacaoEspecial   += $nValorTotal;
          $oProjeto->nTotal              += $nValorTotal;
          break;
      }
    }
    ksort($aRetorno);
    return $aRetorno;
    
  }
  
  /**
   * Retorna O tipo do Processo
   *
   * @param Integer $iCodigoProjeto
   * @return Integer
   */
  protected function getTipoProcesso($iCodigoProjeto) {
    
    if (array_key_exists($iCodigoProjeto, $this->aTipoProcesso)) {
      return $this->aTipoProcesso[$iCodigoProjeto];
    } else {
      
      $clOrcProjAtiv = new cl_orcprojativ();
      // $o55_anousu=null,$o55_projativ=null,$campos="*",$ordem=null,$dbwhere=""){
      $sSQl = $clOrcProjAtiv->sql_query_file($this->iAnoUsu, $iCodigoProjeto, "distinct o55_tipo");
      
      $rsProjetoAtividade = $clOrcProjAtiv->sql_record($sSQl);
      $oProjetoAtividade  = db_utils::fieldsMemory($rsProjetoAtividade, 0);
      $this->aTipoProcesso[$iCodigoProjeto] = $oProjetoAtividade->o55_tipo;
      
    }
    
    return $this->aTipoProcesso[$iCodigoProjeto];
  } 
  
  protected function getWhereFiltro($oDadosFiltro) {
    
    $sWhereDespesa = "";
    /**
     * Verifica se Tem filtro por Orgão
     */
    if (count($oDadosFiltro->orgao->valor) > 0) {

      if ($oDadosFiltro->orgao->operador == 'notin') {
        $oDadosFiltro->orgao->operador = "not in";
      }
      $sWhereDespesa .= " and o58_orgao {$oDadosFiltro->orgao->operador} (".implode(",", $oDadosFiltro->orgao->valor).")";
    }

    /**
     * Verifica se Tem filtro por Unidade
     */
    if (count($oDadosFiltro->unidade->valor) > 0) {

      if ($oDadosFiltro->unidade->operador == 'notin') {
        $oDadosFiltro->unidade->operador = "not in";
      }
      $sWhereDespesa .= " and o58_unidade {$oDadosFiltro->unidade->operador} (".implode(",", $oDadosFiltro->unidade->valor).")";
    }    
    
    /**
     * Verifica se Tem filtro por Função
     */
    if (count($oDadosFiltro->funcao->valor) > 0) {

      if ($oDadosFiltro->funcao->operador == 'notin') {
        $oDadosFiltro->funcao->operador = "not in";
      }
      $sWhereDespesa .= " and o58_funcao {$oDadosFiltro->funcao->operador} (".implode(",", $oDadosFiltro->funcao->valor).")";
    }    
    
    /**
     * Verifica se Tem filtro por SubFunção
     */
    if (count($oDadosFiltro->subfuncao->valor) > 0) {

      if ($oDadosFiltro->subfuncao->operador == 'notin') {
        $oDadosFiltro->subfuncao->operador = "not in";
      }
      $sWhereDespesa .= " and o58_subfuncao {$oDadosFiltro->subfuncao->operador} (".implode(",", $oDadosFiltro->subfuncao->valor).")";
    }
        
    /**
     * Verifica se Tem filtro por Programa
     */
    if (count($oDadosFiltro->programa->valor) > 0) {

      if ($oDadosFiltro->programa->operador == 'notin') {
        $oDadosFiltro->programa->operador = "not in";
      }
      $sWhereDespesa .= " and o58_programa {$oDadosFiltro->programa->operador} (".implode(",", $oDadosFiltro->programa->valor).")";
    }
    
    /**
     * Verifica se Tem filtro por Projeto
     */
    if (count($oDadosFiltro->projativ->valor) > 0) {

      if ($oDadosFiltro->projativ->operador == 'notin') {
        $oDadosFiltro->projativ->operador = "not in";
      }
      $sWhereDespesa .= " and o58_projativ {$oDadosFiltro->projativ->operador} (".implode(",", $oDadosFiltro->projativ->valor).")";
    }
    
    /**
     * Verifica se Tem filtro por Elemento
     */
    if (count($oDadosFiltro->elemento->valor) > 0) {

      if ($oDadosFiltro->elemento->operador == 'notin') {
        $oDadosFiltro->elemento->operador = "not in";
      }
      $sWhereDespesa .= " and o56_elemento {$oDadosFiltro->elemento->operador} (".implode(",", $oDadosFiltro->elemento->valor).")";
    }
    
    /**
     * Verifica se Tem filtro por recurso
     */
    if (count($oDadosFiltro->recurso->valor) > 0) {

      if ($oDadosFiltro->recurso->operador == 'notin') {
        $oDadosFiltro->recurso->operador = "not in";
      }
      $sWhereDespesa .= " and o58_recurso {$oDadosFiltro->recurso->operador} (".implode(",", $oDadosFiltro->recurso->valor).")";
    }
    
    return $sWhereDespesa;
    
  }
  
  
  
}
?>