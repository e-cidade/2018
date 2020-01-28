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


define("URL_MENSAGEM_SITUACAOALUNO", "educacao.escola.SituacaoAluno.");

/**
 * Classe para manipular a situa��o do aluno
 *
 * @package educacao
 * @subpackage classificacao
 * @author Trucolo <trucolo@dbseller.com.br>
 * @version $Revision: 1.4 $
 *
 */

class SituacaoAluno {

  /**
   * C�digo do alunocurso
   * @var integer
   */
  private $iCodigoAlunoCurso;

  /**
   * Inst�ncia de Escola
   * @var Escola
   */
  private $oEscola;

  /**
   * Inst�ncia de Aluno
   * @var Aluno
   */
  private $oAluno;

  /**
   * Inst�ncia de BaseCurricular
   * @var BaseCurricular
   */
  private $oBase;

  /**
   * Inst�ncia de Calendario
   * @var Calendario
   */
  private $oCalendario;

  /**
   * Descri��o da situa��o em alunocurso
   * @var string
   */
  private $sSituacaoAlunoCurso;

  /**
   * Inst�ncia de BaseCurricular anterior
   * @var BaseCurricular
   */
  private $oBaseAnterior;

  /**
   * Inst�ncia de Calendario anterior
   * @var Calendario
   */
  private $oCalendarioAnterior;

  /**
   * Descri��o da situa��o anterior em alunocurso
   * @var string
   */
  private $sSituacaoAnterior;

  /**
   * C�digo de alunopossib
   * @var integer
   */
  private $iCodigoAlunoPossib;

  /**
   * Inst�ncia de Etapa
   * @var Etapa
   */
  private $oEtapa;

  /**
   * Inst�ncia de Turno
   * @var Turno
   */
  private $oTurno;

  /**
   * Inst�ncia de Turma
   * @var Turma
   */
  private $oTurmaAnterior;

  /**
   * Descri��o do resultado anterior de alunopossib
   * @var string
   */
  private $sResultadoAnterior;

  /**
   * Descri��o da situa��o de alunopossib
   * @var string
   */
  private $sSituacaoAlunoPossib;

  /**
   * Cria uma inst�ncia de SituacaoAluno
   * @param integer $iCodigoAlunoCurso
   * @throws ParameterException
   */
  public function __construct(Aluno $oAluno) {

      $oDaoAlunoCursoPossib = new cl_alunocurso();
      
      if ($oAluno->getCodigoAluno() == "") {
      	return ;
      }
      
      $sWhere               = "ed56_i_aluno = {$oAluno->getCodigoAluno()}";
      $sSqlAlunoCursoPossib = $oDaoAlunoCursoPossib->sql_query(null, 'alunocurso.*, alunopossib.*', null, $sWhere);
      $rsAlunoCursoPossib   = $oDaoAlunoCursoPossib->sql_record($sSqlAlunoCursoPossib);

      if ($oDaoAlunoCursoPossib->numrows == 0) {
        return;
      }

      $oAlunoCursoPossib = db_utils::fieldsMemory($rsAlunoCursoPossib, 0);

      $this->iCodigoAlunoCurso    = $oAlunoCursoPossib->ed56_i_codigo;
      $this->iCodigoAlunoPossib   = $oAlunoCursoPossib->ed79_i_codigo;
      $this->oAluno               = new Aluno($oAlunoCursoPossib->ed56_i_aluno);
      $this->oBase                = new BaseCurricular($oAlunoCursoPossib->ed56_i_base);
      $this->oBaseAnterior        = new BaseCurricular($oAlunoCursoPossib->ed56_i_baseant);
      $this->oCalendario          = new Calendario($oAlunoCursoPossib->ed56_i_calendario);
      $this->oCalendarioAnterior  = new Calendario($oAlunoCursoPossib->ed56_i_calendarioant);
      $this->oEscola              = new Escola($oAlunoCursoPossib->ed56_i_escola);
      $this->oEtapa               = new Etapa($oAlunoCursoPossib->ed79_i_serie);
      $this->oTurmaAnterior       = new Turma($oAlunoCursoPossib->ed79_i_turmaant);
      $this->oTurno               = new Turno($oAlunoCursoPossib->ed79_i_turno);
      $this->sResultadoAnterior   = $oAlunoCursoPossib->ed79_c_resulant;
      $this->sSituacaoAlunoCurso  = $oAlunoCursoPossib->ed56_c_situacao;
      $this->sSituacaoAlunoPossib = $oAlunoCursoPossib->ed79_c_situacao;
      $this->sSituacaoAnterior    = $oAlunoCursoPossib->ed56_c_situacaoant;
  }

  /**
   * Retorna o c�digo de alunocurso
   * @return number
   */
  public function getCodigoAlunoCurso() {
    return $this->iCodigoAlunoCurso;
  }

  /**
   * Define uma inst�ncia de Escola
   * @param Escola $oEscola
   */
  public function setEscola(Escola $oEscola) {
    $this->oEscola = $oEscola;
  }

  /**
   * Retorna uma inst�ncia de Escola
   * @return Escola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Retorna uma inst�ncia de Aluno
   * @return Aluno
   */
  public function getAluno() {
    return $this->oAluno;
  }

  /**
   * Define uma inst�ncia de BaseCurricular
   * @param BaseCurricular $oBase
   */
  public function setBase(BaseCurricular $oBase) {
    $this->oBase = $oBase;
  }

  /**
   * Retorna uma inst�ncia de BaseCurricular
   * @return BaseCurricular
   */
  public function getBase() {
    return $this->oBase;
  }

  /**
   * Define uma inst�ncia de Calendario
   * @param Calendario $oCalendario
   */
  public function setCalendario(Calendario $oCalendario) {
    $this->oCalendario = $oCalendario;
  }

  /**
   * Retorna uma inst�ncia de Calendario
   * @return Calendario
   */
  public function getCalendario() {
    return $this->oCalendario;
  }

  /**
   * Define a situacao para alunocurso
   * @param string $sSituacaoAlunoCurso
   */
  public function setSituacaoAlunoCurso($sSituacaoAlunoCurso) {
    $this->sSituacaoAlunoCurso = $sSituacaoAlunoCurso;
  }

  /**
   * Retorna a descri��o da situacao de alunocurso
   * @return string
   */
  public function getSitucaoAlunoCurso() {
    return $this->sSituacaoAlunoCurso;
  }

  /**
   * Define uma inst�ncia de BaseCurricular anterior
   * @param BaseCurricular $oBaseAnterior
   */
  public function setBaseAnterior(BaseCurricular $oBaseAnterior) {
    $this->oBaseAnterior = $oBaseAnterior;
  }

  /**
   * Retorna uma inst�ncia de BaseCurricular anterior
   * @return BaseCurricular
   */
  public function getBaseAnterior() {
    return $this->oBaseAnterior;
  }

  /**
   * Define uma inst�ncia de Calendario anterior
   * @param Calendario $oCalendarioAnterior
   */
  public function setCalendarioAnterior(Calendario $oCalendarioAnterior) {
    $this->oCalendarioAnterior = $oCalendarioAnterior;
  }

  /**
   * Retorna uma inst�ncia de Calendario anterior
   * @return Calendario
   */
  public function getCalendarioAnterior() {
    return $this->oCalendarioAnterior;
  }

  /**
   * Define uma descri��o para situa��o anterior
   * @param string $sSituacaoAnterior
   */
  public function setSituacaoAnterior($sSituacaoAnterior) {
    $this->sSituacaoAnterior = $sSituacaoAnterior;
  }

  /**
   * Retorna a descri��o da situa��o anterior
   * @return string
   */
  public function getSituacaoAnterior() {
    return $this->sSituacaoAnterior;
  }

  /**
   * Retorna o c�digo de alunopossib
   * @return number
   */
  public function getCodigoAlunoPossib() {
    return $this->iCodigoAlunoPossib;
  }

  /**
   * Define uma inst�ncia de Etapa
   * @param Etapa $oEtapa
   */
  public function setEtapa(Etapa $oEtapa) {
    $this->oEtapa = $oEtapa;
  }

  /**
   * Retorna uma inst�ncia de Etapa
   * @return Etapa
   */
  public function getEtapa() {
    return $this->oEtapa;
  }

  /**
   * Define uma inst�ncia de Turno
   * @param Turno $oTurno
   */
  public function setTurno(Turno $oTurno) {
    $this->oTurno = $oTurno;
  }

  /**
   * Retorna uma inst�ncia de Turno
   * @return Turno
   */
  public function getTurno() {
    return $this->oTurno;
  }

  /**
   * Define uma inst�ncia de Turma anterior
   * @param Turma $oTurmaAnterior
   */
  public function setTurmaAnterior(Turma $oTurmaAnterior) {
    $this->oTurmaAnterior = $oTurmaAnterior;
  }

  /**
   * Retorna uma inst�ncia de Turma anterior
   * @return Turma
   */
  public function getTurmaAnterior() {
    return $this->oTurmaAnterior;
  }

  /**
   * Define uma descri��o para resultado anterior em alunopossib
   * @param string $sResultadoAnterior
   */
  public function setResultadoAnterior($sResultadoAnterior) {
    $this->sResultadoAnterior = $sResultadoAnterior;
  }

  /**
   * Retorna a descri��o de resultado anterior de alunopossib
   * @return string
   */
  public function getResultadoAnterior() {
    return $this->sResultadoAnterior;
  }

  /**
   * Define uma descri��o de situa��o em aluno possib
   * @param string $sSituacaoAlunoPossib
   */
  public function setSituacaoAlunoPossib($sSituacaoAlunoPossib) {
    $this->sSituacaoAlunoPossib = $sSituacaoAlunoPossib;
  }

  /**
   * Retorna a descri��o de situa��o de alunopossib
   * @return string
   */
  public function getSituacaoAlunoPossib() {
    return $this->sSituacaoAlunoPossib;
  }

  /**
   * Salva os dados em alunocurso e alunopossib
   * @throws BusinessException
   * @throws DBException
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new BusinessException(_M(URL_MENSAGEM_SITUACAOALUNO."sem_transacao_ativa"));
    }
    $oMsgErro = new stdClass();

    $oDaoAlunoCurso                       = new cl_alunocurso();
    $oDaoAlunoCurso->ed56_i_codigo        = $this->iCodigoAlunoCurso;
    $oDaoAlunoCurso->ed56_i_escola        = $this->getEscola()->getCodigo();
    $oDaoAlunoCurso->ed56_i_aluno         = $this->getAluno()->getCodigoAluno();
    $oDaoAlunoCurso->ed56_i_base          = $this->getBase()->getCodigoSequencial();
    $oDaoAlunoCurso->ed56_i_calendario    = $this->getCalendario()->getCodigo();
    $oDaoAlunoCurso->ed56_c_situacao      = $this->getSitucaoAlunoCurso();
    $oDaoAlunoCurso->ed56_i_baseant       = $this->getBaseAnterior()->getCodigoSequencial();
    $oDaoAlunoCurso->ed56_i_calendarioant = $this->getCalendarioAnterior()->getCodigo();
    $oDaoAlunoCurso->ed56_c_situacaoant   = $this->getSituacaoAnterior();
    
    if (empty($this->iCodigoAlunoCurso)) {

      $oDaoAlunoCurso->incluir(null);
      $this->iCodigoAlunoCurso = $oDaoAlunoCurso->ed56_i_codigo; 
    } else {
      $oDaoAlunoCurso->alterar($this->iCodigoAlunoCurso);
    }

    if ($oDaoAlunoCurso->erro_status == 0) {

      $oMsgErro->erro_banco = str_replace('\\n', "\n", $oDaoAlunoCurso->erro_sql);
      throw new DBException(_M(URL_MENSAGEM_SITUACAOALUNO."erro_incluir_alunocurso", $oMsgErro));
    }

    $oDaoAlunoPossib                    = new cl_alunopossib();
    $oDaoAlunoPossib->ed79_i_codigo     = $this->iCodigoAlunoPossib;
    $oDaoAlunoPossib->ed79_i_alunocurso = $this->iCodigoAlunoCurso;
    $oDaoAlunoPossib->ed79_i_serie      = $this->getEtapa()->getCodigo();
    $oDaoAlunoPossib->ed79_i_turno      = $this->getTurno()->getCodigoTurno();
    $oDaoAlunoPossib->ed79_i_turmaant   = $this->getTurmaAnterior()->getCodigo();
    $oDaoAlunoPossib->ed79_c_resulant   = $this->getResultadoAnterior();
    $oDaoAlunoPossib->ed79_c_situacao   = $this->getSituacaoAlunoPossib();
    
    if ( empty($this->iCodigoAlunoPossib)) {
      
      $oDaoAlunoPossib->incluir(null);
      $this->iCodigoAlunoPossib = $oDaoAlunoPossib->ed79_i_codigo;
    } else {
      $oDaoAlunoPossib->alterar($this->iCodigoAlunoPossib);
    }

    if ($oDaoAlunoPossib->erro_status == 0) {

      $oMsgErro->erro_banco = str_replace('\\n', "\n", $oDaoAlunoPossib->erro_sql);
      throw new DBException(_M(URL_MENSAGEM_SITUACAOALUNO."erro_incluir_alunopossib", $oMsgErro));
    }

    return true;
  }
}