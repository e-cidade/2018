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
include("libs/db_liborcamento.php");
include("libs/db_utils.php");
include("classes/db_cidadao_classe.php");

$clcidado = new cl_cidadao();

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);

$datausu	 = 	date('Y-m-d',db_getsession('DB_datausu'));

$sCampos	=	" ov02_sequencial, ";
$sCampos .=	" ov02_seq,        ";
$sCampos .=	" ov02_nome,       ";
$sCampos .=	" ov16_descricao,  ";
$sCampos .=	" (select ov03_numcgm ";
$sCampos .=	"    from cidadaocgm  "; 
$sCampos .=	"   where cidadaocgm.ov03_cidadao = cidadao.ov02_sequencial limit 1) as ov03_numcgm ";

$sWhere 	= " ov02_ativo is true and ov02_situacaocidadao = 4 ";

$rsQueryCidadao = $clcidado->sql_record($clcidado->sql_query(null,null,$sCampos,'ov03_numcgm,ov02_nome,ov02_sequencial',$sWhere));

if ($clcidado->numrows > 0){
	$aDados = db_utils::getColectionByRecord($rsQueryCidadao,false,false,false);
} else {
	db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum dado para gerar relatório!');
}

/*  
		$rsQueryProcessos	= pg_query($sQueryProcessos);
		if(pg_num_rows($rsQueryProcessos)>0){
			$aDados = db_utils::getColectionByRecord($rsQueryProcessos,false,false,false);
		}else{
			db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum processo encontrado!');
		}
	
*/

$head2 = 'Relatório de Autorização de Atualização de CGM';
$head3 = '';
$head4 = 'Data de Emissão: '.date('d/m/Y',db_getsession('DB_datausu'));
$head5 = '';

$pdf_cabecalho = true;
$pdf = new PDF("P", "mm", "A4"); 
$pdf->Open();
$pdf->AliasNbPages(); 

$iNumRows 	= count($aDados);
$background = 0;
for($iInd=0; $iInd<$iNumRows; $iInd++){

	$pdf->SetAutoPageBreak(false);

	if($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true){
		$pdf_cabecalho = false;  
		$pdf->SetFont('Courier','',8);
	  $pdf->SetTextColor(0,0,0);
	  $pdf->setfillcolor(235);
	  $preenc = 0;
	  $linha = 1;
	  $bordat = 0;
	  $pdf->AddPage('L');
	  $pdf->SetFont('Arial','b',7);
	  $pdf->ln(2);
		$pdf->Cell(15,5,""             ,0,0,"C",0);
		$pdf->Cell(20,5,"Código"             ,1,0,"C",1);
		$pdf->Cell(20,5,"CGM"							   ,1,0,"C",1);
		$pdf->Cell(150,5,"Nome"			         ,1,0,"C",1);
		$pdf->Cell(30,5,"Situação" 		       ,1,0,"C",1);
		$pdf->Cell(30,5,"Situação CGM"       ,1,1,"C",1);
		
		$pdf_cabecalho == false;
	}  
	  
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(15,5,""             ,0,0,"C",0);
	$pdf->Cell(20,5,$aDados[$iInd]->ov02_sequencial,0,0,"C",$background);
  $pdf->Cell(20,5,$aDados[$iInd]->ov03_numcgm		 ,0,0,"C",$background);
	$pdf->Cell(150,5,$aDados[$iInd]->ov02_nome     ,0,0,"L",$background);
	$pdf->Cell(30,5,$aDados[$iInd]->ov16_descricao ,0,0,"C",$background);
	$strStiacaoCGM = $aDados[$iInd]->ov03_numcgm != "" ? "Alterar no CGM" : "Incluir no CGM"; 
	$pdf->Cell(30,5,$strStiacaoCGM,0,1,"C",$background);
	$background = $background == 0 ? 1 : 0;
	
}
	$pdf->Ln(4);
	$pdf->Cell(15,5,"",0,0,"C",0);
	$pdf->Cell(220,5,'Total de Registros:','',0,"R",1);
	$pdf->Cell(30,5,$iNumRows,'',1,"R",1);
	$pdf->Output();

?>