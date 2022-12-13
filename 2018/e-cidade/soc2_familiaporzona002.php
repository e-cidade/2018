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

/**
 * @author Andrio Costa  andrio.costa@dbseller.com.br
 * @version $Revision: 1.2 $
 */
require_once("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("std/DBDate.php");

db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");
db_app::import("configuracao.avaliacao.AvaliacaoRepository");
db_app::import("configuracao.avaliacao.FiltroAvaliacao");
db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("exceptions.*");

/**
 * $oGet->iZona pode assumir três valoes
 * 1 - Urbana
 * 2 - Rural
 * 3 - Ambas
 */
$oGet        = db_utils::postMemory($_GET);
$aZonas      = array();
switch ($oGet->iZona) {
  
  case 1: 
    $aZonas[] = "Urbana";
    break;
  case 2:
    $aZonas[] = "Rural";
    break;
  case 3:
    $aZonas[] = "Urbana";
    $aZonas[] = "Rural";
}

$oFiltroAvaliacao = new FiltroAvaliacao();
$aAvaliacoes      = $oFiltroAvaliacao->daAvaliacao('AvaliacaoFamiliaCadastroUnico')
                                     ->doGrupo('CaracteristicasDoDomicilio')
                                     ->comPergunta('ZonaDomiciliar')
                                     ->comRespostas($aZonas)
                                     ->retornarAvaliacoes();


$aFamilia  = array();


/**
 * Percorremos as avaliacoes buscando a resposta informada e os dados da Familia que a respondeu
 * criando uma estrutura organizada pela resposta e familia 
 */
foreach ($aAvaliacoes as $iKey => $oAvaliacao) {
  
  $oResposta         = $oAvaliacao->getRespostasDaPerguntaPorIdentificador('ZonaDomiciliar');
  $oFamiliaAvaliacao = FamiliaRepository::getFamiliaPorAvaliacao($oAvaliacao->getAvaliacaoGrupo());
  
  if ($oFamiliaAvaliacao->getResponsavel() == null) {
    continue;
  }
  
  $oFamilia                  = new stdClass();
  $oFamilia->iCodigoFamilia  = $oFamiliaAvaliacao->getCodigoFamiliarCadastroUnico();
  $oFamilia->iNis            = $oFamiliaAvaliacao->getResponsavel()->getNis();
  $oFamilia->sResponsavel    = $oFamiliaAvaliacao->getResponsavel()->getNome();
  $oFamilia->nRendaPerCapita = $oFamiliaAvaliacao->getRendaPerCapita();
  $sEndereco                 = $oFamiliaAvaliacao->getResponsavel()->getEndereco();
  $sEndereco                .= ", " . $oFamiliaAvaliacao->getResponsavel()->getNumero();
  $oFamilia->sEndereco       = $sEndereco;

  $aFamilia[$oResposta[0]->identificador][] = $oFamilia;
  
  /**
   * Desalocamos a memoria do que ja foi tratado
   */
  FamiliaRepository::removerFamilia($oFamiliaAvaliacao);
  unset($oResposta);
  unset($oAvaliacao);
  unset($oFamilia);
  unset($aAvaliacoes[$iKey]);
  
}



$iHeigth        = 4;
$lPrimeiroLaco  = true;
$oPdf           = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head2 = "Familias por Zonas Urbana";
$head3 = "Zonas: ". implode(", ", $aZonas);

$iTotalRegistros     = 0;
$iTotalRegistrosZona = 0;

foreach ($aFamilia as $sZona => $aFamilia) {
  
  uasort($aFamilia, "ordernarFamilias");
  foreach ($aFamilia as $oFamilia) {
    
    if ($lPrimeiroLaco || $oPdf->gety() > $oPdf->h - 15) {
      
      setHeader($oPdf, $iHeigth, $sZona);
      $lPrimeiroLaco = false;
    }
    $nRenda = is_numeric($oFamilia->nRendaPerCapita) ? $oFamilia->nRendaPerCapita : 0; 
    $nRenda = db_formatar($nRenda, "f"); 
    
    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell(25,  $iHeigth, "{$oFamilia->iNis}",                 "TBR",  0);
    $oPdf->Cell(25,  $iHeigth, "{$oFamilia->iCodigoFamilia}",       "TBRL", 0);
    $oPdf->Cell(105, $iHeigth, "{$oFamilia->sResponsavel}",         "TBRL", 0);
    $oPdf->Cell(100, $iHeigth, substr($oFamilia->sEndereco, 0, 90), "TBRL", 0);
    $oPdf->Cell(25,  $iHeigth, $nRenda,                             "LTB",  1, "R");
    $iTotalRegistros ++;
    $iTotalRegistrosZona ++;
  }
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(240, $iHeigth, "Total da Zona {$sZona}:",     "TBR",  0, "R");
  $oPdf->Cell(40,  $iHeigth, $iTotalRegistrosZona, "LTB",  1);
  $lPrimeiroLaco        = true;
  $iTotalRegistrosZona  = 0;
}
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(240, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(40,  $iHeigth, $iTotalRegistros,      "LTB",  1);

/**
 * Realizamos a ordenação dos dados.
 * 
 */
function ordernarFamilias($aArrayAtual, $aProximoArray){

  return strcasecmp($aArrayAtual->sResponsavel, $aProximoArray->sResponsavel);
}

/**
 * Criamos o cabeçalho do relatorio
 * @param FPDF $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth, $sZona) {

  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(25,  $iHeigth, "Zona:",            1, 0, "L", 1);
  $oPdf->Cell(255, $iHeigth, "{$sZona}",         1, 1, "L", 1);
  
  $oPdf->Cell(25,  $iHeigth, "NIS",              1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Cód. Familiar",    1, 0, "C", 1);
  $oPdf->Cell(105, $iHeigth, "Responsável",      1, 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Endereço",         1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Renda Per Capita", 1, 1, "C", 1);

}
$oPdf->Output();