 require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
 require_once("scripts/widgets/dbtextField.widget.js");
 require_once("scripts/datagrid.widget.js");
 require_once("scripts/widgets/dbcomboBox.widget.js");
 require_once("scripts/widgets/DBAncora.widget.js");
 require_once("scripts/widgets/DBLancador.widget.js");

/**
 * Cria uma nova instância de DBViewFormularioFolha.
 * @param integer numero da instituição
 * @constructor
 */
 DBViewFormularioFolha.DBViewTipoFiltrosFolha = function ( iInstituicao ) {

  this.iInstituicao           = iInstituicao;
  this.oElementosHTML         = {};
  this.oInstanciaLancador     = {};
  this.oDivLancadorAtivo      = null;
  this.oLancadorAtivo         = null;
  this.oInstanciaLancador     = new Object();
  this.sLegend                = 'Filtros Adicionais';
  this.sInstancia             = '';
  this.lExibeFieldset         = true;
  this.sFuncaoPesquisa        = null;
  this.oInputIntervaloInicial = null;
  this.oInputIntervaloFinal   = null;
  this.aTipos                 = null;
};

/**
 * Função responsavel por realizar o comportamento quando
 * é executado o onChange do compo Tipo de relatório
 * os tipos possiveis sao: 0(Geral), 1(Orgão), 2(Lotação), 3(Matricula), 4(Locais de Trabalho)
 * @param integer iTipoRelatorio  Tipo do Relatorio Selecionado possui os valores 0, 1, 2, 3 ,4.
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.trocaTipoRelatorio = function (iTipoRelatorio) {

  /**
   * Limpa intervalo caso seja alterado o filtro
   */
  if (this.getLancadorAtivo()) {
    this.getLancadorAtivo().clearAll();
  }

  /**
   * Se o Tipo de relatório for Geral(0) ele oculta o campo de
   * intervalo e Tipo de filtro, senão ele exibe o tipo de filtro.
   */
  if (iTipoRelatorio == 0) {

    this.oElementosHTML.oLinhaIntervalos.style.display = 'none';
    this.oElementosHTML.oLinhaTipoFiltro.style.display = 'none';
    this.oElementosHTML.oLinhaLancadores.style.display = 'none';

  } else {

    this.oElementosHTML.oLinhaIntervalos.style.display = 'none';
    this.oElementosHTML.oLinhaLancadores.style.display = 'none';
    this.oElementosHTML.oLinhaTipoFiltro.style.display = '';
  }

  /**
   * Trata os dados recebidos pela função para realizar a
   * chamada da função getInstanciaLancador();
   */
  switch (iTipoRelatorio) {
    case '0':
      break;
    case '1':

      sNomeLancador        = 'Orgao';
      this.sFuncaoPesquisa = 'func_orcorgao.php';
      this.sCampoRetorno   = 'o40_orgao';
      break;
    case '2':

      sNomeLancador        = 'Lotacao';
      this.sFuncaoPesquisa = 'func_rhlota.php';
      this.sCampoRetorno   = 'r70_codigo';
      break;
    case '3':

      sNomeLancador        = 'Matricula';
      this.sFuncaoPesquisa = 'func_rhpessoal.php';
      this.sCampoRetorno   = 'rh01_regist';
      break;
    case '4':

      sNomeLancador        = 'LocaisTrabalho';
      this.sFuncaoPesquisa = 'func_rhlocaltrab.php';
      this.sCampoRetorno   = 'rh55_codigo';
      break;
    case '5':

      sNomeLancador        = 'Cargo';
      this.sFuncaoPesquisa = 'func_rhfuncao.php';
      this.sCampoRetorno   = 'rh37_funcao';
      break;
    case '6':

      sNomeLancador        = 'Recurso';
      this.sFuncaoPesquisa = 'func_orctiporec.php';
      this.sCampoRetorno   = 'o15_codigo';
      break;
    default:
      throw 'Código do tipo de relatório '+iTipoRelatorio+ ' não existe';
  }

};

/**
 * Retorna uma instância de DBLancador a partir do Nome solicitado
 * Os Parametros possíveis são: Orgao, Lotacao, Matricula, LocaisTrabalho
 * @param string sNomeLancador Nome do Lançador que se deseja criar a Instância
 * @return DBLancador
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.getInstanciaLancador = function (sNomeLancador) {

  /**
   * Verifica se já existe uma instância do lançador solicitado, se não existir intância o mesmo.
   */
  var oLancador = {};
  if (!(this.oInstanciaLancador[sNomeLancador])) {

    switch (sNomeLancador) {

      case 'Orgao':

        oLancadorOrgao = new DBLancador('LancadorOrgao');
        oLancadorOrgao.setNomeInstancia('oLancadorOrgao');
        oLancadorOrgao.setGridHeight(150);
        oLancadorOrgao.setLabelAncora('Órgão:');
        oLancadorOrgao.setParametrosPesquisa('func_orcorgao.php',
                                             ['o40_orgao' , 'o40_descr'],
                                             'instit=' + this.iInstituicao);
        oLancadorOrgao.show(this.oElementosHTML.oDivOrgao);
        oLancador = oLancadorOrgao;
        break;
      case 'Lotacao':

        oLancadorLotacao = new DBLancador('LancadorLotacao');
        oLancadorLotacao.setNomeInstancia('oLancadorLotacao');
        oLancadorLotacao.setGridHeight(150);
        oLancadorLotacao.setTituloJanela('Pesquisa Lotação');
        oLancadorLotacao.setLabelAncora('Lotação:');
        oLancadorLotacao.setParametrosPesquisa(
                                               'func_rhlota.php',
                                               ['r70_codigo' , 'r70_descr'],
                                               'instit=' + this.iInstituicao
                                              );
        oLancadorLotacao.show(this.oElementosHTML.oDivLotacao);
        oLancador = oLancadorLotacao;
        break;
      case 'Matricula':

        oLancadorMatricula = new DBLancador('LancadorMatricula');
        oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
        oLancadorMatricula.setGridHeight(150);
        oLancadorMatricula.setTituloJanela('Pesquisa Matrícula');
        oLancadorMatricula.setLabelAncora('Matrícula:');
        oLancadorMatricula.setParametrosPesquisa(
                                                 'func_rhpessoal.php',
                                                 ['rh01_regist' , 'z01_nome'],
                                                 'instit=' + this.iInstituicao
                                                );
        oLancadorMatricula.show(this.oElementosHTML.oDivMatricula);
        oLancador = oLancadorMatricula;
        break;
      case 'LocaisTrabalho':

        oLancadorLocaisTrabalho = new DBLancador('LancadorLocaisTrabalho');
        oLancadorLocaisTrabalho.setNomeInstancia('oLancadorLocaisTrabalho');
        oLancadorLocaisTrabalho.setGridHeight(150);
        oLancadorLocaisTrabalho.setLabelAncora('Locais de trabalho:');
        oLancadorLocaisTrabalho.setParametrosPesquisa('func_rhlocaltrab.php',
                                                      ['rh55_codigo' , 'rh55_descr'],
                                                      'instit=' + this.iInstituicao);
        oLancadorLocaisTrabalho.show(this.oElementosHTML.oDivLocaisTrabalho);
        oLancador = oLancadorLocaisTrabalho;
        break;
      case 'Cargo':

        oLancadorCargo = new DBLancador('LancadorCargo');
        oLancadorCargo.setNomeInstancia('oLancadorCargo');
        oLancadorCargo.setGridHeight(150);
        oLancadorCargo.setTituloJanela('Pesquisa Cargo');
        oLancadorCargo.setLabelAncora('Cargo:');
        oLancadorCargo.setParametrosPesquisa('func_rhfuncao.php',
                                             ['rh37_funcao' , 'rh37_descr'],
                                             'instit=' + this.iInstituicao);
        oLancadorCargo.show(this.oElementosHTML.oDivCargo);
        oLancador = oLancadorCargo;
        break;
      case 'Recurso':
        oLancadorRecurso = new DBLancador('LancadorRecurso');
        oLancadorRecurso.setNomeInstancia('oLancadorRecurso');
        oLancadorRecurso.setGridHeight(150);
        oLancadorRecurso.setLabelAncora('Recurso:');
        oLancadorRecurso.setParametrosPesquisa('func_orctiporec.php',
                                               ['o15_codigo' , 'o15_descr'],
                                               'instit=' + this.iInstituicao);
        oLancadorRecurso.show(this.oElementosHTML.oDivRecurso);
        oLancador = oLancadorRecurso;
        break;
      default:
        throw 'Não existe lançador para o parametro ' + sNomeLancador + '.';
      break;
    }

    this.oInstanciaLancador[sNomeLancador] = oLancador;
  }

  /**
   * Se existir um lançador Ativo, oculta ele.
   */
  if (this.oDivLancadorAtivo) {
    this.oDivLancadorAtivo.style.display = 'none';
  }


  this.oDivLancadorAtivo               = this.oElementosHTML["oDiv"+sNomeLancador];
  this.oDivLancadorAtivo.style.display = '';
  this.oLancadorAtivo = this.oInstanciaLancador[sNomeLancador];

  return this.oInstanciaLancador[sNomeLancador];
};

/**
 * Retorna a instância do lançador ativo.
 * @returns DBLancador
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.getLancadorAtivo = function() {
  return this.oLancadorAtivo;
};

/**
 * Exibe o lançador no elemento informado como parâmetro.
 * @param object oContainerDestino
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.show = function (oContainerDestino) {

  oContainerDestino.appendChild( this.gerarElementosHTML() );
  this.adicionarComponentes();
};

/**
 * Gera os elementos necessários para a estrutura do Filtro.
 * @returns oContainer, elemento principal do HTML
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.gerarElementosHTML = function() {

  var oContainer = document.createElement('div'); // Elemento Principal

  if (this.lExibeFieldset){
    oContainer = document.createElement('fieldset'); // Elemento Principal
  }
  //oContainer.className = 'separator';
  var oLegend          = document.createElement('legend');
  oLegend.innerHTML    = this.sLegend;

  /**
   * Criando Tabela
   */
  this.oElementosHTML.oTable                                     = document.createElement('table'); //Tabela para principal
  this.oElementosHTML.oTable.addClassName("subtable");
  this.oElementosHTML.oTable.style.width                         = '100%';
  this.oElementosHTML.oTable.style.paddingLeft                   = '0px';
  this.oElementosHTML.oLinhaTipoRelatorio                        = document.createElement('tr');
  this.oElementosHTML.oColunaLabelTipoRelatorio                  = document.createElement('td');
  this.oElementosHTML.oColunaLabelTipoRelatorio.style.fontWeight = 'bold';
  this.oElementosHTML.oColunaContainerTipoRelatorio              = document.createElement('td');

  /**
   * TR para o Tipo de Filtro
   */
  this.oElementosHTML.oLinhaTipoFiltro                           = document.createElement('tr');
  this.oElementosHTML.oColunaLabelTipoFiltro                     = document.createElement('td');
  this.oElementosHTML.oColunaLabelTipoFiltro.style.fontWeight    = 'bold';
  this.oElementosHTML.oColunaContainerTipoFiltro                 = document.createElement('td');
  this.oElementosHTML.oLinhaTipoFiltro.style.display             = 'none';

  /**
   * TR para os Intevalos
   */
  this.oElementosHTML.oLinhaIntervalos                            = document.createElement('tr');
  this.oElementosHTML.oColunaLabelIntervalos                      = document.createElement('td');
  this.oElementosHTML.oColunaLabelIntervalos.style.fontWeight     = 'bold';
  this.oElementosHTML.oColunaContainerIntervalos                  = document.createElement('td');
  this.oElementosHTML.oColunaContainerIntervalos.style.fontWeight = 'bold';
  this.oElementosHTML.oLinhaIntervalos.style.display              = 'none';

  /**
   * TR para os Lançadores
   */
  this.oElementosHTML.oLinhaLancadores                   = document.createElement('tr');
  this.oElementosHTML.oColunaLancadores                  = document.createElement('td');
  this.oElementosHTML.oColunaLancadores.colSpan          = 2;
  this.oElementosHTML.oColunaLancadores.style.whiteSpace = "nowrap";

  /**
   * Cria Label's para Tipo de Relatório
   */
  this.oElementosHTML.oLabelTipoFiltro                 = document.createTextNode(" Tipo de Filtro: ");
  this.oElementosHTML.oLabelTipoRelatorio              = document.createTextNode(" Tipo de Resumo: ");


  this.oElementosHTML.oLabelIntervalos                 = document.createElement('span');
  this.oElementosHTML.oLabelEntreIntervalos            = document.createElement('span');
  this.oElementosHTML.oLabelEntreIntervalos.style.padding = '0 5 0 5';


  /**
   * Cria Span's para o intervalo
   */
  this.oElementosHTML.oSpanIntervaloInicial            = document.createElement('span');
  this.oElementosHTML.oSpanIntervaloFinal              = document.createElement('span');
  /**
   *Cria Div's para os lançadores
   */
  this.oElementosHTML.oDivLotacao                      = document.createElement("div");
  this.oElementosHTML.oDivMatricula                    = document.createElement("div");
  this.oElementosHTML.oDivLocaisTrabalho               = document.createElement("div");
  this.oElementosHTML.oDivOrgao                        = document.createElement("div");
  this.oElementosHTML.oDivCargo                        = document.createElement("div");
  this.oElementosHTML.oDivRecurso                      = document.createElement("div");

  /**
   * Criando a hierarquia dos elementos
   */
   if (this.lExibeFieldset){
     oContainer.appendChild(oLegend);
   }
   oContainer.appendChild(this.oElementosHTML.oTable);

   /**
    * Adiciona todos os elementos necessários para a linha com os Tipos de Relatório.
    */
   this.oElementosHTML.oTable.appendChild(this.oElementosHTML.oLinhaTipoRelatorio);
   this.oElementosHTML.oLinhaTipoRelatorio.appendChild(this.oElementosHTML.oColunaLabelTipoRelatorio);
   this.oElementosHTML.oColunaLabelTipoRelatorio.appendChild(this.oElementosHTML.oLabelTipoRelatorio);
   this.oElementosHTML.oLinhaTipoRelatorio.appendChild(this.oElementosHTML.oColunaContainerTipoRelatorio);

   /**
    * Adiciona todos os elementos necessários para a linha com os Tipos de Filtro
    */
   this.oElementosHTML.oTable.appendChild(this.oElementosHTML.oLinhaTipoFiltro);
   this.oElementosHTML.oLinhaTipoFiltro.appendChild(this.oElementosHTML.oColunaLabelTipoFiltro);
   this.oElementosHTML.oColunaLabelTipoFiltro.appendChild(this.oElementosHTML.oLabelTipoFiltro);
   this.oElementosHTML.oLinhaTipoFiltro.appendChild(this.oElementosHTML.oColunaContainerTipoFiltro);

   /**
    * Adiciona todos os elementos necessários para a linha com o Intevalo Iniciarl e Intervalo Final.
    */
   this.oElementosHTML.oTable.appendChild(this.oElementosHTML.oLinhaIntervalos);
   this.oElementosHTML.oLinhaIntervalos.appendChild(this.oElementosHTML.oColunaLabelIntervalos);
   this.oElementosHTML.oColunaLabelIntervalos.appendChild(this.oElementosHTML.oLabelIntervalos);
   this.oElementosHTML.oLinhaIntervalos.appendChild(this.oElementosHTML.oColunaContainerIntervalos);
   this.oElementosHTML.oColunaContainerIntervalos.appendChild(this.oElementosHTML.oSpanIntervaloInicial);
   this.oElementosHTML.oColunaContainerIntervalos.appendChild(this.oElementosHTML.oLabelEntreIntervalos);
   this.oElementosHTML.oColunaContainerIntervalos.appendChild(this.oElementosHTML.oSpanIntervaloFinal);

   /**
    * Adiciona todos os elementos necessários para a linha contendo os Lançadores.
    */
   this.oElementosHTML.oTable.appendChild(this.oElementosHTML.oLinhaLancadores);
   this.oElementosHTML.oLinhaLancadores.appendChild(this.oElementosHTML.oColunaLancadores);
   this.oElementosHTML.oColunaLancadores.appendChild(this.oElementosHTML.oDivLotacao);
   this.oElementosHTML.oColunaLancadores.appendChild(this.oElementosHTML.oDivOrgao);
   this.oElementosHTML.oColunaLancadores.appendChild(this.oElementosHTML.oDivMatricula);
   this.oElementosHTML.oColunaLancadores.appendChild(this.oElementosHTML.oDivLocaisTrabalho);
   this.oElementosHTML.oColunaLancadores.appendChild(this.oElementosHTML.oDivCargo);
   this.oElementosHTML.oColunaLancadores.appendChild(this.oElementosHTML.oDivRecurso);
   this.oElementosHTML.fieldSetPrincipal = oContainer;

   return oContainer;
};

/**
 * Função responsável por adicionar os componentes aos elementos HTML.
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.adicionarComponentes = function() {

  var oSelf = this;

  /**
   * Monta o Combo com os tipos de relatório.
   */
  this.oComboTipoRelatorio = new ComboTipoRelatorio(this.aTipos);
  this.oComboTipoRelatorio.show(this.oElementosHTML.oColunaContainerTipoRelatorio);
  this.oComboTipoRelatorio.getElement().observe("change", function() {

    oSelf.trocaTipoRelatorio(this.value);
    oSelf.trocaTipoFiltro(oSelf.oComboTipoFiltro.getElement().value);
  });

  /**
   * Monta o Combo com os tipos de filtro.
   */
  this.oComboTipoFiltro = new ComboTipoFiltro();
  this.oComboTipoFiltro.show(this.oElementosHTML.oColunaContainerTipoFiltro);
  this.oComboTipoFiltro.getElement().observe("change", function() {
    oSelf.trocaTipoFiltro(this.value);
  });
};

/**
 * Monta o container com os intervalos
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.getInstanciaIntervalos = function () {

  this.oElementosHTML.oLabelIntervalos.innerHTML      = '';
  this.oElementosHTML.oLabelEntreIntervalos.innerHTML = '';

  /**
   * Monta o Intervalo Inicial e Final
   */
  var oAncoraInicial = new DBAncora('Intervalo: ', '#', true);
  var oAncoraFinal   = new DBAncora('até:'      , '#', true);

  oAncoraInicial.show(this.oElementosHTML.oLabelIntervalos);
  oAncoraFinal.show(this.oElementosHTML.oLabelEntreIntervalos);

  this.oInputIntervaloInicial = new DBTextField('InputIntervaloInicial','InputIntervaloInicial', '', 10);
  this.oInputIntervaloInicial.addEvent("onKeyPress", "return js_mask(event, \"0-9\")");
  this.oInputIntervaloInicial.addEvent("onBlur", "if (!isNumeric(this.value)) { this.value = ''; };");
  this.oInputIntervaloFinal   = new DBTextField('InputIntervaloFinal',  'InputIntervaloFinal',   '', 10);

  oAncoraInicial.onClick(this.funcao_ancora.bind(this, this.oInputIntervaloInicial));
  oAncoraFinal.onClick(this.funcao_ancora.bind(this,   this.oInputIntervaloFinal));

  this.oInputIntervaloFinal.addEvent("onKeyPress", "return js_mask(event, \"0-9\")");
  this.oInputIntervaloFinal.addEvent("onBlur", "if (!isNumeric(this.value)) { this.value = ''; };");
  this.oInputIntervaloInicial.show(this.oElementosHTML.oSpanIntervaloInicial);
  this.oInputIntervaloFinal.show(this.oElementosHTML.oSpanIntervaloFinal);
}

/**
 * Função utilizada para montar a pesquisa na lookup de pesquisa
 * @param  object oParametro
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.funcao_ancora = function(oParametro){

  this.oElementoAtual = oParametro;
  var sQuery          = this.sFuncaoPesquisa;
  var sIframe         = 'db_iframe_' + this.sFuncaoPesquisa.replace('.php', '').replace('func_', '');;

  sQuery += '?funcao_js=parent.'+this.sInstancia+'.retornoPesquisaLookUp|';
  sQuery += this.sCampoRetorno;
  sQuery += '&' + 'instit=' + this.iInstituicao;

  // Define o título da modal
  switch(sNomeLancador) {
    case 'Lotacao':
      var sTituloJanela = 'Pesquisa Lotação';
      break;
    case 'Matricula':
      var sTituloJanela = 'Pesquisa Matrícula';
      break;
    case 'Cargo':
      var sTituloJanela = 'Pesquisa Cargo';
      break;
    default:
      var sTituloJanela = 'Pesquisa';
      break;
  }

  js_OpenJanelaIframe('', sIframe, sQuery, sTituloJanela, true);
}

/**
 * Trata o retorno da pesquisa.
 * @param  string sCodigo
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.retornoPesquisaLookUp = function(sCodigo) {

  this.oElementoAtual.setValue(sCodigo);
  var sIframe = 'db_iframe_' + this.sFuncaoPesquisa.replace('.php', '').replace('func_', '');;
  eval(sIframe + '.hide();');
  delete this.oElementoAtual;
};

/**
 * Retorna o Tipo de filtro selecionado.
 * Os retornos possíveis são: 0(Geral), 1(Intervalo), 2(Selecionado)
 * @returns tipo do filtro selecionado
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.getTipoFiltro = function() {
  return this.oComboTipoFiltro.getValue();
};

/**
 * Retorna o Tipo de relatório selecionado
 * Os retornos possíveis são: 0(Geral) 1(Orgão), 2(Lotação), 3(Matricula), 4(Locais de Trabalho)
 * @returns tipo do relatório selecionado
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.getTipoRelatorio = function() {
  return this.oComboTipoRelatorio.getValue();
};

/**
 * Função responsável por executar a exibição correta do formulário.
 * de acordo com o tipo de filtro selecionado.
 *  0 (Geral) Oculta a Linha do Intervalo e das seleções.
 *  1 (Intervalo) Exibe a linha Intervalo e oculda a linha da seleção.
 *  2 (Selecionados) Exibe a linha dos lançadores e oculta a linha dos Intervalos.
 * @param Tipo do Filtro selecionado 0, 1 ,2
 * @access private
 */
DBViewFormularioFolha.DBViewTipoFiltrosFolha.prototype.trocaTipoFiltro = function (iTipoFiltro) {

  switch (iTipoFiltro) {

    case '0':

      this.oElementosHTML.oLinhaIntervalos.style.display = 'none';
      this.oElementosHTML.oLinhaLancadores.style.display = 'none';

    break;

    case '1':

      this.oElementosHTML.oLinhaLancadores.style.display = 'none';
      if ( this.getTipoRelatorio() != 0 ) {
        this.oElementosHTML.oLinhaIntervalos.style.display = '';
      }
      this.getInstanciaIntervalos();

    break;

    case '2' :

      this.oElementosHTML.oLinhaIntervalos.style.display = 'none';

      if ( this.getTipoRelatorio() != 0 ) {

        this.oElementosHTML.oLinhaLancadores.style.display = '';
        this.getInstanciaLancador(sNomeLancador);
      }

    break;

  }
};

/**
 * Monta um DBComboBox exibindo somente os Tipos informados por parâmetro.
 * Os Tipos possíveis são: 0(Geral), 1(Orgão), 2(Lotação), 3(Matricula), 4(Locais de Trabalho)
 * @param Array aTipos Tipos de relatório
 * @return DBComboBox
 * @example new ComboTipoRelatorio([0,1,2,3,4])
 * @access private
 */
var ComboTipoRelatorio = function (aTipos) {

  if (aTipos == null ) {
    aTipos = [0,1,2,3,4,5,6];
  }

  var oComboTipoRelatorio = new DBComboBox('oCboTipoRelatorio', 'oCboTipoRelatorio', new Array());

  aDescricaoTipos    = new Array();
  aDescricaoTipos[0] = 'Geral';
  aDescricaoTipos[1] = 'Órgão';
  aDescricaoTipos[2] = 'Lotação';
  aDescricaoTipos[3] = 'Matrícula';
  aDescricaoTipos[4] = 'Locais de Trabalho';
  aDescricaoTipos[5] = 'Cargo';
  aDescricaoTipos[6] = 'Recurso';

  for (var iItemTipoRelatorio = 0; iItemTipoRelatorio < aTipos.length; iItemTipoRelatorio++) {
    oComboTipoRelatorio.addItem(aTipos[iItemTipoRelatorio], aDescricaoTipos[aTipos[iItemTipoRelatorio]]);
  }

  return oComboTipoRelatorio;
};

/**
 * Monta um DBComboBox com os Tipos de Filtro.
 * @return DBComboBox
 * @access private
 */
var ComboTipoFiltro = function () {

  var oComboTipoFiltro = new DBComboBox('oCboTipoFiltro');
  oComboTipoFiltro.addItem(0, 'Geral');
  oComboTipoFiltro.addItem(1, 'Intervalo');
  oComboTipoFiltro.addItem(2, 'Selecionados');
  return oComboTipoFiltro;
};
