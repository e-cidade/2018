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
 * classe para controle dos valores do Anexo V da RGF
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

class AnexoVRGF extends RelatoriosLegaisBase  {
  
  
  private $aCacheRecursos = array();
  
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
    $sListaRPPS         = '';
    $vInst              = '';
    $vRPPS              = '';
    $sSqlVerificaInstituicao = "SELECT codigo, db21_tipoinstit From db_config where codigo in({$this->getInstituicoes()})";
    $rsVerificaInstituicao   = db_query($sSqlVerificaInstituicao);
    $aInstituicoes           = db_utils::getCollectionByRecord($rsVerificaInstituicao);
    foreach ($aInstituicoes as $oInstituicao) {
       
      if ($oInstituicao->db21_tipoinstit == 5 || $oInstituicao->db21_tipoinstit == 6) {
        
        $sListaRPPS .= $vRPPS.$oInstituicao->codigo;
        $vRPPS = ",";
      } else {

        $sListaInstituicoes .= "{$vInst}{$oInstituicao->codigo}";
        $vInst               = ",";
      }
    }
    unset($aInstituicoes);
    $aLinhas   = array();
    $oRetorno  = new stdClass();
    $oRecursos = $this->initRecursosRetorno(); 
    $oRetorno->recursosVinculados    = $oRecursos->recursosVinculados;
    $oRetorno->recursosNaoVinculados = $oRecursos->recursosNaoVinculados;
    
    $oRetorno->regimerpps                         = new stdClass();
    $oRetorno->regimerpps->disponibilidadebruta   = 0;
    $oRetorno->regimerpps->obrigacoesfinanceiras  = 0;
    $oRetorno->regimerpps->disponibilidadeliquida = 0;
    $sDataInicial  = "{$this->iAnoUsu}-01-01"; 
    $sDataFinal    = "{$this->iAnoUsu}-12-31"; 
    if ($sListaInstituicoes != "") {
      
      $sWhereDespesa = "o58_instit in ({$sListaInstituicoes})";
      $rsDespesa     = db_dotacaosaldo(8,3,3,true, 
                                       $sWhereDespesa, 
                                       $this->iAnoUsu, 
                                       $sDataInicial, 
                                       $sDataFinal
                                      );
        
    }                              
    $sWhereVerificacao = "c61_instit in ({$this->getInstituicoes()})";                                    
    $rsVerificacao     = db_planocontassaldo_matriz($this->iAnoUsu, $sDataInicial, $sDataFinal, false,
                                                    $sWhereVerificacao,
                                                    "", 
                                                   "true", "false"
                                                  );

    /**
     * percorremos os valores lancados manuais para as linhas 
     */
    $aDadosRecurso   = array();
    $aLinhaRelatorio = array();
    for ($iLinha = 1; $iLinha <= 6; $iLinha++) {
      
      $oLinhaRelatorio          = new linhaRelatorioContabil($this->iCodigoRelatorio, $iLinha);
      $aLinhaRelatorio[$iLinha] = $oLinhaRelatorio;
      $aLinhaRelatorio[$iLinha]->setPeriodo($this->iCodigoPeriodo);
      $aLinhaRelatorio[$iLinha]->parametros =  $oLinhaRelatorio->getParametros($this->iAnoUsu, $this->getInstituicoes());
      
      $aValoresColunasLinhas = $aLinhaRelatorio[$iLinha]->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValor) {
        
        $iRecurso = (int) $oValor->colunas[0]->o117_valor;
        $oRecurso = $this->verificaRecursoCache($iRecurso);
        /**
         * recurso invalido devemos igonorar
         */
        if ($oRecurso->o15_codigo == 0) {
          continue;
        }
        
        /**
         * linha 1 Verificamos os valores de recurso vinculado para a coluna disponibilidade de caixa bruto 
         */
        
        if ($iLinha == 1) {
          if ($oRecurso->o15_tipo == 2) {
            $oRetorno->recursosVinculados[$iRecurso]->disponibilidadebruta += @$oValor->colunas[1]->o117_valor;
          }
        }
        if ($iLinha == 2) {
          if ($oRecurso->o15_tipo == 2) {
            $oRetorno->recursosVinculados[$iRecurso]->obrigacoesfinanceiras += @$oValor->colunas[1]->o117_valor;
          }
        }
        
        if ($iLinha == 3) {
          if ($oRecurso->o15_tipo == 1) {
            $oRetorno->recursosNaoVinculados[$iRecurso]->disponibilidadebruta += @$oValor->colunas[1]->o117_valor;
          }
        }
        if ($iLinha == 4) {
          
          if ($oRecurso->o15_tipo == 1) {
            $oRetorno->recursosNaoVinculados[$iRecurso]->obrigacoesfinanceiras += @$oValor->colunas[1]->o117_valor;
          }
        }
        
        if ($iLinha == 5) {
          $oRetorno->regimerpps->disponibilidadebruta += @$oValor->colunas[1]->o117_valor;
        }
        if ($iLinha == 6) {
          $oRetorno->regimerpps->obrigacoesfinanceiras += @$oValor->colunas[1]->o117_valor;
        }
      }
      
    }
    
    /**
     * Calculamos os valores Cadastrados nos parametros para a coluna das disponibilidade bruta.
     */
    $iLinhaBalancente = pg_num_rows($rsVerificacao);
    for ($i = 0; $i < $iLinhaBalancente; $i++) {

      $oResultado = db_utils::fieldsMemory($rsVerificacao, $i);
      for ($iLinha = 1; $iLinha <= 6; $iLinha++) {
         
          
        $oParametro  = $aLinhaRelatorio[$iLinha]->parametros;
        foreach ($oParametro->contas as $oConta) {
          
          $oVerificacao    = $aLinhaRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);
          if ($oVerificacao->match) {

           if ($oVerificacao->exclusao) {
        
              $oResultado->saldo_anterior *= -1;  
              $oResultado->saldo_final    *= -1;  
            }
            $oRecurso = $this->verificaRecursoCache($oResultado->c61_codigo);
            if ($oResultado->c61_reduz == 0) {
              continue;
            }
            if ($iLinha == 1 || $iLinha == 3) {
              
              if ($oRecurso->o15_tipo == 2) {
                if (isset($oRetorno->recursosVinculados[$oResultado->c61_codigo])) {
                  $oRetorno->recursosVinculados[$oResultado->c61_codigo]->disponibilidadebruta += $oResultado->saldo_final;
                } 
              } else {
                if (isset($oRetorno->recursosNaoVinculados[$oResultado->c61_codigo])) {
                  $oRetorno->recursosNaoVinculados[$oResultado->c61_codigo]->disponibilidadebruta += $oResultado->saldo_final;
                }
              }
            } else if ($iLinha == 2 || $iLinha == 4) {
              
              if ($oRecurso->o15_tipo == 2) {
                if (isset($oRetorno->recursosVinculados[$oResultado->c61_codigo])) {
                  $oRetorno->recursosVinculados[$oResultado->c61_codigo]->obrigacoesfinanceiras += $oResultado->saldo_final;
                } 
              } else {
                if (isset($oRetorno->recursosNaoVinculados[$oResultado->c61_codigo])) {
                  $oRetorno->recursosNaoVinculados[$oResultado->c61_codigo]->obrigacoesfinanceiras += $oResultado->saldo_final;
                }
              }
            } else if ($iLinha == 5) {
              $oRetorno->regimerpps->disponibilidadebruta  += $oResultado->saldo_final;
            } else if ($iLinha == 6) {
              $oRetorno->regimerpps->obrigacoesfinanceiras += $oResultado->saldo_final;
            }
          }
        }
      }    
    }
    
    /**
     * Calculamos os valores das obrigacoes financeiras (devemos somar: empenhos a pagar do Exercicio, RP'S)
     */
    if ($sListaInstituicoes != "") {
      
      $iLinhaDespesa = pg_num_rows($rsDespesa);
      for ($i = 0; $i < $iLinhaDespesa; $i++) {
        
        $oDespesa    = db_utils::fieldsmemory($rsDespesa, $i);
        $oRecurso    = $this->verificaRecursoCache($oDespesa->o58_codigo);
        //$nValorPagar = abs($oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado - $oDespesa->pago_acumulado);
        $nValorPagar = abs($oDespesa->liquidado_acumulado - $oDespesa->pago_acumulado);
        if ($oRecurso->o15_tipo == 2) {
          
          if (isset($oRetorno->recursosVinculados[$oDespesa->o58_codigo])) {
            $oRetorno->recursosVinculados[$oDespesa->o58_codigo]->obrigacoesfinanceiras += $nValorPagar;
          } 
        } else {
          if (isset($oRetorno->recursosNaoVinculados[$oDespesa->o58_codigo])) {
            $oRetorno->recursosNaoVinculados[$oDespesa->o58_codigo]->obrigacoesfinanceiras += $nValorPagar;
          }
        }
      }
    }
    /**
     * Calcumos os valores de em de empenhos do exercicio do rpps, caso exista
     */
    if ($sListaRPPS != "") {
      
      $sWhereDespesa = "o58_instit in ({$sListaRPPS})";
      $rsDespesa     = db_dotacaosaldo(8,3,3,true, 
                                       $sWhereDespesa, 
                                       $this->iAnoUsu, 
                                       $sDataInicial, 
                                       $sDataFinal
                                      );
                                      
                                             
      $iLinhaDespesa = pg_num_rows($rsDespesa);
      for ($i = 0; $i < $iLinhaDespesa; $i++) {
        
        $oDespesa    = db_utils::fieldsmemory($rsDespesa, $i);
        $nValorPagar = abs($oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado - $oDespesa->pago_acumulado);
        if (isset($oRetorno->recursosVinculados[$oDespesa->o58_codigo])) {
          $oRetorno->regimerpps->obrigacoesfinanceiras += $nValorPagar;
        } 
      }                                
    }
    
    /**
     * calculos todos os valores de Restos a pagar no periodo.
     */
     $sSqlRecursosRestos  = "SELECT e91_recurso, ";
     $sSqlRecursosRestos .= "       sum(e91_vlremp) as empenhado,";
     $sSqlRecursosRestos .= "       sum(e60_vlranu) as anulado, ";
     $sSqlRecursosRestos .= "       sum(e60_vlrpag) as pago, ";
     $sSqlRecursosRestos .= "       db21_tipoinstit";
     $sSqlRecursosRestos .= "  from empresto ";
     $sSqlRecursosRestos .= "       inner join empempenho on e91_numemp = e60_numemp ";
     $sSqlRecursosRestos .= "       inner join db_config  on e60_instit  = codigo ";
     $sSqlRecursosRestos .= " where e91_anousu = {$this->iAnoUsu} ";
     $sSqlRecursosRestos .= "   and e60_instit in ({$this->getInstituicoes()})";
     $sSqlRecursosRestos .= " group by e91_recurso,"; 
     $sSqlRecursosRestos .= "         db21_tipoinstit "; 
     $rsRecursosResto     = db_query($sSqlRecursosRestos);
     $iNumRows            = pg_num_rows($rsRecursosResto);
     for ($i = 0; $i < $iNumRows; $i++) {
       
        
       $oDadosResto  = db_utils::fieldsMemory($rsRecursosResto, $i);
       $nValorResto  = $oDadosResto->empenhado - $oDadosResto->anulado - $oDadosResto->pago;    
       if ($oDadosResto->db21_tipoinstit == 5 || $oDadosResto->db21_tipoinstit == 6) {
         $oRetorno->regimerpps->obrigacoesfinanceiras += $nValorResto;         
       } else {
         
         $oRecurso    = $this->verificaRecursoCache($oDadosResto->e91_recurso);
         if ($oRecurso->o15_tipo == 2) {
           
           if (isset($oRetorno->recursosVinculados[$oDadosResto->e91_recurso])) {
            $oRetorno->recursosVinculados[$oDadosResto->e91_recurso]->obrigacoesfinanceiras += abs($nValorResto);
           } 
         } else {
           if (isset($oRetorno->recursosNaoVinculados[$oDadosResto->e91_recurso])) {
            $oRetorno->recursosNaoVinculados[$oDadosResto->e91_recurso]->obrigacoesfinanceiras += abs($nValorResto);
           }
         }
       }
     }
    /**
     * percorremos todos os recursos informados, e mostramos apenas os que possuim movimentacoes.
     */
    $aRecursosVinculados = array();
    foreach ($oRetorno->recursosVinculados as $iRecurso => &$oRecurso) {
      
      if (round($oRecurso->disponibilidadebruta, 2) != 0 || round($oRecurso->obrigacoesfinanceiras, 2) != 0) {
        
        $oRecurso->disponibilidadeliquida = $oRecurso->disponibilidadebruta - $oRecurso->obrigacoesfinanceiras;
        $aRecursosVinculados[$iRecurso]   = $oRecurso;
      }
    }
    
    $aRecursosNaoVinculados = array();
    foreach ($oRetorno->recursosNaoVinculados as $iRecurso => &$oRecurso) {
      
      if (round($oRecurso->disponibilidadebruta, 2) != 0 || round($oRecurso->obrigacoesfinanceiras, 2) != 0) {
        
        $oRecurso->disponibilidadeliquida    = $oRecurso->disponibilidadebruta - $oRecurso->obrigacoesfinanceiras;
        $aRecursosNaoVinculados[$iRecurso]   = $oRecurso;
      }
    }
    
    $oRetorno->recursosVinculados    = $aRecursosVinculados;
    $oRetorno->recursosNaoVinculados = $aRecursosNaoVinculados;
    $oRetorno->regimerpps->disponibilidadeliquida = $oRetorno->regimerpps->disponibilidadebruta - 
                                                    $oRetorno->regimerpps->obrigacoesfinanceiras; 
    return $oRetorno;
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
    if (!isset($this->aCacheRecursos[$iRecurso])) {
      
      $sSqlRecurso     = "select o15_codigo, ";
      $sSqlRecurso    .= "       o15_tipo,   ";
      $sSqlRecurso    .= "       o15_descr   ";
      $sSqlRecurso    .= "  from orctiporec  "; 
      $sSqlRecurso    .= " where o15_codigo = {$iRecurso}";
      $rsRecurso       = db_query($sSqlRecurso);
      if (pg_num_rows($rsRecurso) == 1) {
         
        $oRecurso       = db_utils::fieldsMemory($rsRecurso, 0);
        $lRecursoValido = true;
      } else {
        
        $oRecurso = new stdClass();
        $oRecurso->o15_codigo = 0;
        $oRecurso->o15_tipo   = 0;
        $oRecurso->o15_descr  = '';
      }
      if (!$lRecursoValido) {
        $iRecurso = 0;
      }
      $this->aCacheRecursos[$iRecurso] =  $oRecurso;
    } 
    $oRecurso = $this->aCacheRecursos[$iRecurso];
    return $oRecurso;
  }
  
  protected function initRecursosRetorno() {
     
    $sSqlRecurso  = "select o15_codigo, ";
    $sSqlRecurso .= "       o15_tipo,   ";
    $sSqlRecurso .= "       o15_descr   ";
    $sSqlRecurso .= "  from orctiporec  "; 
    $sSqlRecurso .= "  order by o15_codigo"; 
    $rsRecurso    = db_query($sSqlRecurso);
    $aRecursos    = db_utils::getCollectionByRecord($rsRecurso);
    $oRetorno     = new stdClass();    
    foreach ($aRecursos as $oRecurso) {
      
      $iRecurso = $oRecurso->o15_codigo;
      if ($oRecurso->o15_tipo == 2) {
           
        if (!isset($oRetorno->recursosVinculados[$iRecurso])) {
            
          $oRetorno->recursosVinculados[$iRecurso] = new stdClass();
          $oRetorno->recursosVinculados[$iRecurso]->disponibilidadebruta   = 0;
          $oRetorno->recursosVinculados[$iRecurso]->obrigacoesfinanceiras  = 0;
          $oRetorno->recursosVinculados[$iRecurso]->disponibilidadeliquida = 0;
          $oRetorno->recursosVinculados[$iRecurso]->descricao              = $oRecurso->o15_descr;
          $oRetorno->recursosVinculados[$iRecurso]->codigo                 = $oRecurso->o15_codigo;
        }
      }
      
      if ($oRecurso->o15_tipo == 1) {
        if (!isset($oRetorno->recursosNaoVinculados[$iRecurso])) {
         
          $oRetorno->recursosNaoVinculados[$iRecurso] = new stdClass();
          $oRetorno->recursosNaoVinculados[$iRecurso]->disponibilidadebruta   = 0;
          $oRetorno->recursosNaoVinculados[$iRecurso]->obrigacoesfinanceiras  = 0;
          $oRetorno->recursosNaoVinculados[$iRecurso]->disponibilidadeliquida = 0;
          $oRetorno->recursosNaoVinculados[$iRecurso]->descricao              = $oRecurso->o15_descr;
          $oRetorno->recursosNaoVinculados[$iRecurso]->codigo                 = $oRecurso->o15_codigo;
        }
      }
    }
    unset($aRecursos);
    return $oRetorno;
  }
}