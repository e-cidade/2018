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
 * CLasse para tipo de ensino da Educacao
 * @author Iuri Guntchnigg
 * @package Educacao
 * @version $Revision: 1.3 $
 */
class Ensino {
  
  /**
   * Codigo do Ensino;
   * var integer
   */
  protected $iCodigo;
  
  /**
   * Nome do Ensino;
   * @var string
   */
  protected $sNome;
  
  /**
   * Abreviatura do ensino
   * @var string
   */
  protected $sAbreviatura;
  
  /**
   * 
   */
  function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoEnsino = db_utils::getDao("ensino");
      $sSqlEnsino = $oDaoEnsino->sql_query_file($iCodigo);
      $rsEnsino   = $oDaoEnsino->sql_record($sSqlEnsino);
      
      if ($oDaoEnsino->numrows == 1) {
        
        $oEnsino            = db_utils::fieldsMemory($rsEnsino, 0);
        $this->sNome        = $oEnsino->ed10_c_descr;
        $this->sAbreviatura = $oEnsino->ed10_c_abrev;
      }
      
    }
    $this->iCodigo = $iCodigo;
  }
  
  
  /**
   * Retorna o codigo do ensino
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Define o nome do ensino
   * @param string $sNome nome do ensino
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna o nome do ensino
   * @return string
   */
  public function getNome() {
    return $this->sNome;    
  }
  
  /**
   * Retorna a abreviatura do ensino
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }
  
  /**
   * Seta uma abreviatura para o ensino
   * @param string $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }
}
?>