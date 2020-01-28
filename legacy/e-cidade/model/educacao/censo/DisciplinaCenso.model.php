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


/**
 * Model para as disciplinas do Censo
 * @package Educacao
 * @subpackage Censo
 */
class DisciplinaCenso {
  
  protected $iDisciplina;
  
  protected $sNome;
  
  protected $sNomeCampo;
  /**
   * 
   */
  function __construct() {

  }
  /**
   * Retorna o codigo da Disciplina
   * @return Integer
   */
  public function getDisciplina() {
    return $this->iDisciplina;
  }
  
  /**
   * Define o codigo da Disciplina
   * @param integer $iDisciplina
   */
  public function setDisciplina($iDisciplina) {
    $this->iDisciplina = $iDisciplina;
  }
  
  /**
   * Retorna o nome da Disciplina
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * @param unknown_type $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * @return unknown
   */
  public function getCampoLayout() {
    return $this->sNomeCampo;
  }
  
  /**
   * @param unknown_type $sNomeCampo
   */
  public function setCampoLayout($sNomeCampo) {
    $this->sNomeCampo = $sNomeCampo;
  }

}
?>