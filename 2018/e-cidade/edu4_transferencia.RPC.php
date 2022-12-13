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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/JSON.php"));

define( "MENSAGEM_TRANSFERENCIA_RPC", "educacao.escola.edu4_transferencia_RPC." );

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  switch ($oParam->exec) {

    case "verificaMatriculaEscola":

      $sWhere  = "     matriculaserie.ed221_c_origem = 'S' ";
      $sWhere .= " and ed60_c_ativa = 'S' ";
      $sWhere .= " and ed60_i_aluno = {$oParam->iAluno} ";
      $sWhere .= " and calendario.ed52_i_ano = {$oParam->iAno} ";
      $sWhere .= " and turma.ed57_i_escola = " . db_getsession("DB_coddepto");

      $sCampos  = " matricula.ed60_i_codigo,                       ";
      $sCampos .= " matricula.ed60_i_turma,                        ";
      $sCampos .= " matricula.ed60_c_concluida ,                   ";
      $sCampos .= " matricula.ed60_c_situacao ,                    ";
      $sCampos .= " matricula.ed60_d_datamatricula ,               ";
      $sCampos .= " matricula.ed60_d_datasaida,                    ";
      $sCampos .= " turma.ed57_c_descr,                            ";
      $sCampos .= " turma.ed57_i_turno,                            ";
      $sCampos .= " turma.ed57_i_base,                             ";
      $sCampos .= " turma.ed57_i_calendario,                       ";
      $sCampos .= " turno.ed15_c_nome,                             ";
      $sCampos .= " calendario.ed52_i_ano,                         ";
      $sCampos .= " calendario.ed52_c_descr,                       ";
      $sCampos .= " calendario.ed52_d_inicio,                      ";
      $sCampos .= " calendario.ed52_d_fim,                         ";
      $sCampos .= " base.ed31_i_curso,                             ";
      $sCampos .= " base.ed31_c_descr,                             ";
      $sCampos .= " cursoedu.ed29_c_descr,                         ";
      $sCampos .= " fc_nomeetapaturma(ed60_i_turma) AS estapa,     ";
      $sCampos .= " serie.ed11_i_sequencia                         ";

      $oDaoMatricula = new cl_matricula();
      $sSqlMatricula = $oDaoMatricula->sql_query(null, $sCampos, null, $sWhere);
      $rsMatricula   = db_query($sSqlMatricula);

      $oRetorno->lTemMatriculaEscolaAno = false;
      if ( $rsMatricula && pg_num_rows($rsMatricula) > 0) {

        $oRetorno->lTemMatriculaEscolaAno = true;

        $oDados          = db_utils::fieldsMemory($rsMatricula, 0);
        $oDadosMatricula = new stdClass();

        $oDataMatricula  = new DBDate($oDados->ed60_d_datamatricula);
        $oDataSaida      = new DBDate($oDados->ed60_d_datasaida);
        $oDataHoje       = new DBDate(date("Y-m-d"));

        $iDiasPassados   = DBDate::calculaIntervaloEntreDatas( $oDataHoje, $oDataSaida, "d" );

        $oDadosMatricula->iMatricula    = $oDados->ed60_i_codigo;
        $oDadosMatricula->iTurma        = $oDados->ed60_i_turma;
        $oDadosMatricula->sSituacao     = urlencode($oDados->ed60_c_situacao);
        $oDadosMatricula->dtMatricula   = $oDataMatricula->convertTo(DBDate::DATA_PTBR);
        $oDadosMatricula->dtSaida       = $oDataSaida->convertTo(DBDate::DATA_PTBR);
        $oDadosMatricula->iDiasPassados = $iDiasPassados;

        $oRetorno->oDadosMatricula = $oDadosMatricula;
      }


      break;

    case 'verificaEtapaTurma':

      $oTurma = TurmaRepository::getTurmaByCodigo($oParam->iTurma);

      foreach ($oTurma->getEtapas() as $oEtapaTurma) {

        $oEtapa            = new stdClass();
        $oEtapa->iCodigo   = $oEtapaTurma->getEtapa()->getCodigo();
        $oEtapa->sDecricao = urlencode($oEtapaTurma->getEtapa()->getNome());
        $oEtapa->iOrdem    = $oEtapaTurma->getEtapa()->getOrdem();

        $oEtapa->lEquivalente = false;
        if ( $oEtapaTurma->getEtapa()->getCodigo() == $oParam->iEtapaTurmaAnterior) {
          $oEtapa->lEquivalente = true;
        } else {
          foreach ($oEtapaTurma->getEtapa()->buscaEtapaEquivalente() as $oEtapaEquivalente) {

            if ($oEtapaEquivalente->getCodigo() == $oParam->iEtapaTurmaAnterior) {
              $oEtapa->lEquivalente = true;
            }
          }
        }
        $oRetorno->aEtapasTurma[] = $oEtapa;
      }

      $sWhere ='ed233_i_escola = ' . db_getsession("DB_coddepto");
      $oDaoParametros = new cl_edu_parametros();
      $rsParametro    = db_query($oDaoParametros->sql_query_file(null, "ed233_c_consistirmat", null, $sWhere));
      if ( $rsParametro && pg_num_rows($rsParametro) == 1 ) {
        $oRetorno->lConsistirHistorico = db_utils::fieldsMemory($rsParametro, 0)->ed233_c_consistirmat == 'S';
      }
      $oRetorno->lMultietapa = count($oRetorno->aEtapasTurma) > 1;


      break;


    case "MatriculaFora":

      db_inicio_transacao();

      $oMatriculaAntiga = MatriculaRepository::getMatriculaByCodigo($oParam->iMatriculaAntiga);
      $oTurma           = TurmaRepository::getTurmaByCodigo( $oParam->iTurmaDestino );
      $oAluno           = AlunoRepository::getAlunoByCodigo($oParam->iAluno);

      $oTurmaAntiga                             = null;
      $oRetorno->iTurmaAnterior                 = '';
      $oRetorno->lPermiteImportarAproveitamento = true;

      if ($oMatriculaAntiga->getTurma() instanceof Turma && $oMatriculaAntiga->getTurma() != '') {

        $oTurmaAntiga             = $oMatriculaAntiga->getTurma();
        $oRetorno->iTurmaAnterior = $oTurmaAntiga->getCodigo();
      }

      /**
       * Verifica se o aluno já teve matricula na turma de destino, se este for o caso, devemos reabrir o
       * diário do aluno
       */
      $sWhere        ="     ed60_i_aluno = {$oParam->iAluno} ";
      $sWhere       .=" and ed60_i_turma = {$oParam->iTurmaDestino} ";
      $oDaoMatricula = new cl_matricula();
      $sSqlVerifica  =  $oDaoMatricula->sql_query_file(null, "*", null, $sWhere);
      $rsVerifica    = db_query($sSqlVerifica);

      /**
       * Cria a nova matricula
       */
      $oNovaMatricula   = new Matricula();
      $oNovaMatricula->setAluno( $oAluno );

      $oNovaMatricula->setTurma( $oTurma );
      $oNovaMatricula->setSituacao("MATRICULADO");
      $oNovaMatricula->setDataMatricula( new DBDate($oParam->dtMatricula) );
      $oNovaMatricula->setTipo( 'N' );
      $oNovaMatricula->matricular( EtapaRepository::getEtapaByCodigo( $oParam->iEtapa ), $oParam->aTurnosReferente, true );

      /**
       * Encerra matricula antiga
       */
      $oMatriculaAntiga->setConcluida(true);
      $oMatriculaAntiga->setAtiva(false);
      $oMatriculaAntiga->salvar();

      /**
       * Fechamos a transferencia
       */
      $oDaoTranfEscolaFora = new cl_transfescolafora();
      $oDaoTranfEscolaFora->ed104_c_situacao = 'F';
      $oDaoTranfEscolaFora->ed104_i_codigo   = $oParam->iCodigoTranferencia;
      $oDaoTranfEscolaFora->alterar($oParam->iCodigoTranferencia);

      /**
       * reativamos o diário de classe do aluno
       */
      if ( $rsVerifica && pg_num_rows($rsVerifica) > 0 ) {

        /*
         * Verifica se o aluno já possui diario de classe lançado para a turma de destino, caso haja
         * reativamos o diario o mesmo se não criamos um novo
         */
        $sWherePossuiDiario = "ed95_i_aluno = {$oParam->iAluno} AND ed59_i_turma = {$oParam->iTurmaDestino} ";
        $oDaoDiario         = new cl_diario();
        $sSqlPossuiDiario   = $oDaoDiario->sql_query_regencia( null, 'distinct 1', null, $sWherePossuiDiario);
        $rsPossuiDiario     = db_query($sSqlPossuiDiario);

        if ( !$rsPossuiDiario ) {

          $oErro        = new stdClass();
          $oErro->sErro = pg_last_error();
          throw new DBException( _M( MENSAGEM_TRANSFERENCIA_RPC. 'erro_buscar_diario', $oErro ) );
        }

        if ( pg_num_rows( $rsPossuiDiario ) > 0 ) {

          $sSqlDiario    = " UPDATE diario SET ";
          $sSqlDiario   .= "        ed95_c_encerrado = 'N' ";
          $sSqlDiario   .= "  WHERE ed95_i_aluno = {$oParam->iAluno} ";
          $sSqlDiario   .= "    AND ed95_i_regencia in (select ed59_i_codigo ";
          $sSqlDiario   .= "                              from regencia ";
          $sSqlDiario   .= "                             where ed59_i_turma = {$oParam->iTurmaDestino}) ";
          $sResultDiario = db_query($sSqlDiario);

          if ( !$sResultDiario ) {
            throw new Exception( "Erro ao reabrir o diário do aluno.\n" . pg_last_error());
          }
        } else {
          $oDiarioClasse = new DiarioClasse( $oNovaMatricula );
        }
      }

      $oTurmaAntiga = $oMatriculaAntiga->getTurma();
      $oTurmaNova   = $oNovaMatricula->getTurma();

      if(    $oTurmaAntiga->getCalendario()->getAnoExecucao() != $oTurmaNova->getCalendario()->getAnoExecucao()
          || $oTurmaAntiga->getTipoDaTurma() == 2
          || $oTurmaNova->getTipoDaTurma() == 2
        ) {
        $oRetorno->lPermiteImportarAproveitamento = false;
      }

      $oRetorno->oDados = validaProgressaoParcial($oAluno);
      db_fim_transacao();

      break;

    case "incluirMatriculaFora":

    break;
  }
} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);

/**
 * Verifica se o aluno possui progressão ativa na escola que está se transferindo
 * @param  Aluno  $oAluno  instancia de aluno
 * @return stdClass
 */
function validaProgressaoParcial($oAluno) {

  $oDadosProgressao = new stdClass();
  $oDadosProgressao->lTemProgressaoParcial = false;
  $oDadosProgressao->sMensagem             = urlencode("Matrícula efetuada com sucesso!");

  $lTemProgressaoParcial = false;
  $sMensagem             = "O(A) aluno(a) possui a(s) seguinte(s) dependência(s):\n";

  $oNovaMatricula = MatriculaRepository::getUltimaMatriculaAluno($oAluno);
  $iEscolaAtual   = $oNovaMatricula->getTurma()->getEscola()->getCodigo();

  foreach ( $oAluno->getProgressaoParcial() as $oProgressaoParcial ) {

    /**
     * Caso a progressão não esteja ativa, ou esteja concluída ou não seja da escola logada, não valida a progressão
     */
    if (    !$oProgressaoParcial->isAtiva()
         || $oProgressaoParcial->isConcluida()
         || $oProgressaoParcial->getEscola()->getCodigo() != $iEscolaAtual ) {
      continue;
    }

    $sMensagem .= " Etapa: {$oProgressaoParcial->getEtapa()->getNome()}";
    $sMensagem .= " - Disciplina: {$oProgressaoParcial->getDisciplina()->getNomeDisciplina()}";
    $sMensagem .= " - Ensino: {$oProgressaoParcial->getEtapa()->getEnsino()->getNome()};\n";

    $lTemProgressaoParcial = true;
  }

  $sMensagem .= "Acesse:\n";
  $sMensagem .= " Matrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial;\n";
  $sMensagem .= " Matrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma;";

  /**
   * Caso tenha sido encontrada alguma progressão válida, apresenta a mensagem
   */
  if ( $lTemProgressaoParcial ) {

    $oDadosProgressao->lTemProgressaoParcial = true;
    $oDadosProgressao->sMensagem             = urlencode($sMensagem);
  }

  return $oDadosProgressao;
}