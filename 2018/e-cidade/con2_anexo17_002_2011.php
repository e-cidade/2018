<?
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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once('libs/db_libcontabilidade.php');
require_once('libs/db_liborcamento.php');
require_once('classes/db_db_config_classe.php');
require_once("dbforms/db_funcoes.php");

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXVIIBalancoGeral");

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil(112, false);

try {
  
  $oAnexoXVIIBalancoGeral = new AnexoXVIIBalancoGeral($iAnoUsu, 112, $oGet->periodo);
  $oAnexoXVIIBalancoGeral->setInstituicoes($sInstituicoes);
  
  $aDados   = $oAnexoXVIIBalancoGeral->getDados();
  
  $iNumRows = count($aDados);
  if ($iNumRows == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  }
} catch (Exception $eErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$eErro->getMessage()}");
}
/**
 * Adiciona nome abreviado das instituições selecionadas
 */
$sWhere           = "codigo in({$sInstituicoes})";
$sSqlDbConfig     = $cldb_config->sql_query_file(null, "nomeinstabrev", 'codigo', $sWhere);
$rsSqlDbConfig    = $cldb_config->sql_record($sSqlDbConfig); 
$iNumRowsDbConfig = $cldb_config->numrows; 
if ($iNumRowsDbConfig == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Instituição não informada.');
}

$sNomeInstAbrev = "";
$sVirgula       = "";
for ($iInd = 0; $iInd < $iNumRowsDbConfig; $iInd++) {
  
  $oMunicipio      = db_utils::fieldsMemory($rsSqlDbConfig, $iInd);
  $sNomeInstAbrev .= $sVirgula.$oMunicipio->nomeinstabrev;
  $sVirgula        = ", ";
}

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oReltorioContabil->getPeriodos();
foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $oGet->periodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head2  = "DEMONSTRATIVO DA DÍVIDA FLUTUANTE";
$head3  = "EXERCÍCIO {$iAnoUsu}";
$head4  = "ANEXO 17 - PERÍODO: {$sDescricaoPeriodo}";
$head5  = "INSTITUIÇÕES: {$sNomeInstAbrev}";

$oPdf   = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage("L");
$oPdf->SetFillColor(235);

$iTamFonte = 8;
$iAltCell  = 4;

imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, true);

foreach ($aDados as $iIndice => $oDadosRelatorio) {
  
	imprimeLinhaDado($oPdf, $iAltCell, $iTamFonte, $oDadosRelatorio);
  imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
  imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, false, false);
}

$oPdf->Ln();

$oPdf->SetAutoPageBreak(true, 30);
$oPdf->SetFont('arial', '', 5);

/**
 * Adiciona as notas explicativas
 */
$oReltorioContabil->getNotaExplicativa($oPdf, $oGet->periodo);

/**
 * Adiciona as assinaturas
 */
$oReltorioContabil->assinatura($oPdf, 'BG');

$oPdf->Output();

/**
 * Impime cabecalho do relatorio
 *
 * @param Object  type $oPdf
 * @param Integer type $iAltCell
 * @param Boolean type $lImprime
 */
function imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 30 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', $iTamFonte);
    if ( !$lImprime ) {
      
      $oPdf->AddPage("L");
      imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, true);
    }
    
		$oPdf->Cell(105, $iAltCell*2, "TITULOS", "TBR", 0, "C", 0);
		$oPdf->Cell(40, $iAltCell, "SALDO DO EXERCICIO ", "TR", 0, "C", 0);
		$oPdf->Cell(88, $iAltCell, "MOVIMENTAÇÃO NO EXERCICIO", "BTR", 0, "C", 0);
		$oPdf->Cell(45, $iAltCell, "SALDO PARA ", "T", 1, "C", 0);
		     
		$oPdf->SetX(115);
		$oPdf->Cell(40, $iAltCell, "ANTERIOR R$", "BR", 0, "C", 0);
		$oPdf->Cell(44, $iAltCell, "INSCRIÇÃO", "BR", 0, "C", 0);
		$oPdf->Cell(44, $iAltCell, "BAIXA", "BR", 0, "C", 0);
		$oPdf->Cell(45, $iAltCell, "O EXERCICIO SEGUINTE R$", "B", 1, "C", 0);
  }
}

/**
 * Impime informacao da proxima pagina no relatorio
 *
 * @param Object type $oPdf
 * @param Integer type $iAltCell
 * @param Boolean type $lImprime
 * @param Boolean type $lCabecalho default true
 */
function imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, $lImprime, $lCabecalho=true) {
  
  if ( $oPdf->GetY() > $oPdf->h - 32 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', $iTamFonte);
    if ( $lCabecalho ) {
      $oPdf->Cell(278, ($iAltCell*2), 'Continuação '.($oPdf->PageNo())."/{nb}",             'T', 1, "R", 0);
    } else {
      
      $oPdf->Cell(278, ($iAltCell*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}",    'T', 1, "R", 0);
      imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
    }
  }
}

/**
 * Imprime as linhas de dados do relatorio
 *
 * @param object_type $oPdf
 * @param integer_type $iAltCell
 * @param integer_type $iTamFonte
 * @param object_type $oDadosRelatorio
 */
function imprimeLinhaDado($oPdf, $iAltCell, $iTamFonte, $oDadosRelatorio) {
  
  /**
   * Verifica quais são as linhas totalizadoras
   */
  if (!$oDadosRelatorio->totalizar) {
    $oPdf->SetFont('arial', '', $iTamFonte);
  } else {
    $oPdf->SetFont('arial', 'b', $iTamFonte);
  }
  
  $sBordaDir = "R";
  $sBordaEsq = "L";
  if (strtoupper(trim($oDadosRelatorio->descricao)) == 'TOTAL') {
    
    $sBordaDir = "TBR";
    $sBordaEsq = "TBL";
  }
  
  $sDescricao = setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao;
  
  $sSdExAnt = "";
  if (isset($oDadosRelatorio->sd_ex_ant)) {
    $sSdExAnt = db_formatar($oDadosRelatorio->sd_ex_ant, 'f');
  }
  
  $sInscricao = "";
  if (isset($oDadosRelatorio->inscricao)) {
    $sInscricao = db_formatar($oDadosRelatorio->inscricao, 'f');
  }
  
  $sBaixa = "";
  if (isset($oDadosRelatorio->baixa)) {
    $sBaixa = db_formatar($oDadosRelatorio->baixa, 'f');
  }
  
  $sSdExSeg = "";
  if (isset($oDadosRelatorio->sd_ex_seg)) {
    $sSdExSeg = db_formatar($oDadosRelatorio->sd_ex_seg, 'f');
  }
  
  $oPdf->Cell(105, $iAltCell, $sDescricao, $sBordaDir, 0, "L", 0);
  $oPdf->Cell(40, $iAltCell, $sSdExAnt, $sBordaDir, 0, "R", 0);
  $oPdf->Cell(44, $iAltCell, $sInscricao, $sBordaDir, 0, "R", 0);
  $oPdf->Cell(44, $iAltCell, $sBaixa, $sBordaDir, 0, "R", 0);
  $oPdf->Cell(45, $iAltCell, $sSdExSeg, $sBordaEsq, 1, "R", 0);
}

/**
 * Seta identação das linhas
 *
 * @param integer_type $iNivel
 * @return $sEspaco
 */
function setIdentacao($iNivel) {
  
  $sEspaco = "";
  if ($iNivel > 1) {
    $sEspaco = str_repeat("   ", $iNivel);
  }
  
  return $sEspaco;
}
?>