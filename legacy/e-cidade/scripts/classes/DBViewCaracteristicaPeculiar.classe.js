DBViewCaracteristicaPeculiar = function(sEstrutural, sInstance, oNode) {

  var me        = this;
  me.sRPC       = "orc4_caracteristicaPeculiar.RPC.php";
  me.instance   = sInstance;
  me.estrutural = sEstrutural;
  me.codigo     = 0;
  
  
  var oParam  = new Object();
  oParam.exec = 'getParametrosPorAno'; 

  var oAjax   = new Ajax.Request(me.sRPC,
                               {method: 'post',
                                asynchronous: false,
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                  
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  me.codigo = oRetorno.iEstruturaCP;
                                }
                               }) ;
                               


  /**
   *  Monta a estrutura a tela padrão com a classe DBViewEstruturaValor
   */
  this.dbViewEstrutural = new DBViewEstruturaValor(me.codigo,
                                                   "Característica Peculiar e Aplicação",
                                                   me.instance+".dbViewEstrutural",
                                                   oNode);
  /**
   * Seta a ajuda da tela. Somente irá aparecer quando não passarmos oNode para a classe
   */  
  this.dbViewEstrutural.setAjuda("Informe os dados para composição da caracteristica peculiar/aplicação.");
  
  /**
   * Insere novos campos na view padrão
   */
   
  var oTabelaDados = $('tblDados');
  var oElementTr   = document.createElement("tr");
  var oElementLabelId = document.createElement("td");
  
  /**
   *  Conteúdo da célula
   */
  var sLabelClassificacao  = "<a onclick='"+me.instance+".pesquisaClassificacao(true);'";
      sLabelClassificacao += "style='text-decoration: underline;' class='dbancora' href='#'><b>Classificação:</b></a>"; 

  oElementLabelId.innerHTML = sLabelClassificacao;

  var oElementTdTxt        = document.createElement("td");
      oElementTdTxt.id     = 'ctnTxtClassificacao';
      oElementTdTxt.noWrap = 'noWrap';

  var oSpanCodigoClassificacao    = document.createElement("span");
      oSpanCodigoClassificacao.id = 'ctnTxtCodigoClassificacao';

  var oSpanDescClassificacao    = document.createElement("span");
      oSpanDescClassificacao.id = 'ctnTxtDescClassificacao';   

  oElementTdTxt.appendChild(oSpanCodigoClassificacao);
  oElementTdTxt.appendChild(oSpanDescClassificacao);

  oElementTr.appendChild(oElementLabelId);
  oElementTr.appendChild(oElementTdTxt);
  oTabelaDados.appendChild(oElementTr);

  /**
   * Cria um input do tipo text para armazenar o código da Classificação
   */
  me.txtCodigoClassificacao = new DBTextField('txtCodigoClassificacao', me.instance+".txtCodigoClassificacao", '', 8);
  me.txtCodigoClassificacao.addEvent("onChange", ";"+me.instance+".pesquisaClassificacao(false)");
  me.txtCodigoClassificacao.show($('ctnTxtCodigoClassificacao'));

  /**
   * Cria um input do tipo text para armazenar a descrição da Classificação
   */
  me.txtDescricaoClassificacao = new DBTextField('txtDescricaoClassificacao', me.instance+".txtDescricaoClassificacao", '', 35);
  me.txtDescricaoClassificacao.show( $('ctnTxtDescClassificacao') );
  me.txtDescricaoClassificacao.setReadOnly(true);
  
 
  // Mostra na Tela o objeto os elementos adicionados.
  this.dbViewEstrutural.show();

  /**
   *  Função Pesquisa Classificacao
   *  Pesquisa a classificação, pode abrir uma lookup ou auto-preencher
   */
  me.pesquisaClassificacao = function(lMostra) {
    
    var iCodClass = $('txtCodigoClassificacao').getValue();
    
    if ( lMostra ) {
      js_OpenJanelaIframe('', 
                         'db_iframe_concarpeculiarclassificacao',
                         'func_concarpeculiarclassificacao.php?funcao_js=parent.'+me.instance+'.preencheClass|c09_sequencial|c09_descricao',
                         'Selecione uma Classificação...',
                          true);
    } else {
      
      if (iCodClass != '') { 
        js_OpenJanelaIframe('', 
                            'db_iframe_concarpeculiarclassificacao', 
                            'func_concarpeculiarclassificacao.php?pesquisa_chave='+iCodClass+
                            '&funcao_js=parent.'+me.instance+'.completaClass',
                            '',false);
      } else {
        $('txtDescricaoClassificacao').setValue('');
      }
    }
    
  }
  
  /**
   * Função PreencheClass
   * Esta função preenche os inputs com os dados da classificação selecionados pelo
   * usuário dentro da lookup
   */
  me.preencheClass = function(iChave,sDescricao) {
  
    $('txtCodigoClassificacao').setValue(iChave);
    $('txtDescricaoClassificacao').setValue(sDescricao);
    db_iframe_concarpeculiarclassificacao.hide();
  }
  
  /**
   *  Função CompletaClass
   *  Esta função completa os dados da classificação quando digitado pelo usuário
   */
  me.completaClass = function(sDescricao, sErro) {
  
    if (!sErro) {
      $('txtDescricaoClassificacao').setValue(sDescricao);
    } else {
      $('txtCodigoClassificacao').setValue('');
      $('txtDescricaoClassificacao').setValue(sDescricao);
    }
  }
  
  
  me.salvar = function() {
  
    /**
     * Validação do formulário. Verifica se todos campos foram preenchidos
     */
    var sValEstrutural    = me.dbViewEstrutural.txtEstrutural.getValue();
    var sValDescricao     = me.dbViewEstrutural.txtDescricao.getValue();
    var iValTipo          = me.dbViewEstrutural.cboTipoEstrutural.getValue();
    var iValClassificacao = me.txtCodigoClassificacao.getValue();
  
    if ( sValEstrutural == "" ) {
      alert("Código estrutural não informado.");
      return false;
    }
    if ( sValDescricao == "" ) {
      alert("Informe a descrição da configuração!");
      return false;
    }
    if ( iValClassificacao == "" ) {
      alert("Informe a classificação da configuração.");
      return false;
    }
  
    
    js_divCarregando('Salvando...', 'msgBox');

    var oParam                = new Object();
        oParam.exec           = "salvarConfiguracao";
        oParam.iEstruturaCP   = me.codigo;
        oParam.sEstrutural    = sValEstrutural;
        oParam.sDescricao     = encodeURIComponent(tagString(sValDescricao));
        oParam.iTipo          = iValTipo;
        oParam.iClassificacao = iValClassificacao;
        
    var oAjax = new Ajax.Request(me.sRPC,
                                {method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax){
                                   me.onSaveComplete(oAjax);
                                }
                                });
    
  }
  
  /**
   *  Retorno da conclusão do processo
   */
  me.onSaveComplete = function(oRetorno) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oRetorno.responseText+")");
    alert(oRetorno.message.urlDecode());
    me.limpaFormulario();
  }
  
  /**
   * Função que busca os dados para alterá-los
   */
  me.getDados = function(iCodigo) {
  
    var oParam         = new Object();
        oParam.exec    = 'getDadosCaracteristica';
        oParam.iCodigo = iCodigo;
        
    var oAjax = new Ajax.Request(me.sRPC,
                                  {
                                    method: 'post',
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: function (oAjax) {

                                      var oRetorno = eval("("+oAjax.responseText+")");                                
																	    me.dbViewEstrutural.txtEstrutural.setValue(oRetorno.c58_sequencial);
																	    me.dbViewEstrutural.txtDescricao.setValue(oRetorno.c58_descr.urlDecode());
																	    me.dbViewEstrutural.cboTipoEstrutural.setValue(oRetorno.db121_tipoconta);
																	    me.txtCodigoClassificacao.setValue(oRetorno.c58_tipo);
																	    me.pesquisaClassificacao(false);																	    
                                    }
                                  }
                                );
        
  
  }
  

  me.remover = function (sEstrutura) {
  
    
    var oParam             = new Object();
        oParam.exec        = 'removerCaracteristica';
        oParam.sEstrutural = sEstrutura;
        
    js_divCarregando('Excluindo Característica, aguarde...', 'msgBox');
    var oAjax = new Ajax.Request(me.sRPC,
                                  {
                                    method: 'post',
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: function (oAjax) {

                                      js_removeObj('msgBox');
                                      var oRetorno = eval("("+oAjax.responseText+")");                                
                                      me.dbViewEstrutural.txtEstrutural.setValue(0);
                                      me.dbViewEstrutural.txtDescricao.setValue('');
                                      me.dbViewEstrutural.cboTipoEstrutural.setValue(1);
                                      me.txtCodigoClassificacao.setValue('');
                                      
                                      alert(oRetorno.message.urlDecode());
                                    }
                                  }
                                );
  }
  
  /**
   * Esta função observa o "keyup" do campo txtDescricao e transforma tudo em uppercase
   */
  $('txtDescricao').observe('keyup', function() {

    $('txtDescricao').style.textTransform = 'uppercase';
    var sCampo = $F('txtDescricao').toUpperCase();
    $('txtDescricao').value = sCampo;
  });
  
  
  me.limpaFormulario = function () {
  
    $('txtEstrutural').setValue('');
    $('txtDescricao').setValue('');
    $('txtCodigoClassificacao').setValue('');
    $('txtDescricaoClassificacao').setValue('');
  }

}