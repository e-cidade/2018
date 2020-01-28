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

//MODULO: projetos
$clobrashabiteprot->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ob09_habite");
$clrotulo->label("p58_codproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tob19_codhab?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lob19_codhab,"js_pesquisaob19_codhab(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob19_codhab',10,$Iob19_codhab,true,'text',$db_opcao," onchange='js_pesquisaob19_codhab(false);'")
?>
       <?
db_input('ob09_habite',15,$Iob09_habite,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob19_codproc?>">
       <?
       db_ancora(@$Lob19_codproc,"js_pesquisaob19_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob19_codproc',10,$Iob19_codproc,true,'text',$db_opcao," onchange='js_pesquisaob19_codproc(false);'")
?>
       <?
db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaob19_codhab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?funcao_js=parent.js_mostraobrashabite1|ob09_codhab|ob09_habite','Pesquisa',true);
  }else{
     if(document.form1.ob19_codhab.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?pesquisa_chave='+document.form1.ob19_codhab.value+'&funcao_js=parent.js_mostraobrashabite','Pesquisa',false);
     }else{
       document.form1.ob09_habite.value = ''; 
     }
  }
}
function js_mostraobrashabite(chave,erro){
  document.form1.ob09_habite.value = chave; 
  if(erro==true){ 
    document.form1.ob19_codhab.focus(); 
    document.form1.ob19_codhab.value = ''; 
  }
}
function js_mostraobrashabite1(chave1,chave2){
  document.form1.ob19_codhab.value = chave1;
  document.form1.ob09_habite.value = chave2;
  db_iframe_obrashabite.hide();
}
function js_pesquisaob19_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.ob19_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.ob19_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.ob19_codproc.focus(); 
    document.form1.ob19_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.ob19_codproc.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrashabiteprot','func_obrashabiteprot.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrashabiteprot.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>