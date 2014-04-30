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
$clrhlotavincativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh25_codigo");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh39_codlotavinc?>">
       <?
       db_ancora(@$Lrh39_codlotavinc,"js_pesquisarh39_codlotavinc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh39_codlotavinc',6,$Irh39_codlotavinc,true,'text',$db_opcao," onchange='js_pesquisarh39_codlotavinc(false);'")
?>
       <?
db_input('rh25_codigo',4,$Irh25_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh39_codelenov?>">
       <?=@$Lrh39_codelenov?>
    </td>
    <td> 
<?
db_input('rh39_codelenov',6,$Irh39_codelenov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh39_anousu?>">
       <?
       db_ancora(@$Lrh39_anousu,"js_pesquisarh39_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$rh39_anousu = db_getsession('DB_anousu');
db_input('rh39_anousu',4,$Irh39_anousu,true,'text',3," onchange='js_pesquisarh39_anousu(false);'")
?>
       <?
db_input('o55_descr',40,$Io55_descr,true,'text',3,'');
db_input('o55_descr',40,$Io55_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh39_projativ?>">
       <?
       db_ancora(@$Lrh39_projativ,"js_pesquisarh39_projativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh39_projativ',4,$Irh39_projativ,true,'text',$db_opcao," onchange='js_pesquisarh39_projativ(false);'")
?>
       <?
db_input('o55_descr',40,$Io55_descr,true,'text',3,'');
db_input('o55_descr',40,$Io55_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh39_codlotavinc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlotavinc','func_rhlotavinc.php?funcao_js=parent.js_mostrarhlotavinc1|rh25_codlotavinc|rh25_codigo','Pesquisa',true);
  }else{
     if(document.form1.rh39_codlotavinc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlotavinc','func_rhlotavinc.php?pesquisa_chave='+document.form1.rh39_codlotavinc.value+'&funcao_js=parent.js_mostrarhlotavinc','Pesquisa',false);
     }else{
       document.form1.rh25_codigo.value = ''; 
     }
  }
}
function js_mostrarhlotavinc(chave,erro){
  document.form1.rh25_codigo.value = chave; 
  if(erro==true){ 
    document.form1.rh39_codlotavinc.focus(); 
    document.form1.rh39_codlotavinc.value = ''; 
  }
}
function js_mostrarhlotavinc1(chave1,chave2){
  document.form1.rh39_codlotavinc.value = chave1;
  document.form1.rh25_codigo.value = chave2;
  db_iframe_rhlotavinc.hide();
}
function js_pesquisarh39_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh39_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.rh39_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh39_projativ.focus(); 
    document.form1.rh39_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.rh39_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisarh39_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh39_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.rh39_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh39_projativ.focus(); 
    document.form1.rh39_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.rh39_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisarh39_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh39_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.rh39_anousu.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh39_anousu.focus(); 
    document.form1.rh39_anousu.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.rh39_anousu.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisarh39_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.rh39_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.rh39_anousu.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh39_anousu.focus(); 
    document.form1.rh39_anousu.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.rh39_anousu.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhlotavincativ','func_rhlotavincativ.php?funcao_js=parent.js_preenchepesquisa|rh39_codlotavinc|rh39_codelenov','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_rhlotavincativ.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>