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

/**
 * @todo quando servidor dos clientes forem atualizados para versão php 5.6+
 *       remover as funções ini_set, set_time_limit
 */
ini_set('memory_limit', '-1');
set_time_limit('0');


require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/DBArray.php"));
require_once(modification("model/pessoal/ServidorRepository.model.php"));
require_once(modification("model/pessoal/RubricaRepository.model.php"));

require_once(modification("libs/JSON.php"));

define( "TIPO_RESUMO_GERAL"            			, "g" );
define( "TIPO_RESUMO_ORGAO"            			, "o" );
define( "TIPO_RESUMO_UNIDADE"          			, "l" );
define( "TIPO_RESUMO_UNIDADE_COMPLETA" 			, "lc");
define( "TIPO_RESUMO_MATRICULA"    		 			, "m" );
define( "TIPO_RESUMO_LOCAL_TRABALHO"   			, "t" );
define( "TIPO_RESUMO_CARGO"            			, "c" );
define( "TIPO_RESUMO_REGIME"           			, "r" );

define( "RUBRICA_RELATORIO_QUANTIDADE"      , "Q"  );
define( "RUBRICA_RELATORIO_VALOR"      			, "V"  );

define( "EXIBIR_SOMENTE_TOTAIS"             , "s"  );
define( "NAO_EXIBIR_SOMENTE_TOTAIS"         , "n"  );

define( "TIPO_ARQUIVOS_PDF"						 			, "pdf");
define( "TIPO_ARQUIVOS_CSV"						 			, "csv");

define( "ORDENACAO_NUMERICA"           			, "n"  );
define( "ORDENACAO_ALFABETICA"         			, "a"  );

define( "QUEBRAR_PAGINAS_SIM"					 			, "s");
define( "QUEBRAR_PAGINAS_NAO"					 			, "n");

define( "MODO_IMPRESSAO_RETRATO"            , "r");
define( "MODO_IMPRESSAO_PAISAGEM"           , "p");

define( "TIPO_VINCULO_GERAL"                , "g" );
define( "TIPO_VINCULO_ATIVOS"               , "a" );
define( "TIPO_VINCULO_INATIVOS"             , "i" );
define( "TIPO_VINCULO_PENSIONISTAS"         , "p" );
define( "TIPO_VINCULO_INATIVOS_PENSIONISTAS", "ip");

try {

  $oJson        = new services_json();
  $oParametros  = $oJson->decode(str_replace("\\","",base64_decode($_GET["json"])));
  $iInstituicao = db_getsession( "DB_instit" );
  $aWhere       = array();

	/**
	 * Realiza busca das rubricas a partir do relatorio selecionado.
	 */
	$oDaoRelRubMov = new cl_relrubmov();
	$sSelect 			 = "rh46_rubric, rh46_quantval,rh45_form as formulado,rh45_selecao,rh45_descr";
	$sWhere        = "rh46_codigo = {$oParametros->iRelatorio} AND rh27_instit = {$iInstituicao}";
	$sSqlRelRubMov = $oDaoRelRubMov->sql_query(null, $sSelect, "rh46_seq", $sWhere);
	$rsRubricas 	 = db_query($sSqlRelRubMov);

  /**
   * Realiza busca dos campos que devem aparecer no relatório a partir do relatório selecionado.
   */
  $oDaoCamposRelatorio    = new cl_relrubrelrubcampos();
  $sSelectCamposRelatorio = "rh120_campo, rh120_tamanho, rh120_limite, rotulorel, conteudo";
  $sWhereCamposRelatorio  = "rh121_relrub = {$oParametros->iRelatorio} AND rh45_instit = {$iInstituicao}";
  $sSqlCamposRelatorio    = $oDaoCamposRelatorio->sql_queryCamposPorRelatorio($sSelectCamposRelatorio, "rh121_ordem", $sWhereCamposRelatorio);
  $rsCamposRelatorios     = db_query($sSqlCamposRelatorio);

  $aCamposRelatorios = db_utils::getCollectionByRecord($rsCamposRelatorios);

  /**
   * Verifica se foi selecionado o tipo da complementar.
   */
  $sTipoComplementar = null;
  if (!empty($oParametros->sComplementar)) {
    $sTipoComplementar = $oParametros->sComplementar;
  }

	if ( !$rsRubricas ) {
		throw new DBException( "Erro ao buscar Rubricas" );
	}

  if ( pg_num_rows($rsRubricas) == 0 ) {
		throw new BusinessException( "Nenhuma Rubrica encontrada." );
	}

  /**
   * Seleciona Primeiro Registro, pois ele é repetido em todas as linhas dos resultado sql
   */
	$oDadosRubricasRelatorio = db_utils::fieldsMemory($rsRubricas, 0);

	/**
	 * Valida se existe selecao
	 */
	$oDaoSelecao   = new cl_selecao();
	$sSqlSelecao   = $oDaoSelecao->sql_query_file(
	  $oDadosRubricasRelatorio->rh45_selecao, $iInstituicao, 'r44_where', null
  );
	$rsSelecao 	   = db_query($sSqlSelecao);

	if ( pg_num_rows($rsSelecao) > 0 ) {

		$oSelecao      = db_utils::fieldsMemory($rsSelecao, 0);
    $aWhere['selecao'] = $oSelecao->r44_where;
	}

	/**
	 * Retorna as Rubricas Selecionadas
	 */
	$aDadosRubricas        = db_utils::getCollectionByRecord($rsRubricas);
	$aRubricasUtilizadas   = array();
	$aTipoValorRubricas    = array();

  /**
   * Utilizado para definir a ordem da Rubrica na Formula Total. i.e.: RUB1, RUB2,....
   */
  $aOrdemFormula         = array();
  $iContadorOrdemFormula = 0;

	foreach ( $aDadosRubricas as $oDadosRubrica ) {

    $aRubricasUtilizadas[] 		                  		  = $oDadosRubrica->rh46_rubric;
    $aTipoValorRubricas[$oDadosRubrica->rh46_rubric]  = $oDadosRubrica->rh46_quantval;
    $aOrdemFormula["RUB" . ++$iContadorOrdemFormula ] = $oDadosRubrica->rh46_rubric;
    $aTotalRubrica[$oDadosRubrica->rh46_rubric]      = 0.00;
	}


	switch ($oParametros->sTipoResumo) {

		case TIPO_RESUMO_ORGAO:
      $sLabelTipoRelatorio          = "Órgãos";
      $sCampoCondicaoTipoRelatorio  = "rh26_orgao";
      $sCampoEstruturalTipoRelatorio= "rh26_orgao";
      $sCampoDescricaoTipoRelatorio = "o40_descr";
  	break;

		case TIPO_RESUMO_UNIDADE:
	    $sAgrupamento  = "1";

      $sLabelTipoRelatorio             = "Unidade";
      $sCampoCondicaoTipoRelatorio     = "rh25_codlotavinc";
      $sCampoEstruturalTipoRelatorio   = "rh25_codlotavinc";
      $sCampoDescricaoTipoRelatorio    = "(select o41_descr
                                             from orcunidade
                                            where rhlotaexe.rh26_anousu  = orcunidade.o41_anousu
                                              and rhlotaexe.rh26_orgao   = orcunidade.o41_orgao
                                              and rhlotaexe.rh26_unidade = orcunidade.o41_unidade limit 1)";
		break;

		case TIPO_RESUMO_UNIDADE_COMPLETA: //TROCAR PARA LOTACAO
      $sLabelTipoRelatorio             = "Lotações";
      $sCampoCondicaoTipoRelatorio     = "r70_codigo";
      $sCampoEstruturalTipoRelatorio   = "r70_estrut";
      $sCampoDescricaoTipoRelatorio    = "r70_descr";
		break;

		case TIPO_RESUMO_MATRICULA:

      $sLabelTipoRelatorio          = "Matrículas";
      $sCampoCondicaoTipoRelatorio  = "rh02_regist";
      $sCampoEstruturalTipoRelatorio= "rh02_regist";
      $sCampoDescricaoTipoRelatorio = "z01_nome";
		break;

		case TIPO_RESUMO_LOCAL_TRABALHO:

      $sLabelTipoRelatorio          = "Locais de Trabalho";
      $sCampoCondicaoTipoRelatorio  = "rh55_codigo";
      $sCampoEstruturalTipoRelatorio= "rh55_estrut";
      $sCampoDescricaoTipoRelatorio = "rh55_descr";
		break;

		case TIPO_RESUMO_CARGO:

      $sLabelTipoRelatorio           = "Cargos";
      $sCampoCondicaoTipoRelatorio   = "rh37_funcao";
      $sCampoEstruturalTipoRelatorio = "rh37_funcao";
      $sCampoDescricaoTipoRelatorio  = "rh37_descr";
		break;

		case TIPO_RESUMO_REGIME:

		  $sLabelTipoRelatorio           = "Regime";
      $sCampoCondicaoTipoRelatorio   = "rh30_regime";
      $sCampoEstruturalTipoRelatorio = "rh30_regime";
      $sCampoDescricaoTipoRelatorio  = "(select rh52_descr from rhcadregime where rh52_regime = rh30_regime)";
	  break;

    case TIPO_RESUMO_GERAL:

		default:

			$sLabelTipoRelatorio           = "Geral";
      $sCampoCondicaoTipoRelatorio   = 1;
      $sCampoEstruturalTipoRelatorio = 1;
      $sCampoDescricaoTipoRelatorio  = 1;
		break;
	}

  /**
   * Definições Sobre Vinculo
   */
  if ( !empty($oParametros->sVinculo) ) {

    switch ( $oParametros->sVinculo ) {

      default:
        $sTituloVinculo   = "Geral";
        $sCondicaoVinculo = null;
      break;

      case TIPO_VINCULO_ATIVOS:
        $sTituloVinculo   = "Ativos";
        $sCondicaoVinculo = " rh30_vinculo = 'A' ";
      break;

      case TIPO_VINCULO_INATIVOS:
        $sTituloVinculo   = "Inativos";
        $sCondicaoVinculo = " rh30_vinculo = 'I' ";
      break;

      case TIPO_VINCULO_PENSIONISTAS:
        $sTituloVinculo   = "Pensionistas";
        $sCondicaoVinculo = " rh30_vinculo = 'P' ";
      break;

      case TIPO_VINCULO_INATIVOS_PENSIONISTAS:
        $sTituloVinculo   = "Inativos / Pensionistas";
        $sCondicaoVinculo = " rh30_vinculo in ('I','P') ";
      break;
    }

    if (!empty($sCondicaoVinculo) ) {
      $aWhere['vinculo'] = $sCondicaoVinculo;
    }
  }

	/**
	 * Busca Matriculas
	 */
  $oDaoRhPessoalMov = new cl_rhpessoalmov();
  $iInstituicao     = db_getsession('DB_instit');

  $sCampos          = "distinct rh01_regist, z01_nome, ";

  $sCampos         .= "{$sCampoCondicaoTipoRelatorio}   as agrupador_codigo,     ";
  $sCampos         .= "{$sCampoEstruturalTipoRelatorio} as agrupador_estrutural, ";
  $sCampos         .= "{$sCampoDescricaoTipoRelatorio}  as agrupador_descricao   ";

  $sWhere           = implode(' and ', $aWhere);

  $sAgrupamento     = "rh01_regist, z01_nome, {$sCampoCondicaoTipoRelatorio}, {$sCampoDescricaoTipoRelatorio}, {$sCampoEstruturalTipoRelatorio}";

  /**
   * Percorre os campos escolhidos para serem exibidos
   */
  foreach ($aCamposRelatorios as $oCampos) {
    $sCampos       .= ", {$oCampos->rh120_campo}";
    $sAgrupamento  .= ", {$oCampos->rh120_campo}";
  }

	if ( $oParametros->sOrdem == ORDENACAO_NUMERICA ) {
    $sTituloOrdem = "Numérica";
    $sOrdem       = "rh01_regist";
  }
  if ( $oParametros->sOrdem == ORDENACAO_ALFABETICA ) {
    $sTituloOrdem = "Alfabética";
	  $sOrdem       = "z01_nome";
  }

  $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_query_baseServidores( $oParametros->iMesCompetencia,
  																															 	 $oParametros->iAnoCompetencia,
  																															 	 $iInstituicao,
  																															 	 $sCampos,
 																																	 $sWhere,
                                                                   $sOrdem,
                                                                   $sAgrupamento );

  $rsServidores     = db_query( $sSqlRhPessoalMov );

  /**
   * Verifica se a consulta retornou algum resultado,
   * caso tenha retornado 0 registros exibe tela de erro
   */
  if ( pg_num_rows($rsServidores) == 0 ) {
    throw new BusinessException( "Nenhum servidor encontrado." );
  }

  $aServidores      = array();
  $aQuebras         = array();
  $aValorRubricas   = array();
  $aDadosServidores = array();
  $aRubricas        = db_utils::getCollectionByRecord($rsRubricas);
  $iTotalServidores = 0;

  /**
   * Percorre todos os servidores separando eles em seus grupos
   */
  for ( $iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {

    $oDadosServidores     = db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
    $sGrupo               = empty($oDadosServidores->agrupador_codigo)    ? "999999999" : $oDadosServidores->agrupador_codigo;
    $sDescricao           = empty($oDadosServidores->agrupador_descricao) ? "Sem Grupo" : $oDadosServidores->agrupador_estrutural . ' - ' . $oDadosServidores->agrupador_descricao;

    $aQuebras[$sGrupo]    = $sDescricao;
    $oServidor            = ServidorRepository::getInstanciaByCodigo( $oDadosServidores->rh01_regist,
                                                                      $oParametros->iAnoCompetencia,
                                                                      $oParametros->iMesCompetencia );
    $aDadosServidor[$oDadosServidores->rh01_regist]       = $oDadosServidores;
    $aServidores[$sGrupo][$oDadosServidores->rh01_regist] = $oServidor;
    $iTotalServidores++;

    $lPossuiEventoFinanceiro                                 = false;    // Ninguem tem cálculo até que se verifique movimentação
    $aValorRubricas[$oDadosServidores->rh01_regist]["TOTAL"] = 0;

    /**
     * Percorre os Tipos de Folha Verificando os seus Cálculos.
     */
    foreach ( $oParametros->aTipoFolha as $sFolha ) {

      /**
       * Instancia Cálculo
       */
      $oCalculo = $oServidor->getCalculoFinanceiro($sFolha);

      /**
       * Percorre as Rubricas Pegando seu Cálculo Financeiro
       */

      $iComplementar = $sTipoComplementar;

      /**
       * Verifica se a folha é do Tipo adiantamento, se for, passa a complementar como null.
       * Complementar não utiliza Semestre
       */
      if ( $sFolha == 'gerfadi') {
        $iComplementar = null;
      }


      $aEventoFinanceiro = $oCalculo->getEventosFinanceiros($iComplementar, $aRubricasUtilizadas);

      if ( empty($aEventoFinanceiro) ) {
        continue;
      }

      /**
       * Se Houver Eventos Financeiros, mostra dados no Relatório
       */
      $lPossuiEventoFinanceiro = true;

      /**
       * Percorre os Eventos financeiros somando seus valores
       */

      foreach ( $aEventoFinanceiro as $oEvento ) {

        $sRubrica = $oEvento->getRubrica()->getCodigo();

        if ( !isset($aValorRubricas[$oDadosServidores->rh01_regist][$sRubrica]) ) {
          $aValorRubricas[$oDadosServidores->rh01_regist][$sRubrica] = 0;     // Valor Geral das Rubricas por Servidor
        }

        if ( $aTipoValorRubricas[$sRubrica] == RUBRICA_RELATORIO_VALOR ) {

          $aValorRubricas[$oDadosServidores->rh01_regist][$sRubrica] += $oEvento->getValor();
          $aValorRubricas[$oDadosServidores->rh01_regist]["TOTAL"]   += $oEvento->getValor();
        } else {

          $aValorRubricas[$oDadosServidores->rh01_regist][$sRubrica] += $oEvento->getQuantidade();
          $aValorRubricas[$oDadosServidores->rh01_regist]["TOTAL"]   += $oEvento->getQuantidade();
        }
      }//Fim foreach eventos financeiros

    } //Fim Foreach Tipo de Folhas


    if ( !$lPossuiEventoFinanceiro ) {

      unset( $aServidores[$sGrupo][$oDadosServidores->rh01_regist] );
      $iTotalServidores--;
      continue;
    }

    /**
     * Realiza o calculo do total da soma das rubricas a partir da formula cadastrada.
     */


    $sFormula = $oDadosRubricasRelatorio->formulado;

    /**
     * Se A formula estiver vazia mantem a soma dela
     */
    if ( !empty($sFormula) ) {

      foreach ($aOrdemFormula as $sVariavelFormula => $sCodigoRubrica ) {

        $nValorRubrica = 0.00;
        if ( isset($aValorRubricas[$oDadosServidores->rh01_regist][$sCodigoRubrica]) ) {
          $nValorRubrica = $aValorRubricas[$oDadosServidores->rh01_regist][$sCodigoRubrica];
        }
        $sFormula = str_replace($sVariavelFormula, $nValorRubrica, $sFormula);
      }
      eval("\$nTotal = ({$sFormula});");
      $aValorRubricas[$oDadosServidores->rh01_regist]["TOTAL"] = $nTotal;
    }
  }//Fim Foreach Servidores

  /**
   * Verifica se a consulta retornou algum resultado,
   * caso tenha retornado 0 registros exibe tela de erro
   */
  if ($iTotalServidores == 0) {
    throw new BusinessException( "Nenhum servidor encontrado." );
  }

  if ( $oParametros->sTipoArquivo == TIPO_ARQUIVOS_PDF ) {
    include(modification("pes2_emissao_pdf002.php"));
  } else {

    include(modification("pes2_emissao_csv002.php"));

    echo '<script type="text/javascript">';
    echo 'window.close();';
    echo "window.opener.abrirDownload(\"$sArquivo\");";
    echo '</script>';

  }

} catch( Exception $eErro) {

	db_redireciona('db_erros.php?fechar=true&db_erro='. $eErro->getMessage() );
  exit;
}
