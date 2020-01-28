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
$clrechumanorelacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed22_i_codigo");
$clrotulo->label("ed23_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted03_i_codigo?>">
       <?=@$Led03_i_codigo?>
    </td>
    <td> 
<?
db_input('ed03_i_codigo',10,$Ied03_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted03_i_rechumanoativ?>">
       <?
       db_ancora(@$Led03_i_rechumanoativ,"js_pesquisaed03_i_rechumanoativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed03_i_rechumanoativ',10,$Ied03_i_rechumanoativ,true,'text',$db_opcao," onchange='js_pesquisaed03_i_rechumanoativ(false);'")
?>
       <?
db_input('ed22_i_codigo',10,$Ied22_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted03_i_relacaotrabalho?>">
       <?
       db_ancora(@$Led03_i_relacaotrabalho,"js_pesquisaed03_i_relacaotrabalho(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed03_i_relacaotrabalho',10,$Ied03_i_relacaotrabalho,true,'text',$db_opcao," onchange='js_pesquisaed03_i_relacaotrabalho(false);'")
?>
       <?
db_input('ed23_i_codigo',10,$Ied23_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed03_i_rechumanoativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rechumanoativ','func_rechumanoativ.php?funcao_js=parent.js_mostrarechumanoativ1|ed22_i_codigo|ed22_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed03_i_rechumanoativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rechumanoativ','func_rechumanoativ.php?pesquisa_chave='+document.form1.ed03_i_rechumanoativ.value+'&funcao_js=parent.js_mostrarechumanoativ','Pesquisa',false);
     }else{
       document.form1.ed22_i_codigo.value = ''; 
     }
  }
}
function js_mostrarechumanoativ(chave,erro){
  document.form1.ed22_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed03_i_rechumanoativ.focus(); 
    document.form1.ed03_i_rechumanoativ.value = ''; 
  }
}
function js_mostrarechumanoativ1(chave1,chave2){
  document.form1.ed03_i_rechumanoativ.value = chave1;
  document.form1.ed22_i_codigo.value = chave2;
  db_iframe_rechumanoativ.hide();
}
function js_pesquisaed03_i_relacaotrabalho(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_relacaotrabalho','func_relacaotrabalho.php?funcao_js=parent.js_mostrarelacaotrabalho1|ed23_i_codigo|ed23_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed03_i_relacaotrabalho.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_relacaotrabalho','func_relacaotrabalho.php?pesquisa_chave='+document.form1.ed03_i_relacaotrabalho.value+'&funcao_js=parent.js_mostrarelacaotrabalho','Pesquisa',false);
     }else{
       document.form1.ed23_i_codigo.value = ''; 
     }
  }
}
function js_mostrarelacaotrabalho(chave,erro){
  document.form1.ed23_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed03_i_relacaotrabalho.focus(); 
    document.form1.ed03_i_relacaotrabalho.value = ''; 
  }
}
function js_mostrarelacaotrabalho1(chave1,chave2){
  document.form1.ed03_i_relacaotrabalho.value = chave1;
  document.form1.ed23_i_codigo.value = chave2;
  db_iframe_relacaotrabalho.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rechumanorelacao','func_rechumanorelacao.php?funcao_js=parent.js_preenchepesquisa|ed03_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rechumanorelacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>