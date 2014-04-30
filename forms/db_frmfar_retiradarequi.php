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
$clfar_retiradarequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa04_i_codigo");
$clrotulo->label("m40_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa07_i_codigo?>">
       <?=@$Lfa07_i_codigo?>
    </td>
    <td> 
<?
db_input('fa07_i_codigo',5,$Ifa07_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa07_i_retirada?>">
       <?
       db_ancora(@$Lfa07_i_retirada,"js_pesquisafa07_i_retirada(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa07_i_retirada',5,$Ifa07_i_retirada,true,'text',$db_opcao," onchange='js_pesquisafa07_i_retirada(false);'")
?>
       <?
db_input('fa04_i_codigo',5,$Ifa04_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa07_i_matrequi?>">
       <?
       db_ancora(@$Lfa07_i_matrequi,"js_pesquisafa07_i_matrequi(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa07_i_matrequi',5,$Ifa07_i_matrequi,true,'text',$db_opcao," onchange='js_pesquisafa07_i_matrequi(false);'")
?>
       <?
db_input('m40_codigo',10,$Im40_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisafa07_i_retirada(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?funcao_js=parent.js_mostrafar_retirada1|fa04_i_codigo|fa04_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.fa07_i_retirada.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?pesquisa_chave='+document.form1.fa07_i_retirada.value+'&funcao_js=parent.js_mostrafar_retirada','Pesquisa',false);
     }else{
       document.form1.fa04_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_retirada(chave,erro){
  document.form1.fa04_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.fa07_i_retirada.focus(); 
    document.form1.fa07_i_retirada.value = ''; 
  }
}
function js_mostrafar_retirada1(chave1,chave2){
  document.form1.fa07_i_retirada.value = chave1;
  document.form1.fa04_i_codigo.value = chave2;
  db_iframe_far_retirada.hide();
}
function js_pesquisafa07_i_matrequi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matrequi','func_matrequi.php?funcao_js=parent.js_mostramatrequi1|m40_codigo|m40_codigo','Pesquisa',true);
  }else{
     if(document.form1.fa07_i_matrequi.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matrequi','func_matrequi.php?pesquisa_chave='+document.form1.fa07_i_matrequi.value+'&funcao_js=parent.js_mostramatrequi','Pesquisa',false);
     }else{
       document.form1.m40_codigo.value = ''; 
     }
  }
}
function js_mostramatrequi(chave,erro){
  document.form1.m40_codigo.value = chave; 
  if(erro==true){ 
    document.form1.fa07_i_matrequi.focus(); 
    document.form1.fa07_i_matrequi.value = ''; 
  }
}
function js_mostramatrequi1(chave1,chave2){
  document.form1.fa07_i_matrequi.value = chave1;
  document.form1.m40_codigo.value = chave2;
  db_iframe_matrequi.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_retiradarequi','func_far_retiradarequi.php?funcao_js=parent.js_preenchepesquisa|fa07_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_retiradarequi.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>