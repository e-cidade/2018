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

//MODULO: Agua
$claguacalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x19_descr");
$clrotulo->label("x01_numcgm");
if($db_opcao==1){
  $db_action="agu1_aguacalc004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="agu1_aguacalc005.php";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="agu1_aguacalc006.php";
}
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
	<tr>
		<td nowrap title="<?=@$Tx22_codcalc?>"><?=@$Lx22_codcalc?></td>
		<td>
		<?
		  db_input('x22_codcalc',10,$Ix22_codcalc,true,'text',3,"")
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx22_codconsumo?>">
		<?
		  db_ancora(@$Lx22_codconsumo,"js_pesquisax22_codconsumo(true);",$db_opcao);
		?>
		</td>
		<td>
		<?
		  db_input('x22_codconsumo',10,$Ix22_codconsumo,true,'text',$db_opcao," onchange='js_pesquisax22_codconsumo(false);'")
		?> 
		<?
		  db_input('x19_descr',50,$Ix19_descr,true,'text',3,'')
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx22_exerc?>"><?=@$Lx22_exerc?></td>
		<td>
		<?
		  db_input('x22_exerc',10,$Ix22_exerc,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx22_mes?>"><?=@$Lx22_mes?></td>
		<td>
		<?
		  db_input('x22_mes',10,$Ix22_mes,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx22_matric?>">
		<?
		  db_ancora(@$Lx22_matric,"js_pesquisax22_matric(true);",$db_opcao);
		?>
		</td>
		<td>
		<?
		  db_input('x22_matric',10,$Ix22_matric,true,'text',$db_opcao," onchange='js_pesquisax22_matric(false);'")
		?> 
		<?
		  db_input('x01_numcgm',50,$Ix01_numcgm,true,'text',3,'')
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx22_area?>"><?=@$Lx22_area?></td>
		<td>
		<?
		  db_input('x22_area',10,$Ix22_area,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx22_numpre?>"><?=@$Lx22_numpre?></td>
		<td>
		<?
		  db_input('x22_numpre',10,$Ix22_numpre,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>
	<!-- <tr>
		<td nowrap title="<?=@$Tx22_manual?>"><?=@$Lx22_manual?></td>
		<td>
		<?
		  db_textarea('x22_manual',0,0,$Ix22_manual,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>
  -->
  <?
    $x22_tipo = 1;
    db_input('x22_tipo', 10, $Ix22_tipo, true, 'hidden', $db_opcao, ''); 
    
    $x22_hora = date('H:i');
    db_input('x22_hora', 10, $Ix22_hora, true, 'hidden', $db_opcao, '');
    
    $x22_usuario = db_getsession('DB_id_usuario');
    db_input('x22_usuario', 10, $Ix22_usuario, true, 'hidden', $db_opcao, '');
    
    $data = explode("/", date("d/m/Y"));
    
    $x22_data_dia = $data[0];
    $x22_data_mes = $data[1];
    $x22_data_ano = $data[2];
    
    db_inputdata('x22_data',@$x22_data_dia,@$x22_data_mes,@$x22_data_ano,true,'hidden',$db_opcao,"");
  ?>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>> 
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script>
function js_pesquisax22_codconsumo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacalc','db_iframe_aguaconsumo','func_aguaconsumo.php?funcao_js=parent.js_mostraaguaconsumo1|x19_codconsumo|x19_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x22_codconsumo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacalc','db_iframe_aguaconsumo','func_aguaconsumo.php?pesquisa_chave='+document.form1.x22_codconsumo.value+'&funcao_js=parent.js_mostraaguaconsumo','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.x19_descr.value = ''; 
     }
  }
}
function js_mostraaguaconsumo(chave,erro){
  document.form1.x19_descr.value = chave; 
  if(erro==true){ 
    document.form1.x22_codconsumo.focus(); 
    document.form1.x22_codconsumo.value = ''; 
  }
}
function js_mostraaguaconsumo1(chave1,chave2){
  document.form1.x22_codconsumo.value = chave1;
  document.form1.x19_descr.value = chave2;
  db_iframe_aguaconsumo.hide();
}
function js_pesquisax22_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacalc','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x22_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacalc','db_iframe_aguabase','func_aguabase.php?pesquisa_chave='+document.form1.x22_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.x01_numcgm.value = ''; 
     }
  }
}
function js_mostraaguabase(chave,erro){
  document.form1.x01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.x22_matric.focus(); 
    document.form1.x22_matric.value = ''; 
  }
}
function js_mostraaguabase1(chave1,chave2){
  document.form1.x22_matric.value = chave1;
  document.form1.x01_numcgm.value = chave2;
  db_iframe_aguabase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_aguacalc','db_iframe_aguacalc','func_aguacalc.php?funcao_js=parent.js_preenchepesquisa|x22_codcalc','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_aguacalc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>