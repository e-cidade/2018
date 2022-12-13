/**
 * 
 */
(function(window){

  require_once("scripts/AjaxRequest.js");

  var TipoAssentamentoFactory = {
    "oAjax" : new AjaxRequest('pes4_assentamento.RPC.php')
  };
  /**
   * Constroi os formulario de tipo de assentamento
   * 
   * @param  {String} sTipoAssentamento - Natureza do Assentamento
   * @return
   */
  TipoAssentamentoFactory.create = function (sTipoAssentamento, oParametrosAdicionais) {

    var oInstance;

    switch(sTipoAssentamento.toLowerCase().trim()) {

      case 'substituição':
      case 'substituicao':

        require_once("scripts/classes/recursoshumanos/TipoAssentamentoSubstituicao.js");
        oInstance = new TipoAssentamentoSubstituicao();
        if (oParametrosAdicionais &&  oParametrosAdicionais.matricula_servidor_substituido ) {
          oInstance.setServidorSubstituido(oParametrosAdicionais.matricula_servidor_substituido,  oParametrosAdicionais.nome_servidor_substituido);
        }
        break;

      case 'rra':
        require_once("scripts/classes/recursoshumanos/TipoAssentamentoRRA.js");
        oInstance = new TipoAssentamentoRRA();

        if (oParametrosAdicionais) {
          oInstance.setValorTotalDevido (oParametrosAdicionais.valor_total_devido);
          oInstance.setNumeroDeMeses    (oParametrosAdicionais.numero_meses);
          oInstance.setEncargosJudiciais(oParametrosAdicionais.encargos_judiciais);
          oInstance.setCaminhoFormulario();
        }
        break;

      default:
        require_once("scripts/classes/recursoshumanos/TipoAssentamentoPadrao.js");
        oInstance = new TipoAssentamentoPadrao();
        break;
    }
    return oInstance;
  };

  TipoAssentamentoFactory.createFromAssentamento = function( iCodigoAssentamento ) {

    var oAjax    = TipoAssentamentoFactory.oAjax;
    var oRetorno; 
    oAjax.setParameters({
      'exec': 'getAssentamento',
      'iCodigoAssentamento' :iCodigoAssentamento
    });

    oAjax.asynchronous(false);
    oAjax.setCallBack( function(oResponse, lErro) {

      if (lErro) {
        throw 'Erro ao buscar os dados';
      }

      var oAssentamento = JSON.parse(oResponse.oAssentamento);
      oRetorno          = TipoAssentamentoFactory.create( oAssentamento.natureza, oAssentamento );
    });

    oAjax.setMessage("Buscando dados do assentamento...");
    oAjax.execute();
    return oRetorno;
  };

 TipoAssentamentoFactory.createFromTipoAssentamento = function( iCodigoTipoAsseentamento ) {

    var oAjax    = TipoAssentamentoFactory.oAjax;
    var oRetorno; 
    oAjax.setParameters({'exec':'getNaturezaAssentamento', 'iTipoAssentamento': iCodigoTipoAsseentamento});
    oAjax.asynchronous(false);
    oAjax.setCallBack( function(oResponse, lErro) {

      if (lErro) {
        throw 'Erro ao buscar os dados';
      }

      oRetorno      = TipoAssentamentoFactory.create(oResponse.natureza.urlDecode());
    });

    oAjax.setMessage("Buscando dados do assentamento...");
    oAjax.execute();
    return oRetorno;
 };

 TipoAssentamentoFactory.createFromTipoPortaria = function( iCodigoTipoPortaria ) {

    var oAjax    = TipoAssentamentoFactory.oAjax;
    var oRetorno; 
    oAjax.setParameters({'exec':'getNaturezaAssentamento', 'iCodigoTipoPortaria': iCodigoTipoPortaria});
    oAjax.asynchronous(false);
    oAjax.setCallBack( function(oResponse, lErro) {

      if (lErro) {
        throw 'Erro ao buscar os dados';
      }

      oRetorno      = TipoAssentamentoFactory.create(oResponse.natureza);
    });

    oAjax.setMessage("Buscando dados do assentamento...");
    oAjax.execute();
    return oRetorno;
 };

  window.TipoAssentamentoFactory = TipoAssentamentoFactory;
  return TipoAssentamentoFactory;
})(window);
