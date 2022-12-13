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
require_once ("std/DBDate.php");

db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");
db_app::import("configuracao.avaliacao.AvaliacaoRepository");
db_app::import("configuracao.avaliacao.FiltroAvaliacao");
db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("exceptions.*");


$oGet          = db_utils::postMemory($_GET);
$aDeficiencias = explode(",", $oGet->sDeficiencias);

$oFiltroAvaliacao = new FiltroAvaliacao();
$aAvaliacoes      = $oFiltroAvaliacao->daAvaliacao('AvaliacaoCadastroUnicoCidadao')
                                     ->doGrupo('PessoasComDeficiencia')
                                     ->comPergunta('PossuiQuaisTiposDeDeficiencia')
                                     ->comRespostas($aDeficiencias)
                                     ->retornarAvaliacoes(); 

if (count($aAvaliacoes) == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma Cidadão com deficiência encontrada.");
}
$aCidadaos = array();
$aFamilia  = array();
foreach ($aAvaliacoes as $oAvaliacao) {
  
  $oDaoCidadaoAvaliacao = db_utils::getDao("cidadaoavaliacao");
  $sWhere               = "as01_avaliacaogruporesposta = {$oAvaliacao->getAvaliacaoGrupo()}";
  $sSqlAvaliacao        = $oDaoCidadaoAvaliacao->sql_query_cadastrounico(null, "as02_sequencial", null, $sWhere);
  $rsAvaliacao          = $oDaoCidadaoAvaliacao->sql_record($sSqlAvaliacao);
  
  if ($oDaoCidadaoAvaliacao->numrows > 0) {
    
    $lImprimirCidadao   = true; 
    $oRespostaCuidado   = null;
    $sCuidadosEspeciais = ''; 
    $aRespostasCuidado  = ($oAvaliacao->getRespostasDaPerguntaPorIdentificador("RecebeCuidadosDeTerceiro"));
    if (isset($aRespostasCuidado[0])) {
      
      $oRespostaCuidado   = $aRespostasCuidado[0];
      $sCuidadosEspeciais = ajustaResposta($oRespostaCuidado->descricaoresposta); 
      /**
       * Validadamos os cuidados especiais
       */
      switch ($oGet->iCuidadoFamiliar) {
        
        case '2':
          if ($oRespostaCuidado->identificador != "NaoRecebeCuidados") {
            $lImprimirCidadao = false;
          }
          break;
        
        case '1':    
        
          if ($oRespostaCuidado->identificador == "NaoRecebeCuidados") {
            $lImprimirCidadao = false;
          }
          break; 
      }
    } 
    
    if (!$lImprimirCidadao) {
      continue;
    }
    $oCidadao                     = new stdClass();
    $oCidadaoAvaliacao            = new CadastroUnico(db_utils::fieldsMemory($rsAvaliacao, 0)->as02_sequencial);
    $oCidadao->nome               = $oCidadaoAvaliacao->getNome();
    $oCidadao->cuidadosespeciais  = $sCuidadosEspeciais;
    $oCidadao->deficiencias       = '';
    
    $aRespostasDeficiencia = ($oAvaliacao->getRespostasDaPerguntaPorIdentificador("PossuiQuaisTiposDeDeficiencia"));
    $oFamiliaDoCidadao     = $oCidadaoAvaliacao->getFamilia();
    $iCodigoFamilia        = $oFamiliaDoCidadao->getCodigoSequencial(); 
    if (!isset($aFamilia[$iCodigoFamilia])) {
       
      $oFamilia                  = new stdClass();
      $oFamilia->rendafamiliar   = db_formatar($oFamiliaDoCidadao->getRendaPerCapita(), 'f');
      $oFamilia->codigoFamiliar  = $oFamiliaDoCidadao->getCodigoFamiliarCadastroUnico();
      $oFamilia->nisresponsavel  = $oFamiliaDoCidadao->getResponsavel()->getNis();
      $oFamilia->nomeresponsavel = $oFamiliaDoCidadao->getResponsavel()->getNome();
      $oFamilia->cadastrosUnicos = array();
      $aFamilia[$iCodigoFamilia] = $oFamilia; 
    }
    $sVirgula = '';
    foreach ($aRespostasDeficiencia as $oResposta) {
      
      $sNomeDeficiencia        = ajustaResposta($oResposta->descricaoresposta);
      $oCidadao->deficiencias .= "{$sVirgula}{$sNomeDeficiencia}";
      $sVirgula = ", "; 
    }
    
    $aFamilia[$iCodigoFamilia]->cadastrosUnicos[] = $oCidadao;
    unset($aRespostasDeficiencia);
    unset($oFamiliaDoCidadao);
    $aCidadaos[] = $oCidadao;
    unset($oAvaliacao);
    unset($oCidadaoAvaliacao);
  }
}

/**
 * Realizamos a ordenação dos dados.
 */
function ordernarFamilias($aArrayAtual, $aProximoArray){
  
  $sField =  'nomeresponsavel';
  if (isset($aArrayAtual->nome)) {
    $sField = 'nome';
  }
  return strcasecmp($aArrayAtual->{$sField}, $aProximoArray->{$sField});
}
uasort($aFamilia, "ordernarFamilias");

$iHeigth        = 4;
$lPrimeiroLaco  = true;
$oPdf           = new PDF("P");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Pessoas com Deficiência";

$iTotalRegistros = 0;
if (count($aFamilia) == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma Cidadão com deficiência encontrada.");
}
/**
 * Percorremos as Familias imprimindo os valores solicidado no relatorio
 */
foreach ($aFamilia as $oFamilia) {
  
  if ($oPdf->gety() > $oPdf->h - 15 || $lPrimeiroLaco ) {
  
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  } 
  
  $oPdf->SetFont('arial', '', 6);
  $oPdf->setfillcolor(245);
  $oPdf->Cell(25, $iHeigth, $oFamilia->nisresponsavel, "TBR", 0, 'L', 1);
  $oPdf->Cell(25, $iHeigth, $oFamilia->codigoFamiliar, "TBRL",0, "L", 1);
  $oPdf->Cell(110, $iHeigth, $oFamilia->nomeresponsavel, "TBRL",0, 'L', 1);
  $oPdf->Cell(30, $iHeigth, $oFamilia->rendafamiliar, "TBL", 1,'R', 1);
  uasort($oFamilia->cadastrosUnicos, "ordernarFamilias");
  foreach ($oFamilia->cadastrosUnicos as $oDeficiente) {
    
    if ($oPdf->gety() > $oPdf->h - 15 || $lPrimeiroLaco) {
    
      setHeader($oPdf, $iHeigth);
      $lPrimeiroLaco = false;
    }
    $oPdf->SetFont('arial', '', 5);
    $oPdf->Cell(50, $iHeigth, $oDeficiente->nome, "TBR", 0, 'L');
    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell(90, $iHeigth, $oDeficiente->deficiencias, "TBRL",0, "L");
    $oPdf->Cell(50, $iHeigth, $oDeficiente->cuidadosespeciais, "TBL",1, 'L');
    $iTotalRegistros ++;
  }
    
}
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(150, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(40,  $iHeigth, $iTotalRegistros,      "LTB",  1);
$oPdf->Output();

/**
 * Criamos o cabeçalho do relatorio
 * @param FPDF $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {
  
  $oPdf->setfillcolor(235);
  $oPdf->AddPage();
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(25, $iHeigth, "NIS",           1, 0, "C", 1);
  $oPdf->Cell(25, $iHeigth, "Cód. Familiar", 1, 0, "C", 1);
  $oPdf->Cell(110, $iHeigth, "Responsável",   1, 0, "C", 1);
  $oPdf->Cell(30, $iHeigth, "Renda Per Capita",   1, 1, "C", 1);
  
  $oPdf->Cell(50, $iHeigth, "Deficiente",           1, 0, "C", 1);
  $oPdf->Cell(90, $iHeigth, "Deficiências", 1, 0, "C", 1);
  $oPdf->Cell(50, $iHeigth, "Cuidados",   1, 1, "C", 1);
}

function ajustaResposta($sResposta) {
  
  $sResposta = urldecode($sResposta);
  $iInicio   = strpos($sResposta, "-");
  $iInicio   = $iInicio === false ? 0 : $iInicio + 1;
  $sResposta = trim(substr($sResposta, $iInicio));
  return $sResposta;
}