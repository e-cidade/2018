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

include("fpdf151/pdf.php");
include("classes/db_orcfontesdes_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrotulo = new rotulocampo;
$clrotulo->label("o57_fonte");
$clrotulo->label("o57_descr");
$clrotulo->label("o60_anousu");
$clrotulo->label("o60_perc");


$clorcfontesdes = new cl_orcfontesdes;

$clorcfontesdes->sql_record($clorcfontesdes->sql_query_file($anousu));
if ( $clorcfontesdes->numrows ==0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
}

$sql="
       select o57_fonte,o60_codfon,o57_descr,o60_anousu,o60_perc 
       	   from orcfontes
	    	left outer join orcfontesdes on orcfontesdes.o60_codfon = orcfontes.o57_codfon      
	where o57_anousu = $anousu and ( o60_anousu = $anousu or o60_anousu is null )
	     order by o57_fonte
     ";
$result  = $clorcfontesdes->sql_record($sql);     
$numrows = $clorcfontesdes->numrows; 



$pdf = new pdf("L");
$largura = 6;
$pdf->Open();
$head1 = "$RLo60_anousu:$anousu";
$pdf->AliasNbPages();
$pdf->AddPage("P");
$pdf->SetFillColor(220);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(40,$largura,$RLo57_fonte,1,0,"C",0);
$pdf->Cell(100,$largura,$RLo57_descr,1,0,"L",0);
$pdf->Cell(19,$largura,$RLo60_perc,1,0,"C",0);
$pdf->Ln();
$novo=true;
$tot=0;
for($i=0;$i<$numrows;$i++){
  db_fieldsmemory($result,$i);
  if($o60_codfon!='' && $o60_anousu==$anousu ){
    
    if ($pdf->gety() > ($pdf->h-40)) {
	$pdf->AddPage("P");
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(40,$largura,$RLo57_fonte,1,0,"C",0);
 	$pdf->Cell(100,$largura,$RLo57_descr,1,0,"L",0);
 	$pdf->Cell(19,$largura,$RLo60_perc,1,0,"C",0);
        $pdf->Ln();
    }
    $pdf->SetFont('Arial','',8);
    if($novo==true){
      db_fieldsmemory($result,($i-1));
      $pdf->Cell(40,$largura,db_formatar($o57_fonte,"receita"),1,0,"C",1);
      $pdf->Cell(100,$largura,$o57_descr,1,0,"L",1);
      $pdf->Cell(19,$largura,$o60_perc."",1,0,"C",1);
      $pdf->Ln();
      $novo=false;
    }
    db_fieldsmemory($result,$i);
    $pdf->Cell(40,$largura,db_formatar($o57_fonte,"receita"),1,0,"C",0);
    $pdf->Cell(100,$largura,$o57_descr,1,0,"C",0);
    $pdf->Cell(19,$largura,$o60_perc."%",1,0,"C",0);
    $pdf->Ln();
    $tot+=$o60_perc; 
  }else{
    if($novo==false){
      $pdf->Cell(129,$largura,"TOTAL: $tot%",0,0,"R",0);
      $tot=0;
      $pdf->Ln();
    }  
    $novo=true;
  }
}
$pdf->Output();
?>