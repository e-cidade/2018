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
$clfar_retiradarequisitante->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("fa04_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa08_i_codigo?>">
       <?=@$Lfa08_i_codigo?>
    </td>
    <td> 
<?
db_input('fa08_i_codigo',5,$Ifa08_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa08_i_cgsund?>">
       <?
       db_ancora(@$Lfa08_i_cgsund,"js_pesquisafa08_i_cgsund(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa08_i_cgsund',5,$Ifa08_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisafa08_i_cgsund(false);'")
?>
       <?
db_input('z01_i_cgsund',6,$Iz01_i_cgsund,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa08_i_retirada?>">
       <?
       db_ancora(@$Lfa08_i_retirada,"js_pesquisafa08_i_retirada(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa08_i_retirada',5,$Ifa08_i_retirada,true,'text',$db_opcao," onchange='js_pesquisafa08_i_retirada(false);'")
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
function js_pesquisafa08_i_cgsund(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_i_cgsund','Pesquisa',true);
  }else{
     if(document.form1.fa08_i_cgsund.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.fa08_i_cgsund.value+'&funcao_js=parent.js_mostracgs_und','Pesquisa',false);
     }else{
       document.form1.z01_i_cgsund.value = ''; 
     }
  }
}
function js_mostracgs_und(chave,erro){
  document.form1.z01_i_cgsund.value = chave; 
  if(erro==true){ 
    document.form1.fa08_i_cgsund.focus(); 
    document.form1.fa08_i_cgsund.value = ''; 
  }
}
function js_mostracgs_und1(chave1,chave2){
  document.form1.fa08_i_cgsund.value = chave1;
  document.form1.z01_i_cgsund.value = chave2;
  db_iframe_cgs_und.hide();
}
function js_pesquisafa08_i_retirada(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?funcao_js=parent.js_mostrafar_retirada1|fa04_i_codigo|fa04_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.fa08_i_retirada.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?pesquisa_chave='+document.form1.fa08_i_retirada.value+'&funcao_js=parent.js_mostrafar_retirada','Pesquisa',false);
     }else{
       document.form1.fa04_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_retirada(chave,erro){
  document.form1.fa04_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.fa08_i_retirada.focus(); 
    document.form1.fa08_i_retirada.value = ''; 
  }
}
function js_mostrafar_retirada1(chave1,chave2){
  document.form1.fa08_i_retirada.value = chave1;
  document.form1.fa04_i_codigo.value = chave2;
  db_iframe_far_retirada.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_retiradarequisitante','func_far_retiradarequisitante.php?funcao_js=parent.js_preenchepesquisa|fa08_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_retiradarequisitante.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>