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

require_once ('model/configuracao/notificacao/ISMSService.interface.php');
/**
 * Classe que implementa envio de mensagens sms da convect
 * 
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 *         Robson Inacio   <robson@dbseller.com.br> 
 * @version $Revision: 1.2 $ 
 */
class SMSServiceConvect implements ISMSService {
  
  /**
   * Usuario para conexao no servico
   * @var string 
   */
  private $sUser;
  
  /**
   * Senha para conexao no servico
   * @var string
   */
  private $sPassword;
  
  /**
   * Url de conexao no servico
   * @var string
   */
  private $sUrl;
  
  /**
   * Numero do telefone que sera enviado o telefone
   * @var string
   */
  private $sFone;
  
  /**
   * Mensagem de envio do telefone
   * @var string
   */
  private $sMensagem;
  
  private $aMensagemsErros;
  
  /**
   * 
   */
  function __construct() {
    
    $this->sUrl = "http://193.105.74.59/api/sendsms/plain?";
    /**
     *TODO Mudar para arquivo XML de configuracao com os dados 
     */
    $this->aErrorMessage["-2"]  = "Créditos Insuficientes";
    $this->aErrorMessage["-3"]  = "Operadora nao Homologada";
    $this->aErrorMessage["-5"]  = "Usuário e/ou Senha inválidos";
    $this->aErrorMessage["-6"]  = "Número de Destino incompleto";
    $this->aErrorMessage["-7"]  = "Texto SMS inválido";
    $this->aErrorMessage["-8"]  = "Sem Nome do Remetente";
    $this->aErrorMessage["-9"]  = "Formato de destino inválido";
    $this->aErrorMessage["-10"] = "Usuário incompleto";
    $this->aErrorMessage["-11"] = "Senha incompleta";
    $this->aErrorMessage["-13"] = "Número de destino incomplento";
  }
  
  /**
   * 
   * @see ISMSService::send()
   */
  public function send() {
  
    $sArquivoConfiguracao = "config/notificacao/operadoras/convect.xml"; 
    if (!file_exists($sArquivoConfiguracao)) {
      throw new FileException('Arquivo de configuração para o envio de SMS pela Convect nao encontrado.');
    }
    $oDomXML = new DOMDocument();
    $oDomXML->preserveWhiteSpace = false;
    $oDomXML->formatOutput       = true;
    $oDomXML->load($sArquivoConfiguracao);
    
    $oNoUser         = $oDomXML->getElementsByTagName("user");
    $oNoPassWord     = $oDomXML->getElementsByTagName("password");
    $this->sPassword = $oNoPassWord->item(0)->getAttribute("password");
    $this->sUser     = $oNoUser->item(0)->getAttribute("user");
    
    $sUrl      = $this->sUrl;
    $sUrl     .= "user={$this->sUser}&";
    $sUrl     .= "password={$this->sPassword}&";
    $sMensagem = urlencode($this->sMensagem);
    $sGSM      = "55".$this->sFone;  
    $sUrl     .= "sender=e-cidade&SMStext={$sMensagem}&GSM={$sGSM}";
    
    $iRetorno  = file_get_contents($sUrl, false);
    return $iRetorno;
  }
  
  /**
   * Define o telefone de envio do SMS
   * @see ISMSService::setFone()
   * @param string $sFone Numero do telefone de envio 
   */
  public function setFone($sFone) {
    $this->sFone = $sFone;
  }
  
  /**
   * Define a mensagem do telefone
   * @see ISMSService::setMessage()
   * @param string $sMessage Texto com a mensagem de envio
   * 
   */
  public function setMessage($sMessage) {
    $this->sMensagem = $sMessage;
  }
  
  /**
   * Retorna a mensagem de erro atravez de Código
   * @var integer $iCodigoErro;
   * @return string com a mensagem de erro
   */
  public function getErrorMessage($iError) {
    
    return $this->aErrorMessage[$iError];
  }
}

?>