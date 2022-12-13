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
 * Unidade dos materiais
 * Class UnidadeMaterial
 */
class UnidadeMaterial {

  /**
   * @var
   */
  private $iCodigo;

  /**
   * @var
   */
  private $sDescricao;

  /**
   * Abreviatura da unidades
   * @var string
   */
  private $sAbreviatura;


  /**
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoMatunid   = new cl_matunid();
      $oDadosUnidade = db_utils::getRowFromDao($oDaoMatunid, array($iCodigo));
      if (empty($oDadosUnidade)) {
        throw new BusinessException("Unidade {$iCodigo} n�o Cadastrada");
      }
      $this->setCodigo($oDadosUnidade->m61_codmatunid);
      $this->setDescricao($oDadosUnidade->m61_descr);
      $this->setAbreviatura($oDadosUnidade->m61_abrev);
    }
  }

  /**
   * @return mixed
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param mixed $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return mixed
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param mixed $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return string
   * @deprecated
   * @see getAbreviatura
   */
  public function getSAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * @return string
   */
  public function getAbreviatura() {
    return $this->getSAbreviatura();
  }

  /**
   * @param string $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }



}