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

require_once(modification("model/contabilidade/contacorrente/ContaCorrenteRepositoryBase.model.php"));

/**
 * Classe repositório com dados para o relatório balancete de conta corrente Disponibilidade Financeira
 * @package contabilidade
 * @subpackage contacorrente
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.11 $
 */
class DisponibilidadeFinanceiraRepository extends ContaCorrenteRepositoryBase {

  /**
   * @param string $dtInicial - Data Inicial do relatório, utilizado nos filtros
   * @param string $dtFinal   - Data Final   do relatório, utilizado nos filtros
   */
  public function __construct($dtInicial, $dtFinal) {

    parent::__construct(DisponibilidadeFinanceira::CONTA_CORRENTE, $dtInicial, $dtFinal);
    $this->setAtributos();
  }

  /**
   * Busca os dados para a conta corrente de Disponibilidade Financeira
   * Adiciona no array $this->aContaCorrenteDetalhe o resultado da busca
   */
  private function setAtributos() {

    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
    $sCampos                  = "c19_contacorrente, c19_orctiporec, c19_concarpeculiar, c19_instit, c19_reduz, c19_conplanoreduzanousu, c19_sequencial";
    $sWhere                   = "     c19_contacorrente = " . DisponibilidadeFinanceira::CONTA_CORRENTE;
    $sWhere                  .= " and c69_data between '{$this->dtInicial}' and '{$this->dtFinal}'";
    $sWhere                  .= " group by {$sCampos} ";
    $sOrder                   = "c19_instit, c19_contabancaria, c19_reduz";
    $sSqlBuscaLancamentos     = $oDaoContaCorrenteDetalhe->sql_query_lancamentos(null, $sCampos, $sOrder, $sWhere);
    $rsBuscaLancamentos       = $oDaoContaCorrenteDetalhe->sql_record($sSqlBuscaLancamentos);

    if ($oDaoContaCorrenteDetalhe->numrows == 0) {
      return false;
    }

    for($iLancamento = 0; $iLancamento < $oDaoContaCorrenteDetalhe->numrows; $iLancamento++) {
      $this->aContaCorrenteDetalhe[] = db_utils::fieldsMemory($rsBuscaLancamentos, $iLancamento);
    }

    /**
     * Buscamos as contas contábeis (conplano) e agrupamos os dados da conta corrente
     */
    $this->getContasContabeis();
    $this->agrupar();
  }

  /**
   * Agrupa conforme as regras da conta corrente
   */
  private function agrupar() {

    $aContas = array();

    /**
     * Para cada índice do array, buscamos seus atributos e os agrupamos
     */
    foreach ($this->aContaCorrenteDetalhe as $oConta) {

      /**
       * Busca os dados da instituição
       */
      $oDaoDbConfig  = db_utils::getDao("db_config");
      $sCamposInstit = "nomeinst";
      $sSqlInstit    = $oDaoDbConfig->sql_query_file($oConta->c19_instit, $sCamposInstit);
      $rsInstit      = $oDaoDbConfig->sql_record($sSqlInstit);

      if ($oDaoDbConfig->numrows == 0) {
        continue;
      }

      $sInstituicao = db_utils::fieldsMemory($rsInstit, 0)->nomeinst;

      /**
       * Busca os dados do recurso vinculado
       */
      $oDaoOrcTipoRec = db_utils::getDao("orctiporec");
      $sSqlRecurso    = $oDaoOrcTipoRec->sql_query_file($oConta->c19_orctiporec);
      $rsRecurso      = $oDaoOrcTipoRec->sql_record($sSqlRecurso);

      if ($oDaoOrcTipoRec->numrows == 0) {
        continue;
      }

      $sRecurso = db_utils::fieldsMemory($rsRecurso, 0)->o15_descr;

      /**
       * Busca os dados da característica peculiar
       */
      $oDaoCaracteristicaPeculiar = db_utils::getDao("concarpeculiar");
      $sSqlCaracteristicaPeculiar = $oDaoCaracteristicaPeculiar->sql_query_file($oConta->c19_concarpeculiar);
      $rsCaracteristicaPeculiar   = $oDaoCaracteristicaPeculiar->sql_record($sSqlCaracteristicaPeculiar);

      if ($oDaoCaracteristicaPeculiar->numrows == 0) {
        continue;
      }

      $sCaracteristicaPeculiar = db_utils::fieldsMemory($rsCaracteristicaPeculiar, 0)->c58_descr;

      /**
       * Agrupa por instituição, recurso vinculado e por característica peculiar
       */
      $sAgrupamento = $oConta->c19_instit.$oConta->c19_orctiporec.$oConta->c19_concarpeculiar;
      $aContas[$sAgrupamento] = new stdClass();
      $aContas[$sAgrupamento]->aCabecalho = array();

      $oStdInstituicao = new stdClass();
      $oStdInstituicao->sIdentificador = "Instituição";
      $oStdInstituicao->sValor         = $sInstituicao;

      $oStdRecurso = new stdClass();
      $oStdRecurso->sIdentificador = "Recurso Vinculado";
      $oStdRecurso->sValor         = $sRecurso;

      $oStdCaracteristicaPeculiar = new stdClass();
      $oStdCaracteristicaPeculiar->sIdentificador = "C. Peculiar";
      $oStdCaracteristicaPeculiar->sValor         = $sCaracteristicaPeculiar;


      $aContas[$sAgrupamento]->aCabecalho[] = $oStdInstituicao;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdRecurso;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdCaracteristicaPeculiar;

      $aContas[$sAgrupamento]->aContas[] = $oConta;
    }

    $this->aContaCorrenteDetalhe = $aContas;
  }

}
?>