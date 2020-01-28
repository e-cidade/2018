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
 * Class BalancoPatrimonialDcasp
 */
final class BalancoPatrimonialDcasp extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 131;

  public function getDados() {

    $sWhereBalanceteVerificacao = " c61_instit in ({$this->getInstituicoes()}) ";

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');

    $oDataFinalAnterior = clone $this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    $rsBalanceteVerificacaoAtual =  db_planocontassaldo_matriz($this->iAnoUsu,
                                                               $this->getDataInicial()->getDate(),
                                                               $this->getDataFinal()->getDate(),
                                                               false,
                                                               $sWhereBalanceteVerificacao,
                                                               '',
                                                               'true',
                                                               'false'
                                                              );
//db_criatabela($rsBalanceteVerificacaoAtual);exit;
    db_query("drop table work_pl");

    $rsBalanceteVerificacaoAnterior =  db_planocontassaldo_matriz($this->iAnoUsu - 1,
                                                                  $oDataInicialAnterior->getDate(),
                                                                  $oDataFinalAnterior->getDate(),
                                                                  false,
                                                                  $sWhereBalanceteVerificacao,
                                                                  '',
                                                                  'true',
                                                                  'false'
                                                                 );
    db_query("drop table work_pl");


    $oVariacoesPatrimoniais = new VariacaoPatrimonialDCASP($this->iAnoUsu, 132, $this->iCodigoPeriodo);
    $oVariacoesPatrimoniais->setInstituicoes($this->getInstituicoes());
    $aDadosVariacoesPatrimoniais = $oVariacoesPatrimoniais->getDados();

    $aContasVerificarFinanceiro = array();
    $aContasVerificarPatrimonio = array();

    /**
     * Buscamos todas as contas quem tenham superavit financeiro e patrimonial
     */
    $sWhereContasSuperAvit     = "c60_identificadorfinanceiro in('F', 'P') and c60_anousu = {$this->iAnoUsu} ";
    $sWhereContasSuperAvit    .= " and substr(c60_estrut, 1, 1) in('1', '2')";

    $oDaoConplano              = new cl_conplano();
    $sSqlContas                = $oDaoConplano->sql_query_file(null,null, "*", null, $sWhereContasSuperAvit);
    $rsContasSuperAvit         = $oDaoConplano->sql_record($sSqlContas);
    if ($rsContasSuperAvit && $oDaoConplano->numrows > 0) {

      for ($iConta = 0; $iConta < $oDaoConplano->numrows; $iConta++) {

        $oDadosConta = db_utils::fieldsMemory($rsContasSuperAvit, $iConta);
        if ($oDadosConta->c60_identificadorfinanceiro == 'F') {
          $aContasVerificarFinanceiro[] = $oDadosConta->c60_estrut;
        }
        if ($oDadosConta->c60_identificadorfinanceiro == 'P') {
          $aContasVerificarPatrimonio[] = $oDadosConta->c60_estrut;
        }
      }
    }
    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $iLinha =>  $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                            $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $aColunas) {
        foreach ($aColunas->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      $sFormulas  = "(substr(#estrutural, 0, 1) == 1 && #sinal_final == 'C') || (substr(#estrutural, 0, 1) == 2 && #sinal_final == 'D') ? #saldo_final *= -1 : #saldo_final";
      $sFormulaCalculo  = $sFormulas;
      $oColuna          = new stdClass();
      if (in_array($iLinha, array(72, 74))) {

        $oColuna->dados  = $aContasVerificarFinanceiro;
        $sFormulaCalculo = 'in_array(#estrutural, $oDados->coluna->dados) ? #saldo_final : 0';
      }
      if (in_array($iLinha, array(73, 75))) {

        $oColuna->dados  = $aContasVerificarPatrimonio;
        $sFormulaCalculo = 'in_array(#estrutural, $oDados->coluna->dados) ? #saldo_final : 0';
      }

      $oColuna->nome    = 'vlrexanter';
      $oColuna->formula = $sFormulaCalculo;
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacaoAnterior,
                                                  $oLinha,
                                                  array($oColuna),
                                                  RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                );

      $oColuna->nome    = 'vlrexatual';
      $oColuna->formula = $sFormulaCalculo;
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacaoAtual,
                                                 $oLinha,
                                                  array($oColuna),
                                                  RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                );

      unset($oLinha->oLinhaRelatorio);
    }

    $aLinhas[66]->vlrexanter = $aDadosVariacoesPatrimoniais[90]->vlrexanter;
    $aLinhas[66]->vlrexatual = $aDadosVariacoesPatrimoniais[90]->vlrexatual;
    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}