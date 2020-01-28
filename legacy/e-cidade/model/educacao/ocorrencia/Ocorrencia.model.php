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

/**
 * Classe para dados de ocorrencias de alunos
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.3 $ 
 * @package ocorrencia
 */
abstract class Ocorrencia {
  
  /**
   * Codigo da ocorrencia
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Matricula da ocorrencia
   * @var Matricula
   */
  protected $oMatricula;
  
  /**
   * Data da Ocorrencia
   * @var DBDate
   */
  protected $dtOcorrencia;
  
  
  /**
   * Tipo da ocorrencia;
   * @var integer
   */
  protected $iTipo;
  
  /**
   * Notificacoes Enviadas para a Ocorrencia
   * @var array
   */
  protected $aNotificacoes = array();
  
  /**
   * Texto na Notificacao
   */
  protected $sTexto;
  
  /**
   * 
   */
  function __construct() {

  }
  /**
   * Retorna as notificacoes enviadas para a ocorrencia
   * @return array
   */
  public function getNotificacoes() {

    return $this->aNotificacoes;
  }
  
  /**
   * Retorna a data da Ocorrencia
   * @return DBDate
   */
  public function getDataOcorrencia() {

    return $this->dtOcorrencia;
  }
  
  /**
   * Define a data da ocorrencia
   * @param DBDate $dtOcorrencia Data da ocorrencia
   */
  public function setDtOcorrencia(DBDate $dtOcorrencia) {

    $this->dtOcorrencia = $dtOcorrencia;
  }
  
  /**
   * Retorna o codigo da Ocorrencia
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * Retorna a matricula que foi enviada a ocorrencia
   * @return Matricula
   */
  public function getMatricula() {

    return $this->oMatricula;
  }
  
  /**
   * Define a matricula da Ocorrencia
   * @param Matricula $oMatricula
   */
  public function setMatricula($oMatricula) {

    $this->oMatricula = $oMatricula;
  }
  
  /**
   * Retorna o texto da ocorrencia
   * @return string
   */
  public function getTexto() {

    return $this->sTexto;
  }
  
  /**
   * Define o texto da ocorrencia
   * @param unknown_type $sTexto
   */
  public function setTexto($sTexto) {

    $this->sTexto = $sTexto;
  }
  /**
   * Adiciona uma notificacao a ocorrencia
   * @param INotificacao Notificacao
   */
  public function adicionarNotificacao(INotificacao $oNotificacao) {
    
    $this->aNotificacoes[] = $oNotificacao;
  }
  
  /**
   * 
   * Persite os dados da ocorrencia
   */
  public function salvar() {
    
    $oDaoOcorrencia                       = db_utils::getDao("ocorrencia");
    $oDaoOcorrencia->ed103_matricula      = $this->getMatricula()->getCodigo();
    $oDaoOcorrencia->ed103_dataocorrencia = $this->getDataOcorrencia()->convertTo(DBDate::DATA_EN);
    $oDaoOcorrencia->ed103_ocorrenciatipo = $this->iTipo;
    $oDaoOcorrencia->ed103_texto          = $this->getTexto();
    if (empty($this->iCodigo)) {
      
      
      $oDaoOcorrencia->incluir(null);
      $this->iCodigo = $oDaoOcorrencia->ed103_sequencial;  
    } else {
      
      $oDaoOcorrencia->ed103_sequencial = $this->getCodigo();
      $oDaoOcorrencia->alterar($this->getCodigo());
    }
    
    if ($oDaoOcorrencia->erro_status == 0) {
    
      $sMensagemErro   = "Erro ao salvar dados da ocorrência.\n ";
      $sMensagemErro  .= "Erro Técnico : {$oDaoOcorrencia->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
    
    /**
     * Verificamos quais vinculos existem com as notificacoes, 
     * excluimos os vinculos existentes, e vinculamos novamnete.
     */
    $oDaoOcorrenciaNotificacao = db_utils::getDao("ocorrencianotificacao");
    $oDaoOcorrenciaNotificacao->excluir(null, "ed105_ocorrencia = {$this->getCodigo()}");
    if ($oDaoOcorrenciaNotificacao->erro_status == 0) {
    
      $sMensagemErro   = "Erro ao salvar dados da ocorrência.\n ";
      $sMensagemErro  .= "Erro Técnico : {$oDaoOcorrenciaNotificacao->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
    /**
     * Vinculamos as notificacoes a ocorrencia
     */
    foreach ($this->getNotificacoes() as $oNotificacao) {
      
      $oNotificacao->salvar();
      $oDaoOcorrenciaNotificacao->ed105_ocorrencia          = $this->getCodigo();
      $oDaoOcorrenciaNotificacao->ed105_mensagemnotificacao = $oNotificacao->getCodigo();
      $oDaoOcorrenciaNotificacao->incluir(null);
      if ($oDaoOcorrenciaNotificacao->erro_status == 0) {
    
        $sMensagemErro   = "Erro ao salvar dados da ocorrência.\n ";
        $sMensagemErro  .= "Erro Técnico : {$oDaoOcorrenciaNotificacao->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }  
    }
  }
}

?>