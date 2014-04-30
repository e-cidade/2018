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
  
  protected $iGrupoPrograma;
  
  protected $oInteressePrograma;
  
  protected $lAtivo;
    
  public function __construct($iCodigo = '') {
    
    if (!empty($iCodigo)) {
      
      $oDaoInteresse = db_utils::getDao("habitcandidatointeresse");
      $rsInteresses  = $oDaoInteresse->sql_record($oDaoInteresse->sql_query($iCodigo));
      
      if ($oDaoInteresse->numrows > 0) {
        
        $oDadosInteresse = db_utils::fieldsMemory($rsInteresses, 0);
        
        $this->setCodigo       ($oDadosInteresse->ht20_sequencial);
        $this->setCandidato    ($oDadosInteresse->ht20_habitcandidato);
        $this->setGrupoPrograma($oDadosInteresse->ht20_habitgrupoprograma);
        $this->lAtivo          = $oDadosInteresse->ht20_ativo;
        
        if (trim($oDadosInteresse->ht13_sequencial) != '') {
          $this->oInteressePrograma = new InteresseProgramaHabitacao($oDadosInteresse->ht13_sequencial);
        }
        
        unset($oDadosInteresse);
      }
    }
  }
  
  public function getCandidato() {
    return $this->iCandidato;
  }
  
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  public function getGrupoPrograma() {
    return $this->iGrupoPrograma;
  }
  
  public function setCandidato($iCandidato) {
    $this->iCandidato = $iCandidato;
  }
  
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  public function setGrupoPrograma($iGrupoPrograma) {
    $this->iGrupoPrograma = $iGrupoPrograma;
  }
  
  public function isAtivo() {
    if ( $this->lAtivo == 'f') {
      return false;
    } else {
      return true;
    }
  }
  
  public function getInteressePrograma(){
    
    if ( $this->oInteressePrograma instanceof InteresseProgramaHabitacao ) {
      return $this->oInteressePrograma;
    } else {
      return false;
    }
  }
  
  public function addInteressePrograma($iPrograma){
    
    if (empty($this->iCandidato)) {
      throw new Exception('Candidato não informado!');      
    }
    
    $oInteressePrograma = new InteresseProgramaHabitacao();
    $oInteressePrograma->setPrograma($iPrograma);
    $oInteressePrograma->setCandidatoInteresse($this->getCodigo());
    $oInteressePrograma->salvar();
    
    $this->oInteressePrograma = $oInteressePrograma;
  }
  
  public function cancelar($sHistArquivamento=''){
    
  	if (trim($sHistArquivamento) == '' ) {
  	  $sHistArquivamento = 'Cancelamento de Interesse';	
  	}
  	
    if ($this->getInteressePrograma()) {
      $this->getInteressePrograma()->getProcesso()->arquivar($sHistArquivamento);
    }
    
    $this->lAtivo = 'f';
    $this->salvar();
  }
  
  public function salvar() {
    
    $oDaoInteresse = db_utils::getDao('habitcandidatointeresse');
    
    $oDaoInteresse->ht20_sequencial         = $this->getCodigo();
    $oDaoInteresse->ht20_habitcandidato     = $this->getCandidato(); 
    $oDaoInteresse->ht20_habitgrupoprograma = $this->getGrupoPrograma();
    
    if (empty($this->lAtivo)) {
      $oDaoInteresse->ht20_ativo = 't';
    } else {
      $oDaoInteresse->ht20_ativo = $this->lAtivo; 
    }
    
    @$GLOBALS["HTTP_POST_VARS"]["ht20_ativo"] = $this->lAtivo; 
    
    if (empty($this->iCodigo)) {
      
      $oDaoInteresse->incluir(null);
      $this->iCodigo = $oDaoInteresse->ht20_sequencial;
    } else {
      
      $oDaoInteresse->alterar($this->iCodigo);
    }
    
    if ($oDaoInteresse->erro_status == 0) {
      throw new Exception("Erro ao salvar interesse\n{$oDaoInteresse->erro_msg}");
    }
  }
}

?>