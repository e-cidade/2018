<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("libs/db_utils.php");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; parent.lFiltros = true;" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
  <table  border="0" cellspacing="0" cellpadding="0">
  	<tr>
	    <td>
	      <table>
		      <tr>
		        <td valign="top">
		          <table>
		          	<tr>
		          	  <td>
										<table>
					 					  <tr>
							    	    <td style="padding-top:20px;"> 
									  		  <fieldset>
									    	    <table 	cellspacing="0" style="border:0px inset white; width:260px;" >
									    	    <thead style="display:block; position:absolute; overflow:none;">
												      <tr>
												      	<th class="table_header" width="242px"><b>Campos Disponíveis</b></th>
												        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
												      </tr>
												     </thead>
											      	<tbody id="listaCampos" style="height:180px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:18px; display:block;" >
											      	</tbody>
											      </table> 		
											    </fieldset>
										    </td>
										    <td style="padding-top:20px;">
										      <fieldset>
												  	<table 	cellspacing="0" style="border:2px inset white; width:230px;" >
												  	  <tr>
												      	<th class="table_header"><b>Filtros</b></th>
												      	<th class="table_header"><b>Operador</b></th>						      	
												        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
												      </tr>
											      	<tbody id="listaFiltros" style=" height:180px; overflow:scroll; overflow-x:hidden; background-color:white">
											      	  <tr  id="Igual" 	  >
											      	  	<td onClick='js_marcaLinha("Igual"      ,"listaFiltros");' class="linhagrid">Igual a </td>
											      	    <td onClick='js_marcaLinha("Igual"      ,"listaFiltros");' class="linhagrid"> = </td>
											      	  </tr>
											      	  <tr  id="Diferente" >
											      	  	<td onClick='js_marcaLinha("Diferente"  ,"listaFiltros");' class="linhagrid">Diferente de </td>
											      	  	<td onClick='js_marcaLinha("Diferente"  ,"listaFiltros");' class="linhagrid"> <> </td>
											      	  </tr>						      	  
											      	  <tr  id="Maior" 	  >
											      	  	<td onClick='js_marcaLinha("Maior"      ,"listaFiltros");' class="linhagrid">Maior que </td>
							   				      	 	<td onClick='js_marcaLinha("Maior"      ,"listaFiltros");' class="linhagrid"> > </td>
						  				      	  </tr>						      	  
											      	  <tr  id="Menor"     >
											      	  	<td onClick='js_marcaLinha("Menor"      ,"listaFiltros");' class="linhagrid">Menor que </td>
											      	  	<td onClick='js_marcaLinha("Menor"      ,"listaFiltros");' class="linhagrid"> < </td>
											      	  </tr>						      	  
									     				  <tr  id="MenorIgual">
												  		  	<td onClick='js_marcaLinha("MenorIgual" ,"listaFiltros");' class="linhagrid">Menor Igual </td>
												     	  	<td onClick='js_marcaLinha("MenorIgual" ,"listaFiltros");' class="linhagrid"> <= </td>
												     	  </tr>								  
													   	  <tr  id="MaiorIgual">
														     	<td onClick='js_marcaLinha("MaiorIgual" ,"listaFiltros");' class="linhagrid">Maior Igual </td>
												      	 	<td onClick='js_marcaLinha("MaiorIgual" ,"listaFiltros");' class="linhagrid"> >= </td>
												      	</tr>
						  								  <tr  id="Contendo">
						  								  	<td onClick='js_marcaLinha("Contendo" ,"listaFiltros");'   class="linhagrid">Contendo </td>
											      	  	<td onClick='js_marcaLinha("Contendo" ,"listaFiltros");'   class="linhagrid"> in </td>
											      	  </tr>											      	  								  
							   							  <tr  id="Nulo"      >
						  								  	<td onClick='js_marcaLinha("Nulo"       ,"listaFiltros");' class="linhagrid">Igual a Nulo </td>
											      	  	<td onClick='js_marcaLinha("Nulo"       ,"listaFiltros");' class="linhagrid">is null </td>
											      	  </tr>								  
							   							  <tr  id="Preenchido">
									   					  	<td onClick='js_marcaLinha("Preenchido" ,"listaFiltros");' class="linhagrid">Preenchido</td>
											      	  	<td onClick='js_marcaLinha("Preenchido" ,"listaFiltros");' class="linhagrid">is not null</td>
											      	  </tr>								  
											      	</tbody>
											      </table>  	
												  </fieldset>
										    </td>					    					  
										  </tr>
										</table>
  	          	  </td>	          	  
	             	</tr>
	            	<tr>
			            <td>	
					          <fieldset>
						      	  <legend align="center">
						      	    <b>Configurar Filtro</b>
						      	  </legend>
						          <table align="center">
						          	<tr id="btnConfigura">
						          	  <td>
						          	  	<input type="button"  value="Configurar" onClick="js_configuraFiltro();">
						          	  </td>
						          	</tr>			    
						          	<tr id="btnEnvia" style="display:none" ></tr>
						          </table>
					          </fieldset>      
			            </td>
	          	  </tr>
	          	  <tr id="mostraVariaveis"  style="display:none" >
	          	  </tr>
	            </table>
	          </td>
	          <td valign="top">
		          <table>
		            <tr>
				          <td style="padding-top:20px;">
				            <fieldset>
					  	        <table cellspacing="0" style="border:0px inset white; width:260px;" >
					  	        <thead style="display:block; position:absolute; overflow:none;">
						  	        <tr>
										      <th class="table_header" width="242px"><b>Filtros Configurados</b></th>
										      <th class="table_header" width="12px" ><b>&nbsp;</b></th>
										    </tr>
										    </thead>
						            <tbody name="filtrosConfigurados" id="filtrosConfigurados" style="height:180px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:18px; display:block;" >
					              </tbody>
						          </table>
									    <table align="center">
								        <tr>
								          <td>
								          	<input type="button" value="Excluir" onClick="js_excluiFiltro();">
								          </td>
								        </tr>					   
									    </table>  	
					          </fieldset>
				          </td>	  	          	 
	          	  </tr>
	            </table>
	          </td>
	        </tr>
	  	  </table>
	    </td>
	  </tr>	
  </table>
</form>
</center>
</body>
</html>
<script>
	
	function js_carregaCamposDisponiveis(aObjCampos){
	  
	  var sLinha  = "";	
  	  	
  	for ( var iInd = 0; iInd < aObjCampos.length; iInd++ ) {
 		  with (aObjCampos[iInd]) {
		  	sLinha += "<tr id='linhaCampo"+sNome+"' class='linhagrid' >";		  	
		  	sLinha += "  <td class='linhagrid'  onClick='js_marcaLinha(\"linhaCampo"+sNome+"\",\"listaCampos\");'  style='text-align:left; width:242px;'>"+sAlias.urlDecode();
		  	sLinha += "    <input type='hidden' name='"+sAlias.urlDecode()+"' id='"+sNome+"' value='"+aObjCampos[iInd].toSource()+"'>";
		  	sLinha += "  </td>";
		  	sLinha += "</tr>";		  		
   	  }
  	}
  	
    sLinha += "<tr><td style='height:100%;'>&nbsp;</td></tr>";
  	$('listaCampos').innerHTML = sLinha;
  	
	}


	function js_marcaLinha(idCampo,idTabela){
	
	  var iNroLinhas = $(idTabela).rows.length;
  	  
 	  for (var i=0; i < iNroLinhas; i++) {
  	 	$(idTabela).rows[i].className = 'linhagrid';
 	  }
 	  
 	  $(idCampo).className = 'marcado'; 

	}
	
	
	function js_configuraFiltro(){
  	  
 	  var sLinha      = '';  	  
 	  var aMarcados   = new Array();
  	var aCampo      = js_getElementbyClass($('listaCampos').rows,'marcado');
  	var aFiltro     = js_getElementbyClass($('listaFiltros').rows,'marcado');
  	
 	 	    aCampo[0].className  = 'linhagrid';
   	 	 	aFiltro[0].className = 'linhagrid';
  	
 	 	var iCodCampo    = aCampo[0].id.replace('linhaCampo','');
 	 	var idFiltro     = aFiltro[0].id;
 	 	
 	 	   	aMarcados[0] = eval($(iCodCampo).value);

 	  var sNomeCampo   = aMarcados[0].sAlias.urlDecode();
 	 	var sNomeFiltro  = aFiltro[0].cells[1].innerHTML;
	  


	  if ( !iCodCampo || !idFiltro ) {
	  	alert("Favor selecione um campo e filtro!");
	  	return false;
	  }

	  
	  $('btnConfigura').style.display = 'none';

	  sLinha += '  <td> 							                 ';
	  sLinha += '	   <select name="operFiltro">	     	 ';
	  sLinha += '	     <option value="and">e </option> ';
	  sLinha += '	     <option value="or" >ou</option> ';	  	  
    sLinha += '	   </select> 						             ';
    sLinha += '  </td>            				           ';
	  sLinha += '  <td>'+sNomeCampo+'</td>             ';
	  sLinha += '  <td>'+sNomeFiltro+'</td>            ';

	  
 	  if (idFiltro == "Nulo" || idFiltro == "Preenchido" ) {
 		  sLinha += '  <td><input type="hidden"  name="valFiltro"   value=""  ></td>';
	  } else {
 	    if (aMarcados[0].sMascara == "d") {
	      sLinha += '  <td>'+js_criaInputData()+'</td>';	  	  	  	  
	    } else {
	      sLinha += '  <td><input type="text" name="valFiltro"  size="30px;" onKeyUp="js_ValidaCampos(this,0,\'Valor do Filtro\',false,false,event);"></td>';	  	  
	    }	  
	  }
	
	  sLinha += '  <td><input type="button" value="Enviar" 	  onClick="js_enviaFiltro(\''+idFiltro+'\',\''+iCodCampo+'\',\''+aMarcados[0].sMascara+'\');"></td>';
	  sLinha += '  <td><input type="button" value="Voltar" 	  onClick="js_voltaConf();"></td>';	  	   
	  sLinha += '  <td><input type="button" value="Variáveis" onClick="js_consultaVariaveis();"></td>';
	  
	  $('btnEnvia').style.display = '';
	  $('btnEnvia').innerHTML 	  = sLinha;
	  
	}

	    
	
	function js_enviaFiltro(idFiltro,iCodCampo,tipoCampo){
      
    js_divCarregando('Aguarde...','msgBoxEnviaFiltro');
      
    var ValorFiltro  = document.form1.valFiltro.value;
	  var operFiltro   = document.form1.operFiltro.value;
	  	
      var ConsultaTipo = 'incluirFiltro';
      var url          = 'sys4_consultaviewRPC.php';
   	  
   	  var sQuery  = 'tipo='+ConsultaTipo;
   	  	  sQuery += '&sCampo='+$(iCodCampo).id;
   	  	  sQuery += '&sOperador='+operFiltro;
   	  	  sQuery += '&sCondicao='+$(idFiltro).id;
   	      sQuery += '&sValor='+ValorFiltro;
   	      sQuery += '&tipoCampo='+tipoCampo;
   	      
   	  var oAjax   = new Ajax.Request( url, {
                                             method: 'post', 
                                             parameters: sQuery,
                                             onComplete: js_retornoFiltros
                                           }
                                    );			
	}

	function js_retornoFiltros(oAjax){

	  var objFiltros = eval("("+oAjax.responseText+")");	
	  js_removeObj("msgBoxEnviaFiltro");	
	  js_carregaFiltros(objFiltros);
	
	}


	function js_carregaFiltros(aObjFiltros){
	  
	  if ($('ultimaLinha')) {
	    $('filtrosConfigurados').removeChild($('ultimaLinha'));
	  }
	  
	  for( var i=0; i < aObjFiltros.length; i++){
	  
	  	with (aObjFiltros[i]) {
	  	
	  	  var sIdFiltro		   = sCampo+''+sCondicao+''+sValor+''+sOperador;
	      var elem      	   = document.createElement("tr");
	  	      elem.id 	   	 = "filtroConf"+sIdFiltro;
	  	      elem.className = "linhagrid";
	  	  
	  	  $('filtrosConfigurados').appendChild(elem);
		    var sLinha  = "  <td class='linhagrid' onClick='js_marcaLinha(\"filtroConf"+sIdFiltro+"\",\"filtrosConfigurados\");' style='width:242px; text-align:left;' >"+sCampo.urlDecode()+" "+sCondicao.urlDecode()+" "+sValor.urlDecode()+"</td>";
	  	}
	  	
	    sLinha += "  <td><input type='hidden' name='valFiltros"+sIdFiltro+"' id='valFiltros"+sIdFiltro+"' value='"+aObjFiltros[i].toSource()+"' ></td>";		    
		  elem.innerHTML = sLinha;
	  }
	  
	  $('filtrosConfigurados').innerHTML += "<tr id='ultimaLinha'><td style='height:100%;'>&nbsp;</td></tr>";
	  
	  js_voltaConf(); 

	}



	function js_excluiFiltro(){
	  
	  js_voltaConf();
	  
	  var aMarcados   = new Array();
	  var aIdMarcados = new Array();
	  var iNroFiltros = $('filtrosConfigurados').rows.length;
	  
  	  for (var i=0; i < iNroFiltros; i++) {
  	  
  	 	if ($('filtrosConfigurados').rows[i].className == 'marcado'){
			idFiltros 	   =  $('filtrosConfigurados').rows[i].id;
			idValFiltros   = "valFiltros"+idFiltros.replace("filtroConf","");
			aMarcados[i]   =  eval($(idValFiltros).value);
			aIdMarcados[0] = idFiltros; 
  	 	}
  	 	
  	  }
	  
	  for(var i=0; i < aIdMarcados.length; i++ ){
	    $('filtrosConfigurados').removeChild($(aIdMarcados[i]));
	  }
		
	  if (!aMarcados) {
  	  	alert("Escolha algum filtro configurado")
  	  	return false;
  	  }
	  
 	  var url     = 'sys4_consultaviewRPC.php';
 	  var ConsultaTipo = "excluirFiltro";
	  var oAjax   = new Ajax.Request( url, {
                                          	 method: 'post', 
                                             parameters: "aObjFiltros="+aMarcados.toSource()+"&tipo="+ConsultaTipo
                                   		   }
                               	    );		
	}

	
	
	function js_voltaConf(){
	
	  $('btnEnvia').innerHTML 	  	     = "";
	  $('btnEnvia').style.display 	     = "none";
	  $('btnConfigura').style.display    = "";
	  $('mostraVariaveis').innerHTML     = "";
	  $('mostraVariaveis').style.display = "none";
	  
	}
	
	
	function js_criaInputData(){
	
	  var sSaida  = ' <input name="valFiltro"     type="text"   id="valFiltro"  value="" size="10" maxlength="10" autocomplete="off" onBlur="js_validaDbData(this);" onKeyUp="return js_mascaraData(this,event)" onSelect="return js_bloqueiaSelecionar(this);" onFocus="js_validaEntrada(this);"  >';
        sSaida += ' <input name="valFiltro_dia" type="hidden" title="" id="valFiltro_dia" value="" size="2"  maxlength="2" > ';
  		  sSaida += ' <input name="valFiltro_mes" type="hidden" title="" id="valFiltro_mes" value="" size="2"  maxlength="2" > ';
  		  sSaida += ' <input name="valFiltro_ano" type="hidden" title="" id="valFiltro_ano" value="" size="4"  maxlength="4" > ';
		    sSaida += ' <input value="D" type="button" name="dtjs_valFiltro" onclick="pegaPosMouse(event); show_calendar(\'valFiltro\',\'none\')" >';
				
	  return sSaida;
	}    	


  	function js_comparaDatasvalFiltro(dia,mes,ano){
    	var objData        = document.getElementById('valFiltro');
          objData.value  = dia+"/"+mes+'/'+ano;
    }
    
     
	function js_consultaVariaveis(){
		if ( $('mostraVariaveis').innerHTML == "" ){
		  js_pesquisaVariaveis();
		} else {
		  $('mostraVariaveis').innerHTML 	 = ""; 
		  $('mostraVariaveis').style.display = "none";
		}

	}
	
	function js_pesquisaVariaveis(){
	  
	  js_divCarregando('Consultando Variáveis...','msgBoxConsultaVar');
	  var sTipo = "consultaVariaveis";
	  var url   = 'sys4_consultaviewRPC.php';
    var oAjax = new Ajax.Request( url, {
                                         method: 'post', 
                                         parameters: "tipo="+sTipo,
                                         onComplete: js_montaVariaveis
                                       }
                                );		
	}
	
	function js_montaVariaveis(oAjax){
	 
 	  js_removeObj("msgBoxConsultaVar");
	      
    var aObjVariaveis = eval("("+oAjax.responseText+")");
    var sLinha = "";
	 	  
	  if (aObjVariaveis.length == 0){
	 
	    alert("Nenhuma variável cadastrada!");
	    return false;
	   
	  } else {
	 
			sLinha += "<td>";
			sLinha += "  <fieldset>";
			sLinha += "    <legend align='center'><b>Variáveis Disponíveis</b></legend>";
			sLinha += "    <table width='100%'>";
			var   x = 0;
		
	    for (var i=0; i < aObjVariaveis.length; i++) {
	    
		    var sNome  = aObjVariaveis[i].sNome.trim();
		    var sLabel = aObjVariaveis[i].sLabel.trim();
		    
			  if ( x == 0 ) { 	       
		        sLinha += "<tr width='100%'>";
		    }
		    
		    sLinha += "  <td width='33%'>";
		 	  sLinha += "    <input type='radio' class='itemvar'  name='radiovar' value='"+sNome+"'>";
		 	  
		 	  if ( sLabel != "" ){
		 	    sLinha += "    <label>"+sLabel+"( "+sNome+" )</label>";
		 	  } else {
		 	    sLinha += "    <label>"+sNome+"</label>";
		 	  }
		 	  
		 	  sLinha += "  </td>";
		 	  
		    if ( x == 2 ){	 	    
		 	    sLinha += "</tr>";
		 	    x = 0;
		 	  } else {
		 	    x++;
		 	  }  
	    }
	    
      sLinha += "  <tr><td></td><td><input type='button' id='confVar'  value='Usar Variável' onClick='js_usarVariavel()' ></td><td></td></tr>";
		  sLinha += "  </table>";	 
	    sLinha += " </fieldset>";   
		  sLinha += "</td>";
	    
	 	  $('mostraVariaveis').style.display = "";
	 	  $('mostraVariaveis').innerHTML 	   = sLinha;	
	    
	  }
	}     
	
	function js_usarVariavel(){
	  
	  aObjVariaveis = js_getElementbyClass(document.all,'itemvar');
	  
	  for (var i=0; i < aObjVariaveis.length; i++) {
	  	if ( aObjVariaveis[i].checked ){
	  	  document.form1.valFiltro.value = aObjVariaveis[i].value;
	  	}
	  }
	  
	}
	
</script>
