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

define('PATH_JOB','jobs/tributario/juridico/integracao_tjrj/');

require_once ("libs/JSON.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

require_once ('model/configuracao/Task.model.php');
require_once ('interfaces/iTarefa.interface.php');

/**
 * Classe de Integração de Pagamento do Processos do Foro
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @package Jobs/Juridico
 * @revision $Author: dbrafael.nery $
 * @version $Revision: 1.4 $
 */
class IntegracaoWebServiceTJ extends Task implements iTarefa {

  public function iniciar()  {
    
    parent::iniciar();
    
    try {
      
     require_once (PATH_JOB."libs/db_conecta_integracao_tj.php");
      
      db_query($conn, "select fc_startsession()");
      $oSession = new _TaskSession();
      $oSession->DB_acessado    = 1;
      $oSession->DB_id_usuario  = 1;
      $oSession->DB_datausu     = time();
      $oSession->DB_anousu      = date("Y");
      $oSession->DB_instit      = $oDadosIniFile->iInstituicao; //db_conecta_integracao_tj
      
      parent::setSession($oSession);
      
      db_inicio_transacao();
      $this->log("Iniciando Integracao");
      /**
       * Importanto e instanciando cliente do webservice e recibo
       */
      db_app::import('juridico.RemessaWebServiceTJ'); 
      db_app::import('recibo');

      $aRemessasWebService = RemessaWebServiceTJ::getRemessas(1);

      $this->log("Encontradas {$aRemessasWebService} para o Processamento");


      foreach ( $aRemessasWebService as $oRemessa ) {

        $this->log("Iniciando Processamento da Remessa {$oRemessa->getCodigoRemessa()} para o Processamento.");
        $oRemessa->processar();
        $this->log("Fim do Processamento da Remessa {$oRemessa->getCodigoRemessa()}.");
      }
      db_query($conn, "commit;");

    } catch( Exception $eErro ) {
      
     db_query($conn, "rollback;");
      echo "Erro ao Efetuar Integracao:".$eErro->getMessage();
      $this->log("Erro ao Efetuar Integracao: ".$eErro->getMessage(), DBLog::LOG_ERROR);
    }
    
    pg_close($conn);
    $this->log("Finalizando Integracao");
    parent::terminar();
  }

  public function cancelar() {
  }
  public function abortar()  {
  }
}