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
$clfar_retiradaitens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa04_i_codigo");
$clrotulo->label("m77_lote");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa06_i_codigo?>">
       <?=@$Lfa06_i_codigo?>
    </td>
    <td> 
<?
db_input('fa06_i_codigo',5,$Ifa06_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa06_t_posologia?>">
       <?=@$Lfa06_t_posologia?>
    </td>
    <td> 
<?
db_textarea('fa06_t_posologia',1,50,$Ifa06_t_posologia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa06_i_retirada?>">
       <?
       db_ancora(@$Lfa06_i_retirada,"js_pesquisafa06_i_retirada(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa06_i_retirada',5,$Ifa06_i_retirada,true,'text',$db_opcao," onchange='js_pesquisafa06_i_retirada(false);'")
?>
       <?
db_input('fa04_i_codigo',5,$Ifa04_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisafa06_i_retirada(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?funcao_js=parent.js_mostrafar_retirada1|fa04_i_codigo|fa04_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.fa06_i_retirada.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?pesquisa_chave='+document.form1.fa06_i_retirada.value+'&funcao_js=parent.js_mostrafar_retirada','Pesquisa',false);
     }else{
       document.form1.fa04_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_retirada(chave,erro){
  document.form1.fa04_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.fa06_i_retirada.focus(); 
    document.form1.fa06_i_retirada.value = ''; 
  }
}
function js_mostrafar_retirada1(chave1,chave2){
  document.form1.fa06_i_retirada.value = chave1;
  document.form1.fa04_i_codigo.value = chave2;
  db_iframe_far_retirada.hide();
}
function js_pesquisafa06_i_matestoqueitemlote(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitemlote','func_matestoqueitemlote.php?funcao_js=parent.js_mostramatestoqueitemlote1|m77_sequencial|m77_lote','Pesquisa',true);
  }else{
     if(document.form1.fa06_i_matestoqueitemlote.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitemlote','func_matestoqueitemlote.php?pesquisa_chave='+document.form1.fa06_i_matestoqueitemlote.value+'&funcao_js=parent.js_mostramatestoqueitemlote','Pesquisa',false);
     }else{
       document.form1.m77_lote.value = ''; 
     }
  }
}
function js_mostramatestoqueitemlote(chave,erro){
  document.form1.m77_lote.value = chave; 
  if(erro==true){ 
    document.form1.fa06_i_matestoqueitemlote.focus(); 
    document.form1.fa06_i_matestoqueitemlote.value = ''; 
  }
}
function js_mostramatestoqueitemlote1(chave1,chave2){
  document.form1.fa06_i_matestoqueitemlote.value = chave1;
  document.form1.m77_lote.value = chave2;
  db_iframe_matestoqueitemlote.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_retiradaitens','func_far_retiradaitens.php?funcao_js=parent.js_preenchepesquisa|fa06_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_retiradaitens.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>