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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("classes/db_db_config_classe.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_orccenarioeconomicoparam_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/linhaRelatorioContabil.model.php");
require_once("model/relatorioContabil.model.php");
require_once("classes/db_orcparamrel_classe.php");
require_once("model/ppa.model.php");
require_once("model/ppaVersao.model.php");

$oGet	    = db_utils::postMemory($_GET);
$oPPAVersao = new ppaVersao($oGet->iCodVersao);
// Código do relatório
$iCodRel = $oGet->iCodRel;
$orcparamrel = new cl_orcparamrel();
// Lista todas intituições selecionadas 
$sListaInstit = str_replace('-',',',$oGet->sListaInstit);
$cldb_config                = new cl_db_config(); 
$clorccenarioeconomicoparam = new cl_orccenarioeconomicoparam();
$oRelataorioContabil        = new relatorioContabil($iCodRel);
$oPPaReceita = new ppa($iLei, 1, $oGet->iCodVersao);
$oPPaDespesa = new ppa($iLei, 2, $oGet->iCodVersao);
$iAnoRef      = db_getsession("DB_anousu")+1;
$iAnoCorrente = db_getsession("DB_anousu");

$aDadosReceita    = $oPPaReceita->getQuadroEstimativas();
$aDadosDespesa    = $oPPaDespesa->getQuadroEstimativas();
$aValoresReceita  = array();
$aValoresDespesa  = array();
foreach ($aDadosReceita as $oPrevisao) {
  
  $aValoresReceita[$oPrevisao->iEstrutural] = 0; 
  if (isset($oPrevisao->aEstimativas[$iAnoRef])) {
     $aValoresReceita[$oPrevisao->iEstrutural] = $oPrevisao->aEstimativas[$iAnoRef];
  }
}
$aSubFuncoes      = array();
$aLinhasRelatorio = $oRelataorioContabil->getLinhas();
$iLinhasTotal     = count($aLinhasRelatorio);
/*
 * calculamos o valor das variaveis
 */
for ($iLinha = 1;$iLinha <= $iLinhasTotal; $iLinha++) {
  
  $aLinhasRelatorio[$iLinha]->nValorPrevisto = 0;
  $aLinhasRelatorio[$iLinha]->aParametros    = $orcparamrel->sql_parametro($iCodRel, 
                                                                           $iLinha,
                                                                           "f",
                                                                           db_getsession("DB_instit"),
                                                                           $iAnoCorrente);
                                                                           
  $aLinhasRelatorio[$iLinha]->nivel   = $orcparamrel->sql_nivel($iCodRel,   $iLinha);
  $aLinhasRelatorio[$iLinha]->funcao  = $orcparamrel->sql_funcao($iCodRel,  $iLinha);
  $aLinhasRelatorio[$iLinha]->subfunc = $orcparamrel->sql_subfunc($iCodRel, $iLinha);
  $aLinhasRelatorio[$iLinha]->recurso = $orcparamrel->sql_recurso($iCodRel, $iLinha,db_getsession("DB_instit"));
                                                                             
  if (count($aLinhasRelatorio[$iLinha]->valoresVariaveis) > 0) {
    for ($iVar = 0;$iVar < count($aLinhasRelatorio[$iLinha]->valoresVariaveis); $iVar++) {    
      $aLinhasRelatorio[$iLinha]->nValorPrevisto += $aLinhasRelatorio[$iLinha]->valoresVariaveis[$iVar]->colunas[0]->o117_valor;    
    }
  }
}
foreach ($aValoresReceita as $iElemento => $nValor) {
  
  for ($iLinha = 1;$iLinha <= $iLinhasTotal; $iLinha++) {
  
    if (in_array($iElemento,$aLinhasRelatorio[$iLinha]->aParametros)) {
       $aLinhasRelatorio[$iLinha]->nValorPrevisto += $nValor;
    }
  }
}
foreach ($aDadosDespesa as $oDespesa) {
  
  for ($iLinha = 1;$iLinha <= $iLinhasTotal; $iLinha++) {
    
    $iElemento = $oDespesa->iElemento."00"; 
    if (in_array($iElemento, $aLinhasRelatorio[$iLinha]->aParametros)) {
      if (count( $aLinhasRelatorio[$iLinha]->funcao) == 0 || 
          in_array($oDespesa->funcao, $aLinhasRelatorio[$iLinha]->funcao)) {
        if (count( $aLinhasRelatorio[$iLinha]->subfunc) == 0 || 
            in_array($oDespesa->subfuncao, $aLinhasRelatorio[$iLinha]->subfunc)) {
          if (count( $aLinhasRelatorio[$iLinha]->recurso) == 0 || 
            in_array($oDespesa->iRecurso, $aLinhasRelatorio[$iLinha]->recurso)) {

            if (isset($oDespesa->aEstimativas[$iAnoRef])) {
              $aLinhasRelatorio[$iLinha]->nValorPrevisto += $oDespesa->aEstimativas[$iAnoRef];
            }
          }
        }
      } 
    }
  }
}
$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'))); 
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
  $sOrcAnexo = 'Orçamento - Anexo XI';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
  $sOrcAnexo = '';
}
$head2 = "MUNICÍPIO DE ".$oConfig->munic;
$head3 = "ANEXO XI";
$head4 = $sModelo;
$head5 = "DEMONSTRATIVO DAS RECEITAS E DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - MDE";
$head6 = "Versão: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
$head7 = $iAnoRef;
$aTotalizadores              	= array();
$aTotalizadores[0]["label"]  	= "1- RECEITA DE IMPOSTOS";    
$aTotalizadores[1]["label"]  	= "    1.1- Receita Resultante do Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU";    
$aTotalizadores[2]["label"]  	= "    1.2- Receita Resultante do Imposto sobre Transmissão Inter Vivos - ITBI";    
$aTotalizadores[3]["label"]  	= "    1.3- Receita Resultante do Imposto sobre Serviços de Qualquer Natureza - ISS";    
$aTotalizadores[4]["label"]  	= "    1.4- Receita Resultante do Imposto de Renda Retido na Fonte - IRRF";    
$aTotalizadores[5]["label"]  	= "    1.5- Receita Resultante do Imposto Territorial Rural - ITR (CF, art. 153, §4º, inciso III)";    
$aTotalizadores[6]["label"]  	= "2- RECEITA DE TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS ";    
$aTotalizadores[7]["label"]  	= "    2.1- Cota-Parte FPM ";          
$aTotalizadores[8]["label"]  	= "5- RECEITA DE TRANSFERÊNCIAS DO FNDE";    
$aTotalizadores[9]["label"]  = "6- RECEITA DE TRANSFERÊNCIAS DE CONVÊNIOS";    
$aTotalizadores[10]["label"]  = "10- RECEITAS DESTINADAS AO FUNDEB";    
$aTotalizadores[11]["label"]  = "11- RECEITAS RECEBIDAS DO FUNDEB";    
$aTotalizadores[12]["label"]  = "13- PAGAMENTO DOS PROFISSIONAIS DO MAGISTÉRIO";    
$aTotalizadores[13]["label"]  = "14- OUTRAS DESPESAS";    
$aTotalizadores[14]["label"]  = "17- EDUCAÇÃO INFANTIL";    
$aTotalizadores[15]["label"]  = "18- ENSINO FUNDAMENTAL";    
$aTotalizadores[16]["label"]  = "3- TOTAL DA RECEITA DE IMPOSTOS(1+2)";    
$aTotalizadores[17]["label"]  = "9- TOTAL DAS RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO (4+5+6+7+8)";    
$aTotalizadores[18]["label"]  = "12- RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDED (11.1 - 10) ";    
$aTotalizadores[19]["label"]  = "15- TOTAL DAS DESPESAS DO FUNDEB";    
$aTotalizadores[20]["label"]  = "16- MÍNIMO DE 60% DO FUNDEB NA REMUNERAÇÃO DO MAGISTÉRIO COM EDUCAÇÃO INFANTIL ";
$aTotalizadores[20]["label"] .= "E ENSINO FUNDAMENTAL1 ((13 / 11) x 100) %";
$aTotalizadores[21]["label"]  = "23- TOTAL DAS DESPESAS COM AÇÕES TÍPICAS DE MDE (17 + 18 + 19 + 20 + 21 + 22)";
$aTotalizadores[22]["label"]  = "24- RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB = (12)";
$aTotalizadores[23]["label"]  = "26- RECEITA DE APLICAÇÃO FINANCEIRA DOS RECURSOS DO FUNDEB ATÉ O BIMESTRE = (11.3)";
$aTotalizadores[24]["label"]  = "27- TOTAL DAS DEDUÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL (24 + 25 + 26)";
$aTotalizadores[25]["label"]  = "28- TOTAL DAS DESPESAS PARA FINS DE LIMITE ((17 + 18) - (27))";
$aTotalizadores[26]["label"]  = "29- MÍNIMO DE 25% DAS RECEITAS RESULTANTES DE IMPOSTOS EM MDE5 ((28) / (3) x 100) %";
$aTotalizadores[27]["label"]  = "33- TOTAL DAS OUTRAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO (30+31+32)";
$aTotalizadores[28]["label"]  = "34- TOTAL GERAL DAS DESPESAS COM MDE (23 + 33)";

for ($i = 0; $i < count($aTotalizadores); $i++) {
 
  $aTotalizadores[$i]["valor"] = 0;
 
}
/*
 * Soma dos Totalizadores
 */
$aTotalizadores[1]["valor"]  = $aLinhasRelatorio[1]->nValorPrevisto+$aLinhasRelatorio[2]->nValorPrevisto+
                               $aLinhasRelatorio[3]->nValorPrevisto+$aLinhasRelatorio[4]->nValorPrevisto+
                               $aLinhasRelatorio[5]->nValorPrevisto;
                               
$aTotalizadores[2]["valor"]  = $aLinhasRelatorio[6]->nValorPrevisto+$aLinhasRelatorio[7]->nValorPrevisto+
                               $aLinhasRelatorio[9]->nValorPrevisto+$aLinhasRelatorio[8]->nValorPrevisto+
                               $aLinhasRelatorio[10]->nValorPrevisto;                               
                               
$aTotalizadores[3]["valor"]  = $aLinhasRelatorio[11]->nValorPrevisto+$aLinhasRelatorio[12]->nValorPrevisto+
                               $aLinhasRelatorio[13]->nValorPrevisto+$aLinhasRelatorio[14]->nValorPrevisto+
                               $aLinhasRelatorio[15]->nValorPrevisto;

$aTotalizadores[4]["valor"]  = $aLinhasRelatorio[16]->nValorPrevisto+$aLinhasRelatorio[17]->nValorPrevisto+
                               $aLinhasRelatorio[19]->nValorPrevisto+$aLinhasRelatorio[18]->nValorPrevisto+
                               $aLinhasRelatorio[20]->nValorPrevisto;      

$aTotalizadores[5]["valor"]  = $aLinhasRelatorio[21]->nValorPrevisto+$aLinhasRelatorio[22]->nValorPrevisto+
                               $aLinhasRelatorio[23]->nValorPrevisto+$aLinhasRelatorio[24]->nValorPrevisto+
                               $aLinhasRelatorio[25]->nValorPrevisto;


$aTotalizadores[7]["valor"]  = $aLinhasRelatorio[26]->nValorPrevisto+$aLinhasRelatorio[27]->nValorPrevisto;
                                                              
$aTotalizadores[6]["valor"]  = $aLinhasRelatorio[30]->nValorPrevisto+$aLinhasRelatorio[31]->nValorPrevisto+
                               $aLinhasRelatorio[29]->nValorPrevisto+$aLinhasRelatorio[28]->nValorPrevisto+
                               $aLinhasRelatorio[32]->nValorPrevisto+$aLinhasRelatorio[33]->nValorPrevisto+
                               $aTotalizadores[7]["valor"];                                                                  
                                                              
$aTotalizadores[0]["valor"]  = $aTotalizadores[1]["valor"]+$aTotalizadores[2]["valor"]+$aTotalizadores[3]["valor"]+
                               $aTotalizadores[4]["valor"]+$aTotalizadores[5]["valor"];
                                
                               
                               
$aTotalizadores[8]["valor"]  = $aLinhasRelatorio[35]->nValorPrevisto+$aLinhasRelatorio[36]->nValorPrevisto+
                               $aLinhasRelatorio[37]->nValorPrevisto;

$aTotalizadores[9]["valor"]  = $aLinhasRelatorio[38]->nValorPrevisto+$aLinhasRelatorio[39]->nValorPrevisto;


$aTotalizadores[10]["valor"] = $aLinhasRelatorio[42]->nValorPrevisto+$aLinhasRelatorio[43]->nValorPrevisto+
                               $aLinhasRelatorio[44]->nValorPrevisto+$aLinhasRelatorio[45]->nValorPrevisto+
                               $aLinhasRelatorio[46]->nValorPrevisto+$aLinhasRelatorio[47]->nValorPrevisto;
                               

$aTotalizadores[11]["valor"] = $aLinhasRelatorio[48]->nValorPrevisto+$aLinhasRelatorio[49]->nValorPrevisto+
                               $aLinhasRelatorio[50]->nValorPrevisto;
                                                              
$aTotalizadores[12]["valor"] = $aLinhasRelatorio[51]->nValorPrevisto+$aLinhasRelatorio[52]->nValorPrevisto;

$aTotalizadores[13]["valor"] = $aLinhasRelatorio[53]->nValorPrevisto+$aLinhasRelatorio[54]->nValorPrevisto;

$aTotalizadores[14]["valor"] = $aLinhasRelatorio[55]->nValorPrevisto+$aLinhasRelatorio[56]->nValorPrevisto;

$aTotalizadores[15]["valor"] = $aLinhasRelatorio[57]->nValorPrevisto+$aLinhasRelatorio[58]->nValorPrevisto;


$aTotalizadores[16]["valor"] = $aTotalizadores[0]["valor"]+$aTotalizadores[6]["valor"];	

$aTotalizadores[17]["valor"] = $aTotalizadores[8]["valor"]+$aTotalizadores[9]["valor"]+$aLinhasRelatorio[34]->nValorPrevisto+
                               $aLinhasRelatorio[40]->nValorPrevisto+$aLinhasRelatorio[41]->nValorPrevisto;	

                               
                               
$aTotalizadores[18]["valor"] = $aLinhasRelatorio[48]->nValorPrevisto - $aTotalizadores[10]["valor"];      

$aTotalizadores[19]["valor"] = $aTotalizadores[12]["valor"] + $aTotalizadores[13]["valor"];
if ($aTotalizadores[11]["valor"] > 0) {
  $aTotalizadores[20]["valor"] = (($aTotalizadores[12]["valor"]/$aTotalizadores[11]["valor"])*100);
}

$aTotalizadores[21]["valor"] = $aTotalizadores[14]["valor"]+$aTotalizadores[15]["valor"]+
                               $aLinhasRelatorio[59]->nValorPrevisto+$aLinhasRelatorio[60]->nValorPrevisto+
                               $aLinhasRelatorio[61]->nValorPrevisto+$aLinhasRelatorio[62]->nValorPrevisto;

$aTotalizadores[22]["valor"] = $aTotalizadores[18]["valor"];

$aTotalizadores[23]["valor"] = $aLinhasRelatorio[50]->nValorPrevisto;
  
$aTotalizadores[24]["valor"] = $aTotalizadores[22]["valor"]+$aTotalizadores[23]["valor"]+ 
                               $aLinhasRelatorio[63]->nValorPrevisto;
                               
$aTotalizadores[25]["valor"] = ($aTotalizadores[14]["valor"]+$aTotalizadores[15]["valor"])-$aTotalizadores[24]["valor"];
if ($aTotalizadores[16]["valor"] > 0) {
  $aTotalizadores[26]["valor"] = @($aTotalizadores[25]["valor"]/$aTotalizadores[16]["valor"])*100;
}

$aTotalizadores[27]["valor"] = $aLinhasRelatorio[64]->nValorPrevisto+$aLinhasRelatorio[65]->nValorPrevisto+
                               $aLinhasRelatorio[66]->nValorPrevisto;
                               
$aTotalizadores[28]["valor"] = $aTotalizadores[27]["valor"]+$aTotalizadores[21]["valor"];                               
                               
$pdf = new PDF("P", "mm", "A4"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina         = 1;
$pdf->addpage();
$pdf->setfont('arial','',7);
$pdf->cell(160,$alt,$sOrcAnexo,'B',0,"L",0);
$pdf->cell(30,$alt,'R$ 1,00','B',1,"R",0);
  //$pdf->cell(100,$alt,"",'RT',0,"C",0);
  //$pdf->cell(90,$alt,"VALOR",'LTB',1,"C",0);
$pdf->cell(160,$alt,"RECEITAS DO ENSINO",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
  //Início do Bloco <<RECEITA BRUTA DE IMPOSTOS>>
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"PREVISÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"RECEITA BRUTA DE IMPOSTOS","RB",0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'LB',1,"C",0);
  
for ($i = 1; $i <= 33; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "";  
  if ($i == 1) {
    
    $pdf->cell(160, $alt, $aTotalizadores[0]["label"]                 ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[0]["valor"],"f"),"L",1,"R");
    $pdf->cell(160, $alt, "  ".$aTotalizadores[1]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[1]["valor"],"f"),"L",1,"R");
    
  }
  if ($i == 6) {
    
    $pdf->cell(160, $alt, "  ".$aTotalizadores[2]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[2]["valor"],"f"),"L",1,"R");
    
  }
 if ($i == 11) {
    
    $pdf->cell(160, $alt, "  ".$aTotalizadores[3]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[3]["valor"],"f"),"L",1,"R");
    
  }
 if ($i == 16) {
    
    $pdf->cell(160, $alt, "  ".$aTotalizadores[4]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[4]["valor"],"f"),"L",1,"R");
    
  }
  if ($i == 21) {
    
    $pdf->cell(160, $alt, "  ".$aTotalizadores[5]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[5]["valor"],"f"),"L",1,"R");
    
  }
 if ($i == 26) {
    
   $pdf->cell(160, $alt, $aTotalizadores[6]["label"]            ,"R",0,"L");
   $pdf->cell(30 , $alt, db_formatar($aTotalizadores[6]["valor"],"f"),"L",1,"R");
   $pdf->cell(160, $alt, "  ".$aTotalizadores[7]["label"]            ,"R",0,"L");
   $pdf->cell(30 , $alt, db_formatar($aTotalizadores[7]["valor"],"f"),"L",1,"R");
    
  }
  if ($i < 28) {
    $sEspaco = "         ";
  }
  if ($i == 26 || $i == 27) {
    $sEspaco = "         ";
  }
  if ($i >= 28) {
    $sEspaco = "      ";
  }
  $pdf->cell(160,$alt,$sEspaco.$aLinhasRelatorio[$i]->o69_labelrel,0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,'f'),'L',1,"R",0);
  
}
$pdf->cell(160, $alt, $aTotalizadores[16]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[16]["valor"],"f"),"TBL",1,"R");  
  //Fim do Bloco <<RECEITA BRUTA DE IMPOSTOS>>
  //Linha em branco entre 

  //Início do Bloco <<OUTRAS RECEITAS DESTINADAS AO ENSINO>>
$pdf->cell(160,$alt,"",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"PREVISÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"OUTRAS RECEITAS DESTINADAS AO ENSINO","BR",0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'LB',1,"C",0);
for ($i = 34; $i <= 41; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "";  
  if ($i == 35) {
    
    $pdf->cell(160, $alt, "  ".$aTotalizadores[8]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[8]["valor"],"f"),"L",1,"R");
    
  }
  if ($i == 38) {
    
    $pdf->cell(160, $alt, "  ".$aTotalizadores[9]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[9]["valor"],"f"),"L",1,"R");
  }
  
  if ($i > 34 && $i < 40) {
    $sEspaco = "     ";
  }
  $pdf->cell(160,$alt,$sEspaco.$aLinhasRelatorio[$i]->o69_labelrel,0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,'f'),'L',1,"R",0);
  
}
$pdf->cell(160, $alt, $aTotalizadores[17]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[17]["valor"],"f"),"TBL",1,"R");  
  //Fim do Bloco <<OUTRAS RECEITAS DESTINADAS AO ENSINO>>
  $pdf->AddPage();
  //Início do Bloco <<RECEITAS DO FUNDEB>>
$pdf->cell(160,$alt,"FUNDEB",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
  
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"PREVISÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"RECEITAS DO FUNDEB",0,0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'L',1,"C",0);
$pdf->cell(160,$alt,"",'B',0,"C",0);
$pdf->cell(30,$alt,"",'LB',1,"C",0);
for ($i = 42; $i <= 50; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "   ";  
  if ($i == 42) {
    
    $pdf->cell(160, $alt, $aTotalizadores[10]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[10]["valor"],"f"),"L",1,"R");
    
  }
  if ($i == 48) {
    
    $pdf->cell(160, $alt, $aTotalizadores[11]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[11]["valor"],"f"),"L",1,"R");
  }
  
  $pdf->cell(160,$alt,$sEspaco.$aLinhasRelatorio[$i]->o69_labelrel,0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,'f'),'L',1,"R",0);
  
}
$pdf->cell(160, $alt, $aTotalizadores[18]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[18]["valor"],"f"),"TBL",1,"R");  
$pdf->cell(190, $alt,"[SE RESULTADO LÍQUIDO DA TRANSFÊRENCIA (12) > 0] ACRÉSCIMO RESULTANTE DAS TRANSFÊRENCIAS DO FUNDEB","T",1,"L");
$pdf->cell(190, $alt,"[SE RESULTADO LÍQUIDO DA TRANSFÊRENCIA (12) < 0] DECRÉSCIMO RESULTANTE DAS TRANSFÊRENCIAS DO FUNDEB","T",1,"L");

$pdf->cell(160,$alt,"",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"DOTAÇÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"DESPESAS DO FUNDEB",0,0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'L',1,"C",0);
$pdf->cell(160,$alt,"",'B',0,"C",0);
$pdf->cell(30,$alt,"",'LB',1,"C",0);
  
for ($i = 51; $i <= 54; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "   ";  
  if ($i == 51) {
    
    $pdf->cell(160, $alt, $aTotalizadores[12]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[12]["valor"],"f"),"L",1,"R");
    
  }
  if ($i == 53) {
    
    $pdf->cell(160, $alt, $aTotalizadores[13]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[13]["valor"],"f"),"L",1,"R");
  }
  
  $pdf->cell(160,$alt,$sEspaco.$aLinhasRelatorio[$i]->o69_labelrel,0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,'f'),'L',1,"R",0);
  
}
$pdf->cell(160, $alt, $aTotalizadores[19]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[19]["valor"],"f"),"TBL",1,"R"); 
$pdf->cell(160, $alt, $aTotalizadores[20]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[20]["valor"],"f"),"TBL",1,"R"); 
//  //Fim do Bloco <<DESPESAS DO FUNDEB>>
  //Linha em branco entre 
  //Início do Bloco <<DESPESAS COM AÇÕES TÍPICAS DE MDE>>
$pdf->cell(160,$alt,"",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"DOTAÇÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"DESPESAS COM AÇÕES TÍPICAS DE MDE",0,0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'L',1,"C",0);
$pdf->cell(160,$alt,"",'B',0,"C",0);
$pdf->cell(30,$alt,"",'LB',1,"C",0);
  
for ($i = 55; $i <= 62; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "   ";  
  if ($i == 55) {
    
    $pdf->cell(160, $alt, $aTotalizadores[14]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[14]["valor"],"f"),"L",1,"R");
    
  }
  if ($i == 57) {
    
    $pdf->cell(160, $alt, $aTotalizadores[15]["label"]            ,"R",0,"L");
    $pdf->cell(30 , $alt, db_formatar($aTotalizadores[15]["valor"],"f"),"L",1,"R");
    
  }
  if ($i > 58) {
    $sEspaco  = ""; 
  }
  $pdf->cell(160,$alt,$sEspaco.$aLinhasRelatorio[$i]->o69_labelrel,0,0,"L",0);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,'f'),'L',1,"R",0);
  
}

$pdf->cell(160, $alt, $aTotalizadores[21]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[21]["valor"],"f"),"TBL",1,"R");  
//}
  //Fim do Bloco <<DESPESAS COM AÇÕES TÍPICAS DE MDE>>
  //Linha em branco entre 
  //Início do Bloco <<DEDUÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL>>
$pdf->cell(160,$alt,"",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"DOTAÇÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"DEDUÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL",0,0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'L',1,"C",0);
$pdf->cell(160,$alt,"",'B',0,"C",0);
$pdf->cell(30,$alt,"",'LB',1,"C",0);

$pdf->cell(160, $alt, $aTotalizadores[22]["label"]            ,"TR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[22]["valor"],"f"),"TL",1,"R");
  
$pdf->cell(160, $alt, $aLinhasRelatorio[63]->o69_labelrel                       , "R",0,"L");
$pdf->cell(30 , $alt, db_formatar($aLinhasRelatorio[63]->nValorPrevisto,"f"), "L",1,"R");

$pdf->cell(160, $alt, $aTotalizadores[23]["label"]            ,"R",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[23]["valor"],"f"),"L",1,"R");

$pdf->cell(160, $alt, $aTotalizadores[24]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[24]["valor"],"f"),"TBL",1,"R");

$pdf->cell(160, $alt, $aTotalizadores[25]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[25]["valor"],"f"),"TBL",1,"R");

$pdf->cell(160, $alt, $aTotalizadores[26]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[26]["valor"],"f"),"TBL",1,"R");

$pdf->cell(160,$alt,"",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);

$pdf->AddPage();
  //Linha em branco entre 
  //Fim do Bloco <<DEDUÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL>>
  //Início do Bloco <<OUTRAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO>>
$pdf->cell(160,$alt,"",'BT',0,"C",0);
$pdf->cell(30,$alt,"",'BT',1,"C",0);
$pdf->cell(160,$alt,"",'T',0,"C",0);
$pdf->cell(30,$alt,"DOTAÇÃO",'LT',1,"C",0);
$pdf->cell(160,$alt,"OUTRAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO",0,0,"C",0);
$pdf->cell(30,$alt,"INICIAL",'L',1,"C",0);
$pdf->cell(160,$alt,"",'B',0,"C",0);
$pdf->cell(30,$alt,"",'LB',1,"C",0);

$pdf->cell(160, $alt, $aLinhasRelatorio[64]->o69_labelrel                       , "R",0,"L");
$pdf->cell(30 , $alt, db_formatar($aLinhasRelatorio[64]->nValorPrevisto,"f"), "L",1,"R");

$pdf->cell(160, $alt, $aLinhasRelatorio[65]->o69_labelrel                       , "R",0,"L");
$pdf->cell(30 , $alt, db_formatar($aLinhasRelatorio[65]->nValorPrevisto,"f"), "L",1,"R");

$pdf->cell(160, $alt, $aLinhasRelatorio[66]->o69_labelrel                       , "R",0,"L");
$pdf->cell(30 , $alt, db_formatar($aLinhasRelatorio[66]->nValorPrevisto,"f"), "L",1,"R");

$pdf->cell(160, $alt, $aTotalizadores[27]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[27]["valor"],"f"),"TBL",1,"R");

$pdf->cell(160, $alt, $aTotalizadores[28]["label"]            ,"TBR",0,"L");
$pdf->cell(30 , $alt, db_formatar($aTotalizadores[28]["valor"],"f"),"TBL",1,"R");
 //Fim do Bloco <<OUTRAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO>>

$pdf->ln();
$oRelataorioContabil->getNotaExplicativa($pdf,1);
  
$pdf->Output();

?>