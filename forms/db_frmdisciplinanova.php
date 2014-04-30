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
$cldisciplina->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed10_i_codigo");
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
<table border="0" width="650" align="center">
 <tr>
  <td align="center">
   <b>Selecione o ensino:</b>
   <?$query = $clensino->sql_record($clensino->sql_query("","ed10_i_codigo,ed10_c_descr","ed10_c_abrev",""));?>
   <select name="ensino" onchange="js_escolheensino();">
    <option value="">Selecione</option>
    <?for($x=0;$x<$clensino->numrows;$x++){
     db_fieldsmemory($query,$x);?>
     <option value="<?=$ed10_i_codigo?>" <?=@$ensino==$ed10_i_codigo?"selected":""?>><?=$ed10_c_descr?></option>
    <?}?>
   </select>
  </td>
 </tr>
</table>
<?if(@$ensino!=""){
 $query1 = $clensino->sql_record($clensino->sql_query("","ed10_i_codigo as ed12_i_ensino,ed10_c_descr","ed10_c_descr"," ed10_i_codigo = $ensino"));
 db_fieldsmemory($query1,0);
 ?>
 <table border="0" width="650" cellspacing="0">
 <tr>
   <td colspan="2" align="center" valign="top">
    <fieldset style="width:95%"><legend><b>Disciplina</b></legend>
     <table border="0">
      <tr>
       <td nowrap title="<?=@$Ted12_i_ensino?>">
        <?db_ancora(@$Led12_i_ensino,"",3);?>
       </td>
       <td>
        <?db_input('ed12_i_ensino',10,$Ied12_i_ensino,true,'text',3,'')?>
        <?db_input('ed10_c_descr',30,@$Ied10_c_descr,true,'text',3,'')?>
       </td>
      </tr>
      <tr>
       <td nowrap title="<?=@$Ted232_c_descr?>">
        <?=@$Led232_c_descr?>
       </td>
       <td>
        <?db_input('ed232_c_descr',30,$Ied232_c_descr,true,'text',$db_opcao,"")?>
       </td>
      </tr>
      <tr>
       <td nowrap title="<?=@$Ted232_c_abrev?>">
        <?=@$Led232_c_abrev?>
       </td>
       <td>
        <?db_input('ed232_c_abrev',10,$Ied232_c_abrev,true,'text',$db_opcao,"")?>
       </td>
      </tr>
     </table>
   </fieldset>
  </td>
  </tr>
 </table>
 </center>
 <input name="ed12_i_codigo" type="hidden" value="<?=@$ed12_i_codigo?>">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
 <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
 <table>
  <tr>
   <td valign="top">
   <?
    $chavepri= array("ed12_i_codigo"=>@$ed12_i_codigo,"ed232_c_descr"=>@$ed232_c_descr,"ed232_c_abrev"=>@$ed232_c_abrev);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    @$cliframe_alterar_excluir->sql = $cldisciplina->sql_query_file("","*","ed59_i_ordenacao"," ed12_i_ensino = $ensino");
    $cliframe_alterar_excluir->campos  ="ed12_i_codigo,ed232_c_descr,ed232_c_abrev";
    $cliframe_alterar_excluir->legenda="Registros";
    $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec ="#DEB887";
    $cliframe_alterar_excluir->textocorpo ="#444444";
    $cliframe_alterar_excluir->fundocabec ="#444444";
    $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
    $cliframe_alterar_excluir->iframe_height ="150";
    $cliframe_alterar_excluir->iframe_width ="610";
    $cliframe_alterar_excluir->tamfontecabec = 9;
    $cliframe_alterar_excluir->tamfontecorpo = 9;
    $cliframe_alterar_excluir->formulario = false;
    //$cliframe_alterar_excluir->opcoes = 4;
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
   ?>
   </td>
  </tr>
 </table>
<?}?>
</form>
<script>
function js_escolheensino(){
 if(document.form1.ensino.value!=""){
 <?if(isset($nova)){?>
  location.href = "edu1_disciplinanova001.php?ensino="+document.form1.ensino.value+"&nova";
 <?}else{?>
  location.href = "edu1_disciplinanova001.php?ensino="+document.form1.ensino.value;
 <?}?>
 }
}
</script>