function DBGridMultiCabecalho(sId) {
  
  var oGrid         = new DBGrid(sId);
  oGrid.aGrupoLinha = new Array();
  var _show         = oGrid.show;
  oGrid.show        = function (oNode) {
    if (oGrid.aGrupoLinha.length > 0) {
      oGrid.allowSelectColumns(false);
    }
    _show.call(oGrid, oNode) ;
    oGrid.calcularTamanhoGrid();  
    oGrid.renderizarGrupos();    
  }
  
  oGrid.calcularTamanhoGrid = function() {
  
    var oHeader      = $("table"+this.sName+"header");
    var iTamanhoGrid = oHeader.getWidth();
    for (var iCol = 0; iCol < oHeader.rows[0].cells.length; iCol++) {
      
      var iInicio = iCol;
      if (oGrid.hasCheckbox) {
        iInicio -= 1;
      }
      oGrid.aWidths[iInicio]                  = js_round(oHeader.rows[0].cells[iCol].scrollWidth, 0)+"px";
      oHeader.rows[0].cells[iCol].style.width = oGrid.aWidths[iInicio]; 
    }
  }
  
  oGrid.alignsCells = function(oHeader, oBody, oFooter) {
    
    if (!oBody.rows[0]) {
      return true;
    }
    
    var iFirstLineValid = oGrid.getFirstLineValid(oBody);
    
    // Estabelece um tamanho fixo para as colunas.
    oBody.style.tableLayout   = "fixed";
    //oHeader.style.tableLayout = "fixed";
    // Itera sobre todas as colunas criadas
    for (var iCont = 0; iCont < oGrid.aHeaders.length; iCont++) {
      
      // Garante que só será trabalhado sobre as colunas visiveis
      if (oBody.rows[iFirstLineValid].cells[iCont].style.display != "none" && 
        oHeader.rows[oGrid.iHeaderLineModel].cells[iCont].style.display != "none" ) {
       
        //Se oa coluna x do Header e a Conluna x do Body for do mesmo tamanho, passa para o próxima volta do laço  
        if (oBody.rows[iFirstLineValid].cells[iCont].getWidth() == oHeader.rows[oGrid.iHeaderLineModel].cells[iCont].getWidth()) {
          continue;
        }
        var oCelulaBody   = oBody.rows[iFirstLineValid].cells[iCont];
        var oCelulaHeader = oHeader.rows[oGrid.iHeaderLineModel].cells[iCont];
        var nHeaderSize   = oCelulaHeader.getWidth();
        if (oCelulaHeader.style.width != "") {
          nHeaderSize = oCelulaHeader.style.width; 
        }
        //oCelulaBody.style.width = nHeaderSize;
      } 
    } // end for
    if (this.hasTotalizador) {
      //oGrid.alinhaFooter(oHeader);
    }
  }
  
  oGrid.adicionarGrupo = function (sDescricaoGrupo, aColunas, iAcimaLinha) {
   
     if (!oGrid.aGrupoLinha[iAcimaLinha]) {
         
       var oGrupo = new Object();
       oGrupo.iLinhaAcima             = iAcimaLinha;
       oGrupo.aGruposColuna           = new Array();
       oGrid.aGrupoLinha[iAcimaLinha] = oGrupo;
     }
     
     var oGrupoColunas             = new Object();
     oGrupoColunas.sDescricaoGrupo = sDescricaoGrupo;
     oGrupoColunas.aColunas        = aColunas,
     oGrupoColunas.iAcimaLinha     = iAcimaLinha;
     oGrid.aGrupoLinha[iAcimaLinha].aGruposColuna.push(oGrupoColunas);
   }
   
   oGrid.renderizarGrupos = function () {

     oHeader        = $('table'+oGrid.sName+'header');
     oBody          = $('table'+oGrid.sName+'body');
     for (iLinha in oGrid.aGrupoLinha) {
       
       oGrupo     = oGrid.aGrupoLinha[iLinha];
       if (typeof(oGrupo) == 'function') {
         continue;
       }
       
       var oLinhaBase    = oHeader.rows[oGrupo.iLinhaAcima];
       oLinhaBase.id     = 'table'+oGrid.sName+'header_linha'+oGrupo.iLinhaAcima; 
       var oLinha        = document.createElement('tr');
       var iTotalColunas = 0;
       oGrupo.aGruposColuna.each(function(oGrupoColunas, iGrupo) {
         
         var oCelula       = document.createElement('th');
         oCelula.innerHTML = oGrupoColunas.sDescricaoGrupo;
         oCelula.className = 'table_header';
         oCelula.colSpan   = oGrupoColunas.aColunas.length;
         var nTamanho      = 0;
         oGrupoColunas.aColunas.each(function(iColuna, iSeq) {
           nTamanho += oLinhaBase.cells[iColuna].getWidth() - 2; 
         });
         
         oCelula.style.width = nTamanho;
         iTotalColunas     += oGrupoColunas.aColunas.length;
         oLinha.appendChild(oCelula);
       });
       
       if (iTotalColunas < oLinhaBase.cells.length) {
         
         oCelula           = document.createElement('th');
         oCelula.innerHTML = "&nbsp;";
         oCelula.className = 'table_header';
         oCelula.colspan   =  oLinhaBase.cells.length - iTotalColunas; 
         oLinha.appendChild(oCelula);
       }
       oLinhaBase.parentNode.insertBefore(oLinha, oLinhaBase);
     }
     oGrid.setHeaderModel(oHeader.rows.length-1);
   }
   return oGrid;
}
 