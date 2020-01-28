<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oDaoMatEstoqueIni = db_utils::getDao("matestoqueini");

$oRotulo = new rotulo("matestoqueitem");
$oRotulo->label();
$oRotuloMatEstoqueIni = new rotulo("matestoqueini");
$oRotuloMatEstoqueIni->label();



$iInstituicaoSessao = db_getsession("DB_instit");
$oGet  = db_utils::postMemory($_GET);

$iCodigoLancamento = 0;
if (isset($oGet->m80_codigo) && !empty($oGet->m80_codigo)) {
  $iCodigoLancamento = $oGet->m80_codigo;
}
$iCodigoItemEstoque = 0;
if (isset($oGet->m71_codlanc) && !empty($oGet->m71_codlanc)) {
  $iCodigoItemEstoque = $oGet->m71_codlanc;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 30px;">
    <center>
      <form name="form1" id="form1">
        <fieldset style="width: 550px;">
          <legend><b>Dados do Material</b></legend>
          <table>
            <tr>
              <td><b>Código do Lançamento:</b></td>
              <td>
                <?php
                  db_input("iCodigoLancamento", 8, true, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Material:</b>
              </td>
              <td>
                <?php
                  db_input("iCodigoMaterial", 8, "1", true);
                  db_input("sDescricaoMaterial", 30, "2", true);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Tipo Movimentação:</b>
              </td>
              <td>
                <?php
                  db_input("iTipoMovimentacao", 8, "1", true);
                  db_input("sTipoMovimentacao", 30, "2", true);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Depósito:</b>
              </td>
              <td>
                <?php
                  db_input("iCodigoDeposito", 8, "1", true);
                  db_input("sDescricaoDeposito", 30, "2", true);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Data:</b>
              </td>
              <td>
                <?php
                  db_inputdata("dtDataMovimentacao", "", "", "", true, "text", 1);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Horário:</b>
              </td>
              <td>
                <?php
                  db_input("sHoraMovimentacao", 10, $Im80_hora, true, "text", 1, null, null, null, null, 8);
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend><b>Entradas</b></legend>
                  <b>Quantidade:</b>
                  <?php
                    db_input("iQuantidadeEntrada", 10, $Im71_quant, false, "text", 1, "onchange='js_multiplicaValorEntrada();'");
                  ?>
                  <b>Vlr. Unitário:</b>
                  <?php
                    db_input("nValorUnitarioEntrada", 10, $Im71_valor, false, "text", 1, "onchange='js_multiplicaValorEntrada();'");
                  ?>
                  <b>Total:</b>
                  <?php
                    db_input("nTotalEntrada", 10, "1", true);
                  ?>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend><b>Saídas</b></legend>
                  <b>Quantidade:</b>
                  <?php
                    db_input("iQuantidadeSaida", 10, $Im71_quant, false, "text", 1, "onchange='js_multiplicaValorSaida();'");
                  ?>
                  <b>Vlr. Unitário:</b>
                  <?php
                    db_input("nValorUnitarioSaida", 10, $Im71_valor, true);
                  ?>
                  <b>Total:</b>
                  <?php
                    db_input("nTotalSaida", 10, "1", true);
                  ?>
                </fieldset>
              </td>
            </tr>
          </table>
          <?php
            db_input("iTipoMovimento", 10, true, true, 'hidden', 3);
          ?>
        </fieldset>
        <p align="center">
          <input style="margin-top: 10px;" type="button" name="btnAtualizar" id="btnAtualizar" value="Atualizar" />
          &nbsp;
          <input style="margin-top: 10px;" type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" />
        </p>
          <?php

            $sCamposQuery  = " m80_codigo,    ";
            $sCamposQuery .= " m80_coddepto,  ";
            $sCamposQuery .= " m81_descr,     ";
            $sCamposQuery .= " m81_entrada,   ";
            $sCamposQuery .= " m82_quant,     ";
            $sCamposQuery .= " m89_precomedio as preco_medio, ";
            $sCamposQuery .= " m89_valorunitario, ";
            $sCamposQuery .= " deptousu.descrdepto,    ";
            $sCamposQuery .= " m80_data,      ";
            $sCamposQuery .= " m80_hora,      ";
            $sCamposQuery .= " nome,          ";
            $sCamposQuery .= " login,         ";
            $sCamposQuery .= " m60_descr,     ";
            $sCamposQuery .= " m89_precomedio, ";
            $sCamposQuery .= " m89_valorunitario, ";
            $sCamposQuery .= " round(m89_precomedio*m82_quant, 2) as total, ";
            $sCamposQuery .= " m71_quant,     ";
            $sCamposQuery .= " m71_codlanc, ";
            $sCamposQuery .= " m81_codtipo,   ";
            $sCamposQuery .= " m81_tipo       ";

            $sOrderQuery   = "to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'), m80_codigo, m82_codigo";
            $sWhereQuery   = "    deptoest.instit = {$iInstituicaoSessao} ";
            $sWhereQuery  .= " and m70_codmatmater = {$oGet->iCodigoMaterial} ";

            $sSqlMovimentacao = $oDaoMatEstoqueIni->sql_query_movimentacoes_gerais(null, $sCamposQuery, $sOrderQuery, $sWhereQuery);

            $sCamposGrid = "m80_data, m80_hora, m80_codigo, m81_descr, descrdepto,m89_valorunitario, m89_precomedio , m82_quant, total";

            $oIframeAlteraExclui                = new cl_iframe_alterar_excluir;
            $oIframeAlteraExclui->chavepri      = array("m80_codigo" => 1, "m71_codlanc" => 1);
            $oIframeAlteraExclui->sql           = $sSqlMovimentacao;
            $oIframeAlteraExclui->campos        = $sCamposGrid;
            $oIframeAlteraExclui->legenda       = "Movimentações";
            $oIframeAlteraExclui->iframe_height = "300";
            $oIframeAlteraExclui->iframe_width  = "100%";
            $oIframeAlteraExclui->opcoes        = 2;
            $oIframeAlteraExclui->iframe_alterar_excluir(2);
          ?>
      </form>
    </center>
  </body>
</html>

<script type="text/javascript">

var iCodigoLancamento  = <?php echo $iCodigoLancamento;?>;
var iCodigoItemEstoque = <?php echo $iCodigoItemEstoque;?>;

$('btnAtualizar').observe('click', function() {

	if (!confirm("Confirma a as alterações da movimentação do material?")) {
		return false;
	}

	var oParam                   = new Object();
	oParam.exec                  = "salvarDadosMovimentacao";
	oParam.iCodigoLancamento     = $F('iCodigoLancamento');
	oParam.iCodigoItemEstoque    = iCodigoItemEstoque;
	oParam.iCodigoMaterial       = $F('iCodigoMaterial');
	oParam.iTipoMovimentacao     = $F('iTipoMovimentacao');
	oParam.dtMovimentacao        = $F('dtDataMovimentacao');
	oParam.sHoraMovimentacao     = $F('sHoraMovimentacao');
	oParam.iQuantidadeEntrada    = $F('iQuantidadeEntrada');
	oParam.nValorUnitarioEntrada = $F('nValorUnitarioEntrada');
	oParam.nTotalEntrada         = $F('nTotalEntrada');
	oParam.iQuantidadeSaida      = $F('iQuantidadeSaida');
	oParam.nPrecoMedioSaida      = $F('nValorUnitarioSaida');
	oParam.nTotalSaida           = $F('nTotalSaida');
	oParam.iTipoMovimento        = $F('iTipoMovimento');

	js_divCarregando("Aguarde, salvando informações...", "msgBox");
	var oAjax = new Ajax.Request("mat4_manutencaoestoque.RPC.php",
                              {method: 'post',
                               parameters: 'json='+Object.toJSON(oParam),
                               onComplete: js_finalizaSalvar
                              });
});

function js_finalizaSalvar(oAjax) {

	js_removeObj("msgBox");
	var oRetorno = eval("("+oAjax.responseText+")");
	alert(oRetorno.message.urlDecode());
	if (oRetorno.status == 1) {

		$('iCodigoLancamento').value     = "";
		$('iTipoMovimentacao').value     = "";
		$('sTipoMovimentacao').value     = "";
		$('iCodigoDeposito').value       = "";
		$('sDescricaoDeposito').value    = "";
		$('dtDataMovimentacao').value    = "";
		$('sHoraMovimentacao').value     = "";
		$('iQuantidadeEntrada').value    = "";
		$('nValorUnitarioEntrada').value = "";
		$('nTotalEntrada').value         = "";
		$('iQuantidadeSaida').value      = "";
		$('nValorUnitarioSaida').value   = "";
		$('nTotalSaida').value           = "";
		$('iTipoMovimento').value        = "";

	  location.href = 'mat4_manutencaomaterial002.php?iCodigoMaterial='+$F('iCodigoMaterial')+'&sDescricaoMaterial='+$F('sDescricaoMaterial');
	}

}

function js_init() {

	if (iCodigoLancamento == 0 || iCodigoLancamento == "") {
		return false;
	}

	js_divCarregando("Aguarde, carregando informações...", "msgBox");
	var oParam                = new Object();
	oParam.exec               = "buscarDadosLancamento";
	oParam.iCodigoLancamento  = iCodigoLancamento;
	oParam.iCodigoItemEstoque = iCodigoItemEstoque;
	oParam.iCodigoMaterial    = <?php echo $oGet->iCodigoMaterial; ?>;

	var oAjax = new Ajax.Request("mat4_manutencaoestoque.RPC.php",
	                            {method: 'post',
                               parameters: 'json='+Object.toJSON(oParam),
                               onComplete: js_carregaFormulario
                              });
}


/**
 * Preenchemos o formulário com os dados da linha que o usuario selecionou
 */
function js_carregaFormulario(oAjax) {

	js_removeObj("msgBox");
	var oRetorno = eval("("+oAjax.responseText+")");

	$('iCodigoLancamento').value     = oRetorno.iCodigoMovimento;
  $('iCodigoMaterial').value       = oRetorno.iCodigoMaterial;
  $('sDescricaoMaterial').value    = oRetorno.sDescricaoMaterial.urlDecode();
  $('iTipoMovimentacao').value     = oRetorno.iCodigoTipoMovimentacao;
  $('sTipoMovimentacao').value     = oRetorno.sDescricaoTipoMovimentacao.urlDecode();
  $('iCodigoDeposito').value       = oRetorno.iCodigoDepartamento;
  $('sDescricaoDeposito').value    = oRetorno.sDescricaoDepartamento.urlDecode();
	$('dtDataMovimentacao').value    = js_formatar(oRetorno.dtMovimentacao, "d");
	$('sHoraMovimentacao').value     = oRetorno.sHoraMovimentacao;
	$('iQuantidadeEntrada').value    = oRetorno.oDadosEntrada.iQuantidade;
	$('nValorUnitarioEntrada').value = oRetorno.oDadosEntrada.nValor;
	$('nTotalEntrada').value         = oRetorno.oDadosEntrada.nTotal;
	$('iQuantidadeSaida').value      = oRetorno.oDadosSaida.iQuantidade;
	$('nValorUnitarioSaida').value   = oRetorno.oDadosSaida.nValor;
	$('nTotalSaida').value           = oRetorno.oDadosSaida.nTotal;
	$('iTipoMovimento').value        = oRetorno.iTipoMovimento;

	/**
	 * Definimos o que vai ser bloqueado
	 */
	if (oRetorno.iTipoMovimento == 1) {
		js_bloqueiaSaida();
	} else {
		js_bloqueiaEntrada();
	}

	$('btnAtualizar').disabled = false;
}


function js_bloqueiaEntrada() {
	/**
	 * Bloqueia os campos de entrada
	 */
	$("iQuantidadeEntrada").setAttribute("readonly", true);
	$("nValorUnitarioEntrada").setAttribute("readonly", true);

	/**
	 * Desbloqueia os campos de saída
	 */
	$("iQuantidadeSaida").removeAttribute("readonly");

	/**
	 * Pinta os campos de Entrada de laranja
	 */
	$("iQuantidadeEntrada").style.backgroundColor = "#DEB887";
	$("nValorUnitarioEntrada").style.backgroundColor = "#DEB887";

	/**
	 * Pinta os campos de Saída de branco
	 */
	$("iQuantidadeSaida").style.backgroundColor = "#FFFFFF";
}

function js_bloqueiaSaida() {
	/**
	 * Bloqueia os campos de saída
	 */
	$("iQuantidadeSaida").setAttribute("readonly", true);

	/**
	 * Desbloqueia os campos de entrada
	 */
	$("iQuantidadeEntrada").removeAttribute("readonly");
	$("nValorUnitarioEntrada").removeAttribute("readonly");

	/**
	 * Pinta os campos de saída de laranja
	 */
	$("iQuantidadeSaida").style.backgroundColor = "#DEB887";

	/**
	 * Pinta os campos de entrada de branco
	 */
	$("iQuantidadeEntrada").style.backgroundColor = "#FFFFFF";
	$("nValorUnitarioEntrada").style.backgroundColor = "#FFFFFF";
}


function js_multiplicaValorEntrada() {

	var nTotal = js_multiplicaQuantidadeValor($("iQuantidadeEntrada").value, $("nValorUnitarioEntrada").value);
	$("nTotalEntrada").value = nTotal;
}

function js_multiplicaValorSaida() {

	var nTotal = js_multiplicaQuantidadeValor($("iQuantidadeSaida").value, $("nValorUnitarioSaida").value);
	$("nTotalSaida").value = nTotal
}



/**
 * Funcao que multiplica uma quantidade pelo valor
 * @param iQuantidade
 * @param nValor
 * @return Number
 */
function js_multiplicaQuantidadeValor(iQuantidade, nValor) {
	return nValor * iQuantidade;
}


/**
 * Imprime o relatório do material corrente
 */
$("btnImprimir").observe('click', function () {

	var iCodigoMaterial = $("iCodigoMaterial").value;
	var dtInicial       = '1900-01-01';
  var sQueryString = 'codmater='+iCodigoMaterial+'&listatipo=&listadepto=&data='+dtInicial+'&data1=--&vertipo=&verdepto=';
  var janela = window.open('mat2_controlest002.php?'+sQueryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
});
 $('btnAtualizar').disabled = true;
js_init();
</script>