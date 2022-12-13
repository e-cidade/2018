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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_regencia_classe.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clmatricula = new cl_matricula;
$clturma = new cl_turma;
$clregencia = new cl_regencia;
$clprocavaliacao = new cl_procavaliacao;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calendários
 $sql = "SELECT ed52_i_codigo,ed52_c_descr
         FROM calendario
          inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
         WHERE ed38_i_escola = $escola
         AND ed52_c_passivo = 'N'
         ORDER BY ed52_i_ano DESC";
 $sql_result = pg_query($sql);
 $num = pg_num_rows($sql_result);
 $conta = "";
 while ($row=pg_fetch_array($sql_result)){
  $conta = $conta+1;
  $cod_curso = $row["ed52_i_codigo"];
  echo "new Array(\n";
  $sub_sql = "SELECT DISTINCT ed57_i_codigo,ed57_c_descr,ed11_c_descr
              FROM turma
               inner join matricula on ed60_i_turma = ed57_i_codigo
               inner join serie on ed11_i_codigo = ed57_i_serie
               inner join aluno on ed47_i_codigo = ed60_i_aluno
               inner join diario on ed95_i_aluno = ed47_i_codigo
               inner join regencia on ed59_i_codigo = ed95_i_regencia
               inner join disciplina on ed12_i_codigo = ed59_i_disciplina
               inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
               inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo
               inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
               inner join procedimento on ed40_i_codigo = ed41_i_procedimento
               inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
               inner join formaavaliacao on formaavaliacao.ed37_i_codigo = ed41_i_formaavaliacao
              WHERE ed57_i_calendario = '$cod_curso'
              AND ed60_i_turma = ed59_i_turma
              AND ed57_i_escola = '$escola'
              AND ed37_c_tipo != 'PARECER'
              AND (ed72_t_parecer != '' AND ed72_t_parecer is not null)
              ORDER BY ed57_c_descr,ed11_c_descr
             ";
  $sub_result = pg_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   # Se achar alguma base para o curso, marca a palavra Todas
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed57_i_codigo"];
    $base_nome=$rowx["ed57_c_descr"];
    $serie_nome=$rowx["ed11_c_descr"];
    $conta_sub=$conta_sub+1;
    if ($conta_sub==$num_sub){
     echo "new Array(\"$base_nome - $serie_nome\", $codigo_base)\n";
     $conta_sub = "";
    }else{
     echo "new Array(\"$base_nome - $serie_nome\", $codigo_base),\n";
    }
   }
  }else{
   #Se nao achar base para o curso selecionado...
   echo "new Array(\"Sem turmas com Parecer Descritivo\", '')\n";
  }
  if ($num>$conta){
   echo "),\n";
  }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
 var i, j;
 var prompt;
 // empty existing items
 for (i = selectCtrl.options.length; i >= 0; i--) {
  selectCtrl.options[i] = null;
 }
 prompt = (itemArray != null) ? goodPrompt : badPrompt;
 if (prompt == null) {
  document.form1.subgrupo.disabled = true;
  j = 0;
 }else{
  selectCtrl.options[0] = new Option(prompt);
  j = 1;
 }
 if (itemArray != null) {
  // add new items
  for (i = 0; i < itemArray.length; i++){
   selectCtrl.options[j] = new Option(itemArray[i][0]);
   if (itemArray[i][1] != null){
    selectCtrl.options[j].value = itemArray[i][1];
   }
   j++;
  }
  selectCtrl.options[0].selected = true;
  document.form1.subgrupo.disabled = false;
 }
 document.form1.procurar.disabled = true;
 <?if(isset($turma)){?>
  qtd = document.form1.alunosdiario.length;
  for (i = 0; i < qtd; i++) {
   document.form1.alunosdiario.options[0] = null;
  }
  qtd = document.form1.alunos.length;
  for (i = 0; i < qtd; i++) {
   document.form1.alunos.options[0] = null;
  }
  qtd = document.form1.periodo.length;
  for (i = 0; i < qtd; i++) {
   document.form1.periodo.options[0] = null;
  }
  qtd = document.form1.disciplinas.length;
  for (i = 0; i < qtd; i++) {
   document.form1.disciplinas.options[0] = null;
  }
 <?}?>
}
function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
 var i, j;
 var prompt;
 // empty existing items
 for (i = selectCtrl.options.length; i >= 0; i--) {
  selectCtrl.options[i] = null;
 }
 prompt = (itemArray != null) ? goodPrompt : badPrompt;
 if (prompt == null) {
  document.form1.subgrupo.disabled = true;
  j = 0;
 }else{
  selectCtrl.options[0] = new Option(prompt);
  j = 1;
 }
 if (itemArray != null) {
  // add new items
  for (i = 0; i < itemArray.length; i++){
   selectCtrl.options[j] = new Option(itemArray[i][0]);
   if (itemArray[i][1] != null){
    selectCtrl.options[j].value = itemArray[i][1];
   }
   <?if(isset($turma)){?>
    if(<?=trim($turma)?>==itemArray[i][1]){
     indice = i;
    }
   <?}?>
   j++;
  }
  <?if(isset($turma)){?>
   selectCtrl.options[indice].selected = true;
   document.form1.procurar.disabled = false;
  <?}else{?>
   selectCtrl.options[0].selected = true;
  <?}?>
  document.form1.subgrupo.disabled = false;
 }
}
//End -->
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<fieldset style="width:95%"><legend><b>Relatório Parecer Decritivo - Avaliações com Nota</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Selecione o Calendário:</b><br>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql = "SELECT ed52_i_codigo,ed52_c_descr
               FROM calendario
                inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
               WHERE ed38_i_escola = $escola
               AND ed52_c_passivo = 'N'
               ORDER BY ed52_i_ano DESC";
       $sql_result = pg_query($sql);
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed52_i_codigo"];
        $desc_curso=$row["ed52_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" <?=$cod_curso==@$calendario?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
      <b>Selecione a Turma:</b><br>
      <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
       <option value=""></option>
      </select>
     </td>
     <td valign='bottom'>
      <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?if(isset($turma)){?>
 <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
 <tr>
  <td valign="top">
   <?
   $sql = "SELECT DISTINCT ed47_i_codigo,ed47_v_nome
               FROM turma
                inner join matricula on ed60_i_turma = ed57_i_codigo
                inner join serie on ed11_i_codigo = ed57_i_serie
                inner join aluno on ed47_i_codigo = ed60_i_aluno
                inner join diario on ed95_i_aluno = ed47_i_codigo
                inner join regencia on ed59_i_codigo = ed95_i_regencia
                inner join disciplina on ed12_i_codigo = ed59_i_disciplina
                inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
                inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo
               WHERE ed59_i_turma = $turma
               AND ed60_i_turma = ed59_i_turma
               AND ed57_i_escola = '$escola'
               AND (ed72_t_parecer != '' AND ed72_t_parecer is not null)
               ORDER BY ed47_v_nome
              ";
   $result = pg_query($sql);
   $linhas = pg_num_rows($result);
   ?>
   <b>Alunos:</b><br>
   <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()" style="font-size:9px;width:330px;height:120px" multiple>
    <?
    for($i=0;$i<$linhas;$i++) {
     db_fieldsmemory($result,$i);
     echo "<option value='$ed47_i_codigo'>$ed47_v_nome</option>\n";
    }
    ?>
   </select>
  </td>
  <td align="center">
   <br>
   <table border="0">
    <tr>
     <td>
      <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
     </td>
    </tr>
    <tr><td height="3"></td></tr>
    <tr>
     <td>
      <hr>
     </td>
    </tr>
    <tr><td height="3"></td></tr>
    <tr>
     <td>
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <b>Alunos para gerar parecer descritivo:</b><br>
   <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" style="font-size:9px;width:330px;height:120px" multiple>
   </select>
  </td>
 </tr>
 <tr>
  <td>
   <b>Período de Avaliação:</b>
   <?
   $sql_t = $clturma->sql_query("","ed57_i_procedimento",""," ed57_i_codigo = $turma");
   $result_t = $clturma->sql_record($sql_t);
   db_fieldsmemory($result_t,0);
   $sql2  = "SELECT ed41_i_codigo,
                    ed09_c_descr,
                    ed41_i_sequencia
             FROM procavaliacao
              inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
             WHERE ed41_i_procedimento = $ed57_i_procedimento
             ORDER BY ed41_i_sequencia
            ";
   $result2 = pg_query($sql2);
   $linhas2 = pg_num_rows($result2);
   ?>
   <select name="periodo" id="periodo" style="font-size:9px;width:180px;">
    <?
    for($y=0;$y<$linhas2;$y++){
     db_fieldsmemory($result2,$y);
     echo "<option value='$ed41_i_codigo'>$ed09_c_descr</option>";
    }
    ?>
   </select>
  </td>
  <td></td>
  <td>
   <b>Disciplinas:</b>
   <?
   $sql_r = $clregencia->sql_query("","ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $turma AND ed59_c_freqglob != 'F' AND ed59_c_condicao = 'OB'");
   $result_r = $clregencia->sql_record($sql_r);
   ?>
   <select name="disciplinas" id="disciplinas" style="font-size:9px;width:180px;">
    <?
    if($clregencia->numrows>1){
     echo "<option value='PU'>PARECER ÚNICO</option>";
     echo "<option value='T'>TODAS</option>";
    }
    for($y=0;$y<$clregencia->numrows;$y++){
     db_fieldsmemory($result_r,$y);
     echo "<option value='$ed59_i_codigo'>$ed232_c_descr</option>";
    }
    ?>
   </select>
  </td>
 </tr>
 <tr>
  <td colspan="3">
  <input type="checkbox" name="grade" value="" checked> <b>Mostrar grade com as notas do aluno</b>
  </td>
 </tr>
 <tr>
  <td align="center" colspan="3">
   <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa(document.form1.subgrupo.value);" disabled>
   <br><br>
   <fieldset style="width:250;align:center">
    Para selecionar mais de um aluno<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome dos alunos.
   </fieldset>
  </td>
 </tr>
 <?}?>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_incluir() {
 var Tam = document.form1.alunosdiario.length;
 var F = document.form1;
 for(x=0;x<Tam;x++){
  if(F.alunosdiario.options[x].selected==true){
   F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
   F.alunosdiario.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunosdiario.length>0){
  document.form1.alunosdiario.options[0].selected = true;
 }else{
  document.form1.incluirum.disabled = true;
  document.form1.incluirtodos.disabled = true;
 }
 document.form1.pesquisar.disabled = false;
 document.form1.excluirtodos.disabled = false;
 document.form1.alunosdiario.focus();
}
function js_incluirtodos() {
 var Tam = document.form1.alunosdiario.length;
 var F = document.form1;
 for(i=0;i<Tam;i++){
  F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value)
  F.alunosdiario.options[0] = null;
 }
 document.form1.incluirum.disabled = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 document.form1.pesquisar.disabled = false;
 document.form1.alunos.focus();
}
function js_excluir() {
 var F = document.getElementById("alunos");
 Tam = F.length;
 for(x=0;x<Tam;x++){
  if(F.options[x].selected==true){
   document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
   F.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunos.length>0){
  document.form1.alunos.options[0].selected = true;
 }
 if(F.length == 0){
  document.form1.pesquisar.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunos.focus();
}
function js_excluirtodos() {
 var Tam = document.form1.alunos.length;
 var F = document.getElementById("alunos");
 for(i=0;i<Tam;i++){
  document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
  F.options[0] = null;
 }
 if(F.length == 0){
  document.form1.pesquisar.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunosdiario.focus();
}
function js_desabinc(){
 for(i=0;i<document.form1.alunosdiario.length;i++){
  if(document.form1.alunosdiario.length>0 && document.form1.alunosdiario.options[i].selected){
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
   if(document.form1.alunosdiario.length>0){
    document.form1.alunosdiario.options[0].selected = false;
   }
   document.form1.incluirum.disabled = true;
   document.form1.excluirum.disabled = false;
  }
 }
}
function js_botao(valor){
 if(valor!=""){
  document.form1.procurar.disabled = false;
 }else{
  document.form1.procurar.disabled = true;
 }
 <?if(isset($turma)){?>
  qtd = document.form1.alunosdiario.length;
  for (i = 0; i < qtd; i++) {
   document.form1.alunosdiario.options[0] = null;
  }
  qtd = document.form1.alunos.length;
  for (i = 0; i < qtd; i++) {
   document.form1.alunos.options[0] = null;
  }
  qtd = document.form1.periodo.length;
  for (i = 0; i < qtd; i++) {
   document.form1.periodo.options[0] = null;
  }
  qtd = document.form1.disciplinas.length;
  for (i = 0; i < qtd; i++) {
   document.form1.disciplinas.options[0] = null;
  }
 <?}?>
}
function js_procurar(calendario,turma){
 location.href = "edu2_pardescritivonota001.php?calendario="+calendario+"&turma="+turma;
}
function js_pesquisa(turma){
 F = document.form1.alunos;
 alunos = "";
 sep = "";
 for(i=0;i<F.length;i++){
  alunos += sep+F.options[i].value;
  sep = ",";
 }
 disciplinas = "";
 if(document.form1.disciplinas.value=="T"){
  D = document.form1.disciplinas;
  sep = "";
  for(i=2;i<D.length;i++){
   disciplinas += sep+D.options[i].value;
   sep = ",";
  }
  punico = "no";
 }else if(document.form1.disciplinas.value=="PU"){
  D = document.form1.disciplinas;
  disciplinas = D.options[2].value;
  punico = "yes";
 }else{
  disciplinas = document.form1.disciplinas.value;
  punico = "no";
 }
 if(document.form1.grade.checked==true){
  grade = "yes";
 }else{
  grade = "no";
 }
 jan = window.open('edu2_pardescritivonota002.php?punico='+punico+'&grade='+grade+'&periodo='+document.form1.periodo.value+'&disciplinas='+disciplinas+'&turma='+turma+'&alunos='+alunos,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
<?if(!isset($turma) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
</script>