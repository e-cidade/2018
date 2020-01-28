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
 * Esta classe e responsavel para gerenciar as ausencias de um Docente / Professor / Regente
 *
 * @author Andrio Costa <andrio.costa>
 * @package escola
 * @subpackage ausencia
 */
class AusenciaDocente {

	/**
	 * Código da ausencia
	 * @var integer
	 */
	private $iCodigo;

	/**
	 * Docente ausente
	 * @var ProfessorVO
	 */
	private $oDocente;

	/**
	 * Tipo de ausencia
	 * @var TipoAusencia
	 */
	private $oTipoAusencia = null;

	/**
	 * Data de inicio da ausencia
	 * @var DBDate
	 */
	private $oDtInicio = null;

	/**
	 * Data Final da ausencia
	 * @var DBDate
	 */
	private $oDtFinal = null;

	/**
	 * Usuario que lancou a ausencia
	 * @var UsuarioSistema
	 */
	private $oUsuario = null;

	/**
	 * Observacao/motivo ausencia
	 * @var string
	 */
	private $sObservacao;

	/**
	 * Array com os docentes que o substituirao
	 * @var array
	 */
	private $aDocenteSubtituto = null;

	private $oEscola;


	public function __construct($iCodigo = null) {

		if (!empty($iCodigo))	{

		  $oDaoAusencia = db_utils::getDao('docenteausencia');

		  $sCampos  = " docenteausencia.*,                     ";
		  $sCampos .= " case                                   ";
		  $sCampos .= "   when cgmrh.z01_numcgm is not null    ";
		  $sCampos .= "    then cgmrh.z01_numcgm               ";
		  $sCampos .= "    else cgmcgm.z01_numcgm              ";
		  $sCampos .= " end as cgm                             ";

		  $sSqlAusencia = $oDaoAusencia->sql_query_docente_cgm($iCodigo, $sCampos);
		  $rsAusencia   = $oDaoAusencia->sql_record($sSqlAusencia);

		  if ($oDaoAusencia->numrows == 1) {

		  	$oAusencia = db_utils::fieldsMemory($rsAusencia, 0);

		  	$this->iCodigo           = $oAusencia->ed321_sequencial;
		  	$this->oDocente          = new ProfessorVO($oAusencia->ed321_rechumano, $oAusencia->cgm);
		  	$this->oTipoAusencia     = new TipoAusencia($oAusencia->ed321_tipoausencia);
		  	$this->oUsuario          = new UsuarioSistema($oAusencia->ed321_usuario);
		  	$this->sObservacao       = $oAusencia->ed321_observacao;
		  	$this->oDtInicio         = new DBDate($oAusencia->ed321_inicio);
		  	$this->oDtFinal          = null;
		  	$this->oEscola           = new Escola($oAusencia->ed321_escola);

		  	if (!empty($oAusencia->ed321_final)) {
		  	  $this->oDtFinal        = new DBDate($oAusencia->ed321_final);
		  	}

		  }
		}
		return null;
	}


	/**
	 * Seta o Professor que esta se ausentando
	 * @param ProfessorVO $oProfessor
	 */
	public function setDocente(ProfessorVO $oProfessor) {

		$this->oDocente = $oProfessor;
	}

	/**
	 * Seta o tipo de ausencia
	 * @param TipoAusencia $oTipoAusencia
	 */
	public function setTipoAusencia (TipoAusencia $oTipoAusencia) {

		$this->oTipoAusencia = $oTipoAusencia;
	}

	/**
	 *  Seta uma data para inicio da ausencia
	 * @param DBDate $oDtInicial
	 */
	public function setDataInicial (DBDate $oDtInicial) {

		$this->oDtInicio = $oDtInicial;
	}

	/**
	 * Seta uma data para retorno do docente ausente
	 * @param DBDate $oDtFinal
	 */
	public function setDataFinal (DBDate $oDtFinal = null) {

		$this->oDtFinal = $oDtFinal;
	}

	/**
	 * Seta o Usuario que registrou ausencia
	 * @param UsuarioSistema $oUsuario
	 */
	public function setUsuario (UsuarioSistema $oUsuario) {

		$this->oUsuario = $oUsuario;
	}

	/**
	 * Seta uma  Observacao lancada para ausencia
	 * @param string $sObservacao
	 */
	public function setObservacao ($sObservacao) {

		$this->sObservacao = $sObservacao;
	}

	/**
	 * Retorna o codigo da ausencia
	 * @return number
	 */
	public function getCodigo () {

		return $this->iCodigo;
	}

	/**
	 * Retorna o docente ausente
	 * @return ProfessorVO
	 */
	public function getDocente () {

		return $this->oDocente;
	}

	/**
	 * Retorna o tipo de ausencia
	 * @return TipoAusencia
	 */
	public function getTipoAusencia () {

		return $this->oTipoAusencia;
	}

	/**
	 * Retorna a data que o professor se ausentou
	 * @return DBDate
	 */
	public function getDataInicial () {

		return $this->oDtInicio;
	}

	/**
	 * Retorna a data prevista para o fim da ausencia
	 * @return DBDate
	 */
	public function getDataFinal () {

		return $this->oDtFinal;
	}

	/**
	 * Retorna o usuario que cadastrou a ausencia
	 * @return unknown
	 */
	public function getUsuario () {

		return $this->oUsuario;
	}

	/**
	 * Retorna a observacao da ausencia
	 * @return string
	 */
	public function getObservacao() {

		return $this->sObservacao;
	}

	/**
	 * Retorna um lista com os docentes substitutos
	 * @return multitype:DocenteSubtituto
	 */
	public function getDocentesSubstitutos() {

		if (count($this->aDocenteSubtituto) == 0) {

			$oDaoSubstituto = db_utils::getDao('docentesubstituto');
			$sWhere         = " ed322_docenteausente = {$this->iCodigo} ";
			$sOrder         = "ed322_regencia, ed322_periodoinicial, ed322_periodofinal";

			$sSqlSubstituto = $oDaoSubstituto->sql_query_docente_cgm(null, " ed322_sequencial ", $sOrder, $sWhere);
			$rsSubstituto   = $oDaoSubstituto->sql_record($sSqlSubstituto);
			$iRegistros     = $oDaoSubstituto->numrows;

			if ($iRegistros > 0) {

				for ($i = 0; $i < $iRegistros; $i++) {

					$iSubstituto = db_utils::fieldsMemory($rsSubstituto, $i)->ed322_sequencial;
					$this->aDocenteSubtituto[] = new DocenteSubstituto($iSubstituto);
				}
			}
		}

		return $this->aDocenteSubtituto;
	}


	/**
	 * Retorna um array com os docentes que estao substituindo o docente ausente em uma determinada regencia
	 * @param Regencia $oRegencia
	 * @return multitype:DocenteSubstituto
	 */
	public function getDocenteSubstitutoPorRegencia(Regencia $oRegencia) {

		$aDocentesPorRegencia = array();
		$aDocenteSubstituto   = $this->getDocentesSubstitutos();

		if (count($this->getDocentesSubstitutos()) > 0) {

			foreach ($aDocenteSubstituto as $oDocenteSubstituto) {

				if ($oDocenteSubstituto->getRegencia()->getCodigo() == $oRegencia->getCodigo()) {
					$aDocentesPorRegencia[] = $oDocenteSubstituto;
				}
			}
		}

		return $aDocentesPorRegencia;
	}


	/**
	 * Exclui Ausencia
	 * @throws DBException
	 * @return boolean
	 */
	public function excluir() {

		if (!db_utils::inTransaction()) {
			throw new Exception("Não existe transação Ativa.");
		}

		if (count($this->getDocentesSubstitutos()) > 0) {
			$this->excluiSubstitutos();
		}

		$oDaoAusente                   = db_utils::getDao('docenteausencia');
		$oDaoAusente->ed321_sequencial = $this->iCodigo;
		$oDaoAusente->excluir($this->iCodigo);

		if ($oDaoAusente->erro_status == '0') {
			throw new DBException($oDaoAusente->erro_msg);
		}
		return true;
	}

	/**
	 * Exclui professores substitutos
	 */
	protected function excluiSubstitutos() {

		foreach ($this->aDocenteSubtituto as $oDocenteSubstituto) {

			$oDocenteSubstituto->excluir();
		}
		return true;
	}

	/**
	 * Salva uma ausencia para o um professor
	 * @throws Exception
	 * @throws DBException
	 * @return boolean
	 */
	public function salvar() {

		if (!db_utils::inTransaction()) {
			throw new Exception("Não existe transação Ativa.");
		}

		if ($this->existeConflitoPeriodoDeAusencia()) {

			$sMsg  = "Já existe uma ausência lançada para o período informado ou que conflite com o período informado.";
			throw new BusinessException($sMsg);
		}

		$oDaoAusente = db_utils::getDao('docenteausencia');

		$oDaoAusente->ed321_rechumano    = $this->oDocente->getMatricula();
		$oDaoAusente->ed321_tipoausencia = $this->oTipoAusencia->getCodigo();
		$oDaoAusente->ed321_usuario      = $this->oUsuario->getIdUsuario();
		$oDaoAusente->ed321_inicio       = $this->oDtInicio->getDate(DBDate::DATA_EN);
		$oDaoAusente->ed321_final        = null;
		if (!empty($this->oDtFinal)) {
			$oDaoAusente->ed321_final = $this->oDtFinal->getDate(DBDate::DATA_EN);
		}

		/**
		 * setar a data final como nula..
		 */
		if ($oDaoAusente->ed321_final == '') {
		  $GLOBALS["HTTP_POST_VARS"]["ed321_final_dia"] = '';
		}
		$oDaoAusente->ed321_observacao = $this->sObservacao;
		$oDaoAusente->ed321_escola     = $this->oEscola->getCodigo();

		if (!empty($this->iCodigo)) {

			$oDaoAusente->ed321_sequencial = $this->iCodigo;
			$oDaoAusente->alterar($this->iCodigo);
		} else {

			$oDaoAusente->incluir(null);
			$this->iCodigo = $oDaoAusente->ed321_sequencial;
		}

		if ($oDaoAusente->erro_status == '0') {
			throw new DBException($oDaoAusente->erro_msg);
		}

		return true;
	}

	/**
	 * Vincula um docente substituto para uma regencia do professor que esta se ausentando
	 * @param DocenteSubstituto $oDocenteSubstituto
	 * @return true
	 */
	public function vincularSubstituto(DocenteSubstituto $oDocenteSubstituto) {

		$oDocenteSubstituto->salvar($this);
		return true;
	}

	public function desvincularSubstituto(DocenteSubstituto $oDocenteSubstituto) {

		$oDocenteSubstituto->excluir($this);
		return true;
	}

	/**
	 * Valida se o Docente leciona a regencia recebida pelo parametro
	 * @param Regencia $oRegencia
	 * @return boolean
	 */
	public function lecionaRegencia(Regencia $oRegencia) {

		$oDaoRegenciaHorario = db_utils::getDao('regenciahorario');

		$sWhere      = "     ed58_i_regencia  = {$oRegencia->getCodigo()} ";
		$sWhere     .= " and ed58_i_rechumano = {$this->getDocente()->getMatricula()} ";
		$sSqlLeciona = $oDaoRegenciaHorario->sql_query_file(null, '1', null, $sWhere);
		$rsLeciona   = $oDaoRegenciaHorario->sql_record($sSqlLeciona);

		if ($oDaoRegenciaHorario->numrows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Retorna um array com todas as disciplinas lecionadas na turma
	 * @param Turma $oTurma
	 */
	public function getDisciplinasLecionadaTurma(Turma $oTurma) {

		$aRegencias          = array();
		$oDaoRegenciaHorario = db_utils::getDao('regenciahorario');

		$sWhere        = " ed59_i_turma         = {$oTurma->getCodigo()} ";
		$sWhere       .= " and ed58_i_rechumano = {$this->getDocente()->getMatricula()} ";
		$sSqlRegencias = $oDaoRegenciaHorario->sql_query_regencia_dia_semana(null, "distinct ed58_i_regencia", null, $sWhere);
		$rsRegencias   = $oDaoRegenciaHorario->sql_record($sSqlRegencias);
		$iRegistros    = $oDaoRegenciaHorario->numrows;

		for ($i = 0; $i < $iRegistros; $i++) {

			$iCodigoRegencia = db_utils::fieldsMemory($rsRegencias, $i)->ed58_i_regencia;
			$aRegencias[]    = RegenciaRepository::getRegenciaByCodigo($iCodigoRegencia);
		}
		return $aRegencias;
	}

	/**
	 * Especifica a escola em que o professor esta ausente;
	 * @param Escola $oEscola
	 */
	public function setEscola(Escola $oEscola) {

		$this->oEscola = $oEscola;
	}

	/**
	 * Retorna a escola que em que o professor esta ausente
	 * @return Escola
	 */
	public function getEscola() {

		return $this->oEscola;
	}

	/**
	 * Valida se a data que esta sendo inserida, conflita com uma data de outra ausencia já lançada
	 * @return boolean
	 */
	private function existeConflitoPeriodoDeAusencia() {

		$oDaoAusencia = db_utils::getDao("docenteausencia");
		$sWhere       = "     ed321_rechumano = {$this->getDocente()->getMatricula()} ";
		$sWhere      .= " and ed321_escola    = {$this->oEscola->getCodigo()} ";
		$sWhere      .= " and extract(year FROM ed321_inicio) = {$this->getDataInicial()->getAno()} ";

		if (!empty($this->iCodigo)) {
			$sWhere .= " and  ed321_sequencial <> {$this->iCodigo} ";
		}

		$sSqlOutrasAusencias = $oDaoAusencia->sql_query_file(null, "ed321_sequencial", null, $sWhere);
		$rsOutrasAusencias   = $oDaoAusencia->sql_record($sSqlOutrasAusencias);
		$iRegistros          = $oDaoAusencia->numrows;

		if ($iRegistros == 0) {
			return false;
		}

		for($i = 0; $i < $iRegistros; $i++) {

			$iOutraAusencia = db_utils::fieldsMemory($rsOutrasAusencias, $i)->ed321_sequencial;
			$oOutraAusencia = new AusenciaDocente($iOutraAusencia);

			$oDtFinalOutraAusencia = new DBDate("31/12/{$this->getDataInicial()->getAno()}");
			if ($oOutraAusencia->getDataFinal() instanceof DBDate) {
				$oDtFinalOutraAusencia = $oOutraAusencia->getDataFinal();
			}

			if (
					DBDate::dataEstaNoIntervalo($this->oDtInicio, $oOutraAusencia->getDataInicial(), $oDtFinalOutraAusencia)) {
				return true;
			}

			if (!empty($this->oDtFinal) &&
					DBDate::dataEstaNoIntervalo($this->oDtFinal, $oOutraAusencia->getDataInicial(), $oDtFinalOutraAusencia)) {
				return true;
			}

			if (empty($this->oDtFinal) &&
					($this->oDtInicio->getTimeStamp() < $oOutraAusencia->getDataInicial()->getTimeStamp())) {
				return true;
			}

			if (!empty($this->oDtFinal) &&
					$this->oDtFinal->getTimeStamp() > $oOutraAusencia->getDataFinal()->getTimeStamp() &&
					$this->oDtInicio->getTimeStamp() < $oOutraAusencia->getDataInicial()->getTimeStamp() ) {

				return true;
			}

			unset($oOutraAusencia);
		}
		return false;
	}
}