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

/**
 *@package Habitacao
 */
class CandidatoHabitacao {
  
  /**
   * codigo do candidato
   *
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * cgm do canditado 
   *
   * @var CgmFisica
   */
  protected $oCgm;
  
  /**
   * interesse do canditado
   *
   * @var InteresseHabitacao
   */
  protected $aInteresse = array();

  /**
   * Ficha Sócio-Econômica
   *
   * @var iAvalicao
   */
  protected $iAvaliacao;  
  
  
  
  
  public function __construct($iCgm=null) {

    if (empty($iCgm)) {
      throw new Exception('CGM do Candidato não informado!');    
    }
    
    $this->setCGM($iCgm);
    
    $oDaoCandidato   = db_utils::getDao("habitcandidato");
    $sWhereCandidato = " ht10_numcgm = {$iCgm}"; 
    $sSqlCandidato   = $oDaoCandidato->sql_query_exame(null,"*",null,$sWhereCandidato);
    $rsCandidato     = $oDaoCandidato->sql_record($sSqlCandidato);
       
    if ($oDaoCandidato->numrows > 0 ) {
         
      $oDadosCandidato = db_utils::fieldsMemory($rsCandidato,0);
         
      $this->setCodigo                ($oDadosCandidato->ht10_sequencial);
      $this->setCadastroSocioEconomico($oDadosCandidato->ht12_avaliacaogruporesposta);
         
      // Consulta e gera todos interesses 
         
      $oDaoInteresse = db_utils::getDao("habitcandidatointeresse");
      $sSqlInteresse = $oDaoInteresse->sql_query_file(null,
                                                      "ht20_sequencial", 
                                                      null, 
                                                      "ht20_habitcandidato = {$this->getCodigo()}");
                                                          
      $rsInteresse   = $oDaoInteresse->sql_record($sSqlInteresse);
      $aInteresse    = db_utils::getColectionByRecord($rsInteresse);
         
      foreach ($aInteresse as $oInteresse) {
        $this->aInteresse[$oInteresse->ht20_sequencial] = new InteresseHabitacao($oInteresse->ht20_sequencial);
      }
    }
  }
  
  public function addInteresseGrupo($iGrupoPrograma){
    
    $lInsereInteresse = true;
    
    foreach ( $this->aInteresse as $iIndInteresse => $oInteresse ) {
      if ($oInteresse->isAtivo() && $oInteresse->getGrupoPrograma() == $iGrupoPrograma) {
        $lInsereInteresse = false;
      }
    }      
    
    if ($lInsereInteresse) {
      $this->addInteresse($iGrupoPrograma);
    }
  }
  
  public function addInteressePrograma($iPrograma){
    
    $lInsereInteresse = true;
    
    $oDaoPrograma   = db_utils::getDao('habitprograma');
    $rsPrograma     = $oDaoPrograma->sql_record($oDaoPrograma->sql_query_file($iPrograma,"ht01_habitgrupoprograma"));
    $iGrupoPrograma = db_utils::fieldsMemory($rsPrograma,0)->ht01_habitgrupoprograma;     
      
    foreach ( $this->aInteresse as $iIndInteresse => $oInteresse ) {
      
      if ($oInteresse->isAtivo()  ) {
        
        if ($oInteresse->getGrupoPrograma() == $iGrupoPrograma) {
          
          if (!$oInteresse->getInteressePrograma()) {
            
            $this->aInteresse[$iIndInteresse]->addInteressePrograma($iPrograma);
            $lInsereInteresse = false;
          } else if ($oInteresse->getInteressePrograma()->getPrograma() == $iPrograma) {
            $lInsereInteresse = false;
          }
        }
      }
    }    
    
    if ($lInsereInteresse) {
      $this->addInteresse($iGrupoPrograma,$iPrograma);    
    }
  }  
  
  private function addInteresse($iGrupoPrograma='',$iPrograma=''){

    if (empty($this->iCodigo)) {
      throw new Exception('Candidato não cadastrado!');
    }
        
    if (empty($iGrupoPrograma)) {
      throw new Exception('Grupo do programa não informado!');
    }        
    
    $oInteresse = new InteresseHabitacao(null);
      
    $oInteresse->setCandidato    ($this->getCodigo());
    $oInteresse->setGrupoPrograma($iGrupoPrograma);
    $oInteresse->salvar();
     
    if (!empty($iPrograma)) {
      $oInteresse->addInteressePrograma($iPrograma);
    }
    
    $this->aInteresse[$oInteresse->getCodigo()] = $oInteresse;
  }  
  
  public function cancelaInteresseGrupo($iGrupoPrograma){
    
    foreach ($this->aInteresse as $iIndInteresse => $oInteresse) {
      
      if ($oInteresse->getGrupoPrograma() == $iGrupoPrograma && $oInteresse->isAtivo() ) {
        $this->aInteresse[$iIndInteresse]->cancelar();      
      }
    }
  }
  
  public function cancelaInteressePrograma($iPrograma) {

    foreach ($this->aInteresse as $iIndInteresse => $oInteresse) {
      
      if ($oInteresse->isAtivo() && $oInteresse->getInteressePrograma()) {
        if ($oInteresse->getInteressePrograma()->getPrograma() == $iPrograma) {
          $this->aInteresse[$iIndInteresse]->cancelar();      
        }
      }
    }    
  }

  public function salvar() {
    
    $oDaoCanditado           = db_utils::getDao("habitcandidato");
    $oDaoFichaSocioEconomica = db_utils::getDao("habitfichasocioeconomica");
    
    $oDaoCanditado->ht10_sequencial = $this->getCodigo();
    $oDaoCanditado->ht10_numcgm     = $this->oCgm->getCodigo();
    
    if (!empty($this->iCodigo)) {
      
      $oDaoCanditado->alterar($this->getCodigo());
    } else {
      
      $oDaoCanditado->incluir(null);
      $this->setCodigo($oDaoCanditado->ht10_sequencial);
    }
    
    if ($oDaoCanditado->erro_status == 0) {
      throw new Exception("Erro ao incluir candidato!\n{$oDaoCanditado->erro_banco}");
    }

    $oDaoFichaSocioEconomica->excluir(null,"ht12_habitcandidato = {$this->getCodigo()} ");

    if ($oDaoFichaSocioEconomica->erro_status == 0) {
      throw new Exception("Erro ao incluir ficha sócio-econômico!\n{$oDaoFichaSocioEconomica->erro_banco}");
    }    
    
    $oDaoFichaSocioEconomica->ht12_habitcandidato         = $this->getCodigo();
    $oDaoFichaSocioEconomica->ht12_avaliacaogruporesposta = $this->getCadastroSocioEconomico();
    $oDaoFichaSocioEconomica->incluir(null);
    
    if ($oDaoFichaSocioEconomica->erro_status == 0) {
      throw new Exception("Erro ao incluir ficha sócio-econômico!\n{$oDaoFichaSocioEconomica->erro_banco}");
    }
    
    
  }
  
  
  
  public function getCodigo() {
    return $this->iCodigo;
  }    
  
  public function getCgm() {
    
    if ( $this->oCgm instanceof CgmFisico ) {
      return $this->oCgm; 
    } else {
      return false;
    }
  }  
  
  public function getCadastroSocioEconomico() {
    return $this->iAvaliacao;
  }   
  
  public function getInteresse($iInd='') {
    
    if (trim($iInd)!= '') {
      return $this->aInteresse[$iInd];
    } else {
      return $this->aInteresse;
    }
  }
  
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }  
  
  public function setCGM($iCgm) {
    $this->oCgm = new CgmFisico($iCgm);
  }
  
  public function setCadastroSocioEconomico($iAvaliacao) {
    $this->iAvaliacao = $iAvaliacao;
  }  
  
}

?>