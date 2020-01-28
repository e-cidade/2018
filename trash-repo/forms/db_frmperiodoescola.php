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
$clperiodoescola->rotulo->label();
$db_botao1 = false;
if((isset($opcao) && $opcao=="alterar") || isset($alterar)){
 $db_opcao = 2;
 $db_opcao2 = 3;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_opcao = 3;
 $db_opcao2 = 3;
 $db_botao1 = true;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted17_i_escola?>">
   <?db_ancora(@$Led17_i_escola,"",3);?>
  </td>
  <td colspan="2">
   <?db_input('ed17_i_escola',15,@$Ied17_i_escola,true,'text',3,'')?>
   <?db_input('descrdepto',50,@$Idescrdepto,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted17_i_turno?>">
   <?db_ancora(@$Led17_i_turno,"js_pesquisaed17_i_turno(true);",$db_opcao2);?>
  </td>
  <td>
   <?db_input('ed17_i_turno',15,$Ied17_i_turno,true,'text',$db_opcao2," onchange='js_pesquisaed17_i_turno(false);'")?>
   <?db_input('ed15_c_nome',20,@$Ied15_c_nome,true,'text',3,'')?>
  </td>
  <td nowrap title="<?=@$Ted17_h_inicio?>">
   <?=@$Led17_h_inicio?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <?db_input('ed17_h_inicio',5,$Ied17_h_inicio,true,'text',$db_opcao,"OnKeyUp=\"mascara_hora(this.value,4)\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted17_i_periodoaula?>">
   <?db_ancora(@$Led17_i_periodoaula,"js_pesquisaed17_i_periodoaula(true);",$db_opcao2);?>
  </td>
  <td>
   <?db_input('ed17_i_periodoaula',15,$Ied17_i_periodoaula,true,'text',$db_opcao2," onchange='js_pesquisaed17_i_periodoaula(false);'")?>
   <?db_input('ed08_c_descr',10,@$Ied08_c_descr,true,'text',3,'')?>
  </td>
  <td>
   <?=@$Led17_h_fim?>
   <?db_input('ed17_h_fim',5,$Ied17_h_fim,true,'text',$db_opcao,"OnKeyUp=\"mascara_hora(this.value,7)\"")?>
  </td>
 </tr>
</table>
<input name="ed17_i_codigo" type="hidden" value="<?=@$ed17_i_codigo?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed17_i_codigo"=>@$ed17_i_codigo,"ed17_i_escola"=>@$ed17_i_escola,"ed17_i_turno"=>@$ed17_i_turno,"ed15_c_nome"=>@$ed15_c_nome,"ed17_i_periodoaula"=>@$ed17_i_periodoaula,"ed08_c_descr"=>@$ed08_c_descr,"ed17_h_inicio"=>@$ed17_h_inicio,"ed17_h_fim"=>@$ed17_h_fim);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clperiodoescola->sql_query("","*","ed15_i_sequencia,ed08_i_sequencia"," ed17_i_escola = $ed17_i_escola");
   $cliframe_alterar_excluir->campos  ="ed15_c_nome,ed08_c_descr,ed17_h_inicio,ed17_h_fim";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="245";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_pesquisaed17_i_periodoaula(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_periodoaula','func_periodoaula.php?funcao_js=parent.js_mostraperiodoaula1|ed08_i_codigo|ed08_c_descr','Pesquisa Períodos de Aula',true);
 }else{
  if(document.form1.ed17_i_periodoaula.value != ''){
   js_OpenJanelaIframe('','db_iframe_periodoaula','func_periodoaula.php?pesquisa_chave='+document.form1.ed17_i_periodoaula.value+'&funcao_js=parent.js_mostraperiodoaula','Pesquisa Períodos de Aula',false);
  }else{
   document.form1.ed08_c_descr.value = '';
  }
 }
}
function js_mostraperiodoaula(chave,erro){
 document.form1.ed08_c_descr.value = chave;
 if(erro==true){
  document.form1.ed17_i_periodoaula.focus();
  document.form1.ed17_i_periodoaula.value = '';
 }
}
function js_mostraperiodoaula1(chave1,chave2){
 document.form1.ed17_i_periodoaula.value = chave1;
 document.form1.ed08_c_descr.value = chave2;
 db_iframe_periodoaula.hide();
}
function js_pesquisaed17_i_turno(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_turno','func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome','Pesquisa Turnos',true);
 }else{
  if(document.form1.ed17_i_turno.value != ''){
   js_OpenJanelaIframe('','db_iframe_turno','func_turno.php?pesquisa_chave='+document.form1.ed17_i_turno.value+'&funcao_js=parent.js_mostraturno','Pesquisa Turnos',false);
  }else{
   document.form1.ed15_c_nome.value = '';
  }
 }
}
function js_mostraturno(chave,erro){
 document.form1.ed15_c_nome.value = chave;
 if(erro==true){
  document.form1.ed17_i_turno.focus();
  document.form1.ed17_i_turno.value = '';
 }
}
function js_mostraturno1(chave1,chave2){
 document.form1.ed17_i_turno.value = chave1;
 document.form1.ed15_c_nome.value = chave2;
 db_iframe_turno.hide();
}
function mascara_hora(hora,x){
 var myhora = '';
 myhora = myhora + hora;
 if(myhora.length == 2){
  myhora = myhora + ':';
  document.form1[x].value = myhora;
 }
 if(myhora.length == 5){
  verifica_hora(x);
 }
}

function verifica_hora(x){
 hrs = (document.form1[x].value.substring(0,2));
 min = (document.form1[x].value.substring(3,5));
 situacao = "";
 // verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) ) {
  alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
  document.form1[x].value="";
  document.form1[x].focus();
 }
}
</script>