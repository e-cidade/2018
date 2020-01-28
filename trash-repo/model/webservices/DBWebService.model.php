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

require_once( "model/configuracao/consulta_dados/ConsultaDados.model.php");
require_once( "model/webservices/Processamento.model.php");
require_once( "model/configuracao/DBLog.model.php" );

/**
 * Classe Responsável pelo gerenciamento das conexões via WebService 
 *
 * @package WebServices
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo  <renan@dbseller.com.br>
 */
class DBWebService {

  static private $aInstancia = array();
  
  public function __construct() {
    
    if ( isset($_SESSION['DB_debugon'] ) ) {
      set_error_handler(array($this, "handlerError"));
    }
  }
  /**
   * Retorna a Instancia do Método do Webservice
   * @param  string $sMetodo
   * @throws SoapFault - Caso método do webservice for esperado.
   */
  static public function getInstance( $sMetodo ) {
    
    if ( !isset(DBWebService::$aInstancia[$sMetodo]) ) {
      switch ( $sMetodo ) {
      
        case "consultar":
          DBWebService::$aInstancia[$sMetodo] = new ConsultaDados();
        break;

        case "processar":
          DBWebService::$aInstancia[$sMetodo] = new Processamento();
        break;
        
        default:
          throw new SoapFault( "e-Cidade", utf8_encode("Metodo '{$sMetodo}' nao existe.") );
        break;
      }
    }
    return DBWebService::$aInstancia[$sMetodo];
  }
  
  /**
   * Responsavel pela tomada de decisao do webService
   * @param string $sMetodo
   * @param array  $aArgumentos
   */
  public function __call( $sMetodo, $aArgumentos ) {
    
    try {
      
     $oRequisicao = DBWebService::getInstance( $sMetodo );
     $oResposta   = call_user_func_array( array( $oRequisicao, $sMetodo ), $aArgumentos );
     return $oResposta;
    } catch ( Exception $oExcecao ){
      throw new SoapFault( "e-Cidade", utf8_encode($oExcecao->getMessage()) );
    }
  }

  /**
   * Tratamento de Erros
   * @param  ineteger  $errno
   * @param  string    $errstr
   * @param  integer   $errfile
   * @param  integer   $errline
   * @throws SoapFault
   */
  public function handlerError($errno, $errstr, $errfile, $errline) {
  
    $aTiposErro = array(
      E_ERROR             => 'E_ERROR', 
      E_WARNING           => 'E_WARNING', 
      E_PARSE             => 'E_PARSE', 
      E_NOTICE            => 'E_NOTICE', 
      E_CORE_ERROR        => 'E_CORE_ERROR', 
      E_CORE_WARNING      => 'E_CORE_WARNING', 
      E_CORE_ERROR        => 'E_COMPILE_ERROR', 
      E_CORE_WARNING      => 'E_COMPILE_WARNING', 
      E_USER_ERROR        => 'E_USER_ERROR', 
      E_USER_WARNING      => 'E_USER_WARNING', 
      E_USER_NOTICE       => 'E_USER_NOTICE', 
      E_STRICT            => 'E_STRICT', 
      E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
      E_DEPRECATED        => 'E_DEPRECATED', 
      E_USER_DEPRECATED   => 'E_USER_DEPRECATED' 
    );
     
     if ( $errno == E_DEPRECATED ) return;
     if ( $errno == E_NOTICE     ) return;

     throw new SoapFault("e-Cidade", "\n\n" . 
       "Erro   : " . $aTiposErro[$errno] . " - " .$errstr ."\n".
       "Arquivo: " . $errfile ."\n".
       "Linha  : " . $errline ."\n".
       "DEBUG  : " . print_r(debug_backtrace(), 1 )     
     );
  }
}