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
$clescola_sequencias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_opcao1 = 3;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $db_opcao1 = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_opcao1 = 1;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
  $db_opcao1 = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted129_i_codigo?>">
   <?=@$Led129_i_codigo?>
  </td>
  <td>
   <?db_input('ed129_i_codigo',10,$Ied129_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted129_i_escola?>">
   <?db_ancora(@$Led129_i_escola,"js_pesquisaed129_i_escola(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed129_i_escola',10,$Ied129_i_escola,true,'text',$db_opcao1," onchange='js_pesquisaed129_i_escola(false);'")?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted129_i_numinicio?>">
   <?=@$Led129_i_numinicio?>
  </td>
  <td>
   <?db_input('ed129_i_numinicio',20,$Ied129_i_numinicio,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted129_i_numfinal?>">
   <?=@$Led129_i_numfinal?>
  </td>
  <td>
   <?db_input('ed129_i_numfinal',20,$Ied129_i_numfinal,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted129_c_ativo?>">
   <?=@$Led129_c_ativo?>
  </td>
  <td>
   <?
   $x = array('S'=>'SIM','N'=>'NÃO');
   db_select('ed129_c_ativo',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed129_i_codigo"=>@$ed129_i_codigo,"ed129_i_escola"=>@$ed129_i_escola,"ed18_c_nome"=>@$ed18_c_nome,"ed129_i_numinicio"=>@$ed129_i_numinicio,"ed129_i_numfinal"=>@$ed129_i_numfinal);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clescola_sequencias->sql_query($ed129_i_codigo,"*","ed129_i_numinicio");
   $cliframe_alterar_excluir->campos  ="ed129_i_codigo,ed129_i_escola,ed18_c_nome,ed129_i_numinicio,ed129_i_numfinal,ed129_c_ativo";
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
   $cliframe_alterar_excluir->opcoes = 2;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_pesquisaed129_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_escola','func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome','Pesquisa de Escolas',true);
 }else{
  if(document.form1.ed129_i_escola.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_escola','func_escola.php?pesquisa_chave='+document.form1.ed129_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa',false);
  }else{
   document.form1.ed18_c_nome.value = '';
  }
 }
}
function js_mostraescola(chave,erro){
 document.form1.ed18_c_nome.value = chave;
 if(erro==true){
  document.form1.ed129_i_escola.focus();
  document.form1.ed129_i_escola.value = '';
 }
}
function js_mostraescola1(chave1,chave2){
 document.form1.ed129_i_escola.value = chave1;
 document.form1.ed18_c_nome.value = chave2;
 db_iframe_escola.hide();
}
</script>