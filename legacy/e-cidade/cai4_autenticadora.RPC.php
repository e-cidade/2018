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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));


$oParam               = JSON::create()->parse(str_replace("\\","",$_POST["json"]));

$oRetorno             = new stdClass();
$oRetorno->erro       = false;
$oRetorno->mensagem   = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  switch ($oParam->exec) {

    case "possuiCadastro":

      $oRetorno->possuiCadastro = false;
      $oRetorno->ip_usuario     = db_getsession("DB_ip");
      $sWhere = "k11_ipterm = '{$oRetorno->ip_usuario}' and k11_instit = ".db_getsession("DB_instit");
      $oDaoAutentica          = new cl_cfautent();
      $sSqlBuscaAutenticadora = $oDaoAutentica->sql_query_file(null, "k11_tipautent", null, $sWhere);
      $rsBuscaAutenticadora   = $oDaoAutentica->sql_record($sSqlBuscaAutenticadora);
      if ($oDaoAutentica->numrows > 0) {
        $oRetorno->possuiCadastro = true;
      }
      $oRetorno->ip_usuario = urlencode($oRetorno->ip_usuario);

      break;
  }

} catch (Exception $eErro) {

  $oRetorno->erro       = true;
  $oRetorno->mensagem   = $eErro->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);