<?php

/**
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson  = new services_json();
$oParam = $oJson->decode((str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = utf8_encode('Configurações salvas com sucesso!');
$oRetorno->erro    = false;
$oRetorno->aDados  = array();

$oDaoConfVencISSQNRetido   = db_utils::getDao('db_confplan');
$oDaoConfVencISSQNVariavel = db_utils::getDao('confvencissqnvariavel');
$iAnoUsu                   = db_getsession('DB_anousu');

switch ($oParam->exec) {

  case 'salvar':

    try {

      db_inicio_transacao();

      $iValorMinimoISSRetido = (int) $oParam->w10_valor;
      if (empty($iValorMinimoISSRetido) || $iValorMinimoISSRetido < 0) {
        throw new Exception('Informe um valor mínimo para o ISSQN Retido!');
      }

      if (empty($oParam->w10_receit)) {
        throw new Exception('Informe uma receita para o ISSQN Retido!');
      }

      if ($oParam->w10_hist == '') {
        throw new Exception('Informe um histórico para o ISSQN Retido!');
      }

      if (empty($oParam->w10_tipo)) {
        throw new Exception('Informe um tipo de débito para o ISSQN Retido!');
      }

      if (empty($oParam->q144_codvenc)) {
        throw new Exception('Informe um código do vencimento para o ISSQN Váriavel!');
      }

      if (empty($oParam->q144_receita)) {
        throw new Exception('Informe uma receita para o ISSQN Váriavel!');
      }

      if (empty($oParam->q144_tipo)) {
        throw new Exception('Informe um tipo de débito para o ISSQN Váriavel!');
      }

      if ($oParam->q144_hist == '') {
        throw new Exception('Informe um histórico para o ISSQN Váriavel!');
      }

      // Parâmetros ISSQN Retido
      $oDaoConfVencISSQNRetido->w10_valor  = $oParam->w10_valor;
      $oDaoConfVencISSQNRetido->w10_receit = $oParam->w10_receit;
      $oDaoConfVencISSQNRetido->w10_hist   = $oParam->w10_hist;
      $oDaoConfVencISSQNRetido->w10_tipo   = $oParam->w10_tipo;
      $oDaoConfVencISSQNRetido->w10_dia    = $oParam->w10_dia;

      // Verifica se existe um registro
      if (empty($oParam->w10_oid)) {
        $oDaoConfVencISSQNRetido->incluir();
      } else {
        $oDaoConfVencISSQNRetido->alterar($oParam->w10_oid);
      }

      if ($oDaoConfVencISSQNRetido->erro_status == '0') {
        throw new Exception($oDaoConfVencISSQNRetido->erro_msg);
      }

      // Parâmetros ISSQN Váriavel
      $oDaoConfVencISSQNVariavel->q144_ano     = (empty($oParam->q144_ano)) ? $iAnoUsu : $oParam->q144_ano;
      $oDaoConfVencISSQNVariavel->q144_codvenc = $oParam->q144_codvenc;
      $oDaoConfVencISSQNVariavel->q144_receita = $oParam->q144_receita;
      $oDaoConfVencISSQNVariavel->q144_tipo    = $oParam->q144_tipo;
      $oDaoConfVencISSQNVariavel->q144_hist    = $oParam->q144_hist;
      $oDaoConfVencISSQNVariavel->q144_diavenc = $oParam->q144_diavenc;

      // Verifica se existe um registro
      if (empty($oParam->q144_sequencial)) {
        $oDaoConfVencISSQNVariavel->atualizaParametrosGeraisVencimento()->incluir();
      } else {

        $oDaoConfVencISSQNVariavel->q144_sequencial = $oParam->q144_sequencial;
        $oDaoConfVencISSQNVariavel->atualizaParametrosGeraisVencimento()->alterar($oParam->q144_sequencial);
      }

      if ($oDaoConfVencISSQNVariavel->erro_status == '0') {
        throw new Exception($oDaoConfVencISSQNVariavel->erro_msg);
      }

      db_fim_transacao(false);
    } catch (Exception $oErro) {

      $oRetorno->message = utf8_encode(str_replace("\\n", "\n", $oErro->getMessage()));
      $oRetorno->status  = 0;
      $oRetorno->erro    = true;

      db_fim_transacao(true);
    }
    break;

  case 'pesquisar':

    // Parâmetros ISSQN Retido
    $sSqlConfVencISSQNRetido    = $oDaoConfVencISSQNRetido->sql_query_file(null, 'db_confplan.oid,*', null);
    $rsSqlConfVencISSQNRetido   = $oDaoConfVencISSQNRetido->sql_record($sSqlConfVencISSQNRetido);
    $iLinhasConfVencISSQNRetido = $oDaoConfVencISSQNRetido->numrows;

    if ($iLinhasConfVencISSQNRetido > 0) {
      array_push($oRetorno->aDados, db_utils::fieldsMemory($rsSqlConfVencISSQNRetido, 0));
    }

    // Parâmetros ISSQN Váriavel
    $sWhere                       = "q144_ano = {$iAnoUsu}";
    $sSqlConfVencISSQNVariavel    = $oDaoConfVencISSQNVariavel->sql_query_file(null, "*", null, $sWhere);
    $rsSqlConfVencISSQNVariavel   = $oDaoConfVencISSQNVariavel->sql_record($sSqlConfVencISSQNVariavel);
    $iLinhasConfVencISSQNVariavel = $oDaoConfVencISSQNVariavel->numrows;

    if ($iLinhasConfVencISSQNVariavel > 0) {
      array_push($oRetorno->aDados, db_utils::fieldsMemory($rsSqlConfVencISSQNVariavel, 0));
    }

    break;
}

echo $oJson->encode($oRetorno);