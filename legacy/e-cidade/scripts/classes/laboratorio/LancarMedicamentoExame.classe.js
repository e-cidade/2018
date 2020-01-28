/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


const MSG_LANCARMEDICAMENTOEXAME = 'saude.laboratorio.LancarMedicamentoExame.';

/**
 * Classe responsável pelo lançamento de medicamentos aos exames de uma requisição
 * @param {integer} iExameRequisicao Código do vinculo do exame a requisição
 *
 * @author  Andrio Costa       <andrio.costa@dbseller.com.br>
 * @author  Henrique Schreiner <henrique.schreiner@dbseller.com.br>
 * @version $Revision: 1.4 $
 */
LancarMedicamentoExame = function (sNome, iExameRequisicao) {

  /**
   * Vinculo do exame na requisicao
   * @type {integer}
   */
  this.iExameRequisicao = iExameRequisicao;

  /**
   * Array com os medicamentos lançados/utilizado no exame
   * @type {Array}
   */
  this.aMedicamentosLancados = [];

  /**
   * RCP para requisições
   * @type {String}
   */
  this.sRpc = 'lab4_digitacaoexame.RPC.php';

  /**
   * Elementos da interface da view
   */
  this.oBtnAdicionar = new Element('input', {type:'button', name:'adicionar', value:'Adicionar', id:'btnAdicionar'});
  this.oBtnSalvar    = new Element('input', {type:'button', name:'salvar',    value:'Salvar',    id:'btnSalvar'});
  this.oBtnFechar    = new Element('input', {type:'button', name:'fechar',    value:'Fechar',    id:'btnFechar'});

  this.oBtnFechar.style.marginLeft = '5px';

  this.oAncora            = new Element('a', {href:'javascript:void(0)'}).update('Medicamentos:');
  this.oAncora.className += " DBAncora bold ";


  this.oInputCodigo      = new Element('input', {type:'hidden', name:'la43_sequencial',  id:'codigoMedicamento'});
  this.oInputAbreviatura = new Element('input', {type:'text',   name:'la43_abreviatura', id:'abreviaturaMedicamento'});
  this.oInputAbreviatura.setAttribute('lang', 'la43_abreviatura');
  this.oInputAbreviatura.style.textTransform = 'uppercase';
  this.oInputNome        = new Element('input', {type:'text',   name:'la43_nome', id:'nomeMedicamento', disabled:'disabled'});
  this.oInputNome.setAttribute('lang', 'la43_nome');

  this.oInputNome.className += "field-size8 readonly";

  // cria a tabela que contem a acora
  this.oTable = document.createElement('table');
  var oRow    = this.oTable.insertRow(0);
  oRow.insertCell(0).appendChild(this.oAncora);
  var oCell = oRow.insertCell(1);
  oCell.appendChild(this.oInputCodigo);
  oCell.appendChild(this.oInputAbreviatura);
  oCell.appendChild(this.oInputNome);
  oRow.insertCell(2).appendChild(this.oBtnAdicionar);

  var aHeadersGrid       = ["Abreviatura", "Nome", "Ação", "codigo"];
  var aCellWidthGrid     = ["15%", "72%", "13%", "0%"];
  var aCellAlign         = ["center", "left", "center"];
  this.oGrigMedicamentos = new DBGrid('Medicamentos');

  this.oGrigMedicamentos.nameInstance = 'oGrigMedicamentos';
  this.oGrigMedicamentos.setCellWidth(aCellWidthGrid);
  this.oGrigMedicamentos.setCellAlign(aCellAlign);
  this.oGrigMedicamentos.setHeader(aHeadersGrid);
  this.oGrigMedicamentos.setHeight(130);
  this.oGrigMedicamentos.aHeaders[3].lDisplayed = false;

  this.oWindow = null;
};

/**
 * Adiciona as funcionalidades de uma "função de pesquisa" aos inputs
 */
LancarMedicamentoExame.prototype.adicionaLookUp = function() {

  var oParametros = {
    "sArquivo"              : 'func_medicamentoslaboratorio.php',
    "sLabel"                : "Pesquisa Medicamentos",
    "sObjetoLookUp"         : 'db_iframe_medicamentoslaboratorio',
    "aCamposAdicionais"     : ['la43_sequencial', 'la43_nome', 'la43_abreviatura'],
    "zIndex"                : '1000'
  };
  this.oDBLookUp =  new DBLookUp( this.oAncora, this.oInputAbreviatura, this.oInputNome, oParametros);

  var oSelf = this;

  this.oDBLookUp.callBackClick = function(sAbreviatura, sNome, iCodigo) {

    eval(this.oParametros.sObjetoLookUp).hide();
    if ( empty(iCodigo) ) {
      return;
    }

    $('codigoMedicamento').value      = iCodigo;
    $('abreviaturaMedicamento').value = sAbreviatura;
    $('nomeMedicamento').value        = sNome;

  };

  this.oDBLookUp.callBackChange = function(lErro, sNome, sAbreviatura, iCodigo  ) {

    $('codigoMedicamento').value      = "";
    $('abreviaturaMedicamento').value = "";

    $('nomeMedicamento').value        = sNome;
    if ( lErro ) {
      return;
    }

    $('codigoMedicamento').value      = iCodigo;
    $('abreviaturaMedicamento').value = sAbreviatura;
  };

};

/**
 * Adiciona um medicamento na lista de medicamentos
 */
LancarMedicamentoExame.prototype.adicionarMedicamento = function () {

  var oDadosMedicamento = {

    iCodigo      : $F('codigoMedicamento'),
    sNome        : $F('nomeMedicamento'),
    sAbreviatura : $F('abreviaturaMedicamento')
  }

  var lErro = false;
  this.aMedicamentosLancados.each( function (oMedicamento) {

    if ( oMedicamento.iCodigo == oDadosMedicamento.iCodigo ) {

      alert( _M(MSG_LANCARMEDICAMENTOEXAME + "medicamento_ja_lancado") );
      lErro = true;
      throw $break;
    }
  });

  if ( lErro ) {
    return;
  }

  $('codigoMedicamento').value      = '';
  $('abreviaturaMedicamento').value = '';
  $('nomeMedicamento').value        = '';
  this.aMedicamentosLancados.push(oDadosMedicamento);
  this.adicionarLinhasGrid();

};

/**
 * Adiciona as linhas dos medicamentos na Grid
 */
LancarMedicamentoExame.prototype.adicionarLinhasGrid = function() {

  var oSelf = this;
  this.oGrigMedicamentos.clearAll(true);
  this.aMedicamentosLancados.each( function (oMedicamento) {

    var oBtnRemover = new Element('input', {type:'button', name:'remover', value:'Remover', id:'btnRemover'+oMedicamento.iCodigo});
    oBtnRemover.setAttribute('codigo', oMedicamento.iCodigo);
    var aLinha = [];
    aLinha.push(oMedicamento.sAbreviatura);
    aLinha.push(oMedicamento.sNome);
    aLinha.push(oBtnRemover.outerHTML);
    aLinha.push(oMedicamento.iCodigo);

    oSelf.oGrigMedicamentos.addRow(aLinha);

  });
  this.oGrigMedicamentos.renderRows();

  this.aMedicamentosLancados.each( function (oMedicamento) {

    $('btnRemover'+oMedicamento.iCodigo).onclick = function() {
      oSelf.removerMedicamento(this);
    }
  });
};

LancarMedicamentoExame.prototype.removerMedicamento = function(oElement) {

  var oSelf   = this;
  var iCodigo = oElement.getAttribute('codigo');

  this.aMedicamentosLancados.each( function (oMedicamento, iIndex) {

    if (oMedicamento.iCodigo == iCodigo) {
      oSelf.aMedicamentosLancados.splice(iIndex, 1);
      throw $break;
    }
  });
  this.adicionarLinhasGrid();
};

LancarMedicamentoExame.prototype.salvar = function (){

  var oParam = {
    'exec'          : 'adicionarMedicamento',
    'iCodigoExame'  : this.iExameRequisicao,
    'aMedicamentos' : this.aMedicamentosLancados
  };

  var oAjax = new AjaxRequest(this.sRpc, oParam, function (oRetorno, lErro){

    alert(oRetorno.message.urlDecode())
    if ( lErro ) {
     return;
    }
  });
  oAjax.setMessage( _M(MSG_LANCARMEDICAMENTOEXAME + "salvando_medicamentos" ));
  oAjax.execute();
};

LancarMedicamentoExame.prototype.buscarMedicamentos = function (){

  var oSelf  = this;
  var oParam = {
    'exec'         : 'buscarMedicamentos',
    'iCodigoExame' : this.iExameRequisicao
  };

  var oAjax = new AjaxRequest(this.sRpc, oParam, function (oRetorno, lErro){
    oSelf.retornoBuscarMedicamentos(oRetorno, lErro);
  });
  oAjax.setMessage( _M(MSG_LANCARMEDICAMENTOEXAME + "buscando_medicamentos" ));
  oAjax.execute();
};

LancarMedicamentoExame.prototype.retornoBuscarMedicamentos = function (oRetorno, lErro){

  if (lErro) {
    alert(oRetorno.message.urlDecode());
    return;
  }

  oRetorno.aMedicamentos.each(function (oMedicamento){

    oMedicamento.sNome = oMedicamento.sNome.urlDecode();
    oMedicamento.sAbreviatura = oMedicamento.sAbreviatura.urlDecode();
  });

  this.aMedicamentosLancados = oRetorno.aMedicamentos;
  this.adicionarLinhasGrid();
};

LancarMedicamentoExame.prototype.show = function() {

  var oSelf = this;

  if ($('wndMedicamentoExame')) {
    return false;
  }

  this.oWindow = new windowAux('wndMedicamentoExame', 'Informar Medicamentos', 660, 400);
  this.oWindow.setShutDownFunction( function (){
    oSelf.oWindow.destroy();
  });
  var sConteudo  = "<div class='container' style='width:98%;'>";
      sConteudo += "  <fieldset>";
      sConteudo += "    <legend>Medicamentos</legend>";
      sConteudo += "    <div id='ctnAncoraMedicamentos'> </div>";
      sConteudo += "    <div style='width:98%;'id='ctnGrigMedicamentos'  > </div>";
      sConteudo += "  </fieldset>";
      sConteudo += "  <div id='ctnBtnsMedicamento'></div>";
      sConteudo += "</div>";

  this.oWindow.setContent(sConteudo);

  var sTitulo = _M(MSG_LANCARMEDICAMENTOEXAME + "titulo_help");
  var sHelp   = _M(MSG_LANCARMEDICAMENTOEXAME + "mensagem_help");
  new DBMessageBoard( 'ctnMsgBoard', sTitulo, sHelp, this.oWindow.getContentContainer());

  this.oWindow.show();
  $('ctnAncoraMedicamentos').appendChild(this.oTable);
  this.oGrigMedicamentos.show($('ctnGrigMedicamentos'));
  $('ctnBtnsMedicamento').appendChild(this.oBtnSalvar);
  $('ctnBtnsMedicamento').appendChild(this.oBtnFechar);

  this.adicionaLookUp();

  // Ação do botão adicionar
  this.oBtnAdicionar.onclick = function() {

    if ( empty($F('abreviaturaMedicamento')) || empty($F('codigoMedicamento')) ) {

      alert(_M(MSG_LANCARMEDICAMENTOEXAME + "selecione_medicamento") );
      return;
    }

    oSelf.adicionarMedicamento();
  };

  this.oInputAbreviatura.onfocus = function() {

    this.value                   = "";
    $('codigoMedicamento').value = "";
    $('nomeMedicamento').value   = "";

  }

  this.oBtnFechar.observe('click', function() {
    oSelf.oWindow.destroy();
  });

  this.oBtnSalvar.observe('click', function(){
    oSelf.salvar();
  });

  this.buscarMedicamentos();
};

LancarMedicamentoExame.prototype.setParentWindowAux = function (oWindowAux){

  this.oWindow.setChildOf(oWindowAux);
  $('wndMedicamentoExame').style.left = 65;
};