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

//MODULO: contrib
$clparcontrib->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("k01_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("k51_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Td12_receita?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Ld12_receita,"js_pesquisad12_receita(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d12_receita',4,$Id12_receita,true,'text',$db_opcao," onchange='js_pesquisad12_receita(false);'")
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td12_numtot?>">
       <?=@$Ld12_numtot?>
    </td>
    <td> 
<?
db_input('d12_numtot',4,$Id12_numtot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td12_perunica?>">
       <?=@$Ld12_perunica?>
    </td>
    <td> 
<?
db_input('d12_perunica',15,$Id12_perunica,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td12_perc?>">
       <?=@$Ld12_perc?>
    </td>
    <td> 
<?
db_input('d12_perc',15,$Id12_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td12_hist?>">
       <?
       db_ancora(@$Ld12_hist,"js_pesquisad12_hist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d12_hist',4,$Id12_hist,true,'text',$db_opcao," onchange='js_pesquisad12_hist(false);'")
?>
       <?
db_input('k01_descr',20,$Ik01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td12_notitipo?>">
       <?
       db_ancora(@$Ld12_notitipo,"js_pesquisad12_notitipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d12_notitipo',8,$Id12_notitipo,true,'text',$db_opcao," onchange='js_pesquisad12_notitipo(false);'")
?>
       <?
db_input('k51_descr',40,$Ik51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td12_tipo?>">
       <?
       db_ancora(@$Ld12_tipo,"js_pesquisad12_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d12_tipo',4,$Id12_tipo,true,'text',$db_opcao," onchange='js_pesquisad12_tipo(false);'")
?>
       <?
db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisad12_receita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.d12_receita.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.d12_receita.focus(); 
    document.form1.d12_receita.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.d12_receita.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisad12_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.d12_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.d12_hist.focus(); 
    document.form1.d12_hist.value = ''; 
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.d12_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}
function js_pesquisad12_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.d12_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.d12_tipo.focus(); 
    document.form1.d12_tipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.d12_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisad12_notitipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_notitipo','func_notitipo.php?funcao_js=parent.js_mostranotitipo1|k51_procede|k51_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_notitipo','func_notitipo.php?pesquisa_chave='+document.form1.d12_notitipo.value+'&funcao_js=parent.js_mostranotitipo','Pesquisa',false);
  }
}
function js_mostranotitipo(chave,erro){
  document.form1.k51_descr.value = chave; 
  if(erro==true){ 
    document.form1.d12_notitipo.focus(); 
    document.form1.d12_notitipo.value = ''; 
  }
}
function js_mostranotitipo1(chave1,chave2){
  document.form1.d12_notitipo.value = chave1;
  document.form1.k51_descr.value = chave2;
  db_iframe_notitipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_parcontrib','func_parcontrib.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_parcontrib.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>