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
require_once(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clacervo     = new cl_acervo;
$clbiblioteca = new cl_biblioteca;
$db_opcao  = 22;
$db_opcao1 = 3;
$db_botao  = false;
$depto     = db_getsession("DB_coddepto");

$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));
if ($clbiblioteca->numrows != 0) {

  db_fieldsmemory($result,0);
  $bi06_biblioteca = $bi17_codigo;
}
if (isset($alterar)) {

  $db_opcao = 2;
  db_inicio_transacao();

  if (!empty($bi06_colecaoacervo)) {
    $clacervo->bi06_colecaoacervo = $bi06_colecaoacervo;
  }
  $clacervo->alterar($bi06_seq);
  db_fim_transacao();
  $db_botao = true;
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result = $clacervo->sql_record($clacervo->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
  ?>
  <script>
   parent.document.formaba.acervo2.disabled = false;
   parent.document.formaba.acervo3.disabled = false;
   parent.document.formaba.acervo4.disabled = false;
   parent.document.formaba.acervo5.disabled = false;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo2.location.href='bib1_autoracervo001.php?bi21_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo3.location.href='bib1_assunto001.php?bi15_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo4.location.href='bib1_exemplar001.php?bi23_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
  </script>
  <?php
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container">
   <fieldset style="width:93%"><legend><b>Alteração de Acervo</b></legend>
    <?php include(modification("forms/db_frmacervo.php"));?>
   </fieldset>
</div>
<script>
js_tabulacaoforms("form1","bi06_titulo",true,1,"bi06_titulo",true);
</script>
</body>
</html>
<?php
if (isset($alterar)) {

  if ($clacervo->erro_status=="0") {

    $clacervo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if ($clacervo->erro_campo!="") {

      echo "<script> document.form1.".$clacervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clacervo->erro_campo.".focus();</script>";
    };
  } else {
    ?>
    <script>
     parent.document.formaba.acervo2.disabled = false;
     parent.document.formaba.acervo3.disabled = false;
     parent.document.formaba.acervo4.disabled = false;
     parent.document.formaba.acervo5.disabled = false;
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo2.location.href='bib1_autoracervo001.php?bi21_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo3.location.href='bib1_assunto001.php?bi15_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo4.location.href='bib1_exemplar001.php?bi23_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acervo5.location.href='bib1_localacervo001.php?bi20_acervo=<?=$bi06_seq?>&bi06_titulo=<?=$bi06_titulo?>';
    </script>
    <?php
    $clacervo->erro(true,false);
  };
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>