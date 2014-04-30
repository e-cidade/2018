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

//MODULO: atendimento
$cltarefaprojetoativcli->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at40_descr");
$clrotulo->label("at64_codproj");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat69_sequencial?>">
       <?=@$Lat69_sequencial?>
    </td>
    <td> 
<?
db_input('at69_sequencial',8,$Iat69_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat69_seqprojeto?>">
       <?
       db_ancora(@$Lat69_seqprojeto,"js_pesquisaat69_seqprojeto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at69_seqprojeto',10,$Iat69_seqprojeto,true,'text',$db_opcao," onchange='js_pesquisaat69_seqprojeto(false);'")
?>
       <?
db_input('at64_codproj',10,$Iat64_codproj,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat69_seqtarefa?>">
       <?
       db_ancora(@$Lat69_seqtarefa,"js_pesquisaat69_seqtarefa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at69_seqtarefa',10,$Iat69_seqtarefa,true,'text',$db_opcao," onchange='js_pesquisaat69_seqtarefa(false);'")
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
function js_pesquisaat69_seqtarefa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tarefa','func_tarefa.php?funcao_js=parent.js_mostratarefa1|at40_sequencial|at40_descr','Pesquisa',true);
  }else{
     if(document.form1.at69_seqtarefa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tarefa','func_tarefa.php?pesquisa_chave='+document.form1.at69_seqtarefa.value+'&funcao_js=parent.js_mostratarefa','Pesquisa',false);
     }else{
       document.form1.at40_descr.value = ''; 
     }
  }
}
function js_mostratarefa(chave,erro){
  document.form1.at40_descr.value = chave; 
  if(erro==true){ 
    document.form1.at69_seqtarefa.focus(); 
    document.form1.at69_seqtarefa.value = ''; 
  }
}
function js_mostratarefa1(chave1,chave2){
  document.form1.at69_seqtarefa.value = chave1;
  document.form1.at40_descr.value = chave2;
  db_iframe_tarefa.hide();
}
function js_pesquisaat69_seqprojeto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_projetosativcli','func_db_projetosativcli.php?funcao_js=parent.js_mostradb_projetosativcli1|at64_sequencial|at64_codproj','Pesquisa',true);
  }else{
     if(document.form1.at69_seqprojeto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_projetosativcli','func_db_projetosativcli.php?pesquisa_chave='+document.form1.at69_seqprojeto.value+'&funcao_js=parent.js_mostradb_projetosativcli','Pesquisa',false);
     }else{
       document.form1.at64_codproj.value = ''; 
     }
  }
}
function js_mostradb_projetosativcli(chave,erro){
  document.form1.at64_codproj.value = chave; 
  if(erro==true){ 
    document.form1.at69_seqprojeto.focus(); 
    document.form1.at69_seqprojeto.value = ''; 
  }
}
function js_mostradb_projetosativcli1(chave1,chave2){
  document.form1.at69_seqprojeto.value = chave1;
  document.form1.at64_codproj.value = chave2;
  db_iframe_db_projetosativcli.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tarefaprojetoativcli','func_tarefaprojetoativcli.php?funcao_js=parent.js_preenchepesquisa|at69_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tarefaprojetoativcli.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>