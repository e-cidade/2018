<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_libcontabilidade.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conplano_classe.php");
include("classes/db_conplanoorcamento_classe.php");
include("classes/db_conplanoorcamentogrupo_classe.php");
include("classes/db_conplanogrupo_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplano      = new cl_conplano;
$clconplanogrupo = new cl_conplanogrupo;
if (USE_PCASP) {
  $clconplanogrupo = new cl_conplanoorcamentogrupo();  
}
$db_opcao = 1;
$db_botao = true;
$anousu   = db_getsession("DB_anousu");

$sqlerro  = false;
$erro_msg = "";

if (!isset($c21_congrupo)){
  exit;
}

if (isset($novo)){
  unset($c21_sequencial);
  unset($sequencial);
  unset($c60_codcon);
  unset($c60_estrut);
  unset($c60_descr);
  unset($c60_codsis);
  unset($c52_descr);
  unset($c60_codcla);
  unset($c51_descr);
}

if (isset($opcao) && $opcao == "alterar"){
  $sequencial = $c21_sequencial;
  $db_opcao   = 2;
  $res_conplanogrupo = $clconplanogrupo->sql_record($clconplanogrupo->sql_query($c21_sequencial));
  if ($clconplanogrupo->numrows > 0){
    db_fieldsmemory($res_conplanogrupo,0);
  }
}

if (isset($opcao) && $opcao == "excluir"){
  $sequencial = $c21_sequencial;
  $db_opcao   = 3;
  $res_conplanogrupo = $clconplanogrupo->sql_record($clconplanogrupo->sql_query($c21_sequencial));
  if ($clconplanogrupo->numrows > 0){
    db_fieldsmemory($res_conplanogrupo,0);
  }
}

if (isset($incluir)){
  db_inicio_transacao();

  $clconplanogrupo->c21_codcon   = $c60_codcon;
  $clconplanogrupo->c21_anousu   = $anousu;
  $clconplanogrupo->c21_congrupo = $c21_congrupo;
  $clconplanogrupo->c21_instit   = db_getsession("DB_instit");
  
  $clconplanogrupo->incluir(null);
  $erro_msg = $clconplanogrupo->erro_msg;
  if ($clconplanogrupo->erro_status == 0){
    $sqlerro = true;
    $clconplano->erro_campo = "c60_codcon";
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    unset($c21_sequencial);
    unset($sequencial);
    unset($c60_codcon);
    unset($c60_estrut);
    unset($c60_descr);
    unset($c60_codsis);
    unset($c52_descr);
    unset($c60_codcla);
    unset($c51_descr);
  }
}

if (isset($alterar)){
  $c21_sequencial = $sequencial;
  db_inicio_transacao();

  $clconplanogrupo->c21_sequencial = $c21_sequencial;
  $clconplanogrupo->c21_codcon     = $c60_codcon;
  $clconplanogrupo->c21_anousu     = $anousu;
  $clconplanogrupo->c21_congrupo   = $c21_congrupo;
  $clconplanogrupo->c21_instit     = db_getsession("DB_instit");
  
  $clconplanogrupo->alterar($c21_sequencial);
  $erro_msg = $clconplanogrupo->erro_msg;
  if ($clconplanogrupo->erro_status == 0){
    $sqlerro = true;
    $clconplano->erro_campo = "c60_codcon";
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 2;
}

if (isset($excluir)){
  db_inicio_transacao();

  $clconplanogrupo->c21_sequencial = $sequencial;

  $clconplanogrupo->excluir($sequencial);
  $erro_msg = $clconplanogrupo->erro_msg;
  if ($clconplanogrupo->erro_status == 0){
    $sqlerro = true;
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false) {
    $db_opcao = 1;
    unset($c21_sequencial);
    unset($sequencial);
    unset($c60_codcon);
    unset($c60_estrut);
    unset($c60_descr);
    unset($c60_codsis);
    unset($c52_descr);
    unset($c60_codcla);
    unset($c51_descr);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
      include("forms/db_frmconplanogrupoconta.php");
    ?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if ($sqlerro == true) {
	db_msgbox($erro_msg);
	if ($clconplano->erro_campo != "") {
		echo "<script> document.form1.".$clconplano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
 	 	echo "<script> document.form1.".$clconplano->erro_campo.".focus();</script>";
	} else {
		db_msgbox($erro_msg);
/*      
   	echo "<script> 
             top.corpo.iframe_conta.location.href = 'con1_conplano011.php';
             top.corpo.document.formaba.grupos.style.visibility='visible';
	          top.corpo.iframe_grupos.disable='false';
             top.corpo.iframe_grupos.location.href = 'con1_congrupo004.php?c21_anousu=$anousu&c21_codcon=$c60_codcon';
             parent.mo_camada('grupos');
	        </script>";
*/            
	} 
} else if (trim($erro_msg) != ""){
		db_msgbox($erro_msg);
}
?>