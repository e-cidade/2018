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

//MODULO: Configuracoes
$cldb_documentotemplate->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db80_descricao");
$clrotulo->label("nomeinst");

if (isset($db82_arquivo)) {
  $sUrlArquivo = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?oidarq=".$db82_arquivo;
}
?>
<form name="form1" method="post" action="" enctype="multipart/form-data" onsubmit="return js_valida();">
<table style="margin-top: 20px;">
<tr>
	<td>
		<fieldset>
			<legend><b>Cadastro de Template </b></legend>
		<center>
		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tdb82_sequencial?>">
		       <?=@$Ldb82_sequencial?>
		    </td>
		    <td>
					<?
					db_input('db82_sequencial',10,$Idb82_sequencial,true,'text',3,"")
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tdb82_templatetipo?>">
		       <?
		       db_ancora(@$Ldb82_templatetipo,"js_pesquisadb82_templatetipo(true);",$db_opcao);
		       ?>
		    </td>
		    <td>
					<?
					db_input('db82_templatetipo',10,$Idb82_templatetipo,true,'text',$db_opcao," onchange='js_pesquisadb82_templatetipo(false);'")
					?>
		      <?
					db_input('db80_descricao',40,$Idb80_descricao,true,'text',3,'');
		      ?>
		    </td>
		  </tr>
		  <?php /*
		  <tr>
		    <td nowrap title="<?=@$Tdb82_instit?>">
		       <?
		       //db_ancora(@$Ldb82_instit,"js_pesquisadb82_instit(true);",$db_opcao);
		       ?>
		    </td>
		    <td>
					<?
					db_input('db82_instit',10,$Idb82_instit,true,'hidden',$db_opcao," onchange='js_pesquisadb82_instit(false);'")
					?>
		      <?
					db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
		      ?>
		    </td>
		  </tr>
		  */ ?>
			<tr>
		    <td nowrap title="<?=@$Tdb82_descricao?>">
		       <?=@$Ldb82_descricao?>
		    </td>
		    <td>
					<?
					db_input('db82_descricao',53,$Idb82_descricao,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tdb82_arquivo?>">
		       <?=@$Ldb82_arquivo?>
		    </td>
		    <td id="arquivo">
				<?
				db_input('db82_arquivo',40,0,true,'file',$db_opcao,"")
				?>
				<input type="hidden" name="db82_arquivo1" id="db82_arquivo1" style="background-color: #DEB887" size="53" readonly="readonly">
				</td>
		  </tr>
		</table>
		</center>
		</fieldset>
	</td>
</tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="template" type="button" id="template" value="Importar Template Padrão" onclick="js_pesquisatemplate();" >
<input name="template" type="hidden" id="novo" value="Novo" onclick="js_novo();" >
<input name="vertemplates" type="button" id="vertemplates" value="Ver Templates" onclick="js_ver_templates();" >
<?
  if ($db_opcao == 2) {
   echo "<input name=\"download\" type=\"button\" id=\"download\" value=\"Download\" onclick=\"js_download('$sUrlArquivo');\">";
  }
?>
</form>
<script>
function js_download(sUrlArquivo) {
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_download',sUrlArquivo,'Download',false);
}

function js_ver_templates(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplatesver','con1_db_doctemplatepadraolistar.php','Pesquisa',true);
}

function js_pesquisadb82_templatetipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplatetipo','func_db_documentotemplatetipo.php?funcao_js=parent.js_mostradb_documentotemplatetipo1|db80_sequencial|db80_descricao','Pesquisa',true);
  }else{
     if(document.form1.db82_templatetipo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplatetipo','func_db_documentotemplatetipo.php?pesquisa_chave='+document.form1.db82_templatetipo.value+'&funcao_js=parent.js_mostradb_documentotemplatetipo','Pesquisa',false);
     }else{
       document.form1.db80_descricao.value = '';
     }
  }
}
function js_mostradb_documentotemplatetipo(chave,erro){
  document.form1.db80_descricao.value = chave;
  if(erro==true){
    document.form1.db82_templatetipo.focus();
    document.form1.db82_templatetipo.value = '';
  }
}
function js_mostradb_documentotemplatetipo1(chave1,chave2){
  document.form1.db82_templatetipo.value = chave1;
  document.form1.db80_descricao.value = chave2;
  db_iframe_db_documentotemplatetipo.hide();
}
function js_pesquisadb82_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.db82_instit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.db82_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = '';
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave;
  if(erro==true){
    document.form1.db82_instit.focus();
    document.form1.db82_instit.value = '';
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.db82_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplate','func_db_documentotemplate.php?funcao_js=parent.js_preenchepesquisa|db82_sequencial','Pesquisa',true);
}

function js_pesquisatemplate(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documentotemplatepadrao','func_db_documentotemplatepadrao.php?funcao_js=parent.js_preenche_template|db81_templatetipo|db81_nomearquivo','Pesquisa',true);
}

function js_preenche_template(chave1,chave2){
	document.getElementById('db82_arquivo').type = 'hidden';
	document.getElementById('template').type = 'hidden';
	document.getElementById('novo').type = 'button';
	document.form1.db82_templatetipo.value 	= chave1;
	document.getElementById('db82_arquivo1').type = 'text';
  document.getElementById('db82_arquivo1').value = chave2;

	db_iframe_db_documentotemplatepadrao.hide();

}

function js_novo(){
	document.getElementById('db82_arquivo1').type = 'hidden';
	document.getElementById('db82_arquivo1').value = '';
	document.getElementById('db82_arquivo').type = 'file';
	document.getElementById('novo').type = 'hidden';
	document.getElementById('template').type = 'button';
}

function js_preenchepesquisa(chave){
  db_iframe_db_documentotemplate.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_arquivo(){
	arquivo =  document.getElementById('arquivo');
	alert(arquivo.value);
	arquivo.innerHTML = arquivo.innerHTML+'<input type="file" name="db82_arquivo1" >';
}
function js_valida(){
	if(document.getElementById('db82_templatetipo').value.trim() == ""){
		alert('Usuário: \n\n Campo Template Tipo não Informado.\n\nAdministrador: \n\n');
		return false;
	}
	if(document.getElementById('db82_descricao').value.trim() == ""){
		alert('Usuário: \n\n Campo Documento não Informado.\n\nAdministrador: \n\n');
		return false;
	}
	arquivo1 = document.getElementById('db82_arquivo').value;
	arquivo2 = document.getElementById('db82_arquivo1').value;
	if(arquivo1.trim()=="" && arquivo2.trim()==""){
		alert('Usuário: \n\n Campo Arquivo não Informado.\n\nAdministrador: \n\n');
		return false;
	}else{
		return true;
	}

}
</script>
