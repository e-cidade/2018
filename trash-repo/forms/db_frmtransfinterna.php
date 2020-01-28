<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clmatricula->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed57_i_serie");
$clrotulo->label("ed57_i_turno");
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed57_i_numvagas");
$clrotulo->label("ed57_i_nummatr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td colspan="3">
   <fieldset style="width:97%"><legend><b>Turma</b></legend>
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Ted60_i_turma?>">
      <?db_ancora(@$Led60_i_turma,"js_pesquisaed60_i_turma();",$db_opcao==3?$db_opcao1:$db_opcao);?>
     </td>
     <td>
      <?db_input('ed60_i_turma',10,$Ied60_i_turma,true,'text',3,'')?>
      <?db_input('ed57_c_descr',20,@$Ied57_c_descr,true,'text',3,'')?>
      <?=@$Led57_i_calendario?>
      <?db_input('ed52_c_descr',20,@$Ied52_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted31_i_curso?>">
      <?=@$Led31_i_curso?>
     </td>
     <td>
      <?db_input('ed29_c_descr',40,@$Ied29_c_descr,true,'text',3,'')?>
      <?=@$Led57_i_serie?>
      <?db_input('ed11_c_descr',20,@$Ied11_c_descr,true,'text',3,'')?>
      <?=@$Led57_i_turno?>
      <?db_input('ed15_c_nome',20,@$Ied15_c_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted31_i_curso?>">
      <?=@$Led57_i_numvagas?>
     </td>
     <td>
      <?db_input('ed57_i_numvagas',10,@$Ied57_i_numvagas,true,'text',3,'')?>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <?=@$Led57_i_nummatr?>
      <?db_input('ed57_i_nummatr',10,@$Ied57_i_nummatr,true,'text',3,'')?>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <b>Vagas Disponíveis:</b>
      <?db_input('restantes',10,@$Irestantes,true,'text',3,'')?>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
 <?if(isset($chavepesquisa) && $db_opcao==1){?>
 <tr>
  <td valign="top">
   <?
   $result = $clturma->sql_record($clturma->sql_query("","ed57_i_base as base,ed57_i_escola as escola,ed57_i_serie as serie,ed57_i_turno as turno,ed57_i_calendario as calendario",""," ed57_i_codigo = $ed60_i_turma"));
   db_fieldsmemory($result,0);
   $result = $clcalendario->sql_record($clcalendario->sql_query_file("","ed52_i_calendant",""," ed52_i_codigo = $calendario"));
   db_fieldsmemory($result,0);
   $ed52_i_calendant = $ed52_i_calendant==""?"0":$ed52_i_calendant;
   $result1 = $claluno->sql_record($claluno->sql_query_matr("","DISTINCT ed47_i_codigo,z01_nome,ed56_c_situacao ","z01_nome"," (ed56_i_escola = $escola AND ed56_i_base = $base AND ed56_c_situacao = 'CANDIDATO' AND ed79_i_serie = $serie AND ed56_i_calendario = $calendario) OR  (ed56_i_escola = $escola AND ed56_i_base = $base AND ed56_c_situacao = 'AVANÇO' AND ed79_i_serie = $serie AND ed56_i_calendario = $ed52_i_calendant) OR  (ed56_i_escola = $escola AND ed56_i_base = $base AND ed56_c_situacao = 'REPETENTE' AND ed79_i_serie = $serie AND ed56_i_calendario = $ed52_i_calendant)"));
   //db_criatabela($result1);
   ?>
   <b>Alunos em condição de matrícula:</b>
   <select name="alunospossib" id="alunospossib" size="10" onclick="js_desabinc()" style="font-size:9px;width:330px;height:180px" multiple>
    <?
    if($claluno->numrows>0){
     for($i=0;$i<$claluno->numrows;$i++) {
      db_fieldsmemory($result1,$i);
      echo "<option value='$ed47_i_codigo'>$z01_nome --> $ed56_c_situacao</option>\n";
     }
    }
    ?>
   </select>
  </td>
  <td align="center">
   <br>
   <table border="0">
    <tr>
     <td>
      <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;">
     </td>
    </tr>
    <tr><td height="8"></td></tr>
    <tr>
     <td>
      <hr>
     </td>
    </tr>
    <tr><td height="8"></td></tr>
    <tr>
     <td>
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table>
    <tr>
     <td valign="top">
      <b>Matricular na turma <?=@$ed57_c_descr?>:</b>
      <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" style="font-size:9px;width:330px;height:180px" multiple>
      </select>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?}?>
 <?if($db_opcao==3){
 $excluir = "yes";?>
 <tr>
  <td width="15%" nowrap title="<?=@$Ted60_matricula?>" colspan="2">
   <?=@$Led60_matricula?>
   <?db_input('ed60_matricula',10,$Ied60_matricula,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted60_i_aluno?>">
   <?db_ancora(@$Led60_i_aluno,"js_pesquisaed60_i_alunoexc(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed60_i_aluno',10,$Ied60_i_aluno,true,'text',$db_opcao1," onchange='js_pesquisaed60_i_alunoexc(false);'")?>
   <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <script>
 js_tabulacaoforms("form1","ed60_i_aluno",true,1,"ed60_i_aluno",true);
 </script>
 <?}?>
 <?if($db_opcao==2){
 $excluir = "no";?>
 <tr>
  <td width="15%" nowrap title="<?=@$Ted60_matricula?>" colspan="2">
   <?=@$Led60_matricula?>
   <?db_input('ed60_matricula',10,$Ied60_matricula,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted60_i_aluno?>">
   <?db_ancora(@$Led60_i_aluno,"js_pesquisaed60_i_aluno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed60_i_aluno',10,$Ied60_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed60_i_aluno(false);'")?>
   <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Situação Atual:</b>
  </td>
  <td>
   <?db_input('ed60_c_situacaoatual',20,'',true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted60_c_situacao?>">
   <?=@$Led60_c_situacao?>
  </td>
  <td>
   <?
   $x = array();
   db_select('ed60_c_situacao',$x,true,$db_opcao," disabled");
   ?>
  </td>
 </tr>
 <script>
 js_tabulacaoforms("form1","ed60_i_aluno",true,1,"ed60_i_aluno",true);
 </script>
 <?}?>
 <?if($db_opcao==1){?>
 <tr>
  <td nowrap title="<?=@$Ted60_d_datamatricula?>" colspan="2">
   <?=@$Led60_d_datamatricula?>
   <?db_inputdata('ed60_d_datamatricula',@$ed60_d_datamatricula_dia,@$ed60_d_datamatricula_mes,@$ed60_d_datamatricula_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <?}?>
 <?if($db_opcao==2){?>
 <tr>
  <td nowrap title="<?=@$Ted60_d_datamodif?>">
   <?=@$Led60_d_datamodif?>
  </td>
  <td>
   <?db_inputdata('ed60_d_datamodif',@$ed60_d_datamodif_dia,@$ed60_d_datamodif_mes,@$ed60_d_datamodif_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <?}?>
 <tr>
  <td>
   <iframe name="verifmatricula" id="verifmatricula" src="" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
  </td>
 </tr>
</table>
<input name="ed57_i_escola" type="hidden" value="<?=@$ed57_i_escola?>">
<input name="ed57_i_base" type="hidden" value="<?=@$ed57_i_base?>">
<input name="ed57_i_calendario" type="hidden" value="<?=@$ed57_i_calendario?>">
<input name="ed57_i_serie" type="hidden" value="<?=@$ed57_i_serie?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=$db_opcao==1?" onClick='js_selecionar()'":""?> >
</form>
</center>
<script>
function js_pesquisaed60_i_aluno(mostra){
 if(document.form1.ed60_i_turma.value==""){
  alert("Informe a Turma!");
  document.form1.ed60_i_aluno.value = '';
  document.form1.ed60_i_turma.style.backgroundColor='#99A9AE';
  document.form1.ed60_i_turma.focus();
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_matricula.php?excluir=<?=@$excluir?>&turma='+document.form1.ed60_i_turma.value+'&funcao_js=parent.js_mostraaluno1|ed60_matricula|ed60_i_aluno|z01_nome|dl_situação','Pesquisa de Alunos',true);
  }else{
   if(document.form1.ed60_i_aluno.value != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_matricula.php?excluir=<?=@$excluir?>&turma='+document.form1.ed60_i_turma.value+'&pesquisa_chave='+document.form1.ed60_i_aluno.value+'&funcao_js=parent.js_mostraaluno','Pesquisa',false);
   }else{
    document.form1.z01_nome.value = '';
   }
  }
 }
}
function js_mostraaluno(chave1,chave2,chave3,erro){
 document.form1.z01_nome.value = chave1;
 document.form1.ed60_matricula.value = chave2;
 document.form1.ed60_c_situacaoatual.value = chave3;
 if(erro==true){
  document.form1.ed60_i_aluno.focus();
  document.form1.ed60_i_aluno.value = '';
  document.form1.alterar.disabled = true;
 }else{
  document.form1.alterar.disabled = false;
  js_situacao(chave3);
 }
}
function js_mostraaluno1(chave1,chave2,chave3,chave4){
 document.form1.ed60_matricula.value = chave1;
 document.form1.ed60_i_aluno.value = chave2;
 document.form1.z01_nome.value = chave3;
 document.form1.ed60_c_situacaoatual.value = chave4;
 document.form1.alterar.disabled = false;
 js_situacao(chave4);
 db_iframe_aluno.hide();
}
function js_pesquisaed60_i_alunoexc(mostra){
 if(document.form1.ed60_i_turma.value==""){
  alert("Informe a Turma!");
  document.form1.ed60_i_aluno.value = '';
  document.form1.ed60_i_turma.style.backgroundColor='#99A9AE';
  document.form1.ed60_i_turma.focus();
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_matricula.php?excluir=<?=@$excluir?>&turma='+document.form1.ed60_i_turma.value+'&funcao_js=parent.js_mostraalunoexc1|ed60_matricula|ed60_i_aluno|z01_nome|dl_situação','Pesquisa de Alunos',true);
  }else{
   if(document.form1.ed60_i_aluno.value != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_matricula.php?excluir=<?=@$excluir?>&turma='+document.form1.ed60_i_turma.value+'&pesquisa_chave='+document.form1.ed60_i_aluno.value+'&funcao_js=parent.js_mostraalunoexc','Pesquisa',false);
   }else{
    document.form1.z01_nome.value = '';
   }
  }
 }
}
function js_mostraalunoexc(chave1,chave2,chave3,erro){
 document.form1.z01_nome.value = chave1;
 document.form1.ed60_matricula.value = chave2;
 if(erro==true){
  document.form1.ed60_i_aluno.focus();
  document.form1.ed60_i_aluno.value = '';
  document.form1.excluir.disabled = true;
 }else{
  document.form1.excluir.disabled = false;
 }
}
function js_mostraalunoexc1(chave1,chave2,chave3,chave4){
 document.form1.ed60_matricula.value = chave1;
 document.form1.ed60_i_aluno.value = chave2;
 document.form1.z01_nome.value = chave3;
 document.form1.excluir.disabled = false;
 db_iframe_aluno.hide();
}
function js_pesquisaed60_i_turma(){
 js_OpenJanelaIframe('top.corpo','db_iframe_turma','func_turma.php?funcao_js=parent.js_preenchepesquisaturma|ed57_i_codigo','Pesquisa de Turmas',true);
}
function js_preenchepesquisaturma(chave){
 db_iframe_turma.hide();
 <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 ?>
}
function js_calcvagas(){
 if(document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value<0){
  document.form1.restantes.value = 0;
 }else{
  document.form1.restantes.value = document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value;
 }
}
function js_situacao(atual){
 var F = document.getElementById("ed60_c_situacao");
 for(i=0;i<F.length;i++){
  F.options[i] = null;
 }
 if(atual=="MATRICULADO"){
  opcoes = new Array("CANCELADO","FALECIDO","EVADIDO");
 }else if(atual=="CANCELADO"){
  opcoes = new Array("MATRICULADO","FALECIDO","EVADIDO");
 }else if(atual=="EVADIDO"){
  opcoes = new Array("MATRICULADO","FALECIDO","CANCELADO");
 }else if(atual=="FALECIDO"){
  opcoes = new Array("FALECIDO");
 }
 for(i=0;i<opcoes.length;i++){
  document.form1.elements["ed60_c_situacao"].options[i] = new Option(opcoes[i],opcoes[i]);
  if(opcoes[i]==atual){
   F.options[i] = null;
  }
 }
 for(i=0;i<F.length;i++){
  if(F.options[i].text==atual){
   F.options[i] = null;
  }
 }
 document.form1.ed60_c_situacao.disabled = false;
}
function js_incluir() {
 var Tam = document.form1.alunospossib.length;
 var F = document.form1;
 for(x=0;x<Tam;x++){
  if(F.alunospossib.options[x].selected==true){
   F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunospossib.options[x].text,F.alunospossib.options[x].value)
   F.alunospossib.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunospossib.length>0){
  document.form1.alunospossib.options[0].selected = true;
 }else{
  document.form1.incluirum.disabled = true;
  document.form1.incluirtodos.disabled = true;
 }
 document.form1.incluir.disabled = false;
 document.form1.excluirtodos.disabled = false;
 document.form1.alunospossib.focus();
}
function js_incluirtodos() {
 var Tam = document.form1.alunospossib.length;
 var F = document.form1;
 for(i=0;i<Tam;i++){
  F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunospossib.options[0].text,F.alunospossib.options[0].value);
  F.alunospossib.options[0] = null;
 }
 document.form1.incluirum.disabled = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 document.form1.incluir.disabled = false;
 document.form1.alunos.focus();
}
function js_excluir() {
 var F = document.getElementById("alunos");
 Tam = F.length;
 for(x=0;x<Tam;x++){
  if(F.options[x].selected==true){
   document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[x].text,F.options[x].value);
   F.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunos.length>0){
  document.form1.alunos.options[0].selected = true;
 }
 if(F.length == 0){
  document.form1.incluir.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
 }
 document.form1.incluirtodos.disabled = false;
 document.form1.alunos.focus();
}
function js_excluirtodos() {
 var Tam = document.form1.alunos.length;
 var F = document.getElementById("alunos");
 for(i=0;i<Tam;i++){
  document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[0].text,F.options[0].value);
  F.options[0] = null;
 }
 if(F.length == 0){
  document.form1.incluir.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunospossib.focus();
}
function js_selecionar(){
 var F = document.getElementById("alunos").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 return true;
}
function js_desabinc(){
 for(i=0;i<document.form1.alunospossib.length;i++){
  if(document.form1.alunospossib.length>0 && document.form1.alunospossib.options[i].selected){
   if(document.form1.alunos.length>0){
    document.form1.alunos.options[0].selected = false;
   }
   document.form1.incluirum.disabled = false;
   document.form1.excluirum.disabled = true;
  }
 }
}
function js_desabexc(){
 for(i=0;i<document.form1.alunos.length;i++){
  if(document.form1.alunos.length>0 && document.form1.alunos.options[i].selected){
   if(document.form1.alunospossib.length>0){
    document.form1.alunospossib.options[0].selected = false;
   }
   document.form1.incluirum.disabled = true;
   document.form1.excluirum.disabled = false;
  }
 }
}
<?if($db_opcao==1 && isset($chavepesquisa)){?>
if(document.form1.alunospossib.length==0){
 document.form1.incluirtodos.disabled = true;
}
<?}?>
if(document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value<0){
 document.form1.restantes.value = 0;
}else{
 document.form1.restantes.value = document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value;
}
<?if($db_opcao==1 && isset($chavepesquisa)){?>
if(document.form1.restantes.value==0){
 alert("Não há vagas disponíveis nesta turma!");
 document.form1.incluirtodos.disabled = true;
 document.form1.alunospossib.disabled = true;
 document.form1.alunos.disabled = true;
 document.form1.alunospossib.style.background = "#CCCCCC";
 document.form1.alunos.style.background = "#CCCCCC";
}
<?}?>
</script>