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
$clrhpeslotaprinc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r70_estrut");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh11_anousu?>">
       <?
       db_ancora(@$Lrh11_anousu,"js_pesquisarh11_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$rh11_anousu = db_getsession('DB_anousu');
db_input('rh11_anousu',4,$Irh11_anousu,true,'text',$db_opcao," onchange='js_pesquisarh11_anousu(false);'");
?>
       <?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh11_mesusu?>">
       <?
       db_ancora(@$Lrh11_mesusu,"js_pesquisarh11_mesusu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh11_mesusu',2,$Irh11_mesusu,true,'text',$db_opcao," onchange='js_pesquisarh11_mesusu(false);'");
?>
       <?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh11_regist?>">
       <?
       db_ancora(@$Lrh11_regist,"js_pesquisarh11_regist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh11_regist',6,$Irh11_regist,true,'text',$db_opcao," onchange='js_pesquisarh11_regist(false);'");
?>
       <?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh11_lotac?>">
       <?
       db_ancora(@$Lrh11_lotac,"js_pesquisarh11_lotac(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh11_lotac',6,$Irh11_lotac,true,'text',$db_opcao," onchange='js_pesquisarh11_lotac(false);'");
?>
       <?
db_input('r70_estrut',20,$Ir70_estrut,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh11_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_anousu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_anousu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_anousu.focus(); 
    document.form1.rh11_anousu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_anousu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_mesusu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_anousu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_anousu.focus(); 
    document.form1.rh11_anousu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_anousu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_regist|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_anousu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_anousu.focus(); 
    document.form1.rh11_anousu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_anousu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_mesusu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_anousu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_mesusu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_mesusu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_mesusu.focus(); 
    document.form1.rh11_mesusu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_mesusu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_mesusu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_mesusu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_mesusu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_mesusu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_mesusu.focus(); 
    document.form1.rh11_mesusu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_mesusu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_mesusu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_regist|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_mesusu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_mesusu.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_mesusu.focus(); 
    document.form1.rh11_mesusu.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_mesusu.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_anousu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_regist.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_regist.focus(); 
    document.form1.rh11_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_regist.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_mesusu|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_regist.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_regist.focus(); 
    document.form1.rh11_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_regist.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?funcao_js=parent.js_mostrapessoal1|r01_regist|r01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.rh11_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pessoal','func_pessoal.php?pesquisa_chave='+document.form1.rh11_regist.value+'&funcao_js=parent.js_mostrapessoal','Pesquisa',false);
     }else{
       document.form1.r01_numcgm.value = ''; 
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.r01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.rh11_regist.focus(); 
    document.form1.rh11_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh11_regist.value = chave1;
  document.form1.r01_numcgm.value = chave2;
  db_iframe_pessoal.hide();
}
function js_pesquisarh11_lotac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlota','func_rhlota.php?funcao_js=parent.js_mostrarhlota1|r70_codigo|r70_estrut','Pesquisa',true);
  }else{
     if(document.form1.rh11_lotac.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlota','func_rhlota.php?pesquisa_chave='+document.form1.rh11_lotac.value+'&funcao_js=parent.js_mostrarhlota','Pesquisa',false);
     }else{
       document.form1.r70_estrut.value = ''; 
     }
  }
}
function js_mostrarhlota(chave,erro){
  document.form1.r70_estrut.value = chave; 
  if(erro==true){ 
    document.form1.rh11_lotac.focus(); 
    document.form1.rh11_lotac.value = ''; 
  }
}
function js_mostrarhlota1(chave1,chave2){
  document.form1.rh11_lotac.value = chave1;
  document.form1.r70_estrut.value = chave2;
  db_iframe_rhlota.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpeslotaprinc','func_rhpeslotaprinc.php?funcao_js=parent.js_preenchepesquisa|rh11_anousu|rh11_mesusu|rh11_regist|rh11_lotac','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3){
  db_iframe_rhpeslotaprinc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3";
  }
  ?>
}
</script>