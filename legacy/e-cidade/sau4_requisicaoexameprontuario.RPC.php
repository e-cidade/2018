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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oErro               = new stdClass();
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define('MENSAGEM_REQUISICAO_EXAME', 'saude.ambulatorial.sau4_requisicaoexameprontuario_RPC.');

try {

  db_inicio_transacao();

  switch($oParam->sExecucao) {

    /**
     * Salva a requisição de exame de um prontuário e todos os seus exames.
     * Busca os exames já cadastrados na requisição e inclui somente aqueles que não foram cadastrados ainda
     */
    case 'salvarRequisicaoExame':

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( MENSAGEM_REQUISICAO_EXAME . "prontuario_nao_informado") );
      }

      if ( !isset($oParam->iMedico) || empty($oParam->iMedico) ) {
        throw new ParameterException( _M( MENSAGEM_REQUISICAO_EXAME . "medico_nao_informado") );
      }

      $iCodigoRequisicaoExameProntuario = isset($oParam->iRequisicaoExameProntuario) ? $oParam->iRequisicaoExameProntuario : null;
      $sObservacao                      = isset($oParam->sObservacao) ? $oParam->sObservacao : '';
      $aExames                          = isset($oParam->aExames) ? $oParam->aExames : array();

      $oRequisicaoExameProntuarioDao                    = new cl_requisicaoexameprontuario();
      $oRequisicaoExameProntuarioDao->sd103_prontuarios = $oParam->iProntuario;
      $oRequisicaoExameProntuarioDao->sd103_medicos     = $oParam->iMedico;
      $oRequisicaoExameProntuarioDao->sd103_data        = date("Y-m-d");
      $oRequisicaoExameProntuarioDao->sd103_hora        = date("H:i");
      $oRequisicaoExameProntuarioDao->sd103_observacao  = db_stdClass::normalizeStringJsonEscapeString( $sObservacao );

      if ( empty($iCodigoRequisicaoExameProntuario) ) {

        $oRequisicaoExameProntuarioDao->sd103_codigo = null;
        $oRequisicaoExameProntuarioDao->incluir(null);
      } else {
        $oRequisicaoExameProntuarioDao->sd103_codigo = $iCodigoRequisicaoExameProntuario;
        $oRequisicaoExameProntuarioDao->alterar($iCodigoRequisicaoExameProntuario);
      }

      if ( $oRequisicaoExameProntuarioDao->erro_status == "0" ) {

        $oErro->sErro = $oRequisicaoExameProntuarioDao->erro_msg;
        throw new DBException(  _M( MENSAGEM_REQUISICAO_EXAME . "erro_salvar_requisicao_exame", $oErro) );
      }

      $iCodigoRequisicaoExameProntuario = $oRequisicaoExameProntuarioDao->sd103_codigo;

      $oDaoExameRequisicaoExame   = new cl_examerequisicaoexame();
      $sWhereExameRequisicaoExame = "sd104_requisicaoexameprontuario = {$iCodigoRequisicaoExameProntuario}";
      $sSqlExameRequisicaoExame   = $oDaoExameRequisicaoExame->sql_query_file(null, "sd104_lab_exame", null, $sWhereExameRequisicaoExame);
      $rsExameRequisicaoExame     = db_query( $sSqlExameRequisicaoExame );

      if ( !$rsExameRequisicaoExame ) {

        $oErro->sErro = pg_last_error();
        throw new DBException(  _M( MENSAGEM_REQUISICAO_EXAME . "erro_buscar_exames_vinculados", $oErro) );
      }

      $iLinhasExameRequisicao = pg_num_rows($rsExameRequisicaoExame);
      $aExamesCadastrados     = array();

      for ( $iContadorExame = 0; $iContadorExame < $iLinhasExameRequisicao; $iContadorExame++ ) {
        $aExamesCadastrados[] = db_utils::fieldsMemory( $rsExameRequisicaoExame, $iContadorExame )->sd104_lab_exame;
      }

      for ( $iContador = 0; $iContador < count($aExames); $iContador++ ) {

        if ( in_array($aExames[$iContador], $aExamesCadastrados) ) {
          continue;
        }

        $oDaoExameRequisicaoExame->sd104_codigo                    = null;
        $oDaoExameRequisicaoExame->sd104_requisicaoexameprontuario = $iCodigoRequisicaoExameProntuario;
        $oDaoExameRequisicaoExame->sd104_lab_exame                 = $aExames[$iContador];
        $oDaoExameRequisicaoExame->incluir(null);
      }

      if ( $oDaoExameRequisicaoExame->erro_status == "0" ) {

        $oErro->sErro = $oDaoExameRequisicaoExame->erro_msg;
        throw new DBException(  _M( MENSAGEM_REQUISICAO_EXAME . "erro_salvar_exames", $oErro) );
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_REQUISICAO_EXAME . "requisicao_exame_sucesso" ) );
    break;

    /**
     * Remove um exame da requisição de exames do prontuário
     */
    case 'removerExame':

      if ( !isset($oParam->iExameRequisicao) || empty($oParam->iExameRequisicao) ) {
        throw new ParameterException( _M( MENSAGEM_REQUISICAO_EXAME . "codigo_exame_requisicao_nao_informado") );
      }

      $oDaoExameRequisicaoExame = new cl_examerequisicaoexame();
      $oDaoExameRequisicaoExame->excluir( $oParam->iExameRequisicao );

      if ( $oDaoExameRequisicaoExame->erro_status == "0" ) {

        $oErro->sErro = $oDaoExameRequisicaoExame->erro_msg;
        throw new DBException(  _M( MENSAGEM_REQUISICAO_EXAME . "erro_excluir_exame", $oErro) );
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_REQUISICAO_EXAME . "exame_excluido_sucesso" ) );
    break;

    /**
     * Busca o código, a observação e os exames da requisição de exames do prontuário.
     */
    case 'buscarRequisicaoProntuario' :

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( MENSAGEM_REQUISICAO_EXAME . "prontuario_nao_informado") );
      }

      $oExameRequisicao       = new cl_examerequisicaoexame();
      $sWhereExameRequisicao  = " sd103_prontuarios = {$oParam->iProntuario} ";
      $sCamposExameRequisicao = " sd103_codigo, sd103_observacao, sd104_codigo, sd104_lab_exame, la08_c_descr";
      $sSqlExameRequisicao    = $oExameRequisicao->sql_query(null, $sCamposExameRequisicao, null, $sWhereExameRequisicao);
      $rsExameRequisicao      = db_query( $sSqlExameRequisicao );

      if ( !$rsExameRequisicao ) {

        $oErro->sErro = pg_last_error();
        throw new DBException(  _M( MENSAGEM_REQUISICAO_EXAME . "erro_buscar_dados_requisicao_prontuario", $oErro) );
      }

      $iLinhasRequisicaoExame = pg_num_rows( $rsExameRequisicao );
      $oRetorno->iRequisicao  = null;
      $oRetorno->sObservacao  = '';
      $oRetorno->aExames      = array();


      for ( $iContador = 0; $iContador < $iLinhasRequisicaoExame; $iContador++ ) {

        $oDadosRequisicaoExame = db_utils::fieldsMemory( $rsExameRequisicao, $iContador );
        $oRetorno->iRequisicao = $oDadosRequisicaoExame->sd103_codigo;
        $oRetorno->sObservacao = urlencode($oDadosRequisicaoExame->sd103_observacao);

        $oExame                   = new stdClass();
        $oExame->iExameRequisicao = $oDadosRequisicaoExame->sd104_codigo;
        $oExame->iExame           = $oDadosRequisicaoExame->sd104_lab_exame;
        $oExame->sExame           = urlencode($oDadosRequisicaoExame->la08_c_descr);

        $oRetorno->aExames[] = $oExame;
      }

    break;
  }

  db_fim_transacao();

} catch ( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);