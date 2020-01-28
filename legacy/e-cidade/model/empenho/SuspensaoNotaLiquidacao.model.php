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

class SuspensaoNotaLiquidacao {

  const MENSAGENS = "financeiro.empenho.SuspensaoNotaLiquidacao.";

  /**
   * @var NotaLiquidacao
   */
  private $oNotaLiquidacao;

  /**
   * SuspensaoNotaLiquidacao constructor.
   *
   * @param NotaLiquidacao $oNotaLiquidacao
   *
   * @throws ParameterException
   */
  public function __construct(NotaLiquidacao $oNotaLiquidacao) {

    if (empty($oNotaLiquidacao)) {
      throw new ParameterException(_M(self::MENSAGENS . 'nota_liquidacao_necessaria'));
    }

    $iCodigoNota = $oNotaLiquidacao->getCodigoNota();
    if (empty($iCodigoNota)) {
      throw new ParameterException(_M(self::MENSAGENS . 'nota_liquidacao_necessaria'));
    }

    $this->oNotaLiquidacao = $oNotaLiquidacao;
  }

  /**
   * Verifica se a nota de liquidação está liberada para pagamento, ou seja, não tem suspensão ou não tem suspensão
   * sem retorno.
   * @return bool
   *
   * @throws DBException
   */
  public function liberadoPagamento() {

    $iCodigoNota = $this->oNotaLiquidacao->getCodigoNota();

    $oDaoEmpNotaSuspensao = new cl_empnotasuspensao();

    $sCampos = "cc36_sequencial";
    $sWhere  = " cc36_empnota = {$iCodigoNota} and cc36_dataretorno is null ";
    $sSqlSuspensao = $oDaoEmpNotaSuspensao->sql_query_file(null, $sCampos, null, $sWhere);
    $rsSuspensao   = db_query($sSqlSuspensao);

    if (!$rsSuspensao) {
      throw new DBException(_M(self::MENSAGENS . 'erro_busca_suspensao'));
    }

    return pg_num_rows($rsSuspensao) == 0;
  }

  /**
   * Executação a operação de suspensão/retorno da nota de liquidação.
   * @param DBDate $oData
   * @param string $sJustificacao
   *
   * @return bool
   * @throws ParameterException
   */
  public function executar(DBDate $oData, $sJustificacao) {

    if (empty($oData)) {
      throw new ParameterException(_M(self::MENSAGENS . 'data_obrigatoria'));
    }

    if (empty($sJustificacao)) {
      throw new ParameterException(_M(self::MENSAGENS . 'justificativa_obrigatoria'));
    }

    if ($this->liberadoPagamento()) {
      return $this->suspender($oData, $sJustificacao);
    }
    return $this->retornar($oData, $sJustificacao);
  }

  /**
   * Executa a suspensão da nota de liquidação.
   * @param DBDate $oData
   * @param string $sJustificacao
   *
   * @return bool
   * @throws DBException
   */
  private function suspender(DBDate $oData, $sJustificacao) {

    $oDaoEmpNotaSuspensao = new cl_empnotasuspensao();

    $oDaoEmpNotaSuspensao->cc36_sequencial = null;
    $oDaoEmpNotaSuspensao->cc36_empnota    = $this->oNotaLiquidacao->getCodigoNota();
    $oDaoEmpNotaSuspensao->cc36_datasuspensao = $oData->getDate(DBDate::DATA_EN);
    $oDaoEmpNotaSuspensao->cc36_dataretorno   = null;
    $oDaoEmpNotaSuspensao->cc36_justificativasuspensao = $sJustificacao;
    $oDaoEmpNotaSuspensao->cc36_justificativaretorno   = null;
    if(!$oDaoEmpNotaSuspensao->incluir(null)) {
      throw  new DBException(_M(self::MENSAGENS . 'erro_salvar_suspensao'));
    }
    return true;
  }

  /**
   * Executa o retorno de uma nota suspensa.
   * @param DBDate $oData
   * @param string $sJustificacao
   *
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  private function retornar(DBDate $oData, $sJustificacao) {

    $iCodigoNota = $this->oNotaLiquidacao->getCodigoNota();

    $sCampos = "*";
    $sOrder  = "cc36_sequencial";
    $sWhere  = "cc36_empnota = {$iCodigoNota} and cc36_dataretorno is null";

    $oDaoEmpNotaSuspensao = new cl_empnotasuspensao();
    $sSqlRetorno = $oDaoEmpNotaSuspensao->sql_query_file(null, $sCampos, $sOrder, $sWhere);
    $rsRetorno   = db_query($sSqlRetorno);
    if (!$rsRetorno) {
      throw new DBException(_M(self::MENSAGENS . 'erro_busca_suspensao'));
    }
    $iLinhas = pg_num_rows($rsRetorno);
    if ($iLinhas == 0) {
      throw new BusinessException(_M(self::MENSAGENS . 'suspensao_nao_encontrada_retorno'));
    }

    $oSuspensao = db_utils::fieldsMemory($rsRetorno, --$iLinhas);

    $oDaoEmpNotaSuspensao->cc36_sequencial = $oSuspensao->cc36_sequencial;
    $oDaoEmpNotaSuspensao->cc36_empnota    = $oSuspensao->cc36_empnota;
    $oDaoEmpNotaSuspensao->cc36_datasuspensao = $oSuspensao->cc36_datasuspensao;
    $oDaoEmpNotaSuspensao->cc36_dataretorno   = $oData->getDate(DBDate::DATA_EN);
    $oDaoEmpNotaSuspensao->cc36_justificativasuspensao = $oSuspensao->cc36_justificativasuspensao;
    $oDaoEmpNotaSuspensao->cc36_justificativaretorno   = $sJustificacao;

    if (!$oDaoEmpNotaSuspensao->alterar($oSuspensao->cc36_sequencial)) {
      throw new DBException(_M(self::MENSAGENS . 'erro_salvar_suspensao'));
    }
    return true;
  }
}