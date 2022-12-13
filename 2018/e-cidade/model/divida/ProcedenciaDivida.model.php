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
 *
 * Classe para consulta de procedencias
 * Utilizando as tabelas proced
 *
 * @author André Ianzer Hertzog andre.hertzog@dbseller.com.br
 * @author Everton Catto everton.heckler@dbseller.com.br
 * @package Divida
 * @revision $Author: dbroberto $
 * @version $Revision: 1.4 $
 *
 */
class ProcedenciaDivida {
	 
	 /** 
	  * $iProcedenciaDivida
	  * Tabela procdiver
	  * @var integer
	 */

	protected $iProcedenciaDivida;

	/**
	 * $sDescricaoAbreviada
	 * Tabela procdiver
	 * @var string
	 */

	protected $sDescricaoAbreviada;

	/**
	 * $sDescricaoCompleta
	 * Tabela procdiver
	 * @var string
	 */

	protected $sDescricaoCompleta;

	/**
	 * $iReceitaDivida
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iReceitaDivida;

	/**
	 * $iHistoricoCalculo
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iHistoricoCalculo;

	/**
	 * $iTipoProcedenciaTributaria
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iTipoProcedenciaTributaria;

	/**
	 * $iInstituicao
	 * Tabela procdiver
	 * @var integer
	 */

	protected $iInstituicao;
	
	
	/**
	 * iTipoProcedencia
	 * Código do tipo de Procedência. 1 - Imposto, 2 - Taxa, 3 - Contribuição, 4 - Outros 
	 * Tabela procdiver
	 * @var integer
	 */
	protected $iTipoProcedencia;

	/**
   * @var
   */
	protected $iTipoDebito;

	/**
	 * Construtor da classe
	 *
	 * @param integer Procedencia
	 */
	function __construct($iProcedenciaDivida = null) {
	
		$oDaoProcedenciaDivida       = new cl_proced;
	
		if (!empty($iProcedenciaDivida)) {
	
			$sSqlProcedenciaDivida     = $oDaoProcedenciaDivida->sql_query($iProcedenciaDivida);
			$rsSqlProcedenciaDivida    = $oDaoProcedenciaDivida->sql_record($sSqlProcedenciaDivida);
			
			if ($oDaoProcedenciaDivida->erro_status == '0') {
				
				throw new DBException($oDaoProcedenciaDivida->erro_msg);
			}
			
			$iNumRowsProcedenciaDivida = $oDaoProcedenciaDivida->numrows;

			if ($iNumRowsProcedenciaDivida > 0) {
	
				$oProcedenciaDivida      = db_utils::fieldsMemory($rsSqlProcedenciaDivida,0);
	
				$this->setProcedenciaDivida         ($oProcedenciaDivida->v03_codigo);
				$this->setDescricaoAbreviada        ($oProcedenciaDivida->v03_descr);
				$this->setDescricaoCompleta         ($oProcedenciaDivida->v03_dcomp);
				$this->setReceitaDivida             ($oProcedenciaDivida->v03_receit);
				$this->setHistoricoCalculo          ($oProcedenciaDivida->k00_hist);
				$this->setTipoProcedenciaTributaria ($oProcedenciaDivida->v03_tributaria);
				$this->setInstituicao               ($oProcedenciaDivida->v03_instit);
				$this->setTipoProcedencia           ($oProcedenciaDivida->v03_procedtipo);
				$this->setTipoDebito                ($oProcedenciaDivida->v06_arretipo);

				unset($oProcedenciaDivida);
			}
		}
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
	 * @param $iProcedencia
	 */
	public function setProcedenciaDivida($iProcedenciaDivida)
	{
	    $this->iProcedenciaDivida = $iProcedenciaDivida;
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
	public function getDescricaoCompleta()
	{
	    return $this->sDescricaoCompleta;
	}

	/**
	 * 
	 * @param $sDescricaoCompleta
	 */
	public function setDescricaoCompleta($sDescricaoCompleta)
	{
	    $this->sDescricaoCompleta = $sDescricaoCompleta;
	}

	/**
	 * 
	 * @return 
	 */
	public function getReceitaDivida()
	{
	    return $this->iReceitaDivida;
	}

	/**
	 * 
	 * @param $iReceitaDivida
	 */
	public function setReceitaDivida($iReceitaDivida)
	{
	    $this->iReceitaDivida = $iReceitaDivida;
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
	public function getTipoProcedenciaTributaria()
	{
	    return $this->iTipoProcedenciaTributaria;
	}

	/**
	 * 
	 * @param $iTipoProcedenciaTributaria
	 */
	public function setTipoProcedenciaTributaria($iTipoProcedenciaTributaria)
	{
	    $this->iTipoProcedenciaTributaria = $iTipoProcedenciaTributaria;
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
	public function getTipoProcedencia()
	{
	    return $this->iTipoProcedencia;
	}

	/**
	 * 
	 * @param $iTipoProcedencia
	 */
	public function setTipoProcedencia($iTipoProcedencia)
	{
	    $this->iTipoProcedencia = $iTipoProcedencia;
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
     * @param $iTipoDebito
     */
    public function setTipoDebito($iTipoDebito)
    {
        $this->iTipoDebito = $iTipoDebito;
    }

}


?>