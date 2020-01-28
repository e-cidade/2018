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

$cliptubaseregimovel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_lote");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_setor");
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_codi");
$clrotulo->label("j13_descr");
$clrotulo->label("j69_descr");
$clrotulo->label("j06_setorloc");
$clrotulo->label("j06_quadraloc");  
$clrotulo->label("j06_lote");
?>
<? 
	db_app::load('scripts.js, prototype.js, strings.js, dbcomboBox.widget.js, estilos.css');
?>
<script>
function mostraJanelaPesquisa() {
    F = document.form1;

	if(F.j01_matric.value.length > 0) {
	  VisualizacaoMatricula.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricula=' + F.j01_matric.value;
	  VisualizacaoMatricula.mostraMsg();
	  VisualizacaoMatricula.show();
          VisualizacaoMatricula.focus();
	} else if(F.z01_nome.value.length > 0) {
          VisualizacaoProprietario.jan.location.href = 'func_nome.php?funcao_js=parent.mostraTodasMatCad|0&nomeDigitadoParaPesquisa=' +encodeURIComponent(F.z01_nome.value)+'&lCadTecMunic=true';
	  VisualizacaoProprietario.mostraMsg();
	  VisualizacaoProprietario.show();
	  VisualizacaoProprietario.focus();	  
	} else if(F.imobiliaria.value.length > 0) {
    VisualizacaoImobiliaria.jan.location.href = 'func_imobiliarias.php?funcao_js=parent.mostraTodasMatCad_IMobil|0&nome_imobiliaria=' +encodeURIComponent(F.imobiliaria.value);
	  VisualizacaoImobiliaria.mostraMsg();
	  VisualizacaoImobiliaria.show();
	  VisualizacaoImobiliaria.focus();	  
	} else if (F.j34_setor.value.length > 0 || F.j34_quadra.value.length > 0 || F.j34_lote.value.length > 0) {
	  $parametro = ""; 
	  //alert('setor '+F.j34_setor.value+' quadra = '+F.j34_quadra.value+' lote= '+F.j34_lote.value);
	  if (F.j34_setor.value.length == 0){
	  alert('É necessário preencher o setor');
	  return false;
	  }
	  if (F.j34_setor.value.length > 0) $parametro = $parametro + "setor=" + encodeURIComponent(F.j34_setor.value);
	  if (F.j34_quadra.value.length > 0) {
	    if (F.j34_setor.value.length > 0) 
		  $parametro = $parametro + "&quadra=" + encodeURIComponent(F.j34_quadra.value);
		else 
		  $parametro = $parametro + "quadra=" + encodeURIComponent(F.j34_quadra.value);
	  }
	  if (F.j34_lote.value.length > 0) {
	    if (F.j34_setor.value.length > 0 || F.j34_quadra.value.length > 0) 
		  $parametro = $parametro + "&lote=" + encodeURIComponent(F.j34_lote.value);
		else $parametro = $parametro + "lote=" + encodeURIComponent(F.j34_lote.value);
	  } 
    VisualizacaoSetorQuadraLote.jan.location.href = 'func_lotealt.php?funcao_js=parent.mostraTodasMatCadIDBQL|0&' + $parametro;
	  VisualizacaoSetorQuadraLote.mostraMsg();
	  VisualizacaoSetorQuadraLote.show();
	  VisualizacaoSetorQuadraLote.focus();	  
	} else if (F.j05_codigoproprio && F.j06_quadraloc && F.j06_lote && F.j05_codigoproprio.value != 'todos') {

		if(F.j05_codigoproprio.value != 'todos' || F.j06_quadraloc.value != '' || F.j06_lote.value != '') {

  		var sQueryString = '';
  		var sAnd = '';
  		
  		if(F.j05_codigoproprio.value.length > 0) {
  			sQueryString += sAnd + 'j05_codigoproprio=' + F.j05_codigoproprio.value; 
  			sAnd = '&';
  		}	
  		if(F.j06_quadraloc.value.length > 0) {
  			sQueryString += sAnd + 'j06_quadraloc=' + F.j06_quadraloc.value;
  			sAnd = '&'; 
  	  } 
  		if(F.j06_lote.value.length > 0) {
  			sQueryString += sAnd + 'j06_lote=' + F.j06_lote.value;
  			sAnd = '&';
  	  }  
  		VisualizacaoSetorQuadraLoteLoc.jan.location.href = 'func_iptubase.php?funcao_js=parent.mostraTodasMatriculasSetorLoc|j01_matric&'+sQueryString;
  		VisualizacaoSetorQuadraLoteLoc.mostraMsg();
  		VisualizacaoSetorQuadraLoteLoc.show();
  		VisualizacaoSetorQuadraLoteLoc.focus();	  

		}
	} else if(F.j14_codigo.value.length > 0) {

    VisualizacaoRuas.jan.location.href = 'func_ruas.php?funcao_js=parent.mostraTodasMatriculas_PesquisaRuas|0&codrua=' + F.j14_codigo.value;
	  VisualizacaoRuas.mostraMsg();
	  VisualizacaoRuas.show();
	  VisualizacaoRuas.focus();	  
	} else if(F.j14_nome.value.length > 0) {
		
    VisualizacaoNomeRuas.jan.location.href='func_ruas.php?funcao_js=parent.mostraTodasMatriculas_PesquisaRuas|0&nomerua='+ F.j14_nome.value;
	  VisualizacaoNomeRuas.mostraMsg();
	  VisualizacaoNomeRuas.show();
	  VisualizacaoNomeRuas.focus();	  
	} else if(F.j13_codi.value.length > 0) {
		
    VisualizacaoBairros.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasMatriculas_PesquisaBairro|0&codbairro=' + F.j13_codi.value;
	  VisualizacaoBairros.mostraMsg();
	  VisualizacaoBairros.show();
	  VisualizacaoBairros.focus();	  
	} else if(F.j13_descr.value.length > 0) {
		
    VisualizacaoNomeBairro.jan.location.href = 'func_bairros.php?funcao_js=parent.mostraTodasMatriculas_PesquisaBairro|0&nomeBairro=' + F.j13_descr.value;
	  VisualizacaoNomeBairro.mostraMsg();
	  VisualizacaoNomeBairro.show();
	  VisualizacaoNomeBairro.focus();	  
	}else if(F.j04_matricregimo.value.length > 0 && F.j04_setorregimovel.value.length) {
    VisualizacaoMatricula.jan.location.href = 'func_iptubaseregimovel.php?funcao_js=parent.mostraTodasMatriculas_PesquisaMatricregimo|2&setor='+F.j04_setorregimovel.value+'&matricregimo=' + F.j04_matricregimo.value;
      
//    VisualizacaoMatricula.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricularegimo=' + F.j04_matricregimo.value;
    VisualizacaoMatricula.mostraMsg();
    VisualizacaoMatricula.show();
    VisualizacaoMatricula.focus();

  }else if(F.j04_matricregimo.value.length > 0) {

		VisualizacaoMatricula.jan.location.href = 'func_iptubaseregimovel.php?funcao_js=parent.mostraTodasMatriculas_PesquisaMatricregimo|2&matricregimo=' + F.j04_matricregimo.value;
  		
//	  VisualizacaoMatricula.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricularegimo=' + F.j04_matricregimo.value;
	  VisualizacaoMatricula.mostraMsg();
	  VisualizacaoMatricula.show();
    VisualizacaoMatricula.focus();
	}else if (F.j04_setorregimovel.value.length > 0 || F.j04_quadraregimo.value.length > 0 || F.j04_loteregimo.value.length > 0) {
	   $parametro = ""; 
	  if (F.j04_setorregimovel.value.length > 0) $parametro = $parametro + "setor=" + F.j04_setorregimovel.value;
	  if (F.j04_quadraregimo.value.length > 0) {
	    if (F.j04_setorregimovel.value.length > 0) 
		  $parametro = $parametro + "&quadra=" + F.j04_quadraregimo.value;
		else 
		  $parametro = $parametro + "quadra=" + F.j04_quadraregimo.value;
	  }
	  if (F.j04_loteregimo.value.length > 0) {
	    if (F.j04_setorregimovel.value.length > 0 || F.j04_quadraregimo.value.length > 0) 
		  $parametro = $parametro + "&lote=" + F.j04_loteregimo.value;
		else $parametro = $parametro + "lote=" + F.j04_loteregimo.value;
	  } 
	  	  	  
	  VisualizacaoSetorQuadraLote.jan.location.href = 'func_iptubaseregimovel.php?funcao_js=parent.mostraregistro|j04_matric&' + $parametro;
	  VisualizacaoSetorQuadraLote.mostraMsg();
	  VisualizacaoSetorQuadraLote.show();
	  VisualizacaoSetorQuadraLote.focus();	 
	}
	
	F.reset();
}
  function mostraTodasMatCad(numerocgm){
    VisualizacaoTodasMatCad.jan.location.href = 'cad3_conscadastro_003.php?pesquisaPorNome=' + numerocgm;
    VisualizacaoTodasMatCad.mostraMsg();
    VisualizacaoTodasMatCad.show();
	VisualizacaoTodasMatCad.focus();
  }
  function mostraTodasMatCad_IMobil(numerocgm){
    VisualizacaoTodasMatCad.jan.location.href = 'cad3_conscadastro_003.php?pesquisaPorImobiliaria=' + numerocgm;
    VisualizacaoTodasMatCad.mostraMsg();
    VisualizacaoTodasMatCad.show();
	VisualizacaoTodasMatCad.focus();
  }
  function mostraTodasMatCadIDBQL(numidbql){
    //VisualizacaoTodasMatCad.jan.location.href = 'cad3_conscadastro_003.php?pesquisaPorIDBQL=' + numidbql;
    VisualizacaoTodasMatCad.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricula=' + numidbql;
    VisualizacaoTodasMatCad.mostraMsg();
    VisualizacaoTodasMatCad.show();
	VisualizacaoTodasMatCad.focus();
  }
  function mostraJanelaDadosImovel(numeroMat){
    VisualizacaoMatricula.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricula=' + numeroMat;
    VisualizacaoMatricula.mostraMsg();
    VisualizacaoMatricula.show();
	  VisualizacaoMatricula.focus();	  
  }
  function mostraTodasMatriculas_PesquisaRuas(rua){
    VisualizacaoRuas.jan.location.href = 'cad3_conscadastro_003.php?pesquisaRua=' + rua;
    VisualizacaoRuas.mostraMsg();
    VisualizacaoRuas.show();
    VisualizacaoRuas.focus();	  
  }
  function mostraTodasMatriculas_PesquisaBairro(bairro){
    VisualizacaoBairros.jan.location.href = 'cad3_conscadastro_003.php?pesquisaBairro=' + bairro;
    VisualizacaoBairros.mostraMsg();
    VisualizacaoBairros.show();
	VisualizacaoBairros.focus();	  
  }
  // adicionada lookup para mostrar todas matrículas da matricula do registro de imóveis
  function mostraTodasMatriculas_PesquisaMatricregimo(matricula){
	    VisualizacaoMatricula.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricula=' + matricula;
	    VisualizacaoMatricula.mostraMsg();
	    VisualizacaoMatricula.show();
	    VisualizacaoMatricula.focus();	  
	  }
  function mostraregistro(matricula){
  	VisualizacaoSetorQuadraLote.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricula=' + matricula;
    VisualizacaoSetorQuadraLote.mostraMsg();
    VisualizacaoSetorQuadraLote.show();
	  VisualizacaoSetorQuadraLote.focus();
  }

  function mostraTodasMatriculasSetorLoc(iMatricula) {
	  VisualizacaoSetorQuadraLoteLoc.jan.location.href = 'cad3_conscadastronovo_002.php?cod_matricula=' + iMatricula;
	  VisualizacaoSetorQuadraLoteLoc.mostraMsg();
	  VisualizacaoSetorQuadraLoteLoc.show();
	  VisualizacaoSetorQuadraLoteLoc.focus();
  }
  
  function js_pesquisaj04_setorregimovel(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe_setorregimovel','func_setorregimovel.php?funcao_js=parent.js_mostrasetorregimovel1|j69_sequencial|j69_descr','Pesquisa',true);
  }else{
     if(document.form1.j04_setorregimovel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_setorregimovel','func_setorregimovel.php?pesquisa_chave='+document.form1.j04_setorregimovel.value+'&funcao_js=parent.js_mostrasetorregimovel','Pesquisa',false);
     }else{
       document.form1.j69_descr.value = ''; 
     }
  }
}
function js_mostrasetorregimovel(chave,erro){
  document.form1.j69_descr.value = chave; 
  if(erro==true){ 
    document.form1.j04_setorregimovel.focus(); 
    document.form1.j04_setorregimovel.value = ''; 
  }
}
function js_mostrasetorregimovel1(chave1,chave2){
  document.form1.j04_setorregimovel.value = chave1;
  document.form1.j69_descr.value = chave2;
  db_iframe_setorregimovel.hide();
}
function js_pesquisaMatricula(lMostra) {
  var sQueryString = 'func_iptubase.php?';
  if(lMostra) {
	  sQueryString += 'funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome';
  } 
  js_OpenJanelaIframe('top.corpo', 'db_iframe_iptubase', sQueryString, 'Pesquisa', lMostra, 20);
}
function js_mostraMatricula(iMatricula, sNome) {
	document.form1.j01_matric.value = iMatricula;
	db_iframe_iptubase.hide();
}
function js_comTeclaEnter(evt) {

  var evt = (evt) ? evt : (window.event) ? window.event : "";
  if (evt.keyCode == 13) {
	  mostraJanelaPesquisa();
  }
}
</script>
<br>
<table width="90%" border="0" align="left" cellpadding="0"	cellspacing="0">
	<tr>
		<td align="left" valign="top">
		<form name="form1" method="post" action="" onsubmit="js_append()">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="2">
				<fieldset><legend> <b>Dados referentes a matrícula</b></legend>
				<table border="0">


					<tr>
					
						<td nowrap title="<?=@$Tj01_matric?>" width="240">
						<?
						  db_ancora($Lj01_matric, 'js_pesquisaMatricula(true)', 1);
						?>
						</td>
						<td nowrap><?
						db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onBlur='js_ValidaCamposText(this,1);' onkeypress='js_comTeclaEnter(event)'")
						?>
					
					</tr>
					<tr>
						
						<td nowrap title="<?=@$Tz01_nome?>"><?=@$Lz01_nome?></td>
						<td nowrap><?
						db_input('z01_nome',63,$Iz01_nome,true,'text',1,"")
						?>
					
					</tr>
					</tr>
					<tr title="Nome da imobiliaria">
						
						<td nowrap><strong>Imobili&aacute;ria :</strong></td>
						<td nowrap><?
						db_input('imobiliaria',63,$Iz01_nome,true,'text',1,"onkeypress='js_comTeclaEnter(event)' onKeyUp=\"js_ValidaCampos(this,3,'Imobiliaria','f','t',event);\" ","imobiliaria")
						?>
					
					</tr>
					<tr title="<?=@$Tj34_setor?>">
						
						<td nowrap><?=str_replace(":","",$Lj34_setor)."/".str_replace(":","",$Lj34_quadra)."/".$Lj34_lote?>
						</td>
						<td nowrap><?
						db_input('j34_setor',5,$Ij34_setor,true,'text',1, "onkeypress='js_comTeclaEnter(event)'")
						?>/ <?
						db_input('j34_quadra',5,$Ij34_quadra,true,'text',1, "onkeypress='js_comTeclaEnter(event)'")
						?>/ <?
						db_input('j34_lote',5,$Ij34_lote,true,'text',1, "onkeypress='js_comTeclaEnter(event)'")
						?></td>
					</tr>
					<?php  if ($rsSetorLoc) {?>
					<tr>
          	<td width="34%" nowrap title="<?=$Tj06_setorloc?>"><?=$Lj06_setorloc?></td>
          	<td>
          	<?
           		db_selectrecord('j05_codigoproprio', $rsSetorLoc, true, 4, '', 'j05_codigoproprio', '', 'todos', 'js_carregaQuadra(this.value)');
          	?>
          	</td>
          </tr>
          
          <tr>
          	<td width="34%" nowrap title="<?=$Tj06_quadraloc?>"><?=$Lj06_quadraloc?></td>
          	<td id="cboquadraloc" width="66%" >
          		
          	</td>
          </tr>
          
          <tr>
          	<td width="34%" nowrap title="<?=$Tj06_lote?>"><?=$Lj06_lote?></td>
          	<td id="cboloteloc" width="66%" >
          	  
          	</td>
          </tr>
          <?php }?>
          
					<tr>
						
						<td nowrap title="<?=@$Tj14_codigo?>"><?=@$Lj14_codigo?></td>
						<td nowrap><? db_input('j14_codigo',10,$Ij14_codigo,true,'text',1,"onkeypress='js_comTeclaEnter(event)' onBlur='js_ValidaCamposText(this,1)'"); ?></td>
					</tr>
					<tr>
						
						<td nowrap title="<?=@$Tj14_nome?>"><?=@$Lj14_nome?></td>
						<td nowrap><? db_input('j14_nome',63,$Ij14_nome,true,'text',1, "onkeypress='js_comTeclaEnter(event)'");?></td>
					</tr>
					<tr>
						
						<td nowrap title="<?=@$Tj13_codi?>"><?=@$Lj13_codi?></td>
						<td nowrap><? db_input('j13_codi',10,$Ij13_codi,true,'text',1,"onkeypress='js_comTeclaEnter(event)' onBlur='js_ValidaCamposText(this,1)'"); ?>
						</td>
					</tr>
					<tr>
						
						<td nowrap title="<?=@$Tj13_descr?>"><?=@$Lj13_descr?></td>
						<td nowrap><?
						db_input('j13_descr',63,$Ij13_descr,true,'text',1, "onkeypress='js_comTeclaEnter(event)'");
						?></td>
					</tr>
					<tr>
						<td colspan="2" align="left" valign="top" nowrap>&nbsp;</td>
					</tr>
				</table>
				</fieldset>
				</td>
			</tr>


			<tr>
				<td colspan="3" align="left" valign="top" nowrap><?$db_opcao=1; ?>
				<fieldset><legend> <b>Dados referentes ao registro de imóveis</b></legend>
				<table>
					</tr>
					<tr>
						<td nowrap title="<?=@$Tj04_matricregimo?>"><?=@$Lj04_matricregimo?>
						</td>
						<td><?
						db_input('j04_matricregimo',10,$Ij04_matricregimo,true,'text',$db_opcao,"onkeypress='js_comTeclaEnter(event)'")
						?></td>
					</tr>
					<tr>
						<td nowrap title="<?=@$Tj04_setorregimovel?>" width="240"><?
						db_ancora(@$Lj04_setorregimovel,"js_pesquisaj04_setorregimovel(true);",$db_opcao, "onkeypress='js_comTeclaEnter(event)'");
						?></td>
						<td><?
						db_input('j04_setorregimovel',10,$Ij04_setorregimovel,true,'text',$db_opcao," onkeypress='js_comTeclaEnter(event)' onchange='js_pesquisaj04_setorregimovel(false);'")
						?> <?
						db_input('j69_descr',50,$Ij69_descr,true,'text',3,"onkeypress='js_comTeclaEnter(event)'")
						?></td>
					</tr>

					<tr>
						<td nowrap title="<?=@$Tj04_quadraregimo?>"><?=@$Lj04_quadraregimo?>
						</td>
						<td><?
						db_input('j04_quadraregimo',10,$Ij04_quadraregimo,true,'text',$db_opcao,"onkeypress='js_comTeclaEnter(event)'")
						?></td>
					</tr>
					<tr>
						<td nowrap title="<?=@$Tj04_loteregimo?>"><?=@$Lj04_loteregimo?></td>
						<td><?
						db_input('j04_loteregimo',10,$Ij04_loteregimo,true,'text',$db_opcao,"onkeypress='js_comTeclaEnter(event)'")
						?></td>
					</tr>
				</table>
				</fieldset>
				</td>
			</tr>
			<tr align="center">
				<td colspan="3">
					<input name="pesquisar" type="button" onClick="mostraJanelaPesquisa()" id="pesquisar" value="Pesquisar">
			  </td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
</table>
<script type="text/javascript">
var aOptions     = new Array();
aOptions[''] = 'Todos...';

function js_append() {

$('form1').appendChild($('j06_quadraloc'));
$('form1').appendChild($('j06_lote'));

}

function js_mostraQuadra(){

cboQuadras          = new DBComboBox('j06_quadraloc', 'j06_quadraloc', aOptions, '180');
cboQuadras.onChange = 'js_carregaLote(this.value)';
cboQuadras.show(document.getElementById('cboquadraloc'));

}

function js_mostraLotes(){

cboLotes = new DBComboBox('j06_lote', 'j06_lote', aOptions, '180');
cboLotes.show(document.getElementById('cboloteloc'));

}

js_mostraQuadra();
js_mostraLotes();

function js_carregaQuadra(iCodSetor) {

js_mostraQuadra();
js_mostraLotes();

var oParametro       = new Object();
oParametro.sExec     = 'getQuadraSetor';
oParametro.iCodSetor = iCodSetor;

var oAjax = new Ajax.Request('func_iptubase.RPC.php',
                          { 
                           method: 'POST',
						               parameters: 'json='+Object.toJSON(oParametro), 
						                 onComplete: js_retornaQuadra
                          });

}

function js_retornaQuadra(oAjax) {

var oRetorno = eval("("+oAjax.responseText+")"); 
var aQuadras = new Array(); 

if(oRetorno.status == 1) {
	for(var i = 0; i < oRetorno.oQuadras.length; i++) {
		with(oRetorno.oQuadras[i]) {
			cboQuadras.addItem(j06_quadraloc, j06_quadraloc);
	  }
	}
}	
js_carregaLote($F('j06_quadraloc'));

return false;

}

function js_carregaLote(sQuadra) {

js_mostraLotes();
var oParametro = new Object();

oParametro.sExec     = 'getLote';
oParametro.sQuadra   = sQuadra;
oParametro.iSetor    = $F('j05_codigoproprio');

var oAjax = new Ajax.Request('func_iptubase.RPC.php',
                          { 
                           method: 'POST',
							               parameters: 'json='+Object.toJSON(oParametro), 
							               onComplete: js_retornaLote });

}

function js_retornaLote(oAjax) {

var oRetorno = eval("("+oAjax.responseText+")");
var aLotes   = new Array(); 
aLotes['']   = 'Todos...';

if(oRetorno.status == 1) {
	for(var i = 0; i < oRetorno.oLotes.length; i++) {
		with(oRetorno.oLotes[i]) {
			cboLotes.addItem(j06_lote, j06_lote);
	  }
	}
}	

return false;

}

js_carregaQuadra($F('j05_codigoproprio'));

</script>