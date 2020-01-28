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

/**
 * Classe DataTransfer envio de notificacoes
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 *          Robson Inacio   <robson@dbseller.com.br> 
 * @version $Revision: 1.2 $ 
 */

class NotificacaoMensagem {
  
  /**
   * Mensagem completa da notificacao
   * @var string
   */
  private $sMensagem;
  
  /**
   * Assunto da notificacao 
   * @var string
   */
  private $sAssunto;
  
  /**
   * Resumo da notificacao. O texto sera usado para o envio de SMS
   * @var string
   */
  private $sResumo;
  
  /**
   * Telefone da mensagem
   * @var string
   */
  private $sTelefone;
  
  /**
   * Email para envio da notificacao (Destino)
   * @var string
   */
  private $sEmailDestino;
  
  /**
   * Email para envio da notificacao (Origem)
   * @var string
   */
  private $sEmailOrigem;
  
  /**
   * 
   */
  function __construct() {

  }
  /**
   * Retorna o assunto da notificacao
   * @return string
   */
  public function getAssunto() {

    return $this->sAssunto;
  }
  
  /**
   * Define o assunto da notificacao
   * @param string $sAssunto texto com o assunto da notificacao
   */
  public function setAssunto($sAssunto) {

    $this->sAssunto = $sAssunto;
  }
  
  /**
   * Retorna o email que foi enviado a notificacao (Destino)
   * @return string
   */
  public function getEmailDestino() {

    return $this->sEmailDestino;
  }
  
  /**
   * Define o email para envio da notificacao (Destino)
   * @param string $sEmail email que sera enviado a notificacao
   */
  public function setEmailDestino($sEmailDestino = '') {

    $this->sEmailDestino = $sEmailDestino;
  }

  /**
   * Retorna o email que foi enviado a notificacao (Origem)
   * @return string
   */
  public function getEmailOrigem() {
  
    return $this->sEmailOrigem;
  }
  
  /**
   * Define o email para envio da notificacao (Origem)
   * @param string $sEmail email que sera enviado a notificacao
   */
  public function setEmailOrigem($sEmailOrigem = '') {
  
    $this->sEmailOrigem = $sEmailOrigem;
  }
  
  /**
   * Retorna o texto da notificacao
   * @return string
   */
  public function getMensagem() {

    return $this->sMensagem;
  }
  
  /**
   * Define o texto da notificacao
   * @param string $sMensagem texto com a notificacao
   */
  public function setMensagem($sMensagem) {

    $this->sMensagem = $sMensagem;
  }
  
  /**
   * Retorna o resumo da mensagem. 
   * @return string
   */
  public function getResumo() {

    return $this->sResumo;
  }
  
  /**
   * Define o texto resumido da mensagem .
   * o Envio do sms utiliza esse campo. O resumo é limitado a 160 caracteres.
   * @param string $sResumo
   * @throws ParameterException
   */
  public function setResumo($sResumo = '') {

    if (strlen($sResumo) > 160) {
      throw new ParameterException('Resumo da notificação deve ser ter no máximo 160 caracteres');
    }
    $this->sResumo = substr($sResumo, 0, 160);
  }
  
  /**
   * Retorna o telefone de envio da notificacao
   * @return string
   */
  public function getTelefone() {

    return $this->sTelefone;
  }
  
  /**
   * Retorna o telefone de envio da notificacao 
   * @param string $sTelefone numero de telefone para envio do sms
   */
  public function setTelefone($sTelefone = '') {

    $this->sTelefone = $sTelefone;
  }
  
  /**
   * Define a operadora que ira fazer o envio da mensagem via Telefone.
   * @param string $sOperadora nome da operadora
   */
  public function setOperadora($sOperadora = '') {

    $this->sOperadora = $sOperadora;
  }
  
  /**
   * Retorna a operadora que ira realizar o envio.
   * @param string $sOperadora nome da operadora
   */
  public function getOperadora($sOperadora = '') {

    return $this->sOperadora;
  }
  
}

?>