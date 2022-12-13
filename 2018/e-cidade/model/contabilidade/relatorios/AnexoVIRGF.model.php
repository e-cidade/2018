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

require_once ("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");
/**
 * classe para controle dos valores do Anexo VI da RGF
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

final class AnexoVIRGF extends RelatoriosLegaisBase {

  protected $oAnexoV;

  private $aCacheRecursos = array ();
  
  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   * Objeto->recursosVinculados -> array recursos[]->Objetosrecurso->disponibilidadebruta 
   *                                                                ->obrigacoesfinanceiras
   *                                                                ->disponibilidadeliquida 
   *                                                                ->descricao 
   *        ->recursosNaoVinculados -> array recursos[]->Objetosrecurso->disponibilidadebruta 
   *                                                                   ->obrigacoesfinanceiras
   *                                                                   ->disponibilidadeliquida
   * *                                                                ->descricao
   *        ->regimerpps            ->disponibilidadebruta 
   *                                ->obrigacoesfinanceiras
   *                               ->disponibilidadeliquida
   * @return Object - Colecao de stdClass
   */
  public function getDados() {

    /**
     * separamos o RPPS das outras instituicoes
     */
    $sListaInstituicoes = '';
    $sListaRPPS = '';
    $vInst = '';
    $vRPPS = '';
    $sSqlVerificaInstituicao = "SELECT codigo, db21_tipoinstit From db_config where codigo in({$this->getInstituicoes()})";
    $rsVerificaInstituicao = db_query($sSqlVerificaInstituicao);
    $aInstituicoes = db_utils::getCollectionByRecord($rsVerificaInstituicao);
    foreach ( $aInstituicoes as $oInstituicao ) {
      
      if ($oInstituicao->db21_tipoinstit == 5 || $oInstituicao->db21_tipoinstit == 6) {
        
        $sListaRPPS .= $vRPPS . $oInstituicao->codigo;
        $vRPPS = ",";
      } else {
        
        $sListaInstituicoes .= "{$vInst}{$oInstituicao->codigo}";
        $vInst = ",";
      }
    }
    
    unset($aInstituicoes);
    
    $aLinhas = array ();
    $oRetorno = new stdClass();
    $oRecursos = $this->initRecursosRetorno();
    $oRetorno->recursosVinculados = $oRecursos->recursosVinculados;
    $oRetorno->recursosNaoVinculados = $oRecursos->recursosNaoVinculados;
    
    $oRetorno->regimerpps = new stdClass();
    $oRetorno->regimerpps->restospagarprocessadosexercicioanterior = 0;
    $oRetorno->regimerpps->restospagarprocessadosexercicio = 0;
    $oRetorno->regimerpps->restospagarnaoprocessadosexercicioanterior = 0;
    $oRetorno->regimerpps->restospagarnaoprocessadosexercicio = 0;
    $oRetorno->regimerpps->disponibilidadedecaixa = 0;
    $oRetorno->regimerpps->insuficienciafinanceira = 0;
    $sDataInicial = "{$this->iAnoUsu}-01-01";
    $sDataFinal = "{$this->iAnoUsu}-12-31";
    if ($sListaInstituicoes != "") {
      
      $sWhereDespesa = "o58_instit in ({$sListaInstituicoes})";
      $rsDespesa = db_dotacaosaldo(8, 3, 3, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);
      /**
       * Calculamos os valores das obrigacoes financeiras (devemos somar: empenhos a pagar do Exercicio, RP'S)
       */
      $iLinhaDespesa = pg_num_rows($rsDespesa);
      for($i = 0; $i < $iLinhaDespesa; $i ++) {
        
        $oDespesa = db_utils::fieldsmemory($rsDespesa, $i);
        $oRecurso = $this->verificaRecursoCache($oDespesa->o58_codigo);
        $nValorPagar = abs($oDespesa->liquidado_acumulado - $oDespesa->pago_acumulado);
        $nValorLiquidado = abs($oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado - $oDespesa->liquidado_acumulado);
        if ($oRecurso->o15_tipo == 2) {
          
          if (isset($oRetorno->recursosVinculados [$oDespesa->o58_codigo])) {
            
            $oRetorno->recursosVinculados [$oDespesa->o58_codigo]->restospagarprocessadosexercicio += $nValorPagar;
            $oRetorno->recursosVinculados [$oDespesa->o58_codigo]->restospagarnaoprocessadosexercicio += $nValorLiquidado;
          }
        } else {
          if (isset($oRetorno->recursosNaoVinculados [$oDespesa->o58_codigo])) {
            
            $oRetorno->recursosNaoVinculados [$oDespesa->o58_codigo]->restospagarprocessadosexercicio += $nValorPagar;
            $oRetorno->recursosNaoVinculados [$oDespesa->o58_codigo]->restospagarnaoprocessadosexercicio += $nValorLiquidado;
          }
        }
      }
    }
    /**
     * Calcumos os valores de em de empenhos do exercicio do rpps, caso exista
     */
    if ($sListaRPPS != "") {
      
      $sWhereDespesa = "o58_instit in ({$sListaRPPS})";
      $rsDespesa = db_dotacaosaldo(8, 3, 3, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);

      $iLinhaDespesa = pg_num_rows($rsDespesa);
      for($i = 0; $i < $iLinhaDespesa; $i ++) {
        
        $oDespesa = db_utils::fieldsmemory($rsDespesa, $i);
        
        $nValorPagar = abs($oDespesa->liquidado_acumulado - $oDespesa->pago_acumulado);
        $nValorLiquidado = abs($oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado - $oDespesa->liquidado_acumulado);
        if (isset($oRetorno->recursosVinculados [$oDespesa->o58_codigo])) {
          
          $oRetorno->regimerpps->restospagarprocessadosexercicio += $nValorPagar;
          $oRetorno->regimerpps->restospagarnaoprocessadosexercicio += $nValorLiquidado;
        }
      }
    }
    
    /**
     * calculos todos os valores de Restos a pagar no periodo.
     */
    
       
    $sSqlRecursosRestos = "";
    
    $sSqlRecursosRestos .= "select e91_recurso, "; 
    $sSqlRecursosRestos .= "       db21_tipoinstit, "; 
    $sSqlRecursosRestos .= "       sum(e91_vlremp) as empenhado, ";
    $sSqlRecursosRestos .= "       sum(round(e91_vlranu,2) + round(lancam_anulado_proc,2) + ";
    $sSqlRecursosRestos .= "           round(lancam_anulado_nproc,2)) as anulado, ";
    $sSqlRecursosRestos .= "       sum(round(e91_vlrliq,2) + "; 
    $sSqlRecursosRestos .= "           round(lancam_liquidado,2) - ";
    $sSqlRecursosRestos .= "           round(lancam_liquidado_anulado,2) - ";
    $sSqlRecursosRestos .= "           round(lancam_anulado_proc,2)) as liquidado_proc,";
    $sSqlRecursosRestos .= "       sum(round(e91_vlremp,2) - "; 
    $sSqlRecursosRestos .= "           ((round(e91_vlranu,2) + round(lancam_anulado_nproc,2)) + ";
    $sSqlRecursosRestos .= "            (round(lancam_liquidado,2) + round(e91_vlrliq,2) - ";
    $sSqlRecursosRestos .= "             round(lancam_liquidado_anulado,2)";
    $sSqlRecursosRestos .= "             )) ";
    $sSqlRecursosRestos .= "           ) as liquidado_nproc, ";
    $sSqlRecursosRestos .= "       sum(round(e91_vlrpag,2) + round(lancam_pagamento,2) - ";
    $sSqlRecursosRestos .= "           round(lancam_pagamento_anulado,2)) as pago ";
    $sSqlRecursosRestos .= "  from (";
    $sSqlRecursosRestos .= "        SELECT e91_recurso, ";
    $sSqlRecursosRestos .= "               e91_numemp, ";
    $sSqlRecursosRestos .= "               e91_anousu, ";
    $sSqlRecursosRestos .= "               e91_vlremp, ";
    $sSqlRecursosRestos .= "               e91_vlranu, ";
    $sSqlRecursosRestos .= "               e91_vlrliq, ";
    $sSqlRecursosRestos .= "               e91_vlrpag, ";
    $sSqlRecursosRestos .= "               coalesce(   ";
    $sSqlRecursosRestos .= "               (select round(sum(c70_valor),2) ";
    $sSqlRecursosRestos .= "                  from conlancamemp ";
    $sSqlRecursosRestos .= "                       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlRecursosRestos .= "                       inner join conlancam    on c71_codlan = c70_codlan ";
    $sSqlRecursosRestos .= "                 where extract (year from c70_data) = {$this->iAnoUsu} ";
    $sSqlRecursosRestos .= "                   and c75_numemp = empresto.e91_numemp ";
    $sSqlRecursosRestos .= "                   and c71_coddoc in (31) ),0) as lancam_anulado_proc, ";
    $sSqlRecursosRestos .= "               coalesce( ";
    $sSqlRecursosRestos .= "               (select round(sum(c70_valor),2) ";
    $sSqlRecursosRestos .= "                  from conlancamemp ";
    $sSqlRecursosRestos .= "                       inner join conlancamdoc on c71_codlan = c75_codlan";
    $sSqlRecursosRestos .= "                       inner join conlancam    on c71_codlan = c70_codlan ";
    $sSqlRecursosRestos .= "                 where extract (year from c70_data) = {$this->iAnoUsu} ";
    $sSqlRecursosRestos .= "                   and c75_numemp = empresto.e91_numemp ";
    $sSqlRecursosRestos .= "                   and c71_coddoc in (32)), 0) as lancam_anulado_nproc, ";
    $sSqlRecursosRestos .= "               coalesce( ";
    $sSqlRecursosRestos .= "               (select round(sum(c70_valor),2) ";
    $sSqlRecursosRestos .= "                  from conlancamemp ";
    $sSqlRecursosRestos .= "                       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlRecursosRestos .= "                       inner join conlancam    on c71_codlan = c70_codlan ";
    $sSqlRecursosRestos .= "                 where extract (year from c70_data) = {$this->iAnoUsu} ";
    $sSqlRecursosRestos .= "                   and c75_numemp = empresto.e91_numemp ";
    $sSqlRecursosRestos .= "                   and c71_coddoc in (33)) ,0) as lancam_liquidado, ";
    $sSqlRecursosRestos .= "               coalesce( ";
    $sSqlRecursosRestos .= "               (select round(sum(c70_valor),2) ";
    $sSqlRecursosRestos .= "                  from conlancamemp ";
    $sSqlRecursosRestos .= "                       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlRecursosRestos .= "                       inner join conlancam    on c71_codlan = c70_codlan ";
    $sSqlRecursosRestos .= "                 where extract (year from c70_data) = {$this->iAnoUsu} ";
    $sSqlRecursosRestos .= "                   and c75_numemp = empresto.e91_numemp ";
    $sSqlRecursosRestos .= "                   and c71_coddoc in (34)) ,0) as lancam_liquidado_anulado, ";
    $sSqlRecursosRestos .= "               coalesce(";
    $sSqlRecursosRestos .= "               (select round(sum(c70_valor),2) ";
    $sSqlRecursosRestos .= "                  from conlancamemp ";
    $sSqlRecursosRestos .= "                       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlRecursosRestos .= "                       inner join conlancam    on c71_codlan = c70_codlan ";
    $sSqlRecursosRestos .= "                 where extract (year from c70_data) = {$this->iAnoUsu} ";
    $sSqlRecursosRestos .= "                   and c75_numemp = empresto.e91_numemp ";
    $sSqlRecursosRestos .= "                   and c71_coddoc in (35, 37)) ,0) as lancam_pagamento, ";
    $sSqlRecursosRestos .= "               coalesce(";
    $sSqlRecursosRestos .= "               (select round(sum(c70_valor),2) ";
    $sSqlRecursosRestos .= "                  from conlancamemp ";
    $sSqlRecursosRestos .= "                       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlRecursosRestos .= "                       inner join conlancam    on c71_codlan = c70_codlan ";
    $sSqlRecursosRestos .= "                 where extract (year from c70_data) = {$this->iAnoUsu} ";
    $sSqlRecursosRestos .= "                   and c75_numemp = empresto.e91_numemp ";
    $sSqlRecursosRestos .= "                   and c71_coddoc in (36,38)) ,0) as lancam_pagamento_anulado, ";
    $sSqlRecursosRestos .= "               db21_tipoinstit ";
    $sSqlRecursosRestos .= "          from empresto ";
    $sSqlRecursosRestos .= "               inner join empempenho on e91_numemp = e60_numemp ";
    $sSqlRecursosRestos .= "               inner join db_config on e60_instit = codigo ";
    $sSqlRecursosRestos .= "         where e91_anousu = {$this->iAnoUsu} and e60_instit in ({$this->getInstituicoes()}) ";
    $sSqlRecursosRestos .= "       ) as x ";
    $sSqlRecursosRestos .= " group by e91_recurso, ";
    $sSqlRecursosRestos .= "          db21_tipoinstit ";
    
    $rsRecursosResto = db_query($sSqlRecursosRestos);
    $iNumRows = pg_num_rows($rsRecursosResto);
    for($i = 0; $i < $iNumRows; $i ++) {
      
      $oDadosResto = db_utils::fieldsMemory($rsRecursosResto, $i);
      $nValorRestoProcessado = $oDadosResto->liquidado_proc - $oDadosResto->pago;
      $nValorRestoNaoProcessado = $oDadosResto->liquidado_nproc;
      if ($oDadosResto->db21_tipoinstit == 5 || $oDadosResto->db21_tipoinstit == 6) {
        
        $oRetorno->regimerpps->restospagarprocessadosexercicioanterior += $nValorRestoProcessado;
        $oRetorno->regimerpps->restospagarnaoprocessadosexercicioanterior += $nValorRestoNaoProcessado;
      } else {
        
        $oRecurso = $this->verificaRecursoCache($oDadosResto->e91_recurso);
        if ($oRecurso->o15_tipo == 2) {
          
          if (isset($oRetorno->recursosVinculados [$oDadosResto->e91_recurso])) {
            
            $oRetorno->recursosVinculados [$oDadosResto->e91_recurso]->restospagarprocessadosexercicioanterior += $nValorRestoProcessado;
            $oRetorno->recursosVinculados [$oDadosResto->e91_recurso]->restospagarnaoprocessadosexercicioanterior += $nValorRestoNaoProcessado;
          }
        } else {
          if (isset($oRetorno->recursosNaoVinculados [$oDadosResto->e91_recurso])) {
            
            $oRetorno->recursosNaoVinculados [$oDadosResto->e91_recurso]->restospagarprocessadosexercicioanterior += $nValorRestoProcessado;
            $oRetorno->recursosNaoVinculados [$oDadosResto->e91_recurso]->restospagarnaoprocessadosexercicioanterior += $nValorRestoNaoProcessado;
          }
        }
      }
    }
    
    /**
     * verificamos se existe a instancia do anexo V, e calculamos o total da disponibilidade do caixa
     */
    if ($this->oAnexoV instanceof AnexoVRGF) {
      
      $aDados = $this->oAnexoV->getDados();
      if (count($aDados) > 0) {
        
        foreach ( $aDados->recursosVinculados as $oRecursoAnexoV ) {
          
          if (isset($oRetorno->recursosVinculados [$oRecursoAnexoV->codigo])) {
            $oRetorno->recursosVinculados [$oRecursoAnexoV->codigo]->disponibilidadedecaixa += $oRecursoAnexoV->disponibilidadeliquida;
          }
        }
        
        foreach ( $aDados->recursosNaoVinculados as $oRecursoAnexoV ) {
          
          if (isset($oRetorno->recursosNaoVinculados [$oRecursoAnexoV->codigo])) {
            $oRetorno->recursosNaoVinculados [$oRecursoAnexoV->codigo]->disponibilidadedecaixa += $oRecursoAnexoV->disponibilidadeliquida;
          }
        }
      }
      
      /**
       * setamos os dados do rpps
       */
      $oRetorno->regimerpps->disponibilidadedecaixa += $aDados->regimerpps->disponibilidadeliquida;
    }
    
    unset($aDados);
    /**
     * Calculos de restos a pagar inscritos em insuficianecia financeira.
     */
    $sSqlInsuficiencia = "SELECT o58_codigo,  ";
    $sSqlInsuficiencia .= "       sum(e94_valor) as total,";
    $sSqlInsuficiencia .= "       db21_tipoinstit";
    $sSqlInsuficiencia .= "  From empanulado ";
    $sSqlInsuficiencia .= "       inner join empempenho on e94_numemp = e60_numemp ";
    $sSqlInsuficiencia .= "       inner join orcdotacao on o58_coddot = e60_coddot ";
    $sSqlInsuficiencia .= "                            and o58_anousu = e60_anousu ";
    $sSqlInsuficiencia .= "       inner join db_config  on e60_instit  = codigo ";
    $sSqlInsuficiencia .= " where e94_data between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sSqlInsuficiencia .= "   and e94_empanuladotipo = 1  ";
    $sSqlInsuficiencia .= "   and e60_instit in({$this->getInstituicoes()})  ";
    $sSqlInsuficiencia .= " group by o58_codigo, db21_tipoinstit";
    $rsInsuficiencia = db_query($sSqlInsuficiencia);
    $iTotalInsuficiencia = pg_num_rows($rsInsuficiencia);
    if ($iTotalInsuficiencia > 0) {
      
      for($i = 0; $i < $iTotalInsuficiencia; $i ++) {
        
        $oRecursoInsuficiencia = db_utils::fieldsMemory($rsInsuficiencia, $i);
        $nValorInsuficencia = $oRecursoInsuficiencia->total;
        if ($oRecursoInsuficiencia->db21_tipoinstit == 5 || $oRecursoInsuficiencia->db21_tipoinstit == 5) {
          $oRetorno->regimerpps->insuficienciafinanceira += $nValorInsuficencia;
        } else {
          
          $oRecurso = $this->verificaRecursoCache($oRecursoInsuficiencia->o58_codigo);
          if ($oRecurso->o15_tipo == 2) {
            if (isset($oRetorno->recursosVinculados [$oRecursoInsuficiencia->o58_codigo])) {
              $oRetorno->recursosVinculados [$oRecursoInsuficiencia->o58_codigo]->insuficienciafinanceira += $nValorInsuficencia;
            }
          } else {
            if (isset($oRetorno->recursosNaoVinculados [$oRecursoInsuficiencia->o58_codigo])) {
              $oRetorno->recursosNaoVinculados [$oRecursoInsuficiencia->o58_codigo]->insuficienciafinanceira += $nValorInsuficencia;
            }
          }
        }
        unset($oRecursoInsuficiencia);
      }
    }
    
    /**
     * percorremos todos os recursos informados, e mostramos apenas os que possuim movimentacoes.
     */
    $aRecursosVinculados = array ();
    foreach ( $oRetorno->recursosVinculados as $iRecurso => &$oRecurso ) {
      
      if (round($oRecurso->disponibilidadedecaixa, 2) != 0 || round($oRecurso->insuficienciafinanceira, 2) != 0 || round($oRecurso->restospagarnaoprocessadosexercicioanterior, 2) != 0 || round($oRecurso->restospagarnaoprocessadosexercicio, 2) || round($oRecurso->restospagarprocessadosexercicioanterior, 2)) {
        $aRecursosVinculados [$iRecurso] = $oRecurso;
      }
    }
    
    $this->aDados = $oRetorno;
    
    $aRecursosNaoVinculados = array ();
    foreach ( $oRetorno->recursosNaoVinculados as $iRecurso => &$oRecurso ) {
      
      if (round($oRecurso->disponibilidadedecaixa, 2) != 0 || round($oRecurso->insuficienciafinanceira, 2) != 0 || round($oRecurso->restospagarnaoprocessadosexercicioanterior, 2) != 0 || round($oRecurso->restospagarnaoprocessadosexercicio, 2) || round($oRecurso->restospagarprocessadosexercicioanterior, 2)) {
        $aRecursosNaoVinculados [$iRecurso] = $oRecurso;
      }
    }
    
    $oRetorno->recursosVinculados = $aRecursosVinculados;
    $oRetorno->recursosNaoVinculados = $aRecursosNaoVinculados;
    $this->aDados = $oRetorno;
    return $this->aDados;
  }
  
  /**
   * verifica se o recurso já está no cache
   *
   * @param  integer $iRecurso
   * @return dados do recurso
   */
  protected function verificaRecursoCache($iRecurso) {

    /**
     * verifica se o recurso já existe no cache
     */
    $lRecursoValido = false;
    if (! isset($this->aCacheRecursos [$iRecurso])) {
      
      $sSqlRecurso = "select o15_codigo, ";
      $sSqlRecurso .= "       o15_tipo,   ";
      $sSqlRecurso .= "       o15_descr   ";
      $sSqlRecurso .= "  from orctiporec  ";
      $sSqlRecurso .= " where o15_codigo = {$iRecurso}";
      $rsRecurso = db_query($sSqlRecurso);
      if (pg_num_rows($rsRecurso) == 1) {
        
        $oRecurso = db_utils::fieldsMemory($rsRecurso, 0);
        $lRecursoValido = true;
      } else {
        
        $oRecurso = new stdClass();
        $oRecurso->o15_codigo = 0;
        $oRecurso->o15_tipo = 0;
        $oRecurso->o15_descr = '';
      }
      if (! $lRecursoValido) {
        $iRecurso = 0;
      }
      $this->aCacheRecursos [$iRecurso] = $oRecurso;
    }
    $oRecurso = $this->aCacheRecursos [$iRecurso];
    return $oRecurso;
  }
  /**
   * inicializa os valores dos recursos
   *
   * @return array
   */
  protected function initRecursosRetorno() {

    $sSqlRecurso = "select o15_codigo, ";
    $sSqlRecurso .= "       o15_tipo,   ";
    $sSqlRecurso .= "       o15_descr   ";
    $sSqlRecurso .= "  from orctiporec  ";
    $sSqlRecurso .= "  order by o15_codigo";
    $rsRecurso = db_query($sSqlRecurso);
    $aRecursos = db_utils::getCollectionByRecord($rsRecurso);
    $oRetorno = new stdClass();
    foreach ( $aRecursos as $oRecurso ) {
      
      $oDadosRecurso = new stdClass();
      $oDadosRecurso->descricao = $oRecurso->o15_descr;
      $oDadosRecurso->codigo = $oRecurso->o15_codigo;
      $oDadosRecurso->restospagarprocessadosexercicioanterior = 0;
      $oDadosRecurso->restospagarprocessadosexercicio = 0;
      $oDadosRecurso->restospagarnaoprocessadosexercicioanterior = 0;
      $oDadosRecurso->restospagarnaoprocessadosexercicio = 0;
      $oDadosRecurso->disponibilidadedecaixa = 0;
      $oDadosRecurso->insuficienciafinanceira = 0;
      
      $iRecurso = $oRecurso->o15_codigo;
      if ($oRecurso->o15_tipo == 2) {
        if (! isset($oRetorno->recursosVinculados [$iRecurso])) {
          $oRetorno->recursosVinculados [$iRecurso] = $oDadosRecurso;
        }
      } else if ($oRecurso->o15_tipo == 1) {
        if (! isset($oRetorno->recursosNaoVinculados [$iRecurso])) {
          $oRetorno->recursosNaoVinculados [$iRecurso] = $oDadosRecurso;
        }
      }
    }
    unset($aRecursos);
    return $oRetorno;
  }
  
  /**
   * define a instancia do anexo V
   *
   * @param AnexoVRGF $oAnexo
   */
  public function setDadosAnexoV(AnexoVRGF $oAnexo) {

    $this->oAnexoV = $oAnexo;
  }
  
  /**
   * retorna os dados para o relatorio simplificado
   *
   */
  public function getDadosSimplificado() {

    if (! isset($this->aDados->recursosVinculados)) {
      $this->getDados();
    }
    
    $nValorExercicioAnteriores = 0;
    $nValorExercicio = 0;
    $nValorDisponibilidade = 0;
    foreach ( $this->aDados->recursosVinculados as $oRecurso ) {
      
      $nValorExercicioAnteriores += $oRecurso->restospagarnaoprocessadosexercicioanterior;
      $nValorExercicio += $oRecurso->restospagarnaoprocessadosexercicio;
      $nValorDisponibilidade += $oRecurso->disponibilidadedecaixa;
    }
    
    foreach ( $this->aDados->recursosNaoVinculados as $oRecurso ) {
      
      $nValorExercicioAnteriores += $oRecurso->restospagarnaoprocessadosexercicioanterior;
      $nValorExercicio += $oRecurso->restospagarnaoprocessadosexercicio;
      $nValorDisponibilidade += $oRecurso->disponibilidadedecaixa;
    }
    
    $oRetorno = new stdClass();
    $oRetorno->restoapagarexericioanterior = $nValorExercicioAnteriores;
    $oRetorno->restoapagarexericio = $nValorExercicio;
    $oRetorno->disponibilidadedecaixa = $nValorDisponibilidade;
    
    return $oRetorno;
  }
}