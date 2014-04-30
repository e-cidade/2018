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
$clempauthist->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e54_anousu");
$clrotulo->label("e40_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te57_autori?>">
       <?
       db_ancora(@$Le57_autori,"js_pesquisae57_autori(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e57_autori',6,$Ie57_autori,true,'text',$db_opcao," onchange='js_pesquisae57_autori(false);'")
?>
       <?
db_input('e54_anousu',4,$Ie54_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te57_codhist?>">
       <?
       db_ancora(@$Le57_codhist,"js_pesquisae57_codhist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e57_codhist',6,$Ie57_codhist,true,'text',$db_opcao," onchange='js_pesquisae57_codhist(false);'")
?>
       <?
db_input('e40_descr',60,$Ie40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae57_autori(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.js_mostraempautoriza1|e54_autori|e54_anousu','Pesquisa',true);
  }else{
     if(document.form1.e57_autori.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empautoriza','func_empautoriza.php?pesquisa_chave='+document.form1.e57_autori.value+'&funcao_js=parent.js_mostraempautoriza','Pesquisa',false);
     }else{
       document.form1.e54_anousu.value = ''; 
     }
  }
}
function js_mostraempautoriza(chave,erro){
  document.form1.e54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.e57_autori.focus(); 
    document.form1.e57_autori.value = ''; 
  }
}
function js_mostraempautoriza1(chave1,chave2){
  document.form1.e57_autori.value = chave1;
  document.form1.e54_anousu.value = chave2;
  db_iframe_empautoriza.hide();
}
function js_pesquisae57_codhist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emphist','func_emphist.php?funcao_js=parent.js_mostraemphist1|e40_codhist|e40_descr','Pesquisa',true);
  }else{
     if(document.form1.e57_codhist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emphist','func_emphist.php?pesquisa_chave='+document.form1.e57_codhist.value+'&funcao_js=parent.js_mostraemphist','Pesquisa',false);
     }else{
       document.form1.e40_descr.value = ''; 
     }
  }
}
function js_mostraemphist(chave,erro){
  document.form1.e40_descr.value = chave; 
  if(erro==true){ 
    document.form1.e57_codhist.focus(); 
    document.form1.e57_codhist.value = ''; 
  }
}
function js_mostraemphist1(chave1,chave2){
  document.form1.e57_codhist.value = chave1;
  document.form1.e40_descr.value = chave2;
  db_iframe_emphist.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empauthist','func_empauthist.php?funcao_js=parent.js_preenchepesquisa|e57_autori','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empauthist.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>