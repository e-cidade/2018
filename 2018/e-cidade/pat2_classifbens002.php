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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_bens_classe.php");
include("classes/db_bensbaix_classe.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_cfpatriplaca_classe.php");
$clcfpatriplaca = new cl_cfpatriplaca;
$clcfpatric 		= new cl_cfpatri;
$clbens 				= new cl_bens;
$clbens->rotulo->label();
$clrotulo 			= new rotulocampo;
$clbensbaix     = new cl_bensbaix;
$clrotulo->label('z01_nome');
$clrotulo->label('descrdepto');
$clrotulo->label('t64_descr');
$clrotulo->label('t64_class');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
if ($clcfpatriplaca->numrows > 0){
  db_fieldsmemory($res_cfpatriplaca,0);
} else {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_classifbens002.nao_existem_placas_para_instituicao');
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
  exit;
}

$head3 = "RELATÓRIO DE BENS POR CLASSIFICAÇÃO";
$where = "";
if (($class != "") && ($class1 != "")) {		
	$info = "Da classificação $class ";
    $info1 = " até $class1.";
    $class = str_replace(".","",$class);
    $class1= str_replace(".","",$class1);
    $where = "  t64_class  between '$class' and '$class1'  ";
} else	if ($class != "") {		
	$info = "Apartir de $class.";
	$class = str_replace(".","",$class);    
	$where = " t64_class >= '$class'  ";
} else	if ($class1 != "") {		
	$info = "Até $class1.";	
    $class1= str_replace(".","",$class1);
	$where = " t64_class <= '$class1'   ";
}
$head3 = "RELATÓRIO DE BENS POR CLASSIFICAÇÃO";
$head4 = @$info;
$head5 = @$info1;

if($opcao_baixados == 'n'){
  $head7 = "TIPO :  Não Baixados";
  $sWhereBaixa = " and ( t55_codbem is null or t55_baixa > '".date("Y-m-d",db_getsession('DB_datausu'))."' )";
} elseif($opcao_baixados == 'b'){
  $head7 = "TIPO :  Baixados";
  $sWhereBaixa = " and t55_baixa <= '".date("Y-m-d",db_getsession('DB_datausu'))."'";
}else{
  $head7 = "TIPO :  Todos";
  $sWhereBaixa = "";
}

if(isset($ordenar) && trim($ordenar) != ""){
	switch ($ordenar){
		case 1: $ordem = "t52_ident";
			break;
		case 2: $ordem = "t52_descr";
			break;
		case 3: $ordem = "t52_bem";
			break;
	}
}

//Verifica se utiliza pesquisa por orgão sim ou não
$resPesquisaOrgao	= $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
	db_fieldsmemory($resPesquisaOrgao,0);
	if($t06_pesqorgao == "t"){
		$lImprimeOrgao = $t06_pesqorgao;	
	}else{
		$lImprimeOrgao = false;
	}
	
} else{
	$lImprimeOrgao = false;
}
//echo $ordem."<br>".$lImprimeOrgao."<br>";
//die();

$result = $clbens->sql_record($clbens->sql_query(null,"distinct t52_codcla as classif,clabens.*","t64_class",$where));
if ($clbens->numrows == 0){
  
   $sMsg = _M('patrimonial.patrimonio.pat2_classifbens002.nao_existem_registros');
   db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
   exit;
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca          = 1;
$alt            = 4;
$total          = 0;
$total_geral    = 0;
$total_valor_cl = 0;
$total_valor_gr = 0;
$numrows = $clbens->numrows;

//$iOrgao = 0;
//$iUnidade = 0;

for($x = 0; $x < $numrows; $x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
   }
   $troca=1;
   $pdf->setfont('arial','b',10);
   //$pdf->cell(0,$alt,$RLt64_class.":".$t64_class."-".$t64_descr,0,1,"L",0);
             
   if ($lImprimeOrgao == 't') {

   		$ordem = $ordem.",o40_orgao,o41_unidade,t52_depart,t33_divisao";
	         $sql = $clbens->sql_query_orgao(null,
	                                                                    "distinct
	                                                                     t64_class, 
	                                                                     t64_descr, 
								                                                       t52_codcla,
								                                                       t52_bem, 
	                                                                     t52_depart,
	                                                                     descrdepto,
								                                                       t52_numcgm, 
								                                                       t52_valaqu, 
								                                                       t52_dtaqu, 
								                                                       trim(t52_ident) as t52_ident, 
								                                                       t52_descr, 
                                                								       z01_nome, 
								                                                       t52_obs,
								                                                       o41_unidade,
	   									                                                 o41_descr,
	   									                                                 o40_orgao,
							       	                                                 o40_descr,
							       	                                                 t30_codigo,
							       	                                                 t30_descr,
							       	                                                 t33_divisao
							       	                                                 ",
	         																														 
								                                                       $ordem,
	   
								                                                       "t52_codcla= $classif 
	                                                                      and t52_instit = ".db_getsession("DB_instit")." 
	                                                                      $sWhereBaixa
						                                                		        group by t64_class, 
									                                                               t64_descr, 
										                                                             t52_codcla, 
										                                                             t52_bem, 
	                                                                               t52_depart,
	                                                                               descrdepto,
										                                                             t52_numcgm, 
										                                                             t52_valaqu, 
										                                                             t52_dtaqu, 
										                                                             t52_ident, 
										                                                             t52_descr, 
										                                                             t52_obs, 
										                                                             z01_nome,
										                                                             t64_descr,
										                                                             t64_class,
										                                                             o40_orgao,
										                                                             o40_descr,
										                                                             o41_unidade,
										                                                             o41_descr,
										                                                             t30_codigo,
							       	                                                 					 t30_descr,
							       	                                                 					 t33_divisao
										                                                             ");
				//die($sql);							       	                                                 					 
				$result_bens = $clbens->sql_record($sql);
   } else {

   	$ordem = $ordem.",t52_codcla,t52_depart,t33_divisao";
			 $sql = $clbens->sql_query_class(null,
			                                                      "t64_class, 
	                                                           t64_descr, 
								                                             t52_codcla,
								                                             t52_bem, 
	                                                           t52_depart,
	                                                           descrdepto,
								                                             t52_numcgm, 
								                                             t52_valaqu, 
								                                             t52_dtaqu, 
								                                             trim(t52_ident) as t52_ident, 
								                                             t52_descr, 
								                                             z01_nome, 
								                                             t52_obs,
			 																											 t30_codigo,
										                                         t30_descr,
										                                         t33_divisao
										                                         ",
								                                             
			                                                       $ordem,
								                                             
								                                             "t52_codcla = $classif 
								                                              and t52_instit = ".db_getsession("DB_instit")." 
								                                              $sWhereBaixa
								                                             group by t64_class, 
				                                            					        t64_descr, 
										                                                  t52_codcla, 
										                                                  t52_bem, 
	                                                                    t52_depart,
	                                                                    descrdepto,
										                                                  t52_numcgm, 
										                                                  t52_valaqu, 
										                                                  t52_dtaqu, 
										                                                  t52_ident, 
										                                                  t52_descr, 
										                                                  t52_obs, 
										                                                  z01_nome,
										                                                  t30_codigo,
							       	                                                t30_descr,
							       	                                                t33_divisao
										                                                  ");
										       	                                                
							       	                                                
			$result_bens = $clbens->sql_record($sql);
   	
   }

  $numrows_bens = $clbens->numrows;
	$iOrgao = 0;
	$iUnidade = 0;
   
		if ($quebra=='n'){   
   	$troca = 0;
   }
   $p              = 0;
   $total          = 0;
   $total_valor_cl = 0;
   for($w=0;$w<$numrows_bens;$w++){  	
   		db_fieldsmemory($result_bens,$w);
   		$pdf->setfont('arial','b',10);
   		if($iOrgao==0 ){
   			if($lImprimeOrgao == 't' && $o40_orgao != $iOrgao){
		    	$pdf->cell(20,$alt,"Órgão",0,0,"L",0);
		    	$pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);
		    	$iOrgao = $o40_orgao;
		    }else {
		    	$iOrgao = 1;
		    }
		    if($lImprimeOrgao == 't' && $o41_unidade != $iUnidade){
		    	$pdf->cell(20,$alt,"Unidade",0,0,"L",0);
		    	$pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
		    	$iUnidade = $o41_unidade;
		    	$pdf->Ln(3);
		    }
		   $pdf->cell(0,$alt,$RLt64_class.":".$t64_class."-".$t64_descr,0,1,"L",0);
		   
		   $pdf->setfont('arial','b',8);
		   $pdf->cell(15,$alt,"Código",1,0,"C",1);
		   $pdf->cell(100,$alt,$RLt52_descr,1,0,"C",1);
		   //$pdf->cell(15,$alt,$RLt52_numcgm,1,0,"C",1);
		   //$pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
		   $pdf->cell(20,$alt,"Vlr Aquisição",1,0,"C",1);
		   $pdf->cell(25,$alt,"Data Aquisição",1,0,"C",1);
		   $pdf->cell(20,$alt,$RLt52_ident,1,0,"C",1);
		   $pdf->cell(50,$alt,$RLt52_depart,1,0,"C",1);
		   $pdf->cell(50,$alt,"Divisão",1,1,"C",1);
		   
		   $pdf->cell(15,$alt,"Fornec.",1,0,"C",1);
		   $pdf->cell(100,$alt,$RLz01_nome,1,0,"C",1);   
		   $pdf->cell(165,$alt,$RLt52_obs,1,1,"C",1);
   			
		   //$pdf->setfont('arial','b',10);
   		 
   		}
   		   		
   		if ($pdf->gety() > $pdf->h - 30 ){
   			$pdf->setfont('arial','b',10);
      	$pdf->addpage("L");
		   		if($lImprimeOrgao == 't' && $o40_orgao != $iOrgao){
		    	$pdf->cell(20,$alt,"Órgão",0,0,"L",0);
		    	$pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);
		    	$iOrgao = $o40_orgao;
			    }
			    if($lImprimeOrgao == 't' && $o41_unidade != $iUnidade){
			    	$pdf->cell(20,$alt,"Unidade",0,0,"L",0);
			    	$pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
			    	$iUnidade = $o41_unidade;
			    	$pdf->Ln(3);
			    }
      	$pdf->setfont('arial','b',10);
   			$pdf->cell(0,$alt,$RLt64_class.":".$t64_class."-".$t64_descr,0,1,"L",0);  			
      	$pdf->setfont('arial','b',8);
      	$pdf->cell(15,$alt,"Código",1,0,"C",1);
   			$pdf->cell(100,$alt,$RLt52_descr,1,0,"C",1);
   		  //$pdf->cell(15,$alt,$RLt52_numcgm,1,0,"C",1);
		  	//$pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
   			$pdf->cell(20,$alt,"Vlr Aquisição",1,0,"C",1);
   			$pdf->cell(25,$alt,"Data Aquisição",1,0,"C",1);
   			$pdf->cell(20,$alt,$RLt52_ident,1,0,"C",1);
   			$pdf->cell(50,$alt,$RLt52_depart,1,0,"C",1);
   			$pdf->cell(50,$alt,"Divisão",1,1,"C",1);
//   			$pdf->cell(20,$alt,$RLt52_numcgm,1,0,"C",1);
   			$pdf->cell(15,$alt,"Fornec.",1,0,"C",1);
   			$pdf->cell(10,$alt,$RLz01_nome,1,0,"C",1);   
   			$pdf->cell(165,$alt,$RLt52_obs,1,1,"C",1);   
		   	//$pdf->cell(60,$alt,$RLt52_obs,1,1,"C",1);
		   	$p=0;		    
   		}
      $result_bensbaix = $clbensbaix->sql_record($clbensbaix->sql_query_file($t52_bem));
      if ($clbensbaix->numrows>0) {
        if($opcao_baixados == 'n'){
          continue;
       }
          $baix = "Baixado";
      } else {
         if($opcao_baixados == 'b'){
         continue;
      }
         $baix = "Não baixado";
      }
   		$pdf->setfont('arial','',7);
   		$pdf->cell(15,$alt,$t52_bem,0,0,"C",$p);
   		$pdf->cell(100,$alt,$t52_descr,0,0,"L",$p);
   		//$pdf->cell(15,$alt,$t52_numcgm,0,0,"C",0);
   		//$pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   		$pdf->cell(20,$alt,db_formatar($t52_valaqu,"f"),0,0,"R",$p);
   		$pdf->cell(25,$alt,db_formatar($t52_dtaqu,"d"),0,0,"C",$p);
   		$pdf->cell(20,$alt,$t52_ident,0,0,"C",$p);
   		$pdf->cell(50,$alt,substr($t52_depart."-".$descrdepto,0,32),0,0,"L",$p);
   		$pdf->cell(50,$alt,substr($t30_codigo."-".$t30_descr,0,32),0,1,"L",$p);
   		$pdf->cell(15,$alt,$t52_numcgm,0,0,"C",$p);
   		$pdf->cell(105,$alt,$z01_nome,0,0,"L",$p);   		     		
   		$pdf->multicell(160,$alt,$t52_obs,0,"L",$p);
   		if ($p==0){
   			$p=1;
   		}else{
   			$p=0;
   		}
      $total_valor_cl += $t52_valaqu;
      $total_valor_gr += $t52_valaqu;
   		$total++;
      $total_geral++;
   }   
   if($numrows_bens > 0){
	   $pdf->setfont('arial','B',9);
	   $pdf->cell(100,$alt,'TOTAL DA CLASSIFICAÇÃO  :  '.$total,"T",0,"L",0);
	   $pdf->cell(35,$alt,db_formatar($total_valor_cl,'f'),"T",0,"R",0);
	   $pdf->cell(145,$alt,'',"T",0,"R",0);
	   $pdf->Ln();
	   $pdf->Ln();
   }
}
$pdf->setfont('arial','b',9);
//$pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->cell(100,$alt,'TOTAL GERAL :  '.$total_geral,"T",0,"L",0);
$pdf->cell(35,$alt,db_formatar($total_valor_gr,'f'),"T",0,"R",0);
$pdf->cell(145,$alt,'',"T",0,"R",0);
$pdf->Output();
?>