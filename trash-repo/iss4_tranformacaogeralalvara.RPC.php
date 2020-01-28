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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("model/issqn/alvara/MovimentacaoAlvaraFactory.model.php");
require_once("std/db_stdClass.php");

$oPost 			 = db_utils::postMemory($_POST);
$oJson  		 = new Services_JSON();
$sJson       = str_replace("\\", "",$oPost->json );
$oParametros = $oJson->decode($sJson);

$oRetorno 	 = new stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
	
try {

	switch ($oParametros->metodo) {

		case 'consultaAlvaras':

      $oDaoAlvaras = db_utils::getDao('issmovalvara');

			$sSelect = "q120_sequencial, q123_inscr, z01_nome, q120_issalvara, q120_dtmov, (q120_dtmov + q120_validadealvara) as data_validade";

      /**
       * Verifica se necessita realizar a busca pela dataValidadeInicial e Final ou somente pela inicial.
       */
      if ( $oParametros->sDataFinal == '' && $oParametros->sDataInicial == '')  {
        $sWhere  = "q123_isstipoalvara = '{$oParametros->iTipoAlvara}' AND q120_isstipomovalvara <> 2";
      } else {

        $sWhere  = "(q120_dtmov + q120_validadealvara) between '{$oParametros->sDataInicial}' and '{$oParametros->sDataFinal}' ";
        $sWhere .= "and q123_isstipoalvara = '{$oParametros->iTipoAlvara}' AND q120_isstipomovalvara <> 2";
      }

			$sSqlAlvaras = $oDaoAlvaras->sql_queryAlvarasTransformacao($sSelect, $sWhere);
			$rsAlvara    = db_query($sSqlAlvaras);

			if ( !$rsAlvara ) {
				throw new Exception("Erro ao buscar alvarás.\nErro técnico: ". pg_last_error());	
			}

      if ( pg_num_rows($rsAlvara) == 0 ) {
        throw new Exception("Nenhum alvará encontrado para os filtros informados.");	
      }

      $aAlvaras = db_utils::getCollectionByRecord($rsAlvara);
			$oRetorno->dados = $aAlvaras;
	  break;

    case 'transformarAlvaras' :

      db_inicio_transacao();

      $sObservacao = db_stdClass::normalizeStringJson($oParametros->sDescricaoAlvara);

      foreach ( $oParametros->aAlvaras as $oDadosAlvara ) {

        $oAlvara = new Alvara($oDadosAlvara->iAlvara);

        $oTransformacaoAlvara  = $oAlvara->incluirMovimentacao( MovimentacaoAlvara::TIPO_TRANSFORMACAO );
        $oTransformacaoAlvara->setValidadeAlvara($oParametros->iDiasVencimento);
        $oTransformacaoAlvara->setTipoTransformacao($oParametros->iTipoAlvara);
        $oTransformacaoAlvara->setDataMovimentacao( date('Y-m-d' , db_getsession('DB_datausu')) );
        $oTransformacaoAlvara->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );
        $oTransformacaoAlvara->setObservacao($sObservacao);
        $oTransformacaoAlvara->processar();
      } 

      $oRetorno->sMensagem = "Alvarás transformados com sucesso.";
      db_fim_transacao(false);

    break;

	}

} catch ( Exception $oErro ) {

	$oRetorno->iStatus   = 2;
	$oRetorno->sMensagem = $oErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);