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
include("classes/db_empempenho_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcorgao_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_conlancamrec_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdig_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clconlancamval = new cl_conlancamval;
$clconlancamcgm = new cl_conlancamcgm;
$clconlancam  = new cl_conlancam;
$clorcsuplem = new cl_orcsuplem;
$auxiliar    = new cl_conlancam;
$clconlancamrec = new cl_conlancamrec;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdot  = new cl_conlancamdot;
$clconlancamdig  = new cl_conlancamdig;

$clconlancamcgm->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clorcsuplem->rotulo->label();

$clrotulo->label("c60_descr");
$clrotulo->label("c53_descr");
$clrotulo->label("c53_coddoc");


///////////////////////////////////////////////////////////////////////
 $data1="";
 $data2="";
 @$data1="$data1_ano-$data1_mes-$data1_dia"; 
 @$data2="$data2_ano-$data2_mes-$data2_dia"; 
 if (strlen($data1) < 7){
    $data1= db_getsession("DB_anousu")."-01-31";
 }  
 if (strlen($data2) < 7){
    $data2= db_getsession("DB_anousu")."-12-31";
 }  
 //---------
 if (isset($lista)){
   $w="("; 
   $tamanho= sizeof($lista);
   for ($x=0;$x < sizeof($lista);$x++){
       $w = $w."$lista[$x]";
       if ($x < $tamanho-1) {
         $w= $w.",";
       }	
   }  
   $w = $w.")";
 }
 //--  monta sql
 $txt_where=" o58_instit =".db_getsession("DB_instit");
 if (isset($lista)){
      if (isset($ver) and $ver=="com"){
          $txt_where= $txt_where." and c73_coddot in  $w";
      } else {
          $txt_where= $txt_where." and c73_coddot not in  $w";
      }	 
 }  
 // data
 $txt_where = $txt_where." and c73_data between '$data1' and '$data2' "; 

 if ($listarec!=""){
    if (isset($verrec) and $verrec=="com"){
        $txt_where= $txt_where." and orcdotacao.o58_codigo in  ($listarec)";
    } else {
        $txt_where= $txt_where." and orcdotacao.o58_codigo not in  ($listarec)";
     }	 
 }  

 ///////////////////////////////////////////////////

 $sql_analitico= "select   o56_codele,
				                        o56_elemento,
					        		    o56_descr,
				                        c69_codlan,
				                        c69_sequen,
				                        c69_data, 
				                        c69_codhist, 
				                        c53_coddoc,
				                        c53_descr, 
				                        c69_debito,
				                        dd.c60_descr as debito_descr,
				                        c69_credito,
				                        bb.c60_descr as credito_descr,
				                        c69_valor,
										c50_codhist,
										c50_descr,
										c74_codrec,
										c79_codsup,
										c75_numemp,
										c73_coddot,
										c76_numcgm,
										c78_chave
                from conlancamdot 
                     inner join orcdotacao      on o58_coddot=c73_coddot and o58_anousu=c73_anousu
				     inner join orcelemento     on o56_codele = orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu
				     inner join conlancamval 	 on c69_codlan = c73_codlan and c69_anousu=c73_anousu
                     inner join conplanoreduz aa on aa.c61_reduz = c69_credito and aa.c61_anousu=".db_getsession("DB_anousu")."
                     inner join conplano      bb on bb.c60_codcon  = aa.c61_codcon and  bb.c60_anousu  = aa.c61_anousu  

                     inner join conplanoreduz cc on cc.c61_reduz= c69_debito and cc.c61_anousu=".db_getsession("DB_anousu")."
                     inner join conplano      dd on dd.c60_codcon = cc.c61_codcon  and dd.c60_anousu = cc.c61_anousu

		     		 inner join conhist          on c50_codhist = c69_codhist
                     inner join conlancamdoc 	 on c71_codlan  = c69_codlan 
                     inner join conhistdoc 	 on c53_coddoc  = conlancamdoc.c71_coddoc 
				     left outer join conlancamrec on c74_codlan = c69_codlan   and c74_anousu=c69_anousu 
				     left outer join conlancamsup on c79_codlan = c69_codlan
				     left outer join conlancamemp on c75_codlan = c69_codlan
				     left outer join conlancamcgm on c76_codlan = c69_codlan
				     left outer join conlancamdig on c78_codlan = c69_codlan
         where $txt_where
         order by o56_codele,
	          c69_data
           	  ";

$sql_sintetico = "select o56_codele,
			                         o56_elemento,
								 	 o56_descr,
			                         c70_codlan,
			                         c70_data, 
			                         c53_coddoc,
			                         c53_descr, 
			                         c70_valor
                from conlancamdot 
                     inner join orcdotacao      on o58_coddot=c73_coddot and o58_anousu=c73_anousu
				     inner join orcelemento     on o56_codele = orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu                                                       
				     inner join conlancam       on c70_codlan = c73_codlan and c70_anousu = c73_anousu
                     inner join conlancamdoc 	on c71_codlan = conlancam.c70_codlan 
                     inner join conhistdoc 	on c53_coddoc = conlancamdoc.c71_coddoc 
            where $txt_where
            order by o56_codele,
                     c70_data 
           	    ";

if ($tipo =="a") {
       $res=$clconlancam->sql_record($sql_analitico);
       if ($clconlancam->numrows > 0 ){
           $rows=$clconlancam->numrows; 
       } else {
          db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
} else if ($tipo =="s"){
       $res=$clconlancam->sql_record($sql_sintetico);
       if ($clconlancam->numrows > 0 ){
           $rows=$clconlancam->numrows; 
       } else {
          db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
}

//////////////////////////////////////////////////////////////////////
$head2 = "RAZÃO POR ELEMENTO DE DESPESA";
$head5 = "PERÍODO : ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
if ($quebra=="g" && $tipo=="a") {
   $head6 = "TIPO :  Geral, Analitico";
} else {
   $head6 = "TIPO :  Geral, Sintético";
} 
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('P'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$tam = '04';
$imprime_header=true;
$contador=0;
$repete = "";
$__total=0;

/*  geral analitico */
if ($quebra=="g" and $tipo=="a"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa novapagina 
            if ($pdf->gety() > $pdf->h - 40){
 	       $pdf->addpage("P"); 
	       $imprime_header=true;
	    }
            if ($imprime_header==true)
    	    { 
	         /* header  */
		 if ($x > 1){
                    $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),'TB',0,"C",1);
	            $pdf->Cell(20,$tam,strtoupper($RLc69_sequen),'TB',0,"C",1);	 
                    $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,'TB',0,"C",1);
                    $pdf->Cell(20,$tam,strtoupper($RLc69_debito),'TB',0,"C",1); // recurso
	            $pdf->Cell(35,$tam,strtoupper($RLc60_descr) ,'TB',0,"L",1); // recurso
                    $pdf->Cell(20,$tam,strtoupper($RLc69_credito),'TB',0,"C",1); // recurso
	            $pdf->Cell(35,$tam,strtoupper($RLc60_descr)  ,'TB',0,"L",1); // recurso
                    $pdf->Cell(20,$tam,strtoupper($RLc69_valor)  ,'TB',1,"R",1); // cod+estrut dotatao // quebra linha       		
		 }
	    $imprime_header=false;
 
            }
	    /* ----------- */
	    if ($repete != $o56_codele) {
	        /*  */
		if ($x > 1 ){
                       $sql_resumido ="select c53_coddoc as doc,c53_descr as descr,sum(c70_valor) as total
                                     from conlancamcgm
					                         inner join conlancam on c70_codlan=c76_codlan
			                		         inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
					                         inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
                      		     where conlancamcgm.c76_numcgm = $repete
				           and $txt_where
                                     group by c53_coddoc,c53_descr
		                     order by c53_coddoc   ";
		       $rr=$clconlancamval->sql_record($sql_resumido);     
		       $pdf->SetFont('Arial','B',7);	 
		       $pdf->setX(80);
                       $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),'B',0,"C",0); // recurso
		       $pdf->Cell(80,$tam,strtoupper($RLc53_descr),'B',0,"L",0); // recurso
                       $pdf->Cell(20,$tam,"SOMATORIO",'B',1,"R",0);  // cod+estrut dotatao // quebra linha		
  	               $pdf->Ln(1);
		       for($i=0;$i < $clconlancamval->numrows;$i++){
		              db_fieldsmemory($rr,$i);
   		              $pdf->setX(80);
        	              $pdf->Cell(20,$tam,$doc            ,0,0,"C",0); // recurso
       	        	      $pdf->Cell(80,$tam,$descr            ,0,0,"L",0); // recurso
               		      $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
	                }		 
                }
	        /*  */
	        $repete = $o56_codele;
                $pdf->Ln(); $pdf->Ln();    
		$pdf->SetFont('Arial','B',8);	
                $pdf->Cell(150,$tam,"$o56_elemento"." - ".$o56_descr ,0,1,"L",0);
  		$pdf->SetFont('Arial','',7);	
	       /* header  */
                 $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),'TB',0,"C",1);
	         $pdf->Cell(20,$tam,strtoupper($RLc69_sequen),'TB',0,"C",1);	 
                 $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,'TB',0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc69_debito),'TB',0,"C",1); // recurso
	         $pdf->Cell(35,$tam,strtoupper($RLc60_descr) ,'TB',0,"L",1); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_credito),'TB',0,"C",1); // recurso
	         $pdf->Cell(35,$tam,strtoupper($RLc60_descr)  ,'TB',0,"L",1); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor)  ,'TB',1,"R",1); // cod+estrut dotatao // quebra linha       
	    }
	    /* detalhe */
	       $pdf->Cell(20,$tam,$c69_codlan        ,0,0,"C",0);
	       $pdf->Cell(20,$tam,$c69_sequen        ,0,0,"C",0);	 
               $pdf->Cell(20,$tam,$c69_data          ,0,0,"C",0);
               $pdf->Cell(20,$tam,$c69_debito        ,0,0,"C",0); // recurso
  	       $pdf->Cell(35,$tam,substr($debito_descr,0,25) ,0,0,"L",0); // recurso
	       $pdf->Cell(20,$tam,$c69_credito               ,'0',0,"C",0);
               $pdf->Cell(35,$tam,substr($credito_descr,0,25),'0',0,"L",0);        
               $pdf->Cell(20,$tam,db_formatar($c69_valor,'f'),'0',1,"R",0); // cod+estrut dotatao // quebra linha
            // outros dados
	       $pdf->Cell(35,$tam,"REC: $c74_codrec" ,'0',0,"C",0);
               $pdf->Cell(35,$tam,"SUP: $c79_codsup" ,'0',0,"C",0);
  	       $pdf->Cell(35,$tam,"EMP: $c75_numemp" ,'0',0,"C",0); // recurso
               $pdf->Cell(35,$tam,"DOT: $c73_coddot" ,'0',0,"C",0);        
               $pdf->Cell(35,$tam,"DIG: $c78_chave" ,'0',0,"C",0);       
	       $pdf->Cell(15,$tam," " ,'0',1,"C",0);       
	    //--  
               $pdf->Cell(80,$tam,"DOCUMENTO: $c53_coddoc - $c53_descr" ,'B',0,"L",0);
  	       $pdf->Cell(110,$tam,"HISTORICO: $c50_codhist- $c50_descr",'B',1,"L",0); // recurso
	    //---- outros dados
	    $pdf->Ln(1);
            /* somatorio  */
	     $__total += $c69_valor;
	    /*  */
            if ($x == ($rows -1)) {
                      //--
                      $sql_resumido ="select c53_coddoc,c53_descr,sum(conlancam.c70_valor) as total
                             from conlancamdot
						        	inner join orcdotacao  on o58_coddot=c73_coddot and o58_anousu=c73_anousu
									inner join orcelemento on o56_codele=orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu
					                inner join conlancam on c70_codlan=c73_codlan and c70_anousu=c73_anousu
					                inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
					                inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
		              		where orcelemento.o56_codele= $o56_codele
			           				and $txt_where
                            group by c53_coddoc,c53_descr
		             		order by c53_coddoc   ";
		      $rr=$clconlancamval->sql_record($sql_resumido);     
		      $pdf->SetFont('Arial','B',7);	 
		      $pdf->setX(80);
                      $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),'B',0,"C",0); // recurso
		      $pdf->Cell(80,$tam,strtoupper($RLc53_descr),'B',0,"L",0); // recurso
                      $pdf->Cell(20,$tam,"SOMATORIO",'B',1,"R",0);  // cod+estrut dotatao // quebra linha		
  	              $pdf->Ln(1);
		      for($i=0;$i < $clconlancamval->numrows;$i++){
		           db_fieldsmemory($rr,$i);
   		             $pdf->setX(80);
        	             $pdf->Cell(20,$tam,$c53_coddoc            ,0,0,"C",0); // recurso
       	                     $pdf->Cell(80,$tam,$c53_descr            ,0,0,"L",0); // recurso
               	    	     $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
	              }	
		      //--
  	              $pdf->Ln(10);
                  $sql_total ="
                     select c53_coddoc,c53_descr,sum(conlancam.c70_valor) as total
                     from conlancamdot
			            inner join orcdotacao  on o58_coddot=c73_coddot and o58_anousu=c73_anousu
				        inner join orcelemento on o56_codele=orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu
		                inner join conlancam on c70_codlan=c73_codlan and c70_anousu=c73_anousu
		                inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
		                inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
		             where $txt_where
                             group by c53_coddoc,c53_descr
		             order by c53_coddoc   ";
	
		      $rr=$clconlancamval->sql_record($sql_total);     
		      $pdf->SetFont('Arial','B',7);	 
		      // $pdf->setX(80);
                      $pdf->Cell(20,$tam,"TOTAL",'B',0,"C",0); // recurso
                      $pdf->Cell(50,$tam," ",'B',0,"C",0); // recurso
                      $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),'B',0,"C",0); // recurso
		      $pdf->Cell(80,$tam,strtoupper($RLc53_descr),'B',0,"L",0); // recurso
                      $pdf->Cell(20,$tam,"SOMATORIO",'B',1,"R",0);  // cod+estrut dotatao // quebra linha		
  	              $pdf->Ln(1);
		      for($i=0;$i < $clconlancamval->numrows;$i++){
		           db_fieldsmemory($rr,$i);
   		             $pdf->setX(80);
        	             $pdf->Cell(20,$tam,$c53_coddoc            ,0,0,"C",0); // recurso
       	                     $pdf->Cell(80,$tam,$c53_descr            ,0,0,"L",0); // recurso
               	    	     $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
	              }	
                      //---- 
           }
	   /* */
     }  
} /* end quebra ="g" */

/* geral sintetico */
if ($quebra=="g" and $tipo=="s"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa nova pagina
	    if ($pdf->gety() > $pdf->h - 40){
 	       $pdf->addpage("P"); 
	    }

            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
	         $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),1,0,"C",1); // recurso
		 $pdf->Cell(100,$tam,strtoupper($RLc53_descr),1,0,"L",1); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor) ,1,1,"C",1);  // cod+estrut dotatao // quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $o56_codele) {
	        /*  */
		if ($x > 1 ){
		    $pdf->Ln(2);
		    $pdf->setX(150);
	            $pdf->SetFont('Arial','B',7);	
		    //-- mostra total dos conhistdoc
		    //-- //classe conlancamcgm
      	            $sql01 ="select c53_coddoc as doc,c53_descr as descr,sum(conlancam.c70_valor) as total
                             from conlancamdot
						        	inner join orcdotacao  on o58_coddot=c73_coddot and o58_anousu=c73_anousu
									inner join orcelemento on o56_codele=orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu
					                inner join conlancam on c70_codlan=c73_codlan and c70_anousu=c73_anousu
					                inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
					                inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
		             where o56_codele =  $repete
			           and $txt_where
                             group by c53_coddoc,c53_descr
		             order by c53_coddoc   ";
	            $rr= $auxiliar->sql_record($sql01);				   			   
               	    for ($h=0; $h<$auxiliar->numrows;$h++){
                        db_fieldsmemory($rr,$h,true);
		  	   $pdf->setX(50);
	                   // $pdf->Cell(20,$tam,$c53_coddoc            ,'B',0,"C",0);
                           $pdf->Cell(20,$tam,$doc            ,'B',0,"C",0);
		           $pdf->Cell(100,$tam,$descr            ,'B',0,"L",0);
 		           $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0); // quebra linha              
                      //---------------	              		
 	            }
                }
	        /*  */
	        $repete = $o56_codele;
	        $pdf->Ln(); $pdf->Ln();
		$pdf->SetFont('Arial','B',8);	
                $pdf->Cell(150,$tam,"$o56_elemento"." - ".$o56_descr ,0,1,"L",0);
  		$pdf->SetFont('Arial','',7);	
	    }
	    /* detalhe */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$c70_codlan                ,0,0,"C",0);
            // $pdf->Cell(20,$tam,$c69_sequen ,1,0,"C",1);	 
            $pdf->Cell(20,$tam,$c70_data                  ,0,0,"C",0);
            $pdf->Cell(20,$tam,$c53_coddoc                ,0,0,"C",0); // recurso
    	    $pdf->Cell(100,$tam,$c53_descr                ,0,0,"L",0); // recurso
            $pdf->Cell(20,$tam,db_formatar($c70_valor,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
            /*  */
            /*  */
            if ($x == ($rows -1)) {
		    $pdf->Ln(2);
		    $pdf->setX(150);
	            $pdf->SetFont('Arial','B',7);	
		    //-- mostra total dos conhistdoc
		    //-- //classe conlancamcgm
      	            $sql01 ="select c53_coddoc,c53_descr,sum(conlancam.c70_valor) as total
                             from conlancamdot
						        	inner join orcdotacao  on o58_coddot=c73_coddot and o58_anousu=c73_anousu
									inner join orcelemento on o56_codele=orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu
					                inner join conlancam on c70_codlan=c73_codlan and c70_anousu=c73_anousu
					                inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
					                inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
		             where o56_codele =  $repete
			           and $txt_where
                             group by c53_coddoc,c53_descr
		             order by c53_coddoc   ";
	            $rr= $auxiliar->sql_record($sql01);				   			   
               	    for ($h=0; $h<$auxiliar->numrows;$h++){
                        db_fieldsmemory($rr,$h,true);
		  	   $pdf->setX(50);
	                   $pdf->Cell(20,$tam,$c53_coddoc            ,'B',0,"C",0);
		           $pdf->Cell(100,$tam,$c53_descr            ,'B',0,"L",0);
 		           $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0); // quebra linha              
                      //---------------	              		
 	            }
                    /*  */	      
	            $pdf->Ln(10);
	            $pdf->setX(150);
	            $pdf->SetFont('Arial','B',7);	
                    $sql01 ="select c53_coddoc,c53_descr,sum(conlancam.c70_valor) as total
                             from conlancamdot
			        			inner join orcdotacao  on o58_coddot=c73_coddot and o58_anousu=c73_anousu
								inner join orcelemento on o56_codele=orcdotacao.o58_codele and o56_anousu = orcdotacao.o58_anousu
				                inner join conlancam on c70_codlan=c73_codlan and c70_anousu=c73_anousu
				                inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
				                inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
		             where $txt_where
                             group by c53_coddoc,c53_descr
		             order by c53_coddoc   ";
	    
      	     	    $rr= $auxiliar->sql_record($sql01);				   			   
               	    for ($h=0; $h<$auxiliar->numrows;$h++){
                        db_fieldsmemory($rr,$h,true);
		  	   $pdf->setX(50);
	                   $pdf->Cell(20,$tam,$c53_coddoc            ,'B',0,"C",0);
		           $pdf->Cell(100,$tam,$c53_descr            ,'B',0,"L",0);
 		           $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0); // quebra linha              
                      //---------------	              		
		    }  
            }
     }  
}/* fim geral sintetico */
//include("fpdf151/geraarquivo.php");
$pdf->output();

?>