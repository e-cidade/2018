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
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_inserevalores(){
  x = document.form1;
  y = parent.document.form1;
	if(x.linhas.value.length <= 40){
    if(x.cabecalho.length > 0 && x.nrolin.value != ""){
      for(i=0;i<x.cabecalho.length;i++){
        if(x.nrolin.value == x.cabecalho.options[i].value){
          break;
        }
      }
      linha = i;
    }else{
      x.nrolin.value = x.cabecalho.length + 1;
      if(x.nrolin.value == ""){
	      for(i=0;i<x.cabecalho.length;i++){
	        if(x.cabecalho.options[i].text == 'Descrição em branco' || x.cabecalho.options[i].text == 'Linha em branco'){
	          break;
	        }
	      }
      }else{
        i = x.cabecalho.length;
      }
      linha = i;
    }

    if(x.linhas.value == ""){
      texto = "Linha em branco";
      if(linha == 0){
        texto = "Descrição em branco";
      }
      x.linhas.value = texto;
    }

    x.cabecalho.options[linha] = new Option(x.linhas.value,x.nrolin.value);
    x.nrolin.value = "";
		x.linhas.value = "";
	}else{
	  alert('Informe no máximo 40 caracteres!');
	  x.linhas.select();
	}
}
function js_lancarlinha(){
  x = document.form1;
  y = parent.document.form1;

  js_inserevalores();

  contador = 0;
  descrica = new Number(x.cabecalho.length);

  for(i=0;i<x.cabecalho.length;i++){
    if(x.cabecalho.options[i].text == 'Descrição em branco' || x.cabecalho.options[i].text == 'Linha em branco'){
      if(contador == 0){
        descrica = new Number(i);
      }
      contador ++;
    }
  }

	if(contador == 0){
	  document.form1.linhas.value = 'Altere alguma linha';
	  document.getElementById('label').innerHTML = '<b>Altere alguma linha</b>';
		x.linhas.style.backgroundColor = "#DEB887";
		x.linhas.style.color = "black";
		x.linhas.disabled = true;
		x.lancar.disabled = true;
	}else{
		document.form1.linhas.value = '';
		x.linhas.style.backgroundColor="";
		x.linhas.disabled = false;
		x.lancar.disabled = false;
	}

	if(descrica == 0){
	  document.getElementById('label').innerHTML = '<b>Descrição do relatório</b>';
	}else if(contador > 0){
    document.getElementById('label').innerHTML = '<b>Complemento '+descrica+'</b>';
	}
  x.nrolin.value = (descrica + 1);

  x.linhas.focus();
  js_trocacordeselect();
}
function js_retornalinha(){
  indice = document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].value;
  document.form1.nrolin.value = document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].value;
  if(document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].text != 'Descrição em branco' && document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].text != 'Linha em branco'){
    document.form1.linhas.value = document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].text;
  }else{
    document.form1.linhas.value = '';
  }
  if(indice == 1){
    document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].text = 'Descrição em branco';
    document.getElementById('label').innerHTML = '<b>Descrição do relatório</b>';
  }else{
    document.form1.cabecalho.options[document.form1.cabecalho.selectedIndex].text = 'Linha em branco';
  	indice = new Number(indice);
  	indice-= 1;
    document.getElementById('label').innerHTML = '<b>Complemento '+indice+'</b>';
  }
	document.form1.linhas.style.backgroundColor="";
	document.form1.linhas.disabled = false;
	document.form1.lancar.disabled = false;
  document.form1.linhas.focus();
  document.form1.linhas.select();
  js_trocacordeselect();
}
function js_atualizacampos(){
  x = document.form1;
	y = parent.document.form1;

  for(iy=0; iy<7; iy++){
    if(iy == 0){
    	x.linhas.value = y.campo_camporecb_cabecal.value;
    }else{
    	x.linhas.value = eval("y.campo_camporecb_comple"+iy+".value");
    }
	  x.nrolin.value = iy+1;
	  js_lancarlinha();
  }

}
function js_enviarvalor(){
  x = document.form1;
	y = parent.document.form1;
	for(i=0; i<x.cabecalho.length; i++){
	  texto = "";
	  if(x.cabecalho.options[i].text != 'Descrição em branco' && x.cabecalho.options[i].text != 'Linha em branco'){
	    texto = x.cabecalho.options[i].text;
	  }
    if(i == 0){
	    eval("y.campo_camporecb_cabecal.value = '"+texto+"'");
    }else{
	    eval("y.campo_camporecb_comple"+i+".value = '"+texto+"'");
    }
	}
	parent.db_iframe_cabecalho.hide();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="">
  <tr>
    <td valign='center' nowrap>
      <p>
        <fieldset>
	        <legend align='left' id='label'>
	          <b>Descrição do relatório</b>
			    </legend>
			    <table width='100%'>
            <tr>
			        <td align='left' nowrap>
			        <?
              db_input("linhas", 47, '', true, 'text', 1);
              db_input("nrolin", 10, '', true, 'hidden', 3);
              ?>
              <input type='button' name='lancar' value='Lançar' onClick='js_lancarlinha();'>
			        </td>
            </tr>
			    </table>
        </fieldset>
        <fieldset>
	        <legend align='left'>
	          <b>Dados do cabeçalho</b>
			    </legend>
			    <table width='100%'>
            <tr>
			        <td align='center' nowrap>
							  <?
							  $arr_linhas = Array();
                db_selectmultiple("cabecalho", $arr_linhas, 7, 1,'','','','','', " style='width:370px;' onDblClick='js_retornalinha();' ");
							  ?>
							  <BR><b>Dê dois clicks sobre a linha para alterá-la ou excluí-la</b>
			        </td>
            </tr>
			    </table>
        </fieldset>
      </p>
    </td>
  </tr>
  <tr>
    <td align='center'>
			<input name="Enviar" type="button" id="enviar" value="Enviar" onclick="js_enviarvalor();"> 
			<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cabecalho.hide();">
    </td>
  </tr>
  </form>
</table>
</center>
</body>
</html>
<script>
js_atualizacampos();
</script>