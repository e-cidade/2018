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
$cltelefonealuno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
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
  <td nowrap title="<?=@$Ted50_i_codigo?>">
   <?=@$Led50_i_codigo?>
  </td>
  <td>
   <?db_input('ed50_i_codigo',10,$Ied50_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted50_i_aluno?>">
   <?db_ancora(@$Led50_i_aluno,"",3);?>
  </td>
  <td>
   <?db_input('ed50_i_aluno',10,$Ied50_i_aluno,true,'text',3,"")?>
   <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted50_i_tipotelefone?>">
   <?db_ancora(@$Led50_i_tipotelefone,"js_pesquisaed50_i_tipotelefone(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed50_i_tipotelefone',10,$Ied50_i_tipotelefone,true,'text',$db_opcao," onchange='js_pesquisaed50_i_tipotelefone(false);'")?>
   <?db_input('ed13_c_descr',40,@$Ied13_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted50_i_numero?>">
   <?=@$Led50_i_numero?>
  </td>
  <td>
   <?db_input('ed50_i_numero',15,$Ied50_i_numero,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted50_i_ramal?>">
   <?=@$Led50_i_ramal?>
  </td>
  <td>
   <?db_input('ed50_i_ramal',10,$Ied50_i_ramal,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted50_t_obs?>">
   <?=@$Led50_t_obs?>
  </td>
  <td>
   <?db_textarea('ed50_t_obs',2,50,$Ied50_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="ed50_i_aluno" type="hidden" value="<?=@$ed50_i_aluno?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ed50_i_codigo"=>@$ed50_i_codigo,"ed50_i_aluno"=>@$ed50_i_aluno,"z01_nome"=>@$z01_nome,"ed50_i_tipotelefone"=>@$ed50_i_tipotelefone,"ed13_c_descr"=>@$ed13_c_descr,"ed50_i_numero"=>@$ed50_i_numero,"ed50_i_ramal"=>@$ed50_i_ramal,"ed50_t_obs"=>@$ed50_t_obs);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $cltelefonealuno->sql_query("","*","","ed50_i_aluno = $ed50_i_aluno");
   $cliframe_alterar_excluir->campos  = "ed50_i_codigo,ed13_c_descr,ed50_i_numero,ed50_i_ramal";
   $cliframe_alterar_excluir->labels  = "ed50_i_codigo,ed50_i_tipotelefone,ed50_i_numero,ed50_i_ramal";
   $cliframe_alterar_excluir->legenda="Registros";
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
</form>
</center>
<script>
function js_pesquisaed50_i_tipotelefone(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_tipotelefone','func_tipotelefone.php?funcao_js=parent.js_mostratipotelefone1|ed13_i_codigo|ed13_c_descr','Pesquisa Tipos de Telefone',true);
 }else{
  if(document.form1.ed50_i_tipotelefone.value != ''){
   js_OpenJanelaIframe('','db_iframe_tipotelefone','func_tipotelefone.php?pesquisa_chave='+document.form1.ed50_i_tipotelefone.value+'&funcao_js=parent.js_mostratipotelefone','Pesquisa',false);
  }else{
   document.form1.ed13_c_descr.value = '';
  }
 }
}
function js_mostratipotelefone(chave,erro){
 document.form1.ed13_c_descr.value = chave;
 if(erro==true){
  document.form1.ed50_i_tipotelefone.focus();
  document.form1.ed50_i_tipotelefone.value = '';
 }
}
function js_mostratipotelefone1(chave1,chave2){
  document.form1.ed50_i_tipotelefone.value = chave1;
  document.form1.ed13_c_descr.value = chave2;
  db_iframe_tipotelefone.hide();
}
</script>