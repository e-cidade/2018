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

//MODULO: recursos humanos
$clvinculos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh04_descr");
$clrotulo->label("h08_numero");
$clrotulo->label("h08_dtlanc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('h11_codigo',6,$Ih11_codigo,true,'hidden',$db_opcao,"")
?>
  <tr>
    <td nowrap title="<?=@$Th11_regime?>">
       <?=@$Lh11_regime?>
    </td>
    <td> 
<?
$result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file());
db_selectrecord("h11_regime", $result_regime, true, $db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_tipo?>">
       <?=@$Lh11_tipo?>
    </td>
    <td> 
<?
$arr_tipo = array("A"=>"Avanço","G"=>"Gratificação");
db_select("h11_tipo", $arr_tipo, true, $db_opcao,"onchange='js_limpaleis();'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_funcao?>">
       <?
       db_ancora(@$Lh11_funcao,"js_pesquisah11_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h11_funcao',6,$Ih11_funcao,true,'text',$db_opcao," onchange='js_pesquisah11_funcao(false);'")
?>
       <?
db_input('rh04_descr',30,$Irh04_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_lei1?>">
       <?
       db_ancora(@$Lh11_lei1,"js_pesquisah11_lei1(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h11_lei1',6,$Ih11_lei1,true,'hidden',3,"")
?>
       <?
db_input('h08_numero',6,$Ih08_numero,true,'text',$db_opcao,"onchange='js_pesquisah11_lei1(false)'")
       ?>
       <?
db_inputdata("h08_dtlanc", @$h08_dtlanc_dia, @$h08_dtlanc_mes, @$h08_dtlanc_ano, true, 'text', 3);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_lei2?>">
       <?
       db_ancora(@$Lh11_lei2,"js_pesquisah11_lei2(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h11_lei2',6,$Ih11_lei2,true,'hidden',3)
?>
       <?
db_input('h08_numero',6,$Ih08_numero,true,'text',$db_opcao,"onchange='js_pesquisah11_lei2(false)'","h08_numero2")
       ?>
       <?
db_inputdata("h08_dtlanc", @$h08_dtlanc2_dia, @$h08_dtlanc2_mes, @$h08_dtlanc2_ano, true, 'text', 3,"","h08_dtlanc2");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_lei3?>">
       <?
       db_ancora(@$Lh11_lei3,"js_pesquisah11_lei3(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h11_lei3',6,$Ih11_lei3,true,'hidden',3)
?>
       <?
db_input('h08_numero',6,$Ih08_numero,true,'text',$db_opcao,"onchange='js_pesquisah11_lei3(false)'","h08_numero3")
       ?>
       <?
db_inputdata("h08_dtlanc", @$h08_dtlanc3_dia, @$h08_dtlanc3_mes, @$h08_dtlanc3_ano, true, 'text', 3,"","h08_dtlanc3");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_lei4?>">
       <?
       db_ancora(@$Lh11_lei4,"js_pesquisah11_lei4(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h11_lei4',6,$Ih11_lei4,true,'hidden',3)
?>
       <?
db_input('h08_numero',6,$Ih08_numero,true,'text',$db_opcao,"onchange='js_pesquisah11_lei4(false)'","h08_numero4")
       ?>
       <?
db_inputdata("h08_dtlanc", @$h08_dtlanc4_dia, @$h08_dtlanc4_mes, @$h08_dtlanc4_ano, true, 'text', 3,"","h08_dtlanc4");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_lei5?>">
       <?
       db_ancora(@$Lh11_lei5,"js_pesquisah11_lei5(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h11_lei5',6,$Ih11_lei5,true,'hidden',3)
?>
       <?
db_input('h08_numero',6,$Ih08_numero,true,'text',$db_opcao,"onchange='js_pesquisah11_lei5(false)'","h08_numero5")
       ?>
       <?
db_inputdata("h08_dtlanc", @$h08_dtlanc5_dia, @$h08_dtlanc5_mes, @$h08_dtlanc5_ano, true, 'text', 3,"","h08_dtlanc5");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th11_cert01?>">
       <?=@$Lh11_cert01?>
    </td>
    <td> 
<?
db_textarea('h11_cert01',5,38,$Ih11_cert01,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificaigualdadelei();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_limpaleis(){
  for(var i=1; i<6; i++){
    eval("document.form1.h08_numero" + (i == 1 ? "" : i) + ".value = '';");
    eval("js_pesquisah11_lei" + i + "(false);");
  }
}
function js_verificaigualdadelei(){
  // Nenhum dos campos LEI pode ter valor repetido...
  // Rotina para esta verificação.
  var arr_vals = new Array();
  for(var i=1; i<6; i++){
    eval("campo = document.form1.h08_numero" + (i == 1 ? "" : i) + ".value;");
    if(js_search_in_array(arr_vals, campo) && campo != ""){
      alert("Lei " + campo + " já foi informada,verifique!");
      eval("document.form1.h08_numero" + i + ".select();");
      eval("document.form1.h08_numero" + i + ".focus();");
      return false;
    }
    arr_vals[i] = campo;
  }
  return true;
}
function js_pesquisah11_lei5(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?funcao_js=parent.js_mostraleis51|h08_codlei|h08_numero|h08_dtlanc&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',true);
  }else{
    if(document.form1.h08_numero5.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?pesquisa_chave_numero='+document.form1.h08_numero5.value+'&funcao_js=parent.js_mostraleis5&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',false);
    }else{
      document.form1.h11_lei5.value = '';
      document.form1.h08_dtlanc5_dia.value = "";
      document.form1.h08_dtlanc5_mes.value = "";
      document.form1.h08_dtlanc5_ano.value = "";
      document.form1.h08_dtlanc5.value = "";
    }
  }
}
function js_mostraleis5(chave,chave2,erro){
  if(erro==true){
    alert(chave);
    document.form1.h08_numero5.value = '';
    document.form1.h08_numero5.focus(); 
    js_pesquisah11_lei5(false);
  }else{
    arr_data = chave2.split("-");
    document.form1.h11_lei5.value = chave; 
    document.form1.h08_dtlanc5_dia.value = arr_data[2];
    document.form1.h08_dtlanc5_mes.value = arr_data[1];
    document.form1.h08_dtlanc5_ano.value = arr_data[0];
    document.form1.h08_dtlanc5.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  }
}
function js_mostraleis51(chave1,chave2,chave3){
  document.form1.h11_lei5.value = chave1;
  document.form1.h08_numero5.value = chave2;
  arr_data = chave3.split("-");
  document.form1.h08_dtlanc5_dia.value = arr_data[2];
  document.form1.h08_dtlanc5_mes.value = arr_data[1];
  document.form1.h08_dtlanc5_ano.value = arr_data[0];
  document.form1.h08_dtlanc5.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  db_iframe_leis.hide();
}
function js_pesquisah11_lei4(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?funcao_js=parent.js_mostraleis41|h08_codlei|h08_numero|h08_dtlanc&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',true);
  }else{
    if(document.form1.h08_numero4.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?pesquisa_chave_numero='+document.form1.h08_numero4.value+'&funcao_js=parent.js_mostraleis4&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',false);
    }else{
      document.form1.h11_lei4.value = ''; 
      document.form1.h08_dtlanc4_dia.value = "";
      document.form1.h08_dtlanc4_mes.value = "";
      document.form1.h08_dtlanc4_ano.value = "";
      document.form1.h08_dtlanc4.value = "";
    }
  }
}
function js_mostraleis4(chave,chave2,erro){
  if(erro==true){ 
    alert(chave);
    document.form1.h08_numero4.value = '';
    document.form1.h08_numero4.focus(); 
    js_pesquisah11_lei4(false);
  }else{
    arr_data = chave2.split("-");
    document.form1.h11_lei4.value = chave; 
    document.form1.h08_dtlanc4_dia.value = arr_data[2];
    document.form1.h08_dtlanc4_mes.value = arr_data[1];
    document.form1.h08_dtlanc4_ano.value = arr_data[0];
    document.form1.h08_dtlanc4.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  }
}
function js_mostraleis41(chave1,chave2,chave3){
  document.form1.h11_lei4.value = chave1;
  document.form1.h08_numero4.value = chave2;
  arr_data = chave3.split("-");
  document.form1.h08_dtlanc4_dia.value = arr_data[2];
  document.form1.h08_dtlanc4_mes.value = arr_data[1];
  document.form1.h08_dtlanc4_ano.value = arr_data[0];
  document.form1.h08_dtlanc4.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  db_iframe_leis.hide();
}
function js_pesquisah11_lei3(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?funcao_js=parent.js_mostraleis31|h08_codlei|h08_numero|h08_dtlanc&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',true);
  }else{
    if(document.form1.h08_numero3.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?pesquisa_chave_numero='+document.form1.h08_numero3.value+'&funcao_js=parent.js_mostraleis3&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',false);
    }else{
      document.form1.h11_lei3.value = ''; 
      document.form1.h08_dtlanc3_dia.value = "";
      document.form1.h08_dtlanc3_mes.value = "";
      document.form1.h08_dtlanc3_ano.value = "";
      document.form1.h08_dtlanc3.value = "";
    }
  }
}
function js_mostraleis3(chave,chave2,erro){
  if(erro==true){ 
    alert(chave);
    document.form1.h08_numero3.value = ''; 
    document.form1.h08_numero3.focus(); 
    js_pesquisah11_lei3(false);
  }else{
    arr_data = chave2.split("-");
    document.form1.h11_lei3.value = chave; 
    document.form1.h08_dtlanc3_dia.value = arr_data[2];
    document.form1.h08_dtlanc3_mes.value = arr_data[1];
    document.form1.h08_dtlanc3_ano.value = arr_data[0];
    document.form1.h08_dtlanc3.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  }
}
function js_mostraleis31(chave1,chave2,chave3){
  document.form1.h11_lei3.value = chave1;
  document.form1.h08_numero3.value = chave2;
  arr_data = chave3.split("-");
  document.form1.h08_dtlanc3_dia.value = arr_data[2];
  document.form1.h08_dtlanc3_mes.value = arr_data[1];
  document.form1.h08_dtlanc3_ano.value = arr_data[0];
  document.form1.h08_dtlanc3.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  db_iframe_leis.hide();
}
function js_pesquisah11_lei2(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?funcao_js=parent.js_mostraleis21|h08_codlei|h08_numero|h08_dtlanc&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',true);
  }else{
    if(document.form1.h08_numero2.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?pesquisa_chave_numero='+document.form1.h08_numero2.value+'&funcao_js=parent.js_mostraleis2&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',false);
    }else{
      document.form1.h11_lei2.value = ''; 
      document.form1.h08_dtlanc2_dia.value = "";
      document.form1.h08_dtlanc2_mes.value = "";
      document.form1.h08_dtlanc2_ano.value = "";
      document.form1.h08_dtlanc2.value = "";
    }
  }
}
function js_mostraleis2(chave,chave2,erro){
  if(erro==true){ 
    alert(chave);
    document.form1.h08_numero2.value = '';
    document.form1.h08_numero2.focus(); 
    js_pesquisah11_lei2(false);
  }else{
    arr_data = chave2.split("-");
    document.form1.h11_lei2.value = chave; 
    document.form1.h08_dtlanc2_dia.value = arr_data[2];
    document.form1.h08_dtlanc2_mes.value = arr_data[1];
    document.form1.h08_dtlanc2_ano.value = arr_data[0];
	  document.form1.h08_dtlanc2.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  }
}
function js_mostraleis21(chave1,chave2,chave3){
  document.form1.h11_lei2.value = chave1;
  document.form1.h08_numero2.value = chave2;
  arr_data = chave3.split("-");
  document.form1.h08_dtlanc2_dia.value = arr_data[2];
  document.form1.h08_dtlanc2_mes.value = arr_data[1];
  document.form1.h08_dtlanc2_ano.value = arr_data[0];
  document.form1.h08_dtlanc2.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  db_iframe_leis.hide();
}
function js_pesquisah11_lei1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?funcao_js=parent.js_mostraleis1|h08_codlei|h08_numero|h08_dtlanc&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',true);
  }else{
    if(document.form1.h08_numero.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?pesquisa_chave_numero='+document.form1.h08_numero.value+'&funcao_js=parent.js_mostraleis&chave_tipo='+document.form1.h11_tipo.value,'Pesquisa',false);
    }else{
      document.form1.h11_lei1.value = ''; 
      document.form1.h08_dtlanc_dia.value = "";
      document.form1.h08_dtlanc_mes.value = "";
      document.form1.h08_dtlanc_ano.value = "";
      document.form1.h08_dtlanc.value = "";
    }
  }
}
function js_mostraleis(chave,chave2,erro){
  if(erro==true){ 
    alert(chave);
    document.form1.h08_numero.value = ''; 
    document.form1.h08_numero.focus(); 
    js_pesquisah11_lei1(false);
  }else{
    arr_data = chave2.split("-");
    document.form1.h11_lei1.value = chave; 
    document.form1.h08_dtlanc_dia.value = arr_data[2];
    document.form1.h08_dtlanc_mes.value = arr_data[1];
    document.form1.h08_dtlanc_ano.value = arr_data[0];
	  document.form1.h08_dtlanc.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  }
}
function js_mostraleis1(chave1,chave2,chave3){
  document.form1.h11_lei1.value = chave1;
  document.form1.h08_numero.value = chave2;
  arr_data = chave3.split("-");
  document.form1.h08_dtlanc_dia.value = arr_data[2];
  document.form1.h08_dtlanc_mes.value = arr_data[1];
  document.form1.h08_dtlanc_ano.value = arr_data[0];
	document.form1.h08_dtlanc.value = arr_data[2]+'/'+arr_data[1]+'/'+arr_data[0];
  db_iframe_leis.hide();
}
function js_pesquisah11_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhcargo','func_rhcargo.php?funcao_js=parent.js_mostrarhcargo1|rh04_codigo|rh04_descr','Pesquisa',true);
  }else{
    if(document.form1.h11_funcao.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_rhcargo','func_rhcargo.php?pesquisa_chave='+document.form1.h11_funcao.value+'&funcao_js=parent.js_mostrarhcargo','Pesquisa',false);
    }else{
      document.form1.rh04_descr.value = ''; 
    }
  }
}
function js_mostrarhcargo(chave,erro){
  document.form1.rh04_descr.value = chave; 
  if(erro==true){ 
    document.form1.h11_funcao.focus(); 
    document.form1.h11_funcao.value = ''; 
  }
}
function js_mostrarhcargo1(chave1,chave2){
  document.form1.h11_funcao.value = chave1;
  document.form1.rh04_descr.value = chave2;
  db_iframe_rhcargo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vinculos','func_vinculos.php?funcao_js=parent.js_preenchepesquisa|h11_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vinculos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>