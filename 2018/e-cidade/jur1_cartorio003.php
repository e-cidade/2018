<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once("classes/db_cartorio_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clcartorio       = new cl_cartorio;
$oDaoProcessoForo = new cl_processoforo;

$db_botao         = false;
$db_opcao         = 33;

if ( isset($excluir) ) {

  db_inicio_transacao();

  try {

    $sSqlProcessoForo = $oDaoProcessoForo->sql_query_file(null, "*", null, " v70_cartorio = $v82_sequencial" );
    $rsProcessoForo   = $oDaoProcessoForo->sql_record( $sSqlProcessoForo );

    if ($oDaoProcessoForo->numrows != 0) {
      throw new DBException("Há processos vinculados a este cartório. Exclusão abortada!");
    }

    $db_opcao = 3;
    $clcartorio->excluir($v82_sequencial);
    db_fim_transacao();
  } catch (DBException $oErro) {

    echo "<script>alert( \"".$oErro->getMessage()."\" );</script>";
    db_fim_transacao(true);
  }
} else if ( isset($chavepesquisa) ) {

  $db_opcao = 3;
  $result   = $clcartorio->sql_record($clcartorio->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <?php
      db_app::load("scripts.js, strings.js, numbers.js, prototype.js ");
      db_app::load("estilos.css, AjaxRequest.js");
    ?>
  </head>
  <body class="body-default">
    <div class="container">
	    <?php
	      include("forms/db_frmcartorio.php");
	    ?>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
  </body>
</html>
<?php

  if ( isset($excluir) ) {

    if ( $clcartorio->erro_status == "0" ) {
      $clcartorio->erro(true,false);
    } else {
      $clcartorio->erro(true,true);
    }
  }

  if ( $db_opcao == 33 ) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
  js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>