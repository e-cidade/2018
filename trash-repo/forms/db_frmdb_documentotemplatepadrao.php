<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
$cldb_documentotemplatepadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db82_descricao");
$clrotulo->label("nomeinst");

if (isset($db81_nomearquivo)) {
  $sUrlArquivo = $db81_nomearquivo;
}
?>
<form name="form1" method="post" action="">
<table style="margin-top: 20px;"> 
<tr>
	<td>
		<fieldset>
			<legend><b>Cadastro de Template Padrão</b></legend>
			<center>
			<table border="0">
			  <tr>
			    <td nowrap title="<?=@$Tdb81_sequencial?>">
			       <?=@$Ldb81_sequencial?>
			    </td>
			    <td> 
					<?
					db_input('db81_sequencial',10,$Idb81_sequencial,true,'text',3,"")
					?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tdb81_templatetipo?>">
			       <?
			       db_ancora(@$Ldb81_templatetipo,"js_pesquisadb81_templatetipo(true);",$db_opcao);
			       ?>
			    </td>
			    <td> 
						<?
						db_input('db81_templatetipo',10,$Idb81_templatetipo,true,'text',$db_opcao," onchange='js_pesquisadb81_templatetipo(false);'")
						?>
			       <?
							db_input('db82_descricao',50,$Idb82_descricao,true,'text',3,'')
			       ?>
			    </td>
			  </tr>
			  <?/* 
			  <tr>
			    <td nowrap title="<?=@$Tdb81_instit?>">
			       <?
			       db_ancora(@$Ldb81_instit,"js_pesquisadb81_instit(true);",$db_opcao);
			       ?>
			    </td>
			    <td> 
						<?
						db_input('db81_instit',10,$Idb81_instit,true,'text',$db_opcao," onchange='js_pesquisadb81_instit(false);'")
						?>
			       <?
							db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
			       ?>
			    </td>
			  </tr>
			   */?>
			  <tr>
			    <td nowrap title="<?=@$Tdb81_descricao?>">
			       <?=@$Ldb81_descricao?>
			    </td>
			    <td> 
						<?
						db_input('db81_descricao',64,$Idb81_descricao,true,'text',$db_opcao,"")
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tdb81_nomearquivo?>">
			       <?=@$Ldb81_nomearquivo?>
			    </td>
			    <td> 
						<?
						db_input('db81_nomearquivo',64,$Idb81_nomearquivo,true,'text',$db_opcao,"")
						?>
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
<?
  if ($db_opcao == 2) {
   echo "<input name=\"download\" type=\"button\" id=\"download\" value=\"Download\" onclick=\"js_download('$sUrlArquivo');\">";
  }
?>
</form>
<script>
function js_download(sUrlArquivo) {
  location.href = sUrlArquivo;
}

function js_pesquisadb81_templatetipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documentotemplate','func_db_documentotemplatetipo.php?funcao_js=parent.js_mostradb_documentotemplate1|db80_sequencial|db80_descricao','Pesquisa',true);
  }else{
     if(document.form1.db81_templatetipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documentotemplate','func_db_documentotemplatetipo.php?pesquisa_chave='+document.form1.db81_templatetipo.value+'&funcao_js=parent.js_mostradb_documentotemplate','Pesquisa',false);
     }else{
       document.form1.db82_descricao.value = ''; 
     }
  }
}
function js_mostradb_documentotemplate(chave,erro){
  document.form1.db82_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db81_templatetipo.focus(); 
    document.form1.db81_templatetipo.value = ''; 
  }
}
function js_mostradb_documentotemplate1(chave1,chave2){
  document.form1.db81_templatetipo.value = chave1;
  document.form1.db82_descricao.value = chave2;
  db_iframe_db_documentotemplate.hide();
}
function js_pesquisadb81_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.db81_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.db81_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.db81_instit.focus(); 
    document.form1.db81_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.db81_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_documentotemplatepadrao','func_db_documentotemplatepadrao.php?funcao_js=parent.js_preenchepesquisa|db81_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_documentotemplatepadrao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>