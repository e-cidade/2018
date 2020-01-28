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
//include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcsuplem_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcsuplemtipo_classe.php");
include("classes/db_orcsuplemrec_classe.php");
include("classes/db_orcsuplemval_classe.php");
include("classes/db_orcprojeto_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$clorcprojeto = new cl_orcprojeto;
$clorcsuplem = new cl_orcsuplem;
$clorcsuplemtipo = new cl_orcsuplemtipo;
$clorcsuplemrec  = new cl_orcsuplemrec;
$clorcsuplemval  = new cl_orcsuplemval;

$db_opcao = 2;
$db_botao = false;
if(isset($excluir)){
   db_inicio_transacao();
   $clorcsuplemrec->excluir($o46_codsup);
   $clorcsuplemval->excluir($o46_codsup);
   $clorcsuplem->excluir($o46_codsup);
   db_fim_transacao();
   //desabilita abas
   echo "<script>
      	   if (parent.document.formaba.suplem)  
	       parent.document.formaba.suplem.disabled=true;  
	   if (parent.document.formaba.reduz)
               parent.document.formaba.reduz.disabled=true;  
	   if (parent.document.formaba.receita)   
	       parent.document.formaba.receita.disabled=true;  
               
         </script>";

} else if(isset($alterar)){
   db_inicio_transacao();
   $clorcsuplem->alterar($o46_codsup);
   // update no o39_texto do projeto
   $clorcprojeto->o39_texto = $o39_texto;
   $clorcprojeto->o39_codproj = $o46_codlei;
   $clorcprojeto->alterar($o46_codlei);
   if ($clorcprojeto->erro_status == "0" ){
     db_msgbox($clorcprojeto->erro_msg);
     $erro = true;
   }  

   db_fim_transacao();

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcsuplem->sql_record($clorcsuplem->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<? include("forms/db_frmorcsuplem.php"); ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clorcsuplem->erro_status=="0"){
    $clorcsuplem->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcsuplem->erro_campo!=""){
      echo "<script> document.form1.".$clorcsuplem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcsuplem->erro_campo.".focus();</script>";
    };
  }else{
    $clorcsuplem->erro(true,false);
  };
};
if($db_opcao==22){
//  echo "<script>document.form1.pesquisar.click();</script>";
}
if (isset($excluir)){
  echo "<script> parent.location='orc4_orcsuplem002.php'; </script>";
}  
?>