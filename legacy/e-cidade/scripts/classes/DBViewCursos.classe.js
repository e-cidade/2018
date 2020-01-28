/** 
 * @fileoverview Cria elementos para cadastro de cursos
 * 
 * @author       Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @version      $Revision: 1.3 $
 * @revision     $Author: dbrafael.lopes $ 
 * @param        sInstaceName  -  Instancia do objeto
 * 
 * @require      prototype.js
 * @require      datagrid
 * @require      windowAux
 * @require      DBTextField
 */
var DBViewCursos           = function (sInstaceName) {
  
  /**
   * Instancia interna da classe
   */
  var oDBViewRhCursos        = this;
   
  /**
   * Valida se mostra somatório dos cursos
   */
  var lMostraRodape            = false;
  
  /**
   * Código da Promoção que será tratado
   */
  var iCodigoPromocao        = null;

  /**
   * Numero da avaliacao que será tratada.
   */
  var iCodigoAvaliacao       = null;
  
  /**
   * Objeto que contém os dados do formulário do componente
   */
  this.oFormulario           = null;

  /**
   * Altura da Grid
   */
  var iAlturaGrid      	  	 = 150;
  
  /**
   * Caminho do arquivo RPC do componente
   */
  var sUrl                   = 'rec4_cursos.RPC.php';
 
  
  /**
   * Exibe rodapé
   */
  this.mostraRodape      	   = function () {    
    lMostraRodape = true;
  };
  
  /**
   * Esconde rodapé
   */
  this.escondeRodape      	 = function () {    
    lMostraRodape = false;
  }; 
   
  /**
   * Define a altura da grid do componente
   * @param iHeight
   */
  this.setAlturaGrid   	  	 = function (iHeight) {
    iAlturaGrid = iHeight;
  };

  /**
   * Define o código da Avaliação
   * @param iCodigo - Numero da avaliação, sequencial da tabela rhavaliacao
   */
  this.setCodigoAvaliacao 	 = function (iCodigo) {
    iCodigoAvaliacao = iCodigo;
  };
  
  /**
   * Define o código da promoção
   * @param iCodigo - Numero da Promoção, sequencial da tabela rhpromocao
   */
  this.setCodigoPromocao  	 = function (iCodigo) {   
    iCodigoPromocao = iCodigo;
  };
  
  /**
   * Renderiza o html do componente
   */
  this.renderizaHTML             	 = function (oElemento) {
    
    oDBViewRhCursos.oFormulario               = new Object();
    /**
     * Cria Elementos
     */
    
    oFormulario = oDBViewRhCursos.oFormulario;
    // fildset
    oFormulario.fieldSetPrincipal             = document.createElement("fieldset");
    
    // legend
    oFormulario.legendPrincipal               = document.createElement("legend");
    oFormulario.legendPrincipal.innerHTML     = "<b>Cursos:</b>";
    
    // div contenerGrid
    oFormulario.divContenerGrid               = document.createElement("div");

    // div rodapeGrid
    oFormulario.divRodapeGrid                 = document.createElement("div");
    oFormulario.divRodapeGrid.style.textAlign = 'right';    
    oFormulario.divRodapeGrid.style.marginTop = '10px';    
    
    // span labelRodape
    oFormulario.spanLabelRodape               = document.createElement("span");
    oFormulario.spanLabelRodape.innerHTML     = "<b>Conhecimento: </b>";
  
    // span inputRodape
    oFormulario.spanInputRodape               = document.createElement("span");
    
    // appenChild
    oFormulario.divRodapeGrid.appendChild( oFormulario.spanLabelRodape );
    oFormulario.divRodapeGrid.appendChild( oFormulario.spanInputRodape );
    
    oFormulario.inputRodape                   = new DBTextField("conhecimento", sInstaceName + ".inputRodape",'', 10);
    oFormulario.inputRodape.setReadOnly(true);
    oFormulario.inputRodape.show(oFormulario.spanInputRodape);
    
    
    /**
     * Monta HTML
     */
    oFormulario.fieldSetPrincipal.appendChild(oFormulario.legendPrincipal);
    oFormulario.fieldSetPrincipal.appendChild(oFormulario.divContenerGrid);
    oFormulario.fieldSetPrincipal.appendChild(oFormulario.divRodapeGrid);
    oElemento.appendChild(oFormulario.fieldSetPrincipal);    
    
    
    /**
     * Renderiza dataGrid
     */    
    oDBViewRhCursos.dataGrid                      = new DBGrid(sInstaceName + ".dataGrid"); 
    oDBViewRhCursos.dataGrid.nameInstance         = sInstaceName + ".dataGrid";
    oDBViewRhCursos.dataGrid.sName                = ".dataGrid";
    oDBViewRhCursos.dataGrid.setHeight( iAlturaGrid );
    oDBViewRhCursos.dataGrid.setCheckbox(2);
    oDBViewRhCursos.dataGrid.setCellAlign(new Array("center", "left", "right"));
    oDBViewRhCursos.dataGrid.setCellWidth(new Array("15%", "70%", "15%"));
    oDBViewRhCursos.dataGrid.setHeader(new Array('Código', 'Curso','Horas'));
    oDBViewRhCursos.dataGrid.show( oFormulario.divContenerGrid );
    oDBViewRhCursos.dataGrid.clearAll(true);
    oDBViewRhCursos.carregaDadosGrid();
  };
  
  /**
   * Exibe componente no local especificado, caso não seja informado exibe windowAux.
   * @param oElemento - elemento aonde o componente deve ser adicionado
   */
  this.show               	 = function (oElemento) {
    
    if ( oElemento != null ) {
      
      if (oDBViewRhCursos.oFormulario == null) {
        oDBViewRhCursos.renderizaHTML(oElemento);   
      };
      
    } else {
      oWindowAux = new windowAux("oWindowCursos", "Cursos do Servidor", 700, iAlturaGrid + 160); 
      oWindowAux.setContent("<div id=\"ctnComponente\" style=\"padding:10px;\"></div>");
      oWindowAux.show(25);
      oDBViewRhCursos.renderizaHTML($('ctnComponente'));
    }
  };

  /**
   * Carrega dados da Grid com os cursos
   */
  this.carregaDadosGrid      	 = function () {
    
    js_divCarregando('Pesquisando Cursos.', 'msgAjax');
    
    var oParam             = new Object();
    var oAjax              = new Object();
    
    oParam.sExec           = "getDadosCursos";
    oParam.iCodigoPromocao = iCodigoPromocao;
    
    if (iCodigoAvaliacao != null) {
      oParam.iCodigoAvaliacao = iCodigoAvaliacao;
    }
    
    oAjax.method           = 'POST';
    oAjax.parameters       = 'json=' + Object.toJSON(oParam);
    oAjax.onComplete       =  oDBViewRhCursos.retornoDadosGrid;
                           
    oDBViewRhCursos.dataGrid.clearAll(true);
    var oRequest           = new Ajax.Request(sUrl, oAjax);  

  };
   
  /**
   * Função de retorno da grid
   */
  this.retornoDadosGrid   	 = function (oAjax) {
    
    js_removeObj('msgAjax');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 2) {
      alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, '\n') );
    } else {

      for ( var iIndiceGrid = 0; iIndiceGrid < oRetorno.aDados.length; iIndiceGrid++) {

        with (oRetorno.aDados[iIndiceGrid]) {

          var lMarcado      = false;
          var lDesabilitado = false;

          if (habilitado == "f") {

            lMarcado      = true;
            lDesabilitado = true;
          }

          var aCelulas = new Array(h03_seq, h03_descr.urlDecode(), h03_cargahoraria);
          oDBViewRhCursos.dataGrid.addRow(aCelulas ,false, lDesabilitado, lMarcado);
        }

      }
      
      oDBViewRhCursos.dataGrid.renderRows();
      
      /**
       * Atualiza as seleções pré-definidas
       */
      oDBViewRhCursos.somaCargaHoraria();
      
      for (var iIndiceGrid = 0; iIndiceGrid < oDBViewRhCursos.dataGrid.aRows.length; iIndiceGrid++) {
        /**
         * Define eventos ao checkbox da grid
         */
        var oCheckBox  = $(oDBViewRhCursos.dataGrid.aRows[iIndiceGrid].aCells[0].sId).firstChild;
        var sOnClick   = oCheckBox.getAttribute("onclick") + "; " + sInstaceName + ".somaCargaHoraria();";
        oCheckBox.setAttribute("onClick", sOnClick);
      }
      var oLink = $(oDBViewRhCursos.dataGrid.nameInstance + "SelectAll");
      var sOnClick = oLink.getAttribute("onClick");
      oLink.setAttribute("onClick",sOnClick + ";" + sInstaceName + ".somaCargaHoraria()" );
    };
  };
   
  /**
   * Função que atualiza valor da carga horaria
   */
  this.somaCargaHoraria   	 = function (lConsideraDesabilitados) {
    
    lConsideraDesabilitados = lConsideraDesabilitados == null ? true : lConsideraDesabilitados;
    
    var iCargaHorariaSelecao = 0;
    
    var aRows = oDBViewRhCursos.dataGrid.getSelection("object");
    
    aRows.each(function(oLinha) {
      
      if ( lConsideraDesabilitados || !lConsideraDesabilitados && !$(oLinha.aCells[0].sId).firstChild.disabled ) {
        iCargaHorariaSelecao += new Number ( oLinha.aCells[3].getValue()) ;
      } 
    });
                                                                                                           
    oFormulario.inputRodape.setValue(iCargaHorariaSelecao);                                                
                                                                                                           
    return iCargaHorariaSelecao;                                                                           
  };                                                                                       
                                                                                                          
  /**                                                                                                      
   * Função que retorna array de objetos com os cursos e seus valores     
   * @param lConsideraDesabilitados (bool) valida se considera registros que estejam desabilitados                                
   */                                                                                                      
  this.getCursosSelecionados = function (lConsideraDesabilitados) {
                                                                                                           
    var aRows    = oDBViewRhCursos.dataGrid.getSelection("object");                                               
    var aRetorno = new Array(); 
    aRows.each(function(oLinha) {
      
      if ( lConsideraDesabilitados || !lConsideraDesabilitados && !$(oLinha.aCells[0].sId).firstChild.disabled ) {
        
        var oRetorno = new Object();
        oRetorno.iCodigoCurso     = oLinha.aCells[1].getValue();
        oRetorno.sDescricaoCurso  = oLinha.aCells[2].getValue();
        oRetorno.iQuantidadeHoras = oLinha.aCells[3].getValue();
        aRetorno.push( oRetorno );
      } 
    });    
    return aRetorno;
  };
  
  /**
   * Retorna Valor total da carga horária dos cursos
   * @param lConsideraDesabilitados (bool) valida se considera registros que estejam desabilitados      
   */
  this.getTotalCargaHoraria  = function (lConsideraDesabilitados) {
    return oDBViewRhCursos.somaCargaHoraria( lConsideraDesabilitados );
  };
 
};