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
$clbasempd->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed12_i_codigo");
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
  <td nowrap title="<?=@$Ted35_i_base?>">
   <?db_ancora(@$Led35_i_base,"",3);?>
  </td>
  <td>
   <?db_input('ed35_i_base',10,$Ied35_i_base,true,'text',3,"")?>
   <?db_input('ed31_c_descr',40,@$Ied31_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted35_i_disciplina?>">
   <?db_ancora(@$Led35_i_disciplina,"js_pesquisaed35_i_disciplina(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed35_i_disciplina',10,$Ied35_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed35_i_disciplina(false);'")?>
   <?db_input('ed232_c_descr',40,@$Ied232_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted35_i_qtdperiodo?>">
   <?=@$Led35_i_qtdperiodo?>
  </td>
  <td>
   <?db_input('ed35_i_qtdperiodo',10,$Ied35_i_qtdperiodo,true,'text',$db_opcao,"")?>
   <?//=@$Led35_i_chtotal?>
   <?//db_input('ed35_i_chtotal',10,$Ied35_i_chtotal,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted35_c_condicao?>">
   <?=@$Led35_c_condicao?>
  </td>
  <td>
   <?
   $x = array('OB'=>'OBRIGATÓRIA','OP'=>'OPCIONAL');
   db_select('ed35_c_condicao',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
<input name="ed35_i_codigo" type="hidden" value="<?=@$ed35_i_codigo?>">
<input name="curso" type="hidden" value="<?=$curso?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top"><br>
  <?
   $campos = "ed35_i_codigo,ed35_i_base,ed31_c_descr,ed35_i_disciplina,ed232_c_descr,ed35_i_qtdperiodo,ed35_i_chtotal, case when ed35_c_condicao='OB' then 'OBRIGATÒRIA' else 'OPCIONAL' end as ed35_c_condicao";
   $chavepri= array("ed35_i_codigo"=>@$ed35_i_codigo,"ed31_c_descr"=>@$ed31_c_descr,"ed35_i_disciplina"=>@$ed35_i_disciplina,"ed232_c_descr"=>@$ed232_c_descr,"ed35_i_qtdperiodo"=>@$ed35_i_qtdperiodo,"ed35_i_chtotal"=>@$ed35_i_chtotal,"ed35_c_condicao"=>@$ed35_c_condicao);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clbasempd->sql_query("",$campos,"ed34_i_ordenacao"," ed35_i_base = $ed35_i_base");
   $cliframe_alterar_excluir->campos  ="ed232_c_descr,ed35_i_qtdperiodo,ed35_c_condicao";
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
</form>
</center>
<script>
function js_pesquisaed35_i_disciplina(mostra){
 if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplina.php?disciplinas=<?=$disc_cad?>&curso=<?=$curso?>&funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas',true);
 }else{
  if(document.form1.ed35_i_disciplina.value != ''){
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplina.php?disciplinas=<?=$disc_cad?>&curso=<?=$curso?>&pesquisa_chave='+document.form1.ed35_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
  }else{
   document.form1.ed232_c_descr.value = '';
  }
 }
}
function js_mostradisciplina(chave,erro){
 document.form1.ed232_c_descr.value = chave;
 if(erro==true){
  document.form1.ed35_i_disciplina.focus();
  document.form1.ed35_i_disciplina.value = '';
 }
}
function js_mostradisciplina1(chave1,chave2){
 document.form1.ed35_i_disciplina.value = chave1;
 document.form1.ed232_c_descr.value = chave2;
 db_iframe_disciplina.hide();
}
</script>