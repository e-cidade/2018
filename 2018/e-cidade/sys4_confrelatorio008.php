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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.marcado { 
           border-colappse:collapse;
           border-right:1px inset black;
           border-bottom:1px inset black;
           cursor:normal;
           font-family: Arial, Helvetica, sans-serif;
           font-size: 12px;
           background-color:#CCCDDD
         }
</style>
</head>
<script>
	(function(){
		parent.lOrdem = true;
	})();
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
  <table  border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td>
	 	<table>
		  <tr>
	   	    <td style="padding-top:20px;"> 
	  		  <fieldset>
	    	    <table cellspacing="0" style="border:0px inset white; width:260px;" >
			      <thead style="display:block; position:absolute; overflow:none;">
			      <tr>
 	      	       <?	  
	    	      	  db_input("CamposConfigurados" ,40,"",true,"hidden",1,"");			   
	      	       ?>
			      	<th class="table_header" width="242px"><b>Campos Disponíveis</b></th>
			        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
			      </tr>
			      </thead>
		      	  <tbody id="listaCampos" style=" height:300px;overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:16px; display:block;"  >
		     	  </tbody>
			    </table> 		
		 	  </fieldset>
		    </td>
		  <tr> 
	  	</table>
	  </td>
	  <td>
        <table>
          <tr>
      	    <td>
      	  	  <input name="incluiTodos"  type="button" value=">>" style='width:30px;' onClick="js_incluiOrdemMarcada(true);">
      	    </td>
      	  </tr>
      	  <tr>
      	    <td>
      	      <input name="inclui"   	 type="button" value=">"  style='width:30px;' onClick="js_incluiOrdemMarcada(false);">
      	    </td>
      	  </tr>
      	  <tr>
      	    <td>
      	      <input name="exclui" 	     type="button" value="<"  style='width:30px;' onClick="js_excluiOrdemMarcada(false);">
      	    </td>
      	  </tr>	
          <tr>
      	    <td>
      	  	  <input name="excluiTodos"  type="button" value="<<" style='width:30px;' onClick="js_excluiOrdemMarcada(true);">
      	    </td>
      	  </tr>      	
        </table>
      </td>
	  <td>
	 	<table>
		  <tr>
	   	    <td style="padding-top:20px;"> 
	  		  <fieldset>
	  		  <table cellspacing="0" style="border:0px inset white; width:360px;" >
		      <thead style="display:block; position:absolute; overflow:none;">
			      <tr>
			      	<th class="table_header" width="191px" ><b>Campo</b></th>
			      	<th class="table_header" width="150px" ><b>Crescente/Decrescente</b></th>
			        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
			      </tr>
			     </thead>
		      	  <tbody id="listaOrdem"  style="width:360px;height:300px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:16px; display:block;" >
		     	  </tbody>
			    </table>
		 	  </fieldset>
		    </td>
		    <td>
		      <table>
		        <tr>
		          <td>
		             <img style="cursor:hand"  src="imagens/btnSetaUp.gif" onClick="js_moveUp();">
		          </td>
		        </tr>
		        <tr>
		          <td>
  			        <input name="moveDown"  type="button" value="v" style='width:30px;' onClick="js_moveDown();">
		          </td>
		        </tr>		        
		      </table>
		    </td>
		  <tr> 
	  	</table>
	  </td>	  
	</tr>	
  </table>
</form>
</center>
</body>
</html>
<script>
	
	
	function js_carregaCamposDisponiveis(aObjCamposOrdem){
    
	  var sLinha  = "";	
	 	for ( var iInd = 0; iInd < aObjCamposOrdem.length; iInd++ ) {
		  with (aObjCamposOrdem[iInd]) {
		    var oOrdem = new js_objOrdem(iId,sNome,'asc',sAlias);
		  	sLinha += "<tr id='linhaCampo"+iId+"' class='linhagrid' >";		  	
		  	sLinha += "  <td class='linhagrid' onDblClick='js_incluiOrdem(\"linhaCampo"+iId+"\");' onClick='js_marcaLinha(\"linhaCampo"+iId+"\");'  style='text-align:left; width:242px;'>"+sAlias.urlDecode();
		  	sLinha += "    <input type='hidden' name='"+sAlias.urlDecode()+"' id='"+iId+"' value='"+Object.toJSON(oOrdem)+"'>";
		  	sLinha += "  </td>";
		  	sLinha += "</tr>";		  		
		  }	  	
	 	}
	  sLinha += "<tr id='ultimaLinhaHack'><td style='height:100%;'>&nbsp;</td></tr>";
	 	
  	$('listaCampos').innerHTML = sLinha;
  	
	}

  function js_confereCampos(aObjCamposOrdem,aObjCamposConfigurados){
    
    js_carregaCamposDisponiveis(aObjCamposOrdem);
	  
    for( var i=0; i < aObjCamposOrdem.length; i++ ){
	    for (var x=0; x < aObjCamposConfigurados.length; x++ ){
	      if ( aObjCamposOrdem[i].iId == aObjCamposConfigurados[x].iId ) {
		    	$('linhaCampo'+aObjCamposOrdem[i].iId).style.display = "none";
			    $('linhaCampo'+aObjCamposOrdem[i].iId).className 	   = "linhagrid";		       
		    }
      }    	
    }
    
    js_carregaOrdem(aObjCamposConfigurados);
      
  }
 

	function js_marcaLinha(idCampo){
	
    if ( $(idCampo).className == 'linhagrid') {
  	  $(idCampo).className = 'marcado';
 	  } else {
 	    $(idCampo).className = 'linhagrid';
    }
  	  
	}
	
	function js_incluiOrdem(idCampo){
	
    $(idCampo).style.display = "none";
    $(idCampo).className     = "linhagrid";
    
    var objCampo     = ($(idCampo.replace('linhaCampo','')).value).evalJSON();
        aObjOrdem    = new Array(objCampo);
 
    js_carregaOrdem(aObjOrdem);
    
	}
	

	function js_incluiOrdemMarcada(lTodos){
	
	  var aMarcados   = new Array(); 
	  
	  if (lTodos) {
	    var objMarcados = $('listaCampos').rows;
	  } else {
	    var objMarcados = js_getElementbyClass($('listaCampos').rows,'marcado');
	  }
  	  
	  for (var i=0; i < objMarcados.length; i++) {
	    if ( objMarcados[i].id != 'ultimaLinhaHack' && objMarcados[i].style.display == '' ) {
		  	$(objMarcados[i].id).style.display = "none";
			  $(objMarcados[i].id).className 	   = "linhagrid";
			
			  var objCampo = ($(objMarcados[i].id.replace('linhaCampo','')).value).evalJSON();
	  		aMarcados[i] = objCampo;
  		}
	  }
	  
	  if ( aMarcados.length == 0 ) {
  	 	alert("Nenhum campo selecionado");
  	 	return false;
    }
    
	  js_carregaOrdem(aMarcados);
	
	}
	

	function js_enviaOrdemInclusao(aCampos){

	  var ConsultaTipo = 'incluirOrdem';
 	  var url          = 'sys4_consultaviewRPC.php';
 	  var sQuery  	   = 'tipo='+ConsultaTipo;
 	  	  sQuery 	    += '&aObjCampos='+Object.toJSON(aCampos);   	  
 	  var oAjax        = new Ajax.Request( url, {
                                                 method: 'post', 
                                                 parameters: sQuery,
                                                 onComplete: js_retornoInclusaoOrdem
                                              } 
                                       );
	}
	
	
	function js_retornoInclusaoOrdem(oAjax){
	  parent.iframe_finalizar.lTestaOrdem = true;
	}


	function js_carregaOrdem(aObjCampos){
	
    if ($('ultimaLinha')) {
      $('listaOrdem').removeChild($('ultimaLinha'));
    }
    	
	  for( var iInd=0; iInd < aObjCampos.length; iInd++){
	  	with (aObjCampos[iInd]) {
	  	
	      var elem 		        = document.createElement("tr");
    	      elem.id         = "ordem"+iId;
    	      elem.className  = "linhagrid";
    	      
	  	  $('listaOrdem').appendChild(elem);
 	  	  var oOrdem  = new js_objOrdem(iId,sNome,sAscDesc,sAlias);
  		  var sLinha  = "<td class='linhagrid' style='text-align:left; width:191px;' onDblClick='js_excluiOrdem(\"ordem"+iId+"\");'   onClick='js_marcaLinha(\"ordem"+iId+"\");' >"+sAlias.urlDecode()+"</td> ";
					  sLinha += "<td class='linhagrid' style='width:150px;'>					 	  										    						                                               ";
					  sLinha += "  <select name='sAscDesc"+iId+"' id='sAscDesc"+iId+"' style='width:100%'  onChange='js_alteraOrdem(this)'>              ";
					  sLinha += " 	 <option value='asc' >Crescente  </option>						   											                                       ";
					  sLinha += "  	 <option value='desc'>Decrescente</option> 					   											                                         ";
					  sLinha += "  </select> 														   											                                                         ";
            sLinha += "</td>                                                                                                                   ";
			      sLinha += "<td><input type='hidden'  name='valOrdem"+iId+"' id='valOrdem"+iId+"' value='"+Object.toJSON(oOrdem)+"'></td> ";
	  	  
	  	  elem.innerHTML = sLinha;
	  	}
	  
	  }
	  
    $('listaOrdem').innerHTML += "<tr id='ultimaLinha'><td style='height:100%;'>&nbsp;</td></tr>";
	  
	  for( var iInd=0; iInd < aObjCampos.length; iInd++){
	  	$("sAscDesc"+aObjCampos[iInd].iId).value  = aObjCampos[iInd].sAscDesc;
	  }
 
    
	}

	
	
	
	function js_alteraOrdem(obj){
	  
	  var objOrdem 		    	= ( $(obj.name.replace('sAscDesc','valOrdem')).value ).evalJSON();
		    objOrdem.sAscDesc = obj.value;
		    
   	$(obj.name.replace('sAscDesc','valOrdem')).value = Object.toJSON(objOrdem);
		   		
	}


  function js_excluiOrdem(idCampo){
  
    $(idCampo.replace('ordem','linhaCampo')).style.display = "";
    $('listaOrdem').removeChild($(idCampo));
    
  }
	
	function js_excluiOrdemMarcada(lTodos){

	  var aMarcados 	 = new Array(); 
	  var aListaMarcados = new Array();
	  
	  if (lTodos) {
	    var objMarcados = $('listaOrdem').rows;
	  } else {
	    var objMarcados = js_getElementbyClass($('listaOrdem').rows,'marcado');
	  }
	  
	  if (objMarcados.length == 0) {
 	  	alert("Nenhuma ordem selecionada");
 	  	return false;
 	  }
  	  
	  for (var i=0; i < objMarcados.length; i++) {
	    if ( objMarcados[i].id != 'ultimaLinha' ) {
		    aMarcados[i]      = ($(objMarcados[i].id.replace('ordem','valOrdem')).value).evalJSON();
		    aListaMarcados[i] = objMarcados[i].id;
		    $(objMarcados[i].id.replace('ordem','linhaCampo')).style.display = "";
	    }
	  }
	  
	  for (var i=0; i < aListaMarcados.length; i++){
	  	$('listaOrdem').removeChild($(aListaMarcados[i]));
 	  }
	
	}
	
		
	function js_moveUp(){
	  
	  var objMarcados = js_getElementbyClass($('listaOrdem').rows,'marcado');
	  
	  if (objMarcados.length > 1 ) {
	    alert("Favor escolha apenas uma linha");
	    return false;
	  } else if (objMarcados.length == 0) {
	    return false;
	  }	
	  
	  
    var row    = objMarcados[0];
    var tbody  = $('listaOrdem');
    var rowId  = row.rowIndex;
    var hTable = tbody.parentNode;
    var nextId = rowId-1;
    
    if (nextId == 0) 	{
      return false;
    }
      
	  var next = hTable.rows[nextId];
    tbody.removeChild(row);
    tbody.insertBefore(row, next);
	  
	}	
	
	
	function js_moveDown(){
	
	  var objMarcados = js_getElementbyClass($('listaOrdem').rows,'marcado');	
	
	  if (objMarcados.length > 1 ) {
	    alert("Favor escolha apenas uma linha");
	    return false;
	  } else if (objMarcados.length == 0) {
	    return false;	    
	  }	
	  	
	  var row    = objMarcados[0];
    var tbody  = $('listaOrdem');
    var rowId  = row.rowIndex;
    var hTable = tbody.parentNode;
    var nextId = parseInt(rowId)+2;
      
    if (nextId > hTable.rows.length ) {
       return false;
    }
    
    var next = hTable.rows[nextId];
    tbody.removeChild(row);
    tbody.insertBefore(row, next);
	 
  }
	

	function js_enviaOrdem(){

	  var objLinhas  = $('listaOrdem').rows;  
	  var aObjLinhas = new Array(); 
	  var idOrdem	 = "";
	  
	  if ( objLinhas.length > 0 ) {
	    for (var i=0; i < objLinhas.length; i++) {
	      if ( objLinhas[i].id != 'ultimaLinha' ) {
				  idOrdem       = objLinhas[i].id.replace('ordem','valOrdem');
				  aObjLinhas[i] = ($(idOrdem).value).evalJSON();
			  }
 	    }
	  }

    js_enviaOrdemInclusao(aObjLinhas);
	
	}
	
	function js_objOrdem(iId,sNome,sAscDesc,sAlias){
	  this.iId      = iId;
	  this.sNome    = sNome.urlDecode();
	  this.sAscDesc = sAscDesc.urlDecode();
	  this.sAlias   = sAlias.urlDecode();
	}
		
		
	
</script>
