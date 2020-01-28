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

namespace ECidade\Tributario\Agua\Calculo;

use AguaContrato;
use AguaContratoEconomia;
use AguaTipoConsumo;
use cl_arrecad;
use DateTime;
use ECidade\Tributario\Agua\Entity\Calculo\Calculo as CalculoEntity;
use ECidade\Tributario\Agua\Entity\Calculo\Valor as ValorEntity;
use ECidade\Tributario\Agua\Repository\Calculo as CalculoRepository;
use DBLog;
use ParameterException;
use BusinessException;
use DBException;

/**
 * Processa o c�lculo de tarifas
 */
class Calculo {

  /**
   * @todo Criar configura��o para cada Tipo de Consumo e remover sequ�ncias fixas.
   */
  const SERVICO_BASICO_AGUA = 4;

  const SERVICO_BASICO_ESGOTO = 5;

  const SERVICO_AGUA = 6;

  const SERVICO_ESGOTO = 7;

  const CALCULO_TARIFA = 2;

  /**
   * @var CalculoRepository
   */
  private $oRepository;

  /**
   * @var AguaContrato
   */
  private $oContrato;

  /**
   * @var integer
   */
  private $iCodigoEconomia;

  /**
   * @var int
   */
  private $iMesInicial;

  /**
   * @var int
   */
  private $iMesFinal;

  /**
   * @var int
   */
  private $iTipoDebito;

  /**
   * @var int
   */
  private $iCodigoUsuario;

  /**
   * @var int
   */
  private $iAno;

  /**
   * @var DBLog
   */
  private $oLogger;

  /**
   * @var array
   */
  private $aNumpres = array();

  /**
   * @var Recalculo
   */
  private $oRecalculo;

  public function __construct() {
    $this->oRepository = new CalculoRepository();
  }

  /**
   * @return AguaContrato
   */
  public function getContrato() {
    return $this->oContrato;
  }

  /**
   * @param AguaContrato $oContrato
   */
  public function setContrato($oContrato) {
    $this->oContrato = $oContrato;
  }

  /**
   * @return int
   */
  public function getMesInicial() {
    return $this->iMesInicial;
  }

  /**
   * @param int $iMesInicial
   */
  public function setMesInicial($iMesInicial) {
    $this->iMesInicial = $iMesInicial;
  }

  /**
   * @return int
   */
  public function getMesFinal() {
    return $this->iMesFinal;
  }

  /**
   * @param int $iMesFinal
   */
  public function setMesFinal($iMesFinal) {
    $this->iMesFinal = $iMesFinal;
  }

  /**
   * @return int
   */
  public function getTipoDebito() {
    return $this->iTipoDebito;
  }

  /**
   * @param int $iTipoDebito
   */
  public function setTipoDebito($iTipoDebito) {
    $this->iTipoDebito = $iTipoDebito;
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
   * @return DBLog
   */
  public function getLogger() {
    return $this->oLogger;
  }

  /**
   * @param DBLog $oLogger
   */
  public function setLogger(\DBLog $oLogger) {
    $this->oLogger = $oLogger;
  }

  private function log($sMensagem) {

    $sEconomia = $this->iCodigoEconomia ? " - Economia: {$this->iCodigoEconomia}" : null;

    $this->oLogger->escreverLog("Contrato: {$this->oContrato->getCodigo()}{$sEconomia} - {$sMensagem}");
  }

  /**
   * @throws ParameterException
   */
  private function validarParametros() {

    if (!$this->oContrato) {
      throw new ParameterException('Contrato n�o informado.');
    }

    if (!$this->iMesInicial || !$this->iMesFinal) {
      throw new ParameterException('M�s Inicial/Final n�o informado.');
    }

    if ($this->iMesFinal < $this->iMesInicial) {
      throw new ParameterException('M�s Inicial n�o pode ser maior que M�s Final.');
    }

    if (!$this->iAno) {
      throw new ParameterException('Ano n�o informado.');
    }

    if (!$this->iCodigoUsuario) {
      throw new ParameterException('Usu�rio n�o informado.');
    }

    if (!$this->iTipoDebito) {
      throw new ParameterException('Tipo de D�bito n�o informado.');
    }

    if (!$this->oLogger) {
      throw new ParameterException('Logger n�o informado.');
    }
  }

  /**
   * @param $iAno
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  private function validarCategoriasConsumo($iAno) {

    $sSqlCategoriaConsumo = "
      select x13_sequencial from aguacategoriaconsumo
      inner join aguaestruturatarifaria on x37_aguaconsumotipo = x13_sequencial
      where x13_exercicio = {$iAno} limit 1
    ";
    $rsCategoriaConsumo = db_query($sSqlCategoriaConsumo);

    if (!$rsCategoriaConsumo) {
      throw new DBException('N�o foi poss�vel obter informa��es de Categoria de Consumo.');
    }

    if (!pg_num_rows($rsCategoriaConsumo)) {
      throw new BusinessException(
        "N�o existe nenhuma configura��o de Categoria de Consumo/Estrutura Tarif�ria para {$iAno}."
      );
    }

    return true;
  }

  /**
   * Apaga os d�bitos e c�lculos do contrato para o ano/m�s antes de realizar o c�lculo.
   *
   * @param integer $iMes
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  private function apagarDebitos($iMes) {

    if (!$this->oContrato) {
      throw new ParameterException('Contrato n�o informado.');
    }

    $sWhereReceitas = implode(' and ', array(
      "x22_aguacontrato = {$this->oContrato->getCodigo()}",
      "x22_exerc = {$this->iAno}",
      "x22_mes = {$iMes}",
      "x22_numpre = k00_numpre",
    ));
    $rsReceitas = db_query("delete from arrecad using aguacalc where {$sWhereReceitas}");
    if (!$rsReceitas) {
      throw new DBException("N�o foi poss�vel excluir os registros de receitas de {$iMes}/{$this->getAno()}");
    }

    return true;
  }

  /**
   * @param integer $iMes
   * @throws DBException
   * @return bool
   */
  private function apagarCalculos($iMes) {

    $sWhereCalculoValores = implode(' and ', array(
      "x22_aguacontrato = {$this->oContrato->getCodigo()}",
      "x22_exerc = {$this->iAno}",
      "x22_mes = {$iMes}",
      "x23_codcalc = x22_codcalc",
    ));

    $rsCalculo = db_query("delete from aguacalcval using aguacalc where {$sWhereCalculoValores}");
    if (!$rsCalculo) {
      throw new DBException('N�o foi poss�vel excluir os registros de c�lculo.');
    }

    $sWhereCalculo = implode(' and ', array(
      "x22_aguacontrato = {$this->oContrato->getCodigo()}",
      "x22_exerc = {$this->iAno}",
      "x22_mes = {$iMes}",
    ));

    $rsCalculo = db_query("delete from aguacalc where {$sWhereCalculo}");
    if (!$rsCalculo) {
      throw new DBException('N�o foi poss�vel excluir os registros de c�lculo.');
    }

    return true;
  }

  /**
   * @param \stdClass $oDebito
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  private function persistirReceita(\stdClass $oDebito) {

    $lTemParcelamento = $this->hasParcelamento(
      $oDebito->parcela,
      $this->getTipoDebito(),
      $oDebito->codigo_receita,
      $oDebito->codigo_processamento
    );

    if ($lTemParcelamento) {
      throw new BusinessException(
        'Recalculado, mas os d�bitos n�o foram regerados devido a exist�ncia de parcelamento.'
      );
    }

    $oDaoArrecad = new cl_arrecad();
    $oDataOperacao = new DateTime();

    $oDaoArrecad->k00_dtvenc = $oDebito->data_vencimento;
    $oDaoArrecad->k00_hist   = $oDebito->codigo_historico;
    $oDaoArrecad->k00_numpre = $oDebito->codigo_processamento;
    $oDaoArrecad->k00_receit = $oDebito->codigo_receita;
    $oDaoArrecad->k00_numpar = $oDebito->parcela;
    $oDaoArrecad->k00_numcgm = $oDebito->codigo_responsavel;
    $oDaoArrecad->k00_valor  = $oDebito->valor;
    $oDaoArrecad->k00_numtot = 12;
    $oDaoArrecad->k00_dtoper = $oDataOperacao->format('Y-m-d');
    $oDaoArrecad->k00_tipo   = $this->getTipoDebito();
    $oDaoArrecad->k00_numdig = '0';
    $oDaoArrecad->k00_tipojm = '0';
    $oDaoArrecad->incluir();

    if ($oDaoArrecad->erro_status == '0') {
      throw new DBException('N�o foi poss�vel salvar as informa��es de D�bito do C�lculo.');
    }

    return true;
  }

  /**
   * Verifica a exist�ncia de parcelamentos para um d�bito.
   *
   * @todo Verificar se deve ser movido para classe Recalculo.
   *
   * @param integer $iParcela
   * @param integer $iTipoDebito
   * @param integer $iCodigoReceita
   * @param integer $iCodigoProcessamento
   * @return boolean
   * @throws DBException
   */
  private function hasParcelamento($iParcela, $iTipoDebito, $iCodigoReceita, $iCodigoProcessamento) {

    $sSqlParcelamentoDivida = "
      select * from (
        select k00_numpre
          from arrecant
        where k00_numpre = {$iCodigoProcessamento}
          and k00_numpar = {$iParcela}
          and k00_tipo   = {$iTipoDebito}
          and k00_receit = {$iCodigoReceita}
        union all
        select k10_numpre
          from divold
        where k10_numpre  = {$iCodigoProcessamento}
          and k10_numpar  = {$iParcela}
          and k10_receita = {$iCodigoReceita}
      ) as parcelamento_divida
    ";
    $rsParcelamentoDivida = db_query($sSqlParcelamentoDivida);

    if (!$rsParcelamentoDivida) {
      throw new DBException('N�o foi poss�vel verificar se o D�bito foi Parcelado/Importado para D�vida.');
    }

    return (bool) pg_num_rows($rsParcelamentoDivida);
  }

  /**
   * Gera um novo Numpre.
   *
   * @return string|integer
   * @throws DBException
   */
  private function gerarCodigoProcessamento() {

    $rsCodigoProcessamento = db_query("select nextval('numpref_k03_numpre_seq')::integer as numpre");
    if (!$rsCodigoProcessamento) {
      throw new DBException('N�o foi poss�vel gerar o C�digo de Processamento.');
    }

    return pg_fetch_result($rsCodigoProcessamento, 0, 'numpre');
  }

  /**
   * @param integer $iConsumo
   * @return array
   * @throws BusinessException
   */
  private function getCalculosFinanceiros($iConsumo) {

    $oProcessamento = ProcessamentoFactory::create($this->oContrato);
    $oProcessamento->setConsumo($iConsumo);
    $oProcessamento->setCodigoTipoConsumoIsencao(self::SERVICO_AGUA);

    $aCalculos = $oProcessamento->processar();
    if (!$aCalculos) {
      throw new BusinessException(
        "N�o foi poss�vel realizar o calculo para o contrato ({$this->oContrato->getCodigo()})."
      );
    }

    return $aCalculos;
  }

  /**
   * Retorna o c�lculo para o m�s/ano de refer�ncia, para o contrato ou economia.
   *
   * @param integer $iMes
   * @param integer $iCodigoEconomia
   *
   * @return CalculoEntity
   */
  private function getCalculo($iMes, $iCodigoEconomia = null) {

    $aFiltros = array(
      "x22_exerc = {$this->iAno}",
      "x22_mes = {$iMes}",
      "x22_aguacontrato = {$this->oContrato->getCodigo()}",
    );

    if ($iCodigoEconomia) {
      $aFiltros[] = "x22_aguacontratoeconomia = {$iCodigoEconomia}";
    }

    $oCalculo = $this->oRepository->findOneBy($aFiltros);
    if (!$oCalculo) {
      $oCalculo = new CalculoEntity;
    }

    return $oCalculo;
  }

  /**
   * @param integer $iCodigoTipoConsumo
   * @param float $nValor
   *
   * @return boolean
   */
  private function validarExcecoes($iCodigoTipoConsumo, $nValor) {

    if (!$this->oContrato->isCondominio()) {

      if (!$this->oContrato->hasServicoEsgoto() && $iCodigoTipoConsumo == self::SERVICO_BASICO_ESGOTO) {
        $this->log('Contrato dispensado do pagamento da tarifa b�sica de esgoto por n�o ter esse servi�o.');
        return false;
      }

      if (!$this->oContrato->hasServicoEsgoto() && $iCodigoTipoConsumo == self::SERVICO_ESGOTO) {
        $this->log('Contrato dispensado do pagamento da tarifa de percentual de esgoto por n�o ter esse servi�o.');
        return false;
      }

      if (!$this->oContrato->hasServicoAgua() && $iCodigoTipoConsumo == self::SERVICO_AGUA) {
        $this->log('Contrato dispensado do pagamento da tarifa de consumo de �gua por n�o ter esse servi�o.');
        return false;
      }

      if (!$this->oContrato->hasServicoAgua() && $iCodigoTipoConsumo == self::SERVICO_BASICO_AGUA) {
        $this->log('Contrato dispensado do pagamento da tarifa b�sica de �gua por n�o ter esse servi�o.');
        return false;
      }
    }

    if ($nValor <= 0) {
      return false;
    }

    return true;
  }

  /**
   * Caso seja um rec�lculo, verifica a exist�ncia de pagamentos, pagamentos parciais, suspens�es ou cancelamentos
   * nos d�bitos vinculados ao c�lculo do m�s/ano de refer�ncia.
   *
   * @param int $iMes
   * @return bool
   */
  private function validarRecalculo($iMes) {

    $oRecalculo = $this->oRecalculo;

    $aDebitosPagamento = $oRecalculo->getPagamentos();
    if ($aDebitosPagamento) {

      $this->log("Rec�lculo n�o efetuado. Os seguintes d�bitos possuem pagamento: " . implode(', ', $aDebitosPagamento));
      return false;
    }

    $aDebitosParcial = $oRecalculo->getPagamentosParciais();
    if ($aDebitosParcial) {

      $this->log("Rec�lculo n�o efetuado. Os seguintes d�bitos possuem pagamento parcial: " . implode(', ', $aDebitosParcial));
      return false;
    }

    $aDebitosSuspensao = $oRecalculo->getSuspensoes();
    if ($aDebitosSuspensao) {

      $this->log("Rec�lculo n�o efetuado. Os seguintes d�bitos possuem suspens�o: " . implode(', ', $aDebitosSuspensao));
      return false;
    }

    $aDebitosCancelamento = $oRecalculo->getCancelamentos();
    if ($aDebitosCancelamento) {

      $this->log("Rec�lculo n�o efetuado. Os seguintes d�bitos possuem cancelamento: " . implode(', ', $aDebitosPagamento));
      return false;
    }

    $aDebitosCompensacao = $oRecalculo->getCompensacoes();
    if ($aDebitosCompensacao) {

      $this->log("Rec�lculo n�o efetuado. Os seguintes d�bitos possuem compensa��o: " . implode(',', $aDebitosCompensacao));
      return false;
    }

    $aDebitosDescontos = $oRecalculo->getDescontos();
    if ($aDebitosDescontos) {

      $this->log("Rec�lculo n�o efetuado. Os seguintes d�bitos possuem desconto: " . implode(',', $aDebitosDescontos));
      return false;
    }

    return true;
  }

  /**
   * Controla a gera��o de e obten��o de Numpre(s). Caso o Respons�vel pelo Pagamento do contrato seja:
   * - Economia: Numpre por economia.
   * - Condom�nio: Numpre por contrato.
   *
   * @param integer $iMes
   * @param integer $iAno
   * @param integer $iContrato
   * @param integer $iEconomia
   *
   * @return integer
   * @throws DBException
   */
  private function getNumpre($iMes, $iAno, $iContrato, $iEconomia = null) {

    $sChave = $iMes . $iAno . $iContrato . $iEconomia;
    if (isset($this->aNumpres[$sChave])) {
      return $this->aNumpres[$sChave];
    }

    $sSql = "
    select
      x22_numpre
    from
      aguacalc
    where
          x22_numpre is not null
      and x22_aguacontrato = {$iContrato}
      and x22_exerc = {$iAno}
      and x22_mes = {$iMes}";

    if ($iEconomia && $this->oContrato->isCondominio() && $this->oContrato->isPagamentoEconomia()) {
      $sSql .= " and x22_aguacontratoeconomia = {$iEconomia} ";
    }

    $rsNumpre = db_query($sSql);
    if (!$rsNumpre) {
      throw new DBException('Falhou ao buscar numpre.');
    }

    if (pg_num_rows($rsNumpre) > 0) {
      $iNumpre = pg_fetch_object($rsNumpre)->x22_numpre;
    } else {
      $iNumpre = $this->gerarCodigoProcessamento();
    }

    $this->aNumpres[$sChave] = $iNumpre;

    return $this->aNumpres[$sChave];
  }

  /**
   * Persiste os d�bitos de tarifa.
   *
   * @param int $iMes
   * @throws DBException
   */
  private function persistirDebitos($iMes) {

    $sSqlReceitas  = " select ";
    $sSqlReceitas .= "   x22_aguacontrato as contrato, ";
    $sSqlReceitas .= "   x22_aguacontratoeconomia as economia, ";
    $sSqlReceitas .= "   x22_exerc as ano, ";
    $sSqlReceitas .= "   x22_mes as mes, ";
    $sSqlReceitas .= "   x23_codconsumotipo as tipo_consumo, ";
    $sSqlReceitas .= "   x23_valor as valor ";
    $sSqlReceitas .= " from ";
    $sSqlReceitas .= "   aguacalc ";
    $sSqlReceitas .= " inner join aguacalcval on x23_codcalc = x22_codcalc ";
    $sSqlReceitas .= " where ";
    $sSqlReceitas .= "       x22_exerc = {$this->iAno} ";
    $sSqlReceitas .= "   and x22_mes = {$iMes} ";
    $sSqlReceitas .= "   and x22_aguacontrato = {$this->oContrato->getCodigo()} ";
    $sSqlReceitas .= " order by ";
    $sSqlReceitas .= "   1, 2, 3, 4 ";

    if ($this->oContrato->isCondominio() && $this->oContrato->isPagamentoCondominio()) {

      $sSqlReceitas  = " select ";
      $sSqlReceitas .= "   x22_aguacontrato as contrato, ";
      $sSqlReceitas .= "   null as economia, ";
      $sSqlReceitas .= "   x22_exerc as ano, ";
      $sSqlReceitas .= "   x22_mes as mes, ";
      $sSqlReceitas .= "   x23_codconsumotipo as tipo_consumo, ";
      $sSqlReceitas .= "   sum(x23_valor) as valor ";
      $sSqlReceitas .= " from ";
      $sSqlReceitas .= "   aguacalc ";
      $sSqlReceitas .= " inner join aguacalcval on x23_codcalc = x22_codcalc ";
      $sSqlReceitas .= " where ";
      $sSqlReceitas .= "       x22_aguacontrato = {$this->oContrato->getCodigo()} ";
      $sSqlReceitas .= "   and x22_exerc = {$this->iAno} ";
      $sSqlReceitas .= "   and x22_mes = {$iMes} ";
      $sSqlReceitas .= " group ";
      $sSqlReceitas .= "   by x22_aguacontrato, x22_exerc, x22_mes, x23_codconsumotipo ";
      $sSqlReceitas .= " order by ";
      $sSqlReceitas .= "   1, 2, 3, 4 ";
    }

    $rsReceitas = db_query($sSqlReceitas);
    if (!$rsReceitas) {
      throw new DBException('Ocorreu um erro ao preparar os dados das receitas.');
    }

    $iQtdReceitas = pg_num_rows($rsReceitas);
    for ($iReceita = 0; $iReceita < $iQtdReceitas; $iReceita++) {

      $iCodigoResponsavel = $this->oContrato->getCodigoCgm();
      $oDadosReceita = pg_fetch_object($rsReceitas, $iReceita);

      if ($oDadosReceita->economia) {

        $this->iCodigoEconomia = $oDadosReceita->economia;

        $oEconomia = new AguaContratoEconomia();
        $oEconomia->carregar($oDadosReceita->economia);

        if ($this->oContrato->isCondominio() && $this->oContrato->isPagamentoEconomia()) {
          $iCodigoResponsavel = $oEconomia->getCodigoCgm();
        }
      }

      $oTipoConsumo = new AguaTipoConsumo($oDadosReceita->tipo_consumo);
      $iNumpre = $this->getNumpre($oDadosReceita->mes, $oDadosReceita->ano, $oDadosReceita->contrato, $oDadosReceita->economia);
      $oDebito = (object) array(
        'codigo_processamento' => $iNumpre,
        'data_vencimento'      => $this->oContrato->getDataVencimento($this->iAno, $iMes),
        'codigo_receita'       => $oTipoConsumo->getCodigoReceita(),
        'codigo_historico'     => $oTipoConsumo->getCodigoHistorico() + ($iMes - 1),
        'codigo_responsavel'   => $iCodigoResponsavel,
        'parcela'              => $iMes,
        'valor'                => $oDadosReceita->valor,
      );
      $this->persistirReceita($oDebito);
      $sEconomia = $oDadosReceita->economia ? " and x22_aguacontratoeconomia = {$oDadosReceita->economia} " : null;
      $sUpdate = "
        update
          aguacalc
        set
          x22_numpre = {$iNumpre}
        where
              x22_aguacontrato = {$oDadosReceita->contrato}
          and x22_exerc = {$this->iAno}
          and x22_mes = {$iMes}
          {$sEconomia}
      ";
      $rsNumpre = db_query($sUpdate);
      if (!$rsNumpre) {
        throw new DBException('Erro ao salvar numpre.');
      }
    }

    $this->iCodigoEconomia = null;
  }

  /**
   * Persiste os c�lculos.
   *
   * @param int $iMes
   */
  private function persistirCalculos($iMes) {

    $oHidrometro = $this->oContrato->getHidrometro();
    $iConsumo = $oHidrometro->calcularConsumoMes($iMes, $this->iAno);
    $aCalculosFinanceiros = $this->getCalculosFinanceiros($iConsumo);

    foreach ($aCalculosFinanceiros as $aCalculoFinanceiro) {

      $iCodigoEconomia = $aCalculoFinanceiro['responsavel'] ? $aCalculoFinanceiro['responsavel']->getCodigo() : null;
      $this->iCodigoEconomia = $iCodigoEconomia;
      $oDataAtual = new DateTime;

      $oCalculo = $this->getCalculo($iMes, $iCodigoEconomia);
      $oCalculo->setExercicio($this->iAno);
      $oCalculo->setMes($iMes);
      $oCalculo->setData($oDataAtual);
      $oCalculo->setHora($oDataAtual->format('H:i'));
      $oCalculo->setCodigoUsuario($this->iCodigoUsuario);
      $oCalculo->setTipo(self::CALCULO_TARIFA);
      $oCalculo->setCodigoContrato($this->oContrato->getCodigo());
      $oCalculo->setCodigoEconomia($iCodigoEconomia);
      $oCalculo->setCodigoMatricula($this->oContrato->getCodigoMatricula());
      $oCalculo->setManual((string) AguaContrato::RESPONSAVEL_PAGAMENTO_CONTRATO);
      $oCalculo->setValores(array());

      if ($this->oContrato->isCondominio()) {
        $oCalculo->setManual($this->oContrato->getResponsavelPagamento());
      }

      foreach ($aCalculoFinanceiro['resultado']->getPorTipoConsumo() as $iCodigoTipoConsumo => $nValor) {

        if (!$this->oContrato->deveRealizarCobranca() && !$iConsumo) {
          $this->log('Contrato com Situa��o de Corte dispensada de cobran�a de Servi�os de �gua e Esgoto. Sem consumo registrado.');
          break;
      	}

        if (!$this->validarExcecoes($iCodigoTipoConsumo, $nValor)) {
          continue;
        }

        $oValor = new ValorEntity;
        $oValor->setCodigoTipoConsumo($iCodigoTipoConsumo);
        $oValor->setValor($nValor);

        $oCalculo->adicionarValor($oValor);
      }

      if ($oCalculo->getValores()) {
        $this->oRepository->save($oCalculo);
      } else {

        if ($oCalculo->getCodigo()) {
          $this->oRepository->delete($oCalculo->getCodigo());
        }
        $this->log('Nenhum d�bito foi lan�ado para o contrato.');
      }
    }

    $this->iCodigoEconomia = null;
  }

  /**
   * @return boolean
   * @throws BusinessException
   */
  public function processar() {

    $this->validarParametros();
    $this->validarCategoriasConsumo($this->getAno());

    for ($iMes = $this->getMesInicial(); $iMes <= $this->getMesFinal(); $iMes++) {

      $sDataAtual = date(sprintf('%s-%s-d', $this->getAno(), $iMes));
      $oDataAtual = new DateTime($sDataAtual);
      if (!$this->oContrato->isValido($oDataAtual)) {

        $this->log("Contrato fora da data de validade. {$iMes}/{$this->getAno()}");
        continue;
      }

      /* Instancia a classe auxiliar para calculo */
      $this->oRecalculo = new Recalculo($this->oContrato, $iMes, $this->iAno);

      /* Define se for um recalculo*/
      $this->isRecalculo = $this->oRecalculo->isRecalculo();

      /* Valida se pode efetuar um recalculo de agua*/
      if ($this->isRecalculo && !$this->validarRecalculo($iMes)) {
        continue;
      }

      if ($this->isRecalculo) {
        $this->apagarDebitos($iMes);
      }

      if ($this->hasMudancaContrato($iMes)) {
        $this->apagarCalculos($iMes);
      }

      $this->persistirCalculos($iMes);
      $this->persistirDebitos($iMes);
    }

    return true;
  }

  /**
   * Se o contrato mudou:
   *
   * - De condom�nio (respons�vel economia) para contrato normal
   * - De respons�vel economia para condom�nio
   * - De respons�vel condom�nio para economia
   *
   * @param integer $iMes
   *
   * @return bool
   * @throws DBException
   */
  private function hasMudancaContrato($iMes) {

    $sSqlTipo= "
    select
      x22_manual
    from
      aguacalc
    where
          x22_aguacontrato = {$this->oContrato->getCodigo()}
      and x22_exerc = {$this->iAno}
      and x22_mes = {$iMes}
    limit 1";

    $rsTipo = db_query($sSqlTipo);
    if (!$rsTipo) {
      throw new DBException("Ocorreu um erro ao consultar o tipo de c�lculo realizado.");
    }

    $lRecalculo = pg_num_rows($rsTipo) > 0;

    if ($lRecalculo) {

      $oTipo = pg_fetch_object($rsTipo);
      $lMudouResponsavel = $this->oContrato->isCondominio() && $oTipo->x22_manual != $this->oContrato->getResponsavelPagamento();
      $lMudouTipo = ($this->oContrato->isCondominio() && $oTipo->x22_manual == '0') || (!$this->oContrato->isCondominio() && $oTipo->x22_manual != '0');

      return ($lMudouResponsavel || $lMudouTipo);
    }
    return false;
  }
}
