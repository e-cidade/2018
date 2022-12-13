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
$clrhlotaexe->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh26_anousu?>">
       <?
       db_ancora(@$Lrh26_anousu,"js_pesquisarh26_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$rh26_anousu = db_getsession('DB_anousu');
db_input('rh26_anousu',4,$Irh26_anousu,true,'text',$db_opcao," onchange='js_pesquisarh26_anousu(false);'")
?>
       <?
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh26_codigo?>">
       <?=@$Lrh26_codigo?>
    </td>
    <td> 
<?
db_input('rh26_codigo',4,$Irh26_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh26_orgao?>">
       <?
       db_ancora(@$Lrh26_orgao,"js_pesquisarh26_orgao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh26_orgao',2,$Irh26_orgao,true,'text',$db_opcao," onchange='js_pesquisarh26_orgao(false);'")
?>
       <?
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh26_unidade?>">
       <?
       db_ancora(@$Lrh26_unidade,"js_pesquisarh26_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh26_unidade',2,$Irh26_unidade,true,'text',$db_opcao," onchange='js_pesquisarh26_unidade(false);'")
?>
       <?
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
db_input('o41_descr',50,$Io41_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh26_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_anousu.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_anousu.focus(); 
    document.form1.rh26_anousu.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_anousu.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_anousu.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_anousu.focus(); 
    document.form1.rh26_anousu.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_anousu.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_anousu.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_anousu.focus(); 
    document.form1.rh26_anousu.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_anousu.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_orgao.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_orgao.focus(); 
    document.form1.rh26_orgao.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_orgao.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_orgao.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_orgao.focus(); 
    document.form1.rh26_orgao.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_orgao.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_orgao.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_orgao.focus(); 
    document.form1.rh26_orgao.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_orgao.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_unidade.focus(); 
    document.form1.rh26_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_unidade.focus(); 
    document.form1.rh26_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisarh26_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.rh26_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.rh26_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh26_unidade.focus(); 
    document.form1.rh26_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.rh26_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhlotaexe','func_rhlotaexe.php?funcao_js=parent.js_preenchepesquisa|rh26_anousu|rh26_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_rhlotaexe.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>