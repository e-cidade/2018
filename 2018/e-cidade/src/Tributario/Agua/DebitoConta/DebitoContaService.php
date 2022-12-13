<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Agua\DebitoConta;

use \ProgressBar;
use \Exception;
use \BusinessException;
use ECidade\Tributario\Agua\Repository\DebitoConta\Pedido as DebitoContaPedidoRepository;
use ECidade\Tributario\Agua\Repository\DebitoConta\Parametro as DebitoContaParametroRepository;
use ECidade\Tributario\Agua\Repository\DebitoConta\Arquivo as DebitoContaArquivoRepository;
use ECidade\Tributario\Agua\Entity\DebitoConta\Pedido;
use ECidade\Tributario\Agua\Configuracao;

final class DebitoContaService
{
  private $oProgressBar;

  private $oDebitoContaPedidoRepository;

  private $oDebitoContaParametroRepository;

  private $oDebitoContaArquivoRepository;

  private $oDebitoContaArchive;

  public function __construct(
    DebitoContaPedidoRepository $oDebitoContaPedidoRepository,
    DebitoContaParametroRepository $oDebitoContaParametroRepository,
    DebitoContaArquivoRepository $oDebitoContaArquivoRepository,
    DebitoContaArchive $oDebitoContaArchive,
    ProgressBar $oProgressBar
  ) {
    $this->oDebitoContaPedidoRepository = $oDebitoContaPedidoRepository;
    $this->oDebitoContaParametroRepository = $oDebitoContaParametroRepository;
    $this->oDebitoContaArquivoRepository = $oDebitoContaArquivoRepository;
    $this->oDebitoContaArchive = $oDebitoContaArchive;
    $this->oProgressBar = $oProgressBar;
  }

  public function gerar($iTipoDebito, $iAno, $iMes, $iBanco, $iInstit)
  {
    $this->oProgressBar->flush();

    $this->oProgressBar->setMessageLog("(1/3) Buscando Informações dos Contratos...");

    $oDebitoContaCollection = $this->oDebitoContaPedidoRepository->getDebitoContaPedido($iTipoDebito, $iAno, $iMes, $iBanco, $iInstit);

    $iTotalContratos = $oDebitoContaCollection->count();

    if (empty($iTotalContratos)) {
      throw new Exception("Nenhum registro encontrado para os filtros selecionados.");
    }

    $this->oProgressBar->setMessageLog("(2/3) Gerando arquivo...");

    $aParametroConfig = $this->oDebitoContaParametroRepository->getParametroConfig($iInstit);
    $aParametroBanco = $this->oDebitoContaParametroRepository->getParametroBanco($iInstit, $iBanco);

    $this->oDebitoContaParametroRepository->aumentaUltimoNSA($iInstit, $aParametroBanco->d62_banco);

    $this->oDebitoContaArchive->open($iBanco, $aParametroBanco->d62_ultimonsa);

    $sNomeArquivo = $this->oDebitoContaArchive->getNome();

    $iCodigoDebitoContaArquivo = $this->oDebitoContaArquivoRepository->insertDebitoContaArquivo(
      $aParametroBanco->d62_ultimonsa,
      $sNomeArquivo,
      $iTipoDebito,
      $iMes,
      $iBanco
    );

    $this->oDebitoContaArquivoRepository->verificaDebitoContaTipo($iCodigoDebitoContaArquivo, $iTipoDebito);

    $this->oDebitoContaArchive->header(
      $aParametroBanco->d62_convenio,
      $aParametroConfig->nomeinst,
      $aParametroBanco->d62_banco,
      $aParametroBanco->db90_descr,
      $aParametroBanco->d62_ultimonsa
    );

    $iValorTotal = 0;
    $iTotalProcessados = 0;

    $this->oProgressBar->updateMaxProgress($iTotalContratos);

    foreach ($oDebitoContaCollection as $oDebitoConta) {

      $this->oDebitoContaArchive->linha(
        $oDebitoConta->sBancoIdempresa,
        $oDebitoConta->sBancoAgencia,
        $oDebitoConta->sBancoConta,
        $oDebitoConta->oDebitoDataVencimento,
        $oDebitoConta->nDebitoValor,
        $oDebitoConta->nDebitoNumpre,
        $oDebitoConta->nDebitoNumpar,
        $oDebitoConta->iNumeroContrato,
        $oDebitoConta->iCodigoPedido
      );

      $this->oDebitoContaArquivoRepository->gravaArquivoReg(
        $iCodigoDebitoContaArquivo,
        $oDebitoConta->oDebitoDataVencimento,
        $oDebitoConta->nDebitoValor,
        $oDebitoConta->nDebitoNumpar,
        $oDebitoConta->iCodigoPedido
      );

      $iValorTotal += $oDebitoConta->nDebitoValor;

      $iTotalProcessados++;
      $this->oProgressBar->updatePercentual($iTotalProcessados);
    }

    $this->oDebitoContaArchive->footer($iTotalContratos, $iValorTotal);
    $this->oDebitoContaArchive->close();

    $this->oDebitoContaArquivoRepository->atualizaConteudoDebitoContaArquivo(
      $iCodigoDebitoContaArquivo,
      $this->oDebitoContaArchive->getLinhas()
    );

    $this->oProgressBar->setMessageLog("(3/3) Processo concluído.");

    return $sNomeArquivo;
  }

  public function salvar(\stdClass $oParametros)
  {
    if (empty($oParametros->iBanco)) {
      throw new BusinessException('Banco não informado.');
    }

    if (empty($oParametros->sAgencia)) {
      throw new BusinessException('Agência não informada.');
    }

    if (empty($oParametros->sConta)) {
      throw new BusinessException('Conta não informada.');
    }

    if (empty($oParametros->iStatus)) {
      throw new BusinessException('Status não informado.');
    }

    if (empty($oParametros->iContrato)) {
      throw new BusinessException('Contrato não informado.');
    }

    $oContrato = new \AguaContrato((int) $oParametros->iContrato);
    if ($oContrato->isPagamentoEconomia() && empty($oParametros->iEconomia)) {
      throw new BusinessException('Economia não informada.');
    }

    $oConfiguracao = Configuracao::create();

    $oPedido = new Pedido;
    $oPedido->setCodigo((int) $oParametros->iCodigo);
    $oPedido->setBanco((int) $oParametros->iBanco);
    $oPedido->setAgencia($oParametros->sAgencia);
    $oPedido->setConta($oParametros->sConta);
    $oPedido->setStatus((int) $oParametros->iStatus);
    $oPedido->setIdEmpresa($oParametros->sIdEmpresa);
    $oPedido->setInstituicao(db_getsession('DB_instit'));
    $oPedido->setContrato($oContrato);
    $oPedido->adicionarTipoDebito($oConfiguracao->getTipoDebito());

    $iCodigoPedido = $oPedido->getCodigo();
    if (empty($iCodigoPedido)) {
      $oPedido->setDataLancamento(new \DateTime);
    }

    if ($oParametros->iEconomia) {

      $oEconomia = new \AguaContratoEconomia();
      $oEconomia->carregar((int) $oParametros->iEconomia);
      $oPedido->setEconomia($oEconomia);
    }

    return $this->oDebitoContaPedidoRepository->persist($oPedido);
  }

  public function carregar($iCodigo)
  {
    return $this->oDebitoContaPedidoRepository->find($iCodigo);
  }
}
