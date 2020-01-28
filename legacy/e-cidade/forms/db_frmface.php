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

$clface->rotulo->label();
$clcarface->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("j30_descr");
$clrotulo->label("j14_nome");
?>
<fieldset>
 <legend>Face</legend>
<form	name="form1" method="post" action="">
  <input name="deuok"       type="hidden" id="" value="" />
  <input name="alterarface" type="hidden" id="" value="" />

<table border="0">
	<tr>
		<td nowrap title="<?=@$Tj37_face?>"><?=@$Lj37_face?></td>
		<td><?
		db_input('j37_face',4,$Ij37_face,true,'text',"3","")
		?>
		<td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tj37_setor?>"><?
		db_ancora(@$Lj37_setor,"js_pesquisaj37_setor(true);",$db_opcao);
		?></td>
		<td><?
		db_input('j37_setor',4,$Ij37_setor,true,'text',$db_opcao," onchange='js_pesquisaj37_setor(false);'");
		db_input('j30_descr',40,$Ij30_descr,true,'text',3,'');
		?>
		</td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tj37_quadra?>"><?=@$Lj37_quadra?></td>
		<td><?
		$val=$Ij37_quadra;
		$result_param = $clcfiptu->sql_record($clcfiptu->sql_query(db_getsession("DB_anousu"),"j18_formatquadra"));
		if ($clcfiptu->numrows>0){
		  db_fieldsmemory($result_param,0);
		  if ($j18_formatquadra==1){
		    $val = 3;
		  }else{
		    $val = 1;
		  }
		}
		db_input('j37_quadra',4,$val,true,'text',$db_opcao,"");
		?>
		</td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tj37_codigo?>"><?
		db_ancora(@$Lj37_codigo,"js_pesquisaj37_codigo(true);",$db_opcao);
		?></td>
		<td><?
		db_input('j37_codigo',4,$Ij37_codigo,true,'text',$db_opcao," onchange='js_pesquisaj37_codigo(false);'");
		db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
		?>
		</td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tj37_lado?>"><?=@$Lj37_lado?></td>
		<td><?
		$matriz = array('I'=>"Impar",'P'=>"Par");
		db_select('j37_lado',$matriz,true,$db_opcao);
		?>
		</td>
	</tr>
    <?php
    db_input('j37_valor',15,$Ij37_valor,true,'hidden',$db_opcao,"");
    db_input('j37_vlcons',15,$Ij37_vlcons,true,'hidden',$db_opcao,"");
    ?>
    <td>
  </tr>

	<tr>
		<td nowrap title="<?=@$Tj37_exten?>"><?=@$Lj37_exten?></td>
		<td><?
		db_input('j37_exten',15,$Ij37_exten,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tj37_profr?>"><?=@$Lj37_profr?></td>
		<td><?
		db_input('j37_profr',15,$Ij37_profr,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>

	<tr>
		<td nowrap title="<?=@$Tj37_outros?>"><?=@$Lj37_outros?></td>
		<td><?
		db_input('j37_outros',40,$Ij37_outros,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>

	<tr>
		<td nowrap><?
		db_ancora(@$Lcarface,"js_carface();",$db_opcao);
    echo "</td><td>";
    db_input('caracteristica',15,0,true,'hidden',$db_opcao)
  ?>
		</td>
	</tr>
</table>

</fieldset>

  <?php
  $sql="select * from caracter inner join cargrup on j32_grupo=j31_grupo where j32_tipo='F'";
  $result= db_query($sql);
  if (pg_numrows($result)!=0){
    ?> <input
	name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
	<?=($db_botao==false?"disabled":"")?>
	onclick="return testacar()"> <!--<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return testacar()" >-->
	<?}else{?> <input
	name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
	<?=($db_botao==false?"disabled":"")?>> <!--<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >-->
	<?}?>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

</form>
<script type="text/javascript">

function js_confirma(numerotestadas){
  if(numerotestadas != '0'){
    var confirmacao = confirm('A face selecionada esta ligada a '+numerotestadas+' testadas deseja realmente alterar?');
    if(confirmacao){
      document.form1.alterarface.value='t';
      document.form1.submit();
    }

  }else{
     document.form1.alterarface.value='t';
     document.form1.submit();
  }
}

function testacar(){
  if(document.form1.caracteristica.value == ""){
    alert('Preencha as características da face!');
    return false
  }else{
    <?if ($db_opcao== 2 || $db_opcao== 22){?>
  	  var face = document.form1.j37_face.value;
	  js_OpenJanelaIframe('','db_iframe_facerua','cad1_face007.php?face='+face,'Pesquisa',false);
   <?}else{?>
    return true;
    <?}?>
  }
  return false
}

function js_carface(){

  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=F&codigo='+document.form1.j37_face.value
   }else{
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=F&codigo='+document.form1.j37_face.value
   }

    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}

function js_pesquisaj37_setor(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_setor.php?funcao_js=parent.js_mostrasetor1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_setor.php?pesquisa_chave='+document.form1.j37_setor.value+'&funcao_js=parent.js_mostrasetor';
  }
}

function js_mostrasetor(chave,erro){
  document.form1.j30_descr.value = chave;
  if(erro==true){
    document.form1.j37_setor.focus();
    document.form1.j37_setor.value = '';
  }
}
function js_mostrasetor1(chave1,chave2){
  document.form1.j37_setor.value = chave1;
  document.form1.j30_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisaj37_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j37_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.j37_codigo.focus();
    document.form1.j37_codigo.value = '';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j37_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_face.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
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
	?>