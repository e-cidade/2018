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
$cldb_layouttxtgrupo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db57_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb56_sequencial?>">
       <?=@$Ldb56_sequencial?>
    </td>
    <td> 
<?
db_input('db56_sequencial',4,$Idb56_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb56_layouttxtgrupotipo?>">
       <?
       db_ancora(@$Ldb56_layouttxtgrupotipo,"js_pesquisadb56_layouttxtgrupotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db56_layouttxtgrupotipo',4,$Idb56_layouttxtgrupotipo,true,'text',$db_opcao," onchange='js_pesquisadb56_layouttxtgrupotipo(false);'")
?>
       <?
db_input('db57_descr',40,$Idb57_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb56_descr?>">
       <?=@$Ldb56_descr?>
    </td>
    <td> 
<?
db_input('db56_descr',40,$Idb56_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb56_layouttxtgrupotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_layouttxtgrupotipo','func_db_layouttxtgrupotipo.php?funcao_js=parent.js_mostradb_layouttxtgrupotipo1|db57_sequencial|db57_descr','Pesquisa',true);
  }else{
     if(document.form1.db56_layouttxtgrupotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_layouttxtgrupotipo','func_db_layouttxtgrupotipo.php?pesquisa_chave='+document.form1.db56_layouttxtgrupotipo.value+'&funcao_js=parent.js_mostradb_layouttxtgrupotipo','Pesquisa',false);
     }else{
       document.form1.db57_descr.value = ''; 
     }
  }
}
function js_mostradb_layouttxtgrupotipo(chave,erro){
  document.form1.db57_descr.value = chave; 
  if(erro==true){ 
    document.form1.db56_layouttxtgrupotipo.focus(); 
    document.form1.db56_layouttxtgrupotipo.value = ''; 
  }
}
function js_mostradb_layouttxtgrupotipo1(chave1,chave2){
  document.form1.db56_layouttxtgrupotipo.value = chave1;
  document.form1.db57_descr.value = chave2;
  db_iframe_db_layouttxtgrupotipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_layouttxtgrupo','func_db_layouttxtgrupo.php?funcao_js=parent.js_preenchepesquisa|db56_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_layouttxtgrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>