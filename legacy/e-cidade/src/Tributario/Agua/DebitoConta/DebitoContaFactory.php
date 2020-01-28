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

use ECidade\Tributario\Agua\DebitoConta\DebitoContaService;
use ECidade\Tributario\Agua\DebitoConta\DebitoContaArchive;
use ECidade\Tributario\Agua\Repository\DebitoConta\Pedido as DebitoContaPedidoRepository;
use ECidade\Tributario\Agua\Repository\DebitoConta\Parametro as DebitoContaParametroRepository;
use ECidade\Tributario\Agua\Repository\DebitoConta\Arquivo as DebitoContaArquivoRepository;

class DebitoContaFactory {

  public function build()
  {
    $oProgressBar = new \ProgressBar('progress');

    $oDebitoContaPedido = new \cl_debcontapedido();
    $oDebitoContaPedidoTipo = new \cl_debcontapedidotipo();
    $oDebitoContaPedidoContrato = new \cl_debcontapedidoaguacontrato();
    $oDebitoContaPedidoEconomia = new \cl_debcontapedidoaguacontratoeconomia();

    $oDebitoContaPedidoRepository = new DebitoContaPedidoRepository(
      $oDebitoContaPedido,
      $oDebitoContaPedidoTipo,
      $oDebitoContaPedidoContrato,
      $oDebitoContaPedidoEconomia
    );

    $oDebitoContaParametro = new \cl_debcontaparam();
    $oDBConfig = new \cl_db_config();

    $oDebitoContaParametroRepository = new DebitoContaParametroRepository($oDebitoContaParametro, $oDBConfig);

    $oDebitoContaArquivo = new \cl_debcontaarquivo();
    $oDebitoContaArquivoTipo = new \cl_debcontaarquivotipo();
    $oDebitoContaArquivoReg = new \cl_debcontaarquivoreg();
    $oDebitoContaArquivoRegMov = new \cl_debcontaarquivoregmov();
    $oDebitoContaArquivoRegCad = new \cl_debcontaarquivoregcad();
    $oDebitoContaArquivoRegPed = new \cl_debcontaarquivoregped();

    $oDebitoContaArquivoRepository = new DebitoContaArquivoRepository(
      $oDebitoContaArquivo,
      $oDebitoContaArquivoTipo,
      $oDebitoContaArquivoReg,
      $oDebitoContaArquivoRegMov,
      $oDebitoContaArquivoRegCad,
      $oDebitoContaArquivoRegPed
    );

    $oDebitoContaArchive = new DebitoContaArchive();

    return new DebitoContaService(
      $oDebitoContaPedidoRepository,
      $oDebitoContaParametroRepository,
      $oDebitoContaArquivoRepository,
      $oDebitoContaArchive,
      $oProgressBar
    );
  }
}
