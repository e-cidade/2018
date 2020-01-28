<?php

/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
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
        throw new BusinessException("Medicamento ({$iCodigo}) n�o existe cadastrado no sistema");
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