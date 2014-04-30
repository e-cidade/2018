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

//MODULO: pessoal
$clrhlocaltrabcustoplano->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh55_descr");
$clrotulo->label("rh55_descr");
$clrotulo->label("cc08_instit");
$clrotulo->label("rh55_descr");
$clrotulo->label("rh55_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh86_sequencial?>">
       <?=@$Lrh86_sequencial?>
    </td>
    <td> 
<?
db_input('rh86_sequencial',10,$Irh86_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh86_criteriorateio?>">
       <?
       db_ancora(@$Lrh86_criteriorateio,"js_pesquisarh86_criteriorateio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh86_criteriorateio',10,$Irh86_criteriorateio,true,'text',$db_opcao," onchange='js_pesquisarh86_criteriorateio(false);'")
?>
       <?
db_input('cc08_instit',10,$Icc08_instit,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh86_rhlocaltrab?>">
       <?
       db_ancora(@$Lrh86_rhlocaltrab,"js_pesquisarh86_rhlocaltrab(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh86_rhlocaltrab',10,$Irh86_rhlocaltrab,true,'text',$db_opcao," onchange='js_pesquisarh86_rhlocaltrab(false);'")
?>
       <?
db_input('rh55_descr',40,$Irh55_descr,true,'text',3,'')
db_input('rh55_descr',40,$Irh55_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh86_instit?>">
       <?
       db_ancora(@$Lrh86_instit,"js_pesquisarh86_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh86_instit',10,$Irh86_instit,true,'text',$db_opcao," onchange='js_pesquisarh86_instit(false);'")
?>
       <?
db_input('rh55_descr',40,$Irh55_descr,true,'text',3,'')
db_input('rh55_descr',40,$Irh55_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh86_rhlocaltrab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?funcao_js=parent.js_mostrarhlocaltrab1|rh55_codigo|rh55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh86_rhlocaltrab.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?pesquisa_chave='+document.form1.rh86_rhlocaltrab.value+'&funcao_js=parent.js_mostrarhlocaltrab','Pesquisa',false);
     }else{
       document.form1.rh55_descr.value = ''; 
     }
  }
}
function js_mostrarhlocaltrab(chave,erro){
  document.form1.rh55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh86_rhlocaltrab.focus(); 
    document.form1.rh86_rhlocaltrab.value = ''; 
  }
}
function js_mostrarhlocaltrab1(chave1,chave2){
  document.form1.rh86_rhlocaltrab.value = chave1;
  document.form1.rh55_descr.value = chave2;
  db_iframe_rhlocaltrab.hide();
}
function js_pesquisarh86_rhlocaltrab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?funcao_js=parent.js_mostrarhlocaltrab1|rh55_instit|rh55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh86_rhlocaltrab.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?pesquisa_chave='+document.form1.rh86_rhlocaltrab.value+'&funcao_js=parent.js_mostrarhlocaltrab','Pesquisa',false);
     }else{
       document.form1.rh55_descr.value = ''; 
     }
  }
}
function js_mostrarhlocaltrab(chave,erro){
  document.form1.rh55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh86_rhlocaltrab.focus(); 
    document.form1.rh86_rhlocaltrab.value = ''; 
  }
}
function js_mostrarhlocaltrab1(chave1,chave2){
  document.form1.rh86_rhlocaltrab.value = chave1;
  document.form1.rh55_descr.value = chave2;
  db_iframe_rhlocaltrab.hide();
}
function js_pesquisarh86_criteriorateio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custocriteriorateio','func_custocriteriorateio.php?funcao_js=parent.js_mostracustocriteriorateio1|cc08_sequencial|cc08_instit','Pesquisa',true);
  }else{
     if(document.form1.rh86_criteriorateio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custocriteriorateio','func_custocriteriorateio.php?pesquisa_chave='+document.form1.rh86_criteriorateio.value+'&funcao_js=parent.js_mostracustocriteriorateio','Pesquisa',false);
     }else{
       document.form1.cc08_instit.value = ''; 
     }
  }
}
function js_mostracustocriteriorateio(chave,erro){
  document.form1.cc08_instit.value = chave; 
  if(erro==true){ 
    document.form1.rh86_criteriorateio.focus(); 
    document.form1.rh86_criteriorateio.value = ''; 
  }
}
function js_mostracustocriteriorateio1(chave1,chave2){
  document.form1.rh86_criteriorateio.value = chave1;
  document.form1.cc08_instit.value = chave2;
  db_iframe_custocriteriorateio.hide();
}
function js_pesquisarh86_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?funcao_js=parent.js_mostrarhlocaltrab1|rh55_codigo|rh55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh86_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?pesquisa_chave='+document.form1.rh86_instit.value+'&funcao_js=parent.js_mostrarhlocaltrab','Pesquisa',false);
     }else{
       document.form1.rh55_descr.value = ''; 
     }
  }
}
function js_mostrarhlocaltrab(chave,erro){
  document.form1.rh55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh86_instit.focus(); 
    document.form1.rh86_instit.value = ''; 
  }
}
function js_mostrarhlocaltrab1(chave1,chave2){
  document.form1.rh86_instit.value = chave1;
  document.form1.rh55_descr.value = chave2;
  db_iframe_rhlocaltrab.hide();
}
function js_pesquisarh86_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?funcao_js=parent.js_mostrarhlocaltrab1|rh55_instit|rh55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh86_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?pesquisa_chave='+document.form1.rh86_instit.value+'&funcao_js=parent.js_mostrarhlocaltrab','Pesquisa',false);
     }else{
       document.form1.rh55_descr.value = ''; 
     }
  }
}
function js_mostrarhlocaltrab(chave,erro){
  document.form1.rh55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh86_instit.focus(); 
    document.form1.rh86_instit.value = ''; 
  }
}
function js_mostrarhlocaltrab1(chave1,chave2){
  document.form1.rh86_instit.value = chave1;
  document.form1.rh55_descr.value = chave2;
  db_iframe_rhlocaltrab.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrabcustoplano','func_rhlocaltrabcustoplano.php?funcao_js=parent.js_preenchepesquisa|rh86_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhlocaltrabcustoplano.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>