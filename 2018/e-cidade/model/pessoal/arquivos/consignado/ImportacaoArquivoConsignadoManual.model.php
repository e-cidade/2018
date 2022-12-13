<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Class ImportacaoArquivoConsignadoManual
 */
class ImportacaoArquivoConsignadoManual {



  /**
   * @var \Banco
   */
  private $oBanco;

  /**
   * @var \DBCompetencia
   */
  private $oCompetencia;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var \ArquivoConsignadoManualParcela[]
   */
  protected $parcelas = array();

  /**
   * @var \ArquivoConsignadoManualParcela[]
   */
  protected $parcelasPorServidor = array();

  /**
   * @var array
   */
  protected $aSaldoSalarioServidor = array();

  /**
   * ImportacaoArquivoConsignadoManual constructor.
   * @param \Banco         $oBanco
   * @param \DBCompetencia $oCompetencia
   * @param \Instituicao   $oInstituicao
   */
  public function __construct(DBCompetencia $oCompetencia,  Instituicao $oInstituicao) {

    $this->oCompetencia = $oCompetencia;
    $this->oInstituicao = $oInstituicao;

    $this->parcelas = ArquivoConsignadoManualParcelaRepository::getParcelasParaProcessamentoNaCompetencia($this->oCompetencia, $this->oInstituicao);
  }

  /**
   * Processa os ar parcelas da competência
   * @param bool $lProcessarExcluidos deve realizar o processamento das parcelas excluidas
   * @throws \DBException
   */
  public function processar($lProcessarExcluidos = false) {

    if (!db_utils::inTransaction()) {
      throw new DBException('Sem transação com o banco de dados');
    }
    $aParcelasNoMes = $this->parcelas;
    foreach ($aParcelasNoMes as $parcela) {

      if (!$lProcessarExcluidos && $parcela->getMotivo() == ArquivoConsignadoMotivo::MOTIVO_EXCLUIDO) {
        continue;
      }
      if (!$this->servidorTemSaldo($parcela->getServidor())) {
        $parcela->setMotivo(ArquivoConsignadoMotivo::MOTIVO_SALDO_INSUFICIENTE);
      }
      $this->validarAfastamento($parcela);
      $this->validarRescisao($parcela);
      $this->validarServidorFalecido($parcela);
      $oConsigando = ArquivoConsignadoManualRepository::getByCodigo($parcela->getCodigoConsignado());
      ArquivoConsignadoManualParcelaRepository::persist($parcela, $oConsigando);
    }
  }

  protected function validarAfastamento(ArquivoConsignadoManualParcela $oRegistro) {

    $mAfastamento = $oRegistro->getServidor()->isAfastado();
    if ($mAfastamento && !$oRegistro->getServidor()->temRemuneracaoNoPeriodo()) {

      $oRegistro->setMotivo(ArquivoConsignadoMotivo::MOTIVO_SERVIDOR_AFASTADO);
    }

    return $oRegistro;
  }

  protected function validarRescisao(ArquivoConsignadoManualParcela $oRegistro) {

    $oServidor = $oRegistro->getServidor();

    if ($oServidor->isRescindido()) {
      $oRegistro->setMotivo(ArquivoConsignadoMotivo::MOTIVO_SERVIDOR_DESLIGADO);
    }
  }

  /**
   * Valida se o servidor informado, esta falecido, se estiver retorna true senão retorna false.
   *
   * @param $oRegistro
   * @return bool
   * @throws \DBException
   */
  protected function validarServidorFalecido(ArquivoConsignadoManualParcela $oRegistro) {

    $oDaoRhPesRescisao = new cl_rhpesrescisao();

    /**
     * Valida se o servidor possui uma das causas(60,62,64)
     * se posusir é porque o mesmo esta falecido.
     */
    $sWherePesRescisao = "rh02_regist = {$oRegistro->getServidor()->getMatricula()} and r59_causa in (60, 62, 64)";
    $sSqlRhPesRescisao = $oDaoRhPesRescisao->sql_query_rescisao(null, '*', null, $sWherePesRescisao);
    $rsRhPesRescisao = db_query($sSqlRhPesRescisao);

    if (!$rsRhPesRescisao) {
      throw new DBException("Não foi possivel validar os dados do servidor");
    }

    if (pg_num_rows($rsRhPesRescisao)) {
      $oRegistro->setMotivo(ArquivoConsignadoMotivo::MOTIVO_FALECIMENTO);
    }
  }

  /**
   * Valida  o saldo do Servidor
   * @param \Servidor $servidor
   * @return bool
   */
  private function servidorTemSaldo(Servidor $servidor) {

    $aParcelas      = $this->getParcelasDoServidor($servidor);
    $nValorParcelas = 0;
    foreach ($aParcelas as $oParcela) {
       $nValorParcelas += $oParcela->getValor();
    }

    $nSaldoServidor = $this->getSaldoServidor($servidor);
    if ($nValorParcelas > $nSaldoServidor) {
      return false;
    }
    return true;
  }

  /**
   * @param \Servidor $servidor
   * @return \ArquivoConsignadoManualParcela[]
   */
  private function getParcelasDoServidor(Servidor $servidor) {

    if (isset($this->parcelasPorServidor[$servidor->getMatricula()])) {
      return $this->parcelasPorServidor[$servidor->getMatricula()];
    }
    foreach ($this->parcelas as $parcela) {

      if ($parcela->getServidor()->getMatricula() == $servidor->getMatricula()) {
        $this->parcelasPorServidor[$servidor->getMatricula()][] = $parcela;
      }
    }
    return $this->parcelasPorServidor[$servidor->getMatricula()];
  }

  /**
   * Retorna o Saldo do Servidor
   * @param \Servidor $oServidor
   * @return mixed
   */
  public function getSaldoServidor(Servidor $oServidor) {

    if (!isset($this->aSaldoSalarioServidor[$oServidor->getMatricula()])) {

      $oCalculo = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      $nValor   = 0;
      if (!empty($oCalculo)) {
        $nValor = $oCalculo->getValorLiquido();
      }
      $this->setSaldoServidor($oServidor, $nValor);
    }
    return $this->aSaldoSalarioServidor[$oServidor->getMatricula()];
  }

  /**
   * @param \Servidor $oServidor
   * @param           $nValor
   */
  private function setSaldoServidor(Servidor $oServidor, $nValor) {
    $this->aSaldoSalarioServidor[$oServidor->getMatricula()] = $nValor;
  }
}