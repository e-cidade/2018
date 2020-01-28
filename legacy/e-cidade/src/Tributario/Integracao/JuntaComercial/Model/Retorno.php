<?php
/**
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


namespace ECidade\Tributario\Integracao\JuntaComercial\Model;

class Retorno
{
  /**
   * Número único de identifcação do processo
   * @field PROTOCOLO
   * @size 20
   * @var string $sProtocolo
   */
  private $sProtocolo;

  /**
   * CNPJ da Instituição que analisou o processo
   * @field CNPJ_INSTITUICAO
   * @size 14
   * @var string $sCNPJInstituicao
   */
  private $sCNPJInstituicao;

  /**
   * Descrição do erro, caso exista
   * @field DESCRICAO_ERRO
   * @size 10000
   * @var string $sDescricaoErro
   */
  private $sDescricaoErro;

  /**
   * Data de geração do XML
   * @field DATA_GERACAO
   * @size 8
   * @var \DBDate $oDataGeracao
   */
  private $oDataGeracao;

  /**
   * Indica se o processo deve ser finalizado.
   * @field FINALIZA_PROCESSO
   * @size 1
   * @values [1 - Finaliza, 2 - Não finaliza]
   * @var integer $iFinalizaProcesso
   */
  private $iFinalizaProcesso;

  /**
   * Utilizado para indicar se o processo e de interesse da instituição.
   * @field PROCESSO_INTERESSE_INSTITUICAO
   * @size 1
   * @values [0 - Não tem interesse, 1 - Tem interesse]
   * @var integer $iProcessointeresseinstituicao
   */
  private $iProcessointeresseinstituicao;

  /**
   * Lista de licenças geradas ou que a instituição deverá gerar.
   * @field LICENCAS
   * @var Licenca[] $aLicencas
   */
  private $aLicencas = array();

  /**
   * Lista das áreas/secretarias da instituição e seu parecer de acordo com o processo.
   * @field ANALISES
   * @var Area[] $aAnalises
   */
  private $aAnalises = array();

  /**
   * XML de retorno
   * @var \SimpleXMLElement $oXmlRetorno
   */
  private $oXmlRetorno;

  /**
   * @return string
   */
  public function getProtocolo()
  {
    return $this->sProtocolo;
  }

  /**
   * @param string $sProtocolo
   */
  public function setProtocolo($sProtocolo)
  {
    $this->sProtocolo = $sProtocolo;
  }

  /**
   * @return string
   */
  public function getCNPJInstituicao()
  {
    return $this->sCNPJInstituicao;
  }

  /**
   * @param string $sCNPJInstituicao
   */
  public function setCNPJInstituicao($sCNPJInstituicao)
  {
    $this->sCNPJInstituicao = $sCNPJInstituicao;
  }

  /**
   * @return string
   */
  public function getDescricaoErro()
  {
    return $this->sDescricaoErro;
  }

  /**
   * @param string $sDescricaoErro
   */
  public function setDescricaoErro($sDescricaoErro)
  {
    $this->sDescricaoErro = $sDescricaoErro;
  }

  /**
   * @return \DateTime
   */
  public function getDataGeracao()
  {
    return $this->oDataGeracao;
  }

  /**
   * @param \DateTime $oDataGeracao
   */
  public function setDataGeracao(\DateTime $oDataGeracao)
  {
    $this->oDataGeracao = $oDataGeracao;
  }

  /**
   * @return int
   */
  public function getFinalizaProcesso()
  {
    return $this->iFinalizaProcesso;
  }

  /**
   * @param int $iFinalizaProcesso
   */
  public function setFinalizaProcesso($iFinalizaProcesso)
  {
    $this->iFinalizaProcesso = $iFinalizaProcesso;
  }

  /**
   * @return int
   */
  public function getProcessointeresseinstituicao()
  {
    return $this->iProcessointeresseinstituicao;
  }

  /**
   * @param int $iProcessointeresseinstituicao
   */
  public function setProcessointeresseinstituicao($iProcessointeresseinstituicao)
  {
    $this->iProcessointeresseinstituicao = $iProcessointeresseinstituicao;
  }

  /**
   * @return Licenca[]
   */
  public function getLicencas()
  {
    return $this->aLicencas;
  }

  /**
   * @param Licenca[] $aLicencas
   */
  public function setLicencas($aLicencas)
  {
    $this->aLicencas = $aLicencas;
  }

  /**
   * @return Area[]
   */
  public function getAnalises()
  {
    return $this->aAnalises;
  }

  /**
   * @param Area[] $aAnalises
   */
  public function setAnalises($aAnalises)
  {
    $this->aAnalises = $aAnalises;
  }

  /**
   * @return \SimpleXMLElement
   */
  public function getXmlRetorno()
  {
    return $this->oXmlRetorno;
  }

  /**
   * @param \SimpleXMLElement $oXmlRetorno
   */
  private function setXmlRetorno($oXmlRetorno)
  {
    $this->oXmlRetorno = $oXmlRetorno;
  }


  public function __construct()
  {
    $oXmlRetorno = new \SimpleXMLElement("<REGIN></REGIN>");
    $this->setXmlRetorno($oXmlRetorno);
  }

  public function organizaRetorno()
  {
    $this->getXmlRetorno()->addChild("PROTOCOLO", $this->getProtocolo());
    $this->getXmlRetorno()->addChild("CNPJ_INSTITUICAO", $this->getCNPJInstituicao());
    $this->getXmlRetorno()->addChild("DESCRICAO_ERRO", $this->getDescricaoErro());
    $this->getXmlRetorno()->addChild("DATA_GERACAO", $this->getDataGeracao()->format("Ymd"));
    $this->getXmlRetorno()->addChild("FINALIZA_PROCESSO", $this->getFinalizaProcesso());
    $this->getXmlRetorno()->addChild("PROCESSO_INTERESSE_INSTITUICAO", $this->getProcessointeresseinstituicao());
    $oXmlLicencas = $this->getXmlRetorno()->addChild("LICENCAS");
    $oXmlAnalises = $this->getXmlRetorno()->addChild("ANALISES");

    foreach ($this->getLicencas() as $oLicenca){

      $oXmlLicenca = $oXmlLicencas->addChild("LICENCA");
      $oXmlLicenca->addChild("CODIGO_AREA", $oLicenca->getCodigoArea());
      $oXmlLicenca->addChild("CODIGO_LICENCA", $oLicenca->getCodigoLicenca());
      $oXmlLicenca->addChild("LICENCA_DEFINITIVA", $oLicenca->getLicencaDefinitiva());
      $oXmlLicenca->addChild("NUMERO_LICENCA", $oLicenca->getNumeroLicenca());
    }

    foreach ($this->getAnalises() as $oArea){

      $oXmlArea = $oXmlAnalises->addChild("AREA");
      $oXmlArea->addChild("CODIGO_AREA", $oArea->getCodigoArea());
      $oXmlArea->addChild("DATA_ANALISE", $oArea->getDataAnalise());
      $oXmlArea->addChild("STATUS_ANALISE", $oArea->getStatusAnalise());
      $oXmlArea->addChild("JUSTIFICATIVA_ANALISE", $oArea->getJustificaAnalise());
      $oXmlArea->addChild("CPF_USUARIO_ANALISE", $oArea->getCPFUsuarioAnalise());
    }
  }

}
