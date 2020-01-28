<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbmessageBoard.widget.js,dbcomboBox.widget.js,datagrid.widget.js, prototype.maskedinput.js, 
               DBTreeView.widget.js,arrays.js");
?> 
<link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body bgcolor=#CCCCCC leftmargin="0" onload="js_carregaGruposOrigemDestino();" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">


<center>

<form name="form1" method="post">
		
	<fieldset style="margin-top: 50px; width: 800px; height: 480px;">
	<legend><strong>Vincular Grupos e Subgrupos de Estruturais</strong></legend>
	
	  <fieldset style="width: 350; height:450px; position: relative;float: left;">
	    <legend>
	      <strong>Origem</strong>
	    </legend>
  
      <div style="width:350px; text-align: center; ">
      </div>
      
      <div id='ctnTreeViewOrigem' style="width:350px; height:400px; text-align: left;margin-top: 10px;"></div>	  
      
	  </fieldset>	
	
	
	  <fieldset style="width: 350; height:450px; position: relative;float: right; overflow: auto;">
	    <legend>
	      <strong>Destino</strong>
	    </legend>
	    
	    <div style="width:350px; text-align: center;">
        
      </div>
	    <div id='ctnTreeViewDestino' style="width:350px; height:400px; text-align: left;margin-top: 10px;"></div>
	  </fieldset>		
	
	</fieldset>
  <div style="margin-top: 10px;">
	  <input name="vincular" id='vincular' type="button" onclick="js_processarVinculo();"  value="Processar">
	</div>
</form>

</center>

<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

</body>
</html>

<script>


// ================================  funcao para processar dados selecionados origem / destino

function js_processarVinculo(){

  var sRPCArq              = 'mat4_vinculosTipoGrupoSubGrupo.RPC.php';//mat4_materialgrupo.RPC.php';
//  var lTipoVinculo         = $('origemdestino').checked;
  var aOrigemSelecionados  = js_getOrigens();
  var aDestinoSelecionados = js_getDestinos();
  var oParam               = new Object();

  if (aOrigemSelecionados == '') {
    alert('Selecione algum item de origem para realizar o vinculo.');
    return false;
  }
  if (aDestinoSelecionados == '') {
    alert('Selecione algum item de destino para realizar o vinculo.');
    return false;
  }
  
  js_divCarregando("Aguarde, vinculando registros","msgBox");

  oParam.exec         = 'processarVinculoGrupoSubgrupo';
  oParam.aOrigem      = aOrigemSelecionados;
  oParam.aDestino     = aDestinoSelecionados;
  
  var oAjax   = new Ajax.Request(sRPCArq,
                                 {method: 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoVinculo
                                 }) ;  
  
  
}
function js_retornoVinculo(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status == 1){

    alert("Processo realizado com Sucesso.");
    window.location = "mat1_vinculogruposubgrupo001.php";

  } else {
    alert(oRetorno.message.urlDecode());
  }
  
}



//=============================   Funções para retornar os valores selecionados de origem e destino

function js_getDestinos(){

  var aLinhas       = oTreeViewGruposDestino.getNodesChecked();
  var aSelecionados = new Array();

  aLinhas.each ( 
    function(oRetornoCheck) {
    
      aSelecionados.push(oRetornoCheck.value);
    }
  )

  return aSelecionados;
}

function js_getOrigens(){

  var aLinhas       = oTreeViewGruposOrigem.getNodesChecked();
  var aSelecionados = new Array();
  
  aLinhas.each ( 
    function(oRetornoCheck) {
    
      aSelecionados.push(oRetornoCheck.value);
    }
  )

  return aSelecionados;
}


//==========================  funcao que monta a lista de grupos ao carregar o formulario

function js_carregaGruposOrigemDestino(){

  js_escolheGrupoSubgrupoOrigem();
  js_escolheGrupoSubgrupoDestino();
  
}

//=========================== TREEVIEW PARA Destino DE GRUPOS E SUBGRUPOS

var oTreeViewGruposDestino = new DBTreeView('treeViewGrupoDestino');

function js_escolheGrupoSubgrupoDestino() {
 
    var iTamWidth          = screen.availWidth / 2;
    var iTamHeight         = screen.availHeight / 2;
    var iTamHeightFieldset = iTamHeight - 150;

    oTreeViewGruposDestino.show($('ctnTreeViewDestino'));
    oNoPrincipalDestino = oTreeViewGruposDestino.addNode("0", "Grupos / Subgrupos");
    
    js_divCarregando("Aguarde, buscando registros","msgBox");
    
    var sRPCArq = 'mat4_materialgrupo.RPC.php';
    var oParam  = new Object();
    oParam.exec = 'getGrupos'; 
    var oAjax   = new Ajax.Request(sRPCArq,
                                   {method: 'post',
                                    asynchronous: false,
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoWindowGrupoDestino
                                   }) ;
    
    
    oTreeViewGruposDestino.allowFind(true);
    oTreeViewGruposDestino.setFindOptions('hint');
}

function js_retornoWindowGrupoDestino (oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno      = eval("(" + oAjax.responseText + ")");
  var iTotalRetorno = oRetorno.aGrupos.length;
  var sRetornoInfo  = oRetorno.aGrupos;
  
  for (var i = 0; i < iTotalRetorno; i++) {
  
    with(oRetorno.aGrupos[i]) {

      var iRetNivel      = nivel;
      var sRetLabel      = estrutural+" - "+descricaogrupo.urlDecode();
      var sRetParentNode = conta_pai;

      oCheck = function (oNode, event) {

        if (oNode.checkbox.checked) {
          
          oNode.uncheckAll(event);
          oTreeViewGruposDestino.aNodes.each(function (oNodeTeste, iTot) {
             oNodeTeste.uncheckAll(event);
          }); 
          oTreeViewGruposDestino.setChecked(event, event.target);
         } 
        
       }
      
      oTreeViewGruposDestino.addNode(codigogrupo, 
                              sRetLabel, 
                              sRetParentNode,
                              null, 
                              null, 
                              {checked:false,
                               onClick:oCheck
                              }
                              );
    }
  }
   oNoPrincipalDestino.expand();
}


        

// =========================== TREEVIEW PARA ORIGEM DE GRUPOS E SUBGRUPOS
  
var oTreeViewGruposOrigem = new DBTreeView('treeViewGrupoOrigem');

function js_escolheGrupoSubgrupoOrigem() {
 
    var iTamWidth          = screen.availWidth / 2;
    var iTamHeight         = screen.availHeight / 2;
    var iTamHeightFieldset = iTamHeight - 150;

    oTreeViewGruposOrigem.show($('ctnTreeViewOrigem'));
    oNoPrincipalOrigem = oTreeViewGruposOrigem.addNode("0", "Grupos / Subgrupos");
    
    js_divCarregando("Aguarde, buscando registros","msgBox");
    
    var sRPCArq = 'mat4_materialgrupo.RPC.php';
    var oParam  = new Object();
    oParam.exec = 'getGrupos'; 
    var oAjax   = new Ajax.Request(sRPCArq,
                                   {method: 'post',
                                    asynchronous: false,
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoWindowGrupoOrigem
                                   }) ;
    
    
    oTreeViewGruposOrigem.allowFind(true);
    oTreeViewGruposOrigem.setFindOptions('hint');
}

var lExisteSelecionado = false;

function js_retornoWindowGrupoOrigem (oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno      = eval("(" + oAjax.responseText + ")");
  var iTotalRetorno = oRetorno.aGrupos.length;
  var sRetornoInfo  = oRetorno.aGrupos;
  
  for (var i = 0; i < iTotalRetorno; i++) {
  
    with(oRetorno.aGrupos[i]) {
    
      var iRetNivel      = nivel;
      var sRetLabel      = estrutural+" - "+descricaogrupo.urlDecode();
      var sRetParentNode = conta_pai;

      
      oCheck = function (oNode, event) {

       if (oNode.checkbox.checked) {
         
         oNode.uncheckAll(event);
         oTreeViewGruposOrigem.aNodes.each(function (oNodeTeste, iTot) {
            oNodeTeste.uncheckAll(event);
         }); 
         oTreeViewGruposOrigem.setChecked(event, event.target);
         //console.log(oTreeViewGruposOrigem.aNodes); 
        } else {
         
           //oNode.uncheckAll(event);
        }
      }
      
      var aParams       = new Array();
      aParams['indice'] = i;
      aParams['nivel']  = iRetNivel;

      oTreeViewGruposOrigem.addNode(codigogrupo, 
                              sRetLabel, 
                              sRetParentNode,
                              null, 
                              null, 
                              {checked:false,
                               onClick:oCheck
                              },
                              null,
                              aParams
                              );
    }
  }
   oNoPrincipalOrigem.expand();
}
</script>