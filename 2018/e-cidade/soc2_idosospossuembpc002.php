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
require_once ("std/DBDate.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("social.FamiliaRepository");
db_app::import("configuracao.avaliacao.*");
db_app::import("Avaliacao");
db_app::import("AvaliacaoGrupo");
db_app::import("AvaliacaoPergunta");
db_app::import("exceptions.*");

$oDados        = db_utils::postMemory($_GET);
$aFamilias     = array();
$aDadosFamilia = array();
$aFiltros      = array();
$aCabecalho    = array();
$iTotalRegistros = 0;

/**
 * Verificamos os filtros selecionados e adicionamos ao array dos filtros, para buscar as respostas das avaliacoes
 */
if ($oDados->sBpcDeficiente == 'true') {

  $aFiltros[]   = "BPCDeficiente";
  $aCabecalho[] = "BPC Deficiente";
  $head1 = "Relatório de Deficientes que Recebem BPC";
}

if ($oDados->sBpcIdoso == 'true') {

  $aFiltros[]   = "BPCIdoso";
  $aCabecalho[] = "BPC Idoso";
  $head1 = "Relatório de Idosos que Recebem BPC";
}

/**
 * Buscamos as respostas de acordo com o filtro selecionado
 */
$oFiltroAvaliacao = new FiltroAvaliacao();
$aAvaliacoes      = $oFiltroAvaliacao->daAvaliacao('AvaliacaoFamiliaCadastroUnico')
                                     ->doGrupo('Sup1VinculoProgramaServico')
                                     ->comPergunta('RecebeAssistenciaProgramaSocial')
                                     ->comRespostas($aFiltros)
                                     ->retornarAvaliacoes();

/**
 * Validamos se retorna alguma avaliacao
 */
if (count($aAvaliacoes) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros.');
}

/**
 * Percorremos as avaliacoes para buscar as famílias correspondentes
 */
foreach ($aAvaliacoes as $oAvaliacao) {

  /**
   * Retornamos um objeto da familia
   */
  $oFamilia = FamiliaRepository::getFamiliaPorAvaliacao($oAvaliacao->getAvaliacaoGrupo());

  if ($oFamilia->getResponsavel() == null) {
    continue;
  }

  /**
   * Atribuimos as informacoes necessarias para o relatorio
   */
  $oDadosFamilia                   = new stdClass();
  $oDadosFamilia->sNomeResponsavel = $oFamilia->getResponsavel()->getNome();
  $oDadosFamilia->sNisResponsavel  = $oFamilia->getResponsavel()->getNis();
  $oDadosFamilia->iCodigoFamiliar  = $oFamilia->getCodigoFamiliarCadastroUnico();
  $oDadosFamilia->sBairroFamilia   = $oFamilia->getResponsavel()->getBairro();
  $oDadosFamilia->nRendaPerCapita  = $oFamilia->getRendaPerCapita();
  $sLetra                          = substr($oFamilia->getResponsavel()->getNome(),  0 , 1);
  $aDadosFamilia[$sLetra][]        = $oDadosFamilia;

  /**
   * Desalocamos a memoria do que ja foi tratado
   */
  FamiliaRepository::removerFamilia($oFamilia);

  $iTotalRegistros++;
  unset($oAvaliacao);
  unset($oFamilia);
}

/**
 * Dados do relatorio
 */

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$lPrimeiroLaco = true;
$oPdf->iAltura = 4;

/**
 * Setamos a largura de cada coluna
 */
$oPdf->iLarguraNomeResponsavel = 80;
$oPdf->iLarguraNisResponsavel  = 20;
$oPdf->iLarguraCodigoFamiliar  = 27;
$oPdf->iLarguraBairroFamilia   = 40;
$oPdf->iLarguraRendaPerCapita  = 25;

/**
 * Percorremos os dados de cada familia para impressao no relatorio
 */
foreach ($aDadosFamilia as $aFamilia) {

  foreach ($aFamilia as $oDadosFamilia) {

    /**
     * Verificamos se chegou ao final da pagina ou se eh o primeiro laco
     */
    if ($oPdf->gety() > $oPdf->h - 20 || $lPrimeiroLaco) {

      cabecalhoPadrao($oPdf, $oPdf->iAltura);
      $lPrimeiroLaco = false;
    }

    /**
     * Formatamos o valor da Renda Per Capita
     */
    $nRendaPerCapita = db_formatar($oDadosFamilia->nRendaPerCapita, 'f');

    $oPdf->SetFont('arial', '', 6);

    $oPdf->Cell($oPdf->iLarguraNomeResponsavel, $oPdf->iAltura, "{$oDadosFamilia->sNomeResponsavel}", "TBR", 0, 'L');
    $oPdf->Cell($oPdf->iLarguraNisResponsavel,  $oPdf->iAltura, "{$oDadosFamilia->sNisResponsavel}",  1,     0, 'C');
    $oPdf->Cell($oPdf->iLarguraCodigoFamiliar,  $oPdf->iAltura, "{$oDadosFamilia->iCodigoFamiliar}",  1,     0, 'C');
    $oPdf->Cell($oPdf->iLarguraBairroFamilia,   $oPdf->iAltura, "{$oDadosFamilia->sBairroFamilia}",   1,     0, 'L');
    $oPdf->Cell($oPdf->iLarguraRendaPerCapita,  $oPdf->iAltura, "{$nRendaPerCapita}",                 "LTB", 1, 'R');
  }
}

/**
 * Imprimimos o total de registros retornados
 */
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(182, $oPdf->iAltura, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(10,  $oPdf->iAltura, $iTotalRegistros,      "LTB",  1, "R");

/**
 * Monta o cabecalho do relatorio
 */
function cabecalhoPadrao($oPdf) {

  $oPdf->setFont('arial', 'b', 8);
  $oPdf->setfillcolor(225);

  $oPdf->AddPage();

  $oPdf->Cell($oPdf->iLarguraNomeResponsavel, $oPdf->iAltura, 'Nome do Responsável Pela Família', "TBR", 0, 'C', 1);
  $oPdf->Cell($oPdf->iLarguraNisResponsavel,  $oPdf->iAltura, 'NIS',                              1,     0, 'C', 1);
  $oPdf->Cell($oPdf->iLarguraCodigoFamiliar,  $oPdf->iAltura, 'Código Domiciliar',                1,     0, 'C', 1);
  $oPdf->Cell($oPdf->iLarguraBairroFamilia,   $oPdf->iAltura, 'Bairro',                           1,     0, 'C', 1);
  $oPdf->Cell($oPdf->iLarguraRendaPerCapita,  $oPdf->iAltura, 'Renda Per Capita',                 "LTB", 1, 'C', 1);
}

$oPdf->Output();
?>