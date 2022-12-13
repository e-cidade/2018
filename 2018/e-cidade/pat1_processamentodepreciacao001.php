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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_cfpatri_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET);

if ($oGet->iProcessamento == 1) {
  $sLegenda = "Processamento Automático";
} else {
  $sLegenda = "Processamento Manual";
}
$iAnoSessao = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<script type="text/javascript" src="scripts/scripts.js"></script>
	<script type="text/javascript" src="scripts/strings.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/datagrid.widget.js"></script>
	<script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
	<script type="text/javascript" src="scripts/widgets/messageboard.widget.js"></script>
	<script type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body  bgcolor=#CCCCCC >
<div class="container">
	<fieldset>
		<legend><?=$sLegenda;?></legend>
		<table class="form-container">
			<tr>
				<td>Ano:</td>
				<td>
					<?php
					  db_input("iAnoAtual", 10, false, true, "text", 3);
					?>
				</td>
			</tr>
			<tr>
				<td>Mês:</td>
				<td>
					<select id="iMes" name="iMes">
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="button" name="btnProcessa" id="btnProcessa" value="Processar">
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
	var sUrlRPC = "pat4_processamentodepreciacao.RPC.php";
	var oGet    = js_urlToObject(window.location.search);

	/*
	 * Função que será executada para buscar os meses que o usuário pode depreciar
	 */
	function js_getMesesDepreciados() {

	  var oParam  							= new Object();
	  oParam.exec 							= "getMesesDepreciados";
	  oParam.iTipoProcessamento = oGet.iProcessamento;

	  js_divCarregando(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.carregando_mes'), "msgBox");

		var oAjax = new Ajax.Request(sUrlRPC,
                                {method: 'post',
                                 asynchronous: false,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheMesesDepreciados
                                }
                               );
	}
	/**
	 * Preenche o combobox com os meses que o usuário pode depreciar.
	 */
	function js_preencheMesesDepreciados(Ajax) {

	  js_removeObj("msgBox");
	  var oRetorno = eval("("+Ajax.responseText+")");
	  $("iMes").options.length = 0;
	  var aMeses = new Array();
	  aMeses[0]  = "Janeiro";
	  aMeses[1]  = "Fevereiro";
	  aMeses[2]  = "Março";
	  aMeses[3]  = "Abril";
	  aMeses[4]  = "Maio";
	  aMeses[5]  = "Junho";
	  aMeses[6]  = "Julho";
	  aMeses[7]  = "Agosto";
	  aMeses[8]  = "Setembro";
	  aMeses[9]  = "Outubro";
	  aMeses[10] = "Novembro";
	  aMeses[11] = "Dezembro";

	  /*
	   * Verifica se o mes disponível é 0. Caso seja as depreciações já foram realizadas para o ano.
	   */
	  if (oRetorno.iMesDisponivel == 0) {
		  
			alert(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.depreciacao_ja_executada'));
	  }
	  if (oRetorno.message != "") {
			alert(oRetorno.message.urlDecode());
	  }

	  /**
	   * Percorre o array de meses bloqueando os meses que não poderam ser
	   * processados pois já estão processados.
	   */
	  sMesSelecionado      = "";
	  var oOptionSelecione = new Option("Selecione", 0);
	  $("iMes").appendChild(oOptionSelecione);

	  aMeses.each(function (sMes, iMes) {

	    iMesCorrente = (iMes+1);
			var oOption  = new Option(sMes, iMesCorrente);
			Option.id    = iMesCorrente;

			if (iMesCorrente != oRetorno.iMesDisponivel) {

				oOption.disabled = true;
			} else {

			  sMesSelecionado = sMes;
			  iMesProcessar   = iMesCorrente;
			}
			$("iMes").appendChild(oOption);
		});
	}

	/**
	 *  Valida os dados do formulário e o tipo de processamento que o usuário está acessando.
   *  Direciona o programa para a função JS responsável.
	 */
  $("btnProcessa").observe('click', function() {

		if ($F("iMes") == "0") {

			alert(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.informe_mes'));
			return false;
		}

		js_verificaBensSemDepreciacao();
  });

  function js_executaProcessamentoAutomatico() {

    if (!confirm(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.deseja_executar_processamento'))) {
			return false;
    }
		js_divCarregando(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.executando_depreciacao'), "msgBox");

		var oParam           = new Object();
		oParam.exec 				 = "executaDepreciacaoAutomatico";
		oParam.iMesProcessar = $F("iMes");
		oParam.iAnoSessao    = <?=$iAnoSessao;?>;

		var oAjax = new Ajax.Request(sUrlRPC,
        												 {method: 'post',
      													  asynchronous: false,
      													  parameters: 'json='+Object.toJSON(oParam),
      													  onComplete: js_retornoExecutaProcessamentoAutomatico
	                               }
    														);
  }

  function js_retornoExecutaProcessamentoAutomatico (oAjax) {

    js_removeObj("msgBox");
	  var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {

      alert (oRetorno.message.urlDecode());
      js_relatorioBensSemDepreciacao();
      return false;
    } else {

  	  alert(oRetorno.message.urlDecode());
  	  js_getMesesDepreciados();
    }
  }

  /**
   * Esta função chama o relatório de Bens Sem Depreciação.
   */
  function js_relatorioBensSemDepreciacao() {

    var sUrl = "pat2_benssemdepreciacao002.php";
    var jan  = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }

  /**
   * Verifica se há bens que não possui depreciação cadastrada.
   */
  function js_verificaBensSemDepreciacao() {

    var oParam           = new Object();
		oParam.exec 				 = "verificaBensSemDepreciacao";

		js_divCarregando(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.verificando_depreciacao_cadastrada'), "msgBox");
		var oAjax = new Ajax.Request(sUrlRPC,
        												 {method: 'post',
      													  asynchronous: false,
      													  parameters: 'json='+Object.toJSON(oParam),
      													  onComplete: js_retornoBensSemDepreciacao
	                               }
    														);
  }
  function js_retornoBensSemDepreciacao(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj("msgBox");
    switch (oRetorno.status) {

      case 1:

        /**
    		 * 1 = Automático
    		 * 2 = Manual
    		 */
    		if (oGet.iProcessamento == 1) {
    			js_executaProcessamentoAutomatico();
    		} else {
    			js_openWindowItens();
    		}
        break;
      case 2:

        alert (oRetorno.message.urlDecode());
        js_relatorioBensSemDepreciacao();
        break;
    }

  }



	function js_openWindowItens() {



		var sContentItens  = "<center>";
		sContentItens     += "  <fieldset>";
		sContentItens     += "    <legend><b>Itens</b></legend>";
		sContentItens     += "    <div id='ctnGridItens'></div>";
		sContentItens     += "  </fieldset>";
		sContentItens     += "  <br>";
		sContentItens     += "  <input type='button' name='btnExecutaManual' id='btnExecutaManual' value='Processar' onclick='js_processaDepreciacaoManual();'>";
		sContentItens     += "  <input type='button' name='btnCancelaManual' id='btnCancelaManual' value='Cancelar'";
	  sContentItens     += "         onclick='js_fecharWindowProcessamentoManual();'>";
		sContentItens     += "</center>";

	  /**
	   * Monta um objeto do Tipo WindowAux
	   */
		var nWindowWidth  = (screen.availWidth - 200);
		var nWindowHeight = (screen.availHeight -200);
		oWindowItens = new windowAux("oWindowItens", "Depreciação de Bens", nWindowWidth, nWindowHeight);
		oWindowItens.setContent(sContentItens);

		/**
		 * Monta um objeto do tipo messageBoard
		 */
		var sTituloItens = "Processamento manual do mês de "+sMesSelecionado+"/"+<?=$iAnoSessao;?>;
		var sHelpItens   = "Informe o valor em porcentagem que deverá ser depreciado para cada bem.";
		oMessageItens    = new messageBoard("oMessageItens", sTituloItens, sHelpItens, oWindowItens.getContentContainer());

		oWindowItens.show();
		oWindowItens.setShutDownFunction(function(){
		  js_fecharWindowProcessamentoManual();
		});
		oMessageItens.show();

		/**
		 *  Criamos uma grid para listar os itens que poderão ser depreciados
		 */
		oGridItens 							= new DBGrid('ctnGridItens');
		oGridItens.nameInstance = "oGridItens";
		oGridItens.hasCheckbox  = false;
		nGridHeight             = (nWindowHeight - 300);
		oGridItens.setHeight(nGridHeight);

		var aHeaders   = new Array("Código",
                               "Bem",
                               "Valor Aquisição",
                               "Valor Residual",
                               "Valor Atual",
                               "Valor Depreciável",
                               "Percentual à Depreciar",
                               "Valor à Depreciar",
                               "Percentual Restante",
                               "Valor Depreciado");

		var aAligns    = new Array("center",
                               "left",
                               "right",
                               "right",
                               "right",
                               "right",
                               "right",
                               "right",
                               "right",
                               "right");
		var aSizeCells = new Array("5%", "25%", "10%","10%", "10%", "10%", "10%", "10%", "10%", "10%");

		oGridItens.setCellAlign(aAligns)
		oGridItens.setCellWidth(aSizeCells);
		oGridItens.setHeader(aHeaders);
		oGridItens.show($('ctnGridItens'));
		js_getItensParaProcessamento();
	}


	/**
	 *  Busca os bens para informar os percentuais que serão depreciados em cada bem.
	 */
	function js_getItensParaProcessamento() {

		var oParam           = new Object();
		oParam.exec 			   = "getItensProcessamentoManual";
		oParam.iMesProcessar = $F('iMes');
		
	  js_divCarregando(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.buscando_bens'), "msgBox");
		var oAjax = new Ajax.Request(sUrlRPC,
                        				 {method: 'post',
                        				  asynchronous: false,
                        				  parameters: 'json='+Object.toJSON(oParam),
                        				  onComplete: js_preencheGridItens
                                 }
                        				);
	}

	/**
	 * Preenche a grid de itens do processamento manual
	 */
	function js_preencheGridItens(oAjax) {

    js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");
		if (oRetorno.status == 2) {

			alert(oRetorno.message.urlDecode());
			return false;
		}

		if (oRetorno.aBens.length == 0){
			alert(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.bens_nao_encontados'));
			js_fecharWindowProcessamentoManual();
			return false;
		}

		oGridItens.clearAll(true);
		oRetorno.aBens.each(function (oBem, iLinha) {

		  var sNomeObjetoTextField = "oTextField_"+iLinha;
		  var oInputTipo = eval("sNomeObjetoTextField = new DBTextField('"+sNomeObjetoTextField+"', '"+sNomeObjetoTextField+"', 0, 10);");
		  oInputTipo.addStyle("text-align","right");
		  oInputTipo.addStyle("width","99%");
		  oInputTipo.addEvent('onChange', ';js_calculaManual("'+iLinha+'", this);');
		  oInputTipo.addEvent('onKeyDown', ';js_spin(event, this);');
		  oInputTipo.addEvent('onKeyPress', "return js_mask(event, \"0-9|.\");");

      var nValorJaDepreciado      = ((oBem.nValorAquisicao - oBem.nValorResidual) - oBem.nValorDepreciavel);
      var nPercentualJaDepreciado = (100 / ((oBem.nValorAquisicao - oBem.nValorResidual) / nValorJaDepreciado));

		  var aRow = new Array();
		  aRow[0]  = oBem.iCodigoBem;
	    aRow[1]  = oBem.sDescricao.urlDecode();
	    aRow[2]  = js_formatar(oBem.nValorAquisicao, 'f');
	    aRow[3]  = js_formatar(oBem.nValorResidual, 'f');
	    aRow[4]  = js_formatar(oBem.nValorAtualizado, 'f');
      aRow[5]  = js_formatar(oBem.nValorDepreciavel, 'f');
      aRow[6]  = oInputTipo;
      aRow[7]  = "";
      aRow[8]  = js_formatar((100 - nPercentualJaDepreciado), "f");
      aRow[9]  = js_formatar(nValorJaDepreciado, 'f');

      oGridItens.addRow(aRow);
		});
		oGridItens.renderRows();
	}

  /**
   * Efetua o cálculo para validar se o usuário pode depreciar o percentual passado no campo input da grid
   */
	function js_calculaManual(iLinha, oTextField) {

	  var nValorAquisicao 	= js_strToFloat(oGridItens.aRows[iLinha].aCells[2].getValue());
	  var nValorResidual  	= js_strToFloat(oGridItens.aRows[iLinha].aCells[3].getValue());
	  var nValorAtual       = js_strToFloat(oGridItens.aRows[iLinha].aCells[4].getValue());
	  var nValorParaCalculo = new Number((nValorAquisicao - nValorResidual));
	  var nValorDepreciado  = ((oTextField.getValue() * nValorParaCalculo) / 100);

	  if (nValorDepreciado > nValorAtual) {

	    alert(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.valor_total_maior_valor_atualizado'));
			var oInputZero = eval("oTextField_"+iLinha);
			oInputZero.setValue('0');
			$("ctnGridItensrow"+iLinha+"cell7").innerHTML = "&nbsp;";
			return false;
	  }
	  oGridItens.aRows[iLinha].aCells[7].sContent   = js_formatar(nValorDepreciado, 'f');
	  $("ctnGridItensrow"+iLinha+"cell7").innerHTML = js_formatar(nValorDepreciado, 'f');
	}

	/**
	 * Processa a depreciação manual dos bens.
	 */
	function js_processaDepreciacaoManual() {

	  var aLinhasVazias      = new Array();
	  var aLinhasPreenchidas = new Array();

	  /*
	   * Percorremos a grid de itens validando os itens que tiveram percentual informado
	   */
	  oGridItens.aRows.each(function (oLinha, iIdLinha) {

			if (oLinha.aCells[6].getValue() == "" || oLinha.aCells[6].getValue() == 0) {
			  aLinhasVazias.push(oLinha);
			} else {

				var oLinhaPreenchida         = new Object();
				oLinhaPreenchida.iCodigoBem  = oLinha.aCells[0].getValue();
				oLinhaPreenchida.nPercentual = new Number(oLinha.aCells[6].getValue()).valueOf();
				aLinhasPreenchidas.push(oLinhaPreenchida);
			}
		});

	  /*
	   * Comunicamos o usuário que existem bens com valor zero e por este motivo não serão processados.
	   */
	  if (aLinhasVazias.length > 0) {

      alert(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.informe_percentual'));
      return false;
	  }

	  var oParam 						  = new Object();
	  oParam.exec             = "executaDepreciacaoManual";
	  oParam.iMesProcessar    = iMesProcessar;
	  oParam.iAnoSessao       = <?=$iAnoSessao;?>;
	  oParam.aBensDepreciados = aLinhasPreenchidas;

	  js_divCarregando(_M('patrimonial.patrimonio.pat1_processamentodepreciacao001.processando_depreciacao'), "msgBox");
		var oAjax = new Ajax.Request(sUrlRPC,
                        				 {method: 'post',
                        				  asynchronous: false,
                        				  parameters: 'json='+Object.toJSON(oParam),
                        				  onComplete: js_completaExecucaoManual
                                }
                        				);
	}

	/**
	 * Função executada após a conclusão do processo manual
	 */
	function js_completaExecucaoManual(oAjax) {

	  js_removeObj("msgBox");
	  var oRetorno = eval("("+oAjax.responseText+")");

	  alert(oRetorno.message.urlDecode());
	  js_fecharWindowProcessamentoManual();
	  js_getMesesDepreciados();

	}
	/**
	 * Fecha a Janela que permite ao usuário selecionar os bens do processamento manual
	 */
	function js_fecharWindowProcessamentoManual() {
		oWindowItens.destroy();
	}

	$("iAnoAtual").value = <?=$iAnoSessao;?>;
  js_getMesesDepreciados();

  function js_spin(event, button) {

    var iMinino  = 0;
    var iMaximo  = 100;
    var iTecla = event.which;
    if (iTecla == 40) {
      if (button.value > 0) {
        button.value = new Number(button.value) - new Number(1);
      }
    }
    if (iTecla == 38) {
      if (button.value < 100) {
        button.value = new Number(button.value) + new Number(1);
      }
    }
  }
</script>
<script>

$("iAnoAtual").addClassName("field-size2");

</script>