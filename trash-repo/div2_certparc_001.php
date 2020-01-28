<?
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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oGet     = db_utils::postMemory($_GET);

$clRotulo = new rotulocampo();

$clRotulo->label("v13_certid");
$clRotulo->label("v14_vlrhis");

if (isset($oGet->iCdaParcelIni) && isset($oGet->iCdaParcelFim)) {
	$v13_certid       = $oGet->iCdaParcelIni;
	$v13_certid_final = $oGet->iCdaParcelFim;
}

?>
  
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?php 
		db_app::load('scripts.js');
		db_app::load('prototype.js');
		db_app::load('estilos.css');
	?>
</head>
<body bgcolor="#CCCCCC">

<form class="container" name="form1" id="form1">

<fieldset>
	<legend>Certid�o de Parcelamento de Divida</legend>
	
	<table class="form-container">
		<tr>
			<td>	
				<? 
					db_ancora($Lv13_certid, 'js_pesquisaCertidao(true, true)', 1);
				?>
			</td>
			<td>
				<?
					db_input('v13_certid', 10, $Iv13_certid, true, 'text', 1, 'onchange="js_validaCDA()"');
					
					db_ancora('<strong>�</strong>', 'js_pesquisaCertidao(true, false)', 1);
					
					db_input('v13_certid', 10, $Iv13_certid, true, 'text', 1, null, 'v13_certid_final');
				?>
			</td>
		</tr>
		
		<tr>
			<td>	
				Valores:
			</td>
			<td>
				<?
					db_input('v14_vlrhis', 10, $Iv13_certid, true, 'text', 1);
					
					echo '<strong>�</strong>';
					
					db_input('v14_vlrhis', 10, $Iv13_certid, true, 'text', 1, null, 'v14_vlrhis_maximo');
				?>
			</td>
		</tr>
		
		<tr>
			<td>	
				Reemiss�o:
			</td>
			<td>
				<?
					db_select('reemissao', array('t' => 'SIM', 'f' => 'N�O'), true, 1, '');
				?>
			</td>
		</tr>
	</table>
</fieldset>
	<input type="button" name="processar" id="processar" value="Processar" onclick="js_processar()" />


<?
  if (!isset($oGet->iCdaParcelIni) && !isset($oGet->iCdaParcelFim)) {  
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }  
?>

</form>
<script>

function js_validaCDA() {

  var iCertidaoInicial = document.form1.v13_certid.value;
  var iCertidaoFinal   = document.form1.v13_certid_final.value;

  if (iCertidaoFinal == '' || iCertidaoFinal < iCertidaoInicial) {

    document.form1.v13_certid_final.value = iCertidaoInicial;
    
  }
  
}

function js_processar() {

  var iCertidaoInicial = document.form1.v13_certid.value;
  var iCertidaoFinal   = document.form1.v13_certid_final.value;
  var nValorMinimo     = document.form1.v14_vlrhis.value;
  var nValorMaximo     = document.form1.v14_vlrhis_maximo.value;
  var lReemissao       = document.form1.reemissao.value;
	var sUrl             = '';
  
  if(iCertidaoInicial == '') {
    alert('N�mero da certid�o n�o informado.');
    return false;
  }
  

  sUrl += 'div2_certidaodivida002.php';
  sUrl += '?certid='      + iCertidaoInicial;
  sUrl += '&certid1='     + iCertidaoFinal  ;
  sUrl += '&valorminimo=' + nValorMinimo    ;
  sUrl += '&valormaximo=' + nValorMaximo	  ;
  sUrl += '&reemissao='   + lReemissao			;
  sUrl += '&tipo=1'												  ;

  jan = window.open(sUrl, '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');

	jan.moveTo(0,0);
    
}

function js_pesquisaCertidao(lMostra, lInicial){
  
  if(lMostra == true){

    sFuncao = lInicial == true ? 'js_mostraTermoInicial' : 'js_mostraTermoFinal';
    
    js_OpenJanelaIframe('top.corpo','db_iframe_certidao','func_certter.php?funcao_js=parent.'+sFuncao+'|0','Pesquisa',true);
    
  }
  
}

function js_mostraTermoInicial(iCertidao) {

  document.form1.v13_certid.value       = iCertidao;

  if(document.form1.v13_certid_final.value == '' || document.form1.v13_certid_final.value < iCertidao) {
  	document.form1.v13_certid_final.value = iCertidao;
  }
    
  document.form1.v13_certid_final.focus();
  db_iframe_certidao.hide();
  
}

function js_mostraTermoFinal(iCertidao) {

	document.form1.v13_certid_final.value = iCertidao;
  
	db_iframe_certidao.hide();
}

</script>

</body>
</html>  
<script>

$("v13_certid").addClassName("field-size2");
$("v13_certid_final").addClassName("field-size2");
$("v14_vlrhis").addClassName("field-size2");
$("v14_vlrhis_maximo").addClassName("field-size2");
$("reemissao").setAttribute("rel","ignore-css");
$("reemissao").addClassName("field-size2");

</script>