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
 * Model para os UF do Censo
 * @package educacao
 * @subpackage censo
 * @version $Revision: 1.2 $
 */
class CensoUF {
  
  /**
   * Sigla do estado
   * @var string
   */
  protected $sUF;
  
  /**
   * Código IBGE do Estado
   * @integer
   */
  protected $iCodigo;
  
  /**
   * Nome do estado
   * @var string 
   */
  protected $sNomeEstado;
  
  /**
   * Método Construtor
   * @param $iCodigo Codigo do estado
   * @param $sUf Uf do estado
   * @param $sNome nome do estado
   */
  public function __construct($iCodigo, $sUf, $sNome) {
    
    $this->iCodigo      = $iCodigo;
    $this->sUF          = $sUf;
    $this->sNomeEstado  = $sNome;
  }
  
  /**
   * Retorna o código do estado
   * @return Codigo do Estado
   * 
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Retorna o Nome do estado
   * @return string
   */
  public function getNomeEstado() {
    return $this->sNomeEstado;
  }
  /* 
   * Retorna a UF do Estado
   * @return string
   */
  public function getUF() {
    return $this->sUF;
  }
}

?>