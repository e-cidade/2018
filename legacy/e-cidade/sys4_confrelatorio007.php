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
</head>
<style>
.marcado { 
           border-colappse:collapse;
           border-right:1px inset black;
           border-bottom:1px inset black;
           cursor:normal;
           font-family: Arial, Helvetica, sans-serif;
           font-size: 12px;
           font-align:center;
           background-color:#CCCDDD;
         }
</style>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; parent.lVariaveis = true;" bgcolor="#cccccc">
<form name="form1">
<center>
  <table style="padding-top:20px;">
    <tr> 
      <td>
        <table>
       	  <tr>
      	    <td>
      		  <fieldset>
		  		<legend>
		  		  <b>Cadastrar Variável</b>
		  		</legend>
		        <table>
		          <tr>
		            <td>
		              <b>Nome Variável :</b>
		            </td>
		            <td>
		              <?
      					    db_input("nomeVar",20,"",true,"text",1,"");
		              ?>
		            </td>
		          </tr>
		          <tr>   
		            <td>
		              <b>Descrição :</b>
		            </td>
		            <td>
		              <?
		              	db_input("descrVar",20,"",true,"text",1,"");
		              ?>                        
		            </td>                        
		          </tr>
		          <tr>  
		            <td>
		              <b>Tipo de Dado :</b>
		            </td>
		            <td>
		              <?

                  $aTipos = array("0"       => "Selecione o tipo",
                                  "varchar" => "Texto Livre",
                                  "int4"    => "Numero sem decimais",
                                  "float8"  => "Numero com decimais",
                                  "date"    => "Data",
                                  "bool"    => "Logico");
                  db_select('tipoDado',$aTipos,true,1,"");
		              ?>            
		            </td>
		          </tr>
		          <tr>  
		            <td>
		              <b>Valor Padrão :</b>
		            </td>
		            <td>
		              <?
		                db_input("valorVar",20,"",true,"text",1,"");
		              ?>                        
		            </td>                        
		          </tr>
		          
		      	</table>
      		  </fieldset>
      		</td>
     	  </tr>	
     	  <tr align="center">
       		<td>
	      	 <input type="button" name="acao" id="acao" value="Incluir" onclick="js_valida(this.value);" />
	        </td>
     	  </tr>
   		</table> 	
	  </td>
	  <td>
	    <table>
	      <tr>
	        <td>
	          <fieldset>
	 	        <table cellspacing="0" style="border:0px inset white; width:250px;" >
	 	        <thead style="display:block; position:absolute; overflow:none;">
			      <tr>
			        <th class="table_header" width="232px"><b>Variáveis Cadastradas</b></th>
			        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
		          </tr>
		         </thead>
			      <tbody id="variaveisConfigurados" style="height:130px; overflow:scroll; overflow-x:hidden !important; background-color:white; margin-top:18px; display:block;" >
			      </tbody>
	 	        </table>
		      </fieldset>
	        </td>
    	  </tr>
    	  <tr align="center">
    	    <td>
             <input type="button" name="editar"  id="editar"  value="Editar"  onclick="js_editarVariavel();"  disabled/>    	    
      			 <input type="button" name="excluir" id="excluir" value="Excluir" onclick="js_excluirVariavel();" disabled/>
    	    </td>
    	  </tr>
    	</table>
      </td>
    </tr>  	  
  </table>
</center>
</form>
</body>
</html>
<script>

  function js_valida(sAcao){

    var sNomeVar = new String(document.form1.nomeVar.value);
        sNomeVar = sNomeVar.trim();
    
    if ( sNomeVar == "" ) {
      alert("Digite um nome para a variável!");
      return false;
    } else if (document.form1.tipoDado.value=='0') {
      alert("Escolha um tipo para a variável!");
      return false;    
    } else {
      
      if ( sNomeVar.substr(0,1) != "$" ) {
        alert('Nome da variável deve começar com "$"!');
      } else {
        if ( sAcao == "Incluir" || sAcao == 'Alterar' ) {
          document.form1.acao.value = 'Incluir';
          document.form1.nomeVar.disabled      = false;
		      js_incluirVariavel();		      
        }
      }
    } 
  
  }

  function js_criaObjetoVariavel(sNome,sLabel,sValor,sTipoDado){
  
    this.sNome  = sNome;
    this.sLabel = sLabel;
    this.sValor = sValor;        
    this.sTipoDado = sTipoDado;        
  
  }


 
  function js_incluirVariavel(){
	  
		var doc         = document.form1;
		var objVariavel = new js_criaObjetoVariavel( doc.nomeVar.value,
													                       doc.descrVar.value,
													                       doc.valorVar.value,
	                                               doc.tipoDado.value );  
		js_enviaVariavel(objVariavel);  
	  
  }
 
 
 
  function js_enviaVariavel(objVariavel){
  
    js_divCarregando('Aguarde...','msgBoxEnviaVariavel');
    
   	var ConsultaTipo = 'incluirVariaveis';
   	var url          = 'sys4_consultaviewRPC.php';
   	var sQuery    	 = "tipo="+ConsultaTipo;  
   		  sQuery 	  	+= "&objVariavel="+Object.toJSON(objVariavel);
   	var oAjax        = new Ajax.Request( url, {
                                               method: 'post', 
                                               parameters: sQuery, 
                                               onComplete: js_retornoVariavel
                                              }
                                        );
  }

  
  function js_retornoVariavel(oAjax){
  
    js_removeObj("msgBoxEnviaVariavel");
	
	  var aObjVariavel = eval("("+oAjax.responseText+")");
	
	  js_carregaGrid(aObjVariavel);  
	
  }
  
  
  function js_excluirVariavel(){

    var aMarcados    = new Array(); 
    var aObjMarcados = js_getElementbyClass(document.all,'marcado');
    
    for (var i=0; i < aObjMarcados.length; i++) {
    
       var idVar   = aObjMarcados[i].id.replace("varConf","");
       aMarcados[i] = ($(idVar).value).evalJSON();
       $('variaveisConfigurados').removeChild(aObjMarcados[i]);
       
    }
    	
	  js_removeVariavel(aMarcados);
	  	    
  } 


  function js_removeVariavel(aObjVariavel){
    
   	var ConsultaTipo = 'excluirVariaveis';
   	var url          = 'sys4_consultaviewRPC.php';
   	var sQuery     	 = "tipo="+ConsultaTipo;  
   	  	sQuery  		+= "&aObjVariavel="+Object.toJSON(aObjVariavel);
   	var oAjax        = new Ajax.Request( url, {
                                               method: 'post', 
                                               parameters: sQuery,
                                               onComplete:js_limpaCampos
                                              }
                                        );  
  }
 
  
  function js_carregaGrid(aObjVariavel){
  
    if ($('ultimaLinha')) {
      $('variaveisConfigurados').removeChild($('ultimaLinha'));
    }
  
    for ( var i=0; i < aObjVariavel.length; i++ ){
 	  
 	    var idLinha = "varConf"+aObjVariavel[i].sNome.urlDecode();
      var sLinha  = " <tr id='"+idLinha+"'class='linhagrid'> ";
     		  sLinha += "   <td id='"+idLinha+"' class='linhagrid' onClick='js_marcaLinha(\""+idLinha+"\");' style='text-align:center; width:232px;'  >"+aObjVariavel[i].sNome.urlDecode()+" </td> ";
      	  sLinha += "   <input type='hidden' id='"+aObjVariavel[i].sNome.urlDecode()+"' value='"+Object.toJSON(aObjVariavel[i])+"'>";
      	  sLinha += " </tr>";
      $('variaveisConfigurados').innerHTML += sLinha; 
 	  
    } 
    
    $('variaveisConfigurados').innerHTML += "<tr id='ultimaLinha' ><td style='height:100%;'>&nbsp;</td></tr>";    
  	js_limpaCampos();
  }

  function js_marcaLinha(idLinha){
  
   var objMarcado = js_getElementbyClass(document.all,'marcado')

    for(var i=0; i < objMarcado.length; i++){
      if(objMarcado[i].id != idLinha){	
      	objMarcado[i].className = 'linhagrid';
      }	
    }	
    
    if( $(idLinha).className == "linhagrid" ){
       $(idLinha).className  = "marcado";
       $('editar').disabled  = false;
       $('excluir').disabled = false;
    } else {
       $(idLinha).className  = "linhagrid";
       $('editar').disabled  = true;
       $('excluir').disabled = true;    
    } 
    
  }


  function js_limpaCampos(){
  
  	document.form1.nomeVar.value  = "";
  	document.form1.descrVar.value = ""; 
  	document.form1.valorVar.value = "";
    $('editar').disabled  = true;
    $('excluir').disabled = true;
      	 
  }

  function js_editarVariavel(){
  
    var doc            = document.form1;
    var aObjMarcado    = js_getElementbyClass(document.all,'marcado')
    var iIdVariavel    = aObjMarcado[0].id.replace('varConf','');
    var iIdElemento    = aObjMarcado[0].id;
    var oVariavel      = ($(iIdVariavel).value).evalJSON();

    doc.nomeVar.value  = oVariavel.sNome.urlDecode();     
    doc.descrVar.value = oVariavel.sLabel.urlDecode();
    doc.tipoDado.value = oVariavel.sTipoDado.urlDecode();
    doc.valorVar.value = oVariavel.sValor.urlDecode();
  
    $('variaveisConfigurados').removeChild($(iIdElemento));
    $('acao').value = 'Alterar';
    
    doc.nomeVar.disabled  = true;
    $('editar').disabled  = true;
    $('excluir').disabled = true;
        
  }

</script>
