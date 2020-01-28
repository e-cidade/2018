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


class BemTipoDepreciacao {
  
  private $iCodigo;    
  private $sDescricao;     
  private $iQuantidadeAno;
  private $iPercentual;    
  private $sObservacao;
  
  
  public function __construct($iCodigo) {
    
    if (!empty($iCodigo)) {
      
      $oDaoTipoDepreciacao = db_utils::getDao("benstipodepreciacao");
      
      $sSql                = $oDaoTipoDepreciacao->sql_query_file($iCodigo);
      $rsTipoDeprciacao    = $oDaoTipoDepreciacao->sql_record($sSql);
      
      if($oDaoTipoDepreciacao->numrows == 1){
        
        $oTipoDepreciacao = db_utils::fieldsMemory($rsTipoDeprciacao, 0);
        
        $this->setCodigo($oTipoDepreciacao->t46_sequencial);
        $this->setDescricao($oTipoDepreciacao->t46_descricao);
        $this->setQuantidadeAno($oTipoDepreciacao->t46_quantidadeano);
        $this->setPercentual($oTipoDepreciacao->t46_percentual); 
        $this->setObservacao($oTipoDepreciacao->t46_observacao);
      }
    }
  }

  /**
   * C�digo do Tipo da Deprecia��o
   * @param $iCodigo
   */
  public function setCodigo($iCodigo) {
      $this->iCodigo = $iCodigo;
  }

  /**
   * Descri��o da Deprecia��o
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
      $this->sDescricao = $sDescricao;
  }

  /**
   * Numero de Anos que o Bem ser� depreciado
   * @param $iQuantidadeAno
   */
  public function setQuantidadeAno($iQuantidadeAno) {
      $this->iQuantidadeAno = $iQuantidadeAno;
  }

  /**
   * Porcentagem da deprecia��o por ano
   * @param $iPercentual
   */
  public function setPercentual($iPercentual) {
      $this->iPercentual = $iPercentual;
  }

  /**
   * Observa��o
   * @param $sObservacao
   */
  public function setObservacao($sObservacao) {
      $this->sObservacao = $sObservacao;
  }

  /**
   * C�digo do Tipo da Deprecia��o
   * @return integer
   */
  public function getCodigo() {
      return $this->iCodigo;
  }

  /**
   * 
   * Descri��o da Deprecia��o
   * @return String
   */
  public function getDescricao() {
      return $this->sDescricao;
  }

  /**
   * 
   * Numero de Anos que o Bem ser� depreciado
   * @return Integer
   */
  public function getQuantidadeAno() {
      return $this->iQuantidadeAno;
  }

  /**
   * 
   * Porcentagem da deprecia��o por ano
   * @return Integer
   */
  public function getPercentual() {
      return $this->iPercentual;
  }

  /**
   * Observa��o
   * @return String
   */
  public function getObservacao() {
      return $this->sObservacao;
  }
}