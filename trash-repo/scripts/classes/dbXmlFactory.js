/** 
 * @fileoverview Define classe fabrica para criar string xml
 *
 * @author Robson de Souza Inacio robson@dbseller.com.br 
 * @author Ricardo Zucco          ricardo@dbseller.com.br
 * @version  $Revision: 1.1 $
 */

/**
 * @class DBXmlFactory
 * @constructor     
 */
function DBXmlFactory() {

  var me        = this;
  this.sStr     = "<?xml version=\"1.0\" standalone=\"yes\"?>\n";
  this.aNodes   = new Array();
  this.iIndNode = 0;  
  /**
   * Metodo retorna string XML com o conteudo do xml
   */   
  this.toStrXml = function () {

    for (var i = 0; i < me.aNodes.length; i++) {
      me.sStr += me.aNodes[i].toStrXml()+"";
    }   

    return me.sStr;
  }
  
  /**
   * Metodo adiciona objeto do tipo DBXmlNode
   * @param {DBXmlNode} no para ser adicionado ao array de nós
   */
  this.addNode   = function (oNode){
    me.aNodes[me.iIndNode] = oNode;
    me.iIndNode += 1;
  }

}

/** 
 * @fileoverview Define abstracao de no xml
 *
 * @author Robson de Souza Inacio robson@dbseller.com.br 
 * @author Ricardo Zucco          ricardo@dbseller.com.br
 * @version  $Revision: 1.1 $
 */

/**
 * @class DBXmlNode
 * @constructor    
 * @param {string} Nome do no a ser criado 
 */
function DBXmlNode(sNameP){ 


  var me          = this;
  this.aAtributes = new Array();
  this.aNodes     = new Array();
  this.iIndNode   = 0;
  this.iIndAtt    = 0;
  this.sStr       = "";
  this.sName      = sNameP;
  
  /**
   * Metodo adiciona objeto do tipo DBXmlAttribute
   * @param {DBXmlAttribute} no para ser adicionado ao array de nós
   */
  this.addAttribute = function (oAtribute){
    me.aAtributes[me.iIndAtt] = oAtribute;
    me.iIndAtt += 1;
  }
  
  /**
   * Metodo adiciona objeto do tipo DBXmlNode
   * @param {DBXmlNode} no para ser adicionado ao array de nós
   */
  this.addNode   = function (oNode){
    me.aNodes[me.iIndNode] = oNode;
    me.iIndNode += 1;
  }
  
  /**
   * Metodo retorna string XML com o conteudo do xml
   */   
  this.toStrXml = function () {

    // tag de abertura do no com atributos
    me.sStr = "<"+me.sName+" ";
    for (var i = 0; i < me.aAtributes.length; i++) {
      me.sStr += me.aAtributes[i].toStrXml();
    }    
    me.sStr += " >\n";

    if (me.aNodes.length > 0){

      for (var i = 0; i < me.aNodes.length; i++) {
        me.sStr += me.aNodes[i].toStrXml()+"";
      }   

    }

    me.sStr += "</"+me.sName+">\n";
    return me.sStr;

  }

}

/** 
 * @fileoverview Define classe para criar string Xml de atributo
 *
 * @author Robson de Souza Inacio robson@dbseller.com.br 
 * @author Ricardo Zucco          ricardo@dbseller.com.br
 * @version  $Revision: 1.1 $
 */

/**
 * @class DBXmlAttribute
 * @constructor  
 * @param {string} sNameAtt  Nome do atributo a ser criado
 * @param {string} sValueAtt Valor do atributo a ser criado    
 */
function DBXmlAttribute(sNameAtt,sValueAtt){

  var me      = this;
  this.sName  = sNameAtt;
  this.sValue = sValueAtt;
  this.sStr   = "";
  
  /**
   * Metodo retorna string XML com o conteudo do xml
   */
  this.toStrXml = function() {
    me.sStr =" "+me.sName+"=\""+me.sValue+"\"";
    return me.sStr;    
  }

}
