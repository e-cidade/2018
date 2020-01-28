<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clferiado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed52_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $ed54_d_data_dia = substr(@$ed54_d_data,0,2);
 $ed54_d_data_mes = substr(@$ed54_d_data,3,2);
 $ed54_d_data_ano = substr(@$ed54_d_data,6,4);
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $ed54_d_data_dia = substr(@$ed54_d_data,0,2);
 $ed54_d_data_mes = substr(@$ed54_d_data,3,2);
 $ed54_d_data_ano = substr(@$ed54_d_data,6,4);
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
$result = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo",""," ed57_i_calendario = $ed54_i_calendario AND ed59_c_encerrada = 'S' AND ed59_c_condicao = 'OB'"));
if($clregencia->numrows>0){
 $db_botao = false;
 $opcoes = 4;
}else{
 $db_botao = true;
 $opcoes = 1;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td>
   <table border="0">
    <?db_input('ed54_i_codigo',15,$Ied54_i_codigo,true,'hidden',3,"")?>
    <tr>
     <td nowrap title="<?=@$Ted54_i_calendario?>">
      <?db_ancora(@$Led54_i_calendario,"",3);?>
     </td>
     <td>
      <?db_input('ed54_i_calendario',15,$Ied54_i_calendario,true,'text',3,'')?>
      <?db_input('ed52_c_descr',20,$Ied52_i_codigo,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted54_i_evento?>">
      <?db_ancora(@$Led54_i_evento,"js_pesquisaed54_i_evento(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed54_i_evento',15,$Ied54_i_evento,true,'text',$db_opcao," onchange='js_pesquisaed54_i_evento(false);'")?>
      <?db_input('ed96_c_descr',30,@$Ied96_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted54_c_descr?>">
      <?=@$Led54_c_descr?>
     </td>
     <td>
      <?db_input('ed54_c_descr',30,$Ied54_c_descr,true,'text',$db_opcao,"")?>
     </td>
    </tr>
   </table>
  </td>
  <td>
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Ted54_d_data?>">
      <?=@$Led54_d_data?>
     </td>
     <td>
      <?db_inputdata('ed54_d_data', @$ed54_d_data_dia, @$ed54_d_data_mes, @$ed54_d_data_ano, true, 'text', $db_opcao, 
                     " onchange=\"js_diasemana();\"","",""," parent.js_diasemana();")?>
      <b> até </b>
      <? db_inputdata('datafinal', @$datafinal_dia, @$datafinal_mes, @$datafinal_ano, true, 'text', $db_opcao, 
                      " onchange=\"js_validaData();\"","",""," parent.js_validaData();")?>
     </td>
    </tr>
    <tr>
     <td>
      <?=@$Led54_c_diasemana?>
     </td>
     <td>
      <?db_input('ed54_c_diasemana',30,$Ied54_c_diasemana,true,'text',$db_opcao," readonly ")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted54_c_dialetivo?>">
      <?=@$Led54_c_dialetivo?>
     </td>
     <td>
      <?
      $x = array('N'=>'NÃO','S'=>'SIM');
      db_select('ed54_c_dialetivo',$x,true,$db_opcao,"");
      ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<input name="ed54_i_calendario" type="hidden" value="<?=$ed54_i_calendario?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> onclick=" return js_validaPersistencia();" >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed54_i_codigo"=>@$ed54_i_codigo,"ed54_c_descr"=>@$ed54_c_descr,"ed54_c_diasemana"=>@$ed54_c_diasemana,"ed54_d_data"=>@$ed54_d_data,"ed54_c_dialetivo"=>@$ed54_c_dialetivo,"ed54_i_evento"=>@$ed54_i_evento,"ed96_c_descr"=>@$ed96_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clferiado->sql_query("","*","ed54_d_data"," ed54_i_calendario = $ed54_i_calendario AND ed54_c_dialetivo = 'N'");
   $cliframe_alterar_excluir->campos  = "ed96_c_descr,ed54_c_descr,ed54_d_data,ed54_c_diasemana";
   $cliframe_alterar_excluir->legenda="FERIADOS E EVENTOS NÃO LETIVOS";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="140";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->opcoes = $opcoes;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed54_i_codigo"=>@$ed54_i_codigo,"ed54_c_descr"=>@$ed54_c_descr,"ed54_c_diasemana"=>@$ed54_c_diasemana,"ed54_d_data"=>@$ed54_d_data,"ed54_c_dialetivo"=>@$ed54_c_dialetivo,"ed54_i_evento"=>@$ed54_i_evento,"ed96_c_descr"=>@$ed96_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clferiado->sql_query("","*","ed54_d_data"," ed54_i_calendario = $ed54_i_calendario AND ed54_c_dialetivo = 'S'");
   $cliframe_alterar_excluir->campos  = "ed96_c_descr,ed54_c_descr,ed54_d_data,ed54_c_diasemana";
   $cliframe_alterar_excluir->legenda="FERIADOS E EVENTOS LETIVOS";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="140";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->opcoes = $opcoes;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
<table>
 <tr>
  <td>
   <iframe src="" name="iframe_sabado2" id="iframe_sabado2" width="1" height="1" style="visibility:hidden;" frameborder="1"></iframe>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_diasemana() {
  
  if (document.form1.ed54_d_data_ano.value!="") {
    
   d1 = document.form1.ed54_d_data_dia.value;
   m1 = document.form1.ed54_d_data_mes.value;
   a1 = document.form1.ed54_d_data_ano.value;
   if(d1=="" || m1=="" || a1==""){
    alert("Preencha todos os campos da data!");
   }else{
    data = new Date(a1,m1-1,d1);
    diasemana = data.getDay();
    if(diasemana==0) diasemana = "DOMINGO";
    if(diasemana==1) diasemana = "SEGUNDA";
    if(diasemana==2) diasemana = "TERÇA";
    if(diasemana==3) diasemana = "QUARTA";
    if(diasemana==4) diasemana = "QUINTA";
    if(diasemana==5) diasemana = "SEXTA";
    if(diasemana==6) diasemana = "SABADO";
    document.form1.ed54_c_diasemana.value = diasemana;
   }
  }
}
function js_pesquisaed54_i_evento(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_evento','func_evento.php?funcao_js=parent.js_mostraevento1|ed96_i_codigo|ed96_c_descr','Pesquisa de Tipos de Eventos',true);
 }else{
  if(document.form1.ed54_i_evento.value != ''){
   js_OpenJanelaIframe('','db_iframe_evento','func_evento.php?pesquisa_chave='+document.form1.ed54_i_evento.value+'&funcao_js=parent.js_mostraevento','Pesquisa',false);
  }else{
   document.form1.ed96_c_descr.value = '';
  }
 }
}
function js_mostraevento(chave,erro){
 document.form1.ed96_c_descr.value = chave;
 document.form1.ed54_c_descr.value = chave;
 if(erro==true){
  document.form1.ed54_i_evento.focus();
  document.form1.ed54_i_evento.value = '';
 }
}
function js_mostraevento1(chave1,chave2){
 document.form1.ed54_i_evento.value = chave1;
 document.form1.ed96_c_descr.value = chave2;
 document.form1.ed54_c_descr.value = chave2;
 db_iframe_evento.hide();
}

function js_validaData() {

  if (js_comparadata($F('ed54_d_data'), $F('datafinal'), ">")) {

    alert("Intervalo de data inconsitente.\nData final maior que inicial.");
    $('ed54_d_data').value = ""; 
    $('datafinal').value   = "";
    
    return false;
  }
}

function js_validaPersistencia() {

  if ($F('ed54_d_data') == '') {

    alert('Campo data não informado.');
    return false;    
  }

  return confirm('O Sistema irá recalcular os dias e semanas letivos. Confirma?');
}

</script>