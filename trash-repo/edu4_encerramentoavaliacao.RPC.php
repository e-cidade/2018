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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_conecta.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

$iEscola           = db_getsession("DB_coddepto");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

switch($oParam->exec) {

  /**
   * Retorna um array com os alunos matriculados em uma turma, com base no codigo desta e da etapa
   */
  case 'getAlunosMatriculados':

    $oTurma                   = new Turma($oParam->iCodigoTurma);
    $oEtapa                   = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
    $aAlunosMatriculadosTurma = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
    $iCodigoEnsino            = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
    $oRetorno->aDadosAlunos   = array();

    $aTermosAprovado          = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'A');
    if (count($aTermosAprovado) > 0) {
      $sLabelAprovado = $aTermosAprovado[0]->sDescricao;
    }
    $lPermiteAprovacaoParcial = EncerramentoAvaliacao::permiteAprovacaoParcial($oTurma, $oEtapa);
    /**
     * Percorremos as matriculas retornadas, para preencher com as informacoes de cada aluno
     */
    foreach ($aAlunosMatriculadosTurma as $oMatricula) {
      
      if ( isset($oParam->lTrocaTurma) && !$oParam->lTrocaTurma && 
           ($oMatricula->getSituacao() == 'TROCA DE TURMA') ) {
      	continue;
      }

      $oDadosAluno                = new stdClass();
      $oDadosAluno->iCodigoAluno  = $oMatricula->getAluno()->getCodigoAluno();
      $oDadosAluno->iMatricula    = $oMatricula->getCodigo();
      $oDadosAluno->sNomeAluno    = urlencode($oMatricula->getAluno()->getNome());
      $oDadosAluno->iOrdemAluno   = $oMatricula->getNumeroOrdemAluno();
      $oDadosAluno->sSituacao     = urlencode($oMatricula->getSituacao());
      $oDadosAluno->lConcluido    = $oMatricula->isConcluida();
      $oDadosAluno->lEncerrado    = false;  // Adicionado para compor as variaveis padrao de retorno
      $oDadosAluno->sDisciplina   = "";
      $oDadosAluno->sEncerrado    = "";
      $oDadosAluno->aPendencias   = array();
      $oDadosAluno->lAptoEncerrar = false;

      /**
       * Buscamos o resultado final do diario da matricula, e apresentamos o termo correto de acordo com o cadastrado
       * para o ensino, caso exista um resultado final
       */
      db_inicio_transacao();

      /**
       * Caso a matricula esteja com situacao diferente de matriculado (Ex.: Transferido Rede), atribuimos o valor da
       * situacao para ser apresentado no resultado final, para que nao fique em branco
       */
      if ($oDadosAluno->sSituacao != 'MATRICULADO') {

        $oDadosAluno->lAptoEncerrar   = true;
        $oDadosAluno->sResultadoFinal = $oDadosAluno->sSituacao;
      } else {

        $oDadosAluno->sResultadoFinal = $oMatricula->getDiarioDeClasse()->getResultadoFinal();
        

        /**
         * Buscamos o termo correto para o resultado final
         */
        if (!empty($oDadosAluno->sResultadoFinal)) {

          if ($lPermiteAprovacaoParcial && $oDadosAluno->sResultadoFinal == 'A' &&
              EncerramentoAvaliacao::validaDiarioAlunoEja($oMatricula, $oEtapa) == "P") {
            $oDadosAluno->sResultadoFinal = 'P';
          }
          $aTermosEncerramento = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino,
                                                                       $oDadosAluno->sResultadoFinal,
                                                                       $oMatricula->getTurma()->getCalendario()
                                                                                              ->getAnoExecucao()
                                                                      );

          if (count($aTermosEncerramento) > 0) {
            $oDadosAluno->sResultadoFinal = urlencode($aTermosEncerramento[0]->sDescricao);
          }

          if ($oMatricula->getDiarioDeClasse()->aprovadoComProgressaoParcial()) {
            $oDadosAluno->sResultadoFinal = urlencode(" {$sLabelAprovado} (Progressão Parcial / Dependência)");
          }
          
          if ($oMatricula->getDiarioDeClasse()->temRecuperacao() ) {
            $oDadosAluno->sResultadoFinal = urlencode("EM RECUPERAÇÃO");
          } 
        }
        /**
         * Verificamos se o aluno esta apto para encerramento
         */
        $oDadosAluno->lAptoEncerrar = $oMatricula->getDiarioDeClasse()->aptoParaEncerramento();
        $oDadosAluno->aPendencias   = $oMatricula->getDiarioDeClasse()->getPendenciasEncerramento();

        //corrige a codificação das pendências
        $aPendenciasTratadas = array();
        foreach($oDadosAluno->aPendencias as $sPendencia){
          $aPendenciasTratadas[] = urlencode($sPendencia);
        }
        $oDadosAluno->aPendencias = $aPendenciasTratadas;
      }

      $oDadosAluno->sResultadoFinal = $oDadosAluno->sResultadoFinal;
      db_fim_transacao(false);
      $oRetorno->aDadosAlunos[] = $oDadosAluno;
    }
    //var_dump($oRetorno->aDadosAlunos);die;

    break;

  /**
   * Retorna um array de alunos de progressao e suas informacoes
   */
  case 'getAlunosDeProgressao':

    $oTurma                 = new Turma($oParam->iCodigoTurma);
    $iCodigoEnsino          = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
    $aAlunosDeProgressao    = $oTurma->getAlunosProgressaoParcial(EtapaRepository::getEtapaByCodigo($oParam->iEtapa));
    $oRetorno->aDadosAlunos = array();
    $iOrdemAluno            = 1;

    /**
     * Percorremos cada aluno de progressao
     */
    foreach ($aAlunosDeProgressao as $oAlunoProgressao) {

      $oVinculoRegencia = $oAlunoProgressao->getVinculoRegenciaNaTurma($oTurma);
      if (empty($oVinculoRegencia)) {
        continue;
      }
      $sEncerrado = $oVinculoRegencia->isEncerrado() ? "Sim" : "Não" ;

      $oDadosAlunoProgressao                  = new stdClass();
      $oDadosAlunoProgressao->iCodigoAluno    = $oAlunoProgressao->getAluno()->getCodigoAluno();
      $oDadosAlunoProgressao->sNomeAluno      = urlencode($oAlunoProgressao->getAluno()->getNome());
      $oDadosAlunoProgressao->iOrdemAluno     = $iOrdemAluno;
      $oDadosAlunoProgressao->sDisciplina     = urlencode($oAlunoProgressao->getDisciplina()->getAbreviatura());
      $oDadosAlunoProgressao->sEncerrado      = urlencode($sEncerrado);
      $oDadosAlunoProgressao->lEncerrado      = $oVinculoRegencia->isEncerrado();
      $oDadosAlunoProgressao->sResultadoFinal = urlencode($oAlunoProgressao->getResultadoFinal()->getResultado());
      $oDadosAlunoProgressao->iProgressao     = $oAlunoProgressao->getCodigoProgressaoParcial();

      /**
       * Buscamos o termo correto para o resultado final
       */
      if (!empty($oDadosAlunoProgressao->sResultadoFinal)) {

        $aTermosEncerramento = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, $oDadosAlunoProgressao->sResultadoFinal);

        if (count($aTermosEncerramento) > 0) {
          $oDadosAlunoProgressao->sResultadoFinal = urlencode($aTermosEncerramento[0]->sDescricao);
        }
      }

      if (empty($oDadosAlunoProgressao->sResultadoFinal)) {
        $oDadosAlunoProgressao->sResultadoFinal = urlencode("EM ANDAMENTO");
      }

      $iOrdemAluno++;
      $oRetorno->aDadosAlunos[] = $oDadosAlunoProgressao;
    }

    break;

  /**
   * Retorna os dados referentes a uma matricula
   */
  case 'buscaDadosMatricula':

    $oMatricula = new Matricula($oParam->iMatricula);

    /**
     * Buscamos os dados da Matricula do aluno
     */
    $oRetorno->sNomeAluno         = urlencode($oMatricula->getAluno()->getNome());
    $oRetorno->iCodigoAluno       = $oMatricula->getAluno()->getCodigoAluno();
    $oRetorno->sSituacaoAluno     = urlencode($oMatricula->getSituacao());
    $oRetorno->iCodigoTurma       = $oMatricula->getTurma()->getCodigo();
    $oRetorno->dtMatricula        = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
    $oRetorno->dtSaida            = str_repeat("&nbsp;", 10);
    if ($oMatricula->getDataEncerramento() != "") {
      $oRetorno->dtSaida  = $oMatricula->getDataEncerramento()->convertTo(DBDate::DATA_PTBR);
    }
    $oRetorno->sCalendario        = urlencode($oMatricula->getTurma()->getCalendario()->getDescricao());
    $oRetorno->sTurma             = urlencode($oMatricula->getTurma()->getDescricao());

    $oGradeAproveitamento         = new GradeAproveitamentoAluno($oMatricula);
    $oRetorno->mMinimoAprovacao   = urlencode($oGradeAproveitamento->getMinimoParaAprovacao());
    break;

  /**
   * Encerra as avaliacoes de Progressao Parcial
   */
  case 'encerrarProgressaoParcial':

    $aAlunosNaoProcessados = array();
    $oRetorno->lImprimeRel = true;

    if (count($oParam->aAlunos) > 0) {

      $oEncerramento    = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      foreach ($oParam->aAlunos as $oAluno) {

        db_inicio_transacao();

        $oAlunoProgressao = new ProgressaoParcialAluno($oAluno->iProgressao);
        if ($oEncerramento->encerrarProgressaoParcial($oAlunoProgressao)) {

          $oRetorno->status  = 1;
          $oRetorno->message = "Encerramento processado com sucesso!";
        } else {

          $oAlunoNaoProcessado              = new stdClass();
          $oAlunoNaoProcessado->iProgressao = $oAlunoProgressao->getCodigoProgressaoParcial();
          $oAlunoNaoProcessado->sNome       = $oAlunoProgressao->getAluno()->getNome();
          $aAlunosNaoProcessados[]          = $oAlunoNaoProcessado;
          db_fim_transacao(true);
        }

        db_fim_transacao();
      }
    }

    if (count($aAlunosNaoProcessados) > 0) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = "Não foi possível processar o encerramento das avaliações.";
    }

    $oRetorno->message = urlencode($oRetorno->message);
    break;

  /**
   * Cancela o encerramento de uma ou mais Progressoes Parciais
   */
  case 'cancelarEncerramentoProgressaoParcial':

    $aAlunosNaoProcessados = array();
    $oRetorno->lImprimeRel = true;

    if (count($oParam->aAlunos) > 0) {

      $oEncerramento    = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      foreach ($oParam->aAlunos as $oAluno) {

        db_inicio_transacao();

        $oAlunoProgressao = new ProgressaoParcialAluno($oAluno->iProgressao);
        $oAlunoProgressao->setSituacaoProgressao(SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(ProgressaoParcialAluno::ATIVA));

        if ($oEncerramento->cancelarEncerramentoProgressaoParcial($oAlunoProgressao)) {

          $oRetorno->status  = 1;
          $oRetorno->message = "Cancelamento do Encerramento processado com sucesso!";
        } else {


          $oAlunoNaoProcessado              = new stdClass();
          $oAlunoNaoProcessado->iProgressao = $oAlunoProgressao->getCodigoProgressaoParcial();
          $oAlunoNaoProcessado->sNome       = $oAlunoProgressao->getAluno()->getNome();
          $aAlunosNaoProcessados[]          = $oAlunoNaoProcessado;
          db_fim_transacao(true);
        }
        db_fim_transacao();
      }
    }

    if (count($aAlunosNaoProcessados) > 0) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = "Não foi possível processar o cancelamento do encerramento das avaliações.";
    }

    $oRetorno->message = urlencode($oRetorno->message);
    break;

  case 'encerrarAvaliacoes':

    try {

      $oEncerramento                = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      $oRetorno->lImprimeRel        = false;
      $oRetorno->aTurmasProcessadas = array();
      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        $oTurma = new Turma($oTurmaEtapa->iTurma);
        $oEtapa = EtapaRepository::getEtapaByCodigo($oTurmaEtapa->iEtapa);
        db_inicio_transacao();

        if ($oEncerramento->encerrarAvaliacaoTurma($oTurma, $oEtapa)) {

          $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;
          $oRetorno->status               = 1;
          $oRetorno->message              = "Encerramento processado com sucesso!";

        }
        db_fim_transacao(false);
      }

      if (count($oRetorno->aTurmasProcessadas) != count($oParam->aTurmas)) {

        $oRetorno->lImprimeRel = true;
        $oRetorno->status      = 2;
        $oRetorno->message     = "Não foi possível encerrar as avaliações.";
      }
    } catch (ParameterException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    } catch (BusinessException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    } catch (DBException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    }

    $oRetorno->message = urlencode($oRetorno->message);

    break;

  case 'cancelarEncerramento':

    try {

      $oEncerramento                = new EncerramentoAvaliacao(new DBLogJSON("tmp/encerramento.json"));
      $oRetorno->lImprimeRel        = false;
      $oRetorno->aTurmasProcessadas = array();
      foreach ($oParam->aTurmas as $oTurmaEtapa) {

        $oTurma = new Turma($oTurmaEtapa->iTurma);
        $oEtapa = EtapaRepository::getEtapaByCodigo($oTurmaEtapa->iEtapa);
        db_inicio_transacao();

        if ($oEncerramento->cancelarEncerramentoTurma($oTurma, $oEtapa)) {

          $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;
          $oRetorno->status               = 1;
          $oRetorno->message              = "Cancelamento do encerramento processado com sucesso!";
        } else {

          $oRetorno->aTurmasProcessadas[] = $oTurmaEtapa;
          $oRetorno->lImprimeRel          = true;
          $oRetorno->status               = 2;
          $oRetorno->message              = "Não foi possível cancelar o encerramento das avaliações.";
        }

        db_fim_transacao(false);
      }
    } catch (ParameterException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    } catch (BusinessException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    } catch (DBException $oErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = $oErro->getMessage();
    }

    $oRetorno->message = urlencode($oRetorno->message);

    break;

  case 'verificaTurmaSemAulasDadas':

    try {

      $oTurma        = new Turma($oParam->iCodigoTurma);
      $oEtapa        = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      $oEncerramento = new EncerramentoAvaliacao();

      if ($oEncerramento->semAulasDadas($oTurma, $oEtapa, false)) {

        $oRetorno->lTurmaEncerrada = true;
        $oRetorno->status          = 1;
        $oRetorno->message         = 'Turma Encerrada.';
      } else {

        $oRetorno->lTurmaEncerrada = false;
        $oRetorno->status          = 1;
        $oRetorno->message         = 'Turma Não Encerrada.';
      }

    } catch (Exception $oErro) {

      $oRetorno->lTurmaEncerrada = false;
      $oRetorno->status          = 2;
      $oRetorno->message         = $oErro->getMessage();
    }

    break;
}

echo $oJson->encode($oRetorno);
?>