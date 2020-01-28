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

require_once(modification("model/configuracao/consulta_dados/ConsultaDados.model.php"));
require_once(modification("model/webservices/Processamento.model.php"));
require_once(modification("model/webservices/Autenticacao.model.php"));
require_once(modification("model/configuracao/DBLog.model.php"));

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

      if (array_key_exists('webservice', $aArgumentos[1][1])) {

        $aParametrosGlobais = $aArgumentos[1][1];
        $aArgumentos[1]     = $aArgumentos[1][0];

        DBWebService::setParametrosGlobais($aParametrosGlobais);
      }

      Autenticacao::validaConexao($aArgumentos[0]);

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

  /**
   * Adiciona os valores nas variaveis de ambiente caso elas sejam informadas
   *
   * @param array $aParametros
   */
  static public function setParametrosGlobais($aParametros) {

    if (!empty($aParametros['webservice'])) {

      $aFlagAmbiente = $aParametros['webservice'];

      if (isset($aFlagAmbiente['DB_id_usuario'])) {
        $_SESSION['DB_id_usuario'] = $aFlagAmbiente['DB_id_usuario'];
      }

      if (isset($aFlagAmbiente['DB_login'])) {
        $_SESSION['DB_login'] = $aFlagAmbiente['DB_login'];
      }

      if (isset($aFlagAmbiente['DB_administrador'])) {
        $_SESSION['DB_administrador'] = $aFlagAmbiente['DB_administrador'];
      }

      if (isset($aFlagAmbiente['DB_ip'])) {
        $_SESSION['DB_ip'] = $aFlagAmbiente['DB_ip'];
      }

      if (isset($aFlagAmbiente['REQUEST_URI'])) {
        $_SESSION['REQUEST_URI'] = $aFlagAmbiente['REQUEST_URI'];
      }

      if (isset($aFlagAmbiente['DB_configuracao_ok'])) {
        $_SESSION['DB_configuracao_ok'] = $aFlagAmbiente['DB_configuracao_ok'];
      }

      if (isset($aFlagAmbiente['DB_instit'])) {
        $_SESSION['DB_instit'] = $aFlagAmbiente['DB_instit'];
      }

      if (isset($aFlagAmbiente['DB_totalmodulos'])) {
        $_SESSION['DB_totalmodulos'] = $aFlagAmbiente['DB_totalmodulos'];
      }

      if (isset($aFlagAmbiente['DB_use_pcasp'])) {
        $_SESSION['DB_use_pcasp'] = $aFlagAmbiente['DB_use_pcasp'];
      }

      if (isset($aFlagAmbiente['DB_Area'])) {
        $_SESSION['DB_Area'] = $aFlagAmbiente['DB_Area'];
      }

      if (isset($aFlagAmbiente['DB_modulo'])) {
        $_SESSION['DB_modulo'] = $aFlagAmbiente['DB_modulo'];
      }

      if (isset($aFlagAmbiente['DB_nome_modulo'])) {
        $_SESSION['DB_nome_modulo'] = $aFlagAmbiente['DB_nome_modulo'];
      }

      if (isset($aFlagAmbiente['DB_coddepto'])) {
        $_SESSION['DB_coddepto'] = $aFlagAmbiente['DB_coddepto'];
      }

      if (isset($aFlagAmbiente['DB_nomedepto'])) {
        $_SESSION['DB_nomedepto'] = $aFlagAmbiente['DB_nomedepto'];
      }

      if (isset($aFlagAmbiente['DB_itemmenu_acessado'])) {
        $_SESSION['DB_itemmenu_acessado'] = $aFlagAmbiente['DB_itemmenu_acessado'];
      }

      if (isset($aFlagAmbiente['SERVER_ADDR'])) {
        $_SERVER['SERVER_ADDR'] = $aFlagAmbiente['SERVER_ADDR'];
      }

      if (isset($aFlagAmbiente['SERVER_PORT'])) {
        $_SERVER['SERVER_PORT']= $aFlagAmbiente['SERVER_PORT'];
      }

      if (isset($aFlagAmbiente['DOCUMENT_ROOT'])) {
        $_SERVER['DOCUMENT_ROOT'] = $aFlagAmbiente['DOCUMENT_ROOT'];
      }

      if (isset($aFlagAmbiente['SERVER_ADMIN'])) {
        $_SERVER['SERVER_ADMIN'] = $aFlagAmbiente['SERVER_ADMIN'];
      }

      if (isset($aFlagAmbiente['PHP_SELF'])) {
        $_SERVER['PHP_SELF'] = $aFlagAmbiente['PHP_SELF'];
      }

      if (isset($aFlagAmbiente['REQUEST_URI'])) {
        $_SERVER["REQUEST_URI"] = $aFlagAmbiente['REQUEST_URI'];
      }

      if (isset($aFlagAmbiente['HTTP_HOST'])) {
        $_SERVER['HTTP_HOST'] = $aFlagAmbiente['HTTP_HOST'];
      }
    }
  }
}
