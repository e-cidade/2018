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
$clconvocacao->rotulo->label();
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $ed111_d_data_dia = substr($ed111_d_data,0,2);
 $ed111_d_data_mes = substr($ed111_d_data,3,2);
 $ed111_d_data_ano = substr($ed111_d_data,6,4);
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
if($ed110_i_ptconvocacao==0 || $ed110_i_ptgeral==0){
 db_msgbox("Pontuação da Convocação ou Pontuação Geral está com valor zero! (Configurações)");
 $db_opcao = 3;
 $db_opcao1 = 3;
 $db_botao = false;
 $db_botao1 = false;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted111_i_codigo?>">
   <?=@$Led111_i_codigo?>
  </td>
  <td>
   <?db_input('ed111_i_codigo',10,$Ied111_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted111_c_titulo?>">
   <?=@$Led111_c_titulo?>
  </td>
  <td>
   <?db_input('ed111_c_titulo',100,$Ied111_c_titulo,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted111_t_obs?>">
   <?=@$Led111_t_obs?>
  </td>
  <td>
   <?db_textarea('ed111_t_obs',3,70,$Ied111_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted111_d_data?>">
   <?=@$Led111_d_data?>
  </td>
  <td>
   <?db_inputdata('ed111_d_data',@$ed111_d_data_dia,@$ed111_d_data_mes,@$ed111_d_data_ano,true,'text',$db_opcao," onchange=\"js_data();\"","","","parent.js_data();","js_data();")?>
   &nbsp;&nbsp;&nbsp;&nbsp;
   <?=@$Led111_i_ano?>
   <?db_input('ed111_i_ano',4,$Ied111_i_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ed111_i_codigo"=>@$ed111_i_codigo,"ed111_c_titulo"=>@$ed111_c_titulo,"ed111_i_ano"=>@$ed111_i_ano,"ed111_d_data"=>@$ed111_d_data,"ed111_t_obs"=>@$ed111_t_obs);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clconvocacao->sql_query($ed111_i_codigo,"*","ed111_d_data desc");
   $cliframe_alterar_excluir->campos  ="ed111_i_codigo,ed111_c_titulo,ed111_d_data,ed111_i_ano";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="180";
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
function js_data(){
 if(document.form1.ed111_d_data_ano.value!=""){
  document.form1.ed111_i_ano.value = document.form1.ed111_d_data_ano.value;
 }
}
</script>