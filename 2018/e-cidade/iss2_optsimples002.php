<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/PDFDocument.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_isscadsimples_classe.php"));

$clIssCadSimples = new cl_isscadsimples();

$oGet = db_utils::postMemory($_GET);

$aListaHead    = Array();
$aWhereSimples = Array();

$sDataIncSimplesIni   = implode("-",array_reverse(explode("/",$oGet->dataincini)));
$sDataIncSimplesFin   = implode("-",array_reverse(explode("/",$oGet->dataincfin)));

$sDataBaixaSimplesIni = implode("-",array_reverse(explode("/",$oGet->databaixaini)));
$sDataBaixaSimplesFin = implode("-",array_reverse(explode("/",$oGet->databaixafin)));

if ( trim($sDataIncSimplesIni) != '' ) {
	$aWhereSimples[] = " isscadsimples.q38_dtinicial >= '{$sDataIncSimplesIni}' ";
}

if ( trim($sDataIncSimplesFin) != '' ) {
  $aWhereSimples[] = " isscadsimples.q38_dtinicial <= '{$sDataIncSimplesFin}' ";
}

if ( trim($sDataBaixaSimplesIni) != '' ) {
  $aWhereSimples[] = " isscadsimplesbaixa.q39_dtbaixa >= '{$sDataBaixaSimplesIni}' ";
}

if ( trim($sDataBaixaSimplesFin) != '' ) {
  $aWhereSimples[] = " isscadsimplesbaixa.q39_dtbaixa <= '{$sDataBaixaSimplesFin}' ";
}

if ( trim($oGet->situacao) == '1' ) {
  $aWhereSimples[] = " issbase.q02_dtbaix is null ";
  $aListaHead[]    = " Somente Inscrições Ativas";
} else if ( trim($oGet->situacao) == '2' ) {
	$aWhereSimples[] = " issbase.q02_dtbaix is not null ";
	$aListaHead[]    = " Somente Inscrições Baixadas";
}

if ( trim($oGet->categoria) != '0' ) {
  $aWhereSimples[] = " isscadsimples.q38_categoria = {$oGet->categoria} ";
}

$sWhereSimples = implode(" and ",$aWhereSimples);


if ( $oGet->ordem == '0' ) {
	$sOrdemSimples = 'issbase.q02_inscr';
	$aListaHead[]  = " Ordenado por Inscrição ";
} else if ( $oGet->ordem == '1' ) {
	$sOrdemSimples = 'cgm.z01_nome';
	$aListaHead[]  = " Ordenado por Nome ";
} else {
  $sOrdemSimples = 'ativid.q03_descr';
  $aListaHead[]  = " Ordenado por Atividade";
}

$sCamposSimples   = " issbase.q02_inscr as inscricao,                                         ";
$sCamposSimples  .= " cgm.z01_cgccpf    as cgccpf,                                            ";
$sCamposSimples  .= " cgm.z01_numcgm    as numcgm,                                            ";
$sCamposSimples  .= " cgm.z01_nome      as nome,                                              ";
$sCamposSimples  .= " ativid.q03_ativ||' - '||ativid.q03_descr  as descricao_atividade,       ";
$sCamposSimples  .= " case                                                                    ";
$sCamposSimples  .= "    when isscadsimples.q38_categoria = 1 then 'Micro Empresa'            ";
$sCamposSimples  .= "    when isscadsimples.q38_categoria = 2 then 'Empresa de Pequeno Porte' ";
$sCamposSimples  .= "    else 'MEI'                                                           ";
$sCamposSimples  .= " end               as categoria,                                         ";
$sCamposSimples  .= " q38_dtinicial     as data_inclusao_simples,                             ";
$sCamposSimples  .= " q39_dtbaixa       as data_baixa_simples,                                ";
$sCamposSimples  .= " q02_dtbaix        as data_baixa_inscricao                               ";

$sSqlDadosSimples = $clIssCadSimples->sql_query_dadosinscr(null,
		                                                       $sCamposSimples,
		                                                       $sOrdemSimples,
		                                                       $sWhereSimples);


$rsDadosSimples   = $clIssCadSimples->sql_record($sSqlDadosSimples);
$iLinhasSimples   = $clIssCadSimples->numrows;

if ($iLinhasSimples == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!");
}

switch ($oGet->categoria) {
  case '1':
   $aListaHead[] = " Somente Micro Empresa";
  break;
  case '2':
   $aListaHead[] = " Somente Empresas de Pequeno Porte";
  break;
  case '3':
   $aListaHead[] = " Somente MEI";
  break;
}

if ( trim($oGet->dataincini) != '' || trim($oGet->dataincfin) != '' ) {

	$sHeadPeriodoInscr = " Inscrições Incluídas no Simples";

	if ( trim($oGet->dataincini) == '' || trim($oGet->dataincfin) == '' ) {

		if ( trim($oGet->dataincini) != '' ) {
			$sHeadPeriodoInscr .= " a partir de {$oGet->dataincini}";
		} else {
			$sHeadPeriodoInscr .= " até {$oGet->dataincfin}";
		}

	} else {
		$sHeadPeriodoInscr .= " de {$oGet->dataincini} á {$oGet->dataincfin}";
	}

	$aListaHead[] = $sHeadPeriodoInscr;
}

if ( trim($oGet->databaixaini) != '' || trim($oGet->databaixafin) != '' ) {

  $sHeadPeriodoBaixa = " Inscrições Baixadas do Simples";

  if ( trim($oGet->databaixaini) == '' || trim($oGet->databaixafin) == '' ) {

    if ( trim($oGet->databaixaini) != '' ) {
      $sHeadPeriodoBaixa .= " a partir de {$oGet->databaixaini}";
    } else {
      $sHeadPeriodoBaixa .= " até {$oGet->databaixafin}";
    }

  } else {
    $sHeadPeriodoBaixa .= " de {$oGet->databaixaini} á {$oGet->databaixafin}";
  }

  $aListaHead[] = $sHeadPeriodoBaixa;
}

$oPdf = new PDFDocument();
$oPdf->Open();

$oPdf->addHeaderDescription(" Relatório de Optantes pelo Simples");

foreach($aListaHead as $iInd => $sHead){
  $oPdf->addHeaderDescription($sHead);
}

$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);

$iFonte    = 6;
$iAlt      = 4;
$iPreenche = 1;

imprimirCabecalho($oPdf, $iAlt, $iFonte, true);

for($iInd = 0; $iInd < $iLinhasSimples; $iInd++){

  $oDadosSimples = db_utils::fieldsMemory($rsDadosSimples, $iInd);

  imprimirCabecalho($oPdf, $iAlt, $iFonte);

  if($iPreenche == 1){
    $iPreenche = 0;
  } else {
    $iPreenche = 1;
  }

  $iMaxAlt  = $oPdf->getMultiCellHeight(70, $iAlt, $oDadosSimples->descricao_atividade);
  $iMaxNome = $oPdf->getMultiCellHeight(70, $iAlt, $oDadosSimples->nome);

  if($iMaxNome > $iMaxAlt){
    $iMaxAlt = $iMaxNome;
  }

  $oPdf->setAutoNewLineMulticell(false);

  $oPdf->MultiCell(20, $iAlt, $oDadosSimples->inscricao,                               0, 'C', $iPreenche);
  $oPdf->MultiCell(15, $iAlt, $oDadosSimples->numcgm,                                  0, 'C', $iPreenche);
  $oPdf->MultiCell(70, $iAlt, $oDadosSimples->nome,                                    0, 'L', $iPreenche);
  $oPdf->MultiCell(70, $iAlt, $oDadosSimples->descricao_atividade,                     0, 'L', $iPreenche);
  $oPdf->MultiCell(45, $iAlt, $oDadosSimples->categoria,                               0, 'L', $iPreenche);
  $oPdf->MultiCell(20, $iAlt, db_formatar($oDadosSimples->data_inclusao_simples ,'d'), 0, 'C', $iPreenche);
  $oPdf->MultiCell(20, $iAlt, db_formatar($oDadosSimples->data_baixa_simples    ,'d'), 0, 'C', $iPreenche);
  $oPdf->MultiCell(20, $iAlt, db_formatar($oDadosSimples->data_baixa_inscricao  ,'d'), 0, 'C', $iPreenche);

  $oPdf->ln($iMaxAlt);
}

$oPdf->showPDF();

function imprimirCabecalho($oPdf, $iAlt, $iFonte, $lImprime = false){

  if ($oPdf->gety() > $oPdf->h - 30 || $lImprime ){

    $oPdf->AddPage("L");
    $oPdf->SetFont('Arial','b',$iFonte);

    $oPdf->Cell(20 ,$iAlt,"Inscrição"           ,1,0,'C',1);
    $oPdf->Cell(15 ,$iAlt,"CGM"                 ,1,0,'C',1);
    $oPdf->Cell(70 ,$iAlt,"Nome / Razão Social" ,1,0,'C',1);
    $oPdf->Cell(70 ,$iAlt,"Atividade"           ,1,0,'C',1);
    $oPdf->Cell(45 ,$iAlt,"Categoria"           ,1,0,'C',1);
    $oPdf->Cell(20 ,$iAlt,"Inclusão"            ,1,0,'C',1);
    $oPdf->Cell(20 ,$iAlt,"Baixa"               ,1,0,'C',1);
    $oPdf->Cell(20 ,$iAlt,"Baixa Inscrição"     ,1,1,'C',1);

    $oPdf->SetFont('Arial','',$iFonte);
  }
}