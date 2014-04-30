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

//MODULO: educação
$clefetividade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("ed98_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted97_i_codigo?>">
       <?=@$Led97_i_codigo?>
    </td>
    <td> 
<?
db_input('ed97_i_codigo',10,$Ied97_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_efetividaderh?>">
       <?
       db_ancora(@$Led97_i_efetividaderh,"js_pesquisaed97_i_efetividaderh(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed97_i_efetividaderh',10,$Ied97_i_efetividaderh,true,'text',$db_opcao," onchange='js_pesquisaed97_i_efetividaderh(false);'")
?>
       <?
db_input('ed98_i_codigo',10,$Ied98_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_rechumano?>">
       <?
       db_ancora(@$Led97_i_rechumano,"js_pesquisaed97_i_rechumano(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed97_i_rechumano',10,$Ied97_i_rechumano,true,'text',$db_opcao," onchange='js_pesquisaed97_i_rechumano(false);'")
?>
       <?
db_input('ed20_i_codigo',10,$Ied20_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_diasletivos?>">
       <?=@$Led97_i_diasletivos?>
    </td>
    <td> 
<?
db_input('ed97_i_diasletivos',10,$Ied97_i_diasletivos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_faltaabon?>">
       <?=@$Led97_i_faltaabon?>
    </td>
    <td> 
<?
db_input('ed97_i_faltaabon',10,$Ied97_i_faltaabon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_faltanjust?>">
       <?=@$Led97_i_faltanjust?>
    </td>
    <td> 
<?
db_input('ed97_i_faltanjust',10,$Ied97_i_faltanjust,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_t_licenca?>">
       <?=@$Led97_t_licenca?>
    </td>
    <td> 
<?
db_textarea('ed97_t_licenca',0,0,$Ied97_t_licenca,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_t_horario?>">
       <?=@$Led97_t_horario?>
    </td>
    <td> 
<?
db_textarea('ed97_t_horario',0,0,$Ied97_t_horario,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_horacinq?>">
       <?=@$Led97_i_horacinq?>
    </td>
    <td> 
<?
db_input('ed97_i_horacinq',15,$Ied97_i_horacinq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_i_horacem?>">
       <?=@$Led97_i_horacem?>
    </td>
    <td> 
<?
db_input('ed97_i_horacem',15,$Ied97_i_horacem,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted97_t_obs?>">
       <?=@$Led97_t_obs?>
    </td>
    <td> 
<?
db_textarea('ed97_t_obs',0,0,$Ied97_t_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed97_i_rechumano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rechumano','func_rechumano.php?funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|ed20_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed97_i_rechumano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rechumano','func_rechumano.php?pesquisa_chave='+document.form1.ed97_i_rechumano.value+'&funcao_js=parent.js_mostrarechumano','Pesquisa',false);
     }else{
       document.form1.ed20_i_codigo.value = ''; 
     }
  }
}
function js_mostrarechumano(chave,erro){
  document.form1.ed20_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed97_i_rechumano.focus(); 
    document.form1.ed97_i_rechumano.value = ''; 
  }
}
function js_mostrarechumano1(chave1,chave2){
  document.form1.ed97_i_rechumano.value = chave1;
  document.form1.ed20_i_codigo.value = chave2;
  db_iframe_rechumano.hide();
}
function js_pesquisaed97_i_efetividaderh(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_efetividaderh','func_efetividaderh.php?funcao_js=parent.js_mostraefetividaderh1|ed98_i_codigo|ed98_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed97_i_efetividaderh.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_efetividaderh','func_efetividaderh.php?pesquisa_chave='+document.form1.ed97_i_efetividaderh.value+'&funcao_js=parent.js_mostraefetividaderh','Pesquisa',false);
     }else{
       document.form1.ed98_i_codigo.value = ''; 
     }
  }
}
function js_mostraefetividaderh(chave,erro){
  document.form1.ed98_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed97_i_efetividaderh.focus(); 
    document.form1.ed97_i_efetividaderh.value = ''; 
  }
}
function js_mostraefetividaderh1(chave1,chave2){
  document.form1.ed97_i_efetividaderh.value = chave1;
  document.form1.ed98_i_codigo.value = chave2;
  db_iframe_efetividaderh.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_efetividade','func_efetividade.php?funcao_js=parent.js_preenchepesquisa|ed97_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_efetividade.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>