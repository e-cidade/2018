<?PHP
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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empnota_classe.php"));
require_once(modification("classes/db_empnotaord_classe.php"));
require_once(modification("classes/db_empnotaele_classe.php"));
require_once(modification("classes/db_db_usuarios_classe.php"));
require_once(modification("classes/db_matordem_classe.php"));
require_once(modification("classes/db_matordemitem_classe.php"));
require_once(modification("classes/db_matordemitement_classe.php"));
require_once(modification("classes/db_matestoque_classe.php"));
require_once(modification("classes/db_matestoqueitem_classe.php"));
require_once(modification("classes/db_matestoqueitemnota_classe.php"));
require_once(modification("classes/db_matestoqueitemoc_classe.php"));
require_once(modification("classes/db_matmater_classe.php"));
require_once(modification("classes/db_matmaterunisai_classe.php"));
require_once(modification("classes/db_transmater_classe.php"));
require_once(modification("classes/db_matestoqueini_classe.php"));
require_once(modification("classes/db_matestoqueinimei_classe.php"));
require_once(modification("classes/db_matestoqueitemunid_classe.php"));
require_once(modification("classes/db_matparam_classe.php"));
$clmatparam           = new cl_matparam;
$clusuarios           = new cl_db_usuarios;
$clempnota            = new cl_empnota;
$clempnotaord         = new cl_empnotaord;
$clempnotaele         = new cl_empnotaele;
$clmatordemitem       = new cl_matordemitem;
$clmatordemitement    = new cl_matordemitement;
$clmatordem           = new cl_matordem;
$clmatestoque         = new cl_matestoque;
$clmatestoqueitem     = new cl_matestoqueitem;
$clmatestoqueitemnota = new cl_matestoqueitemnota;
$clmatestoqueitemoc   = new cl_matestoqueitemoc;
$clmatmater           = new cl_matmater;
$clmatmaterunisai     = new cl_matmaterunisai;
$cltransmater         = new cl_transmater;
$clmatestoqueini      = new cl_matestoqueini;
$clmatestoqueinimei   = new cl_matestoqueinimei;
$clmatestoqueitemunid = new cl_matestoqueitemunid;
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();
$gravanota = false;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

/////// verifica se a ordem de compra já foi anulada
$result_or = $clmatordem->sql_record($clmatordem->sql_query_numemp($m51_codordem,"m51_codordem, m53_data ","m51_codordem"," m53_codordem is not null and m51_codordem = $m51_codordem "));

if($clmatordem->numrows > 0) {

  //echo "<br> clmatordem->numrows --> ".$clmatordem->numrows;
  echo "<script>alert('Ordem de Compra: ".$m51_codordem." Já Anulada!');</script>";
  echo "<script>location.href='mat4_entraMaterialNota002.php';</script>";

}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <style>
    .semMatmater {
      background-color: #FFFFCC;
    }
    .fracionado {
      background-color:#eeeee2;
    }
    .teste {white-space: nowrap;overflow:hidden;padding:1px}
  </style>
</head>
<body bgcolor=#CCCCCC style='margin:1em;'>

<div class="container">
  <?
  include(modification("forms/db_frmEntradaOrdemCompra.php"));
  ?>
</div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

if (isset($confirma)){
  if (strlen($erro_msg)>0){
    db_msgbox($erro_msg);
  }

  if($clmatestoque->erro_campo!=""){
    echo "<script> document.form1.".$clempnota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clempnota->erro_campo.".focus();</script>";
  }else{
    if ($gravanota=="false"){
      $erro_msg = $clmatestoqueinimei->erro_msg;
      db_msgbox($erro_msg);
    }
    $sql="delete from matordemitement";
    $result_deleta=db_query($sql);
    echo"<script>(window.CurrentWindow || parent.CurrentWindow).corpo.location.href='mat4_entraMaterialNota001.php';</script>";
  }
}
?>
</body>
</html>