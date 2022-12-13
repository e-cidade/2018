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
 
require_once 'libs/db_utils.php';
require_once 'fpdf151/pdf.php';
require_once 'std/DBDate.php';

try { 

  $oGet = db_utils::postMemory($_GET);
  $sDataProcessamento = $oGet->sDataProcessamento;
  $iAlturaLinha = 4;
  $head2 = "Relatório: Posição Estoque";
  $head4 = "Data processamento: " . $sDataProcessamento;

  $oPDF = new PDF('P', 'mm', 'A4'); 
  $oPDF->iAlturaLinha = $iAlturaLinha;
  $oPDF->Open(); 
  $oPDF->AliasNbPages(); 
  $oPDF->setfillcolor(235);
  $oPDF->setfont('arial', 'b', 8);
  $oPDF->addpage();
  $oPDF->ln();

  $aPosicaoEstoque = getPosicaoEstoque($sDataProcessamento);
  $nTotalGeral = 0;

  foreach ( $aPosicaoEstoque as $oPosicaoEstoque ) {

    $sDepartamento = $oPosicaoEstoque->sDepartamento;

    cabecalho($oPDF, $sDepartamento);

    foreach ( $oPosicaoEstoque->aDadosItens as $oDadosItem ) {
      linha($oPDF, $oDadosItem);
    }

    total($oPDF, 'Total almoxarifado ' . $sDepartamento, $oPosicaoEstoque->nTotal);
    $nTotalGeral += $oPosicaoEstoque->nTotal;
  }

  total($oPDF, 'Total geral ', $nTotalGeral);
  $oPDF->Output();

} catch ( Exception $oErro ) {

  $sMensagemErro = str_replace("\n", '<br />', $oErro->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlEncode($sMensagemErro));
}

/**
 * Busca posicao do estoque dos itens processados pela data informada e instituicao logada
 *
 * @param string $sDataProcessamento
 * @access public
 * @return void
 */
function getPosicaoEstoque($sDataProcessamento) {

  $oDaoPosicaoEstoque = db_utils::getDao('posicaoestoque');
  $oData = new DBDate($sDataProcessamento);

  $sDataProcessamento = $oData->getDate();
  $iInstituicao	= db_getsession('DB_instit');

  $sCampos  = 'm60_codmater   as codigo_item,';
  $sCampos .= 'm60_descr      as descricao_item,';
  $sCampos .= 'coddepto       as codigo_departamento,';
  $sCampos .= 'descrdepto     as descricao_departamento,';
  $sCampos .= 'm06_quantidade as quantidade,';
  $sCampos .= 'm06_valor      as valor,';
  $sCampos .= 'm06_precomedio as preco_medio';

  $sWhere = "m05_data = '$sDataProcessamento' and m05_instit = $iInstituicao";

  $sSqlPosicaoEstoque = $oDaoPosicaoEstoque->sql_query(null, $sCampos, null, $sWhere);
  $rsPosicaoEstoque   = db_query($sSqlPosicaoEstoque);
  $iTotalItens = pg_num_rows($rsPosicaoEstoque);
  $aPosicaoEstoque = array();

  if ( !$rsPosicaoEstoque ) {
    throw new Exception("Erro ao buscar posição do estoque.\n\n" . pg_last_error());
  }

  if ( $iTotalItens == 0 ) {
    throw new Exception("Nenhum item encontrado para a data do processamento informada.");
  }

  for ( $iIndice = 0; $iIndice < $iTotalItens; $iIndice++ ) {

    $oPosicaoEstoque = db_utils::fieldsMemory($rsPosicaoEstoque, $iIndice);

    $oDadosPosicao = new StdClass();
    $oDadosPosicao->nTotal = $oPosicaoEstoque->valor;
    $oDadosPosicao->sDepartamento = $oPosicaoEstoque->codigo_departamento . ' - ' .$oPosicaoEstoque->descricao_departamento;

    if ( !empty($aPosicaoEstoque[$oPosicaoEstoque->codigo_departamento]) ) {

      $oDadosPosicao = $aPosicaoEstoque[$oPosicaoEstoque->codigo_departamento];
      $oDadosPosicao->nTotal += $oPosicaoEstoque->valor;
    }

    $oDadosItem = new StdClass();

    $oDadosItem->iCodigo       = $oPosicaoEstoque->codigo_item;
    $oDadosItem->sDescricao    = $oPosicaoEstoque->descricao_item;
    $oDadosItem->nValor        = $oPosicaoEstoque->valor;
    $oDadosItem->nQuantidade   = $oPosicaoEstoque->quantidade;

    $oDadosPosicao->aDadosItens[] = $oDadosItem;
    $aPosicaoEstoque[$oPosicaoEstoque->codigo_departamento] = $oDadosPosicao;
  }

  return $aPosicaoEstoque;
}

/**
 * Imprime cabeçalho 
 *
 * @param PDF $oPDF
 * @param string $sDepartamento
 * @access public
 * @return void
 */
function cabecalho(PDF $oPDF, $sDepartamento) {

  novaPagina($oPDF);
  $iAlturaLinha = $oPDF->iAlturaLinha + 1;
  $sTitulo = 'Almoxarifado: ' . $sDepartamento;

  $oPDF->setfont('arial', 'b', 8);
  $oPDF->cell(larguraColuna(),   $iAlturaLinha, $sTitulo,     1, 1, 'L', 1);
  $oPDF->cell(larguraColuna(8),  $iAlturaLinha, 'Código',     1, 0, 'L', 1);
  $oPDF->cell(larguraColuna(52), $iAlturaLinha, 'Descrição',  1, 0, 'L', 1);
  $oPDF->cell(larguraColuna(20), $iAlturaLinha, 'Quantidade', 1, 0, 'L', 1);
  $oPDF->cell(larguraColuna(20), $iAlturaLinha, 'Valor',      1, 0, 'L', 1);
	$oPDF->setfont('arial', '', 7);
  $oPDF->ln();
}

/**
 * Imprime linha com informacoes do item
 *
 * @param PDF $oPDF
 * @param StdClass $oDadosLinha
 * @access public
 * @return void
 */
function linha(PDF $oPDF, $oDadosLinha) {

  novaPagina($oPDF);
  
  $nValor      = number_format($oDadosLinha->nValor, 3, ',', '.');
  $nQuantidade = number_format($oDadosLinha->nQuantidade, 2, ',', '.');

  $oPDF->cell(larguraColuna(8),  $oPDF->iAlturaLinha, $oDadosLinha->iCodigo,     1, 0, 'L', 0);
  $oPDF->cell(larguraColuna(52), $oPDF->iAlturaLinha, $oDadosLinha->sDescricao,  1, 0, 'L', 0);
  $oPDF->cell(larguraColuna(20), $oPDF->iAlturaLinha, $nQuantidade,              1, 0, 'L', 0);
  $oPDF->cell(larguraColuna(20), $oPDF->iAlturaLinha, $nValor,                   1, 0, 'L', 0);
  $oPDF->ln();
}

/**
 * Imprime total por deparamento
 *
 * @param PDF $oPDF
 * @param string $sDepartamento
 * @param float $nTotal
 * @access public
 * @return void
 */
function total(PDF $oPDF, $sTitulo, $nTotal) {

  novaPagina($oPDF);

  $iAlturaLinha = $oPDF->iAlturaLinha + 1;
  $nTotal = number_format($nTotal, 2, ',', '.'); 
  
  $oPDF->setfont('arial', 'b', 8);
  $oPDF->cell(larguraColuna(), $iAlturaLinha, $sTitulo . ': ' . $nTotal, 1, 1, 'R', 1);
	$oPDF->setfont('arial', '', 7);
  $oPDF->ln();
}

/**
 * Verifica necessidade de imprimir nova pagina
 *
 * @param PDF $oPDF
 * @param int $iMarginBottom
 * @access public
 * @return void
 */
function novaPagina(PDF $oPDF, $iMarginBottom = 30) {

	if ($oPDF->gety() > $oPDF->h - $iMarginBottom) {
    $oPDF->addpage();
  }
}

/**
 * Retorna largura da coluna apartir de uma porcentagem
 *
 * @param float $nPorcentagem - porcentagem do total da linha
 * @access public
 * @return float
 */
function larguraColuna($nPorcentagem = 0) {

  $iLarguraMaxima = 190;

  if ( $nPorcentagem == 0 ) {
    return $iLarguraMaxima;
  }

  $iColuna = round($nPorcentagem / 100 * $iLarguraMaxima, 2);

  return $iColuna;
}