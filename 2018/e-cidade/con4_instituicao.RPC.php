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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJsonSemEscape(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$iInstituicaoSessao = db_getsession("DB_instit");
switch($oParam->exec) {

  case "getInstituicoes":

    try {

      $oUsuarioSistema = new UsuarioSistema(db_getsession("DB_id_usuario"));
      $aInstituicoes   = $oUsuarioSistema->getInstituicoes();

      $oRetorno->aInstituicoes = array();
      foreach ($aInstituicoes as $oInstituicao) {

        $lSelecionaInstituicao = false;
        if ($oInstituicao->getCodigo() == $iInstituicaoSessao) {
          $lSelecionaInstituicao = true;
        }

        $oStdInstituicao = new stdClass();
        $oStdInstituicao->iCodigo        = $oInstituicao->getCodigo();
        $oStdInstituicao->sNomeCompleto  = urlencode($oInstituicao->getDescricao());
        $oStdInstituicao->sNomeAbreviado = urlencode($oInstituicao->getDescricaoAbreviada());
        $oStdInstituicao->lSelecionado   = $lSelecionaInstituicao;
        $oRetorno->aInstituicoes[]       = $oStdInstituicao;
      }

    } catch (Exception $eErro) {

      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
    }

    break;

}
echo $oJson->encode($oRetorno);