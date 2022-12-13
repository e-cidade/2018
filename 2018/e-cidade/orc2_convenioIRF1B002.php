<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_liborcamento.php");
include("fpdf151/assinatura.php");
include("classes/db_orcparamrel_classe.php");
include("libs/db_libcontabilidade.php");
include("libs/db_libtxt.php");
include("dbforms/db_funcoes.php");
$oGet = db_utils::postMemory($_GET);


$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->SetAutoPageBreak(false,1);
$pdf->setfont('arial','b',7);

$iAnoAnterior = db_getsession("DB_anousu") -1;
$alt       = 4;
$pagina    = 1;
$iConvenio = 8;
$iAnoUsu   = db_getsession("DB_anousu"); 
$head1     = "Relatório IFR 1B";
$head2     = "Fontes e Usos por Componente e SubComponente";
$iMesAtual = date("m",db_getsession("DB_datausu"));

switch ($oGet->trimestre) {
  
  case 1:
    
    $sPeriodo = "Período de Janeiro a Março de {$iAnoUsu}";
    $iMesIni  = 1;
    $iMesFim  = 3;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 12;
    $iNota       = 2;
    $dtDataFinal = "{$iAnoUsu}-03-31";
    $iAnoAnt     = db_getsession("DB_anousu") - 1;
    break;
    
  case 2:
    
    $sPeriodo = "Período de Abril a Junho de {$iAnoUsu}";
    $iMesIni  = 4;
    $iMesFim  = 6;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 3;
    $iNota       = 3;
    $dtDataFinal = "{$iAnoUsu}-06-30";
    $iAnoAnt     = db_getsession("DB_anousu");
    break;

  case 3:
    
    $sPeriodo = "Período de Julho a Setembro de {$iAnoUsu}";
    $iMesIni  = 7;
    $iMesFim  = 9;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 6;
    $iNota       = 4;
    $iAnoAnt     = db_getsession("DB_anousu");
    $dtDataFinal = "{$iAnoUsu}-09-30";
    break;

  case 4:
    
    $sPeriodo = "Período de Outubro a Dezembro de {$iAnoUsu}";
    $iMesIni  = 10;
    $iMesFim  = 12;
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
$nSaldoInicial = $oConvenio->o16_saldoaberturacp + $oConvenio->o16_saldoabertura;
$sSqlDados     = "select distinct o54_descr, o55_descr,o87_pactoprograma, o55_projativ,";
$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is true";
$sSqlDados    .= "          ) as cp_realizado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is true";
$sSqlDados    .= "          ) as cp_realizado_ano_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo ";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is true";
$sSqlDados    .= "            and ( (to_char(o103_anousu,'9999') || '-' || lpad(trim(to_char(o103_mesusu,'99')),2,'0') || '-01')::date <= '{$iAnoUsu}-{$iMesFim}-01'::date )" ;
$sSqlDados    .= "          ) as cp_realizado_acumulado, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is false";
$sSqlDados    .= "          ) as bird_realizado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is false";
$sSqlDados    .= "          ) as bird_realizado_ano_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo ";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "            and o103_contrapartida is false";
$sSqlDados    .= "            and ( (to_char(o103_anousu,'9999') || '-' || lpad(trim(to_char(o103_mesusu,'99')),2,'0') || '-01')::date <= '{$iAnoUsu}-{$iMesFim}-01'::date )" ;
//$sSqlDados    .= "            and o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "          ) as bird_realizado_acumulado, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "          ) as planejado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "          ) as planejado_ano_atual, ";
//
$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
$sSqlDados    .= "          where  a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "            and o103_anousu <= {$iAnoUsu}";
$sSqlDados    .= "          ) as planejado_acumulado, ";

$sSqlDados    .= "          (SELECT coalesce(sum(o87_vlraproximado),0)";
$sSqlDados    .= "          from  pactovalor a";
$sSqlDados    .= "          where a.o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "          ) as planejado_original,";

$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "          ) as cp100_planejado_trim_atual, ";

$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between 1 and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 3";
$sSqlDados    .= "          ) as cp100_planejado_ano_atual, ";

$sSqlDados    .= "       (SELECT coalesce(round(sum(o103_valor),2),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor a on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 3";
$sSqlDados    .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
$sSqlDados    .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
$sSqlDados    .= "            and o103_anousu <= {$iAnoUsu}";
$sSqlDados    .= "          ) as cp100_planejado_acumulado";
$sSqlDados    .= " from   pactovalor val ";
$sSqlDados    .= "       inner join orcprojativ   on o55_anousu      = o87_orcprojativanoprojeto";
$sSqlDados    .= "                               and o55_projativ    = o87_orcprojativativprojeto";
$sSqlDados    .= "       inner join orcprograma   on o54_anousu      = o87_orcprogramaano";
$sSqlDados    .= "                               and o54_programa    = o87_pactoprograma";
$sSqlDados    .= " where o87_pactoplano  = {$oGet->iPlano} order by o54_descr, o55_descr";
$rsDados       = db_query($sSqlDados);
$iTotalRows    = pg_num_rows($rsDados);
$aLinhaRelatorio = array();

$aTotalizador["CP"]["realizado_trim_atual"]    = 0; 
$aTotalizador["CP"]["realizado_ano_atual"]     = 0;
$aTotalizador["CP"]["realizado_acumulado"]     = 0;
$aTotalizador["CP"]["planejado_trim_atual"]    = 0; 
$aTotalizador["CP"]["planejado_ano_atual"]     = 0;
$aTotalizador["CP"]["planejado_acumulado"]     = 0;
$aTotalizador["CP"]["variacao_trim_atual"]     = 0; 
$aTotalizador["CP"]["variacao_ano_atual"]      = 0;
$aTotalizador["CP"]["variacao_acumulado"]      = 0; 
$aTotalizador["CP"]["planejado_original"]      = 0;
$aTotalizador["CP100"]["realizado_trim_atual"] = 0; 
$aTotalizador["CP100"]["realizado_ano_atual"]  = 0;
$aTotalizador["CP100"]["realizado_acumulado"]  = 0;
$aTotalizador["CP100"]["planejado_trim_atual"] = 0; 
$aTotalizador["CP100"]["planejado_ano_atual"]  = 0;
$aTotalizador["CP100"]["planejado_acumulado"]  = 0;
$aTotalizador["CP100"]["variacao_trim_atual"]  = 0; 
$aTotalizador["CP100"]["variacao_ano_atual"]   = 0;
$aTotalizador["CP100"]["variacao_acumulado"]   = 0; 
$aTotalizador["CP100"]["planejado_original"]   = 0;
$aTotalizador["BIRD"]["realizado_trim_atual"]  = 0; 
$aTotalizador["BIRD"]["realizado_ano_atual"]   = 0;
$aTotalizador["BIRD"]["realizado_acumulado"]   = 0;
$aTotalizador["BIRD"]["planejado_trim_atual"]  = 0; 
$aTotalizador["BIRD"]["planejado_ano_atual"]   = 0;
$aTotalizador["BIRD"]["planejado_acumulado"]   = 0;
$aTotalizador["BIRD"]["variacao_trim_atual"]   = 0; 
$aTotalizador["BIRD"]["variacao_ano_atual"]    = 0;
$aTotalizador["BIRD"]["variacao_acumulado"]    = 0; 
$aTotalizador["BIRD"]["planejado_original"]    = 0;

for ($i = 0; $i < $iTotalRows; $i++) {
  
  $oLinhaRel  = db_utils::fieldsMemory($rsDados, $i);
//  echo "x: $oLinhaRel->planejado_trim_atual === $oConvenio->o16_percentual<br>";

  $oLinhaRel->cp_planejado_trim_atual = round($oLinhaRel->planejado_trim_atual * ($oConvenio->o16_percentual/100),2);
  $oLinhaRel->cp_planejado_ano_atual  = round($oLinhaRel->planejado_ano_atual * ($oConvenio->o16_percentual/100),2);
  $oLinhaRel->cp_planejado_acumulado  = round($oLinhaRel->planejado_acumulado * ($oConvenio->o16_percentual/100),2);
  $oLinhaRel->cp_planejado_original   = round($oLinhaRel->planejado_original * ($oConvenio->o16_percentual/100),2);
  
  $oLinhaRel->cp_variacao_trim_atual    =  $oLinhaRel->cp_planejado_trim_atual - $oLinhaRel->cp_realizado_trim_atual;
  $oLinhaRel->cp_variacao_ano_atual     =  $oLinhaRel->cp_planejado_ano_atual  - $oLinhaRel->cp_realizado_ano_atual;
  $oLinhaRel->cp_variacao_acumulado     =  $oLinhaRel->cp_planejado_acumulado  - $oLinhaRel->cp_realizado_acumulado;
  
  $oLinhaRel->cp100_variacao_trim_atual =  $oLinhaRel->cp100_planejado_trim_atual;
  $oLinhaRel->cp100_variacao_ano_atual  =  $oLinhaRel->cp100_planejado_ano_atual;
  $oLinhaRel->cp100_variacao_acumulado  =  $oLinhaRel->cp100_planejado_acumulado;
   
  $oLinhaRel->bird_variacao_trim_atual  =  $oLinhaRel->planejado_trim_atual - $oLinhaRel->bird_realizado_trim_atual;
  $oLinhaRel->bird_variacao_ano_atual   =  $oLinhaRel->planejado_ano_atual  - $oLinhaRel->bird_realizado_ano_atual;
  $oLinhaRel->bird_variacao_acumulado   =  $oLinhaRel->planejado_acumulado  - $oLinhaRel->bird_realizado_acumulado;

  if (isset($aLinhaRelatorio[$oLinhaRel->o87_pactoprograma])) {
    
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_trim_atual += $oLinhaRel->cp_planejado_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_ano_atual  += $oLinhaRel->cp_planejado_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_acumulado  += $oLinhaRel->cp_planejado_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_realizado_trim_atual += $oLinhaRel->cp_realizado_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_realizado_ano_atual  += $oLinhaRel->cp_realizado_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_realizado_acumulado  += $oLinhaRel->cp_realizado_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_variacao_trim_atual  += $oLinhaRel->cp_variacao_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_variacao_ano_atual   += $oLinhaRel->cp_variacao_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_variacao_acumulaado  += $oLinhaRel->cp_variacao_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_original   += $oLinhaRel->cp_planejado_original;
     
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_trim_atual += $oLinhaRel->cp100_planejado_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_ano_atual  += $oLinhaRel->cp100_planejado_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_acumulado  += $oLinhaRel->cp100_planejado_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_realizado_trim_atual += 0;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_realizado_ano_atual  += 0;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_realizado_acumulado  += 0;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_variacao_trim_atual  += $oLinhaRel->cp100_variacao_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_variacao_ano_atual   += $oLinhaRel->cp100_variacao_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_variacao_acumulaado  += $oLinhaRel->cp100_variacao_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_original   += 0;
     
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_trim_atual       += $oLinhaRel->planejado_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_ano_atual        += $oLinhaRel->planejado_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_acumulado        += $oLinhaRel->planejado_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_realizado_trim_atual  += $oLinhaRel->bird_realizado_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_realizado_ano_atual   += $oLinhaRel->bird_realizado_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_realizado_acumulado   += $oLinhaRel->bird_realizado_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_variacao_trim_atual   += $oLinhaRel->bird_variacao_trim_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_variacao_ano_atual    += $oLinhaRel->bird_variacao_ano_atual;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_variacao_acumulado    += $oLinhaRel->bird_variacao_acumulado;
     $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_original         += $oLinhaRel->planejado_original;
     
  } else {
    
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->o54_descr                = $oLinhaRel->o54_descr;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->o87_pactoprograma        = $oLinhaRel->o87_pactoprograma;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_trim_atual  = $oLinhaRel->cp_planejado_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_ano_atual   = $oLinhaRel->cp_planejado_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_acumulado   = $oLinhaRel->cp_planejado_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_realizado_trim_atual  = $oLinhaRel->cp_realizado_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_realizado_ano_atual   = $oLinhaRel->cp_realizado_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_realizado_acumulado   = $oLinhaRel->cp_realizado_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_variacao_trim_atual   = $oLinhaRel->cp_variacao_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_variacao_ano_atual    = $oLinhaRel->cp_variacao_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_variacao_acumulado    = $oLinhaRel->cp_variacao_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp_planejado_original    = $oLinhaRel->cp_planejado_original;
     
    
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_trim_atual = $oLinhaRel->cp100_planejado_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_ano_atual  = $oLinhaRel->cp100_planejado_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_acumulado  = $oLinhaRel->cp100_planejado_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_realizado_trim_atual = 0;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_realizado_ano_atual  = 0;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_realizado_acumulado  = 0;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_variacao_trim_atual  = $oLinhaRel->cp100_variacao_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_variacao_ano_atual   = $oLinhaRel->cp100_variacao_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_variacao_acumulado  = $oLinhaRel->cp100_variacao_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->cp100_planejado_original   = 0;
    
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_trim_atual      = $oLinhaRel->planejado_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_ano_atual       = $oLinhaRel->planejado_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_acumulado       = $oLinhaRel->planejado_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_realizado_trim_atual = $oLinhaRel->bird_realizado_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_realizado_ano_atual  = $oLinhaRel->bird_realizado_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_realizado_acumulado  = $oLinhaRel->bird_realizado_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_variacao_trim_atual  = $oLinhaRel->bird_variacao_trim_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_variacao_ano_atual   = $oLinhaRel->bird_variacao_ano_atual;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->bird_variacao_acumulado   = $oLinhaRel->bird_variacao_acumulado;
    $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->planejado_original        = $oLinhaRel->planejado_original;
  }
  $aLinhaRelatorio[$oLinhaRel->o87_pactoprograma]->aAtividades[] = $oLinhaRel;
  
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
  
  $aTotalizador["CP100"]["planejado_trim_atual"]   += $oLinhaRel->cp100_planejado_trim_atual;
  $aTotalizador["CP100"]["planejado_ano_atual"]    += $oLinhaRel->cp100_planejado_ano_atual;
  $aTotalizador["CP100"]["planejado_acumulado"]    += $oLinhaRel->cp100_planejado_acumulado;
  $aTotalizador["CP100"]["realizado_trim_atual"]   += 0; 
  $aTotalizador["CP100"]["realizado_ano_atual"]    += 0;
  $aTotalizador["CP100"]["realizado_acumulado"]    += 0;
  $aTotalizador["CP100"]["variacao_trim_atual"]    += $oLinhaRel->cp100_variacao_trim_atual; 
  $aTotalizador["CP100"]["variacao_ano_atual"]     += $oLinhaRel->cp100_variacao_ano_atual;
  $aTotalizador["CP100"]["variacao_acumulado"]     += $oLinhaRel->cp100_variacao_acumulado; 
  $aTotalizador["CP100"]["planejado_original"]     += 0;
  
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
//exit;

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
$pdf->cell(10, $alt,'',"LT",0,"C",1);
$pdf->cell(60, $alt,'',"LT",0,"C",1);
$pdf->cell(10, $alt,'',"LT",0,"C",1);
$pdf->cell(60, $alt,'REALIZADO',"LBT",0,"C",1);
$pdf->cell(60, $alt,'PLANEJADO',"LBT",0,"C",1);
$pdf->cell(60, $alt,'VARIACAO (Planejado - Realizado)',"LBT",0,"C",1);
$pdf->cell(20, $alt,'PROJETADO',"LTR",1,"C",1);
$pdf->cell(10, $alt,'COD',"LB",0,"C",1);
$pdf->cell(60, $alt,'COMPONENTE/ATIVIDADE',"LB",0,"C",1);
$pdf->cell(10, $alt,'FONTE',"LB",0,"C",1);
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


foreach ($aLinhaRelatorio as $oLinhaRelatorio) {
  
  if ($pdf->GetY() > $pdf->h - 25) {
    
    $pdf->AddPage();
    $pdf->cell(10, $alt,'',"LT",0,"C",1);
    $pdf->cell(60, $alt,'',"LT",0,"C",1);
    $pdf->cell(10, $alt,'',"LT",0,"C",1);
    $pdf->cell(60, $alt,'REALIZADO',"LBT",0,"C",1);
    $pdf->cell(60, $alt,'PLANEJADO',"LBT",0,"C",1);
    $pdf->cell(60, $alt,'VARIACAO (Planejado - Realizado)',"LBT",0,"C",1);
    $pdf->cell(20, $alt,'PROJETADO',"LTR",1,"C",1);
    $pdf->cell(10, $alt,'COD',"LB",0,"C",1);
    $pdf->cell(60, $alt,'COMPONENTE/ATIVIDADE',"LB",0,"C",1);
    $pdf->cell(10, $alt,'FONTE',"LB",0,"C",1);
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
    
    
  }
  $pdf->cell(10, $alt, "","LT",0,"L",1);
  $pdf->cell(60, $alt, "","LT",0,"L",1);
  $pdf->cell(10, $alt, "Total","LBT",0,"L",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_realizado_trim_atual+$oLinhaRelatorio->bird_realizado_trim_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_realizado_ano_atual+$oLinhaRelatorio->bird_realizado_ano_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_realizado_acumulado+$oLinhaRelatorio->bird_realizado_acumulado,"f"),1,0,"R",1);
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_trim_atual+$oLinhaRelatorio->planejado_trim_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_ano_atual+$oLinhaRelatorio->planejado_ano_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_acumulado+$oLinhaRelatorio->planejado_acumulado,"f"),1,0,"R",1);
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_variacao_trim_atual+$oLinhaRelatorio->bird_variacao_trim_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_variacao_ano_atual+$oLinhaRelatorio->bird_variacao_ano_atual,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_variacao_acumulado+$oLinhaRelatorio->bird_variacao_acumulado,"f"),1,0,"R",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_original+$oLinhaRelatorio->planejado_original,"f"),1,1,"R",1);
  $pdf->cell(10, $alt, $oLinhaRelatorio->o87_pactoprograma,"L",0,"L",1);
  $pdf->cell(60, $alt, $oLinhaRelatorio->o54_descr,"L",0,"L",1);
  $pdf->cell(10, $alt, "CP","LTB",0,"L",1);
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_realizado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_realizado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_realizado_acumulado,"f"),1,0,"R");
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_acumulado,"f"),1,0,"R");
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_variacao_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_variacao_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_variacao_acumulado,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp_planejado_original,"f"),1,1,"R");
  $pdf->cell(10, $alt, "","L",0,"L",1);
  $pdf->cell(60, $alt, "","L",0,"L",1);
  $pdf->cell(10, $alt, "CP 100","LTB",0,"L",1);
  $pdf->cell(20, $alt, db_formatar(0,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar(0,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar(0,"f"),1,0,"R");
    
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp100_planejado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp100_planejado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp100_planejado_acumulado,"f"),1,0,"R");

  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp100_variacao_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp100_variacao_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->cp100_variacao_acumulado,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar(0,"f"),1,1,"R");
  $pdf->cell(10, $alt, "","LB",0,"L",1);
  $pdf->cell(60, $alt,"","LB",0,"L",1);
  $pdf->cell(10, $alt, "BIRD","LTB",0,"L",1);
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->bird_realizado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->bird_realizado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->bird_realizado_acumulado,"f"),1,0,"R");
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->planejado_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->planejado_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->planejado_acumulado,"f"),1,0,"R");
  
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->bird_variacao_trim_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->bird_variacao_ano_atual,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->bird_variacao_acumulado,"f"),1,0,"R");
  $pdf->cell(20, $alt, db_formatar($oLinhaRelatorio->planejado_original,"f"),1,1,"R");
  
  foreach ($oLinhaRelatorio->aAtividades as $oAtividade) {
  
    if ($pdf->GetY() > $pdf->h - 25) {
    
      $pdf->AddPage();
      $pdf->cell(10, $alt,'',"LT",0,"C",1);
      $pdf->cell(60, $alt,'',"LT",0,"C",1);
      $pdf->cell(10, $alt,'',"LT",0,"C",1);
      $pdf->cell(60, $alt,'REALIZADO',"LBT",0,"C",1);
      $pdf->cell(60, $alt,'PLANEJADO',"LBT",0,"C",1);
      $pdf->cell(60, $alt,'VARIACAO (Planejado - Realizado)',"LBT",0,"C",1);
      $pdf->cell(20, $alt,'PROJETADO',"LTR",1,"C",1);
      $pdf->cell(10, $alt,'COD',"LB",0,"C",1);
      $pdf->cell(60, $alt,'COMPONENTE/ATIVIDADE',"LB",0,"C",1);
      $pdf->cell(10, $alt,'FONTE',"LB",0,"C",1);
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
      
      
    }
    $pdf->cell(10, $alt, "","LT",0,"L");
    $pdf->cell(60, $alt, "","LT",0,"L");
    $pdf->cell(10, $alt, "Total","LBT",0,"L");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_realizado_trim_atual+$oAtividade->bird_realizado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_realizado_ano_atual+$oAtividade->bird_realizado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_realizado_acumulado+$oAtividade->bird_realizado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_trim_atual+$oAtividade->planejado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_ano_atual+$oAtividade->planejado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_acumulado+$oAtividade->planejado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_variacao_trim_atual+$oAtividade->bird_variacao_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_variacao_ano_atual+$oAtividade->bird_variacao_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_variacao_acumulado+$oAtividade->bird_variacao_acumulado,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_original+$oAtividade->planejado_original,"f"),1,1,"R");
    $pdf->cell(10, $alt, substr($oAtividade->o55_projativ,0,38),"L",0,"L");
    $pdf->cell(60, $alt, substr($oAtividade->o55_descr,0,38),"L",0,"L");
    $pdf->cell(10, $alt, "CP","LTB",0,"L");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_realizado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_realizado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_realizado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_variacao_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_variacao_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_variacao_acumulado,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp_planejado_original,"f"),1,1,"R");
    $pdf->cell(10, $alt, "","L",0,"L");
    $pdf->cell(60, $alt, "","L",0,"L");
    $pdf->cell(10, $alt, "CP 100","LTB",0,"L");
    
    $pdf->cell(20, $alt, db_formatar(0,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar(0,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar(0,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp100_planejado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp100_planejado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp100_planejado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp100_variacao_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp100_variacao_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->cp100_variacao_acumulado,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar(0,"f"),1,1,"R");
    $pdf->cell(10, $alt, "","LB",0,"L");
    $pdf->cell(60, $alt,"","LB",0,"L");
    $pdf->cell(10, $alt, "BIRD","LTB",0,"L");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->bird_realizado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->bird_realizado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->bird_realizado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->planejado_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->planejado_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->planejado_acumulado,"f"),1,0,"R");
    
    $pdf->cell(20, $alt, db_formatar($oAtividade->bird_variacao_trim_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->bird_variacao_ano_atual,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->bird_variacao_acumulado,"f"),1,0,"R");
    $pdf->cell(20, $alt, db_formatar($oAtividade->planejado_original,"f"),1,1,"R");  
    
  }
  
}

$pdf->cell(70, $alt*4, "Total dos Gastos do Programa","LBT",0,"L",1);
$pdf->cell(10, $alt, "Total","L",0,"L");

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
$pdf->setx(80);
$pdf->cell(10, $alt, "CP","LTB",0,"L");

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
$pdf->setx(80);
$pdf->cell(10, $alt, "CP 100","LTB",0,"L");
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["realizado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["realizado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["realizado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["planejado_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["planejado_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["planejado_acumulado"],"f"),1,0,"R",0);

$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["variacao_trim_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["variacao_ano_atual"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["variacao_acumulado"],"f"),1,0,"R",0);
$pdf->cell(20, $alt, db_formatar($aTotalizador["CP100"]["planejado_original"],"f"),1,1,"R",0);
$pdf->setx(80);
$pdf->cell(10, $alt, "BIRD","LBT",0,"L");

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

$pdf->Output();

?>