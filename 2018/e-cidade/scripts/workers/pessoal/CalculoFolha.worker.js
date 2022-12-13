(function(oWorker) {

  var CalculoFolha = function(oWorker) {

    this.oWorker         = oWorker;
    this.oRequest        = new XMLHttpRequest();
    this.oRequest.onload = this.processarRespostaRequisicao;

    this.receberMensagem = (function(oMessageEvent){
      this.processarRequisicao(oMessageEvent.data);
    }).bind(this);

    this.processarRequisicao = (function(oDados){

      var oParametros = {
        "campo_auxilio_carg"      : "",
        "campo_auxilio_loca"      : "",
        "campo_auxilio_orga"      : "",
        "campo_auxilio_recu"      : "",
        "campo_auxilio_rubr"      : "",
        "db_debug"                : "false",
        "faixa_lotac"             : '',
        "faixa_regis"             : oDados.servidor.aMatriculas.join(','),
        "hidTipoFolha"            : oDados.mock_parametros.hidTipoFolha,
        "opcao_filtro"            : "s",
        "opcao_geral"             : oDados.mock_parametros.opcao_geral,
        "opcao_gml"               : "m",
        'processamento_background': 'sim'
      };
      var aQueryString = [];

      for( var sChave in oParametros ) {
        aQueryString.push(sChave+"="+oParametros[sChave]);
      }
      sQueryString = aQueryString.join("&");
      this.oRequest.open("GET", "../../../pes4_gerafolha002.php?"+sQueryString, false);
      this.oRequest.send();

      oDados.mock_parametros = oParametros;
      oDados.resposta        = JSON.parse(this.oRequest.responseText);

      this.devolverMensagem(oDados);

    }).bind(this);


    this.processarRespostaRequisicao = (function(){
///
    }).bind(this);

    this.devolverMensagem = (function(oMensagem){
      this.oWorker.postMessage(oMensagem);
    }).bind(this);

    oWorker.addEventListener("message", this.receberMensagem );
  };
  return new CalculoFolha(oWorker);
})(this);
