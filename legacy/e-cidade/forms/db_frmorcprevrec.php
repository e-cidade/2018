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
$clorcprevrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o57_descr");
$clrotulo->label("o70_anousu");

$opcaoreceita = 1;
$querystring  = "";
if(isset($receita)){
	$result_receita = $clorcreceita->sql_record($clorcreceita->sql_query(db_getsession("DB_anousu"),$receita,"o57_descr"));
	if($clorcreceita->numrows > 0){
		db_fieldsmemory($result_receita, 0);
	}
	if(isset($bimestre)){
		$querystring = "?receita=".$receita."&bimestre=".$bimestre;
	}
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" valign="center">
  <tr>
    <td nowrap title="<?=@$To34_codrec?>" align="right">
      <?
      db_ancora(@$Lo34_codrec,"js_pesquisao34_codrec(true);",$opcaoreceita);
      ?>
    </td>
    <td nowrap title="<?=@$To34_codrec?>" align="left">
      <?
      db_input('o34_codrec',6,$Io34_codrec,true,'text',$opcaoreceita,'onChange="js_pesquisao34_codrec(false);"','receita');
      db_input('o57_descr',50,$Io57_descr,true,'text',3);
      ?>
    </td>
    <td nowrap title="Selecione o bimestre" align="right">
      <?
      db_ancora("<b>Bimestre:</b>","",3);
      ?>
    </td>
    <td nowrap title="Selecione o bimestre" align="left">
      <?
      $arr_bimestres = Array("1"=>"1º - Primeiro","2"=>"2º - Segundo","3"=>"3º - Terceiro","4"=>"4º - Quarto","5"=>"5º - Quinto","6"=>"6º - Sexto");
      db_select("bimestre", $arr_bimestres, true, $opcaoreceita);
      ?>
    </td>
    <td><input name="previsao" type="button" id="previsao" value="Mostrar previsão" onclick="js_pesquisaiframe();" onblur="js_setarfoco(1);"></td>
  </tr>
</table>
<table border="0" width="100%">
  <tr>
    <td align="center">
      <iframe name="iframe_previsao" id="iframe_previsao" marginwidth="0" marginheight="0" frameborder="0" src="orc1_orcprevrec003.php<?=$querystring?>" width="97%" height="150"></iframe>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick='return js_verificacampos();'>
<!--
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" onblur="js_setarfoco(3);">
-->
</form>
<script>
function js_verificacampos(){
  if(document.form1.receita.value == ""){
    alert("Informe a receita");
    document.form1.receita.focus();
  }else if(!iframe_previsao.document.form1.saldo_a_arrecadar){
    alert("Selecione o bimestre e clique em 'Mostrar previsão' para informar os valores");
  }else{
    valores = "";

    valores = iframe_previsao.document.form1.o34_valor_Jan.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Fev.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Mar.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Abr.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Mai.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Jun.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Jul.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Ago.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Set.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Out.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Nov.value;
    valores+= "," + iframe_previsao.document.form1.o34_valor_Dez.value;

		obj=document.createElement('input');
	  obj.setAttribute('name','valores');
	  obj.setAttribute('type','hidden');
	  obj.setAttribute('value',valores);
	  document.form1.appendChild(obj);
    return true;
  }
  return false;
}
function js_pesquisaiframe(){
  if(document.form1.receita.value != ""){
    iframe_previsao.location.href = 'orc1_orcprevrec003.php?receita='+document.form1.receita.value+'&bimestre='+document.form1.bimestre.value;
  }else{
    alert("Informe a receita.");
    document.form1.receita.focus();
  }
}
function js_pesquisao34_codrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraorcreceita1|o70_codrec|o57_descr','Pesquisa',true);
  }else{
     if(document.form1.receita.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.receita.value+'&funcao_js=parent.js_mostraorcreceita','Pesquisa',false);
     }else{
       iframe_previsao.location.href = 'orc1_orcprevrec003.php';
       document.form1.o57_descr.value = ''; 
     }
  }
}
function js_mostraorcreceita(chave,erro){
  iframe_previsao.location.href = 'orc1_orcprevrec003.php';
  document.form1.o57_descr.value = chave; 
  if(erro==true){ 
    document.form1.receita.focus(); 
    document.form1.receita.value = ''; 
  }
}
function js_mostraorcreceita1(chave1,chave2){
  iframe_previsao.location.href = 'orc1_orcprevrec003.php';
  document.form1.receita.value = chave1;
  document.form1.o57_descr.value = chave2;
  db_iframe_orcreceita.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcprevrec','func_orcprevrec.php?funcao_js=parent.js_preenchepesquisa|o34_codrec','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcprevrec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
var time;
function js_setarfoco(opcao){
  if(iframe_previsao.js_setarfoco){
    if(opcao == 1){
      time = setInterval(js_seleciona_campo_iframe,10);
    }else if(opcao == 2){
      time = setInterval(js_seleciona_campo_inclui,10);
    }else{
      time = setInterval(js_seleciona_campo_receit,10);
    }
  }else{
    if(opcao == 1){
      time = setInterval(js_seleciona_campo_inclui,10);
    }else{
      time = setInterval(js_seleciona_campo_receit,10);
    }
  }
}
function js_seleciona_campo_iframe(){
  iframe_previsao.js_setarfoco();
  clearInterval(time);
}
function js_seleciona_campo_inclui(){
  document.getElementById("db_opcao").focus();
  clearInterval(time);
}
function js_seleciona_campo_receit(){
  document.form1.receita.select();
  document.form1.receita.focus();
  clearInterval(time);
}
</script>