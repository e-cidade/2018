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
$cltiafdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y90_codtiaf");
$clrotulo->label("y99_coddoc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty99_coddoc?>">
       <?=@$Ly99_coddoc?>
    </td>
    <td> 
<?
db_input('y99_coddoc',10,$Iy99_coddoc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty99_codtiaf?>">
       <?
       db_ancora(@$Ly99_codtiaf,"js_pesquisay99_codtiaf(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y99_codtiaf',10,$Iy99_codtiaf,true,'text',$db_opcao," onchange='js_pesquisay99_codtiaf(false);'")
?>
       <?
db_input('y90_codtiaf',10,$Iy90_codtiaf,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty99_tiafdoc?>">
       <?
       db_ancora(@$Ly99_tiafdoc,"js_pesquisay99_tiafdoc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y99_tiafdoc',10,$Iy99_tiafdoc,true,'text',$db_opcao," onchange='js_pesquisay99_tiafdoc(false);'")
?>
       <?
db_input('y99_coddoc',10,$Iy99_coddoc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty99_dtini?>">
       <?=@$Ly99_dtini?>
    </td>
    <td> 
<?
db_inputdata('y99_dtini',@$y99_dtini_dia,@$y99_dtini_mes,@$y99_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty99_dtfim?>">
       <?=@$Ly99_dtfim?>
    </td>
    <td> 
<?
db_inputdata('y99_dtfim',@$y99_dtfim_dia,@$y99_dtfim_mes,@$y99_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty99_obs?>">
       <?=@$Ly99_obs?>
    </td>
    <td> 
<?
db_textarea('y99_obs',0,0,$Iy99_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay99_codtiaf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_mostratiaf1|y90_codtiaf|y90_codtiaf','Pesquisa',true);
  }else{
     if(document.form1.y99_codtiaf.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?pesquisa_chave='+document.form1.y99_codtiaf.value+'&funcao_js=parent.js_mostratiaf','Pesquisa',false);
     }else{
       document.form1.y90_codtiaf.value = ''; 
     }
  }
}
function js_mostratiaf(chave,erro){
  document.form1.y90_codtiaf.value = chave; 
  if(erro==true){ 
    document.form1.y99_codtiaf.focus(); 
    document.form1.y99_codtiaf.value = ''; 
  }
}
function js_mostratiaf1(chave1,chave2){
  document.form1.y99_codtiaf.value = chave1;
  document.form1.y90_codtiaf.value = chave2;
  db_iframe_tiaf.hide();
}
function js_pesquisay99_tiafdoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tiafdoc','func_tiafdoc.php?funcao_js=parent.js_mostratiafdoc1|y99_coddoc|y99_coddoc','Pesquisa',true);
  }else{
     if(document.form1.y99_tiafdoc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tiafdoc','func_tiafdoc.php?pesquisa_chave='+document.form1.y99_tiafdoc.value+'&funcao_js=parent.js_mostratiafdoc','Pesquisa',false);
     }else{
       document.form1.y99_coddoc.value = ''; 
     }
  }
}
function js_mostratiafdoc(chave,erro){
  document.form1.y99_coddoc.value = chave; 
  if(erro==true){ 
    document.form1.y99_tiafdoc.focus(); 
    document.form1.y99_tiafdoc.value = ''; 
  }
}
function js_mostratiafdoc1(chave1,chave2){
  document.form1.y99_tiafdoc.value = chave1;
  document.form1.y99_coddoc.value = chave2;
  db_iframe_tiafdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tiafdoc','func_tiafdoc.php?funcao_js=parent.js_preenchepesquisa|y99_coddoc','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tiafdoc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>