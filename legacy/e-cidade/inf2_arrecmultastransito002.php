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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));

define("ARQUIVO_MENSAGEM", "financeiro.caixa.inf2_arrecmultastransito002.");

try {

    $oGet = db_utils::postMemory($_GET);

    if ( empty($oGet->dtRepasseInicial) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM . "informe_data_inicial") );
    }

    if ( empty($oGet->dtRepasseFinal) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM . "informe_data_final") );
    }

    $oDataRepasseInicial      = new DBDate($oGet->dtRepasseInicial);
    $oDataRepasseFinal        = new DBDate($oGet->dtRepasseFinal);

    if ($oDataRepasseInicial->getAno() != $oDataRepasseFinal->getAno() ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM . "ano_igual") );
    }

    $oDaoArquivoInfracaoMulta = new cl_arquivoinfracaomulta();
    $aTotaisMultasReceita     = $oDaoArquivoInfracaoMulta->buscarDadosRelatorioConsolidado($oDataRepasseInicial, $oDataRepasseFinal);

    if ( empty($aTotaisMultasReceita) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM . "registros_nao_encontrados") );
    }

    $oPdf = new PDFDocument();
    $oPdf->addHeaderDescription('INFRAÇÃO DE TRANSITO');
    $oPdf->addHeaderDescription('RELATÓRIO CONSOLIDADO POR NÍVEL');
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription('Data de repasse: '.$oGet->dtRepasseInicial." até ".$oGet->dtRepasseFinal);
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->SetAutoPageBreak(false);
    $oPdf->setfillcolor(235);
    $oPdf->setfont('arial', 'b', 7);

    $iAlturalinha     = 4;
    $iLargura         = 192;
    $lPrimeiraPagina  = true;
    $iTotalMultas     = 0;
    $nTotalBruto      = 0;
    $nTotalPrefeitura = 0;
    $nTotalDetran     = 0;
    $nTotalFunset     = 0;


    foreach ($aTotaisMultasReceita as $oMultas) {

        if ( $lPrimeiraPagina || $oPdf->GetY() + 60 > $oPdf->h) {

            $oPdf->AddPage();
            $oPdf->setfont('arial', 'b', 7);


            $oPdf->Cell($iLargura * 0.33, $iAlturalinha, 'RECEITA',       'TB', 0, 'L', 1); // 1
            $oPdf->Cell($iLargura * 0.07, $iAlturalinha, 'QUANT.',        'TB', 0, 'C', 1); // 2
            $oPdf->Cell($iLargura * 0.15, $iAlturalinha, 'BRUTO',         'TB', 0, 'R', 1); // 3
            $oPdf->Cell($iLargura * 0.15, $iAlturalinha, 'DETRAN',        'TB', 0, 'R', 1); // 5
            $oPdf->Cell($iLargura * 0.15, $iAlturalinha, 'FUNSET',        'TB', 0, 'R', 1); // 6
            $oPdf->Cell($iLargura * 0.15, $iAlturalinha, 'PREF.',         'TB', 1, 'R', 1); // 4

            $oPdf->setfont('arial', '', 7);
            $lPrimeiraPagina = false;
        }

        $sReceita = $oMultas->codigo_receita . ' - ' . $oMultas->descricao_receita;

        $oPdf->Cell($iLargura * 0.33, $iAlturalinha, substr($sReceita, 0, 39), 'B', 0, 'L');
        $oPdf->Cell($iLargura * 0.07, $iAlturalinha, $oMultas->total_multas, 'B', 0, 'C');
        $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($oMultas->total_bruto, 2, ',', '.'),      'B', 0, 'R');
        $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($oMultas->total_detran, 2, ',', '.'),     'B', 0, 'R');
        $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($oMultas->total_funset, 2, ',', '.'),     'B', 0, 'R');
        $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($oMultas->total_prefeitura, 2, ',', '.'), 'B', 1, 'R');



        $iTotalMultas     += $oMultas->total_multas;
        $nTotalBruto      += $oMultas->total_bruto;
        $nTotalPrefeitura += $oMultas->total_prefeitura;
        $nTotalDetran     += $oMultas->total_detran;
        $nTotalFunset     += $oMultas->total_funset;
    }

    $oPdf->setfont('arial', 'b', 7);
    $oPdf->Cell($iLargura * 0.33, $iAlturalinha, "TOTAIS:", 'TB', 0, 'R'); // 1
    $oPdf->Cell($iLargura * 0.07, $iAlturalinha, $iTotalMultas,                                         'TB', 0, 'C'); // 2
    $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($nTotalBruto, 2, ',', '.'),              'TB', 0, 'R'); // 3
    $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($nTotalDetran, 2, ',', '.'),             'TB', 0, 'R'); //5
    $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($nTotalFunset, 2, ',', '.'),             'TB', 0, 'R'); // 6
    $oPdf->Cell($iLargura * 0.15, $iAlturalinha, number_format($nTotalPrefeitura, 2, ',', '.'),         'TB', 1, 'R'); //4

    $oPdf->Output();

} catch (Exception $e) {

    $sMensagem = $e->getMessage();
    db_redireciona("db_erros.php?db_erro={$sMensagem}");
}
