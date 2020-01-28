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
 * Classe para controle dos critérios de avaliação
 *
 * @author Andre Mello andre.mello@dbseller.com.br
 * @package educacao
 * @version $Revision: 1.8 $
 */
class CriterioAvaliacao {

  /**
   * Código do Critério de Avaliação
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição do Critério de Avaliação
   * @var String
   */
  private $sDescricao;

  /**
   * Abreviatura do Critério de Avalição
   * @var String
   */
  private $sAbreviatura;

  /**
   * Ordenação do Critérios de Avaliação
   * @var integer
   */
  private $iOrdem;

  /**
   * Array das turmas na qual o Critério de Avaliação está vinculado
   * @var array
   */
  private $aTurmas = array();

  /**
   * Array das disciplinas na qual o Critério de Avaliação está vinculado
   * @var array
   */
  private $aDisciplinas = array();

  /**
   * Array de Períodos de Avaliação no qual o Critério está vinculado
   * @var array
   */
  private $aPeriodosAvaliacao = array();

  /**
   * Variavél que armazena a última ordem inserida no banco
   * @var integer
   */
  private $iUltimaOrdem;

  /**
   * Instancia de Escola
   * @var Escola
   */
  private $oEscola;

  /**
   * Instancia um Critério de Avaliação
   * Caso seja informado o código do Critério de Avaliação, traz os dados do critério
   * @param integer $iCodigo Código do Critério de Avaliação
   * @throws ParameterException Critério informado nao é encontrado
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
   * Retona o código do Critério de Avaliação
   * @return integer Código do critério
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descrição do Critério de Avaliação
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define a descrição do Critério de Avaliação
   * @param string $sDescricao Descrição do critério
   */
  public function setDescricao( $sDescricao ) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a abreviatura do Critério de Avaliação
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Define a abreviatura do Critério de Avaliação
   * @param string $sAbreviatura Abreviatura do critério
   */
  public function setAbreviatura( $sAbreviatura ) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Retorna uma coleção de turmas que possuem vínculo com o Critério de Avaliação
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
   * Define as turmas que possuem vínculo com o Critério de Avaliação
   * @param Turma $oTurma 
   */
  public function addTurma( Turma $oTurma ) {
    $this->aTurmas[] = $oTurma;
  }

  /**
   * Retorna as disciplinas que possuem vínculo com o Critério de Avaliação
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
   * Define as disciplinas que possuem vínculo com o Critério de Avaliação
   * @param Disciplina $oDisciplinas
   */
  public function addDisciplinas( Disciplina $oDisciplinas ) {
    $this->aDisciplinas[] = $oDisciplinas;
  }

  /**
   * Retorna os períodos de avaliação que possuem vínculo com o Critério de Avaliação
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
   * Define os períodos de avaliação que possuen vínculo com o Critério de Avaliação
   * @param PeriodoAvaliacao $oPeriodosAvaliacao
   */
  public function addPeriodos( PeriodoAvaliacao $oPeriodosAvaliacao ) {
    $this->aPeriodosAvaliacao[] = $oPeriodosAvaliacao;
  }

  /**
   * Retorna a ordenação na qual o Critério pertence
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Define a ordem na qual o critério pertence
   * @param integer $iOrdem
   */
  public function setOrdem( $iOrdem ) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Método responsável por buscar a ultima ordenação inserida no critério
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
   * Remove todos os vínculos existentes com a turma
   * @throws BusinessException Erro ao excluir vínculos com a turma.
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
   * Método responsável por remover os vínculos existentes e incluir os novos
   * @throws BusinessException Erro ao incluir vínculos com a turma.
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
   * Método responsável por vincular os Períodos de Avaliação com o critério
   * @throws BusinessException "Erro ao incluir vínculos com o Período de Avaliação."
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
   * Método responsável por vincular as Disciplinas com o critério
   * @throws BusinessException "Erro ao incluir vínculos com a Disciplina."
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
   * Remove os Períodos de Avaliação que estão vinculados ao critério
   * @throws BusinessException "Erro ao excluir vínculos com o Período de Avaliação"
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
   * Remove as Disciplina que estão vinculadas ao critério
   * @throws BusinessException "Erro ao excluir vínculos com a Disciplina."
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
   * Salva um novo critério no banco ou caso haja código definido, remove os vínculos com Periodo de Avaliação
   * e Disciplina, altera e refaz os vínculos.
   * @throws BusinessException "Erro ao alterar o Critério de Avaliação."
   * @throws BusinessException "Erro ao incluir o Critério de Avaliação."
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
   * Remove todos os vínculos existentes com o Critério de Avaliação e o exclui
   * @throws BusinessException "Erro ao excluir Critério de Avaliação."
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
   * Retorna a instância da escola que está vinculada ao critério
   * @return Escola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Seta uma instância de Escola
   * @param Escola $oEscola
   */
  public function setEscola( Escola $oEscola ) {
    $this->oEscola = $oEscola;
  }
}