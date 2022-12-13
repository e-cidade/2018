<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("fpdf151/PDFDocument.php");
require_once modification("std/DBNumber.php");

/**
 * Variaveis do header do PDF
 */
$oGet = db_utils::postMemory($_GET);

$oDaoIptuCalv = new cl_iptucalv();
$rsIptuCalv   = $oDaoIptuCalv->sql_record($oDaoIptuCalv->sql_queryValoresCalculoIptu($oGet->iAnoCalculo));
$aCalculoIptu = db_utils::getCollectionByRecord($rsIptuCalv);

$oDaoCfIptu = new cl_cfiptu();
$rsCfIptu   = $oDaoCfIptu->sql_record($oDaoCfIptu->sql_query($oGet->iAnoCalculo, "j18_tipodebitorecalculo"));
$oCfIptu    = db_utils::fieldsMemory($rsCfIptu, 0);

$aDiversosIptu = array();

if(!empty($oCfIptu->j18_tipodebitorecalculo)){

  $rsIptuCalv    = $oDaoIptuCalv->sql_record($oDaoIptuCalv->sql_queryIptuDiversos($oGet->iAnoCalculo, $oCfIptu->j18_tipodebitorecalculo));
  $aDiversosIptu = db_utils::getCollectionByRecord($rsIptuCalv);
}

$oDaoIssCalc   = new cl_isscalc();
$rsIssCalc     = $oDaoIssCalc->sql_record($oDaoIssCalc->sql_queryIssqnVistorias($oGet->iAnoCalculo));
$aCalculoIssqn = db_utils::getCollectionByRecord($rsIssCalc);

$aTotalDiversosComplementar = array();

foreach ($aDiversosIptu as $oDiversosIptu){
  $aTotalDiversosComplementar[$oDiversosIptu->receita_codigo] = $oDiversosIptu->valor_calculado;
}

$nTotalGeral = 0;

$oPdf = new PDFDocument('L');
$oPdf->addHeaderDescription("Relatório de Lançamentos Tributários");
$oPdf->addHeaderDescription("Exercício dos Lançamentos: $oGet->iAnoCalculo");
$oPdf->addHeaderDescription("Data de Geração: " . date('d/m/Y', db_getsession('DB_datausu')));
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->AddPage();
$oPdf->SetFillColor(235);

/**
 * Escreve titulo do IPTU
 */
$oPdf->Setfont('Arial', 'b', 9);
$oPdf->cell( largura(0), 6, "IPTU {$oGet->iAnoCalculo}", 1, 0, 'L', 1);
$oPdf->ln(6);

/**
 * Escreve colunas de cabecalhos do IPTU
 */
inserirLinha($oPdf, array('CodigoReceitaIPTU'    => 'Receita',
                          'DescricaoReceitaIPTU' => 'Descrição',
                          'QuantidadeIPTU'       => 'Quantidade',
                          'ValorCalculado'       => 'Valor Calculado',
                          'ValorIsento'          => 'Valor Isento',
                          'ValorImportado'       => 'Valor Importado',
                          'ValorCompensado'      => 'Valor Compensado',
                          'ValorPago'            => 'Valor Pago/Bruto',
                          'ValorCancelado'       => 'Valor Cancelado',
                          'ValorAPagar'          => 'Valor a Pagar'), false, true);

/**
 * linhas do IPTU
 */
$nTotalCalculado  = 0;
$nTotalIsento     = 0;
$nTotalImportado  = 0;
$nTotalPago       = 0;
$nTotalCancelado  = 0;
$nTotalaPagar     = 0;
$nTotalCompensado = 0;

foreach ($aCalculoIptu as $oCalculoIptu) {

  if(!empty($aTotalDiversosComplementar[$oCalculoIptu->codigo_receita])){
    $oCalculoIptu->valor_calculado -= $aTotalDiversosComplementar[$oCalculoIptu->codigo_receita];
  }

  inserirLinha($oPdf, array('CodigoReceitaIPTU'    => $oCalculoIptu->codigo_receita,
                            'DescricaoReceitaIPTU' => $oCalculoIptu->descricao_receita,
                            'QuantidadeIPTU'       => $oCalculoIptu->quantidade,
                            'ValorCalculado'       => $oCalculoIptu->valor_calculado,
                            'ValorIsento'          => $oCalculoIptu->valor_isento,
                            'ValorImportado'       => $oCalculoIptu->valor_importado,
                            'ValorCompensado'      => $oCalculoIptu->valor_compensado,
                            'ValorPago'            => $oCalculoIptu->valor_pago,
                            'ValorCancelado'       => $oCalculoIptu->valor_cancelado,
                            'ValorAPagar'          => $oCalculoIptu->valor_a_pagar));

  $nTotalCalculado  +=  $oCalculoIptu->valor_calculado;
  $nTotalIsento     +=  $oCalculoIptu->valor_isento;
  $nTotalImportado  +=  $oCalculoIptu->valor_importado;
  $nTotalPago       +=  $oCalculoIptu->valor_pago;
  $nTotalCancelado  +=  $oCalculoIptu->valor_cancelado;
  $nTotalaPagar     +=  $oCalculoIptu->valor_a_pagar;
  $nTotalCompensado +=  $oCalculoIptu->valor_compensado;
}

inserirLinha($oPdf, array('CodigoReceitaIPTU'    => '',
                          'DescricaoReceitaIPTU' => '',
                          'QuantidadeIPTU'       => 'TOTAIS',
                          'ValorCalculado'       => $nTotalCalculado,
                          'ValorIsento'          => $nTotalIsento   ,
                          'ValorImportado'       => $nTotalImportado,
                          'ValorCompensado'      => $nTotalCompensado,
                          'ValorPago'            => $nTotalPago     ,
                          'ValorCancelado'       => $nTotalCancelado,
                          'ValorAPagar'          => $nTotalaPagar), true);

$nTotalGeral += $nTotalaPagar;

/**
 * Espaco entre as duas tabelas
 */
$oPdf->ln(10);

if(!empty($aDiversosIptu)){

  $nTotalCalculado  = 0;
  $nTotalPago       = 0;
  $nTotalCancelado  = 0;
  $nTotalaPagar     = 0;
  $nTotalCompensado = 0;

  $oPdf->Setfont('Arial', 'b', 9);
  $oPdf->cell( largura(0), 6, "IPTU Complementar {$oGet->iAnoCalculo}", 1, 0, 'L', 1);
  $oPdf->ln(6);

  inserirLinha($oPdf, array('DivTipoDebito'      => 'Receita',
                            'DivDescricao'       => 'Descrição',
                            'DivQuantidade'      => 'Quantidade',
                            'DivValorCalculado'  => 'Valor Calculado',
                            'ValorCompensado'    => 'Valor Compensado',
                            'DivValorPago'       => 'Valor Pago',
                            'DivValorCancelado'  => 'Valor Cancelado',
                            'DivValorAPagar'     => 'Valor a Pagar'), false, true);

  foreach($aDiversosIptu as $oDiversosIptu){

    inserirLinha($oPdf, array('DivTipoDebito'      => $oDiversosIptu->receita_codigo,
                              'DivDescricao'       => $oDiversosIptu->receita_descr,
                              'DivQuantidade'      => $oDiversosIptu->quantidade,
                              'DivValorCalculado'  => $oDiversosIptu->valor_calculado,
                              'ValorCompensado'    => $oDiversosIptu->valor_compensado,
                              'DivValorPago'       => $oDiversosIptu->valor_pago,
                              'DivValorCancelado'  => $oDiversosIptu->valor_cancelado,
                              'DivValorAPagar'     => $oDiversosIptu->valor_a_pagar));

    $nTotalCalculado  +=  $oDiversosIptu->valor_calculado;
    $nTotalPago       +=  $oDiversosIptu->valor_pago;
    $nTotalCancelado  +=  $oDiversosIptu->valor_cancelado;
    $nTotalaPagar     +=  $oDiversosIptu->valor_a_pagar;
    $nTotalCompensado +=  $oDiversosIptu->valor_compensado;
  }

  inserirLinha($oPdf, array('DivTipoDebito'     => '',
                            'DivDescricao'      => '',
                            'DivQuantidade'     => 'TOTAIS',
                            'DivValorCalculado' => $nTotalCalculado,
                            'ValorCompensado'   => $nTotalCompensado,
                            'DivValorPago'      => $nTotalPago     ,
                            'DivValorCancelado' => $nTotalCancelado,
                            'DivValorAPagar'    => $nTotalaPagar), true);

  $nTotalGeral += $nTotalaPagar;

  $oPdf->ln(10);
}

/**
 * Escreve titulo do ISSQN
 */
$oPdf->Setfont('Arial', 'b', 9);
$oPdf->cell( largura(0), 6, "ISSQN / Vistorias {$oGet->iAnoCalculo}", 1, 0, 'L', 1);
$oPdf->ln(6);

/**
 * Escreve colunas de cabecalhos do ISSQN
 */
inserirLinha($oPdf, array('TipoDebito'            => 'Tipo',
                          'CodigoReceitaISSQN'    => 'Receita',
                          'DescricaoReceitaISSQN' => 'Descrição',
                          'QuantidadeISSQN'       => 'Quantidade',
                          'ValorCalculado'        => 'Valor Calculado',
                          'ValorImportado'        => 'Valor Importado',
                          'ValorCompensado'       => 'Valor Compensado',
                          'ValorPago'             => 'Valor Pago',
                          'ValorCancelado'        => 'Valor Cancelado',
                          'ValorAPagar'           => 'Valor a Pagar'), false, true);

/**
 * Linhas do ISSQN
 */
$nTotalCalculado  = 0;
$nTotalImportado  = 0;
$nTotalPago       = 0;
$nTotalCancelado  = 0;
$nTotalaPagar     = 0;
$nTotalCompensado = 0;

foreach ($aCalculoIssqn as $oCalculoIssqn) {

  inserirLinha($oPdf, array('TipoDebito'            => $oCalculoIssqn->tipodebito,
                            'CodigoReceitaISSQN'    => $oCalculoIssqn->codigo_receita,
                            'DescricaoReceitaISSQN' => $oCalculoIssqn->receita,
                            'QuantidadeISSQN'       => $oCalculoIssqn->quantidade,
                            'ValorCalculado'        => $oCalculoIssqn->valor_calculado,
                            'ValorImportado'        => $oCalculoIssqn->valor_importado,
                            'ValorCompensado'       => $oCalculoIssqn->valor_compensado,
                            'ValorPago'             => $oCalculoIssqn->valor_pago,
                            'ValorCancelado'        => $oCalculoIssqn->valor_cancelado,
                            'ValorAPagar'           => $oCalculoIssqn->valor_a_pagar));

  $nTotalCalculado  +=  $oCalculoIssqn->valor_calculado;
  $nTotalImportado  +=  $oCalculoIssqn->valor_importado;
  $nTotalPago       +=  $oCalculoIssqn->valor_pago;
  $nTotalCancelado  +=  $oCalculoIssqn->valor_cancelado;
  $nTotalaPagar     +=  $oCalculoIssqn->valor_a_pagar;
  $nTotalCompensado +=  $oCalculoIssqn->valor_compensado;
}

inserirLinha($oPdf, array('TipoDebito'            => '',
                          'CodigoReceitaISSQN'    => '',
                          'DescricaoReceitaISSQN' => '',
                          'QuantidadeISSQN'       => 'TOTAIS',
                          'ValorCalculado'        => $nTotalCalculado,
                          'ValorImportado'        => $nTotalImportado,
                          'ValorCompensado'       => $nTotalCompensado,
                          'ValorPago'             => $nTotalPago     ,
                          'ValorCancelado'        => $nTotalCancelado,
                          'ValorAPagar'           => $nTotalaPagar), true);

$nTotalGeral += $nTotalaPagar;

$oPdf->ln(10);

inserirLinha($oPdf, array('TipoDebito'            => '',
                          'CodigoReceitaISSQN'    => '',
                          'DescricaoReceitaISSQN' => '',
                          'QuantidadeISSQN'       => '',
                          'ValorCalculado'        => '',
                          'ValorImportado'        => '',
                          'ValorCompensado'       => '',
                          'ValorPago'             => '',
                          'ValorCancelado'        => 'TOTAL GERAL',
                          'ValorAPagar'           => $nTotalGeral), true);


/**
 * Manda para o browser o pdf
 */
$oPdf->showPDF("lancamentos_tributarios_" . time());


/**
 * Calcula a largura da linha pela porcentagem
 *
 * @param float $nPorcentagem
 * @access public
 * @return integer
 */
function largura($nPorcentagem = 0) {

  $iColuna = 0;
  $iTotalLinha = 280;

  if ( $nPorcentagem == 0 ) {
    return $iTotalLinha;
  }

  $iColuna = $nPorcentagem / 100 * $iTotalLinha;
  $iColuna = round($iColuna, 2);

  return $iColuna;
}

/**
 * Método genérico para inserção das linhas de valores
 *
 * @param  PDF     $oPdf
 * @param  array   $aLinha
 * @param  boolean $lTotal
 */
function inserirLinha($oPdf, $aLinha, $lTotal = false, $lCabecalho = false) {

  $aLarguras = array('TipoDebito'            => 18,
                     'CodigoReceitaIPTU'     => 7,
                     'CodigoReceitaISSQN'    => 5,
                     'DescricaoReceitaIPTU'  => 18,
                     'DescricaoReceitaISSQN' => 13,
                     'QuantidadeIPTU'        => 8,
                     'QuantidadeISSQN'       => 6,
                     'ValorCalculado'        => 10,
                     'ValorIsento'           => 9,
                     'ValorImportado'        => 9,
                     'ValorCompensado'       => 10,
                     'ValorPago'             => 10,
                     'ValorCancelado'        => 9,
                     'ValorAPagar'           => 10,
                     'DivTipoDebito'         => 7,
                     'DivDescricao'          => 33,
                     'DivQuantidade'         => 10,
                     'DivValorCalculado'     => 10,
                     'DivValorPago'          => 10,
                     'DivValorCancelado'     => 10,
                     'DivValorAPagar'        => 10);

  $oPdf->Setfont('Arial', $lCabecalho ? 'b' : '' , 8);

  foreach ($aLinha as $sChave => $sLinha) {

    /**
     * Verificamos se o valor em questão é um numero, para colocar seu devido alinhamento
     */
    $sAlinhamento = ( $lTotal || $lCabecalho ) ? 'C' : 'L';

    if (is_numeric($sLinha)) {

      if (stripos($sChave, 'Valor') !== FALSE) {
        $sLinha       = db_formatar($sLinha, 'f');
      }

      $sAlinhamento = 'R';
    }

    /**
     * Verificamos a necessidade de colocar borda ao torno do valor
     */
    $iBorda = 1;
    if ($lTotal && empty($sLinha)) {
      $iBorda = 0;
    }

    $oPdf->cell( largura($aLarguras[$sChave]), 5, $sLinha, $iBorda, 0, $sAlinhamento);
  }
  $oPdf->ln();
}
