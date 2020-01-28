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
 * Classe que implementa envio de mensagens sms 
 * 
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 *         Robson Inacio   <robson@dbseller.com.br> 
 * @version $Revision: 1.4 $ 
 */
require_once ('model/configuracao/notificacao/INotificacao.interface.php');
require_once ('model/configuracao/notificacao/Notificacao.model.php');

final class NotificacaoSMS extends Notificacao implements INotificacao {
  
  /**
   * Servico de envio de mensagens
   * @var ISMSService
   */
  private $oServicoMensagem;
  
  /**
   * Mensagem de erro do envio
   */
  protected $sErroMensagem;
  
  /**
   * Tipo da Notificacao
   */
  protected $iTipoNotificacao = 1;
  
  /**
   * 
   */
  function __construct() {

  }
  
  /**
   * Realizar o envio da mensagem 
   * @see INotificacao::enviar()
   * @return boolean
   */
  public function enviar() {

    $oMensagem   = $this->getMensagem();
    $this->oServicoMensagem = SMSServiceFactory::getOperadora($oMensagem->getOperadora());
    $this->oServicoMensagem->setFone($oMensagem->getTelefone());
    $this->oServicoMensagem->setMessage($oMensagem->getResumo());
    $iRetorno = $this->oServicoMensagem->send();
    $this->lEnviada = true;
    die($iRetorno);
    if ($iRetorno < 0) {
      
      $this->sErroMensagem = $this->oServicoMensagem->getErrorMessage($iRetorno);
    }
    return $this->lEnviada;
  }
  
  public function getMensagemErro() {
    return $this->sErroMensagem;
  }
  
}

?>