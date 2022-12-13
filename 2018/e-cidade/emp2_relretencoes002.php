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
require("libs/db_utils.php");
include("classes/db_cgm_classe.php");
$oGet    = db_utils::postMemory($_GET);
if ($oGet->dataini == '' || $oGet->datafim  == '' ){
   db_redireciona('db_erros.php?fechar=true&db_erro=Parametros informados incorretos.');
}else{
  $datainip = explode("/", $oGet->dataini);
  $datainif = explode("/", $oGet->datafim);
  $dataini  = "{$datainip[2]}-{$datainip[1]}-{$datainip[0]}";
  $datafim  = "{$datainif[2]}-{$datainif[1]}-{$datainif[0]}";
}

$sqlRet = "select distinct e50_codord, 
                  e60_numemp, 
                  e60_numcgm,
                  z01_nome,
                  z01_cgccpf,
                  o58_codigo,
                  o15_descr,
                  e50_data,
                  (select sum(case when c71_coddoc in (5,35) then c70_valor else c70_valor*-1 end) 
                     from conlancamord 
                         inner join conlancam    on c70_codlan = c80_codlan
                         inner join conlancamdoc on c71_codlan = c70_codlan
                   where c80_codord = e50_codord
                     and c80_data between '$dataini' and '$datafim'
                     and c71_coddoc in (5, 6, 35, 36)
                  ) as c70_valor,
                  (select max(c70_data)
                     from conlancamord 
                         inner join conlancam    on c70_codlan = c80_codlan
                         inner join conlancamdoc on c71_codlan = c70_codlan
                   where  c80_codord = e50_codord
                     and c80_data between '$dataini' and '$datafim' 
                     and c71_coddoc in (5, 6, 35, 36)
                  ) as c70_data
             from pagordem 
                  inner join empempenho  on e60_numemp = e50_numemp 
                  inner join orcdotacao  on e60_coddot = o58_coddot 
                                        and e60_anousu = o58_anousu
                  inner join orctiporec  on o15_codigo = o58_codigo
                  inner join cgm         on z01_numcgm = e60_numcgm 
                  inner join cairetordem on k32_ordpag = e50_codord
                  inner join arrepaga    on k32_numpre = k00_numpre
            group by e50_codord, 
                     e60_numemp, 
                     e60_numcgm, 
                     z01_nome, 
                     z01_cgccpf,
                     o58_codigo,
                     o15_descr,
                     e50_data";
$sqlRet .= " union ";
$sqlRet .= "select distinct e50_codord, 
                  e60_numemp, 
                  e60_numcgm,
                  z01_nome,
                  z01_cgccpf,
                  o58_codigo,
                  o15_descr,
                  e50_data,
                  (select sum(case when c71_coddoc in (5,35) then c70_valor else c70_valor*-1 end) 
                     from conlancamord 
                         inner join conlancam    on c70_codlan = c80_codlan
                         inner join conlancamdoc on c71_codlan = c70_codlan
                   where c80_codord = e50_codord
                     and c80_data between '$dataini' and '$datafim'
                     and c71_coddoc in (5, 6, 35, 36)
                  ) as c70_valor,
                  (select max(c70_data)
                     from conlancamord 
                         inner join conlancam    on c70_codlan = c80_codlan
                         inner join conlancamdoc on c71_codlan = c70_codlan
                   where  c80_codord = e50_codord
                     and c80_data between '$dataini' and '$datafim' 
                     and c71_coddoc in (5, 6, 35, 36)
                  ) as c70_data
             from pagordem 
                  inner join empempenho  on e60_numemp             = e50_numemp 
                  inner join orcdotacao  on e60_coddot             = o58_coddot 
                                        and e60_anousu             = o58_anousu
                  inner join orctiporec  on o15_codigo             = o58_codigo
                  inner join cgm         on z01_numcgm             = e60_numcgm 
                  inner join cairetordem on cairetordem.k32_ordpag = e50_codord
                  inner join recibopaga  on cairetordem.k32_numpre = k00_numnov
                  inner join arrepaga    on recibopaga.k00_numpre  = arrepaga.k00_numpre
            group by e50_codord, 
                     e60_numemp, 
                     e60_numcgm, 
                     z01_nome, 
                     z01_cgccpf,
                     o58_codigo,
                     o15_descr,
                     e50_data";
$sqlRet = "select distinct * 
             from ($sqlRet) as x
            where c70_data   between '$dataini' and '$datafim'
            order by o58_codigo, 
                     e50_data,
                     e50_codord";

$rsOps       = pg_query($sqlRet) or die($sqlRet);
$iNumRowsOps = pg_num_rows($rsOps);

//die($sql1);
//echo db_criatabela($result1); echo $sql1."<br>";
//exit;

$pdf = new PDF("L","mm","A4"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$fonte    = 9;
$fonteSub = 7;
$head3    = "RELATÓRIO DE RETENÇÕES";

if(isset($dataini)&&$dataini!="--"&&isset($datafim)&&$datafim!="--") {
	$head6 = "Pagamentos entre : ".db_formatar($dataini,'d')." a ".db_formatar($datafim,'d');
}


if($iNumRowsOps == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem retenções de empenho no período de '.db_formatar($dataini,'d').' a '.db_formatar($datafim,'d'));
}

$alt               = 5;
$fundln            = 1;
$nTotalRecurso     = 0;
$nTotalRecursoPago = 0;
$nTotalRetidoRec   = 0;
$tot_pago          = 0;
$iRecursoAnt       = null;
$pdf->setfillcolor(243);
$pdf->AddPage();

for ($i=0; $i < $iNumRowsOps; $i++) {
 
    $oRetencoes  = db_utils::fieldsMemory($rsOps,$i);
    $sqlNumpres  = "";
    $sqlNumpres .= " select k00_numpre,";
    $sqlNumpres .= "        k00_receit,";
    $sqlNumpres .= "        k02_drecei,";
    $sqlNumpres .= "        k00_valor";
    $sqlNumpres .= "   from arrepaga ";
    $sqlNumpres .= "         inner join cairetordem on k32_numpre = k00_numpre";
    $sqlNumpres .= "         inner join tabrec      on k02_codigo = k00_receit";
    $sqlNumpres .= "  where k32_ordpag = {$oRetencoes->e50_codord} and";
    $sqlNumpres .= "        k00_dtpaga between '{$dataini}' and '{$datafim}'";
    $sqlNumpres .= " union ";
    $sqlNumpres .= "select arrepaga.k00_numpre,";
    $sqlNumpres .= "       arrepaga.k00_receit,";
    $sqlNumpres .= "       k02_drecei,";
    $sqlNumpres .= "       arrepaga.k00_valor";
    $sqlNumpres .= "   from arrepaga ";
    $sqlNumpres .= "         inner join recibopaga  on recibopaga.k00_numpre  = arrepaga.k00_numpre";
    $sqlNumpres .= "         inner join cairetordem on cairetordem.k32_numpre = recibopaga.k00_numnov";
    $sqlNumpres .= "         inner join tabrec      on tabrec.k02_codigo      = arrepaga.k00_receit";
    $sqlNumpres .= "  where k32_ordpag = {$oRetencoes->e50_codord} and";
    $sqlNumpres .= "        arrepaga.k00_dtpaga between '{$dataini}' and '{$datafim}'";
    $sql3           = " select k00_receit, k02_drecei, sum(k00_valor) as k00_valor ";
    $sql3          .= " from ({$sqlNumpres}) as x";
    $sql3          .= " group by k00_receit, k02_drecei ";
		$rsNumpres      = pg_query($sql3) or die($sql3);
    $iNumrowsNumpre = pg_num_rows($rsNumpres);
    
		
    if ($iNumrowsNumpre == 0) {
      continue;
    }
    //escrevemos o totalizador caso o recurso atual seje diferente d recurso anterior
    if ($iRecursoAnt != $oRetencoes->o58_codigo && $i != 0){
      
        $pdf->cell(240,$alt,"TOTAL DE OP'S PAGAS :","TB",0,"R");
        $pdf->SetFont('Arial','B',$fonte);
        $pdf->cell(40,$alt, trim(db_formatar($nTotalRecurso,"f")),"LTB",1,"R");
				$pdf->Ln(2);
        
				$pdf->SetFont('Arial','',$fonte);
        $pdf->cell(35,$alt,null,"B",0,"C",1);
				$pdf->cell(155,$alt,"RESUMO DE RECEITAS RETIDAS NO PERÍODO","B",1,"C",1);
        $aRecursoCorrente = @$aRetencoes[$iRecursoAnt];
        $nTotalRetido     = 0;
        
        if (is_array($aRecursoCorrente)) { 
     	  
          foreach ($aRecursoCorrente as $dot => $retencao){
  					//$pdf->setx(130);
            $pdf->cell(44,$alt,($dot),0,0,"R");
            $pdf->cell(111,$alt,$retencao["descr"],0,0,"L");
            $pdf->cell(35,$alt,trim(db_formatar($retencao["valor"],'f')),"0",1,"R");
            $nTotalRetido += $retencao["valor"];
  
          }
        }
        $pdf->cell(150,$alt,'TOTAL RETIDO NESSE RECURSO :',"TB",0,"R");
        $pdf->SetFont('Arial','B',$fonte);
        $pdf->cell(40 ,$alt,trim(db_formatar($nTotalRetido,"f")),"TBL",1,"R");
        $pdf->SetFont('Arial','',$fonte);
        $pdf->Ln();
				$nTotalRecurso = 0;
    }
    
    
    if ($iRecursoAnt != $oRetencoes->o58_codigo){
	     
			 $pdf->SetFont('Arial','B',$fonte);
	     $pdf->Cell(30, $alt,"RECURSO :",0,0,"L",1);
       $pdf->Cell(0, $alt,$oRetencoes->o15_descr,0,1,"L",1);
			 //$pdf->Ln();
	     $pdf->Cell(22, $alt,"OP","TBR",0,"C");
			 $pdf->Cell(22, $alt,"CGM","1",0,"C");
	     $pdf->Cell(111, $alt,"NOME",1,0,"C");
	     $pdf->Cell(35, $alt,"CNPJ/CPF",1,0,"C");
	     $pdf->Cell(30, $alt,"EMISSÃO",1,0,"C");
			 $pdf->Cell(30, $alt,"VALOR DA OP",1,0,"C");
	     $pdf->Cell(30, $alt,"VLR RETENCAO","TBL",0,"C");
			 $pdf->Ln();
    	 //$pdf->Cell(120, $alt,"","B",0,"C",0);
	     $pdf->Cell(44, $alt,"RECEITA","TBR",0,"C",0);
	     $pdf->Cell(111, $alt,"DESCRIÇÃO",1,0,"C",0);
	     if ( $oGet->selpag  == "s"){
				 $pdf->Cell(35, $alt,"VLR RETIDO","1",0,"C",0);
				 $pdf->Cell(90, $alt,null,"TBL",0,"C",0);
				 $pdf->Ln();
				 $pdf->Cell(30, $alt,"",0,0,"C",0);
				 $pdf->Cell(45, $alt,"DATA PGTO",1,0,"C",0);
				 $pdf->Cell(45, $alt,"VALOR PGTO",1,0,"C",0);
				 $pdf->Cell(45, $alt,"CONT PAGADORA","1",1,"C",0);
				 $pdf->Ln(1);
				 $pdf->SetFont('Arial','',$fonte);
			 }else{
				 $pdf->Cell(35, $alt,"VLR RETIDO","1",0,"C",0);
				 $pdf->Cell(90, $alt,null,"TBL",1,"C",0);
				 $pdf->Ln(1);
				 $pdf->SetFont('Arial','',$fonte);
			 }
    }
		
    //select para trazer o total das retencoes da ordem   
    $sSQLTotRetencao  = "select coalesce(sum(k00_valor),0) as total ";
    $sSQLTotRetencao .= "  from (select k00_valor";
    $sSQLTotRetencao .= "          from cairetordem ";
    $sSQLTotRetencao .= "               inner join arrepaga on k32_numpre = arrepaga.k00_numpre ";
    $sSQLTotRetencao .= " where k32_ordpag = {$oRetencoes->e50_codord}"; 
    $sSQLTotRetencao .= "   and (k00_dtpaga between '{$dataini}' and '{$datafim}')";
    $sSQLTotRetencao .= " union ";
    $sSQLTotRetencao .= "select arrepaga.k00_valor ";
    $sSQLTotRetencao .= "  from cairetordem ";
    $sSQLTotRetencao .= "       inner join recibopaga on k32_numpre            = recibopaga.k00_numnov ";
    $sSQLTotRetencao .= "       inner join  arrepaga  on recibopaga.k00_numpre = arrepaga.k00_numpre ";
    $sSQLTotRetencao .= " where k32_ordpag = {$oRetencoes->e50_codord}"; 
    $sSQLTotRetencao .="    and (arrepaga.k00_dtpaga between '{$dataini}' and '{$datafim}')";  
    $sSQLTotRetencao .="  ) as x ";
    $oVlrRetido       = db_utils::fieldsMemory(pg_query($sSQLTotRetencao),0);
	  $pdf->SetFont('Arial','',$fonte);
	  $pdf->Cell(22, $alt,$oRetencoes->e50_codord,0,0,"C",$fundln);
		$pdf->Cell(22, $alt,$oRetencoes->e60_numcgm,0,0,"C",$fundln);
    $pdf->Cell(111, $alt,$oRetencoes->z01_nome,0,0,"L",$fundln);
    $pdf->Cell(35, $alt,$oRetencoes->z01_cgccpf,0,0,"C",$fundln);
    $pdf->Cell(30, $alt,db_formatar($oRetencoes->e50_data,"d"),0,0,"C",$fundln);
		$pdf->Cell(30, $alt,db_formatar($oRetencoes->c70_valor,"f"),0,0,"R",$fundln);
    $pdf->Cell(30, $alt,db_formatar($oVlrRetido->total,'f'),0,1,"R",$fundln);
    
		$nTotalParc = 0;//valor parcial de cada retencao;
	  for ($j=0; $j < $iNumrowsNumpre; $j++) {
       	      
        $oNumpres = db_utils::fieldsMemory($rsNumpres,$j);
				$pdf->SetFont('Arial','',$fonteSub);
	      $pdf->Cell(44, $alt,$oNumpres->k00_receit,0,0,"C",$fundln);
	      $pdf->Cell(111,$alt,$oNumpres->k02_drecei,0,0,"L",$fundln);
	      $pdf->Cell(35, $alt,db_formatar($oNumpres->k00_valor,"f"),0,0,"R",$fundln);
	      $pdf->Cell(90, $alt,null,0,0,"R",$fundln);
				$pdf->Ln();
	      
				if (isset($aRetencoes[$oRetencoes->o58_codigo][$oNumpres->k00_receit])){
					 $aRetencoes[$oRetencoes->o58_codigo][$oNumpres->k00_receit]["valor"] += $oNumpres->k00_valor;
        }else{      
					 $aRetencoes[$oRetencoes->o58_codigo][$oNumpres->k00_receit]["valor"] = $oNumpres->k00_valor;
           $aRetencoes[$oRetencoes->o58_codigo][$oNumpres->k00_receit]["descr"] = $oNumpres->k02_drecei;
        }
	         
				if (isset($aRetRecurso[$oRetencoes->o58_codigo])){
					 $aRetRecurso[$oRetencoes->o58_codigo]["valor"] += $oNumpres->k00_valor;
        }else{      
					 $aRetRecurso[$oRetencoes->o58_codigo]["descr"] = $oRetencoes->o15_descr;
					 $aRetRecurso[$oRetencoes->o58_codigo]["valor"] = $oNumpres->k00_valor;
        }
	 
	 
	 }
   
  	
	  if ($oGet->selpag  == "s"){
       $sqlPagOp = "select c80_data,
												 	 c70_valor,
													 c82_reduz
								     from pagordemele
										       inner join pagordem     on e53_codord = e50_codord
													 inner join conlancamord on e53_codord = c80_codord
													 inner join conlancam    on c80_codlan = c70_codlan
													 inner join conlancampag on c80_codlan = c82_codlan
			  			   	   where e53_codord = ".$oRetencoes->e50_codord;
			 
			 $rsPagOp = pg_query($sqlPagOp) or die($sqlPagOp);
			 $iNumRowsPagOp = pg_num_rows($rsPagOp); 
			 
			 for ($ii = 0; $ii < $iNumRowsPagOp; $ii++){
					 $oPagOp = db_utils::fieldsMemory($rsPagOp,$ii);
					 $pdf->SetFont('Arial','',$fonteSub);
					 $pdf->Cell(30, $alt,"",0,0,"C",$fundln);
					 $pdf->Cell(45, $alt,db_formatar($oPagOp->c80_data,"d"),0,0,"C",$fundln);
					 $pdf->Cell(45, $alt,db_formatar($oPagOp->c70_valor,"f"),0,0,"R",$fundln);
					 $pdf->Cell(45, $alt,$oPagOp->c82_reduz,0,0,"C",$fundln);
					 $pdf->Cell(115,$alt,null,0,0,"R",$fundln);
					 $pdf->Ln();
					 $pdf->SetFont('Arial','',$fonte);
			 }
	}
	
	if ($fundln == 0){
			$fundln = 1;
	 }else{
			$fundln = 0;
	 }
	 
	 $nTotalRecurso     += $oRetencoes->c70_valor;
   $nTotalRecursoPago += $oRetencoes->c70_valor;
   $iRecursoAnt        = $oRetencoes->o58_codigo;
}
//  var_dump($aRetRecurso);
//	exit;
$pdf->cell(240,$alt,"TOTAL DE OP'S PAGAS :","TB",0,"R");
$pdf->SetFont('Arial','B',$fonte);
$pdf->cell(40,$alt, db_formatar($nTotalRecurso,"f"),"LTB",1,"R");
$pdf->Ln(2);

$pdf->SetFont('Arial','',$fonte);
$pdf->cell(35,$alt,null,"B",0,"C",1);
$pdf->cell(155,$alt,"RESUMO DE RECEITAS RETIDAS NO PERÍODO","B",1,"C",1);
$aRecursoCorrente = $aRetencoes[$iRecursoAnt];
$nTotalRetido     = 0;
ksort($aRecursoCorrente);
foreach ($aRecursoCorrente as $dot => $retencao){
  
    //$pdf->setx(130);
    $pdf->cell(44,$alt,($dot),0,0,"R");
    $pdf->cell(111,$alt,$retencao["descr"],0,0,"L");
    $pdf->cell(35,$alt,$retencao["valor"],"0",1,"R");
    $nTotalRetido += $retencao["valor"];

}
$pdf->cell(150,$alt,'TOTAL RETIDO NESSE RECURSO :',"TB",0,"R");
$pdf->SetFont('Arial','B',$fonte);
$pdf->cell(40 ,$alt,trim(db_formatar($nTotalRetido,"f")),"TBL",1,"R");
$pdf->Ln();

$nTotalRetido = 0;
foreach ($aRetRecurso as $ret => $descr){
   $nTotalRetido += $descr["valor"];
} 

$pdf->ln(2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(240,7,"TOTAL DE RETENÇÕES NO PERÍODO :","TB",0,"R",1);
$pdf->cell(40 ,7,db_formatar($nTotalRetido,"f"),"TBL",1,"R",1);

$pdf->AddPage();
$pdf->ln(2);
$pdf->SetFont('Arial','',$fonte);
$pdf->cell(30,$alt,null,"B",0,"C",1);
$pdf->cell(160,$alt,"TOTAL DE RETENÇÕES NO PERÍODO POR RECURSO","B",1,"C",1);

foreach ($aRetRecurso as $ret => $descr){
   
   $pdf->cell(44,$alt,$ret,0,0,"R");
   $pdf->cell(111,$alt, $descr["descr"],0,0,"L");
   $pdf->cell(35,$alt,trim(db_formatar($descr["valor"],"f")),0,1,"R");
} 

$pdf->SetFont('Arial','B',$fonte);
$pdf->cell(150,$alt,'TOTAL DE RETENÇÕES NO PERÍODO :',"TB",0,"R");
$pdf->cell(40 ,$alt,db_formatar($nTotalRetido,"f"),"TBL",1,"R");
$pdf->Output();

?>