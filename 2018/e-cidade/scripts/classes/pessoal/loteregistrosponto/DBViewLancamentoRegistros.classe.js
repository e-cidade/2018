require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/strings.js');
require_once('scripts/widgets/DBLookUp.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js');

var DBViewLancamentoRegistros = function(iCodigoLote, sDescricaoLote, iAnoCompetencia, iMesCompetencia, lLancamentoRubrica) {

  if ( iCodigoLote === null ) {
    alert("Código de Lote Inválido");
    return false;
  }

  this.iCodigoLote        = iCodigoLote;
  this.sDescricaoLote     = sDescricaoLote;
  this.iAnoCompetencia    = iAnoCompetencia;
  this.iMesCompetencia    = iMesCompetencia;
  this.lLancamentoRubrica = lLancamentoRubrica; //-True lançamento por Rubrica; False - Lançamento por Servidor.
  var fCallBackRequisicao = function(oResposta, lErro) {};
  this.oRequisicaoAjax    = new AjaxRequest('pes4_loteregistrosponto.RPC.php', {}, fCallBackRequisicao);

  this.oWindow            = {};
  this.oGrid              = {}; //new DBGrid(); 
  this.oMessageBoard      = {}; //new DBMessageBoard();
  this.oLookupRubrica     = {}; //new DBLookUp();
  this.oLookupServidor    = {}; //new DBLookUp();'

  this.MENSAGENS_VALIDA_LIMITE_RUBRICA = "recursoshumanos.pessoal.pes4_valida_limite_rubrica.";
};

DBViewLancamentoRegistros.prototype.persistirRegistro = function (event, lExecutou) {

  iCodigoLote = this.iCodigoLote;
  
  if ( iCodigoLote === null ) {

    alert("Não foi possível obter o código do lote para persistir o registro");
    return false;
  }

  
  var sRubrica     = $F("rh27_rubric");
  var sMatricula   = $F("rh01_regist");
  var sLotacao     = $F("codLotacao");
  var sCompetencia = $F("rh27_limdat");
  var nValor       = $F("nValor");
  var iQuantidade  = $F("iQuantidade");
  var oSelf        = this;

  if(!sLotacao) {
    if (lExecutou == null || lExecutou == false) {
      window.setTimeout(function(){
        oSelf.persistirRegistro(oSelf.event, true);
      }, 2000);
    }
    return false;
  }

  if ($('rh27_limdat').readOnly === false) {
    
    var sStringAnoMes         = sCompetencia.split('/');
    var iAnoInformado         = parseInt(sStringAnoMes[0]);
    var iMesInformado         = parseInt(sStringAnoMes[1]);
    var lCompetenciaInformada = this.validarCompetenciaAtualPosterior(iAnoInformado, iMesInformado); 
    
    if (!lCompetenciaInformada) {
      return false;  
    }
    
  }

  if ( (!iQuantidade || !nValor) || (iQuantidade == 0 && nValor == 0) ) {
    alert("Campos quantidade e valor devem ser preenchidos.");
    return;
  }

  /**
   * Verifica se deve bloquear ou alertar o lançamento
   * caso o valor/quantidade da rubrica seja maior que
   * o limite configurado
   */
  if($F("rh27_tipobloqueio").toLowerCase() != 'n') { 

    var lLimiteExcedido = false;

    if(parseFloat($F("rh27_valorlimite")) > 0 && parseFloat(nValor) > parseFloat($F("rh27_valorlimite"))) {
      alert(_M( this.MENSAGENS_VALIDA_LIMITE_RUBRICA + "limite_valor_excedido", { 'valor' : $F("rh27_valorlimite") }));
      lLimiteExcedido = true;
    }
    
    if(parseFloat($F("rh27_quantidadelimite")) > 0 && parseFloat(iQuantidade) > parseFloat($F("rh27_quantidadelimite"))) {
      alert(_M( this.MENSAGENS_VALIDA_LIMITE_RUBRICA + "limite_quantidade_excedido", { 'quantidade' : $F("rh27_quantidadelimite") }));
      lLimiteExcedido = true;
    }

    //Bloqueia o lançamento
    if($F("rh27_tipobloqueio").toLowerCase() == 'b' && lLimiteExcedido) { 
      return;
    }
  }

  this.oRequisicaoAjax.setParameters({ 
    "exec"          :  "salvarRegistroLote",
    "iCodigoLote"   :  iCodigoLote,
    "sRubrica"      :  sRubrica,
    "sMatricula"    :  sMatricula,
    "sCompetencia"  :  sCompetencia,
    "nValor"        :  nValor,
    "iQuantidade"   :  iQuantidade
  });

  this.oRequisicaoAjax.setCallBack( function(oResponse, lErro) {

    alert(oResponse.message.urlDecode());

    if (lErro) {
      return false;
    }
    oSelf.limparDados();
    oSelf.loadData();
  });

  this.oRequisicaoAjax.setMessage("Incluindo Registro...");
  this.oRequisicaoAjax.execute();
};

DBViewLancamentoRegistros.prototype.limparDados = function (){

  if (this.lLancamentoRubrica) {

    $("rh01_regist").value  = '';
    $("z01_nome").value     = '';
    $("codLotacao").value   = '';
    $("descrLotacao").value = '';

  } else {

    $("rh27_rubric").value = '';
    $("rh27_descr").value  = '';
    $("rh27_presta").value  = '';
    $("notificacao").hide();
    $("rh27_limdat").value  = '';
    $('rh27_limdat').addClassName('readOnly');
    $('rh27_limdat').readOnly = true;

  }

  $("nValor").value       = '0';
  $("iQuantidade").value  = '0';
};

DBViewLancamentoRegistros.prototype.limparTodosDados = function () {

  this.limparDados();

  if (this.lLancamentoRubrica) {

    $("rh27_rubric").value  = '';
    $("rh27_descr").value   = '';
    $("rh27_presta").value  = '';
    $("notificacao").hide();
    $("rh27_limdat").value  = '';
    $('rh27_limdat').addClassName('readOnly');
    $('rh27_limdat').readOnly = true;

  } else {

    $("rh01_regist").value     = '';
    $("z01_nome").value        = '';
    $("codLotacao").value      = '';
    $("descrLotacao").value    = '';
  }
}

DBViewLancamentoRegistros.prototype.excluirRegistro = function (iCodigo) {
 
  if ( iCodigo === null ) {
    alert("Não foi possível obter o código do registro");
    return false;
  }

  var oSelf = this;

  this.oRequisicaoAjax.setParameters({ 
    "exec"          :  "excluirRegistroLote",
    "iCodigo"       :  iCodigo,
    "iCodigoLote"   :  this.iCodigoLote,
  });

  this.oRequisicaoAjax.setCallBack(function(oResponse, lErro){

    alert(oResponse.message.urlDecode());

    if (lErro){
      return false;
    }
    oSelf.loadData();
  });

  this.oRequisicaoAjax.setMessage("Excluindo Registro...");
  this.oRequisicaoAjax.execute();
};

DBViewLancamentoRegistros.prototype.loadWindow = function() {

  var oSelf = this;

  if ( $('lancamentoPorRubrica')) {
    $('lancamentoPorRubrica').remove();
  }

  var sFormulario = 'forms/db_frm_lancamento_registros_loteregistroponto.php';
  var sTitulo     = 'Lançamento de Registros do Ponto por Rubrica';

  if ( !this.lLancamentoRubrica ) {

    sFormulario = 'forms/db_frm_lancamento_registros_loteregistroponto_servidor.php';
    sTitulo     = 'Lançamento de Registros do Ponto por Servidor';
  } 

  this.oWindow  = new windowAux('lancamentoPorRubrica', sTitulo, 800, 600);
  this.oWindow.setContent('<div id="informacoeslote">');
  this.oWindow.setShutDownFunction(function() {oSelf.oWindow.destroy();});
  this.oWindow.show( null, null, true);

  this.oWindow.getContentContainer().load(
    sFormulario,
    function() {
      oSelf.makeEvents();
    }
  );
};

DBViewLancamentoRegistros.prototype.loadMessageBoard = function() {

  this.oMessageBoard = new DBMessageBoard(
    'informacoeslote',
    'Informações do Lote',
    this.getConteudoCabecalhoJanela(),
    $('informacoeslote')
  );
};

DBViewLancamentoRegistros.prototype.loadLookups = function() {

  var oSelf = this;
  this.oLookupRubrica = new DBLookUp($("procurarRubrica"), $("rh27_rubric"), $("rh27_descr"), {
    "sArquivo"             : "func_rhrubricas.php",
    'sObjetoLookUp'        : 'db_iframe_rhrubricas',
    "aCamposAdicionais"    : ["rh27_obs", "rh27_limdat", "rh27_presta", "rh27_valorlimite", "rh27_quantidadelimite", "rh27_tipobloqueio"],
    'aParametrosAdicionais': ['campos_adicionais=true', 'fixas=false']
  });

  this.oLookupMatricula = new DBLookUp($("procurarMatricula"), $("rh01_regist"), $("z01_nome"), {
    "sArquivo"              : "func_rhpessoal.php",
    "sObjetoLookUp"         : "db_iframe_rhpessoal",
    "aParametrosAdicionais" : ["testarescisao=true","lotelotacao=true"]
  });
  this.oLookupMatricula.oInputDescricao.className = "field-size8 readOnly";
  this.oLookupMatricula.setCallBack("onClick", function(lErro) { oSelf.carregarDadosLotacao(lErro); });
  this.oLookupMatricula.setCallBack("onChange",function(lErro) { oSelf.carregarDadosLotacao(lErro); });


  window.Zindex = 11005;
  var fCallBackChangeAnterior        = this.oLookupRubrica.callBackChange.bind(this.oLookupRubrica);

  this.oLookupRubrica.callBackChange = function(oRetornoLookUp, lErro){
    
    if ( lErro ) {
      fCallBackChangeAnterior(oRetornoLookUp, lErro);//arguments[0], true);
    } else {
      fCallBackChangeAnterior(oRetornoLookUp.rh27_descr, lErro);//arguments[0], true);
    }

    $('rh27_limdat').setValue("");
    $('rh27_limdat').removeClassName('readOnly');

    if (oRetornoLookUp.rh27_limdat === 't') { 
      $('rh27_presta').setValue(oRetornoLookUp.rh27_presta);
      $('rh27_limdat').readOnly = false;
    } else {
      $('rh27_limdat').addClassName('readOnly');
      $('rh27_limdat').readOnly = true;
    }

    $('notificacao').style.display         = 'none';

    if (oRetornoLookUp.rh27_obs) {
      $('notificacao').style.display         = 'block';
      $('notificacao').children[0].innerHTML = oRetornoLookUp.rh27_obs.toLowerCase();
    }

    $('rh27_valorlimite').setValue(oRetornoLookUp.rh27_valorlimite);
    $('rh27_quantidadelimite').setValue(oRetornoLookUp.rh27_quantidadelimite);
    $('rh27_tipobloqueio').setValue(oRetornoLookUp.rh27_tipobloqueio);
  };

  var fCallBackClickAnterior        = this.oLookupRubrica.callBackClick.bind(this.oLookupRubrica);

  this.oLookupRubrica.callBackClick = function(iCodigo, sDescricao, sObservacao, lCompetencia, lCalcularPrestacao, nValorlimite, nQuantidadelimite, sTipobloqueio){
    
    $('rh27_limdat').setValue("");
    $('rh27_limdat').removeClassName('readOnly');
 
    if (lCompetencia === 't') {
      $('rh27_presta').setValue(lCalcularPrestacao);
      $('rh27_limdat').readOnly = false;
    } else {
      $('rh27_limdat').addClassName('readOnly');
      $('rh27_limdat').readOnly = true;
    }

    $('notificacao').style.display         = 'none';

    if ( sObservacao ) {
      
      $('notificacao').style.display         = 'block';
      $('notificacao').children[0].innerHTML = sObservacao.toLowerCase();
    }

    $('rh27_valorlimite').setValue(nValorlimite);
    $('rh27_quantidadelimite').setValue(nQuantidadelimite);
    $('rh27_tipobloqueio').setValue(sTipobloqueio);

    fCallBackClickAnterior(iCodigo, sDescricao);
  };
  
  $('rh27_limdat').addEventListener('change', function (){
    oSelf.addEventQuantidadeMeses();
  });
};

DBViewLancamentoRegistros.prototype.loadData = function() {

  this.oRequisicaoAjax.setParameters({                                        
    "exec"         : "buscarRegistrosLote",
    "iCodigoLote"  : this.iCodigoLote,
    "lRubrica"     : this.lLancamentoRubrica
  });

  this.oRequisicaoAjax.setCallBack(DBViewLancamentoRegistros.prototype.carregarDadosGrid.bind(this));
  this.oRequisicaoAjax.setMessage("Buscando Registros...");
  this.oRequisicaoAjax.execute();
};

DBViewLancamentoRegistros.prototype.loadDataGrid = function() {

  var aHeader = ["Matrícula", "Servidor", "Ano/Mês", "Quantidade", "Valor" , "Opção", "Seq", "Rubrica"];

  if (!this.lLancamentoRubrica) {
    aHeader = ["Código", "Rubrica", "Ano/Mês", "Quantidade", "Valor" , "Opção", "Seq", "Matricula"];
  }

  this.oGrid = new DBGrid("registrosLote");
  this.oGrid.setHeader(aHeader);
  this.oGrid.setCellWidth(["8%", "34%", "7%", "11%", "10%", "10%", "10%", "10%"]);
  this.oGrid.setCellAlign(["center","left", "center", "center", "center", "center", "center", "center"]);
  this.oGrid.show( $("gridRegistros") );

  this.loadData();
};

DBViewLancamentoRegistros.prototype.show = function() {
  this.loadWindow();
  this.loadMessageBoard();
  this.loadLookups();
  this.loadDataGrid();
};

DBViewLancamentoRegistros.prototype.getConteudoCabecalhoJanela = function() {

  var sConteudo = "<table class='form-container' style='width: 150px'>";
  sConteudo    += "  <tr style='text-indent:15px;'>";
  //  sConteudo    += "    <td>Competência:</td>";
  //  sConteudo    += "    <td>"+this.iCodigo+"</td></tr>";
  sConteudo    += "  <tr style='text-indent:15px;'>";
  sConteudo    += "    <td style='font-weight: normal'>"+this.sDescricaoLote+"</td></tr>";
  sConteudo    +="</table>";
  return sConteudo;
};

DBViewLancamentoRegistros.prototype.carregarDadosLotacao = function(lErro) {

  $("codLotacao").value   = "";
  $("descrLotacao").value = "";

  if(lErro){
    return false;
  }

  this.oRequisicaoAjax.setParameters({
    "exec"       :  "consultarLotacaoServidor",
    "sMatricula" :  $F("rh01_regist")
  });

  this.oRequisicaoAjax.setCallBack(function(oResponse, lErro){

    if(lErro){
      return false;
    }

    $("codLotacao").value   = oResponse.iCodigoLotacao;
    $("descrLotacao").value = oResponse.sDescricaoLotacao;
  });

  this.oRequisicaoAjax.setMessage("Buscando Lotacao...");
  this.oRequisicaoAjax.execute();
};

DBViewLancamentoRegistros.prototype.carregarDadosGrid = function (oResponse, lErro){
  
  if (this.lLancamentoRubrica) {
    this.carregarDadosGridPorRubrica(oResponse, lErro);
  } else {
    this.carregarDadosGridPorServidor(oResponse, lErro);
  }
};

DBViewLancamentoRegistros.prototype.carregarDadosGridPorRubrica = function(oResponse, lErro) {

  var oSelf = this;

  if (lErro) {
    alert("Não foi possível buscar os registros do lote");
    return false;
  }
      
  this.oGrid.clearAll(true);

  var iCounterLinha = 0;

  /**
   * Percorremos as Rubricas cadastradas para o lote atual
   */
  for (var sIndice in oResponse.aRegistros ) {

    var oRegistros = oResponse.aRegistros[sIndice];

    this.oGrid.addRow(["<b>Rubrica: "+oRegistros[0].sRubrica+" - "+oRegistros[0].sNomeRubrica+ "</b>"]);
    this.oGrid.aRows[iCounterLinha].aCells[0].setUseColspan(true, 8);
    this.oGrid.aRows[iCounterLinha].setClassName('nome-lote');
    iCounterLinha++;

    /**
     * Percorremos servidores que estão vinculados rubrica atual.
     */
    for (iRegistro = 0; iRegistro < oRegistros.length; iRegistro++) {

      oRegistro = oRegistros[iRegistro];

      this.oGrid.addRow([ oRegistro.sMatricula,
                          oRegistro.sNome,
                          oRegistro.sCompetencia,
                          "<input type='text' onInput=\"js_ValidaCampos(this, 4, 'Valor', true)\" style='width: 90%' id='" + oRegistro.iCodigo +"_iQuantidade' value='"+ oRegistro.iQuantidade +"' />" ,
                          "<input type='text' onInput=\"js_ValidaCampos(this, 4, 'Valor', true)\" style='width: 90%' id='" + oRegistro.iCodigo +"_nValor' value='"+ oRegistro.nValor +"' />" ,
                          "<input type='button' class='excluir_registro' value='Excluir' rubrica="+ oRegistro.sRubrica +" matricula="+ oRegistro.sMatricula +"  codigo_registro="+ oRegistro.iCodigo +" />",
                          oRegistro.iCodigo,
                          oRegistro.sRubrica
                        ]); 
      iCounterLinha++;
      
    }
  } 
  this.oGrid.renderRows();

  $$('.excluir_registro').each(function(oElemento, iIndice) {

    oElemento.onclick = function() {

      var sMensagemConfirmacao = "Deseja realmente excluir a rubrica \(";
      sMensagemConfirmacao    += oElemento.getAttribute('rubrica');
      sMensagemConfirmacao    += "\) do servidor \(";
      sMensagemConfirmacao    += oElemento.getAttribute('matricula');
      sMensagemConfirmacao    += "\)?";

      if ( confirm(sMensagemConfirmacao) )
      {
        oSelf.excluirRegistro(oElemento.getAttribute('codigo_registro'));
      }
    };
  });
};

DBViewLancamentoRegistros.prototype.carregarDadosGridPorServidor = function(oResponse, lErro) {

  var oSelf = this;

  if (lErro) {
    alert("Não foi possível buscar os registros do lote");
    return false;
  }
      
  this.oGrid.clearAll(true);

  var iCounterLinha = 0;

  /**
   * Percorremos as Rubricas cadastradas para o lote atual
   */
  for (var sIndice in oResponse.aRegistros ) {

    var oRegistros = oResponse.aRegistros[sIndice];

    this.oGrid.addRow(["<b>Servidor: "+oRegistros[0].sMatricula+" - "+oRegistros[0].sNome+ "</b>"]);
    this.oGrid.aRows[iCounterLinha].aCells[0].setUseColspan(true, 8);
    this.oGrid.aRows[iCounterLinha].setClassName('nome-lote');
    iCounterLinha++;

    /**
     * Percorremos servidores que estão vinculados rubrica atual.
     */
    for (iRegistro = 0; iRegistro < oRegistros.length; iRegistro++) {

      oRegistro = oRegistros[iRegistro];

      this.oGrid.addRow([oRegistro.sRubrica,
                         oRegistro.sNomeRubrica,
                         oRegistro.sCompetencia,
                         "<input type='text' onInput=\"js_ValidaCampos(this, 4, 'Valor', true)\" style='width: 90%' id='" + oRegistro.iCodigo +"_iQuantidade' value='"+ oRegistro.iQuantidade +"' />" ,
                         "<input type='text' onInput=\"js_ValidaCampos(this, 4, 'Valor', true)\" style='width: 90%' id='" + oRegistro.iCodigo +"_nValor' value='"+ oRegistro.nValor +"' />" ,
                         "<input type='button' class='excluir_registro' value='Excluir' codigo_registro="+ oRegistro.iCodigo +" />",
                         oRegistro.iCodigo,
                         oRegistro.sMatricula
                        ]); 
      iCounterLinha++;
      
    }
  } 
  this.oGrid.renderRows();

  $$('.excluir_registro').each(function(oElemento, iIndice) {

    oElemento.onclick = function() {

      if ( confirm('Deseja realmente excluir o lançamanto?') ) {
        oSelf.excluirRegistro(oElemento.getAttribute('codigo_registro'));
      }
    };
  });
};

DBViewLancamentoRegistros.prototype.makeEvents = function() {
  $('incluirRegistro').observe( "click", DBViewLancamentoRegistros.prototype.persistirRegistro.bind(this) );
  $('LimparDados').observe( "click", DBViewLancamentoRegistros.prototype.limparTodosDados.bind(this) );
  $('alterarRegistro').observe( "click", DBViewLancamentoRegistros.prototype.alterarRegistros.bind(this) );
  $('rh27_limdat').observe( "keyup", DBViewLancamentoRegistros.prototype.js_mascaracompetencia.bind(this, $('rh27_limdat')));
  $('rh27_limdat').observe( "drop", DBViewLancamentoRegistros.prototype.js_mascaracompetencia.bind(this, $('rh27_limdat')));
};

DBViewLancamentoRegistros.prototype.alterarRegistros = function() {
  
  var oDadosAtualizar = [];
  var oDados = {};
  var oRow;

  if (this.oGrid.getNumRows() == 0) {
    
    alert('Por favor inclua pelo menos um registro no ponto.');
    return false;
  }

  for (iLinha = 0; iLinha < this.oGrid.getNumRows(); iLinha++) {

    oRow = this.oGrid.getRows()[iLinha];

    if ( oRow.getCells().length > 1) {

      oDados  = {};
      oDados.iMatricula  = oRow.getCells()[0].getValue();
      oDados.sCompetencia= oRow.getCells()[2].getValue();
      oDados.iQuantidade = oRow.getCells()[3].getValue() ?  oRow.getCells()[3].getValue() : "0";
      oDados.iValor      = oRow.getCells()[4].getValue() ?  oRow.getCells()[4].getValue() : "0";
      oDados.iCodigo     = oRow.getCells()[6].getValue();
      oDados.sRubrica    = oRow.getCells()[7].getValue();

      if (!this.lLancamentoRubrica){
        oDados.iMatricula  = oRow.getCells()[7].getValue();
        oDados.sRubrica    = oRow.getCells()[0].getValue();
      }

      oDadosAtualizar.push(oDados);
    }
  }


  this.oRequisicaoAjax.setParameters({ 
    "exec"          :  "alterarRegistrosLote",
    "iCodigoLote"   :  this.iCodigoLote,
    "oDadosAtualizar" :  Object.toJSON(oDadosAtualizar),
  });

  this.oRequisicaoAjax.setCallBack( function(oResponse, lErro) {

    /**
     * Se não houve erro e há mensagens de AVISO
     * que excedeu o limite de valor/quantidade
     */
    if (!lErro && oResponse.messagemValidacaoLimites.urlDecode() != '') {
      alert(oResponse.messagemValidacaoLimites.urlDecode());
    }

    if(oResponse.message.urlDecode() != '') {
      alert(oResponse.message.urlDecode());
    }

    if (lErro){

      if (oResponse.messagemValidacaoLimites.urlDecode() != '') {
        alert(oResponse.messagemValidacaoLimites.urlDecode());
      }
      return false;
    }
    
    oSelf.loadData();
  });

  this.oRequisicaoAjax.setMessage("Alterando Registros...");
  this.oRequisicaoAjax.execute();
};

DBViewLancamentoRegistros.prototype.js_mascaracompetencia = function (input, event) {

  var regNaN          = new RegExp(/[^\d]/g);
  var content         = input.value;
  var contentNumber   = content.replace(regNaN, "");
  var teclasEspeciais = [8, 46, 37, 39, 36, 35];     // Teclas backspace, delete, direcionais , Home e End
  var posicaoCursor   = input.selectionStart;

  if (event.type == 'drop' ) {
    event.preventDefault();
  }

  if (contentNumber == '' || contentNumber == NaN) {

    input.value = '';
    return false;

  }

   //Se o conteúdo do campo for maior que 4 aplica máscara
  if (contentNumber.length >= 4 || content.match(/\d{1,4}/) == null) {

    //Tratando teclas Backspace e Delete e direcionais
    if ( !this.in_array(event.keyCode, teclasEspeciais) || event.type != 'keyup' ) {
      
      //Tratando se não há números depois da barra e a digitação rápida do campo
      if (content.indexOf("/") == -1 || content.match(/\/\d{1,2}/) == null) {

        if (contentNumber.length == 4) { 
          input.value = content.match(/\d{1,4}/) + "/" ;

          //Ajuste posicionamento do cursor
          if (event.type == "keyup" && posicaoCursor == contentNumber.length) {
            posicaoCursor = input.value.length;
          }

        } else {//Tratando o apagar da / e digitação de um dígito

          var digitosPosBarra = content.replace(/.*(\d{1,2})/, "$1");
          input.value = content.match(/\d{1,4}/) + "/" + digitosPosBarra.replace(/(\d*).*/, "$1");

          //Ajuste posicionamento do cursor
          if (event.type == "keyup" && posicaoCursor == content.length) {
            posicaoCursor = input.value.length;
          }
        }
      } else {//Tratantando se há números depois da barra

        var digitosPosBarra = content.replace(/\d{1,4}(.*)/, "$1");
        input.value = content.match(/\d{1,4}/) + "/" + digitosPosBarra.replace(regNaN, "");

      }
    }
  } else {//Se o conteúdo do campo for menor que 4 mantém apenas os números
    input.value = contentNumber;
  }

  //Reposicionando cursor
  if (event.type == "keyup" && posicaoCursor != input.value.length) {
    input.setSelectionRange(posicaoCursor, posicaoCursor);
  }
};

DBViewLancamentoRegistros.prototype.in_array = function(needle, haystack) {
  for(var i in haystack) {
    if(haystack[i] == needle) return true;
  }
  return false;
};

/**
 * Método responsável por válidar se competência informada é superior com a competência atual.
 * 
 * @param {Integer} iAnoInformado
 * @param {Integer} iMesInformado
 * @returns {Boolean}
 */
DBViewLancamentoRegistros.prototype.validarCompetenciaAtualPosterior = function(iAnoInformado, iMesInformado) {
  
  if (!iAnoInformado || iAnoInformado.length < 4) {
    
    alert("Informe um ano válido.");
    return false;
  }

  if (!iMesInformado || iMesInformado < 0 || iMesInformado > 12) {
    
    alert("Informe um mês válido.");
    return false;
  }
  
  /**
   * A inversão dos booleanos é por causa do método "isCompetenciaValida()", porque este método válida 
   * se a competência informada é maior que a competência atual, caso seja retorna um false. Lembrando 
   * que o método tem responsabilidade de retornar um true para este caso.
   */
  var oCompetencia           = new DBViewFormularioFolha.CompetenciaFolha();
  var oCompetenciaInformada  = new DBViewFormularioFolha.CompetenciaFolha();
  oCompetenciaInformada.iAno = iAnoInformado;
  oCompetenciaInformada.iMes = iMesInformado;
  var lCompetencia           = (oCompetencia.equalsTo(oCompetenciaInformada)) ? true : false;
  
  if (!lCompetencia) {
    lCompetencia = (oCompetencia.isCompetenciaValida(iAnoInformado, iMesInformado)) ? false : true;
  }
  
  if (!lCompetencia) {
    alert("Campo 'Ano/Mês' deve ser maior ou igual que a competência atual da folha.");
  }
  
  return lCompetencia;
};

/**
 * Método responsável por calcular a quantidade de meses da competência atual com a competência informada.
 * 
 * @param {DBViewlancamentoRegistros} oSelf
 * @param {Integer} iAnoInformado
 * @param {Integer} iMesInformado
 * @returns {Integer}
 */
DBViewLancamentoRegistros.prototype.calcularQuantidadeMeses = function(oSelf, iAnoInformado, iMesInformado) {
  
  var iQuantidade = 0;
  
  if (iAnoInformado > oSelf.iAnoCompetencia) {
    
    while (iAnoInformado > (oSelf.iAnoCompetencia + 1)) {
      iQuantidade += 12;
      --iAnoInformado;
    }
    var iMesRestante = (12 - oSelf.iMesCompetencia);
    
    iQuantidade += iMesRestante + iMesInformado;

  } else if (iAnoInformado == oSelf.iAnoCompetencia && iMesInformado == oSelf.iMesCompetencia) {

    iQuantidade = 1;
    
  } else {
    
    iQuantidade += iMesInformado - oSelf.iMesCompetencia;
  }
  
  return iQuantidade;
};

/**
 * Método representa um evento que contém a lógica do Ano/Mês e da quantidade de meses da competência atual com a competência informada.
 */
DBViewLancamentoRegistros.prototype.addEventQuantidadeMeses = function() {
  
  $('iQuantidade').setValue("");
  
  var lCalcularPrestacao    = $('rh27_presta').getValue();
  var sStringAnoMes         = $('rh27_limdat').getValue().split('/');
  var iAnoInformado         = parseInt(sStringAnoMes[0]);
  var iMesInformado         = parseInt(sStringAnoMes[1]);
  var lCompetenciaInformada = this.validarCompetenciaAtualPosterior(iAnoInformado, iMesInformado);

  if (lCalcularPrestacao === 't' && lCompetenciaInformada === true) {
    
    var iQuantidade = this.calcularQuantidadeMeses(this, iAnoInformado, iMesInformado);          
    $('iQuantidade').setValue(iQuantidade);
  }
  
};