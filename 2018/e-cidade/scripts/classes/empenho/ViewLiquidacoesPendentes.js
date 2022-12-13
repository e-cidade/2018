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


require_once('estilos/grid.style.css');
require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/EmissaoRelatorio.js');

/**
 * @param sNomeInstancia
 * @param aMovimentos
 * @param fnCallback
 * @constructor
 */
ViewLiquidacoesPendentes = function(sNomeInstancia, aMovimentos, fnCallback) {

  this.sNomeInstancia = sNomeInstancia;
  this.aMovimentos    = aMovimentos;
  this.aMovimentosPendentes = [];
  this.oGridEmpenhos  = null;
  this.oWindowAux     = null;
  this.fnCallback     = fnCallback;
  this.lWindowAux     = false;

  const COLUNA_JUSTIFICATIVA = 6;
  const PATH_RPC = 'emp4_liquidacoespendentes.RPC.php';

  /**
   * Constrói a window com as informações dos empenhos
   */
  this.buildContainer = function() {

    var self = this;
    var oDivContainer = document.createElement('div');
    oDivContainer.className = 'container';
    oDivContainer.style.width = '98%';

    var oFieldset = document.createElement('fieldset');
    var oLegend   = document.createElement('legend');
    oLegend.innerHTML = "<b>Empenhos</b>";

    var oDivGridEmpenho = document.createElement('div');
    oDivGridEmpenho.id  = 'divGridEmpenho';

    oFieldset.appendChild(oDivGridEmpenho);
    oFieldset.appendChild(oLegend);
    oDivContainer.appendChild(oFieldset);

    var sTitulo = "Justificativa da Suspensão da Ordem de Classificação";
    var sHelp   = "Existem Ordens de Pagamento melhor classificadas conforme a Ordem Cronológica de Pagamentos. Para prosseguir justifique as notas abaixo.";

    this.lWindowAux = true;
    this.oWindowAux = new windowAux('oWindowAux', sTitulo, 900, 550);
    this.oWindowAux.setContent(oDivContainer);
    this.oWindowAux.setShutDownFunction(
      function () {
        self.lWindowAux = false;
        self.oWindowAux.destroy();
      }
    );

    var oMessageBoard = new DBMessageBoard('oMessageBoard', sTitulo, sHelp, this.oWindowAux.getContentContainer());
    oMessageBoard.show();
    this.oWindowAux.show();

    this.oGridEmpenhos = new DBGrid(this.sNomeInstancia+'.oGridEmpenhos');
    this.oGridEmpenhos.setHeader(['Empenho', 'Nota', 'Movimento', 'Valor', 'Vencimento', 'Forma Pgto', 'Justificativa']);
    this.oGridEmpenhos.setCellAlign(['center', 'center', 'center', 'right', 'center', 'center', 'center']);
    this.oGridEmpenhos.setCellWidth(['10%', '10%', '10%', '10%', '10%', '10%', '40%']);
    this.oGridEmpenhos.setHeight(250);
    this.oGridEmpenhos.show(oDivGridEmpenho);


    var oBtnSalvar     = document.createElement('input');
    oBtnSalvar.type    = 'button';
    oBtnSalvar.value   = 'Salvar';
    oBtnSalvar.onclick = function() { self.salvar(); };
    oBtnSalvar.style.marginRight = '10px';
    oBtnSalvar.style.marginTop = '10px';

    var oBtnCancelar     = document.createElement('input');
    oBtnCancelar.type    = 'button';
    oBtnCancelar.value   = 'Cancelar';
    oBtnCancelar.onclick = function() {
      self.lWindowAux = false;
      self.oWindowAux.destroy();
    };

    var oBtnImprimir = document.createElement('input');
    oBtnImprimir.type = 'button';
    oBtnImprimir.value = 'Imprimir';
    oBtnImprimir.style.marginLeft = '10px';
    oBtnImprimir.style.marginTop = '10px';
    oBtnImprimir.onclick = function () {

      var sConsulta  = JSON.stringify(self.aMovimentos);
      var oRelatorio = new EmissaoRelatorio('emp2_empenhoclassificacaocredor002.php');
      oRelatorio.setMethod('post');
      oRelatorio.addParameter('movimentos', sConsulta);
      oRelatorio.open();
    };

    oDivContainer.appendChild(oBtnSalvar);
    oDivContainer.appendChild(oBtnCancelar);
    oDivContainer.appendChild(oBtnImprimir);
    this.carregarGrid();
  };

  /**
   * Salva as informações
   * @returns {boolean}
   */
  this.salvar = function() {

    var iTotalLinhas = this.oGridEmpenhos.aRows.length;
    var self = this;
    var aMovimentosParaSalvar = [];
    var iTotalNaoJustificados = 0;

    for (var iRow = 0; iRow < iTotalLinhas; iRow++) {

      var aLinha = self.oGridEmpenhos.aRows[iRow];

      if (aLinha.aCells[COLUNA_JUSTIFICATIVA].getValue().trim() == "") {

        iTotalNaoJustificados++;
        continue;
      }

      var oMovimento = {
        codigo_nota      : aLinha.aCells[1].getValue(),
        codigo_movimento : aLinha.aCells[2].getValue(),
        justificativa    : encodeURIComponent(tagString(aLinha.aCells[COLUNA_JUSTIFICATIVA].getValue()))
      };
      aMovimentosParaSalvar.push(oMovimento);
    }

    var sMensagemConfirmacao = "Deseja prosseguir sem informar Justificativa da Suspensão da Ordem de Classificação para as notas listadas?";
    if (iTotalNaoJustificados > 0 && !confirm(sMensagemConfirmacao)) {
      return false;
    }

    if (empty(aMovimentosParaSalvar)) {

      this.encerraComponente({}, false);
      return false;
    }

    new AjaxRequest(PATH_RPC,
                    {exec:'salvarJustificativa', movimentos:aMovimentosParaSalvar},
                  self.encerraComponente)
      .setMessage('Aguarde, salvando justificativas...')
      .asynchronous(false)
      .execute();
  };

  /**
   * Fecha a view e chama a callback.
   * @type {function(this:ViewLiquidacoesPendentes)}
   */
  this.encerraComponente = function(oRetorno, lErro) {

    if (lErro) {

      alert(oRetorno.mensagem.urlDecode());
      return false;
    }

    this.lWindowAux = false;
    this.oWindowAux.destroy();
    this.fnCallback();
  }.bind(this);

  /**
   * Carrega as informações dentro da GRID
   */
  this.carregarGrid = function () {

    this.oGridEmpenhos.clearAll(true);
    var self = this;

    this.aMovimentosPendentes.each(
      function (oMovimento, iIndice) {

        var aLinha = [
          oMovimento.numero_empenho,
          oMovimento.codigo_nota,
          oMovimento.codigo_movimento,
          js_formatar(oMovimento.valor, 'f'),
          oMovimento.vencimento,
          oMovimento.forma_pagamento,
          "<input type='text' value='' id='txtMovimento_"+iIndice+"' style='width:100%' />"
        ];
        self.oGridEmpenhos.addRow(aLinha);
      }
    );
    this.oGridEmpenhos.renderRows();
  };

  /**
   * Busca os empenhos que necessitam de justificativa
   * @returns {boolean}
   */
  this.carregarEmpenhos = function () {

    var self = this;
    var lOcorreuErro = false;
    var oParametro = {
      exec : 'verificarOrdenacaoPagamento',
      movimentos : this.aMovimentos
    };

    new AjaxRequest(
      PATH_RPC,
      oParametro,
      function (oRetorno, lErro) {

        if (lErro) {
          lOcorreuErro = true;
          return alert(oRetorno.mensagem.urlDecode());
        }

        if (oRetorno.movimentos.length == 0) {
          return;
        }

        self.aMovimentosPendentes = oRetorno.movimentos;
      }
    ).setMessage('Aguarde, verificando ordenação de pagamento...')
     .asynchronous(false)
     .execute();

    if (lOcorreuErro) {
      return false;
    }

    return true;
  };

  /**
   * Apresenta ao usuário a window com as notas pendentes de pagamento
   * @returns {boolean}
   */
  this.show = function () {

    /**
     * Se ocorreu erro ao carregar empenhos não faz nada
     */
    if (!this.carregarEmpenhos()) {
      return false;
    }
    /**
     * Se nenhum movimento precisa de justificativa executa callback
     */
    if (this.aMovimentosPendentes.length == 0) {
      this.fnCallback();
      return false;
    }

    /**
     * Se retornou movimentos exibe janela
     */
    this.buildContainer();
  }
};
