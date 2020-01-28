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
$cldb_cadhelp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrtipohelp");
$clrotulo->label("id_item");
$clrotulo->label("descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tid_help?>">
       <?=@$Lid_help?>
    </td>
    <td> 
<?
db_input('id_help',5,$Iid_help,true,'text',3,"");
?>
    </td>
  </tr>
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
    <td nowrap title="<?=@$Tid_codtipo?>">
       <?
       db_ancora(@$Lid_codtipo,"js_pesquisaid_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('id_codtipo',5,$Iid_codtipo,true,'text',$db_opcao," onchange='js_pesquisaid_codtipo(false);'")
?>
       <?
db_input('descrtipohelp',40,$Idescrtipohelp,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdhelp_resum?>">
       <?=@$Ldhelp_resum?>
    </td>
    <td> 
<?
db_input('dhelp_resum',60,$Idhelp_resum,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap valign="top" title="<?=@$Tdhelp?>">
       <?=@$Ldhelp?>
    </td>
    <td> 
<?
db_textarea('dhelp',15,110,$Idhelp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="inserirlink" type="button" id="inserir" value="Links" onclick="js_pesquisalink();" >
<input name="inserirlinkproced" type="button" id="inserir_proced" value="Links Procedimentos" onclick="js_pesquisalink_procedimento();" >
</form>
<script>
function js_pesquisaid_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_tipohelp','func_db_tipohelp.php?funcao_js=parent.js_mostradb_tipohelp1|id_codtipo|descrtipohelp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_db_tipohelp','func_db_tipohelp.php?pesquisa_chave='+document.form1.id_codtipo.value+'&funcao_js=parent.js_mostradb_tipohelp','Pesquisa',false);
  }
}
function js_mostradb_tipohelp(chave,erro){
  document.form1.descrtipohelp.value = chave; 
  if(erro==true){ 
    document.form1.id_codtipo.focus(); 
    document.form1.id_codtipo.value = ''; 
  }
}
function js_mostradb_tipohelp1(chave1,chave2){
  document.form1.id_codtipo.value = chave1;
  document.form1.descrtipohelp.value = chave2;
  db_iframe_db_tipohelp.hide();
}

function js_pesquisalink(){
  js_OpenJanelaIframe('','db_iframe_db_cadhelp','con1_help003.php?cadhelp=true&item=0&modulo=0','Pesquisa',true);
}
function js_linca(chave){
  db_iframe_db_cadhelp.hide();
  document.form1.dhelp.value += '###'+chave+'###';
}

function js_pesquisalink_procedimento(){
  js_OpenJanelaIframe('','db_iframe_db_cadhelp','con1_help004.php','Pesquisa',true);
}
function js_linca_procedimento(chave){
  db_iframe_db_cadhelp.hide();
  document.form1.dhelp.value += '##'+chave+'##';
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_cadhelp','func_db_cadhelp.php?funcao_js=parent.js_preenchepesquisa|id_help','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_cadhelp.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
function js_pesquisaid_item(chave){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_cadhelp','con1_caditens002.php','Pesquisa',true);
}

function js_pesquisaitemcad(item,modulo){
  db_iframe_db_cadhelp.hide();
  document.form1.id_item.value = item;
  js_OpenJanelaIframe('top.corpo','db_iframe_db_cadhelp','func_db_itensmenu.php?pesquisa_chave='+item+'&funcao_js=parent.js_mostradb_iditem','Pesquisa',false);
}
function js_mostradb_iditem(chave,erro){
  document.form1.descricao.value = chave;
  if(erro==true){
    document.form1.id_item.focus();
    document.form1.id_item.value = '';
  }
}
</script>