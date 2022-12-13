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
$clcustoplanotipoconta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc04_custoplano");
$clrotulo->label("cc02_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc03_sequencial?>">
       <?=@$Lcc03_sequencial?>
    </td>
    <td> 
<?
db_input('cc03_sequencial',10,$Icc03_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc03_custoplanoanalitica?>">
       <?
       db_ancora(@$Lcc03_custoplanoanalitica,"js_pesquisacc03_custoplanoanalitica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc03_custoplanoanalitica',10,$Icc03_custoplanoanalitica,true,'text',$db_opcao," onchange='js_pesquisacc03_custoplanoanalitica(false);'")
?>
       <?
db_input('cc04_custoplano',10,$Icc04_custoplano,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc03_custotipoconta?>">
       <?
       db_ancora(@$Lcc03_custotipoconta,"js_pesquisacc03_custotipoconta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc03_custotipoconta',10,$Icc03_custotipoconta,true,'text',$db_opcao," onchange='js_pesquisacc03_custotipoconta(false);'")
?>
       <?
db_input('cc02_descricao',50,$Icc02_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacc03_custoplanoanalitica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanalitica','func_custoplanoanalitica.php?funcao_js=parent.js_mostracustoplanoanalitica1|cc04_sequencial|cc04_custoplano','Pesquisa',true);
  }else{
     if(document.form1.cc03_custoplanoanalitica.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanalitica','func_custoplanoanalitica.php?pesquisa_chave='+document.form1.cc03_custoplanoanalitica.value+'&funcao_js=parent.js_mostracustoplanoanalitica','Pesquisa',false);
     }else{
       document.form1.cc04_custoplano.value = ''; 
     }
  }
}
function js_mostracustoplanoanalitica(chave,erro){
  document.form1.cc04_custoplano.value = chave; 
  if(erro==true){ 
    document.form1.cc03_custoplanoanalitica.focus(); 
    document.form1.cc03_custoplanoanalitica.value = ''; 
  }
}
function js_mostracustoplanoanalitica1(chave1,chave2){
  document.form1.cc03_custoplanoanalitica.value = chave1;
  document.form1.cc04_custoplano.value = chave2;
  db_iframe_custoplanoanalitica.hide();
}
function js_pesquisacc03_custotipoconta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custotipoconta','func_custotipoconta.php?funcao_js=parent.js_mostracustotipoconta1|cc02_sequencial|cc02_descricao','Pesquisa',true);
  }else{
     if(document.form1.cc03_custotipoconta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custotipoconta','func_custotipoconta.php?pesquisa_chave='+document.form1.cc03_custotipoconta.value+'&funcao_js=parent.js_mostracustotipoconta','Pesquisa',false);
     }else{
       document.form1.cc02_descricao.value = ''; 
     }
  }
}
function js_mostracustotipoconta(chave,erro){
  document.form1.cc02_descricao.value = chave; 
  if(erro==true){ 
    document.form1.cc03_custotipoconta.focus(); 
    document.form1.cc03_custotipoconta.value = ''; 
  }
}
function js_mostracustotipoconta1(chave1,chave2){
  document.form1.cc03_custotipoconta.value = chave1;
  document.form1.cc02_descricao.value = chave2;
  db_iframe_custotipoconta.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_custoplanotipoconta','func_custoplanotipoconta.php?funcao_js=parent.js_preenchepesquisa|cc03_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_custoplanotipoconta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>