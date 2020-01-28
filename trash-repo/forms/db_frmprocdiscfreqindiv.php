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
$clprocdiscfreqindiv->rotulo->label();
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
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
$result1 = $clprocdiscfreqindiv->sql_record($clprocdiscfreqindiv->sql_query("","ed45_i_disciplina as discjacad",""," ed45_i_procedimento = $ed45_i_procedimento"));
if($clprocdiscfreqindiv->numrows>0){
 $sep = "";
 $disc_cad = "";
 for($c=0;$c<$clprocdiscfreqindiv->numrows;$c++){
  db_fieldsmemory($result1,$c);
  $disc_cad .= $sep.$discjacad;
  $sep = ",";
 }
}else{
 $disc_cad = 0;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" align="left" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted45_i_disciplina?>" width="40%">
   <?db_ancora("<b>Disciplinas com controle de frequência individual:</b>","js_pesquisaed45_i_disciplina(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed45_i_disciplina',10,$Ied45_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed45_i_disciplina(false);'")?>
   <?db_input('ed232_c_descr',30,@$Ied232_c_descr,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="ed45_i_codigo" type="hidden" value="<?=@$ed45_i_codigo?>">
<input name="ed45_i_procedimento" type="hidden" value="<?=@$ed45_i_procedimento?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed45_i_codigo"=>@$ed45_i_codigo,"ed45_i_disciplina"=>@$ed45_i_disciplina,"ed232_c_descr"=>@$ed232_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clprocdiscfreqindiv->sql_query("","*","ed59_i_ordenacao"," ed45_i_procedimento = $ed45_i_procedimento");
   $cliframe_alterar_excluir->campos  ="ed232_c_descr,ed10_c_descr";
   $cliframe_alterar_excluir->labels  ="ed45_i_disciplina,ed12_i_ensino";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="70";
   $cliframe_alterar_excluir->iframe_width ="600";
   $cliframe_alterar_excluir->opcoes = 3;
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
function js_pesquisaed45_i_disciplina(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('parent','db_iframe_disciplina','func_disciplinageral.php?disciplinas=<?=$disc_cad?>&funcao_js=parent.iframe_disc.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas',true);
 }else{
  if(document.form1.ed45_i_disciplina.value != ''){
   js_OpenJanelaIframe('parent','db_iframe_disciplina','func_disciplinageral.php?disciplinas=<?=$disc_cad?>&pesquisa_chave='+document.form1.ed45_i_disciplina.value+'&funcao_js=parent.iframe_disc.js_mostradisciplina','Pesquisa',false);
  }else{
   document.form1.ed232_c_descr.value = '';
  }
 }
}
function js_mostradisciplina(chave,erro){
 document.form1.ed232_c_descr.value = chave;
 if(erro==true){
  document.form1.ed45_i_disciplina.focus();
  document.form1.ed45_i_disciplina.value = '';
 }
}
function js_mostradisciplina1(chave1,chave2){
 document.form1.ed45_i_disciplina.value = chave1;
 document.form1.ed232_c_descr.value = chave2;
 parent.db_iframe_disciplina.hide();
}
</script>