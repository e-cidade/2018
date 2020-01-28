require_once( 'scripts/widgets/DBLookUp.widget.js' );
require_once( 'scripts/widgets/dbautocomplete.widget.js' );
require_once( 'scripts/classes/saude/validaCNS.js');
require_once( 'scripts/widgets/windowAux.widget.js');
require_once( 'scripts/widgets/dbmessageBoard.widget.js');
require_once( 'scripts/classes/saude/ambulatorial/DBViewMotivosAlta.classe.js' );
require_once( 'scripts/classes/saude/ambulatorial/DBViewEncaminhamento.classe.js' );
require_once( 'scripts/classes/saude/ambulatorial/DBViewOpcoesSalvar.classe.js' );
require_once( 'scripts/classes/saude/ambulatorial/DBViewAdministracaoMedicamento.classe.js' );
require_once( 'scripts/datagrid.widget.js');
require_once( 'scripts/widgets/DBInputHora.widget.js' );
require_once( 'scripts/widgets/DBAncora.widget.js' );
require_once( 'scripts/widgets/datagrid/plugins/DBHint.plugin.js' );
require_once( 'scripts/widgets/Input/DBInput.widget.js' );
require_once( 'scripts/widgets/Input/DBInputDate.widget.js' );

/**
 * Constante das mensagens
 * @type {string}
 */
const MENSAGENS_DBVIEWTRIAGEM = 'saude.ambulatorial.DBViewTriagem.';

/**
 * Classe para geração da estrutura referente as telas de triagem
 * @constructor
 */
DBViewTriagem = function( iTelaOrigem ) {

  var oSelf = this;

  /**
   * RPC's utilizados
   */
  this.sRpcTriagem          = 'sau4_triagem.RPC.php';
  this.sRpcAgravo           = 'sau4_triagemagravo.RPC.php';
  this.sRpcAmbulatorial     = 'sau4_ambulatorial.RPC.php';
  this.sRpcFichaAtendimento = 'sau4_fichaatendimento.RPC.php';

  /**
   * Código da triagem, caso seja uma alteração
   * @type {int}
   */
  this.iTriagem = null;

  /**
   * Código do agravo, caso exista para o CGS selecionado
   * @type {int}
   */
  this.iAgravo = '';

  /**
   * Código do CID referente ao agravo selecionado
   * @type {int}
   */
  this.iCid = '';

  /**
   * Código CBOS do profissional selecionado
   * @type {int}
   */
  this.iCboProfissional = '';

  /**
   * Sexo do CGS selecionado
   * @type {string}
   */
  this.sSexo = 'M';

  /**
   * Código da tabela unidademedicos. É preenchido ao acessar a rotina e o usuário logado for um profissional da saúde,
   * ou após selecionar um profissional da lookup
   * @type {int}
   */
  this.iUnidadeMedicos = null;

  /**
   * Controla se o profissional logado ou selecionado é um profissional da saúde
   * @type {boolean}
   */
  this.lProfissionalSaude = false;

  /**
   * Guarda a data atual
   * @type {string}
   */
  this.dtAtual = '';

  /**
   * Controla se as lookups foram instanciadas, evitando criá-las novamente
   * @type {boolean}
   */
  this.lInstanciouLookUp = false;

/**
   * Recebe o valor do prontuário (FAA)
   * @type {integer}
   */
  this.iProntuario = null;

  /**
   * Controla qual tela devemos apresentar
   * @type {Boolean}
   */
  this.lTemProntuario = false;

  /**
   * Guarda os procedimentos configurados para triagem
   * @type {Array}
   */
  this.aProcedimentosTriagem = new Array();

  /**
   * Código do CGS
   * @type {integer}
   */
  this.iCgs = null;

  /**
   * Controla qual tela originou o formulário
   * @type {integer}
   */
  this.iTelaOrigem = iTelaOrigem;

  /**
   * Código do agendamento, caso exista
   * @type {integer}
   */
  this.iAgendamento = null;

  /**
   * Controla se a origem da busca é de um agendamento
   * @type {Boolean}
   */
  this.lOrigemAgenda = false;

  /**
   * Chamada para o método que constroi a estrutura HTML pardrão
   */
  this.montaEstruturaHTML( oSelf );

  /**
   * Chamada para o método que contém os eventos da tela
   */
  this.eventosElementos( oSelf );

  /**
   * Controla se o deve salvar um novo vinculo a triagem
   * @type {Boolean}
   */
  this.lIncluirVinculoTriagemProntuario = true;

  /**
   * Código do médico logado
   * @type {integer}
   */
  this.iMedico = null;
};

/**
 * Constante contendo qual tela esta sendo gerada
 * TELA_TRIAGEM_AVULSA            - Procedimentos > Triagem Avulsa
 * TELA_TRIAGEM_FICHA_ATENDIMENTO - Procedimentos > Ficha de Atendimento > Lançamento e Manutenção
 * TELA_TRIAGEM                   - Procedimentos > Triagem
 * TELA_TRIAGEM_CONSULTA          - Procedimentos > Consulta Médica > Botão Triagem
 */
DBViewTriagem.prototype.TELA_TRIAGEM_AVULSA            = 1;
DBViewTriagem.prototype.TELA_TRIAGEM_FICHA_ATENDIMENTO = 2;
DBViewTriagem.prototype.TELA_TRIAGEM                   = 3;
DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA          = 4;

/**
 * Constantes para cada tipo de botão
 */
DBViewTriagem.prototype.BOTAO_FATORES_RISCO            = 1;
DBViewTriagem.prototype.BOTAO_LIMPAR                   = 2;
DBViewTriagem.prototype.BOTAO_CONSULTAR                = 3;
DBViewTriagem.prototype.BOTAO_SALVAR                   = 4;
DBViewTriagem.prototype.BOTAO_FECHAR                   = 5;
DBViewTriagem.prototype.BOTAO_FINALIZAR_ATENDIMENTO    = 6;
DBViewTriagem.prototype.BOTAO_ENCAMINHAR               = 7;
DBViewTriagem.prototype.BOTAO_ADMINISTRAR_MEDICAMENTOS = 8;

/**
 * Monta o HTML
 * @param oSelf
 */
DBViewTriagem.prototype.montaEstruturaHTML = function( oSelf ) {

  /**
   * Arrays com os botões que devem ser disponibilizados na tela de acordo com o menu de origem
   */
  var aBotoesTriagemAvulsa = [
                               DBViewTriagem.prototype.BOTAO_SALVAR,
                               DBViewTriagem.prototype.BOTAO_FATORES_RISCO,
                               DBViewTriagem.prototype.BOTAO_LIMPAR
                             ];
  var aBotoesTriagemFaa      = [
                                 DBViewTriagem.prototype.BOTAO_SALVAR,
                                 DBViewTriagem.prototype.BOTAO_FINALIZAR_ATENDIMENTO,
                                 DBViewTriagem.prototype.BOTAO_ADMINISTRAR_MEDICAMENTOS
                               ];
  var aBotoesTriagem         = [
                                 DBViewTriagem.prototype.BOTAO_CONSULTAR,
                                 DBViewTriagem.prototype.BOTAO_SALVAR,
                                 DBViewTriagem.prototype.BOTAO_ENCAMINHAR,
                                 DBViewTriagem.prototype.BOTAO_FINALIZAR_ATENDIMENTO,
                                 DBViewTriagem.prototype.BOTAO_ADMINISTRAR_MEDICAMENTOS
                               ];
  var aBotoesTriagemConsulta = [ DBViewTriagem.prototype.BOTAO_FECHAR ];
  var aBotoesCriar           = [];

  /**
   * Elemento do formulário HTML
   * @type {form}
   */
  oSelf.oFormulario           = document.createElement( 'form' );
  oSelf.oFormulario.className = 'form-container';

  /**
   * Elementos Fieldset e da legenda do mesmo
   * @type {fieldset}
   */
  oSelf.oFieldsetTriagem          = document.createElement( 'fieldset' );
  oSelf.oLegendaTriagem           = document.createElement( 'legend' );
  oSelf.oLegendaTriagem.innerHTML = 'Triagem';

  /**
   * Valida a Legenda que deve ser exibida conforme a tela que será exibida
   */
  switch (this.iTelaOrigem) {

    case DBViewTriagem.prototype.TELA_TRIAGEM_AVULSA:

      oSelf.oLegendaTriagem.innerHTML = 'Triagem Avulsa';
      aBotoesCriar                    = aBotoesTriagemAvulsa;
      break;

    case DBViewTriagem.prototype.TELA_TRIAGEM_FICHA_ATENDIMENTO:

      aBotoesCriar = aBotoesTriagemFaa;
      break;

    case DBViewTriagem.prototype.TELA_TRIAGEM:

      aBotoesCriar = aBotoesTriagem;
      break;

    case DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA:

      aBotoesCriar = aBotoesTriagemConsulta;
      break;
  }

  /**
   * Elemento da tabela principal
   * @type {table}
   */
  oSelf.oTabelaPrincipal = document.createElement( 'table' );

  /**
   * Realiza os vínculos dos elementos
   */
  oSelf.oFormulario.appendChild( oSelf.oFieldsetTriagem );
  oSelf.oFieldsetTriagem.appendChild( oSelf.oLegendaTriagem );
  oSelf.oFieldsetTriagem.appendChild( oSelf.oTabelaPrincipal );


  /* ********************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DO CARTÃO DO SUS
   * ********************************************************/
  /**
   * Elementos referentes a linha e colunas do Cartão do SUS
   */
  oSelf.oLinhaCartaoSUS        = document.createElement( 'tr' );
  oSelf.oColunaCartaoSUSLabel  = document.createElement( 'td' );
  oSelf.oColunaCartaoSUSCodigo = document.createElement( 'td' );
  oSelf.oColunaCartaoSUSCodigo.setAttribute('colspan', '2');

  /**
   * Label do Cartão do SUS
   * @type {label}
   */
  oSelf.oLabelCartaoSUS           = document.createElement( 'label' );
  oSelf.oLabelCartaoSUS.addClassName( 'bold' );
  oSelf.oLabelCartaoSUS.setAttribute('for', 'oInputCartaoSUS');
  oSelf.oLabelCartaoSUS.innerHTML = 'Cartão SUS: ';

  /**
   * Input do número do cartão do SUS
   * @type {input}
   */
  oSelf.oInputCartaoSUS = document.createElement( 'input' );
  oSelf.oInputCartaoSUS.addClassName( 'field-size3' );
  oSelf.oInputCartaoSUS.setAttribute( 'id', 'oInputCartaoSUS' );
  oSelf.oInputCartaoSUS.setAttribute( 'type', 'text' );
  oSelf.oInputCartaoSUS.setAttribute( 'maxLength', '15' );

  /**
   * Vínculos dos elementos da linha do cartão do SUS
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaCartaoSUS );

  oSelf.oLinhaCartaoSUS.appendChild( oSelf.oColunaCartaoSUSLabel );
  oSelf.oLinhaCartaoSUS.appendChild( oSelf.oColunaCartaoSUSCodigo );

  oSelf.oColunaCartaoSUSLabel.appendChild( oSelf.oLabelCartaoSUS );
  oSelf.oColunaCartaoSUSCodigo.appendChild( oSelf.oInputCartaoSUS );


  /* ******************************************
   * ELEMENTOS REFERENTE AS INFORMAÇÕES DO CGS
   * *****************************************/
  /**
   * Linha e colunas do CGS
   */
  oSelf.oLinhaCGS           = document.createElement( 'tr' );
  oSelf.oColunaCGSLabel     = document.createElement( 'td' );
  oSelf.oColunaCGSCodigo    = document.createElement( 'td' );
  oSelf.oColunaCGSDescricao = document.createElement( 'td' );

  /**
   * Label da ancora do CGS
   * @type {label}
   */
  oSelf.oLabelCGS = document.createElement( 'label' );
  oSelf.oLabelCGS.addClassName( 'bold' );
  oSelf.oLabelCGS.setAttribute('for', 'oInputCGSCodigo');

  /**
   * Ancora para buscar CGS
   * @type {a}
   */
  oSelf.oAncoraCGS           = document.createElement( 'a' );
  oSelf.oAncoraCGS.addClassName( 'bold' );
  oSelf.oAncoraCGS.setAttribute( 'href', '#' );
  oSelf.oAncoraCGS.innerHTML = 'CGS: ';

  /**
   * Input do código CGS
   * @type {input}
   */
  oSelf.oInputCGSCodigo = document.createElement( 'input' );
  oSelf.oInputCGSCodigo.setAttribute( 'id', 'oInputCGSCodigo' );
  oSelf.oInputCGSCodigo.setAttribute( 'type', 'text' );
  oSelf.oInputCGSCodigo.setAttribute( 'lang', 'z01_i_cgsund' );
  oSelf.oInputCGSCodigo.addClassName( 'field-size2' );

  /**
   * Input da descrição do CGS
   * @type {input}
   */
  oSelf.oInputCGSDescricao = document.createElement( 'input' );
  oSelf.oInputCGSDescricao.setAttribute( 'id', 'oInputCGSDescricao' );
  oSelf.oInputCGSDescricao.setAttribute( 'type', 'text' );
  oSelf.oInputCGSDescricao.setAttribute( 'lang', 'z01_v_nome' );
  oSelf.oInputCGSDescricao.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputCGSDescricao.addClassName( 'field-size8' );
  oSelf.oInputCGSDescricao.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Vínculos dos elementos da linha do CGS
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaCGS );

  oSelf.oLinhaCGS.appendChild( oSelf.oColunaCGSLabel );
  oSelf.oLinhaCGS.appendChild( oSelf.oColunaCGSCodigo );
  oSelf.oLinhaCGS.appendChild( oSelf.oColunaCGSDescricao );

  oSelf.oLabelCGS.appendChild(oSelf.oAncoraCGS);

  oSelf.oColunaCGSLabel.appendChild( oSelf.oLabelCGS );
  oSelf.oColunaCGSCodigo.appendChild( oSelf.oInputCGSCodigo );
  oSelf.oColunaCGSDescricao.appendChild( oSelf.oInputCGSDescricao );


  /* *******************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DO PROFISSIONAL
   * *******************************************************/
  /**
   * Linha e colunas referentes aos dados do profissional
   */
  oSelf.oLinhaProfissional           = document.createElement( 'tr' );
  oSelf.oColunaProfissionalLabel     = document.createElement( 'td' );
  oSelf.oColunaProfissionalCodigo    = document.createElement( 'td' );
  oSelf.oColunaProfissionalDescricao = document.createElement( 'td' );

  /**
   * Ancora para buscar o profissional
   * @type {a}
   */
  oSelf.oLabelProfissional           = document.createElement( 'a' );
  oSelf.oLabelProfissional.addClassName( 'bold' );
  // oSelf.oLabelProfissional.setAttribute('for', 'oInputProfissionalCodigo');
  oSelf.oLabelProfissional.innerHTML = 'Profissional: ';

  /**
   * Input com informação do código do profissional
   * @type {input}
   */
  oSelf.oInputProfissionalCodigo = document.createElement( 'input' );
  oSelf.oInputProfissionalCodigo.addClassName( 'field-size2' );
  oSelf.oInputProfissionalCodigo.setAttribute( 'id', 'oInputProfissionalCodigo' );
  oSelf.oInputProfissionalCodigo.setAttribute( 'type', 'text' );
  oSelf.oInputProfissionalCodigo.setAttribute( 'lang', 'sd03_i_codigo' );
  oSelf.oInputProfissionalCodigo.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputProfissionalCodigo.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Input com informação da descrição do profissional
   * @type {input}
   */
  oSelf.oInputProfissionalDescricao = document.createElement( 'input' );
  oSelf.oInputProfissionalDescricao.addClassName( 'field-size8' );
  oSelf.oInputProfissionalDescricao.setAttribute( 'id', 'oInputProfissionalDescricao' );
  oSelf.oInputProfissionalDescricao.setAttribute( 'type', 'text' );
  oSelf.oInputProfissionalDescricao.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputProfissionalDescricao.setAttribute( 'lang', 'z01_nome' );
  oSelf.oInputProfissionalDescricao.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Vínculos dos elementos do profissional
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaProfissional );
  oSelf.oLinhaProfissional.appendChild( oSelf.oColunaProfissionalLabel );
  oSelf.oLinhaProfissional.appendChild( oSelf.oColunaProfissionalCodigo );
  oSelf.oLinhaProfissional.appendChild( oSelf.oColunaProfissionalDescricao );

  oSelf.oColunaProfissionalLabel.appendChild( oSelf.oLabelProfissional );
  oSelf.oColunaProfissionalCodigo.appendChild( oSelf.oInputProfissionalCodigo );
  oSelf.oColunaProfissionalDescricao.appendChild( oSelf.oInputProfissionalDescricao );


  /* **************************************************************************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DE ESPECIALIDADE. LINHA APRESENTADA SOMENTE QUANDO USUÁRIO LOGADO É UM
   * PROFISSIONAL DA SAÚDE
   * **************************************************************************************************************/
  /**
   * Linha e colunas referente a especialidade
   */
  oSelf.oLinhaEspecialidade                   = document.createElement( 'tr' );
  oSelf.oColunaEspecialidadeLabel             = document.createElement( 'td' );
  oSelf.oColunaEspecialidadeDescricao         = document.createElement( 'td' );
  oSelf.oColunaEspecialidadeCodigo            = document.createElement( 'td' );
  oSelf.oColunaEspecialidadeDescricao.colSpan = '2';

  /**
   * Ancora referente a especialidade do profissional
   * @type {a}
   */
  oSelf.oLabelEspecialidade           = document.createElement( 'label' );
  oSelf.oLabelEspecialidade.addClassName( 'bold' );
  oSelf.oLabelEspecialidade.setAttribute( 'for', 'oCboEspecialidade' );
  oSelf.oLabelEspecialidade.innerHTML = 'Especialidade: ';

  /**
   * Elemento para o código da especidalidade
   * @type {input}
   */
  oSelf.oCboEspecialidade = document.createElement( 'select' );
  oSelf.oCboEspecialidade.setAttribute("id", 'oCboEspecialidade');
  oSelf.oCboEspecialidade.style.width = '95%';
  oSelf.oCboEspecialidade.onchange = function() {
    oSelf.liberaAbaProcedimentos();
  };

  /**
   * Vínculos dos elementos da especialidade
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaEspecialidade );

  oSelf.oLinhaEspecialidade.appendChild( oSelf.oColunaEspecialidadeLabel );
  oSelf.oLinhaEspecialidade.appendChild( oSelf.oColunaEspecialidadeDescricao );

  oSelf.oColunaEspecialidadeLabel.appendChild( oSelf.oLabelEspecialidade );
  oSelf.oColunaEspecialidadeDescricao.appendChild( oSelf.oCboEspecialidade );


  /* **************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DA UNIDADE
   * **************************************************/
  /**
   * Linha e colunas referentes aos dados da unidade
   */
  oSelf.oLinhaUnidade           = document.createElement( 'tr' );
  oSelf.oColunaUnidadeLabel     = document.createElement( 'td' );
  oSelf.oColunaUnidadeCodigo    = document.createElement( 'td' );
  oSelf.oColunaUnidadeDescricao = document.createElement( 'td' );

  /**
   * Label da Unidade
   * @type {label}
   */
  oSelf.oLabelUnidade           = document.createElement( 'label' );
  oSelf.oLabelUnidade.addClassName( 'bold' );
  oSelf.oLabelUnidade.setAttribute('for', 'oInputUnidadeCodigo');
  oSelf.oLabelUnidade.innerHTML = 'Unidade: ';

  /**
   * Input com informação do código da unidade
   * @type {input}
   */
  oSelf.oInputUnidadeCodigo = document.createElement( 'input' );
  oSelf.oInputUnidadeCodigo.addClassName( 'field-size2' );
  oSelf.oInputUnidadeCodigo.setAttribute( 'id', 'oInputUnidadeCodigo' );
  oSelf.oInputUnidadeCodigo.setAttribute( 'type', 'text' );
  oSelf.oInputUnidadeCodigo.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputUnidadeCodigo.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Input com informação da descrição da unidade
   * @type {input}
   */
  oSelf.oInputUnidadeDescricao = document.createElement( 'input' );
  oSelf.oInputUnidadeDescricao.addClassName( 'field-size8' );
  oSelf.oInputUnidadeDescricao.setAttribute( 'id', 'oInputUnidadeDescricao' );
  oSelf.oInputUnidadeDescricao.setAttribute( 'type', 'text' );
  oSelf.oInputUnidadeDescricao.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputUnidadeDescricao.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Vínculos dos elementos da unidade
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaUnidade );
  oSelf.oLinhaUnidade.appendChild( oSelf.oColunaUnidadeLabel );
  oSelf.oLinhaUnidade.appendChild( oSelf.oColunaUnidadeCodigo );
  oSelf.oLinhaUnidade.appendChild( oSelf.oColunaUnidadeDescricao );

  oSelf.oColunaUnidadeLabel.appendChild( oSelf.oLabelUnidade );
  oSelf.oColunaUnidadeCodigo.appendChild( oSelf.oInputUnidadeCodigo );
  oSelf.oColunaUnidadeDescricao.appendChild( oSelf.oInputUnidadeDescricao );


  /* ***********************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DO CBOS
   * ***********************************************/
  /**
   * Linha e colunas referentes aos dados do CBOS
   */
  oSelf.oLinhaCBOS        = document.createElement( 'tr' );
  oSelf.oColunaCBOSLabel  = document.createElement( 'td' );
  oSelf.oColunaCBOSSelect = document.createElement( 'td' );
  oSelf.oColunaCBOSSelect.setAttribute( 'colSpan', '2' );
  oSelf.oLinhaCBOS.style.display = "none";

  /**
   * Label do CBOS
   * @type {label}
   */
  oSelf.oLabelCBOS           = document.createElement( 'label' );
  oSelf.oLabelCBOS.addClassName( 'bold' );
  oSelf.oLabelCBOS.setAttribute('for', 'oSelectCBOS');
  oSelf.oLabelCBOS.innerHTML = 'CBOS: ';

  /**
   * Combo com os CBOS existentes
   * @type {select}
   */
  oSelf.oSelectCBOS = document.createElement( 'select' );
  oSelf.oSelectCBOS.addClassName( 'field-size-max' );
  oSelf.oSelectCBOS.setAttribute( 'id', 'oSelectCBOS' );

  /**
   * Vínculos dos elementos do CBOS
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaCBOS );
  oSelf.oLinhaCBOS.appendChild( oSelf.oColunaCBOSLabel );
  oSelf.oLinhaCBOS.appendChild( oSelf.oColunaCBOSSelect );

  oSelf.oColunaCBOSLabel.appendChild( oSelf.oLabelCBOS );
  oSelf.oColunaCBOSSelect.appendChild( oSelf.oSelectCBOS );


  /* ***********************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DA DATA DA CONSULTA
   * ***********************************************************/
  /**
   * Linha e colunas referentes aos dados da data da consulta
   */
  oSelf.oLinhaDataConsulta           = document.createElement( 'tr' );
  oSelf.oColunaDataConsultaLabel     = document.createElement( 'td' );
  oSelf.oColunaDataConsultaValor     = document.createElement( 'td' );
  oSelf.oColunaDataConsultaBotaoData = document.createElement( 'td' );
  oSelf.oColunaDataConsultaCampos    = document.createElement( 'td' );
  oSelf.oColunaDataConsultaCampos.setStyle( { 'display' : 'none' } );

  /**
   * Label da Data da Consulta
   * @type {label}
   */
  oSelf.oLabelDataConsulta           = document.createElement( 'label' );
  oSelf.oLabelDataConsulta.addClassName( 'bold' );
  oSelf.oLabelDataConsulta.setAttribute('for', 'oInputDataConsultaValor');
  oSelf.oLabelDataConsulta.innerHTML = 'Data da Consulta: ';

  /**
   * Input com informação da data da consulta
   * @type {input}
   */
  oSelf.oInputDataConsultaValor = document.createElement( 'input' );
  oSelf.oInputDataConsultaValor.setAttribute( 'id', 'oInputDataConsultaValor' );
  oSelf.oInputDataConsultaValor.addClassName( 'field-size2' );
  oSelf.oInputDataConsultaValor.setAttribute( 'type', 'text' );
  oSelf.oInputDataConsultaValor.setAttribute( 'name', 'oInputDataConsulta' );
  oSelf.oInputDataConsultaValor.setAttribute( 'onkeyup', 'return js_mascaraData(this,event)' );
  oSelf.oInputDataConsultaValor.setAttribute( 'maxLength', '10');

  /**
   * Input com o dia da data da consulta selecionada
   * @type {input}
   */
  oSelf.oInputDataConsultaDia = document.createElement( 'input' );
  oSelf.oInputDataConsultaDia.setAttribute( 'id', 'oInputDataConsulta_dia' );
  oSelf.oInputDataConsultaDia.setAttribute( 'type', 'text' );

  /**
   * Input com o mês da data da consulta selecionada
   * @type {input}
   */
  oSelf.oInputDataConsultaMes = document.createElement( 'input' );
  oSelf.oInputDataConsultaMes.setAttribute( 'id', 'oInputDataConsulta_mes' );
  oSelf.oInputDataConsultaMes.setAttribute( 'type', 'text' );

  /**
   * Input com o ano da data da consulta selecionada
   * @type {input}
   */
  oSelf.oInputDataConsultaAno = document.createElement( 'input' );
  oSelf.oInputDataConsultaAno.setAttribute( 'id', 'oInputDataConsulta_ano' );
  oSelf.oInputDataConsultaAno.setAttribute( 'type', 'text' );

  /**
   * Input com o botão para selecionar uma data
   * @type {input}
   */
  oSelf.oInputDataConsulta = document.createElement( 'input' );
  oSelf.oInputDataConsulta.setAttribute( 'type', 'button' );
  oSelf.oInputDataConsulta.setAttribute( 'id', 'oInputDataConsulta' );
  oSelf.oInputDataConsulta.setAttribute( 'name', 'oInputDataConsulta' );
  oSelf.oInputDataConsulta.setAttribute( 'value', 'D' );
  oSelf.oInputDataConsulta.setAttribute( "onclick", "pegaPosMouse(event); show_calendar('oInputDataConsulta','none')" );

  /**
   * Vínculos dos elementos da data da consulta
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaDataConsulta );

  oSelf.oLinhaDataConsulta.appendChild( oSelf.oColunaDataConsultaLabel );
  oSelf.oLinhaDataConsulta.appendChild( oSelf.oColunaDataConsultaValor );
  oSelf.oLinhaDataConsulta.appendChild( oSelf.oColunaDataConsultaBotaoData );
  oSelf.oLinhaDataConsulta.appendChild( oSelf.oColunaDataConsultaCampos );

  oSelf.oColunaDataConsultaLabel.appendChild( oSelf.oLabelDataConsulta );
  oSelf.oColunaDataConsultaValor.appendChild( oSelf.oInputDataConsultaValor );
  oSelf.oColunaDataConsultaBotaoData.appendChild( oSelf.oInputDataConsulta );

  oSelf.oColunaDataConsultaCampos.appendChild( oSelf.oInputDataConsultaDia );
  oSelf.oColunaDataConsultaCampos.appendChild( oSelf.oInputDataConsultaMes );
  oSelf.oColunaDataConsultaCampos.appendChild( oSelf.oInputDataConsultaAno );

  /* ************************************************
   * ELEMENTOS REFERENTES AS INFORMAÇÕES DAS Antropometria
   * ************************************************/
  /**
   * Linha e coluna do fieldset das Antropometria
   * @type {HTMLElement}
   */
  oSelf.oLinhaTabelaAntropometria  = document.createElement( 'tr' );
  oSelf.oColunaTabelaAntropometria = document.createElement( 'td' );
  oSelf.oColunaTabelaAntropometria.setAttribute( 'colSpan', '3' );

  /**
   * Fieldset das Antropometria
   * @type {fieldset}
   */
  oSelf.oFieldsetAntropometria = document.createElement( 'fieldset' );
  oSelf.oFieldsetAntropometria.addClassName( 'separator' );

  /**
   * Legenda do fieldset das Antropometria
   * @type {legend}
   */
  oSelf.oLegendaAntropometria           = document.createElement( 'legend' );
  oSelf.oLegendaAntropometria.addClassName( 'bold' );
  oSelf.oLegendaAntropometria.innerHTML = 'Antropometria';

  /**
   * Tabela com os dados das Antropometria
   * @type {table}
   */
  oSelf.oTabelaAntropometria = document.createElement( 'table' );

  /**
   * Vínculos da tabela principal com o fieldset das Antropometria
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaTabelaAntropometria );
  oSelf.oLinhaTabelaAntropometria.appendChild( oSelf.oColunaTabelaAntropometria );
  oSelf.oColunaTabelaAntropometria.appendChild( oSelf.oFieldsetAntropometria );

  oSelf.oFieldsetAntropometria.appendChild( oSelf.oLegendaAntropometria );
  oSelf.oFieldsetAntropometria.appendChild( oSelf.oTabelaAntropometria );

  /**
   * Elementos da primeira linha das Antropometria
   */
  oSelf.oLinhaAntropometria               = document.createElement( 'tr' );
  oSelf.oColunaCinturaLabel               = document.createElement( 'td' );
  oSelf.oColunaCinturaDescricao           = document.createElement( 'td' );
  oSelf.oColunaPesoLabel                  = document.createElement( 'td' );
  oSelf.oColunaPesoDescricao              = document.createElement( 'td' );
  oSelf.oColunaAlturaLabel                = document.createElement( 'td' );
  oSelf.oColunaAlturaDescricao            = document.createElement( 'td' );
  oSelf.oColunaPerimetroCefalicoLabel     = document.createElement( 'td' );
  oSelf.oColunaPerimetroCefalicoDescricao = document.createElement( 'td' );

  /**
   * Label da Cintura
   * @type {label}
   */
  oSelf.oLabelCintura           = document.createElement( 'label' );
  oSelf.oLabelCintura.addClassName( 'bold' );
  oSelf.oLabelCintura.setAttribute('for', 'oInputCintura');
  oSelf.oLabelCintura.innerHTML = 'Cintura: ';

  /**
   * Input com informação da cintura
   * @type {input}
   */
  oSelf.oInputCintura = document.createElement( 'input' );
  oSelf.oInputCintura.addClassName( 'field-size1' );
  oSelf.oInputCintura.setAttribute( 'id', 'oInputCintura' );
  oSelf.oInputCintura.setAttribute( 'type', 'text' );
  oSelf.oInputCintura.setAttribute( 'maxLength', '3' );

  /**
   * Label da Peso
   * @type {label}
   */
  oSelf.oLabelPeso           = document.createElement( 'label' );
  oSelf.oLabelPeso.addClassName( 'bold' );
  oSelf.oLabelPeso.setAttribute('for', 'oInputPeso');
  oSelf.oLabelPeso.innerHTML = 'Peso: ';

  /**
   * Input com informação do peso
   * @type {input}
   */
  oSelf.oInputPeso = document.createElement( 'input' );
  oSelf.oInputPeso.addClassName( 'field-size1' );
  oSelf.oInputPeso.setAttribute( 'id', 'oInputPeso' );
  oSelf.oInputPeso.setAttribute( 'type', 'text' );
  oSelf.oInputPeso.setAttribute( 'maxLength', '7');

  /**
   * Label da Altura
   * @type {label}
   */
  oSelf.oLabelAltura           = document.createElement( 'label' );
  oSelf.oLabelAltura.addClassName( 'bold' );
  oSelf.oLabelAltura.setAttribute('for', 'oInputAltura');
  oSelf.oLabelAltura.innerHTML = 'Altura: ';

  /**
   * Input com informação da altura
   * @type {input}
   */
  oSelf.oInputAltura = document.createElement( 'input' );
  oSelf.oInputAltura.addClassName( 'field-size1' );
  oSelf.oInputAltura.setAttribute( 'id', 'oInputAltura' );
  oSelf.oInputAltura.setAttribute( 'type', 'text' );
  oSelf.oInputAltura.setAttribute( 'maxLength', '3' );

  /**
   * Label do Perímetro Cefálico
   * @type {label}
   */
  oSelf.oLabelPerimetroCefalico           = document.createElement( 'label' );
  oSelf.oLabelPerimetroCefalico.addClassName( 'bold' );
  oSelf.oLabelPerimetroCefalico.setAttribute('for', 'oInputPerimetroCefalico');
  oSelf.oLabelPerimetroCefalico.innerHTML = 'Perímetro Cefálico: ';

  /**
   * Input com informação do perímetro cefálico
   * @type {input}
   */
  oSelf.oInputPerimetroCefalico = document.createElement( 'input' );
  oSelf.oInputPerimetroCefalico.addClassName( 'field-size1' );
  oSelf.oInputPerimetroCefalico.setAttribute( 'id', 'oInputPerimetroCefalico' );
  oSelf.oInputPerimetroCefalico.setAttribute( 'type', 'text' );
  oSelf.oInputPerimetroCefalico.setAttribute( 'maxLength', '3' );

  /**
   * Vínculos dos campos da primeira linha das Antropometria
   */
  oSelf.oTabelaAntropometria.appendChild( oSelf.oLinhaAntropometria );

  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaCinturaLabel );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaCinturaDescricao );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaPesoLabel );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaPesoDescricao );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaAlturaLabel );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaAlturaDescricao );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaPerimetroCefalicoLabel );
  oSelf.oLinhaAntropometria.appendChild( oSelf.oColunaPerimetroCefalicoDescricao );

  oSelf.oColunaCinturaLabel.appendChild( oSelf.oLabelCintura );
  oSelf.oColunaCinturaDescricao.appendChild( oSelf.oInputCintura );
  oSelf.oColunaPesoLabel.appendChild( oSelf.oLabelPeso );
  oSelf.oColunaPesoDescricao.appendChild( oSelf.oInputPeso );
  oSelf.oColunaAlturaLabel.appendChild( oSelf.oLabelAltura );
  oSelf.oColunaAlturaDescricao.appendChild( oSelf.oInputAltura );
  oSelf.oColunaPerimetroCefalicoLabel.appendChild( oSelf.oLabelPerimetroCefalico );
  oSelf.oColunaPerimetroCefalicoDescricao.appendChild( oSelf.oInputPerimetroCefalico );

  /**
   * Elementos da segunda linha das Antropometria
   */
  oSelf.oLinhaAntropometria2 = document.createElement( 'tr' );
  oSelf.oColunaIMCLabel      = document.createElement( 'td' );
  oSelf.oColunaIMCValor      = document.createElement( 'td' );
  oSelf.oColunaIMCDescricao  = document.createElement( 'td' );
  oSelf.oColunaIMCDescricao.setAttribute( 'colSpan', '6' );

  /**
   * Label do IMC
   * @type {label}
   */
  oSelf.oLabelIMC           = document.createElement( 'label' );
  oSelf.oLabelIMC.addClassName( 'bold' );
  oSelf.oLabelIMC.setAttribute('for', 'oInputIMCValor');
  oSelf.oLabelIMC.innerHTML = 'IMC: ';

  /**
   * Input com informação da IMC
   * @type {input}
   */
  oSelf.oInputIMCValor = document.createElement( 'input' );
  oSelf.oInputIMCValor.addClassName( 'field-size1' );
  oSelf.oInputIMCValor.setAttribute( 'id', 'oInputIMCValor' );
  oSelf.oInputIMCValor.setAttribute( 'type', 'text' );
  oSelf.oInputIMCValor.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputIMCValor.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Input com informação da descrição do IMC
   * @type {input}
   */
  oSelf.oInputIMCDescricao = document.createElement( 'input' );
  oSelf.oInputIMCDescricao.addClassName( 'field-size8' );
  oSelf.oInputIMCDescricao.setAttribute( 'id', 'oInputIMCDescricao' );
  oSelf.oInputIMCDescricao.setAttribute( 'type', 'text' );
  oSelf.oInputIMCDescricao.setAttribute( 'readOnly', 'readOnly' );
  oSelf.oInputIMCDescricao.setStyle( { 'backgroundColor' : '#DEB887' } );

  /**
   * Vínculos dos campos da segunda linha das Antropometria
   */
  oSelf.oTabelaAntropometria.appendChild( oSelf.oLinhaAntropometria2 );
  oSelf.oLinhaAntropometria2.appendChild( oSelf.oColunaIMCLabel );
  oSelf.oLinhaAntropometria2.appendChild( oSelf.oColunaIMCValor );
  oSelf.oLinhaAntropometria2.appendChild( oSelf.oColunaIMCDescricao );

  oSelf.oColunaIMCLabel.appendChild( oSelf.oLabelIMC );
  oSelf.oColunaIMCValor.appendChild( oSelf.oInputIMCValor );
  oSelf.oColunaIMCDescricao.appendChild( oSelf.oInputIMCDescricao );

  /* **************************************************
   * ELEMENTOS DO FIELDSET E DADOS DA Sinais Vitais
   * **************************************************/
  /**
   * Linha e coluna do fieldset da Sinais Vitais
   * @type {HTMLElement}
   */
  oSelf.oLinhaTabelaSinaisVitais  = document.createElement( 'tr' );
  oSelf.oColunaTabelaSinaisVitais = document.createElement( 'td' );
  oSelf.oColunaTabelaSinaisVitais.setAttribute( 'colSpan', '3' );

  /**
   * Fieldset da Sinais Vitais
   * @type {fieldset}
   */
  oSelf.oFieldsetSinaisVitais = document.createElement( 'fieldset' );
  oSelf.oFieldsetSinaisVitais.addClassName( 'separator' );

  /**
   * Legenda do fieldset da Sinais Vitais
   * @type {legend}
   */
  oSelf.oLegendaSinaisVitais = document.createElement( 'legend' );
  oSelf.oLegendaSinaisVitais.addClassName( 'bold' );
  oSelf.oLegendaSinaisVitais.innerHTML = 'Sinais Vitais';

  /**
   * Tabela com os dados da Sinais Vitais
   * @type {table}
   */
  oSelf.oTabelaSinaisVitais = document.createElement( 'table' );

  /**
   * Vínculos da tabela principal com o fieldset da Sinais Vitais
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaTabelaSinaisVitais );

  oSelf.oLinhaTabelaSinaisVitais.appendChild( oSelf.oColunaTabelaSinaisVitais );
  oSelf.oColunaTabelaSinaisVitais.appendChild( oSelf.oFieldsetSinaisVitais );

  oSelf.oFieldsetSinaisVitais.appendChild( oSelf.oLegendaSinaisVitais );
  oSelf.oFieldsetSinaisVitais.appendChild( oSelf.oTabelaSinaisVitais );

  /**
   * Linha e colunas referentes aos dados da Sinais Vitais
   */
  oSelf.oLinhaSinaisVitais1 = document.createElement( 'tr' );
  oSelf.oLinhaSinaisVitais2 = document.createElement( 'tr' );

  oSelf.oColunaSistolicaLabel                  = document.createElement( 'td' );
  oSelf.oColunaSistolicaDescricao              = document.createElement( 'td' );
  oSelf.oColunaDiastolicaLabel                 = document.createElement( 'td' );
  oSelf.oColunaDiastolicaDescricao             = document.createElement( 'td' );
  oSelf.oColunaFrequenciaRespiratoriaLabel     = document.createElement( 'td' );
  oSelf.oColunaFrequenciaRespiratoriaDescricao = document.createElement( 'td' );
  oSelf.oColunaFrequenciaRespiratoriaDescricao.setAttribute('colSpan', '2');

  oSelf.oColunaFrequenciaCardiacaLabel     = document.createElement( 'td' );
  oSelf.oColunaFrequenciaCardiacaDescricao = document.createElement( 'td' );
  oSelf.oColunaTemperaturaLabel            = document.createElement( 'td' );
  oSelf.oColunaTemperaturaDescricao        = document.createElement( 'td' );
  oSelf.oColunaSaturacaoLabel              = document.createElement( 'td' );
  oSelf.oColunaSaturacaoDescricao          = document.createElement( 'td' );

  /**
   * Label da Pressão Arterial
   * @type {label}
   */
  oSelf.oLabelSistolica           = document.createElement( 'label' );
  oSelf.oLabelSistolica.addClassName( 'bold' );
  oSelf.oLabelSistolica.setAttribute('for', 'oInputSistolica');
  oSelf.oLabelSistolica.innerHTML = 'Pressão Arterial: ';

  /**
   * Input com informação da Pressão Arterial
   * @type {input}
   */
  oSelf.oInputSistolica = document.createElement( 'input' );
  oSelf.oInputSistolica.addClassName( 'field-size1' );
  oSelf.oInputSistolica.setAttribute( 'id', 'oInputSistolica' );
  oSelf.oInputSistolica.setAttribute( 'type', 'text' );
  oSelf.oInputSistolica.setAttribute( 'maxLength', '3' );

  /**
   * Input com informação da Diastólica
   * @type {input}
   */
  oSelf.oInputDiastolica = document.createElement( 'input' );
  oSelf.oInputDiastolica.addClassName( 'field-size1' );
  oSelf.oInputDiastolica.setAttribute( 'id', 'oInputDiastolica' );
  oSelf.oInputDiastolica.setAttribute( 'type', 'text' );
  oSelf.oInputDiastolica.setAttribute( 'maxLength', '3' );

  /**
   * Label da Frequência Respiratória
   * @type {label}
   */
  oSelf.oLabelFrequenciaRespiratoria = document.createElement( 'label' );
  oSelf.oLabelFrequenciaRespiratoria.addClassName( 'bold' );
  oSelf.oLabelFrequenciaRespiratoria.setAttribute('for', 'oInputFrequenciaRespiratoria');
  oSelf.oLabelFrequenciaRespiratoria.innerHTML = 'Frequência Respiratória: ';

  /**
   * Input com informação da Frequência Respiratória
   * @type {input}
   */
  oSelf.oInputFrequenciaRespiratoria = document.createElement( 'input' );
  oSelf.oInputFrequenciaRespiratoria.addClassName( 'field-size1' );
  oSelf.oInputFrequenciaRespiratoria.setAttribute( 'id', 'oInputFrequenciaRespiratoria' );
  oSelf.oInputFrequenciaRespiratoria.setAttribute( 'type', 'text' );
  oSelf.oInputFrequenciaRespiratoria.setAttribute( 'maxLength', '3' );

  /**
   * Label da Frequência Cardiáca
   * @type {label}
   */
  oSelf.oLabelFrequenciaCardiaca = document.createElement( 'label' );
  oSelf.oLabelFrequenciaCardiaca.addClassName( 'bold' );
  oSelf.oLabelFrequenciaCardiaca.setAttribute('for', 'oInputFrequenciaCardiaca');
  oSelf.oLabelFrequenciaCardiaca.innerHTML = 'Frequência Cardíaca: ';

  /**
   * Input com informação da Frequência Cardíaca
   * @type {input}
   */
  oSelf.oInputFrequenciaCardiaca = document.createElement( 'input' );
  oSelf.oInputFrequenciaCardiaca.addClassName( 'field-size1' );
  oSelf.oInputFrequenciaCardiaca.setAttribute( 'id', 'oInputFrequenciaCardiaca' );
  oSelf.oInputFrequenciaCardiaca.setAttribute( 'type', 'text' );
  oSelf.oInputFrequenciaCardiaca.setAttribute( 'maxLength', '3' );

  /**
   * Label da Temperatura
   * @type {label}
   */
  oSelf.oLabelTemperatura           = document.createElement( 'label' );
  oSelf.oLabelTemperatura.addClassName( 'bold' );
  oSelf.oLabelTemperatura.setAttribute('for', 'oInputTemperatura');
  oSelf.oLabelTemperatura.innerHTML = 'Temperatura: ';

  /**
   * Input com informação da temperatura
   * @type {input}
   */
  oSelf.oInputTemperatura           = document.createElement( 'input' );
  oSelf.oInputTemperatura.addClassName( 'field-size1' );
  oSelf.oInputTemperatura.setAttribute( 'id', 'oInputTemperatura' );
  oSelf.oInputTemperatura.setAttribute( 'type', 'text' );
  oSelf.oInputTemperatura.setAttribute( 'maxLength', '6' );

  /**
   * Label da Saturação
   * @type {label}
   */
  oSelf.oLabelSaturacao           = document.createElement( 'label' );
  oSelf.oLabelSaturacao.addClassName( 'bold' );
  oSelf.oLabelSaturacao.setAttribute('for', 'oInputSaturacao');
  oSelf.oLabelSaturacao.innerHTML = 'Saturação de O2: ';

  /**
   * Input com informação da saturação
   * @type {input}
   */
  oSelf.oInputSaturacao           = document.createElement( 'input' );
  oSelf.oInputSaturacao.addClassName( 'field-size1' );
  oSelf.oInputSaturacao.setAttribute( 'id', 'oInputSaturacao' );
  oSelf.oInputSaturacao.setAttribute( 'type', 'text' );
  oSelf.oInputSaturacao.setAttribute( 'maxLength', '6' );

  /**
   * Vínculos dos campos da Sinais Vitais
   */
  oSelf.oTabelaSinaisVitais.appendChild( oSelf.oLinhaSinaisVitais1 );
  oSelf.oTabelaSinaisVitais.appendChild( oSelf.oLinhaSinaisVitais2 );

  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaSistolicaLabel );
  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaSistolicaDescricao );
  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaDiastolicaDescricao );
  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaTemperaturaLabel );
  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaTemperaturaDescricao );
  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaSaturacaoLabel );
  oSelf.oLinhaSinaisVitais1.appendChild( oSelf.oColunaSaturacaoDescricao );

  oSelf.oLinhaSinaisVitais2.appendChild( oSelf.oColunaFrequenciaRespiratoriaLabel );
  oSelf.oLinhaSinaisVitais2.appendChild( oSelf.oColunaFrequenciaRespiratoriaDescricao );
  oSelf.oLinhaSinaisVitais2.appendChild( oSelf.oColunaFrequenciaCardiacaLabel );
  oSelf.oLinhaSinaisVitais2.appendChild( oSelf.oColunaFrequenciaCardiacaDescricao );

  oSelf.oColunaSistolicaLabel.appendChild( oSelf.oLabelSistolica );
  oSelf.oColunaSistolicaDescricao.appendChild( oSelf.oInputSistolica );
  oSelf.oColunaDiastolicaDescricao.appendChild( oSelf.oInputDiastolica );
  oSelf.oColunaTemperaturaLabel.appendChild( oSelf.oLabelTemperatura );
  oSelf.oColunaTemperaturaDescricao.appendChild( oSelf.oInputTemperatura );
  oSelf.oColunaSaturacaoLabel.appendChild( oSelf.oLabelSaturacao );
  oSelf.oColunaSaturacaoDescricao.appendChild( oSelf.oInputSaturacao );

  oSelf.oColunaFrequenciaRespiratoriaLabel.appendChild( oSelf.oLabelFrequenciaRespiratoria );
  oSelf.oColunaFrequenciaRespiratoriaDescricao.appendChild( oSelf.oInputFrequenciaRespiratoria );
  oSelf.oColunaFrequenciaCardiacaLabel.appendChild( oSelf.oLabelFrequenciaCardiaca );
  oSelf.oColunaFrequenciaCardiacaDescricao.appendChild( oSelf.oInputFrequenciaCardiaca );


  /* ***************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DA GLICEMIA
   * ***************************************************/
  /**
   * Linha e colunas da Glicemia
   */
  oSelf.oLinhaTabelaGlicemia  = document.createElement( 'tr' );
  oSelf.oColunaTabelaGlicemia = document.createElement( 'td' );
  oSelf.oColunaTabelaGlicemia.setAttribute( 'colSpan', '3' );

  /**
   * Fieldset da glicemia
   * @type {fieldset}
   */
  oSelf.oFieldsetGlicemia = document.createElement( 'fieldset' );
  oSelf.oFieldsetGlicemia.addClassName( 'separator' );

  /**
   * Legenda do fieldset da glicemia
   * @type {legend}
   */
  oSelf.oLegendaGlicemia           = document.createElement( 'legend' );
  oSelf.oLegendaGlicemia.addClassName( 'bold' );
  oSelf.oLegendaGlicemia.innerHTML = 'Glicemia';

  /**
   * Tabela com os dados da glicemia
   * @type {table}
   */
  oSelf.oTabelaGlicemia = document.createElement( 'table' );

  /**
   * Vínculos da tabela principal com o fieldset da glicemia
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaTabelaGlicemia );
  oSelf.oLinhaTabelaGlicemia.appendChild( oSelf.oColunaTabelaGlicemia );
  oSelf.oColunaTabelaGlicemia.appendChild( oSelf.oFieldsetGlicemia );

  oSelf.oFieldsetGlicemia.appendChild( oSelf.oLegendaGlicemia );
  oSelf.oFieldsetGlicemia.appendChild( oSelf.oTabelaGlicemia );

  /**
   * Linha e colunas referentes aos dados da glicemia
   */
  oSelf.oLinhaGlicemia              = document.createElement( 'tr' );
  oSelf.oColunaGlicemiaCapilarLabel = document.createElement( 'td' );
  oSelf.oColunaGlicemiaCapilarValor = document.createElement( 'td' );
  oSelf.oColunaMomentoColetaLabel   = document.createElement( 'td' );
  oSelf.oColunaMomentoColetaValor   = document.createElement( 'td' );

  /**
   * Label de Glicemia Capilar
   * @type {label}
   */
  oSelf.oLabelGlicemiaCapilar           = document.createElement( 'label' );
  oSelf.oLabelGlicemiaCapilar.addClassName( 'bold' );
  oSelf.oLabelGlicemiaCapilar.setAttribute('for', 'oInputGlicemiaCapilar');
  oSelf.oLabelGlicemiaCapilar.innerHTML = 'Glicemia Capilar: ';

  /**
   * Input com informação do exame de glicemia
   * @type {input}
   */
  oSelf.oInputGlicemiaCapilar = document.createElement( 'input' );
  oSelf.oInputGlicemiaCapilar.addClassName( 'field-size1' );
  oSelf.oInputGlicemiaCapilar.setAttribute( 'id', 'oInputGlicemiaCapilar' );
  oSelf.oInputGlicemiaCapilar.setAttribute( 'type', 'text' );
  oSelf.oInputGlicemiaCapilar.setAttribute( 'maxLength', '3' );

  /**
   * Label de Momento da Coleta
   * @type {label}
   */
  oSelf.oLabelMomentoColeta           = document.createElement( 'label' );
  oSelf.oLabelMomentoColeta.addClassName( 'bold' );
  oSelf.oLabelMomentoColeta.setAttribute('for', 'oCboMomentoColeta');
  oSelf.oLabelMomentoColeta.innerHTML = 'Momento da Coleta: ';

  /**
   * Elemento para o combo do momento da coleta
   * @type {input}
   */
  oSelf.oCboMomentoColeta = document.createElement( 'select' );
  oSelf.oCboMomentoColeta.setAttribute("id", 'oCboMomentoColeta');
  oSelf.oCboMomentoColeta.style.width = '100%';
  oSelf.oCboMomentoColeta.add(new Option('JEJUM', '1'));
  oSelf.oCboMomentoColeta.add(new Option('PÓS-PRANDIAL', '2'));
  oSelf.oCboMomentoColeta.add(new Option('PRÉ-PRANDIAL', '3'));
  oSelf.oCboMomentoColeta.add(new Option('NÃO ESPECIFICADO', '0'));

  /**
   * Vínculos dos elementos da glicemia
   */
  oSelf.oTabelaGlicemia.appendChild( oSelf.oLinhaGlicemia );
  oSelf.oLinhaGlicemia.appendChild( oSelf.oColunaGlicemiaCapilarLabel );
  oSelf.oLinhaGlicemia.appendChild( oSelf.oColunaGlicemiaCapilarValor );
  oSelf.oLinhaGlicemia.appendChild( oSelf.oColunaMomentoColetaLabel );
  oSelf.oLinhaGlicemia.appendChild( oSelf.oColunaMomentoColetaValor );

  oSelf.oColunaGlicemiaCapilarLabel.appendChild( oSelf.oLabelGlicemiaCapilar );
  oSelf.oColunaGlicemiaCapilarValor.appendChild( oSelf.oInputGlicemiaCapilar );
  oSelf.oColunaMomentoColetaLabel.appendChild( oSelf.oLabelMomentoColeta );
  oSelf.oColunaMomentoColetaValor.appendChild( oSelf.oCboMomentoColeta );


  /* ************************************************
   * ELEMENTOS E VÍNCULOS REFERENTE A LINHA DA MULHER
   * ************************************************/
  /**
   * Linha e colunas da Mulher
   */
  oSelf.oLinhaTabelaMulher  = document.createElement( 'tr' );
  oSelf.oLinhaTabelaMulher.setAttribute('id', 'linhaTabelaMulher');
  oSelf.oLinhaTabelaMulher.setStyle({'display': 'none'});

  oSelf.oColunaTabelaMulher = document.createElement( 'td' );
  oSelf.oColunaTabelaMulher.setAttribute( 'colSpan', '3' );

  /**
   * Fieldset da Mulher
   * @type {fieldset}
   */
  oSelf.oFieldsetMulher = document.createElement( 'fieldset' );
  oSelf.oFieldsetMulher.addClassName( 'separator' );

  /**
   * Legenda do fieldset da Mulher
   * @type {legend}
   */
  oSelf.oLegendaMulher           = document.createElement( 'legend' );
  oSelf.oLegendaMulher.addClassName( 'bold' );
  oSelf.oLegendaMulher.innerHTML = 'Mulher';

  /**
   * Tabela com os dados da Mulher
   * @type {table}
   */
  oSelf.oTabelaMulher = document.createElement( 'table' );

  /**
   * Vínculos da tabela principal com o fieldset da Mulher
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaTabelaMulher );
  oSelf.oLinhaTabelaMulher.appendChild( oSelf.oColunaTabelaMulher );
  oSelf.oColunaTabelaMulher.appendChild( oSelf.oFieldsetMulher );

  oSelf.oFieldsetMulher.appendChild( oSelf.oLegendaMulher );
  oSelf.oFieldsetMulher.appendChild( oSelf.oTabelaMulher );

  /**
   * Linha e colunas referentes aos dados da mulher
   */
  oSelf.oLinhaMulher       = document.createElement( 'tr' );
  oSelf.oColunaMulherLabel = document.createElement( 'td' );
  oSelf.oColunaMulherData  = document.createElement( 'td' );
  oSelf.oColunaDUM         = document.createElement( 'td' );

  /**
   * Label de DUM
   * @type {label}
   */
  oSelf.oLabelDUM           = document.createElement( 'label' );
  oSelf.oLabelDUM.addClassName( 'bold' );
  oSelf.oLabelDUM.setAttribute('for', 'oInputDataDUM');
  oSelf.oLabelDUM.innerHTML = 'DUM: ';

  /**
   * Input com informação dum
   * @type {input}
   */
  oSelf.oInputDataDUM = document.createElement( 'input' );
  oSelf.oInputDataDUM.setAttribute( 'id', 'oInputDataDUM' );
  oSelf.oInputDataDUM.setAttribute( 'type', 'text' );

  oSelf.oDivDUM = document.createElement('div');
  oSelf.oDivDUM.addClassName('bold');
  oSelf.oDivDUM.innerHTML = 'Última DUM registrada: ';

  oSelf.oSpanDUM           = document.createElement('span');
  oSelf.oSpanDUM.setAttribute('id', 'ultimaDUM');
  oSelf.oSpanDUM.innerHTML = 'Não informada';

  oSelf.oTabelaMulher.appendChild(oSelf.oLinhaMulher);

  oSelf.oLinhaMulher.appendChild(oSelf.oColunaMulherLabel);
  oSelf.oLinhaMulher.appendChild(oSelf.oColunaMulherData);
  oSelf.oLinhaMulher.appendChild(oSelf.oColunaDUM);

  oSelf.oColunaMulherLabel.appendChild(oSelf.oLabelDUM);
  oSelf.oColunaMulherData.appendChild(oSelf.oInputDataDUM);
  oSelf.oColunaDUM.appendChild(oSelf.oDivDUM);

  oSelf.oDivDUM.appendChild(oSelf.oSpanDUM);

  new DBInputDate(oSelf.oInputDataDUM);

  /**
   * Tabela com os dados da Prioridade
   */
  oSelf.oTabelaPrioridade = document.createElement( 'table' );

  /**
   * Linha contendo o Fieldset da prioridade
   */
  oSelf.oLinhaFieldsetPrioridade = document.createElement( 'tr' );
  oSelf.oLinhaFieldsetPrioridade.setStyle( { 'display' : 'none' } );

  if ( oSelf.iTelaOrigem != DBViewTriagem.prototype.TELA_TRIAGEM_AVULSA ) {
    oSelf.oLinhaFieldsetPrioridade.setStyle( { 'display' : '' } );
  }

  /**
   * Coluna contendo o Fieldset da prioridade
   */
  oSelf.oColunaFieldsetPrioridade              = document.createElement( 'td' );
  oSelf.oColunaFieldsetPrioridade.setAttribute( 'colspan', '5' );

  /**
   * Fieldset da Prioridade
   */
  oSelf.oFieldsetPrioridade = document.createElement( 'fieldset' );
  oSelf.oFieldsetPrioridade.addClassName( 'separator' );

  if ( this.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA ) {
    oSelf.oFieldsetPrioridade.style.display = 'none';
  }

  /**
   * Legend do Fieldset da Prioridade
   */
  oSelf.oLegendaPrioridade = document.createElement( 'legend' );
  oSelf.oLegendaPrioridade.addClassName( 'bold' );
  oSelf.oLegendaPrioridade.innerHTML = 'Prioridade de Atendimento';

  /**
   * Label da Prioridade
   */
  oSelf.oLabelPrioridade = document.createElement( 'label' );
  oSelf.oLabelPrioridade.addClassName( 'bold' );
  oSelf.oLabelPrioridade.setAttribute('for', 'oCboPrioridade');
  oSelf.oLabelPrioridade.innerHTML = 'Prioridade:';

  /**
   * Combobox contendo as prioridades de atendimento
   */
  oSelf.oCboPrioridade             = document.createElement( 'select' );
  oSelf.oCboPrioridade.setAttribute( 'id', 'oCboPrioridade' );
  oSelf.oCboPrioridade.addClassName( 'field-size-max' );

  /**
   * Busca as Prioridades cadastradas
   */
  oSelf.criaComboPrioridade();

  /**
   * Linha e colunas do label e combobox das Prioridades
   */
  oSelf.oLinhaPrioridade          = document.createElement( 'tr' );
  oSelf.oColunaLabelPrioridade    = document.createElement( 'td' );
  oSelf.oColunaComboPrioridade    = document.createElement( 'td' );
  oSelf.oColunaComboPrioridade.setAttribute( 'colspan', '2' );


  /**
   * Vínculos da Tabela Principal com a Tabela de Prioridade
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaFieldsetPrioridade );
  oSelf.oLinhaFieldsetPrioridade.appendChild( oSelf.oColunaFieldsetPrioridade );
  oSelf.oColunaFieldsetPrioridade.appendChild( oSelf.oFieldsetPrioridade );
  oSelf.oFieldsetPrioridade.appendChild( oSelf.oLegendaPrioridade );

  oSelf.oFieldsetPrioridade.appendChild( oSelf.oTabelaPrioridade );
  oSelf.oTabelaPrioridade.appendChild( oSelf.oLinhaPrioridade );
  oSelf.oLinhaPrioridade.appendChild( oSelf.oColunaLabelPrioridade );
  oSelf.oColunaLabelPrioridade.appendChild( oSelf.oLabelPrioridade );
  oSelf.oLinhaPrioridade.appendChild( oSelf.oColunaComboPrioridade );
  oSelf.oColunaComboPrioridade.appendChild( oSelf.oCboPrioridade );

  /* *******************************
   * ELEMENTOS E VÍNCULOS DA EVOLUCAO
   * *******************************/

  /**
   * Tabela com os dados da Evolução
   */
  oSelf.oTabelaEvolucao = document.createElement( 'table' );
  oSelf.oTabelaEvolucao.setAttribute( 'style', 'width:100%' );

  /**
   * Linha contendo o Fieldset da Evolucao
   */
  oSelf.oLinhaFieldsetEvolucao = document.createElement( 'tr' );
  oSelf.oLinhaFieldsetEvolucao.setStyle( { 'display' : 'none' } );

  if (    oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM
       || oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA ) {
    oSelf.oLinhaFieldsetEvolucao.setStyle( { 'display' : '' } );
  }

  /**
   * Coluna contendo o Fieldset da Evolucao
   */
  oSelf.oColunaFieldsetEvolucao              = document.createElement( 'td' );
  oSelf.oColunaFieldsetEvolucao.setAttribute( 'colspan', '5' );

  /**
   * Fieldset da Evolução
   */
  oSelf.oFieldsetEvolucao = document.createElement( 'fieldset' );
  oSelf.oFieldsetEvolucao.addClassName( 'separator' );

  /**
   * Legend do Fieldset da Evolucao
   */
  oSelf.oLegendaEvolucao = document.createElement( 'legend' );

  /**
   * Label da evoluçao
   */
  oSelf.oLabelEvolucao = document.createElement( 'label' );
  oSelf.oLabelEvolucao.addClassName( 'bold' );
  oSelf.oLabelEvolucao.setAttribute('for', 'oTextEvolucao');
  oSelf.oLabelEvolucao.innerHTML = 'Evolução:';

  /**
   * Combobox contendo as prioridades de atendimento
   */
  oSelf.oTextEvolucao = document.createElement( 'textarea' );
  oSelf.oTextEvolucao.setAttribute( 'id', 'oTextEvolucao' );
  oSelf.oTextEvolucao.addClassName( 'field-size-max' );

  oSelf.oLinhaEvolucao  = document.createElement( 'tr' );
  oSelf.oColunaEvolucao = document.createElement( 'td' );
  oSelf.oColunaEvolucao.setAttribute( 'colspan', '3' );

  /**
   * Vínculos da Tabela Principal com a Tabela de Evolução
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaFieldsetEvolucao );

  oSelf.oLinhaFieldsetEvolucao.appendChild( oSelf.oColunaFieldsetEvolucao );
  oSelf.oColunaFieldsetEvolucao.appendChild( oSelf.oFieldsetEvolucao );

  oSelf.oLegendaEvolucao.appendChild(oSelf.oLabelEvolucao);

  oSelf.oFieldsetEvolucao.appendChild( oSelf.oLegendaEvolucao );
  oSelf.oFieldsetEvolucao.appendChild( oSelf.oTabelaEvolucao );

  oSelf.oTabelaEvolucao.appendChild( oSelf.oLinhaEvolucao );
  oSelf.oLinhaEvolucao.appendChild( oSelf.oColunaEvolucao );
  oSelf.oColunaEvolucao.appendChild( oSelf.oTextEvolucao );

  /* *******************************
   * ELEMENTOS E VÍNCULOS DO AGRAVO
   * *******************************/
  /**
   * Linha e coluna referente a descrição do agravo
   */
  oSelf.oLinhaTabelaAgravo  = document.createElement( 'tr' );
  oSelf.oColunaTabelaAgravo = document.createElement( 'td' );
  oSelf.oColunaTabelaAgravo.setAttribute( 'colSpan', '5' );

  /**
   * Fieldset do agravo
   * @type {fieldset}
   */
  oSelf.oFieldsetAgravo = document.createElement( 'fieldset' );
  oSelf.oFieldsetAgravo.addClassName( 'separator' );

  /**
   * Legenda do fieldset do agravo
   * @type {legend}
   */
  oSelf.oLegendaAgravo           = document.createElement( 'legend' );
  oSelf.oLegendaAgravo.addClassName( 'bold' );
  oSelf.oLegendaAgravo.innerHTML = 'Agravo';

  /**
   * Vínculos do fieldset do agravo
   */
  oSelf.oTabelaPrincipal.appendChild( oSelf.oLinhaTabelaAgravo );
  oSelf.oLinhaTabelaAgravo.appendChild( oSelf.oColunaTabelaAgravo );
  oSelf.oColunaTabelaAgravo.appendChild( oSelf.oFieldsetAgravo );
  oSelf.oFieldsetAgravo.appendChild( oSelf.oLegendaAgravo );

  /**
   * Tabela com as linhas das informações do agravo
   * @type {table}
   */
  oSelf.oTabelaAgravo = document.createElement( 'table' );

  /**
   * Linha e colunas referentes as informações do agravo
   */
  oSelf.oLinhaDescricaoAgravo  = document.createElement( 'tr' );
  oSelf.oColunaAgravoLabel     = document.createElement( 'td' );
  oSelf.oColunaAgravoDescricao = document.createElement( 'td' );

  /**
   * Label do agravo
   * @type {label}
   */
  oSelf.oLabelAgravo           = document.createElement( 'label' );
  oSelf.oLabelAgravo.addClassName( 'bold' );
  oSelf.oLabelAgravo.setAttribute('for', 'oInputAgravoDescricao');
  oSelf.oLabelAgravo.innerHTML = 'Agravo: ';

  /**
   * Input para digitação do agravo
   * @type {input}
   */
  oSelf.oInputAgravoDescricao = document.createElement( 'input' );
  oSelf.oInputAgravoDescricao.addClassName( 'field-size8' );
  oSelf.oInputAgravoDescricao.setAttribute( 'id', 'oInputAgravoDescricao' );
  oSelf.oInputAgravoDescricao.setAttribute( 'type', 'text' );

  /**
   * Vincula os elementos referentes ao agravo
   */
  oSelf.oFieldsetAgravo.appendChild( oSelf.oTabelaAgravo );

  oSelf.oTabelaAgravo.appendChild( oSelf.oLinhaDescricaoAgravo );

  oSelf.oLinhaDescricaoAgravo.appendChild( oSelf.oColunaAgravoLabel );
  oSelf.oLinhaDescricaoAgravo.appendChild( oSelf.oColunaAgravoDescricao );

  oSelf.oColunaAgravoLabel.appendChild( oSelf.oLabelAgravo );
  oSelf.oColunaAgravoDescricao.appendChild( oSelf.oInputAgravoDescricao );

  /**
   * Linha e coluna referente a data do primeiro sintoma
   */
  oSelf.oLinhaDataPrimeiroSintoma        = document.createElement( 'tr' );
  oSelf.oColunaDataPrimeiroSintomaLabel  = document.createElement( 'td' );
  oSelf.oColunaDataPrimeiroSintomaValor  = document.createElement( 'td' );
  oSelf.oColunaDataPrimeiroSintomaCampos = document.createElement( 'td' );

  /**
   * Label da data do primeiro sintoma
   * @type {label}
   */
  oSelf.oLabelDataPrimeiroSintoma           = document.createElement( 'label' );
  oSelf.oLabelDataPrimeiroSintoma.addClassName( 'bold' );
  oSelf.oLabelDataPrimeiroSintoma.setAttribute('for', 'oInputDataPrimeiroSintomaValor');
  oSelf.oLabelDataPrimeiroSintoma.innerHTML = 'Data do Primeiro Sintoma: ';

  /**
   * Input para preenchimento da data do primeiro sintoma
   * @type {input}
   */
  oSelf.oInputDataPrimeiroSintomaValor = document.createElement( 'input' );
  oSelf.oInputDataPrimeiroSintomaValor.addClassName( 'field-size2' );
  oSelf.oInputDataPrimeiroSintomaValor.setAttribute( 'id', 'oInputDataPrimeiroSintomaValor' );
  oSelf.oInputDataPrimeiroSintomaValor.setAttribute( 'name', 'oInputDataPrimeiroSintoma' );
  oSelf.oInputDataPrimeiroSintomaValor.setAttribute( 'type', 'text' );
  oSelf.oInputDataPrimeiroSintomaValor.setAttribute( 'onkeyup', 'return js_mascaraData(this,event)' );
  oSelf.oInputDataPrimeiroSintomaValor.setAttribute( 'maxLength', '10');

  /**
   * Botão para selecionar a data do primeiro sintoma
   * @type {input}
   */
  oSelf.oInputDataPrimeiroSintoma = document.createElement( 'input' );
  oSelf.oInputDataPrimeiroSintoma.setAttribute( 'id', 'oInputDataPrimeiroSintoma' );
  oSelf.oInputDataPrimeiroSintoma.setAttribute( 'type', 'button' );
  oSelf.oInputDataPrimeiroSintoma.setAttribute( 'value', 'D' );
  oSelf.oInputDataPrimeiroSintoma.setAttribute( "onclick", "pegaPosMouse(event); show_calendar('oInputDataPrimeiroSintoma','none')" );

  /**
   * Input com o dia da data do primeiro sintoma
   * @type {input}
   */
  oSelf.oInputDataPrimeiroSintomaDia = document.createElement( 'input' );
  oSelf.oInputDataPrimeiroSintomaDia.addClassName( 'field-size2' );
  oSelf.oInputDataPrimeiroSintomaDia.setAttribute( 'id', 'oInputDataPrimeiroSintoma_dia' );
  oSelf.oInputDataPrimeiroSintomaDia.setAttribute( 'type', 'text' );
  oSelf.oInputDataPrimeiroSintomaDia.setStyle( { 'display' : 'none' } );

  /**
   * Input com o mês da data do primeiro sintoma
   * @type {input}
   */
  oSelf.oInputDataPrimeiroSintomaMes = document.createElement( 'input' );
  oSelf.oInputDataPrimeiroSintomaMes.addClassName( 'field-size2' );
  oSelf.oInputDataPrimeiroSintomaMes.setAttribute( 'id', 'oInputDataPrimeiroSintoma_mes' );
  oSelf.oInputDataPrimeiroSintomaMes.setAttribute( 'type', 'text' );
  oSelf.oInputDataPrimeiroSintomaMes.setStyle( { 'display' : 'none' } );

  /**
   * Input com o ano da data do primeiro sintoma
   * @type {input}
   */
  oSelf.oInputDataPrimeiroSintomaAno = document.createElement( 'input' );
  oSelf.oInputDataPrimeiroSintomaAno.addClassName( 'field-size2' );
  oSelf.oInputDataPrimeiroSintomaAno.setAttribute( 'id', 'oInputDataPrimeiroSintoma_ano' );
  oSelf.oInputDataPrimeiroSintomaAno.setAttribute( 'type', 'text' );
  oSelf.oInputDataPrimeiroSintomaAno.setStyle( { 'display' : 'none' } );

  /**
   * Vinculas os elementos com as informações da data do primeiro sintoma
   */
  oSelf.oTabelaAgravo.appendChild( oSelf.oLinhaDataPrimeiroSintoma );

  oSelf.oLinhaDataPrimeiroSintoma.appendChild( oSelf.oColunaDataPrimeiroSintomaLabel );
  oSelf.oLinhaDataPrimeiroSintoma.appendChild( oSelf.oColunaDataPrimeiroSintomaValor );
  oSelf.oLinhaDataPrimeiroSintoma.appendChild( oSelf.oColunaDataPrimeiroSintomaCampos );

  oSelf.oColunaDataPrimeiroSintomaLabel.appendChild( oSelf.oLabelDataPrimeiroSintoma );
  oSelf.oColunaDataPrimeiroSintomaValor.appendChild( oSelf.oInputDataPrimeiroSintomaValor );
  oSelf.oColunaDataPrimeiroSintomaValor.appendChild( oSelf.oInputDataPrimeiroSintoma );
  oSelf.oColunaDataPrimeiroSintomaCampos.appendChild( oSelf.oInputDataPrimeiroSintomaDia );
  oSelf.oColunaDataPrimeiroSintomaCampos.appendChild( oSelf.oInputDataPrimeiroSintomaMes );
  oSelf.oColunaDataPrimeiroSintomaCampos.appendChild( oSelf.oInputDataPrimeiroSintomaAno );

  /**
   * Linha e colunas referentes as informações de gestante
   */
  oSelf.oLinhaGestante        = document.createElement( 'tr' );
  oSelf.oColunaGestanteLabel  = document.createElement( 'td' );
  oSelf.oColunaGestanteSelect = document.createElement( 'td' );

  /**
   * Label da gestante
   * @type {label}
   */
  oSelf.oLabelGestante           = document.createElement( 'label' );
  oSelf.oLabelGestante.addClassName( 'bold' );
  oSelf.oLabelGestante.setAttribute('for', 'oSelectGestante');
  oSelf.oLabelGestante.innerHTML = 'Gestante:';

  /**
   * Combo para informar se é gestante ou não
   * @type {select}
   */
  oSelf.oSelectGestante = document.createElement( 'select' );
  oSelf.oSelectGestante.addClassName( 'field-size-max' );
  oSelf.oSelectGestante.setAttribute( 'id', 'oSelectGestante' );
  oSelf.oSelectGestante.setAttribute( 'disabled', 'disabled' );
  oSelf.oSelectGestante.add( new Option( 'NÃO', 'f' ) );
  oSelf.oSelectGestante.add( new Option( 'SIM', 't' ) );

  /**
   * Vincula os elementos referente as informações de gestante
   */
  oSelf.oTabelaAgravo.appendChild( oSelf.oLinhaGestante );

  oSelf.oLinhaGestante.appendChild( oSelf.oColunaGestanteLabel );
  oSelf.oLinhaGestante.appendChild( oSelf.oColunaGestanteSelect );

  oSelf.oColunaGestanteLabel.appendChild( oSelf.oLabelGestante );
  oSelf.oColunaGestanteSelect.appendChild( oSelf.oSelectGestante );

  oSelf.montaElementosButton( aBotoesCriar );
};

/**
 * Bloqueia o campo do Cartão SUS
 * @param  {boolean} lBloqueiaCartaoSus
 */
DBViewTriagem.prototype.bloqueiaCartaoSus = function( lBloqueiaCartaoSus ) {

  if( lBloqueiaCartaoSus ) {

    this.oInputCartaoSUS.setAttribute( 'readOnly', 'readOnly');
    this.oInputCartaoSUS.setStyle( {'backgroundColor' : '#DEB887' } );
  }
};

/**
 * Método responsável por criar os botões de acordo com o array informado. Array é criado com base nos botões disponíveis
 * para cada tela
 */
DBViewTriagem.prototype.montaElementosButton = function( aBotoes ) {

  var oSelf = this;

  for( var iContador = 0; iContador < aBotoes.length; iContador++ ) {

    var sElemento = '';
    var sValor    = '';
    var fClick    = function(){};

    switch( aBotoes[iContador] ) {

      case DBViewTriagem.prototype.BOTAO_FATORES_RISCO:

        sElemento = 'oInputFatoresRisco';
        sValor    = 'Fatores de Risco';
        fClick    = function(){ oSelf.fatoresRisco(); };

        break;

      case DBViewTriagem.prototype.BOTAO_LIMPAR:

        sElemento = 'oInputLimpar';
        sValor    = 'Limpar';
        fClick    = function(){ oSelf.limpaCampos(); };

        break;

      case DBViewTriagem.prototype.BOTAO_CONSULTAR:

        sElemento = 'oInputConsultar';
        sValor    = 'Atendimentos';
        fClick    = function(){ oSelf.consultarFaa(); };

        break;

      case DBViewTriagem.prototype.BOTAO_SALVAR:

        sElemento = 'oInputSalvar';
        sValor    = 'Salvar';
        fClick    = function(){ oSelf.validaTriagem(); };

        break;

      case DBViewTriagem.prototype.BOTAO_FECHAR:

        sElemento = 'oInputFechar';
        sValor    = 'Fechar';
        fClick    = function(){ oSelf.fecharJanela(); };

        break;

      case DBViewTriagem.prototype.BOTAO_FINALIZAR_ATENDIMENTO:

        sElemento = 'oInputFinalizarAtendimento';
        sValor    = 'Finalizar Atendimento';
        fClick    = function(){ oSelf.finalizarAtendimento(); };

        break;

      case DBViewTriagem.prototype.BOTAO_ENCAMINHAR:

        sElemento = 'oInputEncaminhar';
        sValor    = 'Encaminhar';
        fClick    = function(){ oSelf.encaminharProntuario(); };
        break;

      case DBViewTriagem.prototype.BOTAO_ADMINISTRAR_MEDICAMENTOS:

        sElemento = 'oInputAdministrarMedicamentos';
        sValor    = 'Administrar Medicamentos';
        fClick    = function(){ oSelf.administrarMedicamentos(); };
        break;
    }

    oSelf.sElemento = document.createElement( 'input' );
    oSelf.sElemento.setAttribute( 'id', sElemento );
    oSelf.sElemento.setAttribute( 'type', 'button' );
    oSelf.sElemento.setAttribute( 'value', sValor );
    oSelf.sElemento.addClassName( 'botaoTriagem' );

    oSelf.oFormulario.appendChild( oSelf.sElemento );
    oSelf.sElemento.onclick = fClick;
  }
};

/**
 * Seta se deve ser exibido o botão Emitir FAA
 * @param {Boolean} lExibirBotaoEmitirFAA
 * @param {integer} iModelo
 */
DBViewTriagem.prototype.exibirBotaoEmitirFAA = function( lExibirBotaoEmitirFAA, iModelo ) {

  if ( lExibirBotaoEmitirFAA ) {

    var oSelf = this;

    this.oInputEmitirFAA = document.createElement( 'input' );
    this.oInputEmitirFAA.setAttribute( 'id', 'oInputEmitirFAA' );
    this.oInputEmitirFAA.setAttribute( 'type', 'button' );
    this.oInputEmitirFAA.setAttribute( 'value', 'Emitir FAA' );
    this.oInputEmitirFAA.addClassName( 'botaoTriagem' );

    if ( oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM ) {

      var oLinhaEmBranco = document.createElement('br');
      this.oFormulario.appendChild(oLinhaEmBranco);
    }
    this.oFormulario.appendChild( this.oInputEmitirFAA );

    this.oSelectModelosFAA = document.createElement( 'select' );
    this.oSelectModelosFAA.setAttribute( 'id', 'oSelectModelosFAA' );

    this.oSelectModelosFAA.add( new Option( 'Modelo 1 Padrão',       '1' ) );
    this.oSelectModelosFAA.add( new Option( 'Modelo 2 Continuada',   '2' ) );
    this.oSelectModelosFAA.add( new Option( 'Modelo 3',              '3' ) );
    this.oSelectModelosFAA.add( new Option( 'Modelo 4',              '4' ) );
    this.oSelectModelosFAA.add( new Option( 'Modelo 1 Com 1 via',    '5' ) );
    this.oSelectModelosFAA.add( new Option( 'Modelo TXT - Alegrete', '6' ) );
    this.oSelectModelosFAA.add( new Option( 'Modelo TXT - Bagé',     '7' ) );


    this.oFormulario.appendChild( this.oSelectModelosFAA );
    this.oSelectModelosFAA.value = iModelo;

    this.oInputEmitirFAA.onclick = function() {
      oSelf.emitirFAA();
    }
  }
};

/**
 * Contem os eventos existentes na tela
 */
DBViewTriagem.prototype.eventosElementos = function( oSelf ) {

  /**
   * No change do campo do peso, verifica se deve calcular o IMC
   */
  oSelf.oInputPeso.onchange = function() {
    oSelf.calculaImc();
  };

  /**
   * No change do campo do peso, verifica se deve calcular o IMC
   */
  oSelf.oInputAltura.onchange = function() {
    oSelf.calculaImc();
  };

  /**
   * Valida se o valor digitado para o peso é válido
   */
  oSelf.oInputPeso.onkeyup = function () {
    js_ValidaCampos(oSelf.oInputPeso, 4, "Peso", false, false, "event");
  };

  /**
   * Valida se o valor digitado para a temperatura é válido
   */
  oSelf.oInputTemperatura.onkeyup = function () {
    js_ValidaCampos(oSelf.oInputTemperatura, 4, "Temperatura", false, false, "event");
  };

  /**
   * Ao informar um cartão do SUS, verifica se é um número válido e vinculado a um CGS
   */
  oSelf.oInputCartaoSUS.onchange = function() {
    oSelf.buscaCns( oSelf );
  };

  /**
   * Valida se o valor digitado para Pressão Arterial é válido
   */
  oSelf.oInputSistolica.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputSistolica, 1, "Pressão Arterial", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para diastólica é válido
   */
  oSelf.oInputDiastolica.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputDiastolica, 1, "Pressão Arterial", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para cintura é válido
   */
  oSelf.oInputCintura.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputCintura, 1, "Cintura", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para altura é válido
   */
  oSelf.oInputAltura.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputAltura, 1, "Altura", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para perímetro cefálico é válido
   */
  oSelf.oInputPerimetroCefalico.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputPerimetroCefalico, 1, "Perímetro Cefálico", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para frequência respiratória é válido
   */
  oSelf.oInputFrequenciaRespiratoria.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputFrequenciaRespiratoria, 1, "Frequência Respiratória", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para frequência cardíaca é válido
   */
  oSelf.oInputFrequenciaCardiaca.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputFrequenciaCardiaca, 1, "Frequência Cardíaca", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para saturação é válido
   */
  oSelf.oInputSaturacao.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputSaturacao, 1, "Saturação de O2", false, false, "event" );
  };

  /**
   * Valida se o valor digitado para glicemia capilar é válido
   */
  oSelf.oInputGlicemiaCapilar.onkeyup = function() {
    js_ValidaCampos( oSelf.oInputGlicemiaCapilar, 1, "Glicemia Capilar", false, false, "event" );
  };
};

/**
 * Abre a janela para edição dos fatores de risco
 */
DBViewTriagem.prototype.fatoresRisco = function() {

  if( empty( $F('oInputCGSCodigo') ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'selecione_cgs' ) );
    return;
  }

  var sChave = 'chavepesquisacgs=' + $F('oInputCGSCodigo');

  js_OpenJanelaIframe('', 'db_iframe_fatoresderisco', 'sau4_consultamedica006.php?'+sChave, 'Fotores de Risco', true );
};

/**
 * Cria as instâncias de DBLookUp do CGS e Profissional
 */
DBViewTriagem.prototype.instanciaLookUps = function() {

  var oSelf = this;

  if( !oSelf.lTemProntuario ) {

    oSelf.oInputCGSCodigo.removeClassName( 'field-size2' );
    oSelf.oInputCGSDescricao.removeClassName( 'field-size8' );

    /**
     * Instancia a lookup de pesquisa para o CGS, configurando o callback da mesma
     */
    var fCallBackCGS   = function() { oSelf.buscaTriagemValida( false ); };
    var oParametrosCGS = { 'sArquivo' : 'func_cgs_und.php' };
    var oLookUpCGS     = new DBLookUp( oSelf.oAncoraCGS, oSelf.oInputCGSCodigo, oSelf.oInputCGSDescricao, oParametrosCGS );
        oLookUpCGS.setObjetoLookUp('db_iframe_cgs_und');
        oLookUpCGS.setCallBack( 'onClick', fCallBackCGS );
        oLookUpCGS.setCallBack( 'onChange', fCallBackCGS );
  } else {
    oSelf.desabilitaCGS();
  }

  /**
   * Caso o profissional logado não seja um profissional da saúde da unidade, habilita a lookup de pesquisa do
   * profissional
   * Caso contrário, bloqueio os campos da ancora e preenchimento do código
   */

    oSelf.oInputProfissionalCodigo.removeClassName( 'field-size2' );
    oSelf.oInputProfissionalDescricao.removeClassName( 'field-size8' );

    /**
     * Instancia a lookup de pesquisa do profissional
     */
    var fCallbackProfissional   = function() { oSelf.buscaDadosProfissional( $F('oInputProfissionalCodigo') ); };
    var sQueryString            = '&prof_ativo=1&chave_sd06_i_unidade=' + $F('oInputUnidadeCodigo');
        sQueryString           += '&campo_sd04_i_codigo=true';
    var oParametrosProfissional = { 'sArquivo' : 'func_medicos.php', 'sQueryString' : sQueryString };
    var oLookUpProfissional     = new DBLookUp(
      oSelf.oLabelProfissional,
      oSelf.oInputProfissionalCodigo,
      oSelf.oInputProfissionalDescricao,
      oParametrosProfissional
    );
    oLookUpProfissional.setCallBack( 'onClick', fCallbackProfissional );
    oLookUpProfissional.setCallBack( 'onChange', fCallbackProfissional );

    oSelf.oAncoraProfissional.setAttribute( 'id', 'oAncoraProfissional' );


  if( oSelf.lProfissionalSaude && oSelf.lTemProntuario ) {

    oSelf.oLinhaEspecialidade.setStyle( { 'display' : '' } );
  }

  oSelf.lInstanciouLookUp = true;
};

/**
 * Cria a instância de autocomplete para pesquisa do agravo
 */
DBViewTriagem.prototype.instanciaAutoComplete = function() {

  var oSelf = this;

  oSelf.oInputAgravoDescricao.onkeydown = '';
  oAutoComplete = new dbAutoComplete( oSelf.oInputAgravoDescricao, 'sau4_autocompleteagravo.RPC.php' );
  oAutoComplete.setTxtFieldId( $('oInputAgravoDescricao') );
  oAutoComplete.setHeightList( 300 );
  oAutoComplete.show();
  oAutoComplete.setCallBackFunction(function( cod, label ) {

    oSelf.iCid                       = cod;
    $('oInputAgravoDescricao').value = label;
  });
};

/**
 * Desabilita os campos do CGS quando acesso da triagem possuir prontuário
 */
DBViewTriagem.prototype.desabilitaCGS = function() {

  this.oAncoraCGS.removeAttribute( 'href' );
  this.oInputCGSCodigo.setAttribute( 'readOnly', 'readOnly' );
  this.oInputCGSCodigo.setStyle( { 'backgroundColor' : '#DEB887' } );
};

/**
 * Método responsável por calcular o IMC de acordo com o peso e altura informados.
 * Somente calcula quando ambos os campos estiverem preenchidos
 */
DBViewTriagem.prototype.calculaImc = function() {

  $('oInputIMCValor').value     = '';
  $('oInputIMCDescricao').value = '';

  if( $F('oInputPeso') != '' && $F('oInputAltura') != '' && $F('oInputAltura') != '0') {

    var nImc = parseFloat($F('oInputPeso')) / ((parseFloat($F('oInputAltura')) * parseFloat($F('oInputAltura'))) / 10000);

    $('oInputIMCValor').value = nImc.toString().substr(0, 5);

    if (nImc < 18.5) {
      $('oInputIMCDescricao').value = 'ABAIXO DO PESO';
    } else if (nImc < 25.0) {
      $('oInputIMCDescricao').value = 'PESO NORMAL';
    } else if (nImc < 30.0) {
      $('oInputIMCDescricao').value = 'ACIMA DO PESO';
    } else {
      $('oInputIMCDescricao').value = 'MUITO ACIMA DO PESO';
    }
  }
};

/**
 * Busca o código CGS e o Nome do CGS através do CNS informado
 * @param oSelf
 */
DBViewTriagem.prototype.buscaCns = function( oSelf ) {

  if ( $F('oInputCartaoSUS') == ''  ) {
    return;
  }

  if ( !$F('oInputCartaoSUS').validaCNS() ){

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'cartao_sus_invalido' ) );
    $('oInputCartaoSUS').value = '';
    return;
  }

  var oParametros  = new Object();
  oParametros.exec = "getCgsCns";
  oParametros.iCns = $F('oInputCartaoSUS');

  var oDadosRequisicao          = {};
  oDadosRequisicao.method       = 'post';
  oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
  oDadosRequisicao.asynchronous = false;
  oDadosRequisicao.onComplete   = function( oResponse ) {
    oSelf.retornoBuscaCns( oResponse, oSelf );
  };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_cns' ), "msgBoxA" );
  new Ajax.Request( oSelf.sRpcAmbulatorial, oDadosRequisicao );
};

/**
 * Verifica se existe o Cartão SUS informado possui algum CGS e busca os dados da triagem através do código do CGS
 * cadastrado
 * @param  oResponse [description]
 * @param  oSelf     [description]
 */
DBViewTriagem.prototype.retornoBuscaCns = function( oResponse, oSelf ) {

  js_removeObj("msgBoxA");

  var oRetorno = JSON.parse( oResponse.responseText );
  if ( oRetorno.z01_i_cgsund == '' ) {
    return;
  }

  oSelf.oInputCGSCodigo.value    = oRetorno.z01_i_cgsund;
  oSelf.oInputCGSDescricao.value = oRetorno.z01_v_nome.urlDecode();
  oSelf.buscaTriagemValida( true );
  oSelf.buscaAgravo();
};

/**
 * Busca os CBOS cadastrados
 */
DBViewTriagem.prototype.buscaCBOS = function() {

  var oSelf            = this;
  var oParametros      = {};
      oParametros.exec = 'buscaCBOS';

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaCBOS( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_cbos' ), "msgBox" );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Retorno dos CBOS encontrados. Seleciona por padrão, a opção ENFERMEIRO
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaCBOS = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );

  var oSelf    = this;
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status != 1 ) {
    alert( oRetorno.message.urlDecode() );
  }

  //Armazena o código do médico logado
  this.iMedico = oRetorno.iMedico;

  if( oRetorno.aCbos.length > 0 ) {

    oSelf.oSelectCBOS.options.length = 0;
    oRetorno.aCbos.each(function( oCbos, iSeq ) {

      oSelf.oSelectCBOS.add( new Option( oCbos.sCbos.urlDecode(), oCbos.iCbos ) );

      if( oCbos.sEstrutural.urlDecode() == '00000071' ) {
        oSelf.oSelectCBOS.options[iSeq].selected = true;
      }
    });
  }
};

/**
 * Busca o departamento logado
 */
DBViewTriagem.prototype.buscaDadosIniciais = function() {

  var oSelf            = this;
  var oParametros      = {};
      oParametros.exec = 'dadosDepartamento';

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaDadosIniciais( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_dados_iniciais' ), "msgBoxDadosIniciais" );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Retorna o departamento logado e preenche os dados da unidade
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaDadosIniciais = function( oResponse, oSelf ) {

  js_removeObj( "msgBoxDadosIniciais" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if ( !empty(oRetorno.iModelo) ) {
    oSelf.iModelo = oRetorno.iModelo
  }

  $('oInputUnidadeCodigo').value            = oRetorno.iDepartamento;
  $('oInputUnidadeDescricao').value         = oRetorno.sDepartamento.urlDecode();
  $('oInputDataConsultaValor').value        = oRetorno.dtAtual;
  $('oInputDataPrimeiroSintomaValor').value = oRetorno.dtAtual;

  if( empty( $F('oInputProfissionalCodigo') ) && !empty( oRetorno.iMedico ) ) {

    $('oInputProfissionalCodigo').value    = oRetorno.iMedico;
    $('oInputProfissionalDescricao').value = oRetorno.sMedico.urlDecode();
  }

  oSelf.dtAtual = oRetorno.dtAtual;

  oSelf.lProfissionalSaude = oRetorno.lProfissionalSaude;

  if ( oSelf.lProfissionalSaude ) {
    oSelf.buscaDadosProfissional( oRetorno.iMedico );
  }

  if( !oSelf.lInstanciouLookUp ) {
    oSelf.instanciaLookUps();
  }

  oSelf.instanciaAutoComplete();
};

/**
 * Busca os dados do profissional para envio ao salvar a triagem
 */
DBViewTriagem.prototype.buscaDadosProfissional = function( iMedico ) {

  var oSelf               = this;
  var oParametros         = {};
      oParametros.exec    = 'dadosProfissional';
      oParametros.iMedico = iMedico;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
                                                              oSelf.retornoBuscaDadosProfissional( oResponse, oSelf );
                                                            };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_dados_profissional' ), "msgBox" );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Retorno dos dados do profissional
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaDadosProfissional = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return;
  }

  $('oCboEspecialidade').options.length = 0;
  if( empty( oRetorno.iUnidadeMedicos ) ) {

    var iCgs       = $F('oInputCGSCodigo');
    var sCgs       = $F('oInputCGSDescricao');
    var sCartaoSus = $F('oInputCartaoSUS');

    oSelf.iUnidadeMedicos = '';
    oSelf.limpaCampos();
    oSelf.buscaDadosIniciais();

    $('oInputCGSCodigo').value    = iCgs;
    $('oInputCGSDescricao').value = sCgs;
    $('oInputCartaoSUS').value    = sCartaoSus;
    return;
  }

  oRetorno.aEspecialidades.each(function(oEspecialidade) {

    var oOption = new Option(oEspecialidade.descricao.urlDecode(), oEspecialidade.codigo);
    oOption.setAttribute("codigo_cbo", oEspecialidade.codigo_especialidade);
    $('oCboEspecialidade').add(oOption);
  });

  oSelf.iUnidadeMedicos = oRetorno.iUnidadeMedicos;

  if ( oRetorno.iCbos != '' ) {
    oSelf.oSelectCBOS.value = oRetorno.iCbos;
  }
};

/**
 * Verifica se o CGS selecionado já realizou a consulta
 */
DBViewTriagem.prototype.buscaTriagemValida = function( lEnviarCartaoSus ) {

  if( empty( $F('oInputCGSCodigo') ) ) {
    this.limpaCampos();
  }

  var oSelf                  = this;
  var oParametros            = {};
      oParametros.exec       = 'buscaTriagemValida';
      oParametros.iCgsUnd    = $F('oInputCGSCodigo');

  if (    oSelf.lTemProntuario
       && oSelf.iProntuario != null ) {
    oParametros.iProntuario = oSelf.iProntuario;
  }

  if( lEnviarCartaoSus ) {
    oParametros.iCartaoSus = $F('oInputCartaoSUS');
  }

  if ( oSelf.iTelaOrigem == oSelf.TELA_TRIAGEM_CONSULTA ) {
    oParametros.iTriagem = oSelf.iTriagem;
  }

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaTriagemValida( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_consulta_cgs' ), "msgBox" );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Retorno da verificação de consulta do CGS. Caso não tenha consultado, preenche os dados para atualização
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaTriagemValida = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return;
  }

  oSelf.oSelectGestante.setAttribute( 'value', 'f' );
  oSelf.oSelectGestante.setAttribute( 'disabled', 'disabled' );

  var iCgs = $F('oInputCGSCodigo');
  var sCgs = $F('oInputCGSDescricao');

  oSelf.limpaCampos();

  $('oInputCGSCodigo').value    = iCgs;
  $('oInputCGSDescricao').value = sCgs;
  $('oInputCartaoSUS').value    = oRetorno.sCartaoSus;
  oSelf.sSexo = oRetorno.sSexo;
  oSelf.oSelectGestante.setAttribute( 'disabled', 'disabled' );

  if( oSelf.sSexo == 'F' ) {

    oSelf.oSelectGestante.removeAttribute( 'disabled' );
    oSelf.oLinhaTabelaMulher.setStyle({'display': ''});
  }

  /**
   * Conforme a tela de origem, realiza as validações coerentes com cada para preenchimento dos dados da Triagem
   */
  switch( oSelf.iTelaOrigem ) {

    /**
     * Preenche os dados quando existir somente triagem para o CGS
     */
    case DBViewTriagem.prototype.TELA_TRIAGEM_AVULSA:

      if( oRetorno.lTemTriagem && oRetorno.lSomenteTriagem ) {

        oSelf.preencheTriagemValida( oRetorno, oSelf );
        oSelf.buscaPrioridadeAtendimento(oRetorno.iClassificacaoRisco);
      }

      break;

    /**
     * Preenche os dados quando a origem for uma agenda e não existir somente triagem.
     * Quando o acesso for do menu Triagem, libera a aba dos procedimentos
     */
    case DBViewTriagem.prototype.TELA_TRIAGEM:
    case DBViewTriagem.prototype.TELA_TRIAGEM_FICHA_ATENDIMENTO:

      oSelf.buscaPrioridadeAtendimento(oRetorno.iClassificacaoRisco);

      if(    ( oRetorno.lTemTriagem && !oRetorno.lSomenteTriagem )
          || ( oRetorno.lTemTriagem && oSelf.lOrigemAgenda == 'true' )
        ) {

        oSelf.preencheTriagemValida( oRetorno, oSelf );
      }

      if ( oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM ) {
        oSelf.liberaAbaProcedimentos();
      }

      break;

    /**
     * Preenche os dados da triagem vinculada ao CGS e prontuário selecionados, bloqueando todos os campos
     */
    case DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA:

      $('oInputFechar').disabled = false;

      if ( !oRetorno.lTemTriagem ) {

        alert( _M( MENSAGENS_DBVIEWTRIAGEM + "triagem_nao_lancada" ) );
        oSelf.fecharJanela();
        return;
      }

      oSelf.buscaPrioridadeAtendimento(oRetorno.iClassificacaoRisco);
      oSelf.preencheTriagemValida( oRetorno, oSelf );
      oSelf.buscaEspecialidade();

      break;
  }
};

/**
 * Preenche os campos referentes a triagem encontrada
 * @param oRetorno
 * @param oSelf
 */
DBViewTriagem.prototype.preencheTriagemValida = function( oRetorno, oSelf ) {

  oSelf.buscaCBOS();

  if ( oSelf.iTelaOrigem != DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA ) {

    oSelf.buscaDadosProfissional( this.iMedico );

    if( empty( oSelf.iUnidadeMedicos ) && oSelf.lOrigemAgenda == 'false' ) {
      return;
    }
  }

  $('oInputSistolica').value              = oRetorno.iPressaoSistolica;
  $('oInputDiastolica').value             = oRetorno.iPressaoDiastolica;
  $('oInputPeso').value                   = oRetorno.nPeso;
  $('oInputAltura').value                 = oRetorno.iAltura;
  $('oInputCintura').value                = oRetorno.iCintura;
  $('oInputTemperatura').value            = oRetorno.nTemperatura;
  $('oInputFrequenciaCardiaca').value     = oRetorno.iFrequenciaCardiaca;
  $('oInputFrequenciaRespiratoria').value = oRetorno.iFrequenciaRespiratoria;
  $('oInputSaturacao').value              = oRetorno.iSaturacao;
  $('oInputPerimetroCefalico').value      = oRetorno.iPerimetroCefalico;
  $('oInputGlicemiaCapilar').value        = oRetorno.iGlicemia;
  $('oCboMomentoColeta').value            = oRetorno.iMomentoColeta || oRetorno.iAlimentacaoExameGlicose;
  $('oInputDataConsultaValor').value      = js_formatar(oRetorno.dtDataConsulta.urlDecode(), 'd');
  $('oInputDataDUM').value                = js_formatar(oRetorno.dtDUM.urlDecode(), 'd');

  oSelf.iTriagem         = oRetorno.iCodigo;
  oSelf.iCboProfissional = oRetorno.iCboProfissional;

  if ( oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA) {

    $('oInputProfissionalCodigo').value    = oRetorno.iMedico;
    $('oInputProfissionalDescricao').value = oRetorno.sMedico.urlDecode();
  }

  $('oTextEvolucao').value = oRetorno.sEvolucao.urlDecode();

  oSelf.calculaImc();
  oSelf.buscaAgravo();
};

/**
 * Busca o agravo vinculado a uma triagem, caso tenha sido cadastrado
 */
DBViewTriagem.prototype.buscaAgravo = function() {

  var oSelf                      = this;
  var oParametros                = {};
      oParametros.exec           = "buscarAgravo";
      oParametros.iTriagemAvulsa = oSelf.iTriagem;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaAgravo( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + "buscando_agravo" ) , "msgBox");
  new Ajax.Request( oSelf.sRpcAgravo, oDadosRequisicao);
};

/**
 * Retorno do agravo vinculado a uma triagem
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaAgravo = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return;
  }

  if( oRetorno.lTemAgravo ) {

    oSelf.iAgravo = oRetorno.iTriagemAgravo;
    oSelf.iCid    = oRetorno.iCid;

    $('oInputAgravoDescricao').value          = oRetorno.sCid.urlDecode();
    $('oInputDataPrimeiroSintomaValor').value = oRetorno.dtSintoma.urlDecode();
    $('oSelectGestante').value                = oRetorno.lGestante.urlDecode();
  }
};

/**
 * Método para buscar a descrição do CGS através do código informado
 */
DBViewTriagem.prototype.buscaCGS = function() {

  var oSelf            = this;
  var oParametros      = {};
      oParametros.exec = 'buscaCgs';
      oParametros.iCgs = this.iCgs;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaCGS( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_cgs' ), 'msgBox' );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Adiciona o valor do código e do nome do CGS em seus campos na tela, e chama o método para buscar a Triagem.
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaCGS = function( oResponse, oSelf ) {

  js_removeObj( 'msgBox' );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.iCgs ) {

    $('oInputCGSCodigo').value    = oRetorno.iCgs;
    $('oInputCGSDescricao').value = oRetorno.sCgs.urlDecode();

    if(oRetorno.dtUltimaDUM != null) {
      $('ultimaDUM').innerHTML = oRetorno.dtUltimaDUM;
    }

    oSelf.buscaTriagemValida();
  }
};

/**
 * Busca todos os procedimentos cadastrados nos parâmetros da triagem
 */
DBViewTriagem.prototype.buscaProcedimentosTriagem = function() {

  var oSelf = this;

  var oParametros      = {};
      oParametros.exec = 'buscaProcedimentosTriagem';

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaProcedimentosTriagem( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_procedimentos_triagem' ), 'msgBoxB' );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Verifica se existe algum Procedimento cadastrado como parâmetro para a Triagem, os adiciona no array
 * aProcedimentosTriagem e chama o método para salvar este vínculo
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoBuscaProcedimentosTriagem = function( oResponse, oSelf ) {

  js_removeObj( 'msgBoxB' );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.aProcedimentosTriagem.length > 0 ) {

    oSelf.aProcedimentosTriagem = oRetorno.aProcedimentosTriagem;
    oSelf.salvarEspecialidadeProcedimentos();
  }
};

/**
 * Retorna as Prioridades de Atendimento cadastradas no banco
 * @param  {integer} iClassificacaoRisco
 */
DBViewTriagem.prototype.buscaPrioridadeAtendimento = function ( iClassificacaoRisco ) {

  var oSelf = this;

  var oParametros      = {};
      oParametros.exec = "buscaPrioridadesAtendimento";

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaPrioridadeAtendimento( oResponse, oSelf, iClassificacaoRisco );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_prioridades' ), 'msgBoxC' );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

/**
 * Monta as opções do combobox de prioridades de acordo com o retornado
 * @param  {Object} oResponse
 * @param  {Object} oSelf
 * @param  {integer} iClassificacaoRisco
 */
DBViewTriagem.prototype.retornoBuscaPrioridadeAtendimento = function( oResponse, oSelf, iClassificacaoRisco ) {

  js_removeObj( 'msgBoxC' );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status == 2 ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  oSelf.criaComboPrioridade();

  oRetorno.aClassificacoesRisco.each(function( oClassificacaoRisco ) {

    var oOpcaoClassificacaoRisco             = document.createElement( 'option' );
        oOpcaoClassificacaoRisco.style.color = oClassificacaoRisco.sCor;
        oOpcaoClassificacaoRisco.innerHTML   = oClassificacaoRisco.sDescricao.urlDecode();
        oOpcaoClassificacaoRisco.value       = oClassificacaoRisco.iCodigo;
        oOpcaoClassificacaoRisco.setAttribute( 'id', oClassificacaoRisco.iCodigo );
        oOpcaoClassificacaoRisco.setAttribute( 'cor', oClassificacaoRisco.sCor );


    oSelf.oCboPrioridade.add( oOpcaoClassificacaoRisco );

    if ( iClassificacaoRisco == oClassificacaoRisco.iCodigo ) {

      oOpcaoClassificacaoRisco.selected = true;
      oSelf.oCboPrioridade.style.color  = oClassificacaoRisco.sCor;
    }

  });

  oSelf.oCboPrioridade.onchange = function() {

    var oOption      = this.options[this.selectedIndex];
    this.style.color = oOption.getAttribute("cor");
  }
};

DBViewTriagem.prototype.validaTriagem = function() {

  var oSelf = this;

  if( !this.validaDadosTriagem() ) {
    return;
  }

  if (  oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM && !empty(this.iTriagem) ) {

    var oOpcoesSalvar = new DBViewOpcoesSalvar();
    oOpcoesSalvar.show();

    var fCallbackOpcoes = function() {

      switch ( oOpcoesSalvar.iOpcao ) {

        case 1:

          oSelf.iTriagem                         = null;
          oSelf.lIncluirVinculoTriagemProntuario = true;
          oSelf.salvarTriagem();
          break;

        case 2:
          oSelf.lIncluirVinculoTriagemProntuario = false;
          oSelf.salvarTriagem();
          break;

        default:
          return;
          break;
      }
    };

    oOpcoesSalvar.setCallbackOpcoes( fCallbackOpcoes );
    return;
  }

  if ( !empty(this.iTriagem) ) {
    oSelf.lIncluirVinculoTriagemProntuario = false;
  }

  oSelf.salvarTriagem();
}

/**
 * Salva a triagem, caso todos os campos tenham sido validados
 */
DBViewTriagem.prototype.salvarTriagem = function() {

  var oSelf                                = this;
  var oParametros                          = {};
      oParametros.exec                     = 'salvarTriagem';

  oParametros.iProntuario     = this.iProntuario;
  oParametros.iTriagem        = this.iTriagem;
  oParametros.iCgsUnd         = $F('oInputCGSCodigo');
  oParametros.iProfissional   = $F('oInputProfissionalCodigo');
  oParametros.iUnidadeMedicos = this.iUnidadeMedicos;
  oParametros.iCbos           = $F('oSelectCBOS');
  oParametros.dtDataConsulta  = $F('oInputDataConsultaValor');

  oParametros.iCintura           = $F('oInputCintura');
  oParametros.nPeso              = $F('oInputPeso');
  oParametros.iAltura            = $F('oInputAltura');
  oParametros.iPerimetroCefalico = $F('oInputPerimetroCefalico');

  oParametros.iPressaoSistolica       = $F('oInputSistolica');
  oParametros.iPressaoDiastolica      = $F('oInputDiastolica');
  oParametros.nTemperatura            = $F('oInputTemperatura');
  oParametros.iFrequenciaRespiratoria = $F('oInputFrequenciaRespiratoria');
  oParametros.iFrequenciaCardiaca     = $F('oInputFrequenciaCardiaca');
  oParametros.iSaturacao              = $F('oInputSaturacao');

  oParametros.iGlicemia      = $F('oInputGlicemiaCapilar');
  oParametros.iMomentoColeta = $F('oCboMomentoColeta');

  oParametros.dtDUM         = $F('oInputDataDUM');
  oParametros.iPrioridade   = $F('oCboPrioridade');
  oParametros.sTextEvolucao = encodeURIComponent(tagString($F('oTextEvolucao')));

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoSalvarTriagem( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + "salvando_triagem" ), "msgBox");
  new Ajax.Request( oSelf.sRpcTriagem, oDadosRequisicao);
};

/**
 * Retorno do salvar. Caso a propriedade this.iCid tenha sido preenchido, ou seja, um agravo foi informado, chama o
 * método responsável por salvar o agravo
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoSalvarTriagem = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return;
  }

  oSelf.iTriagem = oRetorno.iTriagemAvulsa;
  if( !empty( oSelf.iCid ) ) {
    oSelf.salvarAgravo();
  } else {

    alert( oRetorno.message.urlDecode() );

    if ( !oSelf.lTemProntuario ) {
      oSelf.limpaCampos();
    }
  }

  /**
   * Verifica se o usuário logado é um profissional da saúde e se existe um prontuário setado, salvando o vínculo entre
   * a Triagem e o Prontuário e buscando os procedimentos.
   * da triagem
   */
  if ( oSelf.lProfissionalSaude && !empty(oSelf.iProntuario) ) {

    if( oSelf.lIncluirVinculoTriagemProntuario ) {
      oSelf.salvarTriagemProntuario();
    }

    oSelf.buscaProcedimentosTriagem();
  }

  oSelf.liberaAbaProcedimentos();
};

/**
 * Salva as informações do agravo selecionado
 */
DBViewTriagem.prototype.salvarAgravo = function() {

  var oSelf                      = this;
  var oParametros                = {};
      oParametros.exec           = "salvarAgravo";
      oParametros.iTriagemAgravo = this.iAgravo;
      oParametros.iCid           = this.iCid;
      oParametros.iTriagemAvulsa = this.iTriagem;
      oParametros.dtSintoma      = $F('oInputDataPrimeiroSintomaValor');
      oParametros.lGestante      = $F('oSelectGestante');

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoSalvarAgravo( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + "salvando_agravo" ) , "msgBox");
  new Ajax.Request( oSelf.sRpcAgravo, oDadosRequisicao );
};

/**
 * Retorno do salvar agravo
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoSalvarAgravo = function( oResponse, oSelf ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return;
  }

  alert( _M( MENSAGENS_DBVIEWTRIAGEM + "triagem_salva" ) );

  if ( !oSelf.lTemProntuario ) {
    oSelf.limpaCampos();
  }
};

/**
 * Salva o vínculo de Prontuário com todos os Procedimentos cadatrados nos parâmetros da Triagem na tabela prontproced
 */
DBViewTriagem.prototype.salvarEspecialidadeProcedimentos = function () {

  var oSelf = this;

  var oParametros                       = {};
      oParametros.exec                  = 'salvarEspecialidadeProcedimentos';
      oParametros.iEspecialidade        = $F('oCboEspecialidade');
      oParametros.aProcedimentosTriagem = this.aProcedimentosTriagem;
      oParametros.iProntuario           = this.iProntuario;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoSalvarEspecialidadeProcedimentos( oResponse, oSelf );
      };

  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

DBViewTriagem.prototype.retornoSalvarEspecialidadeProcedimentos = function( oResponse, oSelf ) {
  var oRetorno = JSON.parse( oResponse.responseText );
};

/**
 * Salva o vínculo entre a Triagem e o prontuário na tabela sau_triagemavulsaprontuario
 */
DBViewTriagem.prototype.salvarTriagemProntuario = function() {

  var oSelf = this;

  var oParametros             = {};
      oParametros.exec        = 'salvarTriagemProntuario';
      oParametros.iTriagem    = this.iTriagem;
      oParametros.iProntuario = this.iProntuario;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoSalvarTriagemProntuario( oResponse, oSelf );
      };

  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

DBViewTriagem.prototype.retornoSalvarTriagemProntuario = function( oResponse, oSelf ) {
  var oRetorno = JSON.parse( oResponse.responseText );
};

/**
 * Validações referentes aos dados da triagem antes de salvar os mesmos
 */
DBViewTriagem.prototype.validaDadosTriagem = function() {

  if( empty( $F('oInputCGSCodigo') ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'selecione_cgs' ) );
    $('oInputCGSCodigo').focus();
    return false;
  }

  var aPeso = $F('oInputPeso').split('.');
  if( aPeso.length == 2 ) {

    if( aPeso[1].length > 3 ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'peso_acima_casas_decimais' ) );
    return false;
    }
  }

  if( $F('oInputPeso') > 999.999 ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'peso_menor' ) );
    $('oInputPeso').focus();
    return false;
  }

  if( $F('oInputAltura') > 250 ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'altura_maior' ) );
    $('oInputAltura').focus();
    return false;
  }

  if( empty( $F('oInputProfissionalCodigo') ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'selecione_profissional' ) );
    $('oInputProfissionalCodigo').focus();
    return false;
  }

  if( empty( $F('oInputDataConsulta') ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'preencha_data_consulta' ) );
    $('oInputDataConsulta').focus();
    return false;
  }

  if( this.lTemProntuario && this.lProfissionalSaude && empty( $F('oCboEspecialidade') ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'preencha_disponibilidade' ) );
    $('oCboEspecialidade').focus();
    return false;
  }

  return true;
};

/**
 * Limpa e desabilita determinados campos
 */
DBViewTriagem.prototype.limpaCampos = function() {

  this.iTriagem         = null;
  this.iAgravo          = null;
  this.iCboProfissional = null;
  this.iCid             = null;
  this.sSexo            = 'M';

  $('oInputCartaoSUS').value         = '';
  $('oInputCGSCodigo').value         = '';
  $('oInputCGSDescricao').value      = '';
  $('oInputDataConsultaValor').value = this.dtAtual;

  $('oInputSistolica').value              = '';
  $('oInputDiastolica').value             = '';
  $('oInputTemperatura').value            = '';
  $('oInputFrequenciaRespiratoria').value = '';
  $('oInputFrequenciaCardiaca').value     = '';

  $('oInputCintura').value           = '';
  $('oInputPeso').value              = '';
  $('oInputAltura').value            = '';
  $('oInputPerimetroCefalico').value = '';

  $('oInputIMCValor').value        = '';
  $('oInputIMCDescricao').value    = '';
  $('oInputGlicemiaCapilar').value = '';
  $('oCboMomentoColeta').value     = '1';

  $('oTextEvolucao').value = '';

  $('oInputAgravoDescricao').value          = '';
  $('oInputDataPrimeiroSintomaValor').value = this.dtAtual;
  $('oSelectGestante').value                = 'f';

  this.oSelectGestante.setAttribute( 'disabled', 'disabled' );

  if( !this.lProfissionalSaude ) {

    $('oInputProfissionalCodigo').value     = '';
    $('oInputProfissionalDescricao').value  = '';
    $('oCboEspecialidade').value            = '';
    this.iUnidadeMedicos                    = null;
    this.buscaCBOS();
  }
};

/**
 * Busca qual o tipo(TXT ou PDF) de relatório deve ser impresso a FAA
 */
DBViewTriagem.prototype.emitirFAA = function() {

  var oSelf                         = this;
  var oParametros                   = {};
      oParametros.exec              = 'gerarFAATXT';
      oParametros.sChaveProntuarios = this.iProntuario;
      oParametros.iModelo           = $F('oSelectModelosFAA');

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
                                                            oSelf.retornoEmitirFAA( oResponse, oSelf );
                                                          };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'emitindo_faa' ), 'msgBox' );
  new Ajax.Request( this.sRpcAmbulatorial, oDadosRequisicao );
};

/**
 * Emite a FAA de acordo com o tipo retornado e o modelo selecionado
 * @param oResponse
 * @param oSelf
 */
DBViewTriagem.prototype.retornoEmitirFAA = function( oResponse, oSelf ) {

  js_removeObj( 'msgBox' );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMessage.urlDecode() );
    return;
  }

  switch( oRetorno.iTipo ) {

    case 1:

      var aModelos = new Array(
                                'sau2_emitirfaa002.php',
                                'sau2_emitirfaa003.php',
                                'sau2_fichaatend005.php',
                                'sau2_fichaatend006.php',
                                'sau2_emitirfaa004.php',
                                'sau2_emitirfaa005.php',
                                'sau2_emitirfaa006.php'
                              );


      var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
      var sChave            = '?chave_sd29_i_prontuario=' + oRetorno.sChaveProntuarios;
      var sArquivo          = aModelos[ $F('oSelectModelosFAA') - 1 ];

      window.open( sArquivo + sChave, "CNN_WindowName", strWindowFeatures );

      break;

    case 2:

      var iTop    = 20;
      var iLeft   = 5;
      var iHeight = screen.availHeight - 210;
      var iWidth  = screen.availWidth  - 35;
      var sChave  = 'sSessionNome=' + oRetorno.sSessionNome;

      js_OpenJanelaIframe ( '', 'db_iframe_visualizador', 'sau2_fichaatend002.php?' + sChave,
                            'Visualisador', true, iTop, iLeft, iWidth, iHeight
                          );

      break;
  }
};

/**
 * Abre janela de pesquisa, buscando todos os CGS que possuem FAA
 */
DBViewTriagem.prototype.consultarFaa = function() {

  oInstancia = this;

  js_OpenJanelaIframe(
                      '',
                      'db_iframe_triagem',
                      'func_triagem.php?lFiltrarMovimentados=true&funcao_js=' + 'parent.oInstancia.retornoConsultaFaa|sd24_i_codigo|sd24_i_numcgs',
                      'Pesquisa',
                      true
                   );
};

/**
 * Verifica os dados retornados da pesquisa e busca o CGS, a Triagem e o Agravo
 * @param  {int} iFaa
 * @param  {int} iCgs
 */
DBViewTriagem.prototype.retornoConsultaFaa = function ( iFaa, iCgs ) {

  var oSelf         = this;
  oSelf.iCgs        = iCgs;
  oSelf.iProntuario = iFaa;


  db_iframe_triagem.hide();
  oSelf.buscaCGS();
  oSelf.buscaUltimaObservacaoDaMovimentacao();
  delete oInstancia;
};

/**
 * Fecha a janela de consulta da triagem
 */
DBViewTriagem.prototype.fecharJanela = function () {
  parent.db_iframe_triagemavulsa.hide();
};

DBViewTriagem.prototype.buscaEspecialidade = function () {

  var oSelf = this;

  var oParametros             = {};
      oParametros.exec        = 'buscaEspecialidade';
      oParametros.iProntuario = this.iProntuario;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete   = function( oResponse ) {
        oSelf.retornoBuscaEspecialidade( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'buscando_especialidade' ), 'msgBox' );
  new Ajax.Request( this.sRpcTriagem, oDadosRequisicao );
};

DBViewTriagem.prototype.retornoBuscaEspecialidade = function ( oResponse, oSelf ) {

  js_removeObj("msgBox");
  var oRetorno = JSON.parse( oResponse.responseText );

  if ( oRetorno.iEspecialidade != null ) {
    $('oCboEspecialidade').value = oRetorno.iEspecialidade;
  }
};

/**
 * Realiza a chamada do componente DBViewMotivosAlta para finalizar o atendimento selecionado
 * Ao instanciar a View, desabilita os botões do HTML para que não seja executada nenhuma ação até o fechamento da View
 */
DBViewTriagem.prototype.finalizarAtendimento = function() {

  if( empty( this.iProntuario ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'selecione_prontuario_finalizar' ) );
    return false;
  }

  var oSelf = this;

  var fCallbackSalvar = function() {

    oSelf.limpaCampos();

    if ( oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM) {

      oSelf.consultarFaa();
      return;
    }


    parent.document.formaba.a2.disabled = true;
    parent.document.formaba.a3.disabled = true;
    parent.document.formaba.a4.disabled = true;
    parent.iframe_a1.location.href = "sau4_fichaatendabas001.php";
    parent.mo_camada('a1');
  };

  var oMotivoAlta = new DBViewMotivosAlta();
      oMotivoAlta.setProntuario( this.iProntuario );
      oMotivoAlta.setCallbackSalvar( fCallbackSalvar );
      oMotivoAlta.show();
};

/**
 * Realiza a movimentação do prontuário entre os setores existentes( RECEPÇÃO, TRIAGEM, CONSULTA MÉDICA E EXTERNO)
 */
DBViewTriagem.prototype.encaminharProntuario = function() {

  if( empty( this.iProntuario ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'selecione_prontuario_encaminhar' ) );
    return false;
  }

  var oSelf = this;

  var fCallbackSalvar = function() {

    oSelf.limpaCampos();
    parent.document.formaba.a2.disabled = true;
    location.href                       = "sau4_sau_triagemavulsanovo001.php";
  }

  var oEncaminhar = new DBViewEncaminhamento( DBViewEncaminhamento.TRIAGEM, this.iProntuario);
      oEncaminhar.setCallbackSalvar( fCallbackSalvar );
      oEncaminhar.show();
};

DBViewTriagem.prototype.administrarMedicamentos = function() {

  var oSelf = this;

  if( empty( oSelf.iProntuario ) ) {

    alert( _M( MENSAGENS_DBVIEWTRIAGEM + 'selecione_prontuario_administrar_medicamentos' ) );
    return false;
  }

  $('oInputSalvar').disabled                  = 'disabled';
  $('oInputFinalizarAtendimento').disabled    = 'disabled';
  $('oInputAdministrarMedicamentos').disabled = 'disabled';
  $('oInputEmitirFAA').disabled               = 'disabled';

  if ( oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM ) {

    $('oInputConsultar').disabled               = 'disabled';
    $('oInputEncaminhar').disabled              = 'disabled';
  }

  var oAdministracaoMedicamento = new DBViewAdministracaoMedicamento( oSelf.iProntuario );
      oAdministracaoMedicamento.setCallbackFechar( function(){

        $('oInputSalvar').removeAttribute('disabled');
        $('oInputFinalizarAtendimento').removeAttribute('disabled');
        $('oInputAdministrarMedicamentos').removeAttribute('disabled');
        $('oInputEmitirFAA').removeAttribute('disabled');

        if ( oSelf.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM ) {
          $('oInputConsultar').removeAttribute('disabled');
          $('oInputEncaminhar').removeAttribute('disabled');
        }
      } );

      oAdministracaoMedicamento.show();
};

/**
 * Cria o combo da prioridade básico, somente com a opção Selecione
 */
DBViewTriagem.prototype.criaComboPrioridade = function() {

  this.oCboPrioridade.length      = 0;
  this.oCboPrioridade.style.color = '';

  this.oOpcaoPrioridade             = document.createElement('option');
  this.oOpcaoPrioridade.innerHTML   = 'Selecione'
  this.oOpcaoPrioridade.style.color = '#000000';
  this.oOpcaoPrioridade.value       = '';
  this.oOpcaoPrioridade.setAttribute('cor', '#000000');
  this.oCboPrioridade.add( this.oOpcaoPrioridade );
};

/**
 * Define o valor de prontuário
 * @param {int} iProntuario
 */
DBViewTriagem.prototype.setProntuario = function( iProntuario ) {
  this.iProntuario = iProntuario;
};

/**
 * Define qual tela devemos apresentar
 * @param  {boolean} lTemProntuario
 */
DBViewTriagem.prototype.temProntuario = function( lTemProntuario ) {
  this.lTemProntuario = lTemProntuario;
};

/**
 * Define o código do CGS
 * @param {int} iCgs
 */
DBViewTriagem.prototype.setCgs = function( iCgs ) {
  this.iCgs = iCgs;
};

/**
 * Define o código do agendamento para saber qual triagem deve ser listada
 * @param {int} iAgendamento
 */
DBViewTriagem.prototype.setAgendamento = function( iAgendamento ) {
  this.iAgendamento = iAgendamento;
};

/**
 * Define se o CGS esta entrando na tela apartir de um agendamento ou não
 * @param  {boolean} lOrigemAgenda
 */
DBViewTriagem.prototype.origemAgenda = function( lOrigemAgenda ) {
  this.lOrigemAgenda = lOrigemAgenda;
};

/**
 * Controla para liberar a ABA de procedimentos após selecionar uma FAA que possua triagem lançada ou após salvar uma
 * triagem, quando a tela selecionada for a de Procedimentos > Triagem
 */
DBViewTriagem.prototype.liberaAbaProcedimentos = function () {

  if ( this.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM ) {

    parent.document.formaba.a2.disabled = true;

    if ( this.iProntuario != null && this.iTriagem != null ) {

      var iEspecialidade = $F('oCboEspecialidade');
      var iProfissional  = $F('oInputProfissionalCodigo');
      var sProfissional  = $F('oInputProfissionalDescricao');
      var iCodigoCbo     = $('oCboEspecialidade').options[$('oCboEspecialidade').selectedIndex].getAttribute("codigo_cbo");
      parent.document.formaba.a2.disabled = false;
      var sUrl ='sau4_triagemproc001.php?chavepesquisaprontuario=' + this.iProntuario +"&iEspecialidade="+iEspecialidade;
      sUrl    +='&iProfissional='+ iProfissional+'&sProfissional='+sProfissional +'&iCbo='+iCodigoCbo;
      parent.iframe_a2.location.href  = sUrl;
    }
  }

  if ( this.iTelaOrigem == DBViewTriagem.prototype.TELA_TRIAGEM_FICHA_ATENDIMENTO ) {

    if ( this.iProntuario != null && this.iTriagem != null ) {

      var iCgs = $F('oInputCGSCodigo');
      var sUrl = 'sau4_fichaatendabas003.php?chavepesquisaprontuario=' + this.iProntuario +"&cgs="+iCgs+"&lOrigemFicha=true";
      parent.iframe_a3.location.href = sUrl;
    }
  }

};

/**
 * Busca a última observação lançada na movimentação e caso seja do setor, mostra ela em um alert
 */
DBViewTriagem.prototype.buscaUltimaObservacaoDaMovimentacao = function() {

  var oSelf = this;

  var oParametros             = {};
      oParametros.sExecucao   = "buscaUltimaObservacaoDaMovimentacao";
      oParametros.iProntuario = this.iProntuario;
      oParametros.iTelaOrigem = DBViewEncaminhamento.TRIAGEM;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete = function( oResponse ) {
        oSelf.retornoBuscaUltimaObservacaoDaMovimentacao( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWTRIAGEM + 'verificando_observacao'), "msgBoxObservacao" );
  new Ajax.Request( this.sRpcFichaAtendimento, oDadosRequisicao );
};

DBViewTriagem.prototype.retornoBuscaUltimaObservacaoDaMovimentacao = function ( oResponse, oSelf ) {

  js_removeObj('msgBoxObservacao');

  var oRetorno = JSON.parse( oResponse.responseText );

  if ( oRetorno.iStatus == 2 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  if (  oRetorno.sObservacao.urlDecode() != '' ) {
    alert( oRetorno.sObservacao.urlDecode() );
  }
};

/**
 * Seta se os campos do formulário devem ser bloqueados
 * @param {boolean} lBloqueiaFormulario
 */
DBViewTriagem.prototype.bloqueiaFormulario = function( lBloqueiaFormulario ) {
  setFormReadOnly( this.oFormulario, lBloqueiaFormulario );
};

/**
 * Inicializa o componente, montando a tela
 * @param oElemento
 */
DBViewTriagem.prototype.show = function( oElemento ) {

  oElemento.appendChild( this.oFormulario );
  this.buscaCBOS();
  this.buscaDadosIniciais();
};
