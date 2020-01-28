<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("LancamentoAuxiliarBase.model.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");

/**
 * Model para executar os lan�amentos auxiliares de um inventario
 * @author Bruno Silva      <bruno.silva@dbseller.com.br>
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.8 $
 */
class LancamentoAuxiliarInventario extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

	/**
	 * Valor Total do Lancamento
	 * @var float
	 */
	private $nValorTotal;

	/**
	 * C�digo do Hist�rico
	 * @var integer
	 */
	private $iHistorico;

	/**
	 * Observa��o do Hist�rico
	 * @var string
	 */
	private $sObservacaoHistorico;

	/**
	 * Conta Cr�dito
	 * @var integer
	 */
	private $iContaCredito;

	/**
	 * Conta D�bito
	 * @var integer
	 */
	private $iContaDebito;

	/**
	 * C�digo da escrituracao do inventario - pk
	 * @var integer
	 */
	private $iEscrituraInventario;

	/**
	 * Codigo do inventario
	 * @var integer
	 */
	private $iCodigoInventario;

	/**
	 * Codigo do reduzido da conta no plano
	 * @var integer
	 */
	private $iReduzido;

	/**
	 * Ano da sessao
	 * @var integer
	 */
	private $iAnousu;

	/**
	 * Salvar vinculo do lancamento com inventario
	 * @throws BusinessException - erro ao incluir
	 */
	public function salvarVinculoInventario() {

		$oDaoConLanCamInventario                           = db_utils::getDao('conlancaminventario');
		$oDaoConLanCamInventario->c85_sequencial           = null;
		$oDaoConLanCamInventario->c85_codlan               = $this->iCodigoLancamento;
		$oDaoConLanCamInventario->c85_escriturainventario  = $this->iEscrituraInventario;
		$oDaoConLanCamInventario->c85_reduz                = $this->iReduzido;
		$oDaoConLanCamInventario->c85_anousu               = $this->iAnousu;
		$oDaoConLanCamInventario->incluir(null);

		if ($oDaoConLanCamInventario->erro_status == 0) {

			$sErroMsg  = "N�o foi poss�vel incluir o v�nculo com o invent�rio do lan�amento.\n\n";
			$sErroMsg .= "Erro T�cnico:{$oDaoConLanCamInventario->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamInventario);
	}

	/**
	 * Implementa m�todo da Interface
	 * Executa Lan�amento, efetuando v�nculo nas tabelas auxiliares da inscri��o
	 */
	public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

		parent::setCodigoLancamento($iCodigoLancamento);
		parent::setDataLancamento($dtLancamento);

		$this->salvarVinculoInventario();
		parent::salvarVinculoComplemento();
	}

	public function setCodigoInventario($iCodigoInventario) {
		$this->iCodigoInventario = $iCodigoInventario;
	}

	public function getCodigoInventario() {
		return $this->iCodigoInventario;
	}

	/**
	 * Define o codigo da escrituracao do inventario
	 * @param integer $iCodigo
	 */
	public function setCodigoEscrituraInventario($iCodigo) {
		$this->iEscrituraInventario = $iCodigo;
	}

	/**
	 * Retorna o codigo da escrituracao do inventario
	 */
	public function getCodigoEscrituraInventario() {
		return $this->iEscrituraInventario;
	}

	/**
	 * Retorna o codigo da conta debito
	 * @return integer
	 */
	public function getContaDebito() {
		return $this->iContaDebito;
	}

	/**
	 * Define a conta debito
	 * @param integer $iContaDebito
	 */
	public function setContaDebito($iContaDebito) {
		$this->iContaDebito = $iContaDebito;
	}

	/**
	 * Define a conta credito
	 * @param integer $iContaCredito
	 */
	public function setContaCredito($iContaCredito) {
		$this->iContaCredito = $iContaCredito;
	}

	/**
	 * Retorna o codigo da conta credito
	 * @return integer
	 */
	public function getContaCredito() {
		return $this->iContaCredito;
	}

	/**
	 * Implementa m�todo da Interface
	 * Retorna o valor total
	 * @return float
	 */
	public function getValorTotal() {
		return $this->nValorTotal;
	}

	/**
	 * Implementa m�todo da Interface
	 * Seta o valor total do evento
	 * @param float
	 */
	public function setValorTotal($nValorTotal){
		$this->nValorTotal = $nValorTotal;
	}

	/**
	 * Implementa m�todo da Interface
	 * Retorna o hist�rico da opera��o
	 * @return integer
	 */
	public function getHistorico() {
		return $this->iHistorico;
	}

	/**
	 * Implementa m�todo da Interface
	 * Seta o hist�rico da opera��o
	 * @param integer
	 */
	public function setHistorico($iHistorico) {
		$this->iHistorico = $iHistorico;
	}

	/**
	 * Implementa m�todo da Interface
	 * Retorna a observa��o do hist�rico da opera��o
	 * @return string
	 */
	public function getObservacaoHistorico() {
		return $this->sObservacaoHistorico;
	}

	/**
	 * Implementa m�todo da Interface
	 * Seta a observa��o do hist�rico da opera��o
	 * @param string
	 */
	public function setObservacaoHistorico($sObservacaoHistorico) {
		$this->sObservacaoHistorico = $sObservacaoHistorico;
	}


	/**
	 * Define a conta credito
	 * @param integer $iReduzido
	 */
	public function setReduzido($iReduzido) {
	  $this->iReduzido = $iReduzido;
	}

	/**
	 * Retorna o codigo do reduzido da conta no plano
	 * @return integer
	 */
	public function getReduzido() {
	  return $this->iReduzido;
	}

	/**
	 * Define ano do lancamento
	 * @param integer $iAnousu
	 */
	public function setAnousu($iAnousu) {
	  $this->iAnousu = $iAnousu;
	}

	/**
	 * Retorna o ano do lancamento
	 * @return integer
	 */
	public function getAnousu() {
	  return $this->iAnousu;
	}



}