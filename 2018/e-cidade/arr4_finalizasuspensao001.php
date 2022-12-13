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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_suspensaofinaliza_classe.php");
include("model/suspensaoDebitos.model.php");

$oPost = db_utils::postMemory($_POST);


$clsuspensaofinaliza = new cl_suspensaofinaliza();
$oSuspensaoDebitos	 = new suspensaoDebitos();

$db_opcao = 1;

if(isset($oPost->finalizar)){

  db_inicio_transacao();

  $lSqlErro = false;

  try {
	$oSuspensaoDebitos->finalizaSuspensao($oPost->ar19_suspensao,$oPost->ar19_obs,$oPost->statusDebito);
  } catch (Exception $eException) {
    $lSqlErro = true;
    $sMsgErro = $eException->getMessage();
  }

  db_fim_transacao($lSqlErro);

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table style="padding-top:20px;" align="center">
  <tr>
    <td>
    <center>
	  <?
	    include("forms/db_frmsuspensaofinaliza.php");
	  ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
</script>
<?
if(isset($oPost->finalizar)){

  if ( $lSqlErro ) {
	  db_msgbox($sMsgErro);
  }else{
    db_msgbox("Operação concluída com sucesso!");
    echo "<script>document.location.href='arr4_finalizasuspensao001.php';</script>";
  }
}
?>