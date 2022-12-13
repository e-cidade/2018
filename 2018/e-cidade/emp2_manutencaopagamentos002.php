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

/**
 * 
 * @author I
 * @revision $Author: dbjeferson $
 * @version $Revision: 1.7 $
 */
require("fpdf151/pdf.php");
include("libs/db_utils.php");
include("libs/JSON.php");
require("classes/db_retencaoreceitas_classe.php");
include("model/agendaPagamento.model.php");
$oJson    = new services_json();
$oAgenda = new agendaPagamento();
$oParam   = $oJson->decode(str_replace("\\","",$_GET["json"]));
$sJoin   = '';
$sWhereIni  = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
$sWhereIni .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";
$sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
$sWhereIni .= " and e80_data  <= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
$sWhereIni .= " and e60_instit = ".db_getsession("DB_instit");
$sWhere     = $sWhereIni; 
$oAgenda->setOrdemConsultas("e82_codord, e81_codmov");

if ($oParam->orderBy == "cgm.z01_nome") {
  $oAgenda->setOrdemConsultas("case when trim(a.z01_nome)   is not null then a.z01_nome   else cgm.z01_nome end");
}

if ($oParam->iOrdemIni != '' && $oParam->iOrdemFim == "") {
  $sWhere .= " and e50_codord = {$oParam->iOrdemIni}";
} else if ($oParam->iOrdemIni != '' && $oParam->iOrdemFim != "") {
  $sWhere .= " and e50_codord between  {$oParam->iOrdemIni} and {$oParam->iOrdemFim}";
}
    
if ($oParam->dtDataIni != "" && $oParam->dtDataFim == "") {
  $sWhere .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->dtDataIni)))."'";
} else if ($oParam->dtDataIni != "" && $oParam->dtDataFim != "") {
      
  $dtDataIni = implode("-",array_reverse(explode("/",$oParam->dtDataIni)));
  $dtDataFim = implode("-",array_reverse(explode("/",$oParam->dtDataFim)));
  $sWhere .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";
      
} else if ($oParam->dtDataIni == "" && $oParam->dtDataFim != "") {
      
  $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->dtDataFim)));
  $sWhere    .= " and e50_data <= '{$dtDataFim}'";
}
    
    //Filtro para Empenho
if ($oParam->iCodEmp!= '') {
      
  if (strpos($oParam->iCodEmp,"/")) {
        
    $aEmpenho = explode("/",$oParam->iCodEmp);
    $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";
        
  } else {
    $sWhere .= " and e60_codemp = '{$oParam->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
  }
      
}
    
//filtro para filtrar por credor
if ($oParam->iNumCgm != '') {
  $sWhere .= " and (e60_numcgm = {$oParam->iNumCgm})";
}
if ($oParam->iAutorizadas == 2) {
    
  $lAutorizadas      = true;
  if ($oParam->sDtAut != "") {
      
    $sDtAut   = implode("-", array_reverse(explode("/", $oParam->sDtAut)));
    $sWhere .= " and e42_dtpagamento = '{$sDtAut}'";
      
  }
  $sWhere .= " and e43_autorizado is true ";
  
} else if ($oParam->iAutorizadas == 3) {
    
  $sWhere .= " and e43_empagemov is null";
}
  
if ($oParam->iOPauxiliar != '') {
    
  $sWhere .= " and e42_sequencial = {$oParam->iOPauxiliar}";
}
if ($oParam->iRecurso != '') {
    
  $sWhere .= " and o15_codigo = {$oParam->iRecurso}";
}
       
$sJoin   .= " left join empagenotasordem    on e81_codmov         = e43_empagemov  "; 
$sJoin   .= " left join empageordem         on e43_ordempagamento = e42_sequencial ";
$sJoin   .= " left join saltes              on e83_conta          = k13_conta ";
$sJoin   .= " left join empageforma         on e97_codforma       = e96_codigo";
$sJoin   .= " left join pcfornecon          on e98_contabanco     = pc63_contabanco";
$sJoin   .= " left join empempaut           on e60_numemp         = e61_numemp";
$sJoin   .= " left join empautorizaprocesso on e150_empautoriza   = e61_autori";
$sCampos  = ",k13_conta,k13_descr, e96_descr,pc63_banco, pc63_agencia,pc63_agencia_dig,pc63_conta,pc63_conta_dig,e150_numeroprocesso";
$aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere,$sJoin,false,false, $sCampos);


$oPdf  = new PDF("L","mm","A4"); 
$oPdf->Open();
$oPdf->SetAutoPageBreak(0,1);
$oPdf->AliasNbPages();
$oPdf->SetFillColor(240);

$head1           = "Relatório de Manutençao de Agenda";
$head2           = "Ordens :{$oParam->iOrdemIni} a {$oParam->iOrdemFim}";
$head3           = "Data   :{$oParam->dtDataIni} a {$oParam->dtDataFim}";
$head4           = "Recurso:{$oParam->iRecurso}";
$head5           = "Credor :{$oParam->iNumCgm}";
$head6           = "OP Auxiliar :{$oParam->iOPauxiliar}";
$lEscreverHeader = true;
$lAddPage        = false;
$sFonte          = "Arial";
$oPdf->AddPage(); 
$nTamanhoTotalCelulas = 255;
$nTotalRetencoes = 0;

$nTotalOP          = 0;
$nTotalAutorizado  = 0;
$nTotalRetido      = 0;
$nTotalLiquido     = 0;
$iLinhas           = 0;  
foreach ($aOrdensAgenda as $oMovimento) {
  
  
 if ($oParam->lChequeArq != true) {
   
   if ($oMovimento->e90_codmov != '') {
     continue;
  }
   
 } 
 if ($oParam->lAtualizadas != true) {
   
    if ($oMovimento->e97_codmov != '' && $oMovimento->e90_codmov == "") {
      continue;
    }
 }
 
 
 if ($oParam->lNormais != true) {
   
   if ( $oMovimento->e97_codmov == '') {
     continue;
   }
 }
 
 $iLinhas++;
 if ($oPdf->Gety() > $oPdf->h - 25 || $lEscreverHeader) {
      
  if ($oPdf->Gety() > $oPdf->h - 25) {
     $oPdf->AddPage();
   }
   
   $oPdf->SetFont($sFonte, "b",6);
   $oPdf->cell(13,5,"Processo",1,0,"C",1);
   $oPdf->cell(10,5,"Mov.",1,0,"C",1);
   $oPdf->cell(15,5,"Empenho",1,0,"C",1);
   $oPdf->cell(10,5,"Recurso",1,0,"C",1);
   $oPdf->cell(10,5,"OP",1,0,"C",1);
   $oPdf->cell(27,5,"Cta Pag.",1,0,"C",1);
   $oPdf->cell(53,5,"Credor",1,0,"C",1);
   $oPdf->cell(30,5,"Conta",1,0,"C",1);
   $oPdf->cell(15,5,"Forma PGTO",1,0,"C",1);
   $oPdf->cell(15,5,"Dt Aut",1,0,"C",1);
   $oPdf->cell(20,5,"Valor OP.",1,0,"C",1);
   $oPdf->cell(20,5,"vlr Aut.",1,0,"C",1);
   $oPdf->cell(20,5,"Retenção",1,0,"C",1);
   $oPdf->cell(20,5,"Valor ",1,1,"C",1);
   $lEscreverHeader = false;
      
  }
  
  $oPdf->SetFont($sFonte, "",5);
  $oPdf->cell(13,5,$oMovimento->e150_numeroprocesso,"TBR",0,"R",0);
  $oPdf->cell(10,5,$oMovimento->e81_codmov,"TBR",0,"R",0);
  $oPdf->cell(15,5,"{$oMovimento->e60_codemp}/{$oMovimento->e60_anousu}","TBR",0,"R");
  $oPdf->cell(10,5,$oMovimento->o15_codigo,"TBR",0,"R");
  $oPdf->cell(10,5,$oMovimento->e50_codord,"TBR",0,"R");
  $oPdf->cell(27,5,substr("{$oMovimento->k13_conta} - {$oMovimento->k13_descr}",0,25),"TBR",0,"L");
  $oPdf->cell(53,5,substr("{$oMovimento->z01_numcgm} - {$oMovimento->z01_nome}", 0, 40),"TBR", 0, "L");
  $sContaBanco = "";
  if ($oMovimento->pc63_banco != "") {
    
    if (trim($oMovimento->pc63_agencia_dig) != ""){
       $oMovimento->pc63_agencia_dig = "/".$oMovimento->pc63_agencia_dig;
    }
    if (trim($oMovimento->pc63_conta_dig) != ""){
      $oMovimento->pc63_conta_dig = "/".$oMovimento->pc63_conta_dig;
    }
    $sContaBanco  = "{$oMovimento->pc63_banco} - {$oMovimento->pc63_agencia}";
    $sContaBanco .= "{$oMovimento->pc63_agencia_dig} - {$oMovimento->pc63_conta}{$oMovimento->pc63_conta_dig}";
    
  }
  $oPdf->cell(30,5, $sContaBanco,"TBR",0,"L");
  $oPdf->cell(15,5,$oMovimento->e96_descr, "TBR", 0, "L");
  $oPdf->cell(15,5, $oMovimento->e42_sequencial." (".db_formatar($oMovimento->e42_dtpagamento,"d").")", "TBR", 0, "C");
  $oPdf->cell(20,5, trim(db_formatar($oMovimento->e53_valor,"f")), "TBR", 0, "R");
  $oPdf->cell(20,5, trim(db_formatar($oMovimento->e81_valor,"f")), "TBR", 0, "R");
  $oPdf->cell(20,5, trim(db_formatar($oMovimento->valorretencao,"f")), "TBR", 0, "R");
  $oPdf->cell(20,5,trim(db_formatar($oMovimento->e81_valor - $oMovimento->valorretencao,"f")),"TBL",1,"R");
  $nTotalOP         += $oMovimento->e53_valor;
  $nTotalAutorizado += $oMovimento->e81_valor;
  $nTotalRetido     += $oMovimento->valorretencao;
  $nTotalLiquido    += ($oMovimento->e81_valor - $oMovimento->valorretencao);
  
}
$oPdf->SetFont($sFonte, "b",7);
$oPdf->cell(178,5,"Total de Registros: ".$iLinhas, "TBR",0,"R",0);
$oPdf->cell(20,5,"Totais","TBR",0,"R",0);
$oPdf->cell(20,5, trim(db_formatar($nTotalOP,"f")), "TBR", 0, "R");
$oPdf->cell(20,5, trim(db_formatar($nTotalAutorizado,"f")), "TBR", 0, "R");
$oPdf->cell(20,5, trim(db_formatar($nTotalRetido,"f")), "TBR", 0, "R");
$oPdf->cell(20,5,trim(db_formatar($nTotalLiquido,"f")),"TBL",1,"R");
$oPdf->ln();
$aTotais = $oAgenda->getTotaisAgenda($sWhere);
if ($oPdf->Gety() > $oPdf->h - 35+(count($aTotais)*5)) {
  $oPdf->AddPage();
}
$oPdf->SetFont($sFonte, "b",8);
$oPdf->cell(0,5,"Totalizadores","TB",1,"C",1);
$nTotalAtualizado    = 0;
$nTotalChequeArquivo = 0;
$nTotalCheque        = 0;
$nTotalArquivo       = 0;
$nTotalNaoAtualizado = 0;
foreach ($aTotais as $oTotal) {
  
  if ($oTotal->tipo != "NDA") {
    $nTotalAtualizado += $oTotal->valor; 
  } else {
   $nTotalNaoAtualizado += $oTotal->valor;  
  }
  
  $nTotalChequeArquivo  += $oTotal->transmissao+$oTotal->cheques; 
  $nTotalCheque         += $oTotal->cheques; 
  $nTotalArquivo        += $oTotal->transmissao; 
}

$oPdf->SetFont($sFonte, "",8);
$oPdf->cell(30,5,"Atualizados","TB",0,"C",0);
$oPdf->cell(30,5,trim(db_formatar($nTotalAtualizado,"f")),1,0,"R",0);
$oPdf->cell(30,5,"Com Cheque/Arquivo",1,0,"C",0);
$oPdf->cell(30,5,trim(db_formatar($nTotalChequeArquivo,"f")),1,0,"R",0);
$oPdf->cell(30,5,"Não Atualizados",1,0,"C",0);
$oPdf->cell(30,5,trim(db_formatar($nTotalNaoAtualizado,"f")),1,1,"R",0);

/**
 * escrevemos os totalizadores individuais
 */
$iAltura = $oPdf->getY();
foreach ($aTotais as $oTotal) {
 
  if ($oTotal->tipo != "NDA") {
    
    $oPdf->cell(30,5,$oTotal->tipo,"TBR",0,"L",0);
    $oPdf->cell(30,5,trim(db_formatar($oTotal->valor,"f")),1,1,"R",0);

  }
}

$oPdf->setXY(70, $iAltura);
$oPdf->cell(30,5, "Cheques","TBR",0,"L",0);
$oPdf->cell(30,5,trim(db_formatar($nTotalCheque,"f")),1,1,"R",0);
$oPdf->setX(70);
$oPdf->cell(30,5, "Transmissão","TBR",0,"L",0);
$oPdf->cell(30,5,trim(db_formatar($nTotalArquivo,"f")),1,1,"R",0);
$oPdf->Output();
?>
