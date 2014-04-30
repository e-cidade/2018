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
 * @author Iuri Guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
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

$aMes = array( 1 => "janeiro",
               2 => "fevereiro", 
               3 => "marco", 
               4 => "abril", 
               5 => "maio", 
               6 => "junho", 
               7 => "julho", 
               8 => "agosto", 
               9 => "setembro", 
              10 => "outubro", 
              11 => "novembro", 
              12 => "dezembro", 

             );
$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt       = 4;
$iAnoUsu   = $oGet->iAno; 

$head1     = "CRONOGRAMA FÍSICO FINANCEIRO - {$iAnoUsu}";
$iMesAtual = date("m",db_getsession("DB_datausu"));
$oDaoConvenio  = db_utils::getDao("pactoplano");
$sSqlConvenio  = $oDaoConvenio->sql_query($oGet->iPlano,"*");
$rsConvenio    = $oDaoConvenio->sql_record($sSqlConvenio);
$oConvenio     = db_utils::fieldsMemory($rsConvenio, 0);
$head2         = "Número do Convênio: {$oConvenio->o16_convenio}";
if ($oConvenio->o74_obs != "") {
  $head3  = "Plano:". substr(str_replace("\n", "",$oConvenio->o74_obs),0,190);
} else {
  $head3  = "Plano:   {$oConvenio->o74_descricao}";
}
$pdf->AddPage();
$sSqlProgramas    = "select distinct o54_descr,";
$sSqlProgramas   .= "       o87_pactoprograma ";
$sSqlProgramas   .= "  from pactovalor ";
$sSqlProgramas   .= "       inner join orcprograma on o54_anousu   = o87_orcprogramaano ";
$sSqlProgramas   .= "                             and o54_programa = o87_pactoprograma  ";
$sSqlProgramas   .= " where o87_pactoplano = {$oGet->iPlano}";
$rsProgramas      = db_query($sSqlProgramas);
$aProgramas       = db_utils::getColectionByRecord($rsProgramas);

$iTotalProgramas  = count($aProgramas); 
/*
 * Pesquisamos todos os projetos para o programa e o pacto 
 */
$oTotal->janeiro   = 0; 
$oTotal->fevereiro = 0; 
$oTotal->marco     = 0; 
$oTotal->abril     = 0; 
$oTotal->maio      = 0; 
$oTotal->junho     = 0; 
$oTotal->julho     = 0; 
$oTotal->agosto    = 0; 
$oTotal->setembro  = 0; 
$oTotal->outubro   = 0; 
$oTotal->novembro  = 0; 
$oTotal->dezembro  = 0; 
$oTotal->total     = 0; 
for ($iProg = 0; $iProg < $iTotalProgramas; $iProg++) {

  $aProgramas[$iProg]->aProjetos = array();  
  $sSqlProjetos    = "select distinct o55_descr,";
  $sSqlProjetos   .= "       o87_orcprojativativprojeto ";
  $sSqlProjetos   .= "  from pactovalor ";
  $sSqlProjetos   .= "       inner join orcprojativ on o55_anousu   = o87_orcprojativanoprojeto ";
  $sSqlProjetos   .= "                             and o55_projativ = o87_orcprojativativprojeto";
  $sSqlProjetos   .= " where o87_pactoplano = {$oGet->iPlano} ";
  $sSqlProjetos   .= "   and o87_pactoprograma = {$aProgramas[$iProg]->o87_pactoprograma}";
  $rsProjetos      = db_query($sSqlProjetos);
  $aProjetos       = db_utils::getColectionByRecord($rsProjetos);
  $iTotalProjetos  = count($aProjetos);
  $aProgramas[$iProg]->janeiro   = 0; 
  $aProgramas[$iProg]->fevereiro = 0; 
  $aProgramas[$iProg]->marco     = 0; 
  $aProgramas[$iProg]->abril     = 0; 
  $aProgramas[$iProg]->maio      = 0; 
  $aProgramas[$iProg]->junho     = 0; 
  $aProgramas[$iProg]->julho     = 0; 
  $aProgramas[$iProg]->agosto    = 0; 
  $aProgramas[$iProg]->setembro  = 0; 
  $aProgramas[$iProg]->outubro   = 0; 
  $aProgramas[$iProg]->novembro  = 0; 
  $aProgramas[$iProg]->dezembro  = 0; 
  $aProgramas[$iProg]->total     = 0; 
  for ($iProj = 0; $iProj < $iTotalProjetos; $iProj++) {
    
    $aProjetos[$iProj]->aSubProjetos = array();
    $sSqlSubProjetos    = "select distinct o104_descricao,";
    $sSqlSubProjetos   .= "       o87_pactoatividade, ";
    /**
     * Criamos um subselect por mes
     */
    $sSqlSubProjetos   .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
    $sSqlSubProjetos   .= "          from  pactovalorsaldo";
    $sSqlSubProjetos   .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
    $sSqlSubProjetos   .= "          where o103_anousu = {$iAnoUsu}";
    $sSqlSubProjetos   .= "            and a.o87_pactoplano = {$oGet->iPlano}";
    $sSqlSubProjetos   .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
    $sSqlSubProjetos   .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
    $sSqlSubProjetos   .= "            and a.o87_pactoatividade         = val.o87_pactoatividade";
    $sSqlSubProjetos   .= "            and o103_pactovalorsaldotipo = 1";
    $sSqlSubProjetos   .= "          ) as planejado_total, ";
    for ($iMes = 1; $iMes <= count($aMes); $iMes++) {
      
      $sVirgula           = ",";
      if ($iMes == 12) {
        $sVirgula =  ""; 
      }
      
      $sSqlSubProjetos   .= "       (SELECT coalesce(sum(o103_valor),0) as valor_saldo";
      $sSqlSubProjetos   .= "          from  pactovalorsaldo";
      $sSqlSubProjetos   .= "                inner join pactovalor a on o103_pactovalor = a.o87_sequencial";
      $sSqlSubProjetos   .= "          where o103_anousu = {$iAnoUsu}";
      $sSqlSubProjetos   .= "            and o103_mesusu = {$iMes}";
      $sSqlSubProjetos   .= "            and a.o87_pactoplano = {$oGet->iPlano}";
      $sSqlSubProjetos   .= "            and a.o87_pactoprograma          = val.o87_pactoprograma";
      $sSqlSubProjetos   .= "            and a.o87_orcprojativativprojeto = val.o87_orcprojativativprojeto";
      $sSqlSubProjetos   .= "            and a.o87_pactoatividade         = val.o87_pactoatividade";
      $sSqlSubProjetos   .= "            and o103_pactovalorsaldotipo = 1";
      $sSqlSubProjetos   .= "          ) as planejado_{$aMes[$iMes]}{$sVirgula} ";
      
    }
    $sSqlSubProjetos   .= "  from pactovalor val";
    $sSqlSubProjetos   .= "       inner join pactoatividade on o104_sequencial = o87_pactoatividade";
    $sSqlSubProjetos   .= " where o87_pactoplano             = {$oGet->iPlano} ";
    $sSqlSubProjetos   .= "   and o87_orcprojativativprojeto = {$aProjetos[$iProj]->o87_orcprojativativprojeto}";
    $sSqlSubProjetos   .= "   and o87_pactoprograma          = {$aProgramas[$iProg]->o87_pactoprograma}";
    $rsSubProjetos     = db_query($sSqlSubProjetos);
    $aSubProjetos      = db_utils::getColectionByRecord($rsSubProjetos);
    $iTotalSubProjetos = count($aSubProjetos);
    for ($iSproj = 0; $iSproj < $iTotalSubProjetos; $iSproj++) {
      
      $aProgramas[$iProg]->janeiro   += $aSubProjetos[$iSproj]->planejado_janeiro; 
      $aProgramas[$iProg]->fevereiro += $aSubProjetos[$iSproj]->planejado_fevereiro; 
      $aProgramas[$iProg]->marco     += $aSubProjetos[$iSproj]->planejado_marco; 
      $aProgramas[$iProg]->abril     += $aSubProjetos[$iSproj]->planejado_abril; 
      $aProgramas[$iProg]->maio      += $aSubProjetos[$iSproj]->planejado_maio; 
      $aProgramas[$iProg]->junho     += $aSubProjetos[$iSproj]->planejado_junho; 
      $aProgramas[$iProg]->julho     += $aSubProjetos[$iSproj]->planejado_julho; 
      $aProgramas[$iProg]->agosto    += $aSubProjetos[$iSproj]->planejado_agosto; 
      $aProgramas[$iProg]->setembro  += $aSubProjetos[$iSproj]->planejado_setembro; 
      $aProgramas[$iProg]->outubro   += $aSubProjetos[$iSproj]->planejado_outubro; 
      $aProgramas[$iProg]->novembro  += $aSubProjetos[$iSproj]->planejado_novembro; 
      $aProgramas[$iProg]->dezembro  += $aSubProjetos[$iSproj]->planejado_dezembro;
      $aProgramas[$iProg]->total     += $aSubProjetos[$iSproj]->planejado_total;
      
      $oTotal->janeiro   += $aSubProjetos[$iSproj]->planejado_janeiro; 
      $oTotal->fevereiro += $aSubProjetos[$iSproj]->planejado_fevereiro; 
      $oTotal->marco     += $aSubProjetos[$iSproj]->planejado_marco; 
      $oTotal->abril     += $aSubProjetos[$iSproj]->planejado_abril; 
      $oTotal->maio      += $aSubProjetos[$iSproj]->planejado_maio; 
      $oTotal->junho     += $aSubProjetos[$iSproj]->planejado_junho; 
      $oTotal->julho     += $aSubProjetos[$iSproj]->planejado_julho; 
      $oTotal->agosto    += $aSubProjetos[$iSproj]->planejado_agosto; 
      $oTotal->setembro  += $aSubProjetos[$iSproj]->planejado_setembro; 
      $oTotal->outubro   += $aSubProjetos[$iSproj]->planejado_outubro; 
      $oTotal->novembro  += $aSubProjetos[$iSproj]->planejado_novembro; 
      $oTotal->dezembro  += $aSubProjetos[$iSproj]->planejado_dezembro;
      $oTotal->total     += $aSubProjetos[$iSproj]->planejado_total;
      
    }
    $aProjetos[$iProj]->aSubProjetos = $aSubProjetos;
    
  }
  $aProgramas[$iProg]->aProjetos = $aProjetos;
      
}
/*
 * Escrevemos o relatorio
 */
$pdf->cell(40, $alt, "Ações","RTB",0,"C",1);
$pdf->cell(7,  $alt, "Unid" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Total" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Jan" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Fev" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Mar" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Abr" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Mai" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Jun" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Jul" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Ago" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Set" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Out" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Nov" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, "Dez" ,"LTB",1,"C",1);

foreach ($aProgramas as $oLinhaRel) {
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(40, $alt, $oLinhaRel->o54_descr, 0,1,"L");
  
  foreach ($oLinhaRel->aProjetos as $oProjeto) {
  
    $pdf->setfont('arial','',6);
    $pdf->cell(40, $alt, $oProjeto->o55_descr, 0,1,"L");
    foreach ($oProjeto->aSubProjetos as $oSubProjeto) {
      
      
      $pdf->setfont('arial','',6);  
      $pdf->cell(40, $alt, substr($oSubProjeto->o104_descricao,0,29),"RT",0,"L");
      $pdf->setfont('arial','',6);
      $pdf->cell(7,  $alt, "R$" ,"LTB",0,"C");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_total,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_janeiro,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_fevereiro,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_marco,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_abril,"f"),"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_maio,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_junho,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_julho,"f") ,"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_agosto,"f"),"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_setembro,"f"),"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_outubro,"f"),"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_novembro,"f"),"LTB",0,"R");
      $pdf->cell(18, $alt, db_formatar($oSubProjeto->planejado_dezembro,"f"),"LTB",1,"R");
      
      $pdf->setfont('arial','',6); 
      $pdf->cell(40, $alt, substr($oSubProjeto->o104_descricao,29,60),"RB",0,"L");
      $pdf->cell(7,  $alt, "U" ,"LTB",0,"C");
      $pdf->cell(18, $alt, "" ,"LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "" ,"LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",0,"R");
      $pdf->cell(18, $alt, "","LTB",1,"R");
      
    }
  }
  $pdf->setfont('arial','',6); 
  $pdf->cell(40, $alt, "SUBTOTAL","TRB",0,"L",1);
  $pdf->cell(7,  $alt, "R$" ,"LTB",0,"C",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->total,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->janeiro,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->fevereiro,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->marco,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->abril,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->maio,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->junho,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->julho,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->agosto,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->setembro,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->outubro,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->novembro,"f"),"LTB",0,"R",1);
  $pdf->cell(18, $alt, db_formatar($oLinhaRel->dezembro,"f"),"LTB",1,"R",1);
  
}
$pdf->setfont('arial','',6); 
$pdf->cell(40, $alt, "TOTAL","TRB",0,"L",1);
$pdf->cell(7,  $alt, "R$" ,"LTB",0,"C",1);
$pdf->cell(18, $alt, db_formatar($oTotal->total,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->janeiro,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->fevereiro,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->marco,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->abril,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->maio,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->junho,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->julho,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->agosto,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->setembro,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->outubro,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->novembro,"f"),"LTB",0,"R",1);
$pdf->cell(18, $alt, db_formatar($oTotal->dezembro,"f"),"LTB",1,"R",1);
$pdf->Output();
?>