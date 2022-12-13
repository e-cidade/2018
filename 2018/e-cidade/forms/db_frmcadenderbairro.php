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

//MODULO: Configuracoes
$clcadenderbairro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db72_descricao");
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top: 15px;">
<tr><td>

<fieldset>
<legend><b>Bairros</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb73_sequencial?>">
       <?=@$Ldb73_sequencial?>
    </td>
    <td> 
<?
db_input('db73_sequencial',10,$Idb73_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb73_cadendermunicipio?>">
       <?
       db_ancora(@$Ldb73_cadendermunicipio,"js_pesquisadb73_cadendermunicipio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db73_cadendermunicipio',10,$Idb73_cadendermunicipio,true,'text',$db_opcao," onchange='js_pesquisadb73_cadendermunicipio(false);'")
?>
       <?
db_input('db72_descricao',26,$Idb72_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb73_descricao?>">
       <?=@$Ldb73_descricao?>
    </td>
    <td> 
<?
db_input('db73_descricao',40,$Idb73_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb73_sigla?>">
       <?=@$Ldb73_sigla?>
    </td>
    <td> 
<?
db_input('db73_sigla',2,$Idb73_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  
</fieldset>

</td></tr>
</table>  
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb73_cadendermunicipio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?funcao_js=parent.js_mostracadendermunicipio1|db72_sequencial|db72_descricao','Pesquisa',true);
  }else{
     if(document.form1.db73_cadendermunicipio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?pesquisa_chave='+document.form1.db73_cadendermunicipio.value+'&funcao_js=parent.js_mostracadendermunicipio','Pesquisa',false);
     }else{
       document.form1.db72_descricao.value = ''; 
     }
  }
}
function js_mostracadendermunicipio(chave,erro){
  document.form1.db72_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db73_cadendermunicipio.focus(); 
    document.form1.db73_cadendermunicipio.value = ''; 
  }
}
function js_mostracadendermunicipio1(chave1,chave2){
  document.form1.db73_cadendermunicipio.value = chave1;
  document.form1.db72_descricao.value = chave2;
  db_iframe_cadendermunicipio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadenderbairro','func_cadenderbairro.php?funcao_js=parent.js_preenchepesquisa|db73_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadenderbairro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>