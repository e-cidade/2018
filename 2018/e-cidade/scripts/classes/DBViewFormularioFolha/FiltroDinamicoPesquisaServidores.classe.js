require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
DBViewFormularioFolha.FiltroDinamicoPesquisaServidores = {
 
  "makeComboRegime" : function( oElemento ) {

     oElemento.innerHTML = '';
     require_once("scripts/classes/DBViewFormularioFolha/ComboRegime.js");

     var oLabel       = document.createElement("label");
     oLabel.innerHTML = "Regime:";
     oElemento.appendChild(oLabel);
     oElemento.appendChild(document.createTextNode(" "));//Hack Alinhamento por causa da identação do fonte

     var oComboRegime = new DBViewFormularioFolha.ComboRegime(); 
     oComboRegime.addStyle('width', "100px");
     oComboRegime.show(oElemento, true);
     oComboRegime.getElement().name = "rh30_regime";
     oComboRegime.getElement().setValue(DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores.rh30_regime);
     return;
  }, 

  "makeLookUpLotacao" : function(oElemento) {
   
    require_once("scripts/widgets/DBLookUp.widget.js");
    var oLabel                 = document.createElement("label");
    var oAncoraPesquisa        = document.createElement("a"); 
    var oInputCodigoLotacao    = document.createElement("input");
    var oInputDescricaoLotacao = document.createElement("input");
    var oValores               = DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores;
    
    oLabel.appendChild(oAncoraPesquisa);

    oInputCodigoLotacao.setAttribute("lang", "r70_codigo");
    oInputCodigoLotacao.setAttribute("name", "r70_codigo");
    oInputCodigoLotacao.setValue(oValores.r70_codigo);

    oInputDescricaoLotacao.setAttribute("lang", "r70_descr");
    oInputDescricaoLotacao.setAttribute("name", "r70_descr");
    oInputDescricaoLotacao.setValue(oValores.r70_descr);

    oInputCodigoLotacao.ondrop = function() {
      return false;
    };

    oAncoraPesquisa.innerHTML = "Lotação:";

    oElemento.appendChild(oLabel);
    oElemento.appendChild(document.createTextNode(" ")); //Hack Alinhamento por causa da identação do fonte
    oElemento.appendChild(oInputCodigoLotacao);
    oElemento.appendChild(document.createTextNode(" ")); //Hack Alinhamento por causa da identação do fonte
    oElemento.appendChild(oInputDescricaoLotacao);

    var oLookUp = new DBLookUp(oAncoraPesquisa, oInputCodigoLotacao, oInputDescricaoLotacao, {
        "sArquivo"      : "func_rhlota.php",
        "sQueryString"  : "&instit=1",
        "sObjetoLookUp" : "db_iframe_rhlota"
      });
    
    oLookUp.oInputID.onchange = function(oEvento) {

      if (!js_ValidaCampos( this, 1, 'Lotação', 't', 'f', oEvento)) {
        this.oInstancia.oInputDescricao.value = "";
        return false;
      }

      this.oInstancia.abrirJanela(false);
    };
  },

  "makeLookUpCargo" : function(oElemento) {

    require_once("scripts/widgets/DBLookUp.widget.js");
    var oValores               = DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores;
    var oLabel                 = document.createElement("label");
    var oAncoraPesquisa        = document.createElement("a"); 
    var oInputCodigoCargo      = document.createElement("input");
    var oInputDescricaoCargo   = document.createElement("input");

    oLabel.appendChild(oAncoraPesquisa);

    oInputCodigoCargo.setAttribute("lang", "rh37_funcao");
    oInputCodigoCargo.setAttribute("name", "rh37_funcao");
    oInputCodigoCargo.setValue(oValores.rh37_funcao);

    oInputDescricaoCargo.setAttribute("lang", "rh37_descr");
    oInputDescricaoCargo.setAttribute("name", "rh37_descr");
    oInputDescricaoCargo.setValue(oValores.rh37_descr);

    oInputCodigoCargo.ondrop = function() {
      return false;
    };

    oAncoraPesquisa.innerHTML = "Cargo:";

    oElemento.appendChild(oLabel);
    oElemento.appendChild(document.createTextNode(" "));//Hack Alinhamento por causa da identação do fonte
    oElemento.appendChild(oInputCodigoCargo);
    oElemento.appendChild(document.createTextNode(" "));//Hack Alinhamento por causa da identação do fonte
    oElemento.appendChild(oInputDescricaoCargo);

    var oLookUp = new DBLookUp(oAncoraPesquisa, oInputCodigoCargo, oInputDescricaoCargo, {
        "sArquivo"      : "func_rhfuncao.php",
        "sObjetoLookUp" : "db_iframe_rhfuncao"
      });
    
    oLookUp.oInputID.onchange = function(oEvento) {
      
      if (!js_ValidaCampos( this, 1, 'Cargo', 't', 'f', oEvento)) {
        this.oInstancia.oInputDescricao.value = "";
        return false;
      }

      this.oInstancia.abrirJanela(false);
    };
  },

  "makeLookUpPadrao" : function(oElemento) {

    require_once("scripts/widgets/DBLookUp.widget.js");
    var oValores               = DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores;
    var oLabel                 = document.createElement("label");
    var oAncoraPesquisa        = document.createElement("a"); 
    var oInputCodigoPadrao     = document.createElement("input");
    var oInputDescricaoPadrao  = document.createElement("input");

    oLabel.appendChild(oAncoraPesquisa);

    oInputCodigoPadrao.setAttribute("lang", "r02_codigo");
    oInputCodigoPadrao.setAttribute("name", "r02_codigo");
    oInputCodigoPadrao.setValue(oValores.r02_codigo);

    oInputDescricaoPadrao.setAttribute("lang", "r02_descr");
    oInputDescricaoPadrao.setAttribute("name", "r02_descr");
    oInputDescricaoPadrao.setValue(oValores.r02_descr);

    oInputCodigoPadrao.ondrop = function() {
      return false;
    };

    oAncoraPesquisa.innerHTML = "Padrão:";

    oElemento.appendChild(oLabel);
    oElemento.appendChild(document.createTextNode(" "));//Hack Alinhamento por causa da identação do fonte
    oElemento.appendChild(oInputCodigoPadrao);
    oElemento.appendChild(document.createTextNode(" "));//Hack Alinhamento por causa da identação do fonte
    oElemento.appendChild(oInputDescricaoPadrao);

    var oLookUp = new DBLookUp(oAncoraPesquisa, oInputCodigoPadrao, oInputDescricaoPadrao, {
        "sArquivo"      : "func_padroes.php",
        "sObjetoLookUp" : "db_iframe_padroes"
      });
    
    oLookUp.oInputID.onchange = function(oEvento) {
      
      if (!js_ValidaCampos( this, 1, 'Padrao', 't', 'f', oEvento)) {
        this.oInstancia.oInputDescricao.value = "";
        return false;
      }

      this.oInstancia.abrirJanela(false);
    };
  }
};

DBViewFormularioFolha.FiltroDinamicoPesquisaServidores.oValores = {
  "rh30_regime"  : "",
  "r70_codigo"   : "",
  "r70_descr"    : "",
  "rh37_funcao"  : "",
  "rh37_descr"   : "",
  "r02_codigo"   : "",
  "r02_descr"    : ""
}