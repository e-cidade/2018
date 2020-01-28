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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clliccomissao = new cl_liccomissao;
$clliccomissaocgm = new cl_liccomissaocgm;
db_postmemory($HTTP_POST_VARS);

$db_opcao = 33;
$db_botao = false;

if (isset($excluir)) {

  db_inicio_transacao();
  $sqlerro = false;

  $oDaoAtributosMembro = new cl_liccomissaocgmcadattdinamicovalorgrupo();
  $sSqlAtributosMembro = $oDaoAtributosMembro->sql_query(null, "l15_sequencial", null, "l30_codigo = {$l30_codigo}");

  $oDaoAtributosMembro->excluir(null, "l15_sequencial in ({$sSqlAtributosMembro})");

  $clliccomissaocgm->l31_liccomissao = $l30_codigo;
  $clliccomissaocgm->excluir(null, "l31_liccomissao=$l30_codigo");

  if ($clliccomissaocgm->erro_status == 0) {
    $sqlerro = true;
  }

  $erro_msg = $clliccomissaocgm->erro_msg;
  $clliccomissao->excluir($l30_codigo);

  if ($clliccomissao->erro_status == 0) {
    $sqlerro = true;
  }

  $erro_msg = $clliccomissao->erro_msg;
  $db_opcao = 3;
  $db_botao = true;
  db_fim_transacao($sqlerro);

} else if (isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result = $clliccomissao->sql_record($clliccomissao->sql_query($chavepesquisa));

  db_fieldsmemory($result, 0);
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
<body class="body-default">

  <div class="container">
  	<?php require_once(modification("forms/db_frmliccomissao.php")) ?>
  </div>

  <?php
  if (isset($excluir)) {

    if ($sqlerro == true) {

      db_msgbox($erro_msg);
      if ($clliccomissao->erro_campo != "") {
        echo "<script> document.form1.".$clliccomissao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clliccomissao->erro_campo.".focus();</script>";
      }
    } else {
     db_msgbox($erro_msg);
      echo "
      <script>
        function js_db_tranca(){
          parent.location.href='lic1_liccomissao003.php';
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
           parent.document.formaba.liccomissaocgm.disabled=false;
           (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_liccomissaocgm.location.href='lic1_liccomissaocgm001.php?db_opcaoal=33&l31_liccomissao=".@$l30_codigo."';
       ";

    if (isset($liberaaba)) {
      echo "  parent.mo_camada('liccomissaocgm');";
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
</body>
</html>
