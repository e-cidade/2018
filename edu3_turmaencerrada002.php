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
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matricula_classe.php");
db_postmemory($_POST);
db_postmemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatricula = new cl_matricula;
$camp = "ed60_d_datasaida as datasaida,
         turma.ed57_c_descr,serie.ed11_c_descr,calendario.ed52_c_descr,cursoedu.ed29_c_descr,ed60_i_numaluno,
         ed47_v_nome,ed47_i_codigo,ed60_c_situacao,ed60_i_codigo,ed60_d_datamatricula,ed60_c_concluida
        ";

$result = $clmatricula->sql_record($clmatricula->sql_query("",$camp,"ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa"," ed60_i_turma = $turma AND ed221_i_serie = $serieregencia"));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 10;
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
<tr>
 <td align="center" valign="top">
  <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
   <tr class='cabec'>
    <td align='center' colspan='7'>
      Turma: <?=pg_result($result,0,"ed57_c_descr")?>&nbsp;&nbsp;&nbsp;&nbsp;
      Etapa: <?=pg_result($result,0,"ed11_c_descr")?>&nbsp;&nbsp;&nbsp;&nbsp;
      Calendário: <?=pg_result($result,0,"ed52_c_descr")?><br>
      Ensino: <?=pg_result($result,0,"ed29_c_descr")?>
    </td>
   </tr>
   <tr><td height='2' colspan='7' bgcolor='#444444'></td></tr>
   <tr bgcolor="#DBDBDB" align="center">
    <td width="5%"><b>N°</b></td>
    <td><b>Aluno</b></td>
    <td><b>Código</b></td>
    <td><b>Situação</b></td>
    <td><b>Data Matrícula</b></td>
    <td><b>Data Saída</b></td>
    <td>&nbsp;</td>
   </tr>
   <?
   for($c=0;$c<$clmatricula->numrows;$c++) {
     
     db_fieldsmemory($result,$c);
    
     if ($trocaturma == 1 && trim($ed60_c_situacao) == "TROCA DE TURMA") {
       continue;
     }
    
     $color = $ed60_c_concluida=="N"?"red":"green";
     ?>
     <tr bgcolor="#f3f3f3">
      <td class="aluno" width="5%" align="center"><?=$ed60_i_numaluno==""?"&nbsp;":$ed60_i_numaluno?></td>
      <td class="aluno"><?=$ed47_v_nome?></td>
      <td class="aluno" align="right"><?=$ed47_i_codigo?></td>
      <td class="aluno" align="center"><?=Situacao($ed60_c_situacao,$ed60_i_codigo)?></td>
      <td class="aluno" align="center"><?=db_formatar($ed60_d_datamatricula,'d')?></td>
      <td class="aluno" align="center"><?=$datasaida==""?"&nbsp;":db_formatar($datasaida,'d')?></td>
      <td class="aluno" align="center" style="color:<?=$color?>;font-weight:bold"><?=$ed60_c_concluida=="N"?"NÃO ENCERRADO":"ENCERRADO"?></td>
     </tr>
    <?
   }
   ?>
  </table>
 </td>
</tr>
</table>
</body>
</html>