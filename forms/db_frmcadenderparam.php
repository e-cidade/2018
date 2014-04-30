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
$clcadenderparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db70_descricao");
$clrotulo->label("db71_descricao");
$clrotulo->label("db72_descricao");
?>
<form name="form1" method="post" action="">
  <?
  db_input('db99_sequencial',10,$Idb99_sequencial,true,'hidden',3,"")
  ?>
<center>
<table width="800" align="center" style="margin-top: 20px;">
  <tr>
  <td>
  <fieldset><legend><b>Parâmetros do Endereço</b></legend>
		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tdb99_cadenderpais?>">
		       <?
		       db_ancora(@$Ldb99_cadenderpais,"js_pesquisadb99_cadenderpais(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?
					db_input('db99_cadenderpais',10,$Idb99_cadenderpais,true,'text',$db_opcao," onchange='js_pesquisadb99_cadenderpais(false);'")
					?>
		      <?
		      db_input('db70_descricao',40,$Idb70_descricao,true,'text',3,'')
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tdb99_cadenderestado?>">
		       <?
		       db_ancora(@$Ldb99_cadenderestado,"js_pesquisadb99_cadenderestado(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?
					db_input('db99_cadenderestado',10,$Idb99_cadenderestado,true,'text',$db_opcao," onchange='js_pesquisadb99_cadenderestado(false);'")
					?>
		      <?
		      db_input('db71_descricao',40,$Idb71_descricao,true,'text',3,'')
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tdb99_cadendermunicipio?>">
		       <?
		       db_ancora(@$Ldb99_cadendermunicipio,"js_pesquisadb99_cadendermunicipio(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?
					db_input('db99_cadendermunicipio',10,$Idb99_cadendermunicipio,true,'text',$db_opcao," onchange='js_pesquisadb99_cadendermunicipio(false);'")
		      ?>
		      <?
		       db_input('db72_descricao',40,$Idb72_descricao,true,'text',3,'')
		      ?>
		    </td>
		  </tr>
		</table>
  </fieldset>
  </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb99_cadenderpais(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadenderpais','func_cadenderpais.php?funcao_js=parent.js_mostracadenderpais1|db70_sequencial|db70_descricao','Pesquisa',true);
  }else{
     if(document.form1.db99_cadenderpais.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadenderpais','func_cadenderpais.php?pesquisa_chave='+document.form1.db99_cadenderpais.value+'&funcao_js=parent.js_mostracadenderpais','Pesquisa',false);
     }else{
       document.form1.db70_descricao.value = ''; 
     }
  }
}
function js_mostracadenderpais(chave,erro){
  document.form1.db70_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db99_cadenderpais.focus(); 
    document.form1.db99_cadenderpais.value = ''; 
  }
}
function js_mostracadenderpais1(chave1,chave2){
  document.form1.db99_cadenderpais.value = chave1;
  document.form1.db70_descricao.value = chave2;
  db_iframe_cadenderpais.hide();
}
function js_pesquisadb99_cadenderestado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadenderestado','func_cadenderestado.php?funcao_js=parent.js_mostracadenderestado1|db71_sequencial|db71_descricao','Pesquisa',true);
  }else{
     if(document.form1.db99_cadenderestado.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadenderestado','func_cadenderestado.php?pesquisa_chave='+document.form1.db99_cadenderestado.value+'&funcao_js=parent.js_mostracadenderestado','Pesquisa',false);
     }else{
       document.form1.db71_descricao.value = ''; 
     }
  }
}
function js_mostracadenderestado(chave,erro){
  document.form1.db71_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db99_cadenderestado.focus(); 
    document.form1.db99_cadenderestado.value = ''; 
  }
}
function js_mostracadenderestado1(chave1,chave2){
  document.form1.db99_cadenderestado.value = chave1;
  document.form1.db71_descricao.value = chave2;
  db_iframe_cadenderestado.hide();
}
function js_pesquisadb99_cadendermunicipio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?funcao_js=parent.js_mostracadendermunicipio1|db72_sequencial|db72_descricao','Pesquisa',true);
  }else{
     if(document.form1.db99_cadendermunicipio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?pesquisa_chave='+document.form1.db99_cadendermunicipio.value+'&funcao_js=parent.js_mostracadendermunicipio','Pesquisa',false);
     }else{
       document.form1.db72_descricao.value = ''; 
     }
  }
}
function js_mostracadendermunicipio(chave,erro){
  document.form1.db72_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db99_cadendermunicipio.focus(); 
    document.form1.db99_cadendermunicipio.value = ''; 
  }
}
function js_mostracadendermunicipio1(chave1,chave2){
  document.form1.db99_cadendermunicipio.value = chave1;
  document.form1.db72_descricao.value = chave2;
  db_iframe_cadendermunicipio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadenderparam','func_cadenderparam.php?funcao_js=parent.js_preenchepesquisa|db99_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadenderparam.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>