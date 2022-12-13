<?php
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
require("libs/db_utils.php");
include("classes/db_empempenho_classe.php");



$oDaoEmpempenho = new cl_empempenho;
$sWhere         = "where e60_anousu = ".db_getsession("DB_anousu")." and e60_instit = ".db_getsession("DB_instit");
$oGet           = db_utils::postMemory($_GET);
$head3          = "Todos os Empenhos";
$head4          = "Ano ".db_getsession("DB_anousu"); 
if ($oGet->selfiltros == "N") {
  
  $sWhere .= " and e68_numemp is null ";
  $head2   = "Empenhos não migrados"; 
  
} else {
  
  $sWhere .= " and e68_numemp is not null ";  
  $head2   = "Empenhos migrados"; 
}
if ($oGet->situacao == "S") {
  
  $sWhere .= " and round(e60_vlremp - e60_vlranu - e60_vlrpag,2) > 0";  
  $head3   = "Empenhos com saldo a pagar";
  
}

$head1  = "Notas de Liquidação";
$sSqlEmpenhos  = "select e60_codemp||'/'||e60_anousu as codemp,";
$sSqlEmpenhos .= "       e60_emiss,";
$sSqlEmpenhos .= "       z01_nome,";
$sSqlEmpenhos .= "       e60_vlremp,";
$sSqlEmpenhos .= "       e60_vlrliq,";
$sSqlEmpenhos .= "       e60_vlranu,";
$sSqlEmpenhos .= "       e60_vlrpag";
$sSqlEmpenhos .= "  from empempenho";
$sSqlEmpenhos .= "        left  join empempenhonl on e68_numemp = e60_numemp";
$sSqlEmpenhos .= "        inner join empelemento  on e64_numemp = e60_numemp";
$sSqlEmpenhos .= "        inner join cgm          on z01_numcgm = e60_numcgm";
$sSqlEmpenhos .= " {$sWhere} ";
$sSqlEmpenhos .= "order by e60_numemp";
$rsEmpenhos   = $oDaoEmpempenho->sql_record($sSqlEmpenhos);

    $troca = 0;
    $pdf = new PDF("L"); 
    $pdf->Open(); 
    $pdf->AliasNbPages(); 
    $pdf->SetFillColor(235);
    $alturacabecalho = $pdf->gety();
    $distanciacabecalho = $pdf->getx();
    $iAlturaLinha  = 5;


function cabecalho(&$pdf,&$troca){ 

  if ($pdf->gety() > $pdf->h - 30 || $troca != 1 ){ 

    $troca = 1;
    $pdf->AddPage(); 
    $pdf->SetFillColor(235);
    $alturacabecalho = $pdf->gety();
    $distanciacabecalho = $pdf->getx();
    $iAlturaLinha = 5;
    $pdf->SetFont('Arial', 'B',9);
    $pdf->Cell(123, $iAlturaLinha,   "Dados Cadastrais","TBR", 0, "C", 1);
    $pdf->Cell(80, $iAlturaLinha,    "Movimentações",1, 0,"C", 1);
    $pdf->Cell(75, $iAlturaLinha,    "Saldos a Pagar","TBL", 1,"C", 1);
    $pdf->Cell(24, $iAlturaLinha,    "Empenho", "TBR", 0, "C", 1);
    $pdf->Cell(24, $iAlturaLinha,    "Emissão", 1, 0, "C", 1);
    $pdf->Cell(75, $iAlturaLinha,    "Credor", 1, 0, "C", 1);
    $pdf->Cell(20, $iAlturaLinha,    "Empenhado", 1, 0, "C", 1);
    $pdf->Cell(20, $iAlturaLinha,    "Liquidado", 1, 0, "C", 1);
    $pdf->Cell(20, $iAlturaLinha,    "Pago", 1, 0, "C", 1);
    $pdf->Cell(20, $iAlturaLinha,    "Anulado", 1, 0, "C",1);
    $pdf->Cell(25, $iAlturaLinha,    "Liquidado", 1, 0, "C",1);
    $pdf->Cell(25, $iAlturaLinha,    "A Liquidar", 1, 0, "C",1);
    $pdf->Cell(25, $iAlturaLinha,    "Geral", "TBL", 1, "C",1); 

  }
}

for ($iInd = 0; $iInd < $oDaoEmpempenho->numrows; $iInd++) {
  
  cabecalho($pdf,$troca);
  $pdf->SetFont('Arial', '',8);
  $troca = 1;
  $oEmpenho  = db_utils::fieldsMemory($rsEmpenhos, $iInd);
  $pdf->Cell(24, $iAlturaLinha, $oEmpenho->codemp                    , "TBR",0 , "C",0);
  $pdf->Cell(24, $iAlturaLinha, db_formatar($oEmpenho->e60_emiss,'d'), 1, 0, "C",0);
  $pdf->Cell(75, $iAlturaLinha, $oEmpenho->z01_nome                  , 1, 0, "L",0);
  $pdf->Cell(20, $iAlturaLinha, db_formatar($oEmpenho->e60_vlremp,'f'), 1, 0, "R",0);
  $pdf->Cell(20, $iAlturaLinha, db_formatar($oEmpenho->e60_vlrliq,'f'), 1, 0, "R",0);
  $pdf->Cell(20, $iAlturaLinha, db_formatar($oEmpenho->e60_vlrpag,'f'), 1, 0, "R",0);
  $pdf->Cell(20, $iAlturaLinha, db_formatar($oEmpenho->e60_vlranu,'f'), 1, 0, "R",0);
  $pdf->Cell(25, $iAlturaLinha, db_formatar($oEmpenho->e60_vlrliq - $oEmpenho->e60_vlrpag,'f'), 1, 0, "R",0);
  $pdf->Cell(25, $iAlturaLinha, db_formatar($oEmpenho->e60_vlremp - $oEmpenho->e60_vlranu - $oEmpenho->e60_vlrliq,'f'), 1, 0, "R",0);
  $pdf->Cell(25, $iAlturaLinha, db_formatar($oEmpenho->e60_vlremp - $oEmpenho->e60_vlrpag - $oEmpenho->e60_vlranu,'f'), "TBL", 1, "R",0);  
  
}
$pdf->Cell(50,5,"Total de registros: {$oDaoEmpempenho->numrows}");
$pdf->output();  
?>