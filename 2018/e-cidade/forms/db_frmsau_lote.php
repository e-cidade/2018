<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
$clsau_lote->rotulo->label();
$clrotulo = new rotulocampo;
//Login
$clrotulo->label("login");
//Lote Prontuario
$clrotulo->label("sd59_i_codigo");
$clrotulo->label("sd59_i_lote");
$clrotulo->label("sd59_i_prontuario");

//Departamento
$clrotulo->label("sd24_i_codigo");
$clrotulo->label("sd24_i_unidade");
$clrotulo->label("descrdepto");
//CGS
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_d_nasc");
//Cid
$clrotulo->label("sd70_c_cid");
$clrotulo->label("sd70_c_nome");
$clrotulo->label("sd55_i_cid");
//Diagnóstico
$clrotulo->label("sd24_t_diagnostico");
?>

<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
		<td>
			<fieldset><legend><b>Digitação Lote</b></legend>
			<table>
				<!-- Lote / login -->
				<tr>
					<td nowrap title="<?=@$Tsd58_i_codigo?>" width="83">
						<?=@$Lsd58_i_codigo?>
					</td>
					<td>
						<?
						db_input('sd58_i_codigo',10,$Isd58_i_codigo,true,'text',3,"tabIndex='0' ");
						db_input('sd59_i_codigo',10,$Isd59_i_codigo,true,'hidden',3,"tabIndex='0' ");
						?>
					</td>
					<td align="right" title="<?=@$Tsd58_i_login?>">
						<?=@$Lsd58_i_login?>
						<?
						db_input('sd58_i_login',10,$Isd58_i_login,true,'hidden',3,"tabIndex='0' ");
						db_input('login',10,$Ilogin,true,'text',3,"tabIndex='0'");
						?>
					</td>
				</tr>
				<!-- Incluí segunda parte do formulário -->
				<?
				include("forms/db_frmsau_lote001.php");
				?>

			</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td align="center">

      <?php
      $sOculta = "style = 'display: none;'";
      if ( in_array($db_opcao, array(1,2)) ) {
        $sOculta = "";
      }
      ?>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22||@$idarq==2||@$idarq==22?"alterar":"excluir"))?>"
             type="submit"
             <?=$sOculta?>
             id="db_opcao"
             value="<?=($db_opcao==1?"Incluir FAA":($db_opcao==2||$db_opcao==22||@$idarq==2||@$idarq==22?"Alterar FAA":"Excluir FAA"))?>" <?=($db_botao==false?"disabled":"")?>
             onFocus='js_foco(this, "prosseguir");'
             <?=($db_botao==false?"disabled":"")?>
             onClick="<?=($db_opcao==3||$db_opcao==33) ? "return js_excluirFAA(); " : "return js_db_opcao();" ?>">

			<input name="prosseguir"
				type="button"
				id="prosseguir"
				value="Prosseguir"
				onFocus='js_foco(this, "pesquisar");'
				onclick="js_prosseguir();"
				<?=($db_opcao<>1?"disabled":"")?>
			>

			<?
  			if($db_opcao==3){
  			?><input name="excluirlote" type="submit" id="excluirlote" value="Excluir Lote" <?=($db_botao==false?"disabled":"")?>  onClick="return confirm('Você quer realmente excluir este registro?')"  ><?
  			}
  			if(@$idarq==2||@$idarq==3||@$idarq==22){
    		?><input name="pesquisarlote" type="button" id="pesquisarlote" value="Consulta Lote" onclick="js_pesquisalote();"> <?
  			}
			?>

			<input name="pesquisar"
				type="button"
				id="pesquisar"
				value="Consulta FAA Lote"
				onclick="js_pesquisa();"
				onFocus='js_foco(this, focoInclusao.name );'
				onblur="focoInclusao.focus()" >

		</td>
	</tr>
	<tr>
		<td>
			<fieldset><legend><b>FAA Lote</b></legend>
			<div id="gridLote"></div>
			</fieldset>
		</td>
	</tr>
</table>
</center>
</form>

<script>

strURL         = 'sau1_sau_loteRPC.php';
focoInclusao   = document.form1.sd24_i_codigo;
objAtualFocado = focoInclusao;

//Evento onkeydow do documento
//document.onkeydown = keyDown;
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

function js_excluirFAA() {

	if (objGridLote.getNumRows() <= 1) {
		return confirm('\nO lote possui apenas esta FAA e será excluído também. \nDeseja realmente excluir FAA?\n');
	} else {
    return confirm('\nVocê quer realmente excluir este registro?\n');
	}

}

function js_init() {
	var arrHeader = new Array ( '<?=str_replace(":","",$Lsd59_i_codigo)?>',
        '<?=str_replace(":","",$Lsd59_i_lote)?>',
        '<?=str_replace(":","",$Lsd59_i_prontuario)?>',
        '<?=str_replace(":","",$Lz01_i_cgsund)?>',
        '<?=str_replace(":","",$Lz01_v_nome)?>',
        'Opções');

	objGridLote              = new DBGrid('objGridLote');
	objGridLote.nameInstance = 'objGridLote';
	objGridLote.setHeader( arrHeader );
  objGridLote.setCellAlign(['left', 'left', 'left', 'left', 'left', 'center'])
	objGridLote.show($('gridLote'));
	js_reloadlote();
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
                          				//js_divCarregando( strCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				//js_removeObj('msgbox');
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

	document.form1.sd24_i_codigo.style.background = '';
	document.form1.z01_v_nome.style.background    = '';
	document.form1.z01_v_nome.readOnly            = false;
	document.form1.sd24_t_diagnostico.value       = '';

	focoInclusao.focus();

	if( $('db_opcao').name == 'alterar' ){
		location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?idarq=<?=$idarq?>&chavepesquisalote='+$F('sd58_i_codigo');
	}else{
		$('db_opcao').name       = "incluir";
		$('db_opcao').value      = "Incluir FAA";
		$('prosseguir').disabled = false;
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

	if( $('db_opcao').name == 'incluir'){

		if(mostra==true){
			js_OpenJanelaIframe('','db_iframe_prontuarios',strParam,'Pesquisa',true);
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
     			objForm.sd70_c_cid.value         = objProntuario.sd70_c_cid.urlDecode();
     			objForm.sd70_c_nome.value        = objProntuario.sd70_c_nome.urlDecode();
     			objForm.sd24_t_diagnostico.value = objProntuario.sd24_t_diagnostico.urlDecode();
     			//Style
     			objForm.z01_v_nome.style.background = '#DEB887';
				objForm.z01_v_nome.readOnly = true;
         	});
			//$('sd70_c_cid').focus();
			$('campoFocado').value = 'sd24_i_codigo';
 		}
	} else {
		alert(objRetorno.message.urlDecode());
		js_limpadados();

	}

}



/**
 * Pesquisa CGS
 */
function js_pesquisaz01_i_cgsund(mostra){
	if( document.form1.db_opcao.name == 'incluir'){
	  if(mostra==true){
	    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund|z01_v_nome|z01_d_nasc&retornacgs=p.p.document.form1.z01_i_cgsund.value&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);p.p.document.form1.z01_v_nome.value','Pesquisa',true);
	  }else{
	     if(document.form1.z01_i_cgsund.value != ''){
	    	js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund|z01_v_nome|z01_d_nasc&retornacgs=p.p.document.form1.z01_i_cgsund.value&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);p.p.document.form1.z01_v_nome.value&chave_z01_i_cgsund='+document.form1.z01_i_cgsund.value,'Pesquisa',true);
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

	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa',true);
	}else{
		if(document.form1.z01_v_nome.value != '' && $('z01_v_nome').readOnly == false ){
			strParam += "&chave_z01_v_nome="+URLEncode(document.form1.z01_v_nome.value);
			js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa',true);
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
		document.form1.iIdade.value = '';
		document.getElementById('z01_d_nasc').focus();
	}
	$('campoFocado').value = 'z01_v_nome';

}


/**
 * Pesquisa CID
 */
function js_pesquisasd70_c_cid(mostra){
	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_sau_cid','func_sau_cid.php?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome','Pesquisa',true);
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

function js_pesquisa(){
  parametro = '?funcao_js=parent.js_preenchepesquisa|sd59_i_codigo';
  parametro += '&chave_sd59_i_lote='+document.form1.sd58_i_codigo.value;
  if( document.form1.sd58_i_codigo.value != "" ){
  	js_OpenJanelaIframe('','db_iframe_sau_lotepront','func_sau_lotepront.php'+parametro,'Pesquisa',true);
  }else{
  	alert('Lote não informado.');
  }
}
function js_preenchepesquisa(chave){

	$('sd59_i_codigo').value = chave;
  db_iframe_sau_lotepront.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?idarq=$idarq&chavepesquisalotepront='+chave";
  ?>
}
function js_pesquisalote(){
  parametro = '?funcao_js=parent.js_preenchepesquisalote|sd58_i_codigo';
  js_OpenJanelaIframe('','db_iframe_sau_lote','func_sau_lote.php'+parametro,'Pesquisa',true);
}
function js_preenchepesquisalote(chave){
  db_iframe_sau_lote.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?idarq=$idarq&chavepesquisalote='+chave";
  ?>
}

function js_prosseguir(){
  if( document.form1.sd58_i_codigo.value == "" ){
  	alert('Deve ser gerado um número de lote.');
  }else{
    parent.document.formaba.a1.disabled = true;
    parent.document.formaba.a2.disabled = false;
    parent.iframe_a2.location.href='sau1_sau_loteproced001.php?idarq=<?=$idarq?>&tmp_table=true&sd58_i_codigo='+document.form1.sd58_i_codigo.value;
  	parent.mo_camada("a2");
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
	}else if ( document.form1.z01_d_nasc.value == "" ){
		alert('Campo Data Nascimento obrigatório');
		$('z01_d_nasc').focus();
	//}else if( document.form1.sd70_c_cid.value == "" ){
	//	alert('Campo CID obrigatório');
	//	$('sd70_c_cid').focus();
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

		//desabilita botão para não duplicar informações
		$('db_opcao').disabled = true;

		js_ajax(objParam, 'Aguarde, lançando os dados....', 'js_retornoIncluir');

		booRetorno = false;
	}
	return booRetorno;

}

/**
 * Retorno Incluir
 */
function js_retornoIncluir( objAjax ){
var objRetorno = eval("("+objAjax.responseText+")");

	$('db_opcao').disabled = false;

  	if (objRetorno.status == 1) {
		$('sd58_i_codigo').value = objRetorno.sd58_i_codigo;
		js_limpadados();
		js_reloadlote();
  	}else{
  		alert(objRetorno.message.urlDecode());
  	}

}

/**
 * Reload Grid
 */
function js_reloadlote(){

  objGridLote.clearAll(true);

	if ($F('sd58_i_codigo') == '') {
    return;
	}
	var objParam           = new Object();
  	objParam.exec          = "getLote";
  	objParam.sd58_i_codigo = $F('sd58_i_codigo');

  	if( $('db_opcao').name != 'incluir' || 	$F('sd58_i_codigo') != '' ) {
  		js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoLote' );
  	}

}
/**
 * retorna lote FAA
 */
function js_retornoLote( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

  	if (objRetorno.status == 1) {
     	if (objRetorno.itens.length > 0) {

     		objRetorno.itens.each(function (objLote, iIteracao) {

	     		var arrLinha = new Array();
	          	arrLinha[0]  = objLote.sd59_i_codigo;
	          	arrLinha[1]  = objLote.sd59_i_lote;
	          	arrLinha[2]  = objLote.sd59_i_prontuario;
	          	arrLinha[3]  = objLote.z01_i_cgsund;
	          	arrLinha[4]  = objLote.z01_v_nome.urlDecode();
	          	arrLinha[5]  = '';
	          	arrLinha[5] =  "<input type='button' value='Excluir' onclick='js_excluirLoteFaa("+objLote.sd59_i_codigo+")'>";

	          	objGridLote.addRow(arrLinha);
         	});
         	objGridLote.renderRows();
     	}
  	} else {
    	alert(objRetorno.message.urlDecode());
  	}
}




function js_excluirLoteFaa(iCodigoLoteFaa) {

  if (!confirm('Você deseja realmente excluir esta FAA?')) {
    return false;
  }

  var oParametro    = {'sExecucao': 'excluirFaaLote','iCodigoLote': $F('sd58_i_codigo'), 'iCodigoLoteFaa': iCodigoLoteFaa};
  var oAjaxRequest  = new AjaxRequest('sau4_manutencaofaaresumida.RPC.php', oParametro, js_retornoExcluirFaa);
  oAjaxRequest.setMessage('Aguarde, excluindo FAA');
  oAjaxRequest.execute();

}

function js_retornoExcluirFaa(oRetorno, lErro) {

  if (lErro) {
    alert ( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  if (oRetorno.lLoteExcluido) {

    js_retorna();
    return;
  }

  js_reloadlote();

}

</script>