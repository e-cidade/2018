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
include("classes/db_face_classe.php");
include("classes/db_iptunaogeracarnesetqua_classe.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
$clface = new cl_face;
$cliptunaogeracarnesetqua = new cl_iptunaogeracarnesetqua;
$db_opcao=1;
$db_botao=true;		
if (isset($atualizar)&&$atualizar!=""){
	$arr_dados = split("#",$chaves);
	$sqlerro=false;
	db_inicio_transacao();
	for($w=0;$w<count($arr_dados);$w++){
		$arr_info = split("-",$arr_dados[$w]);
		$face = $arr_info[0];
		$setor = $arr_info[1];
		$quadra = $arr_info[2];
    if($sqlerro==false){
			$cliptunaogeracarnesetqua->j67_naogeracarne = $j67_naogeracarne;
			$cliptunaogeracarnesetqua->j67_setor = $setor;
			$cliptunaogeracarnesetqua->j67_quadra = $quadra;
		  $cliptunaogeracarnesetqua->incluir(null);
			$erro_msg = $cliptunaogeracarnesetqua->erro_msg;
			if($cliptunaogeracarnesetqua->erro_status==0){
			  $sqlerro=true;
			}
    }
	}
	db_fim_transacao($sqlerro);
	if ($sqlerro==true){
		db_msgbox("Operação cancelada!!");
	}else{
		db_msgbox("Operação efetuada com sucesso!!");
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_atualizar(){
  js_gera_chaves();
//  document.form1.chaves.value = '1-2-3';
  document.form1.atualizar.value="ok";
  document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<!--
<style>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
</style>
-->
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <br>
  <br>
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">     
    <center>
    <table >
    <tr>
    <td>
    <? 
           db_input("j67_naogeracarne",10,"",true,"hidden",3);
           db_input("atualizar",10,"",true,"hidden",3);
//           db_input("chaves",10,"",true,"hidden",3);
    	     $cliframe_seleciona->campos  = "j37_face,j37_setor,j37_quadra";
           $cliframe_seleciona->legenda="Faces de Quadra";            
           $cliframe_seleciona->sql=$clface->sql_query(null,"*","j37_face","");                   
           $cliframe_seleciona->iframe_nome ="faces"; 
           $cliframe_seleciona->chaves = "j37_face,j37_setor,j37_quadra";
           $cliframe_seleciona->iframe_seleciona(1);
           
    ?>
    </td>
    </tr>
    </table>
    </center>
    </td>
  </tr>
</table>
</form>
<?
?>
</body>
</html>
<?
if (isset ($atualizar)&&$atualizar!="") {
	if ($sqlerro == false) {
		echo "<script>parent.js_conclui();</script>";
	} else {
		db_msgbox($erro_msg);
	}
}
?>