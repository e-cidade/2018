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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_aguasitleitura_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claguasitleitura = new cl_aguasitleitura;
$db_botao = false;
$db_opcao = 33;

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $claguasitleitura->excluir($x17_codigo);
  db_fim_transacao();
} else if (isset($chavepesquisa)) {

  $db_opcao = 3;
  $sSql = $claguasitleitura->sql_query($chavepesquisa);
  $result = $claguasitleitura->sql_record($sSql);
  db_fieldsmemory($result,0);
  $db_botao = true;
}
?>

<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <link rel="stylesheet" type="text/css" href="estilos.css">
</head>

<body class="body-default">

<div class="container">
  <?php require (modification("forms/db_frmaguasitleitura.php")) ?>
</div>

<?php db_menu(); ?>

</body>
</html>

<?php
if (isset($excluir)) {

  if ($claguasitleitura->erro_status == "0") {
    $claguasitleitura->erro(true, false);
  } else {
    $claguasitleitura->erro(true, true);
  }
}

if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
