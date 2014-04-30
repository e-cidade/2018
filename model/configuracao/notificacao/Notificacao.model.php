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
 * Classe abstrata para implementacoes de notificacoes
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 *         Robson Inacio   <robson@dbseller.com.br> 
 * @version $Revision: 1.4 $ 
 */
abstract class Notificacao {
  
  /**
   * Codigo da notificacao
   * @var integer
   */
  protected $iCodigo;
  /**
   * Mensagem da notificacao
   * @var NotificacaoMensagem
   */
  protected $oNotificacaoMensagem;
  /**
   * Se a notificacao foi enviada ou nao
   */
  protected $lEnviada = false;
  
  /**
   *  Metodo contrutor
   */
  function __construct() {

  }
  /**
   * @return bool true se a mensagem foi enviada
   * 
   */
  public function isEnviada() {

    return $this->lEnviada;
  }
  
  /**
   * @param bool $lEnviada caso true, altera o estado da notificacao para alterada
   */
  public function setEnviada($lEnviada) {

    $this->lEnviada = $lEnviada;
  }
  
  /**
   * @return NotificacaoMensagem data transfer
   */
  public function getMensagem() {

    return $this->oNotificacaoMensagem;
  }
  
  /**
   * @param NotificacaoMensagem $oNotificacaoMensagem dataTransfer com a mensagem a ser enviada pela notificacao
   */
  public function setMensagem(NotificacaoMensagem $oNotificacaoMensagem) {

    $this->oNotificacaoMensagem = $oNotificacaoMensagem;
  }
  
  /**
   * Retornoa a mensagem de erro
   * @return string
   */
  public function getMensagemErro() {
    return $this->sErroMensagem;
  }
  
  /**
   * Retornoa a mensagem de erro
   * @return string
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  /**
   * Salva os dados da notificacao
   */
  public function salvar() {
    
    $oDaoNotificacao                                = db_utils::getDao("mensagemnotificacao");
    $oDaoNotificacao->db134_mensagemnotificacaotipo = $this->iTipoNotificacao;
    $oDaoNotificacao->db134_enviada                 = $this->isEnviada()?"true":"false";
    $oDaoNotificacao->db134_telefone                = $this->getMensagem()->getTelefone();
    $oDaoNotificacao->db134_email                   = $this->getMensagem()->getEmailDestino();
    $oDaoNotificacao->db134_assunto                 = $this->getMensagem()->getAssunto();
    $oDaoNotificacao->db134_resumo                  = $this->getMensagem()->getResumo();
    $oDaoNotificacao->db134_mensagem                = $this->getMensagem()->getMensagem();  
    $oDaoNotificacao->db134_mensagemretorno         = $this->getMensagemErro();
    if (empty($this->iCodigo)) {
      
      $oDaoNotificacao->incluir(null);
      $this->iCodigo = $oDaoNotificacao->db134_sequencial;
    } else {
      
      $oDaoNotificacao->db134_sequencial = $this->iCodigo; 
      $oDaoNotificacao->alterar($this->iCodigo);
    }
    if ($oDaoNotificacao->erro_status == 0) {
    
      $sMensagemErro   = "Erro ao salvar dados da notificação.\n ";
      $sMensagemErro  .= "Erro Técnico : {$oDaoNotificacao->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
  }
}

?>