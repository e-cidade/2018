<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once(modification("fpdf151/pdfnovo.php"));

$oGet = db_utils::postMemory($_GET);

$iCodigobanco = $oGet->banco;
$iAno         = $oGet->ano;
$iMes         = $oGet->mes;

$aServidores = array();
$oBanco      = new Banco($iCodigobanco);
$aParcelas   = ArquivoConsignadoManualParcelaRepository::getParcelasProcessadasNaCompetenciaPorBanco(new DBCompetencia($iAno, $iMes), $oBanco);

foreach ($aParcelas as $oParcela) {

	$sMotivo = 'Não descontado - ' . ucfirst(strtolower($oParcela->getMotivo()));

	if(trim($oParcela->getMotivo())==false && $oParcela->getValorDescontado() > 0) {
		$sMotivo = 'Descontado';
	}

  $aServidores[] = (object)array('iMatricula'       => $oParcela->getServidor()->getMatricula(),
							  								 'sNome'            => $oParcela->getServidor()->getCgm()->getNome(),
							  								 'sSituacao'        => $sMotivo,
							  								 'nValorDescontar'  => $oParcela->getValor(),
							  								 'nValorDescontado' => $oParcela->getValorDescontado()
						  								  );	
}

$oPdf = new PDFNovo();
$oPdf->addHeader( "" );
$oPdf->addHeader( "Retorno de Empréstimos Consignados" );
$oPdf->addHeader( "Banco: ". $oBanco->getCodigo() .' - '. $oBanco->getNome());
$oPdf->addHeader( "Competência: ". $iAno ."/". $iMes);

$iAltura = 4;

$oPdf->addTableHeader('Matrícula', 15, $iAltura, 'C', true);
$oPdf->addTableHeader('Nome', 75, $iAltura, 'C', true);
$oPdf->addTableHeader('Situação', 58, $iAltura, 'C', true);
$oPdf->addTableHeader('Valor da Parcela', 20, $iAltura, 'C', true);
$oPdf->addTableHeader('Valor Descontado', 25, $iAltura, 'C', true);

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setHeaderMargin(0.2);
$oPdf->SetFillColor(235);
$oPdf->AddPage();

$nValorDescontar  = 0;
$nTotalDescontado = 0;

foreach ($aServidores as $oServidor) {

	$oServidor->nValorDescontar = str_replace(",", ".", $oServidor->nValorDescontar);
	$oServidor->nValorDescontado = str_replace(",", ".", $oServidor->nValorDescontado);
	$nValorDescontar  += $oServidor->nValorDescontar;
	$nTotalDescontado += $oServidor->nValorDescontado;
	$oServidor->nValorDescontar = number_format($oServidor->nValorDescontar, 2, ',', '.');
	$oServidor->nValorDescontado = number_format($oServidor->nValorDescontado, 2, ',', '.');

  $oPdf->SetFont('arial','',7);
  $oPdf->Cell( 15, $iAltura, $oServidor->iMatricula, 'LBR', 0, "C");
  $oPdf->Cell( 75, $iAltura, $oServidor->sNome, 'LBR', 0, "L");
  $oPdf->Cell( 58, $iAltura, $oServidor->sSituacao, 'LBR', 0, "C");
  $oPdf->Cell( 20, $iAltura, $oServidor->nValorDescontar, 'LBR', 0, "R");
  $oPdf->Cell( 25, $iAltura, $oServidor->nValorDescontado, 'LBR', 1, "R");
}

$oPdf->Cell( 25, $iAltura, 'Total de Servidores:', 0, 0, 'R');
$oPdf->Cell( 88, $iAltura, count($aServidores), 0, 0, 'L');
$oPdf->Cell( 35, $iAltura, 'Totais:', 0, 0, 'R');
$oPdf->Cell( 20, $iAltura, number_format($nValorDescontar, 2, ',', '.'), 0, 0, 'R');
$oPdf->Cell( 25, $iAltura, number_format($nTotalDescontado, 2, ',', '.'), 0, 1, 'R');
$oPdf->Output();