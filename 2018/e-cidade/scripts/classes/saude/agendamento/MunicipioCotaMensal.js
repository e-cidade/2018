/**
 *
 * Bibliotecas utilizadas no projeto
 *
 */
require_once('scripts/classes/saude/agendamento/CotaMensalExame.js')

var MunicipioCotaMensal = function(rpc, parametroPesquisa) {
  this.rpc               = rpc;
  this.parametroPesquisa = parametroPesquisa;
}

MunicipioCotaMensal.prototype = new CotaMensalExame();
MunicipioCotaMensal.prototype.constructor = MunicipioCotaMensal();

MunicipioCotaMensal.prototype.comportamentoCadastroCallback = function(){

  /**
   * [innerHTML troca o nome do fieldset se estiver na rotina de Cota de Exame Municípais]
   *
   */
  document.getElementById('CotaMunicipio').innerHTML =  "Cota de Exames Municípais";

  /**
   *
   * Colocamos um label no db_ancora ao carregar a página
   *
   */
  var tr           = document.querySelector("#individual td a");
      tr.innerHTML = '<label for="Exame">Exame:</label>';

  $('prestador').hide();
  $('prestador').disabled = true;
  $('tipoLabel').style = "width: 30px";
  $('tipo').remove('2');
  $('tipo').remove('1');

  $('tipo').options[$('tipo').options.length] = new Option("Individual", "3");
  $('tipo').options[$('tipo').options.length] = new Option("Grupo", "4");
};

MunicipioCotaMensal.prototype.comportamentoTipo = function(){

    if ( $('tipo').value == 3 ) {

      $('individual').show();
      $('gridGrupoExames').hide();
      $('descricaoGrupo').hide();
    }

    if ( $('tipo').value == 4 )  {

      $('descricaoGrupo').show();
      $('gridGrupoExames').show();
      $('ctnLancador').show();
      $('individual').hide();
    }
}

/**
 * Incluimos as cotas conforme dados submitados no formulário
 *
 * @param  array     aCompetencia  Dados subetidos do formulário divido por competência
 */
MunicipioCotaMensal.prototype.incluirCota = function(aCompetencia){

  /**
   * @todo verificar se é possível por esta função no CotaMensalExame.js
   */
  var dadosGrupoFormulario = this.buscarDadosGrupoFormulario();

  var oParametros = {
    aCompetencia : aCompetencia,
    iQuantidade  : $F('quantidade'),
    sNome        : dadosGrupoFormulario.nome,
    procedimento : dadosGrupoFormulario.procedimento,
    iTipo        : $F('tipo'),
    sExecucao    : "salvar"
  }

  var oRequest = new AjaxRequest(this.rpc, oParametros);

  oRequest.setCallBack(function( oRetorno, lErro ){

    if (lErro) {

      alert(oRetorno.sMensagem);
      return false;
    }

    this.carregarGrid();
  }.bind(this));

  alert('Cota de Exame para o Município Cadastrado com Sucesso.');
  oRequest.execute();
}

/**
 * Buscamos os dados dos exames inseridos
 *
 * @return obejct
 */
MunicipioCotaMensal.prototype.buscarDadosGrupoFormulario = function(){

  var sNome        = $F('sd63_c_nome');
  var procedimento = $F('sd63_i_codigo');

  if ( $F('tipo') == 4  ) {

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
MunicipioCotaMensal.prototype.validarGrid = function(aCompetencia){

  var dadosGrupoFormulario = this.buscarDadosGrupoFormulario();

  var oParametros = {
    aCompetencia  : aCompetencia,
    procedimento  : dadosGrupoFormulario.procedimento,
    sNome         : dadosGrupoFormulario.nome,
    iTipo         : $F('tipo'),
    sExecucao     : "validar"
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
