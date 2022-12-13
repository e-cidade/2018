DBViewAvaliacao.Conversao = function ( oTurma, iRegencia, sRegencia, aPeriodos ) {

  /**
   *
   * @exemple
   * ({iTurma:"680",
   *   iEtapa:"17",
   *   sEscola:"EMEF BARAO DO RIO BRANCO",
   *   sCalendario:"CALENDÁC1RIO 2013",
   *   sCurso:"ENSINO FUNDAMENTAL 9 ANOS",
   *   sBaseCurricular:"ANOS FINAIS",
   *   sTurma:"8º ANO",
   *   sEtapa:"8º ANO",
   *   sProcedimentoAvaliacao:"NOTA",
   *   sTurno:"MANHÃ"
   *  }
   * )
   */
  this.oTurma     = oTurma;
  this.iRegencia  = iRegencia;
  this.sRegencia  = sRegencia;

  /**
   * Deve receber um Array de Objetos com as seguintes propriedades:
   * [{iCodigoAvaliacao:"1",
   *   iOrdemAvaliacao:"1",
   *   sFormaAvaliacao:"NOTA",
   *   mMinimoAprovacao:"10.00",
   *   iMenorValor:"0",
   *   iMaiorValor:"20",
   *   sDescricaoPeriodo:"1%BA+TRIMESTRE",
   *   sDescricaoPeriodoAbreviado:"1%BA+TRIM",
   *   sTipoAvaliacao:"A"
   *  }
   * ]
   */
  this.aPeriodos  = aPeriodos;
  this.aAlunos    = new Array();
  this.oAluno     = '';
  this.sRPC       = 'edu4_conversao.RPC.php';


  this.oWindowContainer = '';

  /****************************************************
   ******     Botoes e componentes da View   ***********
   ***************************************************** */

  this.oBtnSalvarConversao       = document.createElement('input');
  this.oBtnSalvarConversao.type  = 'button';
  this.oBtnSalvarConversao.id    = 'salvarConversao';
  this.oBtnSalvarConversao.name  = 'salvarConversao';
  this.oBtnSalvarConversao.value = 'Salvar';

  this.oBtnFecharConversao       = document.createElement('input');
  this.oBtnFecharConversao.type  = 'button';
  this.oBtnFecharConversao.id    = 'fecharConversao';
  this.oBtnFecharConversao.name  = 'fecharConversao';
  this.oBtnFecharConversao.value = 'Fechar';

  this.oCboPeriodos             = document.createElement('select');
  this.oCboPeriodos.id          = 'cboPeriodos';
  this.oCboPeriodos.style.width = '100%';

  this.oCboAlunos             = document.createElement('select');
  this.oCboAlunos.id          = 'cboAlunos';
  this.oCboAlunos.style.width = '100%';
  this.oCboAlunos.add(new Option('Selecione', ''));

  this.oCallBackCloseWindow = function() {
    return true;
  };
};

/**
 * Monta os campos a serem apresentados na tela
 */
DBViewAvaliacao.Conversao.prototype.montaJanela = function () {

  var oSelf = this;
  var iLargura = 720;
  var iAltura  = document.body.clientHeight / 1.6;


  this.oWindowConversao = new windowAux('wndConversao', 'Conversão de Nota', iLargura, iAltura);
  this.oWindowConversao.allowCloseWithEsc(false);

  this.oWindowConversao.setShutDownFunction(function () {

    oSelf.oCallBackCloseWindow();
    oSelf.oWindowConversao.destroy();
  });

  var sConteudo  = '<fieldset class="container" style="width:680px;">';
      sConteudo += '  <legend >Conversão de Avaliação</legend>';
      sConteudo += '  <fieldset class="separator">';
      sConteudo += '    <legend >Selecione o Período / Aluno</legend>';
      sConteudo += '    <table class="form-container" style="width:100%">';
      sConteudo += '      <tr>';
      sConteudo += '        <td class="field-size4" ><b>Período:</b></td>';
      sConteudo += '        <td id="ctnPeriodos"></td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><b>Aluno:</b></td>';
      sConteudo += '        <td id="ctnAlunos"></td>';
      sConteudo += '      </tr>';
      sConteudo += '    </table>';
      sConteudo += '  </fieldset>';
      sConteudo += '  <fieldset class="separator">';
      sConteudo += '    <legend>Escola de Origem</legend>';
      sConteudo += '    <table class="form-container" style="width:100%">';
      sConteudo += '      <tr>';
      sConteudo += '        <td  class="field-size4" ><b>Escola:</b></td>';
      sConteudo += '        <td id="ctnCodigoEscolaOrigem" colspan="3" >';
      sConteudo += '          <input type="text" id="iEscolaOrigem" name="iEscolaOrigem" value = "" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:100px"/>';
      sConteudo += '          <input type="text" id="sEscolaOrigem" name="sEscolaOrigem" value = "" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:385px" />';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><b>Tipo:</b></td>';
      sConteudo += '        <td id="ctnTipoEscolaOrigem" colspan="4">';
      sConteudo += '          <input type="text" id="sTipoEscolaOrigem" name="sTipoEscolaOrigem" value = ""';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:489px" />';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><b>Município:</b></td>';
      sConteudo += '        <td id="ctnMunicipioEscolaOrigem" >';
      sConteudo += '          <input type="text" id="sMunicipio" name="sMunicipio" value = "" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:298px"  />';
      sConteudo += '        </td>';
      sConteudo += '        <td style="width:85px"><b>Estado:</b></td>';
      sConteudo += '        <td id="ctnEstadoEscolaOrigem" >';
      sConteudo += '          <input type="text" id="sEstado" name="sEstado" value = "" readonly="readonly" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:100px" />';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><b>Forma de Avaliação:</b></td>';
      sConteudo += '        <td id="ctnDescricaoFormaAvaliacao" colspan="3">';
      sConteudo += '          <input type="text" id="sFormaOrigem" name="sFormaOrigem" value = "" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:100px" />';
      sConteudo += '          <input type="text" id="sMinimoMaximoOrigem" name="sMinimoMaximoOrigem" value = ""';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:100px" />';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><b>Aproveitamento:</b></td>';
      sConteudo += '        <td id="ctnAproveitamento" colspan="3">';
      sConteudo += '          <input type="text" id="sAproveitamentoOrigem" name="sAproveitamentoOrigem" ';
      sConteudo += '                 value = "" readonly="readonly" class="readonly" style="width:100px" />';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '    </table>';
      sConteudo += '  </fieldset>';
      sConteudo += '  <fieldset class="separator">';
      sConteudo += '    <legend>Aproveitamento nesta Escola</legend>';
      sConteudo += '    <table class="form-container" style="width:100%" >';
      sConteudo += '      <tr><td></td></tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td id="ctnAproveitamentoConvertido" colspan="5"></td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr><td></td></tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td  class="field-size4" ><b>Forma de Avaliação:</b></td>';
      sConteudo += '        <td id="ctnDescricaoFormaAvaliacaoEscola" >';
      sConteudo += '          <input type="text" id="sFormaAvaliacao" name="sForma" value = "" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:100px" />';
      sConteudo += '          <input type="text" id="sMinimoMaximo" name="sMinimoMaximo" value = "" ';
      sConteudo += '                 readonly="readonly" class="readonly" style="width:100px" />';
      sConteudo += '          <input type="text" size="10" id="iDiarioAvaliacao" name="iDiarioAvaliacao" value = "" ';
      sConteudo += '                 style="display:none;" />';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '      <tr>';
      sConteudo += '        <td><b>Aproveitamento:</b></td>';
      sConteudo += '        <td id="ctnAproveitamentoEscola" >';
      sConteudo += '          <input type="text" id="sAproveitamento" name="sAproveitamento" value = "" ';
      sConteudo += '                 style="width:100px"/>';
      sConteudo += '        </td>';
      sConteudo += '      </tr>';
      sConteudo += '    </table>';
      sConteudo += '  </fieldset>';
      sConteudo += '  <center id ="ctnBotoes"></center>';
      sConteudo += '</fieldset>';

  this.oWindowConversao.setContent(sConteudo);

  var sTitulo  = 'Conversão do aproveitamento de alunos na disciplina ' + this.sRegencia;
  var sAjuda   = 'Selecione um período e aluno, e informe o aproveitamento do aluno com base na forma de ';
      sAjuda  += 'avaliação da turma.';
  new DBMessageBoard('msgConversao', sTitulo, sAjuda, this.oWindowConversao.getContentContainer());

  if (this.oWindowContainer != '') {
    oSelf.oWindowConversao.setChildOf(this.oWindowContainer);
  }

  this.oWindowConversao.show();
};

/**
 * Monta o combobox dos períodos
 */
DBViewAvaliacao.Conversao.prototype.getPeriodos = function () {

  var oSelf = this;

  var oOptionVazio       = document.createElement('option');
  oOptionVazio.value     = '';
  oOptionVazio.innerHTML = 'Selecione um período.';

  this.oCboPeriodos.appendChild(oOptionVazio);
  this.aPeriodos.each(function (oPeriodo, iSeq) {

    if(oPeriodo.sTipoAvaliacao == 'R') {
      return;
    }

    var oOption       = document.createElement('option');
    oOption.value     = oPeriodo.iOrdemAvaliacao;
    oOption.innerHTML = oPeriodo.sDescricaoPeriodo.urlDecode();

    oSelf.oCboPeriodos.appendChild(oOption);
  });
};

DBViewAvaliacao.Conversao.prototype.setContainer = function (oWindow) {
  this.oWindowContainer = oWindow;
};

/**
 * Busca os alunos com nota para conversao
 */
DBViewAvaliacao.Conversao.prototype.getAlunos = function () {

  var oSelf = this;

  this.oCboAlunos.innerHTML = '';
  this.oCboAlunos.add(new Option('Selecione um aluno', ''));

  var oParametro           = new Object();
      oParametro.exec      = 'getAlunoComNotaExterna';
      oParametro.iPeriodo  = $F('cboPeriodos');
      oParametro.iTurma    = this.oTurma.iTurma;
      oParametro.iEtapa    = this.oTurma.iEtapa;
      oParametro.iRegencia = this.iRegencia;

  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = function(oAjax) {
        oSelf.retornoGetAluno(oAjax);
      };

  js_divCarregando("Aguarde, pesquisando alunos para conversão da nota no período selecionado.", "msgBox");
  new Ajax.Request( this.sRPC, oDadosRequest );
};

/**
 * Retorno da busca dos alunos com nota para conversao
 */
DBViewAvaliacao.Conversao.prototype.retornoGetAluno = function ( oAjax ) {

  var oSelf = this;

  oSelf.limpaCampos();
  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  if ( oRetorno.aAlunos.length > 0 ) {

    oSelf.aAlunos = oRetorno.aAlunos;
    oRetorno.aAlunos.each(function( oAluno, iAluno ) {

      var oOption = document.createElement('option');
      oOption.setAttribute('value', oAluno.iMatricula);
      oOption.innerHTML = oAluno.sNomeAluno.urlDecode();
      $('cboAlunos').appendChild(oOption);
    });
  } else {
    alert('Nenhum aluno com nota externa para o período.');
  }
};

/**
 * Preenche os campos com os dados do aluno selecionado
 */
DBViewAvaliacao.Conversao.prototype.getDados = function () {

  this.aAlunos.each(function (oAluno, iSeq) {

    if (oAluno.iMatricula != $F('cboAlunos')) {
      return;
    }

    $('iEscolaOrigem').value       = oAluno.oAvaliacaoOrigem.iEscola;
    $('sEscolaOrigem').value       = oAluno.oAvaliacaoOrigem.sEscola.urlDecode();
    $('sTipoEscolaOrigem').value   = oAluno.oAvaliacaoOrigem.sTipoEscola.urlDecode();
    $('sMunicipio').value          = oAluno.oAvaliacaoOrigem.sMunicipio.urlDecode();
    $('sEstado').value             = oAluno.oAvaliacaoOrigem.sEstado.urlDecode();
    $('sFormaOrigem').value        = oAluno.oAvaliacaoOrigem.sFormaAvaliacao.urlDecode();
    $('sMinimoMaximoOrigem').value = '';

    if (oAluno.oAvaliacaoOrigem.sFormaAvaliacao.urlDecode() == 'NOTA') {
      $('sMinimoMaximoOrigem').value = oAluno.oAvaliacaoOrigem.nMenorValor +' à '+ oAluno.oAvaliacaoOrigem.nMaiorValor;
    }

    $('sAproveitamentoOrigem').value = oAluno.oAvaliacaoOrigem.mNotaOrigem;

    $('sFormaAvaliacao').value = oAluno.sFormaAvaliacao.urlDecode();
    $('sMinimoMaximo').value   = '';
    if (oAluno.sFormaAvaliacao.urlDecode() == 'NOTA') {
      $('sMinimoMaximo').value = oAluno.nMenorValor +' à '+ oAluno.nMaiorValor;
    }

    $('sAproveitamento').value  = '';

    if (oAluno.sFormaAvaliacao.urlDecode() == 'NIVEL') {


      $('ctnAproveitamentoEscola').innerHTML = '';

      var oCtnConceito = document.createElement('select');
      oCtnConceito.id  = 'sAproveitamento';
      oCtnConceito.addClassName('tamanhoElemento');
      oCtnConceito.addClassName('alignLeft');

      var oOptionVazio = document.createElement('option');
      oCtnConceito.appendChild(oOptionVazio);

      oAluno.aConceito.each(function (oConceito, iConceito) {

        var oOption = document.createElement('option');
        oOption.setAttribute('value', oConceito.sConceito);
        oOption.setAttribute('ordem', oConceito.iOrdem);
        oOption.innerHTML = oConceito.sConceito;
        oCtnConceito.appendChild(oOption);
      });

      $('ctnAproveitamentoEscola').appendChild(oCtnConceito);
    }

    var sMensagem = '<b>* Forma de avaliação diferente da origem - Aproveitamento precisa ser convertido</b>';
    $('ctnAproveitamentoConvertido').innerHTML = sMensagem;
    if ( !oAluno.lConvertido ) {

      $('ctnAproveitamentoConvertido').innerHTML = '<b>* Aproveitamento já convertido</b>';
      $('sAproveitamento').value = oAluno.mNota;
    }

    $('iDiarioAvaliacao').value = oAluno.iDiarioAvaliacao;
  });
};

/**
 * Salva a conversao
 */
DBViewAvaliacao.Conversao.prototype.salvarConversao = function () {

  var oSelf  = this;

  var iOrdem = '';
  if ( $F('sFormaAvaliacao') == 'NIVEL' ) {
    iOrdem = $('sAproveitamento').options[$('sAproveitamento').selectedIndex].getAttribute('ordem');
  }

  var oObjeto              = new Object();
  oObjeto.exec             = 'salvarConversao';
  oObjeto.iMatricula       = $F('cboAlunos');
  oObjeto.iRegencia        = oSelf.iRegencia;
  oObjeto.iPeriodo         = $F('cboPeriodos');
  oObjeto.iDiarioAvaliacao = $F('iDiarioAvaliacao');
  oObjeto.sAproveitamento  = $F('sAproveitamento');
  oObjeto.sFormaAvaliacao  = $F('sFormaAvaliacao');
  oObjeto.iOrdem           = iOrdem;

  var oJson          = new Object();
  oJson.method       = 'post';
  oJson.parameters   = 'json='+Object.toJSON(oObjeto);
  oJson.onComplete   = function(oResponse) {
                                             oSelf.retornoSalvarConversao(oResponse, oSelf);
                                           };

  js_divCarregando("Aguarde, salvando conversão do aproveitamento do aluno.", "msgBox");
  new Ajax.Request(this.sRPC, oJson);
};

DBViewAvaliacao.Conversao.prototype.retornoSalvarConversao = function ( oResponse, oSelf ) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if ( oRetorno.status == 1 ) {

    alert('Conversão salva com sucesso.');
    $('cboPeriodos').value                     = '';
    $('cboAlunos').innerHTML                   = '';
    $('ctnAproveitamentoConvertido').innerHTML = '';
    this.oCboAlunos.add(new Option('Selecione um aluno', ''));
    oSelf.limpaCampos();
  } else {

    alert(oRetorno.message.urlDecode());
    return false;
  }
};

/**
 * Ao fechar a janela, recarrega a tela anterior
 */
DBViewAvaliacao.Conversao.prototype.setCallBackWindow = function (fFunction) {
  this.oCallBackCloseWindow = fFunction;
};

/**
 * Limpa todos os campos da tela
 */
DBViewAvaliacao.Conversao.prototype.limpaCampos = function () {

  $('iEscolaOrigem').value                   = '';
  $('sEscolaOrigem').value                   = '';
  $('sTipoEscolaOrigem').value               = '';
  $('sMunicipio').value                      = '';
  $('sEstado').value                         = '';
  $('sFormaOrigem').value                    = '';
  $('sMinimoMaximoOrigem').value             = '';
  $('sAproveitamentoOrigem').value           = '';
  $('sFormaAvaliacao').value                 = '';
  $('sMinimoMaximo').value                   = '';
  $('sAproveitamento').value                 = '';
  $('iDiarioAvaliacao').value                = '';
  $('ctnAproveitamentoConvertido').innerHTML = '';
};

/**
 * Inicializamos o componente
 */
DBViewAvaliacao.Conversao.prototype.show = function () {

  var oSelf = this;
  this.montaJanela();
  this.getPeriodos();

  $('ctnPeriodos').appendChild(this.oCboPeriodos);
  $('ctnAlunos').appendChild(this.oCboAlunos);
  $('ctnBotoes').appendChild(this.oBtnSalvarConversao);
  $('ctnBotoes').appendChild(this.oBtnFecharConversao);

  this.oBtnFecharConversao.onclick = function () {

    oSelf.oCallBackCloseWindow();
    oSelf.oWindowConversao.destroy();
  };

  this.oBtnSalvarConversao.onclick = function () {
    oSelf.salvarConversao();
  };


  this.oCboPeriodos.onchange = function () {

    oSelf.limpaCampos();
    $('cboAlunos').value = '';
    if ($F('cboPeriodos') == '') {
      return false;
    }
    oSelf.getAlunos();
  };

  this.oCboAlunos.onchange = function () {

    oSelf.limpaCampos();
    oSelf.getDados();
  };

  if ($F('cboAlunos') != '') {
    oSelf.getDados();
  }
};
