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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_turma_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturma = new cl_turma;
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 11;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="1"  align="center" cellspacing="0" cellpading="2" bgcolor="#CCCCCC">
 </tr>
  <td colspan="3">
   <b>Selecione o Calendário:</b>
   <select name="calendario" onChange="js_procurar(this.value)" style="font-size:9px;width:250px;height:18px;">
    <option></option>
    <?
    $sql = "SELECT ed52_i_codigo,ed52_c_descr
            FROM calendario
             inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
            WHERE ed38_i_escola = $escola
            AND ed52_c_passivo = 'N'
            ORDER BY ed52_i_ano DESC
           ";
    $sql_result = pg_query($sql);
    while($row=pg_fetch_array($sql_result)){
     $cod_curso=$row["ed52_i_codigo"];
     $desc_curso=$row["ed52_c_descr"];
     ?>
     <option value="<?=$cod_curso;?>" <?=$cod_curso==@$calendario?"selected":""?>><?=$desc_curso;?></option>
     <?
    }
    ?>
   </select>
  </td>
 </tr>
 <?
 if(isset($calendario)){
  $result = $clturma->sql_record($clturma->sql_query_turmaserie("","DISTINCT ed11_i_ensino,ed11_c_descr,ed220_c_aprovauto,ed57_c_descr,ed10_c_descr,ed223_i_serie,ed10_c_abrev,ed11_i_sequencia,ed57_i_calendario","ed10_c_abrev,ed11_i_sequencia,ed57_c_descr"," ed57_i_calendario = $calendario"));
  $primeiro = "";
  $primeiro1 = "";
  if($clturma->numrows>0){
   for($x=0;$x<$clturma->numrows;$x++){
    db_fieldsmemory($result,$x);
    if($primeiro!=$ed11_i_ensino){
     ?>
     <tr class="titulo">
      <td colspan="3"><?=$ed10_c_descr?></td>
     </tr>
     <?
     $primeiro = $ed11_i_ensino;
    }
    if($primeiro1!=$ed223_i_serie){
     ?>
     <tr class="cabec1">
      <td colspan="3"><?=$ed11_c_descr?></td>
     </tr>
     <tr>
      <td width="5%">&nbsp;</td>
      <td width="20%"><b>Turma</b></td>
      <td><b>Aprovação Automática</b></td>
     </tr>
     <?
     $primeiro1 = $ed223_i_serie;
    }
    ?>
    <tr bgcolor="#f3f3f3">
     <td width="5%">&nbsp;</td>
     <td class="aluno" width="20%">&nbsp;&nbsp;<a href="javascript:parent.db_iframe_geral.hide();parent.location.href='edu4_aprovautomatica001.php?calendario=<?=$ed57_i_calendario?>&serie=<?=$ed223_i_serie?>'"><?=$ed57_c_descr?></a></td>
     <td><?=$ed220_c_aprovauto=="S"?"SIM":"NÃO"?></td>
    </tr>
    <?
   }
  }else{
   ?>
   <tr class="cabec1">
    <td colspan="3" align="center"><br>Nenhuma turma cadastrada neste calendário.<br><br></td>
   </tr>
   <?
  }
 }
 ?>
</table>
</body>
</html>
<script>
function js_procurar(valor){
 if(valor!=""){
  location.href = "edu4_aprovautomatica002.php?calendario="+valor;
 }else{
  location.href = "edu4_aprovautomatica002.php?calendario=0";
 }
}
</script>