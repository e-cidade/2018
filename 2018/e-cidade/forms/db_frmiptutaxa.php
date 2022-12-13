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

//MODULO: cadastro
$cliptutaxa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("nomefuncao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj19_anousu?>">
       <?=@$Lj19_anousu?>
    </td>
    <td> 
<?
$j19_anousu = db_getsession('DB_anousu');
db_input('j19_anousu',4,$Ij19_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj19_receit?>">
       <?
       db_ancora(@$Lj19_receit,"js_pesquisaj19_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j19_receit',10,$Ij19_receit,true,'text',$db_opcao," onchange='js_pesquisaj19_receit(false);'")
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj19_valor?>">
       <?=@$Lj19_valor?>
    </td>
    <td> 
<?
db_input('j19_valor',15,$Ij19_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj19_funcao?>">
       <?
       db_ancora(@$Lj19_funcao,"js_pesquisaj19_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j19_funcao',5,$Ij19_funcao,true,'text',$db_opcao," onchange='js_pesquisaj19_funcao(false);'")
?>
       <?
db_input('nomefuncao',100,$Inomefuncao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj19_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.j19_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.j19_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.j19_receit.focus(); 
    document.form1.j19_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.j19_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisaj19_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_mostradb_sysfuncoes1|codfuncao|nomefuncao','Pesquisa',true);
  }else{
     if(document.form1.j19_funcao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?pesquisa_chave='+document.form1.j19_funcao.value+'&funcao_js=parent.js_mostradb_sysfuncoes','Pesquisa',false);
     }else{
       document.form1.nomefuncao.value = ''; 
     }
  }
}
function js_mostradb_sysfuncoes(chave,erro){
  document.form1.nomefuncao.value = chave; 
  if(erro==true){ 
    document.form1.j19_funcao.focus(); 
    document.form1.j19_funcao.value = ''; 
  }
}
function js_mostradb_sysfuncoes1(chave1,chave2){
  document.form1.j19_funcao.value = chave1;
  document.form1.nomefuncao.value = chave2;
  db_iframe_db_sysfuncoes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_iptutaxa','func_iptutaxa.php?funcao_js=parent.js_preenchepesquisa|j19_anousu|j19_receit','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_iptutaxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>