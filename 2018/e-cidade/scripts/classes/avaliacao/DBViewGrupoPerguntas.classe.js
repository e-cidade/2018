var DBViewGrupoPerguntas = function(elemento) {

  this.elemento   = elemento || document.createElement('fieldset');
  this.codigo     = null;
  this.id         = null;
  this.label      = null;
  this.perguntas  = new Collection().setId('codigo');
  this.formulario = null;
};

DBViewGrupoPerguntas.makeFromObject = function (oGrupoPerguntas, formulario) {

  
  var oDBViewGrupoPerguntas = new DBViewGrupoPerguntas();
      oDBViewGrupoPerguntas.setCodigo(oGrupoPerguntas.codigo); 
      oDBViewGrupoPerguntas.setId(oGrupoPerguntas.id);
      oDBViewGrupoPerguntas.setLabel(oGrupoPerguntas.label);
      if (formulario != null) {
        oDBViewGrupoPerguntas.setFormulario(formulario);
      }

  oGrupoPerguntas.perguntas.forEach(function(oPergunta) {
    oDBViewGrupoPerguntas.addPergunta(oPergunta);
  });

  return oDBViewGrupoPerguntas;
};

DBViewGrupoPerguntas.prototype = {

  'setFormulario' : function(formulario) {
    this.formulario = formulario;
    return this;
  },

  'getFormulario' : function() {
    return this.formulario;
  },

  'getDados' : function(codigoPergunta) {

    var aPerguntas = [];

    for ( var oPergunta of this.perguntas.get()) {
      
      aPerguntas.push(oPergunta.getDados());

      if(codigoPergunta == oPergunta.codigo) {
        return {
          codigo     : this.codigo,
          perguntas  : oPergunta.getDados()
        };
      }
    }

    return {
      codigo     : this.codigo,
      perguntas  : aPerguntas
    };
  },

  'isValido' : function() {

    var lResponse = true;

    for(var oPergunta of this.perguntas.get()) {

      if(oPergunta.get('obrigatoria')) {

        if(!oPergunta.get('respondida')) {

          for( var oResposta of oPergunta.respostas.get()) {
            oResposta.verificarPreenchimento();
          }

          if (!oPergunta.get('respondida')) {
            lResponse = false;    
          }
        }
      }
    }
    return lResponse;
  },

  'addPergunta': function(oPergunta) {

    var oElemento           = document.createElement('fieldset');
        oElemento.className = (this.perguntas.get().length === 0) ? 'notseparator' : 'separator';
    
    oPergunta.elementoPerguntaObjetiva = this.getFormulario().getElementoRespostasObjetivas();    
    this.perguntas.add(DBViewPergunta.makeFromObject(oPergunta, oElemento));
  },  

  'setCodigo': function(codigo) {
    this.codigo = codigo;
    return this;
  },

  'setId': function(id) {
    this.id = id;
    return this;
  },

  'setLabel': function(label) {

    var oLegend = document.createElement('legend');
        oLegend.innerHTML = label;

    this.elemento.appendChild(oLegend);

    this.label = label;
    return this;
  },

  'getCodigo': function() {
    return this.codigo;
  },

  'getId': function() {
    return this.id;
  },

  'getLabel': function() {
    return this.label ;
  },

  'show': function(oDestino) {
    oDestino.appendChild(this.elemento);
    for (var oPergunta of this.perguntas.get()) {
      oPergunta.show(this.elemento);
    }
  },

  'getPerguntas' : function() {
    return this.perguntas.get();
  },

  'limparRespostas' : function() {
     for (oPergunta of this.getPerguntas()) {
       oPergunta.limparResposta();
     }
  }

};

