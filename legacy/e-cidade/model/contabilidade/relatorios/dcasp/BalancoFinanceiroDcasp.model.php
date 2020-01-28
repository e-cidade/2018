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


/**
 * Classe para controle dos dados da emissao do BAlanco Financiero do DCASP
 *
 * @package contabilidade
 * @subpackage relatorios
 * @version $Revision: 1.13 $
 * @author Bruno De Boni bruno.boni@dbseller.com.br
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 *
 */
final class BalancoFinanceiroDcasp extends RelatoriosLegaisBase {

  private $rsBalanceteReceita             = null;
  private $rsBalanceteReceitaAnoAnterior  = null;
  private $rsBalanceteDespesa             = null;
  private $rsBalanceteDespesaAnterior     = null;
  private $rsBalanceteVerificacao         = null;
  private $rsBalanceteVerificacaoAnterior = null;
  private $aLinhasRelatorio = array();

  private $aLinhasComRecurso = array(4, 5, 6, 15, 16, 17);

  /**
   * @type int
   */
  const CODIGO_RELATORIO = 129;

  /**
   * Contém os Recursos que não foram configurados
   * @var array
   */
  private $aRecursosNaoConfigurados = array();

  /**
   * Retorna os recursos vinculados que não foram vinculados nas configurações
   * @return Recurso[]
   */
  public function getRecursosNaoConfigurados() {
    return $this->aRecursosNaoConfigurados;
  }

  private function calculaValoresRelatorio() {

    $sWhereReceita = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa = " o58_instit in ({$this->getInstituicoes()}) ";
    $sWherePlano   = " c61_instit in ({$this->getInstituicoes()}) ";

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');

    $oDataFinalAnterior   = clone $this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    $this->oDataInicialAnterior = $oDataInicialAnterior;
    $this->oDataFinalAnterior   = $oDataFinalAnterior;

    /**
     * Carregar a Receita do exericio atual
     */
    $this->rsBalanceteReceita = db_receitasaldo( 11, 1, 3, true,
                                                 $sWhereReceita,
                                                 $this->iAnoUsu,
                                                 $this->getDataInicial()->getDate(),
                                                 $this->getDataFinal()->getDate() );

    db_query("drop table work_receita");

    /**
     * Receita do ano Anterior
     */
    $this->rsBalanceteReceitaAnoAnterior = db_receitasaldo( 11, 1, 3, true,
                                                            $sWhereReceita,
                                                            $this->iAnoUsu -1 ,
                                                            $oDataInicialAnterior->getDate(),
                                                            $oDataFinalAnterior->getDate() );

    db_query("drop table work_receita");

    $this->rsBalanceteDespesa = db_dotacaosaldo( 8,2,2, true, $sWhereDespesa,
                                                 $this->iAnoUsu,
                                                 $this->getDataInicial()->getDate(),
                                                 $this->getDataFinal()->getDate() );

    $this->rsBalanceteDespesaAnterior = db_dotacaosaldo( 8,2,2, true, $sWhereDespesa,
                                                         $this->iAnoUsu -1,
                                                         $oDataInicialAnterior->getDate(),
                                                         $oDataFinalAnterior->getDate() );

    $this->rsBalanceteVerificacao =  db_planocontassaldo_matriz( $this->iAnoUsu,
                                                                 $this->getDataInicial()->getDate(),
                                                                 $this->getDataFinal()->getDate(),
                                                                 false,
                                                                 $sWherePlano,
                                                                 '',
                                                                 'true',
                                                                 'false' );

    db_query("drop table work_pl");

    $this->rsBalanceteVerificacaoAnterior =  db_planocontassaldo_matriz( $this->iAnoUsu - 1,
                                                                         $oDataInicialAnterior->getDate(),
                                                                         $oDataFinalAnterior->getDate(),
                                                                         false,
                                                                         $sWherePlano,
                                                                         '',
                                                                         'true',
                                                                         'false' );

    $this->aLinhasRelatorio = $this->getLinhasRelatorio();
  }

  /**
   * Retorna os Dados para emissão do Relatório
   */
  public function getDados() {

    $this->calculaValoresRelatorio();

    $aLinhasUtilizamBalanceteReceita       = array(2, 4, 5, 6, 7);
    $aLinhasUtilizamBalanceteDespesa       = array(13, 14, 15, 16, 17);
    $aLinhasUtilizamBalanceteVerificacao   = array(11, 20);
    $aLinhasUtilizamLancamentoPorDocumento = array(8, 9, 18, 19);

    foreach ($this->aLinhasRelatorio as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      /**
       * Analisamos cada conta configurada para a linha, conforme o balancete de Receita
       */
      if (in_array($iLinha, $aLinhasUtilizamBalanceteReceita)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#saldo_arrecadado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteReceita,
                                                   $oLinha,
                                                   array($oColuna),
                                                    RelatoriosLegaisBase::TIPO_CALCULO_RECEITA);

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = '#saldo_arrecadado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteReceitaAnoAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_RECEITA);

      }

      /**
       * Contas configuradas para Utilizar despesa
       */
      if (in_array($iLinha, $aLinhasUtilizamBalanceteDespesa)) {


        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#empenhado_acumulado - #anulado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteDespesa,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = '#empenhado_acumulado - #anulado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteDespesaAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);
      }

      if (in_array($iLinha, $aLinhasUtilizamBalanceteVerificacao)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = '#saldo_final';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteVerificacaoAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#saldo_final';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteVerificacao,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);

      }

      if ($iLinha == 10) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = '#saldo_anterior';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteVerificacaoAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#saldo_anterior';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteVerificacao,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);
      }

      /**
       * Busca valores "a liquidar" + "a pagar liquidado" do Balancete da Despesa
       */
      if ($iLinha == 9) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '(#empenhado - #anulado - #liquidado) + #atual_a_pagar_liquidado';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteDespesa,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = '(#empenhado - #anulado - #liquidado) + #atual_a_pagar_liquidado';
        RelatoriosLegaisBase::calcularValorDaLinha($this->rsBalanceteDespesaAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);
      }


      if ($iLinha == 19) {

        /**
         * Saldo atual até o periodo
         */
        $rsRestosPagar    = $this->getResultSetRestosAPagar($this->getDataInicial(), $this->getDataFinal());
        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = "#vlrpag + #vlrpagnproc";
        RelatoriosLegaisBase::calcularValorDaLinha(
          $rsRestosPagar,
          $oLinha,
          array($oColuna),
          RelatoriosLegaisBase::TIPO_CALCULO_RESTO
        );

        /**
         * Saldo Anterior até o periodo
         */
        $rsRestosPagar    = $this->getResultSetRestosAPagar($this->oDataInicialAnterior, $this->oDataFinalAnterior);
        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = "#vlrpag + #vlrpagnproc";
        RelatoriosLegaisBase::calcularValorDaLinha(
          $rsRestosPagar,
          $oLinha,
          array($oColuna),
          RelatoriosLegaisBase::TIPO_CALCULO_RESTO
        );
      }

      /**
       * Linhas que utilizam valores totais documentos
       */
      if (in_array($iLinha, $aLinhasUtilizamLancamentoPorDocumento)) {

        $oValores = new stdClass();
        switch ($iLinha) {

          /**
           * Linha 8
           * Busca valores das transferencias financeiras recebidas
           * + 130 Recebimento de Transferência Financeira
           * - 131 Estorno de Receb de Transferência Financeira
           */
          case 8 :
            $oValores = $this->getValoresDocumentos(array(130));
          break;

          /**
           * Linha 9
           * Busca valores de recebimentos extra orcamentarios
           * + Saldo a pagar geral dos empenhos do exercício
           *   ("a liquidar" + "a pagar liquidado" do Balancete da Despesa)
           * + 150 Recebimento de Caução
           * - 152 Recebimento de Caução - Estorno
           * + 160 Depósitos de Diversas Origens - Recebimento
           * - 162 Depósitos de Diversas Origens - Estorno de Recebimento.
           */
          case 9:
            $oValores = $this->getValoresDocumentos(array(150, 160));
          break;

          /**
           * Linha 18
           * Busca valores de transferencias financeiras concedidas
           * + 120 Pagamento de Transferência Financeira
           * - 121 Estorno de Pagamento de Transferência Financeira
           */
          case 18 :
            $oValores = $this->getValoresDocumentos(array(120));
          break;

          /**
           * + Montante dos Pagamentos de Restos a Pagar processados e não processados (fonte: EMPENHO > RELATÓRIOS > RELATÓRIOS DE MOVIMENTAÇÃO > EXECUÇÃO DE RESTOS A PAGAR)
           * + Coddoc 151 ? Devolução de Caução
           * - Coddoc 153 ? Devolução de Caução ? Estorno
           * + Coddoc 161 ? Depósitos de Diversas Origens - Pagamento
           * - Coddoc 163 ? Depósitos de Diversas Origens ? Estorno de Pagamento.
           */
          case 19:
            $oValores = $this->getValoresDocumentos(array(151, 161));
            break;
        }

        $oLinha->vlrexanter += $oValores->nValorAnterior;
        $oLinha->vlrexatual += $oValores->nValorAtual;
      }

      unset($oLinha->oLinhaRelatorio);
    }

    $this->processaTotalizadores($this->aLinhasRelatorio);
    return $this->aLinhasRelatorio;
  }

  /**
   * @param DBDate $dtInicial
   * @param DBDate $dtFinal
   * @return bool|resource
   */
  private function getResultSetRestosAPagar(DBDate $dtInicial, DBDate $dtFinal) {

    $oDaoRestosAPagar = new cl_empresto();
    $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";
    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo(
      $this->iAnoUsu,
      $sWhereRestoPagar,
      $dtInicial->getDate(),
      $dtFinal->getDate()
    );
    return db_query($sSqlRestosaPagar);
  }


  /**
   * Busca valores atual e anterior de uma coleção de documentos
   * - busca documento inverso
   * - caso documento for de estorno, subtrai valores
   *
   * @param Array $aDocumentos
   * @return StdClass
   */
  private function getValoresDocumentos(Array $aDocumentos) {

    /**
     * StdClass retornado
     */
    $oStdValores = new StdClass();
    $oStdValores->nValorAnterior = 0;
    $oStdValores->nValorAtual = 0;

    /**
     * Eventos contabeis do exercicio atual
     */
    $aEventoContabilAtual = array();

    /**
     * Eventos contabeis do exercicio anterior
     */
    $aEventoContabilAnterior = array();

    foreach (explode(', ', $this->getInstituicoes()) as $iInstituicao) {

      /**
       * Percorre os documentos buscando eventos contabeis do exercicio atual e do anterior
       */
      foreach ($aDocumentos as $iDocumento) {

        /**
         * Evento contabil do exercicio atual
         */
        try {

          $oEventoContabilAtual = EventoContabilRepository::getEventoContabilByCodigo($iDocumento, $this->iAnoUsu, $iInstituicao);
          $oEventoContabilAtualInverso = $oEventoContabilAtual->getEventoInverso();
          $aEventoContabilAtual[$iDocumento][] = $oEventoContabilAtual;

          /**
           * Documento inverso do exercicio atual
           */
          if ($oEventoContabilAtualInverso) {
            $aEventoContabilAtual[$oEventoContabilAtualInverso->getCodigoDocumento()][] = $oEventoContabilAtualInverso;
          }

        } catch (Exception $oErro) {}

        try {

          /**
           * Evento contabil do exercicio anterior
           */
          $oEventoContabilAnterior = EventoContabilRepository::getEventoContabilByCodigo($iDocumento, $this->iAnoUsu - 1, $iInstituicao);
          $oEventoContabilAnteriorInverso = $oEventoContabilAnterior->getEventoInverso();
          $aEventoContabilAnterior[$iDocumento][] = $oEventoContabilAnterior;

          /**
           * Documento inverso do exercicio anterior
           */
          if ($oEventoContabilAnteriorInverso) {
            $aEventoContabilAnterior[$oEventoContabilAnteriorInverso->getCodigoDocumento()][] = $oEventoContabilAnteriorInverso;
          }

        } catch (Exception $oErro) {}

      } // foreach

    } // foreach

    /**
     * Calcula valores dos eventos contabeis do exercicio atual
     */
    foreach ($aEventoContabilAtual as $iDocumento => $aEventos) {

      foreach ($aEventos as $oEventoContabil) {

        $nValorAtual = RelatoriosLegaisBase::getValorLancamentoPorDocumentoPeriodo(
          $oEventoContabil, $this->getDataInicial(), $this->getDataFinal()
        );

        /**
         * Documento é de estorno
         */
        if (!$oEventoContabil->isEventoInclusao()) {

          $oStdValores->nValorAtual -= $nValorAtual;
          continue;
        }

        /**
         * Documento de inclusao
         */
        $oStdValores->nValorAtual += $nValorAtual;
      }
    }

    /**
     * Calcula valores dos eventos contabeis do exercicio anterior
     */
    foreach ($aEventoContabilAnterior as $iDocumento => $aEventos) {

      foreach ($aEventos as $oEventoContabil) {

        $nValorAnterior = RelatoriosLegaisBase::getValorLancamentoPorDocumentoPeriodo(
          $oEventoContabil, $this->oDataInicialAnterior, $this->oDataFinalAnterior
        );

        /**
         * Documento é de estorno
         */
        if (!$oEventoContabil->isEventoInclusao()) {

          $oStdValores->nValorAnterior -= $nValorAnterior;
          continue;
        }

        /**
         * Documento de inclusao
         */
        $oStdValores->nValorAnterior += $nValorAnterior;
      }
    }
    return $oStdValores;
  }

  /**
   * Retorna as linhas que devem possuir recurso configurado
   * @return array
   */
  public function getLinhasObrigaRecurso() {
    return $this->aLinhasComRecurso;
  }
}

