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

//MODULO: projetos
$clobrashabite->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ob08_codobra");

$clobrashabiteprot->rotulo->label();
$clobrashabiteprotoff->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ob09_habite");
$clrotulo->label("p58_codproc");
$clrotulo->label("p51_descr");
?>
<form class="container" name="form1" method="post" action="">
		<fieldset>
			<legend>Cadastro de Habite-se	</legend>
						<table class="form-container">
							<tr>
								<td nowrap title="<?=@$Tob09_codhab?>">
									<?=@$Lob09_codhab?>
								</td>
								<td>
									<?
										db_input('ob09_codhab',15,$Iob09_codhab,true,'text',3,"");
									?>
								</td>
							</tr>
							<tr>
								<td nowrap title="<?=@$Tob09_habite?>">
									<?=@$Lob09_habite?>
								</td>
								<td>
									<?
										$rsParProjetos = $clparprojetos->sql_record($clparprojetos->sql_query_file(db_getsession('DB_anousu')));

										if ($clparprojetos->numrows > 0){

											$oParProjetos  = db_utils::fieldsMemory($rsParProjetos,0);

											if ($oParProjetos->ob21_numeracaohabite == 2 ) {
												$ob09_habite		 = $oParProjetos->ob21_ultnumerohabite + 1;
												$db_opcao_habite = 3;
											}else{
												$db_opcao_habite = $db_opcao;
											}

										} else {
											db_msgbox("Configurar parametros para o Exercício de ".db_getsession('DB_anousu'));
											$db_opcao_habite = 3;
											$db_opcao				 = 3;
										}


										db_input('ob09_habite',15,$Iob09_habite,true,'text',$db_opcao_habite,"")
									?>
								</td>
							</tr>
							<tr>
								<td nowrap title="<?=@$Tob09_anousu?>">
									<?=@$Lob09_anousu?>
								</td>
								<td>
									<?
										$ob09_anousu = db_getsession('DB_anousu');
										db_input('ob09_anousu',15,$Iob09_anousu,true,'text',3,"")
									?>
								</td>
							</tr>
							<tr>
								<td nowrap title="<?=@$Tob09_engprefeitura?>">
									<?
										db_ancora(@$Lob09_engprefeitura,"js_pesquisaob09_engprefeitura(true);",($db_opcao == 2?3:$db_opcao));
									?>
								</td>
								<td>
									<?
										db_input('ob09_engprefeitura',15,$Iob09_engprefeitura,true,'text',$db_opcao,"onChange='js_pesquisaob09_engprefeitura(false)'");
										db_input('nomeEngPrefeitura',45,"",true,'text',3,"");
									?>
								</td>
							</tr>
							<tr>
								<td nowrap title="<?=@$Tob09_codconstr?>">
									<?
										db_ancora(@$Lob09_codconstr,"js_pesquisaob09_codconstr(true);",($db_opcao == 2?3:$db_opcao));
									?>
								</td>
								<td>
									<?
										db_input('ob09_codconstr',15,$Iob09_codconstr,true,'text',($db_opcao == 2?3:$db_opcao)," onchange='js_pesquisaob09_codconstr(false);'");
										db_input('nomePropri',45,"",true,'text',3,"");
										db_input('ob08_codobra',10,$Iob08_codobra,true,'hidden',3,'');
									?>
								</td>
							</tr>
							<tr>
								<td>
									<b>Processo do Sistema:</b>
								</td>
								<td>
									<?
									  $aValSis = array("1"=>"Sim","2"=>"Não");
										db_select('iValSis',$aValSis,true,1,"style='width:110px;' onChange='js_mostraProc(this.value)'");
									?>
								</td>
							</tr>
						</table>
						<div id="procManual" style="display:none">
							<table class="form-container">
								<tr>
									<td nowrap title="<?=@$Tob22_codproc?>" width="131px;">
										<?=@$Lob22_codproc?>
									</td>
									<td>
										<?
											db_input('ob22_codproc',15,$Iob22_codproc,true,'text',$db_opcao,"");
										?>
									</td>
								</tr>
								<tr>
									<td nowrap title="<?=@$Tob22_titular?>">
										<?=@$Lob22_titular?>
									</td>
									<td>
										<?
											db_input('ob22_titular',64,$Iob22_titular,true,'text',$db_opcao,"");
										?>
									</td>
								</tr>
								<tr>
									<td>
										<b>Data Processo:</b>
									</td>
									<td>
										<?
											if($db_opcao == 1){
												$ob22_data_dia = date("d",db_getsession("DB_datausu"));
												$ob22_data_mes = date("m",db_getsession("DB_datausu"));
												$ob22_data_ano = date("Y",db_getsession("DB_datausu"));
											}
											db_inputdata('ob22_data',@$ob22_data_dia,@$ob22_data_mes,@$ob22_data_ano,true,'text',$db_opcao,"");
										?>
									</td>
								 </tr>
							</table>
						</div>
						<div id="procSistema">
							<table class="form-container">
								<tr>
									<td nowrap title="<?=@$Tob19_codproc?>" width="131px;">
										<?
											db_ancora(@$Lob19_codproc,"js_pesquisaob19_codproc(true);",$db_opcao);
										?>
									</td>
									<td>
										<?
											db_input('ob19_codproc',15,$Iob19_codproc,true,'text',$db_opcao," onchange='js_pesquisaob19_codproc(false);'");
											db_input('p51_descr'  ,45,$Ip51_descr,true,'text',3,'');
											db_input('p58_codproc',45,$Ip58_codproc,true,'hidden',3,'');
										?>
									</td>
								</tr>
								<tr>
									<td >
										<b>Data Processo:</b>
									</td>
									<td>
										<?
											db_input('p58_dtproc',15,"",true,'text',3,"");
										?>
									</td>
							  </tr>
							</table>
						</div>
						<table class="form-container">
							<tr>
								<td width="131px;" nowrap title="<?=@$Tob09_data?>">
								  <?=@$Lob09_data?>
							  </td>
							  <td>
								  <?
										if($db_opcao == 1){
											$ob09_data_dia = date("d",db_getsession("DB_datausu"));
											$ob09_data_mes = date("m",db_getsession("DB_datausu"));
											$ob09_data_ano = date("Y",db_getsession("DB_datausu"));
										}
										db_inputdata('ob09_data',@$ob09_data_dia,@$ob09_data_mes,@$ob09_data_ano,true,'text',$db_opcao,"");
									?>
								</td>
							</tr>
							<tr>
								<td align="left" nowrap title="<?=@$Tob09_area?>">
									<?=@$Lob09_area?>
								</td>
								<td align="left">
									<?
										db_input('ob09_parcial',10,"",true,'hidden',$db_opcao,"");
										db_input('ob09_area',10,$Iob09_area,true,'hidden',$db_opcao,"","areatotal");
										db_input('ob09_area',10,$Iob09_area,true,'hidden',$db_opcao,"","areausada");
										db_input('ob09_area',10,$Iob09_area,true,'hidden',$db_opcao,"","areahabite");
										db_input('ob09_area',15,$Iob09_area,true,'text',$db_opcao,"onChange='js_verificaarea();' onkeypress='return validaMonetario(this, event)'")
									?>
								</td>
							</tr>
						</table>
						<table class="form-container">
							<tr>
								<td width="131px;" nowrap title="<?=@$Tob09_logradcorresp?>">
									<?=@$Lob09_logradcorresp?>
								</td>
								<td>
									<?
										db_input('ob09_logradcorresp',64,$Iob09_logradcorresp,true,'text',$db_opcao,"")
									?>
								</td>
							</tr>
						</table>
						<table class="form-container">
							<tr>
								<td width="131px;" nowrap title="<?=@$Tob09_numcorresp?>">
									<?=@$Lob09_numcorresp?>
								</td>
								<td>
									<?
										db_input('ob09_numcorresp',15,$Iob09_numcorresp,true,'text',$db_opcao,"")
									?>
								</td>
								<td>
									<?=@$Lob09_compl?>
								</td>
								<td align="right">
									<?
										db_input('ob09_compl',15,$Iob09_compl,true,'text',$db_opcao,"")
									?>
								</td>
							</tr>
						</table>
						<table class="form-container">
							<tr>
								<td width="131px;" nowrap title="<?=@$Tob09_bairrocorresp?>">
									<?=@$Lob09_bairrocorresp?>
								</td>
								<td>
									<?
										db_input('ob09_bairrocorresp',64,$Iob09_bairrocorresp,true,'text',$db_opcao,"")
									?>
								</td>
							</tr>
						</table>
						<table class="form-container">
							<tr>
								<td width="131px;" nowrap>
									<b>
										<?=@$Lob09_codibgemunic?>
									</b>
								</td>
								<td>
									<?
										db_input('ob09_codibgemunic',15,$Iob09_codibgemunic,true,'text',$db_opcao,"")
									?>
								</td>
								<td width="131px;" align="right">
									<b>CEP:</b>
								</td>
								<td align="right">
									<?
										db_input('cep',15,"",true,'text',$db_opcao,"")
									?>
								</td>
							</tr>
						</table>
						<table class="form-container">
							<tr>
								<td width="131px;" nowrap title="<?=@$Tob09_obs?>" colspan="2">
								  <fieldset class="separator">
								    <legend><?=@$Lob09_obs?></legend>
  									<?
  										db_textarea('ob09_obs',3,61,$Iob09_obs,true,'text',$db_opcao,"")
  									?>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td nowrap title="<?=@$Tob09_obsinss?>" colspan="2">
  								<fieldset class="separator">
  								    <legend>Obs. INSS:</legend>
  									<?
  										db_textarea('ob09_obsinss',3,61,$Iob09_obsinss,true,'text',$db_opcao,"")
  									?>
  							  </fieldset>
								</td>
							</tr>
						</table>
						<table class="form-container">
							<tr>
								<td width="131px;">
									<b>Ocupação:</b>
								</td>
								<td>
									<?
										db_input('ob08_ocupacao',64,"",true,'text',3,"")
									?>
								</td>
							</tr>
						</table>
		</fieldset>
			&nbsp;
			<div id="tabelaHistDiv" style="display:none">
		<fieldset>
			<legend>
				<b>&nbsp;Histórico de Habite-se&nbsp;</b>
			</legend>
			<table width='100%' class='tab'>
				<tr>
					<th>
						Habite-se
					</th>
					<th>
						Construção
					</th>
					<th>
						Área
					</th>
					<th>
						Tipo
					</th>
					<th>
					</th>
				</tr>
			<tbody id='tabelaHist'>
		</tbody>
	</table>
</fieldset>
</div>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
<?=($db_botao==false?"disabled":"")?>
onClick='return js_testaarea()' >

</form>
<script>

function js_mostraProc(iValSel){

	if (iValSel == 2) {
		document.getElementById('procManual').style.display  = "";
		document.getElementById('procSistema').style.display = "none";
	}else{
		document.getElementById('procManual').style.display  = "none";
		document.getElementById('procSistema').style.display = "";
	}
}

function js_reemiteHabite(chave){
  if( chave!='' ) {
    jan = window.open('pro2_cartahabite002.php?codigo='+chave,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}

function js_pesquisaob09_engprefeitura(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obrastec','func_obrastec.php?chave_engprefeitura=true&funcao_js=parent.js_mostraobrastec1|ob15_sequencial|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.ob09_engprefeitura.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_obrastec','func_obrastec.php?chave_engprefeitura=true&pesquisa_chave='+document.form1.ob09_engprefeitura.value+'&funcao_js=parent.js_mostraobrastec','Pesquisa',false);
    }else{
      document.form1.ob09_engprefeitura.value = '';
      document.form1.nomeEngPrefeitura.value  = '';
    }
  }
}
function js_mostraobrastec(chave1,chave2,erro){
  if(erro==true){
    document.form1.ob09_engprefeitura.focus();
    document.form1.ob09_engprefeitura.value = '';
    document.form1.nomeEngPrefeitura.value  = '';
  }else{
    document.form1.ob09_engprefeitura.value = chave1;
    document.form1.nomeEngPrefeitura.value  = chave2;
  }
}
function js_mostraobrastec1(chave1,chave2){
  document.form1.ob09_engprefeitura.value = chave1;
  document.form1.nomeEngPrefeitura.value  = chave2;
  db_iframe_obrastec.hide();
}



function js_pesquisaob19_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome|p58_dtproc','Pesquisa',true);
  }else{
    if(document.form1.ob19_codproc.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.ob19_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
    }else{
      document.form1.p58_codproc.value = '';
    }
  }
}
function js_mostraprotprocesso(chave1,chave2,erro){
  if(erro==true){
    document.form1.ob19_codproc.focus();
    document.form1.ob19_codproc.value = '';
    document.form1.p51_descr.value    = '';
  }else{
    document.form1.p58_codproc.value = chave1;
    document.form1.p51_descr.value   = chave2;
  }
}
function js_mostraprotprocesso1(chave1,chave2,chave3){
  document.form1.ob19_codproc.value = chave1;
  document.form1.p58_codproc.value = chave1;
  document.form1.p51_descr.value   = chave2;
  p58_dtproc_dia = chave3.substr(8,2);
  p58_dtproc_mes = chave3.substr(5,2);
  p58_dtproc_ano = chave3.substr(0,4);
  document.form1.p58_dtproc.value = p58_dtproc_dia+'/'+p58_dtproc_mes+'/'+p58_dtproc_ano;
  db_iframe_protprocesso.hide();
}
function js_testaarea(){
  <?
    if($db_opcao == 1) {
      ?>
      var area = new Number(document.form1.ob09_area.value);
      var habite = new Number(document.form1.areahabite.value);
      if (area > habite) {

        alert(_M('tributario.projetos.db_frmobrashabite.area_habitese_maior_total'));
        document.form1.ob09_area.value = '';
        document.form1.ob09_area.focus();
        return false
      }else{
        if (area == 0) {

          alert(_M('tributario.projetos.db_frmobrashabite.area_habitese_maior_zero'));
          document.form1.ob09_area.value = '';
          document.form1.ob09_area.focus();
          return false;
        } else {
          return true;
        }
      }
      return false;
      <?
    }else{
      ?>
        return true;
      <?
    }
  ?>
}

function js_pesquisaob09_codconstr(mostra){

  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_obrasconstr','func_obrasconstralvarahab.php?funcao_js=parent.js_mostraobrasconstr1|ob08_codconstr|ob08_codobra|ob08_area','Pesquisa',true);
  }else{

    if(document.form1.ob09_codconstr.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_obrasconstr','func_obrasconstralvarahab.php?pesquisa_chave='+document.form1.ob09_codconstr.value+'&funcao_js=parent.js_mostraobrasconstr','Pesquisa',false);
    }else{
      document.form1.ob08_codobra.value = '';
    }
  }
}
function js_mostraobrasconstr(chave,area,erro){

  var d = document.form1;

  d.ob08_codobra.value = chave;
  d.areatotal.value = area;

  if(erro==true){
    d.ob09_codconstr.focus();
    d.ob09_codibgemunic.value  = '';
    d.nomePropri.value 				 = '';
    d.ob09_logradcorresp.value = '';
    d.ob09_bairrocorresp.value = '';
    d.ob09_numcorresp.value    = '';
    d.ob09_compl.value         = '';
    d.cep.value                = '';
    d.ob09_codconstr.value 		 = '';
    d.ob08_codobra.value 		   = '';
    d.ob09_area.value 		     = '';
    d.ob08_ocupacao.value 	   = '';
    $('tabelaHistDiv').style.display = "none";
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?constr='+document.form1.ob09_codconstr.value+'&funcao_js=parent.js_mostraarea','Pesquisa',false);
  }
}

function js_verificaarea(){

	if ( document.form1.ob09_area.value != document.form1.areatotal.value) {
		document.form1.ob09_parcial.value = 't';
	} else {
		document.form1.ob09_parcial.value = 'f';
	}

}

function js_mostraarea(area){

  document.form1.areausada.value = area;
  var areahabite = new Number(document.form1.areatotal.value - area).toFixed(2);
  document.form1.ob09_area.value = js_formatar( areahabite.toString(), 'f', 2 ).replace('.', '').replace(',', '.');
  document.form1.areahabite.value = js_formatar( areahabite.toString(), 'f', 2 ).replace('.', '').replace(',', '.');

  if ( document.form1.ob09_area.value != document.form1.areatotal.value){
    document.form1.ob09_parcial.value = 't';
  } else {
    document.form1.ob09_parcial.value = 'f';
  }

  js_processaRequest();
}

function js_processaRequest(){

  js_divCarregando(_M('tributario.projetos.db_frmobrashabite.processando'),'msgCarrega');

  var url       = 'pro4_obrashabiteRPC.php';
  var parametro = 'codObra='+document.form1.ob08_codobra.value+'&codConstr='+document.form1.ob09_codconstr.value;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:js_loadTable});
}

function js_loadTable(resposta){

  var d = document.form1;
  eval('var objJ = '+resposta.responseText+';');

  if(objJ == "Vazio"){
    js_mostraobrasconstr("","",true);
    js_removeObj('msgCarrega');
    return false;
  }

  if(objJ[0] == 'semendereco'){
    d.nomePropri.value         = objJ[1].z01_nome;
    d.ob09_codibgemunic.value  = '';
    d.ob09_logradcorresp.value = '';
    d.ob09_bairrocorresp.value = '';
    d.ob09_numcorresp.value    = '';
    d.ob09_compl.value         = '';
    d.cep.value                = '';
    d.ob08_codobra.value 		   = '';
    d.ob08_ocupacao.value 	   = '';
    $('tabelaHistDiv').style.display = "none";
  }else{
    d.ob09_codibgemunic.value  = objJ[1].cp05_codibge;
    d.nomePropri.value 				 = objJ[2].z01_nome;
    d.ob09_logradcorresp.value = objJ[3].z01_ender;
    d.ob09_bairrocorresp.value = objJ[3].z01_bairro;
    d.ob09_numcorresp.value    = objJ[3].z01_numero;
    d.ob09_compl.value         = objJ[3].z01_compl;
    d.cep.value                = objJ[3].z01_cep;
    d.ob08_ocupacao.value			 = objJ[4].ob08_ocupacao;
    tabela = "";

    for(i = 5; i < objJ.length; i++){
      tabela +=       "<tr>																		";
      tabela +=				"  <td>"+objJ[i].ob09_codhab+"</td> 		";
      tabela +=			  "  <td>"+objJ[i].ob09_codconstr+"</td>  ";
      tabela +=				"  <td>"+objJ[i].ob09_area+"</td> 		  ";
      tabela +=				"  <td>"+objJ[i].ob09_parcial+"</td> 	  ";
      tabela +=		    "  <td width='5%'><input type='button' value='Reemitir' onClick='js_reemiteHabite("+objJ[i].ob09_codhab+");'></td> ";
      tabela +=			  "</tr>       ";
    }
    $('tabelaHistDiv').style.display = "";
    $('tabelaHist').innerHTML = tabela;
  }
  js_removeObj('msgCarrega');
}


function js_mostraobrasconstr1(chave1,chave2,area){

  document.form1.ob09_codconstr.value = chave1;
  document.form1.ob08_codobra.value   = chave2;
  document.form1.areatotal.value      = area;
  js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?constr='+document.form1.ob09_codconstr.value+'&funcao_js=parent.js_mostraarea','Pesquisa',false);
  db_iframe_obrasconstr.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?funcao_js=parent.js_preenchepesquisa|ob09_codhab','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrashabite.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
<?
if($db_opcao == 3 && $ob09_area == 0){
  ?>
    document.form1.ob09_area.value = '';
  <?
}
?>

$("ob09_codhab").addClassName("field-size2");
$("ob09_habite").addClassName("field-size2");
$("ob09_anousu").addClassName("field-size2");
$("ob09_engprefeitura").addClassName("field-size2");
$("nomeEngPrefeitura").addClassName("field-size7");
$("ob09_codconstr").addClassName("field-size2");
$("nomePropri").addClassName("field-size7");
$("ob19_codproc").addClassName("field-size2");
$("p51_descr").addClassName("field-size7");
$("p58_dtproc").addClassName("field-size2");
$("ob09_data").addClassName("field-size2");
$("ob09_area").addClassName("field-size2");
$("ob09_logradcorresp").addClassName("field-size9");
$("ob09_numcorresp").addClassName("field-size2");
$("ob09_compl").addClassName("field-size2");
$("ob09_bairrocorresp").addClassName("field-size9");
$("ob09_codibgemunic").addClassName("field-size2");
$("cep").addClassName("field-size2");
$("ob08_ocupacao").addClassName("field-size9");
</script>