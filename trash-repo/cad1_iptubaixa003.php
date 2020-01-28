<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

/**
*  ROTINA DE CANCELAMENTO DE BAIXA DE MATRICULA
*  26-02-2007
*/
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubaixa_classe.php");
include("classes/db_iptubaixaproc_classe.php");
include("classes/db_iptubase_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cliptubaixa = new cl_iptubaixa;
$cliptubaixaproc = new cl_iptubaixaproc;
$cliptubase = new cl_iptubase;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
	$sqlerro = false;
  db_inicio_transacao();
  $db_opcao = 3;
// update na iptubase setando j01_baixa como null
  $sqlUpdate = " update iptubase set j01_baixa = null where j01_matric = $j02_matric ";
  $rsUpdate  = $cliptubase->sql_record($sqlUpdate);

  // exclui da iptubaixaproc se existir o processo vinculado a baixa
  $rsIptubaixaproc = $cliptubaixaproc->sql_record($cliptubaixaproc->sql_query_file($j02_matric,"j03_matric",null,""));
  if($cliptubaixaproc->numrows > 0){ 
    $cliptubaixaproc->excluir($j02_matric);
    if($cliptubaixaproc->erro_status == 0){
	    $sqlerro = true;
      $cliptubaixa->erro_msg = $cliptubaixaproc->erro_msg;
    }
  }
  $cliptubaixa->excluir($j02_matric);
  if($cliptubaixa->erro_status == 0){
	  $sqlerro = true;
    $cliptubaixa->erro_msg = $cliptubaixa->erro_msg;
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cliptubaixa->sql_record($cliptubaixa->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $rsIptubaixaproc = $cliptubaixaproc->sql_record($cliptubaixaproc->sql_query_file($chavepesquisa,"*",null,""));
   if ($cliptubaixaproc->numrows > 0) {
     db_fieldsmemory($rsIptubaixaproc,0);
   }
   $db_botao = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmiptubaixa.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($cliptubaixa->erro_status=="0"){
    $cliptubaixa->erro(true,false);
  }else{
    $cliptubaixa->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>