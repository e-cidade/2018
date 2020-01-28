<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_utils.php");
include("classes/db_relrubmov_classe.php");
include("classes/db_selecao_classe.php");
include("classes/db_rhrubricas_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrelrubmov  = new cl_relrubmov;
$clselecao  = new cl_selecao;
$clrhrubricas  = new cl_rhrubricas;
$clgeradorsql = new cl_gera_sql_folha;
$clgeradorsub = new cl_gera_sql_folha;
$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('o40_orgao');
$clrotulo->label('o41_unidade');
$clrotulo->label('r70_codigo');
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('rh27_pd');
$clrotulo->label('rh27_obs');
$clrotulo->label('rh55_estrut');
$clrotulo->label('r70_estrut');
$clrotulo->label('r70_descr');
$clrotulo->label('rh01_admiss');
$clrotulo->label('rh30_descr');
$clrotulo->label('rh37_descr');

//echo ($clrelrubmov->sql_query(null,"rh46_rubric,rh46_quantval,rh45_form as formulado,rh45_selecao,rh45_descr","rh46_seq","rh46_codigo=$codigo"));exit;
$result_rubricas = $clrelrubmov->sql_record($clrelrubmov->sql_query(null,"rh46_rubric,rh46_quantval,rh45_form as formulado,rh45_selecao,rh45_descr","rh46_seq","rh46_codigo=$codigo"));
$numrows_rubricas = $clrelrubmov->numrows;
if($numrows_rubricas == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Relatório Nro. $codigo não encontrado");
}

db_fieldsmemory($result_rubricas,0);

//echo ($clselecao->sql_query_file($rh45_selecao,db_getsession('DB_instit'),"r44_descr,r44_where"));exit;
$result_selecao = $clselecao->sql_record($clselecao->sql_query_file($rh45_selecao,db_getsession('DB_instit'),"r44_descr,r44_where"));

if($clselecao->numrows > 0){
  db_fieldsmemory($result_selecao,0);
  $mostrawhere = " $r44_where ";
}

if(!isset($ano) || (isset($ano) && trim($ano) == "")){
  $ano = db_anofolha();
}
if(!isset($mes) || (isset($mes) && trim($mes) == "")){
  $mes = db_mesfolha();
}

$arr_siglas = Array(
                    "00"=>"r14",
                    "01"=>"r22",
                    "02"=>"r31",
                    "03"=>"r20",
                    "04"=>"r35",
                    "05"=>"r48",
                    "06"=>"r90",
                    "07"=>"r10",
                    "08"=>"r47",
                    "09"=>"r19",
                    "10"=>"r34"
                   );
$arr_pontos = Array(
                    "00"=>"Salário",
                    "01"=>"Adiantamento",
                    "02"=>"Férias",
                    "03"=>"Rescisão",
                    "04"=>"Saldo 13o",
                    "05"=>"Complementar",
                    "06"=>"Ponto Fixo",
                    "07"=>"Ponto Salário",
                    "08"=>"Ponto Complementar",
                    "09"=>"Ponto Rescisão",
                    "10"=>"Ponto 13o"
                   );
$rubrleg = "";
$virgleg = "";
$group= "";
$maior= "";
$camp = "";
$camp1= "";
$case = "";
$virg = "";
$or   = "";
$arr_rubricas = Array();
$arr_quantval = Array();
$subformulado = $formulado;
for($i=0; $i<$numrows_rubricas; $i++){
  db_fieldsmemory($result_rubricas, $i);
  if($rh46_quantval == "Q"){
    $quantval = "quant";
    $arr_quantval[($i+1)] = "(Quant)";
  }else{
    $quantval = "val";
    $arr_quantval[($i+1)] = "(Val)";
  }

  $arr_rubricas[($i+1)] = $rh46_rubric;
  $case .= $virg."case when rub='".$rh46_rubric."' then ".$quantval." else 0 end as rub".($i+1);
  $camp .= $virg."'".$rh46_rubric."'";
  $camp1.= $virg."sum(rub".($i+1).") as rub".($i+1);
  $maior.= $or." rub".($i+1)." > 0";
  $group.= $virg."rub".($i+1);
  $virg = ", ";
  $or  = " or ";
  if(trim($formulado) != ""){
    $subformulado = str_replace("RUB".($i+1),"sum(rub".($i+1).")",strtoupper($subformulado));
  }else{
    $subformulado.= "+sum(rub".($i+1).")";
  }
  $rubrleg.= $virgleg."'".$rh46_rubric."'";
  $virgleg = ",";
}

$result_rubricas = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,db_getsession('DB_instit'),"rh27_rubric,rh27_descr,rh27_obs,case when rh27_pd = 1 then 'PROVENTO' when rh27_pd = 2 then 'DESCONTO'  else 'BASE' end as rh27_pd","","rh27_rubric in (".$rubrleg.") and rh27_instit = ".db_getsession('DB_instit')));

$formulado = $subformulado;
$camposgerador = "#s#_regist as regist, #s#_anousu as anousu, #s#_mesusu as mesusu, #s#_rubric as rub, #s#_valor as val, #s#_quant as quant";
$sqlDENTRO = "";
$imprime_selecao  = "";
$union = "";
$virg  = "";

$arr_selecionados = split(",",$selecionados);
for($i=0; $i<count($arr_selecionados); $i++){
  $indice_siglas = $arr_selecionados[$i];
  $clgeradorsql->inicio_rh= false;
  $sqlDENTRO    .= $union." (".$clgeradorsql->gerador_sql($arr_siglas["$indice_siglas"],$ano,$mes,null,null,$camposgerador,"#s#_regist","#s#_rubric in ($camp)").") ";
  $union = " union ";
  $imprime_selecao .= $virg.$arr_pontos["$indice_siglas"];
  $virg = ", ";
}

$clgeradorsub->inner_fun = false;
$clgeradorsub->inner_lot = false;
$clgeradorsub->inner_exe = false;
$clgeradorsub->inner_org = false;
$clgeradorsub->inner_atv = false;
$clgeradorsub->usar_cgm = true;

$campos = "";
$campos1= "";
$orderb = "regist ";
$orderb2 = "regist ";
if($ordem == "a"){
  $orderb = "z01_nome ";
  $orderb2= "nome ";
}

if($resumo != "g" && $resumo != "m"){
  $oteste = "";
  $clgeradorsub->usar_lot = true;
  $clgeradorsub->usar_tra = true;
  if($resumo == "o" || $resumo == "l"){
    $clgeradorsub->usar_exe = true;
    $clgeradorsub->usar_org = true;
    if($resumo == "o"){
      $testar = "RLo40_orgao";
      $campos = "case when o40_codtri = '' then o40_orgao::text else o40_codtri end as teste, o40_descr as descr, ";
      $orderb = " o40_orgao, ".$orderb;
    }
    if($resumo == "l"){
      $testar = "RLo41_unidade";
      $campos = "case when o41_codtri = '' then o41_unidade::text else o41_codtri end as teste, case when o40_codtri = '' then o40_orgao::text else o40_codtri end as teste2, o41_descr as descr, ";
      $orderb = " o41_unidade, o41_orgao, ".$orderb;
    }
  }else if($resumo == "lc"){
    $testar = "RLr70_codigo";
    $campos = "r70_codigo as teste, r70_descr as descr, ";
  }else if($resumo == "t"){
    $testar = "RLrh55_estrut";
    $campos = "rh55_estrut as teste, rh55_descr as descr, ";
  }
  $orderb2= " teste,".$oteste.$orderb2;
  $campos1= $oteste."teste,descr,";
}

$where = "";
if($ativos != "g"){
  $clgeradorsub->usar_atv = true;
  if($ativos == "i"){
    $where = " rh30_vinculo = 'I' ";
  }elseif($ativos == "p"){
    $where = " rh30_vinculo = 'P' ";
  }else if($ativos == "ip"){
    $where = " rh30_vinculo in ('I','P') ";
  }else{
    $where = " rh30_vinculo = 'A' ";
  }
}

if(isset($mostrawhere)){
  $clgeradorsub->inner_cgm = false;
  $clgeradorsub->inner_fun = false;
  $clgeradorsub->inner_lot = false;
  $clgeradorsub->inner_exe = false;
  $clgeradorsub->inner_org = false;
  $clgeradorsub->inner_atv = false;
  $clgeradorsub->inner_res = false;
  $clgeradorsub->usar_cgm = true;
  $clgeradorsub->usar_fun = true;
  $clgeradorsub->usar_lot = true;
  $clgeradorsub->usar_exe = true;
  $clgeradorsub->usar_org = true;
  $clgeradorsub->usar_atv = true;
  $clgeradorsub->usar_res = true;
  if(trim($where) != ""){
    $where .= " and ";
  }
  $where .= $mostrawhere;
}

$clgeradorsub->subsql = $sqlDENTRO;
$clgeradorsub->subsqlano = "anousu";
$clgeradorsub->subsqlmes = "mesusu";
$clgeradorsub->subsqlreg = "regist";
$sqlDENTRO = $clgeradorsub->gerador_sql("", null, null, null, null, $campos."rh02_anousu,rh02_mesusu,regist, z01_nome as nome, ".$case." ",$orderb,$where);

$clgeradorsub->usar_cgm = false;
$clgeradorsub->usar_fun = false;
$clgeradorsub->usar_lot = false;
$clgeradorsub->usar_exe = false;
$clgeradorsub->usar_org = false;
$clgeradorsub->usar_atv = false;
$clgeradorsub->usar_res = false;
$clgeradorsub->usar_tra = false;
$clgeradorsub->subsql = $sqlDENTRO;
$clgeradorsub->somente_subsql = true;
$sqlDENTRO = $clgeradorsub->gerador_sql("", null, null, null, null, $campos1."regist, nome, ".$camp1.",".$formulado." as formulado", $orderb2, $maior." group by ".$campos1."regist, nome");

//echo $sqlDENTRO;exit;

$result_dados = $clrelrubmov->sql_record($sqlDENTRO);
$numrows_dados = $clrelrubmov->numrows;
if($numrows_dados == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

if($resumo == "g"){
  $imprime_resumo = " geral";
}else if($resumo == "o"){
  $imprime_resumo = " órgão";
}else if($resumo == "l"){
  $imprime_resumo = " unidade";
}else if($resumo == "lc"){
  $imprime_resumo = " unidade completa";
}else if($resumo == "m"){
  $imprime_resumo = " matrícula";
}else if($resumo == "t"){
  $imprime_resumo = " local de trabalho";
}

$head1 = $rh45_descr." (".$ano." / ".$mes.")";
$head2 = "Selecionados: ".$imprime_selecao;
$head3 = "Resumo por".$imprime_resumo;
if($quebra == "s"){
  $head3 .= " com quebra de página";
}else{
  $head3 .= " sem quebra de página";
}
if($ordem == "a"){
  $imprime_ordem = "alfabética";
}else{
  $imprime_ordem = "numérica";
}
$head4 = "Ordem ".$imprime_ordem;

if($ativos == "g"){
  $imprime_imp = "geral";
}else if($ativos == "i"){
  $imprime_imp = "inativos / pensionistas";
}else if($ativos == "a"){
  $imprime_imp = "ativos";
}

$head5 = "Imprimir ".$imprime_imp;

if(isset($sototais)){
  $head6 = "Somente totais";
}

if($tipoarq == 'pdf') {

	$pdf = new PDF(); 
	$pdf->Open(); 
	$pdf->AliasNbPages(); 
	$pdf->setfillcolor(235);
	$pdf->setfont('arial','b',8);
	
	$total = 0;
	$troca = 1;
	$alt   = 4;
	$cor 	 = 1;
	$antes = "";
	$registroantigo = "";
	
	$totalreg 	= 0;
	
	$totalrub1   = 0;
	$totalrub2   = 0;
	$totalrub3   = 0;
	$totalrub4   = 0;
	$totalrub5   = 0;
	$totalrub6   = 0;
	$totalrub7   = 0;
	$totalrub8   = 0;
	$totalrub9   = 0;
	$totalrub10  = 0;
	$totalform 	 = 0;
	
	$totalgreg 	 = 0;
	
	$totalgrub1  = 0;
	$totalgrub2  = 0;
	$totalgrub3  = 0;
	$totalgrub4  = 0;
	$totalgrub5  = 0;
	$totalgrub6  = 0;
	$totalgrub7  = 0;
	$totalgrub8  = 0;
	$totalgrub9  = 0;
	$totalgrub10 = 0;
	$totalgform  = 0;
	
	if(count($arr_rubricas) > 6) {
	  $pdf->addpage("L");
	}else{
	  $pdf->addpage();
	}
	
	$pdf->setfont('arial','b',8);
	$pdf->cell(100,$alt,"LEGENDA",1,1,"C",1);
	$pdf->cell(20,$alt,$RLrh27_rubric,1,0,"C",1);
	$pdf->cell(60,$alt,$RLrh27_descr,1,0,"C",1);
	$pdf->cell(20,$alt,"Prov / Desc",1,1,"C",1);
	$pdf->setfont('arial','',7);
	$cor = 1;
	
	for($i=0; $i<$clrhrubricas->numrows; $i++){
	  db_fieldsmemory($result_rubricas, $i);
	  if($cor == 1){
	    $cor = 0;
	  }else{
	    $cor = 1;
	  }
	  $pdf->cell(20,$alt,$rh27_rubric,0,0,"C",$cor);
	  $pdf->cell(60,$alt,$rh27_descr,0,0,"L",$cor);
	  $pdf->cell(20,$alt,$rh27_pd,0,1,"L",$cor);
	}
	
	$pdf->ln(2);
	
	for($i = 0; $i < $numrows_dados;$i++){
	   db_fieldsmemory($result_dados,$i);
	   $mudapag = false;
	   if(isset($teste2)){
	     $teste .= $teste2;
	   }
	   if(isset($testar)){
	     if($teste != $registroantigo){
	       $registroantigo = $teste;
	       $mudapag = true;
	       $corT = 1;
	       if($i!=0){
	         $pdf->setfont('arial','b',7);
	         if(!isset($sototais)){
	           $pdf->cell(60,$alt,"TOTAL DE REGISTROS ","LTB",0,"R",1);
	         }else{
	           $pdf->cell(60,$alt,"","LTB",0,"R",0);
	           $corT = 0;
	         }
	         $pdf->cell(15,$alt,$totalreg,"TB",0,"C",$corT);
	         for($ii=0; $ii<count($arr_rubricas); $ii++){
	           $qualtot = 'totalrub'.($ii+1);
	           $pdf->cell(18,$alt,db_formatar($$qualtot,"f"),"TB",0,"R",$corT);
	           $$qualtot = 0;
	         }
	         $pdf->cell(18,$alt,db_formatar($totalform,"f"),"RTB",1,"R",$corT);
	         $totalform = 0;
	         $totalreg = 0;
	         $pdf->setfont('arial','b',8);
	       }
	     }
	   }
	
	   if($pdf->gety() > $pdf->h - 30 || $troca != 0 || $mudapag == true){
	     $pdf->setfont('arial','b',8);
	     $pass = false;
	     if($i==0 || $pdf->gety() > $pdf->h - 30 || ($mudapag == true && $quebra == "s" && !isset($sototais))){
	       if($troca != 1){
	         if(count($arr_rubricas) > 6){
	           $pdf->addpage("L");
	         }else{
	           $pdf->addpage();
	         }
	       }
	       $troca = 0;
	       if(!isset($sototais)){
	         $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
	         $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
	       }else if(isset($testar)){
	         $pdf->cell((93+(18 * count($arr_rubricas))),$alt,$$testar,1,1,"L",1);
	         $pdf->cell(75,$alt,"TOTAL DE REGISTROS",1,0,"C",1);
	       }else{
	         $pdf->cell(75,$alt,"TOTAL DE REGISTROS",1,0,"C",1);
	       }
	       reset($arr_rubricas);
	       for($ii=0; $ii<count($arr_rubricas); $ii++){
	         $index = key($arr_rubricas);
	         $rubrica = $arr_rubricas[$index]." ".$arr_quantval[$index];      	
	         $pdf->cell(18,$alt,$rubrica,1,0,"C",1);
	         next($arr_rubricas);
	       }
	       $pdf->cell(18,$alt,"Total",1,1,"C",1);
	       $troca = 0;
	       if(isset($testar)){
	         $mudapag = true;
	       }
	       if($mudapag == true && !isset($sototais)){
	         $pdf->cell((93+(18 * count($arr_rubricas))),$alt,$$testar,1,1,"L",1);
	       }
	     }
	     $cor = 1;
	
	     if($mudapag == true){
	       $pdf->ln(2);
	       $pdf->cell((93+(18 * count($arr_rubricas))),$alt,$teste." - ".$descr,1,1,"L",1);
	     }
	   }
	
	   if($cor == 1){
	     $cor = 0;
	   }else{
	     $cor = 1;
	   }
	   $pdf->setfont('arial','',7);
	   if(!isset($sototais)){
	     $pdf->cell(15,$alt,$regist,0,0,"C",$cor);
	     $pdf->cell(60,$alt,$nome,0,0,"L",$cor);
	   }
	   reset($arr_rubricas);
	   for($ii=0; $ii<count($arr_rubricas); $ii++){
	     $index = key($arr_rubricas);
	     $rubrica = $arr_rubricas[$index]." ".$arr_quantval[$index];
	     $qualrub = 'rub'.($ii+1);
	     $qualtot = 'totalrub'.($ii+1);
	     $$qualtot+= $$qualrub;
	     $qualtotg = 'totalgrub'.($ii+1);
	     $$qualtotg+= $$qualrub;
	     if(!isset($sototais)){
	       $pdf->cell(18,$alt,db_formatar($$qualrub,"f"),0,0,"R",$cor);
	     }
	     next($arr_rubricas);
	   }
	   $totalreg ++;
	   $totalgreg ++;
	   $totalform += $formulado;
	   $totalgform += $formulado;
	   if(!isset($sototais)){
	     $pdf->cell(18,$alt,db_formatar($formulado,"f"),0,1,"R",$cor);
	   }
	
	}
	
	$pdf->setfont('arial','b',7);
	
	$corT = 1;
	if(!isset($sototais)){
	  $pdf->cell(60,$alt,"TOTAL DE REGISTROS ","LTB",0,"R",1);
	}else{
	  $pdf->cell(60,$alt,"","LTB",0,"R",0);
	  $corT = 0;
	}
	$pdf->cell(15,$alt,$totalreg,"TB",0,"C",$corT);
	for($ii=0; $ii<count($arr_rubricas); $ii++){
	  $qualtot = 'totalrub'.($ii+1);
	  $pdf->cell(18,$alt,db_formatar($$qualtot,"f"),"TB",0,"R",$corT);
	}
	$pdf->cell(18,$alt,db_formatar($totalform,"f"),"RTB",1,"R",$corT);
	
	$pdf->ln(4);
	$pdf->cell(93+(18*count($arr_rubricas)),$alt,"TOTAL GERAL",1,1,"L",1);
	$pdf->cell(60,$alt,"TOTAL DE REGISTROS ","LTB",0,"R",1);
	$pdf->cell(15,$alt,$totalgreg,"TB",0,"C",1);
	for($ii=0; $ii<count($arr_rubricas); $ii++){
	  $qualtotg = 'totalgrub'.($ii+1);
	  $pdf->cell(18,$alt,db_formatar($$qualtotg,"f"),"TB",0,"R",1);
	}
	$pdf->cell(18,$alt,db_formatar($totalgform,"f"),"RTB",1,"R",1);
	
	$pdf->Output();
	
} else if($tipoarq == 'csv') { 
	
  ?>
  <html>
  <head>
  
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body bgcolor="#CCCCCC" marginheight="0" >
  <form name="form1" id="form1"></form>
	
  <?
	
	$troca = 1;
	
	$total          = 0;
	$alt            = 4;
	$antes          = "";
	$registroantigo = "";

	$totalreg 	    = 0;

	$totalrub1      = 0;
	$totalrub2      = 0;
	$totalrub3      = 0;
	$totalrub4      = 0;
	$totalrub5      = 0;
	$totalrub6      = 0;
	$totalrub7      = 0;
	$totalrub8      = 0;
	$totalrub9      = 0;
	$totalrub10     = 0;
	$totalform 	    = 0;
	
	$totalgreg 	    = 0;
	
	$totalgrub1     = 0;
	$totalgrub2     = 0;
	$totalgrub3     = 0;
	$totalgrub4     = 0;
	$totalgrub5     = 0;
	$totalgrub6     = 0;
	$totalgrub7     = 0;
	$totalgrub8     = 0;
	$totalgrub9     = 0;
	$totalgrub10    = 0;
	$totalgform     = 0;

	$sNomeArq = 'tmp/emissao_'.date('YmdHis').'.csv';
	$rFile    = fopen($sNomeArq, 'w+');
	
	fputs($rFile, "{$RLrh27_rubric};{$RLrh27_descr};Prov/Desc\n");
	
	for ($i = 0; $i < $clrhrubricas->numrows; $i++) {
		$oRubricas = db_utils::fieldsMemory($result_rubricas, $i);
		fputs($rFile, $oRubricas->rh27_rubric);
		fputs($rFile, ';');
		fputs($rFile, $oRubricas->rh27_descr);
		fputs($rFile, ';');
		fputs($rFile, $oRubricas->rh27_pd);
		fputs($rFile, "\n");
	}
	
	fputs($rFile, "\n");
	
	if($numrows_dados == 0) {
		db_msgbox('Nenhum registro encontrado.');
		exit;
	}
	
	for($i = 0; $i < $numrows_dados;$i++) {
		db_fieldsmemory($result_dados,$i);
		
		$iInstit = db_getsession('DB_instit');
		
		$sSql = "select r70_estrut, r70_descr,  rh01_admiss, rh30_descr, rh37_descr
							 from rhpessoal
							inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao 
							inner join rhpessoalmov on rhpessoal.rh01_regist = rhpessoalmov.rh02_regist 
							inner join rhlota       on rhlota.r70_codigo     = rhpessoalmov.rh02_lota 
							                       and rhlota.r70_instit     = rhpessoalmov.rh02_instit
							inner join rhregime     on rhregime.rh30_codreg  = rhpessoalmov.rh02_codreg 
							                       and rhregime.rh30_instit  = rhpessoalmov.rh02_instit                       
							 
							 where rh01_regist = {$regist}
							   and rh37_instit = {$iInstit} 
							   and rh02_anousu = {$ano}
							   and rh02_mesusu = {$mes}";
		
		$rsSql = pg_query($sSql);
		
		$oSql = db_utils::fieldsMemory($rsSql, 0, true);
		
	  $mudapag = false;
  	if(isset($teste2)){
    	$teste .= $teste2;
  	}
  	
		if(isset($testar)) {
			
			if($teste != $registroantigo){

				$registroantigo = $teste;
				$mudapag        = true;
	      
				if($i != 0){
					if(!isset($sototais)){
	       		fputs($rFile, " TOTAL DE REGISTROS ");
						
	        }else{
	          fputs($rFile, "");
	          
	        }
	        fputs($rFile, $totalreg);
	        
	        /*for($ii=0; $ii<count($arr_rubricas); $ii++){
	          $qualtot = 'totalrub'.($ii+1);
	          fputs($rFile, db_formatar($$qualtot,"f"));
	          $$qualtot = 0;
	        }
	        fputs($rFile, db_formatar($totalform,"f"));
	        fputs($rFile, "\n");
	        $totalform = 0;*/
	        $totalreg  = 0;
	      }
	    }
		}
		
		if($troca != 0 || $mudapag == true) {
			
    	$pass = false;
    	
    	if($i == 0 || ($mudapag == true && $quebra == "s" && !isset($sototais))){
      
    		if($troca != 1){
	        fputs($rFile, "\n");
    	  }
				$troca = 0;
				
      	if(!isset($sototais)){
      		
	        fputs($rFile, $RLrh01_regist);
	        fputs($rFile, ";");
	        fputs($rFile, $RLz01_nome);
	        fputs($rFile, ";");
	        
	        fputs($rFile, $RLr70_estrut);
	        fputs($rFile, ";");
	        fputs($rFile, $RLr70_descr);
	        fputs($rFile, ";");
	        fputs($rFile, $RLrh01_admiss);
	        fputs($rFile, ";");
	        fputs($rFile, $RLrh30_descr);
	        fputs($rFile, ";");
	        fputs($rFile, $RLrh37_descr);
	        fputs($rFile, ";");
	        
	        
	        
      	}else if(isset($testar)){

	        fputs($rFile, $$testar);
	        fputs($rFile, "\n");
	        fputs($rFile, "TOTAL DE REGISTROS");
	        
      	}else{
      		
        	fputs($rFile, "TOTAL DE REGISTROS");
        	
      	}
      	
      	reset($arr_rubricas);
      		
      	for($ii=0; $ii < count($arr_rubricas); $ii++){
      		
	        $index   = key($arr_rubricas);
	        $rubrica = $arr_rubricas[$index]." ".$arr_quantval[$index] . ";";
	              	
	        fputs($rFile, $rubrica);
	        
	        next($arr_rubricas);
      	}
      	
     	 	fputs($rFile, "TOTAL\n");
     	 	
     	 	
      	$troca = 0;
      	
     	 	if(isset($testar)){
        	$mudapag = true;
      	}
      	if($mudapag == true && !isset($sototais)){
					//$pdf->cell((93+(18 * count($arr_rubricas))),$alt,$$testar,1,1,"L",1);
					fputs($rFile, $$testar);
					fputs($rFile, "\n");
      	}
    	}
    	$cor = 1;

    	if($mudapag == true){
	      fputs($rFile, "\n");
	      
  	    fputs($rFile, $teste." - ".$descr);
  	    fputs($rFile, "\n");
    	}
  	}
  	
		if(!isset($sototais)){
    	fputs($rFile, $regist);
    	fputs($rFile, ";");
    	fputs($rFile, $nome);
    	fputs($rFile, ";");
			fputs($rFile, $oSql->r70_estrut);
			fputs($rFile, ";");
			fputs($rFile, $oSql->r70_descr);
			fputs($rFile, ";");
			fputs($rFile, $oSql->rh01_admiss);
			fputs($rFile, ";");
			fputs($rFile, $oSql->rh30_descr);
			fputs($rFile, ";");
			fputs($rFile, $oSql->rh37_descr);
			fputs($rFile, ";");    	
    	
		}
  	reset($arr_rubricas);
  
 	 	for($ii=0; $ii<count($arr_rubricas); $ii++) {
			$index     = key($arr_rubricas);
			$rubrica   = $arr_rubricas[$index]." ".$arr_quantval[$index];
			$qualrub   = 'rub'.($ii+1);
			$qualtot   = 'totalrub'.($ii+1);
			$$qualtot += $$qualrub;
			$qualtotg  = 'totalgrub'.($ii+1);
			$$qualtotg+= $$qualrub;
			if(!isset($sototais)){
				fputs($rFile, trim(db_formatar($$qualrub,"f")));
				fputs($rFile, ";");
			}
			next($arr_rubricas);
			
		}
		$totalreg++;
		$totalgreg++;
		$totalform  += $formulado;
		$totalgform += $formulado;
		if(!isset($sototais)){
			fputs($rFile, trim(db_formatar($formulado,"f")));
		}
		fputs($rFile,"\n");
  	
	}
	fclose($rFile);
	
	
  echo "<script>";
  echo "  listagem = '$sNomeArq#Download arquivo TXT (dados dos carnes)|';";
  echo "  parent.js_fechaiframe();";
  echo "  js_montarlista(listagem,'form1');";
  echo "</script>";
	
  echo "</body>";
  echo "</html>";
}