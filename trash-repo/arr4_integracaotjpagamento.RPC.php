<?
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
 * @version   $Revision: 1.3 $
 * @revision  $Author: dbrafael.nery $
 */
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");

require_once ("dbforms/db_funcoes.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();

/**
 * Camada de Tentativas do RPC
 */
try {

  switch ($oParam->sExec) {

    /**
     * Reemissao do TXT
     */
    case "incluiRemessa":
      
      db_inicio_transacao();
      /**
       * Importanto e instanciando cliente do webservice e recibo
       */
      db_app::import('juridico.RemessaWebServiceTJ');
      db_app::import('recibo');
      db_app::import('configuracao.UsuarioSistema');
      

      if ( empty($oParam->iCodigoArquivo) ) {
        throw new BusinessException("Código do Arquivo não Informado", 1);
      }

      $oDaoDisArq    = db_utils::getDao('disarq');
      $sSqlRegistros = $oDaoDisArq->sql_query_integracaoTJ("v77_numnov", "disarq.codret = {$oParam->iCodigoArquivo}");
      $rsRegistros   = db_query($sSqlRegistros);

      /**
       * Validando se a query foi executada com sucesso e se existem registros
       */
      if (!$rsRegistros) {
        throw new DBException( 'Erro ao resgatar dados do arquivo. \n' . pg_last_error(), 2);
      }
      /**
       * Validando se a query foi executada com sucesso e se existem registros
       */
      if (pg_num_rows($rsRegistros) == 0) {
        throw new DBException( 'Nenhum Registro Encontrado. \n' . pg_last_error(), 2);
      }


      /**
       * Percorrendo os dados da query enviado ao webservice
       */
      $aDados = db_utils::getCollectionByRecord($rsRegistros, true, false, true);
      $oRemessaWebService = new RemessaWebServiceTJ();
      $oRemessaWebService->setCodigoSistemaExterno( 2 );
      $oRemessaWebService->setDataCriacao         ( date("Y-m-d", db_getsession("DB_datausu")) );
      $oRemessaWebService->setDescricaoRemessa    ( "Remessa WebService TJ, Arquivo: ".$oParam->iCodigoArquivo );
      $oRemessaWebService->setProcessada          ( false );
      $oRemessaWebService->setUsuario             ( new UsuarioSistema( db_getsession("DB_id_usuario") ) );      
      
      
      foreach ( $aDados as $oDados) {

        $oRecibo            = new recibo(null,null,null,$oDados->v77_numnov);
        $oRemessaWebService->adicionarRecibo($oRecibo);
      }
      $oRemessaWebService->salvar();      
      $oRetorno->sMessage = "Enviado com sucesso.";
    break;
       
    default:
      throw new Exception("Nenhuma Opção Definida", 1);
    break;
  }


  
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  db_fim_transacao(false);
  
} catch (Exception $eErro){
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}