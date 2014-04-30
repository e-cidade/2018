/**
 * funcao responsavel por exibir cgm's com mais de uma inscrição
 * para que seja selecionado em qual será o vinculo na arreinscr 
 */

function js_exibeCgmVariasInscricoes(iCnpj, iLinha){
	
  if($('windowVariasInscricoes')) {
    windowVariasInscricoes.destroy();
  }	
  
  var iLarguraJanela = 500;//screen.availWidth  - 400;
  var iAlturaJanela  = 400;//screen.availHeight - 250;
  
  windowVariasInscricoes   = new windowAux( 'windowVariasInscricoes',
                                      'Erros e Avisos Encontrados',
                                      iLarguraJanela, 
                                      iAlturaJanela
                                    );
  
  var sConteudoInscricao  = "<div>";
  
      sConteudoInscricao += "  <div id='sTituloWindowInscr'></div> "; 
      
      sConteudoInscricao += "  <div id='sContGridInscricao'></div> "; 
      
      sConteudoInscricao += "<center> ";
      sConteudoInscricao += "  <div id='ctnGerarRelatorio' style='margin-top:10px;'>";
      
      sConteudoInscricao += "    <input type='hidden' id = 'variaInscricaoInscricao'  />";
      sConteudoInscricao += "    <input type='hidden' id = 'variaInscricaoNumCgm'     />";
      sConteudoInscricao += "    <input type='hidden' id = 'variaInscricaoCnpj'       />";
      sConteudoInscricao += "    <input type='hidden' id = 'variaInscricaoLinhaReg'   />";
      
      sConteudoInscricao += "    <input type='button' value='Vincular' onclick='js_criarVinculo ();' />";
      sConteudoInscricao += "    <input type='button' value='Cancelar' onclick='windowVariasInscricoes.destroy();'   />";
      sConteudoInscricao += "  </div> ";
      sConteudoInscricao += "<center> ";
      
      sConteudoInscricao += "</div>";
      
   windowVariasInscricoes.setContent(sConteudoInscricao);
 
   //============  MESSAGE BOARD PARA TITULO da JANELA de ERROS   
  var sTextoMessageBoard  = '<strong>CGM '+iCnpj+', com mais de uma Inscrição</strong> <br> ';
      //sTextoMessageBoard += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registros do tipo <strong>AVISO</strong> podem ser ignorados.';
      messageBoard        = new DBMessageBoard('msgboard2',
                                               'Vincular Cgm à uma Inscrição.',
                                                sTextoMessageBoard,
                                                $('sTituloWindowInscr'));
                                                
   /*
     funcao para corrigir a exibição do window aux, apos fechar a primeira vez
   */
   windowVariasInscricoes.setShutDownFunction(function () {
     windowVariasInscricoes.destroy();
   });             
   
   //windowVariasInscricoes.toFront();
   
   windowVariasInscricoes.setChildOf(windowErroAvisos);
   windowVariasInscricoes.show();
   messageBoard.show();          
   
   js_montaGridInscricao(); 
   
   js_getVariasInscricoes(iCnpj, iLinha);
  
}

/**
 * grid que recebera as inscrições encontradas para o cnpj selecionado
 */
function js_montaGridInscricao() {
  
  oGridInscricao = new DBGrid('Cgm Com Mais de Uma Inscrição.');
  oGridInscricao.nameInstance = 'oGridInscricao';
  oGridInscricao.allowSelectColumns(false);
  
  oGridInscricao.setCellWidth(new Array(  '10px',  
                                          '20px',
                                          '30px',
                                          '80px'
                                        ));
  
  oGridInscricao.setCellAlign(new Array( 'center',
                                         'left',
                                         'left',
                                         'left'
                                        ));
  
  oGridInscricao.setHeader(new Array(  '',
                                       'Inscrição',
                                       'Cgm',
                                       'Nome'
                                      ));                                   
                                        
  oGridInscricao.setHeight(200);
  oGridInscricao.show($('sContGridInscricao'));
  oGridInscricao.clearAll(true);                                      
    
}
/**
 * funcao que posta o cnpj, para verificar quais inscrições existem para ele
 * @param iCnpj
 */
function js_getVariasInscricoes(iCnpj, iLinha) {

  
  var msgDiv         = "Buscando Inscrições \n Aguarde ...";
  var oParametros    = new Object();
  oParametros.exec   = 'getVariasInscricoes';
  oParametros.iCnpj  = iCnpj;
  oParametros.iLinha = iLinha;
  
  js_divCarregando(msgDiv, 'msgBox');
  
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                            {method: "post",
                                             parameters:'json='+Object.toJSON(oParametros),
                                             onComplete: js_variasInscricoes
                                            });  
  
}
/**
 * funcao de retorno, para que seja preenchida a grid, listando as inscrições encontradas para o cnpj
 * @param oAjax
 */
function js_variasInscricoes(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 1) {
    
      oRetorno.aDados.each( 
          function (oDado, iInd) {       
              var aRow    = new Array();
                  
                  aRow[0] = "<input type='radio' name='vincular' onclick='js_setVinculo("+oDado.q02_inscr+", "
                                                                                         +oDado.q02_numcgm+", "
                                                                                         +oRetorno.iCnpj+", "
                                                                                         +oRetorno.iLinha+");'> ";
                  aRow[1] = oDado.q02_inscr;
                  aRow[2] = oDado.q02_numcgm;
                  aRow[3] = oDado.z01_nome.urlDecode();
                  oGridInscricao.addRow(aRow);
             });
      oGridInscricao.renderRows();
      
      
    } else {
      
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
}

/*
 * funcao para definirmos os valores, ao clicar o radiobutton
 */
function js_setVinculo (iInscricao, iCgm, iCnpj, iLinha) {
  
  $('variaInscricaoInscricao').value = iInscricao;
  $('variaInscricaoNumCgm')   .value = iCgm; 
  $('variaInscricaoCnpj')     .value = iCnpj;
  $('variaInscricaoLinhaReg') .value = iLinha;
}

/**
 * funcao que posta o radio button selecionado para criar o vinculo, inscricao x cnpj 
 */
function js_criarVinculo () {
  
  var iInscricao         = $F('variaInscricaoInscricao');
  var iCgm               = $F('variaInscricaoNumCgm');
  var iCnpj              = $F('variaInscricaoCnpj');
  var iLinha             = $F('variaInscricaoLinhaReg');
                         
  var msgDiv             = "Realizando Vínculo \n Aguarde ...";
  var oParametros        = new Object();
  oParametros.exec       = 'vincularInscricao';
  oParametros.iInscricao = iInscricao; 
  oParametros.iCgm       = iCgm; 
  oParametros.iCnpj      = iCnpj;
  oParametros.iLinha     = iLinha;
  
  if (iInscricao == "" || iCgm == "") {
    
    alert("Selecione uma Inscrição para ser Vinculado ao CGM.");
    return false;
  }
  js_divCarregando(msgDiv,'msgBox');
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                            {method: "post",
                                             parameters:'json='+Object.toJSON(oParametros),
                                             onComplete: js_retornoVinculo
                                            });   
  
  
}
/*
 * retorno da funcao js_criavinculo, para verificar se o vinculo foi criado corretamente
 */
function js_retornoVinculo(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.iStatus == 1) {
  
    alert(oRetorno.sMessage.urlDecode());
    
    windowErroAvisos.destroy();
    js_validar();
    
  } else {
    
    alert(oRetorno.sMessage.urlDecode());
    return false;
  }
}



///==========================   CNPJ duplicados



var lPermissao = "false";

/**
 * Verifica os CNPJ duplicados
 */
function js_exibeCnpjDuplicado (iCnpj, iLinha) {
	
  if($('windowCnpjDuplicado')) {
	  windowCnpjDuplicado.destroy();
  }		

  var iLarguraJanela = 500;
  var iAlturaJanela  = 400;

  windowCnpjDuplicado = new windowAux('windowCnpjDuplicado',
                                      'Erros e Avisos Encontrados',
                                      iLarguraJanela,
                                      iAlturaJanela
                                     );
  var sConteudoCnpj   = "<div>";
      sConteudoCnpj  += " <div id='sTituloWindowCnpjDuplicado'></div> "
      sConteudoCnpj  += " <div id='sContGridCnpj'></div>              ";
      sConteudoCnpj  += "<center>";
      sConteudoCnpj  += " <div id='ctnCnpjDuplicado' style='margin-top:10px;' >";

      sConteudoCnpj += "    <input type='hidden' id = 'variosCgm' />   ";
      sConteudoCnpj += "    <input type='button' value='Corrigir' onclick='js_corrigirCgm();' />";
      sConteudoCnpj += "    <input type='button' value='Cancelar' onclick='windowCnpjDuplicado.destroy();'   />";
      sConteudoCnpj += "  </div> ";
      sConteudoCnpj  += "</center>";
      sConteudoCnpj
      sConteudoCnpj += "</div>";
      
   windowCnpjDuplicado.setContent(sConteudoCnpj);
 
   //============  MESSAGE BOARD PARA TITULO da JANELA de ERROS   
  var sTextoCnpjMessageBoard  = 'CGMs vinculados ao mesmo CNPJ<br> ';
      messageBoard        = new DBMessageBoard('msgboard3',
                                               'Corrigir duplicidade.',
                                                sTextoCnpjMessageBoard,
                                                $('sTituloWindowCnpjDuplicado'));


      /**
       * funcao para corrigir a exibição do window aux, apos fechar a primeira vez
       **/
      windowCnpjDuplicado.setShutDownFunction(function () {
    	  windowCnpjDuplicado.destroy();
      });             

      windowCnpjDuplicado.setChildOf(windowErroAvisos);
      windowCnpjDuplicado.show();
      messageBoard.show();          

      js_montaGridCnpj(); 

      js_getVariosCnpj(iCnpj, iLinha);                                                   
                                                
}


/**
 * grid que recebera as inscrições encontradas para o cnpj selecionado
 */
function js_montaGridCnpj() {
  
  oGridCnpj = new DBGrid('CNPJs com vários CGMs.');
  oGridCnpj.nameInstance = 'oGridCnpj';
  oGridCnpj.allowSelectColumns(false);
  
  oGridCnpj.setCellWidth(new Array('10px',  
                                    '30px',
                                    '30px',
                                    '80px'
                                   ));
  
  oGridCnpj.setCellAlign(new Array('center',
                                   'left',
                                   'left',
                                   'left'
                                   ));
  
  oGridCnpj.setHeader(new Array('',
                                'CGC/CPF',
                                'Cgm',
                                'Nome'
                                ));                                   
                                        
  oGridCnpj.setHeight(200);
  oGridCnpj.show($('sContGridCnpj'));
  oGridCnpj.clearAll(true);                                      
    
}
/**
 * funcao que posta o cnpj, para verificar quais inscrições existem para ele
 * @param iCnpj
 */
function js_getVariosCnpj(iCnpj, iLinha) {

  var msgDiv         = "Buscando CGMs \n Aguarde ...";
  var oParametros    = new Object();
  oParametros.exec   = 'getVariosCgm';
  oParametros.iCnpj  = iCnpj;
  oParametros.iLinha = iLinha;
  
  js_divCarregando(msgDiv, 'msgBox');
  
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                            {method: "post",
                                             parameters:'json='+Object.toJSON(oParametros),
                                             onComplete: js_variosCnpj
                                            });  
  
}
/**
 * funcao de retorno, para que seja preenchida a grid, listando os CNPJs encontrados para o mesmo CGM
 * @param oAjax
 */
function js_variosCnpj(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 1) {
    
      oRetorno.aDados.each( 
          function (oDado, iInd) {       
              var aRow    = new Array();
                  
                  aRow[0] = "<input type='radio' name='cgmDuplo' onclick='js_setCgm("+oDado.z01_numcgm+");'> ";
                  aRow[1] = oDado.z01_cgccpf;
                  aRow[2] = oDado.z01_numcgm;
                  aRow[3] = oDado.z01_nome.urlDecode();
                  oGridCnpj.addRow(aRow);
             });
      oGridCnpj.renderRows();
      
      if (oRetorno.lPermissao == 'true') {
    	  
    	  lPermissao = 'true';
      }
      
      
    } else {
      
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
}

function js_setCgm (iCgm) {	  
  $('variosCgm').value = iCgm;
}

function js_corrigirCgm() {

  var iCgm = $('variosCgm').value;
  
  if (lPermissao == 'false') {
	  
	  alert('Usuario sem Permissão para Alterações do CGM');
	  return false;
  }
  if (iCgm == '') {
	
    alert('Selecione um Cgm para Alteração.');	  
	return false;  
  }  
  
  js_OpenJanelaIframe(
                  	  'top.corpo',
                      'db_iframe_cgc',
                      'prot1_cadcgm002.php?chavepesquisa='+ iCgm + '&testanome=1&inconsistenciaSimples=1',
                      'Corrigir CGM',
                      true
                    );
 
  IFdb_iframe_cgc.onfocus = function() {
    
    $('windowErroAvisos').style.zIndex = '1';
    $('IFdb_iframe_cgc').style.zIndex = '500000000';
  };
  
  setTimeout(function(){
    $('IFdb_iframe_cgc').focus(); 
    $('windowErroAvisos').style.zIndex = '1';
    $('IFdb_iframe_cgc').style.zIndex = '500000000';
  },200);
  
  

}


//================= GERA COMPLEMENTAR



/**
 * Função para mostra windowAux com a opção de geração de complementar para a opção escolhida
 * @param sCnpj           {string}  - String com o CNPJ do Contribuinte
 * @param iCodigoRegistro {integer} - q23_sequencial
 */
function js_geraComplementar(sCnpj, iCodigoRegistro)  {

//  if ( typeof(oWindowAuxComplementar) == "object" ) {
//    oWindowAuxComplementar.destroy();
//    delete(oWindowAuxComplementar);
//  }
  /**
   * Define contudo do windowAux
   */
  var sConteudoComplementar  = " <div>                                                                                          ";
  sConteudoComplementar     += "   <div id='tituloWindowComplementar'></div>                                                    ";
  sConteudoComplementar     += "   <div id='ctnOpcoesComplementar'>                                                             ";
  sConteudoComplementar     += "     <center>                                                                                   ";
  sConteudoComplementar     += "       <fieldset style='margin: 5px;'>                                                          ";
  sConteudoComplementar     += "         <legend><b>Correção da inconsistência</b></legend>                                     ";
  sConteudoComplementar     += "           <BR/>                                                                                ";
  sConteudoComplementar     += "           <input type='checkbox' id='opcaoComplementar' onChange=\"js_validaComplementar()\">  ";
  sConteudoComplementar     += "           <label for='opcaoComplementar'>                                                      ";
  sConteudoComplementar     += "             Gerar ISSQN Complementar para o CNPJ <B>" + sCnpj + "</B>                          ";
  sConteudoComplementar     += "           </label><BR/><BR/>                                                                   ";
  sConteudoComplementar     += "       </fieldset>                                                                              ";
  sConteudoComplementar     += "       <input type='button' id='salvarComplementar'   value='Salvar'   onclick='js_salvarDadosISSQNComplementar("+iCodigoRegistro+")' disabled />     ";
  sConteudoComplementar     += "       <input type='button' id='cancelarComplementar' value='Cancelar' onclick='oWindowAuxComplementar.destroy();' />        ";
  sConteudoComplementar     += "     </center>                                                                                  ";
  sConteudoComplementar     += "   </div>                                                                                       ";
  sConteudoComplementar     += " </div>                                                                                         ";

  oWindowAuxComplementar     = new windowAux("oWindowAuxComplementar", "Geração de ISSQN Complementar", 600, 400);
  oWindowAuxComplementar.setContent(sConteudoComplementar);
  oWindowAuxComplementar.setChildOf(windowErroAvisos);
  oWindowAuxComplementar.show(0,0);

  var sTextoMessageBoard     = 'Registros do tipo <strong>ERRO</strong>, devem ser corrigidos. <br> ';
  oMessageBoardComplementar  = new DBMessageBoard('messageBoardComplementar',
                                                  'Geração de ISSQN Complementar.',
                                                   sTextoMessageBoard,
                                                   $('tituloWindowComplementar'));
                                              
  /**
   * funcao para corrigir a exibição do window aux, apos fechar a primeira vez
   */
  oWindowAuxComplementar.setShutDownFunction(
    function () {
      oWindowAuxComplementar.destroy();
      
    }
  );             
  
  oWindowAuxComplementar.show();                          
  oMessageBoardComplementar.show();        

}




/**
 * Valida botão salvar da janela de geração de issqn complementar
 */
function js_validaComplementar() {
  
  if ( $('opcaoComplementar').checked ) { 
    $('salvarComplementar').disabled = false;
  } else {
    $('salvarComplementar').disabled = true;
  }
}

/**
 * 
 */
function js_salvarDadosISSQNComplementar(iRegistroBase) {
  
  js_divCarregando("Salvando dados do ISSQN Complementar...",'msgSalvaDadosComplementar');
  var oParametros = {
    exec      : "salvaComplementar",
    iRegistro : iRegistroBase,
    iOpcao    : $('opcaoComplementar').checked ? '1' : '0'
  };
  var oAjaxLista  = new Ajax.Request(sUrlRPC,{
                                    method    : "post",
                                        parameters:'json='+Object.toJSON(oParametros),
                                        onComplete: function(oAjax) {
                                          
                                          js_removeObj('msgSalvaDadosComplementar');
                                          
                                          var oRetorno = eval("("+oAjax.responseText+")");
                                          
                                          if (oRetorno.iStatus == 1) {
                                        	  
                                            alert("Salvo com sucesso.");
                                            
                                            windowErroAvisos.destroy();
                                            js_validar();
                                            
                                          } else {
                                            alert(oRetorno.sMessage.urlDecode());
                                          }
                                        }
  });  
}

function js_pesquisaCgm(){
  
  
  js_OpenJanelaIframe('','db_iframe_cgc','func_nome.php?testanome=1&funcao_js=parent.js_editaCgm|z01_numcgm','Pesquisa',true);
  
  
  /*  
  js_OpenJanelaIframe('',"db_iframe_cgc","func_nome.php?funcao_js=js_editaCgm(z01_numcgm)&testanome=1&inconsistenciaSimples=1","Pesquisa",true); 

   */
  IFdb_iframe_cgc.onfocus = function() {
    
    $('windowErroAvisos').style.zIndex = '1';
    // $('windowCnpjDuplicado').style.zIndex = '2';
    $('IFdb_iframe_cgc').style.zIndex = '500000000';
  };
  
  setTimeout(function(){
    $('IFdb_iframe_cgc').focus(); 
    $('windowErroAvisos').style.zIndex = '1';
    $('IFdb_iframe_cgc').style.zIndex = '500000000';
  },200);
  
}


function js_editaCgm(iCgm){
  
  
  js_OpenJanelaIframe(
      'top.corpo',
      'db_iframe_cgc',
      'prot1_cadcgm002.php?chavepesquisa='+ iCgm + '&testanome=1&inconsistenciaSimples=1',
      'Corrigir CGM',
      true
    );  
}



/*
IFdb_iframe_cgc.onfocus = function() {
  
  $('windowErroAvisos').style.zIndex = '1';
  $('windowCnpjDuplicado').style.zIndex = '2';
  $('IFdb_iframe_cgc').style.zIndex = '500000000';
};

setTimeout(function(){
$('IFdb_iframe_cgc').focus(); 
},100);
*/