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
$cldiarioresultado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed95_i_codigo");
$clrotulo->label("ed43_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted73_i_codigo?>">
       <?=@$Led73_i_codigo?>
    </td>
    <td> 
<?
db_input('ed73_i_codigo',10,$Ied73_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted73_i_diario?>">
       <?
       db_ancora(@$Led73_i_diario,"js_pesquisaed73_i_diario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed73_i_diario',10,$Ied73_i_diario,true,'text',$db_opcao," onchange='js_pesquisaed73_i_diario(false);'")
?>
       <?
db_input('ed95_i_codigo',10,$Ied95_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted73_i_procresultado?>">
       <?
       db_ancora(@$Led73_i_procresultado,"js_pesquisaed73_i_procresultado(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed73_i_procresultado',10,$Ied73_i_procresultado,true,'text',$db_opcao," onchange='js_pesquisaed73_i_procresultado(false);'")
?>
       <?
db_input('ed43_i_codigo',10,$Ied43_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted73_i_valornota?>">
       <?=@$Led73_i_valornota?>
    </td>
    <td> 
<?
db_input('ed73_i_valornota',10,$Ied73_i_valornota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted73_c_valorconceito?>">
       <?=@$Led73_c_valorconceito?>
    </td>
    <td> 
<?
db_input('ed73_c_valorconceito',2,$Ied73_c_valorconceito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted73_t_parecer?>">
       <?=@$Led73_t_parecer?>
    </td>
    <td> 
<?
db_textarea('ed73_t_parecer',0,0,$Ied73_t_parecer,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted73_c_aprovmin?>">
       <?=@$Led73_c_aprovmin?>
    </td>
    <td> 
<?
db_input('ed73_c_aprovmin',1,$Ied73_c_aprovmin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed73_i_diario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_diario','func_diario.php?funcao_js=parent.js_mostradiario1|ed95_i_codigo|ed95_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed73_i_diario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_diario','func_diario.php?pesquisa_chave='+document.form1.ed73_i_diario.value+'&funcao_js=parent.js_mostradiario','Pesquisa',false);
     }else{
       document.form1.ed95_i_codigo.value = ''; 
     }
  }
}
function js_mostradiario(chave,erro){
  document.form1.ed95_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed73_i_diario.focus(); 
    document.form1.ed73_i_diario.value = ''; 
  }
}
function js_mostradiario1(chave1,chave2){
  document.form1.ed73_i_diario.value = chave1;
  document.form1.ed95_i_codigo.value = chave2;
  db_iframe_diario.hide();
}
function js_pesquisaed73_i_procresultado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procresultado','func_procresultado.php?funcao_js=parent.js_mostraprocresultado1|ed43_i_codigo|ed43_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed73_i_procresultado.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procresultado','func_procresultado.php?pesquisa_chave='+document.form1.ed73_i_procresultado.value+'&funcao_js=parent.js_mostraprocresultado','Pesquisa',false);
     }else{
       document.form1.ed43_i_codigo.value = ''; 
     }
  }
}
function js_mostraprocresultado(chave,erro){
  document.form1.ed43_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed73_i_procresultado.focus(); 
    document.form1.ed73_i_procresultado.value = ''; 
  }
}
function js_mostraprocresultado1(chave1,chave2){
  document.form1.ed73_i_procresultado.value = chave1;
  document.form1.ed43_i_codigo.value = chave2;
  db_iframe_procresultado.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_diarioresultado','func_diarioresultado.php?funcao_js=parent.js_preenchepesquisa|ed73_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_diarioresultado.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>