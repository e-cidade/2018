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
$clprocrecomendacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed40_i_codigo");
$clrotulo->label("ed46_i_codigo");
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
  <td nowrap title="<?=@$Ted51_i_codigo?>">
   <?=@$Led51_i_codigo?>
  </td>
  <td>
   <?db_input('ed51_i_codigo',10,$Ied51_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted51_i_procedimento?>">
   <?db_ancora(@$Led51_i_procedimento,"",3);?>
  </td>
  <td>
   <?db_input('ed51_i_procedimento',10,$Ied51_i_procedimento,true,'text',3,"")?>
   <?db_input('ed40_c_descr',30,@$Ied40_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted51_i_recomendacao?>">
   <?db_ancora(@$Led51_i_recomendacao,"js_pesquisaed51_i_recomendacao(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed51_i_recomendacao',10,$Ied51_i_recomendacao,true,'text',$db_opcao," onchange='js_pesquisaed51_i_recomendacao(false);'")?>
   <?db_input('ed46_c_descr',60,@$Ied46_c_descr,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed51_i_codigo"=>@$ed51_i_codigo,"ed40_c_descr"=>@$ed40_c_descr,"ed51_i_procedimento"=>@$ed51_i_procedimento,"ed51_i_recomendacao"=>@$ed51_i_recomendacao,"ed46_c_descr"=>@$ed46_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clprocrecomendacao->sql_query("","*","ed46_c_descr"," ed51_i_procedimento = $ed51_i_procedimento");
   $cliframe_alterar_excluir->campos  ="ed46_i_codigo,ed46_c_descr";
   $cliframe_alterar_excluir->labels  ="ed46_i_codigo,ed51_i_recomendacao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
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
function js_pesquisaed51_i_recomendacao(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_recomendacao','func_recomendacao.php?recomendacoes=<?=$rec_cad?>&funcao_js=parent.js_mostrarecomendacao1|ed46_i_codigo|ed46_c_descr','Pesquisa de Recomendações',true);
 }else{
  if(document.form1.ed51_i_recomendacao.value != ''){
   js_OpenJanelaIframe('','db_iframe_recomendacao','func_recomendacao.php?recomendacoes=<?=$rec_cad?>&pesquisa_chave='+document.form1.ed51_i_recomendacao.value+'&funcao_js=parent.js_mostrarecomendacao','Pesquisa',false);
  }else{
   document.form1.ed46_c_descr.value = '';
  }
 }
}
function js_mostrarecomendacao(chave,erro){
 document.form1.ed46_c_descr.value = chave;
 if(erro==true){
  document.form1.ed51_i_recomendacao.focus();
  document.form1.ed51_i_recomendacao.value = '';
 }
}
function js_mostrarecomendacao1(chave1,chave2){
 document.form1.ed51_i_recomendacao.value = chave1;
 document.form1.ed46_c_descr.value = chave2;
 db_iframe_recomendacao.hide();
}
</script>