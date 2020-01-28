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

//MODULO: orcamento
$clcategoriapacto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o29_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To31_sequencial?>">
       <?=@$Lo31_sequencial?>
    </td>
    <td> 
<?
db_input('o31_sequencial',10,$Io31_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To31_tipopacto?>">
       <?
       db_ancora(@$Lo31_tipopacto,"js_pesquisao31_tipopacto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o31_tipopacto',10,$Io31_tipopacto,true,'text',$db_opcao," onchange='js_pesquisao31_tipopacto(false);'")
?>
       <?
db_input('o29_descricao',40,$Io29_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To31_descricao?>">
       <?=@$Lo31_descricao?>
    </td>
    <td> 
<?
db_input('o31_descricao',54,$Io31_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao31_tipopacto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipopacto','func_tipopacto.php?funcao_js=parent.js_mostratipopacto1|o29_sequencial|o29_descricao','Pesquisa',true);
  }else{
     if(document.form1.o31_tipopacto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipopacto','func_tipopacto.php?pesquisa_chave='+document.form1.o31_tipopacto.value+'&funcao_js=parent.js_mostratipopacto','Pesquisa',false);
     }else{
       document.form1.o29_descricao.value = ''; 
     }
  }
}
function js_mostratipopacto(chave,erro){
  document.form1.o29_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o31_tipopacto.focus(); 
    document.form1.o31_tipopacto.value = ''; 
  }
}
function js_mostratipopacto1(chave1,chave2){
  document.form1.o31_tipopacto.value = chave1;
  document.form1.o29_descricao.value = chave2;
  db_iframe_tipopacto.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_categoriapacto','func_categoriapacto.php?funcao_js=parent.js_preenchepesquisa|o31_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_categoriapacto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>