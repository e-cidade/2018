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
  
  for ($iLinha = 10;$iLinha <= 19; $iLinha++) {
    
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
              if ($iLinha < 16) {
                
                if (isset($aSubFuncoes[$oDespesa->subfuncao])) {
                  $aSubFuncoes[$oDespesa->subfuncao]->valor += $oDespesa->aEstimativas[$iAnoRef];
                } else {
                       
                  $aSubFuncoes[$oDespesa->subfuncao]->subfuncao = $oDespesa->subfuncao; 
                  $aSubFuncoes[$oDespesa->subfuncao]->ano       = $iAnoRef; 
                  $aSubFuncoes[$oDespesa->subfuncao]->valor     = $oDespesa->aEstimativas[$iAnoRef];
                }
                
              }
            }
          }
        }
      } 
    }
  }
}
$aTotalizadores[0]->label = "RECEITA DE IMPOSTOS LÍQUIDA E TRANSFERENCIAS CONSTITUICIONAIS E LEGAIS(I)";
$aTotalizadores[0]->valor = 0;
for ($i = 1; $i <= 5; $i++) {
  $aTotalizadores[0]->valor += $aLinhasRelatorio[$i]->nValorPrevisto;
}
$aTotalizadores[1]->label = "DESPESAS CORRENTES";
$aTotalizadores[1]->valor = 0;
for ($i = 10; $i <= 12; $i++) {
  $aTotalizadores[1]->valor += $aLinhasRelatorio[$i]->nValorPrevisto;
}
$aTotalizadores[2]->label = "DESPESAS DE CAPITAL";
$aTotalizadores[2]->valor = 0;
for ($i = 13; $i <= 15; $i++) {
  $aTotalizadores[2]->valor += $aLinhasRelatorio[$i]->nValorPrevisto;
}
$aTotalizadores[3]->label = "DESPESAS COM SAÚDE ";
$aTotalizadores[3]->valor = $aTotalizadores[1]->valor+$aTotalizadores[2]->valor;

$aTotalizadores[4]->label = "(-) DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS Á SAÚDE";
$aTotalizadores[4]->valor = $aLinhasRelatorio[18]->nValorPrevisto + $aLinhasRelatorio[17]->nValorPrevisto+
                            $aLinhasRelatorio[19]->nValorPrevisto;
                            
$aTotalizadores[5]->label = "TOTAL DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE (V)";
$aTotalizadores[5]->valor = $aTotalizadores[3]->valor  - $aLinhasRelatorio[16]->nValorPrevisto-
                            $aTotalizadores[4]->valor;

$aTotalizadores[6]->label = "TOTAL";
$aTotalizadores[6]->valor = $aTotalizadores[0]->valor  + $aLinhasRelatorio[6]->nValorPrevisto+
                            $aLinhasRelatorio[7]->nValorPrevisto+$aLinhasRelatorio[8]->nValorPrevisto +
                            $aLinhasRelatorio[9]->nValorPrevisto;                             

$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'))); 
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
  $sOrcAnexo = 'Orçamento - Anexo X';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
  $sOrcAnexo = '';
}
$head2 = "MUNICÍPIO DE ".$oConfig->munic;
$head3 = $sModelo;
$head4 = "ANEXO DE  METAS FISCAIS";
$head5 = $iAnoRef;
$head6 = "DEMONSTRATIVO DA RECEITA DE IMPOSTOS LÍQUIDA E DAS DESPESAS PRÓPRIAS COM ASPS";
$head7 = "Versão: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";

$pdf = new PDF('P');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();
$iAlt             = 4;
$iFont            = 6; 

$pdf->setfont('arial','',$iFont);
  
$pdf->Cell(170,$iAlt,$sOrcAnexo,0,0,"L",0);
$pdf->Cell(20  ,$iAlt,"R$ 1,00", 0,1,"R");
$pdf->setfont('arial','',$iFont);
$pdf->cell(160,$iAlt*2, "RECEITAS","RTB",0,"C");
$pdf->cell(30,$iAlt,"PREVISÃO ","LT",1,"C");
$pdf->setx(170);	
$pdf->cell(30,$iAlt,"INICIAL","LB",1,"C");
/*
 * Receitas
 */
$pdf->setfont('arial','',6);
$pdf->cell(160, $iAlt, $aTotalizadores[0]->label                 ,"RT",0,"L");
$pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[0]->valor,"f"),"LT",1,"R");
for ($i= 1; $i <= 9; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "";
  if ($i < 6) {
    $sEspaco = "    ";  
  }
  $pdf->cell(160, $iAlt, $sEspaco.$aLinhasRelatorio[$i]->o69_labelrel          , "R", 0, "L");
  $pdf->cell(30 , $iAlt, db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,"f"), "L", 1, "R");
    
}
$pdf->cell(160, $iAlt, $aTotalizadores[6]->label            ,"RTB",0,"L");
$pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[6]->valor,"f"),"LTB",1,"R");

$pdf->cell(190,$iAlt, "","TB",1,"C");

/*
 * Despesas
 */
$pdf->setfont('arial','',$iFont);
$pdf->cell(160,$iAlt, "DESPESAS COM SAÚDE","RT",0,"C");
$pdf->cell(30,$iAlt,"DOTAÇÃO ","LT",1,"C");
$pdf->cell(160,$iAlt, "(Por Grupo de Natureza de Despesa)","RB",0,"C");
$pdf->cell(30,$iAlt,"INICIAL","LB",1,"C");
$pdf->setfont('arial','',6);
for ($i= 10; $i <= 15; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "    ";  
  if ($i == 10) {
    
    $pdf->cell(160, $iAlt, $aTotalizadores[1]->label                 ,"R",0,"L");
    $pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[1]->valor,"f"),"L",1,"R");
    
  }
  if ($i == 13) {
    
    $pdf->cell(160, $iAlt, $aTotalizadores[2]->label                 ,"R",0,"L");
    $pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[2]->valor,"f"),"L",1,"R");
    
  }
  $pdf->cell(160, $iAlt, $sEspaco.$aLinhasRelatorio[$i]->o69_labelrel          , "R", 0, "L");
  $pdf->cell(30 , $iAlt, db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,"f"), "L", 1, "R");
    
}
$pdf->cell(160, $iAlt, "TOTAL (IV)"                                                        ,"RTB",0,"L");
$pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[1]->valor+$aTotalizadores[2]->valor,"f"),"LTB",1,"R");

$pdf->cell(190,$iAlt, "","TB",1,"C");

$pdf->setfont('arial','',$iFont);
$pdf->cell(160,$iAlt*2, "DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE","RTB",0,"C");
$pdf->cell(30,$iAlt,"DOTAÇÃO ","LT",1,"C");
$pdf->SetX(170);
$pdf->cell(30,$iAlt,"INICIAL","LB",1,"C");
$pdf->setfont('arial','',6);
for ($i= 16; $i <= 19; $i++) {
  
  $pdf->setfont('arial','',6);
  $sEspaco = "";
 if ($i == 16) {
    
    $pdf->cell(160, $iAlt, $aTotalizadores[3]->label                 ,"R",0,"L");
    $pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[3]->valor,"f"),"L",1,"R");
    
  }  
  if ($i == 17) {
    
    $pdf->cell(160, $iAlt, $aTotalizadores[4]->label                 ,"R",0,"L");
    $pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[4]->valor,"f"),"L",1,"R");
    
  }
  if ($i >= 17) {
    $sEspaco = "    ";
  }
  $pdf->cell(160, $iAlt, $sEspaco.$aLinhasRelatorio[$i]->o69_labelrel          , "R", 0, "L");
  $pdf->cell(30 , $iAlt, db_formatar($aLinhasRelatorio[$i]->nValorPrevisto,"f"), "L", 1, "R");
    
}
$pdf->cell(160, $iAlt,$aTotalizadores[5]->label ,"RTB",0,"L");
$pdf->cell(30 , $iAlt, db_formatar($aTotalizadores[5]->valor,"f") ,"LTB",1,"R");

$pdf->cell(190,$iAlt, "","TB",1,"C");
$pdf->setfont('arial','',6);

$nTotal = 0;
if ($aTotalizadores[0]->valor != 0) {
  $nTotal = ($aTotalizadores[5]->valor/$aTotalizadores[0]->valor) * 100;
}

$pdf->cell(160,$iAlt, "PARTICIPAÇÃO DAS DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE NA RECEITA DE IMPOSTOS","RT",0,"l");
$pdf->cell(30,$iAlt, db_formatar($nTotal,"f") ,"LT",1,"R");
$pdf->cell(160,$iAlt, "LÍQUIDA E TRANSFERENCIAS CONSTITUCIONAIS E LEGAIS - LIMITE CONSTITUICIONAL (V/I)","RB",0,"L");
$pdf->cell(30,$iAlt," ","LB",1,"C");

$pdf->cell(190,$iAlt, "","TB",1,"C");

$pdf->setfont('arial','',$iFont);
$pdf->cell(160,$iAlt, "DESPESAS COM SAÚDE","RT",0,"C");
$pdf->cell(30,$iAlt,"DOTAÇÃO ","LT",1,"C");
$pdf->cell(160,$iAlt, "Por SubFunção","RB",0,"C");
$pdf->cell(30,$iAlt,"INICIAL","LB",1,"C");
$pdf->setfont('arial','',6);
$nValorSubfuncao =  0;
foreach ($aSubFuncoes as $oFuncao) {
  
  if ($pdf->GetY() > $pdf->h-30) {
    
    $pdf->AddPage();
    $pdf->setfont('arial','',$iFont);
    $pdf->cell(160,$iAlt, "DESPESAS COM SAÚDE","RT",0,"C");
    $pdf->cell(30,$iAlt,"DOTAÇÃO ","LT",1,"C");
    $pdf->cell(160,$iAlt, "Por SubFunção","RT",0,"C");
    $pdf->cell(30,$iAlt,"INICIAL","LB",1,"C");
    $pdf->setfont('arial','',6);    
    
  }
  $sSql         = "select  o53_descr from orcsubfuncao where o53_subfuncao = {$oFuncao->subfuncao}";
  $iDescrFuncao = db_utils::fieldsMemory(db_query($sSql),0)->o53_descr; 
  $pdf->cell(160, $iAlt, $iDescrFuncao          , "R", 0, "L");
  $pdf->cell(30 , $iAlt, db_formatar($oFuncao->valor,"f"), "L", 1, "R");
  $nValorSubfuncao += $oFuncao->valor;
  
}
$pdf->cell(160, $iAlt, "TOTAL"         , "RTB", 0, "L");
$pdf->cell(30 , $iAlt, db_formatar($nValorSubfuncao,"f"), "LTB", 1, "R");
$oRelataorioContabil->getNotaExplicativa($pdf,1,190);
$pdf->Output();
?>