<?php

/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Controle de Medicamentos para a farmacia
 * Class Medicamento
 */
class Medicamento {

  /**
   * Codigo do Material
   * @var integer
   */
  private $iCodigo;

  /**
   * @var MaterialEstoque
   */
  private $oMaterial;

  /**
   * Descricao do medicamento
   * @var string
   */
  private $sDescricao;

  /**
   * Unidade do material
   * @var UnidadeMaterial
   */
  private $oUnidade;

  /**
   * Quantidade do material
   * @var float
   */
  private $nQuantidade = 1;

  /**
   * Instancia um novo Medicamento
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoMedicamento      = new cl_far_matersaude();
      $sCampos              = "fa01_i_codmater, fa01_i_codigo, fa01_c_nomegenerico, m08_unidade, m08_quantidade";
      $sSqlDadosMedicamento = $oDaoMedicamento->sql_query_dados($iCodigo, $sCampos);
      $rsFracionamento      = $oDaoMedicamento->sql_record($sSqlDadosMedicamento);
      if (!$rsFracionamento || $oDaoMedicamento->numrows == 0) {
        throw new BusinessException("Medicamento ({$iCodigo}) não existe cadastrado no sistema");
      }

      $oDadosMedicamento = db_utils::fieldsMemory($rsFracionamento, 0);
      $this->setCodigo($iCodigo);
      $this->setDescricao($oDaoMedicamento->fa01_c_nomegenerico);
      $this->oMaterial = new MaterialAlmoxarifado($oDadosMedicamento->fa01_i_codmater);
      $this->oUnidade = $this->oMaterial->getUnidade();
      if (!empty($oDadosMedicamento->m08_unidade)) {
        $this->oUnidade = UnidadeMaterialRepository::getByCodigo($oDadosMedicamento->m08_unidade);
      }

      if (!empty($oDadosMedicamento->m08_quantidade)) {
        $this->nQuantidade = $oDadosMedicamento->m08_quantidade;
      }

      if (empty($oDaoMedicamento->fa01_c_nomegenerico)) {
        $this->sDescricao = $this->oMaterial->getDescricao();
      }
    }
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return MaterialAlmoxarifado
   */
  public function getMaterial() {
    return $this->oMaterial;
  }

  /**
   * @param MaterialAlmoxarifado $oMaterial
   */
  public function setMaterial(MaterialAlmoxarifado $oMaterial) {
    $this->oMaterial = $oMaterial;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return UnidadeMaterial
   */
  public function getUnidade() {
    return $this->oUnidade;
  }

  /**
   * @param UnidadeMaterial $oUnidade
   */
  public function setUnidade($oUnidade) {
    $this->oUnidade = $oUnidade;
  }

  /**
   * @return float
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * @param float $nQuantidade
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

}