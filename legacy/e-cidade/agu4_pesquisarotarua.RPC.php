<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
require(modification("libs/db_sessoes.php"));
require(modification("libs/JSON.php"));

$oPost    = db_utils::postMemory($_POST);
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\", "", $oPost->json));
$oRetorno = (object) array(
	"status"  => 1,
	"message" => ""
);

try {

	switch ($oParam->exec) {

		case 'perquisarPorRota':

			$oDaoAguaRotaRua  = new cl_aguarotarua;
			$sWhere = "x07_codrua = {$oParam->rua} and {$oParam->nro} between x07_nroini and x07_nrofim";
			$sSqlAguaRotaRua  = $oDaoAguaRotaRua->sql_query(null, 'x07_codrota, x06_descr', null, $sWhere);
			$rsDaoAguaRotaRua = $oDaoAguaRotaRua->sql_record($sSqlAguaRotaRua);

			if (!$rsDaoAguaRotaRua) {
				throw new DBException("Não foi possível encontrar informações de rota.");
			}

			if ($oDaoAguaRotaRua->numrows  == 0) {
				throw new Exception("Nenhuma rota foi encontrada para o logradouro informado.");
			}

			$oAguaRotaRua = db_utils::fieldsMemory($rsDaoAguaRotaRua, 0);
			$oRetorno->status      = 1;
			$oRetorno->iCodRota    = $oAguaRotaRua->x07_codrota;
			$oRetorno->sDescricao  = $oAguaRotaRua->x06_descr;

			break;

		default:
			throw new Exception("Nenhuma opção definida.");
	}

} catch (Exception $exception) {

	$oRetorno->status = 0;
	$oRetorno->message = $exception->getMessage();
}

echo $oJson->encode($oRetorno);