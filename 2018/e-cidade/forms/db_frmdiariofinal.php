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
$cldiariofinal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed95_i_codigo");
$clrotulo->label("ed43_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted74_i_codigo?>">
       <?=@$Led74_i_codigo?>
    </td>
    <td> 
<?
db_input('ed74_i_codigo',10,$Ied74_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_i_diario?>">
       <?
       db_ancora(@$Led74_i_diario,"js_pesquisaed74_i_diario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed74_i_diario',10,$Ied74_i_diario,true,'text',$db_opcao," onchange='js_pesquisaed74_i_diario(false);'")
?>
       <?
db_input('ed95_i_codigo',10,$Ied95_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_i_procresultado?>">
       <?
       db_ancora(@$Led74_i_procresultado,"js_pesquisaed74_i_procresultado(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed74_i_procresultado',10,$Ied74_i_procresultado,true,'text',$db_opcao," onchange='js_pesquisaed74_i_procresultado(false);'")
?>
       <?
db_input('ed43_i_codigo',10,$Ied43_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_c_valoraprov?>">
       <?=@$Led74_c_valoraprov?>
    </td>
    <td> 
<?
db_input('ed74_c_valoraprov',10,$Ied74_c_valoraprov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_c_resultadoaprov?>">
       <?=@$Led74_c_resultadoaprov?>
    </td>
    <td> 
<?
db_input('ed74_c_resultadoaprov',1,$Ied74_c_resultadoaprov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_i_percfreq?>">
       <?=@$Led74_i_percfreq?>
    </td>
    <td> 
<?
db_input('ed74_i_percfreq',10,$Ied74_i_percfreq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_c_resultadofreq?>">
       <?=@$Led74_c_resultadofreq?>
    </td>
    <td> 
<?
db_input('ed74_c_resultadofreq',1,$Ied74_c_resultadofreq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted74_c_resultadofinal?>">
       <?=@$Led74_c_resultadofinal?>
    </td>
    <td> 
<?
db_input('ed74_c_resultadofinal',1,$Ied74_c_resultadofinal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed74_i_diario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_diario','func_diario.php?funcao_js=parent.js_mostradiario1|ed95_i_codigo|ed95_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed74_i_diario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_diario','func_diario.php?pesquisa_chave='+document.form1.ed74_i_diario.value+'&funcao_js=parent.js_mostradiario','Pesquisa',false);
     }else{
       document.form1.ed95_i_codigo.value = ''; 
     }
  }
}
function js_mostradiario(chave,erro){
  document.form1.ed95_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed74_i_diario.focus(); 
    document.form1.ed74_i_diario.value = ''; 
  }
}
function js_mostradiario1(chave1,chave2){
  document.form1.ed74_i_diario.value = chave1;
  document.form1.ed95_i_codigo.value = chave2;
  db_iframe_diario.hide();
}
function js_pesquisaed74_i_procresultado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procresultado','func_procresultado.php?funcao_js=parent.js_mostraprocresultado1|ed43_i_codigo|ed43_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed74_i_procresultado.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procresultado','func_procresultado.php?pesquisa_chave='+document.form1.ed74_i_procresultado.value+'&funcao_js=parent.js_mostraprocresultado','Pesquisa',false);
     }else{
       document.form1.ed43_i_codigo.value = ''; 
     }
  }
}
function js_mostraprocresultado(chave,erro){
  document.form1.ed43_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed74_i_procresultado.focus(); 
    document.form1.ed74_i_procresultado.value = ''; 
  }
}
function js_mostraprocresultado1(chave1,chave2){
  document.form1.ed74_i_procresultado.value = chave1;
  document.form1.ed43_i_codigo.value = chave2;
  db_iframe_procresultado.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_diariofinal','func_diariofinal.php?funcao_js=parent.js_preenchepesquisa|ed74_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_diariofinal.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>