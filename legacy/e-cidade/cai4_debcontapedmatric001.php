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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$db_opcao=1;

// Verifica se Sistema de Agua esta em Uso

db_sel_instit(null,"db21_usasisagua");

if(isset($db21_usasisagua) && $db21_usasisagua != '') {
  $db21_usasisagua = ($db21_usasisagua=='t');
  if($db21_usasisagua==true) {
	  $j18_nomefunc = "func_aguabase.php";
    $clrotulo->label("x01_matric");
  } else {
    $j18_nomefunc = "func_iptubase.php";
    $clrotulo->label("j01_matric");
  }
} else {
  $db21_usasisagua = false;
  $j18_nomefunc = "func_iptubase.php";
  $clrotulo->label("j01_matric");
}



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submit(){
	var matric;
	matric = document.form1.<?=$db21_usasisagua?"x01_matric":"j01_matric"?>.value;
	if (matric!=""){
		document.form1.submit();
	}else{
		alert("Informe uma matricula!!");
	}
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="form1" method="post" action="cai4_debcontapedaba001.php">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0">
     <tr>   
       <td>
      <?
			 if($db21_usasisagua) {
         db_ancora($Lx01_matric," js_matri(true, '$j18_nomefunc'); ",1);
			 } else {
         db_ancora($Lj01_matric," js_matri(true, '$j18_nomefunc'); ",1);
			 }
      ?>
       </td>
       <td> 
      <?
			 if($db21_usasisagua) {
         db_input('x01_matric',5,$Ix01_matric,true,'text',1,"onchange=\"js_matri(false, '$j18_nomefunc')\"");
			 }else{
         db_input('j01_matric',5,$Ij01_matric,true,'text',1,"onchange=\"js_matri(false, '$j18_nomefunc')\"");
			 }
      db_input('z01_nome',30,0,true,'text',3,"","z01_nomematri");
      ?>
       </td>
     </tr>     
      </table>
    </fieldset>
    </td>
  </tr>
</table>
<input name="Processar" type="button" value="Processar" onClick="js_submit();" >
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_matri(mostra, nome_func){
  var matric;
	var funcao;
	
	if (nome_func == 'func_aguabase.php') {
		matric = document.form1.x01_matric.value;
	}else{
		matric = document.form1.j01_matric.value;
	}

  if(mostra==true){
		funcao = (nome_func=='func_aguabase.php')?'js_mostrax01_matri':'js_mostraj01_matri';
    js_OpenJanelaIframe('','db_iframe3',nome_func+'?funcao_js=parent.'+funcao+'|0|1','Pesquisa',true);
  }else{
		funcao = (nome_func=='func_aguabase.php')?'js_mostrax01_matri1':'js_mostraj01_matri1';
    js_OpenJanelaIframe('','db_iframe3',nome_func+'?pesquisa_chave='+matric+'&funcao_js=parent.'+funcao,'Pesquisa',false);
  }
}

function js_mostraj01_matri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe_iptubase.hide();
}

function js_mostraj01_matri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}

function js_mostrax01_matri(chave1,chave2){
  document.form1.x01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe_iptubase.hide();
}

function js_mostrax01_matri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.x01_matric.focus(); 
    document.form1.x01_matric.value = ''; 
  }
}


</script>