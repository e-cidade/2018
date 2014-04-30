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
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
$clcalendario = new cl_calendario;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
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
<fieldset style="width:95%"><legend><b>Gráfico de Comparação entre Escolas - Frequencia da Etapa </b></legend>
<table border="0" align="left">
 <tr>
  <td valign="top">
   <table border="0" align="left">
    <tr>
     <td>
      <b>Selecione o Ano</b>:<br>
      <?
      $result = $clcalendario->sql_record($clcalendario->sql_query(""," distinct ed52_i_ano,ed52_i_ano","ed52_i_ano DESC",""));
      if($clcalendario->numrows==0){
       $x = array(' '=>'NENHUM REGISTRO');
       db_select('ano',$x,true,1," style='font-size:9px;width:200px;height:18px;'");
      }else{
       ?><select name="ano" onchange="js_ano(this.value)" style="font-size:9px;width:200px;height:18px;"><?
       echo "<option value=''></option>";
       for($x=0;$x<$clcalendario->numrows;$x++){
        db_fieldsmemory($result,$x);
        $selected = $ano==$ed52_i_ano?"selected":"";
        echo "<option value='$ed52_i_ano' $selected>$ed52_i_ano</option>";
       }
       ?></select><?
      }
      ?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Ensino</b>:<br>
      <?
      $sql = "SELECT distinct ed10_i_codigo,ed10_c_descr,ed10_c_abrev
              FROM ensino
               inner join cursoedu on ed29_i_ensino = ed10_i_codigo
               inner join base on ed31_i_curso = ed29_i_codigo
               inner join turma on ed57_i_base = ed31_i_codigo
               inner join matricula on ed60_i_turma = ed57_i_codigo
              WHERE ed60_c_situacao = 'MATRICULADO'
              ORDER BY ed10_c_abrev
             ";
      $result= pg_query($sql);
      $linhas = pg_num_rows($result);
      if($linhas==0){
       $x = array(' '=>'NENHUM REGISTRO');
       db_select('ano',$x,true,1,"style='font-size:9px;width:200px;height:18px;'");
      }else{
       ?><select name="ensino" onchange='js_ensino(this.value)' style="font-size:9px;width:200px;height:18px;"><?
       echo "<option value=''></option>";
       for($x=0;$x<$linhas;$x++){
        db_fieldsmemory($result,$x);
        $selected = $ensino==$ed10_i_codigo?"selected":"";
        echo "<option value='$ed10_i_codigo' $selected>$ed10_c_descr</option>";
       }
       ?></select><?
      }
      ?>
     </td>
    </tr>
    <?if(isset($ensino)){?>
    <tr>
     <td>
      <b>Etapa</b>:<br>
      <?
      $sql = "SELECT distinct ed11_i_codigo,ed11_c_descr,ed11_i_sequencia
              FROM serie
               inner join matriculaserie on ed221_i_serie = ed11_i_codigo
               inner join matricula on ed221_i_matricula = ed60_i_codigo
               inner join turma on ed57_i_codigo = ed60_i_turma
              WHERE ed60_c_situacao = 'MATRICULADO'
              AND ed11_i_ensino = $ensino
              ORDER BY ed11_i_sequencia
             ";
      $result= pg_query($sql);
      $linhas = pg_num_rows($result);
      if($linhas==0){
       $x = array(' '=>'NENHUM REGISTRO');
       db_select('serie',$x,true,1,"style='font-size:9px;width:200px;height:18px;'");
      }else{
       ?><select name="serie" onchange='js_serie(this.value);' style="font-size:9px;width:200px;height:18px;"><?
       echo "<option value=''></option>";
       for($x=0;$x<$linhas;$x++){
        db_fieldsmemory($result,$x);
        $selected = @$serie==$ed11_i_codigo?"selected":"";
        echo "<option value='$ed11_i_codigo' $selected>$ed11_c_descr</option>";
       }
       ?></select><?
      }
      ?>
     </td>
    </tr>
    <?}?>
   </table>
  </td>
  <td>
    <b>Selecione as escolas</b>:<br>
    <?
    if(!isset($serie)){
     $serie = 0;
     $ano = 0;
    }
    $sql = "SELECT distinct ed18_i_codigo,ed18_c_nome
            FROM escola
             inner join turma on ed57_i_escola = ed18_i_codigo
             inner join matricula on ed60_i_turma = ed57_i_codigo
             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
             inner join diario on ed95_i_aluno = ed60_i_aluno
             inner join calendario on ed52_i_codigo = ed57_i_calendario
            WHERE ed221_i_serie = $serie
            AND ed60_c_situacao = 'MATRICULADO'
            AND ed52_i_ano = $ano
            AND ed221_c_origem = 'S'
            ORDER BY ed18_c_nome
           ";
    $result= pg_query($sql);
    $linhas = pg_num_rows($result);?>
    <select name="escola" style="font-size:9px;width:300px;height:100px;" multiple>
     <?
     for($x=0;$x<$linhas;$x++){
      db_fieldsmemory($result,$x);
      echo "<option value='$ed18_i_codigo'>$ed18_c_nome</option>";
     }
     ?>
    </select>
    <input type="button" name="processar" value="Processar" onclick="js_processar()">
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
function js_ensino(ensino){
 if(document.form1.ano.value==""){
  alert("Inform o ano!");
 }else{
  if(ensino!=""){
   location.href = "edu2_gfcoescfreqseries001.php?serie=0&ensino="+ensino+"&ano="+document.form1.ano.value;
  }else{
   <?if(isset($ensino)){?>
    qtd = document.form1.serie.length;
    for (i = 0; i < qtd; i++) {
     document.form1.serie.options[0] = null;
    }
   <?}?>
   qtd = document.form1.escola.length;
   for (i = 0; i < qtd; i++) {
    document.form1.escola.options[0] = null;
   }
  }
 }
}
function js_serie(serie){
 if(serie!=""){
  location.href = "edu2_gfcoescfreqseries001.php?serie="+serie+"&ensino="+document.form1.ensino.value+"&ano="+document.form1.ano.value;
 }
}

function js_ano(ano){
 <?
 if(isset($ensino)){
  if(isset($serie) && !empty($serie)){?>
   serie = document.form1.serie.value;
  <?}else{?>
   serie = "0";
  <?}?>
  location.href = "edu2_gfcoescfreqseries001.php?serie="+serie+"&ensino="+document.form1.ensino.value+"&ano="+ano;
 <?}?>
}

function js_processar(){
 F = document.form1;
 contescola = 0;
 for (i = 0; i < F.escola.length; i++) {
  if(F.escola.options[i].selected==true){
   contescola++;
  }
 }
 if(F.ano.value=="" || F.ensino.value=="" || F.serie.value==""){
  alert("Preencha todas informações(Ano, Ensino e Etapa)!");
 }else if(contescola<2){
  alert("Escolha no mínimo 2 escolas para realizar comparações!");
 }else if(contescola>15){
  alert("Escolha no máximo 15 escolas para realizar comparações!");
 }else{
  escola = "";
  sep = "";
  for (i = 0; i < F.escola.length; i++) {
   if(F.escola.options[i].selected==true){
    escola += sep+F.escola.options[i].value;
    sep = ",";
   }
  }
  iframe_grafico.location.href = "edu2_gfcoescfreqseries002.php?ano="+F.ano.value+"&ensino="+F.ensino.value+"&serie="+F.serie.value+"&escola="+escola+"&larg_pagina="+(screen.availWidth-60);
 }
}
</script>