(function(window){

  require_once("scripts/classes/recursoshumanos/TipoAssentamentoPadrao.js");

  var TipoAssentamentoRRA = function() {

    /**
     * Quando instanciar o Objeto, chama o pai, equivalente PHP "parent::__construct"
     */
    TipoAssentamentoPadrao.call(this);

    this.nValorTotalDevido    = '';
    this.iNumeroDeMeses       = '';
    this.nEncargosJudiciais   = '';
    this.sCaminhoFormulario   = 'forms/db_frmassentamentorra.php?valor_total_devido=' + this.nValorTotalDevido 
      + '&numero_de_meses='+this.iNumeroDeMeses 
      + '&encargos_judiciais='+this.nEncargosJudiciais;

    this.setValorTotalDevido    = function( nValorTotalDevido ) {
      this.nValorTotalDevido    = nValorTotalDevido;
    }.bind(this);

    this.setNumeroDeMeses       = function( iNumeroDeMeses ) {
      this.iNumeroDeMeses       = iNumeroDeMeses;
    }.bind(this);

    this.setEncargosJudiciais   = function( nEncargosJudiciais ) {
      this.nEncargosJudiciais   = nEncargosJudiciais;
    }.bind(this);
  
    this.setCaminhoFormulario   = function() {
      this.sCaminhoFormulario   = 'forms/db_frmassentamentorra.php?h83_valor=' + this.nValorTotalDevido 
        + '&h83_meses='+this.iNumeroDeMeses 
        + '&h83_encargos='+this.nEncargosJudiciais
        + '&db_opcao='+db_opcao;
    }.bind(this);

  };

  TipoAssentamentoRRA.prototype = Object.extend(TipoAssentamentoRRA.prototype, TipoAssentamentoPadrao.prototype);
  TipoAssentamentoRRA.prototype.make = function () {    

    if ($('db_opcao').name.toLowerCase() == 'excluir') {

      $('ancora_servidor_substituido').setStyle('color: #000; text-decoration: none;');
      $('h16_dtterm').setStyle('background-color: #deb887');
      $('rh161_regist').setStyle('background-color: #deb887');
    }
  };

  TipoAssentamentoRRA.prototype.makeEvents = function () {    

    TipoAssentamentoPadrao.prototype.makeEvents.apply(this, arguments);

    require_once('scripts/numbers.js');

    $('h83_valor').on('keypress',    function(event) { return mascaraValor(event, $('h83_valor'));    });
    $('h83_encargos').on('keypress', function(event) { return mascaraValor(event, $('h83_encargos')); });
  };

  TipoAssentamentoRRA.prototype.validarCampos = function (oEvent) {

    if(this.oBotaoAcao.name.toLowerCase() == 'excluir') {
      return;
    }

    if($('h83_valor').value == "") {
      
      alert("O campo Valor Total Devido é de preenchimento obrigatório.");
      oEvent.preventDefault();
      oEvent.stopPropagation();
    }

    if($('h83_meses').value == "") {
      
      alert("O campo Número de Meses é de preenchimento obrigatório.");
      oEvent.preventDefault();
      oEvent.stopPropagation();
    }
  };

  window.TipoAssentamentoRRA = TipoAssentamentoRRA;
})(window);
