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
include("classes/db_conplanosis_classe.php");
include("libs/db_libcontabilidade.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrotulo = new rotulocampo;
$clrotulo->label("c64_codpla");
$clrotulo->label("c64_estrut");
$clrotulo->label("c64_descr");


$clconplanosis = new cl_conplanosis;

$result  = $clconplanosis->sql_record($clconplanosis->sql_query_file(null,'*','c64_estrut'));
$numrows = $clconplanosis->numrows; 
if ( $numrows ==0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
}





$pdf = new pdf("L");
$largura = 6;
$pdf->Open();
$head1 = "SISTEMAS DE CONTAS";
$pdf->AliasNbPages();
$pdf->AddPage("P");
$pdf->SetFillColor(220);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,$largura,$RLc64_codpla,0,0,"C",0);
$pdf->Cell(30,$largura,$RLc64_estrut,0,0,"C",0);
$pdf->Cell(80,$largura,$RLc64_descr,0,0,"L",0);
$pdf->Ln();
$novo=true;
$tot=0;
for($i=0;$i<$numrows;$i++){
  db_fieldsmemory($result,$i);
   
    if ($pdf->gety() > ($pdf->h-40)) {
	$pdf->AddPage("P");
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(25,$largura,$RLc64_codpla,0,0,"C",0);
	$pdf->Cell(30,$largura,$RLc64_estrut,0,0,"C",0);
	$pdf->Cell(80,$largura,$RLc64_descr,0,0,"L",0);
        $pdf->Ln();
    }
    $nivel = db_le_mae_sistema($c64_estrut,true);
    
    switch($nivel){
      case 1:
              $espaco="     ";
	      break;
      case 2:
              $espaco="          ";
	      break;
      case 3:
              $espaco="               ";
	      break;
      case 4:
              $espaco="                    ";
	      break;
      case 5:
              $espaco="                          ";
	      break;
      case 6:
              $espaco="                                ";
	      break;
      case 7:
              $espaco="                                      ";
	      break;
      case 8:
              $espaco="                                            ";
	      break;
      
    }
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(25,$largura,$c64_codpla,0,0,"C",0);
    $pdf->Cell(30,$largura,db_formatar($c64_estrut,"sistema"),0,0,"C",0);
    $pdf->Cell(80,$largura,$espaco.$c64_descr,0,0,"L",0);
    $pdf->Ln();
}
$pdf->Output();
?>