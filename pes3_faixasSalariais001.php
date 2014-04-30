<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';

require_once 'dbforms/db_funcoes.php';
require_once 'dbforms/db_classesgenericas.php';

require_once 'classes/db_gerfcom_classe.php';

$oDaoGerfcom = new cl_gerfcom;

$oRotulo = new rotulocampo;
$oRotulo->label('r44_selec');

/**
 * Periodo da folha, ano e mes atual de calculo
 */	 
$iAnoFolha = db_anofolha();
$iMesFolha = db_mesfolha();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
<style>
	#formatoRelatorio {
		width:56px;
	}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

	<fieldset style="width:600px;margin:30px auto 0 auto;">

		<legend>
			<strong>Faixas Salariais</strong>
		</legend>

		<form name="form1" method="post" action="">
	
			<table>

				<tr>
					<td nowrap width="135">
						<strong>Ano / Mês:</strong>
					</td>
					<td nowrap>
						<?php db_input('iAnoFolha', 5, 4, true, 'text', 1, 'onchange="js_alteraPeriodoFolha();"'); ?> &nbsp; / &nbsp;
						<?php db_input('iMesFolha', 5, 4, true, 'text', 1, 'onchange="js_alteraPeriodoFolha();"'); ?>
					</td>
				</tr>
				<tr>
					<td nowrap width="135">
						<strong>Listar Servidores:</strong>
					</td>
					<td nowrap>
						<?php 
						  $aOpcaoServidor = array("1" => "Não", "2" => "Sim");
						  db_select('iServidor', $aOpcaoServidor, true, 1);
						?>
					</td>
				</tr>
				<tr>
				  <td nowrap title="<?php echo $Tr44_selec;?>">
				    <?php db_ancora('<b>Seleção:</b>', 'js_pesquisasel(true)', 1);?>
				  </td>
				  <td>
				    <?php 
				      db_input('r44_selec', 5,  $Ir44_selec, true, 'text', 2, 'onchange="js_pesquisasel(false)"');
				      db_input('r44_descr', 40, $Ir44_selec, true, 'text', 3, '');
				    ?>
				  </td>
				</tr>
				<tr>
					<td nowrap title="Tipo de Relarório">
						<strong>Quebra:</strong>
					</td>
					<td> 
            <?php
							$aQuebras = array('geral' => 'Geral', 'regime' => 'Regime', 'lotacao' => 'Lotação', 'cargo' => 'Cargo');
						  db_select('sQuebraRelatorio', $aQuebras, false, 2);
            ?>
					</td>
				</tr> 
 
				<tr>
					<td nowrap title="Tipo de Relarório">
						<strong> Formato: </strong>
					</td>
					<td> 
            <?php
							$aFormatos = array('pdf' => 'PDF', 'csv' => 'CSV');
						  db_select('sFormatoRelatorio', $aFormatos, false, 2);
            ?>
					</td>
				</tr> 

				</table>

				<fieldset>

					<legend><strong>Tipos de folha</strong></legend>
					<table>
						<tr id="tipoFolha">
							<td nowrap colspan="2">
							<?php
							$aPontos = Array(
								"salario"      => "Salário",
								"rescisao"     => "Rescisão",
								"decimo13"     => "13o Salario",
								"complementar" => "Complementar"
							);

							db_multiploselect("pontos", "descr", "nselecionados", "selecionados", $aPontos, null, 6, 250, null, null, true, 'js_complementar();');
							?>
							</td>
						</tr>

						<tr id="linhaComplementares" style="display:none;">
							<td class="colunaComplentares" width="135" nowrap title="Número da complementar">
								<b>Nro. da complementar:</b>
							</td>
							<td class="colunaComplentares">
							<?php
								$aTodos = array('todos' => 'Todos');
								db_select('complementares', $aTodos, false, 2);
							?>
							</td>
							<td id="colunaErroComplentar" nowrap align="center" colspan="2" style="display:none;"></td>
						</tr>

					</table>

			</fieldset>

		</form>

		<fieldset>

			<legend><strong>Faixas salariais</strong></legend>

			<table>
				<tr>

					<td width="150"> 
						<strong>Valor inicial:</strong>
						<?php db_input('iValorInicial', 5, 4, true, 'text', 1, '', '', '', '', '8'); ?>
					</td>

					<td width="150"> 
						<strong>Valor final:</strong>
						<?php db_input('iValorFinal', 5, 4, true, 'text', 1, '', '', '', '', '8'); ?>
					</td>

					<td width="150"> 
						<input type="button" onclick="js_lancarFaixa();" value="Lancar faixa" />
					</td>

				</tr>
			</table>

			<div id="ctnGridFaixas"></div>

		</fieldset>

	</fieldset>

	<br />
	<center>
		<input type="button" name="processar" value="Processar" onclick="js_gerarRelatorio();" />
	</center>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>

<script type="text/javascript">

var sUrlRPC  = 'pes3_faixasSalariais.RPC.php';  

/** -------------------------------------------------------------------------------------------- 
 * 
 * Complementares - inicio
 * 
 */	 

/**
 * Ao mudar periodo folha, ano e mes, pesquisa as complementares
 */	 
function js_alteraPeriodoFolha() {

	/**
	 * Se linha com as complementares nao estiver visivel é pq nao foi selecionado tipo de folha complementar
	 */	 
	if ( $('linhaComplementares').style.display == 'none' ) {
		return false;
	}

	js_getComplementares();
}

/**
 * Funcao chamada toda vez que for alterado os tipos de folha
 * @todo alterar de iComplementar para sComplementar, foi alterado de numero para string 
 */	 
function js_complementar() {

  var oSelecionados = $('selecionados');
  var oParaSelecao  = $('nselecionados');

	var iTotalSeleciodos  = oSelecionados.length;
	var iTotalParaSelecao = oParaSelecao.length;

	for ( var iIndice = 0; iIndice < iTotalSeleciodos; iIndice++ ) {

		var sTipoFolha = oSelecionados.options[iIndice].value;

		if ( sTipoFolha == 'complementar' && $('linhaComplementares').style.display == 'none') {
			js_getComplementares();
		}
	}

	for ( var iIndice = 0; iIndice < iTotalParaSelecao; iIndice++ ) {

		var sTipoFolha = oParaSelecao.options[iIndice].value;

		if ( sTipoFolha == 'complementar' ) {

			$('linhaComplementares').style.display = 'none';

			$('complementares').innerHTML  = '';   
			$('complementares').options[0] = new Option( 'Todos', 'todos' ); 	
		}
	}
}

function js_getComplementares() {

  var oParametros  = new Object();
  var msgDiv       = "Pesquisando complementares. \n Aguarde ...";
  
  oParametros.exec      = 'getComplementares';  
	oParametros.iAnoFolha = $F('iAnoFolha');
	oParametros.iMesFolha = $F('iMesFolha');
  
  js_divCarregando(msgDiv,'msgBox');
   
	var oAjax = new Ajax.Request(
		sUrlRPC, 
		{
			method     : 'post',
			parameters : 'json=' + Object.toJSON(oParametros),
			onComplete : js_retornoComplentares
		}
	);   
}

function js_retornoComplentares(oAjax) {
  
  js_removeObj('msgBox');

  var oRetorno              = eval("("+oAjax.responseText+")");
	var sMensagem             = oRetorno.sMensagem.urlDecode();
	var oLinhaComplementares  = $('linhaComplementares');
	var oColunaErroComplentar = $('colunaErroComplentar');
  var oColunaComplentares   = $$('.colunaComplentares');
	var oComplentares         = $('complementares');
  var sLinha                = new String();
	var iIndiceOptions        = 0;

	/**
	 * Limpa select com as complementares e cria option para todos
	 */	 
	oComplentares.innerHTML               = '';
	oComplentares.options[iIndiceOptions] = new Option( 'Todos', 'todos' ); 	
  
	oLinhaComplementares.style.display = '';

	/**
	 * Erro 
	 */	 
	if (oRetorno.iStatus > 1) {

		var sErroComplementar = '<strong>' + sMensagem + '</strong>';
				
		/**
		 * Exibe coluna com mensagem de erro
		 */	 
		oColunaErroComplentar.innerHTML     = sErroComplementar;
		oColunaErroComplentar.style.display = '';

		/**
		 * Esconde colunas das complementares e desabilita select
		 */	 
		oColunaComplentares[0].style.display  = 'none';
		oColunaComplentares[1].style.display  = 'none';
		oComplentares.disabled = true;

		return false;
  } 

	/**
	 * Esconde linha com erro das complentares
	 */	 
	oColunaErroComplentar.style.display = 'none';

	/**
	 * Exibe colunas das complementres
	 */	 
	oColunaComplentares[0].style.display  = '';
	oColunaComplentares[1].style.display  = '';
	oComplentares.disabled = false;

	for ( var iIndice = 0; iIndice < oRetorno.aComplementares.length; iIndice++ ) {
		
		var oComplentar = oRetorno.aComplementares[iIndice];

		iIndiceOptions++;
		oComplentares.options[iIndiceOptions] = new Option( oComplentar.r48_semest , oComplentar.r48_semest ); 	
	}

}

/**
 * 
 * Complementares - fim
 *
 * -------------------------------------------------------------------------------------------- */


/** -------------------------------------------------------------------------------------------- 
 *
 * Grid faixas salariais - inicio
 *
 */	 

var aAlinhamentosFaixas = new Array();
var aHeaderFaixas       = new Array();
var aWidthFaixas        = new Array();

/**
 * Array com headers
 */
aHeaderFaixas[0] = 'Valor inicial';
aHeaderFaixas[1] = 'Valor final';
aHeaderFaixas[2] = 'Remover faixa';

/**
 * Tamanho das colunas
 */
aWidthFaixas[0] = '33%';  
aWidthFaixas[1] = '33%';  
aWidthFaixas[2] = '33%';  

/**
 * Alinhamento das colunas
 */
aAlinhamentosFaixas[0] = 'right';
aAlinhamentosFaixas[1] = 'right';
aAlinhamentosFaixas[2] = 'center';

/**
 * Monta html da grid
 */
oGridFaixas              = new DBGrid('datagridFaixas');
oGridFaixas.sName        = 'datagridFaixas';
oGridFaixas.nameInstance = 'oGridFaixas';
oGridFaixas.setCellWidth( aWidthFaixas );
oGridFaixas.setCellAlign( aAlinhamentosFaixas );
oGridFaixas.setHeader( aHeaderFaixas );
oGridFaixas.show( $('ctnGridFaixas') );
oGridFaixas.clearAll(true);

/**
 * Lanca faixa salarial na grid
 */	 
function js_lancarFaixa() {

	var oValorInicial = $('iValorInicial');
	var oValorFinal   = $('iValorFinal');
	var aLinha        = new Array();

	if ( oValorInicial.value == '' ) {

    alert('Informe o valor inicial da faixa');
		return false;
	}

	if ( oValorFinal.value == '' ) {

    alert('Informe o valor final da faixa');
		return false;
	}

	if ( new Number(oValorFinal.value) < new Number(oValorInicial.value) ) {

		alert('Valor final deve ser maior que valor inicial');
		return false;
	}

	/**
	 * Nao permite cadastrar faixas com mesmo intervalo
	 */	 
	if ( !js_verificaFaixasLancadas([oValorInicial.value, oValorFinal.value]) ) {
		return false;
	}

	/**
	 * Adiciona faixa salarial na grid e renderiza 
	 */	 
	FaixasSalariais.adicionarFaixa( new Number(oValorInicial.value), new Number(oValorFinal.value) );
  js_carregaGrid();

	/**
	 * Limpa os campos
	 */	 
	oValorInicial.value = null;
	oValorFinal.value   = null;
}

/**
 * Valida faixas lancadas
 * Nao permite lancar faixas com mesmo intervalo
 */	 
function js_verificaFaixasLancadas(aFaixa) {

  var iFaixaInicial = new Number(aFaixa[0]);
	var iFaixaFinal   = new Number(aFaixa[1]);

	if ( FaixasSalariais.getQuantidadeFaixas() > 0 ) {

		for ( var iIndice = 0; iIndice < FaixasSalariais.getQuantidadeFaixas(); iIndice++) {
      
			var oFaixaSalarial  = FaixasSalariais.aFaixas[iIndice];
			
			var iFaixaInicialVetor = new Number(oFaixaSalarial.iValorInicial);
			var iFaixaFinalVetor   = new Number(oFaixaSalarial.iValorFinal);
			
			var lValidaAntes  = iFaixaInicial < iFaixaInicialVetor && iFaixaFinal < iFaixaInicialVetor; 
			var lValidaDepois = iFaixaInicial > iFaixaFinalVetor   && iFaixaFinal > iFaixaFinalVetor; 	

			if ( !lValidaAntes && !lValidaDepois ) {                           

				alert('Faixa não pode sobrepor outra faixa');
			  return false;
			}
		}
	}

	return true;
}

/**
 * Remove faixa salarial da grid e renderiza grid
 */	 
function js_removerFaixa( iIndice ) {

	FaixasSalariais.removerFaixa(iIndice);
	js_carregaGrid();
}

/**
 * Carrega os Dados da Grid na Tela e a Renderiza 
 */	 
function js_carregaGrid() {
	
 oGridFaixas.clearAll(true);

 for ( var iIndice = 0; iIndice < FaixasSalariais.aFaixas.length; iIndice++ ) {
 
   oFaixaSalarial = FaixasSalariais.aFaixas[iIndice];

	 var aLinha = new Array();

	 aLinha[0] = js_formatar(oFaixaSalarial.iValorInicial, 'f');
	 aLinha[1] = js_formatar(oFaixaSalarial.iValorFinal, 'f');
	 aLinha[2] = '<input type="button" value="Remover" onclick="js_removerFaixa(' + iIndice + ');" />';

 	oGridFaixas.addRow(aLinha);
 }

 oGridFaixas.renderRows(); 
}

/**
 * Singleton das faixas salariais
 */	 
FaixasSalariais = {

	/**
	 * Array com as faixas salariais
	 */	 
	aFaixas : new Array(),
	
	/**
	 * Array com as faixas ordenandas
	 */	 
  aOrdemFaixas : new Array(),

	/**
	 * Adiciona faixa salaria
	 */	 
	adicionarFaixa : function(iValorInicial, iValorFinal) {

		var aOrdem = new Array();

		aOrdem[0] = iValorInicial;
		aOrdem[1] = iValorFinal;

		FaixasSalariais.aOrdemFaixas.push( aOrdem );

		FaixasSalariais.aOrdemFaixas.sort(function(iIndiceInicial, iIndiceFinal) {
			return iIndiceInicial[0] - iIndiceFinal[0];
		});

		for ( var iIndice = 0; iIndice < FaixasSalariais.aOrdemFaixas.length; iIndice++ ) {

			var oFaixaSalarial = new Object();

			oFaixaSalarial.iValorInicial = FaixasSalariais.aOrdemFaixas[iIndice][0];
			oFaixaSalarial.iValorFinal   = FaixasSalariais.aOrdemFaixas[iIndice][1];

			FaixasSalariais.aFaixas[iIndice] = oFaixaSalarial;
		}
	},

	/**
	 * Remove faixa salarial
	 */	 
	removerFaixa : function( iIndiceFaixa ) {
		FaixasSalariais.aOrdemFaixas.splice(iIndiceFaixa, 1);
		FaixasSalariais.aFaixas.splice(iIndiceFaixa, 1);
	},

	/**
	 * Retorna total de faixas da grid
	 */	 
  getQuantidadeFaixas : function () {
	  return FaixasSalariais.aFaixas.length;
	}	

}

/**
 * 
 * Grid faixas salariais - fim
 *
 * -------------------------------------------------------------------------------------------- */



/* -------------------------------------------------------------------------------------------- 
 *
 * Gerar relatorio - inicio
 *
 */ 

/**
 * Valida formulario 
 * - obrigando usuario informar tipo de folha e a faixa salarial
 */	 
function js_validaFormulario() {

	var oTiposFolha      = $('selecionados').options;
	var iTotalTiposFolha = oTiposFolha.length;

	if ( iTotalTiposFolha == 0 ) {

		alert('Seleciona pelo menos um tipo de folha');
		return false;
	}

	if ( FaixasSalariais.getQuantidadeFaixas() == 0 ) {

		alert('Informe pelo menos uma faixa salarial');
		return false;
	}

	return true;
}

function js_gerarRelatorio() {

	if ( !js_validaFormulario() ) {
		return false;
	}

  var oParametros  = new Object();
  var msgDiv       = "Gerando relatório. \n Aguarde ...";
  
  oParametros.exec              = 'gerarRelatorio';  
	oParametros.iAnoFolha         = $F('iAnoFolha');
	oParametros.iMesFolha         = $F('iMesFolha');
	oParametros.aFaixas           = new Array();
	oParametros.sQuebraRelatorio  = $F('sQuebraRelatorio');
	oParametros.sFormatoRelatorio = $F('sFormatoRelatorio');
	oParametros.aTiposFolha       = new Array();
	oParametros.iSelecao          = $F('r44_selec');
	oParametros.iOpcaoServidor    = $F('iServidor');

	var oTiposFolha      = $('selecionados').options;
	var iTotalTiposFolha = oTiposFolha.length;

	for ( var iIndice = 0; iIndice < iTotalTiposFolha; iIndice++ ) {
		oParametros.aTiposFolha[iIndice] =  oTiposFolha[iIndice].value;
	}

	if ( $('complementares').value != 'todos' ) {
		oParametros.iComplementar = $('complementares').value;
	}

	if ( FaixasSalariais.getQuantidadeFaixas() > 0 ) {
	  oParametros.aFaixas   = FaixasSalariais.aFaixas;
  }
 
  js_divCarregando(msgDiv, 'msgBox');
   
	var oAjax = new Ajax.Request(
		sUrlRPC, 
		{
			method     : 'post',
			parameters : 'json=' + Object.toJSON(oParametros),
			onComplete : js_retornoGerarRelatorio
		}
	);   

}

function js_retornoGerarRelatorio(oAjax) {

  js_removeObj('msgBox');

  var oRetorno              = eval("("+oAjax.responseText+")");
	var sMensagem             = oRetorno.sMensagem.urlDecode();

	/**
	 * Erro
	 */	 
	if ( oRetorno.iStatus > 1 ) {

		alert( sMensagem );
		return false;
	}

	var listagem  = oRetorno.sArquivo + "# Download do Arquivo - " + oRetorno.sArquivo;
	js_montarlista(listagem,'form1');  
}

/**
 * 
 * Gerar relatorio - fim
 *
 * -------------------------------------------------------------------------------------------- */

/**
 * Pesquisamos a selecao
 */
function js_pesquisasel(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_selecao', 
                        'func_selecao.php?funcao_js=parent.js_mostrasel1|r44_selec|r44_descr',
                        'Pesquisa',
                        true
                       );
   } else {
     
     if (document.form1.r44_selec.value != '') { 
       js_OpenJanelaIframe('top.corpo', 
                           'db_iframe_selecao', 
                           'func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostrasel', 
                           'Pesquisa', 
                           false
                          );
     } else {
       document.form1.r44_descr.value = '';
     }
   }
 }

function js_mostrasel(chave,erro) {
  
  document.form1.r44_descr.value = chave; 
  if (erro == true) {
     
    document.form1.r44_selec.focus(); 
    document.form1.r44_selec.value = ''; 
  }
}
 
function js_mostrasel1(chave1,chave2) {
  
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value = chave2;
  db_iframe_selecao.hide();
}
 
</script>