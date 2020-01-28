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

/**
 * Classe responsável pela figura de Partilha.
 * @author vinicius.silva@dbseller.com.br
 */

/**
 * Carregamos as classes necessárias para o funcionamento da classe.
 */
db_app::import('Taxa');
db_app::import('recibo');

class Partilha {

	/**
	 * Código da partilha.
	 * @var integer
	 */
	protected $iCodigoPartilha;

	/**
	 * Código do processo do foro.
	 * @var integer
	 */
	protected $iCodigoProcessoForo;

	/**
	 * Tipo do lançamento.
	 * @var integer
	 */
	protected $iTipoLancamento;

	/**
	 * Data do pagamento.
	 * @var date
	 */
	protected $dDataPagamento;

	/**
	 * Observação da partilha.
	 * @var string
	 */
	protected $sObservacao;

	/**
	 * Recibo da partilha.
	 * @var Recibo
	 */
	protected $oRecibo;
	
	/**
	 * Collection de taxas
	 * @var array
	 */
	protected $aTaxas = array();

	/**
	 * Método construtor da classe.
	 */
	public function __construct($iCodigoPartilha) {

		if (empty($iCodigoProcessoForo)) {

			$oDaoProcessoForoPartilha      = db_utils::getDao("processoforopartilha");
			$oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
			
			$sSqlProcessoForoPartilha      = $oDaoProcessoForoPartilha->sql_query_file($iCodigoPartilha);
			$rsProcessoForoPartilha        = $oDaoProcessoForoPartilha->sql_record($sSqlProcessoForoPartilha);

			if ( $oDaoProcessoForoPartilha->erro_status == "0" ) {
				
				$sErro = "Erro ao Buscar dados da Partilha: {$iCodigoPartilha}: \n".
				         $oDaoProcessoForoPartilha->erro_msg;
        throw new DBException($sErro);
			}

			/**
			 * Carregamos o resultado da busca da partilha e setamos as propriedades da classe com o mesmo.
			 */
			$oPartilha = db_utils::fieldsMemory($rsProcessoForoPartilha, 0);
			$this->setCodigoPartilha($oPartilha->v76_sequencial);
			$this->setCodigoProcessoForo($oPartilha->v76_processoforo);
			$this->setTipoLancamento($oPartilha->v76_tipolancamento);
			$this->setDataPagamento($oPartilha->v76_dtpagamento);
			$this->setObservacao($oPartilha->v76_obs);
			
			
			/**
			 * Buscamos as taxas/custas da partilha.
			 */
			$sSqlProcessoForoPartilhaCusta = $oDaoProcessoForoPartilhaCusta->sql_query_file(null, "*", null, 
			                                                                                " v77_processoforopartilha = 
			                                                                                {$this->getCodigoPartilha()} ");
      $rsProcessoForoPartilhaCusta   = $oDaoProcessoForoPartilhaCusta->sql_record($sSqlProcessoForoPartilhaCusta);
      
      if ($oDaoProcessoForoPartilhaCusta->erro_status == "0") {
        throw new DBException("Erro ao Buscar dados das taxas da Partilha");
      }
    	
      $aTaxas        = db_utils::getCollectionByRecord($rsProcessoForoPartilhaCusta);
    	$iNumpreRecibo = null;

    	foreach ($aTaxas as $oTaxa) {
    		
    		$this->adicionarTaxa(new Taxa($oTaxa->v77_taxa));
    		$iNumpreRecibo = $oTaxa->v77_numnov;
    	}
    	$this->setRecibo( new recibo(null, null, null, $iNumpreRecibo) );
		}
	}
	
	/**
	 * Setter do Código da Partilha.
	 * @param integer $iCodigoPartilha
	 */
	public function setCodigoPartilha($iCodigoPartilha) {
		$this->iCodigoPartilha = $iCodigoPartilha;
	}
	
	/**
	 * Getter do Código da Partilha.
	 * @return integer
	 */
	public function getCodigoPartilha() {
		return $this->iCodigoPartilha;
	}
	
	/**
	 * Setter do Código do Processo do Foro.
	 * @param integer $iCodigoProcessoForo
	 */
	public function setCodigoProcessoForo($iCodigoProcessoForo) {
		$this->iCodigoProcessoForo = $iCodigoProcessoForo;
	}
	
	/**
	 * Getter do Código do Processo do Foro.
	 * @return integer
	 */
	public function getCodigoProcessoForo() {
		return $this->iCodigoProcessoForo;
	}

	/**
	 * Setter do Tipo do Lançamento.
	 * @param integer $iTipoLancamento
	 */
	public function setTipoLancamento($iTipoLancamento) {
		$this->iTipoLancamento = $iTipoLancamento;
	}
	
	/**
	 * Getter do Tipo do Lancamento.
	 * @return integer
	 */
	public function getTipoLancamento() {
		return $this->iTipoLancamento;
	}
	
	/**
	 * Setter da Data de Pagamento.
	 * @param date $dDataPagamento
	 */
	public function setDataPagamento($dDataPagamento) {
		$this->dDataPagamento = $dDataPagamento;
	}
	
	/**
	 * Getter da Data de Pagamento.
	 * @return date
	 */
	public function getDataPagamento() {
		return $this->dDataPagamento;
	}

	/**
	 * Setter da Observação da Partilha.
	 * @param string $sObservacao
	 */
	public function setObservacao($sObservacao) {
		$this->sObservacao = $sObservacao;
	}
	
	/**
	 * Getter da Observação da Partilha.
	 * @return string
	 */
	public function getObservacao() {
		return $this->sObservacao;
	}

	/**
	 * Setter do Recibo
	 * @param Recibo $oRecibo
	 */
	public function setRecibo($oRecibo) {
		$this->oRecibo = $oRecibo;
	}

	/**
	 * Getter do Recibo.
	 * @return Recibo
	 */
	public function getRecibo() {
		return $this->oRecibo;
	}
	
	/**
	 * Método que adiciona um índice à collection de taxas.
	 * @param Taxa $oTaxa
	 */
	public function adicionarTaxa(Taxa $oTaxa) {
		$this->aTaxas[$oTaxa->getCodigoTaxa()] = $oTaxa;
	}
	
	/**
	 * Método que remove um índice à collection de taxas.
	 */
	public function removerTaxa($iCodigoTaxa) {
		
	}
	
	/**
	 * Getter da collection de taxas.
	 * @return array
	 */
	public function getTaxas() {
		return $this->aTaxas;
	}
}