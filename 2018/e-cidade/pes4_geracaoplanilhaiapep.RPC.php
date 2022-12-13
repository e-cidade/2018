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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");  
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

$oJson               = new services_json();
$oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define("MENSAGENS", "recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.");

try {
	
	switch ( $oParametros->sExecucao ) {
	
		case "gerarPlanilhas":
			
			$oGeracaoPlanilhaIapep = new GeracaoPlanilhaIapep();

			$sCompetencia    = $oParametros->iAno . '/' .$oParametros->iMes;
			$aArquivos       = array();
			$iPosicao        = 0;
			
			if ( !empty ($oParametros->sTipoSalario13 ) ) {
				
				$aNomeArquivos                         = $oGeracaoPlanilhaIapep->geraPlanilha($oParametros, $sCompetencia, '13_salario', true );
				foreach ($aNomeArquivos as $sNomeArquivo) {
					$iArrayPosicao                     = $iPosicao++;
					$aArquivos[$iArrayPosicao]['url']  = $sNomeArquivo;
					$aArquivos[$iArrayPosicao]['nome'] = $sNomeArquivo;
				}
			}
			
			if ( !empty ($oParametros->sTipoSalario ) ) {
				
				$aNomeArquivos                      = $oGeracaoPlanilhaIapep->geraPlanilha( $oParametros, $sCompetencia, 'salario', false );
				foreach ($aNomeArquivos as $sNomeArquivo) {
					$iArrayPosicao                     = $iPosicao++;
					$aArquivos[$iArrayPosicao]['url']  = $sNomeArquivo;
					$aArquivos[$iArrayPosicao]['nome'] = $sNomeArquivo;
				}
			}
			
			if ( !empty ($oParametros->sTipoTotalizador ) ) {
				
				try {
					$iArrayPosicao                     = $iPosicao++;
					$sNomeArquivo                      = $oGeracaoPlanilhaIapep->geraPDFTotalizador( $oParametros, $sCompetencia );
					$aArquivos[$iArrayPosicao]['url']  = $sNomeArquivo;
					$aArquivos[$iArrayPosicao]['nome'] = $sNomeArquivo;
				} catch (BusinessException $e) {
					//Nenhum registro encontrado
				}
			}
			
			if (empty($aArquivos)) {
				throw new BusinessException( _M( MENSAGENS . "nenhum_registro_planilha_encontrado" ) );
				
			}

			$oRetorno->aArquivosPlanilha = $aArquivos; 
			
		break;
	}
	
} catch (Exception $oErro) {

	db_fim_transacao(true);
	$oRetorno->iStatus   = 2;
	$oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);
