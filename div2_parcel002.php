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
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('v07_parcel');
$clrotulo->label('v07_dtlanc');
$clrotulo->label('v07_totpar');
$clrotulo->label('v07_numcgm');
$clrotulo->label('z01_nome  ');
$clrotulo->label('x01_matric  ');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);

db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

set_time_limit(0);

$head3 = "PARCELAMENTOS";

$where = "";
$order_by = "";

if (($data!="--")&&($data1!="--")) {
  $where    = " and v07_dtlanc  between '$data' and '$data1'   ";  
  $order_by = "order by v07_dtlanc,v07_parcel";
  $data     = db_formatar($data,'d');
  $data1    = db_formatar($data1,'d');
  $head5    = "Intervalo: $data ate $data1";
}else if ($data!="--"){
  $where    = " and v07_dtlanc >= '$data'     ";
  $order_by = "order by v07_dtlanc,v07_parcel";
  $data     = db_formatar($data,'d');
  $head5    = "Ordem até $data  ";
}else if ($data1!="--"){
  $where    = " and v07_dtlanc <= '$data1'      ";
  $order_by = "order by v07_dtlanc,v07_parcel";
  $data1    = db_formatar($data1,'d');
  $head5    = "Ordem ate $data1 ";
}else{
  $head5    = "Ordem Parcelamento";
  $order_by = "  order by v07_parcel,v07_dtlanc";
}
if ($situacao != 0) {
  $where   .= " and v07_situacao = $situacao ";		
}
switch ($situacao){
   case 1: 
      $head6 = "Situação: Ativo";
	    break;
   case 2: 
      $head6 = "Situação: Anulado";
	    break;
   case 1: 
      $head6 = "Situação: Reparcelado";
	    break;
 	 default:
     $head6 = "Situação: todos";
	   break;
}



/* 
para quem nao tem bola de cristal..
v07_situacao pode ser
  1 - ativo
  2 - anulado
  3 - reparcelado
*/

$sql  = " select distinct ";
$sql .= "        v07_numpre, ";
$sql .= "        v07_parcel, ";
$sql .= "        v07_dtlanc, ";
$sql .= "        v07_totpar, ";
$sql .= "   		 v07_vlrent, ";
$sql .= " 			 v07_valor, ";
$sql .= "        v07_numcgm, ";
$sql .= "        v07_situacao, ";
$sql .= "        cgmresp.z01_numcgm||' - '||cgmresp.z01_nome as z01_nomeresp,   ";
$sql .= "        (case when cgmarrecad.z01_numcgm is not null then ";
$sql .= "          cgmarrecad.z01_numcgm||' - '||cgmarrecad.z01_nome";
$sql .= "           when v07_situacao = 2 then ";
$sql .= "                         cgmresp.z01_numcgm||' - '||cgmresp.z01_nome";
$sql .= "  										when v07_situacao = 3 then ";
$sql .= "  											(select distinct z01_numcgm||' - '||z01_nome ";
$sql .= "										       from termo te 
                                        inner join termoreparc on v08_parcelorigem = te.v07_parcel";
$sql .= "													      inner join cgm on z01_numcgm = te.v07_numcgm ";
$sql .= "									        where v08_parcelorigem = t.v07_parcel limit 1 )				";
$sql .= "	               else ";
//$sql .= "         cgmpgto.z01_numcgm||' - '||cgmpgto.z01_nome end ) as
$sql .= "         (select distinct z01_numcgm||' - '||z01_nome 
                     from arrepaga 
                          inner join cgm on k00_numcgm =  z01_numcgm ";
$sql .= "           where k00_numpre = v07_numpre limit 1) end)";            
$sql .= "							as nome, ";
$sql .= "        termodiv.parcel as divida, ";
$sql .= "        termoini.parcel as inicial, ";
$sql .= "  		   termodiver.dv10_parcel as diversos, ";
$sql .= "  		   termoreparc.v08_parcel as reparcelamento, ";
$sql .= "  		   termocontrib.parcel as contribuicao, ";
$sql .= "   	   arrecad.k00_numcgm, ";
$sql .= "        db_usuarios.nome as nomeusuarios";
$sql .= "  	from termo t ";
$sql .= "  		   left  join arrecad            on v07_numpre = arrecad.k00_numpre ";
//$sql .= "  		   left  join arrecant           on v07_numpre = arrecant.k00_numpre ";
//$sql .= " 	     left  join cgm cgmpgto 			 on arrecant.k00_numcgm =  cgmpgto.z01_numcgm ";
$sql .= " 	     inner join cgm cgmresp 			 on v07_numcgm = cgmresp.z01_numcgm ";
$sql .= "	       left  join termodiv 					 on v07_parcel = termodiv.parcel ";
$sql .= "	       left  join termoini 					 on v07_parcel = termoini.parcel ";
$sql .= "	       left  join termocontrib  		 on v07_parcel = termocontrib.parcel ";
$sql .= "  	     left  join termodiver 				 on v07_parcel = dv10_parcel ";
$sql .= "  	     left  join termoreparc        on v07_parcel = v08_parcel  ";
$sql .= "				 left  join cgm  cgmarrecad    on arrecad.k00_numcgm = cgmarrecad.z01_numcgm ";
$sql .= "       left   join db_usuarios on db_usuarios.id_usuario = t.v07_login ";
$sql .= " where v07_instit = ".db_getsession('DB_instit')." $where $order_by ";

//die($sql);

$result = pg_query($sql);

if (pg_numrows($result) == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem parcelamentos.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$tipo = "";

$totDiver = 0;
$totInicial = 0;
$totDivida  = 0;
$totReparcelamento = 0;
$totValorDiver = 0;
$totValorInicial = 0;
$totValorDivida = 0;
$totValorReparcelamento = 0;

$totalAnulado     = 0;
$totalReparcelado = 0;
$totalAtivo       = 0;


$totReparcelamento             = 0;
$totReparcelamentoAtivo        = 0;
$totReparcelamentoAnulado      = 0;
$totReparcelamentoReparc       = 0;
$totValorReparcelamento        = 0;
$totValorReparcelamentoAnulado = 0;
$totValorReparcelamentoreparc  = 0;
$totValorReparcelamentoAtivo   = 0;

$totDiver                      = 0;
$totDiverAnulado               = 0;
$totDiverAtivo                 = 0;
$totDiverReparc                = 0;
$totValorDiver                 = 0;
$totValorDiverAnulado          = 0;
$totValorDiverreparc           = 0;
$totValorDiverAtivo            = 0;
                               
$totDivida                     = 0;
$totDividaAtivo                = 0;
$totDividaAnulado              = 0;
$totDividaReparc               = 0;
$totValorDivida                = 0;
$totValorDividaAnulado         = 0;
$totValorDividareparc          = 0;
$totValorDividaAtivo           = 0;
                               
$totInicial                    = 0;
$totInicialAtivo               = 0;
$totInicialAnulado             = 0;
$totInicialReparc              = 0;
$totValorInicial               = 0;
$totValorInicialAnulado        = 0;
$totValorInicialreparc         = 0;
$totValorInicialAtivo          = 0;
                               
$totContrib                    = 0;
$totContribAtivo               = 0;
$totContribAnulado             = 0;
$totContribReparc              = 0;
$totValorContrib               = 0;
$totValorContribAnulado        = 0;
$totValorContribreparc         = 0;
$totValorContribAtivo          = 0;

$corcab      = '210';

for($x = 0; $x < pg_numrows($result);$x++){
  db_fieldsmemory($result,$x);
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
		$pdf->ln(4);
    $pdf->SetFillColor($corcab);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,$RLv07_parcel,  1,0,"C",1);
    $pdf->cell(18,$alt,'Lançamento',   1,0,"C",1);
    $pdf->cell(15,$alt,'Parcelas',     1,0,"C",1);
    $pdf->cell(60,$alt,"Responsavel",  1,0,"C",1);
    
    if($db21_usasisagua != 't') {
	    $pdf->cell(60,$alt,$RLz01_nome,    1,0,"C",1);
	    $pdf->cell(20,$alt,'Tipo',         1,0,"C",1);
	    $pdf->cell(20,$alt,'Situação',     1,0,"C",1);
	    $pdf->cell(20,$alt,'Entrada',      1,0,"C",1);
	    $pdf->cell(20,$alt,'Total',        1,1,"C",1);
    } else {
      $pdf->cell(20,$alt,$RLx01_matric,    1,0,"C",1);
      $pdf->cell(20,$alt,'Tipo',         1,0,"C",1);
      $pdf->cell(20,$alt,'Situação',     1,0,"C",1);
      $pdf->cell(20,$alt,'Entrada',      1,0,"C",1);
      $pdf->cell(20,$alt,'Total',        1,0,"C",1);
      $pdf->cell(60,$alt,'Parcelamento Efetuado Por',        1,1,"C",1);
    }
    $troca = 0;
  }
	    
	if($x % 2 == 0){
	   $corfundo = 236;
	}else{
	   $corfundo = 245;	
	}

	$pdf->SetFillColor($corfundo);

	if ($reparcelamento != ""){
		$tipo = "Reparcelamento";
	}else if ($diversos != ""){
    $tipo = "Diversos";
  }elseif ($inicial != ""){
    $tipo = "Inicial";
  }elseif ($contribuicao != ""){
    $tipo = "Contribuicao";
  }else{
    $tipo = "Divida";
  }

  if ($v07_situacao == 1) {
	  $situ = 'Ativo'	;
	} else if ($v07_situacao == 2) {
	  $situ = 'Anulado'	;
	} else if ($v07_situacao == 3) {
	  $situ = 'Reparcelado';
	}

  $pdf->setfont('arial','',7);

  $pdf->cell(25,$alt,$v07_parcel,                   0,0,"C",1);
  $pdf->cell(18,$alt,db_formatar($v07_dtlanc, 'd'), 0,0,"C",1);
  $pdf->cell(15,$alt,$v07_totpar,                   0,0,"C",1);
  $pdf->cell(60,$alt,$z01_nomeresp,                 0,0,"L",1);
  
  if($db21_usasisagua != 't') {
  
		$sqlOutrosNomes  = " select distinct ";
		$sqlOutrosNomes .= "        z01_numcgm||' - '||z01_nome	as nome ";
		$sqlOutrosNomes .= "   from arrenumcgm ";
		$sqlOutrosNomes .= "        inner join cgm on k00_numcgm = z01_numcgm ";
		$sqlOutrosNomes .= "  where k00_numpre = $v07_numpre ";
		$sqlOutrosNomes .= "    and k00_numcgm <> $v07_numcgm ";
		$rsOutrosNomes   = pg_query($sqlOutrosNomes);
		$intNumrows      = pg_numrows($rsOutrosNomes);
	
		if($intNumrows > 1){
	    $pdf->cell(60,$alt,'',0,0,"L",1);
	    $pdf->cell(20,$alt,'',0,0,"C",1); 
	    $pdf->cell(20,$alt,'',0,0,"L",1);
	    $pdf->cell(20,$alt,'',0,0,"R",1);
	    $pdf->cell(20,$alt,'',0,1,"R",1);
			for ($intCont = 0; $intCont < $intNumrows; $intCont++) {
				db_fieldsmemory($rsOutrosNomes,$intCont);
				$pdf->cell(25,$alt,"",    0,0,"C",1);
				$pdf->cell(18,$alt,"",    0,0,"C",1);
				$pdf->cell(15,$alt,"",    0,0,"C",1);
				$pdf->cell(60,$alt,"",    0,0,"L",1);
	      $pdf->cell(60,$alt,$nome, 0,0,"L",1);
				if ($intCont < ($intNumrows-1) ) {
			  	$pdf->cell(20,$alt,"",    0,0,"C",1); 
				  $pdf->cell(20,$alt,"",    0,0,"L",1);
				  $pdf->cell(20,$alt,"",    0,0,"R",1);
				  $pdf->cell(20,$alt,"",    0,1,"R",1);
				}
			}
	/*    $pdf->cell(25,$alt,'',0,0,"C",1);
	    $pdf->cell(18,$alt,'',0,0,"C",1);
	    $pdf->cell(15,$alt,'',0,0,"C",1);
	    $pdf->cell(60,$alt,'',0,0,"L",1);*/
	    $pdf->cell(20,$alt,$tipo,0,0,"C",1); 
	    $pdf->cell(20,$alt,$situ,0,0,"C",1);
	    $pdf->cell(20,$alt,db_formatar($v07_vlrent,'f'),0,0,"R",1);
	    $pdf->cell(20,$alt,db_formatar($v07_valor,'f'),0,1,"R",1);
	    
		}else{
	    $pdf->cell(60,$alt,$nome,0,0,"L",1);
	    $pdf->cell(20,$alt,$tipo,0,0,"C",1); 
	    $pdf->cell(20,$alt,$situ,0,0,"C",1);
	    $pdf->cell(20,$alt,db_formatar($v07_vlrent,'f'),0,0,"R",1);
	    $pdf->cell(20,$alt,db_formatar($v07_valor,'f'),0,1,"R",1);
	  }
  
  } else {
    
    $rsMatricula = pg_query("select K00_matric from arrematric where k00_numpre = $v07_numpre");
    
    db_fieldsmemory($rsMatricula, 0);
    
    $pdf->cell(20,$alt,@$k00_matric,0,0,"C",1);
    $pdf->cell(20,$alt,$tipo,0,0,"C",1); 
    $pdf->cell(20,$alt,$situ,0,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($v07_vlrent,'f'),0,0,"R",1);
    $pdf->cell(20,$alt,db_formatar($v07_valor,'f'),0,0,"R",1);
    $pdf->cell(60,$alt, $nomeusuarios,0,1,"L",1);
    
  }

  $total++;

  // totalizadores para o resumo //
  if ($tipo == "Diversos"){
    $totDiver++;
    $totValorDiver += $v07_valor;
    if ($v07_situacao == 1) {
      $totDiverAtivo++;
      $totValorDiverAtivo += $v07_valor;
   	} else if ($v07_situacao == 2) {
      $totDiverAnulado++;
      $totValorDiverAnulado += $v07_valor;
	  } else if ($v07_situacao == 3) {
      $totDiverReparc++;
      $totValorDiverreparc += $v07_valor;
  	}
  }elseif($tipo == 'Inicial'){
    $totInicial++;
    $totValorInicial += $v07_valor;
    if ($v07_situacao == 1) {
      $totInicialAtivo++;
      $totValorInicialAtivo += $v07_valor;
   	} else if ($v07_situacao == 2) {
      $totInicialAnulado++;
      $totValorInicialAnulado += $v07_valor;
	  } else if ($v07_situacao == 3) {
      $totInicialReparc++;
      $totValorInicialreparc += $v07_valor;
  	}
  }elseif($tipo == 'Divida'){
    $totDivida++;
    $totValorDivida += $v07_valor;
    if ($v07_situacao == 1) {
      $totDividaAtivo++;
      $totValorDividaAtivo += $v07_valor;
   	} else if ($v07_situacao == 2) {
      $totDividaAnulado++;
      $totValorDividaAnulado += $v07_valor;
	  } else if ($v07_situacao == 3) {
      $totDividaReparc++;
      $totValorDividareparc += $v07_valor;
  	}
  }elseif($tipo == 'Reparcelamento'){
    $totReparcelamento++;
    $totValorReparcelamento += $v07_valor;
    if ($v07_situacao == 1) {
      $totReparcelamentoAtivo++;
      $totValorReparcelamentoAtivo += $v07_valor;
   	} else if ($v07_situacao == 2) {
      $totReparcelamentoAnulado++;
      $totValorReparcelamentoAnulado += $v07_valor;
	  } else if ($v07_situacao == 3) {
      $totReparcelamentoReparc++;
      $totValorReparcelamentoreparc += $v07_valor;
  	}
  }elseif($tipo == 'Contribuicao'){
    $totContrib++;
    $totValorContrib += $v07_valor;
    if ($v07_situacao == 1) {
      $totContribAtivo++;
      $totValorContribAtivo += $v07_valor;
   	} else if ($v07_situacao == 2) {
      $totContribAnulado++;
      $totValorContribAnulado += $v07_valor;
	  } else if ($v07_situacao == 3) {
      $totContribReparc++;
      $totValorContribreparc += $v07_valor;
  	}
  }

}
//exit;

$pdf->setfont('arial','b',8);
$pdf->cell(270,$alt,'TOTAL DE PARCELAMENTOS  :  '.$total,"T",0,"L",0);

if ($opcao == 's'){
  $pdf->addpage("L");
  $pdf->setfont('arial','b',10);
  $pdf->cell(190,$alt,"RESUMO DOS PARCELAMENTOS",0,1,"C",0);
  $pdf->setfont('arial','b',8);
  $pdf->setfillcolor(235);

  $pdf->cell(55,$alt,"Tipo",               1,0,"C",1);
  $pdf->cell(35,$alt,"Qtd. Parcelamentos", 1,0,"C",1);
  $pdf->cell(25,$alt,"Qtd. Anulado",       1,0,"C",1);
  $pdf->cell(25,$alt,"Qtd. Reparcelado",   1,0,"C",1);
  $pdf->cell(25,$alt,"Qtd. Ativo",         1,0,"C",1);
  $pdf->cell(30,$alt,"Total Parcelado",    1,0,"C",1);
  $pdf->cell(30,$alt,"Total Anulado",      1,0,"C",1);
  $pdf->cell(30,$alt,"Total Reparcelado",  1,0,"C",1);
  $pdf->cell(30,$alt,"Total Ativo",        1,1,"C",1);
  if($totDiver>0){
    $pdf->cell(55,$alt,"Parcelamento de diversos",               1,0,"L",0); 
    $pdf->cell(35,$alt,$totDiver,                                1,0,"C",0);
    $pdf->cell(25,$alt,$totDiverAnulado,                         1,0,"C",0);
    $pdf->cell(25,$alt,$totDiverReparc,                          1,0,"C",0);
    $pdf->cell(25,$alt,$totDiverAtivo,                           1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($totValorDiver,'f'),          1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorDiverAnulado,'f'),   1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorDiverreparc,'f'),    1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorDiverAtivo,'f'),     1,1,"R",0);
	}
  if($totInicial>0){
    $pdf->cell(55,$alt,"Parcelamento do foro",                     1,0,"L",0); 
    $pdf->cell(35,$alt,$totInicial,                                1,0,"C",0);
    $pdf->cell(25,$alt,$totInicialAnulado,                         1,0,"C",0);
    $pdf->cell(25,$alt,$totInicialReparc,                          1,0,"C",0);
    $pdf->cell(25,$alt,$totInicialAtivo,                           1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($totValorInicial,'f'),          1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorInicialAnulado,'f'),   1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorInicialreparc,'f'),    1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorInicialAtivo,'f'),     1,1,"R",0);
	}
  if($totDivida>0){
    $pdf->cell(55,$alt,"Parcelamento de divida",                  1,0,"L",0); 
    $pdf->cell(35,$alt,$totDivida,                                1,0,"C",0);
    $pdf->cell(25,$alt,$totDividaAnulado,                         1,0,"C",0);
    $pdf->cell(25,$alt,$totDividaReparc,                          1,0,"C",0);
    $pdf->cell(25,$alt,$totDividaAtivo,                           1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($totValorDivida,'f'),          1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorDividaAnulado,'f'),   1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorDividareparc,'f'),    1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorDividaAtivo,'f'),     1,1,"R",0);
	}
  if($totReparcelamento>0){
    $pdf->cell(55,$alt,"Reparcelamento",                                  1,0,"L",0); 
    $pdf->cell(35,$alt,$totReparcelamento,                                1,0,"C",0);
    $pdf->cell(25,$alt,$totReparcelamentoAnulado,                         1,0,"C",0);
    $pdf->cell(25,$alt,$totReparcelamentoReparc,                          1,0,"C",0);
    $pdf->cell(25,$alt,$totReparcelamentoAtivo,                           1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($totValorReparcelamento,'f'),          1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorReparcelamentoAnulado,'f'),   1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorReparcelamentoreparc,'f'),    1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorReparcelamentoAtivo,'f'),     1,1,"R",0);
	}
  if($totContrib>0 ){
    $pdf->cell(55,$alt,"Parcelamento de contrib. de melhoria",     1,0,"L",0); 
    $pdf->cell(35,$alt,$totContrib,                                1,0,"C",0);
    $pdf->cell(25,$alt,$totContribAnulado,                         1,0,"C",0);
    $pdf->cell(25,$alt,$totContribReparc,                          1,0,"C",0);
    $pdf->cell(25,$alt,$totContribAtivo,                           1,0,"C",0);
    $pdf->cell(30,$alt,db_formatar($totValorContrib,'f'),          1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorContribAnulado,'f'),   1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorContribreparc,'f'),    1,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totValorContribAtivo,'f'),     1,1,"R",0);
	}
}
$pdf->Output();
?>