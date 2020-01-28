/**
 * Cria uma janela contendo a estrutura de um XML que deve ser passsado por parâmetro
 *@example Utilizando o DBXMLEditor
 *  var oXmlEditor = new DBXmlEditor('teste.xml');
 *      oXmlEditor.show();
 *
 * @class DBXmlEditor
 * @constructor
 * @param {string} sUrlXml URL do XML a ser lido  
 *   
 */
function DBXmlEditor(sUrlXml,oJs) {

 var me         = this;
 var iWidth     = document.width-20;
 
 this.iIndexList = 0;
 this.sUrlXml    = sUrlXml;
 this.oJsRetorno = oJs; 
 this.window     = new windowAux("windowXmlEditor","Editor de XML", iWidth, 700);
 
 var sHTML  = "<table width='100%' style='background:#FFFFFF;'>";
     sHTML += "  <tr>";
     sHTML += "    <td>";          
     sHTML += "        <div style='width:200px;float:left;clear:both;background-color:#FFFFFF;border-right:1px solid #000000;height:100%;padding-right:15px; '>";
     sHTML += "        <ul id='listaGrid'>";
     sHTML += "        </ul>";
     sHTML += "        </div>";
     sHTML += "        <div style='width:600px;float:left;clear:right;background-color:#FFFFFF; text-align:left;margin:0px'>";
     sHTML += "        <ul id='listaGrid2'>";
     sHTML += "        </ul>";
     sHTML += "        </div>";          
     sHTML += "    </td>";
     sHTML += "  </tr>";
     sHTML += "  <tr align='center'>";
     sHTML += "    <td>";
     sHTML += "      <input type='button' id='salvar' value='Salvar' class='button'>";
     sHTML += "    </td>";
     sHTML += "  </tr>";     
     sHTML += "</table>";

  this.window.setContent(sHTML);
  
  $("windowwindowXmlEditor_btnclose").onclick= function() {
     me.window.destroy();
  }
     
  $('salvar').onclick = function () { 
    me.save() 
  };

     
  this.oStyle = document.createElement('style');


  var sInnerCss  = " #listaGrid  ul { list-style-type:none; }";
      sInnerCss += " #listaGrid2 ul { list-style-type:none; margin:0; padding: 0;}";
      sInnerCss += " #listaGrid2 { list-style-type:none; margin:0px 0px 0px 5px; padding: 0;}";
      sInnerCss += " #listaGrid { list-style-type:none; margin:0; padding: 0;}";
      sInnerCss += " #listaGrid2 ul li{ list-style-type:none; margin:0; padding: 0;}";
		  sInnerCss += " .node { } ";
		  sInnerCss += " .attributes {} ";
		  sInnerCss += " ul {background-color:#FFFFFF; border-top:0px solid #FF0000;} ";
		  sInnerCss += " li {background-color:#FFFFFF; border-top:0px solid #FF0000;} ";
		  //sInnerCss += " input.textXml {border:none;height:90%;background-color:#FFFFFF;margin:0px;padding:0px;} ";
		  sInnerCss += " input.button {border:1px solid #999999;font-family:Arial,Helvetica,sans-serif;font-size:12px;height:17px;} ";
    
  document.getElementsByTagName('head')[0].appendChild(this.oStyle);
    
  this.oStyle.innerHTML = sInnerCss;

  /**
  * Exibe a janela da lista e consulta a estrutura do XML  
  * @param  void
  * @return void
  */      
  this.show = function () {
    me.window.show(25,5);
    me.getXml();
  }  
  
  /**
  * Consulta a estrutura do XML  
  * @param  void
  * @return void
  */   
  this.getXml = function (){
    
    var msgDiv = "Aguarde carregando arquivo XML...";
    js_divCarregando(msgDiv,'msgBox');
       
    var sUrl    = 'sys4_consultaXML.RPC.php';
    var oAjax   = new Ajax.Request(
                                    sUrl, 
                                    {
                                      method    : 'post', 
                                      parameters: 'sUrl='+me.sUrlXml, 
                                      onComplete: me.createGrid
                                    }
                                  );            
  
  }
  
  
  /**
  * Cria a lista apartir do XML  
  * @param  {oAjax} oAjax retorno do RPC
  * @return void
  */   
  this.createGrid = function (oAjax)
  {
    
    //alert(oAjax.responseText);
    js_removeObj("msgBox");
    
    var oXml      = oAjax.responseXML;
    var aListHTML = me.createGridByNode(oXml);
  
    $('listaGrid').innerHTML  = aListHTML[0];   
    $('listaGrid2').innerHTML = aListHTML[1];
  
    /**
    * Adiciona o método toggleLine para o evento onClick das ancoras de cada linha  
    */
    var aAnchors = $$('#listaGrid .node a');
   
    aAnchors.each(
      function ( eElem ) {
        var sID = new String(eElem.id);
        eElem.onclick = function () { me.toggleLine(sID.substr(6,sID.length)) };  
      }
    );
  
    /**
    * Adiciona o método TextFocus para o evento onFocus e onBlur dos inputs correspondentes ao valores dos atributos  
    */
    var aInputValues = $$('#listaGrid2 input');
    
	  aInputValues.each(
	    function ( eElem ) {
	      var sID = new String(eElem.id);
	      eElem.onfocus = function () { me.TextFocus(this,true) };       
	      eElem.onblur  = function () { me.TextFocus(this,false) };
	    }
	  );
	  
  }
  
   
  /**
   * Cria um parte da lista apartir de um nó do XML  
   * @param  {Object} oNode Nó do XML
   * @return string
   */    
  this.createGridByNode = function (oNode) 
  {
          
    var aChild          = oNode.childNodes;
    var iRowChild       = aChild.length;
    var sChildNodeHTML  = '';
    var sAttributeHTML  = '';
    var sChildNodeHTML2 = '';
    var sAttributeHTML2 = '';
            
            
    if ( iRowChild > 0 ) {
      for ( var iInd=0; iInd < iRowChild; iInd++ ) {
      
        if ( new String(aChild[iInd].nodeName).substr(0,1) == '#' ) {
          continue;
        }
        
        aChildrenNodesHTML = me.createGridByNode(aChild[iInd]);
        
        aChildNodeHTML     = me.createElementList(aChild[iInd].nodeName+'_'+me.iIndexList++,aChild[iInd].nodeName,'','node',aChildrenNodesHTML[0],aChildrenNodesHTML[1]);
        sChildNodeHTML    += aChildNodeHTML[0];
        sChildNodeHTML2   += aChildNodeHTML[1];
                        
      }
    }

    
    
    if ( new String(oNode.nodeName).substr(0,1) != '#') {
      var aAttributes   = oNode.attributes;       
      var iRowAttribute = aAttributes.length; 
             
      if ( iRowAttribute > 0 ) {
        for ( var iInd=0; iInd < iRowAttribute; iInd++ ) {
           aAttributeHTML   = me.createElementList(aAttributes[iInd].name+'_'+me.iIndexList++,aAttributes[iInd].name,aAttributes[iInd].value,'attribute','','');   
           sAttributeHTML  += aAttributeHTML[0];
           sAttributeHTML2 += aAttributeHTML[1];
        } 
      } 
    }
    

    var sNodeHTML  = sChildNodeHTML+sAttributeHTML;
    var sNodeHTML2 = sChildNodeHTML2+sAttributeHTML2;
       
    var aRetorno    = new Array();
        aRetorno[0] = sNodeHTML;
        aRetorno[1] = sNodeHTML2;
       
    return aRetorno;
  
  }
   
  /**
  * Cria uma lista apartir dos parâmetros passados 
  * @param {string} sID        ID do elemento
  * @param {string} sLabel     Label da lista
  * @param {string} sClasse    Classe do elemento
  * @param {string} sInnerHTML Conteúdo da lista
  * @param string 
  */    
  this.createElementList = function (sID,sLabel,sValue,sClasse,sInnerHTML,sInnerHTMLValues) {
     
    var sListHTML  = "<li id='"+sID+"' class='"+sClasse+"'>";

    if ( sClasse == 'node') {
      sListHTML += "<a style='-moz-user-select: none;cursor: pointer' id='anchor"+sID+"'>";
      sListHTML += "<img src='imagens/seta.gif' id='"+sID+"toggle'>";
      sListHTML += "<label>"+sLabel+"</label>";
      sListHTML += "</a>";
    } else {
      sListHTML += "<label>"+sLabel+"</label>";
    }
    sListHTML += "<ul id='list_"+sID+"' style='display:none; '>";
    sListHTML += sInnerHTML;
    sListHTML += "</ul>";
    sListHTML += "</li>"; 
    
    
    
    var sListHTMLText  = "<li class='"+sClasse+"'> ";
    
    if ( sClasse == 'node') {
      sListHTMLText += "&nbsp;";
    } else {
    
      var oInput = new DBTextField('values_'+sID,'values_'+sID,sValue);
      oInput.addStyle('width', "100%");
      oInput.addStyle('border', "none");
      oInput.addStyle('height', "90%");
      oInput.addStyle('background-color', "#FFFFFF");
      oInput.addStyle('margin', "0px");
      oInput.addStyle('padding', "0px");
      //border:none;height:90%;background-color:#FFFFFF;margin:0px;padding:0px;
      sListHTMLText += oInput.toInnerHtml();
      
    }
    sListHTMLText += "<ul id='list_values"+sID+"'style='display:none;'>";
    sListHTMLText += sInnerHTMLValues;
    sListHTMLText += "</ul>";
    sListHTMLText += "</li>"; 
        
    var aListHTML = new Array();
    aListHTML[0] = sListHTML;
    aListHTML[1] = sListHTMLText;
    
    return aListHTML;    
  
  }
  /**
  * Alterar a cor de fundo do campo que recebe foco
  * @param obj objeto que recebeu foco  
  * @param alter boolean onFocus = true OnBlur = false  
  * @return void
  */ 
  this.TextFocus = function (obj,alter) {
    
    if (alter) {
       obj.style.backgroundColor='#eeeee2';
     } else {
       obj.style.backgroundColor='white'
     }
    
  }
    
  /**
  * Exibe ou oculta o conteúdo do elemento passado por parâmetro
  * @param  {string} sId ID do elemento
  * @return void
  */ 
  this.toggleLine = function (sId) {
    
    var oNode       = $('list_'+sId);
    var oNodeValues = $('list_values'+sId);
        
    if (oNode.style.display == '') {
    
      oNode.style.display       = 'none';
      oNodeValues.style.display = 'none';
      $(sId+'toggle').src='imagens/seta.gif';
      
    } else if (oNode.style.display == 'none') {
    
      oNode.style.display       = '';
      oNodeValues.style.display = '';
      $(sId+'toggle').src='imagens/setabaixo.gif'
      
    }
    
  }
  
  this.save = function () {  
     me.oJsRetorno(me.toXML());      
  }
  
  this.toXML = function () {

    oNode = me.toXMLByNode($('listaGrid').childNodes[0]); 
    
    oXmlFactory = new DBXmlFactory();
    oXmlFactory.addNode(oNode);
    
    return oXmlFactory.toStrXml();
    
  }

  this.toXMLByNode = function (oNode) {

   var aLista      = oNode.childNodes[1].childNodes;
   var iRowsGrid   = aLista.length;
   var oNodeReturn = new DBXmlNode(oNode.childNodes[0].childNodes[1].innerHTML);
   
   for ( var iInd=0; iInd < iRowsGrid; iInd++ ) {
     if (aLista[iInd].className == 'node') {
	     oNodeReturn.addNode(me.toXMLByNode(aLista[iInd]));
     }
   }
   
   for ( var iInd=0; iInd < iRowsGrid; iInd++ ) {
     if (aLista[iInd].className == 'attribute') {
     
       var sNameAttribute  = aLista[iInd].childNodes[0].innerHTML;
       var sValorAttribute = $('values_'+aLista[iInd].id).value;
       var oAttribute = new DBXmlAttribute(sNameAttribute,sValorAttribute);
       oNodeReturn.addAttribute(oAttribute);
              
     }
   } 
   
   return oNodeReturn;   
    
  }  
     
}