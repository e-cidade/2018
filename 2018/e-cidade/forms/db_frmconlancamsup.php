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

//MODULO: contabilidade
$clconlancamsup->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c70_anousu");
$clrotulo->label("o46_codlei");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc79_codlan?>">
       <?
       db_ancora(@$Lc79_codlan,"js_pesquisac79_codlan(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c79_codlan',8,$Ic79_codlan,true,'text',$db_opcao," onchange='js_pesquisac79_codlan(false);'")
?>
       <?
db_input('c70_anousu',4,$Ic70_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc79_codsup?>">
       <?
       db_ancora(@$Lc79_codsup,"js_pesquisac79_codsup(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c79_codsup',4,$Ic79_codsup,true,'text',$db_opcao," onchange='js_pesquisac79_codsup(false);'")
?>
       <?
db_input('o46_codlei',4,$Io46_codlei,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac79_codlan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?funcao_js=parent.js_mostraconlancam1|c70_codlan|c70_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?pesquisa_chave='+document.form1.c79_codlan.value+'&funcao_js=parent.js_mostraconlancam','Pesquisa',false);
  }
}
function js_mostraconlancam(chave,erro){
  document.form1.c70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.c79_codlan.focus(); 
    document.form1.c79_codlan.value = ''; 
  }
}
function js_mostraconlancam1(chave1,chave2){
  document.form1.c79_codlan.value = chave1;
  document.form1.c70_anousu.value = chave2;
  db_iframe_conlancam.hide();
}
function js_pesquisac79_codsup(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplem','func_orcsuplem.php?funcao_js=parent.js_mostraorcsuplem1|o46_codsup|o46_codlei','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplem','func_orcsuplem.php?pesquisa_chave='+document.form1.c79_codsup.value+'&funcao_js=parent.js_mostraorcsuplem','Pesquisa',false);
  }
}
function js_mostraorcsuplem(chave,erro){
  document.form1.o46_codlei.value = chave; 
  if(erro==true){ 
    document.form1.c79_codsup.focus(); 
    document.form1.c79_codsup.value = ''; 
  }
}
function js_mostraorcsuplem1(chave1,chave2){
  document.form1.c79_codsup.value = chave1;
  document.form1.o46_codlei.value = chave2;
  db_iframe_orcsuplem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conlancamsup','func_conlancamsup.php?funcao_js=parent.js_preenchepesquisa|c79_codlan|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_conlancamsup.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>