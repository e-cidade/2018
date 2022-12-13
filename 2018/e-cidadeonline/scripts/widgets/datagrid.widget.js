/** 
 * @fileoverview Esse arquivo define classes para a construção de uma datagrid
 * para visualizaçao de dados tabulares
 *
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version  $Revision: 1.2 $
 */

/**
 * classe que disponibiliza um datagrid
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
  this.iHeight   = 150;
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
  
  var me = this;
  
  
   /**
   * Metodo para alinha o rodapé se este existir
   *
   * @param {Object} Objéto da tabela do Cabeçalho
   * @return void 
   *
   */
  this.alinhaFooter = function (oHeader) {
  
    
    var oFooter = $("table"+me.sName+"footer");
    
    if (oHeader instanceof Object) {
      oFooter.style.width = oHeader.scrollWidth;
    } else {
     
      var oHeader = $("table"+me.sName+"header");
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
    
    // Percorre as linhas da tabela localizando e retornando a primeira linha visível.
    for (var iCont = 0; iCont < oBody.rows.length; iCont++) {
    
      if (oBody.rows[iCont].style.display != "none") {
          
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
    if (me.hasTotalizador) {
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
     var oHeader = $("table"+me.sName+"header");
     var oBody   = $(me.sName+"body"); 
     var oFooter = $("table"+me.sName+"footer"); 
     
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
      
      oRow = new tableRow(me.sName+'row'+me.sName+me.iRowCount);
      
      if (me.hasCheckbox) {

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
        sCheckbox    += "<input type='checkbox' id='chk"+aRow[me.checkBoxValue]+"' "+sDisabled+ " "+sChecked;
        sCheckbox    += "      onclick=\""+me.nameInstance+".selectSingle(this,'"+me.sName+"row"+me.sName+me.iRowCount+"',"+me.nameInstance+".aRows["+me.iRowCount+"])\""; 
        sCheckbox    += "      value='"+aRow[me.checkBoxValue]+"' class='checkbox"+me.sName+"' style='height:12px; ' >";  
        oRow.addCell(new tableCell(sCheckbox, me.sName+'row'+me.iRowCount+'checkbox',21, 'checkbox'));
        iNumCol++;
          
     }
         
     //Iteramos sobre as posições do array Criado.
     for (var iLength = 0; iLength < aRow.length; iLength++) {
      
        sId        = me.sName+'row'+me.iRowCount+'cell'+iLength;
        sCellWidth = '';
        if (me.aWidths[iLength]) {
          sCellWidth = me.aWidths[iLength];
        }
        var oCell = new tableCell(aRow[iLength] ,sId, sCellWidth, 'cell');
        
        if (me.aAligns[iLength]) {
          oCell.setAlign(me.aAligns[iLength]);
        }
        
        if (!me.aHeaders[iNumCol].lDisplayed) {
           oCell.lDisplayed = false;  
        }
 
        oRow.addCell(oCell);
        iNumCol++;
      }
       
      me.aRows[me.iRowCount] = oRow;
      me.iRowCount++;
      
    } else if ((aRow instanceof tableRow)) {
     
     if (me.hasCheckbox) {

        var sCheckbox = "";
        sCheckbox    += "<input type='checkbox' id='chk"+aRow.aCells[0].getContent()+"'";
        sCheckbox    += "      onclick=\""+me.nameInstance+".selectSingle(this,'"+aRow.sId+"',"+me.nameInstance+".aRows["+me.iRowCount+"])\""; 
        sCheckbox    += "      value='"+aRow.aCells[0].getContent()+"' class='checkbox"+me.sName+"' style='height:12px'>";  
        aRow.addFirstCell(new tableCell(sCheckbox,me.sName+'row'+me.iRowCount+'checkbox', 21, 'checkbox'));
            
     }
     me.aRows[me.iRowCount] = aRow;
     me.iRowCount++;
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
    if (me.hasCheckbox) {
    
      //this.sHeader += "<td class='table_header'>";
      if (me.hasSelectAll) {
      
        sHeader += "<input type='checkbox'  style='display:none'";
        sHeader += "       id='mtodositens"+me.sName+"'><b>";
        sHeader += "      <a id=\""+me.nameInstance+"SelectAll\" onclick=\""+me.nameInstance+".selectAll('mtodositens"+me.sName+"','checkbox"+me.sName+"','"+me.sName+"row"+me.sName+"')\"";
        sHeader += "         style='cursor:pointer; '>M</a></b>";
        
        
      } else {
        sHeader += "&nbsp;";
      }
      
      iNumCells++;
      //this.sHeader += "</td>";
      var oHeader = new tableHeader(sHeader, '21px', iNumCells, 'checkbox');
      me.aHeaders.push(oHeader);
    }
    /*
     * Percorremos os arrays da grid e criamos a string com as tags html
     */
    for (var iLength = 0; iLength < aHeader.length; iLength++) {
    
      iNumCells++;
      sCellWidth = '';
      if (me.aWidths[iLength]) {
        sCellWidth = me.aWidths[iLength];
      }
      var oHeader = new tableHeader(aHeader[iLength], sCellWidth, iNumCells, 'cell');
      me.aHeaders.push(oHeader);
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
      
    var sGrid = "<div class='container' id='grid"+me.sName+"'>"; 
    
    sGrid    += "<div class='header-container' >";
    sGrid    += "<div class='grid-resize'>";
    sGrid    += "<img src='"+me._IMGPATH+"/"+sImgSelection+"' border='0' onclick='"+sCallBackCols+"'>";
    sGrid    += "<div style='clear: both;'></div>" // LIMPA O FLOAT DA IMAGEM
    sGrid    += "</div>"  //FECHA DIV "grid-resize"
    sGrid    += "<table class='table-header'  id='table"+me.sName+"header'>";
    sGrid    += me.renderHeader();
    sGrid    += "</table>"; // FECHA TABELA DO HEADER table-header 
    sGrid    += "</div>";   // FECHA DIV "header-container"
    // FIM DO HEADER    ---    INICIO DO BODY
    
    sGrid    += "<div class='body-container' style='height:"+me.iHeight+"px;' id='divCorpo" + me.sName + "'>";
    sGrid    += "  <table class='table-body'  id='"+me.sName+"body'>";
    sGrid    +=     me.renderRows(true, false);
    sGrid    += "  </table>"; // FECHA TABELA DO HEADER table-body
    sGrid    += "</div>";    // FECHA DIV "body-container"
    // FIM DO BODY     ----    INICIO DO FOOTER
    sGrid    += "<div class='footer-container' >";

    sGrid    += "<table class='table-footer'  id='table"+me.sName+"footer'>";
    if (me.hasTotalizador) {
      sGrid+= me.renderFooter();
    }
    sGrid    += "<tr style='text-align:left;'>";
    sGrid    += "<td colspan='"+(me.aHeaders.length+2)+"'><div style='border:1px inset white;height:100%;padding:2px'>";
    sGrid    += "<span> Total de Registros:</span><span style='color:blue;padding:3px' id='"+me.sName+"numrows'>0</span>";
    sGrid    += "<span style='border-left:1px inset #eeeee2' id='"+me.sName+"status'>&nbsp;</span>&nbsp;";
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
    if (me.lAllowSelectCol) {
      sGrid += me.drawSelectCol();
    }
    oNode.innerHTML = sGrid;
    
    $("table"+me.sName+"header").style.width = me.gridContainerWidth;
    $(me.sName+"body").style.width           = me.gridContainerWidth;
    $("table"+me.sName+"footer").style.width = me.gridContainerWidth; 
    
     // Subtrai o tamanho do scroll
    
    if (me.hasTotalizador) {
     
      if (me.bAlinhaFooter){
        
        me.alinhaFooter();
        me.bAlinhaFooter = false;
      }
    }
    
  }
  
  this.renderFooter = function() {

    var sFooter = "<tr>";
    for ( var i = 0; i < me.aHeaders.length; i++) {
    
      if(me.aHeaders[i].lDisplayed != false){
        if (me.hasCheckbox) {
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
    me.aWidths = aWidths;
  }
  
  /**
   * define o alinhamento global das celulas
   * @param  {array} aAligns array com oAlinhamento de cada celula;
   * @return Void
   * @type void
   */
   
  this.setCellAlign = function(aAligns) {
    me.aAligns = aAligns;
  }
  
  /**
   * Seta se a grid mostrara um checkbox, no inicio de cada linha
   * @param integer iCell Posição da array que contem o valor da checkbox (sera usado com indice.)
   */
  this.setCheckbox = function(iCell) {
  
     me.hasCheckbox   = true;
     me.checkBoxValue = iCell;
   
  }
  /**
   * Define a altura
   * @param {integer} iHeight define a altura
   * @return void
   */
  this.setHeight = function (iHeight) {
     me.iHeight = iHeight;
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
      for (var iItensLength = 0; iItensLength < me.aRows.length; iItensLength++) {
         
        with (me.aRows[iItensLength]) { 
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
    
      for (var iItensLength = 0; iItensLength < me.aRows.length; iItensLength++) {
      
        if (me.aRows[iItensLength].isSelected) {
          aSelecionados.push(me.aRows[iItensLength]);
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
   
    itens = me.getElementsByClass(sClasse);
    for (var i = 0;i < itens.length;i++){
        
      if (itens[i].disabled == false){
        if (obj.checked == true){
  
          itens[i].checked=true;
          me.selectSingle($(itens[i].id), (sLinha+i), me.aRows[i]);
          
        } else {
         
          itens[i].checked=false;
          me.selectSingle($(itens[i].id), (sLinha+i), me.aRows[i]);
          
        }
      }
    }
  }
  
  /**
   * Seleciona uma linha da grid
   *
   * @param {object} oCheckbox qual checkbox foi clicado
   * @param {string} sRow Id da row
   * @param {tableRow} oRow objeto de referencia da linha
   * @type void  
   * @return void
   */
  this.selectSingle = function (oCheckbox,sRow,oRow) {
   
    if (oCheckbox.checked) {
    
      $(sRow).className = 'marcado';
      oRow.isSelected   = true;
      
    } else {
  
      $(sRow).className = oRow.getClassName();
      oRow.isSelected   = false;
     
    }
    return true;
  }
 
  /**
   * reseta as informações da grid, 
   * @param {bool} lDeleteRows se exclui as linhas cadastradas. caso false apenas limpa o corpo da grid
   * @return void
   */
  this.clearAll = function (lDeleteRows) {
    
    $( "divCorpo" + me.sName ).innerHTML    = '';
    $(me.sName+"numrows").innerHTML = 0;
    if (lDeleteRows != null && lDeleteRows == true) {
          
      delete me.aRows;
      
      me.aRows  = new Array();
      
      me.iRowCount   = 0;
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
       sRows += me.aRows[iRowLength].create();
    }
   
    
    if (lReturnString) {
      return sRows;
    } else {
    
      me.clearAll(false);
      var sTabela  = "<table class='table-body'  id='"+me.sName+"body'>";
          sTabela += sRows;
          sTabela += "</table>";

      $( "divCorpo" + me.sName ).innerHTML = sTabela;
      $(me.sName+"numrows").innerHTML      = me.aRows.length;

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
  
    me.hasSelectAll = lSelectAll;
    return false;
    
  }
  /**
   * retorna se deve  mostrar opção para selecionar todos os checkbox
   * @see #setSelectAll
   * @type bool
   * @return boolean
   */
  this.getSelectAll = function () {
    return me.hasSelectAll;
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
         aSelection = me.getSelection();
      } else {
         aSelection = me.aRows;
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
      sCallBackCols        = me.nameInstance+".showSelectCol(this)";
      me.lAllowSelectCol = true;
      
    } else {
    
      sImgSelection        = "espaco.gif";
      sCallBackCols        = "return false";
      me.lAllowSelectCol = false;
      
    }
    return false;
  }
  
  /**
   * Renderiza o dropdown para selecionar as colunas
   * @type void
   * @return string
   */
  this.drawSelectCol = function() {
  
    var sDropDown  = "<div id='columns"+me.sName+"' class='draw-select-col' ";
    sDropDown     += "      style='position:absolute; visibility:hidden; top:0px; left:0px; text-align:left; padding:2px'>";
    for (var i = 0; i < me.aHeaders.length; i++) {
      
      iNumCol = i;
      if (me.hasCheckbox) {
        iNumCol += 1;
      }
      if (me.aHeaders[iNumCol]) {
        
        var sChecked = " checked ";
        if (!me.aHeaders[iNumCol].lDisplayed) {
        
        sChecked = "";  
        }
        sDropDown += "<input type='checkbox' onclick='"+me.nameInstance+".showColumn(this.checked, "+(i+1)+")'";
        sDropDown += " id='"+me.sName+"_col"+iNumCol+"' "+sChecked+">";
        sDropDown += "<label for='"+me.sName+"_col"+iNumCol+"'>"+me.aHeaders[iNumCol].getContent()+"</label><br>";
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
    var iTamObj = $('columns'+me.sName).scrollWidth-1;
    $('columns'+me.sName).style.left = x - iTamObj;
    $('columns'+me.sName).style.top  = y;
    /*
     * decidimos se mostramos ou não o dropdown, conforme o seu estado.
     */
    if ($('columns'+me.sName).style.visibility == 'visible') {
      $('columns'+me.sName).style.visibility = 'hidden';
    } else if ($('columns'+me.sName).style.visibility == 'hidden') {
      $('columns'+me.sName).style.visibility = 'visible';
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
     
    if (me.hasCheckbox) {
      var oHiddenHeader = $("col"+(iWhatCol+1));
    } else{
      var oHiddenHeader = $("col"+iWhatCol);
    }  
   // alert ("Coluna a escolnder col"+iWhatCol);  
    if (lHide) {
      
      if (me.hasCheckbox) {
          me.aHeaders[iWhatCol].lDisplayed = true;
        } else {
          me.aHeaders[iWhatCol-1].lDisplayed = true;
        }  
      oHiddenHeader.style.display = "";
      if ( typeof(me.iRowCount) != 'undefined' ){
        
        for (var ind = 0; ind < me.iRowCount; ind++){
          
          var oHiddenCol = $(me.sName+"row"+ind+"cell"+(iWhatCol-1));
          oHiddenCol.style.display = "";
        }
      }
      var oHiddenFooter = $('TotalForCol'+iWhatCol);
      if (me.hasTotalizador) {
        oHiddenFooter.style.display = "";
      }
    } else {
        if (me.hasCheckbox) {
          me.aHeaders[iWhatCol].lDisplayed = false;
        } else {
          me.aHeaders[iWhatCol-1].lDisplayed = false;
        }
      oHiddenHeader.style.display = "none";
      
      if ( typeof(me.iRowCount) != 'undefined' ) {
       
        for (var ind = 0; ind < me.iRowCount; ind++){
          
          var oHiddenCol = $(me.sName+"row"+ind+"cell"+(iWhatCol-1));
          oHiddenCol.style.display = "none";
        }
      }
      var oHiddenFooter = $('TotalForCol'+iWhatCol);
      if (me.hasTotalizador) {
        oHiddenFooter.style.display = "none";
      }
      
    }  
    $('columns'+me.sName).style.visibility = 'hidden';
    
    me.resizeCols();
    
    return true;
  }
  
  /**
   * renderiza header da grid
   
   * @return string
   */
  this.renderHeader = function() {
   
    var sHeader = "";  
    for (var i = 0; i < me.aHeaders.length; i++) {
      
      var iCol = i;
      if (me.hasCheckbox) {
        iCol--;
      }
      if (me.hasCheckbox && i == 0) {
        me.aHeaders[i].setWidth("21px");
        
      } else if (me.aWidths[iCol]) {
        me.aHeaders[i].setWidth(me.aWidths[iCol]);
      }
      sHeader += me.aHeaders[i].create(); 
    }
    return sHeader;
  }
  
  /**
   * define a propriedade numrows
   * @param {int} iNumnRows Novo valor para a propriedade
   * @return void
   */
  this.setNumRows = function(iNumRows) {
    $(me.sName+"numrows").innerHTML = new Number(iNumRows).valueOf();
  }
  
  /**
   * Retorna o valor definido para a propriedade numrows
   * @return int
   */
  this.getNumRows = function() {
    return new Number($(me.sName+"numrows").innerHTML).valueOf();
  }
  
  /**
   * define o texto da barra de status 
   * @param {string} sText texto
   * @return void
   */
   
  this.setStatus = function(sText) {
    $(me.sName+'status').innerHTML = "&nbsp;"+sText;
  }
  
  /**
   * Retorna o texto da barra de status.
   * @return string
   */
  this.getStatus = function() {
    return $(me.sName+'status').innerHTML;
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
  this.iColSpan     = 1;
  
  if (sWidth != '' && typeof(sWidth) != 'undefined') {
    me.sStyleWidth = "width:"+sWidth+";";
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
    if (me.lUnica) {
      this.sStyleWidth = "100%";
    }
    me.aClassName.each(function(sClass, i) {
      
      sClasse += " "+sClass;
    });
    
//    var sHint = this.getContent().replace(/<[^>]*>/g, "");
    
    var sCol  = "<td class='linhagrid "+sClasse+"' title='' nowrap style='"+me.sStyleWidth+"text-align:"+me.sAlign+";"+sStyleDisplay+";"+me.sStyle+"'";
        sCol += " id='"+me.sId+"' ";
        sCol += " colspan='" + me.iColspan + "' ";     
        
    if (me.sEvents != '') {
      sCol += "  "+me.sEvents;
    }
    sCol += ">";
    sCol += me.getContent();
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
    if (me.content == "") {
      me.content = "&nbsp;";
    }
    sRetorno = me.content;
    if (me.content instanceof Object) {
       
       if (me.content.toInnerHtml) {
         sRetorno = me.content.toInnerHtml();
      }
    }
    return sRetorno;
  }
  
  /**
   * metodo getter para o Id da Celula
   * @return string
   */
    
  this.getId = function() {
    return me.sId;
  }
  
  /**
   * Retorna o valor da celula.
   * analisa o no filho da celula, para decidir qual informação deve pegar.
   * @return string 
   */
  this.getValue = function() {
    
    var oCelulaAtiva = $(me.getId());
    var sValue       = ''; 
    if (me.content instanceof Object) {
       
       if (me.content.getValue) {
         return me.content.getValue();
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
      me.sAlign = sAlign;
    }
  }
  /**
   * Retorna o alinhamento da celula
   * @see #setAlign
   * @return string
   */
  this.getAlign = function () {
    return me.sAlign;
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
  
  

  
}

/**
 * Cria um no do tipo table_header
 * @param {string} sContent caption do header
 * @construct 
 * @type tableHeader
 * @return string
 */
function tableHeader(sContent, sWidth, iCol, classe) {
    
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
      var sCol  = "<td class='table_header "+classe+"' id='col"+iCol+"' nowrap title='"+me.getContent()+"'";
    }
    
    sStyle    = "style ='"; 
    if (!me.lDisplayed) {
      sStyle += " display:none;"; 
    }
    if (me.sWidth != '') {
       sStyle += "width:"+me.sWidth;
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
         }
       } else {
       
         $(me.sId).className='marcado';
         me.setClassName('marcado');
       }
    } else {
    
      me.isSelected = false;
      if ($(me.aCells[0].sId).childNodes[0].type == 'checkbox') {
        $(me.aCells[0].sId).childNodes[0].click();
      }  else {
       
        $(me.sId).className='normal';
        me.setClassName('normal');
      }
    }
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
   
}
