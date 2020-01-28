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


define("URL_MENSAGEM_CLASSIFICACAOALUNO", "educacao.escola.ClassificacaoAluno.");

/**
 * Esta classe contem os dados da classificação/reclassificação de um aluno
 *
 * @package educacao
 * @subpackage classificacao
 * @author Andrio Costa <andrio.costa@dbseller.com>
 * @version $Revision: 1.15 $
 *
 */
final class ClassificacaoAluno {

  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo;

  /**
   * Aluno que realizou a classificão
   * @var Aluno
   */
  private $oAluno;

  /**
   * Turma de origem
   * @var Turma
   */
  private $oTurmaOrigem;

  /**
   * Turma de destino
   * @var Turma
   */
  private $oTurmaDestino;

  /**
   * Observação
   * @var string
   */
  private $sObservacao;

  /**
   * Data da classificação/reclassificação
   * @var DBDate
   */
  private $oData;

  /**
   * Tipo da classificacao
   * @var string
   */
  private $sTipo;

  /**
   * Resultados da avaliação realizada
   * @var ResultadoClassificacao[]
   */
  private $aResultados;


  /**
   * Matricula de origem do aluno é a matricula atual do aluno
   * @var Matricula
   */
  private $oMatriculaOrigem;

  const CLASSIFICACAO   = 'C';
  const RECLASSIFICACAO = 'R';

  /**
   * Cria uma instancia de ClassificacaoAluno
   * @param unknown $iCodigo
   * @throws ParameterException
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoClassificacao = new cl_trocaserie();
      $sSqlClassificacao = $oDaoClassificacao->sql_query_file($iCodigo);
      $rsClassificacao   = $oDaoClassificacao->sql_record($sSqlClassificacao);

      if ($oDaoClassificacao->numrows == 0) {
        throw new ParameterException(_M("URL_MENSAGEM_CLASSIFICACAOALUNO"."codigo_nao_encontrado"));
      }
      $oDadosClassificacao = db_utils::fieldsMemory($rsClassificacao, 0);

      $oTurmaOrigem = '';

      if (!empty($oDadosClassificacao->ed101_i_turmaorig)) {
        $oTurmaOrigem = TurmaRepository::getTurmaByCodigo($oDadosClassificacao->ed101_i_turmaorig);
      }
      $this->iCodigo       = $iCodigo;
      $this->oAluno        = AlunoRepository::getAlunoByCodigo($oDadosClassificacao->ed101_i_aluno);
      $this->oTurmaOrigem  = $oTurmaOrigem;
      $this->oTurmaDestino = TurmaRepository::getTurmaByCodigo($oDadosClassificacao->ed101_i_turmadest);
      $this->sObservacao   = $oDadosClassificacao->ed101_t_obs;
      $this->oData         = new DBDate($oDadosClassificacao->ed101_d_data);
      $this->sTipo         = $oDadosClassificacao->ed101_c_tipo;

    }
  }


  /**
   * Realiza a classificação/reclassificação do aluno
   *
   * @param Etapa $oEtapaDestino
   * @throws DBException
   */
  public function salvar(Etapa $oEtapaDestino, array $aTurnoReferencia ) {

    if (!db_utils::inTransaction()) {
    	throw new DBException(_M(URL_MENSAGEM_CLASSIFICACAOALUNO."sem_transacao"));
    }

    $oMatricula             = $this->oAluno->getMatriculaByTurma($this->oTurmaOrigem);
    $this->oMatriculaOrigem = $oMatricula;

    $oMatricula->setDataEncerramento($this->oData);

    //Encerra matricula da turma de origem
    $oMatricula->encerrar("RECLASSIFICADO");

    //Atualiza situação da matrícula
    $this->atualizaMatricula($oEtapaDestino);

    $oNovaMatricula = new Matricula();

    $oMatricula->getEtapaDeOrigem()->getOrdem();
    // Verifica se necessita gerar histórico
    if ($oMatricula->getEtapaDeOrigem()->getOrdem() < $oEtapaDestino->getOrdem()) {

    	$this->gerarHistorico();
    	$oNovaMatricula->setResultadoFinalAnterior( "A" );
    }

    $oNovaMatricula->setAluno($this->oAluno);
    $oNovaMatricula->setTurma($this->oTurmaDestino);
    $oNovaMatricula->setSituacao("MATRICULADO");
    $oNovaMatricula->setDataMatricula($this->oData);
    $oNovaMatricula->setTurmaAnterior( $this->getTurmaOrigem() );
    $oNovaMatricula->matricular($oEtapaDestino,$aTurnoReferencia);

    $oDaoTrocaSerie = new cl_trocaserie();

    $oDaoTrocaSerie->ed101_i_codigo    = null;
    $oDaoTrocaSerie->ed101_i_aluno     = $this->oAluno->getCodigoAluno();
    $oDaoTrocaSerie->ed101_i_turmaorig = $this->oTurmaOrigem->getCodigo();
    $oDaoTrocaSerie->ed101_i_turmadest = $this->oTurmaDestino->getCodigo();
    $oDaoTrocaSerie->ed101_t_obs       = $this->sObservacao;
    $oDaoTrocaSerie->ed101_d_data      = $this->oData->getDate();
    $oDaoTrocaSerie->ed101_c_tipo      = $this->sTipo;

    $oDaoTrocaSerie->incluir(null);

    if ($oDaoTrocaSerie->erro_status == 0) {
      throw new DBException(_M(URL_MENSAGEM_CLASSIFICACAOALUNO . "erro_ao_incluir_classificacao"));
    }

    $this->iCodigo = $oDaoTrocaSerie->ed101_i_codigo;

    if ( count($this->aResultados) > 0 ) {

      foreach ($this->aResultados as $oResultado) {
        $oResultado->salvar($this);
      }
    }

  }

  /**
   * Monta a string de movimentação para matricula
   * @param Etapa $oEtapaDestino
   */
  private function atualizaMatricula(Etapa $oEtapaDestino) {

    $oMatricula    = $this->oMatriculaOrigem;
    $sTurmaOrigem  = $this->oTurmaOrigem->getDescricao() . " / " . $oMatricula->getEtapaDeOrigem()->getNome();
    $sTurmaDestino = $this->oTurmaDestino->getDescricao() . " / " . $oEtapaDestino->getNome();

    $sSituacao                 = "CLASSIFICADO";
    $sProcedimentoMatriculaMov = "PROGRESSÃO DE ALUNO -> CLASSIFICAÇÃO";
    if ($this->sTipo == ClassificacaoAluno::RECLASSIFICACAO) {

      $sProcedimentoMatriculaMov = "PROGRESSÃO DE ALUNO -> RECLASSIFICAÇÃO";
      $sSituacao = "RECLASSIFICADO";
    }
    $sStringMovimentacao = "ALUNO FOI {$sSituacao} DA TURMA {$sTurmaOrigem} PARA A TURMA {$sTurmaDestino}";

    $oMatricula->atualizarMovimentacao($sStringMovimentacao, $sProcedimentoMatriculaMov, $this->oData);
  }

  /**
   * Gera o histórico do aluno para turma de origem
   */
  private function gerarHistorico() {

    $oMatricula      = $this->oMatriculaOrigem;
    $oTurma          = $oMatricula->getTurma();
    $oCurso          = $oTurma->getBaseCurricular()->getCurso();

    $oHistoricoAluno = HistoricoAlunoRepository::getHistoricoAlunoByCurso($this->oAluno, $oCurso);

    if ( empty($oHistoricoAluno) ) {
      $oHistoricoAluno = new HistoricoAluno();
    }
    $oHistoricoAluno->setCurso($oCurso->getCodigo());
    $oHistoricoAluno->setEscola($oTurma->getEscola());

    $oHistoricoAluno->setAluno($this->oAluno);

    $oHistoricoEtapaRede = new HistoricoEtapaRede();
    $oHistoricoEtapaRede->setAnoCurso($oTurma->getCalendario()->getAnoExecucao());
    $oHistoricoEtapaRede->setResultadoFinal("A");
    $oHistoricoEtapaRede->setResultadoAno("A");
    $oHistoricoEtapaRede->setLancamentoAutomatico(true);
    $oHistoricoEtapaRede->setSituacaoEtapa("RECLASSIFICADO");
    $oHistoricoEtapaRede->setEscola($this->oTurmaOrigem->getEscola());
    $oHistoricoEtapaRede->setEtapa($oMatricula->getEtapaDeOrigem());
    $oHistoricoEtapaRede->setTurma($oTurma->getDescricao());
    $oHistoricoEtapaRede->setMininoParaAprovacao(null);
    $oHistoricoEtapaRede->setDiasLetivos(null);
    $oHistoricoEtapaRede->setJustificativa(null);
    $oHistoricoEtapaRede->setCargaHoraria(null);

    foreach ( $oMatricula->getTurma()->getDisciplinasPorEtapa( $oMatricula->getEtapaDeOrigem() ) as $oRegencia) {

      $oHistoricoDisciplina = new DisciplinaHistoricoRede();
      $oHistoricoDisciplina->setDisciplina($oRegencia->getDisciplina());
      $oHistoricoDisciplina->setResultadoObtido("RECLAS");
      $oHistoricoDisciplina->setSituacaoDisciplina("CONCLUÍDO");
      $oHistoricoDisciplina->setTipoResultado("N");
      $oHistoricoDisciplina->setResultadoFinal("A");
      $oHistoricoDisciplina->setOpcional(!$oRegencia->isObrigatoria());
      $oHistoricoDisciplina->setCargaHoraria(null);
      $oHistoricoDisciplina->setJustificativa(null);
      $oHistoricoDisciplina->setOrdem(null);
      $oHistoricoDisciplina->setTermoFinal(null);
      $oHistoricoDisciplina->setLancamentoAutomatico(true);

      $oHistoricoEtapaRede->adicionarDisciplina($oHistoricoDisciplina);
    }

    $oHistoricoAluno->adicionarEtapa($oHistoricoEtapaRede);
    $oHistoricoAluno->salvar();
  }

  /**
   * Retorna o código
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Define o aluno
   * @param Aluno $oAluno
   */
  public function setAluno (Aluno $oAluno) {
    $this->oAluno = $oAluno;
  }

  /**
   * Retorna o aluno
   * @return Aluno $oAluno
   */
  public function getAluno () {
    return $this->oAluno;
  }

  /**
   * Define a turma de destino
   * @param Turma $oTurma
   */
  public function setTurmaDestino (Turma $oTurma) {
    $this->oTurmaDestino = $oTurma;
  }

  /**
   * Retorna a turma de destino
   * @return Turma $oTurma
   */
  public function getTurmaDestino () {
    return $this->oTurmaDestino;
  }

  /**
   * Define turma de origem
   * @param Turma $oTurma
   */
  public function setTurmaOrigem (Turma $oTurma) {
    $this->oTurmaOrigem = $oTurma;
  }

  /**
   * Retorna turma de origem
   * @return Turma $oTurma
   */
  public function getTurmaOrigem () {
    return $this->oTurmaOrigem;
  }

  /**
   * Define uma observação
   * @param string $sObservacao
   */
  public function setObservacao ($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna uma observação
   * @return string $sObservacao
   */
  public function getObservacao () {
    return $this->sObservacao;
  }

  /**
   * Define uma data
   * @param DBDate $oData
   */
  public function setData (DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * Retorna uma data
   * @return DBDate $oData
   */
  public function getData () {
    return $this->oData;
  }

  /**
   * Define tipo de classificação
   * @param string $sTipo
   */
  public function setTipo ($sTipo) {

    switch ($sTipo) {

    	case ClassificacaoAluno::CLASSIFICACAO   :
  	  case ClassificacaoAluno::RECLASSIFICACAO :
    	  $this->sTipo = $sTipo;
    	  break;
    	default:
    	  throw new ParameterException(_M(URL_MENSAGEM_CLASSIFICACAOALUNO."tipo_nao_valido"));
    }

  }

  /**
   * Retorna tipo de classificação
   * @return string $sTipo
   */
  public function getTipo () {
    return $this->sTipo;
  }

  /**
   * Adiciona um resuldado de uma disciplina
   * @param ResultadoClassificacao $oResultadoClassificacao
   */
  public function adicionarResultadoAvaliacao(ResultadoClassificacao $oResultadoClassificacao) {

    $this->aResultados[] = $oResultadoClassificacao;
  }

  /**
   * Retorna os resultado da classificação
   *
   * @throws DBException
   * @return multitype:ResultadoClassificacao
   */
  public function getResultadoAvaliacao() {

    if ( count($this->aResultados) == 0 ) {

      $sWhere = " ed335_trocaserie = {$this->iCodigo} ";

      $oDaoAvaliacao = new cl_avaliacaoclassificacao();
      $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file(null, "ed335_sequencial", null, $sWhere);
      $rsAvaliacao   = db_query($sSqlAvaliacao);

      if (!$rsAvaliacao) {
      	throw new DBException(_M(URL_MENSAGEM_CLASSIFICACAOALUNO . "erro_ao_buscar_avaliacoes"));
      }

      $iLinhas = pg_num_rows( $rsAvaliacao );

      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $oResultadoClassificacao = new ResultadoClassificacao(db_utils::fieldsMemory($rsAvaliacao, $i)->ed335_sequencial);
          $this->adicionarResultadoAvaliacao($oResultadoClassificacao);
        }
      }
    }

    return $this->aResultados;
  }

  /**
   * Busca todos os alunos que foram classificados / reclassificados na escola e calendário informado
   *
   * @param Escola     $oEscola
   * @param Calendario $oCalendario
   * @access static
   * @throws DBException
   * @throws BusinessException
   * @return multitype:Ambigous <Aluno, multitype:>
   */
  public static function getAlunosClassificadoDaEscolaNoCalendario( Escola $oEscola, Calendario $oCalendario ) {

    $sWhere  = "     escolaorig.ed18_i_codigo     = {$oEscola->getCodigo()} ";
    $sWhere .= " and calendarioorig.ed52_i_codigo = {$oCalendario->getCodigo()} ";

    $sCampos = " ed101_i_aluno ";

    $oDaoBuscaAlunos = new cl_trocaserie();
    $sSqlAlunos      = $oDaoBuscaAlunos->sql_query(null, $sCampos, null, $sWhere);
    $rsAlunos        = db_query($sSqlAlunos);


    if ( !$rsAlunos ) {
    	throw new DBException(_M(URL_MENSAGEM_CLASSIFICACAOALUNO . "erro_ao_buscar_alunos_classificados"));
    }

    $iLinhas = pg_num_rows($rsAlunos);

    if ($iLinhas == 0) {
    	throw new BusinessException(_M(URL_MENSAGEM_CLASSIFICACAOALUNO . "nenhum_aluno_classificado"));
    }

    $aAlunosClassificados = array();
    for ($i = 0; $i < $iLinhas; $i++) {

      $oAluno = AlunoRepository::getAlunoByCodigo(db_utils::fieldsMemory($rsAlunos, $i)->ed101_i_aluno);
      $aAlunosClassificados [] = $oAluno;
    }
    return $aAlunosClassificados;
  }
}