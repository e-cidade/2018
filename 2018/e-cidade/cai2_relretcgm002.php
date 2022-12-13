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
include("classes/db_cgm_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$fonte = 9;
$head3 = "RELATÓRIO DE RETENÇÕES POR CGM DE EMPENHO";

$dbwhere1 = " and empempenho.e60_instit = ".db_getsession("DB_instit");
$dbwhere2 = "";
$dbwhere3 = "";
$cgm      = "";

if( isset($z01_numcgm) && $z01_numcgm!="" ) {
    $cgm       = $z01_numcgm;
	$dbwhere1 .= " and empempenho.e60_numcgm = $z01_numcgm ";
}
if( isset($k02_codigo) && $k02_codigo!="" ) {
	
	$vet_receitas = explode("|",$k02_codigo);
	$receitas     = "";
	
	for ( $i=0; $i < count($vet_receitas); $i++) {
      $receitas   .= $vet_receitas[$i].($i == count($vet_receitas) -1?"":",");
	}
	
	$dbwhere2     = " and arrepaga.k00_receit in ($receitas)";
}

if( isset($data_inicial) && $data_inicial != "--" && isset($data_final) && $data_final != "--") {
	$dbwhere3    .= " and arrepaga.k00_dtpaga between '".$data_inicial."' and '".$data_final."'";
	$dbwhere1    .= " and c71_coddoc in (5,6,35,36,37,38) and conlancamemp.c75_data between '".$data_inicial."' and '".$data_final."'";
    $head6        = "Período : ".db_formatar($data_inicial,'d')." À ".db_formatar($data_final,'d');	
}

$sql1 = "select distinct x.e60_anousu,
				x.e60_numcgm as z01_numcgm,
				cgm.z01_nome,
				cgm.z01_cgccpf,
				orcelemento.o56_codele,
				orcelemento.o56_descr,
				x.k12_anopgto,
				x.k12_mespgto,
				sum(k12_valor) as total_pago
		 from (
			select 	e60_anousu,
					e60_numcgm,
					e60_coddot,
					extract (year  from conlancamemp.c75_data) as k12_anopgto,
					extract (month from conlancamemp.c75_data) as k12_mespgto,
					round(sum(case when c71_coddoc in (5,35,37) then c70_valor else c70_valor * -1 end),2) as k12_valor
					from conlancamemp
			inner join conlancam on c70_codlan = c75_codlan
		 	inner join empempenho on e60_numemp = c75_numemp
			inner join conlancamdoc on c71_codlan = c75_codlan
		 	where 1=1 $dbwhere1 
		 	group by e60_anousu, e60_numcgm, e60_coddot, extract (year from conlancamemp.c75_data),extract (month from conlancamemp.c75_data)) as x
		 inner join cgm on z01_numcgm = e60_numcgm
		 inner join orcdotacao on e60_coddot = o58_coddot and e60_anousu = o58_anousu
		 inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu
		 group by 	x.e60_anousu,
		 			x.e60_numcgm,
					cgm.z01_nome,
					cgm.z01_cgccpf,
					orcelemento.o56_codele,
					orcelemento.o56_descr,
					x.k12_anopgto,
					x.k12_mespgto
		 order by 	cgm.z01_nome,
		 			x.k12_anopgto,
		 			x.k12_mespgto,
					orcelemento.o56_codele
		 		";

$result1 = pg_exec($sql1) or die($sql1);
$numrows = pg_numrows($result1);

 if ( $cgm != "" && $numrows > 0) {
   db_fieldsmemory($result1,0);
   $head5 = "CGM : ".$z01_numcgm." - ".$z01_nome;
 } else {
   $head5 = "CGM : ".$z01_numcgm;
 }

$anospgto      = "";
$numcgm_salvos = "";
$ultmespgto    = "";
$ultcgm        = 0;
$tot_retido    = 0;
$tot_pago      = 0;
$alt           = 6;
$mespag        = 0;
$addpage       = 0;
$countreg      = 0;

$pdf->SetTextColor(0,0,0);

for ( $i=0; $i<$numrows; $i++ ) {
   db_fieldsmemory($result1,$i);

   if ( $pdf->gety() > $pdf->h - 30 || $i == 0 ) {
   	 $pdf->AddPage();
	 $addpage = 1;
   }
    
   if ( $ultmespgto != str_pad($k12_anopgto, 4, "0", STR_PAD_LEFT).str_pad($k12_mespgto, 2, "0", STR_PAD_LEFT)) {

//	$ultmespgto = str_pad($k12_anopgto, 4, "0", STR_PAD_LEFT).str_pad($k12_mespgto, 2, "0", STR_PAD_LEFT);
	 $sql2 = "select k02_codigo,
			         k02_drecei,
		             k00_numpre,
		             k00_numpar,
			         case when arrepaga.k00_hist = 503 then arrepaga.k00_valor*-1 else arrepaga.k00_valor end as k00_valor 
		        from arrepaga
		       inner join tabrec on arrepaga.k00_receit = tabrec.k02_codigo 
		       where 1=1 ".$dbwhere2; 
					
	 $sql2.= "  and arrepaga.k00_numcgm = $z01_numcgm 
	            and extract(month from arrepaga.k00_dtpaga) = $k12_mespgto 
			    and extract(year from arrepaga.k00_dtpaga) = $k12_anopgto";
		
	 $sql2 = " select k02_codigo,
			          k02_drecei,
			          sum(k00_valor) as total_retido
		         from ($sql2) as x
 		         group by k02_codigo,
			              k02_drecei"; 

    $anospgto = $k12_anopgto;
	
	$result2  = pg_exec($sql2);
	$numrows2 = pg_numrows($result2);

	if ( $numrows2 > 0) {
	  $imprime     = "";
	  $flag_retido = true;
	} else {
	  $imprime     = "B";
	  $flag_retido = false;	
	}
	
   }
   
   if ( $flag_retido == true) {
     
	 if ( $ultcgm != $z01_numcgm ) {
       
	   //Acumula os cgms para buscar as retenções sem empenho
	   	if ( $numcgm_salvos == ""){ 
		  $numcgm_salvos  = $z01_numcgm; 
		} else { 
		  $numcgm_salvos .= ",".$z01_numcgm; 
		}
		
       if ( strlen($z01_cgccpf) == 14 ) {
         $doc = db_formatar($z01_cgccpf,"cnpj");
	   } else if (strlen($z01_cgccpf) == 11){
	     $doc = db_formatar($z01_cgccpf,"cpf");
	   } else {
         $doc = $z01_cgccpf;
	   }

	   $pdf->SetFont('Arial', 'B', $fonte);
	   $pdf->Cell(10, $alt, "CGM:",      "", 0, "L", 0);
       
	   $pdf->SetFont('Arial', '', $fonte);
  	   $pdf->Cell(20, $alt, $z01_numcgm, "", 1, "R", 0);
       
	   $pdf->SetFont('Arial', 'B', $fonte);
       $pdf->Cell(10, $alt, "CNPJ/CPF:", "", 0, "L", 0);
       
	   $pdf->SetFont('Arial', '', $fonte);
   	   $pdf->Cell(40, $alt, $doc,        "", 1, "R", 0);
	   
       $pdf->SetFont('Arial', 'B', $fonte);
 	   $pdf->Cell(20, $alt, "NOME:",     "", 0, "L", 0);
	   
       $pdf->SetFont('Arial', '', $fonte);
       $pdf->Cell(80, $alt, $z01_nome,   "", 1, "L", 0);
	   
	   $pdf->SetFont('Arial', 'B', $fonte);
       $pdf->Cell(40, $alt, "REFERÊNCIA",   "TBR", 0, "C", 0);
       $pdf->Cell(90, $alt, "HISTÓRICO",    "TBR", 0, "C", 0);
       $pdf->Cell(30, $alt, "TOTAL PAGO",   "TBR", 0, "R", 0);
       $pdf->Cell(30, $alt, "TOTAL RETIDO", "TB",  1, "R", 0);
       
	   $pdf->SetFont('Arial','',$fonte);
	
	   $ultcgm     = $z01_numcgm;
	   $ultmespgto = "";
	   $mespag     = "";  
	   
     }

     $pdf->Cell(40, $alt,db_mes($k12_mespgto,2)."/".$k12_anopgto,$imprime,0,"C",0);
	 
	     
	 for ( $j=0; $j<$numrows2; $j++ ) {
        db_fieldsmemory($result2,$j);
		
		if ($j > 0) {
		  $pdf->Cell(40, $alt,"",0,0,"L",0);	
		}
					
	    $pdf->Cell(90, $alt, "RETENÇÃO ".$k02_codigo." ".substr($k02_drecei,0,30), "LR", 0, "L", 0);
	    $pdf->Cell(30, $alt, "",                                                   "R",  0, "R", 0);
		
		if ( $mespag != str_pad($k12_anopgto, 4, "0", STR_PAD_LEFT).str_pad($k12_mespgto, 2, "0", STR_PAD_LEFT) ) {
		 
		 /*
		  * Acumula os anos para posteriormente buscar as retenções sem empenho 
		  */
		  if ( $anospgto == ""){ 
		    $anospgto  = $k12_anopgto; 
		  } else { 
		    $anospgto .= ",".$k12_anopgto; 
		  }	
		 
	      $pdf->Cell(30, $alt, db_formatar($total_retido,"f"), 0, 1, "", 0);
	      $tot_retido += $total_retido;	 	
		  
		} else {
		  $pdf->Cell(30, $alt, "", "", 1, "R", 0);	
		}
		
	 }

	 $pdf->Cell(40, $alt, "",                                              "BR",  0, "R", 0);
	 $pdf->Cell(90, $alt, "PGTO ".$o56_codele." ".substr($o56_descr,0,30), "BLR", 0, "L", 0);
	 $pdf->Cell(30, $alt, db_formatar($total_pago,"f"),                    "BLR", 0, "L", 0);
	 $countreg++;
			 
	 if ( $mespag != str_pad($k12_anopgto, 4, "0", STR_PAD_LEFT).str_pad($k12_mespgto, 2, "0", STR_PAD_LEFT) ) {
	   $pdf->Cell(30, $alt, "", "B", 1, "R", 0);
	 } else {
  	   $pdf->Cell(30, $alt, "",  "",  1, "R", 0);
	 }
	 
 	 $mespag      = str_pad($k12_anopgto, 4, "0", STR_PAD_LEFT).str_pad($k12_mespgto, 2, "0", STR_PAD_LEFT); 
     $tot_pago   += $total_pago;

	 $flag_retido = false;
   }
   
  $ultmespgto = "";
  
}


/*
 *  Mostra o total somente se houveram registros de retenções com empenho
 */
if ($numcgm_salvos != "") {
  $pdf->ln(2);
  $pdf->Cell(120, $alt,"TOTAIS","TB",0,"R",0);
  $pdf->Cell(40,  $alt,db_formatar($tot_pago,"f"),"TB",0,"R",0);
  $pdf->Cell(30,  $alt,db_formatar($tot_retido,"f"),"TB",1,"R",0);
}


/*
 *  Verifica as retenções que não foram feitas por empenho
 *
 */

  $sql3 = "select k02_codigo,
  	              k02_drecei, 
			 	  z01_numcgm,
				  z01_nome,
				  z01_cgccpf,
			      sum(k00_valor) as total_retido
		     from ( 
		           select k02_codigo,
	                      k02_drecei,
					      z01_numcgm,
                          z01_nome,
						  z01_cgccpf,
			              case when arrepaga.k00_hist = 503 then arrepaga.k00_valor*-1 else arrepaga.k00_valor end as k00_valor 
		             from arrepaga
				    inner join cgm    on arrepaga.k00_numcgm = cgm.z01_numcgm 
		            inner join tabrec on arrepaga.k00_receit = tabrec.k02_codigo 
		            where 1=1 ".$dbwhere2.$dbwhere3; 
		/*
		 * Verifica se foi informado o cgm para a geração do relatorio 			
		 *
		 * Se o cgm está em branco e há registros mostrados 
		 *
		 * Senão se o cgm foi informado e não há registros mostrados, 
		 * procura retenções sem empenho para o cgm informado, já que 
		 * não foi encontrada nenhuma retenção com empenho.
		 */
		if ($numcgm_salvos != "" && $cgm == "") {
           $sql3 .= " and arrepaga.k00_numcgm not in ( $numcgm_salvos ) ";
	    } else if( $cgm != "" && $numcgm_salvos == ""){
           $sql3 .= " and arrepaga.k00_numcgm = $cgm  ";	    	
	    }

  $sql3 .= "       ) as x
 		    group by k02_codigo,
	                 k02_drecei,
				     z01_numcgm,
				     z01_nome,
				     z01_cgccpf";

 /*
  * Executa a query se o cgm informado for diferente do mostrado para os registros de retenções com empenho 
  * Ou se não houveram registros de retenções com empenho mostrados anteriormente
  */
					 
 if($cgm != $numcgm_salvos || $numcgm_salvos == "" ){
  $result3  = pg_exec($sql3);
  $numrows3 = pg_numrows($result3);
 } else{
  $numrows3 = 0;	
 }

if($numrows == 0 && $numrows3 == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem retenções por cgm de empenho no período de '.db_formatar($data_inicial,'d').' a '.db_formatar($data_final,'d'));
}

  if ( $numrows3 > 0 ) {
    
   if ($addpage == 0 || $numcgm_salvos != "") {	
  	 $pdf->AddPage();
   }
	
    $tot_retido2 = 0;	
   
    for ( $x=0; $x<$numrows3; $x++ ) {
  	   db_fieldsmemory($result3,$x);
	   
	   if ( $pdf->gety() > $pdf->h - 27 || $x == 0) {
  	     
         if ($x == 0) {
           $pdf->Cell(190, 8,"FORNECEDORES COM RETENÇÃO SEM EMPENHOS ",1,1,"L",0);         	
         }
		 
         $pdf->Cell(15, $alt,"CGM",          1, 0, "C", 0);
         $pdf->Cell(30, $alt,"CNPJ/CPF",     1, 0, "C", 0);
         $pdf->Cell(60, $alt,"NOME",         1, 0, "L", 0);
         $pdf->Cell(60, $alt,"RECEITA",      1, 0, "C", 0);
         $pdf->Cell(25, $alt,"TOTAL RETIDO", 1, 1, "R", 0);		 
		 
       }  
	   
	   if ( strlen(trim($z01_cgccpf)) == 14 ) {
         $doc = db_formatar($z01_cgccpf,"cnpj");
	   } else if (strlen(trim($z01_cgccpf)) == 11){
	     $doc = db_formatar($z01_cgccpf,"cpf");
	   } else {
         $doc = $z01_cgccpf;
	   }
  
   	   $pdf->Cell(15, $alt,$z01_numcgm,                              "",   0, "C", 0);
   	   $pdf->Cell(30, $alt,$doc,                                     "",   0, "C", 0);
       $pdf->Cell(60, $alt,substr(trim($z01_nome),0,35),             "",   0, "L", 0);
	   $pdf->Cell(60, $alt,substr($k02_codigo."-".$k02_drecei,0,35), "",   0, "L", 0);	 	 
	   $pdf->Cell(25, $alt,db_formatar($total_retido,"f"),           "",   1, "R", 0);	 
       $tot_retido2 += $total_retido;	 
  	
    }
  
    $pdf->Cell(160, $alt,"TOTAL DE RETENÇÃO SEM EMPENHOS:","TB",0,"R",0);
    $pdf->Cell(30,  $alt,db_formatar($tot_retido2,"f"),"TB",1,"R",0);
  
    $pdf->Cell(160, $alt,"TOTAL:","TB",0,"R",0);
    $pdf->Cell(30,  $alt,db_formatar($tot_retido2 + $tot_retido,"f"),"TB",1,"R",0);  
  }

$pdf->Output();

?>