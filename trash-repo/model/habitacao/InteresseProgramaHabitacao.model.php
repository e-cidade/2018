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


class InteresseProgramaHabitacao {

  protected $iCodigo;
  
  protected $iCandidatoInteresse;
  
  protected $iPrograma;
  
  public $oProcesso;
  
  protected $oInscricao;

  
  public function __construct($iCodigo= '') {

    if (!empty($iCodigo)) {
      
      $oDaoInteressePrograma = db_utils::getDao("habitcandidatointeresseprograma");
      $sSqlInteressePrograma = $oDaoInteressePrograma->sql_query(null,"*",null," ht20_ativo is true and ht13_sequencial = {$iCodigo}");
      $rsInteressePrograma   = $oDaoInteressePrograma->sql_record($sSqlInteressePrograma);
      
      if ($oDaoInteressePrograma->numrows > 0) {
        
        $oDadosInteressePrograma = db_utils::fieldsMemory($rsInteressePrograma,0);
        
        $this->setCodigo            ($oDadosInteressePrograma->ht13_sequencial);
        $this->setCandidatoInteresse($oDadosInteressePrograma->ht13_habitcandidatointeresse);
        $this->setPrograma          ($oDadosInteressePrograma->ht13_habitprograma);     
        $this->setProcesso          ($oDadosInteressePrograma->ht13_codproc);
        
        if ($oDadosInteressePrograma->ht15_sequencial) {
          $this->oInscricao = new InscricaoHabitacao($oDadosInteressePrograma->ht15_sequencial); 
        }
        
        unset($oDadosInteressePrograma);
      }
    }
  }
  
  public function getCandidatoInteresse() {
    return $this->iCandidatoInteresse;
  }
  
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  public function getProcesso() {
    
    if ($this->oProcesso instanceof processoProtocolo) {
      return $this->oProcesso;
    } else {
      return false;
    }
  }
  
  public function getPrograma() {
    return $this->iPrograma;
  }
  
  public function getInscricao(){
    
    if ( $this->oInscricao instanceof InscricaoHabitacao) {
      return $this->oInscricao;
    } else {
      return false;
    }
  }
  
  public function setCandidatoInteresse($iCandidatoInteresse) {
    $this->iCandidatoInteresse = $iCandidatoInteresse;
  }
  
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  public function setProcesso($iProcesso) {
    $this->oProcesso = new processoProtocolo($iProcesso);
  }
  
  public function setPrograma($iPrograma) {
    $this->iPrograma = $iPrograma;
  }
  
  public function addInscricao($sLembrete=''){
    
    $oInscricaoHabitacao = new InscricaoHabitacao();
    $oInscricaoHabitacao->setPrioridade('0');
    $oInscricaoHabitacao->setinteressePrograma($this->getCodigo());
    $oInscricaoHabitacao->setLembrete($sLembrete);
    $oInscricaoHabitacao->salvar();
    
    $this->oInscricao = $oInscricaoHabitacao;    
  }
  
  
  public function salvar() {
    
    $oDaoInteressePrograma = db_utils::getDao('habitcandidatointeresseprograma');

    $oDaoInteressePrograma->ht13_habitcandidatointeresse = $this->getCandidatoInteresse(); 
    $oDaoInteressePrograma->ht13_habitprograma           = $this->getPrograma();

    if ( !$this->getProcesso() instanceof processoProtocolo ) {
      
      $oDaoCandidatoInteresse = db_utils::getDao('habitcandidatointeresse');
      
      $sSqlCandidatoInteresse = $oDaoCandidatoInteresse->sql_query($this->getCandidatoInteresse());
      $rsCandidatoInteresse   = $oDaoCandidatoInteresse->sql_record($sSqlCandidatoInteresse);
      
      if ( !$rsCandidatoInteresse || pg_num_rows($rsCandidatoInteresse) == 0)  {
        throw new Exception('Operaчуo cancelada! Cgm do Candidato nуo cadastrado!');
      }
      
      $oDaoPrograma    = db_utils::getDao('habitprograma');
      $rsDadosPrograma = $oDaoPrograma->sql_record($oDaoPrograma->sql_query($this->getPrograma()));
      
      if ($oDaoPrograma->numrows > 0) {
        $iTipoProc = db_utils::fieldsMemory($rsDadosPrograma,0)->db116_tipoproc;
      } else {
        throw new Exception('Operaчуo cancelada! Tipo de processo nуo cadastrado!');
      }
      
      $oCandidatoInteresse = db_utils::fieldsMemory($rsCandidatoInteresse,0);
      $oProcessoProtocolo  = new processoProtocolo();
      
      $oProcessoProtocolo->setTipoProcesso($iTipoProc);
      $oProcessoProtocolo->setCgm         ($oCandidatoInteresse->z01_numcgm);
      $oProcessoProtocolo->setRequerente  ($oCandidatoInteresse->z01_nome); 
      $oProcessoProtocolo->setObservacao  (''); 
      $oProcessoProtocolo->setDespacho    (''); 
      $oProcessoProtocolo->setInterno     ('f');
      $oProcessoProtocolo->setPublico     ('f');
      $oProcessoProtocolo->setAnoProcesso(db_getsession("DB_anousu"));
      $oProcessoProtocolo->salvar();
      
      $oDaoInteressePrograma->ht13_codproc = $oProcessoProtocolo->getCodProcesso();
    }
    
    if (empty($this->iCodigo)) {
      
      $oDaoInteressePrograma->incluir(null);
      $this->iCodigo = $oDaoInteressePrograma->ht13_sequencial;
    } else {
      
      $oDaoInteressePrograma->ht13_sequencial = $this->iCodigo;
      $oDaoInteressePrograma->alterar($this->iCodigo);
    }
    
    if ($oDaoInteressePrograma->erro_status == 0) {
      throw new Exception("Erro ao salvar interesse em programa\n{$oDaoInteressePrograma->erro_msg}");
    }
  }
}

?>