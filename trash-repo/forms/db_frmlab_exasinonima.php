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

//MODULO: Laboratório
$cllab_exasinonima->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la10_i_codigo");
$clrotulo->label("la08_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla18_i_codigo?>">
       <?=@$Lla18_i_codigo?>
    </td>
    <td> 
<?
db_input('la18_i_codigo',10,$Ila18_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla18_i_sinonima?>">
       <?
       db_ancora(@$Lla18_i_sinonima,"js_pesquisala18_i_sinonima(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la18_i_sinonima',10,$Ila18_i_sinonima,true,'text',$db_opcao," onchange='js_pesquisala18_i_sinonima(false);'")
?>
       <?
db_input('la10_i_codigo',10,$Ila10_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla18_i_exame?>">
       <?
       db_ancora(@$Lla18_i_exame,"js_pesquisala18_i_exame(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la18_i_exame',10,$Ila18_i_exame,true,'text',$db_opcao," onchange='js_pesquisala18_i_exame(false);'")
?>
       <?
db_input('la08_i_codigo',10,$Ila08_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisala18_i_sinonima(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_sinonima','func_lab_sinonima.php?funcao_js=parent.js_mostralab_sinonima1|la10_i_codigo|la10_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la18_i_sinonima.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_sinonima','func_lab_sinonima.php?pesquisa_chave='+document.form1.la18_i_sinonima.value+'&funcao_js=parent.js_mostralab_sinonima','Pesquisa',false);
     }else{
       document.form1.la10_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_sinonima(chave,erro){
  document.form1.la10_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la18_i_sinonima.focus(); 
    document.form1.la18_i_sinonima.value = ''; 
  }
}
function js_mostralab_sinonima1(chave1,chave2){
  document.form1.la18_i_sinonima.value = chave1;
  document.form1.la10_i_codigo.value = chave2;
  db_iframe_lab_sinonima.hide();
}
function js_pesquisala18_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_exame','func_lab_exame.php?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la18_i_exame.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_exame','func_lab_exame.php?pesquisa_chave='+document.form1.la18_i_exame.value+'&funcao_js=parent.js_mostralab_exame','Pesquisa',false);
     }else{
       document.form1.la08_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_exame(chave,erro){
  document.form1.la08_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la18_i_exame.focus(); 
    document.form1.la18_i_exame.value = ''; 
  }
}
function js_mostralab_exame1(chave1,chave2){
  document.form1.la18_i_exame.value = chave1;
  document.form1.la08_i_codigo.value = chave2;
  db_iframe_lab_exame.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_exasinonima','func_lab_exasinonima.php?funcao_js=parent.js_preenchepesquisa|la18_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_exasinonima.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>