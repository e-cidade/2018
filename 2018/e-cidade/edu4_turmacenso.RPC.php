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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

define("URL_MENSAGEM_TURMACENSO_RPC", "educacao.escola.edu4_turmascenso_RPC.");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch ( $oParam->exec ) {

    case 'getTurmasCompartilhamSala':

      $oCalendario = CalendarioRepository::getCalendarioByCodigo($oParam->iCalendario);
      $oSala       = SalaRepository::getByCodigo($oParam->iDependencia);

      $aTurmas = TurmaRepository::getTurmasCompartilhamSala($oCalendario, $oSala, $oParam->iTurnoReferente);


      $oRetorno->aTurmasSala = array();
      $oRetorno->iTurmaCenso = null;
      $oRetorno->iEtapaCenso = null;
      $oRetorno->sNomeTurma  = "";

      if ( count($aTurmas) == 0 ) {
        throw new Exception( _M(URL_MENSAGEM_TURMACENSO_RPC."nenhuma_turma_encontrada") );
      }

      $oDaoTurmaVinculada = new cl_turmacensoturma();

      foreach ( $aTurmas as $oTurma ) {

        $oDados             = new stdClass();
        $oDados->sTurma     = urlencode( $oTurma->getDescricao() );
        $oDados->iTurma     = $oTurma->getCodigo();
        $oDados->lVinculada = false;

        $sCampos     = 'ed134_censoetapa, ed342_nome, ed343_turmacenso';
        $sSql        = $oDaoTurmaVinculada->sql_query2(null, $sCampos, null, "ed343_turma = $oDados->iTurma ");
        $rsVinculada = db_query($sSql);

        $oDados->lVinculada      = pg_num_rows( $rsVinculada ) > 0;
        $oRetorno->aTurmasSala[] = $oDados;
        $oDadosRetorno           = db_utils::fieldsMemory( $rsVinculada, 0 );

        if( empty( $oRetorno->iEtapaCenso ) ) {

          $oRetorno->iTurmaCenso = $oDadosRetorno->ed343_turmacenso;
          $oRetorno->iEtapaCenso = $oDadosRetorno->ed134_censoetapa;
          $oRetorno->sNomeTurma  = urlencode( $oDadosRetorno->ed342_nome );
        }
      }

      break;

    case 'getDadosTurmaCenso':

      $oTurmaCenso           = new TurmaCenso($oParam->iTurmaCenso);
      $oRetorno->iTurmaCenso = $oTurmaCenso->getCodigo();
      $oRetorno->sTurmaCenso = urlencode( $oTurmaCenso->getNomeTurma() );
      $oRetorno->iEtapaCenso = $oTurmaCenso->getEtapaCenso();
      $oRetorno->aTurmasSala = array();

      foreach ( $oTurmaCenso->getTurmaCensoTurma() as $oTurmaVinculada ) {

        $oDados             = new stdClass();
        $oDados->sTurma     = urlencode( $oTurmaVinculada->getTurma()->getDescricao() );
        $oDados->iTurma     = $oTurmaVinculada->getTurma()->getCodigo();
        $oDados->lVinculada = true;

        $oRetorno->aTurmasSala[] = $oDados;
      }

      break;

    case 'salvar':

      db_inicio_transacao();

      $iCodigoTurmaPrincipal  = 999999999;
      $iTurmaCenso            = $oParam->iTurmaCenso;
      $sTurmas                = implode( ", ", $oParam->aTurmas );

      /**
       * Verifica se existem outras turmas que utilizam a mesma sala selecionada
       */
      $oDaoTurmaCensoTurma    = new cl_turmacensoturma();
      $sCamposTurmaCensoTurma = "ed342_sequencial, ed57_i_codigo";
      $sWhereTurmaCensoTurma  = "     ed57_i_sala = {$oParam->iSala}";
      $sWhereTurmaCensoTurma .= " AND exists( select 1 ";
      $sWhereTurmaCensoTurma .= "               from turmacensoturma ";
      $sWhereTurmaCensoTurma .= "              where ed343_principal is true ";
      $sWhereTurmaCensoTurma .= "                AND ed343_turma = ed57_i_codigo)";
      $sSqlTurmaCensoTurma    = $oDaoTurmaCensoTurma->sql_query( null, $sCamposTurmaCensoTurma, null, $sWhereTurmaCensoTurma );
      $rsTurmaCensoTurma      = db_query( $sSqlTurmaCensoTurma );

      if( $rsTurmaCensoTurma && pg_num_rows( $rsTurmaCensoTurma ) > 0 ) {

        $oDadosRetorno         = db_utils::fieldsMemory( $rsTurmaCensoTurma, 0 );
        $iCodigoTurmaPrincipal = $oDadosRetorno->ed57_i_codigo;
        $iTurmaCenso           = $oDadosRetorno->ed342_sequencial;
      }

      $oTurmaCenso = new TurmaCenso( $iTurmaCenso );
      $oTurmaCenso->setAnoCalendarioTurma($oParam->iAnoCalendario);
      $oTurmaCenso->setEtapaCenso($oParam->iCensoEtapa);
      $oTurmaCenso->setNomeTurma($oParam->sTurmaCenso);
      $oTurmaCenso->removerTurmas();

      foreach ($oParam->aTurmas as $iCodigoTurma ) {

        $oTurma           = TurmaRepository::getTurmaByCodigo($iCodigoTurma);
        $oTurmaCensoTurma = new TurmaCensoTurma();
        $oTurmaCensoTurma->setTurma($oTurma);
        $oTurmaCenso->adicionarTurmaCensoTurma($oTurmaCensoTurma);
      }

      $oTurmaCenso->salvar();

      db_fim_transacao();

      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMACENSO_RPC."salvo_com_sucesso") );

      break;

    case 'excluir':

      $oTurmaCenso = new TurmaCenso($oParam->iTurmaCenso);
      db_inicio_transacao();
      $oTurmaCenso->remover();
      db_fim_transacao();
      $oRetorno->message = urlencode( _M(URL_MENSAGEM_TURMACENSO_RPC."removido_com_sucesso") );

      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);