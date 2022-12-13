<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_pctipocompratribunal_classe.php");
require_once("classes/db_pccflicitapar_classe.php");
require_once("model/licitacao/LicitacaoModalidade.model.php");

db_postmemory($HTTP_POST_VARS);

$clcflicita             = new cl_cflicita;
$clpctipocompratribunal = new cl_pctipocompratribunal;

$db_opcao = 22;
$db_botao = false;
$iInstit  = db_getsession('DB_instit');

if (isset($alterar)) {

  $sqlerro = false;

  $oModalidade = new LicitacaoModalidade($l03_codigo);
  $sTipo  = strtoupper($l03_tipo);
  if (LicitacaoModalidade::possuiTipoCadastrado($l03_tipo) && $oModalidade->getSigla() <> $sTipo) {

    $sqlerro = true;
    $erro_msg = "Tipo da Licitação [".strtoupper($l03_tipo)."] já cadastrado no sistema.";

  } else {

    db_inicio_transacao();
    $clcflicita->alterar($l03_codigo);
    if ($clcflicita->erro_status == 0) {
      $sqlerro = true;
    }
    $erro_msg = $clcflicita->erro_msg;
    db_fim_transacao($sqlerro);
  }

  $db_opcao = 2;
  $db_botao = true;
} else if(isset($chavepesquisa)) {

   $db_opcao = 2;
   $db_botao = true;

   $result = $clcflicita->sql_record($clcflicita->sql_query($chavepesquisa));
   db_fieldsmemory($result, 0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_desabilitaSelecionar();" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#CCCCCC">
    <center>
      <?
        include("forms/db_frmcflicita.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($alterar)) {

  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    if ($clcflicita->erro_campo != "") {

      echo "<script> document.form1.".$clcflicita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcflicita->erro_campo.".focus();</script>";
    };
  } else {
    db_msgbox($erro_msg);
  }
}

if (isset($chavepesquisa)) {

 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.pccflicitapar.disabled=false;
         parent.document.formaba.template.disabled=false;
         parent.document.formaba.templateata.disabled=false;
         parent.document.formaba.templateminuta.disabled=false;
         parent.document.formaba.faixavalores.disabled=false;
         top.corpo.iframe_pccflicitapar.location.href='lic1_pccflicitapar001.php?l25_codcflicita=".@$l03_codigo."';
         top.corpo.iframe_template.location.href='lic1_cflicitatemplate001.php?l35_cflicita=".@$l03_codigo."';
         top.corpo.iframe_templateata.location.href='lic1_cflicitatemplateata001.php?l37_cflicita=".@$l03_codigo."';
         top.corpo.iframe_templateminuta.location.href='lic1_cflicitatemplateminuta001.php?l41_cflicita=".@$l03_codigo."';
         top.corpo.iframe_faixavalores.location.href='lic1_cflicitafaixavalor001.php?l37_cflicita=".@$l03_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('pccflicitapar');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>