/**
 * Dependencias
 */
require_once('scripts/widgets/DBAncora.widget.js');
require_once('scripts/strings.js');
require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
/**
 * @fileoverview Cria janela com lista de itens para download
 *
 * @author Rafael Nery      <rafael.nery@dbseller.com.br>
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */

/**
 * DBDownload - Construtor da classe
 *
 * @return void
 */
var DBDownload = function() {

  /**
   * Array com objeto contendo descricao e link dos arquivos para download
   */
  this.aFiles         = new Array();

 /**
  * Agrupadores de Url's dos arquivos
  * @type {Object}
  */
  this.aGroups        = new Object();
  /**
   * Instancia do windowAux
   * @type windowAux
   */
  this.oWindowAux     = null;

  /**
   * callBack para executar o download
   */
  this.fDownload      = js_arquivo_abrir;

  /**
   * Objeto contendo os componentes de HTML
   */
  this.oElementosHTML = new Object();

  /**
   * Objeto de Destino da Escrita
   */
  this.oTarget       = null;

  /**
   * Objeto do MessageBoard
   */
  this.oElementosHTML.oDivMessageBoard = document.createElement("DIV");

  /**
   * Objeto do Conteudo da Aux
   */
  this.oElementosHTML.oDivList         = document.createElement("DIV");
}

/**
 * Adiciona arquivo a lista para download
 *
 * @param string sUrl   - URL do arquivo
 * @param string sLabel - titulo do download
 */
DBDownload.prototype.addFile = function(sUrl, sLabel, sIdGroup) {

  var oFile = new Object();

  oFile.sLabel = sLabel;

  if ( js_empty(sLabel) ) {
    oFile.sLabel = sUrl;
  }

  oFile.sUrl = sUrl;

  this.aFiles.push(oFile);

  if ( js_empty(sIdGroup) ) {
    return;
  }

  if ( !this.aGroups[sIdGroup] ) {
    throw "Grupo não Existe";
  }

  this.aGroups[sIdGroup].aFiles.push(oFile);
  return;
}

/**
 * Retorna array com os arquivos
 *
 * @return Array
 */
DBDownload.prototype.getFiles = function() {
  return this.aFiles;
}

/**
 * Adiciona grupo para lista de Arquivos
 * @returns void
 */
DBDownload.prototype.addGroups = function( sIdGroup, sTitulo ) {

  var oSelf = this;

  this.aGroups[sIdGroup] = {
    sId    : sIdGroup,
    sLabel : sTitulo,
    aFiles : new Array()
  }
  return;
}

/**
 * Cria WindowAux para listar os Arquivos
 * @returns void
 */
DBDownload.prototype.createWindow = function() {

  var oSelf = this;

  this.oWindowAux = new windowAux(null, 'Arquivos para Download', 350, 300);
  this.oWindowAux.show();

  $( this.oWindowAux.idWindow ).appendChild(this.oElementosHTML.oDivMessageBoard);
  $( this.oWindowAux.idWindow ).appendChild(this.oElementosHTML.oDivList);

  var oMessageBoard = new DBMessageBoard('msgboard1',
                                         ' Arquivos para Download.',
                                         '',
                                         this.oElementosHTML.oDivMessageBoard);
  oMessageBoard.show();

  this.oElementosHTML.oDivList.style.height    = 205;
  this.oElementosHTML.oDivList.style.border    = "2px groove #cccccc";
  this.oElementosHTML.oDivList.style.padding   = "3px";
  this.oElementosHTML.oDivList.style.margin    = "3px";
  return this.oElementosHTML.oDivList;
}

/**
 * Mostra lista de Arquivos
 * @returns void
 */
DBDownload.prototype.show = function( oTarget ) {

  var oSelf         = this;
  var aGroups       = {'DBDownloadDefault' : { aFiles : this.aFiles } } ;
  var iGroupsLength = Object.keys(this.aGroups).length;

  if ( js_empty(oTarget) ) {

    if ( !(this.oWindowAux instanceof windowAux) ) {
      this.oTarget = this.createWindow();
    }

  } else {
    this.oTarget = oTarget;
  }

  if ( iGroupsLength > 0 ) {

    aGroups = this.aGroups;
    /**
     * Remove borda externa da div quando possuir grupos
     */
    this.oElementosHTML.oDivList.style.border    = "0px solid";
  }

  for ( var sIdGroup in aGroups ) {

    var oGroup       = aGroups[sIdGroup];
    var aFiles       = oGroup.aFiles;
    var oGroupTarget = this.oTarget;

    /**
     * Adiciona agrupadores para listagem de arquivos
     */
    if( oGroup.sId ){

      oGroupTarget           = document.createElement("fieldset");
      oGroupTarget.className = 'separator';
      var oGroupLegend       = document.createElement("legend");
      oGroupLegend.innerHTML = oGroup.sLabel;
      oGroupTarget.appendChild( oGroupLegend );
      this.oTarget.appendChild( oGroupTarget );
    }

    for ( var iFile = 0; iFile < aFiles.length; iFile++ ) {

      var oFile      = aFiles[iFile];
      var oEspacador = document.createElement("br");
      var oLink      = new DBAncora( oFile.sLabel, oFile.sUrl );

      oLink.onClick( function() {

        oSelf.fDownload( this.sUrl.urlEncode() );
        return false;
      });

      oLink.show( oGroupTarget );
      oGroupTarget.appendChild( oEspacador );
    }
  }

  if ( this.oWindowAux instanceof windowAux ) {
    this.oWindowAux.show();
  }
}


/**
 * Limpa a Lista de arquivos e fecha a janela
 * @returns void
 */
DBDownload.prototype.clear = function() {

  this.aFiles = new Array();
  this.close();
  return;
}

/**
 * Fecha a janela de Arquivos
 * @returns void
 */
DBDownload.prototype.close = function() {

  this.oWindowAux.destroy();
  return;
}
