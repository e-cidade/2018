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
$clcriaabas     = new cl_criaabas;
$clunidades->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td valign="top">
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Tsd02_i_codigo?>">
      <?=@$Lsd02_i_codigo?>
     </td>
     <td>
      <?db_input('sd02_i_codigo',7,$Isd02_i_codigo,true,'text',3,"")?>
      <?db_input('descrdepto',25,@$Idescrdepto,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_cod_esfadm?>">
      <?db_ancora(@$Lsd02_i_cod_esfadm,"js_pesquisasd02_i_cod_esfadm(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_cod_esfadm',7,$Isd02_i_cod_esfadm,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_cod_esfadm(false);'")?>
      <?db_input('sd37_v_descricao',25,@$Isd37_v_descricao,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_cod_ativ?>">
      <?db_ancora(@$Lsd02_i_cod_ativ,"js_pesquisasd02_i_cod_ativ(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_cod_ativ',7,$Isd02_i_cod_ativ,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_cod_ativ(false);'")?>
      <?db_input('sd38_v_descricao',25,@$Isd38_v_descricao,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_codnivhier?>">
      <?db_ancora(@$Lsd02_i_codnivhier,"js_pesquisasd02_i_codnivhier(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_codnivhier',7,$Isd02_i_codnivhier,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_codnivhier(false);'")?>
      <?db_input('sd44_v_descricao',25,@$Isd44_v_descricao,true,'text',3,"")?>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Tsd02_i_cod_turnat?>">
      <?db_ancora(@$Lsd02_i_cod_turnat,"js_pesquisasd02_i_cod_turnat(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_cod_turnat',7,$Isd02_i_cod_turnat,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_cod_turnat(false);'")?>
      <?db_input('sd43_v_descricao',25,@$Isd43_v_descricao,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_cod_natorg?>">
      <?db_ancora(@$Lsd02_i_cod_natorg,"js_pesquisasd02_i_cod_natorg(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_cod_natorg',7,$Isd02_i_cod_natorg,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_cod_natorg(false);'")?>
      <?db_input('sd40_v_descricao',25,@$Isd40_v_descricao,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_reten_trib?>">
      <?db_ancora(@$Lsd02_i_reten_trib,"js_pesquisasd02_i_reten_trib(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_reten_trib',7,$Isd02_i_reten_trib,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_reten_trib(false);'")?>
      <?db_input('sd39_v_situacao',25,@$Isd39_v_situacao,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_cod_client?>">
      <?db_ancora(@$Lsd02_i_cod_client,"js_pesquisasd02_i_cod_client(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('sd02_i_cod_client',7,$Isd02_i_cod_client,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_cod_client(false);'")?>
      <?db_input('sd41_v_descricao',25,@$Isd41_v_descricao,true,'text',3,"")?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td colspan="2" align="center">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  </td>
 </tr>
</table>
</form>
<form name="formaba">
<table border="0" width="100%">
 <tr>
  <td valign="top">
   <?
   $clcriaabas->abas_top   = "175";
   $clcriaabas->identifica = array("b1"=>"Nível de Atenção","b2"=>"Atendimento Prestado");
   $clcriaabas->sizecampo  = array("b1"=>"30","b2"=>"30");
   $clcriaabas->src        = array("b1"=>"sau1_sau_gestaoativ001.php?sd47_i_unidade=$sd02_i_codigo&descrdepto=$descrdepto","b2"=>"sau1_sau_atendprestund001.php?sd48_i_unidade=$sd02_i_codigo&descrdepto=$descrdepto");
   $clcriaabas->iframe_height= "350";
   $clcriaabas->iframe_width= "90%";
   $clcriaabas->scrolling     = "no";
   $clcriaabas->cria_abas();
   ?>
  <td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisasd02_i_cod_esfadm(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_esferaadmin','func_sau_esferaadmin.php?funcao_js=parent.js_mostrasau_esferaadmin1|sd37_i_cod_esfadm|sd37_v_descricao','Pesquisa de Esfera Administrativa',true);
 }else{
  if(document.form1.sd02_i_cod_esfadm.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_esferaadmin','func_sau_esferaadmin.php?pesquisa_chave='+document.form1.sd02_i_cod_esfadm.value+'&funcao_js=parent.js_mostrasau_esferaadmin','Pesquisa',false);
  }else{
   document.form1.sd37_v_descricao.value = '';
  }
 }
}
function js_mostrasau_esferaadmin(chave,erro){
 document.form1.sd37_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_cod_esfadm.focus();
  document.form1.sd02_i_cod_esfadm.value = '';
 }
}
function js_mostrasau_esferaadmin1(chave1,chave2){
 document.form1.sd02_i_cod_esfadm.value = chave1;
 document.form1.sd37_v_descricao.value = chave2;
 db_iframe_sau_esferaadmin.hide();
 js_escondeframe("visible");
}
function js_pesquisasd02_i_cod_ativ(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_atividadeensino','func_sau_atividadeensino.php?funcao_js=parent.js_mostrasau_atividadeensino1|sd38_i_cod_ativid|sd38_v_descricao','Pesquisa de Atvidades de Ensino',true);
 }else{
  if(document.form1.sd02_i_cod_ativ.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_atividadeensino','func_sau_atividadeensino.php?pesquisa_chave='+document.form1.sd02_i_cod_ativ.value+'&funcao_js=parent.js_mostrasau_atividadeensino','Pesquisa',false);
  }else{
   document.form1.sd38_v_descricao.value = '';
  }
 }
}
function js_mostrasau_atividadeensino(chave,erro){
 document.form1.sd38_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_cod_ativ.focus();
  document.form1.sd02_i_cod_ativ.value = '';
 }
}
function js_mostrasau_atividadeensino1(chave1,chave2){
 document.form1.sd02_i_cod_ativ.value = chave1;
 document.form1.sd38_v_descricao.value = chave2;
 db_iframe_sau_atividadeensino.hide();
 js_escondeframe("visible");
}
function js_pesquisasd02_i_codnivhier(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_nivelhier','func_sau_nivelhier.php?funcao_js=parent.js_mostrasau_nivelhier1|sd44_i_codnivhier|sd44_v_descricao','Pesquisa de Nível Hierárquico',true);
 }else{
  if(document.form1.sd02_i_codnivhier.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_nivelhier','func_sau_nivelhier.php?pesquisa_chave='+document.form1.sd02_i_codnivhier.value+'&funcao_js=parent.js_mostrasau_nivelhier','Pesquisa',false);
  }else{
   document.form1.sd44_v_descricao.value = '';
  }
 }
}
function js_mostrasau_nivelhier(chave,erro){
 document.form1.sd44_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_codnivhier.focus();
  document.form1.sd02_i_codnivhier.value = '';
 }
}
function js_mostrasau_nivelhier1(chave1,chave2){
 document.form1.sd02_i_codnivhier.value = chave1;
 document.form1.sd44_v_descricao.value = chave2;
 db_iframe_sau_nivelhier.hide();
 js_escondeframe("visible");
}
function js_pesquisasd02_i_cod_natorg(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_natorg','func_sau_natorg.php?funcao_js=parent.js_mostrasau_natorg1|sd40_i_cod_natorg|sd40_v_descricao','Pesquisa de Natureza de Organização',true);
 }else{
  if(document.form1.sd02_i_cod_natorg.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_natorg','func_sau_natorg.php?pesquisa_chave='+document.form1.sd02_i_cod_natorg.value+'&funcao_js=parent.js_mostrasau_natorg','Pesquisa',false);
  }else{
   document.form1.sd40_v_descricao.value = '';
  }
 }
}
function js_mostrasau_natorg(chave,erro){
 document.form1.sd40_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_cod_natorg.focus();
  document.form1.sd02_i_cod_natorg.value = '';
 }
}
function js_mostrasau_natorg1(chave1,chave2){
 document.form1.sd02_i_cod_natorg.value = chave1;
 document.form1.sd40_v_descricao.value = chave2;
 db_iframe_sau_natorg.hide();
 js_escondeframe("visible");
}
function js_pesquisasd02_i_reten_trib(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_retentributo','func_sau_retentributo.php?funcao_js=parent.js_mostrasau_retentributo1|sd39_i_cod_reten|sd39_v_situacao','Pesquisa de Retenção de Tributos',true);
 }else{
  if(document.form1.sd02_i_reten_trib.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_retentributo','func_sau_retentributo.php?pesquisa_chave='+document.form1.sd02_i_reten_trib.value+'&funcao_js=parent.js_mostrasau_retentributo','Pesquisa',false);
  }else{
   document.form1.sd39_v_situacao.value = '';
  }
 }
}
function js_mostrasau_retentributo(chave,erro){
 document.form1.sd39_v_situacao.value = chave;
 if(erro==true){
  document.form1.sd02_i_reten_trib.focus();
  document.form1.sd02_i_reten_trib.value = '';
 }
}
function js_mostrasau_retentributo1(chave1,chave2){
 document.form1.sd02_i_reten_trib.value = chave1;
 document.form1.sd39_v_situacao.value = chave2;
 db_iframe_sau_retentributo.hide();
 js_escondeframe("visible");
}
function js_pesquisasd02_i_cod_client(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_fluxocliente','func_sau_fluxocliente.php?funcao_js=parent.js_mostrasau_fluxocliente1|sd41_i_cod_cliente|sd41_v_descricao','Pesquisa de Floxo de Clientela',true);
 }else{
  if(document.form1.sd02_i_cod_client.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_fluxocliente','func_sau_fluxocliente.php?pesquisa_chave='+document.form1.sd02_i_cod_client.value+'&funcao_js=parent.js_mostrasau_fluxocliente','Pesquisa',false);
  }else{
   document.form1.sd41_v_descricao.value = '';
  }
 }
}
function js_mostrasau_fluxocliente(chave,erro){
 document.form1.sd41_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_cod_client.focus();
  document.form1.sd02_i_cod_client.value = '';
 }
}
function js_mostrasau_fluxocliente1(chave1,chave2){
 document.form1.sd02_i_cod_client.value = chave1;
 document.form1.sd41_v_descricao.value = chave2;
 db_iframe_sau_fluxocliente.hide();
 js_escondeframe("visible");
}
function js_pesquisasd02_i_cod_turnat(mostra){
 if(mostra==true){
  js_escondeframe("hidden");
  js_OpenJanelaIframe('','db_iframe_sau_turnoatend','func_sau_turnoatend.php?funcao_js=parent.js_mostrasau_turnoatend1|sd43_cod_turnat|sd43_v_descricao','Pesquisa de Turno de Atendimento',true);
 }else{
  if(document.form1.sd02_i_cod_turnat.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_turnoatend','func_sau_turnoatend.php?pesquisa_chave='+document.form1.sd02_i_cod_turnat.value+'&funcao_js=parent.js_mostrasau_turnoatend','Pesquisa',false);
  }else{
   document.form1.sd43_v_descricao.value = '';
  }
 }
}
function js_mostrasau_turnoatend(chave,erro){
 document.form1.sd43_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_cod_turnat.focus();
  document.form1.sd02_i_cod_turnat.value = '';
 }
}
function js_mostrasau_turnoatend1(chave1,chave2){
 document.form1.sd02_i_cod_turnat.value = chave1;
 document.form1.sd43_v_descricao.value = chave2;
 db_iframe_sau_turnoatend.hide();
 js_escondeframe("visible");
}
function js_escondeframe(situacao){
 document.getElementById('div_b1').style.visibility = situacao;
 document.getElementById('div_b2').style.visibility = situacao;
}
</script>