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

include("fpdf151/pdfwebseller.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_turmaac_classe.php");
include("classes/db_turmaachorario_classe.php");
include("classes/db_diasemana_classe.php");
$clregenciahorario = new cl_regenciahorario;
$cldiasemana = new cl_diasemana;
$clperiodoescola = new cl_periodoescola;
$clescola = new cl_escola;
$clturmaac = new cl_turmaac;
$clturmaachorario = new cl_turmaachorario;
$escola = db_getsession("DB_coddepto");
$result1 = $clturmaachorario->sql_record($clturmaachorario->sql_query("","ed268_c_descr,ed17_i_turno,ed52_c_descr,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome",""," ed270_i_turmaac = $turma")) or die (pg_errormessage());
db_fieldsmemory($result1,0);
if($clturmaachorario->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
if($professor!=""){
 $head5 = "Professor : ". $professor ." ". $z01_nome;
 $condicao = " AND ed270_i_rechumano = $professor"; 
}else{
 $head5 = "Professor: TODOS";
 $condicao = "";  
}
$head1 = "RELATÓRIO DE HORÁRIO DE TURMAS";
$head2 = "Turma: $ed268_c_descr";
$head4 = "Calendário: $ed52_c_descr";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->addpage('P');
$result_add = $clturmaachorario->sql_record($clturmaachorario->sql_query("","ed17_i_turno",""," ed270_i_turmaac = $turma"));
if($clturmaachorario->numrows>0){
 db_fieldsmemory($result_add,0);
 $cod_turnos = "$ed17_i_turno";
}else{
 $cod_turnos = "$ed17_i_turno";
}
$turno = "";
$sql = $clperiodoescola->sql_query("","*","ed15_i_sequencia,ed08_i_sequencia"," ed17_i_escola = $escola AND ed17_i_turno in ($cod_turnos)");
$result1 = $clperiodoescola->sql_record($sql) or die (pg_errormessage());
$contp = 0;
$contd = 0;
for($z=0;$z<$clperiodoescola->numrows;$z++){
 db_fieldsmemory($result1,$z); 
 $result = $cldiasemana->sql_record($cldiasemana->sql_query_rh("","*","ed32_i_codigo"," ed32_i_codigo in (2,3,4,5,6) AND ed04_i_escola = $escola"));
 $pdf->setfillcolor(215);
 $contp++;
 if($turno!=$ed15_c_nome){
  $pdf->setfont('arial','B',9);
  $pdf->cell(195,5,$ed15_i_codigo==$ed17_i_turno?"TURNO PRINCIPAL":"TURNO ADICIONAL",1,1,"C",1);
  $pdf->cell(35,5,trim(pg_result($result1,$z,"ed15_c_nome")),1,0,"C",1);
  if($cldiasemana->numrows==0){
   $pdf->cell(195,5,"Informe os dias lelivos desta escola",1,1,"C",1);
  }
  $qb = 0;
  for($x=0;$x<$cldiasemana->numrows;$x++){
   $contd++;
   db_fieldsmemory($result,$x);
   if($x+1==$cldiasemana->numrows){
    $qb = 1;
   }
   $pdf->cell(32,5,$ed32_c_descr,1,$qb,"C",1);
  }
 }
 $turno = $ed15_c_nome;
 $pdf->setfillcolor(215);
 $pdf->setfont('arial','',9);
 $pdf->cell(35,15,$ed08_c_descr." - ".$ed17_h_inicio." / ".$ed17_h_fim,1,0,"C",1);
 $pdf->setfillcolor(255);
 $pdf->setfont('arial','',8);
 $qb = 2;
 for($x=0;$x<$cldiasemana->numrows;$x++){
  if($x+1==$cldiasemana->numrows){
   $qb = 1;
  }
  $quadro = "Q".$z.$x;
  db_fieldsmemory($result,$x);
  
  $sql  = " select distinct ed20_i_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed268_c_descr";
  $sql .= " from turmaachorario ";
  $sql .= "  inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
  $sql .= "  inner join periodoescola  on  periodoescola.ed17_i_codigo = turmaachorario.ed270_i_periodo"; 
  $sql .= "  inner join rechumano  on  rechumano.ed20_i_codigo = turmaachorario.ed270_i_rechumano";
  $sql .= "  inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorario.ed270_i_diasemana";
  $sql .= "  inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
  $sql .= "  inner join escola  as a on   a.ed18_i_codigo = periodoescola.ed17_i_escola";
  $sql .= "  inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
  $sql .= "  inner join turno  as b on   b.ed15_i_codigo = periodoescola.ed17_i_turno";
  $sql .= "  left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
  $sql .= "  left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
  $sql .= "  left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
  $sql .= "  left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
  $sql .= "  left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
  $sql .= " where ed270_i_diasemana = $ed32_i_codigo";
  $sql .= " AND ed270_i_periodo = $ed17_i_codigo";
  $sql .= " AND ed270_i_turmaac = $turma";  
  $sql .= " AND ed268_i_escola = $escola $condicao";    
  $result2 = pg_query($sql);
  $linhas2 = pg_num_rows($result2);
  if($linhas2>0){
   db_fieldsmemory($result2,0);
   $regente = $z01_nome;
   $disci = $ed268_c_descr;
   $cor = "red";
  }else{
   $regente = "";
   $disci = "";;
   $cor = "green";
  }
  $posy = $pdf->getY();
  $posx = $pdf->getX();
  $ed20_i_codigo=pg_result($result2,0);
  //die($ed20_i_codigo);
  if($professor=="" || $professor==$ed20_i_codigo || $linhas2==0){
   $pdf->setfont('arial','B',8);
   $pdf->cell(32,5,substr($disci,0,15),"LRT",2,"C",1);
   $pdf->setfont('arial','',8);
   $pdf->cell(32,10,substr($regente,0,15),"LRB",$qb,"C",1);
  }else{
   $pdf->cell(32,15,"",1,$qb,"C",1);
  }
  if($qb!=1){
   $pdf->setY($posy);
   $pdf->setX($posx+32);
  }
  $regente = "";
  $ed20_i_codigo = "";
 }
}
$pdf->Output();
?>