<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 
//require_once 'libs/db_sql.php';
//require_once 'libs/db_utils.php';
//require_once 'libs/db_app.utils.php';
require_once 'fpdf151/pdf.php';
//require_once 'std/DBDate.php';
//require_once 'std/DBNumber.php';

/**
 * Configurações para o pdf
 */
$iAlturaLinha      = 5;
$iCorPreenchimento = 235;

$aPontos = Array("salario"      => "Salário",
								 "rescisao"     => "Rescisão",
								 "decimo13"     => "13o Salario",
								 "complementar" => "Complementar");
$sTipoFolha = '';

foreach ($oParametros->aTiposFolha as $iIndice => $sFolha) {
  
  if ($iIndice != 0) {
    $sTipoFolha .= ", ";
  }
  $sTipoFolha .= $aPontos[$sFolha];
}

$oPDF = new pdf();
$oPDF->Open();
$oPDF->AliasNbPages();
$oPDF->SetFillColor($iCorPreenchimento);

$head2 = 'RELATÓRIO DE FAIXAS SALARIAIS';
$head4 = "Quebra: {$sAgrupador}";
$head5 = "Periodo: {$oParametros->iAnoFolha} / {$oParametros->iMesFolha}";
$head6 = "Folha(s): {$sTipoFolha}";

if ( isset($oParametros->iComplementar) && $oParametros->iComplementar != '' ) {
	$head7 = "Complementar: {$oParametros->iComplementar}";
}

$oPDF->AddPage();

foreach( $aDadosRelatorio as $oAgrupador ) {

	$oPDF->SetFont('Arial', 'B', 8);
	$oPDF->Cell(190, $iAlturaLinha, $oAgrupador->iCodigoAgrupador . ' - ' . $oAgrupador->sDescricaoAgrupador, 1, 1, "l", 1);

	/**
	 * Faixas salariais
	 */	 
	for ( $iIndice = 0; $iIndice < $iQuantidadeFaixas; $iIndice++ ) {
	  
		$iNumero       = $iIndice + 1;
		$sDescricao    = $aFaixas[$iIndice]['sDescricaoFaixa'];
		$nValor        = db_formatar($oAgrupador->aFaixas[$iIndice]['nValorFaixa'], 'f');
		$iFuncionarios = $oAgrupador->aFaixas[$iIndice]['iTotalFuncionarios'];
		$nPercentual   = db_formatar($oAgrupador->aFaixas[$iIndice]['nPercentual'], 'f') . "%";

		$oPDF->SetFont('Arial', 'B', 8);
		$oPDF->Cell(120, $iAlturaLinha, "Faixa" ,        1, 0, "C", 1);
		$oPDF->Cell(30,  $iAlturaLinha, "Valor" ,        1, 0, "C", 1);
		$oPDF->Cell(20,  $iAlturaLinha, "Percentual" ,   1, 0, "C", 1);
		$oPDF->Cell(20,  $iAlturaLinha, "Funcionários" , 1, 1, "C", 1);
		
		$oPDF->SetFont('Arial', '', 8);
		$oPDF->Cell(120, $iAlturaLinha, "Faixa {$iNumero}: {$sDescricao}", 1, 0, "L", 0);
		$oPDF->Cell(30,  $iAlturaLinha, $nValor,                           1, 0, "R", 0);
		$oPDF->Cell(20,  $iAlturaLinha, $nPercentual,                      1, 0, "R", 0);
		$oPDF->Cell(20,  $iAlturaLinha, "$iFuncionarios",                  1, 1, "R", 0);
		
		/**
		 * Verificamos se opcao de listar servidores esta como 'Sim'
		 */
		if ($iOpcaoServidor == 2) {
		  
  		/**
  		 * Imprimimos os servidores por faixa
  		 */
  		if ($oAgrupador->aFaixas[$iIndice]['iTotalFuncionarios'] > 0) {
  		  
  		  $oPDF->SetFont('Arial', 'b', 8);
  		  $oPDF->Cell(20,  $iAlturaLinha, "Matrícula",        1, 0, "C", 0);
  		  $oPDF->Cell(150, $iAlturaLinha, "Nome do Servidor", 1, 0, "C", 0);
  		  $oPDF->Cell(20,  $iAlturaLinha, "Valor",            1, 1, "C", 0);
  		  
  		  foreach($oAgrupador->aFaixas[$iIndice]['aServidores'] as $oDadosServidor) {
  		    
  		    $oServidor = new Servidor($oDadosServidor->iMatricula);
  		    $oPDF->SetFont('Arial', '', 8);
  		    $oPDF->Cell(20,  $iAlturaLinha, $oServidor->getMatricula(),                1, 0, "C", 0);
  		    $oPDF->Cell(150, $iAlturaLinha, "{$oServidor->getCgm()->getNome()}",       1, 0, "L", 0);
  		    $oPDF->Cell(20,  $iAlturaLinha, db_formatar($oDadosServidor->nValor, 'f'), 1, 1, "C", 0);
  		    unset($oServidor);
    		}
  		}
		}
	}

	$oPDF->SetFont('Arial', 'B', 8);
	$oPDF->Cell(120, $iAlturaLinha, "Total {$oAgrupador->sDescricaoAgrupador}", 1, 0, "L", 1);
	$oPDF->Cell(30,  $iAlturaLinha, db_formatar($oAgrupador->nTotal, 'f'),      1, 0, "R", 1);
	$oPDF->Cell(20,  $iAlturaLinha, "100%",                                     1, 0, "R", 1);
	$oPDF->Cell(20,  $iAlturaLinha, "$oAgrupador->iTotalFuncionarios",          1, 1, "R", 1);
	
	$oPDF->Ln(4);
}

ob_start();
$oPDF->Output($sArquivo);
ob_clean();