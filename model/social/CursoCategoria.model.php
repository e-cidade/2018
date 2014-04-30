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
 * Categoria do Curso
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package model
 * @subpackage social
 * @version $Revision: 1.1 $
 */
class CursoCategoria {
  
  private $iCodigo;
  private $sDescricao;
  private $sObservacao;
  
  public function __construct($iCodigo) {
    
    if (!empty($iCodigo)) {
      
      $oDaoCategoria = new cl_tabcurritipo();
      $sWhere        = "h02_codigo = {$iCodigo}";
      $sSqlCategoria = $oDaoCategoria->sql_query_file(null, "*", null, $sWhere);
      $rsCategoria   = $oDaoCategoria->sql_record($sSqlCategoria);
      
      if ($oDaoCategoria->numrows == 1) {

        $oDados            = db_utils::fieldsMemory($rsCategoria, 0);
        $this->iCodigo     = $oDados->h02_codigo; 
        $this->sDescricao  = $oDados->h02_descr;
        $this->sObservacao = $oDados->h02_obs;
      }
    }
  }  

  /**
   * 
   * @return integer
   */
  public function getCodigo() {
      return $this->iCodigo;
  }


  /**
   * 
   * @return 
   */
  public function getDescricao() {
      return $this->sDescricao;
  }

  /**
   * 
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
      $this->sDescricao = $sDescricao;
  }

  /**
   * 
   * @return 
   */
  public function getObservacao() {
      return $this->sObservacao;
  }

  /**
   * 
   * @param $sObservacao
   */
  public function setObservacao($sObservacao) {
      $this->sObservacao = $sObservacao;
  }
}