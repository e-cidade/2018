<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_POST_VARS,2);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
if ($folha == 'r14'){
     $xarquivo = 'DE SALÁRIO';
     $arquivo = 'gerfsal';
}elseif ($folha == 'r20'){
     $xarquivo = 'DE RESCISÄO';
     $arquivo = 'gerfres';
}elseif ($folha == 'r35'){
     $xarquivo = 'DE 13o SALÁRIO';
     $arquivo = 'gerfs13';
}elseif ($folha == 'r22'){
     $xarquivo = 'DE ADIANTAMENTO';
     $arquivo = 'gerfadi';
}elseif ($folha == 'r48'){
     $xarquivo = 'COMPLEMENTAR';
     $arquivo = 'gerfcom';
}

$wherepes = '';
if(isset($semest) && $semest != 0){
  $wherepes = " and r48_semest = ".$semest;
  $head6 = $xarquivo ." ($semest)";
}

if($vinc == 'a'){
  $dvinc = ' ATIVOS';
  $xvinc = " and rh30_vinculo = 'A' ";
}elseif($vinc == 'i'){
  $dvinc = ' INATIVOS';
  $xvinc = " and rh30_vinculo = 'I' ";
}elseif($vinc == 'p'){
  $dvinc = ' PENSIONISTAS';
  $xvinc = " and rh30_vinculo = 'P' " ;
}elseif($vinc == 'ip'){
  $dvinc = ' ATIVOS/PENSIONISTAS ';
  $xvinc = " and rh30_vinculo in ('P','I') ";
}else{
  $dvinc = ' GERAL';
  $xvinc = '';
}


$xcampos  = " rh30_codreg ,rh30_descr";
$campos  = $xcampos;

if($tipo == "G"){
  $xxordem = $xcampos;
  $grupo   = $xcampos;
}else{
  if($ordem == 'a'){
      $xxordem = ' descr, codigo ';
  }else{
      $xxordem = ' codigo, descr';
  }
  if($tipo == "L"){
    $xcampos = $campos.', r70_estrut as codigo,r70_descr as descr '; 
    $wherepes .= "and r70_estrut between '$lotaini' and '$lotafin'";
    $quebra    = "";
    $head7 = "RESUMO GERAL - LOTAÇÕES : ".$lotaini." A ".$lotafin;
  }elseif($tipo == "R"){
    $xcampos = $campos.', o15_codigo as codigo, o15_descr as descr ';   
    $wherepes .= "and r70_estrut between '$lotaini' and '$lotafin'";
    $grupo   = $campos.", ".$xxordem; 
    $quebra    = 0;
    $head7 = "RESUMO GERAL - RECURSOS : ".$lotaini." A ".$lotafin;
  }elseif($tipo == "O"){
    $xcampos = $campos.', o40_orgao as codigo, o40_descr as descr ';   
    $wherepes .= "and r70_estrut between '$lotaini' and '$lotafin'";
    $grupo   = $campos.", ".$xxordem; 
    $quebra    = 0;
    $head7 = "RESUMO GERAL - ORGÃOS : ".$lotaini." A ".$lotafin;
  }elseif($tipo == "T"){
    $quebra    = "";
    $xcampos  = $campos.', rh55_estrut as codigo ,rh55_descr as descr ';
    if($lotaini != "" && $lotafin != ""){
      $wherepes .= " and rh55_estrut >= '$lotaini' and rh55_estrut <= '$lotafin' ";
    }else if($lotaini != ""){
      $wherepes .= " and rh55_estrut >= '$lotaini' ";
    }else if($lotafin != ""){
      $wherepes .= " and rh55_estrut >= '$lotafin' ";
    }
    $head7 = "RESUMO GERAL - LOCAL DE TRABALHO : ".$lotaini." A ".$lotafin;
  }
  $grupo   = $campos.','.$xxordem; 
}




if($reg != 0){
  $wherepes .= " and rh30_regime = ".$reg;
}

$erroajuda = "";

if($sel != 0){
  $result_sel = db_query("select r44_where from selecao where r44_selec = {$sel} and r44_instit = ". db_getsession('DB_instit'));
  if(pg_numrows($result_sel) > 0){
    db_fieldsmemory($result_sel, 0, 1);
    $wherepes .= " and ".$r44_where;
    $erroajuda = " ou seleção informada é inválida";
  }
}

//$head9    = 'FUNCION?RIOS COM INSS';
//$wherepes = " and r01_tbprev = 1 and r01_tpvinc = 'A' ";

$head1 = "RESUMO DA FOLHA DE PAGAMENTO ";
$head3 = "ARQUIVO : ".$xarquivo;
$head5 = "PERÍODO : ".$mes." / ".$ano;
$head9 = "VINCULO : ".$dvinc;


$sql = "select 
               COUNT(DISTINCT(".$folha."_REGIST)) AS FUNC,
               round(sum(case when ".$folha."_pd = 1 then ".$folha."_valor end),2) as provento, 
               round(sum(case when ".$folha."_pd = 2 then ".$folha."_valor end),2) as desconto,
               $xcampos 
        from ".$arquivo."
             inner join rhpessoalmov on rh02_anousu = ".$folha."_anousu 
                                    and rh02_mesusu = $mes
                                    and rh02_regist = ".$folha."_regist 
                                    and rh02_instit = ".$folha."_instit
             inner join rhregime     on rh30_codreg = rh02_codreg 
                                    and rh30_instit = rh02_instit 
             $xvinc 
             left join rhlota        on r70_codigo = rh02_lota
                                    and r70_instit = rh02_instit
             left join (select distinct rh25_codigo,rh25_projativ, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
             left  join rhlotaexe    on rh26_codigo = r70_codigo 
                                    and rh26_anousu = $ano
             left  join orcprojativ  on o55_anousu = $ano
                                    and o55_projativ = rh25_projativ
             left  join orcorgao     on o40_orgao = rh26_orgao
                                    and o40_anousu = $ano
             left join orcunidade    on o41_anousu = $ano
                                    and o41_orgao = rh26_orgao
                                    and o41_unidade = rh26_unidade
             left join orctiporec    on o15_codigo = rh25_recurso
             left join  rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
			                                and rh56_princ = 't'
	           left join rhlocaltrab     on rh55_codigo = rh56_localtrab
		                                and rh55_instit = rh02_instit 
        where ".$folha."_instit = ".db_getsession('DB_instit')."
          and ".$folha."_anousu = $ano
          and ".$folha."_mesusu = $mes
          and ".$folha."_pd <> 3
        $wherepes 
        group by $grupo 
        order by $xxordem";

//die($sql);

$result = db_query($sql);

//db_criatabela($result);exit;

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos no período de '.$mes.' / '.$ano.$erroajuda.".");

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$alt = 4;

$venc        = 0;
$desc        = 0;
$total_func  = 0;
$venc_g      = 0;
$desc_g      = 0;
$total_func_g= 0;
$troca       = 1;

for($x = 0;$x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
     $pdf->addpage();
     $pdf->setfont('arial','b',8);
     $pdf->cell(15,$alt,'VINCULO',1,0,"C",1);
     $pdf->cell(50,$alt,'DESCRICAO',1,0,"C",1);
     $pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
     $pdf->cell(25,$alt,'PROVENTOS',1,0,"C",1);
     $pdf->cell(25,$alt,'DESCONTOS',1,0,"C",1);
     $pdf->cell(25,$alt,'LÍQUIDO',1,1,"C",1);
     $troca = 0;
   }
   if ($tipo != "G"){
     if($quebra != $codigo){
       if($x > 0 ){
         $pdf->setfont('arial','B',8);
         $pdf->cell(65,$alt,'Sub Total',0,0,"L",0);
         $pdf->cell(15,$alt,$total_func,0,0,"C",0);
         $pdf->cell(25,$alt,db_formatar($venc,'f'),0,0,"R",0);
         $pdf->cell(25,$alt,db_formatar($desc,'f'),0,0,"R",0);
         $pdf->cell(25,$alt,db_formatar($venc - $desc,'f'),0,1,"R",0);
         $venc        = 0;
         $desc        = 0;
         $total_func  = 0;
       }
       $quebra = $codigo;
       $pdf->ln(2);
       $pdf->cell(155,5,$codigo." - ".strtoupper($descr),0,1,"L",1);
     }
   }
   $pdf->setfont('arial','',8);
   $pdf->cell(15,$alt,$rh30_codreg,0,0,"C",0);
   $pdf->cell(50,$alt,$rh30_descr,0,0,"L",0);
   $pdf->cell(15,$alt,$func,0,0,"C",0);
   $pdf->cell(25,$alt,db_formatar($provento,'f'),0,0,"R",0);
   $pdf->cell(25,$alt,db_formatar($desconto,'f'),0,0,"R",0);
   $pdf->cell(25,$alt,db_formatar($provento - $desconto,'f'),0,1,"R",0);
   $venc        += $provento;
   $desc        += $desconto;
   $total_func  += $func;
   $venc_g      += $provento;
   $desc_g      += $desconto;
   $total_func_g+= $func;
}
if ($tipo != "G"){
  $pdf->setfont('arial','B',8);
  $pdf->cell(65,$alt,'Sub Total',0,0,"L",0);
  $pdf->cell(15,$alt,$total_func,0,0,"C",0);
  $pdf->cell(25,$alt,db_formatar($venc,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($desc,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($venc - $desc,'f'),0,1,"R",0);
}
$pdf->setfont('arial','B',8);
$pdf->cell(65,$alt,'Total Geral',0,0,"L",0);
$pdf->cell(15,$alt,$total_func_g,0,0,"C",0);
$pdf->cell(25,$alt,db_formatar($venc_g,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($desc_g,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($venc_g - $desc_g,'f'),0,1,"R",0);
$pdf->Output();
//exit;
?>