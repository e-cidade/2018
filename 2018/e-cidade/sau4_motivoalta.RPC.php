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
define( 'MENSAGENS_MOTIVOALTA_RPC', 'saude.ambulatorial.sau4_motivoalta_rpc.' );

require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

$iUsuario      = db_getsession( "DB_id_usuario" );
$iDepartamento = db_getsession( "DB_coddepto" );
$oData         = new DBDate( date( 'Y-m-d' ) );
$sHora         = date( 'H:i' );

try {

	switch( $oParam->sExecucao ) {

    /**
     * Retorna os motivos de alta da saúde
     */
    case 'buscaMotivosAlta':

      $oDaoMotivoAlta    = new cl_motivoalta();
      $sCamposMotivoAlta = "sd01_codigo, sd01_descricao";
      $sWhereMotivoAlta  = "";

      if( isset( $oParam->lFinalizaAtendimento ) ) {
        $sWhereMotivoAlta  = "sd01_finalizaatendimento is true";
      }

      $sSqlMotivoAlta    = $oDaoMotivoAlta->sql_query_file( null, "*", null, $sWhereMotivoAlta );
      $rsMotivoAlta      = db_query( $sSqlMotivoAlta );

      if( !$rsMotivoAlta ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( MENSAGENS_MOTIVOALTA_RPC . 'erro_buscar_motivos_alta', $oErro ) );
      }

      $oRetorno->aMotivosAlta = array();
      $iTotalMotivoAlta       = pg_num_rows( $rsMotivoAlta );

      for( $iContador = 0; $iContador < $iTotalMotivoAlta; $iContador++ ) {

        $oDadosMotivoAlta             = new stdClass();
        $oRetornoMotivoAlta           = db_utils::fieldsMemory( $rsMotivoAlta, $iContador );
        $oDadosMotivoAlta->iCodigo    = $oRetornoMotivoAlta->sd01_codigo;
        $oDadosMotivoAlta->sDescricao = urlencode( $oRetornoMotivoAlta->sd01_descricao );
        $oDadosMotivoAlta->iCodigoSus = $oRetornoMotivoAlta->sd01_codigosus;

        $oRetorno->aMotivosAlta[] = $oDadosMotivoAlta;
      }

      break;

    /**
     * Finaliza o atendimento a um paciente
     */
    case 'finalizaAtendimento':

      if( !isset( $oParam->iMotivoAlta ) || empty( $oParam->iMotivoAlta ) ) {
        throw new ParameterException( _M( MENSAGENS_MOTIVOALTA_RPC . 'motivo_alta_nao_informado' ) );
      }

      if( !isset( $oParam->iProntuario ) || empty( $oParam->iProntuario ) ) {
        throw new ParameterException( _M( MENSAGENS_MOTIVOALTA_RPC . 'prontuario_nao_informado' ) );
      }

      db_inicio_transacao();

      $oDaoProntuariosMotivoAlta                   = new cl_prontuariosmotivoalta();
      $oDaoProntuariosMotivoAlta->sd25_motivoalta  = $oParam->iMotivoAlta;
      $oDaoProntuariosMotivoAlta->sd25_prontuarios = $oParam->iProntuario;
      $oDaoProntuariosMotivoAlta->sd25_data        = $oData->getDate();
      $oDaoProntuariosMotivoAlta->sd25_hora        = $sHora;
      $oDaoProntuariosMotivoAlta->sd25_db_usuarios = $iUsuario;
      $oDaoProntuariosMotivoAlta->incluir( null );

      if( $oDaoProntuariosMotivoAlta->erro_status == "0" ) {

        $oErro        = new stdClass();
        $oErro->sErro = $oDaoProntuariosMotivoAlta->erro_msg;
        throw new DBException( _M( MENSAGENS_MOTIVOALTA_RPC . 'erro_finalizar_atendimento', $oErro ) );
      }

      $oProntuario = new Prontuario( $oParam->iProntuario );
      $oProntuario->setFinalizado( true );
      $oProntuario->salvar();

      $oUltimaMovimentacao = MovimentacaoFichaAtendimentoRepository::getUltimaMovimentacaoFAA($oParam->iProntuario);

      if ( !empty($oUltimaMovimentacao) ) {

        $oNovaMovimentacao = new MovimentacaoFichaAtendimento();
        $oNovaMovimentacao->setFichaAtendimento($oParam->iProntuario);
        $oNovaMovimentacao->setUsuarioSistema(UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario')));
        $oNovaMovimentacao->setSetorAmbulatorial($oUltimaMovimentacao->getSetorAmbulatorial());
        $oNovaMovimentacao->setData(new DBDate(date("Y-m-d")));
        $oNovaMovimentacao->setHora(date("H:i"));
        $oNovaMovimentacao->setSituacao(MovimentacaoFichaAtendimento::SITUACAO_FINALIZADA);
        $oNovaMovimentacao->salvar();
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_MOTIVOALTA_RPC . 'atendimento_finalizado' ) );

      db_fim_transacao();

      break;

    /**
     * Retorna dados do prontuário
     */
    case 'buscaDadosProntuario':

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( MENSAGENS_MOTIVOALTA_RPC . 'prontuario_nao_informado' ) );
      }

      $oProntuario             = new Prontuario( $oParam->iProntuario );
      $oRetorno->sNomePaciente = urlencode( $oProntuario->getCGS()->getNome() );

      if( $oProntuario->isFinalizado() ) {

        $oMensagem        = new stdClass();
        $oMensagem->sNome = $oProntuario->getCGS()->getNome();
        throw new BusinessException( _M( MENSAGENS_MOTIVOALTA_RPC . 'prontuario_finalizado', $oMensagem ) );
      }

      break;
  }
} catch ( Exception $oErro ) {

	db_fim_transacao(true);
	$oRetorno->iStatus   = 2;
	$oRetorno->sMensagem = urlencode( $oErro->getMessage() );
}

echo $oJson->encode($oRetorno);