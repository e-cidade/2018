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
 * Classe abstrata com metodos bases para salvar os vinculos de lancamento
 *
 * @author Bruno Silva      <bruno.silva@dbseller.com.br>
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @revision Andrio Costa <andrio.costa@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.12 $
 */
abstract class LancamentoAuxiliarBase {

	/**
	 * Código do lancamento
	 * @var integer
	 */
	protected $iCodigoLancamento;

	/**
	 * Data de lancamento
	 * @var string
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
	 * Codigo da dotação
	 * @var integer
	 */
	protected  $iCodigoDotacao;

	/**
	 * Código da nota de liquidação
	 * @var integer
	 */
	protected $iCodigoNotaLiquidacao;

  /**
   * @var ContaCorrenteDetalhe
   */
  protected $oContaCorrenteDetalhe;

	/**
	 * Define o código do lancamento
	 * @param $integer $iCodigoLancamento
	 */
	protected function setCodigoLancamento($iCodigoLancamento) {
		$this->iCodigoLancamento = $iCodigoLancamento;
	}

	/**
	 * Define data de lancamento
	 * @param string $dtLancamento
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
	 *  Inlcuindo vínculo do lançamento com o Elemento [comlancamele]
	 */
	protected function salvarVinculoElemento() {

		$oDaoConLanCamEle = new cl_conlancamele();
		$oDaoConLanCamEle->c67_codlan = $this->iCodigoLancamento;
		$oDaoConLanCamEle->c67_codele = $this->iCodigoElemento;
		$oDaoConLanCamEle->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamEle->erro_status == 0) {

			$sErroMsg  = "Não foi possível incluir o vinculo com o elemento do lançamento.\n\n";
			$sErroMsg .= "Erro Técnico: {$oDaoConLanCamEle->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamEle);
	}

	/**
	 *  Incluindo vinculo do Lançamento com o Complemento [conlancamcompl]
	 */
	protected function salvarVinculoComplemento() {

		$oDaoConLanCamCompl = new cl_conlancamcompl();
		$oDaoConLanCamCompl->c72_codlan  = $this->iCodigoLancamento;
		$oDaoConLanCamCompl->c72_complem = $this->getObservacaoHistorico();
		$oDaoConLanCamCompl->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamCompl->erro_status == 0) {

			$sErroMsg  = "Não foi possível incluir o complemento do lançamento.\n\n";
			$sErroMsg .= "Erro Técnico: {$oDaoConLanCamCompl->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamCompl);
	}

	/**
	 *  Incluindo vinculo do Lançamento com o CGM Favorecido [conlancamcgm]
	 */
	protected function salvarVinculoCgm() {

		$oDaoConLanCamCGM = new cl_conlancamcgm();
		$oDaoConLanCamCGM->c76_codlan = $this->iCodigoLancamento;
		$oDaoConLanCamCGM->c76_numcgm = $this->iFavorecido;
		$oDaoConLanCamCGM->c76_data   = $this->dtLancamento;;
		$oDaoConLanCamCGM->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamCGM->erro_status == 0) {

			$sErroMsg  = "Não foi possível incluir o CGM do lançamento.\n\n";
			$sErroMsg .= "Erro Técnico : {$oDaoConLanCamCGM->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamCGM);
	}

	/**
	 *  Incluindo vinculo do Lançamento com Empenho [conlancamemp]
	 */
	protected function salvarVinculoEmpenho() {

		$oDaoConLanCamEmp = new cl_conlancamemp();
		$oDaoConLanCamEmp->c75_codlan = $this->iCodigoLancamento ;
		$oDaoConLanCamEmp->c75_numemp = $this->iNumeroEmpenho;
		$oDaoConLanCamEmp->c75_data   = $this->dtLancamento;;
		$oDaoConLanCamEmp->incluir($this->iCodigoLancamento);

		if ($oDaoConLanCamEmp->erro_status == 0) {

			$sErroMsg  = "Não foi possível vincular Lançamento e Empenho.\n\n";
			$sErroMsg .= "Erro Técnico : {$oDaoConLanCamEmp->erro_msg}";
			throw new BusinessException($sErroMsg);
		}
		unset($oDaoConLanCamEmp);
	}

	/**
	 *  Incluindo vinculo do Lançamento com Dotacao [conlancamdot]
	 */
	protected function salvarVinculoDotacao() {

	  $oEmpenhoFinanceiro = new EmpenhoFinanceiro($this->getNumeroEmpenho());
	  if ($oEmpenhoFinanceiro->getAno() == db_getsession('DB_anousu')) {

  		$oDaoConLanCamDot = new cl_conlancamdot();
  		$oDaoConLanCamDot->c73_codlan = $this->iCodigoLancamento ;
  		$oDaoConLanCamDot->c73_data   = $this->dtLancamento;;
  		$oDaoConLanCamDot->c73_anousu = db_getsession('DB_anousu');
  		$oDaoConLanCamDot->c73_coddot = $this->iCodigoDotacao;
  		$oDaoConLanCamDot->incluir($this->iCodigoLancamento);

  		if ($oDaoConLanCamDot->erro_status == 0) {

  			$sErroMsg  = "Não foi possível vincular Lançamento e Dotacao.\n\n";
  			$sErroMsg .= "Erro Técnico : {$oDaoConLanCamDot->erro_msg}";
  			throw new BusinessException($sErroMsg);
  		}

  		unset($oDaoConLanCamDot);
	  }
	  return true;
	}


	/**
	 * Vincula uma nota de liquidação ao lançamento
	 * @throws BusinessException
	 * @return boolean
	 */
	protected function salvarVinculoNotaDeLiquidacao() {

	  $oDaoConLancamNota = new cl_conlancamnota();
	  $oDaoConLancamNota->c66_codlan  = $this->iCodigoLancamento;
	  $oDaoConLancamNota->c66_codnota = $this->getCodigoNotaLiquidacao();
	  $oDaoConLancamNota->incluir($this->iCodigoLancamento, $this->getCodigoNotaLiquidacao());
	  if ($oDaoConLancamNota->erro_status == 0) {

	    $sErroMsg  = "Não foi possível incluir o vínculo da nota de liquidacao com o lançamento.\n\n";
	    $sErroMsg .= "Erro Técnico: {$oDaoConLancamNota->erro_msg}";
	    throw new BusinessException($sErroMsg);
	  }
	  return true;
	}

  /**
   * @param ContaCorrenteDetalhe $oContaCorrenteDetalhe
   */
  public function setContaCorrenteDetalhe(ContaCorrenteDetalhe $oContaCorrenteDetalhe) {
    $this->oContaCorrenteDetalhe = $oContaCorrenteDetalhe;
  }

  /**
   * @return ContaCorrenteDetalhe
   */
  public function getContaCorrenteDetalhe() {
    return $this->oContaCorrenteDetalhe;
  }
}