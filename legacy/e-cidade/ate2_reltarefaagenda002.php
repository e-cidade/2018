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
 

require ('fpdf151/pdf.php');
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$pdf = new PDF();
$head1 = "RELATÓRIO TAREFAS";
$head2 = "";

$sql = "select * from tarefa_agenda where at13_tarefa = 6";
$result = pg_exec($sql);

$numlinha = pg_numrows($result);

$pdf->open();
$total_geral = 0;
$pdf->settextcolor(0,0,0);
$pdf->setfillcolor(220);
$pdf->setfont('Arial','B',9);
$pdf->addpage('L');

for($x=0; $x< $numlinha;$x++) {
  db_fieldsmemory($result,$x);

  $horarioini 	= split(":",$at13_horaini);
  if (sizeof($horarioini) == 0) {
    
    if (strlen($at13_horaini) == 4) {
      $horaini	 = substr($at13_horaini,0,2);
      $minutoini = substr($at13_horaini,2,2);
    } else {
      continue;
    }
    
  } else {
    $horaini 	= $horarioini[0];
    $minutoini 	= $horarioini[1]; 
  }

  die("at13_dia: $at13_dia - " . date("Y",$at13_dia));
  
  $agenda_ini = mktime($horaini,$minutoini,0,date("d",$at13_dia),date("m",$at13_dia),date("y",$at13_dia));
  die("x: $agenda_ini - $at13_dia");


  $horariofim 	= split(":",$at13_horafim);
  if (sizeof($horariofim) == 0) {
    
    if (strlen($at13_horafim) == 4) {
      $horafim	 = substr($at13_horafim,0,2);
      $minutofim = substr($at13_horafim,2,2);
    } else {
      continue;
    }
    
  } else {
    $horafim 	= $horariofim[0];
    $minutofim 	= $horariofim[1]; 
  }

  $agenda_fim = mktime($horafim,$minutofim,0,date("d",$at13_dia),date("m",$at13_dia),date("y",$at13_dia));

//  if (($pdf->gety() > ($pdf->h - 30)) || $x == 0 ){
//    $pdf->addpage('L');
//    $pdf->cell(80,6,"CLIENTE",1,0,"C",1);
//    $pdf->cell(60,6,"ATENDIMENTO MAIS SOLICITADO",1,0,"C",1);
//    $pdf->cell(45,6,"N. TECNICOS ENVOLVIDOS",1,0,"C",1);
//    $pdf->cell(35,6,"N. DIAS EM ATEND",1,0,"C",1);
//    $pdf->cell(60,6,"N. DE HORAS DISPENDIDAS",1,0,"C",1);
//    $pdf->Ln();
//  }

  $pdf->cell(20,6,$at13_sequencial,1,0,"L",0);
  $pdf->cell(20,6,$at13_dia,1,0,"L",0);
  $pdf->cell(40,6,$agenda_ini,1,0,"L",0);
  $pdf->ln();

} 
	
//$pdf->cell(145,$tamanho,$nomestec,1,1,"C",0);
//$pdf->Ln();
    
$pdf->Output();

?>