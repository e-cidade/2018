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
 * Classe abstrata com metodos bases para salvar os vinculos de lancamento
 *
 * @author Bruno Silva      <bruno.silva@dbseller.com.br>
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @revision Andrio Costa <andrio.costa@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.7 $
 */
abstract class LancamentoAuxiliarBase {

	/**
	 * C�digo do lancamento
	 * @var integer
	 */
	protected $iCodigoLancamento;

	/**
	 * Data de lancamento
	 * @var date
	 */
	protected $dtLancamento;

	/**
	 * Codigo do elemento
	 * @var integer
	 */
	protected  $iCodigoElemento;

	/**
	 * Complemento do Lancamento
	 * @var string
	 */
	protected  $sObservacao;

	/**
	 * Favorecido do Empenho
	 * @var integer
	 */
	protected $iFavorecido;

	/**
	 * Numero do Empenho
	 * @var integer
	 */
	protected $iNumeroEmpenho;

	/**
	 * Codigo da dota��o
	 * @var integer
	 */
	protected  $iCodigoDotacao;

	/**
	 * C�digo da nota de liquida��o
	 * @var integer
	 */
	protected $iCodigoNotaLiquidacao;

	/**
	 * Define o c�digo do lancamento
	 * @param $integer $iCodigoLancamento
	 */
	protected function setCodigoLancamento($iCodigoLancamento) {
		$this->iCodigoLancamento = $iCodigoLancamento;
	}

	/**
	 * Define data de lancamento
	 * @param date $dtLancamento
	 */
	protected  function setDataLancamento($dtLancamento) {
		$this->dtLancamento = $dtLancamento;
	}

	/**
	 * Seta o codigo do elemento
	 * @param integer $iCodigoElemento
	 */
	public function setCodigoElemento($iCodigoElemento) {
	  $this->iCodigoElemento = $iCodigoElemento;
	}

	/**
	 * Retorna o codigo do elemento
	 * @return integer
	 */
	public function getCodigoElemento() {
	  return $this->iCodigoElemento;
	}

	/**
	 * Complemento do Lancamento
	 * @see ILancamentoAuxiliar::setObservacaoHistorico()
	 */
	public function setObservacaoHistorico($sObservacaoHistorico) {
	  $this->sObservacao = $sObservacaoHistorico;
	}

	/**
	 * Complemento do Lancamento
	 * @see ILancamentoAuxiliar::getObservacaoHistorico()
	 */
	public function getObservacaoHistorico() {
	  return $this->sObservacao;
	}

	/**
	 * Seta o favorecido CGM
	 * @param integer $iFavorecido
	 */
	public function setFavorecido($iFavorecido) {
	  $this->iFavorecido = $iFavorecido;
	}

	/**
	 * Retorna o favorecido CGM
	 * @return integer
	 */
	public function getFavorecido() {
	  return $this->iFavorecido;
	}

	/**
	 * Seta o numero do empenho
	 * @param integer $iNumeroEmpenho
	 */
	public function setNumeroEmpenho($iNumeroEmpenho) {
	  $this->iNumeroEmpenho = $iNumeroEmpenho;
	}

	/**
	 * Retorna o numero do empenho
	 * @return integer
	 */
	public function getNumeroEmpenho() {
	  return $this->iNumeroEmpenho;
	}
	
	/**
	 * Retorna um empenho
	 * @return EmpenhoFinanceiro
	 */
	public function getEmpenho() {
		return new EmpenhoFinanceiro($this->iNumeroEmpenho);
	}

	/**
	 * Seta o codigo da dotacao
	 * @param integer $iCodigoDotacao
	 */
	public function setCodigoDotacao($iCodigoDotacao) {
	  $this->iCodigoDotacao = $iCodigoDotacao;
	}

	/**
	 * Retorna o codigo da dotacao
	 * @return integer
	 */
	public function getCodigoDotacao() {
	  return $this->iCodigoDotacao;
	}

	/**
	 * Seta o codigo da nota de liquidacao
	 * @param integer $iCodigoNotaLiquidacao
	 */
	public function setCodigoNotaLiquidacao($iCodigoNotaLiquidacao) {
	  $this->iCodigoNotaLiquidacao = $iCodigoNotaLiquidacao;
	}

	/**
	 * Retorna o codigo da nota de liquidacao
	 * @return integer
	 */
	public function getCodigoNotaLiquidacao() {
	  return $this->iCodigoNotaLiquidacao;
	}


	/**
	 *  Inlcuindo v�nculo do lan�amento com o Elemento [comlancamele]
	 */
	protected function salvarVinculoElemento() {

		$oDaoConLanCamEle = db_utils::getDao('conlancamele');
		$oDaoConLanCamEle->c67_codlan = $this->iCodigoLancamento;
		$oDaoConLanCamEle->c67_codele = $this->iCodigoElemento;
		$oDaoConLanCamEle->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamEle->erro_status == 0) {

			$sErroMsg  = "N�o foi poss�vel incluir o vinculo com o elemento do lan�amento.\n\n";
			$sErroMsg .= "Erro T�cnico: {$oDaoConLanCamEle->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamEle);
	}

	/**
	 *  Incluindo vinculo do Lan�amento com o Complemento [conlancamcompl]
	 */
	protected function salvarVinculoComplemento() {

		$oDaoConLanCamCompl = db_utils::getDao('conlancamcompl');
		$oDaoConLanCamCompl->c72_codlan  = $this->iCodigoLancamento;
		$oDaoConLanCamCompl->c72_complem = $this->getObservacaoHistorico();
		$oDaoConLanCamCompl->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamCompl->erro_status == 0) {

			$sErroMsg  = "N�o foi poss�vel incluir o complemento do lan�amento.\n\n";
			$sErroMsg .= "Erro T�cnico: {$oDaoConLanCamCompl->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamCompl);
	}

	/**
	 *  Incluindo vinculo do Lan�amento com o CGM Favorecido [conlancamcgm]
	 */
	protected function salvarVinculoCgm() {

		$oDaoConLanCamCGM = db_utils::getDao('conlancamcgm');
		$oDaoConLanCamCGM->c76_codlan = $this->iCodigoLancamento;
		$oDaoConLanCamCGM->c76_numcgm = $this->iFavorecido;
		$oDaoConLanCamCGM->c76_data   = $this->dtLancamento;;
		$oDaoConLanCamCGM->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamCGM->erro_status == 0) {

			$sErroMsg  = "N�o foi poss�vel incluir o CGM do lan�amento.\n\n";
			$sErroMsg .= "Erro T�cnico : {$oDaoConLanCamCGM->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamCGM);
	}

	/**
	 *  Incluindo vinculo do Lan�amento com Empenho [conlancamemp]
	 */
	protected function salvarVinculoEmpenho() {

		$oDaoConLanCamEmp = db_utils::getDao('conlancamemp');
		$oDaoConLanCamEmp->c75_codlan = $this->iCodigoLancamento ;
		$oDaoConLanCamEmp->c75_numemp = $this->iNumeroEmpenho;
		$oDaoConLanCamEmp->c75_data   = $this->dtLancamento;;
		$oDaoConLanCamEmp->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamEmp->erro_status == 0) {

			$sErroMsg  = "N�o foi poss�vel vincular Lan�amento e Empenho.\n\n";
			$sErroMsg .= "Erro T�cnico : {$oDaoConLanCamEmp->erro_msg}";
			throw new BusinessException($sErroMsg);
		}
		unset($oDaoConLanCamEmp);
	}

	/**
	 *  Incluindo vinculo do Lan�amento com Dotacao [conlancamdot]
	 */
	protected function salvarVinculoDotacao() {

		$oDaoConLanCamDot = db_utils::getDao('conlancamdot');
		$oDaoConLanCamDot->c73_codlan = $this->iCodigoLancamento ;
		$oDaoConLanCamDot->c73_data   = $this->dtLancamento;;
		$oDaoConLanCamDot->c73_anousu = db_getsession('DB_anousu');
		$oDaoConLanCamDot->c73_coddot = $this->iCodigoDotacao;
		$oDaoConLanCamDot->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamDot->erro_status == 0) {

			$sErroMsg  = "N�o foi poss�vel vincular Lan�amento e Dotacao.\n\n";
			$sErroMsg .= "Erro T�cnico : {$oDaoConLanCamDot->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamDot);
	}


	/**
	 * Vincula uma nota de liquida��o ao lan�amento
	 * @throws BusinessException
	 * @return boolean
	 */
	protected function salvarVinculoNotaDeLiquidacao() {

	  $oDaoConLancamNota = db_utils::getDao('conlancamnota');
	  $oDaoConLancamNota->c66_codlan  = $this->iCodigoLancamento;
	  $oDaoConLancamNota->c66_codnota = $this->getCodigoNotaLiquidacao();
	  $oDaoConLancamNota->incluir($this->iCodigoLancamento, $this->getCodigoNotaLiquidacao());
	  if ($oDaoConLancamNota->erro_status == 0) {

	    $sErroMsg  = "N�o foi poss�vel incluir o v�nculo da nota de liquidacao com o lan�amento.\n\n";
	    $sErroMsg .= "Erro T�cnico: {$oDaoConLancamNota->erro_msg}";
	    throw new BusinessException($sErroMsg);
	  }
	  return true;
	}

	/**
	 * metodo que ira retornar um lancamento auxiliar
	 * em cada lancamento auxiliar receber� como parametro o codlan
	 * @return object lancamentoauxiliar
	 */
	public static function getInstance(){
		return null;
	}
		
	
}