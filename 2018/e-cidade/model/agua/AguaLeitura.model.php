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

use ECidade\Tributario\Agua\Entity\Leitura\Situacao;

class AguaLeitura {

  const TIPO_MANUAL      = 1;
  const TIPO_EXPORTACAO  = 2;
  const TIPO_IMPORTACAO  = 3;

  const STATUS_ATIVA     = 1;
  const STATUS_INATIVA   = 2;
  const STATUS_CANCELADA = 3;

  const SITUACAO_INATIVA = 2;
  const SITUACAO_ATIVA   = 1;

  const REGRA_NORMAL                = 0;
  const REGRA_SEM_LEITURA_SEM_SALDO = 1;
  const REGRA_CANCELAMENTO          = 2;
  const REGRA_SEM_LEITURA_COM_SALDO = 3;
  const REGRA_MEDIA_ULTIMOS_MESES   = 4;
  const REGRA_MEDIA_PENALIDADE      = 5;

  /** @var int */
  private $iCodigo;

  /** @var int */
  private $iMes;

  /** @var int */
  private $iAno;

  /** @var int */
  private $iLeitura;

  /** @var DBDate */
  private $oDataInclusao;

  /** @var DBDate */
  private $oDataLeitura;

  /** @var int */
  private $iSituacao;

  /** @var int */
  private $iConsumo;

  /** @var int */
  private $iExcesso;

  /** @var bool */
  private $lHidrometroVirou;

  /** @var int */
  private $iTipo;

  /** @var int */
  private $iStatus;

  /** @var int */
  private $nSaldo;

  /** @var int */
  private $iCodigoUsuario;

  /** @var int */
  private $iCodigoLeiturista;

  /** @var int */
  private $iCodigoHidrometro;

  /* @var Situacao */
  private $oSituacao;

  /** @var int */
  private $iCodigoContrato;

  /**
   * AguaLeitura constructor.
   * @param integer $iCodigo
   * @throws DBException
   * @throws ParameterException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;

    if ($this->iCodigo) {

      $oDaoAguaLeitura = new cl_agualeitura();
      $sSqlAguaLeitura = $oDaoAguaLeitura->sql_query_file($iCodigo);
      $rsAguaLeitura   = $oDaoAguaLeitura->sql_record($sSqlAguaLeitura);

      if (!$rsAguaLeitura && $oDaoAguaLeitura->numrows == '0') {
        throw new DBException("Não foi possível encontrar as informações de leitura.");
      }

      $oLeitura = db_utils::fieldsMemory($rsAguaLeitura, 0);

      $this->iCodigoHidrometro = $oLeitura->x21_codhidrometro;
      $this->iAno              = $oLeitura->x21_exerc;
      $this->iMes              = $oLeitura->x21_mes;
      $this->iSituacao         = $oLeitura->x21_situacao;
      $this->iCodigoLeiturista = $oLeitura->x21_numcgm;
      $this->iCodigoUsuario    = $oLeitura->x21_usuario;
      $this->iLeitura          = $oLeitura->x21_leitura;
      $this->iConsumo          = $oLeitura->x21_consumo;
      $this->iExcesso          = $oLeitura->x21_excesso;
      $this->lHidrometroVirou  = $oLeitura->x21_virou == 't' ? true : false;
      $this->iTipo             = $oLeitura->x21_tipo;
      $this->iStatus           = $oLeitura->x21_status;
      $this->nSaldo            = $oLeitura->x21_saldo;
      $this->iCodigoContrato   = $oLeitura->x21_aguacontrato;

      if ($oLeitura->x21_dtleitura) {
        $this->oDataLeitura = new DBDate($oLeitura->x21_dtleitura);
      }

      if ($oLeitura->x21_dtinc) {
        $this->oDataInclusao = new DBDate($oLeitura->x21_dtinc);
      }

      $oStdSituacao = db_utils::getRowFromDao(new cl_aguasitleitura, array($this->iSituacao));
      if (!$oStdSituacao) {
        throw new \DBException('Situação de Leitura não foi encontrada.');
      }

      $oSituacao = new Situacao;
      $oSituacao->setCodigo($oStdSituacao->x17_codigo);
      $oSituacao->setDescricao($oStdSituacao->x17_descr);
      $oSituacao->setRegra($oStdSituacao->x17_regra);

      $this->oSituacao = $oSituacao;
    }
  }


  /**
   * @return int
   * @throws DBException
   */
  public function salvar() {

    $oDaoAguaLeitura = new cl_agualeitura();
    $oDaoAguaLeitura->x21_codhidrometro = $this->getCodigoHidrometro();
    $oDaoAguaLeitura->x21_exerc         = $this->getAno();
    $oDaoAguaLeitura->x21_mes           = $this->getMes();
    $oDaoAguaLeitura->x21_situacao      = $this->getSituacao();
    $oDaoAguaLeitura->x21_numcgm        = $this->getCodigoLeiturista();
    $oDaoAguaLeitura->x21_usuario       = $this->getCodigoUsuario();
    $oDaoAguaLeitura->x21_tipo          = $this->getTipo();
    $oDaoAguaLeitura->x21_status        = $this->getStatus();
    $oDaoAguaLeitura->x21_leitura       = $this->getLeitura();
    $oDaoAguaLeitura->x21_consumo       = $this->getConsumo();
    $oDaoAguaLeitura->x21_excesso       = $this->getExcesso();
    $oDaoAguaLeitura->x21_saldo         = $this->getSaldo();

    if ($this->getDataInclusao()) {
      $oDaoAguaLeitura->x21_dtinc = $this->getDataInclusao()->getDate();
    }

    if ($this->getDataLeitura()) {
      $oDaoAguaLeitura->x21_dtleitura = $this->getDataLeitura()->getDate();
    }

    if ($this->getHidrometroVirou() !== null) {
      $oDaoAguaLeitura->x21_virou = $this->getHidrometroVirou() ? 'true' : 'false';
    }

    if ($this->getCodigoContrato()) {
      $oDaoAguaLeitura->x21_aguacontrato = $this->getCodigoContrato();
    }

    if ($this->iCodigo) {

      $oDaoAguaLeitura->x21_codleitura = $this->iCodigo;
      $oDaoAguaLeitura->alterar($this->iCodigo);
    }

    if (!$this->iCodigo) {

      $oDaoAguaLeitura->incluir(null);
      $this->iCodigo = $oDaoAguaLeitura->x21_codleitura;
    }

    if ($oDaoAguaLeitura->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações da Leitura.");
    }

    return $this->iCodigo;
  }

  /**
   * @param $sMotivo
   * @throws DBException
   * @throws ParameterException
   */
  public function cancelar($sMotivo) {

    if (!$this->iCodigo) {
      throw new ParameterException("Código da Leitura não foi informado.");
    }

    $oDaoAguaLeituraSaldoUtilizado = new cl_agualeiturasaldoutilizado;
    $oDaoAguaLeituraSaldoUtilizado->excluir(null, "x34_agualeitura = {$this->iCodigo}");

    if ($oDaoAguaLeituraSaldoUtilizado->erro_status == '0') {
      throw new DBException("Não foi possível excluir o Saldo Compensando desta Leitura.");
    }

    $oDaoAguaLeituraCancela = new cl_agualeituracancela();
    $oDaoAguaLeituraCancela->x47_agualeitura = $this->getCodigo();
    $oDaoAguaLeituraCancela->x47_usuario     = db_getsession('DB_id_usuario');
    $oDaoAguaLeituraCancela->x47_data        = date("d/m/Y");
    $oDaoAguaLeituraCancela->x47_hora        = date("H:i");
    $oDaoAguaLeituraCancela->x47_motivo      = $sMotivo;
    $oDaoAguaLeituraCancela->incluir(null);

    if ($oDaoAguaLeituraCancela->erro_status == '0') {
      throw new DBException("Não foi possível incluir o Cancelamento da Leitura.");
    }

    $this->setStatus(self::STATUS_CANCELADA);
    $this->salvar();
  }

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
   * @return int
   */
  public function getMes() {
    return $this->iMes;
  }

  /**
   * @param int $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param int $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return int
   */
  public function getLeitura() {
    return $this->iLeitura;
  }

  /**
   * @param int $iLeitura
   */
  public function setLeitura($iLeitura) {
    $this->iLeitura = $iLeitura;
  }

  /**
   * @return DBDate
   */
  public function getDataInclusao() {
    return $this->oDataInclusao;
  }

  /**
   * @param DBDate $oDataInclusao
   */
  public function setDataInclusao($oDataInclusao) {
    $this->oDataInclusao = $oDataInclusao;
  }

  /**
   * @return DBDate
   */
  public function getDataLeitura() {
    return $this->oDataLeitura;
  }

  /**
   * @param DBDate $oDataLeitura
   */
  public function setDataLeitura($oDataLeitura) {
    $this->oDataLeitura = $oDataLeitura;
  }

  /**
   * @return int
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * @param Situacao $oSituacao
   */
  public function setSituacaoLeitura(Situacao $oSituacao) {
    $this->oSituacao = $oSituacao;
  }

  /**
   * @return Situacao
   */
  public function getSituacaoLeitura() {
    return $this->oSituacao;
  }

  /**
   * @param int $iSituacao
   * @throws ParameterException
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * @return int
   */
  public function getConsumo() {
    return $this->iConsumo;
  }

  /**
   * @param int $iConsumo
   */
  public function setConsumo($iConsumo) {
    $this->iConsumo = $iConsumo;
  }

  /**
   * @return int
   */
  public function getExcesso() {
    return $this->iExcesso;
  }

  /**
   * @param int $iExcesso
   */
  public function setExcesso($iExcesso) {
    $this->iExcesso = $iExcesso;
  }

  /**
   * @return boolean
   */
  public function getHidrometroVirou() {
    return $this->lHidrometroVirou;
  }

  /**
   * @param boolean $lHidrometroVirou
   */
  public function setHidrometroVirou($lHidrometroVirou) {
    $this->lHidrometroVirou = (boolean) $lHidrometroVirou;
  }

  /**
   * @return int
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * @param int $iTipo
   * @throws ParameterException
   */
  public function setTipo($iTipo) {

    $aTipos = array(
      self::TIPO_MANUAL,
      self::TIPO_EXPORTACAO,
      self::TIPO_IMPORTACAO
    );

    if (!in_array($iTipo, $aTipos)) {
      throw new ParameterException("O Tipo informado é inválido.");
    }

    $this->iTipo = $iTipo;
  }

  /**
   * @return int
   */
  public function getStatus() {
    return $this->iStatus;
  }

  /**
   * @param int $iStatus
   * @throws ParameterException
   */
  public function setStatus($iStatus) {

    $aStatus = array(
      self::STATUS_ATIVA,
      self::SITUACAO_INATIVA,
      self::STATUS_CANCELADA
    );

    if (!in_array($iStatus, $aStatus)) {
      throw new ParameterException("O Status informado é inválido.");
    }

    $this->iStatus = $iStatus;
  }

  /**
   * @return int
   */
  public function getSaldo() {
    return $this->nSaldo;
  }

  /**
   * @param int $nSaldo
   */
  public function setSaldo($nSaldo) {
    $this->nSaldo = $nSaldo;
  }

  /**
   * @return int
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * @param int $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @return int
   */
  public function getCodigoLeiturista() {
    return $this->iCodigoLeiturista;
  }

  /**
   * @param int $iCodigoLeiturista
   */
  public function setCodigoLeiturista($iCodigoLeiturista) {
    $this->iCodigoLeiturista = $iCodigoLeiturista;
  }

  /**
   * @return int
   */
  public function getCodigoHidrometro() {
    return $this->iCodigoHidrometro;
  }

  /**
   * @param int $iCodigoHidrometro
   */
  public function setCodigoHidrometro($iCodigoHidrometro) {
    $this->iCodigoHidrometro = $iCodigoHidrometro;
  }

  /**
   * @param $iCodigoContrato
   */
  public function setCodigoContrato($iCodigoContrato) {
    $this->iCodigoContrato = $iCodigoContrato;
  }

  /**
   * @return int
   */
  public function getCodigoContrato() {
    return $this->iCodigoContrato;
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function foiEmitidaColetor() {

    if (!$this->iCodigo) {
      throw new ParameterException("Código da leitura não informada.");
    }

    $sSqlDadosExportados = "
      select x50_contaimpressa from aguacoletorexportadados
        inner join aguacoletorexportadadosleitura on x51_aguacoletorexportadados = x50_sequencial
        inner join agualeitura on x51_agualeitura = x21_codleitura
      where
        x21_codleitura = {$this->iCodigo} and
        x50_contaimpressa = 1
      limit 1
    ";

    $rsDadosExportados = db_query($sSqlDadosExportados);

    if (!$rsDadosExportados) {
      throw new DBException("Não foi possível encontrar informações da importação da leitura.");
    }

    return (boolean) pg_numrows($rsDadosExportados);
  }

  /**
   * @param $iCodigoMatricula
   * @return integer
   * @throws DBException
   */
  public static function getContratoPorMatricula($iCodigoMatricula) {

    $sSql = "
      select
        x54_sequencial
      from
        aguacontrato
      inner join aguacontratoligacao on x55_aguacontrato  = x54_sequencial
      inner join aguahidromatric     on x04_codhidrometro = x55_aguahidromatric
      where
        x54_aguabase = {$iCodigoMatricula}
    ";
    $rsDados = db_query($sSql);


    if (!$rsDados) {
      throw new DBException('Não foi possível encontrar o contrato da matrícula informada.');
    }

    if (pg_num_rows($rsDados) === 0) {
      return null;
    }

    return pg_fetch_object($rsDados)->x54_sequencial;
  }
}
