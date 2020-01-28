<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clparecerturma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed92_i_codigo");
?>
<form name="form1" method="post" action="">
<table border="1" width="100%" cellspacing="0" cellpading="0">
 <tr>
  <td class='titulo' colspan="3">
   Pareceres para a turma <?=$ed57_c_descr?>
  </td>
 </tr>
 <tr>
  <td align="center" width="5%" class='cabec1'>
   <input type="checkbox" name="todos" value="" onclick="MarcaTodos();">
  </td>
  <td align="center" width="5%" class='cabec1'>Sequencial</td>
  <td class='cabec1'>Parecer</td>
 </tr>
 <?
 $escola = db_getsession("DB_coddepto");
 $result = $clparecer->sql_record($clparecer->sql_query("","*","ed92_i_sequencial"," ed92_i_escola = $escola"));
 $cor1 = "#f3f3f3";
 $cor2 = "#DBDBDB";
 $cor = "";
 if($clparecer->numrows>0){
  for($x=0;$x<$clparecer->numrows;$x++){
   db_fieldsmemory($result,$x);
   $result1 = $clparecerturma->sql_record($clparecerturma->sql_query_file("","*","","ed105_i_parecer = $ed92_i_codigo AND ed105_i_turma = $ed105_i_turma"));
   if($clparecerturma->numrows>0){
    db_fieldsmemory($result1,0);
    $checked = "checked";
    $disabled = "";
    $classe = "aluno1";
   }else{
    $checked = "";
    $disabled = "disabled";
    $classe = "aluno";
   }
   if($cor==$cor1){
    $cor = $cor2;
   }else{
    $cor = $cor1;
   }
   ?>
   <tr bgcolor="<?=$cor?>" height="10">
    <td align="center" ><input type="checkbox" id="unidade" name="<?=$ed92_i_codigo?>" value="ativo" <?=$checked?>></td>
    <td align="center" class='<?=$classe?>'><?=$ed92_i_sequencial==""?"&nbsp;":$ed92_i_sequencial?></td>
    <td class='<?=$classe?>'><?=$checked!=""?" -> ":""?><?=$ed92_c_descr?></td>
   </tr>
   <?
  }
  ?>
  <tr>
   <td colspan="3" align="center">
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Salvar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
   </td>
  </tr>
  <?
 }else{
  ?>
  <tr>
   <td colspan="3" align="center" bgcolor="#f3f3f3">
    Nenhum parecer cadastrado.
   </td>
  </tr>
  <?
 }
 ?>
</table>
<input name="ed57_i_codigo" type="hidden" value="<?=$ed105_i_turma?>" />
<input name="ed57_c_descr" type="hidden" value="<?=$ed57_c_descr?>" />
<input name="ed52_c_descr" type="hidden" value="<?=$ed52_c_descr?>" />
</form>
<script>
function MarcaTodos(){
 qtd = document.form1.unidade.length;
 if(document.form1.todos.checked==true){
  for(i=0;i<qtd;i++){
   document.form1.unidade[i].checked = true;
  }
 }else{
  for(i=0;i<qtd;i++){
   document.form1.unidade[i].checked = false;
  }
 }
}
</script>