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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oDaoBens = db_utils::getDao("bens");

$sCampos = " t52_bem, t52_descr, t64_class,  t41_placa, t41_placaseq ";
$sWhere  = " t44_bens is null and t55_codbem is null";
$sWhere .= " and t52_instit = ".db_getsession("DB_instit");
$sOrder  = " t52_bem";

$sBensSemDepreciacao  = $oDaoBens->sql_query_left_depreciacao(null, $sCampos, $sOrder, $sWhere);
$rsBensSemDepreciacao = $oDaoBens->sql_record($sBensSemDepreciacao);

$iContador = $oDaoBens->numrows;
if ($iContador == 0 ) {
  $sMsg = _M('patrimonial.patrimonio.pat2_benssemdepreciacao002.nao_ha_cadastros');
  
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

$oBensSemDepreciacao = db_utils::getCollectionByRecord($rsBensSemDepreciacao);

$head1 = "Relatório de Bens sem Depreciação.";
$oPdf  = new PDF("P");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth             = 4;
$iWidth              = 100;
$lPrimeiroLaco       = true;

foreach ($oBensSemDepreciacao as $iIndiceBens => $oBens) {

  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  }
  $oPdf->SetFont("arial", "", 7);
  $oPdf->Cell(30,  $iHeigth, $oBens->t52_bem, "TB", 0);
  $oPdf->Cell(100, $iHeigth, substr($oBens->t52_descr, 0, 64), "TB", 0);
  $oPdf->Cell(30,  $iHeigth, $oBens->t41_placa . $oBens->t41_placaseq, "TB", 0);
  $oPdf->Cell(30,  $iHeigth, $oBens->t64_class, "TB", 1);
}

function setHeader($oPdf, $iHeigth) {

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->AddPage();
  $oPdf->setfillcolor(235);
  $oPdf->Cell(30,  $iHeigth, "Código do Bem", "TBR", 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Descrição",     1, 0, "C", 1);
  $oPdf->Cell(30,  $iHeigth, "Placa", 			   1, 0, "C", 1);
  $oPdf->Cell(30,  $iHeigth, "Classificação", "TBL", 1, "C", 1);

}


$oPdf->Output();