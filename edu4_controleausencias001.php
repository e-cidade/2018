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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oGet    = db_utils::postMemory($_GET);
$oRotulo = new rotulocampo;

$oRotulo->label("ed20_i_codigo");
$oRotulo->label("z01_nome");
$oRotulo->label("ed321_inicio");
$oRotulo->label("ed321_final");
$oRotulo->label("ed322_sequencial");
$oRotulo->label("ed322_periodoinicial");
$oRotulo->label("ed322_periodofinal");

/**
 * Nao é permitido alterar o regente
 */
$iPesquisaDocente = 1;

if ($oGet->db_opcao != '1') {
	$iPesquisaDocente = 3;
}


// edu4_controleausencias001.php?db_opcao=1
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link rel="stylesheet" type="text/css" href="estilos.css"/>
<link rel="stylesheet" type="text/css" href="estilos/DBtab.style.css"/>
<link rel="stylesheet" type="text/css" href="estilos/grid.style.css"/>
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>

<style type="text/css">

.bold{
	font-weight: bold;
}

</style>
</head>
<body bgcolor="#cccccc" >

<div><?MsgAviso(db_getsession("DB_coddepto"),"escola");?></div>

<div style="margin-top: 15px;" id = 'ctnAbas'></div>

<div id='ctnAbaAusencia'>
	<fieldset style="width: 600px; margin: 0 auto;">
		<legend class="bold" >Manutenção de Ausências e Substituições</legend>
		<table>
			<tr>
				<td colspan="4">
					<?php
						db_input('ed321_sequencial', 10, '', true,'hidden', 3);
				  ?>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold" id='ctnRegente'>
					<?php
						db_ancora("Regente:","js_buscaDocente(true); ",$iPesquisaDocente);
				  ?>
				</td>
				<td colspan="3" nowrap="nowrap" >
				  <?php
				    db_input('ed20_i_codigo', 10, $Ied20_i_codigo, true, 'text', 3, " onchange='js_buscaDocente(false);'");
				    db_input('z01_nome', 55, $Iz01_nome, true,'text', 3,'');
				    db_input('iCgm', 10, '', true,'hidden', 3,'');
				  ?>
				</td>
			</tr>
		  <tr>
				<td nowrap="nowrap" class="bold">
					Tipo de Ausência:
				</td>
				<td nowrap="nowrap" colspan="3" id='ctnTipoLancamento'>
					<select id='tipoLancamento' style="width:100%">
						<option value='' selected='selected'>Selecione</option>
					</select>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold">
					Data Inicial:
				</td>
				<td nowrap="nowrap" >
					<?php
					  db_inputdata('ed321_inicio', "", "", "", true, 'text', $oGet->db_opcao,"");
					?>
				</td>
				<td nowrap="nowrap" class="bold">
					Data Final:
				</td>
				<td nowrap="nowrap">
				  <?php
					  db_inputdata('ed321_final', "", "", "", true, 'text', $oGet->db_opcao,"");
					?>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" colspan="4">
					<fieldset>
						<legend class="bold">Observação</legend>
						<textarea rows="4" cols="80" id='observacao' ></textarea>
						<br>
						<label id='strLimite'>Limite de caracteres: <span class="bold" id='limiteCaracteres'>300</span></label>
					</fieldset>
				</td>
			</tr>
		</table>
	</fieldset>
	<center>
	  <div id = "ctnButton"></div>
	</center>
</div>


<!-- ABA 2 (SUBSTITUTO) -->
<div id='ctnAbaSubstituto'>

	<fieldset style="width: 600px; margin: 0 auto;">
		<legend class="bold" >Manutenção de Ausência e Substituições</legend>
		<table>
			<tr>
				<td colspan="4">
					<?php
						db_input('ed322_sequencial', 10, '', true,'hidden', 3);
				  ?>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold">Turma(s):</td>
				<td id='ctnTurmas' colspan="3"></td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold">Disciplina(s):</td>
				<td id='ctnDisciplina' colspan="3"></td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold" id='docenteSubstituto'>
					<?php
						db_ancora("Regente Substituto:", "js_buscaDocenteSubstituto(true); ", 1);
				  ?>
				</td>
				<td nowrap="nowrap" colspan="3">
					<?php
				    db_input('iSubstituto', 10, $Ied20_i_codigo, true, 'text', 3, " onchange='js_buscaDocenteSubstituto(false);'");
				    db_input('sNomeSubstituto', 55, $Iz01_nome, true,'text', 3,'');
				    db_input('iCgmSubstituto', 10, '', true,'hidden', 3,'');
				  ?>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold">Tipo de Substituição:</td>
				<td id='ctnTipoVinculo' colspan="3"></td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="bold">
					Data Inicial:
				</td>
				<td nowrap="nowrap" >
					<?php
					  db_inputdata('ed322_periodoinicial', "", "", "", true, 'text', 1,"");
					?>
				</td>
				<td nowrap="nowrap" class="bold">
					Data Final:
				</td>
				<td nowrap="nowrap">
				  <?php
					  db_inputdata('ed322_periodofinal', "", "", "", true, 'text', 1,"");
					?>
				</td>
			</tr>
		</table>
	</fieldset>
	<center>
	  <div id = "ctnButtonSubstituto">
	  	<input type="button" id='btnSubstituto' name='btnSubstituto' value='Salvar' onclick='js_vincularSubstituto()' />
	  	<input type="button" id='novo' name='novo' value='Novo Registro' onclick='js_limpaAbaSubstituto()' />
	  </div>
	</center>
	<br />
	<fieldset style="width: 1300px; margin: 15px auto;">
		<legend class="bold">Lista de Substitutos</legend>
		<div id='ctnGridSusbstitutos' style="width: 1298px;"></div>
	</fieldset>

</div>

</body>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">

var sUrlRPC = 'edu4_controleausencia.RPC.php';
var oGet    = js_urlToObject();


var oBotaoAusencia = document.createElement('input');
oBotaoAusencia.type  = "button";
oBotaoAusencia.id    = "action";
oBotaoAusencia.name  = "action";
oBotaoAusencia.value = "Salvar";
$('ctnButton').appendChild(oBotaoAusencia);

/**
 * Busca Regente
 */
function js_buscaDocente(lMostra) {

  var sUrl = "func_rechumanoescolanovo.php";
  if (lMostra) {

    sUrl += "?funcao_js=parent.js_preencheDocente|ed20_i_codigo|z01_nome|z01_numcgm";
    sUrl += "&lProfessor=true";
    js_OpenJanelaIframe('', 'db_iframe_rechumano', sUrl, 'Pesquisa Regente', true);
  } else if ($F('ed20_i_codigo') != '') {

    sUrl += "?funcao_js=parent.js_preencheDocente2";
    sUrl += "&pesquisa_chave="+$F('ed20_i_codigo');
    sUrl += "&lProfessor=true";
    js_OpenJanelaIframe('', 'db_iframe_rechumano', sUrl, 'Pesquisa Regente', false);
  } else {

    $('ed20_i_codigo').value = '';
    $('z01_nome').value      = '';
  }
}

function js_preencheDocente(iCodigo, sNome, iCgm) {

  $('ed20_i_codigo').value = iCodigo;
  $('z01_nome').value      = sNome;
  $('iCgm').value          = iCgm;
  db_iframe_rechumano.hide();
}

function js_preencheDocente2(sNome, iCgm,  lErro) {

  $('z01_nome').value = sNome;
  $('iCgm').value     = iCgm;
  if (lErro) {

    $('ed20_i_codigo').value = '';
    $('iCgm').value          = '';
  }
}

/**
 * Prepara a aba Ausencia e Substituicao de acordo com acao a ser executada (Inclusao, Alteracao, Exclusao)
 */
function js_init() {

	switch(oGet.db_opcao) {
	  case '1':

				js_limpaDadosAbaAusencia();
		  break;
	  case '2':

	    js_pesquisaAusente();
	    break;
	  case '3':

		  $('tipoLancamento').setAttribute('disabled','disabled');
	    $('observacao').disabled              = true;
	    $('observacao').style.color           = "#000";
	    $('observacao').style.backgroundColor = "rgb(222, 184, 135)";
	    $('strLimite').style.display          = "none";

	    oBotaoAusencia.value = 'Excluir';
	    $('ctnButton').appendChild(oBotaoAusencia);

	    js_pesquisaAusente();

	    break;
	}
}

function js_pesquisaAusente() {

  var sUrl = "func_docenteausencia.php?funcao_js=parent.js_carregaDadosAusencia|ed321_sequencial";
  js_OpenJanelaIframe('', 'db_iframe_tipoausencia', sUrl, "Pesquisa Ausências", true);
}

function js_limpaDadosAbaAusencia() {

  $('ed321_sequencial').value = '';
  $('ed20_i_codigo').value    = '';
  $('z01_nome').value         = '';
  $('ed321_inicio').value     = '';
  $('ed321_final').value      = '';
  $('observacao').value       = '';
  $('tipoLancamento').value   = '';
}


function js_carregaTipoLAncamento () {

  var oParametro  = new Object();
  oParametro.exec = 'pesquisaTiposAusencia';
  js_divCarregando("Aguarde, buscando tipos de lançamento.", "msgBox");

  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoTiposLancamento
                               }
                              );

}

function js_retornoTiposLancamento(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval('('+oAjax.responseText+')');

	if (oRetorno.status == 2) {

	  alert(oRetorno.message.urlDecode());
	  return false;
	}

	oRetorno.dados.each(function (oLancamento, iSeq) {

	  var oOption       = document.createElement('option');
	  oOption.value     = oLancamento.codigo;
	  oOption.innerHTML = oLancamento.descricao.urlDecode();

	  $('tipoLancamento').appendChild(oOption);
    if (oRetorno.dados.length == 1) {
      $('tipoLancamento').value = oLancamento.codigo;
    }
  });
}

/**
 * Carrega os dados da Ausencia para modo de alteracao
 */
function js_carregaDadosAusencia(iCodigoAusencia) {


  db_iframe_tipoausencia.hide();
	if (iCodigoAusencia == '') {

		alert ('Nenhuma ausência lançada.');
		return false;
	}

  var oParametro     = new Object();
  oParametro.exec    = 'carregaDadosAusencia';
  oParametro.iCodigo = iCodigoAusencia;
  js_divCarregando("Aguarde, Carregando dados da Ausência!.", "msgBox");

  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoDadosAusencia
                               }
                              );

}

function js_retornoDadosAusencia(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval('('+oAjax.responseText+')');
	if (oRetorno.status == 2) {

	  alert(oRetorno.message());
	  return false;
	}
	$dtFinal = oRetorno.oAusencia.dtFinal;

	$('ed321_sequencial').value = oRetorno.oAusencia.iCodigo;
	$('ed20_i_codigo').value    = oRetorno.oAusencia.iRecHumano;
	$('z01_nome').value         = oRetorno.oAusencia.sNome.urlDecode();
	$('iCgm').value             = oRetorno.oAusencia.iCgm;
	$('ed321_inicio').value     = oRetorno.oAusencia.dtInicio.urlDecode();
	$('observacao').value       = oRetorno.oAusencia.sObservacao.urlDecode();

	if ($dtFinal != null) {
		$('ed321_final').value = oRetorno.oAusencia.dtFinal.urlDecode();
	}

	$('tipoLancamento').value = oRetorno.oAusencia.iAusencia

	if (oGet.db_opcao == '2') {

	  oAbaSubstituto.lBloqueada = false;
	  js_carregaAbaSubstituto();
	}
}

/**
 * Controla o numero maximo de caracteres de um textarea
 */
$('observacao').observe('keyup', function () {

  var iLimiteMax        = 300;
	var iCaracterDigitado = new Number($F('observacao').length);

  if (iCaracterDigitado.valueOf() <= iLimiteMax) {
    $('limiteCaracteres').innerHTML = iLimiteMax - iCaracterDigitado.valueOf();
  }

	if (iCaracterDigitado.valueOf() >= iLimiteMax) {

	  $('observacao').value = $F('observacao').substr(0, iLimiteMax);
		return false;
  }
});


$('action').observe('click', function () {

	switch(oGet.db_opcao) {

	  case '1':
	  case '2':

	    js_salvarDadosAusencia();
	    break;
	  case '3':

	    js_excluirDadosAusencia();
		   break;
	}

});

function js_validaCamposAusencia() {

  if ($F('ed20_i_codigo') == '') {

    alert('Você deve selecionar o Regente.');
    $('ed20_i_codigo').focus();
    return false;
  }

  if ($('tipoLancamento').value == '') {

    alert('Você deve selecionar o Tipo de Ausência.');
    return false;
  }

  if ($F('ed321_inicio') == '') {

    alert('Deve ser informada a data inicial.');
    $('ed321_inicio').focus();
    return false;
  }

  if ($F('ed321_final') != '' && js_comparadata($F('ed321_final'), $F('ed321_inicio'), '<')) {

		alert('Data final esta menor que a data inicial.');
		return false;
  }

  return true;
}

/** **************************
 *  Salva dados da Ausencia
 */
function js_salvarDadosAusencia() {

  if (!js_validaCamposAusencia()) {
    return false;
  }

  var oParametro         = new Object();
  oParametro.exec        = 'salvarDadosAusencia';
  oParametro.iCodigo     = $F('ed321_sequencial');
  oParametro.iRecHumano  = $F('ed20_i_codigo');
  oParametro.iAusencia   = $('tipoLancamento').value;
  oParametro.dtInicio    = $F('ed321_inicio');
  oParametro.dtFinal     = $F('ed321_final');
  oParametro.sObservacao = encodeURIComponent(tagString($F('observacao')));

  js_divCarregando("Aguarde, Carregando dados da Ausência!.", "msgBox");

  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoSalvarDados
                               }
                              );
}

function js_retornoSalvarDados(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == '2') {

		return false;
  }

  if (oRetorno.status == '1') {

    $('ctnRegente').innerHTML                = 'Regente';
    $('ed20_i_codigo').style.backgroundColor = '#DEB887';
  	$('ed321_sequencial').value              = oRetorno.iCodigo;
  	oAbaAusencia.setVisibilidade(false);
  	oAbaSubstituto.setVisibilidade(true);

  	oAbaSubstituto.lBloqueada = false;
  	js_carregaAbaSubstituto();
  }

}

/** *****************
 *  Exclui Ausencia
 */
function js_excluirDadosAusencia() {

  var oParametro         = new Object();
  oParametro.exec        = 'excluirDadosAusencia';
  oParametro.iCodigo     = $F('ed321_sequencial');

  js_divCarregando("Aguarde, Excluindo dados da Ausência!.", "msgBox");

  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoExcluindoAusencia
                               }
                              );
}

function js_retornoExcluindoAusencia(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());

  js_limpaDadosAbaAusencia();
  js_pesquisaAusente();
}




var oCboTurmas         = document.createElement('select');
oCboTurmas.id          = 'turmas';
oCboTurmas.style.width = "100%";
oCboTurmas.setAttribute("onchange", "js_buscaDisciplinas()");
$('ctnTurmas').appendChild(oCboTurmas);

var oCboDisciplina         = document.createElement('select');
oCboDisciplina.id          = 'disciplina';
oCboDisciplina.style.width = '100%';
$('ctnDisciplina').appendChild(oCboDisciplina);

var oCboTipoVinculo         = document.createElement('select');
oCboTipoVinculo.id          = 'tipoVinculo';
oCboTipoVinculo.style.width = "100%";
$('ctnTipoVinculo').appendChild(oCboTipoVinculo);


var oGridSubstituto          = new DBGrid('gridSubstituto');
oGridSubstituto.nameInstance = 'oGridSubstituto';

var aHeadersGrid           = new Array("Nome", "Turma", "Disciplina", "Início", "Fim", "Tipo", "Ação", "RecHumano", "Regencia", "Codigo Tipo");
var aCellWidthGrid         = new Array("30%", "13%", "22%", "9%", "9%", "10%", "7%" );
var aCellAlign             = new Array("left", "left", "left", "center", "center", "center", "center");

oGridSubstituto.setCellWidth(aCellWidthGrid);
oGridSubstituto.setCellAlign(aCellAlign);
oGridSubstituto.setHeader(aHeadersGrid);
oGridSubstituto.setHeight(130);

oGridSubstituto.aHeaders[7].lDisplayed = false;
oGridSubstituto.aHeaders[8].lDisplayed = false;
oGridSubstituto.aHeaders[9].lDisplayed = false;

oGridSubstituto.show($('ctnGridSusbstitutos'));


/**
 * Carrega os dados da ABA Substitutos.
 */
function js_carregaAbaSubstituto() {

  if (oGet.db_opcao != 3) {

    js_carregaTurmas();
    js_carregaTipoSubstituicao();
    js_carregaDadosGrid();
  }
}

function js_carregaTurmas() {

  var oParametro         = new Object();
  oParametro.exec        = 'turmasDocente';
  oParametro.iRecHumano  = $F('ed20_i_codigo');

  js_divCarregando("Aguarde, Buscando turmas do Docente " + $F('z01_nome') + "!.", "msgBox");

  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete = js_retornoTurmas;

  var oAjax = new Ajax.Request(sUrlRPC, oConfig);
}

function js_retornoTurmas(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('(' + oAjax.responseText+ ')');

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
		return false;
  }

  oCboTurmas.innerHTML = "";

  var oOptionSelecione       = document.createElement('option');
  oOptionSelecione.value     = '';
  oOptionSelecione.innerHTML = "Selecione";
  oCboTurmas.appendChild(oOptionSelecione);

  oRetorno.dados.each(function (oTurmas, iSeq) {

    var oOption       = document.createElement('option');
    oOption.value     = oTurmas.codigo;
    oOption.innerHTML = oTurmas.descricao.urlDecode();

    oCboTurmas.appendChild(oOption);

    if (oRetorno.dados.length == 1) {

      oCboTurmas.value = oTurmas.codigo;
			js_buscaDisciplinas();
    }
  });
}


function js_buscaDisciplinas() {

  //Limpa campos do professor substituto.
  $('iSubstituto').value     = '';
  $('sNomeSubstituto').value = '';
  $('iCgmSubstituto').value  = '';

  if (oCboTurmas.value == '') {

		alert("Selecione uma Turma.");
		return false;
  }

  js_divCarregando("Aguarde, Buscando disciplina da Turma: " + $('turmas').value + ".", "msgBox");

  var oParametro        = new Object();
  oParametro.exec       = "disciplinasTurma";
  oParametro.iTurma     = oCboTurmas.value;
  oParametro.iRecHumano = $F('ed20_i_codigo');
  oParametro.iCodigo    = $F('ed321_sequencial');

  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete = js_retornoDiscplinas;

  var oAjax = new Ajax.Request (sUrlRPC, oConfig);
}

function js_retornoDiscplinas(oAjax) {

	if ($('msgBox')) {
	  js_removeObj('msgBox');
	}

  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.status == 2) {

		alert(oRetorno.message.urlDecode());
		return false;
  }

  oCboDisciplina.innerHTML = '';

  var oOptionSelecione       = document.createElement('option');
  oOptionSelecione.value     = '';
  oOptionSelecione.innerHTML = "Selecione";
  oCboDisciplina.appendChild(oOptionSelecione);

  oRetorno.dados.each(function (oDisciplina, iSeq) {

    var oOption       = document.createElement('option');
    oOption.value     = oDisciplina.regencia;
    oOption.innerHTML = oDisciplina.descricao.urlDecode();

    oCboDisciplina.appendChild(oOption);
	  if (oRetorno.dados.length == 1) {
	    oCboDisciplina.value = oDisciplina.regencia;
	  }
  });

}

/**
 * Carrega os tipos de Substituicoes
 */
function js_carregaTipoSubstituicao() {


  var oParametro     = new Object();
  oParametro.exec    = "buscaTipoVinculo";

  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete = js_retornoTipoSubstituicao;

  var oAjax = new Ajax.Request (sUrlRPC, oConfig);
}

function js_retornoTipoSubstituicao (oAjax) {

  if ($('msgBox')) {
	  js_removeObj('msgBox');
	}
  var oRetorno = eval('(' + oAjax.responseText + ')');

  oCboTipoVinculo.innerHTML = "";
  oRetorno.dados.each( function (oTipo, iSeq) {

    var oOption = document.createElement('option');
    oOption.value = oTipo.codigo
    oOption.innerHTML = oTipo.descricao.urlDecode();

    oCboTipoVinculo.appendChild(oOption);
  });

  oCboTipoVinculo.value = 1;
}


/**
 * Busca o Regente Substituto.
 * Regente substituto tem que ter disponibilidade de horarios nos dias e períodos em que a regencia original é oferecida
 *
 */
function js_buscaDocenteSubstituto(lMostra) {

  if ($F('turmas') == '') {

		alert('Selecione uma Turma e após uma disciplina.');
		return false;
  }

  if ($F('disciplina') == '') {

    alert('Selecione uma disciplina para poder selecionar um regente.');
		return false;
  }

  var sUrl  = "func_rechumanohorariodisponivel.php";
			sUrl += "?turma="+$F('turmas');
			sUrl += "&regencia="+$F('disciplina');
			sUrl += "&regente="+$F('ed20_i_codigo');


  if (lMostra) {

    sUrl += "&funcao_js=parent.js_docenteSubstituto|ed20_i_codigo|z01_nome|z01_numcgm";
    js_OpenJanelaIframe('', 'db_iframe_rechumano', sUrl, 'Pesquisa Regente', true);
  }
}

/**
 * Retorno de js_buscaDocenteSubstituto
 */
function js_docenteSubstituto(iCodigo, sNome, iCgm) {

  $('iSubstituto').value      = iCodigo;
  $('sNomeSubstituto').value  = sNome;
  $('iCgmSubstituto').value   = iCgm;
  db_iframe_rechumano.hide();
}


/**
 * Busca os docentes que estao substituindo o regente ausente
 */
function js_carregaDadosGrid() {

  var oParametro     = new Object();
  oParametro.exec    = "buscaDocentesSubstitutos";
  oParametro.iCodigo = $F('ed321_sequencial');

  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete = js_retornoDocenteSubstituito;


  js_divCarregando("Aguarde, Buscando regentes substitutos do professor: " + $F('z01_nome') + "!.", "msgBox");

  var oAjax = new Ajax.Request (sUrlRPC, oConfig);

}

/**
 * Preenche a grid com os Regentes que estao substituindo o Regente ausente
 */
function js_retornoDocenteSubstituito (oAjax) {

  if ($('msgBox')) {
	  js_removeObj('msgBox');
	}
  var oRetorno = eval('(' + oAjax.responseText + ')');

  oGridSubstituto.clearAll(true);

  if (oRetorno.dados.length > 0) {

    oRetorno.dados.each( function (oSubstituto, iSeq) {

      var sHtml  = "<input type='button' id='alterar' name='alterar' value = 'A' ";
          sHtml += "onclick = 'js_alterarSubstituto("+oSubstituto.iSubstituto+")' />";
          sHtml += " ";
          sHtml += "<input type='button' id='alterar' name='alterar' value = 'E' ";
          sHtml += "onclick = 'js_excluirSubstituto("+oSubstituto.iSubstituto+", \""+oSubstituto.sNome.urlDecode()+"\")' />";

      var aLinha = new Array();
      aLinha[0]  = oSubstituto.sNome.urlDecode();
      aLinha[1]  = oSubstituto.sTurma.urlDecode();
      aLinha[2]  = oSubstituto.sRegencia.urlDecode();
      aLinha[3]  = oSubstituto.dtInicio.urlDecode();
      aLinha[4]  = oSubstituto.dtFinal.urlDecode();
      aLinha[5]  = oSubstituto.sTipo.urlDecode();
      aLinha[6]  = sHtml;
      aLinha[7]  = oSubstituto.iRecHumano;
      aLinha[8]  = oSubstituto.iRegencia;
      aLinha[9]  = oSubstituto.iTipo;

      oGridSubstituto.addRow(aLinha);
    });
    oGridSubstituto.renderRows();
  }

}

/**
 * Busca os dados do professor substituto a ser alterado e carrega no formulário
 */
function js_alterarSubstituto(iCodigoSubstituto) {

  var oParametro         = new Object();
  oParametro.exec        = "carregaDadosDocenteSubstituto";
  oParametro.iSubstituto = iCodigoSubstituto;


  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.onComplete = js_retornoDadosSubstituto;

	js_divCarregando("Aguarde, Vinculando substituto!.", "msgBox");

  var oAjax = new Ajax.Request (sUrlRPC, oConfig);

}

function js_retornoDadosSubstituto(oAjax) {

  js_removeObj('msgBox');
	var oRetorno = eval('('+oAjax.responseText+')');

	if (oRetorno.status == 2) {

	  alert(oRetorno.message.urlDecode());
	  return false;
	}

	var oOption       = document.createElement('option');
	oOption.value     = oRetorno.iRegencia;
  oOption.innerHTML = oRetorno.sRegencia.urlDecode();

	oCboTurmas.value                 = oRetorno.iTurma;

	$('ed322_sequencial').value      = oRetorno.iSubstituto;
	$('iSubstituto').value           = oRetorno.iRecHumanoSubstituto;
	$('iCgmSubstituto').value        = oRetorno.iCgmSubstituto;
	$('sNomeSubstituto').value       = oRetorno.sNome.urlDecode();

	oCboDisciplina.innerHTML         = '';
	oCboDisciplina.appendChild(oOption);
	oCboTipoVinculo.value            = oRetorno.iTipoVinculo;

	$('iSubstituto').style.backgroundColor = '#DEB887';
	$('iSubstituto').setAttribute('readonly', 'readonly');
	$('docenteSubstituto').innerHTML = "Regente Substituto";

	oCboTurmas.setAttribute("disabled", "disabled");
	oCboDisciplina.setAttribute("disabled", "disabled");
	oCboTipoVinculo.setAttribute("disabled", "disabled");

	$('ed322_periodoinicial').value = oRetorno.dtInicial.urlDecode();
	$('ed322_periodofinal').value   = '';

	if (oRetorno.dtFinal.urlDecode() != '') {
		$('ed322_periodofinal').value   = oRetorno.dtFinal.urlDecode();
	}

}

function js_excluirSubstituto(iCodigoSubstituto, sNome) {

	if (!confirm('Deseja realmente excluir substituição do regente substituto '+ sNome+ '?')) {
		return false;
	}

  var oParametro         = new Object();
  oParametro.exec        = "removerVinculoDocenteSubstituto";
  oParametro.iAusente    = $F('ed321_sequencial');
  oParametro.iSubstituto = iCodigoSubstituto;

  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.onComplete = js_retornoExcluirSubstituto;

	js_divCarregando("Aguarde, Removendo dados da substituição!.", "msgBox");

  var oAjax = new Ajax.Request (sUrlRPC, oConfig);

}

function js_retornoExcluirSubstituto(oAjax) {

  js_removeObj('msgBox');
	var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());

	if (oRetorno.status == '1') {

    js_limpaAbaSubstituto();
  }
}

/**
 * Vincula / altera os dados de um professor substituto
 */
function js_vincularSubstituto() {

  if ($F('disciplina') == '') {

		alert('Selecione uma disciplina.');
		return false;
  }

  if ($F('iSubstituto') == '') {

    alert('Selecione um regente para substituir o regente: ' +$F('z01_nome')+ '.');
		return false;
  }

  if ($F('ed322_periodoinicial') == '') {

    alert('Informe o período inicial da substituição.');
		return false;
  }

  if (js_comparadata ($F('ed322_periodoinicial'), $F('ed321_inicio'), '<')) {

    var sMsg  = 'Período inicial é menor do que a data de inicio ' + $F('ed321_inicio');
        sMsg += ' da ausência lançada. ';
    alert(sMsg);
		return false;
  }

  if ($F('ed322_periodofinal') != '') {

    if (js_comparadata ($F('ed322_periodoinicial'),$F('ed322_periodofinal'), '>')) {

      alert('Período final da substituicao não pode ser menor que período inicial.');
      return false;
    }
  }

  var oParametro                  = new Object();
  oParametro.exec                 = "vincularDocenteSubstituto";
  oParametro.iAusente             = $F('ed321_sequencial');
  oParametro.iSubstituto          = $F('ed322_sequencial');
  oParametro.iRecHumanoSubstituto = $F('iSubstituto');
  oParametro.iCgmSubstituto       = $F('iCgmSubstituto');
  oParametro.iRegencia            = oCboDisciplina.value;
  oParametro.iTipoVinculo         = oCboTipoVinculo.value;
  oParametro.dtInicial            = $F('ed322_periodoinicial');
  oParametro.dtFinal              = $F('ed322_periodofinal');

  var oConfig        = new Object();
  oConfig.method     = 'post';
  oConfig.parameters = 'json='+Object.toJSON(oParametro);
  oConfig.asynchronous = true;
  oConfig.onComplete = js_retornoVincularDocente;

	js_divCarregando("Aguarde, Vinculando substituto!.", "msgBox");

  var oAjax = new Ajax.Request (sUrlRPC, oConfig);

}

function js_retornoVincularDocente(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

    js_limpaAbaSubstituto();
  }
}

/**
 * Depois de realizado uma operacao de inclusao / alterarcao é limpado todos
 */
function js_limpaAbaSubstituto() {

	var sAncora  = "<a class='dbancora' onclick='js_buscaDocenteSubstituto(true);' style='text-decoration:underline;' ";
	    sAncora += " href='#'>Regente Substituto:</a>";

	$('docenteSubstituto').innerHTML = sAncora;

	oCboTurmas.removeAttribute("disabled");
	oCboDisciplina.removeAttribute("disabled");
	oCboTipoVinculo.removeAttribute("disabled");

  $('ed322_sequencial').value     = '';
  $('iSubstituto').value          = '';
  $('iCgmSubstituto').value       = '';
  $('sNomeSubstituto').value      = '';
  $('ed322_periodoinicial').value = '';
  $('ed322_periodofinal').value   = '';
  oCboDisciplina.innerHTML        = '';

  js_carregaAbaSubstituto();
}

/**
 * Cria abas
 */
var oDBAba         = new DBAbas($('ctnAbas'));
var oAbaAusencia   = oDBAba.adicionarAba("Ausências e Substituições", $('ctnAbaAusencia'));
var oAbaSubstituto = oDBAba.adicionarAba("Lançar Substituição", $('ctnAbaSubstituto'));
oAbaSubstituto.lBloqueada = true;

js_carregaTipoLAncamento();
js_init();
</script>