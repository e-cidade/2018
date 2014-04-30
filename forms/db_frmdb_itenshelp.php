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
$cldb_itenshelp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descricao");
$clrotulo->label("dhelp_resum");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tid_item?>">
       <?
       db_ancora(@$Lid_item,"js_pesquisaid_item(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('id_item',5,$Iid_item,true,'text',$db_opcao," onchange='js_pesquisaid_item(false);'")
?>
       <?
db_input('descricao',40,$Idescricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tid_help?>">
       <?
       db_ancora(@$Lid_help,"js_pesquisaid_help(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('id_help',5,$Iid_help,true,'text',$db_opcao," onchange='js_pesquisaid_help(false);'")
?>
       <?
db_input('dhelp_resum',60,$Idhelp_resum,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaid_item(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_itensmenu','func_db_itensmenu.php?funcao_js=parent.js_mostradb_itensmenu1|id_item|descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_db_itensmenu','func_db_itensmenu.php?pesquisa_chave='+document.form1.id_item.value+'&funcao_js=parent.js_mostradb_itensmenu','Pesquisa',false);
  }
}
function js_mostradb_itensmenu(chave,erro){
  document.form1.descricao.value = chave; 
  if(erro==true){ 
    document.form1.id_item.focus(); 
    document.form1.id_item.value = ''; 
  }
}
function js_mostradb_itensmenu1(chave1,chave2){
  document.form1.id_item.value = chave1;
  document.form1.descricao.value = chave2;
  db_iframe_db_itensmenu.hide();
}
function js_pesquisaid_help(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_cadhelp','func_db_cadhelp.php?funcao_js=parent.js_mostradb_cadhelp1|id_help|dhelp_resum','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_db_cadhelp','func_db_cadhelp.php?pesquisa_chave='+document.form1.id_help.value+'&funcao_js=parent.js_mostradb_cadhelp','Pesquisa',false);
  }
}
function js_mostradb_cadhelp(chave,erro){
  document.form1.dhelp_resum.value = chave; 
  if(erro==true){ 
    document.form1.id_help.focus(); 
    document.form1.id_help.value = ''; 
  }
}
function js_mostradb_cadhelp1(chave1,chave2){
  document.form1.id_help.value = chave1;
  document.form1.dhelp_resum.value = chave2;
  db_iframe_db_cadhelp.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_itenshelp','func_db_itenshelp.php?funcao_js=parent.js_preenchepesquisa|id_item|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_db_itenshelp.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
+"&chavepesquisa1="+chave1}
</script>