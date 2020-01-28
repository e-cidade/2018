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

/**
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
$oGet = db_utils::postMemory($_GET);

$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetAutoPageBreak(false, 0); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);

$alt       = 4;
$pagina    = 1;
$iConvenio = 8;
$iAnoUsu   = db_getsession("DB_anousu"); 

$head1     = "Relatório IBRD 7338-BR - Folha Resumo - SS";
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


$head3         = $sPeriodo;
$oDaoConvenio  = db_utils::getDao("pactoplano");
$sSqlConvenio  = $oDaoConvenio->sql_query($oGet->iPlano,"*");
$rsConvenio    = $oDaoConvenio->sql_record($sSqlConvenio);
$oConvenio     = db_utils::fieldsMemory($rsConvenio, 0);
$head4         = "Número do Convênio: {$oConvenio->o16_convenio}";
$iAlt          = 4;
$sSqlDados     = "select z01_nome, ";
$sSqlDados    .= "       z01_numcgm, ";
$sSqlDados    .= "       e69_numero, ";
$sSqlDados    .= "       e60_vlremp, ";
$sSqlDados    .= "       o103_valor, ";
$sSqlDados    .= "       (select max(c80_data) ";
$sSqlDados    .= "          from conlancamord ";
$sSqlDados    .= "          where c80_codord = o110_pagordem ";
$sSqlDados    .= "            and extract(month from c80_data) = o103_mesusu ";
$sSqlDados    .= "            and extract(year  from c80_data) = o103_anousu ";
$sSqlDados    .= "         ) as datapag, ";
$sSqlDados    .= "         e50_obs, ";
$sSqlDados    .= "         pc50_descr, ";
$sSqlDados    .= "         e60_numerol ";
$sSqlDados    .= "  from pactovalorsaldo  ";
$sSqlDados    .= "       inner join pactovalorsaldopagordem on o110_pactovalorsaldo = o103_sequencial ";
$sSqlDados    .= "       inner join pagordem                on e50_codord           = o110_pagordem ";
$sSqlDados    .= "       inner join empempenho              on e50_numemp           = e60_numemp ";
$sSqlDados    .= "       inner join cgm                     on z01_numcgm           = e60_numcgm  ";
$sSqlDados    .= "       inner join pagordemnota            on e71_codord           = e50_codord ";
$sSqlDados    .= "       inner join empnota                 on e71_codnota          = e69_codnota ";
$sSqlDados    .= "       inner join pctipocompra            on pc50_codcom          = e60_codcom ";
$sSqlDados    .= " where o103_mesusu between {$iMesIni} and {$iMesFim} ";
$sSqlDados    .= "  and o103_anousu  = 2009 ";     
$sSqlDados    .= " order by e69_numero ";
$rsDados       = db_query($sSqlDados);
$aLinhasRelatorio = array();
$aDadosConsulta   = db_utils::getColectionByRecord($rsDados);
$iIndice          = 0;
for ($i = 0; $i < count($aDadosConsulta) ; $i++) {
  
  if (isset($aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm])) {

    if (isset($aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm]->notas["{$aDadosConsulta[$i]->e69_numero}"])) {
      $aDadosConsulta[$i]->o103_valor +=  $aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm]->notas["{$aDadosConsulta[$i]->e69_numero}"]->o103_valor;  
      $aDadosConsulta[$i]->e60_vlremp +=  $aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm]->notas["{$aDadosConsulta[$i]->e69_numero}"]->e60_vlremp;  
    }
    $aDadosConsulta[$i]->valorfinanciado = 89;
    $aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm]->notas["{$aDadosConsulta[$i]->e69_numero}"]  = $aDadosConsulta[$i];
    
  } else {
    
    $aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm]->z01_nome =  $aDadosConsulta[$i]->z01_nome;
    $aDadosConsulta[$i]->valorfinanciado = 89;
    $aLinhasRelatorio[$aDadosConsulta[$i]->z01_numcgm]->notas["{$aDadosConsulta[$i]->e69_numero}"]   = $aDadosConsulta[$i];
    
  }
  
}
//echo "<pre>";
// print_r($aLinhasRelatorio);
//echo "</pre>";
$pdf->AddPage();
$pdf->cell(8, $iAlt, ''                         , 1, 0, "C", 1);
$pdf->cell(70, $iAlt, 'Fornecedor/Consultor'    , 1, 0, "C", 1);
$pdf->cell(25, $iAlt, 'Nº. do Contrato'         , 1, 0, "C", 1);
$pdf->cell(25, $iAlt, 'Nº. da Fatura'           , 1, 0, "C", 1);
$pdf->cell(20, $iAlt, 'Vlr. Contrato'           , 1, 0, "C", 1);
$pdf->cell(20, $iAlt, 'Vlr Pago'                , 1, 0, "C", 1);
$pdf->cell(10, $iAlt, '% BIRD'                  , 1, 0, "C", 1);
$pdf->cell(25, $iAlt, 'Vlr Fin. BIRD (6x7)'     , 1, 0, "C", 1);
$pdf->cell(25, $iAlt, 'Data Pagamento'          , 1, 0, "C", 1);
$pdf->cell(55, $iAlt, 'Obs'                     , 1, 1, "C", 1);
$i = 1;
$iInicio         = $pdf->GetY();
$nValorPagoTotal = 0;
$nValorBirdTotal = 0;
foreach ($aLinhasRelatorio as $oLinhaFornecedor) {
  
  $pdf->setfont('arial','',6);
  $pdf->cell(8, $iAlt, $i                          , "TLR", 0, "R");
  $pdf->cell(70, $iAlt,$oLinhaFornecedor->z01_nome , "TLR", 0, "L");
  $nSubTotalBird = 0; 
  $nSubTotalPago = 0; 
  foreach($oLinhaFornecedor->notas as $oNota) {
    
    $pdf->Setx(88);
    $pdf->cell(25, $iAlt, $oNota->e60_numerol                                               , 1, 0, "C");
    $pdf->cell(25, $iAlt, $oNota->e69_numero                                                , 1, 0, "C");
    $pdf->cell(20, $iAlt, db_formatar($oNota->e60_vlremp,'f')                               , 1, 0, "R");
    $pdf->cell(20, $iAlt, db_formatar($oNota->o103_valor,"f")                              , 1, 0, "R");
    $pdf->cell(10, $iAlt, db_formatar($oNota->valorfinanciado,'f')                          , 1, 0, "R");
    $pdf->cell(25, $iAlt, db_formatar($oNota->o103_valor*($oNota->valorfinanciado/100),'f') , 1, 0, "R");
    $pdf->cell(25, $iAlt, db_formatar($oNota->datapag,"d")                                 , 1, 0, "C");
    $pdf->cell(55, $iAlt, $oNota->e50_obs                                                  , 1, 1, "L");
    $nSubTotalPago   += $oNota->o103_valor;
    $nSubTotalBird   += round($oNota->o103_valor*($oNota->valorfinanciado/100),2);
    
  }
  $nValorPagoTotal += $nSubTotalPago;
  $nValorBirdTotal += $nSubTotalBird;
  $pdf->setfont('arial','b',6);
  $pdf->cell(8, $iAlt, '' , "TLR", 0, "R");
  $pdf->cell(70, $iAlt,'' , "TLR", 0, "L");
  $pdf->cell(25, $iAlt, '', 1, 0, "C");
  $pdf->cell(25, $iAlt, '', 1, 0, "C");
  $pdf->cell(20, $iAlt, 'SubTotal', 1, 0, "R");
  $pdf->cell(20, $iAlt, db_formatar($nSubTotalPago,"f") , 1, 0, "R");
  $pdf->cell(10, $iAlt, '89%', 1, 0, "R");
  $pdf->cell(25, $iAlt, db_formatar($nSubTotalBird, 'f') , 1, 0, "R");
  $pdf->cell(25, $iAlt,''                                , 1, 0, "C");
  $pdf->cell(55, $iAlt,'', 1, 1, "C");
  $i++;
}
$pdf->setfont('arial','',6);
$pdf->cell(8, $iAlt, '' , "TLR", 0, "R");
$pdf->cell(70, $iAlt,'' , "TLR", 0, "L");
$pdf->cell(25, $iAlt, '', 1, 0, "C");
$pdf->cell(25, $iAlt, '', 1, 0, "C");
$pdf->cell(20, $iAlt, 'Total', 1, 0, "R");
$pdf->cell(20, $iAlt, db_formatar($nValorPagoTotal,"f") , 1, 0, "R");
$pdf->cell(10, $iAlt, '89%', 1, 0, "R");
$pdf->cell(25, $iAlt, db_formatar($nValorBirdTotal, 'f') , 1, 0, "R");
$pdf->cell(25, $iAlt,''                                  , 1, 0, "C");
$pdf->cell(55, $iAlt,'', 1, 1, "C");
$pdf->line(10, $iInicio, 10, $pdf->getY());
$pdf->line(18, $iInicio, 18, $pdf->getY());
$pdf->line(10, $pdf->getY(), 190, $pdf->getY());
$pdf->Output();