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
include("classes/db_orcprojeto_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clorcprojeto = new cl_orcprojeto;

//-- db_opcao
$db_opcao = 1;
//-- 
$db_botao = true;
if(isset($incluir)){
   db_inicio_transacao();
   $clorcprojeto->incluir($o39_codproj);
   $cod_proj = $o39_codproj;
   db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clorcprojeto->sql_record($clorcprojeto->sql_query($chavepesquisa)); 
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
<!---
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
--->
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcprojeto.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
// db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
   if($clorcprojeto->erro_status=="0"){
       $clorcprojeto->erro(true,false);
       $db_botao=true;
       echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
       if($clorcprojeto->erro_campo!=""){
           echo "<script> document.form1.".$clorcprojeto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
           echo "<script> document.form1.".$clorcprojeto->erro_campo.".focus();</script>";
       };
   }else{
       $clorcprojeto->erro(true,false);
       echo "<script> document.form1.incluir.disabled=true;</script>  ";
       //-- libera abas
       echo "<script>
 	       // libera segunda aba
               parent.document.formaba.suplem.disabled=false;\n
               top.corpo.iframe_suplem.location.href='orc1_orcprojeto0012.php?o39_codproj=$o39_codproj';\n
               parent.mo_camada('suplem');    //envia direto para outra aba     
           </script>";

  };
};
if (isset($o39_codproj) && ($o39_codproj!="")){
        echo "<script>
 	       // libera segunda aba
               parent.document.formaba.suplem.disabled=false;\n
               top.corpo.iframe_suplem.location.href='orc1_orcprojeto0012.php?o39_codproj=$o39_codproj';\n
             </script>";
}  

?>