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
$clcustoplanoanalitica->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc01_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc04_sequencial?>">
       <?=@$Lcc04_sequencial?>
    </td>
    <td> 
<?
db_input('cc04_sequencial',10,$Icc04_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc04_custoplano?>">
       <?
       db_ancora(@$Lcc04_custoplano,"js_pesquisacc04_custoplano(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc04_custoplano',10,$Icc04_custoplano,true,'text',$db_opcao," onchange='js_pesquisacc04_custoplano(false);'")
?>
       <?
db_input('cc01_descricao',50,$Icc01_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacc04_custoplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_custoplano','func_custoplano.php?funcao_js=parent.js_mostracustoplano1|cc01_sequencial|cc01_descricao','Pesquisa',true);
  }else{
     if(document.form1.cc04_custoplano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_custoplano','func_custoplano.php?pesquisa_chave='+document.form1.cc04_custoplano.value+'&funcao_js=parent.js_mostracustoplano','Pesquisa',false);
     }else{
       document.form1.cc01_descricao.value = ''; 
     }
  }
}
function js_mostracustoplano(chave,erro){
  document.form1.cc01_descricao.value = chave; 
  if(erro==true){ 
    document.form1.cc04_custoplano.focus(); 
    document.form1.cc04_custoplano.value = ''; 
  }
}
function js_mostracustoplano1(chave1,chave2){
  document.form1.cc04_custoplano.value = chave1;
  document.form1.cc01_descricao.value = chave2;
  db_iframe_custoplano.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_custoplanoanalitica','func_custoplanoanalitica.php?funcao_js=parent.js_preenchepesquisa|cc04_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_custoplanoanalitica.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>