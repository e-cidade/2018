require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
require_once("scripts/widgets/DBLancador.widget.js");
require_once("scripts/strings.js");
require_once("estilos/DadosAluno.css");
/**
 * Componente permite selecção de um aluno e mostra os dados atual do aluno como:
 *   Curso, Turma, Turno, Calendário, Data de matrícula, Situação
 *     
 * @autor   Andrio Costa - andrio.costa@dbseller.com.br
 * @package Educacao
 * @version $Revision: 1.6 $
 * @example Exemplo de utilização
 *   var oViewDadosAluno = new DBViewFormularioEducacao.DadosAluno();
 *   oViewDadosAluno.modoSeparador(false);
 *   oViewDadosAluno.setLegend();
 *   oViewDadosAluno.show($('ctnViewDadosAluno'));
 * @returns {DBViewFormularioEducacao.DadosAluno}
 *
 */
DBViewFormularioEducacao.DadosAluno = function() {

  this.sURLArquivoMsg = "educacao.escola.DadosAluno.";
  
  this.fCallBackRetornoAluno = function () {
    return true;
  };
  
  this.fCallBackLimpaDados = function () {
    return true;
  }
  
  /**
   * Elemento HTML fildset para agrupar o conteúdo do component
   * @var HTMLElement
   */
  this.oFieldset = new Element("fieldset", {'id':'DBViewDadosAluno'});
  
  /**
   * Elemento HTML de legenda
   * @var HTMLElement
   */
  this.oLegend = new Element("legend").update("Dados do Aluno");
  
  /**
   * Objeto com os dados do aluno
   * Propriedades do objeto: 
   *   codigo_aluno     
   *   nome_aluno       
   *   codigo_turma     
   *   descricao_turma  
   *   descricao_turno  
   *   codigo_curso
   *   codigo_base
   *   codigo_calendario
   *   nome_calendario
   *   ano_calendario  
   *   codigo_etapa     
   *   descricao_etapa  
   *   situacao_aluno   
   *   data_matricula   
   * @var Object{}
   */
  this.oDadosAluno = {};
  
  this.oFieldset.appendChild(this.oLegend);
  
  /**
   * Tabela dos dados do aluno
   */
  var oTableContainer = new Element("table");
  oTableContainer.insertRow(0).update("<tr><td class='bold field-size3'id='ctnAncoraAluno'></td><td id='ctnAlunoDados'></td></tr>");
  oTableContainer.insertRow(1).update("<tr><td class='bold field-size3'>Turma:</td><td class='tdStyleImputReadOnly' id='ctnTurmaDados'>&nbsp;</td></tr>");
  oTableContainer.insertRow(2).update("<tr><td class='bold field-size3'>Turno:</td><td class='tdStyleImputReadOnly' id='ctnTurnoDados'>&nbsp;</td></tr>");
  oTableContainer.insertRow(3).update("<tr><td class='bold field-size3'>Calendário:</td><td class='tdStyleImputReadOnly' id='ctnCalendarioDados'>&nbsp;</td></tr>");
  oTableContainer.insertRow(4).update("<tr><td class='bold field-size3'>Data Matrícula:</td><td class='tdStyleImputReadOnly' id='ctnDataMatriculaDados'>&nbsp;</td></tr>");
  oTableContainer.insertRow(5).update("<tr><td class='bold field-size3'>Situação:</td><td class='tdStyleImputReadOnly' id='ctnSituacaoDados'>&nbsp;</td></tr>");
  
  this.oFieldset.appendChild(oTableContainer);

  DBViewFormularioEducacao.DadosAluno.instance = this;
};

DBViewFormularioEducacao.DadosAluno.prototype.modoSeparador = function (lSeparator) {
  
  if (lSeparator) {
    this.oFieldset.addClassName("separator");
  }
};

/**
 * Redefine a legenda do fieldset
 */
DBViewFormularioEducacao.DadosAluno.prototype.setLegend = function (sLegend) {
  
  if ( sLegend != undefined && sLegend != "" ) {
    this.oLegend.update(sLegend);
  }
};


/**
 * Rederiza os dados
 * @param oElement Elemento onde será renderizado o componente
 */
DBViewFormularioEducacao.DadosAluno.prototype.show = function (oElement) {
  
  var oSelf = this;
  oElement.appendChild(this.oFieldset);
  this.oAlunoAncora = new DBAncora("Aluno:");
  this.oAlunoAncora.onClick(function() {
    oSelf.abrirLookUpAluno(true);
  });
  this.oAlunoAncora.show( $('ctnAncoraAluno') );
  
  $('ctnAlunoDados').update("<input type='text' id='codigoAluno' size='10' /> " +
  		                      "<input type='text' class='readonly' readonly='readonly' id='nomeAluno' />");
  
  $('codigoAluno').onchange = function () {
    oSelf.abrirLookUpAluno(false);
  };
  
  // calcula o tamanho do input nomeAluno
  $('nomeAluno').style.width = ($('ctnTurmaDados').clientWidth - $('codigoAluno').clientWidth) - 7 +"px" ;
  
};

/**
 * consulta a lookup de pesquisa e retorna os dados do aluno
 * @param lMostra Se deve exibir a função de pesquisa 
 */
DBViewFormularioEducacao.DadosAluno.prototype.abrirLookUpAluno = function (lMostra) {
  
  this.fCallBackLimpaDados();
  
  var oParametros = { sFontePesquisa   : "func_dadosaluno.php",
                      aCamposRetorno   : ["ed47_i_codigo", "ed47_v_nome"],
                      sStringAdicional : "situacao=MATRICULADO"
                    };
  var sIframe     = "db_iframe_dadosaluno"; 
  var sQuery  = oParametros.sFontePesquisa;

  sQuery += '?' + oParametros.sStringAdicional;
  sQuery += '&funcao_js=parent.DBViewFormularioEducacao.DadosAluno.getInstance().exibirAluno';
  if (lMostra) {
    
    sQuery += "|"+oParametros.aCamposRetorno.join("|");
    js_OpenJanelaIframe('', sIframe, sQuery, 'Pesquisa Aluno', true);
    return;
  }
  
  if ( $F('codigoAluno') == '' ) {
    
    $('nomeAluno').value = "";
    this.limpaCampos();
    return;     
  }
  
  sQuery += "&pesquisa_chave="+$F('codigoAluno');
  js_OpenJanelaIframe('', sIframe, sQuery, 'Pesquisa Aluno', false);
  return;
};

/**
 * Exibe retorno da pesquisa do aluno
 */
DBViewFormularioEducacao.DadosAluno.prototype.exibirAluno = function () {

  this.limpaCampos();
  $('codigoAluno').value = arguments[0]; 
  $('nomeAluno').value   = arguments[1];
  db_iframe_dadosaluno.hide();
  if ( !arguments[2] ) {
    this.pesquisaDadosAluno(arguments[0]);
  }
};

/**
 * Pesquisa os dados do aluno
 * @param iCodigoAluno
 */
DBViewFormularioEducacao.DadosAluno.prototype.pesquisaDadosAluno = function (iCodigoAluno) {

  var oSelf = this;
  var oParametros = {'exec' : 'buscaDadosAluno', 'iAluno':iCodigoAluno};
  
  var oRequest = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametros),
  oRequest.onComplete = function(oAjax) {
                          oSelf.retornoDadosAluno(oAjax);
                        };

  js_divCarregando( _M(this.sURLArquivoMsg + "aguarde_buscando_informacoes_aluno"), "msgBox");
  new Ajax.Request('edu4_aluno.RPC.php', oRequest); 
};

/**
 * Retorno do dados do aluno
 * @param oAjax
 */
DBViewFormularioEducacao.DadosAluno.prototype.retornoDadosAluno = function (oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("(" + oAjax.responseText + ")");
  
  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return;
  }

  this.oDadosAluno.codigo_aluno      = oRetorno.oDadosAluno.codigo_aluno; 
  this.oDadosAluno.nome_aluno        = oRetorno.oDadosAluno.nome_aluno.urlDecode(); 
  this.oDadosAluno.codigo_turma      = oRetorno.oDadosAluno.codigo_turma; 
  this.oDadosAluno.descricao_turma   = oRetorno.oDadosAluno.descricao_turma.urlDecode();
  this.oDadosAluno.descricao_turno   = oRetorno.oDadosAluno.descricao_turno.urlDecode();
  this.oDadosAluno.codigo_curso      = oRetorno.oDadosAluno.codigo_curso;
  this.oDadosAluno.codigo_base       = oRetorno.oDadosAluno.codigo_base;
  this.oDadosAluno.codigo_calendario = oRetorno.oDadosAluno.codigo_calendario; 
  this.oDadosAluno.nome_calendario   = oRetorno.oDadosAluno.nome_calendario.urlDecode(); 
  this.oDadosAluno.ano_calendario    = oRetorno.oDadosAluno.ano_calendario;
  this.oDadosAluno.codigo_etapa      = oRetorno.oDadosAluno.codigo_etapa; 
  this.oDadosAluno.descricao_etapa   = oRetorno.oDadosAluno.descricao_etapa.urlDecode(); 
  this.oDadosAluno.situacao_aluno    = oRetorno.oDadosAluno.situacao_aluno.urlDecode(); 
  this.oDadosAluno.data_matricula    = oRetorno.oDadosAluno.data_matricula;
  
  $('ctnTurmaDados').update(this.oDadosAluno.descricao_turma  + ' - ' +  this.oDadosAluno.descricao_etapa);
  $('ctnTurnoDados').update(this.oDadosAluno.descricao_turno);
  $('ctnCalendarioDados').update(this.oDadosAluno.nome_calendario);
  $('ctnDataMatriculaDados').update(this.oDadosAluno.data_matricula);
  $('ctnSituacaoDados').update(this.oDadosAluno.situacao_aluno);
  
  if ( this.oDadosAluno.codigo_aluno != '' ) {
    this.fCallBackRetornoAluno();
  }
  return;
};

DBViewFormularioEducacao.DadosAluno.prototype.limpaCampos = function () {
  
  $('ctnTurmaDados').update('&nbsp;');
  $('ctnTurnoDados').update('&nbsp;');
  $('ctnCalendarioDados').update('&nbsp;');
  $('ctnDataMatriculaDados').update('&nbsp;');
  $('ctnSituacaoDados').update('&nbsp;');
};

/**
 * Retorna a instancia de DadosAluno
 * @returns DBViewFormularioEducacao.DadosAluno 
 */
DBViewFormularioEducacao.DadosAluno.getInstance = function () {
  return DBViewFormularioEducacao.DadosAluno.instance;
};

/**
 * Retorna o código da etapa
 * @returns integer|NULL
 */
DBViewFormularioEducacao.DadosAluno.prototype.getCodigoEtapa = function () {
  
  if (this.oDadosAluno.codigo_etapa) {
    return this.oDadosAluno.codigo_etapa;
  }
  return null;
};

/**
 * Retorna o código da turma
 * @returns integer|NULL
 */
DBViewFormularioEducacao.DadosAluno.prototype.getCodigoTurma = function () {
  
  if (this.oDadosAluno.codigo_turma) {
    return this.oDadosAluno.codigo_turma;
  }
  return null;
};

/**
 * Retorna o código do aluno
 * @returns integer|NULL
 */
DBViewFormularioEducacao.DadosAluno.prototype.getCodigoAluno = function () {
  
  if (this.oDadosAluno.codigo_aluno) {
    return this.oDadosAluno.codigo_aluno;
  }
  return null;
};

/**
 * Retorna o código do cusro da turma onde o aluno esta matriculado
 * @returns integer|NULL
 */
DBViewFormularioEducacao.DadosAluno.prototype.getCodigoCurso = function () {
  
  if (this.oDadosAluno.codigo_curso) {
    return this.oDadosAluno.codigo_curso;
  }
  return null;
};

/**
 * Retorna o código da base curricular da turma onde o aluno esta matriculado
 * @returns integer|NULL
 */
DBViewFormularioEducacao.DadosAluno.prototype.getCodigoBaseCurricular = function () {
  
  if (this.oDadosAluno.codigo_base) {
    return this.oDadosAluno.codigo_base;
  }
  return null;
};

/**
 * Retorna o ano do calendário da atual matrícula do aluno
 * @returns integer|NULL
 */
DBViewFormularioEducacao.DadosAluno.prototype.getAnoCalendario = function () {
  
  if (this.oDadosAluno.ano_calendario) {
    return this.oDadosAluno.ano_calendario;
  }
  return null;
};


/**
 * Adiciona uma função para ser executada no retorno dos dados do aluno.
 * @param fFunction
 */
DBViewFormularioEducacao.DadosAluno.prototype.setCallBackRetornoAluno = function (fFunction) {

  if ( typeof fFunction == 'function' ) {
    
    this.fCallBackRetornoAluno = fFunction;
  } 
};

/**
 * Adiciona uma função para ser executada no retorno dos dados do aluno.
 * @param fFunction
 */
DBViewFormularioEducacao.DadosAluno.prototype.setCallBackLimpaDados = function (fFunction) {

  if ( typeof fFunction == 'function' ) {
    
    this.fCallBackLimpaDados = fFunction;
  } 
};