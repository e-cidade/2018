<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("model/educacao/ArredondamentoNota.model.php"));

$lAcessadoEscola = db_getsession('DB_modulo') == 1100747;

db_postmemory($_POST);
$oDaoFormaAvaliacao = new cl_formaavaliacao;
$oDaoConceito       = new cl_conceito;
$db_opcao           = 22;
$db_opcao1          = 3;
$db_botao           = false;
if (isset($alterar)) {

  $db_botao  = true;
  $db_opcao  = 2;
  $db_opcao1 = 3;
  db_inicio_transacao();

  if ($ed37_c_tipo == "PARECER") {

    $oDaoFormaAvaliacao->ed37_i_menorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_maiorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_variacao    = "0";
    $oDaoFormaAvaliacao->ed37_c_minimoaprov = "0";

  } elseif ($ed37_c_tipo == "NIVEL") {

    $oDaoFormaAvaliacao->ed37_i_menorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_maiorvalor  = "0";
    $oDaoFormaAvaliacao->ed37_i_variacao    = "0";
    $oDaoFormaAvaliacao->ed37_c_minimoaprov = str_replace(", ", ".", $ed37_c_minimoaprovconc);

  } else {
    $oDaoFormaAvaliacao->ed37_c_minimoaprov = str_replace(", ", ".", $ed37_c_minimoaprovnota);
  }

  $oDaoFormaAvaliacao->ed37_i_escola = 'null';
  if ( $lAcessadoEscola ) {
    $oDaoFormaAvaliacao->ed37_i_escola = db_getsession("DB_coddepto");
  }
  $oDaoFormaAvaliacao->alterar($ed37_i_codigo);
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

  $db_opcao           = 2;
  $db_opcao1          = 3;
  $sSqlFormaAvaliacao = $oDaoFormaAvaliacao->sql_formaavaliacao($chavepesquisa);
  $rsFormaAvaliacao   = $oDaoFormaAvaliacao->sql_record($sSqlFormaAvaliacao);
  db_fieldsmemory($rsFormaAvaliacao, 0);
  $db_botao               = true;
  $ed37_c_minimoaprovconc = $ed37_c_minimoaprov;
  $ed37_c_minimoaprovnota = $ed37_c_minimoaprov;

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js,"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px">
    <div class="container">
      <?php include(modification("forms/db_frmformaavaliacao.php")); ?>
    </div>
  </body>
</html>
<?php
      db_menu();

if ($ed37_c_tipo == "NIVEL") {
  ?>
  <script>
   document.getElementById("conceito").style.visibility = "visible";
   iframe_conceitos.location.href = "edu1_conceito001.php?ed39_i_formaavaliacao=<?=$ed37_i_codigo?>"+
                                    "&ed37_c_descr=<?=$ed37_c_descr?>";
  </script>
  <?
} elseif ($ed37_c_tipo == "PARECER") {
  ?><script>document.getElementById("parecer").style.visibility = "visible";</script><?
} elseif($ed37_c_tipo == "NOTA") {

  ?>
   <script>
    document.getElementById("nota").style.visibility = "visible";
    document.form1.ed37_i_menorvalor.value = "<?=$ed37_i_menorvalor?>";
    document.form1.ed37_i_maiorvalor.value = "<?=$ed37_i_maiorvalor?>";
    document.form1.ed37_i_variacao.value   = "<?=$ed37_i_variacao?>";
    js_formataVariacao();
   </script>
  <?

}

if (isset($alterar)) {

  if ($oDaoFormaAvaliacao->erro_status == "0") {

    $oDaoFormaAvaliacao->erro(true, false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoFormaAvaliacao->erro_campo != "") {

      echo "<script> document.form1.".$oDaoFormaAvaliacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoFormaAvaliacao->erro_campo.".focus();</script>";

    }

  } else {
    $oDaoFormaAvaliacao->erro(true, true);
  }

}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>