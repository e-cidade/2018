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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clzonas  = new cl_zonas;
$db_opcao = 22;
$db_botao = false;
if ( ( isset( $HTTP_POST_VARS["db_opcao"] ) && $HTTP_POST_VARS["db_opcao"] ) == "Alterar" ) {

  db_inicio_transacao();

  $db_opcao = 2;
  $clzonas->alterar($j50_zona);

  db_fim_transacao();
} else if ( isset( $chavepesquisa ) ) {

   $db_opcao = 2;
   $result   = $clzonas->sql_record($clzonas->sql_query($chavepesquisa));

   db_fieldsmemory($result,0);
   $db_botao = true;
}

?>
<html>

  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css, scripts.js, strings.js, numbers.js, prototype.js ");
    ?>
  </head>

  <body class="body-default">
    <div class="container">
      <?php require_once("forms/db_frmzonas.php"); ?>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit")); ?>
  </body>

</html>
<?php
if ( ( isset( $HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] ) == "Alterar" ) {

  if ( $clzonas->erro_status == "0" ) {

    $clzonas->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $clzonas->erro_campo != "" ) {

      echo "<script> document.form1.".$clzonas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clzonas->erro_campo.".focus();</script>";
    }
  } else {

    $clzonas->erro_msg = "Zona Fiscal alterada com sucesso!";
    $clzonas->erro(true,true);
  }
}

if ( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>