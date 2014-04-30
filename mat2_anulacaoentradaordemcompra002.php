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
include("libs/db_utils.php");
include("classes/db_db_almox_classe.php");

$oGet = db_utils::postMemory($_GET,0);

$cldb_almox = new cl_db_almox;
$iInstit    = db_getsession("DB_instit");
$sLetra     = 'arial';
$sWhere     = "";

if (isset($oGet->coddpto) && !empty($oGet->coddpto)) {
	
	$sWhere      = "and m70_coddepto = {$oGet->coddpto}";
	$sSqlDbAlmox = $cldb_almox->sql_query(null,"descrdepto",null,"m91_depto = {$oGet->coddpto}");
	$rsDbAlmox   = $cldb_almox->sql_record($sSqlDbAlmox);
	if ($cldb_almox->numrows > 0) {
		
		$oDbAlmox  = db_utils::fieldsMemory($rsDbAlmox,0);
  	$head5     = "Almoxarifado: {$oDbAlmox->descrdepto}";
	}
}

$dtInicial = implode("-", array_reverse(explode("/", $oGet->dtInicial)));
$dtFinal   = implode("-", array_reverse(explode("/", $oGet->dtFinal)));

$sSql  = "    select m80_data,                                                                                        ";
$sSql .= "           m52_codordem,                                                                                    ";
$sSql .= "           m80_codigo,                                                                                      ";
$sSql .= "           m60_codmater,                                                                                    ";
$sSql .= "           m60_descr,                                                                                       ";
$sSql .= "           e62_descr,                                                                                       ";
$sSql .= "           m52_codlanc,                                                                                     ";
$sSql .= "           m70_coddepto,                                                                                    ";
$sSql .= "           descrdepto,                                                                                      ";
$sSql .= "           m82_quant,                                                                                       ";
$sSql .= "           e62_vlrun as valorunitario                                                                       ";
$sSql .= "      from matestoqueini                                                                                    ";
$sSql .= "           inner join matestoqueinimei on m82_matestoqueini     = m80_codigo                                ";
$sSql .= "           inner join matestoqueitem   on m82_matestoqueitem    = m71_codlanc                               ";
$sSql .= "           inner join matestoqueitemoc on m73_codmatestoqueitem = m71_codlanc                               ";
$sSql .= "           inner join matestoque       on m71_codmatestoque     = m70_codigo                                ";
$sSql .= "           inner join matmater         on m70_codmatmater       = m60_codmater                              "; 
$sSql .= "           inner join db_depart        on m70_coddepto          = coddepto                                  ";
$sSql .= "           inner join matordemitem     on m52_codlanc           = m73_codmatordemitem                       ";
$sSql .= "           inner join empempitem       on m52_numemp            = e62_numemp                                ";
$sSql .= "                                      and m52_sequen            = e62_sequen                                "; 
$sSql .= "     where m80_codtipo = 19                                                                                 ";
$sSql .= "       and m80_data between '{$dtInicial}'  and  '{$dtFinal}'                                               "; 
$sSql .= "  {$sWhere}                                                                                                 ";
$sSql .= "       and instit = {$iInstit}                                                                              ";
$sSql .= "  order by m70_coddepto,m80_data,m52_codordem                                                               ";

$rsSql = db_query($sSql);
$iRows = pg_numrows($rsSql);

if ($iRows == 0){
  $sMsg = "Nenhum registro encontrado.";
  db_redireciona("db_erros.php?fechar=true&db_erro=$sMsg");
}

$head2 = "RELATÓRIO DE ANULAÇÃO DE ENTRADA DE ORDEM DE COMPRA";
$head4 = "Periodo: {$oGet->dtInicial} até {$oGet->dtFinal}";

$pdf = new PDF();
$pdf->Open();
$pdf->addpage();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('arial','b',8);

$aDadosMatEstoque    = array();
$lImprime            = true;
$nTotalItens         = 0;
$nTotalGeralQuant    = 0;
$nTotalGeralVlrUnit  = 0;
$nTotalGeralVlrTotal = 0;
$nTotalGeralItens    = 0;

for ( $iInd = 0; $iInd  < $iRows; $iInd++ ) {
          
  $oDados = db_utils::fieldsMemory($rsSql,$iInd);

    $oDadosMatEstoque = new stdClass();
    $oDadosMatEstoque->CodDepart  = $oDados->m70_coddepto;
    $oDadosMatEstoque->DescrDepto = $oDados->descrdepto;
    $oDadosMatEstoque->Data       = $oDados->m80_data;
    $oDadosMatEstoque->CodOC      = $oDados->m52_codordem;
    $oDadosMatEstoque->CodMater   = $oDados->m60_codmater;
    $oDadosMatEstoque->Descricao  = $oDados->m60_descr;
    $oDadosMatEstoque->Quant      = $oDados->m82_quant;
    $oDadosMatEstoque->VlrUnit    = $oDados->valorunitario;
    $oDadosMatEstoque->VlrTotal   = ($oDados->m82_quant*$oDados->valorunitario);
                               
   if ( !isset($aDadosMatEstoque[$oDados->m70_coddepto]) ) {
   	 
     $aDadosMatEstoque[$oDados->m70_coddepto]["DescrDpto"]         = $oDadosMatEstoque->DescrDepto;
     $aDadosMatEstoque[$oDados->m70_coddepto]["itens"][]           = $oDadosMatEstoque; 
     $aDadosMatEstoque[$oDados->m70_coddepto]['iQuant']            = $oDadosMatEstoque->Quant; 
     $aDadosMatEstoque[$oDados->m70_coddepto]['nVlrUnit']          = $oDadosMatEstoque->VlrUnit; 
     $aDadosMatEstoque[$oDados->m70_coddepto]['nVlrTotal']         = $oDadosMatEstoque->VlrTotal;
   } else {
   	
     $aDadosMatEstoque[$oDados->m70_coddepto]["DescrDpto"]         = $oDadosMatEstoque->DescrDepto;     
     $aDadosMatEstoque[$oDados->m70_coddepto]["itens"][]           = $oDadosMatEstoque;
     $aDadosMatEstoque[$oDados->m70_coddepto]['iQuant']           += $oDadosMatEstoque->Quant; 
     $aDadosMatEstoque[$oDados->m70_coddepto]['nVlrUnit']         += $oDadosMatEstoque->VlrUnit; 
     $aDadosMatEstoque[$oDados->m70_coddepto]['nVlrTotal']        += $oDadosMatEstoque->VlrTotal;
   }                                      
}

foreach ( $aDadosMatEstoque as $iCodImp => $aDados ) {
	
  if ($pdf->gety() > $pdf->h - 30  || $lImprime  ){
      
  	$lImprime = false;

    $pdf->ln(0);
    $pdf->SetFont($sLetra,'B',6);
    $pdf->Cell(20,5,"Almoxarifado:",0,0,"L",0);
    $pdf->Cell(20,5,$aDados['DescrDpto'],0,1,"L",0);
    
    $pdf->ln(2);
    $pdf->SetFont($sLetra,'B',6);
    $pdf->Cell(20,5,"Data",1,0,"C",1);
    $pdf->Cell(20,5,"OC",1,0,"C",1);
    $pdf->Cell(20,5,"Cod. Item",1,0,"C",1);
    $pdf->Cell(72,5,"Descrição",1,0,"C",1);
    $pdf->Cell(20,5,"Quant.",1,0,"C",1);
    $pdf->Cell(20,5,"Valor Unit.",1,0,"C",1);
    $pdf->Cell(20,5,"Valor Total",1,1,"C",1);
      
  }
  
  foreach ( $aDados['itens'] as $iInd => $oDadosItens ) {
    
  	if ($pdf->gety() > $pdf->h - 30){

  		$pdf->AddPage(); 
  		$pdf->ln(0);
      $pdf->SetFont($sLetra,'B',6);
      $pdf->Cell(20,5,"Almoxarifado:",0,0,"L",0);
      $pdf->Cell(20,5,$aDados['DescrDpto'],0,1,"L",0);
    
      $pdf->ln(2);
      $pdf->SetFont($sLetra,'B',6);
      $pdf->Cell(20,5,"Data",1,0,"C",1);
      $pdf->Cell(20,5,"OC",1,0,"C",1);
      $pdf->Cell(20,5,"Cod. Item",1,0,"C",1);
      $pdf->Cell(72,5,"Descrição",1,0,"C",1);
      $pdf->Cell(20,5,"Quant.",1,0,"C",1);
      $pdf->Cell(20,5,"Valor Unit.",1,0,"C",1);
      $pdf->Cell(20,5,"Valor Total",1,1,"C",1);
       
  	}
  	
    $pdf->SetFont($sLetra,'',5);
    $pdf->Cell(20,4,db_formatar($oDadosItens->Data,'d'),"TRB",0,"C",0);
    $pdf->Cell(20,4,$oDadosItens->CodOC,1,0,"R",0);
    $pdf->Cell(20,4,$oDadosItens->CodMater,1,0,"R",0);
    $pdf->Cell(72,4,$oDadosItens->Descricao,1,0,"L",0);
    $pdf->Cell(20,4,$oDadosItens->Quant,1,0,"R",0);
    $pdf->Cell(20,4,db_formatar($oDadosItens->VlrUnit,'f'),1,0,"R",0);
    $pdf->Cell(20,4,db_formatar($oDadosItens->VlrTotal,'f'),"TLB",1,"R",0);
    
    $nTotalItens++;
  }

  $pdf->SetFont($sLetra,'B',6);
  $pdf->Cell(20,5,"Total de Registros:",1,0,"C",1);
  $pdf->Cell(20,5,$nTotalItens,1,0,"C",1);

  $pdf->Cell(20,5,"","TLB",0,"C",1);
  $pdf->Cell(72,5,"Total:","TRB",0,"R",1);
  $pdf->Cell(20,5,$aDados['iQuant'],1,0,"R",1);
  $pdf->Cell(20,5,db_formatar($aDados['nVlrUnit'],'f'),1,0,"R",1);
  $pdf->Cell(20,5,db_formatar($aDados['nVlrTotal'],'f'),1,1,"R",1);
  
  $pdf->Cell(192,2,"",0,1,"C",0);

  
  $lImprime             = true;
  $nTotalItens          = 0;
  $nTotalGeralQuant    += $aDados['iQuant'];
  $nTotalGeralVlrUnit  += $aDados['nVlrUnit'];
  $nTotalGeralVlrTotal += $aDados['nVlrTotal'];
  $nTotalGeralItens++;
}

$pdf->SetFont($sLetra,'B',6);
$pdf->Cell(192,0,"","T",1,"C",0);
$pdf->Cell(20,5,"Total de Registros:",0,0,"C",0);
$pdf->Cell(20,5,$nTotalGeralItens,0,0,"C",0);

$pdf->Cell(20,5,"",0,0,"C",0);
$pdf->Cell(72,5,"Total Geral:",0,0,"R",0);
$pdf->Cell(20,5,$nTotalGeralQuant,0,0,"R",0);
$pdf->Cell(20,5,db_formatar($nTotalGeralVlrUnit,'f'),0,0,"R",0);
$pdf->Cell(20,5,db_formatar($nTotalGeralVlrTotal,'f'),0,1,"R",0);

$pdf->Output();
?>