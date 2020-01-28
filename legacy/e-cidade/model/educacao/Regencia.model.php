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

define("MSG_REGENCIA", "educacao.escola.Regencia.");
/**
 * Regencias da turma. Controla as regencias (disciplinas) que a turma possui
 * Dados da regencia da turma, onde possuimos as informacoes dos regentes,
 * quantidades de periodos dados na disciplina.
 *
 * @package educacao
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.38 $
 */
class Regencia {

  private $iCodigo;

  /**
   * Disciplina da regencia
   * @var Disciplina
   */
  private $oDisciplina;

  /**
   * Docentes da Disciplina
   * @var Docente[]
   */
  private $aDocentes = array();

  /**
   * Forma de controle da frequencia
   * @var string
   */
  private $sFrequenciaGlobal;

  /**
   * Turma da Regencia
   * @var Turma
   */
  private $oTurma = null;

  /**
   * Etapa da Regencia
   * @var Etapa
   */
  private $oEtapa = null;

  /**
   * Total de aulas dadas no periodo;
   * @var integer
   */
  protected $iTotalAulasDadas;

  /**
   * Codigo da Turma
   * Utilizado para a instancia da turma, caso necessario
   * @var integer
   */
  private $iCodigoTurma = null;

  /**
   * Codigo da etapa
   * Utilizado para a instancia da etapa, caso solicitado
   * @var integer
   */
  private $iCodigoEtapa = null;

  /**
   * Regencia encerrada
   * @var boolean
   */
  private $lEncerrada = false;

  /**
   * Informa o periodo da ultima atualizacao para a regencia
   * @var string
   */
  private $sUltimaAtualizacao;

  /**
   * Informa a condicao da regencia (discilplina)
   * @var string
   */
  private $sCondicao;

  /**
   * Informa se a disciplina é obrigatória
   * @var bool
   */
  private $lObrigatorio;

  /**
   * Informa se disciplina é lançada no histórico
   * @var bool
   */
  private $lLancadaHistorico;

  /**
   * Ordem da disciplina
   * @var integer
   */
  private $iOrdem;

  /**
   * Se disciplina faz parte de uma Base Comum ou Diversificada
   * @var bool
   */
  private $lBaseComum;

  /**
   * Define se disciplina possui carácter reprobatorio
   * @var bool
   */
  private $lCaracterReprobatorio;

  /**
   * Número de horas/períodos da disciplina na turma
   * @var int
   */
  private $iHorasAula;

  /**
   * Data de atualização da regência
   * @var DBDate
   */
  private $oDataAtualizacao;

  /**
   * Procedimento de Avaliação que está vinculado com a Regencia
   * @var ProcedimentoAvaliacao
   */
  private $oProcedimentoAvaliacao = null;

  /**
   * Recebe o código do Procedimento de Avaliação
   * @var integer
   */
  private $iCodigoProcedimentoAvaliacao = null;


  /**
   * Instancia uma Regencia
   * Caso informamos o parametro $iCodigo, a classe ira prencher os dados da regencia atraves do codigo
   * @param integer $iCodigo Codigo da regencia
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoRegencia   = new cl_regencia;
      $sSqlRegencia   = $oDaoRegencia->sql_query_file($iCodigo);
      $rsRegencia     = $oDaoRegencia->sql_record($sSqlRegencia);
      $iTotalRegencia = $oDaoRegencia->numrows;

      if ($iTotalRegencia > 0) {

        $oDadosRegencia                     = db_utils::fieldsMemory($rsRegencia, 0);
        $this->iCodigo                      = $oDadosRegencia->ed59_i_codigo;
        $this->oDisciplina                  = DisciplinaRepository::getDisciplinaByCodigo($oDadosRegencia->ed59_i_disciplina);
        $this->sFrequenciaGlobal            = $oDadosRegencia->ed59_c_freqglob;
        $this->iCodigoEtapa                 = $oDadosRegencia->ed59_i_serie;
        $this->iCodigoTurma                 = $oDadosRegencia->ed59_i_turma;
        $this->lEncerrada                   = $oDadosRegencia->ed59_c_encerrada == 'S';
        $this->sUltimaAtualizacao           = $oDadosRegencia->ed59_c_ultatualiz;
        $this->sCondicao                    = $oDadosRegencia->ed59_c_condicao;
        $this->lObrigatorio                 = $oDadosRegencia->ed59_c_condicao == 'OB';
        $this->lLancadaHistorico            = $oDadosRegencia->ed59_lancarhistorico == 't';
        $this->iOrdem                       = $oDadosRegencia->ed59_i_ordenacao;
        $this->lBaseComum                   = $oDadosRegencia->ed59_basecomum == 't';
        $this->lCaracterReprobatorio        = $oDadosRegencia->ed59_caracterreprobatorio == 't';
        $this->iHorasAula                   = $oDadosRegencia->ed59_i_qtdperiodo;
        $this->oDataAtualizacao             = null;
        $this->iCodigoProcedimentoAvaliacao = $oDadosRegencia->ed59_procedimento;

        if (!empty($oDadosRegencia->ed59_d_dataatualiz)) {
          $this->oDataAtualizacao = new DBDate($oDadosRegencia->ed59_d_dataatualiz);
        }

        unset($oDadosRegencia);
      }
    }
  }

  /**
   * Defina a disciplina da Regencia
   * @param Disciplina $oDisciplina Instancia da disciplina
   */
  public function setDisciplina(Disciplina $oDisciplina) {
    $this->oDisciplina = $oDisciplina;
  }

  /**
   * Retorna a disciplina da regencia
   * @return Disciplina
   */
  public function getDisciplina() {
    return $this->oDisciplina;
  }

  /**
   * Retorna o codigo da regencia;
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a frequencia da disciplina
   * @return string
   */
  public function getFrequenciaGlobal() {
    return $this->sFrequenciaGlobal;
  }

  /**
   * Retorna o total de aulas dadas na regencia
   * @return integer
   */
  public function getTotalDeAulas() {

    if ($this->getFrequenciaGlobal() == "A") {
      return 0;
    }

    if ($this->iTotalAulasDadas == null)  {

      $oDaoRegenciaPeriodo = new cl_regenciaperiodo();
      $sSqlTotalFaltas     = $oDaoRegenciaPeriodo->sql_query(null,
                                                             "coalesce(sum(ed78_i_aulasdadas), 0) as total_aulas",
                                                             null,
                                                             "ed78_i_regencia={$this->iCodigo} and ed09_c_somach = 'S'"
                                                            );
      $rsTotalFaltas  = $oDaoRegenciaPeriodo->sql_record($sSqlTotalFaltas);
      $this->iTotalAulasDadas = db_utils::fieldsMemory($rsTotalFaltas, 0)->total_aulas;
    }

    return $this->iTotalAulasDadas;
  }

  /**
   * Retorna os docentes que lecionam a Disciplina na turma.
   * @return Docente[] Colecao de Docentes
   */
  public function getDocentes() {

    if (count($this->aDocentes) == 0 && $this->iCodigo != "") {

      $sWhere               = "ed58_i_regencia = {$this->getCodigo()} ";
      $sWhere              .= " and ed58_ativo is true";

      $oDaoRegenciaHorario = new cl_regenciahorario();
      $sSqlDocentes        = $oDaoRegenciaHorario->sql_query_file(null,
                                                                  "distinct ed58_i_rechumano",
                                                                  "ed58_i_rechumano",
                                                                  $sWhere
                                                                 );

      $rsRegentes = $oDaoRegenciaHorario->sql_record($sSqlDocentes);
      $aDocentes  = db_utils::getCollectionByRecord($rsRegentes);
      foreach ($aDocentes as $oDocente) {

        $oDocente = DocenteRepository::getDocenteByCodigoRecursosHumano($oDocente->ed58_i_rechumano);
        $this->aDocentes[$oDocente->getCodigoDocente()] =  $oDocente;
      }
    }

    return $this->aDocentes;
  }

  /**
   * Define a que etapa a regencia esta vinculada
   * @param Etapa $oEtapa
   */
  public function setEtapa(Etapa $oEtapa) {

    $this->oEtapa       = $oEtapa;
    $this->iCodigoEtapa = $oEtapa->getCodigo();
  }

  /**
   * Retorna a etapa em que a disciplina é lecionada
   * @return Etapa Etapa da Disciplina
   */
  public function getEtapa() {

    if (empty($this->oEtapa)) {
      $this->oEtapa  = EtapaRepository::getEtapaByCodigo($this->iCodigoEtapa);
    }
    return $this->oEtapa;
  }

  /**
   * Define a turma da Disciplina
   * Turma em que a regencia sera Lecionada
   * @param Turma $oTurma
   */
  public function setTurma(Turma $oTurma) {

    $this->oTurma       = $oTurma;
    $this->iCodigoTurma = $oTurma->getCodigo();
  }

  /**
   * Retorna a turma em que a Regencia sera lecionada
   * @return Turma Turma Da Regencia
   */
  public function getTurma() {

    if (empty($this->oTurma)) {
      $this->oTurma = TurmaRepository::getTurmaByCodigo($this->iCodigoTurma);
    }
    return $this->oTurma;
  }

  /**
   * Verifica se a regencia já foi encerrada.
   * Retorna true para a regencia que esta encerrada
   * @return boolean true para encerrada
   */
  public function isEncerrada () {
    return $this->lEncerrada;
  }

  /**
   * Retorna o Professor que esta presente em sala
   * @param integer $iPeriodoEscola
   * @param integer $iDiaLetivo
   * @return Ambigous <ProfessorVO, NULL>
   */
  public function getDocenteEmAula($iPeriodoEscola, $iDiaLetivo) {

  	$sSqlDocente  = "	select professor,                                                                             \n";
  	$sSqlDocente .= "	       case                                                                                   \n";
  	$sSqlDocente .= "	         when cgmrh.z01_numcgm is not null                                                    \n";
  	$sSqlDocente .= "	          then cgmrh.z01_numcgm                                                               \n";
  	$sSqlDocente .= "	          else cgmcgm.z01_numcgm                                                              \n";
  	$sSqlDocente .= "	       end as cgm                                                                             \n";
  	$sSqlDocente .= "	  from (select distinct                                                                       \n";
  	$sSqlDocente .= "                case                                                                           \n";
    $sSqlDocente .= "                  when docenteausencia.ed321_rechumano is not null                             \n";
    $sSqlDocente .= "                    then case                                                                  \n";
    $sSqlDocente .= "                           when docentesubstituto.ed322_rechumano is null                      \n";
    $sSqlDocente .= "                             then null                                                         \n";
    $sSqlDocente .= "                             else docentesubstituto.ed322_rechumano                            \n";
    $sSqlDocente .= "                         end                                                                   \n";
    $sSqlDocente .= "                  else regenciahorario.ed58_i_rechumano                                        \n";
    $sSqlDocente .= "                end as professor                                                               \n";
    $sSqlDocente .= "          from regenciahorario                                                                 \n";
    $sSqlDocente .= "          left join docenteausencia   on docenteausencia.ed321_rechumano        = regenciahorario.ed58_i_rechumano \n";
    $sSqlDocente .= "          left join docentesubstituto on docentesubstituto.ed322_docenteausente = docenteausencia.ed321_sequencial \n";
    $sSqlDocente .= "         where regenciahorario.ed58_i_regencia  = {$this->iCodigo}                             \n";
    $sSqlDocente .= "           and regenciahorario.ed58_i_diasemana = {$iDiaLetivo}                                \n";
    $sSqlDocente .= "           and regenciahorario.ed58_i_periodo   = {$iPeriodoEscola}) as x                      \n";

    /**
     * CGM da rhpessoal
     */
    $sSqlDocente .= "   left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = professor              \n";
    $sSqlDocente .= "   left join rhpessoal    on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal       \n";
    $sSqlDocente .= "   left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm                         \n";
    /**
     * CGM do RH
     */
    $sSqlDocente .= "   left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = professor                      \n";
    $sSqlDocente .= "   left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm                    \n";

    $oDaoRegencia = new cl_regencia();
    $rsDocente    = $oDaoRegencia->sql_record($sSqlDocente);

    $oMatriculaDocente = null;
    if ($oDaoRegencia->numrows > 0) {

      $oMatricula        = db_utils::fieldsMemory($rsDocente, 0);

      /**
       * Professor pode não ter substituto, por isso testamos se o retorno é null
       */
      if (!empty($oMatricula->professor)) {
        $oMatriculaDocente = new ProfessorVO($oMatricula->professor, $oMatricula->cgm);
      }
    }

    return $oMatriculaDocente;
  }

  /**
   * Retorna o total de aulas dadas em um periodo de avaliacao, da regencia
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @return integer
   */
  public function getTotalDeAulasNoPeriodo(PeriodoAvaliacao $oPeriodoAvaliacao) {

    if ($this->getFrequenciaGlobal() == "A") {
      return 0;
    }
    $iTotalAulas            = null;
    $sWhereRegenciaPeriodo  = "     ed41_i_periodoavaliacao = {$oPeriodoAvaliacao->getCodigo()}";
    $sWhereRegenciaPeriodo .= " AND ed78_i_regencia = {$this->getCodigo()}";
    $oDaoRegenciaPeriodo    = new cl_regenciaperiodo();
    $sSqlRegenciaPeriodo    = $oDaoRegenciaPeriodo->sql_query(null, 'ed78_i_aulasdadas', null, $sWhereRegenciaPeriodo);
    $rsRegenciaPeriodo      = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);

    if ($oDaoRegenciaPeriodo->numrows > 0) {
      $iTotalAulas = db_utils::fieldsMemory($rsRegenciaPeriodo, 0)->ed78_i_aulasdadas;
    }

    return $iTotalAulas;
  }

  /**
   * Adiciona quantidade de aulas dadas em um determinado periodo para a regencia
   * @param integer $iTotalAulas
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @throws BusinessException
   */
  public function adicionarAulasDadasNoPeriodo($iTotalAulas, PeriodoAvaliacao $oPeriodoAvaliacao) {

    $sWhereRegenciaPeriodo  = "     ed41_i_periodoavaliacao = {$oPeriodoAvaliacao->getCodigo()}";
    $sWhereRegenciaPeriodo .= " AND ed78_i_regencia = {$this->getCodigo()}";
    $oDaoRegenciaPeriodo    = new cl_regenciaperiodo();
    $sSqlRegenciaPeriodo    = $oDaoRegenciaPeriodo->sql_query_periodo_avaliacao(null, 'ed78_i_codigo', null, $sWhereRegenciaPeriodo);
    $rsRegenciaPeriodo      = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);

    $oProcAvaliacao      = $this->getTurma()->getProcedimentoDeAvaliacaoDaEtapa($this->getEtapa());
    $oAvaliacaoPeriodica = null;

    foreach ($oProcAvaliacao->getElementos() as $oElementoAvaliacao) {

    	if ($oElementoAvaliacao instanceof AvaliacaoPeriodica  &&
    			$oElementoAvaliacao->getPeriodoAvaliacao()->getCodigo() == $oPeriodoAvaliacao->getCodigo()) {

    		$oAvaliacaoPeriodica = $oElementoAvaliacao;
    	}
    }

    if (empty($oAvaliacaoPeriodica)) {
    	throw new BusinessException('Não foi possível encontrar o período de avaliação.');
    }

    $oRegenciaPeriodo = new RegenciaPeriodo();

    if ($oDaoRegenciaPeriodo->numrows > 0) {

    	$iCodigo          = db_utils::fieldsMemory($rsRegenciaPeriodo, 0)->ed78_i_codigo;
    	$oRegenciaPeriodo = new RegenciaPeriodo($iCodigo);
    }

    $oRegenciaPeriodo->setAulasDadas($iTotalAulas);
    $oRegenciaPeriodo->setRegencia($this);
    $oRegenciaPeriodo->setAvaliacaoPeriodica($oAvaliacaoPeriodica);
    $oRegenciaPeriodo->salvar();
  }

  /**
   * Retorna a ultima atualizacao da regencia
   * @return string
   */
  public function getUltimaAtualizacao() {
    return $this->sUltimaAtualizacao;
  }

  /**
   * Seta o ultimo periodo de atualizacao da regencia
   * @param string $sUltimaAtualizacao
   */
  public function setUltimaAtualizacao($sUltimaAtualizacao) {
    $this->sUltimaAtualizacao = $sUltimaAtualizacao;
  }

  /**
   * Retorna a condicao da regencia (disciplina)
   * @return string
   */
  public function getCondicao() {
    return $this->sCondicao;
  }

  /**
   * Seta a condicao da disciplina
   * @param string $sCondicao
   */
  public function setCondicao($sCondicao) {
    $this->sCondicao = $sCondicao;
  }

  /**
   * Retorna se discilplina é obrigatória
   * @return bool
   */
  public function isObrigatoria() {
    return $this->lObrigatorio;
  }

  /**
   * Retorna se disciplina é lançada no histórico
   * @return bool
   * @deprecated
   */
  public function isLancadaNoHistorico() {
    return $this->lLancadaHistorico;
  }

  /**
   * Define se deve lançar as informações da disciplina na documentação
   * @param $lLancaDocumentacao
   */
  public function setLancadaDocumentacao($lLancaDocumentacao = true) {
    $this->lLancadaHistorico = $lLancaDocumentacao;
  }

  /**
   * Retorna se disciplina é lançada na documentação
   * @return bool
   * @deprecated
   */
  public function isLancadaDocumentacao() {
    return $this->lLancadaHistorico;
  }

  /**
   * Verifica se Regência possui grade de horários configurada
   * @return boolean
   */
  public function temGradeHorario() {

    $oDaoRegenciaHorario = new cl_regenciahorario();
    $sSqlRegenciaHorario = $oDaoRegenciaHorario->sql_query_file("", "1", "", "ed58_i_regencia = {$this->iCodigo}");
    $rsRegenciaHorario   = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);

    if ( $oDaoRegenciaHorario->numrows > 0 ) {
      return true;
    }

    return false;
  }

  /**
   * Define se disciplina possui carácter reprobatorio
   * @param boolean $lCaracterReprobatorio
   */
  public function setCaracterReprobatorio ($lCaracterReprobatorio) {
    $this->lCaracterReprobatorio = $lCaracterReprobatorio;
  }

  /**
   * Se disciplina possui carácter reprobatorio
   * @return boolean
   */
  public function possuiCaracterReprobatorio () {
    return $this->lCaracterReprobatorio;
  }

  /**
   * Define se disciplina é de uma base comum ou de base diversificada
   * @param boolean $lBaseComum
   */
  public function setBaseComum ($lBaseComum) {
    $this->lBaseComum = $lBaseComum;
  }

  /**
   * Identifica se disciplina é de uma base comum ou de base diversificada
   * @return boolean
   */
  public function isBaseComum () {
    return $this->lBaseComum;
  }

  /**
   * Define a ordem da disciplina na turma
   * @param int $iOrdem
   */
  public function setOrdem ($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Retorna a ordem da disciplina na turma
   * @return int
   */
  public function getOrdem () {
    return $this->iOrdem;
  }

  /**
   * Define o número de horas-aula
   * @param int $iHorasAula
   */
  public function setHorasAula ($iHorasAula) {
    $this->iHorasAula = $iHorasAula;
  }

  /**
   * Retorno o número de horas-aula
   * @return int
   */
  public function getHorasAula () {
    return $this->iHorasAula;
  }

  /**
   * Valida se tem ao menos um aluno com a disciplina encerrada
   * @return bool
   */
  public function parcialmenteEncerrada() {

    $sWhere  = "     ed59_i_codigo    = {$this->iCodigo}";
    $sWhere .= " and ed60_c_situacao  = 'MATRICULADO' ";
    $sWhere .= " and ed95_c_encerrado = 'S' ";
    $sWhere .= " and ed60_i_turma     = {$this->getTurma()->getCodigo()}";

    $oDiario = new cl_diario();
    $sSql    = $oDiario->sql_query_diario_classe(null, '1', null, $sWhere);
    $rs      = db_query($sSql);

    if (pg_num_rows($rs) > 0) {
      return true;
    }
    return false;
  }

  /**
   * Responsável pela exclusão dos diários e dependências, vinculadas a regência
   * @throws DBException
   */
  public function excluirVinculoDiario() {

    $oDaoDiario = new cl_diario();
    $sSqlDiario = $oDaoDiario->sql_query_file(null, " ed95_i_codigo ", null, "ed95_i_regencia = {$this->iCodigo}");
    $rsDiario   = db_query($sSqlDiario);
    $oMsgErro   = new stdClass();

    /**
     * Exclui todos possíveis vinculos do diário
     */
    if ($rsDiario && pg_num_rows($rsDiario) > 0) {

      $oDaoAmparo                                  = new cl_amparo();
      $oDaoAprovConselho                           = new cl_aprovconselho();
      $oDaoDiarioAvaliacao                         = new cl_diarioavaliacao();
      $oDaoAbonoFalta                              = new cl_abonofalta();
      $oDaoParecerAval                             = new cl_pareceraval();
      $oDaoTransfAprov                             = new cl_transfaprov();   // ed251_i_diariodestino ou ed251_i_diarioorigem
      $oDaoDiarioFinal                             = new cl_diariofinal();
      $oDaoProgressaoParcialAlunoDiarioFinalOrigem = new cl_progressaoparcialalunodiariofinalorigem();
      $oDaoProgressaoParcialAlunoEncerradoDiario   = new cl_progressaoparcialalunoencerradodiario();
      $oDaoDiarioResultado                         = new cl_diarioresultado();
      $oDaoDiarioResultadoRecuperacao              = new cl_diarioresultadorecuperacao();
      $oDaoParecerResult                           = new cl_parecerresult();
      $oDaoDiarioRegraCalculo                      = new cl_diarioregracalculo();
      $oDaoDiarioAvaliacaoAlternativa              = new cl_diarioavaliacaoalternativa();

      $iLinhas = pg_num_rows($rsDiario);

      for ( $i = 0; $i < $iLinhas; $i++) {

        $iDiario = db_utils::fieldsMemory($rsDiario, $i)->ed95_i_codigo;

        // Exclui amparo
        $oDaoAmparo->excluir(null, "ed81_i_diario = {$iDiario}");
        if ($oDaoAmparo->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_amparos", $oMsgErro));
        }

        // Exclui aprovconselho
        $oDaoAprovConselho->excluir(null, "ed253_i_diario = {$iDiario}");
        if ($oDaoAprovConselho->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_aprovconselho", $oMsgErro));
        }

        $sSqlDiarioAvaliacao = $oDaoDiarioAvaliacao->sql_query_file(null, "ed72_i_codigo", null, " ed72_i_diario = {$iDiario}");

        //Exclui abonofalta
        $oDaoAbonoFalta->excluir(null, " ed80_i_diarioavaliacao in ({$sSqlDiarioAvaliacao})");
        if ($oDaoAbonoFalta->erro_status == 0 ) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_abonofalta", $oMsgErro));
        }

        //Exclui pareceraval
        $oDaoParecerAval->excluir(null, " ed93_i_diarioavaliacao in ({$sSqlDiarioAvaliacao})");
        if ($oDaoParecerAval->erro_status == 0 ) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_pareceraval", $oMsgErro));
        }

        //Exclui transfaprov
        $sWhere = "( ed251_i_diariodestino in ({$sSqlDiarioAvaliacao}) or ed251_i_diarioorigem in ({$sSqlDiarioAvaliacao}) )";
        $oDaoTransfAprov->excluir(null, $sWhere);
        if ($oDaoTransfAprov->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_transfaprov", $oMsgErro));
        }

        //Exclui diarioavaliacao
        $oDaoDiarioAvaliacao->excluir(null, "ed72_i_diario = {$iDiario}");
        if ($oDaoDiarioAvaliacao->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioavaliacao", $oMsgErro));
        }

        $sSqlDiarioFinal = $oDaoDiarioFinal->sql_query_file(null, "ed74_i_codigo", null, "ed74_i_diario = {$iDiario}");

        // Exclui progressaoparcialalunodiariofinalorigem
        $oDaoProgressaoParcialAlunoDiarioFinalOrigem->excluir(null, " ed107_diariofinal in ($sSqlDiarioFinal)");
        if ($oDaoProgressaoParcialAlunoDiarioFinalOrigem->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_progressaoparcialalunodiariofinalorigem", $oMsgErro));
        }

        //Exclui progressaoparcialalunoencerradodiario
        $oDaoProgressaoParcialAlunoEncerradoDiario->excluir(null, " ed151_diariofinal in ($sSqlDiarioFinal) ");
        if ($oDaoProgressaoParcialAlunoEncerradoDiario->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_progressaoparcialalunoencerradodiario", $oMsgErro));
        }

        //Exclui diariofinal
        $oDaoDiarioFinal->excluir(null, "ed74_i_diario = {$iDiario}" );
        if ($oDaoDiarioFinal->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diariofinal", $oMsgErro));
        }

        $sSqlDiarioResultado = $oDaoDiarioResultado->sql_query_file(null, "ed73_i_codigo", null, " ed73_i_diario = {$iDiario}");

        //Exclui diarioresultadorecuperacao
        $oDaoDiarioResultadoRecuperacao->excluir(null, " ed116_diarioresultado in ({$sSqlDiarioResultado})");
        if ($oDaoDiarioResultadoRecuperacao->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioresultadorecuperacao", $oMsgErro));
        }

        //Exclui parecerresult
        $oDaoParecerResult->excluir(null, "ed63_i_diarioresultado in ({$sSqlDiarioResultado})");
        if ($oDaoParecerResult->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_parecerresult", $oMsgErro));
        }

        //Exclui diarioresultado
        $oDaoDiarioResultado->excluir(null, " ed73_i_diario = {$iDiario}");
        if ($oDaoDiarioResultado->erro_status == 0) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioresultado", $oMsgErro));
        }

        //Exclui diarioregracalculo
        $oDaoDiarioRegraCalculo->excluir(null, "ed125_diario = {$iDiario}");
        if ( $oDaoDiarioRegraCalculo->erro_status == 0 ) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioregracalculo", $oMsgErro));
        }

        // Exclui diarioavaliacaoalternativa
        $oDaoDiarioAvaliacaoAlternativa->excluir(null, "ed136_diario = {$iDiario}");
        if ( $oDaoDiarioAvaliacaoAlternativa->erro_status == 0 ) {

          $oMsgErro->sMsgErro = pg_last_error();
          throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioavaliacaoalternativa", $oMsgErro));
        }
      }
    }

    //Exclui diario
    $oDaoDiario->excluir(null, "ed95_i_codigo in ({$sSqlDiario})");
    if ($oDaoDiario->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diario", $oMsgErro));
    }
  }

  /**
   * Remove a regência e seus vínculos
   * @return bool
   * @throws DBException
   */
  public function excluir() {

    $this->excluirVinculoDiario();
    $oMsgErro = new stdClass();

    /**
     * Remove todos vínculos da regência e a própria regência
     */
    $oDaoRecencia                            = new cl_regencia();
    $oDaoDocenteSubstituto                   = new cl_docentesubstituto();
    $oDaoProgressaoParcialAlunoTurmaRegencia = new cl_progressaoparcialalunoturmaregencia();
    $oDaoRegenciaHorario                     = new cl_regenciahorario();
    $oDaoDiarioClasseRegenciaHorario         = new cl_diarioclasseregenciahorario();
    $oDaoRegenciaHorarioHistorico            = new cl_regenciahorariohistorico();
    $oDaoRegenciaPeriodo                     = new cl_regenciaperiodo();
    $oDaoDiarioRegenciaAlunoFalta            = new cl_diarioclassealunofalta();

    $sSqlRegencia = $oDaoRecencia->sql_query_file(null, "ed59_i_codigo", null, "ed59_i_codigo = {$this->iCodigo}");

    //Exclui docentesubstituto
    $oDaoDocenteSubstituto->excluir(null, " ed322_regencia in ({$sSqlRegencia})");
    if ($oDaoDocenteSubstituto->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_docentesubstituto", $oMsgErro));
    }

    //Exclui progressaoparcialalunoturmaregencia
    $oDaoProgressaoParcialAlunoTurmaRegencia->excluir(null, " ed115_regencia in ({$sSqlRegencia})");
    if ($oDaoProgressaoParcialAlunoTurmaRegencia->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_progressaoparcialalunoturmaregencia", $oMsgErro));
    }

    //Exclui diarioclassealunofalta
    $sWhereRegenciaHorario        = "ed58_i_regencia in ({$sSqlRegencia})";
    $sSqlDiarioRegenciaAlunoFalta = $oDaoDiarioRegenciaAlunoFalta->sql_query_aluno_falta(null, " ed301_sequencial ", null, $sWhereRegenciaHorario);

    $oDaoDiarioRegenciaAlunoFalta->excluir(null, " ed301_sequencial in ({$sSqlDiarioRegenciaAlunoFalta}) ");
    if ( $oDaoDiarioRegenciaAlunoFalta->erro_status == 0 ) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioclassealunofalta", $oMsgErro));
    }

    $sSqlRegenciaHorario   = $oDaoRegenciaHorario->sql_query_file(null, "ed58_i_codigo", null, $sWhereRegenciaHorario);

    //Exclui diarioclasseregenciahorario
    $oDaoDiarioClasseRegenciaHorario->excluir(null, "ed302_regenciahorario in ({$sSqlRegenciaHorario})");
    if ($oDaoDiarioClasseRegenciaHorario->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioclasseregenciahorario", $oMsgErro));
    }

    //Exclui regenciahorariohistorico
    $oDaoRegenciaHorarioHistorico->excluir(null, "ed323_regencia in ({$sSqlRegencia})");
    if ($oDaoRegenciaHorarioHistorico->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_regenciahorariohistorico", $oMsgErro));
    }

    //Exclui regenciaperiodo
    $oDaoRegenciaPeriodo->excluir(null, " ed78_i_regencia in ({$sSqlRegencia})");
    if ($oDaoRegenciaPeriodo->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_regenciaperiodo", $oMsgErro));
    }

    //Exclui regenciahorario
    $oDaoRegenciaHorario->excluir(null, $sWhereRegenciaHorario);
    if ( $oDaoRegenciaHorario->erro_status == 0 ) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_regenciahorario", $oMsgErro));
    }

    //Exclui regencia
    $oDaoRecencia->excluir(null, "ed59_i_codigo = {$this->iCodigo}");
    if ($oDaoRecencia->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException ( _M(MSG_REGENCIA."erro_excluir_diarioresultado", $oMsgErro));
    }

    RegenciaRepository::removerRegencia($this);

    return true;
  }

  /**
   * Define o tipo de controle de frequencia
   * @param string $sControleFrequencia
   */
  public function setControleFrequencia($sControleFrequencia) {
    $this->sFrequenciaGlobal = $sControleFrequencia;
  }

  /**
   * Data de atualização
   * @param DBDate $oDataAtualizacao
   */
  public function setDataAtualizacao(DBDate $oDataAtualizacao) {
    $this->oDataAtualizacao = $oDataAtualizacao;
  }

  /**
   * Salva os dados referentes a Regência
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  public function salvar () {

    if (!db_utils::inTransaction()) {
      throw new BusinessException( _M(MSG_REGENCIA . "sem_transacao_ativa"));
    }

    $oDaoRegencia                            = new cl_regencia();
    $oDaoRegencia->ed59_i_turma              = $this->iCodigoTurma;
    $oDaoRegencia->ed59_i_disciplina         = $this->oDisciplina->getCodigoDisciplina();
    $oDaoRegencia->ed59_i_qtdperiodo         = $this->iHorasAula;
    $oDaoRegencia->ed59_c_condicao           = $this->sCondicao;
    $oDaoRegencia->ed59_c_freqglob           = $this->sFrequenciaGlobal;
    $oDaoRegencia->ed59_d_dataatualiz        = $this->oDataAtualizacao->getDate();
    $oDaoRegencia->ed59_i_ordenacao          = $this->iOrdem;
    $oDaoRegencia->ed59_i_serie              = $this->iCodigoEtapa;
    $oDaoRegencia->ed59_lancarhistorico      = $this->lLancadaHistorico     ? 'true' : 'false';
    $oDaoRegencia->ed59_caracterreprobatorio = $this->lCaracterReprobatorio ? 'true' : 'false';
    $oDaoRegencia->ed59_basecomum            = $this->lBaseComum            ? 'true' : 'false';
    $oDaoRegencia->ed59_procedimento         = $this->iCodigoProcedimentoAvaliacao;

    /**
     * Na inclusão e alteração este é o valor default para as propriedades abaixo, caso haja necessidade deve se criar
     * a propriedade na classe
     */
    $oDaoRegencia->ed59_c_ultatualiz = "SI";
    $oDaoRegencia->ed59_c_encerrada  = "N";

    if ( empty($this->iCodigo) ) {
      $oDaoRegencia->incluir(null);
    } else {

      $oDaoRegencia->ed59_i_codigo = $this->iCodigo;
      $oDaoRegencia->alterar($this->iCodigo);
    }

    $oMsgErro = new stdClass();
    if ($oDaoRegencia->erro_status == 0) {

      $oMsgErro->sMsgErro = pg_last_error();
      throw new DBException( _M(MSG_REGENCIA."erro_salvar", $oMsgErro));
    }

    return true;
  }

  /**
   * Retorna o total de horas aulas da Regência. Caso seja passado por parâmetro um período de avaliação, calcula
   * as horas somente do período informado
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   * @return integer
   * @throws DBException
   */
  public function getTotalHorasAula( PeriodoAvaliacao $oPeriodoAvaliacao = null ) {

    $oCalculoCargaHoraria = FormaCalculoCargaHorariaRepository::getByCalendario($this->getTurma()->getCalendario());

    if ( $oCalculoCargaHoraria instanceof FormaCalculoCargaHoraria && $oCalculoCargaHoraria->isCalcularDuracaoPeriodo()) {
      return $this->calcularDuracaoPeriodo( $oPeriodoAvaliacao );
    }

    if ($this->getTurma()->getFormaCalculoCargaHoraria() == Turma::CH_DIA_LETIVO ) {
      return $this->somaAulasDadas($oPeriodoAvaliacao) * 4;
    }

    return $this->somaAulasDadas($oPeriodoAvaliacao);

  }

  /**
   * Retorna o Procedimento de Avaliação
   * ou null quando não houver procedimento vinculado
   * @return ProcedimentoAvaliacao|null
   */
  public function getProcedimentoAvaliacao() {

    if ( !empty($this->iCodigoProcedimentoAvaliacao) ) {
      $this->oProcedimentoAvaliacao  = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo($this->iCodigoProcedimentoAvaliacao);
    }
    return $this->oProcedimentoAvaliacao;
  }

  /**
   * Define o procedimento de avaliação da Regência
   * @param ProcedimentoAvaliacao $oProcedimentoAvaliacao
   */
  public function setProcedimentoAvaliacao( ProcedimentoAvaliacao $oProcedimentoAvaliacao ) {

    $this->oProcedimentoAvaliacao       = $oProcedimentoAvaliacao;
    $this->iCodigoProcedimentoAvaliacao = $oProcedimentoAvaliacao->getCodigo();
  }

  /**
   * Compara se a Regência informada possui o mesmo Procedimento de Avaliação que esta Regência
   * @param  Regencia $oRegencia
   * @return boolean
   */
  public function possuiMesmoProcedimentoAvaliacao( Regencia $oRegencia ) {

    $lPossuiMesmoProcedimentoAvaliacao = false;

    if ( $this->getProcedimentoAvaliacao()->getCodigo() == $oRegencia->getProcedimentoAvaliacao()->getCodigo() ) {
      $lPossuiMesmoProcedimentoAvaliacao = true;
    }

    return $lPossuiMesmoProcedimentoAvaliacao;
  }

  /**
   * Calcula a carga horaria da turma com base no valor informado das Aulas dadas / Dias Letivo
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao se for para calcular apenas de um determinado período de avaliação
   * @return integer                             carga horária da
   */
  private function somaAulasDadas(PeriodoAvaliacao $oPeriodoAvaliacao = null) {

    if( !empty( $oPeriodoAvaliacao ) ) {
      return $this->getTotalDeAulasNoPeriodo( $oPeriodoAvaliacao );
    }
    return $this->getTotalDeAulas();

  }

  /**
   * Calcula a carga horária da disciplina com base na duração do período
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao se for para calcular apenas de um determinado período de avaliação
   * @return integer                             carga horária da
   */
  private function calcularDuracaoPeriodo( PeriodoAvaliacao $oPeriodoAvaliacao = null ) {

    $oDaoRegencia    = new cl_regencia();
    $sCamposRegencia = "ed17_duracao";
    $sWhereRegencia  = "ed59_i_codigo = {$this->iCodigo} limit 1";
    $sSqlRegencia    = $oDaoRegencia->sql_query_turma_turno( null, $sCamposRegencia, null, $sWhereRegencia );
    $rsRegencia      = db_query( $sSqlRegencia );

    if( !$rsRegencia ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( MSG_REGENCIA . 'erro_buscar_duracao_periodo', $oErro ) );
    }

    $iMinutos    = 0;
    $iHorasAulas = 0;

    if( pg_num_rows( $rsRegencia ) ) {

      $sDuracaoPeriodo = db_utils::fieldsMemory( $rsRegencia, 0 )->ed17_duracao;
      $aDuracao        = explode( ':', $sDuracaoPeriodo );

      $iMinutos += (int) $aDuracao[0] > 0 ? (int) $aDuracao[0] * 60 : 0;
      $iMinutos += (int) $aDuracao[1] > 0 ? (int) $aDuracao[1] : 0;
    }

    /**
     * Se for para calcular de um período especifico
     */
    if( !empty( $oPeriodoAvaliacao ) ) {
      return ( $this->getTotalDeAulasNoPeriodo( $oPeriodoAvaliacao ) * $iMinutos ) / 60;
    }

    return ( $this->getTotalDeAulas() * $iMinutos ) / 60;;
  }
}