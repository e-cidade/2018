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
define( 'MENSAGENS_DIARIOCLASSE_MODEL', 'educacao.escola.DiarioClasse.' );

/**
 * Controla o lançamento das notas do aluno
 * @author     Fábio Esteves - fabio.esteves@dbseller.com.br
 * @package    educacao
 * @subpackage avaliacao
 * @version $Revision: 1.102 $
 */
class DiarioClasse {

  /**
   * Dados da matrícula de um aluno
   * @var object
   */
  private $oMatricula;

  /**
   * Array de disciplinas
   * @var array
   */
  private $aDiarioDisciplina = array();

  /**
   * Dados da turma que a matrícula está vinculada
   * @var object
   */
  private $oTurma;

  /**
   * Array dos períodos da turma
   * @var AvaliacaoPeriodica[] | ResultadoAvaliacao[]
   */
  private $aPeriodosAvaliacao;

  /**
   * Controla se deve ser apresentado a nota proporcional ou a real
   * @var boolean|null
   */
  private $lApresentarNotaProporcional = null;

  /**
   * @param Matricula $oMatricula
   * @param bool      $lCriarDiario
   */
  public function __construct(Matricula $oMatricula, $lCriarDiario = true) {

    $this->oMatricula = $oMatricula;
    if ($lCriarDiario) {
      $this->criarDiarioClasseAluno();
    }
  }

  /**
   * Retorna uma instância de Matricula
   * @return Matricula
   */
  public function getMatricula() {
    return $this->oMatricula;
  }

  /**
   * Retorna uma instancia da turma na qual a matrícula está vinculada
   * @return Turma
   */
  public function getTurma() {

    $this->oTurma = $this->oMatricula->getTurma();
    return $this->oTurma;
  }

  /**
   * Retorna os períodos de avaliação da Turma
   *
   * @deprecated a partir da versão 2.3.32 os periodos de avaliação devem ser vistos de acordo com o
   *             procedimento de avaliação de cada regência
   */
  public function getPeriodoAvaliacao($iCodigoEtapa = '') {

    if (count($this->aPeriodosAvaliacao) == 0) {

      $aEtapas = $this->getTurma()->getEtapas();
      foreach ($aEtapas as $oEtapas) {

        if ($oEtapas->getEtapa()->getCodigo() == $iCodigoEtapa || $iCodigoEtapa == '') {

          $aProcedimentoAvaliacao = $oEtapas->getProcedimentoAvaliacao();
          foreach ($aProcedimentoAvaliacao->getElementos() as $aDadosProcedimentoAvaliacao) {
            $this->aPeriodosAvaliacao[] = $aDadosProcedimentoAvaliacao;
          }
        }
      }
    }
    return $this->aPeriodosAvaliacao;
  }

  /**
   * Cria os dados do diario de classe
   */
  protected function criarDiarioClasseAluno() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem Transação com o banco de dados ativa.");
    }

    $oDaoRegencia      = new cl_regencia();
    $oDaoDiarioClasse  = new cl_diario();
    $sSqlDiarioClasse  = "select 1  ";
    $sSqlDiarioClasse .= "  from diario ";
    $sSqlDiarioClasse .= "       inner join aluno            on ed47_i_codigo = ed95_i_aluno ";
    $sSqlDiarioClasse .= "       inner join matricula        on ed60_i_aluno  = ed47_i_codigo ";
    $sSqlDiarioClasse .= "       inner join matriculaserie   on ed60_i_codigo = ed221_i_matricula ";
    $sSqlDiarioClasse .= "       inner join regencia as reg  on reg.ed59_i_codigo = ed95_i_regencia ";
    $sSqlDiarioClasse .= "                                  and reg.ed59_i_serie  = ed221_i_serie ";
    $sSqlDiarioClasse .= " where ed60_i_codigo   = {$this->getMatricula()->getCodigo()} ";
    $sSqlDiarioClasse .= "   and ed95_i_regencia = regencia.ed59_i_codigo ";
    $sSqlDiarioClasse .= "   and  ed221_c_origem = 'S' ";

    $sWhereRegencia  = " ed57_i_codigo = {$this->getTurma()->getCodigo()} ";
    $sWhereRegencia .= " and not exists($sSqlDiarioClasse) ";

    if(    $this->getMatricula()->getEtapaDeOrigem() instanceof Etapa
        && $this->getMatricula()->getEtapaDeOrigem()->getCodigo() != null
      ) {
      $sWhereRegencia .= " and ed59_i_serie = {$this->getMatricula()->getEtapaDeOrigem()->getCodigo()}";
    }

    $sCamposRegencia = "distinct ed59_i_ordenacao, ed59_i_codigo, ed223_i_serie";
    $sSqlRegencias   = $oDaoRegencia->sql_query_avaliacao( null, $sCamposRegencia, "ed59_i_ordenacao", $sWhereRegencia );
    $rsRegencias     = db_query($sSqlRegencias);
    if( !is_resource( $rsRegencias ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_DIARIOCLASSE_MODEL . 'erro_buscar_regencias', $oErro ) );
    }

    /**
     * Incluimos o diario do aluno, para as disciplinas que nao possuem diario.
     */
    $iTotalRegenciasSemDiario = pg_num_rows( $rsRegencias );
    for ($i = 0; $i < $iTotalRegenciasSemDiario; $i++) {

      $oRegencia        = db_utils::fieldsMemory($rsRegencias, $i);
      $sDiarioEncerrado = 'N';
      if ($this->getMatricula()->getSituacao() != "MATRICULADO") {
        $sDiarioEncerrado = "S";
      }
      $oDaoDiarioClasse->ed95_i_aluno      = $this->getMatricula()->getAluno()->getCodigoAluno();
      $oDaoDiarioClasse->ed95_c_encerrado  = $sDiarioEncerrado;
      $oDaoDiarioClasse->ed95_i_calendario = $this->getTurma()->getCalendario()->getCodigo();
      $oDaoDiarioClasse->ed95_i_escola     = $this->getTurma()->getEscola()->getCodigo();
      $oDaoDiarioClasse->ed95_i_regencia   = $oRegencia->ed59_i_codigo;
      $oDaoDiarioClasse->ed95_i_serie      = $oRegencia->ed223_i_serie;
      $oDaoDiarioClasse->incluir(null);
      if ($oDaoDiarioClasse->erro_status == 0) {

        $sMsgErro = "Erro ao salvar dados do diario de classe do aluno {$this->getMatricula()->getAluno()->getNome()}";
        throw new BusinessException($sMsgErro);
      }

      $oRegencia = RegenciaRepository::getRegenciaByCodigo($oRegencia->ed59_i_codigo);

      /**
       * Incluimos um registro em branco para os dados das avaliacoes da etapa de origem da matricula.
       */
      foreach ($oRegencia->getProcedimentoAvaliacao()->getElementos() as $oPeriodoAvaliacao) {

        if ($oPeriodoAvaliacao instanceof AvaliacaoPeriodica) {
          $this->salvarNovoAvaliacao($oDaoDiarioClasse->ed95_i_codigo, $oPeriodoAvaliacao);
        } else {
          $this->salvarNovoResultado($oDaoDiarioClasse->ed95_i_codigo, $oPeriodoAvaliacao);
        }
      }
    }
  }

  /**
   * Retorna as disciplinas do diario de classe
   * @return DiarioAvaliacaoDisciplina[]
   */
  public function getDisciplinas() {

    if (count($this->aDiarioDisciplina) == 0) {

      $oDaoDiarioClasse = new cl_diario();

      $sWhereDiario  = " ed60_i_codigo       = {$this->getMatricula()->getCodigo()}";
      $sWhereDiario .= " and ed95_i_regencia = ed59_i_codigo";
      $sWhereDiario .= " and ed95_i_serie    = {$this->getMatricula()->getEtapaDeOrigem()->getCodigo()} ";
      $sWhereDiario .= " and ed59_i_turma    = {$this->getMatricula()->getTurma()->getCodigo()}";

      $sSqlDiarioClasse = $oDaoDiarioClasse->sql_query_diario_classe( null, 'diario.*, ed59_i_codigo',
                                                                      "ed95_i_codigo", $sWhereDiario );

      $rsDiarioClasse   = $oDaoDiarioClasse->sql_record($sSqlDiarioClasse);
      if ($oDaoDiarioClasse->numrows > 0) {

        for ($iDiario = 0; $iDiario < $oDaoDiarioClasse->numrows; $iDiario++) {

          $oDiario      = db_utils::fieldsMemory($rsDiarioClasse, $iDiario);
          $oDadosDiario = new DiarioAvaliacaoDisciplinaVO();
          $oDadosDiario->setCodigoDiario($oDiario->ed95_i_codigo);
          $oDadosDiario->setEncerrado($oDiario->ed95_c_encerrado == "S" ? true : false);
          $oDadosDiario->setRegencia(RegenciaRepository::getRegenciaByCodigo($oDiario->ed59_i_codigo));

          $oDiarioDisciplina = new DiarioAvaliacaoDisciplina($oDadosDiario);
          $oDiarioDisciplina->setDiario($this);
          $this->aDiarioDisciplina[] = $oDiarioDisciplina;
        }
      }
    }
    return $this->aDiarioDisciplina;
  }

  /**
   * Salvar dados do novo diario
   *
   * @param integer            $iCodigoDiario codigo do diario de avaliacao
   * @param AvaliacaoPeriodica $oPeriodo      Instancia do periodo
   * @throws BusinessException
   */
  protected function salvarNovoAvaliacao($iCodigoDiario, AvaliacaoPeriodica $oPeriodo) {

    $oDaoDiarioAvaliacao                       = new cl_diarioavaliacao;
    $oDaoDiarioAvaliacao->ed72_c_amparo        = 'N';
    $oDaoDiarioAvaliacao->ed72_c_aprovmin      = 'N';

    if ($oPeriodo->getFormaDeAvaliacao()->getTipo() == 'PARECER') {
      $oDaoDiarioAvaliacao->ed73_c_aprovmin = 'S';
    }

    $oDaoDiarioAvaliacao->ed72_c_convertido    = 'N';
    $oDaoDiarioAvaliacao->ed72_c_tipo          = 'M';
    $oDaoDiarioAvaliacao->ed72_i_procavaliacao = $oPeriodo->getCodigo();
    $oDaoDiarioAvaliacao->ed72_i_diario        = $iCodigoDiario;
    $oDaoDiarioAvaliacao->ed72_i_escola        = $this->getTurma()->getEscola()->getCodigo();
    $oDaoDiarioAvaliacao->incluir(null);

    if ($oDaoDiarioAvaliacao->erro_status == 0) {

      $sMsgErro  = "Erro ao salvar dados do diario de avaliacao do aluno";
      $sMsgErro .= "{$this->getMatricula()->getAluno()->getNome()}.\n$oDaoDiarioAvaliacao->erro_msg";
      throw new BusinessException($sMsgErro);
    }
  }

  /**
   * persite os dados do novo resultado
   *
   * @param integer            $iCodigoDiario codigo do diario de avalicao
   * @param ResultadoAvaliacao $oPeriodo      instancia do resultado final
   * @throws BusinessException
   */
  protected function salvarNovoResultado($iCodigoDiario, ResultadoAvaliacao $oPeriodo) {

    $oDaoDiarioResultado                       = new cl_diarioresultado();
    $oDaoDiarioResultado->ed73_i_diario        = $iCodigoDiario;
    $oDaoDiarioResultado->ed73_i_procresultado = $oPeriodo->getCodigo();
    $oDaoDiarioResultado->ed73_c_aprovmin      = 'N';

    if ($oPeriodo->getFormaDeAvaliacao()->getTipo() == 'PARECER' || $this->getMatricula()->isAvaliadoPorParecer()) {
      $oDaoDiarioResultado->ed73_c_aprovmin = 'S';
    }

    $oDaoDiarioResultado->incluir(null);
    if ($oDaoDiarioResultado->erro_status == 0) {

      $sMsgErro  = "Erro ao salvar resultados para o diario de avaliacao do aluno ";
      $sMsgErro .= "{$this->getMatricula()->getAluno()->getNome()}.\n{$oDaoDiarioResultado->erro_msg}";
      throw new BusinessException($sMsgErro);
    }

    if ($oPeriodo->geraResultadoFinal()) {
      $this->salvarDiarioFinal( $iCodigoDiario,  $oPeriodo);
    }
  }

  /**
   * persiste os dados do diario
   *
   * @throws DBException
   * @return void
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem Transação com o banco de dados ativa.");
    }
    foreach ($this->getDisciplinas() as $oDisciplina) {

      $oDisciplina->salvar();
    }
  }

  /**
   * Retorna os dados de uma Discipliuna pela regencia
   * @param Regencia $oRegencia
   * @return DiarioAvaliacaoDisciplina
   */
  public function getDisciplinasPorRegencia(Regencia $oRegencia) {

    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplinas) {

      if ($oRegencia->getCodigo() == $oDisciplinas->getRegencia()->getCodigo()) {
        return $oDisciplinas;
      }
    }
    return null;
  }

  /**
   * Retorna os dados de uma Discipliuna pela disciplina
   * @param Disciplina $oDisciplina
   * @return DiarioAvaliacaoDisciplina
   */
  public function getDisciplinasPorDisciplina(Disciplina $oDisciplina) {

    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oAvaliacaoDisciplina) {

      if ($oDisciplina->getCodigoDisciplina() == $oAvaliacaoDisciplina->getRegencia()->getDisciplina()->getCodigoDisciplina()) {
        return $oAvaliacaoDisciplina;
      }
    }
    return null;
  }

  /**
   * Retorna o Aproveitamento para o periodo
   * @param Regencia $oRegencia  instancia de Regencia
   * @param IElementoAvaliacao $oElemento instancia de um Elemento de avalaiacao ou resultado
   * @return AvaliacaoAproveitamento[]
   */
  public function getDisciplinasPorRegenciaPeriodo(Regencia $oRegencia, IElementoAvaliacao $oElemento) {

    $oDisciplina = $this->getDisciplinasPorRegencia($oRegencia);
    if (empty($oDisciplina)) {
      return false;
    }

    $oAproveitamentoNoPeriodo = $oDisciplina->getAvaliacoesPorOrdem($oElemento->getOrdemSequencia());

    if (empty($oAproveitamentoNoPeriodo)) {
      return false;
    }
    return $oAproveitamentoNoPeriodo;
  }

  /**
   * Retorna o Aproveitamento para o periodo
   *
   * @param Disciplina         $oDisciplina
   * @param IElementoAvaliacao $oElemento instancia de um Elemento de avalaiacao ou resultado
   * @internal param \Regencia $oRegencia instancia de Regencia
   * @return AvaliacaoAproveitamento
   */
  public function getAvaliacoesPorDisciplina(Disciplina $oDisciplina, IElementoAvaliacao $oElemento) {

    $oDiarioDisciplina = null;
    foreach ($this->getDisciplinas() as $oAvaliacao) {
      if ($oAvaliacao->getRegencia()->getDisciplina()->getCodigoDisciplina() == $oDisciplina->getCodigoDisciplina()) {

        $oDiarioDisciplina = $oAvaliacao;
        break;
      }
    }

    if (empty($oDiarioDisciplina)) {
      return false;
    }

    $oAproveitamentoNoPeriodo = $oDiarioDisciplina->getAvaliacoesPorOrdem($oElemento->getOrdemSequencia());
    if (empty($oAproveitamentoNoPeriodo)) {
      return false;
    }
    return $oAproveitamentoNoPeriodo;
  }

  /**
   * Retorna as disciplinas que o aluno ficou como reprovado
   * @return DiarioAvaliacaoDisciplina  com as disciplinas reprovadas
   */
  public function getDisciplinasComReprovacao() {

    $aDisciplinasComReprovacao = array();
    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplina) {

      $oResultadoFinal = $oDisciplina->getResultadoFinal();
      if ($oResultadoFinal->isAprovado() === false || $oResultadoFinal->aprovadoPorProgressaoParcial()) {
        $aDisciplinasComReprovacao[] = $oDisciplina;
      }
    }
    return $aDisciplinasComReprovacao;
  }

  /**
   * Retorna as disciplinas que o aluno ficou como reprovado
   * @return DiarioAvaliacaoDisciplina[]  com as disciplinas reprovadas
   */
  public function getDisciplinasComReprovacaoNoAproveitamento() {

    $aDisciplinasComReprovacaoNoAproveitamento = array();
    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplina) {

      if ( !$oDisciplina->getRegencia()->isObrigatoria() ) {
        continue;
      }

      $oResultadoFinal = $oDisciplina->getResultadoFinal();
      if (($oResultadoFinal->getResultadoAprovacao() == 'R' && $oResultadoFinal->getResultadoFinal() == 'R') ||
        $oResultadoFinal->aprovadoPorProgressaoParcial()) {
        $aDisciplinasComReprovacaoNoAproveitamento[] = $oDisciplina;
      }
    }
    return $aDisciplinasComReprovacaoNoAproveitamento;
  }

  /**
   * Retorna as disciplinas que o aluno ficou como reprovado na frequencia
   * @return DiarioAvaliacaoDisciplina[] Disciplinas Reprovadas na Frequência
   */
  public function getDisciplinasComReprovacaoNaFrequencia() {

    $aDisciplinasComReprovacaoNaFrequencia = array();
    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplina) {

      $oResultadoFinal = $oDisciplina->getResultadoFinal();
      if ($oResultadoFinal->getResultadoFrequencia() == 'R') {
        $aDisciplinasComReprovacaoNaFrequencia[] = $oDisciplina;
      }
    }
    return $aDisciplinasComReprovacaoNaFrequencia;
  }

  /**
   * Verifica se o aluno foi aprovado com progressão parcial
   * @return boolean
   */
  public function aprovadoComProgressaoParcial() {

    $iEscola = db_getsession("DB_coddepto");

    $oParametroProgressaoParcial = ProgressaoParcialParametroRepository::getProgressaoParcialParametroByCodigo($iEscola);

    if (!$oParametroProgressaoParcial->isHabilitada()) {
      return false;
    }

    /**
     * Validamos se o aluno possui alguma reprovacao por frequencia.
     * Caso tenha, o aluno perde o direito a Progressao parcial, conforme conversa com marisandra e tiago
     * no dia 12/12/2013, na correção do bug da tarefa 85374
     */
    if (count($this->getDisciplinasComReprovacaoNaFrequencia()) > 0) {
      return false;
    }


    $aEtapasComProgressao = array();
    foreach ($oParametroProgressaoParcial->getEtapas() as $oEtapa) {
      $aEtapasComProgressao[] = $oEtapa->getCodigo();
    }
    $iTotalDisciplinasReprovadas = count($this->getDisciplinasComReprovacaoNoAproveitamento());

    if ( $iTotalDisciplinasReprovadas == 0 ) {
      return false;
    }

    $iTotalDisciplinasProgressao = 0;
    $iTotalProgressoesEmAberto   = 0;
    $aProgressoes                = ProgressaoParcialAlunoRepository::getProgressoesAtivasNaMatricula($this->getMatricula());

    /**
     * Caso o controle for por base curricular, devemos olhar as progressoes que o aluno está
     * como reprovado, e acrescentar nas disciplinas como reprovado.
     */
    if ($oParametroProgressaoParcial->getFormaControle() == ProgressaoParcialParametro::CONTROLE_BASE_CURRICULAR) {
      $iTotalProgressoesEmAberto   = count($aProgressoes);
    }

    /**
     * Caso o parametro para elimininar a dependencia caso a disciolina esteja habilitado,
     * nao devemos contar as disciplinas aprovadaas com em aberto.
     */
    if ($oParametroProgressaoParcial->disciplinaAprovadaEliminaProgressao()) {

      foreach ($this->getDisciplinas() as $oDisciplina) {

        if ($oDisciplina->getResultadoFinal()->getResultadoFinal() == "A") {
          foreach ($aProgressoes as $oProgressao) {

            $oDisciplinaProgressao = $oProgressao->getDisciplina()->getCodigoDisciplina();
            if ($oDisciplinaProgressao == $oDisciplina->getDisciplina()->getCodigoDisciplina()) {
              $iTotalProgressoesEmAberto --;
            }
          }
        }
      }
    }

    if ($iTotalProgressoesEmAberto < 0) {
      $iTotalProgressoesEmAberto = 0;
    }
    $iTotalDisciplinasReprovadas += $iTotalProgressoesEmAberto;
    if ($oParametroProgressaoParcial->getQuantidadeDisciplina() != null) {
      $iTotalDisciplinasProgressao = $oParametroProgressaoParcial->getQuantidadeDisciplina();
    }

    /* ATENCAO: PLUGIN ParametroProgressaoParcial - Variável $lValidaDependenciaMesmaDisciplina alterada*/
    $lValidaDependenciaMesmaDisciplina = false;

    /**
     * caso umas das disciplina em que o aluno está reprovado no ensino regular, também for reprovado
     * na progressao, o aluno nao pode ser aprovado como progressao.
     */
    if ( !$lValidaDependenciaMesmaDisciplina ) {

      foreach ($this->getDisciplinasComReprovacaoNoAproveitamento() as $oDisciplinaReprovada) {

        foreach ($aProgressoes as $oProgressao) {

          $iDisciplinaProgressao = $oProgressao->getDisciplina()->getCodigoDisciplina();
          if ($iDisciplinaProgressao == $oDisciplinaReprovada->getDisciplina()->getCodigoDisciplina()) {

            return false;
            break;
          }
        }
      }
    }

    $iEtapaDeOrigem = $this->getMatricula()->getEtapaDeOrigem()->getCodigo();
    if ((in_array($iEtapaDeOrigem, $aEtapasComProgressao) && $iTotalDisciplinasReprovadas > 0)
        && $iTotalDisciplinasReprovadas <= $iTotalDisciplinasProgressao) {
       return true;
    }
    return false;
  }

  /**
   * Adiciona as Disciplinas que o aluno reprovou como progressao parcial
   * Apenas adiciona as disciplinas, caso o aluno reprovou dentro da quantidade das disciplinas
   * em que está configurado para a escola
   * @return void
   */
  public function adicionarDisciplinasReprovadasComoProgressaoParcial() {


    if ($this->aprovadoComProgressaoParcial()) {

      /**
       * A etapa do historico aprovada, fica como resultado final 'D'
       */
      $iCodigoCurso    = $this->getMatricula()->getTurma()->getBaseCurricular()->getCurso()->getCodigo();
      $oHistorico      = $this->getMatricula()->getAluno()->getHistoricoEscolar($iCodigoCurso);
      $aEtapas         = $oHistorico->getEtapas();
      $oEtapaAluno     = $this->getMatricula()->getEtapaDeOrigem();
      $oEtapaHistorico = null;
      foreach ($aEtapas as $oEtapa) {

        if ($oEtapa->getEtapa()->getCodigo() == $oEtapaAluno->getCodigo()
            && $oEtapa->getAnoCurso() == $this->getTurma()->getCalendario()->getAnoExecucao()) {

          $oEtapa->setResultadoAno("D");
          $oEtapa->salvar();
          $oEtapaHistorico = $oEtapa;
          break;
        }
      }

      $aDisciplinaHistorico = array();
      if ($oEtapaHistorico != null) {
        $aDisciplinaHistorico = $oEtapaHistorico->getDisciplinas();
      }

      foreach ($this->getDisciplinasComReprovacaoNoAproveitamento() as $oDisciplinaReprovada) {

        if ( !$oDisciplinaReprovada->getRegencia()->isObrigatoria() ) {
          continue;
        }

        /**
         * Removido lançamento de observação,
         * Observação só será lançada via rotina
         */
        $oResultadoFinal = $oDisciplinaReprovada->getResultadoFinal();
        $oResultadoFinal->setResultadoFinal('A');
        $oResultadoFinal->salvar();

        /**
         * Incluimos a disciplina como Dependencia
         */
        $oProgressaoParcial = new ProgressaoParcialAluno();
        $oProgressaoParcial->setAluno($this->getMatricula()->getAluno());
        $oProgressaoParcial->setCodigoDiarioFinal($oResultadoFinal->getCodigoResultadoFinal());
        $oProgressaoParcial->setDisciplina($oDisciplinaReprovada->getDisciplina());
        $oProgressaoParcial->setEtapa($this->getMatricula()->getEtapaDeOrigem());
        $oProgressaoParcial->setAno( $this->getTurma()->getCalendario()->getAnoExecucao() );
        $oProgressaoParcial->setEscola( $this->getTurma()->getEscola() );
        $oProgressaoParcial->setSituacaoProgressao(SituacaoEducacaoRepository::getSituacaoEducacaoByCodigo(ProgressaoParcialAluno::ATIVA));
        $oProgressaoParcial->salvar();

        /**
         * salvamos a disciplina nos historico como "D"
         */
        foreach ($aDisciplinaHistorico as $oDisciplinaHistorico) {

          $iDisciplinaDoHistorico = $oDisciplinaHistorico->getDisciplina()->getCodigoDisciplina();
          if ($iDisciplinaDoHistorico == $oDisciplinaReprovada->getDisciplina()->getCodigoDisciplina() &&
              $oDisciplinaHistorico->getResultadoFinal() == 'R') {

            $oDisciplinaHistorico->setResultadoFinal("D");
            $oDisciplinaHistorico->setLancamentoAutomatico(true);
            $oDisciplinaHistorico->salvar();
          }
        }
      }
    }
  }

  /**
   * Remove as disciplinas que o aluno possui dependencia.
   * Todas as disciplinas que foram incluidas como dependencia na etapa de origem do diario,
   * serão removidas.
   * @return array
   */
  public function removerDisciplinasComProgressaParcial () {

    $oAluno               = $this->getMatricula()->getAluno();
    $aProgressoesParciais = $oAluno->getProgressaoParcial();

    /**
     * percorremos todas as progressoes do aluno procuramos apenas as progressoes
     * em que a etapa seja a mesma da matricula do diario;
     */
    $oEtapaMatricula = $this->getMatricula()->getEtapaDeOrigem();
    $aPendencias     = array();
    $aProgressoes    = array();

    /**
     * Percorre as progressões do aluno, verificando alguma pendência existente.
     * Caso não exista pendência, guarda as progressões a serem removidas
     */
    foreach ($aProgressoesParciais as $oProgressaoParcial) {

      if ($oEtapaMatricula->getCodigo() == $oProgressaoParcial->getEtapa()->getCodigo()) {

        if ($oProgressaoParcial->isVinculadoRegencia()) {

          $oDadosPendencia         = new stdClass();
          $oDadosPendencia->iTurma = $this->getMatricula()->getTurma()->getCodigo();
          $oDadosPendencia->sAluno = $this->getMatricula()->getAluno()->getNome();

          $sMensagemErro  = "Aluno {$oAluno->getNome()} possui a progressão parcial para a disciplina ";
          $sMensagemErro .= "{$oProgressaoParcial->getDisciplina()->getNomeDisciplina()} da etapa ";
          $sMensagemErro .= "{$oProgressaoParcial->getEtapa()->getNome()} vinculada a uma turma. ";

          $oDadosPendencia->sMensagem = $sMensagemErro;

          $aPendencias[] = $oDadosPendencia;
          continue;
        }

        if ( $oProgressaoParcial->isConcluida() ) {

          $oDadosPendencia         = new stdClass();
          $oDadosPendencia->iTurma = $this->getMatricula()->getTurma()->getCodigo();
          $oDadosPendencia->sAluno = $this->getMatricula()->getAluno()->getNome();

          $sMensagemErro  = "Aluno {$oAluno->getNome()} possui progressão parcial encerrada na disciplina";
          $sMensagemErro .= " {$oProgressaoParcial->getDisciplina()->getNomeDisciplina()}, no calendário posterior a";
          $sMensagemErro .= " este. É necessário cancelar o encerramento da progressão parcial e depois excluir o";
          $sMensagemErro .= " vínculo do aluno com a turma de progressão parcial.";

          $oDadosPendencia->sMensagem = $sMensagemErro;

          $aPendencias[] = $oDadosPendencia;
          continue;
        }

        $aProgressoes[] = $oProgressaoParcial;
      }
    }

    /**
     * Caso não existam pendências e existam progressões válidas, remove as mesmas
     */
    if( count( $aPendencias ) == 0 && count( $aProgressoes ) > 0 ) {

      foreach ( $aProgressoes as $oProgressaoParcial ) {

        foreach ($this->getDisciplinas() as $oDisciplinaReprovada) {

          $oResultadoFinal = $oDisciplinaReprovada->getResultadoFinal();
          if ($oResultadoFinal->getCodigoResultadoFinal() == $oProgressaoParcial->getCodigoDiarioFinal()) {

            $oResultadoFinal->setResultadoFinal('R');
            $oResultadoFinal->setObservacao();
            $oResultadoFinal->salvar();
          }
        }

        $oProgressaoParcial->remover();
      }
    }

    return $aPendencias;
  }

  /**
   * Verifica se o aluno foi reclassificado por baixa frequencia.
   * Caso o aluno foi reclassificado, o aluno passa a ter seu resultado como aprovado na etapa.
   * @return boolean
   */
  public function reclassificadoPorBaixaFrequencia() {

    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplinas) {
      if ($oDisciplinas->reclassificadoPorBaixaFrequencia()) {
        return true;
      }
    }
    return false;
  }

  /**
   * Retorna o resultado final do diario de uma matricula
   * @return string
   */
  public function getResultadoFinal() {

    $sResultadoFinal = 'A';
    $aDisciplinas    = $this->getDisciplinas();

    foreach ($aDisciplinas as $oDisciplina) {

      if( !$oDisciplina->getRegencia()->isObrigatoria() ) {
        continue;
      }

      $oResultadoFinal = $oDisciplina->getResultadoFinal();

      if ($oResultadoFinal->getResultadoFinal() == "") {

        $sResultadoFinal = '';
        break;
      } else if ($oResultadoFinal->getResultadoFinal() == "R") {

        $sResultadoFinal = 'R';
        break;
      }
    }
    return $sResultadoFinal;
  }


  /**
   * Verifica as pendências para o encerramento do diario do aluno.
   * @return array
   */
  public function getPendenciasEncerramento() {

    $aPendencias = array();

    /**
     * Verificamos quais disciplinas estao sem resultado final
     */
    foreach ($this->getDisciplinas() as $oDisciplina) {

      if ($oDisciplina->isEncerrado()) {
        continue;
      }

      $sTipoControleDisciplina = $oDisciplina->getRegencia()->getFrequenciaGlobal();
      $oResultadoFinal         = $oDisciplina->getResultadoFinal();
      if ($oResultadoFinal->getResultadoFrequencia() == "" &&
         (trim($sTipoControleDisciplina) == "F" || trim($sTipoControleDisciplina) == "FA")
          && $oDisciplina->getRegencia()->getCondicao() != 'OP') {

        $sPendencia    = "Falta informar resultado final relativo a frequência para a disciplina ";
        $sPendencia   .= "{$oDisciplina->getDisciplina()->getNomeDisciplina()}";
        $aPendencias[] = $sPendencia;
      }

      if ( $oDisciplina->emRecuperacao() ) {

        $sPendencia    =  " Aluno esta em recuperação em: ";
        $sPendencia   .= "{$oDisciplina->getDisciplina()->getNomeDisciplina()}";
        $aPendencias[] = $sPendencia;
        continue;
      }

      if ($oResultadoFinal->getResultadoFinal() == "" && $oDisciplina->getRegencia()->getCondicao() != 'OP') {

        $sPendencia    =  " Falta informar resultado final relativo ao aproveitamento para a disciplina ";
        $sPendencia   .= "{$oDisciplina->getDisciplina()->getNomeDisciplina()}";
        $aPendencias[] = $sPendencia;
      }
    }

    /**
     * Verficamos e existe alguma progressao em aberto para o Aluno.
     */
    $aProgressoes = ProgressaoParcialAlunoRepository::getProgressoesNaoEncerradasDoAluno($this->getMatricula()
                                                                                              ->getAluno());

    foreach ($aProgressoes as $oProgressao) {

      if ( !$oProgressao->isAtiva() || $oProgressao->isConcluida() ) {
        continue;
      }

      $oVinculo = $oProgressao->getVinculoRegencia();

      if ($oVinculo == null) {

        $sPendencia    = "A progressão parcial de {$oProgressao->getDisciplina()->getNomeDisciplina()} ";
        $sPendencia   .= "({$oProgressao->getEtapa()->getNome()}) não está vinculada a uma turma.";
        $aPendencias[] = $sPendencia;
      }
      if ( !empty($oVinculo) && !$oVinculo->isEncerrado() ) {

        $sPendencia    = "Falta encerrar a progressão parcial de {$oProgressao->getDisciplina()->getNomeDisciplina()} ";
        $sPendencia   .= "({$oProgressao->getEtapa()->getNome()}) do ano de {$oVinculo->getAno()}.";
        $aPendencias[] = $sPendencia;
      }
    }
    return $aPendencias;
  }

  /**
   * Verifica se a diario está apto para ser encerrado
   * @return boolean
   */
  public function aptoParaEncerramento() {
    return count($this->getPendenciasEncerramento()) == 0;
  }

  /**
   * Atualiza o diario de um aluno, com as informacoes do diario passado por parametro
   * @param DiarioClasse $oDiario
   * @param array|null   $aListaRegenciasSubstituir - regencias para a substituicao de notas
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function atualizar(DiarioClasse $oDiario, array $aListaRegenciasSubstituir = null) {

    if (empty($oDiario)) {
      throw new ParameterException('Parâmetro $oDiario não foi informado.');
    }

    $aRegenciasSubstituir = array();
    /**
     * criamos um array associativo apenas com o codigo da regencia de destino apontando para qual regencia
     * de origem deve ser usado.
     */
    if (is_array($aListaRegenciasSubstituir)) {

      foreach ($aListaRegenciasSubstituir as $oRegenciaSubstituida) {
        $aRegenciasSubstituir[$oRegenciaSubstituida->destino->getCodigo()] = $oRegenciaSubstituida->origem->getDisciplina();
      }
    }
    if ($this->getMatricula()->getAluno()->getCodigoAluno() == $oDiario->getMatricula()->getAluno()->getCodigoAluno()) {

      foreach ($this->getDisciplinas() as $oDiarioAvaliacaoDisciplina) {


        /**
         * Percorremos as avaliacoes do aluno de cada periodo. e procuramos a disciplina correspondente do diario
         * passado no parametro, no mesmo periodo de avaliacao.
         */
        foreach ($oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacao) {

          $oDisciplinaPesquisar = $oDiarioAvaliacaoDisciplina->getRegencia()->getDisciplina();
          $iCodigoRegencia      = $oDiarioAvaliacaoDisciplina->getRegencia()->getCodigo();
          if (array_key_exists($iCodigoRegencia, $aRegenciasSubstituir)) {
             $oDisciplinaPesquisar = $aRegenciasSubstituir[$iCodigoRegencia];
          }

          $oAvaliacaoOrigem = $oDiario->getAvaliacoesPorDisciplina($oDisciplinaPesquisar,
                                                                   $oAvaliacao->getElementoAvaliacao()
                                                                  );

          if (!empty($oAvaliacaoOrigem) && $oAvaliacaoOrigem->getElementoAvaliacao() instanceof AvaliacaoPeriodica) {

            $oAvaliacao->setValorAproveitamento($oAvaliacaoOrigem->getValorAproveitamento());
            $oAvaliacao->setNumeroFaltas($oAvaliacaoOrigem->getNumeroFaltas());
            $oAvaliacao->setAproveitamentoMinimo($oAvaliacaoOrigem->temAproveitamentoMinimo());
            $oAvaliacao->setParecerPadronizado($oAvaliacaoOrigem->getParecerPadronizado());
            $oAvaliacao->setAvaliacaoExterna($oAvaliacaoOrigem->isAvaliacaoExterna());
            $oAvaliacao->setTipo($oAvaliacaoOrigem->getTipo());
            $oAvaliacao->setAmparado($oAvaliacaoOrigem->isAmparado());

            /**
             * Migra as origens da tranferência do aluno
             * @todo refatorar
             */
            $oDaoTransAprov   = new cl_transfaprov();
            $sWhereTransAprov = "ed251_i_diariodestino = {$oAvaliacaoOrigem->getCodigo()}";
            $sSqlTransAprov   = $oDaoTransAprov->sql_query_file(null, "ed251_i_codigo", null,$sWhereTransAprov);
            $rsTransAprov     = $oDaoTransAprov->sql_record($sSqlTransAprov);

            if ($oDaoTransAprov->numrows > 0) {

              $iCodigoTransfAprov = db_utils::fieldsMemory($rsTransAprov, 0)->ed251_i_codigo;

              $oDaoTransAprov->ed251_i_diariodestino = $oAvaliacao->getCodigo();
              $oDaoTransAprov->ed251_i_codigo        = $iCodigoTransfAprov;
              $oDaoTransAprov->alterar($iCodigoTransfAprov);

              if ($oDaoTransAprov->erro_status == 0) {
                throw new BusinessException("Impossível migrar origem da transferência.");
              }
            }
          }
        }

        foreach ($oDiario->getDisciplinas() as $oDiarioAvaliacaoAtual) {

          $iDisciplinaAtual    = $oDiarioAvaliacaoAtual->getDisciplina()->getCodigoDisciplina();
          $iDisciplinaAnterior = $oDiarioAvaliacaoDisciplina->getDisciplina()->getCodigoDisciplina();

          if ($iDisciplinaAtual == $iDisciplinaAnterior) {


            if ($oDiarioAvaliacaoAtual->getResultadoFinal()->getResultadoAvaliacao() == '') {
              continue;
            }

            $sResultadoAprovacao = $oDiarioAvaliacaoAtual->getResultadoFinal()->getResultadoAprovacao();
            $sResultadoFinal     = $oDiarioAvaliacaoAtual->getResultadoFinal()->getResultadoFinal();
            $nPercentualFreq     = $oDiarioAvaliacaoAtual->getResultadoFinal()->getPercentualFrequencia();
            $oResultadoAvaliacao = $oDiarioAvaliacaoAtual->getResultadoFinal()->getResultadoAvaliacao();
            $sObservacao         = $oDiarioAvaliacaoAtual->getResultadoFinal()->getObservacao();

            $oDiarioAvaliacaoDisciplina->getResultadoFinal()->setResultadoAprovacao($sResultadoAprovacao);
            $oDiarioAvaliacaoDisciplina->getResultadoFinal()->setResultadoFinal($sResultadoFinal);
            $oDiarioAvaliacaoDisciplina->getResultadoFinal()->setPercentualFrequencia($nPercentualFreq);
            $oDiarioAvaliacaoDisciplina->getResultadoFinal()->setResultadoAvaliacao($oResultadoAvaliacao);
            $oDiarioAvaliacaoDisciplina->getResultadoFinal()->setObservacao($sObservacao);

            /**
             * Verificamos se a disciplina esta amparada, se for o caso levamos o amparo para o novo Diario
             * @todo refatorar para usar classe
             */
            $oDaoAmparo         = new cl_amparo();
            $sWhere             = " ed81_i_diario = {$oDiarioAvaliacaoAtual->getCodigoDiario()}";
            $sSqlVerificaAmparo = $oDaoAmparo->sql_query_file(null, "ed81_i_codigo", null, $sWhere);
            $rsVerificaAmparo   = $oDaoAmparo->sql_record($sSqlVerificaAmparo);

            if ($oDaoAmparo->numrows > 0) {

              $sWhereVerificaAmparoDiarioAlterar = "ed81_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
              $sSqlVerificaAmparoDiarioAlterar   = $oDaoAmparo->sql_query_file(null, "*", null, $sWhereVerificaAmparoDiarioAlterar);
              $rsVerificaAmparoDiarioAlterar     = db_query( $sSqlVerificaAmparoDiarioAlterar );

              if ( pg_num_rows( $rsVerificaAmparoDiarioAlterar ) > 0 ) {

                $iCodigoAmparo        = db_utils::fieldsMemory($rsVerificaAmparo, 0)->ed81_i_codigo;
                $oDadosAmparoAnterior = db_utils::fieldsMemory( $rsVerificaAmparoDiarioAlterar, 0 );

                $oDaoAmparo->ed81_i_justificativa = $oDadosAmparoAnterior->ed81_i_justificativa;
                $oDaoAmparo->ed81_c_todoperiodo   = $oDadosAmparoAnterior->ed81_c_todoperiodo;
                $oDaoAmparo->ed81_c_aprovch       = $oDadosAmparoAnterior->ed81_c_aprovch;
                $oDaoAmparo->ed81_i_convencaoamp  = $oDadosAmparoAnterior->ed81_i_convencaoamp;
                $oDaoAmparo->ed81_i_codigo        = $iCodigoAmparo;
                $oDaoAmparo->alterar($iCodigoAmparo);

                if ($oDaoAmparo->erro_status == 0) {
                  throw new BusinessException("Impossível migrar amparo. Exclua o amparo do aluno.");
                }
              }
            }

            /**
             * Verifica se o diario possui avaliação alternativa configurada( casos onde a forma de cálculo é SOMA )
             */
            $oDaoDiarioAvaliacaoAlternativa   = new cl_diarioavaliacaoalternativa();
            $sWhereDiarioAvaliacaoAlternativa = "ed136_diario = {$oDiarioAvaliacaoAtual->getCodigoDiario()}";
            $sSqlDiarioAvaliacaoAlternativa   = $oDaoDiarioAvaliacaoAlternativa->sql_query_file(
                                                                                                 null,
                                                                                                 "ed136_procavalalternativa",
                                                                                                 null,
                                                                                                 $sWhereDiarioAvaliacaoAlternativa
                                                                                               );
            $rsDiarioAvaliacaoAlternativa = db_query( $sSqlDiarioAvaliacaoAlternativa );

            if( !$rsDiarioAvaliacaoAlternativa ) {

              $oErro        = new stdClass();
              $oErro->sErro = pg_last_error();

              throw new DBException( _M( MENSAGENS_DIARIOCLASSE_MODEL . 'erro_buscar_avaliacao_alternativa', $oErro ) );
            }

            /**
             * Caso exista vínculo do diario antigo com diarioavaliacaoalternativa, migra estes para o novo diario
             */
            if( pg_num_rows( $rsDiarioAvaliacaoAlternativa ) > 0 ) {

              $iProcAvalAlternativa  = db_utils::fieldsMemory( $rsDiarioAvaliacaoAlternativa, 0 )->ed136_procavalalternativa;
              $oAvaliacaoAlternativa = AvaliacaoAlternativaRepository::getByCodigo( $iProcAvalAlternativa );
              $oDiarioAvaliacaoDisciplina->salvarAvaliacaoAlternativa( $oAvaliacaoAlternativa );
            }
          }
        }

        $oDiarioAvaliacaoDisciplina->setEncerrado(false);
        $oDiarioAvaliacaoDisciplina->salvar();

      }
    } else {
      throw new BusinessException('Código do aluno da matrícula anterior e matrícula atual são diferentes.');
    }
  }

  /**
   * Remove todos o diario referente a uma turma, de um aluno
   * @throws ParameterException
   */
  public function remover() {

    $oDaoDiarioAvaliacao            = new cl_diarioavaliacao();
    $oDaoDiarioResultadoRecuperacao = new cl_diarioresultadorecuperacao();
    $oDaoDiarioResultado            = new cl_diarioresultado();
    $oDaoDiarioFinal                = new cl_diariofinal();
    $oDaoDiario                     = new cl_diario();

    /**
     * Percorremos os diarios, removendo de cada tabela referente ao diario
     */
    foreach ($this->getDisciplinas() as $oDiarioAvaliacaoDisciplina) {

      /**
       * Removendo diarioavaliacao
       */
      $sWhereAvaliacao  = "ed72_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
      $sSqlAvaliacao    = $oDaoDiarioAvaliacao->sql_query_file(null, "ed72_i_codigo", null, $sWhereAvaliacao);
      $rsAvaliacao      = $oDaoDiarioAvaliacao->sql_record($sSqlAvaliacao);
      $iLinhasAvaliacao = $oDaoDiarioAvaliacao->numrows;

      if ($iLinhasAvaliacao > 0) {

        for ($iContadorAvaliacao = 0; $iContadorAvaliacao < $iLinhasAvaliacao; $iContadorAvaliacao++) {

          $iCodigoDiarioAvaliacao = db_utils::fieldsMemory($rsAvaliacao, $iContadorAvaliacao)->ed72_i_codigo;

          /**
           * Verificamos se o diarioavaliacao possui algum registro na tabela abonofalta para ser excluido
           */
          $oDaoAbonoFalta    = new cl_abonofalta();
          $sWhereAbonoFalta  = "ed80_i_diarioavaliacao = {$iCodigoDiarioAvaliacao}";
          $sSqlAbonoFalta    = $oDaoAbonoFalta->sql_query_file(null, "ed80_i_codigo", null, $sWhereAbonoFalta);
          $rsAbonoFalta      = $oDaoAbonoFalta->sql_record($sSqlAbonoFalta);
          $iLinhasAbonoFalta = $oDaoAbonoFalta->numrows;

          if ($iLinhasAbonoFalta > 0) {

            for ($iContadorAbonoFalta = 0; $iContadorAbonoFalta < $iLinhasAbonoFalta; $iContadorAbonoFalta++) {

              $iCodigoAbonoFalta = db_utils::fieldsMemory($rsAbonoFalta, $iContadorAbonoFalta)->ed80_i_codigo;
              $oDaoAbonoFalta->excluir($iCodigoAbonoFalta);

              if ($oDaoAbonoFalta->erro_status == "0") {
                throw new DBException($oDaoAbonoFalta->erro_msg);
              }
            }
          }

          /**
           * Removemos os vinculos com a tabela transfaprov
           */
          $oDaoTransfaprov = new cl_transfaprov();

          $sWhereTransfAprov  = " ed251_i_diarioorigem = {$iCodigoDiarioAvaliacao} ";
          $sWhereTransfAprov .= " or ed251_i_diariodestino = {$iCodigoDiarioAvaliacao}";

          $sSqlTransfAprov = $oDaoTransfaprov->sql_query_file(null, "ed251_i_codigo", null, $sWhereTransfAprov);
          $rsTransfAprov   = $oDaoTransfaprov->sql_record($sSqlTransfAprov);
          $iLinhasTransfAprov = $oDaoTransfaprov->numrows;
          if ($rsTransfAprov && $iLinhasTransfAprov > 0) {

            for ($iContadorTransf = 0; $iContadorTransf < $iLinhasTransfAprov; $iContadorTransf++) {

              $iCodigoTransAprov = db_utils::fieldsMemory($rsTransfAprov, $iContadorTransf)->ed251_i_codigo;
              $oDaoTransfaprov->excluir($iCodigoTransAprov);
              if ($oDaoTransfaprov->erro_status == 0) {
                throw new DBException($oDaoTransfaprov->erro_msg);
              }
            }
          }

          /**
           * Verificamos se o diarioavaliacao possui algum registro na tabela pareceraval para ser excluido
           */
          $oDaoParecerAval    = new cl_pareceraval();
          $sWhereParecerAval  = "ed93_i_diarioavaliacao = {$iCodigoDiarioAvaliacao}";
          $sSqlParecerAval    = $oDaoParecerAval->sql_query_file(null, "ed93_i_codigo", null, $sWhereParecerAval);
          $rsParecerAval      = $oDaoParecerAval->sql_record($sSqlParecerAval);
          $iLinhasParecerAval = $oDaoParecerAval->numrows;

          if ($iLinhasParecerAval > 0) {

            for ($iContadorParecerAval = 0; $iContadorParecerAval < $iLinhasParecerAval; $iContadorParecerAval++) {

              $iCodigoParecerAval = db_utils::fieldsMemory($rsParecerAval, $iContadorParecerAval)->ed93_i_codigo;
              $oDaoParecerAval->excluir($iCodigoParecerAval);

              if ($oDaoParecerAval->erro_status == "0") {
                throw new DBException($oDaoParecerAval->erro_msg);
              }
            }
          }

          $oDaoDiarioAvaliacao->excluir($iCodigoDiarioAvaliacao);

          if ($oDaoDiarioAvaliacao->erro_status == "0") {
            throw new DBException($oDaoDiarioAvaliacao->erro_msg);
          }
        }
      } else {
        throw new BusinessException('Não há dados na tabela diarioavaliacao para o diário da matrícula em questão.');
      }

      /**
       * Removendo diarioresultado
       */
      $sWhereResultado  = "ed73_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
      $sSqlResultado    = $oDaoDiarioResultado->sql_query_file(null, "ed73_i_codigo", null, $sWhereResultado);
      $rsResultado      = $oDaoDiarioResultado->sql_record($sSqlResultado);
      $iLinhasResultado = $oDaoDiarioResultado->numrows;

      if ($iLinhasResultado > 0) {

        for ($iContadorResultado = 0; $iContadorResultado < $iLinhasResultado; $iContadorResultado++) {

          $iCodigoDiarioResultado = db_utils::fieldsMemory($rsResultado, $iContadorResultado)->ed73_i_codigo;

          /**
           * Verificamos se o diarioavaliacao possui algum registro na tabela parecerresult para ser excluido
           */
          $oDaoParecerResult    = db_utils::getDao("parecerresult");
          $sWhereParecerResult  = "ed63_i_diarioresultado = {$iCodigoDiarioResultado}";
          $sSqlParecerResult    = $oDaoParecerResult->sql_query_file(null, "ed63_i_codigo", null, $sWhereParecerResult);
          $rsParecerResult      = $oDaoParecerResult->sql_record($sSqlParecerResult);
          $iLinhasParecerResult = $oDaoParecerResult->numrows;

          if ($iLinhasParecerResult > 0) {

            for ($iContadorParecerResult = 0; $iContadorParecerResult < $iLinhasParecerResult; $iContadorParecerResult++) {

              $iCodigoParecerResult = db_utils::fieldsMemory($rsParecerResult, $iContadorParecerResult)->ed63_i_codigo;
              $oDaoParecerResult->excluir($iCodigoParecerResult);

              if ($oDaoParecerResult->erro_status == "0") {
                throw new DBException($oDaoParecerResult->erro_msg);
              }
            }
          }

          $oDaoDiarioResultadoRecuperacao->excluir( null, "ed116_diarioresultado = {$iCodigoDiarioResultado}" );
          $oDaoDiarioResultado->excluir($iCodigoDiarioResultado);

          if ($oDaoDiarioResultado->erro_status == "0") {
            throw new DBException($oDaoDiarioResultado->erro_msg);
          }
        }
      }

      /**
       * Removendo diariofinal
       */
      $sWhereFinal  = "ed74_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
      $sSqlFinal    = $oDaoDiarioFinal->sql_query_file(null, "ed74_i_codigo", null, $sWhereFinal);
      $rsFinal      = $oDaoDiarioFinal->sql_record($sSqlFinal);
      $iLinhasFinal = $oDaoDiarioFinal->numrows;

      if ($iLinhasFinal > 0) {

        $iCodigoDiarioFinal = db_utils::fieldsMemory($rsFinal, 0)->ed74_i_codigo;
        $oDaoDiarioFinal->excluir($iCodigoDiarioFinal);

        if ($oDaoDiarioFinal->erro_status == "0") {
          throw new DBException($oDaoDiarioFinal->erro_msg);
        }
      }

      /**
       * Removendo amparos vinculados ao diário
       */
      $oDaoAmparoExclusao = new cl_amparo();
      $sWhereAmparo       = "ed81_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
      $sSqlAmparoExclusao = $oDaoAmparoExclusao->sql_query_file( null, "*", null, $sWhereAmparo );
      $rsAmparoExclusao   = db_query( $sSqlAmparoExclusao );

      if ( !$rsAmparoExclusao ) {
        throw new DBException( "Erro ao buscar os amparos do diário:\n" . pg_result_error( $rsAmparoExclusao ) );
      }

      $iLinhasAmparoExclusao = pg_num_rows( $rsAmparoExclusao );

      if ( $iLinhasAmparoExclusao > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhasAmparoExclusao; $iContador++ ) {

          $iCodigoAmparoExclusao = db_utils::fieldsMemory( $rsAmparoExclusao, $iContador )->ed81_i_codigo;
          $oDaoAmparoExclusao->excluir( $iCodigoAmparoExclusao );

          if ( $oDaoAmparoExclusao->erro_status == "0" ) {
            throw new DBException( $oDaoAmparoExclusao->erro_msg );
          }
        }
      }

      /**
       * Remove as avaliações alternativas... validos somente para turmas avaliada por SOMA e que usem aval. alternativas
       */
      $oDaoDiarioAvaliacaoAlternativa = new cl_diarioavaliacaoalternativa();
      $oDaoDiarioAvaliacaoAlternativa->excluir(null, " ed136_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()} " );
      if ( $oDaoDiarioAvaliacaoAlternativa->erro_status == '0') {
        throw new DBException( $oDaoDiarioAvaliacaoAlternativa->erro_msg );
      }

      /**
       * Removendo diario
       */
      $oDaoDiario->excluir($oDiarioAvaliacaoDisciplina->getCodigoDiario());

      if ($oDaoDiario->erro_status == "0") {
        throw new DBException($oDaoDiario->erro_msg);
      }
    }
  }

  /**
   * Retorna o procedimento de avaliacao da turma
   * @return ProcedimentoAvaliacao
   */
  public function getProcedimentoDeAvaliacao() {
    return $this->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($this->getMatricula()->getEtapaDeOrigem());
  }

  /**
   * Encerra o diário de classe.
   */
  public function encerrar() {

    $aDisciplinas = $this->getDisciplinas();
    unset($this->aDiarioDisciplina);

    foreach ($aDisciplinas as $oDisciplina) {

      $oDisciplina->setEncerrado(true);
      $this->aDiarioDisciplina[] = $oDisciplina;
    }
    $this->salvar();

  }

  /**
   * Retorna as disciplinas que possuem Reprovacao no periodo informado.
   * @param iElementoAvaliacao $oElemento
   * @param bool               $lConsideraAmparo se deve considerar avaliações amparadas
   * @return DiarioAvaliacaoDisciplina[]
   */
  public function getDisciplinasReprovadasNoPeriodo(iElementoAvaliacao $oElemento, $lConsideraAmparo = true) {

    $aDisciplinasComReprovacaoNoPeriodo = array();
    foreach ($this->getDisciplinas() as $oDisciplina) {

      if ($oDisciplina->getRegencia()->getFrequenciaGlobal() == 'F') {
        continue;
      }

      if ( !$oDisciplina->getRegencia()->possuiCaracterReprobatorio() ) {
        continue;
      }

      foreach ($oDisciplina->getAvaliacoes() as $oAvaliacao) {

        if ($oAvaliacao->getElementoAvaliacao()->getOrdemSequencia() == $oElemento->getOrdemSequencia()) {

          if (!$lConsideraAmparo && $oAvaliacao->isAmparado()) {
            continue;
          }

          if(!$oAvaliacao->isResultado()) {
            continue;
          }

          if (!$oAvaliacao->temAproveitamentoMinimo() && $oDisciplina->temAproveitamentoLancado()) {
            $aDisciplinasComReprovacaoNoPeriodo[$oDisciplina->getCodigoDiario()] = $oDisciplina;
          }
        }
      }
    }
    return $aDisciplinasComReprovacaoNoPeriodo;
  }

  /**
   * Verifica se o aluno ficou em recuperação em uma disciplina
   * @return boolean
   */
  public function temRecuperacao() {

    foreach ($this->getDisciplinas() as $oDisciplina) {

      if ( $oDisciplina->emRecuperacao() ) {

        return true;
      }
    }
    return false;
  }

  /**
   * Verifica pendências existentes ao cancelar o encerramento de um diário
   * @return array
   */
  public function getPendenciasCancelamentoEncerramento() {

    $aPendencias  = array();
    $aProgressoes = $this->getMatricula()->getAluno()->getProgressaoParcial();

    foreach ($aProgressoes as $oProgressao) {

      if( $oProgressao->getSituacaoProgressao()->getCodigo() == ProgressaoParcialAluno::INATIVA ) {
        continue;
      }

      $oVinculo = $oProgressao->getVinculoRegencia();

      if(    $oVinculo != null
          && $oVinculo->getRegencia()->getEtapa()->getCodigo() == $this->getMatricula()->getEtapaDeOrigem()->getCodigo() ) {

        $sPendencia    = "Aluno possui a progressão parcial para a disciplina {$oProgressao->getDisciplina()->getNomeDisciplina()}";
        $sPendencia   .= " da etapa {$oProgressao->getEtapa()->getNome()} vinculada a uma turma.";

        if ( $oProgressao->isConcluida() ) {

          $sPendencia  = "Aluno {$this->getMatricula()->getAluno()->getNome()} possui progressão parcial encerrada no calendário";
          $sPendencia .= " posterior a este. É necessário cancelar o encerramento da progressão parcial e depois excluir";
          $sPendencia .= " o vínculo do aluno com a turma de progressão parcial.";
        }

        $aPendencias[] = $sPendencia;
      }
    }

    return $aPendencias;
  }

  /**
   * Retorna o valor mínimo para aprovação do aluno
   * @todo refatorar sql e implementar tratamento para NIVEL
   *
   * @return mixed $mAproveitamentoMinimo valor minimo para aproveitamento do aluno
   */
  public function getMinimoAprovacao() {

    $oProcedimentoAvaliacaoTurma =  $this->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($this->getMatricula()->getEtapaDeOrigem());
    $mAproveitamentoMinimo       = $oProcedimentoAvaliacaoTurma->getFormaAvaliacao()->getAproveitamentoMinino();

    /**
     * Se aluno ja possui resultado final, devemos buscar o minimo do resultado que gerou resultado final do aluno
     * @todo mover para uma classe
     */
    $sSqlMinimo  = " select  distinct trim(ed43_c_minimoaprov) as minimo, ed37_c_tipo, ed39_i_sequencia      ";
    $sSqlMinimo .= "   from matricula                                                                        ";
    $sSqlMinimo .= "  inner join matriculaserie      on ed221_i_matricula         = ed60_i_codigo            ";
    $sSqlMinimo .= "                                and ed221_c_origem            = 'S'                      ";
    $sSqlMinimo .= "  inner join regencia            on ed59_i_turma              = ed60_i_turma             ";
    $sSqlMinimo .= "                                and ed59_i_serie              = ed221_i_serie            ";
    $sSqlMinimo .= "  inner join diario              on ed95_i_aluno              = ed60_i_aluno             ";
    $sSqlMinimo .= "                                and ed95_i_regencia           = ed59_i_codigo            ";
    $sSqlMinimo .= "  inner join diariofinal         on ed74_i_diario             = ed95_i_codigo            ";
    $sSqlMinimo .= "  inner join procresultado       on ed74_i_procresultadoaprov = ed43_i_codigo            ";
    $sSqlMinimo .= "  inner join formaavaliacao      on ed37_i_codigo             = ed43_i_formaavaliacao    ";
    $sSqlMinimo .= "  left  join conceito            on ed39_i_formaavaliacao     = ed37_i_codigo            ";
    $sSqlMinimo .= "                                and trim (ed39_c_conceito)    = trim(ed37_c_minimoaprov) ";
    $sSqlMinimo .= "  inner join turmaserieregimemat on ed220_i_turma             = ed59_i_turma             ";
    $sSqlMinimo .= "                                and ed220_i_procedimento      = ed43_i_procedimento      ";
    $sSqlMinimo .= "  inner join serieregimemat      on ed223_i_codigo            = ed220_i_serieregimemat   ";
    $sSqlMinimo .= "                                and ed223_i_serie             = ed59_i_serie             ";
    $sSqlMinimo .= " where ed60_i_codigo = {$this->oMatricula->getCodigo()} ";

    $rsMinimo = db_query($sSqlMinimo);

    $sMinimoResultadoFinal = null;

    /**
     * @todo  foi implementado somente para forma de avaliação NOTA
     *        caso aluno possua dois resultados finais e for avaliado por conceito, vai retornar o do procedimento
     */
    if ($rsMinimo && pg_num_rows($rsMinimo) > 0) {

      $iLinhas = pg_num_rows($rsMinimo);
      for ($i = 0; $i < $iLinhas; $i++) {

        $oDados = db_utils::fieldsMemory($rsMinimo, $i);
        switch ($oDados->ed37_c_tipo) {

          case 'NOTA':
            if (is_null($sMinimoResultadoFinal) || (float) $oDados->minimo < (float) $sMinimoResultadoFinal ) {
              $sMinimoResultadoFinal = $oDados->minimo;
            }
            break;

          case 'NIVEL':
            /**
             * @todo  implementar
             */
            break;
        }
      }
    }

    if (!empty($sMinimoResultadoFinal)) {
      $mAproveitamentoMinimo = $sMinimoResultadoFinal;
    }
    return $mAproveitamentoMinimo;
  }

  /**
   * Retorna a ordem dos períodos utilizados para cálculo do resultado final
   * @return array
   * @throws DBException
   */
  public function periodosCalculoResultadoFinal() {

    $aDiarios        = array();
    $aOrdensPeriodos = array();

    foreach( $this->getDisciplinas() as $oDiarioAvaliacaoDisciplina ) {
      $aDiarios[] = $oDiarioAvaliacaoDisciplina->getCodigoDiario();
    }

    $sDiarios                  = implode( ', ', $aDiarios );
    $oDaoDiarioRegraCalculo    = new cl_diarioregracalculo();
    $sSqlDiarioRegraCalculo    = $oDaoDiarioRegraCalculo->sql_query_file(
                                                                          null,
                                                                          'distinct ed125_ordemperiodo',
                                                                          'ed125_ordemperiodo',
                                                                          "ed125_diario in({$sDiarios})"
                                                                        );
    $rsDiarioRegraCalculo = db_query( $sSqlDiarioRegraCalculo );

    if( !$rsDiarioRegraCalculo ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGENS_DIARIOCLASSE_MODEL . "erro_buscar_ordem_periodos", $oErro ) );
    }

    $iTotalLinhas = pg_num_rows( $rsDiarioRegraCalculo );

    if( $iTotalLinhas> 0 ) {

      for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {
        $aOrdensPeriodos[] = db_utils::fieldsMemory( $rsDiarioRegraCalculo, $iContador )->ed125_ordemperiodo;
      }
    }

    return $aOrdensPeriodos;
  }

  /**
   * [atualizarDiario description]
   * @param  Regencias[] $aRegencias
   * @return boolean
   */
  public function atualizarDiario($aRegencias) {

    foreach ($aRegencias as $oRegencia) {

      $oDiarioDisciplina = $this->getDisciplinasPorRegencia($oRegencia);
      if ( is_null($oDiarioDisciplina) ) {
        continue;
      }

      /**
       * Incluimos um registro em branco para os dados das avaliacoes da etapa de origem da matricula.
       */
      foreach ($oRegencia->getProcedimentoAvaliacao()->getElementos() as $oPeriodoAvaliacao) {

        if ($oPeriodoAvaliacao instanceof AvaliacaoPeriodica) {
          $this->atualizarAvaliacao($oDiarioDisciplina, $oPeriodoAvaliacao);
        } else {
          $this->atualizarResultado($oDiarioDisciplina, $oPeriodoAvaliacao);
        }
      }
    }

    return true;
  }

  /**
   * Atualiza as avaliações do aluno incluindo novos elementos de avaliação se não existem no diario do aluno
   *
   * @todo   validar se tem que atualizar alguma disciplina
   * @param  DiarioAvaliacaoDisciplina $oDiarioDisciplina
   * @param  AvaliacaoPeriodica        $oPeriodo
   * @throws DBException
   *
   * @return boolean
   */
  private function atualizarAvaliacao(DiarioAvaliacaoDisciplina $oDiarioDisciplina, AvaliacaoPeriodica $oPeriodo) {

    $sWhere  = " ed72_i_procavaliacao = {$oPeriodo->getCodigo()} ";
    $sWhere .= " and ed72_i_diario    = {$oDiarioDisciplina->getCodigoDiario()} ";

    $oDao = new cl_diarioavaliacao();
    $sSql = $oDao->sql_query_file(null, " * ", null, $sWhere);
    $rs   = db_query($sSql);
    if ( !$rs ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGENS_DIARIOCLASSE_MODEL . "erro_buscar_diarioavaliacao", $oMsgErro ) );
    }

    // Só cria o diarioavaliacao se ainda não existe
    if ( pg_num_rows($rs) == 0 ) {
      $this->salvarNovoAvaliacao($oDiarioDisciplina->getCodigoDiario(),$oPeriodo);
    }
    return true;
  }

  /**
   * Atualiza os resultados do aluno, incluindo os novos elementos de resultado
   *
   * @param  DiarioAvaliacaoDisciplina $oDiarioDisciplina [description]
   * @param  AvaliacaoPeriodica        $oPeriodo          [description]
   * @return boolean                                     [description]
   */
  private function atualizarResultado(DiarioAvaliacaoDisciplina $oDiarioDisciplina, ResultadoAvaliacao $oPeriodo) {

    $iCodigoDiario   = $oDiarioDisciplina->getCodigoDiario();
    $sWhereResultado = " ed73_i_diario = {$iCodigoDiario} and ed73_i_procresultado  = {$oPeriodo->getCodigo()} ";
    $sWhereFinal     = " ed74_i_diario = {$iCodigoDiario} and ed74_i_procresultadoaprov = {$oPeriodo->getCodigo()} ";

    /**
     * Sql busca validar a existência do resultado para o diário
     */
    $sSql  = " select (select ed73_i_codigo from diarioresultado where {$sWhereResultado}) as diarioresultado,";
    $sSql .= "        (select ed74_i_codigo from diariofinal     where {$sWhereFinal}) as diariofinal ";
    $rs    = db_query($sSql);

    if ( !$rs ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGENS_DIARIOCLASSE_MODEL . "erro_buscar_diarioavaliacao", $oMsgErro ) );
    }

    $oDados = db_utils::fieldsMemory( $rs, 0 );
    /**
     * Se ainda não existe o resultado no diário do aluno, cria o diárioresultado e se o elemento gerar resultado final
     * cria o diariofinal
     */
    if ( empty($oDados->diarioresultado) ) {

      $this->salvarNovoResultado($iCodigoDiario, $oPeriodo);
      return true;
    }

    /**
     * Se o resultado foi alterado para gerar resultado final cria o diáriofinal
     */
    if ( !empty($oDados->diarioresultado) && empty($oDados->diariofinal) && $oPeriodo->geraResultadoFinal() ) {

      $this->salvarDiarioFinal( $iCodigoDiario, $oPeriodo);
      return true;
    }

    /**
     * Se o resultado foi alterado para NÃO gerar resultado final remove o diáriofinal
     */
    if ( !empty($oDados->diarioresultado) && !empty($oDados->diariofinal) && !$oPeriodo->geraResultadoFinal() ) {

      $oDaoDiarioFinal = new cl_diariofinal();
      $oDaoDiarioFinal->excluir( null, $sWhereFinal );

      if ($oDaoDiarioFinal->erro_status == 0) {

        $oMsgErro         = new stdClass();
        $oMsgErro->sErro  = $oDaoDiarioFinal->erro_sql;
        $oMsgErro->sAluno = $this->getMatricula()->getAluno()->getNome();

        throw new DBException( _M( MENSAGENS_DIARIOCLASSE_MODEL . "erro_exluir_diariofinal", $oMsgErro ) );
      }
    }

    return true;
  }

  /**
   * Inclui os Resultados que geram o resultado final do aluno
   * @param  integer            $iCodigoDiario
   * @param  ResultadoAvaliacao $oPeriodo
   * @throws BusinessException
   *
   * @return boolean
   */
  private function salvarDiarioFinal( $iCodigoDiario, ResultadoAvaliacao $oPeriodo) {

    $oDaoDiarioFinal = new cl_diariofinal();

    /**
     * Verificado caso haja dois resultados, para incluir apenas o primeiro.
     * Caso já exista diariofinal com o código do diario informado, ele não inclui.
     */
    $sWhereDiarioFinal = "ed74_i_diario = {$iCodigoDiario}";
    $sSqlDiarioFinal   = $oDaoDiarioFinal->sql_query_file(null, '1', null, $sWhereDiarioFinal);
    $rsDiarioFinal     = db_query($sSqlDiarioFinal);

    if( $rsDiarioFinal && pg_num_rows($rsDiarioFinal) > 0 ) {
      return;
    }

    $oDaoDiarioFinal->ed74_c_resultadoaprov     = "";
    $oDaoDiarioFinal->ed74_c_resultadofinal     = "";
    $oDaoDiarioFinal->ed74_c_resultadofreq      = "";
    $oDaoDiarioFinal->ed74_c_valoraprov         = "";
    $oDaoDiarioFinal->ed74_i_calcfreq           = "";
    $oDaoDiarioFinal->ed74_i_diario             = $iCodigoDiario;
    $oDaoDiarioFinal->ed74_i_percfreq           = "";
    $oDaoDiarioFinal->ed74_i_procresultadoaprov = $oPeriodo->getCodigo();
    $oDaoDiarioFinal->ed74_i_procresultadofreq  = $oPeriodo->getCodigo();
    $oDaoDiarioFinal->incluir(null);

    if ($oDaoDiarioFinal->erro_status == "0") {

      $sMsgErro  = "Erro ao salvar resultados finais para o diario de avaliacao do ";
      $sMsgErro .= "aluno {$this->getMatricula()->getAluno()->getNome()}.\n{$oDaoDiarioFinal->erro_msg}";
      throw new BusinessException($sMsgErro);
    }

    return true;
  }

  public function apresentarNotaProporcional() {

      if ( !is_null($this->lApresentarNotaProporcional) ) {
        return $this->lApresentarNotaProporcional;
      }

      $oDaoParametros   = new cl_edu_parametros();
      $sWhereParametros = " ed233_i_escola = {$this->getTurma()->getEscola()->getCodigo()} ";
      $sSqlParametros   = $oDaoParametros->sql_query_file( null, "ed233_apresentarnotaproporcional", null, $sWhereParametros);
      $rsParametros     = db_query( $sSqlParametros );

      if ( !$rsParametros || pg_num_rows($rsParametros) == 0) {
        throw new DBException("Erro ao buscar parâmetro de apresentação da nota proporcional do Aluno.");
      }

      $this->lApresentarNotaProporcional = db_utils::fieldsMemory( $rsParametros, 0 )->ed233_apresentarnotaproporcional == 't';

    return $this->lApresentarNotaProporcional;
  }


  /**
   * PLUGIN DiarioProgressaoParcial adiciona a seguinte lógica
   * Quando aluno evadido em uma progressão parcial, este deve reprovar na matrícula atual.
   * Fazemos isso no encerramento da turma, validando se há progressões evadidas. Havendo, é alterado o resultado final
   * do aluno para 'R' (diariofinal) e incluindo o código do diariofinal na tabela plugins.diariofinalreprovadoprogressao.
   *
   * Ao cancelar o encerramento da turma, devemos apagar plugins.diariofinalreprovadoprogressao
   */

}