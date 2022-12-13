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
include("dbforms/db_funcoes.php");

include("classes/db_db_estruturanivel_classe.php");
include("classes/db_db_estrutura_classe.php");
db_postmemory($HTTP_POST_VARS);
$cldb_estruturanivel = new cl_db_estruturanivel;
$cldb_estrutura = new cl_db_estrutura;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();

 //rotina para trazer o próximo nível
     $result = $cldb_estruturanivel->sql_record($cldb_estruturanivel->sql_query_file($db78_codestrut,null,"max(db78_nivel)+1 as db78_nivel"));
     db_fieldsmemory($result,0);
     if($db78_nivel==''){
	  $db78_nivel=1;
     }
   //final  

  $cldb_estruturanivel->incluir($db78_codestrut,$db78_nivel);
  $erro_msg=$cldb_estruturanivel->erro_msg;
  if($cldb_estruturanivel->erro_status==0){
    $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_estruturanivel->alterar($db78_codestrut);
  $erro_msg=$cldb_estruturanivel->erro_msg;
  if($cldb_estruturanivel->erro_status==0){
    $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_estruturanivel->excluir($db78_codestrut);
  $erro_msg=$cldb_estruturanivel->erro_msg;
  if($cldb_estruturanivel->erro_status==0){
    $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}elseif(isset($opcao)){
   $result = $cldb_estruturanivel->sql_record($cldb_estruturanivel->sql_query($db78_codestrut,$db78_nivel)); 
   db_fieldsmemory($result,0);

}

//rotina para pegar o estrutural
  if(isset($db78_codestrut)){ 
    $result = $cldb_estrutura->sql_record($cldb_estrutura->sql_query($db78_codestrut)); 
    db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_estruturanivel.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($cldb_estruturanivel->erro_campo!=""){
      echo "<script> document.form1.".$cldb_estruturanivel->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_estruturanivel->erro_campo.".focus();</script>";
    };
  }  
}
?>