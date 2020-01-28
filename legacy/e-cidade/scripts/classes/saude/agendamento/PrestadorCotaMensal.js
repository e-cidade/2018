/**
 *
 * Bibliotecas utilizadas no projeto
 *
 */
require_once('scripts/classes/saude/agendamento/CotaMensalExame.js')

var PrestadorCotaMensal = function(rpc, parametroPesquisa) {
  this.rpc               = rpc;
  this.parametroPesquisa = parametroPesquisa;
}

PrestadorCotaMensal.prototype = new CotaMensalExame();
PrestadorCotaMensal.prototype.constructor = PrestadorCotaMensal();

/**
 * Incluimos as cotas conforme dados submitados no formulário
 *
 * @param  array     aCompetencia  Dados subetidos do formulário divido por competência
 */
PrestadorCotaMensal.prototype.incluirCota = function(aCompetencia){

  var dadosGrupoFormulario = this.buscarDadosGrupoFormulario();

  var oParametros = {
    aCompetencia      : aCompetencia,
    iQuantidade       : $F('quantidade'),
    sNome             : dadosGrupoFormulario.nome,
    prestadorVinculo  : dadosGrupoFormulario.procedimento,
    iTipo             : $F('tipo'),
    sExecucao         : "salvar"
  }

  var oRequest = new AjaxRequest(this.rpc, oParametros);

  oRequest.setCallBack(function( oRetorno, lErro ){

    if (lErro) {

      alert(oRetorno.sMensagem);
      return false;
    }

    this.carregarGrid();
  }.bind(this));

  oRequest.execute();
}

/**
 * Buscamos os dados dos exames inseridos
 *
 * @return obejct
 */
PrestadorCotaMensal.prototype.buscarDadosGrupoFormulario = function(){

  var sNome        = $F('sd63_c_nome');
  var procedimento = $F('s112_i_prestadorvinc');

  if ( $F('tipo') == 2  ) {

    sNome          = $F('age02_nome');
    procedimento   = Array();
    var aRegistros = oLancador.getRegistros();

    aRegistros.each(function(oParametro, iIndice) {
      procedimento.push(oParametro.sClasse);
    });
  }

  return {
    nome : sNome,
    procedimento : procedimento
  }
}

/**
 * Validamos os dados que estão na grid com os que estão sendo incluidos, para que não exista duplicidade
 *
 * @param  array     aCompetencia  Dados subetidos do formulário divido por competência
 */
PrestadorCotaMensal.prototype.validarGrid = function(aCompetencia){

  var dadosGrupoFormulario = this.buscarDadosGrupoFormulario();

  var oParametros = {
    aCompetencia      : aCompetencia,
    prestadorVinculo  : dadosGrupoFormulario.procedimento,
    sNome             : dadosGrupoFormulario.nome,
    iTipo             : $F('tipo'),
    sExecucao         : "validar"
  }

  var lValidado         = true;
  var oRequestValidacao = new AjaxRequest(this.rpc, oParametros);
  oRequestValidacao.setCallBack(function( oRetorno, lErro ){

    if (lErro) {

      alert(oRetorno.sMensagem);
      lValidado = false;
      return false;
    }
  }.bind(this));

  oRequestValidacao.asynchronous(false);
  oRequestValidacao.execute();
  return lValidado;
}

/**
 * Ao verificar que existe cota mensal, colocamos uma mensagem de aviso na aba de cota diária
 *
 * @param  object   oRetorno    retorno do ajax
 */
PrestadorCotaMensal.prototype.carregarGridAjaxCallback = function(oRetorno){
  parent.iframe_a3.$('msg_cota').hide();

  if ( oRetorno.aCotas.length > 0 ) {
    parent.iframe_a3.$('msg_cota').show();
  }
}
