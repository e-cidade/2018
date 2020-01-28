<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

/**
 *
 * @author Luiz Marcelo Schmitt
 * @revision $Author: dbmatheus.felini $
 * @version $Revision: 1.6 $
 *
 */

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_db_almox_classe.php"));

$oGet = db_utils::postMemory($_GET,0);

$cldb_almox = new cl_db_almox;
$iInstit    = db_getsession("DB_instit");
$sLetra     = 'arial';
$sWhere     = "";
$head5      = "Almoxarifado: Todos";

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

$sSql  = "    select m45_codigo,                                                             ";
$sSql .= "           m40_data,                                                                                        ";
$sSql .= "           m45_data,                                                                                        ";
$sSql .= "           m46_codatendrequiitem,                                                                           ";
$sSql .= "           m60_codmater,                                                                                    ";
$sSql .= "           substring(m60_descr,0,50) as descrmaterial,                                                      ";
$sSql .= "           m89_valorunitario as vlrun,  ";
$sSql .= "           m46_quantdev,                                                                                    ";
$sSql .= "           m70_coddepto,                                                                                    ";
$sSql .= "           descrdepto                                                                                       ";
$sSql .= "      from matestoquedevitem                                                                                ";
$sSql .= "           inner join matestoquedev       on m46_codmatestoquedev      = m45_codigo                         ";
$sSql .= "           inner join matrequiitem        on m46_codmatrequiitem       = m41_codigo                         ";
$sSql .= "           inner join matrequi            on m41_codmatrequi           = m40_codigo                         ";
$sSql .= "           inner join matestoqueinimeimdi on m50_codmatestoquedevitem  = m46_codigo                         ";
$sSql .= "           inner join matestoqueinimei    on m50_codmatestoqueinimei   = m82_codigo                         ";
$sSql .= "           inner join matestoqueinimeipm  on m89_matestoqueinimei      = m82_codigo                         ";
$sSql .= "           inner join matestoqueitem      on m82_matestoqueitem        = m71_codlanc                        ";
$sSql .= "           inner join matestoque          on m71_codmatestoque         = m70_codigo                         ";
$sSql .= "           inner join matmater            on m60_codmater              = m70_codmatmater                    ";
$sSql .= "           inner join db_depart           on m70_coddepto              = coddepto                           ";
$sSql .= "     where instit = {$iInstit}                                                                              ";
$sSql .= " {$sWhere}                                                                                                  ";
$sSql .= "       and m45_data between '{$dtInicial}' and '{$dtFinal}'                                                 ";
$sSql .= "  order by m45_codigo,                                                                                      ";
$sSql .= "           m45_data                                                                                         ";

$rsSql = db_query($sSql);
$iRows = pg_num_rows($rsSql);

if ($iRows == 0) {

  $sMsg = "Nenhum registro encontrado.";
  db_redireciona("db_erros.php?fechar=true&db_erro=$sMsg");
}

$head2 = "RELATÓRIO DE DEVOLUÇÃO DE MATERIAIS AO ALMOXARIFADO";
$head4 = "Periodo: {$oGet->dtInicial} até {$oGet->dtFinal}";

$pdf = new PDF();
$pdf->Open();
$pdf->addpage();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('arial','b',8);

$aDadosMatEstoqueDevItem    = array();
$lImprime                   = true;
$nTotalItens                = 0;
$nTotalGeralQuant           = 0;
$nTotalGeralVlrUnit         = 0;
$nTotalGeralVlrTotal        = 0;
$nTotalGeralItens           = 0;

for ( $iInd = 0; $iInd  < $iRows; $iInd++ ) {

  $oDados = db_utils::fieldsMemory($rsSql,$iInd);

    $oDadosMatEstoqueDevItem = new stdClass();
    $oDadosMatEstoqueDevItem->iCodigo           = $oDados->m45_codigo;
    $oDadosMatEstoqueDevItem->dtData            = $oDados->m45_data;
    $oDadosMatEstoqueDevItem->dtDataReq         = $oDados->m45_data;
    $oDadosMatEstoqueDevItem->iCodAtendReqItem  = $oDados->m46_codatendrequiitem;
    $oDadosMatEstoqueDevItem->iCodMater         = $oDados->m60_codmater;
    $oDadosMatEstoqueDevItem->sDescrMater       = $oDados->descrmaterial;
    $oDadosMatEstoqueDevItem->nVlrUnit          = $oDados->vlrun;
    $oDadosMatEstoqueDevItem->iQuantDev         = $oDados->m46_quantdev;
    $oDadosMatEstoqueDevItem->nTotal            = ( $oDados->m46_quantdev * $oDados->vlrun );
    $oDadosMatEstoqueDevItem->iCodDepto         = $oDados->m70_coddepto;
    $oDadosMatEstoqueDevItem->sDescrDpto        = $oDados->descrdepto;


   if ( !isset($aDadosMatEstoqueDevItem[$oDados->m70_coddepto]) ) {

   	 $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['iCodDpto'] = $oDadosMatEstoqueDevItem->iCodDepto;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['sDescrDpto'] = $oDadosMatEstoqueDevItem->sDescrDpto;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['itens'][]    = $oDadosMatEstoqueDevItem;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['iQuantDev']  = $oDadosMatEstoqueDevItem->iQuantDev;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['nVlrUnit']   = $oDadosMatEstoqueDevItem->nVlrUnit;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['nVlrTotal']  = $oDadosMatEstoqueDevItem->nTotal;
   } else {

   	 $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['iCodDpto'] = $oDadosMatEstoqueDevItem->iCodDepto;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['sDescrDpto'] = $oDadosMatEstoqueDevItem->sDescrDpto;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['itens'][]    = $oDadosMatEstoqueDevItem;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['iQuantDev'] += $oDadosMatEstoqueDevItem->iQuantDev;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['nVlrUnit']  += $oDadosMatEstoqueDevItem->nVlrUnit;
     $aDadosMatEstoqueDevItem[$oDados->m70_coddepto]['nVlrTotal'] += $oDadosMatEstoqueDevItem->nTotal;
   }
}

foreach ( $aDadosMatEstoqueDevItem as $iCodImp => $aDados ) {

  if ($pdf->gety() > $pdf->h - 30  || $lImprime  ){

    $lImprime = false;

    $pdf->ln(0);
    $pdf->SetFont($sLetra,'B',6);
    $pdf->Cell(20,5,"Almoxarifado:"                                             ,0,0,"L",0);
    $pdf->Cell(20,5,$aDados['iCodDpto']." - ".$aDados['sDescrDpto']             ,0,1,"L",0);

    $pdf->ln(2);
    $pdf->SetFont($sLetra,'B',6);
    $pdf->Cell(20,5,"Data"                                                      ,1,0,"C",1);
    $pdf->Cell(20,5,"Cod. Requisição"                                           ,1,0,"C",1);
    $pdf->Cell(20,5,"Data requisição"                                           ,1,0,"C",1);
    $pdf->Cell(20,5,"Cod. Item"                                                 ,1,0,"C",1);
    $pdf->Cell(52,5,"Descrição."                                                ,1,0,"C",1);
    $pdf->Cell(20,5,"Quant."                                                    ,1,0,"C",1);
    $pdf->Cell(20,5,"Valor Unit."                                               ,1,0,"C",1);
    $pdf->Cell(20,5,"Valor Total"                                               ,1,1,"C",1);

  }

  foreach ( $aDados['itens'] as $iInd => $oDadosItens ) {

    if ($pdf->gety() > $pdf->h - 30){

      $pdf->AddPage();
      $pdf->ln(0);
      $pdf->SetFont($sLetra,'B',6);
      $pdf->Cell(20,5,"Almoxarifado:"                                           ,0,0,"L",0);
      $pdf->Cell(20,5,$aDados['iCodDpto']." - ".$aDados['sDescrDpto']           ,0,1,"L",0);

      $pdf->ln(2);
      $pdf->SetFont($sLetra,'B',6);
      $pdf->Cell(20,5,"Data"                                                    ,1,0,"C",1);
      $pdf->Cell(20,5,"Cod. Requisição"                                         ,1,0,"C",1);
      $pdf->Cell(20,5,"Data requisição"                                         ,1,0,"C",1);
      $pdf->Cell(20,5,"Cod. Item"                                               ,1,0,"C",1);
      $pdf->Cell(52,5,"Descrição."                                              ,1,0,"C",1);
      $pdf->Cell(20,5,"Quant."                                                  ,1,0,"C",1);
      $pdf->Cell(20,5,"Valor Unit."                                             ,1,0,"C",1);
      $pdf->Cell(20,5,"Valor Total"                                             ,1,1,"C",1);

    }

    $pdf->SetFont($sLetra,'',5);
    $pdf->Cell(20,4,db_formatar($oDadosItens->dtData,'d')                       ,"TRB",0,"C",0);
    $pdf->Cell(20,4,$oDadosItens->iCodigo                                       ,1,0,"C",0);
    $pdf->Cell(20,4,db_formatar($oDadosItens->dtDataReq,'d')                    ,1,0,"C",0);
    $pdf->Cell(20,4,$oDadosItens->iCodMater                                     ,1,0,"C",0);
    $pdf->Cell(52,4,$oDadosItens->sDescrMater                                   ,1,0,"L",0);
    $pdf->Cell(20,4,$oDadosItens->iQuantDev                                     ,1,0,"R",0);
    $pdf->Cell(20,4,db_formatar($oDadosItens->nVlrUnit,'f')                     ,1,0,"R",0);
    $pdf->Cell(20,4,db_formatar($oDadosItens->nTotal,'f')                       ,"TLB",1,"R",0);

    $nTotalItens++;
  }

  $pdf->SetFont($sLetra,'B',6);
  $pdf->Cell(20,5,"Total de Registros:"                                         ,1,0,"C",1);
  $pdf->Cell(20,5,$nTotalItens                                                  ,1,0,"C",1);

  $pdf->Cell(20,5,""                                                            ,"TLB",0,"C",1);
  $pdf->Cell(72,5,"Total:"                                                      ,"TRB",0,"R",1);
  $pdf->Cell(20,5,$aDados['iQuantDev']                                          ,1,0,"R",1);
  $pdf->Cell(20,5,db_formatar($aDados['nVlrUnit'],'f')                          ,1,0,"R",1);
  $pdf->Cell(20,5,db_formatar($aDados['nVlrTotal'],'f')                         ,1,1,"R",1);

  $pdf->Cell(192,2,"",0,1,"C",0);


  $lImprime             = true;
  $nTotalItens          = 0;
  $nTotalGeralQuant    += $aDados['iQuantDev'];
  $nTotalGeralVlrUnit  += $aDados['nVlrUnit'];
  $nTotalGeralVlrTotal += $aDados['nVlrTotal'];
  $nTotalGeralItens++;
}

$pdf->SetFont($sLetra,'B',6);
$pdf->Cell(192,0,"","T",1,"C",0);
$pdf->Cell(27,5,"Total Geral de Registros:"                                    ,0,0,"L",0);
$pdf->Cell(20,5,$nTotalGeralItens                                              ,0,0,"C",0);

$pdf->Cell(13,5,"",0,0,"C",0);
$pdf->Cell(72,5,"Total Geral:"                                                 ,0,0,"R",0);
$pdf->Cell(20,5,$nTotalGeralQuant                                              ,0,0,"R",0);
$pdf->Cell(20,5,db_formatar($nTotalGeralVlrUnit,'f')                           ,0,0,"R",0);
$pdf->Cell(20,5,db_formatar($nTotalGeralVlrTotal,'f')                          ,0,1,"R",0);

$pdf->Output();
?>