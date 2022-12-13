/**
 *
 * Bibliotecas utilizadas no projeto
 *
 */

require_once('scripts/widgets/Collection.widget.js');
require_once('scripts/widgets/DatagridCollection.widget.js');
require_once('scripts/widgets/DBLancador.widget.js')

var CotaMensalExame = function() {
  this.grid              = null;
  this.collection        = null;
  this.rpc               = null;
  this.parametroPesquisa = null;
}

/**
 * Comportamento padrão do formulário
 */
CotaMensalExame.prototype.comportamentoCadastro = function() {

  /**
   * Esconde as informações referentes a Mensal e Grupo
   * para que o usuário possa selecionar a opção de escolha
   */
  $('individual').hide();
  $('descricaoGrupo').hide();
  $('gridGrupoExames').hide();

  /**
   *
   * Verifica a opção de cota
   * que o usuário vai escolher
   * se vai ser Individual ou Grupo
   */
  $('tipo').observe('change', function() {

    $('individual').hide();
    $('descricaoGrupo').hide();
    $('gridGrupoExames').hide();

    /**
     *
     * Limpa os campos ao trocar o
     *  Tipo de Cota
     *
     */
    $('quantidade').clear();
    $('age02_nome').clear();
    $('mes_inicial').clear();
    $('mes_final').clear();
    $('ano_inicial').clear();
    $('ano_final').clear();
    $('sd63_c_procedimento').clear();
    $('sd63_c_nome').clear();

    this.comportamentoTipo();

    /**
     * DBLancador com os exames do grupo
     */
    oLancador = new DBLancador('Lancador');
    oLancador.setNomeInstancia('oLancador');
    oLancador.setLabelAncora('Exames: ');
    oLancador.setCamposEscondidos(true);
    oLancador.setParametrosPesquisa( this.parametroPesquisa[0],
                                     this.parametroPesquisa[1],
                                     this.parametroPesquisa[2] );
    oLancador.setGridHeight(150);
    oLancador.setHabilitado(true);
    oLancador.show($('ctnLancador'));
  }.bind(this));

  $('tipo').dispatchEvent(new Event('change'));

  $('btnSalvar').observe('click', function() {

    if ( !this.validarFormulario() ) {
      return false;
    }

    var iMesInicial = parseInt( $F('mes_inicial') );
    var iAnoInicial = parseInt( $F('ano_inicial') );
    var iMesFinal   = parseInt( $F('mes_final') );
    var iAnoFinal   = parseInt( $F('ano_final') );

    var aCompetencia = new Array();

    for (var iAno = iAnoInicial; iAno <= iAnoFinal; iAno++) {

      var iMesTotal = 12;

      if (iAnoInicial == iAnoFinal) {
          iMesTotal = iMesFinal;
      }

      if (iAno <= iAnoFinal && iAno != iAnoInicial) {
        iMesInicial = 1;

        if (iAno == iAnoFinal) {
          iMesTotal = iMesFinal;
        }
      }

      for (var iMes = iMesInicial; iMes <= iMesTotal; iMes++) {
        aCompetencia.push({mes: parseInt(iMes), ano: parseInt(iAno)});
      }
    }

    if ( !this.validarGrid(aCompetencia) ) {
      return false;
    }

    this.incluirCota(aCompetencia);

    /**
     * Limpa os dados do formulário
     * após incluir as informações na Grade Mensal
     */
     this.limpar();

  }.bind(this));

  $('btnLimpar').observe('click', function(){
    this.limpar();
  }.bind(this));

  this.comportamentoCadastroCallback();
  this.limpar();
}

CotaMensalExame.prototype.comportamentoTipo = function(){

    if ( $('tipo').value == 1 ) {

      $('individual').show();
      $('gridGrupoExames').hide();
      $('descricaoGrupo').hide();
    }

    if ( $('tipo').value == 2 )  {

      $('descricaoGrupo').show();
      $('gridGrupoExames').show();
      $('ctnLancador').show();
      $('individual').hide();
    }
}

/**
 * Validação dos dados submetidos do formulário
 *
 * @return boolean
 */
CotaMensalExame.prototype.validarFormulario = function(){

  if ( $('tipo').value == 0 ) {

    alert('Campo Tipo de Cota é de preenchimento obrigatório!');
    return false;
  }

  if ($('tipo').value == 1) {

    if ($F('s112_i_prestadorvinc') == '') {

      alert('Campo Exame é de preenchimento obrigatório!');
      return false;
    }
  }

  if ($('tipo').value == 3) {

    if ($F('sd63_i_codigo') == '') {

      alert('Campo Exame é de preenchimento obrigatório!');
      return false;
    }
  }

  if ($('tipo').value == 2 || $('tipo').value == 4) {

    if(empty($F('age02_nome'))) {

      alert('Campo Grupo é de preenchimento obrigatório!');
      return false;
    }
  }

  if (empty($F('quantidade'))) {

    alert('Campo Quantidade é de preenchimento obrigatório!');
    $('quantidade').focus();
    return false;
  }

  if (empty($F('mes_inicial')) || empty($F('ano_inicial'))) {

    alert('Campo Competência Inicial é de preenchimento obrigatório!');
    return false;
  }

  if (empty($F('mes_final')) || empty($F('ano_final'))) {

    alert('Campo Competência Inicial é de preenchimento obrigatório!');
    return false;
  }

  var lInicialMaior = false;
  if ($F('ano_final') < $F('ano_inicial')) {
    lInicialMaior = true;
  }

  if ( $F('ano_final') == $F('ano_inicial') && $F('mes_final') < $F('mes_inicial') ) {
    lInicialMaior = true;
  }

  if (lInicialMaior) {

    alert('A Competência Final não pode ser menor que a Competência Inicial!');
    return false;
  }

  if ( $F('mes_inicial') > 12) {

    alert("O valor referente ao mês no campo Competência Inicial não pode ser maior que 12(dezembro)!");
    return false;
  }

  if ( $F('mes_final') > 12) {

    alert("O valor referente ao mês no campo Competência Final não pode ser maior que 12(dezembro)!");
    return false;
  }

  if ($F('ano_inicial').length < 4) {

      alert("O ano da Competência Inicial não pode ser menor que 4 digitos.");
      $('ano_inicial').focus();
      return false;
  }

  if ($F('ano_final').length < 4) {

      alert("O ano da Competência Final não pode ser menor que 4 digitos.");
      $('ano_final').focus();
      return false;
  }

  return true;
}

/**
 * Limpa os campos e retornar o formulário ao estado inicial
 */
CotaMensalExame.prototype.limpar = function(){

  $('form1').reset();
  $('s112_i_prestadorvinc').value = '';
  $('sd63_i_codigo').value = '';
  oLancador.clearAll();
  $('tipo').value = '0';
  $('tipo').dispatchEvent( new Event('change') );
}

/**
 * Remover item da grid
 */
CotaMensalExame.prototype.remover = function(oCota) {

  if (!confirm('Confirma a Exclusão da Cota da Competência '+oCota.age01_mes+'/'+oCota.age01_ano+'?')) {
    return;
  }

  var aCompetencia = oCota.competencia.split('_');

  var oDados = {
    sExecucao : 'excluir',
    iGrupo    : aCompetencia[2]
  }

  var oRequest = new AjaxRequest(this.rpc, oDados, function (oResponse, lErro) {

    if (lErro) {
      alert(oResponse.sMensagem);
      return false;
    }

    this.carregarGrid();
  }.bind(this));

  oRequest.setMessage('Aguarde, removendo cota selecionada...');
  oRequest.execute();
}

/**
 * Montamos a grid das cotas
 */
CotaMensalExame.prototype.montarGrid = function(){

  var oCollection = new Collection();

  oCollection.setId("competencia");
  this.collection = oCollection;

  var oGridCotas = new DatagridCollection(oCollection);

  //layout da Datagrid
  oGridCotas.configure({"height":"350", "width":"600", "update":false, "delete":true});
  oGridCotas.addColumn("sd63_c_nome", { label : "Exame/Grupo", align : "left", width : "300px" });
  oGridCotas.addColumn("quantidade", { label : "Quantidade", align : "center", width : "100px" });
  oGridCotas.addColumn("age01_mes", { label : "Mês", align : "center", width : "50px" });
  oGridCotas.addColumn("age01_ano", { label : "Ano", align : "center", width : "50px" });
  oGridCotas.setEvent("clickDelete", function(event, element, collection) {
              this.remover(element);
           }.bind(this)
         );

  oGridCotas.show($("gridMensal"));

  this.grid = oGridCotas;
}

/**
 * Carregamos as cotas existentes na grid
 */
CotaMensalExame.prototype.carregarGrid = function(){

  var oParametros = {
    iPrestador    : $F('s111_i_prestador'),
    sExecucao     : 'buscarCotas'
  }

  var oRequest = new AjaxRequest(this.rpc, oParametros);

  oRequest.setCallBack(function(oRetorno, lErro){

    if (lErro) {
      alert(oRetorno.sMensagem);
      return false;
    }

    this.carregarGridAjaxCallback(oRetorno);
    this.collection.clear();

    oRetorno.aCotas.forEach(function(oCota, iIndice){

      var oDados = {
        competencia      : oCota.mes.toString() + "_" + oCota.ano.toString() + "_" + oCota.grupo,
        quantidade       : parseInt(oCota.quantidade),
        age01_mes        : parseInt(oCota.mes),
        age01_ano        : parseInt(oCota.ano),
        sd63_c_nome      : oCota.nomegrupo
      }

      this.collection.add(oDados);
    }.bind(this));

    this.grid.reload();
  }.bind(this));

  oRequest.setMessage("Buscando Cotas.");
  oRequest.execute();
}

/**
 * Função para executar algum procedimento no retorno da consulta dos dados da grid
 *
 * @param object   Objeto com o retorno do ajax
 */
CotaMensalExame.prototype.carregarGridAjaxCallback = function(oRetorno){}

/**
 * Validamos os dados que estão na grid com os que estão sendo incluidos, para que não exista duplicidade
 *
 * @param  array     aCompetencia  Dados subetidos do formulário divido por competência
 */
CotaMensalExame.prototype.validarGrid = function(aCompetencia){}

/**
 * Incluimos as cotas de acordo com as informações submetidas no formulário
 *
 * @param  array     aCompetencia  Dados subetidos do formulário divido por competência
 */
CotaMensalExame.prototype.incluirCota = function(aCompetencia){}

/**
 * Comportamento inicial espeífico para cada tipo de Cota Mensal
 */
CotaMensalExame.prototype.comportamentoCadastroCallback = function(){}
