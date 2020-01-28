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

//escola
$clescola->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cltelefoneescola->rotulo->label();
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed13_i_codigo");
$clrotulo->label("ed18_c_email");
$clrotulo->label("ed18_c_homepage");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 if(isset($db_opcaol)){
  $db_opcao=33;
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
<fieldset><Legend><b>Contatos Web</b></Legend>
<table align="left">
 <tr>
  <td><?=$Led18_c_email?></td>
  <td><?db_input('ed18_c_email',50,$Ied18_c_email,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,4,'$GLOBALS[Sed18_c_email]','f','t',event);\"")?></td>
 </tr>
 <tr>
  <td><?=$Led18_c_homepage?></td>
  <td><?db_input('ed18_c_homepage',100,$Ied18_c_homepage,true,'text',$db_opcao,"")?>
  <input name="alterar1" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();"></td>
 </tr>
</table>
</fieldset>
<fieldset><legend><b>Telefone/Fax</b></legend>
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted26_i_codigo?>">
   <?=@$Led26_i_codigo?>
  </td>
  <td>
   <?db_input('ed26_i_codigo',15,$Ied26_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted26_i_escola?>">
   <?db_ancora(@$Led26_i_escola,"",3);?>
  </td>
  <td>
   <?db_input('ed26_i_escola',15,@$Ied26_i_escola,true,'text',3,'')?>
   <?db_input('descrdepto',50,@$Idescrdepto,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted26_i_tipotelefone?>">
   <?db_ancora(@$Led26_i_tipotelefone,"js_pesquisaed26_i_tipotelefone(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed26_i_tipotelefone',15,$Ied26_i_tipotelefone,true,'text',$db_opcao," onchange='js_pesquisaed26_i_tipotelefone(false);'")?>
   <?db_input('ed13_c_descr',20,$Ied13_i_codigo,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted26_i_ddd?>">
   <?=@$Led26_i_ddd?>
   <?db_input('ed26_i_ddd',2,$Ied26_i_ddd,true,'text',$db_opcao,"")?>
  </td>
  <td nowrap title="<?=@$Ted26_i_numero?>">
   <?=@$Led26_i_numero?>
   <?db_input('ed26_i_numero',15,$Ied26_i_numero,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted26_t_obs?>">
   <?=@$Led26_t_obs?>
  </td>
  <td>
   <?db_textarea('ed26_t_obs',2,40,$Ied26_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed26_i_codigo"=>@$ed26_i_codigo,"ed26_i_tipotelefone"=>@$ed26_i_tipotelefone,"ed13_c_descr"=>@$ed13_c_descr,"ed26_i_numero"=>@$ed26_i_numero,"ed26_i_ramal"=>@$ed26_i_ramal,"ed26_t_obs"=>@$ed26_t_obs,"ed26_i_ddd"=>@$ed26_i_ddd);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $cltelefoneescola->sql_query("","*","ed26_i_codigo"," ed26_i_escola = $ed26_i_escola");
   $cliframe_alterar_excluir->campos  ="ed26_i_codigo,ed13_c_descr,ed26_i_ddd,ed26_i_numero,ed26_i_ramal";
   $cliframe_alterar_excluir->labels  ="ed26_i_codigo,ed26_i_tipotelefone,ed26_i_ddd,ed26_i_numero,ed26_i_ramal";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="130";
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
</fieldset>
</form>
<script>
function js_pesquisaed26_i_tipotelefone(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_tipotelefone','func_tipotelefone.php?funcao_js=parent.js_mostratipotelefone1|ed13_i_codigo|ed13_c_descr','Pesquisa Tipos de Telefone',true);
 }else{
  if(document.form1.ed26_i_tipotelefone.value != ''){
   js_OpenJanelaIframe('','db_iframe_tipotelefone','func_tipotelefone.php?pesquisa_chave='+document.form1.ed26_i_tipotelefone.value+'&funcao_js=parent.js_mostratipotelefone','Pesquisa Tipos de Telefone',false);
  }else{
   document.form1.ed13_c_descr.value = '';
  }
 }
}
function js_mostratipotelefone(chave,erro){
 document.form1.ed13_c_descr.value = chave;
 if(erro==true){
  document.form1.ed26_i_tipotelefone.focus();
  document.form1.ed26_i_tipotelefone.value = '';
 }
}
function js_mostratipotelefone1(chave1,chave2){
 document.form1.ed26_i_tipotelefone.value = chave1;
 document.form1.ed13_c_descr.value = chave2;
 db_iframe_tipotelefone.hide();
}
function js_valida(){
 Vemail = "<?=@$GLOBALS[Sed18_c_email]?>";
 if(jsValidaEmail(document.form1.ed18_c_email.value,Vemail)==false){
  return false;
 }
 return true;
}
</script>