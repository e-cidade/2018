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
include("classes/db_pagordemnota_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clconlancamval  = new cl_conlancamval;
$clconlancamcgm  = new cl_conlancamcgm;
$clconlancam     = new cl_conlancam;
$auxiliar        = new cl_conlancam;
$clorcsuplem     = new cl_orcsuplem;
$clconlancamrec  = new cl_conlancamrec;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdot  = new cl_conlancamdot;
$clconlancamdig  = new cl_conlancamdig;

$clpagordemnota = new cl_pagordemnota;

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
if ($so_emp == 'n') {

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
   $txt_where="1=1";
   if (isset($lista)){
       if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and c76_numcgm in  $w";
       } else {
           $txt_where= $txt_where." and c76_numcgm not in  $w";
       }	 
   }  
   if ($tipo=="a") {
       $txt_where = $txt_where." and c76_data between '$data1' and '$data2' "; 
   } else if ($tipo=="s"){
       $txt_where = $txt_where." and c76_data between '$data1' and '$data2' "; 
   }  
    // echo $txt_where;exit;
    $sql_analitico= "select c76_numcgm,
                            z01_nome,
                            c76_codlan,
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
						                c78_chave
                       from conlancamcgm 
                      inner join cgm 		           on cgm.z01_numcgm          = conlancamcgm.c76_numcgm 
                      inner join conlancamval 	   on conlancamval.c69_codlan = conlancamcgm.c76_codlan
                      inner join conplanoreduz aa  on c69_credito             = aa.c61_reduz 
                                                  and aa.c61_anousu           = ".db_getsession("DB_anousu")."
                                                  and aa.c61_instit           = ".db_getsession("DB_instit")."                 
                      inner join conplano      bb  on bb.c60_codcon           = aa.c61_codcon 
                                                  and bb.c60_anousu           = aa.c61_anousu 
                      inner join conplanoreduz cc  on c69_debito              = cc.c61_reduz 
                                                  and cc.c61_anousu           = ".db_getsession("DB_anousu")."
                                                  and cc.c61_instit           = ".db_getsession("DB_instit")."
                      inner join conplano      dd  on dd.c60_codcon           = cc.c61_codcon 
                                                  and dd.c60_anousu           = cc.c61_anousu 
		                  inner join conhist           on c50_codhist             = c69_codhist
		                  inner join conlancam         on c70_codlan              = c69_codlan 
		                                              and c70_anousu              = c69_anousu 
                      inner join conlancamdoc 	   on c71_codlan              = c70_codlan 
                      inner join conhistdoc 	     on c53_coddoc              = conlancamdoc.c71_coddoc 
				         left outer join conlancamrec      on c74_codlan              = c70_codlan   
				                                          and c74_anousu              = c70_anousu 
				         left outer join conlancamsup      on c79_codlan              = c70_codlan
				         left outer join conlancamemp      on c75_codlan              = c70_codlan
				         left outer join conlancamdot      on c73_codlan              = c70_codlan   
				                                          and c73_anousu              = c70_anousu
				         left outer join conlancamdig      on c78_codlan              = c70_codlan
         where $txt_where
         order by c76_numcgm,
                  c69_codlan,
		              c69_sequen,
           	      c69_data";
           	      
//echo $sql_analitico;exit;
$sql_sintetico= "select c76_numcgm,
                        z01_nome,
                        c76_codlan,
                        c70_codlan,
                        c70_data, 
                        c53_coddoc,
                        c53_descr, 
                        c70_valor
                   from conlancamcgm 
                  inner join cgm 		           on cgm.z01_numcgm          = conlancamcgm.c76_numcgm 
		              inner join conlancam         on c70_codlan              = c76_codlan  
                  inner join conlancamdoc      on c71_codlan              = c70_codlan 
                  inner join conhistdoc 	     on c53_coddoc              = conlancamdoc.c71_coddoc 
                  inner join conlancamval      on conlancamval.c69_codlan = conlancamcgm.c76_codlan
                  inner join conplanoreduz aa  on c69_credito             = aa.c61_reduz 
                                              and aa.c61_anousu           = ".db_getsession("DB_anousu")."
                                              and aa.c61_instit           = ".db_getsession("DB_instit")."
                  inner join conplanoreduz cc  on c69_debito              = cc.c61_reduz 
                                              and cc.c61_anousu           = ".db_getsession("DB_anousu")."
                                              and cc.c61_instit           = ".db_getsession("DB_instit")."
                  
                  where $txt_where
                  order by c76_numcgm,
                           c76_codlan,
		                       c76_data";

if ($tipo =="a") {
       // echo $sql_analitico;exit;
       $res=$clconlancam->sql_record($sql_analitico);
       //  db_criatabela($res);    exit;
       if ($clconlancam->numrows > 0 ){
           $rows=$clconlancam->numrows; 
       } else {
          db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
} else if ($tipo =="s"){
       // echo $sql_analitico;exit;
       $res=$clconlancam->sql_record($sql_sintetico);
       // db_criatabela($res);    exit;
       if ($clconlancam->numrows > 0 ){
           $rows=$clconlancam->numrows; 
       } else {
          db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
}
// db_criatabela($res); exit;

//////////////////////////////////////////////////////////////////////
$head3 = "EXTRATO POR CREDOR";
$head5 = "PERÍODO : ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
if ($tipo=="a"){
  $head6 = "TIPO :  Geral, Analitico";
} else {
  $head6 = "TIPO :  Geral, Sintético";
}
$head7= "Quebra: \"NumCGM : Nome \" ";
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
if ($tipo=="a") {
  
	$pdf->SetFont('Arial','',7);
  for ($x=0; $x < $rows;$x++){
     db_fieldsmemory($res,$x,true);
            // testa novapagina 
     if ($pdf->gety() > $pdf->h - 40){
 	      $pdf->addpage("P"); 
	      $imprime_header=true;
	   }

	   if ($imprime_header==true) {
		   $imprime_header=false;
		 }
		 
	    /* ----------- */
	  if ($repete != $c76_numcgm) {
	        /*  */
		   if ($x > 0 ){
         
		   	 $sql_resumido ="select c53_coddoc,
                                c53_descr,
                                sum(c70_valor) as total
                           from conlancamcgm
				                  inner join conlancam    on c70_codlan = c76_codlan
		     	                inner join conlancamdoc on c71_codlan = conlancam.c70_codlan
				                  inner join conhistdoc   on c53_coddoc = conlancamdoc.c71_coddoc		     
	         		            where conlancamcgm.c76_numcgm = $repete
                          group by c53_coddoc,c53_descr
		                     order by c53_coddoc   ";
		     $rr=$clconlancamval->sql_record($sql_resumido);     
		     
		     $pdf->SetFont('Arial','B',7);	 
		     $pdf->setX(80);
         $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),'B',0,"C",0); // recurso
		     $pdf->Cell(80,$tam,strtoupper($RLc53_descr),'B',0,"L",0); // recurso
         $pdf->Cell(20,$tam,"SOMATORIO",'B',1,"R",0);  // cod+estrut dotatao // quebra linha		
  	     $pdf->Ln(1);

  	     for ($i=0;$i < $clconlancamval->numrows;$i++) {
		        db_fieldsmemory($rr,$i);
   		      $pdf->setX(80);
        	  $pdf->Cell(20,$tam,$c53_coddoc            ,0,0,"C",0); // recurso
       	    $pdf->Cell(80,$tam,$c53_descr            ,0,0,"L",0); // recurso
            $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
	       }		 
       }
       
	        /*  */
	     $repete = $c76_numcgm;
       $pdf->Ln(); $pdf->Ln();    
		   $pdf->SetFont('Arial','B',8);	
       $pdf->Cell(20,$tam,"$c76_numcgm"." - ",0,0,"R",0);
       $pdf->Cell(100,$tam,"$z01_nome" ,0,1,"L",0);  //quebra inha
  		 $pdf->SetFont('Arial','',7);	

  		 /* header  */
       $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),'TB',0,"C",0);
	     $pdf->Cell(20,$tam,strtoupper($RLc69_sequen),'TB',0,"C",0);	 
       $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,'TB',0,"C",0);
       $pdf->Cell(20,$tam,strtoupper($RLc69_debito),'TB',0,"C",0); // recurso
	     $pdf->Cell(35,$tam,strtoupper($RLc60_descr) ,'TB',0,"L",0); // recurso
       $pdf->Cell(20,$tam,strtoupper($RLc69_credito),'TB',0,"C",0); // recurso
	     $pdf->Cell(35,$tam,strtoupper($RLc60_descr) ,'TB',0,"L",0); // recurso
       $pdf->Cell(20,$tam,strtoupper($RLc69_valor),'TB',1,"R",0); // cod+estrut dotatao // quebra linha       
	  }

	  /* detalhe */
	  $pdf->Cell(20,$tam,$c69_codlan        ,0,0,"C",0);
	  $pdf->Cell(20,$tam,$c69_sequen        ,0,0,"C",0);	 
    $pdf->Cell(18,$tam,$c69_data          ,0,0,"C",0);
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
       $sql_resumido ="select c53_coddoc,
                              c53_descr,
                              sum(c70_valor) as total
                         from conlancamcgm
				                inner join conlancam    on c70_codlan = c76_codlan
				                inner join conlancamdoc on c71_codlan = conlancam.c70_codlan
				                inner join conhistdoc   on c53_coddoc = conlancamdoc.c71_coddoc		     
		             		    where conlancamcgm.c76_numcgm = $c76_numcgm
                        group by c53_coddoc,c53_descr
		                    order by c53_coddoc   ";
		   $rr=$clconlancamval->sql_record($sql_resumido);     
		   $pdf->SetFont('Arial','B',7);	 
		   $pdf->setX(80);
       $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),'B',0,"C",0); // recurso
		   $pdf->Cell(80,$tam,strtoupper($RLc53_descr),'B',0,"L",0); // recurso
       $pdf->Cell(20,$tam,"SOMATORIO",'B',1,"R",0);  // cod+estrut dotatao // quebra linha		
  	   $pdf->Ln(1);

  	   for ($i=0;$i < $clconlancamval->numrows;$i++) {
		      db_fieldsmemory($rr,$i);
   		    $pdf->setX(80);
        	$pdf->Cell(20,$tam,$c53_coddoc            ,0,0,"C",0); // recurso
       	  $pdf->Cell(80,$tam,$c53_descr            ,0,0,"L",0); // recurso
          $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
	     }	
		      //--
  	   $pdf->Ln(10);
       
  	   $sql_total ="select c53_coddoc,
  	                       c53_descr,sum(c70_valor) as total
                      from conlancamcgm
				             inner join conlancam on c70_codlan=c76_codlan
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
		   for ($i=0;$i < $clconlancamval->numrows;$i++) {
		      db_fieldsmemory($rr,$i);
   		    $pdf->setX(80);
        	$pdf->Cell(20,$tam,$c53_coddoc            ,0,0,"C",0); // recurso
       	  $pdf->Cell(80,$tam,$c53_descr            ,0,0,"L",0); // recurso
          $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
	     }	
   }
  }  
} /* end quebra ="g" */

/* geral sintetico */
if ($tipo=="s"){
   $pdf->SetFont('Arial','',7);
   for ($x=0; $x < $rows;$x++){
      db_fieldsmemory($res,$x,true);

      // testa nova pagina
	    if ($pdf->gety() > $pdf->h - 40){
 	       $pdf->addpage("P"); 
	    }

      if ($imprime_header==true) {
         $pdf->Ln();
	       $pdf->SetFont('Arial','B',7);	 
	       $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),1,0,"C",1);
         $pdf->Cell(18,$tam,strtoupper($RLc69_data)  ,1,0,"C",1);
         $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),1,0,"C",1); // recurso
		     $pdf->Cell(100,$tam,strtoupper($RLc53_descr),1,0,"L",1); // recurso
         $pdf->Cell(20,$tam,strtoupper($RLc69_valor) ,1,1,"C",1);  // cod+estrut dotatao // quebra linha
		     $pdf->Ln();
	       $pdf->SetFont('Arial','',7);	
		     $imprime_header=false;
      }
      
	    /* ----------- */
	    if ($repete != $c76_numcgm) {
	        /*  */
		     if ($x > 0 ){
   		     $pdf->setX(150);
	         $pdf->SetFont('Arial','B',7);
	         	
		       //-- mostra total dos conhistdoc
		       //-- //classe conlancamcgm
	 	       $sql01 = "select c53_coddoc, 
	 	                        c53_descr, 
	 	                        sum(conlancam.c70_valor) as total
                       from conlancamcgm
                      inner join conlancamdoc on c71_codlan=c76_codlan
                      inner join conhistdoc   on c71_coddoc=c53_coddoc
                      inner join conlancam    on c70_codlan=c71_codlan
                      where c76_numcgm = $repete
			  		 	        group by c53_coddoc, c53_descr
				  	 	        order by c53_descr";
      	   $rr= $auxiliar->sql_record($sql01);				   			   
		       $pdf->Ln(3);
           for ($h=0; $h<$auxiliar->numrows;$h++){
              db_fieldsmemory($rr,$h,true);
		  	      $pdf->setX(50);
	            $pdf->Cell(20,$tam,$c53_coddoc            ,'TB',0,"C",0);
		          $pdf->Cell(100,$tam,$c53_descr            ,'TB',0,"L",0);
 		          $pdf->Cell(20,$tam,db_formatar($total,'f'),'TB',1,"R",0); // quebra linha              
           }
         }
         /*  */

         $repete = $c76_numcgm;
	       $pdf->Ln(); $pdf->Ln();
		     $pdf->SetFont('Arial','B',8);	
         $pdf->Cell(20,$tam,"$c76_numcgm",1,0,"C",0);
         $pdf->Cell(100,$tam,"$z01_nome",1,1,"L",0);  //quebra inha
  		   $pdf->SetFont('Arial','',7);	
	    }
	    /* detalhe */
	    
	    $pdf->Ln(1);
      $pdf->Cell(20,$tam,$c70_codlan                ,0,0,"C",0);
      $pdf->Cell(18,$tam,$c70_data                  ,0,0,"C",0);
      $pdf->Cell(20,$tam,$c53_coddoc                ,0,0,"C",0); // recurso
    	$pdf->Cell(100,$tam,$c53_descr                ,0,0,"L",0); // recurso
      $pdf->Cell(20,$tam,db_formatar($c70_valor,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha

      if ($x == ($rows -1)) {
        $pdf->setX(150);
	      $pdf->SetFont('Arial','B',7);	
		    //-- mostra total dos conhistdoc
		    //-- //classe conlancamcgm
	 	    $sql01 = "select c53_coddoc, 
	 	                     c53_descr, 
	 	                     sum(conlancam.c70_valor) as total
                    from conlancamcgm
                   inner join conlancamdoc on c71_codlan=c76_codlan
                   inner join conhistdoc   on c71_coddoc=c53_coddoc
                   inner join conlancam    on c70_codlan=c71_codlan
                   where c76_numcgm = $repete
						       group by c53_coddoc, c53_descr
						       order by c53_descr";
      	$rr= $auxiliar->sql_record($sql01);				   			   
		    $pdf->Ln(3);
        for ($h=0; $h<$auxiliar->numrows;$h++){
           db_fieldsmemory($rr,$h,true);
		  	   $pdf->setX(50);
	         $pdf->Cell(20,$tam,$c53_coddoc            ,'TB',0,"C",0);
		       $pdf->Cell(100,$tam,$c53_descr            ,'TB',0,"L",0);
 		       $pdf->Cell(20,$tam,db_formatar($total,'f'),'TB',1,"R",0); // quebra linha              
		    }  
      }
	   /* */
   }  
}/* fim geral sintetico */


/// so empenhos = sim
} else {

  $xtipo = ' and c53_coddoc in (';
  $tem_outro = false;
  if (isset($emp)) {
    $xtipo .= '1,2,32';
    $tem_outro = true;
  }
  
  if (isset($liq)) {
    if ($tem_outro == true)
      $xtipo .= ',';
    $xtipo .= '3,4,23,24,33,34'; 
    $tem_outro = true;
  }
  
  if (isset($pag)) {
    if ($tem_outro == true)
      $xtipo .= ',';
    $xtipo .= '5,6,35,36,37'; 
  }
  $xtipo .= ')';

  if (isset($lista)) {
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
   $txt_where="1=1";
   if (isset($lista)){
       if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and e60_numcgm in  $w";
       } else {
           $txt_where= $txt_where." and e60_numcgm not in  $w";
       }	 
   } 
   $txt_where = $txt_where." and c70_data between '$data1' and '$data2' "; 

$head3 = "EXTRATO POR CREDOR";
$head5 = "PERÍODO : ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
if ($tipo=="a"){
  $head6 = "TIPO :  Geral, Analitico";
} else {
  $head6 = "TIPO :  Geral, Sintético";
}
$head7= "Quebra: \"NumCGM : Nome \" ";

$sql = "
select conlancam.*,
       e60_numcgm,
       z01_nome,
       e60_numemp,
       e60_anousu,
       lpad(e60_codemp,5,0) as e60_codemp,
       c71_coddoc,
       c53_descr,
       o56_elemento, 
       c80_codord,
       o56_descr,
       c82_reduz
from conlancam 
     inner join conlancamemp 	  on c70_codlan = c75_codlan
     left  join conlancamord    on c70_codlan = c80_codlan
     left  join conlancampag    on c82_codlan = c70_codlan
     inner join conlancamdoc 	  on c71_codlan = c70_codlan 
     inner join conhistdoc    	on c53_coddoc = c71_coddoc 
     inner join empempenho 	    on e60_numemp = c75_numemp
                               and e60_instit = ".db_getsession("DB_instit")." 
     inner join orcdotacao 	    on o58_coddot = e60_coddot 
                        			 and e60_anousu = o58_anousu 
                               and o58_instit = ".db_getsession("DB_instit")."                        			 
     inner join orcelemento 	  on o58_codele = o56_codele 
                               and o56_anousu = o58_anousu
     inner join cgm 		        on z01_numcgm = e60_numcgm
where $txt_where $xtipo
order by e60_numcgm,e60_anousu,e60_codemp,c70_codlan
";


$sql2 = "
select c53_descr,
       sum(c70_valor)
from conlancam 
     inner join conlancamemp 	on c70_codlan = c75_codlan
     left  join conlancamord  on c70_codlan = c80_codlan
     left  join conlancampag  on c82_codlan = c70_codlan
     inner join conlancamdoc 	on c71_codlan = c70_codlan 
     inner join conhistdoc 	  on c53_coddoc = c71_coddoc 
     inner join empempenho 	  on e60_numemp = c75_numemp
                             and e60_instit = ".db_getsession("DB_instit")." 
     inner join orcdotacao 	  on o58_coddot = e60_coddot 
     				                 and e60_anousu = o58_anousu
     				                 and o58_instit = ".db_getsession("DB_instit")."  
     inner join orcelemento 	on o58_codele = o56_codele 
                             and o56_anousu = o58_anousu
     inner join cgm 		on z01_numcgm = e60_numcgm
where $txt_where $xtipo
group by c53_descr
";

$result = pg_exec($sql);
$result2 = pg_exec($sql2); 

//db_criatabela($result);
//db_criatabela($result2);exit;

$xxnum = pg_numrows($result);
$xxnum2= pg_numrows($result2);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Movimentações para este credor.');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 5;
$total_liq = 0;
$total_anuliq = 0;
$total_pago = 0;
$total_anupago = 0;
$cor = 0;
$emp = 0;
$cgm = 0;
   for($x=0;$x<pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   
   if($cgm != $e60_numcgm && $troca != 1){
     $troca = 1;
   }
     
   if ($pdf->gety() > ($pdf->h - 30) || $troca ==1){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->multicell(0,4,'Credor : '.$e60_numcgm.' - '.$z01_nome,0,"L");
      $cgm = $e60_numcgm;
      $pdf->ln(3);
      $pdf->cell(23,$alt,'EMPENHO',1,0,"C",1);
      $pdf->cell(18,$alt,'DATA',1,0,"C",1);
      $pdf->cell(23,$alt,'ORDEM',1,0,"C",1);
      $pdf->cell(33,$alt,'NOTAS',1,0,"C",1);
      $pdf->cell(23,$alt,'CONTA',1,0,"C",1);
      $pdf->cell(50,$alt,'MOVIMENTAÇÃO',1,0,"C",1);
      $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   if($emp != $e60_codemp){
     $emp = $e60_codemp;
     if($cor == 0)
       $cor = 1;
     else
       $cor = 0;
   }
   $pdf->setfont('arial','',8);
//   $dots = $pdf->preenchimento($r13_descr,60);
   $pdf->cell(23,$alt,$e60_codemp.'/'.$e60_anousu,0,0,"C",$cor);
   $pdf->cell(18,$alt,db_formatar($c70_data,'d'),0,0,"C",$cor);
   $pdf->cell(23,$alt,$c80_codord,0,0,"C",$cor);
   
   if( $c80_codord > 0 ){
     $res = $clpagordemnota->sql_record($clpagordemnota->sql_query($c80_codord,null,'e69_numero'));
     if( $clpagordemnota->numrows > 0){ 
       $notas = "";
       $sepnotas = "";
       for( $ord = 0; $ord < $clpagordemnota->numrows; $ord ++){
       	 db_fieldsmemory($res,$ord);
       	 $notas .= $sepnotas.trim($e69_numero);
       	 $sepnotas = "-";
       } 
       $pdf->cell(33,$alt,$notas,0,0,"L",$cor);
     }else{
     	$pdf->cell(33,$alt,'',0,0,"L",$cor);
     }
   }else{
     $pdf->cell(33,$alt,'',0,0,"L",$cor);
   }   	
   
   
   $pdf->cell(23,$alt,$c82_reduz,0,0,"C",$cor);
   $pdf->cell(50,$alt,$c53_descr,0,0,"L",$cor);
   $pdf->cell(23,$alt,db_formatar($c70_valor,'f'),0,1,"R",$cor);
   }
   
   $pdf->ln(3);
   $pdf->setfont('arial','b',8);
   $pdf->cell(142,$alt,'MOVIMENTAÇÃO',1,0,"C",1);
   $pdf->cell(30,$alt,'TOTAIS',1,1,"C",1);
   
   for($y=0;$y<pg_numrows($result2);$y++){
       db_fieldsmemory($result2,$y);
       
       if($y % 2)
	 $cor = 1;
       else
	 $cor =0;
       
       $pdf->setfont('arial','',8);
       $pdf->cell(142,$alt,$c53_descr,0,0,"L",$cor);
       $pdf->cell(30,$alt,db_formatar($sum,'f'),0,1,"R",$cor);
   } 
   
   
}
//include("fpdf151/geraarquivo.php");
$pdf->output();

?>