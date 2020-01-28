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
$aEscolaridades     = explode(",", $oGet->sEscolaridades);
$aAvaliacoes        = array();
$aRespostaAvaliacao = array();
$aFamilias          = array();
$aCidadaos          = array();

/**
 * Buscamos as avalicoes referentes aos filtros selecionados
 */
$oFiltroAvaliacao = new FiltroAvaliacao();
$aAvaliacoes      = $oFiltroAvaliacao->daAvaliacao('AvaliacaoCadastroUnicoCidadao')
                                     ->doGrupo('Escolaridade')
                                     ->comPergunta('CursoQueFrequenta')
                                     ->comRespostas($aEscolaridades)
                                     ->retornarAvaliacoes();

/**
 * Buscamos os cidadaos e familias destes, que possuem as avaliacoes selecionadas
 */
foreach ($aAvaliacoes as $oAvaliacao) {
  
  $oDaoCidadaoAvaliacao    = db_utils::getDao('cidadaoavaliacao');
  $sCamposCidadaoAvaliacao = "ov02_sequencial, ov02_seq, as02_sequencial";
  $sWhereCidadaoAvaliacao  = "db107_sequencial = {$oAvaliacao->getAvaliacaoGrupo()}";
  $sSqlCidadaoAvaliacao    = $oDaoCidadaoAvaliacao->sql_query_cadastrounico(null, 
                                                                            $sCamposCidadaoAvaliacao, 
                                                                            null, 
                                                                            $sWhereCidadaoAvaliacao
                                                                           );
  $rsCidadaoAvaliacao      = $oDaoCidadaoAvaliacao->sql_record($sSqlCidadaoAvaliacao);
  
  if ($oDaoCidadaoAvaliacao->numrows > 0) {
    
    $oDadosCidadaoAvaliacao    = db_utils::fieldsMemory($rsCidadaoAvaliacao, 0);
    $oCidadao                  = new CadastroUnico($oDadosCidadaoAvaliacao->as02_sequencial);
    $oFamiliaCidadao           = $oCidadao->getFamilia();
    
    $oFamilia                  = new stdClass();
    $oFamilia->iSequencial     = $oFamiliaCidadao->getCodigoSequencial();
    $oFamilia->sNIS            = $oFamiliaCidadao->getResponsavel()->getNis();
    $oFamilia->iCodigoFamilia  = $oFamiliaCidadao->getCodigoFamiliarCadastroUnico();
    $oFamilia->sResponsavel    = $oFamiliaCidadao->getResponsavel()->getNome();
    $oFamilia->sEndereco       = $oFamiliaCidadao->getResponsavel()->getEndereco();
    $oFamilia->sEndereco      .= ", ". $oFamiliaCidadao->getResponsavel()->getNumero();
    $oFamilia->sBairro         = $oFamiliaCidadao->getResponsavel()->getBairro();
    $oFamilia->sPessoaCursando = $oCidadao->getNome();
    $aRespostaAvaliacao[$oFamilia->iSequencial] = $oAvaliacao->getRespostasDaPerguntaPorIdentificador('CursoQueFrequenta');
  }
  $aFamilias[] = $oFamilia;
}

$iHeigth       = 4;
$lPrimeiroLaco = true;
$oPdf          = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Pessoas EJA";
$oPdf->setfillcolor(235);
$oPdf->AddPage("L");

$iTotalFamiliaCidadao = count($aFamilias);
$iTotalRegistros      = 0;

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
  
  if ($oPdf->gety() > $oPdf->h - 30) {
    $oPdf->AddPage("L");
  }
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(20,  $iHeigth, "NIS",             1, 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Cód. Familiar",   1, 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Responsável",     1, 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Endereço",        1, 0, "C", 1);
  $oPdf->Cell(40,  $iHeigth, "Bairro",          1, 1, "C", 1);
  
  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(20,  $iHeigth, "{$oFamilia->sNIS}",                     "TBRL", 0);
  $oPdf->Cell(20,  $iHeigth, "{$oFamilia->iCodigoFamilia}",           "TBRL", 0);
  $oPdf->Cell(100, $iHeigth, substr($oFamilia->sResponsavel, 0, 100), "TBRL", 0);
  $oPdf->Cell(100, $iHeigth, substr($oFamilia->sEndereco, 0, 100),    "LTB",  0);
  $oPdf->Cell(40,  $iHeigth, substr($oFamilia->sBairro, 0, 40),       "TBRL", 1);
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(200, $iHeigth, "Pessoa Cursando", 1, 0, "C", 1);
  $oPdf->Cell(80,  $iHeigth, "Curso",           1, 1, "C", 1);
  
  $oPdf->SetFont('arial', '', 6);
  $oPdf->Cell(200,  $iHeigth, "{$oFamilia->sPessoaCursando}", "TBRL", 0);
  foreach ($aRespostaAvaliacao[$oFamilia->iSequencial] as $sResposta) {
    $oPdf->Cell(80, $iHeigth, substr(urldecode($sResposta->descricaoresposta), 4), "TBRL", 1);
  }
  $oPdf->ln();
  $oPdf->SetY($oPdf->GetY());
  $oPdf->SetX($oPdf->GetX());
  
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