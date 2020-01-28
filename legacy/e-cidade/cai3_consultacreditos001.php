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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

$oGet  = db_utils::postMemory($_GET);
$iInstituicao = db_getsession("DB_instit");

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
<?php


  if ( isset($oGet->numcgm) ) {

    $sInnerCredito = "inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = "and arrenumcgm.k00_numcgm = ".$oGet->numcgm;
  } else if ( isset($oGet->matric) ) {

    $sInnerCredito = "inner join arrematric on arrematric.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = "and arrematric.k00_matric = ".$oGet->matric;
  } else if ( isset($oGet->inscr) ) {

    $sInnerCredito = "inner join arreinscr on arreinscr.k00_numpre = abatimentorecibo.k127_numprerecibo ";
    $sWhereCredito = "and arreinscr.k00_inscr = ".$oGet->inscr;
  } else {

    $sInnerCredito = "";
    $sWhereCredito = "and abatimentorecibo.k127_numprerecibo = ".$oGet->numpre;
  }

  $dDataSistema = date('Y-m-d', db_getsession('DB_datausu'));
  $iAnoUsu  = db_getsession("DB_anousu");
  $sSqlCreditosDisponiveis  = " select k125_sequencial,                                                                                                                           \n";
  $sSqlCreditosDisponiveis .= "        recibo.k00_numpre,                                                                                                                         \n";
  $sSqlCreditosDisponiveis .= "        recibo.k00_receit,                                                                                                                         \n";
  $sSqlCreditosDisponiveis .= "        recibo.k00_hist,                                                                                                                           \n";
  $sSqlCreditosDisponiveis .= "        tabrec.k02_descr,                                                                                                                          \n";
  $sSqlCreditosDisponiveis .= "        histcalc.k01_descr,                                                                                                                        \n";
  $sSqlCreditosDisponiveis .= "        k125_valor,                                                                                                                                \n";
  $sSqlCreditosDisponiveis .= "        k125_valordisponivel,                                                                                                                      \n";
  $sSqlCreditosDisponiveis .= "        case                                                                                                                                       \n";
  $sSqlCreditosDisponiveis .= "          when (k125_datalanc + ((select coalesce(min(case when k155_tempovalidade = '' then null else k155_tempovalidade end::integer), 99999999) \n";
  $sSqlCreditosDisponiveis .= "                                     from abatimentoregracompensacao                                                                               \n";
  $sSqlCreditosDisponiveis .= "                                    inner join regracompensacao on k155_sequencial = k156_regracompensacao                                         \n";
  $sSqlCreditosDisponiveis .= "                                   where k156_abatimento = abatimento.k125_sequencial)::integer||' days')::interval) >= '{$dDataSistema}'          \n";
  $sSqlCreditosDisponiveis .= "           and k125_valordisponivel > 0                                                                                                            \n";
  $sSqlCreditosDisponiveis .= "          then 'ATIVO'::varchar                                                                                                                    \n";
  $sSqlCreditosDisponiveis .= "                                                                                                                                                   \n";
  $sSqlCreditosDisponiveis .= "          else 'INATIVO'::varchar                                                                                                                  \n";
  $sSqlCreditosDisponiveis .= "        end as status,                                                                                                                             \n";
  $sSqlCreditosDisponiveis .= "        coalesce(
                                          (select sum(k157_valor) from abatimentoutilizacao where k157_abatimento = abatimento.k125_sequencial), 0
                                       ) as valor_utilizado,                                                                                                                      \n";
  $sSqlCreditosDisponiveis .= "        coalesce(
                                          (select k167_data from abatimentocorrecao where k167_abatimento = k125_sequencial order by k167_data desc limit 1), k125_datalanc
                                       ) as data_correcao,                                                                                                                        \n";
  $sSqlCreditosDisponiveis .= "       coalesce(
                                        (select sum(k167_valorcorrigido - k167_valorantigo) from abatimentocorrecao where k167_abatimento = abatimento.k125_sequencial), 0
                                      ) as valor_corrigido                                                                                                                        \n";
  $sSqlCreditosDisponiveis .= "   from abatimentorecibo                                                                                                                           \n";
  $sSqlCreditosDisponiveis .= "        inner join abatimento              on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento                                        \n";
  $sSqlCreditosDisponiveis .= "        inner join recibo                  on recibo.k00_numpre          = abatimentorecibo.k127_numprerecibo                                      \n";
  $sSqlCreditosDisponiveis .= "        inner join arreinstit              on arreinstit.k00_numpre      = recibo.k00_numpre                                                       \n";
  $sSqlCreditosDisponiveis .= "        inner join arretipo                on arretipo.k00_tipo          = recibo.k00_tipo                                                         \n";
  $sSqlCreditosDisponiveis .= "        inner join tabrec                  on tabrec.k02_codigo          = recibo.k00_receit                                                       \n";
  $sSqlCreditosDisponiveis .= "        inner join histcalc                on histcalc.k01_codigo        = recibo.k00_hist                                                         \n";
  $sSqlCreditosDisponiveis .= "        left  join abatimentotransferencia on k158_abatimentoorigem      = k125_sequencial                                                         \n";
  $sSqlCreditosDisponiveis .= "        left  join abatimentoutilizacao    on k157_sequencial            = k158_abatimentoutilizacao                                               \n";
  $sSqlCreditosDisponiveis .= "        {$sInnerCredito}                                                                                                                           \n";
  $sSqlCreditosDisponiveis .= "  where abatimento.k125_tipoabatimento = 3                                                                                                         \n";
  $sSqlCreditosDisponiveis .= "    and arreinstit.k00_instit = {$iInstituicao}                                                                                                    \n";
  $sSqlCreditosDisponiveis .= "        {$sWhereCredito}                                                                                                                           \n";
  $sSqlCreditosDisponiveis .= "  group by k125_sequencial, recibo.k00_numpre, recibo.k00_receit, recibo.k00_hist, tabrec.k02_descr, histcalc.k01_descr,                           \n";
  $sSqlCreditosDisponiveis .= "           k125_valor, k125_valordisponivel, k125_datalanc                                                                                         \n";
  $sSqlCreditosDisponiveis .= "  order by k125_sequencial desc                                                                                                                    \n";

  $sSqlCreditosDisponiveis  = " select *, fc_corre( k00_receit, data_correcao, k125_valordisponivel, current_date, {$iAnoUsu}, data_correcao ) as valor_disponivel
                                from ({$sSqlCreditosDisponiveis}) as creditos";

  $rsCreditosDisponiveis    = db_query($sSqlCreditosDisponiveis);
  $iLinhasCreditos          = pg_num_rows($rsCreditosDisponiveis);

  if ( $iLinhasCreditos > 0 ) {

    ?>
    <table border="1" cellspacing="0" cellpadding="3">
      <tr bgcolor="#FFCC66">
        <th nowrap>MI                  </th>
        <th nowrap>Status              </th>
        <th nowrap>Código              </th>
        <th nowrap>Numpre              </th>
        <th nowrap>Receita             </th>
        <th nowrap>Descrição Receita   </th>
        <th nowrap>Histórico           </th>
        <th nowrap>Descrição Histórico </th>
        <th nowrap>Valor Original      </th>
        <th nowrap>Valor Corrigido     </th>
        <th nowrap>Valor Utilizado     </th>
        <th nowrap>Valor Disponível    </th>
      </tr>
    <?php

    $sCor1   = "#EFE029";
    $sCor2   = "#E4F471";
    $sCorRow = $sCor1;

    for ( $iInd=0; $iInd < $iLinhasCreditos; $iInd++ ) {

      $oCredito = db_utils::fieldsMemory($rsCreditosDisponiveis,$iInd);

      if ($sCorRow == $sCor1) {
        $sCorRow = $sCor2;
      } else {
        $sCorRow = $sCor1;
      }

    ?>

      <tr bgcolor="<?php echo $sCorRow; ?>">
        <td align="center" nowrap >
          <?php
             db_ancora('MI',"js_consultaOrigemCredito({$oCredito->k125_sequencial})",1,'');
          ?>
        </td>
        <td align="center" nowrap ><?php echo $oCredito->status ?></td>
        <td align="center" nowrap ><?php echo $oCredito->k125_sequencial ?></td>
        <td align="center" nowrap ><?php echo $oCredito->k00_numpre; ?></td>
        <td align="center" nowrap ><?php echo $oCredito->k00_receit; ?></td>
        <td align="center" nowrap ><?php echo $oCredito->k02_descr;  ?></td>
        <td align="center" nowrap ><?php echo $oCredito->k00_hist;   ?></td>
        <td align="center" nowrap ><?php echo $oCredito->k01_descr;  ?></td>
        <td align="right"  nowrap ><?php echo db_formatar($oCredito->k125_valor,'f'); // valor credito?></td>
        <?php
          $nValorCorrigido = $oCredito->k125_valor + $oCredito->valor_corrigido;
          if (!$oCredito->valor_corrigido) {
            $nValorCorrigido = $oCredito->valor_disponivel;
          }
        ?>
        <td align="right"  nowrap ><?php echo db_formatar($nValorCorrigido,'f'); ?></td>
        <td align="right"  nowrap ><?php echo db_formatar($oCredito->valor_utilizado,'f')  ?></td>
        <td align="right"  nowrap ><?php echo db_formatar($oCredito->valor_disponivel,'f'); // valor utilizado?></td>

      </tr>
    <?php
    }
  }

  ?>
</table>
</center>
</body>
</html>
<script>
  function js_consultaOrigemCredito(iAbatimento) {

    var sUrl = 'func_origemabatimento.php?iAbatimento='+iAbatimento;
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_abatimento',sUrl,'Origem Crédito',true);

  }
</script>
