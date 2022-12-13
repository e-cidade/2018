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
include("classes/db_empemphist_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempempenho = new cl_empempenho;
$clcgm = new cl_cgm;
$clorctiporec = new cl_orctiporec;
$clorcdotacao = new cl_orcdotacao;
$clorcorgao = new cl_orcorgao;
$clempemphist = new cl_empemphist;

$rotulo = new rotulocampo;
$rotulo->label("DBtxt_credor");
$rotulo->label("DBtxt_estrutural");
$rotulo->label("e40_codhist");
$rotulo->label("e40_descr");
$rotulo->label("o41_unidade");
$rotulo->label("o41_descr");
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();
$clempemphist->rotulo->label();

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

  $campos ="e60_numemp,e60_codemp,e60_emiss,e60_numcgm,z01_nome,e60_vlremp,e60_vlranu,e60_vlrliq,";
  $campos .="e60_vlrpag,e60_anousu,e60_coddot,o58_coddot,o58_orgao,o40_orgao,o40_descr,o58_unidade,o41_unidade,o41_descr,o15_codigo,o15_descr";
  $campos .=",fc_estruturaldotacao(".db_getsession("DB_anousu").",o58_coddot) as dl_estrutural";
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
// monta sql
   $txt_where="1=1 ";
   if (isset($lista)){
       if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and e60_coddot in  $w";
       } else {
           $txt_where= $txt_where." and e60_coddot not in  $w";
       }	 
   }  
   if (isset($data1) and isset($data2)){
        $txt_where = $txt_where." and e60_emiss between '$data1' and '$data2' "; 
   }  
////////  
if ($tipo =="a") { // relatorios analiticos
       if ($quebra == "g"){
            $ordem = "e60_coddot, e60_emiss ";
       } else if  ($quebra == "o") { 
            $ordem = "e60_coddot, o58_orgao, e60_emiss";
       } else if ($quebra =="u"){
            $ordem = "e60_coddot, o58_orgao, o58_unidade, e60_emiss";
       }	 
       $res=$clempempenho->sql_record($clempempenho->sql_query(null,$campos,$ordem,$txt_where));
       if ($clempempenho->numrows > 0 ){
           $rows=$clempempenho->numrows; 
       } else {
           db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
       }
} else {
// é sintético monta sql direto 
   if($quebra =="g"){
        $sql = "select e60_coddot,fc_estruturaldotacao(".db_getsession("DB_anousu").",e60_coddot) as dl_estrutural,
                       sum(e60_vlremp) as e60_vlremp,
                       sum(e60_vlranu) as e60_vlranu,
          	       sum(e60_vlrliq) as e60_vlrliq,
       	               sum(e60_vlrpag) as e60_vlrpag
   	        from empempenho
		     inner join cgm on z01_numcgm = empempenho.e60_numcgm
                     inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu 
 		                           and  orcdotacao.o58_coddot = empempenho.e60_coddot
  	        where 
	            e60_emiss between '$data1' and '$data2'  ";
         if (isset($lista)){
              if (isset($ver) and $ver=="com"){
                 $sql = $sql ." and e60_coddot in  $w";
              } else {
                 $sql = $sql ." and e60_coddot not in  $w";
              }	 
         }  
         $sql = $sql ." group by e60_coddot, dl_estrutural  order by e60_coddot ";
	 $res=$clempempenho->sql_record($sql);
         if ($clempempenho->numrows > 0 ){
             $rows=$clempempenho->numrows; 
         } else {
              db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
         }
    } 
    else if ($quebra =="o") {
       /*  sintético por orgao */
        $sql = "select e60_coddot, fc_estruturaldotacao(".db_getsession("DB_anousu").",e60_coddot) as dl_estrutural,
                       o40_orgao,o40_descr, 
                       sum(e60_vlremp) as e60_vlremp,
                       sum(e60_vlranu) as e60_vlranu,
          	       sum(e60_vlrliq) as e60_vlrliq,
       	               sum(e60_vlrpag) as e60_vlrpag
   	        from empempenho
		     inner join cgm on z01_numcgm = empempenho.e60_numcgm
                     inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu 
		                          and orcdotacao.o58_coddot = empempenho.e60_coddot
                     inner join orcorgao on orcorgao.o40_orgao  = orcdotacao.o58_orgao
 		                        and orcorgao.o40_anousu = orcdotacao.o58_anousu
       	        where 
	            e60_emiss between '$data1' and '$data2'  ";
           if (isset($lista)){
                if (isset($ver) and $ver=="com"){
                     $sql = $sql ." and e60_coddot in  $w";
                } else {
                    $sql = $sql ." and e60_coddot not in  $w";
                }	 
           }  
           $sql = $sql ." group by e60_coddot, dl_estrutural, o40_orgao, o40_descr 
	                  order by e60_coddot, o40_orgao ";
           // echo $sql;
	   // exit;
	   $res=$clempempenho->sql_record($sql);
           if ($clempempenho->numrows > 0 ){
                $rows=$clempempenho->numrows; 
           } else {
               db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
           }
    } /* ---------  */      
      /* unidade sintética */
     else if ($quebra =="u") {  
       /*  sintético por orgao */
        $sql = "select e60_coddot,fc_estruturaldotacao(".db_getsession("DB_anousu").",e60_coddot) as dl_estrutural,
	               o40_orgao,o40_descr,o41_unidade,o41_descr, 
                       sum(e60_vlremp) as e60_vlremp,
                       sum(e60_vlranu) as e60_vlranu,
          	       sum(e60_vlrliq) as e60_vlrliq,
       	               sum(e60_vlrpag) as e60_vlrpag
   	        from empempenho
		     inner join cgm on z01_numcgm = empempenho.e60_numcgm
                     inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu 
		                          and orcdotacao.o58_coddot = empempenho.e60_coddot
                     inner join orcorgao on orcorgao.o40_orgao  = orcdotacao.o58_orgao
 		                        and orcorgao.o40_anousu = orcdotacao.o58_anousu
	             inner join orcunidade on orcunidade.o41_unidade = orcdotacao.o58_unidade
		                          and orcunidade.o41_anousu  = orcdotacao.o58_anousu
					  and orcunidade.o41_orgao   = orcdotacao.o58_orgao
       	        where 
	            e60_emiss between '$data1' and '$data2'  ";
           if (isset($lista)){
                if (isset($ver) and $ver=="com"){
                    $sql = $sql ." and e60_coddot in  $w";
                } else {
                    $sql = $sql ." and e60_coddot not in  $w";
                }	 
           }  
           $sql = $sql ." group by e60_coddot, dl_estrutural, o40_orgao, o40_descr, o41_unidade, o41_descr 
	                  order by e60_coddot, o40_orgao, o41_unidade ";
           // echo $sql;
	   // exit;
	   $res=$clempempenho->sql_record($sql);
           if ($clempempenho->numrows > 0 ){
                $rows=$clempempenho->numrows; 
           } else {
               db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
           }
    } /* ---------  */      
};  
//--
if ($quebra =="g"){
    $inf =" Geral  "; 
} else if ($quebra =="o"){
    $inf =" Orgão  ";
} else if ($quebra =="u"){
    $inf =" Unidade "; 
}  
if ($tipo == "a"){
    $inf = $inf . " - Analitico";
} else {
    $inf = $inf . " - Sintético ";
}
//--

//////////////////////////////////////////////////////////////////////
$head4 = "Empenho por Dotações";
$head5 = db_formatar($data1,'d')." à ".db_formatar($data2,'d');
$head6 = "Tipo :".$inf;
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$tam = '04';
$imprime_header=true;
$contador=0;
$repete = "";
$t_emp=0;
$t_liq=0;
$t_anu=0;
$t_pag=0;
$t_total=0;
$g_emp=0;
$g_liq=0;
$g_anu=0;
$g_pag=0;
$g_total=0;
$tg_emp=0;
$tg_liq=0;
$tg_anu=0;
$tg_pag=0;
$tg_total=0;

/*  geral analitico */
if ($quebra=="g" and $tipo=="a"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa novapagina 
            if ($pdf->gety() > $pdf->h - 40){
 	       $pdf->addpage("L"); 
	    }
            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
	         $pdf->Cell(20,$tam,strtoupper($RLe60_numemp),1,0,"C",1);
	         $pdf->Cell(20,$tam,strtoupper($RLe60_codemp),1,0,"C",1);	 
                 $pdf->Cell(20,$tam,strtoupper($RLe60_emiss),1,0,"C",1);
                 $pdf->Cell(80,$tam,strtoupper($RLo15_codigo),1,0,"C",1); // recurso
                 $pdf->Cell(100,$tam,"",1,1,"L",1); // cod+estrut dotatao // quebra linha
		 // 
		 $pdf->Cell(20,$tam,strtoupper($RLe60_numcgm),1,0,"C",1);
                 $pdf->Cell(120,$tam,strtoupper($RLDBtxt_credor),1,0,"C",1);
		 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(20,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $e60_coddot) {
	        /*  */
		if ($x > 1 ){
		    $pdf->setX(130);
	            $pdf->SetFont('Arial','B',7);	 
                    $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
	            $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	            $pdf->SetFont('Arial','',7);	    
		    $t_emp=0;
                    $t_liq=0;
		    $t_anu=0;
		    $t_pag=0;
		    $t_total=0;
                }
	        /*  */
	        $repete = $e60_coddot;
	        $pdf->Ln();
		$pdf->SetFont('Arial','B',9);	
                $pdf->Cell(20,$tam,"$e60_coddot",0,0,"R",0);
                $pdf->Cell(100,$tam,"$dl_estrutural",0,1,"L",0);  //<br>
      	        //$pdf->Cell(100,$tam,"$o56_descr",0,1,"L",0);
  		$pdf->SetFont('Arial','',7);	
	    }
	    $pdf->Cell(20,$tam,"$e60_numemp",0,0,"R",0);
	    $pdf->Cell(20,$tam,"$e60_codemp",0,0,"R",0);
            $pdf->Cell(20,$tam,"$e60_emiss",0,0,"C",0);
            $pdf->Cell(80,$tam,db_formatar($o15_codigo,'recurso')." - $o15_descr",0,0,"L",0); // recurso
	    $pdf->Cell(100,$tam,"",0,1,"L",0); //quebra linha
	    
	    // $pdf->setX(150);
	    $pdf->Cell(20,$tam, "$e60_numcgm",'B',0,"R",0);
	    $pdf->Cell(120,$tam,"$z01_nome  ",'B',0,"L",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlremp,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlranu,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrliq,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrpag,'f'),'B',0,"R",0);
	    $total = $e60_vlremp - $e60_vlranu - $e60_vlrpag;
            $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0);   //quebra linha
	    $pdf->Ln(1);
            /* somatorio  */
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
	    $g_emp  += $e60_vlremp;
            $g_liq  += $e60_vlrliq;
            $g_anu  += $e60_vlranu;
            $g_pag  += $e60_vlrpag;
            $g_total+= $total;
	    /*  */
            if ($x == ($rows -1)) {
		 $pdf->setX(130);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 $pdf->Ln();
                 $pdf->Ln();
		 $pdf->Cell(120,$tam,"",0,0,"L",1);
		 $pdf->Cell(20,$tam,"TOTAL GERAL",0,0,"L",1);
		 $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),0,0,"R",1);  //totais globais
                 $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_total,'f'),0,1,"R",1);   //quebra linha
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
 	       $pdg->addpage("L"); 
	    }

            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
		 $pdf->Cell(20,$tam,strtoupper($RLe60_coddot),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLDBtxt_estrutural),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(20,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$e60_coddot    ,0,0,"R",0);
 	    $pdf->Cell(100,$tam,$dl_estrutural,0,0,"L",0);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",0);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);   //quebra linha
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
	
            if ($x == ($rows -1)) {
		 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
           }
	   /* */
      }  
}/* fim geral sintetico */

/* inicio orgao analitico */
$orgao="";
if ($quebra=="o" and $tipo=="a"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
	    // testa nova pagina
            if ($pdf->gety() > $pdf->h - 40){
 	        $pdg->addpage("L"); 
	    }
	    
            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
	         $pdf->Cell(20,$tam,strtoupper($RLe60_numemp),1,0,"C",1);
	         $pdf->Cell(20,$tam,strtoupper($RLe60_codemp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_emiss),1,0,"C",1);
                 $pdf->Cell(80,$tam,strtoupper($RLo15_codigo),1,0,"C",1);  //
                 $pdf->Cell(100,$tam,"",1,1,"C",1); //quebra linha
		 
		 $pdf->Cell(20,$tam,strtoupper($RLe60_numcgm)   ,1,0,"C",1);
 	         $pdf->Cell(120,$tam,strtoupper($RLDBtxt_credor),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(20,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
		 $pdf->Ln();
            }
	    if ($repete != $e60_coddot) {
	        $orgao = "";
		if ($x > 1 ){
                   //// imprime total do orgao acima
		     //   $orgao=$o40_orgao;
 			$pdf->setX(130);
 		        $pdf->SetFont('Arial','B',7);	
                	$pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 	$pdf->Ln();
	                $t_emp   = "";
                        $t_liq   = "";
                        $t_anu   = "";
                        $t_pag   = "";
                        $t_total = "";
 		  ///////////
		    /*
		    $pdf->setX(120);  // escreve totais do credor 
	            $pdf->SetFont('Arial','B',7);	 
                    $pdf->Cell(30,$tam,"TOTAL POR HISTORICO",0,0,"L",1);
	            $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),0,0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($g_total,'f'),0,1,"R",1);   //quebra linha
	            $pdf->SetFont('Arial','',7);	    
		    */
		    $tg_emp += $g_emp;
                    $tg_liq += $g_liq;
		    $tg_anu += $g_anu;
		    $tg_pag += $g_pag;
		    $tg_total += $g_total; //
                    $g_emp=0;
                    $g_liq=0;
		    $g_anu=0;
		    $g_pag=0;
		    $g_total=0;
		}
	        $repete = $e60_coddot;
	        $pdf->Ln();
		$pdf->SetFont('Arial','B',9);	
                $pdf->Cell(20,$tam,$e60_coddot,0,0,"R",0);
                $pdf->Cell(100,$tam,$dl_estrutural,0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
	    }
	    if ($orgao != $o40_orgao) {
                if (($x >1) and $orgao!="") {
 			$pdf->setX(130);
 		        $pdf->SetFont('Arial','B',7);	
                	$pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 	$pdf->Ln();
	                $t_emp   = "";
                        $t_liq   = "";
                        $t_anu   = "";
                        $t_pag   = "";
                        $t_total = "";
                }	      		
	        $orgao = $o40_orgao;
	        // $pdf->Ln();
		$pdf->SetFont('Arial','B',7);	
                $pdf->Cell(20,$tam,db_formatar($o40_orgao,'orgao'),0,0,"R",0);
                $pdf->Cell(100,$tam,"$o40_descr",0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
	    } 
             
	    $pdf->Cell(20,$tam,"$e60_numemp",0,0,"R",0);
	    $pdf->Cell(20,$tam,"$e60_codemp",0,0,"R",0);
            $pdf->Cell(20,$tam,"$e60_emiss",0,0,"C",0);
            $pdf->Cell(80,$tam,db_formatar($o15_codigo,'recurso')." - $o15_descr",0,0,"L",0);
            $pdf->Cell(100,$tam," ",0,1,"L",0);  //quebra linha
	    // $pdf->setX(150);
	    // $pdf->Cell(140,$tam,"",'B',0,"R",0);
            $pdf->Cell(20,$tam, "$e60_numcgm",'B',0,"R",0);
	    $pdf->Cell(120,$tam,"$z01_nome  ",'B',0,"L",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlremp,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlranu,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrliq,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrpag,'f'),'B',0,"R",0);
  	    $total = $e60_vlremp - $e60_vlranu - $e60_vlrpag;
            $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0);   //quebra linha
	    $pdf->Ln(1);
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
	    $g_emp  += $e60_vlremp;
            $g_liq  += $e60_vlrliq;
            $g_anu  += $e60_vlranu;
            $g_pag  += $e60_vlrpag;
            $g_total+= $total;
            if ($x == ($rows -1)) {
	         
		 $pdf->setX(130);
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 $pdf->Ln();
                 
		 // imprime total do credor
		 /*
		 $pdf->setX(120);
		 $pdf->Cell(30,$tam,"TOTAL POR HISTORICO",0,0,"L",1);
		 $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),0,0,"R",1);  //totais globais
                 $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
                 */
		    $tg_emp += $g_emp;
                    $tg_liq += $g_liq;
		    $tg_anu += $g_anu;
		    $tg_pag += $g_pag;
		    $tg_total += $g_total; //
        
		
     	         $pdf->Ln(); // imprime total do geral
		 $pdf->Ln(); 
		 $pdf->setX(120);
		 $pdf->Cell(30,$tam,"TOTAL GERAL",0,0,"L",1);
		 $pdf->Cell(20,$tam,db_formatar($tg_emp,'f'),0,0,"R",1);  //totais globais
                 $pdf->Cell(20,$tam,db_formatar($tg_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($tg_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($tg_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($tg_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	

           }
     }  
} /* fim orgao analitico */ 
if ($quebra=="o" and $tipo=="s"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa nova pagina
            if ($pdf->gety() > $pdf->h - 40){
 	        $pdg->addpage("L"); 
	    }
	
	    
            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
  	         $pdf->Cell(20,$tam,strtoupper($RLo40_orgao ),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLo40_descr),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(20,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $e60_coddot){
                 // imprime o total do orgao acima
		  if ($x > 1 ){
 		       $pdf->Ln(2);
		       $pdf->setX(110);
                       /* imprime totais -*/
 	               $pdf->SetFont('Arial','B',7);	
                       $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                       $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	 	       $pdf->SetFont('Arial','',7);	
		       $t_emp  = 0;
                       $t_liq  = 0;
                       $t_anu  = 0;
                       $t_pag  = 0;
                       $t_total= 0;
		 }  
		 //
	         $repete = $e60_coddot;
	         $pdf->Ln();
                 $pdf->SetFont('Arial','B',8);	 
                 $pdf->Cell(20,$tam,$e60_coddot    ,0,0,"R",0);
 	         $pdf->Cell(100,$tam,$dl_estrutural,0,1,"L",0); //quebra linha
                 $pdf->SetFont('Arial','',7);	 
	    }
       
            $pdf->Cell(20,$tam,$o40_orgao,0,0,"R",0);
            $pdf->Cell(100,$tam,$o40_descr,0,0,"L",0);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",0);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);   //quebra linha
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
            $g_emp  += $e60_vlremp;
            $g_liq  += $e60_vlrliq;
            $g_anu  += $e60_vlranu;
            $g_pag  += $e60_vlrpag;
            $g_total+= $total;
            if ($x == ($rows -1)) {
		 $pdf->Ln(2);
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
                     $g_emp  += $e60_vlremp;
                     $g_liq  += $e60_vlrliq;
                     $g_anu  += $e60_vlranu;
                     $g_pag  += $e60_vlrpag;
                     $g_total+= $total;
                 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais 
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL GERAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
		 */
           }
	   /* */
      }  
}/* fim orgao sintetico */
/* unidade analitica  */
if ($quebra=="u" and $tipo=="a"){
       $pdf->SetFont('Arial','',7);
       $orgao="";
       $unidade="";
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
	    // testa nova pagina
            if ($pdf->gety() > $pdf->h - 40){
 	        $pdg->addpage("L"); 
	    }
	    if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
	         $pdf->Cell(20,$tam,strtoupper($RLe60_numemp),1,0,"C",1);
	         $pdf->Cell(20,$tam,strtoupper($RLe60_codemp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_emiss),1,0,"C",1);
                 $pdf->Cell(80,$tam,strtoupper($RLo15_codigo),1,0,"C",1);  //
                 $pdf->Cell(100,$tam,"",1,1,"C",1); //quebra linha
		 
		 $pdf->Cell(20,$tam,strtoupper($RLe60_numcgm)   ,1,0,"C",1);
 	         $pdf->Cell(120,$tam,strtoupper($RLDBtxt_credor),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(20,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
		 $pdf->Ln();
            }
	    if ($repete != $e60_coddot) {
	        $orgao = "";
		$unidade ="";
		if ($x > 1 ){
                   //// imprime total do orgao acima
		     //   $orgao=$o40_orgao;
 			$pdf->setX(110);
 		        $pdf->SetFont('Arial','B',7);	
                	$pdf->Cell(40,$tam,"TOTAL POR ORGAO",0,0,"L",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 	$pdf->Ln();
	                $t_emp   = "";
                        $t_liq   = "";
                        $t_anu   = "";
                        $t_pag   = "";
                        $t_total = "";
 		  ///////////
		}
	        $repete = $e60_coddot;
	        $pdf->Ln();
		$pdf->SetFont('Arial','B',9);	
                $pdf->Cell(20,$tam,$e60_coddot,0,0,"R",0);
                $pdf->Cell(100,$tam,$dl_estrutural,0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
	    }
	    if ($orgao != $o40_orgao) {
	        $unidade ="";
                if (($x >1) and $orgao!="") {
 		
		$pdf->setX(110);
 		        $pdf->SetFont('Arial','B',7);	
		      	$pdf->Cell(40,$tam,"TOTAL POR ORGAO",0,0,"L",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 	$pdf->Ln();
                            $g_emp  += $t_emp;
                            $g_liq  += $t_liq;
	   	            $g_anu  += $t_anu;
		            $g_pag  += $t_pag;
		            $g_total+= $t_total;
	                $t_emp   = "";
                        $t_liq   = "";
                        $t_anu   = "";
                        $t_pag   = "";
                        $t_total = "";
                }	      		
	        $orgao = $o40_orgao;
	        // $pdf->Ln();
		$pdf->SetFont('Arial','B',7);	
                $pdf->Cell(20,$tam,db_formatar($o40_orgao,'orgao'),0,0,"R",0);
                $pdf->Cell(100,$tam,"$o40_descr",0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
	    } 
       	    if ($unidade != $o41_unidade) {
                if (($x >1) and $unidade!="") {
 		  /*	$pdf->setX(110);
 		        $pdf->SetFont('Arial','B',7);	
                	$pdf->Cell(40,$tam,"TOTAL POR UNIDADE",0,0,"L",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 	$pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 	$pdf->Ln();
	                $t_emp   = "";
                        $t_liq   = "";
                        $t_anu   = "";
                        $t_pag   = "";
                        $t_total = "";
	            */		
                }	      		
	        $unidade = $o41_unidade;
		$pdf->SetFont('Arial','B',7);	
                $pdf->Cell(20,$tam,db_formatar("$orgao",'orgao').db_formatar($o41_unidade,'unidade'),0,0,"R",0);
                $pdf->Cell(100,$tam,"$o41_descr",0,1,"L",0);  //quebra inha
  		$pdf->SetFont('Arial','',7);	
	    } 
            /* dados */	    
	    $pdf->Cell(20,$tam,"$e60_numemp",0,0,"R",0);
	    $pdf->Cell(20,$tam,"$e60_codemp",0,0,"R",0);
            $pdf->Cell(20,$tam,"$e60_emiss",0,0,"C",0);
            $pdf->Cell(80,$tam,db_formatar($o15_codigo,'recurso')." - $o15_descr",0,0,"L",0);
            $pdf->Cell(100,$tam,"",0,1,"L",0);  //quebra linha
	    // $pdf->setX(150);
	    // $pdf->Cell(140,$tam,"",'B',0,"R",0);
            $pdf->Cell(20,$tam, "$e60_numcgm",'B',0,"R",0);
	    $pdf->Cell(120,$tam,"$z01_nome  ",'B',0,"L",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlremp,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlranu,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrliq,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrpag,'f'),'B',0,"R",0);
  	    $total = $e60_vlremp - $e60_vlranu - $e60_vlrpag;
            $pdf->Cell(20,$tam,db_formatar($total,'f'),'B',1,"R",0);   //quebra linha
	    $pdf->Ln(1);
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
	    $g_emp  += $e60_vlremp;
            $g_liq  += $e60_vlrliq;
            $g_anu  += $e60_vlranu;
            $g_pag  += $e60_vlrpag;
            $g_total+= $total;
            if ($x == ($rows -1)) {
	         
		 $pdf->setX(110);
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(40,$tam,"TOTAL POR ORGAO",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
                 $pdf->Ln();
                 
		
     	         $pdf->Ln(); // imprime total do geral
		 $pdf->Ln(); 
		 $pdf->setX(120);
		 $pdf->Cell(30,$tam,"TOTAL GERAL",0,0,"L",1);
		 $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),0,0,"R",1);  //totais globais
                 $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	

           }
     }  
} /* unidade analitica */
/* inicio unidade sintética */
if ($quebra=="u" and $tipo=="s"){
       $pdf->SetFont('Arial','',7);
       $orgao ="";
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa nova pagina
            if ($pdf->gety() > $pdf->h - 40){
 	        $pdg->addpage("L"); 
	    }
            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
  	         $pdf->Cell(20,$tam,strtoupper($RLo41_unidade),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLo41_descr ),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(20,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $e60_coddot){
                 // imprime o total do orgao acima
		 $orgao="";
		  if ($x > 1 ){
 		       $pdf->Ln(2);
		       $pdf->setX(110);
                       /* imprime totais -*/
 	               $pdf->SetFont('Arial','B',7);	
                       $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                       $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	 	       $pdf->SetFont('Arial','',7);	
		       $t_emp  = 0;
                       $t_liq  = 0;
                       $t_anu  = 0;
                       $t_pag  = 0;
                       $t_total= 0;
		 }  
		 //
	         $repete = $e60_coddot;
	         $pdf->Ln();
                 $pdf->SetFont('Arial','B',8);	 
                 $pdf->Cell(20,$tam,$e60_coddot    ,0,0,"R",0);
 	         $pdf->Cell(100,$tam,$dl_estrutural,0,1,"L",0); //quebra linha
                 $pdf->SetFont('Arial','',7);	 
	    }
            if ($orgao != $o40_orgao){
                 // imprime o total do orgao acima
		  if (($x > 1 ) and $orgao!=""){
 		       $pdf->Ln(2);
		       $pdf->setX(110);
                       /* imprime totais -*/
 	               $pdf->SetFont('Arial','B',7);	
                       $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                       $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                       $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	 	       $pdf->SetFont('Arial','',7);	
		       $t_emp  = 0;
                       $t_liq  = 0;
                       $t_anu  = 0;
                       $t_pag  = 0;
                       $t_total= 0;
		 }  
		 //
	         $orgao  = $o40_orgao;
	         //$pdf->Ln();
                 $pdf->SetFont('Arial','B',8);	 
                 $pdf->Cell(20,$tam,db_formatar($o40_orgao,'orgao'),0,0,"R",0);
 	         $pdf->Cell(100,$tam,$o40_descr ,0,1,"L",0); //quebra linha
                 $pdf->SetFont('Arial','',7);	 
	    }
            /* dados */
            $pdf->Cell(20, $tam,db_formatar($orgao,'orgao').db_formatar($o41_unidade,'unidade'),0,0,"R",0);
            $pdf->Cell(100,$tam,$o41_descr  ,0,0,"L",0);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",0);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",0);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(20,$tam,db_formatar($total,'f'),0,1,"R",0);   //quebra linha
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
            $g_emp  += $e60_vlremp;
            $g_liq  += $e60_vlrliq;
            $g_anu  += $e60_vlranu;
            $g_pag  += $e60_vlrpag;
            $g_total+= $total;
            if ($x == ($rows -1)) {
		 $pdf->Ln(2);
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
                     $g_emp  += $e60_vlremp;
                     $g_liq  += $e60_vlrliq;
                     $g_anu  += $e60_vlranu;
                     $g_pag  += $e60_vlrpag;
                     $g_total+= $total;
                 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais 
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL GERAL ",0,0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),0,0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_total,'f'),0,1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
		 */
           }
	   /* */
      }  
}  /* fim unidade sintética */


//include("fpdf151/geraarquivo.php");
$pdf->output();

?>