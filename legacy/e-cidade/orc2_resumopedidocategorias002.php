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
$pdf->setfont('arial','b',7);

$alt       = 4;
$pagina    = 1;
$iConvenio = 8;
$iAnoUsu   = db_getsession("DB_anousu"); 

$head1     = "Resumo do Pedido Por Categorias de Gasto";
$iMesAtual = date("m",db_getsession("DB_datausu"));

switch ($oGet->trimestre) {
  
  case 1:
    
    $sPeriodo = "Período de Janeiro a Março de {$iAnoUsu}";
    $iMesIni     = 1;
    $iMesFim     = 3;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 12;
    $iAnoAnt     = db_getsession("DB_anousu") - 1;
    break;
    
  case 2:
    
    $sPeriodo    = "Período de Abril a Junho de {$iAnoUsu}";
    $iMesIni     = 4;
    $iMesFim     = 6;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 3;
    $iAnoAnt     = db_getsession("DB_anousu");
    break;

  case 3:
    
    $sPeriodo = "Período de Julho a Setembro de {$iAnoUsu}";
    $iMesIni     = 7;
    $iMesFim     = 9;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 6;
    $iAnoAnt     = db_getsession("DB_anousu");
    break;

  case 4:
    
    $sPeriodo = "Período de Outubro a Dezembro de {$iAnoUsu}";
    $iMesIni     = 10;
    $iMesFim     = 12;
    $iMesIniAnt  = 1;
    $iMesFimAnt  = 9;
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
$sSqlDados     = "select o31_sequencial, o31_descricao,";
$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "          where o103_anousu = {$iAnoUsu}";
$sSqlDados    .= "            and o103_mesusu between {$iMesIni} and {$iMesFim}";
$sSqlDados    .= "            and o87_pactoplano = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 2";
$sSqlDados    .= "          ) as valor_bird, ";

$sSqlDados    .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
$sSqlDados    .= "          from  pactovalorsaldo";
$sSqlDados    .= "                inner join pactovalor on o103_pactovalor = o87_sequencial";
$sSqlDados    .= "            and o87_pactoplano           = {$oGet->iPlano}";
$sSqlDados    .= "            and o87_categoriapacto       = o31_sequencial";
$sSqlDados    .= "            and o103_pactovalorsaldotipo = 1";
$sSqlDados    .= "          ) as planejado_acumulado ";


$sSqlDados    .= " from   categoriapacto";
$sSqlDados    .= " where o31_tipopacto = {$oConvenio->o16_tipopacto} order by o31_sequencial";
$rsDados       = db_query($sSqlDados);
$iTotalRows    = pg_num_rows($rsDados);
$aLinhaRelatorio = db_utils::getColectionByRecord($rsDados);

$iAlt = 4;
$pdf->AddPage();
$pdf->cell(20, $iAlt, "", "TRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "", "TRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "", "TRL", 0 , "C" ,1);
$pdf->cell(120, $iAlt, "Valores Documentados em USD(Dolar)", 1, 0 , "C" ,1);
$pdf->cell(40, $iAlt, "", "TRL", 1 , "C" ,1);

$pdf->cell(20, $iAlt, "Categoria", "BRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "Valor Original Alocado", "BRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "Acumulado Anterior", "BRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "(1)SOE Sem Justificativa", "TBRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "(2)SOE Com Justificativa", "TBRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "Total do Saque Pedido", "TBRL", 0 , "C" ,1);
$pdf->cell(40, $iAlt, "Saldo por Categoria", "TBRL", 1 , "C" ,1);
$nTotalAlocado   = 0;
$nTotalPlanejado = 0;
$nTotalAcumulado = 0;
$pdf->setfont('arial','',7);
foreach ($aLinhaRelatorio as $oLinha) {
  
  $pdf->cell(20, $iAlt, $oLinha->o31_descricao, 1, 0 , "L" );
  $pdf->cell(40, $iAlt, trim(db_formatar(($oLinha->valor_bird*0.89),"f")), "TRL", 0 , "R" );
  $pdf->cell(40, $iAlt, trim(db_formatar($oLinha->planejado_acumulado,"f")), "TRL", 0 , "R");
  $pdf->cell(40, $iAlt, "", "TRL", 0 , "R");
  $pdf->cell(40, $iAlt, "", "TRL", 0 , "R");
  $pdf->cell(40, $iAlt, trim(db_formatar(($oLinha->valor_bird*0.89),"f")), "TRL", 0 , "R");
  $pdf->cell(40, $iAlt, trim(db_formatar(($oLinha->valor_bird*0.89)+$oLinha->planejado_acumulado,"f")), "TRL", 1, "R");
  
  $nTotalAlocado   += ($oLinha->valor_bird*0.89);
  $nTotalPlanejado += ($oLinha->planejado_acumulado);
  $nTotalAcumulado += ($oLinha->planejado_acumulado)+($oLinha->valor_bird*0.89);
}
$pdf->cell(20, $iAlt, "", 1, 0 , "L" );
$pdf->cell(40, $iAlt, "", "TRL", 0 , "R" );
$pdf->cell(40, $iAlt, "", "TRL", 0 , "R");
$pdf->cell(40, $iAlt, "", "TRL", 0 , "R");
$pdf->cell(40, $iAlt, "", "TRL", 0 , "R");
$pdf->cell(40, $iAlt, "", "TRL", 0 , "R");
$pdf->cell(40, $iAlt, "", "TRL", 1 , "R");

$pdf->setfont('arial','b',7);
$pdf->cell(20, $iAlt, "", 1, 0 , "L" );
$pdf->cell(40, $iAlt, trim(db_formatar($nTotalAlocado,"f")), 1, 0 , "R" );
$pdf->cell(40, $iAlt, trim(db_formatar($nTotalPlanejado,"f")), 1, 0 , "R");
$pdf->cell(40, $iAlt, "", 1, 0 , "R");
$pdf->cell(40, $iAlt, "", 1, 0 , "R");
$pdf->cell(40, $iAlt, trim(db_formatar($nTotalAlocado,"f")), 1, 0 , "R" );
$pdf->cell(40, $iAlt, trim(db_formatar($nTotalAcumulado,"f")), 1, 0 , "R");
$pdf->Output();

?>