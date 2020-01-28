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

require_once ("RelatoriosLegaisBase.model.php");
/**
 * Classe para controle dos valores do Anexo I da RGF.
 *
 * @package    contabilidade
 * @subpackage relatorios
 * @author Luiz Marcelo Schmitt
 */

final class AnexoIRGF extends RelatoriosLegaisBase {

  /**
   * Objeto com os dados do relatorio
   *
   * @var stdclass $oDados
   */
  protected $oDados;

  /**
   * Data inicial para emiss�o do relat�rio.
   *
   * @var date_type $dtDataInicial
   */
  private $dtDataInicial;

  /**
   * Data final para emiss�o do relat�rio.
   *
   * @var date_type $dtDataFinal
   */
  private $dtDataFinal;

  /**
   * M�todo construtor da classe.
   *
   * @param integer $iAnoUsu Ano de emiss�o do relat�rio.
   * @param integer $iCodigoRelatorio C�digo do relat�rio.
   * @param integer $iCodigoPeriodo C�digo do per�odo de emiss�o do relat�rio.
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $oDaoPeriodo       = db_utils::getDao("periodo");
    $sSqlDadosPeriodo  = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo         = db_query($sSqlDadosPeriodo);
    $oDadosPeriodo     = db_utils::fieldsMemory($rsPeriodo, 0);

    if ($oDadosPeriodo->o114_sequencial < 17) {

      $aPeriodo        = data_periodo($this->iAnoUsu, $oDadosPeriodo->o114_sigla);
      $sDataExercicio  = $aPeriodo[1];
    } else {

      $iUltimoDiaMes   = cal_days_in_month(CAL_GREGORIAN, $oDadosPeriodo->o114_mesfinal, $this->iAnoUsu);
      $sDataExercicio  = "{$this->iAnoUsu}-{$oDadosPeriodo->o114_mesfinal}-{$iUltimoDiaMes}";
    }

    $this->setDataInicial("{$iAnoUsu}-01-01");
    $this->setDataFinal($sDataExercicio);
  }

  /**
   * Seta a data inicial do relat�rio.
   *
   * @param date_type $dtDataInicial
   */
  public function setDataInicial($dtDataInicial) {
    $this->dtDataInicial = $dtDataInicial;
  }

  /**
   * Retorna a data inicial para o relat�rio.
   *
   * @return date_type
   */
  public function getDataInicial() {
    return $this->dtDataInicial;
  }

  /**
   * Seta a data final do relat�rio.
   *
   * @param date_type $dtDataFinal
   */
  public function setDataFinal($dtDataFinal) {
    $this->dtDataFinal = $dtDataFinal;
  }

  /**
   * Retorna a data final para o relat�rio.
   *
   * @return date_type
   */
  public function getDataFinal() {
    return $this->dtDataFinal;
  }

  /**
   * Retorna os dados da classe em forma de objeto.
   *
   * @return Object $oRetorno
   */
  public function getDados() {

  	/**
  	 * Configura��es do per�odo informado.
  	 */
    $oDaoPeriodo       = db_utils::getDao("periodo");
    $sSqlDadosPeriodo  = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo         = db_query($sSqlDadosPeriodo);
    $oDadosPeriodo     = db_utils::fieldsMemory($rsPeriodo, 0);

    /**
     * Configura��es do tipo de institui��o da prefeitura ou c�mara.
     */
    $oDaoDbConfig       = db_utils::getDao("db_config");
    $sWhere             = "codigo in({$this->getInstituicoes()})";
    $sSqlDbConfig       = $oDaoDbConfig->sql_query_file(null, 'db21_tipoinstit', null, $sWhere);
    $rsSqlDbConfig      = $oDaoDbConfig->sql_record($sSqlDbConfig);
    $INumRowsDbConfig   = $oDaoDbConfig->numrows;

    $iLimiteMaximo             = 0;
    $iLimitePrudencial         = 0;
    $nValorDespesaTotalPessoal = 0;
    $nTotalRCL                 = 0;
    $lTemPrefeitura            = false;
    $lTemCamara                = false;
    $lTemMinisterio            = false;

    /**
     * Verifica o db21_tipoinstit para ver se � prefeitura ou c�mara.
     */
    for ($iInd = 0; $iInd < $INumRowsDbConfig; $iInd++) {

      $oMunicipio = db_utils::fieldsMemory($rsSqlDbConfig, $iInd);

      if ($oMunicipio->db21_tipoinstit == 1) {
        $lTemPrefeitura = true;
      } else if ($oMunicipio->db21_tipoinstit == 2) {
        $lTemCamara = true;
      } else if ($oMunicipio->db21_tipoinstit == 101) {
      	$lTemMinisterio = true;
      }
    }

    /**
     * Verifica o limite m�ximo  (incisos I, II e III, art. 20 da LRF) {$iLimiteMaximo}%
     */
    if ($lTemPrefeitura == true && $lTemCamara == true) {

      if ($iLimiteMaximo == 0){
        $iLimiteMaximo = 60;
        $iLimiteMaximoAlerta = 54;

      }
    } else if ($lTemPrefeitura == true && $lTemCamara == false) {

      if ($iLimiteMaximo == 0) {

        $iLimiteMaximo       = 54;
        $iLimiteMaximoAlerta = 48.6;
      }
    } else if ($lTemPrefeitura == false &&  $lTemCamara == true) {

      if ($iLimiteMaximo == 0) {
        $iLimiteMaximo = 6;
        $iLimiteMaximoAlerta = 5.4;
      }
    } else if ($lTemMinisterio) {

      if ($iLimiteMaximo == 0) {
        $iLimiteMaximo = 2;
      }
    }

    /**
     * Verifica o limite prudencial (par�grafo �nico, art. 22 da LRF) {$iLimitePrudencial}%
     */
    if ($iLimitePrudencial == 0) {
      $iLimitePrudencial = $iLimiteMaximo*95/100;
    }

    /**
     * Quadro de despesa bruta.
     */
    $oDespesaBruta = new stdClass();
    $oDespesaBruta->quadrodescricao    = 'DESPESA BRUTA COM PESSOAL (I)';
    $oDespesaBruta->exercicio          = 0;
    $oDespesaBruta->inscritas          = 0;
    $oDespesaBruta->linhas             = array();
    $oDespesaBruta->colunameses        = $this->getDadosColuna();
    $oDespesaBruta->valorapurado       = 0;
    $oDespesaBruta->percentuallimite   = 0;
    $oDespesaBruta->linhatotalizadora  = true;

    /**
     * Quadro de despesa n�o computadas.
     */
    $oDespesaNaoComputada = new stdClass();
    $oDespesaNaoComputada->quadrodescricao    = 'DESPESAS N�O COMPUTADAS (� 1o do art. 19 da LRF) (II)';
    $oDespesaNaoComputada->exercicio          = 0;
    $oDespesaNaoComputada->inscritas          = 0;
    $oDespesaNaoComputada->linhas             = array();
    $oDespesaNaoComputada->colunameses        = $this->getDadosColuna();
    $oDespesaNaoComputada->valorapurado       = 0;
    $oDespesaNaoComputada->percentuallimite   = 0;
    $oDespesaNaoComputada->linhatotalizadora  = true;

    /**
     * Quadro de despesa l�quida.
     */
    $oDespesaLiquida = new stdClass();
    $oDespesaLiquida->quadrodescricao   = 'DESPESA L�QUIDA COM PESSOAL (III) = (I - II)';
    $oDespesaLiquida->exercicio         = 0;
    $oDespesaLiquida->inscritas         = 0;
    $oDespesaLiquida->linhas            = array();
    $oDespesaLiquida->colunameses       = $this->getDadosColuna();
    $oDespesaLiquida->valorapurado      = 0;
    $oDespesaLiquida->percentuallimite  = 0;
    $oDespesaLiquida->linhatotalizadora = true;

    /**
     * Quadro de despesa total com pessoal.
     */
    $oDespesaTotalComPessoal = new stdClass();
    $oDespesaTotalComPessoal->quadrodescricao   = 'DESPESA TOTAL COM PESSOAL - DTP(IV) = (IIIa + IIIb)';
    $oDespesaTotalComPessoal->exercicio         = 0;
    $oDespesaTotalComPessoal->inscritas         = 0;
    $oDespesaTotalComPessoal->linhas            = array();
    $oDespesaTotalComPessoal->colunameses       = array();
    $oDespesaTotalComPessoal->valorapurado      = 0;
    $oDespesaTotalComPessoal->percentuallimite  = 0;
    $oDespesaTotalComPessoal->linhatotalizadora = true;

    /**
     * Quadro de receita corrente l�quida.
     */
    $oReceitaTotalCorrenteLiquida = new stdClass();
    $oReceitaTotalCorrenteLiquida->quadrodescricao   = 'RECEITA CORRENTE L�QUIDA - RCL (V)';
    $oReceitaTotalCorrenteLiquida->exercicio         = 0;
    $oReceitaTotalCorrenteLiquida->inscritas         = 0;
    $oReceitaTotalCorrenteLiquida->linhas            = array();
    $oReceitaTotalCorrenteLiquida->colunameses       = array();
    $oReceitaTotalCorrenteLiquida->valorapurado      = 0;
    $oReceitaTotalCorrenteLiquida->percentuallimite  = 0;
    $oReceitaTotalCorrenteLiquida->linhatotalizadora = false;

    /**
     * Quadro de despesa total com pessoal sem a RCL.
     */
    $oDespesaTotalComPessoalSemRCL = new stdClass();
    $oDespesaTotalComPessoalSemRCL->quadrodescricao   = '% da DESPESA TOTAL COM PESSOAL - DTP sobre a RCL (VI)=(IV/V)*100';
    $oDespesaTotalComPessoalSemRCL->exercicio         = 0;
    $oDespesaTotalComPessoalSemRCL->inscritas         = 0;
    $oDespesaTotalComPessoalSemRCL->linhas            = array();
    $oDespesaTotalComPessoalSemRCL->colunameses       = array();
    $oDespesaTotalComPessoalSemRCL->valorapurado      = 0;
    $oDespesaTotalComPessoalSemRCL->percentuallimite  = 0;
    $oDespesaTotalComPessoalSemRCL->linhatotalizadora = false;

    /**
     * Quadro de limite m�ximo.
     */
    $oLimiteMaximo = new stdClass();
    $oLimiteMaximo->quadrodescricao   = 'LIMITE M�XIMO (incisos I, II e III, art. 20 da LRF)';
    $oLimiteMaximo->exercicio         = 0;
    $oLimiteMaximo->inscritas         = 0;
    $oLimiteMaximo->linhas            = array();
    $oLimiteMaximo->colunameses       = array();
    $oLimiteMaximo->valorapurado      = 0;
    $oLimiteMaximo->percentuallimite  = 0;
    $oLimiteMaximo->linhatotalizadora = false;

    /**
     * Quadro de limite prudencial.
     */
    $oLimitePrudencial = new stdClass();
    $oLimitePrudencial->quadrodescricao   = 'LIMITE PRUDENCIAL (par�grafo �nico, art. 22 da LRF)';
    $oLimitePrudencial->exercicio         = 0;
    $oLimitePrudencial->inscritas         = 0;
    $oLimitePrudencial->linhas            = array();
    $oLimitePrudencial->colunameses       = array();
    $oLimitePrudencial->valorapurado      = 0;
    $oLimitePrudencial->percentuallimite  = 0;
    $oLimitePrudencial->linhatotalizadora = false;

    $oLimiteAlerta = new stdClass();
    $oLimiteAlerta->quadrodescricao   = 'LIMITE DE ALERTA (inciso II do � 1� do art. 59 da LRF)';
    $oLimiteAlerta->exercicio         = 0;
    $oLimiteAlerta->inscritas         = 0;
    $oLimiteAlerta->linhas            = array();
    $oLimiteAlerta->colunameses       = array();
    $oLimiteAlerta->valorapurado      = 0;
    $oLimiteAlerta->percentuallimite  = 0;
    $oLimiteAlerta->linhatotalizadora = false;

    $aDadosMeses = $this->getDadosColuna();
    /**
     * Percorremos as linhas cadastradas no relatorio, e adicionamos os valores cadastrados manualmente.
     */
    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
    for ($iLinha = 1; $iLinha <= 7; $iLinha++) {

      $aLinhasRelatorio[$iLinha]->setPeriodo($this->iCodigoPeriodo);
      $aColunasRelatorio   = $aLinhasRelatorio[$iLinha]->getCols($this->iCodigoPeriodo);

      /**
       * Monta o Object com os dados de cada linha interna do relat�rio.
       */
      $oLinha              = new stdClass();
      switch ($iLinha) {

      	case 1:
      	  $sDescricao = '   Pessoal Ativo';
      	  break;

        case 2:
          $sDescricao = '   Pessoal Inativo e Pensionistas';
          break;

        case 3:
          $sDescricao = '   Outras Desp Pessoal dec de Contr Terceiriza��o';
          break;

        case 4:
          $sDescricao = '   Ind por Demiss�o e Inc � Demiss�o Volunt�ria';
          break;

        case 5:
          $sDescricao = '   Decorrentes de Decis�o Judicial';
          break;

        case 6:
          $sDescricao = '   Despesas de Exerc�cios Anteriores';
          break;

        case 7:
          $sDescricao = '   Inativos e Pensionistas com Rec Vinculados';
          break;
      }

      $oLinha->descricao   = $sDescricao;
      $oLinha->inscritas   = 0;
      $oLinha->exercicio   = 0;
      $oLinha->colunameses = $this->getDadosColuna();
      $oParametros         = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu,
                                                                       $this->getInstituicoes());
      /**
       * Verifica nas configura��es se possui valores configurados por linha.
       */
      $aValoresColunasLinhas = $aLinhasRelatorio[$iLinha]->getValoresColunas(null, null,
                                                                             $this->getInstituicoes(),
                                                                             $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValor) {

      	if (isset($oLinha->colunameses[$oValor->colunas[0]->o117_valor])) {

         $oLinha->colunameses[$oValor->colunas[0]->o117_valor]->nValor += $oValor->colunas[1]->o117_valor;
         $oLinha->exercicio                                            += $oValor->colunas[1]->o117_valor;
         $oLinha->inscritas                                            += $oValor->colunas[2]->o117_valor;
        }
      }

      /**
       * Percore as colunas do per�odo dos �ltimos 12 m�ses.
       */
      foreach ($oLinha->colunameses as $sChaveMes => $oDadosMes) {

      	/**
      	 * Informa as datas inicial e final do per�odo.
      	 */
        $sDataInicialPeriodo = "{$oDadosMes->iAno}-{$oDadosMes->iMes}-01";
        $sDataFinalPeriodo   = "{$oDadosMes->iAno}-{$oDadosMes->iMes}-{$oDadosMes->iDiaFim}";

        /**
         * Executa o saldo da dota��o.
         */
        $sWhereDespesa = "o58_instit in ({$this->getInstituicoes()})";
        $rsDespesa     = db_dotacaosaldo(8, 2, 3, true, $sWhereDespesa, $oDadosMes->iAno,
                                         $sDataInicialPeriodo, $sDataFinalPeriodo);

        /**
         * Verifica o saldo das contas por linha e m�s do relat�rio
         */
        for ($iDespesa = 0; $iDespesa < pg_numrows($rsDespesa); $iDespesa++) {

          $oDespesa = db_utils::fieldsmemory($rsDespesa, $iDespesa);

          /**
           * Percorre as contas configuradas.
           */
          foreach ($oParametros->contas as $oConta) {

            $oVerificacao = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametros->orcamento, $oDespesa, 2);
            if ($oVerificacao->match) {

              $oDespesaValores = clone $oDespesa;
              if ($oVerificacao->exclusao) {

                /**
                 * Somas apenas os valor liquidados.
                 */
                $oDespesaValores->liquidado           *= -1;

                /**
                 * Soma os demais valores
                 */
                $oDespesaValores->liquidado_acumulado *= -1;
                $oDespesaValores->empenhado_acumulado *= -1;
                $oDespesaValores->anulado_acumulado   *= -1;
              }

              $oLinha->colunameses[$sChaveMes]->nValor += $oDespesaValores->liquidado;
              $oLinha->exercicio                       += $oDespesaValores->liquidado;

              /**
               * Verifica totaliza��o das inscritas do �ltimo per�odo.
               */
              if ($oDadosPeriodo->o114_sigla == "3Q" || $oDadosPeriodo->o114_sigla == "2S"
                  || $oDadosPeriodo->o114_sigla == "DEZ") {

                $aChaveMesColunaMeses = array_keys($oLinha->colunameses);
                if (trim($aChaveMesColunaMeses[11]) == trim($sChaveMes)) {

                  $oLinha->inscritas += abs(round(
                    $oDespesaValores->empenhado_acumulado -
                    $oDespesaValores->anulado_acumulado -
                    $oDespesaValores->liquidado_acumulado, 2)
                  );
                }
              }
            }
          }
        }
      }

      /**
       * Monta as linhas dos quadros do demostrativo.
       */
      if ($iLinha <= 3) {
        $oDespesaBruta->linhas[$iLinha]        = $oLinha;
      } else if ($iLinha >= 4) {
        $oDespesaNaoComputada->linhas[$iLinha] = $oLinha;
      }
    }

    /*
     * Calcula linhas totalizadoras da despesa bruta.
     */
    foreach ($oDespesaBruta->linhas as $oDadosLinhaDespesaBruta) {

      $oDespesaBruta->inscritas += $oDadosLinhaDespesaBruta->inscritas;
      $oDespesaBruta->exercicio += $oDadosLinhaDespesaBruta->exercicio;
      foreach ($oDadosLinhaDespesaBruta->colunameses as $sChaveMesDespesaBruta => $oDadosMesDespesaBruta) {

      	if (isset($oDespesaBruta->colunameses[$sChaveMesDespesaBruta])) {
          $oDespesaBruta->colunameses[$sChaveMesDespesaBruta]->nValor += $oDadosMesDespesaBruta->nValor;
      	}
      }
    }

    /**
     * Calcula linhas totalizadoras da despesa n�o computada.
     */
    foreach ($oDespesaNaoComputada->linhas as $oDadosLinhaDespesaNaoComputada) {

      $oDespesaNaoComputada->inscritas += $oDadosLinhaDespesaNaoComputada->inscritas;
      $oDespesaNaoComputada->exercicio += $oDadosLinhaDespesaNaoComputada->exercicio;
      foreach ($oDadosLinhaDespesaNaoComputada->colunameses as $sChaveMesDespesaNaoComputada
                 => $oDadosMesDespesaNaoComputada) {

        if (isset($oDespesaNaoComputada->colunameses[$sChaveMesDespesaNaoComputada])) {
          $oDespesaNaoComputada->colunameses[$sChaveMesDespesaNaoComputada]
                               ->nValor += $oDadosMesDespesaNaoComputada->nValor;
        }
      }
    }

    /**
     * Calcula linhas totalizadoras da despesa liqu�da.
     */
    $oDespesaLiquida->inscritas = ($oDespesaBruta->inscritas - $oDespesaNaoComputada->inscritas);
    $oDespesaLiquida->exercicio = ($oDespesaBruta->exercicio - $oDespesaNaoComputada->exercicio);
    foreach ($oDespesaLiquida->colunameses as $sChaveMesDespesaLiquida => $oDadosMesDespesaLiquida) {

    	if (isset($oDespesaLiquida->colunameses[$sChaveMesDespesaLiquida])) {
    		$oDespesaLiquida->colunameses[$sChaveMesDespesaLiquida]->nValor = ( $oDespesaBruta
    		                                                                      ->colunameses[$sChaveMesDespesaLiquida]
    		                                                                      ->nValor
    		                                                                    - $oDespesaNaoComputada
    		                                                                      ->colunameses[$sChaveMesDespesaLiquida]
    		                                                                      ->nValor );
    	}
    }

    /**
     * Verifica valor RCL nas configura��es da linha 8.
     */
    $aValoresColunasLinhas = $aLinhasRelatorio[8]->getValoresColunas(null, null,
                                                                     $this->getInstituicoes(),
                                                                     $this->iAnoUsu);
    foreach($aValoresColunasLinhas as $oValor) {

      if (isset($oValor->colunas[0]->o117_valor)) {
        $nTotalRCL += $oValor->colunas[0]->o117_valor;
      }
    }

    if ($nTotalRCL == 0) {
      /**
       * Para o c�lculo da RCL, a base de c�lculo
       * deve ser feita por todas as institui��es.
       */
      $rsInstituicoes = db_query("SELECT codigo FROM db_config;");
      $oInstituicoes  = db_utils::getColectionByRecord($rsInstituicoes);
      $aInstituicoes  = array();
      foreach ($oInstituicoes as $oInstituicao){
          $aInstituicoes[]= $oInstituicao->codigo;
      }
      $sInstituicoes  =  implode(",",$aInstituicoes);

	    /**
	     * Calcula RCL - duplicaReceitaaCorrenteLiquida.
	     */
      $dtInicialAnterior = ($this->iAnoUsu-1)."-01-01";
      $dtFinalAnterior   = ($this->iAnoUsu-1)."-12-31";

      duplicaReceitaaCorrenteLiquida($this->iAnoUsu, 81);

      $nTotalRCL += calcula_rcl2($this->iAnoUsu, $this->getDataInicial(), $this->getDataFinal(),
                                 $sInstituicoes, false, 81);
      $nTotalRCL += calcula_rcl2(($this->iAnoUsu-1), $dtInicialAnterior, $dtFinalAnterior,
                                 $sInstituicoes, false, 81, $this->getDataFinal());
    }

    $nValorDespesaTotalPessoal = ($oDespesaLiquida->exercicio + $oDespesaLiquida->inscritas);

    /**
     * Verifica valor % despesa total com pessoal - DTP sobre a RCL (VI)=(IV/V)*100.
     */
    if ($nTotalRCL > 0) {
      $nValorDesepesaTotalPessoalSobreRCL = ($nValorDespesaTotalPessoal/$nTotalRCL)*100;
    } else {
      $nValorDesepesaTotalPessoalSobreRCL = 0;
    }

    /**
     * Soma valores totail do limite maximo (incisos I, II e III, art. 20 da LRF) e
     * do limite prudencial (par�grafo �nico, art. 22 da LRF).
     */
    $nValorLimiteMaximo     = (($nTotalRCL + 0) * $iLimiteMaximo) / 100;
    $nValorLimitePrudencial = (($nTotalRCL + 0) * $iLimitePrudencial) / 100;
    $nValorLimiteAlerta     = (($nTotalRCL + 0) * $iLimiteMaximoAlerta) / 100;

    $oDespesaTotalComPessoal->valorapurado       = $nValorDespesaTotalPessoal;
    $oReceitaTotalCorrenteLiquida->valorapurado  = $nTotalRCL;
    $oDespesaTotalComPessoalSemRCL->valorapurado = $nValorDesepesaTotalPessoalSobreRCL;
    $oLimiteMaximo->percentuallimite             = $iLimiteMaximo;
    $oLimiteMaximo->valorapurado                 = $nValorLimiteMaximo;
    $oLimitePrudencial->percentuallimite         = $iLimitePrudencial;
    $oLimitePrudencial->valorapurado             = $nValorLimitePrudencial;

    $oLimiteAlerta->percentuallimite             = $iLimiteMaximoAlerta;
    $oLimiteAlerta->valorapurado                 = $nValorLimiteAlerta;

    /**
     * Monta Object para retorno com todos os dados por quadro informado.
     */
    $oRetorno                                     = new stdClass();
    $oRetorno->quadrodespesabruta                 = $oDespesaBruta;
    $oRetorno->quadrodespesanaocomputadas         = $oDespesaNaoComputada;
    $oRetorno->quadrodespesaliquida               = $oDespesaLiquida;
    $oRetorno->quadrodespesatotalcompessoal       = $oDespesaTotalComPessoal;
    $oRetorno->quadroreceitatotalcorrenteliquida  = $oReceitaTotalCorrenteLiquida;
    $oRetorno->quadrodespesatotalcompessoalsemrcl = $oDespesaTotalComPessoalSemRCL;
    $oRetorno->quadrolimitemaximo                 = $oLimiteMaximo;
    $oRetorno->quadrolimiteprudencial             = $oLimitePrudencial;
    $oRetorno->quadrolimitealerta                 = $oLimiteAlerta;

    unset($aLinhasRelatorio);

    $this->oDados = $oRetorno;
    return $this->oDados;
  }

  /**
   * Retorna dos dados simplificados do relat�rio.
   *
   * @return Object $oDadosSimplificado;
   */
  public function getDadosSimplificado() {

	  /**
	   * Monta o Object de retorno do m�todo.
	   */
    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->despesatotalpessoal->valorapurado         = 0;
    $oDadosSimplificado->receitacorrenteliquida->valorapurado      = 0;
    $oDadosSimplificado->despesatotalpessoalsobreRCL->valorapurado = 0;
    $oDadosSimplificado->limitemaximo->percentuallimite            = 0;
    $oDadosSimplificado->limitemaximo->valorapurado                = 0;
    $oDadosSimplificado->limiteprudencial->percentuallimite        = 0;
    $oDadosSimplificado->limiteprudencial->valorapurado            = 0;
    if (empty($this->oDados)) {
      $this->getDados();
    }

    /**
     * Adiciona ao Object os respectivos valores.
     */
    $oDadosSimplificado->despesatotalpessoal->valorapurado         = $this->oDados
                                                                          ->quadrodespesatotalcompessoal
                                                                          ->valorapurado;
    $oDadosSimplificado->receitacorrenteliquida->valorapurado      = $this->oDados
                                                                          ->quadroreceitatotalcorrenteliquida
                                                                          ->valorapurado;
    $oDadosSimplificado->despesatotalpessoalsobreRCL->valorapurado = $this->oDados
                                                                          ->quadrodespesatotalcompessoalsemrcl
                                                                          ->valorapurado;
    $oDadosSimplificado->limitemaximo->percentuallimite            = $this->oDados
                                                                          ->quadrolimitemaximo
                                                                          ->percentuallimite;
    $oDadosSimplificado->limitemaximo->valorapurado                = $this->oDados
                                                                          ->quadrolimitemaximo
                                                                          ->valorapurado;
    $oDadosSimplificado->limiteprudencial->percentuallimite        = $this->oDados
                                                                          ->quadrolimiteprudencial
                                                                          ->percentuallimite;
    $oDadosSimplificado->limiteprudencial->valorapurado            = $this->oDados
                                                                          ->quadrolimiteprudencial
                                                                          ->valorapurado;

    return $oDadosSimplificado;
  }

  /**
   * M�todo para pesquisar as colunas de cada m�s conforme o per�odo informado, retornando os 12 �ltimos m�ses
   * anteriores ao exerc�cio.
   *
   * @return array $aRetorno
   */
  public function getDadosColuna() {

  	$aRetorno                     = array();
	  $aUltimaData                  = explode("-", $this->getDataFinal());
		$iMesFinalExecicioAtual       = $aUltimaData[1];
		$iMesInicialExercicioAnterior = ($aUltimaData[1]+1);
		$iAnoExercicioAtual           = $this->iAnoUsu;
		$iAnoExercicioAnterior        = ($this->iAnoUsu-1);

	  /**
	   * Verifica o m�s inicial do exercic�o anterior ao m�s 12.
	   */
	  if ($iMesInicialExercicioAnterior != 13) {

	    /**
	     * Coluna m�s exerc�cio anterior.
	     */
		  for ($iIndMesAnterior = $iMesInicialExercicioAnterior; $iIndMesAnterior <= 12; $iIndMesAnterior++) {

		  	$oDadosMesColuna             = new stdClass();
		  	$oDadosMesColuna->sDescricao = $this->getDescricaoMes($iIndMesAnterior)."/".$iAnoExercicioAnterior;
		  	$oDadosMesColuna->iAno       = $iAnoExercicioAnterior;
		  	$oDadosMesColuna->iMes       = $iIndMesAnterior;
		  	$oDadosMesColuna->iDiaIni    = 1;
		  	$oDadosMesColuna->iDiaFim    = cal_days_in_month(CAL_GREGORIAN, $iIndMesAnterior,
		  	                                                                $iAnoExercicioAnterior);
        $oDadosMesColuna->nValor     = 0;

        if ($iIndMesAnterior <= 9) {
          $iMesAnterior              = str_pad('0', 2, $iIndMesAnterior);
        } else {
        	$iMesAnterior              = $iIndMesAnterior;
        }

		  	$aRetorno[$iMesAnterior."/".$iAnoExercicioAnterior] = $oDadosMesColuna;
	      if ($iIndMesAnterior == 12) {
	        $iAnoExercicioAnterior++;
	      }
	    }
	  }

	  /**
	   * Coluna m�s exerc�cio atual.
	   */
	  for ($iIndMesAtual = 1; $iIndMesAtual <= $iMesFinalExecicioAtual; $iIndMesAtual++) {

	    $oDadosMesColuna             = new stdClass();
      $oDadosMesColuna->sDescricao = $this->getDescricaoMes($iIndMesAtual)."/".$iAnoExercicioAtual;
      $oDadosMesColuna->iAno       = $iAnoExercicioAtual;
      $oDadosMesColuna->iMes       = $iIndMesAtual;
      $oDadosMesColuna->iDiaIni    = 1;
      $oDadosMesColuna->iDiaFim    = cal_days_in_month(CAL_GREGORIAN, $iIndMesAtual, $iAnoExercicioAtual);
      $oDadosMesColuna->nValor     = 0;

      if ($iIndMesAtual <= 9) {
        $iMesAtual                 = str_pad('0', 2, $iIndMesAtual);
      } else {
      	$iMesAtual                 = $iIndMesAtual;
      }

      $aRetorno[$iMesAtual."/".$iAnoExercicioAtual] = $oDadosMesColuna;
	    if ($iIndMesAtual == 12) {
	      $iAnoExercicioAtual++;
	    }
	  }

	  return $aRetorno;
  }

  /**
   * M�todo para inserir a descri��o do m�s informado.
   *
   * @param integer_type $iIndice
   * @return string $sDescricaoMes
   */
  private function getDescricaoMes($iIndice=1) {

  	/**
  	 * Pesquisa por m�s e monta a descri��o.
  	 */
  	switch ($iIndice) {

  		case 1:
  		  $sDescricaoMes = 'Jan';
  		  break;

      case 2:
        $sDescricaoMes = 'Fev';
        break;

      case 3:
        $sDescricaoMes = 'Mar';
        break;

      case 4:
        $sDescricaoMes = 'Abr';
        break;

      case 5:
        $sDescricaoMes = 'Mai';
        break;

      case 6:
        $sDescricaoMes = 'Jun';
        break;

      case 7:
        $sDescricaoMes = 'Jul';
        break;

      case 8:
        $sDescricaoMes = 'Ago';
        break;

      case 9:
        $sDescricaoMes = 'Set';
        break;

      case 10:
        $sDescricaoMes = 'Out';
        break;

      case 11:
        $sDescricaoMes = 'Nov';
        break;

      case 12:
        $sDescricaoMes = 'Dez';
        break;

  		default:
  			throw new Exception("M�s ({$iIndice}) informado � inv�lido! Verifique o per�odo informado.");
  		  break;
  	}

    return $sDescricaoMes;
  }
}
?>