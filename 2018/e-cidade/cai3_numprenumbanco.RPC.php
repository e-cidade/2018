<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson             = new services_json();
$oRetorno          = new stdClass();

$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->erro    = 0;

$iInstit   = db_getsession("DB_instit");
$sWhere    = "";
$sAnd      = "";

switch ($oParam->exec) {

  case "pesquisaNumpreNumbanco":

    if (isset($oParam->numpre) && !empty($oParam->numpre)) {

    	$oRetorno->TipoPesquisa = 'Numpre';
    	$sWhere .= "{$sAnd} arr.k00_numpre = {$oParam->numpre}";
    	$sWhereReciboPaga = "{$sAnd} arr.k00_numnov = {$oParam->numpre}";
      $sWhereRecibo     = "{$sAnd} arr.k00_numpre = {$oParam->numpre}";
    	$sAnd    = " and ";
      if (isset($oParam->numpar) && !empty($oParam->numpar)) {

        $sWhere .= "{$sAnd} arr.k00_numpar = {$oParam->numpar}";
        $sAnd    = " and ";
      }
    } else {

      $oRetorno->TipoPesquisa = 'Numbanco';
      $sWhere .= "{$sAnd} arrebanco.k00_numbco ilike '{$oParam->numbanco}%'";
      $sWhereReciboPaga = "{$sAnd} arrebanco.k00_numbco ilike '{$oParam->numbanco}%'";
      $sWhereRecibo     = "{$sAnd} arrebanco.k00_numbco ilike '{$oParam->numbanco}%'";
      $sAnd    = " and ";
    }

    $sSqlNumpreNumbanco  = "    select distinct numnov, numbco, numpre,                                                                   ";
    $sSqlNumpreNumbanco .= "           numpar,                                                                                            ";
    $sSqlNumpreNumbanco .= "           codreceita,                                                                                        ";
    $sSqlNumpreNumbanco .= "           descrreceita,                                                                                      ";
    $sSqlNumpreNumbanco .= "           numcgm,                                                                                            ";
    $sSqlNumpreNumbanco .= "           dtoper,                                                                                            ";
    $sSqlNumpreNumbanco .= "           codhist,                                                                                           ";
    $sSqlNumpreNumbanco .= "           descrhist,                                                                                         ";
    $sSqlNumpreNumbanco .= "           valor,                                                                                             ";
    $sSqlNumpreNumbanco .= "           dtvenc,                                                                                            ";
    $sSqlNumpreNumbanco .= "           totparc,                                                                                           ";
    $sSqlNumpreNumbanco .= "           digitp,                                                                                            ";
    $sSqlNumpreNumbanco .= "           codtipo,                                                                                           ";
    $sSqlNumpreNumbanco .= "           descrtipo,                                                                                         ";
    $sSqlNumpreNumbanco .= "           tipojm,                                                                                            ";
    $sSqlNumpreNumbanco .= "           case                                                                                               ";
    $sSqlNumpreNumbanco .= "             when arrejustreg.k28_sequencia is not null                                                       ";
    $sSqlNumpreNumbanco .= "               then 'Justificado, '||x.situacao                                                               ";
    $sSqlNumpreNumbanco .= "             else x.situacao                                                                                  ";
    $sSqlNumpreNumbanco .= "           end as movimentacao                                                                                ";


    if (isset($oParam->numbanco) && !empty($oParam->numbanco)) {

      $sSqlNumpreNumbanco .= "        ,codbanco,                                                                                          ";
      $sSqlNumpreNumbanco .= "         codagencia,                                                                                        ";
      $sSqlNumpreNumbanco .= "         numbanco,                                                                                          ";
      $sSqlNumpreNumbanco .= "         numbancoant                                                                                        ";
    }

    $sSqlNumpreNumbanco .= "     from ( select k00_numnov as numnov, k00_numbco as numbco, arr.k00_numpre    as numpre,                   ";
    $sSqlNumpreNumbanco .= "                   arr.k00_numpar    as numpar,                                                               ";
    $sSqlNumpreNumbanco .= "                   tabrec.k02_codigo     as codreceita,                                                       ";
    $sSqlNumpreNumbanco .= "                   tabrec.k02_descr      as descrreceita,                                                     ";
    $sSqlNumpreNumbanco .= "                   arrenumcgm.k00_numcgm as numcgm,                                                           ";
		$sSqlNumpreNumbanco .= "                   arr.k00_dtoper    as dtoper,                                                               ";
		$sSqlNumpreNumbanco .= "                   histcalc.k01_codigo   as codhist,                                                          ";
		$sSqlNumpreNumbanco .= "                   histcalc.k01_descr    as descrhist,                                                        ";
		$sSqlNumpreNumbanco .= "                   arr.k00_valor     as valor,                                                                ";
		$sSqlNumpreNumbanco .= "                   arr.k00_dtvenc    as dtvenc,                                                               ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numtot    as totparc,                                                              ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numdig    as digitp,                                                               ";
		$sSqlNumpreNumbanco .= "                   arretipo.k00_tipo     as codtipo,                                                          ";
		$sSqlNumpreNumbanco .= "                   arretipo.k00_descr    as descrtipo,                                                        ";
		$sSqlNumpreNumbanco .= "                   ''                    as tipojm,                                                           ";
		$sSqlNumpreNumbanco .= "                   'Aberto'              as situacao,                                                         ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_codbco as codbanco,                                                          ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_codage as codagencia,                                                        ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_numbco as numbanco,                                                          ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_nbant  as numbancoant                                                        ";
		$sSqlNumpreNumbanco .= "              from arrecad as arr                                                                             ";
		$sSqlNumpreNumbanco .= "                   inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                            ";
		$sSqlNumpreNumbanco .= "                   inner join arrenumcgm on arrenumcgm.k00_numpre = arr.k00_numpre                            ";
		$sSqlNumpreNumbanco .= "                   inner join arretipo   on arretipo.k00_tipo     = arr.k00_tipo                              ";
		$sSqlNumpreNumbanco .= "                   inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                              ";
		$sSqlNumpreNumbanco .= "                   inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                            ";
		$sSqlNumpreNumbanco .= "                                        and arreinstit.k00_instit = {$iInstit}                                ";
		$sSqlNumpreNumbanco .= "                   left  join recibopaga on recibopaga.k00_numpre = arr.k00_numpre                            ";
		$sSqlNumpreNumbanco .= "                                        and recibopaga.k00_numpar = arr.k00_numpar                            ";
		$sSqlNumpreNumbanco .= "                   left  join arrebanco  on arrebanco.k00_numpre  = arr.k00_numpre and arrebanco.k00_numpar = arr.k00_numpar ";

    if (isset($sWhere) && !empty($sWhere)) {
      $sSqlNumpreNumbanco .= "     where   {$sWhere}                                                                                      ";
    }

    $sSqlNumpreNumbanco .= "     union all                                                                                                ";

    $sSqlNumpreNumbanco .= "            select distinct k00_numnov as numnov, k00_numbco as numbco, arr.k00_numpre   as numpre,           ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numpar   as numpar,                                                                ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_codigo     as codreceita,                                                       ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_descr      as descrreceita,                                                     ";
		$sSqlNumpreNumbanco .= "                   ( select c.k00_numcgm                                                                      ";
		$sSqlNumpreNumbanco .= "                       from arrenumcgm c                                                                      ";
		$sSqlNumpreNumbanco .= "                      where c.k00_numpre = arr.k00_numpre limit 1 ) as numcgm,                                ";
		$sSqlNumpreNumbanco .= "                    arr.k00_dtoper   as dtoper,                                                               ";
		$sSqlNumpreNumbanco .= "                    histcalc.k01_codigo   as codhist,                                                         ";
		$sSqlNumpreNumbanco .= "                    histcalc.k01_descr    as descrhist,                                                       ";
		$sSqlNumpreNumbanco .= "                    arr.k00_valor    as valor,                                                                ";
		$sSqlNumpreNumbanco .= "                    arr.k00_dtvenc   as dtvenc,                                                               ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numtot   as totparc,                                                              ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numdig   as digitp,                                                               ";
		$sSqlNumpreNumbanco .= "                    ( select k00_tipo from ( select arretipo.k00_tipo from caixa.arrecad a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit union select arretipo.k00_tipo from caixa.arrecant a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit ) as x limit 1)::integer    as codtipo,                                                          ";
		$sSqlNumpreNumbanco .= "                    ( select k00_descr from ( select arretipo.k00_descr from caixa.arrecad a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit union select arretipo.k00_descr from caixa.arrecant a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit ) as x limit 1)::varchar(40)    as descrtipo,                                                          ";
		$sSqlNumpreNumbanco .= "                    ''                    as tipojm,                                                          ";
		$sSqlNumpreNumbanco .= "                    case when ( select count(*) from caixa.arrecant a where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit and ( select count(*) from caixa.arrepaga b where b.k00_numpre = a.k00_numpre and b.k00_numpar = a.k00_numpar and b.k00_receit = a.k00_receit ) > 0 ) > 0 then 'Pago' else case when ( select count(*) from caixa.arrecad a where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit ) > 0 then 'ABERTO' else case when ( select count(*) from caixa.arrecant a where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit and ( select count(*) from caixa.arrepaga b where b.k00_numpre = a.k00_numpre and b.k00_numpar = a.k00_numpar and b.k00_receit = a.k00_receit ) = 0 ) > 0  then 'CANCELADO' else 'IND' end end end as situacao,                                                        ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_codbco as codbanco,                                                         ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_codage as codagencia,                                                       ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_numbco as numbanco,                                                         ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_nbant  as numbancoant                                                       ";
		$sSqlNumpreNumbanco .= "               from recibopaga as arr                                                                         ";
		$sSqlNumpreNumbanco .= "                    inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                           ";
		$sSqlNumpreNumbanco .= "                    inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                             ";
		$sSqlNumpreNumbanco .= "                    inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                           ";
		$sSqlNumpreNumbanco .= "                                         and arreinstit.k00_instit = {$iInstit}                               ";
    $sSqlNumpreNumbanco .= "                    left  join arrebanco  on arrebanco.k00_numpre  = arr.k00_numnov                           ";


    if (isset($sWhere) && !empty($sWhere)) {
      $sSqlNumpreNumbanco .= "   where   {$sWhereReciboPaga}                                                                              ";
    }

    $sSqlNumpreNumbanco .= "     union all                                                                                                ";

    $sSqlNumpreNumbanco .= "            select distinct k00_numnov as numnov, k00_numbco as numbco, arr.k00_numpre   as numpre,           ";
    $sSqlNumpreNumbanco .= "                   arr.k00_numpar   as numpar,                                                                ";
    $sSqlNumpreNumbanco .= "                   tabrec.k02_codigo     as codreceita,                                                       ";
    $sSqlNumpreNumbanco .= "                   tabrec.k02_descr      as descrreceita,                                                     ";
    $sSqlNumpreNumbanco .= "                   ( select c.k00_numcgm                                                                      ";
    $sSqlNumpreNumbanco .= "                       from arrenumcgm c                                                                      ";
    $sSqlNumpreNumbanco .= "                      where c.k00_numpre = arr.k00_numpre limit 1 ) as numcgm,                                ";
    $sSqlNumpreNumbanco .= "                    arr.k00_dtoper   as dtoper,                                                               ";
    $sSqlNumpreNumbanco .= "                    histcalc.k01_codigo   as codhist,                                                         ";
    $sSqlNumpreNumbanco .= "                    histcalc.k01_descr    as descrhist,                                                       ";
    $sSqlNumpreNumbanco .= "                    arr.k00_valor    as valor,                                                                ";
    $sSqlNumpreNumbanco .= "                    arr.k00_dtvenc   as dtvenc,                                                               ";
    $sSqlNumpreNumbanco .= "                    arr.k00_numtot   as totparc,                                                              ";
    $sSqlNumpreNumbanco .= "                    arr.k00_numdig   as digitp,                                                               ";
    $sSqlNumpreNumbanco .= "                    ( select k00_tipo from ( select arretipo.k00_tipo from caixa.arrecad a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit union select arretipo.k00_tipo from caixa.arrecant a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit ) as x limit 1)::integer    as codtipo,                                                          ";
    $sSqlNumpreNumbanco .= "                    ( select k00_descr from ( select arretipo.k00_descr from caixa.arrecad a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit union select arretipo.k00_descr from caixa.arrecant a inner join caixa.arretipo on arretipo.k00_tipo = a.k00_tipo where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit ) as x limit 1)::varchar(40)    as descrtipo,                                                          ";
    $sSqlNumpreNumbanco .= "                    ''                    as tipojm,                                                          ";
    $sSqlNumpreNumbanco .= "                    case when ( select count(*) from caixa.arrecant a where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit and ( select count(*) from caixa.arrepaga b where b.k00_numpre = a.k00_numpre and b.k00_numpar = a.k00_numpar and b.k00_receit = a.k00_receit ) > 0 ) > 0 then 'Pago' else case when ( select count(*) from caixa.arrecad a where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit ) > 0 then 'ABERTO' else case when ( select count(*) from caixa.arrecant a where a.k00_numpre = arr.k00_numpre and a.k00_numpar = arr.k00_numpar and a.k00_receit = arr.k00_receit and ( select count(*) from caixa.arrepaga b where b.k00_numpre = a.k00_numpre and b.k00_numpar = a.k00_numpar and b.k00_receit = a.k00_receit ) = 0 ) > 0  then 'CANCELADO' else 'IND' end end end as situacao,                                                        ";
    $sSqlNumpreNumbanco .= "                    arrebanco.k00_codbco as codbanco,                                                         ";
    $sSqlNumpreNumbanco .= "                    arrebanco.k00_codage as codagencia,                                                       ";
    $sSqlNumpreNumbanco .= "                    arrebanco.k00_numbco as numbanco,                                                         ";
    $sSqlNumpreNumbanco .= "                    arrebanco.k00_nbant  as numbancoant                                                       ";
    $sSqlNumpreNumbanco .= "               from recibo as arr                                                                             ";
    $sSqlNumpreNumbanco .= "                    inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                           ";
    $sSqlNumpreNumbanco .= "                    inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                             ";
    $sSqlNumpreNumbanco .= "                    inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                           ";
    $sSqlNumpreNumbanco .= "                                         and arreinstit.k00_instit = {$iInstit}                               ";
    $sSqlNumpreNumbanco .= "                    left  join arrebanco  on arrebanco.k00_numpre  = arr.k00_numpre                           ";

    if (isset($sWhereRecibo) && !empty($sWhereRecibo)) {
      $sSqlNumpreNumbanco .= "   where   {$sWhereRecibo}                                                                                  ";
    }

    $sSqlNumpreNumbanco .= "     union all                                                                                                ";

    $sSqlNumpreNumbanco .= "            select k00_numnov as numnov, arrebanco.k00_numbco as numbco, arr.k00_numpre   as numpre,          ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numpar   as numpar,                                                                ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_codigo     as codreceita,                                                       ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_descr      as descrreceita,                                                     ";
		$sSqlNumpreNumbanco .= "                   ( select c.k00_numcgm                                                                      ";
		$sSqlNumpreNumbanco .= "                       from arrenumcgm c                                                                      ";
		$sSqlNumpreNumbanco .= "                      where c.k00_numpre = arr.k00_numpre limit 1 ) as numcgm,                                ";
		$sSqlNumpreNumbanco .= "                    arr.k00_dtoper   as dtoper,                                                               ";
		$sSqlNumpreNumbanco .= "                    histcalc.k01_codigo   as codhist,                                                         ";
		$sSqlNumpreNumbanco .= "                    histcalc.k01_descr    as descrhist,                                                       ";
		$sSqlNumpreNumbanco .= "                    arr.k00_valor    as valor,                                                                ";
		$sSqlNumpreNumbanco .= "                    arr.k00_dtvenc   as dtvenc,                                                               ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numtot   as totparc,                                                              ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numdig   as digitp,                                                               ";
		$sSqlNumpreNumbanco .= "                    arretipo.k00_tipo     as codtipo,                                                         ";
		$sSqlNumpreNumbanco .= "                    arretipo.k00_descr    as descrtipo,                                                       ";
		$sSqlNumpreNumbanco .= "                    ''                    as tipojm,                                                          ";
		$sSqlNumpreNumbanco .= "                    'Pago'                as situacao,                                                        ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_codbco as codbanco,                                                         ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_codage as codagencia,                                                       ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_numbco as numbanco,                                                         ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_nbant  as numbancoant                                                       ";
		$sSqlNumpreNumbanco .= "               from arrepaga as arr                                                                           ";
		$sSqlNumpreNumbanco .= "                    inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                           ";
		$sSqlNumpreNumbanco .= "                    inner join arrecant   on arrecant.k00_numpre   = arr.k00_numpre                           ";
		$sSqlNumpreNumbanco .= "                                         and arrecant.k00_numpar   = arr.k00_numpar                           ";
		$sSqlNumpreNumbanco .= "                                         and arrecant.k00_receit   = arr.k00_receit                           ";
		$sSqlNumpreNumbanco .= "                    inner join arretipo   on arretipo.k00_tipo     = arrecant.k00_tipo                        ";
		$sSqlNumpreNumbanco .= "                    inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                             ";
		$sSqlNumpreNumbanco .= "                    inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                           ";
		$sSqlNumpreNumbanco .= "                                         and arreinstit.k00_instit = {$iInstit}                               ";
    $sSqlNumpreNumbanco .= "                    left  join arrebanco  on (arrebanco.k00_numpre = arr.k00_numpre and arrebanco.k00_numpar = arr.k00_numpar )        ";
    $sSqlNumpreNumbanco .= "                                          or  arrebanco.k00_numpre = arr.k00_numpre                                                    ";
    $sSqlNumpreNumbanco .= "                    left  join recibopaga on recibopaga.k00_numpre = arrecant.k00_numpre                                               ";
    $sSqlNumpreNumbanco .= "                                         and recibopaga.k00_numpar = arrecant.k00_numpar                                               ";
    $sSqlNumpreNumbanco .= "                                         and recibopaga.k00_receit = arrecant.k00_receit                                               ";

    if ($oRetorno->TipoPesquisa == 'Numbanco') {

      $sSqlNumpreNumbanco .= "                                         and recibopaga.k00_numnov not in ( select k00_numpre                                          ";
      $sSqlNumpreNumbanco .= "                                                                              from arrebanco                                           ";
      $sSqlNumpreNumbanco .= "                                                                             where k00_numpre in ( select k00_numnov                   ";
      $sSqlNumpreNumbanco .= "                                                                                                     from recibopaga                   ";
      $sSqlNumpreNumbanco .= "                                                                                                    where k00_numpre = arr.k00_numpre  ";
      $sSqlNumpreNumbanco .= "                                                                                                      and k00_numpar = arr.k00_numpar  ";
      $sSqlNumpreNumbanco .= "                                                                                                      and k00_receit = arr.k00_receit  ";
      $sSqlNumpreNumbanco .= "                                                                                                    group by k00_numnov ) )            ";
    }

    if (isset($sWhere) && !empty($sWhere)) {
      $sSqlNumpreNumbanco .= "   where   {$sWhere}                                                                                        ";
    }

    $sSqlNumpreNumbanco .= "     union all                                                                                                ";

    $sSqlNumpreNumbanco .= "             select k00_numnov as numnov, arrebanco.k00_numbco as numbco, arr.k00_numpre   as numpre,         ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numpar   as numpar,                                                               ";
		$sSqlNumpreNumbanco .= "                    tabrec.k02_codigo     as codreceita,                                                      ";
		$sSqlNumpreNumbanco .= "                    tabrec.k02_descr      as descrreceita,                                                    ";

		$sSqlNumpreNumbanco .= "                    ( select c.k00_numcgm                                                                     ";
		$sSqlNumpreNumbanco .= "                        from arrenumcgm c                                                                     ";
		$sSqlNumpreNumbanco .= "                       where c.k00_numpre = arr.k00_numpre limit 1 ) as numcgm,                               ";

		$sSqlNumpreNumbanco .= "                    arr.k00_dtoper   as dtoper,                                                               ";
		$sSqlNumpreNumbanco .= "                    histcalc.k01_codigo   as codhist,                                                         ";
		$sSqlNumpreNumbanco .= "                    histcalc.k01_descr    as descrhist,                                                       ";
		$sSqlNumpreNumbanco .= "                    arr.k00_valor    as valor,                                                                ";
		$sSqlNumpreNumbanco .= "                    arr.k00_dtvenc   as dtvenc,                                                               ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numtot   as totparc,                                                              ";
		$sSqlNumpreNumbanco .= "                    arr.k00_numdig   as digitp,                                                               ";
		$sSqlNumpreNumbanco .= "                    arretipo.k00_tipo     as codtipo,                                                         ";
		$sSqlNumpreNumbanco .= "                    arretipo.k00_descr    as descrtipo,                                                       ";
		$sSqlNumpreNumbanco .= "                    ''                    as tipojm,                                                          ";
		$sSqlNumpreNumbanco .= "                    'Cancelado'           as situacao,                                                        ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_codbco as codbanco,                                                         ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_codage as codagencia,                                                       ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_numbco as numbanco,                                                         ";
		$sSqlNumpreNumbanco .= "                    arrebanco.k00_nbant  as numbancoant                                                       ";
		$sSqlNumpreNumbanco .= "               from arrecant as arr                                                                           ";
		$sSqlNumpreNumbanco .= "                    inner join tabrec     on tabrec.k02_codigo     = arr.k00_receit                           ";
		$sSqlNumpreNumbanco .= "                    inner join arretipo   on arretipo.k00_tipo     = arr.k00_tipo                             ";
		$sSqlNumpreNumbanco .= "                    inner join histcalc   on histcalc.k01_codigo   = arr.k00_hist                             ";
		$sSqlNumpreNumbanco .= "                    inner join arreinstit on arreinstit.k00_numpre = arr.k00_numpre                           ";
		$sSqlNumpreNumbanco .= "                                         and arreinstit.k00_instit = {$iInstit}                               ";
    $sSqlNumpreNumbanco .= "                    left  join arrebanco  on ( arrebanco.k00_numpre  = arr.k00_numpre and arrebanco.k00_numpar = arr.k00_numpar )";
    $sSqlNumpreNumbanco .= "                                          or arrebanco.k00_numpre  = arr.k00_numpre ";
    $sSqlNumpreNumbanco .= "                    left  join recibopaga on recibopaga.k00_numpre = arr.k00_numpre                           ";
    $sSqlNumpreNumbanco .= "                                         and recibopaga.k00_numpar = arr.k00_numpar                           ";
    $sSqlNumpreNumbanco .= "                                         and recibopaga.k00_receit = arr.k00_receit                           ";
		$sSqlNumpreNumbanco .= "                    left  join arrepaga   on arrepaga.k00_numpre   = arr.k00_numpre                           ";
		$sSqlNumpreNumbanco .= "                                         and arrepaga.k00_numpar   = arr.k00_numpar                           ";
		$sSqlNumpreNumbanco .= "                                         and arrepaga.k00_receit   = arr.k00_receit                           ";
    $sSqlNumpreNumbanco .= "              where arrepaga.k00_numpre is null                                                               ";

    if (isset($sWhere) && !empty($sWhere)) {
      $sSqlNumpreNumbanco .= "  and  {$sWhere}                                                                                            ";
    }

    $sSqlNumpreNumbanco .= "      union all                                                                                               ";

    $sSqlNumpreNumbanco .= "            select k00_numnov as numnov, arrebanco.k00_numbco as numbco, arr.k30_numpre  as numpre,           ";
		$sSqlNumpreNumbanco .= "                   arr.k30_numpar  as numpar,                                                                 ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_codigo      as codreceita,                                                      ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_descr       as descrreceita,                                                    ";

		$sSqlNumpreNumbanco .= "                   ( select c.k00_numcgm                                                                      ";
		$sSqlNumpreNumbanco .= "                       from arrenumcgm c                                                                      ";
		$sSqlNumpreNumbanco .= "                      where c.k00_numpre = arr.k30_numpre limit 1 ) as numcgm,                                ";

		$sSqlNumpreNumbanco .= "                   arr.k30_dtoper  as dtoper,                                                                 ";
		$sSqlNumpreNumbanco .= "                   histcalc.k01_codigo    as codhist,                                                         ";
		$sSqlNumpreNumbanco .= "                   histcalc.k01_descr     as descrhist,                                                       ";
		$sSqlNumpreNumbanco .= "                   arr.k30_valor   as valor,                                                                  ";
		$sSqlNumpreNumbanco .= "                   arr.k30_dtvenc  as dtvenc,                                                                 ";
		$sSqlNumpreNumbanco .= "                   arr.k30_numtot  as totparc,                                                                ";
		$sSqlNumpreNumbanco .= "                   arr.k30_numdig  as digitp,                                                                 ";
		$sSqlNumpreNumbanco .= "                   arretipo.k00_tipo      as codtipo,                                                         ";
		$sSqlNumpreNumbanco .= "                   arretipo.k00_descr     as descrtipo,                                                       ";
		$sSqlNumpreNumbanco .= "                   ''                     as tipojm,                                                          ";
		$sSqlNumpreNumbanco .= "                   'Prescrito'            as situacao,                                                        ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_codbco as codbanco,                                                          ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_codage as codagencia,                                                        ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_numbco as numbanco,                                                          ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_nbant  as numbancoant                                                        ";
		$sSqlNumpreNumbanco .= "              from arreprescr as arr                                                                          ";
		$sSqlNumpreNumbanco .= "                   inner join tabrec     on tabrec.k02_codigo     = arr.k30_receit                            ";
		$sSqlNumpreNumbanco .= "                   inner join arretipo   on arretipo.k00_tipo     = arr.k30_tipo                              ";
		$sSqlNumpreNumbanco .= "                   inner join histcalc   on histcalc.k01_codigo   = arr.k30_hist                              ";
		$sSqlNumpreNumbanco .= "                   inner join arreinstit on arreinstit.k00_numpre = arr.k30_numpre                            ";
		$sSqlNumpreNumbanco .= "                                        and arreinstit.k00_instit = {$iInstit}                                ";
		$sSqlNumpreNumbanco .= "                   left  join recibopaga on recibopaga.k00_numpre = arr.k30_numpre                            ";
		$sSqlNumpreNumbanco .= "                                        and recibopaga.k00_numpar = arr.k30_numpar                            ";
		$sSqlNumpreNumbanco .= "                                        and recibopaga.k00_receit = arr.k30_receit                            ";
		$sSqlNumpreNumbanco .= "                   left  join arrebanco  on arrebanco.k00_numpre  = arr.k30_numpre and arrebanco.k00_numpar = arr.k30_numpar ";

    if (isset($sWhere) && !empty($sWhere)) {

    	if (isset($oParam->numpre) && !empty($oParam->numpre)) {
    		$sWherePrescr = str_replace("k00", "k30", $sWhere);
    	} else {
    		$sWherePrescr = $sWhere;
    	}

    }
    $sSqlNumpreNumbanco .= "   where {$sWherePrescr} {$sAnd} k30_anulado is false                                                         ";


    $sSqlNumpreNumbanco .= "       union all                                                                                              ";
    $sSqlNumpreNumbanco .= "            select k00_numnov as numnov, arrebanco.k00_numbco as numbco, arr.k00_numpre    as numpre,         ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numpar    as numpar,                                                               ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_codigo     as codreceita,                                                       ";
		$sSqlNumpreNumbanco .= "                   tabrec.k02_descr      as descrreceita,                                                     ";

		$sSqlNumpreNumbanco .= "                   ( select c.k00_numcgm                                                                      ";
		$sSqlNumpreNumbanco .= "                       from arrenumcgm c                                                                      ";
		$sSqlNumpreNumbanco .= "                      where c.k00_numpre = arr.k00_numpre limit 1 ) as numcgm,                                ";

		$sSqlNumpreNumbanco .= "                   arr.k00_dtoper    as dtoper,                                                               ";
		$sSqlNumpreNumbanco .= "                   histcalc.k01_codigo   as codhist,                                                          ";
		$sSqlNumpreNumbanco .= "                   histcalc.k01_descr    as descrhist,                                                        ";
		$sSqlNumpreNumbanco .= "                   arr.k00_valor     as valor,                                                                ";
		$sSqlNumpreNumbanco .= "                   arr.k00_dtvenc    as dtvenc,                                                               ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numtot    as totparc,                                                              ";
		$sSqlNumpreNumbanco .= "                   arr.k00_numdig    as digitp,                                                               ";
		$sSqlNumpreNumbanco .= "                   arretipo.k00_tipo     as codtipo,                                                          ";
		$sSqlNumpreNumbanco .= "                   arretipo.k00_descr    as descrtipo,                                                        ";
		$sSqlNumpreNumbanco .= "                   ''                    as tipojm,                                                           ";
		$sSqlNumpreNumbanco .= "                   case                                                                                       ";
		$sSqlNumpreNumbanco .= "                     when divold.k10_sequencial is not null                                                   ";
		$sSqlNumpreNumbanco .= "                       then 'Importado'                                                                       ";
		$sSqlNumpreNumbanco .= "                     when termoreparc.v08_sequencial is not null                                              ";
		$sSqlNumpreNumbanco .= "                       then 'Reparcelado'                                                                     ";
		$sSqlNumpreNumbanco .= "                     else 'Parcelado'                                                                         ";
		$sSqlNumpreNumbanco .= "                   end as situacao,                                                                           ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_codbco as codbanco,                                                          ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_codage as codagencia,                                                        ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_numbco as numbanco,                                                          ";
		$sSqlNumpreNumbanco .= "                   arrebanco.k00_nbant  as numbancoant                                                        ";
		$sSqlNumpreNumbanco .= "              from arreold as arr                                                                             ";
		$sSqlNumpreNumbanco .= "                   inner join tabrec        on tabrec.k02_codigo            = arr.k00_receit                  ";
		$sSqlNumpreNumbanco .= "                   inner join arretipo      on arretipo.k00_tipo            = arr.k00_tipo                    ";
		$sSqlNumpreNumbanco .= "                   inner join histcalc      on histcalc.k01_codigo          = arr.k00_hist                    ";
		$sSqlNumpreNumbanco .= "                   inner join arreinstit    on arreinstit.k00_numpre        = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                                           and arreinstit.k00_instit        = {$iInstit}                      ";
		$sSqlNumpreNumbanco .= "                   left  join inicialnumpre on inicialnumpre.v59_numpre     = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                   left  join termoini      on termoini.inicial             = inicialnumpre.v59_inicial       ";
		$sSqlNumpreNumbanco .= "                   left  join divida        on divida.v01_numpre            = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                                           and divida.v01_numpar            = arr.k00_numpar                  ";
		$sSqlNumpreNumbanco .= "                   left  join termodiv      on termodiv.coddiv              = divida.v01_coddiv               ";
		$sSqlNumpreNumbanco .= "                   left  join arrecant      on arrecant.k00_numpre          = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                                           and arrecant.k00_numpar          = arr.k00_numpar                  ";
		$sSqlNumpreNumbanco .= "                                           and arrecant.k00_receit          = arr.k00_receit                  ";
		$sSqlNumpreNumbanco .= "                   left  join termo         on termo.v07_numpre             = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                   left  join termoreparc   on termoreparc.v08_parcelorigem = termo.v07_parcel                ";
		$sSqlNumpreNumbanco .= "                   left  join divold        on divold.k10_numpre            = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                                           and divold.k10_numpar            = arr.k00_numpar                  ";
		$sSqlNumpreNumbanco .= "                                           and divold.k10_receita           = tabrec.k02_codigo               ";
		$sSqlNumpreNumbanco .= "                   left  join recibopaga    on recibopaga.k00_numpre        = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                                           and recibopaga.k00_numpar        = arr.k00_numpar                  ";
		$sSqlNumpreNumbanco .= "                                           and recibopaga.k00_receit        = arr.k00_receit                  ";
    $sSqlNumpreNumbanco .= "                   left  join arrebanco     on arrebanco.k00_numpre         = arr.k00_numpre                  ";
		$sSqlNumpreNumbanco .= "                                           and arrebanco.k00_numpar         = arr.k00_numpar                  ";

		$sSqlNumpreNumbanco .= "             where arrecant.k00_numpre is null                                                                ";

    if (isset($sWhere) && !empty($sWhere)) {
      $sSqlNumpreNumbanco .= "   and {$sWhere}                                                                                            ";
    }

		$sSqlNumpreNumbanco .= "               and ( termodiv.coddiv  is not null                                                             ";
		$sSqlNumpreNumbanco .= "                  or termoini.inicial is not null                                                             ";
		$sSqlNumpreNumbanco .= "                  or termoreparc.v08_sequencial is not null                                                   ";
		$sSqlNumpreNumbanco .= "                  or divold.k10_sequencial is not null )                                                      ";

    $sSqlNumpreNumbanco .= "            ) as x                                                                                            ";
    $sSqlNumpreNumbanco .= "              left join arrejustreg  on arrejustreg.k28_numpre  = x.numpre                                    ";
    $sSqlNumpreNumbanco .= "                                    and arrejustreg.k28_numpar  = x.numpar                                    ";
    $sSqlNumpreNumbanco .= "                                    and arrejustreg.k28_receita = x.codreceita                                ";

    if (isset($oParam->numbanco) && !empty($oParam->numbanco)) {
      $sSqlNumpreNumbanco = "select distinct numnov, numpre, numpar, codbanco, codagencia, numbanco, numbancoant from ( $sSqlNumpreNumbanco ) as x";
    }

    $rsNumpreNumbanco          = db_query($sSqlNumpreNumbanco);
    $oRetorno->aNumpreNumbanco = db_utils::getCollectionByRecord($rsNumpreNumbanco, false, false, true);

    break;

}

echo $oJson->encode($oRetorno);