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
 
require_once "interfaces/ILancamentoAuxiliar.interface.php";
require_once "model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php";

/**
 * Classe que implementa os mйtodos necessarios para generalizar um lanзamento auxiliar
 * Utilizada no reprocessamento de lanзamentos contбbeis
 * @author Bruno Silva
 * @version $Revision: 1.4 $
 */
class LancamentoAuxiliarContaCorrente extends LancamentoAuxiliarBase implements ILancamentoAuxiliar{

	/**
	 * Sequencial da conta corrente (c19_contacorrente - contacorrentedetalhe)
	 * @var integer $iContaCorrente
	 */
	private $iContaCorrente;
	
	/**
	 * Cуdigo da receita
	 * @var integer $iTipoReceita
	 */
	private $iTipoReceita;

	/**
	 * Cуdigo da instituiзгo (db_config)
	 * @var integer $iInstituicao
	 */
	private $iInstituicao;

	/**
	 * Caracterнstica Peculiar
	 * @var String $sCaracteristicaPeculiar
	 */
	private $sCaracteristicaPeculiar;

	/**
	 * Cуdigo da conta bancбria
	 * @var integer $iContaBancaria
	 */
	private $iContaBancaria;
	 
	/**
	 * Cуdigo reduzido da conta bancбria
	 * @var integer $iReduzidoContaBancaria
	 */
	private $iReduzidoContaBancaria;
	 

	/**
	 * Cуdigo do CGM
	 * @var integer $iCgm
	 */
	private $iCgm;

	/**
	 * Cуdigo do recurso
	 * @var integer $iCodigoRecurso
	 */
	private $iCodigoRecurso;

	/**
	 * Ano da Unidade
	 * @var integer $iUnidadeAnousu
	 */
	private $iUnidadeAnousu;

	/**
	 * Unidade do уrgгo
	 * @var integer $iUnidadeDoOrgao
	 */
	private $iUnidadeDoOrgao;

	/**
	 * Cуdigo da unidade
	 * @var integer $iUnidade
	 */
	private $iUnidade;

	/**
   * Ano do уrgгo
   * @var integer $iOrgaoAnousu
	 */
	private $iOrgaoAnousu;

	/**
	 * Cуdigo do уrgгo
	 * @var integer $iOrgao
	 */
	private $iOrgao;

	/**
	 * Propriedades Obrigatorias da Interface
	 */

	/**
	 * Cуdigo do histуrico
	 * @var integer $iCodigoHistorico
	 */
	private $iCodigoHistorico;
	/**
	 * Valor total
	 * @var numeric $nValorTotal
	 */
	private $nValorTotal;

	/**
	 * sequencial do acordo
	 */
	private $iAcordo;

	/**
	 * Construtor da classe
	 * A classe й contruнda a partir de um dos parвmetros passados para o construtor
	 * 1) a partir de um cуdigo de lanзamento:
	 * 	- buscando todos atributos necessбrios para o preenchimento do objeto
	 * 2) a partir do cуdigo do detalhamento do lanзamento
	 * 	- busca na tabela mapeada pela classe, e reconstroi o objeto
	 *
	 * @param int $iCodigoLancamento
	 * @throws Exception
	 */
	public function __construct($iCodigoLancamento) {

		$this->iContaCorrente    = null;
		$this->iInstituicao      = db_getsession("DB_instit");
		$this->iCodigoLancamento = $iCodigoLancamento;
			
		/**
		 * propriedades setadas durante reconstruзгo do lanзamento
		 */
		$this->iContaBancaria         = null;
		$this->iReduzidoContaBancaria = null;
			
		/**
		 *	Busca dados referente ao cgm, empenho e caracteristica peculiar
		 */
		$oDAOConlancam	= db_utils::getDao("conlancam");
		$sWhere					= "c70_codlan = {$this->iCodigoLancamento}";
		$sSQLConlancam	= $oDAOConlancam->sql_query_empenho_cgm(null, "*", null, $sWhere);
		$rsConlancam		= $oDAOConlancam->sql_record($sSQLConlancam);
			
		if ($oDAOConlancam->numrows > 0) {

			$oConlancam							       = db_utils::fieldsMemory($rsConlancam, 0);
			$this->iCgm 					         = $oConlancam->c76_numcgm;
			$this->iNumeroEmpenho          = $oConlancam->e60_numemp;
			$this->sCaracteristicaPeculiar = $oConlancam->c08_concarpeculiar;

			/**
			 * Caso nгo vir caracterнstica peculiar, pegar a mesma do registro do empenho
			 */
			if (empty($this->sCaracteristicaPeculiar)) {
				$this->sCaracteristicaPeculiar = $oConlancam->e60_concarpeculiar;
			}
		}
			

		/**
		 *  Busca dados referente a unidade e orgгo
		 */
		$oDAOConlancamdot	= db_utils::getDao("conlancamdot");
		$sSQLConlancamdot	= $oDAOConlancamdot->sql_query(null, "*", null, $sWhere);
		$rsConlancamdot		= $oDAOConlancamdot->sql_record($sSQLConlancamdot);
			
		if ($oDAOConlancamdot->numrows > 0) {

			$oConlancamdotorcunidade = db_utils::fieldsMemory($rsConlancamdot, 0);
			$this->iUnidadeAnousu    = $oConlancamdotorcunidade->o41_anousu;
			$this->iUnidadeDoOrgao   = $oConlancamdotorcunidade->o41_orgao;
			$this->iUnidade          = $oConlancamdotorcunidade->o41_unidade;
			$this->iOrgaoAnousu      = $oConlancamdotorcunidade->o40_anousu;
			$this->iOrgao            = $oConlancamdotorcunidade->o40_orgao;
		}
			
		$oDAOConlancamgrupocorrente	= db_utils::getDao("conlancamcorgrupocorrente");
		$sWhere											= "c70_codlan = {$this->iCodigoLancamento}";
		$sSQLConlancamgrupocorrente	= $oDAOConlancamgrupocorrente->sql_query_recurso(null, "*", null, $sWhere);
		$rsConlancamgrupocorrente   = $oDAOConlancamgrupocorrente->sql_record($sSQLConlancamgrupocorrente);
			
		$this->iCodigoRecurso		= null;
		if($oDAOConlancamgrupocorrente->numrows > 0) {
			$this->iCodigoRecurso = db_utils::fieldsMemory($rsConlancamgrupocorrente, 0)->k00_recurso;
		}

		/**
		 * Se for empenho, buscar o nъmero do acordo (contrato)
		 */
		if (!empty($this->iNumeroEmpenho)) {

			$oDAOEmpempenhocontrato	= db_utils::getDao("empempenhocontrato");
			$sWhere									= "e100_numemp = {$this->iNumeroEmpenho}";
			$sSQLEmpempenhocontrato	= $oDAOEmpempenhocontrato->sql_query_file(null, "*", null, $sWhere);
			$rsEmpempenhocontrato   = $oDAOEmpempenhocontrato->sql_record($sSQLEmpempenhocontrato);

			if($oDAOEmpempenhocontrato->numrows > 0) {
				$this->iAcordo = db_utils::fieldsMemory($rsEmpempenhocontrato, 0)->e100_acordo;
			}

		}
	}


	/**
	 * Retorna o Cуdigo do caracteristica peculiar
	 * @return integer
	 */
	public function getCaracteristicaPeculiar() {
		return $this->sCaracteristicaPeculiar;
	}

	/**
	 * Retorna o Nъmero do Empenho
	 * @return integer
	 */
	public function getNumeroEmpenho() {
		return $this->iNumeroEmpenho;
	}

	/**
	 * Retorna o Cуdigo do Recurso
	 * @return integer
	 */
	public function getCodigoRecurso() {
		return $this->iCodigoRecurso;
	}

	/**
	 * Retorna o Cуdigo do Acordo
	 * @return integer
	 */
	public function getAcordo() {
		return $this->iAcordo;
	}

	/**
	 * Retorna o Cуdigo do Favorecido
	 * @return integer
	 */
	public function getFavorecido() {
		return $this->iCgm;
	}


	/**
	 * Seta o valor total do lancamento
	 * @see ILancamentoAuxiliar::setValorTotal()
	 */
	public function setValorTotal($nValorTotal) {
		$this->nValorTotal = $nValorTotal;
	}

	/**
	 * Retorna o valor total
	 * @see ILancamentoAuxiliar::getValorTotal()
	 */
	public function getValorTotal() {
		return $this->nValorTotal;
	}

	/**
	 * Seta o codigo do historico
	 * @see ILancamentoAuxiliar::setHistorico()
	 */
	public function setHistorico($iHistorico) {
		$this->iCodigoHistorico = $iHistorico;
	}

	/**
	 * Retorna o codigo do historico
	 * @see ILancamentoAuxiliar::getHistorico()
	 */
	public function getHistorico() {
		return $this->iCodigoHistorico;
	}

	/**
	 * Retorna o complemento do lancamento contabil
	 * @see ILancamentoAuxiliar::getObservacaoHistorico()
	 */
	public function getObservacaoHistorico() {
		return $this->sObservacao;
	}

	/**
	 * Seta o complemento do lancamento contabil
	 * @see ILancamentoAuxiliar::setObservacaoHistorico()
	 */
	public function setObservacaoHistorico($sObservacao) {
		$this->sObservacao = $sObservacao;
	}

	/**
	 * Executa o lanзamento Auxiliar
	 * Mйtodo necessбrio para implementar a interface ILancamentoAuxiliar
	 * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
	 */
	public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {
		return true;
	}

}

?>