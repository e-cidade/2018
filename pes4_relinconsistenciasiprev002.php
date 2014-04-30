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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
  
$oJson             = new services_json();

$oParam = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_GET ["json"])));
$sArquivos = $oParam;

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca = 1;
$alt = 4;
$head2  = "Relatório de Inconcistências SIPREV";
$pdf->AddPage("L");

imprimirCabecalho($pdf, $alt, true);


 // Verifica se o arquivo foi selecionado e se o array de erros possui registros
 
$aLista = explode(",", $sArquivos);

if ( (count($_SESSION['erro_servidores']) > 0) && (in_array('1', $aLista))) {
	
  $aErrosServidores = $_SESSION ['erro_servidores'];
  foreach ( $aErrosServidores as $aErro ) {

     $pdf->setfont('arial','',6);
     $pdf->cell(20,  $alt, "{$aErro[0]}", "TBR",  0, "L", 0);
     $pdf->cell(30,  $alt, "{$aErro[1]}", "TBL",  0, "L", 0);
     $pdf->cell(30,  $alt, "{$aErro[2]}", "TBL",  0, "C", 0);
     $pdf->cell(80,  $alt, "{$aErro[3]}", "TBLR", 0, "L", 0);
     $pdf->cell(50,  $alt, "{$aErro[4]}", "TBR",  0, "C", 0);
     $pdf->cell(50,  $alt, "{$aErro[5]}", "TBR",  0, "C", 0);
     $pdf->cell(15,  $alt, "{$aErro[6]}", "TB",   1, "C", 0);   	
     imprimirCabecalho($pdf, $alt, false);
  }
	
}	

if ((count($_SESSION['erro_dependentes']) > 0) && (in_array('2', $aLista))) {
 
  $aErrosDependentes = $_SESSION ['erro_dependentes'];
  foreach ( $aErrosDependentes as $aErroDependente ) {	
      
     $pdf->setfont('arial','',6);
     $pdf->cell(20,  $alt, "{$aErroDependente[0]}", "TBR",  0, "L", 0);
     $pdf->cell(30,  $alt, "{$aErroDependente[1]}", "TBL",  0, "L", 0);
     $pdf->cell(30,  $alt, "{$aErroDependente[2]}", "TBL",  0, "C", 0);
     $pdf->cell(80,  $alt, "{$aErroDependente[3]}", "TBLR", 0, "L", 0);
     $pdf->cell(50,  $alt, "{$aErroDependente[4]}", "TBR",  0, "C", 0);
     $pdf->cell(50,  $alt, "{$aErroDependente[5]}", "TBR",  0, "C", 0);
     $pdf->cell(15,  $alt, "{$aErroDependente[6]}", "TB",   1, "C", 0);
     imprimirCabecalho($pdf, $alt, false);    	
  }
       
}

$pdf->output();

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    
    if ( !$lImprime ) {
    	
      $oPdf->AddPage("L");
    }

    $oPdf->setfont('arial','b',8);
    $oPdf->cell(20,  $iAlturalinha, "ARQUIVO",  "TBR",  0, "C", 0);
    $oPdf->cell(30,  $iAlturalinha, "ERRO",     "TBL",  0, "C", 0);
    $oPdf->cell(30,  $iAlturalinha, "NUM. CGM", "TBL",  0, "C", 0);
    $oPdf->cell(80,  $iAlturalinha, "NOME",     "TBLR", 0, "C", 0);
    $oPdf->cell(50,  $iAlturalinha, "CPF",      "TBR",  0, "C", 0);
    $oPdf->cell(50,  $iAlturalinha, "PIS",      "TBR",  0, "C", 0);
    $oPdf->cell(15,  $iAlturalinha, "SEXO",     "TB",   1, "C", 0);
   
  }
}

?>