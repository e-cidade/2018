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
$clatojustificativa->rotulo->label();
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
  <td nowrap title="<?=@$Ted07_i_codigo?>">
   <?=@$Led07_i_codigo?>
  </td>
  <td>
   <?db_input('ed07_i_codigo',10,$Ied07_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted07_i_justificativa?>">
   <?db_ancora(@$Led07_i_justificativa,'',3);?>
  </td>
  <td>
   <?db_input('ed07_i_justificativa',10,@$Ied07_i_justificativa,true,'text',3,'')?>
   <?db_input('ed06_c_descr',60,@$ed06_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted07_i_ato?>">
   <?db_ancora(@$Led07_i_ato," js_pesquisaed07_i_ato(true); ",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed07_i_ato',10,@$Ied07_i_ato,true,'text',$db_opcao," onchange='js_pesquisaed07_i_ato(false)'; ")?>
   <?db_input('ed05_c_finalidade',50,@$Ied05_c_finalidade,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="ed07_i_justificativa" type="hidden" value="<?=@$ed07_i_justificativa?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ed07_i_codigo"=>@$ed07_i_codigo,"ed07_i_justificativa"=>@$ed07_i_justificativa,"ed07_i_ato"=>@$ed07_i_ato,"ed05_c_finalidade"=>@$ed05_c_finalidade);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clatojustificativa->sql_query("","*","","ed07_i_justificativa = $ed07_i_justificativa");
   $cliframe_alterar_excluir->campos  = "ed05_c_numero,ed05_c_finalidade,ed05_d_vigora,ed83_c_descr";
   $cliframe_alterar_excluir->labels  = "ed05_c_numero,ed05_c_finalidade,ed05_d_vigora,ed05_i_tipoato";
   $cliframe_alterar_excluir->legenda="ATOS ASSOCIADOS A JUSTIFICATIVA Cód. ".@$ed07_i_justificativa;
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="100";
   $cliframe_alterar_excluir->iframe_width ="650";
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
function js_pesquisaed07_i_ato(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?funcao_js=parent.js_mostraatolegal1|ed05_i_codigo|ed05_c_finalidade|','Pesquisa Atos Legais',true);
 }else{
  if(document.form1.ed07_i_ato.value != ''){
   js_OpenJanelaIframe('','db_iframe_atolegal','func_atolegal.php?pesquisa_chave='+document.form1.ed07_i_ato.value+'&funcao_js=parent.js_mostraatolegal','Pesquisa Atos Legais',false);
  }else{
   document.form1.ed05_c_finalidade.value = '';
  }
 }
}
function js_mostraatolegal(chave1,erro){
 document.form1.ed05_c_finalidade.value = chave1;
 if(erro==true){
  document.form1.ed07_i_ato.value = '';
  document.form1.ed07_i_ato.focus();
 }
}
function js_mostraatolegal1(chave1,chave2){
 document.form1.ed07_i_ato.value = chave1;
 document.form1.ed05_c_finalidade.value = chave2;
 db_iframe_atolegal.hide();
}
</script>