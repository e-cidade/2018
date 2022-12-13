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
 * @version $Revision: 1.9 $
 */
include ("fpdf151/pdf.php");
include ("libs/db_liborcamento.php");
require("libs/db_utils.php");
require_once("model/cronogramaFinanceiro.model.php");
require_once("model/cronogramaBaseReceita.model.php");
require_once("model/cronogramaMetaReceita.model.php");
require_once("model/cronogramaMetaDespesa.model.php");
require_once("model/relatorioContabil.model.php");
$oParams = db_utils::postMemory($_POST);
/**
 * Preparamos os dados
 */
$oRelatorioOrcamento = new relatorioContabil(77);
$aRecursos           = "";
$sWhereSelOrcDotacao = '';
if (isset($oParams->recursos) && $oParams->recursos != "") { 
  $aRecursos   = $oParams->recursos; 
  /**
   * Preparamos a string para a classe cl_selorcdotacao
   */
  foreach($oParams->recursos as $recurso) {
    
    $sWhereSelOrcDotacao .= "recurso_{$recurso}-";
  }
}

$iTipoAgrupamento  = $oParams->periodicidade;
$oCronograma       = new cronogramaFinanceiro($oParams->o124_sequencial);
$oCronograma->setInstituicoes(array(str_replace("-",",",$oParams->db_selinstit)));
$aDadosMetaReceita = $oCronograma->getMetasReceita('', $aRecursos);

/**
 * Percorremos os registros da Receita  agrupamos pelo recurso
 */ 
$aReceitaAgrupadaPorRecurso = array();
foreach ($aDadosMetaReceita as $oReceita) {
	
  if ($oReceita->o70_codrec == '') {
    
    continue;
  }
  if (isset($aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo])) {
    
     /**
      * Agrupamento por mes
      */
    if ($iTipoAgrupamento == 1) {

      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][0]->valor  += $oReceita->aMetas->dados[0]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][1]->valor  += $oReceita->aMetas->dados[1]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][2]->valor  += $oReceita->aMetas->dados[2]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][3]->valor  += $oReceita->aMetas->dados[3]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][4]->valor  += $oReceita->aMetas->dados[4]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][5]->valor  += $oReceita->aMetas->dados[5]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][6]->valor  += $oReceita->aMetas->dados[6]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][7]->valor  += $oReceita->aMetas->dados[7]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][8]->valor  += $oReceita->aMetas->dados[8]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][9]->valor  += $oReceita->aMetas->dados[9]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][10]->valor += $oReceita->aMetas->dados[10]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][11]->valor += $oReceita->aMetas->dados[11]->valor;
     
     /**
      * Agrupamento por bimestre
      */
    } else {
     $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][0]->valor +=  $oReceita->aMetas->dados[0]->valor + 
                                                                      $oReceita->aMetas->dados[1]->valor;
     $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][1]->valor +=  $oReceita->aMetas->dados[2]->valor + 
                                                                      $oReceita->aMetas->dados[3]->valor;
     $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][2]->valor +=  $oReceita->aMetas->dados[4]->valor + 
                                                                      $oReceita->aMetas->dados[5]->valor; 
     $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][3]->valor +=  $oReceita->aMetas->dados[6]->valor + 
                                                                      $oReceita->aMetas->dados[7]->valor;
     $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][4]->valor +=  $oReceita->aMetas->dados[8]->valor + 
                                                                      $oReceita->aMetas->dados[9]->valor;
     $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][5]->valor +=  $oReceita->aMetas->dados[10]->valor + 
                                                                      $oReceita->aMetas->dados[11]->valor;
    }                                                                                                                                                                                                                                                                                                                                 
  } else {
    
    if ($iTipoAgrupamento == 1) {
     
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][0]->valor  = $oReceita->aMetas->dados[0]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][1]->valor  = $oReceita->aMetas->dados[1]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][2]->valor  = $oReceita->aMetas->dados[2]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][3]->valor  = $oReceita->aMetas->dados[3]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][4]->valor  = $oReceita->aMetas->dados[4]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][5]->valor  = $oReceita->aMetas->dados[5]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][6]->valor  = $oReceita->aMetas->dados[6]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][7]->valor  = $oReceita->aMetas->dados[7]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][8]->valor  = $oReceita->aMetas->dados[8]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][9]->valor  = $oReceita->aMetas->dados[9]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][10]->valor = $oReceita->aMetas->dados[10]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][11]->valor = $oReceita->aMetas->dados[11]->valor;
    
    } else {
     
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][0]->valor =  $oReceita->aMetas->dados[0]->valor + 
                                                                      $oReceita->aMetas->dados[1]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][1]->valor =  $oReceita->aMetas->dados[2]->valor + 
                                                                      $oReceita->aMetas->dados[3]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][2]->valor =  $oReceita->aMetas->dados[4]->valor + 
                                                                      $oReceita->aMetas->dados[5]->valor; 
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][3]->valor =  $oReceita->aMetas->dados[6]->valor + 
                                                                      $oReceita->aMetas->dados[7]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][4]->valor =  $oReceita->aMetas->dados[8]->valor + 
                                                                      $oReceita->aMetas->dados[9]->valor;
      $aReceitaAgrupadaPorRecurso[$oReceita->o70_codigo][5]->valor =  $oReceita->aMetas->dados[10]->valor + 
                                                                      $oReceita->aMetas->dados[11]->valor;  
    }
  }
}

$aDespesaAgrupadaPorRecurso = array();
$aDadosMetaDespesa = $oCronograma->getMetasDespesa(8, $sWhereSelOrcDotacao);
foreach ($aDadosMetaDespesa as $oDespesa) {
  
  if (isset($aDespesaAgrupadaPorRecurso[$oDespesa->codigo])) {
 
     /*
      * Agrupamento por mes
      */
     if ($iTipoAgrupamento == 1) {

       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][0]->valor  += @$oDespesa->aMetas->dados[0]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][1]->valor  += @$oDespesa->aMetas->dados[1]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][2]->valor  += @$oDespesa->aMetas->dados[2]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][3]->valor  += @$oDespesa->aMetas->dados[3]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][4]->valor  += @$oDespesa->aMetas->dados[4]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][5]->valor  += @$oDespesa->aMetas->dados[5]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][6]->valor  += @$oDespesa->aMetas->dados[6]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][7]->valor  += @$oDespesa->aMetas->dados[7]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][8]->valor  += @$oDespesa->aMetas->dados[8]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][9]->valor  += @$oDespesa->aMetas->dados[9]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][10]->valor += @$oDespesa->aMetas->dados[10]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][11]->valor += @$oDespesa->aMetas->dados[11]->valor;
     
     /**
      * Agrupamento por bimestre
      */
     } else {
       
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][0]->valor +=  @$oDespesa->aMetas->dados[0]->valor + 
                                                                        @$oDespesa->aMetas->dados[1]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][1]->valor +=  @$oDespesa->aMetas->dados[2]->valor + 
                                                                        @$oDespesa->aMetas->dados[3]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][2]->valor +=  @$oDespesa->aMetas->dados[4]->valor + 
                                                                        @$oDespesa->aMetas->dados[5]->valor; 
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][3]->valor +=  @$oDespesa->aMetas->dados[6]->valor + 
                                                                        @$oDespesa->aMetas->dados[7]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][4]->valor +=  @$oDespesa->aMetas->dados[8]->valor + 
                                                                        @$oDespesa->aMetas->dados[9]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][5]->valor +=  @$oDespesa->aMetas->dados[10]->valor + 
                                                                        @$oDespesa->aMetas->dados[11]->valor;
     }                                                                                                                                                                                                                                                                                                                                 
  } else {
     
    if ($iTipoAgrupamento == 1) {
       
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][0]->valor  = @$oDespesa->aMetas->dados[0]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][1]->valor  = @$oDespesa->aMetas->dados[1]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][2]->valor  = @$oDespesa->aMetas->dados[2]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][3]->valor  = @$oDespesa->aMetas->dados[3]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][4]->valor  = @$oDespesa->aMetas->dados[4]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][5]->valor  = @$oDespesa->aMetas->dados[5]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][6]->valor  = @$oDespesa->aMetas->dados[6]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][7]->valor  = @$oDespesa->aMetas->dados[7]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][8]->valor  = @$oDespesa->aMetas->dados[8]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][9]->valor  = @$oDespesa->aMetas->dados[9]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][10]->valor = @$oDespesa->aMetas->dados[10]->valor;
      $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][11]->valor = @$oDespesa->aMetas->dados[11]->valor;
       
      
     } else {

       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][0]->valor =  @$oDespesa->aMetas->dados[0]->valor + 
                                                                       @$oDespesa->aMetas->dados[1]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][1]->valor =  @$oDespesa->aMetas->dados[2]->valor + 
                                                                       @$oDespesa->aMetas->dados[3]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][2]->valor =  @$oDespesa->aMetas->dados[4]->valor + 
                                                                       @$oDespesa->aMetas->dados[5]->valor; 
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][3]->valor =  @$oDespesa->aMetas->dados[6]->valor + 
                                                                       @$oDespesa->aMetas->dados[7]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][4]->valor =  @$oDespesa->aMetas->dados[8]->valor + 
                                                                       @$oDespesa->aMetas->dados[9]->valor;
       $aDespesaAgrupadaPorRecurso[$oDespesa->codigo][5]->valor =  @$oDespesa->aMetas->dados[10]->valor + 
                                                                       @$oDespesa->aMetas->dados[11]->valor;
     }                                                                       
  }
}

/**
 * Realizamos o totalizador por Recurso, ou por Bimestre 
 */
foreach ($aReceitaAgrupadaPorRecurso as $iRecurso => $oReceita) {

  $aReceitaAgrupadaPorRecurso[$iRecurso]["nValorTotal"] = 0;
  foreach ($oReceita as $oValor) {
    $aReceitaAgrupadaPorRecurso[$iRecurso]["nValorTotal"] += $oValor->valor;  
  }
}

foreach ($aDespesaAgrupadaPorRecurso as $iRecurso => $oDespesa) {
  
  $aDespesaAgrupadaPorRecurso[$iRecurso]["nValorTotal"] = 0;
  foreach ($oDespesa as $oValor) {
    $aDespesaAgrupadaPorRecurso[$iRecurso]["nValorTotal"] += $oValor->valor;  
  }
}

$aLinhasRelatorio = array();
if ($iTipoAgrupamento == 1) {

  $iTotalLinhas = 12;
  $sStringPeriocidade = "Mensal";
  
  $aLabelsLinha[0]  = "Janeiro";
  $aLabelsLinha[1]  = "Fevereiro";
  $aLabelsLinha[2]  = "Março";
  $aLabelsLinha[3]  = "Abril";
  $aLabelsLinha[4]  = "Maio";
  $aLabelsLinha[5]  = "Junho";
  $aLabelsLinha[6]  = "Julho";
  $aLabelsLinha[7]  = "Agosto";
  $aLabelsLinha[8]  = "Setembro";
  $aLabelsLinha[9]  = "Outubro";
  $aLabelsLinha[10] = "Novembro";
  $aLabelsLinha[11] = "Dezembro";
  
} else {
  
  $iTotalLinhas = 0;
  $sStringPeriocidade = "Bimestral";
  
  $aLabelsLinha[0]  = "1º Bim";
  $aLabelsLinha[1]  = "2º Bim";
  $aLabelsLinha[2]  = "3º Bim";
  $aLabelsLinha[3]  = "5º Bim";
  $aLabelsLinha[4]  = "5º Bim";
  $aLabelsLinha[5]  = "6º Bim";
  
}
unset($aDadosMetaDespesa);
unset($aDadosMetaReceita);
/**
 * Agrupamos os objetos em um unico objeto contendo os dados do Relatorio
 */
foreach ($aReceitaAgrupadaPorRecurso as $iRecurso => $oReceita) {
  
  $oDaoRecurso = db_utils::getDao("orctiporec");
  $sSqlRecurso = $oDaoRecurso->sql_query_file($iRecurso);
  $rsRecurso   = $oDaoRecurso->sql_record($sSqlRecurso);
  if ($oParams->forma == 1) {
    $aLinhasRelatorio[$iRecurso]["descricao"] = db_utils::fieldsMemory($rsRecurso,0)->o15_descr;
  }
  for ($iLabel = 0; $iLabel < count($aLabelsLinha); $iLabel++) {
     
    if ($oParams->forma == 1) {
      
       $aLinhasRelatorio[$iRecurso][$iLabel]->valorReceita = $oReceita[$iLabel]->valor;
       $aLinhasRelatorio[$iRecurso][$iLabel]->valorDespesa = 0;
     
       if (isset($aDespesaAgrupadaPorRecurso[$iRecurso][$iLabel])) {
         $aLinhasRelatorio[$iRecurso][$iLabel]->valorDespesa = $aDespesaAgrupadaPorRecurso[$iRecurso][$iLabel]->valor;
       }
       
    } else {
      
      if (isset($aLinhasRelatorio[0][$iLabel])) {
        
        $aLinhasRelatorio[0][$iLabel]->valorReceita += $oReceita[$iLabel]->valor;
        $aLinhasRelatorio[0][$iLabel]->valorDespesa += 0;
        if (isset($aDespesaAgrupadaPorRecurso[$iRecurso][$iLabel])) {
          $aLinhasRelatorio[0][$iLabel]->valorDespesa += $aDespesaAgrupadaPorRecurso[$iRecurso][$iLabel]->valor;
        }
      } else {
        
        $aLinhasRelatorio[0][$iLabel]->valorReceita = $oReceita[$iLabel]->valor;
        $aLinhasRelatorio[0][$iLabel]->valorDespesa = 0;
        if (isset($aDespesaAgrupadaPorRecurso[$iRecurso])) {
          $aLinhasRelatorio[0][$iLabel]->valorDespesa = $aDespesaAgrupadaPorRecurso[$iRecurso][$iLabel]->valor;
        } 
      }
    }
  }
  
  /**
   * agrupamos os valores totais do recurso/Agrupador Geral
   */
  if ($oParams->forma == 1) {
    
    $aLinhasRelatorio[$iRecurso]["nValorTotalRec"]   = $oReceita["nValorTotal"];    
    $aLinhasRelatorio[$iRecurso]["nValorTotalDesp"] = 0; 
    if (isset($aDespesaAgrupadaPorRecurso[$iRecurso])) {
      $aLinhasRelatorio[$iRecurso]["nValorTotalDesp"] = $aDespesaAgrupadaPorRecurso[$iRecurso]["nValorTotal"];
    }   
  } else {

    if (isset($aLinhasRelatorio[0]["nValorTotalRec"])) {

      $aLinhasRelatorio[0]["nValorTotalRec"]  += $oReceita["nValorTotal"];
      if (isset($aDespesaAgrupadaPorRecurso[$iRecurso])) {
        $aLinhasRelatorio[0]["nValorTotalDesp"] += $aDespesaAgrupadaPorRecurso[$iRecurso]["nValorTotal"];
      }
      
    } else {
      
      $aLinhasRelatorio[0]["nValorTotalRec"]  = $oReceita["nValorTotal"];
      $aLinhasRelatorio[0]["nValorTotalDesp"] = 0;
      if (isset($aDespesaAgrupadaPorRecurso[$iRecurso])) {
        $aLinhasRelatorio[0]["nValorTotalDesp"] = $aDespesaAgrupadaPorRecurso[$iRecurso]["nValorTotal"];
      }       
    }
  }
}

unset($aDespesaAgrupadaPorRecurso);
unset($aReceitaAgrupadaPorRecurso);

$sStringEmissao = "";
if ($oParams->forma == 1) {
  $sStringEmissao = "Por Recurso";
} else {
  $sStringEmissao = "Totalização Geral";
}

$head1 = "METAS DA RECEITA X COTAS DA DESPESA";
$head2 = "Orçamento do exercício de {$oCronograma->getAno()}";
$head3 = "Emissão: {$sStringEmissao} ";
$head4 = "Periodicidade: {$sStringPeriocidade} ";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
$pdf->AddPage();
$pdf->setfillcolor(244);
$sFonte = "arial";
$iAlt   = 4; 

$lEscreveHeader = true;
/**
 * escrevemos o relatorio
 */
foreach ($aLinhasRelatorio as $iRecurso => $oLinha) {
  
  for ($iLabel = 0; $iLabel < count($aLabelsLinha); $iLabel++) {

  	if ($oLinha[$iLabel]->valorReceita == 0 || $oLinha[$iLabel]->valorDespesa == 0) {
  		  $mostra = false;
        continue;
    }
    
    if (($pdf->GetY() > $pdf->h - 25) || $lEscreveHeader) {
    	
      if ($pdf->GetY() > $pdf->h - 25) { 
        $pdf->AddPage();
      }
      $pdf->SetFont($sFonte,"b",8);
      if ($oParams->forma == 1) {
        
         $pdf->cell(10,  $iAlt, $iRecurso." - ", 0,0, "L");
         $pdf->cell(100,$iAlt, $oLinha["descricao"], 0,1, "L");
         
      } else {
        $pdf->cell(50,$iAlt, "Totalização Geral", 0,1, "C");
      }
      $pdf->cell(25, $iAlt *3,"Período","TBR", 0, "C", 1);   
      $pdf->cell(150, $iAlt,"Valores {$sStringPeriocidade}","TBR", 1, "C", 1);
      $pdf->SetXY(35, $pdf->GetY()-($iAlt));
      $pdf->cell(60, $iAlt,"Receita", 1, 0, "C", 1);   
      $pdf->cell(60, $iAlt,"Despesa", 1, 0, "C", 1);   
      $pdf->cell(40, $iAlt,"Diferença", "TBL", 1, "C", 1);
      $pdf->SetX(35);
      $pdf->cell(30, $iAlt,"Valor R$", 1, 0, "C", 1);   
      $pdf->cell(30, $iAlt,"% s/ Total", 1, 0, "C", 1);
      $pdf->cell(30, $iAlt,"Valor R$", 1, 0, "C", 1);   
      $pdf->cell(30, $iAlt,"% s/ Total", 1, 0 , "C", 1);
      $pdf->cell(40, $iAlt,"Valor R$", "TBL", 1, "C", 1);
      $lEscreveHeader = false;
    }

    $pdf->SetFont($sFonte, "b", 7);  
    $pdf->cell(25, $iAlt, $aLabelsLinha[$iLabel], "TBR", 0, "L",1);
    $pdf->SetFont($sFonte, "", 7);
    $pdf->Cell(30, $iAlt, db_formatar($oLinha[$iLabel]->valorReceita,"f"), 1, 0, "R");
    $pdf->Cell(30, $iAlt, @db_formatar((($oLinha[$iLabel]->valorReceita*100)/$oLinha["nValorTotalRec"]),"f"), 1, 0, "R");
    $pdf->Cell(30, $iAlt, db_formatar($oLinha[$iLabel]->valorDespesa, "f"), 1, 0, "R");
    $pdf->Cell(30, $iAlt, @db_formatar((($oLinha[$iLabel]->valorDespesa*100)/$oLinha["nValorTotalDesp"]),"f"), 1, 0, "R");
    $pdf->Cell(40, $iAlt, db_formatar($oLinha[$iLabel]->valorReceita - $oLinha[$iLabel]->valorDespesa,"f"), "TBL", 1, "R");
  }

  if ($oLinha["nValorTotalRec"] != 0 || $oLinha["nValorTotalDesp"] != 0 ) {
  
     $pdf->SetFont($sFonte, "b", 7);  
     $pdf->cell(25, $iAlt, "Total do Recurso", "TBR", 0, "L",1);
     $pdf->SetFont($sFonte, "", 7);
     $pdf->Cell(30, $iAlt, db_formatar($oLinha["nValorTotalRec"] ,"f"), 1, 0, "R");
     $pdf->Cell(30, $iAlt, db_formatar("100","f"), 1, 0, "R");
     $pdf->Cell(30, $iAlt, db_formatar($oLinha["nValorTotalDesp"] ,"f"), 1, 0, "R");
     $pdf->Cell(30, $iAlt, db_formatar("100","f"), 1, 0, "R");
     $pdf->Cell(40, $iAlt, db_formatar($oLinha["nValorTotalRec"] - $oLinha["nValorTotalDesp"],"f"), "TBL", 1, "R");
     $pdf->ln();       
  }

  $lEscreveHeader = true;
  
}
$pdf->ln();
$oRelatorioOrcamento->getNotaExplicativa($pdf,1);
$pdf->Output();
?>