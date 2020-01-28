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
$clrhpeslota->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r70_estrut");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh10_anousu?>">
       <?
       db_ancora(@$Lrh10_anousu,"js_pesquisarh10_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$rh10_anousu = db_getsession('DB_anousu');
db_input('rh10_anousu',4,$Irh10_anousu,true,'text',$db_opcao," onchange='js_pesquisarh10_anousu(false);'")
?>
       <?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh10_mesusu?>">
       <?
       db_ancora(@$Lrh10_mesusu,"js_pesquisarh10_mesusu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh10_mesusu',2,$Irh10_mesusu,true,'text',$db_opcao," onchange='js_pesquisarh10_mesusu(false);'")
?>
       <?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh10_regist?>">
       <?
       db_ancora(@$Lrh10_regist,"js_pesquisarh10_regist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh10_regist',6,$Irh10_regist,true,'text',$db_opcao," onchange='js_pesquisarh10_regist(false);'");
?>
       <?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh10_lotac?>">
       <?
       db_ancora(@$Lrh10_lotac,"js_pesquisarh10_lotac(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh10_lotac',6,$Irh10_lotac,true,'text',$db_opcao," onchange='js_pesquisarh10_lotac(false);'");
?>
       <?
db_input('r70_estrut',20,$Ir70_estrut,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh10_percrateio?>">
       <?=@$Lrh10_percrateio?>
    </td>
    <td> 
<?
db_input('rh10_percrateio',3,$Irh10_percrateio,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh10_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_anousu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_regist.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_regist.focus(); 
    document.form1.rh10_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_regist.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_mesusu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_regist.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_regist.focus(); 
    document.form1.rh10_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_regist.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_regist|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_regist.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_regist.focus(); 
    document.form1.rh10_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_regist.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_lotac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlota','func_rhlota.php?funcao_js=parent.js_mostrarhlota1|r70_codigo|r70_estrut','Pesquisa',true);
  }else{
     if(document.form1.rh10_lotac.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlota','func_rhlota.php?pesquisa_chave='+document.form1.rh10_lotac.value+'&funcao_js=parent.js_mostrarhlota','Pesquisa',false);
     }else{
       document.form1.r70_estrut.value = ''; 
     }
  }
}
function js_mostrarhlota(chave,erro){
  document.form1.r70_estrut.value = chave; 
  if(erro==true){ 
    document.form1.rh10_lotac.focus(); 
    document.form1.rh10_lotac.value = ''; 
  }
}
function js_mostrarhlota1(chave1,chave2){
  document.form1.rh10_lotac.value = chave1;
  document.form1.r70_estrut.value = chave2;
  db_iframe_rhlota.hide();
}
function js_pesquisarh10_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_anousu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_anousu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_anousu.focus(); 
    document.form1.rh10_anousu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_anousu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_mesusu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_anousu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_anousu.focus(); 
    document.form1.rh10_anousu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_anousu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_regist|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_anousu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_anousu.focus(); 
    document.form1.rh10_anousu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_anousu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_mesusu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_anousu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_mesusu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_mesusu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_mesusu.focus(); 
    document.form1.rh10_mesusu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_mesusu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_mesusu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_mesusu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_mesusu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_mesusu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_mesusu.focus(); 
    document.form1.rh10_mesusu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_mesusu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh10_mesusu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_regist|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh10_mesusu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh10_mesusu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh10_mesusu.focus(); 
    document.form1.rh10_mesusu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh10_mesusu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpeslota','func_rhpeslota.php?funcao_js=parent.js_preenchepesquisa|rh10_regist|rh10_lotac|rh10_anousu|rh10_mesusu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3){
  db_iframe_rhpeslota.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3";
  }
  ?>
}
</script>