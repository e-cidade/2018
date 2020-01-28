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

namespace ECidade\Tributario\Agua\Leitura;

use AguaContrato;
use AguaLeitura;
use db_utils;
use DBDate;
use DBException;
use ECidade\Tributario\Agua\Repository\Leitura as LeituraRepository;
use ParameterException;

class Processamento {

  /**
   * @var LeituraRepository
   */
  private $oRepository;

  /**
   * @var \DBLog
   */
  private $oLogger;

  const CODIGO_SITUACAO_MEDIA = 33;

  const CODIGO_SITUACAO_PENALIDADE = 34;

  const MOTIVO_CANCELAMETNO = 'Cancelado automáticamente devido ao cálculo de média/penalidade.';

  /**
   * @param LeituraRepository $oRepository
   */
  public function __construct(LeituraRepository $oRepository) {
    $this->oRepository = $oRepository;
  }

  public function setLogger(\DBLog $oLogger) {
    $this->oLogger = $oLogger;
  }

  public function getLogger() {
    return $this->oLogger;
  }

  public function log($sMensagem) {

    if ($this->oLogger) {
      $this->oLogger->escreverLog("Contrato: {$this->iCodigoContrato} Mês: {$this->iMesReferencia}/{$this->iAnoReferencia} - {$sMensagem}");
    }

    return false;
  }

  /**
   * @param $iCodigoContrato
   * @param $iMesReferencia
   * @param $iAnoReferencia
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function executar($iCodigoContrato, $iMesReferencia, $iAnoReferencia) {

    if (!db_utils::inTransaction()) {
      throw new DBException('Sem transação iniciada.');
    }

    if (!$iCodigoContrato) {
      throw new ParameterException('Código do Contrato não foi imformado.');
    }

    if (!$iMesReferencia) {
      throw new ParameterException('Mês de referência não foi informado.');
    }

    if (!$iAnoReferencia) {
      throw new ParameterException('Mês de referência não foi informado.');
    }

    /**
     *  Define nas propriedades da classe os parametros de execucao
     *  para utilizar no log
    */
    $this->iCodigoContrato = $iCodigoContrato;
    $this->iMesReferencia  = $iMesReferencia;
    $this->iAnoReferencia  = $iAnoReferencia;

    $oContrato = new AguaContrato($iCodigoContrato);
    $oHidrometro = $oContrato->getHidrometro();

    $aLeituras = $this->oRepository->findUltimas($iCodigoContrato, $iMesReferencia, $iAnoReferencia);
    $aResumosMensais = $this->oRepository->agruparPorMes($aLeituras);

    $oRegraFactory = new RegraFactory();
    $oRegra = $oRegraFactory->create($aResumosMensais);

    if (!$oRegra) {
      $this->log("Leitura: {$aLeituras[0]->getCodigo()} - Não se enquadra em regra de média / penalidade.");
      return false;
    }

    $oResumoMensal = current($aResumosMensais);
    $this->cancelarLeituras($iCodigoContrato, $oResumoMensal->getMes(), $oResumoMensal->getAno());

    $oLeituraGerada = new AguaLeitura();
    $oLeituraGerada->setCodigoHidrometro($oHidrometro->getCodigo());
    $oLeituraGerada->setAno($iAnoReferencia);
    $oLeituraGerada->setMes($iMesReferencia);
    $oLeituraGerada->setCodigoLeiturista('0');
    $oLeituraGerada->setCodigoUsuario(1);
    $oLeituraGerada->setLeitura($oResumoMensal->getLeitura());
    $oLeituraGerada->setConsumo($oRegra->calcular());
    $oLeituraGerada->setExcesso(0);
    $oLeituraGerada->setHidrometroVirou(false);
    $oLeituraGerada->setSaldo(0);
    $oLeituraGerada->setTipo(AguaLeitura::TIPO_IMPORTACAO);
    $oLeituraGerada->setStatus(AguaLeitura::STATUS_ATIVA);
    $oLeituraGerada->setCodigoContrato($iCodigoContrato);

    $oData = new DBDate(date('Y-m-d'));
    $oLeituraGerada->setDataInclusao($oData);
    $oLeituraGerada->setDataLeitura($oData);

    $sNomeRegra = '';
    if ($oRegra instanceof Regra\Penalidade) {
      $sNomeRegra = 'Penalidade';
      $oLeituraGerada->setSituacao(self::CODIGO_SITUACAO_PENALIDADE);
    }

    if ($oRegra instanceof Regra\Media) {
      $sNomeRegra = 'Media';
      $oLeituraGerada->setSituacao(self::CODIGO_SITUACAO_MEDIA);
    }

    $oLeituraGerada->salvar();
    $this->log("Leitura: {$oLeituraGerada->getCodigo()} - Gerada com regra de {$sNomeRegra}");
    return true;
  }

  /**
   * Cancela todas as leituras do contrato para o mês/ano informado.
   *
   * @param int $iCodigoContrato
   * @param int $iMes
   * @param int $iAno
   */
  public function cancelarLeituras($iCodigoContrato, $iMes, $iAno) {

    $aLeituras = $this->oRepository->findByMesAno($iCodigoContrato, $iMes, $iAno);

    foreach ($aLeituras as $oLeitura) {
      $oLeitura->cancelar(self::MOTIVO_CANCELAMETNO);
    }
  }
}
