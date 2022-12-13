/**
 * view para visualização dos detalhes de uma contacorrentedetalhe
 * 
 */
var DBViewContaCorrenteDetalhe = function(sInstance) {

  var me             = this;
  var sUrlRPC        = "con4_contacorrente.RPC.php";
  var sInstanceName  = sInstance;
  this.oElementos    = new Object();
  
  if ($('windowDetalhes')) {
    return false;
  }
  this.getDetalhe = function(iDetalhe){
	  
	var oParametros              = new Object();
	var msgDiv                   = "Pesquisando detalhes. \n Aguarde ...";
	oParametros.exec             = 'getDetalheContaCorrente';  
	oParametros.iDetalhe         = iDetalhe; 

	js_divCarregando(msgDiv,'msgBox');
	 
	 var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                           {method: "post",
	                                            parameters:'json='+Object.toJSON(oParametros),
	                                            onComplete: me.js_retornoDetalhes
	                                           });
  }
  
  this.js_retornoDetalhes = function (oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");
	
    $('ctnSequencial_new').innerHTML             = oRetorno.aDados.c19_sequencial;
    $('ctnContaCorrente_new').innerHTML          = oRetorno.aDados.c17_descricao;
    $('ctnTipoReceita_new').innerHTML            = oRetorno.aDados.o15_descr;
    $('ctnInstituicao_new').innerHTML            = oRetorno.aDados.nomeinst;
    $('ctnCaracteristicaPeculiar_new').innerHTML = oRetorno.aDados.c58_descr.urlDecode();
    $('ctnContaBancaria_new').innerHTML          = oRetorno.aDados.c19_contabancaria.urlDecode();
    $('ctnReduzido_new').innerHTML               = oRetorno.aDados.c19_reduz;
    $('ctnNumeroEmpenho_new').innerHTML          = oRetorno.aDados.c19_numemp;
    $('ctnNome_new').innerHTML                   = oRetorno.aDados.z01_nome.urlDecode();
    $('ctnAnoUnidade_new').innerHTML             = oRetorno.aDados.c19_orcunidadeanousu;
    $('ctnOrgaoUnidade_new').innerHTML           = oRetorno.aDados.c19_orcunidadeorgao.urlDecode();
    $('ctnUnidade_new').innerHTML                = oRetorno.aDados.c19_orcunidadeunidade.urlDecode();
    $('ctnOrgao_new').innerHTML                  = oRetorno.aDados.c19_orcorgaoorgao.urlDecode();
    $('ctnAnoOrgao_new').innerHTML               = oRetorno.aDados.c19_orcorgaoanousu;
    $('ctnAcordo_new').innerHTML                 = oRetorno.aDados.c19_acordo;
    $('ctnAnoReduzido_new').innerHTML            = oRetorno.aDados.c19_conplanoreduzanousu; 
  };	
  
  this.show = function (iCodigoDetalhe) {
	  
	  var c19_sequencial = iCodigoDetalhe;
	  var iLarguraJanela = 650;
	  var iAlturaJanela  = 450;	  
	  
	  windowDetalhes   = new windowAux( 'windowDetalhes',
	                                    'Consulta de Detalhamento',
	                                    iLarguraJanela, 
	                                    iAlturaJanela
	                                    );
	  
	  var sConteudoDetalhes  = "<div>";
	      sConteudoDetalhes += "<div id='sTituloWindow'></div> "; // container do message box

		sConteudoDetalhes += "  <center>  ";
		
        sConteudoDetalhes += "  <br>  <table style='width: 98%;'border = 0 >  ";
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td style='width:30%;'>   ";
        sConteudoDetalhes += "         <strong> Sequencial: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnSequencial_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 

        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Conta Corrente: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnContaCorrente_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 

        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Tipo de Receita: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnTipoReceita_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 

        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Instituição: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnInstituicao_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 

        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong>Característica Peculiar: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnCaracteristicaPeculiar_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 

        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Conta Bancária: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnContaBancaria_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Reduzido: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnReduzido_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Ano do Reduzido: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnAnoReduzido_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    ";         
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Número do Empenho: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnNumeroEmpenho_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Nome: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnNome_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Ano da Unidade: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnAnoUnidade_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Órgão da Unidade: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnOrgaoUnidade_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Unidade: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnUnidade_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Órgão: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnOrgao_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Ano do Orgão: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnAnoOrgao_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
        
        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <strong> Acordo: </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "        <td>   ";
        sConteudoDetalhes += "         <span id='ctnAcordo_new'></span>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    "; 
       
		sConteudoDetalhes += "    </table>";
	    sConteudoDetalhes += "  </center> ";
	      
	      sConteudoDetalhes += "</div>";
	      
	   windowDetalhes.setContent(sConteudoDetalhes);
	 
	   //============  MESAGE BORD PARA TITULO da JANELA 
	  var sTextoMessageBoard  = 'Detalhamento do registro selecionado. <br> ';
	      messageBoard        = new DBMessageBoard('msgboard1',
	                                               'Detalhamento da Conta Corrente.',
	                                                sTextoMessageBoard,
	                                                $('sTituloWindow'));
	                                                
	    //funcao para corrigir a exibição do window aux, apos fechar a primeira vez
	    
	    windowDetalhes.setShutDownFunction(function () {
	      windowDetalhes.destroy();
	    });             
	                                       
	   windowDetalhes.show();
	   messageBoard.show();	  
	   
	   me.getDetalhe(iCodigoDetalhe);

  }
  
  

};