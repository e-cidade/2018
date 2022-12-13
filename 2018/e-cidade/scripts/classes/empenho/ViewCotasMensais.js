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

/**
 * @author Matheus Felini
 * @param sNameInstance
 * @param iNumeroEmpenho
 * @constructor
 */
ViewCotasMensais = function(sNameInstance, iNumeroEmpenho) {

  this.sNameInstance  = sNameInstance;
  this.sLegend        = 'Cotas Mensais';
  this.aCotasMensais  = [];
  this.readonly       = true;
  this.oGrid          = null;
  this.oWindowAux     = null;

  /**
   * Dados do Empenho
   * @type {object}
   */
  this.oEmpenho                    = {};
  this.oEmpenho.iNumeroEmpenho     = iNumeroEmpenho;
  this.oEmpenho.nValorTotalEmpenho = 0;

  const RPC = 'emp4_empenhocotasmensais.RPC.php';

  /**
   * Constrói o fieldset que englobará a grid.
   * @returns {HTMLElement}
   */
  this.buildFieldSet = function() {

    var oFieldSet = document.createElement('fieldset');
    oFieldSet.style.width = '100%';
    var oLegend = document.createElement('legend');
    oLegend.innerHTML = "<b>"+this.sLegend+"</b>";
    oFieldSet.appendChild(oLegend);
    return oFieldSet;
  };

  /**
   * Constrói a grid
   * @returns {null|*}
   */
  this.buildGrid = function() {

    this.oGrid = new DBGrid('oGrid');
    this.oGrid.nameInstance = this.sNameInstance+'.oGrid';
    this.oGrid.setHeader(['Mês', 'Valor']);
    this.oGrid.setCellWidth(['70%', '29%']);
    this.oGrid.setCellAlign(['left', 'right']);
    this.oGrid.setHeight('240');
    this.oGrid.hasTotalizador = true;
    return this.oGrid;
  };

  /**
   * @param {boolean} lReadOnly
   */
  this.setReadOnly = function(lReadOnly) {
    this.readonly = lReadOnly;
  };


  /**
   * Carrega as cotas mensais cadastradas para o empenho
   * @returns {Array}
   */
  this.loadCotasMensais = function() {

    var self = this;
    new AjaxRequest(
      RPC,
      {exec:'getCotasMensais', iNumeroEmpenho: this.oEmpenho.iNumeroEmpenho},
      function (oRetorno, lErro) {

        if (lErro) {
          alert(oRetorno.mensagem.urlDecode());
        } else {

          self.aCotasMensais = oRetorno.cotas;
          self.oEmpenho.nValorTotalEmpenho = oRetorno.valorempenho;
        }
      }
    ).setMessage('Aguarde, carregando cotas mensais...').asynchronous(false).execute();

    return this.aCotasMensais;
  };

  /**
   *
   * @param {HTMLElement} oDestino
   */
  this.show = function(oDestino) {

    this.loadCotasMensais();
    this.buildGrid();
    var oFieldset  = this.buildFieldSet();

    var oGridContainer = document.createElement('div');
    oGridContainer.id  = "DBGrid_"+this.sNameInstance;
    oFieldset.appendChild(oGridContainer);
    oDestino.appendChild(oFieldset);
    this.oGrid.show(oGridContainer);
    this.oGrid.clearAll(true);

    var self = this;

    this.aCotasMensais.each(
      function (oCota, iIndice) {

        var aLinha = [
          oCota.mes + " - " + oCota.nome_mes.urlDecode(),
          self.readonly ? js_formatar(oCota.valor, 'f') : self.getInputValor(oCota)
        ];
        self.oGrid.addRow(aLinha);
      }
    );

    this.oGrid.renderRows();
    this.atualizarTotalizador();

    if (!this.readonly) {

      var oDivBotao = document.createElement('div');
      oDivBotao.innerHTML = "<div class='container'><input type='button' value='Salvar' onClick='"+this.sNameInstance+".salvar();' /></div>";
      oDestino.appendChild(oDivBotao);
    }
  };

  /**
   * Abre uma janela para apresentar as informações ao usuário
   */
  this.abrirJanela = function () {

    var oContainerDestino = document.createElement('div');
    oContainerDestino.id  = "windowCotasMensais";
    oContainerDestino.style.width = "550";

    var self = this;
    this.oWindowAux = new windowAux('oWindowAux', 'Cotas Mensais', 600, 500);
    this.oWindowAux.setContent(oContainerDestino);
    this.oWindowAux.show();
    this.oWindowAux.setShutDownFunction(
      function () {
        self.oWindowAux.destroy();
      }
    );

    var sTitulo = "Manutenção de Cotas Mensais";
    var sHelp   = "Informe os valores referente ao mês correspondente. O valor da soma das cotas mensais não pode ser diferente do valor total do empenho.";
    var oMessageBoard = new DBMessageBoard('oMessageBoard', sTitulo, sHelp, this.oWindowAux.getContentContainer());
    oMessageBoard.show();
    this.show(oContainerDestino);
  };

  /**
   * Cria o input com os dados
   * @param {object} oDadosCota
   * @returns {string}
   */
  this.getInputValor = function (oDadosCota) {

    var nValorFormatado = js_formatar(oDadosCota.valor, 'f');
    var oInput = eval("oInput"+oDadosCota.mes+" = new DBTextField('mes_"+oDadosCota.mes+"', 'oInput"+oDadosCota.mes+"', '"+nValorFormatado+"');");
    oInput.addStyle('width', '100%');
    oInput.addStyle('border', '0');
    oInput.addStyle('text-align', 'right');
    oInput.addEvent("onKeyPress", "return js_teclas(event, this);");
    if (!oDadosCota.permitealterar) {
      oInput.setReadOnly(true);
    } else {

      oInput.addEvent("onChange", this.sNameInstance+".formatarValor(this);");
      oInput.addEvent("onFocus",  this.sNameInstance+".removerFormatacaoValor(this);");
      oInput.addEvent("onBlur",   this.sNameInstance+".formatarValor(this);");
    }
    return oInput.toInnerHtml();
  };


  /**
   * Salva os dados
   */
  this.salvar = function () {

    var aValores    = [];
    var nValorTotal = 0;
    this.oGrid.aRows.each(
      function(oRow, iIndice) {

        var oValores = {
          mes : (iIndice+1),
          valor : js_strToFloat(oRow.aCells[1].getValue())
        };

        nValorTotal += js_strToFloat(oRow.aCells[1].getValue());
        aValores.push(oValores);
      }
    );

    if (nValorTotal != 0 && js_round(nValorTotal, 2) != this.oEmpenho.nValorTotalEmpenho) {
      return alert("O valor total das cotas mensais deve ser igual ao valor total do empenho.");
    }

    new AjaxRequest(
      RPC,
      {exec:'salvar', iNumeroEmpenho: this.oEmpenho.iNumeroEmpenho, cotas:aValores, valortotal: js_round(nValorTotal, 2)},
      function (oRetorno, lErro) {
        alert(oRetorno.mensagem.urlDecode());
      }
    ).setMessage('Aguarde, salvando informações...').asynchronous(false).execute();
  };
};

ViewCotasMensais.prototype.formatarValor = function(oObjeto) {

  oObjeto.setValue(js_formatar(oObjeto.getValue(), 'f'));
  this.atualizarTotalizador();
};

ViewCotasMensais.prototype.removerFormatacaoValor = function(oObjeto) {

  oObjeto.setValue(js_strToFloat(oObjeto.getValue()));
};

ViewCotasMensais.prototype.atualizarTotalizador = function() {

  var nValorTotal = 0;
  this.oGrid.aRows.each(
    function(oRow, iIndice) {
      nValorTotal += js_strToFloat(oRow.aCells[1].getValue());
    }
  );
  $('TotalForCol1').innerHTML = js_formatar(nValorTotal, 'f');
};

