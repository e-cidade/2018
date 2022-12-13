<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

define("URL_MENSAGEM_CBO", "recursoshumanos.pessoal.CBO.");
/**
 * Cadastro brasileiro de Ocupação
 * 
 * @author Andrio Costa    <andrio.costa@dbseller.com.br
 * @package pessoal
 * @version $Revision: 1.4 $
 */
class CBO {
	
  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo;

  /**
   * Código estrutural do cbo
   * @var string
   */
  private $sEstrutural;

  /**
   * Descrição do CBO
   * @var string
   */
  private $sDescricao;

  /**
   * Tipo do CBO
   * @var integer
   */
  private $iTipo; 

  /**
   * Cria uma instancia de CBO
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo) {
  	
    if (!empty($iCodigo)) {
    	
      $oDaoCbo = new cl_rhcbo();
      $sSqlCbo = $oDaoCbo->sql_query_file($iCodigo);
      $rsCbo   = $oDaoCbo->sql_record($sSqlCbo);
      
      if ($oDaoCbo->numrows == 0) {
        throw new BusinessException(_M(URL_MENSAGEM_CBO."cbo_nao_encontrado"));
      }
      $oDados = db_utils::fieldsMemory($rsCbo, 0);

      $this->iCodigo     = $oDados->rh70_sequencial;
      $this->sEstrutural = $oDados->rh70_estrutural;
      $this->sDescricao  = $oDados->rh70_descr;
      $this->iTipo       = $oDados->rh70_tipo;
    }
  }
 
  /**
   * Retorna codigo
   * @return integer $iCodigo
   */
  public function getCodigo () {
    return $this->iCodigo; 
  }

  /**
   * Define estrutural definido pelo governo
   * @param string $sEstrutural
   */
  public function setEstrutural ($sEstrutural) {
    $this->sEstrutural = $sEstrutural;
  }
  
  /**
   * Retorna estrutural definido pelo governo
   * @return string $sEstrutural
   */
  public function getEstrutural () {
    return $this->sEstrutural;
  }

  /**
   * Define Descricao do CBO
   * @param string $sDescricao
   */
  public function setDescricao ($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna Descricao do CBO
   * @return string $sDescricao
   */
  public function getDescricao () {
    return $this->sDescricao; 
  }

  /**
   * Define tipo do cbo
   * @param integer $iTipo
   */
  public function setTipo ($iTipo) {
    $this->iTipo = $iTipo;
  }
  
  /**
   * Retorna tipo do cbo
   * @return integer $iTipo
   */
  public function getTipo () {
    return $this->iTipo; 
  }
}