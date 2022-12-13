<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( !isset($parcel) || $parcel == '' ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento nÃ£o encontrado!');
  exit;
}

$iParcelPrinc = $parcel;

if ( $iParcelPrinc == 0 ) {
  die("<br><br><br> sem parcelamento especificado! <br><br><br>");
}

if (!isset($iDebuga)) {
  $iDebuga = 0;	
}

echo $iDebuga."<br>";
db_inicio_transacao();

// Seta Nome do Script
$sNomeScript = basename(__FILE__);

$parcels = $iParcelPrinc;

//echo "<br><br><br>... 5 segundos... parcelamentos: [$parcels]<br>";

//sleep(5);

$bErro = false;
$sMsgErro = "";

// matricula: 20083

# falta_ok testar anulacao de parcelamento (erro 9)
# falta_ok acertar para anular os parcelamentos com mais de duas em aberto e nao as matriculas inteiras... coletar caso com ivana...
# falta_ok gravar termoanu aqui e alterar v07_situacao da termo

// NAO PODE ANULAR: 1016, 1020, 20085, 10042

$sSql = "select setval('divida.termoanu_v09_sequencial_seq', coalesce( ( select max(v09_sequencial) from divida.termoanu ),0) + 1, false); ";
if (!db_query($sSql)) {
  $bErro = true;
  $sMsgErro = "Erro alterando sequencial da termoanu";	
}


$sSql  = " ";
$sSql .= " select distinct     "; 
$sSql .= "        k00_matric,  ";
$sSql .= "        k00_inscr    ";
$sSql .= "   from divida.termo ";
$sSql .= "        left join caixa.arrematric on arrematric.k00_numpre = v07_numpre ";
$sSql .= "        left join caixa.arreinscr  on arreinscr.k00_numpre  = v07_numpre ";
$sSql .= "  where v07_parcel in ( $parcels ) ";
$sSql .= "    and ( k00_matric is not null or k00_inscr is not null) ";
$rsVinculoMatriculaInscricao = db_query($sSql);
if ( pg_numrows($rsVinculoMatriculaInscricao) > 1 ) {
  die("Erro! Parcelamento [$parcels] vinculado a mais de uma matricula ou inscrição!");
} elseif ( pg_numrows($rsVinculoMatriculaInscricao) == 0 ) {
  die("Erro! Parcelamento [$parcels] sem vinculação com matricula ou inscrição!");
}
$oDados = db_utils::fieldsmemory($rsVinculoMatriculaInscricao, 0);
$iMatric = $oDados->k00_matric;
$iInscr  = $oDados->k00_inscr;

$sSqlDados  = "";
$sSqlDados .= " select distinct    "; 
$sSqlDados .= "        v07_numpre, ";
$sSqlDados .= "        v07_parcel, ";
$sSqlDados .= "        k00_matric, ";
$sSqlDados .= "        k00_inscr,   ";
$sSqlDados .= "        ( select distinct parcel           from divida.termodiv    where termodiv.parcel         = termo.v07_parcel ) as quant_termodiv, ";
$sSqlDados .= "        ( select distinct parcel           from divida.termoini    where termoini.parcel         = termo.v07_parcel ) as quant_termoini, ";
$sSqlDados .= "        ( select distinct v08_parcelorigem from divida.termoreparc where termoreparc.v08_parcel  = termo.v07_parcel ) as quant_termoreparc ";
$sSqlDados .= "   from divida.termo ";
$sSqlDados .= "        inner join caixa.arrecad    on termo.v07_numpre = arrecad.k00_numpre      ";
$sSqlDados .= "         left join caixa.arrematric on arrecad.k00_numpre = arrematric.k00_numpre ";
$sSqlDados .= "         left join caixa.arreinscr  on arrecad.k00_numpre = arreinscr.k00_numpre  ";

$sSql  = " select distinct       "; 
$sSql .= "        k00_matric,    ";
$sSql .= "        k00_inscr      ";
$sSql .= "   from ( $sSqlDados ) as x ";
if ($iMatric != "") {
  $sWhere = "k00_matric in ($iMatric) ";
} else if ($iInscr != "") {
  $sWhere = "k00_inscr in ($iInscr) ";
}
$sSql .= "  where {$sWhere} ";
$sSql .= " order by k00_matric,k00_inscr;";
$rsVinculoMatriculaInscricao = db_query($sSql);
if(!$rsVinculoMatriculaInscricao) {
  $bErro  = true;
  $sMsgErro = "Buscando dados da Origem 1";	
}
$iLinhasMatric = pg_numrows($rsVinculoMatriculaInscricao);

echo "<br><br><br> iLinhasMatric: $iLinhasMatric <br><br><br>";

for ( $iMatric = 0; $iMatric < $iLinhasMatric; $iMatric++ ) {
  $oMatric = db_utils::fieldsmemory($rsVinculoMatriculaInscricao, $iMatric);

  echo("matric: $oMatric->k00_matric - inscr: $oMatric->k00_inscr - $iMatric / $iLinhasMatric <br>");

  $sSql  = "";
  $sSql .= " select v07_parcel, ";
  $sSql .= "        v07_numpre, ";
  $sSql .= "        coalesce( ( select distinct parcel           from divida.termodiv    where termodiv.parcel         = termo.v07_parcel ),0) as quant_termodiv, ";
  $sSql .= "        coalesce( ( select distinct parcel           from divida.termoini    where termoini.parcel         = termo.v07_parcel ),0) as quant_termoini, ";
  $sSql .= "        coalesce( ( select distinct v08_parcelorigem from divida.termoreparc where termoreparc.v08_parcel  = termo.v07_parcel ),0) as quant_termoreparc ";
  $sSql .= "   from divida.termo ";
  $sSql .= "        left join caixa.arrematric on arrematric.k00_numpre = termo.v07_numpre ";
  $sSql .= "        left join caixa.arreinscr  on arreinscr.k00_numpre = termo.v07_numpre ";  
  $sSql .= "  where {$sWhere}";
  //$sSql .= " and v07_parcel in ( $parcels ) ";

  $sSql  = " select v07_parcel, 
                    v07_numpre, 
                    case when quant_termodiv = 0 and quant_termoini = 0 then 0 else case when quant_termodiv > 0 then 1 else 2 end end as tipo, 
                    quant_termodiv, 
                    quant_termoini, 
                    quant_termoreparc, 
                    coalesce( ( select arrecad.k00_numpre from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre limit 1 ) ,0) as k00_numpre 
               from ( $sSql ) as x ";
  //$sSql .= " where ( ( select count(distinct k00_numpar) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre and arrecad.k00_dtvenc > current_date ) = 0 ) and ( ( select count(distinct k00_numpar) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) > 2 and ( ( ( select current_date - case when ( select max(k00_dtvenc) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) is null then current_date else ( select max(k00_dtvenc) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) end ) / 30 ) >= 6 ) ) ";
  $sSql .= " order by v07_numpre";
  //die("<br><br><br> $sSql <br><br><br>");
  $rsDivida = db_query($sSql);
  if (!$rsDivida) {
  	$bErro  = true;
  	$sMsgErro = "Buscando dados da Origem 2";
  }
  $iLinhasDivida = pg_numrows($rsDivida);

  $aParcelas   = array();
  $aExercicios = array();
  $aValores    = array();
  for ( $iCont = 0; $iCont < $iLinhasDivida; $iCont++ ) {
    $oTermo = db_utils::fieldsmemory($rsDivida, $iCont);

    echo($iCont+1 . "/$iLinhasDivida - parcel: $oTermo->v07_parcel <br>");

    echo "<br>";

    $iParcelOriginal  = $oTermo->v07_parcel;
    $iSequencial      = 1;
    $iParcel          = $oTermo->v07_parcel;

    if ( $iDebuga == 1 ) {
      echo "   1=pesquisando pelo parcel [$iParcel]<br>";
    }

    $sPagTermo  = "select count(distinct k00_numpar) as quant_parcelas, k00_numtot from caixa.arrepaga where k00_numpre = $oTermo->v07_numpre group by k00_numtot";
    $rsPagTermo = db_query($sPagTermo);
    if (!$rsPagTermo) {
      $bErro  = true;
      $sMsgErro = "Buscando dados da Origem 3";
    }
    
    if ( pg_numrows($rsPagTermo) > 0 ) {
//      echo "<br> achou pag(1)...<br>";
    }

    if ( $oTermo->quant_termodiv > 0 ) {

      if ( $iDebuga == 1 ) {
        echo "      termodiv(1)...<br>";
      }
      $sOrigem  = "";
      $sOrigem .= " select v01_coddiv, v01_exerc, v01_valor as v01_vlrhis ";
      $sOrigem .= " from divida.termodiv ";
      $sOrigem .= " inner join divida.divida on divida.v01_coddiv = termodiv.coddiv ";
      $sOrigem .= " where parcel = $iParcel ";
      $rsOrigem = db_query($sOrigem);
      if (!$rsOrigem) {
       $bErro  = true;
       $sMsgErro = "Buscando dados da Origem 4";
      }
      
      if ( pg_numrows($rsOrigem) > 0 ) {
        for ( $iAnos=0; $iAnos < pg_numrows($rsOrigem); $iAnos++) {
          $oOrigem = db_utils::fieldsmemory( $rsOrigem, $iAnos );
          if ( !isset($aExercicios[$oOrigem->v01_exerc][0]) or true ) {
            $aExercicios[$oOrigem->v01_exerc][0] = $oOrigem->v01_coddiv;
            $aExercicios[$oOrigem->v01_exerc][1] = $oOrigem->v01_vlrhis;

            if ( pg_numrows($rsPagTermo) > 0 ) {
              $oPagTermo  = db_utils::fieldsmemory( $rsPagTermo, 0 );
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = $oPagTermo->quant_parcelas;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = $oPagTermo->k00_numtot;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $iParcel;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
              $iSequencial++;
              if ( $iDebuga == 1 ) {
                echo "         1=incluindo parcelas pagas [$oPagTermo->quant_parcelas] de [$oPagTermo->k00_numtot] - ano [$oOrigem->v01_exerc] <br>";
              }
            } else {
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = 0;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = 0;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $iParcel;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
              $iSequencial++;
              if ( $iDebuga == 1 ) {
                echo "         2=sem parcelas pagas - ano [$oOrigem->v01_exerc] <br>";
              }
            }

          }
        }
      } else {
        die("<br><br><br> $sOrigem <br><br><br>");
      }

    }

    if ( $oTermo->quant_termoini > 0 ) {

      if ( $iDebuga == 1 ) {
        echo "      termoini(1)...<br>";
      }

      $sOrigem  = "";
      $sOrigem .= " select v01_coddiv, v01_exerc, v01_valor as v01_vlrhis ";
      $sOrigem .= " from divida.termoini ";
      $sOrigem .= " inner join juridico.inicialcert on inicial = v51_inicial ";
      $sOrigem .= " inner join divida.certdiv on v14_certid = v51_certidao ";
      $sOrigem .= " inner join divida.divida on divida.v01_coddiv = certdiv.v14_coddiv ";
      $sOrigem .= " where parcel = $iParcel ";
//      echo("<br><br><br> $sOrigem <br><br><br>");
      $rsOrigem = db_query($sOrigem);
      if (!$rsOrigem) {
      	$bErro  = true;
      	$sMsgErro = "Buscando dados da Origem 5";
      }      
      if ( pg_numrows($rsOrigem) > 0 ) {
        for ( $iAnos=0; $iAnos < pg_numrows($rsOrigem); $iAnos++) {
          $oOrigem = db_utils::fieldsmemory( $rsOrigem, $iAnos );
          if ( !isset($aExercicios[$oOrigem->v01_exerc][0]) or true ) {
            $aExercicios[$oOrigem->v01_exerc][0] = $oOrigem->v01_coddiv;
            $aExercicios[$oOrigem->v01_exerc][1] = $oOrigem->v01_vlrhis;

            if ( pg_numrows($rsPagTermo) > 0 ) {
              $oPagTermo  = db_utils::fieldsmemory( $rsPagTermo, 0 );
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = $oPagTermo->quant_parcelas;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = $oPagTermo->k00_numtot;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $iParcel;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
              $iSequencial++;
              if ( $iDebuga == 1 ) {
                echo "         3=incluindo parcelas pagas [$oPagTermo->quant_parcelas] de [$oPagTermo->k00_numtot] - ano [$oOrigem->v01_exerc] <br>";
              }
            } else {
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = 0;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = 0;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $iParcel;
              $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
              $iSequencial++;
              if ( $iDebuga == 1 ) {
                echo "         4=sem parcelas pagas - ano [$oOrigem->v01_exerc] <br>";
              }
            }


          }
        }
      }

    }

    if ( $oTermo->quant_termoreparc > 0 ) {

      if ( $iDebuga == 1 ) {
        echo "      termoreparc...<br>";
      }

      if ( pg_numrows($rsPagTermo) > 0 or true ) {
        if ( $iDebuga == 1 ) {
          echo "         termoreparc - encontrou pagamento...<br>";
        }

        $iParcelBusca = $oTermo->quant_termoreparc;
 
        $lContinua = 1;
        while ( $lContinua == 1 ) {

          $sParcelBusca  = "";
          $sParcelBusca .= " select distinct v07_numpre, v07_parcel, ";
          $sParcelBusca .= " coalesce ( ( select distinct parcel           from divida.termodiv    where termodiv.parcel         = termo.v07_parcel ),0) as quant_termodiv, ";
          $sParcelBusca .= " coalesce ( ( select distinct parcel           from divida.termoini    where termoini.parcel         = termo.v07_parcel ),0) as quant_termoini, ";
          $sParcelBusca .= " coalesce ( ( select distinct v08_parcelorigem from divida.termoreparc where termoreparc.v08_parcel  = termo.v07_parcel ),0) as quant_termoreparc ";
          $sParcelBusca .= " from divida.termo where v07_parcel = $iParcelBusca ";
//          echo("<br><br><br> $sParcelBusca <br><br><br>");
//          sleep(1);
          $rsParcelBusca = db_query($sParcelBusca);
          if (!$rsParcelBusca) {
          	$bErro  = true;
          	$sMsgErro = "Buscando dados da Origem 6";
          }
          $iLinhasParcelBusca = pg_numrows($rsParcelBusca);

          $oParcelBusca = db_utils::fieldsmemory( $rsParcelBusca, 0 );
          $iParcelBusca = $oParcelBusca->v07_parcel;

          for ( $iParcelamentos = 0; $iParcelamentos < pg_numrows($rsParcelBusca); $iParcelamentos++ ) {
            $oParcelBusca = db_utils::fieldsmemory( $rsParcelBusca, $iParcelamentos );

            if ( $oParcelBusca->quant_termodiv > 0 ) {

              if ( $iDebuga == 1 ) {
                echo "      termodiv(2)... - parcel [$iParcelBusca]<br>";
              }
              $sOrigem  = "";
              $sOrigem .= " select v01_coddiv, v01_exerc, v01_valor as v01_vlrhis ";
              $sOrigem .= " from divida.termodiv ";
              $sOrigem .= " inner join divida.divida on divida.v01_coddiv = termodiv.coddiv ";
              $sOrigem .= " where parcel = $iParcelBusca ";
              $rsOrigem = db_query($sOrigem);
              if (!$rsOrigem) {
              	$bErro  = true;
              	$sMsgErro = "Buscando dados da Origem 7";
              }              
//              echo "<br><br><br> $sOrigem <br><br><br>";
              if ( pg_numrows($rsOrigem) > 0 ) {
                for ( $iAnos=0; $iAnos < pg_numrows($rsOrigem); $iAnos++) {
                  $oOrigem = db_utils::fieldsmemory( $rsOrigem, $iAnos );

                  $aExercicios[$oOrigem->v01_exerc][0] = $oOrigem->v01_coddiv;
                  $aExercicios[$oOrigem->v01_exerc][1] = $oOrigem->v01_vlrhis;

                  if ( pg_numrows($rsPagTermo) > 0 ) {
                    $oPagTermo  = db_utils::fieldsmemory( $rsPagTermo, 0 );
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = $oPagTermo->quant_parcelas;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = $oPagTermo->k00_numtot;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $iParcelBusca;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
                    if ( $iDebuga == 1 ) {
                      echo "         3=incluindo parcelas pagas [$oPagTermo->quant_parcelas] de [$oPagTermo->k00_numtot] - ano [$oOrigem->v01_exerc] <br>";
                    }
                  } else {
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = 0;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = 0;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $iParcelBusca;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
                    if ( $iDebuga == 1 ) {
                      echo "         3.1=sem parcelas pagas - ano [$oOrigem->v01_exerc] <br>";
                    }
                  }
                  $iSequencial++;
                  
                }

              } else {
                die("<br><br><br> $sOrigem <br><br><br>");
              }

              $lContinua = 0;

            } elseif ( $oParcelBusca->quant_termoini > 0 ) {

              if ( $iDebuga == 1 ) {
                echo "      termoini(2)...<br>";
              }
              $sOrigem  = "";
              $sOrigem .= " select v01_coddiv, v01_exerc, v01_valor as v01_vlrhis ";
              $sOrigem .= " from divida.termoini ";
              $sOrigem .= " inner join juridico.inicialcert on inicial = v51_inicial ";
              $sOrigem .= " inner join divida.certdiv on v14_certid = v51_certidao ";
              $sOrigem .= " inner join divida.divida on divida.v01_coddiv = certdiv.v14_coddiv ";
              $sOrigem .= " where parcel = $oParcelBusca->v07_parcel";
              $rsOrigem = db_query($sOrigem);
              if (!$rsOrigem) {
              	$bErro  = true;
              	$sMsgErro = "Buscando dados da Origem 8";
              }
              if ( pg_numrows($rsOrigem) > 0 ) {
                for ( $iAnos=0; $iAnos < pg_numrows($rsOrigem); $iAnos++) {
                  $oOrigem = db_utils::fieldsmemory( $rsOrigem, $iAnos );
                  $aExercicios[$oOrigem->v01_exerc][0] = $oOrigem->v01_coddiv;
                  $aExercicios[$oOrigem->v01_exerc][1] = $oOrigem->v01_vlrhis;

                  if ( pg_numrows($rsPagTermo) > 0 ) {
                    $oPagTermo  = db_utils::fieldsmemory( $rsPagTermo, 0 );
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = $oPagTermo->quant_parcelas;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = $oPagTermo->k00_numtot;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $oParcelBusca->v07_parcel;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
                    $iSequencial++;
                    if ( $iDebuga == 1 ) {
                      echo "         4=incluindo parcelas pagas [$oPagTermo->quant_parcelas] de [$oPagTermo->k00_numtot] - ano [$oOrigem->v01_exerc] <br>";
                    }
                  } else {
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][0] = 0;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][1] = 0;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][2] = $oOrigem->v01_vlrhis;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][3] = $oParcelBusca->v07_parcel;
                    $aParcelas[$iParcelOriginal][$iSequencial][$oOrigem->v01_exerc][$oOrigem->v01_coddiv][4] = $oTermo->k00_numpre;
                    $iSequencial++;
                    if ( $iDebuga == 1 ) {
                      echo "         5=sem parcelas pagas - ano [$oOrigem->v01_exerc] <br>";
                    }
                  }
                }
              }

              $lContinua = 0;

            }

            if ( $oParcelBusca->quant_termoreparc > 0 ) {
//              echo "<br> hhhhhhhhhhhhhhhhhhhhhh <br>";
              $iParcelBusca = $oParcelBusca->quant_termoreparc;
              $lContinua = 1;
            }

          }

//          echo "<br><br><br> kkkkkkkkkkkkkkkkkkkkkk <br><br><br>";

        }

//        echo "<br><br><br> fim do while ... <br><br><br>";

      }

    }

//    sleep(1);

  }

//exit;

  $sSql  = "";
  $sSql .= " select distinct    "; 
  $sSql .= "        v07_parcel, "; 
  $sSql .= "        v07_numpre, ";
  $sSql .= "        coalesce( ( select distinct parcel           from divida.termodiv    where termodiv.parcel         = termo.v07_parcel ),0) as quant_termodiv, ";
  $sSql .= "        coalesce( ( select distinct parcel           from divida.termoini    where termoini.parcel         = termo.v07_parcel ),0) as quant_termoini, ";
  $sSql .= "        coalesce( ( select distinct v08_parcelorigem from divida.termoreparc where termoreparc.v08_parcel  = termo.v07_parcel ),0) as quant_termoreparc ";
  $sSql .= " from divida.termo ";
  $sSql .= "      left  join caixa.arrematric on arrematric.k00_numpre = termo.v07_numpre ";
  $sSql .= "      left  join caixa.arreinscr  on arreinscr.k00_numpre  = termo.v07_numpre ";  
  $sSql .= "      inner join caixa.arrecad    on arrecad.k00_numpre    = termo.v07_numpre ";
  $sSql .= " where {$sWhere}";
  $sSql .= " and v07_parcel in ( $parcels ) ";

  $sSql  = " select v07_parcel, v07_numpre, case when quant_termodiv = 0 and quant_termoini = 0 then 0 else case when quant_termodiv > 0 then 1 else 2 end end as tipo, quant_termodiv, quant_termoini, quant_termoreparc from ( $sSql ) as x ";



  $sSql .= " where 1=1 ";
//  $sSql .= " and ( ( select count(distinct k00_numpar) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre and arrecad.k00_dtvenc > current_date ) = 0 ) ";
//  $sSql .= " and ( ( select count(distinct k00_numpar) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) > 2 and ( ( ( select current_date - case when ( select max(k00_dtvenc) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) is null then current_date else ( select max(k00_dtvenc) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) end ) / 30 ) >= 6 ) ) ";



  $sSql .= " order by v07_parcel";
//  die("<br><br><br> $sSql <br><br><br>"); 
  $rsArrecad = db_query($sSql);
  if (!$rsArrecad) {
  	$bErro  = true;
  	$sMsgErro = "Buscando dados da Origem 9";
  }    
  $iLinhasArrecad = pg_numrows($rsArrecad);

  $aArrecad = array();

  for ( $iArrecad = 0; $iArrecad < $iLinhasArrecad; $iArrecad++ ) {
    $oArrecad = db_utils::fieldsmemory($rsArrecad, $iArrecad);

    if ( $oArrecad->quant_termodiv > 0 ) {
      $aArrecad[] = $oArrecad->v07_parcel;
    }

    if ( $oArrecad->quant_termoini > 0 ) {
      $aArrecad[] = $oArrecad->v07_parcel;
    }

    if ( $oArrecad->quant_termoreparc > 0 ) {

      $aArrecad[] = $oArrecad->v07_parcel;

      $iParcelBusca = $oArrecad->quant_termoreparc;
      $aArrecad[] = $iParcelBusca;

      $lContinua = 1;

      while ( $lContinua == 1 ) {

        $sParcelBusca  = "";
        $sParcelBusca .= " select distinct v07_numpre, v07_parcel, ";
        $sParcelBusca .= " coalesce ( ( select distinct parcel           from divida.termodiv    where termodiv.parcel         = termo.v07_parcel ),0) as quant_termodiv, ";
        $sParcelBusca .= " coalesce ( ( select distinct parcel           from divida.termoini    where termoini.parcel         = termo.v07_parcel ),0) as quant_termoini, ";
        $sParcelBusca .= " coalesce ( ( select distinct v08_parcelorigem from divida.termoreparc where termoreparc.v08_parcel  = termo.v07_parcel ),0) as quant_termoreparc ";
        $sParcelBusca .= " from divida.termo where v07_parcel = $iParcelBusca ";
        $rsParcelBusca = db_query($sParcelBusca);
        if (!$rsParcelBusca) {
          $bErro = true;
          $sMsgErro = "Buscando dados da Origem 10";	
        }
        $iLinhasParcelBusca = pg_numrows($rsParcelBusca);

        $oParcelBusca = db_utils::fieldsmemory( $rsParcelBusca, 0 );
        $iParcelBusca = $oParcelBusca->v07_parcel;

        for ( $iParcelamentos = 0; $iParcelamentos < pg_numrows($rsParcelBusca); $iParcelamentos++ ) {
          $oParcelBusca = db_utils::fieldsmemory( $rsParcelBusca, $iParcelamentos );

          if ( $oParcelBusca->quant_termodiv > 0 ) {
            $lContinua = 0;
          } elseif ( $oParcelBusca->quant_termoini > 0 ) {
            $lContinua = 0;
          }

          if ( $oParcelBusca->quant_termoreparc > 0 ) {
            $iParcelBusca = $oParcelBusca->quant_termoreparc;
            $aArrecad[] = $iParcelBusca;
            $lContinua = 1;
          }

        }

      }

    }

  }

echo "<br><br><br> =============================================== <br><br><br>";

  if ( $iDebuga == 1 ) {
    echo "<br><br><br>";
    echo "<pre>";
    var_dump( $aParcelas );
    echo "<br><br><br>";
    
  }

  $aParcelamentoComAno = array();

  if ( true ) {

//    echo "<br>";
//    echo " xxxxxxxxxxxxxxxxxxxxxxx - " . sizeof($aExercicios);
//    echo "<br>";

    foreach ( $aParcelas as $aNivel1 => $aNivel2 ) {

      if ( $iDebuga == 1 ) {
        echo "<br>   P A R C E L A M E N T O: $aNivel1<br><br>";
      }

      foreach ( $aNivel2 as $aNivel3 => $aNivel4 ) {

        if ( $iDebuga == 1 ) {
          echo "      seq: $aNivel3<br>";
        }

//        $nValor = $aNivelExercicio2[1];
        $nValor = 100;

        foreach ( $aNivel4 as $aNivel5 => $aNivel6 ) {

          if ( $iDebuga == 1 ) {
            echo "      ano: $aNivel5<br>";
          }

          for ( $iAnoParcelamento=0; $iAnoParcelamento < sizeof($aArrecad); $iAnoParcelamento++ ) {
            if ( $aNivel1 == $aArrecad[$iAnoParcelamento] ) {
              $aParcelamentoComAno[$aNivel5] = 1;
            }
          }

          if ( $iDebuga == 1 ) {
            echo "<br>";
          }
          foreach ( $aNivel6 as $aNivel7 => $aNivel8 ) {

            if ( $iDebuga == 1 ) {
              echo "         nivel7: $aNivel7 - nivel8: $aNivel8[4] - valor: " . $aValores[$aNivel7][0] . "<br><br>";
            }

//            if ( $aNivel8[4] > 0 or true ) {
            if ( in_array($aNivel1, $aArrecad) or true ) {

              if ( isset( $aValores[$aNivel7][0] ) ) {
                $nValor = $aValores[$aNivel7][0];
              } else {
                $nValor = 9999999999;
              }

              if ( $iDebuga == 1 ) {
                echo "<br>          nivel8[0]: $aNivel8[0] - nivel8[1]: $aNivel8[1] - nivel8[2]: $aNivel8[2] - nivel8: $aNivel8[3] - nValor: [$nValor] <br>";
              }

              if ( $nValor == 9999999999 ) {
                if ( $iDebuga == 1 ) {
                  echo "<br>          entrou no valor == null <br>";
                }
                $aValores[$aNivel7][0] = $aNivel8[2];
                $aValores[$aNivel7][1] = $aNivel3;
                $aValores[$aNivel7][2] = $aNivel5;
                $nValor = $aNivel8[2];
              }

              if ( $aNivel8[1] > 0 ) {

                if ( $aNivel8[0] == $aNivel8[1] ) {
                  $nValor = 0;
                } else {
                  $nValor = round ( ( $nValor / $aNivel8[1] ) * ( $aNivel8[1] - $aNivel8[0] ) ,2);
                }

              }
//              die("<br><br><br> nValor: $nValor<br><br><br>");
//              exit;

              $aValores[$aNivel7][0] = $nValor;

              if ( $iDebuga == 1 ) {
                echo "         quant_parcelas: " . $aNivel8[0] . " - k00_numtot: " . $aNivel8[1] . " - valor: $nValor<br>";
              }

            }

          }

        }

      }

    }

    if ( $iDebuga == 1 ) {
      echo "<br>2=ano: " . $aNivelExercicio1 . " coddiv: " . $aNivelExercicio2[0] . " - novo valor: " . $nValor . "<br><br>";
    }

  }

//exit;

  $sSql  = "";
  $sSql .= " select distinct    "; 
  $sSql .= "        v07_parcel, "; 
  $sSql .= "        v07_numpre, ";
  $sSql .= "        coalesce( ( select distinct parcel           from divida.termodiv    where termodiv.parcel         = termo.v07_parcel ),0) as quant_termodiv, ";
  $sSql .= "        coalesce( ( select distinct parcel           from divida.termoini    where termoini.parcel         = termo.v07_parcel ),0) as quant_termoini, ";
  $sSql .= "        coalesce( ( select distinct v08_parcelorigem from divida.termoreparc where termoreparc.v08_parcel  = termo.v07_parcel ),0) as quant_termoreparc ";
  $sSql .= "   from divida.termo ";
  $sSql .= "        left  join caixa.arrematric on arrematric.k00_numpre = termo.v07_numpre ";
  $sSql .= "        left  join caixa.arreinscr  on arreinscr.k00_numpre  = termo.v07_numpre ";
  $sSql .= "        inner join caixa.arrecad    on arrecad.k00_numpre    = termo.v07_numpre ";
  $sSql .= "  where {$sWhere}";
  $sSql .= "    and v07_parcel in ( $parcels ) ";

  $sSql  = " select v07_parcel, v07_numpre, case when quant_termodiv = 0 and quant_termoini = 0 then 0 else case when quant_termodiv > 0 then 1 else 2 end end as tipo, quant_termodiv, quant_termoini, quant_termoreparc from ( $sSql ) as x ";
//  $sSql .= " where ( ( select count(distinct k00_numpar) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre and arrecad.k00_dtvenc > current_date ) = 0 ) and ( ( select count(distinct k00_numpar) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) > 2 and ( ( ( select current_date - case when ( select max(k00_dtvenc) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) is null then current_date else ( select max(k00_dtvenc) from caixa.arrecad where arrecad.k00_numpre = x.v07_numpre ) end ) / 30 ) >= 6 ) ) ";
  $sSql .= " order by v07_parcel";

//  die("<br><br><br> $sSql <br><br><br>");

  $rsDivida = db_query($sSql);
  if (!$rsDivida) {
  	$bErro = true;
  	$sMsgErro = "Buscando dados da Origem 11";
  }  
  $iLinhasDivida = pg_numrows($rsDivida);

  if ( $iDebuga == 1) {
    echo "<br><br><br>";
    echo "<pre>";
    var_dump( $aValores );
    echo "<br><br><br>";
    
  }

  if ( $iDebuga == 1 ) {
    echo "<br><br><br>";
    echo "<br><br><br>";
    echo "<br><br><br>";
  }

  $lAnularMesmoParcelamento = 0;
                    
  if ( $iDebuga == 1 ) {
    echo "<br><br><br>  iLinhasDivida: $iLinhasDivida <br><br><br>";
  }

  if ( $iLinhasDivida > 0 ) {

    foreach ( $aValores as $aValores1 => $aValores2 ) {

      $iCoddiv   = $aValores1;
      $nValorDiv = $aValores2[0];
      $iParcel   = $aValores2[1];
      $iAno      = $aValores2[2];

      if ( !isset( $aParcelamentoComAno[$iAno] ) ) {
        if ( $iDebuga == 1 ) {
          echo "coddiv (1): $aValores1 - valor: $aValores2[0] - x: $aValores2[1] - ano: " . $aValores2[2] . " - P A S S A N D O   A N O...<br>";
        }
        continue;
      }

      $sPagoDivida = "select count(distinct k00_numpar) as quant_parcelas, k00_numtot from caixa.arrecant inner join divida.divida on v01_numpre = k00_numpre and v01_numpar = k00_numpar where v01_coddiv = $iCoddiv group by k00_numtot";
      $rsPagoDivida = db_query( $sPagoDivida );
      if (!$rsPagoDivida) {
      	$bErro = true;
      	$sMsgErro = "Buscando dados da Origem 12";
      }      
      if ( pg_numrows($rsPagoDivida) > 0 ) {
//        die("\n\n\n $sPagoDivida \n\n\n");
      }

      if ( $iDebuga == 1 ) {
        echo "coddiv (2): $aValores1 - valor: $aValores2[0] - x: $aValores2[1] - ano: " . $aValores2[2] . " - nValorDiv: $nValorDiv <br>";
      }

      if ( $nValorDiv > 0 or true ) {

        $lAnularMesmoParcelamento = 1;

        $sDeleteArrecad = "delete from caixa.arrecad using divida.divida where divida.v01_numpre = arrecad.k00_numpre and divida.v01_numpar = arrecad.k00_numpar and divida.v01_coddiv = $iCoddiv";
        $rsDeleteArrecad = db_query($sDeleteArrecad);
        if (!$rsDeleteArrecad) {
        	$bErro = true;
        	$sMsgErro = "Buscando dados da Origem 13";
        }

        if ( $nValorDiv > 0 and pg_numrows($rsPagoDivida ) == 0 ) {

          $sArrecad  = " ";
          $sArrecad .= " insert into caixa.arrecad ";
          $sArrecad .= " ( k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm )";
          $sArrecad .= " select k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, $nValorDiv, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm from caixa.arreold ";
          $sArrecad .= " inner join divida.divida on divida.v01_numpre = arreold.k00_numpre and divida.v01_numpar = arreold.k00_numpar ";
          $sArrecad .= " where divida.v01_coddiv = $iCoddiv";
    //      die("<br><br><br> $sArrecad <br><br><br>");
          $rsArrecad = db_query($sArrecad);
          if (!$rsArrecad) {
          	$bErro = true;
          	$sMsgErro = "Buscando dados da Origem 14";
          }

          $sCalcula  = "";
          $sCalcula .= " select fc_calcula( k00_numpre, k00_numpar, 0, current_date, current_date, extract (year from current_date)::integer ) from caixa.arreold ";
          $sCalcula .= " inner join divida.divida on divida.v01_numpre = arreold.k00_numpre and divida.v01_numpar = arreold.k00_numpar ";
          $sCalcula .= " where divida.v01_coddiv = $iCoddiv";
          $rsCalcula = db_query($sCalcula);
          if (!$rsCalcula) {
          	$bErro = true;
          	$sMsgErro = "Buscando dados da Origem 15";
          }
          $oCalcula = db_utils::fieldsmemory($rsCalcula, 0);

          $sUpdateDivida = "update divida.divida set v01_vlrhis = $nValorDiv where v01_coddiv = $iCoddiv";
          $rsUpdateDivida = db_query($sUpdateDivida);
          if (!$rsUpdateDivida) {
          	$bErro = true;
          	$sMsgErro = "Buscando dados da Origem 16";
          }

          if ( $iDebuga == 1 ) {
            echo "<br> calcula: " . $oCalcula->fc_calcula . " <br>";
            echo "<br>";
          }

        }

        $sPrescricao  = "";
        $sPrescricao .= " select * from divida.divida ";
        $sPrescricao .= " left join divida_sitm_marica on divida.v01_coddiv = divida_sitm_marica.v01_coddiv ";
        $sPrescricao .= " inner join caixa.arrecad on v01_numpre = k00_numpre and v01_numpar = k00_numpar ";
        $sPrescricao .= " where divida.v01_coddiv = $iCoddiv and v01_exerc <= 2006 and k00_tipo = 5 and length(trim( case when divida_sitm_marica.v01_processo is null then '' else divida_sitm_marica.v01_processo end )) = 0 ";
        $rsPrescricao = db_query($sPrescricao);
        if (!$rsPrescricao) {
        	$bErro = true;
        	$sMsgErro = "Buscando dados da Origem 17";
        }
        if ( pg_numrows($rsPrescricao) > 0 ) {

          if ( $iDebuga == 1 ) {
            echo "<br> prescricao do coddiv [$iCoddiv]<br>";
          }

          $sInsert  = " insert into caixa.prescricao ( k31_codigo, k31_data, k31_hora, k31_usuario, k31_obs, k31_instit, k31_situacao )";
          $sInsert .= " select ";
          $sInsert .= " nextval('caixa.prescricao_k31_codigo_seq'), '$iAno-01-01'::date, '00:00', 1, 'migracao SITM', 1, 1; ";
          $rsInsert = db_query($sInsert);
          if (!$rsInsert) {
          	$bErro = true;
          	$sMsgErro = "Buscando dados da Origem 18";
          }

          $sInsert  = " insert into caixa.arreprescr ";
          $sInsert .= " ( k30_numpre, k30_numpar, k30_numcgm, k30_dtoper, k30_receit, k30_hist, k30_valor, k30_dtvenc, k30_numtot, k30_numdig, k30_tipo, k30_tipojm, k30_prescricao, k30_vlrcorr, k30_vlrjuros, k30_multa, k30_desconto, k30_sequencial, k30_anulado )";
          $sInsert .= " select ";
          $sInsert .= " k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm, currval('caixa.prescricao_k31_codigo_seq'), k00_valor, 0, 0, 0, nextval('caixa.arreprescr_k30_sequencial_seq'), false ";
          $sInsert .= " from divida.divida ";
          $sInsert .= " inner join caixa.arrecad on v01_numpre = k00_numpre and v01_numpar = k00_numpar ";
          $sInsert .= " where v01_coddiv = $iCoddiv ";
          $rsInsert = db_query($sInsert);
          if (!$rsInsert) {
          	$bErro = true;
          	$sMsgErro = "Buscando dados da Origem 19";
          }

          $sDeleteArrecad = "delete from caixa.arrecad using divida.divida where divida.v01_numpre = arrecad.k00_numpre and divida.v01_numpar = arrecad.k00_numpar and divida.v01_coddiv = $iCoddiv";
          $rsDeleteArrecad = db_query($sDeleteArrecad);
          if (!$rsDeleteArrecad) {
          	$bErro = true;
          	$sMsgErro = "Buscando dados da Origem 20";
          }

        }

      }

    }

  }

  if ( $iDebuga == 1 ) {
    echo "<br> total parcelamentos a processar [$iLinhasDivida] - lAnularMesmoParcelamento: $lAnularMesmoParcelamento <br>";
  }

  if ( $lAnularMesmoParcelamento == 1 ) {

    for ( $iCont = 0; $iCont < $iLinhasDivida; $iCont++ ) {
      $oTermo = db_utils::fieldsmemory($rsDivida, $iCont);

      if ( $iDebuga == 1 ) {
        echo "<br> processando parcelamento [$oTermo->v07_parcel] - numpre [$oTermo->v07_numpre] <br>";
      }

      $sDeleteArreold = "delete from caixa.arreold where k00_numpre = $oTermo->v07_numpre";
      $rsDeleteArreold = db_query($sDeleteArreold);
      if (!$rsDeleteArreold) {
      	$bErro = true;
      	$sMsgErro = "Buscando dados da Origem 21";
      }

      $sInsert  = "";
      $sInsert  .= " insert into caixa.arreold ( k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm ) ";
      $sInsert  .= " select ";
      $sInsert  .= " k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm  ";
      $sInsert  .= " from caixa.arrecad where k00_numpre = $oTermo->v07_numpre ";
      $rsInsert = db_query($sInsert);
      if (!$rsInsert) {
      	$bErro = true;
      	$sMsgErro = "Buscando dados da Origem 22";
      }
        
      $sDelete  = " delete from caixa.arrecad where k00_numpre = $oTermo->v07_numpre ";
      $rsDelete = db_query($sDelete);
      if (!$rsDelete) {
      	$bErro = true;
      	$sMsgErro = "Buscando dados da Origem 23";
      }

      $sBusca = " select * from divida.termoanu where v09_parcel = $oTermo->v07_parcel ";
      $rsBusca = db_query($sBusca);
      if (!$rsBusca) {
      	$bErro = true;
      	$sMsgErro = "Buscando dados da Origem 24";
      }
      if ( pg_numrows($rsBusca) == 0 ) {
        $sInsert  = " ";
        $sInsert .= " insert into divida.termoanu ( v09_sequencial, v09_parcel, v09_usuario, v09_data, v09_hora, v09_motivo ) ";
        $sInsert .= " select nextval('divida.termoanu_v09_sequencial_seq'), $oTermo->v07_parcel, 1, current_date, substr(current_time::text,1,5), 'MIGRACAO SITM'; ";
        $rsInsert = db_query($sInsert);
        if (!$rsInsert) {
        	$bErro = true;
        	$sMsgErro = "Buscando dados da Origem 25";
        }
      }

      $sUpdateSituacao = "update divida.termo set v07_situacao = 2 where v07_parcel = $oTermo->v07_parcel";
      $rsUpdateSituacao = db_query($sUpdateSituacao);
      if (!$rsUpdateSituacao) {
      	$bErro = true;
      	$sMsgErro = "Buscando dados da Origem 26";
      }

    }

  }

}

if ( $iDebuga == 1 && isset($aParcelamentoComAno) && count($aParcelamentoComAno) > 0) {
  echo "<br><br><br>";
  echo "<pre>";
  var_dump( $aParcelamentoComAno );
  echo "<br><br><br>";
  
}

$sSituacao = "update divida.termo set v07_situacao = 2 from divida.termoanu where v07_parcel = v09_parcel and v07_situacao <> 2";
$rsSituacao = db_query($sSituacao);
if (!$rsSituacao) {
	$bErro = true;
	$sMsgErro = "Buscando dados da Origem 27";
}

if ( $bErro == false ) {

  echo("<br><br><br> Executado com Sucesso <br><br><br>");
  if ($iDebuga == 1) {
  	echo("<br><br><br> Para confirmar operação, refaça o procedimento selecionando a opção 'Simular Anulação: Não' <br><br><br>");
  	db_fim_transacao(true);
  } else {
  	db_fim_transacao(false);
  }
  

} else {
  echo("<br><br><br> Executado COM ERRO <br><br><br>");
  echo $sMsgErro;
  db_fim_transacao(true);
}
?>