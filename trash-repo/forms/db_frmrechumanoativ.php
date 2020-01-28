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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrechumanoativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed75_i_codigo");
$clrotulo->label("ed01_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
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
  <td nowrap>
  </td>
  <td>
   <?db_input('ed22_i_codigo',15,@$Ied22_i_codigo,true,'hidden',3,"")?>
   <?db_input('ed22_i_rechumanoescola',15,@$Ied22_i_rechumanoescola,true,'hidden',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$ed20_i_tiposervidor=='1'?'Matrícula':'CGM'?>">
   <b><?=@$ed20_i_tiposervidor=='1'?'Matrícula:':'CGM:'?></b>
  </td>
  <td>
   <?db_input('identificacao',15,@$identificacao,true,'text',3,"")?>
   <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted22_i_atividade?>">
   <?db_ancora(@$Led22_i_atividade,"js_pesquisaed22_i_atividade(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed22_i_atividade',15,$Ied22_i_atividade,true,'text',$db_opcao," onchange='js_pesquisaed22_i_atividade(false);'")?>
   <?db_input('ed01_c_descr',40,@$Ied01_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <?$visible = (isset($ed22_i_atolegal)&&$ed22_i_atolegal!="")||(isset($ed01_c_exigeato)&&$ed01_c_exigeato=="S")?"visible":"hidden"?>
 <tr id="atolegal" style="visibility:<?=$visible?>;">
  <td nowrap title="<?=@$Ted22_i_atolegal?>">
   <?db_ancora(@$Led22_i_atolegal,"js_pesquisaed22_i_atolegal(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed22_i_atolegal',15,$Ied22_i_atolegal,true,'text',$db_opcao," onchange='js_pesquisaed22_i_atolegal(false);'")?>
   <?db_input('ed05_c_finalidade',40,@$Ied05_c_finalidade,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted22_i_horasmanha?>">
   <?=@$Led22_i_horasmanha?>
  </td>
  <td>
   <?db_input('ed22_i_horasmanha',10,$Ied22_i_horasmanha,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted22_i_horastarde?>">
   <?=@$Led22_i_horastarde?>
  </td>
  <td>
   <?db_input('ed22_i_horastarde',10,$Ied22_i_horastarde,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted22_i_horasnoite?>">
   <?=@$Led22_i_horasnoite?>
  </td>
  <td>
   <?db_input('ed22_i_horasnoite',10,$Ied22_i_horasnoite,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $escola = db_getsession("DB_coddepto");
   $chavepri= array("ed22_i_codigo"=>@$ed22_i_codigo,
                    "ed22_i_rechumanoescola"=>@$ed22_i_rechumanoescola,
                    "ed22_i_atividade"=>@$ed22_i_atividade,
                    "ed01_c_descr"=>@$ed01_c_descr,
                    "ed01_c_exigeato"=>@$ed01_c_exigeato,
                    "ed22_i_atolegal"=>@$ed22_i_atolegal,
                    "ed05_c_finalidade"=>@$ed05_c_finalidade,
                    "ed22_i_horasmanha"=>@$ed22_i_horasmanha,
                    "ed22_i_horastarde"=>@$ed22_i_horastarde,
                    "ed22_i_horasnoite"=>@$ed22_i_horasnoite
                   );
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clrechumanoativ->sql_query("","*",""," ed22_i_rechumanoescola = $ed22_i_rechumanoescola");
   $cliframe_alterar_excluir->campos  ="ed22_i_codigo,ed01_c_descr,ed22_i_horasmanha,ed22_i_horastarde,ed22_i_horasnoite,ed05_c_finalidade";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="170";
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
function js_pesquisaed22_i_atividade(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_atividaderh','func_atividaderh.php?atividades=<?=$ativ_cad?>&funcao_js=parent.js_mostraatividaderh1|ed01_i_codigo|ed01_c_descr|ed01_c_exigeato','Pesquisa de Atividades',true);
 }else{
  if(document.form1.ed22_i_atividade.value != ''){
   js_OpenJanelaIframe('','db_iframe_atividaderh','func_atividaderh.php?atividades=<?=$ativ_cad?>&pesquisa_chave='+document.form1.ed22_i_atividade.value+'&funcao_js=parent.js_mostraatividaderh','Pesquisa',false);
  }else{
   document.form1.ed01_c_descr.value = '';
   document.getElementById("atolegal").style.visibility = "hidden";
   document.form1.ed22_i_atolegal.value = "";
   document.form1.ed05_c_finalidade.value = "";
  }
 }
}
function js_mostraatividaderh(chave1,chave2,erro){
 document.form1.ed01_c_descr.value = chave1;
 if(erro==true){
  document.form1.ed22_i_atividade.focus();
  document.form1.ed22_i_atividade.value = '';
  document.getElementById("atolegal").style.visibility = "hidden";
  document.form1.ed22_i_atolegal.value = "";
  document.form1.ed05_c_finalidade.value = "";
 }else{
  if(chave2=="S"){
   document.getElementById("atolegal").style.visibility = "visible";
  }else{
   document.getElementById("atolegal").style.visibility = "hidden";
   document.form1.ed22_i_atolegal.value = "";
   document.form1.ed05_c_finalidade.value = "";
  }
 }
}
function js_mostraatividaderh1(chave1,chave2,chave3){
 document.form1.ed22_i_atividade.value = chave1;
 document.form1.ed01_c_descr.value = chave2;
 if(chave3=="S"){
  document.getElementById("atolegal").style.visibility = "visible";
 }else{
  document.getElementById("atolegal").style.visibility = "hidden";
  document.form1.ed22_i_atolegal.value = "";
  document.form1.ed05_c_finalidade.value = "";
 }
 db_iframe_atividaderh.hide();
}

function js_pesquisaed22_i_atolegal(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?funcao_js=parent.js_mostraatolegal1|ed05_i_codigo|ed05_c_finalidade','Pesquisa de Ato Legal',true);
 }else{
  if(document.form1.ed22_i_atolegal.value != ''){
   js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?pesquisa_chave='+document.form1.ed22_i_atolegal.value+'&funcao_js=parent.js_mostraatolegal','Pesquisa',false);
  }else{
   document.form1.ed05_c_finalidade.value = '';
  }
 }
}
function js_mostraatolegal(chave,erro){
 document.form1.ed05_c_finalidade.value = chave;
 if(erro==true){
  document.form1.ed22_i_atolegal.focus();
  document.form1.ed22_i_atolegal.value = '';
 }
}
function js_mostraatolegal1(chave1,chave2){
 document.form1.ed22_i_atolegal.value = chave1;
 document.form1.ed05_c_finalidade.value = chave2;
 db_iframe_atolegal.hide();
}
</script>