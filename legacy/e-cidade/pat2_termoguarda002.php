<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_db_docparag_classe.php");
include("classes/db_bensguarda_classe.php");
include("classes/db_bensguardaitem_classe.php");
$cldb_docparag = new cl_db_docparag;
$clbensguarda = new cl_bensguarda;
$clbensguardaitem = new cl_bensguardaitem;
$clrotulo = new rotulocampo;
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where_instit = "t21_instit = ".db_getsession("DB_instit");
$result_bensguarda = $clbensguarda->sql_record($clbensguarda->sql_query_file(null,"*",null,"t21_codigo = $codigo and $where_instit"));

if ($clbensguarda->numrows==0) {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_termoguarda002.nao_existem_registros');
	db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
	exit;	
	
}else{
	db_fieldsmemory($result_bensguarda,0);
}

$pdf = new PDF1(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->Addpage();
$pdf->setfont('arial','b',8);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$result = $cldb_docparag->sql_record($cldb_docparag->sql_query_doc("","","db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem","db03_tipodoc = 6"));
$numrows = $cldb_docparag->numrows;
$pdf->SetXY('10','60');
$pdf->SetFont('Arial','b',14);
$pdf->cell(0,10,"TERMO DE GUARDA N° $t21_codigo",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
for($i=0; $i<$numrows; $i++){
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',12);
   $pdf->SetX($db02_alinha);
   $texto=db_geratexto($db02_texto);
   $pdf->SetFont('Arial','',12);
   $pdf->MultiCell("0",4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
   $pdf->cell(0,6,"",0,1,"R",0);
}
$pdf->SetFont('Arial','',12);
$result_item=$clbensguardaitem->sql_record($clbensguardaitem->sql_query_dev(null,"*",null,"t22_bensguarda=$t21_codigo and t23_guardaitem is null"));

if ($clbensguardaitem->numrows>0){
  
  $pdf->cell(50,6,'Código do Bem',1,0,"C",0);
  $pdf->cell(50,6,'Placa',1,0,"C",0);
  $pdf->cell(0,6,'Bem',1,1,"L",0);
  
	for($w=0;$w<$clbensguardaitem->numrows;$w++){
		db_fieldsmemory($result_item,$w);		
		$pdf->cell(50,6,$t22_bem,0,0,"C",0);
		$pdf->cell(50,6,$t41_placa.' '.$t41_placaseq,0,0,"C",0);
		$pdf->cell(0,6,$t52_descr,0,1,"L",0);
	}
}
$pdf->ln(2);
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(90,4,"",0,0,"C",0);
$pdf->cell(90,4,"___________________________",0,1,"C",0);
$pdf->cell(90,4,"",0,0,"C",0);
$pdf->cell(90,4,"Responsável",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->cell(0,4,"Data:___/___/_____ ",0,1,"R",0);
$pdf->Output();
?>