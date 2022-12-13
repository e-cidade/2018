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
 * Docente que esta substituindo um docente ausente
 * @package educacao
 * @subpackage ausencia
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
final class DocenteSubstituto {

	/**
	 * Codigo sequencial
	 * @var integer
	 */
	private $iCodigo = null;
	
	/**
	 * Docente Ausente
	 * @var AusenciaDocente
	 */
	private $oAusenciaDocente;
	
	/**
	 * Professor substituto
	 * @var ProfessorVO
	 */
	private $oProfessorSubstituto;
	
	/**
	 * Regencia que esta sendo substituida
	 * @var Regencia
	 */
	private $oRegencia = null;
	
	/**
	 * Tipo de vinculo
	 *  1 - TEMPORARIO
	 *  2 - PERMANENTE
	 * @var integer
	 */
	private $iTipoVinculo;
	
	/**
	 * Inicio do período de substituicao
	 * @var DBDate
	 */
	private $oDtPeriodoInicial = null;

	/**
	 * Fim do periodo de substituicao
	 * @var DBDate
	 */
	private $oDtPeriodoFinal   = null;
	
	/**
	 * Usuario que lancou o registro
	 * @var UsuarioSistema
	 */
	private $oUsuario       = null;
	
	
	const TEMPORARIO = 1;
	const PERMANENTE = 2;
	
	
	public function __construct($iCodigo = null) {
		
		if (!empty($iCodigo)) {
			
			$oDaoSubstituto = db_utils::getDao('docentesubstituto');
			
			$sCampos  = " docentesubstituto.*,                   ";
			$sCampos .= " case                                   ";
      $sCampos .= "   when cgmrh.z01_numcgm is not null    ";
      $sCampos .= "    then cgmrh.z01_numcgm               ";
      $sCampos .= "    else cgmcgm.z01_numcgm              ";
      $sCampos .= " end as cgm                             ";
			
			$sSqlSubstituto = $oDaoSubstituto->sql_query_docente_cgm($iCodigo, $sCampos);
			$rsSubstituto   = $oDaoSubstituto->sql_record($sSqlSubstituto);
			
			if ($oDaoSubstituto->numrows == 1) {
				
				$oSubstituto = db_utils::fieldsMemory($rsSubstituto, 0);
				$this->iCodigo = $oSubstituto->ed322_sequencial;
				$this->oProfessorSubstituto = new ProfessorVO($oSubstituto->ed322_rechumano, $oSubstituto->cgm);
				$this->oRegencia    = RegenciaRepository::getRegenciaByCodigo($oSubstituto->ed322_regencia);
				$this->iTipoVinculo = $oSubstituto->ed322_tipovinculo;
				
				$this->oDtPeriodoInicial = null;
				$this->oDtPeriodoFinal   = null;
				if (!empty($oSubstituto->ed322_periodoinicial)) {
					$this->oDtPeriodoInicial = new DBDate($oSubstituto->ed322_periodoinicial);
				}
				
				if (!empty($oSubstituto->ed322_periodofinal)) {
					$this->oDtPeriodoFinal = new DBDate($oSubstituto->ed322_periodofinal);
				}
				
				$this->oAusenciaDocente = new AusenciaDocente($oSubstituto->ed322_docenteausente);
				
				$this->oUsuario = new UsuarioSistema($oSubstituto->ed322_usuario);
				
			}
		}
		
		return null;
	}
	
	/**
	 * Seta o Professor Substituto
	 * @param ProfessorVO $oProfessorVO
	 */
	public function setProfessorSubstituto(ProfessorVO $oProfessorVO) {
		
		$this->oProfessorSubstituto = $oProfessorVO;
	}
	
	/**
	 * Seta a Regencia
	 * @param Regencia $oRegencia
	 */
	public function setRegencia(Regencia $oRegencia) {
	
		$this->oRegencia = $oRegencia;
	}
	
	/**
	 * Tipo de vinculo
	 *  1 - TEMPORARIO
	 *  2 - PERMANENTE
	 * @param integer $iTipoVinculo
	 */
	public function setTipovinculo($iTipoVinculo = DocenteSubstituto::TEMPORARIO) {
	
		$this->iTipoVinculo = $iTipoVinculo;
	}
	
	/**
	 * Seta o periodo inicial
	 * @param DBDate $oDtPeriodoInicial
	 */
	public function setPeriodoInicial(DBDate $oDtPeriodoInicial) {
	
		$this->oDtPeriodoInicial = $oDtPeriodoInicial;
	}

	/**
	 * Seta o periodo final
	 * @param DBDate $oDtPeriodoFinal
	 */
	public function setPeriodoFinal(DBDate $oDtPeriodoFinal) {
	
		$this->oDtPeriodoFinal = $oDtPeriodoFinal;
	}
	
	/**
	 * Usuario que realizaou cadastro
	 * @param UsuarioSistema $oUsuario
	 */
	public function setUsuario(UsuarioSistema $oUsuario) {
	
		$this->oUsuario = $oUsuario;
	}

	/**
	 * Retorna o codigo sequencial
	 * @return number
	 */
	public function getCodigo () {
	
		return $this->iCodigo;
	}
	
	/**
	 * Retorna quem e o professor substituto
	 * @return ProfessorVO
	 */
	public function getProfessorSubstituto () {
	
		return $this->oProfessorSubstituto;
	}
	
	/**
	 * Retorna a regencia que esta sendo substituida
	 * @return Regencia
	 */
	public function getRegencia () {
	
		return $this->oRegencia;
	}
	
	/**
	 * Tipo de vinculo
	 *  1 - TEMPORARIO
	 *  2 - PERMANENTE
	 * @return number
	 */
	public function getTipoVinculo () {
		
		return $this->iTipoVinculo;
	}
	
	/**
	 * Retorna a data de inicio da substituicao
	 * @return DBDate
	 */
	public function getPeriodoInicial () {
		
		return $this->oDtPeriodoInicial;
	}
	
	/**
	 * Retorna o periodo final para substituicao
	 * @return NULL || DBDate
	 */
	public function getPeriodoFinal() {
	
		return $this->oDtPeriodoFinal;
	}
	
	/**
	 * Retorna o usuário que realizou cadastro
	 * @return UsuarioSistema
	 */
	public function getUsuario () {
	
		return $this->oUsuario;
	}

	
	/**
	 * Exclui o vinculo do substituto com o docente ausente
	 * @throws DBException
	 * @return boolean
	 */
	public function excluir() {
		
		if (!db_utils::inTransaction()) {
			throw new Exception("Não existe transação Ativa.");
		}
		
		if ($this->iTipoVinculo == 2) {
			
			$this->alteraDocentePermanente(true);
		}
		
		$oDaoSubstituto = db_utils::getDao('docentesubstituto');
		$oDaoSubstituto->ed322_sequencial = $this->iCodigo;
		$oDaoSubstituto->excluir($this->iCodigo);
		
		if ($oDaoSubstituto->erro_status == '0') {
			throw new DBException($oDaoSubstituto->erro_msg);
		}
		return true;
	}
	
	/**
	 * Salva / altera um docente substituto
	 * @param AusenciaDocente $oDocenteAusente
	 * @throws Exception
	 * @throws BusinessException
	 * @throws DBException
	 * @return boolean
	 */
	public function salvar(AusenciaDocente $oDocenteAusente) {
		
		if (!db_utils::inTransaction()) {
			throw new Exception("Não existe transação Ativa.");
		}
		 
		$oDaoSubstituto = db_utils::getDao('docentesubstituto');
		
		/**
		 * Temos que validar a inclusao/alteracao das substituicoes, nao deixando:
		 * -- Incluir dois substitutos para o mesmo intervalo
		 * -- Se ja houver um permanete cadastrado nao pode se incluir substituto (temporario ou permanente)
		 *
		 */
		
		$sCampos  = " docentesubstituto.*,                   ";
		$sCampos .= " case                                   ";
		$sCampos .= "   when cgmrh.z01_numcgm is not null    ";
		$sCampos .= "    then cgmrh.z01_numcgm               ";
		$sCampos .= "    else cgmcgm.z01_numcgm              ";
		$sCampos .= " end as cgm                             ";
			
		$sWhereValidacao  = "     ed322_regencia = {$this->getRegencia()->getCodigo()}   ";
		$sWhereValidacao .= " and ed322_docenteausente = {$oDocenteAusente->getCodigo()} ";
		
		$sSqlValidacoes = $oDaoSubstituto->sql_query_docente_cgm(null, $sCampos, null, $sWhereValidacao);
		$rsValidacoes   = $oDaoSubstituto->sql_record($sSqlValidacoes);
		$iRegistros     = $oDaoSubstituto->numrows;

		if ($oDocenteAusente->getDataFinal() != null) {

		  if ($this->getPeriodoInicial()->getTimeStamp() > $oDocenteAusente->getDataFinal()->getTimeStamp()) {
		    
		    $sMsg = "Data Inicial da substituição não pode ser maior que a data final do período de ausência.";
		    throw new BusinessException($sMsg);
		  }
		  if ($this->getPeriodoFinal() != null) {
		  
  		  if ($this->getPeriodoFinal()->getTimeStamp() > $oDocenteAusente->getDataFinal()->getTimeStamp()) {
  		    
  		    $sMsg = "Data final da substituição não pode ser maior que a data final do período de ausência.";
  		    throw new BusinessException($sMsg);
  		  }
		  }
		}
		/**
		 * Data Final do calendario.
		 * Utilizada caso em casos onde o periodo final não é informado
		 */
		$oDataFinalCalendario  = $this->getRegencia()->getTurma()->getCalendario()->getDataFinal();
		
		if ($rsValidacoes && $iRegistros > 0) {
			
			for ($i = 0; $i < $iRegistros; $i++) {
				
				$oDadosValida     = db_utils::fieldsMemory($rsValidacoes, $i);
				$oDadosSubstituto = new ProfessorVO($oDadosValida->ed322_rechumano, $oDadosValida->cgm);

				/**
				 * Não permitir alterar o tipo de substituicao
				 */
				if ($this->iCodigo == $oDadosValida->ed322_sequencial &&
						($oDadosValida->ed322_tipovinculo == 2 && $this->getTipoVinculo() == 1)) {
					
					$sMsg = "Não é possível alterar o tipo de substituição de permanente para temporário.";
					throw new BusinessException($sMsg);
				}

				/**
				 * Se estiver tratando do mesmo registro, so e necessario realizar a validacao do (tipo de substituicao)
				 */
				if ($this->iCodigo == $oDadosValida->ed322_sequencial) {
					continue;
				}
				
				/**
				 * Nao pode deixar incluir um outro substituto quando ja existe um permanente
				 */
				if ($oDadosValida->ed322_tipovinculo == 2 && empty($this->iCodigo)) {
					
					$sMsg  = "O docente: {$oDadosSubstituto->getProfessor()->getNome()} esta substituindo o docente: ";
					$sMsg .= " {$oDocenteAusente->getDocente()->getProfessor()->getNome()} como docente PERMANENTE.";
					$sMsg .= "\nNão é possível incluir um docente substituto para o docente ausente quando já houver vinculado ";
					$sMsg .= "um docente permanete.";
					
					throw new BusinessException($sMsg);
				}
				
				/**
				 * Validamos conflito nas datas para professores temporarios
				 */
				$oValidaDataInicio = new DBDate($oDadosValida->ed322_periodoinicial);
				
				/**
				 * Como a data final nao e obrigatoria, se nao for setado uma data final para a substituicao,
				 * utilizamos a data final do calendario da turma como parametro para validacao
				 */
				$oValidaDataFinalJaInclusa = $oDataFinalCalendario;
				
				if (!empty($oDadosValida->ed322_periodofinal)) {
					$oValidaDataFinalJaInclusa  = new DBDate($oDadosValida->ed322_periodofinal);
				}
				
				if ($this->getPeriodoInicial()->getTimeStamp() == $oValidaDataInicio->getTimeStamp()) {
					
					$sMsg  = "Período inicial não pode ser igual ao período já lançado para o regente: ";
					$sMsg .= " {$oDadosSubstituto->getProfessor()->getNome()} ";
							
					throw new BusinessException($sMsg);
				}
				
				if ($this->getTipoVinculo() == 1 &&
						DBDate::dataEstaNoIntervalo($this->getPeriodoInicial(), $oValidaDataInicio, $oValidaDataFinalJaInclusa)) {
					
					$sMsg  = "Período inicial : {$this->getPeriodoInicial()->getDate(DBDate::DATA_PTBR)} conflita com período ";
					$sMsg .= "inicial ou final lançado para o regene: {$oDadosSubstituto->getProfessor()->getNome()} ";
					$sMsg .= "\nObs: Se não foi informada uma data final para docente: {$oDadosSubstituto->getProfessor()->getNome()}";
					$sMsg .= " o sistema irá considerar a data final do calendário letivo. ";
					  
					throw new BusinessException($sMsg);
				}
				
				if ($this->getTipoVinculo() == 1 && !empty($this->oDtPeriodoFinal) &&
						DBDate::dataEstaNoIntervalo($this->getPeriodoFinal(), $oValidaDataInicio, $oValidaDataFinalJaInclusa)) {
						
					$sMsg  = "Período final : {$this->getPeriodoFinal()->getDate(DBDate::DATA_PTBR)} conflita com período ";
					$sMsg .= "inicial ou final lançado para o regente: {$oDadosSubstituto->getProfessor()->getNome()} ";
					$sMsg .= "\nObs: Se não foi informada uma data final para regente: {$oDadosSubstituto->getProfessor()->getNome()}";
					$sMsg .= " o sistema irá considerar a data final do calendário letivo. ";
					
					throw new BusinessException($sMsg);
				}
				
				/**
				 * Se o período final do professor que esta sendo incluso não for informado,
				 * para critério de validação, temos que assumir que a data final é a data do calendario;
				 *
				 * Neste bloco validamos:
				 * -- se o período inicial informado é menor que um período já existente.
				 * -- se a data do calendario esta entre um periodo ja cadastrado
				 *
				 */
				
				if ($this->getTipoVinculo() == 1 && empty($this->oDtPeriodoFinal) &&
						DBDate::dataEstaNoIntervalo($oDataFinalCalendario, $oValidaDataInicio, $oValidaDataFinalJaInclusa) &&
						$this->getPeriodoInicial()->getTimeStamp() < $oValidaDataInicio->getTimeStamp()) {
						
					$sMsg  = "Períodos informados conflitam com período inicial ou final ";
					$sMsg .= " lançado para o regente: {$oDadosSubstituto->getProfessor()->getNome()} ";
					$sMsg .= "\nObs: Se não foi informada uma data final para o regente";
					$sMsg .= " o sistema irá considerar a data final do calendário letivo. ";
						
					throw new BusinessException($sMsg);
				}
				
				/**
				 * Ao incluir um regente permanente, devemos validar se existe um substituto temporário onde a data final
				 * conflita com a data de inicio do professor permanente.
				 * Se sim, devemos alterar a data final do regente temporario para (data inicial do permanente - 1 dia)
				 */
				if ($this->getTipoVinculo() == 2 &&
						$this->getPeriodoInicial()->getTimeStamp() < $oValidaDataInicio->getTimeStamp()) {
					
					$sMsg  = "Período inicial: {$this->getPeriodoInicial()->getDate(DBDate::DATA_PTBR)} conflita com período ";
					$sMsg .= "inicial lançado para o regente: {$oDadosSubstituto->getProfessor()->getNome()} ";
					
					throw new BusinessException($sMsg);
				}
				
				if ($this->getTipoVinculo() == 2 &&
						$this->getPeriodoInicial()->getTimeStamp() > $oValidaDataInicio->getTimeStamp() &&
						$this->getPeriodoInicial()->getTimeStamp() <= $oValidaDataFinalJaInclusa->getTimeStamp()) {
					
					date_default_timezone_set('America/Sao_Paulo');
					
					$sDtFinalAlterada = date("Y-m-d",mktime(0, 0, 0, $this->getPeriodoInicial()->getMes(),
							                                    $this->getPeriodoInicial()->getDia() -1,
							                                    $this->getPeriodoInicial()->getAno()
							                                   )
						                      );
					$oAlteraDataFinal                     = db_utils::getDao('docentesubstituto');
					$oAlteraDataFinal->ed322_sequencial   = $oDadosValida->ed322_sequencial;
					$oAlteraDataFinal->ed322_periodofinal = $sDtFinalAlterada;
					$oAlteraDataFinal->alterar($oDadosValida->ed322_sequencial);
					
					if ($oDaoSubstituto->erro_status == '0') {
							
						$sMsg  = "Não foi possível alterar a data final do regente {$oDadosSubstituto->getProfessor()->getNome()}.\n ";
						$sMsg .= "{$oDaoSubstituto->erro_msg}";
						throw new DBException($sMsg);
					}
					
				}
				
				unset($oDadosSubstituto);
				unset($oDadosValida);
				unset($oValidaDataInicio);
				unset($oValidaDataFinalJaInclusa);
			}
		} // fim das validacoes

		
		/**
		 * Incluimos o regente substituto
		 */
		$oDaoSubstituto->ed322_docenteausente = $oDocenteAusente->getCodigo();
		$oDaoSubstituto->ed322_rechumano      = $this->getProfessorSubstituto()->getMatricula();
		$oDaoSubstituto->ed322_regencia       = $this->getRegencia()->getCodigo();
		$oDaoSubstituto->ed322_tipovinculo    = $this->getTipoVinculo();
		$oDaoSubstituto->ed322_periodoinicial = $this->getPeriodoInicial()->getDate(DBDate::DATA_EN);
		
		$oDaoSubstituto->ed322_periodofinal   = null;
		if (!empty($this->oDtPeriodoFinal)) {
			$oDaoSubstituto->ed322_periodofinal   = $this->getPeriodoFinal()->getDate(DBDate::DATA_EN);
		}
		$oDaoSubstituto->ed322_usuario        = $this->getUsuario()->getIdUsuario();

		if (!empty($this->iCodigo)) {
			
			$oDaoSubstituto->ed322_sequencial = $this->iCodigo;
			$oDaoSubstituto->alterar($this->iCodigo);
		} else {
			
			$oDaoSubstituto->incluir(null);
			$this->iCodigo = $oDaoSubstituto->ed322_sequencial;
		}
		
		if ($oDaoSubstituto->erro_status == '0') {
			
			$sMsg  = "Não foi possível vincular o regente Substituto.\n ";
			$sMsg .= "{$oDaoSubstituto->erro_msg}";
			throw new DBException($sMsg);
		}
		
		/**
		 * Se regente for permanente, alterar na regenciahorario
		 */
		if ($this->getTipoVinculo() == 2) {
			$this->alteraDocentePermanente();
		}
		return true;
		
	}

	/**
	 * Altera o professor na regencia horario se regente for um professor substituto.
	 * O Parametro $lRetornaHistorico controla se é para retornar o professor antigo como professor da regenciahorario
	 * ou se e para alterar pelo professor substituto
	 *
	 * @todo alem da regencia, será obrigatório passar o rechumano na hora de incluir
	 *
	 * @param booblean $lRetornaHistorico
	 * @throws DBException
	 * @return boolean
	 */
	private function alteraDocentePermanente($lRetornaHistorico = false) {
		
		$oDaoRegencia          = db_utils::getDao('regenciahorario');
		$oDaoRegenciaHistorico = db_utils::getDao('regenciahorariohistorico');

		$sWhere  = " ed58_i_regencia  = " . $this->getRegencia()->getCodigo();
		$sCampos = " regenciahorario.* ";
		
		if ($lRetornaHistorico) {
			
			$sCampos .= " ,ed323_sequencial,  ed323_docente ";
			$sWhere  .= " and ed58_i_rechumano = " . $this->oProfessorSubstituto->getMatricula();
			$sWhere  .= " and ed58_ativo is true";
			
			$sSqlRegenciaHorario = $oDaoRegencia->sql_query_regencia_horario(null, $sCampos, null, $sWhere);
			$rsRegenciaHorario   = $oDaoRegencia->sql_record($sSqlRegenciaHorario);
			$iRegistros          = $oDaoRegencia->numrows;
			
			if ($iRegistros > 0) {
				
				for ($i = 0; $i < $iRegistros; $i++) {
					
					$oDadosRegencia = db_utils::fieldsMemory($rsRegenciaHorario, $i);
					
					$oDaoRegencia->ed58_i_codigo    = $oDadosRegencia->ed58_i_codigo;
					$oDaoRegencia->ed58_i_rechumano = $oDadosRegencia->ed323_docente;
					$oDaoRegencia->alterar($oDaoRegencia->ed58_i_codigo);
					
					if ($oDaoRegencia->erro_status == '0') {
						
						throw new DBException($oDadosRegencia->erro_msg);
					}
					
					$oDaoRegenciaHistorico->ed323_sequencial = $oDadosRegencia->ed323_sequencial;
					$oDaoRegenciaHistorico->excluir($oDadosRegencia->ed323_sequencial);
					
					if ($oDaoRegenciaHistorico->erro_status == '0') {
						throw new DBException($oDaoRegenciaHistorico->erro_msg);
					}
				}
			}
		} else {
			
			$sSqlRegenciaHorario = $oDaoRegencia->sql_query_regencia_horario(null, $sCampos, null, $sWhere);
			$rsRegenciaHorario   = $oDaoRegencia->sql_record($sSqlRegenciaHorario);
			$iRegistros          = $oDaoRegencia->numrows;
			
			if ($iRegistros > 0) {
			
				for ($i = 0; $i < $iRegistros; $i++) {
					
					$oDadosRegencia = db_utils::fieldsMemory($rsRegenciaHorario, $i);

					$oDaoRegenciaHistorico->ed323_docente    = $oDadosRegencia->ed58_i_rechumano;
					$oDaoRegenciaHistorico->ed323_substituto = $this->oProfessorSubstituto->getMatricula();
					$oDaoRegenciaHistorico->ed323_regencia   = $oDadosRegencia->ed58_i_regencia;
					$oDaoRegenciaHistorico->ed323_periodo    = $oDadosRegencia->ed58_i_periodo;
					$oDaoRegenciaHistorico->ed323_diasemana  = $oDadosRegencia->ed58_i_diasemana;
					
					$oDaoRegenciaHistorico->incluir(null);
						
					if ($oDaoRegenciaHistorico->erro_status == '0') {
							
						throw new DBException($oDaoRegenciaHistorico->erro_msg);
					}
					
					$oDaoRegencia->ed58_i_codigo    = $oDadosRegencia->ed58_i_codigo;
					$oDaoRegencia->ed58_i_rechumano = $this->oProfessorSubstituto->getMatricula();
					$oDaoRegencia->alterar($oDaoRegencia->ed58_i_codigo);
						
					if ($oDaoRegencia->erro_status == '0') {
					
						throw new DBException($oDadosRegencia->erro_msg);
					}
				}
			}
			
		}
		
		return true;
	}
	
	
	public function removePeriodoFinal() {
		
		$this->oDtPeriodoFinal = null;
	}
	

	/**
	 * Retorna o Docente ausente
	 * @return AusenciaDocente
	 */
	public function getAusente() {

		return $this->oAusenciaDocente;
	}
}