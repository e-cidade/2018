<?php
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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_liborcamento.php");
require_once ("libs/db_libcontabilidade.php");
require_once ("classes/db_conplanoorcamento_classe.php");

$clComplanoOrcamento = new cl_conplanoorcamento();

$oGet         = db_utils::postMemory($_GET);
$iAnoUsu      = db_getsession("DB_anousu");
$iInstituicao = db_getsession("DB_instit");
$sWhere       = "     c72_sequencial is null";
$sWhere      .= " and c61_anousu = {$iAnoUsu} ";
$sWhere      .= " and c61_instit = {$iInstituicao} ";
if (!empty($oGet->estrutual)) {
  $sWhere .= " and c60_estrut like '{$oGet->estrutual}%'";
}

$sCampos = "c60_codcon, c61_reduz, c60_estrut, c60_descr";
$sOrder  = "c60_estrut, c61_reduz";

$sSqlNaoVinculados = $clComplanoOrcamento->sql_query_inconsistencia_plano(null, $sCampos, $sOrder, $sWhere);
$rsNaoVinculados   = $clComplanoOrcamento->sql_record($sSqlNaoVinculados);
$iNumRows          = $clComplanoOrcamento->numrows;

if ($iNumRows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado. Exercício: {$iAnoUsu}");
}

$head1 = "";
$head2 = "PLANO ORÇAMENTÁRIO SEM VÍNCULOS COM O PCASP";
$head3 = "Exercício: {$iAnoUsu}";
$head4 = "";
$head5 = "";


$pdf = new PDF("P");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false, 0);

$sFonte          = "arial";
$iAlt            = 4;
$lEscreveHeader  = true;
$iNivel           = "";

if ($iNumRows == 0) {
  
  $pdf->SetFont($sFonte, "b", 9);
  $pdf->cell(20, $iAlt * 2, "Não há Planos sem vínculos", "TBR", 0, "C", 1);
  
} else {
  
  for ($i = 0; $i < $iNumRows; $i++) {
    
    $oPlanoOcamentario = db_utils::fieldsMemory($rsNaoVinculados, $i);
    
    if ($pdf->GetY() > $pdf->h - 30 or $lEscreveHeader) {
      
      $pdf->AddPage("P");
      $pdf->SetFillColor(230);
      $pdf->SetFont($sFonte, "b", 9);
      $iAlturaHeader = $pdf->getY();
      
      $pdf->cell(15,  6, "Código", "TBR", 0, "C", 1);
      $pdf->cell(15,  6, "Reduz", "TBR", 0, "C", 1);
      $pdf->cell(40,  6, "Estrutural", "TBR", 0, "C", 1);
      $pdf->cell(121, 6, "Descrição", "TB", 1, "C", 1);
      
      $lEscreveHeader = false;
      $pdf->SetFillColor(245);
    }
    
    $pdf->SetFont($sFonte, "", 8);
    $pdf->cell(15,  $iAlt, $oPlanoOcamentario->c60_codcon, "TB", 0, 'R', 0);
    $pdf->cell(15,  $iAlt, $oPlanoOcamentario->c61_reduz, "TBL", 0, 'R', 0);
    $pdf->cell(40,  $iAlt, $oPlanoOcamentario->c60_estrut, "TBL", 0, 'R', 0);
    $pdf->cell(121, $iAlt, $oPlanoOcamentario->c60_descr, "TBL", 1, 'L', 0);
    
  }
  
}

$pdf->Output();



?>