(function(window){

  require_once("scripts/classes/recursoshumanos/TipoAssentamentoPadrao.js");

  var TipoAssentamentoSubstituicao = function() {

    /**
     * Quando instanciar o Objeto, chama o pai, equivalente PHP "parent::__construct"
     */
    TipoAssentamentoPadrao.call(this);

    this.iMatriculaSubstituido   = '';
    this.sNomeSubstituido        = '';
    this.sCaminhoFormulario      = 'forms/db_frmassentamentosubstituicao.php?rh161_regist=' + this.iMatriculaSubstituido + '&nome_substituido='+this.sNomeSubstituido;

    this.setServidorSubstituido  = function( iMatricula, sNome ) {
      this.iMatriculaSubstituido   = iMatricula;
      this.sNomeSubstituido        = sNome;
      this.sCaminhoFormulario      = 'forms/db_frmassentamentosubstituicao.php?rh161_regist=' + this.iMatriculaSubstituido + '&nome_substituido='+this.sNomeSubstituido;
    }.bind(this);

  };

  TipoAssentamentoSubstituicao.prototype = Object.extend(TipoAssentamentoSubstituicao.prototype, TipoAssentamentoPadrao.prototype);
  TipoAssentamentoSubstituicao.prototype.make = function () {

    require_once('scripts/widgets/DBLookUp.widget.js');
    var oLookUpServidorSubstituido = new DBLookUp(
      $('ancora_servidor_substituido'),
      $('rh161_regist'),
      $('nome_substituido'),
      { "sArquivo" : "func_rhpessoal.php", "sObjetoLookUp" : "db_iframe_rhpessoal", "aParametrosAdicionais" : ["testarescisao=true"]}
    );

    $('h16_dtterm').setStyle('background-color: #fff');

    if ($('db_opcao').name.toLowerCase() == 'excluir') {

      $('ancora_servidor_substituido').setStyle('color: #000; text-decoration: none;');
      $('h16_dtterm').setStyle('background-color: #deb887');
      $('rh161_regist').setStyle('background-color: #deb887');
    }
  };

  TipoAssentamentoSubstituicao.prototype.validarCampos = function (oEvent) {

    if(this.oBotaoAcao.name.toLowerCase() == 'excluir') {
      return;
    }

    if($('rh161_regist').value == "") {
      
      alert("É necessário informar o servidor a substituir.");
      oEvent.preventDefault();
      oEvent.stopPropagation();
    } else {
      this.validarDataFinal(oEvent);
    }
  };

  TipoAssentamentoSubstituicao.prototype.validarDataFinal = function (oEvent) {

    var sDataInicial  = $F('h16_dtconc');
    var sDataFinal    = $F('h16_dtterm');

    if(sDataFinal == "") {
      alert("A data final deve ser preenchida.");
      oEvent.preventDefault();
      oEvent.stopPropagation();
      return false;
    }

    if(getDateInDatabaseFormat(sDataFinal) < getDateInDatabaseFormat(sDataInicial)) {
      alert("A data final não pode ser menor que a data inicial.");
      oEvent.preventDefault();
      oEvent.stopPropagation();
      return false;
    }

    /**
     * Busca o mês e ano das data inicial e final para compará-las
     */
    var sMesAnoDataInicial     = sDataInicial.match(/.*\/(\d{2}\/\d{4})/)[1];
    var sMesAnoDataFinal       = sDataFinal.match(/.*\/(\d{2}\/\d{4})/)[1];

    if(sMesAnoDataFinal != sMesAnoDataInicial) {
      alert("A data final deve estar no mesmo mês e ano da data inicial.");
      oEvent.preventDefault();
      oEvent.stopPropagation();
      return false;
    }
  };

  window.TipoAssentamentoSubstituicao = TipoAssentamentoSubstituicao;
})(window);
