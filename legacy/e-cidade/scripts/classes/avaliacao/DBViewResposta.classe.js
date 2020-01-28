/**
 * Representa um campo de digitação de uma resposta de uma pergunta de um grupo de perguntas de uma avaliacao
 *
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.27 $
 *
 */
(function(exports) {

  var DBViewResposta = function(elemento, tipo, label) {

    this.elemento = elemento || document.createElement("input");

    if(typeof tipo == 'string') {
      this.elemento.type = tipo;
    } else {
      this.elemento.type = 'text';
    }

    this.label = null;

    if(typeof label == 'string') {
      this.label           = document.createElement('label');
      this.label.innerHTML = label;
    }

    this.codigo            = null;
    this.id                = null;
    this.peso              = 0;
    this.itens             = [];
    this.valor             = null;
    this.permiteTexto      = false;
    this.pergunta          = null;
    this.wrapper           = document.createElement('div');
    this.wrapper.className = 'wrapper_resposta';
    this.elementoAuxiliar  = null;
    this.funcaoData        = null;
  };

  DBViewResposta.makeFromObject = function(object) {

    var sTipoElementoObjetiva = 'radio';
    var elemento = null;
    
    if (object.pergunta.getElementoRespostasObjetiva() == 'select' && object.pergunta.tipoResposta == 1) {
      
      elemento =  document.createElement('select');
      sTipoElementoObjetiva = 'select';
    }
    
    var resposta = new DBViewResposta(elemento);
    resposta.set("codigo", object.codigo);
    resposta.set("id", object.id);
    resposta.set("peso", object.peso);
    resposta.set("valor", object.valor);
    if (object.options) {
      resposta.set("itens", object.options);
    }
    resposta.set("permiteTexto", object.permiteTexto);
    resposta.set("pergunta", object.pergunta);
    resposta.setTipo(object.pergunta.tipo);    
    resposta.setLabel(object.label);

    if(object.pergunta.tipoResposta == 1) { // tipo_resposta == 1 - Pergunta do tipo objetiva, só permite uma resposta
      resposta.setTipo(sTipoElementoObjetiva);
      
    }

    if(object.pergunta.tipoResposta == 3) { // tipo_resposta == 2 - Pergunta do tipo multipla escolha, permite mais de uma resposta
      resposta.setTipo("checkbox");
    }
    resposta.setElementoAuxiliar();

    return resposta;
  };

  DBViewResposta.setFuncaoInputData = function(fFunction) {
    this.funcaoData = fFunction;
  };

  DBViewResposta.getFuncaoInputData = function(oElemento) {
    if (typeof this.funcaoData !== 'undefined') {
      return this.funcaoData(oElemento);
    }
    return new DBInputDate(oElemento);
  };

  DBViewResposta.prototype = {

    'set' : function(chave, valor) {

      this[chave] = valor;
      return this;
    },

    'setTipo' : function(tipo) {

      if(typeof tipo == 'string') {
        this.elemento.type = tipo;
      } else {
        this.elemento.type = 'text';
      }
    },

    'setLabel' : function(label) {

      if(typeof label == 'string') {
        
        this.label           = document.createElement('label');
        this.label.innerHTML = label;
      }
    },

    'setElementoAuxiliar' : function(element) {

      if(this.permiteTexto) {
        this.elementoAuxiliar = element || document.createElement('input');
        this.elementoAuxiliar.type          = 'text';
        this.elementoAuxiliar.name          = this.id+"_auxiliar";
        this.elementoAuxiliar.className     = 'elemento-auxiliar';
        this.elementoAuxiliar.value         = this.valor;
        this.elementoAuxiliar.style.display = 'none';
      }
    },

    'get' : function(chave) {

      return this[chave];
    },
    'getId' : function() {

      return this.id;
    },

    'getPergunta' : function() {
      return this.pergunta;
    },

    'getElemento' : function() {
      return this.elemento;
    },

    'getElementoAuxiliar' : function() {
      return this.elementoAuxiliar;
    },

    'getDados' : function () {

      var resposta;

      resposta = {
        codigo        : this.codigo,
        valor         : this.elemento.getValue(),
        valorAuxiliar : null
      };

      if(this.getElemento().type == 'radio' || this.getElemento().type == 'checkbox') {

        resposta.valor = 0;

        if(this.getElemento().isChecked()) {

          resposta.valor = 1;

          if(this.get('permiteTexto')) {
            resposta.valorAuxiliar = this.get('elementoAuxiliar').getValue();
          }
        }
        
      }
      
      if (this.getElemento().type == 'select') {        
      
        var elemento = this.getElemento().getElement();
        if (elemento.value != '') {
          
          resposta.codigo = elemento.options[elemento.selectedIndex].getAttribute('codigo');
          resposta.valor  = 1;
        }
      }      
      return resposta;
    },

    'aplicarCSS' : function() {

      this.elemento.addClassName('');      
      if (this.elemento.type === 'select-one') {        
        this.elemento.style.width = '100%';
      }
    },

    'aplicarAtributos' : function() {      
      

      this.elemento.id = this.id;
      if(this.elemento.type == 'radio') {        
        this.elemento.name = this.pergunta.id;

      } else {

        this.elemento.name = this.id;

        if(this.elemento.type != 'checkbox') {
          this.elemento.value = this.valor;
        }
      }
            
      if(this.elemento.type == 'radio' || this.elemento.type == 'checkbox') {
        
        this.label.setAttribute('for', this.id);

        if(this.valor) {
          this.elemento.checked = true;
        }
      }
      
      if(this.valor || !(this.pergunta.obrigatoria)) {
        this.pergunta.respondida = true;
      }
    },

    'setCallbacks' : function() {

      if (this.elemento.type =='select') {
  
        this.elemento.getElement().addEventListener('change', this.verificarPreenchimento.bind(this));
        
      }
      if(this.elemento.type != 'radio') {

        if(this.elemento.type != 'checkbox') {

          if(this.pergunta.get('obrigatoria')) {
            this.elemento.getElement().addEventListener('blur', this.verificarPreenchimento.bind(this));
          }
        }
      }
      
      if(this.elemento.type == 'radio' || this.elemento.type == 'checkbox') {
        this.elemento.getElement().addEventListener('click', this.verificarPreenchimento.bind(this));
      }
    },

    'modificaElementoAuxiliar' : function() {

      if(!this.getElementoAuxiliar()) {
        return false;
      }
      var elemento = this.getElemento().getElement();
      this.getElementoAuxiliar().getElement().style.display = elemento.checked ? '' : 'none';
      return true;
    },

    'verificarPreenchimento' : function(event) {
      

      this.modificaElementoAuxiliar();      
      if(this.elemento.type == 'radio' || this.elemento.type == 'checkbox') {

        this.pergunta.setRespondida(false);

        for(var oResposta of this.pergunta.respostas.get()) {

          oResposta.modificaElementoAuxiliar();

          if(!oResposta.getElemento().getElement().checked) {
            continue;
          }
          this.pergunta.setRespondida(true);
        }

      } else {

        this.pergunta.setRespondida(false);

        if (this.elemento.getValue()) {
          this.pergunta.setRespondida(true);
        }
      }

      /**
       * Validações dos DBInput's
       */
      if(!this.elemento.isValid()) {
        this.pergunta.setRespondida(false);
      }

      this.elemento.inputElement.removeClassName('input-erro-validacao');
      this.pergunta.elemento.childNodes.item('label').innerHTML = this.pergunta.elemento.childNodes.item('label').innerHTML.replace(/(.*)(\<.*?\<\/span>)/g, "$1");

      if(!this.pergunta.respondida) {
        
        if(this.elemento.type != 'radio' && this.elemento.type != 'checkbox') {
          this.elemento.inputElement.addClassName('input-erro-validacao');
        }        
        this.pergunta.elemento.childNodes.item('label').innerHTML += '<span class="erro-validacao">* Informação inconsistente</span>';
      }
    },

    'renderizarElemento' : function(tipo) {
      
      this.aplicarCSS();
      this.aplicarAtributos();
      
      switch (tipo + '') {

        case "2":// 2=>'CEP'
          this.elemento = new DBInputCep(this.elemento);
          break;
        case "3":// 3=>'CNPJ'
          this.elemento = new DBInputCNPJ(this.elemento);
          break;
        case "4":// 4=>'CPF'
          this.elemento = new DBInputCpf(this.elemento);
          break;
        case "5":// 5=>'Data'
          this.elemento = DBViewResposta.getFuncaoInputData(this.elemento);
          break;
        case "6":// 6=>'Inteiro'
         this.elemento = new DBInputInteger(this.elemento);
          break;
        case "7":// 7=>'Telefone'
          this.elemento = new DBInputTelefone(this.elemento);
          break;
        case "8":// 8=>'Valor
          this.elemento = new DBInputValor(this.elemento);
          break;
        case "9":
          this.elemento = new DBInputHora(this.elemento);
          break;
        case 'checkbox':
          this.elemento = new DBCheckBox(this.elemento);
          break;
        case 'radio':          
          this.elemento = new DBRadio(this.elemento);
          break;
        case 'select':          
          
          this.elemento = new DBSelect(this.elemento);
          this.elemento.inputElement.id = this.id;          
          for (item of this.itens) {
            
            var attributes = [{'name':'codigo','value':item.codigo}];
            this.elemento.addOption(item.codigo, item.label, attributes);
           }
            
          break;
        default:
          if(this.pergunta.tipoResposta == 2) { // tipo_resposta = 2 - Trata-se de pergunta descritiva
            this.elemento = new DBInput(this.elemento);
          }
      }

      if(this.permiteTexto) {
        if(typeof this.elementoAuxiliar == 'object') {
          this.elementoAuxiliar = new DBInput(this.elementoAuxiliar);
        }
      }        
      this.elemento.setValue(this.valor);
      this.setCallbacks();
    },

    'show' : function(destino) {

      this.wrapper.appendChild(this.elemento);

      if(this.pergunta.tipoResposta != 2) { // tipo_resposta = 1|3 - Para perguntas objetivas e multipla escolha

        if(this.label !== null) {
          this.wrapper.appendChild(this.label);
        }

        if(this.permiteTexto) {

          if(typeof this.elementoAuxiliar == 'object') {
            this.wrapper.appendChild(this.elementoAuxiliar);
          }
        }
      }
      destino.appendChild(this.wrapper);

      if(this.pergunta.tipoResposta == 2) {
        this.renderizarElemento(this.pergunta.tipo); // Renderiza o elemento de acordo com o tipo informado
      } else {       
        
        if(this.pergunta.tipoResposta == 1) {
          
          this.renderizarElemento(this.pergunta.elementoRespostasObjetiva);
        }
        if(this.pergunta.tipoResposta == 3) {
          this.renderizarElemento('checkbox');
        }
      }
      return this;
    }
  };

  exports.DBViewResposta = DBViewResposta;
  return DBViewResposta;

})(this);
