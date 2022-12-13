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
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/BusinessException.php"));

define('MODELO_RELATORIO_PPA', 1);
define('MODELO_RELATORIO_LDO', 2);

define('SEM_INDICE_POR_ANO', 'f');
define('COM_INDICE_POR_ANO', 't');

$oGet       = db_utils::postMemory($_GET);
$iAnoSessao = db_getsession("DB_anousu");

$aTipoPrograma = array();
$aTipoPrograma[0] = "Todos";
$aTipoPrograma[3] = "Programas Temáticos";
$aTipoPrograma[4] = "Programas de Gestão, Manutenção e Serviços";
$iModeloRelatorio = $oGet->iModeloRelatorio;
$imprimirIndices  = $oGet->imprimirIndices;

/**
 *  Seta as propriedades do pdf
 */
$oPdf = new PDF("P");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 10);
$oPdf->setfillcolor(235);

$sWhere        = "     o01_sequencial  = {$oGet->siLei} ";
$sWhere       .= " and o119_sequencial = {$oGet->iVersao} " ;
$oDaoPpaVersao = new cl_ppaversao();
$sSqlPeriodo   = $oDaoPpaVersao->sql_query(null, " o01_anoinicio, o01_anofinal", null, $sWhere);
$rsPeriodoPPA  = $oDaoPpaVersao->sql_record($sSqlPeriodo);
$oPeriodoPPA   = db_utils::fieldsMemory($rsPeriodoPPA, 0);


$oPPA = new ppa($oGet->siLei, 2, $oGet->iVersao);

/**
 * Inicia Impressão do PDF
 */
$oStdDadosInstituicao = db_stdClass::getDadosInstit(db_getsession('DB_instit'));

$iAnoPrograma = null;

switch ($iModeloRelatorio) {

	case MODELO_RELATORIO_PPA :

    $head1 =  $oStdDadosInstituicao->nomeinst;
    $head2 =  "Plano Plurianual";
    $head3 = "Lei do PPA: {$oPPA->oObjeto->oDadosLei->o01_sequencial} - {$oPPA->oObjeto->oDadosLei->o01_descricao}";
    $head4 = "Data ínicio: " . db_formatar($oPPA->oObjeto->oDadosLei->o119_datainicio, "d");
    $head5 = "Tipo de Programa: {$aTipoPrograma[$oGet->iTipo]}";

	break;

	case MODELO_RELATORIO_LDO :

		$head1 =  $oStdDadosInstituicao->nomeinst;
		$head2 = "Lei de Diretrizes Orçamentárias";
		$head3 = "Exercício de: {$iAnoSessao}";
		$head4 = "Lei do PPA: {$oPPA->oObjeto->oDadosLei->o01_sequencial} - {$oPPA->oObjeto->oDadosLei->o01_descricao}";
		$head5 = "Data ínicio: " . db_formatar($oPPA->oObjeto->oDadosLei->o119_datainicio, "d");
		$head6 = "Tipo de Programa: {$aTipoPrograma[$oGet->iTipo]}";

	break;

}

$iAlturaLinha  = 5;
$iTamanhoFonte = 6;

try {

  $aCodigoProgramas = explode(",", $oGet->sProgramas);

  if (trim($oGet->sProgramas) == "") {

    $aWherePrograma  = array();
    $aWherePrograma[] = "o08_ppaversao = {$oGet->iVersao}";
    /**
     * Alterado lógica para filtrar pelo competencia do ppa
     */
    $aWherePrograma[] = "o08_ano between {$oPeriodoPPA->o01_anoinicio} and {$oPeriodoPPA->o01_anofinal}";

    if ($oGet->iTipo != "0") {
      $aWherePrograma[] = "o54_tipoprograma = {$oGet->iTipo}";
    }
    $sWherePrograma    = implode(" and ", $aWherePrograma);
    $oDAOPPADotacao    = db_utils::getDao("ppadotacao");
    $sCamposPrograma   = "array_to_string(array_accum(distinct o08_programa)::integer[], ', ') as lista_programa";
    $sSQLBuscaPrograma = $oDAOPPADotacao->sql_query_despesa_programa(null,  $sCamposPrograma, null, $sWherePrograma);
    $rsBuscaPrograma   = $oDAOPPADotacao->sql_record($sSQLBuscaPrograma);
    if ($oDAOPPADotacao->erro_status == "0") {
      throw new BusinessException("Nenhum programa localizado para o filtro selecionado.");
    }

    $sCodigosProgramas = db_utils::fieldsMemory($rsBuscaPrograma, 0)->lista_programa;
    $aCodigoProgramas = explode(', ', $sCodigosProgramas);
  }

  /**
   * Inicia a impressão do relatório
   */
  foreach ($aCodigoProgramas as $iCodigoPrograma) {

    $oPrograma = new Programa($iCodigoPrograma, $iAnoSessao);
    $aValoresPrograma = Programa::getValorGlobalEstimadoPPAPorAno($oPrograma->getCodigoPrograma(), $iAnoSessao, $oGet->iVersao);

    $lPrimeiro   = true;
    $nValorTotal = 0;
    $lImprime    = false;

    foreach($aValoresPrograma as $iValor) {

      if ($iValor > 0) {
        $lImprime         = true;
    	}
    }

    if (!$lImprime) {
    	continue;
    }

    $aPeriodoPrograma    = array_keys($aValoresPrograma);
    $iAnoInicialPrograma = min($aPeriodoPrograma);
    $iAnoFinalPrograma   = max($aPeriodoPrograma);
    $sPeriodoPrograma    = "($iAnoInicialPrograma/$iAnoFinalPrograma)";

    $oPdf->AddPage();

    imprimirCabecalhoPrograma($oPdf, $iTamanhoFonte, $iTamanhoFonte);

    $oPdf->SetFont('arial', '', $iTamanhoFonte);

    foreach ($aValoresPrograma as $iIndicePrograma => $nValorPrograma) {

      if ($lPrimeiro) {

        $oPdf->Cell(30,  $iAlturaLinha, $oPrograma->getCodigoPrograma(),           "TR",  0, "C");
        $oPdf->Cell(110, $iAlturaLinha, substr($oPrograma->getDescricao(), 0, 70), "TR",  0, "L");
        $lPrimeiro = false;

      } else {

        $oPdf->Cell(30,  $iAlturaLinha, "", "R", 0, "C");
        $oPdf->Cell(110, $iAlturaLinha, "", "R",  0, "L");
      }
      $oPdf->Cell(25, $iAlturaLinha, $iIndicePrograma,                  "L", 0, "C");
      $oPdf->Cell(25, $iAlturaLinha, db_formatar($nValorPrograma, "f"), "L", 1, "R");

      $nValorTotal += $nValorPrograma;

      if ( $iModeloRelatorio == MODELO_RELATORIO_LDO ) {
      	break;
      }
    }

    $oPdf->Cell(30,  $iAlturaLinha, "", "R",  0, "C");
    $oPdf->Cell(110, $iAlturaLinha, "", "R",  0, "L");

    $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
    $oPdf->Cell(25,  $iAlturaLinha, "Total:",                       "T", 0, "R");
    $oPdf->Cell(25,  $iAlturaLinha, db_formatar($nValorTotal, "f"), "T", 1, "R");

    imprimirCabecalhoIndicadores($oPdf, $iAlturaLinha, $iTamanhoFonte);

    /** Lista de Indicadores do Programa */
    $aIndicadores = Programa::getDadosIndicadores($oPrograma->getCodigoPrograma(), $iAnoSessao);

    foreach ($aIndicadores as $oStdIndicador) {

      if($oPdf->gety() > $oPdf->h-35) {
        imprimirCabecalhoIndicadores($oPdf, $iAlturaLinha, $iTamanhoFonte);
      }
      $oPdf->SetFont('arial', '', $iTamanhoFonte);
      $oPdf->Cell(100, $iAlturaLinha, substr($oStdIndicador->s_descricao, 0, 80), "TR", 0, "L");
      $oPdf->Cell(30,  $iAlturaLinha, substr($oStdIndicador->s_unidade, 0, 23),   "TR", 0, "L");
      $oPdf->Cell(20,  $iAlturaLinha, $oStdIndicador->i_ano,                      "TR", 0, "C");
      $oPdf->Cell(40,  $iAlturaLinha, $oStdIndicador->n_valor,                    "T",  1, "R");
    }

    $aObjetivos = $oPrograma->getObjetivos();

    /**
     * Objetivos do Programa
     */
    imprimirCabecalhoObjetivos($oPdf, $iAlturaLinha, $iTamanhoFonte);

    /**
     * Lista de Objetivos do Programa
     */
    foreach ($aObjetivos as $oObjetivo) {

      /**
       * Exibe somente ano do primeiro programa
       */
      if ( $iModeloRelatorio == MODELO_RELATORIO_LDO ) {
        $sPeriodoPrograma = "($iAnoInicialPrograma)";
      }

      imprimirSegundoCabecalhoObjetivos($oPdf, $iAlturaLinha, $iTamanhoFonte, $oObjetivo->getCodigoSequencial());

      /**
       * Dados do Objetivo
       */
      $oPdf->SetFont('arial', '', $iTamanhoFonte);
      $oPdf->Cell(30,  $iAlturaLinha, $oObjetivo->getCodigoSequencial(),          "TBR", 0, "C");
      $oPdf->Cell(160, $iAlturaLinha, substr($oObjetivo->getDescricao(), 0, 125), "TB",  1, "L");
      $oPdf->MultiCell(190, $iAlturaLinha, $oObjetivo->getObjetivo(), "TB", "L");

      /**
       * Cabeçalho do Órgão do objetivo
       */
      imprimirCabecalhoOrgao($oPdf, $iAlturaLinha, $iTamanhoFonte);

      /**
       * Dados do Órgão
       */
      $oOrgao = $oObjetivo->getOrgao();
      $oPdf->SetFont('arial', '', $iTamanhoFonte);
      $oPdf->Cell(30,  $iAlturaLinha, $oOrgao->getCodigoOrgao(), "TBR", 0, "C");
      $oPdf->Cell(160, $iAlturaLinha, $oOrgao->getDescricao(), "TB",  1, "L");

      /**
       * Cabeçalho de Metas do Objetivo
       */
      imprimirCabecalhoMetas($oPdf, $iAlturaLinha, $iTamanhoFonte, $sPeriodoPrograma);

      $aMetas       = $oObjetivo->getMetas();
      $aIniciativas = array();

      /**
       * Percorre as metas do objetivo
       */
      foreach ($aMetas as $oMeta) {

        if($oPdf->gety() > $oPdf->h-35) {
          imprimirCabecalhoMetas($oPdf, $iAlturaLinha, $iTamanhoFonte, $sPeriodoPrograma);
        }

        $meta = $oMeta->getMeta();

        if($imprimirIndices == COM_INDICE_POR_ANO) {

          $indice = $oMeta->getIndiceNoAno($iAnoInicialPrograma);

          /**
           * Se houver índice para o ano do programa, valida os seguintes pontos:
           * 1 - Se o menor e o maior ano do mesmo índice são iguais, imprimindo apenas uma vez o ano. Caso contrário,
           *     fica entre períodos
           * 2 - Se for o modelo LDO, não apresenta o ano e o índice é somente para aquele ano, ao invés da soma de todos
           *     os anos lançados
           */
          if($indice) {

            $meta  = "{$oMeta->getAnoMinimoIndice()} até {$oMeta->getAnoMaximoIndice()}";

            if($oMeta->getAnoMinimoIndice() == $oMeta->getAnoMaximoIndice()) {
              $meta = $oMeta->getAnoMinimoIndice();
            }

            $meta .= ": {$oMeta->getMeta()} - Índice: {$oMeta->getIndicesSomados()} {$indice->getUnidadeMedida()}";

            if($iModeloRelatorio == MODELO_RELATORIO_LDO) {
              $meta = "{$oMeta->getMeta()} - Índice: {$indice->getIndice()} {$indice->getUnidadeMedida()}";
            }
          }
        }

        $oPdf->SetFont('arial', '', $iTamanhoFonte);
        $oPdf->MultiCell(190, $iAlturaLinha, $meta, "TB", "L");
        $aIniciativas = array_merge($aIniciativas, $oMeta->getIniciativas());
      }

      /**
       * Quando apresentar o ano, reordena as iniciativas pelo ano
       */
      if($imprimirIndices == COM_INDICE_POR_ANO) {

        usort($aIniciativas, function($objeto1, $objeto2) {
          return strcmp($objeto1->getAno(), $objeto2->getAno());
        });
      }

      /**
       * Cabeçalho de Iniciativas do Objetivo
       */
      imprimirCabecalhoIniciativas($oPdf, $iAlturaLinha, $iTamanhoFonte, $sPeriodoPrograma);

      foreach ($aIniciativas as $oIniciativa) {

        $iniciativa = $oIniciativa->getIniciativa();

        /**
         * Modelo do relatorio LDO
         * Imprime somente se a iniciativa for para o ano impresso referente ao programa
         */
        if (    $iModeloRelatorio == MODELO_RELATORIO_LDO
             && !DBNumber::overlaps($iAnoInicialPrograma, $oIniciativa->getAno(), $oIniciativa->getAnoFinal())) {
          continue;
        }

        if($oPdf->gety() > $oPdf->h-35) {
          imprimirCabecalhoIniciativas($oPdf, $iAlturaLinha, $iTamanhoFonte, $sPeriodoPrograma);
        }

        /**
         * Quando o modelo for PPA e selecionada impressão com ano, incrementa os dados do período cadastrado
         */
        if($iModeloRelatorio == MODELO_RELATORIO_PPA && $imprimirIndices == COM_INDICE_POR_ANO) {

          if($oIniciativa->getAno() != null && $oIniciativa->getAnoFinal() != null) {

            $iniciativa = "{$oIniciativa->getAno()} até {$oIniciativa->getAnoFinal()}";

            if($oIniciativa->getAno() == $oIniciativa->getAnoFinal()) {
              $iniciativa = $oIniciativa->getAno();
            }

            $iniciativa .= ": {$oIniciativa->getIniciativa()}";
          }
        }

        $oPdf->SetFont('arial', '', $iTamanhoFonte);
        $oPdf->MultiCell(190, 4, $iniciativa, "TB", "L");
      }
    }
  }

  $oPdf->Output();

} catch (Exception $oException) {
  db_redireciona("db_erros.php?fechar=true&db_erro=[1] - {$oException->getMessage()}");
}

function imprimirCabecalhoOrgao(&$oPdf, $iAlturaLinha, $iTamanhoFonte) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(190, $iAlturaLinha, "1.3.1 Órgão responsável pelo objetivo:", "TB",  1, "L", 1);
  $oPdf->Cell(30,  $iAlturaLinha, "Código",                                 "TBR", 0, "C", 1);
  $oPdf->Cell(160, $iAlturaLinha, "Descrição",                              "TLB", 1, "L", 1);
}

function imprimirCabecalhoIniciativas(&$oPdf, $iAlturaLinha, $iTamanhoFonte, $sPeriodo = null) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }
  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(190, $iAlturaLinha, "1.3.2 Iniciativas Vinculadas às Metas{$sPeriodo}:", "TB", 1, "L", 1);
}

function imprimirCabecalhoPrograma(&$oPdf, $iAlturaLinha, $iTamanhoFonte) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }

  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(140, $iAlturaLinha, "1 Descrição do Programa", "TBR", 0, "C", 1);
  $oPdf->Cell(50,  $iAlturaLinha, "",                        "LT",  1, "C", 1);

  $oPdf->Cell(30,  $iAlturaLinha, "Código",                       "TBR", 0, "C", 1);
  $oPdf->Cell(110, $iAlturaLinha, "Título",                       "TBR", 0, "C", 1);
  $oPdf->Cell(50,  $iAlturaLinha, "1.1 Valor Global do Programa", "LB",  1, "C", 1);
}

function imprimirCabecalhoIndicadores(&$oPdf, $iAlturaLinha, $iTamanhoFonte) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }
  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(190, $iAlturaLinha, "1.2 Indicadores vinculados ao Programa", "TB", 1, "L", 1);

  $oPdf->Cell(100, $iAlturaLinha, "",           "TR",  0, "C", 1);
  $oPdf->Cell(30,  $iAlturaLinha, "Unidade de", "TLR", 0, "C", 1);
  $oPdf->Cell(60,  $iAlturaLinha, "Referência", "TLB", 1, "C", 1);

  $oPdf->Cell(100, $iAlturaLinha, "Descrição", "BR",   0, "C", 1);
  $oPdf->Cell(30,  $iAlturaLinha, "Medida",    "BLR",  0, "C", 1);
  $oPdf->Cell(20,  $iAlturaLinha, "Ano",       "TBLR", 0, "C", 1);
  $oPdf->Cell(40,  $iAlturaLinha, "Índice",    "TLB",  1, "C", 1);
}

function imprimirCabecalhoMetas(&$oPdf, $iAlturaLinha, $iTamanhoFonte, $sPeriodo = null) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }
  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(190, $iAlturaLinha, "1.3.2 Metas Vinculadas aos Objetivos{$sPeriodo}:", "TB", 1, "L", 1);
}

function imprimirCabecalhoObjetivos(&$oPdf, $iAlturaLinha, $iTamanhoFonte) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }
  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(190, $iAlturaLinha, "1.3 Objetivos do Programa", "TB", 1, "L", 1);

}

function imprimirSegundoCabecalhoObjetivos(&$oPdf, $iAlturaLinha, $iTamanhoFonte, $iCodigoObjetivo) {

  if($oPdf->gety() > $oPdf->h-35) {
    imprimirContinuacaoPagina($oPdf, $iAlturaLinha);
  }
  /** Cabeçalho do Objetivo */
  $oPdf->SetFont('arial', 'b', $iTamanhoFonte);
  $oPdf->Cell(190, $iAlturaLinha, "OBJETIVO {$iCodigoObjetivo}:", "TB",  1, "L", 1);
  $oPdf->Cell(30,  $iAlturaLinha, "Código",                       "TBR", 0, "L", 1);
  $oPdf->Cell(160, $iAlturaLinha, "Descrição",                    "TLB", 1, "L", 1);
}

function imprimirContinuacaoPagina($oPdf, $iAlturaLinha) {

  //$oPdf->cell(190, $iAlturaLinha, 'Continua na Página ' . ($oPdf->pageNo() + 1)."/{nb}", "T", 1, "R", 0);
  $oPdf->addpage();
  //$oPdf->ln(2);
  //$oPdf->cell(190, $iAlturaLinha, 'Continuação ' . ($oPdf->pageNo() - 1) . "/{nb}", "B", 1, "R", 0);
}