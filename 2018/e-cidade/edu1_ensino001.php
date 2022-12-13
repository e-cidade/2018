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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_ensino_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
db_postmemory($_POST);
$oDaoEnsino = new cl_ensino();
$db_opcao   = 1;
$db_botao   = true;

if (isset($incluir)) {

  db_inicio_transacao();

  $sCamposEnsino = " max(ed10_ordem) as ordem";
  $sSqlEnsino    = $oDaoEnsino->sql_query_file( null, $sCamposEnsino );
  $rsEnsino      = db_query( $sSqlEnsino );
  $iOrdemEnsino  = 0;
  if ( $rsEnsino && pg_num_rows( $rsEnsino ) > 0 ) {

    $oEnsino      = db_utils::fieldsMemory( $rsEnsino, 0 );
    if ( $oEnsino->ordem != '' ) {
      $iOrdemEnsino = (int)$oEnsino->ordem + 1;
    }
  }

  $oDaoEnsino->ed10_i_grauensino                = 'null';
  $oDaoEnsino->ed10_mediacaodidaticopedagogica  = $ed130_codigo;
  $oDaoEnsino->ed10_ordem                       = $iOrdemEnsino;
  $oDaoEnsino->ed10_censocursoprofiss           = $ed247_i_codigo;
  $oDaoEnsino->incluir($ed10_i_codigo);
  db_fim_transacao();

  $db_botao = false;
}

$sAcao = 'Inclusão';
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
  <body >

    <div class="container">
      <?php include(modification("forms/db_frmensino.php")); ?>
    </div>

  </body>
</html>
<script>
  js_tabulacaoforms("form1","ed10_c_descr",true,1,"ed10_c_descr",true);
</script>
<?php
if (isset($incluir)) {

  if ($oDaoEnsino->erro_status == "0") {

    $oDaoEnsino->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoEnsino->erro_campo != "") {

      echo "<script> document.form1.".$oDaoEnsino->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoEnsino->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoEnsino->erro(true,false);
    db_redireciona("edu1_ensino002.php?chavepesquisa=$oDaoEnsino->ed10_i_codigo");

  }

}
?>