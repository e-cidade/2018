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

//MODULO: empenho
$clempresto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e90_descr");
$clrotulo->label("o15_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te91_anousu?>">
       <?=@$Le91_anousu?>
    </td>
    <td> 
<?
$e91_anousu = db_getsession('DB_anousu');
db_input('e91_anousu',4,$Ie91_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_numemp?>">
       <?=@$Le91_numemp?>
    </td>
    <td> 
<?
db_input('e91_numemp',15,$Ie91_numemp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_vlremp?>">
       <?=@$Le91_vlremp?>
    </td>
    <td> 
<?
db_input('e91_vlremp',15,$Ie91_vlremp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_vlranu?>">
       <?=@$Le91_vlranu?>
    </td>
    <td> 
<?
db_input('e91_vlranu',15,$Ie91_vlranu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_vlrliq?>">
       <?=@$Le91_vlrliq?>
    </td>
    <td> 
<?
db_input('e91_vlrliq',15,$Ie91_vlrliq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_vlrpag?>">
       <?=@$Le91_vlrpag?>
    </td>
    <td> 
<?
db_input('e91_vlrpag',15,$Ie91_vlrpag,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_elemento?>">
       <?=@$Le91_elemento?>
    </td>
    <td> 
<?
db_input('e91_elemento',20,$Ie91_elemento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_recurso?>">
       <?
       db_ancora(@$Le91_recurso,"js_pesquisae91_recurso(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e91_recurso',4,$Ie91_recurso,true,'text',$db_opcao," onchange='js_pesquisae91_recurso(false);'")
?>
       <?
db_input('o15_descr',60,$Io15_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_codtipo?>">
       <?
       db_ancora(@$Le91_codtipo,"js_pesquisae91_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e91_codtipo',15,$Ie91_codtipo,true,'text',$db_opcao," onchange='js_pesquisae91_codtipo(false);'")
?>
       <?
db_input('e90_descr',70,$Ie90_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te91_rpcorreto?>">
       <?=@$Le91_rpcorreto?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('e91_rpcorreto',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae91_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emprestotipo','func_emprestotipo.php?funcao_js=parent.js_mostraemprestotipo1|e90_codigo|e90_descr','Pesquisa',true);
  }else{
     if(document.form1.e91_codtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emprestotipo','func_emprestotipo.php?pesquisa_chave='+document.form1.e91_codtipo.value+'&funcao_js=parent.js_mostraemprestotipo','Pesquisa',false);
     }else{
       document.form1.e90_descr.value = ''; 
     }
  }
}
function js_mostraemprestotipo(chave,erro){
  document.form1.e90_descr.value = chave; 
  if(erro==true){ 
    document.form1.e91_codtipo.focus(); 
    document.form1.e91_codtipo.value = ''; 
  }
}
function js_mostraemprestotipo1(chave1,chave2){
  document.form1.e91_codtipo.value = chave1;
  document.form1.e90_descr.value = chave2;
  db_iframe_emprestotipo.hide();
}
function js_pesquisae91_recurso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.e91_recurso.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.e91_recurso.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.e91_recurso.focus(); 
    document.form1.e91_recurso.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.e91_recurso.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empresto','func_empresto.php?funcao_js=parent.js_preenchepesquisa|e91_anousu|e91_numemp','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_empresto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>