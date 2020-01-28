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
 
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");  
require_once ("dbforms/db_funcoes.php");
require_once("model/cadastro/Construcao.model.php");
require_once("model/cadastro/Imovel.model.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
try {
  
  switch ($oParam->exec) {

    
    /**
     * responsavel por retornar as certidoes cadastradas de uma matricula
     */
    case 'getCertidao' :
    
      require_once("classes/db_certidaoexistencia_classe.php");
      $oDaoCertidaoExistencia = new cl_certidaoexistencia;
    
      $iMatricula                 = $oParam->iMatricula;
      $sCamposCertidaoExistencia  = "j133_sequencial,   ";
      $sCamposCertidaoExistencia .= "j133_iptuconstr,   ";
      $sCamposCertidaoExistencia .= "j133_data,         ";
      $sCamposCertidaoExistencia .= "login,             ";
      $sCamposCertidaoExistencia .= "j133_arquivo       ";
    
      $sSqlCertidaoExistencia = $oDaoCertidaoExistencia->sql_query(null,
                                                                  "{$sCamposCertidaoExistencia}",
                                                                  "j133_sequencial",
                                                                  "j133_matric = {$iMatricula}");
    
      $rsCertidaoExistencia   = $oDaoCertidaoExistencia->sql_record($sSqlCertidaoExistencia);
      $aCertidaoExistencia    = db_utils::getCollectionByRecord($rsCertidaoExistencia,true,false,true);
    
      foreach ($aCertidaoExistencia as $oDadosCertidao) {
    
      $oCertidao = new stdClass();
    
      $oCertidao->j133_sequencial = $oDadosCertidao->j133_sequencial;
      $oCertidao->j133_iptuconstr = $oDadosCertidao->j133_iptuconstr;
      $oCertidao->j133_data       = $oDadosCertidao->j133_data      ;
      $oCertidao->login           = $oDadosCertidao->login          ;
      $oCertidao->j133_arquivo    = $oDadosCertidao->j133_arquivo   ;
      $aDadosRetorno[]            = $oCertidao;
    
      }
      if (count($aDadosRetorno) == 0) {
    
      throw new ErrorException("Nenhuma Certidуo Emitida para Esta Matrэcula.");
      }
      $oRetorno->aDados = $aDadosRetorno;
    
    break;    
    
    /**
     * retorna a lista de construчѕes de uma determinada matricuala
     */
    case "getConstrucoes":
      
      $iMatricula   = $oParam->iMatricula;
      $oImovel      = new Imovel($iMatricula);
      $aConstrucoes = $oImovel->getConstrucoes();

      foreach ($aConstrucoes as $oConstrucao) {
        
        $oDadoConstrucao = new stdClass();
        $oDadoConstrucao->iCodigoConstrucao = $oConstrucao->getCodigoConstrucao();
        $oDadoConstrucao->nArea             = $oConstrucao->getArea();
        $oDadoConstrucao->iAnoConstrucao    = $oConstrucao->getAnoConstrucao();
        $aDadosRetorno[] = $oDadoConstrucao;
      }
      $oRetorno->aDados = $aDadosRetorno;
      
    break;
    
    
    

    /**
     * responsavel pela geraчуo de uma certidao de uma determinada construcao
     * @TODO
     */
    case "geraCertidao":
    	
    	 
    	try {
    		
    		
    		db_query($conn,'BEGIN;');
    	  
    		$iMatricula          = $oParam->iMatricula;
    		$lProcessoSistema    = $oParam->lProcessoSistema == 1 ? true : false;
    		$iConstrucao         = $oParam->iConstrucao;
    		$sObservacao         = $oParam->sObservacao;
    		$iProcesso           = $oParam->iProcesso;
    		$sTitular            = $oParam->sTitular;
    		$dtDataProcesso      = $oParam->dtDataProcesso;
    		
    		$oConstrucao         = new Construcao($iMatricula, $iConstrucao);
    		$oCertidaoExistencia = $oConstrucao->emiteCertidaoExistencia();
    		$oCertidaoExistencia->setCodigoUsuario   ( db_getsession("DB_id_usuario") );
    		$oCertidaoExistencia->setObservacao      ( $sObservacao );
    		$oCertidaoExistencia->setDataEmissao     ( date("Y-m-d", db_getsession("DB_datausu") ) );
    		$oCertidaoExistencia->setHoraEmissao     ( date("H:m") );
    		$oCertidaoExistencia->setProcessoSistema ( $lProcessoSistema );
    		$oCertidaoExistencia->setDadosProcesso   ( $iProcesso, $sTitular, $dtDataProcesso );
    		$oCertidaoExistencia->salvar();
    		
    		$oRetorno->iCodigoCertidao = $oCertidaoExistencia->getCodigoCertidao();
    		
    		
    		db_query($conn,'COMMIT;');
    		db_query($conn,'BEGIN;');
    		$oCertidaoExistencia->geraArquivoOpenOffice();
    		db_query($conn,'COMMIT;');
    		
    	} catch ( Exception $eErro ) {
    		
    		db_fim_transacao(true);
    		throw new Exception($eErro->getMessage());
    	}
    break;  
    
    default:
      throw new ErrorException("Nenhuma Opчуo Definida para o RPC.");
    break;
    
  }
  
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}


?>