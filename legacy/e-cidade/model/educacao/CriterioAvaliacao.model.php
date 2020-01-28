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
 * Classe para controle dos crit�rios de avalia��o
 *
 * @author Andre Mello andre.mello@dbseller.com.br
 * @package educacao
 * @version $Revision: 1.8 $
 */
class CriterioAvaliacao {

  /**
   * C�digo do Crit�rio de Avalia��o
   * @var integer
   */
  private $iCodigo;

  /**
   * Descri��o do Crit�rio de Avalia��o
   * @var String
   */
  private $sDescricao;

  /**
   * Abreviatura do Crit�rio de Avali��o
   * @var String
   */
  private $sAbreviatura;

  /**
   * Ordena��o do Crit�rios de Avalia��o
   * @var integer
   */
  private $iOrdem;

  /**
   * Array das turmas na qual o Crit�rio de Avalia��o est� vinculado
   * @var array
   */
  private $aTurmas = array();

  /**
   * Array das disciplinas na qual o Crit�rio de Avalia��o est� vinculado
   * @var array
   */
  private $aDisciplinas = array();

  /**
   * Array de Per�odos de Avalia��o no qual o Crit�rio est� vinculado
   * @var array
   */
  private $aPeriodosAvaliacao = array();

  /**
   * Variav�l que armazena a �ltima ordem inserida no banco
   * @var integer
   */
  private $iUltimaOrdem;

  /**
   * Instancia de Escola
   * @var Escola
   */
  private $oEscola;

  /**
   * Instancia um Crit�rio de Avalia��o
   * Caso seja informado o c�digo do Crit�rio de Avalia��o, traz os dados do crit�rio
   * @param integer $iCodigo C�digo do Crit�rio de Avalia��o
   * @throws ParameterException Crit�rio informado nao � encontrado
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoCriterioAvaliacao = new cl_criterioavaliacao();
      $sSqlCriterioAvaliacao = $oDaoCriterioAvaliacao->sql_query_file( $iCodigo );
      $rsCriterioAvaliacao   = $oDaoCriterioAvaliacao->sql_record( $sSqlCriterioAvaliacao );

      if ($oDaoCriterioAvaliacao->numrows == 0) {
        throw new ParameterException(_M('educacao.escola.CriterioAvaliacao.codigo_nao_informado'));
      }

      $oDadosCriterioAvaliacao  = db_utils::fieldsMemory($rsCriterioAvaliacao, 0);
      $this->iCodigo            = $iCodigo;
      $this->sDescricao         = $oDadosCriterioAvaliacao->ed338_descricao  ;
      $this->sAbreviatura       = $oDadosCriterioAvaliacao->ed338_abreviatura;
      $this->iOrdem             = $oDadosCriterioAvaliacao->ed338_ordem;
      $this->oEscola            = EscolaRepository::getEscolaByCodigo( $oDadosCriterioAvaliacao->ed338_escola );
    }
  }

  /**
   * Retona o c�digo do Crit�rio de Avalia��o
   * @return integer C�digo do crit�rio
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descri��o do Crit�rio de Avalia��o
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define a descri��o do Crit�rio de Avalia��o
   * @param string $sDescricao Descri��o do crit�rio
   */
  public function setDescricao( $sDescricao ) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a abreviatura do Crit�rio de Avalia��o
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Define a abreviatura do Crit�rio de Avalia��o
   * @param string $sAbreviatura Abreviatura do crit�rio
   */
  public function setAbreviatura( $sAbreviatura ) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Retorna uma cole��o de turmas que possuem v�nculo com o Crit�rio de Avalia��o
   * @return Turma[]
   */
  public function getTurmasVinculadas() {

    if ( $this->iCodigo != null && count($this->aTurmas) == 0 ) { 

      $oDaoCriterioAvaliacaoTurma    = new cl_criterioavaliacaoturma();
      $sWhere                        = " ed341_criterioavaliacao = {$this->iCodigo}";
      $sSqlCriterioAvaliacaoTurmas   = $oDaoCriterioAvaliacaoTurma->sql_query_file( "", "ed341_turma", "", $sWhere );
      $rsCriterioAvaliacaoTurmas     = $oDaoCriterioAvaliacaoTurma->sql_record ( $sSqlCriterioAvaliacaoTurmas );
      $iLinhaCriterioAvaliacaoTurmas = $oDaoCriterioAvaliacaoTurma->numrows;

      if ( $rsCriterioAvaliacaoTurmas && $iLinhaCriterioAvaliacaoTurmas > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhaCriterioAvaliacaoTurmas;  $iContador++ ) {

          $iTurma          = db_utils::fieldsMemory( $rsCriterioAvaliacaoTurmas, $iContador )->ed341_turma;
          $oTurma          = TurmaRepository::getTurmaByCodigo( $iTurma );
          $this->aTurmas[] = $oTurma;
        }
      }
    }

    return $this->aTurmas;
  }

  /**
   * Define as turmas que possuem v�nculo com o Crit�rio de Avalia��o
   * @param Turma $oTurma 
   */
  public function addTurma( Turma $oTurma ) {
    $this->aTurmas[] = $oTurma;
  }

  /**
   * Retorna as disciplinas que possuem v�nculo com o Crit�rio de Avalia��o
   * @return Disciplina[]
   */
  public function getDisciplinas() {

    if ( $this->iCodigo != null && count($this->aDisciplinas) == 0 ) { 

      $oDaoCriterioAvaliacaoDisciplina   = new cl_criterioavaliacaodisciplina();
      $sWhere                            = " ed339_criterioavaliacao = {$this->iCodigo}";
      $sSqlCriterioAvaliacaoDisciplina   = $oDaoCriterioAvaliacaoDisciplina->sql_query_file( "", "ed339_disciplina", "", $sWhere);
      $rsCriterioAvaliacaoDisciplina     = $oDaoCriterioAvaliacaoDisciplina->sql_record( $sSqlCriterioAvaliacaoDisciplina );
      $iLinhaCriterioAvaliacaoDisciplina = $oDaoCriterioAvaliacaoDisciplina->numrows;

      if ( $rsCriterioAvaliacaoDisciplina && $iLinhaCriterioAvaliacaoDisciplina > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhaCriterioAvaliacaoDisciplina; $iContador++ ) {

          $iDisciplina          = db_utils::fieldsMemory( $rsCriterioAvaliacaoDisciplina, $iContador)->ed339_disciplina;
          $oDisciplina          = DisciplinaRepository::getDisciplinaByCodigo( $iDisciplina );
          $this->aDisciplinas[] = $oDisciplina;
        }
      }
    }
    return $this->aDisciplinas;
  }

  /**
   * Define as disciplinas que possuem v�nculo com o Crit�rio de Avalia��o
   * @param Disciplina $oDisciplinas
   */
  public function addDisciplinas( Disciplina $oDisciplinas ) {
    $this->aDisciplinas[] = $oDisciplinas;
  }

  /**
   * Retorna os per�odos de avalia��o que possuem v�nculo com o Crit�rio de Avalia��o
   * @return array
   */
  public function getPeriodos() {

    if ( $this->iCodigo != null && count($this->aPeriodosAvaliacao) == 0 ) { 

      $oDaoCriterioAvaliacaoPeriodo   = new cl_criterioavaliacaoperiodoavaliacao();
      $sWhere                         = " ed340_criterioavaliacao = {$this->iCodigo}";
      $sSqlCriterioAvaliacaoPeriodo   = $oDaoCriterioAvaliacaoPeriodo->sql_query_file( "", "ed340_periodoavaliacao", "", $sWhere);
      $rsCriterioAvaliacaoPeriodo     = $oDaoCriterioAvaliacaoPeriodo->sql_record( $sSqlCriterioAvaliacaoPeriodo );
      $iLinhaCriterioAvaliacaoPeriodo = $oDaoCriterioAvaliacaoPeriodo->numrows;

      if ( $rsCriterioAvaliacaoPeriodo && $iLinhaCriterioAvaliacaoPeriodo > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhaCriterioAvaliacaoPeriodo; $iContador++ ) {

          $iPeriodoAvaliacao          = db_utils::fieldsMemory( $rsCriterioAvaliacaoPeriodo, $iContador )->ed340_periodoavaliacao;
          $oPeriodoAvaliacao          = new PeriodoAvaliacao( $iPeriodoAvaliacao );
          $this->aPeriodosAvaliacao[] = $oPeriodoAvaliacao;
          
        }
      }
    }
    return $this->aPeriodosAvaliacao;
  }

  /**
   * Define os per�odos de avalia��o que possuen v�nculo com o Crit�rio de Avalia��o
   * @param PeriodoAvaliacao $oPeriodosAvaliacao
   */
  public function addPeriodos( PeriodoAvaliacao $oPeriodosAvaliacao ) {
    $this->aPeriodosAvaliacao[] = $oPeriodosAvaliacao;
  }

  /**
   * Retorna a ordena��o na qual o Crit�rio pertence
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Define a ordem na qual o crit�rio pertence
   * @param integer $iOrdem
   */
  public function setOrdem( $iOrdem ) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * M�todo respons�vel por buscar a ultima ordena��o inserida no crit�rio
   * @return integer
   */
  private function getUltimaOrdem ( ) {
    
    $oDaoCriterioAvaliacao = new cl_criterioavaliacao();
    $sWhereUltimaOrdem     = "ed338_escola = {$this->getEscola()->getCodigo()}";
    $sSqlUltimaOrdem       = $oDaoCriterioAvaliacao->sql_query(null, ' max(ed338_ordem) ', null, $sWhereUltimaOrdem);
    $rsUltimaOrdem         = $oDaoCriterioAvaliacao->sql_record($sSqlUltimaOrdem);

    if ( $rsUltimaOrdem && $oDaoCriterioAvaliacao->numrows > 0 ) {
      $this->iUltimaOrdem = db_utils::fieldsMemory( $rsUltimaOrdem, 0 )->max;
    }

    return $this->iUltimaOrdem;
  }

  /**
   * Remove todos os v�nculos existentes com a turma
   * @throws BusinessException Erro ao excluir v�nculos com a turma.
   */
  public function removerVinculosTurmas() {

    if ( $this->iCodigo != null ) {

      $oDaoCriterioAvaliacaoTurma = new cl_criterioavaliacaoturma();
      $oDaoCriterioAvaliacaoTurma->excluir( null, " ed341_criterioavaliacao = {$this->iCodigo}" );

      if ( $oDaoCriterioAvaliacaoTurma->erro_status == 0 ) {

        $oParms        = new stdClass();
        $oParms->sErro = $oDaoCriterioAvaliacaoTurma->erro_msg;
        $sMensagemErro = _M('educacao.escola.CriterioAvaliacao.erro_excluir_turma', $oParms->sErro);
        throw new BusinessException( $sMensagemErro );
      }
    }
  }

  /**
   * M�todo respons�vel por remover os v�nculos existentes e incluir os novos
   * @throws BusinessException Erro ao incluir v�nculos com a turma.
   */
  public function vincularTurmas() {

    if ( $this->iCodigo != null ) {

      $aTurmasVinculadas = $this->getTurmasVinculadas();

      $this->removerVinculosTurmas();

      foreach ( $aTurmasVinculadas as $oTurma ) {

        $oDaoCriterioAvaliacaoTurma = new cl_criterioavaliacaoturma(); 
        $oDaoCriterioAvaliacaoTurma->ed341_criterioavaliacao = $this->iCodigo;
        $oDaoCriterioAvaliacaoTurma->ed341_turma             = $oTurma->getCodigo();
        $oDaoCriterioAvaliacaoTurma->incluir(null);
        
        if ( $oDaoCriterioAvaliacaoTurma->erro_status == 0 ) {

          $oParms        = new stdClass();
          $oParms->sErro = $oDaoCriterioAvaliacaoTurma->erro_msg;
          throw new BusinessException( _M('educacao.escola.CriterioAvaliacao.erro_incluir_turma', $oParms->sErro));
        }
      }
    }
  }

  /**
   * M�todo respons�vel por vincular os Per�odos de Avalia��o com o crit�rio
   * @throws BusinessException "Erro ao incluir v�nculos com o Per�odo de Avalia��o."
   */
  private function vincularPeriodosAvaliacao() {

    foreach ($this->aPeriodosAvaliacao as $oPeriodoAvaliacao) {
      
      $oDaoCriterioAvaliacaoPeriodo = new cl_criterioavaliacaoperiodoavaliacao();
      $oDaoCriterioAvaliacaoPeriodo->ed340_criterioavaliacao = $this->iCodigo;
      $oDaoCriterioAvaliacaoPeriodo->ed340_periodoavaliacao  = $oPeriodoAvaliacao->getCodigo();
      $oDaoCriterioAvaliacaoPeriodo->incluir(null);

      if ( $oDaoCriterioAvaliacaoPeriodo->erro_status == 0 ) {

        $oParms        = new stdClass();
        $oParms->sErro = $oDaoCriterioAvaliacaoPeriodo->erro_msg;
        throw new BusinessException(_M('educacao.escola.CriterioAvaliacao.erro_incluir_periodo', $oParms->sErro));
      }
    }
  }

  /**
   * M�todo respons�vel por vincular as Disciplinas com o crit�rio
   * @throws BusinessException "Erro ao incluir v�nculos com a Disciplina."
   */
  private function vincularDisciplinas() {

    foreach ($this->aDisciplinas as $oDisciplina) {
      
      $oDaoCriterioAvaliacaoDisciplina = new cl_criterioavaliacaodisciplina();
      $oDaoCriterioAvaliacaoDisciplina->ed339_criterioavaliacao = $this->iCodigo;
      $oDaoCriterioAvaliacaoDisciplina->ed339_disciplina        = $oDisciplina->getCodigoDisciplina();
      $oDaoCriterioAvaliacaoDisciplina->incluir(null);

      if ( $oDaoCriterioAvaliacaoDisciplina->erro_status == 0 ) {

        $oParms        = new stdClass();
        $oParms->sErro = $oDaoCriterioAvaliacaoDisciplina->erro_msg;
        throw new BusinessException( _M('educacao.escola.CriterioAvaliacao.erro_incluir_disciplina', $oParms->sErro) );
      }
    }
  }

  /**
   * Remove os Per�odos de Avalia��o que est�o vinculados ao crit�rio
   * @throws BusinessException "Erro ao excluir v�nculos com o Per�odo de Avalia��o"
   */
  private function removerVinculosPeriodo() {

    $oDaoCriterioAvaliacaoPeriodo = new cl_criterioavaliacaoperiodoavaliacao();
    $oDaoCriterioAvaliacaoPeriodo->excluir( null, " ed340_criterioavaliacao = {$this->iCodigo}" );

    if ( $oDaoCriterioAvaliacaoPeriodo->erro_status == 0 ) {

      $oParms        = new stdClass();
      $oParms->sErro = $oDaoCriterioAvaliacaoPeriodo->erro_msg;
      $sMensagemErro = _M('educacao.escola.CriterioAvaliacao.erro_excluir_periodo', $oParms->sErro);
      throw new BusinessException( $sMensagemErro );
    }
  }

  /**
   * Remove as Disciplina que est�o vinculadas ao crit�rio
   * @throws BusinessException "Erro ao excluir v�nculos com a Disciplina."
   */
  private function removerVinculosDisciplina() {

    $oDaoCriterioAvaliacaoDisciplina = new cl_criterioavaliacaodisciplina();
    $oDaoCriterioAvaliacaoDisciplina->excluir( null, " ed339_criterioavaliacao = {$this->iCodigo}" );

    if ( $oDaoCriterioAvaliacaoDisciplina->erro_status == 0 ) {

      $oParms        = new stdClass();
      $oParms->sErro = $oDaoCriterioAvaliacaoDisciplina->erro_msg;
      $sMensagemErro = _M('educacao.escola.CriterioAvaliacao.erro_excluir_disciplina', $oParms->sErro);
      throw new BusinessException( $sMensagemErro );
    }
  }

  /**
   * Salva um novo crit�rio no banco ou caso haja c�digo definido, remove os v�nculos com Periodo de Avalia��o
   * e Disciplina, altera e refaz os v�nculos.
   * @throws BusinessException "Erro ao alterar o Crit�rio de Avalia��o."
   * @throws BusinessException "Erro ao incluir o Crit�rio de Avalia��o."
   */
  public function salvar() {

    $oDaoCriterioAvaliacao = new cl_criterioavaliacao();
    $oDaoCriterioAvaliacao->ed338_descricao   = $this->getDescricao();
    $oDaoCriterioAvaliacao->ed338_abreviatura = $this->getAbreviatura();
    $oDaoCriterioAvaliacao->ed338_ordem       = $this->getOrdem();
    $oDaoCriterioAvaliacao->ed338_escola      = $this->getEscola()->getCodigo();

    if ( !empty( $this->iCodigo ) ) {

      $this->removerVinculosPeriodo();
      $this->removerVinculosDisciplina();

      $oDaoCriterioAvaliacao->ed338_sequencial = $this->iCodigo;
      $oDaoCriterioAvaliacao->alterar($oDaoCriterioAvaliacao->ed338_sequencial);

      if ( $oDaoCriterioAvaliacao->erro_status == 0 ) {

        $oParms        = new stdClass();
        $oParms->sErro = $oDaoCriterioAvaliacao->erro_msg;
        $sMensagemErro = _M('educacao.escola.CriterioAvaliacao.erro_alterar_criterio', $oParms->sErro);
        throw new BusinessException( $sMensagemErro );
      }
    } else {

      $oDaoCriterioAvaliacao->ed338_ordem = $this->getUltimaOrdem() + 1;
      $oDaoCriterioAvaliacao->incluir(null);

      if ( $oDaoCriterioAvaliacao->erro_status == 0 ) {

        $oParms        = new stdClass();
        $oParms->sErro = $oDaoCriterioAvaliacao->erro_msg;
        $sMensagemErro = _M('educacao.escola.CriterioAvaliacao.erro_incluir_criterio', $oParms->sErro);
        throw new BusinessException( $sMensagemErro );
      }

      $this->iCodigo = $oDaoCriterioAvaliacao->ed338_sequencial;
    }

    $this->vincularPeriodosAvaliacao();
    $this->vincularDisciplinas();
  }
 
  /**
   * Retorna as turmas que possuem vinculo com a disciplina informada
   * @param Disciplina $oDisciplina disciplina
   * @return Turma[]
   */
  public function getTurmasVinculadasDisciplina( Disciplina $oDisciplina ) {

    $aTurmasDisciplina = array();
    foreach ( $this->getTurmasVinculadas() as $oTurma ) {
      
      foreach ( $oTurma->getDisciplinas() as $oRegencia ) {
        
        if ( $oRegencia->getDisciplina()->getCodigoDisciplina() == $oDisciplina->getCodigoDisciplina() ) {
          $aTurmasDisciplina[$oTurma->getCodigo()] = $oTurma;
        } 
      }
    }
    return $aTurmasDisciplina;
  }

  /**
   * Remove todos os v�nculos existentes com o Crit�rio de Avalia��o e o exclui
   * @throws BusinessException "Erro ao excluir Crit�rio de Avalia��o."
   */
  public function remover() {

    if ( !empty( $this->iCodigo ) ) {

      $this->removerVinculosPeriodo();
      $this->removerVinculosDisciplina();
      $this->removerVinculosTurmas();
  
      $oDaoCriterioAvaliacao = new cl_criterioavaliacao();
      $oDaoCriterioAvaliacao->excluir( $this->iCodigo );

      if ( $oDaoCriterioAvaliacao->erro_status == 0 ) {

        $oParms        = new stdClass();
        $oParms->sErro = $oDaoCriterioAvaliacao->erro_msg;
        $sMensagemErro = _M('educacao.escola.CriterioAvaliacao.erro_excluir_criterio', $oParms->sErro);
        throw new BusinessException( $sMensagemErro );
      }
    }
  }

  /**
   * Retorna a inst�ncia da escola que est� vinculada ao crit�rio
   * @return Escola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Seta uma inst�ncia de Escola
   * @param Escola $oEscola
   */
  public function setEscola( Escola $oEscola ) {
    $this->oEscola = $oEscola;
  }
}