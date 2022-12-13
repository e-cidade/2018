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
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);

if ( !isset($ed01_atividadeescolar) ) {
  $ed01_atividadeescolar = 'f';
}

$clatividaderh       = new cl_atividaderh;
$oDaoFuncaoAtividade = new cl_funcaoatividade();

$db_opcao      = 1;
$db_botao      = true;

if( isset( $incluir ) ) {

  db_inicio_transacao();

  $clatividaderh->ed01_atividadeescolar = $ed01_atividadeescolar;
  $clatividaderh->ed01_c_atualiz        = 'S';
  $clatividaderh->incluir($ed01_i_codigo);

  db_fim_transacao();
}

if( isset( $alterar ) ) {

  $db_opcao = 2;
  db_inicio_transacao();
  $clatividaderh->ed01_atividadeescolar = $ed01_atividadeescolar;
  $clatividaderh->ed01_c_atualiz        = 'S';
  $clatividaderh->alterar($ed01_i_codigo);

  db_fim_transacao();
}

if( isset( $excluir ) ) {

  db_inicio_transacao();

  $db_opcao = 3;
  $clatividaderh->excluir($ed01_i_codigo);

  db_fim_transacao();
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC">
    <?php require_once(modification("forms/db_frmatividaderh.php"));?>
  </body>
<?php db_menu(); ?>
</html>
<script>
  js_tabulacaoforms("form1","ed01_c_descr",true,1,"ed01_c_descr",true);
</script>
<?php
if( isset( $incluir ) ) {

  if( $clatividaderh->erro_status == "0" ) {

    $clatividaderh->erro(true,false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clatividaderh->erro_campo != "" ) {

      echo "<script> document.form1.".$clatividaderh->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clatividaderh->erro_campo.".focus();</script>";
    }
  } else {
    $clatividaderh->erro(true,true);
  }
}

if( isset( $alterar ) ) {

  if( $clatividaderh->erro_status == "0" ) {

    $clatividaderh->erro(true,false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clatividaderh->erro_campo != "" ) {

      echo "<script> document.form1.".$clatividaderh->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clatividaderh->erro_campo.".focus();</script>";
    }
  } else {
    $clatividaderh->erro(true,true);
  }
}

if( isset( $excluir ) ) {

  if( $clatividaderh->erro_status == "0" ) {
    $clatividaderh->erro(true,false);
  } else {
    $clatividaderh->erro(true,true);
  }
}

if( isset( $cancelar ) ) {
  echo "<script>location.href='".$clatividaderh->pagina_retorno."'</script>";
}