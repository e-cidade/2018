<?php

/**
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");  


$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

define('MENSAGEM','recursoshumanos.pessoal.pes4_rhdirfparametros.');

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "salvar":

      $oDaoDirfParametros = db_utils::getDao('rhdirfparametros');

      /**
       * Valida para não duplicar registros com o mesmo ano base
       */
      $sSql = $oDaoDirfParametros->sql_query_file( null, 
                                                   "*", 
                                                   null,
                                                   " rh132_anobase = {$oParam->iAnoBase} "
                                                   . ($oParam->iSequencial ? " and rh132_sequencial <> {$oParam->iSequencial}" : "") );
      $oDaoDirfParametros->sql_record($sSql);

      if ($oDaoDirfParametros->numrows > 0) {
        throw new BusinessException( _M( MENSAGEM . "ano_base_duplicado") );
      }

      $oDaoDirfParametros->rh132_sequencial    = null;
      $oDaoDirfParametros->rh132_anobase       = $oParam->iAnoBase;
      $oDaoDirfParametros->rh132_valorminimo   = $oParam->iValorMinimo;
      $oDaoDirfParametros->rh132_codigoarquivo = addslashes( $oParam->sCodigoArquivo );

      if (empty($oParam->iSequencial)) {
        
        $oDaoDirfParametros->incluir(null);
        if ($oDaoDirfParametros->erro_status == "0") {
          throw new BusinessException( _M(MENSAGEM . 'erro_incluir') );
        }
      } else {

        $oDaoDirfParametros->rh132_sequencial = $oParam->iSequencial;
        
        $oDaoDirfParametros->alterar($oParam->iSequencial);
        if ($oDaoDirfParametros->erro_status == "0") {
          throw new BusinessException( _M( MENSAGEM.'erro_alterar' ) );
        }
      }
      
      $oRetorno->sMessage = _M(MENSAGEM.'incluir_sucesso');
    break;

    case "excluir":
      $oDaoDirfParametros = db_utils::getDao('rhdirfparametros');

      $oDaoDirfParametros->rh132_sequencial = $oParam->iSequencial;
      $oDaoDirfParametros->excluir($oParam->iSequencial);

      if ($oDaoDirfParametros->erro_status == "0") {
        throw new BusinessException( _M( MENSAGEM.'erro_excluir' ) );
      }

      $oRetorno->sMessage = _M( MENSAGEM . 'exclusao_sucesso' );
    break;

    case "getDados":

      $oDaoDirfParametros = db_utils::getDao('rhdirfparametros');

      if(empty($oParam->iSequencial)){
        throw new BusinessException(_M(MENSAGEM.'sequencial_invalido'));
      }

      $sSqlDirfParametros = $oDaoDirfParametros->sql_query($oParam->iSequencial);
      $rsDirfParametros   = db_query($sSqlDirfParametros);
      $oRetorno->oDados   = db_utils::getCollectionByRecord($rsDirfParametros);

    break;

    case "getParametros":

      $oDaoDirfParametros = db_utils::getDao('rhdirfparametros');

      $sSqlDirfParametros = $oDaoDirfParametros->sql_query(null, "*", "rh132_anobase desc");
      $rsDirfParametros   = db_query($sSqlDirfParametros);
      $oRetorno->oDados   = db_utils::getCollectionByRecord($rsDirfParametros);

    break;

    case "getAnos":

      $iAnoFolha          = DBPessoal::getAnoFolha()-1;
      $oDaoDirfParametros = db_utils::getDao('rhdirfparametros');
      $sSqlDirfParametros = $oDaoDirfParametros->sql_query(null, "rh132_anobase", "rh132_anobase desc limit 5", "rh132_anobase <= {$iAnoFolha}");
      $rsDirfParametros   = db_query($sSqlDirfParametros);

      if (pg_num_rows($rsDirfParametros) == 0) {
        throw new BusinessException(_M(MENSAGEM.'parametros_nao_configurados'));
      }

      $oAnos = db_utils::getCollectionByRecord($rsDirfParametros);
      $aAnos = array();

      foreach ($oAnos as $oAno) {
        $aAnos[] = $oAno->rh132_anobase;
      }

      $oRetorno->oDados = $aAnos;

    break;

    case "getValorAno":

      $oDaoDirfParametros       = db_utils::getDao('rhdirfparametros');
      $sSqlDirfParametros       = $oDaoDirfParametros->sql_query(null, 'rh132_valorminimo, rh132_codigoarquivo', "", "rh132_anobase = {$oParam->iAno}");
      $rsDirfParametros         = db_query($sSqlDirfParametros);
      $oValorMinimo             = db_utils::fieldsMemory($rsDirfParametros, 0);
      $oRetorno->iValor         = db_formatar($oValorMinimo->rh132_valorminimo,'v');
      $oRetorno->sCodigoArquivo = $oValorMinimo->rh132_codigoarquivo;
    break;
  }
  
  db_fim_transacao(false);
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode( $eErro->getMessage() );
}

$oRetorno->sMessage = utf8_encode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);
?>