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

//MODULO: cadastro
$cltesinteroutros->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j39_idbql");
$clrotulo->label("j92_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj84_tesintertipo?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lj84_tesintertipo,"js_pesquisaj84_tesintertipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j84_tesintertipo',10,$Ij84_tesintertipo,true,'text',$db_opcao," onchange='js_pesquisaj84_tesintertipo(false);'")
?>
       <?
db_input('j92_descr',40,$Ij92_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj84_tesinter?>">
       <?
       db_ancora(@$Lj84_tesinter,"js_pesquisaj84_tesinter(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j84_tesinter',10,$Ij84_tesinter,true,'text',$db_opcao," onchange='js_pesquisaj84_tesinter(false);'")
?>
       <?
db_input('j39_idbql',4,$Ij39_idbql,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj84_tesinter(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tesinter','func_tesinter.php?funcao_js=parent.js_mostratesinter1|j39_sequencial|j39_idbql','Pesquisa',true);
  }else{
     if(document.form1.j84_tesinter.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tesinter','func_tesinter.php?pesquisa_chave='+document.form1.j84_tesinter.value+'&funcao_js=parent.js_mostratesinter','Pesquisa',false);
     }else{
       document.form1.j39_idbql.value = ''; 
     }
  }
}
function js_mostratesinter(chave,erro){
  document.form1.j39_idbql.value = chave; 
  if(erro==true){ 
    document.form1.j84_tesinter.focus(); 
    document.form1.j84_tesinter.value = ''; 
  }
}
function js_mostratesinter1(chave1,chave2){
  document.form1.j84_tesinter.value = chave1;
  document.form1.j39_idbql.value = chave2;
  db_iframe_tesinter.hide();
}
function js_pesquisaj84_tesintertipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tesintertipo','func_tesintertipo.php?funcao_js=parent.js_mostratesintertipo1|j92_sequencial|j92_descr','Pesquisa',true);
  }else{
     if(document.form1.j84_tesintertipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tesintertipo','func_tesintertipo.php?pesquisa_chave='+document.form1.j84_tesintertipo.value+'&funcao_js=parent.js_mostratesintertipo','Pesquisa',false);
     }else{
       document.form1.j92_descr.value = ''; 
     }
  }
}
function js_mostratesintertipo(chave,erro){
  document.form1.j92_descr.value = chave; 
  if(erro==true){ 
    document.form1.j84_tesintertipo.focus(); 
    document.form1.j84_tesintertipo.value = ''; 
  }
}
function js_mostratesintertipo1(chave1,chave2){
  document.form1.j84_tesintertipo.value = chave1;
  document.form1.j92_descr.value = chave2;
  db_iframe_tesintertipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tesinteroutros','func_tesinteroutros.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tesinteroutros.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>