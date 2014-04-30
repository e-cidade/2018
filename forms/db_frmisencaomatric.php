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

//MODULO: tributario
$clisencaomatric->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v10_isencaotipo");
$clrotulo->label("j01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv15_sequencial?>">
       <?=@$Lv15_sequencial?>
    </td>
    <td> 
<?
db_input('v15_sequencial',10,$Iv15_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv15_isencao?>">
       <?
       db_ancora(@$Lv15_isencao,"js_pesquisav15_isencao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v15_isencao',10,$Iv15_isencao,true,'text',$db_opcao," onchange='js_pesquisav15_isencao(false);'")
?>
       <?
db_input('v10_isencaotipo',10,$Iv10_isencaotipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv15_matric?>">
       <?
       db_ancora(@$Lv15_matric,"js_pesquisav15_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v15_matric',10,$Iv15_matric,true,'text',$db_opcao," onchange='js_pesquisav15_matric(false);'")
?>
       <?
db_input('j01_numcgm',10,$Ij01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav15_isencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_isencao.php?funcao_js=parent.js_mostraisencao1|v10_sequencial|v10_isencaotipo','Pesquisa',true);
  }else{
     if(document.form1.v15_isencao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_isencao.php?pesquisa_chave='+document.form1.v15_isencao.value+'&funcao_js=parent.js_mostraisencao','Pesquisa',false);
     }else{
       document.form1.v10_isencaotipo.value = ''; 
     }
  }
}
function js_mostraisencao(chave,erro){
  document.form1.v10_isencaotipo.value = chave; 
  if(erro==true){ 
    document.form1.v15_isencao.focus(); 
    document.form1.v15_isencao.value = ''; 
  }
}
function js_mostraisencao1(chave1,chave2){
  document.form1.v15_isencao.value = chave1;
  document.form1.v10_isencaotipo.value = chave2;
  db_iframe_isencao.hide();
}
function js_pesquisav15_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.v15_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.v15_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.v15_matric.focus(); 
    document.form1.v15_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.v15_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_isencaomatric','func_isencaomatric.php?funcao_js=parent.js_preenchepesquisa|v15_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_isencaomatric.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>