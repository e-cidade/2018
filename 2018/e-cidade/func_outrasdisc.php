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
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_diarioresultado_classe.php");
include("classes/db_diariofinal_classe.php");
include("classes/db_regencia_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldiarioresultado = new cl_diarioresultado;
$cldiariofinal = new cl_diariofinal;
$clregencia = new cl_regencia;
$sql = "SELECT ed47_i_codigo as codaluno,ed47_v_nome as nomealuno
        FROM aluno
         inner join diario on ed95_i_aluno = ed47_i_codigo
         inner join diarioresultado on ed73_i_diario = ed95_i_codigo
        WHERE ed73_i_codigo = $codigo
       ";
$result = pg_query($sql);
db_fieldsmemory($result,0);
$sql = "SELECT ed57_i_codigo as turma
        FROM turma
         inner join regencia on ed59_i_turma = ed57_i_codigo
        WHERE ed59_i_codigo = $regencia
       ";
$result = pg_query($sql);
db_fieldsmemory($result,0);
if(isset($coddisciplinas)){
 $result = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("","ed95_i_codigo as codatual",""," ed73_i_codigo = $codigo"));
 db_fieldsmemory($result,0);
 $result = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("","ed95_i_regencia as outrasregs,ed95_i_codigo as coddiariodeste,ed73_i_codigo as outroscodigos",""," ed95_i_regencia in($coddisciplinas) AND ed95_i_aluno = $codaluno AND ed73_i_procresultado = $ed43_i_codigo"));
 for($t=0;$t<$cldiarioresultado->numrows;$t++){
  db_fieldsmemory($result,$t);
  $sql3 = "UPDATE diarioresultado SET ed73_c_aprovmin = '$valor' WHERE ed73_i_codigo = $outroscodigos";
  $result3 = pg_query($sql3);
  if($valor=="S"){
   $valoraprov = "A";
   $valordescrito = "Parecer";
  }elseif($valor=="N"){
   $valoraprov = "R";
   $valordescrito = "Parecer";
  }else{
   $valoraprov = "";
   $valordescrito = "";
  }
  $result_df1 = $cldiariofinal->sql_record($cldiariofinal->sql_query("","ed74_c_resultadofreq,ed74_i_procresultadofreq,ed74_i_percfreq",""," ed74_i_diario = $codatual"));
  db_fieldsmemory($result_df1,0);
  if($ed74_c_resultadofreq=="A" && $valoraprov=="A"){
   $res_final = "A";
  }elseif($ed74_c_resultadofreq=="" || $valoraprov==""){
   $res_final = "";
  }else{
   $res_final = "R";
  }
  $ed74_i_procresultadofreq = $ed74_i_procresultadofreq==""?"null":$ed74_i_procresultadofreq;
  $ed74_i_percfreq = $ed74_i_percfreq==""?"null":$ed74_i_percfreq;
  $sql2 = "UPDATE diariofinal SET
           ed74_i_procresultadoaprov = $ed43_i_codigo,
           ed74_c_resultadoaprov = '$valoraprov',
           ed74_c_valoraprov = '$valordescrito',
           ed74_i_procresultadofreq = $ed74_i_procresultadofreq,
           ed74_c_resultadofreq = '$ed74_c_resultadofreq',
           ed74_i_percfreq = $ed74_i_percfreq,
           ed74_c_resultadofinal = '$res_final'
          WHERE ed74_i_diario = $coddiariodeste";
  $result2 = pg_query($sql2);
  $dataatualiz = date("Y-m-d");
  $sql1 = "UPDATE regencia SET
           ed59_d_dataatualiz = '$dataatualiz'
          WHERE ed59_i_codigo = $outrasregs
         ";
  $result1 = pg_query($sql1);
 }
 ?>
 <script>
  parent.db_iframe_outrasdisc.hide();
 </script>
 <?
 db_msgbox("Alterações efetuadas com sucesso!");
 exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <b>
   Aluno: <?=$nomealuno?><br>
   Selecione as outras disciplinas para<br>
   conter este resultado (<?=$valor=="N"?"REPROVADO":"APROVADO"?>)<br>
   </b>
   <?
   $sql = "SELECT ed59_i_codigo,ed232_c_descr
           FROM regencia
            inner join disciplina on ed12_i_codigo = ed59_i_disciplina
            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
           WHERE ed59_i_turma = $turma
           AND ed59_c_freqglob != 'F'
           EXCEPT
           SELECT ed59_i_codigo,ed232_c_descr
           FROM diarioresultado
            inner join diario on ed95_i_codigo = ed73_i_diario
            inner join regencia on ed59_i_codigo = ed95_i_regencia
            inner join disciplina on ed12_i_codigo = ed59_i_disciplina
            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
           WHERE ed73_i_codigo = $codigo
           AND ed59_c_freqglob != 'F'
           ORDER BY ed59_i_ordenacao
          ";
   $result = pg_query($sql);
   $linhas = pg_num_rows($result);
   if($linhas>0){
    ?>
    <select name="outras_disc[]" id="outras_disc" size="10" style="width:200px;font-size:10px;padding:0px;" multiple>
    <?
    for($r=0;$r<$linhas;$r++){
     db_fieldsmemory($result,$r);
     ?>
      <option value="<?=$ed59_i_codigo?>"> <?=$ed232_c_descr?></option>
     <?
    }
    ?>
    </select>
    <br><br>
    <input type="button" value="Confirmar" onClick="js_confirmaserie();">
    <input type="button" value="Cancelar" onClick="js_fechar();">
   <?}else{
    ?>
    <script>
     parent.db_iframe_outrasdisc.hide();
    </script>
    <?
    exit;
   }?>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
 function js_confirmaserie(){
  qtd = document.form2.outras_disc.length;
  sel = 0;
  coddisciplinas = "";
  sep = "";
  for(i=0;i<qtd;i++){
   if(document.form2.outras_disc.options[i].selected==true){
    sel++;
    coddisciplinas += sep+document.form2.outras_disc.options[i].value;
    sep = ",";
   }
  }
  if(sel==0){
   alert("Seleciona alguma disciplina!");
  }else{
   location.href = "func_outrasdisc.php?coddisciplinas="+coddisciplinas+"&regencia=<?=$regencia?>&ed43_i_codigo=<?=$ed43_i_codigo?>&codigo=<?=$codigo?>&valor=<?=$valor?>";
  }
 }
 function js_fechar(){
  parent.db_iframe_outrasdisc.hide();
 }
</script>