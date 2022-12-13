<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_app.utils.php"));
include(modification("model/arrecadacao/abatimento/Desconto.model.php"));

$oGet  = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php

 db_app::load('scripts.js');
 db_app::load('prototype.js');
 db_app::load('estilos.css');

?>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<?


  /**
   * Busca as Compensações.
   */

  $iInstit = db_getsession('DB_instit');

  if ( isset($oGet->numcgm) ) {

    $sInnerCredito = " inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = " and arrenumcgm.k00_numcgm = ".$oGet->numcgm;
    $sTipoPesquisa = "C";
    $sChavePesquisa= $oGet->numcgm;

    $sInnerCompensacao = " inner join arrenumcgm on arrenumcgm.k00_numpre =  abatimentoutilizacaodestino.k170_numpre ";
    $sInnerDevolucao   = " inner join arrenumcgm on arrenumcgm.k00_numpre = arreckey.k00_numpre ";
    $sWhereCompensacao = $sWhereCredito;
    $sWhereDevolucao   = $sWhereCompensacao;

  } else if ( isset($oGet->matric) ) {

    $sInnerCredito = " inner join arrematric on arrematric.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = " and arrematric.k00_matric = " . $oGet->matric;
    $sTipoPesquisa = "M";
    $sChavePesquisa= $oGet->matric;

    $sInnerCompensacao = " inner join arrematric on arrematric.k00_numpre = abatimentoutilizacaodestino.k170_numpre ";
    $sInnerDevolucao   = " inner join arrematric on arrematric.k00_numpre = arreckey.k00_numpre ";
    $sWhereCompensacao = $sWhereCredito;
    $sWhereDevolucao   = $sWhereCompensacao;

  } else if ( isset($oGet->inscr) ) {

    $sInnerCredito = " inner join arreinscr on arreinscr.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = " and arreinscr.k00_inscr = " . $oGet->inscr;
    $sTipoPesquisa = "I";
    $sChavePesquisa= $oGet->inscr;

    $sInnerCompensacao = " inner join arreinscr on arreinscr.k00_numpre = abatimentoutilizacaodestino.k170_numpre ";
    $sInnerDevolucao   = " inner join arreinscr on arreinscr.k00_numpre = arreckey.k00_numpre ";
    $sWhereCompensacao = $sWhereCredito;
    $sWhereDevolucao   = $sWhereCompensacao;

  } else {

    $sInnerCredito = "";
    $sWhereCredito = " and abatimentorecibo.k127_numprerecibo = " . $oGet->numpre;

    $sInnerCompensacao = "";
    $sWhereCompensacao = " abatimentoutilizacaodestino.k170_numpre = " . $oGet->numpre;

    $sInnerDevolucao = "";
    $sWhereDevolucao = " arreckey.k00_numpre = " . $oGet->numpre;

  }

  $sSqlCreditosDisponiveis  = " select k01_codigo,                                                                              ";
  $sSqlCreditosDisponiveis .= "        k125_datalanc,                                                                           ";
  $sSqlCreditosDisponiveis .= "        k125_sequencial,                                                                         ";
  $sSqlCreditosDisponiveis .= "        recibo.k00_tipo,                                                                         ";
  $sSqlCreditosDisponiveis .= "        recibo.k00_numpre,                                                                       ";
  $sSqlCreditosDisponiveis .= "        recibo.k00_numpar,                                                                       ";
  $sSqlCreditosDisponiveis .= "        sum(recibo.k00_valor) as k00_valor,                                                      ";
  $sSqlCreditosDisponiveis .= "        recibo.k00_tipo,                                                                         ";
  $sSqlCreditosDisponiveis .= "        arretipo.k00_descr                                                                       ";
  $sSqlCreditosDisponiveis .= "   from abatimentorecibo                                                                         ";
  $sSqlCreditosDisponiveis .= "        inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento   ";
  $sSqlCreditosDisponiveis .= "        inner join recibo     on recibo.k00_numpre          = abatimentorecibo.k127_numprerecibo ";
  $sSqlCreditosDisponiveis .= "        inner join arretipo   on arretipo.k00_tipo          = recibo.k00_tipo                    ";
  $sSqlCreditosDisponiveis .= "        inner join tabrec     on tabrec.k02_codigo          = recibo.k00_receit                  ";
  $sSqlCreditosDisponiveis .= "        inner join histcalc   on histcalc.k01_codigo        = recibo.k00_hist                    ";
  $sSqlCreditosDisponiveis .= "        {$sInnerCredito}                                                                         ";
  $sSqlCreditosDisponiveis .= "  where abatimento.k125_tipoabatimento = 4 and abatimento.k125_instit = {$iInstit}               ";
  $sSqlCreditosDisponiveis .= "        {$sWhereCredito}                                                                         ";
  $sSqlCreditosDisponiveis .= " group by                                                                                        ";
  $sSqlCreditosDisponiveis .= "      k01_codigo, k125_sequencial, recibo.k00_numpre, recibo.k00_numpar, arretipo.k00_descr,     ";
  $sSqlCreditosDisponiveis .= "      recibo.k00_tipo, k125_datalanc";

  $rsCreditosDisponiveis    = db_query($sSqlCreditosDisponiveis);
  $iLinhasCreditos          = pg_num_rows($rsCreditosDisponiveis);
  $aDadosSaida = array();

  /**
   * BUSCA OS DESCONTOS CONCEDIDOS
   * ADICIONANDO A VARIAVEL $aDadosSaida[]
   */
  // TODO: Verificar se deve aparecer no relatório das compensações utilizadas
  $aDescontos = !isset($sChavePesquisa) ?
                array() :
                Desconto::getDescontosPorOrigem($sTipoPesquisa, $sChavePesquisa);

  foreach ( $aDescontos as $oDescontos) {

    $oDados                  = new stdClass();
    $oDados->k01_codigo      = $oDescontos->getTipoAbatimento();
    $oDados->k125_sequencial = $oDescontos->getCodigo();
    $oDados->k00_tipo        = $oDescontos->getTipoDebito();
    $oDados->k00_descr       = !empty($oDados->k00_tipo) ? getDescricaoTipoDebito($oDescontos->getTipoDebito()) : "";
    $oDados->k125_datalanc   = $oDescontos->getDataLancamento()->getDate();
    $oDados->k00_hist        = Desconto::HISTORICO;
    $oDados->k01_descr       = 'DESCONTO';
    $oDados->k170_numpre     = $oDescontos->getNumpre();
    $oDados->k170_numpar     = $oDescontos->getNumpar();
    $oDados->k02_descr       = $oDescontos->getDescRec();

    if ( $oDescontos->getSituacao() == Abatimento::SITUACAO_CANCELADO ) {

      $oDados->k00_hist      = Desconto::HISTORICO_CANCELAMENTO;
      $oDados->k01_descr     = 'DESCONTO CANCELADO';
    }

    $oDados->k00_valor       = $oDescontos->getValor();
    $oDados->sTipo           = 'desconto';
    $aDadosSaida[]           = $oDados;
  }

  if (isset($oGet->devolucao)) {
    $iLinhasCreditos = 0;
  }

  if ( $iLinhasCreditos > 0 ) {

    /**
     * Somente compensações
     */
    for ( $iInd=0; $iInd < $iLinhasCreditos; $iInd++ ) {

      $oCredito = db_utils::fieldsMemory($rsCreditosDisponiveis,$iInd);
      $oDados = new stdClass();
      $oDados->k01_codigo      = $oCredito->k01_codigo;
      $oDados->k125_datalanc   = $oCredito->k125_datalanc;
      $oDados->k125_sequencial = $oCredito->k125_sequencial;
      $oDados->k02_descr       = $oCredito->k00_descr;
      $oDados->k00_tipo        = $oCredito->k00_tipo;
      $oDados->k00_descr       = $oCredito->k00_descr;
      $oDados->k01_descr       = 'COMPENSAÇÃO';
      $oDados->k170_numpre     = $oCredito->k00_numpre;
      $oDados->k170_numpar     = $oCredito->k00_numpar;
      $oDados->k00_valor       = $oCredito->k00_valor;
      $oDados->sTipo           = 'compensação';

      $aDadosSaida[] = $oDados;
    }
  }

  $sSqlCreditosCompensados  = "select distinct                                                                        ";
  $sSqlCreditosCompensados .= "       k157_abatimento, k157_observacao,                                               ";
  $sSqlCreditosCompensados .= "       sum(k170_valor) as k157_valor,                                                  ";
  $sSqlCreditosCompensados .= "       k157_data,                                                                      ";
  $sSqlCreditosCompensados .= "       k170_numpre, k170_numpar,                                                       ";
  $sSqlCreditosCompensados .= "       recibo.k00_tipo,                                                                ";
  $sSqlCreditosCompensados .= "       recibo.k00_receit,                                                              ";
  $sSqlCreditosCompensados .= "       arretipo.k00_descr                                                              ";
  $sSqlCreditosCompensados .= "  from abatimentoutilizacao                                                            ";
  $sSqlCreditosCompensados .= "       inner join abatimentoutilizacaodestino on k170_utilizacao = k157_sequencial     ";
  $sSqlCreditosCompensados .= "       inner join abatimento                  on k125_sequencial = k157_abatimento     ";
  $sSqlCreditosCompensados .= "       inner join abatimentorecibo            on k125_sequencial = k127_abatimento     ";
  $sSqlCreditosCompensados .= "       inner join recibo                      on recibo.k00_numpre = k127_numprerecibo ";
  $sSqlCreditosCompensados .= "       inner join arretipo                    on arretipo.k00_tipo = k170_tipo         ";
  $sSqlCreditosCompensados .= "       inner join tabrec                      on k02_codigo = k170_receit              ";
  $sSqlCreditosCompensados .= "        {$sInnerCompensacao}                                                           ";
  $sSqlCreditosCompensados .= " where k157_tipoutilizacao = '2' and abatimento.k125_instit = {$iInstit} ". $sWhereCompensacao;
  $sSqlCreditosCompensados .= " group by k157_abatimento, k157_observacao, k170_numpre, k170_numpar,                  ";
  $sSqlCreditosCompensados .= "          recibo.k00_receit, recibo.k00_tipo, k157_data, arretipo.k00_descr            ";
  $sSqlCreditosCompensados .= " order by k170_numpre, k170_numpar, k157_data asc";

  $rsCreditosCompensados      = db_query($sSqlCreditosCompensados);
  $iLinhasCreditosCompensados = pg_num_rows($rsCreditosCompensados);

  if (isset($oGet->devolucao)) {
    $iLinhasCreditosCompensados = 0;
  }

  if ( $iLinhasCreditosCompensados > 0 ) {

    for ( $iInd=0; $iInd < $iLinhasCreditosCompensados; $iInd++ ) {

      $oCredito = db_utils::fieldsMemory($rsCreditosCompensados,$iInd);
      $oDados = new stdClass();
      $oDados->k01_codigo      = '';
      $oDados->k125_datalanc   = $oCredito->k157_data;
      $oDados->k125_sequencial = $oCredito->k157_abatimento;
      $oDados->k00_descr       = $oCredito->k00_descr;
      $oDados->k00_tipo        = $oCredito->k00_tipo;
      $oDados->k01_descr       = 'COMPENSAÇÃO';
      $oDados->k170_numpre     = $oCredito->k170_numpre;
      $oDados->k170_numpar     = $oCredito->k170_numpar;
      $oDados->k00_receit      = $oCredito->k00_receit;
      $oDados->k00_valor       = $oCredito->k157_valor;
      $oDados->sTipo           = 'compensação';
      $oDados->k157_observacao = $oCredito->k157_observacao;

      $aDadosSaida[] = $oDados;
    }

  }

  $sSqlCreditosDevolucao  = "select distinct                                                                                          ";
  $sSqlCreditosDevolucao .= "       k157_abatimento, arreckey.k00_numpre, arreckey.k00_numpar, recibo.k00_receit,                     ";
  $sSqlCreditosDevolucao .= "       arretipo.k00_tipo, k00_descr, k157_valor, k157_usuario, k157_data, k157_hora, k157_observacao     ";
  $sSqlCreditosDevolucao .= "  from abatimentoutilizacao                                                                              ";
  $sSqlCreditosDevolucao .= "       left  join abatimentoutilizacaodestino on k170_utilizacao = k157_sequencial                       ";
  $sSqlCreditosDevolucao .= "       inner join abatimento                  on k125_sequencial = k157_abatimento                       ";
  $sSqlCreditosDevolucao .= "       inner join abatimentorecibo            on k125_sequencial = k127_abatimento                       ";
  $sSqlCreditosDevolucao .= "       inner join recibo                      on recibo.k00_numpre = k127_numprerecibo                   ";
  $sSqlCreditosDevolucao .= "       inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial  ";
  $sSqlCreditosDevolucao .= "       inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey                 ";
  $sSqlCreditosDevolucao .= "       inner join arretipo                    on arretipo.k00_tipo = arreckey.k00_tipo                   ";
  $sSqlCreditosDevolucao .= "       {$sInnerDevolucao}                                                                                ";
  $sSqlCreditosDevolucao .= " where abatimento.k125_instit = {$iInstit} ".$sWhereDevolucao;
  $sSqlCreditosDevolucao .= "   and k170_utilizacao is null                                                                           ";

  $rsCreditosDevolucao      = db_query($sSqlCreditosDevolucao);
  $iLinhasCreditosDevolucao = pg_num_rows($rsCreditosDevolucao);

  if (isset($oGet->compensacao)) {
    $iLinhasCreditosDevolucao = 0;
  }

  if ( $iLinhasCreditosDevolucao > 0 ) {

    for ( $iInd=0; $iInd < $iLinhasCreditosDevolucao; $iInd++ ) {

      $oCredito = db_utils::fieldsMemory($rsCreditosDevolucao,$iInd);

      $oDados = new stdClass();
      $oDados->k01_codigo      = '';
      $oDados->k125_datalanc   = $oCredito->k157_data;
      $oDados->k125_sequencial = $oCredito->k157_abatimento;
      $oDados->k00_descr       = $oCredito->k00_descr;
      $oDados->k00_tipo        = $oCredito->k00_tipo;
      $oDados->k01_descr       = 'DEVOLUÇÃO';
      $oDados->k170_numpre     = $oCredito->k00_numpre;
      $oDados->k170_numpar     = $oCredito->k00_numpar;
      $oDados->k00_valor       = $oCredito->k157_valor;
      $oDados->k157_observacao = $oCredito->k157_observacao;
      $oDados->k02_descr       = '';
      $oDados->k00_receit      = $oCredito->k00_receit;
      $oDados->sTipo           = 'devolução';

      $aDadosSaida[] = $oDados;
    }
  }

 if ( count( $aDadosSaida ) > 0 ) {

    ?>
    <table border="1" cellspacing="0" cellpadding="3">
      <tr bgcolor="#FFCC66">
        <th nowrap>MI                  </th>
        <th nowrap>Numpre              </th>
        <th nowrap>Parcela             </th>
        <th nowrap>Tipo de Débito      </th>
        <th nowrap>Tipo de Movimento   </th>
        <th nowrap>Receita             </th>
        <th nowrap>Valor               </th>
        <th nowrap>Data                </th>
        <th nowrap>Observação          </th>
      </tr>
    <?

    $sCor1   = "#EFE029";
    $sCor2   = "#E4F471";
    $sCorRow = $sCor1;
  }
    foreach ($aDadosSaida as $oCredito ) {

      if ($sCorRow == $sCor1) {
        $sCorRow = $sCor2;
      } else {
        $sCorRow = $sCor1;
      }
    ?>
      <tr bgcolor="<?=$sCorRow?>">
        <td align="center" nowrap >
          <?php
            /**
             * Verifica se deve exibir as informações do desconto ou da compensação
             */

            if ( $oCredito->sTipo == 'desconto' ) {
              db_ancora('MI',"js_consultaDesconto({$oCredito->k125_sequencial})",1,'');
            } else {
              db_ancora('MI',"js_consultaOrigemCredito({$oCredito->k125_sequencial})",1,'');
            }

          ?>
        </td>
        <td align="center" nowrap ><?=$oCredito->k170_numpre ?>&nbsp;</td>
        <td align="center" nowrap ><?=$oCredito->k170_numpar ?>&nbsp;</td>
        <td align="center" nowrap ><?=$oCredito->k00_descr ?>&nbsp;</td>
        <td align="center" nowrap ><?=$oCredito->k01_descr ?>&nbsp;</td>
        <td align="center" nowrap ><?= (isset($oCredito->k00_receit) ? $oCredito->k00_receit : ' - ') ?></td>
        <td align="right"  nowrap ><?=db_formatar($oCredito->k00_valor,'f')   ?>&nbsp;</td>
        <td align="center" nowrap ><?=db_formatar($oCredito->k125_datalanc, 'd') ?>&nbsp;</td>
        <td align="left" nowrap ><?= (isset($oCredito->k157_observacao) ? $oCredito->k157_observacao  : '') ?>&nbsp;</td>
      </tr>
    <?
    }

  ?>
</table>
</center>
</body>
</html>
<script type="text/javascript">

  function js_consultaOrigemCredito(iAbatimento) {

    var sUrl = 'func_compensacao.php?iAbatimento='+iAbatimento;
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_compensacao',sUrl,'Origem da Compensação',true);

  }

  function js_consultaDesconto(iAbatimento) {

    var sUrl = 'func_compensacaodesconto.php?iAbatimento='+iAbatimento;
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_desconto',sUrl,'Origem do Desconto',true);
  }


</script>
<?php
/**
 *
 */
function getDescricaoTipoDebito( $iTipoDebito ) {

 static $aTiposDebito;

 if (empty($aTiposDebito[$iTipoDebito])) {

  $oDaoArretipo = new cl_arretipo();
  $sSql         = $oDaoArretipo->sql_query_file($iTipoDebito);
  $rsSql        = db_query($sSql);

  if ( !$rsSql || pg_num_rows($rsSql) == 0 ) {
    $aTiposDebito[$iTipoDebito] = '';
    return $aTiposDebito[$iTipoDebito];
  }
  $aTiposDebito[$iTipoDebito] = db_utils::fieldsMemory($rsSql,0)->k00_descr;
 }
 return $aTiposDebito[$iTipoDebito];
}
