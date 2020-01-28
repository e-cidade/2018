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
db_app::import("contabilidade.relatorios.AnexoXIVBalancoGeral");

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil(3, false);

$oAnexoXIVBalancoGeral = new AnexoXIVBalancoGeral($iAnoUsu, 3, $oGet->periodo);
$oAnexoXIVBalancoGeral->setInstituicoes($sInstituicoes);

$aDados   = $oAnexoXIVBalancoGeral->getDados();
$iNumRows = count($aDados);
if ($iNumRows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
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

$head2  = "BALANÇO PATRIMONIAL - ANEXO 14";
$head3  = "EXERCÍCIO {$iAnoUsu}";
$head6  = "INSTITUIÇÕES: {$sNomeInstAbrev}";

$oPdf   = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetAutoPageBreak(true, 30);
$oPdf->AddPage("P");
$oPdf->SetFillColor(235);

$iTamFonte = 8;
$iAltCell  = 4;
$iPosX     = 0;

/**
 * Cabeçalho da página
 */
$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(95, $iAltCell, 'ATIVO', 0, 0, "C", 0);
$oPdf->Cell(95, $iAltCell, 'PASSIVO', 0, 1, "C", 0);

$oPdf->Ln(5);

$iPosInicialY = $oPdf->GetY();
$iPosInicialX = $oPdf->GetX();

foreach ($aDados as $iIndice => $oDadosRelatorio) {
	
	/**
	 * Verifica quais são as linhas totalizadoras
	 */
  if (!$oDadosRelatorio->totalizar) {
  	
    $oPdf->SetFont('arial', '', $iTamFonte);
    $sPonto = "";
  } else {
  	
    $oPdf->SetFont('arial', 'b', $iTamFonte);
    $sPonto = ".";
  }
	
  /**
   * Verifica coluna dos ativos e passivos
   */
	if ($iIndice == 25) {
		
		$oPdf->SetY($iPosInicialY);
		$iPosX = 95;
	} else if ($iIndice > 25) {
		$iPosX = 95;
	}
	
	/**
	 * Verifica o nivel da linha na página
	 */
  if ($oDadosRelatorio->nivellinha == 1) {
        
    $iTamCell = 70;
    $oPdf->SetX($iPosInicialX+$iPosX);
  } else if ($oDadosRelatorio->nivellinha == 2) {
        
    $iTamCell = 65;
    $oPdf->SetX(15+$iPosX);
  } else if ($oDadosRelatorio->nivellinha == 3) {
        
    $iTamCell = 60;
    $oPdf->SetX(20+$iPosX);
  }

  $oPdf->Cell($iTamCell, $iAltCell, $oDadosRelatorio->descricao, 0, 0, "L", 0, '', $sPonto);
  $oPdf->Cell(25, $iAltCell, db_formatar($oDadosRelatorio->valor, 'f'), 0, 1, "R", 0);
  
  /**
   * Verificação de quebra da página
   */
  if ($iIndice == 23 || $iIndice == 47) {
    $oPdf->Ln(4);
  } else if ($iIndice != 15 || $iIndice != 16 || $iIndice != 31 || $iIndice != 40) {
    $oPdf->Ln(2);
  }
  
  /**
   * Adiciona linhas em branco e linha do saldo patrimonial
   */
  if ($iIndice == 15 || $iIndice == 31) {
    setLinhaEmBranco($oPdf, $iPosInicialX, $iPosX, $iAltCell, 2);
  } else if ($iIndice == 16 || $iIndice == 40) {
  	
    $iTamCell = 70;
    $oPdf->SetX($iPosInicialX+$iPosX);
    $oPdf->Cell($iTamCell, $iAltCell, 'SALDO PATRIMONIAL', 0, 0, "L", 0);
    $oPdf->Cell(25, $iAltCell, '', 0, 1, "R", 0);
    $oPdf->Ln(2);
  }
}

$oPdf->Ln(4);

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
 * Seta as linhas em branco
 *
 * @param object_type $oPdf
 * @param integer_type $iPosInicialX
 * @param integer_type $iPosX
 * @param integer_type $iAltCell
 * @param integer_type $iQnt
 */
function setLinhaEmBranco($oPdf, $iPosInicialX, $iPosX, $iAltCell, $iQnt) {
  
  for ($iInd = 0; $iInd < $iQnt; $iInd++) {
    
    $oPdf->SetX($iPosInicialX+$iPosX);
    $oPdf->Cell(95, $iAltCell, '', 0, 1, "C", 0);
    $oPdf->Ln(2);
  }
}
?>