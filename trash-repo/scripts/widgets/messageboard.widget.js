/** 
 * @fileoverview Esse arquivo define classes para a constru��o de um messagboard
 * para ajuda ao usu�rio
 *
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version  $Revision: 1.4 $
 */

const MSG_INFO    =  1;
const MSG_WARNING =  2;
const MSG_ERROR   =  3;
/**
 * cria um objeto messagboard
 * @class       messageBoard 
 * @constructor
 * @deprecated
 * @param {string} sId    nome do Objeto
 * @param {string} sTitle Titulo do messagBoard
 * @param {string} sHelp Texto de ajuda ao usu�rio 
 * @param {HTMLElement} oElementToAppend Elemento HTML aonde o messageBoard sera Anexado 
 * @example 
 * var oMessageBoard = new messageBoard('msgboard1','Teste','Marque os itens',$('body'));
 *     oMessageBoard.show();
 */
function messageBoard (sId, sTitle, sHelp, oElementToAppend) {

  if (sId == null) {
    this.id = "messageBoard"+Math.random();
  }
  this.id = sId;
  
  this.divContent    = document.createElement("DIV");
  this.divContent.id = this.id;
  with (this.divContent.style) {
  
     borderBottom    = "2px groove white";
     padding         = "5px";
     backgroundColor = "white";
     width           = "99%"
     height          = "50px";
     textAlign       = "left";
     
  }
  if (sTitle == null) {
    sTitle = "";
  } 
  if (sHelp == null) {
    sHelp = "";
  }
  
  this.tableInfo = document.createElement("TABLE");
  this.tableInfo.width="100%";
  var oRow       = document.createElement("TR");
  var oCellTitle = document.createElement("TD");
  oCellTitle.id  = this.id+'_title';
  with (oCellTitle.style) {
    fontWeight = "bold";  
  }
  
  oCellTitle.innerHTML = sTitle
  oRow.appendChild(oCellTitle);
  var oCellImg     = document.createElement("TD");
  oCellImg.style.textAlign = "right";
  oCellImg.rowSpan = 2;
  cType = null;
  
  if  ( cType != null) {
  
    var oImgHelp    = document.createElement("IMG");
    oImgHelp.border = 0; 
    if (cType == MSG_INFO) {
      oImgHelp.src = 'dbportal2/imagens/info.png';
    } else if (cType == MSG_WARNING) {
      oImgHelp.src = 'dbportal2/imagens/info.png';
    } else if (cType == MSG_ERROR) {
      oImgHelp.src = 'dbportal2/imagens/info.png';
    }
    oCellImg.appendChild(oImgHelp);
    
  }
  
  oRow.appendChild(oCellImg);
  this.tableInfo.appendChild(oRow);
  var oRowHelp  = document.createElement("TR");
  var oCellHelp       = document.createElement("TD");
  oCellHelp.innerHTML = sHelp
  oCellHelp.id  = this.id+'_help';
  with (oCellHelp.style) {
     textIndent= "15px";
  }
  
  oRowHelp.appendChild(oCellHelp);
  this.divContent.appendChild(this.tableInfo);
  this.tableInfo.appendChild(oRowHelp);

	if (oElementToAppend ==  null) {
	  document.body.insertBefore(this.divContent,document.body.elements[0]);
	} else {
	  oElementToAppend.insertBefore(this.divContent, oElementToAppend.childNodes[0]);
	}   
  /**
   * Define o titulo do messageBoard 
   * @param {string} sTitle texto com o Titulo 
   * @return void
   */
  this.setTitle = function (sTitle) {
    $(this.id+'_title').innerHTML = sTitle;
  }
  
  /**
   * Define a mensagem de ajuda do messageBoard 
   * @param {string} sTitle texto com a ajuda 
   * @return void
   */ 
  this.setHelp = function (sHelp) {
    $(this.id+'_help').innerHTML = sHelp; 
  }
  
  /**
   * Mostra o MessageBoard 
   * @return void
   */
  this.show = function (oElement) {
    $(this.id).style.display="";
  }
  /**
   * Esconde o MessaBoard 
   * @return void
   */
  this.hide = function () {
    $(this.id).style.display="none";
  } 
}
