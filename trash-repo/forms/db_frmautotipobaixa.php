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

//MODULO: fiscal
$clautotipobaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y59_codigo");
$clrotulo->label("y87_baixaproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty86_codautotipo?>">
       <?
       db_ancora(@$Ly86_codautotipo,"js_pesquisay86_codautotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y86_codautotipo',8,$Iy86_codautotipo,true,'text',$db_opcao," onchange='js_pesquisay86_codautotipo(false);'")
?>
       <?
db_input('y59_codigo',8,$Iy59_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty86_codbaixaproc?>">
       <?
       db_ancora(@$Ly86_codbaixaproc,"js_pesquisay86_codbaixaproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y86_codbaixaproc',8,$Iy86_codbaixaproc,true,'text',$db_opcao," onchange='js_pesquisay86_codbaixaproc(false);'")
?>
       <?
db_input('y87_baixaproc',8,$Iy87_baixaproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay86_codautotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_autotipo','func_autotipo.php?funcao_js=parent.js_mostraautotipo1|y59_codigo|y59_codigo','Pesquisa',true);
  }else{
     if(document.form1.y86_codautotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_autotipo','func_autotipo.php?pesquisa_chave='+document.form1.y86_codautotipo.value+'&funcao_js=parent.js_mostraautotipo','Pesquisa',false);
     }else{
       document.form1.y59_codigo.value = ''; 
     }
  }
}
function js_mostraautotipo(chave,erro){
  document.form1.y59_codigo.value = chave; 
  if(erro==true){ 
    document.form1.y86_codautotipo.focus(); 
    document.form1.y86_codautotipo.value = ''; 
  }
}
function js_mostraautotipo1(chave1,chave2){
  document.form1.y86_codautotipo.value = chave1;
  document.form1.y59_codigo.value = chave2;
  db_iframe_autotipo.hide();
}
function js_pesquisay86_codbaixaproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_autotipobaixaproc','func_autotipobaixaproc.php?funcao_js=parent.js_mostraautotipobaixaproc1|y87_baixaproc|y87_baixaproc','Pesquisa',true);
  }else{
     if(document.form1.y86_codbaixaproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_autotipobaixaproc','func_autotipobaixaproc.php?pesquisa_chave='+document.form1.y86_codbaixaproc.value+'&funcao_js=parent.js_mostraautotipobaixaproc','Pesquisa',false);
     }else{
       document.form1.y87_baixaproc.value = ''; 
     }
  }
}
function js_mostraautotipobaixaproc(chave,erro){
  document.form1.y87_baixaproc.value = chave; 
  if(erro==true){ 
    document.form1.y86_codbaixaproc.focus(); 
    document.form1.y86_codbaixaproc.value = ''; 
  }
}
function js_mostraautotipobaixaproc1(chave1,chave2){
  document.form1.y86_codbaixaproc.value = chave1;
  document.form1.y87_baixaproc.value = chave2;
  db_iframe_autotipobaixaproc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_autotipobaixa','func_autotipobaixa.php?funcao_js=parent.js_preenchepesquisa|y86_codautotipo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_autotipobaixa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>