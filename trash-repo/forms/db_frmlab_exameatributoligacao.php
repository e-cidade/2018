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
$cllab_exameatributoligacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la25_i_codigo");
$clrotulo->label("la25_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla26_i_codigo?>">
       <?=@$Lla26_i_codigo?>
    </td>
    <td> 
<?
db_input('la26_i_codigo',10,$Ila26_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla26_i_exameatributofilho?>">
       <?
       db_ancora(@$Lla26_i_exameatributofilho,"js_pesquisala26_i_exameatributofilho(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la26_i_exameatributofilho',10,$Ila26_i_exameatributofilho,true,'text',$db_opcao," onchange='js_pesquisala26_i_exameatributofilho(false);'")
?>
       <?
db_input('la25_i_codigo',10,$Ila25_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla26_i_exameatributopai?>">
       <?
       db_ancora(@$Lla26_i_exameatributopai,"js_pesquisala26_i_exameatributopai(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la26_i_exameatributopai',10,$Ila26_i_exameatributopai,true,'text',$db_opcao," onchange='js_pesquisala26_i_exameatributopai(false);'")
?>
       <?
db_input('la25_i_codigo',10,$Ila25_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisala26_i_exameatributofilho(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_atributo','func_lab_atributo.php?funcao_js=parent.js_mostralab_atributo1|la25_i_codigo|la25_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la26_i_exameatributofilho.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_atributo','func_lab_atributo.php?pesquisa_chave='+document.form1.la26_i_exameatributofilho.value+'&funcao_js=parent.js_mostralab_atributo','Pesquisa',false);
     }else{
       document.form1.la25_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_atributo(chave,erro){
  document.form1.la25_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la26_i_exameatributofilho.focus(); 
    document.form1.la26_i_exameatributofilho.value = ''; 
  }
}
function js_mostralab_atributo1(chave1,chave2){
  document.form1.la26_i_exameatributofilho.value = chave1;
  document.form1.la25_i_codigo.value = chave2;
  db_iframe_lab_atributo.hide();
}
function js_pesquisala26_i_exameatributopai(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_atributo','func_lab_atributo.php?funcao_js=parent.js_mostralab_atributo1|la25_i_codigo|la25_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la26_i_exameatributopai.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_atributo','func_lab_atributo.php?pesquisa_chave='+document.form1.la26_i_exameatributopai.value+'&funcao_js=parent.js_mostralab_atributo','Pesquisa',false);
     }else{
       document.form1.la25_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_atributo(chave,erro){
  document.form1.la25_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.la26_i_exameatributopai.focus(); 
    document.form1.la26_i_exameatributopai.value = ''; 
  }
}
function js_mostralab_atributo1(chave1,chave2){
  document.form1.la26_i_exameatributopai.value = chave1;
  document.form1.la25_i_codigo.value = chave2;
  db_iframe_lab_atributo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_exameatributoligacao','func_lab_exameatributoligacao.php?funcao_js=parent.js_preenchepesquisa|la26_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_exameatributoligacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>