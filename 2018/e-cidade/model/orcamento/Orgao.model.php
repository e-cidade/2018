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
 * Classe Уrgгo (orcorgao)
 * @author Acбcio
 * @package orcamento
 * @version $Revision: 1.1 $
 */
class Orgao{

	/**
	 * Ano do уrgгo
	 * @var integer
	 */
	private $iAnousu;

	/**
	 * Cуdigo do уrgгo
   * @var integer
	 */
	private $iCodigoOrgao;

	/**
	 * Cуdigo do tribunal
	 * @var string
	 */
	private $sCodigoTribunal;

	/**
	 * Descriзгo do уrgгo
	 * @var string
	 */
	private $sDescricao;

	/**
	 * Objeto Instituiзгo
	 * @var Instituicao
	 */
	private $oInstituicao = null;

	/**
	 * Cуdigo da instituiзгo
   * @var integer
	 */
	private $iCodigoInstituicao;

	/**
	 * Finalidade do уrgгo
	 * @var string
	 */
	private $sFinalidade;

	/**
	 * Busca o Уrgгo a partir do cуdigo e do ano informado por parвmetro e seta os atributos do objeto
	 * conforme o que resultou da busca na base
	 *
	 * @param int $iCodigoOrgao
	 * @param int $iAno
	 * @throws BusinessException
	 */
	public function __construct($iCodigoOrgao = null, $iAno = null) {

		$this->iCodigoOrgao = $iCodigoOrgao;
		$this->iAnousu      = $iAno;

		if (!empty($iCodigoOrgao) && !empty($iAno)) {

			$oDaoOrcOrgao   = db_utils::getDao("orcorgao");
			$sSqlBuscaOrgao = $oDaoOrcOrgao->sql_query_file($iAno, $iCodigoOrgao);
			$rsBuscaOrgao   = $oDaoOrcOrgao->sql_record($sSqlBuscaOrgao);

			if ($oDaoOrcOrgao->numrows == 0) {
				throw new BusinessException("Уrgгo {$iCodigoOrgao} nгo encontrado para o ano {$iAno}.");
			}

			$oStdOrgao = db_utils::fieldsMemory($rsBuscaOrgao, 0);
			$this->sCodigoTribunal    = $oStdOrgao->o40_codtri;
			$this->sDescricao         = $oStdOrgao->o40_descr;
			$this->sFinalidade        = $oStdOrgao->o40_finali;
			$this->iCodigoInstituicao = $oStdOrgao->o40_instit;

			unset($oStdOrgao);
		}

		return true;
	}

	/**
	 * Retorna o ano do уrgгo
	 * @return integer
	 */
	public function getAno() {
    return $this->iAnousu;
	}

	/**
	 * Seta o ano do уrgгo
	 * @param integer $iAnousu
	 */
	public function setAno($iAnousu) {
	  $this->iAnousu = $iAnousu;
	}

	/**
	 * Retorna o Cуdigo do Уrgгo
	 * @return integer
	 */
	public function getCodigoOrgao() {
	  return $this->iCodigoOrgao;
	}

	/**
	 * Seta o cуdigo do Уrgгo
	 * @param integer $iCodigoOrgao
	 */
	public function setCodigoOrgao($iCodigoOrgao) {
	  $this->iCodigoOrgao = $iCodigoOrgao;
	}

	/**
	 * Retorna o Cуdigo do tribunal
	 * @return string
	 */
	public function getCodigoTribunal() {
	  return $this->sCodigoTribunal;
	}

	/**
	 * Seta o cуdigo do tribunal
	 * @param string $sCodigoTribunal
	 */
	public function setCodigoTribunal($sCodigoTribunal) {
	  $this->sCodigoTribunal = $sCodigoTribunal;
	}

	/**
	 * Retorna a descriзгo do Уrgгo
	 * @return string
	 */
	public function getDescricao() {
	  return $this->sDescricao;
	}

	/**
	 * Seta a descriзгo do Уrgгo
	 * @param string $sDescricao
	 */
	public function setDescricao($sDescricao) {
	  $this->sDescricao = $sDescricao;
	}

	/**
	 * Retorna o objeto Instituiзгo
	 * @return Instituicao
	 */
	public function getInstituicao() {

		if (!$this->oInstituicao instanceof Instituicao) {
			$this->oInstituicao = new Instituicao($this->iCodigoInstituicao);
		}
	  return $this->oInstituicao;
	}

	/**
	 * Seta a Instituiзгo
	 * @param Instituicao $oInstituicao
	 */
	public function setInstituicao(Instituicao $oInstituicao) {
	  $this->oInstituicao = $oInstituicao;
	}

	/**
	 * Retorna a finalidade do уrgгo
	 * @return string
	 */
	public function getFinalidade() {
	  return $this->sFinalidade;
	}

	/**
	 * Seta a finalidade do уrgгo
	 * @param string $sFinalidade
	 */
	public function setFinalidade($sFinalidade) {
	  $this->sFinalidade = $sFinalidade;
	}
}
?>