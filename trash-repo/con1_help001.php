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
include("classes/db_db_cadhelp_classe.php");
include("classes/db_db_itenshelp_classe.php");
include("classes/db_db_tipohelp_classe.php");
include("classes/db_db_modulos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_help_retorna(){

  retorna = document.form1.helps_acessados_r.value;
	item_retorna = retorna.split("-");
	if(item_retorna.length==2){
		document.form1.itens_retorna.disabled = true;
		return;
	}else{
		document.form1.itens_retorna.disabled = false;
	}
	
  document.form1.helps_acessados_a.value += "-"+item_retorna[item_retorna.length-1];

  //alert(item_retorna[item_retorna.length-2].substr(0));

  if( item_retorna[item_retorna.length-2].substr(0,1) == "I"){
    mostrahelp.location.href='con1_help002.php?id_help='+item_retorna[item_retorna.length-2].substr(1);
  }else{
    mostrahelp.location.href='con1_help002.php?proced_help='+item_retorna[item_retorna.length-2].substr(1);
  }
  document.form1.helps_acessados_r.value = "";
  for(i=0;i<item_retorna.length-1;i++){
		if(item_retorna[i]!="")
      document.form1.helps_acessados_r.value += "-"+item_retorna[i];
  }
  if(document.form1.helps_acessados_r.value == ""){
    document.form1.helps_acessados_r.value += "-"+item_retorna[1];
		document.form1.helps_acessados_r.disabled = true;
	}else{
		document.form1.itens_avanca.disabled = false;
	}
	

}
function js_help_avanca(){
  avanca = document.form1.helps_acessados_a.value;
	item_avanca = avanca.split("-");
	
  document.form1.helps_acessados_r.value += "-"+item_avanca[1];
  document.form1.itens_retorna.disabled = false;

  //alert(item_avanca[1].substr(0));

  if( item_avanca[1].substr(0,1) == "I"){
    mostrahelp.location.href='con1_help002.php?id_help='+item_avanca[1].substr(1);
  }else{
    mostrahelp.location.href='con1_help002.php?proced_help='+item_avanca[1].substr(1);
  }
  document.form1.helps_acessados_a.value = "";
  for(i=2;i<item_avanca.length;i++){
		if(item_avanca[i]!="")
      document.form1.helps_acessados_a.value += "-"+item_avanca[i];
  }
  if(document.form1.helps_acessados_a.value == ""){
		document.form1.itens_avanca.disabled = true;
	}
	

}

</script>



</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" action"" method="POST">
<table width="100%" height='98%' border="1">
<!--
<tr>
<td colspan="2">
   <?
   /*
   $cldb_tipohelp = new cl_db_tipohelp;
   $result_tipo = $cldb_tipohelp->sql_record($cldb_tipohelp->sql_query());
   if($result_tipo!=false && $cldb_tipohelp->numrows>0){
     for($i=0;$i<$cldb_tipohelp->numrows;$i++){
       db_fieldsmemory($result_tipo,$i);
       echo "<a href='' onclick='return false;' >$descrtipohelp</a>&nbsp&nbsp&nbsp&nbsp";
     }
   }
   */
   ?>
</td>
</tr>
-->
<tr>
<td width="20%" valign="top">

<?
/*
Módulo:
$nome_modulo = $modulo;
$cldb_modulos = new cl_db_modulos;
$result_modulo = $cldb_modulos->sql_record($cldb_modulos->sql_query('','*','nome_modulo'));
if($result_modulo!=false && $cldb_modulos->numrows>0){
  db_selectrecord('nome_modulo',$result_modulo,true,2,'','','','',"location.href='con1_help001.php?item='+document.form1.nome_modulo.value+'&modulo='+document.form1.nome_modulo.value",1);
}

global $todosmodulos;
$todosmodulos=' Fechar ';
db_input('todosmodulos',10,0,true,'button',2,'onclick="parent.db_janelaHelp_OnLine.hide();"');


// verifica se tem item, senao pesquisa por modulo
//echo "Programa: ".$pagina."  -  item : ".$item."  modulo: ".$modulo;
$cldb_itenshelp = new cl_db_itenshelp;
$cldb_cadhelp = new cl_db_cadhelp;
if($item!=""){
  $qitem = $item;    
}else{
  $qitem = $modulo;
}
$result = $cldb_itenshelp->sql_record($cldb_itenshelp->sql_query($qitem,null,'db_itenshelp.id_help'));
if($result!=false&&$cldb_itenshelp->numrows>0){
  db_fieldsmemory($result,0);
  $result = $cldb_cadhelp->sql_record($cldb_cadhelp->sql_query($id_help,'dhelp_resum'));
  if($result!=false&&$cldb_cadhelp->numrows>0){
    db_fieldsmemory($result,0);
    if(isset($DB_SELLER)){
      echo "&nbsp&nbsp<input size='5' type='button' name='opcao' value='Alteracao' onclick=\"js_OpenJanelaIframe('','cadastra_help_iframe','con1_db_cadhelp002.php?modulo_help=".$modulo."&automatico=".$qitem."','Alterar',1,50,50,940)\">";
    }
  }
}else{
  if(isset($DB_SELLER)){
    echo "&nbsp&nbsp<input size='5' type='button' name='opcao' value='Incluso' onclick=\"js_OpenJanelaIframe('','cadastra_help_iframe','con1_db_cadhelp001.php?modulo_help=".$modulo."&automatico=".$qitem."','Incluir',1,50,50,940)\">";
  }
  $dhelp_resum = "";
  $id_help = 0;
}

*/

?>
<iframe frameborder="0" name="mostrahelpmenu" height="100%" width="280" src="con1_help003.php?modulo=<?=$modulo?>&item=<?=$item?>"></iframe>
</td>
<td width="80%" valign="top">
  <table  height="100%">
  <tr>
  <td width='100%'>
  <!--a href='' onclick='document.getElementById("mostrahelp").src="con1_help002.php?id_help=<?=$id_help?>";return false;' >Descrição do Help</a>: &nbsp <?=$dhelp_resum?></strong--!> 
  <!--a href='' onclick='document.getElementById("mostrahelp").src="con1_help002.php?tipo_chamada=logs&id_help=<?=$id_help?>";return false;'  name='logs' >Consulta Logs</a--!> 
	<!--<input name="helps_acessados_r" value="-I<?=$id_help?>" type="hidden"><input name="itens_retorna" onclick="js_help_retorna()" type="button" value="Retornar" disabled id="itens_retorna">--!>
	<!--<input name="helps_acessados_a" value="" type="hidden">               <input name="itens_avanca" onclick="js_help_avanca()" type="button" value="Avançar" disabled id="itens_avanca">--!>
  </td>
  <tr>
  <tr><td>
  <iframe frameborder="0" name="mostrahelp" id="mostrahelp" height="100%" width="920" src="con1_help002.php?"></iframe>
  </td></tr>
  </table>
</td>
</tr>
</table>
</form>
</body>
</html>