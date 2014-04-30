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

//MODULO: notificacoes
$clnotificaarretipodoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db03_descr");
$clrotulo->label("k00_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk101_sequencial?>">
       <?=@$Lk101_sequencial?>
    </td>
    <td> 
<?
db_input('k101_sequencial',10,$Ik101_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk101_db_documento?>">
       <?
       db_ancora(@$Lk101_db_documento,"js_pesquisak101_db_documento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k101_db_documento',10,$Ik101_db_documento,true,'text',$db_opcao," onchange='js_pesquisak101_db_documento(false);'")
?>
       <?
db_input('db03_descr',40,$Idb03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk101_tipo?>">
       <?
       db_ancora(@$Lk101_tipo,"js_pesquisak101_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k101_tipo',10,$Ik101_tipo,true,'text',$db_opcao," onchange='js_pesquisak101_tipo(false);'")
?>
       <?
db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak101_db_documento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.k101_db_documento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.k101_db_documento.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = ''; 
     }
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.k101_db_documento.focus(); 
    document.form1.k101_db_documento.value = ''; 
  }
}
function js_mostradb_documento1(chave1,chave2){
  document.form1.k101_db_documento.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}
function js_pesquisak101_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.k101_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k101_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k101_tipo.focus(); 
    document.form1.k101_tipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.k101_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_notificaarretipodoc','func_notificaarretipodoc.php?funcao_js=parent.js_preenchepesquisa|k101_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_notificaarretipodoc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>