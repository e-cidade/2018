/**
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 *
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

/**
 * View para definir se a triagem deve ser alterada ou incluida uma nova
 */
DBViewOpcoesSalvar = function () {

  this.iOpcao = null;

  this.fCallbackOpcoes = function() {
    return true;
  };

  var oFieldsetDescricao = document.createElement("fieldset");
  oFieldsetDescricao.style.textAlign = "left";

  var oBotaoNovo    = document.createElement("input");
  oBotaoNovo.type   = "button";
  oBotaoNovo.id     = "btnNovaTriagem";
  oBotaoNovo.value  = "Novo";

  var oBotaoAlterar    = document.createElement("input");
  oBotaoAlterar.type   = "button";
  oBotaoAlterar.id     = "btnAlterarTriagem";
  oBotaoAlterar.value  = "Alterar";

  var oBotaoCancelar    = document.createElement("input");
  oBotaoCancelar.type   = "button";
  oBotaoCancelar.id     = "btnCancelarTriagem";
  oBotaoCancelar.value  = "Cancelar";

  var oMensagemNovo = document.createElement("p");
  oMensagemNovo.innerHTML = "<strong>Novo:</strong> Gerar nova triagem.";
  oFieldsetDescricao.appendChild( oMensagemNovo );

  var oMensagemAlterar = document.createElement("p");
  oMensagemAlterar.innerHTML = "<strong>Alterar:</strong> Alterar triagem já existente.";
  oFieldsetDescricao.appendChild( oMensagemAlterar );

  var oMensagemCancelar = document.createElement("p");
  oMensagemCancelar.innerHTML = "<strong>Cancelar:</strong> Retornar para a triagem.";
  oFieldsetDescricao.appendChild( oMensagemCancelar );

  /**
   * Container com todos elementos do formulário
   * @type {HTMLDivElement}
   */
  this.oDivContainer = document.createElement('div');
  this.oDivContainer.addClassName('container');
  this.oDivContainer.style.width = "95%";
  this.oDivContainer.appendChild( oFieldsetDescricao );
  this.oDivContainer.appendChild( oBotaoNovo );
  this.oDivContainer.appendChild( oBotaoAlterar );
  this.oDivContainer.appendChild( oBotaoCancelar );
}

/**
 * define uma função de callback para ser executada ao selecionar uma das opções (Novo, Alterar e Cancelar)
 * @param {function} fFunction
 */
DBViewOpcoesSalvar.prototype.setCallbackOpcoes = function( fFunction ) {
  this.fCallbackOpcoes = fFunction;
};

DBViewOpcoesSalvar.prototype.criaJaneja = function() {

  var oSelf    = this;
  this.oWindow = new windowAux( 'oWindowOpcoesSalvar', 'Opções para salvar a triagem', 350, 220 );

  this.oWindow.setContent( this.oDivContainer );
  this.oWindow.setShutDownFunction( function () {

    oSelf.oWindow.destroy();
  });

  $('btnNovaTriagem').onclick = function() {
    oSelf.opcaoSelecionada(1);
  };

  $('btnAlterarTriagem').onclick = function() {
    oSelf.opcaoSelecionada(2);
  };

  $('btnCancelarTriagem').onclick = function() {
    oSelf.opcaoSelecionada(0);
  };

  this.oWindow.show( null, null, true );
};

/**
 * Define a opção selecionada
 * @param {integer} iOpcao
 */
DBViewOpcoesSalvar.prototype.setOpcao = function ( iOpcao ) {
  this.iOpcao = iOpcao;
};

/**
 * Define qual opção foi selecoinada
 * @return {void}
 */
DBViewOpcoesSalvar.prototype.opcaoSelecionada = function( iOpcao ) {

  var oSelf = this;
  oSelf.setOpcao( iOpcao );
  oSelf.fCallbackOpcoes();
  oSelf.oWindow.destroy();
};

/**
 * cria a window
 * @return {void}
 */
DBViewOpcoesSalvar.prototype.show = function () {
  this.criaJaneja();
};