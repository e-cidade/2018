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
 * Classe para controle dos dados da emissao do Fluxo Financeiro do DCASP
 *
 * @package contabilidade
 * @subpackage relatorios
 * @version $Revision: 1.8 $
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 *
 */
final class FluxoCaixaDCASP extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 133;

  /**
   * Retorna os Dados para emissão do Relatório
   * @return array
   */
  public function getDados() {

    $sWhereReceita = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa = " o58_instit in ({$this->getInstituicoes()}) ";
    $sWherePlano   = " c61_instit in ({$this->getInstituicoes()}) ";

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');
    $oDataFinalAnterior = clone$this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');
    $aLinhasUtilizamBalanceteReceita     = array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 15, 16, 17, 18, 33, 34, 40);
    $aLinhasUtilizamBalanceteDespesa     = array(20, 22, 23, 24, 27, 28, 29, 30, 36, 37, 42);
    $aLinhasUtilizamBalanceteVerificacao = array(45, 46);

    /**
     * Carregar a Receita do exericio atual
     */

    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          $sWhereReceita,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate());

    db_query("drop table work_receita");


    /**
     * Receita do ano Anterior
     */
    $rsBalanceteReceitaAnoAnterior = db_receitasaldo(11, 1, 3, true,
                                                     $sWhereReceita,
                                                     $this->iAnoUsu -1 ,
                                                     $oDataInicialAnterior->getDate(),
                                                     $oDataFinalAnterior->getDate()
                                                    );

    db_query("drop table work_receita");

    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate());

    $rsBalanceteDespesaAnterior = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                                  $this->iAnoUsu -1,
                                                  $oDataInicialAnterior->getDate(),
                                                  $oDataFinalAnterior->getDate()
                                                 );

    $rsBalanceteVerificacao =  db_planocontassaldo_matriz($this->iAnoUsu,
                                                          $this->getDataInicial()->getDate(),
                                                          $this->getDataFinal()->getDate(),
                                                          false,
                                                          $sWherePlano,
                                                          '',
                                                          'true',
                                                          'false'
                                                         );
    db_query("drop table work_pl");

    $rsBalanceteVerificacaoAnterior =  db_planocontassaldo_matriz($this->iAnoUsu - 1,
                                                          $oDataInicialAnterior->getDate(),
                                                          $oDataFinalAnterior->getDate(),
                                                          false,
                                                          $sWherePlano,
                                                          '',
                                                          'true',
                                                          'false'
                                                        );

    $aLinhas = $this->getLinhasRelatorio();

    $oGroupBy            = new stdClass();
    $oGroupBy->campo     = "o58_funcao";
    $oGroupBy->descricao = "o52_descr";
    $oGroupBy->nome      = "funcao";

    foreach ($aLinhas as $iLinha =>  $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValor) {
        foreach ($oValor->colunas as $oColuna) {
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
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                   $oLinha,
                                                   array($oColuna),
                                                    RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                   );
        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = '#saldo_arrecadado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceitaAnoAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                  );

      }

      /**
       * Contas configuradas para Utilizar despesa
       */
      if (in_array($iLinha, $aLinhasUtilizamBalanceteDespesa)) {


        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = "#pago_acumulado";
        if ($oLinha->ordem == 20) {
          $oColuna->agrupar = $oGroupBy;
        }
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                  );

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = "#pago_acumulado";
        if ($oLinha->ordem == 20) {
          $oColuna->agrupar = $oGroupBy;
        }

        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesaAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                  );
      }
      if (in_array($iLinha, $aLinhasUtilizamBalanceteVerificacao)) {

        $sFormula = "#saldo_final";
        if ($iLinha  == 45) {
          $sFormula = "#saldo_anterior";
        }
        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexanter';
        $oColuna->formula = $sFormula;
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacaoAnterior,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                  );

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = $sFormula;
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                  );
      }
      unset($oLinha->oLinhaRelatorio);
    }

    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}