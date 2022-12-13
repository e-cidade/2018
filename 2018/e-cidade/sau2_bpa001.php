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
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "ARQUIVO: $bpa";
$head3 = "DATA: ".date ( 'd/m/Y', db_getsession ( "DB_datausu" ) );


$aBPA = file( $bpa );
$sTipo = "";
$sTipoant = "";
$iReg = 0;

foreach ($aBPA as $iLine => $sLine) {
	$aTipo = explode( ":", $sLine );
	$sTipo = $aTipo[0];
	$aDesc = explode( ",", $aTipo[1] );
	
	if (  ($pdf->gety() > $pdf->h -30) || $iLine == 0 || $sTipo != $sTipoant ){
		if( $iLine > 0 && $sTipo != $sTipoant ){
			$pdf->cell(170,4,"TOTAL DE REGISTROS: $iReg",1,1,"C",0);
			$iReg = 0; 
		}
		$pdf->addpage();
		$pdf->setfillcolor(235);
		$pdf->setfont('arial','b',10);
		$pdf->cell(170,4,$sTipo,1,1,"C",1);
		if( sizeof($aDesc) > 1){
			$pdf->cell(30,4,"Código",1,0,"C",0);
			$pdf->cell(100,4,"Nome",1,0,"C",0);
			$pdf->cell(40,4,"CNS",1,1,"C",0);
		}
		$sTipoant = $sTipo;
	}
	
	$pdf->setfont('arial','',10);
	if( sizeof($aDesc) > 1){
		$pdf->cell(30,4,$aDesc[0],1,0,"L",0);
		$pdf->cell(100,4,$aDesc[1],1,0,"L",0);
		$pdf->cell(40,4,$aDesc[2],1,1,"L",0);
	}else{
		$pdf->cell(170,4,$aDesc[0],1,1,"L",0);		
	}
	$iReg++;
}
$pdf->cell(170,4,"TOTAL DE REGISTROS: $iReg",1,1,"C",0);	

$pdf->Output();
?>