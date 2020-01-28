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

/**
 * @author Andrio Costa  andrio.costa@dbseller.com.br
 * @version $Revision: 1.5 $
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

$oGet       = db_utils::postMemory($_GET);
$aAtividade = explode(",", $oGet->sAtividade);

$oDaoCidadaoFamilia = new cl_cidadaofamilia();
$sSqlListaCidadaos  = $oDaoCidadaoFamilia->sql_query_responsavel_por_resposta_avaliacao($aAtividade);
$rsListaCidadaos    = $oDaoCidadaoFamilia->sql_record($sSqlListaCidadaos);
$iLinhas            = $oDaoCidadaoFamilia->numrows;

if ($iLinhas == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

/**
 * Percorremos os registros encontrados pela query de acordo com os filtros selecionados
 * e criando uma estrutura organizada pela resposta e familia
 */
for ($i = 0; $i < $iLinhas; $i++) {

  $oCidadaoAvaliacao = new CadastroUnico(db_utils::fieldsMemory($rsListaCidadaos, $i)->as02_sequencial);
  $oAvaliacao = $oCidadaoAvaliacao->getAvaliacao();
  $oResposta  = $oAvaliacao->getRespostasDaPerguntaPorIdentificador('FuncaoTrabalhoPrincipal');
    
  if ($oCidadaoAvaliacao->getSexo() == "M") {
    continue;
  }
  
  $oFamilia = $oCidadaoAvaliacao->getFamilia();

  if (empty($oFamilia)) {
  	continue;
  }
  
  $oChefeFamilia                  = new stdClass();
  $oChefeFamilia->iCodigoFamilia  = $oFamilia->getCodigoFamiliarCadastroUnico();
  $oChefeFamilia->iNis            = $oCidadaoAvaliacao->getNis();
  $oChefeFamilia->sResponsavel    = $oCidadaoAvaliacao->getNome();
  $oChefeFamilia->nRendaPerCapita = $oCidadaoAvaliacao->getFamilia()->getRendaPerCapita();
  $oChefeFamilia->sBairro         = $oCidadaoAvaliacao->getBairro();
  
  $aFamilia[ajustaResposta($oResposta[0]->descricaoresposta)][] = $oChefeFamilia;
}
/**
 * Desalocamos a memoria do que ja foi tratado
 */
unset($oCidadaoAvaliacao);
unset($oResposta);
unset($oAvaliacao);

$iHeigth        = 4;
$lPrimeiroLaco  = true;
$oPdf           = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head2 = "Mulheres Chefe de Família";

$iTotalRegistros = 0;
foreach ($aFamilia as $sAtividade => $aFamilia) {

  uasort($aFamilia, "ordernarFamilias");
  foreach ($aFamilia as $oFamilia) {

    if ($lPrimeiroLaco || $oPdf->gety() > $oPdf->h - 15) {

      setHeader($oPdf, $iHeigth, $sAtividade);
      $lPrimeiroLaco = false;
    }
    $nRenda = is_numeric($oFamilia->nRendaPerCapita) ? $oFamilia->nRendaPerCapita : 0;
    $nRenda = db_formatar($nRenda, "f");

    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell(25,  $iHeigth, "{$oFamilia->iNis}",               "TBR",  0);
    $oPdf->Cell(25,  $iHeigth, "{$oFamilia->iCodigoFamilia}",     "TBRL", 0);
    $oPdf->Cell(105, $iHeigth, "{$oFamilia->sResponsavel}",       "TBRL", 0);
    $oPdf->Cell(25,  $iHeigth, $nRenda,                           "TBRL", 0, "R");
    $oPdf->Cell(100, $iHeigth, substr($oFamilia->sBairro, 0, 90), "LTB",  1);
    $iTotalRegistros++;
  }
  $lPrimeiroLaco = true;
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
function setHeader($oPdf, $iHeigth, $sAtividade) {

  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(25,  $iHeigth, "Atividade:",       1, 0, "L", 1);
  $oPdf->Cell(255, $iHeigth, "{$sAtividade}",    1, 1, "L", 1);

  $oPdf->Cell(25,  $iHeigth, "NIS",              1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Cód. Familiar",    1, 0, "C", 1);
  $oPdf->Cell(105, $iHeigth, "Responsável",      1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Renda Per Capita", 1, 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Bairro",           1, 1, "C", 1);
}

function ajustaResposta($sResposta) {

  $sResposta = urldecode($sResposta);
  $iInicio   = strpos($sResposta, "-");
  $iInicio   = $iInicio === false ? 0 : $iInicio + 1;
  $sResposta = trim(substr($sResposta, $iInicio));
  return $sResposta;
}
$oPdf->Output();