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

//MODULO: configuracoes
$cldb_syscadproced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomemod");
$clrotulo->label("at25_descr");
      if($db_opcao==1){
 	   $db_action="con1_db_syscadproced004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_db_syscadproced005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="con1_db_syscadproced006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcodproced?>">
       <?=@$Lcodproced?>
    </td>
    <td> 
<?
db_input('codproced',10,$Icodproced,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdescrproced?>">
       <?=@$Ldescrproced?>
    </td>
    <td> 
<?
db_input('descrproced',60,$Idescrproced,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tobsproced?>">
       <?=@$Lobsproced?>
    </td>
    <td> 
<?
db_textarea('obsproced',20,110,$Iobsproced,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodmod?>">
       <?
       db_ancora(@$Lcodmod,"js_pesquisacodmod(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('codmod',4,$Icodmod,true,'text',$db_opcao," onchange='js_pesquisacodmod(false);'")
?>
       <?
db_input('nomemod',40,$Inomemod,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodarea?>">
       <?
       db_ancora(@$Lcodarea,"js_pesquisacodarea(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('codarea',10,$Icodarea,true,'text',$db_opcao," onchange='js_pesquisacodarea(false);'")
?>
       <?
db_input('at25_descr',30,$Iat25_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacodmod(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_syscadproced','db_iframe_db_sysmodulo','func_db_sysmodulo.php?funcao_js=parent.js_mostradb_sysmodulo1|codmod|nomemod','Pesquisa',true);
  }else{
     if(document.form1.codmod.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_syscadproced','db_iframe_db_sysmodulo','func_db_sysmodulo.php?pesquisa_chave='+document.form1.codmod.value+'&funcao_js=parent.js_mostradb_sysmodulo','Pesquisa',false);
     }else{
       document.form1.nomemod.value = ''; 
     }
  }
}
function js_mostradb_sysmodulo(chave,erro){
  document.form1.nomemod.value = chave; 
  if(erro==true){ 
    document.form1.codmod.focus(); 
    document.form1.codmod.value = ''; 
  }
}
function js_mostradb_sysmodulo1(chave1,chave2){
  document.form1.codmod.value = chave1;
  document.form1.nomemod.value = chave2;
  db_iframe_db_sysmodulo.hide();
}
function js_pesquisacodarea(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_syscadproced','db_iframe_atendcadarea','func_atendcadarea.php?funcao_js=parent.js_mostraatendcadarea1|at26_sequencial|at25_descr','Pesquisa',true);
  }else{
     if(document.form1.codarea.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_syscadproced','db_iframe_atendcadarea','func_atendcadarea.php?pesquisa_chave='+document.form1.codarea.value+'&funcao_js=parent.js_mostraatendcadarea','Pesquisa',false);
     }else{
       document.form1.at25_descr.value = ''; 
     }
  }
}
function js_mostraatendcadarea(chave,erro){
  document.form1.at25_descr.value = chave; 
  if(erro==true){ 
    document.form1.codarea.focus(); 
    document.form1.codarea.value = ''; 
  }
}
function js_mostraatendcadarea1(chave1,chave2){
  document.form1.codarea.value = chave1;
  document.form1.at25_descr.value = chave2;
  db_iframe_atendcadarea.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_db_syscadproced','db_iframe_db_syscadproced','func_db_syscadproced.php?funcao_js=parent.js_preenchepesquisa|codproced','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_syscadproced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>