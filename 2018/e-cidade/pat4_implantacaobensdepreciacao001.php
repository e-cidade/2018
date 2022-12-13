<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
 
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" id='frmImplantacaoDepreciacao'>
    	<fieldset>
    		<legend>Filtros para Pesquisa</legend>
    		<table class="form-container">
    			<tr>
    			  <td><?php db_ancora('<b>Classificação: </b>', ' js_pesquisaClassificacaoInicial(true); ', 1); ?></td>
    			  <td>
    			    <?php 
    			      db_input('t64_codcla_ini', 5, "", true, 'text', 1, ' onchange="js_pesquisaClassificacaoInicial(false);" ');
    			      db_input('t64_descr_ini', 20, "", true, 'text', 3); 
    			    ?>
    			  </td>
    			  <td><?php db_ancora('<b>Até: </b>', ' js_pesquisaClassificacaoFinal(true); ', 1); ?></td>
    			  <td>
    			    <?php
    			    	db_input('t64_codcla_fin', 5, "", true, 'text', 1, ' onchange="js_pesquisaClassificacaoFinal(false);" ');
    			    	db_input('t64_descr_fin', 20, "", true, 'text', 3);
    			    ?>
    			  </td>
    			</tr>
    		</table>
    	</fieldset>
    	<input type="button" value="Pesquisar" onclick="js_envia();">
</form>
  <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>

var sUrl = "pat4_implantacaobensdepreciacao.RPC.php";
var iClassificacaoInicial = "";
var iClassificacaoFinal = "";

/**
 * Função que efetua a pesquisa de classificações de bens para a classificação inicial
 */
function js_pesquisaClassificacaoInicial(mostra) {

	if (mostra === true) {
		js_OpenJanelaIframe('top.corpo', 
				                'db_iframe_classificacao', 
				                'func_clabens.php?funcao_js=parent.js_mostraClassificacaoInicial|t64_codcla|t64_descr', 
				                'Pesquisa Classificação', 
				                true);
	} else {
		
		var sValorCampo = $F('t64_codcla_ini');
		js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_classificacao', 
                        'func_clabens.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraClassificacaoInicial', 
                        'Pesquisa Classificação', 
                        false);
	}
}

/**
 * Função que mostra o resultado da pesquisa de classificação de bens para a classificação inicial
 */
function js_mostraClassificacaoInicial() {

	if (arguments[1] === true) {

		$('t64_codcla_ini').value = '';
		$('t64_descr_ini').value  = arguments[0];
	} else if (arguments[1] === false) {

		$('t64_codcla_ini').value = arguments[2];
		$('t64_descr_ini').value  = arguments[0];
	}	else {

		$('t64_codcla_ini').value = arguments[0]; 
	  $('t64_descr_ini').value  = arguments[1];
	}  
	db_iframe_classificacao.hide();
}

/**
 * Função que efetua a pesquisa de classificações de bens para a classificação final
 */
function js_pesquisaClassificacaoFinal(mostra) {

	if (mostra === true) {
		js_OpenJanelaIframe('top.corpo', 
				                'db_iframe_classificacao', 
				                'func_clabens.php?funcao_js=parent.js_mostraClassificacaoFinal|t64_codcla|t64_descr', 
				                'Pesquisa Classificação', 
				                true);
	} else {
		
		var sValorCampo = $F('t64_codcla_fin');
		js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_classificacao', 
                        'func_clabens.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraClassificacaoFinal', 
                        'Pesquisa Classificação', 
                        false);
	}
}

/**
 * Função que mostra o resultado da pesquisa de classificação de bens para a classificação final
 */
function js_mostraClassificacaoFinal() {

	if (arguments[1] === true) {

		$('t64_codcla_fin').value = '';
		$('t64_descr_fin').value  = arguments[0];
	} else if (arguments[1] === false) {

		$('t64_codcla_fin').value = arguments[2];
		$('t64_descr_fin').value  = arguments[0];
	}	else {

		$('t64_codcla_fin').value = arguments[0]; 
	  $('t64_descr_fin').value  = arguments[1];
	}  
	db_iframe_classificacao.hide();
}

/**
 * Função que exibe a windowAux para ser inserido o tipo de deprecação e a vida útil
 */
function js_envia() {
  
  
	var iCodClaInicial    = $F('t64_codcla_ini');
	iClassificacaoInicial = $F('t64_codcla_ini');
	var iCodClaFinal      = $F('t64_codcla_fin');
	iClassificacaoFinal   = $F('t64_codcla_fin');

	if (iCodClaInicial == "") {

		alert(_M("patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_classificacao_inicial"));
		return false;
	}
	if (iCodClaFinal == "") {

		alert(_M("patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_classificacao_final"));
		return false;
	}
	if (iCodClaInicial > iCodClaFinal) {

		alert(_M("patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.classificacao_inicial_menor_final"));
		return false;
	}

	
	var larguraJanela     = screen.availWidth - 200;
	var alturaJanela      = screen.availHeight - 200;
	windowClassificacoes  = new windowAux('windowClassificacoes',
			                                  'Classificações Filtradas',
			                                  larguraJanela, alturaJanela);

	var sConteudowinAux  = '<div> ';                                                                         
	    sConteudowinAux += '  <fieldset style="margin-top:5px; width:97%;">                                           ';
	    sConteudowinAux += '    <legend><b>Dados da Depreciação</b></legend>                                          ';
	    sConteudowinAux += '    <table>                                                                               ';
	    sConteudowinAux += '      <tr>                                                                                ';
	    sConteudowinAux += '        <td nowrap="nowrap">                                                              ';
			sConteudowinAux += '          <a href="#" class="dbancora" style="text-decoration:underline;"                 ';
			sConteudowinAux += '             onclick="js_pesquisaTipoDepreciacao(true);"><b>Tipo de Depreciação:</b></a>  ';
	    sConteudowinAux += '        </td>                                                                             ';
	    sConteudowinAux += '        <td id="inputcodigotipodepreciacao"></td>                           ';
	    sConteudowinAux += '        <td id="inputdescricaotipodepreciacao"></td>                                      ';
	    sConteudowinAux += '        <td nowrap="nowrap">                                                              ';
	    sConteudowinAux += '          <b>Vida Útil:</b>                                                               ';
 	    sConteudowinAux += '        </td> '; 
	    sConteudowinAux += '        <td id="inputvidautil"></td>                                                      ';
      sConteudowinAux += '      </tr>';
      sConteudowinAux += '      <tr>';
      sConteudowinAux += '        <td nowrap="nowrap">                                                              ';
      sConteudowinAux += '          <a href="#" class="dbancora" style="text-decoration:underline;"                 ';
      sConteudowinAux += '             onclick="js_pesquisaTipoAquisicao(true);"><b>Tipo de Aquisição:</b></a>      ';
      sConteudowinAux += '        </td>                                                                             ';
      sConteudowinAux += '        <td id="inputcodigotipoaquisicao"></td>                             ';
      sConteudowinAux += '        <td id="inputdescricaotipoaquisicao"></td> ';   
      sConteudowinAux += '        <td><b>Percentual p/ calculo do valor residual:</b></td> ';
      sConteudowinAux += '        <td id="inputpercentualResidual"></td> ';                   
	    sConteudowinAux += '      </tr>                                                                               ';
	    sConteudowinAux += '    </table>                                                                              ';
	    sConteudowinAux += '  </fieldset>                                                                             ';
	    sConteudowinAux += '  <fieldset style="margin-top:5px; margin-bottom:5px; width:97%;">                        ';
	    sConteudowinAux += '    <legend><b>Classificação/Bens</b></legend>                                            ';
	    sConteudowinAux += '    <div id="gridContainer"></div>                                                        ';
	    sConteudowinAux += '  </fieldset>                                                                             ';     
	    sConteudowinAux += '  <center>                                                                                ';                                                                     
	    sConteudowinAux += '  <input type="button" value="Processar" onclick="js_processaTipoDepreciacao();">         ';
	    sConteudowinAux += '  <input type="button" value="Cancelar" onclick="js_cancelaProcessamentoClassificacao();">';
	    sConteudowinAux += '</center></div>                                                                           ';
  windowClassificacoes.setContent(sConteudowinAux);
  
  /*var sTextoMessageBoard  = 'Informe o tipo de depreciação e a vida útil dos bens. A informação será replicada para ';
      sTextoMessageBoard += 'as classificações/Bens listados abaixo.';*/

  var sTextoMessageBoard = _M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_depreciacao_e_vida_util');
      
  messageBoard = new DBMessageBoard('msgboard1',
		                                'Manutenção de Tipos de Depreciação.',
		                                sTextoMessageBoard,
		                                $('windowwindowClassificacoes_content'));

  dbGrid = new DBGrid('gridContainer');
  dbGrid.nameInstance = 'dbGrid';
  dbGrid.hasTotalizador = false;
  dbGrid.setHeight(alturaJanela - 300);
  dbGrid.allowSelectColumns(false);

  var aHeader = new Array();
      aHeader[0] = 'Classificação';
      aHeader[1] = '';
    	aHeader[2] = 'Descrição';
    	aHeader[3] = 'Código Bem';
    	aHeader[4] = 'Placa';
    	aHeader[5] = 'Vida Útil';
    	aHeader[6] = 'Depreciação';
    	aHeader[7] = 'Código Tipo Depreciação';

  var aAligns = new Array();
  		aAligns[0] = 'left';
  		aAligns[1] = 'left';
  		aAligns[2] = 'left';
  		aAligns[3] = 'right';
  		aAligns[4] = 'right';
  		aAligns[5] = 'right';
  		aAligns[6] = 'left';
  		aAligns[7] = 'left';

  dbGrid.setCellAlign(aAligns);
  dbGrid.setHeader(aHeader);
  dbGrid.aHeaders[1].lDisplayed = false;
  dbGrid.aHeaders[3].lDisplayed = false;
  dbGrid.aHeaders[7].lDisplayed = false;
  dbGrid.show($('gridContainer'));
 
  oTxtCodTipoDepreciacao = new DBTextField('oTxtCodTipoDepreciacao', 'oTxtCodTipoDepreciacao', '', 4);
  oTxtCodTipoDepreciacao.addEvent("onChange", ";js_pesquisaTipoDepreciacao(false);");
  oTxtCodTipoDepreciacao.show($('inputcodigotipodepreciacao'));
  oTxtCodTipoDepreciacao.setReadOnly(false);

  oTxtDescrTipoDepreciacao = new DBTextField('oTxtDescrTipoDepreciacao', 'oTxtDescrTipoDepreciacao', '', 20);
  oTxtDescrTipoDepreciacao.show($('inputdescricaotipodepreciacao'));
  oTxtDescrTipoDepreciacao.setReadOnly(true);

  oTxtVidaUtil = new DBTextField('oTxtVidaUtil', 'oTxtVidaUtil', '', 10);
  oTxtVidaUtil.addEvent("onKeyPress", 'return js_mask(event, "0-9")');
  oTxtVidaUtil.show($('inputvidautil'));
  oTxtVidaUtil.setReadOnly(false);

  oTxtPercentualResidual = new DBTextField('oPercentualResidual', 'oPercentualResidual', '', 10);
  oTxtPercentualResidual.addEvent("onKeyPress", 'return js_mask(event, "0-9|.")');
  oTxtPercentualResidual.show($('inputpercentualResidual'));
  oTxtPercentualResidual.setReadOnly(false);
   
  oTxtCodTipoAquisicao = new DBTextField('oTxtCodTipoAquisicao', 'oTxtCodTipoAquisicao', '', 4);
	oTxtCodTipoAquisicao.addEvent("onChange", ';js_pesquisaTipoAquisicao(false);');
  oTxtCodTipoAquisicao.show($('inputcodigotipoaquisicao'));
	oTxtCodTipoAquisicao.setReadOnly(false);

	oTxtDescrTipoAquisicao = new DBTextField('oTxtDescrTipoAquisicao', 'oTxtDescrTipoAquisicao', '', 20);
	oTxtDescrTipoAquisicao.show($('inputdescricaotipoaquisicao'));
	oTxtDescrTipoAquisicao.setReadOnly(true);  

  windowClassificacoes.setShutDownFunction(function() {
    js_cancelaProcessamentoClassificacao();
  });
  windowClassificacoes.show();
  messageBoard.show();
  js_mostraClassificacaoBensNaGrid();
}

function js_pesquisaTipoDepreciacao(mostra) {

	if (mostra === true) {
		js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_tipodepreciacao', 
                        'func_benstipodepreciacao.php?limita=true&funcao_js=parent.js_mostraTipoDepreciacao|t46_sequencial|t46_descricao', 
                        'Pesquisa Tipo Depreciacao', 
                        true);
	} else {

		var sValorCampo = $F('oTxtCodTipoDepreciacao');
		js_OpenJanelaIframe('top.corpo',
				                'db_iframe_tipodepreciacao',
				                'func_benstipodepreciacao.php?limita=true&pesquisa_chave='+sValorCampo+
				                '&funcao_js=parent.js_mostraTipoDepreciacao',
				                'Pesquisa Tipo Depreciação',
				                false);
	}
	$('Jandb_iframe_tipodepreciacao').style.zIndex = 10000;
}

function js_mostraTipoDepreciacao() {

	if (arguments[1] === true) {

		$('oTxtCodTipoDepreciacao').value = '';
		$('oTxtDescrTipoDepreciacao').value = arguments[0];
	} else if (arguments[1] === false) {
		
		$('oTxtDescrTipoDepreciacao').value = arguments[0];
	} else {

		$('oTxtCodTipoDepreciacao').value = arguments[0];
		$('oTxtDescrTipoDepreciacao').value = arguments[1];
	}
	db_iframe_tipodepreciacao.hide();	
}

function js_pesquisaTipoAquisicao(mostra) {
  
  if (mostra === true) {
    js_OpenJanelaIframe('top.corpo',
        								'db_iframe_tipoaquisicao',
        								'func_benstipoaquisicao.php?funcao_js=parent.js_mostraTipoAquisicao|t45_sequencial|t45_descricao',
        								'Pesquisa Tipo Aquisição',
        								true);
  } else {

    var sValorCampo = $F('oTxtCodTipoAquisicao');
    js_OpenJanelaIframe('top.corpo',
												'db_iframe_tipoaquisicao',
												'func_benstipoaquisicao.php?funcao_js=parent.js_mostraTipoAquisicao&pesquisa_chave='+sValorCampo,
												'Pesquisa Tipo Aquisição',
												false);
  }
  $('Jandb_iframe_tipoaquisicao').style.zIndex = 10000;
}

function js_mostraTipoAquisicao(oAjax) {

  if (arguments[1] === true) {

		$('oTxtCodTipoAquisicao').value = '';
		$('oTxtDescrTipoAquisicao').value = arguments[0];
	} else if (arguments[1] === false) {
		
		$('oTxtDescrTipoAquisicao').value = arguments[0];
	} else {

		$('oTxtCodTipoAquisicao').value = arguments[0];
		$('oTxtDescrTipoAquisicao').value = arguments[1];
	}
	db_iframe_tipoaquisicao.hide();
}

function js_mostraClassificacaoBensNaGrid() {

	var oParam = new Object();
			oParam.exec = 'getBensClassificacao';
			oParam.t64_codcla_ini = $F('t64_codcla_ini');
			oParam.t64_codcla_fin = $F('t64_codcla_fin');
  
  js_divCarregando(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.pesquisando_bens'), 'msgBox');
	var oAjax = new Ajax.Request(sUrl,
			                         {method: 'post',
			                          asynchronous: false,
			                          parameters: 'json='+Object.toJSON(oParam),
			                          onComplete: js_populaGrid
                               }
                              );
}

function js_populaGrid(oAjax) {
	
	js_removeObj('msgBox');
	var oRetorno = eval('('+oAjax.responseText+')');
	dbGrid.clearAll(true);
	if (oRetorno.status == 1) {

	  /**
	   * Percorre retorno do RPC imprimindo os dados na grid
	   */
	  var iLinha = 0;
	  for (iClassificacao in oRetorno.aDados) {

	    if (typeof(oRetorno.aDados[iClassificacao]) == "function") {
				continue;
	    }
      with (oRetorno.aDados[iClassificacao]) {

        /**
         * Imprime a linha que mostra a classificação dos bens
         */
        var aRowClassificacao = new Array();
        aRowClassificacao[0]  = classificacao;
        aRowClassificacao[1]  = '';
				aRowClassificacao[2]  = descricao.urlDecode();
				aRowClassificacao[3]  = '';
				aRowClassificacao[4]  = '';
				aRowClassificacao[5]  = '';
				aRowClassificacao[6]  = '';
				aRowClassificacao[7]  = '';
		    dbGrid.addRow(aRowClassificacao);

		    dbGrid.aRows[iLinha].sStyle = 'background-color:#eeeee2;';
		    dbGrid.aRows[iLinha].aCells.each(function (oCell, iIndice) {
			    
          oCell.sStyle +=';border-right: 1px solid #eeeee2;';
          oCell.sStyle += 'text-align:left;font-weight:bold;';
			  });

			  /**
			   * Percorremos os itens para verificarmos a quantidade de caracteres do campo t52_bem
			   * e guardamos essa informação para ser usada no js_strLeftPad
			   */
			  var iQuantCaracteresCodigoBem = 1;
			  itens.each(function(oItem, iSeqItem) {

				   if (oItem.t52_bem.length > iQuantCaracteresCodigoBem) {
				     iQuantCaracteresCodigoBem = oItem.t52_bem.length;
				   }
			  });

		    /**
		     *  Adiciona os bens associados a classificação adicionada anteriormente
		     */
		    itens.each(function(oItem, iSeqItem) {

					var sDescricao                 = "";
					var iSequencialTipoDepreciacao = "";

		      var aRowItem = new Array();
							aRowItem[0]  = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+classificacao+".";
							aRowItem[0] += js_strLeftPad(oItem.t52_bem, iQuantCaracteresCodigoBem, "0");
							aRowItem[1]  = '';
							aRowItem[2]  = oItem.t52_descr.urlDecode();
							aRowItem[3]  = oItem.t52_bem;
							aRowItem[4]  = oItem.t52_ident;
							aRowItem[5]  = oItem.t44_vidautil;

					if (oItem.t46_sequencial != "") {
						
					  sDescricao 								 = oItem.t46_sequencial+' - '+oItem.t46_descricao.urlDecode();
						iSequencialTipoDepreciacao = oItem.t46_sequencial;
					}
					
					aRowItem[6]  	 = sDescricao;
					aRowItem[7]  	 = iSequencialTipoDepreciacao;
					dbGrid.addRow(aRowItem);
				  iLinha++;
		    });
        iLinha++;
      }
	  }
    dbGrid.renderRows();
	} else {

    alert(oRetorno.message.urlDecode());
    windowClassificacoes.destroy();
	}
}

/**
 * Processa o tipo de depreciação
 * Valida se existem conflitos dos tipos de depreciação para os bens listados. Caso exista, será aberta uma
 * windowAux listando os bens em conflito.
 */
function js_processaTipoDepreciacao() {

  if (oTxtCodTipoDepreciacao.getValue() == "") {

    alert(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_depreciacao'));
    return false;
  }
  if (oTxtVidaUtil.getValue() == "") {

		alert(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_vida_util'));
		return false;
  }
  if (oTxtCodTipoAquisicao.getValue() == "") {

    alert(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_aquisicao'));
    return false;
  }
  if (oTxtPercentualResidual.getValue() == "") {

    alert(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.informe_percentual'));
    return false;
  }
	var aItensComConflito = new Array();
	aItensProcessar = new Array();
  dbGrid.aRows.each (function (oRow, iIdRow) {

		/**
		 *  Adiciona os itens que possuem tipo de depreciação diferentes do tipo escolhido pelo usuário.
		 *  Refatorar essa validação
		 */
    if (oRow.aCells[7].getValue().trim() != oTxtCodTipoDepreciacao.getValue() && oRow.aCells[7].getValue().trim() != "") {
      aItensComConflito.push(oRow);
    } else {
      aItensProcessar.push(oRow);
    }
  });

  if (aItensComConflito.length > 0) {

    var iLargura = $('windowClassificacoes').getWidth() - 100;
  	var iAltura  = $('windowClassificacoes').getHeight() - 100;
  	oWindowConflitos  = new windowAux('oWindowConflitos',
  			                              'Conflitos Encontrados',
  			                              iLargura, iAltura);

  	var sConteudowinAux  = '<div align="center">                                            ';
  			sConteudowinAux += '  <fieldset>                                                    ';
  			sConteudowinAux += '    <legend><b>Conflitos</b></legend>                           ';
  			sConteudowinAux += '    <div id="gridConflitosContainer"></div>                     ';
  			sConteudowinAux += '  </fieldset>                                                   ';
  			sConteudowinAux += '  <input type="button" name="btnProcessar" id="btnProcessar"    '; 
    		sConteudowinAux += '         value="Processar" onclick="js_configuraConflitos();">  ';
  			sConteudowinAux += '  <input type="button" name="btnCancelar" id="btnCancelar"      '; 
    		sConteudowinAux += '         value="Cancelar" onclick="js_fechaWindowConflitos();"> ';
  			sConteudowinAux += '</div>                                                          ';
		oWindowConflitos.setContent(sConteudowinAux);
		oWindowConflitos.allowCloseWithEsc(true);

	  /*var sTextoMessageBoard  = 'Os bens/classificações abaixo estão em conflito com a configuração de depreciação ';
        sTextoMessageBoard += 'informada. Marque as que deseja alterar o tipo de depreciação.';*/

    var sTextoMessageBoard = _M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.bens_em_conflito');
        
		oMsgBoardConflito = new DBMessageBoard('oMsgBoardConflito',
    	                                    'Conflitos encontrados no processamento',
    	                                    sTextoMessageBoard,
    	                                    oWindowConflitos.getContentContainer());
    oWindowConflitos.setChildOf(windowClassificacoes);
    oWindowConflitos.setShutDownFunction(function () {
      js_fechaWindowConflitos();
    });
    oWindowConflitos.show(40, 50);
    oMsgBoardConflito.show();
    
    oGridConflitos                = new DBGrid('gridConflitosContainer');
    oGridConflitos.nameInstance   = 'oGridConflitos';
    oGridConflitos.hasTotalizador = false;
    oGridConflitos.hasCheckbox    = true;
    oGridConflitos.setHeight(iAltura - 200);
    oGridConflitos.allowSelectColumns(false);

    var aHeader    = new Array();
        aHeader[0] = 'Classificação';
      	aHeader[1] = 'Descrição';
      	aHeader[2] = 'Código Bem';
      	aHeader[3] = 'Placa';
      	aHeader[4] = 'Vida Útil';
      	aHeader[5] = 'Depreciação';
      	aHeader[6] = 'Código Tipo Depreciação';

    var aAligns 	 = new Array();
    		aAligns[0] = 'left';
    		aAligns[1] = 'left';
    		aAligns[2] = 'right';
    		aAligns[3] = 'right';
    		aAligns[4] = 'right';
    		aAligns[5] = 'left';
    		aAligns[6] = 'left';

		oGridConflitos.setCellAlign(aAligns);
    oGridConflitos.setHeader(aHeader);
    oGridConflitos.aHeaders[3].lDisplayed = false;
    oGridConflitos.aHeaders[7].lDisplayed = false;
    oGridConflitos.show($('gridConflitosContainer'));

    oGridConflitos.clearAll(true);

    aItensComConflito.each(function (oRowItemConlito, iIdLinha) {

      var aLinha    = new Array();
          aLinha[0] = oRowItemConlito.aCells[0].getValue();
          aLinha[1] = oRowItemConlito.aCells[2].getValue();
          aLinha[2] = oRowItemConlito.aCells[3].getValue();
          aLinha[3] = oRowItemConlito.aCells[4].getValue();
          aLinha[4] = oRowItemConlito.aCells[5].getValue();
          aLinha[5] = oRowItemConlito.aCells[6].getValue();
          aLinha[6] = oRowItemConlito.aCells[7].getValue();
      oGridConflitos.addRow(aLinha);
    });
    oGridConflitos.renderRows();
  } else {
    
    if (!confirm(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.deseja_implementar_depreciacao'))) {
			return false;
    }
  	js_processarImplementacao();
  }

}


function js_configuraConflitos() {

  if (!confirm(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.deseja_implementar_depreciacao'))) {
		return false;
  }

	var aLinhasSelecionadas = oGridConflitos.getSelection("object");
	aLinhasSelecionadas.each(function(oLinhaSelecionada, iIdLinha){
	  aItensProcessar.push(oLinhaSelecionada);
	});
  js_processarImplementacao();
}

function js_processarImplementacao() {

  var aBensImplementar = new Array();

  if (aItensProcessar.length == 0) {

    alert(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.sem_itens'));
    return false;
  }
  
  var iItensPorGrupo = 500;
  var aGruposBens    = new Array();
  var iNumeroGrupo   = 0;
  var iTotalItens    = 0;
  sMessageError      = '';
  /*
   * Dividimos os bens em grupos de 500 itens
   */
  aItensProcessar.each(function(oBemImplementar, iIdLinha) {

    if (oBemImplementar.aCells[3].getValue().trim() != "") {
      
  		var oBem  = new Object();
  		oBem.iBem = oBemImplementar.aCells[3].getValue();
  		aBensImplementar.push(oBem);
  		iTotalItens++;
  		if (iTotalItens ==  iItensPorGrupo) {
  		  
  		  aGruposBens.push(aBensImplementar);
  		  aBensImplementar = new Array();  
  		  iTotalItens = 0;
  		}
    }
    
     
     
    
  });
  
  /**
   * Caso ainda restou algum que nao tem o total de itens de um grupo, 
   *criamos um grupo com esse restante de itens
   */
  if (iTotalItens > 0 && iTotalItens < iItensPorGrupo) {
      aGruposBens.push(aBensImplementar);
  }
  iTotalGrupos = aGruposBens.length;
  for (var iGrupo = 0; iGrupo < iTotalGrupos; iGrupo++) {

    js_processarImplementacaoPorGrupo(aGruposBens[iGrupo], iGrupo+1, iTotalGrupos);
    if (sMessageError != "") {
      
      alert(sMessageError);
      break;
     }
  };
  if (sMessageError != "") {
    return false;
  }
  alert(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001.bens_implementados'));
  js_cancelaProcessamentoClassificacao();
}
   
function js_retornoProcessamentoImplementacaoGrupo(oAjax) {
  
  js_removeObj('msgBox');
  sMessageError = '';
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {
    
    $('t64_codcla_ini').value = '';
    $('t64_descr_ini').value  = '';
    $('t64_codcla_fin').value = '';
    $('t64_descr_fin').value  = '';
  } else {
   
    sMessageError = oRetorno.message.urlDecode();
    return false;
  }
}


function js_processarImplementacaoPorGrupo(aItensGrupo, iGrupo, iTotalGrupo) {

	js_divCarregando(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao001. processando_grupo', {grupo: iGrupo, totalGrupo: iTotalGrupo}),'msgBox');
  //js_divCarregando('Aguarde, processando grupo '+iGrupo+' de '+iTotalGrupo+' Grupos de Itens', 'msgBox');
  lComGrupoProcessamento          = true;
  var oParam                      = new Object();
  oParam.exec                     = 'processarImplementacao';
  oParam.iTipoDepreciacao         = $F('oTxtCodTipoDepreciacao');
  oParam.iVidaUtil                = $F('oTxtVidaUtil');
  oParam.iTipoAquisicao           = $F('oTxtCodTipoAquisicao');
  oParam.nPercentualValorResidual = oTxtPercentualResidual.getValue();
  oParam.aItens                   = aItensGrupo;
  
  var oAjax = new Ajax.Request(sUrl,
                               {method: 'post',
                               asynchronous:false,
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoProcessamentoImplementacaoGrupo
                               }
                              );
}

function js_fechaWindowConflitos() {

  oWindowConflitos.destroy();
}

function js_cancelaProcessamentoClassificacao() {
  windowClassificacoes.destroy();
}
/**
function js_validaCalculos() {

  var oParam      = new Object();
      oParam.exec = 'validarCalculosEfetuados';

  var oAjax = new Ajax.Request(sUrl,
                               {method: 'post',
                                asynchronous: false,
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoValidaCalculos
                               }
                              );
}

function js_retornoValidaCalculos(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    
    $('frmImplantacaoDepreciacao').disable();
    alert(oRetorno.message.urlDecode());
  }
}

js_validaCalculos();
*/
</script>
<script>

$("t64_codcla_ini").addClassName("field-size2");
$("t64_descr_ini").addClassName("field-size7");
$("t64_codcla_fin").addClassName("field-size2");
$("t64_descr_fin").addClassName("field-size7");

</script>