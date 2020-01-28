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
$cldb_layouttxt->rotulo->label();

$cldb_layouttxt->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db56_descr");

      if($db_opcao==1){
 	   $db_action="con1_db_layouttxt004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="con1_db_layouttxt005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="con1_db_layouttxt006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb50_codigo?>">
       <?=@$Ldb50_codigo?>
    </td>
    <td colspan=3 nowrap> 
<?
db_input('db50_codigo',6,$Idb50_codigo,true,'text',3,"");
if(isset($chavepesquisa)){
  db_input('codigoimporta',6,0,true,'hidden',3,"");
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb50_descr?>">
       <?=@$Ldb50_descr?>
    </td>
    <td colspan=3 nowrap> 
<?
db_input('db50_descr',46,$Idb50_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb50_layouttxtgrupo?>">
       <?
       db_ancora(@$Ldb50_layouttxtgrupo,"js_pesquisadb50_layouttxtgrupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db50_layouttxtgrupo',6,$Idb50_layouttxtgrupo,true,'text',$db_opcao," onchange='js_pesquisadb50_layouttxtgrupo(false);'")
?>
       <?
db_input('db56_descr',37,$Idb56_descr,true,'text',3,'')
       ?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tdb50_obs?>">
       <?=@$Ldb50_obs?>
    </td>
    <td nowrap> 
<?
db_textarea('db50_obs',4,44,$Idb50_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb50_quantlinhas?>">
       <?=@$Ldb50_quantlinhas?>
    </td>
    <td> 
<?
db_input('db50_quantlinhas',10,$Idb50_quantlinhas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?(!isset($chavepesquisa)?"incluir":"importar"):($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?(!isset($chavepesquisa)?"Incluir":"Importar"):($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?if($db_opcao == 1 && !isset($chavepesquisa)){?>
<input name="importar" type="button" id="importar" value="Importar layout" onclick="js_pesquisa();" >
<?}else if($db_opcao != 1){?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}else{?>
<input name="novo" type="button" id="novo" value="Novo" onclick="location.href='con1_db_layouttxt004.php'" >
<?}?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_db_layouttxt','db_iframe_db_layouttxt','func_db_layouttxt.php?funcao_js=parent.js_preenchepesquisa|db50_codigo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_db_layouttxt.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
function js_pesquisadb50_layouttxtgrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_layouttxt','db_iframe_db_layouttxtgrupo','func_db_layouttxtgrupo.php?funcao_js=parent.js_mostradb_layouttxtgrupo1|db56_sequencial|db56_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.db50_layouttxtgrupo.value != ''){ 
       js_OpenJanelaIframe('top.corpo.iframe_db_layouttxt','db_iframe_db_layouttxtgrupo','func_db_layouttxtgrupo.php?pesquisa_chave='+document.form1.db50_layouttxtgrupo.value+'&funcao_js=parent.js_mostradb_layouttxtgrupo','Pesquisa',false,'0');
     }else{
       document.form1.db56_descr.value = ''; 
     }
  }
}
function js_mostradb_layouttxtgrupo(chave,erro){
  document.form1.db56_descr.value = chave; 
  if(erro==true){ 
    document.form1.db50_layouttxtgrupo.focus(); 
    document.form1.db50_layouttxtgrupo.value = ''; 
  }
}
function js_mostradb_layouttxtgrupo1(chave1,chave2){
  document.form1.db50_layouttxtgrupo.value = chave1;
  document.form1.db56_descr.value = chave2;
  db_iframe_db_layouttxtgrupo.hide();
}
</script>