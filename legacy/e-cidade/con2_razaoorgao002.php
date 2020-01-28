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
include("dbforms/db_funcoes.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamval_classe.php");

db_postmemory($HTTP_POST_VARS);
//-- classes
$clrotulo = new rotulocampo;
$clconlancamdot = new cl_conlancamdot;
$clconlancamval = new cl_conlancamval;
//-- rotulos
$clconlancamdot->rotulo->label();
$clconlancamval->rotulo->label();
$clrotulo->label("c60_descr");
//$clrotulo->label("c69_codlan");
//$clrotulo->label("c69_sequen");
//$clrotulo->label("c69_data");
//$clrotulo->label("c69_debito");
//$clrotulo->label("c69_valor");
$clrotulo->label("c53_coddoc");
$clrotulo->label("c53_descr");
$clrotulo->label("o40_orgao");
$clrotulo->label("o40_descr");

//$clrotulo->label("c69_credito");

//--

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
   $txt_where="1=1";
   if (isset($lista)){
       if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and o40_orgao in  $w";
       } else {
           $txt_where= $txt_where." and o40_orgao not in  $w";
       }	 
   }  
   if ($tipo=="a") { //analitico
       $txt_where = $txt_where." and c69_data between '$data1' and '$data2' "; 
   } else if ($tipo=="s"){ //sintetico
       $txt_where = $txt_where." and c70_data between '$data1' and '$data2' "; 
   }  else if ($tipo=="r"){ //resumido
       $txt_where = $txt_where." and c70_data between '$data1' and '$data2' "; 
   }  

$sql_analitico="select o40_orgao,
 		       o40_descr,
		           o58_coddot,
   	 	           o58_anousu,
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
                       c69_valor 
            from conlancamdot
                  inner join orcdotacao on o58_coddot=c73_coddot and o58_anousu=c73_anousu
   	              inner join orcorgao on o40_orgao=orcdotacao.o58_orgao and o40_anousu=orcdotacao.o58_anousu
	              inner join conlancamval 	 on conlancamval.c69_codlan = conlancamdot.c73_codlan
                  inner join conplanoreduz aa on c69_credito = aa.c61_reduz and aa.c61_anousu=".db_getsession("DB_anousu")."
                  inner join conplano      bb on bb.c60_codcon  = aa.c61_codcon and bb.c60_anousu  = aa.c61_anousu

                  inner join conplanoreduz cc on c69_debito = cc.c61_reduz and cc.c61_anousu=".db_getsession("DB_anousu")."
                  inner join conplano      dd on dd.c60_codcon  = cc.c61_codcon and dd.c60_anousu  = cc.c61_anousu
 
                  inner join conlancamdoc 	 on c71_codlan=c69_codlan 
                  inner join conhistdoc 	 on c53_coddoc = conlancamdoc.c71_coddoc 
            where $txt_where
            order by o40_orgao,
                     c69_codlan,
             	     c69_data";

$sql_sintetico="select o40_orgao,
 		       o40_descr,
		         o58_coddot,
   	 	         o58_anousu,
		       c70_codlan,
                       c70_data, 
		       c70_valor,
                         c53_coddoc,
                         c53_descr 
                from conlancamdot
	               inner join orcdotacao on o58_coddot=c73_coddot and o58_anousu=c73_anousu
		       inner join orcorgao on o40_orgao=orcdotacao.o58_orgao and o40_anousu=orcdotacao.o58_anousu
		       inner join conlancam on c70_codlan=c73_codlan   and c70_anousu=c73_anousu
		       inner join conlancamdoc 	 on c71_codlan=c70_codlan 
                       inner join conhistdoc 	 on c53_coddoc = conlancamdoc.c71_coddoc 
                where $txt_where
                order by o40_orgao,
                         c70_codlan,
           	         c70_data";

$sql_resumido ="select o40_orgao,o40_descr,c53_coddoc,c53_descr,sum(c70_valor) as total
                from conlancamdot
		           inner join orcdotacao on o58_coddot=c73_coddot and  o58_anousu=c73_anousu
		           inner join orcorgao on o40_orgao=orcdotacao.o58_orgao and o40_anousu=o58_anousu
		           inner join conlancam on c70_codlan=c73_codlan  and c70_anousu=c73_anousu
		           inner join conlancamdoc on c71_codlan=conlancam.c70_codlan
		           inner join conhistdoc on c53_coddoc=conlancamdoc.c71_coddoc		     
		       where $txt_where    
               group by o40_orgao,o40_descr,c53_coddoc,c53_descr
		       order by o40_orgao
                ";
		  

//--- campos
if ($tipo =="a") {      
       $res=$clconlancamdot->sql_record($sql_analitico);       
       if ($clconlancamdot->numrows > 0 ){
            $rows=$clconlancamdot->numrows; 
       } else {
            db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
} else if ($tipo =="s"){
       $res=$clconlancamdot->sql_record($sql_sintetico);       
       if ($clconlancamdot->numrows > 0 ){
            $rows=$clconlancamdot->numrows; 
       } else {
            db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
} else if ($tipo =="r"){
       $res=$clconlancamdot->sql_record($sql_resumido);       
       if ($clconlancamdot->numrows > 0 ){
            $rows=$clconlancamdot->numrows; 
       } else {
            db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
}


// db_criatabela($res);
// exit;

//////////////////////////////////////////////////////////////////////
$head3 = "RAZÃO POR ORGÂO";
if ($tipo =="r") 
      $head4= "Resumido por Orgão";
$head5 = "PERÍODO : ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
$head6 = "TIPO :".$quebra." ".$tipo;

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
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
	         $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),1,0,"C",1);
	         $pdf->Cell(20,$tam,strtoupper($RLc69_sequen),1,0,"C",1);	 
                 $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc69_debito),1,0,"C",1); // recurso
		 $pdf->Cell(80,$tam,strtoupper($RLc60_descr) ,1,0,"L",1); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor),1,1,"C",1); // cod+estrut dotatao // quebra linha
                 // $pdf->setX(150);
		 $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),1,0,"C",1);
		 $pdf->Cell(40,$tam,strtoupper($RLc53_descr)  ,1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc69_credito),1,0,"C",1);
                 $pdf->Cell(80,$tam,strtoupper($RLc60_descr)  ,1,0,"L",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor)  ,1,1,"C",1);
	         $pdf->SetFont('Arial','',7);	
		 $pdf->Ln();
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $o40_orgao) {
	        /*  */
		if ($x > 1 ){
		    $pdf->setX(150);
	            $pdf->SetFont('Arial','B',7);	
		    $pdf->Cell(20,$tam,"TOTAL "  ,0,0,"R",1);               
        	    $pdf->Cell(20,$tam,db_formatar($__total,'f'),0,0,"R",1);               
	            $pdf->SetFont('Arial','',7);	    
		    $__total=0;
                }
	        /*  */
	        $repete = $o40_orgao;
                $pdf->Ln(); $pdf->Ln();    
		$pdf->SetFont('Arial','B',8);	
                $pdf->Cell(20,$tam,"$o40_orgao" ,0,0,"C",0);
		$pdf->Cell(100,$tam,"$o40_descr",0,1,"L",0); // quebra linha
  		$pdf->SetFont('Arial','',7);	
	    }
	    /* detalhe */
	    $pdf->Cell(20,$tam,$c69_codlan   ,0,0,"C",0);
	    $pdf->Cell(20,$tam,$c69_sequen   ,0,0,"C",0);	 
            $pdf->Cell(20,$tam,$c69_data     ,0,0,"C",0);
            $pdf->Cell(20,$tam,$c69_debito   ,0,0,"C",0); // recurso
  	    $pdf->Cell(80,$tam,$debito_descr ,0,0,"L",0); // recurso
            $pdf->Cell(20,$tam,db_formatar($c69_valor,'f'),0,1,"R",0); // cod+estrut dotatao // quebra linha
             // $pdf->setX(150);
	    $pdf->Cell(20,$tam,$c53_coddoc   ,'B',0,"C",0);
	    $pdf->Cell(40,$tam,$c53_descr    ,'B',0,"C",0);
            $pdf->Cell(20,$tam,$c69_credito  ,'B',0,"C",0);
            $pdf->Cell(80,$tam,$credito_descr,'B',0,"L",0);
            $pdf->Cell(20,$tam,db_formatar($c69_valor,'f'),'B',1,"R",0);
	    $pdf->Ln(1);
            /* somatorio  */
	     $__total += $c69_valor;
	    /*  */
            if ($x == ($rows -1)) {
                 /* imprime totais -*/
 	         $pdf->setX(150);
	         $pdf->SetFont('Arial','B',7);	
	         $pdf->Cell(20,$tam,"TOTAL "  ,0,0,"R",1);               
                 $pdf->Cell(20,$tam,db_formatar($__total,'f'),0,0,"R",1);              
	         $pdf->SetFont('Arial','',7);	    
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
    	    {    /*
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
	         $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),1,0,"C",1); // recurso
		 $pdf->Cell(100,$tam,strtoupper($RLc53_descr),1,0,"L",1); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor) ,1,1,"C",1);  // cod+estrut dotatao // quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 */
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $o40_orgao) {
		if ($x > 1 ){
    	            $pdf->Cell(160,$tam,"Total"                 ,'T',0,"R",0); // recurso
                    $pdf->Cell(20,$tam,db_formatar($__total,'f'),'T',1,"R",0);  //  quebra linha         	            
		    $__total=0;
                }
	        /*  */
	        $repete = $o40_orgao;
	        $pdf->Ln(); $pdf->Ln();
		$pdf->SetFont('Arial','B',8);	
                $pdf->Cell(20,$tam,"$o40_orgao" ,0,0,"C",0);
                $pdf->Cell(100,$tam,"$o40_descr",0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
		// header das colunas
                $pdf->SetFont('Arial','B',7);	 
	        $pdf->Cell(20,$tam,strtoupper($RLc69_codlan),1,0,"C",1);
                $pdf->Cell(20,$tam,strtoupper($RLc69_data)  ,1,0,"C",1);
                $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),1,0,"C",1); // recurso
		$pdf->Cell(100,$tam,strtoupper($RLc53_descr),1,0,"L",1); // recurso
                $pdf->Cell(20,$tam,strtoupper($RLc69_valor) ,1,1,"C",1);  // cod+estrut dotatao // quebra linha
	        $pdf->SetFont('Arial','',7);	

	    }
	    /* detalhe */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$c70_codlan                ,0,0,"C",0);
            $pdf->Cell(20,$tam,$c70_data                  ,0,0,"C",0);
            $pdf->Cell(20,$tam,$c53_coddoc                ,0,0,"C",0); // recurso
    	    $pdf->Cell(100,$tam,$c53_descr                ,0,0,"L",0); // recurso
            $pdf->Cell(20,$tam,db_formatar($c70_valor,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
            /*  */
	    $__total += $c70_valor; 
            /*  */
            if ($x == ($rows -1)) {
    	            $pdf->Cell(160,$tam,"Total"                 ,'T',0,"R",0); // recurso
                    $pdf->Cell(20,$tam,db_formatar($__total,'f'),'T',1,"R",0);  //  quebra linha         	            
		    $__total=0;
 
                    /*
	 	    $sql01 = "select c53_coddoc, c53_descr, sum(conlancam.c70_valor) as total
                              from conlancamdot
			           inner join conlancam on c70_codlan=c73_codlan and c70_anousu=c73_anousu
				   inner join conlancamdoc on on c71_codlan=c70_codlan
                                   inner join conhistdoc   on c53_coddoc=c71_coddoc
                              where c73_coddot = $o58_coddot
			          and c73_anousu = $o58_anousu
			      group by c53_coddoc, c53_descr
			      order by c53_descr";
      	     	    $rr= $clconlancamcgm01->sql_record($sql01);				   			   
               	    for ($h=0; $h<$clconlancamcgm01->numrows;$h++){
                        db_fieldsmemory($rr,$h,true);
		  	   $pdf->setX(50);
	                   $pdf->Cell(20,$tam,$c53_coddoc            ,'B',0,"C",0);
		           $pdf->Cell(100,$tam,$c53_descr            ,'B',0,"L",0);
 		           $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0); // quebra linha              
                                    		
		    } */  
            }       
	   /* */
     }  
}/* fim geral sintetico */

/* resumido, quebra por orgao */
if ($quebra=="g" and $tipo=="r"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa nova pagina
	    if ($pdf->gety() > $pdf->h - 40){
 	       $pdf->addpage("P"); 
	    }

            if ($imprime_header==true)
    	    {  /*
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
		 $pdf->setX(40);
                 $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),1,0,"C",1); // recurso
		 $pdf->Cell(100,$tam,strtoupper($RLc53_descr),1,0,"L",1); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor) ,1,1,"C",1);  // cod+estrut dotatao // quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
		 */
            }
	    /* ----------- */
	    if ($repete != $o40_orgao) {
	        /*  */
		if ($x > 1 ){
		    $pdf->setX(50);
	            $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(20,$tam,"Total"                  ,'T',0,"C",0);  //quebra inha	    
                    $pdf->Cell(100,$tam,""                      ,'T',0,"R",0);  //quebra inha	    
	            $pdf->Cell(20,$tam,db_formatar($__total,'f'),'T',0,"R",0);  //quebra inha	    
		    $__total=0;
                }
	        /*  */
	        $repete = $o40_orgao;
	        $pdf->Ln(); $pdf->Ln();
		$pdf->SetFont('Arial','B',8);	
                $pdf->Cell(20,$tam,"$o40_orgao" ,0,0,"C",0);
                $pdf->Cell(100,$tam,"$o40_descr",0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
                // inprime header do detalhe
                 $pdf->SetFont('Arial','B',7);	 
		 $pdf->setX(50);
                 $pdf->Cell(20,$tam,strtoupper($RLc53_coddoc),'B',0,"C",0); // recurso
		 $pdf->Cell(100,$tam,strtoupper($RLc53_descr),'B',0,"L",0); // recurso
                 $pdf->Cell(20,$tam,strtoupper($RLc69_valor) ,'B',1,"R",0);  // cod+estrut dotatao // quebra linha		
	    }
	    /* detalhe */
	    $pdf->Ln(1);
	    $pdf->setX(50);
            $pdf->Cell(20,$tam,$c53_coddoc                ,0,0,"C",0); // recurso
    	    $pdf->Cell(100,$tam,$c53_descr                ,0,0,"L",0); // recurso
            $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);  // cod+estrut dotatao // quebra linha
            /*  */
	    $__total += $total; 
            /*  */
            if ($x == ($rows -1)) {
                    //---------------	              		
		    $pdf->setX(50);
	            $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(20,$tam,"Total"                  ,'T',0,"C",0);  //quebra inha	    
                    $pdf->Cell(100,$tam,""                      ,'T',0,"R",0);  //quebra inha	    
	            $pdf->Cell(20,$tam,db_formatar($__total,'f'),'T',0,"R",0);  //quebra inha	    
		    $__total=0;
        
	    }  
    }  /* end loop */

}/* fim geral sintetico */
//include("fpdf151/geraarquivo.php");
$pdf->output();

?>