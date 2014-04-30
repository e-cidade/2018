<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


class InteresseHabitacao {

  protected $iCodigo;
  
  protected $iCandidato;
  
  protected $iGrupo;
  
  protected $iTipo;
  
  public function __construct($iInteresse = '') {

    if (!empty($iInteresse)) {
      
      $oDaoInteresse  = db_utils::getDao("habitcandidatointeresse");
      $sSqlInteresses = $oDaoInteresse->sql_query_file($iInteresse);
      $rsInteresses   = $oDaoInteresse->sql_record($sSqlInteresses);
      if ($oDaoInteresse->numrows > 0) {
        
        $oDadosInteresse = db_utils::fieldsMemory($rsInteresses, 0);
        $this->setTipo($oDadosInteresse->ht20_tipo);
        $this->setGrupo($oDadosInteresse->ht20_habitgrupoprograma);
        $this->iCodigo    = $oDadosInteresse->ht20_sequencial;
        $this->iCandidato = $oDadosInteresse->ht20_habitcandidato;
        unset($oDadosInteresse);
      }
    }
       
  }
  /**
   * @return unknown
   */
  public function getCandidato() {

    return $this->iCandidato;
  }
  
  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getGrupo() {

    return $this->iGrupo;
  }
  
  /**
   * @param unknown_type $iGrupo
   */
  public function setGrupo($iGrupo) {

    $this->iGrupo = $iGrupo;
  }
  
  /**
   * @return unknown
   */
  public function getTipo() {

    return $this->iTipo;
  }
  
  /**
   * @param unknown_type $iTipo
   */
  public function setTipo($iTipo) {

    $this->iTipo = $iTipo;
  }
  
  public function save($iCandidato) {
    
    $oDaoInteresse = new cl_habitcandidatointeresse;
    $oDaoInteresse->ht20_habitcandidato     = $iCandidato; 
    $oDaoInteresse->ht20_habitgrupoprograma = $this->getGrupo(); 
    $oDaoInteresse->ht20_tipo               = $this->getTipo(); 
    if (empty($this->iCodigo)) {
      
      $this->iCandidato = $iCandidato;
      $oDaoInteresse->incluir(null);
      $this->iCodigo = $oDaoInteresse->ht20_sequencial;
    } else {
      
      $oDaoInteresse->ht20_sequencial = $this->iCodigo;
      $oDaoInteresse->alterar($this->iCodigo);
    }
    if ($oDaoInteresse->erro_status == 0) {
      throw new Exception("Erro ao salvar interesse\n{$oDaoInteresse->erro_msg}");
    }
  }
}

?>