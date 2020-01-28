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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBNumber.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once('model/educacao/avaliacao/iFormaObtencao.interface.php');
require_once('model/educacao/avaliacao/iElementoAvaliacao.interface.php');

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$iDepartamentoLogado = db_getsession("DB_coddepto");
$iModulo             = db_getsession("DB_modulo");

$sCaminhoMensagens = "educacao.escola.edu4_alunoprogressao.";

try {

  switch ($oParam->exec) {

    case 'getAlunosVinculados':

      $oTurma            = TurmaRepository::getTurmaByCodigo($oParam->iCodigoTurma);
      $aAlunosVinculados = array();
      foreach ($oTurma->getAlunosProgressaoParcial() as $oProgressaoAluno) {

        $oDadosAluno                           = new stdClass();
        $oDadosAluno->iCodigoProgressaoParcial = $oProgressaoAluno->getCodigoProgressaoParcial();
        $oDadosAluno->iCodigoAluno             = $oProgressaoAluno->getAluno()->getCodigoAluno();
        $oDadosAluno->sNomeAluno               = urlencode($oProgressaoAluno->getAluno()->getNome());
        $oDadosAluno->sDisciplina              = urlencode($oProgressaoAluno->getDisciplina()->getNomeDisciplina());
        $oDadosAluno->dtVinculo                = $oProgressaoAluno->getVinculoRegencia()
                                                                  ->getDataVinculo()
                                                                  ->convertTo(DBDate::DATA_EN);
        $oDadosAluno->temResultadoFinal        = false;
        $oDadosAluno->encerrado                = $oProgressaoAluno->getVinculoRegencia()->isEncerrado();
        if ($oProgressaoAluno->getResultadoFinal() != null) {
          if ($oProgressaoAluno->getResultadoFinal()->getResultado() != "") {
            $oDadosAluno->temResultadoFinal = true;
          }
        }
        $aAlunosVinculados[] = $oDadosAluno;
      }

      $oRetorno->dados = $aAlunosVinculados;

      break;

    case 'getDadosTurma':

      $oTurma  = TurmaRepository::getTurmaByCodigo($oParam->iCodigoTurma);
      $aEtapas = array();

      foreach ($oTurma->getEtapas() as $oEtapaTurma) {

        if ($oEtapaTurma->getEtapa()->getCodigo() != $oParam->iEtapa) {
          continue;
        }
        $oEtapa         = new stdClass();
        $oEtapa->iEtapa = $oEtapaTurma->getEtapa()->getCodigo();
        $oEtapa->sEtapa = urlencode($oEtapaTurma->getEtapa()->getNome());
        $aEtapas[]      = $oEtapa;
      }

      $oDadosTurma                    = new stdClass();
      $oDadosTurma->iCodigoTurma      = $oTurma->getCodigo();
      $oDadosTurma->sNomeTurma        = urlencode($oTurma->getDescricao());
      $oDadosTurma->sCalendario       = urlencode($oTurma->getCalendario()->getDescricao());
      $oDadosTurma->sCurso            = urlencode($oTurma->getBaseCurricular()->getCurso()->getNome());
      $oDadosTurma->aEtapas           = $aEtapas;
      $oDadosTurma->sTurno            = urlencode($oTurma->getTurno()->getDescricao());

      if ( !$oTurma->getTurno()->isIntegral() ) {

        $oDadosTurma->iVagas            = current( $oTurma->getVagas() );
        $oDadosTurma->iVagasOcupadas    = current( $oTurma->getVagasOcupadas() );
        $oDadosTurma->iVagasDisponiveis = current( $oTurma->getVagasDisponiveis() );
      }

      $oRetorno->oDadosTurma = $oDadosTurma;

      break;

    case 'getDisciplinaTurma':

      $oTurma       = TurmaRepository::getTurmaByCodigo($oParam->iCodigoTurma);
      $oEtapa       = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);
      $aDisciplinas = array();

      foreach ($oTurma->getDisciplinasPorEtapa($oEtapa) as $oRegencia) {

        if ( !$oRegencia->isObrigatoria() ) {
          continue;
        }

        $oDisciplina                       = new stdClass();
        $oDisciplina->iRegencia            = $oRegencia->getCodigo();
        $oDisciplina->iCodigoDisciplina    = $oRegencia->getDisciplina()->getCodigoDisciplina();
        $oDisciplina->sDescricaoDisciplina = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());

        $aDisciplinas[] = $oDisciplina;
      }

      $oRetorno->aDisciplinas = $aDisciplinas;

      break;
    case 'getAlunosParaVincular':

      $oRegencia    = new Regencia($oParam->iRegencia);
      $oEtapa       = new Etapa($oParam->iEtapa);
      $oEscola      = new Escola($iDepartamentoLogado);

      $lSomenteAlunosEscola = false;
      if ( $oParam->iAlunosEscola == 1 ) {
        $lSomenteAlunosEscola = true;
      }
      $aProgressoes = ProgressaoParcialAlunoRepository::getAlunosComProgressaoParcialSemVinculoComTurma($oRegencia,
                                                                                                        $oEtapa,
                                                                                                        $oEscola,
                                                                                                        $lSomenteAlunosEscola
                                                                                                       );

      $aAlunosSemVinculo = array();

      foreach ($aProgressoes as $oProgressaoAluno) {

        $oAlunosSemVinculo                    = new stdClass();
        $oAlunosSemVinculo->iCodigoAluno      = $oProgressaoAluno->getAluno()->getCodigoAluno();
        $oAlunosSemVinculo->iCodigoProgressao = $oProgressaoAluno->getCodigoProgressaoParcial();
        $oAlunosSemVinculo->sDisciplina       = urlencode($oProgressaoAluno->getDisciplina()->getNomeDisciplina());
        $oAlunosSemVinculo->sNomeAluno        = urlencode($oProgressaoAluno->getAluno()->getNome());
        $aAlunosSemVinculo[]                  = $oAlunosSemVinculo;
      }

      $oRetorno->aAlunos = $aAlunosSemVinculo;

      break;

    case 'vincular':

      db_inicio_transacao();

      foreach ($oParam->aVincular as $oVincular) {

        $oRegencia               = new Regencia($oVincular->iRegencia);
        $oProgressaoAlunoVinculo = new ProgressaoParcialAluno($oVincular->iCodigoProgressaoParcial);
        $oProgressaoAlunoVinculo->vincularComRegencia($oRegencia, new DBDate($oVincular->dtVinculo));
        $oProgressaoAlunoVinculo->salvar();
      }
      db_fim_transacao();

      break;
    case 'removerVinculo':

      db_inicio_transacao();
      $oProgressaoAlunoVinculo = new ProgressaoParcialAluno($oParam->iCodigoProgressaoParcial);
      $oProgressaoAlunoVinculo->removerVinculo();
      db_fim_transacao();
      break;

    /**
     * Salva uma progressão para um aluno
     *
     * @param integer $oParam->iAluno       - Código do aluno que ficou com progressão
     * @param array   $oParam->aProgressoes - Coleção com as progressões a serem vinculadas ao aluno
     *        ....... stdClass
     *        ................ integer iAno        - Ano em que o aluno teria ficado em progressão
     *        ................ integer iDisciplina - Código da disciplina a ser vinculada como progressão
     *        ................ integer iEtapa      - Código da etapa onde o aluno realizará a progressão
     */
    case 'salvarProgressaoAluno':

      if( !isset( $oParam->iAluno ) || ( isset( $oParam->iAluno ) && empty( $oParam->iAluno ) ) ) {
        throw new ParameterException( _M( $sCaminhoMensagens."aluno_nao_informado" ) );
      }

      if( !isset( $oParam->aProgressoes ) || ( isset( $oParam->aProgressoes ) && count( $oParam->aProgressoes ) == 0 ) ) {
        throw new ParameterException( _M( $sCaminhoMensagens."progressoes_nao_informadas" ) );
      }

      /**
       * Armazena a instância do aluno que ficou em progressão
       */
      $oAluno = AlunoRepository::getAlunoByCodigo( $oParam->iAluno );

      db_inicio_transacao();

      /**
       * Percorre as progressões a serem vinculadas ao aluno
       */
      foreach( $oParam->aProgressoes as $oProgressao ) {

        $oProgressaoParcial = new ProgressaoParcialAluno();
        $oProgressaoParcial->setAluno     ( $oAluno );
        $oProgressaoParcial->setAno       ( $oProgressao->iAno );
        $oProgressaoParcial->setDisciplina( DisciplinaRepository::getDisciplinaByCodigo( $oProgressao->iDisciplina ) );
        $oProgressaoParcial->setEtapa     ( EtapaRepository::getEtapaByCodigo( $oProgressao->iEtapa ) );
        $oProgressaoParcial->setEscola    ( EscolaRepository::getEscolaByCodigo( $iDepartamentoLogado ) );
        $oProgressaoParcial->setSituacaoProgressao(SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(ProgressaoParcialAluno::ATIVA));

        $oProgressaoParcial->salvar();
      }

      db_fim_transacao();

      $oRetorno->message = _M( $sCaminhoMensagens."progressao_salva" );

      break;

    /**
     * Retorna as progressões do aluno que estão ativas (não foram concluídas)
     * Parâmetros necessários
     * @param integer $oParam->iAluno código do aluno
     */
    case 'buscaProgressaoAluno':

      if ( !isset( $oParam->iAluno ) && empty( $oParam->iAluno ) ) {
      	throw new ParameterException( _M( $sCaminhoMensagens."aluno_nao_informado" ) );
      }

      $sCampos  = " ed114_sequencial, ed114_ano, ed114_serie, trim(ed11_c_descr) as serie, ";
      $sCampos .= " ed12_i_codigo, trim(ed232_c_descr) as disciplina";
      $sWhere   = "     ed114_situacaoeducacao  = " . ProgressaoParcialAluno::ATIVA;
      $sWhere  .= " and ed114_aluno = {$oParam->iAluno} ";

      $oDaoProgressao = new cl_progressaoparcialaluno();
      $sSqlProgressao = $oDaoProgressao->sql_query_aluno_em_progressao( null, $sCampos, null, $sWhere );
      $rsProgressao   = db_query($sSqlProgressao);

      if ( !$rsProgressao ) {
      	throw new DBException( _M( $sCaminhoMensagens."erro_ao_buscar_progressoes" ) );
      }
      $iLinhas = pg_num_rows( $rsProgressao );

      $oRetorno->aProgressao = array();
      for ($i = 0; $i < $iLinhas; $i++) {

        $oDadosProgressao                = db_utils::fieldsMemory($rsProgressao, $i);
        $oProgressao                     = new stdClass();
        $oProgressao->iCodigoProgressao  = $oDadosProgressao->ed114_sequencial;
        $oProgressao->iAno               = $oDadosProgressao->ed114_ano;
        $oProgressao->iEtapa             = $oDadosProgressao->ed114_serie;
        $oProgressao->sEtapa             = urlencode($oDadosProgressao->serie);
        $oProgressao->iDisciplina        = $oDadosProgressao->ed12_i_codigo;
        $oProgressao->sDisciplina        = urlencode($oDadosProgressao->disciplina);

        $oRetorno->aProgressao[] = $oProgressao;
      }

      break;
  }
} catch (BusinessException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
} catch (ParameterException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
} catch (DBException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $eErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);