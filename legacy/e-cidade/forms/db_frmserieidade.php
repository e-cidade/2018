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

//MODULO: educa��o
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clserieidade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed11_i_ensino");
$clrotulo->label("ed10_c_descr");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_opcao1 = 3;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $db_opcao1 = 3;
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
  <td nowrap title="<?=@$Ted259_i_codigo?>">
   <?=@$Led259_i_codigo?>
  </td>
  <td>
   <?db_input('ed259_i_codigo',20,$Ied259_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted259_i_serie?>">
   <?db_ancora(@$Led259_i_serie,"js_pesquisaed259_i_serie(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed259_i_serie',20,$Ied259_i_serie,true,'text',$db_opcao1," onchange='js_pesquisaed259_i_serie(false);'")?>
   <?db_input('ed11_c_descr',20,$Ied11_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted11_i_ensino?>">
   <?=@$Led11_i_ensino?>
  </td>
  <td>
   <?db_input('ed11_i_ensino',20,$Ied11_i_ensino,true,'text',3,'')?>
   <?db_input('ed10_c_descr',40,$Ied10_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted259_i_idadeini?>">
   <?=@$Led259_i_idadeini?>
  </td>
  <td>
   <?db_input('ed259_i_idadeini',20,$Ied259_i_idadeini,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted259_i_idadefim?>">
   <?=@$Led259_i_idadefim?>
  </td>
  <td>
   <?db_input('ed259_i_idadefim',20,$Ied259_i_idadefim,true,'text',$db_opcao,"")?>
   </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed259_i_codigo"=>@$ed259_i_codigo,"ed259_i_serie"=>@$ed259_i_serie,"ed11_c_descr"=>@$ed11_c_descr,"ed11_i_ensino"=>@$ed11_i_ensino,"ed10_c_descr"=>@$ed10_c_descr,"ed259_i_idadeini"=>@$ed259_i_idadeini,"ed259_i_idadefim"=>@$ed259_i_idadefim);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clserieidade->sql_query($ed259_i_codigo,"*","ed11_i_ensino,ed11_i_sequencia");
   $cliframe_alterar_excluir->campos  ="ed259_i_codigo,ed11_c_descr,ed10_c_descr,ed259_i_idadeini,ed259_i_idadefim";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="N�o foi encontrado nenhum registro.";
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
</center>
</form>
<script>
function js_pesquisaed259_i_serie(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_serieidade','func_serieidade.php?funcao_js=parent.js_mostraserie1|ed11_i_codigo|ed11_c_descr|ed10_i_codigo|ed10_c_descr','Pesquisa',true);
 }else{
  if(document.form1.ed259_i_serie.value != ''){
   js_OpenJanelaIframe('','db_iframe_serieidade','func_serieidade.php?pesquisa_chave='+document.form1.ed259_i_serie.value+'&funcao_js=parent.js_mostraserie','Pesquisa',false);
  }else{
   document.form1.ed11_c_descr.value = '';
   document.form1.ed11_i_ensino.value = '';
   document.form1.ed10_c_descr.value = '';
  }
 }
}
function js_mostraserie(chave1,chave2,chave3,erro){
 document.form1.ed11_c_descr.value = chave1;
 document.form1.ed11_i_ensino.value = chave2;
 document.form1.ed10_c_descr.value = chave3;
 if(erro==true){
  document.form1.ed259_i_serie.focus();
  document.form1.ed259_i_serie.value = '';
 }
}
function js_mostraserie1(chave1,chave2,chave3,chave4){
 document.form1.ed259_i_serie.value = chave1;
 document.form1.ed11_c_descr.value = chave2;
 document.form1.ed11_i_ensino.value = chave3;
 document.form1.ed10_c_descr.value = chave4;
 db_iframe_serieidade.hide();
}
</script>