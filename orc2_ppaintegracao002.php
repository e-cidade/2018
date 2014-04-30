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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("model/ppaVersao.model.php");

$oGet              = db_utils::postMemory($_GET);
$oPPAVersao        = new ppaVersao($oGet->o05_ppaversao);
$head1             = "INTEGRAÇÕES REALIZADAS ";
$head2             = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
$oDaoPPAintegracao = db_utils::getDao("ppaintegracao");
$sSqlIntegracoes   = $oDaoPPAintegracao->sql_query(null,"*", "o123_sequencial",
                                                  "o123_ppaversao = {$oGet->o05_ppaversao} 
                                                  and o123_tipointegracao = 1");
$rsIntegracoes     = $oDaoPPAintegracao->sql_record($sSqlIntegracoes);
$aIntegracoes      = db_utils::getColectionByRecord($rsIntegracoes);
$pdf = new PDF("P", "mm", "A4"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pdf->AddPage();
$pdf->Cell(20,$alt, "Data","TBR",0,"C",1);
$pdf->Cell(100,$alt, "Usuário","TBL",0,"C",1);
$pdf->Cell(20,$alt, "Ano Destino","TBL",0,"C",1);
$pdf->Cell(15,$alt, "Instituicao","TBL",0,"C",1);
$pdf->Cell(20,$alt, "Situação","TBL",1,"C",1);
$pdf->setfont('arial','',7);
foreach ($aIntegracoes as $oIntegracao) {
  
  $pdf->Cell(20,$alt, db_formatar($oIntegracao->o123_data,"d"),"TBR",0);
  $pdf->Cell(100,$alt, $oIntegracao->nome,"TBR",0);
  $pdf->Cell(20,$alt, $oIntegracao->o123_ano,"TBR",0,"R");
  $pdf->Cell(15,$alt, $oIntegracao->o123_instit,"TBR",0,"R");
  if ($oIntegracao->o123_situacao == 1) {
    $sProcedimento = "Exportado";
  } else {
    $sProcedimento = "Cancelado";
  }
  $pdf->Cell(20,$alt, $sProcedimento,"TBR",1,"L");
  
}
$pdf->Output();
?>