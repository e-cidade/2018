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
 * Classe Unidade (orcunidade)
 * @author Acбcio Schneider
 * @package orcamento
 * @version $Revision: 1.1 $
 */
class Unidade{

  /**
   * Ano da Unidade
   * @var integer
   */
  private $iAnousu;

  /**
   * Cуdigo da Unidade
   * @var integer
   */
  private $iCodigoUnidade;

  /**
   * Cуdigo do tributбrio
   * @var string
   */
  private $sCodigoTributario;

  /**
   * Descriзгo da Unidade
   * @var string
   */
  private $sDescricao;

  /**
   * Identificador do tribunal
   * @var integer
   */
  private $iIdentificadorTribunal;

  /**
   * Identificador da Unidade
   * @var integer
   */
  private $iIdentificador;

  /**
   * Cуdigo da Instituiзгo
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * Cуdigo do Уrgгo
   * @var integer
   */
  private $iCodigoOrgao;

  /**
   * Objeto Instituiзгo
   * @var Instituicao
   */
  private $oInstituicao = null;

  /**
   * Objeto Orgao
   * @var Orgao
   */
  private $oOrgao       = null;

  /**
   * Cnpj da Unidade
   * @var string
   */
  private $sCnpj;

  /**
   * Busca a Unidade conforme os parвmetros passados e seta os atributos do objeto
   * conforme o que resultou da busca na base de dados
   *
   * @param int $iAno
   * @param int $iCodigoOrgao
   * @param int $iCodigoUnidade
   */
  public function __construct($iAno = null, $iCodigoOrgao = null, $iCodigoUnidade = null) {

    $this->iAnousu        = $iAno;
    $this->iCodigoOrgao   = $iCodigoOrgao;
    $this->iCodigoUnidade = $iCodigoUnidade;

    if (!empty($iAno) && !empty($iCodigoOrgao) && !empty($iCodigoUnidade)) {

      $oDaoOrcUnidade   = db_utils::getDao("orcunidade");
      $sSqlBuscaUnidade = $oDaoOrcUnidade->sql_query_file($iAno, $iCodigoOrgao, $iCodigoUnidade);
      $rsBuscaUnidade   = $oDaoOrcUnidade->sql_record($sSqlBuscaUnidade);

      if ($oDaoOrcUnidade->numrows == 0) {
        throw new BusinessException("Unidade {$iCodigoUnidade} nгo encontrada para o ano {$iAno} e уrgгo {$iCodigoOrgao}.");
      }

      $oStdUnidade = db_utils::fieldsMemory($rsBuscaUnidade, 0);
      $this->sCodigoTributario      = $oStdUnidade->o41_codtri;
      $this->sDescricao             = $oStdUnidade->o41_descr;
      $this->iIdentificadorTribunal = $oStdUnidade->o41_indent;
      $this->iIdentificador         = $oStdUnidade->o41_ident;
      $this->sCnpj                  = $oStdUnidade->o41_cnpj;
      $this->iCodigoInstituicao     = $oStdUnidade->o41_instit;

      unset($oStdUnidade);
    }

    return true;
  }

  /**
   * Seta o Cnpj da Unidade
   * @param string $sCnpj
   */
  public function setCnpj($sCnpj) {
    $this->sCnpj = $sCnpj;
  }

  /**
   * Retorna o Cnpj da Unidade
   * @return string
   */
  public function getCnpj() {
    return $this->sCnpj;
  }

  /**
   * Retorna o ano da Unidade
   * @return integer
   */
  public function getAno() {
    return $this->iAnousu;
  }

  /**
   * Seta o Ano da Unidade
   * @param integer $iAnoUsu
   */
  public function setAno($iAnousu) {
    $this->iAnousu = $iAnousu;
  }

  /**
   * Retorna o Cуdigo da Unidade
   * @return integer
   */
  public function getCodigoUnidade() {
    return $this->iCodigoUnidade;
  }

  /**
   * Seta o Cуdigo da Unidade
   * @param integer $iCodigoUnidade
   */
  public function setCodigoUnidade($iCodigoUnidade)	{
    $this->iCodigoUnidade = $iCodigoUnidade;
  }

  /**
   * Retorna o Codigo do tributбrio
   * @return integer
   */
  public function getCodigoTributario() {
    return $this->sCodigoTributario;
  }

  /**
   * Seta o Cуdigo do Tributбrio
   * @param string $sCodigoTributario
   */
  public function setCodigoTributario($sCodigoTributario) {
    $this->sCodigoTributario = $sCodigoTributario;
  }

  /**
   * Retorna a descriзгo da Unidade
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descriзгo da Unidade
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o Identificador do Tribunal
   * @return integer
   */
  public function getIdentificadorTribunal()	{
    return $this->iIdentificadorTribunal;
  }

  /**
   * Seta o Identificador do Tribunal
   * @param integer $iIdentificadorTribunal
   */
  public function setIdentificadorTribunal($iIdentificadorTribunal)	{
    $this->iIdentificadorTribunal = $iIdentificadorTribunal;
  }

  /**
   * Retorna o Identificador da Unidade
   * @return integer
   */
  public function getIdentificador()	{
    return $this->iIdentificador;
  }

  /**
   * Seta o Identificador da Unidade
   * @param integer $iIdentificador
   */
  public function setIdentificador($iIdentificador)	{
    $this->iIdentificador = $iIdentificador;
  }

  /**
   * Retorna o Objeto Instituiзгo da Unidade
   * @return Instituicao
   */
  public function getInstituicao() {

    if (!$this->oInstituicao instanceof Instituicao) {
      $this->oInstituicao = new Instituicao($this->iCodigoInstituicao);
    }
    return $this->oInstituicao;
  }

  /**
   * Seta a Instituiзгo da Unidade
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna o objeto Orgao da Unidade
   * @return Orgao
   */
  public function getOrgao() {

    if (!$this->oOrgao instanceof Orgao) {
      $this->oOrgao = new Orgao($this->iCodigoOrgao, $this->iAnousu);
    }
    return $this->oOrgao;
  }

  /**
   * Seta o Orgao da Unidade
   * @param Orgao
   */
  public function setOrgao(Orgao $oOrgao) {
    $this->oOrgao = $oOrgao;
  }
}
?>