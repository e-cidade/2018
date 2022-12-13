<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: saude
$clrotulo = new rotulocampo ( );

//Login
$clrotulo->label ( "login" );
//Departamento
$clrotulo->label ( "sd24_i_codigo" );
$clrotulo->label ( "sd24_i_unidade" );
$clrotulo->label ( "descrdepto" );
//CGS
$clrotulo->label ( "z01_i_cgsund" );
$clrotulo->label ( "z01_v_nome" );
$clrotulo->label ( "z01_d_nasc" );
//Cid
$clrotulo->label ( "sd70_c_cid" );
$clrotulo->label ( "sd70_c_nome" );
$clrotulo->label ( "sd55_i_cid" );
//Diagnóstico
$clrotulo->label ( "sd24_t_diagnostico" );
?>

<form name="form1" method="post" action="">
	<center>
	<table border="0">
		<tr>
			<td>
			<fieldset><legend><b>Digitação Individual</b></legend>
			<table>
					<!-- Lote / login -->
					<tr>
						<td width="83">
						</td>
						<td>
						</td>
						<td>
						</td>
					</tr>
				<!-- Incluí segunda parte do formulário -->
				<?php
				include ("forms/db_frmsau_lote001.php");
				?>
	  		</table>
			</fieldset>
			</td>
		</tr>
	</table>
	</center>
	<input
		name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 || @$idarq == 2 || @$idarq == 22 ? "alterar" : "excluir"))?>"
		type="button"
		id="db_opcao"
		value="<?=($db_opcao == 1 ? "Incluir FAA" : ($db_opcao == 2 || $db_opcao == 22 || @$idarq == 2 || @$idarq == 22 ? "Alterar FAA" : "Excluir FAA"))?>"
		<?=($db_botao == false ? "disabled" : "")?>
		onClick="return js_db_opcao()"
		onFocus='js_foco(this, "pesquisarfaa" );'
	>

	<input name="pesquisarfaa"
		type="button"
		id="pesquisarfaa"
		value="Consulta FAA"
		onclick="js_pesquisafaa(<?=$db_opcao?>);"
		onFocus='js_foco(this, focoInclusao.name );'
		onblur="focoInclusao.focus()"
	>

</form>

<script>

strURL         = 'sau1_sau_individualRPC.php';
focoInclusao   = $('sd24_i_codigo');
objAtualFocado = focoInclusao;

document.onkeydown = function(evt) {
 	var evt = (evt) ? evt : (window.event) ? window.event : "";
	var array_types = new Array('button','submit','reset');
	var valor_types = js_search_in_array(array_types, evt.target.type);
	if (evt.keyCode == 13 ) {
		if (nextfield == 'done' || valor_types ) {
			return true;
		} else {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		}
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}

//Evento onclick do documento
document.onclick = function(evt){
	var objTarget = evt.target;

	if( objTarget.type != 'select-one' )
		if( objTarget.readOnly == undefined || objTarget.readOnly == true )
			objAtualFocado.focus();
}


/**
 * Função para atualizar foco atual e próximo foco
 * objAtual - foco no objeto atual
 * strField - string com nome do próximo foco
 */
function js_foco(objAtual, strField ){
	objAtualFocado = objAtual;
	nextfield=strField;
	return false;
}

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno ){
	var objAjax = new Ajax.Request(
                         strURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(objParam),
                          onCreate  : function(){
                          				js_divCarregando( strCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				js_removeObj('msgbox');
                          				eval( evlJS );
                          			}
                         }
                        );
}

/**
 * Função para limpar dados
 */
function js_limpadados(){
	//var arrInput = $$('input, textarea');
	var arrInput = $('divFAA').getElementsByTagName('input');
	for( var i=0; i < arrInput.length; i++ ){
		var elmX = arrInput[i];
		if( elmX.type == 'text' || elmX.type == 'hidden' || elmX.type == 'textarea' ){
			elmX.value = '';
		}
	}

	if( $('db_opcao').name == 'excluir' ){

		document.form1.sd24_i_codigo.style.background = '';
		document.form1.z01_v_nome.style.background    = '';
		document.form1.z01_v_nome.readOnly            = false;
		document.form1.sd24_t_diagnostico.value       = '';

		focoInclusao.focus();

		if( $('db_opcao').name == 'alterar' ){
			//location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?idarq=<?=$idarq?>&chavepesquisalote='+$F('sd58_i_codigo');
		}else{
			$('db_opcao').name       = "incluir";
			$('db_opcao').value      = "Incluir FAA";
			//$('prosseguir').disabled = false;

		}
	}
}

/**
 * Pesquisa FAA
 */
function js_pesquisasd24_i_codigo(mostra){
	var strParam = '';
	strParam += 'func_prontuarios003.php'
	strParam += '?chave_sd24_i_unidade='+document.form1.sd24_i_unidade.value;
	strParam += '&funcao_js=parent.js_preencheprontuario|sd24_i_codigo';
	strParam += '&campoFoco=sd24_i_codigo';

	if( $('db_opcao').name == 'incluir'){

		if(mostra==true){
			js_OpenJanelaIframe('','db_iframe_prontuarios',strParam,'Pesquisa FAA',true);
		}else{
			if( $F('sd24_i_codigo') != '' ){
				var objParam            = new Object();
				objParam.exec           = "getFAA";
				objParam.sd24_i_codigo  = $F('sd24_i_codigo');
				objParam.sd24_i_unidade = $F('sd24_i_unidade');

				js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoFAA' );
			}
	  	}
  	}
}
function js_preencheprontuario(chave){
	db_iframe_prontuarios.hide();

	var objParam            = new Object();
  	objParam.exec           = "getFAA";
  	objParam.sd24_i_codigo  = chave;
	objParam.sd24_i_unidade = $F('sd24_i_unidade');

  	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoFAA' );

}

/**
 * retorna FAA
 */
function js_retornoFAA( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");
  	var objForm    = document.form1;
	if (objRetorno.status == 1) {
		if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objProntuario, iIteracao) {
     			//Prenche Formulário
     			objForm.sd24_i_codigo.value      = objProntuario.sd24_i_codigo;
     			objForm.z01_i_cgsund.value       = objProntuario.z01_i_cgsund;
     			objForm.z01_v_nome.value         = objProntuario.z01_v_nome.urlDecode();
     			objForm.z01_d_nasc.value         = objProntuario.z01_d_nasc;
         		js_atualizarIdade();
     			objForm.sd24_t_diagnostico.value    = objProntuario.sd24_t_diagnostico.urlDecode();
     			objForm.z01_v_nome.style.background = '#DEB887';
				objForm.z01_v_nome.readOnly         = true;
     			objForm.sd70_c_cid.value            = objProntuario.sd70_c_cid.urlDecode();
     			objForm.sd70_c_nome.value           = objProntuario.sd70_c_nome.urlDecode();

         	});
			$('sd70_c_cid').focus();
			$('campoFocado').value = 'sd24_i_codigo';
			$('db_opcao').name       = "alterar";
			$('db_opcao').value      = "Alterar FAA";
 		}

	} else {
		alert(objRetorno.message.urlDecode());
		focoInclusao = $('sd24_i_codigo');
		focoInclusao.focus();
		js_limpadados();
	}

}



/**
 * Pesquisa CGS
 */
function js_pesquisaz01_i_cgsund(mostra){
	var strParam = ''
	strParam += 'func_cgs_und.php';
	strParam += '?funcao_js=parent.js_preenchecgs|z01_i_cgsund|z01_v_nome|z01_d_nasc';
	strParam += '&retornacgs=p.p.document.form1.z01_i_cgsund.value';
	strParam += '&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);p.p.document.form1.z01_v_nome.value';
	strParam += '&campoFoco=z01_v_nome';

	if( $('db_opcao').name == 'incluir'){
		if(mostra==true){
			js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa CGS',true);
		}else{
			if(document.form1.z01_i_cgsund.value != ''){
				strParam += '&chave_z01_i_cgsund='+document.form1.z01_i_cgsund.value;

				js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa CGS',true);
				document.form1.z01_i_cgsund.value = '';
				document.form1.z01_v_nome.value = '';
				document.form1.z01_d_nasc.value = '';
				document.form1.iIdade.value     = '';
			}
		}
	}
}

function js_pesquisaz01_v_nome(mostra){
	var strParam = '';
	strParam += 'func_cgs_und.php';
	strParam += '?funcao_js=parent.js_preenchecgs|z01_i_cgsund|z01_v_nome|z01_d_nasc';
	strParam += '&retornacgs=p.p.document.form1.z01_i_cgsund.value';
	strParam += '&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);p.p.document.form1.z01_v_nome.value';
	strParam += '&campoFoco=z01_v_nome';

	if(mostra==true && $F('z01_v_nome') == '' ){
		js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa Paciente',true);
	}else{
		if(document.form1.z01_v_nome.value != '' && $('z01_v_nome').readOnly == false){

			strParam += "&chave_z01_v_nome="+URLEncode(document.form1.z01_v_nome.value);

			js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa Paciente',true);
			document.form1.z01_i_cgsund.value = '';
			document.form1.z01_v_nome.value = '';
	   		document.form1.z01_d_nasc.value = '';
	   		document.form1.iIdade.value     = '';
	   		document.form1.z01_d_nasc_dia.value = '';
	   		document.form1.z01_d_nasc_mes.value = '';
	   		document.form1.z01_d_nasc_ano.value = '';
		}
	}
}
function js_preenchecgs(chave1,chave2,chave3){
	db_iframe_cgs_und.hide();
	document.form1.z01_i_cgsund.value = chave1;
	document.form1.z01_v_nome.value   = chave2;
	if( chave3 != '' ){
		document.form1.z01_d_nasc.value   = chave3.substr(8,2)+'/'+chave3.substr(5,2)+'/'+chave3.substr(0,4);
		js_atualizarIdade();
		//document.getElementById('sd70_c_cid').focus();
	}else{
		alert('Data de Nascimento em branco, deverá ser preenchida');
		document.getElementById('z01_d_nasc').focus();
	}

	$('campoFocado').value = 'z01_v_nome';

}


/**
 * Pesquisa CID
 */
function js_pesquisasd70_c_cid(mostra){
	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_sau_cid','func_sau_cid.php?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome','Pesquisa CID',true);
	}else{
		if(document.form1.sd70_c_cid.value != ''){
			var objParam            = new Object();
			objParam.exec           = "getCID";
			objParam.sd70_c_cid     = $F('sd70_c_cid');

			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoCID' );
		}else{
			document.form1.sd55_i_cid.value = '';
			document.form1.sd70_c_cid.value = '';
		}
	}
}
function js_mostrasd70_c_cid1(chave1,chave2,chave3){
	document.form1.sd55_i_cid.value = chave1;
	document.form1.sd70_c_cid.value = chave2;
	document.form1.sd70_c_nome.value = chave3;
	document.getElementById( nextfield ).focus();
	db_iframe_sau_cid.hide();

}

/**
 * retorno CID
 */
function js_retornoCID( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");
  	var objForm    = document.form1;

	objForm.sd55_i_cid.value = '';
	objForm.sd70_c_cid.value    = '';
	objForm.sd70_c_nome.value   = '';

  	if (objRetorno.status == 1) {
     	if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objCID, iIteracao) {
     			objForm.sd55_i_cid.value = objCID.sd70_i_codigo;
     			objForm.sd70_c_cid.value    = objCID.sd70_c_cid.urlDecode();
     			objForm.sd70_c_nome.value   = objCID.sd70_c_nome.urlDecode();
         	});
     	}
  	} else {
    	alert(objRetorno.message.urlDecode());
    	objForm.sd70_c_cid.focus();
  	}

}



/**
 * Botão Incluir
 */
function js_db_opcao(){
var booRetorno = false;
	if( document.form1.z01_i_cgsund.value == "" ){
		alert('Campo CGS obrigatório');
		$('z01_v_nome').focus();
	}else if ( document.form1.z01_d_nasc.value == "" && $('db_opcao').name != 'excluir' ){
		alert('Campo Data Nascimento obrigatório');
		$('z01_d_nasc').focus();
	//}else if( document.form1.sd70_c_cid.value == "" && $('db_opcao').name != 'excluir' ){
	//	alert('Campo CID obrigatório');
	//	$('sd70_c_cid').focus();
	// retirada verificação cfe tarefa 28998
	}else{

		var objParam           = new Object();
		objParam.exec          = $('db_opcao').name;

		var arrInputs = $$('input, select, textarea');
		arrInputs.each(function(input, intIterator) {
			if (input.type == 'text' || input.type == 'hidden' || input.type == 'textarea' || input.type == 'select-one') {
				var evlTmp = 'objParam.'+input.name+'='+'\''+input.value+'\'; \n';
				eval( evlTmp );
			}
		});
		focoInclusao =  $('sd24_i_codigo');
		if( isNaN( parseInt( $('sd24_i_codigo').value ) ) )
			focoInclusao = $('z01_v_nome');

		if( objParam.exec == 'excluir' ){
			js_ajax(objParam, 'Aguarde, lançando os dados....', 'js_retornoExcluir');
		}else{
			js_ajax(objParam, 'Aguarde, lançando os dados....', 'js_retornoIncluir');
		}

		booRetorno = false;
	}
	return booRetorno;

}

/**
 * Retorno Incluir
 */
function js_retornoIncluir( objAjax ){
var objRetorno = eval("("+objAjax.responseText+")");

  	if (objRetorno.status == 1) {
		$('sd24_i_codigo').value = objRetorno.sd24_i_codigo;
		//js_limpadados();
		//js_reloadlote();
		parent.document.formaba.a2.disabled = false
		parent.mo_camada('a2');
		parent.iframe_a2.location.href='sau1_sau_individualproced001.php?idarq=<?=$idarq?>&tmp_table=true&sd24_i_codigo='+$F('sd24_i_codigo');
  	}

  	alert(objRetorno.message.urlDecode());

}
/**
 * Retorno Excluir
 */
function js_retornoExcluir( objAjax ){
var objRetorno = eval("("+objAjax.responseText+")");

	alert(objRetorno.message.urlDecode());
  	if (objRetorno.status == 1) {
		js_limpadados();
  		document.form1.pesquisarfaa.click();
  	}

}

/**
 * Reload Grid
 */
function js_reloadlote(){
	var objParam           = new Object();
  	objParam.exec          = "getLote";
  	objParam.sd58_i_codigo = $F('sd58_i_codigo');
  	if( $('db_opcao').name != 'incluir' || 	$F('sd58_i_codigo') != '' ){
  		js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoLote' );
  	}

}
/**
 * retorna data do veiculo
 */
function js_retornoLote( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

  	objGridLote.clearAll(true);

  	if (objRetorno.status == 1) {
     	if (objRetorno.itens.length > 0) {

     		objRetorno.itens.each(function (objLote, iIteracao) {

	     		var arrLinha = new Array();
	          	arrLinha[0]  = objLote.sd59_i_codigo;
	          	arrLinha[1]  = objLote.sd59_i_lote;
	          	arrLinha[2]  = objLote.sd59_i_prontuario;
	          	arrLinha[3]  = objLote.z01_i_cgsund;
	          	arrLinha[4]  = objLote.z01_v_nome.urlDecode();

	          	strDisabled  =  objLote.sd59_i_codigo==""?" disabled ":"";

	          	//arrLinha[4]  =  "<input type='button' "+strDisabled+" value='Excluir' onclick='js_excluirAgendaTransporte("+objLote.sd59_i_codigo+")'>";

	          	objGridLote.addRow(arrLinha);
         	});
         	objGridLote.renderRows();
     	}
  	} else {
    	alert(objRetorno.message.urlDecode());
  	}

}


function js_pesquisafaa(chave){
	var strParam = '';
	strParam += '?pesquisa_chave=true';
	strParam += '&funcao_js=parent.js_preenchepesquisafaa|sd24_i_codigo';

	if(chave==1){
		js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuariosincl002.php'+strParam,'Pesquisa',true);
	}else{
		js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuariosalt002.php'+strParam,'Pesquisa',true);
	}
}
function js_preenchepesquisafaa(chave){
	db_iframe_prontuarios.hide();
	<?
		echo " location.href = '" . basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] ) . "?idarq=" . @$idarq . "&db_botao=true&chavepesquisaprontuario='+chave";
	?>
}

</script>