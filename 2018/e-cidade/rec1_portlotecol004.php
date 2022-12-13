<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oPost = db_utils::postMemory($_POST);
$db_opcao = 1;

$clrhpessoal = new cl_rhpessoal;
$clrhpessoal->rotulo->label();

$clselecao   = new cl_selecao;
$clselecao->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/libjson.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
  <form name="form1" method="post" action="">
  <table style="padding-top:15px;">
    <tr> 
      <td> 
		<fieldset>
		  <legend>
		  	<b>Funcionários</b>
		  </legend>
		  <table align="center">
		    <tr>
		      <td>
		      	<?
				  db_ancora($Lr44_selec,"js_pesquisaSelec(true);",$db_opcao);
		      	?>
		      </td>
		      <td>
		        <?
		          db_input("r44_selec",10,$Ir44_descr,true,"text",$db_opcao,"onChange='js_pesquisaSelec(false);'");
				  db_input("r44_descr",40,"",true,"text",3,"");
		        ?>
		      </td>
		    </tr>
		    <tr>
		      <td>
		      	<b>
		      	<?
				  db_ancora("Funcionário","js_pesquisaFunc(true);",$db_opcao);
		      	?>
		      	</b>
		      </td>
		      <td>
		        <?
				  db_input("rh01_regist",10,$Irh01_numcgm,true,"text",$db_opcao,"onChange='js_pesquisaFunc(false);'");
				  db_input("z01_nome",40,"",true,"text",3,"");
		        ?>
		      </td>
		    </tr>
		  </table>
		</fieldset>
      </td>
    </tr>
    <tr align="center">
      <td>
	    <input name="incluir" type="button" value="Incluir" onClick="js_consultaInclusao();">	
	  </td>
    </tr>
	<tr>
	  <td>
	  <fieldset>
	    <legend>
	      <b>Funcionários Selecionados</b>
	    </legend>
	    <div style='position:fixed;
	              border:2px outset white;
	              background-color: #CCCCCC;
	              z-index:0;
	              visibility:hidden;' id='digitarObs'>
	     <div style='padding:0px;text-align:right;border-bottom: 2px outset white;background-color: #2C7AFE;color:white'>
	       <span style='float:left'>
	          <b>Observação</b>
	       </span>
	       <img src='imagens/jan_fechar_on.gif' border='0' onclick="$('digitarObs').style.visibility='hidden';">
	     </div>
	     <div style='padding:3px ;border: 1px inset white'>         
	       <textarea id='obsAssenta' rows="10" cols="30">
	       </textarea>
	       <center>
	         <input value='Confirma' type='button' id='atualizarObs'>
	       </center>          
	    </div> 
	  </div>	    
	    <table 	cellspacing="0" style="border:2px inset white;" >
	      <tr>
	        <th class="table_header" width="80px" ><b>Matrícula</b></th>
	        <th class="table_header" width="270px"><b>Nome do Funcionário</b></th>
	        <th class="table_header" width="100px"><b>Observação</b></th>
	        <th class="table_header" width="60px" ><b>Opções</b></th>
	        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
	      </tr>  
	      <tbody id="listaFuncionarios" style=" height:200px; overflow:scroll; overflow-x:hidden; background-color:white"  >
          </tbody>
	    </table>
	  </fieldset>
	  </td> 
	</tr>
  </table>
  </form>
</center>
</body>
</html>
<script>

function js_retornaObjMatric() {
  
  var aObjFuncionarios     = $('listaFuncionarios').rows;
  var iLinhasFunctionarios = aObjFuncionarios.length;
  var aRetorno             = new Array();
  var iIndRet              = new Number();
  
  for (var iInd = 0; iInd < iLinhasFunctionarios; iInd++ ) {
    
    if (aObjFuncionarios[iInd].id != 'ultimaLinha') {
    
      var iMatric               = aObjFuncionarios[iInd].id;
      var oFuncionarios         = new Object();
          oFuncionarios.iMatric = iMatric ;
          oFuncionarios.sObs    = $('obs'+iMatric).innerHTML;
          
      aRetorno[iIndRet++] = oFuncionarios;
    }
  }

  var oRetorno          = new Object();
      oRetorno.aRetorno = aRetorno;

  //return aRetorno.toJSON();
  return Object.toJSON(oRetorno);
}

function js_consultaInclusao(){

  var F = document.form1;
  
  if (F.r44_selec.value != "") {
  
  	if ($('listaFuncionarios').rows.length == 0) {
  	  js_carregaGrid('selecao',F.r44_selec.value);
  	} else {
	  
  	  var lConfirma = confirm("Deseja incluir uma nova seleção?");
  	  
  	  if (lConfirma) {
    	  
  	    $('listaFuncionarios').innerHTML = "";
  	  	js_carregaGrid('selecao', F.r44_selec.value);
  	  }
  	}
  } else if (F.rh01_regist.value != "") {
  	js_carregaGrid('funcionario',F.rh01_regist.value);
  }
}

function js_carregaGrid(sTipo, iValor) {

  js_divCarregando('Aguarde...','msgBox');
  
  var sQuery  = 'tipo='+sTipo;
      sQuery += '&valor='+iValor;
  
  var url     = 'rec4_portlotecolRPC.php';
  var oAjax   = new Ajax.Request( url, {
                                         method: 'post', 
                                         parameters: sQuery, 
                                         onComplete: js_retornoGrid
                                       }
                                   );
}

function js_retornoGrid(oAjax){
    
	js_removeObj("msgBox");
  var sLinha  = "";
  var objFunc = eval("("+oAjax.responseText+")");

  if (objFunc.iStatus && objFunc.iStatus == 2) {
    

   	alert(objFunc.sMensagem.urlDecode());
   	return false ;
  }

	var aObjFuncionarios     = $('listaFuncionarios').rows;
	var iLinhasFunctionarios = aObjFuncionarios.length;
	if (objFunc.length == 1) {

			for (oRowFuncionario of aObjFuncionarios) {

			if (oRowFuncionario.cells[0].innerHTML ==objFunc[0].rh01_regist) {

				alert('Funcionário já lançado');
				return false;
			}
		}
	}

	if ( $('ultimaLinha') ) {
		$('listaFuncionarios').removeChild($('ultimaLinha'));
	}
  if (objFunc) {
    
  	for ( var iInd = 0; iInd < objFunc.length; iInd++ ) {
    	
		  with (objFunc[iInd]) {

		  	sLinha += "<tr id='"+rh01_regist+"' class='linhaFunc' >";		  	
		  	sLinha += "  <td  class='linhagrid' style='text-align:center;'>"+rh01_regist+"</td>";		  	
		  	sLinha += "  <td  class='linhagrid' style='text-align:left;'>"+z01_nome.urlDecode();+"</td>";
        sLinha += "  <td  class='linhagrid' nowrap> ";			  	
        
        sLinha += "    <div style='overflow:hidden;width:80px;text-align:left'>";
        sLinha += "      <span><a href='#' onclick='js_showObs("+rh01_regist+",this)'><img src='imagens/edittext.png' border='0' ></a></span>";
        sLinha += "      <span id='obs"+rh01_regist+"'></span>";
        sLinha += "    </div>";
        
        sLinha += "  </td> ";			  			  	
		  	sLinha += "  <td  class='linhagrid' > ";
		  	sLinha += "    <input type='button' name='excluir' id='excluir' value='Excluir' onClick='js_excluiLinha(\""+rh01_regist+"\");'>";		  	
		  	sLinha += "  <td>";
		  	sLinha += "</tr>";		  		
		  }	  	
  	}
  	
  	sLinha += "<tr id='ultimaLinha'><td style='height:100%;'>&nbsp;</td></tr>";
  	
  	var sSaida = $('listaFuncionarios').innerHTML;
  	
  	$('listaFuncionarios').innerHTML = "";
  	$('listaFuncionarios').innerHTML = sSaida+sLinha;
  	js_limpaFiltros();
  	js_removeObj("msgBox");
  	
  }
}

function js_showObs(iCodMov,obj) {

  var el = obj; 
  var x  = 0;
  var y  = 0;
  
	while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {
     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;
   }	  
  
  $('digitarObs').style.top     = (y-$('listaFuncionarios').scrollTop)+"px";
  $('digitarObs').style.left    = (x)+"px";
  $('digitarObs').style.visibility = 'visible';
  $('obsAssenta').value = $('obs'+iCodMov).innerHTML;
  $('obsAssenta').focus();
  $('atualizarObs').onclick = function() {
    
    $('digitarObs').style.visibility ='hidden';
    $('obs'+iCodMov).innerHTML       = $('obsAssenta').value;
    $('obsAssenta').value           = '';
  
  }
}  

function js_excluiLinha(idLinha){
	$('listaFuncionarios').removeChild($(idLinha));
}


function js_pesquisaSelec(lMostra) {
  
	if (lMostra) {
    js_OpenJanelaIframe("","db_iframe_selecao","func_selecao.php?funcao_js=parent.js_preencheSelec|r44_selec|r44_descr","Pesquisa",true);  	
	} else {
	  js_OpenJanelaIframe("","db_iframe_selecao","func_selecao.php?funcao_js=parent.js_preencheSelec1&pesquisa_chave="+document.form1.r44_selec.value,"Pesquisa",false);
	}
}

function js_preencheSelec(iChave, sChave) {
  
	document.form1.r44_selec.value = iChave;
	document.form1.r44_descr.value = sChave;
  db_iframe_selecao.hide();
}

function js_preencheSelec1(sChave, lErro) {
  
  document.form1.r44_descr.value	 = sChave;
  if(lErro){
    document.form1.r44_selec.focus();
  }
   
	db_iframe_selecao.hide();
} 


function js_pesquisaFunc(lMostra) {
  
	if (lMostra) {
    js_OpenJanelaIframe("","db_iframe_func","func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_preencheFunc|rh01_regist|z01_nome","Pesquisa",true);  	
	} else {
	  js_OpenJanelaIframe("","db_iframe_func","func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_preencheFunc1&pesquisa_chave="+document.form1.rh01_regist.value,"Pesquisa",false);
	}
}

function js_preencheFunc(iChave, sChave) {
  
	document.form1.rh01_regist.value = iChave;
	document.form1.z01_nome.value    = sChave;
  db_iframe_func.hide();
}

function js_preencheFunc1(sChave, lErro) {
  
  document.form1.z01_nome.value = sChave;
  if(lErro){
    document.form1.rh01_regist.focus();
  }
   
	db_iframe_rhpessoal.hide();
} 

function js_limpaFiltros() {
  
	document.form1.r44_selec.value   = ""; 
	document.form1.r44_descr.value   = "";
	document.form1.rh01_regist.value = ""; 
	document.form1.z01_nome.value    = ""; 
}

</script>