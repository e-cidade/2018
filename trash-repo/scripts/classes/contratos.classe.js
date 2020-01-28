contrato = function () {

  me = this;
	this.verificaLicitacoes = function () {

	 var sFuncao = '';
	 if ($F('ac16_origem') == 2) {
	   sFuncao = 'getLicitacoesContratado';
	 } else if ($F('ac16_origem') == 1) {
	   sFuncao = 'getProcessosContratado';
	 } else {
	   return true;
	 }

   var oParam         = new Object();
   oParam.exec        = sFuncao;
	 oParam.iContratado = $F('ac16_contratado');
	 oParam.iContrato   = $F('ac16_sequencial');

	 js_divCarregando("Aguarde, carregando as licita��es...", "msgBox");
	 var oAjax   = new Ajax.Request(
	                           sURL,
	                           {
	                            method    : 'post',
	                            parameters: 'json='+Object.toJSON(oParam),
	                            onComplete: me.mostraLicitacoesContratado
	                            }
	                          );
	}

	this.mostraLicitacoesContratado = function(oResponse)  {

	  js_removeObj("msgBox");
	   var oRetorno = eval("("+oResponse.responseText+")");
	   var sTitulo = '';
	   if ($F('ac16_origem') == 1) {
	     sTitulo = "Processo de compras";
	   } else {
	     sTitulo = "Licita��es";
	   }
	   var iLarguraJanela = document.body.getWidth();
	   var iAlturaJanela  = document.body.clientHeight / 1.5;

	   oJanela       = new windowAux('wndLicitacoesVencidas', sTitulo,
                                   iLarguraJanela,
                                   iAlturaJanela);
	   var sContent  = '  <fieldset style="width: 97%"><legend><b>'+sTitulo+'</b></legend>';
	   sContent     += '    <div id="cntDados"></div>' ;
	   sContent     += '  </fieldset>';
	   sContent     += '  <center> ';
	   sContent     += '   <input type="button" value="Confirmar" id="btnConfirmarObjetos">';
	   sContent     += '  </center> ';

	   oJanela.setContent(sContent);
	   oMessageBoard = new DBMessageBoard('messageboardlicitacao',
	                                      sTitulo +" vencidas por "+$F('nomecontratado'),
	                                      'Escolha as licita��es que far�o parte do contrato',
	                                      $('windowwndLicitacoesVencidas_content')
	                                     );
	   oJanela.setShutDownFunction(function() {
	     oJanela.destroy();
	   });

	   /**
	    * Define o callback para o botao confirmar
	    */
	    $('btnConfirmarObjetos').observe("click", function (){
	       me.confirmaSelecao();
	    });
	   /*
	    * Montamos a grid com os dados
	    */
	   oGridDados              = new DBGrid('gridDados');
	   oGridDados.nameInstance = 'oGridDados';
	   oGridDados.setCheckbox(0);
	   oGridDados.setHeight(300);
	   oGridDados.selectAll = function(idObjeto, sClasse, sLinha) {}; //reeinscrita a funcao selecionar todos, pois somente um sera permitido

	   /**
	    * reescrita fun��o selectSingle para permitir somente um item selecionados
	    */
	   oGridDados.selectSingle = function (oCheckbox,sRow,oRow) {

	     itens = document.getElementsByClassName("checkboxgridDados");

	     for (var i = 0;i < itens.length;i++){

	       itens[i].checked = false;
	       $('gridDadosrowgridDados'+i).className = 'normal';
	       oGridDados.aRows[i].isSelected = false;
	     }

	     $(sRow).className = 'marcado';
	     $(oCheckbox.id).checked = true;
	     oRow.isSelected   = true;


	     return true;
	   };

	   oGridDados.setCellWidth(new Array("5%", "5%", "60%", "10%", "10%", "10%"));
	   oGridDados.setCellAlign(new Array("right", "right", "left", "right", "right", "right"));
	   oGridDados.setHeader(new Array("C�digo","N�mero", "Objeto", "N�mero do Exerc�cio", "CGM", "Data da Inclus�o"));
	   oGridDados.show($('cntDados'));


	   oJanela.show(1,0);
	   js_divCarregando("Aguarde, carregando itens...", "msgBox");
	   me.preencheDadosItens(oRetorno);
	};

	this.preencheDadosItens = function(oDados) {

	  js_removeObj("msgBox");
	  oGridDados.clearAll(true);
	  if (oDados.itens.length > 0) {

    for (var i = 0; i < oDados.itens.length; i++) {

	     with (oDados.itens[0]) {

	       var aLinha = new Array();
	       aLinha[0]  = oDados.itens[i].licitacao;
	       aLinha[1]  = oDados.itens[i].numero;
	       aLinha[2]  = oDados.itens[i].objeto.urlDecode();
	       aLinha[3]  = oDados.itens[i].numero_exercicio;
	       aLinha[4]  = oDados.itens[i].cgm;
	       aLinha[5]  = js_formatar(oDados.itens[i].data, 'd');

	       var lMarcado = false;

	       if (js_search_in_array(oDados.itensSelecionados, oDados.itens[i].licitacao)) {
	        lMarcado = true;
	       }
	       oGridDados.addRow(aLinha, false, false, lMarcado);
	     }
	   }

	   oGridDados.renderRows();
	   oGridDados.setStatus("");
	  } else {

	    oGridDados.setStatus("N�o foram Encontrados registros");
	  }
	};



  this.confirmaSelecao = function() {

	  var aItensSelecionados = oGridDados.getSelection("object");

	  if (aItensSelecionados.length == 0) {

	    alert('N�o foram selecionados nenhum item.');
	    return false;
	  } else {

      if (aItensSelecionados.length > 1) {

        alert("Permitida Sele��o de Somente um Julgamento!!");
        return false;
      }


	  }
//cntDados


	  var oParam     = new Object();
	  oParam.exec    = "setDadosSelecao";
	  oParam.itens   = new Array();
	  var sDescricao = new String();
	  aItensSelecionados.each(function(oLinha, id) {

      if (oLinha.aCells[3].getContent() != "&nbsp;") {
	      sDescricao += oLinha.aCells[3].getContent().trim()+"\n"
	    }
     oParam.itens.push(oLinha.aCells[1].getValue());

	  });
	  $('ac16_objeto').value       = sDescricao;
	  $('ac16_resumoobjeto').value = sDescricao.substring(0, 50).toUpperCase();
	  var oAjax   = new Ajax.Request(
	                         sURL,
	                         {
	                          method    : 'post',
	                          parameters: 'json='+Object.toJSON(oParam),
	                          onComplete: me.retornoSetDadosSelecao
	                          }
	                        );
	}

	this.retornoSetDadosSelecao = function(oAjax) {

	  var oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == 1) {

	     oJanela.destroy();
	     $('ac16_deptoresponsavel').focus();

	  } else {
	    alert(oRetorno.message.urlDecode());
	  }
	}

	this.getNumeroAcordo  = function() {

	  var oParam         = new Object();
	  oParam.exec        = 'getNumeroContrato';
	  oParam.iGrupo      = $F('ac16_acordogrupo');
	  $('ac16_numero').disabled = true;
	  var oAjax   = new Ajax.Request(
	                         sURL,
	                         {
	                          method    : 'post',
	                          parameters: 'json='+Object.toJSON(oParam),
	                          onComplete: me.retornoNumeroContrato
	                          }
	                        );
	}

  this.retornoNumeroContrato = function (oAjax) {

	  $('ac16_numero').disabled = false;
	  var oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == 1) {

	    $('ac16_numero').value = oRetorno.numero;
	  } else {

	    alert(oRetorno.message.urlDecode());
	    $('ac16_acordogrupo').value = '';
	    $('ac02_descricao').value   = '';
	    return false;
	  }
	}
	/**
	 *Salva os dados do contrato
	 */
	this.saveContrato = function () {

	  var iGrupoContrato            = $F('ac16_acordogrupo');
	  var iNumero                   = $F('ac16_numero');
	  var iOrigem                   = $F('ac16_origem');
	  var iContratado               = $F('ac16_contratado');
	  var iDepartamentoResponsavel  = $F('ac16_deptoresponsavel');
	  var iComissao                 = $F('ac16_acordocomissao');
	  var dtInicio                  = $F('ac16_datainicio');
	  var dtTermino                 = $F('ac16_datafim');
	  var sLei                      = $F('ac16_lei');
	  var dtAssinatura              = $F('ac16_dataassinatura');
	  var sObjeto                   = $F('ac16_objeto');
	  var sResumoObjeto             = $F('ac16_resumoobjeto');
	  var iQtdRenovacao             = $F('ac16_qtdrenovacao');
	  var iUnidRenovacao            = $F('ac16_tipounidtempo');
	  var sProcesso                 = encodeURIComponent($F('ac16_numeroprocesso'));
	  var lEmergencial              = $F('ac26_emergencial')=='f'?false:true;
	  var lPeriodoComercial         = $F('ac16_periodocomercial');	  
	  var iCategoriaAcordo          = $F('ac50_sequencial');
	  var iTipoUnidadeTempoVigencia = $F('ac16_tipounidtempoperiodo');
	  var iQtdPeriodoVigencia       = $F('ac16_qtdperiodo');

	   if (iOrigem == "0") {

      alert('Informe a origem do acordo.');
      $('ac16_origem').focus();
      return false;
    }
	  if (iGrupoContrato == "") {

	    alert('Informe o grupo do acordo.');
	    $('ac16_acordogrupo').focus();
	    return false;
	  }
	  if (iNumero == "") {

	    alert('Informe o n�mero do acordo.');
	    $('ac16_numero').focus();
	    return false;
	  }

	  if (iContratado == "") {

	    alert('Informe o contratado do acordo.');
	    $('ac16_contratado').focus();
	    return false;
	  }
	  if (iDepartamentoResponsavel == "") {

	    alert('Informe o Departamento Respons�vel.');
	    $('ac16_deptoresponsavel').focus();
	    return false;
	  }
	  if (iComissao == "") {

	    alert('Informe a comiss�o de vistoria do acordo.');
	    $('ac16_acordocomiss�o').focus();
	    return false;
	  }

	  if (sLei == "") {

	    alert('Informe a n�mero da lei.');
	    $('ac16_lei').focus();
	    return false;

	  }
	  if (dtInicio == "") {

	    alert('Informe a data de in�cio do Contrato.');
	    $('ac16_datainicio').focus();
	    return false;
	  }

	  if (dtTermino == "") {

	    alert('Informe a data de termino do Contrato.');
	    $('ac16_datafim').focus();
	    return false;
	  }

	  if (js_comparadata(dtTermino, dtInicio, "<")) {

	    alert('Data do termino do contrato deve ser maior que a data de inicio do contrato.');
	    $('ac16_datafim').focus();
	    return false;
	  }
	  
	  if (iCategoriaAcordo == "" || iCategoriaAcordo == 0) {

      alert('Informe a categoria do Contrato.');
      $('ac50_sequencial').focus();
      return false;
    }
	  

	  if (sObjeto == "") {

	    alert('Informe o objeto do Contrato.');
	    $('ac16_objeto').focus();
	    return false;
	  }

	  if (sResumoObjeto == "") {

	    alert('Informe o resumo objeto do Contrato.');
	    $('ac16_resumoobjeto').focus();
	    return false;
	  }

	  var oParam      = new Object();
	  oParam.exec     = "salvarContrato";
	  oParam.contrato = new Object();

	  oParam.contrato.iOrigem                   = iOrigem;
	  oParam.contrato.iGrupo                    = iGrupoContrato;
	  oParam.contrato.iNumero                   = iNumero;
	  oParam.contrato.iCodigo                   = $F('ac16_sequencial');
	  oParam.contrato.iContratado               = iContratado;
	  oParam.contrato.iDepartamentoResponsavel  = iDepartamentoResponsavel;
	  oParam.contrato.iComissao                 = iComissao;
	  oParam.contrato.sLei                      = sLei;
	  oParam.contrato.dtInicio                  = dtInicio;
	  oParam.contrato.dtTermino                 = dtTermino;
	  oParam.contrato.dtAssinatura              = dtAssinatura;
	  oParam.contrato.sObjeto                   = encodeURIComponent(tagString(sObjeto));
	  oParam.contrato.sResumoObjeto             = encodeURIComponent(tagString(sResumoObjeto));
	  oParam.contrato.iQtdRenovacao             = iQtdRenovacao;
    oParam.contrato.iUnidRenovacao            = iUnidRenovacao;
    oParam.contrato.lEmergencial              = lEmergencial;
    oParam.contrato.sProcesso                 = sProcesso;
    oParam.contrato.lPeriodoComercial         = lPeriodoComercial;    
    oParam.contrato.iCategoriaAcordo          = iCategoriaAcordo;
    oParam.contrato.iTipoUnidadeTempoVigencia = iTipoUnidadeTempoVigencia;
    oParam.contrato.iQtdPeriodoVigencia       = iQtdPeriodoVigencia;
    
	  js_divCarregando('Aguarde, salvando dados do contrato','msgbox');
	  //$('db_opcao').disabled = true;
	  var oAjax   = new Ajax.Request(
	                         sURL,
	                         {
	                          method    : 'post',
	                          parameters: 'json='+Object.toJSON(oParam),
	                          onComplete: me.retornoSaveContrato
	                          }
	                        );
	}

  this.bloqueiaCampos = function() {

    $('ac16_acordogrupo').disabled             = true;
    $('ac16_numero').disabled                  = true;
    $('ac16_origem').disabled                  = true;
    $('ac16_contratado').disabled              = true;
    $("ac16_periodocomercial").disabled        = true;
    $("ac16_datainicio").disabled              = true;
    $("ac16_datafim").disabled                 = true;
    $("ac16_deptoresponsavel").disabled        = true;
    $("ac16_acordocomissao").disabled          = true;
    $("ac16_datainicio").style.backgroundColor = "#DEB887";
    $("ac16_datafim").style.backgroundColor    = "#DEB887";



    //Desabilita o botao "D" do calendario
    //getByName do prototype
    var btDataInicio = $$('[name="dtjs_ac16_datainicio"]')[0];
    var btDataFim    = $$('[name="dtjs_ac16_datafim"]')[0];

    if (btDataInicio) {
      btDataInicio.disabled = true;
    }

    if (btDataFim) {
      btDataFim.disabled = true;
    }

    //Bloqueando ancoras
    var aAncoras = document.getElementsByClassName("dbancora");

    for (var iAncora = 0; iAncora < aAncoras.length; iAncora++) {
    
      //iAncora 4 � referente a �ncora Categoria.
      if (iAncora != 4) {
        
        aAncoras[iAncora].setAttribute("onclick", "");
        aAncoras[iAncora].setAttribute("style", "text-decoration: none; color: #000;");
      }
    }

  }

	this.retornoSaveContrato = function (oAjax) {

	  $('db_opcao').disabled = false;
	  js_removeObj('msgbox');

	  var oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == 1) {

      $('ac16_sequencial').value = oRetorno.iCodigoContrato;
      if ($F('ac16_origem') == 6 || $F('ac16_origem') == '6' ) {
    	  
    	  $('pesquisarEmpenhos').style.display = 'inLine';
      }
      
      me.bloqueiaCampos();

	  //parent.mo_camada('acordoitem');
	  parent.document.formaba.acordoitem.disabled       = false;
	  top.corpo.iframe_acordoitem.location.href         = 'aco1_acordoitem001.php?ac20_acordo='+oRetorno.iCodigoContrato;
	  parent.document.formaba.acordogarantia.disabled   = false;
      top.corpo.iframe_acordogarantia.location.href     = 'aco1_acordoacordogarantia001.php?ac12_acordo='+oRetorno.iCodigoContrato;
      parent.document.formaba.acordopenalidade.disabled = false;
      top.corpo.iframe_acordopenalidade.location.href   = 'aco1_acordoacordopenalidade001.php?ac13_acordo='+oRetorno.iCodigoContrato;
	  parent.document.formaba.acordodocumento.disabled  = false;
      top.corpo.iframe_acordodocumento.location.href    = 'aco1_acordodocumento001.php?ac40_acordo='+oRetorno.iCodigoContrato;
	    alert("Acordo Salvo com Sucesso.");
	  } else {

	    alert(oRetorno.message.urlDecode());
	    return false;
	  }
	}

	this.getContrato = function(iContrato) {

	  var oParam         = new Object();
      oParam.exec        = 'getDadosAcordo';
      oParam.iContrato   = iContrato;
      js_divCarregando('Aguarde, pesquisando dados do contrato', 'msgBox');
      var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: me.retornoGetContrato
                              }
                            );

	}

	this.retornoGetContrato = function(oAjax) {

	  $('db_opcao').disabled = false;
    js_removeObj('msgBox');
	  var oRetorno  = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == 1) {

      me.bloqueiaCampos();
		  $('db_opcao').value                   = 'Alterar';
	    $('db_opcao').name                    = 'alterar';
	    $('ac16_sequencial').value            = oRetorno.contrato.iSequencial;
	    $('ac16_origem').value                = oRetorno.contrato.iOrigem;
	    if (oRetorno.contrato.iOrigem == 6 || oRetorno.contrato.iOrigem == '6') {
	    	
	    	$('pesquisarEmpenhos').style.display = 'inLine';
	    }
	    $('ac16_acordogrupo').value           = oRetorno.contrato.iGrupo;
	    $('ac16_numero').value                = oRetorno.contrato.iNumero;
	    $('ac16_contratado').value            = oRetorno.contrato.iContratado;
	    $('nomecontratado').value             = oRetorno.contrato.sNomeContratado.urlDecode();
	    $('ac16_deptoresponsavel').value      = oRetorno.contrato.iDepartamentoResponsavel;
      $('descrdepto').value                 = oRetorno.contrato.sNomeDepartamentoResponsavel.urlDecode();
      $('ac16_acordocomissao').value        = oRetorno.contrato.iComissao;
      $('ac08_descricao').value             = oRetorno.contrato.sNomeComissao.urlDecode();
      $('ac16_lei').value                   = oRetorno.contrato.sLei.urlDecode();
      $('ac16_datainicio').value            = oRetorno.contrato.dtInicio;
      $('ac16_datafim').value               = oRetorno.contrato.dtTermino;
      $('ac16_dataassinatura').value        = oRetorno.contrato.dtAssinatura;
      $('ac16_objeto').value                = oRetorno.contrato.sObjeto.urlDecode();
      $('ac16_resumoobjeto').value          = oRetorno.contrato.sResumoObjeto.urlDecode();
      $('ac16_numeroprocesso').value        = oRetorno.contrato.sNumeroProcesso.urlDecode();
      $('ac16_qtdrenovacao').value          = oRetorno.contrato.iNumeroRenovacao;
      $('ac16_tipounidtempo').value         = oRetorno.contrato.iTipoRenovacao;
      $('ac16_periodocomercial').value      = oRetorno.contrato.lPeriodoComercial;      
      $('ac50_sequencial').value            = oRetorno.contrato.iCategoriaAcordo;
      $('ac16_tipounidtempoperiodo').value  = oRetorno.contrato.iTipoUnidadeTempoVigencia;
      $('ac16_qtdperiodo').value            = oRetorno.contrato.iQtdPeriodoVigencia;
      
      js_pesquisaac16_acordogrupo(false);
      js_pesquisaac50_descricao(false);
      
      var dtInicio                     = oRetorno.contrato.dtInicio;
      var dtTermino                    = oRetorno.contrato.dtTermino;
		  if (js_somarDiasVigencia(dtInicio, dtTermino) != false) {
		    $('diasvigencia').value        = js_somarDiasVigencia(dtInicio, dtTermino);
		  }
      parent.document.formaba.acordogarantia.disabled = false;
      top.corpo.iframe_acordogarantia.location.href   = 'aco1_acordoacordogarantia001.php?ac12_acordo='+
         oRetorno.contrato.iSequencial;
      parent.document.formaba.acordopenalidade.disabled = false;
      top.corpo.iframe_acordopenalidade.location.href   = 'aco1_acordoacordopenalidade001.php?ac13_acordo='+
      oRetorno.contrato.iSequencial;
      js_exibeBotaoJulgamento();
    }
	}
}
