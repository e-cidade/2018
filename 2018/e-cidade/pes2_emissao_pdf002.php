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


define("ALTURA_CELULA", 4);
define("COM_FUNDO", true);
define("SEM_FUNDO", false);
define("COM_QUEBRA", true);
define("SEM_QUEBRA", false);
define("COM_BORDA", true);
define("SEM_BORDA", false);
define("COR_PREENCHIMENTO", 235);

/**
 * Configuração do cabeçalho
 */
$sNomeFolha = (count($oParametros->aNomeTipoFolha) == 1) ? utf8_decode(urldecode($oParametros->aNomeTipoFolha[0])) : 'Vários';
$head2      = "$oDadosRubricasRelatorio->rh45_descr ({$oParametros->iMesCompetencia} / {$oParametros->iAnoCompetencia})";
$head4      = "Tipo de Folha: " . $sNomeFolha;
$head5      = "Tipo de Resumo: {$sLabelTipoRelatorio}";
$head6      = "Ordem: $sTituloOrdem";
$head7      = "Vínculo: $sTituloVinculo";

/**
 * Configurações do PDF
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(COR_PREENCHIMENTO);

/**
 * Define o Tamanho da Pagina
 */
if( $oParametros->sModoImpressao == MODO_IMPRESSAO_RETRATO ) {

	$iLarguraMaxima = 215;
	$iLarguraUtil   = 195;
	$oPdf->AddPage("P");
} else {

	$iLarguraMaxima = 320;
	$iLarguraUtil   = 300;
	$oPdf->AddPage("L");
}

/**
 * Mostra a coluna de total
 */
$lMostraTotal = true;

/**
 * Tamanhos da célula
 */

$iTotalCampos = 0;

/**
 * Verifica se foi selecionado algum campo para o relatorio,
 * se não foi adiciona uma coluna vazia no inicio
 */
if( count($aCamposRelatorios) == 0) {

	$iTotalCampos = 24;
	$aCamposRelatorios[]->rh120_tamanho = 24;
}

/**
 * Percorre todos os campos selecionados,
 * somando o seu tamanho.
 */
foreach ($aCamposRelatorios as $iIndiceCampo => $oCampo) {

	$iTotal = $iTotalCampos + $oCampo->rh120_tamanho;

	/**
	 * Verifica os campos que cabem no tamanho de 250, as
	 * que não cabem são removidas do array de campos
	 */
	if ($iTotal > $iLarguraUtil) {

		$lMostraTotal = false;
		unset($aCamposRelatorios[$iIndiceCampo]);
	} else {
		$iTotalCampos += $oCampo->rh120_tamanho;
	}
}

/**
 * Valor padrão caso seja exibido somente os totais
 */
if ($oParametros->sSomenteTotais == EXIBIR_SOMENTE_TOTAIS) {
	$iTotalCampos = 24; 		
}


/**
 * calcula o Numero maximo de rubricas que poderá aparecer no relatório
 */
$iLimiteRubricas = floor(($iLarguraUtil - $iTotalCampos) / 20);

if ((($iLimiteRubricas * 20) + $iTotalCampos) + 20 >= $iLarguraUtil){
	$lMostraTotal = false;
}

/**
 * Percorre todas as Rubricas removendo as que não devem ser exibidas por falta de espaço.
 */
$TotalRubricas     = sizeof($aOrdemFormula);
$aRubricasLegendas = $aOrdemFormula;

for ( $iIndiceRubricas = 0; $iIndiceRubricas <= $TotalRubricas; $iIndiceRubricas++) {

	if($iIndiceRubricas >= $iLimiteRubricas) {

		$lMostraTotal = false;
		unset($aOrdemFormula['RUB' . ($iIndiceRubricas+1)]);
	}
}

/**
 * Tamanho das Colunas
 */
$iLarguraMaxima             = 280;
$iTamanhoRubrica            = 20;
$aTamanhosColunas['iTotal'] = 20;

if (count( $aOrdemFormula ) > $iLimiteRubricas) {
	$aTamanhosColunas['iRubricas']  = $iTamanhoRubrica * $iLimiteRubricas;
} else{
	$aTamanhosColunas['iRubricas']  = $iTamanhoRubrica * count( $aOrdemFormula );
}

$aTamanhosColunas['iCampos'  ]  = $iTotalCampos;
$iTamanhTotalGeral              = array_sum( $aTamanhosColunas ) - $aTamanhosColunas['iTotal'] - $aTamanhosColunas['iRubricas'];
$iLarguraMaxima                 = $aTamanhosColunas['iRubricas'] + $aTamanhosColunas['iCampos'] + 20;

/**
 * Calcula o tamanho das colunas quando é
 * selecionado para exibir somente os totais
 */
if ($oParametros->sSomenteTotais == EXIBIR_SOMENTE_TOTAIS) {

	$iTamanhoSomenteTotais = 30;
	$iTamanhTotalGeral     = 30;
	$iLarguraMaxima        = $iTamanhoSomenteTotais + $aTamanhosColunas['iRubricas'] + $aTamanhosColunas['iTotal'];
}

/**
 * Monta a Legenda
 */
$oPdf->SetFont('arial','b',8);
$oPdf->Cell(118, ALTURA_CELULA, "LEGENDA"             , COM_BORDA, COM_QUEBRA, "C" ,COM_FUNDO );
$oPdf->Cell( 15, ALTURA_CELULA, "Variável"            , COM_BORDA, SEM_QUEBRA, "C" ,COM_FUNDO );
$oPdf->Cell( 15, ALTURA_CELULA, "Rubrica"             , COM_BORDA, SEM_QUEBRA, "C" ,COM_FUNDO );
$oPdf->Cell( 55, ALTURA_CELULA, "Descrição da Rubrica", COM_BORDA, SEM_QUEBRA, "C" ,COM_FUNDO );
$oPdf->Cell( 15, ALTURA_CELULA, "Tipo"                , COM_BORDA, SEM_QUEBRA, "C" ,COM_FUNDO );
$oPdf->Cell( 18, ALTURA_CELULA, "Qtde/Valor"          , COM_BORDA, COM_QUEBRA, "C" ,COM_FUNDO );
$oPdf->SetFont('arial','',8);

foreach ( $aRubricasLegendas as $sDescricao => $sCodigoRubrica ) {

	$oRubrica = RubricaRepository::getInstanciaByCodigo($sCodigoRubrica);
	$sTipo    = $oRubrica->getTipo() == Rubrica::TIPO_PROVENTO ? "Provento" : "Desconto";

	$oPdf->Cell( 15 , ALTURA_CELULA, $sDescricao              , COM_BORDA, SEM_QUEBRA, "C" ,SEM_FUNDO );
	$oPdf->Cell( 15 , ALTURA_CELULA, $sCodigoRubrica          , COM_BORDA, SEM_QUEBRA, "C" ,SEM_FUNDO );
	$oPdf->Cell( 55 , ALTURA_CELULA, $oRubrica->getDescricao(), COM_BORDA, SEM_QUEBRA, "L" ,SEM_FUNDO );
	$oPdf->Cell( 15 , ALTURA_CELULA, $sTipo                   , COM_BORDA, SEM_QUEBRA, "C" ,SEM_FUNDO );

	$sTipoSoma = $aTipoValorRubricas[$sCodigoRubrica] == 'V' ? 'Valor' : 'Qtde';
	$oPdf->Cell( 18 , ALTURA_CELULA, $sTipoSoma               , COM_BORDA, COM_QUEBRA, "C" ,SEM_FUNDO );
}
$oPdf->ln(10);

/**
 * Total Geral, somanto todas as rubricas de todos os grupos
*/
$iTotalGeral = 0.0;

ksort($aServidores);

/**
 * Percorre todos os Servidores, separando pelos
 * grupos e por suas respectivas rubricas
 */
foreach ( $aServidores as $sGrupo => $aDadosServidores ) {

	if ( count($aDadosServidores) == 0 ) {
		continue;
	}

	/**
	 * Soma total dos valores do Grupo Atual
	 */
	$iTotalGrupo = 0.0;

	$sNomeGrupo = $aQuebras[$sGrupo];
	if($sNomeGrupo  == '1 - 1' ) {
		$sNomeGrupo = '1 - Geral';
	}

	$oPdf->SetFont('arial','b',10);
	$oPdf->Cell( $iLarguraMaxima , ALTURA_CELULA, $sNomeGrupo, SEM_BORDA, COM_QUEBRA, "L" ,SEM_FUNDO );
	$oPdf->SetFont('arial','b',8);

	/**
	 * Verifica se deve ser exibido somente os
	 * totais ou os dados dos servidores
	*/
	if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {
		/**
		 * Percorre todos os campos selecionados,
		 * criando suas respectivas colunas
		 */
		foreach ($aCamposRelatorios as $oCampo) {

			/**
			 * Verifica se existe o Rotulo para o campos escolhido
			 */
			if ( isset($oCampo->rotulorel) ) {
				$sNomeCampo = $oCampo->rotulorel;
			} else {
				$sNomeCampo = '';
			}

			if ((end($aCamposRelatorios)->rotulorel == $oCampo->rotulorel && !$lMostraTotal && count($aOrdemFormula) == 0)) {
				$oPdf->Cell( $oCampo->rh120_tamanho, ALTURA_CELULA, $sNomeCampo , COM_BORDA, COM_QUEBRA, "C" ,COM_FUNDO );
			} else {
				$oPdf->Cell( $oCampo->rh120_tamanho, ALTURA_CELULA, $sNomeCampo , COM_BORDA, SEM_QUEBRA, "C" ,COM_FUNDO );
			}
		}
	} else {
		$oPdf->Cell( $iTamanhoSomenteTotais, ALTURA_CELULA, "", COM_BORDA, SEM_QUEBRA, "C" ,COM_FUNDO );
	}

	/**
	 * Percorremos as Rubricas Montando os Cabecalhos
	 */
	foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

		$aTotalRubricaGrupo[$sCodigoRubrica] = 0.0;
		$oPdf->Cell( $iTamanhoRubrica, ALTURA_CELULA, $sCodigoRubrica, COM_BORDA, (end($aOrdemFormula) == $sCodigoRubrica && !$lMostraTotal) ? COM_QUEBRA : SEM_QUEBRA, "C" ,COM_FUNDO );
	}

	if ($lMostraTotal) {
		$oPdf->Cell( $aTamanhosColunas['iTotal'], ALTURA_CELULA, "Total" , COM_BORDA, COM_QUEBRA, "C" ,COM_FUNDO );
	}

	$oPdf->SetFont('arial','',8);

	/**
	 * Percorre cada servidor do grupo escrevendo seus dados
   */
	foreach ($aDadosServidores as $iMatricula => $oServidor) {

		$sValorTotal   = db_formatar($aValorRubricas[$iMatricula]["TOTAL"], "f");
		$sNomeServidor = $oServidor->getCgm()->getNome();

		/**
		 * Verifica se deve ser exibido somente os
		 * totais ou os dados dos servidores
		*/
		if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {

			/**
			 * Percorre todos os campos selecionados,
			 * criando suas respectivas colunas
			 */
			foreach ($aCamposRelatorios as $oCampo) {

				$oDadosServidor = $aDadosServidor[$iMatricula];

				/**
				 * Verifica se existe o campo rh120_campo no array
				 */
				if ( isset($oCampo->rh120_campo) ) {

					$sCampo      = $oCampo->rh120_campo;
					$sValorCampo = $oDadosServidor->$sCampo;
				} else {
					$sValorCampo = '';
				}

				/**
				 *  Verifica se o campo é do tipo data, se for formada ele para o formato Brasileiro
				 */
				if ( isset($oCampo->conteudo) && $oCampo->conteudo == 'date') {
					$sValorCampo = db_formatar($sValorCampo, 'd');
				}

				if( isset($oCampo->conteudo) && $oCampo->conteudo == 'float8') {
					$sValorCampo = trim(db_formatar($sValorCampo, 'f'));
				}

				if ( isset($oCampo->rh120_limite)) {
					$sValorCampo = substr($sValorCampo, 0, $oCampo->rh120_limite);
				} else {
					$sValorCampo = $sValorCampo;
				}

				if ((end($aCamposRelatorios)->rotulorel == $oCampo->rotulorel && !$lMostraTotal && count($aOrdemFormula) == 0)) {
					$oPdf->Cell( $oCampo->rh120_tamanho, ALTURA_CELULA, $sValorCampo , COM_BORDA, COM_QUEBRA, "L" ,SEM_FUNDO );
				} else {
					$oPdf->Cell( $oCampo->rh120_tamanho, ALTURA_CELULA, $sValorCampo , COM_BORDA, SEM_QUEBRA, "L" ,SEM_FUNDO );
				}
			}
		}

		/**
		 * Percorre as Rubricas dos Servidores escrevendo os valores
		 */
		foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

			$nValorRubrica = 0.00;
			/**
			 * Define Valror se Houver Rubrica
			 */
			if ( isset($aValorRubricas[$iMatricula][$sCodigoRubrica]) ) {
				$nValorRubrica   = $aValorRubricas[$iMatricula][$sCodigoRubrica];
			}

			$aTotalRubricaGrupo[$sCodigoRubrica] += $nValorRubrica;
			$aTotalRubrica[$sCodigoRubrica]      += $nValorRubrica;
			$sValorRubrica                        = db_formatar($nValorRubrica, "f");

			/**
			 * Verifica se deve ser exibido somente os
			 * totais ou os dados dos servidores
			*/
			if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {
				$oPdf->Cell( $iTamanhoRubrica, ALTURA_CELULA, $sValorRubrica, COM_BORDA, (end($aOrdemFormula) == $sCodigoRubrica && !$lMostraTotal) ? COM_QUEBRA : SEM_QUEBRA, "R" ,SEM_FUNDO );
			}
		}

		/**
		 * Verifica se deve ser exibido somente os
		 * totais ou os dados dos servidores
		 */
		if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS && $lMostraTotal) {
			$oPdf->SetFont('arial','b',8);
		  $oPdf->Cell( $aTamanhosColunas['iTotal']    , ALTURA_CELULA, $sValorTotal  , COM_BORDA, COM_QUEBRA, "R" ,COM_FUNDO );
			$oPdf->SetFont('arial','',8);
		}

		$iTotalGrupo += $aValorRubricas[$iMatricula]["TOTAL"];
	}

	$iTotalGeral += $iTotalGrupo;

	$oPdf->SetFont('arial','b',8);
	$oPdf->Cell( $iTamanhTotalGeral, ALTURA_CELULA, "TOTAL", COM_BORDA, SEM_QUEBRA, "L" ,COM_FUNDO );

	/*
	 * Monta os totais para cada rubrica.
	*/
	foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

		$nValorRubrica = 0.00;

		if ( isset($aTotalRubricaGrupo[$sCodigoRubrica]) ) {
			$nValorRubrica = $aTotalRubricaGrupo[$sCodigoRubrica];
		}

		$oPdf->Cell( $iTamanhoRubrica, ALTURA_CELULA, db_formatar($nValorRubrica, "f"), COM_BORDA, SEM_QUEBRA, "R" ,COM_FUNDO );
	}

	if( $lMostraTotal ) {
		$oPdf->Cell( $aTamanhosColunas['iTotal'] , ALTURA_CELULA, db_formatar($iTotalGrupo, "f") , COM_BORDA, COM_QUEBRA, "R" ,COM_FUNDO );
		$oPdf->Ln();
	} else {
		$oPdf->Ln(10);
	}
	$oPdf->SetFont('arial','',8);
}

/**
 * Monta o valor totalsomando o valor de todos os grupos
 */
$oPdf->SetFont('arial','b',8);
$oPdf->Cell( $iTamanhTotalGeral, ALTURA_CELULA, "TOTAL GERAL", COM_BORDA, SEM_QUEBRA, "L" ,COM_FUNDO );

/*
 * Monta os totais gerais para cada Rubrica.
 */
foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

	$nValorTotalRubrica = 0.00;

	if ( isset($aTotalRubrica[$sCodigoRubrica]) ) {
		$nValorTotalRubrica = $aTotalRubrica[$sCodigoRubrica];
	}

	$oPdf->Cell( $iTamanhoRubrica, ALTURA_CELULA, db_formatar($nValorTotalRubrica, "f"), COM_BORDA, SEM_QUEBRA, "R" ,COM_FUNDO );
}

if ( $lMostraTotal ) {
	$oPdf->Cell( $aTamanhosColunas['iTotal'] , ALTURA_CELULA, db_formatar($iTotalGeral, "f") , COM_BORDA, COM_QUEBRA, "R" ,COM_FUNDO );
}

$oPdf->Output(date('ymdhis') . ".pdf",false);