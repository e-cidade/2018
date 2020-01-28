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
use ECidade\Tributario\Agua\Repository\Isencao as IsencaoRepository;

/**
 * Contrato para fornecimento de água e esgoto
 */
class AguaContrato {

  const RESPONSAVEL_PAGAMENTO_CONTRATO = 0;
  const RESPONSAVEL_PAGAMENTO_ECONOMIA = 1;
  const RESPONSAVEL_PAGAMENTO_CONDOMINIO = 2;

  const TIPO_CONTRATO_SEM_HIDROMETRO = 1;

  /**
   * Código sequencial
   *
   * @var integer
   */
  private $iCodigo;

  /**
   * Matrícula do imóvel
   *
   * @var AguaMatricula
   */
  private $oMatricula;

  /**
   * Código da matrícula do imóvel
   * @var integer
   */
  private $iCodigoMatricula;

  /**
   * Dia de vencimento das faturas
   *
   * @var integer
   */
  private $iDiaVencimento;

  /**
   * Data de validade do cadastro social
   *
   * @var DBDate
   */
  private $oDataValidadeCadastro;

  /**
   * Data de ínicio do contrato
   *
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * Data de encerramento do contrato
   *
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * Hidrômetros vinculados ao contrato
   *
   * @var AguaHidrometro[]
   */
  private $aHidrometros;

  /**
   * Número do NIS
   *
   * @var string
   */
  private $sNis;

  /**
   * Código do CGM
   *
   * @var integer
   */
  private $iCodigoCgm;

  /**
   * CGM
   *
   * @var CgmBase
   */
  private $oCgm;

  /**
   * @var integer Categoria de Consumo
   */
  private $iCodigoCategoriaConsumo;

  /**
   * @var AguaCategoriaConsumo
   */
  private $oCategoriaConsumo;

  /**
   * @var boolean Contrato de Condomínio
   */
  private $lCondominio;

  /**
   * @var integer Codigo do Tipo de Contrato
   */
  private $iCodigoTipoContrato;

  /**
   * @var AguaTipoContrato
   */
  private $oTipoContrato;

  /**
   * @var AguaContratoEconomia[]
   */
  private $aEconomias;

  /**
   * @var int
   */
  private $iResponsavelPagamento;

  /**
   * @var IsencaoRepository
   */
  private $oIsencaoRepository;

  /**
   * @var boolean
   */
  private $lEmitirOutrosDebitos;

  /**
   *
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    $this->oIsencaoRepository = new IsencaoRepository;

    if ($iCodigo) {

      $this->iCodigo = $iCodigo;
      $oDaoAguaContrato = new cl_aguacontrato;
      $sSqlDados = $oDaoAguaContrato->sql_query_file($this->iCodigo);
      $rsDados = db_query($sSqlDados);
      if (!$rsDados || pg_num_rows($rsDados) === 0) {
        throw new BusinessException('Não foi possível encontrar as informações do Contrato informado.');
      }

      $oContrato = pg_fetch_object($rsDados);
      $this->iCodigoMatricula        = $oContrato->x54_aguabase;
      $this->iDiaVencimento          = $oContrato->x54_diavencimento;
      $this->iCodigoTipoContrato     = $oContrato->x54_aguatipocontrato;
      $this->iCodigoCgm              = $oContrato->x54_cgm;
      $this->iCodigoCategoriaConsumo = $oContrato->x54_aguacategoriaconsumo;
      $this->lCondominio             = $oContrato->x54_condominio == 't';
      $this->lEmitirOutrosDebitos    = $oContrato->x54_emitiroutrosdebitos == 't';
      $this->sNis                    = $oContrato->x54_nis;
      $this->oDataInicial            = new DBDate($oContrato->x54_datainicial);
      $this->iResponsavelPagamento   = $oContrato->x54_responsavelpagamento;

      if ($oContrato->x54_datavalidadecadastro) {
        $this->oDataValidadeCadastro = new DBDate($oContrato->x54_datavalidadecadastro);
      }

      if ($oContrato->x54_datafinal) {
        $this->oDataFinal = new DBDate($oContrato->x54_datafinal);
      }
    }
  }

  /**
   * @param DateTime|null $oDataAtual
   * @return bool
   */
  public function isValido(DateTime $oDataAtual = null) {

    if (!$oDataAtual) {
      $oDataAtual = new DateTime(date('Y-m-d'));
    }

    /*
     * Altera as datas para o dia 1º, para que somente o ano e mês sejam comparados
     */
    $oDataAtual = $oDataAtual->setDate($oDataAtual->format('Y'), $oDataAtual->format('m'), 1);
    $oDataInicial = new DateTime($this->oDataInicial->getDate());
    $oDataInicial->setDate($this->oDataInicial->getAno(), $this->oDataInicial->getMes(), 1);

    /*
     * Caso a Data Inicial é maior que data atual, o contrato é inválido (não virgente)
     * Caso a Data Inicial é menor ou igual a data atual, o contrato é válido (virgente)
     */
    if ($oDataInicial->getTimestamp() > $oDataAtual->getTimestamp()) {
      return false;
    }

    if (!$this->oDataFinal) {
      return true;
    }

    $oDataFinal = new DateTime($this->oDataFinal->getDate());
    $oDataFinal->setDate($oDataFinal->format('Y'), $oDataFinal->format('m'), 1);

    if ($oDataFinal->getTimestamp() < $oDataAtual->getTimestamp()) {
      return false;
    }

    return true;
  }

  /**
   * Valida se o hidrômetro informado encontra-se vinculado a um contrato não encerrado.
   *
   * @param  integer $iCodigoHidrometro
   * @throws DBException
   * @return integer|null Código do contrato se o hidrômetro estiver vinculado a um contrato, nulo caso contrário.
   */
  private function hidrometroInstalado($iCodigoHidrometro) {

    $oDaoAguaContrato = new cl_aguacontrato;
    $oDataAtual = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));
    $aWhereVerificacao = array(
      "(x54_datafinal is null or x54_datafinal <= '{$oDataAtual->getDate()}')",
      "x55_aguahidromatric = {$iCodigoHidrometro}",
    );
    $sSqlVerificacao = $oDaoAguaContrato->sql_query_hidrometros('x54_sequencial', implode(' and ', $aWhereVerificacao));
    $rsVerificacao   = db_query($sSqlVerificacao);

    if (!$rsVerificacao) {
      throw new DBException('Não foi possível buscar as informações do hidrômetro informado.');
    }

    if (pg_num_rows($rsVerificacao) > 0) {
      return pg_fetch_object($rsVerificacao)->x54_sequencial;
    }

    return null;
  }

  /**
   * @throws BusinessException
   * @throws DBException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('É necessário que uma transação esteja aberta.');
    }

    $oDaoContratoLigacao = new cl_aguacontratoligacao;

    if ($this->iCodigo !== null) {
      $oDaoContratoLigacao->excluir(null, "x55_aguacontrato = {$this->iCodigo}");
    }

    if (empty($this->iCodigoCgm)) {
      throw new BusinessException('O campo Nome/Razão Social é de preenchimento obrigatório.');
    }

    if (empty($this->oDataInicial)) {
      throw new BusinessException('O campo Data Inicial é de preenchimento obrigatório.');
    }

    if ($this->oDataFinal) {

      if ($this->oDataFinal->getTimeStamp() < $this->oDataInicial->getTimeStamp()) {
        throw new BusinessException('A Data Final não pode ser inferior a Data Inicial.');
      }
    }

    if (count($this->getHidrometros()) === 0) {
      throw new BusinessException('O campo Hidrômetro é de preenchimento obrigatório.');
    }

    if (!$this->lCondominio && empty($this->iCodigoCategoriaConsumo)) {
      throw new BusinessException('O campo Categoria de Consumo é de preenchimento obrigatório.');
    }

    if (!$this->lCondominio) {
       $this->iResponsavelPagamento = (string) self::RESPONSAVEL_PAGAMENTO_CONTRATO;
    }

    $oDaoAguaContrato = new cl_aguacontrato;
    $oDaoAguaContrato->x54_sequencial           = $this->iCodigo;
    $oDaoAguaContrato->x54_datainicial          = $this->oDataInicial->getDate();
    $oDaoAguaContrato->x54_nis                  = $this->sNis;
    $oDaoAguaContrato->x54_cgm                  = $this->iCodigoCgm;
    $oDaoAguaContrato->x54_datafinal            = null;
    $oDaoAguaContrato->x54_responsavelpagamento = $this->iResponsavelPagamento;
    $oDaoAguaContrato->x54_emitiroutrosdebitos  = $this->lEmitirOutrosDebitos === true ? 't' : 'f';

    if ($this->oDataFinal) {
      $oDaoAguaContrato->x54_datafinal = $this->oDataFinal->getDate();
    }

    if ($this->lCondominio) {
      $oDaoAguaContrato->x54_condominio = $this->lCondominio;
    }

    if ($this->iCodigoCategoriaConsumo) {
      $oDaoAguaContrato->x54_aguacategoriaconsumo = $this->iCodigoCategoriaConsumo;
    }

    if ($this->iCodigoTipoContrato) {
      $oDaoAguaContrato->x54_aguatipocontrato = $this->iCodigoTipoContrato;
    }

    if ($this->iDiaVencimento) {
      $oDaoAguaContrato->x54_diavencimento = $this->iDiaVencimento;
    }

    if ($this->iCodigoMatricula) {
      $oDaoAguaContrato->x54_aguabase = $this->iCodigoMatricula;
    }

    if ($this->oDataValidadeCadastro) {
      $oDaoAguaContrato->x54_datavalidadecadastro = $this->oDataValidadeCadastro->getDate();
    }

    if ($this->iCodigo !== null) {
      $oDaoAguaContrato->alterar($this->iCodigo);
    } else {
      $oDaoAguaContrato->incluir(null);
    }

    if ($oDaoAguaContrato->erro_status == '0') {
      throw new DBException('Não foi possível salvar o contrato.');
    }

    foreach ($this->aHidrometros as $oHidrometro) {

      $iContrato = $this->hidrometroInstalado($oHidrometro->getCodigo());
      $lContratoSemHidrometro = $this->iCodigoTipoContrato == self::TIPO_CONTRATO_SEM_HIDROMETRO;

      if ($iContrato !== null && !$lContratoSemHidrometro) {

        $sMensagem  = "O hidrômetro {$oHidrometro->getCodigo()} já está vinculado ao contrato {$iContrato}.";
        throw new BusinessException($sMensagem);
      }

      $oDaoContratoLigacao->x55_aguahidromatric = $oHidrometro->getCodigo();
      $oDaoContratoLigacao->x55_aguacontrato    = $oDaoAguaContrato->x54_sequencial;
      $oDaoContratoLigacao->incluir(null);

      if ($oDaoContratoLigacao->erro_status == '0') {
        throw new DBException('Não foi possível vincular o hidrômetro ao contrato.');
      }
    }

    $this->iCodigo = $oDaoAguaContrato->x54_sequencial;
    return $this->iCodigo;
  }

  /**
   * @throws BusinessException
   * @throws DBException
   * @return boolean
   */
  public function excluir() {

    if (!$this->iCodigo) {
      throw new BusinessException('Nenhum contrato carregado.');
    }

    if (!db_utils::inTransaction()) {
      throw new DBException('É necessário que uma transação esteja aberta.');
    }

    $oDaoContratoLigacao = new cl_aguacontratoligacao;
    $oDaoContratoEconomia = new cl_aguacontratoeconomia;
    $oDaoContrato = new cl_aguacontrato;

    $oDaoContratoEconomia->excluir(null, "x38_aguacontrato = {$this->iCodigo}");
    $oDaoContratoLigacao->excluir(null, "x55_aguacontrato = {$this->iCodigo}");
    $oDaoContrato->excluir($this->iCodigo);

    if ($oDaoContratoEconomia->erro_status == '0') {
      throw new DBException('Não foi possível apagar as economias do contrato.');
    }
    if ($oDaoContratoLigacao->erro_status == '0') {
      throw new DBException('Não foi possível desvincular o hidrômetro do contrato.');
    }
    if ($oDaoContrato->erro_status == '0') {
      throw new DBException('Não foi possível apagar o contrato.');
    }

    return true;
  }

  /**
   * @param AguaHidrometro $oHidrometro
   */
  public function adicionarHidrometro(AguaHidrometro $oHidrometro) {

    if (!$this->aHidrometros) {
      $this->aHidrometros = array();
    }

    $this->aHidrometros[$oHidrometro->getCodigo()] = $oHidrometro;
  }

  /**
   * @param integer $iCodigo
   */
  public function removerHidrometro($iCodigo) {

    if ($this->aHidrometros) {
      unset($this->aHidrometros[$iCodigo]);
    }
  }

  /**
   * @return integer $iCodigo
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return AguaMatricula $oMatricula
   */
  public function getMatricula() {

    if ($this->iCodigoMatricula && !$this->oMatricula) {
      $this->oMatricula = new AguaMatricula($this->iCodigoMatricula);
    }

    return $this->oMatricula;
  }

  /**
   * @param AguaMatricula $oMatricula
   */
  public function setMatricula(AguaMatricula $oMatricula) {

    $this->oMatricula = $oMatricula;
    $this->iCodigoMatricula = $oMatricula->getMatricula();
  }

  /**
   * @return integer $iDiaVencimento
   */
  public function getDiaVencimento() {
    return $this->iDiaVencimento;
  }

  /**
   * @param integer $iDiaVencimento
   */
  public function setDiaVencimento($iDiaVencimento) {
    $this->iDiaVencimento = $iDiaVencimento;
  }

  /**
   * @return DBDate $oDataValidadeCadastro
   */
  public function getDataValidadeCadastro() {
    return $this->oDataValidadeCadastro;
  }

  /**
   * @param DBDate $oDataValidadeCadastro
   */
  public function setDataValidadeCadastro(DBDate $oDataValidadeCadastro) {
    $this->oDataValidadeCadastro = $oDataValidadeCadastro;
  }

  /**
   * @return DBDate $oDataFinal
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
   * @return DBDate $oDataInicial
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
   * @return AguaHidrometro[] $aHidrometros
   * @throws DBException
   * @deprecated Use getHidrometro.
   */
  public function getHidrometros() {

    if ($this->iCodigo && $this->aHidrometros === null) {

      $oDaoAguaContrato = new cl_aguacontrato;
      $sWhere  = "x55_aguacontrato = {$this->iCodigo}";
      $sCampos = 'x55_aguahidromatric';
      $sSqlHidrometros = $oDaoAguaContrato->sql_query_hidrometros($sCampos, $sWhere);
      $rsHidrometros   = db_query($sSqlHidrometros);

      if (!$rsHidrometros) {
        throw new DBException('Não foi possível buscar os hidrômetros para o contrato informado.');
      }

      while ($oHidrometro = pg_fetch_object($rsHidrometros)) {

        $iCodigo = (integer) $oHidrometro->x55_aguahidromatric;
        $this->adicionarHidrometro(new AguaHidrometro($iCodigo));
      }
    }

    return $this->aHidrometros;
  }

  /**
   * @return AguaHidrometro
   * @throws DBException
   */
  public function getHidrometro() {

    $aHidrometros = $this->getHidrometros();
    if (!$aHidrometros) {
      throw new DBException('Nenhum hidrômetro vinculado ao contrato.');
    }

    return current($aHidrometros);
  }

  /**
   * @return int
   * @throws DBException
   * @throws ParameterException
   */
  public function getQuantidadeEconomias() {

    if (!$this->iCodigo) {
      throw new ParameterException('Código de Contrato não informado.');
    }

    $oDaoContratoEconomias = new cl_aguacontratoeconomia();

    $rsEconomias = db_query($oDaoContratoEconomias->sql_query_file(
      null, 'count(x38_sequencial) as total', null, "x38_aguacontrato = {$this->iCodigo}"
    ));

    if (!$rsEconomias) {
      throw new DBException("Não foi possível obter as informações de Economias do Contrato.");
    }

    $oEconomias  = pg_fetch_object($rsEconomias);

    return ($oEconomias->total ? $oEconomias->total : 1);
  }

  /**
   * @return AguaContratoEconomia[]
   * @throws DBException
   */
  public function getEconomias() {

    if ($this->aEconomias === null && $this->iCodigo) {

      $oDaoContratoEconomia = new cl_aguacontratoeconomia;
      $sWhere      = "x38_aguacontrato = {$this->iCodigo}";
      $sOrder      = "x38_sequencial";
      $sSql        = $oDaoContratoEconomia->sql_query_file(null, 'x38_sequencial', $sOrder, $sWhere);
      $rsEconomias = db_query($sSql);

      if (!$rsEconomias) {
        throw new DBException('Não foi possível buscar as economias.');
      }

      $this->aEconomias = array();
      $iQuantidadeEconomias = pg_num_rows($rsEconomias);
      for ($iEconomia = 0; $iEconomia < $iQuantidadeEconomias; $iEconomia++) {

        $iCodigoEconomia = db_utils::fieldsMemory($rsEconomias, $iEconomia)->x38_sequencial;
        $oEconomia = new AguaContratoEconomia();
        $oEconomia->carregar($iCodigoEconomia);
        $this->aEconomias[$iCodigoEconomia] = $oEconomia;
      }
    }

    return $this->aEconomias;
  }

  /**
   * Se existir isenção válida, retorna uma instância do model.
   *
   * @return AguaIsencaoCgm|null
   * @throws ParameterException
   */
  public function getIsencao() {

    if (!$this->iCodigoCgm) {
      throw new ParameterException('CGM não informado.');
    }

    return $this->oIsencaoRepository->getIsencaoValida($this->iCodigoCgm);
  }

  /**
   * @return integer $iCodigoMatricula
   */
  public function getCodigoMatricula() {
    return $this->iCodigoMatricula;
  }

  /**
   * @param integer $iCodigoMatricula
   */
  public function setCodigoMatricula($iCodigoMatricula) {
    $this->iCodigoMatricula = $iCodigoMatricula;
  }

  /**
   * @return string $sNis
   */
  public function getNis() {
    return $this->sNis;
  }

  /**
   * @param string $sNis
   */
  public function setNis($sNis) {
    $this->sNis = $sNis;
  }

  /**
   * @return integer $iCodigoCgm
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }

  /**
   * @param integer $iCodigoCgm
   */
  public function setCodigoCgm($iCodigoCgm) {
    $this->iCodigoCgm = $iCodigoCgm;
  }

  /**
   * @return CgmBase $oCgm
   */
  public function getCgm() {

    if ($this->iCodigoCgm && !$this->oCgm) {
      $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoCgm);
    }
    return $this->oCgm;
  }

  /**
   * @param CgmBase $oCgm
   */
  public function setCgm(CgmBase $oCgm) {

    $this->iCodigoCgm = $oCgm->getCodigo();
    $this->oCgm = $oCgm;
  }

  /**
   * @return int
   */
  public function getCodigoCategoriaConsumo() {
    return $this->iCodigoCategoriaConsumo;
  }

  /**
   * @param int $iCodigoCategoriaConsumo
   */
  public function setCodigoCategoriaConsumo($iCodigoCategoriaConsumo) {
    $this->iCodigoCategoriaConsumo = $iCodigoCategoriaConsumo;
  }

  /**
   * @return AguaCategoriaConsumo
   * @throws ParameterException
   */
  public function getCategoriaConsumo() {

    if (!$this->iCodigoCategoriaConsumo) {
      throw new ParameterException("Código da Categoria de Consumo não informado.");
    }

    if (!$this->oCategoriaConsumo) {
      $this->oCategoriaConsumo = new AguaCategoriaConsumo($this->iCodigoCategoriaConsumo);
    }

    return $this->oCategoriaConsumo;
  }

  /**
   * @return bool
   */
  public function isCondominio() {
    return $this->lCondominio;
  }

  /**
   * @param bool $lCondominio
   */
  public function setCondominio($lCondominio) {
    $this->lCondominio = (bool) $lCondominio;
  }

  /**
   * @param integer $iTipoContrato
   */
  public function setCodigoTipoContrato($iTipoContrato) {
    $this->iCodigoTipoContrato = $iTipoContrato;
  }

  /**
   * @return int
   */
  public function getCodigoTipoContrato() {
    return $this->iCodigoTipoContrato;
  }

  /**
   * @return AguaTipoContrato
   */
  public function getTipoContrato() {

    if ($this->iCodigoTipoContrato && !$this->oTipoContrato) {
      $this->oTipoContrato = new AguaTipoContrato($this->iCodigoTipoContrato);
    }

    return $this->oTipoContrato;
  }

  /**
   * @param AguaTipoContrato $oTipoContrato
   */
  public function setTipoContrato(AguaTipoContrato $oTipoContrato) {

    $this->iCodigoTipoContrato = $oTipoContrato->getCodigo();
    $this->oTipoContrato = $oTipoContrato;
  }

  /**
   * @param bool $lEmitirOutrosDebitos
   */
  public function setEmitirOutrosDebitos($lEmitirOutrosDebitos) {
    $this->lEmitirOutrosDebitos = $lEmitirOutrosDebitos;
  }

  /**
   * @return bool
   */
  public function getEmitirOutrosDebitos() {
    return $this->lEmitirOutrosDebitos;
  }

  /**
   * @return bool
   * @throws DBException
   */
  public function hasServicoEsgoto() {
    return $this->hasCaracteristica(5200);
  }

  /**
   * @return bool
   * @throws DBException
   */
  public function hasServicoAgua() {
    return $this->hasCaracteristica(5300);
  }

  /**
   * @param $iCodigoCaracteristica
   * @return bool
   * @throws DBException
   */
  private function hasCaracteristica($iCodigoCaracteristica) {

    $sJoin = implode(' ', array(
      'inner join aguacontrato on x54_aguabase = x01_matric',
      'inner join aguabasecar on x30_matric = x01_matric',
      'inner join caracter on j31_codigo = x30_codigo'
    ));

    $sWhere = implode(' and ', array(
      "x54_aguabase = {$this->getCodigoMatricula()}",
      "j31_codigo = {$iCodigoCaracteristica}",
    ));

    $sSqlServicoEsgoto = "select x54_sequencial from aguabase {$sJoin} where {$sWhere} limit 1";
    $rsServicoEsgoto = db_query($sSqlServicoEsgoto);

    if (!$rsServicoEsgoto) {
      throw new DBException('Não foi possível obter as informações do Serviço de Esgoto do Contrato.');
    }

    return pg_num_rows($rsServicoEsgoto) == 0;
  }

  /**
   * @return bool
   * @throws DBException
   */
  public function hasConstrucao() {

    $sJoin = implode(' ', array(
      'inner join aguabase   on x01_matric = x54_aguabase',
      'inner join aguaconstr on x11_matric = x01_matric',
    ));

    $sWhere = "x54_sequencial = {$this->iCodigo}";
    $sSql = "select x54_sequencial from aguacontrato {$sJoin} where {$sWhere} limit 1";
    $rsResultado = db_query($sSql);

    if (!$rsResultado) {
      throw new \DBException('Não foi possível verificar se a matrícula possuí contrato.');
    }

    return pg_num_rows($rsResultado) > 0;
  }

  /**
   * Verifica se deve ou não ser feita cobrança para o contrato, dependendo
   * da última situação de corte cadastrada.
   *
   * @return boolean
   */
  public function deveRealizarCobranca() {

    $oSituacaoCorte = $this->getSituacaoCorte();
    if (!$oSituacaoCorte) {
      return true;
    }

    return ($oSituacaoCorte->x43_realizacobranca == 't' ? true : false);
  }

  /**
   * @return null|object Última situação de corte cadastrada.
   * @throws ParameterException
   */
  public function getSituacaoCorte() {

    if (!$this->iCodigoMatricula) {
      throw new \ParameterException('Código da Matrícula não informado.');
    }

    $sCampos = 'aguacortesituacao.*';

    $sJoin = implode(' ', array(
      'inner join aguacortemat     on x42_codcortemat = x41_codcortemat',
      'inner join aguacortesituacao on x42_codsituacao = x43_codsituacao'
    ));

    $sOrderBy = implode(', ', array(
      'x42_data desc',
      'x42_codmov desc'
    ));

    $sWhere = "x41_matric = {$this->getCodigoMatricula()}";
    $sSql = "select {$sCampos} from aguacortematmov {$sJoin} where {$sWhere} order by {$sOrderBy} limit 1";
    $rsSituacaoCorte = db_query($sSql);

    if (!pg_num_rows($rsSituacaoCorte)) {
      return null;
    }

    return pg_fetch_object($rsSituacaoCorte);
  }

  /**
   * @return int
   */
  public function getResponsavelPagamento() {
    return $this->iResponsavelPagamento;
  }

  /**
   * @param int $iResponsavelPagamento
   */
  public function setResponsavelPagamento($iResponsavelPagamento) {
    $this->iResponsavelPagamento = $iResponsavelPagamento;
  }

  /**
   * @return bool
   */
  public function isPagamentoCondominio() {
    return $this->lCondominio && $this->iResponsavelPagamento == self::RESPONSAVEL_PAGAMENTO_CONDOMINIO;
  }

  /**
   * @return bool
   */
  public function isPagamentoEconomia() {
    return $this->lCondominio && $this->iResponsavelPagamento == self::RESPONSAVEL_PAGAMENTO_ECONOMIA;
  }

  /**
   * @return IsencaoRepository
   */
  public function getIsencaoRepository() {
    return $this->oIsencaoRepository;
  }

  /**
   * @param IsencaoRepository $oIsencaoRepository
   */
  public function setIsencaoRepository($oIsencaoRepository) {
    $this->oIsencaoRepository = $oIsencaoRepository;
  }

  /**
   * @return AguaLeitura
   * @throws BusinessException
   */
  public function getUltimaLeituraAtiva() {

    $sWhere = implode(' and ', array(
      "x55_aguacontrato = {$this->iCodigo}",
      "x21_status = " . AguaLeitura::STATUS_ATIVA,
    ));

    $sJoin = 'inner join aguacontratoligacao on x21_codhidrometro = x55_aguahidromatric';
    $sOrderBy = implode(', ', array(
      'x21_exerc desc',
      'x21_mes desc'
    ));

    $sSql = "select x21_codleitura from agualeitura {$sJoin} where {$sWhere} order by {$sOrderBy} limit 1";
    $rsLeitura = db_query($sSql);

    if (!$rsLeitura || pg_num_rows($rsLeitura) == 0) {
      throw new BusinessException('Nenhuma leitura encontrada para o hidrômetro.');
    }

    $iCodigoLeitura = pg_fetch_result($rsLeitura, 0, 'x21_codleitura');
    $oAguaLeitura = new AguaLeitura($iCodigoLeitura);

    return $oAguaLeitura;
  }

  /**
   * @param int $iAno
   * @param int $iMes
   *
   * @return string
   * @throws BusinessException
   * @throws DBException
   */
  public function getDataVencimento($iAno, $iMes) {

    if (!$this->iCodigoMatricula) {
      throw new BusinessException('Matrícula não encontrada.');
    }

    $rsDataVencimento = db_query(
      "select fc_agua_datavencimento({$iAno}, {$iMes}, {$this->iCodigoMatricula}) as data_vencimento"
    );

    if (!$rsDataVencimento) {
      throw new DBException('Não foi possível encontrar Data de Vencimento.');
    }

    return pg_fetch_result($rsDataVencimento, 0, 'data_vencimento');
  }
}
