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
 * Classe para manipulação do Fechamento da Promoção
 *
 * @author   Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @package  Recursos Humanos
 * @revision  $Author: dbrafael.nery $
 * @version  $Revision: 1.1 $
 */
class PromocaoFechamento  {

  /**
   * Codigo do Fechamento
   * @var integer
   */
	private $iCodigoFechamento;

	/**
	 * Código da Promoção 
	 * @var integer
	 */
	private $iCodigoPromocao;

	/**
	 * Pontuação adquirida na promoção 
	 * @var integer
	 */
	private $iPontuacao;


	/**
	 * Construtor da classe
	 * @param integer $iCodigoPromocao
	 */
	public function __construct($iCodigoFechamento = null) {
		 
		require_once("classes/db_rhpromocaofechamento_classe.php");
		 
		if ( !is_null($iCodigoFechamento) ) {
			 
			$oDaoRHPromocaoFechamento  = new cl_rhpromocaofechamento();
			$sSqlPromocaoFechamento    = $oDaoRHPromocaoFechamento->sql_query_file($iCodigoFechamento);
			$rsPromocaoFechamento      = db_query($sSqlPromocaoFechamento);
			 
			if ( !$rsPromocaoFechamento ||  ( $rsPromocaoFechamento && pg_num_rows($rsPromocaoFechamento) == 0 ) ) {
				throw new Exception( "Erro ao Buscar dados do Fechamento da Promoção \n" . pg_last_error() );
			}
			 
			$oPromocaoFechamento       = db_utils::fieldsMemory($rsPromocao, 0);
			$this->iCodigoFechamento   = $oPromocaoFechamento->h77_sequencial;
			$this->iCodigoPromocao     = $oPromocaoFechamento->h77_rhpromocao;
			$this->iPontuacao          = $oPromocaoFechamento->h77_pontosavaliacao;
		}
	}

	/**
	 * Define o código do Fechamento
	 * @param integer $iCodigoFechamento
	 */
	public function setCodigoFechamento($iCodigoFechamento) {
		$this->iCodigoFechamento = $iCodigoFechamento;
	}
	
	/**
	 * Retorna o código do fechamento
	 * @return integer
	 */
	public function getCodigoFechamento() {
		return $this->iCodigoFechamento;
	}

	/**
	 * Define o código da Promoção
	 * @param integer $iCodigoPromocao
	 */
	public function setCodigoPromocao($iCodigoPromocao) {
		$this->iCodigoPromocao = $iCodigoPromocao;
	}

	/**
	 * Retorna o código da promocao
	 * @return integer
	 */
	public function getCodigoPromocao() {
		return $this->iCodigoPromocao;
	}

	/**
	 * Define a Pontuacao
	 * @param integer $iPontuacao
	 */
	public function setPontuacao($iPontuacao) {
		$this->iPontuacao = $iPontuacao;
	}

	/**
	 * Retorna o pontuacao do fechamento
	 * @return integer
	 */
	public function getPontuacao() {
		return $this->iPontuacao;
	}

	/**
	 * Salva os dados do Fechamento
	 * @throws Exception
	 * @return bool;
	 */
	public function salvar() {

		$oDaoRhpromocaofechamento                      = new cl_rhpromocaofechamento();
		$oDaoRhpromocaofechamento->h77_rhpromocao      = $this->getCodigoPromocao();
		$oDaoRhpromocaofechamento->h77_pontosavaliacao = $this->getPontuacao();
		$oDaoRhpromocaofechamento->incluir(null);

		if ($oDaoRhpromocaofechamento->erro_status == 0 ) {
			throw new Exception($oDaoRhpromocaofechamento->erro_msg);
		}
		$this->iCodigoFechamento = $oDaoRhpromocaofechamento->h77_sequencial;

		return true;
	}

	/**
	 * Adiciona um assentamento ao fechamento da promoção
	 * @param  integer $iCodigoAssentamento
	 * @return bool
	 */
	public function adicionarAssentamento($iCodigoAssentamento) {

		require_once("classes/db_rhpromocaofechamentoassentamento_classe.php");

		$oDaoRhpromocaoFechamentoAssentamento                           = new cl_rhpromocaofechamentoassentamento();
		$oDaoRhpromocaoFechamentoAssentamento->h78_rhpromocaofechamento = $this->getCodigoFechamento();
		$oDaoRhpromocaoFechamentoAssentamento->h78_rhassentamento       = $iCodigoAssentamento;
		$oDaoRhpromocaoFechamentoAssentamento->incluir(null);

		if ($oDaoRhpromocaoFechamentoAssentamento->erro_status == 0 ) {
			throw new Exception($oDaoRhpromocaoFechamentoAssentamento->erro_msg);
		}
		return true;
	}
}