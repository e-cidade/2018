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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");

require_once modification("std/DBDate.php");

require_once modification("classes/db_inicial_classe.php");

require_once modification("dbforms/db_funcoes.php");

db_app::import('exceptions.*');

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

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

$db_opcao = 1;

$oDaoInicial = new cl_inicial();
$oDaoInicial->rotulo->label();

$clrotulo  = new rotulocampo;
$clrotulo->label("v50_data");
$clrotulo->label("v50_advog");
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_situacao");
$clrotulo->label("v52_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v53_codvara");
$clrotulo->label("v54_codlocal");
$clrotulo->label("v54_descr");
$clrotulo->label("v56_codsit");
$clrotulo->label("v70_codforo");
$clrotulo->label("z01_nome");
$clrotulo->label("v58_numcgm");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
<style>
<!--

 #selTipo {
   width: 92px !important;
 } 
  #v70_codforo {
   width: 467px !important;
 } 

-->
</style>
</head>
<body style="border: 0px solid #000;">

<table align="center">
	<tr>
		<td>
			<form name="form2" id="formInicial" method="post" action="">

				<fieldset style="margin:0 auto;">

					<legend><b>Filtro Pesquisa Inicial: </b></legend>

					<!-- INICIO Tabela --> 

					<table style="margin:0px  auto;" >
						<tr>
							<td nowrap title="<?=@$Tv50_inicial?>">
								<?php echo $Lv50_inicial; ?>
							</td>
							<td> 
								<?php
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
									db_input('v70_codforo',60,$Iv70_codforo,true,'text',1," onchange='js_pesquisaCodigoForo(false);'");
								?>
							</td>
						</tr>

						<tr>
							<td nowrap title="<?=@$Tv58_numcgm?>">
								<?php db_ancora(@$Lv58_numcgm," js_pesquisacgm(true); ",1); ?>
							</td>
							<td> 
								<?php
									db_input('v58_numcgm', 10, $Iv58_numcgm, true, 'text', 4, " onchange='js_pesquisacgm(false);'");
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
							<td nowrap title="Tipo"><b>Tipo</b></td>
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
								&nbsp;<b>até<b>
								<?php
									db_inputdata('datafinal', $diaDataFinal, $mesDataFinal, $anoDataFinal, true, 'text', $db_opcao,"");
								?>
							</td>
						</tr>
					</table>	

					<!--   FIM Tabela --> 

				</fieldset>

				<br />

				<center>
					<input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar" />
					<input name="limpar" type="button" id="limpar" value="Limpar" onClick="return js_limpar();" />
					<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_inicial.hide();">
				</center>

			</form>
		</td>

		</tr>

		<tr id="tabelaPesquisa">
			<td>
				<fieldset>
					<table align="center">
						<tr> 
							<td align="center" valign="top"> 
							<?php
							if ( !isset($pesquisa_chave) ) {

								if (isset($campos)==false) {

									$campos = "distinct inicial.*";

									if (file_exists("funcoes/db_func_inicial.php")) {
										include(modification("funcoes/db_func_inicial.php"));
									}
								}
								
								if (isset($verif_proc) && (trim($verif_proc) != "")) {
									
									$sWhere = " v50_situacao = 1 and v50_instit = ".db_getsession('DB_instit')." and v50_inicial not in ( select v71_inicial from processoforoinicial where v71_anulado is false )";
									$sql    = $oDaoInicial->sql_query_file("",$campos,"v50_inicial", $sWhere);
								} else if (isset($chave_v50_inicial) && (trim($chave_v50_inicial) != "")) {
									
									$sWhere = " v50_inicial = $chave_v50_inicial and v50_situacao = 1 and v50_instit = ".db_getsession('DB_instit');
									$sql    = $oDaoInicial->sql_query($chave_v50_inicial,$campos,"v50_inicial",$sWhere);
								} else if (isset($chave_v50_data) && (trim($chave_v50_data) != "")) {
									
									$chave_v50_data = implode("-",array_reverse(explode("/",trim($chave_v50_data))));
									$sWhere = "  v50_situacao = 1 and v50_instit = ".db_getsession('DB_instit')." and v50_data = '{$chave_v50_data}' ";
									$sql    = $oDaoInicial->sql_query("",$campos,"v50_data", $sWhere);
								} else {

									if ( isset($pesquisar) || isset($filtroquery) ) {

										/**
										 * Filtros de pesquisa da inicial
										 */	 
										$aSqlFiltros = array();

										/**
										 * Pesquisa por código do foro
										 */	 
										if (  empty($oPost->v70_codforo) == false ) {
											$aSqlFiltros[] = "v70_codforo ilike '%{$oPost->v70_codforo}%'";
										}

										/**
										 * Pesquisa pelo cgm na tabela inicialnomes
										 */	 
										if ( empty($oPost->v58_numcgm) == false ) {
											$aSqlFiltros[] = "v58_numcgm = {$oPost->v58_numcgm}";
										}

										/**
										 * Pesquisa por código de situação
										 */	 
										if ( empty($oPost->v56_codsit) == false ) {
											$aSqlFiltros[] = "v56_codsit = '{$oPost->v56_codsit}'";
										}

										/**
										 * Pesquisa por código do advogado
										 */	 
										if ( empty($oPost->v50_advog) == false ) {
											$aSqlFiltros[] = "v50_advog = '{$oPost->v50_advog}'";
										}

										/**
										 * Pesquisa por código de localização
										 */	 
										if ( empty($oPost->v54_codlocal) == false ) {
											$aSqlFiltros[] = "v54_codlocal = '{$oPost->v54_codlocal}'";
										}

										/**
										 * Pesquisa por código da vara
										 */	 
										if ( empty($oPost->v53_codvara) == false ) {
											$aSqlFiltros[] = "processoforo.v70_vara = '{$oPost->v53_codvara}'";
										}

										/**
										 * Data inicial
										 * Pesquisa iniciais com data maior ou igual a data inicial
										 */	 
										if ( empty($oPost->datainicial) == false ) {
											$aSqlFiltros[] = "v50_data >= '".$oDataInicial->getDate()."'";
										}

										/**
										 * Data final
										 * Pesquisa iniciais com data menor ou igual a data final
										 */	 
										if ( empty($oPost->datafinal) == false ) {
											$aSqlFiltros[] = "v50_data <= '".$oDataFinal->getDate()."'";
										}

										/**
										 * Pesquisar pelo numero da inicial
										 * Se não for vazio, pesquisa somente pelo código da inicial e ignora filtros anteriores
										 */	 
										if ( empty($oPost->v50_inicial) == false ) {
											$aSqlFiltros = array("v50_inicial = '{$oPost->v50_inicial}'");
										}

										/**
										 * Situacao da inicial
										 * 1 - Ativa 
										 * 2 - Anulada
										 */	 
										if ( empty($oPost->v50_situacao) == false ) {
											$aSqlFiltros[] = "v50_situacao = '{$oPost->v50_situacao}'";
										}

										$sCampos  = "inicial.v50_inicial,  ";
										$sCampos .= "z01_nome as v50_advog,";
										$sCampos .= "v70_codforo,          ";
										$sCampos .= "inicial.v50_data,     ";
										$sCampos .= "db_usuarios.nome,     ";
										$sCampos .= "v70_vara,             ";
										$sCampos .= "v53_descr             ";

										$iInstituicao = db_getsession('DB_instit');
										$sOrdem       = 'v50_inicial';
										$sWhere       = implode(' and ', $aSqlFiltros);

										$sSql     = $oDaoInicial->sql_queryFiltroInicias($iInstituicao, $sCampos, $sOrdem, $sWhere);

										db_lovrot($sSql, 15, "()", "", $funcao_js);
									} 
								}
							} else {

								if ($pesquisa_chave != null && $pesquisa_chave != "") {
									
									if (isset($verif_proc) && (trim($verif_proc) != "")) {
										
										$sWhere      = " v50_situacao = 1 and v50_inicial = $pesquisa_chave and v50_instit = ".db_getsession('DB_instit')." and v50_inicial not in ( select v71_inicial from processoforoinicial where v71_anulado is false )";
										$sSqlInicial = $oDaoInicial->sql_query($pesquisa_chave,"*",null,$sWhere);
										$result      = $oDaoInicial->sql_record($sSqlInicial);
									}else{	
										$result = $oDaoInicial->sql_record($oDaoInicial->sql_query($pesquisa_chave,"*",null," v50_situacao = 1 and v50_inicial = $pesquisa_chave and v50_instit = ".db_getsession('DB_instit')));
									} 
									if ($oDaoInicial->numrows!=0) {
										db_fieldsmemory($result,0);
										echo "<script>".$funcao_js."('$v50_inicial',false);</script>";
									}else{
									 echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
									}
								}else{
								 echo "<script>".$funcao_js."('',false);</script>";
								}
							}
						?>
				</table>
			</fieldset>	
		</td>
	</tr>
</table>
</body>
</html>

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
	
	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa processo foro', lMostra, 0, 0, sWidth, sHeight);

	return true;
}

/**
 * Codigo do processo do foro
 * Retorno da loockup de pesquisa quando origem for o ANCORA
 */
function js_pesquisaCodigoForoAncora(iCodforo, iInicial) {  

  $('v70_codforo').value = iCodforo;

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

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa situação', lMostra, 0, 0, sWidth, sHeight);

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

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa advogado', lMostra, 0, 0, sWidth, sHeight);

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

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa localização', lMostra, 0, 0, sWidth, sHeight);

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

	js_OpenJanelaIframe('', sIframe, sArquivoPesquisa, 'Pesquisa vara', lMostra, 0, 0, sWidth, sHeight);

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
 * -----------------------------------------------------------------------------------------------------
 * FUNÇÕES PARA PESQUISAR CGM 
 * -------------
 */	 

function js_pesquisacgm(mostra) {

  var cgm = $F('v58_numcgm');

  if (mostra == true) {
  
    var sUrl = 'func_nome.php?funcao_js=parent.js_mostracgm|0|1';
    js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', true);
  } else {
  
    if (cgm != "") {
    
      var sUrl = 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1';
      js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', false);
    } else {
    
      $('v58_numcgm').value = '';
      $('z01_nome').value   = '';
    }  
  }
}

function js_mostracgm(chave1, chave2) {

  $('v58_numcgm').value						 = chave1;
  $('inicialnomes_z01_nome').value = chave2;

  db_iframe_numcgm.hide();
}

function js_mostracgm1(erro, chave) {

  $('inicialnomes_z01_nome').value = chave; 

  if (erro == true) { 
  
    $('v58_numcgm').focus(); 
    $('v58_numcgm').value = '';
  }
}

/**
 * -------
 * FUNÇÕES PARA PESQUISAR CGM 
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
</script>
<?php 
if ( !isset($pesquisar) && !isset($filtroquery) ) {

	echo '<script>';
	echo '$("tabelaPesquisa").style.display = "none"';
	echo '</script>';
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
