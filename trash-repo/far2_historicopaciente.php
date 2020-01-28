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
include("classes/db_far_retiradaitens_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$cl_far_retiradaitens = new cl_far_retiradaitens;



$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Histórico de Retirada de Medicamentos";
$head3 = "Paciente: $cgs_get - $nome";

$rsHistoricos = pg_query( $cl_far_retiradaitens->sql_query_historicoretiradas($cgs_get, "fa04_d_data, to_char(fa04_d_data, 'dd/mm/yyyy') as fa04_d_data2, fa06_i_matersaude, m60_descr, fa06_f_quant, m77_lote, fa07_i_matrequi, login", "fa04_d_data desc") );
$iLinhas = pg_num_rows($rsHistoricos);

for($iCount = 0; $iCount < $iLinhas; $iCount++){
	db_fieldsmemory($rsHistoricos,$iCount);
	
	if (  ($pdf->gety() > $pdf->h -30) || $iCount == 0 ){
		$pdf->addpage();
		$pdf->setfillcolor(235);
		$pdf->setfont('arial','b',8);
		$pdf->cell(15,4,"Data",1,0,"C",1);
		$pdf->cell(15,4,"Código",1,0,"C",1);   	 
		$pdf->cell(100,4,"Medicamento",1,0,"C",1);   	 
		$pdf->cell(15,4,"Quantidade",1,0,"C",1);   	 
		$pdf->cell(15,4,"Lote",1,0,"C",1);   	 
		$pdf->cell(15,4,"Requisição",1,0,"C",1);   	 
		$pdf->cell(15,4,"Usuário",1,1,"C",1);		
	}
 
	$pdf->setfont('arial','',8);
	$pdf->cell(15,4,"$fa04_d_data2",1,0,"C",0);
	$pdf->cell(15,4,"$fa06_i_matersaude",1,0,"L",0);   	 
	$pdf->cell(100,4,"$m60_descr",1,0,"L",0);   	 
	$pdf->cell(15,4,"$fa06_f_quant",1,0,"C",0);   	 
	$pdf->cell(15,4,"$m77_lote",1,0,"C",0);   	 
	$pdf->cell(15,4,"$fa07_i_matrequi",1,0,"C",0);   	 
	$pdf->cell(15,4,"$login",1,1,"C",0);		
	
}
$pdf->Output();
?>