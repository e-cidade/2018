<?
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

set_time_limit(0);

require_once(modification("libs/db_sql.php"));
require_once(modification("fpdf151/pdf.php"));
require_once(modification("classes/db_tabativ_classe.php"));

db_postmemory($HTTP_POST_VARS);

$cltabativ    = new cl_tabativ;

$data         = implode("-",array_reverse(explode("/",trim($data))));
$data1        = implode("-",array_reverse(explode("/",trim($data1))));

$baixai       = implode("-",array_reverse(explode("/",trim($baixai))));
$baixaf       = implode("-",array_reverse(explode("/",trim($baixaf))));

$dataini      = implode("-",array_reverse(explode("/",trim($dataini))));
$datafim      = implode("-",array_reverse(explode("/",trim($datafim))));

$atividades   = trim($dados1.$dados2.$dados3.$dados4.$dados5.$dados6.$dados7.$dados8.$dados9.$dados10);
$ruas         = trim($recruas1.$recruas2.$recruas3.$recruas4.$recruas5.$recruas6.$recruas7.$recruas8.$recruas9.$recruas10);
$proce        = $processar;

$info         = "Total";
$txt_where    = "";
$where2       = "";
$where3       = "";
$whereativ    = '';
$whereruas    = '';
$and          = "";
$ordem        = '';
$dis          = "";
$inner        = "";
$inscrordem   = " ,q07_inscr";

if (($data != "--" && !empty($data)) && ($data1 != "--" && !empty($data1))) {
	
   $txt_where = $txt_where." and q02_dtinic  between '$data' and '$data1'  ";
   $data      = db_formatar($data,"d");
   $data1     = db_formatar($data1,"d");
   $info      = "De $data até $data1.";
} else if ($data != "--" && !empty($data)) {
	
   $txt_where = $txt_where." and q02_dtinic >= '$data'  ";
   $data      = db_formatar($data,"d");
   $info      = "Apartir de $data.";
} else if ($data1 != "--" && !empty($data1)) {
	
   $txt_where = $txt_where."and q02_dtinic <= '$data1'   ";
   $data1     = db_formatar($data1,"d");
   $info      = "Até $data1.";
}

if ($txt_where != "") {
  $and = "and";
}

if (isset($tativ) && $tativ == 'p' ) {
   $dis   = " distinct on (q07_inscr) ";
   $inner = " inner join ativprinc on tabativ.q07_inscr = ativprinc.q88_inscr 
                                  and tabativ.q07_seq   = ativprinc.q88_seq  ";
   $inscrordem = " ,q07_inscr ";
}

$sWhereBaixa    = '';
$sSituacaoInscricao = 'Todas';
if ($proce == 's') {
	
	$sSituacaoInscricao = 'Baixadas';
  $sWhereBaixa        = " and q02_dtbaix is not null ";
  
  if (($baixai != "--" && !empty($baixai)) && ($baixaf != "--" && !empty($baixaf))) {
    $sWhereBaixa = " and q02_dtbaix between '$baixai' and '$baixaf' ";
  } else if ($baixai != "--" && !empty($baixai)) {
    $sWhereBaixa = " and q02_dtbaix >= '$baixai' ";
  } else if ($baixaf != "--" && !empty($baixaf)) {
    $sWhereBaixa = "and q02_dtbaix <= '$baixaf' ";
  }
} elseif ($proce == 'n') {
	
	$sSituacaoInscricao = 'Não Baixadas';
  $sWhereBaixa        = " and q02_dtbaix is null ";
}

$sTipoInscricao = 'Todas';
if (trim($tipoinscricao) == 'per') {
	
	$sTipoInscricao = 'Permanentes';
	$where2         = " and q07_perman is true and q07_datafi is null ";
	
} else if (trim($tipoinscricao) == 'pro') {
	
	$sTipoInscricao = 'Provisórias';
	
	$where2 = " and q07_perman is false ";
  if (($dataini != "--" && !empty($dataini)) && ($datafim != "--" && !empty($datafim))) {
    $where3 = " and q07_datafi between '{$dataini}' and '{$datafim}' ";
  } else if ($dataini != "--" && !empty($dataini)) {
    $where3 = " and q07_datafi >= '{$dataini}' ";
  } else if ($datafim != "--" && !empty($datafim)) {
    $where3 = "and q07_datafi <= '{$datafim}' ";
  }
  
}

if(isset($ruas) && $ruas != ""){
	$whereruas = " and issruas.j14_codigo in ($ruas) ";
}

if(isset($atividades) && $atividades != ""){
	$whereativ = " and q03_ativ in ($atividades) ";
}

$total      = 0;
$totalgeral = 0;
$troca      = 1;

$head2 = "SECRETARIA DA FAZENDA";
$head3 = "Relatório de Inscrições Por Atividade";
$head4 = "Periodo $info";
$head5 = "Relatório $opcao";
$head6 = "Insrições: $sSituacaoInscricao";
$head7 = "Tipo de Inscrição: $sTipoInscricao";

if ($grupo == "r"){
    $ordem = ' j14_nome, z01_nome ';
}else if ($grupo == "a"){
    $ordem = ' q03_descr, z01_nome ';
}else if ($grupo == "c"){
  	$ordem = ' q12_descr, z01_nome ';
}else if ($grupo == "i"){
  	$ordem = ' z01_nome, q03_descr ';
}


//=======================================================================================================================================

$pdf = new pdf();
$pdf->SetFillColor(255);
$pdf->Open();
$pdf->AliasNbPages();

// tirei o $dis do sql... distinct on
$sql = "
			select  
			       issruas.j14_codigo, 
						 j14_nome, 
						 q03_ativ, 
						 q03_descr, 
						 q12_classe, 
						 q12_descr,
			       issbase.q02_inscr, 
						 z01_nome,
						 z01_cgccpf, 
						 q02_numero, 
						 q02_compl, 
						 z01_telef, 
						 q07_datain, 
						 q07_datafi,
						 q02_dtbaix
			from tabativ
				inner join issbase     on issbase.q02_inscr = tabativ.q07_inscr
				$inner
				inner join issruas     on issruas.q02_inscr = tabativ.q07_inscr
				inner join ruas        on issruas.j14_codigo = ruas.j14_codigo
				inner join ativid      on ativid.q03_ativ = tabativ.q07_ativ
				inner join cgm         on cgm.z01_numcgm = issbase.q02_numcgm
				inner join clasativ    on q03_ativ = q82_ativ
				inner join classe      on q82_classe = q12_classe
				left join tabativbaixa on tabativ.q07_inscr = tabativbaixa.q11_inscr
				                      and tabativ.q07_seq   = tabativbaixa.q11_seq
				where  q12_classe in ($classe1) $txt_where $where2 $whereativ $whereruas $whereativ $where3 $sWhereBaixa

					  group by  issruas.j14_codigo, 
						          j14_nome, 
											q03_ativ, 
											q03_descr, 
											q12_classe, 
											q12_descr,
			                issbase.q02_inscr, 
											q07_inscr, 
											z01_nome, 
											z01_cgccpf,
											q02_numero, 
											q02_compl, 
											z01_telef, 
											q07_datain, 
											q07_datafi,
											q02_dtbaix
                 
            order by  $ordem $inscrordem ";

$result = db_query($sql);
//db_criatabela($result);exit;             
$numlinhas = pg_numrows($result);
if(!isset($numlinhas) || $numlinhas == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foi encontrado nenhum registro para o filtro selecionado.');
}

$descrrua    = '';
$descrativ   = '';
$descrclasse = '';
$descrinscr  = '';

$totinscr    = 0;
$totativ     = 0;
$totclasse   = 0;
$totruas     = 0;
$corcab      = '210';

if ($opcao == 'analitico'){
	    
	for($i=0;$i<$numlinhas;$i++){
		
	    db_fieldsmemory($result,$i);
	    if ($pdf->gety() > $pdf->h-30 || $i == 0){
	    	
   	    $pdf->AddPage('L');
				$pdf->SetFont('Arial','B',10);
				$pdf->SetFillColor($corcab);
				
				if ($grupo == "r"){
					
	     		$pdf->Cell(30,6,"Inscrição",1,0,"L",1);
					$pdf->Cell(60,6,"CNPJ/CPF",1,0,"L",1);
					$pdf->Cell(145,6,"Razão Social",1,0,"L",1);
					$pdf->Cell(40,6,"Num/Compl" ,1,1,"L",1);
					  
					$pdf->Cell(50,6,"Classe",1,0,"L",1);
					$pdf->Cell(115,6,"Atividade",1,0,"L",1);
					
					if ($proce=='s'){
						$pdf->Cell(30,6,"Data Baixa",1,0,"L",1);
					}else{
						 $pdf->Cell(30,6,"Telefone",1,0,"L",1);
					}
						
					$pdf->Cell(40,6,"Data de Início",1,0,"L",1); 
					$pdf->Cell(40,6,"Data de Fim",1,1,"L",1); 
		    } else if ($grupo == "a") {
		    	
				  $pdf->Cell(20,6,"Inscrição",1,0,"L",1);
					$pdf->Cell(40,6,"CNPJ/CPF",1,0,"L",1);
					$pdf->Cell(80,6,"Razão Social",1,0,"L",1);
					$pdf->Cell(100,6,"Rua",1,0,"L",1);
					$pdf->Cell(35,6,"Num/Compl" ,1,1,"L",1);
					$pdf->Cell(170,6,"Classe",1,0,"L",1);
					if ($proce=='s'){
						$pdf->Cell(35,6,"Data Baixa",1,0,"L",1);
					}else{
						 $pdf->Cell(35,6,"Telefone",1,0,"L",1);
					}
						
					$pdf->Cell(35,6,"Data de Início",1,0,"L",1); 
					$pdf->Cell(35,6,"Data de Fim",1,1,"L",1);
		    } else if ($grupo == "c"){
		    	
				  $pdf->Cell(20,6,"Inscrição",1,0,"L",1);
				  $pdf->Cell(40,6,"CNPJ/CPF",1,0,"L",1);
				  $pdf->Cell(80,6,"Razão Social",1,0,"L",1);
				  $pdf->Cell(100,6,"Rua",1,0,"L",1);
				  $pdf->Cell(35,6,"Num/Compl" ,1,1,"L",1);
				 
				  $pdf->Cell(170,6,"Atividade",1,0,"L",1);
			    if ($proce=='s'){
					  $pdf->Cell(35,6,"Data Baixa",1,0,"L",1);
				  } else{
					  $pdf->Cell(35,6,"Telefone",1,0,"L",1);
				  }
				 
				  $pdf->Cell(35,6,"Data de Início",1,0,"L",1); 
				  $pdf->Cell(35,6,"Data de Fim",1,1,"L",1);    
		    } else if($grupo == 'i') {
		    	
				  $pdf->Cell(20,6,"Inscrição",1,0,"C",1);
				  $pdf->Cell(35,6,"CNPJ/CPF",1,0,"L",1);
      	  $pdf->Cell(50,6,"Razão Social",1,0,"C",1);
			    $pdf->Cell(50,6,"Atividade",1,0,"C",1);
			    $pdf->Cell(45,6,"Rua",1,0,"C",1);
			    $pdf->Cell(25,6,"Num/Compl" ,1,0,"C",1);
				  $pdf->Cell(25,6,"Data de Início",1,0,"C",1);
				  $pdf->Cell(25,6,"Data de Fim",1,1,"L",1); 
	      }
      }
	        
	    if($i % 2 == 0){
	      $corfundo = 236;
	    } else {
	      $corfundo = 245;	
	    }
	    
	    $pdf->SetFillColor($corfundo);
	    if ($descrrua != $j14_nome && $grupo == "r"){
	    	
	    	$pdf->SetFillColor(220);
	    	$pdf->SetFont('Arial','B',10);
	    	if($totruas != 0){
	    		$pdf->Cell(275,5,"Total de ".$totruas." contribuinte".($totruas > 1?"s":"")." para a rua : ".$descrrua,0,1,"R",0);
	    		$totruas = 0;
	    	}
	    	
	    	$pdf->Cell(280,2,"",0,1,"C",0);
	      $pdf->Cell(275,5,$j14_codigo." - ".$j14_nome,"B",1,"L",1);
	      $pdf->Cell(280,2,"",0,1,"C",0);
	      $descrrua = $j14_nome;
	   
	   // SE FOR POR ATIVIDADE
	    } else if ($descrativ != $q03_descr && $grupo == "a") {
	    	
	    	//q03_ativ, q03_descr,
	    	$pdf->SetFillColor(220);
	    	$pdf->SetFont('Arial','B',10);
	    	if($totativ != 0){
	    		$pdf->Cell(275,5,"Total de ".$totativ." contribuinte".($totativ > 1?"s":"")." para a atividade : ".$descrativ,0,1,"R",0);
	    		$totativ = 0;
	    	}
	    	
	    	$pdf->Cell(280,2,"",0,1,"C",0);
	      $pdf->Cell(275,5,$q03_ativ." - ".$q03_descr,"B",1,"L",1);
	      $pdf->Cell(280,2,"",0,1,"C",0);
	      $descrativ = $q03_descr;
	     // SE FOR POR CLASSE
	    }else if ($descrclasse != $q12_descr && $grupo == "c"){
	    	
	    	//q12_classe, q12_descr
	    	$pdf->SetFillColor(220);
	    	$pdf->SetFont('Arial','B',10);
	    	if($totclasse != 0){
	    		$pdf->Cell(275,5,"Total de ".$totclasse." contribuinte".($totclasse > 1?"s":"")." para a classe : ".$descrclasse,0,1,"R",0);
	    		$totclasse = 0;
	    	}
	    	$pdf->Cell(280,2,"",0,1,"C",0);
	      $pdf->Cell(275,5,$q12_classe." - ".$q12_descr,"B",1,"L",1);
	      $pdf->Cell(280,2,"",0,1,"C",0);
	      $descrclasse = $q12_descr;

				/* PARA INSCRICAO */
	    }else if ($descrinscr != $q02_inscr && $grupo == "i"){
	    	
	    	//q12_classe, q12_descr
	    	$pdf->SetFillColor(220);
	    	$pdf->SetFont('Arial','B',10);
	    	if($totinscr != 0){
	    		$pdf->Cell(275,5,"Total de ".$totinscr." contribuinte".($totinscr > 1?"s":"")." para a inscrição : ".$descrinscr,0,1,"R",0);
	    		$totinscr = 0;
	    	}
	    	$pdf->Cell(280,2,"",0,1,"C",0);
	      $pdf->Cell(275,5,$q02_inscr." - ".$z01_nome,"B",1,"L",1);
	      $pdf->Cell(280,2,"",0,1,"C",0);
	      $descrinscr = $q02_inscr;
	    }
	    
	    $pdf->SetFillColor($corfundo);
	    $pdf->SetFont('Arial','',8);
	  // SE FOR POR RUAS
	  
	   	if ($grupo == "r"){
	   		
	  		$pdf->Cell(30,05,$q02_inscr,0,0,"L",1);
	  	 	$pdf->Cell(60,05,db_cgccpf($z01_cgccpf),0,0,"L",1); 
	  	 	$pdf->Cell(145,05,(strlen($z01_nome) > 140?substr($z01_nome,0,140)."...":$z01_nome),0,0,"L",1);
	  	 	$pdf->Cell(40,05,$q02_numero."/".$q02_compl,0,1,"L",1);
	  	  	
	  		$pdf->Cell(50,05,(strlen($q12_descr) > 80?substr($q12_descr,0,80)."...":$q12_descr),0,0,"L",1);
		  	$pdf->Cell(115,05,(strlen($q03_descr) > 110?substr($q03_descr,0,110)."...":$q03_descr),0,0,"L",1);
      
				if ($proce=='s'){
					
					$dtbaix = db_formatar($q02_dtbaix,'d');
					$pdf->Cell(30,05,$dtbaix,0,0,"L",1);
				} else { 
					$pdf->Cell(30,05,$z01_telef,0,0,"L",1);
				}
				
	      $pdf->Cell(40,05,db_formatar($q07_datain,'d'),0,0,"L",1);
		    $pdf->Cell(40,05,db_formatar($q07_datain,'d'),0,1,"L",1);
        // SE FOR POR ATIVIDADE      
      } else if ($grupo == "a"){
      	
	      $pdf->Cell(20,05,$q02_inscr,0,0,"L",1);
	      $pdf->Cell(40,05,db_cgccpf($z01_cgccpf),0,0,"L",1);
        $pdf->Cell(80,05,(strlen($z01_nome) > 75?substr($z01_nome,0,75)."...":$z01_nome),0,0,"L",1);
        $pdf->Cell(100,05,(strlen($j14_nome) > 90?substr($j14_nome,0,90)."...":$j14_nome),0,0,"L",1);
        $pdf->Cell(35,05,$q02_numero."/".$q02_compl,0,1,"L",1);
           
	      $pdf->Cell(170,05,(strlen($q12_descr) > 24?substr($q12_descr,0,24)."...":$q12_descr),0,0,"L",1);
	      if ($proce=='s'){
	      	
				  $dtbaix = db_formatar($q02_dtbaix,'d');
					$pdf->Cell(35,05,$dtbaix,0,0,"L",1);
				} else { 
					$pdf->Cell(35,05,$z01_telef,0,0,"L",1);
				}
				
        $pdf->Cell(35,05,db_formatar($q07_datain,'d'),0,0,"L",1);
        $pdf->Cell(35,05,db_formatar($q07_datafi,'d'),0,1,"L",1);
      
        // SE FOR POR CLASSE
      } else if ($grupo == "c") {
      	
	  	  $pdf->Cell(20,05,$q02_inscr,0,0,"L",1);
	  	  $pdf->Cell(40,05,db_cgccpf($z01_cgccpf),0,0,"L",1);
    	  $pdf->Cell(80,05,(strlen($z01_nome) > 70?substr($z01_nome,0,70)."...":$z01_nome),0,0,"L",1);
	    	$pdf->Cell(100,05,(strlen($j14_nome) > 90?substr($j14_nome,0,90)."...":$j14_nome),0,0,"L",1);
	    	$pdf->Cell(35,05,$q02_numero."/".$q02_compl,0,1,"L",1);
	    	
	    	$pdf->Cell(170,05,(strlen($q03_descr) > 200?substr($q03_descr,0,200)."...":$q03_descr),0,0,"L",1);

	      if ($proce == 's') {
	      	
				  $dtbaix = db_formatar($q02_dtbaix,'d');
					$pdf->Cell(35,05,$dtbaix,0,0,"L",1);
				} else { 
					$pdf->Cell(35,05,$z01_telef,0,0,"L",1);
				}

				$pdf->Cell(35,05,db_formatar($q07_datain,'d'),0,0,"L",1);
        $pdf->Cell(35,05,db_formatar($q07_datafi,'d'),0,1,"L",1);
      } else if ($grupo == "i") {
      	
				   /* PARA INSCRICAO */
	  	  $pdf->Cell(20,05,$q12_classe,0,0,"C",1);
	  	  $pdf->Cell(35,05,db_cgccpf($z01_cgccpf),0,0,"L",1);
    	  $pdf->Cell(50,05,(strlen($q12_descr) > 55?substr($q12_descr,0,55)."...":$q12_descr),0,0,"L",1);
	    	$pdf->Cell(50,05,(strlen($q03_ativ."-".$q03_descr) > 54?substr($q03_ativ."-".$q03_descr,0,54)."...":$q03_ativ."-".$q03_descr),0,0,"L",1);
	      $pdf->Cell(45,05,(strlen($j14_nome) > 44?substr($j14_nome,0,44)."...":$j14_nome),0,0,"L",1);
	      $pdf->Cell(25,05,$q02_numero."/".$q02_compl,0,0,"C",1);
	      $pdf->Cell(25,05,db_formatar($q07_datain,'d'),0,0,"C",1);
	      $pdf->Cell(25,05,db_formatar($q07_datafi,'d'),0,1,"C",1);
      }

	    $totativ++;
	    $totinscr++;
	    $totclasse++;
	    $totruas++;    
	}
	
	if ($grupo == "r"){
		
    	$pdf->SetFillColor(236);
    	$pdf->SetFont('Arial','B',10);
    	if($totruas != 0){
    		$pdf->Cell(275,5,"Total de ".$totruas." contribuintes para a rua : ".$descrrua,0,1,"R",0);
    		$totruas = 0;
    	}
  } else if ($grupo == "a"){
  	
    $pdf->SetFillColor(236);
    $pdf->SetFont('Arial','B',10);
    if($totativ != 0){
    	
    	$pdf->Cell(275,5,"Total de ".$totativ." contribuintes para a atividade : ".$descrativ,0,1,"R",0);
    	$totativ = 0;
    }
  } else if ($grupo == "c"){
    	//q12_classe, q12_descr
    	$pdf->SetFillColor(236);
    	$pdf->SetFont('Arial','B',10);
    	if($totclasse != 0){
    		$pdf->Cell(275,5,"Total de ".$totclasse." contribuintes para a classe : ".$descrclasse,0,1,"R",0);
    		$totclasse = 0;
    	}
  }
  
  // total geral
  $pdf->SetFont('Arial','B',14);
  $pdf->Cell(280,5,"",0,1,"R",0);
  $pdf->Cell(270,5,"Total Geral de ".$i." contribuintes.",0,1,"R",0);
  //

} else {

	for($i=0;$i<$numlinhas;$i++){
		
	    db_fieldsmemory($result,$i);
	    if ($pdf->gety() > $pdf->h-30 || $i == 0){
	    	
		   	$pdf->AddPage('L');
				$pdf->SetFont('Arial','B',9);
				$pdf->SetFillColor(210);
				$pdf->Cell(25,05,"Inscrição",1,0,"L",1);
				$pdf->Cell(45,05,"CNPJ/CPF",1,0,"L",1);
				$pdf->Cell(80,05,"Razão Social",1,0,"L",1);
				$pdf->Cell(90,05,"Rua",1,0,"L",1);
				$pdf->Cell(35,05,"Nº/Compl.",1,1,"L",1);
							
				if ($proce=='s') {
					$iTam = 170;
				} else {
					$iTam = 135;
				}
				
	      $pdf->Cell(70,05,"Classe",1,0,"L",1);
			  $pdf->Cell($iTam,05,"Atividade",1,0,"L",1);
			    
				if ($proce=='s'){
				  $pdf->Cell(35,05,"Data baixa",1,1,"L",1);
				} else {
					
				  $pdf->Cell(35,05,"Data de Inicio",1,0,"L",1);
				  $pdf->Cell(35,05,"Data de Fim",1,1,"L",1);
				}
				
				$pdf->Cell(60,3,"",0,1,"L",0);   
	    }
	    
	    if ($i % 2 == 0) {
	      $corfundo = 236;
	    } else {
	      $corfundo = 245;	
	    }
	    
	    $pdf->SetFillColor($corfundo);
	    $pdf->SetFont('Arial','',7);
	    $pdf->Cell(25,5,$q02_inscr,0,0,"L",1);
	    $pdf->Cell(45,5,db_cgccpf($z01_cgccpf),0,0,"L",1);
	    $pdf->Cell(80,5,(strlen($z01_nome) > 70?substr($z01_nome,0,70)."...":$z01_nome),0,0,"L",1);
	    $pdf->Cell(90,5,$j14_nome,0,0,"L",1);
	    $pdf->Cell(35,5,$q02_numero.'/'.$q02_compl,0,1,"L",1);
	    
	    if ($proce=='s') {
        $iTam = 170;
      } else {
        $iTam = 135;
      }
	    
	    $pdf->Cell(70,5,(strlen($q12_descr) > 80?substr($q12_descr,0,80)."...":$q12_descr),0,0,"L",1);
	    $pdf->Cell($iTam,5,(strlen($q03_descr) > 140?substr($q03_descr,0,140)."...":$q03_descr),0,0,"L",1);
	    
        
			if ($proce=='s'){
				
			  $dtbaix = db_formatar($q02_dtbaix,'d');
			  $pdf->Cell(35,05,@$dtbaix,0,1,"L",1);
			} else { 
				
				$pdf->Cell(35,05,db_formatar($q07_datain,'d'),0,0,"L",1);
		    $pdf->Cell(35,05,db_formatar($q07_datafi,'d'),0,1,"L",1);
			}
	}
	
  // total geral
  $pdf->SetFont('Arial','B',14);
  $pdf->Cell(280,5,"",0,1,"R",0);
  $pdf->Cell(270,5,"Total Geral de ".$i." contribuintes.",0,1,"R",0);
}

$pdf->Output();
?>