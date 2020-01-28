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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("std/DBArray.php");
require_once("model/pessoal/relatorios/resumoFolha.model.php");
require_once("model/pessoal/ServidorRepository.model.php");
require_once("libs/JSON.php");

$oJson        = new services_json();
$oGet         = db_utils::postMemory($HTTP_GET_VARS);
$iInstituicao = db_getsession("DB_instit");
$oParametros  = $oJson->decode(str_replace("\\","",$oGet->json));

try {

  /**
   * constantes para o Relatorio
   */
  define( "TIPO_RELATORIO_GERAL"              , "0" );
  define( "TIPO_RELATORIO_ORGAO"              , "1" );
  define( "TIPO_RELATORIO_LOTACAO"            , "2" );
  define( "TIPO_RELATORIO_MATRICULA"          , "3" );
  define( "TIPO_RELATORIO_LOCAIS_TRABALHO"    , "4" );
  define( "TIPO_RELATORIO_CARGO"              , "5" );
  define( "TIPO_RELATORIO_RECURSO"            , "6" );

  define( "TIPO_FILTRO_GERAL"                 , 0 );
  define( "TIPO_FILTRO_INTERVALO"             , 1 );
  define( "TIPO_FILTRO_SELECIONADOS"          , 2 );

  define( "TIPO_VINCULO_GERAL"                , "g" );
  define( "TIPO_VINCULO_ATIVOS"               , "a" );
  define( "TIPO_VINCULO_INATIVOS"             , "i" );
  define( "TIPO_VINCULO_PENSIONISTAS"         , "p" );
  define( "TIPO_VINCULO_INATIVOS_PENSIONISTAS", "ip");

  define( "TIPO_PREVIDENCIA_SEM_PREVIDENCIA"  , 5   );

  define( "ORDENACAO_RELATORIO_NUMERICA"      , "n" );
  define( "ORDENACAO_RELATORIO_ALFABETICA"    , "a" );

  $aWhere            = array();
  $sDescricaoSelecao = '';

  /**
   * Valida se existe seleção
   */
  if ( !empty($oParametros->iSelecao) ) {

    $sSelecao = trim( db_utils::getDao( "selecao" )->getCondicaoSelecao( $oParametros->iSelecao ) );

    if ( !empty($sSelecao) ) {
      $aWhere['selecao'] = $sSelecao;

      $sDescricaoSelecao = trim( db_utils::getDao( "selecao" )->getDescricaoSelecao( $oParametros->iSelecao ) );
      $sDescricaoSelecao = "\nSELEÇÃO : " . $sDescricaoSelecao;
    }
  }

  /**
   * Valida se existe Regime
   */
  if ( !empty($oParametros->iRegime) ) {

    $aWhere['regime']  = "rh30_regime = {$oParametros->iRegime}";
  }

  $head3 = 'TIPO FILTRO : ';

  switch ( $oParametros->iTipoRelatorio ) {

    default:

      $sLabelTipoRelatorio           = "Geral";
      $sCampoCondicaoTipoRelatorio   = 1;
      $sCampoEstruturalTipoRelatorio = 1;
      $sCampoDescricaoTipoRelatorio  = "'GERAL'";
      $head3                        .= 'GERAL';

    break;

    case TIPO_RELATORIO_CARGO:

      $sLabelTipoRelatorio           = "Cargos:";
      $sCampoCondicaoTipoRelatorio   = "rh37_funcao";
      $sCampoEstruturalTipoRelatorio = "rh37_funcao";
      $sCampoDescricaoTipoRelatorio  = "rh37_descr";
      $head3                        .= 'CARGOS';

    break;

    case TIPO_RELATORIO_LOTACAO:

      $sLabelTipoRelatorio             = "Lotações:";
      $sCampoCondicaoTipoRelatorio     = "r70_codigo";
      $sCampoEstruturalTipoRelatorio   = "r70_estrut";
      $sCampoDescricaoTipoRelatorio    = "r70_descr";
      $head3                          .= 'LOTAÇÕES';

    break;

    case TIPO_RELATORIO_ORGAO:

      $sLabelTipoRelatorio          = "Órgãos:";
      $sCampoCondicaoTipoRelatorio  = "rh26_orgao";
      $sCampoEstruturalTipoRelatorio= "rh26_orgao";
      $sCampoDescricaoTipoRelatorio = "o40_descr";
      $head3                       .= 'ÓRGÃOS';

    break;

    case TIPO_RELATORIO_LOCAIS_TRABALHO:

      $sLabelTipoRelatorio          = "Locais de Trabalho:";
      $sCampoCondicaoTipoRelatorio  = "rh55_codigo";
      $sCampoEstruturalTipoRelatorio= "rh55_estrut";
      $sCampoDescricaoTipoRelatorio = "rh55_descr";
      $head3                       .= 'LOCAIS DE TRABALHO';

    break;

    case TIPO_RELATORIO_MATRICULA:

      $sLabelTipoRelatorio          = "Matrículas:";
      $sCampoCondicaoTipoRelatorio  = "rh02_regist";
      $sCampoEstruturalTipoRelatorio= "rh02_regist";
      $sCampoDescricaoTipoRelatorio = "z01_nome";
      $head3                       .= 'MATRÍCULAS';

    break;

    case TIPO_RELATORIO_RECURSO:

      $sLabelTipoRelatorio          = "Recursos:";
      $sCampoCondicaoTipoRelatorio  = "o15_codigo";
      $sCampoEstruturalTipoRelatorio= "o15_codigo";
      $sCampoDescricaoTipoRelatorio = "o15_descr";
      $head3                       .= 'RECURSOS';

    break;
  }

  if ( $oParametros->iTipoRelatorio <> TIPO_RELATORIO_GERAL ) {

    switch ( $oParametros->iTipoFiltro ) {

      case TIPO_FILTRO_GERAL:
        //Sem Filtros
      break;
      case TIPO_FILTRO_INTERVALO:
        $aWhere['tipo_filtro' . $oParametros->iTipoRelatorio] = "{$sCampoCondicaoTipoRelatorio} between $oParametros->iIntervaloInicial and $oParametros->iIntervaloFinal";
      break;
      case TIPO_FILTRO_SELECIONADOS:
        $aWhere['tipo_filtro' . $oParametros->iTipoRelatorio] = "{$sCampoCondicaoTipoRelatorio} in (" . implode(", ", $oParametros->iRegistros) . ")";
      break;
    }
  }

  /**
   * Definições Sobre Vinculo
   */
  if ( !empty($oParametros->sVinculo) ) {

    switch ( $oParametros->sVinculo ) {

      default:
        $sTituloVinculo   = "GERAL";
        $sCondicaoVinculo = null;
      break;

      case TIPO_VINCULO_ATIVOS:
        $sTituloVinculo   = "ATIVOS";
        $sCondicaoVinculo = " rh30_vinculo = 'A' ";
      break;

      case TIPO_VINCULO_INATIVOS:
        $sTituloVinculo   = "INATIVOS";
        $sCondicaoVinculo = " rh30_vinculo = 'I' ";
      break;

      case TIPO_VINCULO_PENSIONISTAS:
        $sTituloVinculo   = "PENSIONISTAS";
        $sCondicaoVinculo = " rh30_vinculo = 'P' ";
      break;

      case TIPO_VINCULO_INATIVOS_PENSIONISTAS:
        $sTituloVinculo   = "INATIVOS / PENSIONISTAS";
        $sCondicaoVinculo = " rh30_vinculo in ('I','P') ";
      break;
    }

    if (!empty($sCondicaoVinculo) ) {
      $aWhere['vinculo'] = $sCondicaoVinculo;
    }
  }

  /**
   * Validando Previdencia
   */
  if ( !empty($oParametros->iPrevidencia) ) {

    $aWhere['previdencia'] = "rh02_tbprev = {$oParametros->iPrevidencia}";

    if ( $oParametros->iPrevidencia != TIPO_PREVIDENCIA_SEM_PREVIDENCIA ) {
      $head4 = "PREVIDÊNCIA : {$oParametros->sPrevidencia}";
    } else {
      $head4 = "PREVIDÊNCIA : FUNCIONÁRIOS SEM PREVIDÊNCIA";
    }
  } else {
    $head4 = "PREVIDÊNCIA : 0 - Todos";
  }

  if( !empty($sDescricaoSelecao) ){
      $head3 .= $sDescricaoSelecao;
  }

  $oDaoRhPessoalMov = db_utils::getDao("rhpessoalmov");
  $iInstituicao     = db_getsession('DB_instit');
  $sWhere           = implode(' and ', $aWhere);
  $sCampos          = "distinct rh01_regist,                                     ";
  $sCampos         .= "{$sCampoCondicaoTipoRelatorio}   as agrupador_codigo,     ";
  $sCampos         .= "{$sCampoEstruturalTipoRelatorio} as agrupador_estrutural, ";
  $sCampos         .= "{$sCampoDescricaoTipoRelatorio}  as agrupador_descricao   ";
  $sAgrupamento     = "rh01_regist, {$sCampoCondicaoTipoRelatorio}, {$sCampoDescricaoTipoRelatorio}, {$sCampoEstruturalTipoRelatorio}";
  $sAgrupamento     = $oParametros->iTipoRelatorio == TIPO_RELATORIO_GERAL ? "" : $sAgrupamento;

  $sSqlServidores   = $oDaoRhPessoalMov->sql_query_baseServidores($oParametros->iMes,
                                                                  $oParametros->iAno,
                                                                  $iInstituicao,
                                                                  $sCampos,
                                                                  $sWhere,
                                                                  "agrupador_codigo",
                                                                  $sAgrupamento);
  $rsServidores = db_query($sSqlServidores);

  if ( !$rsServidores ) {
    throw new DBException( "Erro ao Buscar os Servidores pelos filtros selecionados. \n" . pg_last_error() );
  }

  if ( pg_num_rows( $rsServidores ) == 0 ) {
    throw new BusinessException("Nenhum Servidor encontrado nos Filtros Selecionados");
  }

  $aDadosRelatorios   = array();
  $aGrupos            = array();
  $aRubricas          = array();
  $aTotalServidores   = array();
  $aRubricasOrdenacao = array();
  $lExisteCalculo     = false;
  /**
   * Agrupa servidores pelo tipo de relatório
   */
  foreach ( db_utils::getCollectionByRecord($rsServidores) as $oDadosPesquisados  ) {

    $agrupador = $oDadosPesquisados->agrupador_estrutural . '-' . $oDadosPesquisados->agrupador_codigo;
    if ( $agrupador == "-" ) {
      continue;
    }

    $oServidor = ServidorRepository::getInstanciaByCodigo( $oDadosPesquisados->rh01_regist, $oParametros->iAno, $oParametros->iMes);
    /**
     * descricao do agrupador
     */
    $aGrupos[$agrupador] = $oDadosPesquisados->agrupador_descricao;

    /**
     * Rubricas
     */
    foreach ($oParametros->aTiposFolhas as $sTipoFolha) {

      $oCalculo = $oServidor->getCalculoFinanceiro($sTipoFolha);
      switch ($sTipoFolha) {
        case CalculoFolha::CALCULO_COMPLEMENTAR:

          if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
            $aEventoFinanceiro = $oCalculo->getEventosFinanceirosHistorico(
              FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR,
              $oParametros->iComplementar
            );
          } else {
            $aEventoFinanceiro = $oCalculo->getEventosFinanceiros($oParametros->iComplementar);
          }
          break;

        case CalculoFolha::CALCULO_SUPLEMENTAR:

          $aEventoFinanceiro = $oCalculo->getEventosFinanceirosHistorico(
            FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR,
            $oParametros->iSuplementar
          );
          break;

        case CalculoFolha::CALCULO_SALARIO:

          if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
            $aEventoFinanceiro = $oCalculo->getEventosFinanceirosHistorico(FolhaPagamento::TIPO_FOLHA_SALARIO);
          } else {
            $aEventoFinanceiro = $oCalculo->getEventosFinanceiros();
          }
          break;
        
        default:

          $aEventoFinanceiro = $oCalculo->getEventosFinanceiros();
          break;
      }

      foreach($aEventoFinanceiro as $oEventoFinanceiro) {


        $lExisteCalculo    = true; // Exibe Relatório se existir movimentação financeira
        $sRubrica          = $oEventoFinanceiro->getRubrica()->getCodigo();
        $sDescricaoRubrica = $oEventoFinanceiro->getRubrica()->getDescricao();
        $iMatricula        = $oDadosPesquisados->rh01_regist;

        $aRubricasOrdenacao        [$agrupador][$sRubrica]              = $sDescricaoRubrica;
        $aTotalServidoresRubrica   [$agrupador][$sRubrica][$iMatricula] = $iMatricula;
        $aTotalServidoresEstrutural[$agrupador][$iMatricula]            = $iMatricula;
        $aRubricas                 [$agrupador][$sRubrica][]            = $oEventoFinanceiro;
      }
    }
  }


  /**
   * Percorre o array de rubricas para ordenar as rubricas conforme filtro
   * numerico ou alfabetica
   */
  foreach ($aRubricasOrdenacao as $sCodigoOrdenacao => $aRubricasGrupo) {

    if ( $oParametros->sOrdem == ORDENACAO_RELATORIO_NUMERICA ) {
      DBArray::keyNatSort($aRubricasOrdenacao[$sCodigoOrdenacao]);
    }

    if ( $oParametros->sOrdem == ORDENACAO_RELATORIO_ALFABETICA ) {
      natcasesort($aRubricasOrdenacao[$sCodigoOrdenacao]);
    }
  }

  if ( !$lExisteCalculo ) {
    throw new BusinessException("Não Existe Cálculo para a Competência Selecionada.");
  }

  $oDaoInssirf     = db_utils::getDao('inssirf');
  $oDadosPatronais = $oDaoInssirf->getPercentuaisPatronais($oParametros->iAno, $oParametros->iMes, db_getsession('DB_instit'));
  $sTipoFolhas     = count($oParametros->aTiposFolhas) > 1 ? 'VÁRIOS' : nomeFolhaAtual($oParametros->aTiposFolhas[0]);

  $head1 = "RESUMO DA FOLHA DE PAGAMENTO ";
  $head5 = "PERÍODO : {$oParametros->iMes} / {$oParametros->iAno}";
  $head6 = "VINCULO : {$sTituloVinculo}";
  $head7 = "TIPO FOLHA : {$sTipoFolhas}";

  // Verifica se foi selecionado apenas um tipo de folha
  if (count($oParametros->aTiposFolhas) == 1) {

    // Verifica se foi selecionado um número de complementar
    if (isset($oParametros->iComplementar)) {

      $sNumeroSelecionado = (!empty($oParametros->iComplementar)) ? $oParametros->iComplementar : 'Todos';
      $head8              = "NÚMERO: {$sNumeroSelecionado}";
    } else if (isset($oParametros->iSuplementar)) { // Verifica se foi selecionado um número de suplementar

      $sNumeroSelecionado = (!empty($oParametros->iSuplementar)) ? $oParametros->iSuplementar : 'Todos';
      $head8              = "NÚMERO: {$sNumeroSelecionado}";
    }
  }

  /**
   * Configurações do PDF
   */
  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $oPdf->setfillcolor(235);

  /**
   * Altura da célula
   */
  $iAlt = 4;

  $oPdf->setfont('arial','b',8);

  /**
   * Percorre array com os dados do filtro
   */
  foreach ( $aRubricas as $sEstruturalFiltro => $aRubricas ) {

    $oTotais                         = new stdClass();
    $oTotais->nValorProventos        = 0;
    $oTotais->nValorDescontos        = 0;
    $oTotais->nValorLiquido          = 0;
    $oTotais->nTotalBasePrevidencia  = 0;
    $oTotais->nValorBasePrevidencia1 = 0;
    $oTotais->nValorBasePrevidencia2 = 0;
    $oTotais->nValorBasePrevidencia3 = 0;
    $oTotais->nValorBasePrevidencia4 = 0;
    $oTotais->nValorBaseFGTS         = 0;
    $oTotais->nValorFGTS             = 0;
    $oTotais->nValorBaseIRRF         = 0;
    $oTotais->nValorEmpenhos         = 0;
    $oTotais->nValorPontoExtra       = 0;
    $oTotais->nValorRetencao         = 0;
    $oTotais->nValorDeducao          = 0;
    $oTotais->nValorDiferenca        = 0;
    $oTotais->iTotalFuncionarios     = 0;

    $oTamanhoColunas = new stdClass();
    $oTamanhoColunas->rubrica        = 15;
    $oTamanhoColunas->funcionarios   = 15;
    $oTamanhoColunas->quantidade     = 15;
    $oTamanhoColunas->descricao      = 107;
    $oTamanhoColunas->proventos      = 20;
    $oTamanhoColunas->descontos      = 20;
    $oTamanhoColunas->total          = $oTamanhoColunas->rubrica;
    $oTamanhoColunas->total         += $oTamanhoColunas->funcionarios;
    $oTamanhoColunas->total         += $oTamanhoColunas->quantidade;
    $oTamanhoColunas->total         += $oTamanhoColunas->descricao;
    $oTamanhoColunas->total         += $oTamanhoColunas->proventos;
    $oTamanhoColunas->total         += $oTamanhoColunas->descontos;

    $nValorTotalProventos = 0;
    $nValorTotalDescontos = 0;

    $oPdf->addpage();

    $oPdf->setfont('arial','b',8);
    $oPdf->cell($oTamanhoColunas->total       ,$iAlt,"{$sEstruturalFiltro} - ".strtoupper($aGrupos[$sEstruturalFiltro]), 1, 1, "L", 1);
    $oPdf->cell($oTamanhoColunas->rubrica     ,$iAlt,'RUBRICA'  ,1,0,"C",1);
    $oPdf->cell($oTamanhoColunas->funcionarios,$iAlt,'N.FUNC.'  ,1,0,"C",1);
    $oPdf->cell($oTamanhoColunas->quantidade  ,$iAlt,'QUANT.'   ,1,0,"C",1);
    $oPdf->cell($oTamanhoColunas->descricao   ,$iAlt,'DESCRIÇÃO',1,0,"C",1);
    $oPdf->cell($oTamanhoColunas->proventos   ,$iAlt,'PROVENTOS',1,0,"C",1);
    $oPdf->cell($oTamanhoColunas->descontos   ,$iAlt,'DESCONTOS',1,1,"C",1);

    foreach ( $aRubricasOrdenacao[$sEstruturalFiltro] as $sCodigoRubrica => $sDescricaoRubrica ) {

      $aEventosFinanceiros = $aRubricas[ $sCodigoRubrica ];

      $oPdf->setfont('arial','',8);
      $oRubrica = RubricaRepository::getInstanciaByCodigo($sCodigoRubrica);

      $oTotalEventosRubricas                       = new stdClass();
      $oTotalEventosRubricas->nValorProventos      = 0;
      $oTotalEventosRubricas->nValorDescontos      = 0;
      $oTotalEventosRubricas->nQuantidadeProventos = 0;
      $oTotalEventosRubricas->nQuantidadeDescontos = 0;
      $oTotalEventosRubricas->sDescricaoRubrica    = $oRubrica->getDescricao();

      foreach ( $aEventosFinanceiros as $oEventoFinanceiro ) {

        switch ( $oEventoFinanceiro->getNatureza() ) {

          default:
            continue;
          break;

          case EventoFinanceiroFolha::BASE:

            switch ( $oRubrica->getCodigo() ) {

              case 'R981'://Valor Base do IRRF
                $oTotais->nValorBaseIRRF += $oEventoFinanceiro->getValor();
              break;

              case 'R991'://Valor Base do FGTS
                $oTotais->nValorBaseFGTS += $oEventoFinanceiro->getValor();
              break;

              /**
               * Caso for rubrica de base de previdencia
               *  Soma ao total das previdencias
               *  e as Separa por tabela.
               */
              case 'R992' :

                $oTotais->nTotalBasePrevidencia += $oEventoFinanceiro->getValor();

                /**
                 * Valida a Tabela de Previdencia do Servidor na Competencia.
                 * E Soma os Valores em Cada uma
                 * OBS.: A lógica dos valores abaixos são a mesma da rotina de geração de empenhos. Segue a lógica:
                 *       Multiplica cada valor do evento fincanceiro pelo percentual da base respectivo e após arredonda o valor.
                 */
                switch ( $oEventoFinanceiro->getServidor()->getTabelaPrevidencia() ) {

                  case '1' :
                    $oTotais->nValorBasePrevidencia1 += round(($oEventoFinanceiro->getValor() * $oDadosPatronais->aBasePrevidencia1->nValor / 100), 2);
                  break;

                  case '2' :
                    $oTotais->nValorBasePrevidencia2 += round(($oEventoFinanceiro->getValor() * $oDadosPatronais->aBasePrevidencia2->nValor / 100), 2);
                  break;

                  case '3' :
                    $oTotais->nValorBasePrevidencia3 += round(($oEventoFinanceiro->getValor() * $oDadosPatronais->aBasePrevidencia3->nValor / 100), 2);
                  break;

                  case '4' :
                    $oTotais->nValorBasePrevidencia4 += round(($oEventoFinanceiro->getValor() * $oDadosPatronais->aBasePrevidencia4->nValor / 100), 2);
                  break;
                }

              break;//FIM Case R992
            }
            continue;

          break;  //FIM Case TIPO EventoFinanceiro == BASE

          case EventoFinanceiroFolha::PROVENTO:

            if ( $oRubrica->getCodigo() >= 'R950' ) {
              continue;
            }

            $oTotalEventosRubricas->nValorProventos      += $oEventoFinanceiro->getValor();
            $oTotalEventosRubricas->nQuantidadeProventos += $oEventoFinanceiro->getQuantidade();

            $oTotais->nValorProventos                    += $oEventoFinanceiro->getValor();
            $oTotais->nValorLiquido                      += $oEventoFinanceiro->getValor();

            /**
             * Valida o tipo de Empenho da Rubrica
             * Se é um elemento de receita/despesa e se
             * é Algum Tipo de Retenção
             */
            switch ( $oRubrica->getTipoEmpenho() ) {

              case 'e': //empenhos[
                $oTotais->nValorEmpenhos   += $oEventoFinanceiro->getValor();
              break;

              case 'r': //retencao
                $oTotais->nValorRetencao   -= $oEventoFinanceiro->getValor();
              break;

              case 'd': //deducao
                $oTotais->nValorDeducao    += $oEventoFinanceiro->getValor();
              break;

              case 'p': //P.Extra
                $oTotais->nValorPontoExtra += $oEventoFinanceiro->getValor();
              break;

              case ''://Diferenca
                $oTotais->nValorDiferenca  += $oEventoFinanceiro->getValor();
              break;
            }

          break;

          case EventoFinanceiroFolha::DESCONTO:

            if ( $oRubrica->getCodigo() >= 'R950' ) {
              continue;
            }

            $oTotalEventosRubricas->nValorDescontos      += $oEventoFinanceiro->getValor();
            $oTotalEventosRubricas->nQuantidadeDescontos += $oEventoFinanceiro->getQuantidade();

            $oTotais->nValorDescontos                    += $oEventoFinanceiro->getValor();
            $oTotais->nValorLiquido                      -= $oEventoFinanceiro->getValor();

            switch ( $oRubrica->getTipoEmpenho() ) {

              case 'e': //empenhos[
                $oTotais->nValorEmpenhos   -= $oEventoFinanceiro->getValor();
              break;

              case 'r': //retencao
                $oTotais->nValorRetencao   += $oEventoFinanceiro->getValor();
              break;

              case 'd': //deducao
                $oTotais->nValorDeducao    -= $oEventoFinanceiro->getValor();
              break;

              case 'p': //P.Extra
                $oTotais->nValorPontoExtra -= $oEventoFinanceiro->getValor();
              break;

              case ''://Diferenca
                $oTotais->nValorDiferenca  -= $oEventoFinanceiro->getValor();
              break;
            }

          break;
        }
      }

      $sCodigoRubricaTipoEmpenho   = $oRubrica->getTipoEmpenho() == "" ? $sCodigoRubrica : $oRubrica->getTipoEmpenho() . "-" . $sCodigoRubrica;


      $iQuantidadeServidores       = count($aTotalServidoresRubrica[$sEstruturalFiltro][$sCodigoRubrica]);
      $oTotais->iTotalFuncionarios = count($aTotalServidoresEstrutural[$sEstruturalFiltro]);

      if ( $oTotalEventosRubricas->nValorProventos > 0 ) {

        $sValorProvento = db_formatar($oTotalEventosRubricas->nValorProventos, 'f');
        $oPdf->cell($oTamanhoColunas->rubrica     , $iAlt, $sCodigoRubricaTipoEmpenho                    , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->funcionarios, $iAlt, $iQuantidadeServidores                        , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->quantidade  , $iAlt, db_formatar("{$oTotalEventosRubricas->nQuantidadeProventos}", "f")  , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->descricao   , $iAlt, $oRubrica->getDescricao()                     , 0, 0, "L", 0);
        $oPdf->cell($oTamanhoColunas->proventos   , $iAlt, $sValorProvento                               , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->descontos   , $iAlt, ''                                            , 0, 1, "R", 0);
      }

      if ( $oTotalEventosRubricas->nValorDescontos > 0 ) {

        $sValorDesconto = db_formatar($oTotalEventosRubricas->nValorDescontos, 'f');
        $oPdf->cell($oTamanhoColunas->rubrica     , $iAlt, $sCodigoRubricaTipoEmpenho                    , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->funcionarios, $iAlt, $iQuantidadeServidores                        , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->quantidade  , $iAlt, db_formatar("{$oTotalEventosRubricas->nQuantidadeDescontos}", "f")  , 0, 0, "R", 0);
        $oPdf->cell($oTamanhoColunas->descricao   , $iAlt, $oRubrica->getDescricao()                     , 0, 0, "L", 0);
        $oPdf->cell($oTamanhoColunas->proventos   , $iAlt, ''                                            , 0 ,0, "R", 0);
        $oPdf->cell($oTamanhoColunas->descontos   , $iAlt, $sValorDesconto                               , 0 ,1, "R", 0);
      }
    }

    /**
     * Mostra o Totalizador
     */
    mostraTotalizador( $oPdf, $aRubricas, $oTotais, $oDadosPatronais );
  }

  $oPdf->Output();

} catch ( Exception $eErro ) {

  db_redireciona('db_erros.php?fechar=true&db_erro='. $eErro->getMessage() );
  exit;
}

/**
 * Função mostraTotalizador
 *
 * @param mixed $oPdf
 * @param mixed $aRubricas
 * @access public
 * @return void
 */
function mostraTotalizador ( $oPdf, $aRubricas, $oDadosFolha, $oDadosPatronais ) {

  $iAlt     = 4;
  $iEspacoX = 117;

  $oPdf->ln(3);
  $oPdf->setX($iEspacoX);

  $oPdf->Line(10, $oPdf->getY() - 2, 202, $oPdf->getY() - 2) ;

  $oPdf->cell(45, $iAlt, 'TOTAL'                                                       , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorProventos,'f')                , 0, 0, "R", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorDescontos,'f')                , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'TOTAL LÍQUIDO '                                              , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorLiquido  ,'f')                , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'N. FUNCIONÁRIOS '                                            , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, $oDadosFolha->iTotalFuncionarios                              , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'BASE PREVIDÊNCIA '                                           , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nTotalBasePrevidencia,'f')          , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'BASE I.R.R.F  '                                              , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBaseIRRF,'f')                 , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'EMPENHOS  '                                                  , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorEmpenhos,'f')                 , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'P.EXTRA   '                                                  , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorPontoExtra,'f')               , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'RETENCAO  '                                                  , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorRetencao,'f')                 , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'DEDUCAO  '                                                   , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorDeducao,'f')                  , 0, 1, "R", 0);

  $oPdf->setX($iEspacoX);
  $oPdf->cell(65, $iAlt, 'DIFERENCA '                                                  , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorDiferenca,'f')                , 0, 1, "R", 0);

  $oPdf->ln(3);

  $oPdf->setfont('arial','',7);
  
  $nValorBasePrevidencia1 = $oDadosPatronais->aBasePrevidencia1->nValor ? $oDadosPatronais->aBasePrevidencia1->nValor : 1;
  $nValorBasePrevidencia2 = $oDadosPatronais->aBasePrevidencia2->nValor ? $oDadosPatronais->aBasePrevidencia2->nValor : 1;
  $nValorBasePrevidencia3 = $oDadosPatronais->aBasePrevidencia3->nValor ? $oDadosPatronais->aBasePrevidencia3->nValor : 1;
  $nValorBasePrevidencia4 = $oDadosPatronais->aBasePrevidencia4->nValor ? $oDadosPatronais->aBasePrevidencia4->nValor : 1;
  
  $nValorPrevidencia1 = ($oDadosFolha->nValorBasePrevidencia1 * 100) / $nValorBasePrevidencia1;
  $nValorPrevidencia2 = ($oDadosFolha->nValorBasePrevidencia2 * 100) / $nValorBasePrevidencia2;
  $nValorPrevidencia3 = ($oDadosFolha->nValorBasePrevidencia3 * 100) / $nValorBasePrevidencia3;
  $nValorPrevidencia4 = ($oDadosFolha->nValorBasePrevidencia4 * 100) / $nValorBasePrevidencia4;
  
  $oPdf->cell(25, $iAlt, "{$oDadosPatronais->aBasePrevidencia1->sNome}:"             , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($nValorPrevidencia1,'f')         , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "{$oDadosPatronais->aBasePrevidencia2->sNome}:"             , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($nValorPrevidencia2,'f')         , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "{$oDadosPatronais->aBasePrevidencia3->sNome}:"             , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($nValorPrevidencia3,'f')         , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "{$oDadosPatronais->aBasePrevidencia4->sNome}:"             , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($nValorPrevidencia4,'f')         , 0, 1, "R", 0);

  /**
   * Verifica se as folhas com siglas r35 ou r93 ou r94 foram escolhidas pelo usuário.
   */
  $oPdf->ln(3);

  $oPdf->cell(25, $iAlt, "PATRONAL({$oDadosPatronais->aBasePrevidencia1->nValor}%): ", 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBasePrevidencia1,'f')                            , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "PATRONAL({$oDadosPatronais->aBasePrevidencia2->nValor}%): ", 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBasePrevidencia2,'f')                            , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "PATRONAL({$oDadosPatronais->aBasePrevidencia3->nValor}%): ", 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBasePrevidencia3,'f')                            , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "PATRONAL({$oDadosPatronais->aBasePrevidencia4->nValor}%): ", 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBasePrevidencia4,'f')                            , 0, 1, "R", 0);

  $oPdf->ln(3);
  $oPdf->cell(25, $iAlt, "BASE F.G.T.S. :"                                             , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBaseFGTS,'f')                 , 0, 0, "R", 0);

  $oPdf->cell(25, $iAlt, "F.G.T.S. EMPR :"                                             , 0, 0, "L", 0);
  $oPdf->cell(20, $iAlt, db_formatar($oDadosFolha->nValorBaseFGTS * 0.08,'f')          , 0, 1, "R", 0);

}

/**
 * Função nomeFolhaAtual
 * Retorna o nome do tipo de folha que o programa está imprimindo no momento em que a função é chamada.
 * array com tipos de folhas a serem emitidos
 * 1 - salario            - r14 - gerfsal
 * 2 - folha complementar - r48 - gerfcom
 * 3 - rescisao           - r20 - gerfres
 * 4 - 13 salario         - r35 - gerfs13
 * 5 - adiantamento       - r22 - gerfadi
 * 6 - ferias             - r31 - gerffer
 * 7 - ponto fixo         - r53 - gerffx
 * 8 - provisao de Ferias - r93 - gerfprovfer
 * 9 - provisao de 13o    - r94 - gerfprovs13
 *
 * @return string
 */
function nomeFolhaAtual ($sNomeTabela) {

  switch ($sNomeTabela) {

    case CalculoFolha::CALCULO_SALARIO : // GERFSAL
      $sNomeFolhaAtual = "FOLHA SALÁRIO";
    break;

    case CalculoFolha::CALCULO_RESCISAO :  // GERFRES
      $sNomeFolhaAtual = "FOLHA RECISÃO";
    break;

    case CalculoFolha::CALCULO_ADIANTAMENTO : // GERFADI
      $sNomeFolhaAtual = "FOLHA ADIANTAMENTO";
    break;

    case CalculoFolha::CALCULO_13o : // GERFS13
      $sNomeFolhaAtual = "FOLHA 13º SALÁRIO";
    break;

    case CalculoFolha::CALCULO_COMPLEMENTAR : // GERFCOM
      $sNomeFolhaAtual = "FOLHA COMPLEMENTAR";
    break;

    case CalculoFolha::CALCULO_SUPLEMENTAR : // SUPLEMENTAR
      $sNomeFolhaAtual = "FOLHA SUPLEMENTAR";
      break;

    case CalculoFolha::CALCULO_PROVISAO_FERIAS : // GERFPROVFER
      $sNomeFolhaAtual = "FOLHA PROVISÃO DE FÉRIAS";
    break;

    case CalculoFolha::CALCULO_PROVISAO_13o : // GERFPROVS13
      $sNomeFolhaAtual = "FOLHA PROVISÃO 13º SALÁRIO";
    break;
  }

  return $sNomeFolhaAtual;

}