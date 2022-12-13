require_once("scripts/datagrid.widget.js");
require_once("scripts/widgets/dbmessageBoard.widget.js");
require_once("scripts/widgets/dbcomboBox.widget.js");
require_once("scripts/widgets/dbtextField.widget.js");
require_once("scripts/widgets/dbtextFieldData.widget.js");
require_once("scripts/widgets/windowAux.widget.js");
require_once("scripts/arrays.js");

DBViewCadastroAtributoDinamico = function () {

  this.TIPO_TEXTO    = 1;
  this.TIPO_INTEIRO  = 2;
  this.TIPO_DATA     = 3;
  this.TIPO_NUMERICO = 4;
  this.TIPO_BOOLEAN  = 5;
  this.TIPO_COMBO    = 6;
  var me = this;
  this.sUrlRPC = 'sys1_atributodinamico.RPC.php';
  this.sSaveCallBackFunction = null;
  this.lShowCampoObrigatorio = false;
  this.lShowCampoReferencia  = true;
  this.tiposDesabilitados = [];
  this.aTiposDados = [
    {codigo:1, nome:'Texto'},
    {codigo:2, nome:'Número sem casas decimais'},
    {codigo:3, nome:'Data'},
    {codigo:4, nome:'Número com casas decimais'},
    {codigo:5, nome:'Verdadeiro / Falso'},
    {codigo:6, nome:'Combo'}
  ]                         ;
  this.showForm = function () {


    me.window = new windowAux("windowCadAtt","Cadastro de Atributos", 695, 550);

    var oTxtIndAtributoGrid = new DBTextField('oTxtIndAtributoGrid','oTxtIndAtributoGrid','',10);
    var oTxtGrupoAtt        = new DBTextField('oTxtGrupoAtt'       ,'oTxtGrupoAtt'       ,'',10);
    var oTxtTitulo          = new DBTextField('oTxtTitulo'         ,'oTxtTitulo'         ,'',50);
    var oTxtCodAtributo     = new DBTextField('oTxtCodAtributo'    ,'oTxtCodAtributo'    ,'',10);
    var oTxtDescricao       = new DBTextField('oTxtDescricao'      ,'oTxtDescricao'      ,'',50);
    var oTxtDefault         = new DBTextField('oTxtDefault'        ,'oTxtDefault'        ,'',50);
    var oTxtCodReferencia   = new DBTextField('oTxtCodReferencia'  ,'oTxtCodReferencia'  ,'',10);
    var oTxtDescrReferencia = new DBTextField('oTxtDescrReferencia','oTxtDescrReferencia','',50);
    var oCboTipoAtt         = new DBComboBox('oCboTipoAtt','oCboTipoAtt');
    var oCboObrigatorio     = new DBComboBox('oCboObrigatorio','oCboObrigatorio');
    var oComboAtivo         = new DBComboBox('oComboAtivo','oComboAtivo');

    oTxtCodReferencia.setReadOnly(true);
    oTxtDescrReferencia.setReadOnly(true);

    for (tipo of me.aTiposDados) {

      if (me.tiposDesabilitados.in_array(tipo.codigo)) {
        continue;
      }
      oCboTipoAtt.addItem(tipo.codigo, tipo.nome);
    }

    oCboObrigatorio.addItem('false', 'Não');
    oCboObrigatorio.addItem('true', 'Sim');

    oComboAtivo.addItem('true', 'Sim');
    oComboAtivo.addItem('false', 'Não');

    oTxtGrupoAtt.sStyle        = 'display:none';
    oTxtCodAtributo.sStyle     = 'display:none';
    oTxtCodReferencia.sStyle   = 'display:none';
    oTxtIndAtributoGrid.sStyle = 'display:none';

    var sDisplayCampoObrigatorio = this.lShowCampoObrigatorio ? ' ': 'display: none';
    var sDisplayCampoReferencia  = this.lShowCampoReferencia ? ' ': 'display: none';

    var sHTML =  "  <form name='form_cadatt' id='form_cadatt' action='' class='container'> ";
        sHTML += "    <fieldset style='width: 490px; margin: 0 auto'>                      ";
        sHTML += "      <legend>Cadastro de Atributos</legend>                             ";
        sHTML += "      <table class='form-container'>                                     ";
        sHTML += "        <tr style='display: none;'>                                      ";
        sHTML += "          <td><label for='oTxtTitulo'>Título:</label></td>               ";
        sHTML += "          <td>                                                           ";
        sHTML +=              oTxtIndAtributoGrid.toInnerHtml();
        sHTML +=              oTxtGrupoAtt.toInnerHtml();
        sHTML +=              oTxtTitulo.toInnerHtml();
        sHTML += "          </td>                                                          ";
        sHTML += "        </tr>                                                            ";
        sHTML += "        <tr>                                                             ";
        sHTML += "          <td><label for='oTxtDescricao'>Descrição:</label></td>         ";
        sHTML += "          <td>                                       ";
        sHTML +=              oTxtCodAtributo.toInnerHtml();
        sHTML +=              oTxtDescricao.toInnerHtml();
        sHTML += "          </td>                                      ";
        sHTML += "        </tr>                                        ";
        sHTML += "        <tr>                                         ";
        sHTML += "          <td><label for='oCboTipoAtt'>Tipo de Atributo:</label></td>         ";
        sHTML += "          <td>"+oCboTipoAtt.toInnerHtml()+"</td>     ";
        sHTML += "        </tr>                                        ";
        sHTML += "        <tr>";
        sHTML += "          <td><label for='oTxtDefault'>Valor Default:</label></td>            ";
        sHTML += "          <td>"+oTxtDefault.toInnerHtml()+"</td>     ";
        sHTML += "        </tr>                                        ";
        sHTML += "        <tr style='"+sDisplayCampoObrigatorio+"'>                                         ";
        sHTML += "          <td><label for='oCboObrigatorio'>Obrigatório:</label></td>            ";
        sHTML += "          <td>"+oCboObrigatorio.toInnerHtml()+"</td> ";
        sHTML += "        </tr>                                        ";
        sHTML += "        <tr style='"+sDisplayCampoObrigatorio+"'>";
        sHTML += "          <td><label for='oComboAtivo'>Ativo:</label></td>";
        sHTML += "          <td>"+oComboAtivo.toInnerHtml()+"</td>";
        sHTML += "        </tr>                                        ";
        sHTML += "        <tr style='"+sDisplayCampoReferencia+"'>                                         ";
        sHTML += "          <td><label for='oTxtCodReferencia'><a onclick='js_pesquisaAttDin_db_syscampo(true)' href='#'>Campo Referência:</a></label>    ";
        sHTML += "          </td>                                      ";
        sHTML += "          <td>                                       ";
        sHTML +=             oTxtCodReferencia.toInnerHtml();
        sHTML +=             oTxtDescrReferencia.toInnerHtml();
        sHTML += "          </td>                                      ";
        sHTML += "        </tr>                                        ";
        sHTML += "      </table>    ";
        sHTML += "    </fieldset>   ";
        sHTML += "    <input type=button value='Incluir' name='acao' id='acao'> ";
        sHTML += "    <input type=button value='Novo'    name='novo' id='novo' style='display:none'> ";
        sHTML += "    <fieldset style='width: 650px'>                                                            ";
        sHTML += "      <legend><b>Lista de Atributos</b></legend>                          ";
        sHTML += "      <div id='listaCadAtt'></div>                                        ";
        sHTML += "    </fieldset>                                                           ";
        sHTML += "    <input type=button value='Confirmar' name='confirmar' id='confirmar'> ";
        sHTML += "    <input type=button value='Fechar'    name='cancelar'  id='fechar'>    ";
        sHTML += "</form>                                                                   ";

	  this.window.setContent(sHTML);

	  var oMessage = new DBMessageBoard('msgboard1',
	                                    'Cadastro dos Atributos Dinâmicos',
	                                    '',
	                                    $("windowwindowCadAtt_content"));
	  oMessage.show();

    me.window.show();

    me.oGridCadAtt = new DBGrid('oGridCadAtt');
    me.oGridCadAtt.nameInstance = 'oGridCadAtt';
    me.oGridCadAtt.setCellWidth(new Array('10%','45%','20%','15%','10%','10%'));
    me.oGridCadAtt.setCellAlign(new Array('center','left','left','left','center','center'));
    me.oGridCadAtt.setHeader   (new Array('Código','Descrição','Tipo de Atributo','Valor Default','Opções','Obj'));

    me.oGridCadAtt.aHeaders[5].lDisplayed = false;

    me.oGridCadAtt.show($('listaCadAtt'));

	  me.window.setShutDownFunction(function(){
	    me.window.destroy();
	  });

    $('confirmar').onclick = function () {
      me.confirmRegistration();
    };

	  $('fechar').onclick = function () {
	    me.closeWindow();
	  };

    $('acao').onclick = function () {
      me.saveAttribute();
    };

    $('novo').onclick = function () {
      me.newRecord();
    };

	  me.setAction('incluir');
    me.buildLookUp('db_syscampo',new Array('codcam','descricao'),new Array('oTxtCodReferencia','oTxtDescrReferencia'));
  }

  this.newAttribute = function () {
    me.endSession(me.showForm());
  }

  this.loadAttribute = function (iCodGrupoAtributo) {

    me.endSession(me.showForm());

    js_divCarregando('Aguarde, Consultando Atributos...','msgBox');

    oParam = new Object();

    oParam.sMethod   = 'consultarAtributos';
    oParam.iGrupoAtt = iCodGrupoAtributo;

    var oAjax = new Ajax.Request( me.sUrlRPC, {
                                          method: 'post',
                                          parameters: 'json='+Object.toJSON(oParam),
                                          onComplete: function(oAjax) {

                                                        var oRetorno = eval("("+oAjax.responseText+")");

                                                        if ( oRetorno.iStatus == 2 ) {

                                                          alert(oRetorno.sMsg.urlDecode());
                                                          return false;
                                                        } else {

                                                          $('oTxtGrupoAtt').value = oRetorno.iGrupoAtt;
                                                          $('oTxtTitulo').value   = oRetorno.sTitulo;

                                                          me.returnBuildGrid(oAjax);
                                                        }
                                                      }
                                        }
                                   );
  }



  this.newRecord = function () {

    var iIndAtributo = $F('oTxtIndAtributoGrid');

    me.clearForm();
    me.oGridCadAtt.aRows[iIndAtributo].lDisplayed = true;
    me.renderGrid();

    $('novo').style.display = 'none';

    me.setAction('incluir');
  }

  this.setAction = function(sAcao) {

    if (sAcao == 'incluir') {

      $('acao').value         = 'Incluir';
      $('novo').style.display = 'none';
      me.enableGrid(true);

    } else {

      $('acao').value         = 'Alterar';
      $('novo').style.display = '';
      me.enableGrid(false);
    }
  };


  this.screenChange = function(iIndAtributo) {

    var oAtributo = (me.oGridCadAtt.aRows[iIndAtributo].aCells[5].getValue()).evalJSON();

    me.oGridCadAtt.aRows[iIndAtributo].lDisplayed = false;
    me.renderGrid();

    $('oTxtIndAtributoGrid').value = iIndAtributo;
    $('oTxtCodAtributo').value     = oAtributo.iCodigo;
    $('oTxtDescricao').value       = oAtributo.sDescricao.urlDecode();
    $('oCboTipoAtt').value         = oAtributo.iTipo;
    $('oCboObrigatorio').value     = oAtributo.lObrigatorio ? 'true' : 'false';
    $('oComboAtivo').value         = oAtributo.lAtivo ? 'true' : 'false';
    $('oTxtDefault').value         = oAtributo.sValorDefault.urlDecode();
    $('oTxtCodReferencia').value   = oAtributo.iCodCampo;
    $('oTxtDescrReferencia').value = oAtributo.sDescrCampo.urlDecode();

    me.setAction('alterar');
  };


  this.saveAttribute = function() {

    if ($F('oTxtDescricao') == '') {
      alert('Descrição do atributo não informado!');
      return false;
    }

    js_divCarregando('Aguarde, Salvando Atributo...','msgBox');

    oParam = {};
    oParam.sMethod       = 'salvarAtributo';
    oParam.iIndAtributo  = $F('oTxtIndAtributoGrid');
    oParam.iGrupoAtt     = $F('oTxtGrupoAtt');
    oParam.iCodigo       = $F('oTxtCodAtributo');
    oParam.sTitulo       = $F('oTxtTitulo');
    oParam.sDescricao    = $F('oTxtDescricao');
    oParam.iTipo         = $F('oCboTipoAtt');
    oParam.sValorDefault = $F('oTxtDefault');
    oParam.iCampo        = $F('oTxtCodReferencia');
    oParam.lObrigatorio  = $F('oCboObrigatorio') == 'true';
    oParam.lAtivo        = $F('oComboAtivo') == 'true';

    new Ajax.Request( me.sUrlRPC,
                      {
                        method: 'post',
                        parameters: 'json='+Object.toJSON(oParam),
                        onComplete: me.returnBuildGrid
                      }
                    );
  };

  this.removeAttribute = function(iIndAtributo) {

    js_divCarregando('Aguarde, Excluindo Atributo...','msgBox');

    oParam = new Object();
    oParam.sMethod      = 'removerAtributo';
    oParam.iIndAtributo = iIndAtributo;

    var oAjax = new Ajax.Request( me.sUrlRPC, {
                                          method: 'post',
                                          parameters: 'json='+Object.toJSON(oParam),
                                          onComplete: me.returnBuildGrid
                                        }
                                   );
  }


  this.returnBuildGrid = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == 2) {
      alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
      me.clearForm();
      me.buildGrid(oRetorno.aAtributos);
    }
  }


  this.confirmRegistration = function() {

    js_divCarregando('Aguarde, Confirmando Cadastro...','msgBox');

    oParam           = new Object();
    oParam.sMethod   = 'confirmar';
    oParam.iGrupoAtt = $F('oTxtGrupoAtt');

    var oAjax = new Ajax.Request( me.sUrlRPC, {
                                          method: 'post',
                                          parameters: 'json='+Object.toJSON(oParam),
                                          onComplete: me.returnConfirm
                                        }
                                   );
  }


  this.returnConfirm = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == 2) {
      alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
      alert('Cadastro salvo com sucesso!');
      me.closeWindow();
      me.getSaveCallBackFunction(oRetorno.iGrupoAtt);
    }
  }

  this.closeWindow = function () {

    oParam           = new Object();
    oParam.sMethod   = 'finalizarSessao';

    me.endSession(me.window.destroy());
  }


  this.endSession = function (sFuncao) {

    oParam         = new Object();
    oParam.sMethod = 'finalizarSessao';

    var oAjax = new Ajax.Request( me.sUrlRPC, {
                                                method: 'post',
                                                parameters: 'json='+Object.toJSON(oParam),
                                                onComplete: sFuncao
                                              }
                                );
  }



  this.buildGrid = function(aRows) {

    me.oGridCadAtt.clearAll(true);

    if (aRows.length > 0 ) {
      $('oTxtTitulo').readOnly = true;
      $('oTxtTitulo').style.backgroundColor= 'rgb(222, 184, 135)';
    } else {
      $('oTxtTitulo').readOnly = false;
      $('oTxtTitulo').style.backgroundColor= 'white';
    }

    aRows.each(
      function (oRow,iInd) {
        var aColunas = new Array();
            aColunas.push(oRow.iCodigo);
            aColunas.push(oRow.sDescricao.urlDecode());

            switch(oRow.iTipo) {
              case '1':
                sDescrTipo = 'Varchar';
                break;
              case '2':
                sDescrTipo = 'Integer';
                break;
              case '3':
                sDescrTipo = 'Date';
                break;
              case '4':
                sDescrTipo = 'Float';
                break;
              case '5':
                sDescrTipo = 'Boolean';
                break;
              case '6':
                sDescrTipo = 'Combo';
                break;
              default:
                sDescrTipo = '';
            }

            aColunas.push(sDescrTipo);
            aColunas.push(oRow.sValorDefault.urlDecode());

        var sInput  = "<input type='button' id='"+iInd+"' value='A' data-codigo='"+ oRow.iCodigo +"'/> ";
            sInput += "<input type='button' id='"+iInd+"' value='E' data-codigo='"+ oRow.iCodigo +"'/> ";

            aColunas.push(sInput);
            aColunas.push(Object.toJSON(oRow));

        me.oGridCadAtt.addRow(aColunas);
      }
    );

    me.renderGrid();
  }



  this.clearForm = function () {

    $('oTxtTitulo').value           = '';
    $('oTxtIndAtributoGrid').value  = '';
    $('oTxtCodAtributo').value      = '';
    $('oTxtDescricao').value        = '';
    $('oCboTipoAtt').value          = '1';
    $('oTxtDefault').value          = '';
    $('oTxtCodReferencia').value    = '';
    $('oTxtDescrReferencia').value  = '';
    $('oComboAtivo').value  = 'true';

    me.setAction('incluir');
  }


  this.buildLookUp = function(sTabela,aCampo,aCampoForm) {


    var sLookUp     = "";
    var aListaChave = new Array();

    sLookUp += "function js_pesquisaAttDin_"+sTabela+"(mostra){";
    sLookUp += "  if (mostra) {";
    sLookUp += "    js_OpenJanelaIframe('','db_iframe_AttDin_"+sTabela+"','func_"+sTabela+".php?funcao_js=parent.js_mostraAttDin_"+sTabela+"1|"+aCampo.join('|')+"','Pesquisa',true);";
    sLookUp += "    $('Jandb_iframe_AttDin_"+sTabela+"').style.zIndex = '999999999';";
    sLookUp += "  }";
    sLookUp += "}";

    aCampoForm.each(
      function(sIdCampo,iInd){
        aListaChave.push('chave'+iInd)
      }
    );

    sLookUp += "function js_mostraAttDin_"+sTabela+"1("+aListaChave.join(',')+"){";

    aCampoForm.each(
      function(sIdCampo,iInd){
        sLookUp += " $('"+sIdCampo+"').value = chave"+iInd+";";
      }
    );

    sLookUp += "  db_iframe_AttDin_"+sTabela+".hide();";
    sLookUp += "}";

    var oScript             = document.createElement("script");
        oScript.innerHTML  += sLookUp;
        document.getElementsByTagName("head")[0].appendChild(oScript);
  }


  me.renderGrid = function () {

    me.oGridCadAtt.renderRows();

    var aListaButtons = $$('#listaCadAtt input[type=button]');

    aListaButtons.each(
      function (oButton) {
        if (oButton.value == 'A') {
          oButton.onclick = function () {
                              me.screenChange(oButton.id);
                            };
        } else {
          oButton.onclick = function () {
                              if (confirm('Deseja realmente excluir registro informado?')) {
                                me.removeAttribute(oButton.getAttribute('data-codigo'));
                              }
                            };
        }
      }
    );
  }

  me.enableGrid = function (lHabilita) {

    me.renderGrid();

    var aListaButtons = $$('#listaCadAtt input[type=button]');

    aListaButtons.each(
      function (oButton) {
        if (lHabilita) {
          oButton.disabled = false;
        } else {
          oButton.disabled = true;
        }
      }
    );
  }


  this.setSaveCallBackFunction = function(sFunction){
    me.sSaveCallBackFunction = sFunction;
  }

  this.getSaveCallBackFunction = function(iGrupoAtributo){
    me.sSaveCallBackFunction(iGrupoAtributo);
  }

  this.showComboObrigatorio = function(lShow) {
    this.lShowCampoObrigatorio = lShow;
  }
  this.showCampoReferencia = function(lShow) {
    this.lShowCampoReferencia = lShow;
  }

  this.setTiposDesabilitados = function(tipos) {
    this.tiposDesabilitados = tipos;
  }
}
