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

define( 'MENSAGENS_EDU4_ETAPAS_RPC', 'educacao.escola.edu4_etapas_RPC.' );

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
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  switch($oParam->exec) {

    /**
     * Pesquisa os dados dos alunos para cancelamento da troca de turma
     */
    case 'pesquisaEtapas':

      $aFiltros = array();

      if (isset($oParam->iEscola) && !empty($oParam->iEscola) && $oParam->iEscola != 0) {
        $aFiltros[] = " ed18_i_codigo in ({$oParam->iEscola}) ";
      }

      if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
        $aFiltros[] = " ed57_i_calendario in ({$oParam->iCalendario}) ";
      }

      if (isset($oParam->iAnoCalendario) && !empty($oParam->iAnoCalendario)) {
        $aFiltros[] = " ed52_i_ano in ({$oParam->iAnoCalendario}) ";
      }

      if (isset($oParam->iCurso) && !($oParam->iCurso)) {
        $aFiltros[] = " ed11_i_ensino= {$oParam->iCurso} ";
      }

      $sWhere    = implode(" and ", $aFiltros);
      $sCampos   = "distinct ed11_i_ensino,  ed11_i_codigo as codigo_etapa, ";
      $sCampos  .= "ed11_c_descr as etapa, ed10_c_abrev as ensino";
      $sOrdem    = "ed10_c_abrev, ed11_c_descr";

      $oDaoTurma  = new cl_turma();
      $sSqlEtapas = $oDaoTurma->sql_query_turma(null, $sCampos, $sOrdem, $sWhere);
      $rsEtapas   = $oDaoTurma->sql_record($sSqlEtapas);
      $iLnhas     = $oDaoTurma->numrows;

      if ($iLnhas == 0) {

        $sMsgErro  = "Nenhuma etapa encontrada.\n";
        $sMsgErro .= str_replace('\\n', "\n", $oDaoTurma->erro_sql);
        throw new Exception($sMsgErro, 1);
      }

      $oRetorno->dados = db_utils::getCollectionByRecord($rsEtapas, false, false, true);

      break;

    /**
     * Salva a etapa do censo referente a uma etapa do ecidade
     */
    case 'salvarEtapaCenso':

      if( !isset( $oParam->iEtapa ) || empty( $oParam->iEtapa ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'etapa_nao_informada' ) );
      }

      if( !isset( $oParam->iEtapaCenso ) || empty( $oParam->iEtapaCenso ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'etapa_censo_nao_informada' ) );
      }

      if( !isset( $oParam->iAno ) || empty( $oParam->iAno ) ) {
        throw new ParameterException( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'ano_nao_informado' ) );
      }

      $oDaoSerieCensoEtapa   = new cl_seriecensoetapa();
      $sWhereSerieCensoEtapa = "ed133_serie = {$oParam->iEtapa} AND ed133_ano = {$oParam->iAno}";

      if( isset( $oParam->iCodigoVinculo ) && !empty( $oParam->iCodigoVinculo ) ) {
        $sWhereSerieCensoEtapa .= " AND ed133_codigo <> {$oParam->iCodigoVinculo}";
      }

      $sSqlSerieCensoEtapa = $oDaoSerieCensoEtapa->sql_query_file( null, '1', null, $sWhereSerieCensoEtapa );
      $rsSerieCensoEtapa   = db_query( $sSqlSerieCensoEtapa );

      if( !$rsSerieCensoEtapa ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'erro_validar_vinculo', $oErro ) );
      }

      if( pg_num_rows( $rsSerieCensoEtapa ) > 0 ) {

        $oMensagem       = new stdClass();
        $oMensagem->iAno = $oParam->iAno;
        throw new BusinessException( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'vinculo_existente', $oMensagem ) );
      }

      db_inicio_transacao();

      $oDaoSerieCensoEtapa->ed133_serie      = $oParam->iEtapa;
      $oDaoSerieCensoEtapa->ed133_censoetapa = $oParam->iEtapaCenso;
      $oDaoSerieCensoEtapa->ed133_ano        = $oParam->iAno;

      if( isset( $oParam->iCodigoVinculo ) && !empty( $oParam->iCodigoVinculo ) ) {

        $oDaoSerieCensoEtapa->ed133_codigo = $oParam->iCodigoVinculo;
        $oDaoSerieCensoEtapa->alterar( $oParam->iCodigoVinculo );
      } else {
        $oDaoSerieCensoEtapa->incluir( null );
      }

      if( $oDaoSerieCensoEtapa->erro_status == 0 ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'erro_salvar_vinculo', $oErro ) );
      }

      db_fim_transacao();

      $oRetorno->message = urlencode( _M( MENSAGENS_EDU4_ETAPAS_RPC . 'etapa_censo_salva' ) );

      break;

    /**
     * Busca vínculos existem a uma etapa do ecidade com etapas do censo
     */
    case "buscarEtapaVinculadaEtapasCenso":

      $sCampos = " ed133_codigo as vinculo, ed133_censoetapa as etapa_censo, ed133_ano as ano, ed266_c_descr as descricao";
      $sWhere  = " ed133_serie = {$oParam->iSerie} ";
      $oDaoCensoEtapa = new cl_seriecensoetapa();
      $sSqlCensoEtapa = $oDaoCensoEtapa->sql_query( null, $sCampos, "ed133_ano", $sWhere );
      $rsCensoEtapa   = db_query($sSqlCensoEtapa);
      if (!$rsCensoEtapa) {

        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M( MENSAGENS_EDU4_ETAPAS_RPC . "erro_buscar_etapas_vinculadas", $oMsgErro ) );
      }

      $iLinhas                = pg_num_rows($rsCensoEtapa);
      $oRetorno->aEtapasCenso = array();
      for ($i=0; $i < $iLinhas; $i++) {

        $oRetorno->aEtapasCenso[] = db_utils::fieldsMemory($rsCensoEtapa, $i, false, false, true);
      }

      break;
  }
} catch( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
  $oRetorno->erro    = true;
}

echo $oJson->encode($oRetorno);