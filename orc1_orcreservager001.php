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
include("classes/db_orcreserva_classe.php");
include("classes/db_orcreservager_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcdotacao_classe.php");
include("libs/db_liborcamento.php");

db_postmemory($HTTP_POST_VARS);

$clorcreserva    = new cl_orcreserva ; // tabela de reserva
$clorcreservager = new cl_orcreservager; // tabela de reserva automatica
$clorcorgao      = new cl_orcorgao;  // instancia orgãos
$clorcunidade    = new cl_orcunidade; // instancia unidades
$clorcelemento   = new cl_orcelemento; // instancia elemento
$clorcdotacao    = new cl_orcdotacao; // instancia dotação

$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_imprime(){
  jan = window.open('orc2_orcreserprev004.php?atividade='+document.form1.ativid.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}
</script>
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
<form name="form1" mthod="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
  <td colspan="2"><br>
  </td>
  </tr>
  <tr> 
  <td><strong>Atividade:</strong>
  </td>
  <td> 
  <?
  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o58_projativ,o55_descr","o58_projativ"," o58_anousu = ".db_getsession("DB_anousu")." and o58_instit = ".db_getsession("DB_instit")));
  //db_criatabela($result);exit;
  db_selectrecord("ativid",$result,true,2,"","","","0","");
  ?>
  <input name="seleciona" value="Seleciona" type="button" onclick="document.getElementById('iframe_reserva').src = 'orc1_orcreservager002.php?atividade='+document.form1.ativid.value">
  <br>
  </td>
  </tr> 
  <tr> 
    <td colspan="2" height="450" align="left" valign="top" bgcolor="#CCCCCC">
    <iframe  id="iframe_reserva" name="iframe_reserva" src="" frameborder="0" marginwidth="0" leftmargin="0" topmargin="0"   height="450" scrolling=""  width=100% > 
    </iframe>
	</td>
  </tr>
  <tr>
  <td colspan="2" align='center'>
   <input name='atualiza' type='button' value='Atualiza' onclick='js_testaperc();'>
   <input name='imprime' type='button' value='Imprime' onclick='js_imprime()'>
     </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_testaperc(){
	x = iframe_reserva.document.form1;
	percentant = "";
	for(i=0; i<x.length; i++){
	  if(x.elements[i].name.substr(0,8) == "previsao"){
	    arr = x.elements[i].name.split("_");
	    if(percentant != arr[2]){
	      percentual = 0;
	    }
	    percentual += new Number(x.elements[i].value);
	    if(percentual > 100){
	    	break;
	    }
	    percentant = arr[2];
	  }
	}

	if(percentual.toFixed(2) > 100){
	  alert("Soma dos percentuais ultrapassa 100%. Verifique.");
	}else{
	  x.submit();
	}
}
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clorcreservager->erro_status=="0"){
    $clorcreservager->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcreservager->erro_campo!=""){
      echo "<script> document.form1.".$clorcreservager->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcreservager->erro_campo.".focus();</script>";
    };
  }else{
    $clorcreservager->erro(true,true);
  };
};
?>