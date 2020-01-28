<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_turmaturnoadicional_classe.php");
include("classes/db_diasemana_classe.php");
$clregenciahorario     = new cl_regenciahorario;
$cldiasemana           = new cl_diasemana;
$clperiodoescola       = new cl_periodoescola;
$clescola              = new cl_escola;
$clturma               = new cl_turma;
$clturmaturnoadicional = new cl_turmaturnoadicional;
$escola                = db_getsession("DB_coddepto");
$result1               = $clturma->sql_record($clturma->sql_query_turmaserie("","ed57_i_turno",""," ed220_i_codigo = $turma")) or die (pg_errormessage());
db_fieldsmemory($result1,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<center>
<table width="100%" cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
  <?
  $result_add = $clturmaturnoadicional->sql_record($clturmaturnoadicional->sql_query("","ed246_i_turno",""," ed246_i_turma = $turma"));
  if($clturmaturnoadicional->numrows>0){
   db_fieldsmemory($result_add,0);
   $cod_turnos = "$ed57_i_turno,$ed246_i_turno";
  }else{
   $cod_turnos = "$ed57_i_turno";
  }
  $turno = "";
  $sql = $clperiodoescola->sql_query("","*","ed15_i_sequencia,ed08_i_sequencia"," ed17_i_escola = $escola AND ed17_i_turno in ($cod_turnos)");
  $result1 = $clperiodoescola->sql_record($sql) or die (pg_errormessage());
  //db_criatabela($result1);
  //exit;
  $contp = 0;
  $contd = 0;
  for($z=0;$z<$clperiodoescola->numrows;$z++){
  db_fieldsmemory($result1,$z);
   $contp++;
   $result = $cldiasemana->sql_record($cldiasemana->sql_query_rh("","*","ed32_i_codigo"," ed04_c_letivo = 'S' AND ed04_i_escola = $escola"));
   if($turno!=$ed15_c_nome){
    ?>
    <tr><td colspan="<?=$cldiasemana->numrows+1?>" bgcolor=""><b><?=$ed15_i_codigo==$ed57_i_turno?"TURNO PRINCIPAL":"TURNO ADICIONAL"?></b></td></tr>
    <tr bgcolor="#444444">
    <td align="center" style="font-weight: bold; color: #DEB887;"><?=pg_result($result1,$z,"ed15_c_nome");?>
    </td>
    <?
    if($cldiasemana->numrows==0){
     ?><tr><td><a href="javascript:parent.location.href='edu1_diasemanaabas001.php'"><b>Informe os dias lelivos desta escola</b></a></td></tr><?
    }
    for($x=0;$x<$cldiasemana->numrows;$x++){
     $contd++;
     db_fieldsmemory($result,$x);?>
     <td>
      <table cellspacing="0" cellpading="0" >
       <tr>
        <td width="120" style="font-weight: bold; color: #DEB887;">
         <div align="center"><?=trim($ed32_c_descr)?></div>
        </td>
       </tr>
      </table>
     </td>
    <?}?>
    </tr>
   <?}
   $turno = $ed15_c_nome?>
   <td align="center" width="120" height="60" style="font-weight: bold; background-color: #f3f3f3;">
    <?=$ed08_c_descr?> - <?=$ed17_h_inicio?> / <?=$ed17_h_fim?>
   </td><?
   for($x=0;$x<$cldiasemana->numrows;$x++){
    $quadro = "Q".$z.$x;
    db_fieldsmemory($result,$x);
    $sql2 ="SELECT ed20_i_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed232_c_descr
            FROM regenciahorario
             inner join regencia on regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
             inner join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina
             inner join caddisciplina on  ed232_i_codigo = ed12_i_caddisciplina
             inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma
             inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
             inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
             inner join serie on ed11_i_codigo = ed223_i_serie
             inner join rechumano on rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano
             left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
             left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
             left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
             left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
             left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
            WHERE ed58_i_diasemana = $ed32_i_codigo and ed58_ativo is true  
            AND ed58_i_periodo = $ed17_i_codigo
            AND ed220_i_codigo = $turma
            AND ed57_i_escola = $escola
            AND ed223_i_serie = ed59_i_serie
           ";
    //db_criatabela($result2);
    //exit;
    $result2 = db_query($sql2);
    $linhas2 = pg_num_rows($result2);
    if($linhas2>0){
     db_fieldsmemory($result2,0);
     $regente = $z01_nome;
     $disci = $ed232_c_descr;
     $cor = "red";
    }else{
     $regente = "HORÁRIO<br>LIVRE";
     $disci = "";;
     $cor = "green";
    }
    ?>
    <td style="font-size:11px;background:#DBDBDB;" align="center">
     <?
     if($professor=="" || $professor==$ed20_i_codigo || $linhas2==0){
      ?>
      <b><?=$disci?></b><br>
      <font color="<?=$cor?>"><?=$regente?></font>
      <?
     }else{
      echo "&nbsp";
     }
     ?>
    </td>
   <?
   $regente = "";
   $ed20_i_codigo = "";
   }
   ?>
   <tr>
   <?}?>
 </tr>
</table>
</body>
</html>