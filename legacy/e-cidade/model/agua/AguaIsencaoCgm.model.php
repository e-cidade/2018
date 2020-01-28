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

use ECidade\Tributario\Agua\Calculo\IsencaoFactory;

class AguaIsencaoCgm {

  /**
   * @var integer Código
   */
  private $iCodigo;

  /**
   * @var DBDate Data Inicial da Isenção
   */
  private $oDataInicial;

  /**
   * @var DBDate Data Final da Isenção
   */
  private $oDataFinal;

  /**
   * @var integer Código do CGM
   */
  private $iCodigoCgm;

  /**
   * @var CgmBase
   */
  private $oCgm;

  /**
   * @var integer Código do Tipo de Isenção
   */
  private $iCodigoTipoIsencao;

  /**
   * @var AguaTipoIsencao
   */
  private $oTipoIsencao;

  /**
   * @var string Número do Processo
   */
  private $sNumeroProcesso;

  /**
   * @var string Observação
   */
  private $sObservacoes;

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
   * @return DBDate
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @return DBDate
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @return int
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }

  /**
   * @param int $iCodigoCgm
   */
  public function setCodigoCgm($iCodigoCgm) {
    $this->iCodigoCgm = $iCodigoCgm;
  }

  /**
   * @return CgmBase
   */
  public function getCgm() {

    if (!$this->oCgm) {
      $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoCgm);
    }

    return $this->oCgm;
  }

  /**
   * @param CgmBase $oCgm
   */
  public function setCgm(CgmBase $oCgm) {

    $this->oCgm = $oCgm;
    $this->iCodigoCgm = $oCgm->getCodigo();
  }

  /**
   * @return int
   */
  public function getCodigoTipoIsencao() {
    return $this->iCodigoTipoIsencao;
  }

  /**
   * @return AguaTipoIsencao
   */
  public function getTipoIsencao() {

    if (!$this->oTipoIsencao && $this->iCodigoTipoIsencao) {
      $this->oTipoIsencao = new AguaTipoIsencao($this->iCodigoTipoIsencao);
    }
    return $this->oTipoIsencao;
  }

  /**
   * @param AguaTipoIsencao $oTipoIsencao [description]
   */
  public function setTipoIsencao(AguaTipoIsencao $oTipoIsencao) {

    $this->oTipoIsencao = $oTipoIsencao;
    $this->iCodigoTipoIsencao = $oTipoIsencao->getCodigo();
  }

  /**
   * @param int $iCodigoTipoIsencao
   */
  public function setCodigoTipoIsencao($iCodigoTipoIsencao) {
    $this->iCodigoTipoIsencao = $iCodigoTipoIsencao;
  }

  /**
   * @return string
   */
  public function getNumeroProcesso() {
    return $this->sNumeroProcesso;
  }

  /**
   * @param string $sProcesso
   */
  public function setNumeroProcesso($sProcesso) {
    $this->sNumeroProcesso = $sProcesso;
  }

  /**
   * @return string
   */
  public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * @param string $sObservacoes
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }

  /**
   * AguaIsencaoCgm constructor.
   * @param integer|null $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = (integer) $iCodigo;
    if (!$this->iCodigo) {
      return;
    }

    $oDaoAguaIsencaoCgm = new cl_aguaisencaocgm();
    $sSqlAguaIsencaoCgm = $oDaoAguaIsencaoCgm->sql_query_file($this->iCodigo);
    $rsAguaIsencaoCgm   = db_query($sSqlAguaIsencaoCgm);

    if (!$rsAguaIsencaoCgm || pg_num_rows($rsAguaIsencaoCgm) == 0) {
      throw new DBException("Não foi possível encontrar as informações de Isenção.");
    }

    $oAguaIsencao = db_utils::fieldsMemory($rsAguaIsencaoCgm, 0);

    $this->setCodigo($oAguaIsencao->x56_sequencial);
    $this->setCodigoCgm($oAguaIsencao->x56_cgm);
    $this->setCodigoTipoIsencao($oAguaIsencao->x56_aguaisencaotipo);
    $this->setDataInicial(new DBDate($oAguaIsencao->x56_datainicial));

    if ($oAguaIsencao->x56_datafinal) {
      $this->setDataFinal(new DBDate($oAguaIsencao->x56_datafinal));
    }

    if ($oAguaIsencao->x56_processo) {
      $this->setNumeroProcesso($oAguaIsencao->x56_processo);
    }

    if ($oAguaIsencao->x56_observacoes) {
      $this->setObservacoes($oAguaIsencao->x56_observacoes);
    }
  }

  /**
   * @return int
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function salvar() {

    if (!$this->iCodigoCgm) {
      throw new ParameterException('O campo Nome/Razão Social é de preenchimento obrigatório.');
    }

    if (!$this->iCodigoTipoIsencao) {
      throw new ParameterException('O campo Tipo de Isenção é de preenchimento obrigatório.');
    }

    if (!$this->oDataInicial) {
      throw new ParameterException('O campo Data Inicial é de preenchimento obrigatório.');
    }

    if ($this->oDataInicial && $this->oDataFinal && $this->oDataInicial->getTimeStamp() > $this->oDataFinal->getTimeStamp()) {
      throw new BusinessException('A Data Inicial não pode ser maior que a Data Final.');
    }

    $oDaoAguaIsencaoCgm = new cl_aguaisencaocgm();
    $oDaoAguaIsencaoCgm->x56_sequencial      = (int) $this->getCodigo();
    $oDaoAguaIsencaoCgm->x56_aguaisencaotipo = (int) $this->getCodigoTipoIsencao();
    $oDaoAguaIsencaoCgm->x56_cgm             = (int) $this->getCodigoCgm();
    $oDaoAguaIsencaoCgm->x56_datainicial     = $this->getDataInicial()->getDate();
    $oDaoAguaIsencaoCgm->x56_processo        = pg_escape_string($this->getNumeroProcesso());
    $oDaoAguaIsencaoCgm->x56_observacoes     = pg_escape_string($this->getObservacoes());

    if ($this->getDataFinal()) {
      $oDaoAguaIsencaoCgm->x56_datafinal = $this->getDataFinal()->getDate();
    }

    if ($this->getCodigo()) {
      $oDaoAguaIsencaoCgm->alterar($this->getCodigo());
    } else {
      $oDaoAguaIsencaoCgm->incluir(null);
    }

    if ($oDaoAguaIsencaoCgm->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações da Isenção.");
    }

    $this->setCodigo($oDaoAguaIsencaoCgm->x56_sequencial);

    return $this->getCodigo();
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if (!$this->getCodigo()) {
      throw new ParameterException("Código da Isenção não informado.");
    }

    $oDaoAguaIsencaoCgm = new cl_aguaisencaocgm();
    $oDaoAguaIsencaoCgm->excluir($this->getCodigo());

    if ($oDaoAguaIsencaoCgm->erro_status == '0') {
      throw new DBException("Não foi possível excluir as informações da Isenção.");
    }

    return true;
  }

  /**
   * @return \ECidade\Tributario\Agua\Calculo\Isencao\Isencao
   */
  public function getIsencao() {

    $iTipo = $this->getTipoIsencao()->getTipo();
    return IsencaoFactory::getPorTipo($iTipo);
  }
}
