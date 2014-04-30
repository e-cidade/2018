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

define("URL_MENSAGEM_TURMA", "educacao.escola.Turma.");

/**
 * Turma
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.38 $
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
  private $aDisciplinas;

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
   * @var memo
   */
  protected $mObservacao;

  /**
   * Numero de vagas da turma
   * @var integer
   */
  private $iNumeroVagas;

  /**
   * Numero de alunos Matriculados
   * @var integer
   */
  private $iNumeroAlunosMatriculados;

  /**
   * Numero de alunos Vinculados como Progressao Parcial
   * @var integer
   */
  private $iNumeroAlunosVinculados = 0;


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
   * Metodo construtural da classe
   * Instancia uma nova turma, ou carrega os dados de uma turma existente
   * @param integer $iCodigoTurma Código da turma
   */
  public function __construct($iCodigoTurma = null) {

    if (!empty($iCodigoTurma)) {

      $oDaoTurma = db_utils::getDao('turma');
      $sSqlTurma = $oDaoTurma->sql_query_file($iCodigoTurma);
      $rsTurma   = $oDaoTurma->sql_record($sSqlTurma);

      if ($oDaoTurma->numrows > 0) {

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
        $this->iNumeroVagas              = $oTurma->ed57_i_numvagas;
        $this->iNumeroAlunosMatriculados = $oTurma->ed57_i_nummatr;
        $this->iTipoTurma                = $oTurma->ed57_i_tipoturma;
        $this->iCodigoSala               = $oTurma->ed57_i_sala;
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
   * @param bolean $lOrdenarPorClassificacaoDaTurma parâmetro para filtrar a ordem de busca dos alunos.
   *        Se: true  (padrao) busca ordenado pela Classificacao da turma (numero do aluno na chamada)
   *            false busca por ordem Alfabética
   * @todo refatorar parametro de ordenação.
   * @return Matricula
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
   * @return Matricula
   */
  public function getAlunosMatriculadosNaTurmaPorSerie(Etapa $oEtapaOrigem) {

    $aAlunos        = $this->getAlunosMatriculados();
    $aAlunosRetorno = array();
    foreach ($aAlunos as $oAluno) {

      if ($oAluno->getEtapaDeOrigem()->getCodigo() == $oEtapaOrigem->getCodigo()) {
        $aAlunosRetorno[] = $oAluno;
      }
    }
    return $aAlunosRetorno;
  }

  /**
   * Retorna as etapas (série) da turma
   * @return EtapaTurma
   */
  public function getEtapas() {

    if (count($this->aEtapas) == 0 && $this->getCodigo() != "") {

      $oDaoTurmaSerie    = db_utils::getDao("turmaserieregimemat");
      $sCamposTurmaSerie = " ed223_i_serie,  ed220_i_procedimento, ed220_c_aprovauto";
      $sWhereTurmaSerie  = " ed220_i_turma = {$this->getCodigo()} ";
      $sSqlTurmaSerie    = $oDaoTurmaSerie->sql_query(null, $sCamposTurmaSerie, null, $sWhereTurmaSerie);
      $rsTurmaSerie      = $oDaoTurmaSerie->sql_record($sSqlTurmaSerie);
      $iTotalTurmaSerie  = $oDaoTurmaSerie->numrows;

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
   * @throws BusinessException
   * @return array Regencia
   */
  public function getDisciplinas() {

    if (count($this->aDisciplinas) == 0) {

      $oDaoDisciplina = db_utils::getDao('regencia');
      $sCampos        = " ed59_i_codigo, ed59_i_disciplina";
      $sWhere         = " ed59_i_turma = {$this->getCodigo()} ";
      $sSqlDisciplina = $oDaoDisciplina->sql_query_file(null,
                                                        $sCampos,
                                                        'ed59_i_ordenacao',
                                                         $sWhere
                                                       );
      $rsDisciplina   = $oDaoDisciplina->sql_record($sSqlDisciplina);
      $iTotalLinhas   = $oDaoDisciplina->numrows;

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
   * @return memo
   */
  public function getObservacao() {
    return $this->mObservacao;
  }

  /**
   * Retorna o procedimento de avaliacao da etapa
   * @param Etapa $oEtapa
   * @return boolean:ProcedimentoAvaliacao
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
   * @return Regencia Retorna todas as regências da turma
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
   * @return ProgressaoParcialAluno
   */
  public function getAlunosProgressaoParcial(Etapa $oEtapa = null) {

    if (count($this->aAlunosProgressaoParcial) == 0) {

      $oDaoProgressaoParcial = db_utils::getdao("progressaoparcialalunoturmaregencia");

      $sWhere = " ed57_i_codigo = {$this->iCodigo}";
      
      if ($oEtapa != null) {
        
        $aRegencias = array();
        foreach ( $this->getDisciplinasPorEtapa($oEtapa) as $oRegencia ) {
          $aRegencias[] = $oRegencia->getCodigo();
        }
        $sRegenciasTurma = implode(", ", $aRegencias);
        $sWhere .= " and ed115_regencia in ({$sRegenciasTurma})";
        
      }
     
      /**
       * @todo removi o filtro das etapas 
       */
      $sSqlProgressaoParcial = $oDaoProgressaoParcial->sql_query_aluno(null,
                                                                       "ed114_sequencial",
                                                                       " ed47_v_nome ",
                                                                       $sWhere
                                                                      );

      $rsProgressaoParcial = $oDaoProgressaoParcial->sql_record($sSqlProgressaoParcial);
      $iRegistros          = $oDaoProgressaoParcial->numrows;

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
   * Retorna o numero de vagas que a turma comporta
   * @return number Retorna o numero de vagas que a Turma comporta
   */
  public function getVagas() {
    return $this->iNumeroVagas;
  }

  /**
   * Retorna o numero de vagas ocupadas na turma
   * @return number Retorna o numero de vagas ocupadas na Turma
   */
  public function getVagasOcupadas() {
    return $this->iNumeroAlunosMatriculados;
  }

  /**
   * Retorna o numero de Vagas disponiveis da Turma
   * @return number Retorna o numero de Vagas disponiveis da Turma
   */
  public function getVagasDisponiveis () {

    return $this->iNumeroVagas - $this->getVagasOcupadas();
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
   * Retorna a carga horaria Total da turma
   * @return integer carga horaria total
   */
  public function getCargaHoraria() {

    $iCargaHoraria = 0;
    if ($this->iTipoCalculoAulas == Turma::CH_PERIODO) {
      foreach ($this->getDisciplinas() as $oRegencia) {
        $iCargaHoraria += $oRegencia->getTotalDeAulas();
      }
    } else {
      $iCargaHoraria = $this->getCalendario()->getDiasLetivos() * 4;
    }
    return $iCargaHoraria;
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
   * @param Etapa $oEtapa Etapa de encerramento
   * @return boolean retorna
   */
  public function encerradaNaEtapa(Etapa $oEtapa) {

    $oDaoRegencia = db_utils::getDao("regencia");
    $sWhere       = "ed59_i_turma = {$this->getCodigo()}";
    $sWhere      .= " and ed59_i_serie = {$oEtapa->getCodigo()}";
    $sWhere      .= " and ed59_c_encerrada = 'S' ";

    $sSqlRegenciaEncerrada = $oDaoRegencia->sql_query_file(null, "1", null, $sWhere);
    $rsRegenciaEncerrada   = $oDaoRegencia->sql_record($sSqlRegenciaEncerrada);
    if ($oDaoRegencia->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Verifica se a tuma está encerrada parcialmente.
   * @param Etapa $oEtapa Etapa para ser verificada
   * @return boolean
   */
  public function encerradaParcial(Etapa $oEtapa) {

    $iTotalDiariosEncerrados = 0;
    $oDaoDiario              = db_utils::getDao("diario");
    $sWhere                  = "ed59_i_turma = {$this->getCodigo()}";
    $sWhere                 .= " and ed59_i_serie = {$oEtapa->getCodigo()}";
    $sWhere                 .= " and ed59_i_serie = {$oEtapa->getCodigo()}";
    $sWhere                 .= " and ed60_c_situacao = 'MATRICULADO' ";
    $sWhere                 .= " and ed95_c_encerrado = 'S' ";
    $sSqlTurmaEncerradaParcialmente = $oDaoDiario->sql_query_regencia_aluno(null,
                                                                           "count(*) as encerrados",
                                                                           null,
                                                                           $sWhere
                                                                          );
    $rsTurmaEncerrada               = $oDaoDiario->sql_record($sSqlTurmaEncerradaParcialmente);

    if ($oDaoDiario->numrows > 0) {
      $iTotalDiariosEncerrados = db_utils::fieldsMemory($rsTurmaEncerrada, 0)->encerrados;
    }
    if ($iTotalDiariosEncerrados > 0 && !$this->encerradaNaEtapa($oEtapa)) {
      return true;
    }
    return false;
  }

  /**
   * Retorna uma instancia de Docente, referente ao regente conselheiro da turma
   * @return Docente
   */
  public function getProfessorConselheiro() {

    $oDaoRegenteConselho = db_utils::getDao("regenteconselho");
    $sCampos             = "ed235_i_rechumano";
    $sWhere              = "ed235_i_turma = {$this->iCodigo}";
    $sSqlRegenteConselho = $oDaoRegenteConselho->sql_query(null, $sCampos, null, $sWhere);
    $rsRegenteConselho   = $oDaoRegenteConselho->sql_record($sSqlRegenteConselho);

    if ($oDaoRegenteConselho->numrows > 0) {

      $iCodigoRecHumano = db_utils::fieldsMemory($rsRegenteConselho, 0)->ed235_i_rechumano;
      $oDocente         = DocenteRepository::getDocenteByCodigoRecursosHumano($iCodigoRecHumano);
      return $oDocente;
    }
    return false;
  }

  /**
   * Retorna uma instancia do turno adicional, caso exista
   * @return Turno
   */
  public function temTurnoAdicional() {

    $oDaoTurmaTurno   = db_utils::getDao("turmaturno");
    $sWhereTurmaTurno = "ed246_i_turma = {$this->getCodigo()}";
    $sSqlTurmaTurno   = $oDaoTurmaTurno->sql_query_file(null, "ed246_i_turno", null, $sWhereTurmaTurno);
    $rsTurmaTurno     = $oDaoTurmaTurno->sql_record($sSqlTurmaTurno);

    if ($oDaoTurmaTurno->numrows > 0) {

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

      $rsEduNumAlunoBloqueado = $oDaoEduNumAlunoBloqueado->sql_record($sSqlEduNumAlunoBloqueado);
      $iTotalLinhas           = $oDaoEduNumAlunoBloqueado->numrows;
      for ($iNumero = 0; $iNumero < $iTotalLinhas; $iNumero++) {
        $aNumerosBloqueados[] = db_utils::fieldsMemory($rsEduNumAlunoBloqueado, $iNumero)->ed289_i_numaluno;
      }
    }
    return $aNumerosBloqueados;
  }


  /**
   * Realiza a classificacao numerica da turma
   * @param boolean $lOrdenarPorClassificacaoDaTurma se deve retornar os alunos em ordem de classificação ou Alfabética
   * 
   */
  public function reclassificarNumeracaoDaTurma($lOrdenarPorClassificacaoDaTurma = true) {

    $aNumerosBloqueados = $this->getNumerosBloqueados();
    $aAlunos            = $this->getAlunosMatriculados($lOrdenarPorClassificacaoDaTurma);
    $iNumeracao         = 1;

    foreach ($aAlunos as $oMatricula) {

     while (in_array($iNumeracao, $aNumerosBloqueados)) {
        $iNumeracao++;
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

    $oDaoTurma                 = db_utils::getDao("turma");
    $oDaoTurma->ed57_i_nummatr = "{$this->getTotalAlunosMatriculados()}";

    if (!empty($this->iCodigo)) {

      $oDaoTurma->ed57_i_codigo = $this->getCodigo();
      $oDaoTurma->alterar($this->getCodigo());
    }

    if ($oDaoTurma->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados da turma.\nErro Técnico{$oDaoTurma->erro_msg}");
    }
  }

  /**
   *  Efetua a matrícula de um aluno em uma turma
   * @param Aluno  $oAluno Aluno a ser matriculado
   * @param Etapa  $oEtapa Etapa de destino
   * @param DBDate $oData data da matricula
   * @throws BusinessException
   */
  public function matricularAluno(Aluno $oAluno, Etapa $oEtapa, DBDate $oData) {

    $lEtapaDaTurma = false;
    foreach ($this->getEtapas() as $oEtapaTurma) {

      if ($oEtapaTurma->getEtapa()->getCodigo() == $oEtapa->getCodigo()) {
        $lEtapaDaTurma = true;
      }
    }

    if (!$lEtapaDaTurma) {
      throw new BusinessException(URL_MENSAGEM_TURMA."etapa_inexistente_na_turma");
    }

    $oMatricula = new Matricula();
    $oMatricula->setAluno($oAluno);
    $oMatricula->setTurma($this);
    $oMatricula->setSituacao("MATRICULADO");
    $oMatricula->setDataMatricula($oData);
    $oMatricula->matricular($oEtapa);
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
}
?>