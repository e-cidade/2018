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
define( 'MENSAGENS_EDU4_TIPOHORATRABALHO_RPC', 'educacao.escola.edu4_tipohoratrabalho_RPC.' );

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro      = false;

try {

  switch( $oParam->sExecuta ) {

    /**
     * Busca os tipos de hora de trabalho cadastrados
     */
    case 'buscaTipoHoraCadastradas':

      $oDaoTipoHoraTrabalho = new cl_tipohoratrabalho();

      /**
       * Tipo padrão nao pode ser alterado
       */
      $sSqlTipoPadrao = $oDaoTipoHoraTrabalho->sql_query_file( null, 'min(ed128_codigo) as codigo', null, null );
      $rsTipoPadrao   = db_query( $sSqlTipoPadrao );

      $sCampos = " *, (select distinct 1 from agendaatividade where ed129_tipohoratrabalho = ed128_codigo) as vinculado";

      $sSqlTipoHoraTrabalho = $oDaoTipoHoraTrabalho->sql_query_file( null, $sCampos, 'ed128_codigo', null );
      $rsTipoHoraTrabalho   = db_query( $sSqlTipoHoraTrabalho );

      if( !$rsTipoHoraTrabalho || !$rsTipoPadrao) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'erro_buscar_tipo_hora', $oErro ) );
      }

      $oRetorno->aTipoHoraTrabalho         = db_utils::getCollectionByRecord( $rsTipoHoraTrabalho, false, false, true );
      $oRetorno->iCodigoNaoPodeSerAlterado = db_utils::fieldsMemory($rsTipoPadrao, 0)->codigo;

      break;

    /**
     * Salva as informações do tipo de hora de trabalho
     */
    case 'salvar':

      db_inicio_transacao();

      if( empty( $oParam->sDescricao ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'descricao_nao_informada' ) );
      }

      if( empty( $oParam->sAbreviatura ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'abreviatura_nao_informada' ) );
      }

      if( empty( $oParam->iEfetividade ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'efetividade_nao_informada' ) );
      }

      $oDaoValidaTipoHora    = new cl_tipohoratrabalho();
      $sWhereValidaTipoHora  = "     (    trim(ed128_descricao) = '" . trim( $oParam->sDescricao ) . "'";
      $sWhereValidaTipoHora .= "       or trim(ed128_abreviatura) = '" . trim( $oParam->sAbreviatura ) . "' )";

      if( !empty( $oParam->iCodigo ) ) {
        $sWhereValidaTipoHora .= " AND ed128_codigo <> {$oParam->iCodigo}";
      }

      $sSqlValidaTipoHora    = $oDaoValidaTipoHora->sql_query_file( null, '1', null, $sWhereValidaTipoHora );
      $rsValidaTipoHora      = db_query( $sSqlValidaTipoHora );

      if( !$rsValidaTipoHora ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'erro_validar_tipo_hora', $oErro ) );
      }

      if( pg_num_rows( $rsValidaTipoHora ) > 0 ) {

        $oErro        = new stdClass();
        $oErro->sTipo = empty( $oParam->iCodigo ) ? 'Inclusão' : 'Alteração';
        throw new BusinessException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'registro_existente', $oErro ) );
      }

      $oTipoHoraTrabalho = new TipoHoraTrabalho( $oParam->iCodigo );
      $oTipoHoraTrabalho->setDescricao( db_stdClass::normalizeStringJsonEscapeString( $oParam->sDescricao ) );
      $oTipoHoraTrabalho->setAbreviatura( db_stdClass::normalizeStringJsonEscapeString( $oParam->sAbreviatura ) );
      $oTipoHoraTrabalho->setEfetividade( $oParam->iEfetividade );
      $oTipoHoraTrabalho->setAtivo( $oParam->sAtivo == 't' );
      $oTipoHoraTrabalho->salvar();

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'tipo_hora_salvo' ) );

      db_fim_transacao();

      break;

    /**
     * Exclui um tipo de hora de trabalho, verificando primeiramente, se este possui algum vínculo, não permitindo
     * a exclusão
     */
    case 'excluirTipoHoraTrabalho':

      if( !isset( $oParam->iCodigo ) || empty( $oParam->iCodigo ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'codigo_nao_informado' ) );
      }

      $oDaoVinculoTipoHora    = new cl_tipohoratrabalho();
      $sWhereVinculoTipoHora  = "    ed129_tipohoratrabalho = {$oParam->iCodigo}";
      $sWhereVinculoTipoHora .= " OR ed33_tipohoratrabalho  = {$oParam->iCodigo}";
      $sWhereVinculoTipoHora .= " OR ed23_tipohoratrabalho  = {$oParam->iCodigo}";
      $sSqlVinculoTipoHora    = $oDaoVinculoTipoHora->sql_query_vinculos( null, '1', null, $sWhereVinculoTipoHora );
      $rsVinculoTipoHora      = db_query( $sSqlVinculoTipoHora );

      if( !$rsVinculoTipoHora ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'erro_buscar_vinculo_tipo_hora', $oErro ) );
      }

      if( pg_num_rows( $rsVinculoTipoHora ) > 0 ) {
        throw new BusinessException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'tipo_hora_possui_vinculo' ) );
      }

      $oDaoVinculoTipoHora->excluir( $oParam->iCodigo );

      if( $oDaoVinculoTipoHora->erro_status == '0' ) {

        $oErro        = new stdClass();
        $oErro->sErro = $oDaoVinculoTipoHora->erro_msg;
        throw new DBException( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'erro_excluir_tipo_hora', $oErro ) );
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_EDU4_TIPOHORATRABALHO_RPC . 'tipo_hora_excluido' ) );

      break;
  }
} catch( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
  $oRetorno->erro      = true;
}

echo $oJson->encode( $oRetorno );