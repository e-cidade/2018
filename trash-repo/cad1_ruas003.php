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
include("classes/db_ruas_classe.php");
include("classes/db_logradcep_classe.php");
include("classes/db_ruascep_classe.php");
include("classes/db_ruasbairro_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_ruastipo_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clruas        = new cl_ruas;
$cllogradcep   = new cl_logradcep;
$clruascep     = new cl_ruascep;
$clruasbairro  = new cl_ruasbairro;
$clruastipo    = new cl_ruastipo;
$db_botao      = false;
$db_opcao      = 33;
$sqlerro       = false;
$db_codopcao   = 3;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  // db_inicio_transacao();
  $db_opcao = 3;
 
  if ($sqlerro==false){
    $reslogradcep = $cllogradcep->sql_record($cllogradcep->sql_query_file($j14_codigo,"","*","","j65_lograd = $j14_codigo"));
    if($cllogradcep->numrows > 0){    	
        $cllogradcep->excluir("","","j65_lograd=$j14_codigo");
        if ($cllogradcep->erro_status==0){
	          $sqlerro=true;
	          $erro_msg=$cllogradcep->erro_msg;
        }
    }  
  }
  
  if ($sqlerro==false){
     $resruascep = $clruascep->sql_record($clruascep->sql_query_file(null,null,"*",null,"j29_codigo = $j14_codigo"));
     if($clruascep->numrows > 0){
         $j29_codigo=$j14_codigo;
         $clruascep->excluir($j29_codigo);
         if ($clruascep->erro_status==0){
           	   $sqlerro=true;
	           $erro_msg=$clruascep->erro_msg;
         }
    }
  }
  
  if ($sqlerro==false){
  	$clruasbairro->excluir(null,"j16_lograd=$j14_codigo");
  	if ($clruasbairro->erro_status==0){
      $sqlerro=true;
      $erro_msg=$clruasbairro->erro_msg;
    }  	
  }    
  
  if ($sqlerro==false){
  	//debug($clruas);
  	$clruas->excluir($j14_codigo);
  	
  	//debug($clruas,true);
  	//exit;
    if ($clruas->erro_status==0){
      $sqlerro=true;      
    }
    $erro_msg=$clruas->erro_msg;
  }
  //db_fim_transacao();
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clruas->sql_record($clruas->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result_cep = $clruascep->sql_record($clruascep->sql_query_file(null,null,"*",null,"j29_codigo = $j14_codigo"));
   if ($clruascep->numrows>0){
   	db_fieldsmemory($result_cep,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmruas.php");
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
if($clruas->erro_status=="0"){
  $clruas->erro(true,false);
}else{
  $clruas->erro(true,true);
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>