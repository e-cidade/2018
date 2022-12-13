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

define("URL_MENSAGEM_TURMA", "educacao.escola.Turma.");

/**
 * Turma
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.86 $
 */
class Turma {

  /**
   * Codigo sequencial da Turma
   * @var integer
   */
  private $iCodigo;

  /**
   * Descricao para turma
   * @var string
   */
  private $sDescricao;

  /**
   * Codigo da base curricular
   * @var integer
   */
  private $iBaseCurricular;

  /**
   * Instancia da Escola
   * @var Escola
   */
  private $oEscola;

  /**
   * Instancia do Calendario
   * @var Calendario
   */
  private $oCalendario;

  /**
   * Instancia da Base Curricular
   * @var BaseCurricular
   */
  private $oBase;

  /**
   * Conjunto de alunos matriculados que compoem a turma
   * @var array de Matriculas
   */
  private $aAlunosMatriculados = array();

  /**
   * conjunto de alunos vinculados a turma
   * @var array de aluno
   */
  private $aAlunosProgressaoParcial = array();

  /**
   * Conjunto de Etapas (serie) que podem compor uma turma
   * @var array
   */
  private $aEtapas;

  /**
   *
   * @var array Disciplina
   */
  private $aDisciplinas = array();

  /**
   * Instancia de Sala
   * @var Sala
   */
  private $oSala;

  /**
   * Instancia de Turno
   * @var Turno
   */
  private $oTurno;

  /**
   * Observacoes da turma
   * @var string
   */
  protected $mObservacao;

  /**
   * Tipo do calculo de frequencia da turma
   * @var integer
   */
  private $iTipoCalculoAulas;

  /**
   * Instancia do turno adicional da turma
   * @var Turno
   */
  private $oTurnoAdicional = '';

  /**
   * Indica se a turma tem turno adicional
   * @var boolean
   */
  private $lTemTurnoAdicional = false;

  /**
   * Controle da carga horaria é por dia letivo
   * @var integer
   */
  CONST CH_DIA_LETIVO = 2;

  /**
   * Controle da carga horaria é por periodo
   * @var integer
   */
  CONST CH_PERIODO = 1;

  /**
   * Constantes referentes ao turno referente da turma ( tabela turnoreferente )
   * @var integer
   */
  CONST TURNO_MANHA = 1;
  CONST TURNO_TARDE = 2;
  CONST TURNO_NOITE = 3;

  /**
   * Tipo da Turma
   * @var integer
   */
  private $iTipoTurma = null;

  /**
   * Codigo da sala em que sao ministradas as aulas da turma
   * @var integer
   */
  private $iCodigoSala = null;

  /**
   * Código INEP da Turma de acordo com o censo
   * @var integer
   */
  private $iCodigoInep;

  /**
   * Array com o número de vagas por turno referente
   * @var array
   */
  private $aNumeroVagas = array();

  /**
   * Controla o número de vagas ocupadas por turno referente
   * @var array
   */
  private $aVagasOcupadas = array();

  /**
   * Controla o número de vagas disponíveis por turno referente
   * @var array
   */
  private $aVagasDisponiveis = array();

  /**
   * Array com os turnos refenrente da turma
   * @var array
   */
  private $aTurnoReferente = array();

  /**
   * Código da etapa do censo na turma
   * @var integer
   */
  private $iCodigoEtapaCenso = null;

  /**
   * Metodo construtor da classe
   * Instancia uma nova turma, ou carrega os dados de uma turma existente
   * @param integer $iCodigoTurma
   * @throws DBException
   */
  public function __construct($iCodigoTurma = null) {

    if (!empty($iCodigoTurma)) {

      $oDaoTurma = db_utils::getDao('turma');
      $sSqlTurma = $oDaoTurma->sql_query_file($iCodigoTurma);
      $rsTurma   = db_query($sSqlTurma);

      if( !is_resource( $rsTurma ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_turma', $oErro ) );
      }

      if (pg_num_rows( $rsTurma ) > 0) {

        $oTurma = db_utils::fieldsMemory($rsTurma, 0);

        $this->iCodigo           = $oTurma->ed57_i_codigo;
        $this->sDescricao        = $oTurma->ed57_c_descr;
        $this->iBaseCurricular   = $oTurma->ed57_i_base;
        $this->mObservacao       = $oTurma->ed57_t_obs;
        $this->iTipoCalculoAulas = Turma::CH_PERIODO;
        if (trim($oTurma->ed57_c_medfreq) == 'DIAS LETIVOS') {
          $this->iTipoCalculoAulas = Turma::CH_DIA_LETIVO;
        }
        $this->oEscola                   = EscolaRepository::getEscolaByCodigo($oTurma->ed57_i_escola);
        $this->oCalendario               = CalendarioRepository::getCalendarioByCodigo($oTurma->ed57_i_calendario);
        $this->oBase                     = BaseCurricularRepository::getBaseCurricularByCodigo($oTurma->ed57_i_base);
        $this->oTurno                    = TurnoRepository::getTurnoByCodigo($oTurma->ed57_i_turno);
        $this->iTipoTurma                = $oTurma->ed57_i_tipoturma;
        $this->iCodigoSala               = $oTurma->ed57_i_sala;
        $this->iCodigoInep               = $oTurma->ed57_i_codigoinep;
      }
    }
  }

  /**
   * retorna o codigo sequencial da turma
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Atribui uma descricao para a turma
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * retorna uma descricao para a turma
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * atribui o codigo de uma base curricular para a turma
   * @param BaseCurricular $oBase
   */
  public function setBaseCurricular(BaseCurricular $oBase) {
    $this->oBase = $oBase;
  }

  /**
   * retorna o codigo de uma base curricular para a turma
   * @return BaseCurricular
   */
  public function getBaseCurricular() {
    return $this->oBase;
  }

  /**
   * Vincula uma escola a turma
   * @param Escola $oEscola
   */
  public function setEscola(Escola $oEscola) {
    $this->oEscola = $oEscola;
  }

  /**
   * Retorna a Escola da Turma
   * @return Escola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Vincula um calendario a Turma
   * @param Calendario $oCalendario
   */
  public function setCalendario(Calendario $oCalendario) {
    $this->oCalendario = $oCalendario;
  }

  /**
   * Retorna o calendario da Turma
   * @return Calendario
   */
  public function getCalendario() {
    return $this->oCalendario;
  }

  /**
   * Retorna os alunos matriculados na turma
   *
   * @param boolean $lOrdenarPorClassificacaoDaTurma parâmetro para filtrar a ordem de busca dos alunos.
   *                                                      Se: true  (padrao) busca ordenado pela Classificacao da turma (numero do aluno na chamada)
   *                                                      false busca por ordem Alfabética
   * @todo refatorar parametro de ordenação.
   * @return Matricula[]
   */
  public function getAlunosMatriculados($lOrdenarPorClassificacaoDaTurma = true) {

    if (count($this->aAlunosMatriculados) == 0 && !empty($this->iCodigo)) {

      $aAlunosMatriculados = array();
      if ($lOrdenarPorClassificacaoDaTurma) {
        $aAlunosMatriculados = MatriculaRepository::getMatriculasByTurma($this);
      } else {
        $aAlunosMatriculados = MatriculaRepository::getMatriculasByTurmaOrdemAlfabetica($this);
      }

      foreach ($aAlunosMatriculados as $oMatricula) {
        $this->aAlunosMatriculados[] = $oMatricula;
      }
    }

    return $this->aAlunosMatriculados;
  }

  /**
   * Retorna todas as matriculas na turma, pela etapa de Origem passada como parametro
   * @param Etapa $oEtapaOrigem Etapa de Origem
   * @param boolean $lOrdenarPorClassificacaoDaTurma parâmetro para filtrar a ordem de busca dos alunos.
   *                                                      Se: true  (padrao) busca ordenado pela Classificacao da turma (numero do aluno na chamada)
   *                                                      false busca por ordem Alfabética
   * @return Matricula[]
   */
  public function getAlunosMatriculadosNaTurmaPorSerie(Etapa $oEtapaOrigem, $lOrdenarPorClassificacaoDaTurma = true) {

    $aAlunos        = $this->getAlunosMatriculados( $lOrdenarPorClassificacaoDaTurma );
    $aAlunosRetorno = array();
    foreach ($aAlunos as $oAluno) {

      if (    $oAluno->getEtapaDeOrigem() instanceof Etapa
           && $oAluno->getEtapaDeOrigem()->getCodigo() == $oEtapaOrigem->getCodigo()
         ) {
        $aAlunosRetorno[] = $oAluno;
      }
    }
    return $aAlunosRetorno;
  }

  /**
   * Retorna as etapas (série) da turma
   * @return EtapaTurma[]
   * @throws DBException
   */
  public function getEtapas() {

    if (count($this->aEtapas) == 0 && $this->getCodigo() != "") {

      $oDaoTurmaSerie    = db_utils::getDao("turmaserieregimemat");
      $sCamposTurmaSerie = " ed223_i_serie,  ed220_i_procedimento, ed220_c_aprovauto";
      $sWhereTurmaSerie  = " ed220_i_turma = {$this->getCodigo()} ";
      $sSqlTurmaSerie    = $oDaoTurmaSerie->sql_query(null, $sCamposTurmaSerie, "ed11_i_sequencia", $sWhereTurmaSerie);
      $rsTurmaSerie      = db_query($sSqlTurmaSerie);

      if( !is_resource( $rsTurmaSerie ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_etapas_turma', $oErro ) );
      }

      $iTotalTurmaSerie  = pg_num_rows( $rsTurmaSerie );

      if ($iTotalTurmaSerie > 0) {

        for ($iContadorTurma = 0; $iContadorTurma < $iTotalTurmaSerie; $iContadorTurma++) {

          $oDadosTurmaSerie       = db_utils::fieldsMemory($rsTurmaSerie, $iContadorTurma);
          $oEtapa                 = EtapaRepository::getEtapaByCodigo($oDadosTurmaSerie->ed223_i_serie);
          $oProcedimentoAvaliacao = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($oDadosTurmaSerie->ed220_i_procedimento);
          $oEtapaTurma            = new EtapaTurma($oEtapa, $oProcedimentoAvaliacao);
          $oEtapaTurma->setAprovacaoAutomatica( $oDadosTurmaSerie->ed220_c_aprovauto == 'S' ? true : false );
          $this->aEtapas[]        = $oEtapaTurma;
        }
      }
    }
    return $this->aEtapas;
  }

  /**
   * Busca as regencias da turma
   * @return Regencia[]
   * @throws DBException
   */
  public function getDisciplinas() {

    if (count($this->aDisciplinas) == 0) {

      $oDaoDisciplina = new cl_regencia();
      $sCampos        = " ed59_i_codigo, ed59_i_disciplina";
      $sWhere         = " ed59_i_turma = {$this->getCodigo()} ";
      $sOrder         = 'ed59_basecomum desc, ed59_i_ordenacao, ed232_c_descr';
      $sSqlDisciplina = $oDaoDisciplina->sql_query_disciplina_censo(null, $sCampos, $sOrder, $sWhere );
      $rsDisciplina   = db_query($sSqlDisciplina);

      if( !is_resource( $rsDisciplina ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_regencias', $oErro ) );
      }

      $iTotalLinhas = pg_num_rows( $rsDisciplina );

      for ($i = 0; $i < $iTotalLinhas; $i++) {

        $oDadosDisciplina = db_utils::fieldsMemory($rsDisciplina, $i);
        $oRegencia        = RegenciaRepository::getRegenciaByCodigo($oDadosDisciplina->ed59_i_codigo);
        $oRegencia->setTurma($this);
        $this->aDisciplinas[] = $oRegencia;
        unset($oDadosDisciplina);
      }
    }

    return $this->aDisciplinas;
  }

  /**
   * Retorna a sala de aula
   * Sala de aula onde é lecionado as aulas da turma
   * @return Sala Sala de aula da Turma
   */
  public function getSala() {

    if (empty($this->oSala) && $this->iCodigoSala != null) {
      $this->oSala = new Sala($this->iCodigoSala);
    }

    return $this->oSala;
  }

  /**
   * Retorna uma instancia de Turno
   * @return Turno
   */
  public function getTurno() {
    return $this->oTurno;
  }

  /**
   * Retorna a observacao da turma
   * @return string
   */
  public function getObservacao() {
    return $this->mObservacao;
  }

  /**
   * Retorna o procedimento de avaliacao da etapa
   * @param Etapa $oEtapa
   * @return ProcedimentoAvaliacao|false
   */
  public function getProcedimentoDeAvaliacaoDaEtapa(Etapa $oEtapa) {

    $aEtapas = $this->getEtapas();
    foreach ($aEtapas as $oEtapaTurma) {

      if ($oEtapaTurma->getEtapa()->getCodigo() == $oEtapa->getCodigo()) {

        return $oEtapaTurma->getProcedimentoAvaliacao();
        break;
      }
    }
    return false;
  }

  /**
   * Retorna as disciplinas de uma determinada etapa
   * @param Etapa $oEtapa Etapa das disciplinas
   * @return Regencia[] Retorna todas as regências da turma
   */
  public function getDisciplinasPorEtapa(Etapa $oEtapa) {

    $aDisciplinas      = $this->getDisciplinas();
    $aDisciplinasEtapa = array();
    foreach ($aDisciplinas as $oDisciplina) {

      if ($oDisciplina->getEtapa()->getCodigo() === $oEtapa->getCodigo()) {
        $aDisciplinasEtapa[] = $oDisciplina;
      }
    }
    return $aDisciplinasEtapa;
  }

  /**
   * retorna todos os alunos de progressao parcia vinculados a turma
   * @param Etapa|null $oEtapa
   * @return ProgressaoParcialAluno[]
   * @throws DBException
   */
  public function getAlunosProgressaoParcial(Etapa $oEtapa = null) {

    if( count($this->aAlunosProgressaoParcial) == 0 || $oEtapa != null ) {

      $this->aAlunosProgressaoParcial = array();
      $oDaoProgressaoParcial          = new cl_progressaoparcialalunoturmaregencia();
      $sWhere                         = " ed57_i_codigo = {$this->iCodigo}";

      if ($oEtapa != null) {

        $aRegencias = array();

        foreach ( $this->getDisciplinasPorEtapa($oEtapa) as $oRegencia ) {
          $aRegencias[] = $oRegencia->getCodigo();
        }

        $aEtapasEquivalentes = array( $oEtapa->getCodigo() );
        foreach( $oEtapa->buscaEtapaEquivalente() as $oEtapaEquivalente ) {
          $aEtapasEquivalentes[] = $oEtapaEquivalente->getCodigo();
        }

        $sEtapas          = implode( ', ', $aEtapasEquivalentes );
        $sRegenciasTurma  = implode(", ", $aRegencias);
        $sWhere          .= " and ed115_regencia in ({$sRegenciasTurma}) and ed114_serie in( {$sEtapas} )";
      }

      $sSqlProgressaoParcial = $oDaoProgressaoParcial->sql_query_aluno( null,
                                                                       "ed114_sequencial",
                                                                       " ed47_v_nome ",
                                                                       $sWhere
                                                                      );

      $rsProgressaoParcial = db_query($sSqlProgressaoParcial);

      if( !is_resource( $rsProgressaoParcial ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_alunos_progressao', $oErro ) );
      }

      $iRegistros = pg_num_rows( $rsProgressaoParcial );

      if ($iRegistros > 0) {

        for ($i = 0; $i < $iRegistros; $i++) {

          $iCodigoProgressao                = db_utils::fieldsmemory($rsProgressaoParcial, $i)->ed114_sequencial;
          $this->aAlunosProgressaoParcial[] = new ProgressaoParcialAluno($iCodigoProgressao);
        }
      }
    }

    return $this->aAlunosProgressaoParcial;
  }

  /**
   * Retorna o total de vagas da turma por turno referente
   *
   * @param  integer $iTurnoReferente - Código do turno referente que deseja retornar
   * @throws DBException
   * @return array $this->aNumeroVagas[ "código do turno referente" ] = número de vagas
   */
  public function getVagas( $iTurnoReferente = null ) {

    if ( count( $this->aNumeroVagas ) == 0 ) {

      $sCamposTurmaTurnoReferente = "ed336_vagas, ed336_turnoreferente";
      $sWhereTurmaTurnoReferente  = "ed336_turma = {$this->getCodigo()}";

      if ( !empty( $iTurnoReferente ) ) {
        $sWhereTurmaTurnoReferente .= " AND ed336_turnoreferente = {$iTurnoReferente} ";
      }

      $oDaoTurmaTurnoReferente    = new cl_turmaturnoreferente();
      $sSqlTurmaTurnoReferente    = $oDaoTurmaTurnoReferente->sql_query_file(
                                                                              null,
                                                                              $sCamposTurmaTurnoReferente,
                                                                              null,
                                                                              $sWhereTurmaTurnoReferente
                                                                            );
      $rsTurmaTurnoReferente = db_query( $sSqlTurmaTurnoReferente );

      if ( !$rsTurmaTurnoReferente ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_result_error( $rsTurmaTurnoReferente );
        throw new DBException( _M( URL_MENSAGEM_TURMA . "erro_buscar_vagas", $oMensagem ) );
      }

      $iTotalLinhas = pg_num_rows( $rsTurmaTurnoReferente );

      for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

        $oDadosTurno = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador );
        $this->aNumeroVagas[ $oDadosTurno->ed336_turnoreferente ] = $oDadosTurno->ed336_vagas;
      }
    }

    return $this->aNumeroVagas;
  }

  /**
   * Retorna o total de vagas ocupadas por turno referente da turma
   *
   * @param  integer $iTurnoReferente - Código do turno referente que deseja retornar
   * @throws DBException
   * @return array $this->aVagasOcupadas[ "código do turno referente" ] = número de vagas ocupadas
   */
  public function getVagasOcupadas( $iTurnoReferente = null ) {

    if ( count( $this->aVagasOcupadas ) == 0 ) {

      $sCamposMatriculaTurno = "count(ed337_matricula) as total_matricula, ed336_turnoreferente";
      $sWhereMatriculaTurno  = "ed336_turma = {$this->getCodigo()} AND ed60_c_situacao = 'MATRICULADO'";

      if ( !empty( $iTurnoReferente ) ) {
        $sWhereMatriculaTurno .= " AND ed336_turnoreferente = {$iTurnoReferente} ";
      }
      $sWhereMatriculaTurno .= " GROUP BY ed336_turnoreferente ";

      $oDaoMatriculaTurno = new cl_matriculaturnoreferente();
      $sSqlMatriculaTurno = $oDaoMatriculaTurno->sql_query( null, $sCamposMatriculaTurno, null, $sWhereMatriculaTurno );
      $rsMatriculaTurno   = db_query( $sSqlMatriculaTurno );

      if ( !$rsMatriculaTurno ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_result_error( $rsMatriculaTurno );
        throw new DBException( _M( URL_MENSAGEM_TURMA . "erro_buscar_matriculas_turno", $oMensagem ) );
      }

      $iTotalLinhas = pg_num_rows( $rsMatriculaTurno );
      foreach( $this->getVagas() as $iIndiceTurnoReferente => $iVagas ) {

        /**
         * Caso não tenha sido retornado nenhum registro no total de matriculas por turno, preenche o array com vagas
         * ocupadas = 0 para os turnos existentes para a turma;
         * Ou quando filtramos por um turno especifico, quando turno das vagas for diferente, este será considerádo = 0;
         */
        if ( ($iTotalLinhas == 0) ||
             !empty( $iTurnoReferente ) && ($iIndiceTurnoReferente != $iTurnoReferente) ) {

          $this->aVagasOcupadas[ $iIndiceTurnoReferente ] = 0;
          continue;
        }

        for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

          $oDadosMatriculaTurno = db_utils::fieldsMemory( $rsMatriculaTurno, $iContador );
          $this->aVagasOcupadas[ $oDadosMatriculaTurno->ed336_turnoreferente ] = $oDadosMatriculaTurno->total_matricula;
        }
      }

      /**
       * Em uma turma de ensino infantil, quando uma turma conter matriculas somente em um turno,
       * devemos setar como vagas ocupas = 0 para o turno que não tem alunos matriculados.
       */
      if (count($this->getVagas()) > count($this->aVagasOcupadas)) {

        foreach ($this->getVagas() as $iIndiceTurnoReferente => $iVagas ) {

          if ( !array_key_exists($iIndiceTurnoReferente, $this->aVagasOcupadas) ) {
            $this->aVagasOcupadas[ $iIndiceTurnoReferente ] = 0;
          }
        }
      }
    }

    return $this->aVagasOcupadas;
  }

  /**
   * Retorna o total de vagas disponíveis por turno referente da turma
   *
   * @param  integer $iTurnoReferente - Código do turno referente que deseja retornar
   * @return array $this->aVagasDisponiveis[ "código do turno referente" ] = número de vagas disponíveis
   */
  public function getVagasDisponiveis() {

    $this->getVagasOcupadas();
    foreach( $this->getVagas() as $iTurnoVagas => $iVagas ) {

      if (array_key_exists($iTurnoVagas, $this->getVagasOcupadas())) {
        $this->aVagasDisponiveis[ $iTurnoVagas ] = $iVagas - $this->aVagasOcupadas[$iTurnoVagas];
      }
    }

    /**
     * Caso não exista dados em aVagasDisponiveis definimos as vagas disponíveis com o valor do array de vagas
     */
    if ( count( $this->aVagasDisponiveis ) == 0 ) {
      $this->aVagasDisponiveis = $this->getVagas();
    }

    return $this->aVagasDisponiveis;
  }

  /**
   * Retorna a forma é calculado a carga horaria da turma
   * Retorna uma das constantes Turma::CH_PERIODO quando o calculo é por periodo
   * e Turma::CH_DIA_LETIVO, quando a calculo é por dia letivo
   * @return Turma::CH_DIA_LETIVO | Turma::CH_PERIODO Forma de calculo da carga horaria
   */
  public function getFormaCalculoCargaHoraria() {
   return $this->iTipoCalculoAulas;
  }

  /**
   * Retorna a carga horária Total da turma
   * @param  Etapa $oEtapa
   * @return integer Carga horária total
   */
  public function getCargaHoraria( $oEtapa = null ) {

    $iCargaHoraria = 0;

    foreach ($this->getDisciplinas() as $oRegencia) {

      if( !is_null( $oEtapa ) && $oEtapa->getCodigo() != $oRegencia->getEtapa()->getCodigo() ) {
        continue;
      }

      $iCargaHoraria += $oRegencia->getTotalHorasAula();
    }
    return (int)$iCargaHoraria;
  }

  /**
   * Retorna o tipo da turma
   * Tipo da turma
   * @return integer tipo da turma
   */
  public function getTipoDaTurma() {
    return $this->iTipoTurma;
  }

  /**
   * Verifica se a turma já foi encerrada para a etapa
   * @param Etapa $oEtapa
   * @return bool
   * @throws DBException
   */
  public function encerradaNaEtapa(Etapa $oEtapa) {

    $oDaoRegencia = db_utils::getDao("regencia");
    $sWhere       = "ed59_i_turma = {$this->getCodigo()}";
    $sWhere      .= " and ed59_i_serie = {$oEtapa->getCodigo()}";
    $sWhere      .= " and ed59_c_encerrada = 'S' ";

    $sSqlRegenciaEncerrada = $oDaoRegencia->sql_query_file(null, "1", null, $sWhere);
    $rsRegenciaEncerrada   = db_query($sSqlRegenciaEncerrada);

    if( !is_resource( $rsRegenciaEncerrada ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_regencia_encerrada', $oErro ) );
    }

    if (pg_num_rows( $rsRegenciaEncerrada ) > 0) {
      return true;
    }
    return false;
  }

  /**
   * Verifica se a tuma está encerrada parcialmente.
   * @param Etapa $oEtapa Etapa para ser verificada
   * @return bool
   * @throws DBException
   */
  public function encerradaParcial(Etapa $oEtapa) {

    $oDaoDiario              = db_utils::getDao("diario");
    $sWhere                  = "ed59_i_turma = {$this->getCodigo()}";
    $sWhere                 .= " and ed59_i_serie = {$oEtapa->getCodigo()}";
    $sWhere                 .= " and ed60_c_ativa = 'S' ";

    $sCampos = " array_to_string(array_accum(distinct ed95_c_encerrado), ',') as situacao ";

    $sSqlTurmaEncerradaParcialmente = $oDaoDiario->sql_query_regencia_aluno(null, $sCampos, null, $sWhere);
    $rsDiariosEncerrados            = db_query($sSqlTurmaEncerradaParcialmente);

    if( !is_resource( $rsDiariosEncerrados ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_validar_encerramento_parcial', $oErro ) );
    }

    if (pg_num_rows( $rsDiariosEncerrados ) > 0) {

      $sSituacao  =  db_utils::fieldsMemory($rsDiariosEncerrados, 0)->situacao;
      $aSituacoes = explode(",", $sSituacao);

      if ( in_array("S", $aSituacoes) && in_array("N", $aSituacoes)) {
        return true;
      }
    }
    return false;
  }

  /**
   * Retorna uma instancia de Docente, referente ao regente conselheiro da turma
   * @return bool|Docente
   * @throws DBException
   */
  public function getProfessorConselheiro() {

    $oDaoRegenteConselho = db_utils::getDao("regenteconselho");
    $sCampos             = "ed235_i_rechumano";
    $sWhere              = "ed235_i_turma = {$this->iCodigo}";
    $sSqlRegenteConselho = $oDaoRegenteConselho->sql_query(null, $sCampos, null, $sWhere);
    $rsRegenteConselho   = db_query($sSqlRegenteConselho);

    if( !is_resource( $rsRegenteConselho ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_regente_conselheiro', $oErro ) );
    }

    if (pg_num_rows( $rsRegenteConselho ) > 0) {

      $iCodigoRecHumano = db_utils::fieldsMemory($rsRegenteConselho, 0)->ed235_i_rechumano;
      $oDocente         = DocenteRepository::getDocenteByCodigoRecursosHumano($iCodigoRecHumano);
      return $oDocente;
    }
    return false;
  }

  /**
   * Retorna uma instancia do turno adicional, caso exista
   * @return bool
   * @throws DBException
   */
  public function temTurnoAdicional() {

    $oDaoTurmaTurno   = db_utils::getDao("turmaturnoadicional");
    $sWhereTurmaTurno = "ed246_i_turma = {$this->getCodigo()}";
    $sSqlTurmaTurno   = $oDaoTurmaTurno->sql_query_file(null, "ed246_i_turno", null, $sWhereTurmaTurno);
    $rsTurmaTurno     = db_query($sSqlTurmaTurno);

    if( !is_resource( $rsTurmaTurno ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_turno_adicional', $oErro ) );
    }

    if (pg_num_rows( $rsTurmaTurno ) > 0) {

      $iTurnoAdicional          = db_utils::fieldsMemory($rsTurmaTurno, 0)->ed246_i_turno;
      $oTurnoAdicional          = TurnoRepository::getTurnoByCodigo($iTurnoAdicional);
      $this->oTurnoAdicional    = $oTurnoAdicional;
      $this->lTemTurnoAdicional = true;
    }

    return $this->lTemTurnoAdicional;
  }

  /**
   * Retorna uma instancia do turno adicional, caso exista
   * @return Turno
   */
  public function getTurnoAdicional() {
    return $this->oTurnoAdicional;
  }

  /**
   * Retorna os numeros bloqueados para a numeracao dos alunos
   * @return array
   * @throws DBException
   */
  public function getNumerosBloqueados() {

    $aNumerosBloqueados = array();
    if ($this->getCodigo() != "") {

      $oDaoEduNumAlunoBloqueado   = db_utils::getDao("edu_numalunobloqueado");
      $sWhereEduNumAlunoBloqueado = "ed289_i_turma = {$this->getCodigo()}";
      $sSqlEduNumAlunoBloqueado   = $oDaoEduNumAlunoBloqueado->sql_query_file(null,
                                                                              "ed289_i_numaluno",
                                                                              null,
                                                                              $sWhereEduNumAlunoBloqueado);

      $rsEduNumAlunoBloqueado = db_query($sSqlEduNumAlunoBloqueado);

      if( !is_resource( $rsEduNumAlunoBloqueado ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_numeros_bloqueados', $oErro ) );
      }

      $iTotalLinhas = pg_num_rows( $rsEduNumAlunoBloqueado );

      for ($iNumero = 0; $iNumero < $iTotalLinhas; $iNumero++) {
        $aNumerosBloqueados[] = db_utils::fieldsMemory($rsEduNumAlunoBloqueado, $iNumero)->ed289_i_numaluno;
      }
    }
    return $aNumerosBloqueados;
  }

  /**
   * Realiza a classificacao numerica da turma
   * @param boolean $lOrdenarPorClassificacaoDaTurma se deve retornar os alunos em ordem de classificação ou Alfabética
   * @param boolean $lTrocaTurma se deve classificar os alunos com a situação troca de turma
   */
  public function reclassificarNumeracaoDaTurma($lOrdenarPorClassificacaoDaTurma = true, $lTrocaTurma = false) {

    $aAlunos            = $this->getAlunosMatriculados($lOrdenarPorClassificacaoDaTurma);
    $iNumeracao         = 1;

    foreach ($aAlunos as $oMatricula) {

      if ( !$lTrocaTurma && $oMatricula->getSituacao() == "TROCA DE TURMA" ) {
        continue;
      }

      $oMatricula->setNumeroOrdemAluno($iNumeracao);
      $oMatricula->salvar();
      $iNumeracao++;
    }
  }

  /**
   * Remove a classificação numerica da turma
   */
  public function zerarNumeracaoDaTurma() {

    $aAlunos = $this->getAlunosMatriculados();

    if ( count( $this->getNumerosBloqueados() ) > 0 ) {

      $oDaoEduNumAlunoBloqueado   = db_utils::getDao("edu_numalunobloqueado");
      $oDaoEduNumAlunoBloqueado->excluir(null,"ed289_i_turma = {$this->getCodigo()}");

      if ($oDaoEduNumAlunoBloqueado->erro_status == 0) {
        throw new Exception($oDaoEduNumAlunoBloqueado->erro_msg);
      }
    }

    foreach ($aAlunos as $oMatricula) {

      $oMatricula->setNumeroOrdemAluno(null);
      $oMatricula->salvar();
    }
  }

  /**
   * Verifica se a turma já foi classificada numericamente.
   * @return boolean
   */
  public function isClassificada() {

    $lTurmaClassificada = false;
    $aAlunos            = $this->getAlunosMatriculados(true);
    foreach ($aAlunos as $oMatricula) {

      if ($oMatricula->getNumeroOrdemAluno() != '') {

        $lTurmaClassificada = true;
        break;
      }
    }
    return $lTurmaClassificada;
  }

  /**
   * Retorna o total de alunos matriculados na turma
   * @return number
   */
  public function getTotalAlunosMatriculados() {

    $iTotalAlunosMatriculados = 0;
    $aAlunos                  = $this->getAlunosMatriculados(true);
    foreach ($aAlunos as $oMatricula) {

      if ($oMatricula->getSituacao() == "MATRICULADO") {
        $iTotalAlunosMatriculados ++;
      }
    }
    return $iTotalAlunosMatriculados;
  }

  /**
   * persiste os dados da turma
   * @todo completar o metodo salvar
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoTurma                    = new cl_turma();
    $oDaoTurma->ed57_i_codigoinep = $this->getCodigoInep();

    if (!empty($this->iCodigo)) {

      $oDaoTurma->ed57_i_codigo = $this->getCodigo();
      $oDaoTurma->alterar($this->getCodigo());
    }

    if ($oDaoTurma->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados da turma.\nErro Técnico{$oDaoTurma->erro_msg}");
    }
  }

  /**
   * Retorna o ultimo número da classificação da turma.
   * Se a turma ainda não foi classificada, retorna 0 (zero)
   * @return number
   */
  public function getUltimoNumeroClassificado() {

    if ($this->getTotalAlunosMatriculados() > 0) {

      $sWhere                 = " ed60_i_turma = {$this->iCodigo} ";
      $oDaoMatricula          = new cl_matricula();
      $sSqlUltimoClassificado = $oDaoMatricula->sql_query_file(null, "max(ed60_i_numaluno) as numero", null, $sWhere);
      $rsUltimoClassificado   = db_query($sSqlUltimoClassificado);

      if ( $rsUltimoClassificado && pg_num_rows($rsUltimoClassificado) > 0) {
        return db_utils::fieldsMemory($rsUltimoClassificado, 0)->numero;
      }
    }

    return 0;
  }

  /**
   * Retorna o código INEP da turma
   * @return integer
   */
  public function getCodigoInep() {
    return $this->iCodigoInep;
  }

  /**
   * Seta um código INEP da turma
   * @param integer $iCodigoInep
   */
  public function setCodigoInep( $iCodigoInep ) {
    $this->iCodigoInep = $iCodigoInep;
  }

  /**
   * Método responsável por buscar os turnos referêntes exixstentes por turma
   * e retorna-los como um array
   * @return array
   * @throws BusinessException
   * @throws DBException
   */
  public function getTurnoReferente() {

    if (count($this->aTurnoReferente) == 0) {

      $sWhere = " ed336_turma = {$this->iCodigo} ";

      $oDaoTurnoReferente = new cl_turmaturnoreferente();
      $sSqlTurnoReferente = $oDaoTurnoReferente->sql_query_file(null, "*", "ed336_turnoreferente", $sWhere);
      $rsTurnoReferente   = db_query($sSqlTurnoReferente);

      if( !is_resource( $rsTurnoReferente ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_turno_referente', $oErro ) );
      }

      $iLinhas = pg_num_rows($rsTurnoReferente);

      if ($iLinhas == 0) {
        throw new BusinessException( _M( URL_MENSAGEM_TURMA . "sem_registro_turno" ) );
      }

      for ($i = 0; $i < $iLinhas; $i++) {

        $oDados = db_utils::fieldsmemory($rsTurnoReferente, $i);
        $this->aTurnoReferente[$oDados->ed336_turnoreferente] = $oDados;
      }
    }

    return $this->aTurnoReferente;
  }

  /**
   * Valida se a turma é compativel com o Critério de Avaliação informado
   * Para a turma validar, é necessário que ao menos uma disciplina da turma, esteja presente no Critério de Avaliação
   * @param CriterioAvaliacao $oCriterioAvaliacao
   * @return bool
   */
  public function permiteVinculoCriterioAvaliacao( CriterioAvaliacao $oCriterioAvaliacao ) {

    $lCompativelDisciplina = false;
    foreach ( $oCriterioAvaliacao->getDisciplinas() as $oDisciplinaCriterio ) {

      foreach ( $this->getDisciplinas() as $oRegencia ) {

        if ( $oRegencia->getDisciplina()->getCodigoDisciplina() == $oDisciplinaCriterio->getCodigoDisciplina() ) {
          $lCompativelDisciplina = true;
          break;
        }
      }
    }
    return $lCompativelDisciplina;
  }

  /**
   * Verifica se a turma possui disciplinas com frequência globalizada
   *
   * @return bool
   */
  public function isFrequenciaGlobalizada() {

    $aFrequenciasValidas = array('F', 'FA');
    foreach ($this->getDisciplinas() as $oDisciplina) {

      if (in_array($oDisciplina->getFrequenciaGlobal(), $aFrequenciasValidas)) {
        return true;
      }
    }
    return false;
  }

  /**
   * Salva o vínculo de um profissional e sua atividade, com a turma
   * @param integer $iRecHumano
   * @param integer $iFuncaoAtividade
   * @throws DBException
   * @throws Exception
   */
  public function salvarVinculoOutrosProfissionais( $iRecHumano, $iFuncaoAtividade ) {

    if ( empty( $iRecHumano ) ) {
      throw new Exception( _M(URL_MENSAGEM_TURMA . "informe_profissional") );
    }

    if ( empty( $iFuncaoAtividade ) ) {
      throw new Exception( _M(URL_MENSAGEM_TURMA . "informe_atividade") );
    }

    $oDaoTurmaOutrosProfissionais                        = new cl_turmaoutrosprofissionais();
    $oDaoTurmaOutrosProfissionais->ed347_turma           = $this->iCodigo;
    $oDaoTurmaOutrosProfissionais->ed347_rechumano       = $iRecHumano;
    $oDaoTurmaOutrosProfissionais->ed347_funcaoatividade = $iFuncaoAtividade;
    $oDaoTurmaOutrosProfissionais->incluir( null );

    if( $oDaoTurmaOutrosProfissionais->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoTurmaOutrosProfissionais->erro_msg;
      throw new DBException( _M( URL_MENSAGEM_TURMA . "erro_vincular_profissional", $oMensagem ) );
    }
  }

  /**
   * Busca os profissionais vinculados a turma
   * @return stdClass[]
   * @throws DBException
   */
  public function getProfissionaisVinculados() {

    $oDaoOutrosProfissionais     = new cl_turmaoutrosprofissionais();
    $sCamposOutrosProfissionais  = "ed347_sequencial as codigo, ed119_descricao as atividade";
    $sCamposOutrosProfissionais .= ", case  ";
    $sCamposOutrosProfissionais .= "       when ed20_i_tiposervidor = 1 ";
    $sCamposOutrosProfissionais .= "            then cgmrh.z01_nome ";
    $sCamposOutrosProfissionais .= "            else cgmcgm.z01_nome ";
    $sCamposOutrosProfissionais .= "   end as nome";
    $sCamposOutrosProfissionais .= ", ed347_rechumano as rechumano";
    $sWhereOutrosProfissionais   = "ed347_turma = {$this->iCodigo}";

    $sSqlOutrosProfissionais     = $oDaoOutrosProfissionais->sql_query_profissional_atividade('',
                                                                                              $sCamposOutrosProfissionais,
                                                                                              '',
                                                                                              $sWhereOutrosProfissionais);
    $rsOutrosProfissionais = db_query( $sSqlOutrosProfissionais );

    if( !$rsOutrosProfissionais ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsOutrosProfissionais );
      throw new DBException( _M( URL_MENSAGEM_TURMA . "erro_buscar_profissionais", $oMensagem ) );
    }

    return db_utils::getCollectionByRecord( $rsOutrosProfissionais );
  }

  /**
   * Exclui o vínculo do profissional e sua atividade com a turma
   * @param integer $iCodigoVinculo
   * @throws DBException
   * @throws ParameterException
   */
  public function excluirProfissionalVinculado( $iCodigoVinculo ) {

    if( empty( $iCodigoVinculo ) ) {
      throw new ParameterException( _M( URL_MENSAGEM_TURMA . "codigo_vinculo_nao_informado" ) );
    }

    $oDaoOutrosProfissionais = new cl_turmaoutrosprofissionais();
    $oDaoOutrosProfissionais->excluir( $iCodigoVinculo );

    if( $oDaoOutrosProfissionais->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoOutrosProfissionais->erro_msg;
      throw new DBException( _M( URL_MENSAGEM_TURMA . "erro_excluir_profissional", $oMensagem ) );
    }
  }

  /**
   * Retorna somente a ultima matrícula dos alunos vinculados a turma
   * @param bool|true  $lOrdenarNome
   * @param Etapa|null $oEtapaOrigem
   * @return Matricula[]
   * @throws DBException
   */
  public function getUltimaMatriculaAlunos( $lOrdenarNome = true , Etapa $oEtapaOrigem = null) {

    $aMatriculas      = array();
    $oDaoMatricula    = new cl_matricula();
    $sCamposMatricula = 'max(ed60_i_codigo) as matricula';
    $sOrdenacao       = $lOrdenarNome ? 'ed47_v_nome' : '';
    $sWhereMatricula  = "ed60_i_turma = {$this->iCodigo} and ed221_c_origem = 'S' ";

    if ( !is_null($oEtapaOrigem) ) {
      $sWhereMatricula .= " and ed221_i_serie = " . $oEtapaOrigem->getCodigo();
    }

    $sWhereMatricula .= " group by ed60_i_aluno, ed47_v_nome ";
    $sSqlMatricula    = $oDaoMatricula->sql_query_aluno( null, $sCamposMatricula, $sOrdenacao, $sWhereMatricula );
    $rsMatricula      = db_query( $sSqlMatricula );

    if( !is_resource( $rsMatricula ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( URL_MENSAGEM_TURMA . 'erro_buscar_ultima_matricula', $oErro ) );
    }

    $iLinhasMatricula = pg_num_rows( $rsMatricula );

    if ( $iLinhasMatricula > 0 ) {

      for ( $iContador = 0; $iContador < $iLinhasMatricula; $iContador++ ) {

        $iMatricula    = db_utils::fieldsMemory( $rsMatricula, $iContador )->matricula;
        $oMatricula    = MatriculaRepository::getMatriculaByCodigo( $iMatricula );
        $aMatriculas[] = $oMatricula;
      }
    }

    return $aMatriculas;
  }

  /**
   * Verifica se a turma possui algum critério de avaliação vinculado a mesma
   * @return bool
   */
  public function possuiCriterioAvaliacao() {

    $oDaoCriterioAvalicao    = new cl_criterioavaliacaoturma();
    $sWhereCriterioAvaliacao = "ed341_turma = {$this->iCodigo}";
    $sSqlCriterioAvaliacao   = $oDaoCriterioAvalicao->sql_query_file(null, '1', null, $sWhereCriterioAvaliacao);
    $rsCriterioAvalicao      = db_query( $sSqlCriterioAvaliacao );

    if ( $rsCriterioAvalicao && pg_num_rows( $rsCriterioAvalicao ) > 0 ) {
      return true;
    }

    return false;
  }

  /**
   * Compara os Procedimentos de Avaliação das Regências entre as Disciplinas da Turmas e verifica se os
   * Procedimentos são iguais
   *
   *   Ex.:
   *     Turma1 - Português = Nota
   *     Turma2 - Português = Conceito
   *     Retorna false
   *   Ex.:
   *     Turma1 - Matemática = Conceito
   *     Turma2 - Matemática = Conceito
   *     Retorna true
   * @param  Turma  $oTurmaNova
   * @param  Etapa  $oEtapaOrigem Etapa da turma de origem
   * @param  Etapa  $oEtapaDestino Etapa da turma de destino
   * @return boolean
   */
  public function possuiMesmoProcedimentosAvaliacao( Turma $oTurmaNova, Etapa $oEtapaOrigem, Etapa $oEtapaDestino ) {

    foreach( $this->getDisciplinasPorEtapa($oEtapaOrigem) as $oRegenciaAtual ) {

      foreach( $oTurmaNova->getDisciplinasPorEtapa($oEtapaDestino) as $oRegenciaNova ) {

        if ( $oRegenciaAtual->getDisciplina()->getCodigoDisciplina() == $oRegenciaNova->getDisciplina()->getCodigoDisciplina() ) {

          if ( !$oRegenciaAtual->possuiMesmoProcedimentoAvaliacao( $oRegenciaNova ) ) {
            return false;
          }
        }
      }
    }
    return true;
  }

  /**
   * Verifica se a turma tem vaga disponível em determinado turno, ou em algum dos turnos existentes
   * @param integer|null $iTurnoReferente
   * @return bool
   */
  public function temVagaDisponivel( $iTurnoReferente = null ) {

    $this->getVagasDisponiveis();

    if( !empty( $iTurnoReferente ) ) {

      if ( $this->aVagasDisponiveis[ $iTurnoReferente ] == 0) {
        return false;
      }
      return true;
    }

    $lTemVaga = false;

    foreach( $this->aVagasDisponiveis as $iQuantidadeVagas ) {

      if ( $iQuantidadeVagas > 0 ) {
        $lTemVaga = true;
      }
    }

    return $lTemVaga;
  }

  /**
   * Busca o código da etapa do censo vinculado a turma
   * @return integer
   */
  public function getEtapaCenso() {

    if ( is_null($this->iCodigoEtapaCenso) ) {

      $oDaoTurmaCensoEtapa   = new cl_turmacensoetapa();
      $sWhereTurmaCensoEtapa = "ed132_turma = {$this->iCodigo}";
      $sSqlTurmaCensoEtapa   = $oDaoTurmaCensoEtapa->sql_query_file( null, 'ed132_censoetapa', null, $sWhereTurmaCensoEtapa );
      $rsTurmaCensoEtapa     = db_query($sSqlTurmaCensoEtapa);

      if ( !$rsTurmaCensoEtapa || pg_num_rows($rsTurmaCensoEtapa) == 0 ) {
        throw new DBException("Erro ao buscar a etapa do censo da turma.");
      }

      $this->iCodigoEtapaCenso = db_utils::fieldsmemory( $rsTurmaCensoEtapa, 0 )->ed132_censoetapa;
    }

    return $this->iCodigoEtapaCenso;
  }
}