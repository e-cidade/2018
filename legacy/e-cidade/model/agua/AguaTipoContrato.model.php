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
 * Tipo de Contrato
 */
class AguaTipoContrato {

  /**
   * @var integer Código Sequencial
   */
  private $iCodigo;

  /**
   * @var string Descrição do Tipo
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
   * AguaTipoContrato constructor.
   * @param string|null $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (!$this->iCodigo) {
      return;
    }

    $oDaoAguaTipoContrato = new cl_aguatipocontrato();
    $sSqlAguaTipoContrato = $oDaoAguaTipoContrato->sql_query_file($this->iCodigo);
    $rsAguaTipoContrato = db_query($sSqlAguaTipoContrato);

    if (!$rsAguaTipoContrato || pg_num_rows($rsAguaTipoContrato) == 0) {
      throw new DBException('Não foi possível encontrar o Tipo de Contrato.');
    }

    $oTipoContrato = db_utils::fieldsMemory($rsAguaTipoContrato, 0);

    $this->iCodigo    = $oTipoContrato->x39_sequencial;
    $this->sDescricao = $oTipoContrato->x39_descricao;
  }

  /**
   * @throws DBException
   */
  public function salvar() {

    if (!$this->getDescricao()) {
      throw new ParameterException('O campo Descrição é de preenchimento obrigatório.');
    }

    $oDaoAguaTipoContrato = new cl_aguatipocontrato();
    $oDaoAguaTipoContrato->x39_sequencial = $this->getCodigo();
    $oDaoAguaTipoContrato->x39_descricao = pg_escape_string($this->getDescricao());

    if ($this->getCodigo()) {
      $oDaoAguaTipoContrato->alterar($this->getCodigo());
    } else {
      $oDaoAguaTipoContrato->incluir(null);
    }

    if ($oDaoAguaTipoContrato->erro_status == '0') {
      throw new DBException('Não foi possível salvar as informações do Tipo de Contrato');
    }

    $this->setCodigo($oDaoAguaTipoContrato->x39_sequencial);
    return $this->getCodigo();
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if (!$this->getCodigo()) {
      throw new ParameterException('Código do Tipo de Contrato não informado.');
    }

    $oDaoAguaTipoContrato = new cl_aguatipocontrato();
    $oDaoAguaTipoContrato->excluir($this->getCodigo());

    if ($oDaoAguaTipoContrato->erro_status == '0') {
      throw new DBException("Não foi possível excluir o Tipo de Contrato.");
    }

    return true;
  }
}