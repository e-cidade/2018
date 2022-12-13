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

/**
 * Cria uma window com o detalhamento do calculo do IPTU da matrícula selecionada
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @param {Object}  oMatricula matricula selecionada: {iMatricula: "123", sRazao: "ADEMAR ", nValorAtual: "1050.76", nValorNovo: "1050.76", iSituacao: "0" }
 * @param {Object}  oSchema    schema selecionado {sSchema: "importacao_21052017", iSchema: "2"}
 * @return {void}
 */
DBViewAtualizacaoCadastral = function(oMatricula, oSchema) {

  this.oMatricula  = oMatricula;
  this.oSchema     = oSchema;
  this.aMatriculas = [];

  this.oWindow = null;
  this.fCloseCallback  = function () {};
  this.fSalvarCallback = function () {};
};

/**
 * Adiciona um array de Objeto com todas matrículas selecionadas
 * @param {Array} aMatriculas array com as matrículas selecionadas, inclusive a atual
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.matriculasSelecionadas = function (aMatriculas) {
  this.aMatriculas = aMatriculas;
};

/**
 * Cria o html da janela
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.criaWindow = function () {

  if ( !empty(this.oWindow) ) {
    return null;
  }
  this.oWindow = new windowAux("wndAtualizarCadastro", "Atualizar cadastro IPTU", 1280, 600 );
  this.oWindow.allowCloseWithEsc(true);

  var sConteudo  = "<div class='subcontainer' style='width:100%;'>";
      sConteudo += "  <div class='_header'>";
      sConteudo += "    <label class='bold' for='codigoMatricula'>Matrícula:</label> ";
      sConteudo += "    <span id='btnEsquerda' style='background-image: url(\"imagens/seta_direita.gif\"); width:10px; display: inline-block; width: 10px; height: 10px; background-size: 10px; cursor:pointer;' ></span>";
      sConteudo += "    <input type='text' name='matricula' id='codigoMatricula' class='field-size2 readonly' disabled  > ";
      sConteudo += "    <span id='btnDireita' style='background-image: url(\"imagens/seta.gif\"); width:10px; display: inline-block; width: 10px; height: 10px; background-size: 10px; cursor:pointer;'></span>";
      sConteudo += "    <input type='text' name='razao' id='nome_razao' class='field-size8 readonly' disabled> ";
      sConteudo += "  </div>";
      sConteudo += "  <div id='conteudo' style='width:100%; '>";
      sConteudo += "    <div style='position: relative; width: 99%; margin: auto; '> ";

      sConteudo += "      <div id='a' style='width: 49%; float: left; display: inline-block; '> ";
      sConteudo += "        <fieldset style='height:400px;'>";
      sConteudo += "          <legend>Situação atual</legend>";
      sConteudo += "          <div id='conteudoAtual'>";
      sConteudo += "            <iframe style='width:100%; height:100%;' id='iFrameAtual' frameborder='0' ></iframe>";
      sConteudo += "          </div>";
      sConteudo += "        </fieldset>";
      sConteudo += "      </div>";

      sConteudo += "      <div id='b' style='width: 49%; float: rigth; display: inline-block;'>";
      sConteudo += "        <fieldset style='height:400px;'>";
      sConteudo += "          <legend>Situação após atualizações</legend>";
      sConteudo += "          <div id='conteudoAtualizado'>";
      sConteudo += "            <iframe style='width:100%; height:100%;' id='iFrameAtualizado' frameborder='0' ></iframe>";
      sConteudo += "          </div>";
      sConteudo += "        </fieldset>";
      sConteudo += "      </div>";

      sConteudo += "    </div>";
      sConteudo += "    <div style='clear:both'>";
      sConteudo += "      <input type='button' id='btnAtualizar' value='Atualizar' />";
      sConteudo += "      <input type='button' id='btnRejeitar'  value='Rejeitar' />";
      sConteudo += "      <input type='button' id='btnFechar'    value='Fechar' />";
      sConteudo += "    </div>";
      sConteudo += "  </div>";
      sConteudo += "</div>";

  this.oWindow.setShutDownFunction( function() {

    this.fCloseCallback();
    this.oWindow.destroy();
  }.bind(this));

  var sMsg = 'Permite atualizar ou rejeitar os dados do IPTU com base nos dados da importação do CIVITAS';


  this.oWindow.setContent(sConteudo);
  this.oMessageBoard = new DBMessageBoard( 'msgBoardIPTU', '', '', this.oWindow.getContentContainer() );
  this.oWindow.show();
};

/**
 * @param {function} fCallback função callback
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.setCallbackFechar = function(fCallback) {
  this.fCloseCallback = fCallback;
};

/**
 * @param {function} fCallback função callback
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.setCallbackSalvar = function(fCallback) {
  this.fSalvarCallback = fCallback;
}

/**
 * Renderiza a view e define suas ações
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.show = function() {

  this.criaWindow();
  this.atualizarDados();

  $('btnFechar').addEventListener('click', function() {

    this.fCloseCallback();
    this.oWindow.destroy();
  }.bind(this));

  $('btnEsquerda').addEventListener('click', function(){
    this.navegarEsquerda();
  }.bind(this));

  $('btnDireita').addEventListener('click', function(){
    this.navegarDireita();
  }.bind(this));

  $('btnAtualizar').addEventListener('click', function(){

    if(!confirm('Tem certeza que deseja Atualizar o cadastro da matrícula selecionada?' )) {
      return false;
    }

    this.atualizar();
  }.bind(this));

  $('btnRejeitar').addEventListener('click', function(){

    if(!confirm('Tem certeza que deseja Rejeitar a atualização cadastral da matrícula selecionada?' )) {
      return false;
    }

    this.rejeitar();
  }.bind(this));
};

/**
 * Seleciona a matrícula anterior no array
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.navegarEsquerda = function() {

  var iMatriculas = this.aMatriculas.length;
  for (var i = 0; i < iMatriculas; i++ ) {

    if ( this.aMatriculas[i].iMatricula == this.oMatricula.iMatricula ) {

      if ( i == 0 ) {
        break;
      }
      this.oMatricula = this.aMatriculas[i-1];
      this.atualizarDados();
      break;
    }
  }
};

/**
 * Seleciona a próxima matrícula no array
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.navegarDireita = function() {

  var iMatriculas = this.aMatriculas.length;
  for (var i = 0; i < iMatriculas; i++ ) {

    if ( this.aMatriculas[i].iMatricula == this.oMatricula.iMatricula ) {

      if ( i == (iMatriculas - 1) ) {
        break;
      }

      this.oMatricula = this.aMatriculas[i+1];
      this.atualizarDados();
      break;
    }
  }
}

/**
 * Atualiza os dados da view conforme matrícula selecionada
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.atualizarDados = function() {

  $('codigoMatricula').value = this.oMatricula.iMatricula;
  $('nome_razao').value      = this.oMatricula.sRazao;

  $('iFrameAtual').src      = 'con3_consultacalculoiptu.php?parametro='+this.oMatricula.iMatricula;
  $('iFrameAtualizado').src = 'con3_consultacalculoiptu.php?parametro='+this.oMatricula.iMatricula+'&schema='+this.oSchema.sSchema;
}

/**
 * Ação executada ao clicar no botão Rejeitar
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.rejeitar = function() {

  var oParametros = {
    exec              : 'rejeitar',
    aMatriculas       : [this.oMatricula.iMatricula],
    sNomeImportacao   : this.oSchema.sSchema,
    iCodigoImportacao : this.oSchema.iSchema
  }

  new AjaxRequest('cad4_recadastramento.RPC.php', oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }

    this.selecionarProximaMatricula();
  }.bind(this)).setMessage('Rejeitando cadastro...').execute();
};

/**
 * Ação executada ao clicar no botão Atualizar
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.atualizar = function() {

  var oParametros = {
    exec              : 'atualizar',
    aMatriculas       : [this.oMatricula.iMatricula],
    sNomeImportacao   : this.oSchema.sSchema,
    iCodigoImportacao : this.oSchema.iSchema
  }
  new AjaxRequest('cad4_recadastramento.RPC.php', oParametros, function(oRetorno, lErro){

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }
    this.selecionarProximaMatricula();
  }.bind(this)).setMessage('Atualizando cadastro...').execute();
}

/**
 * Após Atualizar/Rejeitar, seleciona a próxima matrícula no array. Se não haver, fecha a window
 * @return {void}
 */
DBViewAtualizacaoCadastral.prototype.selecionarProximaMatricula = function() {

  for (var i = 0; i < this.aMatriculas.length; i++) {

    if ( this.aMatriculas[i].iMatricula == this.oMatricula.iMatricula) {

      this.aMatriculas.splice(i, 1);
      if ( this.aMatriculas.length > 0 ) {

        if (!this.aMatriculas[i]) {
          i = 0;
        }
        this.oMatricula = this.aMatriculas[i];
        this.atualizarDados();
      }

      if ( this.aMatriculas.length == 0 ) {

        this.fCloseCallback();
        this.oWindow.destroy();
      }
      break;
    }
  }
}