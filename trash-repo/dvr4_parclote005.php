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

require("fpdf151/pdf.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$sql = "select * from loteam where j34_loteam = $loteam";
$result = pg_exec($sql);
db_fieldsmemory($result,0);

$sql = "select  b.j01_matric,
				b.j01_numcgm ,
        		d.*,
				e.z01_nome,
				e.z01_ender,
				e.z01_numero,
				e.z01_compl
		from loteloteam a
        		inner join iptubase b           on b.j01_idbql = a.j34_idbql
												and b.j01_baixa is null
        		inner join divermatric c        on b.j01_matric = c.matricula
        		inner join diversos d           on d.coddiver = c.coddiver
												and d.exerc = $DB_anousu
			inner join proprietario e 	on b.j01_matric = e.j01_matric 
		where j34_loteam = $loteam
		order by z01_nome;
		";
$principal= pg_exec($sql);
$tipo = 25;   
//flush();
$head3 = 'LOTEAMENTO : '.$j34_descr;
$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(12,4,'MATR.',1,0,"C",0);
$pdf->Cell(80,4,'NOME',1,0,"C",0);
$pdf->Cell(100,4,'ENDEREÇO',1,1,"C",0);
$total = 0;
$num = pg_numrows($principal);
for($i = 0;$i < $num ;$i++) {
   db_fieldsmemory($principal,$i) ;
   if($pdf->GetY() > ( $pdf->h - 30 )){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(12,4,'MATR.',1,0,"C",0);
      $pdf->Cell(80,4,'NOME',1,0,"C",0);
      $pdf->Cell(100,4,'ENDEREÇO',1,1,"C",0);
   } 
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(12,4,$j01_matric,0,0,"R",0);
   $pdf->Cell(80,4,$z01_nome,0,0,"L",0);
   $pdf->Cell(100,4,$z01_ender.', '.$z01_numero.'  '.$z01_compl,0,1,"L",0);
   $total += 1;
}
$pdf->SetFont('Arial','B',8);
$pdf->Ln(5);
$pdf->Cell(192,4,'Total de Registros  :     '.$total,"TB",1,"L",0);

$pdf->Output();
//echo "<script> location.href='dvr3_parclote003.php'</script>";

?>