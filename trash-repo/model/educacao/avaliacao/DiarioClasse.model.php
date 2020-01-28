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


/**
 * Controla o lançamento das notas do aluno
 * @author  Fábio Esteves - fabio.esteves@dbseller.com.br
 * @package educacao
 * @subpackage avaliacao
 * @version $Revision: 1.60 $
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
  private $aDiarioDisciplina;

  /**
   * Dados da turma que a matrícula está vinculada
   * @var object
   */
  private $oTurma;


  private $oProcedimentoDeAvaliacao = null;

  /**
   * Array dos períodos da turma
   * @var array
   */
  private $aPeriodosAvaliacao;

  /**
   * Array de cache para as disciplinas Reprovadas
   * @var DiarioAvaliacaoDisciplina
   */
  private $aDisciplinasComReprovacao = array();

  public function __construct(Matricula $oMatricula) {

    $this->oMatricula = $oMatricula;
    $this->criarDiarioClasseAluno();
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
   * @return array Periodos
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

    $oDaoRegencia      = db_utils::getDao("regencia");
    $oDaoDiarioClasse  = db_utils::getDao("diario");
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

    $iCodigoEtapaOrigem = $this->getMatricula()->getEtapaDeOrigem()->getCodigo();
    if (!empty($iCodigoEtapaOrigem)) {
      $sWhereRegencia .= " and ed59_i_serie = {$iCodigoEtapaOrigem}";
    }

    $sSqlRegencias        = $oDaoRegencia->sql_query_avaliacao(null,
                                                          "distinct ed59_i_ordenacao, ed59_i_codigo, ed223_i_serie",
                                                          "ed59_i_ordenacao", $sWhereRegencia);

    $rsRegenciasSemDiario = $oDaoRegencia->sql_record($sSqlRegencias);

    /**
     * Incluimos o diario do aluno, para as disciplinas que nao possuem diario.
     */
    $iTotalRegenciasSemDiario = $oDaoRegencia->numrows;
    for ($i = 0; $i < $iTotalRegenciasSemDiario; $i++) {

      $oRegencia        = db_utils::fieldsMemory($rsRegenciasSemDiario, $i);
      $sDiarioEncerrado = 'N';
      if ($this->getMatricula()->getSituacao() != "MATRICULADO") {
        $sDiarioEncerrado = "S";
      }
      $oDaoDiarioClasse->ed95_i_aluno      = $this->getMatricula()->getAluno()->getCodigoAluno();
      $oDaoDiarioClasse->ed95_c_encerrado  = $sDiarioEncerrado;
      $oDaoDiarioClasse->ed95_i_calendario = $this->getTurma()->getCalendario()->getCodigo();
      $oDaoDiarioClasse->ed95_i_escola     = db_getsession("DB_coddepto");
      $oDaoDiarioClasse->ed95_i_regencia   = $oRegencia->ed59_i_codigo;
      $oDaoDiarioClasse->ed95_i_serie      = $oRegencia->ed223_i_serie;
      $oDaoDiarioClasse->incluir(null);
      if ($oDaoDiarioClasse->erro_status == 0) {

        $sMsgErro = "Erro ao salvar dados do diario de classe do aluno {$this->getMatricula()->getAluno()->getNome()}";
        throw new BusinessException($sMsgErro);
      }

      /**
       * Incluimos um registro em branco para os dados das avaliacoes da etapa de origem da matricula.
       */
      foreach ($this->getPeriodoAvaliacao($oRegencia->ed223_i_serie) as $oPeriodoAvaliacao) {

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

      $oDaoDiarioClasse = db_utils::getDao("diario");

      $sWhereDiario  = " ed60_i_codigo       = {$this->getMatricula()->getCodigo()}";
      $sWhereDiario .= " and ed95_i_regencia = ed59_i_codigo";
      $sWhereDiario .= " and ed95_i_serie    = ed59_i_serie";
      $sWhereDiario .= " and ed59_i_turma    = {$this->getMatricula()->getTurma()->getCodigo()}";


      $sSqlDiarioClasse = $oDaoDiarioClasse->sql_query_diario_classe(null,
                                                                     'diario.*,
                                                                     ed59_i_codigo',
                                                                     "ed95_i_codigo",
                                                                     $sWhereDiario
                                                                     );
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
   * @param integer $iCodigoDiario codigo do diario de avaliacao
   * @param AvaliacaoPeriodica $oPeriodo Instancia do periodo
   */
  protected function salvarNovoAvaliacao($iCodigoDiario, AvaliacaoPeriodica $oPeriodo) {

    $oDaoDiarioAvaliacao                       = db_utils::getDao("diarioavaliacao");
    $oDaoDiarioAvaliacao->ed72_c_amparo        = 'N';
    $oDaoDiarioAvaliacao->ed72_c_aprovmin      = 'N';

    if ($oPeriodo->getFormaDeAvaliacao()->getTipo() == 'PARECER') {
      $oDaoDiarioResultado->ed73_c_aprovmin = 'S';
    }

    $oDaoDiarioAvaliacao->ed72_c_convertido    = 'N';
    $oDaoDiarioAvaliacao->ed72_c_tipo          = 'M';
    $oDaoDiarioAvaliacao->ed72_i_procavaliacao = $oPeriodo->getCodigo();
    $oDaoDiarioAvaliacao->ed72_i_diario        = $iCodigoDiario;
    $oDaoDiarioAvaliacao->ed72_i_escola        = db_getsession("DB_coddepto");
    $oDaoDiarioAvaliacao->incluir(null);

    if ($oDaoDiarioAvaliacao->erro_status == 0) {

      $sMsgErro  = "Erro ao salvar dados do diario de avaliacao do aluno";
      $sMsgErro .= "{$this->getMatricula()->getAluno()->getNome()}.\n$oDaoDiarioAvaliacao->erro_msg";
      throw new BusinessException($sMsgErro);
    }
  }

  /**
   * persite os dados do novo resultado
   * @param integer $iCodigoDiario codigo do diario de avalicao
   * @param ResultadoAvaliacao $oPeriodo instancia do resultado final
   */
  protected function salvarNovoResultado($iCodigoDiario, ResultadoAvaliacao $oPeriodo) {

    $oDaoDiarioResultado                       = db_utils::getDao("diarioresultado");
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

      $oDaoDiarioFinal                            = db_utils::getDao("diariofinal");
      $oDaoDiarioFinal->ed74_c_resultadoaprov     = "";
      $oDaoDiarioFinal->ed74_c_resultadofinal     = "";
      $oDaoDiarioFinal->ed74_c_resultadofreq      = "";
      $oDaoDiarioFinal->ed74_c_valoraprov         = "";
      $oDaoDiarioFinal->ed74_i_calcfreq           = "";
      $oDaoDiarioFinal->ed74_i_diario             = $iCodigoDiario;
      $oDaoDiarioFinal->ed74_i_percfreq           = "";
      $oDaoDiarioFinal->ed74_i_procresultadoaprov = $oPeriodo->getCodigo();
      $oDaoDiarioFinal->ed74_i_procresultadofreq  = $oPeriodo->getCodigo();
      if ($oPeriodo->getFormaDeAvaliacao()->getTipo() == 'PARECER') {

        $oDaoDiarioFinal->ed74_c_resultadoaprov = "A";
        $oDaoDiarioFinal->ed74_c_resultadofreq  = "A";
        $oDaoDiarioFinal->ed74_c_resultadofinal = "A";
      }
      $oDaoDiarioFinal->incluir(null);
      if ($oDaoDiarioFinal->erro_status == "0") {

        $sMsgErro  = "Erro ao salvar resultados finais para o diario de avaliacao do ";
        $sMsgErro .= "aluno {$this->getMatricula()->getAluno()->getNome()}.\n{$oDaoDiarioFinal->erro_msg}";
        throw new BusinessException($sMsgErro);
      }
    }
  }

  /**
   * persiste os dados do diario
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
   * @return DiarioAvaliacaoDisciplina[]
   */
  public function getDisciplinasPorRegencia(Regencia $oRegencia) {

    $aDisciplinas = $this->getDisciplinas();
    foreach ($aDisciplinas as $oDisciplinas) {

      if ($oRegencia->getCodigo() == $oDisciplinas->getRegencia()->getCodigo()) {
        return $oDisciplinas;
      }
    }
  }

  /**
   * Retorna o Aproveitamento para o periodo
   * @param Regencia $oRegencia  instancia de Regencia
   * @param IElementoAvaliacao $oElemento instancia de um Elemento de avalaiacao ou resultado
   * @return boolean|Ambigous <AvaliacaoAproveitamento, multitype:>
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
   * @param Regencia $oRegencia  instancia de Regencia
   * @param IElementoAvaliacao $oElemento instancia de um Elemento de avalaiacao ou resultado
   * @return boolean|Ambigous <AvaliacaoAproveitamento, multitype:>
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
   * @return DiarioAvaliacaoDisciplina  com as disciplinas reprovadas
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
   * @return DiarioAvaliacaoDisciplina Disciplinas Reprovadas na Frequência
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
    $aProgressoes                = ProgressaoParcialAlunoRepository::getProgressoesReprovadas($this->getMatricula()->
                                                                                                     getAluno()
                                                                                             );

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

    /**
     * caso umas das disciplina em que o aluno está reprovado no ensino regular, também for reprovado
     * na progressao, o aluno nao pode ser aprovado como progressao.
     */
    foreach ($this->getDisciplinasComReprovacaoNoAproveitamento() as $oDisciplinaReprovada) {

      foreach ($aProgressoes as $oProgressao) {

        $oDisciplinaProgressao = $oProgressao->getDisciplina()->getCodigoDisciplina();
        if ($oDisciplinaProgressao == $oDisciplinaReprovada->getDisciplina()->getCodigoDisciplina()) {

          return false;
          break;
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
        
        $oResultadoFinal = $oDisciplinaReprovada->getResultadoFinal();
        $oResultadoFinal->setResultadoFinal('A');
        $oResultadoFinal->setObservacao("Aluno aprovado nesta disciplina através de progressão parcial.");
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
   */
  public function removerDisciplinasComProgressaParcial () {

    $oAluno               = $this->getMatricula()->getAluno();
    $aProgressoesParciais = $oAluno->getProgressaoParcial();

    /**
     * percorremos todas as progressoes do aluno procuramos apenas as progressoes
     * em que a etapa seja a mesma da matricula do diario;
     */
    $oEtapaMatricula = $this->getMatricula()->getEtapaDeOrigem();
    foreach ($aProgressoesParciais as $oProgressaoParcial) {

      if ($oEtapaMatricula->getCodigo() == $oProgressaoParcial->getEtapa()->getCodigo()) {
        if ($oProgressaoParcial->isVinculadoRegencia()) {

          $sMensagemErro  = "Aluno {$oAluno->getNome()} possui a progressão parcial para a disciplina ";
          $sMensagemErro .= "{$oProgressaoParcial->getDisciplina()->getNomeDisciplina()} da etapa ";
          $sMensagemErro .= "{$oProgressaoParcial->getEtapa()->getNome()} vinculada a uma turma. ";
          throw new BusinessException($sMensagemErro);
        }

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

      $oResultadoFinal = $oDisciplina->getResultadoFinal();
      if ($oDisciplina->getRegencia()->getCondicao() != "OP") {

        if ($oResultadoFinal->getResultadoFinal() == "") {

          $sResultadoFinal = '';
          break;
        } else if ($oResultadoFinal->getResultadoFinal() == "R") {

          $sResultadoFinal = 'R';
          break;
        }
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
                                                                                              ->getAluno()
                                                                                        );

    foreach ($aProgressoes as $oProgressao) {

      if ( !$oProgressao->isAtiva() || $oProgressao->isConcluida() ) {
        continue;
      } 
      
      $oVinculo = $oProgressao->getVinculoRegencia();

      if ($oVinculo == null) {

        $sPendencia    = "A progressao parcial de {$oProgressao->getDisciplina()->getNomeDisciplina()} ";
        $sPendencia   .= "({$oProgressao->getEtapa()->getNome()}) não está vinculada a uma turma.";
        $aPendencias[] = $sPendencia;
      } else {

        $sPendencia    = "Falta encerrar a progressao parcial de {$oProgressao->getDisciplina()->getNomeDisciplina()} ";
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
   * @param Array $aListaRegenciasSubstituir regencias para a substituicao de notas
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

    $oEtapa                           = $this->getMatricula()->getEtapaDeOrigem();
    $oEtapaDiarioBase                 = $oDiario->getMatricula()->getEtapaDeOrigem();
    $oProcedimentoAvaliacaoDiario     = $this->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);
    $oProcedimentoAvaliacaoDiarioBase = $oDiario->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

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

              $iCodigoAmparo             = db_utils::fieldsMemory($rsVerificaAmparo, 0)->ed81_i_codigo;
              $oDaoAmparo->ed81_i_diario = $oDiarioAvaliacaoDisciplina->getCodigoDiario();
              $oDaoAmparo->ed81_i_codigo = $iCodigoAmparo;
              $oDaoAmparo->alterar($iCodigoAmparo);

              if ($oDaoAmparo->erro_status == 0) {
                throw new BusinessException("Impossível migrar amparo. Exclua o amparo do aluno.");
              }
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

    $oDaoDiarioAvaliacao = db_utils::getDao("diarioavaliacao");
    $oDaoDiarioResultado = db_utils::getDao("diarioresultado");
    $oDaoDiarioFinal     = db_utils::getDao("diariofinal");
    $oDaoDiario          = db_utils::getDao("diario");

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
          $oDaoAbonoFalta    = db_utils::getDao("abonofalta");
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
          $oDaoParecerAval    = db_utils::getDao("pareceraval");
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
   * @return DiarioAvaliacaoDisciplina[]
   */
  public function getDisciplinasReprovadasNoPeriodo(iElementoAvaliacao $oElemento) {

    $aDisciplinasComReprovacaoNoPeriodo = array();
    foreach ($this->getDisciplinas() as $oDisciplina) {

      if ($oDisciplina->getRegencia()->getFrequenciaGlobal() == 'F') {
        continue;
      }
      
      foreach ($oDisciplina->getAvaliacoes() as $oAvaliacao) {

        if ($oAvaliacao->getElementoAvaliacao()->getCodigo() == $oElemento->getCodigo()) {
          if (!$oAvaliacao->temAproveitamentoMinimo()) {
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
}

?>