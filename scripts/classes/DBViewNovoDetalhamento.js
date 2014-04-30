/**
 * view para inclusão de detalhamento
 * 
 */
var DBViewNovoDetalhamento = function(sInstance) {

  var me             = this;
  var sUrlRPC        = "con4_contacorrente.RPC.php";
  var sInstanceName  = sInstance;
  this.oElementos    = new Object();
  
  this.mostraCampos = function(iCodigoContaCorrente){
	  
	$('ctnTipoReceita')    . style.display = 'none';
	$('ctnConcarPeculiar') . style.display = 'none';
	$('ctnContaBancaria')  . style.display = 'none';
	$('ctnEmpenho')        . style.display = 'none';
	$('ctnNome')           . style.display = 'none';
	$('ctnOrgao')          . style.display = 'none';
	$('ctnUnidade')        . style.display = 'none';
	$('ctnAcordo')         . style.display = 'none';
	
    switch (iCodigoContaCorrente) {
    
	    case '1':
			
	    	/*
	    	 *  c19_contacorrente       
				c19_orctiporec          
				c19_instit              
				c19_concarpeculiar      
				c19_reduz               
				c19_conplanoreduzanousu
	    	 */
	    	$('ctnTipoReceita')    . style.display = 'table-row';
	    	$('ctnConcarPeculiar') . style.display = 'table-row';
		break;
		
		
	    case '2':
			
	    	/*
	    	 *  c19_contacorrente       
				c19_instit              
				c19_conplanoreduzanousu 
				c19_reduz               
				c19_contabancaria 	 
	    	 */
	    	$('ctnContaBancaria')  . style.display = 'table-row';
	    break;
	    	
	    	
	    case '3':
			
	    	/*
	    	 *  c19_contacorrente       
				c19_instit              
				c19_numcgm              
				c19_conplanoreduzanousu 
				c19_reduz 	
	    	 */
	    	$('ctnNome')           . style.display = 'table-row';
	    break;
	    
	    
	    case '19':
			
	    	/*
	    	 *	c19_contacorrente       
				c19_conplanoreduzanousu 
				c19_instit              
				c19_numcgm              
				c19_numemp              
				c19_orcunidadeanousu    
				c19_orcunidadeorgao     
				c19_orcunidadeunidade   
				c19_orcorgaoanousu      
				c19_orcorgaoorgao       
				c19_reduz  
	    	 */
	    	$('ctnEmpenho')        . style.display = 'table-row';
	    	$('ctnNome')           . style.display = 'table-row';
	    	$('ctnOrgao')          . style.display = 'table-row';
	    	$('ctnUnidade')        . style.display = 'table-row';
	    break;
	    	
	    
	    case '25':
			
	    	/*
	    	 *  c19_contacorrente       
				c19_instit              
				c19_reduz               
				c19_conplanoreduzanousu 
				c19_acordo              
				c19_numcgm    
	    	 */
	    	$('ctnNome')           . style.display = 'table-row';
	    	$('ctnAcordo')         . style.display = 'table-row';
	    break;	

    }  
	  
  }

  this.show = function (iCodigoContaCorrente, iReduzido) {
	  
	  var iLarguraJanela = 750;
	  var iAlturaJanela  = 400;	  
	  
	  
	  windowNovoDetalhes   = new windowAux( 'windowNovoDetalhes',
	                                        'Cadastro de Detalhamento',
	                                        iLarguraJanela, 
	                                        iAlturaJanela
	                                       );
	  
	  var sConteudoNovo  = "<div>";
	      sConteudoNovo += "<div id='sTituloWindowDetalhe'></div> "; // container do message box

		sConteudoNovo += "  <center>  ";
		
		sConteudoNovo += "  <fieldset style='width: 700px;margin-top:10px;'><legend><strong> Cadastro de Novo Detalhamento para Conta Corrente</strong> </legend>";
        sConteudoNovo += "    <table style='width: 100%; margin-top:10px;' border = 0 >  ";
       
        sConteudoNovo += "      <tr nowrap id='ctnTipoReceita'>     ";
        sConteudoNovo += "        <td id='ancoraTipoReceita'>   ";
        sConteudoNovo += "          <a class='dbancora'  onclick='js_pesquisaTipoReceita(true);' style='text-decoration:underline;' href='#'>Tipo de Receita:</a> ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputTipoReceita'>   ";
        sConteudoNovo += "          <input type='text' id='iTipoReceita' class='iNovoDetalhe'onChange='js_pesquisaTipoReceita(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "          <input type='text' readonly='readonly' id='sTipoReceita' class='sNovoDetalhe' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    "; 
        
        sConteudoNovo += "      <tr nowrap id='ctnConcarPeculiar'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "         <a class='dbancora'  onclick='js_pesquisaCaracteristicaPeculiar(true);' style='text-decoration:underline;' href='#'>Característica Peculiar :</a> ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputConcarPeculiar'>   ";
        sConteudoNovo += "          <input type='text' id='iConcarPeculiar' class='iNovoDetalhe' onChange='js_pesquisaCaracteristicaPeculiar(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);'/>  ";
        sConteudoNovo += "          <input type='text' id='sConcarPeculiar' class='sNovoDetalhe' readonly='readonly' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    "; 

        sConteudoNovo += "      <tr nowrap id='ctnContaBancaria'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "         <a class='dbancora'  onclick='js_pesquisaContaBancaria(true);' style='text-decoration:underline;' href='#'>Conta Bancária: </a>  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputContaBancaria'>   ";
        sConteudoNovo += "          <input type='text' id='iContaBancaria' class='iNovoDetalhe' onChange='js_pesquisaContaBancaria(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "          <input type='text' id='sContaBancaria' class='sNovoDetalhe' readonly='readonly' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    "; 

        sConteudoNovo += "      <tr nowrap id='ctnEmpenho'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "         <a class='dbancora'  onclick='js_pesquisaEmpenho(true);' style='text-decoration:underline;' href='#'>Empenho: </a>  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputEmpenho'>   ";
        sConteudoNovo += "          <input type='text' id='iEmpenho' class='iNovoDetalhe' onChange='js_pesquisaEmpenho(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "          <input type='text' id='sEmpenho' class='sNovoDetalhe' readonly='readonly' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    "; 
        
        sConteudoNovo += "      <tr nowrap id='ctnNome'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "         <a class='dbancora'  onclick='js_pesquisaNome(true);' style='text-decoration:underline;' href='#'>Nome: </a> ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputNome'>   ";
        sConteudoNovo += "          <input type='text' id='iNome' class='iNovoDetalhe' onChange='js_pesquisaNome(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "          <input type='text' id='sNome' class='sNovoDetalhe'  readonly='readonly'/>  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    ";
        
        sConteudoNovo += "      <tr nowrap id='ctnOrgao'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "          <a class='dbancora'  onclick='js_pesquisaOrgao(true);' style='text-decoration:underline;' href='#'>Orgão: </a>   ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputOrgao'>   ";
        sConteudoNovo += "          <input type='text' id='iOrgao' class='iNovoDetalhe'onChange='js_pesquisaOrgao(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "          <input type='text' id='sOrgao' class='sNovoDetalhe' readonly='readonly' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    ";         
        
        sConteudoNovo += "      <tr nowrap id='ctnUnidade'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "         <a class='dbancora'  onclick='js_pesquisaUnidade(true);' style='text-decoration:underline;' href='#'>Unidade: </a>  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputUnidade'>   ";
        sConteudoNovo += "          <input type='text' id='iUnidade' class='iNovoDetalhe' onChange='js_pesquisaUnidade(false);' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "          <input type='text' id='sUnidade' class='sNovoDetalhe' readonly='readonly' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    "; 
        
        sConteudoNovo += "      <tr nowrap id='ctnAcordo'>     ";
        sConteudoNovo += "        <td>   ";
        sConteudoNovo += "         <a class='dbancora'  onclick='js_pesquisaAcordo(true);' style='text-decoration:underline;' href='#'>Acordo: </a>  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "        <td id='inputAcordo'>   ";
        sConteudoNovo += "          <input type='text' id='iAcordo' onChange='js_pesquisaAcordo(false);'class='iNovoDetalhe' onKeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' />  ";
        sConteudoNovo += "        </td>  ";
        sConteudoNovo += "      </tr>    "; 
        
		sConteudoNovo += "    </table>";
		sConteudoNovo += "    </fieldset>";
		sConteudoNovo += "    <input style='margin-top:10px;' type='button' value='Incluir' onClick='js_incluirDetalhamento();' id='incluirDetalhe' />";
	    sConteudoNovo += "  </center> ";
	      
	    sConteudoNovo += "</div>";
	      
	   windowNovoDetalhes.setContent(sConteudoNovo);
	 
	   //============  MESAGE BORD PARA TITULO da JANELA 
	  var sTextoMessageBoard  = 'Cadastro de novo detalhamento. <br> ';
	      messageBoardNovo        = new DBMessageBoard('msgboard1',
	                                               'Novo detalhamento da conta corrente.',
	                                                sTextoMessageBoard,
	                                                $('sTituloWindowDetalhe'));
	      
	    //funcao para corrigir a exibição do window aux, apos fechar a primeira vez
	    
	    windowNovoDetalhes.setShutDownFunction(function () {
	      windowNovoDetalhes.destroy();
	      $('novo').disabled = false;
	    });             
	                                       
	   windowNovoDetalhes.show();
	   messageBoardNovo.show();	 
	   
	   me.mostraCampos(iCodigoContaCorrente);

  }

  
};



