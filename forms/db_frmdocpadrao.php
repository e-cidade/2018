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
$cldb_documentopadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db61_codparag");
$clrotulo->label("db61_descr");
$clrotulo->label("db08_descr");
if ($db_opcao == 1) {
	$db60_instit = db_getsession("DB_instit");
}
?>
<form name="form1" method="post" onSubmit="return js_selecionar()">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb60_coddoc?>">
       <?=@$Ldb60_coddoc?>
    </td>
    <td> 
       <?db_input('db60_coddoc', 8, $Idb60_coddoc, true, 'text', 3, "")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb60_descr?>">
       <?=@$Ldb60_descr?>
    </td>
    <td> 
       <?db_input('db60_descr', 40, $Idb60_descr, true, 'text', $db_opcao, "")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb60_tipodoc?>">
       <?db_ancora(@ $Ldb60_tipodoc, "js_pesquisadb60_tipodoc(true);", $db_opcao);?>
    </td>
    <td> 
       <?db_input('db60_tipodoc', 10, $Idb60_tipodoc, true, 'text', $db_opcao, " onchange='js_pesquisadb60_tipodoc(false);'")?>
       <?db_input('db08_descr', 40, $Idb08_descr, true, 'text', 3, '')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb60_instit?>">
       <?=$Ldb60_instit?>
    </td>
    <td> 
      <?$result = $cldb_config->sql_record($cldb_config->sql_query_file(null, "codigo,nomeinst", "", " codigo = ".db_getsession("DB_instit")));
        db_selectrecord("db60_instit", $result, true, $db_opcao, "", "", "");?>
    </td>
  </tr>
</table>
</center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"))?>" <?=($db_botao==false?"disabled":"")?>  >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
if ($db_opcao==1){
?>
<!--<input name="import" type="button" id="import" value="Importar Documento" onclick="js_importadoc();" >-->
<?
}
?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_db_documentopadrao','func_db_documentopadrao.php?funcao_js=parent.js_preenchepesquisa|db60_coddoc','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_db_documentopadrao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisadb60_tipodoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_tipodoc','func_db_tipodoc.php?funcao_js=parent.js_mostradb_tipodoc1|db08_codigo|db08_descr','Pesquisa',true);
  }else{
     if(document.form1.db60_tipodoc.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_tipodoc','func_db_tipodoc.php?pesquisa_chave='+document.form1.db60_tipodoc.value+'&funcao_js=parent.js_mostradb_tipodoc','Pesquisa',false);
     }else{
       document.form1.db08_descr.value = ''; 
     }
  }
}
function js_mostradb_tipodoc(chave,erro){
  document.form1.db08_descr.value = chave; 
  if(erro==true){ 
    document.form1.db60_tipodoc.focus(); 
    document.form1.db60_tipodoc.value = ''; 
  }
}
function js_mostradb_tipodoc1(chave1,chave2){
  document.form1.db60_tipodoc.value = chave1;
  document.form1.db08_descr.value = chave2;
  db_iframe_db_tipodoc.hide();
}
function js_importadoc(){
  js_OpenJanelaIframe('','db_iframe_db_documentopadrao','func_db_documentopadrao.php?funcao_js=parent.js_import|db60_coddoc|db60_descr','Pesquisa',true);
}
function js_import(chave1,chave2){
  db_iframe_db_documentopadrao.hide();
  if(confirm('Deseja realmente importar o Documento '+chave1+'-'+chave2+'?')){
      js_OpenJanelaIframe('','db_iframe_importa','con4_importadoc001.php?documento='+chave1,'',false);
  }
}
function js_retornaimport(cod,descr,erro){
     db_iframe_importa.hide();
     if (erro=="true"){
         alert("Operação Cancelada!!Contate Suporte!!");   
     }else{

      	alert("Foi incluido o Documento "+cod+"-"+descr);
      	parent.document.formaba.parag.disabled=false;
      	top.corpo.iframe_parag.location.href='con4_docpadrao003.php?db60_coddoc='+cod;
        top.corpo.iframe_doc.location.href='con4_docpadrao004.php?chavepesquisa='+cod;
     }
}
</script>