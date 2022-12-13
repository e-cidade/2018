function dbViewAvaliacao(iAvaliacao, iGrupoResposta, oNode) {

  var me              = this;
  this.iGrupoResposta = '';
  this.lEnable        = true;
  this.lRetornaCodigo = false;
  if (iGrupoResposta != null) {
    me.iGrupoResposta = iGrupoResposta;
  }
  this.iAvaliacao      = iAvaliacao;
  var iWidth           = document.body.getWidth()/1.4;
  me.view            = '';
  if (oNode != null) {
    me.view = oNode;
  }
  this.lMostrarMensagensSucesso = true;
  this.iDisableForm = false; // variavel opcional para desabilitar o form

  this.windowAvaliacao = new windowAux('wndAvaliacao'+iAvaliacao,'Avaliacao', iWidth);
  this.urlRPC          = 'con4_avaliacao.RPC.php';
  var sContent  = '<form id="frmAvaliacao'+iAvaliacao+'">';
  sContent     += '<div style="height:80%">';
  sContent     += ' <fieldset>';
  sContent     += '  <legend>';
  sContent     += '    <b>Grupo de Perguntas:</b>';
  sContent     += '  </legend>';
  sContent     += ' <table style="width:100%" border="0">';
  sContent     += '   <tr>';
  sContent     += '     <td id="ctnCboGrupos'+me.iAvaliacao+'">';
  sContent     += '     </td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' </fieldset>';
  sContent     += ' <fieldset style=";background-color:white;height:80%">';
  sContent     += ' <div style="overflow:scroll;overflow-x:hidden;height:100%">';
  sContent     += '   <table border="0" style="width:100%" cellspacing="0">';
  sContent     += '     <tbody id="rolPerguntas'+me.iAvaliacao+'">';
  sContent     += '     </tbody>';
  sContent     += '   </table>';
  sContent     += '   </fieldset>';
  sContent     += ' </div>';
  sContent     += ' <center>';
  sContent     += ' <input type="button" id="btnSalvarPerguntas'+me.iAvaliacao+'" value="Salvar Perguntas">';
  sContent     += ' <input type="button" id="btnSalvarAvaliacao'+me.iAvaliacao+'" value="Salvar Avaliacao">';
  sContent     += ' </center>';
  sContent     += '</div>';
  sContent     += '</form>';
  if (this.view === "") {
    this.windowAvaliacao.setContent(sContent);
  } else {
    oNode.innerHTML = sContent;
  }

  $('rolPerguntas'+me.iAvaliacao).style.height = (me.windowAvaliacao.getHeight() - 200)+"px";
  me.oCboGrupos = new DBComboBox("cboGrupos"+me.iAvaliacao, "oCboGrupos"+me.iAvaliacao);
  me.oCboGrupos.addItem("", "Selecione");
  me.oCboGrupos.addStyle("width","100%");
  me.oCboGrupos.show($('ctnCboGrupos'+me.iAvaliacao));
  if (this.view == "") {
	  this.oMessageBoard   = new DBMessageBoard('msgBoardAvaliacao'+this.iAvaliacao,
	                                          'Ajuda',
	                                          '',
	                                          $('windowwndAvaliacao'+me.iAvaliacao+'_content')
	                                          );
	  this.oMessageBoard.show();
  }
  this.windowAvaliacao.setShutDownFunction(function (){
    me.windowAvaliacao.destroy();
  });

  this.onComplete      = function (){
    me.windowAvaliacao.destroy();
  };
  this.show = function () {
    me.getDadosAvaliacao();
  };
  this.close = function() {
    me.windowAvaliacao.destroy();
  };

  this.mostrarMensagensSucesso  = function(lMostrar) {
    me.lMostrarMensagensSucesso = lMostrar;
  };
  this.getDadosAvaliacao = function () {

    var oParam            = new Object();
    oParam.iAvaliacao     = me.iAvaliacao;
    oParam.iGrupoResposta = me.iGrupoResposta;
    oParam.exec           = 'getDadosAvaliacao';
    var oAjaxRequest = new Ajax.Request (me.urlRPC,
                 {
                  method: 'post',
                  parameters:'json='+Object.toJSON(oParam),
                  onComplete: me.retornoGetDadosAvaliacao
                 }
                 );

    if (this.lRetornaCodigo) {
      oAjaxRequest.lAsynchronous = false;
    }
  };
  this.retornoGetDadosAvaliacao = function(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

      if (me.view == "") {

	      me.windowAvaliacao.setTitle(oRetorno.avaliacao.descricao.urlDecode());
	      me.oMessageBoard.setTitle(oRetorno.avaliacao.descricao.urlDecode());
	      me.oMessageBoard.setHelp(oRetorno.avaliacao.observacao.urlDecode());
      }
      me.iGrupoResposta = oRetorno.avaliacao.gruporespostas;
      /**
       * Incluimos os grupos da avaliacao
       */
      for (var iGrupo = 0; iGrupo < oRetorno.avaliacao.grupos.length; iGrupo++) {

        with (oRetorno.avaliacao.grupos[iGrupo]) {
          me.oCboGrupos.addItem(codigo, descricao.urlDecode());
        }
      }
      me.getPerguntasByGrupo();
      if (me.view == "") {
        me.windowAvaliacao.show();
      }
    } else {
     alert(oRetorno.message.urlDecode());
    }
  };

  this.getPerguntasByGrupo = function() {

    var oParam        = new Object();
    oParam.exec       = 'getPerguntasPorGrupo';
    oParam.iAvaliacao = me.iAvaliacao;
    oParam.iGrupo     = me.oCboGrupos.getValue();
    new Ajax.Request (me.urlRPC,
                      {
                       method: 'post',
                       parameters:'json='+Object.toJSON(oParam),
                       onComplete: me.retornoGetPerguntas
                       }
                      );
  };

  this.retornoGetPerguntas = function (oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

      $('rolPerguntas'+me.iAvaliacao).innerHTML = '';
      for (var iPer = 0; iPer < oRetorno.perguntas.length; iPer++) {

        with (oRetorno.perguntas[iPer]) {

          var oRowPergunta  = document.createElement("tr");
          var oRowResposta  = document.createElement("tr");
          var oPergunta     = document.createElement("TD");

          var sTextoPergunta   = "<input type='checkbox' class='perguntas' value='"+codigo+"' style='display:none'>";
          sTextoPergunta      += "<b>"+codigo+")</b> "+descricao.urlDecode();
          oPergunta.vAlign    = "top";
          oPergunta.innerHTML = sTextoPergunta;
          oPergunta.style.borderBottom    = "1px solid black";
          oPergunta.style.backgroundColor = "#EEEEE2";
          oPergunta.style.height          = "1em";
          oPergunta.setAttribute("respostas", codigo);

          var oResposta = document.createElement("TD");

          if (tipo == 1) {

            var sName       = "pergunta"+codigo;
            var sComponente = 'radio';

          } else if (tipo == 3) {

            var sName       = '';
            var sComponente = 'checkbox';
          }
          var sRepostas = "";
          var lChecked  = false;
          if (respostas.length == 1) {
            lChecked  = true;
          }

          for (var iResp = 0; iResp < respostas.length; iResp++) {

            with(respostas[iResp]) {

              var oInput  = document.createElement("input");
              oInput.type = sComponente;

              if (tipo == 1) {
                oInput.name = sName;
              }

              oInput.id        =  "resposta"+codigoresposta;
              oInput.className =  "resposta"+codigo;
              oInput.setAttribute("codigo", codigoresposta);
              oInput.value     =  codigoresposta;
              oInput.checked   =  marcada;

              if (tipo == 2) {

                oInput.checked       = true;
                oInput.style.display = 'none';
              }
              oInput.setAttribute("aceitatexto", aceitatexto);
              oResposta.appendChild(oInput);

              var oLabel       = document.createElement("label");
              oLabel.htmlFor   = "resposta"+codigoresposta;
              oLabel.innerHTML = descricaoresposta.urlDecode();
              oResposta.appendChild(oLabel);

              if (aceitatexto) {

                var oSpanInput       = document.createElement("span");
                oSpanInput.id        = 'spaninput'+codigoresposta;
                oSpanInput.className = 'texto_pergunta'+codigo;

                var sValueInput = textoresposta.urlDecode();
                eval("oTexto"+codigoresposta+" = new DBTextField('texto"+codigoresposta+"','oTexto"+codigoresposta+"','"+sValueInput+"','30');");
                eval("oTexto"+codigoresposta+".setExpansible(true, 150, 250);");
                eval("sInput = oTexto"+codigoresposta+".toInnerHtml();");

                oSpanInput.innerHTML = sInput;
                oResposta.appendChild(oSpanInput);

                oInput.onclick = function() {

                  if ($("resposta"+codigoresposta).checked) {
                    $("texto"+codigoresposta).focus();
                  }
                };
              }

              oResposta.appendChild(document.createElement("br"));
            }
          }

          oResposta.style.padding       = "5px";
          oResposta.style.paddingLeft   = "15pt";
          oRowPergunta.appendChild(oPergunta);
          oRowResposta.appendChild(oResposta);
          oRowResposta.style.height        = "1em";
          oRowPergunta.style.height        = "1em";

          $('rolPerguntas'+me.iAvaliacao).appendChild(oRowPergunta);
          $('rolPerguntas'+me.iAvaliacao).appendChild(oRowResposta);
        }
      }

          /**
           *Hack para o bug do Firefox 3.
           */
          var oRowHack = document.createElement("tr");
          oRowHack.style.height   = "auto";
          var oCellhack           = document.createElement("td");
          oCellhack.innerHTML = '&nbsp';
          oRowHack.appendChild(oCellhack);
          $('rolPerguntas'+me.iAvaliacao).appendChild(oRowHack);
    }
    if (!me.enabled()){
      me.disable();
    }
  };

  this.saveresposta = function() {

    var aPerguntas = $$('input.perguntas');
    var aRespostas = new Array();
    aPerguntas.each(function (oChkPergunta, idPergunta) {

      var aRespostasValidas = $$('input.resposta'+oChkPergunta.value);
      var oPergunta       = new Object();
      oPergunta.codigo    = oChkPergunta.value;
      oPergunta.respostas = new Array();
      aRespostasValidas.each(function (oCheckbox, id) {

        if (oCheckbox.checked) {

          var oResposta            = new Object();
          oResposta.codigoresposta = oCheckbox.value;
          oResposta.textoresposta  = '';
          oResposta.marcada        = true;
          if (oCheckbox.getAttribute('aceitatexto') && oCheckbox.getAttribute('aceitatexto') == "true") {
             oResposta.textoresposta = encodeURIComponent(tagString($F('texto'+oResposta.codigoresposta)));
          }
          oPergunta.respostas.push(oResposta);
        }
      });
      aRespostas.push(oPergunta);
    });
    js_divCarregando('Aguarde, salvando respostas..',"msgBox");
    var oParam        = new Object();
    oParam.exec       = 'salvarRepostas';
    oParam.perguntas  = aRespostas;
    oParam.iAvaliacao = me.iAvaliacao;
    new Ajax.Request(me.urlRPC,
                          {
                          method: "post",
                          parameters:'json='+Object.toJSON(oParam),
                          onComplete: me.js_retornosaveResposta,
                          asynchronous: false
                          }
                         );
  };

  this.js_retornosaveResposta = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      //alert('Respostas salvas com sucesso.');
    } else {
      alert(oRetorno.message.urlDecode());
    }
  };

  this.saveAvaliacao = function() {

    me.saveresposta();
    js_divCarregando('Aguarde, salvando avaliação...',"msgBox");
    var oParam        = new Object();
    oParam.exec       = 'salvarAvaliacao';
    oParam.iAvaliacao = me.iAvaliacao;
    new Ajax.Request(me.urlRPC,
                          {
                          method: "post",
                          parameters:'json='+Object.toJSON(oParam),
                          onComplete: me.js_retornosaveAvaliacao
                          }
                         );
  };

  // metodo responsavel por desabilitar o form para edição, somente permitindo a visualisação
  // uso: após o .show(); , chamamos o metodo OBJ.disable();
  this.enabled = function() {
    if (me.lEnable) {
      return true;
    }else{
      return false;
    }
  };

  this.disable = function() {

    $('frmAvaliacao'+me.iAvaliacao).disable();
    me.lEnable              = false;
    $('cboGrupos'+me.iAvaliacao).disabled = false;
  };
  this.enable = function() {
    $('frmAvaliacao'+me.iAvaliacao).enable();
    me.lEnable = true;
  };

  this.js_retornosaveAvaliacao = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

     if (me.lMostrarMensagensSucesso) {
       alert('Avaliação salva com sucesso!\nCodigo Para essa Avaliacao: '+me.iGrupoResposta);
     }
     me.onComplete();
    } else {
      alert(oRetorno.message.urlDecode());
    }
  };

  this.setInline = function(oNode) {
    me.view = oNode;
  };
  this.setWindow = function() {
    me.view = '';
  };
  this.setCompleteFunction = function(oFunction) {
    me.onComplete = oFunction;
  };
  $('cboGrupos'+me.iAvaliacao).observe('change', function () {
      if (me.lEnable) {
        me.saveresposta();
      }
      me.getPerguntasByGrupo();
  });

  this.getCodigoGrupoResposta = function() {
    return this.iGrupoResposta;
  };

  this.setRetornaCodigo = function(lRetorna) {
    this.lRetornaCodigo = lRetorna;
  };

  if ( !this.lRetornaCodigo ) {
    $('btnSalvarPerguntas'+me.iAvaliacao).observe('click', me.saveresposta);
    $('btnSalvarAvaliacao'+me.iAvaliacao).observe('click', me.saveAvaliacao);
  }
}
