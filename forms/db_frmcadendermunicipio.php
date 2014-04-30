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
$clcadendermunicipio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db71_descricao");
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top:15px;">
<tr><td> 

<fieldset>
<legend><b>Municípios</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb72_sequencial?>">
       <?=@$Ldb72_sequencial?>
    </td>
    <td> 
<?
db_input('db72_sequencial',10,$Idb72_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb72_cadenderestado?>">
       <?
       db_ancora(@$Ldb72_cadenderestado,"js_pesquisadb72_cadenderestado(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db72_cadenderestado',10,$Idb72_cadenderestado,true,'text',$db_opcao," onchange='js_pesquisadb72_cadenderestado(false);'")
?>
       <?
db_input('db71_descricao',26,$Idb71_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb72_descricao?>">
       <?=@$Ldb72_descricao?>
    </td>
    <td> 
<?
db_input('db72_descricao',40,$Idb72_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb72_sigla?>">
       <?=@$Ldb72_sigla?>
    </td>
    <td> 
<?
db_input('db72_sigla',2,$Idb72_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb72_cepinicial?>">
       <?=@$Ldb72_cepinicial?>
    </td>
    <td> 
<?
db_input('db72_cepinicial',8,$Idb72_cepinicial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb72_cepfinal?>">
       <?=@$Ldb72_cepfinal?>
    </td>
    <td> 
<?
db_input('db72_cepfinal',8,$Idb72_cepfinal,true,'text',$db_opcao,"")
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
function js_pesquisadb72_cadenderestado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadenderestado','func_cadenderestado.php?funcao_js=parent.js_mostracadenderestado1|db71_sequencial|db71_descricao','Pesquisa',true);
  }else{
     if(document.form1.db72_cadenderestado.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadenderestado','func_cadenderestado.php?pesquisa_chave='+document.form1.db72_cadenderestado.value+'&funcao_js=parent.js_mostracadenderestado','Pesquisa',false);
     }else{
       document.form1.db71_descricao.value = ''; 
     }
  }
}
function js_mostracadenderestado(chave,erro){
  document.form1.db71_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db72_cadenderestado.focus(); 
    document.form1.db72_cadenderestado.value = ''; 
  }
}
function js_mostracadenderestado1(chave1,chave2){
  document.form1.db72_cadenderestado.value = chave1;
  document.form1.db71_descricao.value = chave2;
  db_iframe_cadenderestado.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadendermunicipio','func_cadendermunicipio.php?funcao_js=parent.js_preenchepesquisa|db72_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadendermunicipio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>