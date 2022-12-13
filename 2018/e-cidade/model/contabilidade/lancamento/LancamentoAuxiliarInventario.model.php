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

require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");

/**
 * Model para executar os lançamentos auxiliares de um inventario
 * @author Bruno Silva      <bruno.silva@dbseller.com.br>
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @package contabilidade
 * @version $Revision: 1.11 $
 */
class LancamentoAuxiliarInventario extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

	/**
	 * Valor Total do Lancamento
	 * @var float
	 */
	private $nValorTotal;

	/**
	 * Código do Histórico
	 * @var integer
	 */
	private $iHistorico;

	/**
	 * Observação do Histórico
	 * @var string
	 */
	private $sObservacaoHistorico;

	/**
	 * Conta Crédito
	 * @var integer
	 */
	private $iContaCredito;

	/**
	 * Conta Débito
	 * @var integer
	 */
	private $iContaDebito;

	/**
	 * Código da escrituracao do inventario - pk
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

			$sErroMsg  = "Não foi possível incluir o vínculo com o inventário do lançamento.\n\n";
			$sErroMsg .= "Erro Técnico:{$oDaoConLanCamInventario->erro_msg}";
			throw new BusinessException($sErroMsg);
		}

		unset($oDaoConLanCamInventario);
	}

	/**
	 * Implementa método da Interface
	 * Executa Lançamento, efetuando vínculo nas tabelas auxiliares da inscrição
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
	 * Implementa método da Interface
	 * Retorna o valor total
	 * @return float
	 */
	public function getValorTotal() {
		return $this->nValorTotal;
	}

	/**
	 * Implementa método da Interface
	 * Seta o valor total do evento
	 * @param float
	 */
	public function setValorTotal($nValorTotal){
		$this->nValorTotal = $nValorTotal;
	}

	/**
	 * Implementa método da Interface
	 * Retorna o histórico da operação
	 * @return integer
	 */
	public function getHistorico() {
		return $this->iHistorico;
	}

	/**
	 * Implementa método da Interface
	 * Seta o histórico da operação
	 * @param integer
	 */
	public function setHistorico($iHistorico) {
		$this->iHistorico = $iHistorico;
	}

	/**
	 * Implementa método da Interface
	 * Retorna a observação do histórico da operação
	 * @return string
	 */
	public function getObservacaoHistorico() {
		return $this->sObservacaoHistorico;
	}

	/**
	 * Implementa método da Interface
	 * Seta a observação do histórico da operação
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