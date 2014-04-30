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

//MODULO: escola
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clserieregimemat->rotulo->label();
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $result = $clserieregimemat->sql_record($clserieregimemat->sql_query("","*",""," ed223_i_codigo = $ed223_i_codigo"));
 db_fieldsmemory($result,0);
}elseif((isset($opcao) && $opcao=="excluir") || (isset($db_opcao) && $db_opcao==3 && !isset($excluir))){
 $db_botao1 = true;
 $db_opcao = 3;
 $result = $clserieregimemat->sql_record($clserieregimemat->sql_query("","*",""," ed223_i_codigo = $ed223_i_codigo"));
 db_fieldsmemory($result,0);
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted223_i_codigo?>">
   <?=@$Led223_i_codigo?>
  </td>
  <td>
   <?db_input('ed223_i_codigo',20,$Ied223_i_codigo,true,'text',3,"")?>
   </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted223_i_serie?>">
   <?db_ancora(@$Led223_i_serie,"",3);?>
  </td>
  <td>
   <?db_input('ed223_i_serie',20,$Ied223_i_serie,true,'text',3,"")?>
   <?db_input('ed11_c_descr',20,@$Ied11_c_descr,true,'text',3,'')?>
   </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted223_i_regimemat?>">
   <?db_ancora(@$Led223_i_regimemat,"js_pesquisaed223_i_regimemat(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed223_i_regimemat',20,$Ied223_i_regimemat,true,'text',$db_opcao," onchange='js_pesquisaed223_i_regimemat(false);'")?>
   <?db_input('ed218_c_nome',30,@$Ied218_c_nome,true,'text',3,'')?>
   <?db_input('ed218_c_divisao',1,@$Ied218_c_divisao,true,'hidden',3,'')?>
  </td>
 </tr>
 <?
 if(isset($ed218_c_divisao)&&$ed218_c_divisao=="S"){
  $visible = "visible";
 }else{
  $visible = "hidden";
 }
 ?>
 <tbody id="divisao" style="visibility:<?=$visible?>">
 <tr>
  <td nowrap title="<?=@$Ted223_i_regimematdiv?>">
   <?db_ancora(@$Led223_i_regimematdiv,"js_pesquisaed223_i_regimematdiv(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed223_i_regimematdiv',20,$Ied223_i_regimematdiv,true,'text',$db_opcao," onchange='js_pesquisaed223_i_regimematdiv(false);'")?>
   <?db_input('ed219_c_nome',30,@$Ied219_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 </tbody>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top" width="70%">
  <?
  $campos = "ed223_i_codigo,
             ed218_c_nome as ed223_i_regimemat,
             ed219_c_nome as ed223_i_regimematdiv";
  $chavepri= array("ed223_i_codigo"=>@$ed223_i_codigo);
  $cliframe_alterar_excluir->chavepri=$chavepri;
  $cliframe_alterar_excluir->sql = $clserieregimemat->sql_query("",$campos,"ed223_i_ordenacao,ed223_i_codigo"," ed223_i_serie = $ed223_i_serie");
  $cliframe_alterar_excluir->campos  ="ed223_i_codigo,ed223_i_regimemat,ed223_i_regimematdiv";
  $cliframe_alterar_excluir->legenda="Registros";
  $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
  $cliframe_alterar_excluir->textocabec ="#DEB887";
  $cliframe_alterar_excluir->textocorpo ="#444444";
  $cliframe_alterar_excluir->fundocabec ="#444444";
  $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
  $cliframe_alterar_excluir->iframe_height ="150";
  $cliframe_alterar_excluir->iframe_width ="100%";
  $cliframe_alterar_excluir->tamfontecabec = 9;
  $cliframe_alterar_excluir->tamfontecorpo = 9;
  $cliframe_alterar_excluir->formulario = false;
  $cliframe_alterar_excluir->opcoes = 3;
  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisaed223_i_regimemat(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_regimemat','func_regimemat.php?funcao_js=parent.js_mostraregimemat1|ed218_i_codigo|ed218_c_nome|ed218_c_divisao','Pesquisa de Regimes de Matrícula',true);
 }else{
  if(document.form1.ed223_i_regimemat.value != ''){
   js_OpenJanelaIframe('','db_iframe_regimemat','func_regimemat.php?pesquisa_chave='+document.form1.ed223_i_regimemat.value+'&funcao_js=parent.js_mostraregimemat','Pesquisa',false);
  }else{
   document.form1.ed218_c_nome.value = '';
   document.form1.ed218_c_divisao.value = '';
   document.form1.ed223_i_regimematdiv.value = '';
   document.form1.ed219_c_nome.value = '';
   document.getElementById("divisao").style.visibility = "hidden";
  }
 }
}
function js_mostraregimemat(chave1,chave2,erro){
 document.form1.ed218_c_nome.value = chave1;
 document.form1.ed218_c_divisao.value = chave2;
 document.form1.ed223_i_regimematdiv.value = '';
 document.form1.ed219_c_nome.value = '';
 if(erro==true){
  document.form1.ed223_i_regimemat.focus();
  document.form1.ed223_i_regimemat.value = '';
  document.getElementById("divisao").style.visibility = "hidden";
 }else{
  if(chave2=="S"){
   document.getElementById("divisao").style.visibility = "visible";
  }else{
   document.getElementById("divisao").style.visibility = "hidden";
  }
 }
}
function js_mostraregimemat1(chave1,chave2,chave3){
  document.form1.ed223_i_regimemat.value = chave1;
  document.form1.ed218_c_nome.value = chave2;
  document.form1.ed218_c_divisao.value = chave3;
  document.form1.ed223_i_regimematdiv.value = '';
  document.form1.ed219_c_nome.value = '';
  if(chave3=="S"){
   document.getElementById("divisao").style.visibility = "visible";
  }else{
   document.getElementById("divisao").style.visibility = "hidden";
  }
  db_iframe_regimemat.hide();
}
function js_pesquisaed223_i_regimematdiv(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_regimematdiv','func_regimematdiv.php?regime='+document.form1.ed223_i_regimemat.value+'&funcao_js=parent.js_mostraregimematdiv1|ed219_i_codigo|ed219_c_nome','Pesquisa de Divisão do Regime de Matrícula',true);
 }else{
  if(document.form1.ed223_i_regimematdiv.value != ''){
   js_OpenJanelaIframe('','db_iframe_regimematdiv','func_regimematdiv.php?regime='+document.form1.ed223_i_regimemat.value+'&pesquisa_chave='+document.form1.ed223_i_regimematdiv.value+'&funcao_js=parent.js_mostraregimematdiv','Pesquisa',false);
  }else{
   document.form1.ed219_c_nome.value = '';
  }
 }
}
function js_mostraregimematdiv(chave,erro){
 document.form1.ed219_c_nome.value = chave;
 if(erro==true){
  document.form1.ed223_i_regimematdiv.focus();
  document.form1.ed223_i_regimematdiv.value = '';
 }
}
function js_mostraregimematdiv1(chave1,chave2){
 document.form1.ed223_i_regimematdiv.value = chave1;
 document.form1.ed219_c_nome.value = chave2;
 db_iframe_regimematdiv.hide();
}
function js_valida(){
 if(document.form1.ed223_i_regimemat.value!="" && document.form1.ed218_c_divisao.value=="S" && document.form1.ed223_i_regimematdiv.value==""){
  alert("Informe a Divisão do Regime de Matrícula");
  return false;
 }
 return true;
}
</script>