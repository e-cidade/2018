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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("model/pessoal/relatorios/RelatorioFolhaSinteticoAnalitico.model.php");

$oJson               = new services_json();
$oParam              = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno            = new stdClass();
$oRetorno->status    = 1;
$oRetorno->message   = 1;
$lErro               = false;
$sMensagem           = "";

$oDadosCsv           = new  RelatorioFolhaSinteticoAnalitico();
$aRubricas           = array();
$iTipoFolha          = "";
$iAgrupador          = "";
$aSelecionados       = "";


try {

switch($oParam->exec) {
	
	case 'gerarCsv' :
		
		$aRubricasFamilia = array( '0014'  ,
															 'R918'  ,
															 'R920'  ,
															 'R921'  ,
															 'R917'  ,
															 'R917'  ,
															 'R919'  ,
															 'R920'  ,
															 'R921'  ,
															 '0159'  ,
															 '0419'  ,
															 '0130'  ,
															 '0143'  ,
															 'R919'  ,
															 'R918'  
		);

		$aRubricaPrevidencia = array("R993");
		$aRubricasIrrf       = array("R913", "R914", "R915");

		$sCampos  = "distinct rhpessoal.* ";


		if($oParam->sFaixareg != ""){
		   
		  $oDadosCsv->setFiltroAgrupador(explode(",", $oParam->sFaixareg));
		}elseif($oParam->sFaixalot != ""){
		   
		  $oDadosCsv->setFiltroAgrupador(explode(",", $oParam->sFaixalot));
		}elseif($oParam->sFaixaloc != ""){
		   
		  $oDadosCsv->setFiltroAgrupador(explode(",", $oParam->sFaixaloc));
		}elseif($oParam->sFaixaorg != ""){
		   
		  $oDadosCsv->setFiltroAgrupador(explode(",", $oParam->sFaixaorg));
		}elseif(!empty($oParam->iRegini) && !(empty($oParam->iRegfim))) {
		   
		  $oDadosCsv->setFiltroAgrupador($oParam->iRegini, $oParam->iRegfim);
		}elseif (!empty($oParam->iLotini) && !(empty($oParam->iLotfim))){
		   
		  $oDadosCsv->setFiltroAgrupador($oParam->iLotini, $oParam->iLotfim);
		}elseif (!empty($oParam->iLocini) && !(empty($oParam->iLocfim))){
		   
		  $oDadosCsv->setFiltroAgrupador($oParam->iLocini, $oParam->iLocfim);
		}elseif (!empty($oParam->iOrgini) && !(empty($oParam->iOrgfim))){
		   
		  $oDadosCsv->setFiltroAgrupador($oParam->iOrgini, $oParam->iOrgfim);
		}

		/**
		 * convertemos o tipo de ponto de string para inteiro para
		 * melhor manipulaзгo
		 */
		switch ($oParam->sFolha){
		  case "r14":
		    $iTipoFolha = "1"; //salario
		    break;
		  case "r48":
		    $iTipoFolha = "2"; //complementar
		    break;
		  case "r20":
		    $iTipoFolha = "3"; //rescisao
		    break;
		  case "r35":
		    $iTipoFolha = "4"; //13 salario
		    break;
		  case "r22":
		    $iTipoFolha = "5"; //adiantamento
		    break;
		}
		/**
		 * convertemos o tip de agrupador de string para inteiro para
		 * melhor manipulaзгo
		 */
		switch ($oParam->sTipo){
		  case "g":
		    $iAgrupador = "0"; //Geral
		    break;
		     
		  case "l":
		    $iAgrupador = "1"; //Lotaзгo
		    break;

		  case "o":
		    $iAgrupador = "2"; //Уrgгo
		    break;

		  case "m":
		    $iAgrupador = "3"; //Matrнcula
		    break;

		  case "t":
		    $iAgrupador = "4"; //Locais de trabalho
		    break;
		     
		}

		if($oParam->sAfastado == 'n'){
		  $oDadosCsv->setAfastados(false);
		}

		$oDadosCsv->addTipoFolha   ($iTipoFolha  );
		$oDadosCsv->setAgrupador   ($iAgrupador  );
		$oDadosCsv->setCompetencia ($oParam->iMes, $oParam->iAno);
		$oDadosCsv->setSelecao     ($oParam->sSel);
		$oDadosCsv->setRegime      ($oParam->sReg);
		$oDadosCsv->setCamposQuery ($sCampos     );


		if($iTipoFolha == "2"){
		  
		  // setamos o ponto complementar
		  $oDadosCsv->setCodigoComplementar($oParam->sSemest);
		}

		$aDadosCsv          = $oDadosCsv->getDadosBase();
		$aDadosCsvServidor  = $aDadosCsv->aDadosServidor;
		$aDadosCsvRubricas  = $aDadosCsv->aDadosRubricas;
		$aDadosRubricas     = $aDadosCsv->aRubricas;
		$iDadosRelatorio    = count($aDadosRubricas);


		if($iDadosRelatorio == 0){
		  throw new Exception("Nenhum registro Encontrado");
		}

		if ( $oParam->sAnsin == 'a' ) {
		  $sArquivo     = "tmp/relatorioFolhaPagamentoAnalitico.csv";
		} elseif ( $oParam->sAnsin == 's' ) {
		  $sArquivo     = "tmp/relatorioFolhaPagamentoSintetico.csv";
		} else {
		  throw new Exception("Erro ao selecionar o tipo de relatorio");
		}
		
		$fArquivo     = fopen($sArquivo, "w");

		$aDadosRelatorio["iMatricula"]            = "Matrнcula";
		$aDadosRelatorio["sNome"]                 = "Nome";
		$aDadosRelatorio["sLotacao"]              = "Lotaзгo";
		$aDadosRelatorio["sCargo"]                = "Cargo";
		if ( $oParam->sAnsin == 'a' ) {

		  foreach ( $aDadosRubricas as $oRubrica ) {

		    $aDadosRelatorio["quant_{$oRubrica->rubrica}"] = "Quant_{$oRubrica->rubrica}";
		    $aDadosRelatorio["valor_{$oRubrica->rubrica}"] = "Valor_{$oRubrica->rubrica}";
		  }
		} else {

		  $aDadosRelatorio["nPrevidenciaSintetico"] = "Previdкncia";
		  $aDadosRelatorio["nIrrfSintetico"]        = "I.R.R.F";
		  $aDadosRelatorio["nSalFamiliaSintetico"]  = "Sal.Familнa";
		}

		$aDadosRelatorio["nProventoSintetico"]    = "Proventos";
		$aDadosRelatorio["nDescontoSintetico"]    = "Descontos";
		$aDadosRelatorio["nLiquidoSintetico"]     = "Liquido";

		if ($oParam->sAnsin == 'a') {

			$aDadosRelatorio["dtAfastamento"]      = "Data Afastamento";
			$aDadosRelatorio["dtRetorno"]          = "Data Retorno";
			$aDadosRelatorio["sMotivoAfastamento"] = "Motivo Afastamento";
		}

		fputcsv($fArquivo, $aDadosRelatorio, ";");
		
		/**
		 * Percorre os dados referentes ao servidor
		 */
		foreach ($aDadosCsvServidor as $iMatricula => $oDadosServidor)	{
		   
		  $aDadosRelatorio["iMatricula"]             = $oDadosServidor->matricula_servidor;
		  $aDadosRelatorio["sNome"]                  = $oDadosServidor->nome_servidor;
		  $aDadosRelatorio["sLotacao"]               = $oDadosServidor->codigo_lotacao." - ".$oDadosServidor->descr_lotacao;
		  $aDadosRelatorio["sCargo"]                 = $oDadosServidor->codigo_cargo  ." - ".$oDadosServidor->descr_cargo;
		   
		  if ($oParam->sAnsin == 'a') {

		    foreach ($aDadosRubricas as $oRubrica){
		      $aDadosRelatorio["quant_{$oRubrica->rubrica}"]  = 0;
		      $aDadosRelatorio["valor_{$oRubrica->rubrica}"]  = 0;
		    }
		  } else {
		    
		    $aDadosRelatorio["nPrevidenciaSintetico"]  = 0;
		    $aDadosRelatorio["nIrrfSintetico"]         = 0;
		    $aDadosRelatorio["nSalFamiliaSintetico"]   = 0;
		  }
		  
		  $aDadosRelatorio["nProventoSintetico"]     = 0;
		  $aDadosRelatorio["nDescontoSintetico"]     = 0;
		  $aDadosRelatorio["nLiquidoSintetico"]      = 0;

		  /**
		   * Percorre os dados referentes a folha de pagamento escolhida
		   */
		  foreach ($aDadosCsvRubricas as $sTabelaPonto => $aDadosRubricaFolha) {
		    /**
		     * Percorre as matriculas do servidor selecionado
		     */
		    foreach ($aDadosRubricaFolha[$iMatricula] as $oDadosRubricasSintetico) {
		      /**
		       * Valida se o valor vai ser adiconado a provento ou desconto
		       */
		      switch ($oDadosRubricasSintetico->provento_desconto){
		        case "1":
		          $aDadosRelatorio["nProventoSintetico"]  += $oDadosRubricasSintetico->valor_rubrica;
		          //$aDadosRelatorio["nProventoSintetico"]   = number_format($aDadosRelatorio["nProventoSintetico"], 2, ",", "");
		          break;
		        case "2" :
		          $aDadosRelatorio["nDescontoSintetico"]  += $oDadosRubricasSintetico->valor_rubrica;
		          //$aDadosRelatorio["nDescontoSintetico"]   = number_format($aDadosRelatorio["nDescontoSintetico"], 2, ",", "");
		          break;
		      }
		      /**
		       * Caso seja um relatуrio analнtico mostra em detalhe os valores das rubricas
		       */
		      if ($oParam->sAnsin == 'a') {
		         
		        if ( isset( $aDadosRubricas[$oDadosRubricasSintetico->rubrica] ) ) {
		          
		          $aDadosRelatorio["quant_{$oDadosRubricasSintetico->rubrica}"] = number_format($oDadosRubricasSintetico->quant_rubrica, 2, ",", "");
		          $aDadosRelatorio["valor_{$oDadosRubricasSintetico->rubrica}"] = number_format($oDadosRubricasSintetico->valor_rubrica, 2, ",", "");
		        } else {
		          $aDadosRelatorio["quant_{$oDadosRubricasSintetico->rubrica}"] = "0,00";
		          $aDadosRelatorio["valor_{$oDadosRubricasSintetico->rubrica}"] = "0,00";
		        }
		      } else {
		        /**
		         * somamos todas rubricas referente a salario familia, que estiverem no array
		         */
		        if(in_array($oDadosRubricasSintetico->rubrica, $aRubricasFamilia)){
		          $aDadosRelatorio["nSalFamiliaSintetico"]   += $oDadosRubricasSintetico->valor_rubrica;
		        }
		        /**
		         * somamos todas rubricas referente a previdencia, que estiverem no array
		         */
		        if(in_array($oDadosRubricasSintetico->rubrica, $aRubricaPrevidencia)){
		          $aDadosRelatorio["nPrevidenciaSintetico"] += $oDadosRubricasSintetico->valor_rubrica;
		        }
		        /**
		         * Valida se a rubrica selecionada faz parte do cбlculo de IRRF
		         */
		        if(in_array($oDadosRubricasSintetico->rubrica, $aRubricasIrrf)){
		          $aDadosRelatorio["nIrrfSintetico"]        += $oDadosRubricasSintetico->valor_rubrica;
		        }
		      }
		    }
		  }
		  if ($oParam->sAnsin == 's') {

		    $aDadosRelatorio["nPrevidenciaSintetico"]   = number_format($aDadosRelatorio["nPrevidenciaSintetico"], 2, ",", "");
  		  $aDadosRelatorio["nSalFamiliaSintetico"]    = number_format($aDadosRelatorio["nSalFamiliaSintetico"], 2, ",", "");
  		  $aDadosRelatorio["nIrrfSintetico"]          = number_format($aDadosRelatorio["nIrrfSintetico"], 2, ",", "");
		  }
		  $aDadosRelatorio["nLiquidoSintetico"]       = $aDadosRelatorio["nProventoSintetico"] - $aDadosRelatorio["nDescontoSintetico"];
		  $aDadosRelatorio["nLiquidoSintetico"]       = number_format($aDadosRelatorio["nLiquidoSintetico"], 2, ",", ""); //. " ASASA";
		  
		  $aDadosRelatorio["nProventoSintetico"]   = number_format($aDadosRelatorio["nProventoSintetico"], 2, ",", "");
		  $aDadosRelatorio["nDescontoSintetico"]   = number_format($aDadosRelatorio["nDescontoSintetico"], 2, ",", ""); 

		  if ($oParam->sAnsin == 'a') {

		  	$aDadosRelatorio["dtAfastamento"]      = '';
				$aDadosRelatorio["dtRetorno"]          = '';
				$aDadosRelatorio["sMotivoAfastamento"] = '';

				$sSeparador = '';
		  	foreach ($oDadosServidor->aAfastamentos as $iIndiceAfastamento => $oAfastamento) {

		  		$iIndice = $iIndiceAfastamento+1;

				  $aDadosRelatorio["dtAfastamento"]      .= "{$sSeparador}{$iIndice} - {$oAfastamento->r45_dtafas}";
				  $aDadosRelatorio["dtRetorno"]          .= "{$sSeparador}{$iIndice} - {$oAfastamento->r45_dtreto}";
				  $aDadosRelatorio["sMotivoAfastamento"] .= "{$sSeparador}{$iIndice} - {$oAfastamento->afastamento}";

				  $sSeparador = ', ';
		  	}

		  }
		  /**
		   * se o provento nao for zero acrescentamos ao arquivo, caso contrario nao й necessario apresenta-lo;
		   */
		  if((float)$aDadosRelatorio["nProventoSintetico"] > 0) {
		    fputcsv($fArquivo, $aDadosRelatorio, ";");
		    unset($aDadosRelatorio);
		  }

		}
		
		/**
		 * CRiando legendas para o relatуrio analitico
		 */
		if ( $oParam->sAnsin == 'a' ) {
		  $aLegenda   = array();
		  $aLegenda[] = array(" "," ");
		  $aLegenda[] = array("Rubrica","Descriзгo");
		  foreach ($aDadosRubricas as $oRubrica){
		    $aLegenda[] = array($oRubrica->rubrica, $oRubrica->descr_rubrica);
		  }
		}

		if ( $oParam->sAnsin == 'a' ) {
		  
		  foreach ($aLegenda as $aCSVLegenda) {
		    fputcsv($fArquivo, $aCSVLegenda, ";");
		  }
		}
		
		
		fclose($fArquivo);

		$oRetorno->sArquivo = $sArquivo;
		break;

}
/**
 * Encerrando switch escreve a saida json
 */
echo $oJson->encode($oRetorno);

} catch (Exception $oErro){
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
  echo $oJson->encode($oRetorno);
}

?>