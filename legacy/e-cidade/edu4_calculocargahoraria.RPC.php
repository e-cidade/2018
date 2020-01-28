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
$iEscola             = db_getsession('DB_coddepto');

define("MENSAGEM_CALCULOCARGAHORARIARPC", "educacao.escola.edu4_calculocargahoraria_RPC.");

try {

  db_inicio_transacao();

  switch($oParam->sExecucao) {

    /**
     * Case para alterar a configuração do parametro da carga horaria de acordo com o ano selecionado
     */
    case 'alterarCalculoCargaHoraria':

      if ( !isset($oParam->iCalculoCargaHoraria) || empty($oParam->iCalculoCargaHoraria) ) {
        throw new ParameterException( _M( MENSAGEM_CALCULOCARGAHORARIARPC . 'codigo_nao_informado') );
      }

      $oRegraCalculoHorariaDao                              = new cl_regracalculocargahoraria();
      $oRegraCalculoHorariaDao->ed127_codigo                = $oParam->iCalculoCargaHoraria;
      $oRegraCalculoHorariaDao->ed127_ano                   = $oParam->iAnoCargaHoraria;
      $oRegraCalculoHorariaDao->ed127_calculaduracaoperiodo = $oParam->lCalculaDuracaoPeriodo ? 'true' : 'false';
      $oRegraCalculoHorariaDao->ed127_escola                = $iEscola;
      $oRegraCalculoHorariaDao->alterar( $oParam->iCalculoCargaHoraria );

      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_CALCULOCARGAHORARIARPC . "salvo_sucesso" ) );

    break;

    /**
     * Case para validar se o ano selecionado não possui turmas fechadas
     */
    case 'validarAlteracaoParametro':

      if ( !isset($oParam->iAno) || empty($oParam->iAno) ) {
        throw new ParameterException( _M( MENSAGEM_CALCULOCARGAHORARIARPC . 'ano_nao_informado') );
      }

      $oRetorno->lBloquearAlteracao = false;
      $oDaoRegencia = new cl_regencia();

      $sWhereRegenciaEncerrada  = "ed59_c_encerrada = 'S' and ed57_i_escola = {$iEscola} AND ed52_i_ano = {$oParam->iAno}";
      $sSqlRegenciaEncerrada    = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaEncerrada);
      $rsRegenciaEncerrada      =  db_query( $sSqlRegenciaEncerrada );

      if ( !$rsRegenciaEncerrada ) {
        throw new DBException( _M( MENSAGEM_CALCULOCARGAHORARIARPC . 'erro_buscar_regencias') );
      }

      if ( pg_num_rows( $rsRegenciaEncerrada ) > 0 ) {

        $oRetorno->lBloquearAlteracao = true;
        $oRetorno->sMensagem = urlencode( _M( MENSAGEM_CALCULOCARGAHORARIARPC . "alteracao_nao_permitida" ) );
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