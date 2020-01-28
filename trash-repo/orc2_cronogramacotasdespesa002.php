<?
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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_db_config_classe.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("model/cronogramaFinanceiro.model.php");
require_once("model/relatorioContabil.model.php");
$oParams	    = db_utils::postMemory($_POST);
$iCodRel = 78;
$sListaInstit = str_replace('-',',',$oParams->db_selinstit);
$cldb_config                = new cl_db_config; 
$oCronogramaFinanceiro			= new cronogramaFinanceiro($oParams->o124_sequencial);
$oCronogramaFinanceiro->setInstituicoes(explode("-", $oParams->db_selinstit));
$oRelatorioOrcamento        = new relatorioContabil($iCodRel);

try{
	$aDespesas = $oCronogramaFinanceiro->getMetasDespesa($oParams->nivel, $oParams->filtra_despesa);
}catch (Exception $erro){
	db_redireciona('db_erros.php?fechar=true&db_erro='.$erro->getMessage());
}

$aLinhasRelatorio = array();

/**
 * Agrupamos as despesas por mes/Bimestre
 */
foreach ($aDespesas as $oDespesa) {
  
  if ($oParams->nivel == 2) {
    $oDespesa->codigo = "{$oDespesa->o58_orgao}.{$oDespesa->codigo}";
  }
  if ($oParams->iPeriodoImpr == 1) {

     $aLinhasRelatorio[$oDespesa->codigo]->codigo    = $oDespesa->codigo;
     $aLinhasRelatorio[$oDespesa->codigo]->descricao = $oDespesa->descricao;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses    = array();
     
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[0]->valor   = @$oDespesa->aMetas->dados[0]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[1]->valor   = @$oDespesa->aMetas->dados[1]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[2]->valor   = @$oDespesa->aMetas->dados[2]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[3]->valor   = @$oDespesa->aMetas->dados[3]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[4]->valor   = @$oDespesa->aMetas->dados[4]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[5]->valor   = @$oDespesa->aMetas->dados[5]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[6]->valor   = @$oDespesa->aMetas->dados[6]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[7]->valor   = @$oDespesa->aMetas->dados[7]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[8]->valor   = @$oDespesa->aMetas->dados[8]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[9]->valor   = @$oDespesa->aMetas->dados[9]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[10]->valor  = @$oDespesa->aMetas->dados[10]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->aMeses[11]->valor  = @$oDespesa->aMetas->dados[11]->valor;
     $aLinhasRelatorio[$oDespesa->codigo]->total              = @$oDespesa->aMetas->getValorTotal();
     
  } else {
    
    $aLinhasRelatorio[$oDespesa->codigo]->codigo    = $oDespesa->codigo;
    $aLinhasRelatorio[$oDespesa->codigo]->descricao = $oDespesa->descricao;
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses    = array();
     
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses[0]->valor   = $oDespesa->aMetas->dados[0]->valor+$oDespesa->aMetas->dados[1]->valor;
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses[1]->valor   = $oDespesa->aMetas->dados[2]->valor+$oDespesa->aMetas->dados[3]->valor;
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses[2]->valor   = $oDespesa->aMetas->dados[4]->valor+$oDespesa->aMetas->dados[5]->valor;
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses[3]->valor   = $oDespesa->aMetas->dados[6]->valor+$oDespesa->aMetas->dados[7]->valor;
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses[4]->valor   = $oDespesa->aMetas->dados[8]->valor+$oDespesa->aMetas->dados[9]->valor;
    $aLinhasRelatorio[$oDespesa->codigo]->aMeses[5]->valor   = $oDespesa->aMetas->dados[10]->valor+$oDespesa->aMetas->dados[11]->valor;
    $aLinhasRelatorio[$oDespesa->codigo]->total              = $oDespesa->aMetas->getValorTotal(); 
  }
}

/**
 * Montamos o totalizador Geral
 */
if ($oParams->iPeriodoImpr == 1) {
  $iNumColunas = 12;    
} else {
  $iNumColunas = 6;
}
$oTotalizador->total  = 0;
$oTotalizador->aMeses = array();
foreach ($aLinhasRelatorio as $oLinhaRelatorio) {
  
  for ($i = 0; $i < $iNumColunas; $i++  ) {
    if (isset($oTotalizador->aMeses[$i])) {
       $oTotalizador->aMeses[$i]->valor += $oLinhaRelatorio->aMeses[$i]->valor; 
    } else {
       $oTotalizador->aMeses[$i]->valor = $oLinhaRelatorio->aMeses[$i]->valor;
    }
  }
  $oTotalizador->total +=  $oLinhaRelatorio->total;
}
$oRelatorio        = new stdClass();
$oRelatorio->linha = array();
$slabelPeriodo = ""; 
if ($oParams->iPeriodoImpr == 1) {
	
  $slabelPeriodo = "MENSAL"; 
	$tamanho	     = 30;
	$aCabecalho    = array();
	
	$aCabecalho[0]->descricao  = "Janeiro";
	$aCabecalho[0]->tamanho 	 = $tamanho;
	$aCabecalho[1]->descricao  = "Fevereiro";
	$aCabecalho[1]->tamanho 	 = $tamanho;
	$aCabecalho[2]->descricao  = "Mar�o";
	$aCabecalho[2]->tamanho 	 = $tamanho;
	$aCabecalho[3]->descricao  = "Abril";
	$aCabecalho[3]->tamanho 	 = $tamanho;
	$aCabecalho[4]->descricao  = "Maio";
	$aCabecalho[4]->tamanho    = $tamanho;
	$aCabecalho[5]->descricao  = "Junho";
	$aCabecalho[5]->tamanho 	 = $tamanho;
	$aCabecalho[6]->descricao  = "Julho";
	$aCabecalho[6]->tamanho 	 = $tamanho;
	$aCabecalho[7]->descricao  = "Agosto";
	$aCabecalho[7]->tamanho 	 = $tamanho;
	$aCabecalho[8]->descricao = "Setembro";
	$aCabecalho[8]->tamanho   = $tamanho;
	$aCabecalho[9]->descricao = "Outubro";
	$aCabecalho[9]->tamanho 	 = $tamanho;
	$aCabecalho[10]->descricao = "Novembro";
	$aCabecalho[10]->tamanho 	 = $tamanho;
	$aCabecalho[11]->descricao = "Dezembro";
	$aCabecalho[11]->tamanho 	 = $tamanho;
	$oRelatorio->aPeriocidade  = $aCabecalho;
	
} else if ($oParams->iPeriodoImpr == 2) {
  
  $slabelPeriodo = "BIMESTRAL"; 
	$aCabecalho    = array();
	$tamanho       = 30;
	$aCabecalho[0]->descricao = "1� Bimestre";
	$aCabecalho[0]->tamanho 	= $tamanho;
	$aCabecalho[1]->descricao = "2� Bimestre";
	$aCabecalho[1]->tamanho 	= $tamanho;
	$aCabecalho[2]->descricao = "3� Bimestre";
	$aCabecalho[2]->tamanho 	= $tamanho;
	$aCabecalho[3]->descricao = "4� Bimestre";
	$aCabecalho[3]->tamanho 	= $tamanho;
	$aCabecalho[4]->descricao = "5� Bimestre";
	$aCabecalho[4]->tamanho 	= $tamanho;
	$aCabecalho[5]->descricao = "6� Bimestre";
	$aCabecalho[5]->tamanho 	= $tamanho;
	$oRelatorio->aPeriocidade = $aCabecalho;
	
}	
 $aNiveis = array(
                  1 => "Org�o",
                  2 => "Unidade",
                  3 => "Fun��o",
                  4 => "Subfun��o",
                  5 => "Programa",
                  6 => "Projeto/Atividade",
                  7 => "Elemento",
                  8 => "Recurso",
                 );

$head2 = "Cronograma Mensal de Desembolso por {$aNiveis[$oParams->nivel]}";
$head3 = "Art. 8�, da Lei Complementar 101/2000";
$head4 = "Or�amento do exerc�cio de {$oCronogramaFinanceiro->getAno()}";
//$head5 = "Valores expressos por {$aNiveis[$oParams->nivel]}";

$pdf = new PDF('L');
$pdf->Open();
$pdf->SetAutoPageBreak(false, 0);
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$iAlt   = 4;
$sFonte = "arial";
$lEscreveCabecalho = true;
$iCodigo = '';

foreach ($aLinhasRelatorio as $oLinhaRelatorio) {
  
  if ($pdf->GetY() > $pdf->h - 25 ||$lEscreveCabecalho) {
    
    $pdf->AddPage();
    $pdf->SetFont($sFonte,"b",8);
    if ($oParams->iPeriodoImpr == 1) {
      $iAltLabel = 8;
    } else {
      $iAltLabel = 4; 
    }
    
    $pdf->cell(10,$iAltLabel, "Cod","TBR", 0, "C" , 1);
    $pdf->cell(60,$iAltLabel, "Descri��o","TBL", 0, "C" , 1);
    $iAlturaCabecalho  = $pdf->GetY();
    $iMargemTotal      = 0;
    foreach ($oRelatorio->aPeriocidade as $iIndicePeriodo => $oPeriodo) {
      
      if ($oParams->iPeriodoImpr == 1 && $iIndicePeriodo == 6) {
        
        $pdf->ln();
        $pdf->setX(80);
        
      }
      $pdf->cell($oPeriodo->tamanho, $iAlt, $oPeriodo->descricao, 1, 0,"C",1);
    }
    $pdf->SetXY(260, $iAlturaCabecalho);
    $pdf->cell(25, $iAltLabel, "Total", "TBL", 1, "C", 1);
    $lEscreveCabecalho = false;
  }
  $iAlturaLinha  = $pdf->GetY();
  $pdf->SetFont($sFonte, '', 7);
  $mostra = true;
  foreach ($oRelatorio->aPeriocidade as $iIndicePeriodo => $oPeriodo) {
  	
    if ($oLinhaRelatorio->total == 0) {
      
    	$mostra = false;
      continue;	  
    }
    if ($iCodigo != $oLinhaRelatorio->codigo ) {
      
      $pdf->cell(10,$iAltLabel, $oLinhaRelatorio->codigo,"TBR", 0, "R" );
      $pdf->SetFont($sFonte, '', 6);
      $pdf->cell(60,$iAltLabel, substr(urldecode($oLinhaRelatorio->descricao),0,45),"TBL", 0, "L" );
      $iAlturaLinha  = $pdf->GetY();
      
    }  
    if ($oParams->iPeriodoImpr == 1 && $iIndicePeriodo == 6) {
        
      $pdf->ln();
      $pdf->setX(80);
       
    }
    
    $pdf->cell($oPeriodo->tamanho, $iAlt, 
               db_formatar($oLinhaRelatorio->aMeses[$iIndicePeriodo]->valor, "f"), 1, 0,"R");
    $iCodigo = $oLinhaRelatorio->codigo;               
  }
  if ($mostra == true) {
    $pdf->SetXY(260, $iAlturaLinha);
    $pdf->cell(25, $iAltLabel, db_formatar($oLinhaRelatorio->total, "f"), "TBL", 1, "R");
  }
  
}


/**
 * totalizadores 
 */
if ($pdf->GetY() > $pdf->h - 25) {
  
  $pdf->AddPage();
  $pdf->SetFont($sFonte,"b",8);
  $pdf->cell(70,$iAlt, "", "TR", 0, "L" ,1);
  foreach ($oRelatorio->aPeriocidade as $iIndicePeriodo => $oPeriodo) {
      
    if ($oParams->iPeriodoImpr == 1 && $iIndicePeriodo == 6) {

      $pdf->ln();
      $pdf->cell(70, $iAlt, "", "R", 0, "L" ,1);
    }
    $pdf->cell($oPeriodo->tamanho, $iAlt, $oPeriodo->descricao, 1, 0,"C",1);
  }
   $pdf->SetXY(260, $iAlturaCabecalho);
   $pdf->cell(25, $iAltLabel, "Total", "TBL", 1, "C", 1);
    
}
$pdf->SetFont($sFonte, '', 7);
$pdf->cell(70,$iAltLabel, "Totaliza��o Geral", "TBR", 0, "L" ,1);
$iAlturaLinha  = $pdf->GetY();

foreach ($oRelatorio->aPeriocidade as $iIndicePeriodo => $oPeriodo) {
  
  if ($oParams->iPeriodoImpr == 1 && $iIndicePeriodo == 6) {
        
    $pdf->ln();
    $pdf->setX(80);
       
  }
  $pdf->cell($oPeriodo->tamanho, $iAlt, 
             db_formatar($oTotalizador->aMeses[$iIndicePeriodo]->valor, "f"), 1, 0,"R",1);
}

$pdf->SetXY(260, $iAlturaLinha);
$pdf->cell(25, $iAltLabel, db_formatar($oTotalizador->total, "f"), "TBL", 1, "R",1);
$pdf->addPage();
$oRelatorioOrcamento->getNotaExplicativa($pdf,1);
$pdf->Output();  
?>