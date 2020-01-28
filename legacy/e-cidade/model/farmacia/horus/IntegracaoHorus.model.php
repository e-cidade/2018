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
 *  Classe responsavel pela integracao com o Horus
 *  @package Farmacia\Horus
 *  @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class IntegracaoHorus {

  private $sUrlWebService = "http://189.28.128.35/horus-ws-basico/RecebeDadosWS?wsdl";
  private $sLoginWebService;
  private $sSenhaWebService;

  private $aArquivos = array();

  public function __construct() {

    $oDaoHorusUsuario = new cl_horususuario();
    $sSqlHorusUsuario = $oDaoHorusUsuario->sql_query_file(null, '*', null, "fa66_unidade = " . db_getsession("DB_coddepto"));
    $rsHorusUsuario   = db_query($sSqlHorusUsuario);

    if(!$rsHorusUsuario) {
      throw new DBException("Erro ao buscar as configurações do Hórus.");
    }

    if(pg_num_rows($rsHorusUsuario) == 0) {

      $sMensagem  = "As configurações de acesso ao Hórus não foram realizadas para esta Unidade. Acesse o menu:";
      $sMensagem .= "\n\n- Farmácia > Procedimentos > Hórus > Configuração de Usuário";
      throw new BusinessException($sMensagem);
    }

    $oDadosHorusUsuario = db_utils::fieldsMemory($rsHorusUsuario, 0);

    if(empty($oDadosHorusUsuario->fa66_usuario)) {
      throw new Exception("Usuário de acesso ao webservice não esta configurado.");
    }

    if(empty($oDadosHorusUsuario->fa66_senha)) {
      throw new Exception("Senha de acesso ao webservice não esta configurada.");
    }

    $this->sLoginWebService = $oDadosHorusUsuario->fa66_usuario;
    $this->sSenhaWebService = $oDadosHorusUsuario->fa66_senha;
  }

  /**
   * Adiciona um arquivo para processamento
   * @param  iArquivosHorus $oArquivo Arquivo que deve ser enviado
   * @return null
   */
  public function adicionarArquivo(iArquivosHorus $oArquivo) {
    $this->aArquivos[] = $oArquivo;
  }

  /**
   * realiza o envio dos arquivos informados para o Horus
   * @return string[] lista dos arquivos enviados
   * @throws Exception
   */
  public function enviar() {

    $aArquivosEnviados = array();

    try {

      $oCliente = $this->getClienteHorus();
      foreach ($this->aArquivos as $oArquivo) {

        $sArquivo = $oArquivo->gerarArquivo();
        if ( !is_file($sArquivo) ) {
          continue;
        }
        $sConteudoArquivo = file_get_contents($sArquivo);

        $oRetorno = $oCliente->__call("recebeDados", array(array("source" => $sConteudoArquivo )));
        $aRetorno = (array) $oRetorno->return;
        if ( isset($aRetorno['horus-ws-numero-protocolo'] ) ) {

          $iProtocolo = $aRetorno['horus-ws-numero-protocolo'];
          $oArquivo->salvarProtocolo($iProtocolo);
          $oArquivo->atualizaSituacaoEnvio();
        }

        $aArquivosEnviados[] = $sArquivo;
      }

    } catch (SoapFault $oErro) {
      throw new Exception( $oErro->getMessage() );
    }

    return $aArquivosEnviados;
  }


  /**
   * Cria uma conexão autenticada com servidor Horus
   * @return SoapClient
   */
  private function getClienteHorus() {

    $oCliente = new SoapClient($this->sUrlWebService, array('trace' => 1));

    $sNSSecuritySecext    = DBSoapClient::NS_WSECURITY_SECEXT;
    $sNSSecurityUtulility = DBSoapClient::NS_WSECURITY_UTILITY;

    /**
     * Criando os token de usuario e senha
     */

    $oTokenAuth = new stdClass();
    $oTokenAuth->Username = new SoapVar($this->sLoginWebService, XSD_STRING, null, null, null, $sNSSecuritySecext);
    $oTokenAuth->Password = new SoapVar($this->sSenhaWebService, XSD_STRING, null, null, null, $sNSSecuritySecext);

    $oTimestamp = new stdClass();
    $sCreated   = date("Y-m-d\Th:i:s\Z");
    $oTimestamp->Created = new SOAPVar($sCreated, XSD_STRING, null, null, null, $sNSSecurityUtulility);

    /**
     * criando token de segurança
     */
    $oTokenSecurity                = new stdClass();
    $oTokenSecurity->UsernameToken = new SoapVar($oTokenAuth, SOAP_ENC_OBJECT, null, null, null, $sNSSecuritySecext);
    $oTokenSecurity->Timestamp     = new SoapVar($oTimestamp, SOAP_ENC_OBJECT, null, null, null, $sNSSecurityUtulility);


    $oSoapHeader = new SOAPHeader($sNSSecuritySecext, 'Security', $oTokenSecurity);
    $oCliente->__setSOAPHeaders($oSoapHeader);
    return $oCliente;
  }

  /**
   * Valida entre os arquivos e suas competencia, se o ultimo envio foi validado com sucesso pelo servidor.
   * Não loga as inconsistencias pois foi visto que o retorno do Hórus não é sufiente para que possamos identificar no
   * ecidade sobre qual registro enviado é o erro retornado.
   *
   * @return boolean
   */
  public function preProcessar() {

    foreach ($this->aArquivos as $oArquivo) {

      $iProtocolo = $oArquivo->getUltimoEnvio();

      if ( empty($iProtocolo) ) {
        continue;
      }

      $oCliente         = $this->getClienteHorus();
      $oRetornoConsulta = $oCliente->__call("consultarAllDadosPorMunicipio", array(array("numeroProtocolo"=> $iProtocolo)));

      $sNomePropriedadeInconsistencia = "horus-ws-quantidade-insucessos";
      if ( $oRetornoConsulta->return->$sNomePropriedadeInconsistencia === 0 ) {
        $oArquivo->validarDadosEnviados( $iProtocolo );
      } else {
        $oArquivo->atualizaSituacaoIntegracao(self::INCONSISTENTE);
      }
    }

    return true;
  }
}
