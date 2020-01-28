<?php

/**
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

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("classes/db_cfpess_classe.php");

$oPost    = db_utils::postMemory($_POST);
$oJson    = new services_json();

$lErro    = false;
$sMsgErro = '';

if ( $oPost->tipo == "consultaMes" ) {

  if (cl_cfpess::verificarUtilizacaoEstruturaSuplementar()) {

    $sSqlCalculoMes = <<<SQL
      SELECT DISTINCT *
        FROM(
          SELECT DISTINCT fc_mesextenso(rh02_mesusu, 'nome') AS descr,
                          rh02_mesusu                        AS codigo
            FROM rhpessoalmov
                 INNER JOIN rhfolhapagamento   ON rh141_instit         = rh02_instit
                                              AND rh141_anousu         = rh02_anousu
                                              AND rh141_mesusu         = rh02_mesusu
                                              AND rh141_aberto         = FALSE
                 INNER JOIN rhhistoricocalculo ON rh143_folhapagamento = rh141_sequencial
                                              AND rh143_regist         = rh02_regist
           WHERE rh02_anousu = {$oPost->anousu}
             AND rh02_regist = {$oPost->matric}
             AND rh02_instit = {$oPost->instit}

           UNION ALL
          SELECT DISTINCT fc_mesextenso(rh02_mesusu, 'nome') AS descr,
                          rh02_mesusu                        AS codigo
            FROM rhpessoalmov
                 INNER JOIN rhfolhapagamento   ON rh141_instit    = rh02_instit
                                              AND rh141_anousu    = rh02_anousu
                                              AND rh141_mesusu    = rh02_mesusu
                                              AND rh141_aberto    = FALSE
                                              AND rh141_tipofolha = 2
                 INNER JOIN gerfres            ON r20_anousu      = rh02_anousu
                                              AND r20_instit      = rh02_instit
                                              AND r20_regist      = rh02_regist
           WHERE rh02_anousu = {$oPost->anousu}
             AND rh02_regist = {$oPost->matric}
             AND rh02_instit = {$oPost->instit}
           
           UNION ALL
          SELECT DISTINCT fc_mesextenso(rh02_mesusu, 'nome') AS descr,
                          rh02_mesusu                        AS codigo
            FROM rhpessoalmov
                 INNER JOIN rhfolhapagamento   ON rh141_instit    = rh02_instit
                                              AND rh141_anousu    = rh02_anousu
                                              AND rh141_mesusu    = rh02_mesusu
                                              AND rh141_aberto    = FALSE
                                              AND rh141_tipofolha = 5
                 INNER JOIN gerfs13            ON r35_anousu      = rh02_anousu
                                              AND r35_instit      = rh02_instit
                                              AND r35_regist      = rh02_regist
           WHERE rh02_anousu = {$oPost->anousu}
             AND rh02_regist = {$oPost->matric}
             AND rh02_instit = {$oPost->instit}

           UNION ALL
          SELECT DISTINCT fc_mesextenso(rh02_mesusu, 'nome') AS descr,
                          rh02_mesusu                        AS codigo
            FROM rhpessoalmov
                 INNER JOIN rhfolhapagamento   ON rh141_instit    = rh02_instit
                                              AND rh141_anousu    = rh02_anousu
                                              AND rh141_mesusu    = rh02_mesusu
                                              AND rh141_aberto    = FALSE
                                              AND rh141_tipofolha = 4
                 INNER JOIN gerfadi            ON r22_anousu      = rh02_anousu
                                              AND r22_instit      = rh02_instit
                                              AND r22_regist      = rh02_regist
           WHERE rh02_anousu = {$oPost->anousu}
             AND rh02_regist = {$oPost->matric}
             AND rh02_instit = {$oPost->instit}
        ) AS ordena
       ORDER BY codigo DESC;
SQL;
  } else {

    $sSqlCalculoMes = " select distinct rh02_mesusu as codigo, 
                                      case when rh02_mesusu = 1  then 'Janeiro'  
                                           when rh02_mesusu = 2  then 'Fevereiro' 
                                           when rh02_mesusu = 3  then 'Março'
                                           when rh02_mesusu = 4  then 'Abril'
                                           when rh02_mesusu = 5  then 'Maio'
                                           when rh02_mesusu = 6  then 'Junho'
                                           when rh02_mesusu = 7  then 'Julho'
                                           when rh02_mesusu = 8  then 'Agosto'
                                           when rh02_mesusu = 9  then 'Setembro'
                                           when rh02_mesusu = 10 then 'Outubro'
                                           when rh02_mesusu = 11 then 'Novembro '
                                           when rh02_mesusu = 12 then 'Dezembro'  end as descr
                           from rhpessoalmov 
                                left join rhpesrescisao on rh05_seqpes = rh02_seqpes
                          where rh02_regist  = {$oPost->matric} 
                            and rh02_anousu  = {$oPost->anousu}
                            and rh02_instit  = {$oPost->instit} 
                            and case when rh02_anousu = fc_anofolha({$oPost->instit}) 
                                      and rh02_mesusu = fc_mesfolha({$oPost->instit}) then false else true end
                          order by rh02_mesusu desc ";
  }

  $rsCalculoMes = db_query($sSqlCalculoMes);
    
  if ( $rsCalculoMes ) {
    $aRetorno = db_utils::getCollectionByRecord($rsCalculoMes,false,false,true);
  } else {
    $sMsgErro = pg_last_error();
    $lErro    = true;
  }   
  
  if ( $lErro ) {
    $aRetorno  = array( "sMsg" =>urlencode($sMsgErro),
                        "lErro"=>true );    
  } else {
    $aRetorno  = array( "aLista"=>$aRetorno,
                        "lErro" =>false );
  }
  
  echo $oJson->encode($aRetorno); 

} else if ( $oPost->tipo == "consultaTipoCalc" ) {
  
      $sSqlTipoCalculo  = "select distinct 'r14' as codigo ,case when r14_regist is not null then 'Salário' end as descr      ";
      $sSqlTipoCalculo .= "     from gerfsal                                                                                  ";
      $sSqlTipoCalculo .= "    where r14_regist = {$oPost->matric}                                                            ";
      $sSqlTipoCalculo .= "      and r14_anousu = {$oPost->anousu}                                                            ";
      $sSqlTipoCalculo .= "      and r14_mesusu = {$oPost->mesusu}                                                            ";
      $sSqlTipoCalculo .= "  union all                                                                                        ";
      $sSqlTipoCalculo .= " select distinct 'r22' as codigo,case when r22_regist is not null then 'Adiantamento' end as descr ";
      $sSqlTipoCalculo .= "     from gerfadi                                                                                  ";
      $sSqlTipoCalculo .= "    where r22_regist = {$oPost->matric}                                                            ";
      $sSqlTipoCalculo .= "      and r22_anousu = {$oPost->anousu}                                                            ";
      $sSqlTipoCalculo .= "      and r22_mesusu = {$oPost->mesusu}                                                            ";
      $sSqlTipoCalculo .= "  union all                                                                                        ";
      $sSqlTipoCalculo .= " select distinct 'r48' as codigo,case when r48_regist is not null then 'Complementar' end as descr ";
      $sSqlTipoCalculo .= "     from gerfcom                                                                                  ";
      $sSqlTipoCalculo .= "    where r48_regist = {$oPost->matric}                                                            ";
      $sSqlTipoCalculo .= "      and r48_anousu = {$oPost->anousu}                                                            ";
      $sSqlTipoCalculo .= "      and r48_mesusu = {$oPost->mesusu}                                                            ";
      $sSqlTipoCalculo .= " union all                                                                                         ";
      $sSqlTipoCalculo .= " select distinct 'r35' as codigo,case when r35_regist is not null then '13º Salário' end as descr  ";
      $sSqlTipoCalculo .= "     from gerfs13                                                                                  ";
      $sSqlTipoCalculo .= "    where r35_regist = {$oPost->matric}                                                            ";
      $sSqlTipoCalculo .= "      and r35_anousu = {$oPost->anousu}                                                            ";
      $sSqlTipoCalculo .= "      and r35_mesusu = {$oPost->mesusu}                                                            ";
      $sSqlTipoCalculo .= "  union all                                                                                        ";
      $sSqlTipoCalculo .= " select distinct 'r20' as codigo,case when r20_regist is not null then 'Rescisão' end as descr     ";
      $sSqlTipoCalculo .= "     from gerfres                                                                                  ";
      $sSqlTipoCalculo .= "    where r20_regist = {$oPost->matric}                                                            ";
      $sSqlTipoCalculo .= "      and r20_anousu = {$oPost->anousu}                                                            ";
      $sSqlTipoCalculo .= "      and r20_mesusu = {$oPost->mesusu}                                                            ";

    /**
     * Se o parâmetro da sessão "DB_COMPLEMENTAR" estiver ativado, 
     * será realizado a busca das folhas na tabela rhfolhapagamento e rhhistoricocalculo para verificar 
     * a existência de suplementar para o servidor informado na competência atual;
     */
    if (cl_cfpess::verificarUtilizacaoEstruturaSuplementar()){

      $sSalario = "SELECT DISTINCT 'r14'   AS codigo,
                          rh141_codigo     AS numero,
                          rh141_sequencial AS sequencial,
                          'Salário'        AS descr
          FROM rhfolhapagamento
               INNER JOIN rhhistoricocalculo ON rh143_folhapagamento = rh141_sequencial
                                            AND rh143_regist         = {$oPost->matric}
         WHERE rh141_anousu    = {$oPost->anousu}
           AND rh141_mesusu    = {$oPost->mesusu}
           AND rh141_tipofolha = 1
           AND rh141_aberto    = FALSE
           AND EXISTS(
             SELECT 1
               FROM gerfsal
              WHERE r14_regist = rh143_regist
                AND r14_anousu = {$oPost->anousu}
                AND r14_mesusu = {$oPost->mesusu}
                AND r14_instit = rh141_instit
              LIMIT 1
           )
         UNION ALL
      ";

      $sComplementar = "SELECT DISTINCT 'r48'                     AS codigo,
                          rh141_codigo                            AS numero,
                          rh141_sequencial                        AS sequencial,
                          concat('Complementar nº', rh141_codigo) AS descr
          FROM rhfolhapagamento
               INNER JOIN rhhistoricocalculo ON rh143_folhapagamento = rh141_sequencial
                                            AND rh143_regist         = {$oPost->matric}
         WHERE rh141_anousu    = {$oPost->anousu}
           AND rh141_mesusu    = {$oPost->mesusu}
           AND rh141_tipofolha = 3
           AND rh141_aberto    = FALSE
           AND EXISTS(
             SELECT 1
               FROM gerfcom
              WHERE r48_regist = rh143_regist
                AND r48_anousu = {$oPost->anousu}
                AND r48_mesusu = {$oPost->mesusu}
                AND r48_instit = rh141_instit LIMIT 1
           )
         UNION ALL
      ";

      $sSuplementar = "SELECT DISTINCT 'supl'                  AS codigo,
                        rh141_codigo                           AS numero,
                        rh141_sequencial                       AS sequencial,
                        concat('Suplementar nº', rh141_codigo) AS descr
          FROM rhfolhapagamento
               INNER JOIN rhhistoricocalculo ON rh143_folhapagamento = rh141_sequencial
                                            AND rh143_regist         = {$oPost->matric}
         WHERE rh141_anousu    = {$oPost->anousu}
           AND rh141_mesusu    = {$oPost->mesusu}
           AND rh141_tipofolha = 6
           AND rh141_aberto    = FALSE
           AND EXISTS(
             SELECT 1
               FROM gerfsal
              WHERE r14_regist = rh143_regist
                AND r14_anousu = {$oPost->anousu}
                AND r14_mesusu = {$oPost->mesusu}
                AND r14_instit = rh141_instit LIMIT 1
           )
         UNION ALL
      ";

      $sRescisao = "SELECT DISTINCT 'r20'  AS codigo,
                          rh141_codigo     AS numero,
                          rh141_sequencial AS sequencial,
                          'Rescisão'       AS descr
          FROM rhfolhapagamento
         WHERE rh141_anousu    = {$oPost->anousu}
           AND rh141_mesusu    = {$oPost->mesusu}
           AND rh141_tipofolha = 2
           AND rh141_aberto    = FALSE
           AND EXISTS(
             SELECT 1
               FROM gerfres
              WHERE r20_regist = {$oPost->matric}
                AND r20_anousu = {$oPost->anousu}
                AND r20_mesusu = {$oPost->mesusu}
                AND r20_instit = rh141_instit LIMIT 1
           )
         UNION ALL
      ";

      $sAdiantamento = "SELECT DISTINCT 'r22' AS codigo,
                          rh141_codigo        AS numero,
                          rh141_sequencial    AS sequencial,
                          'Adiantamento'      AS descr
          FROM rhfolhapagamento
         WHERE rh141_anousu    = {$oPost->anousu}
           AND rh141_mesusu    = {$oPost->mesusu}
           AND rh141_tipofolha = 4
           AND rh141_aberto    = FALSE
           AND EXISTS(
             SELECT 1
               FROM gerfadi
              WHERE r22_regist = {$oPost->matric}
                AND r22_anousu = {$oPost->anousu}
                AND r22_mesusu = {$oPost->mesusu}
                AND r22_instit = rh141_instit LIMIT 1
           )
         UNION ALL
      ";

      $sDecimo = "SELECT DISTINCT 'r35'    AS codigo,
                          rh141_codigo     AS numero,
                          rh141_sequencial AS sequencial,
                          '13º Salário'    AS descr
         FROM rhfolhapagamento
         WHERE rh141_anousu    = {$oPost->anousu}
           AND rh141_mesusu    = {$oPost->mesusu}
           AND rh141_tipofolha = 5
           AND rh141_aberto    = FALSE
           AND EXISTS(
             SELECT 1
               FROM gerfs13
              WHERE r35_regist = {$oPost->matric}
                AND r35_anousu = {$oPost->anousu}
                AND r35_mesusu = {$oPost->mesusu}
                AND r35_instit = rh141_instit LIMIT 1
           )
      ";

      $sSqlTipoCalculo = $sComplementar
                       . $sSalario
                       . $sSuplementar
                       . $sAdiantamento
                       . $sRescisao
                       . $sDecimo;
    }

    $rsTipoCalculo   = db_query($sSqlTipoCalculo);
    
    if ( $rsTipoCalculo ) {
      $aRetorno = db_utils::getCollectionByRecord($rsTipoCalculo,false,false,true);
    } else {
      $sMsgErro = pg_last_error();
      $lErro    = true;
    } 

  
  if ( $lErro ) {
    $aRetorno  = array( "sMsg" =>urlencode($sMsgErro),
                        "lErro"=>true );    
  } else {
    $aRetorno  = array( "aLista"=>$aRetorno,
                        "lErro" =>false );
  }

  echo $oJson->encode($aRetorno);
    
}
  
?>