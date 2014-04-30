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
$clorcdotacaoval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("c53_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To59_anousu?>">
       <?
       db_ancora(@$Lo59_anousu,"js_pesquisao59_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$o59_anousu = db_getsession('DB_anousu');
db_input('o59_anousu',4,$Io59_anousu,true,'text',$db_opcao," onchange='js_pesquisao59_anousu(false);'")
?>
       <?
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To59_coddot?>">
       <?
       db_ancora(@$Lo59_coddot,"js_pesquisao59_coddot(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o59_coddot',6,$Io59_coddot,true,'text',$db_opcao," onchange='js_pesquisao59_coddot(false);'")
?>
       <?
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To59_mes?>">
       <?=@$Lo59_mes?>
    </td>
    <td> 
<?
db_input('o59_mes',2,$Io59_mes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To59_coddoc?>">
       <?
       db_ancora(@$Lo59_coddoc,"js_pesquisao59_coddoc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o59_coddoc',4,$Io59_coddoc,true,'text',$db_opcao," onchange='js_pesquisao59_coddoc(false);'")
?>
       <?
db_input('c53_descr',50,$Ic53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To59_valor?>">
       <?=@$Lo59_valor?>
    </td>
    <td> 
<?
db_input('o59_valor',15,$Io59_valor,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao59_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_anousu|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o59_anousu.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.o59_anousu.focus(); 
    document.form1.o59_anousu.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.o59_anousu.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisao59_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o59_anousu.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.o59_anousu.focus(); 
    document.form1.o59_anousu.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.o59_anousu.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisao59_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_anousu|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o59_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.o59_coddot.focus(); 
    document.form1.o59_coddot.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.o59_coddot.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisao59_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o59_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.o59_coddot.focus(); 
    document.form1.o59_coddot.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.o59_coddot.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisao59_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.o59_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false);
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.o59_coddoc.focus(); 
    document.form1.o59_coddoc.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.o59_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacaoval','func_orcdotacaoval.php?funcao_js=parent.js_preenchepesquisa|o59_anousu|1|2|3','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3){
  db_iframe_orcdotacaoval.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3";
  }
  ?>
}
</script>