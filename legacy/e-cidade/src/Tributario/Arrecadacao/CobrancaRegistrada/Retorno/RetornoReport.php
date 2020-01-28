<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno;

use \PDFDocument;
use \DBDate;

class RetornoReport
{
  public static function reportCobrancaRegistrada(
    RetornoRequestFilters $oRetornoRequestFilters,
    RetornoCollection $oRetornoCollection
  ){
    $oPdf = new PDFDocument('L');
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->setfillcolor(235);

    $oPdf->addHeaderDescription('Retorno Cobrança Registrada');
    $oPdf->addHeaderDescription('Filtros Utilizados:');

    $sConvenioDescricao = $oRetornoRequestFilters->getConvenioDescricao();

    if (!empty($sConvenioDescricao)) {
      $oPdf->addHeaderDescription('Convênio: '.$sConvenioDescricao);
    }

    $sTipoDebitoDescricao = $oRetornoRequestFilters->getTipoDebitoDescricao();

    if (!empty($sTipoDebitoDescricao)) {
      $oPdf->addHeaderDescription('Tipo de Débito: '.$sTipoDebitoDescricao);
    }

    $oPdf->addHeaderDescription('Data de Emissão Inicio: '.$oRetornoRequestFilters->getDataEmissaoInicio()->getDate(DBDate::DATA_PTBR));
    $oPdf->addHeaderDescription('Data de Emissão Fim: '.$oRetornoRequestFilters->getDataEmissaoFim()->getDate(DBDate::DATA_PTBR));

    $sCodigoArrecadacao = $oRetornoRequestFilters->getCodigoArrecadacao();

    if (!empty($sCodigoArrecadacao)) {
      $oPdf->addHeaderDescription('Código de Arrecadação: '.$sCodigoArrecadacao);
    }

    $sOcorrenciaDescricao = $oRetornoRequestFilters->getOcorrenciaDescricao($oRetornoRequestFilters->getCodigoOcorrencia());

    $oPdf->addHeaderDescription('Ocorrência: '.$sOcorrenciaDescricao);

    $iAltura = 4;

    $oPdf->addpage();
    $oPdf->setfont('arial', 'b', 8);

    $iCellWidthCgm            = 25;
    $iCellWidthCodArrecadacao = 40;
    $iCellWidthTipoDebito     = 40;
    $iCellWidthConvenio       = 40;
    $iCellWidthEmissao        = 20;
    $iCellWidthOcorrencia     = 112;

    $oPdf->cell($iCellWidthCgm,            $iAltura, 'CGM',                   1, 0, "C", 1);
    $oPdf->cell($iCellWidthCodArrecadacao, $iAltura, 'Código de Arrecadação', 1, 0, "C", 1);
    $oPdf->cell($iCellWidthTipoDebito,     $iAltura, 'Tipo de Débito',        1, 0, "C", 1);
    $oPdf->cell($iCellWidthConvenio,       $iAltura, 'Convênio',              1, 0, "C", 1);
    $oPdf->cell($iCellWidthEmissao,        $iAltura, 'Emissão',               1, 0, "C", 1);
    $oPdf->cell($iCellWidthOcorrencia,     $iAltura, 'Ocorrência',            1, 0, "C", 1);

    $oPdf->ln();

    $oPdf->setfont('arial', null, 6);

    $iClr = 0;

    foreach ($oRetornoCollection as $oRetorno) {

      $iArrayCountTipo = count($oRetorno->aTipo);
      $iArrayCountOcorrencia = count($oRetorno->aOcorrencia);

      $iTotalLinhas = $iArrayCountOcorrencia;

      if ($iArrayCountTipo > $iTotalLinhas) {
        $iTotalLinhas = $iArrayCountTipo;
      }

      $lTotalLinhasImpar = true;

      if (($iTotalLinhas % 2) === 0) {
        $lTotalLinhasImpar = false;
      }

      for ($iLinha = 1; $iLinha <= $iTotalLinhas; $iLinha++) {

        $sBorder = 'LR';

        if ($iLinha == 1 and $iLinha != $iTotalLinhas) {
          $sBorder = 'LTR';
        } else if ($iLinha == $iTotalLinhas) {
          $sBorder = 'LBR';
        }

        if (
             ($lTotalLinhasImpar and ($iTotalLinhas == 1 or ((($iTotalLinhas - 1) / 2 ) + 1 == $iLinha)))
          or (!$lTotalLinhasImpar and $iTotalLinhas / 2 == $iLinha)
        ){

          $oPdf->Cell($iCellWidthCgm,            $iAltura, $oRetorno->sCgm,                                     $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthCodArrecadacao, $iAltura, $oRetorno->sCodigoArrecadacao,                       $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthTipoDebito,     $iAltura, implode("/", $oRetorno->aTipo),                      $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthConvenio,       $iAltura, $oRetorno->sConvenio,                                $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthEmissao,        $iAltura, $oRetorno->oDataEmissao->getDate(DBDate::DATA_PTBR), $sBorder, 0, "C", $iClr);
        } else {

          $oPdf->Cell($iCellWidthCgm,            $iAltura, null, $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthCodArrecadacao, $iAltura, null, $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthTipoDebito,     $iAltura, null, $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthConvenio,       $iAltura, null, $sBorder, 0, "C", $iClr);
          $oPdf->Cell($iCellWidthEmissao,        $iAltura, null, $sBorder, 0, "C", $iClr);
        }

        $sOcorrencia = $oRetorno->aOcorrencia[($iLinha - 1)];

        if (strlen($sOcorrencia) > 90) {
          $sOcorrencia = substr($sOcorrencia, 0, 90) . "...";
        }

        $oPdf->Cell($iCellWidthOcorrencia, $iAltura, $sOcorrencia, 1, 0, "L", $iClr);

        $oPdf->ln();
      }

      if($iClr == 0){
        $iClr = 1;
      }else{
        $iClr = 0;
      }
    }

    $sPdfPathFile = 'tmp/retorno-cobranca-registrada-'.uniqid().'.pdf';
    $oPdf->Output($sPdfPathFile, false, true);

    return $sPdfPathFile;
  }
}