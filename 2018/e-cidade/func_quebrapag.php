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
include("classes/db_db_syscampo_classe.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_syscampo = new cl_db_syscampo;
$clrotulo = new rotulocampo;
if(isset($selecionados)){
  if(isset($quebras) && $quebras != ""){
    $arr1 = split(",",$selecionados);
    $arr2 = split(",",$quebras);
    for($i=0; $i<count($arr1); $i++){
      if(in_array($arr1[$i],$arr2)){
        array_splice($arr1,$i,1);
        $i--;
      }
    }
    $selecionados = implode(",",$arr1);
  }
  $result_campo = $cldb_syscampo->sql_record($cldb_syscampo->sql_query(null,"codcam, nomecam, conteudo, rotulo, descricao",""," codcam in (".$selecionados.")"));
}
if(isset($quebras) && $quebras != ""){
  $result_quebras = $cldb_syscampo->sql_record($cldb_syscampo->sql_query(null,"codcam, nomecam, conteudo, rotulo, descricao",""," codcam in (".$quebras.")"));
}
if(isset($totaliz) && $totaliz != ""){
  $arr_totalizacao = split(",",$totaliz);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_enviarvalor(){
  x = document.form1;
  if(x.objetosel2.length > 8){
  	  alert("Informe no máximo oito (8) quebras.");
  }else{
	  valor = "";
	  virgu = "";
	  for(i=0; i<x.objetosel2.length; i++){
	    valor += virgu+x.objetosel2.options[i].value;
	    virgu = ",";
	  }
	  parent.document.form1.campo_camporecb_qbrapor.value = valor;

	  virgu = "";
	  parent.document.form1.campo_camporecb_totaliz.value = "";
	  if(x.chk01.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk01.value;
	    virgu = ",";
	  }

	  if(x.chk11.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk11.value;
	    virgu = ",";
	  }
	  if(x.chk21.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk21.value;
	    virgu = ",";
	  }
	  if(x.chk31.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk31.value;
	    virgu = ",";
	  }
	  if(x.chk41.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk41.value;
	    virgu = ",";
	  }
	  if(x.chk51.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk51.value;
	    virgu = ",";
	  }
	  if(x.chk61.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk61.value;
	    virgu = ",";
	  }
	  if(x.chk71.checked == true){
	    parent.document.form1.campo_camporecb_totaliz.value+= virgu+x.chk71.value;
	    virgu = ",";
	  }

    if(parent.document.form1.campo_camporecb_totaliz.value == ""){
      parent.document.form1.qbrapag.disabled = true;
      parent.document.form1.qbrapag.checked  = false;
      parent.document.form1.qbratod.disabled = true;
      parent.document.form1.qbratod.checked  = false;
    }else{
      parent.document.form1.qbrapag.disabled = false;
      parent.document.form1.qbratod.disabled = false;
    }
	  parent.db_iframe_quebrapag.hide();
  }
}
function js_buscarvalores(){
  virgu  = "";
  valor  = "";
  /*
  valor  = parent.document.form1.campo_auxilio_nselecion.value;
  if(valor != ""){
    virgu = ",";
  }
  */
  parent.js_submita(false);
  if(parent.document.form1.campo_auxilio_sselecion.value != ""){
    valor += virgu + parent.document.form1.campo_auxilio_sselecion.value;
  }
 
  document.form1.selecionados.value = valor;
  document.form1.quebras.value = parent.document.form1.campo_camporecb_qbrapor.value;
  document.form1.totaliz.value = parent.document.form1.campo_camporecb_totaliz.value;
  document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="">
  <tr>
    <td> 
   <?
    db_input("selecionados",30,0,true,'hidden',1);
    db_input("quebras",30,0,true,'hidden',1);
    db_input("totaliz",30,0,true,'hidden',1);
    db_multiploselect("codcam","rotulo", "objetosel1", "objetosel2", @$result_campo, @$result_quebras, 20, 300, "Campos a selecionar", "Campos selecionados", false, "js_mostradiv();");
   ?>
    </td>
  </tr>
  <tr>
    <td align='center'>
			<input name="Enviar" type="button" id="enviar" value="Enviar" onclick="js_enviarvalor();"> 
			<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_quebrapag.hide();">
    </td>
  </tr>
  <tr>
    <td>
      <p>
      <div id='listaquebras' style="width:96%; height:30px; visibility:hidden;">
        <fieldset>
	        <legend align='left'>
	          <b>Totalizações por</b>
			    </legend>
			    <table width='100%'>
			      <tr>
			        <div id='dv0' style="visibility:hidden;">
				        <td id='td01' align='center' width='5%'>
				          <input type='checkbox' name='chk01' value='chk01' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td02' align='left' width='45%'>
				        </td>
			        </div>
			        <div id='dv1' style="visibility:hidden;">
				        <td id='td11' align='center' width='5%'>
				          <input type='checkbox' name='chk11' value='chk11' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td12' align='left' width='45%'>
				        </td>
			        </div>
			      </tr>
			      <tr>
			        <div id='dv2' style="visibility:hidden;">
				        <td id='td21' align='center' width='5%'>
				          <input type='checkbox' name='chk21' value='chk21' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td22' align='left' width='45%'>
				        </td>
			        </div>
			        <div id='dv3' style="visibility:hidden;">	
				        <td id='td31' align='center' width='5%'>
				          <input type='checkbox' name='chk31' value='chk31' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td32' align='left' width='45%'>
				        </td>
			        </div>
			      </tr>
			      <tr>
			        <div id='dv4' style="visibility:hidden;">
				        <td id='td41' align='center' width='5%'>
				          <input type='checkbox' name='chk41' value='chk41' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td42' align='left' width='45%'>
				        </td>
			        </div>
			        <div id='dv5' style="visibility:hidden;">
				        <td id='td51' align='center' width='5%'>
				          <input type='checkbox' name='chk51' value='chk51' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td52' align='left' width='45%'>
				        </td>
			        </div>
			      </tr>
			      <tr>
			        <div id='dv6' style="visibility:hidden;">
				        <td id='td61' align='center' width='5%'>
				          <input type='checkbox' name='chk61' value='chk61' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td62' align='left' width='45%'>
				        </td>
			        </div>
			        <div id='dv7' style="visibility:hidden;">
				        <td id='td71' align='center' width='5%'>
				          <input type='checkbox' name='chk71' value='chk71' style="visibility:hidden;" onclick='js_atualizaARR();'>
				        </td>
				        <td id='td72' align='left' width='45%'>
				        </td>
			        </div>
			      </tr>
			    </table>
        </fieldset>
      </div>
      </p>
    </td>
  </tr>
  </form>
</table>
</center>
</body>
</html>
<script>
var recebelistagem = "";
function js_atualizaARR(){
	y = document.form1;
  recebelistagem = "";
	recebevirgulas = "";
	for(i=0;i<8;i++){
	  check = eval("y.chk"+i+"1.checked");
	  valor = eval("y.chk"+i+"1.value");
	  visiv = eval("y.chk"+i+"1.style.visibility");
	  if(check == true && visiv == "visible"){
	    recebelistagem += recebevirgulas + valor;
	    recebevirgulas  = ",";
	  }
  }
}
function js_checkarcampos(){
	x = document.form1.objetosel2;
	y = document.form1;
	if(x.length > 0){
    for(i=0;i<x.length;i++){
      if(i<8){
	      value = x.options[i].value;
	      <?
	      if(isset($arr_totalizacao) && count($arr_totalizacao) > 0){
	      	for($i=0; $i<count($arr_totalizacao); $i++){
	      		echo "
                  if(value == '".$arr_totalizacao[$i]."'){\n
                    eval('y.chk'+i+'1.checked = true');\n
                  }\n
                 ";
	      	}
	      }
	      ?>
      }
    }
	}
	js_atualizaARR();
}
function js_mostradiv(){
	x = document.form1.objetosel2;
	y = document.form1;
  js_zeralista(true,true,true);
	if(x.length > 0){
		if(recebelistagem == ""){
			js_checkarcampos();
		}
    document.getElementById('listaquebras').style.visibility = 'visible';
    for(i=0;i<x.length;i++){
      if(i<8){
	      value = x.options[i].value;
	      texto = x.options[i].text;
	      eval("y.chk"+i+"1.value = '"+value+"'");
        eval("y.chk"+i+"1.style.visibility = 'visible'");
	      eval("document.getElementById('td"+i+"2').innerHTML='<b>"+texto+"</b>'");
	      eval("document.getElementById('dv"+i+"').style.visibility = 'visible'");
	      if(recebelistagem != ""){
	      	arr = recebelistagem.split(",");
	      	for(ix=0;ix<arr.length;ix++){
	      	  if(arr[ix] == value){
	      	    eval('y.chk'+i+'1.checked = true');
	      	  }
	      	}
	      }
      }
    }
	}else{
    document.getElementById('listaquebras').style.visibility = 'hidden';
    js_zeralista(true,true,true);
	}
	js_atualizaARR();
}
function js_zeralista(tdh,val,dvh){
  for(i=0;i<8;i++){
    if(tdh == true){
      eval("document.getElementById('td"+i+"2').innerHTML=''");
      eval("y.chk"+i+"1.style.visibility = 'hidden'");
    }
    if(val == true){
      eval("y.chk"+i+"1.value = 'chk"+i+"1'");
      eval("y.chk"+i+"1.checked = false");
    }
    if(dvh == true){
      eval("document.getElementById('dv"+i+"').style.visibility = 'hidden'");
    }
  }
}
<?
if(!isset($selecionados)){
  echo "js_buscarvalores();";
}else{
	echo "js_checkarcampos();";
}
?>
js_mostradiv();
</script>