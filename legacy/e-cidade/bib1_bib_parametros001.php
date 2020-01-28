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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_stdlibwebseller.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_bib_parametros_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
$clbib_parametros = new cl_bib_parametros;
$db_opcao         = 1;
$db_botao         = true;

if (isset($incluir)) {

  db_inicio_transacao();
  $clbib_parametros->incluir($bi26_codigo);
  db_fim_transacao();
}

if (isset($alterar)) {

  $db_opcao = 2;
  db_inicio_transacao();
  $clbib_parametros->alterar($bi26_codigo);
  db_fim_transacao();
}

$oDao    = new cl_biblioteca();
$sCampos = "bi26_codigo, bi17_codigo as bi26_biblioteca, bi17_nome, bi26_leitorbarra, bi26_impressora";
$sSql    = $oDao->buscaParametros(null, $sCampos, null," bi17_coddepto = ". db_getsession("DB_coddepto"));
$rs      = db_query($sSql);
if (!$rs) {
  db_redireciona("db_erros.php?pagina_retorno=bib1_bib_parametros001.php&db_erro=" . urlencode(pg_last_error()));
}

if ( pg_num_rows($rs) == 1) {

  db_fieldsmemory($rs, 0);
  if ( !empty($bi26_codigo) ) {
    $db_opcao = 2;
  }
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
  <body>
    <?php
      MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");
      include(modification("forms/db_frmbib_parametros.php"));
      db_menu();
    ?>
  </body>
</html>
<?php
if (isset($incluir)) {

  if($clbib_parametros->erro_status=="0"){

    $clbib_parametros->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clbib_parametros->erro_campo!=""){
      echo "<script> document.form1.".$clbib_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbib_parametros->erro_campo.".focus();</script>";
    }
  }else{
    $clbib_parametros->erro(true,true);
  }
}
if (isset($alterar)) {

  if($clbib_parametros->erro_status=="0"){

    $clbib_parametros->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clbib_parametros->erro_campo!=""){
      echo "<script> document.form1.".$clbib_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbib_parametros->erro_campo.".focus();</script>";
    }
  }else{
    $clbib_parametros->erro(true,true);
  }
}
