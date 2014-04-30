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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matmater_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmatmater = new cl_matmater;
$clmatmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_servico");
$clrotulo->label("");
$clrotulo->label("");
$clrotulo->label("");
$clrotulo->label("");
if (isset($incluir)){
  db_inicio_transacao();
  $sqlerro=false;
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave);
      if ($dados[1]!=""){
      	  $clmatmater->m60_codmater =  $dados[1] ;	  
      	  $clmatmater->m60_ativo = '0';
      	  $clmatmater->alterar($dados[1]);
      	  $erro_msg=$clmatmater->erro_msg;
      	  if ($clmatmater->erro_status==0){
      	  	$sqlerro=true;
		
      	  	
      	  }      	  
      }
     }
    $proximo=next($vt);
  }
  db_fim_transacao($sqlerro);
  db_msgbox($erro_msg);
  if ($sqlerro==false){
  	echo "<script>parent.location.href='mat4_desatmat001.php';</script>";
  	exit;  	
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
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
function js_desabilita(){
  document.form1.incluir.disabled = true;
}

</script>
<!--
      <script>
		function js_mostradiv(liga,evt,vlr,vlr1,vlr2){
		  evt= (evt)?evt:(window.event)?window.event:""; 
		  if(liga){
		     document.getElementById('vlr').innerHTML=vlr;
		     document.getElementById('vlr1').innerHTML=vlr1;
		     document.getElementById('vlr2').innerHTML=vlr2;
		     document.getElementById('divlabel').style.left=0;
		     document.getElementById('divlabel').style.top=0;
		     document.getElementById('divlabel').style.visibility='visible';
		  }else{
		    document.getElementById('divlabel').style.visibility='hidden';
		  }  
		}
	      </script>
		<div align="left" id="divlabel" style="position:absolute; z-index:12; top:0; left:0; visibility: hidden; border: 2px outset #666666; background-color: #6699cc; font-style:italic;">
		  <table cellpadding="2" border='1'>
		    <tr nowrap>
		      <td align="center" nowrap>
		        <strong>Reduzido:</strong><span color="#9966cc" id="vlr"></span>&nbsp;&nbsp;&nbsp;<br> 
		      </td>
		      <td align="center" nowrap>
		        <strong>Desdobramento:</strong><span color="#9966cc" id="vlr1"></span><br> 
		      </td>
		      <td align="center" nowrap>
		        <strong>Descr:</strong><span color="#9966cc" id="vlr2"></span><br> 
		      </td>
		    </tr>
		  </table>  
		</div>  
		-->
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
color: black;
background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" target="" action="">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  
  <tr>
    <td colspan=2 align='center' >
  <?db_input('incluir','100','',true,'hidden',3);
      db_input('descrmater','100','',true,'hidden',3);
     $campos = "matmater.m60_codmater,matmater.m60_descr";
     $result = $clmatmater->sql_record($clmatmater->sql_query("",$campos,"m60_descr"," m60_descr like '$descrmater%'  and m60_ativo is true"));
     $numrows = $clmatmater->numrows; 
	 if($numrows>0){ 
	    echo "
	    <br><br>
	    <table>
	     <tr>
	       <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	       <td class='cabec' align='center'  >".str_replace(":","",$Lm60_codmater)."</td>
	       <td class='cabec' align='center'  >".str_replace(":","",$Lm60_descr)."</td>
	     </tr>
	    "; 	   
	 }else{
	   echo"<script>js_desabilita();</script> ";
	   echo "<br><br><b>Nenhum registro encontrado!!</b>";
	 }
	 for($i=0; $i<$numrows; $i++){
	   db_fieldsmemory($result,$i);
           echo"
	       <tr>
		  <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$m60_codmater' id='CHECK_".$m60_codmater."'></td>
           <td  class='corpo'  align='center' ><label style=\"cursor: hand\"><small>$m60_codmater</small></label></td>
          <td   class='corpo'  align='center' ><label style=\"cursor: hand\"><small>$m60_descr</small></label></td>
		   </tr>";
	   }
	  echo" </table>";	        
    ?>
  </td>
  </tr>
  </table>
  </form>
</center>
</body>
</html>
<script>
function js_verinfo(codigo){
  js_OpenJanelaIframe('top.corpo','db_iframe_altmat','com1_matmater002_iframe.php?chavepesquisa='+codigo,'Info.',true);
}

</script>