<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_autousu_classe.php");
require_once("classes/db_fandamusu_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clautousu   = new cl_autousu;
$clfandamusu = new cl_fandamusu;
$db_opcao    = 1;
$db_botao    = true;
global $y59_codauto;
global $y39_codandam;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  db_inicio_transacao();
  $clfandamusu->y40_obs="0";
  $clfandamusu->y40_id_usuario=$y56_id_usuario;
  $clfandamusu->y40_codandam=$y39_codandam;
  $clfandamusu->incluir($y39_codandam,$y56_id_usuario);
  $erro=$clfandamusu->erro_msg;
  if($clfandamusu->erro_status==0){
    $sqlerro = true;
  }
  $clautousu->incluir($y56_codauto,$y56_id_usuario);
  db_fim_transacao();
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
<body class="abas">
  <div class="container">
  	<?php
  	  include("forms/db_frmautousu.php");
  	?>
  </div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clautousu->erro_status=="0"){
    $clautousu->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautousu->erro_campo!=""){
      echo "<script> document.form1.".$clautousu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautousu->erro_campo.".focus();</script>";
    };
  }else{
    $clautousu->erro(true,false);
    echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=$y59_codauto&y39_codandam=$y39_codandam';</script>";
  };
};
if(isset($y59_codauto) && $y59_codauto != "" && $y39_codandam == ""){
  $clautousu->sql_record($clautousu->sql_query($y59_codauto));
  if($clautousu->numrows == 0){
    echo "<script>parent.document.formaba.fiscais.disabled=true;</script>";
  }
}
?>