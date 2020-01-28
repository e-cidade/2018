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

//MODULO: arrecadacao
$clgrupotaxa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ar38_descricao");
?>
<form name="form1" method="post" action="">
<center>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro Grupo de Taxa</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tar37_sequencial?>">
       <?=@$Lar37_sequencial?>
    </td>
    <td> 
<?
db_input('ar37_sequencial',10,$Iar37_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar37_grupotaxatipo?>">
       <?
       db_ancora(@$Lar37_grupotaxatipo,"js_pesquisaar37_grupotaxatipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ar37_grupotaxatipo',10,$Iar37_grupotaxatipo,true,'text',$db_opcao," onchange='js_pesquisaar37_grupotaxatipo(false);'")
?>
       <?
db_input('ar38_descricao',40,$Iar38_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tar37_descricao?>">
       <?=@$Lar37_descricao?>
    </td>
    <td> 
<?
db_input('ar37_descricao',54,$Iar37_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
</fieldset>  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaar37_grupotaxatipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_grupotaxatipo','func_grupotaxatipo.php?funcao_js=parent.js_mostragrupotaxatipo1|ar38_sequencial|ar38_descricao','Pesquisa',true);
  }else{
     if(document.form1.ar37_grupotaxatipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_grupotaxatipo','func_grupotaxatipo.php?pesquisa_chave='+document.form1.ar37_grupotaxatipo.value+'&funcao_js=parent.js_mostragrupotaxatipo','Pesquisa',false);
     }else{
       document.form1.ar38_descricao.value = ''; 
     }
  }
}
function js_mostragrupotaxatipo(chave,erro){
  document.form1.ar38_descricao.value = chave; 
  if(erro==true){ 
    document.form1.ar37_grupotaxatipo.focus(); 
    document.form1.ar37_grupotaxatipo.value = ''; 
  }
}
function js_mostragrupotaxatipo1(chave1,chave2){
  document.form1.ar37_grupotaxatipo.value = chave1;
  document.form1.ar38_descricao.value = chave2;
  db_iframe_grupotaxatipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_grupotaxa','func_grupotaxa.php?funcao_js=parent.js_preenchepesquisa|ar37_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_grupotaxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>