/**
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

const REFERENCIA_FIXA                        = 1;
const REFERENCIA_NUMERICA                    = 2;
const REFERENCIA_SELECIONAVEL                = 3;
const MENSAGENS_LANCAMENTO_EXAME_LABORATORIO = 'saude.laboratorio.LancamentoExameLaboratorio.';
const MSG_LANCAMENTOLABCONFERENCIA           = 'saude.laboratorio.db_frmlab_conferencia.'

require_once("scripts/arrays.js");
LancamentoExameLaboratorio = function(sInstance) {

  var oSelf                = this;
  this.iCodigoExame        = '';
  this.iCodigoRequisicao   = '';
  this.aAtributos          = [];
  this.aAtributosFormula   = [];
  this.aAtributosCampos    = [];
  this.sNameInstance       = sInstance;
  this.lReadOnly           = false;
  this.lAbrirComoJanela    = false;
  this.aCIDs               = [];
  this.iCodigoProcedimento = '';
  this.iCIDConferido       = null;

  this.callbackAfterSalvar   = function() {return true;}
  this.callbackAfterConferir = function() {return true;}

  this.oElementoDivContainer = document.createElement("div");

  this.oElementoDivObservacao                  = document.createElement("div");
  this.oElementoDivObservacao.style.paddingTop = '10px';
  this.oElementoDivObservacao.style.display    = "none";

  this.oElementoFieldset           = document.createElement("fieldset");
  this.oElementoFieldset.className = 'separator';

  this.oLegend           = document.createElement("legend");
  this.oLegend.innerHTML = "<b>Atributos do Exame</b>";
  this.oElementoFieldset.appendChild(this.oLegend);

  this.oElementoDivGrid                 = document.createElement("div");
  this.oElementoDivGrid.style.textAlign = "center";

  this.oElementoDivBotao                 = document.createElement("div");
  this.oElementoDivBotao.style.textAlign = "center";

  this.oTextAreaObservacao      = document.createElement("textarea");
  this.oTextAreaObservacao.rows = 5;
  this.oTextAreaObservacao.cols = 100;
  this.oTextAreaObservacao.id   = 'textAreaObservacao';

  this.oElementoFieldsetObservacao = document.createElement("fieldset");

  this.oLegendObservacao           = document.createElement("legend");
  this.oLegendObservacao.innerHTML = "<b>Observação</b>";

  this.oElementoDivCID                  = document.createElement("div");
  this.oElementoDivCID.style.paddingTop = '10px';
  this.oElementoDivCID.style.display    = 'none';
  this.oFieldsetCID                     = document.createElement('fieldset');
  this.oFieldsetCIDLegend               = document.createElement('legend');
  this.oFieldsetCIDLegend               = document.createElement('legend');
  this.oCIDLabel                        = document.createElement('label');
  this.oCIDLabel.setAttribute('for', 'iCodigoCID');
  this.oCIDSelect                       = document.createElement('select');
  this.oCIDSelect.id                    = 'iCodigoCID';
  this.oCIDSelect.addClassName('field-size-max');
  this.oFieldsetCIDLegend.innerHTML = 'CID';
  this.oFieldsetCID.appendChild(this.oFieldsetCIDLegend);
  this.oCIDLabel.appendChild( this.oCIDSelect );
  this.oFieldsetCID.appendChild(this.oCIDLabel);
  this.oElementoDivCID.appendChild(this.oFieldsetCID);

  this.oElementoFieldsetObservacao.appendChild( this.oLegendObservacao );
  this.oElementoFieldsetObservacao.appendChild( this.oTextAreaObservacao );
  this.oElementoDivObservacao.appendChild( this.oElementoFieldsetObservacao );

  this.oBtnSalvar       = document.createElement("input");
  this.oBtnSalvar.value = 'Salvar';
  this.oBtnSalvar.type  ='button';
  this.oBtnSalvar.observe('click', function() {
    oSelf.salvar( true );
  });
  document.body.observe('keydown', function(event){

    if (event.ctrlKey && event.which == 13 ) {
      oSelf.oBtnSalvar.click();
    }
  });

  this.oBtnFechar                  = document.createElement("input");
  this.oBtnFechar.id               = 'btnFechar';
  this.oBtnFechar.value            = 'Fechar';
  this.oBtnFechar.type             = 'button';
  this.oBtnFechar.style.marginLeft = '5px';
  this.oBtnFechar.style.display    = 'none';

  this.oBtnConfirmar                   = document.createElement("input");
  this.oBtnConfirmar.id                = 'btnConfirmar';
  this.oBtnConfirmar.value             = 'Confirmar Resultado';
  this.oBtnConfirmar.type              = 'button';
  this.oBtnConfirmar.style.marginRight = '5px';
  this.oBtnConfirmar.style.display     = 'none';

  this.oBtnConfirmar.observe('click', function() {

    oSelf.setCallbackSalvar( oSelf.confirmarExame );
    oSelf.salvar(false);
  });

  this.oBtnLancarMedicamento                  = document.createElement("input");
  this.oBtnLancarMedicamento.id               = 'btnMedicamento';
  this.oBtnLancarMedicamento.value            = 'Medicamentos';
  this.oBtnLancarMedicamento.type             = 'button';
  this.oBtnLancarMedicamento.style.marginLeft = '5px';
  this.oBtnLancarMedicamento.setAttribute('disabled', 'disabled');

  this.oElementoDivBotao.appendChild(this.oBtnConfirmar);
  this.oElementoDivBotao.appendChild(this.oBtnSalvar);
  this.oElementoDivBotao.appendChild(this.oBtnFechar);
  this.oElementoDivBotao.appendChild(this.oBtnLancarMedicamento);

  this.oElementoFieldset.appendChild(this.oElementoDivGrid);
  this.oElementoFieldset.appendChild(this.oElementoDivCID);
  this.oElementoFieldset.appendChild(this.oElementoDivObservacao);
  this.oElementoFieldset.appendChild(this.oElementoDivBotao);

  this.oElementoDivContainer.appendChild(this.oElementoFieldset);
  this.oElementoDivContainer.appendChild(this.oElementoDivBotao);

  this.sUrlRPC                      = 'lab4_digitacaoexame.RPC.php';
  this.sUrlRPCConferencia           = 'lab4_conferencia.RPC.php';
  oGridAtributosExame               = new DBGrid("gridAtributos");
  oGridAtributosExame.nameInstance  = 'oGridAtributos';
  oGridAtributosExame.setHeader(['Codigo', 'Atributo', '%', 'VA', "Referência", "codigo_ref"]);
  oGridAtributosExame.setCellWidth(['5', '35', '10', '20', '30', '1']);
  oGridAtributosExame.setCellAlign(['right']);
  oGridAtributosExame.setHeight(300);
  oGridAtributosExame.aHeaders[5].lDisplayed = false;
};

/**
 * Renderiza   o componente
 * @param oElement
 */
LancamentoExameLaboratorio.prototype.show = function(oElement) {

  var oSelf = this;

  oElement.appendChild(this.oElementoDivContainer);
  oGridAtributosExame.show(this.oElementoDivGrid);
};

/**
 * Define o codigo da requisicao de Exame
 * @param iRequisicaoExame
 */
LancamentoExameLaboratorio.prototype.setRequisicao = function(iRequisicaoExame) {

  this.iCodigoRequisicao = iRequisicaoExame;
  this.getAtributosDoExame();
  this.oBtnLancarMedicamento.removeAttribute('disabled');

  if ( !this.lAbrirComoJanela ) {
    this.lancarMedicamentos();
  }
};

/**
 * Carrega todos os atributos do exame
 * @private
 */
LancamentoExameLaboratorio.prototype.getAtributosDoExame = function() {

  var oParam = {'exec':'getAtributosDoExame', 'requisicao' : this.iCodigoRequisicao, 'lConferencia' : this.lReadOnly};
  var oSelf  = this;

  js_divCarregando( _M( MENSAGENS_LANCAMENTO_EXAME_LABORATORIO + 'buscando_atributos' ), 'msgBox');
  new Ajax.Request(oSelf.sUrlRPC,
                   {
                     method:'post',
                     parameters:'json='+Object.toJSON(oParam),
                     onComplete: function(oResponse) {

                       js_removeObj('msgBox');
                       var oRetorno     = eval("("+oResponse.responseText+")");

                       $('textAreaObservacao').value = oRetorno.sObservacao.urlDecode();
                       oSelf.aAtributos              = oRetorno.atributos;
                       // decodifica string da titulação
                       for ( var oAtributo of oSelf.aAtributos ) {
                         oAtributo.titulacao = oAtributo.titulacao.urlDecode();
                       }
                       oSelf.preencherAtributos();
                     }
                   });
};

/**
 * Preenche os dados dos atributos
 * @private
 */
LancamentoExameLaboratorio.prototype.preencherAtributos = function() {

  oGridAtributosExame.clearAll(true);
  var oSelf = this;

  this.aAtributos.each(function(oAtributo, iSeq) {

    var sDescricaoAtributo = oAtributo.descricao.urlDecode()
    // quando atributo recebe valor, transforma em um link
    if (oAtributo.tipo == 2) {
      sDescricaoAtributo = '<a href="#" id="atributo_obs_'+oAtributo.codigo+'" title="Clique para lançar titulação." > ' + sDescricaoAtributo + '</a>';
    }

    var aLinha = [];
    aLinha[0]  = oAtributo.codigo;
    aLinha[1]  = strRepeat("&nbsp;&nbsp;", oAtributo.nivel) + sDescricaoAtributo;

    aLinha[2]  = oSelf.inputPercentual(oAtributo);
    aLinha[3]  = oSelf.inputValorAbsoluto(oAtributo);
    aLinha[4]  = '';
    if (oAtributo.referencia != '') {

      var sStringReferencia = '';
      switch (oAtributo.referencia.tipo) {

        case REFERENCIA_NUMERICA:

          sStringReferencia  = "("+oAtributo.referencia.faixanormalminimo+" Até ";
          sStringReferencia += oAtributo.referencia.faixanormalmaximo +") "+oAtributo.referencia.unidade.urlDecode();
          break;
      }
      aLinha[4] = sStringReferencia;
    }

    aLinha[5] = oAtributo.codigoreferencia;
    oGridAtributosExame.addRow(aLinha);
    if (oAtributo.tipo == 1) {
      oGridAtributosExame.aRows[iSeq].sStyle += ";font-weight:bold";
    }
  });

  oGridAtributosExame.renderRows();
  var oPrimeiroAtributo = null;
  oSelf.aAtributos.each(function(oAtributo) {

    // implementa ação ao link do atributo para lançar titulação
    if ( $("atributo_obs_"+oAtributo.codigo) ) {
      $("atributo_obs_"+oAtributo.codigo).addEventListener('click', oSelf.lancarTitulacao.bind(this, oAtributo, oSelf ));
    }

    if (!$("atributo_"+oAtributo.codigo)) {
      return;
    }

    if ( $("atributo_"+oAtributo.codigo) ) {

      $("atributo_"+oAtributo.codigo).addEventListener('paste'   , oSelf.bloqueiaEventos.bind(this, $("atributo_"+oAtributo.codigo)) );
      $("atributo_"+oAtributo.codigo).addEventListener('drop'    , oSelf.bloqueiaEventos.bind(this, $("atributo_"+oAtributo.codigo)) );
      $("atributo_"+oAtributo.codigo).addEventListener('change'  , oSelf.validaValorInformado.bind(this, $("atributo_"+oAtributo.codigo), oAtributo, oSelf) );
      $("atributo_"+oAtributo.codigo).addEventListener('keypress', oSelf.validaValorInformado.bind(this, $("atributo_"+oAtributo.codigo), oAtributo, oSelf) );

      if (oPrimeiroAtributo == null) {
        oPrimeiroAtributo = $("atributo_"+oAtributo.codigo);
      }
    }

    if ( $("atributo_"+oAtributo.codigo+"_percentual") ) {

      $("atributo_"+oAtributo.codigo+"_percentual").observe('keypress', function (event) {

        if ( !js_teclas(event) ) {

          event.preventDefault();
          event.stopImmediatePropagation();
          return false;
        }
      });

      $("atributo_"+oAtributo.codigo+"_percentual").addEventListener('paste', oSelf.bloqueiaEventos.bind(this, $("atributo_"+oAtributo.codigo+"_percentual")) );
      $("atributo_"+oAtributo.codigo+"_percentual").addEventListener('drop' , oSelf.bloqueiaEventos.bind(this, $("atributo_"+oAtributo.codigo+"_percentual")) );
      if (oPrimeiroAtributo == null) {
        oPrimeiroAtributo = $("atributo_"+oAtributo.codigo+"_percentual");
      }
    }

    if (oAtributo.tiporeferencia != REFERENCIA_NUMERICA) {
      return;
    }
    if ($("atributo_"+oAtributo.codigo)) {
      oSelf.sinalizaInput(oAtributo.codigo, $("atributo_" + oAtributo.codigo));
    }


  });

  oPrimeiroAtributo.focus();

};

/**
 * Cria um input para informacao do valor percentual
 *
 * @private
 * @param oAtributo
 * @returns {string}
 */
LancamentoExameLaboratorio.prototype.inputPercentual = function(oAtributo) {

  if (oAtributo.referencia == '') {
    return '';
  }

  var sCampo         = '';
  var sBloqueioTela  = '';
  var sReadOnly      = '';
  //var sFuncaoCalculo = "onchange='"+this.sNameInstance+".calcularValorAbsoluto("+oAtributo.codigo+", this)';";
  var sFuncaoCalculo = "onchange='"+this.sNameInstance+".calcularPorcentagem("+oAtributo.codigo+", this)';";


  if (this.lReadOnly) {

   sBloqueioTela  = ' border:0px;';
   sFuncaoCalculo = '';
   sReadOnly      = 'readonly="readonly"';
  }


  if (oAtributo.referencia.tipo == REFERENCIA_NUMERICA &&
     (oAtributo.referencia.tipocalculo == 2) ) { // (oAtributo.referencia.tipocalculo == 1 || oAtributo.referencia.baseparacalculo) ) {

    if (oAtributo.referencia.baseparacalculo) {

      sReadOnly = 'readonly="readonly"';
      sFuncaoCalculo = '';
    }

    sCampo  = "<input class='campoAtributoExame' type='text' "+sReadOnly+" style='width:99%;text-align: right;"+sBloqueioTela+"'";
    sCampo += " id='atributo_"+oAtributo.codigo+"_percentual' "+sFuncaoCalculo;
    sCampo +=  " value='"+oAtributo.valorpercentual+"' >";
  } else if(oAtributo.referencia.baseparacalculo) {
    sCampo = '100';
  }

  return sCampo;
};

/**
 * Cria um input de texto numerico, validando seus intervalos
 * @private
 * @param oAtributo
 * @returns {string}
 */
LancamentoExameLaboratorio.prototype.inputValorNumerico = function(oAtributo) {

  if (oAtributo.referencia == '') {
    return '';
  }

  var oSelf    = this;
  var oInput   = new Element('input', {'type':'text', 'id':'atributo_'+oAtributo.codigo, 'value':oAtributo.valorabsoluto, 'onchange': this.sNameInstance + '.verificaFormulas('+oAtributo.codigo+', this)'});
  oInput.style = 'width:99%; text-align: right';

  if (oAtributo.referencia.tipocalculo == 1 || oAtributo.referencia.tipocalculo == 2) { // oAtributo.referencia.tipocalculo == 1
    oInput.setAttribute('readonly', 'readonly');
    oInput.style.border = '0px';

    // Verifica se o atributo é gerado a partir de uma fórmula (Valor Absoluto). Se sim, inclui o campo na lista para checagem futura
    if(oAtributo.referencia.tipocalculo == 1) {
      oSelf.aAtributosFormula.push(oAtributo);
    }
  }

  // Inclui na lista os campos adicionados ao GRID para futura referência via código_estrutural
  oSelf.aAtributosCampos[oAtributo.codigo_estrutural] = oAtributo;



  if (this.lReadOnly) {

    oInput.setAttribute('readonly', 'readonly');
    oInput.style.border = '0px';
  }


  return oInput.outerHTML;
};

/**
 * Cria uminput de digitacao livre
 *
 * @private
 * @param oAtributo
 * @returns {string}
 */
LancamentoExameLaboratorio.prototype.inputValorFixo = function(oAtributo) {

  var oInput = new Element('input', {type:'text', value:oAtributo.valorabsoluto.urlDecode(), style:'width:98%'});
  oInput.setAttribute("id", 'atributo_'+oAtributo.codigo);

  if (this.lReadOnly) {

    oInput.style.border = "0px";
    oInput.setAttribute("readonly", "readonly");
  }

  return oInput.outerHTML;
};

LancamentoExameLaboratorio.prototype.comboBoxAtributos = function(oAtributo) {

  var sValorTexto = '';
  var sSelect = "<select style='width:100%' id='atributo_"+oAtributo.codigo+"'>";
  oAtributo.referencia.selecoes.each(function(oSelecao, iSeq) {

    var sSelected = '';
    if (oSelecao.codigo == oAtributo.valorabsoluto) {

      sSelected   = ' selected ';
      sValorTexto = oSelecao.nome.urlDecode();
    }
    sSelect += "<option value='"+oSelecao.codigo+"'"+sSelected+">"+oSelecao.nome.urlDecode()+"</option>";
  });

  sSelect += "</selectd>";
  if (this.lReadOnly) {
    sSelect = sValorTexto;
  }

  return sSelect;
};

/**
 * Realiza a validação dos valores digitados
 *
 * @private
 * @param iCodigoAtributo
 * @param oInput
 */
LancamentoExameLaboratorio.prototype.validaValores = function(iCodigoAtributo, oInput) {

  var oAtributo   = this.getAtributo(iCodigoAtributo);
  var oReferencia = oAtributo.referencia;
  var oSelf       = this;

  if (oReferencia == '' || oInput.value == '') {
    return;
  }

  var nReferenciaMinimo = oReferencia.faixaabsurdoinicio.replace(',', '.');
  var nReferenciaMaximo = oReferencia.faixaasurdomaximo.replace(',', '.');

  var nValor       = new Number(oInput.value).valueOf();
  var sValorMinimo = new Number(nReferenciaMinimo).valueOf();
  var sValorMaximo = new Number(nReferenciaMaximo).valueOf();

  if (nValor < sValorMinimo || nValor > sValorMaximo) {

    var sStringIntervalor = "("+sValorMinimo + " até ";
    sStringIntervalor    += sValorMaximo + ") " + oAtributo.referencia.unidade.urlDecode() + ' para ' + oAtributo.descricao.urlDecode();

    var oPropriedades        = {};
        oPropriedades.sValor = sStringIntervalor;
    alert( _M( MENSAGENS_LANCAMENTO_EXAME_LABORATORIO + 'fora_valores_absurdos', oPropriedades ) );
  }

  /**
   * Verifica se o valor informado para o percentual do atributo ultrapassa o valor de percentual do atributo base
   */
  if ( !this.calculaTotalPercentual( oAtributo.referencia.atributobase ) ) {

    alert( _M( MENSAGENS_LANCAMENTO_EXAME_LABORATORIO + 'valor_acima_porcentagem' ) );
    $('atributo_'+oAtributo.codigo+'_percentual').value = 0;
    $('atributo_'+oAtributo.codigo).value               = 0;
  }

  oSelf.sinalizaInput(iCodigoAtributo, oInput);
  if (oAtributo.referencia.baseparacalculo) {

    oSelf.aAtributos.each(function(oAtributoCalculo, iSeq) {

      if (oAtributoCalculo.referencia == '' || (oAtributoCalculo.referencia.tipocalculo != 2)) {
        return ;
      }

      if (oAtributoCalculo.referencia.atributobase == iCodigoAtributo) {
        oSelf.calcularPorcentagem(oAtributoCalculo.codigo, $('atributo_'+oAtributoCalculo.codigo+'_percentual'));
      }
    });
  }
};

/**
 * Realiza a marcacas das cores dos textos conforme seu resultado
 *
 * @private
 * @param iCodigoAtributo
 * @param oInput
 */
LancamentoExameLaboratorio.prototype.sinalizaInput = function(iCodigoAtributo, oInput) {

  var oAtributo   = this.getAtributo(iCodigoAtributo);
  var oReferencia = oAtributo.referencia;
  if (oReferencia == '' || oInput.value == '') {
    return ;
  }

  var nNormaMinimo = oReferencia.faixanormalminimo.replace(',', '.');
  var nNormaMaximo = oReferencia.faixanormalmaximo.replace(',', '.');

  var nValor         = new Number(oInput.value).valueOf();
  var sValorMinimo   = new Number(nNormaMinimo).valueOf();
  var sValorMaximo   = new Number(nNormaMaximo).valueOf();
  oInput.style.color = 'green';

  if (nValor < sValorMinimo || nValor > sValorMaximo) {
    oInput.style.color = 'red';
  }
};

/**
 * Pesquisa um atributro pelo codigo
 *
 * @private
 * @param iCodigoAtributo
 * @returns {string}
 */
LancamentoExameLaboratorio.prototype.getAtributo = function(iCodigoAtributo) {

  var oAtributoRetorno = '';
  this.aAtributos.each(function(oAtributo) {

    if (oAtributo.codigo == iCodigoAtributo) {

      oAtributoRetorno = oAtributo;
      return;
    }
  });

  return oAtributoRetorno;
};

/**
 * Checa se existem campos com fórmula fazendo referência ao campo atual
 *
 * @private
 * @param iAtributo
 * @param oInput
 */
LancamentoExameLaboratorio.prototype.verificaFormulas = function(iAtributo, oInput) {

  var oAtributoCurrent  = this.getAtributo(iAtributo);
  var sCodigoEstrutural = oAtributoCurrent.codigo_estrutural;
  var _this = this;

  this.aAtributosFormula.each(function(oAtributo) {
    var sFormula = oAtributo.referencia.formula;

    // Extrai os códigos estruturais da fórmula
    var sPattern = /([0-9]+\.[0-9]+\.[0-9]+)/g;
    var aResults = sFormula.match(sPattern);

    var sFormulaComVariaveis = sFormula;

    if (aResults != null) {

      aResults.each(function(codigo_estrutural){
        if (typeof _this.aAtributosCampos[codigo_estrutural] != 'undefined' && $('atributo_' + _this.aAtributosCampos[codigo_estrutural].codigo) != undefined) {
          var sValorSubstituir = $F('atributo_' + _this.aAtributosCampos[codigo_estrutural].codigo);
          if(sValorSubstituir != "") {
            sFormulaComVariaveis = sFormulaComVariaveis.replace(codigo_estrutural, sValorSubstituir);
          }
        }
      });

      if (sFormulaComVariaveis.match(sPattern) == null) {

        var valorFinal = eval(sFormulaComVariaveis);
        if(oAtributo.referencia.casasdecimais > 0) {

          valorFinal = parseFloat(valorFinal).toFixed(oAtributo.referencia.casasdecimais);
        }

        $('atributo_' + oAtributo.codigo).value = valorFinal;
        _this.validaValores(oAtributo.codigo, $('atributo_' + oAtributo.codigo));
      }
    }

  });
};


/**
 * Realiza o calculo do valor absoluto do atributo (NÃO ESTÁ SENDO UTILIZADA. VER calcularPorcentagem())
 *
 * @private
 * @param iAtributo
 * @param oInput
 */
LancamentoExameLaboratorio.prototype.calcularValorAbsoluto = function(iAtributo, oInput) {

  var oAtributo  = this.getAtributo(iAtributo);
  if (oAtributo.referencia.atributobase == '') {
    return ;
  }

  var nValorBase          = new Number($F('atributo_'+oAtributo.referencia.atributobase)).valueOf();
  var nPercentualBase     = new Number($F('atributo_'+oAtributo.referencia.atributobase+"_percentual")).valueOf();
  var nPercentualDigitado = new Number(oInput.value).valueOf();
  var nValorAbsoluto      = new Number((nPercentualDigitado * nValorBase) / nPercentualBase);

  $('atributo_'+iAtributo).value = nValorAbsoluto;
  this.validaValores(iAtributo, $('atributo_'+iAtributo));
};

/**
 * Realiza o calculo do valor absoluto do atributo
 *
 * @private
 * @param iAtributo
 * @param oInput
 */
LancamentoExameLaboratorio.prototype.calcularPorcentagem = function(iAtributo, oInput) {

  var oAtributo  = this.getAtributo(iAtributo);
  if (oAtributo.referencia.atributobase == '') {
    return ;
  }

  if (oInput == '') {
    return;
  }
  var nValorBase          = new Number($F('atributo_'+oAtributo.referencia.atributobase)).valueOf();
  var nPercentualDigitado = new Number(oInput.value).valueOf();
  var nValorAbsoluto      = new Number((nValorBase / 100) * nPercentualDigitado);

  $('atributo_'+iAtributo).value = nValorAbsoluto;
  this.validaValores(iAtributo, $('atributo_'+iAtributo));
};

/**
 * Cria um componente de entrada conforme sua referencia
 *
 * @private
 * @param oLinha
 * @returns {string}
 */
LancamentoExameLaboratorio.prototype.inputValorAbsoluto = function(oLinha) {

  if (oLinha.tipo == 1) {
    return '';
  }

  var sCampo = '';
  switch (oLinha.referencia.tipo) {

    case REFERENCIA_NUMERICA:

      sCampo = this.inputValorNumerico(oLinha);
      break;

    case REFERENCIA_FIXA:

      sCampo = this.inputValorFixo(oLinha);
      break;

    case REFERENCIA_SELECIONAVEL:

      sCampo = this.comboBoxAtributos(oLinha);
      break;
  }
  return sCampo;
};

/**
 * Salva os dados do exame
 * @returns {boolean}
 */
LancamentoExameLaboratorio.prototype.salvar = function( lExibeMensagem ) {

  if ( lExibeMensagem ) {

    if ( !confirm( _M( MENSAGENS_LANCAMENTO_EXAME_LABORATORIO + 'confirma_valores' ) ) ) {
      return false;
    }
  }

  var oSelf      = this;
  var aAtributos = [];
  aAtributosGrid = oGridAtributosExame.aRows.each(function(oLinha) {

    var oAtributo = oSelf.getAtributo(oLinha.aCells[0].getValue());
    if (oAtributo.tipo == 1) {
      return;
    }

    var oAtributoValor = {

      iCodigoAtributo   : oLinha.aCells[0].getValue(),
      nValorPercentual  : parseFloat(oLinha.aCells[2].getValue().trim()),
      iCodigoReferencia : oLinha.aCells[5].getValue().trim(),
      nValorAbsoluto    : encodeURIComponent(tagString(oLinha.aCells[3].getValue().trim())),
      sTitulacao        : encodeURIComponent(tagString(oAtributo.titulacao))
    };

    aAtributos.push(oAtributoValor);
  });

  var oParam = {
    exec         :'salvarResultadoExame',
    iCodigoExame : this.iCodigoRequisicao,
    sObservacao  : encodeURIComponent( tagString(this.oTextAreaObservacao.value)),
    aAtributos   : aAtributos
  };

  js_divCarregando( _M( MENSAGENS_LANCAMENTO_EXAME_LABORATORIO + 'salvando_exame' ), 'msgBox');
  new Ajax.Request(oSelf.sUrlRPC,
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: function(oResponse) {

        js_removeObj('msgBox');
        var oRetorno     = eval("("+oResponse.responseText+")");

        if ( lExibeMensagem ) {
          alert(oRetorno.message.urlDecode());
        }
        oSelf.callbackAfterSalvar(oRetorno);
      }
    });
};

LancamentoExameLaboratorio.prototype.setReadOnly = function(lReadOnly) {

  this.lReadOnly                = lReadOnly;
  this.oBtnSalvar.style.display = '';
  this.oBtnSalvar.disabled      = lReadOnly;

  if (lReadOnly) {
    this.oBtnSalvar.style.display = 'none';
  }
};

/**
 * Monta uma WindowAux e agrega a gridAtributos a ela, abrindo a grid em uma nova janela
 * @param  integer iLancamentoExame Código da RequisicaoExame
 */
LancamentoExameLaboratorio.prototype.abrirComoJanela = function( iLancamentoExame ) {

  this.lAbrirComoJanela = true;

  var oSelf = this;

  if ($('wndLancamentoExame')) {
    return false;
  }

  this.oWindowLancamentoExame = new windowAux('wndLancamentoExame', 'Lançamento de Exames', 800, 650);

  oSelf.oWindowLancamentoExame.setShutDownFunction(function() {
    oSelf.oWindowLancamentoExame.destroy();
  });

  var sConteudo  = '<div style="height:78%;width:97%;">';
      sConteudo += '    <div id="ctnGridResultado"></div>';
      sConteudo += '</div>';

  this.oWindowLancamentoExame.setContent(sConteudo);

  var sMensagemExame = _M( MENSAGENS_LANCAMENTO_EXAME_LABORATORIO + 'dados_exame' );
  new DBMessageBoard(
                      'msgLancamentoExame',
                      'Dados do Exame',
                      sMensagemExame,
                      oSelf.oWindowLancamentoExame.getContentContainer()
                     );

  if ( this.aCIDs.length ) {

    this.aCIDs.forEach( function( oCID ) {

      var oCIDOption = document.createElement('option');
      oCIDOption.value     = oCID.iCodigo;
      oCIDOption.innerHTML = oCID.sCID + " - " + oCID.sNome.urlDecode();

      if ( oSelf.iCIDConferido == oCID.iCodigo ){
        oCIDOption.selected = true;
      }

      oSelf.oCIDSelect.appendChild(oCIDOption);
    });

    this.oElementoDivCID.style.display = '';
  }

  this.oBtnConfirmar.style.display = '';
  this.oBtnFechar.style.display = '';


  oSelf.setRequisicao(iLancamentoExame);
  this.show($('ctnGridResultado'));
  this.oWindowLancamentoExame.show();
  this.lancarMedicamentos();

  this.oBtnFechar.observe("click", function() {
    oSelf.oWindowLancamentoExame.destroy();
  });

};

/**
 * Seta se o campo observação deve ser mostrado ou não
 * @param  {boolean} lMostraCampoObservacao
 */
LancamentoExameLaboratorio.prototype.mostraCampoObservacao = function( lMostraCampoObservacao ) {

  if( lMostraCampoObservacao ) {
    this.oElementoDivObservacao.style.display = '';
  }
};

LancamentoExameLaboratorio.prototype.bloqueiaEventos = function (oElement, oEvent) {

  var aType = ['paste', 'drop'];

  if ( aType.in_array(oEvent.type) ) {

    oElement.value = '';
    oEvent.preventDefault();
    oEvent.stopImmediatePropagation();
  }
};

LancamentoExameLaboratorio.prototype.validaValorInformado = function (oElement, oAtributo, oSelf, oEvent) {

  switch( oAtributo.referencia.tipo ) {

    case REFERENCIA_FIXA:
    case REFERENCIA_SELECIONAVEL:

      break
    case REFERENCIA_NUMERICA:

      if (oEvent.type == 'keypress' && !js_teclas(oEvent)) {

        oEvent.preventDefault();
        oEvent.stopImmediatePropagation();
        return false;
      }
      if (oEvent.type == 'change') {
        oSelf.validaValores(oAtributo.codigo, oElement);
      }
      break;
  }
};

/**
 * Soma a quantidade de porcentagem informada para todos os atributos que referenciam o atributo base informado e valida
 * se o total somado ultrapassa o valor percentual do atributo base
 * @param  {integer} iAtributoBase Código do Atributo Base
 * @return {boolean}
 */
LancamentoExameLaboratorio.prototype.calculaTotalPercentual = function( iAtributoBase ) {

  var oAtributoBase    = this.getAtributo( iAtributoBase );
  var iTotalPercentual = 0;

  this.aAtributos.each(function(oAtributo) {

    if (oAtributo.referencia.atributobase == oAtributoBase.codigo) {

      if ( $('atributo_'+oAtributo.codigo+'_percentual') && $('atributo_'+oAtributo.codigo+'_percentual').value != '' ) {
        iTotalPercentual += new Number( $('atributo_'+oAtributo.codigo+'_percentual').value );
      }
    }
  });

  if ( iTotalPercentual > oAtributoBase.valorpercentual ) {
    return false;
  }
  return true;
};

LancamentoExameLaboratorio.prototype.setCallbackSalvar = function(sFunction) {
  this.callbackAfterSalvar = sFunction;
};

LancamentoExameLaboratorio.prototype.setCallbackConferir = function(sFunction) {
  this.callbackAfterConferir = sFunction;
}

LancamentoExameLaboratorio.prototype.clear = function(sFunction) {

  $('textAreaObservacao').value = '';
  oGridAtributosExame.clearAll(true);

  this.aAtributos        = [];
  this.aAtributosFormula = [];
  this.aAtributosCampos  = [];
};

LancamentoExameLaboratorio.prototype.lancarMedicamentos = function (){

  var oSelf = this;

  this.oBtnLancarMedicamento.observe('click', function(){

    var oMedicamento = new LancarMedicamentoExame('oMedicamento', oSelf.iCodigoRequisicao);
    oMedicamento.show();
    if (oSelf.lAbrirComoJanela) {

      oMedicamento.setParentWindowAux(oSelf.oWindowLancamentoExame);
    }

  });
};

LancamentoExameLaboratorio.prototype.setCIDs = function ( aCIDs ){
   this.aCIDs = aCIDs;
};

LancamentoExameLaboratorio.prototype.setProcedimento = function ( iCodigoProcedimento ){
   this.iCodigoProcedimento = iCodigoProcedimento;
};

LancamentoExameLaboratorio.prototype.confirmarExame = function() {

  var oSelf      = this,
      oParametro = {};

  oParametro.exec       = 'salvarConferencia';
  oParametro.iCodigo    = $F('la22_i_codigo');
  oParametro.lConferido = true;
  oParametro.aExames    = [];

  var oExame                    = {};
  oExame.iCodigoRequisicaoExame = this.iCodigoRequisicao;
  oExame.iCodigoCID             = this.oCIDSelect.value;
  oExame.iProcedimento          = this.iCodigoProcedimento;
  oParametro.aExames.push(oExame);

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.asynchronous = false;
  var oCID = null;

  if ( oExame.iCodigoCID != '' ) {

    var aDadosCID = this.oCIDSelect.options[this.oCIDSelect.selectedIndex].text.split(' - ');

    oCID = {
      'sEstruturalCidConferido' : aDadosCID[0],
      'sNomeCidConferido'       : aDadosCID[1]
    };
  }

  oRequest.onComplete   = function( oAjax ) {

    js_removeObj("msgBoxB");

    var oRetorno = eval( "(" + oAjax.responseText + ")" );
    alert( oRetorno.sMensagem.urlDecode() );

    if ( oRetorno.iStatus == '2' ) {
      return false;
    }

    oSelf.callbackAfterConferir( oCID );
  };
  js_divCarregando( _M( MSG_LANCAMENTOLABCONFERENCIA + "aguarde_salvando_conferencia" ), "msgBoxB");
  new Ajax.Request( oSelf.sUrlRPCConferencia , oRequest );
};

LancamentoExameLaboratorio.prototype.setCodigoCIDConferido = function( iCIDConferido ) {
  this.iCIDConferido = iCIDConferido;
};

/**
 * Monta a window para inserção da titulação
 * @param  {Object}                     oAtributo
 * @param  {LancamentoExameLaboratorio} oSelf
 * @return {void}
 */
LancamentoExameLaboratorio.prototype.lancarTitulacao = function ( oAtributo, oSelf ) {

  if ($("wndLancaTitulacaoAtributo")) {
    return;
  }

  var oWindowTitulacao = new windowAux("wndLancaTitulacaoAtributo", "Titulação", 450, 240 );
  oWindowTitulacao.setShutDownFunction(function () {
    oWindowTitulacao.destroy();
  });

  oWindowTitulacao.allowCloseWithEsc(true);

  var sConteudo    = "<div class='subcontainer'>                                                  \n";
      sConteudo   += "  <fieldset id='ctnTitulacao'>                                           \n";
      sConteudo   += "    <legend><label for='titulacaoAtributo'>Titulação</label></legend>   \n";
      sConteudo   += "    <textarea rows='4' cols='50' id='titulacaoAtributo' > </textarea>    \n";
      sConteudo   += "  </fieldset>                                                            \n";
      sConteudo   += "  <input type='button' value='Adicionar' id='salvarTitulacao' />  \n";
      sConteudo   += "</div>                                                                   \n";

  oWindowTitulacao.setShutDownFunction(function() {
    oWindowTitulacao.destroy();
  });

  var sHelpMsgBox  = ' Titular: <b>' +  oAtributo.descricao.urlDecode() + '</b> ';

  oWindowTitulacao.setContent(sConteudo);
  var oMessageBoard = new DBMessageBoard('msgBoardTitulacao'+oAtributo.codigo,
                                         'Titular: <b>' + oAtributo.descricao.urlDecode() + '</b> ',
                                         'Adicione a titulação e clique em salvar.',
                                         oWindowTitulacao.getContentContainer()
                                        );
  oWindowTitulacao.show();

  if ( $('wndLancaTitulacaoAtributo') ) {

    setTimeout(function () {
      $('wndLancaTitulacaoAtributo').style.zIndex = 99999;
    }, 1 );
  }

  $('titulacaoAtributo').value = undoTagString(oAtributo.titulacao);
  $('salvarTitulacao').addEventListener('click', oSelf.salvarTitulacao.bind(this, oAtributo.codigo, $('titulacaoAtributo'), oSelf, oWindowTitulacao ));
};

/**
 * Salva na classe a titulação informada
 * @param  {integer}                    iAtributo  código do atributo
 * @param  {HTMLTextAreaElement}        oTitulacao
 * @param  {LancamentoExameLaboratorio} oSelf
 * @param  {windowAux}                  oWindow
 * @return {void}
 */
LancamentoExameLaboratorio.prototype.salvarTitulacao = function ( iAtributo, oTitulacao, oSelf, oWindow ) {

  for (var oAtributo of oSelf.aAtributos) {

    if (oAtributo.codigo == iAtributo) {
      oAtributo.titulacao = oTitulacao.value;
    }
  }

  oWindow.destroy();
};