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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_itenserv_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_arrepaga_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clitenserv = new cl_itenserv;
$clarrepaga= new cl_arrepaga;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clitenserv->alterar($cm10_i_codigo);
  db_fim_transacao();
  if($clitenserv->numrows_alterar != 0){
   db_inicio_transacao();
   $result_arrecad=pg_query("select fc_cemitarrecad(5,$cm01_i_numpre,false,$cm10_i_sepultamento) as retorno") or die("Erro ao incluir em arrecad.");
   db_fieldmemory( $result_arrecad, 0 );
   if( substr( $retorno, 0, 1 ) != '9' ){
     db_msgbox($retorno);
   }
   db_fim_transacao();
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clitenserv->sql_record($clitenserv->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;

  $clarrepaga->sql_record($clarrepaga->sql_query_file("","k00_numpre","","k00_numpre=$cm10_i_numpre"));
  if($clarrepaga->numrows>0){
   $db_opcao=22;
   $sove="true";
   $db_botao = false;
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
     <?
     include("forms/db_frmitenserv.php");
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
if($sove=="true"){
  db_msgbox("Débito com parcela paga. Alteração não permitida.");
}

if(isset($alterar)){
  if($clitenserv->erro_status=="0"){
    $clitenserv->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clitenserv->erro_campo!=""){
      echo "<script> document.form1.".$clitenserv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitenserv->erro_campo.".focus();</script>";
    };
  }else{
    $clitenserv->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>