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
$cldb_versaoclientes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db30_codversao");
$clrotulo->label("at01_nomecli");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb19_sequen?>">
       <?=@$Ldb19_sequen?>
    </td>
    <td> 
<?
db_input('db19_sequen',6,$Idb19_sequen,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb19_codver?>">
       <?
       db_ancora(@$Ldb19_codver,"js_pesquisadb19_codver(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db19_codver',6,$Idb19_codver,true,'text',$db_opcao," onchange='js_pesquisadb19_codver(false);'")
?>
       <?
db_input('db30_codversao',6,$Idb30_codversao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb19_codcli?>">
       <?
       db_ancora(@$Ldb19_codcli,"js_pesquisadb19_codcli(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db19_codcli',4,$Idb19_codcli,true,'text',$db_opcao," onchange='js_pesquisadb19_codcli(false);'")
?>
       <?
db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb19_data?>">
       <?=@$Ldb19_data?>
    </td>
    <td> 
<?
db_inputdata('db19_data',@$db19_data_dia,@$db19_data_mes,@$db19_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb19_obs?>">
       <?=@$Ldb19_obs?>
    </td>
    <td> 
<?
db_textarea('db19_obs',0,0,$Idb19_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb19_codver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_versao','func_db_versao.php?funcao_js=parent.js_mostradb_versao1|db30_codver|db30_codversao','Pesquisa',true);
  }else{
     if(document.form1.db19_codver.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_versao','func_db_versao.php?pesquisa_chave='+document.form1.db19_codver.value+'&funcao_js=parent.js_mostradb_versao','Pesquisa',false);
     }else{
       document.form1.db30_codversao.value = ''; 
     }
  }
}
function js_mostradb_versao(chave,erro){
  document.form1.db30_codversao.value = chave; 
  if(erro==true){ 
    document.form1.db19_codver.focus(); 
    document.form1.db19_codver.value = ''; 
  }
}
function js_mostradb_versao1(chave1,chave2){
  document.form1.db19_codver.value = chave1;
  document.form1.db30_codversao.value = chave2;
  db_iframe_db_versao.hide();
}
function js_pesquisadb19_codcli(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_mostraclientes1|at01_codcli|at01_nomecli','Pesquisa',true);
  }else{
     if(document.form1.db19_codcli.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_clientes','func_clientes.php?pesquisa_chave='+document.form1.db19_codcli.value+'&funcao_js=parent.js_mostraclientes','Pesquisa',false);
     }else{
       document.form1.at01_nomecli.value = ''; 
     }
  }
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.db19_codcli.focus(); 
    document.form1.db19_codcli.value = ''; 
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.db19_codcli.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe_clientes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_versaoclientes','func_db_versaoclientes.php?funcao_js=parent.js_preenchepesquisa|db19_sequen','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_versaoclientes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>