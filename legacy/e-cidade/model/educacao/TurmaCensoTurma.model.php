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

/**
 * Classe modelo para ligação entre sa turmas e o censo
 * @package   Educacao
 * @author    Andre Mello - andre.mello@dbseller.com.br
 * @version   
 */
class TurmaCensoTurma {

  /**
   * Turma vinculada ao censo
   * @var Turma
   */
  private $oTurma;

  /**
   * Propriedade que controla se a turma é considerado a principal e que sera vista pelo censo
   * @var boolean
   */
  private $lPrincipal = false;
  
  /**
   * Retorna a turma
   * @return Turma 
   */
  public function getTurma() {
    return $this->oTurma;
  }

  /**
   * Define a turma para vínculo com o censo
   * @param Turma $oTurma
   */
  public function setTurma( Turma $oTurma ) {
    $this->oTurma = $oTurma;
  }

  /**
   * Retorna se a turma é a principal ou não
   * @return boolean
   */
  public function getPrincipal() {
    return $this->lPrincipal == 't' ? true : false;
  }

  /**
   * Define se turma é considerado a principal para o censo
   * @param boolean $lPrincipal
   */
  public function setPrincipal( $lPrincipal ) {
    $this->lPrincipal = $lPrincipal;
  }
}