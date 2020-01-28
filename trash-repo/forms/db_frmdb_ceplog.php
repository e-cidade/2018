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
$cldb_ceplog->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db10_munic");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb11_codlog?>">
       <?=@$Ldb11_codlog?>
    </td>
    <td> 
<?
db_input('db11_codlog',10,$Idb11_codlog,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb11_codigo?>">
       <?
       db_ancora(@$Ldb11_codigo,"js_pesquisadb11_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db11_codigo',10,$Idb11_codigo,true,'text',$db_opcao," onchange='js_pesquisadb11_codigo(false);'")
?>
       <?
db_input('db10_munic',40,$Idb10_munic,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb11_tipo?>">
       <?=@$Ldb11_tipo?>
    </td>
    <td> 
<?
db_input('db11_tipo',12,$Idb11_tipo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb11_logradouro?>">
       <?=@$Ldb11_logradouro?>
    </td>
    <td> 
<?
db_input('db11_logradouro',60,$Idb11_logradouro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb11_logsemacento?>">
       <?=@$Ldb11_logsemacento?>
    </td>
    <td> 
<?
db_input('db11_logsemacento',60,$Idb11_logsemacento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb11_bairro?>">
       <?=@$Ldb11_bairro?>
    </td>
    <td> 
<?
db_input('db11_bairro',40,$Idb11_bairro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb11_cep?>">
       <?=@$Ldb11_cep?>
    </td>
    <td> 
<?
db_input('db11_cep',8,$Idb11_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb11_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_cepmunic','func_db_cepmunic.php?funcao_js=parent.js_mostradb_cepmunic1|db10_codigo|db10_munic','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_db_cepmunic','func_db_cepmunic.php?pesquisa_chave='+document.form1.db11_codigo.value+'&funcao_js=parent.js_mostradb_cepmunic','Pesquisa',false);
  }
}
function js_mostradb_cepmunic(chave,erro){
  document.form1.db10_munic.value = chave; 
  if(erro==true){ 
    document.form1.db11_codigo.focus(); 
    document.form1.db11_codigo.value = ''; 
  }
}
function js_mostradb_cepmunic1(chave1,chave2){
  document.form1.db11_codigo.value = chave1;
  document.form1.db10_munic.value = chave2;
  db_iframe_db_cepmunic.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_ceplog','func_db_ceplog.php?funcao_js=parent.js_preenchepesquisa|db11_codlog','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_ceplog.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>