<?
/*
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
<script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.marca, .marcaEnvia, .marcaRetira 
 { 
 	 border-colappse  : collapse;
 	 border-right     : 1px inset black;
   border-bottom    : 1px inset black;
   cursor           : normal;
   font-family      : Arial, Helvetica, sans-serif;
   font-size        : 12px;
   background-color : #CCCDDD
 }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td style="padding-top:20px;"> 
	  <fieldset>
	  	<legend align="center">
	  	  <b>Campos Disponíveis:</b>
	  	</legend>
	     <table cellspacing="0" style="border:0px inset white; width:260px;" >
		      <thead style="display:block; position:absolute; overflow:none;">
		      <tr>
			      	<th class="table_header" width="244px"><b>Campos</b></th>
	    		    <th class="table_header" width="12px" ><b>&nbsp;</b></th>
	      	</tr>
	      	</thead>
	      	<tbody id="camposDisponiveis" style="height:300px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:16px; display:block;"  >
	      	</tbody>
	    </table> 		
	  </fieldset>
    </td>
    <td>
      <table>
        <tr>
      	  <td>
      	  	<input name="enviaTodos"  type="button" value=">>"  style='width:30px;' onClick="js_enviaTodosCampos();">
      	  </td>
      	</tr>
      	<tr>
      	  <td>
      	  	<input name="envia"   	  type="button" value=">"   style='width:30px;' onClick="js_enviaCamposMarcados();">
      	  </td>
      	</tr>
      	<tr>
      	  <td>
      	  	<input name="retorna" 	   type="button" value="<"  style='width:30px;' onClick="js_retiraCamposMarcados();">
      	  </td>
      	</tr>	
      	<tr>
      	  <td>
      	  	<input name="retornaTodos" type="button" value="<<" style='width:30px;' onClick="js_retiraTodosCampos();">
      	  </td>
      	</tr>      	
      </table>
    </td>
    <td style="padding-top:20px;">
      <fieldset>
	  	<legend align="center">
	  	  <b>Campos Selecionados:</b>
	  	</legend>
	    <table cellspacing="0" style="border:0px inset white; width:637px;" >
		      <thead style="display:block; position:absolute; overflow:none;">
		      <tr>
		        <th class="table_header" width="154px"><b>Nome</b></th>
		        <th class="table_header" width="79px" ><b>Tamanho</b></th>
		        <th class="table_header" width="84px"><b>Alinhamento Coluna</b></th>
		        <th class="table_header" width="68px" ><b>Formatar</b></th>
		        <th class="table_header" width="83px" ><b>Alinhamento Cabeçalho</b></th>
		        <th class="table_header" width="96px" ><b>Totalizar</b></th>
		        <th class="table_header" width="50px" ><b>Quebra</b></th>
		        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
		      </tr>  
		    </thead>
	      <tbody id="camposSelecionados" style="width:650px;height:300px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:31px; display:block;" >
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
    
  var sUrl          = 'sys4_consultaviewRPC.php';    
	var temporizador  = null;	 

	js_divCarregando('Aguarde, Iniciando Gerador...','msgBoxInicio');
  js_verificaAbas();
  
     
  function js_verificaAbas() {
    if ( parent.lFinalizar && parent.lVariaveis && parent.lPropriedades && parent.lFiltros ) {    
      clearTimeout(temporizador);
      js_removeObj('msgBoxInicio');
      js_verificaAlteracao();
    } else {
      temporizador  = setTimeout('js_verificaAbas()',500);
    }
      
  }
     

  function js_verificaAlteracao(){
      
    var ConsultaTipo = 'verificaAlteracao';
    var oAjax        = new Ajax.Request( sUrl, {
                                                 method: 'post', 
                                                 parameters: 'tipo='+ConsultaTipo, 
                                                 onComplete: js_retornoVerificaAlteracao
                                               }
                                        );
  }                                             

  function js_retornoVerificaAlteracao(oAjax){
  
    var aRetorno = eval("("+oAjax.responseText+")");
    
    if ( aRetorno.lAlteracao ) {
      js_buscaDadosRelatorio();
    } else {
      js_buscaDadosCampos();
    } 
    
  }    
       
     
  function js_buscaDadosRelatorio(){
      
    js_divCarregando('Buscando Dados do Relatório...','msgBoxCarregaRelatorio');
    
    var ConsultaTipo = 'buscaDadosRelatorio';
    var oAjax        = new Ajax.Request( sUrl, {
                                                 method: 'post', 
                                                 parameters: 'tipo='+ConsultaTipo, 
                                                 onComplete: js_retornoRelatorio
                                               }
                                        );      
  }
    

	function js_retornoRelatorio(oAjax){

	  js_removeObj('msgBoxCarregaRelatorio');
    
    var oRelatorio = eval("("+oAjax.responseText+")");

    if ( oRelatorio.erro ) {

      alert(oRelatorio.msg);
      return false;
    }
    
    
    //Carrega aba Campos
    js_confereCampos(oRelatorio.aCampos,oRelatorio.aCamposConfigurados);
    
    //Carrega aba Ordem
    parent.iframe_ordem.js_confereCampos(oRelatorio.aCampos,oRelatorio.aOrdem);
    
    //Carrega aba Filtros
    parent.iframe_filtros.js_carregaCamposDisponiveis(oRelatorio.aCampos);
    parent.iframe_filtros.js_carregaFiltros(oRelatorio.aFiltros);

    //Carrega aba propriedades
    parent.iframe_layout.js_processaPropriedades(oRelatorio.oPropriedades);
      
    //Carrega aba variáveis
    parent.iframe_variaveis.js_carregaGrid(oRelatorio.aVariaveis);
    
    //Carrega aba finalizar
    parent.iframe_finalizar.js_consultaTipoGrupo(oRelatorio.oTipoGrupo);
    
	  
	}


	function js_confereCampos(aObjCamposDisponiveis,aObjCamposConfigurados){
	  
	  $('camposDisponiveis').innerHTML = js_carregaCamposDisponiveis(aObjCamposDisponiveis);
	  
	  for(var i=0; i < aObjCamposDisponiveis.length; i++ ){
	    for(var x=0; x < aObjCamposConfigurados.length; x++){
	  	  if ( aObjCamposDisponiveis[i].iId == aObjCamposConfigurados[x].iId){
		  		if (aObjCamposConfigurados[x].sAlias == '' ) {
		  		  aObjCamposConfigurados[x].sAlias = aObjCamposDisponiveis[i].sAlias.urlDecode();
		  		}
		  		$('linhaCampo'+aObjCamposDisponiveis[i].iId).style.display = "none";
	  	  }
	  	}
	  }
    
	  js_carregaGrid(aObjCamposConfigurados);
	  
	}


  function js_buscaDadosCampos(){
    
    js_divCarregando('Listando Campos da Consulta...','msgBoxDadosCampos');
    
    var ConsultaTipo = 'consultaCampos';
    var oAjax        = new Ajax.Request( sUrl, {
                                                 method: 'post', 
                                                 parameters: 'tipo='+ConsultaTipo, 
                                                 onComplete: js_retornoBuscaCampos
                                                }
                                         );
  }


	function js_retornoBuscaCampos(oAjax){
	

    var aRetorno = eval("("+oAjax.responseText+")");

	  js_removeObj("msgBoxDadosCampos");
	  
    if ( aRetorno.erro ) {
      alert(aRetorno.msg.urlDecode());
     	parent.document.location.href = "sys4_geradorrelatorio001.php";
    }  
	
    //Carrega aba Ordem
    parent.iframe_ordem.js_carregaCamposDisponiveis(aRetorno.aCampos);
    
    //Carrega aba Filtros
    parent.iframe_filtros.js_carregaCamposDisponiveis(aRetorno.aCampos);
   
    // Carrega aba Campos	
	  $('camposDisponiveis').innerHTML = js_carregaCamposDisponiveis(aRetorno.aCampos);
	  
    //Carrega aba variáveis
    if ( aRetorno.aVariaveis.length > 0  ) {
	    parent.iframe_variaveis.js_carregaGrid(aRetorno.aVariaveis);
    }  	  
	  
	}
	

	function js_carregaCamposDisponiveis(objCampos){
	  
	  var sLinha  = "";
	  
	  if (objCampos) {
	   
	  	for ( var iInd = 0; iInd < objCampos.length; iInd++ ) {
	       	
			  with (objCampos[iInd]) {
			  
			    var oCampo      = new js_objCampo(iId,sNome,sAlias,iLargura,sAlinhamento,sAlinhamentoCab,sMascara,sTotalizar,lQuebra);
			    var sAtributos  = " class='linhagrid'"; 
			        sAtributos += " onDblClick='js_enviaCampo(\"linhaCampo"+iId+"\");'";   
			        sAtributos += " onClick='js_marcaLinha(\"linhaCampo"+iId+"\",\"marcaEnvia\");'";  
			        sAtributos += " style='text-align:left; width:244px !important;'";
			        
					  	sLinha += " <tr id='linhaCampo"+iId+"' >";		  	
					  	sLinha += "   <td "+sAtributos+">"+sAlias.urlDecode();
					  	sLinha += "     <input type='hidden' name='"+sNome+"' id='"+iId+"' value='"+Object.toJSON(oCampo)+"'>";
					  	sLinha += "   </td> ";
					  	sLinha += " </tr> ";
			  }	  	
	  	}
	  }
    sLinha += "<tr id='ultimaLinhaDisp' ><td style='height:100%;'>&nbsp;</td></tr>";
    
	  return sLinha;
	}



	function js_marcaLinha(iId,sTipoMarca){
    if ($(iId).className != sTipoMarca){
	  	$(iId).className = sTipoMarca; 
    } else {
   		$(iId).className = 'linhagrid';  	  
  	}
	}
	
	
	function js_retiraCampo(objCampo){
	    
	  var objMarcados = new Array(objCampo); 
    js_retiraCamposGrid(objMarcados);
	
	}
	
	function js_retiraCamposMarcados(){
	
	  var objMarcados = js_getElementbyClass(document.all,'marcaRetira');
    js_retiraCamposGrid(objMarcados);
    
	}	
	
	function js_retiraTodosCampos(){
	
	  var objLinhas   = $('camposSelecionados').rows;
	  var objMarcados = new Array();  
	  
	  for (var iInd=0; iInd < objLinhas.length; iInd++ )  {
      if ( objLinhas[iInd].id != 'ultimaLinha' ) {
        objMarcados[iInd] = objLinhas[iInd];
      }
	  }
	  
	  js_retiraCamposGrid(objMarcados);
	  
	}
	
	function js_retiraCamposGrid(objMarcados){
    
    var aMarcado        = new Array();
    var iLinhasMarcados = new Number(objMarcados.length);
    
    if ( iLinhasMarcados > 0 ) {
    	
	    for ( i=0; i < iLinhasMarcados; i++ ) {
	      if ( objMarcados[i].id ) { 
		      var idCampo     = objMarcados[i].id.replace('linhaGrid','');
		          aMarcado[i] = $(idCampo).name; 
		          idCampo     = 'linhaCampo'+idCampo;
		          $(idCampo).style.display = '';
		          $('camposSelecionados').removeChild(objMarcados[i]);
		    }               
	    }
	    
      js_retiraCamposSessao(aMarcado);
      	
    } else {
      alert('Nenhum campo selecionado!');
      return false;
    }
    
	
	}
	
	
  function js_retiraCamposSessao(aMarcado){
  
    js_divCarregando('Aguarde...','msgBoxRetiraCampo');
    
	  var oAjax  = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: 'tipo=excluirCampos&aCampos='+aMarcado,
                                           onComplete: js_removeObj("msgBoxRetiraCampo")
                                         }
                                  );
  }

  
  function js_enviaCampo(idCampo){
    
    var aObjCampos = new Array($(idCampo));
    js_enviaCamposGrid(aObjCampos);
        
  }   
	
	function js_enviaCamposMarcados(){
	  
	  var aObjCampos = js_getElementbyClass(document.all,'marcaEnvia');
    js_enviaCamposGrid(aObjCampos);
	  	  
	}


  function js_enviaTodosCampos(){
    
    var objLinhas  = $('camposDisponiveis').rows;
    var aObjCampos = new Array();
    var iCont      = new Number(0);
    
    for (var iInd=0; iInd < objLinhas.length; iInd++ ) {
      if ( objLinhas[iInd].id  != 'ultimaLinhaDisp' ){    
	      if ( $(objLinhas[iInd].id).style.display == "") {
	        aObjCampos[iCont++] = objLinhas[iInd];
	      }
      }
      
    }
    
    js_enviaCamposGrid(aObjCampos);
        
  }

  function js_enviaCamposGrid(objMarcados){
  
    var aMarcados       = new Array();
    var iLinhasMarcados = new Number(objMarcados.length); 
     
    if ( iLinhasMarcados > 0 ) { 
	    for (var iInd=0; iInd < iLinhasMarcados; iInd++ ) {
	      $(objMarcados[iInd].id).style.display = "none";
	      $(objMarcados[iInd].id).className     = "linhagrid";
	      aMarcados[iInd] = ($(objMarcados[iInd].id.replace('linhaCampo','')).value).evalJSON();
	    }
	    js_enviaCamposSessao(aMarcados);
    } else {
      alert('Nenhum campo selecionado!');
      return false;
    }
      
  }

	
	function js_enviaCamposSessao(aCampos){
	
	  js_divCarregando('Aguarde...','msgBoxEnviaCampo');
	  var ConsultaTipo = 'incluirCampos';
 	  var sQuery    	 = 'tipo='+ConsultaTipo;
 	  	  sQuery 	    += '&aObjCampos='+Object.toJSON(aCampos);   	  
   	  
 	  var oAjax        = new Ajax.Request( sUrl, {
                                                 method: 'post', 
                                                 parameters: sQuery,
                                                 onComplete: js_retornoCamposSessao
                                               } 
                                         );
	}
	
	
	function js_retornoCamposSessao(oAjax){
	
	  js_removeObj("msgBoxEnviaCampo");
	  var objCampos = eval("("+oAjax.responseText+")");
    js_carregaGrid(objCampos);
      	  		
	}


	function js_carregaGrid(objCampos){
	
	  var sLinha = "";
	  
	  if ($('ultimaLinha')) {
	    $('camposSelecionados').removeChild($('ultimaLinha'));
	  }
	  
	  for (var i = 0; i < objCampos.length; i++) {	  
 	   
	    with (objCampos[i]) {
	  	
       var oCampo     = new js_objCampo(iId,sNome,sAlias,iLargura,sAlinhamento,sAlinhamentoCab,sMascara,sTotalizar,lQuebra);
	  	 var elem       = document.createElement("tr");
	  	     elem.id    = "linhaGrid"+iId;
	  	 
		   $('camposSelecionados').appendChild(elem);
		   
		   var sAtributos = "onChange='js_alteraCampo("+iId+",this);'"; 
		   var sAcoes     = "onClick='js_marcaLinha(\"linhaGrid"+iId+"\",\"marcaRetira\");js_marcaLinha(this.id,\"marca\");'";
		       sAcoes    += "onDblClick='js_retiraCampo("+elem.id+");'";

	     sLinha  = " <td class='linhagrid' ><input  name='sAlias'          id='sAlias"+iId+"'         "+sAtributos+" type='text'  "+sAcoes+" value='"+sAlias.urlDecode()+"'  style='border:0;width:100%;text-align:left;' ></td> ";
	     sLinha += " <td class='linhagrid' ><input  name='iLargura'        id='iLargura"+iId+"'       "+sAtributos+" type='text' size='8px'  value="+iLargura+" ></input></td>  ";
	   	 sLinha += " <td class='linhagrid' ><select name='sAlinhamento'    id='sAlinhamento"+iId+"'   "+sAtributos+" style='width:100%' ></select></td> ";
	     sLinha += " <td class='linhagrid' ><select name='sMascara'        id='sMascara"+iId+"'       "+sAtributos+" style='width:100%' ></select></td> ";
	   	 sLinha += " <td class='linhagrid' ><select name='sAlinhamentoCab' id='sAlinhamentoCab"+iId+"'"+sAtributos+" style='width:100%' ></select></td> ";
	   	 sLinha += " <td class='linhagrid' ><select name='sTotalizar'      id='sTotalizar"+iId+"'     "+sAtributos+" style='width:100%' ></select></td> ";
       
       if ( lQuebra ) {
         sLinha += " <td class='linhagrid' style='width:50px;' ><input  name='lQuebra'         id='lQuebra"+iId+"'      onChange='js_alteraQuebra("+iId+",this);' type='checkbox' checked/></td> ";
       } else {
         sLinha += " <td class='linhagrid' style='width:50px;' ><input  name='lQuebra'         id='lQuebra"+iId+"'      onChange='js_alteraQuebra("+iId+",this);' type='checkbox' /></td> ";       
       }		   
		   
		   sLinha += " <td><input type='hidden' name='atributoCampos' id='atributoCampos"+iId+"' value='"+Object.toJSON(oCampo)+"' /></td> ";
		 	
	     elem.innerHTML = sLinha;
		  
   	   var sStrAlinhamento = "[{valor:'c',texto:'Centro'},{valor:'l',texto:'Esquerda'},{valor:'r',texto:'Direita'}]";	      
	     var sStrFormato     = "[{valor:'t',texto:'Texto'} ,{valor:'m',texto:'Moeda'}   ,{valor:'d',texto:'Data'}]";
	     var sStrTotalizar   = "[{valor:'n',texto:'Não'}   ,{valor:'s',texto:'Soma' }   ,{valor:'q',texto:'Quantidade'}]";
	     
	     js_montaSelect($("sAlinhamento"+iId)    ,sStrAlinhamento ,sAlinhamento);
	     js_montaSelect($("sMascara"+iId)		     ,sStrFormato     ,sMascara);
	     js_montaSelect($("sAlinhamentoCab"+iId) ,sStrAlinhamento ,sAlinhamentoCab);
	     js_montaSelect($("sTotalizar"+iId)		   ,sStrTotalizar   ,sTotalizar);
	      
	    }			  		
    }
    
    var elemUltimaLinha           = document.createElement("tr");
        elemUltimaLinha.id        = 'ultimaLinha';
        
        $('camposSelecionados').appendChild(elemUltimaLinha);
        
        elemUltimaLinha.innerHTML = "<td style='height:100%;'>&nbsp;</td>";
    
	}
	
	
	function js_alteraCampo(idCampo,objAtributo){
	
	  var objCampo = JSON.parse($('atributoCampos'+idCampo).value);
      	  eval( 'objCampo.'+objAtributo.name+' = "'+objAtributo.value+'"');
      	  $('atributoCampos'+idCampo).value = JSON.stringify(objCampo); 	      
   	  var ConsultaTipo = 'alterarCampos';
          
   	  var sQuery  = 'tipo='+ConsultaTipo;
   	  	  sQuery += '&objCampo='+JSON.stringify(objCampo);
   	  var oAjax   = new Ajax.Request( sUrl, {
                                             method: 'post', 
                                             parameters: sQuery
                                           } 
                                    );
	 }
	

  function js_alteraQuebra(idCampo,objAtributo){
  
    var objCampo = JSON.parse($('atributoCampos'+idCampo).value);
          eval( 'objCampo.'+objAtributo.name+' = "'+objAtributo.checked+'"');
          $('atributoCampos'+idCampo).value = JSON.stringify(objCampo);         
      var ConsultaTipo = 'alterarCampos';
          
      var sQuery  = 'tipo='+ConsultaTipo;
          sQuery += '&objCampo='+JSON.stringify(objCampo);
      var oAjax   = new Ajax.Request( sUrl, {
                                             method: 'post', 
                                             parameters: sQuery
                                           } 
                                    );
   }	
	
	
	 function js_montaSelect(objSel, jsonParametros, sValorPadrao){
	   
	   eval("objParam = "+jsonParametros);
		   
	   for(var i=0; i< objParam.length; i++){
	   
			 objSel.options[i]       = new Option();
			 objSel.options[i].value = objParam[i].valor;
			 objSel.options[i].text  = objParam[i].texto;
			 
			 if (objParam[i].valor == sValorPadrao){
			   objSel.options[i].selected = true;
			 }
	   }
	   	
	 }

   function js_objCampo(iId,sNome,sAlias,iLargura,sAlinhamento,sAlinhamentoCab,sMascara,sTotalizar,lQuebra){
   
     this.iId             = iId;  
     this.sNome           = sNome.urlDecode();
     this.sAlias          = sAlias.urlDecode();
     this.iLargura        = iLargura;
     this.sAlinhamento    = sAlinhamento;
     this.sAlinhamentoCab = sAlinhamentoCab;
     this.sMascara        = sMascara;
     this.sTotalizar      = sTotalizar;
     this.lQuebra         = lQuebra;
   
   }
	
</script>
