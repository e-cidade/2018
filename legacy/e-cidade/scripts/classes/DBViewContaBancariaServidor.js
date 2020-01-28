/**
 * @fileoverview Cria elementos para cadastro de conta bancaria
 * 
 * @author Vinicius Martins <vinicius@dbseller.com.br>
 * @version $Revision: 1.15 $
 * @revision $Author: dbluma $
 * 
 * @param integer
 *          iCodigoContaBancaria
 * @param string
 *          sInstance
 * @param boolean
 *          lReadOnly
 */
DBViewContaBancariaServidor = function(iCodigoContaBancaria, sInstance, lReadOnly) {

  var me = this;
  var sRPC = 'con1_contabancaria.RPC.php';
  me.instance = sInstance + ".";
  me.iSequencialContaBancaria = iCodigoContaBancaria;
  me.lContaPlano = false;
  me.lStatus = true;

  me.getDados = function(iCodigoContaBancaria) {


    var oParam = new Object();
    oParam.exec = "getDados";
    oParam.iCodigoContaBancaria = iCodigoContaBancaria;
    oParam.isContaPlano = me.lContaPlano;

    var oAjax = new Ajax.Request(

    sRPC, {
      method : 'post',
      asynchronous : false,
      parameters : 'json=' + Object.toJSON(oParam),
      onComplete : function(oAjax) {

        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == "2") {
          alert(oRetorno.message.urlDecode());
        } else {

          $('inputCodigoBanco').value = oRetorno.db89_db_bancos.trim();
          $('inputNomeBanco').value = oRetorno.db90_descr.trim();
          $('inputNumeroAgencia').value = oRetorno.db89_codagencia.trim();
          $('inputDvAgencia').value = oRetorno.db89_digito.trim();
          $('inputNumeroConta').value = oRetorno.db83_conta.trim();
          $('inputDvConta').value = oRetorno.db83_dvconta.trim();
          $('inputOperacao').value = oRetorno.db83_codigooperacao;
          $('cboTipoConta').value = oRetorno.db83_tipoconta;
          me.iSequencialContaBancaria = oRetorno.db83_sequencial;
          $('inputSequencialAgencia').value = oRetorno.db89_sequencial;
        }
      }
    });

  }
  /**
   * Labels dos Campos
   */
  if ( !lReadOnly ) {

    var sLblBanco   = "<B><a onclick='" + me.instance + "js_pesquisabanco(true);' ";
    sLblBanco      += " style ='text-decoration: underline;' ";
    sLblBanco      += " class ='dbancora' href='#'>Banco: </a></B> ";
  } else {
    var sLblBanco   = "<B>Banco: </B>";
  }
  var sLblAgencia   = "<B><a href='#' onclick='" + me.instance + "js_pesquisaAgencia(true);'>Agência:</a> </B>";
  var sLblDvAgencia = "<B>DV da Agência: </B>";
  var sLblConta     = "<B><a href='#' onclick='" + me.instance + "js_pesquisaNumeroConta(true);'>Conta:</a> </B>";
  var sLblDvConta   = "<B>DV da Conta: </B> ";
  var sLblOperacao  = "<B>Código da Operação: </B>";
  var sLblTipoConta = "<B>Tipo de Conta: </B>";

  /**
   * Criando HTML
   */
  var oFiedSetContaBancaria = document.createElement("FIELDSET");
  oFiedSetContaBancaria.style.width = "auto";

  var oLegendContaBancaria = document.createElement("LEGEND");
  oLegendContaBancaria.innerHTML = "<b>Dados da Conta Bancária</b>";

  var oTableContaBancaria = document.createElement("TABLE");
  oTableContaBancaria.border = "0";
  oTableContaBancaria.width = "100%";

  var oRowBanco = document.createElement("TR");
  var oCelulaLblCodigoBanco = document.createElement("TD");
  oCelulaLblCodigoBanco.style.width = "120px";
  oCelulaLblCodigoBanco.innerHTML = sLblBanco;

  var oCelulaCtnNomeBanco = document.createElement("TD");
  oCelulaCtnNomeBanco.colSpan = "3";
  oCelulaCtnNomeBanco.align = "left";
  oCelulaCtnNomeBanco.id = "CtnNomeBanco";
  var oSpanCodigo = document.createElement("SPAN");
  oSpanCodigo.id = "spanCodigo";
  oSpanCodigo.style.margin = "0 3px 0 0";
  var oSpanNome = document.createElement("SPAN");
  oSpanNome.id = "spanNome";

  oCelulaCtnNomeBanco.appendChild(oSpanCodigo);
  oCelulaCtnNomeBanco.appendChild(oSpanNome);
  oRowBanco.appendChild(oCelulaLblCodigoBanco);
  oRowBanco.appendChild(oCelulaCtnNomeBanco);

  var oRowAgencia = document.createElement("TR");
  var oCelulaLblCodigoAgencia = document.createElement("TD");
  oCelulaLblCodigoAgencia.innerHTML = sLblAgencia;

  var oCelulaCtnCodigoAgencia = document.createElement("TD");
  oCelulaCtnCodigoAgencia.id = "CtnCodigoAgencia";

  var oCelulaLblDvAgencia = document.createElement("TD");
  oCelulaLblDvAgencia.style.width = "130px";
  oCelulaLblDvAgencia.innerHTML = sLblDvAgencia;

  var oCelulaCtnDvAgencia   = document.createElement("TD");
  oCelulaCtnDvAgencia.id    = "CtnDvAgencia";

  oRowAgencia.appendChild(oCelulaLblCodigoAgencia);
  oRowAgencia.appendChild(oCelulaCtnCodigoAgencia);
  oRowAgencia.appendChild(oCelulaLblDvAgencia);
  oRowAgencia.appendChild(oCelulaCtnDvAgencia);

  var oRowConta = document.createElement("TR");
  var oCelulaLblCodigoConta = document.createElement("TD");
  oCelulaLblCodigoConta.innerHTML = sLblConta;

  var oCelulaCtnCodigoConta = document.createElement("TD");
  oCelulaCtnCodigoConta.id = "CtnCodigoConta";

  var oCelulaLblDvConta = document.createElement("TD");
  oCelulaLblDvConta.innerHTML = sLblDvConta;

  var oCelulaCtnDvConta = document.createElement("TD");
  oCelulaCtnDvConta.id = "CtnDvConta";

  oRowConta.appendChild(oCelulaLblCodigoConta);
  oRowConta.appendChild(oCelulaCtnCodigoConta);
  oRowConta.appendChild(oCelulaLblDvConta);
  oRowConta.appendChild(oCelulaCtnDvConta);

  var oRowOperacao_TipoConta = document.createElement("TR");
  var oCelulaLblOperacao = document.createElement("TD");
  oCelulaLblOperacao.innerHTML = sLblOperacao;

  var oCelulaCtnOperacao = document.createElement("TD");
  oCelulaCtnOperacao.id = "CtnOperacao";

  var oCelulaLblTipoConta = document.createElement("TD");
  oCelulaLblTipoConta.innerHTML = sLblTipoConta;

  var oCelulaCtnTipoConta = document.createElement("TD");
  oCelulaCtnTipoConta.id = "CtnTipoConta";

  oRowOperacao_TipoConta.appendChild(oCelulaLblOperacao);
  oRowOperacao_TipoConta.appendChild(oCelulaCtnOperacao);
  oRowOperacao_TipoConta.appendChild(oCelulaLblTipoConta);
  oRowOperacao_TipoConta.appendChild(oCelulaCtnTipoConta);

  oTableContaBancaria.appendChild(oRowBanco);
  oTableContaBancaria.appendChild(oRowAgencia);
  oTableContaBancaria.appendChild(oRowConta);
  oTableContaBancaria.appendChild(oRowOperacao_TipoConta);

  oFiedSetContaBancaria.appendChild(oLegendContaBancaria);
  oFiedSetContaBancaria.appendChild(oTableContaBancaria);

  /**
   * Elementos do formulario,
   */

  var inputSequencialAgencia = document.createElement("INPUT");
  inputSequencialAgencia.type = "hidden";
  inputSequencialAgencia.id = "inputSequencialAgencia";
  inputSequencialAgencia.name = "inputSequencialAgencia";

  var inputSequencialConta = document.createElement("INPUT");
  inputSequencialConta.type = "hidden";
  inputSequencialConta.id = "inputSequencialConta";
  inputSequencialConta.name = "inputSequencialConta";

  me.inputCodigoBanco   = new DBTextField('inputCodigoBanco', me.instance+ "inputCodigoBanco", '', 9);
  me.inputCodigoBanco.setMaxLength(10);
  me.inputCodigoBanco.addEvent("onChange", ";" + me.instance+ "js_pesquisabanco(false);");
  me.inputNomeBanco     = new DBTextField('inputNomeBanco', me.instance+ "inputNomeBanco", '', 41);
  me.inputNumeroAgencia = new DBTextField('inputNumeroAgencia', me.instance+ "inputNumeroAgencia", '', 9);
  me.inputNumeroAgencia.setMaxLength(5);
  me.inputNumeroAgencia.addEvent("onChange", ";" + me.instance+ "js_pesquisaAgencia(false);");
  me.inputDvAgencia     = new DBTextField('inputDvAgencia', me.instance+ "inputDvAgencia", '', 4);
  me.inputDvAgencia.setMaxLength(2);
  me.inputNumeroConta   = new DBTextField('inputNumeroConta', me.instance+ "inputNumeroConta", '', 9);
  me.inputNumeroConta.setMaxLength(15);
  me.inputDvConta       = new DBTextField('inputDvConta', me.instance+ "inputDvConta", '', 4);
  me.inputDvConta.setMaxLength(2);
  me.inputOperacao      = new DBTextField('inputOperacao', me.instance+ "inputOperacao", '', 9);
  me.inputOperacao.setMaxLength(4);

  me.inputNomeBanco.setReadOnly(true);
  
  var aTipoConta        = new Array();
  aTipoConta['0'] = 'Selecione';
  aTipoConta['1'] = 'Conta Corrente';
  aTipoConta['2'] = 'Conta Poupança';
  aTipoConta['4'] = 'Conta Salário';
  me.cboTipoConta = new DBComboBox('cboTipoConta',me.instance + "cboTipoConta", aTipoConta, '180');

  if (lReadOnly) {

    me.inputCodigoBanco.setReadOnly(true);
    me.inputNumeroAgencia.setReadOnly(true);
    me.inputDvAgencia.setReadOnly(true);
    me.inputNumeroConta.setReadOnly(true);
    me.inputDvConta.setReadOnly(true);
    me.inputOperacao.setReadOnly(true);
    me.cboTipoConta.setDisable(true);
  }

  /**
   * Renderiza o HTML
   */
  me.show = function(sLocal) {

    $(sLocal).appendChild(oFiedSetContaBancaria);
    $(sLocal).appendChild(inputSequencialAgencia);
    $(sLocal).appendChild(inputSequencialConta);

    me.inputCodigoBanco.show(document.getElementById('spanCodigo'));
    me.inputNomeBanco.show(document.getElementById('spanNome'));
    me.inputNumeroAgencia.show(document.getElementById('CtnCodigoAgencia'));
    me.inputDvAgencia.show(document.getElementById('CtnDvAgencia'));
    me.inputNumeroConta.show(document.getElementById('CtnCodigoConta'));
    me.inputDvConta.show(document.getElementById('CtnDvConta'));
    me.inputOperacao.show(document.getElementById('CtnOperacao'));
    me.cboTipoConta.show(document.getElementById('CtnTipoConta'));

  }

  this.js_pesquisabanco = function(mostra) {

    me.lStatus = false;

    if (mostra) {

      sUrlBanco = 'func_db_bancos.php?funcao_js=parent.' + me.instance + 'js_mostrabanco|db90_codban|db90_descr';
      js_OpenJanelaIframe('', 'db_iframe_db_bancos', sUrlBanco, 'Pesquisa de Bancos', true, '0');
      $('Jandb_iframe_db_bancos').style.zIndex = '100000';
    } else {

      sUrlBanco = 'func_db_bancos.php?pesquisa_chave='+me.inputCodigoBanco.getValue()+'&funcao_js=parent.' + me.instance + 'js_mostraBancoDescricao';
      js_OpenJanelaIframe('', 'db_iframe_db_bancos', sUrlBanco, 'Pesquisa de Bancos', false, '0');
    }
  }

  this.js_mostrabanco = function(chave1, chave2) {

    $('inputCodigoBanco').value = chave1;
    $('inputNomeBanco').value = chave2;
    this.js_limpaDadosSelecaoBanco();
    db_iframe_db_bancos.hide();
    me.lStatus = true;
  }

  this.js_mostraBancoDescricao = function(sDescricao, lErro){
    
    $('inputNomeBanco').value = sDescricao;

    if (lErro){
      $('inputCodigoBanco').value = '';
    }

    this.js_limpaDadosSelecaoBanco();
    me.lStatus = true;
  }

  this.setContaPlano = function(lContaPlano) {
    me.lContaPlano = lContaPlano;
  }
  this.getCodigosAgenciaBanco = function() {

    var oRetorno = new Object();
    oRetorno.iCodigoAgencia = $F('inputSequencialAgencia');
    oRetorno.iCodigoConta = $F('inputSequencialConta');
    return oRetorno;
  }
  
  this.js_limpaDadosSelecaoBanco = function() {
    
    me.inputNumeroAgencia.setValue('');
    me.inputDvAgencia.setValue('');
    this.js_limpaDadosSelecaoAgencia();
  }

  this.js_limpaDadosSelecaoAgencia = function () {
    
    me.inputNumeroConta.setValue('');
    me.inputDvConta.setValue('');
    me.inputOperacao.setValue('');
    me.cboTipoConta.setValue('');
  }
  
  this.salvar = function() {

    oParam = new Object();
    oParam.exec = 'salvarDados';
    oParam.oDados = new Object();
    oParam.oDados.inputNumeroConta = $('inputNumeroConta').value;
    oParam.oDados.inputDvConta = $('inputDvConta').value;
    oParam.oDados.inputDvAgencia = $('inputDvAgencia').value;
    oParam.oDados.inputNumeroAgencia = $('inputNumeroAgencia').value;
    oParam.oDados.inputOperacao = $('inputOperacao').value;
    oParam.oDados.cboTipoConta = $('cboTipoConta').value;
    oParam.oDados.iSequencialConta = $F('inputSequencialConta');
    oParam.oDados.iSequencialAgencia = $F('inputSequencialAgencia');
    oParam.oDados.inputCodigoBanco = $F('inputCodigoBanco');
    oParam.oDados.lContaPlano = me.lContaPlano;
    js_divCarregando('Salvando dados...', 'msgBox');
    var oAjax = new Ajax.Request("con1_contabancaria.RPC.php", {
      method : 'post',
      parameters : 'json=' + Object.toJSON(oParam),
      onComplete : function(oAjax) {

        js_removeObj('msgBox')
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

          me.iSequencialContaBancaria = oRetorno.iSequencialContaBancaria;
          me.afterSave(oRetorno);
        } else {
          alert(oRetorno.message.urlDecode());
        }
      }

    });
  };

  this.afterSave = function(oAjax) {

  }

  this.onAfterSave = function(sFunction) {
    this.afterSave = sFunction;
  }

  this.getDadosConta = function() {

    var sDadosConta = "Bco: " + $F('inputCodigoBanco');
    sDadosConta += " Ag: " + $F('inputNumeroAgencia') + "-"+ $F('inputDvAgencia');
    sDadosConta += " Cta:" + $F('inputNumeroConta') + "-" + $F('inputDvConta');
    return sDadosConta;
  }

  /**
   * Pesquisa os dados da agência
   */
  this.js_pesquisaAgencia = function(lMostrar) {

    var sBuscarPorCodigoBanco = "";
    if (me.inputCodigoBanco.getValue() != "") {
      sBuscarPorCodigoBanco = "db89_db_bancos=" + me.inputCodigoBanco.getValue() + "&";
    }
    
    var sUrlAgencia = "func_bancoagenciaconta.php?"+sBuscarPorCodigoBanco+"funcao_js=parent."+me.instance+"js_preencheAgencia|db89_codagencia|db89_digito|db89_sequencial";

    if ( !lMostrar ) {
      sUrlAgencia = "func_bancoagenciaconta.php?"+sBuscarPorCodigoBanco+"pesquisa_chave="+me.inputNumeroAgencia.getValue()+"&funcao_js=parent."+me.instance+"js_completaAgencia";
    }

    js_OpenJanelaIframe('', "db_iframe_bancoagencia", sUrlAgencia, "Pesquisa de Agências", lMostrar);
    $('Jandb_iframe_bancoagenciaconta').style.zIndex = '100000';
  }

  /**
   * Preenche os dados da agencia quando aberto a lookup de pesquisa
   */
  this.js_preencheAgencia = function(sCodigoAgencia, sDigito, iSequencialAgencia) {

    me.inputNumeroAgencia.setValue(sCodigoAgencia);
    me.inputDvAgencia.setValue(sDigito);
    $('inputSequencialAgencia').value = iSequencialAgencia;
    db_iframe_bancoagencia.hide();
    // this.js_limpaDadosSelecaoAgencia();
  }

  this.js_completaAgencia = function(lErro, sCodigoBanco, sDescricaoBanco, sAgencia, sDigito, iSequencial) {

    if (!lErro) {

      me.inputNumeroAgencia.setValue(sAgencia);
      me.inputDvAgencia.setValue(sDigito);
      $('inputSequencialAgencia').value = iSequencial;
    }
  }

  this.js_pesquisaNumeroConta = function(lMostra) {

    var sBuscarPorAgencia = "";
    if ($('inputSequencialAgencia').value != "") {
      sBuscarPorAgencia = "bancoagencia="+$('inputSequencialAgencia').value+"&";
    }
    
    var sUrlNumeroConta = "func_contabancaria.php?"+sBuscarPorAgencia+"chave_tipo_conta=2&funcao_js=parent."+me.instance+"js_preencheNumeroConta|db83_conta|db83_dvconta|db83_codigooperacao|db83_tipoconta|db83_bancoagencia|db83_sequencial";
    if (!lMostra) {
      sUrlNumeroConta = "func_contabancaria.php?"+sBuscarPorAgencia+"chave_tipo_conta=2&pesquisa_chave="+me.inputNumeroConta.getValue()+"&funcao_js=parent."+me.instance+"js_completaAgencia";
    }
    js_OpenJanelaIframe('', "db_iframe_contabancaria", sUrlNumeroConta, "Pesquisa de Conta", lMostra);
    $('Jandb_iframe_contabancaria').style.zIndex = '100000';
  }
  
  this.js_preencheNumeroConta = function (iCodigoConta, iDigitoConta, sCodigoOperacao, iTipoConta, iCodigoAgencia, iSequencial) {
    
    me.inputNumeroConta.setValue(iCodigoConta);
    me.inputDvConta.setValue(iDigitoConta);
    me.inputOperacao.setValue(sCodigoOperacao);
    me.cboTipoConta.setValue(iTipoConta);
    $('inputSequencialConta').value = iSequencial;
    db_iframe_contabancaria.hide();
    this.getDadosContaBancaria();
  }
  
  this.getDadosContaBancaria = function () {
    
    if ($F('inputSequencialConta') == "") {
      return false;
    }
    
    js_divCarregando("Aguarde, carregando dados...", "msgBox");
    
    var oParam          = new Object();
    oParam.exec         = "getDadosContaBancaria";
    oParam.iCodigoConta = $F('inputSequencialConta');
    
    var oAjax = new Ajax.Request("con1_contabancaria.RPC.php", {
                                 method : 'post',
                                 parameters : 'json=' + Object.toJSON(oParam),
                                 onComplete : function(oAjax) {
                                 
                                   js_removeObj('msgBox')
                                   var oRetorno = eval("(" + oAjax.responseText + ")");
                                   me.inputNumeroAgencia.setValue(oRetorno.iCodigoAgencia);
                                   me.inputDvAgencia.setValue(oRetorno.iDigitoAgencia);
                                   me.inputCodigoBanco.setValue(oRetorno.iCodigoBanco);
                                   me.inputNomeBanco.setValue(oRetorno.sDescricaoBanco);
                                   
                                 }

    });
  }
}
