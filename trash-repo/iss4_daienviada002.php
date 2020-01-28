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

set_time_limit(0);
include("libs/db_sql.php");
include("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "SECRETARIA DA FAZENDA";
$head4 = "Relatório de DAI's não enviadas";
$linha = 60;
$pdf->SetFillColor(220);
$TPagina = 40;
$sql = "
select * from (
select q02_numcgm,q02_inscr, case when tip1.q81_cadcalc is null then tip2.q81_cadcalc else tip1.q81_cadcalc end as tipo from issbase
		inner join tabativ on 
			q02_inscr = q07_inscr
			and q07_datafi is null 
			and q07_databx is null	
		left outer join tabativtipcalc on 
			q02_inscr = q11_inscr 
		left outer join ativtipo on 
			q80_ativ = q07_ativ
		left outer join tipcalc as tip1 on
		  	tip1.q81_codigo = q80_tipcal
		left outer join tipcalc as tip2 on
			tip2.q81_codigo = q11_tipcalc
		left join cadcalc cad1 on
			tip1.q81_cadcalc = cad1.q85_codigo
		left join cadcalc cad2 on
			tip2.q81_cadcalc = cad2.q85_codigo
) as x
left join db_dae on q02_inscr = w04_inscr
left join cgm on q02_numcgm = z01_numcgm
where tipo = 3 and w04_enviado is not true
order by w04_inscr;		
";
$result = pg_exec($sql);
$num = pg_numrows($result);
$linha = 70;
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(15,05,"Inscrição",1,0,"C",1);
$pdf->Cell(70,05,"Razão Social",1,0,"C",1);
$pdf->Cell(70,05,"Endereço",1,0,"C",1);
$pdf->Cell(25,05,"Telefone",1,1,"C",1);
for($s=0;$s<pg_numrows($result);$s++){
  db_fieldsmemory($result,$s);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(15,05,$q02_inscr,0,0,"C",0);
  $pdf->Cell(70,05,$z01_nome,0,0,"L",0);
  $pdf->Cell(70,05,$z01_ender.', '.$z01_numero.' '.$z01_compl,0,0,"L",0);
  $pdf->Cell(25,05,$z01_telef,0,1,"L",0);
  $linha += 1;
  if($pdf->GetY() > ( $pdf->h - 30 )){
    $linha = 1;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(15,05,"Inscrição",1,0,"C",1);
    $pdf->Cell(70,05,"Razão Social",1,0,"C",1);
    $pdf->Cell(70,05,"Endereço",1,0,"C",1);
    $pdf->Cell(25,05,"Telefone",1,1,"C",1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,05,$q02_inscr,0,0,"C",0);
    $pdf->Cell(70,05,$z01_nome,0,0,"L",0);
    $pdf->Cell(70,05,$z01_ender.', '.$z01_numero.' '.$z01_compl,0,0,"L",0);
    $pdf->Cell(25,05,$z01_telef,0,1,"L",0);
    $linha += 1;
  }
}
$pdf->Cell(25,05,"TOTAL:".$num,0,1,"L",0);
$pdf->Output();
?>