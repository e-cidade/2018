<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("model/CgmFactory.model.php");
require_once ("std/DBDate.php");
require_once ("fpdf151/pdfnovo.php");
require_once ("interfaces/ICalculoMediaRubrica.interface.php");

db_app::import('exceptions.*');
db_app::import('pessoal.*');

$oJson                  = new services_json();
$oRetorno               = new stdClass();

$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->iStatus      = 1;
$oRetorno->sMensagem    = '';


try {

  switch ($oParametros->exec) {

		/**
		 * --------------------------------------------------------------------------------------------
		 * Retorna as complementares para o periodo da folha escolhido
		 * --------------------------------------------------------------------------------------------
		 */
		case 'getComplementares' :

			$oDaoGerfcom       = db_utils::getDao('gerfcom');
			$sCampos           = 'distinct r48_semest';
			$sSqlComplentares  = $oDaoGerfcom->sql_query_file($oParametros->iAnoFolha, $oParametros->iMesFolha, null, null, $sCampos);
			$rsComplementarres = $oDaoGerfcom->sql_record($sSqlComplentares);

			if($oDaoGerfcom->numrows == 0){
				throw new Exception('Sem complementar para este período.');
			}

			$oRetorno->aComplementares = db_utils::getCollectionByRecord($rsComplementarres, false, false, true);

		break;

		/**
		 * --------------------------------------------------------------------------------------------
		 * Gera relatorio das faixas salariais em PDF e CSV
		 * --------------------------------------------------------------------------------------------
		 */
		case 'gerarRelatorio' :

			$oDaoPessoalmov = db_utils::getDao('rhpessoalmov');
			$oParametrosSql = new stdClass();

			$oParametrosSql->iAnoFolha    = $oParametros->iAnoFolha;
			$oParametrosSql->iMesFolha    = $oParametros->iMesFolha;
			$oParametrosSql->iInstituicao = db_getsession('DB_instit');
			$oParametrosSql->aFaixas      = $oParametros->aFaixas;
			$oParametrosSql->sSelecao     = "";
			$iOpcaoServidor               = $oParametros->iOpcaoServidor;
			$iQuantidadeFaixas            = count($oParametros->aFaixas);

			/**
			 * Buscamos a condicao da selecao escolhida
			 */
			$oDaoSelecao   = db_utils::getDao("selecao");

			$sWhereSelecao = "";
			if (!empty($oParametros->iSelecao)) {
  			$sWhereSelecao = "r44_selec = {$oParametros->iSelecao}";

				$sSqlSelecao   = $oDaoSelecao->sql_query(null, $oParametrosSql->iInstituicao, "r44_where", null, $sWhereSelecao);
				$rsSelecao     = $oDaoSelecao->sql_record($sSqlSelecao);

				if ($oDaoSelecao->numrows > 0) {
				  $oParametrosSql->sSelecao = db_utils::fieldsMemory($rsSelecao, 0)->r44_where;
				}
			}
			/**
			 * Codigo da complementar
			 */
			if ( !empty($oParametros->iComplementar) ) {
				$oParametrosSql->iComplementar = $oParametros->iComplementar;
			}

			/**
			 * Tipo de folha
			 */
			if ( count($oParametros->aTiposFolha) > 0 ) {
				$oParametrosSql->sTiposFolha = implode(', ', $oParametros->aTiposFolha);
			}

			/**
			 * String do agrupador
			 */
			$oParametrosSql->sQuebraRelatorio  = $oParametros->sQuebraRelatorio;

			/**
			 * Array com os valores das faixas por tipo de folha
			 */
			$aDadosBanco = array();

			/**
			 * Percorre os tipos de folha e para cada tipo gera um resultado de faixas vindo do banco
			 * depois mesca todos os dados num array soh
			 */
			foreach ( $oParametros->aTiposFolha as $sTipoFolha ) {

				$sSqlFaixa = $oDaoPessoalmov->sql_query_faixasSalariais($oParametrosSql, $sTipoFolha);

		    $rsFaixa   = $oDaoPessoalmov->sql_record($sSqlFaixa);

				/**
				 * Mescla tudo em um array soh ignorando os tipos de folha, considerando apenas valores das faixas por quebra(agrupador)
				 */
				$aDadosBanco = array_merge($aDadosBanco, db_utils::getCollectionByRecord($rsFaixa));
			}

			/**
			 *  Criando array estruturado para a base dos relatórios
			 */
			$aDadosAgrupados = array(); // array com os dados das faixas separados por agrupamento
			$aDadosRelatorio = array(); // array com o esqueleto basico do relatorio
			$aTotalizadores  = array(); // array com os totalizadores do grupo
			$aFaixas         = array();

			/**
			 * Percorre os dados vindos do banco separando eles por agrupamemto e criando esqueleto basico
			 */
			foreach ( $aDadosBanco as $oDadosRegistro ) {

				/**
			   * inicia contagem de funcioarios por faixa
				 */
				$aTotalizadores[$oDadosRegistro->codigo_agrupador ]['aMatriculas'][$oDadosRegistro->matricula_servidor] = $oDadosRegistro->matricula_servidor;

				/**
				 * Soma o valor do grupo
				 */
				if ( !isset($aTotalizadores[$oDadosRegistro->codigo_agrupador ]['nValorTotal']) ) {
					$aTotalizadores[$oDadosRegistro->codigo_agrupador ]['nValorTotal']  = $oDadosRegistro->valor_provento;
				} else {
					$aTotalizadores[$oDadosRegistro->codigo_agrupador ]['nValorTotal'] += $oDadosRegistro->valor_provento;
				}

				/**
				 * Objeto com estrutura basica do relatorio
				 */
				$oDadosRelatorio = new stdClass;

				$oDadosRelatorio->nTotal              = 0;
				$oDadosRelatorio->iTotalFuncionarios  = 0;
				$oDadosRelatorio->sDescricaoAgrupador = $oDadosRegistro->descricao_agrupador;
				$oDadosRelatorio->iCodigoAgrupador    = $oDadosRegistro->codigo_agrupador;
				$oDadosRelatorio->aFaixas             = array();

				/**
				 * Atributos das faixa com valores zerados
				 */
				for ( $iFaixa = 0; $iFaixa < $iQuantidadeFaixas; $iFaixa++ ) {

					$oDadosRelatorio->aFaixas[$iFaixa]['nValorFaixa']        = 0;
					$oDadosRelatorio->aFaixas[$iFaixa]['iTotalFuncionarios'] = 0;
					$oDadosRelatorio->aFaixas[$iFaixa]['nPercentual']        = 0;

					$aFaixas[$iFaixa]['sDescricaoFaixa']  = trim(db_formatar($oParametros->aFaixas[$iFaixa]->iValorInicial, 'f')) . ' - ' .
																									trim(db_formatar($oParametros->aFaixas[$iFaixa]->iValorFinal, 'f'));
				}

				/**
				 * Agrupa os dados da query pelo agrupador
				 */
				$aDadosAgrupados[$oDadosRegistro->codigo_agrupador][] = $oDadosRegistro;
				$aDadosRelatorio[$oDadosRegistro->codigo_agrupador]   = $oDadosRelatorio;
      }


      $lValorDentroFaixa = false;
			/**
			 * Percorre cada agrupador
			 */
			foreach( $aDadosAgrupados as $iCodigoAgrupador => $aDadosAgrupador ) {

				$aDadosRelatorio[$iCodigoAgrupador]->nTotal             += $aTotalizadores[$iCodigoAgrupador]['nValorTotal'];
				$aDadosRelatorio[$iCodigoAgrupador]->iTotalFuncionarios  = count($aTotalizadores[$iCodigoAgrupador]['aMatriculas']);

				/**
				 * Percorre os objetos das faixas do agrupador atual
				 */
				foreach ( $aDadosAgrupador as $oDadosAgrupador ) {

					for ( $iFaixa = 0; $iFaixa < $iQuantidadeFaixas; $iFaixa++ ) {

						$iValorInicial = $oParametros->aFaixas[$iFaixa]->iValorInicial;
						$iValorFinal   = $oParametros->aFaixas[$iFaixa]->iValorFinal;

            /**
             * Verificamos se já encontrou valores dentro da faixa solicitada
             * Caso tenha, não podemos sobreescrever a variável.
             */
						$lValorMaiorFaixaInicial = $oDadosAgrupador->valor_provento >= $iValorInicial;
						$lValorMenorFaixaFinal   = $oDadosAgrupador->valor_provento <= $iValorFinal;
						$lValorDentroFaixa       = $lValorMaiorFaixaInicial && $lValorMenorFaixaFinal;

						if ( !isset( $aTotalizadores[$iCodigoAgrupador]["aFaixas"][$iFaixa] ) ) {
							$aTotalizadores[$iCodigoAgrupador]["aFaixas"][$iFaixa] = array();
						}

						/**
						 * Verifica se valor esta dentro da faixa, e soma ao total da faixa
						 */
						if ( $lValorDentroFaixa ) {

							/**
							 * Adiciona a matricula que esta dentro da faixa, para depois saber total de funcionarios por faixa
							 */
							$aTotalizadores[$iCodigoAgrupador]["aFaixas"][$iFaixa][$oDadosAgrupador->matricula_servidor] = $oDadosAgrupador->matricula_servidor;

							/**
							 * Valor total da faixa
							 */
							$aDadosRelatorio[$iCodigoAgrupador]->aFaixas[$iFaixa]['nValorFaixa'] += $oDadosAgrupador->valor_provento;

							/**
							 * Agrupamos as matriculas e valor salarial dos servidores por faixa
							 */
							$oDadosServidor                                                        = new stdClass();
							$oDadosServidor->iMatricula                                            = $oDadosAgrupador->matricula_servidor;
							$oDadosServidor->nValor                                                = $oDadosAgrupador->valor_provento;
							$aDadosRelatorio[$iCodigoAgrupador]->aFaixas[$iFaixa]['aServidores'][] = $oDadosServidor;
							unset($oDadosServidor);
						}
   				}

				}

				/**
				 * Apos somar os valores das faixas, calcula o percentual dela referente ao total do agrupador
				 */
				for ( $iFaixa = 0; $iFaixa < $iQuantidadeFaixas; $iFaixa++ ) {

					$nPercentual = ($aDadosRelatorio[$iCodigoAgrupador]->aFaixas[$iFaixa]['nValorFaixa'] * 100 / $aTotalizadores[$iCodigoAgrupador]['nValorTotal']);

					/**
					 * Total de funcionarios do agrupador
					 */
					$aDadosRelatorio[$iCodigoAgrupador]->aFaixas[$iFaixa]['iTotalFuncionarios'] = count( $aTotalizadores[$iCodigoAgrupador]["aFaixas"][$iFaixa]);

					/**
					 * Percentual do valor total da faixa referente ao valor total do agrupador
					 */
					$aDadosRelatorio[$iCodigoAgrupador]->aFaixas[$iFaixa]['nPercentual'] = round( $nPercentual, 2 );
				}
			}


			$aAgrupador = array('geral'   => 'Geral',
			                    'regime'  => 'Regime',
			                    'lotacao' => 'Lotação',
			                    'cargo'   => 'Cargo');

			$sAgrupador = $aAgrupador[$oParametros->sQuebraRelatorio];
			$sArquivo   = 'tmp/relatorio_faixa_salarial_'. date('Y-m-d_H:i') . '_' . db_getsession('DB_login');

			/**
			 * Nao encontrou nenhum registro para o grupo dentro do periodo da folha(mes e ano)
			 */
			if ( count($aDadosRelatorio) == 0 ) {
				throw new Exception('Nenhum registro encontrado para o período escolhido.');
			}

			switch ( $oParametros->sFormatoRelatorio ) {

				case 'csv' :

					$sArquivo .= '.csv';
					include_once 'pes3_faixasSalariais002CSV.php';
				break;

				case 'pdf' :

					$sArquivo .= '.pdf';
					include_once 'pes3_faixasSalariais002PDF.php';
				break;

				default:
					throw new Exception('Nenhum formato de relatório escolhido');
				break;
			}

			$oRetorno->sArquivo = $sArquivo;

		break;

    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }

  $oRetorno->sMensagem = urlencode($oRetorno->sMensagem);

  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());

  echo $oJson->encode($oRetorno);
}