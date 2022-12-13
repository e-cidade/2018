<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

define("MENSAGENS", "tributario.itbi.itb1_intermediadores.");

$oJson              = new services_json(0, true);
$oParametros        = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  switch($oParametros->sExecucao){

    case "salvarItbiIntermediadores":

      if(empty($oParametros->iItbi)){
        throw new Exception(_M(MENSAGENS."campo_obrigatorio_itbi"));
      }

      if(empty($oParametros->sNome)){
        throw new Exception(_M(MENSAGENS."campo_obrigatorio_nome"));
      }

      if(empty($oParametros->iCnpjCpf)){
        throw new Exception(_M(MENSAGENS."campo_obrigatorio_cnpj_cpf"));
      }

      if(strlen($oParametros->iCnpjCpf) != 11 and strlen($oParametros->iCnpjCpf) != 14){
        throw new Exception(_M(MENSAGENS."campo_cnpj_cpf_irregular"));
      }

      if($oRetorno->erro == false){

        $oItbiIntermediador = new cl_itbiintermediador;

        $oItbiIntermediador->it35_itbi      = $oParametros->iItbi;
        $oItbiIntermediador->it35_cgm       = $oParametros->iCgm;
        $oItbiIntermediador->it35_nome      = $oParametros->sNome;
        $oItbiIntermediador->it35_cnpj_cpf  = $oParametros->iCnpjCpf;
        $oItbiIntermediador->it35_creci     = $oParametros->sCreci;
        $oItbiIntermediador->it35_principal = $oParametros->iPrincipal;

        $sSql = $oItbiIntermediador->sql_verifica_documento_itbi($oParametros->iItbi, $oParametros->iCnpjCpf, $oParametros->iSequencial);
        $rsItbiIntermediadorDocumento = db_query($sSql);

        if(empty($rsItbiIntermediadorDocumento)){
          throw new Exception(_M(MENSAGENS."erro_salvar_intermediador"));
        }

        $aItbiIntermediadorDocumento = db_utils::getCollectionByRecord($rsItbiIntermediadorDocumento);

        if(!empty($aItbiIntermediadorDocumento)){
          throw new Exception(_M(MENSAGENS."erro_salvar_intermediador_documento"));
        }

        if($oItbiIntermediador->it35_principal == 1){
          $sSql = $oItbiIntermediador->sql_update_principal($oParametros->iItbi);
          $rsItbiIntermediadorUpdate = db_query($sSql);

          if(empty($rsItbiIntermediadorUpdate)){
            throw new Exception(_M(MENSAGENS."erro_salvar_intermediador"));
          }
        }

        if(empty($oParametros->iSequencial)){
          $oItbiIntermediador->incluir();
        }

        if(!empty($oParametros->iSequencial)){

          $oItbiIntermediador->it35_sequencial = $oParametros->iSequencial;
          $oItbiIntermediador->alterar($oParametros->iSequencial);
        }

        if($oItbiIntermediador->erro_status == "0"){
          throw new Exception(_M(MENSAGENS."erro_salvar_intermediador"));
        }

        $oRetorno->sMessage = _M(MENSAGENS."sucesso_salvar_intermediador");
      }

      break;

    case "getItbiIntermediadores":

      $oItbiIntermediador = new cl_itbiintermediador;

      $sWhere = "it35_itbi = ".$oParametros->sGuia;
      $sSqlItbiIntermediador = $oItbiIntermediador->sql_query_file(null, "*", null, $sWhere);
      $rsItbiIntermediador   = db_query($sSqlItbiIntermediador);

      if(empty($rsItbiIntermediador)){
        throw new Exception(_M(MENSAGENS."erro_carrega_intermediadores_itbi"));
      }

      $oRetorno->aItbiIntermediador = db_utils::getCollectionByRecord($rsItbiIntermediador);

      break;

    case "excluirItbiIntermediadores":

      $oItbiIntermediador = new cl_itbiintermediador;

      $sWhere = "it35_itbi = ".$oParametros->iItbi;
      $sSqlItbiIntermediador = $oItbiIntermediador->sql_query_file(null, "*", null, $sWhere);
      $rsItbiIntermediador   = db_query($sSqlItbiIntermediador);

      $aItbiIntermediadores = db_utils::getCollectionByRecord($rsItbiIntermediador);

      $oItbiIntermediador->excluir($oParametros->iSequencial);

      if($oItbiIntermediador->erro_status == "0"){
        throw new Exception(_M(MENSAGENS."erro_excluir_intermediador"));
      }

      if(count($aItbiIntermediadores) > 1){

        $sOrder = "it35_sequencial asc";
        $sWhere = "it35_itbi = ".$oParametros->iItbi;
        $sSqlItbiIntermediador = $oItbiIntermediador->sql_query_file(null, "*", $sOrder, $sWhere);
        $rsItbiIntermediador   = db_query($sSqlItbiIntermediador);

        db_fieldsmemory($rsItbiIntermediador, 0);

        $sSql = $oItbiIntermediador->sql_update_itbiintermediador_principal($it35_sequencial, "true");
        $rsItbiIntermediador = db_query($sSql);

        if($oItbiIntermediador->erro_status == "0"){
          throw new Exception(_M(MENSAGENS."erro_excluir_principal"));
        }
      }

      $oRetorno->sMessage = _M(MENSAGENS."sucesso_excluir_intermediador");
      break;

    case "getIntermediador":

      $oItbiIntermediador = new cl_itbiintermediador;

      $sSqlItbiIntermediador = $oItbiIntermediador->sql_query_file($oParametros->iSequencial, "*");
      $rsItbiIntermediador   = db_query($sSqlItbiIntermediador);

      if(empty($rsItbiIntermediador)){
        throw new Exception(_M(MENSAGENS."erro_carrega_intermediadores_itbi"));
      }

      db_fieldsmemory($rsItbiIntermediador, 0);

      $oRetorno->oIntermediador = new stdClass();
      $oRetorno->oIntermediador->iSequencial = $it35_sequencial;
      $oRetorno->oIntermediador->iItbi       = $it35_itbi;
      $oRetorno->oIntermediador->iCgm        = $it35_cgm;
      $oRetorno->oIntermediador->sNome       = $it35_nome;
      $oRetorno->oIntermediador->iCnpjCpf    = $it35_cnpj_cpf;
      $oRetorno->oIntermediador->sCreci      = $it35_creci;
      $oRetorno->oIntermediador->lPrincipal  = $it35_principal;

      break;
  }

  db_fim_transacao(false);

} catch (Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);
