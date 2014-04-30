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
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clmatricula = new cl_matricula;
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
  $sub_sql = "SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr
              FROM turma
               inner join matricula on ed60_i_turma = ed57_i_codigo
               inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
               inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
               inner join serie on ed11_i_codigo = ed223_i_serie
               inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                                         and ed221_i_serie = ed223_i_serie
               inner join procedimento on ed40_i_codigo = ed220_i_procedimento
               inner join formaavaliacao on ed37_i_codigo = ed40_i_formaavaliacao
              WHERE ed57_i_calendario = '$cod_curso'
              AND ed57_i_escola = $escola
              AND ed221_c_origem = 'S'
              AND ed37_c_tipo = 'NOTA'
              ORDER BY ed57_c_descr,ed11_c_descr
             ";
  $sub_result = pg_query($sub_sql);
  $num_sub = pg_num_rows($sub_result);
  if ($num_sub>=1){
   # Se achar alguma base para o curso, marca a palavra Todas
   echo "new Array(\"\", ''),\n";
   $conta_sub = "";
   while ($rowx=pg_fetch_array($sub_result)){
    $codigo_base=$rowx["ed220_i_codigo"];
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
   echo "new Array(\"Sem turmas.\", '')\n";
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
 <?if(isset($turma)){?>
  qtd = document.form1.aluno.length;
  for (i = 0; i < qtd; i++) {
   document.form1.aluno.options[0] = null;
  }
  document.form1.aluno.disabled = true;
  iframe_grafico.location.href = "edu2_gfcofreqaluno002.php?turma=0";
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
<fieldset style="width:95%"><legend><b>Gráfico de Frequência de Alunos</b></legend>
<table border="0" align="left">
 <tr>
  <td>
   <table border="0" align="left">
    <tr>
     <td>
      <b>Calendário:</b><br>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:150px;height:18px;">
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
      <b>Turma:</b><br>
      <select name="subgrupo" style="font-size:9px;width:150px;height:18px;" disabled onchange="js_botao(this.value,document.form1.grupo.value,document.form1.subgrupo.value);">
       <option value=""></option>
      </select>
     </td>
     <?if(isset($turma)){?>
      <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
      <td>
       <?
       $sql = "SELECT ed47_i_codigo,ed47_v_nome
               FROM matricula
                inner join aluno on ed47_i_codigo = ed60_i_aluno
                inner join turma on ed57_i_codigo = ed60_i_turma
                inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
                inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                inner join serie on ed11_i_codigo = ed223_i_serie
                inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
               WHERE ed220_i_codigo = $turma
               AND ed60_c_ativa = 'S'
               AND ed221_c_origem = 'S'
               AND ed221_i_serie = ed223_i_serie
               AND ed60_c_situacao = 'MATRICULADO'
               ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome)
              ";
       $result = pg_query($sql);
       $linhas = pg_num_rows($result);
       ?>
       <b>Aluno:</b><br>
       <select name="aluno" style="font-size:9px;width:300px;height:18px;" onchange="js_pesquisa(document.form1.subgrupo.value,document.form1.aluno.value);">
         <option value=''></option>
        <?
        for($i=0;$i<$linhas;$i++){
         db_fieldsmemory($result,$i);
         echo "<option value='$ed47_i_codigo'>$ed47_v_nome - $ed47_i_codigo</option>\n";
        }
        ?>
       </select>
      </td>
      <?}?>
    </tr>
   </table>
  </td>
 </tr>
</table>
</fieldset>
<iframe name="iframe_grafico" id="iframe_grafico" src="" width="97%" height="470" frameborder="0"></iframe>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_botao(valor,calendario,turma){
 if(valor!=""){
  location.href = "edu2_gfcofreqaluno001.php?calendario="+calendario+"&turma="+turma;
 }
 <?if(isset($turma)){?>
  qtd = document.form1.aluno.length;
  for (i = 0; i < qtd; i++) {
   document.form1.aluno.options[0] = null;
  }
 <?}?>
}
function js_pesquisa(turma,aluno){
 if(aluno==""){
  iframe_grafico.location.href = "edu2_gfcofreqaluno002.php?turma=0";
 }else{
  iframe_grafico.location.href = "edu2_gfcofreqaluno002.php?turma="+turma+"&aluno="+aluno+"&larg_pagina="+(screen.availWidth-60);
 }
}
<?if(!isset($turma) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>
</script>