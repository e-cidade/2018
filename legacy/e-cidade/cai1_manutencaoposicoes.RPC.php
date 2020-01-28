<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Libs necessárias
 */
require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/JSON.php';  
require_once 'dbforms/db_funcoes.php';

/**
 * Tratamento de erros 
 */
db_app::import('exceptions.*');

$oJson                  = new services_json();
$oRetorno               = new stdClass();

$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"])); 
$oRetorno->iStatus      = 1;
$oRetorno->sMensagem    = '';

try {
	
  switch ($oParametros->exec) {

		/**
		 * --------------------------------------------------------------------------------------------
		 * BUSCA OS DADOS DO LAYOUT
		 * --------------------------------------------------------------------------------------------
		 */
		case 'getDadosLayout' :

			$oDaoCadban         = db_utils::getDao('cadban');

			/**
			 * Array com os dados do header do arquivo
			 */
			$aDadosLayoutHeader   = array();

			/**
			 * Array com os dados dos detalhes do arquivo
			 */
			$aDadosLayoutDetalhes = array();


			$sSqlDadosLayout = $oDaoCadban->sql_query($oParametros->iCodigoBanco);
			$rsDadosLayout   = $oDaoCadban->sql_record($sSqlDadosLayout); 

			if ( $oDaoCadban->numrows == 0 ) {
				throw new Exception ( 'Erro na consulta dos dados do layout \n'.$oDaoCadban->erro_msg );
			}

			/**
			 * Obj com os dados da tabela cadban 
			 */
			$oDadosLayout = db_utils::fieldsMemory($rsDadosLayout, 0);

			/**
			 * --------------------------------------------------------------------------------------------------
			 * HEADER DO ARQUIVO
			 * --------------------------------------------------------------------------------------------------
			 */	 

			/**
			 * Posição banco
			 */	 
			$oPosicaoBancao                  = new stdClass;
			$oPosicaoBancao->nomeCampo       = 'k15_posbco'; 
			$oPosicaoBancao->campo           = 'Posição banco'; 
			$aDadosLayoutHeader[]            = $oPosicaoBancao;

			/**
			 * Data movimento
			 * - Dia
			 */	 
			$oDiaMovimento            = new stdClass;
			$oDiaMovimento->nomeCampo = 'k15_posdta'; 
			$oDiaMovimento->campo     = 'Dia movimento'; 
			$aDadosLayoutHeader[]     = $oDiaMovimento;

			/**
			 * Data movimento
			 * - Mes
			 */	 
			$oMesMovimento            = new stdClass;
			$oMesMovimento->nomeCampo = 'k15_pdmes'; 
			$oMesMovimento->campo     = 'Mês movimento'; 
			$aDadosLayoutHeader[]     = $oMesMovimento;

			/**
			 * Data movimento
			 * - Ano
			 */	 
			$oAnoMovimento            = new stdClass;
			$oAnoMovimento->nomeCampo = 'k15_pdano'; 
			$oAnoMovimento->campo     = 'Ano movimento'; 
			$aDadosLayoutHeader[]     = $oAnoMovimento;
			
			/**
			 * Percorre os dados do HEADER e corrige retorno com urlEncode e substr
			 * - campo          - encode para retorno pelo json
			 * - posicaoInicial - pega os 3 primeiro caracteres
			 * - tamanho        - pega os 3 ultimos caracteres
			 */	 
			foreach ($aDadosLayoutHeader as $oHeader) {
				
				$sNomeCampo      = $oHeader->nomeCampo;
				$oHeader->campo  = urlEncode($oHeader->campo);
				$sPosicaoInicial = null;
				$sTamanho        = null;

				if ( strlen($oDadosLayout->$sNomeCampo) == 6 ) {

					$sPosicaoInicial = substr($oDadosLayout->$sNomeCampo, 0, -3);
					$sTamanho        = substr($oDadosLayout->$sNomeCampo, 3);
				}

				$oHeader->posicaoInicial = $sPosicaoInicial;
				$oHeader->tamanho        = $sTamanho; 
			}

			/**
			 * --------------------------------------------------------------------------------------------------
			 * DETALHES DO ARQUIVO 
			 * --------------------------------------------------------------------------------------------------
			 */	 

			/**
			 * Numbanco
			 */	 
			$oNumbanco              = new stdClass;
			$oNumbanco->nomeCampo   = 'k15_numbco'; 
			$oNumbanco->campo       = 'Numbanco'; 
			$aDadosLayoutDetalhes[] = $oNumbanco;

			/**
			 * Numpre
			 */	 
			$oNumpre                = new stdClass;
			$oNumpre->nomeCampo     = 'k15_numpre'; 
			$oNumpre->campo         = 'Numpre'; 
			$aDadosLayoutDetalhes[] = $oNumpre;

			/**
			 * Numpar
			 */	 
			$oNumpar                = new stdClass;
			$oNumpar->nomeCampo     = 'k15_numpar'; 
			$oNumpar->campo         = 'Numpar'; 
			$aDadosLayoutDetalhes[] = $oNumpar;

			/**
			 * Data lançamento
			 * - Dia
			 */	 
			$oDiaLancamento            = new stdClass;
			$oDiaLancamento->nomeCampo = 'k15_poslan'; 
			$oDiaLancamento->campo     = 'Dia de lançamento'; 
			$aDadosLayoutDetalhes[]    = $oDiaLancamento;

			/**
			 * Data lançamento
			 * - Mes
			 */	 
			$oMesLancamento            = new stdClass;
			$oMesLancamento->nomeCampo = 'k15_plmes'; 
			$oMesLancamento->campo     = 'Mês de lançamento'; 
			$aDadosLayoutDetalhes[]    = $oMesLancamento;

			/**
			 * Data lançamento
			 * - Ano
			 */	 
			$oAnoLancamento            = new stdClass;
			$oAnoLancamento->nomeCampo = 'k15_plano'; 
			$oAnoLancamento->campo     = 'Ano de lançamento'; 
			$aDadosLayoutDetalhes[]    = $oAnoLancamento;

			/**
			 * Data pagamento
			 * - Dia
			 */	 
			$oDiaPagamento            = new stdClass;
			$oDiaPagamento->nomeCampo = 'k15_pospag'; 
			$oDiaPagamento->campo     = 'Dia de pagamento'; 
			$aDadosLayoutDetalhes[]   = $oDiaPagamento;

			/**
			 * Data lançamento
			 * - Mes
			 */	 
			$oMesPagamento            = new stdClass;
			$oMesPagamento->nomeCampo = 'k15_ppmes'; 
			$oMesPagamento->campo     = 'Mês de pagamento'; 
			$aDadosLayoutDetalhes[]   = $oMesPagamento;

			/**
			 * Data lançamento
			 * - Ano
			 */	 
			$oAnoPagamento            = new stdClass;
			$oAnoPagamento->nomeCampo = 'k15_ppano'; 
			$oAnoPagamento->campo     = 'Ano de pagamento'; 
			$aDadosLayoutDetalhes[]   = $oAnoPagamento;
			
			/**
			 * Posicao valor
			 */	 
			$oPosicaoValor            = new stdClass;
			$oPosicaoValor->nomeCampo = 'k15_posvlr'; 
			$oPosicaoValor->campo     = 'Posição valor'; 
			$aDadosLayoutDetalhes[]   = $oPosicaoValor;

			/**
			 * Posicao acrescimo
			 */	 
			$oPosicaoAcrescimo            = new stdClass;
			$oPosicaoAcrescimo->nomeCampo = 'k15_posacr'; 
			$oPosicaoAcrescimo->campo     = 'Posição acrescimo'; 
			$aDadosLayoutDetalhes[]       = $oPosicaoAcrescimo;

			/**
			 * Posicao desconto
			 */	 
			$oPosicaoDesconto            = new stdClass;
			$oPosicaoDesconto->nomeCampo = 'k15_posdes'; 
			$oPosicaoDesconto->campo     = 'Posição desconto'; 
			$aDadosLayoutDetalhes[]      = $oPosicaoDesconto;

			/**
			 * Posicao cedente
			 */	 
			$oPosicaoCedente            = new stdClass;
			$oPosicaoCedente->nomeCampo = 'k15_posced'; 
			$oPosicaoCedente->campo     = 'Posição cedente'; 
			$aDadosLayoutDetalhes[]     = $oPosicaoCedente;

			/**
			 * Posicao abatimento
			 */	 
			$oPosicaoAbatimento            = new stdClass;
			$oPosicaoAbatimento->nomeCampo = 'k15_poscon'; 
			$oPosicaoAbatimento->campo     = 'Posição abatimento'; 
			$aDadosLayoutDetalhes[]        = $oPosicaoAbatimento;

			/**
			 * Data de credito
			 * - Dia
			 */	 
			$oDiaCredito            = new stdClass;
			$oDiaCredito->nomeCampo = 'k15_diacredito'; 
			$oDiaCredito->campo     = 'Dia de crédito'; 
			$aDadosLayoutDetalhes[] = $oDiaCredito;

			/**
			 * Data de credito
			 * - Mes
			 */	 
			$oMesCredito            = new stdClass;
			$oMesCredito->nomeCampo = 'k15_mescredito'; 
			$oMesCredito->campo     = 'Mês de crédito'; 
			$aDadosLayoutDetalhes[] = $oMesCredito;

			/**
			 * Data de credito
			 * - Ano
			 */	 
			$oAnoCredito            = new stdClass;
			$oAnoCredito->nomeCampo = 'k15_anocredito'; 
			$oAnoCredito->campo     = 'Ano de crédito'; 
			$aDadosLayoutDetalhes[] = $oAnoCredito;

			/**
			 * Percorre os dados dos DETALHES e corrige retorno com urlEncode e substr
			 * - campo          - encode para retorno pelo json
			 * - posicaoInicial - pega os 3 primeiro caracteres
			 * - tamanho        - pega os 3 ultimos caracteres
			 */	 
			foreach ($aDadosLayoutDetalhes as $oDetalhes) {

				$sNomeCampo        = $oDetalhes->nomeCampo; 
				$oDetalhes->campo  = urlEncode($oDetalhes->campo);
				$sPosicaoInicial   = '000';
				$sTamanho          = '000';

				/**
				 * Verifica se a quantidade de caracteres for igual a 6 pega os dados, senão usa valor '000'
				 */	 
				if ( strlen($oDadosLayout->$sNomeCampo) == 6 ) {

					$sPosicaoInicial = substr($oDadosLayout->$sNomeCampo, 0, -3);
					$sTamanho        = substr($oDadosLayout->$sNomeCampo, 3);
				}

				$oDetalhes->posicaoInicial = $sPosicaoInicial;   
				$oDetalhes->tamanho        = $sTamanho;          
			}

			/**
			 * Retorna os dados do header e detalhes do arquivo txt  
			 */
			$oRetorno->aDadosLayoutHeader   = $aDadosLayoutHeader;
			$oRetorno->aDadosLayoutDetalhes = $aDadosLayoutDetalhes;

		break;

		/**
		 * --------------------------------------------------------------------------------------------
		 * SALVA OS DADOS DO LAYOUT
		 * --------------------------------------------------------------------------------------------
		 */
		case 'alterarDadosLayout' :

			db_inicio_transacao();

			$oDaoCadban = db_utils::getDao('cadban');

			/**
			 * Define o valor dos campos do header que serão alterados  
			 */
			foreach ($oParametros->oDadosHeader as $sCampo => $sValor) {
				$oDaoCadban->$sCampo = substr($sValor, 0, 6);
			}

			/**
			 * Define o valor dos campos de detalhes que serao alterados 
			 */
			foreach ($oParametros->oDadosDetalhes as $sCampo => $sValor) {
				$oDaoCadban->$sCampo = substr($sValor, 0, 6);
			}

			if (isset($oParametros->iTamanhoRegistro)) {
			  $oDaoCadban->k15_taman =  $oParametros->iTamanhoRegistro;
			}
			$oDaoCadban->k15_codigo = $oParametros->iCodigoBanco;
			$oDaoCadban->alterar($oParametros->iCodigoBanco);

			if ($oDaoCadban->erro_status == 0) {
				throw new DBException ("Erro na alteração do layout: \n");
			}

			$oRetorno->sMensagem = $oDaoCadban->erro_msg;

			/**
			 * Commit
			 */	 
			db_fim_transacao(false);

		break;
				
    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }

  $oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
  echo $oJson->encode($oRetorno);

} catch (DBException $eErro){
  
	/**
	 * Rollback  
	 */
	db_fim_transacao(true);

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
	
  echo $oJson->encode($oRetorno);
} catch (Exception $eErro){
  
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}