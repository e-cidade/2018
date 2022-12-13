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



/**
 * Implementação de Facede para a lógica implementada no RPC edu4_lancamentoavaliacao.RPC.php
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *
 */
final class LancamentoAvaliacaoAluno {

  /**
   * Salva os dados do Diário de Classe de uma Matricula
   * @param Matricula $oMatricula
   * @throws DBException
   */
  static function salvaAvaliacaoAluno(Matricula $oMatricula) {

    $oDiarioClasse = $oMatricula->getDiarioDeClasse();
    $oDiarioClasse->salvar();
  }

  /**
   * Salva os dados do Diário de Classe de uma Matricula
   * @param Matricula $oMatricula
   * @throws DBException
   */
  static function salvaAvaliacaoDisciplinaAluno(Matricula $oMatricula, Regencia $oDisciplina) {

    $oDiarioClasse = $oMatricula->getDiarioDeClasse();
    $oDisciplinaDiario = $oDiarioClasse->getDisciplinasPorRegencia($oDisciplina);
    if (!empty($oDisciplinaDiario)) {
     $oDisciplinaDiario->salvar();
    }
  }



  /**
   * Calcula o resultado final com base nos valores das avaliações informadas
   * @param Matricula $oMatricula
   * @param stdClass $oAvaliacao   Representa os dados de uma avaliacao (nota e falta) de uma
   *                               disciplina em um determinado periodo
   * @param
   */
  static function calcularResultado (Matricula $oMatricula, $oAvaliacao, $oRetorno) {

    $oDiario                = $oMatricula->getDiarioDeClasse();
    $iAno                   = $oMatricula->getTurma()->getCalendario()->getAnoExecucao();
    $oDiarioDisciplina      = $oDiario->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($oAvaliacao->iCodigoRegencia));
    $oAproveitamentoAlterar = $oDiarioDisciplina->getAvaliacoesPorOrdem($oAvaliacao->iPeriodo);

    if ( !$oAproveitamentoAlterar instanceof AvaliacaoAproveitamento) {
      throw new Exception("Período de Avaliação não encontrado no Diário do Aluno. Contate o Suporte.");
    }

    $oFormaAvaliacao        = $oAproveitamentoAlterar->getElementoAvaliacao()->getFormaDeAvaliacao();
    $oValorAproveitamento   = FormaObtencao::getTipoValorAproveitamento($oFormaAvaliacao);

    $oValorAproveitamento->setAproveitamento($oAvaliacao->nNota);
    $oValorAproveitamento->setAproveitamentoReal($oAvaliacao->nNota);
    if ($oValorAproveitamento->hasOrdem()) {
      $oValorAproveitamento->setOrdem($oAvaliacao->iOrdem);
    }

    $nValorMinimo         = $oAproveitamentoAlterar->getElementoAvaliacao()->getAproveitamentoMinimo();
    $nValorAproveitamento = $oValorAproveitamento->getAproveitamento();

    /**
     * Quando possuimos avaliacao por conceito, devemos verificar o nivel do mesmo;
     */
    if ($oFormaAvaliacao->getTipo() == 'NIVEL') {

      $oConceitoMinimo      = $oFormaAvaliacao->getConceitoMinimo();
      $nValorMinimo         = $oConceitoMinimo->iOrdem;
      $nValorAproveitamento = $oValorAproveitamento->getOrdem();
    }
    $oAproveitamentoAlterar->setAproveitamentoMinimo(true);
    if ($nValorAproveitamento < $nValorMinimo) {
      $oAproveitamentoAlterar->setAproveitamentoMinimo(false);
    }

    $oAproveitamentoAlterar->setValorAproveitamento($oValorAproveitamento);
    $aResultados               = $oDiarioDisciplina->getResultados();
    $aResultadosRetorno        = array();
    $oElementoAvaliacaoPeriodo = $oAproveitamentoAlterar->getElementoAvaliacao();
    $oRetorno->iCodigoRegencia = $oAvaliacao->iCodigoRegencia;
    $oRetorno->iCodigoPeriodo  = $oAvaliacao->iPeriodo;
    $oRetorno->lMinimoAtingido = $oAproveitamentoAlterar->temAproveitamentoMinimo();
    $oPeriodoDependente        = $oDiarioDisciplina->getAvaliacaoDependentesDoPeriodo($oElementoAvaliacaoPeriodo);

    $oUltimoResultado   = null;
    $aPeriodosVerificar = array();

    foreach ($aResultados as $oResultado) {

      $oUltimoResultado = $oUltimoResultado;
      if ($oResultado->getElementoAvaliacao()->getOrdemSequencia() <
          $oAproveitamentoAlterar->getElementoAvaliacao()->getOrdemSequencia()) {
        continue;
      }

      /**
       * Calcula o valor do Resultado final o Retorna uma instancia de ValorResultado
       */
      $oValorResultado = $oResultado->getElementoAvaliacao()->getResultado( $oDiarioDisciplina->getAvaliacoes(), false, $iAno );
      if ( is_null( $oValorResultado->getAproveitamentoReal() ) ) {
        $oValorResultado->setAproveitamentoReal($oValorResultado->getAproveitamento());
      }


      /* calula a nota real */
      $mNotaReal = DiarioAvaliacaoDisciplina::calcularResultadoReal( $oResultado->getElementoAvaliacao(), $oDiarioDisciplina->getDiario(), $oDiarioDisciplina->getAvaliacoes(), $iAno);
      if ( !is_null($mNotaReal) ) {
        $oValorResultado->setAproveitamentoReal($mNotaReal);
      }

      $oFormaAvaliacao = $oResultado->getElementoAvaliacao()->getFormaDeAvaliacao();

      /**
       * Setamos o valor do resultado no aproveitamento
       */
      $oResultado->setValorAproveitamento($oValorResultado);
      $nValorMinimo        = $oResultado->getElementoAvaliacao()->getAproveitamentoMinimo();
      $nResultadoVerificar = $oValorResultado->getAproveitamento();

      $oValorResultado->setAproveitamento($nResultadoVerificar);
      $oResultado->setAproveitamentoMinimo(true);

      if ($oFormaAvaliacao->getTipo() == 'NIVEL') {

        $oConceitoMinimo     = $oFormaAvaliacao->getConceitoMinimo();
        $nValorMinimo        = $oConceitoMinimo->iOrdem;
        $nResultadoVerificar = $oValorAproveitamento->getOrdem();
      }

      if ($nResultadoVerificar < $nValorMinimo) {
        $oResultado->setAproveitamentoMinimo(false);
      }

      $oPeriodoDependente = $oDiarioDisciplina->getAvaliacaoDependentesDoPeriodo($oResultado->getElementoAvaliacao());
      if ($oPeriodoDependente) {

        if ( $oResultado->temAproveitamentoMinimo()) {
          $oPeriodoDependente->getValorAproveitamento()->setAproveitamento('');
        }

        $oDadosPeriodoVerificar                         = new stdClass();
        $oDadosPeriodoVerificar->oResultado             = $oResultado->getElementoAvaliacao();
        $oDadosPeriodoVerificar->oPeriodoDependente     = $oPeriodoDependente->getElementoAvaliacao();
        $oDadosPeriodoVerificar->iDisciplinasReprovadas = $oPeriodoDependente->getElementoAvaliacao()
                                                                             ->quantidadeMaximaDisciplinasParaRecuperacao();
        $aPeriodosVerificar[] = $oDadosPeriodoVerificar;
      }

      $oResultadoRetorno        = new stdClass();
      $oResultadoRetorno->nNota = ArredondamentoNota::formatar($oValorResultado->getAproveitamentoReal(), $iAno);

      $oResultadoRetorno->iOrdem          = '';
      $oResultadoRetorno->iCodigoRegencia = $oDiarioDisciplina->getRegencia()->getCodigo();
      $oResultadoRetorno->iPeriodo        = $oResultado->getElementoAvaliacao()->getOrdemSequencia();
      $oResultadoRetorno->lMinimoAtingido = $oResultado->temAproveitamentoMinimo();
      $oResultadoRetorno->lAmparado       = $oResultado->isAmparado();

      $aReprovacoesPeriodo = $oDiario->getDisciplinasReprovadasNoPeriodo($oResultado->getElementoAvaliacao());

      $oResultadoRetorno->iTotalDisciplinasReprovadas = count($aReprovacoesPeriodo);

      if ($oValorResultado->hasOrdem()) {
        $oResultadoRetorno->iOrdem = $oValorResultado->getOrdem();
      }

      $aResultadosRetorno[] = $oResultadoRetorno;
      $oUltimoResultado     = $oResultado;
    }



    /**
     * Ajuste das recuperacoes.
     * caso exista limite de disciplinas para reprovacao, o sistema era realizar o bloqueio dos valores da
     * recuperacao
     */
    foreach ($aPeriodosVerificar as $oPeriodosRecuperacao) {

      $iLimiteReprovacao   = $oPeriodosRecuperacao->iDisciplinasReprovadas;
      $aReprovacoesPeriodo = $oDiario->getDisciplinasReprovadasNoPeriodo($oPeriodosRecuperacao->oResultado);

      if ($iLimiteReprovacao > 0 && count($aReprovacoesPeriodo) > $iLimiteReprovacao) {

        foreach($aReprovacoesPeriodo as $aDisciplinasReprovadasNoPeriodo) {

          $iOrdemPeriodo   = $oPeriodosRecuperacao->oPeriodoDependente->getOrdemSequencia();
          $oAproveitamento = $aDisciplinasReprovadasNoPeriodo->getAvaliacoesPorOrdem($iOrdemPeriodo);
          if (!empty($oAproveitamento)) {

            $oAproveitamento->getValorAproveitamento()->setAproveitamento('');
            $oAproveitamento->setEmRecuperacao(false);
          }
        }
      }
    }

    $oRetorno->aResultados                = $aResultadosRetorno;
    $oRetorno->nMediaParcial              = '';
    $oRetorno->iPeriodoMediaParcial       = '';
    $oRetorno->iCodigoPeriodoMediaParcial = '';

    if (!empty($oUltimoResultado)) {

      $oRetorno->iPeriodoMediaParcial       = $oResultado->getElementoAvaliacao()->getOrdemSequencia();
      $oRetorno->iCodigoPeriodoMediaParcial = $oResultado->getCodigo();
      $oRetorno->nMediaParcial              = $oDiarioDisciplina->getNotaParcial($oResultado->getElementoAvaliacao());
    }

    return $oMatricula;

  }


  /**
   * Adiciona o parecer a avaliacao de uma matricula.
   * @param Matricula $oMatricula
   * @param Regencia  $oRegencia
   * @param integer   $iOrdem ordem do período de avaliacao
   * @param string    $sParecer
   * @param string    $sParecerPadronizado
   * @return Matricula
   */
  static function salvarParecer(Matricula $oMatricula, Regencia $oRegencia, $iOrdem, $sParecer, $sParecerPadronizado) {

    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina      = $oDiario->getDisciplinasPorRegencia($oRegencia);
    $oAproveitamentoAlterar = $oDiarioDisciplina->getAvaliacoesPorOrdem($iOrdem);
    $oValorAproveitamento   = new ValorAproveitamentoParecer($sParecer);
    $oAproveitamentoAlterar->setValorAproveitamento($oValorAproveitamento);

    if (!$oAproveitamentoAlterar->getElementoAvaliacao()->isResultado()) {
      $oAproveitamentoAlterar->setAproveitamentoMinimo(true);
    }
    $oAproveitamentoAlterar->setParecerPadronizado($sParecerPadronizado);
    return $oMatricula;
  }

  /**
   * Adiciona o parecer complementar a avaliacao de uma matricula.
   * @param Matricula $oMatricula
   * @param Regencia  $oRegencia
   * @param integer   $iOrdem ordem do período de avaliacao
   * @param string    $sParecer
   * @return Matricula
   */
  static function salvarParecerComplementar(Matricula $oMatricula, Regencia $oRegencia, $iOrdem, $sParecer, $sParecerPadronizado) {

    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina      = $oDiario->getDisciplinasPorRegencia($oRegencia);
    $oAproveitamentoAlterar = $oDiarioDisciplina->getAvaliacoesPorOrdem($iOrdem);

    $oAproveitamentoAlterar->setParecerPadronizado($sParecerPadronizado);
    $oAproveitamentoAlterar->setParecer($sParecer);

    $oDiarioDisciplina->salvar();
  }

  /**
   *
   * @param Matricula $oMatricula
   * @param Regencia  $oRegencia
   * @param integer   $iOrdem ordem do período de avaliacao
   * @param boolean   $lAproveitamentoMinimo
   * @return Matricula
   */
  static function salvarResultadoParecer(Matricula $oMatricula, Regencia $oRegencia,
                                         $iOrdem, $lAproveitamentoMinimo, $lRecuperacao = false) {

    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina      = $oDiario->getDisciplinasPorRegencia($oRegencia);
    $oAproveitamentoAlterar = $oDiarioDisciplina->getAvaliacoesPorOrdem($iOrdem);
    $oAproveitamentoAlterar->setAproveitamentoMinimo($lAproveitamentoMinimo);
    $oAproveitamentoAlterar->setEmRecuperacao($lRecuperacao);
    return $oMatricula;
  }

  /**
   * Retorna os parecers de uma Matricula para uma disciplina e um período(ordem do período)
   * @param Matricula $oMatricula
   * @param Regencia $oRegencia
   * @param integer $iOrdem ordem do período de avaliacao
   * @return stdClass
   */
  static function getParecer (Matricula $oMatricula, Regencia $oRegencia, $iOrdem) {

    $oRetornoParecer                      = new stdClass();
    $oDiario                              = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina                    = $oDiario->getDisciplinasPorRegencia($oRegencia);
    $oAproveitamentoAlterar               = $oDiarioDisciplina->getAvaliacoesPorOrdem($iOrdem);
    $oRetornoParecer->sParecerPadronizado = $oAproveitamentoAlterar->getParecerPadronizado();
    $oRetornoParecer->sParecer            = $oAproveitamentoAlterar->getValorAproveitamento()->getAproveitamento();

    return $oRetornoParecer;

  }

  /**
   * Retorna os parecers complementares de uma Matricula para uma disciplina e um período(ordem do período)
   * @param Matricula $oMatricula
   * @param Regencia  $oRegencia
   * @param integer   $iOrdem      ordem do período de avaliacao
   * @return stdClass
   */
  static function getParecerComplementar (Matricula $oMatricula, Regencia $oRegencia, $iOrdem) {

    $oRetornoParecer                      = new stdClass();
    $oDiario                              = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina                    = $oDiario->getDisciplinasPorRegencia($oRegencia);
    $oAproveitamentoAlterar               = $oDiarioDisciplina->getAvaliacoesPorOrdem($iOrdem);
    if ( !$oAproveitamentoAlterar instanceof AvaliacaoAproveitamento) {
      throw new Exception("Período de Avaliação não encontrado no Diário do Aluno. Contate o Suporte.");
    }
    $oRetornoParecer->sParecerPadronizado = $oAproveitamentoAlterar->getParecerPadronizado();
    $oRetornoParecer->sParecer            = $oAproveitamentoAlterar->getParecer();
    return $oRetornoParecer;

  }

  /**
   * Retorna os parecers de uma Matricula para uma disciplina e um período(ordem do período)
   * @param Matricula $oMatricula
   * @param Regencia $oRegencia
   * @param integer $iOrdem ordem do período de avaliacao
   * @param integer $iFaltas numero de faltas no período
   * @return Matricula
   */
  static function setFalta(Matricula $oMatricula, Regencia $oRegencia, $iOrdem, $iFaltas) {

    $oDiario                = $oMatricula->getDiarioDeClasse();
    $oDiarioDisciplina      = $oDiario->getDisciplinasPorRegencia($oRegencia);
    $oAproveitamentoAlterar = $oDiarioDisciplina->getAvaliacoesPorOrdem($iOrdem);
    $oAproveitamentoAlterar->setNumeroFaltas($iFaltas);

    return $oMatricula;
  }


  /**
   * Retorna os pareceres vinculados a disciplina e período de avaliação
   * @param Regencia $oRegencia
   * @param integer $iPeriodoAvaliacao
   * @return stdClass
   */
  static function getParecerDisciplina(Regencia $oRegencia, $iPeriodoAvaliacao) {

    $iEscola = db_getsession('DB_coddepto');
    /**
     * Buscamos os pareceres vinculados a uma disciplina
     */
    $aPareceres               = array();
    $oDaoParecerDisciplina    = new cl_parecer();
    $sCamposParecerDisciplina = "distinct ed92_i_codigo, ed92_c_descr, ed92_i_sequencial";

    $aWhereParecer   = array();
    $aWhereParecer[] = " ed105_i_turma = " . $oRegencia->getTurma()->getCodigo();

    if (!empty($oRegencia)) {

      $sDisciplina     = " (ed106_disciplina = {$oRegencia->getDisciplina()->getCodigoDisciplina()} ";
      $sDisciplina    .= " or ed106_disciplina is null) ";
      $aWhereParecer[] = $sDisciplina;
    }

    if (!empty($iPeriodoAvaliacao)) {
      $aWhereParecer[] = " (ed120_periodoavaliacao = {$iPeriodoAvaliacao} or ed120_periodoavaliacao is null )";
    }

    $sWhereParecer         = implode(" and ", $aWhereParecer);
    $sSqlParecerDisciplina = $oDaoParecerDisciplina->sql_query_turma_disciplina_periodo(null,
                                                                                        $sCamposParecerDisciplina,
                                                                                        "ed92_i_sequencial",
                                                                                        $sWhereParecer);
    $rsParecerDisciplina = $oDaoParecerDisciplina->sql_record($sSqlParecerDisciplina);
    $iTotalLinhas        = $oDaoParecerDisciplina->numrows;

    if ($iTotalLinhas > 0) {

      for ($iContadorParecer = 0; $iContadorParecer < $iTotalLinhas; $iContadorParecer++) {

        $oDadosParecerDisciplina            = db_utils::fieldsMemory($rsParecerDisciplina, $iContadorParecer);
        $oParecerDisciplina                 = new stdClass();
        $oParecerDisciplina->iCodigoParecer = $oDadosParecerDisciplina->ed92_i_codigo;
        $oParecerDisciplina->sDescricao     = urlencode($oDadosParecerDisciplina->ed92_c_descr);
        $aPareceres[]                       = $oParecerDisciplina;
      }
      unset($oParecerDisciplina);
    }

    /**
     * Buscamos as legendas cadastradas na escola
     */
    $aLegendas      = array();
    $oDaoLegenda    = new cl_parecerlegenda();
    $sCamposLegenda = "ed91_i_codigo, ed91_sigla";
    $sWhereLegenda  = "ed91_i_escola = {$iEscola}";
    $sSqlLegenda    = $oDaoLegenda->sql_query(null, $sCamposLegenda, null, $sWhereLegenda);
    $rsLegenda      = $oDaoLegenda->sql_record($sSqlLegenda);
    $iTotalLegenda  = $oDaoLegenda->numrows;

    if ($iTotalLegenda > 0) {

      for ($iContadorLegenda = 0; $iContadorLegenda < $iTotalLegenda; $iContadorLegenda++) {

        $oDadosLegenda            = db_utils::fieldsMemory($rsLegenda, $iContadorLegenda);
        $oLegenda                 = new stdClass();
        $oLegenda->iCodigoLegenda = $oDadosLegenda->ed91_i_codigo;
        $oLegenda->sSigla         = urlencode($oDadosLegenda->ed91_sigla);
        $aLegendas[]              = $oLegenda;
      }
    }

    $oRetornoDados            = new stdClass();
    $oRetornoDados->aDados    = $aPareceres;
    $oRetornoDados->aLegendas = $aLegendas;

    return $oRetornoDados;
  }
}
