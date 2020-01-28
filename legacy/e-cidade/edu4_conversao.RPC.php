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

try {

  switch($oParam->exec) {

    /**
     * Só retorna alunos que possuam avaliação externa, com Forma de Avaliacao diferente da escola atual
     */
    case 'getAlunoComNotaExterna':

      $oTurma    = EducacaoSessionManager::carregarTurma($oParam->iTurma);
      $oEtapa    = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);
      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

      $oProcedimentoAvaliacao = $oRegencia->getProcedimentoAvaliacao();
      $oPeriodoAvaliacao      = null;

      foreach ($oProcedimentoAvaliacao->getElementos() as $oAvaliacao) {

        if ($oAvaliacao instanceof ResultadoAvaliacao) {
          continue;
        }
        if ($oAvaliacao->getOrdemSequencia() == $oParam->iPeriodo) {

          $oPeriodoAvaliacao = $oAvaliacao;
          break;
        }
      }

      $aMatriculas = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
      $iAno        = $oTurma->getCalendario()->getAnoExecucao();
      $aAlunos     = array();

      foreach ($aMatriculas as $oMatricula) {

        if ( $oMatricula->getSituacao() != "MATRICULADO" || !$oMatricula->isAtiva() || $oMatricula->isConcluida() ) {
          continue;
        }

        $oDadosAluno = new stdClass();

        db_inicio_transacao();
        $oAvaliacao = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegenciaPeriodo($oRegencia, $oPeriodoAvaliacao);

        if (($oAvaliacao->getElementoAvaliacao()->getOrdemSequencia() != $oParam->iPeriodo) ||
            !$oAvaliacao->isAvaliacaoExterna()) {
          continue;
        }

        $oAvaliacao->getAproveitamentoOrigem();
        /**
         * Busca a avaliação de origem do aluno e valida se as formas de avaliação são iguais, e verifica se o diario
         * esta amparado
         */
        if ($oAvaliacao->getAproveitamentoOrigem() == null || $oAvaliacao->isAmparado()) {
          continue;
        }

        if ($oProcedimentoAvaliacao->getFormaAvaliacao()->getCodigo() ==
            $oAvaliacao->getAproveitamentoOrigem()->getElementoAvaliacao()->getFormaDeAvaliacao()->getCodigo()) {
          continue;
        }

        $mNota = $oAvaliacao->getValorAproveitamento()->getAproveitamento();

        /**
         * Quando as formas de avaliações são diferentes, devemos retornar os dados de cada uma das formas de avaliação
         */
        $oDadosAluno->sNomeAluno            = urlencode($oMatricula->getAluno()->getNome());
        $oDadosAluno->iCodigoAluno          = $oMatricula->getAluno()->getCodigoAluno();
        $oDadosAluno->iMatricula            = $oMatricula->getCodigo();
        $oDadosAluno->iDiarioAvaliacao      = $oAvaliacao->getCodigo();
        $oDadosAluno->sFormaAvaliacao       = urlencode($oProcedimentoAvaliacao->getFormaAvaliacao()->getTipo());
        $oDadosAluno->iFormaAvaliacao       = $oProcedimentoAvaliacao->getFormaAvaliacao()->getCodigo();
        $oDadosAluno->lConvertido           = $oAvaliacao->isConvertido();
        $oDadosAluno->nMenorValor           = "";
        $oDadosAluno->nMaiorValor           = "";
        $oDadosAluno->aConceito             = array();
        $oDadosAluno->mAproveitamentoMinino = $oProcedimentoAvaliacao->getFormaAvaliacao()->getAproveitamentoMinino();

        if($oProcedimentoAvaliacao->getFormaAvaliacao()->getTipo() == 'NOTA') {

          $oDadosAluno->nMenorValor = $oProcedimentoAvaliacao->getFormaAvaliacao()->getMenorValor();
          $oDadosAluno->nMaiorValor = $oProcedimentoAvaliacao->getFormaAvaliacao()->getMaiorValor();
          $mNota                    = ArredondamentoNota::formatar($mNota, $iAno);
        }
        if($oProcedimentoAvaliacao->getFormaAvaliacao()->getTipo() == 'NIVEL') {
          $oDadosAluno->aConceito = $oProcedimentoAvaliacao->getFormaAvaliacao()->getConceitos();
        }

        $oDadosAluno->sTipoEscola = $oAvaliacao->getTipo() == "M" ? 'ESCOLA DA REDE' : 'FORA DA REDE';

        $oDadosAluno->mNota       = urlencode("{$mNota}");


        /**
         * Dados da escola de origem
         */
        $mNotaOrigem       = $oAvaliacao->getAproveitamentoOrigem()->getValorAproveitamento()->getAproveitamento();
        $sTipoEscolaOrigem = $oAvaliacao->getAproveitamentoOrigem()->getTipo() == "M" ? 'ESCOLA DA REDE'
                                                                                          : 'FORA DA REDE';

        $oAvaliacaoOrigem                  = new stdClass();
        $oAvaliacaoOrigem->sTipoEscola     = $sTipoEscolaOrigem;
        $oAvaliacaoOrigem->sFormaAvaliacao = urlencode($oAvaliacao->getAproveitamentoOrigem()->getElementoAvaliacao()
                                                                              ->getFormaDeAvaliacao()->getTipo());
        $oAvaliacaoOrigem->iFormaAvaliacao = $oAvaliacao->getAproveitamentoOrigem()->getElementoAvaliacao()
                                                                                   ->getFormaDeAvaliacao()->getCodigo();
        $oAvaliacaoOrigem->nMenorValor     = "";
        $oAvaliacaoOrigem->nMaiorValor     = "";

        $oAvaliacaoOrigem->aConceito       = array();

        if($oAvaliacao->getAproveitamentoOrigem()->getElementoAvaliacao()
                                                     ->getFormaDeAvaliacao()->getTipo() == 'NOTA') {

          $oAvaliacaoOrigem->nMenorValor = $oAvaliacao->getAproveitamentoOrigem()->
                                                          getElementoAvaliacao()->getFormaDeAvaliacao()->getMenorValor();
          $oAvaliacaoOrigem->nMaiorValor = $oAvaliacao->getAproveitamentoOrigem()->
                                                          getElementoAvaliacao()->getFormaDeAvaliacao()->getMaiorValor();

          $mNotaOrigem = ArredondamentoNota::formatar($mNotaOrigem, $iAno);
        }

        if($oAvaliacao->getAproveitamentoOrigem()->getElementoAvaliacao()
                                                        ->getFormaDeAvaliacao()->getTipo() == 'NIVEL') {

          $oAvaliacaoOrigem->aConceito = $oAvaliacao->getAproveitamentoOrigem()->
                                                   getElementoAvaliacao()->getFormaDeAvaliacao()->getConceitos();
        }
        $oAvaliacaoOrigem->mNotaOrigem = $mNotaOrigem;

        $oAvaliacaoOrigem->iEscola    = $oAvaliacao->getAproveitamentoOrigem()->getEscola()->getCodigo();
        $oAvaliacaoOrigem->sEscola    = urlencode($oAvaliacao->getAproveitamentoOrigem()->getEscola()->getNome());
        $oAvaliacaoOrigem->sMunicipio = urlencode($oAvaliacao->getAproveitamentoOrigem()->getEscola()->getMunicipio());
        $oAvaliacaoOrigem->sEstado    = urlencode($oAvaliacao->getAproveitamentoOrigem()->getEscola()->getUf());

        $oDadosAluno->oAvaliacaoOrigem = $oAvaliacaoOrigem;
        $aAlunos[]                     = $oDadosAluno;

        db_fim_transacao();

      }

      $oRetorno->aAlunos = $aAlunos;

      break;

    case 'salvarConversao':

      if ( isset($oParam->iMatricula) && isset($oParam->iDiarioAvaliacao) ) {

        db_inicio_transacao();

        $oMatricula = EducacaoSessionManager::carregarMatricula($oParam->iMatricula);
        $oRegencia  = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);

        $oDiarioAvaliacao = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);

        foreach ( $oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

          if ( $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof AvaliacaoPeriodica &&
              $oAvaliacaoAproveitamento->getElementoAvaliacao()->getOrdemSequencia() == $oParam->iPeriodo
             ) {

            $oValorAproveitamento = $oAvaliacaoAproveitamento->getValorAproveitamento();
            $oValorAproveitamento->setAproveitamento($oParam->sAproveitamento);
            if ( $oValorAproveitamento instanceof ValorAproveitamentoNivel ) {
              $oValorAproveitamento->setOrdem($oParam->iOrdem);
            }

            $oAvaliacaoAproveitamento->setNumeroFaltas($oAvaliacaoAproveitamento->getNumeroFaltas());
            $oAvaliacaoAproveitamento->setValorAproveitamento($oValorAproveitamento);
            $oAvaliacaoAproveitamento->setConvertido(false);
            $oDiarioAvaliacao->salvar();
          }
        }
        db_fim_transacao();
      }
      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);