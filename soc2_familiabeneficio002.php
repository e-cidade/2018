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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("Avaliacao");
db_app::import("AvaliacaoPergunta");

$oGet   = db_utils::postMemory($_GET);
$aWhere = array();

/**
 * Verificação dos filtros
 */
if (!empty($oGet->sBeneficio)) {
	$aWhere[] = " upper(trim(as08_tipobeneficio)) = upper(trim('{$oGet->sBeneficio}'))";
}

if (!empty($oGet->iMesCompetencia)) {
	$aWhere[] = " as08_mes = {$oGet->iMesCompetencia}";
}

$sAnoCompetencia = " as08_ano = " . db_getsession("DB_anousu");
if (!empty($oGet->iAnoCompetencia)) {
	$sAnoCompetencia = " as08_ano = {$oGet->iAnoCompetencia}";
} 
$aWhere[] = $sAnoCompetencia;
//$aWhere[] = " as03_tipofamiliar = 0 ";

$sWhere        = implode(" and ", $aWhere);
$oDaoBeneficio = db_utils::getDao('cidadaobeneficio');
$sSqlBeneficio = $oDaoBeneficio->sql_query_beneficio_familia(null,
                                                          "distinct as04_sequencial, 
                                                           as08_sequencial, ov02_nome",
                                                           "ov02_nome", $sWhere);

$rsBeneficio   = $oDaoBeneficio->sql_record($sSqlBeneficio);

if ($oDaoBeneficio->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros.');
}

$aDadosFamilia = array();
for ($i = 0; $i < $oDaoBeneficio->numrows; $i++) {
	
	$oDados             = new stdClass();
	$oFamilia           = new Familia(db_utils::fieldsMemory($rsBeneficio, $i)->as04_sequencial);
	$oResponsavel       = $oFamilia->getResponsavel();
	
	if (empty($oResponsavel)) {
		continue;
	}
	
	$oDados->iFamilia   = $oFamilia->getCodigoFamiliarCadastroUnico();
	$oDados->iNis       = $oResponsavel->getNis();
	$oDados->sNome      = $oResponsavel->getNome();
	$aDadosFamilia[]    = $oDados;
	
}

/**
 * Cabeçalho do relatório
 */
$head2 = "Relatório de Familia por Beneficio";
$head3 = "Benefício: {$oGet->sBeneficio}";
$head4 = "Mês/Ano de Competencia: {$oGet->iMesCompetencia}/{$oGet->iAnoCompetencia}";

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth             = 4;
$iWidth              = 100;
$lPrimeiroLaco       = true;
$iTotalRegistros     = 0;

foreach ($aDadosFamilia as $oFamilia) {
	
	if ($oPdf->gety() > $oPdf->h - 20 || $lPrimeiroLaco) {
	
		setHeader($oPdf, $iHeigth);
		$lPrimeiroLaco = false;
	}
	$oPdf->setfont('arial', '', 8);
	$oPdf->Cell(40,   $iHeigth, "{$oFamilia->iFamilia}", "B", 0, "C", 0);
	$oPdf->Cell(30,   $iHeigth, "{$oFamilia->iNis}",     "LBR", 0, "C", 0);
	$oPdf->Cell(120,  $iHeigth, "{$oFamilia->sNome}",    "B", 1, "L", 0);
	$iTotalRegistros++;
}
if ($oPdf->gety() > $oPdf->h - 20 ) {
	$oPdf->AddPage();
}
$oPdf->setfont('arial', 'b', 9);
$oPdf->Cell(150,   $iHeigth, "Total:", "TB", 0, "R", 0);
$oPdf->Cell(40,   $iHeigth, "{$iTotalRegistros}",     "TB", 0, "L", 0);


/**
 * Imprime o cabeçalho
 * @param object $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {

	$oPdf->setfont('arial', 'b', 9);
	$oPdf->setfillcolor(235);

	$oPdf->AddPage();

	$oPdf->Cell(40,  $iHeigth, "Código da Familia", "TBR", 0, "C", 1);
	$oPdf->Cell(30,  $iHeigth, "NIS",               "LTB", 0, "C", 1);
	$oPdf->Cell(120, $iHeigth, "Nome",              "LTB", 1, "C", 1);
	
}

$oPdf->Output();