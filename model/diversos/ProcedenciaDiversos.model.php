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
 *
 * Classe para consulta de procedencias de Diversos
 * Utilizando as tabelas procdiver
 *
 * @author André Ianzer Hertzog andre.hertzog@dbseller.com.br
 * @author Everton Catto everton.heckler@dbseller.com.br
 * @package Diversos
 * @revision $Author: dbeverton.heckler $
 * @version $Revision: 1.1 $
 *
 */
class ProcedenciaDiversos {

	/**
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iProcedenciaDiverso;

	/**
	 * Tabela procdiver
	 * @var string
	 */

	protected $sDescricaoAbreviada;

	/**
	 * Tabela procdiver
	 * @var string
	 */

	protected $sDescricao;

	/**
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iReceita;

	/**
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iHistoricoCalculo;

	/**
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iProcedenciaDivida;

	/**
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iTipoDebito;

	/**
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iInstituicao;

	/**
	 * Tabela procdiver
	 * @var date
	 */

	protected $dDataLimite;

	

	/**
	 * Construtor da classe
	 *
	 * @param integer ProcedenciaDiverso
	 */
	function __construct($iProcedenciaDiverso = null) {
    
		$oDaoProcedenciaDiversos     = db_utils::getDao("procdiver");
		
		if (!empty($iProcedenciaDiverso)) {
			
			$sSqlProcedenciaDiversos     = $oDaoProcedenciaDiversos->sql_query($iProcedenciaDiverso);
			$rsSqlProcedenciaDiversos    = $oDaoProcedenciaDiversos->sql_record($sSqlProcedenciaDiversos);
			
			if ($oDaoProcedenciaDiversos->erro_status == '0') {
				throw new DBException($oDaoProcedenciaDiversos->erro_msg);
			}
			
			$iNumRowsProcedenciaDiversos = $oDaoProcedenciaDiversos->numrows;

			if ($iNumRowsProcedenciaDiversos > 0) {
        
				$oProcedenciaDiversos      = db_utils::fieldsMemory($rsSqlProcedenciaDiversos,0);
				
				$this->setProcedenciaDiverso ($oProcedenciaDiversos->dv09_procdiver);
				$this->setDescricaoAbreviada ($oProcedenciaDiversos->dv09_descra);
				$this->setDescricao          ($oProcedenciaDiversos->dv09_descr);
				$this->setReceita            ($oProcedenciaDiversos->dv09_receit);
				$this->setHistoricoCalculo   ($oProcedenciaDiversos->dv09_hist);
				$this->setProcedenciaDivida	 ($oProcedenciaDiversos->dv09_proced);
				$this->setTipoDebito				 ($oProcedenciaDiversos->dv09_tipo);
				$this->setInstituicao        ($oProcedenciaDiversos->dv09_instit);
				$this->setDataLimite         ($oProcedenciaDiversos->dv09_dtlimite);

				unset($oProcedenciaDiversos);
			}
		}
	}

	/**
	 *
	 * @return
	 */
	public function getProcedenciaDiverso()
	{
		return $this->iProcedenciaDiverso;
	}

	/**
	 *
	 * @param $iProcedencia
	 */
	public function setProcedenciaDiverso($iProcedenciaDiverso)
	{
		$this->iProcedenciaDiverso = $iProcedenciaDiverso;
	}

	/**
	 *
	 * @return
	 */
	public function getDescricaoAbreviada()
	{
		return $this->sDescricaoAbreviada;
	}

	/**
	 *
	 * @param $sDescricaoAbreviada
	 */
	public function setDescricaoAbreviada($sDescricaoAbreviada)
	{
		$this->sDescricaoAbreviada = $sDescricaoAbreviada;
	}

	/**
	 *
	 * @return
	 */
	public function getDescricao()
	{
		return $this->sDescricao;
	}

	/**
	 *
	 * @param $sDescricao
	 */
	public function setDescricao($sDescricao)
	{
		$this->sDescricao = $sDescricao;
	}

	/**
	 *
	 * @return
	 */
	public function getReceita()
	{
		return $this->iReceita;
	}

	/**
	 *
	 * @param $iReceita
	 */
	public function setReceita($iReceita)
	{
		$this->iReceita = $iReceita;
	}

	/**
	 *
	 * @return
	 */
	public function getHistoricoCalculo()
	{
		return $this->iHistoricoCalculo;
	}

	/**
	 *
	 * @param $iHistoricoCalculo
	 */
	public function setHistoricoCalculo($iHistoricoCalculo)
	{
		$this->iHistoricoCalculo = $iHistoricoCalculo;
	}

	/**
	 *
	 * @return
	 */
	public function getProcedenciaDivida()
	{
		return $this->iProcedenciaDivida;
	}

	/**
	 *
	 * @param $iProcedenciaDivida
	 */
	public function setProcedenciaDivida($iProcedenciaDivida)
	{
		$this->iProcedenciaDivida = $iProcedenciaDivida;
	}

	/**
	 *
	 * @return
	 */
	public function getTipoDebito()
	{
		return $this->iTipoDebito;
	}

	/**
	 *
	 * @param $iTipoDivida
	 */
	public function setTipoDebito($iTipoDebito)
	{
		$this->iTipoDebito = $iTipoDebito;
	}

	/**
	 *
	 * @return
	 */
	public function getInstituicao()
	{
		return $this->iInstituicao;
	}

	/**
	 *
	 * @param $iInstituicao
	 */
	public function setInstituicao($iInstituicao)
	{
		$this->iInstituicao = $iInstituicao;
	}

	/**
	 *
	 * @return
	 */
	public function getDataLimite()
	{
		return $this->dDataLimite;
	}

	/**
	 *
	 * @param $dDataLimite
	 */
	public function setDataLimite($dDataLimite)
	{
		$this->dDataLimite = $dDataLimite;
	}

	/**
	 *
	 * @return
	 */
	public function getProcedenciasInstituicao()
	{

		$iInstituicao = $this->getInstituicao();
			
		if(!empty($iInstituicao)){
			
			$sWhereInstituicao = "dv09_instit = '{$iInstituicao}'";
			
		}else{
			
			$sWhereInstituicao = '';
			
		}
		
		$sSqlProcedenciaDiversos      = $oDaoProcedenciaDiversos->sql_query_file(null,'*','dv09_descra',$sWhereInstituicao);
		$rsSqlProcedenciaDiversos     = $oDaoProcedenciaDiversos->sql_record($sSqlProcedenciaDiversos);
		$iNumRowsProcedenciaDiversos  = $oDaoProcedenciaDiversos->numrows;

		if ($iNumRowsProcedenciaDiversos > 0) {

			return db_utils::getCollectionByRecord($rsSqlProcedenciaDiversos);
				
		}
			
	}


}

?>