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

require_once('fpdf151/FpdfMultiCellBorder.php');
require_once('libs/db_utils.php');

$oGet    = db_utils::postMemory($_GET);
$oPedido = new PedidoTFD($oGet->iPedido);

$oDaoPedidoTFD = new cl_tfd_pedidotfd();
$sSqlAndamento = $oDaoPedidoTFD->sql_query_andamento_pedido($oGet->iPedido);
$aAndamentos   = array();
$sMsgErro      = null;

try {

  $rsAndamento = db_query($sSqlAndamento);
  if (!$rsAndamento )  {
    throw new Exception(pg_last_error());
  }

  $iLinhas = pg_num_rows($rsAndamento);

  for ($i = 0; $i < $iLinhas; $i++ ) {

    $oDados = db_utils::fieldsMemory($rsAndamento, $i);
    if ( empty($oDados->usuario) ) {
      continue;
    }

    $oUsuario             = UsuarioSistemaRepository::getPorCodigo($oDados->usuario);
    $oDados->sNomeUsuario = utf8_encode($oUsuario->getCGM()->getNome());
    $oDados->observacao   = utf8_encode($oDados->observacao);
    $aAndamentos[]        = $oDados;
  }

} catch (Exception $e) {
  $sMsgErro = $e->getMessage();
}

// var_dump($aAndamentos);


$head1 = "ANDAMENTO DO PEDIDO DO TFD";
$head2 = "CGS: {$oPedido->getPaciente()->getCodigo()} - {$oPedido->getPaciente()->getNome()} ";
$head3 = "Pedido: {$oGet->iPedido}";
$head4 = "Unidade: {$oPedido->getDepartamento()->getNomeDepartamento()}";

$oPdf = new FpdfMultiCellBorder();

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->exibeHeader(true);
$oPdf->setLeftMargin(10);

$oPdf->setfillcolor(220);
$oPdf->setfont('arial', 'B', 7);



$lPrimeiraVez = true;
foreach ($aAndamentos as $oAndamento) {

  if ($lPrimeiraVez || ($oPdf->getY() >= $oPdf->h - 20) ) {

    imprimeHeader($oPdf);
    $lPrimeiraVez = false;
  }


  $oUsuario     = UsuarioSistemaRepository::getPorCodigo($oAndamento->usuario);
  $sNomeUsuario = $oUsuario->getCGM()->getNome();
  $sNomeUsuario = substr($sNomeUsuario, 0, 48);
  $oData        = new DBDate($oAndamento->data);

  $oPdf->setfont('arial', '', 7);

  $iLinhasObservacao = $oPdf->nbLines(80, $oAndamento->observacao);

  $iAlturaLinha = 4;
  if ($iLinhasObservacao > 1) {
    $iAlturaLinha = 4 * $iLinhasObservacao;
  }

  $oPdf->cell( 15, $iAlturaLinha, $oData->convertTo(DBDate::DATA_PTBR), 1, 0, "C" );
  $oPdf->cell( 10, $iAlturaLinha, $oAndamento->hora ,                   1, 0, "C" );
  $oPdf->cell( 73, $iAlturaLinha, $sNomeUsuario,                        1, 0, "L" );
  $oPdf->cell( 20, $iAlturaLinha, $oAndamento->situacao,                1, 0, "C" );
  $oPdf->MultiCell(75,         4, $oAndamento->observacao,              1, 'L');
}

function imprimeHeader(FPDF $oPdf) {

  $oPdf->setfont('arial', 'B', 7);
  $oPdf->addPage('P');
  $oPdf->cell( 15, 4, "Data",       1, 0, "C", 1 );
  $oPdf->cell( 10, 4, "Hora",       1, 0, "C", 1 );
  $oPdf->cell( 73, 4, "Usuário",    1, 0, "C", 1 );
  $oPdf->cell( 20, 4, "Situação",   1, 0, "C", 1 );
  $oPdf->cell( 75, 4, "Observação", 1, 1, "C", 1 );
}
// Quadrinho cinza

// CGS( CÓDIGO E NOME )
// Pedido
// Unidade

// Dados da tela( conforme SQL )


$oPdf->output();