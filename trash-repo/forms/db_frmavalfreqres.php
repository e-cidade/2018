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
$clavalfreqres->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed41_i_codigo");
$clrotulo->label("ed43_i_codigo");
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
if(isset($ed67_i_procresultado) && !isset($excluir)){
 $result = $clavalfreqres->sql_record($clavalfreqres->sql_query("","ed41_i_codigo as perjacad",""," ed67_i_procresultado = $ed67_i_procresultado"));
 if($clavalfreqres->numrows>0){
  $sep = "";
  $per_cad = "";
  for($c=0;$c<$clavalfreqres->numrows;$c++){
   db_fieldsmemory($result,$c);
   $per_cad .= $sep.$perjacad;
   $sep = ",";
  }
 }else{
  $per_cad = 0;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap >
   <table border="0" width="100%">
    <tr>
     <td nowrap title="<?=@$Ted67_i_codigo?>">
      <?=@$Led67_i_codigo?>
     </td>
     <td>
      <?db_input('ed67_i_codigo',15,$Ied67_i_codigo,true,'text',3,"")
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted67_i_procresultado?>">
      <?db_ancora(@$Led67_i_procresultado,"",3);?>
     </td>
     <td>
      <?db_input('ed67_i_procresultado',15,$Ied67_i_procresultado,true,'text',3,"")?>
      <?db_input('ed42_c_descr',25,@$Ied42_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted67_i_procavaliacao?>">
      <?db_ancora(@$Led67_i_procavaliacao,"js_pesquisaed67_i_procavaliacao(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed67_i_procavaliacao',15,$Ied67_i_procavaliacao,true,'text',3,"")?>
      <?db_input('ed09_c_descr',25,@$Ied09_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <input type="hidden" name="procedimento" value="<?=@$procedimento?>">
      <input type="hidden" name="forma" value="<?=@$forma?>">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table width="100%">
    <tr>
     <td valign="top">
     <?
      $chavepri= array("ed67_i_codigo"=>@$ed67_i_codigo,"ed67_i_procresultado"=>@$ed67_i_procresultado,"ed42_c_descr"=>@$ed42_c_descr,"ed67_i_procavaliacao"=>@$ed67_i_procavaliacao,"ed09_c_descr"=>@$ed09_c_descr);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      @$cliframe_alterar_excluir->sql = $clavalfreqres->sql_query("","*","ed41_i_sequencia"," ed67_i_procresultado = $ed67_i_procresultado");
      $cliframe_alterar_excluir->campos  ="ed09_c_descr";
      $cliframe_alterar_excluir->legenda="Registros";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="#DEB887";
      $cliframe_alterar_excluir->textocorpo ="#444444";
      $cliframe_alterar_excluir->fundocabec ="#444444";
      $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
      $cliframe_alterar_excluir->iframe_height ="100";
      $cliframe_alterar_excluir->iframe_width ="100%";
      $cliframe_alterar_excluir->tamfontecabec = 9;
      $cliframe_alterar_excluir->tamfontecorpo = 9;
      $cliframe_alterar_excluir->opcoes = 3;
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed67_i_procavaliacao(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_procavaliacao','func_procavaliacaofreq.php?periodos=<?=@$per_cad?>&procedimento=<?=$procedimento?>&funcao_js=parent.js_mostraprocavaliacao1|ed41_i_codigo|ed09_c_descr','Pesquisa de Avaliações Períodicas',true,0,0,770,90);
 }else{
  if(document.form1.ed67_i_procavaliacao.value != ''){
   js_OpenJanelaIframe('','db_iframe_procavaliacao','func_procavaliacaofreq.php?periodos=<?=@$per_cad?>&procedimento=<?=$procedimento?>&pesquisa_chave='+document.form1.ed67_i_procavaliacao.value+'&funcao_js=parent.js_mostraprocavaliacao','Pesquisa',false);
  }else{
   document.form1.ed09_c_descr.value = '';
  }
 }
}
function js_mostraprocavaliacao(chave,erro){
 document.form1.ed09_c_descr.value = chave;
 if(erro==true){
   document.form1.ed67_i_procavaliacao.focus();
   document.form1.ed67_i_procavaliacao.value = '';
 }
}
function js_mostraprocavaliacao1(chave1,chave2){
 document.form1.ed67_i_procavaliacao.value = chave1;
 document.form1.ed09_c_descr.value = chave2;
 db_iframe_procavaliacao.hide();
}
</script>