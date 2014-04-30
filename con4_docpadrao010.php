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

include("libs/db_sql.php");
include("fpdf151/pdf1.php");
include("classes/db_db_docparagpadrao_classe.php");
$cldb_docparagpadrao = new cl_db_docparagpadrao;

$clrotulo = new rotulocampo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$pdf = new PDF1(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->Addpage();
$pdf->setfont('arial','b',8);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$result = $cldb_docparagpadrao->sql_record($cldb_docparagpadrao->sql_query($db60_coddoc,"","db_docparagpadrao.*,db61_texto,db61_espaco,db61_alinha,db61_inicia,db60_descr","db62_ordem"));
$numrows = $cldb_docparagpadrao->numrows;
if ($numrows==0){
	  db_redireciona('db_erros.php?fechar=true&db_erro=Dados insuficientes para visualização!!.');
}
$pdf->SetXY('10','60');
   $pdf->SetFont('Arial','b',14);
   db_fieldsmemory($result,0);
   $pdf->cell(0,10," $db60_descr COD $db60_coddoc",0,1,"C",0);
   $pdf->cell(0,10,"",0,1,"R",0);
for($i=0; $i<$numrows; $i++){
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',12);
   $pdf->SetX($db61_alinha);
   $texto=$db61_texto;
   $pdf->SetFont('Arial','',12);
   $pdf->MultiCell("0",4+$db61_espaco,$texto,"0","J",0,$db61_inicia+0);
   $pdf->cell(0,6,"",0,1,"R",0);
}
$pdf->Output();
?>