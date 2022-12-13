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

//MODULO: compras
$clsolicitemunid->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m61_descr");
$clrotulo->label("pc11_numero");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc17_unid?>">
       <?
       db_ancora(@$Lpc17_unid,"js_pesquisapc17_unid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc17_unid',10,$Ipc17_unid,true,'text',$db_opcao," onchange='js_pesquisapc17_unid(false);'")
?>
       <?
db_input('m61_descr',40,$Im61_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc17_quant?>">
       <?=@$Lpc17_quant?>
    </td>
    <td> 
<?
db_input('pc17_quant',15,$Ipc17_quant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc17_codigo?>">
       <?
       db_ancora(@$Lpc17_codigo,"js_pesquisapc17_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc17_codigo',10,$Ipc17_codigo,true,'text',$db_opcao," onchange='js_pesquisapc17_codigo(false);'")
?>
       <?
db_input('pc11_numero',10,$Ipc11_numero,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisapc17_unid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr','Pesquisa',true);
  }else{
     if(document.form1.pc17_unid.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?pesquisa_chave='+document.form1.pc17_unid.value+'&funcao_js=parent.js_mostramatunid','Pesquisa',false);
     }else{
       document.form1.m61_descr.value = ''; 
     }
  }
}
function js_mostramatunid(chave,erro){
  document.form1.m61_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc17_unid.focus(); 
    document.form1.pc17_unid.value = ''; 
  }
}
function js_mostramatunid1(chave1,chave2){
  document.form1.pc17_unid.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
}
function js_pesquisapc17_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicitem','func_solicitem.php?funcao_js=parent.js_mostrasolicitem1|pc11_codigo|pc11_numero','Pesquisa',true);
  }else{
     if(document.form1.pc17_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_solicitem','func_solicitem.php?pesquisa_chave='+document.form1.pc17_codigo.value+'&funcao_js=parent.js_mostrasolicitem','Pesquisa',false);
     }else{
       document.form1.pc11_numero.value = ''; 
     }
  }
}
function js_mostrasolicitem(chave,erro){
  document.form1.pc11_numero.value = chave; 
  if(erro==true){ 
    document.form1.pc17_codigo.focus(); 
    document.form1.pc17_codigo.value = ''; 
  }
}
function js_mostrasolicitem1(chave1,chave2){
  document.form1.pc17_codigo.value = chave1;
  document.form1.pc11_numero.value = chave2;
  db_iframe_solicitem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_solicitemunid','func_solicitemunid.php?funcao_js=parent.js_preenchepesquisa|pc17_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_solicitemunid.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>