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
        throw new BusinessException("Unidade {$iCodigo} não Cadastrada");
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