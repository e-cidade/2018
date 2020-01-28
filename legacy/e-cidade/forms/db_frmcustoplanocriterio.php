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

//MODULO: custos
$clcustoplanocriterio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc08_instit");
$clrotulo->label("cc04_custoplano");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc07_sequencial?>">
       <?=@$Lcc07_sequencial?>
    </td>
    <td> 
<?
db_input('cc07_sequencial',10,$Icc07_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_custocriteriorateio?>">
       <?
       db_ancora(@$Lcc07_custocriteriorateio,"js_pesquisacc07_custocriteriorateio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc07_custocriteriorateio',10,$Icc07_custocriteriorateio,true,'text',$db_opcao," onchange='js_pesquisacc07_custocriteriorateio(false);'")
?>
       <?
db_input('cc08_instit',10,$Icc08_instit,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_custoplanoanalitica?>">
       <?
       db_ancora(@$Lcc07_custoplanoanalitica,"js_pesquisacc07_custoplanoanalitica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc07_custoplanoanalitica',10,$Icc07_custoplanoanalitica,true,'text',$db_opcao," onchange='js_pesquisacc07_custoplanoanalitica(false);'")
?>
       <?
db_input('cc04_custoplano',10,$Icc04_custoplano,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_quantidade?>">
       <?=@$Lcc07_quantidade?>
    </td>
    <td> 
<?
db_input('cc07_quantidade',10,$Icc07_quantidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_percentual?>">
       <?=@$Lcc07_percentual?>
    </td>
    <td> 
<?
db_input('cc07_percentual',10,$Icc07_percentual,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacc07_custocriteriorateio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custocriteriorateio','func_custocriteriorateio.php?funcao_js=parent.js_mostracustocriteriorateio1|cc08_sequencial|cc08_instit','Pesquisa',true);
  }else{
     if(document.form1.cc07_custocriteriorateio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custocriteriorateio','func_custocriteriorateio.php?pesquisa_chave='+document.form1.cc07_custocriteriorateio.value+'&funcao_js=parent.js_mostracustocriteriorateio','Pesquisa',false);
     }else{
       document.form1.cc08_instit.value = ''; 
     }
  }
}
function js_mostracustocriteriorateio(chave,erro){
  document.form1.cc08_instit.value = chave; 
  if(erro==true){ 
    document.form1.cc07_custocriteriorateio.focus(); 
    document.form1.cc07_custocriteriorateio.value = ''; 
  }
}
function js_mostracustocriteriorateio1(chave1,chave2){
  document.form1.cc07_custocriteriorateio.value = chave1;
  document.form1.cc08_instit.value = chave2;
  db_iframe_custocriteriorateio.hide();
}
function js_pesquisacc07_custoplanoanalitica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanalitica','func_custoplanoanalitica.php?funcao_js=parent.js_mostracustoplanoanalitica1|cc04_sequencial|cc04_custoplano','Pesquisa',true);
  }else{
     if(document.form1.cc07_custoplanoanalitica.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanalitica','func_custoplanoanalitica.php?pesquisa_chave='+document.form1.cc07_custoplanoanalitica.value+'&funcao_js=parent.js_mostracustoplanoanalitica','Pesquisa',false);
     }else{
       document.form1.cc04_custoplano.value = ''; 
     }
  }
}
function js_mostracustoplanoanalitica(chave,erro){
  document.form1.cc04_custoplano.value = chave; 
  if(erro==true){ 
    document.form1.cc07_custoplanoanalitica.focus(); 
    document.form1.cc07_custoplanoanalitica.value = ''; 
  }
}
function js_mostracustoplanoanalitica1(chave1,chave2){
  document.form1.cc07_custoplanoanalitica.value = chave1;
  document.form1.cc04_custoplano.value = chave2;
  db_iframe_custoplanoanalitica.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_custoplanocriterio','func_custoplanocriterio.php?funcao_js=parent.js_preenchepesquisa|cc07_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_custoplanocriterio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>