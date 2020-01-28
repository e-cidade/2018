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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("libs/exceptions/ParameterException.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

$lProfessorLogado            = false;
$lBloqueiaAlteracaoAvaliacao = false;

/**
 * Buscamos o parametro ed233_bloqueioalteracaoavaliacao verificando se a alteracao de notas das avaliacoes deve ser
 * permitida ou nao
 */
$oDaoEduParametros   = new cl_edu_parametros();
$sWhereEduParametros = "ed233_i_escola = {$iEscola}";
$sSqlEduParametros   = $oDaoEduParametros->sql_query_file(null, "ed233_bloqueioalteracaoavaliacao", null, $sWhereEduParametros);
$rsEduParametros     = $oDaoEduParametros->sql_record($sSqlEduParametros);

if ($oDaoEduParametros->numrows > 0) {
  
  $sBloqueio                   = db_utils::fieldsMemory($rsEduParametros, 0)->ed233_bloqueioalteracaoavaliacao;
  $lBloqueiaAlteracaoAvaliacao = $sBloqueio == 't' ? true : false;
}

/**
 * Verifica se um professor esta logado
 */
$iCodigoUsuario   = db_getsession("DB_id_usuario");
$oDocente         = DocenteRepository::getDocenteLogado($iCodigoUsuario);

if ($oDocente != null) {
  $lProfessorLogado = true;
}

$oRetorno->lProfessorLogado = $lProfessorLogado;

try {

  switch($oParam->exec) {

    /**
     * Retornamos os periodos de avaliacao da turma,
     * $oParam->lControlaFrequencia se setado, filtra somente os Procedimentos que controlam frequencia
     */
    case 'getPeriodosAvaliacao':

      if (isset($oParam->iTurma) && isset($oParam->iEtapa) && !empty($oParam->iTurma) && !empty($oParam->iEtapa)) {

        $oRetorno->aPeriodos    = array();
        $oTurma                 = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
        $oEtapa                 = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
        $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
        $_SESSION["oTurma"]     = $oTurma;
        $lControlaFrequencia = false;
        if (isset($oParam->lControlaFrequencia)) {
          $lControlaFrequencia = true;
        }

        $aPeriodosAvaliacaoRetorno = array();

        foreach ($oProcedimentoAvaliacao->getElementos() as $oAvaliacao) {

          $oPeriodosAvaliacao                              = new stdClass();
          $oPeriodosAvaliacao->iTotalDisciplinasReprovadas = 0;
          $oPeriodosAvaliacao->iCodigoAvaliacao            = $oAvaliacao->getCodigo();
          $oPeriodosAvaliacao->iOrdemAvaliacao             = $oAvaliacao->getOrdemSequencia();
          $oPeriodosAvaliacao->geraResultadoFinal          = false;
          $oPeriodosAvaliacao->sFormaAvaliacao             = $oAvaliacao->getFormaDeAvaliacao()->getTipo();
          $oPeriodosAvaliacao->iPeriodoDependenteAprovacao = '';
          $oPeriodosAvaliacao->mMinimoAprovacao            = $oAvaliacao->getAproveitamentoMinimo();
          $oPeriodosAvaliacao->lControlaFrequencia         = false;

          switch ($oPeriodosAvaliacao->sFormaAvaliacao) {

            case 'NOTA':

              $oPeriodosAvaliacao->iMenorValor = $oAvaliacao->getFormaDeAvaliacao()->getMenorValor();
              $oPeriodosAvaliacao->iMaiorValor = $oAvaliacao->getFormaDeAvaliacao()->getMaiorValor();
              $oPeriodosAvaliacao->nVariacao   = $oAvaliacao->getFormaDeAvaliacao()->getVariacao();
              break;
            case 'NIVEL':

              $oPeriodosAvaliacao->aConceitos = array();
              foreach ($oAvaliacao->getFormaDeAvaliacao()->getConceitos() as $oConceito) {

                $oDadoConceito                     = new stdClass();
                $oDadoConceito->iCodigoConceito    = $oConceito->iCodigo;
                $oDadoConceito->sDescricaoConceito = $oConceito->sConceito;
                $oDadoConceito->iOrdem             = $oConceito->iOrdem;
                $oPeriodosAvaliacao->aConceitos[]  = $oDadoConceito;
              }

              break;
          }

          if ($oAvaliacao instanceof ResultadoAvaliacao) {

            $oTipoResultado                                 = $oAvaliacao->getTipoResultado();
            $oPeriodosAvaliacao->sDescricaoPeriodo          = urlencode($oTipoResultado->getDescricao());
            $oPeriodosAvaliacao->sDescricaoPeriodoAbreviado = urlencode($oTipoResultado->getDescricaoAbreviada());
            $oPeriodosAvaliacao->sTipoAvaliacao             = "R";
            $oPeriodosAvaliacao->geraResultadoFinal         = $oAvaliacao->geraResultadoFinal();
            $oPeriodosAvaliacao->sFormaObtencao             = $oAvaliacao->getFormaDeObtencao();
          }

          if ($oAvaliacao instanceof AvaliacaoPeriodica) {

            $oPeriodo                                       = $oAvaliacao->getPeriodoAvaliacao();
            $oPeriodosAvaliacao->sDescricaoPeriodo          = urlencode($oPeriodo->getDescricao());
            $oPeriodosAvaliacao->sDescricaoPeriodoAbreviado = urlencode($oPeriodo->getDescricaoAbreviada());
            $oPeriodosAvaliacao->sTipoAvaliacao             = 'A';
            $oPeriodosAvaliacao->lControlaFrequencia        = $oAvaliacao->getPeriodoAvaliacao()->hasControlaFrequencia();
            $oPeriodosAvaliacao->iLimiteReprovacao          = $oAvaliacao->quantidadeMaximaDisciplinasParaRecuperacao();
            if ($oAvaliacao->getElementoAvaliacaoVinculado() != "") {

              $oPeriodosAvaliacao->iPeriodoDependenteAprovacao = $oAvaliacao->getElementoAvaliacaoVinculado()
                                                                               ->getOrdemSequencia();
            }
          }

          $aPeriodosAvaliacaoRetorno[] = $oPeriodosAvaliacao;
        }

         if (count($aPeriodosAvaliacaoRetorno) == 0) {

           $sMsgErro  = "Não foi possível buscar os períodos de avaliação da turma.\n";
           $sMsgErro .= "Acessar Cadastro > Procedimentos de Avaliação > Alteração e verificar a configuração do ";
           $sMsgErro .= " procedimento: " . $oProcedimentoAvaliacao->getCodigo();
           $sMsgErro .= "  - " . $oProcedimentoAvaliacao->getDescricao();
           throw new BusinessException($sMsgErro);
         }

        $oRetorno->aPeriodos = $aPeriodosAvaliacaoRetorno;
      }

      break;

    /**
     * Retorna todos os alunos de uma turma e as avaliações obtidas em uma regencia
     * Após processamento serializa a turma na sessão do PHP
     * Parâmetros necessários:
     * $oParam->iTurma
     * $oParam->iEtapa
     * $oParam->iRegencia
     */
    case 'getAlunosAvaliacaoRegencia' :

      if (isset($_SESSION["oTurma"]) && $_SESSION["oTurma"] instanceof Turma) {
        $oTurma = $_SESSION["oTurma"];
      } else {
        $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      }

      if (!isset($oParam->iTurma) || !isset($oParam->iEtapa)) {
        throw new BusinessException('Turma ou regencia não informada.');
      }

      if(!isset($oParam->iRegencia)) {
        throw new BusinessException('Nenhuma disciplina informada.');
      }

      $oRetorno->aAlunos   = array();

      $oDaoParametro       = db_utils::getDao('edu_parametros');
      $sWhereParametro     = "ed233_i_escola = " .db_getsession('DB_coddepto') ;
      $sCamposParametro    = "ed233_c_notabranca, ed233_deslocamentocursor";
      
      $sSqlParametro       = $oDaoParametro->sql_query_file(null, $sCamposParametro, null, $sWhereParametro);
      $rsParametro         = $oDaoParametro->sql_record($sSqlParametro);
      $oDadosParametro     = db_utils::fieldsMemory($rsParametro, 0);
      
      $oRetorno->iTabIndex             = $oDadosParametro->ed233_deslocamentocursor;
      $oRetorno->lGeraResultadoParcial = $oDadosParametro->ed233_c_notabranca == 'S' ? true : false;

      $oEtapa      = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      $aMatriculas = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
      $iAno        = $oTurma->getCalendario()->getAnoExecucao();

      $oRetorno->sMascaraFormatacao   = ArredondamentoNota::getMascara($iAno);
      $oRetorno->lAprovacaoAutomatica = false;
      
      foreach ( $oTurma->getEtapas() as $oEtapaTurma ) {
        
        if (    $oEtapaTurma->getEtapa()->getCodigo() == $oEtapa->getCodigo()
             && $oEtapaTurma->temAprovacaoAutomatica()
           ) {
          $oRetorno->lAprovacaoAutomatica = true;
        }
      }
      
      foreach ($aMatriculas as $oMatricula) {

        db_inicio_transacao();
        $oDadosAluno = new stdClass();

        $oDiario = $oMatricula->getDiarioDeClasse();
        $oDadosAluno->iSequencia     = urlencode($oMatricula->getNumeroOrdemAluno());
        $oDadosAluno->sNomeAluno     = urlencode($oMatricula->getAluno()->getNome());
        $oDadosAluno->iCodigoAluno   = $oMatricula->getAluno()->getCodigoAluno();
        $oDadosAluno->iMatricula     = $oMatricula->getCodigo();
        
        $sSituacaoReal = Situacao($oMatricula->getSituacao(),$oMatricula->getCodigo());
        
        $oDadosAluno->sSituacaoReal  = urlencode($sSituacaoReal);
        $oDadosAluno->sSituacaoAluno = urlencode($oMatricula->getSituacao());
        $oDadosAluno->dtMatricula    = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
        $oDadosAluno->dtSaida        = "";
        if ($oMatricula->getDataEncerramento() != "") {
          $oDadosAluno->dtSaida = $oMatricula->getDataEncerramento()->convertTo(DBDate::DATA_PTBR);
        }

        $oDadosAluno->oDisciplina = new stdClass();

        /**
         * Buscamos as avaliações da regencia selecionada
         */
        $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

        $oDadoRegencia                    = new stdClass();
        $oDadoRegencia->iCodigoRegencia   = $oRegencia->getCodigo();
        $oDadoRegencia->sDescricao        = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
        $oDadoRegencia->sFrequenciaGlobal = $oRegencia->getFrequenciaGlobal();
        $oDadoRegencia->aAproveitamentos  = array();
        $oDadosAproveitamento             = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);

        $aAvaliacoesDependentesReprovadas = array();
        foreach ($oDadosAproveitamento->getAvaliacoes() as $oAvaliacao) {

          $sFormaAvaliacao = $oAvaliacao->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo();
          if ($oMatricula->isAvaliadoPorParecer()) {
            $sFormaAvaliacao = 'PARECER';
          }
          
          $nNota           = $oAvaliacao->getValorAproveitamento()->getAproveitamento();
          $sTipoAvaliacao  = 'A';
          $iOrdem          = '';
          $lFaltasAbonadas = false;
          $iFaltasAbonadas = 0;
          $iFaltasPeriodo  = $oAvaliacao->getNumeroFaltas();

          $oElementoAvaliacao = $oAvaliacao->getElementoAvaliacao();
          if ($oAvaliacao->getValorAproveitamento()->hasOrdem()) {
            $iOrdem = $oAvaliacao->getValorAproveitamento()->getOrdem();
          }

          if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {

            $iFaltasPeriodo = $oDadosAproveitamento->getTotalFaltas();
            $sTipoAvaliacao = 'R';
          }
          
          if (!$oElementoAvaliacao->isResultado()) {

            $iFaltasAbonadas = $oAvaliacao->getFaltasAbonadas();
            $lFaltasAbonadas = $lFaltasAbonadas > 0 ? true : false;
          }

          if ($oAvaliacao->getElementoAvaliacao()->isResultado() || $sFormaAvaliacao == 'NOTA') {
            $nNota = ArredondamentoNota::formatar($nNota, $iAno);
          }
          
          
          $sFormaObtencao = '';
          if ($oElementoAvaliacao instanceof ResultadoAvaliacao) {
            $sFormaObtencao = $oElementoAvaliacao->getFormaDeObtencao();
          }

          $oDadosAvaliacao                        = new stdClass();
          $oDadosAvaliacao->iPeriodo              = $oAvaliacao->getElementoAvaliacao()->getOrdemSequencia();
          $oDadosAvaliacao->nNota                 = urlencode("{$nNota}");
          $oDadosAvaliacao->sTipoAvaliacao        = $sTipoAvaliacao;
          $oDadosAvaliacao->iOrdem                = $iOrdem; // Ordem do conceito
          $oDadosAvaliacao->lEncerrado            = $oDadosAproveitamento->isEncerrado();
          $oDadosAvaliacao->lMinimoAtingido       = $oAvaliacao->temAproveitamentoMinimo();
          $oDadosAvaliacao->iFalta                = $iFaltasPeriodo;
          $oDadosAvaliacao->lFaltaBloqueada       = false;
          $oDadosAvaliacao->lNotaBloqueada        = false;
          $oDadosAvaliacao->iAulasPerido          = 0;
          $oDadosAvaliacao->lFaltasAbonadas       = $lFaltasAbonadas;
          $oDadosAvaliacao->iFaltasAbonadas       = $iFaltasAbonadas;
          $oDadosAvaliacao->sTipoEscola           = "";
          $oDadosAvaliacao->sTipoAbreviado        = "";
          $oDadosAvaliacao->sEscola               = "";
          $oDadosAvaliacao->sMunicipio            = "";
          $oDadosAvaliacao->sFormaObtencao        = $sFormaObtencao;
          $oDadosAvaliacao->lRecuperacao          = $oAvaliacao->emRecuperacao();
          $oDadosAvaliacao->lAvaliacaoExterna     = $oAvaliacao->isAvaliacaoExterna();
          $oDadosAvaliacao->lAmparado             = $oAvaliacao->isAmparado();
          $oDadosAvaliacao->lConvertido           = $oAvaliacao->isConvertido();
          $oDadosAvaliacao->sFormaAvaliacao       = urlencode($oAvaliacao->getElementoAvaliacao()
                                                                         ->getFormaDeAvaliacao()
                                                                         ->getDescricao()
                                                             );
          $oDadosAvaliacao->iFormaAvaliacao       = $oAvaliacao->getElementoAvaliacao()
                                                               ->getFormaDeAvaliacao()
                                                               ->getCodigo();
          $oDadosAvaliacao->sTipoFormaAvaliacao   = urlencode($sFormaAvaliacao);
          $oDadosAvaliacao->nMenorValor           = "";
          $oDadosAvaliacao->nMaiorValor           = "";
          $oDadosAvaliacao->mAproveitamentoMinino = $oAvaliacao->getElementoAvaliacao()
                                                               ->getFormaDeAvaliacao()
                                                               ->getAproveitamentoMinino();
          $oDadosAvaliacao->aConceito             = array();

          if ($sFormaAvaliacao == 'NIVEL') {
            $oDadosAvaliacao->aConceito = $oAvaliacao->getElementoAvaliacao()->getFormaDeAvaliacao()->getConceitos();
          }

          if ($sFormaAvaliacao == 'NOTA') {

            $oDadosAvaliacao->nMenorValor = $oAvaliacao->getElementoAvaliacao()->getFormaDeAvaliacao()->getMenorValor();
            $oDadosAvaliacao->nMaiorValor = $oAvaliacao->getElementoAvaliacao()->getFormaDeAvaliacao()->getMaiorValor();
          }

          if ($oAvaliacao->getEscola() != "") {

          	$oDadosAvaliacao->sTipoAbreviado = $oAvaliacao->getTipo();
            $oDadosAvaliacao->sTipoEscola    = $oAvaliacao->getTipo() == "M" ? 'ESCOLA DA REDE' : 'FORA DA REDE';
            $oDadosAvaliacao->sEscola        = urlencode($oAvaliacao->getEscola()->getNome());
            $oDadosAvaliacao->sMunicipio     = urlencode($oAvaliacao->getEscola()->getMunicipio());
          }

          if ($sFormaAvaliacao != 'PARECER' && $oDadosAvaliacao->nNota == '' && !$oDadosAvaliacao->lMinimoAtingido) {
            $oDadosAvaliacao->lMinimoAtingido = true;
          }

          /**
           * Quando possuir avaliação externa, buscamos a origem para verificar se já foi migrado
           */
          $oDadosAvaliacao->oAvaliacaoOrigem                  = new stdClass();
          $oDadosAvaliacao->oAvaliacaoOrigem->sFormaAvaliacao = "";
          $oDadosAvaliacao->oAvaliacaoOrigem->iFormaAvaliacao = "";
          $oDadosAvaliacao->oAvaliacaoOrigem->nMenorValor     = "";
          $oDadosAvaliacao->oAvaliacaoOrigem->nMaiorValor     = "";
          $oDadosAvaliacao->oAvaliacaoOrigem->sTipoEscola     = "";
          $oDadosAvaliacao->oAvaliacaoOrigem->sEscola         = "";
          $oDadosAvaliacao->oAvaliacaoOrigem->sMunicipio      = "";

          if ($oAvaliacao->isAvaliacaoExterna()) {

            $oAvaliacaoOrigem = $oAvaliacao->getAproveitamentoOrigem();

            if (!empty($oAvaliacaoOrigem)) {

              $oDadosAvaliacao->oAvaliacaoOrigem->sFormaAvaliacao = urlencode($oAvaliacaoOrigem->getElementoAvaliacao()
                                                                                               ->getFormaDeAvaliacao()
                                                                                               ->getDescricao()
                                                                              );
              $oDadosAvaliacao->oAvaliacaoOrigem->iFormaAvaliacao = $oAvaliacaoOrigem->getElementoAvaliacao()
                                                                                     ->getFormaDeAvaliacao()
                                                                                     ->getCodigo();

              $sTipoEscola = $oAvaliacaoOrigem->getTipo() == "M" ? 'ESCOLA DA REDE' : 'FORA DA REDE';

              $oDadosAvaliacao->oAvaliacaoOrigem->sTipoEscola = $sTipoEscola;

              $oDadosAvaliacao->oAvaliacaoOrigem->iEscola    = "";
              $oDadosAvaliacao->oAvaliacaoOrigem->sEscola    = "";
              $oDadosAvaliacao->oAvaliacaoOrigem->sMunicipio = "";
              $oDadosAvaliacao->oAvaliacaoOrigem->sEstado    = "";

              $oEscolaOrigem = null;
              $oEscolaOrigem = $oAvaliacaoOrigem->getEscola();
              if (!empty($oEscolaOrigem)) {

                $oDadosAvaliacao->oAvaliacaoOrigem->iEscola    = $oEscolaOrigem->getCodigo();
                $oDadosAvaliacao->oAvaliacaoOrigem->sEscola    = urlencode($oEscolaOrigem->getNome());
                $oDadosAvaliacao->oAvaliacaoOrigem->sMunicipio = urlencode($oEscolaOrigem->getMunicipio());
                $oDadosAvaliacao->oAvaliacaoOrigem->sEstado    = urlencode($oEscolaOrigem->getUf());
              }

              $oDadosAvaliacao->oAvaliacaoOrigem->nMenorValor     = "";
              $oDadosAvaliacao->oAvaliacaoOrigem->nMaiorValor     = "";

              if ($sFormaAvaliacao == 'NOTA') {

                $oDadosAvaliacao->oAvaliacaoOrigem->nMenorValor = $oAvaliacaoOrigem->getElementoAvaliacao()
                                                                                   ->getFormaDeAvaliacao()
                                                                                   ->getMenorValor();
                $oDadosAvaliacao->oAvaliacaoOrigem->nMaiorValor = $oAvaliacaoOrigem->getElementoAvaliacao()
                                                                                   ->getFormaDeAvaliacao()
                                                                                   ->getMaiorValor();
              }
            }
          }

          /**
           * Quando não for Resultado Final
           */
          if (!$oAvaliacao->getElementoAvaliacao()->isResultado()) {

            $iChaveAulasNoPeriodo = "aulasPeriodo{$oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao()->getCodigo()}";
            $iAulasPeriodo        = DBRegistry::get($iChaveAulasNoPeriodo);
            if ($iAulasPeriodo === null) {

              $iAulasPeriodo = $oRegencia->getTotalDeAulasNoPeriodo($oAvaliacao->getElementoAvaliacao()
                                                                               ->getPeriodoAvaliacao()
                                                                  );
              if ($iAulasPeriodo == null) {
                $iAulasPeriodo == 0;
              }
              DBRegistry::add($iChaveAulasNoPeriodo, $iAulasPeriodo);
            }
            $oDadosAvaliacao->lFaltaBloqueada = $iAulasPeriodo > 0 ? true : false;
            $oDadosAvaliacao->iAulasPerido    = $iAulasPeriodo;
            $oDadosAvaliacao->lNotaBloqueada  = trim($oDadosAvaliacao->nNota) != '' && $lBloqueiaAlteracaoAvaliacao && $lProfessorLogado;
          }
          if ($oAvaliacao->getElementoAvaliacao()->isResultado() &&
              $oAvaliacao->getElementoAvaliacao()->geraResultadoFinal()) {
            if ($oRetorno->lGeraResultadoParcial && $sFormaAvaliacao == 'NOTA') {
              $oDadosAvaliacao->nNota = (string)$oDadosAproveitamento->getNotaParcial($oAvaliacao->getElementoAvaliacao());
            }
          }

          $oResultadoFinalRegencia = $oDadosAproveitamento->getResultadoFinal();

          $oDadoRegencia->oResultadoFinal                        = new stdClass();
          $oDadoRegencia->oResultadoFinal->nValor                = '';
          $oDadoRegencia->oResultadoFinal->sResultadoFinal       = '';
          $oDadoRegencia->oResultadoFinal->lPossuiResultadoFinal = false;
          $oDadoRegencia->oResultadoFinal->iAprovadoPeloConselho = 0;

          if (!empty($oResultadoFinalRegencia)) {

            $mAproveitamentoFinal = ArredondamentoNota::formatar($oResultadoFinalRegencia->getValorAprovacao(), $iAno);

            $oDadoRegencia->oResultadoFinal->nValor                = $mAproveitamentoFinal;
            $oDadoRegencia->oResultadoFinal->sResultadoFinal       = urlencode($oResultadoFinalRegencia->getResultadoFinal());
            $oDadoRegencia->oResultadoFinal->lPossuiResultadoFinal = true;

            if (!$oRegencia->isObrigatoria()) {
              $oDadoRegencia->oResultadoFinal->sResultadoFinal = 'A';
            }

            $oAprovadoConselho = $oResultadoFinalRegencia->getFormaAprovacaoConselho();

            if ($oAprovadoConselho != null) {

              // Aluno aprovado pelo conselho sempre estará aprovado na regência
              $oDadoRegencia->oResultadoFinal->sResultadoFinal       = 'A';
              $oDadoRegencia->oResultadoFinal->iAprovadoPeloConselho = $oAprovadoConselho->getFormaAprovacao();
            }
          }


          if ($oDadosAproveitamento->getAmparo() != null && $oDadosAproveitamento->getAmparo()->isTotal()) {
            $oDadoRegencia->oResultadoFinal->nValor = 'AMP';
          }

          /**
           * Somamos todas as disciplinas com o elemento de avaliacoa reprovado no periodo
           * @todo Refatorar
           */
          $oDadosAvaliacao->iTotalDisciplinasReprovadas = count($oDiario->getDisciplinasReprovadasNoPeriodo($oElementoAvaliacao));
          $oDadoRegencia->aAproveitamentos[] = $oDadosAvaliacao;
        }
        $oDadosAluno->oDisciplina = $oDadoRegencia;
        $oRetorno->aAlunos[]      = $oDadosAluno;

        db_fim_transacao(false);
      }

      $_SESSION["oTurma"] = $oTurma;
      $_SESSION["oEtapa"] = $oEtapa;

      db_fim_transacao(false);

      break;

    case 'buscaTermosAvaliacao' :

      $oTurma            = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $iCodigoEnsino     = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
      $aTermos           = DBEducacaoTermo::getTermoEncerramentoDoEnsino($iCodigoEnsino);
      $oRetorno->aTermos = array();

      $iContadorTermos = 1;
      if (count($aTermos) > 0) {

        $oRetorno->aTermos[0]->sReferencia = '';
        $oRetorno->aTermos[0]->sDescricao  = '';
        foreach ($aTermos as $oTermo) {

          $oRetorno->aTermos[$iContadorTermos]->sReferencia = urlencode($oTermo->sReferencia);
          $oRetorno->aTermos[$iContadorTermos]->sDescricao  = urlencode($oTermo->sDescricao);
          $oRetorno->aTermos[$iContadorTermos]->sSigla      = urlencode($oTermo->sAbreviatura);
          $iContadorTermos++;
        }
      } else {

        $oRetorno->aTermos[0]->sReferencia = '';
        $oRetorno->aTermos[0]->sDescricao  = '';
        $oRetorno->aTermos[1]->sReferencia = urlencode('A');
        $oRetorno->aTermos[1]->sDescricao  = urlencode('Aprovado');
        $oRetorno->aTermos[1]->sSigla      = urlencode('Apr');
        $oRetorno->aTermos[2]->sReferencia = urlencode('R');
        $oRetorno->aTermos[2]->sDescricao  = urlencode('Reprovado');
        $oRetorno->aTermos[2]->sSigla      = urlencode('Rep');
      }
      break;

    case 'calcularResultado':

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];
      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        if ($oMatricula->getCodigo() == $oParam->iMatricula) {

          LancamentoAvaliacaoAluno::calcularResultado($oMatricula,
                                                     $oParam->oAvaliacao,
                                                     $oRetorno);
        }
      }
      $_SESSION["oTurma"] = $oTurma;

      break;

    case 'setFalta':

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];
      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        if ($oMatricula->getCodigo() == $oParam->iMatricula) {

          LancamentoAvaliacaoAluno::setFalta($oMatricula,
                                             RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                             $oParam->iPeriodo,
                                             $oParam->iFalta);
        }
      }
      $_SESSION["oTurma"] = $oTurma;

      break;

    case 'getLegendasParecer':

      $oDaoParecerLegenda = db_utils::getDao("parecerlegenda");
      $sSqlLegendas       = $oDaoParecerLegenda->sql_query_file(null,
                                                                 "ed91_i_codigo as codigo,
                                                                  trim(ed91_c_descr) as descricao",
                                                                 "ed91_i_codigo",
                                                                 "ed91_i_escola=".db_getsession("DB_coddepto")
                                                                );

      $rsLegendas          = $oDaoParecerLegenda->sql_record($sSqlLegendas);
      $oRetorno->aLegendas = db_utils::getCollectionByRecord($rsLegendas, false, false, true);
      break;

    case 'salvarParecer':

      $_SESSION["DB_desativar_account"] = true;
      $sParecer            = db_stdClass::normalizeStringJsonEscapeString($oParam->sParecer);
      $sParecerPadronizado = db_stdClass::normalizeStringJsonEscapeString($oParam->sParecerPadronizado);

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];

      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        if ($oMatricula->getCodigo() == $oParam->iMatricula) {

          LancamentoAvaliacaoAluno::salvarParecer($oMatricula,
                                                  RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                  $oParam->iPeriodo,
                                                  $sParecer,
                                                  $sParecerPadronizado
                                                 );
        }
      }
      $_SESSION["oTurma"] = $oTurma;
      unset($_SESSION["DB_desativar_account"]);
      break;

     case 'salvarResultadoParecer':

       $_SESSION["DB_desativar_account"] = true;
       $oTurma = $_SESSION["oTurma"];
       $oEtapa = $_SESSION["oEtapa"];

       foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

         if ($oMatricula->getCodigo() == $oParam->iMatricula) {

           $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
           LancamentoAvaliacaoAluno::salvarResultadoParecer($oMatricula,
                                                            $oRegencia,
                                                            $oParam->iPeriodo,
                                                            $oParam->lAproveitamentoMinimo,
                                                            $oParam->lRecuperacao
                                                          );
         }
       }
       $_SESSION["oTurma"] = $oTurma;
       unset($_SESSION["DB_desativar_account"]);
       break;

     case 'getParecer':

      db_inicio_transacao();

      $oRetorno->sParecerPadronizado = '';
      $oRetorno->sParecer            = '';

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];

      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        if ($oMatricula->getCodigo() == $oParam->iMatricula) {

          $oParecer = LancamentoAvaliacaoAluno::getParecer($oMatricula,
                                                           RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                           $oParam->iPeriodo);
          $oRetorno->sParecerPadronizado = urlencode($oParecer->sParecerPadronizado);
          $oRetorno->sParecer            = urlencode(str_replace('\n',"\n", $oParecer->sParecer));
        }
      }
      $_SESSION["oTurma"] = $oTurma;

      db_fim_transacao();

      break;

    case 'getParecerDisciplina':

      $oTurma = $_SESSION["oTurma"];
      $oEtapa = $_SESSION["oEtapa"];

      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {

        if ($oMatricula->getCodigo() == $oParam->iMatriculaAluno) {

          $oRegencia     = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
          $oRetornoDados = LancamentoAvaliacaoAluno::getParecerDisciplina(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia),
                                                                          $oParam->iPeriodoAvaliacao);
          $oRetorno->dados     = $oRetornoDados->aDados;
          $oRetorno->aLegendas = $oRetornoDados->aLegendas;
        }
      }
      $_SESSION["oTurma"] = $oTurma;

      break;

   case 'salvaAvaliacaoAluno':

     $oTurma = $_SESSION["oTurma"];
     $oEtapa = $_SESSION["oEtapa"];
     $_SESSION["DB_desativar_account"] = true;
     db_inicio_transacao();
     foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatricula) {
       LancamentoAvaliacaoAluno::salvaAvaliacaoAluno($oMatricula);
     }
     db_fim_transacao(false);
     $_SESSION["oTurma"] = $oTurma;
     unset($_SESSION["DB_desativar_account"]);
     $oRetorno->status   = 1;
     $oRetorno->message  = urlencode("Dados de avaliação salvos com sucesso.");
     break;

   case 'destroySession' :

     unset($_SESSION["oTurma"]);
     unset($_SESSION["oEtapa"]);
     break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

unset($_SESSION["DB_desativar_account"]);
echo $oJson->encode($oRetorno);