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

db_app::import("configuracao.avaliacao.*");
db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");
db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("exceptions.*");

$oGet               = db_utils::postMemory($_GET);
$aCrasSelecionados   = explode(",", $oGet->sCras);
$aAvaliacoes        = array();
$aRespostaAvaliacao = array();
$aFamilias          = array();
$aCidadaos          = array();

$aCras = array("NomeCRAS");

/**
 * Buscamos as avalicoes referentes aos filtros selecionados
 */
$oFiltroAvaliacao = new FiltroAvaliacao();
$aAvaliacoes      = $oFiltroAvaliacao->daAvaliacao('AvaliacaoFamiliaCadastroUnico')
                                     ->doGrupo('Familia')
                                     ->comPergunta('AtendimentoCRAS')
                                     ->comRespostas($aCras, $aCrasSelecionados)
                                     ->retornarAvaliacoes();
/**
 * Buscamos os cidadaos e familias destes, que possuem as avaliacoes selecionadas
 */

foreach ($aAvaliacoes as $oAvaliacao) {

  $aRespostas      = $oAvaliacao->getRespostasDaPerguntaPorIdentificador('AtendimentoCRAS');
  $lTemAtendimento = false;
  foreach ($aRespostas as $oResposta) {

    if (in_array($oResposta->textoresposta, $aCrasSelecionados)) {

      $lTemAtendimento = true;
      break;
    }
  }

  if (!$lTemAtendimento) {
    continue;
  }
  $oDaoFamiliaAvaliacao    = db_utils::getDao('cidadaofamilia');
  $sCamposCidadaoAvaliacao = "as06_sequencial, as06_cidadaofamilia";
  $sWhereCidadaoAvaliacao  = "as06_avaliacaogruporesposta = {$oAvaliacao->getAvaliacaoGrupo()}";
  $sSqlCidadaoAvaliacao    = $oDaoFamiliaAvaliacao->sql_query_familia_avaliacao(null,
                                                                            $sCamposCidadaoAvaliacao,
                                                                            null,
                                                                            $sWhereCidadaoAvaliacao
                                                                           );

  $rsCidadaoAvaliacao = $oDaoFamiliaAvaliacao->sql_record($sSqlCidadaoAvaliacao);

  if ($oDaoFamiliaAvaliacao->numrows > 0) {

    $oDadosFamiliaAvaliacao    = db_utils::fieldsMemory($rsCidadaoAvaliacao, 0);
    $oInformacaoFamilia        = new Familia($oDadosFamiliaAvaliacao->as06_cidadaofamilia);

    $oFamilia                  = new stdClass();
    $oFamilia->iCodigoFamilia  = $oInformacaoFamilia->getCodigoFamiliarCadastroUnico();
    if ($oInformacaoFamilia->getResponsavel() != "") {

      $oFamilia->sResponsavel = $oInformacaoFamilia->getResponsavel()->getNome();
      $oFamilia->sNIS         = $oInformacaoFamilia->getResponsavel()->getNis();
      $oFamilia->iCodigoCras  = $aRespostas[1]->textoresposta;
      $oFamilia->sNomeCras    = $aRespostas[0]->textoresposta;
      $aFamilias[]            = $oFamilia;
    }
    unset($oFamilia);
  }
}

$iHeigth       = 4;
$lPrimeiroLaco = true;
$oPdf          = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$head1 = "CRAS - Centro de Referência de Assistência Social";
$oPdf->setfillcolor(235);

$iTotalFamilias   = count($aFamilias);
$iTotalRegistros  = 0;

/**
 * Ordenamos as familias em ordem alfabetica
 */
uasort($aFamilias, "ordernarFamilias");

/**
 * Percorremos as Familias imprimindo os valores solicitados no relatorio
*/
foreach ($aFamilias as $oFamilia) {

  if ($oFamilia->sResponsavel == null) {
    continue;
  }
  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    $lPrimeiroLaco = false;
    imprimeHeader($oPdf);
  }

  $iCodigoCras = "";
  if (!empty($oFamilia->iCodigoCras)) {
    $iCodigoCras = $oFamilia->iCodigoCras;
  }

  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(20,  $iHeigth, "{$oFamilia->sNIS}",                     "TBR", 0, 'C');
  $oPdf->Cell(20,  $iHeigth, "{$oFamilia->iCodigoFamilia}",               1, 0, 'C');
  $oPdf->Cell(100, $iHeigth, substr($oFamilia->sResponsavel, 0, 100),     1, 0, 'L');
  $oPdf->Cell(20,  $iHeigth, substr($iCodigoCras, 0, 20) ,                 1, 0, 'C');
  $oPdf->Cell(120, $iHeigth, substr($oFamilia->sNomeCras, 0, 100),     "TBL", 1, 'L');

  $iTotalRegistros ++;
}
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(240, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(40,  $iHeigth, $iTotalRegistros,      "LTB",  1);

/**
 * Funcao para ordenacao das familias
*/
function ordernarFamilias($aArrayAtual, $aProximoArray){
  return strcasecmp($aArrayAtual->sResponsavel, $aProximoArray->sResponsavel);
}

$oPdf->Output();

function imprimeHeader($oPdf) {

  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20,  4, "NIS",             1, 0, "C", 1);
  $oPdf->Cell(20,  4, "Cód. Familiar",   1, 0, "C", 1);
  $oPdf->Cell(100, 4, "Responsável",     1, 0, "C", 1);
  $oPdf->Cell(20,  4, "Codigo CRAS",      1, 0, "C", 1);
  $oPdf->Cell(120, 4, "Nome CRAS",        1, 1, "C", 1);

}