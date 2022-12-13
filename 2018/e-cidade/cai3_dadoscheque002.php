<?
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
include("libs/db_liborcamento.php");
include("libs/db_utils.php");

//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_GET_VARS);
$oParam = db_utils::postMemory($HTTP_GET_VARS);

$sql  = "select distinct e91_codcheque, ";
    $sql .= "  e86_cheque,	"; 
    $sql .= "  e91_cheque ,	"; 
    $sql .= "  case when cgmslip.z01_numcgm is null then cgmemp.z01_numcgm else cgmslip.z01_numcgm end  as numcgm,	"; 
    $sql .= "  case when cgmslip.z01_nome is null then cgmemp.z01_nome else cgmslip.z01_nome end  as credor, 				";
    $sql .= "  e86_data, 		";
    $sql .= "  case when e81_numemp <> 0 then e60_codemp||'/'||e60_anousu else 'slip' end  as empenho,	"; 
    $sql .= "  e83_descr,";
    $sql .= "  case when e89_codigo is null then e82_codord else e89_codigo end as codigo_origem,	"; 
    $sql .= "  case when cpreduzslip.c61_reduz is null then o58_codigo else cpreduzslip.c61_codigo end as recurso,	"; 
    $sql .= "  e91_valor,	";
    $sql .= "  c63_banco,	";
    $sql .= "  c60_codcon,	";
    $sql .= "  c60_descr,	";
    $sql .= "  o15_codigo,	";
    $sql .= "  o15_descr,	";
    $sql .= "  e83_descr,	";
    $sql .= "  contareduz.c61_reduz,	";
    $sql .= "  db90_descr,	";
    //$sql .= "  c63_conta||'-'||c63_dvconta as c63_conta,	";
    //$sql .= "  c63_agencia||'-'||c63_dvagencia";
    $sql .= "  c63_agencia	,";
    $sql .= "  case when e91_ativo is true then 'Não' else 'Sim' end as anulado";
 		$sql .= "  from empageconfche	"; 
    $sql .= "  inner join empageconf   on e86_codmov  = e91_codmov	"; 
    $sql .= "  left  join empord       on e82_codmov  = e86_codmov 	";
    $sql .= "  left  join empageslip   on e86_codmov  = e89_codmov 	";
    $sql .= "  inner join empagemov    on e86_codmov  = e81_codmov 	";
    $sql .= "  left  join empempenho   on e60_numemp  = e81_numemp 	";
    $sql .= "  left  join orcdotacao   on e60_coddot  = o58_coddot 	";
    $sql .= "                         and e60_anousu  = o58_anousu 	";
    $sql .= "	 	";
    $sql .= "  left join slip          on slip.k17_codigo  = e89_codigo	"; 
    $sql .= "  left join conplanoreduz cpreduzslip on slip.k17_credito = c61_reduz	"; 
    $sql .= "                         and cpreduzslip.c61_anousu::integer  = extract(year from k17_data)::integer	"; 
    $sql .= "  left join cgm cgmemp    on e60_numcgm = cgmemp.z01_numcgm ";
    $sql .= "  left join slipnum       on slipnum.k17_codigo = slip.k17_codigo	"; 
    $sql .= "  left join cgm cgmslip   on slipnum.k17_numcgm  = cgmslip.z01_numcgm	"; 
    $sql .= "  inner join empagepag    on e85_codmov = e81_codmov 	";
    $sql .= "  inner join empagetipo   on e85_codtipo = e83_codtipo ";
    $sql .= "  inner join conplanoreduz contareduz  on contareduz.c61_reduz = e83_conta	"; 
    $sql .= "                                      and contareduz.c61_anousu = ".db_getsession('DB_anousu');
    $sql .= "  inner join conplano conta  on contareduz.c61_codcon = c60_codcon	"; 
    $sql .= "                                      and contareduz.c61_anousu = c60_anousu";
    $sql .= "  inner join orctiporec on contareduz.c61_codigo = o15_codigo  	";
    $sql .= "  inner join conplanoconta on contareduz.c61_codcon = c63_codcon ";
 		$sql .= "                          and contareduz.c61_anousu = c63_anousu ";
 		$sql .= "  left join db_bancos on c63_banco = db90_codban ";
// 		$sql .= " ".$sWhere." ";
 		$sql .= " where e91_cheque  = '".$oParam->e91_cheque ."'"; 
 		$sql .= "   and c63_banco   = '".$oParam->c63_banco  ."'";
 		$sql .= "   and c63_agencia = '".$oParam->c63_agencia."'";
 		$sql .= "   and c63_conta 	= '".$oParam->c63_conta."'";
// 		$sql .= "  order by e86_data, e91_cheque, e81_numemp "; 
 		
 		$sql .= " union ";
 		
 		$sql .= "select distinct e93_codcheque, ";
    $sql .= "  e88_cheque as e86_cheque,	"; 
    $sql .= "  e93_cheque as e91_cheque,	"; 
    $sql .= "  case when cgmslip.z01_numcgm is null then cgmemp.z01_numcgm else cgmslip.z01_numcgm end  as numcgm,	"; 
    $sql .= "  case when cgmslip.z01_nome is null then cgmemp.z01_nome else cgmslip.z01_nome end  as credor, 				";
    $sql .= "  e88_data as e86_data, 		";
    $sql .= "  case when e81_numemp <> 0 then e60_codemp||'/'||e60_anousu else 'slip' end  as empenho,	"; 
    $sql .= "  e83_descr,";
    $sql .= "  case when e89_codigo is null then e82_codord else e89_codigo end as codigo_origem,	"; 
    $sql .= "  case when cpreduzslip.c61_reduz is null then o58_codigo else cpreduzslip.c61_codigo end as recurso,	"; 
    $sql .= "  e93_valor,	";
    $sql .= "  c63_banco,	";
    $sql .= "  c60_codcon,	";
    $sql .= "  c60_descr,	";
    $sql .= "  o15_codigo,	";
    $sql .= "  o15_descr,	";
    $sql .= "  e83_descr,	";
    $sql .= "  contareduz.c61_reduz,	";
    $sql .= "  db90_descr,	";
    //$sql .= "  c63_conta||'-'||c63_dvconta as c63_conta,	";
    //$sql .= "  c63_agencia||'-'||c63_dvagencia";
    $sql .= "  c63_agencia	,";
    $sql .= "  'Sim' as anulado ";
 		$sql .= "  from empageconfchecanc	"; 
    $sql .= "  inner join empageconfcanc  on e88_codmov  = e93_codmov	"; 
    $sql .= "  left  join empord       on e82_codmov  = e88_codmov 	";
    $sql .= "  left  join empageslip   on e88_codmov  = e89_codmov 	";
    $sql .= "  inner join empagemov    on e88_codmov  = e81_codmov 	";
    $sql .= "  left  join empempenho   on e60_numemp  = e81_numemp 	";
    $sql .= "  left  join orcdotacao   on e60_coddot  = o58_coddot 	";
    $sql .= "                         and e60_anousu  = o58_anousu 	";
    $sql .= "	 	";
    $sql .= "  left join slip          on slip.k17_codigo  = e89_codigo	"; 
    $sql .= "  left join conplanoreduz cpreduzslip on slip.k17_credito = c61_reduz	"; 
    $sql .= "                         and cpreduzslip.c61_anousu::integer  = extract(year from k17_data)::integer	"; 
    $sql .= "  left join cgm cgmemp    on e60_numcgm = cgmemp.z01_numcgm ";
    $sql .= "  left join slipnum       on slipnum.k17_codigo = slip.k17_codigo	"; 
    $sql .= "  left join cgm cgmslip   on slipnum.k17_numcgm  = cgmslip.z01_numcgm	"; 
    $sql .= "  inner join empagepag    on e85_codmov = e81_codmov 	";
    $sql .= "  inner join empagetipo   on e85_codtipo = e83_codtipo ";
    $sql .= "  inner join conplanoreduz contareduz  on contareduz.c61_reduz = e83_conta	"; 
    $sql .= "                                      and contareduz.c61_anousu = ".db_getsession('DB_anousu');
    $sql .= "  inner join conplano conta  on contareduz.c61_codcon = c60_codcon	"; 
    $sql .= "                                      and contareduz.c61_anousu = c60_anousu";
    $sql .= "  inner join orctiporec on contareduz.c61_codigo = o15_codigo  	";
    $sql .= "  inner join conplanoconta on contareduz.c61_codcon = c63_codcon ";
 		$sql .= "                          and contareduz.c61_anousu = c63_anousu ";
 		$sql .= "  left join db_bancos on c63_banco = db90_codban ";
// 		$sql .= " ".$sWhere." ";
 		$sql .= " where e93_cheque  = '".$oParam->e91_cheque ."'"; 
 		$sql .= "   and c63_banco   = '".$oParam->c63_banco  ."'";
 		$sql .= "   and c63_agencia = '".$oParam->c63_agencia."'";
 		$sql .= "   and c63_conta 	= '".$oParam->c63_conta."'";
 		$sql .= "  order by e86_data, e91_cheque, empenho "; 



//echo $sql;
//die();

$rsSql = pg_query($sql);
if(pg_num_rows($rsSql) > 0){
	
	$oRelatorio = new stdClass();
	
	$aTemp = array();
 	$aTemp = db_utils::getColectionByRecord($rsSql,false,false,false);
 	$oRetorno->dados	= array();
 	$oDado 					 	= new stdClass();
 	$oDado->valor 			= 0;
 	$oDado->numcgm 			= $aTemp[0]->numcgm;
 	$oDado->credor 			= $aTemp[0]->credor;
 	$oDado->o15_codigo 	= $aTemp[0]->o15_codigo;
 	$oDado->o15_descr 	= $aTemp[0]->o15_descr;
 	$oDado->e83_descr 	= $aTemp[0]->e83_descr;
 	$oDado->c61_reduz 	= $aTemp[0]->c61_reduz;
 	$oDado->c60_descr 	= $aTemp[0]->c60_descr;
 	$oDado->c63_banco 	= $aTemp[0]->c63_banco;
 	$oDado->recurso 		= $aTemp[0]->recurso;
 	$oDado->e91_cheque	= $aTemp[0]->e91_cheque;
 	$oDado->db90_descr	= $aTemp[0]->db90_descr;
 	$oDado->empenho			= "";
 	$oDado->ordem				= "";
 	$oDado->slip				= "";
 	$virgula = "";
 	$virgula_slip = "";
 	$virgula_ordem = "";
 	$str_e91_codcheque = "";
 	foreach ($aTemp as $oRow){
 		$oDado->empenho  		.= $virgula.$oRow->empenho;
		if($oRow->empenho == 'slip'){
			$oDado->slip	.= $virgula_slip.$oRow->codigo_origem;
			$virgula_slip = ",";
		}else{
 			$oDado->ordem	.= $virgula_ordem.$oRow->codigo_origem;
 			$virgula_ordem = ",";
		}
 			$oDado->valor   		+= $oRow->e91_valor;
 			$str_e91_codcheque 	.= $virgula.$oRow->e91_codcheque;
 			$virgula = ",";
 	}
 	
 	$oRelatorio->dados[] = $oDado;
	
 	$oRelatorio->historico = array();
 	 		$sql  = "SELECT corrente.k12_data, ";
      $sql .= " case when corrente.k12_estorn is true then 'Estornado' else 'Autenticado' end as situacao, ";
      $sql .= " k11_tesoureiro, ";
      $sql .= " coremp.k12_codord ";
   		$sql .= " from corconf inner join "; 
      $sql .= " 							 corrente 			on corrente.k12_id 		=	corconf.k12_id "; 
      $sql .= "           								and corrente.k12_data 		= corconf.k12_data "; 
      $sql .= "           								and corrente.k12_autent 	= corconf.k12_autent "; 
      $sql .= "     inner join cfautent 			on corrente.k12_id 		= k11_id ";
      $sql .= "     inner join empageconfche 	on corconf.k12_codmov = e91_codcheque "; 
      $sql .= "     inner join coremp 				on coremp.k12_id 		 	= corrente.k12_id "; 
      $sql .= "     									 		and coremp.k12_data 	 	  = corrente.k12_data "; 
      $sql .= "     									 		and coremp.k12_autent 		= corrente.k12_autent "; 
  		$sql .= " 	where e91_codcheque in (".$str_e91_codcheque.")";
 			
  		$sql .= " union ";
  		
  		//echo "\n".$sql."\n";
  		//exit;
  		
  		$sql .= "SELECT corrente.k12_data, ";
      $sql .= " case when corrente.k12_estorn is true then 'Estornado' else 'Autenticado' end as situacao, ";
      $sql .= " k11_tesoureiro, ";
      $sql .= " coremp.k12_codord ";
   		$sql .= " from corconf inner join "; 
      $sql .= " 							 corrente 			on corrente.k12_id 		=	corconf.k12_id "; 
      $sql .= "           								and corrente.k12_data 		= corconf.k12_data "; 
      $sql .= "           								and corrente.k12_autent 	= corconf.k12_autent "; 
      $sql .= "     inner join cfautent 			on corrente.k12_id 		= k11_id ";
      $sql .= "     inner join empageconfchecanc 	on corconf.k12_codmov = e93_codcheque "; 
      $sql .= "     inner join coremp 				on coremp.k12_id 		 	= corrente.k12_id "; 
      $sql .= "     									 		and coremp.k12_data 	 	  = corrente.k12_data "; 
      $sql .= "     									 		and coremp.k12_autent 		= corrente.k12_autent "; 
  		$sql .= " 	where e93_codcheque in (".$str_e91_codcheque.")";
  		
 
  $rsSql = pg_query($sql);
 			
  if(pg_num_rows($rsSql) > 0){
		$oRelatorio->historico = db_utils::getColectionByRecord($rsSql,false,false,false); 			
 	}
}else{
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum dado encontrado para o cheque infromado ($oParam->e91_cheque)!");
}



$head2 = 'Relatório de consulta de cheque';
$head3 = 'Data de emissão: '.date('d/m/Y',db_getsession('DB_datausu'));
$head4 = '';
$head5 = '';

$pdf_cabecalho = true;
$pdf = new PDF("P", "mm", "A4"); 
$pdf->Open();
$pdf->AliasNbPages(); 
$pdf->SetTextColor(0,0,0);
$pdf->setfillcolor(235);

$pdf->AddPage('L');

$pdf->ln(2);
$pdf->SetFont('Arial','b',10);	
$pdf->Cell(32,5,"Dados do Cheque"    ,"B",1,"L",0);
$pdf->ln(2);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(30,5,"Número do cheque"   ,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,5,$oRelatorio->dados[0]->e91_cheque	,0,1,"R",0);
$pdf->SetFont('Arial','b',8);

$pdf->Cell(50,5,"Empenho(s)"        					 		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190,5,$oRelatorio->dados[0]->empenho,0,"L",0,0);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(50,5,"Ordem(s)"        					 		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190,5,$oRelatorio->dados[0]->ordem,0,"L",0,0);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(50,5,"Slip(s)"        					 		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190,5,$oRelatorio->dados[0]->slip,0,"L",0,0);
$pdf->SetFont('Arial','b',8);

$pdf->Cell(30,5,"Conta Pagadora"   		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,5,$oRelatorio->dados[0]->c61_reduz    ,0,0,"R",0);
$pdf->Cell(100,5,$oRelatorio->dados[0]->e83_descr   ,0,1,"L",0);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(30,5,"Banco"   		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,5,$oRelatorio->dados[0]->c63_banco    ,0,0,"R",0);
$pdf->Cell(100,5,$oRelatorio->dados[0]->db90_descr   ,0,1,"L",0);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(30,5,"Recurso"   		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,5,$oRelatorio->dados[0]->recurso    ,0,0,"R",0);
$pdf->Cell(100,5,$oRelatorio->dados[0]->o15_descr   ,0,1,"L",0);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(30,5,"Credor"   		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,5,$oRelatorio->dados[0]->numcgm    ,0,0,"R",0);
$pdf->Cell(100,5,$oRelatorio->dados[0]->credor   ,0,1,"L",0);
$pdf->SetFont('Arial','b',8);
$pdf->Cell(30,5,"Valor"   		,0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,5,"R$ ".db_formatar($oRelatorio->dados[0]->valor,'f'),0,1,"R",0);
$pdf->SetFont('Arial','b',8);

if($pdf->GetY() > $pdf->h - 25){
	$pdf->AddPage("P");
}

$pdf->ln(2);
$pdf->SetFont('Arial','b',10);	
$pdf->Cell(38,5,"Histórico do Cheque"    ,"B",1,"L",0);
$pdf->ln(2);

$iNumRows 	= count($oRelatorio->historico);
$lCabecalho = true;
$backGround = 1;
	
for($iInd=0; $iInd<$iNumRows; $iInd++){
	
	
	if($pdf->GetY() > $pdf->h - 25){
		$pdf->AddPage("P");
		$lCabecalho = true;
	}
	if($lCabecalho){
		imprime_cabecalho($pdf);
		$lCabecalho = false;
		$pdf->ln(1);
	}
	$backGround = $backGround == 1 ? 0 : 1;
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(20,5,db_formatar($oRelatorio->historico[$iInd]->k12_data,'d'),0,0,"C",$backGround);
	$pdf->Cell(60,5,$oRelatorio->historico[$iInd]->situacao ,0,0,"L",$backGround);
	$pdf->Cell(50,5,$oRelatorio->historico[$iInd]->k11_tesoureiro,0,1,"L",$backGround);
}
$backGround = $backGround == 1 ? 0 : 1;
$pdf->SetFont('Arial','b',7);
$pdf->Cell(20,5,"",	0,0,"C",$backGround);
$pdf->Cell(60,5,"Total de Registros:",0,0,"R",$backGround);
$pdf->Cell(50,5,$iNumRows,0,1,"L",$backGround);


$pdf->Output();

function imprime_cabecalho($pdf){
	$pdf->SetFont('Arial','b',7);
	$pdf->Cell(20,5,"Data"             		 ,"TB" ,0,"C",1);
	$pdf->Cell(60,5,"Tipo de Movimentação" ,"TBL",0,"C",1);
	$pdf->Cell(50,5,"Usuário"       			 ,"TBL",1,"C",1);
}


/*
$iNumRows 	= count($aDados);
$background = 0;
for($iInd=0; $iInd<$iNumRows; $iInd++){

	$pdf->SetAutoPageBreak(false);

	if($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true){
		$pdf_cabecalho = false;  
		$pdf->SetFont('Courier','',7);
	  $pdf->SetTextColor(0,0,0);
	  $pdf->setfillcolor(235);
	  $preenc = 0;
	  $linha = 1;
	  $bordat = 0;
	  $pdf->AddPage('L');
	  $pdf->SetFont('Arial','b',7);
	  $pdf->ln(2);
		
		$pdf->Cell(15,5,"Número"             ,1,0,"C",1);
		$pdf->Cell(60,5,"Tipo de Processo"   ,1,0,"C",1);
		$pdf->Cell(50,5,"Requerente"         ,1,0,"C",1);
		$pdf->Cell(55,5,"Depto Atual"        ,1,0,"C",1);
		$pdf->Cell(25,5,"Data Criação"       ,1,0,"C",1);
		$pdf->Cell(25,5,"Data Recebimento"   ,1,0,"C",1);
		$pdf->Cell(25,5,"Data Vencimento"    ,1,0,"C",1);
		$pdf->Cell(20,5,"Dias em Atraso"     ,1,1,"C",1);
	
		$pdf_cabecalho == false;
	}  

	if ( trim($aDados[$iInd]->ov15_dtfim) != '' && $aDados[$iInd]->ov15_dtfim < date('Y-m-d',db_getsession('DB_datausu'))) {
    $aDataPrevFin = explode('-',$aDados[$iInd]->ov15_dtfim);
    $iDataPrevFin = mktime(0,0,0,$aDataPrevFin[1],$aDataPrevFin[2],$aDataPrevFin[0]);
    $iDiasAtraso  = ceil(((db_getsession('DB_datausu')-$iDataPrevFin)/86400)-1);
	} else {
		$iDiasAtraso  = '';
	}
    
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(15,5,$aDados[$iInd]->p58_codproc                 ,0,0,"C",$background);
  $pdf->Cell(60,5,$aDados[$iInd]->p58_codigo."-".$aDados[$iInd]->p51_descr,0,0,"L",$background);
	$pdf->Cell(50,5,$aDados[$iInd]->p58_requer                  ,0,0,"L",$background);
	$pdf->Cell(55,5,$aDados[$iInd]->deptoatual                  ,0,0,"L",$background);
	$pdf->Cell(25,5,db_formatar($aDados[$iInd]->p58_dtproc,'d') ,0,0,"C",$background);
	$pdf->Cell(25,5,db_formatar($aDados[$iInd]->p61_dtandam,'d'),0,0,"C",$background);
	$pdf->Cell(25,5,db_formatar($aDados[$iInd]->ov15_dtfim,'d') ,0,0,"C",$background);
	$pdf->Cell(20,5,$iDiasAtraso                                ,0,1,"C",$background);
	$background = $background == 0 ? 1 : 0;
	

	$pdf->Ln(4);
	$pdf->Cell(245,5,'Total de Registros:','',0,"R",1);
	$pdf->Cell(30,5,$iNumRows,'',1,"R",1);
	$pdf->Output();
*/
?>