<?php
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_liborcamento.php");
require_once("fpdf151/assinatura.php");
require_once("classes/db_orcparamrel_classe.php");
require_once("model/relatorioContabil.model.php");
require_once("model/linhaRelatorioContabil.model.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_libtxt.php");
require_once("dbforms/db_funcoes.php");
$oGet = db_utils::postMemory($_GET);


$pdf = new PDF("P");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->SetAutoPageBreak(false,1);
$pdf->setfont('arial','b',7);
$oRelatorioContabil = new relatorioContabil(100, false);

$alt       = 4;
$pagina    = 1;
$iConvenio = 8;
$iFonte    = "Arial";
$iAnoUsu   = db_getsession("DB_anousu");
$head1     = "Relatório IFR 1C";
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

/**
 * Descobrimos qual o ultimo dia do mes anterior
 * para poder informar qual o fim do trimestre anterior
 * Este dado serve somente para informação
 */
$iUltimoDiaTrimestreAnterior  = mktime(0, 0, 0, $iMesIni, 0, $iAnoUsu);
$dtUltimoDiaTrimestreAnterior = date("d/m/Y", $iUltimoDiaTrimestreAnterior);

$iCodigoRelatorio            = 100;
$oLinhaDepositoConta         = new linhaRelatorioContabil($iCodigoRelatorio, 1);
$oParametrosDeposito         = $oLinhaDepositoConta->getParametros($iAnoUsu);
$oLinhaContasRendimentos     = new linhaRelatorioContabil($iCodigoRelatorio, 2);
$oParametroRendimentos       = $oLinhaContasRendimentos->getParametros($iAnoUsu);
$iAnoAnterior                = db_getsession("DB_anousu")-1;
$oDaoConvenio                = db_utils::getDao("pactoplano");
$sSqlConvenio                = $oDaoConvenio->sql_query($oGet->iPlano,"*");
$rsConvenio                  = $oDaoConvenio->sql_record($sSqlConvenio);
$oConvenio                   = db_utils::fieldsMemory($rsConvenio, 0);

if ($iAnoUsu == 2013){
   $iContaBIRD = 21443;
}else{
   $iContaBIRD = $oConvenio->o16_saltes;
}


$sWhere          = " o70_instit = ".db_getsession("DB_instit");
$sWhere         .= " and o70_codigo = {$oConvenio->o16_orctiporec}";
$iDataInicial    = "{$iAnoUsu}-{$iMesIni}-01";
$iUltimoDiaMes   = cal_days_in_month(CAL_GREGORIAN,$iMesFim,$iAnoUsu);
$datausu         = "{$iAnoUsu}-{$iMesFim}-$iUltimoDiaMes";

$rsReceitaSaldo = db_receitasaldo(11, 1, 3,true, $sWhere,$iAnoUsu, $iDataInicial, $datausu);
$nTotalPeriodo  = 0;
$iTotalLinhas   = pg_num_rows($rsReceitaSaldo);
for ($iRec = 0; $iRec < $iTotalLinhas; $iRec++) {

  $oLinha = db_utils::fieldsMemory($rsReceitaSaldo, $iRec);
  foreach ($oParametrosDeposito->contas as $oEstrutural) {

    $oVerificacao  = $oLinhaDepositoConta->match($oEstrutural , $oParametrosDeposito->orcamento, $oLinha, 1);
    if ($oVerificacao->match) {

      if ($oVerificacao->exclusao) {
        $oLinha->saldo_arrecadado   *=-1;
      }
      $nTotalPeriodo += $oLinha->saldo_arrecadado;
    }
  }
}
$head2          = "Acordo de Empréstimo nº";
$head2          = "Acordo de Empréstimo nº {$oConvenio->o16_convenio}";
$head3          = "Conta Nº {$iContaBIRD}";
$sSqlNomeBanco  = "select db90_descr ";
$sSqlNomeBanco .= "  from conplanoreduz";
$sSqlNomeBanco .= "       inner join conplano      on c61_codcon = c60_codcon";
$sSqlNomeBanco .= "                               and c61_anousu = c60_anousu";
$sSqlNomeBanco .= "       inner join conplanoconta on c63_codcon = c60_codcon";
$sSqlNomeBanco .= "                               and c63_anousu = c60_anousu";
$sSqlNomeBanco .= "       inner join db_bancos     on c63_banco  = db90_codban";
$sSqlNomeBanco .= " where c61_reduz  = {$iContaBIRD} ";
$sSqlNomeBanco .= "   and c61_anousu = {$iAnoUsu}";
$rsBancos       = db_query($sSqlNomeBanco);
$oBanco         = db_utils::fieldsMemory($rsBancos, 0);
if ($oBanco->db90_descr != "") {
  $head4 = "Banco:{$oBanco->db90_descr} ";
}
$sSqlSaldoOrigem  = "        select (select ";
$sSqlSaldoOrigem .= "                       round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "                  from conlancam ";
$sSqlSaldoOrigem .= "                 inner join conlancamrec on c70_codlan = c74_codlan";
$sSqlSaldoOrigem .= "                 inner join conlancamdoc on c71_codlan = c70_codlan";
$sSqlSaldoOrigem .= "                 inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "                 inner join orcreceita   on o70_codrec = c74_codrec";
$sSqlSaldoOrigem .= "                                        and o70_anousu = c74_anousu";
$sSqlSaldoOrigem .= "                 where extract(month from  c70_data) between {$iMesIniAnt} and {$iMesFimAnt} ";
$sSqlSaldoOrigem .= "                   and c70_anousu  = {$iAnoAnt}";
$sSqlSaldoOrigem .= "                   and o70_codfon in (14153,16451) ";
$sSqlSaldoOrigem .= "                   and c53_tipo = 100";
$sSqlSaldoOrigem .= "                   and c70_data >= '2009-01-01'";
$sSqlSaldoOrigem .= "                 ) as saldo_inicial_anterior,";
$sSqlSaldoOrigem .= "               (select ";
$sSqlSaldoOrigem .= "                       round(sum(c70_valor),2)";
$sSqlSaldoOrigem .= "                  from conlancam ";
$sSqlSaldoOrigem .= "                 inner join conlancamrec on c70_codlan = c74_codlan ";
$sSqlSaldoOrigem .= "                 inner join conlancamdoc on c71_codlan = c70_codlan";
$sSqlSaldoOrigem .= "                 inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlSaldoOrigem .= "                 inner join orcreceita   on o70_codrec = c74_codrec ";
$sSqlSaldoOrigem .= "                                        and o70_anousu = c74_anousu ";
$sSqlSaldoOrigem .= "                 where extract(month from  c70_data) between 1 and 12 ";
$sSqlSaldoOrigem .= "                   and c70_anousu  <= {$iAnoAnterior}";
$sSqlSaldoOrigem .= "                   and o70_codfon in (14153,16451) ";
$sSqlSaldoOrigem .= "                   and c53_tipo = 100";
$sSqlSaldoOrigem .= "                   and c70_data >= '2009-01-01'";
$sSqlSaldoOrigem .= "                 ) as saldo_inicial_ano_anterior;";

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


$oConvenio->o16_saldoaberturacp = $oSaldoAnterior->cp_realizado_anterior - $oSaldoAnterior->cp_realizado_anterior;

$oConvenio->o16_saldoabertura   = ($oSaldoInicialAnoAnteriorBird-$oSaldoAnterior->bird_realizado_ano_anterior);
if ($oGet->trimestre > 1  && $oGet->trimestre <= 4) {
  $oConvenio->o16_saldoabertura   = ($oSaldoInicialAnteriorBird+$oSaldoInicialAnoAnteriorBird) -
                                    ($oSaldoAnterior->bird_realizado_anterior+$oSaldoAnterior->bird_realizado_ano_anterior);
}
$nValorFundos   = $nTotalPeriodo+$oConvenio->o16_saldoabertura;

/*
 * valores de pagamento de empenho
 */
$sSqlValoresEmpenho  = "select sum(c70_valor) as valorpago";
$sSqlValoresEmpenho .= "  from conlancampag";
$sSqlValoresEmpenho .= "       inner join conlancamemp on c75_codlan = c82_codlan";
$sSqlValoresEmpenho .= "       inner join conlancam    on c75_codlan = c70_codlan ";
$sSqlValoresEmpenho .= "       inner join conlancamdoc on c71_codlan = c70_codlan ";
$sSqlValoresEmpenho .= "       inner join conhistdoc   on c71_coddoc = c53_coddoc  ";
$sSqlValoresEmpenho .= " where c82_reduz  = {$iContaBIRD} ";
$sSqlValoresEmpenho .= "   and c70_data between '{$iDataInicial}' and '{$datausu}'";
$sSqlValoresEmpenho .= "   and c53_tipo = 30";
$rsValoresEmpenhos   = db_query($sSqlValoresEmpenho);
$nValorEmpenhos      = db_utils::fieldsMemory($rsValoresEmpenhos, 0)->valorpago;
$nSaldoContaDesignada = $nValorFundos - $nValorEmpenhos;

/**
 * Calculamos todas os arrecadacoes da receita
 */
$sSqlArrecadacoes  = "SELECT o57_fonte, o70_codigo,";
$sSqlArrecadacoes .= "       case when (extract(month from c70_data) in (1,2,3)) then '1'";
$sSqlArrecadacoes .= "            when (extract(month from c70_data) in (4,5,6)) then '2'";
$sSqlArrecadacoes .= "            when (extract(month from c70_data) in (7,8,9)) then '3'";
$sSqlArrecadacoes .= "            when (extract(month from c70_data) in (10,11,12)) then '4'";
$sSqlArrecadacoes .= "       end as trimestre,";
$sSqlArrecadacoes .= "       c70_anousu,";
$sSqlArrecadacoes .= "       sum (case when c53_coddoc = 101 then c70_valor*-1 else c70_valor end )as valor";
$sSqlArrecadacoes .= "  from conlancam";
$sSqlArrecadacoes .= "        inner join conlancamrec on c74_Codlan = c70_codlan";
$sSqlArrecadacoes .= "        inner join orcreceita   on c74_codrec = o70_codrec";
$sSqlArrecadacoes .= "                               and c74_anousu = o70_anousu";
$sSqlArrecadacoes .= "        inner join orcfontes    on o70_codfon = o57_codfon";
$sSqlArrecadacoes .= "                               and o57_anousu = o70_anousu";
$sSqlArrecadacoes .= "        inner join conlancamdoc on c70_codlan = c71_codlan";
$sSqlArrecadacoes .= "        inner join conhistdoc   on c71_coddoc = c53_coddoc";
$sSqlArrecadacoes .= "  where o70_codigo = {$oConvenio->o16_orctiporec}";
$sSqlArrecadacoes .= "    and c70_data <= '{$datausu}'";
$sSqlArrecadacoes .= "    and c53_tipo in(100, 101)";
$sSqlArrecadacoes .= "  group by 1,2,3,4 order by 4,3,1 ";
$rsArrecadoes        = db_query($sSqlArrecadacoes);
$aArrecadacoes       = array();
for ($iRec = 0; $iRec < pg_num_rows($rsArrecadoes); $iRec++) {

  $oLinha = db_utils::fieldsMemory($rsArrecadoes, $iRec);
  foreach ($oParametroRendimentos->contas as $oEstrutural) {

    $oVerificacao  = $oLinhaContasRendimentos->match($oEstrutural , $oParametroRendimentos->orcamento, $oLinha, 1);
    if ($oVerificacao->match) {

      if ($oVerificacao->exclusao) {
        $oLinha->valor   *=-1;
      }
      if (!isset($aArrecadacoes[$oLinha->c70_anousu.$oLinha->trimestre])) {

        $oTrimestre = new stdClass();
        $oTrimestre->trimestre  = $oLinha->trimestre;
        $oTrimestre->c70_anousu = $oLinha->c70_anousu;
        $oTrimestre->valor      = $oLinha->valor;

        $aArrecadacoes[$oLinha->c70_anousu.$oLinha->trimestre] = $oTrimestre;
      } else {
        $aArrecadacoes[$oLinha->c70_anousu.$oLinha->trimestre]->valor += $oLinha->valor;
      }

    }
  }
}
$head5  = "{$oGet->trimestre}º Trimestre - Acumulado do Exercicio de {$iAnoUsu}";
$pdf->AddPage();
$pdf->RoundedRect(8, $pdf->getY()-2,194, 254, 2, '');
$pdf->ln(4);
$pdf->SetFont($iFonte, "B", 12);
$pdf->cell(150, $alt, "I - FUNDO RECEBIDO");
$pdf->cell(30, $alt, "R$", "B",1, "C");
$pdf->SetFont($iFonte, "", 10);
$pdf->SetX(20);
$pdf->cell(140, 6, "1 - Saldo em {$dtUltimoDiaTrimestreAnterior}");
$pdf->cell(30, 6, db_formatar($oConvenio->o16_saldoabertura,"f"), "",1, "R");
$pdf->SetX(20);
$pdf->cell(140, 6, "2 - Reintegracao do Banco Mundial");
$pdf->cell(30, 6, "", "",1, "R");
$pdf->SetX(40);
$pdf->cell(120, 6, "Depósito na Conta Designada");
$pdf->cell(30, 6, db_formatar($nTotalPeriodo,"f"), "",1, "R");
$pdf->SetX(40);
$pdf->cell(120, 6, "Reposição par a Conta Designada");
$pdf->cell(30, 6, "", "B",1, "R");
$pdf->SetX(40);
$pdf->cell(120, 6, "");
$pdf->cell(30, 6, "", "",1, "R");
$pdf->SetX(20);
$pdf->cell(140, 6, "3 - Fundos Disponíveis em ");
$pdf->SetFont($iFonte, "B", 10);
$pdf->cell(30, 6, db_formatar($nValorFundos, "f"), "B",1, "R");

$pdf->ln(10);
$pdf->SetFont($iFonte, "B", 12);
$pdf->cell(150, $alt, "II - MENOS");
$pdf->cell(30, $alt, "", "",1, "C");
$pdf->SetFont($iFonte, "", 10);
$pdf->SetX(20);
$pdf->cell(140, 6, "4 - Inversões");
$pdf->cell(30, 6, "", "",1, "R");
$pdf->SetX(20);
$pdf->cell(140, 6, "5 - Pagamento de Bens e Serviços");
$pdf->cell(30, 6, db_formatar($nValorEmpenhos, "f"), "",1, "R");
$pdf->SetX(20);
$pdf->cell(140, 6, "6 - Aplicações");
$pdf->cell(30, 6, "", "B",1, "R");
$pdf->SetX(20);
$pdf->cell(140, 6, "");
$pdf->SetFont($iFonte, "b", 10);
$pdf->cell(30, 6, db_formatar($nValorEmpenhos, "f"), "",1, "R");

$pdf->ln(10);
$pdf->SetFont($iFonte, "B", 12);
$pdf->cell(150, $alt, "III - SALDO DA CONTA DESIGNADA");
$pdf->cell(30, $alt, "", "",1, "C");
$pdf->SetFont($iFonte, "", 10);
$pdf->SetX(20);
$pdf->cell(140, 6, "7 - Principal");
$pdf->SetFont($iFonte, "b", 10);
$pdf->cell(30, 6, db_formatar($nSaldoContaDesignada, "f"), "T",1, "R");
$pdf->SetX(20);
$pdf->SetFont($iFonte, "b", 10);
$pdf->ln(5);
$pdf->SetFillColor(245);
$pdf->setx(20);
$pdf->cell(170, 5, 'Rendimentos de Aplicações Financeiras', "TB", 1, "C", 1);
$pdf->setx(20);
$pdf->cell(20, 5, 'Período', "TBR", 0, "C", 1);
$pdf->cell(100, 5, 'Descrição', "TBRL", 0, "C", 1);
$pdf->cell(50, 5, 'Valor', "TBL", 1, "C", 1);
$nTotalArreacado = 0;
$pdf->SetFont($iFonte, "", 8);
foreach ($aArrecadacoes as $oArrecadacao) {

  $pdf->setx(20);
  $pdf->cell(20, 5, "{$oArrecadacao->trimestre}º Trimestre", "TBR", 0, "C");
  $pdf->cell(100, 5, "Rendimentos {$oArrecadacao->trimestre}º Trimestre {$oArrecadacao->c70_anousu}", "TBRL", 0, "L");
  $pdf->cell(50, 5, db_formatar($oArrecadacao->valor, "f"), "TBL", 1, "R");
  $nTotalArreacado += $oArrecadacao->valor;
}
$pdf->SetFont($iFonte, "b", 10);
$pdf->setx(20);
$pdf->cell(120, 5, "Total:", "TBR", 0, "R", 1);
$pdf->cell(50, 5, db_formatar($nTotalArreacado, "f"), "TBL", 1, "C", 1);
$pdf->ln(3);
$oRelatorioContabil->getNotaExplicativa($pdf, $iNota);
$pdf->Output();