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

//MODULO: protocolo
$cldb_cepmunic->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db12_uf");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb10_codigo?>">
       <?=@$Ldb10_codigo?>
    </td>
    <td> 
<?
db_input('db10_codigo',10,$Idb10_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb10_munic?>">
       <?=@$Ldb10_munic?>
    </td>
    <td> 
<?
db_input('db10_munic',60,$Idb10_munic,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb10_cep?>">
       <?=@$Ldb10_cep?>
    </td>
    <td> 
<?
db_input('db10_cep',8,$Idb10_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb10_uf?>">
       <?
       db_ancora(@$Ldb10_uf,"js_pesquisadb10_uf(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db10_uf',2,$Idb10_uf,true,'text',$db_opcao," onchange='js_pesquisadb10_uf(false);'")
?>
       <?
db_input('db12_uf',2,$Idb12_uf,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb10_codibge?>">
       <?=@$Ldb10_codibge?>
    </td>
    <td> 
<?
db_input('db10_codibge',10,$Idb10_codibge,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb10_uf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_uf','func_db_uf.php?funcao_js=parent.js_mostradb_uf1|db12_codigo|db12_uf','Pesquisa',true);
  }else{
     if(document.form1.db10_uf.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_uf','func_db_uf.php?pesquisa_chave='+document.form1.db10_uf.value+'&funcao_js=parent.js_mostradb_uf','Pesquisa',false);
     }else{
       document.form1.db12_uf.value = ''; 
     }
  }
}
function js_mostradb_uf(chave,erro){
  document.form1.db12_uf.value = chave; 
  if(erro==true){ 
    document.form1.db10_uf.focus(); 
    document.form1.db10_uf.value = ''; 
  }
}
function js_mostradb_uf1(chave1,chave2){
  document.form1.db10_uf.value = chave1;
  document.form1.db12_uf.value = chave2;
  db_iframe_db_uf.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_cepmunic','func_db_cepmunic.php?funcao_js=parent.js_preenchepesquisa|db10_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_cepmunic.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>