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

//MODULO: Farmácia
$clfar_retiradaitemlote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa06_i_codigo");
$clrotulo->label("m71_codmatestoque");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa09_i_codigo?>">
       <?=@$Lfa09_i_codigo?>
    </td>
    <td> 
<?
db_input('fa09_i_codigo',5,$Ifa09_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa09_i_retiradaitens?>">
       <?
       db_ancora(@$Lfa09_i_retiradaitens,"js_pesquisafa09_i_retiradaitens(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa09_i_retiradaitens',5,$Ifa09_i_retiradaitens,true,'text',$db_opcao," onchange='js_pesquisafa09_i_retiradaitens(false);'")
?>
       <?
db_input('fa06_i_codigo',5,$Ifa06_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa09_i_matestoqueitem?>">
       <?
       db_ancora(@$Lfa09_i_matestoqueitem,"js_pesquisafa09_i_matestoqueitem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa09_i_matestoqueitem',5,$Ifa09_i_matestoqueitem,true,'text',$db_opcao," onchange='js_pesquisafa09_i_matestoqueitem(false);'")
?>
       <?
db_input('m71_codmatestoque',10,$Im71_codmatestoque,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa09_f_quant?>">
       <?=@$Lfa09_f_quant?>
    </td>
    <td> 
<?
db_input('fa09_f_quant',5,$Ifa09_f_quant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisafa09_i_retiradaitens(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_retiradaitens','func_far_retiradaitens.php?funcao_js=parent.js_mostrafar_retiradaitens1|fa06_i_codigo|fa06_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.fa09_i_retiradaitens.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_far_retiradaitens','func_far_retiradaitens.php?pesquisa_chave='+document.form1.fa09_i_retiradaitens.value+'&funcao_js=parent.js_mostrafar_retiradaitens','Pesquisa',false);
     }else{
       document.form1.fa06_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_retiradaitens(chave,erro){
  document.form1.fa06_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.fa09_i_retiradaitens.focus(); 
    document.form1.fa09_i_retiradaitens.value = ''; 
  }
}
function js_mostrafar_retiradaitens1(chave1,chave2){
  document.form1.fa09_i_retiradaitens.value = chave1;
  document.form1.fa06_i_codigo.value = chave2;
  db_iframe_far_retiradaitens.hide();
}
function js_pesquisafa09_i_matestoqueitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?funcao_js=parent.js_mostramatestoqueitem1|m71_codlanc|m71_codmatestoque','Pesquisa',true);
  }else{
     if(document.form1.fa09_i_matestoqueitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?pesquisa_chave='+document.form1.fa09_i_matestoqueitem.value+'&funcao_js=parent.js_mostramatestoqueitem','Pesquisa',false);
     }else{
       document.form1.m71_codmatestoque.value = ''; 
     }
  }
}
function js_mostramatestoqueitem(chave,erro){
  document.form1.m71_codmatestoque.value = chave; 
  if(erro==true){ 
    document.form1.fa09_i_matestoqueitem.focus(); 
    document.form1.fa09_i_matestoqueitem.value = ''; 
  }
}
function js_mostramatestoqueitem1(chave1,chave2){
  document.form1.fa09_i_matestoqueitem.value = chave1;
  document.form1.m71_codmatestoque.value = chave2;
  db_iframe_matestoqueitem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_retiradaitemlote','func_far_retiradaitemlote.php?funcao_js=parent.js_preenchepesquisa|fa09_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_retiradaitemlote.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>