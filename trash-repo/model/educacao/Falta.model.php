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
 * Classe para as faltas de uma matricula
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.1 $ 
 * @package educacao 
 */
class Falta {
  
  /**
   * C�digo da falta;
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Periodo da Falta;
   */
  private $iPeriodo;
  
  /**
   * Matricula da falta
   * @var Matricula
   */
  private $oMatricula;
  
  /**
   * Disciplina da falta
   * @var Disciplina
   */
  private $oDisciplina;
  
  /**
   * 
   */
  function __construct($iCodigo = '') {
    $this->iCodigo = $iCodigo;  
  }
  /**
   * C�digo da falta 
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * Retorna o codigo do periodo da falta
   * @return integer
   */
  public function getPeriodo() {

    return $this->iPeriodo;
  }
  
  /**
   * Define o Periodo da Falta
   * @param integer $iPeriodo C�digo do per�odo
   */
  public function setPeriodo($iPeriodo) {

    $this->iPeriodo = $iPeriodo;
  }
  
  /**
   * Retorna a disciplina da falta
   * @return Disciplina
   */
  public function getDisciplina() {

    return $this->oDisciplina;
  }
  
  /**
   * Define a disciplina da falta
   * @param Disciplina $oDisciplina
   */
  public function setDisciplina(Disciplina $oDisciplina) {

    $this->oDisciplina = $oDisciplina;
  }
  
  /**
   * @return Matricula
   */
  public function getMatricula() {

    return $this->oMatricula;
  }
  
  /**
   * @param Matricula $oMatricula
   */
  public function setMatricula(Matricula $oMatricula) {

    $this->oMatricula = $oMatricula;
  }

}

?>