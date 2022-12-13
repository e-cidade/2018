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
 * Model com os tipo de situações do cadastro único
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package social
 * @subpackage cadastrounico
 */
class TipoSituacaoCadastroUnico {
  
  /**
   * Código PK que identifica o tiposituacaocadastrounico
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Descrição da situação
   * @var string
   */
  private $sDescricao;

  /**
   * Construtor
   * @param string $iCodigo
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oTipoSituacao    = new cl_tiposituacaocadastrounico();
      $sSqlTipoSituacao = $oTipoSituacao->sql_query_file($iCodigo);
      $rsTipoSituacao   = $oTipoSituacao->sql_record($sSqlTipoSituacao);
      
      if ($oTipoSituacao->numrows == 1) {
        
        $oDados           = db_utils::fieldsMemory($rsTipoSituacao, 0);
        $this->iCodigo    = $oDados->as11_sequencial;
        $this->sDescricao = $oDados->as11_situacao;
      }
    }
  }
  
  public function getCodigo() {
    
    return $this->iCodigo;
  }
  
  /**
   * Seta a descrição da situação
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna descrição da situação
   * @return string
   */
  public function getDescricao() {
    
    return $this->sDescricao;
  }
}