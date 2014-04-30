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
 * Model dos assentamentos de Servidor
 *
 * @package pessoal
 * @author Alberto <alberto@dbseller.com.br>
 */
class Assentamento {

	/**
	 * Código assentamento
	 * @var integer
	 */
	private $iCodigo;

	/**
	 * Matrícula servidor
	 * @var integer
	 */
	private $iMatricula;

	/**
	 * Código do tipo de assentamento
	 * @var integer
	 */
	private $iTipoAssentamento;

	/**
	 * Instância do objeto DBDate com a data da concessão do afastamento ou assentamento
	 * @var DBDate
	 */
	private $oDataConcessao;

	/**
	 * Histórico do assentamento
	 * @var string
	 */
	private $sHistorico;

	/**
	 * Código da portaria emitida
	 * @var string
	 */
	private $sCodigoPortaria;

	/**
	 * Descrição do ato oficial
	 * @var string
	 */
	private $sDescricaoAto;

	/**
	 * Quantidade de dias concedidos
	 * @var integer
	 */
	private $iDias;

	/**
	 * Percentual concedido
	 * @var number
	 */
	private $nPercentual;

	/**
	 * Instância do objeto DBDate com a data do termino do afastamento/assentamento
	 * @var DBDate
	 */
	private $oDataTermino;

	/**
	 * Segundo Histórico do assentamento
	 * @var string
	 */
	private $sSegundoHistorico;

	/**
	 * Login do usuário que registrou o asssentamento/afastamento
	 * @var string
	 */
	private $sLoginUsuario;

	/**
	 * Instância do objeto DBDate com a data de lançamento do afastamento ou assentamento
	 * @var DBDate
	 */
	private $oDataLancamento;

	/**
	 * Se registro foi convertido
	 * @var boolean
	 */
	private $lConvertido;

	/**
	 * Ano da portaria do registro
	 * @var integer
	 */
	private $iAnoPortaria;

	public function __construct($iCodigo) {

		if ( empty($iCodigo) ) {
			return;
		}

		$oDaoAssentamento = db_utils::getDao('assenta');
		$sSqlAssentamento = $oDaoAssentamento->sql_query_file($iCodigo);
		$rsAssentamento   = $oDaoAssentamento->sql_record($sSqlAssentamento);

		if ($oDaoAssentamento->numrows == 0) {
			throw new BusinessException('Nenhum assentamento encontrado.');
		}

		$oAssentamento = db_utils::fieldsMemory($rsAssentamento, 0);

		$this->setCodigo          ($oAssentamento->h16_codigo);
		$this->setMatricula       ($oAssentamento->h16_regist);
		$this->setTipoAssentamento($oAssentamento->h16_assent);
		$this->setHistorico       ($oAssentamento->h16_histor);
		$this->setCodigoPortaria  ($oAssentamento->h16_nrport);
		$this->setDescricaoAto    ($oAssentamento->h16_atofic);
		$this->setDias            ($oAssentamento->h16_quant);
		$this->setPercentual      ($oAssentamento->h16_perc);
		$this->setSegundoHistorico($oAssentamento->h16_hist2);
		$this->setLoginUsuario    ($oAssentamento->h16_login);
		$this->setDataLancamento  ($oAssentamento->h16_dtlanc);
		$this->setConvertido      ($oAssentamento->h16_conver);
		$this->setAnoPortaria     ($oAssentamento->h16_anoato);

		if( !empty($oAssentamento->h16_dtconc) ){
			$oDataConcessao = new DBDate($oAssentamento->h16_dtconc);
		  $this->setDataConcessao ($oDataConcessao);
		}

		if( !empty($oAssentamento->h16_dtterm) ){
			$oDataTermino = new DBDate($oAssentamento->h16_dtterm);
		  $this->setDataTermino   ($oDataTermino);
		}

	}

	/**
	 * Retorna o código do assentamento
	 * @return number
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}

	/**
	 * Define o código do assentamento
	 * @param integer $iCodigo
	 */
	public function setCodigo($iCodigo) {
		$this->iCodigo = $iCodigo;
	}

	/**
	 * Retorna a matrícula do servidor do assentamento
	 * @return integer
	 */
	public function getMatricula() {
		return $this->iMatricula;
	}

	/**
	 * Define a matrícula do servidor do assentamento
	 * @param unknown $iMatricula
	 */
	public function setMatricula($iMatricula) {
		$this->iMatricula = $iMatricula;
	}

	/**
	 * Define o tipo de assentamento
	 * @return integer
	 */
	public function getTipoAssentamento() {
		return $this->iTipoAssentamento;
	}

	/**
	 * Define o tipo de assentamneto
	 * @param integer $iTipoAssentamento
	 */
	public function setTipoAssentamento($iTipoAssentamento) {
		$this->iTipoAssentamento = $iTipoAssentamento;
	}

	/**
	 * Retorna a data de concessão do afastamento
	 * @return DBDate
	 */
	public function getDataConcessao() {
		return $this->oDataConcessao;
	}

	/**
	 * Define a data de concessão do afastamento
	 * @param DBDate $oDataConcessao
	 */
	public function setDataConcessao(DBDate $oDataConcessao) {
		$this->oDataConcessao = $oDataConcessao;
	}

	/**
	 * Define o histórico do afastamento
	 * @return string
	 */
	public function getHistorico() {
		return $this->sHistorico;
	}

	/**
	 * Define o histório do afastamento
	 * @param unknown $sHistorico
	 */
	public function setHistorico($sHistorico) {
		$this->sHistorico = $sHistorico;
	}

	/**
	 * Retorna o código da portaria
	 * @return string
	 */
	public function getCodigoPortaria() {
		return $this->sCodigoPortaria;
	}

	/**
	 * Define o código da portaria
	 * @param unknown $sCodigoPortaria
	 */
	public function setCodigoPortaria($sCodigoPortaria) {
		$this->sCodigoPortaria = $sCodigoPortaria;
	}

	/**
	 * Retorna a descrição do ato
	 * @return string
	 */
	public function getDescricaoAto() {
		return $this->sDescricaoAto;
	}

	/**
	 * Define a descrição do ato
	 * @param string $sDescricaoAto
	 */
	public function setDescricaoAto($sDescricaoAto) {
		$this->sDescricaoAto = $sDescricaoAto;
	}

	/**
	 * Retorna o número de dias do afastametno
	 * @return number
	 */
	public function getDias() {
		return $this->iDias;
	}

	/**
	 * Define o número de dias do afastamento
	 * @param integer $iDias
	 */
	public function setDias($iDias) {
		$this->iDias = $iDias;
	}

	/**
	 * Retorna o percentual do afastamento
	 * @return number
	 */
	public function getPercentual() {
		return $this->nPercentual;
	}

	/**
	 * Define o percentual do afastamento
	 * @param number $nPercentual
	 */
	public function setPercentual($nPercentual) {
		$this->nPercentual = $nPercentual;
	}

	/**
	 * Retorna uma instância do objeto DBDate com a data do afastamento
	 * @return DBDate
	 */
	public function getDataTermino() {
		return $this->oDataTermino;
	}

	/**
	 * Define uma instância do objeto DBDate com a  data de termino do afastamento/assentamento
	 * @paramDBDate $oDataTermino
	 */
	public function setDataTermino(DBDate $oDataTermino) {
		$this->oDataTermino = $oDataTermino;
	}

	/**
	 * Retorna o segundo histórico do afastamento
	 * @return string
	 */
	public function getSegundoHistorico() {
		return $this->sSegundoHistorico;
	}

	/**
	 * Define o segundo histórico do afastamento
	 * @param string $sSegundoHistorico
	 */
	public function setSegundoHistorico($sSegundoHistorico) {
		$this->sSegundoHistorico = $sSegundoHistorico;
	}

	/**
	 * Retorna o login do usuário
	 * @return string
	 */
	public function getLoginUsuario() {
		return $this->sLoginUsuario;
	}

	/**
	 * Define o login do usuário
	 * @param string $sLoginUsuario
	 */
	public function setLoginUsuario($sLoginUsuario) {
		$this->sLoginUsuario = $sLoginUsuario;
	}

	/**
	 * Retorna uma instância do objeto DBDate com a data de lançamento do afastamento / assentamento
	 * @return DBDate
	 */
	public function getDataLancamento() {
		return $this->oDataLancamento;
	}

	/**
	 * Define uma instância do objeto DBDate com a data de lançamento do afastamento / assentamento
	 * @param unknown $oDataLancamento
	 */
	public function setDataLancamento($oDataLancamento) {
		$this->oDataLancamento = $oDataLancamento;
	}

	/**
	 * Retorna se o afastamento foi convertido
	 * @return boolean
	 */
	public function isConvertido() {
		return $this->lConvertido;
	}

	/**
	 * Define se o registro foi convertido
	 * @param boolean $lConvertido
	 */
	public function setConvertido($lConvertido) {
		$this->lConvertido = $lConvertido;
	}

	/**
	 * Retorna o ano da portaria
	 * @return number
	 */
	public function getAnoPortaria() {
		return $this->iAnoPortaria;
	}

	/**
	 * Define o ano da portaria
	 * @param integer $iAnoPortaria
	 */
	public function setAnoPortaria($iAnoPortaria) {
		$this->iAnoPortaria = $iAnoPortaria;
	}

}