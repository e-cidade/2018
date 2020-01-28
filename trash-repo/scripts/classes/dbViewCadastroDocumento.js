/**
 * Componente para o controle dos dados dos documentos do sistema ( Inclusão e Alteração ). 
 * Cria uma janela contendo o formulário do documento com todos atributos cadastrados.   
 *    
 *   
 * @class dbViewCadastroDocumento
 * 
 */
function dbViewCadastroDocumento() {

  var me   = this;
  
  
  
  /**
   *  RPC principal do componente
   */
  var sUrl = 'lancaDocumentosRPC.php';
  
  /**
   * Flag que indica se o componente está sendo usada em uma consulta
   */
  var lConsulta = false;

  /**
   *  Função de retorno do método save. 
   *  Deve ser definida para a inclusão de um novo documento
   */
  this.sSaveCallBackFunction = null;

  
  /**
   *  Método que cria a janela principal do documento  
   */      
  this.showForm = function () {
  
    me.window = new windowAux("windowCadDoc","Vincular Documento", 550, 400);
 
	  var sHTML  = "<table width='95%' align=center>";
	      sHTML += "  <tr> ";
	      sHTML += "    <td align=center>";
	      sHTML += "      <form name='form_documento' id='form_documento' action=''>";
	      sHTML += "        <table style='margin-top:15px;' width='95%' align=center>";
	      sHTML += "          <tr>                ";
	      sHTML += "            <td align=center> ";
	      sHTML += "              <fieldset>                        ";
	      sHTML += "                <legend>                        ";
	      sHTML += "                  <b>Atributos do Documento</b> ";
	      sHTML += "                </legend>                       ";
	      sHTML += "                <table id='form_doc' border=0>";
	      sHTML += "                </table>";
	      sHTML += "              </fieldset>";       
	      sHTML += "            </td> ";
	      sHTML += "          </tr>";
	      sHTML += "        </table>";
	      sHTML += "        <table align=center border=0>";
	      sHTML += "          <tr>";
	      sHTML += "            <td align=center>";
	      if (me.lConsulta) {
	        
	        sHTML += "            <input type=button value='Salvar'   name='salvar' id='salvar' style='display:none;'>"
	        sHTML += "            <input type=button value='Fechar' name='cancelar' id='cancelar'>";
	      } else {
	        
	        sHTML += "            <input type=button value='Salvar'   name='salvar'   id='salvar'>"
	        sHTML += "            <input type=button value='Cancelar' name='cancelar' id='cancelar'>";
	      }
	      sHTML += "            </td> ";
	      sHTML += "          </tr>";
	      sHTML += "        </table>";
	      sHTML += "      </form>";
	      sHTML += "    </td>";
	      sHTML += "  </tr>";
	      sHTML += "</table>";
	      
	  this.window.setContent(sHTML);
	  
	  oMessage = new DBMessageBoard('msgboard1', 
	                                'Lançar valores para atributos do documento',
	                                '',
	                                $("windowwindowCadDoc_content"));
	  oMessage.show();

    me.window.show();
	  
	  /**
	   * Define ações dos botões da janela 
	   */
	  me.window.setShutDownFunction(function(){
	    me.window.destroy();
	  });
	     
	  $('cancelar').onclick = function () { 
	    me.window.destroy() 
	  };
	  
	  $('salvar').onclick = function () { 
	    me.save();        
	  };  
    
  }  

  
  /**
   * Método que salva os dados do documento, caso seja informado o código do documento 
   * por parâmetro então irá alterar os dados do documento já existente
   *
   * @param  {integer} iCodDocumento  Código do documento
   */      
  this.save = function (iCodDocumento) {
  
    js_divCarregando('Salvando Dados...','msgBox');
   
    aForm    = $('form_documento').getElements();
    var aAtr = new Array();
    var i    = 0;
    
    aForm.each(function(input) {
      if (js_search_in_array(me.aInputsValidos, input.name)) {
      
		    var oAtributo              = new Object();
		    oAtributo.atributo         = input.name;
		    oAtributo.valor            = input.value;
		    oAtributo.iCodCadDocumento = me.iCodCadDocumento;        
        
        aAtr[i] = oAtributo;
        i++;            
      }      
    }); 
    
    var lAltera = false;    
    var oJson   = new Object();
    
    if ( iCodDocumento == null) {
      oJson.sMethod       = "salvaDocumento";
    } else {
      oJson.sMethod       = "alteraDocumento";
      oJson.iCodDocumento = iCodDocumento
      lAltera = true;
    }
    
    oJson.aAtributos = aAtr; 

    var oAjax = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+Object.toJSON(oJson), 
                                          onComplete: function (oAjax) { me.retorno_save(oAjax,lAltera) }
                                        }
                                   );
        
  }  

  /**
   *  Método de retorno do método save
   *
   *  @param  {object} oAjax  Retorno do RPC
   *  @param  {bool}   lAtera Variável que informa se o retorno é de uma alteração
   */
  this.retorno_save = function (oAjax, lAltera ) {
    
    js_removeObj("msgBox");
    
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 2) {
    
	    alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
    
      if (!lAltera) {
        /**
         * Caso seja uma inclusão então é chamado a função de retorno para o save. 
         * Sempre é passado por parâmetro o código do documento gerado
         */
		    me.getSaveCallBackFunction(oRetorno.iCodDocumento);
      }
      
      me.window.destroy();
    }
  
  }


 /**
  * Retorna os dados dos atributos cadastrados para um documento 
  * a partir do código do cadastro de documento informado
  *
  * @param  {integer} iCodCadDocumento
  */  
  this.getFormDocumento = function (iCodCadDocumento) {
  
    js_divCarregando('Carregando formulário...','msgBox');
  
    var oJson              = new Object();
    oJson.sMethod          = "carregaFormDocumento";
    oJson.iCodCadDocumento = iCodCadDocumento;
             
    var oAjax   = new Ajax.Request( sUrl, {
	                                          method: 'post', 
	                                          parameters: 'json='+Object.toJSON(oJson), 
	                                          onComplete: me.retorno_getFormDocumento 
                                          }
                                   );
                                      
  }
  
  /**
   * Método de retorno do método getFormDocumento
   */
  this.retorno_getFormDocumento = function (oAjax) {
  
    js_removeObj("msgBox");
 
    var oRetorno    = eval("("+oAjax.responseText+")");
    
    me.createFormDocument(oRetorno.aAtributos);
    
  }    
  
 
  this.geraLookUp = function(tabela, campo, campo_form) {
    
    var lookUp = "";
    
    lookUp += "function js_pesquisa"+tabela+campo+"(mostra){";
    lookUp += "  if(mostra==true){";
    lookUp += "    js_OpenJanelaIframe('','db_iframe_"+tabela+campo+"','func_"+tabela+".php?funcao_js=parent.js_mostra"+tabela+campo+"1|"+campo+"','Pesquisa',true);";
    lookUp += "    $('Jandb_iframe_"+tabela+campo+"').style.zIndex = '999999999';";
    lookUp += "  }else{";
    lookUp += "      $('"+campo_form+"').value = '';"; 
    lookUp += "  }";
    lookUp += "}\n"; 
    
    lookUp += "function js_mostra"+tabela+campo+"1(chave1){";
    lookUp += "  $('"+campo_form+"').value = chave1;";
    lookUp += "  db_iframe_"+tabela+campo+".hide();";
    lookUp += "}\n";
    
    return lookUp;
  }

  
  /**
	 * Cria os campos na janela para cada atributo informado
	 *  
   * @param {array} aAtributos	 
	 */
  this.createFormDocument = function(aAtributos){

    if (aAtributos.length == 0 ) {
      alert('Nenhum atributo informado!');
      return false;
    } 
 
    me.aInputsValidos = new Array();
    var iInd          = 0;
 
    aAtributos.each(function(oAtributo) {
         
      var input         = "";
      var codigo        = oAtributo.db45_sequencial;
      var descricao     = oAtributo.db45_descricao.urlDecode();
      var valor_default = oAtributo.db45_valordefault.urlDecode();
      var referencia    = oAtributo.referencia;
      var iTamanhoCampo = oAtributo.db45_tamanho;
      
      me.aInputsValidos[iInd] = codigo;
      iInd++
         
         

      switch (oAtributo.db45_tipo) {
        case "1": //TIPO VARCHAR
        
          eval("db_textfield_v"+codigo+"            = new DBTextField("+codigo+",'db_textfield_v"+codigo+"','"+valor_default+"','40')");
          eval("db_textfield_v"+codigo+".iMaxLength = '"+iTamanhoCampo+"'");
          eval("db_textfield_v"+codigo+".onKeyDown  = 'return js_controla_tecla_enter(this,event);'");
          eval("db_textfield_v"+codigo+".onKeyUp    = 'js_ValidaCampos(this,0,\""+descricao+"\",\"f\",\"t\",event)'");
          eval("db_textfield_v"+codigo+".onBlur     = 'js_ValidaMaiusculo(this,\"t\",event)'");
          eval("db_textfield_v"+codigo+".sStyle     = 'text-transform: uppercase;'");
          eval("db_textfield_v"+codigo+".makeInput()");
          eval("input = db_textfield_v"+codigo+".toInnerHtml()");
          break;
             
        case "2": //TIPO INTEGER
        
          eval("db_textfield_i"+codigo+"            = new DBTextField("+codigo+",'db_textfield_i"+codigo+"','"+valor_default+"', '20')");
          eval("db_textfield_i"+codigo+".iMaxLength = '"+iTamanhoCampo+"'");
          eval("db_textfield_i"+codigo+".onKeyUp    = 'js_ValidaCampos(this,1,\""+descricao+"\",\"f\",\"f\",event)'");
          eval("db_textfield_i"+codigo+".makeInput()");
          eval("input = db_textfield_i"+codigo+".toInnerHtml()");
          break;
             
        case "3": //TIPO DATE
        
          eval("db_textfield_date"+codigo+" = new DBTextFieldData("+codigo+",'db_textfield_date"+codigo+"','"+valor_default+"','')");
          eval("input = db_textfield_date"+codigo+".toInnerHtml()");
          break;
             
        case "4": //TIPO FLOAT
        
          eval("db_textfield_f"+codigo+"           = new DBTextField("+codigo+",'db_textfield_f"+codigo+"','"+valor_default+"','20')");
          eval("db_textfield_f"+codigo+".iMaxLength= '"+iTamanhoCampo+"'");
          eval("db_textfield_f"+codigo+".onKeyUp   = 'js_ValidaCampos(this,4,\""+descricao+"\",\"f\",\"f\",event)'"); 
          eval("db_textfield_f"+codigo+".onKeyDown = 'return js_controla_tecla_enter(this,event)'");
          eval("db_textfield_f"+codigo+".onBlur    = 'js_ValidaMaiusculo(this,\"f\",event)'");
          eval("db_textfield_f"+codigo+".makeInput()");
          eval("input = db_textfield_f"+codigo+".toInnerHtml()");
          break;
             
        case "5": //TIPO BOOLEAN
        
          input  = "<select name="+codigo+" id="+codigo+">";
          input += " <option value='t' "+ (valor_default=='t'?"selected":"") +"> SIM </option>";
          input += " <option value='f' "+ (valor_default=='f'?"selected":"") +"> NÃO </option>";
          input += "</select>";
          break;
      }
      
      if (referencia != null) {
        descricao = "<a class='dbancora' onclick='js_pesquisa"+referencia.tabela+referencia.campo+"(true)' href='#'>"+descricao+"</a>";
           
        var oScript        = document.createElement("script");
        oScript.innerHTML  += me.geraLookUp(referencia.tabela, referencia.campo, codigo);
        document.getElementsByTagName("head")[0].appendChild(oScript);            
      }
         
      var shtml  = "<tr>";
          shtml += " <td align=left>";
          shtml += "<b>" +descricao+ "</b>: ";
          shtml += " </td>";
          shtml += " <td>"; 
          shtml += input;
          shtml += "</td>";
          shtml += "</tr>";
      
      
      $('form_doc').innerHTML += shtml;
       
    });     
  
  }


  /**
   *  Cria uma nova janela com os atributos apartir do código do cadastro de documento informado 
   * 
   *  @param {integer} iCodCadDocumento   
   */
  this.newDocument = function(iCodCadDocumento){
    
    me.showForm();
    me.getFormDocumento(iCodCadDocumento);
  }

  /**
   *  Cria uma nova janela com os atributos e seus valores já preenchidos apartir do código documento informado 
   * 
   *  @param {integer} iCodDocumento   
   *  @param {boolean} lConsulta flag que define se cadastro aparece como consulta 
   */
  this.loadDocument = function(iCodDocumento, lConsulta) {
    
    me.lConsulta = lConsulta;
  
    js_divCarregando('Carregando Formulário...','msgBox');
  
    var oJson           = new Object();
    oJson.sMethod       = "loadDocumento";
    oJson.iCodDocumento = iCodDocumento;
    oJson.lConsulta     = lConsulta;
    
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: 'json='+Object.toJSON(oJson), 
                                            onComplete: me.retorno_loadDocumento 
                                          }
                                  );  
  }
  

  /**
   * Método de retorno para o método loadDocument
   */
  this.retorno_loadDocumento = function (oAjax) {
  
    var oRetorno  = eval("("+oAjax.responseText+")");
    var aValores  = oRetorno.aValores;
    
    me.showForm();
    
    // Oculta botão "salvar" se flag consulta for true
    if (oRetorno.lConsulta == true){
	    $('salvar').style.display = "none";
    } else {
    
      $('salvar').onclick = function () { 
        me.save(oRetorno.iCodDocumento);        
      };
    }
    me.createFormDocument(oRetorno.aAtributos);
 
    aValores.each (function(valor) {
    
      $(valor.db43_caddocumentoatributo).value = valor.db43_valor.urlDecode();
      // Se consulta estiver setada, coloca os campos em modo leitura
      if (me.lConsulta) {
        
        $(valor.db43_caddocumentoatributo).setAttribute("readonly", "readonly");
        $(valor.db43_caddocumentoatributo).style.backgroundColor = "#DEB887";
        if ($('dtjs_'+valor.db43_caddocumentoatributo)) {
          
          $('dtjs_'+valor.db43_caddocumentoatributo).style.display='none';
        }
      }
    });
        
    js_removeObj("msgBox");
     
  }

  /**
   *  Define a função de retorno para o método save 
   * 
   *  @param {function} sFunction   
   */
  this.setSaveCallBackFunction = function(sFunction){
    me.sSaveCallBackFunction = sFunction;
  }

  /**
   *  Retorna a função de retorno do método save passando por parâmetro o código do documento gerado 
   * 
   *  @param {function} iCodDocumento   
   */
  this.getSaveCallBackFunction = function(iCodDocumento){
    me.sSaveCallBackFunction(iCodDocumento);
  }  

}