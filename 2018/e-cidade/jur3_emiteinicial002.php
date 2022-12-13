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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_app.utils.php"));

require_once modification("std/DBDate.php");

include(modification("dbforms/db_funcoes.php"));

include(modification("classes/db_iptubase_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_advog_classe.php"));
include(modification("classes/db_inicial_classe.php"));
include(modification("classes/db_inicialcert_classe.php"));
include(modification("classes/db_inicialmov_classe.php"));

db_app::import('exceptions.*');

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);

/**
 * Data final
 */	 
$diaDataInicial = null;
$mesDataInicial = null;
$anoDataInicial = null;

if ( isset($oPost->datainicial) && $oPost->datainicial <> null ) {

	try {

		$oDataInicial   = new DBDate($oPost->datainicial);
		$diaDataInicial = $oDataInicial->getDia();
		$mesDataInicial = $oDataInicial->getMes();
		$anoDataInicial = $oDataInicial->getAno();

	} catch (Exception $oErro) {
		echo $oErro->getMessage();
	}
}

/**
 * Data final
 */	 
$diaDataFinal   = null;
$mesDataFinal   = null;
$anoDataFinal   = null;

if ( isset($oPost->datafinal) && $oPost->datafinal <> null ) {

	try {

		$oDataFinal   = new DBDate($oPost->datafinal);
		$diaDataFinal = $oDataFinal->getDia();
		$mesDataFinal = $oDataFinal->getMes();
		$anoDataFinal = $oDataFinal->getAno();

	} catch (Exception $oErro) {
		echo $oErro->getMessage();
	}
}

$db_botao      = 1;
$botao         = 1;
$db_opcao      = 1;
$verificachave = true;
$veinclu       = false;

$clinicial      = new cl_inicial;
$clinicialcert  = new cl_inicialcert;
$clinicialmov   = new cl_inicialmov;
$cladvog        = new cl_advog;
$clcgm          = new cl_cgm;
$clrotulo       = new rotulocampo;

$cladvog->rotulo->label();
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
$clrotulo->label("v56_codsit");
$clrotulo->label("v52_descr");
$clrotulo->label("v54_codlocal");
$clrotulo->label("v54_descr");
$clrotulo->label("v50_data");
$clrotulo->label("v50_advog");
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_situacao");
$clrotulo->label("v70_codforo");
$clrotulo->label("v53_descr");
$clrotulo->label("v58_numcgm");
$clrotulo->label("v53_codvara");

if(isset($v13_certid) && $v13_certid!="" && $veinclu==false){
	db_msgbox(_M('tributario.juridico.jur3_emiteinicial002.nao_existe'));
}
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<?php  
			db_app::load("estilos.css");
			db_app::load("scripts.js, strings.js, prototype.js");
		?>
<style type="text/css">
	<!--
	td {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	input {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		height: 17px;
		border: 1px solid #999999;
	}
	-->
</style>
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" id="form1" method="post" action="" onsubmit="return js_pesquisa();">
	<fieldset>
		<legend>Consultas - Inicial</legend>
  	<table class="form-container">
  		<tr>
  			<td nowrap title="<?=@$Tv50_inicial?>">
  				<?=$Lv50_inicial?>
  			</td>
  			<td> 
  				<?
  					db_input('v50_inicial',10,$Iv50_inicial,true,'text',1);
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv70_codforo?>">
  				<?php
  					db_ancora(@$Lv70_codforo,"js_pesquisaCodigoForo(true);",1);
  				?>
  			</td>
  			<td> 
  				<?php
  					db_input('v70_codforo',64,$Iv70_codforo,true,'text',1," onchange='js_pesquisaCodigoForo(false);'");
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv58_numcgm?>">
  				<?php db_ancora(@$Lv58_numcgm," js_pesquisaCgm(true); ",1); ?>
  			</td>
  			<td> 
  				<?php
  					db_input('v58_numcgm', 10, $Iv58_numcgm, true, 'text', 4, " onchange='js_pesquisaCgm(false);'");
  					db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '', 'inicialnomes_z01_nome');
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv56_codsit?>">
  				<?php
  					db_ancora(@$Lv56_codsit,"js_pesquisaSituacao(true);","1");
  				?>
  			</td>
  			<td> 
  				<?php
  					db_input('v56_codsit',10,$Iv56_codsit,true,'text',"1","onchange='js_pesquisaSituacao(false);'");
  					db_input('v52_descr',50,$Iv52_descr,true,'text',3,'');
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv50_advog?>">
  				<?php
  					db_ancora(@$Lv50_advog,"js_pesquisaAdvogado(true);",$db_opcao);
  				?>
  			</td>
  			<td> 
  				<?php
  					db_input('v50_advog',10,$Iv50_advog,true,'text',$db_opcao,"onchange='js_pesquisaAdvogado(false);'");
  					db_input('z01_nome',50,$Iz01_nome,true,'text',3,'');
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv54_codlocal?>">
  				<?php
  					db_ancora(@$Lv54_codlocal,"js_pesquisaLocalizacao(true);",$db_opcao);
  				?>
  			</td>
  			<td> 
  				<?php 
  					db_input('v54_codlocal',10,$Iv54_codlocal,true,'text',$db_opcao,"onchange='js_pesquisaLocalizacao(false);'");
  					db_input('v54_descr',50,$Iv54_descr,true,'text',3,'');
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$tv53_codvara?>">
  				<?php
  				  db_ancora(@$Lv53_codvara,"js_pesquisaVara(true);",$db_opcao);
  				?>
  			</td>
  			<td>
  				<?php
    				db_input('v53_codvara',10,$Iv53_codvara,true,'text',$db_opcao," onchange='js_pesquisaVara(false);'");
    				db_input('v53_descr'  ,50,$Iv53_descr,true,'text',3,'');
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="Tipo">Tipo</td>
  			<td> 
  				<?php
  					$aTipo = array(""=>"TODOS", "1" => "ATIVA", "2" => "ANULADA");
  					db_select('v50_situacao',$aTipo, true, $db_opcao);
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv50_data?>">
  				<?=@$Lv50_data?>
  			</td>
  			<td> 
  				<?php
  					db_inputdata('datainicial', $diaDataInicial, $mesDataInicial, $anoDataInicial, true, 'text', $db_opcao,"");
  				?>
  				&nbsp;<b>até</b>
  				<?php
  					db_inputdata('datafinal', $diaDataFinal, $mesDataFinal, $anoDataFinal, true, 'text', $db_opcao,"");
  				?>
  			</td>
  		</tr>
  	</table>	
  </fieldset>	
  <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar" >
</form>

<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

<script>
/**
 * -----------------------------------------------------------------------------------------------------
 * FUNÇÕES DE PESQUISA PROCESSO FORO
 * --------------
 */	 

/**
 * Pesquisa processo foro
 * 
 * @param boolean $mostra 
 * @return boolean
 */
function js_pesquisaCodigoForo(lMostra) {

	if ( lMostra == false ) {
		return false;
	}

	sArquivoPesquisa  = 'func_processoforo.php?funcao_js=';
	sArquivoPesquisa += 'parent.js_pesquisaCodigoForoAncora|v70_codforo|v71_inicial';
	sIframe           = 'db_iframe_processoforo';
	sWidth            = document.body.clientWidth;
	sHeight           = document.body.clientHeight;

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa processo foro', lMostra, 20, 0, sWidth, sHeight);

	return true;
}

/**
 * Codigo do processo do foro
 * Retorno da loockup de pesquisa quando origem for o ANCORA
 */
function js_pesquisaCodigoForoAncora(iCodforo, iInicial) {  

  $('v70_codforo').value = iCodforo;
  $('v50_inicial').value = iInicial;

  db_iframe_processoforo.hide();   
}

/**
 * --------------
 * FUNÇÕES DE PESQUISA PROCESSO FORO
 * -----------------------------------------------------------------------------------------------------
 */	 


/**
 * -----------------------------------------------------------------------------------------------------
 * FUNÇÕES DE PESQUISA SITUACAO
 * ----------
 */	 

/**
 * Pesquisa situacao
 */
function js_pesquisaSituacao(lMostra) {

	sArquivoPesquisa = 'func_situacao.php?funcao_js=';
	sIframe          = 'db_iframe_situacao';
	sWidth           = document.body.clientWidth;
	sHeight          = document.body.clientHeight;

	/**
	 * Pesquisa pelo ancora
	 */	 
  if (lMostra == true ) {
		sArquivoPesquisa += 'parent.js_mostraSituacaoAncora|0|1';
	}

	/**
	 * Pesquisa pelo input
	 */	 
	if ( lMostra == false && $F('v56_codsit') != null ) {
		sArquivoPesquisa += 'parent.js_mostraSituacaoInput&pesquisa_chave=' + $F('v56_codsit');
	}

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa situação', lMostra, 20, 0, sWidth, sHeight);

	return true;
}

function js_mostraSituacaoAncora(iCodigo, sDescricao) {

	db_iframe_situacao.hide();
	$('v56_codsit').value = iCodigo; 
	$('v52_descr').value  = sDescricao; 
}

function js_mostraSituacaoInput(sDescricao, lErro) {

	if ( lErro == true ) {
		$('v56_codsit').value = null;
	}

	$('v52_descr').value = sDescricao;
}

/**
 * --------------
 * FUNÇÕES DE PESQUISA SITUACAO
 * -----------------------------------------------------------------------------------------------------
 */	 



/**
 * -----------------------------------------------------------------------------------------------------
 * FUNÇÕES PARA PESQUISAR OS ADVOGADOS 
 * -----------
 */	 

/**
 * Pesquisa advogado 
 */
function js_pesquisaAdvogado(lMostra) {

	sArquivoPesquisa = 'func_advog.php?funcao_js=';
	sIframe          = 'db_iframe_advogado';
	sWidth           = document.body.clientWidth;
	sHeight          = document.body.clientHeight;

	/**
	 * Pesquisa pelo ancora
	 */	 
  if (lMostra == true ) {
		sArquivoPesquisa += 'parent.js_mostraAdvogadoAncora|0|z01_nome';
	}

	/**
	 * Pesquisa pelo input se o campo v50_advog não estiver vazio
	 */	 
	if ( lMostra == false && $F('v50_advog') != null ) {
		sArquivoPesquisa += 'parent.js_mostraAdvogadoInput&pesquisa_chave=' + $F('v50_advog');
	}

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa advogado', lMostra, 20, 0, sWidth, sHeight);

	return true;
}

/**
 * Retorno quando pesquisa for pelo input
 */
function js_mostraAdvogadoInput(sDescricao, lErro) {

	if ( lErro == true ) {
	
    $('v50_advog').focus(); 
    $('v50_advog').value = ''; 
	}

  $('z01_nome').value = sDescricao; 
}

/**
 * Retorno quando pesquisa for pelo ancora
 */
function js_mostraAdvogadoAncora(iCodigo, sDescricao) {

  $('v50_advog').value = iCodigo;
  $('z01_nome').value  = sDescricao;

  db_iframe_advogado.hide();
}

/**
 * ------------------
 * FUNÇÕES PARA PESQUISAR OS ADVOGADOS 
 * -----------------------------------------------------------------------------------------------------
 */	 

/**
 * -----------------------------------------------------------------------------------------------------
 * FUNÇÕES PARA PESQUISAR A LOCALIZAÇÃO
 * -----------
 */	 

/**
 * Pesquisa localização 
 */	 
function js_pesquisaLocalizacao(lMostra) {

	sArquivoPesquisa = 'func_localiza.php?funcao_js=';
	sIframe          = 'db_iframe_localizacao';
	sWidth           = document.body.clientWidth;
	sHeight          = document.body.clientHeight;

	/**
	 * Pesquisa pelo ancora
	 */	 
  if ( lMostra == true ) {
		sArquivoPesquisa += 'parent.js_mostraLocalizacaoAncora|0|1';
	}

	/**
	 * Pesquisa pelo input se o campo v54_codlocal não estiver vazio
	 */	 
	if ( lMostra == false && $F('v54_codlocal') != null ) {
		sArquivoPesquisa += 'parent.js_mostraLocalizacaoInput&pesquisa_chave=' + $F('v54_codlocal');
	}

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa localização', lMostra, 20, 0, sWidth, sHeight);

	return true;
}

function js_mostraLocalizacaoInput(sDescricao, lErro) {

	if ( lErro == true ) {
	
    $('v54_codlocal').focus(); 
    $('v54_codlocal').value = ''; 
	}

  $('v54_descr').value = sDescricao; 
}

function js_mostraLocalizacaoAncora(iCodigo, sDescricao) { 

  $('v54_codlocal').value = iCodigo;
  $('v54_descr').value    = sDescricao;

  db_iframe_localizacao.hide();
}

/**
 * -----------
 * FUNÇÕES PARA PESQUISAR A LOCALIZAÇÃO
 * -----------------------------------------------------------------------------------------------------
 */	 


/**
 * -----------------------------------------------------------------------------------------------------
 * FUNÇÕES PARA PESQUISAR VARA
 * -----------
 */	 

/**
 * Pesquisa localização 
 */	 
function js_pesquisaVara(lMostra) {

	sArquivoPesquisa = 'func_vara.php?funcao_js=';
	sIframe          = 'db_iframe_vara';
	sWidth           = document.body.clientWidth;
	sHeight          = document.body.clientHeight;

	/**
	 * Pesquisa pelo ancora
	 */	 
  if ( lMostra == true ) {
		sArquivoPesquisa += 'parent.js_mostraVaraAncora|0|1';
	}

	/**
	 * Pesquisa pelo input se o campo v53_codvara não estiver vazio
	 */	 
	if ( lMostra == false && $F('v53_codvara') != null ) {
		sArquivoPesquisa += 'parent.js_mostraVaraInput&pesquisa_chave=' + $F('v53_codvara');
	}

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa vara', lMostra, 20, 0, sWidth, sHeight);

	return true;
}

function js_mostraVaraInput(sDescricao, lErro) {

	if ( lErro == true ) {
	
    $('v53_codvara').focus(); 
    $('v53_codvara').value = ''; 
	}

  $('v53_descr').value = sDescricao; 
}

function js_mostraVaraAncora(iCodigo, sDescricao) { 

  $('v53_codvara').value = iCodigo;
  $('v53_descr').value    = sDescricao;

  db_iframe_vara.hide();
}

/**
 * -----------
 * FUNÇÕES PARA PESQUISAR VARA
 * -----------------------------------------------------------------------------------------------------
 */	 

/**
 * Limpa formulário
 * - Não usado o reset do html pq este retorna para o valor inicial(value) do campo, 
 *   que apos o primeiro submit muda, não limpando campo
 */	 
function js_limpar() {

	var oInputs = $('formInicial').getInputs();

	oInputs.each(function(oInput, iIndice) {

		if ( oInput.type ==  'text' ) {
			oInput.value = null;
		}
	});

  $('v50_situacao').value = '1';
}









/**
 * @todo procurar as funcoes abaixo que não sao usadas e remover
 */	 


/**
 * @todo - verificar
 */	 
function js_testacodforo(){

	// ---------------
	js_pesquisa();
	return true;
	// ---------------

	if (document.form1.v50_inicial.value==""){
		if (document.form1.v70_codforo.value!=""){
			js_openjanelaiframe('CurrentWindow.corpo','db_iframe_processoforo','func_processoforo.php?chave_v70_codforo='+document.form1.v70_codforo.value+'&funcao_js=parent.js_mostracodforo|v70_codforo|v71_inicial','pesquisa',true);
			return false;
		}
	}else{

		return true;
	}
}

function js_retorno(){
  return false;
}

var msg= (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML ;


/**
 * Abre lookup de pesquisa 
 * 
 */         
function js_pesquisa() {

	
	var sIframe        = 'db_iframe_emiteinicial';
  var sPrograma      = js_pesquisav50_inicial(true, 'PESQUISA');
	$('form1').method  = 'post';
	$('form1').action  = sPrograma;
	$('form1').target  = 'IFdb_iframe_inicial';
}

function js_pesquisav50_inicial(lMostrarJanela, sTipoRetorno){

	if (sTipoRetorno == null || sTipoRetorno == '') {
	  sTipoRetorno = 'LOOKUP';
	}
	var sLocal        = '';
	var sObjetoLookUp = 'db_iframe_inicial';
	var sTituloJanela = '';
	var sFonte        = 'func_inicial.php?funcao_js=';
	    sFonte       += sTipoRetorno == "LOOKUP" ? 'parent.js_mostrainicial1|v50_inicial' : 'parent.js_abrirConsulta|v50_inicial';
	js_OpenJanelaIframe(sLocal,sObjetoLookUp,sFonte,sTituloJanela,lMostrarJanela);
	return sFonte;
}
function js_mostrainicial(chave,erro){
  if(erro==true){
    alert(_M('tributario.juridico.jur3_emiteinicial002.inicial_invalida'));
    document.form1.v50_inicial.focus(); 
    document.form1.v50_inicial.value=""; 
  }  
    document.form1.v50_inicial.value = chave;
    db_iframe_inicial.hide();
}
function js_mostrainicial1(chave1){
    document.form1.v50_inicial.value = chave1;
    db_iframe_inicial.hide();
}
  function js_pesquisav56_codsit(mostra){
    if(mostra==true){
      db_iframe.jan.location.href = 'func_situacao.php?funcao_js=parent.js_mostrasituacao1|0|1';
      db_iframe.mostraMsg();
      db_iframe.show();
      db_iframe.focus();
    }else{
      db_iframe.jan.location.href = 'func_situacao.php?pesquisa_chave='+document.form1.v56_codsit.value+'&funcao_js=parent.js_mostrasituacao';
    }
  }
  function js_mostrasituacao(chave,erro){
    document.form1.v52_descr.value = chave; 
    if(erro==true){ 
      document.form1.v56_codsit.focus(); 
      document.form1.v56_codsit.value = ''; 
    }
  }
  function js_mostrasituacao1(chave1,chave2){
    document.form1.v56_codsit.value = chave1;
    document.form1.v52_descr.value = chave2;
    db_iframe.hide();
  }
function js_pesquisav54_codlocal(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_localiza.php?funcao_js=parent.js_mostralocaliza1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_localiza.php?pesquisa_chave='+document.form1.v54_codlocal.value+'&funcao_js=parent.js_mostralocaliza';
  }
}
function js_mostralocaliza(chave,erro){
  document.form1.v54_descr.value = chave; 
  if(erro==true){ 
    document.form1.v54_descr.focus(); 
    document.form1.v54_descr.value = ''; 
  }
}
function js_mostralocaliza1(chave1,chave2){
  document.form1.v54_codlocal.value = chave1;
  document.form1.v54_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisav50_advog(mostra){
  if(mostra==true){
      db_iframe.jan.location.href = 'func_advog.php?funcao_js=parent.js_mostraAdvogado1|0|z01_nome';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_advog.php?pesquisa_chave='+document.form1.v50_advog.value+'&funcao_js=parent.js_mostraAdvogado';
  }
}
function js_mostraAdvogado(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.v50_advog.focus(); 
    document.form1.v50_advog.value = ''; 
  }
}
function js_mostraAdvogado1(chave1,chave2){
  document.form1.v50_advog.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
//----------------------------------------------------------------------------------------------------------------------------------------------
function js_pesquisav70_codforo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_processoforo','func_processoforo.php?funcao_js=parent.js_mostracodforo|v70_codforo|v71_inicial','Pesquisa',true);
  }else{
    if(document.form1.v70_codforo.value!=""){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_processoforo','func_processoforo.php?chave_v70_codforo='+document.form1.v70_codforo.value+'&funcao_js=parent.js_mostracodforo1|v70_codforo','Pesquisa',false);
    }
  }
}
function js_mostracodforo(chave,chave1){  
  document.form1.v70_codforo.value = chave;
  document.form1.v50_inicial.value = chave1;
  db_iframe_processoforo.hide();   
}
function js_mostracodforo1(chave,erro,chave1){
  document.form1.v70_codforo.value = chave; 
  if(erro==true){ 
    
  }else{
  	//document.form1.v50_inicial.value = chave1;    
  }
}

function js_pesquisaCgm(mostra) {

  var cgm = document.form1.v58_numcgm.value;
  if (mostra == true) {
  
    var sUrl = 'func_nome.php?funcao_js=parent.js_mostracgm|0|1';
    js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', true);
  } else {
  
    if (cgm != "") {
    
      var sUrl = 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1';
      js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', false);
    } else {
    
      document.form1.v58_numcgm.value = '';
      document.form1.inicialnomes_z01_nome.value         = '';
    }  
  }
}

function js_mostracgm(chave1, chave2) {

  document.form1.v58_numcgm.value  = chave1;
  document.form1.inicialnomes_z01_nome.value    = chave2;
  db_iframe_numcgm.hide();
}

function js_mostracgm1(erro,chave) {

  document.form1.inicialnomes_z01_nome.value = chave; 
  if (erro == true) { 
  
    document.form1.v58_numcgm.focus(); 
    document.form1.v58_numcgm.value = '';
  }
}
//----------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Abre tela de consulta dos dados da incial 
 */

function js_abrirConsulta(iCodigoIncial) {
	
	js_OpenJanelaIframe('','db_consulta_inicial_iframe',  'func_inicialmovcert.php?v50_inicial=' + iCodigoIncial + '&funcao_js=parent.js_oculta','Consulta Inicial: '+iCodigoIncial,true );
}

function js_fecharConsulta() {
  db_consulta_inicial_iframe.hide();
}
</script>
<?php
  $func_iframe = new janela('db_iframe','');
  $func_iframe->posX=1;
  $func_iframe->posY=20;
  $func_iframe->largura=780;
  $func_iframe->altura=430;
  $func_iframe->titulo='Pesquisa';
  $func_iframe->iniciarVisivel = false;
  $func_iframe->mostrar();
  if(isset($invalido)){
    db_msgbox(_M('tributario.juridico.jur3_emiteinicial002.nao_existe'));
    
  }
?>
</body>
</html>

<script>

$("v50_inicial").addClassName("field-size2");
$("v70_codforo").addClassName("field-size9");
$("v58_numcgm").addClassName("field-size2");
$("inicialnomes_z01_nome").addClassName("field-size7");
$("v56_codsit").addClassName("field-size2");
$("v52_descr").addClassName("field-size7");
$("v50_advog").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("v54_codlocal").addClassName("field-size2");
$("v54_descr").addClassName("field-size7");
$("v53_codvara").addClassName("field-size2");
$("v53_descr").addClassName("field-size7");
$("datainicial").addClassName("field-size2");
$("datafinal").addClassName("field-size2");

</script>
