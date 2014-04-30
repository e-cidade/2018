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
$clescoladiretor->rotulo->label();
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $ed254_d_dataini_dia = substr($ed254_d_dataini,0,2);
 $ed254_d_dataini_mes = substr($ed254_d_dataini,3,2);
 $ed254_d_dataini_ano = substr($ed254_d_dataini,6,4);
 $ed254_d_datafim_dia = substr($ed254_d_datafim,0,2);
 $ed254_d_datafim_mes = substr($ed254_d_datafim,3,2);
 $ed254_d_datafim_ano = substr($ed254_d_datafim,6,4);
 $ed254_d_datacad_dia = substr($ed254_d_datacad,0,2);
 $ed254_d_datacad_mes = substr($ed254_d_datacad,3,2);
 $ed254_d_datacad_ano = substr($ed254_d_datacad,6,4);
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $ed254_d_dataini_dia = substr($ed254_d_dataini,0,2);
 $ed254_d_dataini_mes = substr($ed254_d_dataini,3,2);
 $ed254_d_dataini_ano = substr($ed254_d_dataini,6,4);
 $ed254_d_datafim_dia = substr($ed254_d_datafim,0,2);
 $ed254_d_datafim_mes = substr($ed254_d_datafim,3,2);
 $ed254_d_datafim_ano = substr($ed254_d_datafim,6,4);
 $ed254_d_datacad_dia = substr($ed254_d_datacad,0,2);
 $ed254_d_datacad_mes = substr($ed254_d_datacad,3,2);
 $ed254_d_datacad_ano = substr($ed254_d_datacad,6,4);
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
  <td nowrap title="<?=@$Ted254_i_codigo?>">
   <?=@$Led254_i_codigo?>
  </td>
  <td>
   <?db_input('ed254_i_codigo',20,$Ied254_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_i_escola?>">
   <?db_ancora(@$Led254_i_escola,"",3);?>
  </td>
  <td>
   <?db_input('ed254_i_escola',20,$Ied254_i_escola,true,'text',3,"")?>
   <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_i_rechumano?>">
   <?db_ancora(@$Led254_i_rechumano,"js_pesquisaed254_i_rechumano(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed254_i_rechumano',20,$Ied254_i_rechumano,true,'hidden',3,'')?>
   <?db_input('identificacao',20,@$Iidentificacao,true,'text',3,'')?>   
   <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td>
   <b>CPF:</b>
  </td>
  <td>
   <?db_input('z01_cgccpf',12,@$Iz01_cgccpf,true,'text',3,'')?>
   <b>Cargo:</b>
   <?db_input('rh37_descr',40,@$rh37_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_i_turno?>">
   <?db_ancora(@$Led254_i_turno,"js_pesquisaed254_i_turno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed254_i_turno',20,$Ied254_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed254_i_turno(false);'")?>
   <?db_input('ed15_c_nome',20,@$Ied15_c_nome,true,'text',3,'')?>
   </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_i_atolegal?>">
   <?db_ancora(@$Led254_i_atolegal,"js_pesquisaed254_i_atolegal(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed254_i_atolegal',20,$Ied254_i_atolegal,true,'text',$db_opcao," onchange='js_pesquisaed254_i_atolegal(false);'")?>
   <?db_input('ed05_c_finalidade',50,@$Ied05_c_finalidade,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_c_email?>">
   <?=@$Led254_c_email?>
  </td>
  <td>
   <?db_input('ed254_c_email',50,$Ied254_c_email,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,4,'$GLOBALS[Sed254_c_email]','f','t',event);\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_d_dataini?>">
   <?=@$Led254_d_dataini?>
  </td>
  <td>
   <?db_inputdata('ed254_d_dataini',@$ed254_d_dataini_dia,@$ed254_d_dataini_mes,@$ed254_d_dataini_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_d_datafim?>">
   <?=@$Led254_d_datafim?>
  </td>
  <td>
   <?db_inputdata('ed254_d_datafim',@$ed254_d_datafim_dia,@$ed254_d_datafim_mes,@$ed254_d_datafim_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_c_tipo?>">
   <?=@$Led254_c_tipo?>
  </td>
  <td>
   <?
   $x = array('A'=>'ABERTO','F'=>'FECHADO');
   db_select('ed254_c_tipo',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted254_d_datacad?>">
   <?=@$Led254_d_datacad?>
  </td>
  <td>
   <?db_inputdata('ed254_d_datacad',@$ed254_d_datacad_dia,@$ed254_d_datacad_mes,@$ed254_d_datacad_ano,true,'text',3,"")?>
   <?db_input('ed254_i_usuario',20,$Ied254_i_usuario,true,'hidden',3,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $campossql = "ed254_i_codigo,
                 ed254_i_escola,
                 ed18_c_nome,
                 ed254_i_rechumano,
                 case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,
                 case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as z01_cgccpf,
                 rh37_descr,
                 ed254_i_turno,
                 ed15_c_nome,
                 ed254_i_atolegal,
                 ed05_c_finalidade,
                 ed254_c_email,
                 ed254_d_dataini,
                 ed254_d_datafim,
                 ed254_c_tipo,
                 ed254_i_usuario,
                 ed254_d_datacad,
                 case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao";
   $chavepri= array("ed254_i_codigo"=>@$ed254_i_codigo,"ed254_i_escola"=>@$ed254_i_escola,"ed18_c_nome"=>@$ed18_c_nome,"ed254_i_rechumano"=>@$ed254_i_rechumano,"z01_nome"=>@$z01_nome,"z01_cgccpf"=>@$z01_cgccpf,"rh37_descr"=>@$rh37_descr,"ed254_i_turno"=>@$ed254_i_turno,"ed15_c_nome"=>@$ed15_c_nome,"ed254_i_atolegal"=>@$ed254_i_atolegal,"ed05_c_finalidade"=>@$ed05_c_finalidade,"ed254_c_email"=>@$ed254_c_email,"ed254_d_dataini"=>@$ed254_d_dataini,"ed254_d_datafim"=>@$ed254_d_datafim,"ed254_c_tipo"=>@$ed254_c_tipo,"ed254_i_usuario"=>@$ed254_i_usuario,"ed254_d_datacad"=>@$ed254_d_datacad,"identificacao"=>@$identificacao);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clescoladiretor->sql_query("",$campossql,"ed15_i_sequencia,ed254_d_dataini desc","ed254_i_escola = $ed254_i_escola");
   $cliframe_alterar_excluir->campos  ="ed254_i_rechumano,z01_nome,ed15_c_nome,ed254_d_dataini,ed254_d_datafim,ed254_c_tipo";
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
<script>
function js_pesquisaed254_i_rechumano(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_rechumano','func_rechumano.php?funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome|dl_cpf|rh37_descr|dl_identificacao','Pesquisa de Recursos Humanos',true);
 }
}
function js_mostrarechumano1(chave1,chave2,chave3,chave4,chave5){
 document.form1.ed254_i_rechumano.value = chave1;
 document.form1.identificacao.value = chave5;
 document.form1.z01_nome.value = chave2;
 document.form1.z01_cgccpf.value = chave3;
 document.form1.rh37_descr.value = chave4;
 db_iframe_rechumano.hide();
}
function js_pesquisaed254_i_turno(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_turno','func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome','Pesquisa de Turnos',true);
 }else{
  if(document.form1.ed254_i_turno.value != ''){
   js_OpenJanelaIframe('','db_iframe_turno','func_turno.php?pesquisa_chave='+document.form1.ed254_i_turno.value+'&funcao_js=parent.js_mostraturno','Pesquisa',false);
  }else{
   document.form1.ed15_c_nome.value = '';
  }
 }
}
function js_mostraturno(chave,erro){
 document.form1.ed15_c_nome.value = chave;
 if(erro==true){
  document.form1.ed254_i_turno.focus();
  document.form1.ed254_i_turno.value = '';
 }
}
function js_mostraturno1(chave1,chave2){
 document.form1.ed254_i_turno.value = chave1;
 document.form1.ed15_c_nome.value = chave2;
 db_iframe_turno.hide();
}
function js_pesquisaed254_i_atolegal(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?funcao_js=parent.js_mostraatolegal1|ed05_i_codigo|ed05_c_finalidade','Pesquisa de Atos Legais',true);
 }else{
  if(document.form1.ed254_i_atolegal.value != ''){
   js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?pesquisa_chave='+document.form1.ed254_i_atolegal.value+'&funcao_js=parent.js_mostraatolegal','Pesquisa',false);
  }else{
   document.form1.ed05_c_finalidade.value = '';
  }
 }
}
function js_mostraatolegal(chave,erro){
 document.form1.ed05_c_finalidade.value = chave;
 if(erro==true){
  document.form1.ed254_i_atolegal.focus();
  document.form1.ed254_i_atolegal.value = '';
 }
}
function js_mostraatolegal1(chave1,chave2){
 document.form1.ed254_i_atolegal.value = chave1;
 document.form1.ed05_c_finalidade.value = chave2;
 db_iframe_atolegal.hide();
}
function js_valida(){
 if(document.form1.ed254_c_tipo.value=="A" && document.form1.ed254_d_datafim.value!=""){
  alert("Situação do Exercício ABERTO exige a Data Final do Exercício em branco!");
  return false;
 }
 if(document.form1.ed254_c_tipo.value=="F" && document.form1.ed254_d_datafim.value==""){
  alert("Situação do Exercício FECHADO exige a Data Final do Exercício preenchida!");
  return false;
 }
 if(document.form1.ed254_i_turno.value==""){
  alert("Campo Turno não informado!");
  return false;
 }
 if(document.form1.z01_nome.value.length<4){
  alert("Nome do Diretor deve ter no mínimo 4 dígitos!");
  document.form1.z01_nome.style.backgroundColor='#99A9AE';
  document.form1.z01_nome.focus();
  return false;
 }
 Vemail = "<?=@$GLOBALS[Sed254_c_email]?>";
 if(jsValidaEmail(document.form1.ed254_c_email.value,Vemail)==false){
  return false;
 }
 return true;
}
</script>