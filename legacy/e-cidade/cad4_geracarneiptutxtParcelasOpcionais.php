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

/**
 * se a opção gera vencimento parcela for sim
 * percorremos as unicas e pra cada uma 10 vezez
 */
$lDebug = false;
if ( $lDebug ) {
  echo "<PRE>";
}
if ( $lGeraVencParcelas == true ) {

  $sSqlValorImpressao = "select k00_txban                                      ";
  $sSqlValorImpressao .= "from cfiptu                                           ";
  $sSqlValorImpressao .= "inner join cadvencdesc on j18_vencim = q92_codigo     ";
  $sSqlValorImpressao .= "inner join arretipo    on k00_tipo   = q92_tipo       ";
  $sSqlValorImpressao .= "where j18_anousu = {$anousu}                          ";
  $rsValorImpressao = db_query( $sSqlValorImpressao );
  $nValorImpressao = db_utils::fieldsMemory( $rsValorImpressao, 0 )->k00_txban;

  $aUnicasComparativo = array();
  if ( count( @$aUnicas ) > 0 ) {

    foreach ( $aUnicas as $iIndiceUnicas => $oValorUnicas ) {

      $aDadosUnicaComparativo = explode( "=", $oValorUnicas );
      $oUnicasComparativo = new stdClass();
      $oUnicasComparativo->vencimento = implode( "/", array_reverse( explode( "-", $aDadosUnicaComparativo[0] ) ) );
      $oUnicasComparativo->lancamento = implode( "/", array_reverse( explode( "-", $aDadosUnicaComparativo[1] ) ) );
      $oUnicasComparativo->percentual = $aDadosUnicaComparativo[2];
      $iIndiceComparativo = explode( "/", $oUnicasComparativo->vencimento );
      $aUnicasComparativo[(int) $iIndiceComparativo[1]] = $oUnicasComparativo;
    }
    ///  totais de UNICAS

    foreach ( $aUnicas as $iUnicas => $oUnicas ) {

      $iIndicadorOpcao = 1;
      $aDadosUnica = explode( "=", $oUnicas );
      /*
       * Montamos um objeto com dados das unicas
       * vencimento
       * lancamento
       * percentual
       */
      $oDadosUnica = new stdClass();
      $oDadosUnica->vencimento = implode( "/", array_reverse( explode( "-", $aDadosUnica[0] ) ) );
      $oDadosUnica->lancamento = implode( "/", array_reverse( explode( "-", $aDadosUnica[1] ) ) );
      $oDadosUnica->percentual = $aDadosUnica[2];

      /*
       * Definimos o mes do vencimento,
       * para iniciar no for das unicas, para que na unica do mes 3
       * nao apareca a opcao do mes 2 e do 1
       */

      $iMesVencimento = explode( "/", $oDadosUnica->vencimento );
      $aVencimento = $iMesVencimento;
      $iProximoDiaUnica = $iMesVencimento[0];
      $iMesVencimento = $iMesVencimento[1];
      $iOpcaoUnicas = $iUnicas + 1;

      $iProximoMesUnica = (int) $iMesVencimento;

      $nPercentualUnica = 0;
      //       print_r($aUnicasComparativo);

      if ( isset( $aUnicasComparativo[$iUnicas + 1] ) ) {

        $oDadosComparativo = $aUnicasComparativo[$iUnicas + 1];
        $nPercentualUnica = $oDadosComparativo->percentual / 100;
      }
      for ( $iMes = $iMesVencimento; $iMes <= 12; $iMes++ ) {

        $iProximoAnoUnica = $aVencimento[2];
        if ( (int) $iProximoMesUnica > 12 ) {
          $iProximoMesUnica = 1;
          $iProximoAnoUnica = $iProximoAnoUnica + 1;
        }

        $iDiaVencUnica = getUltimoDiaMes( $iProximoMesUnica, $iProximoAnoUnica, "friday" );

        $sDataVencimentoUnica = "{$iDiaVencUnica}/" . str_pad( $iProximoMesUnica, 2, "0", STR_PAD_LEFT )
            . "/{$iProximoAnoUnica}";

        if ( isset( $aUnicasComparativo[(int) $iMes] ) ) {
          $oTeste = $aUnicasComparativo[(int) $iMes];
          $sDataVencimentoUnica = $oTeste->vencimento;
        }

        $dtVencimentoUnicaCorrecao = implode( "-", array_reverse( explode( "/", $sDataVencimentoUnica ) ) );

        /*
         * aplicamos debitos numpre para o valor corrigido da unica
         */
        $sSqlCorrecao = "select k00_numpre         as numpre,                                                \n";
        $sSqlCorrecao .= "       sum(vlr_historico) as historico,                                             \n";
        $sSqlCorrecao .= "       sum(vlr_corrigido) as corrigido,                                             \n";
        $sSqlCorrecao .= "       sum(vlr_juros)     as juros,                                                 \n";
        $sSqlCorrecao .= "       sum(vlr_multa)     as multa,                                                 \n";
        $sSqlCorrecao .= "       sum(vlr_desconto)  as desconto,                                              \n";
        $sSqlCorrecao .= "       sum(vlr_total)     as total                                                  \n";
        $sSqlCorrecao .= "  from (select distinct                                                             \n";
        $sSqlCorrecao .= "               k00_numpre,                                                          \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 2 , 13)::float8  as vlr_historico,               \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 15, 13)::float8  as vlr_corrigido,               \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 28, 13)::float8  as vlr_juros,                   \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 41, 13)::float8  as vlr_multa,                   \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 54, 13)::float8  as vlr_desconto,                \n";
        $sSqlCorrecao .= "               (substr(fc_calcula, 15, 13)::float8+                                 \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 28, 13)::float8+                                 \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 41, 13)::float8-                                 \n";
        $sSqlCorrecao .= "               substr( fc_calcula, 54, 13)::float8) as vlr_total                    \n";
        $sSqlCorrecao .= "          from (select k00_numpre,                                                  \n";
        $sSqlCorrecao .= "                       fc_calcula(k00_numpre,                                       \n";
        $sSqlCorrecao .= "                                  0,                                                \n";
        $sSqlCorrecao .= "                                  0,                                                \n";
        $sSqlCorrecao .= "                                  '{$dtVencimentoUnicaCorrecao}',                   \n";
        $sSqlCorrecao .= "                                  '{$dtVencimentoUnicaCorrecao}',                   \n";
        $sSqlCorrecao .= "                                  " . db_getsession( "DB_anousu" ) . "                    \n";
        $sSqlCorrecao .= "                       )                                                            \n";
        $sSqlCorrecao .= " from ( select distinct k00_numpre from arrecad where k00_numpre=$j20_numpre ) as x \n";
        //        $sSqlCorrecao .= "                  from arrecad                                                      \n";
        //        $sSqlCorrecao .= "                 where k00_numpre = $j20_numpre                                     \n";
        $sSqlCorrecao .= "               ) as arrecad                                                         \n";
        $sSqlCorrecao .= "      ) as total_unica                                                              \n";
        $sSqlCorrecao .= "group by k00_numpre                                                                 \n";
        $rsDadosUnica = db_query( $sSqlCorrecao );
        $oValUnica = db_utils::fieldsMemory( $rsDadosUnica, 0 );
        $nTotalUnica = $oValUnica->total + $nValorImpressao;

        if ( isset( $aUnicasComparativo[(int) $iMes] ) ) {
          $oUnica = $aUnicasComparativo[(int) $iMes];
          $sDataVencimentoUnica = $oUnica->vencimento;
        }

        if ( $gerar == "layout" ) {
          fputs( $clabre_arquivo->arquivo,
              db_contador( "VCTO_OPCAO_{$iIndicadorOpcao}_QUOTA_{$iOpcaoUnicas}",
                  "OPCAO DE VENC. {$iIndicadorOpcao} DA QUOTA ÚNICA {$iOpcaoUnicas}", $contador, 10 ) );
          fputs( $clabre_arquivo->arquivo,
              db_contador( "VALOR_{$iIndicadorOpcao}_QUOTA_{$iOpcaoUnicas}",
                  "Valor Opçao {$iIndicadorOpcao} da Quota ÚNICA {$iOpcaoUnicas}", $contador, 15 ) );
          $iIndicadorOpcao++ ;
        } else { // DADOS DO LAYOUT

          if ( $lDebug ) {

            echo "MATRICULA:" . $j23_matric . "  \t";
            echo "NUMPRE:" . $j20_numpre . "  \t";
            echo "COTA:" . $iOpcaoUnicas . "  \t";
            echo "OPÇÃO:" . $iIndicadorOpcao . "  \t";
            echo "VENCIMENTO:" . $sDataVencimentoUnica . " outra -> " . $dtVencimentoUnicaCorrecao . "  \t";
            echo "VALOR_ORIGINAL:" . trim( @db_formatar( $nTotalUnica, "f" ) ) . "  \t";
            echo "VALOR_FC_CALCULA:" . trim( @db_formatar( $oValUnica->total, "f" ) ) . "  \t";
            echo "JUROS:" . $oValUnica->juros . "  \t";
            echo "MULTA:" . $oValUnica->multa . "  \t";
            echo "DESCONTO:" . $oValUnica->desconto . "  \n";
            $iIndicadorOpcao++ ;
          }
          fputs( $clabre_arquivo->arquivo, $sDataVencimentoUnica );
          fputs( $clabre_arquivo->arquivo, str_pad( trim( db_formatar( $nTotalUnica, "f" ) ), 15, ' ', STR_PAD_LEFT ) );
        }
        $iProximoMesUnica++ ;
      }
      if ( $lDebug ) {
        echo "\n";
      }
    }

  }

  /// FOEREACH PARA TOTAL PARCELAS
  $sDataVencParcela    = $aParcelasArrecad[0]->k00_dtvenc;
  $iTotalParcelas      = $aParcelasArrecad[0]->k00_numtot;
  $iMesPrimeiraParcela = explode( "-", $sDataVencParcela );
  $iMesPrimeiraParcela = $iMesPrimeiraParcela[1];
  $iContadorParcela = 0;
  for ( $iMesParcela = 1; $iMesParcela <= $iTotalParcelas; $iMesParcela++ ) {

    $iIndice            = $iMesParcela - 1;
    $oParcela           = $aParcelasArrecad[$iIndice];//dados das parcelas tipo, numpre, valor etc.
    $iProximoMesParcela = explode( "-", $oParcela->k00_dtvenc ); // MES VENCIMENTO DA PARCELA
    $iProximoDiaParcela = $iProximoMesParcela[2];
    $iProximoMesParcela = $iProximoMesParcela[1];

    for ( $iMesParcelaOpcao = $iMesPrimeiraParcela; $iMesParcelaOpcao <= 12 - $iIndice; $iMesParcelaOpcao++ ) {

      $iContadorParcela++ ;
      $sSqlVencimento = "select extract (year from k00_dtvenc) as anoVencimento from arrecad where k00_numpre = {$j20_numpre} and k00_numpar = {$iMesParcela}";
      $rsAnoVenc = db_query( $sSqlVencimento );
      $aAnoVenc = db_utils::fieldsMemory( $rsAnoVenc, 0 );
      $iAnoVencimento = $aAnoVenc->anovencimento;
      $iProximoAnoParcela = $iAnoVencimento;

      if ( $iProximoMesParcela > 12 ) {
        $iProximoMesParcela = 1;
        $iProximoAnoParcela = $iProximoAnoParcela + 1;
      }

      $iDiaVenc = getUltimoDiaMes( $iProximoMesParcela, $iProximoAnoParcela, "friday" );
      if ( $iMesParcelaOpcao == $iMesPrimeiraParcela ) {
        $iDiaVenc = $iProximoDiaParcela;
      }

      $sDataVencimentoParcela = "{$iDiaVenc}/" . str_pad( $iProximoMesParcela, 2, "0", STR_PAD_LEFT )
          . "/{$iProximoAnoParcela}";
      $dtVencimentoParcCorrecao = implode( "-", array_reverse( explode( "/", $sDataVencimentoParcela ) ) );
      $rsDadosParcela = debitos_numpre( $j20_numpre, 0, 0, strtotime( $dtVencimentoParcCorrecao ), $anousu,
          $iMesParcela, 'k00_numpre, k00_numpar' );

      $aValoresParcela = db_utils::getCollectionByRecord( $rsDadosParcela );

      $nMulta = 0;
      $nJuros = 0;
      foreach ( $aValoresParcela as $indTotalParcela => $oValorTotalParcela ) {

        $nTotalParcela = $oValorTotalParcela->total + $nValorImpressao;
        $nMulta = $oValorTotalParcela->vlrmulta;
        $nJuros = $oValorTotalParcela->vlrjuros;
      }

      if ( $gerar == "layout" ) {

        fputs( $clabre_arquivo->arquivo,
            db_contador( "VCTO_OPCAO_{$iContadorParcela}_PARCELA_{$iMesParcela}",
                "OPCAO DE VENC. {$iMesParcelaOpcao} DA COTA ÚNICA {$iMesParcela}", $contador, 10 ) );
        fputs( $clabre_arquivo->arquivo,
            db_contador( "VALOR_{$iContadorParcela}_PARCELA_{$iMesParcela}",
                "VALOR OPCAO {$iMesParcelaOpcao} DA PARCELA  {$iMesParcela}", $contador, 15 ) );
      } else { // DADOS DAS PARCELAS

        fputs( $clabre_arquivo->arquivo, $sDataVencimentoParcela );
        fputs( $clabre_arquivo->arquivo, str_pad( trim( db_formatar( $nTotalParcela, "f" ) ), 15, ' ', STR_PAD_LEFT ) );
      }
      $iProximoMesParcela++ ;
    }
  }
}

?>