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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/FileException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$lProfessorLogado = false;
$iCodigoUsuario   = db_getsession("DB_id_usuario");
$oDocente         = DocenteRepository::getDocenteLogado( $iCodigoUsuario, $iEscola );

if ($oDocente != null) {

  if (!empty($oDocente) && count($oDocente->getTurmas()) > 0) {
    $lProfessorLogado = true;
  }
}

$oRetorno->lProfessorLogado = $lProfessorLogado;
try {

  switch($oParam->exec) {

    /**
     * Salvamos as aulas dadas de um periodo da regencia
     */
    case 'salvarAulasDadas':

      if (isset($oParam->iRegencia) && isset($oParam->iPeriodoAvaliacao) &&
         !empty($oParam->iRegencia) && !empty($oParam->iPeriodoAvaliacao)) {

        db_inicio_transacao();

        $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
        $oRegencia->adicionarAulasDadasNoPeriodo($oParam->iTotalAulas, PeriodoAvaliacaoRepository::getPeriodoAvaliacaoByCodigo($oParam->iPeriodoAvaliacao));

        db_fim_transacao();
        unset($oRegencia);
      }
      break;

    /**
     * Retorna os dados da turma para apresentacao na view de lancamento de turma
     */
    case 'getDadosTurma':

      if (isset($oParam->iTurma) && isset($oParam->iEtapa) && !empty($oParam->iTurma) && !empty($oParam->iEtapa)) {

        $oEtapa                 = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
        $oTurma                 = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
        $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

        $oRetorno->iTurma                       = $oParam->iTurma;
        $oRetorno->iEtapa                       = $oParam->iEtapa;
        $oRetorno->sEscola                      = urlencode($oTurma->getEscola()->getNome());
        $oRetorno->sCalendario                  = urlencode($oTurma->getCalendario()->getDescricao());
        $oRetorno->sCurso                       = urlencode($oTurma->getBaseCurricular()->getCurso()->getNome());
        $oRetorno->sBaseCurricular              = urlencode($oTurma->getBaseCurricular()->getDescricao());
        $oRetorno->sTurma                       = urlencode($oTurma->getDescricao());
        $oRetorno->sEtapa                       = urlencode($oEtapa->getNome());
        $oRetorno->sProcedimentoAvaliacao       = urlencode($oProcedimentoAvaliacao->getDescricao());
        $oRetorno->sTipoProcedimentoAvaliacao   = urlencode($oProcedimentoAvaliacao->getFormaAvaliacao()->getTipo());
        $oRetorno->sFormaObtencaoResultado      = '';
        $oRetorno->lUtilizaProporcionalidade    = false;
        $oRetorno->lUtilizaAvaliacaoAlternativa = false;
        $oRetorno->sTurno                       = urlencode($oTurma->getTurno()->getDescricao());
        $oRetorno->sFrequencia                  = urlencode("PERÍODOS");
        $oRetorno->lTurmaEncerrada              = $oTurma->encerradaNaEtapa($oEtapa);
        $oRetorno->lTurmaEncerradaParcial       = $oTurma->encerradaParcial($oEtapa);
        $oRetorno->lBloqueiaEncerramento        = $lProfessorLogado;


        // Verifica se a avaliação alternativa esta configurada para a escola
        $oDaoEduParametros   = new cl_edu_parametros;
        $sqlParametrosEscola = $oDaoEduParametros->sql_query(null, "ed233_c_avalalternativa", null, "ed233_i_escola = {$iEscola}");
        $rsParametrosEscola  = db_query($sqlParametrosEscola);

        if ( !$rsParametrosEscola ) {
          throw new DBException('Falha ao buscar os parâmetros da escola.');
        }

        $oDadosParametroEscola = db_utils::fieldsMemory($rsParametrosEscola, 0);
        $oRetorno->lEscolaUtilizaAvaliacaoAlternativa = $oDadosParametroEscola->ed233_c_avalalternativa == 'N' ? false : true;

        foreach( $oProcedimentoAvaliacao->getResultados() as $oResultadoAvaliacao ) {

          $oRetorno->sFormaObtencaoResultado   = $oResultadoAvaliacao->getFormaDeObtencao();

          // Verifica se ao menos um resultado utiliza proporcionalidade
          if ( !$oRetorno->lUtilizaProporcionalidade && $oResultadoAvaliacao->utilizaProporcionalidade() ) {
            $oRetorno->lUtilizaProporcionalidade = true;
          }

          /**
           * Só é valido para Resultados onde a forma de obtenção for SOMA
           */
          $aAvaliacaoAlternativa = $oResultadoAvaliacao->getAvaliacoesAlternativas();
          if (count($aAvaliacaoAlternativa) > 0) {
            $oRetorno->lUtilizaAvaliacaoAlternativa = true;
          }
        }

        if ($oTurma->getFormaCalculoCargaHoraria() == 2) {
          $oRetorno->sFrequencia = urlencode("DIAS LETIVOS");
        }

        $oRetorno->aDisciplinas = array();

        /**
         * Percorremos as disciplinas da turma, armazenando em um objeto os atributos da disciplina e dos periodos de
         * avaliacao desta
         */
        foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oDisciplina) {

          if ($lProfessorLogado && !$oDocente->lecionaRegencia($oDisciplina)) {
            continue;
          }

          $oDadosDisciplina                  = new stdClass();
          $oDadosDisciplina->iCodigo         = $oDisciplina->getCodigo();
          $oDadosDisciplina->sDescricao      = urlencode($oDisciplina->getDisciplina()->getNomeDisciplina());
          $oDadosDisciplina->sAbrev          = urlencode($oDisciplina->getDisciplina()->getAbreviatura());
          $oDadosDisciplina->lEncerrada      = urlencode($oDisciplina->isEncerrada());
          $oDadosDisciplina->lTratada        = $oDisciplina->getFrequenciaGlobal() == "A" ? true : false;
          $oDadosDisciplina->lObrigatoria    = $oDisciplina->isObrigatoria();
          $oRegencia                         = RegenciaRepository::getRegenciaByCodigo($oDisciplina->getCodigo());
          $oDadosDisciplina->sFormaAvaliacao = $oRegencia->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo();
          $oDadosDisciplina->aPeriodos       = array();

          foreach($oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa)->getElementos() as $oAvaliacao) {



            if ($oAvaliacao instanceof AvaliacaoPeriodica && $oAvaliacao->getPeriodoAvaliacao()->hasControlaFrequencia()) {

              $oPeriodo          = new stdClass();
              $oPeriodo->iCodigo = $oAvaliacao->getPeriodoAvaliacao()->getCodigo();

              $iTotalDeAulas = $oRegencia->getTotalDeAulasNoPeriodo($oAvaliacao->getPeriodoAvaliacao());
              if (empty($iTotalDeAulas)) {
                $iTotalDeAulas = '';
              }
              $iAulas           = $iTotalDeAulas;
              $oPeriodo->iAulas = $iAulas;

              $oDadosDisciplina->aPeriodos[] = $oPeriodo;
            }
          }
          $oRetorno->aDisciplinas[] = $oDadosDisciplina;
        }
      }
      break;

    /**
     * Retornamos os periodos de avaliacao da turma que controlam frequência
     */
    case 'getPeriodosAvaliacao':

      if (isset($oParam->iTurma) && isset($oParam->iEtapa) && !empty($oParam->iTurma) && !empty($oParam->iEtapa)) {

        $oRetorno->aPeriodos    = array();
        $oTurma                 = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
        $oEtapa                 = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
        $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
        foreach ($oProcedimentoAvaliacao->getElementos() as $oElemento) {

          if ($oElemento instanceof AvaliacaoPeriodica && $oElemento->getPeriodoAvaliacao()->hasControlaFrequencia()) {

            $oDadosPeriodo               = new stdClass();
            $oDadosPeriodo->iCodigo      = $oElemento->getPeriodoAvaliacao()->getCodigo();
            $oDadosPeriodo->sAbreviatura = urlencode($oElemento->getPeriodoAvaliacao()->getDescricaoAbreviada());
            $oRetorno->aPeriodos[]       = $oDadosPeriodo;
          }
        }
      }
      break;

    case 'validarEncerramentoDaTurma':

      $oTurma        = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
      $oEtapa        =  EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      $oEncerramento = new EncerramentoAvaliacao();

      $oRetorno->lAulasDadas = $oEncerramento->semAulasDadas($oTurma, $oEtapa);
      if ($oRetorno->lAulasDadas) {

        $sMensagem = "Faltam informar aulas dadas para a turma.\nEncerramento não poderá ser realizado.";
        throw new BusinessException($sMensagem);
      }

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

$oRetorno->erro = $oRetorno->status == 2;
echo $oJson->encode($oRetorno);
