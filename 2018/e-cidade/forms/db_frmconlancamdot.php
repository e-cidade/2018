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
$clconlancamdot->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c70_anousu");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc73_codlan?>">
       <?
       db_ancora(@$Lc73_codlan,"js_pesquisac73_codlan(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c73_codlan',8,$Ic73_codlan,true,'text',$db_opcao," onchange='js_pesquisac73_codlan(false);'")
?>
       <?
db_input('c70_anousu',4,$Ic70_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc73_anousu?>">
       <?
       db_ancora(@$Lc73_anousu,"js_pesquisac73_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$c73_anousu = db_getsession('DB_anousu');
db_input('c73_anousu',4,$Ic73_anousu,true,'text',3," onchange='js_pesquisac73_anousu(false);'")
?>
       <?
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc73_coddot?>">
       <?
       db_ancora(@$Lc73_coddot,"js_pesquisac73_coddot(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c73_coddot',6,$Ic73_coddot,true,'text',$db_opcao," onchange='js_pesquisac73_coddot(false);'")
?>
       <?
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
db_input('o58_orgao',2,$Io58_orgao,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac73_codlan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?funcao_js=parent.js_mostraconlancam1|c70_codlan|c70_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancam','func_conlancam.php?pesquisa_chave='+document.form1.c73_codlan.value+'&funcao_js=parent.js_mostraconlancam','Pesquisa',false);
  }
}
function js_mostraconlancam(chave,erro){
  document.form1.c70_anousu.value = chave; 
  if(erro==true){ 
    document.form1.c73_codlan.focus(); 
    document.form1.c73_codlan.value = ''; 
  }
}
function js_mostraconlancam1(chave1,chave2){
  document.form1.c73_codlan.value = chave1;
  document.form1.c70_anousu.value = chave2;
  db_iframe_conlancam.hide();
}
function js_pesquisac73_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_anousu|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.c73_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.c73_coddot.focus(); 
    document.form1.c73_coddot.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.c73_coddot.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisac73_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.c73_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.c73_coddot.focus(); 
    document.form1.c73_coddot.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.c73_coddot.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisac73_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_anousu|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.c73_anousu.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.c73_anousu.focus(); 
    document.form1.c73_anousu.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.c73_anousu.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisac73_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|o58_orgao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.c73_anousu.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  document.form1.o58_orgao.value = chave; 
  if(erro==true){ 
    document.form1.c73_anousu.focus(); 
    document.form1.c73_anousu.value = ''; 
  }
}
function js_mostraorcdotacao1(chave1,chave2){
  document.form1.c73_anousu.value = chave1;
  document.form1.o58_orgao.value = chave2;
  db_iframe_orcdotacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdot','func_conlancamdot.php?funcao_js=parent.js_preenchepesquisa|c73_codlan','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conlancamdot.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>