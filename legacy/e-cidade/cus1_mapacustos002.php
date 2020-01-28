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

include ("fpdf151/pdf.php");
include ("libs/db_utils.php");
include ("dbforms/db_funcoes.php");
include ("model/custoPlanilha.model.php");
include ("classes/db_custoplanilhaorigem_classe.php");
include ("classes/db_custoplano_classe.php");

$oParametros = db_utils::postMemory($_POST);
if ($oParametros->cc15_anousu ==  "" || $oParametros->cc15_mesusu == ""){
  die("Informe ano e mes.");
}

$oDaoOrigens    = new cl_custoplanilhaorigem();
$oDaoCustoPlano = new cl_custoplano();
$oPlanilhaCusto = new custoPlanilha($oParametros->cc15_mesusu, $oParametros->cc15_anousu);
$aHeadersCusto  = array();
$sSqlOrigens    = $oDaoOrigens->sql_query(null,"*", "cc14_sequencial");  
$rsOrigens      = $oDaoOrigens->sql_record($sSqlOrigens);
$aDadosOrigem   = db_utils::getColectionByRecord($rsOrigens);
foreach ($aDadosOrigem as $oOrigem) {

  if ($oOrigem->cc14_sequencial == 3) {
     $oOrigem->cc14_descricao = "Consumo Almox.";
   }
   $aHeadersCusto[$oOrigem->cc14_sequencial] = $oOrigem->cc14_descricao; 
}
$aCustos = $oPlanilhaCusto->getCustosPlanilha();

/**
 * Montamos o plano de contas
 */
$sSqlCustoPlano = $oDaoCustoPlano->sql_query_analitica(null, 
                                                  "custoplano.*, fc_estrutural_pai(cc01_estrutural) as contapai,
                                                  fc_estrutural_nivel(cc01_estrutural) as nivelconta,
                                                  cc04_sequencial",
                                                  "cc01_estrutural");
$rsCustoPlano   = $oDaoCustoPlano->sql_record($sSqlCustoPlano);
$aPlanoCusto    = array();
for ($iPlano = 0; $iPlano < $oDaoCustoPlano->numrows; $iPlano++) {
  
  $oPlano = db_utils::fieldsMemory($rsCustoPlano, $iPlano);
  $aPlanoCusto[$oPlano->cc01_estrutural] = $oPlano;
   
}

/**
 * Agrupamos os dados da planilha por nivel/Conta na estrutura informada abaixo
 * Conta Custo
 *      |_ Niveis 
 *            |_ Valores do Nivel
 */
$aCustosProcessados = array();
foreach ($aCustos as $oCusto) {

  if (isset($aPlanoCusto[$oCusto->cc01_estrutural]->aOrigens[$oCusto->cc17_custoplanilhaorigem])) {
    
    $aPlanoCusto[$oCusto->cc01_estrutural]->aOrigens[$oCusto->cc17_custoplanilhaorigem]->valor += $oCusto->cc17_valor;
    
  } else {
    
    $oCustoProcessado = new stdClass();
    $oCustoProcessado->valor = $oCusto->cc17_valor;
    $aPlanoCusto[$oCusto->cc01_estrutural]->aOrigens[$oCusto->cc17_custoplanilhaorigem] = $oCustoProcessado;
    
  }
  addValorContaPai($aPlanoCusto[$oCusto->cc01_estrutural], $oCusto->cc17_custoplanilhaorigem, $oCusto->cc17_valor);
}

/**
 * adiciona os valores nas contas pais
 *
 * @param object $oConta contaa 
 * @param integer $iNivel nivel
 * @param float $nValor valor
 */
function addValorContaPai($oConta, $iNivel, $nValor) {
  
  global $aPlanoCusto;
  if (substr($oConta->contapai,0,2) > 0) {
    if (isset($aPlanoCusto[$oConta->contapai]->aOrigens[$iNivel])){
      $aPlanoCusto[$oConta->contapai]->aOrigens[$iNivel]->valor += $nValor;
    } else {
      
      $oContaProcessado = new stdClass();
      $oContaProcessado->valor = $nValor;
      $aPlanoCusto[$oConta->contapai]->aOrigens[$iNivel]->valor = $nValor;
      
    }
    addValorContaPai($aPlanoCusto[$aPlanoCusto[$oConta->contapai]->cc01_estrutural], $iNivel, $nValor);
  }
}

$head1 = "MAPA DE CUSTOS";
$head3 = "Período: {$oParametros->cc15_mesusu}/{$oParametros->cc15_anousu}";
$pdf = new PDF("L");
$pdf->open();
$pdf->aliasNbPages();
$pdf->setFillColor(235);
$pdf->setFont("arial", "b", 7);
$pdf->addPage("L");
$alt = 4;
/**
 * escrevemos o Header do Relatorio
 */
$pdf->Cell(70,$alt, "Conta","TBR",0,'C',1);
$iTotalHeader     = count($aHeadersCusto);
$iTotalNivel      = 1;
$iSizeCelulaNivel = 27;
/*
echo "<pre>";
print_r($aHeadersCusto);
die();
*/
foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
  
  $sBorda = "TBLR";
  $pdf->cell($iSizeCelulaNivel, $alt, $sDescricao, $sBorda, 0, "C", 1);
  $iTotalNivel++;
  
}
$pdf->cell(20, $alt, "Total", "TBL", 1, "C", 1);

$aTotais = array();
/**
 * Percorremos os Custos
 */
foreach ($aPlanoCusto as $oPlano) {
  
  $sStringIndenta = "";
  if ($oPlano->nivelconta > 1) {
    
   $sStringIndenta =  str_repeat(" ",$oPlano->nivelconta*3);
  }
  /**
   * Caso vazio, a conta é sintetica
   */
  if ($oPlano->cc04_sequencial != "") {
    
    $pdf->setFont("arial", "", 7);
  } else {
    $pdf->setFont("arial", "b", 7);  
  }
  
  $pdf->Cell(70,$alt, "{$oPlano->cc01_estrutural} - ".substr($sStringIndenta.$oPlano->cc01_descricao,0,35),"TBR",0,'L',0);
  $nValorConta = 0;
  foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
  
    $sBorda      = "TBLR";
    $nValorNivel = 0;
    if (isset($oPlano->aOrigens[$iIndex])) {
      $nValorNivel = $oPlano->aOrigens[$iIndex]->valor;
    }
    
    $nValorConta += $nValorNivel;
    $pdf->cell($iSizeCelulaNivel, $alt, trim(db_formatar($nValorNivel,"f")), $sBorda, 0, "R", 0);
    $iTotalNivel++;
    if ($oPlano->nivelconta == 1) {
      
      if (isset($aTotais[$iIndex])) {
        $aTotais[$iIndex] += $nValorNivel;
      } else {
        $aTotais[$iIndex] = $nValorNivel;
      }
    }
  
  }
  $pdf->cell(20, $alt, trim(db_formatar($nValorConta, "f")), "TBL", 1, "R");
  imprimirCabecalho($pdf, $alt, false,$aHeadersCusto,$iSizeCelulaNivel,$iTotalNivel);
} 

/**
 * Totalizadores 
 * 
 */
$pdf->setFont("arial", "b", 7);  
$pdf->Cell(70,$alt, "Total","TBR",0,'L',0);
$nValorConta = 0;
foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
   
  $nValorTotal = 0;
  if (isset($aTotais[$iIndex])) {
    $nValorTotal = $aTotais[$iIndex];
  }
  
  $nValorConta += $nValorTotal;
  $pdf->cell($iSizeCelulaNivel, $alt, trim(db_formatar($nValorTotal,"f")), $sBorda, 0, "R", 0);
  $iTotalNivel++;
}
$pdf->cell(20, $alt, trim(db_formatar($nValorConta, "f")), "TBL", 1, "R");
$pdf->Output();


function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime, $aHeadersCusto,$iSizeCelulaNivel,$iTotalNivel) {

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', 'b', 7);

    if ( !$lImprime ) {
       
      $oPdf->AddPage("L");
    }
    
		$oPdf->Cell(70,$iAlturalinha, "Conta","TBR",0,'C',1);
		$iTotalHeader     = count($aHeadersCusto);
		$iTotalNivel      = 1;
		$iSizeCelulaNivel = 27;
		foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
				  
				$sBorda = "TBLR";
				$oPdf->cell($iSizeCelulaNivel, $iAlturalinha, $sDescricao, $sBorda, 0, "C", 1);
				$iTotalNivel++;
				  
		}
		$oPdf->cell(20, $iAlturalinha, "Total", "TBL", 1, "C", 1);		
     
  }
}
?>