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
$clproglicencamatr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed112_d_datainicio");
$clrotulo->label("ed112_i_progclasse");
$clrotulo->label("ed112_c_situacao");
if(isset($ed112_c_situacao) && trim($ed112_c_situacao)!="A"){
 $db_botao = false;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted122_i_codigo?>">
   <?=@$Led122_i_codigo?>
  </td>
  <td>
   <?db_input('ed122_i_codigo',10,$Ied122_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted122_i_progmatricula?>">
   <?db_ancora(@$Led122_i_progmatricula,"js_pesquisaed122_i_progmatricula(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed122_i_progmatricula',10,$Ied122_i_progmatricula,true,'hidden',3,"")?>
   <?db_input('ed112_i_rhpessoal',10,@$Ied112_i_rhpessoal,true,'text',3,"")?>
   <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_d_datainicio?>">
   <?=@$Led112_d_datainicio?>
  </td>
  <td>
   <?db_inputdata('ed112_d_datainicio',@$ed112_d_datainicio_dia,@$ed112_d_datainicio_mes,@$ed112_d_datainicio_ano,true,'text',3,"")?>
   <?=@$Led112_i_progclasse?>
   <?db_input('ed107_c_descr',10,@$Ied107_c_descr,true,'text',3,'')?>
   <?if($db_opcao!=1){
    if(@$ed112_c_situacao=="A"){
     $ed112_c_situacao = "ABERTA";
    }elseif(@$ed112_c_situacao=="I"){
     $ed112_c_situacao = "INTERROMPIDA";
    }else{
     $ed112_c_situacao = "ENCERRADA";
    }
    ?>
    <?=@$Led112_c_situacao?>
    <input name="ed112_c_situacao" type="text" value="<?=@$ed112_c_situacao?>" style="background:#DEB887;" readonly>
   <?}?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted122_i_proglicenca?>">
   <?db_ancora(@$Led122_i_proglicenca,"js_pesquisaed122_i_proglicenca(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed122_i_proglicenca',10,$Ied122_i_proglicenca,true,'text',$db_opcao," onchange='js_pesquisaed122_i_proglicenca(false);'")?>
   <?db_input('ed121_c_descr',50,@$Ied121_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted122_d_inicio?>">
   <?=@$Led122_d_inicio?>
  </td>
  <td>
   <?db_inputdata('ed122_d_inicio',@$ed122_d_inicio_dia,@$ed122_d_inicio_mes,@$ed122_d_inicio_ano,true,'text',$db_opcao," onchange=\"js_data();\"","","","parent.js_data();","js_data();")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted122_d_final?>">
   <?=@$Led122_d_final?>
  </td>
  <td>
   <?db_inputdata('ed122_d_final',@$ed122_d_final_dia,@$ed122_d_final_mes,@$ed122_d_final_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted122_t_obs?>">
   <?=@$Led122_t_obs?>
  </td>
  <td>
   <?db_textarea('ed122_t_obs',2,70,$Ied122_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted122_d_data?>">
   <?=@$Led122_d_data?>
  </td>
  <td>
   <?db_inputdata('ed122_d_data',@$ed122_d_data_dia,@$ed122_d_data_mes,@$ed122_d_data_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed122_i_progmatricula(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?funcao_js=parent.js_mostraprogmatricula1|ed112_i_codigo|ed112_i_rhpessoal|z01_nome|ed112_d_datainicio|ed107_c_descr','Pesquisa Matrículas',true);
 }
}
function js_mostraprogmatricula1(chave1,chave2,chave3,chave4,chave5){
 document.form1.ed122_i_progmatricula.value = chave1;
 document.form1.ed112_i_rhpessoal.value = chave2;
 document.form1.z01_nome.value = chave3;
 document.form1.ed112_d_datainicio_ano.value = chave4.substr(0,4);
 document.form1.ed112_d_datainicio_mes.value = chave4.substr(5,2);
 document.form1.ed112_d_datainicio_dia.value = chave4.substr(8,2);
 document.form1.ed107_c_descr.value = chave5;
 db_iframe_progmatricula.hide();
}
function js_pesquisaed122_i_proglicenca(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_proglicenca','func_proglicenca.php?funcao_js=parent.js_mostraproglicenca1|ed121_i_codigo|ed121_c_descr','Pesquisa de Tipos de Licenças/Afastamentos',true);
 }else{
  if(document.form1.ed122_i_proglicenca.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_proglicenca','func_proglicenca.php?pesquisa_chave='+document.form1.ed122_i_proglicenca.value+'&funcao_js=parent.js_mostraproglicenca','Pesquisa',false);
  }else{
   document.form1.ed121_c_descr.value = '';
  }
 }
}
function js_mostraproglicenca(chave,erro){
 document.form1.ed121_c_descr.value = chave;
 if(erro==true){
  document.form1.ed122_i_proglicenca.focus();
  document.form1.ed122_i_proglicenca.value = '';
 }
}
function js_mostraproglicenca1(chave1,chave2){
 document.form1.ed122_i_proglicenca.value = chave1;
 document.form1.ed121_c_descr.value = chave2;
 db_iframe_proglicenca.hide();
}
function js_data(){
 if(document.form1.ed122_i_progmatricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed122_d_inicio_dia.value = "";
  document.form1.ed122_d_inicio_mes.value = "";
  document.form1.ed122_d_inicio_ano.value = "";
  js_pesquisaed122_i_progmatricula(true);
 }else{
  dataini = document.form1.ed112_d_datainicio_ano.value+document.form1.ed112_d_datainicio_mes.value+document.form1.ed112_d_datainicio_dia.value;
  data = document.form1.ed122_d_inicio_ano.value+document.form1.ed122_d_inicio_mes.value+document.form1.ed122_d_inicio_dia.value;
  if(dataini>data && document.form1.ed122_d_inicio_dia.value!="" && document.form1.ed122_d_inicio_mes.value!="" && document.form1.ed122_d_inicio_ano.value!=""){
   alert("Data Inicial deve ser maior que a Data de Início na Classe!");
   document.form1.ed122_d_inicio_dia.value = "";
   document.form1.ed122_d_inicio_mes.value = "";
   document.form1.ed122_d_inicio_ano.value = "";
   document.form1.ed122_d_inicio_dia.focus();
  }
 }
}

function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_proglicencamatr','func_proglicencamatr.php?funcao_js=parent.js_preenchepesquisa|ed122_i_codigo','Pesquisa Licenças/Afastamentos',true);
}
function js_preenchepesquisa(chave){
 db_iframe_proglicencamatr.hide();
 <?
 if($db_opcao!=1){
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>