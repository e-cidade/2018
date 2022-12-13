<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/pdf.php"));

define('LARGURA_PAGINA', 192);
define('ALTURA_LINHA', 4);
define('TAMANHO_FONTE_CABECALHO', 6);
define('TAMANHO_FONTE', 6);
define('MARGEM_NOVA_PAGINA', 30);

try {

  $oGet                  = db_utils::postMemory($_GET);
  $iInstituicao          = db_getsession('DB_instit');
  $aCodigosAlmoxarifados = array();
  $aCodigosMateriais     = array();
  $oPeriodoInicial       = null;
  $lMaterialImpresso     = false;
  $iAlmoxarifadoAnterior = null;

  if (!empty($oGet->periodoInicial)) {
    $oPeriodoInicial = new DBDate($oGet->periodoInicial);
  }

  /**
   * Periodo final, caso nao informado pega data atual
   */
  if (!empty($oGet->periodoFinal)) {
    $oPeriodoFinal = new DBDate($oGet->periodoFinal);
  } else {
    $oPeriodoFinal = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));
  }

  $lQuebraPorAlmoxarifado   = $oGet->quebraPorAlmoxarifado    == 1;
  $somenteItensComMovimento = $oGet->somenteItensComMovimento == 1;
  $sOrdem                   = $oGet->ordem;
  $sTipoImpressao           = $oGet->tipoImpressao;

  if (!empty($oGet->sAlmoxarifados)) {
    $aCodigosAlmoxarifados = explode(',', $oGet->sAlmoxarifados);
  }

  if (!empty($oGet->sMateriais)) {
    $aCodigosMateriais = explode(',', $oGet->sMateriais);
  }

  /**
   * Pesquisa almoxarifado
   * - nenhum almoxarifado inforamdo
   *   - caso for informado algum material, pesquisa almoxarifado destes itens
   *   - caso não for inforamdo nenhum material, pesquisa todos os almoxarifados para instituicao atual
   */
  if (empty($aCodigosAlmoxarifados)) {

    /**
     * Nenhum material informado, pesquisa todas os almoxarifados da instituicao atual
     */
    if (empty($aCodigosMateriais)) {

      $oDaoMatestoque      = new cl_matestoque();
      $sSqlAlmoxarifados   = $oDaoMatestoque->sql_query_almoxarifado('distinct m70_coddepto', null, "db_depart.instit = $iInstituicao");
      $rsAlmoxarifados     = $oDaoMatestoque->sql_record($sSqlAlmoxarifados);

      if ($oDaoMatestoque->erro_status == '0') {
        throw new Exception(_M('patrimonial.material.mat2_controleestoque002.nenhum_almoxarifado_encontrado'));
      }

      foreach (db_utils::getCollectionByRecord($rsAlmoxarifados) as $oDadosAlmoxarifado) {
        $aCodigosAlmoxarifados[] = $oDadosAlmoxarifado->m70_coddepto;
      }
    }

    /**
     * Material informado, pesquisa os almoxarifados destes
     */
    if (!empty($aCodigosMateriais)) {

      $oDaoMatestoque      = new cl_matestoque();
      $sWhereAlmoxarifados = 'm70_codmatmater in('. implode(',', $aCodigosMateriais) .')';
      $sSqlAlmoxarifados   = $oDaoMatestoque->sql_query_almoxarifado('distinct m70_coddepto', null, $sWhereAlmoxarifados);
      $rsAlmoxarifados     = $oDaoMatestoque->sql_record($sSqlAlmoxarifados);

      if ($oDaoMatestoque->erro_status == '0') {
        throw new Exception(_M('patrimonial.material.mat2_controleestoque002.nenhum_almoxarifado_encontrado_pelo_item'));
      }

      foreach (db_utils::getCollectionByRecord($rsAlmoxarifados) as $oDadosAlmoxarifado) {
        $aCodigosAlmoxarifados[] = $oDadosAlmoxarifado->m70_coddepto;
      }
    }

  }

  /**
   * Nenhum material informado
   * - busca todos os materias de cada almoxarifado
   */
  if (empty($aCodigosMateriais)) {

    $oDaoMatestoque = new cl_matestoque();
    $sWhereItens    = 'm70_coddepto in('. implode(',', $aCodigosAlmoxarifados) .')';
    $sSqlItens      = $oDaoMatestoque->sql_query_almoxarifado('m70_codmatmater', null, $sWhereItens);
    $rsItens        = $oDaoMatestoque->sql_record($sSqlItens);

    if ($oDaoMatestoque->erro_status == '0') {
      throw new Exception(_M('patrimonial.material.mat2_controleestoque002.nenhum_item_encontrado'));
    }

    foreach (db_utils::getCollectionByRecord($rsItens) as $oDadosItem) {
      $aCodigosMateriais[] = $oDadosItem->m70_codmatmater;
    }
  }

  $oControleEstoque = new ControleEstoque();
  $oControleEstoque->setPeriodo($oPeriodoInicial, $oPeriodoFinal);

  foreach ($aCodigosAlmoxarifados as $iAlmoxarifado) {
    $oControleEstoque->adicionarAlmoxarifado(new Almoxarifado($iAlmoxarifado));
  }

  foreach ($aCodigosMateriais as $iMaterial) {
    $oControleEstoque->adicionarItem(new Item($iMaterial));
  }

  $aMovimentacoes = $oControleEstoque->getMovimentacaoEstoqueSintetica();

  $aDadosMovimentacoes    = array();
  $aOrdenacaoCodigo       = array();
  $aOrdenacaoNome         = array();
  $aOrdenacaoAlmoxarifado = array();

  foreach ($aMovimentacoes as $oMovimentacao) {

    $aDadosMovimentacoes[]    = $oMovimentacao;
    $aOrdenacaoCodigo[]       = $oMovimentacao->getItem()->getCodigo();
    $aOrdenacaoNome[]         = $oMovimentacao->getItem()->getNome();
    $aOrdenacaoAlmoxarifado[] = $oMovimentacao->getAlmoxarifado()->getCodigo();
  }

  /**
   * Ordena de acordo com filtro
   */
  switch($sOrdem) {

    case 'alfabetica' :
      array_multisort($aOrdenacaoNome, SORT_STRING, $aOrdenacaoCodigo, SORT_ASC, $aDadosMovimentacoes);
    break;

    case 'codigo' :
      array_multisort($aOrdenacaoCodigo, SORT_ASC, $aOrdenacaoNome, SORT_STRING, $aDadosMovimentacoes);
    break;

    default :
    case 'almoxarifado' :
      array_multisort($aOrdenacaoAlmoxarifado, SORT_ASC, $aOrdenacaoNome, SORT_ASC, $aDadosMovimentacoes);
    break;
  }

  /**
   * Nao quebra por almoxarifado
   * - soma itens
   * - quanto tipo de impressao for sintetico
   */
  if (!$lQuebraPorAlmoxarifado && $tipoImpressao == 'sintetico') {

    /**
     * Agrupa movimentacoes por material para somalos
     */
    $aMovimentacoesPorMaterial = array();

    /**
     * Agrupa movimentacoes pelo codigo do material
     */
    foreach ($aDadosMovimentacoes as $oMovimentacao) {
      $aMovimentacoesPorMaterial[$oMovimentacao->getItem()->getCodigo()][] = $oMovimentacao;
    }

    /**
     * Percorre as movimentacoes de cada material e soma adicionanando no array de movimentacoes, $aDadosMovimentacoes
     */
    foreach ($aMovimentacoesPorMaterial as $iMaterial => $aDadosMovimentacoesPorMaterial) {

      $nValorAnterior      = 0;
      $nQuantidadeAnterior = 0;
      $nQuantidadeEntrada  = 0;
      $nQuantidadeSaida    = 0;
      $nValorEntrada       = 0;
      $nValorSaida         = 0;

      foreach ($aDadosMovimentacoesPorMaterial as $oMovimentacaoPorMaterial) {

        $nValorAnterior      += $oMovimentacaoPorMaterial->getValorAnterior();
        $nQuantidadeAnterior += $oMovimentacaoPorMaterial->getQuantidadeAnterior();
        $nQuantidadeEntrada  += $oMovimentacaoPorMaterial->getQuantidadeEntrada();
        $nQuantidadeSaida    += $oMovimentacaoPorMaterial->getQuantidadeSaida();
        $nValorEntrada       += $oMovimentacaoPorMaterial->getValorEntrada();
        $nValorSaida         += $oMovimentacaoPorMaterial->getValorSaida();
      }

      $oMovimentacaoPorMaterial->setValorAnterior($nValorAnterior);
      $oMovimentacaoPorMaterial->setValorSaida($nValorSaida);
      $oMovimentacaoPorMaterial->setValorEntrada($nValorEntrada);
      $oMovimentacaoPorMaterial->setQuantidadeAnterior($nQuantidadeAnterior);
      $oMovimentacaoPorMaterial->setQuantidadeEntrada($nQuantidadeEntrada);
      $oMovimentacaoPorMaterial->setQuantidadeSaida($nQuantidadeSaida);

      $aDadosMovimentacoes[] = $oMovimentacaoPorMaterial;
    }
  }

  if (empty($aDadosMovimentacoes)) {
    throw new Exception(_M('patrimonial.material.mat2_controleestoque002.nenhuma_movimentacao_encontrada'));
  }

  $aDescricaoOrdem         = array();
  $aDescricaoTipoImpressao = array();

  $aDescricaoOrdem['alfabetica']   = 'Alfabética';
  $aDescricaoOrdem['codigo']       = 'Código';
  $aDescricaoOrdem['almoxarifado'] = 'Almoxarifado';

  $aDescricaoTipoImpressao['sintetico']   = 'Sintético';
  $aDescricaoTipoImpressao['conferencia'] = 'Conferência';

  $head2 = "Controle de estoque";

  if (!empty($oGet->periodoInicial)) {
    $head4 = "Período: " .$oPeriodoInicial->getDate(DBDate::DATA_PTBR) . ' a ' . $oPeriodoFinal->getDate(DBDate::DATA_PTBR);
  } else {
    $head4 = "Período final: " . $oPeriodoFinal->getDate(DBDate::DATA_PTBR);
  }

  $head5 = 'Quebra por almoxarifado: ' .  ($lQuebraPorAlmoxarifado ? 'Sim' : 'Não');
  $head6 = 'Somente itens com movimento: ' . ($somenteItensComMovimento ? 'Sim' : 'Não');
  $head7 = 'Ordem: ' . $aDescricaoOrdem[$sOrdem];
  $head8 = 'Tipo de impressão: ' . $aDescricaoTipoImpressao[$sTipoImpressao];

  $oPDF = new PDF('P', 'mm', 'A4');
  $oPDF->Open();
  $oPDF->AliasNbPages();
  $oPDF->setFillColor(235);
  $oPDF->setfont('arial', '', 8);
  $oPDF->addPage();

  $oDados                         = new stdClass();
  $oDados->sPeriodoInicial        = !empty($oPeriodoInicial) ? $oPeriodoInicial->getDate(DBDate::DATA_PTBR) : null;
  $oDados->sPeriodoFinal          = $oPeriodoFinal->getDate(DBDate::DATA_PTBR);
  $oDados->lQuebraPorAlmoxarifado = $lQuebraPorAlmoxarifado;

  if (!$lQuebraPorAlmoxarifado) {

    if ($sTipoImpressao == 'sintetico') {
      cabecalhoSintetico(false, $oDados);
    }

    if ($sTipoImpressao == 'conferencia') {
      cabecalhoConferencia(false, $oDados);
    }
  }


  /*
   *  Criada variaveis para um totalizador, este totalizador, será apenas para o
   *  Tipo de Impressão: SINTETICO
   *
   */

  $nTotalQuantidadeAnterior = 0;
  $nTotalValorAnterior      = 0;
  $nTotalQuantidadeEntrada  = 0;
  $nTotalValorEntrada       = 0;
  $nTotalQuantidadeSaida    = 0;
  $nTotalValorSaida         = 0;
  $nTotalValorFinal         = 0;
  $nTotalQuantidadeFinal    = 0;

  foreach ($aDadosMovimentacoes as $oMovimentacao) {

    $oDados->iMaterial           = $oMovimentacao->getItem()->getCodigo();
    $oDados->sMaterial           = $oMovimentacao->getItem()->getNome();
    $oDados->iAlmoxarifado       = $oMovimentacao->getAlmoxarifado()->getCodigo();
    $oDados->sAlmoxarifado       = $oMovimentacao->getAlmoxarifado()->getNomeDepartamento();

//    $nValorAnterior = ;

    $oDados->nQuantidadeAnterior = formatar($oMovimentacao->getQuantidadeAnterior());
    $oDados->nValorAnterior      = formatar(formatarValor($oMovimentacao->getQuantidadeAnterior(), $oMovimentacao->getValorAnterior()));
    $oDados->nQuantidadeEntrada  = formatar($oMovimentacao->getQuantidadeEntrada());
    $oDados->nValorEntrada       = formatar(formatarValor($oMovimentacao->getQuantidadeEntrada(), $oMovimentacao->getValorEntrada()));
    $oDados->nQuantidadeSaida    = formatar($oMovimentacao->getQuantidadeSaida());
    $oDados->nValorSaida         = formatar(formatarValor($oMovimentacao->getQuantidadeSaida(), $oMovimentacao->getValorSaida()));
    $nValorFinal      = $oMovimentacao->getValorAnterior() + $oMovimentacao->getValorEntrada() - $oMovimentacao->getValorSaida();
    $nQuantidadeFinal = $oMovimentacao->getQuantidadeAnterior() + $oMovimentacao->getQuantidadeEntrada() - $oMovimentacao->getQuantidadeSaida();

    $oDados->lImprimirCabecalho  = false;

    /**
     * Nao exibe matareiais sem movimentacao no periodo
     */
    if ($somenteItensComMovimento && $oMovimentacao->getValorEntrada() <= 0 && $oMovimentacao->getValorSaida() <= 0) {
      continue;
    }

    $oDados->nValorFinal      = formatar($nValorFinal);
    $oDados->nQuantidadeFinal = formatar($nQuantidadeFinal);

    /**
     * Almoxarifado diferente do ultimo impresso, imprime cabecalho
     */
    if ($lQuebraPorAlmoxarifado && $oMovimentacao->getAlmoxarifado()->getCodigo() <> $iAlmoxarifadoAnterior) {

      if ($lMaterialImpresso) {
        $oPDF->ln();
      }

      $oDados->lImprimirCabecalho = true;
    }

    if ($tipoImpressao == 'conferencia') {

      if ($oDados->lImprimirCabecalho) {
        cabecalhoConferencia($lQuebraPorAlmoxarifado, $oDados);
      }

      linhaConferencia($oDados);
    }

    if ($tipoImpressao == 'sintetico') {

      if ($oDados->lImprimirCabecalho) {
        cabecalhoSintetico($lQuebraPorAlmoxarifado, $oDados);
      }

      linhaSintetico($oDados);

      /**
       * somamos os totalizadores, somente quando ele for imprimir
       * dados sintetico
       */
	    $nTotalQuantidadeAnterior  += $oMovimentacao->getQuantidadeAnterior();
	    $nTotalValorAnterior       += $oMovimentacao->getValorAnterior();
	    $nTotalQuantidadeEntrada   += $oMovimentacao->getQuantidadeEntrada();
	    $nTotalValorEntrada        += $oMovimentacao->getValorEntrada();
	    $nTotalQuantidadeSaida     += $oMovimentacao->getQuantidadeSaida();
	    $nTotalValorSaida          += $oMovimentacao->getValorSaida();
	    $nTotalValorFinal          += $nValorFinal;
	    $nTotalQuantidadeFinal     += $nQuantidadeFinal;
    }

    $lMaterialImpresso     = true;
    $iAlmoxarifadoAnterior = $oMovimentacao->getAlmoxarifado()->getCodigo();
  }

  if (!$lMaterialImpresso) {
    throw new Exception(_M('patrimonial.material.mat2_controleestoque002.nenhuma_movimentacao_encontrada'));
  }

  /**
   * Impressão da linha dos totalizadores
   * Somente para Tipo de Impressão: SINTETICO
   *
   */

  if ($tipoImpressao == 'sintetico') {

	  $oPDF->setfont('arial', 'b', TAMANHO_FONTE);
	  linha(36, "TOTAL", "TRB", "R");
	  linha(8, 	formatar($nTotalQuantidadeAnterior), "TRB", "R");
	  linha(8, 	formatar(formatarValor($nTotalQuantidadeAnterior, $nTotalValorAnterior)) , "TRB", "R");
	  linha(8, 	formatar($nTotalQuantidadeEntrada), "TRB", "R");
	  linha(8, 	formatar(formatarValor($nTotalQuantidadeEntrada, $nTotalValorEntrada)), "TRB", "R");
	  linha(8, 	formatar($nTotalQuantidadeSaida), "TRB", "R");
	  linha(8, 	formatar(formatarValor($nTotalQuantidadeSaida, $nTotalValorSaida)), "TRB", "R");
	  linha(8, 	formatar($nTotalQuantidadeFinal), "TRB", "R");
		linha(8, 	formatar(formatarValor($nTotalQuantidadeFinal, $nTotalValorFinal)), "TB" , "R");
  }

  $oPDF->Output();

} catch (Exception $oErro) {
  $sMensagemErro = nl2br($oErro->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlEncode($sMensagemErro));
}

function linhaSintetico(stdClass $oDados) {

  $oPDF = getPdf();
  $oPDF->setfont('arial', '', TAMANHO_FONTE);

  $sMaterial = $oDados->sMaterial;

  if (strlen($sMaterial) > 37) {
    $sMaterial = substr($oDados->sMaterial, 0, 37) . '...';
  }

  linha(8, $oDados->iMaterial, "TRB", "C");
  linha(28, $sMaterial, "TRB", "L");
  linha(8, $oDados->nQuantidadeAnterior, "TRB", "R");
  linha(8, $oDados->nValorAnterior, "TRB", "R");
  linha(8, $oDados->nQuantidadeEntrada, "TRB", "R");
  linha(8, $oDados->nValorEntrada, "TRB", "R");
  linha(8, $oDados->nQuantidadeSaida, "TRB", "R");
  linha(8, $oDados->nValorSaida, "TRB", "R");
  linha(8, $oDados->nQuantidadeFinal, "TRB", "R");

  if ($oDados->nQuantidadeFinal == 0 && $oDados->nValorFinal <= 0.99) {
    linha(8, '0,00', "TB", "R");
  } else {
    linha(8, $oDados->nValorFinal, "TB", "R");
  }

  $oPDF->ln();

  /**
   * Quebra de pagina
   */
  if ($oPDF->GetY() > $oPDF->h - MARGEM_NOVA_PAGINA) {

    $oPDF->AddPage();
    cabecalhoSintetico($oDados->lQuebraPorAlmoxarifado, $oDados);
  }

}

function cabecalhoSintetico($lImprimirCabecalhoAlmoxarifado = false, $oDados = null) {

  if ($lImprimirCabecalhoAlmoxarifado) {
    cabecalhoAlmoxarifado($oDados);
  }

  $oPDF = getPdf();
  $oPDF->setfont('arial', 'b', TAMANHO_FONTE_CABECALHO);

  linha(36, "Material", "TRB", "C");

  if (!empty($oDados->sPeriodoInicial)){
    linha(16, "Saldo anterior a " . $oDados->sPeriodoInicial, "TRB", "C");
  } else {
    linha(16, "Saldo anterior", "TRB", "C");
  }

  linha(16, "Entradas no período", "TRB", "C");
  linha(16, "Saídas no período", "TRB", "C");
  linha(16, "Saldo em " . $oDados->sPeriodoFinal, "TB", "C");

  $oPDF->ln();

  linha(8, "Código", "TRB", "C");
  linha(28, "Descrição", "TRB", "C");
  linha(8, "Quantidade", "TRB", "C");
  linha(8, "Valor", "TRB", "C");
  linha(8, "Quantidade", "TRB", "C");
  linha(8, "Valor", "TRB", "C");
  linha(8, "Quantidade", "TRB", "C");
  linha(8, "Valor", "TRB", "C");
  linha(8, "Quantidade", "TRB", "C");
  linha(8, "Valor", "TB", "C");

  $oPDF->ln();
  $oPDF->setfont('arial', '', TAMANHO_FONTE);
}

function cabecalhoAlmoxarifado(stdClass $oDados) {

  $oPDF = getPdf();

  if ($oPDF->GetY() > $oPDF->h - MARGEM_NOVA_PAGINA - ALTURA_LINHA) {
    $oPDF->AddPage();
  }

  $sAlmoxarifado = $oDados->iAlmoxarifado . ' - ' . $oDados->sAlmoxarifado;
  $oPDF->setfont('arial', 'b', TAMANHO_FONTE_CABECALHO);
  linha(100, $sAlmoxarifado, "TB", "L");
  $oPDF->setfont('arial', '', TAMANHO_FONTE);
  $oPDF->ln();
}

function linhaConferencia(stdClass $oDados) {

  $oPDF = getPdf();
  $oPDF->setfont('arial', '', TAMANHO_FONTE);
  $sMaterial = $oDados->iMaterial . ' - ' . $oDados->sMaterial;
  $sAlmoxarifado = $oDados->iAlmoxarifado . ' - ' . $oDados->sAlmoxarifado;

  if (strlen($sMaterial) > 57) {
    $sMaterial = substr($oDados->sMaterial, 0, 57) . '...';
  }

  if ($oDados->lQuebraPorAlmoxarifado) {

    linha(70, $sMaterial, "TRB", "L");
    linha(10, $oDados->nQuantidadeFinal, "TRB", "R");
    linha(10, '', "TRB", "C");
    if ($oDados->nQuantidadeFinal == 0 && $oDados->nValorFinal <= 0.99) {
      linha(10, '0,00', "TB", "R");
    } else {
      linha(10, $oDados->nValorFinal, "TB", "R");
    }

  } else {

    linha(40, $sMaterial, "TRB", "L");
    linha(30, $sAlmoxarifado, "TRB", "L");
    linha(10, $oDados->nQuantidadeFinal, "TRB", "R");
    linha(10, '', "TRB", "C");

    if ($oDados->nQuantidadeFinal == 0 && $oDados->nValorFinal <= 0.99) {
      linha(10, '0,00', "TB", "R");
    } else {
      linha(10, $oDados->nValorFinal, "TB", "R");
    }
  }

  $oPDF->ln();

   /**
   * Quebra de pagina
   */
  if ($oPDF->GetY() > $oPDF->h - MARGEM_NOVA_PAGINA) {

    $oPDF->AddPage();
    cabecalhoConferencia($oDados->lQuebraPorAlmoxarifado,  $oDados);
  }
}

function cabecalhoConferencia($lQuebraPorAlmoxarifado = false, $oDados = null) {

  $oPDF = getPdf();
  $oPDF->setfont('arial', 'b', TAMANHO_FONTE_CABECALHO);

  if ($lQuebraPorAlmoxarifado) {

    cabecalhoAlmoxarifado($oDados);
    $oPDF->setfont('arial', 'b', TAMANHO_FONTE_CABECALHO);

    linha(70, "Material", "TRB", "C");
    linha(10, "Quantidade", "TRB", "C");
    linha(10, "Contagem", "TRB", "C");
    linha(10, "Valor final", "TB", "C");

  } else {

    linha(40, "Material", "TRB", "C");
    linha(30, "Almoxarifado", "TRB", "C");
    linha(10, "Quantidade", "TRB", "C");
    linha(10, "Contagem", "TRB", "C");
    linha(10, "Valor final", "TB", "C");
  }

  $oPDF->setfont('arial', '', TAMANHO_FONTE);
  $oPDF->ln();
}

/**
 * Imprime linha no relatorio
 *
 * @param integer $iLargura
 * @param string $sValor
 * @param string $sBordas
 * @param string $sAlinhamento
 * @access public
 * @return void
 */
function linha($iLargura, $sValor, $sBordas, $sAlinhamento, $iAlturaLinha = ALTURA_LINHA) {
  getPdf()->cell(larguraColuna($iLargura), $iAlturaLinha, $sValor, $sBordas, 0, $sAlinhamento);
}

/**
 * Retorna instancia do objeto PDF
 *
 * @access public
 * @return PDF
 */
function getPdf() {
  return $GLOBALS['oPDF'];
}

/**
 * Largura da coluna
 *
 * @param string $sTipo
 * @param float $nPorcentagem - Porcentagem que a coluna ocupara na linha
 * @return float
 */
function larguraColuna($nPorcentagem = 0) {

  if ( $nPorcentagem == 0 ) {
    return LARGURA_PAGINA;
  }

  return round($nPorcentagem / 100 * LARGURA_PAGINA, 2);
}

/**
 * Formata valor e quantidade
 *
 * @param float $nValor
 * @param int $iCasasDecimais
 * @access public
 * @return float
 */
function formatar($nValor, $iCasasDecimais = 2) {
  return number_format( (float) $nValor, (int) $iCasasDecimais, ',', '.');
}

/**
 * Função que retorna ZERO caso o valor informado no parâmetro seja negativo
 * @param $nValor
 * @return string
 */
function formatarValor($iQuantidade, $nValor) {

  $iQuantidade = round($iQuantidade, 2);
  return (($iQuantidade == 0 || $iQuantidade == 0.01) && $nValor != 0) ? '0' : $nValor;
}