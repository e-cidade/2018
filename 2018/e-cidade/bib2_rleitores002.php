<?
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
require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/pdf.php"));

parse_str(base64_decode($_GET['q']), $aGet);
define('MSG_LEITORES', 'educacao.biblioteca.bib2_rleitores002.');

$oLeitores             = new stdClass();
$oLeitores->sOrdem     = 'NUMÉRICA';
$oLeitores->sCategoria = '';
$oLeitores->aLeitores  = array();

try {

  if ( empty($aGet['ordem']) ) {
    throw new ParameterException( _M(MSG_LEITORES . "ordem_nao_informada") );
  }
  if ( !isset($aGet['categoria']) ) {
    throw new ParameterException( _M(MSG_LEITORES . "categoria_nao_informada") );
  }
  if ( empty($aGet['sCategoria']) ) {
    throw new ParameterException( _M(MSG_LEITORES . "categoria_nao_informada") . 'assd');
  }

  $oLeitores->sCategoria = $aGet['sCategoria'];

  $sOrdem = 'bi16_codigo';
  if ( $aGet['ordem'] == 'a') {

    $oLeitores->sOrdem     = 'ALFABÉTICA';
    $sOrdem = 'ov02_nome';
  }
  $iDepartamento = db_getsession("DB_coddepto");

  $sCampos  = "x.bi16_codigo, trim(ov02_nome) as ov02_nome, ov02_cnpjcpf, ov02_endereco, ov02_numero, bi16_valida, ";
  $sCampos .= "trim(bi07_nome) as categoria";

  $oDaoCarteira = new cl_carteira();
  $sSqlLeitores = $oDaoCarteira->sql_query_ultima_carteira(null, $sCampos, $sOrdem, null, $iDepartamento, $aGet['categoria']);
  $rsLeitores   = db_query($sSqlLeitores);

  if ( !$rsLeitores ) {
    throw new DBException( _M( MSG_LEITORES . "erro_buscar_leitores") );
  }

  if ( pg_num_rows($rsLeitores) == 0 ) {
    throw new DBException( _M( MSG_LEITORES . "nenhum_leitor_encontrado") );
  }

  $oLeitores->aLeitores =  db_utils::getCollectionByRecord($rsLeitores);
} catch( Exception $e ) {

  $sMsg = urlencode($e->getMessage());
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

$head1 = "RELATÓRIO DE LEITORES";
$head2 = "Ordem:     {$oLeitores->sOrdem}";
$head3 = "Categoria: {$oLeitores->sCategoria}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->SetAutoPageBreak(false,10);
$oPdf->AliasNbPages();

function imprimeCabecalho($oPdf) {

  $oPdf->SetFillColor(215);
  $oPdf->AddPage();
  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->Cell(17, 4, "N° Carteira", 1, 0, "C", 1);
  $oPdf->Cell(60, 4, "Nome",        1, 0, "C", 1);
  $oPdf->Cell(20, 4, "CPF",         1, 0, "C", 1);
  $oPdf->Cell(55, 4, "Endereço",    1, 0, "C", 1);
  $oPdf->Cell(25, 4, "Categoria",   1, 0, "C", 1);
  $oPdf->Cell(15, 4, "Situação",     1, 1, "C", 1);

  $oPdf->SetFont('arial', '', 7);
  $oPdf->SetFillColor(240);
}

$lFirstPage = true;
$lPinta     = true;
foreach ($oLeitores->aLeitores as $oLeitor) {

  if ($lFirstPage) {

    imprimeCabecalho($oPdf);
    $lFirstPage = false;
  }

  $sEndereco = $oLeitor->ov02_endereco.", ".$oLeitor->ov02_numero;
  $sSituacao = $oLeitor->bi16_valida == 'S' ? 'VÁLIDA' : 'VENCIDA';

  $iYAntes        = $oPdf->gety();
  $aAlturaLinha   = array();
  $aAlturaLinha[] = $oPdf->NbLines(60, $oLeitor->ov02_nome);
  $aAlturaLinha[] = $oPdf->NbLines(55, $sEndereco);
  $aAlturaLinha[] = $oPdf->NbLines(25, $oLeitor->categoria);
  $iLinhas        = array_reduce($aAlturaLinha, "DBNumber::maiorValor");
  $iAlturaLinha   = 4 * $iLinhas;

  if ( ($oPdf->gety() ) > ($oPdf->h - 15)) {

    imprimeCabecalho($oPdf);
    $lFirstPage = false;
    $iYAntes    = $oPdf->gety();
  }

  $lPinta = !$lPinta;
  if ($lPinta)  {
    $oPdf->Rect($oPdf->getX(), $iYAntes, 192, $iAlturaLinha, "F");
  }
  $oPdf->cell(17, $iAlturaLinha, $oLeitor->bi16_codigo,  0, 0, "C");
  $oPdf->MultiCell(60, 4, $oLeitor->ov02_nome,           0, "L");
  $oPdf->SetXY(87, $iYAntes);
  $oPdf->cell(20, $iAlturaLinha, $oLeitor->ov02_cnpjcpf, 0, 0, "L");
  $oPdf->MultiCell(55, 4, $sEndereco,                    0, "L");
  $oPdf->SetXY(162, $iYAntes);
  $oPdf->MultiCell(25, 4, $oLeitor->categoria,           0, "L");
  $oPdf->SetXY(187, $iYAntes);
  $oPdf->cell(15, $iAlturaLinha, $sSituacao,             0, 1, "L");
}

$oPdf->setfont('arial','b',8);
$oPdf->cell(192, 4, 'TOTAL DE LEITORES:  ' . count($oLeitores->aLeitores) , "T", 0, "L", 0);
$oPdf->Output();