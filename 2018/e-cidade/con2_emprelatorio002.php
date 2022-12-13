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
include("classes/db_emphist_classe.php");
include("classes/db_orcelemento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clorcelemento = new cl_orcelemento;
$clemphist = new cl_emphist;
$clempempenho = new cl_empempenho;
$clcgm = new cl_cgm;
$clorctiporec = new cl_orctiporec;
$clorcdotacao = new cl_orcdotacao;
$clorcorgao = new cl_orcorgao;
$clempemphist= new cl_empemphist;

$clorcelemento->rotulo->label();
$clemphist->rotulo->label();
$clempemphist->rotulo->label();
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();

///////////////////////////////////////////////////////////////////////
    $campos ="e60_numemp,e60_codemp,e60_emiss,e60_numcgm,z01_nome,z01_cgccpf,z01_munic,e60_vlremp,e60_vlranu,e60_vlrliq,e63_codhist,e40_descr,";
    $campos = $campos."e60_vlrpag,e60_anousu,e60_coddot,o58_coddot,o58_orgao,o40_orgao,o40_descr,o58_unidade,o41_descr,o15_codigo,o15_descr";
    $campos = $campos.",fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural";
    
//---------
// monta sql
   $txt_where="1 = 1";
   if ($listacredor!=""){
       if (isset($vercredor) and $vercredor=="com"){
           $txt_where= $txt_where." and e60_numcgm in  ($listacredor)";
       } else {
           $txt_where= $txt_where." and e60_numcgm not in  ($listacredor)";
       }	 
   }  
   if ($listarec!=""){
       if (isset($verrec) and $verrec=="com"){
           $txt_where= $txt_where." and o15_codigo in  ($listarec)";
       } else {
           $txt_where= $txt_where." and o15_codigo not in  ($listarec)";
       }	 
   }  
   if ($listadot!=""){
       if (isset($verdot) and $verdot=="com"){
           $txt_where= $txt_where." and e60_coddot in  ($listadot)";
       } else {
           $txt_where= $txt_where." and e60_coddot not in  ($listadot)";
       }	 
   }
   
  if ($listahist!=""){
       if (isset($verhist) and $verhist=="com"){
           $txt_where= $txt_where." and conlancamdoc.c71_coddoc in  ($listahist)";
       } else {
           $txt_where= $txt_where." and conlancamdoc.c71_coddoc not in  ($listahist)";
       }	 
  }  
   
  if (($datacredor!="--")&&($datacredor1!="--")) {
        $txt_where = $txt_where." and conlancamdoc.c71_data  between '$datacredor' and '$datacredor1'  ";
        $datacredor=db_formatar($datacredor,"d");
        $datacredor1=db_formatar($datacredor1,"d");
	$info="De $datacredor até $datacredor1.";
  } else if ($datacredor!="--"){
	  $txt_where = $txt_where." and conlancamdoc.c71_data >= '$datacredor'  ";
          $datacredor=db_formatar($datacredor,"d");
	  $info="Apartir de $datacredor.";
  } else if ($datacredor1!="--"){
         $txt_where = $txt_where."  conlancamdoc.c71_data <= '$datacredor1'   ";  
         $datacredor1=db_formatar($datacredor1,"d");
         $info="Até $datacredor1.";
  }

 /////////////////////////////////////////////  
if ($tipo=="a"){
      $ordem = "e60_numcgm, e60_emiss ";
     
     // die($clempempenho->sql_query(null,$campos,$ordem,$txt_where));
     $res=$clempempenho->sql_record($clempempenho->sql_query_doc(null,$campos,$ordem,$txt_where));
     // db_criatabela($res);
     // exit;
     if ($clempempenho->numrows > 0 ){
         $rows=$clempempenho->numrows; 
     } else {
         db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
     }

}else  {
        $sql = "select e60_numcgm,z01_nome,
                       sum(e60_vlremp) as e60_vlremp,
                       sum(e60_vlranu) as e60_vlranu,
          	       sum(e60_vlrliq) as e60_vlrliq,
       	               sum(e60_vlrpag) as e60_vlrpag
   	        from empempenho 
		       inner join conlancamemp on c75_numemp = e60_numemp
		       inner join conlancamdoc on c71_codlan = conlancamemp.c75_codlan
		       inner join conhistdoc on c53_coddoc = conlancamdoc.c71_coddoc
        	       inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm
	  	       inner join db_config  on  db_config.codigo = empempenho.e60_instit
		       inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot  = empempenho.e60_coddot
                       inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo
                       inner join db_config  as a on   a.codigo = orcdotacao.o58_instit
                       inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo
                       inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao
                       inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
                       inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa
                       inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu
                       inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ
                       inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao
                       inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade
                       left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp 
                       left join emphist on emphist.e40_codhist = empemphist.e63_codhist
		where     
                      $txt_where ";                                          
         $sql = $sql ." group by e60_numcgm,z01_nome  order by e60_numcgm ";
	 $res=$clempempenho->sql_record($sql);
         if ($clempempenho->numrows > 0 ){
             $rows=$clempempenho->numrows; 
         } else {
	   
              db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
         }
}

//////////////////////////////////////////////////////////////////////
$head4 = "Relatório de Empenhos";
$head5= "$info";
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
$t_emp1=0;
$t_liq1=0;
$t_anu1=0;
$t_pag1=0;
$t_total1=0;
$t_emp2=0;
$t_liq2=0;
$t_anu2=0;
$t_pag2=0;
$t_total2=0;
$t_emp3=0;
$t_liq3=0;
$t_anu3=0;
$t_pag3=0;
$t_total3=0;
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
$p=0;
/*  geral analitico */
if ($tipo=="a"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa novapagina 
            if ($pdf->gety() > $pdf->h - 40){
 	       $pdf->addpage("L"); 
	       $imprime_header=true;
	    }
            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
                 $pdf->Cell(40,$tam,strtoupper($RLe60_numcgm),1,0,"C",1);
                 $pdf->Cell(135,$tam,strtoupper($RLz01_nome),1,0,"C",1);  
                 $pdf->Cell(50,$tam,strtoupper($RLz01_cgccpf),1,0,"C",1);  
                 $pdf->Cell(50,$tam,strtoupper($RLz01_munic),1,1,"C",1);  
                 
	         $pdf->Cell(15,$tam,"N°",1,0,"C",1);
	         $pdf->Cell(15,$tam,"EMP.",1,0,"C",1);	 
                 $pdf->Cell(20,$tam,strtoupper($RLe60_emiss),1,0,"C",1);
                 $pdf->Cell(50,$tam,strtoupper($RLo15_codigo),1,0,"C",1); // recurso
	         
                 $pdf->Cell(70,$tam,strtoupper($RLe60_coddot),1,0,"L",1); // cod+estrut dotatao // quebra linha
		 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(25,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $e60_numcgm) {
	        /*  */
		if ($x > 1 ){
		    $pdf->setX(160);
	            $pdf->SetFont('Arial','B',7);	 
                    $pdf->Cell(20,$tam,"TOTAL ","B",0,"L",1);
	            $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),"B",0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),"B",0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),"B",0,"R",1);
                    $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),"B",0,"R",1);
                    $pdf->Cell(25,$tam,db_formatar($t_total,'f'),"B",1,"R",1);   //quebra linha
	            $pdf->SetFont('Arial','',7);	    
		    $t_emp=0;
                    $t_liq=0;
		    $t_anu=0;
		    $t_pag=0;
		    $t_total=0;
                }
	        /*  */
	        $repete = $e60_numcgm;
	        $pdf->Ln();
		$pdf->SetFont('Arial','B',8);	
                $pdf->Cell(40,$tam,"$e60_numcgm",0,0,"C",0);
                $pdf->Cell(135,$tam,"$z01_nome",0,0,"L",0);  //quebra inha
                $pdf->Cell(50,$tam,$z01_cgccpf,0,0,"C",0);  
                $pdf->Cell(50,$tam,$z01_munic,0,1,"L",0);  
  		$pdf->SetFont('Arial','',7);	
	   
	 
	    }
	    $pdf->Cell(15,$tam,"$e60_numemp",0,0,"R",0);
	    $pdf->Cell(15,$tam,"$e60_codemp",0,0,"R",0);
            $pdf->Cell(20,$tam,"$e60_emiss",0,0,"C",0);
            $pdf->Cell(50,$tam,db_formatar($o15_codigo,'recurso')." - $o15_descr",0,0,"L",0); // recurso
	    $pdf->Cell(70,$tam,"$e60_coddot -  $dl_estrutural",0,0,"L",0); //quebra linha
            $pdf->Cell(20,$tam,db_formatar($e60_vlremp,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlranu,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrliq,'f'),'B',0,"R",0);
            $pdf->Cell(20,$tam,db_formatar($e60_vlrpag,'f'),'B',0,"R",0);
	    $total = $e60_vlremp - $e60_vlranu - $e60_vlrpag;
            $pdf->Cell(25,$tam,db_formatar($total,'f'),'B',1,"R",0);   //quebra linha


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
		 $pdf->setX(160);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ","B",0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),"B",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),"B",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),"B",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),"B",0,"R",1);
                 $pdf->Cell(25,$tam,db_formatar($t_total,'f'),"B",1,"R",1);   //quebra linha
                 $pdf->Ln();
                 $pdf->Ln();
		 $pdf->Cell(150,$tam,"","T",0,"L",1);
		 $pdf->Cell(20,$tam,"TOTAL GERAL","T",0,"L",1);
		 $pdf->Cell(20,$tam,db_formatar($g_emp,'f'),"T",0,"R",1);  //totais globais
                 $pdf->Cell(20,$tam,db_formatar($g_anu,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_liq,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($g_pag,'f'),"T",0,"R",1);
                 $pdf->Cell(25,$tam,db_formatar($g_total,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
           }
	   /* */
     }  
} /* end quebra ="g" */

/* geral sintetico */
if ($tipo=="s"){
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($res,$x,true);
            // testa nova pagina
	    if ($pdf->gety() > $pdf->h - 30){
 	       $pdf->addpage("L"); 
	       $imprime_header=true;
	    }

            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',7);	 
		 $pdf->Cell(20,$tam,strtoupper($RLe60_numcgm),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLz01_nome  ),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(30,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$e60_numcgm,0,0,"R",$p);
 	    $pdf->Cell(100,$tam,$z01_nome ,0,0,"L",$p);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",$p);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(30,$tam,db_formatar($total,'f'),0,1,"R",$p);   //quebra linha
            $t_emp  += $e60_vlremp;
            $t_liq  += $e60_vlrliq;
            $t_anu  += $e60_vlranu;
            $t_pag  += $e60_vlrpag;
            $t_total+= $total;
            if($p==0){
              $p=1;  
	    }else $p=0;
            if ($x == ($rows -1)) {
		 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ","T",0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),"T",0,"R",1);
                 $pdf->Cell(30,$tam,db_formatar($t_total,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
	   
	   
	   }
	   /* */
 

     }  
}/* fim geral sintetico */

if ($hist=="true"){

       

	$sql = "select case when e63_codhist is null then 0               else e63_codhist end as e63_codhist,
		       case when e40_descr   is null then 'SEM HISTORICO' else e40_descr   end as e40_descr,
		       e60_vlremp, e60_vlranu, e60_vlrliq, e60_vlrpag from (
		select e63_codhist,e40_descr,
                       sum(e60_vlremp) as e60_vlremp,
                       sum(e60_vlranu) as e60_vlranu,
          	       sum(e60_vlrliq) as e60_vlrliq,
       	               sum(e60_vlrpag) as e60_vlrpag
   	        from empempenho 
                       left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp 
                       left join emphist on emphist.e40_codhist = empemphist.e63_codhist
		where $txt_where
                      ";                                   
         $sql = $sql ." group by e63_codhist,e40_descr  order by e63_codhist) as x";
	 $result=$clempempenho->sql_record($sql);
         if ($clempempenho->numrows > 0 ){
           $pdf->addpage("L");
           $imprime_header=true;
           $rows=$clempempenho->numrows; 
	   
           $pdf->SetFont('Arial','',7);
           for ($x=0; $x < $rows;$x++){
             db_fieldsmemory($result,$x,true);
             // testa nova pagina
	     if ($pdf->gety() > $pdf->h - 30){
 	       $pdf->addpage("L"); 
	       $imprime_header=true;
	     }

             if ($imprime_header==true)
    	     {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',8);	 
		 $pdf->cell(230,$tam,"TOTALIZAÇÃO DOS HISTÓRICOS",1,1,"C",1);
	         $pdf->SetFont('Arial','B',7);	 
		 $pdf->Cell(20,$tam,strtoupper($RLe63_codhist),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLe40_descr  ),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(30,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$e63_codhist,0,0,"R",$p);
 	    $pdf->Cell(100,$tam,$e40_descr ,0,0,"L",$p);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",$p);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(30,$tam,db_formatar($total,'f'),0,1,"R",$p);   //quebra linha
            $t_emp1  += $e60_vlremp;
            $t_liq1  += $e60_vlrliq;
            $t_anu1  += $e60_vlranu;
            $t_pag1  += $e60_vlrpag;
            $t_total1+= $total;
            if($p==0){
              $p=1;  
	    }else $p=0;
            if ($x == ($rows -1)) {
		 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ","T",0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp1,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu1,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq1,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag1,'f'),"T",0,"R",1);
                 $pdf->Cell(30,$tam,db_formatar($t_total1,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
	   
	   
	   }
	   /* */
 

     }  

  }else{

  }
 
}
if ($dot=="true"){

     
  
     $sql="select e60_coddot,fc_estruturaldotacao(".db_getsession("DB_anousu").",e60_coddot) as dl_estrutural,o56_descr,
               sum(e60_vlremp) as e60_vlremp,
               sum(e60_vlranu) as e60_vlranu,
	       sum(e60_vlrliq) as e60_vlrliq,
	       sum(e60_vlrpag) as e60_vlrpag
        from empempenho
               inner join cgm on z01_numcgm = empempenho.e60_numcgm
               inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu
                                     and  orcdotacao.o58_coddot = empempenho.e60_coddot
	       inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and 
	                                   orcelemento.o56_anousu = orcdotacao.o58_anousu 
        where
	        $txt_where";                                   
         $sql = $sql ."group by e60_coddot, dl_estrutural,o56_descr  order by e60_coddot";
	 
	 $result1=$clempempenho->sql_record($sql);
         if ($clempempenho->numrows > 0 ){
            $pdf->addpage("L");
            $imprime_header=true;
            $rows=$clempempenho->numrows; 
	   
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($result1,$x,true);
            // testa nova pagina
	    if ($pdf->gety() > $pdf->h - 30){
 	       $pdf->addpage("L"); 
	       $imprime_header=true;
	    }

            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',8);	 
	  	 $pdf->cell(230,$tam,"TOTALIZAÇÃO DAS DOTAÇÕES",1,1,"C",1);
	         $pdf->SetFont('Arial','B',7);	 
 		 $pdf->Cell(20,$tam,strtoupper($RLe60_coddot),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLo56_descr  ),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(30,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$e60_coddot,0,0,"R",$p);
 	    $pdf->Cell(100,$tam,$o56_descr ,0,0,"L",$p);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",$p);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(30,$tam,db_formatar($total,'f'),0,1,"R",$p);   //quebra linha
            $t_emp2  += $e60_vlremp;
            $t_liq2  += $e60_vlrliq;
            $t_anu2  += $e60_vlranu;
            $t_pag2  += $e60_vlrpag;
            $t_total2+= $total;
            if($p==0){
              $p=1;  
	    }else $p=0;
            if ($x == ($rows -1)) {
		 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ","T",0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp2,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu2,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq2,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag2,'f'),"T",0,"R",1);
                 $pdf->Cell(30,$tam,db_formatar($t_total2,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
	   
	   
	   }
	   /* */
 

     }  

  }else{
         
	 
  }
 
}

if ($rec=="true"){
     
     $sql="select o15_codigo, o15_descr,
                  sum(e60_vlremp) as e60_vlremp,
	          sum(e60_vlranu) as e60_vlranu,
	          sum(e60_vlrliq) as e60_vlrliq,
	          sum(e60_vlrpag) as e60_vlrpag
	   from empempenho
	          inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu
	                               and orcdotacao.o58_coddot = empempenho.e60_coddot
	          inner join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo
		  where $txt_where
	        	";                                   
         $sql = $sql ."group by o15_codigo,o15_descr  order by o15_codigo";
	 
	 $result1=$clempempenho->sql_record($sql);
         if ($clempempenho->numrows > 0 ){
            $pdf->addpage("L");
            $imprime_header=true;
            $rows=$clempempenho->numrows; 
	   
       $pdf->SetFont('Arial','',7);
       for ($x=0; $x < $rows;$x++){
            db_fieldsmemory($result1,$x,true);
            // testa nova pagina
	    if ($pdf->gety() > $pdf->h - 30){
 	       $pdf->addpage("L"); 
	       $imprime_header=true;
	    }

            if ($imprime_header==true)
    	    {
                 $pdf->Ln();
	         $pdf->SetFont('Arial','B',8);	 
	  	 $pdf->cell(230,$tam,"TOTALIZAÇÃO DOS RECURSOS",1,1,"C",1);
	         $pdf->SetFont('Arial','B',7);	 
 		 $pdf->Cell(20,$tam,strtoupper($RLo15_codigo),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLo15_descr  ),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(30,$tam,"TOTAL A PAGAR",1,1,"C",1);   //quebra linha
		 $pdf->Ln();
	         $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    $pdf->Ln(1);
            $pdf->Cell(20,$tam,$o15_codigo,0,0,"R",$p);
 	    $pdf->Cell(100,$tam,$o15_descr ,0,0,"L",$p);
            $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",$p);
            $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",$p);
	    $total = $e60_vlremp - $e60_vlranu -$e60_vlrpag ;
            $pdf->Cell(30,$tam,db_formatar($total,'f'),0,1,"R",$p);   //quebra linha
            $t_emp3  += $e60_vlremp;
            $t_liq3  += $e60_vlrliq;
            $t_anu3  += $e60_vlranu;
            $t_pag3  += $e60_vlrpag;
            $t_total3+= $total;
            if($p==0){
              $p=1;  
	    }else $p=0;
            if ($x == ($rows -1)) {
		 $pdf->Ln();
		 $pdf->setX(110);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);	
                 $pdf->Cell(20,$tam,"TOTAL ","T",0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp3,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu3,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq3,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag3,'f'),"T",0,"R",1);
                 $pdf->Cell(30,$tam,db_formatar($t_total3,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
	   
	   
	   }
	   /* */
 

     }  

  }else{
         
	 
  }
 
}

$pdf->output();

?>