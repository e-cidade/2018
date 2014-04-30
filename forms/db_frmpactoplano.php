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
$clpactoplano->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o16_convenio");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To74_sequencial?>">
       <?=@$Lo74_sequencial?>
    </td>
    <td> 
<?
db_input('o74_sequencial',10,$Io74_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To74_orctiporecconvenio?>">
       <?
       db_ancora(@$Lo74_orctiporecconvenio,"js_pesquisao74_orctiporecconvenio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o74_orctiporecconvenio',10,$Io74_orctiporecconvenio,true,'text',$db_opcao," onchange='js_pesquisao74_orctiporecconvenio(false);'")
?>
       <?
db_input('o16_convenio',48,$Io16_convenio,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To74_descricao?>">
       <?=@$Lo74_descricao?>
    </td>
    <td> 
<?
db_input('o74_descricao',62,$Io74_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To74_obs?>">
       <?=@$Lo74_obs?>
    </td>
    <td> 
<?
db_textarea('o74_obs',5,60,$Io74_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao74_orctiporecconvenio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporecconvenio','func_orctiporecconvenio.php?funcao_js=parent.js_mostraorctiporecconvenio1|o16_sequencial|o16_convenio','Pesquisa',true);
  }else{
     if(document.form1.o74_orctiporecconvenio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporecconvenio','func_orctiporecconvenio.php?pesquisa_chave='+document.form1.o74_orctiporecconvenio.value+'&funcao_js=parent.js_mostraorctiporecconvenio','Pesquisa',false);
     }else{
       document.form1.o16_convenio.value = ''; 
     }
  }
}
function js_mostraorctiporecconvenio(chave,erro){
  document.form1.o16_convenio.value = chave; 
  if(erro==true){ 
    document.form1.o74_orctiporecconvenio.focus(); 
    document.form1.o74_orctiporecconvenio.value = ''; 
  }
}
function js_mostraorctiporecconvenio1(chave1,chave2){
  document.form1.o74_orctiporecconvenio.value = chave1;
  document.form1.o16_convenio.value = chave2;
  db_iframe_orctiporecconvenio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pactoplano','func_pactoplano.php?funcao_js=parent.js_preenchepesquisa|o74_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pactoplano.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>