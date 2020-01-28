<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$sWhere       = "";
$sOrder       = "";
$iInstituicao = db_getsession('DB_instit');

$sWhere .= " k17_instit = {$iInstituicao} ";
$sAnd    = " and ";

if (isset($oGet->anofolha)) {
	
	if (!empty($oGet->anofolha)) {
		
    $sWhere .= "{$sAnd} rh79_anousu = {$oGet->anofolha}";
    $sAnd    = " and ";		
	}
}

if (isset($oGet->mesfolha)) {
	
  if (!empty($oGet->mesfolha)) {
    
    $sWhere .= "{$sAnd} rh79_mesusu = {$oGet->mesfolha}";
    $sAnd    = " and ";   
  }
}

if (isset($oGet->ponto)) {
	
	$sPonto        = 'Todos';
	$sComplementar = 'Nenhum';
	if ($oGet->ponto == 'r14') {
		
		$sPonto  = 'Salário';
		$sWhere .= "{$sAnd} rh79_siglaarq = 'r14'";
		$sAnd    = " and ";
	} else if ($oGet->ponto == 'r48') {
    
		$sPonto  = 'Complementar';
		$sWhere .= "{$sAnd} rh79_siglaarq = 'r48'";
		$sAnd    = " and ";
		
		if (isset($oGet->complementar)) {
			
			$sComplementar = 'Todos';
			if ($oGet->complementar != '') {
				
				$sComplementar = "{$oGet->complementar}";
				$sWhere       .= "{$sAnd} rh79_seqcompl = {$oGet->complementar}";
				$sAnd          = " and ";
			}
		}
		
		$sWhere       .= "{$sAnd} rh79_seqcompl <> 0 ";
		$sAnd          = " and ";
			
  } else if ($oGet->ponto == 'r35') {
  	
  	$sPonto  = '13o. Salário';
    $sWhere .= "{$sAnd} rh79_siglaarq = 'r35'";
    $sAnd    = " and ";
  } else if ($oGet->ponto == 'r20') {
  	
  	$sPonto  = 'Rescisão';
    $sWhere .= "{$sAnd} rh79_siglaarq = 'r20'";
    $sAnd    = " and ";
  } else if ($oGet->ponto == 'r22') {
  	
  	$sPonto  = 'Adiantamento';
    $sWhere .= "{$sAnd} rh79_siglaarq = 'r22'";
    $sAnd    = " and ";
  }
}

if (isset($sWhere)) {
	
	$sWhere  = " where {$sWhere}                ";
	$sWhere .= "   {$sAnd} rh73_tiporubrica = 3 ";
}

if (isset($oGet->ordem)) {
	
	if ($oGet->ordem == 'rc') {
		
		$sOrdem = 'Recurso';
		$sOrder = ' order by rh79_recurso,rh82_slip';
	} else {
		
		$sOrdem = 'Tipo de Folha';
		$sOrder = ' order by rh79_siglaarq,rh82_slip';
	}
}

$sSqlConferencia  = "   select distinct                                                                                      ";
$sSqlConferencia .= "          rh79_recurso,                                                                                 ";
$sSqlConferencia .= "          rh82_slip,                                                                                    ";
$sSqlConferencia .= "          case rh79_siglaarq                                                                            ";
$sSqlConferencia .= "               when 'r14' then 'salario'                                                                ";
$sSqlConferencia .= "               when 'r20' then 'rescisao'                                                               ";
$sSqlConferencia .= "               when 'r48' then 'complementar'                                                           ";
$sSqlConferencia .= "               when 'r35' then '13. salario'                                                            ";
$sSqlConferencia .= "               when 'r22' then 'adiantamento'                                                           ";
$sSqlConferencia .= "               else 'nao cadastrada'                                                                    ";
$sSqlConferencia .= "          end as rh79_siglaarq,                                                                         ";
$sSqlConferencia .= "          rh79_seqcompl,                                                                                ";
$sSqlConferencia .= "          rh80_rhslipfolha,                                                                             ";
$sSqlConferencia .= "          k17_valor,                                                                                    ";
$sSqlConferencia .= "          k17_dtaut,                                                                                    ";
$sSqlConferencia .= "          k17_dtanu                                                                                    ";
$sSqlConferencia .= "   from pessoal.rhslipfolha                                                                             ";
$sSqlConferencia .= "        inner join pessoal.rhslipfolhaslip          on rh82_rhslipfolha           = rh79_sequencial     ";
$sSqlConferencia .= "        inner join pessoal.rhslipfolharhemprubrica  on rh79_sequencial            = rh80_rhslipfolha    ";
$sSqlConferencia .= "        inner join pessoal.rhempenhofolharubrica    on rh80_rhempenhofolharubrica = rh73_sequencial     ";
$sSqlConferencia .= "        inner join caixa.slip                       on k17_codigo                 = rh82_slip           ";
$sSqlConferencia .= "   {$sWhere}                                                                                            ";
$sSqlConferencia .= "   {$sOrder}                                                                                            ";

$rsSqlConferencia = db_query($sSqlConferencia);
$iNumrows         = pg_numrows($rsSqlConferencia);


if ($iNumrows == 0){
	
  $sMsg = "Não existem conferências para esse período $oGet->anofolha / $oGet->mesfolha";
  db_redireciona("db_erros.php?fechar=true&db_erro=$sMsg");
}

$head1 = "RELATÓRIO DE CONFERÊNCIAS DE SLIPS";
$head3 = "Ano/Mês: {$oGet->anofolha}/{$oGet->mesfolha}";
$head4 = "Ponto: {$sPonto}";
$head5 = "Nro. Complementar: {$sComplementar}";
$head6 = "Ordem: {$sOrdem}";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$lImprime    = true;
$lPreencher  = false;
$iAlt        = 4;
$nTotalValor = 0;

for ($iInd = 0; $iInd < $iNumrows; $iInd++) {
	
  $oConferencia = db_utils::fieldsMemory($rsSqlConferencia,$iInd);
         
  if ($pdf->gety() > $pdf->h - 30 || $lImprime == true ){
      
    $lImprime   = false;
    $lPreencher = false;
    
    $pdf->addpage("P");
    $pdf->SetFont('arial','b',8);

    $pdf->cell(20,$iAlt+1,"Ponto"                                                                           ,1,0,"C",1);  
    $pdf->cell(22,$iAlt+1,"Nro. Compl."                                                                     ,1,0,"C",1);
    $pdf->cell(22,$iAlt+1,"Recurso"                                                                         ,1,0,"C",1);
    $pdf->cell(22,$iAlt+1,"SLIP"                                                                            ,1,0,"C",1);
    $pdf->cell(22,$iAlt+1,"SLIP Folha"                                                                      ,1,0,"C",1);
    $pdf->cell(25,$iAlt+1,"Valor"                                                                           ,1,0,"C",1);
    $pdf->cell(30,$iAlt+1,"Dt. Autenticação"                                                                ,1,0,"C",1);
    $pdf->cell(30,$iAlt+1,"Dt. Anulação"                                                                    ,1,1,"C",1);    
  }
  
  if ($lPreencher == true) {
  	
    $lPreencher = false;
    $iCorFundo = 1;
  } else {
  	
    $lPreencher = true;
    $iCorFundo  = 0;
  }
  
  $pdf->SetFont('arial','',6);
  $pdf->cell(20,$iAlt,$oConferencia->rh79_siglaarq                                                 ,0,0,"C",$iCorFundo);  
  $pdf->cell(22,$iAlt,$oConferencia->rh79_seqcompl                                                 ,0,0,"C",$iCorFundo);
  $pdf->cell(22,$iAlt,$oConferencia->rh79_recurso                                                  ,0,0,"C",$iCorFundo);
  $pdf->cell(22,$iAlt,$oConferencia->rh82_slip                                                     ,0,0,"C",$iCorFundo);
  $pdf->cell(22,$iAlt,$oConferencia->rh80_rhslipfolha                                              ,0,0,"C",$iCorFundo);
  $pdf->cell(25,$iAlt,db_formatar($oConferencia->k17_valor,'f')                                    ,0,0,"R",$iCorFundo);
  $pdf->cell(30,$iAlt,db_formatar($oConferencia->k17_dtaut,'d')                                    ,0,0,"C",$iCorFundo);
  $pdf->cell(30,$iAlt,db_formatar($oConferencia->k17_dtanu,'d')                                    ,0,1,"C",$iCorFundo); 
  
  $nTotalValor = ($nTotalValor+$oConferencia->k17_valor);
}

$pdf->cell(193,1,""                                                                                         ,0,1,"C",0);

$pdf->SetFont('arial','b',6);
$pdf->cell(108,$iAlt+1,"Soma:"                                                                              ,0,0,"R",0);

$pdf->SetFont('arial','',6);
$pdf->cell(25,$iAlt+1,db_formatar($nTotalValor,'f')                                                         ,0,1,"R",0);

$pdf->Output();
?>