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
 * Regencias da turma. Controla as regencias (disciplinas) que a turma possui
 * Dados da regencia da turma, onde possuimos as informacoes dos regentes,
 * quantidades de periodos dados na disciplina.
 *
 * @package educacao
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.19 $
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
   * Instancia uma Regencia
   * Caso informamos o parametro $iCodigo, a classe ira prencher os dados da regencia atraves do codigo
   * @param integer $iCodigo Codigo da regencia
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoRegencia   = db_utils::getDao('regencia');
      $sSqlRegencia   = $oDaoRegencia->sql_query_file($iCodigo);
      $rsRegencia     = $oDaoRegencia->sql_record($sSqlRegencia);
      $iTotalRegencia = $oDaoRegencia->numrows;

      if ($iTotalRegencia > 0) {

        $oDadosRegencia           = db_utils::fieldsMemory($rsRegencia, 0);
        $this->iCodigo            = $oDadosRegencia->ed59_i_codigo;
        $this->oDisciplina        = DisciplinaRepository::getDisciplinaByCodigo($oDadosRegencia->ed59_i_disciplina);
        $this->sFrequenciaGlobal  = $oDadosRegencia->ed59_c_freqglob;
        $this->iCodigoEtapa       = $oDadosRegencia->ed59_i_serie;
        $this->iCodigoTurma       = $oDadosRegencia->ed59_i_turma;
        $this->lEncerrada         = $oDadosRegencia->ed59_c_encerrada == 'S' ? true : false;
        $this->sUltimaAtualizacao = $oDadosRegencia->ed59_c_ultatualiz;
        $this->sCondicao          = $oDadosRegencia->ed59_c_condicao;
        $this->lObrigatorio       = $oDadosRegencia->ed59_c_condicao == 'OB' ? true : false;
        $this->lLancadaHistorico  = $oDadosRegencia->ed59_lancarhistorico == 't' ? true : false;
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

      $oDaoRegenciaPeriodo = db_utils::getDao("regenciaperiodo");
      $sSqlTotalFaltas     = $oDaoRegenciaPeriodo->sql_query(null,
                                                             "coalesce(sum(ed78_i_aulasdadas), 0) as total_aulas",
                                                             null,
                                                             "ed78_i_regencia={$this->iCodigo}"
                                                            );
      $rsTotalFaltas  = $oDaoRegenciaPeriodo->sql_record($sSqlTotalFaltas);
      $this->iTotalAulasDadas = db_utils::fieldsMemory($rsTotalFaltas, 0)->total_aulas;
    }
    return $this->iTotalAulasDadas;

  }

  /**
   * Retorna os docentes que lecionam a Disciplina na turma.
   * @return Docente Colecao de Docentes
   */
  public function getDocentes() {

    if (count($this->aDocentes) == 0 && $this->iCodigo != "") {

      $sWhere               = "ed58_i_regencia = {$this->getCodigo()} ";
      $sWhere              .= " and ed58_ativo is true";

      $oDaoRegenciaHorario = db_utils::getDao("regenciahorario");
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
    $this->oTurma = $oTurma;
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
   *
   * @todo refatorar (criar uma figura para professor)
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

    $oDaoRegencia = db_utils::getDao('regencia');
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
    $oDaoRegenciaPeriodo    = db_utils::getDao("regenciaperiodo");
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
   */
  public function adicionarAulasDadasNoPeriodo($iTotalAulas, PeriodoAvaliacao $oPeriodoAvaliacao) {

    $sWhereRegenciaPeriodo  = "     ed41_i_periodoavaliacao = {$oPeriodoAvaliacao->getCodigo()}";
    $sWhereRegenciaPeriodo .= " AND ed78_i_regencia = {$this->getCodigo()}";
    $oDaoRegenciaPeriodo    = db_utils::getDao("regenciaperiodo");
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
   */
  public function isLancadaNoHistorico() {
    return $this->lLancadaHistorico;
  }
}
?>