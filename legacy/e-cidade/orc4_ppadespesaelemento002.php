<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
 * @version $Revision: 1.17 $
 */
include ("fpdf151/pdf.php");
include ("libs/db_liborcamento.php");
require ("libs/db_utils.php");
require ("model/ppaVersao.model.php");


/**
 *
 * forçamos $_SESSION["DB_use_pcasp"] = 't'
 * para que seja feito o parse das tabelas na analiseQueryPlanoOrcamento
 * apesar do ano nao ser 2013 em que o pcasp estaria ativo
 * modificação temporaria devendo ser mudado
 * @todo remover $_SESSION["DB_use_pcasp"] = 't';
 */
$_SESSION["DB_use_pcasp"] = 't';

$oDaoPPALei      = db_utils::getDao("ppalei");
$clselorcdotacao = new cl_selorcdotacao();
$oPost           = db_utils::postMemory($_POST);

$sSqlPPALei  = $oDaoPPALei->sql_query($oPost->o05_ppalei);
$rsPPALei    = $oDaoPPALei->sql_record($sSqlPPALei);
$oLeiPPA     = db_utils::fieldsMemory($rsPPALei, 0);
  
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits        = str_replace('-',', ',$db_selinstit);
$sele_work      = $clselorcdotacao->getDados(false);
$sElementos     = $clselorcdotacao->getElemento();
$sRecursos      = $clselorcdotacao->recurso;
$oPPAVersao     = new ppaVersao($oPost->o05_ppaversao);
$desdobramentos = $clselorcdotacao->getDesdobramento(); // coloca os codele dos desdobramntos no formato (x,y,z)
if ($desdobramentos != "") {
  $sele_desdobramentos = " and o08_elemento in ".$desdobramentos; // adiciona desdobramentos
}
$sele_work = str_replace("o58", "o08", $sele_work);
$sele_work = str_replace("o08_codigo", "o08_recurso", $sele_work);

$sSql  = "select distinct o08_orgao,                                                                                     "; 
$sSql .= "       trim(o40_descr) as o40_descr,                                                                           ";
$sSql .= "       o08_unidade,                                                                                            ";
$sSql .= "       trim(o41_descr) as o41_descr,                                                                           ";
$sSql .= "       o08_funcao,                                                                                             ";
$sSql .= "       trim(o52_descr) as o52_descr,                                                                           ";
$sSql .= "       o08_subfuncao,                                                                                          ";
$sSql .= "       trim(o53_descr) as o53_descr,                                                                           ";
$sSql .= "       o08_programa,                                                                                           ";
$sSql .= "       trim(o54_descr) as o54_descr,                                                                           ";
$sSql .= "       o08_projativ,                                                                                           ";
$sSql .= "       trim(o55_descr) as o55_descr                                                                            ";
$sSql .= "  from ppaestimativadespesa                                                                                    ";
$sSql .= "       inner join ppadotacao          on o07_coddot                   = o08_sequencial                         ";
$sSql .= "       inner join ppaestimativa       on ppaestimativa.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa ";
$sSql .= "       left join ppadotacaoorcdotacao on o19_ppadotacao               = o08_sequencial                         ";
$sSql .= "       inner join orcelemento         on o08_elemento                 = o56_codele                             ";
$sSql .= "                                     and o56_anousu                   = o08_ano                                ";
$sSql .= "       inner join conplano            on c60_codcon                   = o56_codele                             ";
$sSql .= "                                     and o56_anousu                   = c60_anousu                             ";
$sSql .= "       inner join orcfuncao           on orcfuncao.o52_funcao         = ppadotacao.o08_funcao                  ";
$sSql .= "       inner join orcsubfuncao        on orcsubfuncao.o53_subfuncao   = ppadotacao.o08_subfuncao               ";
$sSql .= "       inner join orcprograma         on orcprograma.o54_anousu       = ppadotacao.o08_ano                     ";
$sSql .= "                                     and orcprograma.o54_programa     = ppadotacao.o08_programa                ";
$sSql .= "       inner join orcprojativ         on orcprojativ.o55_anousu       = ppadotacao.o08_ano                     ";
$sSql .= "                                     and orcprojativ.o55_projativ     = ppadotacao.o08_projativ                ";
$sSql .= "       inner join orcorgao            on orcorgao.o40_anousu          = ppadotacao.o08_ano                     ";
$sSql .= "                                     and orcorgao.o40_orgao           = ppadotacao.o08_orgao                   ";
$sSql .= "       inner join orcunidade          on orcunidade.o41_anousu        = ppadotacao.o08_ano                     ";
$sSql .= "                                     and orcunidade.o41_orgao         = ppadotacao.o08_orgao                   ";
$sSql .= "                                     and orcunidade.o41_unidade       = ppadotacao.o08_unidade                 ";
$sSql .= "       inner join orcproduto          on orcproduto.o22_codproduto    = orcprojativ.o55_orcproduto             ";
$sSql .= "       inner join orctiporec          on o15_codigo                   = o08_recurso                            ";
$sSql .= " where {$sele_work}                                                                                            ";
$sSql .= "   and o08_instit in ({$instits})                                                                              ";
$sSql .= "   and o05_ppaversao  =  {$oPost->o05_ppaversao}                                                               ";
$sSql .= "   and o08_ppaversao  =  {$oPost->o05_ppaversao}                                                               ";
$sSql .= "   and o08_ano       >=  {$oLeiPPA->o01_anoinicio}                                                             ";
$sSql .= " order by o08_orgao,                                                                                           "; 
$sSql .= "       o40_descr,                                                                                              ";
$sSql .= "       o08_unidade,                                                                                            ";
$sSql .= "       o41_descr,                                                                                              ";
$sSql .= "       o08_funcao,                                                                                             ";
$sSql .= "       o52_descr,                                                                                              ";
$sSql .= "       o08_subfuncao,                                                                                          ";
$sSql .= "       o53_descr,                                                                                              ";
$sSql .= "       o08_programa,                                                                                           ";
$sSql .= "       o54_descr,                                                                                              ";
$sSql .= "       o08_projativ,                                                                                           ";
$sSql .= "       o55_descr                                                                                               ";

$rsConsulta   = db_query(analiseQueryPlanoOrcamento($sSql));
$iNumRows     = pg_num_rows($rsConsulta);
$aLinhasAcoes = db_utils::getCollectionByRecord($rsConsulta);
 
/**
 * Percorremos as acoes encontradas e pesquisamos os dados dos elementos da acao
 */
$aElementos     = explode(",", $sElementos);
$sWhereElemento = "";
foreach ($aElementos as $aElementos) {
	
  $sOr             = "";
  if ($sWhereElemento != "") {
    $sOr = " or "; 
  }
  $sWhereElemento .= "{$sOr} o56_elemento like '".trim($aElementos)."%' ";
}

$aLinhasRelatorio = array();
if ($iNumRows > 0) {
  
  foreach ($aLinhasAcoes as $oLinhaAcao) {
    
    $oLinhaAdicionar  = $oLinhaAcao; 
    $sSqlElemento     = "select distinct o08_elemento,                                                ";
    $sSqlElemento    .= "       o56_elemento,                                                         ";
    $sSqlElemento    .= "       trim(o56_descr) as o56_descr,                                         "; 
    $sSqlElemento    .= "       o08_recurso,                                                          "; 
    $sSqlElemento    .= "       trim(o15_descr) as o15_descr                                          "; 
    $sSqlElemento    .= "  from ppaestimativa                                                         "; 
    $sSqlElemento    .= "       inner join ppaestimativadespesa on o05_sequencial = o07_ppaestimativa "; 
    $sSqlElemento    .= "       inner join ppadotacao           on o08_sequencial = o07_coddot        ";
    $sSqlElemento    .= "       inner join orcelemento          on o08_elemento   = o56_codele        ";
    $sSqlElemento    .= "                                      and o08_ano        = o56_anousu        ";
    $sSqlElemento    .= "       inner join orctiporec           on o08_recurso    = o15_codigo        ";
    $sSqlElemento    .= " where o08_ppaversao = {$oPost->o05_ppaversao}                               ";
    $sSqlElemento    .= "   and o05_ppaversao = {$oPost->o05_ppaversao}                               ";
    $sSqlElemento    .= "   and o08_orgao     = {$oLinhaAcao->o08_orgao}                              "; 
    $sSqlElemento    .= "   and o08_unidade   = {$oLinhaAcao->o08_unidade}                            ";
    $sSqlElemento    .= "   and o08_funcao    = {$oLinhaAcao->o08_funcao}                             ";
    $sSqlElemento    .= "   and o08_subfuncao = {$oLinhaAcao->o08_subfuncao}                          ";
    $sSqlElemento    .= "   and o08_programa  = {$oLinhaAcao->o08_programa}                           ";
    $sSqlElemento    .= "   and o08_projativ  = {$oLinhaAcao->o08_projativ}                           ";
    $sSqlElemento    .= "   and o08_ano      >= {$oLeiPPA->o01_anoinicio}                           ";
    if ($sWhereElemento != "" ) {
      $sSqlElemento  .= "   and ($sWhereElemento)                                                     ";
    }
    if ($sRecursos) {
      $sSqlElemento  .= "   and o08_recurso  in {$sRecursos}                                          ";
    }
    $sSqlElemento    .= "   and o08_instit in ({$instits})                                            ";
    $sSqlElemento    .= "   order by o56_elemento                                                     ";
    $rsElemento       = db_query($sSqlElemento);
        
    $iNumRowsElemento = pg_num_rows($rsElemento);
    for ($i = 0; $i < $iNumRowsElemento; $i++) {
      
      $oElemento = db_utils::fieldsMemory($rsElemento, $i);
      /**
       * Calculamos o valor dos anos para cada elemento
       */
      for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {

        $sSqlValor  = "select coalesce(sum(o05_valor),0) as valor                                   ";
        $sSqlValor .= "  from ppaestimativa                                                         "; 
        $sSqlValor .= "       inner join ppaestimativadespesa on o05_sequencial = o07_ppaestimativa "; 
        $sSqlValor .= "       inner join ppadotacao           on o08_sequencial = o07_coddot        ";
        $sSqlValor .= " where o05_anoreferencia = {$iAno}                                           ";
        $sSqlValor .= "   and o05_ppaversao     = {$oPost->o05_ppaversao}                           ";
        $sSqlValor .= "   and o08_ppaversao     = {$oPost->o05_ppaversao}                           ";
        $sSqlValor .= "   and o08_instit        in ({$instits})                                     ";
        $sSqlValor .= "   and o08_orgao         = {$oLinhaAcao->o08_orgao}                          "; 
        $sSqlValor .= "   and o08_unidade       = {$oLinhaAcao->o08_unidade}                        ";
        $sSqlValor .= "   and o08_funcao        = {$oLinhaAcao->o08_funcao}                         ";
        $sSqlValor .= "   and o08_subfuncao     = {$oLinhaAcao->o08_subfuncao}                      ";
        $sSqlValor .= "   and o08_programa      = {$oLinhaAcao->o08_programa}                       ";
        $sSqlValor .= "   and o08_elemento      = {$oElemento->o08_elemento}                        ";
        $sSqlValor .= "   and o08_recurso       = {$oElemento->o08_recurso}                         ";
        $sSqlValor .= "   and o08_projativ      = {$oLinhaAcao->o08_projativ}                       ";
        $rsValor    = db_query($sSqlValor); 
        $oElemento->valor[$iAno] = db_utils::fieldsMemory($rsValor, 0)->valor; 
        
      }
      $oLinhaAdicionar->elementos[] = $oElemento;
    }
    $aLinhasRelatorio[] = $oLinhaAdicionar;
  }
  
  /*
   * Faz o somatório de valores por ano de cada orgão e o total do relatório. 
   */
  $iRegistrosPorOrgao = array();
  $iTotalDoRelatorio  = array();
  foreach ($aLinhasRelatorio as $oLinhaRelatorio) {
  	
  	if (!isset($aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao])) {
      $aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao] = array('orgao' => $oLinhaRelatorio->o08_orgao);
    }
    
    if (!isset($iRegistrosPorOrgao[$oLinhaRelatorio->o08_orgao])) {
      $iRegistrosPorOrgao[$oLinhaRelatorio->o08_orgao] = 1;
    } else {
      $iRegistrosPorOrgao[$oLinhaRelatorio->o08_orgao] += 1;
    }
    
    $iContador = array();
	foreach ($oLinhaRelatorio->elementos as $elem){
      for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
       	   
        if (isset($iContador[$iAno])) {
          $iContador[$iAno] += $elem->valor[$iAno];
        } else {
          $iContador[$iAno]  = $elem->valor[$iAno];
        }  
      }
    }
    
    for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
    	
      if (isset($aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao][$iAno])) {
        $aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao][$iAno] += $iContador[$iAno];
      } else {
        $aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao][$iAno]  = $iContador[$iAno];
      }
      
      if (isset($iTotalDoRelatorio[$iAno])) {
      	$iTotalDoRelatorio[$iAno] += $iContador[$iAno];
      } else {
      	$iTotalDoRelatorio[$iAno]  = $iContador[$iAno];
      }
    }
  }
  
  $head2  = "PPA Por Elemento";
  $head3  = "PPA - {$oPost->o01_anoinicio} - {$oPost->o01_anofinal}";
  $head4  = "Lei {$oLeiPPA->o01_numerolei} - {$oLeiPPA->o01_descricao}";
  $head5  = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
  $pdf    = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->SetAutoPageBreak(false,1);
  $pdf->AddPage();
  $pdf->setfillcolor(244);
  $sFonte = "arial";
  $iAlt   = 4; 
  $pdf->cell(90, $iAlt, "Estrutural","RTB",0, "C" ,1);
  for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
    $pdf->cell(20, $iAlt, $iAno, 1, 0, "C" ,1);  
  }
  $pdf->cell(20, $iAlt, "Total", "LTB", 1,"C" ,1);
  $iContadorOrgao = 0;
  foreach ($aLinhasRelatorio as $oLinhaRelatorio) {
        
     if ($pdf->getY() > $pdf->h - 35) {
       
       $pdf->SetFont($sFonte,"",8); 
       $pdf->AddPage();
       $pdf->cell(90, $iAlt, "Estrutural","RTB",0, "C" ,1);
       for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
         $pdf->cell(20, $iAlt, $iAno, 1, 0, "C" ,1);  
       }
       $pdf->cell(20, $iAlt, "Total", "LTB", 1,"C" ,1);
     }
     
     /**
	  * Calcula o total do elemento
	  */
     $nTotal = 0;
     foreach($oLinhaRelatorio->elementos as $oElemento) {

       for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
         
         if (isset($oElemento->valor[$iAno])) {
           $nTotal     +=  $oElemento->valor[$iAno];
         }
       }
     }
     
     /*
      * Incrementa o contador
      */
     $iContadorOrgao++;
     
     /*
      * só imprime no relatório se a soma do total do elemento for maior do que zero
      */
     if ($nTotal  > 0) {
     	
       $pdf->SetFont($sFonte,"",7);
  	   $pdf->cell(25, $iAlt, $oLinhaRelatorio->o08_orgao, 0 , 0, "L" );
  	   $pdf->SetFont($sFonte,"",5);
  	   $pdf->cell(50, $iAlt, $oLinhaRelatorio->o40_descr, 0 , 1, "L" );
  	     
  	   $pdf->SetFont($sFonte,"",7);
  	   $pdf->cell(25, $iAlt, "{$oLinhaRelatorio->o08_orgao}.{$oLinhaRelatorio->o08_unidade}", 0 , 0, "L" );
  	   $pdf->SetFont($sFonte,"",5);
  	   $pdf->cell(50, $iAlt, $oLinhaRelatorio->o41_descr, 0 , 1, "L" );
  	     
  	   $sEstruturalFuncao  = "{$oLinhaRelatorio->o08_orgao}.{$oLinhaRelatorio->o08_unidade}.";
  	   $sEstruturalFuncao .= "{$oLinhaRelatorio->o08_funcao}";
  	   $pdf->SetFont($sFonte,"",7);
  	   $pdf->cell(25, $iAlt, $sEstruturalFuncao, 0 , 0, "L" );
  	   $pdf->SetFont($sFonte,"",5);
  	   $pdf->cell(50, $iAlt, $oLinhaRelatorio->o52_descr, 0 , 1, "L" );
  	     
  	   $sEstrutSubFuncao  = "{$oLinhaRelatorio->o08_orgao}.{$oLinhaRelatorio->o08_unidade}.{$oLinhaRelatorio->o08_funcao}.";
  	   $sEstrutSubFuncao .= "{$oLinhaRelatorio->o08_subfuncao}"; 
  	   $pdf->SetFont($sFonte,"",7);
  	   $pdf->cell(25, $iAlt, $sEstrutSubFuncao, 0 , 0, "L" );
  	   $pdf->SetFont($sFonte,"",5);
  	   $pdf->cell(50, $iAlt, $oLinhaRelatorio->o53_descr, 0 , 1, "L" );
  	     
  	   $pdf->SetFont($sFonte,"",7);
  	   $sEstrutPrograma  = "{$sEstrutSubFuncao}.{$oLinhaRelatorio->o08_programa}"; 
  	   $pdf->cell(25, $iAlt, $sEstrutPrograma, 0 , 0, "L" );
  	   $pdf->SetFont($sFonte,"",5);
  	   $pdf->cell(50, $iAlt, $oLinhaRelatorio->o54_descr, 0 , 1, "L" );
  	   
  	   $sEstrutAcao  = "{$sEstrutPrograma}.{$oLinhaRelatorio->o08_projativ}";
  	   $nTamanhoString = strlen ($sEstrutAcao);
  	   switch ($nTamanhoString){
  	   	case $nTamanhoString == 20:
  	   		$nTamanhoFonte = 6.7;
  	   		break;
  	   	case $nTamanhoString >= 21:
  	   		$nTamanhoFonte = 6.2;
  	   		break;
  	   	default:
  	   		$nTamanhoFonte = 7;
  	   	break;
  	   }
  	   $pdf->SetFont($sFonte,"b",$nTamanhoFonte);
  	   $pdf->cell(25, $iAlt, $sEstrutAcao, 0 , 0, "L" );
  	   $pdf->SetFont($sFonte,"b",5);
  	   $pdf->cell(65, $iAlt, $oLinhaRelatorio->o55_descr, 0 , 0, "L" );
  	   $nValorAno = array();
  	   $nTotal    = 0;
  	   foreach($oLinhaRelatorio->elementos as $oElemento) {
  	       
  	     $nValoresColunas = array();
  	
  	     for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
  	         
  	       if (isset($oElemento->valor[$iAno])) {
  	          
  	         $nTotal += $oElemento->valor[$iAno];
  	         if (isset($nValorAno[$iAno])) {             
  	           $nValorAno[$iAno]  +=  $oElemento->valor[$iAno];
  	         } else {
  	           $nValorAno[$iAno]   =  $oElemento->valor[$iAno];
  	         }
  	       }
  	     }
  	   }
  	   
  	   $pdf->SetFont($sFonte,"b",7);
  	   for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
  	     $nValor = 0; 
  	     if (isset($nValorAno[$iAno])) {
  	       $nValor = $nValorAno[$iAno];
  	     }
  	     $pdf->cell(20, $iAlt, db_formatar($nValor,"f"), 0, 0, "R");
  	   }
  	   $pdf->cell(20, $iAlt, db_formatar($nTotal,"f"), 0, 1, "R");
  	     
  	   /**
  	    * Percorremos os Elementos
  	    */
  	    
  	   foreach($oLinhaRelatorio->elementos as $oElemento) {       
  	       
  	     if ($pdf->getY() > $pdf->h - 16) {
  	         
  	       $pdf->SetFont($sFonte,"",8); 
  	       $pdf->AddPage();
  	       $pdf->cell(90, $iAlt, "Estrutural","RTB",0, "C" ,1);
  	       for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
  	         $pdf->cell(20, $iAlt, $iAno, 1, 0, "C" ,1);  
  	       }
  	       $pdf->cell(20, $iAlt, "Total", "LTB", 1,"C" ,1);
  	     }
  	     
  	     /*
  	      * Calcula o total da linha 
  	      */
  	     $nTotal = 0;
  	     for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
  	       if (isset($oElemento->valor[$iAno])) {
  	         $nTotal += $oElemento->valor[$iAno];
  	       }
  	     }
  	     if ($nTotal == 0) {
  	       continue;
  	     }
  	       
  	     $pdf->SetFont($sFonte,"",7);
  	     $pdf->cell(25, $iAlt, $oElemento->o56_elemento, 0 , 0, "R" );
  	     $pdf->SetFont($sFonte,"",5);
  	     $pdf->cell(65, $iAlt, "{$oElemento->o56_descr} - Rec: {$oElemento->o08_recurso}", 0 , 0, "L" );
  	     $nTotal = 0;
  	     $pdf->SetFont($sFonte,"",7);
  	     for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
  	         
  	       $nValorAno = 0;
  	       if (isset($oElemento->valor[$iAno])) {
  	         
  	         $nTotal     +=  $oElemento->valor[$iAno];
  	         $nValorAno   =  $oElemento->valor[$iAno];
  	          
  	       }
  	       $pdf->cell(20, $iAlt, db_formatar($nValorAno,"f"), 0, 0, "R");  
  	     }
  	     $pdf->cell(20, $iAlt, db_formatar($nTotal,"f"), 0, 1,"R");
  	   }
  	   $pdf->Line(10, $pdf->getY(), 200, $pdf->GetY());
     }
     
     /*
      * testa se o elemento é o último do orgão atual
      * se for o último, imprime os totais
      */
     if ($iContadorOrgao == $iRegistrosPorOrgao[$oLinhaRelatorio->o08_orgao]) {
       
       $pdf->SetFont($sFonte,"b",7);
       $pdf->cell(90, $iAlt, "TOTAL DO ORGÃO: ", "TBR", 0,"L");
       $iTotal = 0;
       for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {
         
         $pdf->cell(20, $iAlt, db_formatar($aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao][$iAno], "f"), 1, 0, "R");
         $iTotal += $aRegistrosOrgaos[$oLinhaRelatorio->o08_orgao][$iAno];
       }
       $pdf->cell(20, $iAlt, db_formatar($iTotal, "f"), "TBL", 1, "R");
       $iContadorOrgao = 0;
     }
     
  }
  
  /*
   * Imprime o total do relatório
   */
  
  $pdf->cell(190, $iAlt, "", 0, 1, "R");
  $pdf->SetFont($sFonte,"b",7);
  $pdf->cell(90, $iAlt, "TOTAL DO RELATORIO", "TBR", 0,"L");
  $iTotal = 0;
  for ($iAno = $oPost->o01_anoinicio; $iAno <= $oPost->o01_anofinal; $iAno++) {

    $pdf->cell(20, $iAlt, db_formatar($iTotalDoRelatorio[$iAno], "f"), 1, 0, "R");
    $iTotal += $iTotalDoRelatorio[$iAno];
  }
  $pdf->cell(20, $iAlt, db_formatar($iTotal, "f"), "TBL", 1, "R");
  
  $pdf->Output();  
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Não foram encontradas despesas com os filtros selecionados.");  
}
?>