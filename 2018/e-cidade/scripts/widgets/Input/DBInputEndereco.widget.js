/**
 * Para que o input funcione corretamente, os assets são necessários:
 * 
 *   AjaxRequest.js
 *   dbautocomplete.widget.js
 *   dbcomboBox.widget.js
 *   dbmessageBoard.widget.js
 *   dbtextField.widget.js
 *   dbViewCadEndereco.classe.js
 *   windowAux.widget.js
 *   
 *   Input/DBInput.widget.js
 */
(function (exports, DBInput, DBViewCadastroEndereco, AjaxRequest) {


  /**
   * Componente que um input e seus complementos para selecao de enderecos
   *
   * @constructor
   * 
   * @author Rafael Nery <rafael.nery@dbseller.com.br>
   * @return DBInputEndereco
   */
  var DBInputEndereco = function (elemento, useEnderecoMunicipio) {

    this.viewEndereco          = null;
    this.inputAuxiliar         = DBInputEndereco.criarInputAuxiliar();
    this.callbackPesquisa       = DBInputEndereco.buscarEndereco.bind(this);
    var callbackLancar         = function(event){

      this.viewEndereco = null;
      $('wndEnderecopri') && $('wndEnderecopri').remove();

      this.viewEndereco =  new DBViewCadastroEndereco('pri', null, this.inputElement.value);
      this.viewEndereco.setObjetoRetorno(this.inputElement);
      this.viewEndereco.setEnderecoMunicipio(this.useEnderecoMunicipio);
      this.viewEndereco.setTipoValidacao(2);
      this.callbackPesquisa       = DBInputEndereco.buscarEndereco.bind(this);
      this.viewEndereco.setCallBackFunction(this.callbackPesquisa);
      this.viewEndereco.show();
    }.bind(this);

    this.botaoLancar           = DBInputEndereco.criarBotao(callbackLancar);

    this.useEnderecoMunicipio = !!useEnderecoMunicipio;
    this.type                 = 'hidden'; // Campo que fica com o código fica oculto
    return DBInput.apply(this, [elemento]);
  };

  /**
   * Registrando Herança
   */
  DBInputEndereco.prototype = Object.create(DBInput.prototype, {

    /**
     * Personalizações do elemento e seu ecodssistema
     */
    '__infect' : DBInput.extend(function() {
      
      this.inputElement.parentNode.appendChild(this.inputAuxiliar);
      this.inputElement.parentNode.appendChild(this.botaoLancar);

      this.setValue(this.inputElement.value);
      
      DBInput.prototype.__infect.apply(this, arguments);
    }),

    /**
     * Obriga a entrada do valor ser um numero inteiro
     */
    'setValue' : DBInput.extend(function(valor) {

      valor = valor ? +valor : valor;

      DBInput.prototype.setValue.apply(this, [valor]);

      if(valor) {
        this.callbackPesquisa();
      }
      return this;
    }),

    /**
     * Torna o retorno do valor ser um inteiro
     */
    'getValue' : DBInput.extend(function() {
      return +DBInput.prototype.getValue.apply(this, arguments) || null;
    })
  });
 
  /**
   * Campo onde será mostrado o endereço
   */
  DBInputEndereco.criarInputAuxiliar = function() {
    var input         = document.createElement("input");
    input.type        = "text";
    input.className   = 'readOnly';
    input.disabled    = true;
    input.style.width = 'calc(100% - 55px)';
    return input;
  };
 
  DBInputEndereco.criarBotao = function(callback) {

    var botao         = document.createElement("input");
    botao.type        = "button";
    botao.value       = "Editar";
    botao.style.width = '50px';
    botao.addEventListener("click", callback);
    return botao;
  };
    
  DBInputEndereco.prototype.constructor = DBInputEndereco;

  DBInputEndereco.buscarEndereco = function() {

    var iCodigoEndereco = this.getValue();

    if (!iCodigoEndereco) {
      return;
    }

    var callback = function(oRetorno) {

      if (oRetorno.endereco) {

        var sEndereco = oRetorno.endereco[0].srua.urlDecode();
        sEndereco    += ",  nº " + oRetorno.endereco[0].snumero.urlDecode();
        sEndereco    += " - "    + oRetorno.endereco[0].scomplemento.urlDecode();
        sEndereco    += " - "    + oRetorno.endereco[0].sbairro.urlDecode();
        sEndereco    += " - "    + oRetorno.endereco[0].smunicipio.urlDecode();
        sEndereco    += " - "    + oRetorno.endereco[0].ssigla.urlDecode();

        this.inputElement.value  = oRetorno.endereco[0].iendereco; // Settando direto pois no setValue entraria em loop infinito
        this.inputAuxiliar.value = sEndereco;
      }
    };

    var oEndereco = {
      "exec"            : 'findEnderecoByCodigo',
      "iCodigoEndereco" : iCodigoEndereco
    }

    this.inputElement.value  = '';
    this.inputAuxiliar.value = '';

    new AjaxRequest(
      'prot1_cadgeralmunic.RPC.php', 
      oEndereco, 
      callback.bind(this)
    ).execute();
  };

  /**
   * Registrando módulo na memoria para execução
   */
  exports.DBInputEndereco = DBInputEndereco;
  return DBInputEndereco;

})(this, DBInput, DBViewCadastroEndereco, AjaxRequest);
