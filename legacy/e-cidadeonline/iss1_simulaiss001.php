<?
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

session_start();
require_once("libs/db_libsession.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once('classes/db_db_confmensagem_classe.php');
$comboRotas = new cl_arquivo_auxiliar();
$oDaoConfMensagem = new cl_db_confmensagem();

$rotulo = new rotulocampo();
$rotulo->label("z01_cgccpf");
$rotulo->label("z01_nome");
$rotulo->label("z01_email");
$rotulo->label("j14_codigo");
$rotulo->label("j14_nome");
$rotulo->label("z01_numero");
$rotulo->label("z01_compl");
$rotulo->label("j13_descr");
$rotulo->label("z01_telef");
$rotulo->label("z01_numcgm");
$rotulo->label("j50_zona");
$rotulo->label("j50_descr");
$rotulo->label("q03_ativ");
$rotulo->label("q03_descr");
$rotulo->label("q86_numcgm");

$sCampos            = 'mens, alinhamento';
$sWhereConfMensagem = "cod = 'simulacao_inscricao_cab' ";
$sSqlConfMensagem   = $oDaoConfMensagem->sql_query(null, $sCampos , null, $sWhereConfMensagem);
$rsConfMensagem     = $oDaoConfMensagem->sql_record($sSqlConfMensagem);
$oDadosConfMensagem = db_utils::fieldsMemory($rsConfMensagem, 0);
$sConfMensagem      = $oDadosConfMensagem->mens;
$sAlinhamento       = $oDadosConfMensagem->alinhamento;

db_logs(0,0,0,"Simulação de Inscrição ISS");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<script language="JavaScript" src="scripts/scripts.js"></script>
	<script language="JavaScript" src="scripts/strings.js"></script>
	<script language="JavaScript" src="scripts/prototype.js"></script>
	<script language="JavaScript" src="scripts/widgets/DBHint.widget.js"></script>
	<link rel="stylesheet" type="text/css" href="include/estilodai.css">
	<link rel="stylesheet" type="text/css" href="config/estilos.css">
	<style>
		body *{
			font-size: 11px
		}
		table {
			margin: 0 auto;
		}
		form table tr td:first-child {
		  width: 150px !important;

		}

		form fieldset {
			margin: 0 auto;
			width: 700px;
		}

		form fieldset fieldset {
		  width: 100%;
		}

		form input {
			border: 1px solid #000;
		}

		#Jandb_iframe_bairro 		 table,
		#Jandb_iframe_ruas 			 table,
		#Jandb_iframe_cadescrito table,
		#Jandb_iframe_ativid     table,
		#Jandb_iframe_zona       table{
		  width: 100%;
		  font-size: 10px;
		}
		#atividades {
		  border-collapse: collapse;
		}
		#atividades td {
		  border: 1px groove #CCC;
		  padding: 2px;
		}
		a {
		  color: #0000FF !important;
		  text-decoration: underline !important;
		}

		table#atividades input:text {
		  border: 0px solid #000 !important;
		}

    #btnLimparOutrosDados {
      position:absolute;
      margin-left:3px;
    }
	</style>
</head>
<body>
<form name="form1" id="form1">
<div id="mensagem">
<p>

</p>

</div>

<table border='0' align='<?php echo $sAlinhamento ?>' >
  <tr>
    <td nowrap="nowrap"><span><?php echo $sConfMensagem?></span> </td>
  </tr>
</table>
<br>
<fieldset>
	<legend><strong>Dados do Contribuinte</strong></legend>
	<table>
	  <tr>
	  	<td title="<?=$Tz01_cgccpf?>">
	  		<?=$Lz01_cgccpf?>
	  	</td>
	  	<td>
	  	  <?
	  	    db_input('z01_cgccpf', 20, $Iz01_cgccpf, true, 'text', 1, "onchange='verifica(this)'");
	  	  ?>
	  	</td>
	  </tr>

	  <tr>
	    <td title="<?=$Tz01_nome?>">
	    	<?=$Lz01_nome?>
	    </td>
	    <td>
	    	<?
	    		db_input('z01_nome', 54, $Iz01_nome, true, 'text', 1)
	    	?>
	    </td>
	  </tr>

	  <tr>
	    <td title="<?=$Tz01_email?>">
	    	<?=$Lz01_email?>
	    </td>
	    <td>
	    	<?
	    		db_input('z01_email', 54, $Iz01_email, true, 'text', 1, "onchange='js_validaEmail(this)'")
	    	?>
	    </td>
	  </tr>
	</table>
</fieldset>

<fieldset>
	<legend><strong>Endere&ccedil;o</strong></legend>
	<table>
	  <tr>
	  	<td title="<?=$Tj14_nome?>">
	  		<?
	  			db_ancora('Logradouro', 'js_pesquisaLogradouro()', 1);
	  		?>
	  	</td>
	  	<td>
	  	  <?
	  	    db_input('j14_codigo', 54, $Ij14_nome, true, 'hidden', 1);
	  	    db_input('j14_nome', 54, $Ij14_nome, true, 'text', 3);
	  	  ?>
	  	</td>
	  </tr>

	  <tr>
	    <td title="<?=$Tz01_numero?>">
	    	<?=$Lz01_numero?>
	    </td>
	    <td>
	    	<?
	    		db_input('z01_numero', 10, $Iz01_numero, true, 'text', 1)
	    	?>
	    </td>
	  </tr>

	  <tr>
	    <td title="<?=$Tz01_compl?>">
	    	<?=$Lz01_compl?>
	    </td>
	    <td>
	    	<?
	    		db_input('z01_compl', 54, $Iz01_compl, true, 'text', 1)
	    	?>
	    </td>
	  </tr>

	  <tr>
	  	<td title="<?=$Tj13_descr?>">
	  		<?
	  			db_ancora('<strong>Bairro:</strong>', 'js_pesquisaBairro()', 1);
	  		?>
	  	</td>
	  	<td>
	  	  <?
	  	    db_input('j13_codi', 54, $Ij13_descr, true, 'hidden', 1);
	  	    db_input('j13_descr', 54, $Ij13_descr, true, 'text', 3);
	  	  ?>
	  	</td>
	  </tr>

	  <tr>
	  	<td title="<?=$Tz01_telef?>">
	  		<strong><?=$Lz01_telef?></strong>
	  	</td>
	  	<td>
	  	  <?
	  	    db_input('z01_telef', 54, $Iz01_telef, true, 'text', 1);
	  	  ?>
	  	</td>
	  </tr>
	</table>
</fieldset>

<fieldset>
	<legend><strong>Outros Dados</strong></legend>
	<table>
	  <tr>
	  	<td title="<?=$Tq86_numcgm?>">
	  		<?
	  			db_ancora($Lq86_numcgm, 'js_pesquisaEscritorio()', 1);
	  		?>
	  	</td>
	  	<td style="position:relative;">
	  		<?
	  			db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'hidden', 1);
	  			db_input('z01_nome', 54, $Iz01_nome, true, 'text', 3, '', 'z01_nome_escritorio');
	  		?>
        <input type='button' value = 'Limpar' onclick='js_limparOutrosDados();' id='btnLimparOutrosDados' />
	  	</td>
	  </tr>

	  <tr>
	  	<td title="<?=$Tj50_descr?>">
	  		<?
	  			db_ancora('<strong>Zona</strong>', 'js_pesquisaZona()', 1)
	  		?>
	  	</td>
	  	<td>
	  		<?
	  			db_input('j50_zona', 10, $Ij50_zona, true, 'hidden', 1);
	  			db_input('j50_descr', 54, $Ij50_descr, true, 'text', 3);
	  		?>
	  	</td>
	  </tr>

	  <tr>
	  	<td title="Número de empregados">
	  		<strong>Número de Empregados:</strong>
	  	</td>
	  	<td>
	  		<input type				  = "text"
	  				   id					  = "numero_empregados"
	  				 	 name			    = "numero_empregados"
	  			     autocomplete = "off"
	  		       onkeydown    = "return js_controla_tecla_enter(this,event);"
	  					 onkeyup			= "js_ValidaCampos(this,1,'Número de Empregados','t','f',event);"
	  					 onblur       = "js_ValidaMaiusculo(this,'f',event);"
	  					 style        = "background-color:#E6E4F1"
	  					 maxlength    = "6"
	  		       size         = "10"
	  		       value        = ""
	  		       title        = "Número de empregados da empresa.">
	  	</td>
	  </tr>

	  <tr>
	  	<td title="Número de empregados">
	  		<strong>Área:</strong>
	  	</td>
	  	<td>
	  		<input type				  = "text"
	  				   id					  = "area"
	  				 	 name			    = "area"
	  			     autocomplete = "off"
	  		       onkeydown    = "return js_controla_tecla_enter(this,event);"
	  					 onkeyup			= "js_ValidaCampos(this,4,'Área','t','f',event);"
	  					 onblur       = "js_ValidaMaiusculo(this,'f',event);"
	  					 style        = "background-color:#E6E4F1"
	  					 maxlength    = "6"
	  		       size         = "10"
	  		       value        = ""
	  		       title        = "Área">
	  	</td>
	  </tr>

	  <tr>
	  	<td>
	  		<strong>Data Início</strong>
	  	</td>
	  	<td>
	  	  <?
	  	  	db_inputdata('data_inicio', @$data_inicio_dia, @$data_inicio_mes, @$data_inicio_ano, true, 'text', 1);
	  	  ?>
	  	</td>
	  </tr>
	</table>
</fieldset>

<fieldset>
	<legend><strong>Atividades:</strong></legend>
	<table>
	  <tr>
	  	<td title="Atividades">
	  		<?
	  			db_ancora('<strong>Atividade:</strong>', 'js_pesquisaAtividade()', 1);
	  		?>
	  	</td>
	  	<td nowrap="nowrap">
	  		<?
	  			db_input('q03_ativ' , 10, $Ij50_zona , true, 'hidden', 1);
	  			db_input('q03_descr', 44, $Ij50_descr, true, 'text', 3);
	  		?>
	  		<input type="button" value="Lançar" name="lancar" id="lancar" onclick="js_lancar()" />
	  	</td>
	  </tr>


	  <tr>
	  	<td colspan="2">
	  	  <fieldset id="fieldsetAtividades" style="display: none;">
	        <legend><strong>Atividades Cadastradas:</strong></legend>
				  <table id="atividades">
				  	<tr style="background-color: #EEE; border:2px outset #DDD" id="headerAtividades">
				  		<th width="15%" align="center" style="border:2px outset #DDD"><strong>Código    </strong></th>
				  		<th width="50%" align="center" style="border:2px outset #DDD"><strong>Atividade </strong></th>
				  		<th width="15%" align="center" style="border:2px outset #DDD"><strong>Principal </strong></th>
				  		<th width="15%" align="center" style="border:2px outset #DDD"><strong>Quantidade</strong></th>
				  		<th width="5%" align="center" style="border:2px outset #DDD"><strong>E</strong></th>
				  	</tr>
				  	<tbody id="corpoAtividades">
				  	</tbody>
				  </table>
				</fieldset>
	  	</td>
	  </tr>
	</table>
</fieldset>
<br/>
<center>
	<input type="button" name="simular" id="simular" value="Simular" onclick="js_simular()"/>
</center>

<script type="text/javascript">

var iAtividadePrincipal = null;
var aSelecionados       = new Array();
var sUrl                = "iss1_simulaiss.RPC.php";

/**
 * funcao para limpar os text do fieldset outros dados
 */
function js_limparOutrosDados() {

  $('z01_nome_escritorio') .value = '';
  $('z01_numcgm')          .value = '';
 }


/**
 * função para retornal nome e email do copf cgc digitado.
 */
function js_getDadosCpf(){


   var z01_cgccpf             = $F('z01_cgccpf');
   var oParametros            = new Object();
   oParametros.sExec          = 'getNome';
   oParametros.z01_cgccpf     = z01_cgccpf;

   var oAjaxLista  = new Ajax.Request(sUrl,
                                      {method: "post",
                                       parameters:'json='+Object.toJSON(oParametros),
                                       onComplete: js_retornoGetDados
                                      });


}

function js_retornoGetDados(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");

   if (oRetorno.iStatus == 1) {

     oRetorno.aDados.each(
         function (oDado, iInd) {

              $('z01_nome').value = oDado.z01_nome.urlDecode();
              $('z01_email').value = oDado.z01_email;

            });

   } else {

     var confirmacao = confirm(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));

     if(confirmacao == false) {

       $('z01_cgccpf').value = '';
       $('z01_nome').value = '';
       return false;
     }
   }
}

function verifica(oObj) {
  
  var retorno = js_verificaCGCCPF(oObj);
  if(!retorno) {
    document.form1.z01_cgccpf.value = '';
    document.form1.z01_cgccpf.focus();
    return;
  } else {

    js_getDadosCpf();
  }

}

function js_validaEmail(oEmail){

	var ER = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);

	if(typeof(oEmail) == "object"){
		if(ER.test(oEmail.value)){
		  return true;
		} else {
		  alert('E-mail informado é invalido.')
		  oEmail.value = '';
			return false;
		}
	}
}

function js_defineAtividadePrincipal(iCodigoAtividade){
  iAtividadePrincipal = iCodigoAtividade;
}

function js_simular() {

  if (aSelecionados.length == 0) {
    alert('Você deve selecionar ao menos uma atividade para simular o cálculo.');
    return false;
  }

  if ( iAtividadePrincipal == null) {
    alert('Sem atividade principal selecionada.');
    return false;
  }

  var aAtividades = new Array();
  $$('#atividades tr').each( function(oLinha, iSeqAtividade){

    if (oLinha.id != "headerAtividades") {

      var iAtividade = oLinha.cells[0].firstChild.value;
      var oAtividade = new Object();
      oAtividade.iCodigoAtividade = oLinha.cells[0].firstChild.value;
      oAtividade.iQuantidade      = oLinha.cells[3].firstChild.value;

      oAtividade.lPrincipal       = iAtividade == iAtividadePrincipal ? true : false;
      aAtividades.push(oAtividade);

    }
  });

	oParam = new Object();

	oParam.iCpfCpnj            = $F('z01_cgccpf');
	oParam.sRazaoSocial        = $F('z01_nome').replace("&","\\x86");
	oParam.sEmail              = $F('z01_email');
	oParam.iCodigoLogradouro   = $F('j14_codigo');
	oParam.iCodigoBairro       = $F('j13_codi');
	oParam.iNumero             = $F('z01_numero');
	oParam.sComplemento        = $F('z01_compl');
	oParam.iCodigoBairro       = $F('j13_codi');
	oParam.sBairro             = $F('j13_descr');
	oParam.iTelefone           = $F('z01_telef');
	oParam.iEscritorio         = $F('z01_numcgm');
	oParam.sEscritorio         = $F('z01_nome_escritorio');
	oParam.iZona               = $F('j50_zona');
	oParam.sZona               = $F('j50_descr');
	oParam.iNumeroEmpregados   = $F('numero_empregados');
	oParam.dDataInicio         = $F('data_inicio');
	oParam.nArea               = $F('area');
	oParam.aAtividades         = aAtividades;

	oParam.sExec = 'simular';

	js_divCarregando('Processando informações, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam),
                               onComplete: js_retornoOperacao
                              });
}

function js_retornoOperacao(oAjax) {

  js_removeObj('msgbox');
  
  var oRetorno = eval("("+oAjax.responseText+")");

  alert( oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n") );

  if (oRetorno.iStatus == 1) {

    oCalculo = Object.toJSON(oRetorno.oCalculo);
    window.open('iss2_imprimebicisssimulacalculo001.php?iSimulacao='+oRetorno.iSimulacao+'&oCalculo='+oCalculo,'','location=0,height=600,width=600');

  }

}

function js_pesquisaZona() {
  js_OpenJanelaIframe('','db_iframe_zona','func_zonas.php?funcao_js=parent.js_mostraZona|0|1','Pesquisa',true, '0', (screen.availWidth - 800) / 2 , 800, 500);
}
function js_mostraZona(iCodigoZona, sNomeZona) {
  document.form1.j50_zona .value = iCodigoZona;
  document.form1.j50_descr.value = sNomeZona;
  db_iframe_zona.hide();
}
function js_pesquisaAtividade() {

  tipoPesquisa = '';

  if ($F("z01_cgccpf").length == 14) {
    tipoPesquisa = 'cnpj';
  }
  js_OpenJanelaIframe('','db_iframe_ativid','func_ativid.php?funcao_js=parent.js_mostraAtividade|q03_ativ|q03_descr&tipoPesquisa='+tipoPesquisa,'Pesquisa',true, '0', (screen.availWidth - 800) / 2 , 800, 500);

}
function js_mostraAtividade(iCodigoAtividade, sNomeAtividade) {
  document.form1.q03_ativ.value  = iCodigoAtividade;
  document.form1.q03_descr.value = sNomeAtividade;
  db_iframe_ativid.hide();
}
function js_pesquisaEscritorio() {
  js_OpenJanelaIframe('','db_iframe_cadescrito','func_cadescrito.php?funcao_js=parent.js_mostraEscritorio|q86_numcgm|z01_nome','Pesquisa',true, '0', (screen.availWidth - 800) / 2 , 800, 500 );
}
function js_mostraEscritorio(iCgmEscritorio, sNomeEscritorio) {
  document.form1.z01_numcgm.value 			   = iCgmEscritorio;
  document.form1.z01_nome_escritorio.value = sNomeEscritorio;
  db_iframe_cadescrito.hide();
}
function js_pesquisaBairro() {
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostraBairro|0|1','Pesquisa',true, '0', (screen.availWidth - 800) / 2 , 800, 500 );
}
function js_mostraBairro(iCodigoBairro, sNomeBairro) {
  document.form1.j13_codi.value  = iCodigoBairro;
  document.form1.j13_descr.value = sNomeBairro;
  db_iframe_bairro.hide();
}

function js_pesquisaLogradouro() {
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraRua|0|1','Pesquisa',true, '0', (screen.availWidth - 800) / 2 , 800, 500 );
}

function js_mostraRua(iCodigoRua, sNomeRua) {
	document.form1.j14_codigo.value = iCodigoRua;
	document.form1.j14_nome.value   = sNomeRua;
	db_iframe_ruas.hide();
}

function js_lancar() {

	var iCodigoAtividade    = document.form1.q03_ativ.value;
	var sNomeAtividade      = document.form1.q03_descr.value;

	/**
	 * Adiciona ao array com os dados para evitar duplicidade
	 */
  if (js_in_array(iCodigoAtividade, aSelecionados)) {
    return false;
  } else {
	  aSelecionados.push(iCodigoAtividade);
  }

	if(iCodigoAtividade == '' || sNomeAtividade == '') {
		return false;
	}

  var oFieldSetAtividades = document.getElementById("fieldsetAtividades");
  oFieldSetAtividades.style.display = '';

  var oTabela                     = document.getElementById("corpoAtividades");
  var iNumeroLinhas               = oTabela.rows.length + 1;
  var oNewLinha                   = document.createElement("tr");

  oNewLinha.id                    = 'linhaAtividade_' +  iCodigoAtividade;
  oNewLinha.style.backgroundColor = "#FFF";
  oNewLinha.style.border          = "2px outset #DDD";

  var oNewCell      = document.createElement("td");
  var sCodigo       = document.createTextNode(iCodigoAtividade);
  var oInput        = document.createElement("input");
      oInput.id     = 'atividade_' + iNumeroLinhas;
      oInput.name   = 'atividade_' + iNumeroLinhas;
      oInput.type   = 'hidden';
      oInput.readOnly = true;
      oInput.value  = iCodigoAtividade;

  oNewCell.appendChild(oInput);
  oNewCell.appendChild(sCodigo);
  oNewLinha.appendChild(oNewCell);

  var oNewCell           = document.createElement("td");
  var oInput             = document.createElement("div");
		  oInput.id          = 'descricao_' + iNumeroLinhas;
		  oInput.name        = 'descricao_' + iNumeroLinhas;
		  oInput.innerHTML   = sNomeAtividade;

  oNewCell.appendChild(oInput);
  oNewLinha.appendChild(oNewCell);

  var oNewCell           = document.createElement("td");
  var oInput             = document.createElement("input");
      oInput.type        = 'radio';
		  oInput.id          = 'atividadePrincipal';
		  oInput.name        = 'principal';
		  oInput.value       = iCodigoAtividade;
      oInput.setAttribute("onclick", function() { js_defineAtividadePrincipal(this.value)});
		  oNewCell.align     = 'center';
		  oInput.style.width = '50px';

  oNewCell.appendChild(oInput);
  oNewLinha.appendChild(oNewCell);

  var oNewCell           = document.createElement("td");
  var oInput             = document.createElement("input");
		  oInput.id          = 'quantidade_' + iNumeroLinhas;
		  oInput.name        = 'quantidade_' + iNumeroLinhas;
		  oInput.value       = 1;
		  oInput.setAttribute("onkeyup", "js_ValidaCampos(this, 1, 'Quantidade', 't', 'f', event)");
		  oInput.style.width = '100%';

  oNewCell .appendChild(oInput);
  oNewLinha.appendChild(oNewCell);

  var oNewCell              = document.createElement("td");
  var oInput                = document.createElement("input");
      oInput.type           = 'button';
		  oInput.id             = 'excluir_' + iCodigoAtividade;
		  oInput.name           = 'excluir_' + iCodigoAtividade;
		  oInput.value          = 'E';
		  oInput.setAttribute("onclick", function() {js_excluirAtividade(this.parentNode.parentNode, this.value)});

  oNewCell .appendChild(oInput);
  oNewLinha.appendChild(oNewCell);
  oTabela  .appendChild(oNewLinha);


/**
 * Limpa os dados da atividade lancada
 */
  $('q03_descr').setValue('');
  $('q03_ativ') .setValue('');

}

function js_excluirAtividade (oLinha, iCodigoAtividade) {

  var iIndiceExclusao = null;

	for (var iIndice in aSelecionados) {

	  if (aSelecionados[iIndice] == iCodigoAtividade) {
		  iIndiceExclusao = iIndice;
	  }
	}
	if (iCodigoAtividade == iAtividadePrincipal) {
		iAtividadePrincipal = null;
	}
	if ( iIndiceExclusao != null ) {
	  aSelecionados.splice(iIndiceExclusao, 1);
	}
  oLinha.parentNode.removeChild(oLinha);

}

$$('input:text').each( function(oElemento, iIndice) {

  if (oElemento.readOnly) {
    oElemento.style.backgroundColor = "#EEE";
  } else {
    oElemento.style.backgroundColor = "#FFF";
  }
});

var aShowEvents     = ["onFocus", "onMouseOver"];
var aHideEvents     = ["onBlur" , "onMouseOut" ];

var oHintCpfCnpj     = new DBHint("oHintCpfCnpj");
    oHintCpfCnpj    .setText("<b>Digite o CPF ou CNPJ. (Somente números)</b>");
    oHintCpfCnpj    .setShowEvents(aShowEvents);
    oHintCpfCnpj    .setHideEvents(aHideEvents);
    oHintCpfCnpj    .make($('z01_cgccpf'));


var oHintLogradouro  = new DBHint("oHintLogradouro");
    oHintLogradouro .setText("<b>Selecione o Logradouro clicando no link ao lado.</b>");
    oHintLogradouro .setShowEvents(aShowEvents);
    oHintLogradouro .setHideEvents(aHideEvents);
    oHintLogradouro .make($('j14_nome'));

var oHintBairro      = new DBHint("oHintBairro");
    oHintBairro     .setText("<b>Selecione o Bairro clicando no link ao lado.</b>");
    oHintBairro     .setShowEvents(aShowEvents);
    oHintBairro     .setHideEvents(aHideEvents);
    oHintBairro     .make($('j13_descr'));

var oHintEscritorio  = new DBHint("oHintEscritorio");
    oHintEscritorio .setText("<b>Selecione o Escritório Contábil clicando no link ao lado.</b>");
    oHintEscritorio .setShowEvents(aShowEvents);
    oHintEscritorio .setHideEvents(aHideEvents);
    oHintEscritorio .make($('z01_nome_escritorio'));

var oHintZona        = new DBHint("oHintZona");
    oHintZona       .setText("<b>Selecione a Zona Fiscal clicando no link ao lado.</b>");
    oHintZona       .setShowEvents(aShowEvents);
    oHintZona       .setHideEvents(aHideEvents);
    oHintZona       .make($('j50_descr'));

var oHintAtividade   = new DBHint("oHintAtividade");
		oHintAtividade  .setText("<b>Selecione a Atividade clicando no link ao lado.</b>");
    oHintAtividade  .setShowEvents(aShowEvents);
    oHintAtividade  .setHideEvents(aHideEvents);
    oHintAtividade  .make($('q03_descr'));

</script>

</form>
</body>
</html>