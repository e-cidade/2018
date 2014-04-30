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
$cldb_versaotarefa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db30_codversao");
$clrotulo->label("at40_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb29_seqvertar?>">
       <?=@$Ldb29_seqvertar?>
    </td>
    <td> 
<?
db_input('db29_seqvertar',8,$Idb29_seqvertar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb29_codver?>">
       <?
       db_ancora(@$Ldb29_codver,"js_pesquisadb29_codver(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db29_codver',6,$Idb29_codver,true,'text',$db_opcao," onchange='js_pesquisadb29_codver(false);'")
?>
       <?
db_input('db30_codversao',6,$Idb30_codversao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb29_tarefa?>">
       <?
       db_ancora(@$Ldb29_tarefa,"js_pesquisadb29_tarefa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db29_tarefa',10,$Idb29_tarefa,true,'text',$db_opcao," onchange='js_pesquisadb29_tarefa(false);'")
?>
       <?
db_input('at40_descr',1,$Iat40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb29_codver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_versao','func_db_versao.php?funcao_js=parent.js_mostradb_versao1|db30_codver|db30_codversao','Pesquisa',true);
  }else{
     if(document.form1.db29_codver.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_versao','func_db_versao.php?pesquisa_chave='+document.form1.db29_codver.value+'&funcao_js=parent.js_mostradb_versao','Pesquisa',false);
     }else{
       document.form1.db30_codversao.value = ''; 
     }
  }
}
function js_mostradb_versao(chave,erro){
  document.form1.db30_codversao.value = chave; 
  if(erro==true){ 
    document.form1.db29_codver.focus(); 
    document.form1.db29_codver.value = ''; 
  }
}
function js_mostradb_versao1(chave1,chave2){
  document.form1.db29_codver.value = chave1;
  document.form1.db30_codversao.value = chave2;
  db_iframe_db_versao.hide();
}
function js_pesquisadb29_tarefa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tarefa','func_tarefa.php?funcao_js=parent.js_mostratarefa1|at40_sequencial|at40_descr','Pesquisa',true);
  }else{
     if(document.form1.db29_tarefa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tarefa','func_tarefa.php?pesquisa_chave='+document.form1.db29_tarefa.value+'&funcao_js=parent.js_mostratarefa','Pesquisa',false);
     }else{
       document.form1.at40_descr.value = ''; 
     }
  }
}
function js_mostratarefa(chave,erro){
  document.form1.at40_descr.value = chave; 
  if(erro==true){ 
    document.form1.db29_tarefa.focus(); 
    document.form1.db29_tarefa.value = ''; 
  }
}
function js_mostratarefa1(chave1,chave2){
  document.form1.db29_tarefa.value = chave1;
  document.form1.at40_descr.value = chave2;
  db_iframe_tarefa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_versaotarefa','func_db_versaotarefa.php?funcao_js=parent.js_preenchepesquisa|db29_seqvertar','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_versaotarefa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>