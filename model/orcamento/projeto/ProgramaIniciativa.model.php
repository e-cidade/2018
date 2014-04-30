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
 * Classe 
 * @author bruno.silva
 * @package orcamento
 * @version $Revision: 1.1 $
 */
class ProgramaIniciativa {
  
  /**
   * Sequencial da tabela orciniciativa
   * @var integer
   */
  private $iCodigoSequencial;
  
  /**
   * Descriчуo sucinta da iniciativa
   * @var string
   */
  private $sIniciativa;
  
  /**
   * Descriчуo completa da iniciativa
   * @var string
   */
  private $sDescricao;

  public function getCodigoSequencial() {
    return $this->iCodigoSequencial;
  }
  
  public function setCodigoSequencial($iCodigoSequencial) {
    $this->iCodigoSequencial = $iCodigoSequencial;
  }
  
  public function getIniciativa() {
    return $this->sIniciativa;
  }
  
  public function setIniciativa($sIniciativa) {
    $this->sIniciativa = $sIniciativa;
  }
  
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  
  function __construct($iCodigoSequencial = null) {
    
    if (!empty($iCodigoSequencial)) {
      
      $oDAOOrciniciativa = db_utils::getDao("orciniciativa");
      $sSQL              = $oDAOOrciniciativa->sql_query_file(null, "*", null, "o147_sequencial ={$iCodigoSequencial}");
      $rsResultado       = $oDAOOrciniciativa->sql_query($sSQL);
      
      if ($oDAOOrciniciativa->erro_status == 0) {
        
        $sMensagemErro  = "Erro Tщcnico: erro ao carregar dados da Iniciativa {$iCodigoSequencial}.";
        $sMensagemErro .= $oDAOOrciniciativa->erro_msg;
        throw new Exception($sMensagemErro);
      }
      
      $oIniciativa             = db_utils::fieldsMemory($rsResultado, 0);
      $this->iCodigoSequencial = $oIniciativa->o147_sequencial;
      $this->sDescricao        = $oIniciativa->o147_descricao;
      $this->sIniciativa       = $oIniciativa->o147_iniciativa;
    }
  }
  
  /**
   * Salva os dados da iniciativa, caso o sequъncial seja nulo
   * Do contrсrio, altera a iniciativa 
   */
  function salvar() {
    
    $oDAOOrciniciativa                  = db_utils::getDao("orciniciativa");
    $oDAOOrciniciativa->o147_descricao  = $this->sDescricao;  
    $oDAOOrciniciativa->o147_iniciativa = $this->sIniciativa;

    if (empty($this->iCodigoSequencial)) {
      $oDAOOrciniciativa->incluir(null);
    } else {
      
      $oDAOOrciniciativa->o147_sequencial = $this->iCodigoSequencial;
      $oDAOOrciniciativa->alterar($this->iCodigoSequencial);
    }
    
    if ($oDAOOrciniciativa->erro_status == 0) {
      $sMensagemErro  = "Erro Tщcnico: erro ao salvar dados da Iniciativa.";
      $sMensagemErro .= $oDAOOrciniciativa->erro_msg;
      throw new Exception($sMensagemErro);
    }
  }
  
  
  
  
}

?>