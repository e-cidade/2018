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

//MODULO: Educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsala->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed14_i_codigo");
$clrotulo->label("ed14_c_aula");
$clrotulo->label("ed233_f_medidaaluno");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 if(isset($db_opcaol)){
  $db_opcao=33;
 }
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
  <td nowrap title="<?=@$Ted16_i_codigo?>">
   <?=@$Led16_i_codigo?>
  </td>
  <td colspan="2">
   <?db_input('ed16_i_codigo',15,$Ied16_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted16_i_escola?>">
   <?db_ancora(@$Led16_i_escola,"js_pesquisaed16_i_escola(true);",3);?>
  </td>
  <td colspan="2">
   <?db_input('ed16_i_escola',15,@$Ied16_i_escola,true,'text',3,'')?>
   <?db_input('descrdepto',50,@$Idescrdepto,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted16_i_tiposala?>">
   <?db_ancora(@$Led16_i_tiposala,"js_pesquisaed16_i_tiposala(true);",$db_opcao);?>
  </td>
  <td colspan="2">
   <?db_input('ed16_i_tiposala',15,$Ied16_i_tiposala,true,'text',$db_opcao," onchange='js_pesquisaed16_i_tiposala(false);'")?>
   <?db_input('ed14_c_descr',20,@$Ied14_c_descr,true,'text',3,'')?>
   <?=@$Led14_c_aula?>
   <?db_input('ed14_c_aula',3,@$Ied14_c_aula=="S"?"SIM":"NÃO",true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted16_c_descr?>">
   <?=@$Led16_c_descr?>
  </td>
  <td>
   <?
   db_input('ed16_c_descr',20,$Ied16_c_descr,true,'text',$db_opcao,"");
   $visible = isset($ed14_c_aula)&&$ed14_c_aula=="S"?"visible":"hidden";
   ?>
  </td>
  <td rowspan="4">
   <fieldset id="capacidade" style="visibility:<?=$visible?>"><legend><b>Cálculo da capacidade</b></legend>
   <?=@$Led16_f_metragem?>
   <?db_input('ed16_f_metragem',10,$Ied16_f_metragem,true,'text',$db_opcao," onchange='js_calculo(this.value);'")?><br>
   <?=@$Led233_f_medidaaluno?>
   <?db_input('ed233_f_medidaaluno',10,$Ied233_f_medidaaluno,true,'text',3,"")?> <?=isset($ed233_f_medidaaluno)&&$ed233_f_medidaaluno!=""?"":" (Procedimentos -> Parâmetros)"?><br>
   <?=@$Led16_i_calculoaluno?>
   <?db_input('ed16_i_calculoaluno',10,$Ied16_i_calculoaluno,true,'text',3,"")?>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Led16_i_capacidade?>
  </td>
  <td>
   <?db_input('ed16_i_capacidade',10,$Ied16_i_capacidade,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Led16_c_pertence?>
  </td>
  <td>
   <?
   $x = array('S'=>'SIM','N'=>'NÃO');
   db_select('ed16_c_pertence',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed16_i_escola" => @$ed16_i_escola, "ed16_i_codigo"=>@$ed16_i_codigo,"ed16_i_tiposala"=>@$ed16_i_tiposala,"ed16_c_descr"=>@$ed16_c_descr,"ed14_c_descr"=>@$ed14_c_descr,"ed16_i_capacidade"=>@$ed16_i_capacidade,"ed16_c_pertence"=>@$ed16_c_pertence,"ed14_c_aula"=>@$ed14_c_aula,"ed16_f_metragem"=>@$ed16_f_metragem,"ed16_i_calculoaluno"=>@$ed16_i_calculoaluno);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clsala->sql_query("","*","ed16_c_descr"," ed16_i_escola = $ed16_i_escola");
   $cliframe_alterar_excluir->campos  ="ed16_i_codigo,ed16_c_descr,ed16_i_capacidade,ed16_f_metragem,ed16_i_calculoaluno,ed16_c_pertence,ed14_c_descr";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed16_i_tiposala(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_tiposala','func_tiposala.php?funcao_js=parent.js_mostratiposala1|ed14_i_codigo|ed14_c_descr|ed14_c_aula','Pesquisa Tipos de Sala',true);
 }else{
  if(document.form1.ed16_i_tiposala.value != ''){
   js_OpenJanelaIframe('','db_iframe_tiposala','func_tiposala.php?pesquisa_chave='+document.form1.ed16_i_tiposala.value+'&funcao_js=parent.js_mostratiposala','Pesquisa Tipos de Sala',false);
  }else{
   document.form1.ed14_c_descr.value = '';
   document.form1.ed14_c_aula.value = '';
   document.form1.ed16_f_metragem.value = '';
   document.form1.ed16_i_calculoaluno.value = '';
   document.form1.ed16_i_capacidade.value = "";
   document.getElementById("capacidade").style.visibility = "hidden";
  }
 }
}
function js_mostratiposala(chave,chave1,erro){
 document.form1.ed14_c_descr.value = chave;
 document.form1.ed14_c_aula.value = chave1;
 if(erro==true){
  document.form1.ed16_i_tiposala.focus();
  document.form1.ed16_i_tiposala.value = '';
 }
 if(chave1=="S"){
  document.getElementById("capacidade").style.visibility = "visible";
 }else{
  document.getElementById("capacidade").style.visibility = "hidden";
 }
 document.form1.ed16_f_metragem.value = '';
 document.form1.ed16_i_calculoaluno.value = '';
 document.form1.ed16_i_capacidade.value = "";
}
function js_mostratiposala1(chave1,chave2,chave3){
 document.form1.ed16_i_tiposala.value = chave1;
 document.form1.ed14_c_descr.value = chave2;
 document.form1.ed14_c_aula.value = chave3;
 if(chave3=="S"){
  document.getElementById("capacidade").style.visibility = "visible";
 }else{
  document.getElementById("capacidade").style.visibility = "hidden";
 }
 document.form1.ed16_f_metragem.value = '';
 document.form1.ed16_i_calculoaluno.value = '';
 document.form1.ed16_i_capacidade.value = "";
 db_iframe_tiposala.hide();
}
function js_calculo(valor){
 if(valor!=""){
  if(document.form1.ed233_f_medidaaluno.value==""){
   alert("Medida em m2 por aluno em sala de aula não informada!");
   document.form1.ed233_f_medidaaluno.style.backgroundColor='#99A9AE';
   document.form1.ed233_f_medidaaluno.focus();
   return false;
  }else{
   if(parseFloat(document.form1.ed16_f_metragem.value)<parseFloat(document.form1.ed233_f_medidaaluno.value)){
    alert("Medida da Sala em m2 de ser maior que Medida em m2 por aluno em sala de aula!");
    document.form1.ed16_f_metragem.style.backgroundColor='#99A9AE';
    document.form1.ed16_f_metragem.value = "";
    document.form1.ed16_f_metragem.focus();
    return false;
   }else{
    calculo = parseFloat(document.form1.ed16_f_metragem.value)/parseFloat(document.form1.ed233_f_medidaaluno.value);
    document.form1.ed16_i_calculoaluno.value = Math.floor(calculo);
    document.form1.ed16_i_capacidade.value = Math.floor(calculo);
   }
  }
 }else{
  document.form1.ed16_i_calculoaluno.value = "";
  document.form1.ed16_i_capacidade.value = "";
 }
}
function js_valida(){
 if(document.form1.ed14_c_aula.value=="S" && document.form1.ed16_i_capacidade.value==""){
  alert("Campo Capacidade deve ser informado\nquando o Tipo de Dependência for Sala de Aula!");
  document.form1.ed16_i_capacidade.style.backgroundColor='#99A9AE';
  document.form1.ed16_i_capacidade.focus();
  return false;
 }
 if(document.form1.ed16_i_capacidade.value==0){
  alert("Campo Capacidade, quando informado, deve ser diferente de zero!");
  document.form1.ed16_i_capacidade.style.backgroundColor='#99A9AE';
  document.form1.ed16_i_capacidade.focus();
  document.form1.ed16_i_capacidade.value = "";
  return false;
 }
 return true;
}
</script>