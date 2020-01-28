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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cltelefonerechumano->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("ed13_i_codigo");
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
   <?db_input('ed30_i_codigo',15,@$Ied30_i_codigo,true,'hidden',3,"")?>
   <?db_input('ed30_i_rechumano',15,@$Ied30_i_rechumano,true,'hidden',3,"")?>
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
  <td nowrap title="<?=@$Ted30_i_tipotelefone?>">
   <?db_ancora(@$Led30_i_tipotelefone,"js_pesquisaed30_i_tipotelefone(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed30_i_tipotelefone', 15, $Ied30_i_tipotelefone, true, 'text', $db_opcao," onchange='js_pesquisaed30_i_tipotelefone(false);'")?>
   <?db_input('ed13_c_descr',20,@$Ied13_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted30_i_numero?>">
   <b>DDD:</b>
  </td>
  <td>
   <?db_input('ed30_i_ramal',2,$Ied30_i_ramal,true,'text',$db_opcao,"")?>
   <?=@$Led30_i_numero?>
   <?db_input('ed30_i_numero',10,$Ied30_i_numero,true,'text',$db_opcao,"", '', '', '', 9)?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted30_t_obs?>">
   <?=@$Led30_t_obs?>
  </td>
  <td>
   <?db_textarea('ed30_t_obs',2,50,$Ied30_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed30_i_codigo"=>@$ed30_i_codigo,"ed30_i_rechumano"=>@$ed30_i_rechumano,"z01_nome"=>@$z01_nome,"ed30_i_tipotelefone"=>@$ed30_i_tipotelefone,"ed13_c_descr"=>@$ed13_c_descr,"ed30_i_numero"=>@$ed30_i_numero,"ed30_i_ramal"=>@$ed30_i_ramal,"ed30_t_obs"=>@$ed30_t_obs);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $cltelefonerechumano->sql_query("","*",""," ed30_i_rechumano = $ed30_i_rechumano");
   $cliframe_alterar_excluir->campos  ="ed30_i_codigo,ed13_c_descr,ed30_i_ramal,ed30_i_numero";
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
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed30_i_tipotelefone(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_tipotelefone','func_tipotelefone.php?funcao_js=parent.js_mostratipotelefone1|ed13_i_codigo|ed13_c_descr','Pesquisa de Tipos de Telefone',true);
 }else{
  if(document.form1.ed30_i_tipotelefone.value != ''){
   js_OpenJanelaIframe('','db_iframe_tipotelefone','func_tipotelefone.php?pesquisa_chave='+document.form1.ed30_i_tipotelefone.value+'&funcao_js=parent.js_mostratipotelefone','Pesquisa',false);
  }else{
   document.form1.ed13_c_descr.value = '';
  }
 }
}
function js_mostratipotelefone(chave,erro){
 document.form1.ed13_c_descr.value = chave;
 if(erro==true){
  document.form1.ed30_i_tipotelefone.focus();
  document.form1.ed30_i_tipotelefone.value = '';
 }
}
function js_mostratipotelefone1(chave1,chave2){
 document.form1.ed30_i_tipotelefone.value = chave1;
 document.form1.ed13_c_descr.value = chave2;
 db_iframe_tipotelefone.hide();
}
</script>