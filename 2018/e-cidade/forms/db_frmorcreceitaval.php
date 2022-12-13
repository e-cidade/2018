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
$clorcreceitaval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o70_codfon");
$clrotulo->label("o70_codfon");
$clrotulo->label("o70_codfon");
$clrotulo->label("o70_codfon");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To71_anousu?>">
       <?
       db_ancora(@$Lo71_anousu,"js_pesquisao71_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$o71_anousu = db_getsession('DB_anousu');
db_input('o71_anousu',4,$Io71_anousu,true,'text',$db_opcao," onchange='js_pesquisao71_anousu(false);'")
?>
       <?
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To71_codrec?>">
       <?
       db_ancora(@$Lo71_codrec,"js_pesquisao71_codrec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o71_codrec',6,$Io71_codrec,true,'text',$db_opcao," onchange='js_pesquisao71_codrec(false);'")
?>
       <?
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
db_input('o70_codfon',6,$Io70_codfon,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To71_mes?>">
       <?=@$Lo71_mes?>
    </td>
    <td> 
<?
db_input('o71_mes',2,$Io71_mes,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To71_valor?>">
       <?=@$Lo71_valor?>
    </td>
    <td> 
<?
db_input('o71_valor',15,$Io71_valor,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao71_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_anousu|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o71_anousu.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.o71_anousu.focus(); 
    document.form1.o71_anousu.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o71_anousu.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao71_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o71_anousu.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.o71_anousu.focus(); 
    document.form1.o71_anousu.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o71_anousu.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao71_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_anousu|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o71_codrec.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.o71_codrec.focus(); 
    document.form1.o71_codrec.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o71_codrec.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisao71_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o70_codfon','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o71_codrec.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
  }
}
function js_mostraorcreceita(chave,erro){
  document.form1.o70_codfon.value = chave; 
  if(erro==true){ 
    document.form1.o71_codrec.focus(); 
    document.form1.o71_codrec.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  document.form1.o71_codrec.value = chave1;
  document.form1.o70_codfon.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcreceitaval','func_orcreceitaval.php?funcao_js=parent.js_preenchepesquisa|o71_anousu|1|2','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_orcreceitaval.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>