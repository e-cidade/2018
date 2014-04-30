<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label("j69_descr");
$clrotulo->label("j01_numcgm");
$clrotulo->label("j107_sequencial");
$clrotulo->label("j107_nome");
?>
<script>
function js_verinome(){
  if(document.form1.z01_nome.value==""){
    alert("Verifique se o nome do NUMCGM esta correto!");
    return false;    
  }
  
  if(document.form1.j01_fracao.value > 100){
   alert("Fração Ideal informada maior que 100%");	
   document.form1.j01_fracao.value = "";
   document.form1.j01_fracao.focus(); 
   return false;
  }
  
  if(document.getElementById('predios')){
  	if($('predios').value == 0){
  		alert("Usuario:\n\n Campo Prédio não Selecionado ! \n\nAdministrador:\n\n");
  		return false;
  	}
  }
  
  return  parent.js_veripros("iptubase");
//  return true;    

}
</script>
<br>
<table border="0" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2">
		<fieldset><legend> <b>Dados referentes a matrícula</b></legend>
		<table>
			<tr>
				<td nowrap title="<?=@$Tj01_matric?>" width="200" ><?=@$Lj01_matric?></td>
				<td><?
				db_input('j01_matric',10,$Ij01_matric,true,'text',3,"");
				db_input('j01_idbql',10,$Ij01_idbql,true,'hidden',3,"");
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj01_numcgm?>"><?
				db_ancora(@$Lj01_numcgm,"js_pesquisaj01_numcgm(true);",$db_opcao);
				?></td>
				<td><?
				db_input('j01_numcgm',10,$Ij01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaj01_numcgm(false);'")
				?> <?
				db_input('z01_nome',56,$Iz01_nome,true,'text',3,'')
				?></td>
			</tr>
      <!--
			<tr>
				<td nowrap title="<?=@$Tj01_baixa?>"><?=@$Lj01_baixa?></td>
				<td><?
				db_inputdata('j01_baixa',@$j01_baixa_dia,@$j01_baixa_mes,@$j01_baixa_ano,true,'text',3,"")
				?></td>
			</tr>
      -->
			<tr>
				<td nowrap title="<?=@$Tj01_fracao?>"><?=@$Lj01_fracao?></td>
				<td><?
				db_input('j01_fracao',20,$Ij01_fracao,true,'text',$db_opcao,"")
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj40_refant?>"><?=@$Lj40_refant?></td>
				<td><?
	   db_input('j40_refant',20,$Ij40_refant,true,'text',$db_opcao,"")
	   ?></td>
			</tr>
			<tr>
				<td>
					<? 
			  		db_ancora('<b>Cód Condomínio</b>','js_pesquisa_j107_sequencial(true)',$db_opcao);
			   	?>
				</td>
				<td>
				<?
					db_input('j107_sequencial',10,$Ij107_sequencial,true,'text',$db_opcao,'onchange=js_pesquisa_j107_sequencial(false)');
					db_input('j107_nome',56,$Ij107_nome,true,'text',3,'');
				?>
				</td>
			</tr>
			<tr>
				<td id="predio">
					<? 
						if(isset($j111_nome)){
							echo "<b>Prédio:</b>";
						}
					?>
				</td>
				<td id="sltpredio">
					<? 
						if(isset($j111_nome)){
						?>
						<input type="hidden" name="predios" id="predios" value="<?=$j111_sequencial?>">  
						<input type="text" name="nomepredio" id="nomepredio" value="<?=$j111_nome?>" disabled="disabled" style="background-color: #DEB887; color:#000000;">  
						<? 	 
						}
					?>
				</td>
			</tr>

			<tr>
        <td colspan="2" title="<?=@$Tj26_obs?>">
        <fieldset>
          <legend><?=@$Lj26_obs?></legend>
          <?
              db_textarea('j26_obs',10,93,@$Ij26_obs,true,'text',$db_opcao,"","","#E6E4F1");
          ?>
        </fieldset>
			</tr>
		</table>
		</fieldset>
		<td>
	<tr>
	<tr>
		<td colspan="2">
		<fieldset><legend> <b>Dados referentes ao registro de imóveis</b></legend>
		<table>
			
			<tr>
				<td nowrap title="<?=@$Tj04_setorregimovel?>" width="200"><?
				db_ancora(@$Lj04_setorregimovel,"js_pesquisaj04_setorregimovel(true);",$db_opcao);
				?></td>
				<td><?
				db_input('j04_setorregimovel',10,$Ij04_setorregimovel,true,'text',$db_opcao," onchange='js_pesquisaj04_setorregimovel(false);'")
				?> <?
				db_input('j69_descr',56,$Ij69_descr,true,'text',3,'')
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj04_matricregimo?>"><?=@$Lj04_matricregimo?>
				</td>
				<td><?
				db_input('j04_matricregimo',10,$Ij04_matricregimo,true,'text',$db_opcao,"")
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj04_quadraregimo?>"><?=@$Lj04_quadraregimo?>
				</td>
				<td><?
				db_input('j04_quadraregimo',10,$Ij04_quadraregimo,true,'text',$db_opcao,"")
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tj04_loteregimo?>"><?=@$Lj04_loteregimo?></td>
				<td><?
				db_input('j04_loteregimo',10,$Ij04_loteregimo,true,'text',$db_opcao,"")
				?></td>
			</tr>
		</table>
		</fieldset>
		</td>
	</tr>


</table>
<input
	name="<?=($db_opcao==1?"incluir":"alterar")?>" type="submit"
	id="db_opcao"
	value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>"
	<?=($db_botao==false?"disabled":"")?> onclick="return js_verinome()">
<script>
function js_pesquisa_j107_sequencial(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe_condominio','func_condominio.php?funcao_js=parent.js_mostra_j107_sequencial1|j107_sequencial|j107_nome','Pesquisa',true);
  }else{
     if(document.form1.j107_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe_condominio','func_condominio.php?pesquisa_chave='+document.form1.j107_sequencial.value+'&funcao_js=parent.js_mostra_j107_sequencial','Pesquisa',false);
     }else{
       document.form1.j107_nome.value = ''; 
     }
  }
}
function js_mostra_j107_sequencial(chave,erro){
  document.form1.j107_nome.value = chave; 
  if(erro==true){ 
    document.form1.j107_sequencial.focus(); 
    document.form1.j107_sequencial.value = ''; 
  }else{
  	js_processaRequest();
  }
}
function js_mostra_j107_sequencial1(chave1,chave2){
  document.form1.j107_sequencial.value 	= chave1;
  document.form1.j107_nome.value 				= chave2;
  db_iframe_condominio.hide();
  js_processaRequest();
}

function js_processaRequest(){

  js_divCarregando('Aguarde Processando...','msgCarrega');

  var url       = 'cad1_iptubaseRPC.php';
  var parametro = 'j107_sequencial='+document.form1.j107_sequencial.value;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:js_loadSelect});

}

function js_loadSelect(resposta){

  //var d = document.form1;
  eval('var objJ = '+resposta.responseText+';');

  if(objJ == "Vazio"){
  	if($('predios')){
  		$('predios').value = 0 ;
  	}  	
  	//$('predios').value = 0;
  	$('sltpredio').innerHTML = "";
  	$('predio').innerHTML		 = "";
  	
    //js_mostraobrasconstr("","",true);
    js_removeObj('msgCarrega');  
    return false;
  }
  
  var objSelect;
  objSelect  = '<select name="predios" id="predios">';
  if( objJ.length > 1){
  	objSelect +='<option value="0" selected>NENHUM</option>';
  }
  for(i = 0; i < objJ.length; i++){
  		objSelect +='<option value="'+ objJ[i].j111_sequencial +'">'+objJ[i].j111_nome.urlDecode()+'</option>';
  }
  
  objSelect += '</select>';
  $('sltpredio').innerHTML = objSelect;
  $('predio').innerHTML		 = '<b>Prédio:</b>';
  js_removeObj('msgCarrega');
  
}

function js_pesquisaj01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptubase','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&testanome=true','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_iptubase','func_nome','func_nome.php?pesquisa_chave='+document.form1.j01_numcgm.value+'&funcao_js=parent.js_mostracgm',false,0);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j01_numcgm.focus(); 
    document.form1.j01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.j01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}
function js_pesquisaj01_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|0|1','Pesquisa',true,0);
   }else{
    js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe_lote', 'func_lote.php?pesquisa_chave='+document.form1.j01_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false,0);
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.j01_idbql.focus(); 
    document.form1.j01_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.j01_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe_lote.hide();
}
function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe', 'func_iptubase.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true,0);

}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_pesquisaj04_setorregimovel(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe_setorregimovel','func_setorregimovel.php?funcao_js=parent.js_mostrasetorregimovel1|j69_sequencial|j69_descr','Pesquisa',true);
  }else{
     if(document.form1.j04_setorregimovel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_iptubase','db_iframe_setorregimovel','func_setorregimovel.php?pesquisa_chave='+document.form1.j04_setorregimovel.value+'&funcao_js=parent.js_mostrasetorregimovel','Pesquisa',false);
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

</script>