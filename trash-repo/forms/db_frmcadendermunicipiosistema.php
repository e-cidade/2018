<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clcadendermunicipiosistema->rotulo->label();
$clcadendermunicipiosistema->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("db72_descricao");
$clrotulo->label("db124_descricao");
?>
<form name="form1" method="post" action="">
<center>

<fieldset style="margin-top:50px;">
  <legend><b>Vinculo de município com sistema</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb125_sequencial?>">
       <b>Sequêncial:</b>
    </td>
    <td> 
<?
db_input('db125_sequencial',10,$Idb125_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb125_cadendermunicipio?>">
       <b>
       <?
       db_ancora('Município:',"js_pesquisadb125_cadendermunicipio(true);",$db_opcao);
       ?>
       </b>
    </td>
    <td> 
<?
db_input('db125_cadendermunicipio',10,$Idb125_cadendermunicipio,true,'text',$db_opcao," onchange='js_pesquisadb125_cadendermunicipio(false);'")
?>
       <?
db_input('db72_descricao',50,$Idb72_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb125_db_sistemaexterno?>">
      <b>
       <?
       db_ancora('Sistema: ',"js_pesquisadb125_db_sistemaexterno(true);",$db_opcao);
       ?>
       </b>
    </td>
    <td> 
<?
db_input('db125_db_sistemaexterno',10,$Idb125_db_sistemaexterno,true,'text',$db_opcao," onchange='js_pesquisadb125_db_sistemaexterno(false);'")
?>
       <?
db_input('db124_descricao',50,$Idb124_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb125_codigosistema?>">
       <?=@$Ldb125_codigosistema?>
    </td>
    <td> 
<?
db_input('db125_codigosistema',10,$Idb125_codigosistema,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  
  </fieldset>
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb125_cadendermunicipio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?funcao_js=parent.js_mostracadendermunicipio1|db72_sequencial|db72_descricao','Pesquisa',true);
  }else{
     if(document.form1.db125_cadendermunicipio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?pesquisa_chave='+document.form1.db125_cadendermunicipio.value+'&funcao_js=parent.js_mostracadendermunicipio','Pesquisa',false);
     }else{
       document.form1.db72_descricao.value = ''; 
     }
  }
}
function js_mostracadendermunicipio(chave,erro){
  document.form1.db72_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db125_cadendermunicipio.focus(); 
    document.form1.db125_cadendermunicipio.value = ''; 
  }
}
function js_mostracadendermunicipio1(chave1,chave2){
  document.form1.db125_cadendermunicipio.value = chave1;
  document.form1.db72_descricao.value = chave2;
  db_iframe_cadendermunicipio.hide();
}
function js_pesquisadb125_db_sistemaexterno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sistemaexterno','func_db_sistemaexterno.php?funcao_js=parent.js_mostradb_sistemaexterno1|db124_sequencial|db124_descricao','Pesquisa',true);
  }else{
     if(document.form1.db125_db_sistemaexterno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sistemaexterno','func_db_sistemaexterno.php?pesquisa_chave='+document.form1.db125_db_sistemaexterno.value+'&funcao_js=parent.js_mostradb_sistemaexterno','Pesquisa',false);
     }else{
       document.form1.db124_descricao.value = ''; 
     }
  }
}
function js_mostradb_sistemaexterno(chave,erro){
  document.form1.db124_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db125_db_sistemaexterno.focus(); 
    document.form1.db125_db_sistemaexterno.value = ''; 
  }
}
function js_mostradb_sistemaexterno1(chave1,chave2){
  document.form1.db125_db_sistemaexterno.value = chave1;
  document.form1.db124_descricao.value = chave2;
  db_iframe_db_sistemaexterno.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipiosistema','func_cadendermunicipiosistema.php?funcao_js=parent.js_preenchepesquisa|db125_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadendermunicipiosistema.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>