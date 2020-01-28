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
 * Lista de telefone(s) de um cidadao
 * @author Andrio Costa
 * @package social
 * @version $Revision: 1.4 $
 */
class CidadaoTelefone {

  
  /**
   * sequencia da tabela: cidadaotelefone
   * @var integer
   */
  private $iCodigoTelefone;
  
  /**
   * codigo do cidadao: ov07_cidadao
   * @var integer
   */
  private $iCodigoCidadao;
  
  /**
   * Numero de telefone 
   * @var string
   */
  private $sNumeroTelefone;
  
  /**
   * codigo do tipo de telefone 
   * tabela: telefonetipo campo: ov23_sequencial
   * @var integer
   */
  private $iTipoTelefone;
  
  /**
   * tipo de telefone
   * tabela: telefonetipo campo: ov23_descricao
   * @var string
   */
  private $sTipoTelefone;
  
  /**
   * codigo do ddd 
   * @var string
   */
  private $sDDD;
  
  private $sRamal;
  
  private $sObservacao;
  
  /**
   * identifica se eh o telefone principal do cidadao
   * @var boolean
   */
  private $lPrincipal;

  
  public function __construct($iCodigoTelefone = null) {    
    
    if (!empty($iCodigoTelefone)) {

      $oDaoTelefone = db_utils::getDao('cidadaotelefone');
      $sSqlTelefone = $oDaoTelefone->sql_query_telefonetipo($iCodigoTelefone, "*", null);
      $rsTelefone   = $oDaoTelefone->sql_record($sSqlTelefone);
      
      $iRetorno = $oDaoTelefone->numrows; 
      
      if ($iRetorno > 0) {
          
         $oTelefone             = db_utils::fieldsMemory($rsTelefone, 0);
         $this->iCodigoTelefone = $oTelefone->ov07_sequencial;
         
         $this->setCodigoCidadao($oTelefone->ov07_cidadao);
         $this->setNumeroTelefone($oTelefone->ov07_numero);
         $this->setDDD($oTelefone->ov07_ddd);
         $this->setRamal($oTelefone->ov07_ramal);
         $this->setObservacao($oTelefone->ov07_obs);
         $this->setTelefonePrincipal($oTelefone->ov07_principal == 't'?true:false);
         $this->iTipoTelefone = $oTelefone->ov07_tipotelefone;
         $this->sTipoTelefone = $oTelefone->ov23_descricao;
         unset($oTelefone);
      }
    }
  }
  
  /**
   * Salva / Altera os dados do telefone do cidadao 
   * @throws DBException
   * @return true
   */
  public function salvar($iCodigoCidadao = '', $iSequencialCidadao = '') {
    
    $oDaoTelefone = db_utils::getDao('cidadaotelefone');
    
    $oDaoTelefone->ov07_numero       = $this->getNumeroTelefone();
    $oDaoTelefone->ov07_tipotelefone = $this->getCodigoTipoTelefone();
    $oDaoTelefone->ov07_ddd          = $this->getDDD();
    $oDaoTelefone->ov07_ramal        = $this->getRamal();
    $oDaoTelefone->ov07_obs          = $this->getObservacao();
    $oDaoTelefone->ov07_principal    = $this->isTelefonePrincipal()?"true":"false";
    
    if (!empty($this->iCodigoTelefone)) {
      
      $oDaoTelefone->ov07_sequencial   = $this->getCodigoTelefone(); 
      $oDaoTelefone->alterar($this->getCodigoTelefone());
    } else {
      
      $oDaoTelefone->ov07_cidadao = $iCodigoCidadao;
      $oDaoTelefone->ov07_seq     = $iSequencialCidadao;
      $oDaoTelefone->incluir(null);
    }
    if ($oDaoTelefone->erro_status == 0) {
      
      $sMsgErro  = "Erro ao salvar dados do Telefone:";
      $sMsgErro .= "\n\nErro técnico: {$oDaoTelefone->erro_msg}"; 
      throw new BusinessException($sMsgErro);
    }
    return true;
  }
  
  
  /**
   * retorna o sequencial da tabela: cidadaotelefone
   * @return integer
   */
  public function getCodigoTelefone() {
  
    return $this->iCodigoTelefone;
  }
  
  /**
   * seta o codigo do cidadao
   * @param integer $iCodigoCidadao
   */  
  private function setCodigoCidadao($iCodigoCidadao) {
  
    $this->iCodigoCidadao = $iCodigoCidadao;
  }
  /**
   * retorna o codigo do cidadao
   * @return integer
   */
  public function getCodigoCidadao() {
  
    return $this->iCodigoCidadao;
  }
  
  /**
   * seta o numero de telefone
   * @param string $sNumeroTelefone
   */
  public function setNumeroTelefone($sNumeroTelefone) {
  
    $this->sNumeroTelefone= $sNumeroTelefone;
  }
  /**
   * retorna o numero de telefone
   */
  public function getNumeroTelefone() {
  
    return $this->sNumeroTelefone;
  }
  
  /**
   * identifica um tipo de telefone
   * @param integer $iTipoTelefone
   */
  public function setCodigoTipoTelefone($iTipoTelefone) {
  
    $this->iTipoTelefone = $iTipoTelefone;
  }
  /**
   * retorna o codigo identificador do tipo de telefone
   * @return integer
   */
  public function getCodigoTipoTelefone() {
  
    return $this->iTipoTelefone;
  }

  /**
   * seta descricao do tipo de telefone
   * @return string 
   */
  public function getTipoTelefone() {
  
    return $this->sTipoTelefone;
  }
  
  /**
   * seta string do codigo do ddd
   * @param string $sDDD
   */
  public function setDDD($sDDD) {
  
    $this->sDDD = $sDDD;
  }
  /**
   * codigo do ddd
   * @return string
   */
  public function getDDD() {
  
    return $this->sDDD;
  }
  
  /**
   * seta o ramal
   * @param string $sRamal
   */
  public function setRamal($sRamal) {
  
    $this->sRamal = $sRamal;
  }
  /**
   * retorna ramal
   * @return string
   */  
   public function getRamal() {
  
    return $this->sRamal;
  }
  
  /**
   * seta uma observação para o telefone
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
  
    $this->sObservacao = $sObservacao;
  }
  /**
   * retorna a observacao
   * @return string
   */
  public function getObservacao() {
  
    return $this->sObservacao;
  }
  
  /**
   * seta identificacao se o telefone é o principal 
   * @param boolean $lPrincipal
   */
  public function setTelefonePrincipal($lPrincipal) {
  
    $this->lPrincipal = $lPrincipal;
  }
  /**
   * retorna identificacao se o telefone é o principal
   * @return booleam
   */
  public function isTelefonePrincipal() {
  
    return $this->lPrincipal;
  }
}