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

//MODULO: material
$clmatestoqueitemfabric->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("m76_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm78_sequencial?>">
       <?=@$Lm78_sequencial?>
    </td>
    <td> 
<?
db_input('m78_sequencial',10,$Im78_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm78_matestoqueitem?>">
       <?
       db_ancora(@$Lm78_matestoqueitem,"js_pesquisam78_matestoqueitem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m78_matestoqueitem',10,$Im78_matestoqueitem,true,'text',$db_opcao," onchange='js_pesquisam78_matestoqueitem(false);'")
?>
       <?
db_input('m71_codmatestoque',10,$Im71_codmatestoque,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm78_matfabricante?>">
       <?
       db_ancora(@$Lm78_matfabricante,"js_pesquisam78_matfabricante(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m78_matfabricante',10,$Im78_matfabricante,true,'text',$db_opcao," onchange='js_pesquisam78_matfabricante(false);'")
?>
       <?
db_input('m76_numcgm',10,$Im76_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam78_matestoqueitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?funcao_js=parent.js_mostramatestoqueitem1|m71_codlanc|m71_codmatestoque','Pesquisa',true);
  }else{
     if(document.form1.m78_matestoqueitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?pesquisa_chave='+document.form1.m78_matestoqueitem.value+'&funcao_js=parent.js_mostramatestoqueitem','Pesquisa',false);
     }else{
       document.form1.m71_codmatestoque.value = ''; 
     }
  }
}
function js_mostramatestoqueitem(chave,erro){
  document.form1.m71_codmatestoque.value = chave; 
  if(erro==true){ 
    document.form1.m78_matestoqueitem.focus(); 
    document.form1.m78_matestoqueitem.value = ''; 
  }
}
function js_mostramatestoqueitem1(chave1,chave2){
  document.form1.m78_matestoqueitem.value = chave1;
  document.form1.m71_codmatestoque.value = chave2;
  db_iframe_matestoqueitem.hide();
}
function js_pesquisam78_matfabricante(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matfabricante','func_matfabricante.php?funcao_js=parent.js_mostramatfabricante1|m76_sequencial|m76_numcgm','Pesquisa',true);
  }else{
     if(document.form1.m78_matfabricante.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matfabricante','func_matfabricante.php?pesquisa_chave='+document.form1.m78_matfabricante.value+'&funcao_js=parent.js_mostramatfabricante','Pesquisa',false);
     }else{
       document.form1.m76_numcgm.value = ''; 
     }
  }
}
function js_mostramatfabricante(chave,erro){
  document.form1.m76_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.m78_matfabricante.focus(); 
    document.form1.m78_matfabricante.value = ''; 
  }
}
function js_mostramatfabricante1(chave1,chave2){
  document.form1.m78_matfabricante.value = chave1;
  document.form1.m76_numcgm.value = chave2;
  db_iframe_matfabricante.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_matestoqueitemfabric','func_matestoqueitemfabric.php?funcao_js=parent.js_preenchepesquisa|m78_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matestoqueitemfabric.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>