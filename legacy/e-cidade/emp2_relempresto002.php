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
include("classes/db_empempenho_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_empemphist_classe.php");
include("classes/db_emphist_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_empempitem_classe.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clselorcdotacao = new cl_selorcdotacao();
$clorcelemento = new cl_orcelemento;
$clemphist = new cl_emphist;
$clconlancamemp = new cl_conlancamemp;
$clconlancamdoc = new cl_conlancamdoc;
$clempempenho = new cl_empempenho;
$clcgm = new cl_cgm;
$clorctiporec = new cl_orctiporec;
$clorcdotacao = new cl_orcdotacao;
$clorcorgao = new cl_orcorgao;
$clempemphist= new cl_empemphist;
$clempempitem = new cl_empempitem;

$clorcelemento->rotulo->label();
$clemphist->rotulo->label();
$clempemphist->rotulo->label();
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();
$clrotulo = new rotulocampo;

$tipo = "a"; // sempre analitico

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits= $clselorcdotacao->getInstit();

$sele_work = $clselorcdotacao->getDados(false);

//echo $sele_work;exit;

$clrotulo->label("pc50_descr");
///////////////////////////////////////////////////////////////////////
    $campos ="e60_resumo, e60_numemp,e60_codemp,e60_emiss,e60_numcgm,z01_nome,z01_cgccpf,z01_munic,e60_vlremp,e60_vlranu,e60_vlrliq,e63_codhist,e40_descr,";
    $campos = $campos."e60_vlrpag,e60_anousu,e60_coddot,o58_coddot,o58_orgao,o40_orgao,o40_descr,o58_unidade,o41_descr,o15_codigo,o15_descr";
    $campos = $campos.",fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,e60_codcom,pc50_descr";

//---------
// monta sql
   $txt_where="e60_instit in $instits";
   if ($listacredor!=""){
       if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and e60_numcgm in  ($listacredor)";
       } else {
           $txt_where= $txt_where." and e60_numcgm not in  ($listacredor)";
       }	 
   }  
   if ($listahist!="" and 1==2){
       if (isset($verhist) and $verhist=="com"){
           $txt_where= $txt_where." and e63_codhist in  ($listahist)";
       } else {
           $txt_where= $txt_where." and e63_codhist not in  ($listahist)";
       }	 
   }  
   if ($listacom!="" and 1==2){
       if (isset($vercom) and $vercom=="com"){
           $txt_where= $txt_where." and e60_codcom in  ($listacom)";
       } else {
           $txt_where= $txt_where." and e60_codcom not in  ($listacom)";
       }	 
   }  
  if (($datacredor1!="--")) {
        $txt_where = $txt_where." and e60_emiss  between '$datacredor' and '$datacredor1'  ";
//        $datacredor=db_formatar($datacredor,"d");
//        $datacredor1=db_formatar($datacredor1,"d");
	$info="De " . db_formatar($datacredor,"d") . " até " . db_formatar($datacredor1,"d") . ".";
  } else if ($datacredor!="--"){
	  $txt_where = $txt_where." and e60_emiss >= '$datacredor'  ";
//          $datacredor=db_formatar($datacredor,"d");
	  $info="Apartir de " . db_formatar($datacredor,"d") . ".";
  } else if ($datacredor1!="--"){
         $txt_where = $txt_where."    e60_emiss <= '$datacredor1'   ";  
//         $datacredor1=db_formatar($datacredor1,"d");
         $info="Até " . db_formatar($datacredor1,"d") . ".";
  }

  $txt_where .= " and $sele_work";

 /////////////////////////////////////////////  


   if ($agrupar == "a") {
     $ordem = "e60_numcgm, e60_emiss";
   } else {
     $ordem = "e60_emiss";
   }
   
	$sqlperiodo = "
	select 	empempenho.e60_numemp,
		      e60_resumo,
		      e60_codemp,
		      e60_emiss,
		      e60_numcgm,
		      z01_nome,
		      z01_cgccpf,
		      z01_munic,
		      e91_vlremp+vlremp as e60_vlremp,
		      e91_vlranu+vlranu as e60_vlranu,
		      e91_vlrliq+vlrliq as e60_vlrliq,
		      e63_codhist,
		      e40_descr,
		      e91_vlrpag+vlrpag as e60_vlrpag,
		      e60_anousu,
		      e60_coddot,
		      o58_coddot,
		      o58_orgao,
		      o40_orgao,
		      o40_descr,
		      o58_unidade,
		      o41_descr,
		      o15_codigo,
		      o15_descr,
		      fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,
		      e60_codcom,
		      pc50_descr 
	 from (
	select 	e60_numemp, 
		      sum(case when c53_tipo = 10 then c70_valor else 0 end) as vlremp,
		      sum(case when c53_tipo = 11 then c70_valor else 0 end) as vlranu,
		      sum(case when c53_tipo = 20 then c70_valor else 0 end) - sum(case when c53_tipo = 21 then c70_valor else 0 end) as vlrliq,
		      sum(case when c53_tipo = 30 then c70_valor else 0 end) - sum(case when c53_tipo = 31 then c70_valor else 0 end) as vlrpag
	      from (

	      select 		e60_numemp,
			      c53_tipo,
			      sum(c70_valor) as c70_valor
	      from (
		    select e60_numemp
		    from empresto
		         inner join empempenho on e91_anousu = ".db_getsession("DB_anousu")." and e91_numemp = e60_numemp
		    where 	e60_instit in $instits and 
			      e60_emiss between '$datacredor' and '$datacredor1' ";
		
        if($tiporesto=='l'){
	  
	  $sqlperiodo .= " and ( round(e91_vlrliq,2)-round(e91_vlrpag,2) > 0 )";
	  
	}else if($tiporesto=='n'){
	  
	  $sqlperiodo .= " and ( round(e91_vlremp,2)-round(e91_vlranu,2)-round(e91_vlrliq,2) > 0 )";
	  
	}
	$sqlperiodo .= "
        
	            ) as xxx

			    left join conlancamemp 	on c75_numemp = xxx.e60_numemp
			    left join conlancam	on c70_codlan = c75_codlan and c70_data between '".db_getsession("DB_anousu")."-01-01' and '$dataesp2'
			    left join conlancamdoc 	on c71_codlan = c70_codlan
			    left join conhistdoc 	on c53_coddoc = c71_coddoc and c53_tipo in (10,11,20,21,30,31)

			    group by e60_numemp, c53_tipo
	      ) as xxx
      group by e60_numemp) as yyy
		      inner join empresto		on empresto.e91_anousu = ".db_getsession("DB_anousu")." and empresto.e91_numemp	= yyy.e60_numemp
		      inner join empempenho		on empempenho.e60_numemp	= yyy.e60_numemp
		      inner join cgm 			on cgm.z01_numcgm 		= empempenho.e60_numcgm 
		      inner join db_config 		on db_config.codigo 		= empempenho.e60_instit 
		      inner join orcdotacao 		on orcdotacao.o58_anousu 	= empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot 
		      inner join emptipo 		on emptipo.e41_codtipo 		= empempenho.e60_codtipo 
		      inner join db_config as a 	on a.codigo 			= orcdotacao.o58_instit 
		      inner join orctiporec 		on orctiporec.o15_codigo 	= orcdotacao.o58_codigo 
		      inner join orcfuncao 		on orcfuncao.o52_funcao 	= orcdotacao.o58_funcao 
		      inner join orcsubfuncao 	on orcsubfuncao.o53_subfuncao 	= orcdotacao.o58_subfuncao 
		      inner join orcprograma 		on orcprograma.o54_anousu 	= orcdotacao.o58_anousu and orcprograma.o54_programa = orcdotacao.o58_programa 
		      inner join orcelemento 		on orcelemento.o56_codele 	= orcdotacao.o58_codele 
		                                       and orcelemento.o56_anousu       = orcdotacao.o58_anousu
		      inner join orcprojativ 		on orcprojativ.o55_anousu 	= orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ 
		      inner join orcorgao 		on orcorgao.o40_anousu 		= orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao 
		      inner join orcunidade 		on orcunidade.o41_anousu 	= orcdotacao.o58_anousu and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade 
		      left join  empemphist 		on empemphist.e63_numemp = empempenho.e60_numemp 
		      left join  emphist 		on emphist.e40_codhist = empemphist.e63_codhist 
		      inner join pctipocompra 	on pctipocompra.pc50_codcom = empempenho.e60_codcom 
	      where $txt_where
	      order by 	e60_numcgm, 
			      e60_emiss

";
//     echo $sqlperiodo;exit;
     $res=$clempempenho->sql_record($sqlperiodo);
     $rows=$clempempenho->numrows; 


//////////////////////////////////////////////////////////////////////

$head3 = "Relatório de Empenhos";

$head5= "$info";

$head6= "Posiçao: ".db_formatar($dataesp2,"d");

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
$t_emp5=0;
$t_liq5=0;
$t_anu5=0;
$t_pag5=0;
$t_total5=0;
$t_emp6=0;
$t_liq6=0;
$t_anu6=0;
$t_pag6=0;
$t_total6=0;
$quantimp=0;
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
            if ($imprime_header==true) {
                 $pdf->Ln();
		 
		 $pdf->SetFont('Arial','B',7);	 
		 
                 if ($agrupar == "a") {
		   $pdf->Cell(45,$tam,strtoupper($RLe60_numcgm),1,0,"C",1);
		   $pdf->Cell(80,$tam,strtoupper($RLz01_nome),1,0,"C",1);  
		   $pdf->Cell(25,$tam,strtoupper($RLz01_cgccpf),1,0,"C",1);  
		   $pdf->Cell(72,$tam,"MOVIMENTAÇÃO",1,0,"C",1);  
		   $pdf->Cell(54,$tam,"SALDO A PAGAR",1,1,"C",1);  
		 }
                 
                 if ($tipo=="a"){
		   $pdf->Cell(15,$tam,"N°",1,0,"C",1);
		   $pdf->Cell(15,$tam,"EMP.",1,0,"C",1);	 
		   $pdf->Cell(15,$tam,"EMISSÃO",1,0,"C",1);

                   if ($agrupar == "a") {
		     if ($mostrar=="r"){
		       $pdf->Cell(40,$tam,strtoupper($RLo15_codigo),1,0,"C",1); // recurso
		     }else if ($mostrar=="t"){
		       $pdf->Cell(40,$tam,strtoupper('Tipo de Compra'),1,0,"C",1); // tipo de compra
		     }
		   } else {
		       $pdf->Cell(40,$tam,strtoupper($RLz01_nome),1,0,"C",1); // recurso
		   }

		   $pdf->Cell(65,$tam,strtoupper($RLe60_coddot),1,0,"L",1); // cod+estrut dotatao // quebra linha
		   $pdf->Cell(18,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
		   $pdf->Cell(18,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
		   $pdf->Cell(18,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
		   $pdf->Cell(18,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
		   $pdf->Cell(18,$tam,"LIQUIDADO",1,0,"C",1);
		   $pdf->Cell(18,$tam,"NAO LIQUID",1,0,"C",1);
		   $pdf->Cell(18,$tam,"GERAL",1,1,"C",1);   //quebra linha
		   
		   if ($mostralan == "m") {
		     $pdf->Cell(40,$tam,"",0,0,"C",0);
		     $pdf->Cell(20,$tam,"DATA",1,0,"C",1);
		     $pdf->Cell(25,$tam,"LANÇAMENTO",1,0,"C",1);
		     $pdf->Cell(25,$tam,"DOCUMENTO",1,0,"C",1);
		     $pdf->Cell(25,$tam,"VALOR",1,1,"C",1); // quebra linha1
		   }	 
		   if ($mostraritem == "m") {
		     $pdf->Cell(40,$tam,"",0,0,"C",0);
		     $pdf->Cell(20,$tam,"ITEM",1,0,"C",1);
		     $pdf->Cell(75,$tam,"DESCRIÇÃO DO ITEM",1,0,"C",1);
		     $pdf->Cell(20,$tam,"QUANTIDADE",1,0,"C",1);
		     $pdf->Cell(20,$tam,"VALOR TOTAL",1,0,"C",1);
		     $pdf->Cell(102,$tam,"COMPLEMENTO",1,1,"C",1); // quebra linha1
		   }	 
		 }
		 
		 $pdf->SetFont('Arial','',7);	
		 $imprime_header=false;
            }
	    /* ----------- */
	    if ($repete != $e60_numcgm and $agrupar == "a") {
	        /*  */
		if ($quantimp > 1){
		    $pdf->setX(125);
	            $pdf->SetFont('Arial','B',7);	 
                    $pdf->Cell(35,$tam,"TOTAL DE " . db_formatar($quantimp,"s") . " EMPENHOS","B",0,"L",1);
	            $pdf->Cell(18,$tam,db_formatar($t_emp,'f'),"B",0,"R",1);
                    $pdf->Cell(18,$tam,db_formatar($t_anu,'f'),"B",0,"R",1);
                    $pdf->Cell(18,$tam,db_formatar($t_liq,'f'),"B",0,"R",1);
                    $pdf->Cell(18,$tam,db_formatar($t_pag,'f'),"B",0,"R",1);
                    $pdf->Cell(18,$tam,db_formatar($t_liq - $t_pag,'f'),"B",0,"R",1);   //quebra linha
                    $pdf->Cell(18,$tam,db_formatar($t_emp - $t_anu - $t_liq,'f'),"B",0,"R",1);   //quebra linha
                    $pdf->Cell(18,$tam,db_formatar($t_emp - $t_anu - $t_pag,'f'),"B",1,"R",1);   //quebra linha
	            $pdf->SetFont('Arial','',7);	    
		    $t_emp=0;
                    $t_liq=0;
		    $t_anu=0;
		    $t_pag=0;
		    $t_total=0;
                }
	        /*  */
	        $repete = $e60_numcgm;
		$quantimp=0;
	        $pdf->Ln();
		$pdf->SetFont('Arial','B',8);	
		
                if ($agrupar == "a") {
		  $pdf->Cell(45,$tam,"$e60_numcgm",0,0,"C",0);
		  $pdf->Cell(80,$tam,"$z01_nome",0,0,"L",0);
		  $pdf->Cell(25,$tam,$z01_cgccpf,0,0,"C",0);  
		  $pdf->Cell(72,$tam,$z01_munic,0,1,"L",0);
		}
		
  		$pdf->SetFont('Arial','',7);	
	 
	    }
	    if ($agrupar == "a") {
	      $preenche = 1;
	    } else {
	      $preenche = 0;
	    }

            if ($tipo=="a"){
              $quantimp++;
	      $pdf->Cell(15,$tam,"$e60_numemp",0,0,"R",$preenche);
	      $pdf->Cell(15,$tam,"$e60_codemp",0,0,"R",$preenche);
	      $pdf->Cell(15,$tam,$e60_emiss,0,0,"C",$preenche);

	      if ($agrupar == "a") {
		if ($mostrar=="r"){
		  $pdf->Cell(40,$tam,db_formatar($o15_codigo,'recurso')." - $o15_descr",0,0,"L",$preenche); // recurso
		}else if ($mostrar=="t"){
		  $pdf->Cell(40,$tam,$e60_codcom." - $pc50_descr",0,0,"L",$preenche); // tipo de compra
		}
	      } else {
		  $pdf->Cell(40,$tam,substr($z01_nome,0,30),0,0,"L",$preenche);
	      }
	      $pdf->Cell(65,$tam,"$e60_coddot -  $dl_estrutural",0,0,"L",$preenche); //quebra linha
	      $pdf->Cell(18,$tam,db_formatar($e60_vlremp,'f'),'B',0,"R",$preenche);
	      $pdf->Cell(18,$tam,db_formatar($e60_vlranu,'f'),'B',0,"R",$preenche);
	      $pdf->Cell(18,$tam,db_formatar($e60_vlrliq,'f'),'B',0,"R",$preenche);
	      $pdf->Cell(18,$tam,db_formatar($e60_vlrpag,'f'),'B',0,"R",$preenche);
	      $total = $e60_vlrliq - $e60_vlrpag;
	      $pdf->Cell(18,$tam,db_formatar($e60_vlrliq - $e60_vlrpag,'f'),'B',0,"R",$preenche);   //quebra linha
	      $pdf->Cell(18,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq,'f'),'B',0,"R",$preenche);
	      $pdf->Cell(18,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag,'f'),'B',1,"R",$preenche);
	      if ($mostrarobs == "m") {
//  	        $pdf->Cell(200,$tam,$e60_resumo,0,1,"L",0);
                $pdf->multicell(270,4,$e60_resumo);
	      }

	      if ($mostralan == "m") {
		
		$reslancam=$clconlancamemp->sql_record($clconlancamemp->sql_query("","*",""," c75_numemp = $e60_numemp " . ($processar == "a"?"":" and c75_data between '$dataesp1' and '$dataesp2'")));
		  $rows_lancamemp = $clconlancamemp->numrows;
		  for ($lancemp=0; $lancemp < $rows_lancamemp; $lancemp++) {
		    db_fieldsmemory($reslancam,$lancemp,true);
		    $reslancamdoc=$clconlancamdoc->sql_record($clconlancamdoc->sql_query($c70_codlan,"*"));
		    db_fieldsmemory($reslancamdoc,0,true);

		    $preenche = ($lancemp%2==0?0:1);

		    $pdf->Cell(40,$tam,"",0,0,"R",$preenche);
		    $pdf->Cell(20,$tam,$c70_data,0,0,"C",$preenche);
		    $pdf->Cell(25,$tam,$c70_codlan,0,0,"R",$preenche);
		    $pdf->Cell(25,$tam,$c53_descr,0,0,"L",$preenche);
		    $pdf->Cell(25,$tam,db_formatar($c70_valor,'f'),0,1,"R",$preenche);
		  }
	      }

	      if ($mostraritem == "m") {
		$resitem=$clempempitem->sql_record($clempempitem->sql_query($e60_numemp,null,"*"));
		$rows_item = $clempempitem->numrows;
		for ($item=0; $item < $rows_item; $item++) {
		  db_fieldsmemory($resitem,$item,true);
		  $preenche = ($item%2==0?0:1);
		  $pdf->Cell(40,$tam,"",0,0,"R",$preenche);
		  $pdf->Cell(20,$tam,"$e62_item",0,0,"R",$preenche);
		  $pdf->Cell(75,$tam,"$pc01_descrmater",0,0,"L",$preenche);
		  $pdf->Cell(20,$tam,db_formatar($e62_quant,'f'),0,0,"R",$preenche);
		  $pdf->Cell(20,$tam,db_formatar($e62_vltot,'f'),0,0,"R",$preenche);
		  $pdf->Cell(80,$tam,substr($e62_descr,0,70),0,1,"L",$preenche);
		  $pdf->Cell(20,$tam,"",0,1,"R",$preenche);
		}
	      }
	      
	    }

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
		 $pdf->setX(135);
                 /* imprime totais -*/
 	         $pdf->SetFont('Arial','B',7);
                 $pdf->Cell(25,$tam,"TOTAL ","B",0,"L",1);
                 $pdf->Cell(18,$tam,db_formatar($t_emp,'f'),"B",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($t_anu,'f'),"B",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($t_liq,'f'),"B",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($t_pag,'f'),"B",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($t_liq - $t_pag,'f'),"B",0,"R",1);
		 $pdf->Cell(18,$tam,db_formatar($t_emp - $t_anu - $t_liq,'f'),"B",0,"R",1);   //quebra linha
		 $pdf->Cell(18,$tam,db_formatar($t_emp - $t_anu - $t_pag,'f'),"B",1,"R",1);   //quebra linha
                 $pdf->Ln();
                 $pdf->Ln();
		 $pdf->Cell(125,$tam,"TOTAL DE EMPENHOS: " . db_formatar($rows,"s"),"T",0,"L",1);
		 $pdf->Cell(25,$tam,"TOTAL GERAL","T",0,"L",1);
		 $pdf->Cell(18,$tam,db_formatar($g_emp,'f'),"T",0,"R",1);  //totais globais
                 $pdf->Cell(18,$tam,db_formatar($g_anu,'f'),"T",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($g_liq,'f'),"T",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($g_pag,'f'),"T",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($g_liq - $g_pag,'f'),"T",0,"R",1);
                 $pdf->Cell(18,$tam,db_formatar($g_emp - $g_anu - $g_liq,'f'),"T",0,"R",1);   //quebra linha
                 $pdf->Cell(18,$tam,db_formatar($g_emp - $g_anu - $g_pag,'f'),"T",1,"R",1);   //quebra linha
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
                 $pdf->Cell(25,$tam,"TOTAL ","T",0,"L",1);
                 $pdf->Cell(20,$tam,db_formatar($t_emp,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_anu,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_liq,'f'),"T",0,"R",1);
                 $pdf->Cell(20,$tam,db_formatar($t_pag,'f'),"T",0,"R",1);
                 $pdf->Cell(22,$tam,db_formatar($t_total,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
	   
	   
	   }
	   /* */
 

     }  
}/* fim geral sintetico */

if ($hist=="h"){

	$sql = "select case when x.e63_codhist is null then 0               else x.e63_codhist end as e63_codhist,
		       case when x.e40_descr   is null then 'SEM HISTORICO' else x.e40_descr   end as e40_descr,
		       sum(e60_vlremp) as e60_vlremp, sum(e60_vlranu) as e60_vlranu, sum(e60_vlrliq) as e60_vlrliq, sum(e60_vlrpag) as e60_vlrpag from 
		       ($sqlperiodo) as x
                       left join empemphist on empemphist.e63_numemp = x.e60_numemp 
                       left join emphist on emphist.e40_codhist = empemphist.e63_codhist
		       group by x.e63_codhist,x.e40_descr order by x.e63_codhist";
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
		 $pdf->cell(200,$tam,"TOTALIZAÇÃO DOS HISTÓRICOS",1,0,"C",1);
		 $pdf->cell(66,$tam,"SALDO A PAGAR",1,1,"C",1);
	         $pdf->SetFont('Arial','B',7);	 
		 $pdf->Cell(20,$tam,strtoupper($RLe63_codhist),1,0,"C",1);
 		 $pdf->Cell(100,$tam,strtoupper($RLe40_descr  ),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
                 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
                 $pdf->Cell(22,$tam,"LIQUIDADO",1,0,"C",1);   //quebra linha
                 $pdf->Cell(22,$tam,"NAO LIQUIDADO",1,0,"C",1);   //quebra linha
                 $pdf->Cell(22,$tam,"GERAL",1,1,"C",1);   //quebra linha
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
	    $total = $e60_vlrliq - $e60_vlrpag ;
            $pdf->Cell(22,$tam,db_formatar($e60_vlrliq - $e60_vlrpag,'f'),0,0,"R",$p);
            $pdf->Cell(22,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq,'f'),0,0,"R",$p);
            $pdf->Cell(22,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag,'f'),0,1,"R",$p); //quebra linha
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
                 $pdf->Cell(22,$tam,db_formatar($t_liq1 - $t_pag1,'f'),"T",0,"R",1);
                 $pdf->Cell(22,$tam,db_formatar($t_emp1 - $t_anu1 - $t_liq1,'f'),"T",0,"R",1);
                 $pdf->Cell(22,$tam,db_formatar($t_emp1 - $t_anu1 - $t_pag1,'f'),"T",1,"R",1);   //quebra linha
	 	 $pdf->SetFont('Arial','',7);	
	   }
	   /* */
 

     }  

  }else{

  }
 
}

if ($hist=="h"){
     
       $sql="select 	orctiporec.o15_codigo, orctiporec.o15_descr,
		    	sum(x.e60_vlremp) as e60_vlremp,
		    	sum(x.e60_vlranu) as e60_vlranu,
		    	sum(x.e60_vlrliq) as e60_vlrliq,
		    	sum(x.e60_vlrpag) as e60_vlrpag
			from
		       ($sqlperiodo) as x
		      	inner join orcdotacao on orcdotacao.o58_anousu = x.e60_anousu
					   and orcdotacao.o58_coddot = x.e60_coddot
		      	inner join orctiporec on orcdotacao.o58_codigo = orctiporec.o15_codigo
		      	inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
			                       and  orcelemento.o56_anousu = orcdotacao.o58_anousu
		       	group by orctiporec.o15_codigo, orctiporec.o15_descr order by orctiporec.o15_codigo";
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
		 $pdf->cell(200,$tam,"TOTALIZAÇÃO POR TIPO DE COMPRA",1,0,"C",1);
		 $pdf->cell(66,$tam,"SALDO A PAGAR",1,1,"C",1);
		 $pdf->SetFont('Arial','B',7);	 
		 $pdf->Cell(20,$tam,'Codigo',1,0,"C",1);
		 $pdf->Cell(100,$tam,strtoupper($RLo15_descr  ),1,0,"C",1);
		 $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
		 $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
		 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
		 $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
		 $pdf->Cell(22,$tam,"LIQUIDADO",1,0,"C",1);
		 $pdf->Cell(22,$tam,"NÃO LIQUIDADO",1,0,"C",1);
		 $pdf->Cell(22,$tam,"GERAL",1,1,"C",1); //quebra linha
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
	    $total = $e60_vlrliq -$e60_vlrpag ;
	    $pdf->Cell(22,$tam,db_formatar($e60_vlrliq - $e60_vlrpag,'f'),0,0,"R",$p);
	    $pdf->Cell(22,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq,'f'),0,0,"R",$p);
	    $pdf->Cell(22,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag,'f'),0,1,"R",$p);   //quebra linha
	    $t_emp5  += $e60_vlremp;
	    $t_liq5  += $e60_vlrliq;
	    $t_anu5  += $e60_vlranu;
	    $t_pag5  += $e60_vlrpag;
	    $t_total5+= $total;
	    if($p==0){
	      $p=1;  
	    }else $p=0;
	    if ($x == ($rows -1)) {
		 $pdf->Ln();
		 $pdf->setX(110);
		 /* imprime totais -*/
		 $pdf->SetFont('Arial','B',7);	
		 $pdf->Cell(20,$tam,"TOTAL ","T",0,"L",1);
		 $pdf->Cell(20,$tam,db_formatar($t_emp5,'f'),"T",0,"R",1);
		 $pdf->Cell(20,$tam,db_formatar($t_anu5,'f'),"T",0,"R",1);
		 $pdf->Cell(20,$tam,db_formatar($t_liq5,'f'),"T",0,"R",1);
		 $pdf->Cell(20,$tam,db_formatar($t_pag5,'f'),"T",0,"R",1);
		 $pdf->Cell(22,$tam,db_formatar($t_liq1 - $t_pag1,'f'),"T",0,"R",1);
		 $pdf->Cell(22,$tam,db_formatar($t_emp1 - $t_anu1 - $t_liq1,'f'),"T",0,"R",1);
		 $pdf->Cell(22,$tam,db_formatar($t_emp1 - $t_anu1 - $t_pag1,'f'),"T",1,"R",1); // quebra linha
		 $pdf->SetFont('Arial','',7);	
	   
	   
	   }
	   /* */
 

     }  

   }
 
}

if ($hist=="h"){
     
       $sql="select x.o58_orgao,
		    x.o40_descr,
		    sum(e60_vlremp) as e60_vlremp,
		    sum(e60_vlranu) as e60_vlranu,
		    sum(e60_vlrliq) as e60_vlrliq,
		    sum(e60_vlrpag) as e60_vlrpag
		    from
		    ($sqlperiodo) as x
		    inner join orcdotacao 	on 	orcdotacao.o58_anousu = x.e60_anousu and
					       		orcdotacao.o58_coddot = x.e60_coddot
		    inner join orcorgao 	on 	orcorgao.o40_orgao = orcdotacao.o58_orgao and orcorgao.o40_anousu = orcdotacao.o58_anousu
		    inner join orcunidade 	on 	o41_orgao = orcorgao.o40_orgao and o41_unidade = orcdotacao.o58_unidade and o41_anousu = orcorgao.o40_anousu
		    group by x.o58_orgao, x.o40_descr
		    ";
//     echo $sql;exit;
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

	  if ($imprime_header==true) {
	     $pdf->Ln();
	     $pdf->SetFont('Arial','B',8);	 
	     $pdf->cell(200,$tam,"TOTALIZAÇÃO POR ORGAO",1,0,"C",1);
	     $pdf->cell(66,$tam,"SALDO A PAGAR",1,1,"C",1);
	     $pdf->SetFont('Arial','B',7);	 
	     $pdf->Cell(20,$tam,'ORGAO',1,0,"C",1);
		     $pdf->Cell(100,$tam,"DESCRICAO",1,0,"C",1);
	     $pdf->Cell(20,$tam,strtoupper($RLe60_vlremp),1,0,"C",1);
	     $pdf->Cell(20,$tam,strtoupper($RLe60_vlranu),1,0,"C",1);
	     $pdf->Cell(20,$tam,strtoupper($RLe60_vlrliq),1,0,"C",1);
	     $pdf->Cell(20,$tam,strtoupper($RLe60_vlrpag),1,0,"C",1);
	     $pdf->Cell(22,$tam,"LIQUIDADO",1,0,"C",1);
	     $pdf->Cell(22,$tam,"NÃO LIQUIDADO",1,0,"C",1);
	     $pdf->Cell(22,$tam,"GERAL",1,1,"C",1);   //quebra linha
	     $pdf->Ln();
	     $pdf->SetFont('Arial','',7);	
	     $imprime_header=false;
	  }
	  /* ----------- */
	  $pdf->Ln(1);
	  $pdf->Cell(20,$tam,$o58_orgao,0,0,"R",$p);
	  $pdf->Cell(100,$tam,$o40_descr,0,0,"L",$p);
	  $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",$p);
	  $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",$p);
	  $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",$p);
	  $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",$p);
	  $total = $e60_vlrliq - $e60_vlrpag ;
	  $pdf->Cell(22,$tam,db_formatar($e60_vlrliq - $e60_vlrpag,'f'),0,0,"R",$p);
	  $pdf->Cell(22,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq,'f'),0,0,"R",$p);
	  $pdf->Cell(22,$tam,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag,'f'),0,1,"R",$p); // quebra linha
	  $t_emp6  += $e60_vlremp;
	  $t_liq6  += $e60_vlrliq;
	  $t_anu6  += $e60_vlranu;
	  $t_pag6  += $e60_vlrpag;
	  $t_total6+= $total;
	  if($p==0){
	    $p=1;  
	  }else $p=0;
	  if ($x == ($rows -1)) {
	       $pdf->Ln();
	       $pdf->setX(110);
	       /* imprime totais -*/
	       $pdf->SetFont('Arial','B',7);	
	       $pdf->Cell(20,$tam,"TOTAL ","T",0,"L",1);
	       $pdf->Cell(20,$tam,db_formatar($t_emp6,'f'),"T",0,"R",1);
	       $pdf->Cell(20,$tam,db_formatar($t_anu6,'f'),"T",0,"R",1);
	       $pdf->Cell(20,$tam,db_formatar($t_liq6,'f'),"T",0,"R",1);
	       $pdf->Cell(20,$tam,db_formatar($t_pag6,'f'),"T",0,"R",1);
	       $pdf->Cell(22,$tam,db_formatar($t_liq1 - $t_pag1,'f'),"T",0,"R",1);
	       $pdf->Cell(22,$tam,db_formatar($t_emp1 - $t_anu1 - $t_liq1,'f'),"T",0,"R",1);
	       $pdf->Cell(22,$tam,db_formatar($t_emp1 - $t_anu1 - $t_pag1,'f'),"T",1,"R",1); // quebra linha
	       $pdf->SetFont('Arial','',7);	
	     }
         }

     }

   $t_emp6=0;
   $t_liq6=0;
   $t_anu6=0;
   $t_pag6=0;
   $t_total6=0;

       $sql="select x.o58_orgao,
		    x.o58_unidade,
		    x.o40_descr,
		    x.o41_descr,
		    sum(e60_vlremp) as e60_vlremp,
		    sum(e60_vlranu) as e60_vlranu,
		    sum(e60_vlrliq) as e60_vlrliq,
		    sum(e60_vlrpag) as e60_vlrpag
	     from ($sqlperiodo) as x
		    inner join orcdotacao 	on orcdotacao.o58_anousu = x.e60_anousu
					 	and orcdotacao.o58_coddot = x.e60_coddot
		    inner join orcorgao 	on orcorgao.o40_orgao = orcdotacao.o58_orgao and o40_anousu = orcdotacao.o58_anousu
		    inner join orcunidade 	on o41_orgao = orcorgao.o40_orgao and o41_unidade = orcdotacao.o58_unidade and o41_anousu = orcorgao.o40_anousu
		    group by x.o58_orgao, x.o58_unidade, x.o40_descr, x.o41_descr";
//     echo $sql;exit;
   $result1=$clempempenho->sql_record($sql);
   if ($clempempenho->numrows > 0 and 1 == 2){
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

      if ($imprime_header==true) {
	 $pdf->Ln();
	 $pdf->SetFont('Arial','B',8);	 
	 $pdf->cell(275,$tam,"TOTALIZAÇÃO POR ORGAO/UNIDADE",1,1,"C",1);
	 $pdf->SetFont('Arial','B',7);	 
	 $pdf->Cell(10,$tam,'ORGAO',1,0,"C",1);
	 $pdf->Cell(60,$tam,"DESCRICAO",1,0,"C",1);
	 $pdf->Cell(15,$tam,"UNIDADE",1,0,"C",1);
	 $pdf->Cell(80,$tam,"DESCRICAO",1,0,"C",1);
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
      $pdf->Cell(10,$tam,$o58_orgao,0,0,"R",$p);
      $pdf->Cell(60,$tam,substr($o40_descr,0,50),0,0,"L",$p);
      $pdf->Cell(15,$tam,$o58_unidade,0,0,"L",$p);
      $pdf->Cell(80,$tam,$o41_descr,0,0,"L",$p);
      $pdf->Cell(20,$tam,$e60_vlremp,0,0,"R",$p);
      $pdf->Cell(20,$tam,$e60_vlranu,0,0,"R",$p);
      $pdf->Cell(20,$tam,$e60_vlrliq,0,0,"R",$p);
      $pdf->Cell(20,$tam,$e60_vlrpag,0,0,"R",$p);
      $total = $e60_vlrliq - $e60_vlrpag ;
      $pdf->Cell(30,$tam,db_formatar($total,'f'),0,1,"R",$p);   //quebra linha
      $t_emp6  += $e60_vlremp;
      $t_liq6  += $e60_vlrliq;
      $t_anu6  += $e60_vlranu;
      $t_pag6  += $e60_vlrpag;
      $t_total6+= $total;
      if($p==0){
	$p=1;  
      }else $p=0;
      if ($x == ($rows -1)) {
	   $pdf->Ln();
//  $pdf->setX(110);
	   /* imprime totais -*/
	   $pdf->SetFont('Arial','B',7);	
	   $pdf->Cell(140,$tam,"","T",0,"L",0);
	   $pdf->Cell(25,$tam,"TOTAL ","T",0,"L",1);
	   $pdf->Cell(20,$tam,db_formatar($t_emp6,'f'),"T",0,"R",1);
	   $pdf->Cell(20,$tam,db_formatar($t_anu6,'f'),"T",0,"R",1);
	   $pdf->Cell(20,$tam,db_formatar($t_liq6,'f'),"T",0,"R",1);
	   $pdf->Cell(20,$tam,db_formatar($t_pag6,'f'),"T",0,"R",1);
	   $pdf->Cell(30,$tam,db_formatar($t_total6,'f'),"T",1,"R",1);   //quebra linha
	   $pdf->SetFont('Arial','',7);	
	 }
       }

     }



     

}

$pdf->output();

?>