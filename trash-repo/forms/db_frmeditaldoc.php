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

//MODULO: contrib
$cleditaldoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db03_descr");
$clrotulo->label("d01_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Td13_sequencial?>">
       <?=@$Ld13_sequencial?>
    </td>
    <td> 
<?
db_input('d13_sequencial',10,$Id13_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td13_db_documento?>">
       <?
       db_ancora(@$Ld13_db_documento,"js_pesquisad13_db_documento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d13_db_documento',8,$Id13_db_documento,true,'text',$db_opcao," onchange='js_pesquisad13_db_documento(false);'")
?>
       <?
db_input('db03_descr',40,$Idb03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td13_edital?>">
       <?
       db_ancora(@$Ld13_edital,"js_pesquisad13_edital(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d13_edital',4,$Id13_edital,true,'text',$db_opcao," onchange='js_pesquisad13_edital(false);'")
?>
       <?
db_input('d01_descr',40,$Id01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisad13_db_documento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.d13_db_documento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.d13_db_documento.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = ''; 
     }
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.d13_db_documento.focus(); 
    document.form1.d13_db_documento.value = ''; 
  }
}
function js_mostradb_documento1(chave1,chave2){
  document.form1.d13_db_documento.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}
function js_pesquisad13_edital(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_edital','func_edital.php?funcao_js=parent.js_mostraedital1|d01_codedi|d01_descr','Pesquisa',true);
  }else{
     if(document.form1.d13_edital.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_edital','func_edital.php?pesquisa_chave='+document.form1.d13_edital.value+'&funcao_js=parent.js_mostraedital','Pesquisa',false);
     }else{
       document.form1.d01_descr.value = ''; 
     }
  }
}
function js_mostraedital(chave,erro){
  document.form1.d01_descr.value = chave; 
  if(erro==true){ 
    document.form1.d13_edital.focus(); 
    document.form1.d13_edital.value = ''; 
  }
}
function js_mostraedital1(chave1,chave2){
  document.form1.d13_edital.value = chave1;
  document.form1.d01_descr.value = chave2;
  db_iframe_edital.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_editaldoc','func_editaldoc.php?funcao_js=parent.js_preenchepesquisa|d13_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_editaldoc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>