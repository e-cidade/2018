DBViewCadastroEndereco = function(sId, sNameInstance, iCodigoEndereco) {

  var me                = this;

  var iCodigoComplemento= '';

  this.iCodigoPais            = '';
  this.iCodigoEstado          = '';
  this.iCodigoMunicipio       = '';
  this.iCodigoBairro          = '';
  this.iCodigoLogradouro      = '';
  this.iCodigoLocal           = '';
  this.iCodigoEndereco        = iCodigoEndereco;
  this.iCodigoRua             = '';
  this.iCodigoRuasTipo        = '';
  this.iCodigoRuaTipo         = '';
  this.iCodigoCepRua          = '';
  this.iTipoValidacao         = 1;
  this.iCodigoRuaMunicipio    = "";
  this.iCodigoBairroMunicipio = "";
  this.iCodigoOv02_sequencial = "";
  this.iCodigoOv02_seq        = "";
  this.iEnderecoAutomatico    = false;
  this.iBairroAutomatico      = false;
  this.lModificado            = false;
  this.iMunicipioAutomatico   = false;
  this.lEnderecoMunicipio     = false;
  this.lShowCondominio        = true;
  this.lShowLoteamento        = true;
  this.lShowPontoReferencia   = true;
  this.lShowComplemento       = true;
  this.sNameInstance          = sNameInstance || "window.repository.endereco.getInstance(" + window.repository.endereco.addInstance(this) +")";
  this.sNumero                = '';
  this.sId                    = sId;
  this.sComplemento           = '';
  this.sCondominio            = '';
  this.sLoteamento            = '';
  this.municiovalidado        = false;
  this.sPontoReferencia       = '';
  this.sNomeMunicipio         = '';
  this.sNomePais              = '';
  this.sNomeRua               = '';
  this.sNomeBairro            = '';
  this.sCepEndereco           = '';
  this.objRetorno             = '';
  this.callBackFunction = function () {

  }
  this.sUrlRpc          = 'con4_endereco.RPC.php';

//  var iWidth = document.width / 1.7;
  var iWidth = 790;
  //var iWheigth = window.innerHeight / 1.5;
  var iWheigth = 460;


  this.oWindowEndereco  = new windowAux('wndEndereco'+me.sId, 'Cadastro de Endereço', iWidth, iWheigth);

  sContent  = "<div  id='ctnMessageBoard' style='text-align:center;width:100%'>";
  sContent += "  <div style='width:100%' id='ctnDados"+sId+"'>";
  sContent += "  <fieldset style='text-align:center;'>";
  sContent += "    <legend><b>Dados Endereço :</b></legend>";
  sContent += "     <table border='0' style=\"border-collapse:collapse;\">";
  sContent += "       <tr >";
  sContent += "         <td id='ctnLabelCep"+sId+"' >";
  sContent += "           <a href='#' onclick='"+me.sNameInstance+".lookupCep();'><b>Pesquisa Cep:</b></a>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoCep"+sId+"' >";
  sContent += "         </td>";
  sContent += "         <td colspan='3' ><input type='button' id='btnPesquisarCep"+sId+"' value='Pesquisar' ";
  sContent += "                  onClick='"+me.sNameInstance+".pesquisaCep();' style='display:none'>";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "       <tr>";
  sContent += "         <td id='ctnLabelPais"+sId+"'>";
  sContent += "           <b>País:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoPais"+sId+"' colspan='4'></td>";
  sContent += "       </tr>";
  sContent += "       <tr>";
  sContent += "         <td id='ctnLabelEstado"+sId+"' >";
  sContent += "           <b>Estado:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoEstado"+sId+"' colspan='4'></td>";
  sContent += "       </tr>";
  sContent += "       <tr>";
  sContent += "         <td id='ctnLabelMunicipio"+sId+"' >";
  sContent += "           <b>Município:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoMunicipio"+sId+"' colspan='4'></td>";
  sContent += "       </tr>";
  sContent += "       <tr>";
  sContent += "         <td id='ctnLabelBairro"+sId+"' >";
  sContent += "           <b>Bairro:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoBairro"+sId+"' >"
  sContent += "         </td>";
  sContent += "         <td id='ctnDescrBairro"+sId+"' colspan='3'>";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "       <tr valign='top'>";
  sContent += "         <td id='ctnLabelRua"+sId+"' style='width:10%;'>";
  sContent += "           <b>Rua:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoRua"+sId+"' style='width:25%;'>"
  sContent += "         </td>";
  sContent += "          <td id='ctnCboRuasTipo"+sId+"' style='width:15%;'>";
  sContent += "          </td>";
  sContent += "          <td id='ctnDescrRua"+sId+"'  style='width:50%;' colspan='2'>";
  sContent += "          </td>";
  sContent += "         </tr>";
  sContent += "       <tr>";
  sContent += "         <td id='ctnLabelNumero"+sId+"' >";
  sContent += "           <b>Número:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoNumero"+sId+"' >"
  sContent += "         </td>";
  sContent += "         <td><input type='button' id='btnPesquisarNumero"+sId+"' value='Pesquisar' ";
  sContent += "                  onClick='"+me.sNameInstance+".findNumeroByNumero();' disabled='disabled' >";
  sContent += "         </td>";
  sContent += "         <td id='ctnLabelComplemento"+sId+"' style='width: 80px;'>";
  sContent += "           <b>Complemento:</b>";
  sContent += "         </td>";
  sContent += "         <td id='ctnDescrComplemento"+sId+"'>";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "       <tr>";
  sContent += "         <td id='ctnLabelCepEnd"+sId+"' >";
  sContent += "           <b>Cep:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnCodigoCepEnd"+sId+"' >"
  sContent += "         </td>";
  sContent += "         <td colspan='3'> &nbsp;";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "       <tr id='trCondominio"+sId+"' >";
  sContent += "         <td id='ctnLabelCondominio"+sId+"' >";
  sContent += "           <b>Condomínio:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnDescrCondominio"+sId+"' colspan='4'>";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "       <tr id='trLoteamento"+sId+"'>";
  sContent += "         <td id='ctnLabelLoteamento"+sId+"' >";
  sContent += "           <b>Loteamento:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnDescrLoteamento"+sId+"' colspan='4'>";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "       <tr id='trPontoReferencia"+sId+"'>";
  sContent += "         <td id='ctnLabelPontoReferencia"+sId+"'  nowrap>";
  sContent += "           <b>Ponto Referência:</b>";
  sContent += "         </td>"
  sContent += "         <td id='ctnDescrPontoReferencia"+sId+"' colspan='4'>";
  sContent += "         <textarea  id='txtDescrPontoReferencia"+sId+"' rows='5' style='width:100%'></textarea>";
  sContent += "         </td>";
  sContent += "       </tr>";
  sContent += "     </table>";
  sContent += "  </fieldset>";
  sContent += "  </div>";
  sContent += "  <div id='btnAcoes"+sId+"' style='margim-top:5px;'>";
  sContent += "     <input type='button' id='btnSalvar"+sId+"' value='Salvar' onClick='"+me.sNameInstance+".salvarEndereco();'>";
  sContent += "     <input type='button' id='btnLimpar"+sId+"' value='Limpar' onClick='"+me.sNameInstance+".limpaForm();'>";
  sContent += "  </div>";
  sContent += "</div>";
  me.oWindowEndereco.setContent(sContent);

  //Metodo para fechar a janela e retornar o endereco salvo
  me.close = function() {

    if (me.getObjetoRetorno() != "") {
       me.getObjetoRetorno().value = me.getCodigoEndereco();
    }
    me.oWindowEndereco.destroy();
  }
  me.oWindowEndereco.setShutDownFunction(me.close);
  me.oWindowEndereco.allowCloseWithEsc(false);
  this.oMessageBoardEndereco = new DBMessageBoard('msgBoardEndereco'+sId,
                                                 'Cadastro de Endereço',
                                                 'Cadastro de Endereço',
                                                 $('ctnMessageBoard')
                                                 );
  this.oMessageBoardEndereco.show();

  /**
   *Seta o objeto que vai receber o retorno
   *@param {objeto} oRetorno
   *return void
   */

  this.setObjetoRetorno = function(oRetorno) {

    this.objRetorno = oRetorno;
  }

  /**
   *Retorna o objeto informado para retorno
   *@return objeto
   */
  this.getObjetoRetorno = function() {

    return this.objRetorno;
  }
  /**
   *Seta se o campo condominio vai estar disponivel na tela
   *@param {boolean} lCondominio
   *return void
   */

  this.hideCondominio = function() {

    me.lShowCondominio = false;
    if ($('trCondominio'+me.sId)) {
      $('trCondominio'+me.sId).style.display='none';
    }

  }

  /**
   *Seta se o campo loteamento vai estar disponivel na tela
   *return void
   */
  this.hideLoteamento = function() {

    me.lShowLoteamento = false;
    if ($('trLoteamento'+me.sId)) {
      $('trLoteamento'+me.sId).style.display='none';
    }
  }

  /**
   *Seta se o campo ponto de referencia vai estar disponivel na tela
   *return void
   */
  this.hidePontoReferencia = function() {

    me.lShowPontoReferencia = false;
    if ($('trPontoReferencia'+me.sId)) {
      $('trPontoReferencia'+me.sId).style.display='none';
    }
  }

  /**
   *Seta se o campo complemento vai estar disponivel na tela
   *return void
   */
  this.hideComplemento = function() {

    me.lShowComplemento = false;
    if ($('trComplemento'+me.sId)) {
      $('trComplemento'+me.sId).style.display='none';
    }
  }

  //Métodos para setar e ler as propriedades
  /**
   *Seta o estado se foi modificado alguma coisa na tela
   *@param {boolean} lModificado
   *@return void
   */
  this.setModificado = function(lModificado) {

    me.lModificado = lModificado;
  }

 /**
  *Retorna o estado se houve alguma modificacao
  *@return {boolean} true se alterado false se nao alterado
  */
  this.getModificado = function() {

    return this.lModificado;
  }

  /**
   *Seta o codigo da rua do endereco do cadastro Imobiliario
   *@param {integer} iCodigoRuaMunicipio
   *@return void
   */
  this.setCodigoRuaMunicipio = function(iCodigoRuaMunicipio) {
    this.iCodigoRuaMunicipio = iCodigoRuaMunicipio;
  }

  /**
   *Retorna o codigo da rua do endereco do cadastro Imobiliario
   *@return {integer} codigo da rua do cadastro imobiliario
   */
  this.getCodigoRuaMunicipio = function() {

    return this.iCodigoRuaMunicipio;
  }

  /**
   *Seta o codigo do bairro do endereco do cadastro Imobiliario
   *@param {integer} iCodigoBairroMunicipio
   *@return void
   */
  this.setCodigoBairroMunicipio = function(iCodigoBairroMunicipio) {

    this.iCodigoBairroMunicipio = iCodigoBairroMunicipio;
  }

  /**
   *Retorna o codigo do bairro do endereco do cadastro Imobiliario
   *@return {integer} codigo do bairro do cadastro imobiliario
   */
  this.getCodigoBairroMunicipio = function() {

    return this.iCodigoBairroMunicipio;
  }


  /**
   *Seta a descricao da rua do endereco
   *@param {string} sNomeRua
   *@return void
   */
  this.setNomeRua = function(sNomeRua) {

    this.sNomeRua = sNomeRua;
  }

  /**
   *Retorna a descricao da rua do endereco
   *@return {string} nome da rua
   */
  this.getNomeRua = function() {

    return this.sNomeRua;
  }

  /**
   *Seta o codigo do tipo da rua conforme a tabela de ligacao
   *@param {string} iCodigoRuasTipo
   *@return void
   */
  this.setRuasTipo = function(iCodigoRuasTipo) {
    this.iCodigoRuasTipo = iCodigoRuasTipo;
  }

  /**
   *Retorna o codigo sequencial que contem a ligacao com o tipo de rua
   *@return {string} com o codigo de ligacao com do tipo de rua
   */
  this.getRuasTipo = function() {
    return this.iCodigoRuasTipo;
  }

  /**
   *Seta o tipo da rua
   *@param {string} iCodigoRuaTipo
   *@return void
   */
  this.setRuaTipo = function(iCodigoRuaTipo) {
    this.iCodigoRuaTipo = iCodigoRuaTipo;
  }

  /**
   *Retorna o tipo da rua que esta selecionado no select da tela
   *@return {string} com o codigo do tipo de rua
   */
  this.getRuaTipo = function() {
    return this.iCodigoRuaTipo;
  }

  /**
   *Seta o Cep da rua
   *@param {string} iCodigoCepRua
   *@return void
   */

  this.setCepRua = function(iCodigoCepRua) {
    this.iCodigoCepRua = iCodigoCepRua;
  }
  /**
   *Retorna o Cep da rua
   *@return {string} com o cep da rua
   */
  this.getCepRua = function() {

    return this.iCodigoCepRua;
  }

  /**
   *Retorna o codigo ov02_seqeuncial do cidadao
   *@return {integer} codigo ov02_sequencial
   */
  this.getCodigoCidadao = function() {

    var aCidadao = new Array();
    aCidadao[0]  = this.iCodigoOv02_sequencial;
    aCidadao[1]  = this.iCodigoOv02_seq;

    return aCidadao;
  }

  /**
   *Retorna o codigo ov02_seqeuncial do cidadao
   *@return {integer} codigo ov02_sequencial
   */
  this.setCodigoCidadao = function(ov02_sequencial,ov02_seq) {

    this.iCodigoOv02_sequencial = ov02_sequencial;
    this.iCodigoOv02_seq        = ov02_seq;
  }

  me.sTxtNomeBairro = null;

  /*
   *Cria o campo de busca do cep
   */
  me.oTxtCep = new DBTextField('txtCep'+sId, 'txtCep'+sId, '');
  me.oTxtCep.lReadOnly = true;
  //me.oTxtCep.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oTxtCep.addEvent('onKeyUp',"js_ValidaCampos(this,1,\"Campo Cep\",\"f\",\"f\",event)");
  me.oTxtCep.addStyle('width', '70px');
  //me.oTxtCep.addStyle('display', 'none');
  me.oTxtCep.setMaxLength(8);
  me.oTxtCep.show($('ctnCodigoCep'+sId));

  /**
   *Metodo para realizar a busca do endereco pelo cep informado
   *caso seje retornado algum dado estes serao preenchidos ate
   *o campo rua.
   */
  this.pesquisaCep = function() {

    if ($('txtCep'+sId).value == '') {

      return false;
    }

    if ($('txtCep'+sId).value.length != 8) {

      alert('usuário:\n\nO Cep informado possui menos de 8 digitos.\n\nVerifique para continuar a pesquisa.\n\n');
      return false;
    }

    if ($F('txtDescrBairro'+sId).trim() != "" ||
        $F('txtDescrRua'+sId).trim() != "" ||
        $F('txtCodigoNumero'+sId).trim() != "") {

      if(!confirm('usuário:\n\nExistem dados abaixo preenchidos serão perdidos Deseja Continuar?\n\n')){
        $('txtCep'+sId).value = ''
        return false;
      }
    }

    var oPesquisa = new Object();
    oPesquisa.exec  = 'findCep';
    oPesquisa.codigoCep = $F('txtCep'+sId);
    oPesquisa.sNomeBairro = me.sTxtNomeBairro;

    var msgDiv = "Aguarde pesquisando cep.";
    js_divCarregando(msgDiv,'msgBox');

    var oAjax = new Ajax.Request(
                me.sUrlRpc,
                { parameters: 'json='+Object.toJSON(oPesquisa),
                  method: 'post',
                  onComplete : me.retornoFindCep
                }

    );
  }

  /**
   * Método que exibe a lookup de pesquisa por CEP
   * No retorno da mesma disparamos a pesquisa que busca as informações do CEP selecionado
   */
  this.lookupCep = function() {

    js_OpenJanelaIframe('', 'db_iframe_cep',
                        'func_cep.php?funcao_js=parent.'+me.sNameInstance+'.retornoLookupCep|cep|cp01_bairro',
                        'Pesquisa CEP', true);
    $('Jandb_iframe_cep').style.zIndex = 100000;
  }

  /**
   * Método que exibe o retorno da função lookupCep()
   * Escondemos a Lookup e disparamos a pesquisa das informações do CEP selecionado
   */
  this.retornoLookupCep = function() {

    db_iframe_cep.hide();
    $('txtCep'+sId).value = arguments[0];
    me.sTxtNomeBairro = arguments[1];
    me.pesquisaCep();
  }

  /**
   *Metodo que trata o retorno da busca pelo cep informado
   *caso retorno seja false limpa os campos e preenche com os valores default
   *senao preenche os campos com os dados retornados
   */
  this.retornoFindCep = function (oAjax) {

    js_removeObj('msgBox');

    $('cboCodigoPais'+sId).removeAttribute("readonly");
    $('cboCodigoPais'+sId).style.backgroundColor      = '#FFFFFF';
    $('cboCodigoMunicipio'+sId).removeAttribute("readonly");
    $('cboCodigoMunicipio'+sId).style.backgroundColor = '#FFFFFF';
    $('txtCodigoBairro'+sId).removeAttribute("readonly");
    $('txtCodigoBairro'+sId).style.backgroundColor    = '#FFFFFF';
    $('txtDescrBairro'+sId).removeAttribute("readonly");
    $('txtDescrBairro'+sId).style.backgroundColor     = '#FFFFFF';
    $('txtCodigoRua'+sId).removeAttribute("readonly");
    $('txtCodigoRua'+sId).style.backgroundColor       = '#FFFFFF';
    $('txtDescrRua'+sId).removeAttribute("readonly");
    $('txtDescrRua'+sId).style.backgroundColor        = '#FFFFFF';
    $('txtCepEnd'+sId).removeAttribute("readonly");
    $('txtCepEnd'+sId).style.backgroundColor          = '#FFFFFF';
    $('cboCodigoEstado'+sId).removeAttribute("readonly");
    $('cboCodigoEstado'+sId).style.backgroundColor     = '#FFFFFF';
    $('cboRuasTipo'+sId).removeAttribute("readonly");
    $('cboRuasTipo'+sId).style.backgroundColor     = '#FFFFFF';

    var oRetorno = eval('('+oAjax.responseText+')');

    if (oRetorno.endereco != false) {

      var iNumReg = oRetorno.endereco.length;

      //if (iNumReg == 1) {

        me.clearAll(1);

        me.preencheCboEstados(oRetorno.estados,oRetorno.endereco[0].iestado);

        $('cboCodigoPais'+sId).value = oRetorno.endereco[0].ipais;
        //$('cboCodigoPais'+sId).style.backgroundColor = '#DEB887';
        //$('cboCodigoPais'+sId).disabled = true;
        me.setPais($F('cboCodigoPais'+sId));

        $('cboCodigoEstado'+sId).value = oRetorno.endereco[0].iestado;
        //$('cboCodigoEstado'+sId).disabled = true;
        //$('cboCodigoEstado'+sId).style.backgroundColor = '#DEB887';
        me.setEstado($F('cboCodigoEstado'+sId));

        $('cboCodigoMunicipio'+sId).value = oRetorno.endereco[0].imunicipio;
        //$('cboCodigoMunicipio'+sId).style.backgroundColor = '#DEB887';
        //$('cboCodigoMunicipio'+sId).disabled = true;
        me.setMunicipio($F('cboCodigoMunicipio'+sId));

        $('txtCodigoBairro'+sId).value = oRetorno.endereco[0].ibairro;
        //$('txtCodigoBairro'+sId).setAttribute("readonly", "readonly");
        //$('txtCodigoBairro'+sId).style.backgroundColor = '#DEB887';
        me.setBairro($F('txtCodigoBairro'+sId));

        $('txtDescrBairro'+sId).value = oRetorno.endereco[0].sbairro.urlDecode();
        //$('txtDescrBairro'+sId).setAttribute("readonly", "readonly");
        //$('txtDescrBairro'+sId).style.backgroundColor = '#DEB887';
        me.setNomeBairro($F('txtDescrBairro'+sId));

        $('txtCodigoRua'+sId).value = oRetorno.endereco[0].irua;
        //$('txtCodigoRua'+sId).setAttribute("readonly", "readonly");
        //$('txtCodigoRua'+sId).style.backgroundColor = '#DEB887';
        me.setRua($F('txtCodigoRua'+sId));

        $('txtDescrRua'+sId).value = oRetorno.endereco[0].srua.urlDecode();
        //$('txtDescrRua'+sId).setAttribute("readonly", "readonly");
        //$('txtDescrRua'+sId).style.backgroundColor = '#DEB887';
        me.setNomeRua($F('txtDescrRua'+sId));
        me.setRuasTipo(oRetorno.endereco[0].iruastipo);

        $('txtCepEnd'+sId).value = $F('txtCep'+sId);
        //$('txtCepEnd'+sId).setAttribute("readonly", "readonly");
        //$('txtCepEnd'+sId).style.backgroundColor = '#DEB887';
        me.setCepEndereco($F('txtCepEnd'+sId));

        me.oCboRuasTipo.setValue(oRetorno.endereco[0].iruatipo);

     /*} else {

    	me.clearAll(1);

        $('cboCodigoPais'+sId).value = oRetorno.endereco[0].ipais;
        me.setPais($F('cboCodigoPais'+sId));
        //$('cboCodigoPais'+sId).disabled = true;
        //$('cboCodigoPais'+sId).style.backgroundColor = '#DEB887';

        me.preencheCboEstados(oRetorno.estados,oRetorno.endereco[0].iestado);
        //$('cboCodigoEstado'+sId).disabled = true;
       //$('cboCodigoEstado'+sId).style.backgroundColor = '#DEB887';

        $('cboCodigoMunicipio'+sId).value = oRetorno.endereco[0].imunicipio;
        me.setMunicipio($F('cboCodigoMunicipio'+sId));
        //$('cboCodigoMunicipio'+sId).disabled = true;
        //$('cboCodigoMunicipio'+sId).style.backgroundColor = '#DEB887';

        $('txtCepEnd'+sId).value = $F('txtCep'+sId);
        me.setCepEndereco($F('txtCepEnd'+sId));
        //$('txtCepEnd'+sId).setAttribute("readonly", "readonly");
        //$('txtCepEnd'+sId).style.backgroundColor = '#DEB887';

      }*/

    } else {

      alert('usuário:\n\n\Nenhum endereço retornado para o cep informado !\n\n');
      $('txtCep'+sId).value = '';
      me.clearAll(1);
      me.setCodigoEndereco('');
      me.buscaEndereco();
    }
  }

  $('txtCep'+sId).observe('change', me.pesquisaCep);

//-------------------------------------Início da Manipulação do Pais----------------------------------------------------

  this.changePais = function() {

    if ( $('cboCodigoEstado'+sId).length != 0 && $('cboCodigoEstado'+sId).value != 0) {

      var sMessage  = "usuário:\n\nDeseja alterar o País?";
          sMessage += "\n\nOs dados abaixo já preenchidos serão perdidos ! ";
          sMessage += "\n\nDeseja continuar ?";

      if (!confirm(sMessage)) {

        $('cboCodigoPais' + sId).value = me.getPais();
        return false;
      }

      me.clearAll(1);
    }

    me.findEstadoByCodigoPais();
  }

  me.oCboCodigoPais = new DBComboBox('cboCodigoPais'+sId, 'cboCodigoPais'+sId);
  me.oCboCodigoPais.addStyle('width', '100%');
  me.oCboCodigoPais.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oCboCodigoPais.show($('ctnCodigoPais'+sId));
  $('ctnCodigoPais'+sId).observe('change', me.changePais);


//-------------------------------------Fim Manipulação o Pais-----------------------------------------------------------
//-------------------------------------Início da Manipulação do Estado--------------------------------------------------

  /*
   *Cria o campo código do Estado
   */
  this.changeEstado = function() {

    if ($('cboCodigoMunicipio'+sId).length != '' && $('cboCodigoMunicipio'+sId).value != 0) {

      var sMessage  = "usuário:\n\nDeseja alterar o Estado?";
          sMessage += "\n\nOs dados abaixo já preenchidos serão perdidos!";
          sMessage += "\n\nDeseja continuar ?";

      if (!confirm(sMessage)) {

        $('cboCodigoEstado'+sId).value  = me.getEstado();
        return false;

      } else {
        me.clearAll(2);
      }
    }

    me.setEstado($F('cboCodigoEstado'+sId));
    me.findMunicipioByEstado();
  }

  me.oCboCodigoEstado = new DBComboBox('cboCodigoEstado'+sId, 'cboCodigoEstado'+sId);
  me.oCboCodigoEstado.addStyle('width', '100%');
  me.oCboCodigoEstado.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oCboCodigoEstado.show($('ctnCodigoEstado'+sId));
  $('ctnCodigoEstado'+sId).observe('change', me.changeEstado);

  this.findEstadoByCodigoPais = function() {

    $('cboCodigoEstado'+sId).options.length = 0;
    var oPesquisa         = new Object();
    oPesquisa.exec        = 'findEstadoByCodigoPais';
    oPesquisa.iCodigoPais = $F('cboCodigoPais'+sId);

    var msgDiv = "Aguarde carregando lista de estados.";
    js_divCarregando(msgDiv,'msgBox');

    var oAjax = new Ajax.Request(
      me.sUrlRpc,
      {
    	asynchronous: false,
        parameters: 'json='+Object.toJSON(oPesquisa),
        method: 'post',
        onComplete : me.retornofindEstadoByCodigoPais
      }
    );
  }

  this.retornofindEstadoByCodigoPais = function (oAjax) {

    js_removeObj('msgBox');

    var oRetorno = eval('('+oAjax.responseText+')');

    if (oRetorno.status == 2) {

      alert('usuário:\n\nNenhum estado retornado para o País selecionado.\n\n');
      return false;
    }

    //var iEstado = '';

    //if (oRetorno.pais[0].db70_sequencial == $F('cboCodigoPais'+sId)) {
    //  iEstado = oRetorno.pais[0].db71_sequencial;
    //}

    iEstado = -1;
    me.preencheCboEstados(oRetorno.itens, iEstado);
  }

//-------------------------------------Fim da Manipulação do Estado-----------------------------------------------------
//-------------------------------------Início da Manipulação do Municipio-----------------------------------------------

  this.findMunicipioByEstado = function() {

    if ($('cboCodigoEstado'+sId).length == 0) {
      return false;
    }

    var msgDiv = "Aguarde pesquisando município.";
    js_divCarregando(msgDiv,'msgBox');

    /*
     * Função em ajax que realiza a busca do municipio pelo código informado
     */
    $('cboCodigoMunicipio'+sId).options.length = 0;
    var oPesquisa              = new Object();
    oPesquisa.exec             = 'findMunicipioByEstado';
    oPesquisa.iCodigoEstado    = $F('cboCodigoEstado'+sId);

    var oAjax = new Ajax.Request(
      me.sUrlRpc,
      {
        asynchronous: false,
        parameters : 'json='+Object.toJSON(oPesquisa),
        method     : 'post',
        onComplete : me.retornofindMunicipioByEstado
      }
    );
  }

  this.retornofindMunicipioByEstado = function (oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('('+oAjax.responseText+')');

    if (oRetorno.status == 2) {

      alert('usuário:\n\nNenhum municipio retornado para o estado selecionado.');
      return false;
    }

    var iMunicipio = '';

    if (oRetorno.iEstadoPadrao == $F('cboCodigoEstado'+sId)) {
      iMunicipio = oRetorno.iMunicipioPadrao;
    }

    me.preencheCboMunicipio(oRetorno.aMunicipios, iMunicipio);
  }

  this.preencheCboMunicipio = function(aValues, iCodigoMunicipio) {

    var iCodigoMunicipio = iCodigoMunicipio;
    var iNumMunicipios   = aValues.length;

    me.oCboCodigoMunicipio.addItem(0, 'Selecione o município');

    for (var iInd = 0; iInd < iNumMunicipios; iInd++) {
      with (aValues[iInd]) {
        me.oCboCodigoMunicipio.addItem(codigo, descricao.urlDecode());
      }
    }

    if (iCodigoMunicipio != '') {

      me.oCboCodigoMunicipio.setValue(iCodigoMunicipio)
      me.setMunicipio(iCodigoMunicipio);
    }
  }

  this.changeMunicipio = function() {

    if ( $('txtCodigoBairro'+sId).value != '' || $('txtCodigoRua'+sId).value != '' ) {

      var sMessage  = "Usuário:\n\nDeseja alterar o Município?";
          sMessage += "\n\nOs dados abaixo já preenchidos serão perdidos!";
          sMessage += "\n\nDeseja continuar ?";

      if (!confirm(sMessage)) {

        $('cboCodigoMunicipio'+sId).value  = me.getMunicipio();
        return false;

      } else {
        me.clearAll(3);
      }
    }

    me.setMunicipio($F('cboCodigoMunicipio'+sId));
  }

  me.oCboCodigoMunicipio = new DBComboBox('cboCodigoMunicipio'+sId, 'cboCodigoMunicipio'+sId);
  me.oCboCodigoMunicipio.addStyle('width', '100%');
  me.oCboCodigoMunicipio.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oCboCodigoMunicipio.show($('ctnCodigoMunicipio'+sId));
  $('ctnCodigoMunicipio'+sId).observe('change', me.changeMunicipio);

  /**
   * Seta o codigo do Municipio
   * @param {string} iCodigoMunicipio
   * @return void
   */
  this.setMunicipio = function(iCodigoMunicipio){
    this.icodigoMunicipio = iCodigoMunicipio;
  }

  /**
   * Seta o codigo do Municipio
   * @return {string} codigo do municipio
   */
  this.getMunicipio = function(){
    return this.icodigoMunicipio;
  }

  /**
   * Seta a descricao do Municipio do endereco
   * @param {string} sNomeBairro
   * @return void
   */
  this.setNomeMunicipio = function(sNomeMunicipio){
    this.sNomeMunicipio = sNomeMunicipio;
  }

  /**
   * Retorna a descricao do Municipio do endereco
   * @return void
   */
  this.getNomeMunicipio = function(){
    return this.sNomeMunicipio;
  }

/*-------------------------------------Fim da Manipulação do Município------------------------------------------------*/
/*-------------------------------------Início da Manipulação do Bairro------------------------------------------------*/

  /*
   *Metodo para pesquisa da descrição do Bairro pelo codigo
   */
  this.findBairroByCodigo = function() {

    /*
     * Validação para verificar se o usuario ja preencheu o cadastro
     * e esta realizando modificações então exibe alerta na tela.
     */
    if ($('txtCodigoRua'+sId).value != '') {

      var sMessage  = "usuário:\n\nDeseja alterar o Bairro ? ";
          sMessage += "\n\n Os dados abaixo já preenchidos serão perdidos ! ";
          sMessage += "\n\n Deseja continuar ?";
      if (!confirm(sMessage)) {

        $('txtCodigoBairro'+sId).value  = me.getBairro();
        $('txtDescrBairro'+sId).value   = me.getNomeBairro();
        return false;
      }else{
          me.setBairro('');
          me.setNomeBairro('');
          me.clearAll(3);
      }
    }
    //se vazio retorna sem executar a pesquisa
    if ($F('txtCodigoBairro'+sId).trim() == '') {
      $('txtDescrBairro'+sId).value = '';
      me.setBairro('');
      me.setNomeBairro('');
      return false;
    }
    //valida se esta preenchido o codigo do municipio
    if ($F('cboCodigoMunicipio'+sId) == '') {

       $('cboCodigoMunicipio'+sId).value = '';
       return false;
    }

    var msgDiv = "Aguarde pesquisando bairro pelo código.";
    js_divCarregando(msgDiv,'msgBox');


    var oPesquisa              = new Object();
    oPesquisa.exec             = 'findBairroByCodigo';
    oPesquisa.iCodigoBairro    = $F('txtCodigoBairro'+sId);
    oPesquisa.iCodigoMunicipio = $F('cboCodigoMunicipio'+sId);

    var oAjax = new Ajax.Request(
                me.sUrlRpc,
                { parameters: 'json='+Object.toJSON(oPesquisa),
                  method: 'post',
                  onComplete : me.retornofindBairroByCodigo
                }

    );
  }

  this.retornofindBairroByCodigo = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval('('+oAjax.responseText+')');

    if (!oRetorno.sNomeBairro) {

      $('txtDescrBairro'+sId).value  = 'Código ('+$F('txtCodigoBairro'+sId)+') não encontrado!';
      $('txtCodigoBairro'+sId).value = '';
      $('txtCodigoBairro'+sId).focus();
      me.setBairro('');
      me.setNomeBairro('');

    } else {

      $('txtDescrBairro'+sId).value = oRetorno.sNomeBairro.urlDecode();
      me.setBairro($F('txtCodigoBairro'+sId));
      me.setNomeBairro($F('txtDescrBairro'+sId));

    }
  }

  /*
   *Cria o campo código do  Bairro
   */
  me.oTxtCodigoBairro = new DBTextField('txtCodigoBairro'+sId, 'txtCodigoBairro'+sId, '');
  me.oTxtCodigoBairro.addStyle('width', '100%');
  //me.oTxtCodigoBairro.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oTxtCodigoBairro.addEvent('onKeyUp',"js_ValidaCampos(this,1,\"Campo Bairro\",\"f\",\"f\",event)");
  me.oTxtCodigoBairro.show($('ctnCodigoBairro'+sId));
  $('txtCodigoBairro'+sId).observe('change', me.findBairroByCodigo);

  /**
   *Método para detectar a mudanca no campo Descricao do Bairro
   *@private
   *@return void
   */
   this.changeDescrBairro = function() {

     if ($('txtCodigoRua'+sId).value != '' && me.oTxtCodigoBairro.getValue() != '') {

       var sMessage  = "Usuário:\n\nDeseja alterar o Bairro ? ";
           sMessage += "\n\nOs dados abaixo já preenchidos serão perdidos ! ";
           sMessage += "\n\nDeseja continuar ?";

       /**
       *1º caso: usuário cancelou a modificaçao
       */
       if (!confirm(sMessage)) {

         /*
          * Voltar os dados conforme propriedade da classe
          */
          $('txtDescrBairro'+sId).value   = me.getNomeBairro();
          $('txtCodigoBairro'+sId).value  = me.getBairro();
          me.setRuasTipo(me.getRuaTipo());
          me.iBairroAutomatico = false;
          return false;

       } else if (me.iBairroAutomatico) {

          me.setBairro($('txtCodigoBairro'+sId).value);
          me.setNomeBairro($('txtDescrBairro'+sId).value);
          me.iBairroAutomatico = false;
       } else {

          me.setBairro('');
          $('txtCodigoBairro'+sId).value = '';
          me.setNomeBairro($('txtDescrBairro'+sId).value);
          me.iBairroAutomatico = false;

       }
       me.clearAll(4);

    } else if (me.iBairroAutomatico) {

       me.setNomeBairro($('txtDescrBairro'+sId).value);
       me.setBairro($('txtCodigoBairro'+sId).value);
       me.setRuasTipo(me.oCboRuasTipo.getValue());
       me.iBairroAutomatico = false;
     } else {

       me.setNomeBairro($('txtDescrBairro'+sId).value);
       me.setBairro('');
       $('txtCodigoBairro'+sId).value = '';
       me.setRuasTipo(me.oCboRuasTipo.getValue());
       me.iBairroAutomatico = false;
     }
     $('txtDescrBairro'+sId).value = $F('txtDescrBairro'+sId).toUpperCase();
     me.iBairroAutomatico = false;
  }

  /*
   *Cria o campo descrição do Bairro
   */
  me.oTxtDescrBairro = new DBTextField('txtDescrBairro'+sId, 'txtDescrBairro'+sId, '');
  me.oTxtDescrBairro.addStyle('width', '100%');
  me.oTxtDescrBairro.show($('ctnDescrBairro'+sId));
  $('txtDescrBairro'+sId).observe('change', me.changeDescrBairro)
  /*
   *Função para realizar a busca pelo autocomplete do Bairro
   */
  var sUrl = this.sUrlRpc;
  var oParam  = new Object();
  oAutoCompleteBairro = new dbAutoComplete($('txtDescrBairro'+sId), sUrl);
  oAutoCompleteBairro.setTxtFieldId($('txtCodigoBairro'+sId));
  oAutoCompleteBairro.show();
  oAutoCompleteBairro.setQueryStringFunction(function () {
    /*
     *Função para validar se deve disparar a busca do autocomplete
     */
    oAutoCompleteBairro.setValidateFunction(function() {

      if (($F('cboCodigoMunicipio'+sId).trim() == '')) {
        return false;
      } else {
        return true;
      }
    });
    oParam.exec = 'findBairroByName';
    oParam.iCodigoEstado    = $F('cboCodigoEstado'+sId);
    oParam.iCodigoMunicipio = $F('cboCodigoMunicipio'+sId).trim();
    oParam.sQuery = encodeURIComponent(tagString($F('txtDescrBairro'+sId)));
    sQuery       = 'json='+Object.toJSON(oParam);
    return sQuery;
  });
 /**
  *Seta o codigo do bairro
  *@param {string} iCodigoBairro
  *@return void
  */
  this.setBairro = function(iCodigoBairro){
    this.iCodigoBairro = iCodigoBairro;
  };
 /**
  *Retorna o codigo do bairro
  *@return {string} codigo do bairro
  */
  this.getBairro = function(){
    return this.iCodigoBairro;
  };


  oAutoCompleteBairro.setCallBackFunction(function(id, label) {

     me.oTxtCodigoBairro.setValue(id);
     me.oTxtDescrBairro.setValue(label);
     me.findComplementoBairro(id);
  });

  /**
   *Metodo utilizado para buscar os dados complementares do bairro e preencher os campos acima dele
   */
  this.findComplementoBairro = function(id) {

    var oRua        = new Object();
    oRua.exec       = 'findComplementoBairro';
    oRua.iCodigoBairro = id;

    var msgDiv = "Aguarde ...";
    js_divCarregando(msgDiv,'msgBox');

    var oAjax = new Ajax.Request(
                me.sUrlRpc,
                { parameters: 'json='+Object.toJSON(oRua),
                  method: 'post',
                  onComplete : me.retornofindComplementoBairro
                }

    );

  }

  this.retornofindComplementoBairro = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval('('+oAjax.responseText+')');
    if (oRetorno.status == 2) {

      alert('Falha ao retornar os complementos.');
      me.iBairroAutomatico = false;
    } else {

      if ($F('cboCodigoMunicipio'+sId) == '') {

        $('cboCodigoMunicipio'+sId).value = oRetorno[0].icodigomunicipio;
        me.setMunicipio($F('cboCodigoMunicipio'+sId));
        me.iBairroAutomatico = true;
      }

      $('txtCodigoBairro'+sId).value = oRetorno[0].icodigobairro;
      $('txtDescrBairro'+sId).value  = oRetorno[0].sdescrbairro.urlDecode();
      me.iBairroAutomatico = true;
    }
  }

//-------------------------------------Fim da Manipulação do Bairro-----------------------------------------------------
//-------------------------------------Início da Manipulação da Rua-----------------------------------------------------
  /**
   *Metodo para pesquisa a descrição da Rua pelo codigo
   */
  this.findRuaByCodigo = function() {

    if ( $F('txtCodigoNumero'+sId) != '') {

      var sMessage  = "Usuário:\n\nDeseja alterar a Rua ? ";
          sMessage += "\n\nOs dados abaixo já preenchidos serão perdidos ! ";
          sMessage += "\n\nDeseja continuar ?";

      if (!confirm(sMessage)) {

        $('txtCodigoRua'+sId).value   = me.getRua();
        return false;
      }

      $('txtDescrRua'+sId).value = '';
      me.clearAll(5);
    }

    if ($F('txtCodigoRua'+sId).trim() == '') {

      $('txtDescrRua'+sId).value = '';
      me.setNomeRua('');
      me.setRua('');
      me.oCboRuasTipo.setEnable();
      //me.oCboRuasTipo.setValue(3);

      return false;
    }

    var oPesquisa              = new Object();
    oPesquisa.exec             = 'findRuaByCodigo';
    oPesquisa.iCodigoRua       = $F('txtCodigoRua'+sId);
    oPesquisa.iCodigoMunicipio = $F('cboCodigoMunicipio'+sId);

    var msgDiv = "Aguarde pesquisando rua pelo código.";
    js_divCarregando(msgDiv,'msgBox');

    var oAjax = new Ajax.Request(
      me.sUrlRpc,
      {
        parameters: 'json='+Object.toJSON(oPesquisa),
        method: 'post',
        onComplete : me.retornofindRuaByCodigo
      }
    );
  }

  this.retornofindRuaByCodigo = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval('('+oAjax.responseText+')');

    if (!oRetorno.dados) {

      $('txtDescrRua'+sId).value  = 'Código ('+$F('txtCodigoRua'+sId)+') não encontrado!';
      $('txtCodigoRua'+sId).value = '';
      $('txtCodigoRua'+sId).focus();
      me.setRua('');
      me.setNomeRua('');
      me.oCboRuasTipo.setEnable();
    } else {

      $('txtDescrRua'+sId).value = oRetorno.dados[0].db74_descricao.urlDecode();
      //$('txtDescrBairro'+sId).value = oRetorno.dados[0].db73_descricao.urlDecode();
      //$('txtCodigoBairro'+sId).value = oRetorno.dados[0].db73_sequencial;
      me.setRua($F('txtCodigoRua'+sId));
      me.setNomeRua($F('txtDescrRua'+sId));
      //me.setBairro($F('txtCodigoBairro'+sId));
      //me.setNomeBairro($F('txtDescrBairro'+sId));
      me.setRuasTipo(oRetorno.dados[0].db85_sequencial);
      me.oCboRuasTipo.setValue(oRetorno.dados[0].db85_ruastipo);
      //me.oCboRuasTipo.setDisable();

    }
  }

  /*
   *Cria o campo código do Rua
   */
  me.oTxtCodigoRua = new DBTextField('txtCodigoRua'+sId, 'txtCodigoRua'+sId, '');
  me.oTxtCodigoRua.addStyle('width', '100%');
  //me.oTxtCodigoRua.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oTxtCodigoRua.addEvent('onKeyUp',"js_ValidaCampos(this,1,\"Campo Rua\",\"f\",\"f\",event)");
  me.oTxtCodigoRua.show($('ctnCodigoRua'+sId));
  $('txtCodigoRua'+sId).observe('change', me.findRuaByCodigo);

  /*
   *Cria o campo descrição do Rua
   */
  me.oTxtDescrRua = new DBTextField('txtDescrRua'+sId, 'txtDescrRua'+sId, '');
  me.oTxtDescrRua.addStyle('width', '450px');
  me.oTxtDescrRua.show($('ctnDescrRua'+sId));

  this.changeDescrRua = function () {

    /*
     * No evento onChange do campo descrição realiza
     * validação para verificar se o campo imediato abaixo
     * possui um codigo se o mesmo tiver um codigo alerta o usuario
     * que os dados abaixo serão perdidos. Oferece a opção de continuar ou
     * cancelar a modificação e preservar os dados informados
     */
     if ($('txtCodigoNumero'+sId).value != '' && me.oTxtCodigoRua.getValue() != '') {

       var sMessage  = "Usuário:\n\nDeseja alterar a Rua ? ";
           sMessage += "\n\nOs dados abaixo já preenchidos serão perdidos ! ";
           sMessage += "\n\nDeseja continuar ?";

       /**
       *1º caso: usuário cancelou a modificaçao
       */
       if (!confirm(sMessage)) {

         /*
          * Voltar os dados conforme propriedade da classe
          */
          $('txtDescrRua'+sId).value   = me.getNomeRua();
          $('txtCodigoRua'+sId).value  = me.getRua();
          me.setRuasTipo(me.getRuaTipo());
          me.iEnderecoAutomatico = false;
          return false;

       } else if (me.iEnderecoAutomatico) {

          me.setRua($('txtCodigoRua'+sId).value);
          me.setNomeRua($('txtDescrRua'+sId).value);


          me.iEnderecoAutomatico = false;
       } else {

          me.setRua('');
          $('txtCodigoRua'+sId).value = '';
          me.setNomeRua($('txtDescrRua'+sId).value);
          me.iEnderecoAutomatico = false;

       }

       me.clearAll(5);

     } else if (me.iEnderecoAutomatico) {

       me.setNomeRua($('txtDescrRua'+sId).value);
       me.setRua($('txtCodigoRua'+sId).value);
       me.setRuasTipo(me.oCboRuasTipo.getValue());
       me.iEnderecoAutomatico = false;
     } else {

       me.setNomeRua($('txtDescrRua'+sId).value);
       me.setRua('');
       //$('txtCodigoRua'+sId).value = '';
       me.setRuasTipo(me.oCboRuasTipo.getValue());
       me.iEnderecoAutomatico = false;
     }

     $('txtDescrRua'+sId).value = $F('txtDescrRua'+sId).toUpperCase();
     me.iEnderecoAutomatico = false;

     if ($F('txtCodigoRua'+sId).trim() == '') {
       me.oCboRuasTipo.setEnable();
     }
  }

  $('txtDescrRua'+sId).observe('change', me.changeDescrRua);

  /*
   *Função para realizar a busca pelo autocomplete da Rua
   */
  var sUrl = this.sUrlRpc;
  var oParam  = new Object();
  oAutoCompleteRua = new dbAutoComplete($('txtDescrRua'+sId), sUrl);
  oAutoCompleteRua.setTxtFieldId($('txtCodigoRua'+sId));
  oAutoCompleteRua.show();

  /*
   *Função para validar se deve disparar a busca do autocomplete
   */
  oAutoCompleteRua.setValidateFunction(function() {

    if (($F('cboCodigoMunicipio'+sId).trim() == '') || (($F('txtCodigoBairro'+sId).trim() == '') && ($F('txtDescrBairro'+sId).trim() != ''))) {
      return false;
    } else {
      return true;
    }
  });

  oAutoCompleteRua.setQueryStringFunction(function () {
    oParam.exec = 'findRuaByName';
    oParam.iCodigoEstado    = $F('cboCodigoEstado'+sId);
    oParam.iCodigoMunicipio = $F('cboCodigoMunicipio'+sId).trim();
    oParam.iCodigoBairro    = $F('txtCodigoBairro'+sId).trim();
    oParam.sQuery = encodeURIComponent(tagString($F('txtDescrRua'+sId)));
    sQuery       = 'json='+Object.toJSON(oParam);
    return sQuery;
  });

  oAutoCompleteRua.setCallBackFunction(function(id, label) {

    me.oTxtCodigoRua.setValue(id);
    me.oTxtDescrRua.setValue(label);
    me.findComplementoRua(id);
  });

  /**
   *Metodo de pesquisa dos dados complementares da Rua pelo número informado
   */
  this.findComplementoRua = function(id) {

    var oRua              = new Object();
    oRua.exec             = 'findComplementoRua';
    oRua.iCodigoRua       = id;
    oRua.iCodigoMunicipio = $F('cboCodigoMunicipio'+sId);
    oRua.iCodigoBairro    = $F('txtCodigoBairro'+sId);

    var msgDiv = "Aguarde ...";
    js_divCarregando(msgDiv,'msgBox');

    var oAjax = new Ajax.Request(
      me.sUrlRpc,
      {
        parameters: 'json='+Object.toJSON(oRua),
        method: 'post',
        onComplete : me.retornofindComplementoRua
      }
    );

  }

  this.retornofindComplementoRua = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval('('+oAjax.responseText+')');
    if (oRetorno.status == 2) {

      alert('Falha ao retornar os complementos.');
      me.iEnderecoAutomatico = false;
    } else {

      $('cboCodigoMunicipio'+sId).value = oRetorno[0].icodigomunicipio;
      me.setMunicipio($F('cboCodigoMunicipio'+sId));

      $('txtCodigoBairro'+sId).value    = oRetorno[0].icodigobairro;
      me.setBairro($F('txtCodigoBairro'+sId));
      $('txtDescrBairro'+sId).value     = oRetorno[0].sdescrbairro.urlDecode();
      me.setNomeBairro($F('txtDescrBairro'+sId));
      $('txtDescrRua'+sId).value        = oRetorno[0].sdescrrua.urlDecode();
      //me.setNomeRua($F('txtDescrRua'+sId));
      $('txtCodigoRua'+sId).value       = oRetorno[0].icodigorua;
      //me.setRua($F('txtCodigoRua'+sId));
      me.oCboRuasTipo.setValue(oRetorno[0].icodigoruatipo);
      //me.oCboRuasTipo.setDisable();
      me.setRuaTipo(oRetorno[0].icodigoruatipo);
      me.setRuasTipo(oRetorno[0].icodigoruastipo);
      me.iEnderecoAutomatico = true;
      if (oRetorno[0].db76_cep != "") {

        $('txtCepEnd'+sId).value = oRetorno[0].db76_cep;
        me.setCepEndereco($F('txtCepEnd'+sId));
      }
      $('txtCodigoNumero'+sId).focus();
    }
  }

  me.oCboRuasTipo = new DBComboBox('cboRuasTipo'+sId, 'cboRuasTipo'+sId);
  me.oCboRuasTipo.addStyle('width', '60px');

  me.oCboRuasTipo.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oCboRuasTipo.show($('ctnCboRuasTipo'+sId));
  /**
   *Metodo disparado quando e modificado o select tipos de rua
   *seta a propriedade com o nova alteracao
   */
  this.changeRuasTipo = function() {

	me.setModificado(true);
    me.setRuaTipo(me.oCboRuasTipo.getValue());
  }

  $('ctnCboRuasTipo'+sId).observe('change', me.changeRuasTipo);

//-------------------------------------Fim da Manipulação da Rua--------------------------------------------------------
//-------------------------------------Inicio da Manipulação do Numero--------------------------------------------------
  /**
   *Metodo de pesquisa dos dados complementares da Rua pelo número informado
   */
  this.findNumeroByNumero = function() {

    //Reset nas propriedades que são dependentes do codigo do número.
    me.setCodigoLocal('');
    me.setCodigoEndereco('');
    me.setLoteamento('');
    me.setCondominio('');
    me.setComplemento('');
    me.setPontoReferencia('');
    //me.setCepEndereco('');

    me.setNumero($F('txtCodigoNumero'+sId));
    //Desabilita o bota de pesquisa
    $('btnPesquisarNumero'+sId).disabled = true;

    if ($F('txtCodigoRua'+sId) == '') {

      return false;
    }

    var oPesquisa           = new Object();
    oPesquisa.exec          = 'findNumeroByNumero';
    oPesquisa.iCodigoRua    = $F('txtCodigoRua'+sId);
    oPesquisa.iCodigoBairro = $F('txtCodigoBairro'+sId);
    oPesquisa.iCodigoNumero = $F('txtCodigoNumero'+sId);
    var msgDiv = "Aguarde pesquisando numero.";
    js_divCarregando(msgDiv,'mensagemBuscaRua');

    var oAjax = new Ajax.Request(
                me.sUrlRpc,
                { parameters: 'json='+Object.toJSON(oPesquisa),
                  method: 'post',
                  onComplete : function (oAjax) { me.retornofindNumeroByNumero(oAjax); }
                }

    );
  }

  this.retornofindNumeroByNumero = function (oAjax) {

	js_removeObj("mensagemBuscaRua");
    var oRetorno = eval('('+oAjax.responseText+')' );
    if (oRetorno.dados != false){
      $('btnPesquisarNumero'+sId).disabled = true;
      me.exibeGridRua(oRetorno.dados);
      }else{
      me.setModificado(true);
      $('txtDescrCondominio'+sId).value      = '';
      $('txtDescrLoteamento'+sId).value      = '';
      $('txtDescrComplemento'+sId).value     = '';
      $('txtDescrPontoReferencia'+sId).value = '';
    }

  }

  /**
   *Metodo para montar uma janela que exibe
   *todos os complementos cadastrados para a rua e numero informado
   */

  this.exibeGridRua = function(aDados) {

    var iWidthGrid     = 600;
    var iWheigthGrid   = 350 ;
    me.oWindowGridRua  = new windowAux('wndGridRua'+me.sId, 'Cadastros da Rua', iWidthGrid, iWheigthGrid);

    var sContentGridRua  = "<div  id='ctnMessageBoardRua'>";
        sContentGridRua += "  <div style='width:100%' id='GridRua"+me.sId+"'>";
        sContentGridRua += "  </div>";
        sContentGridRua += "</div>";

    me.oWindowGridRua.setContent(sContentGridRua);
    me.oWindowGridRua.show();

    me.oWindowGridRua.setShutDownFunction(function () {
      $('btnPesquisarNumero'+sId).disabled = false;
      me.oWindowGridRua.destroy();
    });


    //Define a janela por cima da q chamou
    me.oWindowGridRua.setChildOf(me.oWindowEndereco);
    /**
     *Defincao da Grid que exibe os complementos cadastrados para a rua e nuemro selecionado
     */
    me.oGridViewRua = new DBGrid('oGridViewRua');
    me.oGridViewRua.nameInstance = sNameInstance + '.oGridViewRua';
    me.oGridViewRua.sName        = 'oGridViewRua';
    me.oGridViewRua.setHeight(300);
    me.oGridViewRua.setCellWidth(new Array('10%',
    		                               '10%',
                                           '10%',

                                           '10%',
                                           '10%',
                                           '10%',

                                           '10%',
                                           '10%',
                                           '10%',

                                           '10%'));

    me.oGridViewRua.setHeader(new Array('Cep',
    		                            'Número',
                                        'Complemento',

                                        'Loteamento',
                                        'Condomínio',
                                        'SeqLocal',

                                        'SeqEnder',
                                        'PontoRef',
                                        'SeqRuasTipo',

                                        'RuaTipo'));
    me.oGridViewRua.setHeight(200);
    me.oGridViewRua.aHeaders[5].lDisplayed = false;//(false,5);
    me.oGridViewRua.aHeaders[6].lDisplayed = false;//(false,6);
    me.oGridViewRua.aHeaders[7].lDisplayed = false;//(false,7);
    me.oGridViewRua.aHeaders[8].lDisplayed = false;//(false,8);
    me.oGridViewRua.aHeaders[9].lDisplayed = false;//(false,9);
    var iNumDados = aDados.length;

    me.oGridViewRua.show($('GridRua'+me.sId));
    me.oGridViewRua.clearAll(true);

    if (iNumDados > 0) {

      aDados.each(
                  function (oRua, iIndRua) {
                    var aRow = new Array();

                    var sCep = (oRua.db76_cep == '' || oRua.db76_cep == 'null') ? '' : oRua.db76_cep;

                    aRow[0] = sCep;
                    aRow[1] = oRua.db75_numero;
                    aRow[2] = oRua.db76_complemento.urlDecode();
                    aRow[3] = oRua.db76_loteamento.urlDecode().substring(0,30);
                    aRow[4] = oRua.db76_condominio.urlDecode().substring(0,30);
                    aRow[5] = oRua.db75_sequencial;
                    aRow[6] = oRua.db76_sequencial;
                    aRow[7] = oRua.db76_pontoref.urlDecode();
                    aRow[8] = oRua.db85_sequencial;
                    aRow[9] = oRua.db85_ruastipo;

                    me.oGridViewRua.addRow(aRow);
                    me.oGridViewRua.aRows[iIndRua].sEvents = "onClick='"+me.sNameInstance+".setEndereco("+iIndRua+");'";

                  }

                  );
      me.oGridViewRua.renderRows();
      $('btnPesquisarNumero'+sId).disabled = true;

    }

  }

  /**
   *Metodo que e disparado quando e apresentada a grid com
   *os enderecos e algum endereco e selecionado.
   *Preenche os dados complementares do endereco
   */

  this.setEndereco = function(iIndLinha) {

    $('txtDescrCondominio'+sId).value  = me.oGridViewRua.aRows[iIndLinha].aCells[4].getValue().trim();
    $('txtDescrLoteamento'+sId).value  = me.oGridViewRua.aRows[iIndLinha].aCells[3].getValue().trim();
    $('txtDescrComplemento'+sId).value = me.oGridViewRua.aRows[iIndLinha].aCells[2].getValue().trim();
    $('txtDescrPontoReferencia'+sId).value = me.oGridViewRua.aRows[iIndLinha].aCells[7].getValue().trim();
    $('txtCepEnd'+sId).value           = me.oGridViewRua.aRows[iIndLinha].aCells[0].getValue().trim();
    me.setCodigoLocal(me.oGridViewRua.aRows[iIndLinha].aCells[5].getValue());
    me.setCodigoEndereco(me.oGridViewRua.aRows[iIndLinha].aCells[6].getValue());
    me.setRuaTipo(me.oGridViewRua.aRows[iIndLinha].aCells[9].getValue());
    me.setRuasTipo(me.oGridViewRua.aRows[iIndLinha].aCells[8].getValue());
    me.setNumero($F('txtCodigoNumero'+sId));
    me.setCepEndereco($F('txtCepEnd'+sId));
    me.setComplemento($F('txtDescrComplemento'+sId));
    me.setCondominio($F('txtDescrCondominio'+sId));
    me.setPontoReferencia($F('txtDescrPontoReferencia'+sId));
    me.setLoteamento($F('txtDescrLoteamento'+sId));

    me.oWindowGridRua.destroy();
    $('btnPesquisarNumero'+sId).disabled = false;
  }

  /*
   *Cria o campo código do Número
   */
  me.oTxtCodigoNumero = new DBTextField('txtCodigoNumero'+sId, 'txtCodigoNumero'+sId, '');
  me.oTxtCodigoNumero.addStyle('width', '100%');
  //me.oTxtCodigoNumero.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oTxtCodigoNumero.addEvent('onKeyUp',"js_ValidaCampos(this,1,\"Campo Código Rua\",\"f\",\"f\",event)");
  me.oTxtCodigoNumero.show($('ctnCodigoNumero'+sId));
  $('txtCodigoNumero'+sId).observe('change', me.findNumeroByNumero);
  /*
  this.btnPesquisarDisable = function() {
    $('btnPesquisarNumero'+sId).disabled = true;
  }
  $('txtCodigoNumero'+sId).observe('keyup', me.btnPesquisarDisable);
  */

//-------------------------------------Fim da Manipulação do Número-----------------------------------------------------
//-------------------------------------Inicio da Manipulação do Cep do Endereco-----------------------------------------
  /*
   *Cria o campo de cep do endereco
   */
  me.oTxtCepEnd = new DBTextField('txtCepEnd'+sId, 'txtCepEnd'+sId, '');
  //me.oTxtCepEnd.addEvent('onKeyPress', 'return js_mask(event, "0-9|")');
  me.oTxtCepEnd.addEvent('onKeyUp',"js_ValidaCampos(this,1,\"Campo Cep Endereço\",\"f\",\"f\",event)");
  me.oTxtCepEnd.addStyle('width', '100%');
  me.oTxtCepEnd.setMaxLength(8);
  me.oTxtCepEnd.show($('ctnCodigoCepEnd'+sId));
  /**
   *Metodo disparado quando e modificado o campo cep
   *seta a propriedade com o nova alteracao
   */
  this.onchangeCepEnd = function() {
    if ($F('txtCepEnd'+sId).length != 8 && $F('txtCepEnd'+sId).trim() != '') {

      alert('usuário:\n\nO Cep informado possui menos de 8 digitos.\n\nVerifique !\n\n');
      $('txtCepEnd'+sId).focus();
      return false;
    }
    me.setCepEndereco($F('txtCepEnd'+sId));
    me.setModificado(true);
  }


  $('txtCepEnd'+sId).observe('change', me.onchangeCepEnd);

//-------------------------------------Fim da Manipulação do Cep do Endereco--------------------------------------------
//-------------------------------------Início da Manipulação do Condomínio----------------------------------------------
  /*
   *Cria o campo descrição do Condominio
   */
  me.oTxtDescrCondominio = new DBTextField('txtDescrCondominio'+sId, 'txtDescrCondominio'+sId, '');
  me.oTxtDescrCondominio.addStyle('width', '100%');
  me.oTxtDescrCondominio.show($('ctnDescrCondominio'+sId));
  /**
   *Metodo disparado quando e modificado o campo ponto de condominio
   *seta a propriedade com o nova alteracao
   */
  this.alteraCondominio = function() {

    $('txtDescrCondominio'+sId).value = $F('txtDescrCondominio'+sId).toUpperCase();

    if ($F('txtDescrCondominio'+sId).trim() != me.getCondominio()) {

      me.setCondominio($F('txtDescrCondominio'+sId));
      me.setModificado(true);
    }
  }
  $('txtDescrCondominio'+sId).observe('change', me.alteraCondominio);

//-------------------------------------Fim da Manipulação do Condomínio-------------------------------------------------
//-------------------------------------Início da Manipulação do Loteamento----------------------------------------------

  /*
   *Cria o campo descrição do Loteamento
   */
  me.oTxtDescrLoteamento = new DBTextField('txtDescrLoteamento'+sId, 'txtDescrLoteamento'+sId, '');
  me.oTxtDescrLoteamento.addStyle('width', '100%');
  me.oTxtDescrLoteamento.show($('ctnDescrLoteamento'+sId));
  /**
   *Metodo disparado quando e modificado o campo ponto de loteamento
   *seta a propriedade com o nova alteracao
   */
  this.alteraLoteamento = function() {

    $('txtDescrLoteamento'+sId).value = $F('txtDescrLoteamento'+sId).toUpperCase();

    if ($F('txtDescrLoteamento'+sId).trim() != me.getLoteamento()) {
      me.setLoteamento($F('txtDescrLoteamento'+sId));
      me.setModificado(true);
    }
  }

  $('txtDescrLoteamento'+sId).observe('change', me.alteraLoteamento);

  /*
   *Função para realizar a busca pelo autocomplete do Loteamento
   */
  var sUrl = this.sUrlRpc;
  var oParam  = new Object();
  oAutoCompleteCondominio = new dbAutoComplete($('txtDescrLoteamento'+sId), sUrl);
  oAutoCompleteCondominio.setTxtFieldId($('txtCodigoNumero'+sId));
  oAutoCompleteCondominio.show();
  oAutoCompleteCondominio.setQueryStringFunction(function () {

    oParam.exec = 'findLoteamentoByName';
    oParam.iCodigoBairro = $F('txtCodigoBairro'+sId);
    oParam.iCodigoRua    = $F('txtCodigoRua'+sId);
    oParam.iCodigoNumero = $F('txtCodigoNumero'+sId);
    oParam.sQuery = encodeURIComponent(tagString($F('txtDescrLoteamento'+sId)));
    sQuery       = 'json='+Object.toJSON(oParam);
    return sQuery;
  });

//-------------------------------------Fim da Manipulação do Loteamento-------------------------------------------------
//-------------------------------------Início da Manipulação do Complemento---------------------------------------------

  /*
   *Cria o campo descrição do Complemento
   */
  me.oTxtDescrComplemento = new DBTextField('txtDescrComplemento'+sId, 'txtDescrComplemento'+sId, '');
  me.oTxtDescrComplemento.addStyle('width', '100%');
  me.oTxtDescrComplemento.show($('ctnDescrComplemento'+sId));
  /**
   *Metodo disparado quando e modificado o campo complemento
   *seta a propriedade com o nova alteracao
   */
  this.alteraComplemento = function() {

    $('txtDescrComplemento'+sId).value = $F('txtDescrComplemento'+sId).toUpperCase();

    if ($F('txtDescrComplemento'+sId).trim() != me.getComplemento()) {

      me.setCodigoEndereco('');
      me.setComplemento($F('txtDescrComplemento'+sId));
      me.setModificado(true);
    }
  }

  $('txtDescrComplemento'+sId).observe('change', me.alteraComplemento);

//-------------------------------------Fim da Manipulação do Complemento------------------------------------------------
//-------------------------------------Início da Manipulação do Ponto de Referência-------------------------------------

  this.alteraPontoReferencia = function() {
    //$('txtDescrPontoReferencia'+sId).value = $F('txtDescrPontoReferencia'+sId).toUpperCase();

    if ($F('txtDescrPontoReferencia'+sId).trim() != me.getPontoReferencia()) {

      me.setPontoReferencia($F('txtDescrPontoReferencia'+sId));
      me.setModificado(true);
    }
  }

  $('txtDescrPontoReferencia'+sId).observe('change', me.alteraPontoReferencia);

//-------------------------------------Fim da Manipulação do Ponto de Referência----------------------------------------

  /**
   *Método que exibe a tela de cadastro de endereco
   */
  this.show = function() {
    me.oWindowEndereco.show();
  }

  /**
   *Seta a descricao do ponto de referencia do endereco
   *@param {string} sPontoReferencia
   *@return void
   */

  this.setPontoReferencia = function(sPontoReferencia){

    this.sPontoReferencia = sPontoReferencia;
  }

  /**
   *Retorna a descricao do ponto de referencia do endereco
   *@return {string} descricao do ponto de referencia
   */

  this.getPontoReferencia = function(){

    return this.sPontoReferencia;
  }

  /**
   *Seta a descricao do loteamento do endereco
   *@param {string} sLoteamento
   *@return void
   */

  this.setLoteamento = function(sLoteamento){

    this.sLoteamento = sLoteamento;
  }

  /**
   *Retorna a decricao do loteamento do endereco
   *@return {string} descricao do loteamento
   */

  this.getLoteamento = function(){

    return this.sLoteamento;
  }

  /**
   *Seta a descricao do condominio do endereco
   *@param {string} sCondominio nome do condominio
   *@return void
   */

  this.setCondominio = function(sCondominio){

    this.sCondominio = sCondominio;
  }

  /**
   *Retorna a descricao do condominio do endereco
   *@return {string} nome do condominio
   */

  this.getCondominio = function(){

    return this.sCondominio;
  }

  /**
   *Seta o complemento do endereco
   *@param {string} sComplemento complemento do endereco
   *@return void
   */

  this.setComplemento = function(sComplemento){

    this.sComplemento = sComplemento;
  }

  /**
   *Retorna o complemnto do endereco
   *@return {string} sComplemento
   */

  this.getComplemento = function(){

    return this.sComplemento;
  }

  /**
   *Seta o numero do endereco
   *@param {string} sNumero
   *@return void
   */

  this.setNumero = function(sNumero){

    this.sNumero = sNumero;
  }

  /**
   *Retorna o numero do endereco
   *@return {string} sNumero
   */

  this.getNumero = function(){

    return this.sNumero;
  }

  /**
   *Seta o codigo do pais do endereco
   *@param {string} iCodigoPais
   *@return void
   */

  this.setPais = function(iCodigoPais){

    this.iCodigoPais = iCodigoPais;
  }

  /**
   *Retorna o codigo pais do endereco
   *@return {string} iCodigoPais
   */

  this.getPais = function(){

   return this.iCodigoPais;
  }

  /**
   *Seta a descricao do pais do endereco
   *@param {string} sNomeBairro
   *@return void
   */

  this.setNomePais = function(sNomePais){

    this.sNomePais = sNomePais;
  }

  /**
   *Retorna a descricao do Pais do endereco
   *@return {string} sNomePais
   */

  this.getNomePais = function(){

    return this.sNomePais;
  }

  /**
   *Seta o codigo do estado do endereco
   *@param {string} iCodigoEstado
   *@return void
   */

  this.setEstado = function(iCodigoEstado){

    this.iCodigoEstado = iCodigoEstado;
  }

  /**
   *Retorna o codigo do estado do endereco
   *@return void
   */

  this.getEstado = function(){

    return this.iCodigoEstado;
  }


  /**
   *Seta a descricao do bairro do endereco
   *@param {string} sNomeBairro
   *@return void
   */

  this.setNomeBairro = function(sNomeBairro){
    //$('txtDescrPontoReferencia'+sId).value = sNomeBairro;
    this.sNomeBairro = sNomeBairro;
  }

  /**
   *Retorna a descricao do bairro do endereco
   *@return void
   */

  this.getNomeBairro = function(){

    return this.sNomeBairro;
  }

  /**
   *Seta o codigo do logradouro
   *@param {string} iCodigoLogradouro
   *@return void
   */

  this.setLogradouro = function(iCodigoLogradouro){

    this.iCodigoLogradouro = iCodigoLogradouro;
  }

  /**
   *Retorna o codigo do Logradouro
   *@return void
   */

  this.getLogradouro = function(){

    return this.iCodigoLogradouro;
  }

  /**
   *Seta o codigo da local do endereco
   *@param {string} iCodigoLocal
   *@return void
   */

  this.setCodigoLocal = function(iCodigoLocal){

    this.iCodigoLocal = iCodigoLocal;
  }

  /**
   *Retorna o codigo da local do endereco
   *@param {string} iCodigoLocal
   *@return void
   */

  this.getCodigoLocal = function(){

    return this.iCodigoLocal;
  }

  /**
   *Seta o codigo do endereco
   *@param {string} iCodigoEndereco
   *@return void
   */

  this.setCodigoEndereco = function(iCodigoEndereco){

    this.iCodigoEndereco = iCodigoEndereco;
  }

  /**
   *Retorna o codigo do endereco
   *@return string com o codigo
   */

  this.getCodigoEndereco = function(){
    return this.iCodigoEndereco;
  }

  /**
   *Seta o codigo da rua do endereco
   *@param {string} iCodigoRua
   *@return void
   */

  this.setRua = function(iCodigoRua) {

    this.iCodigoRua = iCodigoRua;
  }

  /**
   *Retorna o codigo da rua do endereco
   *@return string
   */

  this.getRua = function() {

    return this.iCodigoRua;
  }

  /**
   *Seta o valor do campo Cep do endereco
   *@param {string} sCepEnder string contendo o cep
   *@return void
   */

  this.setCepEndereco = function(sCepEnder) {

    this.sCepEndereco = sCepEnder;
  }
  /**
   * Retorna o valor do Cep
   * @return string
   */

  this.getCepEndereco = function() {

    return this.sCepEndereco;
  }

 /**
  * Método utilizado para verificar se foi informado o código
  * de endereço caso tenha sido carrega os dados para alteração
  * se não foi informado carrega a tela com os campos padrões
  * conforme configurações.
  */
  this.buscaEndereco = function() {

    if (me.getCgmMunicipio() == true && me.getCodigoRuaMunicipio() != "") {

      me.clearAll(1);
      var oEndereco = new Object();
      oEndereco.exec = 'buscaBairroRuaMunicipio';
      oEndereco.icodigoruamunicipio    = me.getCodigoRuaMunicipio();
      oEndereco.icodigobairromunicipio = me.getCodigoBairroMunicipio();
      oEndereco.lCgmMunicipio          = true;

      var msgDiv = "Aguarde ...";
      js_divCarregando(msgDiv,'msgBox');

      var oAjax = new Ajax.Request(
        me.sUrlRpc,
        {
          parameters: 'json='+Object.toJSON(oEndereco),
          method: 'post',
          onComplete : me.retornoBuscaBairroRuaMunicipio
        }
      );

    }

    else if (me.getCodigoEndereco() == '') {

      me.clearAll(1);
      var oEndereco = new Object();
      oEndereco.exec = 'buscaValoresPadrao';
      oEndereco.icodigoendereco = me.getCodigoEndereco();

      var msgDiv = "Aguarde ...";
      js_divCarregando(msgDiv,'msgBox');

      var oAjax = new Ajax.Request(
        me.sUrlRpc,
        {
          parameters: 'json='+Object.toJSON(oEndereco),
          method: 'post',
          onComplete : me.retornoBuscaValoresPadrao
        }
      );

    } else {

      var oEndereco = new Object();
      oEndereco.exec = 'buscaEndereco';

      oEndereco.icodigoendereco = me.getCodigoEndereco();

      var msgDiv = "Aguarde pesquisando os dados do endereço.";
      js_divCarregando(msgDiv,'msgBox');

      var oAjax = new Ajax.Request(
        me.sUrlRpc,
        {
          parameters: 'json='+Object.toJSON(oEndereco),
          method: 'post',
          onComplete : me.retornoBuscaEndereco
        }
      );
    }
  }

  /**
   * Processa os dados retornados da consulta para preencher o
   * form com os valores do bairro e rua do municipio
   */
  this.retornoBuscaBairroRuaMunicipio = function (oAjax) {

    js_removeObj('msgBox');

    var oRetorno = eval('('+oAjax.responseText+')');
    var sExpReg  = new RegExp('\\\\n','g');

    if (oRetorno.status == 2) {

      me.close();
      return false;
    }

    if (!oRetorno) {
      alert("usuário:\n\nFalha ao buscar valores padrão!\n\nContate o administrador.\n\n");
    } else {

      $('cboCodigoPais'+sId).value = oRetorno.valoresPadrao[0].db70_sequencial;
      me.setPais(oRetorno.valoresPadrao[0].db70_sequencial);
      var iEstado = oRetorno.valoresPadrao[0].db72_cadenderestado;

      me.preencheCboEstados(oRetorno.estados, iEstado);
      me.preencheCboRuasTipo(oRetorno.tiposRua, 3);

      if (oRetorno.municipio == false) {

        me.setMunicipio('');
        me.setNomeMunicipio('');
      } else {

        me.setMunicipio(oRetorno.valoresPadrao[0].db72_sequencial);
        $('cboCodigoMunicipio'+sId).value = me.getMunicipio();
      }


      $('txtDescrRua'+sId).value = oRetorno.bairroRuaMunicipio[0].srua.urlDecode();
      me.oTxtDescrRua.setReadOnly(true);
      $('txtCodigoRua'+sId).value = oRetorno.bairroRuaMunicipio[0].irua;
      me.oTxtCodigoRua.setReadOnly(true);
      me.setRua($('txtCodigoRua'+sId).value);
      $('txtDescrBairro'+sId).value = oRetorno.bairroRuaMunicipio[0].sbairro.urlDecode();
      me.oTxtDescrBairro.setReadOnly(true);
      $('txtCodigoBairro'+sId).value = oRetorno.bairroRuaMunicipio[0].ibairro;
      me.setBairro($('txtCodigoBairro'+sId).value);
      me.oTxtCodigoBairro.setReadOnly(true);
      me.oCboRuasTipo.setValue(oRetorno.bairroRuaMunicipio[0].iruatipo);
      me.setRuaTipo(oRetorno.bairroRuaMunicipio[0].iruatipo);
      me.setRuasTipo(oRetorno.bairroRuaMunicipio[0].iruastipo);
      $('txtCepEnd'+sId).value = oRetorno.cepmunic[0].cep;
      me.setCepEndereco($F('txtCepEnd'+sId));
      me.oTxtCepEnd.setReadOnly(true);
    }
  }
  /**
   * Processa os dados retornados da consulta para preencher o
   * form com os valores padrao
   */
  this.retornoBuscaValoresPadrao = function (oAjax) {

    js_removeObj('msgBox');

    var oRetorno = eval('('+oAjax.responseText+')');
    var sExpReg  = new RegExp('\\\\n','g');

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));
      me.close();
      return false;
    }

    if (!oRetorno) {

      alert("usuário:\n\nFalha ao buscar valores padrão!\n\nContate o administrador.\n\n");

    } else {

      var iEstado = oRetorno.valoresPadrao[0].db72_cadenderestado;
      var iPais   = oRetorno.valoresPadrao[0].db70_sequencial;

      me.preencheCboPaises(oRetorno.aPaises, iPais);
      me.preencheCboEstados(oRetorno.estados, iEstado);
      me.preencheCboRuasTipo(oRetorno.tiposRua, 3);

      if (oRetorno.municipio == false) {

        me.setMunicipio('');
        me.setNomeMunicipio('');
      }

    }
  }

    /**
   * Processa os dados retornados da consulta do
   * endereço informado para preencher o
   * form com os valores retornados
   */

  this.retornoBuscaEnderecoCidadao = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval('('+oAjax.responseText+')');

    var sExpReg  = new RegExp('\\\\n','g');

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));
    } else {

      $('cboCodigoPais'+sId).value            = oRetorno.endereco.iPais;
      me.setPais(oRetorno.endereco.iPais);

      $('cboCodigoMunicipio'+sId).value       = oRetorno.endereco.iMunicipio;
      me.setMunicipio($F('cboCodigoMunicipio'+sId))

      $('txtCodigoBairro'+sId).value          = oRetorno.endereco.iBairro;
      me.setBairro($F('txtCodigoBairro'+sId));

      $('txtDescrBairro'+sId).value           = oRetorno.endereco.sBairro.urlDecode();
      me.setNomeBairro($F('txtDescrBairro'+sId));

      $('txtCodigoRua'+sId).value             = oRetorno.endereco.iRua;
      me.setRua($F('txtCodigoRua'+sId));

      $('txtDescrRua'+sId).value              = oRetorno.endereco.sRua.urlDecode();
      me.setNomeRua($F('txtDescrRua'+sId));

      $('txtCodigoNumero'+sId).value          = oRetorno.endereco.sNumeroLocal.urlDecode();
      me.setNumero($F('txtCodigoNumero'+sId));

      $('txtCepEnd'+sId).value                = oRetorno.endereco.sCep.urlDecode();
      me.setCepEndereco($F('txtCepEnd'+sId));

      $('txtDescrCondominio'+sId).value       = oRetorno.endereco.sCondominio.urlDecode();
      me.setCondominio($F('txtDescrCondominio'+sId));

      $('txtDescrLoteamento'+sId).value       = oRetorno.endereco.sLoteamento.urlDecode();
      me.setLoteamento($F('txtDescrLoteamento'+sId));

      $('txtDescrPontoReferencia'+sId).value  = oRetorno.endereco.sPontoReferencia.urlDecode();
      me.setPontoReferencia($F('txtDescrPontoReferencia'+sId));

      $('txtDescrComplemento'+sId).value      = oRetorno.endereco.sComplemento.urlDecode();
      me.setComplemento($F('txtDescrComplemento'+sId));

      me.setCepRua(oRetorno.endereco.iCep);

      me.preencheCboEstados(oRetorno.estados,oRetorno.endereco.iEstado);
      me.preencheCboRuasTipo(oRetorno.tiposRua, oRetorno.endereco.iRuaTipo);

      $('btnPesquisarNumero'+sId).disabled = false;
      me.setEstado(oRetorno.endereco.iEstado);

      me.oCboRuasTipo.setValue(oRetorno.endereco.iRuaTipo);

      me.setRuaTipo(oRetorno.endereco.iRuaTipo);
      me.setRuasTipo(oRetorno.endereco.iRuasTipo);
    }

  }

  /**
   * Processa os dados retornados da consulta do
   * endereço informado para preencher o
   * form com os valores retornados
   */
  this.retornoBuscaEndereco = function (oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval('('+oAjax.responseText+')');

    var sExpReg  = new RegExp('\\\\n','g');

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));

    } else {

      $('cboCodigoPais'+sId).value            = oRetorno.endereco.iPais;
      me.setPais(oRetorno.endereco.iPais);

      $('txtCodigoBairro'+sId).value          = oRetorno.endereco.iBairro;
      me.setBairro($F('txtCodigoBairro'+sId));
      $('txtDescrBairro'+sId).value           = oRetorno.endereco.sBairro.urlDecode();
      me.setNomeBairro($F('txtDescrBairro'+sId));

      $('txtCodigoRua'+sId).value             = oRetorno.endereco.iRua;
      me.setRua($F('txtCodigoRua'+sId));
      $('txtDescrRua'+sId).value              = oRetorno.endereco.sRua.urlDecode();
      me.setNomeRua($F('txtDescrRua'+sId));

      $('txtCodigoNumero'+sId).value          = oRetorno.endereco.sNumeroLocal.urlDecode();
      me.setNumero($F('txtCodigoNumero'+sId));

      $('txtCepEnd'+sId).value                = oRetorno.endereco.sCep.urlDecode();
      me.setCepEndereco($F('txtCepEnd'+sId));

      $('txtDescrCondominio'+sId).value       = oRetorno.endereco.sCondominio.urlDecode();
      me.setCondominio($F('txtDescrCondominio'+sId));

      $('txtDescrLoteamento'+sId).value       = oRetorno.endereco.sLoteamento.urlDecode();
      me.setLoteamento($F('txtDescrLoteamento'+sId));

      $('txtDescrPontoReferencia'+sId).value  = oRetorno.endereco.sPontoReferencia.urlDecode();
      me.setPontoReferencia($F('txtDescrPontoReferencia'+sId));

      $('txtDescrComplemento'+sId).value      = oRetorno.endereco.sComplemento.urlDecode();
      me.setComplemento($F('txtDescrComplemento'+sId));

      me.setCepRua(oRetorno.endereco.iCep);

      me.preencheCboPaises(oRetorno.aPaises, oRetorno.endereco.iPais);
      me.preencheCboEstados(oRetorno.estados,oRetorno.endereco.iEstado);
      me.preencheCboRuasTipo(oRetorno.tiposRua, oRetorno.endereco.iRuaTipo);

      $('cboCodigoMunicipio'+sId).value       = oRetorno.iCodigoMunicipio;
      me.setMunicipio($F('cboCodigoMunicipio'+sId));

      $('btnPesquisarNumero'+sId).disabled = false;
      me.setEstado(oRetorno.endereco.iEstado);
      me.oCboRuasTipo.setValue(oRetorno.endereco.iRuaTipo);

      me.setRuaTipo(oRetorno.endereco.iRuaTipo);
      me.setRuasTipo(oRetorno.endereco.iRuasTipo);

      if (me.getCgmMunicipio() == true) {

        var lEnderecoMunicipio = true;
        me.setCgmMunicipio(lEnderecoMunicipio);
        //me.oTxtCep.setReadOnly(lEnderecoMunicipio);
        me.oTxtCodigoPais.setReadOnly(lEnderecoMunicipio);
        me.oTxtDescrPais.setReadOnly(lEnderecoMunicipio);
        me.oCboCodigoEstado.setDisable();
        me.oTxtCodigoMunicipio.setReadOnly(lEnderecoMunicipio);
        me.oTxtDescrMunicipio.setReadOnly(lEnderecoMunicipio);
        me.oTxtCodigoBairro.setReadOnly(lEnderecoMunicipio);
        me.oTxtDescrBairro.setReadOnly(lEnderecoMunicipio);
        me.oTxtCodigoRua.setReadOnly(lEnderecoMunicipio);
        me.oTxtDescrRua.setReadOnly(lEnderecoMunicipio);
        $('btnPesquisarCep'+sId).disabled = lEnderecoMunicipio;
      }

      $('btnSalvar'+sId).value = 'Alterar';
    }

  }
  /*
   * Preenche a combobox dos tipos de rua
   * recebe por parametro array com os dados
   */
  this.preencheCboRuasTipo = function(aValues, iCodigoTipo) {

    var iCodigoTipo = iCodigoTipo;
    var iNumRuasTipo = aValues.length;

    for (var iInd = 0; iInd < iNumRuasTipo; iInd++) {

      with (aValues[iInd]) {
        me.oCboRuasTipo.addItem(codigo, descricao.urlDecode());
      }
    }

    if (iCodigoTipo == '') {
      iCodigoTipo = 3;
    }

    me.oCboRuasTipo.setValue(iCodigoTipo);
    me.setRuaTipo(iCodigoTipo);
  }

  /*
   * Preenche a combobox dos paises recebidos por parametro
   * aValues array de objetos com os paises
   * iCodigoPais valor par deixar selecionado o pais
   */
  this.preencheCboPaises = function(aValues, iCodigoPais) {

    var iNumPaises  = aValues.length;

    me.oCboCodigoPais.addItem(0, 'Selecione o País');

    for (var iInd = 0; iInd < iNumPaises; iInd++) {
      with (aValues[iInd]) {
        me.oCboCodigoPais.addItem(codigo, descricao.urlDecode());
      }
    }

    if (iCodigoPais == '') {
       iCodigoPais = aValues[0].codigo;
    }

    me.oCboCodigoPais.setValue(iCodigoPais);

    if (iNumPaises > 0) {
      me.setPais(iCodigoPais);
    }

    me.findEstadoByCodigoPais();
  }

  /*
   * Preenche a combobox dos estados recebidos por parametro
   * aValues array de objetos com os estados
   * iCodigoEstado valor par deixar selecionado o estado
   */
  this.preencheCboEstados = function(aValues, iCodigoEstado) {

    var iNumEstados = aValues.length;

    me.oCboCodigoEstado.addItem(0, 'Selecione o estado');

    for (var iInd = 0; iInd < iNumEstados; iInd++) {
      with (aValues[iInd]) {
        me.oCboCodigoEstado.addItem(codigo, descricao.urlDecode());
      }
    }

    if (iCodigoEstado == '' && aValues.length > 0) {
      iCodigoEstado = aValues[0].codigo;
    }

    me.oCboCodigoEstado.setValue(iCodigoEstado);

    if (iNumEstados > 0) {
      me.setEstado(iCodigoEstado);
    }

    me.findMunicipioByEstado();
  }

   /*
   *Método para limpar os campos da tela conforme
   *o código informado
   *1 limpa todos abaixo de Pais
   *2 limpa todos abaixo de Estado
   *3 limpa todos abaixo de Municipio
   *4 limpa todos abaixo de Bairro
   *5 limpa todos abaixo de Rua
   *6 limpa todos abaixo de Numero
   */
  this.clearAll = function(iCodigoClear) {
    switch(iCodigoClear) {
      case 1:
        $('cboCodigoPais'+sId).value            = '';
        me.setPais('');
        $('cboCodigoEstado'+sId).length         = 0 ;
        me.setEstado('');
        $('cboCodigoMunicipio'+sId).value       = '';
        me.setMunicipio('');
        me.setNomeMunicipio('');
        $('txtCodigoBairro'+sId).value          = '';
        $('txtDescrBairro'+sId).value           = '';
        me.setBairro('');
        me.setNomeBairro('');
        $('txtCodigoRua'+sId).value             = '';
        $('txtDescrRua'+sId).value              = '';
        me.setRua('');
        me.setNomeRua('');
        $('txtCepEnd'+sId).value                = '';
        me.setCepEndereco('');
        $('txtCodigoNumero'+sId).value          = '';
        me.setNumero('');
        me.setCodigoEndereco('');
        $('txtDescrCondominio'+sId).value       = '';
        me.setCondominio('');
        $('txtDescrLoteamento'+sId).value       = '';
        me.setLoteamento('');
        $('txtDescrComplemento'+sId).value      = '';
        me.setComplemento('');
        $('txtDescrPontoReferencia'+sId).value  = '';
        me.setPontoReferencia('');

        //me.oCboRuasTipo.setValue(3);
        //me.setRuaTipo(3);
        me.setRuasTipo('');
        me.setCepRua('');
        break;

      case 2:
        $('cboCodigoMunicipio'+sId).value       = '';
        me.setMunicipio('');
        $('txtCodigoBairro'+sId).value          = '';
        $('txtDescrBairro'+sId).value           = '';
        me.setBairro('');
        me.setNomeBairro('');
        $('txtCodigoRua'+sId).value             = '';
        $('txtDescrRua'+sId).value              = '';
        me.setRua('');
        me.setNomeRua('');
        $('txtCodigoNumero'+sId).value          = '';
        me.setNumero('');
        $('txtCepEnd'+sId).value                = '';
        me.setCepEndereco('');
        me.setCodigoEndereco('');
        $('txtDescrCondominio'+sId).value       = '';
        me.setCondominio('');
        $('txtDescrLoteamento'+sId).value       = '';
        me.setLoteamento('');
        $('txtDescrComplemento'+sId).value      = '';
        me.setComplemento('');
        $('txtDescrPontoReferencia'+sId).value  = '';
        me.setPontoReferencia('');
        //me.oCboRuasTipo.setValue(3);
        //me.setRuaTipo(3);
        me.setRuasTipo('');
        me.setCepRua('');
        break;
      case 3:

        $('txtCodigoBairro'+sId).value          = '';
        $('txtDescrBairro'+sId).value           = '';
        me.setBairro('');
        me.setNomeBairro('');
        $('txtCodigoRua'+sId).value             = '';
        $('txtDescrRua'+sId).value              = '';
        me.setRua('');
        me.setNomeRua('');
        $('txtCodigoNumero'+sId).value          = '';
        me.setNumero('');
        $('txtCepEnd'+sId).value                = '';
        me.setCepEndereco('');
        me.setCodigoEndereco('');
        $('txtDescrCondominio'+sId).value       = '';
        me.setCondominio('');
        $('txtDescrLoteamento'+sId).value       = '';
        me.setLoteamento('');
        $('txtDescrComplemento'+sId).value      = '';
        me.setComplemento('');
        $('txtDescrPontoReferencia'+sId).value  = '';
        me.setPontoReferencia('');
        //me.oCboRuasTipo.setValue(3);
        //me.setRuaTipo(3);
        me.setRuasTipo('');
        me.setCepRua('');
        break;
      case 4:
        $('txtCodigoRua'+sId).value             = '';
        $('txtDescrRua'+sId).value              = '';
        me.setRua('');
        me.setNomeRua('');
        $('txtCodigoNumero'+sId).value          = '';
        me.setNumero('');
        $('txtCepEnd'+sId).value          = '';
        me.setCepEndereco('');
        me.setCodigoEndereco('');
        $('txtDescrCondominio'+sId).value       = '';
        me.setCondominio('');
        $('txtDescrLoteamento'+sId).value       = '';
        me.setLoteamento('');
        $('txtDescrComplemento'+sId).value      = '';
        me.setComplemento('');
        $('txtDescrPontoReferencia'+sId).value  = '';
        me.setPontoReferencia('');
        //me.oCboRuasTipo.setValue(3);
        //me.setRuaTipo(3);
        me.setRuasTipo('');
        me.setCepRua('');
        break;
      case 5:
        //$('txtDescrRua'+sId).value              = '';
        $('txtCodigoNumero'+sId).value          = '';
        me.setNumero('');
        $('txtCepEnd'+sId).value          = '';
        me.setCepEndereco('');
        me.setCodigoEndereco('');
        $('txtDescrCondominio'+sId).value       = '';
        me.setCondominio('');
        $('txtDescrLoteamento'+sId).value       = '';
        me.setLoteamento('');
        $('txtDescrComplemento'+sId).value      = '';
        me.setComplemento('');
        $('txtDescrPontoReferencia'+sId).value  = '';
        me.setPontoReferencia('');
        //me.oCboRuasTipo.setValue(3);
        //me.setRuaTipo(3);
        me.setRuasTipo('');
        me.setCepRua('');
        break;
      case 6:
        $('txtCepEnd'+sId).value                = '';
        $('txtDescrCondominio'+sId).value       = '';
        $('txtDescrLoteamento'+sId).value       = '';
        $('txtDescrComplemento'+sId).value      = '';
        $('txtDescrPontoReferencia'+sId).value  = '';
        break;
    }
  }

 /**
   *Método para salvar um endereco
   */
  this.salvarEndereco = function() {

	//Aqui verifico se houve modificação e se o código do enedereço esta setado
    if (me.getCodigoEndereco() != '' && me.lModificado == false ) {

      me.close();
      me.callBackFunction();
      return false;
    }

    var oEndereco = new Object();


    if ($F('cboCodigoMunicipio'+sId).trim() == '') {
      me.setMunicipio('');
    }

    //Validações de campo em branco

    //Verifica se o País foi imformado
    if ($F('cboCodigoPais'+sId).value == '' || $F('cboCodigoPais'+sId).value == 0) {

      $('cboCodigoPais'+sId).focus();
      alert("usuário:\n\n\País não informado!\n\n");
      return false;
    }

    //Verifica se o Municipio foi imformado
    if ($F('cboCodigoMunicipio'+sId).trim() == '' && me.getTipoValidacao() == 1) {

      $('cboCodigoMunicipio'+sId).focus();
      alert("usuário:\n\n\Município não informado!\n\n");
      return false;
    } else if (me.getTipoValidacao() == 2 && $F('cboCodigoMunicipio'+sId) == '') {
      me.setMunicipio('0');
      me.setNomeMunicipio('');
    }

    //Verifica se o Bairro foi imformado
    if ($F('txtDescrBairro'+sId).trim() == '' && me.getTipoValidacao() == 1) {

      $('txtCodigoBairro'+sId).focus();
      alert("Usuário:\n\n\Bairro não informado!\n\n");
      return false;
    } else if (me.getTipoValidacao() == 2 && $F('txtDescrBairro'+sId).trim() == '') {

      me.setBairro('0');
      me.setNomeBairro('');
    }

    //Verifica se a Rua foi imformado
    if ($F('txtCodigoRua'+sId).trim() == '' && $F('txtDescrRua'+sId).trim() == '') {

      $('txtCodigoRua'+sId).focus();
      alert("Usuário:\n\n\Nome da rua não informada!\n\n");
      return false;
    }
    //Verifica se o Número foi informado

    if (me.getRua() == '') {
      me.setRuasTipo('');
    }

    oEndereco.codigoEstado             = me.getEstado();
    oEndereco.codigoMunicipio          = me.getMunicipio();
    oEndereco.codigoBairro             = me.getBairro();
    oEndereco.descricaoBairro          = encodeURIComponent(tagString(me.getNomeBairro().toUpperCase()));
    oEndereco.codigoRua                = me.getRua();
    oEndereco.descricaoRua             = encodeURIComponent(tagString(me.getNomeRua().toUpperCase()));
    oEndereco.codigoLocal              = me.getCodigoLocal();
    oEndereco.numeroLocal              = me.getNumero();
    oEndereco.cepEndereco              = me.getCepEndereco();
    oEndereco.codigoEndereco           = me.getCodigoEndereco();
    oEndereco.descricaoComplemento     = encodeURIComponent(tagString(me.getComplemento().toUpperCase()));
    oEndereco.descricaoLoteamento      = encodeURIComponent(tagString(me.getLoteamento().toUpperCase()));
    oEndereco.descricaoCondominio      = encodeURIComponent(tagString(me.getCondominio().toUpperCase()))
    oEndereco.codigoRuasTipo           = me.getRuasTipo();
    oEndereco.codigoRuaTipo            = me.getRuaTipo();
    oEndereco.descricaoPontoReferencia = encodeURIComponent(tagString(me.getPontoReferencia().toUpperCase()));
    oEndereco.codigoCepRua             = me.getCepRua();

    oDados = new Object();
    oDados.exec    = 'salvarEndereco';
    oDados.endereco = oEndereco;

    var msgDiv = "Salvando endereço aguarde ...";
    js_divCarregando(msgDiv,'msgBox');

    var aParam = Object.toJSON(oDados);

    var oAjax = new Ajax.Request(
      me.sUrlRpc,
      {
        parameters: 'json='+Object.toJSON(oDados),
        method: 'post',
        onComplete : me.retornoSalvaEndereco
      }
    );
  }
  /**
   *Metodo que trata o retorno da inclusao ou alteracao de um endereco
   */
  this.retornoSalvaEndereco = function (oAjax) {

    js_removeObj('msgBox');

    var oRetorno = eval('('+oAjax.responseText+')');

    var sExpReg  = new RegExp('\\\\n','g');

    if (oRetorno.status == 1) {
      me.setCodigoEndereco(oRetorno.icodigoEndereco);
      $('cboCodigoMunicipio'+sId).value = oRetorno.icodigoMunicipio;
      me.setMunicipio(oRetorno.icodigoMunicipio);
      $('txtCodigoBairro'+sId).value    = oRetorno.icodigoBairro;
      me.setBairro(oRetorno.icodigoBairro);
      $('txtCodigoRua'+sId).value       = oRetorno.icodigoRua;
      me.setRua(oRetorno.icodigoRua);
      //Fecha a janela e preenche o campo com o endereco informado
      me.close();
      me.callBackFunction();
      return false;
    } else {
      alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));
      me.setCodigoEndereco('');
    }

  }

  /**
   * Retorna se o endereco é do municipio
   * @param bollean lEnderecoMunicipio
   */
  this.getCgmMunicipio = function() {
    return me.lEnderecoMunicipio;
  }

  this.setCgmMunicipio = function(lEnderecoMunicipio) {
    me.lEnderecoMunicipio = lEnderecoMunicipio;
  }

  /**
   *Seta como read only campos do endereco que nao podem ser modificados
   *quando o endereco e do municipio
   *@param bollean lEnderecoMunicipio true se for do município
   *return void
   */

  this.setEnderecoMunicipio = function(lEnderecoMunicipio) {

    me.setCgmMunicipio(lEnderecoMunicipio);
    //me.oTxtCep.setReadOnly(lEnderecoMunicipio);

    if (lEnderecoMunicipio) {

      me.oCboCodigoPais.setDisable();
      me.oCboCodigoEstado.setDisable();
      me.oCboCodigoMunicipio.setDisable();
      me.oCboRuasTipo.setDisable();

    } else {

      me.oCboCodigoPais.setEnable();
      me.oCboCodigoEstado.setEnable();
      me.oCboCodigoMunicipio.setEnable();
      me.oCboRuasTipo.setEnable();

    }

    $('btnPesquisarCep'+sId).disabled = lEnderecoMunicipio;
    $('cboCodigoMunicipio'+sId).value = me.getMunicipio();
  }

  /**
   *Retorna o tipo de validacao do formulario de endereco
   *return void
   */
  this.getTipoValidacao = function() {

    return this.iTipoValidacao;
  }
  /**
   *Seta o tipo de validacao do formulario de endereco
   *@param integer iTipoValidacao 1 - forte, 2 - fraca
   *return void
   */
  this.setTipoValidacao = function(iTipoValidacao) {

    this.iTipoValidacao = iTipoValidacao;
  }

  me.buscaEndereco();

  this.limpaForm = function(){
    var iCodEndereco = me.getCodigoEndereco();
    $('txtCep'+sId).value = '';
    me.clearAll(1);
    me.setCodigoEndereco('');
    me.oCboRuasTipo.setEnable();
    me.buscaEndereco();
    me.setCodigoEndereco(iCodEndereco);
  }

  this.setCallBackFunction = function(sFunction) {
    this.callBackFunction = sFunction;
  }
}

/**
 * 'HAck' para utilização da View Endereços
 */
window.repository                  = window.repository                  || {};
window.repository.endereco         = window.repository.endereco         || {};
window.repository.endereco.counter = window.repository.endereco.counter || 0;

window.repository.endereco.addInstance = function( ViewEndereco ) {

  var instanceNumber = window.repository.endereco.counter++;
  window.repository.endereco['DBViewCadastroEndereco_'+ instanceNumber] = ViewEndereco;
  return instanceNumber;
}

window.repository.endereco.getInstance = function(instanceNumber) {
  return window.repository.endereco['DBViewCadastroEndereco_'+ instanceNumber];
};


