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

//MODULO: saude
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsau_atendprestund->rotulo->label();
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
  <td nowrap title="<?=@$Tsd48_i_codigo?>">
   <?=@$Lsd48_i_codigo?>
  </td>
  <td>
   <?db_input('sd48_i_codigo',10,$Isd48_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd48_i_unidade?>">
   <?=@$Lsd48_i_unidade?>
  </td>
  <td>
   <?db_input('sd48_i_unidade',10,$Isd48_i_unidade,true,'text',3,"")?>
   <?db_input('descrdepto',40,@$Idescrdepto,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd48_i_atendprestado?>">
   <?db_ancora(@$Lsd48_i_atendprestado,"js_pesquisasd48_i_atendprestado(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('sd48_i_atendprestado',10,$Isd48_i_atendprestado,true,'text',$db_opcao,"onchange='js_pesquisasd48_i_atendprestado(false);'")?>
   <?db_input('sd46_v_descricao',60,@$Isd46_v_descricao,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd48_i_convenio?>">
   <?db_ancora(@$Lsd48_i_convenio,"js_pesquisasd48_i_convenio(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('sd48_i_convenio',10,$Isd48_i_convenio,true,'text',$db_opcao," onchange='js_pesquisasd48_i_convenio(false);'")?>
   <?db_input('sd49_v_descricao',60,@$Isd49_v_descricao,true,'text',3,'')?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $escola = db_getsession("DB_coddepto");
   $chavepri= array("sd48_i_codigo"=>@$sd48_i_codigo,"sd48_i_unidade"=>@$sd48_i_unidade,"descrdepto"=>@$descrdepto,"sd48_i_atendprestado"=>@$sd48_i_atendprestado,"sd46_v_descricao"=>@$sd46_v_descricao,"sd48_i_convenio"=>@$sd48_i_convenio,"sd49_v_descricao"=>@$sd49_v_descricao);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clsau_atendprestund->sql_query("","*",""," sd48_i_unidade = $sd48_i_unidade");
   $cliframe_alterar_excluir->campos  ="sd48_i_codigo,sd46_v_descricao,sd49_v_descricao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="110";
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
function js_pesquisasd48_i_atendprestado(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_sau_atendprest','func_sau_atendprest.php?funcao_js=parent.js_mostrasau_atendprest1|sd46_i_codigio|sd46_v_descricao','Pesquisa',true,0,0,screen.availWidth-140,350);
 }else{
  if(document.form1.sd48_i_atendprestado.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_atendprest','func_sau_atendprest.php?pesquisa_chave='+document.form1.sd48_i_atendprestado.value+'&funcao_js=parent.js_mostrasau_atendprest','Pesquisa',false);
  }else{
   document.form1.sd46_v_descricao.value = '';
  }
 }
}
function js_mostrasau_atendprest(chave,erro){
 document.form1.sd46_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd48_i_atendprestado.focus();
  document.form1.sd48_i_atendprestado.value = '';
 }
}
function js_mostrasau_atendprest1(chave1,chave2){
 document.form1.sd48_i_atendprestado.value = chave1;
 document.form1.sd46_v_descricao.value = chave2;
 db_iframe_sau_atendprest.hide();
}
function js_pesquisasd48_i_convenio(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_sau_convenio','func_sau_convenio.php?funcao_js=parent.js_mostrasau_convenio1|sd49_i_codigo|sd49_v_descricao','Pesquisa',true,0,0,screen.availWidth-140,350);
 }else{
  if(document.form1.sd48_i_convenio.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_convenio','func_sau_convenio.php?pesquisa_chave='+document.form1.sd48_i_convenio.value+'&funcao_js=parent.js_mostrasau_convenio','Pesquisa',false);
  }else{
   document.form1.sd49_v_descricao.value = '';
  }
 }
}
function js_mostrasau_convenio(chave,erro){
 document.form1.sd49_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd48_i_convenio.focus();
  document.form1.sd48_i_convenio.value = '';
 }
}
function js_mostrasau_convenio1(chave1,chave2){
 document.form1.sd48_i_convenio.value = chave1;
 document.form1.sd49_v_descricao.value = chave2;
 db_iframe_sau_convenio.hide();
}
</script>