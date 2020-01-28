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


class DBDivisaoDepartamento {
  
  /**
   * Codigo
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Descricao
   * @var string
   */
  protected $sDescricao;
  
  /**
   * Departamento
   * @var DBDepartamento
   */
  protected $oDepartamento;
  
  /**
   * Situacao da Divisao
   * @var boolean
   */
  protected $lAtivo;
  
  /**
   * CGM do Responsavel
   * @var CgmBase
   */
  protected $oCGM;
  
  
  /**
   * Constroi o objeto com os dados retornados do codigo passado
   * @param  integer $iCodigo
   * @throws BusinessException
   * @return DBDivisaoDepartamento
   */
  public function __construct($iCodigo=null) {
    
    $this->iCodigo = $iCodigo;
    if (!empty($this->iCodigo)) {
      
      $oDaoDepartamentoDivisao = db_utils::getDao('departdiv');
      $sSqlBuscaDivisao        = $oDaoDepartamentoDivisao->sql_query_file($this->iCodigo);
      $rsBuscaDivisao          = $oDaoDepartamentoDivisao->sql_record($sSqlBuscaDivisao);
      
      if ($oDaoDepartamentoDivisao->erro_status == "0") {
        throw new BusinessException("Não foi possível localizar a divisão {$this->iCodigo}.");
      }
      
      $oDadoDivisao        = db_utils::fieldsMemory($rsBuscaDivisao, 0);
      $this->iCodigo       = $oDadoDivisao->t30_codigo;
      $this->sDescricao    = $oDadoDivisao->t30_descr;
      $this->oDepartamento = new DBDepartamento($oDadoDivisao->t30_depto);
      $this->lAtivo        = $oDadoDivisao->t30_ativo;
      $this->oCGM          = CgmFactory::getInstanceByCgm($oDadoDivisao->t30_numcgm);
      unset($oDadoDivisao);
    }
    return true;
  }
  
  
  
  /**
   * Codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o Codigo
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a descricao
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a Descricao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o Departamento
   * @return DBDepartamento
   */
  public function getDepartamento() {
    return $this->oDepartamento;
  }

  /**
   * Seta o Departamento
   * @param DBDepartamento $oDepartamento
   */
  public function setDepartamento(DBDepartamento $oDepartamento) {
    $this->oDepartamento = $oDepartamento;
  }

  /**
   * Retorna a situacao da divisao
   * @return boolean
   */
  public function getAtivo() {
    return $this->lAtivo;
  }
  
  /**
   * Seta a situacao
   * @param boolean $lAtivo
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * Retorna o CGM (Objeto)
   * @return CgmBase
   */
  public function getCGM() {
    return $this->oCGM;
  }

  /**
   * Seta o CGM do responsavel
   * @param Cgm $oCGM
   */
  public function setCGM($oCGM) {
    $this->oCGM = $oCGM;
  }
}