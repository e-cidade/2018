<?
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oPost  = db_utils::postMemory($_POST);
$oJson  = new services_json();

/**
 * Alteraчуo realizada por motivos de sql injection detectado pelo firewall do cliente
 */
$oPost->sql = base64_decode($oPost->sql);

$lErro = false;

if ( isset($oPost->sql) && trim($oPost->sql) != '' ) {


	db_inicio_transacao();


	$sSql = str_replace("\\","",utf8_decode($oPost->sql));

  $rsExecutaSQL = db_query($sSql);

	if ( $rsExecutaSQL ) {

		$iLinhasSQL = pg_num_rows($rsExecutaSQL);

		if ( $iLinhasSQL > 0 ) {

			$aRetornoConsulta       = pg_fetch_all($rsExecutaSQL);
		  $_SESSION['sqlGerador'] = serialize($aRetornoConsulta);


		} else {
 	    $lErro    = true;
      $sMsgErro = 'Nenhum registro encontrado!';
		}

	} else {
		$lErro    = true;
		$sMsgErro = pg_last_error();
	}


	db_fim_transacao(true);


} else {
	$lErro    = true;
	$sMsgErro = 'Nenhum SQL informado!';
}


if (!$lErro){
  $aRetorno = array("erro"=>false);
} else {
  $aRetorno = array("msg" =>urlencode($sMsgErro),
                    "erro"=>true);
}

echo $oJson->encode($aRetorno);


?>