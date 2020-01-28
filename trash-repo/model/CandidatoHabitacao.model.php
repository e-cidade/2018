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
   * gera uma nova inscricao para o programa so usuário
   *
   * @var InscricaoHabitacao
   */
  protected $oInscricao;
  
  /**
   * interesse do canditado
   *
   * @var InteresseHabitacao
   */
  protected $aInteresses;
  
  /**
   * avaliacao socio economica do usuário
   *
   * @var integer
   */
  protected $iAvaliacao;
  
  /**
   * 
   */
  public function __construct($iCandidato= null) {

     if (!empty($iCandidato)) {
       
       $oDaoCandidato = db_utils::getDao("habitcandidato");
       $sSqlCandidato = $oDaoCandidato->sql_query_exame($iCandidato);
       $rsCandidato   = $oDaoCandidato->sql_record($sSqlCandidato);
       if ($oDaoCandidato->numrows == 1) {
         
         $oDadosCandidato = db_utils::fieldsMemory($rsCandidato, 0);
         $this->iCodigo   = $oDadosCandidato->ht10_sequencial;
         $this->setCGM(new CgmFisico($oDadosCandidato->ht10_numcgm));
         
         /**
          * retornamos os dados dos interesses
          */
         $oDaoInteresse  = db_utils::getDao("habitcandidatointeresse");
         $sSqlInteresses = $oDaoInteresse->sql_query_file(null,
                                                          "ht20_sequencial", 
                                                          null, 
                                                          "ht20_habitcandidato={$this->iCodigo}"
                                                          );
                                                          
         $rsInteresses   = $oDaoInteresse->sql_record($sSqlInteresses);
         $aInteresses    = db_utils::getColectionByRecord($rsInteresses);
         foreach ($aInteresses as $oInteresseBase) {
         
           $oInteresse = new InteresseHabitacao($oInteresseBase->ht20_sequencial);
           $this->aInteresses[] = $oInteresse;
         }
         
         /**
          * retornamos os dados dos programas
          */
         $oDaoPrograma  = db_utils::getDao("habitinscricao");
         $sSqlProgramas = $oDaoPrograma->sql_query_file(null,
                                                          "ht15_sequencial", 
                                                          null, 
                                                          "ht15_candidato={$this->iCodigo}"
                                                          );
         $rsProgramas   = $oDaoPrograma->sql_record($sSqlProgramas);
         $aProgramas    = db_utils::getColectionByRecord($rsProgramas);
         foreach ($aProgramas as $oProgramaBase) {
         
           $oInscricao = new InscricaoHabitacao($oProgramaBase->ht15_sequencial);
           $this->oInscricao = $oInscricao;
         }
         $this->iAvaliacao = $oDadosCandidato->ht12_avaliacaogruporesposta;
       }
     }
  }
  
  public function addInteresse($iGrupoInteresse) {
    
    $oInteresse = new InteresseHabitacao(null);
    $oInteresse->setGrupo($iGrupoInteresse); 
    $oInteresse->setTipo(1);
    $this->aInteresses[] = $oInteresse; 
  }
  
  public function setInscricao($iInscricao) {
    
    if ($this->oInscricao instanceof InscricaoHabitacao ) {
      $this->oInscricao->setPrograma($iInscricao);
    } else {
      
      $this->oInscricao = new InscricaoHabitacao();
      $this->oInscricao->setPrioridade(0);
      $this->oInscricao->setSituacao(1);
      $this->oInscricao->setPrograma($iInscricao);
      $this->oInscricao->setTipoInscricao(2);
    }
  }
  
  public function getInteresse() {
    return $this->aInteresses;
  }
  
  /**
   * retorna os dados da inscricao da contrato 
   *
   * @return InscricaoHabitacao
   */
  public function getInscricao() {
    return $this->oInscricao;
  }
  public function setCadastroSocioEconomico($iAvaliacao) {
    $this->iAvaliacao = $iAvaliacao;
  }
  
  public function save() {
    
    $oDaoCanditado = db_utils::getDao("habitcandidato");
    $oDaoCanditado->ht10_numcgm = $this->oCgm->getCodigo();
    if (!empty($this->iCodigo)) {
      
      $oDaoCanditado->ht10_sequencial = $this->iCodigo;
      $oDaoCanditado->alterar($this->iCodigo);
    } else {
      
      $oDaoCanditado->incluir(null);
      $this->iCodigo = $oDaoCanditado->ht10_sequencial;
    }
    if ($oDaoCanditado->erro_status == 0) {
      
      $sErroMsg  = 'não foi possivel salvar dados do canditado!';
      $sErroMsg .= "Erro retorno do sistema: \n{$oDaoCanditado->erro_banco}";
    }
    
    if ($this->oInscricao != null) {
      $this->oInscricao->save($this->iCodigo); 
    }
    $oDaoInteresse  = db_utils::getDao("habitcandidatointeresse");
    $oDaoInteresse->excluir(null, "ht20_habitcandidato = {$this->iCodigo}");
    foreach ($this->aInteresses as $oInteresse) {
      $oInteresse->save($this->iCodigo);    
    }
    $oDaoAvaliacao  = db_utils::getDao("habitfichasocioeconomica");
    $oDaoAvaliacao->excluir(null, "ht12_habitcandidato = {$this->iCodigo}");
    if ($oDaoAvaliacao->erro_status == 0) {
      throw new Exception("Erro sao salvar interesses do candidato!\n{$oDaoAvaliacao->erro_msg}");
    }
    $oDaoAvaliacao->ht12_avaliacaogruporesposta = $this->iAvaliacao;
    $oDaoAvaliacao->ht12_habitcandidato         = $this->iCodigo;
    $oDaoAvaliacao->incluir(null);
    if ($oDaoAvaliacao->erro_status == 0) {
      throw new Exception("Erro sao salvar interesses do candidato!\n{$oDaoAvaliacao->erro_msg}");
    }
  }
  
  /**
   * define o cgm do canditado 
   *
   * @param CgmFisico $oCgm cgm do candidato
   * @return CandidatoHabitacao
   */
  public function setCGM(CgmFisico $oCgm) {
    
    $this->oCgm = $oCgm;
    return $this;
  }
  
  /**
   * Retorna o cgm do candidato
   *
   * @return CgmFisico
   */
  public function getCgm() {
    return $this->oCgm; 
  }
  public function setAvaliacao($iAvaliacao) {
    $this->iAvaliacao = $iAvaliacao;
  }
  
  public function getAvaliacao() {
    return $this->iAvaliacao;
  }
  
  public function removeInteresses() {

    $this->aInteresses = array();
  }
 
}

?>