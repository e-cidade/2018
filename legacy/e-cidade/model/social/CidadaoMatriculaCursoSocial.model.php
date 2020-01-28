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
 * Model responsável pela criação dos cursos sociais
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package model
 * @subpackage social
 * @version $Revision: 1.7 $
 */
class CidadaoMatriculaCursoSocial {

  /**
   * Matrícula do curso
   * @var integer
   */
  private $iCodigo;

  /**
   * Curso Social
   * @var CursoSocial
   */
  private $oCursoSocial = null;

  /**
   * Cidadão
   * @var Cidadao
   */
  private $oCidadao = null;

  /**
   * Observação
   * @var string
   */
  private $sObservacao;

  private $aAusencias = array(); 
  
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoCursoCidadao = new cl_cursosocialcidadao();
      $sWhere           = "as22_sequencial = {$iCodigo}";
      $sSqlCursoCidadao = $oDaoCursoCidadao->sql_query_file(null, "*", null, $sWhere);
      $rsCursoCidadao   = $oDaoCursoCidadao->sql_record($sSqlCursoCidadao);

      if ($oDaoCursoCidadao->numrows == 1) {

        $oDados = db_utils::fieldsMemory($rsCursoCidadao, 0);
        
        $this->iCodigo      = $oDados->as22_sequencial ;
        $this->oCursoSocial = CursoSocialRepository::getCursoSocialByCodigo($oDados->as22_cursosocial);
        $this->oCidadao     = CidadaoRepository::getCidadaoByCodigo($oDados->as22_cidadao);
        $this->sObservacao  = $oDados->as22_observacao; 
      }
    }
  }
  
  /**
   * Matricula um cidadao a um curso
   * @throws DBException
   * @throws BusinessException
   * @return boolean
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }
    
    $oDaoCursoCidadao = new cl_cursosocialcidadao();
    $sWhereValida     = "     as22_cidadao     = {$this->oCidadao->getCodigo()}";
    $sWhereValida    .= " and as22_cidadao_seq = {$this->oCidadao->getSequencialInterno()}";
    $sWhereValida    .= " and as22_cursosocial = {$this->oCursoSocial->getCodigo()}";
    $sSqlValida       = $oDaoCursoCidadao->sql_query_file(null, "1", null, $sWhereValida);
    $rsValida         = $oDaoCursoCidadao->sql_record($sSqlValida);
    
    if ($oDaoCursoCidadao->numrows > 0) {
      
      $sMsgErro  = "Cidadão já está matriculado no curso selecionado.";
      throw new BusinessException($sMsgErro);
    } 
    
    
    $oDaoCursoCidadao->as22_sequencial  = null;
    $oDaoCursoCidadao->as22_cursosocial = $this->oCursoSocial->getCodigo();
    $oDaoCursoCidadao->as22_cidadao     = $this->oCidadao->getCodigo();
    $oDaoCursoCidadao->as22_cidadao_seq = $this->oCidadao->getSequencialInterno();
    $oDaoCursoCidadao->as22_observacao  = $this->sObservacao;
    
    if (empty($this->iCodigo)) {
      
      $oDaoCursoCidadao->incluir(null);
      $this->iCodigo = $oDaoCursoCidadao->as22_sequencial;
    } else {
      
      $oDaoCursoCidadao->as22_sequencial = $this->iCodigo;
      $oDaoCursoCidadao->alterar($this->iCodigo);
    }
    
    if ($oDaoCursoCidadao->erro_status == 0) {

      $sMsgErro  = "Não foi possível matricular o Cidadão ao curso selecionado.";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoCidadao->erro_msg);
      throw new BusinessException($sMsgErro);
    }
    return true;
  }

  /**
   * Retorna Matrícula do curso
   * @return integer
   */
  public function getCodigo() {
      return $this->iCodigo;
  }

  /**
   * Retorna o Curso social
   * @return CursoSocial 
   */
  public function getCursoSocial() {
      return $this->oCursoSocial;
  }

  /**
   * Seta um Curso social
   * @param $oCursoSocial
   */
  public function setCursoSocial(CursoSocial $oCursoSocial) {
      $this->oCursoSocial = $oCursoSocial;
  }

  /**
   * Retorna o cidadão
   * @return 
   */
  public function getCidadao() {
      return $this->oCidadao;
  }

  /**
   * Seta o Cidadão
   * @param $oCidadao
   */
  public function setCidadao(Cidadao $oCidadao) {
      $this->oCidadao = $oCidadao;
  }

  /**
   * Retorna uma observação 
   * @return string
   */
  public function getObservacao() {
      return $this->sObservacao;
  }

  /**
   * Seta uma observação
   * @param $sObservacao
   */
  public function setObservacao($sObservacao) {
      $this->sObservacao = $sObservacao;
  }

  public function removerMatricula() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }
    
    $oDaoCursoCidadao         = new cl_cursosocialcidadao();
    $oDaoCursoCidadaoAusencia = new cl_cursosocialcidadaoausencia();
    
    $sWhereAusencia = " as18_cursocialcidadao = {$this->iCodigo} ";
    $oDaoCursoCidadaoAusencia->excluir(null, $sWhereAusencia);
    
    if ($oDaoCursoCidadaoAusencia->erro_status == 0) {
    
      $sMsgErro  = "Não foi possível excluir as ausências lançada para o cidadão:\n".
      $sMsgErro .= $this->getCidadao()->getNome() . "\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoDiaAula->erro_msg);
      throw new BusinessException($sMsgErro);
    }  
    
    $sWhereMatricula = "as22_sequencial = {$this->iCodigo}";
    $oDaoCursoCidadao->excluir(null, $sWhereMatricula);
    if ($oDaoCursoCidadao->erro_status == 0) {
    
      $sMsgErro  = "Não foi possível excluir a matrícula do cidadão:\n".
      $sMsgErro .= $this->getCidadao()->getNome() . "\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoDiaAula->erro_msg);
      throw new BusinessException($sMsgErro);
    }
  }

  /**
   * Retorna um array de stdClass com as faltas do Cidadão para o curso
   *
   *  [{iCursoAula:'1', oDia:DBDate}]
   *    iCursoAula => sequencial da tabela "cursosocialaula"
   *    oDia       => DBDate com a data correspondente ao sequencial
   * @example
   *    foreach($this->getAusencias() as $oAusencia) {
   *      echo $oAusencia->iCursoAula;
   *      echo $oAusencia->oDia->convetTo(DBDate::DATA_EN);
   *    }
   * @return multitype:stdClass
   */
  public function getAusencias() {
  
    if (count($this->aAusencias) == 0) {
  
      $oDaoAusencias = new cl_cursosocialcidadaoausencia();
      $sWhere        = " as18_cursocialcidadao = {$this->iCodigo} ";
      $sSqlAusencias = $oDaoAusencias->sql_query(null, "as21_sequencial, as21_dataaula", "as21_dataaula", $sWhere);
      $rsAusencias   = $oDaoAusencias->sql_record($sSqlAusencias);
      $iLinhas       = $oDaoAusencias->numrows;
  
      if ($iLinhas > 0) {
  
        for ($i = 0; $i < $iLinhas; $i++) {
  
          $oDados = db_utils::fieldsMemory($rsAusencias, $i);
  
          $oAusencia                                  = new stdClass();
          $oAusencia->iCursoAula                      = $oDados->as21_sequencial;
          $oAusencia->oDia                            = new DBDate($oDados->as21_dataaula);
          $this->aAusencias[$oDados->as21_sequencial] = $oAusencia;
        }
      }
    }
    return $this->aAusencias;
  }
  
  /**
   * Remove todas as ausências do Cidadão para o Curso
   * Deve ser usado antes de setar as ausências
   * @throws BusinessException
   */
  public function removeTodasAusencias () {
  
    $oDaoAusencias = new cl_cursosocialcidadaoausencia();
    $sWhere        = " as18_cursocialcidadao = {$this->iCodigo} ";
    $oDaoAusencias->excluir(null, $sWhere);
  
    if ($oDaoAusencias->erro_status == 0) {
  
      $sMsgErro  = "Não foi possível excluir as ausências lançada para o cidadão:\n".
      $sMsgErro .= $this->getCidadao()->getNome() . "\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoDiaAula->erro_msg);
      throw new BusinessException($sMsgErro);
    }
  
    $this->aAusencias = array();
  }
  /**
   * Seta uma ausencia para o Cidadao no Curso atual
   * @param integer $iCursoAula sequencial da tabela "cursosocialaula"
   */
  public function setAusencias($iCursoAula, DBDate $oData) {
  
    $oAusencia             = new stdClass();
    $oAusencia->iCursoAula = $iCursoAula;
    $oAusencia->oDia       = $oData;
    $this->aAusencias[]    = $oAusencia;
  }
  
  
  /**
   * Salva as ausências do cidadão
   * @throws DBException
   * @throws BusinessException
   */
  public function salvarAusencias () {
  
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }
  
    $oDaoAusencias = new cl_cursosocialcidadaoausencia();
    foreach ($this->getAusencias() as $oAusencia) {
  
      $oDaoAusencias->as18_sequencial       = null;
      $oDaoAusencias->as18_cursosocialaula  = $oAusencia->iCursoAula;
      $oDaoAusencias->as18_cursocialcidadao = $this->iCodigo;
  
      $oDaoAusencias->incluir(null);
  
      if ($oDaoAusencias->erro_status == 0) {
  
        $sMsgErro  = "Não foi lançar ausência lançada para o cidadão:\n".
            $sMsgErro .= $this->getCidadao()->getNome() . "\n";
        $sMsgErro .= str_replace("\\n", "\n", $oDaoDiaAula->erro_msg);
        throw new BusinessException($sMsgErro);
      }
    }
  }
  
}