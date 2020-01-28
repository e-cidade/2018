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

$sDataAtualizacao = "Atualizações :";
$sDataVisita      = "Visitas: ";

/**
 * Verifica os filtros
 */
if (!empty($oGet->dtVisitaInicio)) {
  
  $aWhere[]     = " as05_datavisita >= '{$oGet->dtVisitaInicio}'"; 
  $sDataVisita .= " " . db_formatar($oGet->dtVisitaInicio, 'd');
}
if (!empty($oGet->dtVisitaFinal)) {
  
  $aWhere[] = " as05_datavisita <= '{$oGet->dtVisitaFinal}'";
  $sDataVisita .= " até " . db_formatar($oGet->dtVisitaFinal, 'd');
}

if (!empty($oGet->dtAtualizacaoInicio)) {
  
  $aWhere[]          = " as04_dataentrevista >= '{$oGet->dtAtualizacaoInicio}'";
  $sDataAtualizacao .= " " . db_formatar($oGet->dtAtualizacaoInicio, 'd');
}

if (!empty($oGet->dtAtualizacaoFinal)) {
  
  $aWhere[]          = " as04_dataentrevista <= '{$oGet->dtAtualizacaoFinal}'";
  $sDataAtualizacao .= " até " . db_formatar($oGet->dtAtualizacaoFinal, 'd');
}
$aWhere[] = " substr(ov02_nome, 1, 1) BETWEEN '{$oGet->sLetraInicio}' and '{$oGet->sLetraFinal}'";
$aWhere[] = " as02_sequencial is not null ";
$aWhere[] = " as04_sequencial is not null ";

$sOrdem   = " ov02_nome ";

/**
 * Realizamos a busca dos dados 
 */
$oDaoFamilia = db_utils::getDao('cidadaofamiliavisita');
$sCampos     = " distinct as04_sequencial, ov02_nome ";
$sWhere      = implode(" and", $aWhere);
$sSqlFamilia = $oDaoFamilia->sql_query_busca_visitas_cidadao(null, $sCampos, $sOrdem, $sWhere);

$rsFamilia   = $oDaoFamilia->sql_record($sSqlFamilia);

if ($oDaoFamilia->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi encontrados registros.');
}

$aDadosVisita = array();

/**
 * Organizamos os dados indexando-os pelo alfabeto
 */
for ($i = 0; $i < $oDaoFamilia->numrows; $i++) {
  
  $sTelefonePrincipal = "";
  $oFamilia           = new Familia(db_utils::fieldsMemory($rsFamilia, $i)->as04_sequencial);
  $dtUltimaVisita     = FamiliaVisita::getUltimaVisita($oFamilia->getCodigoSequencial());
  $oResponsavel       = $oFamilia->getResponsavel();
  $oTelefone          = $oResponsavel->getTelefonePrincipal();
  
  if ($oTelefone instanceof CidadaoTelefone) {
  	$sTelefonePrincipal = $oTelefone->getDDD() . " - " . $oTelefone->getNumeroTelefone();
  }
  
  $oDados->iCadastroUnico  = $oFamilia->getCodigoFamiliarCadastroUnico();
  $oDados->iNome           = $oResponsavel->getNome();
  $oDados->dtEntrevista    = $oFamilia->getDataEntrevista();
  $oDados->dtVisita        = $dtUltimaVisita;
  $oDados->iNis            = $oResponsavel->getNis();
  $oDados->nRenda          = $oFamilia->getRendaPerCapita();
  $oDados->sTelefone       = $sTelefonePrincipal;
  $sLetra                  = substr($oResponsavel->getNome(), 0, 1);
  $aDadosVisita[$sLetra][] = $oDados;
  unset($oDados);
}

$head1 = "Relatório de Visitas";
$head2 = "Filtros";
$head3 = $sDataVisita;
$head4 = $sDataAtualizacao;
$head5 = "Letras: {$oGet->sLetraInicio} à {$oGet->sLetraFinal}";


$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth              = 4;
$iWidth               = 100;
$lPrimeiroLaco        = true;
$iTotalRegistros      = 0;
$iTotalRegistrosLetra = 0;


/**
 * Realiza impressão dos dados
 */
foreach ($aDadosVisita as $iIndice => $aVisitas) {
	
	/**
	 * Quebra a página se foi solicitado
	 */
	if ($oGet->sQuebraPagina == "true" || $lPrimeiroLaco) {
		 
		$sIndiceImpresso = $iIndice;
		setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
	}
	
  /**
   * Iteramos sobre uma coleção de dados agrupado pela letra inicial do nome 
   */
  foreach ($aVisitas as $oDados) {
    
    if ($oPdf->gety() > $oPdf->h - 20) {
      setHeader($oPdf, $iHeigth);
    }
    
    $oPdf->SetFont("arial", "", 7);
    $oPdf->Cell(25,  $iHeigth, "{$oDados->iCadastroUnico}",       "TB", 0, "C");
    $oPdf->Cell(66,  $iHeigth, substr($oDados->iNome, 0,35),      "1",  0, "L");
    $oPdf->Cell(20,  $iHeigth, $oDados->dtEntrevista,             "1",  0, "C");
    $oPdf->Cell(20,  $iHeigth, $oDados->dtVisita,                 "1",  0, "C");
    $oPdf->Cell(20,  $iHeigth, $oDados->sTelefone,                "1",  0, "L");
    $oPdf->Cell(25,  $iHeigth, $oDados->iNis,                     "1",  0, "C");
    $oPdf->Cell(15,  $iHeigth, db_formatar($oDados->nRenda, "f"), "TB", 1, "R");
    
    $iTotalRegistros ++;
    $iTotalRegistrosLetra++;
  }
  
  /**
   * Imprime totalizador da Letra sómente com a quebra de página habilitada
   */
  if ($oGet->sQuebraPagina == "true") {
  	
  	$oPdf->setfont('arial', 'b', 9);
  	$oPdf->Cell(170, $iHeigth, "Total da letra \"{$iIndice}\":", "TB", 0,"R");
  	$oPdf->Cell(20,  $iHeigth, $iTotalRegistrosLetra,            "TB", 1,"L");
  }
  $iTotalRegistrosLetra = 0;
}

/**
 * Imprime o totalizador geral dos registros
 */
if ($oPdf->gety() > $oPdf->h - 20) {
	$oPdf->AddPage();
}
$oPdf->setfont('arial', 'b', 9);
$oPdf->Cell(170, $iHeigth, "Total de Registros:", "TB", 0,"R");
$oPdf->Cell(20,  $iHeigth, $iTotalRegistros,      "TB", 1,"L");

/**
 * Imprime o cabeçalho 
 * @param object $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->setfillcolor(235);
  
  $oPdf->AddPage();
  
  $oPdf->Cell(25,  $iHeigth, "Domiciliar",  "TBR", 0, "C", 1);
  $oPdf->Cell(66,  $iHeigth, "Nome",        "LTB", 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Atualização", "LTB", 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Visita",      "LTB", 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Telefone",    "LTB", 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "NIS",         "LTB", 0, "C", 1);
  $oPdf->Cell(15,  $iHeigth, "Renda",       "TBL", 1, "C", 1);
}

$oPdf->Output();