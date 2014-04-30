<?php
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson    = new services_json();

$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();

$oRetorno->status  = 1;

$oRetorno->message = 1;

switch($oParam->exec) {

	case 'getDadosExportacao':

		include('classes/db_aguacoletorexporta_classe.php');
		include('classes/db_aguacoletorexportadados_classe.php');

		try{
				
			$oDaoAguaColetorExporta = new cl_aguacoletorexporta();
			$sCampos = "x49_sequencial, x49_aguacoletor, x46_descricao, x49_instit, x49_anousu, x49_mesusu, x49_situacao";
			$sSqlAguaColetorExporta = $oDaoAguaColetorExporta->sql_query(null, "*", "x49_sequencial", "x49_sequencial = $oParam->codExportacao");
			$rsAguaColetorExporta   = $oDaoAguaColetorExporta->sql_record($sSqlAguaColetorExporta);
				
			if($oDaoAguaColetorExporta->numrows > 0) {

				$oAguaColetorExporta 		= db_utils::fieldsMemory($rsAguaColetorExporta, 0);
				$oRetorno->x49_sequencial 	= $oAguaColetorExporta->x49_sequencial;
				$oRetorno->x49_aguacoletor  = $oAguaColetorExporta->x49_aguacoletor;
				$oRetorno->x46_descricao    = $oAguaColetorExporta->x46_descricao;
				$oRetorno->x49_instit		= $oAguaColetorExporta->x49_instit;
				$oRetorno->x49_anousu		= $oAguaColetorExporta->x49_anousu;
				$oRetorno->x49_mesusu		= $oAguaColetorExporta->x49_mesusu;
				$oRetorno->x49_situacao		= $oAguaColetorExporta->x49_situacao;

			}
				
			$oDaoAguaColetorExportaDados = new cl_aguacoletorexportadados();
				
			$sCampos = "x50_rota, x06_descr, x50_codlogradouro, x50_nomelogradouro, x07_nroini, x07_nrofim, count(distinct x50_sequencial)";
			$sSqlAguaColetorExportaDados = $oDaoAguaColetorExportaDados->sql_query_dados(null, $sCampos, "x50_rota", "x50_aguacoletorexporta = $oAguaColetorExporta->x49_sequencial group by x50_rota, x06_descr, x50_codlogradouro, x50_nomelogradouro, x07_nroini, x07_nrofim");
			$rsAguaColetorExportaDados   = $oDaoAguaColetorExportaDados->sql_record($sSqlAguaColetorExportaDados);
			if($oDaoAguaColetorExportaDados->numrows > 0) {
				for($i = 0; $i < $oDaoAguaColetorExportaDados->numrows; $i++) {
					$oRetorno->aRotasRuas[] = db_utils::fieldsMemory($rsAguaColetorExportaDados, $i);
				}

			}
			
				
		}catch (Exception $eErro) {

			$oRetorno->status  = 2;
			$oRetorno->message = urlencode($eErro->getMessage());

		}

		echo $oJson->encode($oRetorno);

		break;
		
	case 'vericaRotaRuaSituacao':
    include('classes/db_aguacoletorexporta_classe.php');
    include('classes/db_aguacoletorexportadados_classe.php');
    
    try {
    	
    	$oDaoAguaColetorExportaDados = new cl_aguacoletorexportadados();
    	$sWhere                      = "x49_anousu = $oParam->anousu and x49_mesusu = $oParam->mesusu and x50_rota = $oParam->rota and x50_codlogradouro = $oParam->logradouro and x49_situacao  = 1";
    	$sSqlAguaColetorExportaDados = $oDaoAguaColetorExportaDados->sql_query_dados(null, "count(*)", null, $sWhere);
    	$rsAguaColetorExportaDados   = $oDaoAguaColetorExportaDados->sql_record($sSqlAguaColetorExportaDados);
    	$oAguaColetorExportaDados    = db_utils::fieldsMemory($rsAguaColetorExportaDados, 0);
      $oRetorno->count             = $oAguaColetorExportaDados->count;
      
      $oDaoAguaColetorExportaDadosLeitura = db_utils::getDao('aguacoletorexportadadosleitura');
      
      $sWhere = "    b.x49_anousu        = {$oParam->anousu}
                 and b.x49_mesusu        = {$oParam->mesusu}
                 and aguacoletorexportadados.x50_rota          = {$oParam->rota} 
                 and aguacoletorexportadados.x50_codlogradouro = {$oParam->logradouro}
                 and x21_tipo            = 3
                 and x21_status          = 1";
      
      $sSqlAguaColetorExportaDadosLeitura = $oDaoAguaColetorExportaDadosLeitura->sql_query(null, 'count(*)', null, $sWhere);
      $rsAguaColetorExportaDadosLeitura   = $oDaoAguaColetorExportaDadosLeitura->sql_record($sSqlAguaColetorExportaDadosLeitura);
      $oAguaColetorExportaDadosLeitura    = db_utils::fieldsMemory($rsAguaColetorExportaDadosLeitura, 0);
      
      $oRetorno->iQteLeiturasLog = $oAguaColetorExportaDadosLeitura->count;
      
    	
    }catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }

    echo $oJson->encode($oRetorno);
    
		break;
}

?>