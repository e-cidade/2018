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
$clareatrabalho->rotulo->label();
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
  <td nowrap title="<?=@$Ted25_i_codigo?>">
   <?=@$Led25_i_codigo?>
  </td>
  <td>
   <?db_input('ed25_i_codigo',10,$Ied25_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted25_i_ensino?>">
   <?db_ancora(@$Led25_i_ensino,"js_pesquisaed25_i_ensino(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed25_i_ensino',10,$Ied25_i_ensino,true,'text',$db_opcao," onchange='js_pesquisaed25_i_ensino(false);'")?>
   <?db_input('ed10_c_descr',30,@$Ied10_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted25_c_descr?>">
   <?=@$Led25_c_descr?>
  </td>
  <td>
   <?db_input('ed25_c_descr',50,$Ied25_c_descr,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed25_i_codigo"=>@$ed25_i_codigo,"ed25_c_descr"=>@$ed25_c_descr,"ed25_i_ensino"=>@$ed25_i_ensino,"ed10_c_descr"=>@$ed10_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clareatrabalho->sql_query("","ed25_i_codigo,ed25_c_descr,ed25_i_ensino,ed10_c_descr","ed10_c_descr,ed25_c_descr");
   $cliframe_alterar_excluir->campos  ="ed25_i_codigo,ed25_c_descr,ed10_c_descr";
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
function js_pesquisaed25_i_ensino(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?funcao_js=parent.js_mostraensino1|ed10_i_codigo|ed10_c_descr','Pesquisa de Ensinos',true);
 }else{
  if(document.form1.ed25_i_ensino.value != ''){
   js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?pesquisa_chave='+document.form1.ed25_i_ensino.value+'&funcao_js=parent.js_mostraensino','Pesquisa Ensinos',false);
  }else{
   document.form1.ed10_c_descr.value = '';
  }
 }
}
function js_mostraensino(chave,erro){
 document.form1.ed10_c_descr.value = chave;
 if(erro==true){
  document.form1.ed25_i_ensino.focus();
  document.form1.ed25_i_ensino.value = '';
 }
}
function js_mostraensino1(chave1,chave2){
 document.form1.ed25_i_ensino.value = chave1;
 document.form1.ed10_c_descr.value = chave2;
 db_iframe_ensino.hide();
}
</script>