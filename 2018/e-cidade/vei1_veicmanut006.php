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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

$clveicmanut         = new cl_veicmanut();
$clveicmanutitem     = new cl_veicmanutitem();
$clveiculos          = new cl_veiculos();
$clveicmanutoficina  = new cl_veicmanutoficina();
$clveicmanutretirada = new cl_veicmanutretirada();

db_postmemory($_POST);

$db_opcao = 33;
$db_botao = false;
$sqlerro  = false;

if (isset($excluir)) {

  $sqlerro = false;
  db_inicio_transacao();

  if ($sqlerro == false) {

    $result_oficina = $clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null, "*", null, " ve66_veicmanut = {$ve62_codigo} "));
  	if ($clveicmanutoficina->numrows > 0) {

      $clveicmanutoficina->excluir(null, " ve66_veicmanut = {$ve62_codigo} ");
  		if ($clveicmanutoficina->erro_status == "0") {

        $erro_msg = $clveicmanutoficina->erro_msg;
  			$sqlerro  = true;
  		}
  	}
  }

  if ($sqlerro == false) {

    $result_retirada = $clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null, "*", null, " ve65_veicmanut = {$ve62_codigo} "));
  	if ($clveicmanutretirada->numrows > 0) {

      $clveicmanutretirada->excluir(null, " ve65_veicmanut = {$ve62_codigo} ");
      if ($clveicmanutretirada->erro_status == "0") {

        $erro_msg = $clveicmanutretirada->erro_msg;
  			$sqlerro  = true;
  		}
  	}
  }

  $result_veicmanutitem = $clveicmanutitem->sql_record($clveicmanutitem->sql_query(null, "ve63_codigo", null, " ve63_veicmanut = {$ve62_codigo} "));
  if ($clveicmanutitem->numrows > 0) {

    $clveicmanutitemmaterial = new cl_veicmanutitempcmater();
    for ($iItem = 0; $iItem < $clveicmanutitem->numrows; $iItem++) {

      $oItem = db_utils::fieldsMemory($result_veicmanutitem, $iItem);
      $clveicmanutitemmaterial->excluir(null, " ve64_veicmanutitem = {$oItem->ve63_codigo} ");
      if ($clveicmanutitemmaterial->erro_status == "0") {

        $erro_msg = $clveicmanutitemmaterial->erro_msg;
        $sqlerro  = true;
        break;
      }
    }

    if ($sqlerro == false) {

      $clveicmanutitem->excluir(null, " ve63_veicmanut = {$ve62_codigo} ");
      if ($clveicmanutitem->erro_status == "0") {

        $erro_msg = $clveicmanutitem->erro_msg;
        $sqlerro  = true;
      }
    }
  }

  if ($sqlerro == false) {

    $clveicmanut->excluir($ve62_codigo);
    if ($clveicmanut->erro_status == 0) {
      $sqlerro = true;
  	}
    $erro_msg = $clveicmanut->erro_msg;
  }
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;
} else if (isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $sCampos  = " *, motorista.z01_nome as descricao_motorista ";
  $result   = $clveicmanut->sql_record($clveicmanut->sql_query($chavepesquisa, $sCampos));

  if ($result != false && $clveicmanut->numrows > 0) {

    db_fieldsmemory($result, 0);
    $result_oficina = $clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null, "*", null, " ve66_veicmanut = {$chavepesquisa} "));
    if ($result_oficina != false && $clveicmanutoficina->numrows > 0) {
      db_fieldsmemory($result_oficina, 0);
    }
    $result_retirada = $clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null, "*", null, " ve65_veicmanut = {$ve62_codigo} "));
    if ($result_retirada != false && $clveicmanutretirada->numrows > 0) {
      db_fieldsmemory($result_retirada, 0);
    }
  } else {

    $erro_msg = "A Manutenção de Veículo informada é inválida.";
    $excluir  = false;
    $db_botao = false;
    $db_opcao = 33;
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
	  <?php include "forms/db_frmveicmanut.php"; ?>
  </body>
</html>
<?php
if (isset($excluir)) {
  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    if($clveicmanut->erro_campo != "") {

      echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
    };
  } else {
    db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='vei1_veicmanut003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if (isset($chavepesquisa)) {
  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.veicmanutitem.disabled=false;
         top.corpo.iframe_veicmanutitem.location.href='vei1_veicmanutitem001.php?db_opcaoal=3&ve63_veicmanut=".@$ve62_codigo."';
     ";
  if (isset($liberaaba)) {
    echo "  parent.mo_camada('veicmanutitem');";
  }
  echo"}\n
    js_db_libera();
  </script>\n
 ";
}
if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>