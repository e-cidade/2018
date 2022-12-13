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


require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_libtxt.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$iAnoAnterior = db_getsession("DB_anousu") -1;
$alt       = 4;
$pagina    = 1;
$iConvenio = 8;
$iAnoUsu   = db_getsession("DB_anousu");

$head1     = "Relatório IFR 1A";
$head2     = "Demostrativo de Fontes e Usos por Categoria de Despesa";
$iMesAtual = date("m",db_getsession("DB_datausu"));

switch ($oGet->trimestre) {

  case 1:

    $sPeriodo = "Período de Janeiro a Março de {$iAnoUsu}";
    $iMesIni     = 1;
    $iMesFim     = 3;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 12;
    $iNota       = 2;
    $dtDataFinal = "{$iAnoUsu}-03-31";
    $iAnoAnt     = db_getsession("DB_anousu") - 1;
    break;

  case 2:

    $sPeriodo    = "Período de Abril a Junho de {$iAnoUsu}";
    $iMesIni     = 4;
    $iMesFim     = 6;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 3;
    $iNota       = 3;
    $dtDataFinal = "{$iAnoUsu}-06-30";
    $iAnoAnt     = db_getsession("DB_anousu");
    break;

  case 3:

    $sPeriodo = "Período de Julho a Setembro de {$iAnoUsu}";
    $iMesIni     = 7;
    $iMesFim     = 9;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 6;
    $iNota       = 4;
    $iAnoAnt     = db_getsession("DB_anousu");
    $dtDataFinal = "{$iAnoUsu}-09-30";
    break;

  case 4:

    $sPeriodo = "Período de Outubro a Dezembro de {$iAnoUsu}";
    $iMesIni     = 10;
    $iMesFim     = 12;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 9;
    $iNota       = 5;
    $dtDataFinal = "{$iAnoUsu}-12-31";
    $iAnoAnt     = db_getsession("DB_anousu");
    break;

}

$iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN,$iMesFim,$iAnoUsu);
$datausu       = "{$iAnoUsu}-{$iMesFim}-$iUltimoDiaMes";
$head3         = $sPeriodo;
$oDaoConvenio  = db_utils::getDao("pactoplano");
$sSqlConvenio  = $oDaoConvenio->sql_query($oGet->iPlano,"*");
$rsConvenio    = $oDaoConvenio->sql_record($sSqlConvenio);
$oConvenio     = db_utils::fieldsMemory($rsConvenio, 0);
$head4         = "Número do Convênio: {$oConvenio->o16_convenio}";

if ($oConvenio->o74_obs != "") {
  $head5  = "Plano:". substr(str_replace("\n", "",$oConvenio->o74_obs),0,190);
} else {
  $head5  = "Plano:   {$oConvenio->o74_descricao}";
}

/**
 * Adicionado inner join com a conlancamdoc
 * Foi solicitado para trazer somente os lançamentos do doc 100
 */
$sSqlSaldoOrigem  = " select (select ";
$sSqlSaldoOrigem .= "                round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "           from conlancam ";
$sSqlSaldoOrigem .= "                inner join conlancamrec on c70_codlan = c74_codlan";
$sSqlSaldoOrigem .= "                inner join conlancamdoc on c71_codlan = c70_codlan";
$sSqlSaldoOrigem .= "                inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "          where extract(month from  c70_data) between {$iMesIni} and {$iMesFim} ";
$sSqlSaldoOrigem .= "            and c70_anousu  = {$iAnoUsu}";
$sSqlSaldoOrigem .= "            and c74_codrec in (990, 1285, 3654) ";
$sSqlSaldoOrigem .= "            and c53_tipo = 100";
$sSqlSaldoOrigem .= "          ) as saldo_inicial_trim_atual,";

$sSqlSaldoOrigem .= "        (select ";
$sSqlSaldoOrigem .= "                round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "           from conlancam ";
$sSqlSaldoOrigem .= "                inner join conlancamrec on c70_codlan = c74_codlan";
$sSqlSaldoOrigem .= "                inner join conlancamdoc on c71_codlan = c70_codlan";
$sSqlSaldoOrigem .= "                inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "          where extract(month from  c70_data) between {$iMesIniAnt} and {$iMesFimAnt} ";
$sSqlSaldoOrigem .= "            and c70_anousu  = {$iAnoAnt}";
$sSqlSaldoOrigem .= "            and c74_codrec in (990, 1285, 3654) ";
$sSqlSaldoOrigem .= "            and c53_tipo = 100";
$sSqlSaldoOrigem .= "            and c70_data >= '2009-01-01'";
$sSqlSaldoOrigem .= "         ) as saldo_inicial_anterior,";
$sSqlSaldoOrigem .= "        (select ";
$sSqlSaldoOrigem .= "                round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "           from conlancam ";
$sSqlSaldoOrigem .= "                inner join conlancamrec on c70_codlan = c74_codlan";
$sSqlSaldoOrigem .= "                inner join conlancamdoc on c71_codlan = c70_codlan"; 
$sSqlSaldoOrigem .= "                inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "          where extract(month from  c70_data) between 1 and {$iMesFim} ";
$sSqlSaldoOrigem .= "            and c70_anousu  = {$iAnoUsu}";
$sSqlSaldoOrigem .= "            and c74_codrec in (990, 1285, 3654) ";
$sSqlSaldoOrigem .= "            and c53_tipo = 100";
$sSqlSaldoOrigem .= "          ) as saldo_inicial_ano_atual,";

$sSqlSaldoOrigem .= "        (select ";
$sSqlSaldoOrigem .= "                round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "           from conlancam ";
$sSqlSaldoOrigem .= "                inner join conlancamrec on c70_codlan = c74_codlan";
$sSqlSaldoOrigem .= "                inner join conlancamdoc on c71_codlan = c70_codlan";
$sSqlSaldoOrigem .= "                inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "          where extract(month from  c70_data) between 1 and 12 ";
$sSqlSaldoOrigem .= "            and c70_anousu  <= {$iAnoAnterior}";
$sSqlSaldoOrigem .= "            and c74_codrec in (990, 1285, 3654) ";
$sSqlSaldoOrigem .= "            and c53_tipo = 100";
$sSqlSaldoOrigem .= "            and c70_data >= '2009-01-01'";
$sSqlSaldoOrigem .= "          ) as saldo_inicial_ano_anterior,";

$sSqlSaldoOrigem .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoOrigem .= "          from  pactovalorsaldo";
$sSqlSaldoOrigem .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoOrigem .= "          where o103_anousu = {$iAnoUsu}";
$sSqlSaldoOrigem .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlSaldoOrigem .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoOrigem .= "            and o103_pactovalorsaldotipo = 3";
$sSqlSaldoOrigem .= "          ) as planejado_cp100_trim_atual, ";

$sSqlSaldoOrigem .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoOrigem .= "          from  pactovalorsaldo";
$sSqlSaldoOrigem .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoOrigem .= "          where o103_anousu = {$iAnoUsu}";
$sSqlSaldoOrigem .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlSaldoOrigem .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoOrigem .= "            and o103_pactovalorsaldotipo = 3";
$sSqlSaldoOrigem .= "          ) as planejado_cp100_ano_atual, ";

$sSqlSaldoOrigem .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoOrigem .= "          from  pactovalorsaldo";
$sSqlSaldoOrigem .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoOrigem .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoOrigem .= "            and o103_pactovalorsaldotipo = 3";

$sSqlSaldoOrigem .= "            and ( (to_char(o103_anousu,'9999') || '-' || lpad(trim(to_char(o103_mesusu,'99')),2,'0') || '-01')::date <= '{$iAnoUsu}-{$iMesFim}-01'::date )" ;
$sSqlSaldoOrigem .= "          ) as planejado_cp100_acumulado, ";

$sSqlSaldoOrigem .= "        (select ";
$sSqlSaldoOrigem .= "                round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "           from conlancam ";
$sSqlSaldoOrigem .= "                inner join conlancamrec on c70_codlan = c74_codlan";
$sSqlSaldoOrigem .= "                inner join conlancamdoc on c71_codlan = c70_codlan";
$sSqlSaldoOrigem .= "                inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "          where c74_codrec in (990, 1285, 3654) ";
$sSqlSaldoOrigem .= "            and c70_data between '2009-01-01' and '{$dtDataFinal}'";
$sSqlSaldoOrigem .= "            and c53_tipo   = 100";
$sSqlSaldoOrigem .= "          ) as saldo_inicial_acumulado,";

$sSqlSaldoOrigem    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoOrigem    .= "          from  pactovalorsaldo";
$sSqlSaldoOrigem    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoOrigem    .= "          where o103_anousu = {$iAnoAnt}";
$sSqlSaldoOrigem    .= "            and o103_mesusu between {$iMesIniAnt} and {$iMesFimAnt}";
$sSqlSaldoOrigem    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoOrigem    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlSaldoOrigem    .= "            and o103_contrapartida is true";
$sSqlSaldoOrigem    .= "          ) as cp_realizado_trim_anterior ";
$rsSaldoOrigem    = db_query($sSqlSaldoOrigem); 
$oSaldoOrigem     = db_utils::fieldsMemory($rsSaldoOrigem,0);
$oSaldoInicialAnteriorBird    = $oSaldoOrigem->saldo_inicial_anterior;
$oSaldoInicialAnoAnteriorBird = $oSaldoOrigem->saldo_inicial_ano_anterior;
$oSaldoInicialAnteriorCP   = $oSaldoOrigem->saldo_inicial_anterior * ($oConvenio->o16_percentual/100);

$sSqlSaldoAnt     = "select  ";
$sSqlSaldoAnt    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoAnt    .= "          from  pactovalorsaldo";
$sSqlSaldoAnt    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoAnt    .= "          where o103_anousu = {$iAnoAnt}";
$sSqlSaldoAnt    .= "            and o103_mesusu between {$iMesIniAnt} and {$iMesFimAnt}";
$sSqlSaldoAnt    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoAnt    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlSaldoAnt    .= "            and o103_contrapartida is true";
$sSqlSaldoAnt    .= "          ) as cp_realizado_anterior, ";

$sSqlSaldoAnt    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoAnt    .= "          from  pactovalorsaldo";
$sSqlSaldoAnt    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoAnt    .= "          where o103_anousu = {$iAnoAnt}";
$sSqlSaldoAnt    .= "            and o103_mesusu between {$iMesIniAnt} and {$iMesFimAnt}";
$sSqlSaldoAnt    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoAnt    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlSaldoAnt    .= "            and o103_contrapartida is false";
$sSqlSaldoAnt    .= "          ) as bird_realizado_anterior, ";

$sSqlSaldoAnt    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlSaldoAnt    .= "          from  pactovalorsaldo";
$sSqlSaldoAnt    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlSaldoAnt    .= "          where o103_anousu <= {$iAnoAnterior}";
$sSqlSaldoAnt    .= "            and o103_mesusu between 1 and 12";
$sSqlSaldoAnt    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlSaldoAnt    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlSaldoAnt    .= "            and o103_contrapartida is false";
$sSqlSaldoAnt    .= "          ) as bird_realizado_ano_anterior ";

$rsSaldoAnterior  = db_query($sSqlSaldoAnt);
$oSaldoAnterior   = db_utils::fieldsMemory($rsSaldoAnterior,0);

$oConvenio->o16_saldoaberturacp = $oSaldoAnterior->cp_realizado_anterior-$oSaldoAnterior->cp_realizado_anterior;
$oConvenio->o16_saldoabertura   = ($oSaldoInicialAnoAnteriorBird-$oSaldoAnterior->bird_realizado_ano_anterior);


if ($oGet->trimestre > 1  && $oGet->trimestre <= 4) {
  $oConvenio->o16_saldoabertura   = ($oSaldoInicialAnteriorBird+$oSaldoInicialAnoAnteriorBird) -
    ($oSaldoAnterior->bird_realizado_anterior+$oSaldoAnterior->bird_realizado_ano_anterior);
}
$nSaldoInicial = $oConvenio->o16_saldoaberturacp + $oConvenio->o16_saldoabertura;
$sSqlDados     = "select o31_sequencial, o31_descricao,";
$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is true";
$sSqlDados    .= "          ) as cp_realizado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is true";
$sSqlDados    .= "          ) as cp_realizado_ano_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is true";
$sSqlDados    .= "            and ( (to_char(o103_anousu,'9999') || '-' || lpad(trim(to_char(o103_mesusu,'99')),2,'0') || '-01')::date <= '{$iAnoUsu}-{$iMesFim}-01'::date )" ;
$sSqlDados    .= "          ) as cp_realizado_acumulado, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is false";
$sSqlDados    .= "          ) as bird_realizado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is false";
$sSqlDados    .= "          ) as bird_realizado_ano_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is false";
$sSqlDados    .= "            and ( (to_char(o103_anousu,'9999') || '-' || lpad(trim(to_char(o103_mesusu,'99')),2,'0') || '-01')::date <= '{$iAnoUsu}-{$iMesFim}-01'::date )" ;
$sSqlDados    .= "          ) as bird_realizado_acumulado, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "          ) as planejado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "          ) as planejado_ano_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "            and o87_pactoplano           = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto       = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1 ";
$sSqlDados    .= "            and o103_anousu <= {$iAnoUsu}";
$sSqlDados    .= "          ) as planejado_acumulado, ";

$sSqlDados    .= "          (SELECT coalesce(round(sum(o87_vlraproximado),2),0)";
$sSqlDados    .= "          from  pactovalor";
$sSqlDados    .= "          where o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "           and  o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "          ) as planejado_original";

$sSqlDados    .= " from   categoriapacto";
$sSqlDados    .= " where o31_tipopacto = {$oConvenio->o16_tipopacto} order by o31_sequencial";
$rsDados       = db_query($sSqlDados);
$iTotalRows    = pg_num_rows($rsDados);
$aLinhaRelatorio = array();

$aTotalizador["CP"]["realizado_trim_atual"]   = 0;
$aTotalizador["CP"]["realizado_ano_atual"]    = 0;
$aTotalizador["CP"]["realizado_acumulado"]    = 0;
$aTotalizador["CP"]["planejado_trim_atual"]   = 0;
$aTotalizador["CP"]["planejado_ano_atual"]    = 0;
$aTotalizador["CP"]["planejado_acumulado"]    = 0;
$aTotalizador["CP"]["variacao_trim_atual"]    = 0;
$aTotalizador["CP"]["variacao_ano_atual"]     = 0;
$aTotalizador["CP"]["variacao_acumulado"]     = 0;
$aTotalizador["CP"]["planejado_original"]     = 0;
$aTotalizador["BIRD"]["realizado_trim_atual"] = 0;
$aTotalizador["BIRD"]["realizado_ano_atual"]  = 0;
$aTotalizador["BIRD"]["realizado_acumulado"]  = 0;
$aTotalizador["BIRD"]["planejado_trim_atual"] = 0;
$aTotalizador["BIRD"]["planejado_ano_atual"]  = 0;
$aTotalizador["BIRD"]["planejado_acumulado"]  = 0;
$aTotalizador["BIRD"]["variacao_trim_atual"]  = 0;
$aTotalizador["BIRD"]["variacao_ano_atual"]   = 0;
$aTotalizador["BIRD"]["variacao_acumulado"]   = 0;
$aTotalizador["BIRD"]["planejado_original"]   = 0;

for ($i = 0; $i < $iTotalRows; $i++) {

  $oLinhaRel  = db_utils::fieldsMemory($rsDados, $i);

  $oLinhaRel->cp_planejado_trim_atual = round($oLinhaRel->planejado_trim_atual * ($oConvenio->o16_percentual/100),2);
  $oLinhaRel->cp_planejado_ano_atual  = round($oLinhaRel->planejado_ano_atual * ($oConvenio->o16_percentual/100),2);
  $oLinhaRel->cp_planejado_acumulado  = round($oLinhaRel->planejado_acumulado * ($oConvenio->o16_percentual/100),2);
  $oLinhaRel->cp_planejado_original   = round($oLinhaRel->planejado_original * ($oConvenio->o16_percentual/100),2);

  $oLinhaRel->cp_variacao_trim_atual =  $oLinhaRel->cp_planejado_trim_atual - $oLinhaRel->cp_realizado_trim_atual;
  $oLinhaRel->cp_variacao_ano_atual  =  $oLinhaRel->cp_planejado_ano_atual  - $oLinhaRel->cp_realizado_ano_atual;
  $oLinhaRel->cp_variacao_acumulado  =  $oLinhaRel->cp_planejado_acumulado  - $oLinhaRel->cp_realizado_acumulado;

  $oLinhaRel->bird_variacao_trim_atual =  $oLinhaRel->planejado_trim_atual - $oLinhaRel->bird_realizado_trim_atual;
  $oLinhaRel->bird_variacao_ano_atual  =  $oLinhaRel->planejado_ano_atual  - $oLinhaRel->bird_realizado_ano_atual;
  $oLinhaRel->bird_variacao_acumulado  =  $oLinhaRel->planejado_acumulado  - $oLinhaRel->bird_realizado_acumulado;
  $aLinhaRelatorio[] = $oLinhaRel;
  /*
   * Totalizadores
   */
  $aTotalizador["CP"]["realizado_trim_atual"]   += $oLinhaRel->cp_realizado_trim_atual;
  $aTotalizador["CP"]["realizado_ano_atual"]    += $oLinhaRel->cp_realizado_ano_atual;
  $aTotalizador["CP"]["realizado_acumulado"]    += $oLinhaRel->cp_realizado_acumulado;
  $aTotalizador["CP"]["planejado_trim_atual"]   += $oLinhaRel->cp_planejado_trim_atual;
  $aTotalizador["CP"]["planejado_ano_atual"]    += $oLinhaRel->cp_planejado_ano_atual;
  $aTotalizador["CP"]["planejado_acumulado"]    += $oLinhaRel->cp_planejado_acumulado;
  $aTotalizador["CP"]["variacao_trim_atual"]    += $oLinhaRel->cp_variacao_trim_atual;
  $aTotalizador["CP"]["variacao_ano_atual"]     += $oLinhaRel->cp_variacao_ano_atual;
  $aTotalizador["CP"]["variacao_acumulado"]     += $oLinhaRel->cp_variacao_acumulado;
  $aTotalizador["CP"]["planejado_original"]     += $oLinhaRel->cp_planejado_original;
  $aTotalizador["BIRD"]["realizado_trim_atual"] += $oLinhaRel->bird_realizado_trim_atual;
  $aTotalizador["BIRD"]["realizado_ano_atual"]  += $oLinhaRel->bird_realizado_ano_atual;
  $aTotalizador["BIRD"]["realizado_acumulado"]  += $oLinhaRel->bird_realizado_acumulado;
  $aTotalizador["BIRD"]["planejado_trim_atual"] += $oLinhaRel->planejado_trim_atual;
  $aTotalizador["BIRD"]["planejado_ano_atual"]  += $oLinhaRel->planejado_ano_atual;
  $aTotalizador["BIRD"]["planejado_acumulado"]  += $oLinhaRel->planejado_acumulado;
  $aTotalizador["BIRD"]["variacao_trim_atual"]  += $oLinhaRel->bird_variacao_trim_atual;
  $aTotalizador["BIRD"]["variacao_ano_atual"]   += $oLinhaRel->bird_variacao_ano_atual;
  $aTotalizador["BIRD"]["variacao_acumulado"]   += $oLinhaRel->bird_variacao_acumulado;
  $aTotalizador["BIRD"]["planejado_original"]   += $oLinhaRel->planejado_original;

}

$oSaldoOrigem->cp_saldo_inicial_trim_atual =  $aTotalizador["CP"]["realizado_trim_atual"];
$oSaldoOrigem->cp_saldo_inicial_ano_atual  =  $aTotalizador["CP"]["realizado_ano_atual"];
$oSaldoOrigem->cp_saldo_inicial_acumulado  =  $aTotalizador["CP"]["realizado_acumulado"];

$aTotalizador["TOTAL"]["realizado_trim_atual"]   = $aTotalizador["BIRD"]["realizado_trim_atual"]+
                                                   $aTotalizador["CP"]["realizado_trim_atual"];
$aTotalizador["TOTAL"]["realizado_ano_atual"]    = $aTotalizador["CP"]["realizado_ano_atual"]+
                                                   $aTotalizador["BIRD"]["realizado_ano_atual"];
$aTotalizador["TOTAL"]["realizado_acumulado"]    = $aTotalizador["BIRD"]["realizado_acumulado"]+
                                                   $aTotalizador["CP"]["realizado_acumulado"];
$aTotalizador["TOTAL"]["planejado_trim_atual"]   = $aTotalizador["BIRD"]["planejado_trim_atual"]+
                                                   $aTotalizador["CP"]["planejado_trim_atual"];
$aTotalizador["TOTAL"]["planejado_ano_atual"]    = $aTotalizador["BIRD"]["planejado_ano_atual"]+
                                                   $aTotalizador["CP"]["planejado_ano_atual"];
$aTotalizador["TOTAL"]["planejado_acumulado"]    = $aTotalizador["BIRD"]["planejado_acumulado"]+
                                                   $aTotalizador["CP"]["planejado_acumulado"];
$aTotalizador["TOTAL"]["variacao_trim_atual"]    = $aTotalizador["BIRD"]["variacao_trim_atual"]+
                                                   $aTotalizador["CP"]["variacao_trim_atual"];
$aTotalizador["TOTAL"]["variacao_ano_atual"]     = $aTotalizador["BIRD"]["variacao_ano_atual"]+
                                                   $aTotalizador["CP"]["variacao_ano_atual"];
$aTotalizador["TOTAL"]["variacao_acumulado"]     = $aTotalizador["BIRD"]["variacao_acumulado"]+
                                                   $aTotalizador["CP"]["variacao_acumulado"];
$aTotalizador["TOTAL"]["planejado_original"]     = $aTotalizador["BIRD"]["planejado_original"]+
                                                   $aTotalizador["CP"]["planejado_original"];

$pdf->AddPage();
$pdf->cell(60, $alt,'',"LT",0,"C",1);

$pdf->cell(60, $alt,'REALIZADO',"LBT",0,"C",1);
$pdf->cell(60, $alt,'PLANEJADO',"LBT",0,"C",1);

$pdf->cell(60, $alt,'VARIACAO (Planejado - Realizado)',"LBT",0,"C",1);
$pdf->cell(20, $alt,'PROJETADO',"LTR",1,"C",1);
$pdf->cell(60, $alt,'DESCRIÇÃO',"LB",0,"C",1);
$pdf->cell(20, $alt,'Trim atual',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Ano Atual',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Acumulado',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Trim atual',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Ano Atual',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Acumulado',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Trim atual',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Ano Atual',"LTB",0,"C",1);
$pdf->cell(20, $alt,'Acumulado',"LTB",0,"C",1);

$pdf->cell(20, $alt,'ORIGINAL',"LBR",1,"C",1);
$pdf->cell(60, $alt, "A.SALDO DE ABERTURA","LBT",0,"L",1);
$pdf->cell(20, $alt, db_formatar($nSaldoInicial,"f"),1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,1,"R",1);
$pdf->cell(60, $alt, "CP","LTB",0,"L");
$pdf->cell(20, $alt, db_formatar($oConvenio->o16_saldoaberturacp,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");

$pdf->cell(60, $alt, "CP 100% RECONHECIDA","LTB",0,"L");
$pdf->cell(20, $alt, db_formatar(0,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->planejado_cp100_trim_atual,"f"), 1,0,"R");
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->planejado_cp100_ano_atual,"f"), 1,0,"R");
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->planejado_cp100_acumulado,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");
$pdf->cell(60, $alt, "CONTA DESIGNADA - BIRD","LBT",0,"L");
$pdf->cell(20, $alt, db_formatar($oConvenio->o16_saldoabertura,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");
$pdf->cell(60, $alt, "CONTA OPERATIVA - PREFEITURA","LBT",0,"L");
$pdf->cell(20, $alt, db_formatar($nSaldoInicial*($oConvenio->o16_percentual/100),"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");
$pdf->cell(60, $alt, "B. FONTES (ORIGENS) DOS FUNDOS","LBT",0,"L",1);


/*
 * realizado
 */
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_trim_atual+$oSaldoOrigem->saldo_inicial_trim_atual,"f"),1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_ano_atual+$oSaldoOrigem->saldo_inicial_ano_atual,"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_acumulado+$oSaldoOrigem->saldo_inicial_acumulado,"f"),1,0,"R",1);
/**
 * Planejado
 */
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_trim_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_ano_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_acumulado"],"f"),1,0,"R",1);

//Variacao
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_trim_atual"] -
                                 ($oSaldoOrigem->cp_saldo_inicial_trim_atual+$oSaldoOrigem->saldo_inicial_trim_atual),"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_ano_atual"] -
                                 ($oSaldoOrigem->cp_saldo_inicial_ano_atual+$oSaldoOrigem->saldo_inicial_ano_atual),"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_acumulado"] -
                                 ($oSaldoOrigem->cp_saldo_inicial_acumulado+$oSaldoOrigem->saldo_inicial_acumulado),"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_original"],"f"),1,1,"R",1);
$pdf->cell(60, $alt, "CP","LTB",0,"L");

$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_trim_atual,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_ano_atual,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_acumulado,"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_trim_atual"]-$oSaldoOrigem->cp_saldo_inicial_trim_atual  ,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_ano_atual"]-$oSaldoOrigem->cp_saldo_inicial_ano_atual,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_acumulado"]-$oSaldoOrigem->cp_saldo_inicial_acumulado,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_original"],"f"),1,1,"R",0);

$pdf->cell(60, $alt, "BIRD","LBT",0,"L");
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->saldo_inicial_trim_atual,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->saldo_inicial_ano_atual,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->saldo_inicial_acumulado,"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_trim_atual"]-$oSaldoOrigem->saldo_inicial_trim_atual  ,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_ano_atual"]-$oSaldoOrigem->saldo_inicial_ano_atual,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_acumulado"]-$oSaldoOrigem->saldo_inicial_acumulado,"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_original"],"f"),1,1,"R",0);
$pdf->cell(60, $alt, "TOTAL DISPONÍVEL (A+B)","LBT",0,"L",1);


/*
 * Alterada a posição das linhas pois o relatório foi modificado e o planejado passou a ser visto como realizado
 */
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_trim_atual+$oSaldoOrigem->saldo_inicial_trim_atual
                                 +$nSaldoInicial,"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_ano_atual+$oSaldoOrigem->saldo_inicial_ano_atual,"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($oSaldoOrigem->cp_saldo_inicial_acumulado+$oSaldoOrigem->saldo_inicial_acumulado,"f"),1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_trim_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_ano_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_acumulado"],"f"),1,0,"R",1);


$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_trim_atual"] -
                                 ($oSaldoOrigem->cp_saldo_inicial_trim_atual+$oSaldoOrigem->saldo_inicial_trim_atual+$nSaldoInicial),"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_ano_atual"] -
                                 ($oSaldoOrigem->cp_saldo_inicial_ano_atual+$oSaldoOrigem->saldo_inicial_ano_atual),"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_acumulado"] -
                                 ($oSaldoOrigem->cp_saldo_inicial_acumulado+$oSaldoOrigem->saldo_inicial_acumulado),"f"),1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_original"],"f"),1,1,"R",1);

$pdf->cell(60, $alt, "C. USOS DOS FUNDOS",1,1,"L",1);

foreach ($aLinhaRelatorio as $oLinhaRel) {

  $pdf->cell(60, $alt, $oLinhaRel->o31_descricao,1,0,"L",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_realizado_trim_atual+$oLinhaRel->bird_realizado_trim_atual ,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_realizado_ano_atual+$oLinhaRel->bird_realizado_ano_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_realizado_acumulado+$oLinhaRel->bird_realizado_acumulado,"f"),1,0,"R",1);

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_trim_atual+$oLinhaRel->planejado_trim_atual ,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_ano_atual+$oLinhaRel->planejado_ano_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_acumulado+$oLinhaRel->planejado_acumulado,"f"),1,0,"R",1);

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_variacao_trim_atual+$oLinhaRel->bird_variacao_trim_atual ,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_variacao_ano_atual+$oLinhaRel->bird_variacao_ano_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_variacao_acumulado+$oLinhaRel->bird_variacao_acumulado,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_original+$oLinhaRel->planejado_original,"f"),1,1,"R",1);
  /*
   * quadro contrapartida
   */
  $pdf->cell(60, $alt, "CP",1,0,"L");

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_realizado_trim_atual ,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_realizado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_realizado_acumulado,"f"),1,0,"R");

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_acumulado,"f"),1,0,"R");

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_variacao_trim_atual ,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_variacao_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_variacao_acumulado,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->cp_planejado_original,"f"),1,1,"R");
  /*
   * Quadro Bird
   */
  $pdf->cell(60, $alt, "BIRD",1,0,"L");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->bird_realizado_trim_atual ,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->bird_realizado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->bird_realizado_acumulado,"f"),1,0,"R");

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->planejado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->planejado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->planejado_acumulado,"f"),1,0,"R");

  $pdf->cell(20, $alt, db_formatar($oLinhaRel->bird_variacao_trim_atual ,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->bird_variacao_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->bird_variacao_acumulado,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRel->planejado_original,"f"),1,1,"R");

}

$pdf->cell(60, $alt, "Total das Despesas (USOS)","LBT",0,"L",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["realizado_trim_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["realizado_ano_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["realizado_acumulado"],"f"),1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_trim_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_ano_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_acumulado"],"f"),1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["variacao_trim_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["variacao_ano_atual"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["variacao_acumulado"],"f"),1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($aTotalizador["TOTAL"]["planejado_original"],"f"),1,1,"R",1);
$pdf->cell(60, $alt, "CP","LTB",0,"L");
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["realizado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["realizado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["realizado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["variacao_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["variacao_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["variacao_acumulado"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP"]["planejado_original"],"f"),1,1,"R",0);
$pdf->cell(60, $alt, "BIRD","LBT",0,"L");
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["realizado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["realizado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["realizado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_acumulado"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["variacao_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["variacao_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["variacao_acumulado"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["BIRD"]["planejado_original"],"f"),1,1,"R",0);

$pdf->cell(60, $alt, "Disponível menos Despesa (A+B-C)","LBT",0,"L",1);

$nSaldoFinalPlanejadoTrim  =   $aTotalizador["TOTAL"]["planejado_trim_atual"]-
                               $aTotalizador["TOTAL"]["planejado_trim_atual"];

$nSaldoFinalPlanejadoAno   =   $aTotalizador["TOTAL"]["planejado_trim_atual"]-
                               $aTotalizador["TOTAL"]["planejado_trim_atual"];

$nSaldoFinalPlanejadoAcumulado  = $aTotalizador["TOTAL"]["planejado_acumulado"]-
                                  $aTotalizador["TOTAL"]["planejado_acumulado"];

$nSaldoFinalRealizadoAtual =   $oSaldoOrigem->cp_saldo_inicial_trim_atual+$oSaldoOrigem->saldo_inicial_trim_atual
                              +$nSaldoInicial-$aTotalizador["TOTAL"]["realizado_trim_atual"];

$nSaldoFinalRealizadoAno  =   $oSaldoOrigem->cp_saldo_inicial_ano_atual+$oSaldoOrigem->saldo_inicial_ano_atual
                              -$aTotalizador["TOTAL"]["realizado_ano_atual"];

$nSaldoFinalRealizadoAcumulado  =   $oSaldoOrigem->cp_saldo_inicial_acumulado+$oSaldoOrigem->saldo_inicial_acumulado
                                    -$aTotalizador["TOTAL"]["realizado_acumulado"];

$nSaldoVariacaoTrim       =    $nSaldoFinalPlanejadoTrim - $nSaldoFinalRealizadoAtual;
$nSaldoVariacaoAno        =    $nSaldoFinalPlanejadoAno - $nSaldoFinalRealizadoAno;
$nSaldoVariacaoAcumulado  =    $nSaldoFinalPlanejadoAcumulado- $nSaldoFinalRealizadoAcumulado;

$nTotalSaldoContrapartida = $oSaldoOrigem->cp_saldo_inicial_trim_atual-$aTotalizador["CP"]["realizado_trim_atual"]+
                            $oConvenio->o16_saldoaberturacp;
$nTotalSaldoBIRD          = $oSaldoOrigem->saldo_inicial_trim_atual -$aTotalizador["BIRD"]["realizado_trim_atual"]+
                            $oConvenio->o16_saldoabertura;

$nSaldoContrapartida100 =    $nTotalSaldoBIRD*($oConvenio->o16_percentual/100);

if ($iAnoUsu == 2013){
   $iContaBIRD = 21443;
}else{
   $iContaBIRD = $oConvenio->o16_saltes;
}

$sSqlTotalconta = "select substr(fc_saltessaldo({$iContaBIRD},'{$dtDataFinal}',
                         '{$dtDataFinal}',null," . db_getsession("DB_instit")."),41,13)::numeric as atual";
$nTotalSaldoBIRD  = db_utils::fieldsMemory(db_query($sSqlTotalconta),0)->atual;

$pdf->cell(20, $alt, db_formatar($nSaldoFinalRealizadoAtual,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($nSaldoFinalRealizadoAno,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($nSaldoFinalRealizadoAcumulado,"f"), 1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($nSaldoFinalPlanejadoTrim,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($nSaldoFinalPlanejadoAno,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($nSaldoFinalPlanejadoAcumulado,"f"), 1,0,"R",1);

$pdf->cell(20, $alt, db_formatar($nSaldoVariacaoTrim,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($nSaldoVariacaoAno,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar($nSaldoVariacaoAcumulado,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, db_formatar(0,"f"), 1,1,"R",1);
$pdf->cell(60, $alt, "D. SALDO DE ENCERRAMENTO ","LBT",0,"L",1);
$pdf->cell(20, $alt, db_formatar($nSaldoFinalRealizadoAtual,"f"), 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,0,"R",1);
$pdf->cell(20, $alt, "", 1,1,"R",1);
$pdf->cell(60, $alt, "CP","LTB",0,"L");
$pdf->cell(20, $alt, db_formatar($nTotalSaldoContrapartida,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");
$pdf->cell(60, $alt, "CONTA DESIGNADA - BIRD","LBT",0,"L");
$pdf->cell(20, $alt, db_formatar($nTotalSaldoBIRD,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");
$pdf->cell(60, $alt, "CONTA OPERATIVA - PREFEITURA","LBT",0,"L");
$pdf->cell(20, $alt, db_formatar($nSaldoContrapartida100,"f"), 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,0,"R");
$pdf->cell(20, $alt, "", 1,1,"R");
$pdf->ln();

notasExplicativas($pdf,100000,$iNota,190,false);

$pdf->Output();
?>
