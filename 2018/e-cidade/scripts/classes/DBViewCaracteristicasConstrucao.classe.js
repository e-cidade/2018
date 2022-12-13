/**
 * @constructor
 */
var DBViewCaracteristicasConstrucao = function (sNomeInstanciaParametro, iCodigoMatricula, iCodigoConstrucao) {

  /**
   * Instancia da Classe
   */
  var oInstancia             = this;
  var lPrimeiroCarregamento  = true;
  /**
   *
   */
  var sNomeInstancia         = sNomeInstanciaParametro;
  var aCaracteristicas       = new Array();
  var iMatricula             = new Number(iCodigoMatricula);
  var iConstrucao            = new Number(iCodigoConstrucao);
  var sRPC                   = 'cad4_iptuconstr.RPC.php';
  this.oGridCaracteristicas  = new Object();
  this.aSelecionados         = new Array();
  /**
   *
   */
  this.isPrimeiroCarregamento = function () {
    return lPrimeiroCarregamento;

 }

  this.setPrimeiroCarregamento = function ( lCondicao ) {
    lPrimeiroCarregamento = lCondicao;
  }

  /**
   * Retorna o Nome
   */
  this.getNomeInstancia    = function() {
    return sNomeInstancia;
  }
  /**
   * Retorna a Instancia do Componente
   * @returns DBViewCaracteristicasConstrucao
   */
  this.getInstancia        = function() {
    return this;
  }
  /**
   * Define as Caracteristicas da Construção
   */
  this.setCaracteristicas  = function(aCaracter) {
    aCaracteristicas = aCaracter;
  }
  /**
   * Retorna as Caracteristicas da Construcao
   */
  this.getCaracteristicas  = function() {
    return aCaracteristicas;
  }
  /**
   * Retorna a Caracteristica da Construcao
   */
  this.getMatricula        = function() {
    return iMatricula;
  }
  /**
   * Retorna o ID da Construcao
   */
  this.getCodigoConstrucao = function() {
    return iCodigoConstrucao;
  }
  /**
   * Retorna o Caminho do arquivo para onde os dados do componente serão enviados
   */
  this.getCaminhoArquivoRPC = function() {
    return sRPC;
  };

  if ( iCodigoMatricula == "" && iCodigoConstrucao == "" ) {
    return this;
  }

  this.carregarCaracteristicas();

  return this;
}

/**
 * Carrega os dados das Caracteristica da Construcao
 * @returns void
 */
DBViewCaracteristicasConstrucao.prototype.carregarCaracteristicas = function(iMatricula, iConstrucao) {

  js_divCarregando('Carregando características...', 'msgBox');

  /**
   * Utilizada para não perder referencia dentro de uma outra função.
   */
  var oInstancia            = this.getInstancia();
  /**
   * Parametros do RPC
   */
  var oParam                = new Object();
  oParam.sExec              = "getCaracteristicasSelecao";
  oParam.iTipoConstrucao    = 1;
  oParam.iMatricula         = iMatricula  == null ? this.getMatricula()        : iMatricula;
  oParam.iCodigoConstrucao  = iConstrucao == null ? this.getCodigoConstrucao() : iConstrucao;

  /**
   * Transforma parametros do RPC em uma string JSON
   */
  var sJSONParametros       = Object.toJSON(oParam);

  /**
   * Dados da Requisição AJAX
   */
  var oRequisicao           = new Object();
  oRequisicao.method        = "post";
  oRequisicao.parameters    = "json=" + sJSONParametros;
  oRequisicao.asynchronous  = false;
  oRequisicao.onComplete    = function( oAjax ) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus== "2") {
      alert(oRetorno.sMessage.urlDecode());
      return;
    }

    /**
     * Define as Caracteristicas encontradas;
     */
    oInstancia.setCaracteristicas(oRetorno.aCaracteristicas);
    oInstancia.aSelecionados = oRetorno.aSelecionadas;

  };

  /**
   * Executa a Requisição
   */
  var oAjax          = new Ajax.Request(this.getCaminhoArquivoRPC(), oRequisicao);

}

DBViewCaracteristicasConstrucao.prototype.criarJanela = function() {

    /**
     * Inicia WindowAux
     */
    if ( typeof(this.oJanela) == "object" ) {
      js_showWindow( this.oJanela.divWindow );
    } else {

      var sConteudo = " <center>                                                                                           \n";
      sConteudo    += "   <div id='" + this.getNomeInstancia() + "headerCaracteristicas'></div>                            \n";
      sConteudo    += "   <fieldset>                                                                                       \n";
      sConteudo    += "     <legend>                                                                                       \n";
      sConteudo    += "       <strong>Características: </strong>                                                           \n";
      sConteudo    += "     </legend>                                                                                      \n";
      sConteudo    += "     <div id='" + this.getNomeInstancia() + "contentCaracteristicas'></div>                         \n";
      sConteudo    += "   </fieldset>                                                                                      \n";
      sConteudo    += "   <div id='" + this.getNomeInstancia() + "footerCaracteristicas'>                                  \n";
      sConteudo    += "     <br />                                                                                         \n";
      sConteudo    += "     <input type='button' value='Salvar' onClick='" + this.getNomeInstancia() + ".oJanela.hide();'> \n";
      sConteudo    += "   </div>                                                                                           \n";
      sConteudo    += " </center>                                                                                          \n";

      var sMsg      = "Selecione as características relacionadas ao Grupo \"Construções\"";

      this.oJanela  = new windowAux(this.getNomeInstancia()+ ".oJanela",
                                    "Características da Constução",
                                    650,
                                    560);
      this.oJanela.setContent(sConteudo);
      this.oJanela.show(10, window.availWidth);

      $( this.getNomeInstancia() + "contentCaracteristicas" ).style.width = this.oJanela.getWidth() - 30;


       /**
        * Inicia MessageBoard
        */
       this.oPainelMensagem = new DBMessageBoard(this.getNomeInstancia() + "_painel",
                                                 'Características da Construção',
                                                 sMsg,
                                                 $(this.getNomeInstancia() + 'headerCaracteristicas'));
       this.oPainelMensagem.show();
     }
   }

DBViewCaracteristicasConstrucao.prototype.criarDataGrid = function () {

  this.oGridCaracteristicas              = new DBGrid('grid_caracteristicas');
  this.oGridCaracteristicas.nameInstance = this.getNomeInstancia() +  '.oGridCaracteristicas';
  this.oGridCaracteristicas.setHeader    ( ['Codigo', 'Grupo da Característica', 'Característica'] );
  this.oGridCaracteristicas.setCellWidth ( ["20%", "40%", "40%"] );
  this.oGridCaracteristicas.setCellAlign ( ['center', "left", "center"] );
  this.oGridCaracteristicas.setHeight("350");
  this.oGridCaracteristicas.show         ( $(this.getNomeInstancia() + "contentCaracteristicas") );
}

DBViewCaracteristicasConstrucao.prototype.renderizarDadosGrid = function() {

  this.oGridCaracteristicas.clearAll     (true);
  var aCaracteristicas = this.getCaracteristicas();
  for (var iIndiceCaracteristica = 0 in aCaracteristicas ) {

    var oCaracteristica  = aCaracteristicas[iIndiceCaracteristica];
    var aCelulas         = new Array();
    aCelulas[0]          = oCaracteristica.iCodigoGrupo;
    aCelulas[1]          = oCaracteristica.sDescricaoGrupo.urlDecode();

    var sComboBox        = "<select id=\"comboCaracter"+ iIndiceCaracteristica +"\" style='width: 100%'>\n";
    sComboBox           += "  <option value='0'>Nenhuma...</option>                       \n";
    for (var iOpcao = 0; iOpcao < oCaracteristica.aOpcoes.length; iOpcao++) {

      var oOpcao         = oCaracteristica.aOpcoes[iOpcao];
      var sSelecionado   = oOpcao.lSelecionada ? "selected" : "";
      sComboBox         += "  <option value='" + oOpcao.iCodigoCaracteristica + "' " + sSelecionado + " > " + oOpcao.iCodigoCaracteristica  + " - " + oOpcao.sDescricaoCaracteristica.urlDecode() + "</option>\n";
    }
    sComboBox           += "</select>";
    aCelulas[2]          = sComboBox;
    this.oGridCaracteristicas.addRow(aCelulas);
  }
  this.oGridCaracteristicas.renderRows();
}

DBViewCaracteristicasConstrucao.prototype.show = function ( oElemento ) {
  if ( this.isPrimeiroCarregamento() ) {

    this.criarJanela();
    this.criarDataGrid();
    this.renderizarDadosGrid();
    this.setPrimeiroCarregamento(false);
  }
  js_showWindow( this.oJanela.divWindow );

}

DBViewCaracteristicasConstrucao.prototype.importarDadosConsntrucao = function( iMatriculaImportada, iIdConstrucao) {

  if ( iMatriculaImportada == null ) {
    return false;
  }
  if ( iIdConstrucao == null) {
    return false;
  }

  this.carregarCaracteristicas(iMatriculaImportada, iIdConstrucao);
  this.setPrimeiroCarregamento(true);
}

DBViewCaracteristicasConstrucao.prototype.getSelecao = function() {

  if ( this.oGridCaracteristicas instanceof DBGrid  ) {

    this.aSelecionados = new Array();

    for (var iIndiceLinha = 0; iIndiceLinha < this.oGridCaracteristicas.aRows.length; iIndiceLinha++) {

      with (this.oGridCaracteristicas.aRows[iIndiceLinha]) {

        if ( this.aSelecionados.indexOf( aCells[2].getValue() ) == -1 ) {
          this.aSelecionados.push(aCells[2].getValue());
        }
      }
    }

  }
  return this.aSelecionados;
}


function js_showWindow(oElemento) {
  oElemento.style.display = '';
}
