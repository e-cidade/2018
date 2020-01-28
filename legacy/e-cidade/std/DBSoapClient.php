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

/**
 * Classe intermediara para clientes SOAP
 * @author Jeferson Belmiro   <jeferson.belmiro@dbseller.com.br>
 * @author Alberto Ferri Neto <alberto@dbseller.com.br>
 * @version $
 * @revision $
 */
class DBSoapClient {

  /**
  * Objeto que instancia SoapClient
  * @param object $sCaminhoArquivo
  */
  private $oSoap;

  const NS_WSECURITY_SECEXT  = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
  const NS_WSECURITY_UTILITY = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';

  /**
   * Contrutor, instancia o objeto SoapClient
   * @param string $sUrl        - endereço do wsdl
   * @param array $aParametros  - Opções do SoapClient
   * @throws Exception $sUrl vazio
   */
  public function __construct($sUrl = null, $aParametros = null) {

    $this->oSoap = new SoapClient($sUrl, $aParametros);
  }

  /**
   * Retorna um array com as funções do webservice
   * Esta função funciona apenas no modo WSDL
   * @return array
   */
  public function getFunctions() {
    return $this->oSoap->__getFunctions();
  }

  /**
   * Retorna uma função
   * @param string $sFuncao    - Nome da função SOAP
   * @param array $aArgumentos - Um array de argumentos para passar na função
   * @param $aOpcoes           - Um array associativo com opções (location, uri, soapaction)
   * @param $oHeaderEntrada    - Um array com os headers enviados na requisição SOAP
   * @param $oHeaderSaida      - Array com header de retorno do SOAP
   * @see SoapClient::__soapCall()
   */
  public function DBSoapCall($sFuncao, $aArgumentos, $aOpcoes = null, $oHeaderEntrada = null, $oHeaderSaida = null) {
    return $this->oSoap->__soapCall($sFuncao, $aArgumentos, $aOpcoes, $oHeaderEntrada, $oHeaderSaida);
  }

  /**
   * Intercepta todos os métodos e verifica se ele pertence a propria classe, caso contrário
   * executa o método do objeto $oSoap
   * @param string $sFuncao - nome da funcao chamada
   * @param array $aArgumentos. Argumentos da função
   */
  public function __call($sFuncao, array $aArgumentos = array()) {

    if(!method_exists($this, $sFuncao)) {
      return call_user_func_array( array($this->oSoap, $sFuncao),$aArgumentos);
    }
  }

}