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
$clprogconhec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed112_d_datainicio");
$clrotulo->label("ed112_i_progclasse");
$clrotulo->label("ed112_c_situacao");
if(isset($ed112_c_situacao) && trim($ed112_c_situacao)!="A"){
 $db_botao = false;
}
if($ed110_i_ptconhecimento==0 || $ed110_i_ptgeral==0){
 db_msgbox("Pontuação para Conhecimento ou Pontuação Geral está com valor zero! (Configurações)");
 $db_opcao = 3;
 $db_opcao1 = 3;
 $db_botao = false;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted114_i_codigo?>">
   <?=@$Led114_i_codigo?>
  </td>
  <td>
   <?db_input('ed114_i_codigo',10,$Ied114_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_i_progmatricula?>">
   <?db_ancora(@$Led114_i_progmatricula,"js_pesquisaed114_i_progmatricula(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed114_i_progmatricula',10,$Ied114_i_progmatricula,true,'hidden',3,"")?>
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
    }elseif(@$ed112_c_situacao=="E"){
     $ed112_c_situacao = "ENCERRADA";
    }
    ?>
    <?=@$Led112_c_situacao?>
    <input name="ed112_c_situacao" type="text" value="<?=@$ed112_c_situacao?>" style="background:#DEB887;" readonly>
   <?}?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_i_tipoconhecimento?>">
   <?db_ancora(@$Led114_i_tipoconhecimento,"js_pesquisaed114_i_tipoconhecimento(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed114_i_tipoconhecimento',10,$Ied114_i_tipoconhecimento,true,'text',$db_opcao," onchange='js_pesquisaed114_i_tipoconhecimento(false);'")?>
   <?db_input('ed109_c_descr',40,@$Ied109_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_d_data?>">
   <?=@$Led114_d_data?>
  </td>
  <td>
   <?db_inputdata('ed114_d_data',@$ed114_d_data_dia,@$ed114_d_data_mes,@$ed114_d_data_ano,true,'text',$db_opcao," onchange=\"js_data();\"","","","parent.js_data();","js_data();")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_i_ano?>">
   <?=@$Led114_i_ano?>
  </td>
  <td>
   <?db_input('ed114_i_ano',4,$Ied114_i_ano,true,'text',$db_opcao," onChange='js_valida(this.value);'")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_f_cargahoraria?>">
   <?=@$Led114_f_cargahoraria?>
  </td>
  <td>
   <?db_input('ed114_f_cargahoraria',10,$Ied114_f_cargahoraria,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_t_obs?>">
   <?=@$Led114_t_obs?>
  </td>
  <td>
   <?db_textarea('ed114_t_obs',2,97,$Ied114_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted114_c_orgao?>">
   <?=@$Led114_c_orgao?>
  </td>
  <td>
   <?db_input('ed114_c_orgao',100,$Ied114_c_orgao,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <!--
 <tr>
  <td nowrap title="<?=@$Ted114_c_arquivo?>">
   <?=@$Led114_c_arquivo?>
  </td>
  <td>
   <?db_input('ed114_c_arquivo',100,$Ied114_c_arquivo,true,'file',$db_opcao,"")?>
  </td>
 </tr>
 -->
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed114_i_progmatricula(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula.php?funcao_js=parent.js_mostraprogmatricula1|ed112_i_codigo|ed112_i_rhpessoal|z01_nome|ed112_d_datainicio|ed107_c_descr','Pesquisa de Matrículas',true);
 }
}
function js_mostraprogmatricula1(chave1,chave2,chave3,chave4,chave5){
 document.form1.ed114_i_progmatricula.value = chave1;
 document.form1.ed112_i_rhpessoal.value = chave2;
 document.form1.z01_nome.value = chave3;
 document.form1.ed112_d_datainicio_ano.value = chave4.substr(0,4);
 document.form1.ed112_d_datainicio_mes.value = chave4.substr(5,2);
 document.form1.ed112_d_datainicio_dia.value = chave4.substr(8,2);
 document.form1.ed107_c_descr.value = chave5;
 db_iframe_progmatricula.hide();
}
function js_pesquisaed114_i_tipoconhecimento(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_tipoconhecimento','func_tipoconhecimento.php?funcao_js=parent.js_mostratipoconhecimento1|ed109_i_codigo|ed109_c_descr','Pesquisa de Tipos de Conhecimentos',true);
 }else{
  if(document.form1.ed114_i_tipoconhecimento.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_tipoconhecimento','func_tipoconhecimento.php?pesquisa_chave='+document.form1.ed114_i_tipoconhecimento.value+'&funcao_js=parent.js_mostratipoconhecimento','Pesquisa',false);
  }else{
   document.form1.ed109_c_descr.value = '';
  }
 }
}
function js_mostratipoconhecimento(chave,erro){
 document.form1.ed109_c_descr.value = chave;
 if(erro==true){
  document.form1.ed114_i_tipoconhecimento.focus();
  document.form1.ed114_i_tipoconhecimento.value = '';
 }
}
function js_mostratipoconhecimento1(chave1,chave2){
 document.form1.ed114_i_tipoconhecimento.value = chave1;
 document.form1.ed109_c_descr.value = chave2;
 db_iframe_tipoconhecimento.hide();
}
function js_valida(ano){
 if(document.form1.ed114_i_progmatricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed114_i_ano.value = "";
  js_pesquisaed114_i_progmatricula(true);
 }else{
  if(ano.length<4){
   alert("Ano deve ser digitado com 4 dígitos!");
   document.form1.ed114_i_ano.value = "";
  }else{
   if(document.form1.ed114_i_ano.value<document.form1.ed112_d_datainicio_ano.value){
    alert("Ano Referente deve ser maior ou igual ao ano da Data de Início!");
    document.form1.ed114_i_ano.value = "";
   }else if(document.form1.ed114_i_ano.value!=document.form1.ed114_d_data_ano.value){
    alert("Ano Referente deve igual ao ano da Data!");
    document.form1.ed114_i_ano.value = "";
   }else{
    iframe_valida.location.href = "edu1_progantig004.php?matricula="+document.form1.ed114_i_progmatricula.value+"&ano="+ano;
   }
  }
 }
}
function js_data(){
 if(document.form1.ed114_i_progmatricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed114_d_data_dia.value = "";
  document.form1.ed114_d_data_mes.value = "";
  document.form1.ed114_d_data_ano.value = "";
  js_pesquisaed114_i_progmatricula(true);
 }else{
  dataini = document.form1.ed112_d_datainicio_ano.value+document.form1.ed112_d_datainicio_mes.value+document.form1.ed112_d_datainicio_dia.value;
  data = document.form1.ed114_d_data_ano.value+document.form1.ed114_d_data_mes.value+document.form1.ed114_d_data_dia.value;
  if(dataini>data && document.form1.ed114_d_data_dia.value!="" && document.form1.ed114_d_data_mes.value!="" && document.form1.ed114_d_data_ano.value!=""){
   alert("Data deve ser maior que a Data de Início na Classe!");
   document.form1.ed114_d_data_dia.value = "";
   document.form1.ed114_d_data_mes.value = "";
   document.form1.ed114_d_data_ano.value = "";
   document.form1.ed114_d_data_dia.focus();
  }
 }
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_progconhec','func_progconhec.php?funcao_js=parent.js_preenchepesquisa|ed114_i_codigo','Pesquisa de Conhecimentos',true);
}
function js_preenchepesquisa(chave){
 db_iframe_progconhec.hide();
 <?
 if($db_opcao!=1){
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>