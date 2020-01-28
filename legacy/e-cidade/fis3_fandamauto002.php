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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(!isset($abas)) {
  echo "<script>location.href='fis3_fandamauto005.php?db_opcao=2'</script>";
  exit;
}

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_auto_classe.php"));
include(modification("classes/db_autotipo_classe.php"));
include(modification("classes/db_autoandam_classe.php"));
include(modification("classes/db_autoultandam_classe.php"));
include(modification("classes/db_fandam_classe.php"));
include(modification("classes/db_fandamusu_classe.php"));
include(modification("classes/db_autolocal_classe.php"));
include(modification("classes/db_autoexec_classe.php"));
include(modification("classes/db_autousu_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clrotulo       = new rotulocampo;
$clauto         = new cl_auto;
$clautotipo     = new cl_autotipo;
$clautoandam    = new cl_autoandam;
$clautoultandam = new cl_autoultandam;
$clfandam       = new cl_fandam;
$clfandamusu    = new cl_fandamusu;
$clautousu      = new cl_autousu;
$clautolocal    = new cl_autolocal;
$clautoexec     = new cl_autoexec;

$clrotulo->label("y39_codandam");
$clrotulo->label("y50_codauto");

$db_botao = false;
$auto     = 1;

echo "<script>parent.document.formaba.fiscais.disabled=true;</script>";

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar") {

  db_inicio_transacao();
  $db_opcao = 2;
  $sqlerro = false;
  $clfandam->alterar($y39_codandam);

  $erro = $clfandam->erro_msg;
  if($clfandam->erro_status == 0){
    $sqlerro = true;
  }

  db_fim_transacao();

} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result = $clfandam->sql_record($clfandam->sql_query($chavepesquisa));
  db_fieldsmemory($result, 0);

  $result = $clautoandam->sql_record($clautoandam->sql_query("", "", "*", "", " autoandam.y58_codandam = $chavepesquisa and y50_instit = ".db_getsession('DB_instit')));
  db_fieldsmemory($result, 0);

  $y16_codandam = $y58_codandam;
  $db_botao = false;
  $result = $clauto->sql_record($clauto->sql_query("", "*", null, "y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit')));

  if($clauto->numrows > 0) {

    db_fieldsmemory($result, 0);
    $result = $clautolocal->sql_record($clautolocal->sql_query($y50_codauto));
    if($clautolocal->numrows > 0) {
      db_fieldsmemory($result, 0);
    }

    $result = $clautoexec->sql_record($clautoexec->sql_query($y50_codauto));
    if($clautoexec->numrows > 0) {
      db_fieldsmemory($result,0);
    }

    $result = $clautousu->sql_record($clautousu->sql_query($y50_codauto));
    if($clautousu->numrows == 0) {

      $db_opcao = 22;
      echo "<script>alert('Não existem fiscais cadastrados para este auto de infração!');</script>";
      echo "<script>location.href='fis3_fandamauto002.php?abas=1';</script>";
      exit;
      $db_botao = false;
    }

    $db_botao = false;
  }

  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamautousu001.php?y39_codandam=$y16_codandam&y50_codauto=$y50_codauto';</script>";
  echo "<script>parent.document.formaba.fiscais.disabled=false;</script>";
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="expires" content="0">
  <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <center>
      <tr>
        <td width="100%" align="center" valign="top" bgcolor="#CCCCCC">
          <fieldset width="100%">
          <legend align="center">AUTO DE INFRAÇÃO</legend>
            <?php
              db_ancora(@$Ly50_codauto, "js_auto(true);", $db_opcao);
              db_input('y50_codauto', 20, $Iy50_codauto, true, 'text', 3, "");
            ?>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td height="230" width="100%" align="center" valign="top" bgcolor="#CCCCCC">
          <fieldset>
          <legend align="center">ANDAMENTO</legend>
          	<?php
      	      $db_opcao = 2;

              if($db_opcao == 2 && !isset($chavepesquisa)) {
          	    $db_opcao = 22;
              }
              $db_botao = true;

              include(modification("forms/db_frmfandam.php"));

              if($db_opcao == 22 && !isset($chavepesquisa)) {
                echo "<script>document.form1.pesquisar.click();</script>";
              }
          	?>
          </fieldset>
      	</td>
      </tr>
    </center>
  </table>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar") {

  if($clfandam->erro_status == "0") {

    $clfandam->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>";

    if($clfandam->erro_campo != "") {
      echo "<script> document.form1.".$clfandam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfandam->erro_campo.".focus();</script>";
    }

  } else {

    if($sqlerro == true) {
      db_msgbox($erro);
    } else {

      $clfandam->erro(true, false);
      echo "<script>parent.iframe_fiscais.location.href='fis3_fandamautousu001.php?y39_codandam=$y39_codandam&y50_codauto=$y50_codauto';</script>";
      echo "<script>parent.mo_camada('fiscais');</script>";
      echo "<script>parent.document.formaba.fiscais.disabled=false;</script>";
      echo "<script>parent.iframe_fandam.location.href='fis3_fandamauto002.php?abas=1&y50_codauto=$y50_codauto&chavepesquisa=".$y39_codandam."';</script>";
    }
  }
}

?>
<script>
function js_auto(mostra) {
  var auto = document.form1.y50_codauto.value;
  js_OpenJanelaIframe('', 'db_iframe', 'fis3_auto006.php?y50_codauto='+auto, 'Consulta', true, 0);
}
</script>