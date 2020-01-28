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
include("classes/db_empresto_classe.php");
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
$clempresto = new cl_empresto;
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

//$tipo = "a"; // sempre analitico

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits= $clselorcdotacao->getInstit();

$sele_work = $clselorcdotacao->getDados(false);

$clrotulo->label("pc50_descr");
///////////////////////////////////////////////////////////////////////
    $campos ="e60_resumo, e60_numemp,e60_codemp,e60_emiss,e60_numcgm,z01_nome,z01_cgccpf,z01_munic,e60_vlremp,e60_vlranu,e60_vlrliq,e63_codhist,e40_descr,";
    $campos = $campos."e60_vlrpag,e60_anousu,e60_coddot,o58_coddot,o58_orgao,o40_orgao,o40_descr,o58_unidade,o41_descr,o15_codigo,o15_descr";
    $campos = $campos.",fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,e60_codcom,pc50_descr";

//---------
// monta sql
$txt_where=" and e60_instit in $instits";
if ($listacredor!=""){
    if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and e60_numcgm in  ($listacredor)";
    } else {
           $txt_where= $txt_where." and e60_numcgm not in  ($listacredor)";
     }	 
}
$txt_where .= " and $sele_work";

  /*  
  if (($datacredor1!="--")) {
        $txt_where = $txt_where." and e60_emiss  between '$datacredor' and '$datacredor1'  ";
	$info="De " . db_formatar($datacredor,"d") . " até " . db_formatar($datacredor1,"d") . ".";
  } else if ($datacredor!="--"){
	  $txt_where = $txt_where." and e60_emiss >= '$datacredor'  ";
	  $info="Apartir de " . db_formatar($datacredor,"d") . ".";
  } else if ($datacredor1!="--"){
         $txt_where = $txt_where."    e60_emiss <= '$datacredor1'   ";  
         $info="Até " . db_formatar($datacredor1,"d") . ".";
  } */



$info="De " . db_formatar($datacredor,"d") . " até " . db_formatar($datacredor1,"d") . ".";
 /////////////////////////////////////////////  

     $result1 = $clempempenho->sql_record("begin;");

//     echo $sqlperiodo;exit;
     $sqlwork = "create temporary table workempliqapagar (codlan integer, data date, numemp integer, codemp varchar(20), valor float8, nome varchar(40), anousu integer)";
     $res     = $clempempenho->sql_record($sqlwork);
    
     $sqlperiodo ="
              select x.c75_numemp, x.e60_codemp, x.e60_anousu, x.z01_nome, totalliquidado, totalanuliq, totalpago, totalanupag
              from 
     		     (  select conlancamemp.c75_numemp, empempenho.e60_codemp, empempenho.e60_anousu,
                               cgm.z01_nome, sum(c70_valor) as totalliquidado 
                    from conlancam 
                           inner join conlancamdoc on c70_codlan = c71_codlan 
                           inner join conlancamemp on c75_codlan = c70_codlan
 			               inner join empempenho on c75_numemp = e60_numemp 
			               inner join orcdotacao on o58_anousu=e60_anousu and o58_instit=e60_instit and o58_coddot=e60_coddot
			               inner join orcelemento on o56_codele=o58_codele and o56_anousu=o58_anousu
                           inner join cgm on e60_numcgm = z01_numcgm
                    where c70_data between '$datacredor' and '$datacredor1' and c71_coddoc in (3, 23, 33)
                              $txt_where
                    group by c75_numemp, e60_codemp, e60_anousu, z01_nome
                   ) as x 
		            left join  ( select conlancamemp.c75_numemp, sum(c70_valor) as totalpago
                                    from conlancam 
											inner join conlancamdoc on c70_codlan = c71_codlan
											inner join conlancamemp on c75_codlan = c70_codlan
									where  c71_coddoc in (5, 35) 
									group by c75_numemp
                             ) as y		on x.c75_numemp = y.c75_numemp 
		           left join	( select conlancamemp.c75_numemp, sum(c70_valor) as totalanuliq
								  from conlancam 
										  inner join conlancamdoc on c70_codlan = c71_codlan
										  inner join conlancamemp on c75_codlan = c70_codlan
			                      where c70_data between '$datacredor' and '$datacredor1' and c71_coddoc in (4, 24, 34)
                                  group by c75_numemp
                   ) as z   on x.c75_numemp = z.c75_numemp 
		           left join ( select conlancamemp.c75_numemp, sum(c70_valor) as totalanupag 
 								  from conlancam
                                         inner join conlancamdoc on c70_codlan = c71_codlan
										 inner join conlancamemp on c75_codlan = c70_codlan
								  where c70_data between '$datacredor' and '$datacredor1' and c71_coddoc in (6, 36) 
                                  group by c75_numemp
                    ) as a  on x.c75_numemp = a.c75_numemp";
//		where (totalliquidado - totalanuliq) > (totalpago - totalanupag)";
       //echo $sqlperiodo;exit;
       
       
       
       
       
      $result1 = $clempempenho->sql_record($sqlperiodo);
//      db_criatabela($result1);
      $rows1   = $clempempenho->numrows;
      $x=0;
      for ($contador = 0; $contador < $rows1; $contador++) {
	db_fieldsmemory($result1,$contador,true);

        if ($x == 1) {	
	  if ($c75_numemp != 12350 and $c75_numemp != 44 and $c75_numemp != 511 and $c75_numemp != 4588) continue;
          echo "<br>";
          echo "empenho ......: $c75_numemp<br>";
          echo "totalliquidado: $totalliquidado<br>";
          echo "totalanuliq ..: $totalanuliq<br>";
          echo "totalpago ....: $totalpago<br>";
          echo "totalanupag ..: $totalanupag<br>";
          echo "pagliquido ...: $totalanupag - $totalanupag<br>";
        }
        if ( round((round($totalliquidado,2) - round($totalanuliq,2)),2) > round((round($totalpago,2) - round($totalanupag,2)),2)) {

	  $sql2 = "select * from conlancam inner join conlancamdoc on c70_codlan = c71_codlan inner join conlancamemp on c75_codlan = c70_codlan where c71_coddoc in (3, 23, 33, 4, 24, 34) and conlancamemp.c75_numemp = $c75_numemp order by c70_data";
	  $result2 = $clempempenho->sql_record($sql2);
	  $rows2   = $clempempenho->numrows;
	  
	  $numemp    = $c75_numemp;
	  $liquidado = 0;

	  for ($lanc = 0; $lanc < $rows2; $lanc++) {
	    db_fieldsmemory($result2,$lanc);
	
            if ($x == 1) {	
	              echo "   coddoc: $c71_coddoc - valor: $c70_valor - liquidado: $liquidado<br>";
            }
	    
        if ($c71_coddoc == 4 or $c71_coddoc == 24 or $c71_coddoc == 34) {
	         $liquidado -= $c70_valor;
	         continue;
	    }
	 
	    if ( round((round($c70_valor,2) + round($liquidado,2)),2) <= round(( round($totalpago,2) - round($totalanupag,2)),2) ) {
	      $liquidado += $c70_valor;
	    } else {
	      $valor = $c70_valor;
	      if ($lanc == ($rows2 - 1)) {
		if ($x == 1) {	
                    echo "valor - pago - pago anulado: " . $valor - ($totalpago - $totalanupag) . "<br>";
		}
		if ($liquidado == 0) {
		  if ( round($valor,2) - round((round($totalpago,2) - round($totalanupag,2)),2) >= 0) {
		    $valor = $valor - ($totalpago - $totalanupag);
		  }
		} else {
		  if ( round((round($liquidado,2) + round($valor,2)),2) - (round($totalpago,2) - round($totalanupag,2)) >= 0) {
		    $valor = ($liquidado + $valor) - ($totalpago - $totalanupag);
		  }
		}
		if ($x == 1) {	
                    echo "valor inserido: $valor<br>";
		}
	      }
//	      if (($valor + $liquidado) >= ($totalpago - $totalanupag)) {
//		$liquidado += $valor;
//	      } else {
		$sql3 = "insert into workempliqapagar values ($c70_codlan, '$c70_data', $numemp, $e60_codemp, $valor, '$z01_nome', $e60_anousu)";
		$result3 = $clempempenho->sql_record($sql3);
//	      }

	    }
	    
	  }

	}
	
      }
      if ($x == 1) {	
        exit;
      }

//////////////////////////////////////////////////////////////////////

$head3 = "Relatório de empenhos a pagar por liquidacao";

$head5= "$info";

$head6= "";

$sql4 = "select * from workempliqapagar order by data, numemp";
$result4 = $clempempenho->sql_record($sql4);
//db_criatabela($result4);
$rows4   = $clempempenho->numrows;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$tam = '04';
$imprime_header=true;

$total = 0;

for ($contador = 0; $contador < $rows4; $contador++ ) {
  db_fieldsmemory($result4, $contador, true);

   if ($tipo == "doexerc") {
     $res = $clempresto->sql_record($clempresto->sql_query(null, null,"*", "", "e91_numemp = $numemp"));
     if ($clempresto->numrows > 0) continue;
   }

   if ($tipo == "rps") {
     $res = $clempresto->sql_record($clempresto->sql_query(null, null,"*", "", "e91_numemp = $numemp"));
     if ($clempresto->numrows == 0) continue;
   }

   // header
   if ($pdf->gety() > $pdf->h - 40){
      $pdf->addpage("L"); 
      $imprime_header=true;
   }

   if ($imprime_header==true) {

     $pdf->Ln();
     
     $pdf->SetFont('Arial','B',7);	 
     
     $pdf->Cell(20,$tam,"NUMEMP"		,1,0,"C",1);
     $pdf->Cell(20,$tam,"EMPENHO"		,1,0,"C",1);
     $pdf->Cell(10,$tam,"ANO"    		,1,0,"C",1);
     $pdf->Cell(80,$tam,"FORNECEDOR"		,1,0,"C",1);  
     $pdf->Cell(30,$tam,"DATA LIQUIDACAO"	,1,0,"C",1);  
     $pdf->Cell(30,$tam,"CODIGO LANÇAMENTO"	,1,0,"C",1);  
     $pdf->Cell(60,$tam,"VALOR A PAGAR"		,1,1,"C",1);  

     $imprime_header=false;

   }
 
   // dados
   $pdf->Cell(20,$tam,$numemp 			,0,0,"R",0); 
   $pdf->Cell(20,$tam,$codemp 			,0,0,"R",0); 
   $pdf->Cell(10,$tam,$anousu 			,0,0,"R",0); 
   $pdf->Cell(80,$tam,$nome   			,0,0,"L",0);  
   $pdf->Cell(30,$tam,$data			,0,0,"C",0);
   $pdf->Cell(30,$tam,$codlan 			,0,0,"R",0);
   $pdf->Cell(60,$tam,db_formatar($valor,'f')	,0,1,"R",0);
   $total += $valor;
 
}
$pdf->Ln();
$pdf->Cell(190,$tam,"TOTAL DE REGISTROS: " . db_formatar($rows4,"c"),1,0,"L",1);
$pdf->Cell( 60,$tam,db_formatar($total,'f'),                         1,1,"R",1);

$result1 = $clempempenho->sql_record("commit;");
$pdf->output();

?>