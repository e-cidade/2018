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
 * Atividade da escola
 * @package educacao
 * @subpackage recursohumano
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 */
class AtividadeEscolar {
  
  /**
   * Código da atividade
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Descrição da atividade
   * @var string
   */
  protected $sDescricao;
  
  /**
   * Identifica se permite lecionar
   * @var boolean
   */
  protected $lPermiteLecionar;
  
  /**
   * Construtor da classe
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoAtividadeRh   = db_utils::getDao("atividaderh");
      $sWhereAtividadeRh = "ed01_i_codigo = {$iCodigo}";
      $sSqlAtividadeRh   = $oDaoAtividadeRh->sql_query(null, "*", null, $sWhereAtividadeRh);
      $rsAtividadeRh     = $oDaoAtividadeRh->sql_record($sSqlAtividadeRh);
      
      if ($oDaoAtividadeRh->numrows > 0) {
        
        $oDadosAtividadeRh      = db_utils::fieldsMemory($rsAtividadeRh, 0);
        $this->iCodigo          = $oDadosAtividadeRh->ed01_i_codigo;
        $this->sDescricao       = $oDadosAtividadeRh->ed01_c_descr;
        $this->lPermiteLecionar = $oDadosAtividadeRh->ed01_c_docencia == "S" ? true : false;
        unset($oDadosAtividadeRh);
      }
    }
  }
  
  /**
   * Retorna o codigo da atividade
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Seta o codigo da atividade
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna a descricao da atividade
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta a descricao da atividade
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna se a atividade permite lecionar
   * @return boolean
   */
  public function permiteLecionar() {
    return $this->lPermiteLecionar;
  }
}