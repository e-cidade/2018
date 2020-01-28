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
include("classes/db_zonasvalor_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clzonasvalor = new cl_zonasvalor;
$clrotulo = new rotulocampo;
$clrotulo->label("j50_zona");
$clrotulo->label("j50_descr");
$clrotulo->label("j51_valorm2t");
$head3 = "RELATÓRIO DE ZONAS FISCAIS: " . $anousu;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$result = $clzonasvalor->sql_record($clzonasvalor->sql_query("",$anousu));
$num = $clzonasvalor->numrows;
$pdf->SetFont('Arial','B',9);
$pdf->Cell(15,6,$RLj50_zona."",1,0,"C",1);
$pdf->Cell(60,6,$RLj50_descr."",1,0,"C",1);
$pdf->Cell(50,6,$RLj51_valorm2t."",1,1,"C",1);
for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i);
  $pdf->cell(15,6,$j50_zona,1,0,"C");
  $pdf->cell(60,6,$j50_descr,1,0,"C");
  $pdf->cell(50,6,db_formatar($j51_valorm2t,'f'),1,1,"R");
}
$pdf->Ln(5);
$pdf->Output();

?>