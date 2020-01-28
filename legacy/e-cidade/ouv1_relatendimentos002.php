<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_db_depart_classe.php");

$cldepartamento = new cl_db_depart();

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);

//db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum processo encontrado!');

$head2 = 'Relatório de Atendimentos';
$head3 = 'Período:';
$head4 = 'Ouvidoria:';

$instit = db_getsession('DB_instit');

$sWhere = " ov01_instit = $instit ";

if(isset($dtfim) && isset($dtini) && trim($dtfim) != "" && trim($dtini) != ""){
	$head3 .= " ".$dtini." à "."$dtfim";
	$dtIni = implode('-',array_reverse(explode('/',$dtini)));
	$dtFim = implode('-',array_reverse(explode('/',$dtfim)));
	
	if($sWhere == ""){
		$sWhere .= " ov01_dataatend between '$dtIni' and '$dtFim'";	
	}else{
		$sWhere .= " and ov01_dataatend between '$dtIni' and '$dtFim' ";
	}
}

if(isset($ouvidoria) && trim($ouvidoria) != "") {
	if($ouvidoria==1){
		$head4 .= " Todas";
	}else{

		if($sWhere == ""){
			$sWhere .= " ov01_depart = $ouvidoria ";
		}else{
			$sWhere .= " and ov01_depart = $ouvidoria ";
		}
		
		$rsDepto = $cldepartamento->sql_record($cldepartamento->sql_query_file($ouvidoria));
		if($cldepartamento->numrows>0){
			db_fieldsmemory($rsDepto,0);
			$head4 .= " ".$coddepto." - ".$descrdepto; 		
		}
	}
}

/* verifica o tipo de situação, caso deva haver filtragem */  
if(isset($situacaoatendimento) && $situacaoatendimento!=0){
  $sWhere .= " and ov01_situacaoouvidoriaatendimento = {$situacaoatendimento} ";
}

$sOrdem = "ov01_depart, depto_destino ";

if($quebra == 0){
	$lQuebraPorTipo    = true;
  $lQuebraPorOuvidor = false; 
	
	if($sOrdem == ""){
		$sOrdem .= " tiporpocesso";
	}else{
		$sOrdem .= " ,tipoprocesso";
	}
}else if($quebra == 1){
	$lQuebraPorTipo    = false;
  $lQuebraPorOuvidor = true;
	if($sOrdem == ""){
			$sOrdem .= " ov01_usuario";
		}else{
		$sOrdem .= " ,ov01_usuario";
	}
}else if($quebra == 2){
	$lQuebraPorTipo    = false;
  $lQuebraPorOuvidor = false; 
  $sOrdem = "depto_destino ";
}

if($ordenacao == 0){
	if($sOrdem == ""){
		$sOrdem .= " ov01_numero";
	}else{
		$sOrdem .= " ,ov01_numero";
	}
}else if($ordenacao == 1){
if($sOrdem == ""){
		$sOrdem .= " situacao,ov01_numero";
	}else{
		$sOrdem .= " ,situacao,ov01_numero";
	}
}

$sQuery  = "   select ";
$sQuery .= "		      ov01_sequencial,";
$sQuery .= "		      fc_numeroouvidoria(ov01_sequencial) as ov01_numero,";
$sQuery .= "          ov01_anousu,";
$sQuery .= "		      ov01_tipoprocesso,";
$sQuery .= "		      ov01_depart,";
$sQuery .= "		      depart.descrdepto,";
$sQuery .= "		      p51_descr as tipoprocesso,";
$sQuery .= "		      ov01_dataatend as data,";
$sQuery .= "		      ov01_horaatend as hora,";
$sQuery .= "		      ov01_requerente as nomerequerente,";
$sQuery .= "		      ov09_protprocesso as processo,";
$sQuery .= "		      ov18_descricao as situacao,";
$sQuery .= "		      p42_descricao as formareclamacao,";
$sQuery .= "		      ov01_usuario,";
$sQuery .= "		      nome as nomeouvidor,";

$sQuery .= "		      (select p62_coddeptorec ||' - '|| descrdepto from proctransferproc inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran inner join db_depart on p62_coddeptorec = coddepto left join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran where p63_codproc = p58_codproc and ( p62_id_usorec = 0 or p62_id_usorec = 1 ) and p64_codtran is null ) as depto_destino_original,";
$sQuery .= "		      (select p62_coddeptorec ||' - '|| descrdepto from proctransferproc inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran inner join db_depart on p62_coddeptorec = coddepto left join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran where p63_codproc = p58_codproc and ( p62_id_usorec = 0 or p62_id_usorec = 1 ) and p64_codtran is null ) as depto_destino,";

$sQuery .= "		      case when p61_coddepto is null then ov01_depart ||' - '|| depart.descrdepto else p61_coddepto ||' - '|| pa.descrdepto end as localizacaoprocesso";
$sQuery .= "	   from ouvidoriaatendimento as oa";
$sQuery .= "	        inner join tipoproc as tp on tp.p51_codigo = oa.ov01_tipoprocesso";
$sQuery .= "	        left  join processoouvidoria as po on po.ov09_ouvidoriaatendimento = oa.ov01_sequencial";
$sQuery .= "	        left  join protprocesso on protprocesso.p58_codproc = po.ov09_protprocesso";
$sQuery .= "	        left  join procandam  on procandam.p61_codproc  = protprocesso.p58_codproc";
$sQuery .= "	                             and procandam.p61_codandam = protprocesso.p58_codandam";
$sQuery .= "	        inner join situacaoouvidoriaatendimento as soa on soa.ov18_sequencial = oa.ov01_situacaoouvidoriaatendimento";
$sQuery .= "	        inner join formareclamacao as fr on fr.p42_sequencial = oa.ov01_formareclamacao ";
$sQuery .= "          inner join db_usuarios as u on u.id_usuario = oa.ov01_usuario ";
$sQuery .= "	        inner join db_depart as depart on depart.coddepto = oa.ov01_depart ";
$sQuery .= "	        left  join db_depart as pa on pa.coddepto = p61_coddepto";
$sQuery .= "    where $sWhere ";
//$sQuery .= " order by $sOrdem ";

$sQuery = " select ov01_sequencial, ov01_numero, ov01_anousu, ov01_tipoprocesso, ov01_depart, descrdepto, tipoprocesso, data, hora, nomerequerente, processo, situacao, formareclamacao, ov01_usuario, nomeouvidor, case when depto_destino_original is null then 'Recebido' else 'Em transferência' end as status, case when depto_destino is null then localizacaoprocesso else depto_destino end as depto_destino, localizacaoprocesso from ( $sQuery ) as x ";
$sQuery = " select * from ( $sQuery ) as y order by $sOrdem";

//die($sQuery);

$rsQuery = db_query($sQuery);

if(pg_num_rows($rsQuery) > 0) {
	$aRelatorio = db_utils::getColectionByRecord($rsQuery,false,false,false);
}else{
	db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum atendimento encontrado!');
}

$iNumRows = count($aRelatorio);
$aDados = array();

$aTotalPorDepto    = array();
$aTotalPorTipo     = array();
$aTotalPorForma    = array();
$aTotalPorOuvidor  = array();
$aTotalPorSituacao = array();
$aDeptosImpressos  = array();
$aOuvidorImpressos = array();
$aTiposImpressos   = array();

$aTotalParcialPorTipo			= array();
$aTotalParcialPorForma		= array();
$aTotalParcialPorOuvidor	= array();
$aTotalParcialPorSituacao	= array();

$pdf_cabecalho = true;
$pdf = new PDF("L", "mm", "A4"); 
$pdf->Open();
$pdf->AliasNbPages(); 
$pdf->SetAutoPageBreak(false);
$pdf->AddPage('L');
$pdf->SetTextColor(0,0,0);
$pdf->setfillcolor(235);
$iNumRows 	= count($aRelatorio);

$pdf_cabecalho = true;
$background = 0;

$sDeptoAnterior = ($quebra == 2?"":$aRelatorio[0]->descrdepto . " - " ) . $aRelatorio[0]->depto_destino;

for ($iInd=0; $iInd<$iNumRows; $iInd++) {
	
	if (array_key_exists(($quebra == 2?"":$aRelatorio[$iInd]->descrdepto . " - " ) . $aRelatorio[$iInd]->depto_destino,$aTotalPorDepto)) {
	  $aTotalPorDepto[($quebra == 2?"":$aRelatorio[$iInd]->descrdepto . " - " ) . $aRelatorio[$iInd]->depto_destino]++;
	}else{
		$aTotalPorDepto[($quebra == 2?"":$aRelatorio[$iInd]->descrdepto . " - " ) . $aRelatorio[$iInd]->depto_destino] = 1;
	}  	
	if (array_key_exists($aRelatorio[$iInd]->tipoprocesso,$aTotalPorTipo)) {
    $aTotalPorTipo[$aRelatorio[$iInd]->tipoprocesso]++;
	}else {
		$aTotalPorTipo[$aRelatorio[$iInd]->tipoprocesso] = 1;
	}
	if (array_key_exists($aRelatorio[$iInd]->formareclamacao,$aTotalPorForma)) {
    $aTotalPorForma[$aRelatorio[$iInd]->formareclamacao]++;
	}else{
		$aTotalPorForma[$aRelatorio[$iInd]->formareclamacao] = 1;
	}
	if (array_key_exists($aRelatorio[$iInd]->nomeouvidor,$aTotalPorOuvidor)) {
    $aTotalPorOuvidor[$aRelatorio[$iInd]->nomeouvidor]++;
	}else{
    $aTotalPorOuvidor[$aRelatorio[$iInd]->nomeouvidor] = 1;
	}
	if (array_key_exists($aRelatorio[$iInd]->situacao,$aTotalPorSituacao)) {
    $aTotalPorSituacao[$aRelatorio[$iInd]->situacao]++;
	}else{
    $aTotalPorSituacao[$aRelatorio[$iInd]->situacao] = 1;
	}
	
	if($pdf->GetY() > $pdf->h - 25){
  		$pdf->AddPage('L');
  	}

  if (!array_key_exists( ($quebra == 2?"":str_pad( $aRelatorio[$iInd]->ov01_depart, 5, '0', STR_PAD_LEFT ) ) . $aRelatorio[$iInd]->depto_destino,$aDeptosImpressos)) {
  	//echo "<b>Quebra por Departamento : ".$aRelatorio[$iInd]->ov01_depart."</b><br>";
  	if($iInd > 0){
		  	//Imprimir resumo do departamento anterior
  			if($pdf->GetY() > $pdf->h - 25){
				  $pdf->AddPage('L');
				}
		  	$pdf->SetFont('Arial','B',8);
		  	$pdf->Ln(1);
				$pdf->Cell(100,5,"Total de Atendimentos *",0,0,"L",1);
				$pdf->Cell(20,5,$aTotalPorDepto[$sDeptoAnterior],0,1,"C",1);
						
  			//Imprime resumo por Tipo de Processo
  			if($tipoprocesso == 'S'){
					if($pdf->GetY() > $pdf->h - 30){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Tipo de Processo",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorTipo);
					$aPorTipo =  array_reverse($aTotalParcialPorTipo);
					foreach ($aPorTipo as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					
					}
  			}
  			//Imprime resumo por Forma de Reclamação
  			if($formareclamacao == 'S'){
	  			if($pdf->GetY() > $pdf->h - 25){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Forma de Reclamação",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorForma);
					$aPorForma = array_reverse($aTotalParcialPorForma);
					foreach ($aPorForma as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					
					}
  			}
  			//Imprime resumo por Situacão
  			if($situacao == 'S'){
	  			if($pdf->GetY() > $pdf->h - 25){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Situação",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorSituacao);
					$aPorTipo =  array_reverse($aTotalParcialPorSituacao);
					foreach ($aPorTipo as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					}
  			}
  			//Imprime resumo por Ouvidor
  			if($ouvidor == 'S'){
	  			if($pdf->GetY() > $pdf->h - 25){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Ouvidor",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorOuvidor);
					$aPorTipo =  array_reverse($aTotalParcialPorOuvidor);
					foreach ($aPorTipo as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					}
  			}
				$pdf->Ln();
				$aTotalParcialPorTipo 	= array();
				$aTotalParcialPorForma 	= array();
				$aTotalParcialPorOuvidor= array(); 	
				$aTotalParcialPorSituacao= array(); 	
  	}
  	$background = 1;
  	$pdf->SetFont('Arial','B',8);
    $pdf->setfillcolor(180);
    if ( $quebra < 2 ) {
      if($pdf->GetY() > $pdf->h - 25){
        $pdf->AddPage('L');
      }
    	$pdf->Cell(50,5,'DEPARTAMENTO: ',0,0,'L',1);
    	$pdf->Cell(150,5,$aRelatorio[$iInd]->ov01_depart." - ".$aRelatorio[$iInd]->descrdepto,0,1,'L',1);
    }
    if($pdf->GetY() > $pdf->h - 25){
      $pdf->AddPage('L');
    }
  	$pdf->Cell(50,5,'DEPARTAMENTO DESTINO: ',0,0,'L',1);
  	$pdf->Cell(150,5,$aRelatorio[$iInd]->depto_destino,0,1,'L',1);
    if ( $lQuebraPorOuvidor ){
  	  //echo "<b>Quebra por ouvidor : ".$aRelatorio[$iInd]->nomeouvidor."</b><br>";
  	  //$pdf->Ln();
      if($pdf->GetY() > $pdf->h - 25){
        $pdf->AddPage('L');
      }
  	  $pdf->Cell(50,5,'OUVIDOR: ',0,0,'L',1);
	  	$pdf->Cell(150,5,$aRelatorio[$iInd]->ov01_usuario." - ".$aRelatorio[$iInd]->nomeouvidor,0,1,'L',1);
	  	$pdf_cabecalho = true; 	
    }else if ( $lQuebraPorTipo ){
  	  //echo "<b>Quebra por tipo de processo : ".$aRelatorio[$iInd]->tipoprocesso."</b><br>";
  	  //$pdf->Ln();
      if($pdf->GetY() > $pdf->h - 25){
        $pdf->AddPage('L');
      }
  	  $pdf->Cell(50,5,'TIPO DE PROCESSO: ',0,0,'L',1);
	  	$pdf->Cell(150,5,$aRelatorio[$iInd]->ov01_tipoprocesso." - ".$aRelatorio[$iInd]->tipoprocesso,0,1,'L',1);
	  	$pdf_cabecalho = true;  	
    }
    $aDeptosImpressos[($quebra == 2?"":str_pad( $aRelatorio[$iInd]->ov01_depart, 5, '0', STR_PAD_LEFT )) . $aRelatorio[$iInd]->depto_destino] = ($quebra == 2?"":str_pad( $aRelatorio[$iInd]->ov01_depart, 5, '0', STR_PAD_LEFT )) . $aRelatorio[$iInd]->depto_destino;
    $aOuvidorImpressos[$aRelatorio[$iInd]->nomeouvidor] = $aRelatorio[$iInd]->nomeouvidor;
    $aTiposImpressos[$aRelatorio[$iInd]->tipoprocesso]  = $aRelatorio[$iInd]->tipoprocesso;
    $pdf->setfillcolor(235);
  }
	if($pdf->GetY() > $pdf->h - 25){
  		$pdf->AddPage('L');
  	}
  	$pdf->SetFont('Arial','B',8);
    $pdf->setfillcolor(180);
  if ($lQuebraPorOuvidor && !array_key_exists($aRelatorio[$iInd]->nomeouvidor,$aOuvidorImpressos) ){
  	//echo "<b>Quebra por ouvidor : ".$aRelatorio[$iInd]->nomeouvidor."</b><br>";
  	
  	$pdf->Ln();
  	
  	$pdf->Cell(30,5,'OUVIDOR: ',0,0,'L',0);
	  $pdf->Cell(10,5,$aRelatorio[$iInd]->ov01_usuario." - ".$aRelatorio[$iInd]->nomeouvidor,0,1,'L',1);
	  $pdf_cabecalho = true;   	
  }else if ($lQuebraPorTipo && !array_key_exists($aRelatorio[$iInd]->tipoprocesso,$aTiposImpressos) ){
  	//echo "<b>Quebra por tipo de processo : ".$aRelatorio[$iInd]->tipoprocesso."</b><br>";
  	$pdf->Ln();
  	$pdf->Cell(50,5,'TIPO DE PROCESSO: ',0,0,'L',1);
	  $pdf->Cell(150,5,$aRelatorio[$iInd]->ov01_tipoprocesso." - " . $aRelatorio[$iInd]->tipoprocesso,0,1,'L',1);
	  $pdf_cabecalho = true;  	
  }
  $pdf->setfillcolor(235);
	
  if($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true){
  	if($pdf->GetY() > $pdf->h - 25){
  		$pdf->AddPage('L');
  	}
		$pdf_cabecalho = false;  
		//$pdf->SetFont('Courier','',7);
	  $pdf->SetTextColor(0,0,0);
	  $pdf->setfillcolor(235);
	  $preenc = 0;
	  $linha = 1;
	  $bordat = 0;
	  $pdf->SetFont('Arial','b',7);
	  $pdf->ln(2);
		
		$pdf->Cell(20,5,"Atendimento"   ,1,0,"C",1);
		$pdf->Cell(60,5,"Tipo de Processo"   ,1,0,"C",1);
		$pdf->Cell(15,5,"Data"			         ,1,0,"C",1);
		$pdf->Cell(10,5,"Hora"               ,1,0,"C",1);
		$pdf->Cell(50,5,"Nome Requerente"    ,1,0,"C",1);
		$pdf->Cell(20,5,"Processo"           ,1,0,"C",1);
		$pdf->Cell(25,5,"Situação"           ,1,0,"C",1);
		$pdf->Cell(30,5,"Forma Reclamação"   ,1,0,"C",1);
		$pdf->Cell(50,5,"Ouvidor"   				 ,1,1,"C",1);
		$pdf->ln(1);
		$pdf_cabecalho == false;
	}
  //echo "Codigo atendimento : ".$aRelatorio[$iInd]->ov01_numero."<br>";  	
  	$pdf->SetFont('Arial','',7);
	  //$pdf->ln(2);
		$background = $background == 1 ? 0 : 1;
		
		$pdf->Cell(20,5,$aRelatorio[$iInd]->ov01_numero  					,0,0,"C",$background);
		$pdf->Cell(60,5,substr($aRelatorio[$iInd]->tipoprocesso		,0,38)		,0,0,"L",$background);
		$pdf->Cell(15,5,db_formatar($aRelatorio[$iInd]->data,'d')	,0,0,"C",$background);
		$pdf->Cell(10,5,$aRelatorio[$iInd]->hora									,0,0,"C",$background);
		$pdf->Cell(50,5,substr($aRelatorio[$iInd]->nomerequerente ,0,30) ,0,0,"L",$background);
		$pdf->Cell(20,5,$aRelatorio[$iInd]->processo							,0,0,"C",$background);
		$pdf->Cell(25,5,substr($aRelatorio[$iInd]->situacao				,0,20) ,0,0,"L",$background);
		$pdf->Cell(30,5,$aRelatorio[$iInd]->formareclamacao 			,0,0,"L",$background);
		$pdf->Cell(50,5,substr($aRelatorio[$iInd]->nomeouvidor		,0,32),0,1,"L",$background);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(100,5,"Departamento Atual: ". $aRelatorio[$iInd]->localizacaoprocesso  ,0,0,"L",$background);
		$pdf->Cell(50,5,"Status: ". $aRelatorio[$iInd]->status,0,1,"L",$background);
		$pdf->SetFont('Arial','',7);
		if($pdf->GetY() > $pdf->h - 25){
			$pdf->AddPage('L');
			$pdf_cabecalho = true;
		}		
		//Totais Parciais Por Tipo
		if (array_key_exists($aRelatorio[$iInd]->tipoprocesso,$aTotalParcialPorTipo)) {
	  	$aTotalParcialPorTipo[$aRelatorio[$iInd]->tipoprocesso]++;
		}else{
			$aTotalParcialPorTipo[$aRelatorio[$iInd]->tipoprocesso] = 1;
		}
		//Totais Parciais Por Forma
		if (array_key_exists($aRelatorio[$iInd]->formareclamacao,$aTotalParcialPorForma)) {
	  	$aTotalParcialPorForma[$aRelatorio[$iInd]->formareclamacao]++;
		}else{
			$aTotalParcialPorForma[$aRelatorio[$iInd]->formareclamacao] = 1;
		}
		//Totais Parciais Por Ouvidor
		if (array_key_exists($aRelatorio[$iInd]->nomeouvidor,$aTotalParcialPorOuvidor)) {
	  	$aTotalParcialPorOuvidor[$aRelatorio[$iInd]->nomeouvidor]++;
		}else{
			$aTotalParcialPorOuvidor[$aRelatorio[$iInd]->nomeouvidor] = 1;
		}
		//Totais Parciais Por Ouvidor
		if (array_key_exists($aRelatorio[$iInd]->situacao,$aTotalParcialPorSituacao)) {
	  	$aTotalParcialPorSituacao[$aRelatorio[$iInd]->situacao]++;
		}else{
			$aTotalParcialPorSituacao[$aRelatorio[$iInd]->situacao] = 1;
		}
		
  $aDeptosImpressos[ ($quebra == 2?"":str_pad( $aRelatorio[$iInd]->ov01_depart, 5, '0', STR_PAD_LEFT )) . $aRelatorio[$iInd]->depto_destino] = ($quebra == 2?"":str_pad( $aRelatorio[$iInd]->ov01_depart, 5, '0', STR_PAD_LEFT )) . $aRelatorio[$iInd]->depto_destino;
  $aOuvidorImpressos[$aRelatorio[$iInd]->nomeouvidor] = $aRelatorio[$iInd]->nomeouvidor;
  $aTiposImpressos[$aRelatorio[$iInd]->tipoprocesso]  = $aRelatorio[$iInd]->tipoprocesso;
  
  $sDeptoAnterior = ($quebra == 2?"":$aRelatorio[$iInd]->descrdepto . " - " ) . $aRelatorio[$iInd]->depto_destino;

//  echo $aRelatorio[$iInd]->ov01_numero . " - " . $sDeptoAnterior . "<br>";
  
}
//exit;

if($ouvidoria > 0) {
	//Imprimir resumo do departamento anterior
  			if($pdf->GetY() > $pdf->h - 25){
				  $pdf->AddPage('L');
				}
		  	$pdf->SetFont('Arial','B',8);
		  	$pdf->Ln(1);
				$pdf->Cell(100,5,"Total de Atendimentos **",0,0,"L",1);
				$pdf->Cell(20,5,$aTotalPorDepto[$sDeptoAnterior],0,1,"C",1);
						
  			//Imprime resumo por Tipo de Processo
  			if($tipoprocesso == 'S'){
					if($pdf->GetY() > $pdf->h - 30){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Tipo de Processo",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorTipo);
					$aPorTipo =  array_reverse($aTotalParcialPorTipo);
					foreach ($aPorTipo as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					
					}
  			}
  			//Imprime resumo por Forma de Reclamação
  			if($formareclamacao == 'S'){
	  			if($pdf->GetY() > $pdf->h - 25){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Forma de Reclamação",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorForma);
					$aPorForma = array_reverse($aTotalParcialPorForma);
					foreach ($aPorForma as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					
					}
  			}
  			//Imprime resumo por Situacão
  			if($situacao == 'S'){
	  			if($pdf->GetY() > $pdf->h - 25){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Situação",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorSituacao);
					$aPorTipo =  array_reverse($aTotalParcialPorSituacao);
					foreach ($aPorTipo as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					}
  			}
  			//Imprime resumo por Ouvidor
  			if($ouvidor == 'S'){
	  			if($pdf->GetY() > $pdf->h - 25){
						$pdf->AddPage('L');
					}else{
						$pdf->Ln(1);
					}
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,5,"Ouvidor",0,0,"L",1);
					$pdf->Cell(20,5,"Total",0,1,"C",1);
					$pdf->SetFont('Arial','',7);
					
					$background = 1;
					asort($aTotalParcialPorOuvidor);
					$aPorTipo =  array_reverse($aTotalParcialPorOuvidor);
					foreach ($aPorTipo as $key=>$value){
						if($pdf->GetY() > $pdf->h - 25){
						  		$pdf->AddPage('L');
						}
						$background = $background == 1 ? 0 : 1;
						$pdf->Cell(100,5,$key,0,0,"L",$background);
						$pdf->Cell(20,5,$value,0,1,"C",$background);
					}
  			}
				$pdf->Ln();
	
}

$pdf->ln(1);
if($pdf->GetY() > $pdf->h - 25){
  		$pdf->AddPage('L');
}
$pdf->SetFont('Arial','B',8);
$pdf->ln();
$pdf->Cell(50,5,"Resumo",0,1,"L",0);
if($pdf->GetY() > $pdf->h - 25){
  		$pdf->AddPage('L');
}

//Imprime resumo por Departamento
$pdf->ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(100,5,"Total de Atendimentos ***",0,0,"L",1);
$pdf->Cell(20,5,"Total",0,1,"C",1);
$pdf->SetFont('Arial','',7);

$background = 1;
asort($aTotalPorDepto);
$aPorDepto = array_reverse($aTotalPorDepto);
$soma = 0;
foreach ($aPorDepto as $key=>$value){
	if($pdf->GetY() > $pdf->h - 25){
	  		$pdf->AddPage('L');
	}
	$soma += $value;
	$background = $background == 1 ? 0 : 1;
	$pdf->Cell(100,5,$key,0,0,"L",$background);
	$pdf->Cell(20,5,$value,0,1,"C",$background);

}
$pdf->SetFont('Arial','B',8);
$background = $background == 1 ? 0 : 1;
$pdf->Cell(100,5,'Total',0,0,"L",$background);
$pdf->Cell(20,5,$soma,0,1,"C",$background);


//Imprime resumo por Tipo de Processo
if($tipoprocesso == 'S'){	
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,5,"Tipo de Processo",0,0,"L",1);
	$pdf->Cell(20,5,"Total",0,1,"C",1);
	$pdf->SetFont('Arial','',7);
	
	$background = 1;
	asort($aTotalPorTipo);
	$aPorTipo =  array_reverse($aTotalPorTipo);
	foreach ($aPorTipo as $key=>$value){
		if($pdf->GetY() > $pdf->h - 25){
		  		$pdf->AddPage('L');
		}
		$background = $background == 1 ? 0 : 1;
		$pdf->Cell(100,5,$key,0,0,"L",$background);
		$pdf->Cell(20,5,$value,0,1,"C",$background);
	
	}
}
//Imprime resumo por Forma de Reclamação
if($formareclamacao == 'S'){
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,5,"Forma de Reclamação",0,0,"L",1);
	$pdf->Cell(20,5,"Total",0,1,"C",1);
	$pdf->SetFont('Arial','',7);
	
	$background = 1;
	asort($aTotalPorForma);
	$aPorForma = array_reverse($aTotalPorForma);
	foreach ($aPorForma as $key=>$value){
		if($pdf->GetY() > $pdf->h - 25){
		  		$pdf->AddPage('L');
		}
		$background = $background == 1 ? 0 : 1;
		$pdf->Cell(100,5,$key,0,0,"L",$background);
		$pdf->Cell(20,5,$value,0,1,"C",$background);
	
	}
}	
//Imprime resumo por Situação
if($situacao == 'S'){
	$pdf->Ln(1);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,5,"Situação",0,0,"L",1);
	$pdf->Cell(20,5,"Total",0,1,"C",1);
	$pdf->SetFont('Arial','',7);
	
	$background = 1;
	asort($aTotalPorSituacao);
	$aPorTipo =  array_reverse($aTotalPorSituacao);
	foreach ($aPorTipo as $key=>$value){
		if($pdf->GetY() > $pdf->h - 25){
		  		$pdf->AddPage('L');
		}
		$background = $background == 1 ? 0 : 1;
		$pdf->Cell(100,5,$key,0,0,"L",$background);
		$pdf->Cell(20,5,$value,0,1,"C",$background);
	}
}
//Imprime resumo por Ouvidor
if($ouvidor == 'S'){
	$pdf->Ln(1);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,5,"Ouvidor",0,0,"L",1);
	$pdf->Cell(20,5,"Total",0,1,"C",1);
	$pdf->SetFont('Arial','',7);
	
	$background = 1;
	asort($aTotalPorOuvidor);
	$aPorTipo =  array_reverse($aTotalPorOuvidor);
	foreach ($aPorTipo as $key=>$value){
		if($pdf->GetY() > $pdf->h - 25){
		  		$pdf->AddPage('L');
		}
		$background = $background == 1 ? 0 : 1;
		$pdf->Cell(100,5,$key,0,0,"L",$background);
		$pdf->Cell(20,5,$value,0,1,"C",$background);
	}
}
$pdf->Output();

?>