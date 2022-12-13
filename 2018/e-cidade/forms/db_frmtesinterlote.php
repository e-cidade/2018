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
$cltesinterlote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j39_idbql");
$clrotulo->label("j34_setor");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj69_tesinter?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lj69_tesinter,"js_pesquisaj69_tesinter(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j69_tesinter',10,$Ij69_tesinter,true,'text',$db_opcao," onchange='js_pesquisaj69_tesinter(false);'")
?>
       <?
db_input('j39_idbql',4,$Ij39_idbql,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj69_idbql?>">
       <?
       db_ancora(@$Lj69_idbql,"js_pesquisaj69_idbql(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j69_idbql',6,$Ij69_idbql,true,'text',$db_opcao," onchange='js_pesquisaj69_idbql(false);'")
?>
       <?
db_input('j34_setor',4,$Ij34_setor,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj69_tesinter(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tesinter','func_tesinter.php?funcao_js=parent.js_mostratesinter1|j39_sequencial|j39_idbql','Pesquisa',true);
  }else{
     if(document.form1.j69_tesinter.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tesinter','func_tesinter.php?pesquisa_chave='+document.form1.j69_tesinter.value+'&funcao_js=parent.js_mostratesinter','Pesquisa',false);
     }else{
       document.form1.j39_idbql.value = ''; 
     }
  }
}
function js_mostratesinter(chave,erro){
  document.form1.j39_idbql.value = chave; 
  if(erro==true){ 
    document.form1.j69_tesinter.focus(); 
    document.form1.j69_tesinter.value = ''; 
  }
}
function js_mostratesinter1(chave1,chave2){
  document.form1.j69_tesinter.value = chave1;
  document.form1.j39_idbql.value = chave2;
  db_iframe_tesinter.hide();
}
function js_pesquisaj69_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql|j34_setor','Pesquisa',true);
  }else{
     if(document.form1.j69_idbql.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.j69_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }else{
       document.form1.j34_setor.value = ''; 
     }
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.j69_idbql.focus(); 
    document.form1.j69_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2){
  document.form1.j69_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  db_iframe_lote.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tesinterlote','func_tesinterlote.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tesinterlote.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>