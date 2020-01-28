<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral;

use \ECidade\Tributario\Arrecadacao\EmissaoGeral\ParcelaUnica;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;

class ParcelaUnicaRepository
{

  /**
   * @var \cl_emissaogeralparcelaunica
   */
  private $oDao;

  public function __construct()
  {
    $this->oDao = new \cl_emissaogeralparcelaunica();
  }

  /**
   * Persiste os dados da parcela unica
   *
   * @param ParcelaUnica $oParcelaUnica
   * @throws \Exception
   * @return ParcelaUnica
   */
  public function add(ParcelaUnica $oParcelaUnica)
  {

    $this->oDao->tr05_sequencial = null;
    $this->oDao->tr05_emissaogeral = $oParcelaUnica->getEmissaoGeral()->getCodigo();
    $this->oDao->tr05_dataoperacao = $oParcelaUnica->getDataOperacao()->getDate();
    $this->oDao->tr05_datavencimento = $oParcelaUnica->getDataVencimento()->getDate();
    $this->oDao->tr05_percentual = $oParcelaUnica->getPercentual();

    $this->oDao->incluir(null);

    if ($this->oDao->erro_status == "2") {
      throw new \Exception($this->oDao->erro_msg);
    }

    $oParcelaUnica->setCodigo($this->oDao->tr05_sequencial);
    return $oParcelaUnica;
  }

  public function getParcelas(EmissaoGeral $oEmissao)
  {
    $sSqlParcelas = $this->oDao->sql_query_file(
      null,
      "*",
      null,
      "tr05_emissaogeral = {$oEmissao->getCodigo()}"
    );
    $rsParcelas = \db_query($sSqlParcelas);

    if (!$rsParcelas) {
      throw new \Exception("Erro ao buscar dados das parcelas unicas.");
    }

    return \db_utils::makeCollectionFromRecord($rsParcelas, function($oDados) use($oEmissao) {

      $oParcela = new ParcelaUnica();
      $oParcela->setEmissaoGeral($oEmissao);
      $oParcela->setCodigo($oDados->tr05_sequencial);
      $oParcela->setDataOperacao(new \DBDate($oDados->tr05_dataoperacao));
      $oParcela->setDataVencimento(new \DBDate($oDados->tr05_datavencimento));
      $oParcela->setPercentual($oDados->tr05_percentual);

      return $oParcela;
    });
  }
}
