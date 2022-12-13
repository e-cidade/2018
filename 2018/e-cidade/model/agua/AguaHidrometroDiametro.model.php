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

class AguaHidrometroDiametro {

  /**
   * @var integer Código
   */
  private $iCodigo;

  /**
   * @var string Descrição
   */
  private $sDescricao;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
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
   * AguaHidrometroDiametro constructor.
   * @param integer|null $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (!$this->iCodigo) {
      return;
    }

    $oAguaHidroDiametro    = new cl_aguahidrodiametro();
    $sSqlAguaHidroDiametro = $oAguaHidroDiametro->sql_query_file($this->getCodigo());
    $rsAguaHidroDiametro   = db_query($sSqlAguaHidroDiametro);

    if (!$rsAguaHidroDiametro || pg_num_rows($rsAguaHidroDiametro) == 0) {
      throw new DBException("Não foi possível encontrar as informações do Diâmetro do Hidrômetro.");
    }

    $oHidrometroDiametro = db_utils::fieldsMemory($rsAguaHidroDiametro, 0);

    $this->iCodigo    = $oHidrometroDiametro->x15_coddiametro;
    $this->sDescricao = $oHidrometroDiametro->x15_diametro;
  }

  /**
   * @return int
   * @throws DBException
   */
  public function salvar() {

    $oDaoAguaHidroDiametro = new cl_aguahidrodiametro();
    $oDaoAguaHidroDiametro->x15_coddiametro = $this->getCodigo();
    $oDaoAguaHidroDiametro->x15_diametro    = $this->getDescricao();

    if ($this->getCodigo()) {
      $oDaoAguaHidroDiametro->alterar($this->getCodigo());
    }

    if (!$this->getCodigo()) {
      $oDaoAguaHidroDiametro->incluir(null);
    }

    if ($oDaoAguaHidroDiametro->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Diâmetro do Hidrômetro.");
    }

    $this->setCodigo($oDaoAguaHidroDiametro->x15_coddiametro);

    return $this->getCodigo();
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if (!$this->getCodigo()) {
      throw new ParameterException("Código do Diâmetro não informado.");
    }

    $oDaoAguaHidroDiametro = new cl_aguahidrodiametro();
    $oDaoAguaHidroDiametro->excluir($this->getCodigo());

    if ($oDaoAguaHidroDiametro->erro_status == '0') {
      throw new DBException("Não foi possível excluir as informações do Diâmetro do Hidrômetro.");
    }

    return true;
  }
}