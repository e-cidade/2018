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

namespace ECidade\Tributario\Agua\Repository\DebitoConta;

use DateTime;
use DBException;
use BusinessException;
use AguaContrato;
use AguaContratoEconomia;
use cl_debcontapedido as DebitoContaPedidoDAO;
use cl_debcontapedidotipo as DebitoContaPedidoTipoDAO;
use cl_debcontapedidoaguacontrato as DebitoContaPedidoContratoDAO;
use cl_debcontapedidoaguacontratoeconomia as DebitoContaPedidoEconomiaDAO;

use ECidade\Tributario\Agua\DebitoConta\DebitoContaCollection;
use ECidade\Tributario\Agua\Entity\DebitoConta\Pedido as DebitoContaPedido;
use ECidade\Tributario\Arrecadacao\TipoDebito;

final class Pedido
{

  private $oPedidoDAO;
  private $oPedidoTipoDAO;
  private $oPedidoContratoDAO;
  private $oPedidoEconomiaDAO;

  /**
   * @param DebitoContaPedidoDAO $oPedidoDAO
   * @param DebitoContaPedidoTipoDAO $oPedidoTipoDAO
   * @param DebitoContaPedidoContratoDAO $oPedidoContratoDAO
   * @param DebitoContaPedidoEconomiaDAO $oPedidoEconomiaDAO
   */
  public function __construct(
    DebitoContaPedidoDAO $oPedidoDAO,
    DebitoContaPedidoTipoDAO $oPedidoTipoDAO,
    DebitoContaPedidoContratoDAO $oPedidoContratoDAO,
    DebitoContaPedidoEconomiaDAO $oPedidoEconomiaDAO
  )
  {
    $this->oPedidoDAO = $oPedidoDAO;
    $this->oPedidoTipoDAO = $oPedidoTipoDAO;
    $this->oPedidoContratoDAO = $oPedidoContratoDAO;
    $this->oPedidoEconomiaDAO = $oPedidoEconomiaDAO;
  }

  /**
   * @param int $iTipoDebito
   * @param int $iAno
   * @param int $iMes
   * @param int $iBanco
   * @param int $iInstit
   *
   * @return DebitoContaCollection
   */
  public function getDebitoContaPedido($iTipoDebito, $iAno, $iMes, $iBanco, $iInstit)
  {
    $sSql  = " select contrato,                                                                                                        ";
    $sSql .= "        economia,                                                                                                        ";
    $sSql .= "        d63_codigo     as codigo_pedido,                                                                                 ";
    $sSql .= "        d63_agencia    as banco_agencia,                                                                                 ";
    $sSql .= "        d63_conta      as banco_conta,                                                                                   ";
    $sSql .= "        d63_idempresa  as banco_idempresa,                                                                               ";
    $sSql .= "        k00_numpre     as debito_numpre,                                                                                 ";
    $sSql .= "        k00_numpar     as debito_parcela,                                                                                ";
    $sSql .= "        k00_dtvenc     as debito_datavencimento,                                                                         ";
    $sSql .= "        k00_tipo       as debito_tipo,                                                                                   ";
    $sSql .= "        sum(k00_valor) as debito_valor                                                                                   ";
    $sSql .= "   from (                                                                                                                ";

    // Débitos de tarifa por contrato (contrato individual ou com condomínio responsável pelo pagamento)

    $sSql .= "         select debcontapedido.*,                                                                                        ";
    $sSql .= "                arrecad.*,                                                                                               ";
    $sSql .= "                aguacontrato.x54_sequencial as contrato,                                                                 ";
    $sSql .= "                0 as economia                                                                                            ";
    $sSql .= "           from debcontapedido                                                                                           ";
    $sSql .= "                inner join debcontapedidotipo on d66_codigo = d63_codigo                                                 ";
    $sSql .= "                inner join debcontapedidoaguacontrato on d63_codigo = d81_codigo                                         ";
    $sSql .= "                inner join aguacontrato on x54_sequencial = d81_contrato                                                 ";
    $sSql .= "                inner join aguacalc on x22_aguacontrato = d81_contrato                                                   ";
    $sSql .= "                inner join arrecad on arrecad.k00_numpre = x22_numpre                                                    ";
    $sSql .= "                inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                      ";
    $sSql .= "          where d63_status = 2                                                                                           ";
    $sSql .= "            and d63_banco = $iBanco                                                                                      ";
    $sSql .= "            and d63_instit = $iInstit                                                                                    ";
    $sSql .= "            and arreinstit.k00_instit = $iInstit                                                                         ";
    $sSql .= "            and extract(month from arrecad.k00_dtvenc) = $iMes                                                           ";
    $sSql .= "            and extract(year from arrecad.k00_dtvenc) = $iAno                                                            ";
    $sSql .= "            and d66_arretipo = $iTipoDebito                                                                              ";
    $sSql .= "            and arrecad.k00_tipo = $iTipoDebito                                                                          ";

    $sSql .= "          union all                                                                                                      ";

    // Débitos de parcelamento por contrato (contrato individual ou com condomínio responsável pelo pagamento)

    $sSql .= "         select debcontapedido.*,                                                                                        ";
    $sSql .= "                arrecad.*,                                                                                               ";
    $sSql .= "                x54_sequencial as contrato,                                                                              ";
    $sSql .= "                0 as economia                                                                                            ";
    $sSql .= "           from debcontapedido                                                                                           ";
    $sSql .= "                inner join debcontapedidoaguacontrato on d63_codigo = d81_codigo                                         ";
    $sSql .= "                inner join aguacontrato on x54_sequencial = d81_contrato                                                 ";
    $sSql .= "                inner join arrecad on arrecad.k00_numcgm = x54_cgm                                                       ";
    $sSql .= "                inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                      ";
    $sSql .= "          where d63_status = 2                                                                                           ";
    $sSql .= "            and d63_banco = $iBanco                                                                                      ";
    $sSql .= "            and d63_instit = $iInstit                                                                                    ";
    $sSql .= "            and arreinstit.k00_instit = $iInstit                                                                         ";
    $sSql .= "            and extract(month from arrecad.k00_dtvenc) = $iMes                                                           ";
    $sSql .= "            and extract(year from arrecad.k00_dtvenc) = $iAno                                                            ";
    $sSql .= "            and x54_emitiroutrosdebitos is true                                                                          ";
    $sSql .= "            and arrecad.k00_tipo <> $iTipoDebito                                                                         ";

    $sSql .= "          union all                                                                                                      ";

    // Débitos de tarifa por economia (contrato com economias responsáveis pelo pagamento)

    $sSql .= "         select debcontapedido.*,                                                                                        ";
    $sSql .= "                arrecad.*,                                                                                               ";
    $sSql .= "                x38_aguacontrato as contrato,                                                                            ";
    $sSql .= "                x38_sequencial as economia                                                                               ";
    $sSql .= "           from debcontapedido                                                                                           ";
    $sSql .= "                inner join debcontapedidotipo on d66_codigo = d63_codigo                                                 ";
    $sSql .= "                inner join debcontapedidoaguacontratoeconomia on d63_codigo = d82_codigo                                 ";
    $sSql .= "                inner join aguacontratoeconomia on x38_sequencial = d82_economia                                         ";
    $sSql .= "                inner join aguacalc on x22_aguacontrato = x38_aguacontrato and x22_aguacontratoeconomia = x38_sequencial ";
    $sSql .= "                inner join arrecad on arrecad.k00_numpre = x22_numpre                                                    ";
    $sSql .= "                inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                      ";
    $sSql .= "          where d63_status = 2                                                                                           ";
    $sSql .= "            and d63_banco = $iBanco                                                                                      ";
    $sSql .= "            and d63_instit = $iInstit                                                                                    ";
    $sSql .= "            and arreinstit.k00_instit = $iInstit                                                                         ";
    $sSql .= "            and extract(month from arrecad.k00_dtvenc) = $iMes                                                           ";
    $sSql .= "            and extract(year from arrecad.k00_dtvenc) = $iAno                                                            ";
    $sSql .= "            and d66_arretipo = $iTipoDebito                                                                              ";
    $sSql .= "            and arrecad.k00_tipo = $iTipoDebito                                                                          ";

    $sSql .= "          union all                                                                                                      ";

    // Débitos de parcelamento por economia (contrato com economias responsáveis pelo pagamento)

    $sSql .= "         select debcontapedido.*,                                                                                        ";
    $sSql .= "                arrecad.*,                                                                                               ";
    $sSql .= "                x38_aguacontrato as contrato,                                                                            ";
    $sSql .= "                x38_sequencial as economia                                                                               ";
    $sSql .= "           from debcontapedido                                                                                           ";
    $sSql .= "                inner join debcontapedidoaguacontratoeconomia on d63_codigo = d82_codigo                                 ";
    $sSql .= "                inner join aguacontratoeconomia on x38_sequencial = d82_economia                                         ";
    $sSql .= "                inner join arrecad on arrecad.k00_numcgm = x38_cgm                                                       ";
    $sSql .= "                inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                      ";
    $sSql .= "          where d63_status = 2                                                                                           ";
    $sSql .= "            and d63_banco = $iBanco                                                                                      ";
    $sSql .= "            and d63_instit = $iInstit                                                                                    ";
    $sSql .= "            and arreinstit.k00_instit = $iInstit                                                                         ";
    $sSql .= "            and extract(month from arrecad.k00_dtvenc) = $iMes                                                           ";
    $sSql .= "            and extract(year from arrecad.k00_dtvenc) = $iAno                                                            ";
    $sSql .= "            and x38_emitiroutrosdebitos is true                                                                          ";
    $sSql .= "            and arrecad.k00_tipo <> $iTipoDebito                                                                         ";
    $sSql .= "       ) as informacoes_linhas_arquivo                                                                                   ";
    $sSql .= "   group by contrato,                                                                                                    ";
    $sSql .= "            economia,                                                                                                    ";
    $sSql .= "            codigo_pedido,                                                                                               ";
    $sSql .= "            debito_numpre,                                                                                               ";
    $sSql .= "            debito_parcela,                                                                                              ";
    $sSql .= "            debito_datavencimento,                                                                                       ";
    $sSql .= "            debito_tipo,                                                                                                 ";
    $sSql .= "            banco_agencia,                                                                                               ";
    $sSql .= "            banco_conta,                                                                                                 ";
    $sSql .= "            banco_idempresa                                                                                              ";
    $sSql .= "   order by contrato,                                                                                                    ";
    $sSql .= "            economia,                                                                                                    ";
    $sSql .= "            debito_numpre,                                                                                               ";
    $sSql .= "            k00_numpar                                                                                                   ";

    $rsDebitoContaPedido = $this->oPedidoDAO->sql_record($sSql);

    if ($this->oPedidoDAO->erro_banco) {
      throw new Exception($this->oPedidoDAO->erro_msg);
    }

    return new DebitoContaCollection($rsDebitoContaPedido);
  }

  /**
   * @param DebitoContaPedido $oPedido
   *
   * @return DebitoContaPedido
   * @throws DBException
   */
  public function persist(DebitoContaPedido $oPedido)
  {
    $this->oPedidoDAO->d63_codigo = $oPedido->getCodigo();
    $this->oPedidoDAO->d63_instit = $oPedido->getInstituicao();
    $this->oPedidoDAO->d63_banco = $oPedido->getBanco();
    $this->oPedidoDAO->d63_agencia = $oPedido->getAgencia();
    $this->oPedidoDAO->d63_conta = $oPedido->getConta();
    $this->oPedidoDAO->d63_status = $oPedido->getStatus();
    $this->oPedidoDAO->d63_idempresa = $oPedido->getIdEmpresa();

    if ($oPedido->getCodigo()) {
      $this->oPedidoDAO->alterar($oPedido->getCodigo());
    } else {

      $this->oPedidoDAO->d63_datalanc = $oPedido->getDataLancamento()->format('Y-m-d');
      $this->oPedidoDAO->d63_horalanc = $oPedido->getDataLancamento()->format('Hi');

      $this->oPedidoDAO->incluir(null);

      $oPedido->setCodigo($this->oPedidoDAO->d63_codigo);
    }

    if ($this->oPedidoDAO->erro_status == 0) {
      throw new DBException('Ocorreu um erro ao salvar o pedido de débito em conta.');
    }

    $this->oPedidoTipoDAO->excluir(null, "d66_codigo = {$oPedido->getCodigo()}");
    foreach ($oPedido->getTiposDebito() as $oTipoDebito) {

      $this->oPedidoTipoDAO->d66_codigo = $oPedido->getCodigo();
      $this->oPedidoTipoDAO->d66_arretipo = $oTipoDebito->getCodigo();
      $this->oPedidoTipoDAO->incluir(null);
      if ($this->oPedidoTipoDAO->erro_status == '0') {
        throw new DBException('Ocorreu um erro ao vincular um tipo de débito ao pedido de débito em conta.');
      }
    }

    $this->oPedidoContratoDAO->excluir($oPedido->getCodigo());
    if ($oPedido->getContrato() && !$oPedido->getEconomia()) {

      $this->oPedidoContratoDAO->d81_codigo = $oPedido->getCodigo();
      $this->oPedidoContratoDAO->d81_contrato = $oPedido->getContrato()->getCodigo();

      $this->oPedidoContratoDAO->incluir();

      if ($this->oPedidoContratoDAO->erro_status == '0') {
        throw new DBException('Ocorreu um erro ao vincular o contrato ao pedido de débito em conta.');
      }
    }

    $this->oPedidoEconomiaDAO->excluir($oPedido->getCodigo());
    if ($oPedido->getEconomia()) {

      $this->oPedidoEconomiaDAO->d82_codigo = $oPedido->getCodigo();
      $this->oPedidoEconomiaDAO->d82_economia = $oPedido->getEconomia()->getCodigo();
      $this->oPedidoEconomiaDAO->incluir();
      if ($this->oPedidoEconomiaDAO->erro_status == '0') {
        throw new DBException('Ocorreu um erro ao vincular a economia ao pedido de débito em conta.');
      }
    }

    return $oPedido;
  }

  /**
   * @param int $iCodigo
   *
   * @return DebitoContaPedido
   * @throws BusinessException
   * @throws DBException
   */
  public function find($iCodigo)
  {
    $oPedido = new DebitoContaPedido();

    $sWhere = "d63_codigo = {$iCodigo} and (d81_contrato is not null or d82_economia is not null)";
    $sSql = $this->oPedidoDAO->sql_query_pedido_agua('*', $sWhere);
    $rsResultado = db_query($sSql);
    $oResultado = pg_fetch_object($rsResultado);

    if (!$rsResultado) {
      throw new DBException('Ocorreu um erro ao buscar o pedido de débito em conta.');
    }

    if (pg_num_rows($rsResultado) === 0) {
      throw new BusinessException('Pedido de débito em conta não encontrado.');
    }

    $oPedido->setCodigo($oResultado->d63_codigo);
    $oPedido->setInstituicao($oResultado->d63_instit);
    $oPedido->setBanco($oResultado->d63_banco);
    $oPedido->setAgencia($oResultado->d63_agencia);
    $oPedido->setConta($oResultado->d63_conta);
    $oPedido->setStatus($oResultado->d63_status);
    $oPedido->setDataLancamento(new DateTime("{$oResultado->d63_datalanc} {$oResultado->d63_horalanc}"));
    $oPedido->setStatus($oResultado->d63_status);
    $oPedido->setIdEmpresa($oResultado->d63_idempresa);

    if ($oResultado->d81_contrato) {
      $oPedido->setContrato(new AguaContrato($oResultado->d81_contrato));
    }

    if ($oResultado->d82_economia) {
      $oEconomia = new AguaContratoEconomia();
      $oEconomia->carregar($oResultado->d82_economia);
      $oPedido->setEconomia($oEconomia);
    }

    $sSqlTipos = $this->oPedidoTipoDAO->sql_query_file(null, "d66_arretipo", null, "d66_codigo = {$oPedido->getCodigo()}");
    $rsResultadoTipos = db_query($sSqlTipos);
    $iQuantidadeTipos = pg_num_rows($rsResultadoTipos);

    for ($iTipo = 0; $iTipo < $iQuantidadeTipos; $iTipo++) {

      $oPedidoTipo = pg_fetch_object($rsResultadoTipos, $iTipo);
      $oTipoDebito = new TipoDebito($oPedidoTipo->d66_arretipo);
      $oPedido->adicionarTipoDebito($oTipoDebito);
    }

    return $oPedido;
  }
}
