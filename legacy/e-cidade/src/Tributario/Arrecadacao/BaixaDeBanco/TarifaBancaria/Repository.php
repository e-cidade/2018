<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\BaixaDeBanco\TarifaBancaria;

use ECidade\Tributario\Arrecadacao\Relatorio\TarifaArrecadacao;
/**
 * Classe repossável por fazer comunicação com o banco de dados referente
 * as dados das tarifa bancaria
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class Repository
{
  /**
   * Função que exclui a tarifa bancária
   *
   * @param integer $iSequencial Código da tarifa bancária
   * @param integer $iCodRet     Código do arquivo bancário
   * @param integer $iIdRet      Código do registro do arquivo bancário
   * @throws \DBException
   */
  public function excluir($iSequencial = null, $iCodRet = null, $iIdRet = null)
  {
    $oDaoDisBancoTarifa = new \cl_disbancotarifa();
    $sWhereTarifa       = null;

    if (!is_null($iIdRet)) {
      $sWhereTarifa = "k179_idret = $iIdRet";
    }

    if (!is_null($iCodRet)) {

      $sWhereTarifa  = " k179_idret in (select idret              ";
      $sWhereTarifa .= "                  from disbanco           ";
      $sWhereTarifa .= "                 where codret = $iCodRet) ";
    }

    $oDaoDisBancoTarifa->excluir($iSequencial, $sWhereTarifa);

    if ($oDaoDisBancoTarifa->erro_status == "0") {
      throw new \DBException("Erro ao excluir tarifa bancária.");
    }
  }

  /**
   * Função que salva as informações da tarifa bancária na base de dados
   *
   * @param  \stdClass $oDadosTarifa
   * @throws \DBException
   */
  public function salvar(\stdClass $oDadosTarifa)
  {
    $oDisBancoTarifDao                        = new \cl_disbancotarifa();
    $oDisBancoTarifDao->k179_idret            = $oDadosTarifa->iIdRet;
    $oDisBancoTarifDao->k179_formaarrecadacao = $oDadosTarifa->iFormaArrecadacao;
    $oDisBancoTarifDao->k179_valor            = $oDadosTarifa->nValor;
    $oDisBancoTarifDao->incluir(null);

    if ($oDisBancoTarifDao->erro_status == "0") {
      throw new \DBException("Operação Abortada! \nErro na inclusão da tarifa bancária \n $oDisBancoTarifDao->erro_msg");
    }
  }

  /**
   * Função que busca os dados das tarifas bancárias para o relatório
   *
   * @param \Banco $banco
   * @param \DBDate $dataInicial
   * @param \DBDate $dataFinal
   * @return \stdClass
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getDadosRelatorio(\Banco $banco, \DBDate $dataInicial, \DBDate $dataFinal)
  {
    $campos = array(
      'disarq.codret as codigo_arquivo',
      'disarq.arqret as nome_arquivo',
      'formaarrecadacao.k178_descricao as forma_arrecadacao',
      'disbancotarifa.k179_valor as valor_tarifa',
      'sum(disbancotarifa.k179_valor) as total_tarifa',
      'sum(disbanco.vlrtot) as total_arrecadado',
      'count(*) as quantidade'
    );

    $where  = "disarq.dtarquivo between '{$dataInicial->getDate()}' and '{$dataFinal->getDate()}'";
    $where .= " and cast(lpad(disarq.k15_codbco, 3, '0') as text) = '{$banco->getCodigo()}'";
    $where .= " group by disarq.codret, disarq.arqret, formaarrecadacao.k178_descricao, disbancotarifa.k179_valor";

    $daoTarifas = new \cl_disbancotarifa();
    $sqlBuscaTarifas = $daoTarifas->sql_query_tarifas(null, implode(',', $campos), 'disarq.dtarquivo', $where);
    $rsBuscaTarifas  = db_query($sqlBuscaTarifas);
    if (!$rsBuscaTarifas) {
      throw new \DBException(_M(TarifaArrecadacao::MENSAGENS . 'erro_busca_tarifas'));
    }

    $totalRegistros = pg_num_rows($rsBuscaTarifas);

    if ($totalRegistros === 0) {
      throw new \BusinessException( _M(TarifaArrecadacao::MENSAGENS . 'sem_registros') );
    }

    $aTarifas = \db_utils::getCollectionByRecord($rsBuscaTarifas);
    return $aTarifas;
  }
}