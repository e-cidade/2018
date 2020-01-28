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

//MODULO: pessoal
include(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhlotavinc->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("o55_descr");
$clrotulo->label("r70_descr");
$clrotulo->label("o15_descr");
$clrotulo->label("o54_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o52_descr");

if ( isset($opcao) && $opcao == "alterar" ) {
  $db_opcao = 2;
} elseif ( isset($opcao) && $opcao == "excluir" ) {
  $db_opcao = 3;
} elseif ( isset($db_opcaoal) ) {

	if ( $db_opcaoal == "false" ) {

    $db_opcao = 3;
    $opcoesae = 4;
  }
}
?>
<form name="form1" method="post" action="">
	<center>
		<table border="0" >

			<tr>
				<td nowrap title="<?=@$Trh25_codlotavinc?>">
					<?php db_ancora(@$Lrh25_codlotavinc, "", 3); ?>
				</td>
				<td> 
					<?php db_input('rh25_codlotavinc', 8, $Irh25_codlotavinc, true, 'text', 3); ?>
				</td>
			</tr>

			<tr>
				<td nowrap title="<?=@$Trh25_codigo?>">
					<?php db_ancora(@$Lrh25_codigo, "", 3); ?>
				</td>
				<td> 
					<?php 
						db_input('rh25_codigo', 8, $Irh25_codigo, true, 'text', 3);
						db_input('rh25_descr', 50, $Ir70_descr, true, 'text', 3);
					?>
			  </td>
		  </tr>

			<tr>
				<td nowrap title="<?=@$Trh25_projativ?>">
					<?php db_ancora(@$Lrh25_projativ, "js_pesquisarh25_projativ(true)", $db_opcao); ?>
				</td>
				<td> 
					<?php
					  if (empty($rh25_anousu)) {
							$rh25_anousu = db_getsession("DB_anousu");
						}
						if(isset($rh25_projativ) && trim($rh25_projativ)!="" && isset($rh25_anousu) && trim($rh25_anousu)!=""){
							$result_projativ = $clorcprojativ->sql_record($clorcprojativ->sql_query_file($rh25_anousu, $rh25_projativ, "o55_descr"));
							if($clorcprojativ->numrows>0){
								db_fieldsmemory($result_projativ, 0);
							}
						}
						db_input('rh25_projativ', 8, $Irh25_projativ, true, 'text', $db_opcao, "onchange='js_pesquisarh25_projativ(false)'");
						db_input('rh25_anousu', 4, $Irh25_anousu, true, 'text', 3);
						db_input('o55_descr', 42, $Io55_descr, true, 'text', 3);
					?>
				</td>
			</tr>

			<tr>
				<td nowrap title="<?=@$Trh25_recurso?>">
					<?php
						if(!isset($rh25_recurso)){
							$rh25_recurso = 1;
						}

						$result_recurso = $clorctiporec->sql_record($clorctiporec->sql_query_file($rh25_recurso, "o15_descr"));
						if($clorctiporec->numrows >0){
							db_fieldsmemory($result_recurso, 0);
						}
						db_ancora(@$Lrh25_recurso, "js_pesquisarh25_recurso(true)", $db_opcao);
					?>
				</td>
				<td> 
					<?php 
						db_input('rh25_recurso', 8, $Irh25_recurso, true, 'text', $db_opcao, "onchange='js_pesquisarh25_recurso(false)'"); 
						db_input('o15_descr', 50, $Io15_descr, true, 'text', 3);
					?>
				</td>
			</tr>

			<tr>
				<td nowrap title="<?php echo $Trh25_programa; ?>">
					<?php db_ancora($Lrh25_programa, 'js_pesquisa_programa(true)', $db_opcao); ?>
				</td>
				<td>
					<?php db_input('rh25_programa', 8, $Irh25_programa, true, 'text', $db_opcao, "onchange='js_pesquisa_programa(false)'"); ?>
					<?php db_input('o54_descr', 50, $Io54_descr, true, 'text', 3); ?>
				</td>
			</tr>

			<tr>
				<td nowrap title="<?php echo $Trh25_funcao; ?>">
					<?php db_ancora($Lrh25_funcao, 'js_pesquisa_funcao(true)', $db_opcao); ?>
				</td>
				<td>
					<?php db_input('rh25_funcao', 8, $Irh25_funcao, true, 'text', $db_opcao, "onchange='js_pesquisa_funcao(false)'"); ?>
					<?php db_input('o52_descr', 50, $Io52_descr, true, 'text', 3); ?>
				</td>
			</tr>

			<tr>
				<td nowrap title="<?php echo $Trh25_subfuncao; ?>">
					<?php db_ancora($Lrh25_subfuncao, 'js_pesquisa_subfuncao(true)', $db_opcao); ?>
				</td>
				<td>
					<?php db_input('rh25_subfuncao', 8, $Irh25_subfuncao, true, 'text', $db_opcao, "onchange='js_pesquisa_subfuncao(false)'"); ?>
					<?php db_input('o53_descr', 50, $Io53_descr, true, 'text', 3); ?>
				</td>
			</tr>

			<tr>
				<td nowrap title="<?=@$Trh25_vinculo?>">
					<?php db_ancora(@$Lrh25_vinculo, "", 3); ?>
				</td>
				<td> 
					<?php
						$arr_vinculo =  Array();
						$arr_vinculo["A"] = "Ativo";
						$arr_vinculo["I"] = "Inativo";
						$arr_vinculo["P"] = "Pensionista";
						db_select("rh25_vinculo", $arr_vinculo, true, $db_opcao);

						db_input('opcao', 5, 0, true, 'hidden', 3);
						db_input('chave', 5, 0, true, 'hidden', 3);
						db_input('chave1', 5, 0, true, 'hidden', 3);
						db_input('chave2', 5, 0, true, 'hidden', 3);
						db_input('chave3', 5, 0, true, 'hidden', 3);
						db_input('chave4', 5, 0, true, 'hidden', 3);
						db_input('chave5', 5, 0, true, 'hidden', 3);
						db_input('chave6', 5, 0, true, 'hidden', 3);
						db_input('chave7', 5, 0, true, 'hidden', 3);
						db_input('opcaoiframe', 5, 0, true, 'hidden', 3);
						db_input('defaultifra', 5, 0, true, 'hidden', 3);
					?>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="center">
					<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
					<?php 
						$result_exist = $clrhlotavinc->sql_record($clrhlotavinc->sql_query_file(null, "rh25_codlotavinc", "", "rh25_codigo=".@$rh25_codigo));
						
						if($clrhlotavinc->numrows>0 && isset($opcao) && $opcao!="excluir") {
							echo '<input name="cadele" type="button" id="cadele" value="Elementos secundários" onclick="js_cadele();" >&nbsp;';	
						}

						if($db_opcao!=1){
							echo '<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" >&nbsp;';
							echo '<input name="importar" type="button" id="importar" value="Importar elementos" onclick="js_importa();">&nbsp;';
						}
					?>
				</td>							
			</tr>

		</table>

		<table  width="90%" height="70%" border=0>

			<tr>
				<td valign="top"  align="center" width="100%" height="100%">
					<?php
						$where = " rh25_codigo= ".@$rh25_codigo ;
						if(isset($rh25_codlotavinc) && trim($rh25_codlotavinc)!=""){
							$where .= " and rh25_codlotavinc <> $rh25_codlotavinc ";
						}
						$chavepri= array("rh25_codigo"=>@$rh25_codigo, "rh25_codlotavinc"=>@$rh25_codlotavinc);
						$cliframe_alterar_excluir->chavepri=$chavepri;
						$cliframe_alterar_excluir->sql           = $clrhlotavinc->sql_query(null, "case when rh25_vinculo = 'I' then 'Inativo' else (case when rh25_vinculo = 'A' then 'Ativo' else 'Pensionista' end) end as rh25_vinculo,  o55_descr, rh25_anousu, rh25_codigo, rh25_projativ, rh25_codlotavinc, o15_descr", "rh25_codigo, rh25_codlotavinc", $where);
						$cliframe_alterar_excluir->campos        = "rh25_codlotavinc, rh25_projativ, rh25_anousu, o55_descr, o15_descr, rh25_vinculo";
						$cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
						$cliframe_alterar_excluir->iframe_height = "250";
						$cliframe_alterar_excluir->iframe_width  = "90%";
						$cliframe_alterar_excluir->opcoes        = $opcoesae;
						$cliframe_alterar_excluir->iframe_alterar_excluir(1);
					?>
				</td>
			</tr>

		</table>

	</center>
</form>

<script>
function js_submitaiframe(projativ,  anousu) {

  db_iframe_cadele.hide();
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_cadele', 'pes1_rhlotavincele001.php?chavepesquisa='+projativ+js_geraQueryStringElementosSecundarios() + '&chavepesquisa2='+anousu, 'Pesquisa', true, '0');
}

function js_cadele() {

  document.form1.opcaoiframe.value = "";
  document.form1.defaultifra.value = "";
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_cadele', 'pes1_rhlotavincele001.php?lotacao='+document.form1.rh25_codigo.value+'&lotavinc='+document.form1.rh25_codlotavinc.value, 'Pesquisa', true, '0');
  //+'&registro='+document.form1.rh25_codlotavinc.value
}

function js_cancelar() {

  document.location.href = "pes1_rhlotavinc001.php?chavepesquisa="+document.form1.rh25_codigo.value;
  /*
  var opcao = document.createElement("input");
  opcao.setAttribute("type", "hidden");
  opcao.setAttribute("name", "incluirnovo");
  opcao.setAttribute("value", "true");
  document.form1.appendChild(opcao);
  document.form1.submit();
  */
}

function js_importa() {
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_rhlotavinc', 'func_rhlotavinc.php?filtroano=true&funcao_js=parent.js_confirmaimporta|rh25_codlotavinc', 'Pesquisa', true, 0);
}

function js_confirmaimporta(chave) {

  db_iframe_rhlotavinc.hide();

  if(confirm("Deseja importar elementos secundários do vinculo "+chave+"?\n\n\nOBS.: Elementos e projetos/atividades secundários serão excluídos.")){

    obj=document.createElement('input');
    obj.setAttribute('name', 'importar');
    obj.setAttribute('type', 'hidden');
    obj.setAttribute('value', chave);
    document.form1.appendChild(obj);
    document.form1.submit();
  }
}

function js_pesquisarh25_recurso(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_orctiporec', 'func_orctiporec.php?funcao_js=parent.CurrentWindow.corpo.iframe_rhlotavinc.js_mostraorctiporec|o15_codigo|o15_descr', 'Pesquisa', true, '0');
  }else{

     if(document.form1.rh25_recurso.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_orctiporec', 'func_orctiporec.php?pesquisa_chave='+document.form1.rh25_recurso.value+'&funcao_js=parent.CurrentWindow.corpo.iframe_rhlotavinc.js_mostraorctiporec1', 'Pesquisa', false, '0');
     }else{
       document.form1.rh25_recurso.value = '';
     }
  }
}

function js_mostraorctiporec(chave,  chave1) {

  document.form1.rh25_recurso.value = chave;
  document.form1.o15_descr.value = chave1;
  db_iframe_orctiporec.hide();
}

function js_mostraorctiporec1(chave,  erro) {

  document.form1.o15_descr.value = chave;
  if (erro == true) {

    document.form1.rh25_recurso.value = "";
    document.form1.rh25_recurso.focus();
  }
}

function js_pesquisarh25_projativ(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_orcprojativ', 'func_orcprojativ.php?funcao_js=parent.CurrentWindow.corpo.iframe_rhlotavinc.js_mostraprojativ|o55_descr|o55_projativ&anousu=<?=(db_getsession("DB_anousu"))?>&rh=true', 'Pesquisa', true, '0');
  } else {
		
    if(document.form1.rh25_projativ.value!=""){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_orcprojativ', 'func_orcprojativ.php?pesquisa_chave='+document.form1.rh25_projativ.value+'&funcao_js=parent.CurrentWindow.corpo.iframe_rhlotavinc.js_mostraprojativ1&rh=true', 'Pesquisa', false, '0');
    } else {

      document.form1.o55_descr.value = "";
      document.form1.rh25_projativ.value = "";
      document.form1.rh25_projativ.focus();
    }
  }
}
function js_mostraprojativ(chave, chave1){
  document.form1.o55_descr.value = chave;
  document.form1.rh25_projativ.value = chave1;
  db_iframe_orcprojativ.hide();

 /* 
  var opcao = document.createElement("input");
  opcao.setAttribute("type", "hidden");
  opcao.setAttribute("name", "incluirnovo");
  opcao.setAttribute("value", "true");
  document.form1.appendChild(opcao);

  
  document.form1.submit();
 */
}

function js_mostraprojativ1(chave,  erro) {

  document.form1.o55_descr.value = chave;
  if (erro == true) {

    document.form1.rh25_projativ.value = "";
    document.form1.rh25_projativ.focus();
 /* 
    var opcao = document.createElement("input");
    opcao.setAttribute("type", "hidden");
    opcao.setAttribute("name", "incluirnovo");
    opcao.setAttribute("value", "true");
    document.form1.appendChild(opcao);
    
    document.form1.submit();
 */
  }
}

// funcões chamadas do iframe db_iframe_cadele
function js_mostraprojativ2(chave,  chave1) {

  document.form1.chave.value = chave;
  document.form1.chave1.value = chave1;
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_cadele', 'pes1_rhlotavincele001.php?lotacao='+document.form1.rh25_codigo.value+js_geraQueryStringElementosSecundarios() + '&lotavinc='+document.form1.rh25_codlotavinc.value+'&ch='+chave+'&ch1='+chave1+'&ch2='+document.form1.chave2.value+'&ch3='+document.form1.chave3.value+'&ch4='+document.form1.chave4.value+'&ch5='+document.form1.chave5.value+'&ch6='+document.form1.chave6.value+'&ch7='+document.form1.chave7.value+'&opcao='+document.form1.opcaoiframe.value+"&default="+document.form1.defaultifra.value+'&npass=true', 'Pesquisa', true, '0');
  db_iframe_orcprojativ.hide();
}                                                                                                                                                                                            

function js_mostraorcelemento(chave2,  chave3) {                                                                                                                                                

  if(chave3!="true"){
    chave3 = "1";
  }

  document.form1.chave2.value = chave2;
  document.form1.chave3.value = chave3;
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_cadele', 'pes1_rhlotavincele001.php?lotacao='+document.form1.rh25_codigo.value+js_geraQueryStringElementosSecundarios() + '&lotavinc='+document.form1.rh25_codlotavinc.value+'&ch='+document.form1.chave.value+'&ch1='+document.form1.chave1.value+'&ch2='+chave2+'&ch3='+chave3+'&ch4='+document.form1.chave4.value+'&ch5='+document.form1.chave5.value+'&ch6='+document.form1.chave6.value+'&ch7='+document.form1.chave7.value+'&opcao='+document.form1.opcaoiframe.value+"&default="+document.form1.defaultifra.value+'&npass=true', 'Pesquisa', true, '0');
  db_iframe_orcelemento.hide();
}

function js_mostraorcelemento1(chave4,  chave5) {

  if (chave5 != "true") {
    chave5 = "1";
  }
  document.form1.chave4.value = chave4;                                                                                                                                      
  document.form1.chave5.value = chave5;                                                                                                                                      
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 'db_iframe_cadele', 'pes1_rhlotavincele001.php?lotacao='+document.form1.rh25_codigo.value+js_geraQueryStringElementosSecundarios() + '&lotavinc='+document.form1.rh25_codlotavinc.value+'&ch='+document.form1.chave.value+'&ch1='+document.form1.chave1.value+'&ch2='+document.form1.chave2.value+'&ch3='+document.form1.chave3.value+'&ch4='+chave4+'&ch5='+chave5+'&ch6='+document.form1.chave6.value+'&ch7='+document.form1.chave7.value+'&opcao='+document.form1.opcaoiframe.value+"&default="+document.form1.defaultifra.value+'&npass=true', 'Pesquisa', true, '0');
  db_iframe_orcelemento.hide();
											
}

function js_mostrarecurso(chave6,  chave7) {
	
  if (chave7 != "true") {
    chave7 = "1";
  }
  document.form1.chave6.value = chave6;
  document.form1.chave7.value = chave7;

	js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlotavinc', 
		                  'db_iframe_cadele', 
											'pes1_rhlotavincele001.php?lotacao='+document.form1.rh25_codigo.value + 
											'&lotavinc='+document.form1.rh25_codlotavinc.value + 
											'&ch='+document.form1.chave.value + 
											'&ch1='+document.form1.chave1.value +
											'&ch2='+document.form1.chave2.value + 
											'&ch3='+document.form1.chave3.value + 
											'&ch4='+document.form1.chave4.value +
											'&ch5='+document.form1.chave5.value +
											'&ch6='+chave6 +
											'&ch7='+chave7 +
											js_geraQueryStringElementosSecundarios() +
											'&opcao='+document.form1.opcaoiframe.value +
											"&default="+document.form1.defaultifra.value +
											'&npass=true', 
											'Pesquisa', 
											true, 
											'0');
  db_iframe_orctiporec.hide();

}

/**
 * -------------------------------------------------
 * Pesquisa programa - rh25_programa 
 * -------------------------------------------------
 */
function js_pesquisa_programa(lMostra) {

	/**
	 * Ancora
	 */
	if (lMostra) {

		js_OpenJanelaIframe('', 
												'db_iframe_orcprograma', 
												'func_orcprograma.php?funcao_js=parent.js_preenchePesquisaProgramaAncora|o54_programa|o54_descr', 
												'Pesquisa', 
												true);
		return;
	}

	/**
	 * Input change
	 */	 
	var iPrograma = $('rh25_programa').value;

	js_OpenJanelaIframe('', 
											'db_iframe_orcprograma', 
											'func_orcprograma.php?pesquisa_chave=' + iPrograma + '&funcao_js=parent.js_preenchePesquisaProgramaInput', 
											'Pesquisa', 
											false);
}

/**
 * Programa
 * Preenhce campos pela pesquisa do ancora
 */
function js_preenchePesquisaProgramaAncora(iPrograma, sDescricao, lErro) {
	
	if (sDescricao != '') {
		$('o54_descr').value = sDescricao;
	}

	if (iPrograma != '') {
		$('rh25_programa').value = iPrograma;
	}

	if (lErro) {
		$('o54_descr').value = '';
	}

	db_iframe_orcprograma.hide();
}

/**
 * Programa
 * Preenche campo pela pesquisa do input
 */
function js_preenchePesquisaProgramaInput(sDescricao, lErro) {
	
	if (sDescricao != '') {
		$('o54_descr').value = sDescricao;
	}

	if (lErro) {
		$('rh25_programa').value = '';
	}
}

/**
 * -------------------------------------------------
 * Pesquisa subfunção - rh25_subfuncao 
 * -------------------------------------------------
 */
function js_pesquisa_subfuncao(lMostra) {

	/**
	 * Ancora
	 */
	if (lMostra) {

		js_OpenJanelaIframe('', 
												'db_iframe_orcsubfuncao', 
												'func_orcsubfuncao.php?funcao_js=parent.js_preenchePesquisaSubfuncaoAncora|o53_subfuncao|o53_descr', 
												'Pesquisa', 
												true);
		return;
	}

	/**
	 * Input change
	 */	 
	var iSubfuncao = $('rh25_subfuncao').value;

	js_OpenJanelaIframe('', 
											'db_iframe_orcsubfuncao', 
											'func_orcsubfuncao.php?pesquisa_chave=' + iSubfuncao + '&funcao_js=parent.js_preenchePesquisaSubfuncaoInput', 
											'Pesquisa', 
											false);
}

/**
 * Subfunção
 * Preenhce campos pela pesquisa do ancora
 */
function js_preenchePesquisaSubfuncaoAncora(iSubfuncao, sDescricao, lErro) {
	
	if (sDescricao != '') {
		$('o53_descr').value = sDescricao;
	}

	if (iSubfuncao != '') {
		$('rh25_subfuncao').value = iSubfuncao;
	}

	if (lErro) {
		$('o53_descr').value = '';
	}

	db_iframe_orcsubfuncao.hide();
}

/**
 * Subfunção
 * Preenche campo pela pesquisa do input
 */
function js_preenchePesquisaSubfuncaoInput(sDescricao, lErro) {
	
	if (sDescricao != '') {
		$('o53_descr').value = sDescricao;
	}

	if (lErro) {
		$('rh25_subfuncao').value = '';
	}
}

/**
 * -------------------------------------------------
 * Pesquisa função - rh25_funcao
 * -------------------------------------------------
 */
function js_pesquisa_funcao(lMostra) {

	/**
	 * Ancora
	 */
	if (lMostra) {

		js_OpenJanelaIframe('', 
												'db_iframe_orcfuncao', 
												'func_orcfuncao.php?funcao_js=parent.js_preenchePesquisaFuncaoAncora|o52_funcao|o52_descr', 
												'Pesquisa', 
												true);
		return;
	}

	/**
	 * Input change
	 */	 
	var iFuncao = $('rh25_funcao').value;

	js_OpenJanelaIframe('', 
											'db_iframe_orcfuncao', 
											'func_orcfuncao.php?pesquisa_chave=' + iFuncao + '&funcao_js=parent.js_preenchePesquisaFuncaoInput', 
											'Pesquisa', 
											false);
}

/**
 * Função
 * Preenhce campos pela pesquisa do ancora
 */
function js_preenchePesquisaFuncaoAncora(iFuncao, sDescricao, lErro) {
	
	if (sDescricao != '') {
		$('o52_descr').value = sDescricao;
	}

	if (iFuncao != '') {
		$('rh25_funcao').value = iFuncao;
	}

	if (lErro) {
		$('o52_descr').value = '';
	}

	db_iframe_orcfuncao.hide();
}

/**
 * Função
 * Preenche campo pela pesquisa do input
 */
function js_preenchePesquisaFuncaoInput(sDescricao, lErro) {
	
	if (sDescricao != '') {
		$('o52_descr').value = sDescricao;
	}

	if (lErro) {
		$('rh25_funcao').value = '';
	}
}

/**
 * Monta querystring para elementos secundários
 */
function js_geraQueryStringElementosSecundarios() {

	var oFrame     = document.getElementById('IFdb_iframe_cadele');

	if (!oFrame) {
		return;
	}

	var frameDoc   = oFrame.contentDocument || oFrame.contentWindow.document;
	var iPrograma  = frameDoc.getElementById('rh39_programa').value;
	var iSubfuncao = frameDoc.getElementById('rh39_subfuncao').value;
	var iFuncao    = frameDoc.getElementById('rh39_funcao').value;
	var sPrograma  = frameDoc.getElementById('o54_descr').value;
	var sSubfuncao = frameDoc.getElementById('o53_descr').value;
	var sFuncao    = frameDoc.getElementById('o53_descr').value;

	sQueryString  = '&rh39_programa='  + iPrograma; 
  sQueryString += '&rh39_subfuncao=' + iSubfuncao;
  sQueryString += '&rh39_funcao='    + iFuncao;
  sQueryString += '&o54_descr='      + sPrograma;
  sQueryString += '&o53_descr='      + sSubfuncao;
  sQueryString += '&o52_descr='      + sFuncao; 

	return sQueryString;
}
</script>
