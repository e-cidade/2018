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

//MODULO: dividaativa
$cltermoanuproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v09_sequencial");
$clrotulo->label("p58_codproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv22_sequencial?>">
       <?=@$Lv22_sequencial?>
    </td>
    <td> 
<?
db_input('v22_sequencial',10,$Iv22_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv22_termoanu?>">
       <?
       db_ancora(@$Lv22_termoanu,"js_pesquisav22_termoanu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v22_termoanu',10,$Iv22_termoanu,true,'text',$db_opcao," onchange='js_pesquisav22_termoanu(false);'")
?>
       <?
db_input('v09_sequencial',10,$Iv09_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv22_processo?>">
       <?
       db_ancora(@$Lv22_processo,"js_pesquisav22_processo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v22_processo',10,$Iv22_processo,true,'text',$db_opcao," onchange='js_pesquisav22_processo(false);'")
?>
       <?
db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav22_termoanu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_termoanu','func_termoanu.php?funcao_js=parent.js_mostratermoanu1|v09_sequencial|v09_sequencial','Pesquisa',true);
  }else{
     if(document.form1.v22_termoanu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_termoanu','func_termoanu.php?pesquisa_chave='+document.form1.v22_termoanu.value+'&funcao_js=parent.js_mostratermoanu','Pesquisa',false);
     }else{
       document.form1.v09_sequencial.value = ''; 
     }
  }
}
function js_mostratermoanu(chave,erro){
  document.form1.v09_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.v22_termoanu.focus(); 
    document.form1.v22_termoanu.value = ''; 
  }
}
function js_mostratermoanu1(chave1,chave2){
  document.form1.v22_termoanu.value = chave1;
  document.form1.v09_sequencial.value = chave2;
  db_iframe_termoanu.hide();
}
function js_pesquisav22_processo(mostra){
  if(mostra==true){
  	
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.v22_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.v22_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.v22_processo.focus(); 
    document.form1.v22_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.v22_processo.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_termoanuproc','func_termoanuproc.php?funcao_js=parent.js_preenchepesquisa|v22_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_termoanuproc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>