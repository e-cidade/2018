var DBViewPergunta = function(elemento) {

  this.elemento                  = elemento || document.createElement('fieldset');
  this.elemento.className        = 'questionario_pergunta';
  this.codigo                    = null;
  this.id                        = null;
  this.label                     = null;
  this.tipoResposta              = null;
  this.tipo                      = null;
  this.ordem                     = null;
  this.obrigatoria               = false;
  this.ativo                     = false;
  this.formato                   = null;
  this.mascara                   = null;
  this.respostaDefault           = null;
  this.respostas                 = new Collection().setId('codigo');
  this.respondida                = false;
  this.oElementLabel             = null;
  this.elementoRespostasObjetiva = 'radio';
};

DBViewPergunta.makeFromObject = function(oPergunta, oElemento) {
  
  var oDBViewPergunta = new DBViewPergunta(oElemento);
  oDBViewPergunta.setCodigo(oPergunta.codigo);
  oDBViewPergunta.setId(oPergunta.id);
  oDBViewPergunta.setLabel(oPergunta.label);
  oDBViewPergunta.setTipo(oPergunta.tipo);
  oDBViewPergunta.setElementoRespostasObjetiva(oPergunta.elementoPerguntaObjetiva);
  oDBViewPergunta.setTipoResposta(oPergunta.tipo_resposta);
  oDBViewPergunta.setOrdem(oPergunta.ordem);
  oDBViewPergunta.setObrigatoria(oPergunta.obrigatoria);
  oDBViewPergunta.setAtivo(oPergunta.ativo);
  oDBViewPergunta.setFormato(oPergunta.formato);
  oDBViewPergunta.setMascara(oPergunta.mascara);  
  
  
  if (oPergunta.tipo_resposta == 1 && oPergunta.elementoPerguntaObjetiva == 'select') {  
    
    var aRespostas = oPergunta.respostas;    
    var resposta = {
      id: 'select_'+oPergunta.codigo,      
      options : [],
      label   : '',
      permiteTexto:false,
      codigo: 0,
      valor: '',
      peso: 0
    };    
    for (respostaPergunta of aRespostas) {
      resposta.options.push(respostaPergunta);
      if (respostaPergunta.valor != 0) {
        resposta.valor = respostaPergunta.valor; 
      }
    }    
    oPergunta.respostas = [resposta];
  }
  
  oPergunta.respostas.forEach(function(oResposta) {    
    
    oResposta.pergunta = oDBViewPergunta;
    var resposta       = DBViewResposta.makeFromObject(oResposta).show(oDBViewPergunta.elemento);
    oDBViewPergunta.respostas.add(resposta);
  });

  return oDBViewPergunta;
};

DBViewPergunta.prototype = {

  'setCodigo': function(codigo) {
    this.codigo = codigo;  
  },

  'setId': function(id) {
    this.id = id;  
  },

  'setLabel': function(label) {
    
    var oLabel = document.createElement('label');
    oLabel.innerHTML = label;    

    this.oElementLabel = oLabel;
    this.elemento.appendChild(oLabel);
    
    this.label = label;  
  }, 

  'setTipo': function(tipo) {
    this.tipo = tipo;  
  },

  'setOrdem': function(ordem) {
    this.ordem = ordem;  
  },

  'setObrigatoria': function(obrigatoria) {
    this.obrigatoria = obrigatoria;  
  },

  'setAtivo': function(ativo) {
    this.ativo = ativo;  
  },

  'setFormato': function(formato) {
    this.formato = formato;  
  },

  'setMascara': function(mascara) {
    this.mascara = mascara;  
  },

  'setTipoResposta' : function(tipoResposta) {
    this.tipoResposta = tipoResposta;
  },

  'setRespondida' : function(respondida){
    this.respondida  = !!respondida;//Forca boolean
  },

  'get' : function(atrib) {
    return this[atrib];
  },
  
  'setElementoRespostasObjetiva' : function(tipo) {
    this.elementoRespostasObjetiva = tipo;
  },

  'getElementoRespostasObjetiva' : function() {
    return this.elementoRespostasObjetiva;
  },
  'getDados' : function(respostaSelecionada) {

    var aRespostas = [];

    for ( var oResposta of this.respostas.get()) {
      
      aRespostas.push(oResposta.getDados());

      if(respostaSelecionada == oResposta.codigo) {
        return {
          codigo    : this.codigo,
          respostas : oResposta.getDados()
        }
      }
    }

    return {
      codigo    : this.codigo,
      respostas : aRespostas
    }
  },

  'show': function(oDestino) {

    this.fixLabelElement();
    oDestino.appendChild(this.elemento);
  },

  'fixLabelElement' :function () {

    if (this.tipoResposta == 2 || this.tipoResposta == 1 && this.elementoRespostasObjetiva == 'select') {

      var aRespostas = this.respostas.get();
      this.oElementLabel.htmlFor = aRespostas[0].getId();
    }
  },

  'limparResposta' : function() {
     this.setRespondida(false);
     for (oResposta of this.respostas.get()) {
       switch (this.tipoResposta) {

         case '3':
         case '1':

           oResposta.elemento.getElement().checked = false;
           break;
         default:
           oResposta.elemento.setValue('');
          break;
       }
     }
   }
 };
