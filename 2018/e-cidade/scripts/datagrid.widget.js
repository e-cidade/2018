require_once('estilos/grid.style.css');
/**
 * @fileoverview Esse arquivo define classes para a construção de uma datagrid
 * para visualizaçao de dados tabulares
 *
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version  $Revision: 1.92 $
 */

/**
 * classe que disponibiliza um datagrid
 *
 * @class Classe do tipo Datagrid cria uma datagrid com diversas opcoes de visualizacao
 * @constructor
 * @requires tableCell
 * @requires tableRow
 * @requires tableHeader
 * @param {string} sName Nome do datagrid
 */
function DBGrid(sName) {

  /**
   * Nome da datagrid
   * @type string
   * @private
   */
  this.sName           = sName;

  /**
   * string com a definicao do header do datagrid
   * @type string
   * @private
   */
  this.sHeader         = '';

  /**
   * string com a definicao do body do datagrid
   * @type string
   * @private
   */

  this.sTbody          = '';
  /**
   * numero de linhas que o objeto possui.
   * @type int
   * @public
   */
  this.iRowCount       = new Number(0);

  /**
   * tamanho de cada de cada Celula
   * @type array
   * @public
   */
  this.aWidths         = new Array();

  /**
   * Array com o alinhamento de cada Celula
   * @type array
   * @public
   */
  this.aAligns         = new Array();
  /**
   * define se a grid tera uma celula com checkbox
   * @type bool
   * @private
   */
  this.hasCheckbox     = false;
  /**
   * valor padrao do checkbox
   * @type string
   * @private
   */
  this.checkBoxValue   = '';

  /**
   * callback que sera executada no evento click do checkbox
   * @type string
   * @public
   */
  this.checkBoxClick   = 'selectSingle';
  /**
   * array de linhas que a datagrid possui
   * @type array
   * @private
   */
  this.aRows           = new Array();

  /**
   * Nome da instancia do datagrid
   * @type string
   * @public
   */
  this.nameInstance    = '';

  /**
   * quantidade de celulas que a coluna possui
   * @type int
   * @private
   */
  this.hasSelectAll    = true;

  /**
   * quantidade de celulas que a coluna possui
   * @type int
   * @private
   */
  var iNumCells        = 0;

  /**
   * permite escolher as colunas
   * @private
   * @type bool
   */
  this.lAllowSelectCol = false;

  /**
   * nome da imagem para selecaio de coluna
   * @type integer
   * @private
   */
  this.iHeight = 150;

  /**
   * nome da imagem para selecaio de coluna
   * @type string
   * @private
   */
  var sImgSelection    = "espaco.gif";

  /**
   * callback paraa seleção de colunas
   * @type string
   * @private
   */
  var sCallBackCols    = "return false";

  /**
   * Caminho das imagens que a grid utiliza padrao ./imagens
   * @type string
   * @public
   */
  this._IMGPATH        = "imagens";

  /**
   * header da datagrid
   * @type array
   * @private
   */
  this.aHeaders        = new Array();


  /**
   * Totalizadores das colunas
   * @type boolean
   * @private
   */
  this.hasTotalizador  = false;

  this.sCellClass       = "";
  this.sRowClass        = "";

  this.bAlinhaFooter    = true;

  this.gridContainer    = null;

  this.iHeaderLineModel = 0;

  /**
   * Guarda o tamanho inical da grid.
   */
  this.gridContainerWidth = null;

  /**
   * Armazena a propriedade oFooter criada quando a grid tem um rodapé de totalizadores
   * @type Object tableRow
   */
  this.oFooter;

  this.fCallBackSelectAll = null;

  /**
   * Callback que será chamado apos a seleção de um checkbox
   * @type {function}
   */
  this.fnCallbackSelectSingle = null;

  /**
   * Seta o callback ao selecionar todos os itens1
   * @param function fCallBackSelectAll
   */
  this.setCallBackSelectAll = function(fCallBackSelectAll) {
    this.fCallBackSelectAll = fCallBackSelectAll;
  }

  /**
   * Callback para execucao apos a seleção do checkbox
   * @param fnCallbackSelectSingle
   */
  this.setCallbackSingle = function (fnCallbackSelectSingle) {
    this.fnCallbackSelectSingle = fnCallbackSelectSingle;
  }

  var me = this;


   /**
   * Metodo para alinha o rodapé se este existir
   *
   * @param {Object} Objéto da tabela do Cabeçalho
   * @return void
   *
   */
  this.alinhaFooter = function (oHeader) {


    var oFooter = $("table"+this.sName+"footer");

    if (oHeader instanceof Object) {
      oFooter.style.width = oHeader.scrollWidth;
    } else {

      var oHeader = $("table"+this.sName+"header");
      var iWidthContainer = me.gridContainerWidth;
      //Garante que todas as tabelas tenham o mesmo tamanho(lagura) em px
      oFooter.style.width = iWidthContainer+"px";
    }


    for (var iCont = 0; iCont < me.aHeaders.length; iCont ++) {

      var iSizeCell = oHeader.rows[me.iHeaderLineModel].cells[iCont].scrollWidth;
      if ( oHeader.rows[me.iHeaderLineModel].cells[iCont].style.display != "none" ) {

        if (oFooter.rows[0].cells[iCont].scrollWidth == oHeader.rows[me.iHeaderLineModel].cells[iCont].scrollWidth) {
          continue;
        }
        if (me.hasCheckbox){
          if (iCont == 0){
            oFooter.rows[0].cells[iCont].style.width = (iSizeCell - 1) +'px';
          }else{
            oFooter.rows[0].cells[iCont].style.width = (iSizeCell - 3) +'px';
          }
        }else{
          oFooter.rows[0].cells[iCont].style.width = (iSizeCell - 3) +'px';
        }
      }
    }// end for
    this.oFooter = oFooter;

  }


   /**
   * Retorna um {integer} com a primeira linha que estivér visível.
   *
   * @param {Object} Objéto da tabela do Body
   * @return Integer
   *
   */
  this.getFirstLineValid = function(oBody){


    var iFirstLineValid = 0;
    var iLinhasColspan  = 0;

    // Percorre as linhas da tabela localizando e retornando a primeira linha visível.
    for (var iCont = 0; iCont < oBody.rows.length; iCont++) {

      if (oBody.rows[iCont].style.display != "none") {

          iLinhasColspan  = $$("#"+oBody.rows[iCont].id+" > td[colspan]").length;
          if ( iLinhasColspan > 0 ) {
            continue;
          }
          iFirstLineValid = iCont;
          break;
      }
    }

    return iFirstLineValid;
  }


  /**
   * Garante que as colunas permaneçam alinhadas
   *
   * @param {Object} Objéto da tabela do header
   * @param {Object} Objéto da tabela do Body
   * @param {Object} Objéto da tabela do Footer
   * @return void
   *
   */
  this.alignsCells = function(oHeader, oBody, oFooter) {

    if (!oBody.rows[0]) {
      return true;
    }

    var iFirstLineValid = me.getFirstLineValid(oBody);

    // Estabelece um tamanho fixo para as colunas.
    oBody.style.tableLayout   = "fixed";
    oHeader.style.tableLayout = "fixed";
    // Itera sobre todas as colunas criadas
    for (var iCont = 0; iCont < me.aHeaders.length; iCont++) {

      // Garante que só será trabalhado sobre as colunas visiveis
      if (oBody.rows[iFirstLineValid].cells[iCont].style.display != "none" &&
        oHeader.rows[me.iHeaderLineModel].cells[iCont].style.display != "none" ) {

        //Se oa coluna x do Header e a Conluna x do Body for do mesmo tamanho, passa para o próxima volta do laço
        if (oBody.rows[iFirstLineValid].cells[iCont].scrollWidth == oHeader.rows[me.iHeaderLineModel].cells[iCont].scrollWidth) {
          continue;
        }
        var oCelulaBody          = oBody.rows[iFirstLineValid].cells[iCont];
        var oCelulaHeader        = oHeader.rows[me.iHeaderLineModel].cells[iCont];

        oCelulaBody.style.width = (oCelulaHeader.getWidth()-3)+"px";
      }
    } // end for
    if (this.hasTotalizador) {
      me.alinhaFooter(oHeader);
    }
  }


  this.setHeaderModel = function(iHeaderLineModel) {
     me.iHeaderLineModel = iHeaderLineModel;
  }
  /**
   * Instancia os objétos das tabelas (Header, Body e Footer) e seta o tamnho do Container em cada uma delas,
   * garantindo assim que todas tenham o mesmo tamanho.
   * Garante que as tabelas não iram mudar de tamanho e realiza chamada para método alinhar as colunas.
   *
   * @return void
   *
   */
  this.resizeCols = function() {

     /**
      * Caso a grid não seja renderizada antes do carregamento, o valor de "me.gridContainerWidth" será 0.
      * Esse teste verifica este caso e atribui seu width inicial.
      */
     if (me.gridContainerWidth <= 0) {
       me.gridContainerWidth = me.gridContainer.scrollWidth;
     }
     var oHeader = $("table"+this.sName+"header");
     var oBody   = $(this.sName+"body");
     var oFooter = $("table"+this.sName+"footer");

     // Tamanho do container pai da grid
     oBody.style.width    = me.gridContainerWidth+"px";
     oHeader.style.width  = me.gridContainerWidth+"px";
     me.alignsCells(oHeader, oBody, oFooter);
  }


  /**
   * adiciona uma linha ao corpo da grid
   * @param {Array} aRow array com as colunas da linha
   * @param {boolean} lRender renderiza apos adicionar a coluna
   * @param {boolean} Se o checkbox está desabilitado
   * @return void
   * @see tableRow
   */
  this.addRow =  function(aRow, lRender, lDisabled, lChecked) {

    if (lDisabled == null) {
      lDisabled = false;
    }
    if (lChecked == null) {
        lChecked =  false;
    }
    if (lRender == null) {
     lRender = false;
    }
    var iNumCol = 0;
    if ((aRow instanceof Array)) {

      oRow = new tableRow(this.sName+'row'+this.sName+iRowCount);

      if (this.hasCheckbox) {

        var sDisabled = "";
        var sChecked  = "";
        if (lDisabled) {
          sDisabled  = " disabled ";
        }
        if (lChecked) {

          sChecked  = " checked ";
          oRow.classChecked = "marcado";
          oRow.isSelected = true;

        }
        var sCheckbox = "";
        sCheckbox    += "<input type='checkbox' id='chk"+this.sName+aRow[this.checkBoxValue]+"' "+sDisabled+ " "+sChecked;
        sCheckbox    += "      onclick=\""+this.nameInstance+".selectSingle(this,'"+this.sName+"row"+this.sName+iRowCount+"',"+this.nameInstance+".aRows["+iRowCount+"])\"";
        sCheckbox    += "      value='"+aRow[this.checkBoxValue]+"' class='checkbox"+this.sName+"' style='height:12px; ' >";
        oRow.addCell(new tableCell(sCheckbox, this.sName+'row'+iRowCount+'checkbox',21, 'checkbox'));
        iNumCol++;

     }

     //Iteramos sobre as posições do array Criado.
     for (var iLength = 0; iLength < aRow.length; iLength++) {

        sId        = this.sName+'row'+iRowCount+'cell'+iLength;
        sCellWidth = '';
        if (this.aWidths[iLength]) {
          sCellWidth = this.aWidths[iLength];
        }
        var oCell = new tableCell(aRow[iLength] ,sId, sCellWidth, 'cell');

        if (this.aAligns[iLength]) {
          oCell.setAlign(this.aAligns[iLength]);
        }

        if (!this.aHeaders[iNumCol].lDisplayed) {
           oCell.lDisplayed = false;
        }

        oRow.addCell(oCell);
        iNumCol++;
      }

      this.aRows[iRowCount] = oRow;
      iRowCount++;

    } else if ((aRow instanceof tableRow)) {

     if (this.hasCheckbox) {

        var sCheckbox = "";
        sCheckbox    += "<input type='checkbox' id='chk"+this.sName+aRow.aCells[0].getContent()+"'";
        sCheckbox    += "      onclick=\""+this.nameInstance+".selectSingle(this,'"+aRow.sId+"',"+this.nameInstance+".aRows["+iRowCount+"])\"";
        sCheckbox    += "      value='"+aRow.aCells[0].getContent()+"' class='checkbox"+this.sName+"' style='height:12px'>";
        aRow.addFirstCell(new tableCell(sCheckbox,this.sName+'row'+iRowCount+'checkbox', 21, 'checkbox'));

     }
     this.aRows[iRowCount] = aRow;
     iRowCount++;
    }

    if( lRender == true ){
      this.renderRows();
    }
  }

  /**
   * define as colunas do Header da grid, criando para cada no da array um objeto do tipo tableHeader
   * @param {array} aHeader array com as colunas da grid
   *
   */
  this.setHeader = function(aHeader) {

    if (!(aHeader instanceof Array)) {

      alert('propriedade aHeader deve ser um array');
      return false;

    }
    /*
     * Caso a grid possui checkbox, adicionamos uma coluna a mais na grid.
     */
    var sHeader = "";
    if (this.hasCheckbox) {

      //this.sHeader += "<td class='table_header'>";
      if (this.hasSelectAll) {

        sHeader += "<input type='checkbox'  style='display:none'";
        sHeader += "       id='mtodositens"+this.sName+"'><b>";
        sHeader += "      <a id=\""+this.nameInstance+"SelectAll\" onclick=\""+this.nameInstance+".selectAll('mtodositens"+this.sName+"','checkbox"+this.sName+"','"+this.sName+"row"+this.sName+"')\"";
        sHeader += "         style='cursor:pointer; '>M</a></b>";


      } else {
        sHeader += "&nbsp;";
      }

      iNumCells++;
      //this.sHeader += "</td>";
      var oHeader = new tableHeader(sHeader, '21px', iNumCells, 'checkbox', 'checkbox');
      this.aHeaders.push(oHeader);
    }
    /*
     * Percorremos os arrays da grid e criamos a string com as tags html
     */
    for (var iLength = 0; iLength < aHeader.length; iLength++) {

      iNumCells++;
      sCellWidth = '';
      if (this.aWidths[iLength]) {
        sCellWidth = this.aWidths[iLength];
      }
      var oHeader = new tableHeader(aHeader[iLength], sCellWidth, iNumCells, 'cell', iLength);
      this.aHeaders.push(oHeader);
    }

    //this.aHeader  = aHeader;

  }

  /**
   * Renderiza a grid no nó especificado no parametro
   * @param  {HTMLNode} oNode onde a grid sera incluida.
   * @return void
   * @type   void
   */
  this.show = function (oNode) {

    var sGrid = "<div class='gridcontainer' id='grid"+this.sName+"'>";
    sGrid    += "<div class='header-container' >";
    sGrid    += "<div class='grid-resize'>";
    sGrid    += "<img src='"+this._IMGPATH+"/"+sImgSelection+"' border='0' onclick='"+sCallBackCols+"'>";
    sGrid    += "<div style='clear: both;'></div>" // LIMPA O FLOAT DA IMAGEM
    sGrid    += "</div>"  //FECHA DIV "grid-resize"
    sGrid    += "<table class='table-header' rel='ignore-css'  id='table"+this.sName+"header'>";
    sGrid    += this.renderHeader();
    sGrid    += "</table>"; // FECHA TABELA DO HEADER table-header
    sGrid    += "</div>";   // FECHA DIV "header-container"
    // FIM DO HEADER    ---    INICIO DO BODY

    var height = me.iHeight;

    if ( Number.isInteger(me.iHeight) ) {
      height = me.iHeight + "px";
    }

    sGrid    += "<div id='body-container-"+this.sName+"' class='body-container' style='height:"+height+";'>";
    sGrid    += "<table  class='table-body'  id='"+this.sName+"body'>";
    sGrid    += this.renderRows(true, false);
    sGrid    += "</table>"; // FECHA TABELA DO HEADER table-body
    sGrid    += "</div>";    // FECHA DIV "body-container"
    // FIM DO BODY     ----    INICIO DO FOOTER
    sGrid    += "<div class='footer-container' >";
    sGrid    += "<table class='table-footer'  id='table"+this.sName+"footer'>";
    if (this.hasTotalizador) {
      sGrid+= this.renderFooter();
    }
    sGrid    += "<tr style='text-align:left;'>";
    sGrid    += "<td colspan='"+(this.aHeaders.length+2)+"'><div style='border:1px inset white;height:100%;padding:2px'>";
    sGrid    += "<span> Total de Registros:</span><span style='color:blue;padding:3px' id='"+this.sName+"numrows'>0</span>";
    sGrid    += "<span style='border-left:1px inset #eeeee2' id='"+this.sName+"status'>&nbsp;</span>&nbsp;";
    sGrid    += "</div></td></tr>";
    sGrid    += "</table>"; //  FECHA TABLE "table_footer"
    sGrid    += "</div>";   //  FECHA DIV "footer-container"
    //
    sGrid    += "</div>";   //  FECHA DIV "container"



    /*
     * Se foi escolhido mostrar as colunas,
     * mudamos a imagem, e criamos o dropdown com as colunas a serem escolhidas;
     */
    me.gridContainer      =  oNode;
    me.gridContainerWidth =  oNode.scrollWidth - 25;
    if (this.lAllowSelectCol) {
      sGrid += this.drawSelectCol();
    }

    oNode.innerHTML = sGrid;

    $("table"+this.sName+"header").style.width = me.gridContainerWidth;
    $(this.sName+"body").style.width           = me.gridContainerWidth;
    $("table"+this.sName+"footer").style.width = me.gridContainerWidth;

     // Subtrai o tamanho do scroll

    if (this.hasTotalizador) {

      if (me.bAlinhaFooter){

        this.alinhaFooter();
        me.bAlinhaFooter = false;
      }
    }

  }

  this.renderFooter = function() {

    var sFooter = "<tr>";
    for ( var i = 0; i < this.aHeaders.length; i++) {

      if(me.aHeaders[i].lDisplayed != false){
        if (this.hasCheckbox) {
          if (i == 0){

            sFooter += "<td class='cell' style=' width:20px; padding:0; margin:0; ' class='gridtotalizador' id='TotalForCol" + i
                        + "'></td>";
          }else{

            sFooter += "<td class='gridtotalizador' style='text-align:right;' id='TotalForCol" + i
                        + "'></td>";
          }
        }else{

          sFooter += "<td class='gridtotalizador' style='text-align:right;' id='TotalForCol" + i
                      + "'></td>";
        }
      } else {
        sFooter += "<td class='cell' style='display:none; text-align:right;' class='gridtotalizador' id='TotalForCol" + i
                + "'></td>";
      }
    }

    sFooter += "</tr>";
    return sFooter;
  }
  /**
   * Define o tamanho das celulas
   * @param  {array} aWidths array com o tamanho de cada celula ();
   * @return Void
   */

  this.setCellWidth = function (aWidths) {
    this.aWidths = aWidths;
  }

  /**
   * define o alinhamento global das celulas
   * @param  {array} aAligns array com oAlinhamento de cada celula;
   * @return Void
   * @type void
   */

  this.setCellAlign = function(aAligns) {
    this.aAligns = aAligns;
  }

  /**
   * Seta se a grid mostrara um checkbox, no inicio de cada linha
   * @param integer iCell Posição da array que contem o valor da checkbox (sera usado com indice.)
   */
  this.setCheckbox = function(iCell) {

     this.hasCheckbox   = true;
     this.checkBoxValue = iCell;

  }
  /**
   * Define a altura
   * @param {integer} iHeight define a altura
   * @return void
   */
  this.setHeight = function (iHeight) {
     this.iHeight = iHeight;
  }

  /**
   * retorna as linhas da grid que estao marcadas como selecionadas
   * @param {string} sTipoRetorno define como sera o retorno do metodo. caso object retorna os objetos tableRow selecionados
   * caso array, retornara um array com os valores das linhas selecionadas.
   *
   * @return array
   */
  this.getSelection = function (sTipoRetorno) {

    if (sTipoRetorno == null ) {
       sTipoRetorno = "array";
    }
    var aSelecionados = new Array();
    if (sTipoRetorno == "array") {
      for (var iItensLength = 0; iItensLength < this.aRows.length; iItensLength++) {

        with (this.aRows[iItensLength]) {
          if (isSelected) {

            var aCellsCollection = new Array();
            //percorremos a lista de celulas da linha e retornos seus conteudos.
            for (var iTotCells = 0; iTotCells < aCells.length; iTotCells++) {

               var sValue = ''
               with (aCells[iTotCells]) {
                 aCellsCollection.push(getValue());
               }
            }
            aSelecionados.push(aCellsCollection);
          }
        }
      }
    } else if (sTipoRetorno == "object") {

      for (var iItensLength = 0; iItensLength < this.aRows.length; iItensLength++) {

        if (this.aRows[iItensLength].isSelected) {
          aSelecionados.push(this.aRows[iItensLength]);
        }
      }
    }
    return aSelecionados;
  }

  /**
   *
   * Busca elementos pela className
   * @param {string} searchClass nome da classe que deve ser pesquisada
   * @param {string} domNode nó que deve iniciar a busca default é document.
   * @param {string} tagName tag que deve ser verificada default "*"
   *
   * @return array com todos os objetos encontrados
   */
  this.getElementsByClass = function ( searchClass, domNode, tagName) {

    if (domNode == null) {
      domNode = document;
    }

    if (tagName == null) {
      tagName = '*';
    }

    var el = new Array();
    var tags = domNode.getElementsByTagName(tagName);
    var tcl = " "+searchClass+" ";
    for (i=0,j=0; i<tags.length; i++) {

      var test = " " + tags[i].className + " ";
      if (test.indexOf(tcl) != -1) {
         el[j++] = tags[i];
       }
    }
    return el;
  }

  /**
   * Seleciona todos as checkboxes da grid
   * @param {string} idObjeto id do objeto que controla se as checkboxes estao marcadas ao nao
   * @param {string} sClasse nome classname css que deve ser marcada
   * @param {string} sLinha  padrao (id ex.: 'row' )do objeto que deve ser marcardo ;
   */
  this.selectAll = function(idObjeto, sClasse, sLinha) {

    var obj = document.getElementById(idObjeto);
    if (obj.checked){
      obj.checked = false;
    } else{
      obj.checked = true;
    }

    itens = this.getElementsByClass(sClasse);
    for (var i = 0;i < itens.length;i++){

      if (itens[i].disabled == false){
        if (obj.checked == true){

          itens[i].checked=true;
          this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i]);

        } else {

          itens[i].checked=false;
          this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i]);

        }
      }
    }

    if (this.fCallBackSelectAll) {
      this.fCallBackSelectAll();
    }
  };

  this.renderizar = function() {

    var aLinhasImprimir = [];

    for (var iRow = 0; iRow < this.aRows.length; iRow++) {

      var iTotalCelulas = this.aRows[iRow].aCells.length;
      var aLinha = [];
      var iIndice = 0;
      for (var iCell = 0; iCell < iTotalCelulas; iCell++) {

        if (iCell === 0 && this.hasCheckbox) {
          continue;
        }
        aLinha[iIndice] = this.aRows[iRow].aCells[iCell].getValue();
        iIndice++;
      }
      aLinhasImprimir.push(aLinha);
    }

    this.clearAll(true);
    aLinhasImprimir.each(
      function (aCelula, iIndice) {
        me.addRow(aCelula);
      }
    );
    this.renderRows();
  };

  /**
   * Seleciona uma linha da grid
   *
   * @param {object} oCheckbox qual checkbox foi clicado
   * @param {string} sRow Id da row
   * @param {tableRow} oRow objeto de referencia da linha
   * @type void
   * @return {boolean}
   */
  DBGrid.prototype.selectSingle = function (oCheckbox,sRow,oRow) {

    if (oCheckbox.checked) {

      $(sRow).removeClassName(oRow.getClassName());
      $(sRow).addClassName('marcado');
      oRow.isSelected   = true;
    } else {

      $(sRow).removeClassName('marcado');
      $(sRow).addClassName(oRow.getClassName());
      oRow.isSelected   = false;
    }

    if (this.fnCallbackSelectSingle !== null) {
      this.fnCallbackSelectSingle(oCheckbox, sRow, oRow);
    }
    return true;
  }


  this.getRowById = function(sRowId) {

    var oRowFind = false;
    me.aRows.each(function(oRow, iSeq) {

      if (oRow.sId == sRowId) {

        oRowFind = oRow;
        return;
      }
    });
    return oRowFind;
  };

  /**
   * reseta as informações da grid,
   * @param {bool} lDeleteRows se exclui as linhas cadastradas. caso false apenas limpa o corpo da grid
   * @return void
   */
  this.clearAll = function (lDeleteRows) {

    if ($(this.sName+"body") != null) {
      $(this.sName+"body").innerHTML    = '';
    }

    if ($(this.sName+"numrows") != null) {
      $(this.sName+"numrows").innerHTML = 0;
    }

    if (lDeleteRows != null && lDeleteRows == true) {

      delete me.aRows;

      me.aRows  = new Array();

      iRowCount   = 0;
    }

    return true;
  }

  /**
   * Renderiza a linhas do grid.
   *
   * @param  {bool} lReturnString se retorna as linhas do grid como string
   * @return string com as linhas da grid
   */
  this.renderRows = function (lReturnString, lResizeColumns) {

    if (lResizeColumns == null) {
      lResizeColumns = true;
    }
    var sRows = '';
    for (var iRowLength = 0; iRowLength < me.aRows.length; iRowLength++) {

      if (!me.aRows[iRowLength]) {
        continue;
      }
       sRows += me.aRows[iRowLength].create();
       me.aRows[iRowLength].iRowNumber = iRowLength;
    }


    if (lReturnString) {
      return sRows;
    } else {

      this.clearAll(false);
      $(this.sName+"body").innerHTML    = sRows;
      $(this.sName+"numrows").innerHTML = this.aRows.length;
      if (lResizeColumns) {
        me.resizeCols();
      }
    }
  }
  /**
   * Mostra a opcao para marcar todos os checkboxes
   *
   * @param {bool} lSelectAll true para mostrar a opção
   * @see #getSelectAll
   * @return void;
   */
  this.setSelectAll = function (lSelectAll) {

    this.hasSelectAll = lSelectAll;
    return false;

  }
  /**
   * retorna se deve  mostrar opção para selecionar todos os checkbox
   * @see #setSelectAll
   * @type bool
   * @return boolean
   */
  this.getSelectAll = function () {
    return this.hasSelectAll;
  }

  /**
   * Realiza a soma de valores de toda uma coluna;
   * @param  {int} iCol Indice da coluna.
   * @param  {bool} lInSelection se leva em consideração apenas o que está selecionado
   * @type   Number
   * @return total da soma das colunas;
   */
  this.sum = function(iCol, lInSelection) {

    var nSomaTotal     = new Number(0);
    var aSelection = new Array();
    if (lInSelection == null) {
      lInSelection = true;
    }
    if (iCol != null) {

      if (lInSelection) {
         aSelection = this.getSelection();
      } else {
         aSelection = this.aRows;
      }
      /*
       * Percorremos todos os itens escolhidos.
       */
      for (var i = 0; i < aSelection.length; i++) {

        if (lInSelection) {

          if (aSelection[i][iCol]) {

            if (aSelection[i][iCol].indexOf(",") > 0) {
              nSomaTotal += js_strToFloat(aSelection[i][iCol]);
            } else {
              nSomaTotal += new Number(aSelection[i][iCol]);
            }
          }
        } else {

          if (aSelection[i].aCells[iCol].getValue().indexOf(",") > 0){;
            nSomaTotal += js_strToFloat(aSelection[i].aCells[iCol].getValue());
          } else {
            nSomaTotal += new Number(aSelection[i].aCells[iCol].getValue());
          }
        }
      }
    }
    return nSomaTotal;
  }

  /**
   * Define se a grid ira permitir quais colunas serao mostradas
   * @param {bool} lAllow true/false true mostra os campos para selecao
   * @return void
   */
  this.allowSelectColumns = function(lAllow) {

    if (lAllow == null) {
      lAllow = false;
    }
    if (lAllow) {

      sImgSelection        = "addcolumn.png";
      sCallBackCols        = this.nameInstance+".showSelectCol(this)";
      this.lAllowSelectCol = true;

    } else {

      sImgSelection        = "espaco.gif";
      sCallBackCols        = "return false";
      this.lAllowSelectCol = false;

    }
    return false;
  }

  /**
   * Renderiza o dropdown para selecionar as colunas
   * @type void
   * @return string
   */
  this.drawSelectCol = function() {

    var sDropDown  = "<div id='columns"+this.sName+"' class='draw-select-col' ";
    sDropDown     += "      style='position:absolute; visibility:hidden; top:0px; left:0px; text-align:left; padding:2px'>";
    for (var i = 0; i < this.aHeaders.length; i++) {

      iNumCol = i;
      if (this.hasCheckbox) {
        iNumCol += 1;
      }
      if (this.aHeaders[iNumCol]) {

        var sChecked = " checked ";
        if (!this.aHeaders[iNumCol].lDisplayed) {

        sChecked = "";
        }
        sDropDown += "<input type='checkbox' onclick='"+this.nameInstance+".showColumn(this.checked, "+(i+1)+")'";
        sDropDown += " id='"+this.sName+"_col"+iNumCol+"' "+sChecked+">";
        sDropDown += "<label for='"+this.sName+"_col"+iNumCol+"'>"+this.aHeaders[iNumCol].getContent()+"</label><br>";
      }
    }
    sDropDown     += "</div>";
    return sDropDown;
  }

  /**
   * mostra o dropDown para selecao dos campos
   *
   * @param {object} oSender qual objeto requisitou o dropdown;
   * @return void
   */
  this.showSelectCol = function (oSender) {

    el =  oSender;
    var x = 0;
    var y = el.offsetHeight;

    /*
     * calculamos a distancia do dropdown em relação a página,
     * para podemos renderiza-lo na posição correta.
     */
    while (el.offsetParent && el.id.toUpperCase() != 'wndAuxiliar') {

      if (el.className != "windowAux12") {

        x += new Number(el.offsetLeft);
        y += new Number(el.offsetTop);

      }
      el = el.offsetParent;

    }
    x += new Number(el.offsetLeft);
    y += new Number(el.offsetTop)+4;
    /*
     * Pegamos a largura do dropdown, e diminuimos da posiçao do cursors
     */
    var iTamObj = $('columns'+this.sName).scrollWidth-1;
    $('columns'+this.sName).style.left = x - iTamObj;
    $('columns'+this.sName).style.top  = y;
    /*
     * decidimos se mostramos ou não o dropdown, conforme o seu estado.
     */
    if ($('columns'+this.sName).style.visibility == 'visible') {
      $('columns'+this.sName).style.visibility = 'hidden';
    } else if ($('columns'+this.sName).style.visibility == 'hidden') {
      $('columns'+this.sName).style.visibility = 'visible';
    }
    return true;
  }

  /**
   * Mostra /esconde a coluna selecionada.
   *
   * @param {bool} lHide true para esconder /false para mostrar a coluna
   * @param {int} iWhatCol qual coluna afetada
   * @return true
   */
  this.showColumn = function(lHide, iWhatCol){

    if (this.hasCheckbox) {
      var oHiddenHeader = $("col"+(iWhatCol+1));
    } else{
      var oHiddenHeader = $("col"+iWhatCol);
    }
    if (lHide) {

      if (this.hasCheckbox) {
          this.aHeaders[iWhatCol].lDisplayed = true;
        } else {
          this.aHeaders[iWhatCol-1].lDisplayed = true;
        }
      oHiddenHeader.style.display = "";
      if ( typeof(iRowCount) != 'undefined' ){

        for (var ind = 0; ind < iRowCount; ind++){

          var oHiddenCol = $(this.sName+"row"+ind+"cell"+(iWhatCol-1));
          oHiddenCol.style.display = "";
        }
      }
      var oHiddenFooter = $('TotalForCol'+iWhatCol);
      if (this.hasTotalizador) {
        oHiddenFooter.style.display = "";
      }
    } else {
        if (this.hasCheckbox) {
          this.aHeaders[iWhatCol].lDisplayed = false;
        } else {
          this.aHeaders[iWhatCol-1].lDisplayed = false;
        }
      oHiddenHeader.style.display = "none";

      if ( typeof(iRowCount) != 'undefined' ) {

        for (var ind = 0; ind < iRowCount; ind++){

          var oHiddenCol = $(this.sName+"row"+ind+"cell"+(iWhatCol-1));
          oHiddenCol.style.display = "none";
        }
      }
      var oHiddenFooter = $('TotalForCol'+iWhatCol);
      if (this.hasTotalizador) {
        oHiddenFooter.style.display = "none";
      }

    }
    if ($('columns'+this.sName)) {
      $('columns' + this.sName).style.visibility = 'hidden';
    }

    this.resizeCols();

    return true;
  }

  /**
   * renderiza header da grid

   * @return string
   */
  this.renderHeader = function() {

    var sHeader = "";
    for (var i = 0; i < this.aHeaders.length; i++) {

      var iCol = i;
      if (this.hasCheckbox) {
        iCol--;
      }
      if (this.hasCheckbox && i == 0) {
        this.aHeaders[i].setWidth("21px");

      } else if (this.aWidths[iCol]) {
        this.aHeaders[i].setWidth(this.aWidths[iCol]);
      }
      sHeader += this.aHeaders[i].create();
    }
    return sHeader;
  }

  /**
   * define a propriedade numrows
   * @param {int} iNumnRows Novo valor para a propriedade
   * @return void
   */
  this.setNumRows = function(iNumRows) {
    $(this.sName+"numrows").innerHTML = new Number(iNumRows).valueOf();
  }

  /**
   * Retorna o valor definido para a propriedade numrows
   * @return int
   */
  this.getNumRows = function() {
    return new Number($(this.sName+"numrows").innerHTML).valueOf();
  }

  /**
   * define o texto da barra de status
   * @param {string} sText texto
   * @return void
   */

  this.setStatus = function(sText) {
    $(this.sName+'status').innerHTML = "&nbsp;"+sText;
  }

  /**
   * Retorna o texto da barra de status.
   * @return string
   */
  this.getStatus = function() {
    return $(this.sName+'status').innerHTML;
  }
  this.clickRow = function() {
    
  };
}

/**
 * Retorna as Linhas da Grid
 * @retuns tableRow[]
 */
DBGrid.prototype.getRows = function() {
  return this.aRows;
}

/**
 * Remove linhas da grid
 * - apos remover linhas, grid deve ser renderizada novamente, funcao renderRows
 *
 * @paran {Array} aLines - Lista dos indices das linhas na grid
 * @retrun {Void}
 */
DBGrid.prototype.removeRow = function(aLines) {

  aLines.sort().reverse();

  for (var iIndice = 0; iIndice < aLines.length; iIndice++) {
    this.aRows.splice(aLines[iIndice], 1);
  }
}

/**
 * Classe para criar um No do tipo TD
 * @param {string} sContent Conteudo da celula
 * @param {string} Sid    Campo identificador da celula
 * @param {string} sWidth tamanho da Celula;
 * @construct
 */
function tableCell(sContent, sId, sWidth, sClasse) {

  this.sEvents      = '';
  this.sStyleWidth  = '';
  this.sAlign       = 'left';
  this.lDisplayed   = true;
  this.lDisabled    = false;
  this.sStyle       = "";
  this.aClassName   = new Array();
  var sStyleDisplay = "";
  var me            = this;
  this.lUnica       = false;
  this.iColspan     = 1;
  this.aEventos     = [];

  if (sWidth != '' && typeof(sWidth) != 'undefined') {
    this.sStyleWidth = "width:"+sWidth+";";
  }

  this.sId     = sId;
  this.content = sContent;

  /**
   * Adiciona uma class na TD através do Id passado.
   */
  this.addClassName = function(sClass){

    if ($(me.sId)) {
      $(me.sId).addClassName(sClass);

    }
    me.aClassName.push(sClass);
  }

  /**
   * Remove a class na TD através do ID passado
   */
  this.removeClassName = function( sClass){
    if ($(me.sId)) {
      $(me.sId).removeClassName(sClass);
    }
    for (var ind = 0; ind < me.aClassName.length; ind ++){
      if ( me.aClassName[ind] == sClass){
        me.aClassName[ind] ="";
      }
    }
  }

   /**
    * renderiza a TD m em forma de uma string
    * @return string
    */
  this.create = function () {

    if (!me.lDisplayed) {
     sStyleDisplay = "display: none";
    }
    if (this.lUnica) {
      this.sStyleWidth = "100%";
    }
    me.aClassName.each(function(sClass, i) {

      sClasse += " "+sClass;
    });


    var sCol  = "<td class='linhagrid "+sClasse+"' title='' nowrap style='"+this.sStyleWidth+"text-align:"+this.sAlign+";"+sStyleDisplay+";"+this.sStyle+"'";
    sCol += " id='"+this.sId+"' ";

    if ( this.iColspan != 1 ) {
      sCol += " colspan='" + this.iColspan + "' ";
    }
    if (this.sEvents != '') {
      sCol += "  "+this.sEvents;
    }

    var sEventosAdicionais = "";
    if (this.aEventos.length > 0) {

      this.aEventos.each(
        function (oEvento) {
          sEventosAdicionais += oEvento.evento+"='"+oEvento.funcao+"';";
        }
      );
    }
    sCol += sEventosAdicionais;
    sCol += ">";
    sCol += this.getContent();
    sCol += "</td>\n";

    return sCol;
  }

  /**
   * retorna o valor da celula
   * @return string
   *
   */
  this.getContent = function () {

    var sRetorno = '';
    if (this.content == "") {
      this.content = "&nbsp;";
    }
    sRetorno = this.content;
    if (this.content instanceof Object) {

       if (this.content.toInnerHtml) {
         sRetorno = this.content.toInnerHtml();
      }
    }
    return sRetorno;
  }

  /**
   * Seta o conteúdo da célula e altera em tela
   * @param string sContent
   */
  this.setContent = function(sContent) {

    this.content = sContent;
    var oElement = document.getElementById(this.sId);

    if (oElement) {
      document.getElementById(this.sId).innerHTML = sContent;
    }
  }

  /**
   * metodo getter para o Id da Celula
   * @return string
   */
  this.getId = function() {
    return this.sId;
  }

  /**
   * Retorna o valor da celula.
   * analisa o no filho da celula, para decidir qual informação deve pegar.
   * @return string
   */
  this.getValue = function() {

    var oCelulaAtiva = $(this.getId());
    var sValue       = '';
    if (this.content instanceof Object) {

       if (this.content.getValue) {
         return this.content.getValue();
      }
    }
    switch(oCelulaAtiva.childNodes[0].nodeName) {

      case "INPUT" : //Objetos do tipo input., pegamos o atributo value do objeto.

        sValue = $F(oCelulaAtiva.childNodes[0].id);
        break;

      case "#text" : //nó padrao, a TD possui apenas texto, sem nenhuma tag html.

        sValue = oCelulaAtiva.childNodes[0].nodeValue;
        break;

      case "SELECT" : // Objeto do tipo select , retorna o atributo value do objeto.

        sValue =  $F(oCelulaAtiva.childNodes[0].id);
        break;

     case "BUTTON":

       sValue = "";
       break;

     default : // o elemento filho possui um innerHMTL. (geralmente para tags HTML.)

        if (oCelulaAtiva.childNodes[0].innerHTML) {

          sValue =  oCelulaAtiva.childNodes[0].innerHTML;
        } else {
          sValue = ""
        }
        break;

    }
    return sValue;
  }
   /**
   * Seta o alinhamento da celula
   * @param {string} sAlign [left, right, center, justify]
   * @see #getAlign
   */
  this.setAlign = function (sAlign) {

    if (sAlign != null) {
      this.sAlign = sAlign;
    }
  }
  /**
   * Retorna o alinhamento da celula
   * @see #setAlign
   * @return string
   */
  this.getAlign = function () {
    return this.sAlign;
  }

  /**
   * Define a Utilização de Colspan pela Célula
   * @param lUtilizaColspan      bool
   * @param iQuantidadeMesclagem integer - Quantidade de Colunas que vão ser mescladas
   */
  this.setUseColspan     = function( lUtilizaColspan, iQuantidadeMesclagem ) {

    me.lUnica   = lUtilizaColspan;
    me.iColspan = iQuantidadeMesclagem;
  }

  /**
   * Adiciona um evento à ser chamado
   * @param sEvento - Evento desejado - EX: onclick, onmousein
   * @param sFuncao - Função à ser executada
   */
  this.adicionarEvento = function(sEvento, sFuncao) {

    var oEvento = {"evento" : sEvento, "funcao" : sFuncao};
    this.aEventos.push(oEvento);
  };
}

/**
 * Cria um no do tipo table_header
 * @param {string} sContent caption do header
 * @construct
 * @type tableHeader
 * @return string
 */
function tableHeader(sContent, sWidth, iCol, classe, sGridCollNumber) {

  /**
   * contem o caption da celula
   * @type string
   */
  var me       = this;
  this.content = sContent;

  /**
   * Define se a coluna está visível ou não
   * @type bool
   */
  this.lDisplayed = true;


  this.sWidth = '';
  if (sWidth != null || sWidth != "") {
    this.sWidth = sWidth;
  }
  /**
   * Renderiza a TD em forma de string
   * @return string
   */

  this.create = function () {

    if (classe == "checkbox") {
      var sCol  = "<td class='table_header "+classe+"' id='col"+iCol+"' nowrap  ";
    } else {
      var sCol  = "<td class='table_header "+classe+"' id='col"+iCol+"' nowrap title='"+this.getContent()+"'";
    }
    sCol += "gridColNumber='"+sGridCollNumber+"'";
    sStyle    = "style ='";
    if (!this.lDisplayed) {
      sStyle += " display:none;";
    }
    if (this.sWidth != '') {
       sStyle += "width:"+this.sWidth;
    }
    sStyle   += "'";
    sCol     += sStyle;
    sCol     += ">";
    sCol     += me.getContent();
    sCol     += "</td>\n";
    return sCol;

  }

  /**
   * metodo getter para o innerHTML da Celula
   * @return string
   */
  this.getContent = function () {

    var sRetorno = '';
    if (this.content == "") {
      me.content = "&nbsp;";
    }
    sRetorno = this.content;
    if (me.content instanceof Object) {

       if (me.content.toInnerHtml) {
         sRetorno = this.content.toInnerHtml();
      }
    }
    return sRetorno;
  }

  /**
   * metodo getter para o Id da Celula
   * @return string
   */
  this.getId = function() {
    return this.sId;
  }

  /**
   * Define o tamanho da coluna
   * @param {string} sWidthColumn Tamanho da Coluna
   * @return void
   */
  this.setWidth = function(sWidthColumn) {

    if (sWidthColumn != null || sWidthColumn != "") {
      me.sWidth = sWidthColumn;
    }
  }
}

/**
 * Cria um objeto do tipo TR
 * @param {string} sId Id de identificador, deve ser o padrao 'row<nome_da_grid><rowcount>
 * @constructor
 * @class Define um objeto do tipo tableRow,
 */
function tableRow (sId) {

  this.aCells     = new Array();
  this.sId        = sId;
  this.sEvents    = "";
  this.classChecked = "";
  this.sStyle     = "";
  this.isSelected = false;
  this.sValue     = null;
  var cellCount   = new Number(0);
  this.sClassName = "normal";
  this.lDisplayed = true;
  this.lDisabled  = false;
  this.iRowNumber = 0;
  this.aClassName = new Array();
  var me          = this;

  /**
   * Adiciona uma class na TD através do Id passado.
   */
  this.addClassName = function(sClass){

    if ($(me.sId)) {
      $(me.sId).addClassName(sClass);
    }
    me.aClassName.push(sClass);
  }

  /**
   * Remove a class na TD através do ID passado
   */
  this.removeClassName = function( sClass){

    if ($(me.sId)) {
      $(me.sId).removeClassName(sClass);
    }
    for (var ind = 0; ind < me.aClassName.length; ind ++){
      if ( me.aClassName[ind] == sClass){
        me.aClassName[ind] ="";
      }
    }
  }

  /**
   * Adiciona uma celula a Linha
   * @param {tableCell} oCell Objeto do tipo tableCell
   * @return void
   */

  this.addCell   = function (oCell) {

    if (!(oCell instanceof tableCell)) {
      return false;
    } else {

      this.aCells[cellCount] = oCell;
      cellCount++;

    }
    return true;
  }

  /**
   * Seleciona a Linha
   */
  this.select = function (lSelect) {

    if (lSelect) {

       me.isSelected = true;
       if ($(me.aCells[0].sId).childNodes[0].type =='checkbox') {
         if ($(me.aCells[0].sId).childNodes[0].checked == false) {

            $(me.aCells[0].sId).childNodes[0].click();
            $(me.aCells[0].sId).childNodes[0].checked = true;
         }
       } else {

         $(me.sId).className='marcado';
         me.setClassName('marcado');
       }
    } else {

      me.isSelected = false;
      if ($(me.aCells[0].sId).childNodes[0].type == 'checkbox') {

        $(me.aCells[0].sId).childNodes[0].click();
        $(me.aCells[0].sId).childNodes[0].checked = false;
      }  else {

        $(me.sId).className='normal';
        me.setClassName('normal');
      }
    }
  }

  this.getRowNumber = function() {
    return this.iRowNumber;
  }

   /**
    * Adiciona a celula como primeira na pilha
    * @param {tableCell} oCell Objeto do tipo tableCell
    * @return void
    */
  this.addFirstCell = function (oCell) {

    if (!(oCell instanceof tableCell)) {

      return false;

    } else {

      this.aCells.unshift(oCell);
      cellCount++;

    }
    return true;
  }

  /**
   * Metodo para criar o objeto. transforma o objeto numa string
   * @return string
   */
  this.create = function () {

    var classCSS   = "";
    if (this.classChecked != "") {
      classCSS = this.classChecked;
    } else {
      classCSS =  this.sClassName;
    }

    me.aClassName.each(function(sClass, i) {

      classCSS += " "+sClass;
    });

    var sContents  = "<tr id='"+this.sId+"' class='"+classCSS+"'";
    sContents     += this.sEvents;
    var sDisplay   = '';
    if (this.lDisplayed == false) {
      sDisplay = "display:none";
    }
    sContents     += " style='height:1em;"+sDisplay+"; "+this.sStyle+"'>";
    if (this.aCells.length > 0) {
      for (var iCellsLength = 0; iCellsLength < this.aCells.length; iCellsLength++) {
        sContents += this.aCells[iCellsLength].create();
        if (this.aCells[iCellsLength].lUnica ) {
          break;
        }
      }
    }

    sContents +="</tr>\n";
    return sContents;

  }

  /**
   * seta a classe css da linha
   * @param {string} sClassName nome da classe css
   */
  this.setClassName = function(sClassName) {

    if (sClassName == null || sClassName == "") {
      this.sClassName = 'normal';
    } else {
      this.sClassName = sClassName;
    }
    return true;
  }

  this.getClassName = function () {
    return this.sClassName;
  }

  /**
   * metodo getter para o Id da Linha
   * @return string
   */
  this.getId = function() {
    return this.sId;
  }
}

/**
 * Retorna as Celulas da Linha
 * @returns {tableCell[]}
 */
tableRow.prototype.getCells = function () {
  return this.aCells;
}

/**
 * Retorna o elemento TR da linha
 * @returns
 */
tableRow.prototype.getElement = function() {

  var me = this;
  return $(me.sId);
}

