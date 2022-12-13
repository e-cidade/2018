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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
require("libs/db_utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('ar24_numcgm');
$clrotulo->label('ar25_matric');
$clrotulo->label('ar26_inscr');
$clrotulo->label('ar23_data');
$clrotulo->label('ar23_hora');
$clrotulo->label('ar23_descricao');
$clrotulo->label('ar23_ocorrencia');

$oGet = db_utils::postMemory($_GET);

$sTipo        = $oGet->t;
$dDataInicio  = $oGet->di;
$dDataFim     = $oGet->df;
$sOrdenar     = $oGet->o;
$sDescricao   = $oGet->desc;
$sOcorrencia  = $oGet->oco;
$sLogradouros = $oGet->logs;
$sBairros     = $oGet->bai;
$sZonaFiscal  = $oGet->zf;
$sZonaEntrega = $oGet->ze;

$sWhere = '';

db_sel_instit(null, "db21_usasisagua");

if($sTipo == 'cgm') {
  
  $sCabecalho = $RLar24_numcgm;
  
  $sOrderBy   = 'ar24_numcgm';
  
  $sInnerJoin = ' inner join histocorrenciacgm on ar24_histocorrencia = ar23_sequencial '; 
  
} else if ($sTipo == 'matric') {
  
  $sCabecalho = $RLar25_matric;
  
  $sOrderBy   = 'ar25_matric';
  
  $sInnerJoin = ' inner join histocorrenciamatric on ar25_histocorrencia = ar23_sequencial';
  
  if (isset($db21_usasisagua) && $db21_usasisagua != '' && $db21_usasisagua == 't') {
		$sInnerJoin .= ' inner join aguabase on x01_matric = ar25_matric ';
  } else {
  	$sInnerJoin .= ' inner join iptubase on j01_matric = ar25_matric ';
  }

  if($sLogradouros != '') {
    $sWhere .= " and x01_codrua in ({$sLogradouros}) ";
  }
  if($sBairros != '') {
    $sWhere .= " and x01_codbairro in ({$sBairros}) ";
  }                  
  if($sZonaFiscal != '') {
    $sWhere .= " and x01_zona in ({$sZonaFiscal}) ";
  }
  if($sZonaEntrega != '') {
    $sWhere .= " and x01_entrega in ({$sZonaEntrega}) ";
  }
  
} else if ($sTipo == "inscr") {
  
  $sCabecalho = $RLar26_inscr;
  
  $sOrderBy   = 'ar26_inscr';
  
  $sInnerJoin = ' inner join histocorrenciainscr on ar26_histocorrencia = ar23_sequencial ';

}

if($dDataInicio != '' and $dDataFim != '') {
  $sWhere .= " and ar23_data between '{$dDataInicio}' and '{$dDataFim}' ";
}

if($sDescricao != '') {
  $sWhere .= " and ar23_descricao ilike '%{$sDescricao}%' ";
}
if($sOcorrencia != '') {
  $sWhere .= " and ar23_ocorrencia ilike '%{$sOcorrencia}%' ";
}

if($sOrdenar != 'codigo') {
  $sOrderBy = $sOrdenar == 'data' ? 'ar23_data' : 'ar23_descricao';
} 

$sSqlOcorrencias = "select * 
                      from histocorrencia 
                           {$sInnerJoin}
                     where 1=1    
                           {$sWhere}
                  order by {$sOrderBy} "; 
                           
$rSqlOcorrencias = db_query($sSqlOcorrencias);

if(pg_num_rows($rSqlOcorrencias) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

if($sTipo == 'matric') {
  $sTipoHeader = 'MATRÍCULA';
} else if ($sTipo == 'cgm') {
  $sTipoHeader = 'CGM'; 
} else {
  $sTipoHeader = 'INSCRIÇÃO';
}

$head2  = 'Relatório de Ocorrências por: ';
$head3  = $sTipoHeader;
$head4  = 'Período: ';
$head5  = $dDataInicio != "" ? "{$dDataInicio} até {$dDataFim}" : "TOTAL";
$head6  = 'Ordenado por: ';
if($sOrdenar == 'codigo') {
  $head7  = $sTipoHeader;  
} else if($sOrdenar == 'data') {
  $head7 = 'DATA';
} else {
  $head7 = 'DESCRIÇÃO';
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$alt   = 6;
$troca = 1;
$total = 0;
$p     = 0;

for($i = 0; $i < pg_num_rows($rSqlOcorrencias); $i++) {
  
  $oOcorrencia = db_utils::fieldsMemory($rSqlOcorrencias, $i, true);
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, $sCabecalho       , 1, 0, "C", 1);
    $pdf->cell(20, $alt, $RLar23_data      , 1, 0, "C", 1);       
    $pdf->cell(100, $alt, $RLar23_descricao , 1, 0, "C", 1);
    $pdf->cell(130, $alt, $RLar23_ocorrencia , 1, 1, "C", 1);
     
    $troca = 0;
    $p     = 0;
  }
  
  if($sTipo == 'cgm') {
    $iCodigo = $oOcorrencia->ar24_numcgm;
  }elseif($sTipo == 'matric') {
    $iCodigo = $oOcorrencia->ar25_matric;
  }else {
    $iCodigo = $oOcorrencia->ar26_inscr;
  }
  
  $pdf->setfont('arial', '', 7);   
  $pdf->cell(30, $alt, $iCodigo                     , 'T', 0, "C", $p);
  $pdf->cell(20, $alt, $oOcorrencia->ar23_data      , 'T', 0, "C", $p);
  
  $iPosX = $pdf->GetX();
  $iPosY = $pdf->GetY();
  $pdf->MultiCell(100, $alt, $oOcorrencia->ar23_descricao, 'T', 'J', $p, 0);
  
  $pdf->SetXY($iPosX + 100,$iPosY);
  $pdf->MultiCell(115, $alt, $oOcorrencia->ar23_ocorrencia,'T', 'J', $p, 0);
  
  $p = $p == 0 ? 1 : 0;
  
  $pdf->cell(280, $alt, '' , 0, 1, '', 0);

  $total++;  
  
}

$pdf->setfont('arial','b',8);
$pdf->cell(280, $alt, 'TOTAL DE REGISTROS : '.$total, "T", 0, "L", 0);
$pdf->Output();