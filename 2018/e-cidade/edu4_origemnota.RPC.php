<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
require_once(modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php"));
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
     * Retorna os alunos que tem nota lancada no periodo e disciplina informado
     * @param integer $oParam->iTurma: codigo da turma
     * @param integer $oParam->iEtapa: codigo da etapa
     * @param integer $oParam->iRegencia:  codigo da regencia
     * @param integer $oParam->iPeriodo: codigo do periodo selecionado
     * @return array $oRetorno->aAlunos: array com os dados do aluno para apresentacao e manipulacao da origem da nota
     */
    case 'getAlunoComNotaNoPeriodo':

      if ( isset($oParam->iTurma) && isset($oParam->iEtapa) ) {

        $oRetorno->aAlunos   = array();

        $oTurma = EducacaoSessionManager::carregarTurma($oParam->iTurma);
        $oEtapa = EducacaoSessionManager::carregarEtapa($oParam->iEtapa);

        $aAlunosMatriculados = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);

        db_inicio_transacao();
        foreach ( $aAlunosMatriculados as $oMatricula ) {

          $dtInicioPeriodo = '';
          $dtFinalPeriodo  = '';

          if ( $oMatricula->getSituacao() == "MATRICULADO" && $oMatricula->isAtiva() && !$oMatricula->isConcluida() ) {

            $oDiarioAvaliacao = $oMatricula->getDiarioDeClasse()
                                           ->getDisciplinasPorRegencia(RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia));

            foreach ( $oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

              if ( $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof AvaliacaoPeriodica &&
                   $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo() == $oParam->iAvaliacao
                 ) {

                foreach ( $oTurma->getCalendario()->getPeriodos() as $oPeriodoCalendario ) {

                  $iPeriodo = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getPeriodoAvaliacao()->getCodigo();
                  if ( $oPeriodoCalendario->getPeriodoAvaliacao()->getCodigo() == $iPeriodo ) {

                    $dtInicioPeriodo = $oPeriodoCalendario->getDataInicio()->getDate(DBDate::DATA_PTBR);
                    $dtFinalPeriodo  = $oPeriodoCalendario->getDataTermino()->getDate(DBDate::DATA_PTBR);
                    $oPeriodoFinal   = $oPeriodoCalendario->getDataTermino();
                  }
                }
                $oAvaliacao = $oDiarioAvaliacao->getAproveitamentosDoPeriodo($oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo());

                if ( $oAvaliacao->getValorAproveitamento()->getAproveitamento() != '' &&
                     !$oAvaliacaoAproveitamento->getAproveitamentoOrigem() != null
                   ) {

                  $oDadosAlunos                        = new stdClass();
                  $oDadosAlunos->iMatricula            = $oMatricula->getCodigo();
                  $oDadosAlunos->sNome                 = urlencode($oMatricula->getAluno()->getNome());
                  $oDadosAlunos->iDiarioAvaliacao      = $oAvaliacaoAproveitamento->getCodigo();
                  $oDadosAlunos->iEscolaTurma          = $oTurma->getEscola()->getCodigo();
                  $oDadosAlunos->dtInicioPeriodo       = $dtInicioPeriodo;
                  $oDadosAlunos->dtFinalPeriodo        = $dtFinalPeriodo;
                  $oDadosAlunos->lAnteriorFinalPeriodo = false;

                  $iIntervalo = DBDate::calculaIntervaloEntreDatas($oMatricula->getDataMatricula(), $oPeriodoFinal, 'd');

                  if ( $iIntervalo < 0 ) {
                    $oDadosAlunos->lAnteriorFinalPeriodo = true;
                  }

                  $oDadosAlunos->iEscolaDestino     = '';
                  $oDadosAlunos->sNomeEscolaDestino = '';
                  $oDadosAlunos->sTipoDestino       = '';
                  $oDadosAlunos->sMunicipioDestino  = '';
                  $oDadosAlunos->sUfDestino         = '';

                  if ( !$oAvaliacaoAproveitamento->isAvaliacaoExterna() ) {

                    $oDadosAlunos->iEscolaAtual        = $oTurma->getEscola()->getCodigo();
                    $oDadosAlunos->sNomeEscolaAtual    = urlencode(strtoupper($oTurma->getEscola()->getNome()));
                    $oDadosAlunos->sTipoAtual          = urlencode('ESCOLA DA REDE');
                    $oDadosAlunos->sTipoAbreviadoAtual = 'M';
                    $oDadosAlunos->sMunicipioAtual     = urlencode(strtoupper($oTurma->getEscola()->getMunicipio()));
                    $oDadosAlunos->sUfAtual            = urlencode(strtoupper($oTurma->getEscola()->getUf()));
                    $oDadosAlunos->lEscolaRede         = true;
                  } else {

                    $oDadosAlunos->iEscolaAtual        = $oAvaliacaoAproveitamento->getEscola()->getCodigo();
                    $oDadosAlunos->sNomeEscolaAtual    = urlencode(strtoupper($oAvaliacaoAproveitamento->getEscola()
                                                                                                       ->getNome()));
                    $oDadosAlunos->sTipoAtual          = urlencode('FORA DA REDE');
                    $oDadosAlunos->sTipoAbreviadoAtual = 'F';

                    if ( $oAvaliacaoAproveitamento->getTipo() == 'M' ) {

                      $oDadosAlunos->sTipoAtual          = urlencode('ESCOLA DA REDE');
                      $oDadosAlunos->sTipoAbreviadoAtual = 'M';
                    }

                    $oDadosAlunos->sMunicipioAtual     = urlencode(strtoupper($oAvaliacaoAproveitamento->getEscola()
                                                                                                       ->getMunicipio()));
                    $oDadosAlunos->sUfAtual            = urlencode(strtoupper($oAvaliacaoAproveitamento->getEscola()
                                                                                                       ->getUf()));
                    $oDadosAlunos->lEscolaRede         = false;

                    $oDadosAlunos->iEscolaDestino        = $oTurma->getEscola()->getCodigo();
                    $oDadosAlunos->sNomeEscolaDestino    = urlencode(strtoupper($oTurma->getEscola()->getNome()));
                    $oDadosAlunos->sTipoDestino          = urlencode('ESCOLA DA REDE');
                    $oDadosAlunos->sTipoAbreviadoDestino = 'M';
                    $oDadosAlunos->sMunicipioDestino     = urlencode(strtoupper($oTurma->getEscola()->getMunicipio()));
                    $oDadosAlunos->sUfDestino            = urlencode(strtoupper($oTurma->getEscola()->getUf()));
                  }

                  $oRetorno->aAlunos[] = $oDadosAlunos;
                }
              }
            }
          }
        }

        db_fim_transacao();
      }
      break;

    /**
     * Retorno o Municipio e Estado de uma escola de fora
     * @param integer $oParam->iCodigo: codigo da escola de fora da rede
     * @return $oRetorno: nome do Municipio e sigla do Estado onde se encontra a escola
     */
    case 'buscaDadosEscolaFora':

      if ( isset($oParam->iCodigo) ) {

        $oEscolaProc          = new EscolaProcedencia($oParam->iCodigo);
        $oRetorno->sMunicipio = urlencode(strtoupper($oEscolaProc->getMunicipio()));
        $oRetorno->sUf        = urlencode(strtoupper($oEscolaProc->getUf()));
        unset($oEscolaProc);
      }
      break;

    /**
     * Persiste as alteracoes referentes a origem de uma nota, de uma disciplina e periodo especificado, com base no
     * codigo do diarioavaliacao
     * @param integer $oParam->iMatricula: matricula do aluno selecionado
     * @param integer $oParam->iDiarioAvaliacao: codigo do diarioavaliacao do aluno
     * @param integer $oParam->iRegencia: codigo da regencia
     * @param integer $oParam->iPeriodo: codigo do periodo selecionado
     * @param integer $oParam->iEscola: codigo da escola que deve ser setada para o DiarioAvaliacao da disciplina
     * @param string  $oParam->sTipo: 'F' ou 'M', informando se eh uma escola de dentro ou de fora da rede
     */
    case 'salvarOrigemNota':


      if ( isset($oParam->iMatricula) && isset($oParam->iDiarioAvaliacao) ) {

        db_inicio_transacao();

        $oMatricula            = EducacaoSessionManager::carregarMatricula($oParam->iMatricula);
        $iCodigoTurmaMatricula = $oMatricula->getTurma()->getCodigo();

        $oRegencia        = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
        $oDiarioAvaliacao = $oMatricula->getDiarioDeClasse()->getDisciplinasPorRegencia($oRegencia);

        foreach ( $oDiarioAvaliacao->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

          if ( $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof AvaliacaoPeriodica &&
               $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo() == $oParam->iAvaliacao
             ) {

            if ( $oParam->sTipo == 'M' ) {

              $oEscola = EscolaRepository::getEscolaByCodigo($oParam->iEscola);
              $oAvaliacaoAproveitamento->setAvaliacaoExterna(false);
            } else {

              $oEscola = EscolaProcedenciaRepository::getEscolaByCodigo($oParam->iEscola);
              $oAvaliacaoAproveitamento->setAvaliacaoExterna(true);
            }

            $oAvaliacaoAproveitamento->setNumeroFaltas($oAvaliacaoAproveitamento->getNumeroFaltas());
            $oAvaliacaoAproveitamento->setTipo($oParam->sTipo);
            $oAvaliacaoAproveitamento->setEscola($oEscola);
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