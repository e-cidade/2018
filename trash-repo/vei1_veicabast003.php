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
include("classes/db_veiculos_classe.php");
include("classes/db_veicabast_classe.php");
include("classes/db_veicabastposto_classe.php");
include("classes/db_veicabastpostoempnota_classe.php");
include("classes/db_veicabastretirada_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveiculos = new cl_veiculos;
$clveicabast = new cl_veicabast;
$clveicabastposto = new cl_veicabastposto;
$clveicabastpostoempnota = new cl_veicabastpostoempnota;
$clveicabastretirada = new cl_veicabastretirada;

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $result_retirada=$clveicabastretirada->sql_record($clveicabastretirada->sql_query(null,"*",null,"ve73_veicabast=$ve70_codigo"));
  if ($clveicabastretirada->numrows>0){
  	$clveicabastretirada->excluir(null,"ve73_veicabast=$ve70_codigo");  	
  	if ($clveicabastretirada->erro_status=="0"){
  		$sqlerro=true;
  		$erro_msg=$clveicabastretirada->erro_msg;
  	}
  }
  $result_empnota=$clveicabastpostoempnota->sql_record($clveicabastpostoempnota->sql_query(null,"ve72_codigo",null,"ve71_veicabast=$ve70_codigo"));
  if ($clveicabastpostoempnota->numrows>0){
  	db_fieldsmemory($result_empnota,0);  	
  	$clveicabastpostoempnota->alterar($ve72_codigo);  	
  	if ($clveicabastpostoempnota->erro_status=="0"){
  		$sqlerro=true;
  		$erro_msg=$clveicabastpostoempnota->erro_msg;
  	}     
  }
  if ($sqlerro==false){  	
  	$clveicabastposto->excluir(null,"ve71_veicabast=$ve70_codigo");  	
  	if ($clveicabastposto->erro_status=="0"){
  		$sqlerro=true;
  		$erro_msg=$clveicabastposto->erro_msg;
  	}      	  	  	
  }
  if ($sqlerro==false){
  	$clveicabast->excluir($ve70_codigo);
  	$erro_msg=$clveicabast->erro_msg;
  	if ($clveicabast->erro_status=="0"){
  		$sqlerro=true;
  	}    
  }
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clveicabast->sql_record($clveicabast->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $ve70_codigo=$chavepesquisa;
   $result_posto=$clveicabastposto->sql_record($clveicabastposto->sql_query_tip(null,"*",null,"ve71_veicabast=$ve70_codigo"));
   if ($clveicabastposto->numrows>0){
  	db_fieldsmemory($result_posto,0);  	
  	if ($descrdepto!=""){
       	$posto=$descdepto;
     }
     if ($z01_nome!=""){
       	$posto=$z01_nome;
     }
  }
   $result_retirada=$clveicabastretirada->sql_record($clveicabastretirada->sql_query(null,"*",null,"ve73_veicabast=$ve70_codigo"));
  	if ($clveicabastretirada->numrows>0){
  		db_fieldsmemory($result_retirada,0);
  	}
  	$result_empnota=$clveicabastpostoempnota->sql_record($clveicabastpostoempnota->sql_query(null,"ve72_codigo",null,"ve71_veicabast=$ve70_codigo"));
  if ($clveicabastpostoempnota->numrows>0){
  	db_fieldsmemory($result_empnota,0);  	
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
	include("forms/db_frmveicabast.php");
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
  if($clveicabast->erro_status=="0"||$sqlerro==true){
    //$clveicabast->erro(true,false);
    db_msgbox($erro_msg);
  }else{
    $clveicabast->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>