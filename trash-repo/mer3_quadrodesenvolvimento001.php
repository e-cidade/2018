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
include("classes/db_periodocalendario_classe.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clmatricula = new cl_matricula;
$clturma = new cl_turma;
$clperiodocalendario = new cl_periodocalendario;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calendários
  $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
  $sql       .= "       FROM calendario ";
  $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
  $sql       .= "       WHERE ed38_i_escola = $escola ";
  $sql       .= "       AND ed52_c_passivo = 'N' ";
  $sql       .= "       ORDER BY ed52_i_ano DESC ";
  $sql_result = pg_query($sql);
  $num        = pg_num_rows($sql_result);
  $conta      = "";
  while ($row = pg_fetch_array($sql_result)) {
    
    $conta     = $conta+1;
    $cod_curso = $row["ed52_i_codigo"];
    echo "new Array(\n";
    $sub_sql    = " SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr ";
    $sub_sql   .= "         FROM turma ";
    $sub_sql   .= "          inner join matricula on ed60_i_turma = ed57_i_codigo ";
    $sub_sql   .= "          inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
    $sub_sql   .= "          inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
    $sub_sql   .= "          inner join serie on ed11_i_codigo = ed223_i_serie ";
    $sub_sql   .= "          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
    $sub_sql   .= "                                    and ed221_i_serie = ed223_i_serie "; 
    $sub_sql   .= "         WHERE ed57_i_calendario = '$cod_curso' ";
    $sub_sql   .= "         AND ed57_i_escola = $escola ";
    $sub_sql   .= "         AND ed221_c_origem = 'S' ";
    $sub_sql   .= "         ORDER BY ed57_c_descr,ed11_c_descr ";
    $sub_result = pg_query($sub_sql);
    $num_sub    = pg_num_rows($sub_result);
    if ($num_sub >= 1) {
        
      # Se achar alguma base para o curso, marca a palavra Todas
      echo "new Array(\"\", ''),\n";
      $conta_sub = "";
      while ($rowx = pg_fetch_array($sub_result)) {
        
        $codigo_base = $rowx["ed220_i_codigo"];
        $base_nome   = $rowx["ed57_c_descr"];
        $serie_nome  = $rowx["ed11_c_descr"];
        $conta_sub   = $conta_sub+1;
        
        if ($conta_sub == $num_sub) {
            
          echo "new Array(\"$base_nome - $serie_nome\", $codigo_base)\n";
          $conta_sub = "";
          
        } else {
          echo "new Array(\"$base_nome - $serie_nome\", $codigo_base),\n";
        }
        
      }
    } else {
        
      #Se nao achar base para o curso selecionado...
      echo "new Array(\"Calendário sem turmas cadastradas\", '')\n";
      
    }
    
    if ($num > $conta) {
      echo "),\n";
    }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
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
  } else {
    selectCtrl.options[0] = new Option(prompt);
    j = 1;
  }
  if (itemArray != null) {
   // add new items
    for (i = 0; i < itemArray.length; i++) {
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null) {
        selectCtrl.options[j].value = itemArray[i][1];
      }
      j++;
    }
    selectCtrl.options[0].selected   = true;
    document.form1.subgrupo.disabled = false;
  }
  document.form1.procurar.disabled = true;
 <?if (isset($turma)) {?>
     qtd = document.form1.alunosdiario.length;
     for (i = 0; i < qtd; i++) {
       document.form1.alunosdiario.options[0] = null;
     }
     qtd = document.form1.alunos.length;
     for (i = 0; i < qtd; i++) {
       document.form1.alunos.options[0] = null;
     }
 <?}?>
}
function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
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
  } else {
    selectCtrl.options[0] = new Option(prompt);
    j = 1;
  }
  if (itemArray != null) {
  // add new items
    for (i = 0; i < itemArray.length; i++) {
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null) {
        selectCtrl.options[j].value = itemArray[i][1];
      }
    <?if (isset($turma)) {?>
        if (<?=trim($turma)?> == itemArray[i][1]) {
          indice = i;
        }
    <?}?>
      j++;
  }
  <?if (isset($turma)) {?>
      selectCtrl.options[indice].selected = true;
      document.form1.procurar.disabled    = false;
  <?} else {?>
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
<fieldset style="width:95%"><legend><b>Quadro de Desenvolvimento</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Selecione o Calendário:</b><br>
      <select name="grupo" id="grupo" 
              onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" 
              style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
       $sql       .= "       FROM calendario ";
       $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
       $sql       .= "       WHERE ed38_i_escola = $escola ";
       $sql       .= "      AND ed52_c_passivo = 'N' ";
       $sql       .= "       ORDER BY ed52_i_ano DESC ";
       $sql_result = pg_query($sql);
       while($row = pg_fetch_array($sql_result)) {
         $cod_curso  = $row["ed52_i_codigo"];
         $desc_curso = $row["ed52_c_descr"];
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
      <select name="subgrupo" id="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
       <option value=""></option>
      </select>
     </td>
     <td>
      <b>Competência:</b><br>
      <select name="tipocompetencia" onchange="js_competencia(this.value);" style="font-size:9px;height:18px;" >
       <option value="" <?=@$tipocompetencia==""?"selected":""?>>Todas</option>
       <option value="M" <?=@$tipocompetencia=="M"?"selected":""?>>Mês</option>
       <option value="P" <?=@$tipocompetencia=="P"?"selected":""?>>Período de Avaliação</option>
      </select>
     </td>
     <td>
      <span id="div_tipocompetencia">
      <?if(isset($tipomes)){?>
       <b>Mês</b><br>
       <select name="tipomes" style="font-size:9px;height:18px;">
        <option value="1" <?=@$tipomes=="1"?"selected":""?>>Janeiro</option>
        <option value="2" <?=@$tipomes=="2"?"selected":""?>>Fevereiro</option>
        <option value="3" <?=@$tipomes=="3"?"selected":""?>>Março</option>
        <option value="4" <?=@$tipomes=="4"?"selected":""?>>Abril</option>
        <option value="5" <?=@$tipomes=="5"?"selected":""?>>Maio</option>
        <option value="6" <?=@$tipomes=="6"?"selected":""?>>Junho</option>
        <option value="7" <?=@$tipomes=="7"?"selected":""?>>Julho</option>
        <option value="8" <?=@$tipomes=="8"?"selected":""?>>Agosto</option>
        <option value="9" <?=@$tipomes=="9"?"selected":""?>>Setembro</option>
        <option value="10" <?=@$tipomes=="10"?"selected":""?>>Outubro</option>
        <option value="11" <?=@$tipomes=="11"?"selected":""?>>Novembro</option>
        <option value="12" <?=@$tipomes=="12"?"selected":""?>>Dezembro</option>
       </select>
      <?}?>
      <?if(isset($tipoperiodo)){
        $result1 = $clperiodocalendario->sql_record(
                    $clperiodocalendario->sql_query("",
                                               "ed09_i_codigo,ed09_c_descr",
                                               "ed09_i_sequencia",
                                               "ed53_i_calendario = $calendario" 
                                              )
                                              );
       ?>
       <b>Período:<br></b>
       <select name="tipoperiodo" style="font-size:9px;height:18px;">
        <?
        for($tt=0;$tt<$clperiodocalendario->numrows;$tt++){
         db_fieldsmemory($result1,$tt);
         ?>
         <option value="<?=$ed09_i_codigo?>" <?=@$tipoperiodo==$ed09_i_codigo?"selected":""?>><?=$ed09_c_descr?></option>
         <? 
        }
        ?>
       </select>
       <?
       }
      ?>
      </span>
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
   $sql    = " SELECT distinct ed47_i_codigo,ed47_v_nome,ed60_i_codigo,ed60_i_numaluno ";
   $sql   .= "       FROM matricula ";
   $sql   .= "        inner join aluno on ed47_i_codigo = ed60_i_aluno ";
   $sql   .= "        inner join mer_infaluno on mer_infaluno.me14_i_aluno = aluno.ed47_i_codigo";
   $sql   .= "        inner join turma on ed57_i_codigo = ed60_i_turma ";
   $sql   .= "        inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
   $sql   .= "        inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
   $sql   .= "        inner join serie on ed11_i_codigo = ed223_i_serie ";
   $sql   .= "        inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
   $sql   .= "       WHERE ed220_i_codigo = $turma ";
   $sql   .= "       AND ed60_c_ativa = 'S' ";
   $sql   .= "       AND ed221_c_origem = 'S' ";
   $sql   .= "       AND ed221_i_serie = ed223_i_serie ";
   $sql   .= "       AND ed60_c_situacao = 'MATRICULADO' ";
   $sql   .= "       ORDER BY ed60_i_numaluno,ed47_i_codigo,ed47_v_nome,ed60_i_codigo ";
   $result = pg_query($sql);
   $linhas = pg_num_rows($result);
   ?>
   <b>Selecione o Aluno:</b><br>
   <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()" 
           style="font-size:9px;width:330px;height:120px" multiple>
    <?
    for ($i = 0; $i < $linhas; $i++) {
        
      db_fieldsmemory($result,$i);
      echo "<option value='$ed60_i_codigo'>$ed47_i_codigo - $ed47_v_nome</option>\n";
      
    }
    ?>
   </select>
  </td>
  <td align="center">
   <br>
   <table border="0">
    <tr>
     <td>
      <input name="incluirum" title="Incluir" type="button" value=">" 
             onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" 
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$linhas==0?"disabled":""?>>
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
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" 
             style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <b>Emitir quadro de:</b><br>
   <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" 
           style="font-size:9px;width:330px;height:120px" multiple>
   </select>
  </td>
 </tr>
 <tr>
  <td align="center" colspan="3">
   <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa(document.form1.subgrupo.value);" disabled>
   <br><br>
  </td>
 </tr>
 <?}?>
 <?if(isset($alunos)){?>
 <tr>
  <td align="center" colspan="3">
   <?
  $condicao2 = "";
if($tipocompetencia!=""){
    
 if(isset($tipomes)){
  $condicao2 .= "AND me14_i_mes = $tipomes";    
 }
 if(isset($tipoperiodo)){
  $condicao2 .= "AND me14_i_periodocalendario = $tipoperiodo"; 
 }
}
 $condicao=" AND ed60_c_situacao = 'MATRICULADO'";
 $campos = "ed47_i_codigo,ed47_v_nome,round(me14_f_peso,2) as peso,round(me14_f_altura,2) as altura,round(me14_f_peso/(me14_f_altura*2),2)as massa,to_char(me14_d_data,'DD/MM/YYYY') as data,ed47_v_sexo,fc_idade(ed47_d_nasc,current_date) as idade,ed47_c_bolsafamilia";    
$sql2 = "SELECT $campos
         FROM matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
          inner join aluno on ed47_i_codigo = ed60_i_aluno
          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
          left join mer_infaluno on me14_i_aluno = ed47_i_codigo
         WHERE ed60_i_codigo in ($alunos)
         AND ed221_c_origem = 'S'
         $condicao
         $condicao2
         ORDER BY ed47_v_nome,ed60_c_ativa
        ";
$result2 = pg_query($sql2);
$linhas2 = pg_num_rows($result2);
if($linhas2==0){
 ?>
 <font size="4">Nenhum registro encontrado</font>
 <?
}else{
 echo "<table width='100%'>";
   echo "<tr bgcolor='#444444' style='color:#DEB887'>";
   echo "<td>Código</td>";
   echo "<td>Nome</td>";
   echo "<td>Sexo</td>";
   echo "<td>Idade</td>";
   echo "<td>Peso</td>";
   echo "<td>Altura</td>";
   echo "<td>Massa</td>";
   echo "<td>Bolsa Família</td>";
   echo "<td>Data</td>";
   echo "</tr>";
 
 for($rr=0;$rr<$linhas2;$rr++){
   db_fieldsmemory($result2,$rr);
   echo "<tr bgcolor='#f3f3f3'>";
   echo "<td>$ed47_i_codigo</td>";
   echo "<td>$ed47_v_nome</td>";
   echo "<td>$ed47_v_sexo</td>";
   echo "<td>$idade</td>";
   echo "<td>$peso</td>";
   echo "<td>$altura</td>";
   echo "<td>$massa</td>";
   echo "<td>$ed47_c_bolsafamilia</td>";
   echo "<td>$data</td>";
   echo "</tr>";
 	
 }
 echo "</table>";
	
}
?>  
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
 <?}?>
}
function js_procurar(calendario,turma){
 str = "";
 if(document.form1.tipomes){
  str += "&tipomes="+document.form1.tipomes.value;   
 }
 if(document.form1.tipoperiodo){
  str += "&tipoperiodo="+document.form1.tipoperiodo.value;   
 }  
 location.href = "mer3_quadrodesenvolvimento001.php?calendario="+calendario+"&turma="+turma+"&tipocompetencia="+document.form1.tipocompetencia.value+str;
}
function js_pesquisa(turma){
 str = "";
 if(document.form1.tipomes){
  str += "&tipomes="+document.form1.tipomes.value;   
 }
 if(document.form1.tipoperiodo){
  str += "&tipoperiodo="+document.form1.tipoperiodo.value;   
 }  
 F = document.form1.alunos;
 alunos = "";
 sep = "";
 for(i=0;i<F.length;i++){
  alunos += sep+F.options[i].value;
  sep = ",";
 }
 location.href = "mer3_quadrodesenvolvimento001.php?calendario="+document.form1.grupo.value+"&turma="+document.form1.subgrupo.value+"&tipocompetencia="+document.form1.tipocompetencia.value+str+"&alunos="+alunos; 
}
<?if(!isset($turma) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray2(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
function js_competencia(valor){
 document.getElementById('div_tipocompetencia').innerHTML = "";
 if(valor=="M"){
  sHtml  = '<b>Mês</b><br>';
  sHtml += '<select name="tipomes" style="font-size:9px;height:18px;">';
  sHtml += '<option value="1">Janeiro</option>';
  sHtml += '<option value="2">Fevereiro</option>';
  sHtml += '<option value="3">Março</option>';
  sHtml += '<option value="4">Abril</option>';
  sHtml += '<option value="5">Maio</option>';
  sHtml += '<option value="6">Junho</option>';
  sHtml += '<option value="7">Julho</option>';
  sHtml += '<option value="8">Agosto</option>';
  sHtml += '<option value="9">Setembro</option>';
  sHtml += '<option value="10">Outubro</option>';
  sHtml += '<option value="11">Novembro</option>';
  sHtml += '<option value="12">Dezembro</option>';
  sHtml += '</select>';
  document.getElementById('div_tipocompetencia').innerHTML = sHtml;
 }else if(valor=="P"){
  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaPeriodo';
  var url     = 'mer2_quadrodesenvolvimentoRPC.php';
  var oAjax = new Ajax.Request(url,
                                  {
                                    method    : 'post',
                                    parameters: 'calendario='+$('grupo').value+'&sAction='+sAction,
                                    onComplete: js_retornoPesquisaPeriodo
                                  }
                               );
 }
}
function js_retornoPesquisaPeriodo(oAjax) {
 js_removeObj("msgBox");
 var oRetorno = eval("("+oAjax.responseText+")");
 sHtml  = '<b>Período:<br></b>';
 sHtml += '<select name="tipoperiodo" style="font-size:9px;height:18px;">'; 
 for (var i = 0;i < oRetorno.length; i++) {
  with (oRetorno[i]) {
   sHtml += '  <option value="'+ed09_i_codigo+'">'+ed09_c_descr.urlDecode()+'</option>';
  }
 }
 sHtml += '  </select>';
 document.getElementById('div_tipocompetencia').innerHTML = sHtml;
}
</script>