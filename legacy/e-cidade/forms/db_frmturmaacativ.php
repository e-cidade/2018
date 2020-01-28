<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: Escola
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clturmaacativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed268_i_codigo");
$clrotulo->label("ed274_c_nome");

$db_botao1 = false;
if (isset($opcao) && $opcao=="alterar") {
  $db_opcao = 2;
  $db_botao1 = true;
  $sql0 = "SELECT ed274_i_codigo,ed274_c_nome FROM turmaacativnova WHERE ed274_i_turmaacativ = $ed267_i_codigo";
  $result0 = db_query($sql0);
  if (pg_num_rows($result0)>0) {

    db_fieldsmemory($result0,0);
  }
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $sql0 = "SELECT ed274_i_codigo,ed274_c_nome FROM turmaacativnova WHERE ed274_i_turmaacativ = $ed267_i_codigo";
 $result0 = db_query($sql0);
 if(pg_num_rows($result0)>0){
  db_fieldsmemory($result0,0);
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
  <td nowrap title="<?=@$Ted267_i_codigo?>">
   <?=@$Led267_i_codigo?>
  </td>
  <td>
   <?db_input('ed267_i_codigo',10,$Ied267_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted267_i_turmaac?>">
   <?db_ancora(@$Led267_i_turmaac,"",3);?>
  </td>
  <td>
   <?db_input('ed267_i_turmaac',10,$Ied267_i_turmaac,true,'text',3,"")?>
   <?db_input('ed268_c_descr',50,@$Ied268_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted267_i_censoativcompl?>">
   <?db_ancora(@$Led267_i_censoativcompl,"js_pesquisaed267_i_censoativcompl(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed267_i_censoativcompl',10,$Ied267_i_censoativcompl,true,'text',$db_opcao," onchange='js_pesquisaed267_i_censoativcompl(false);'")?>
   <?db_input('ed133_c_descr',80,@$Ied133_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <?
 if(isset($ed274_i_codigo) && $ed274_i_codigo!=""){
  $visible = "visible";
 }else{
  $visible = "hidden";
  $ed274_i_codigo = "";
  $ed274_c_nome = "";
 }
 ?>
 <tr id="outraativ" style="visibility:<?=$visible?>">
  <td nowrap title="<?=@$Ted274_c_nome?>">
   <?=@$Led274_c_nome?>
  </td>
  <td>
   <?db_input('ed274_i_codigo',10,@$Ied274_i_codigo,true,'hidden',$db_opcao,"")?>
   <?db_input('ed274_c_nome',70,@$Ied274_c_nome,true,'text',$db_opcao,"")?>
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
   $campos = "ed267_i_codigo,
              ed267_i_turmaac,
              ed268_c_descr,
              ed267_i_censoativcompl,
              ed133_c_descr,
              (select ed274_c_nome from turmaacativnova where ed274_i_turmaacativ = ed267_i_codigo) as ed274_c_nome
             ";
   $chavepri= array("ed267_i_codigo"=>@$ed267_i_codigo,"ed267_i_turmaac"=>@$ed267_i_turmaac,"ed268_c_descr"=>@$ed268_c_descr,"ed267_i_censoativcompl"=>@$ed267_i_censoativcompl,"ed133_c_descr"=>@$ed133_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clturmaacativ->sql_query("",$campos,"ed133_c_descr"," ed267_i_turmaac = $ed267_i_turmaac");
   $cliframe_alterar_excluir->campos  ="ed267_i_censoativcompl,ed133_c_descr,ed274_c_nome";
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

var oGet = js_urlToObject();

function js_pesquisaed267_i_censoativcompl(mostra) {

  var sUrl = 'func_censoativcompl.php?';
  sUrl += 'iCalendario='+oGet.iCalendario;
  if (mostra) {

    sUrl += '&funcao_js=parent.js_mostracensoativcompl1|ed133_i_codigo|ed133_c_descr';
    js_OpenJanelaIframe('','db_iframe_censoativcompl',sUrl ,'Pesquisa atividades complementares',true);
  } else {

    if ( $F('ed267_i_censoativcompl') != '' ) {

      sUrl += '&pesquisa_chave='+$F('ed267_i_censoativcompl')+'&funcao_js=parent.js_mostracensoativcompl';
      js_OpenJanelaIframe('','db_iframe_censoativcompl',sUrl , 'Pesquisa atividades complementares',false);
    } else {

      document.form1.ed133_c_descr.value = '';
      document.getElementById("outraativ").style.visibility = 'hidden';
      document.form1.ed274_c_nome.value = '';
      document.form1.ed274_i_codigo.value = '';
    }
  }
}
function js_mostracensoativcompl(chave,erro){
 document.form1.ed133_c_descr.value = chave;
 if(erro==true){
  document.form1.ed267_i_censoativcompl.focus();
  document.form1.ed267_i_censoativcompl.value = '';
  document.getElementById("outraativ").style.visibility = 'hidden';
  document.form1.ed274_c_nome.value = '';
  document.form1.ed274_i_codigo.value = '';
 }else{
  if(document.form1.ed267_i_censoativcompl.value==19999 || document.form1.ed267_i_censoativcompl.value==29999 || document.form1.ed267_i_censoativcompl.value==39999 || document.form1.ed267_i_censoativcompl.value==49999 || document.form1.ed267_i_censoativcompl.value==59999 || document.form1.ed267_i_censoativcompl.value==69999 || document.form1.ed267_i_censoativcompl.value==79999 || document.form1.ed267_i_censoativcompl.value==89999 || document.form1.ed267_i_censoativcompl.value==99999){
   document.getElementById("outraativ").style.visibility = 'visible';
  }else{
   document.getElementById("outraativ").style.visibility = 'hidden';
   document.form1.ed274_c_nome.value = '';
   document.form1.ed274_i_codigo.value = '';
  }
 }
}
function js_mostracensoativcompl1(chave1,chave2){
 document.form1.ed267_i_censoativcompl.value = chave1;
 document.form1.ed133_c_descr.value = chave2;
 if(document.form1.ed267_i_censoativcompl.value==19999 || document.form1.ed267_i_censoativcompl.value==29999 || document.form1.ed267_i_censoativcompl.value==39999 || document.form1.ed267_i_censoativcompl.value==49999 || document.form1.ed267_i_censoativcompl.value==59999 || document.form1.ed267_i_censoativcompl.value==69999 || document.form1.ed267_i_censoativcompl.value==79999 || document.form1.ed267_i_censoativcompl.value==89999 || document.form1.ed267_i_censoativcompl.value==99999){
  document.getElementById("outraativ").style.visibility = 'visible';
 }else{
  document.getElementById("outraativ").style.visibility = 'hidden';
  document.form1.ed274_c_nome.value = '';
  document.form1.ed274_i_codigo.value = '';
 }
 db_iframe_censoativcompl.hide();
}
function js_valida(){
 return true;
}
</script>