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
include("classes/db_regencia_classe.php");
include("classes/db_regenciaperiodo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clregencia = new cl_regencia;
$clregenciaperiodo = new cl_regenciaperiodo;
$result = $clregencia->sql_record($clregencia->sql_query("","*","","ed59_i_codigo = $regencia"));
db_fieldsmemory($result,0);
if(trim($ed57_c_medfreq)=="PERÌODOS"){
 $tipofreq = "Aulas Dadas";
}else{
 $tipofreq = "Dias Letivos";
}
if(isset($disciplina_codigos)){
 $cod_unit = explode(",",$disciplina_codigos);
 for($x=0;$x<count($cod_unit);$x++){
  db_inicio_transacao();
  $clregenciaperiodo->ed78_i_aulasdadas = $aulasdadas;
  $clregenciaperiodo->ed78_i_codigo = $cod_unit[$x];
  $clregenciaperiodo->alterar($cod_unit[$x]);
  db_fim_transacao();
 }
 $clregenciaperiodo->erro(true,false);
 ?>
 <script>
  parent.db_iframe_outrareg.hide();
 </script>
 <?
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
   <b>Selecione as outras disciplinas<br>para conter as alterações de <?=$tipofreq?>.<br></b>
   <?
   //$result1 = $clregencia->sql_record($clregencia->sql_query("","*","ed232_c_descr","ed59_i_turma = $ed59_i_turma AND ed59_i_codigo != $regencia"));
   $sql1 = "SELECT ed78_i_codigo,ed232_c_descr
            FROM regenciaperiodo
             inner join regencia on ed59_i_codigo = ed78_i_regencia
             inner join disciplina on ed12_i_codigo = ed59_i_disciplina
             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
            WHERE ed59_i_turma = $ed59_i_turma
            AND ed59_i_serie = $ed59_i_serie
            AND ed78_i_procavaliacao = $avaliacao
            AND ed78_i_regencia != $regencia
            ORDER BY ed59_i_ordenacao
           ";
   $result1 = pg_query($sql1);
   $linhas1 = pg_num_rows($result1);
   ?>
   <select name="disciplinas[]" id="disciplinas" size="10" style="font-size:9px;width:330px;" multiple>
   <?
   for($x=0;$x<$linhas1;$x++){
    db_fieldsmemory($result1,$x);
    echo "<option value='$ed78_i_codigo'>$ed232_c_descr</option>";
   }
   ?>
   </select><br><br>
   <input type="button" value="Confirmar" onClick="js_confirmadisciplina();">
   <input type="button" value="Cancelar" onClick="js_fechar();">
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
 function js_confirmadisciplina(){
  qtd = document.form2.disciplinas.length;
  sel = 0;
  coddisciplina = "";
  sep = "";
  for(i=0;i<qtd;i++){
   if(document.form2.disciplinas.options[i].selected==true){
    sel++;
    coddisciplina += sep+document.form2.disciplinas.options[i].value;
    sep = ",";
   }
  }
  if(sel==0){
   alert("Seleciona alguma Disciplina!");
  }else{
   location.href = "func_outrareg.php?disciplina_codigos="+coddisciplina+"&regencia=<?=$regencia?>&nabas=<?=$nabas?>&avaliacao=<?=$avaliacao?>&aulasdadas=<?=$aulasdadas?>";
  }
 }
 function js_fechar(){
  parent.db_iframe_outrareg.hide();
 }
</script>