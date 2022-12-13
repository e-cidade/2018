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
include("classes/db_rhlocaltrab_classe.php");
$clrhlocaltrab = new cl_rhlocaltrab;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($tipoarq == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14';
}elseif($tipoarq == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35';
}elseif($tipoarq == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48';
}


$result_local = $clrhlocaltrab->sql_record($clrhlocaltrab->sql_query_file($local,db_getsession('DB_instit'), "rh55_codigo, rh55_codigo||'-'||rh55_descr as rh55_descr", "rh55_descr" ));
db_fieldsmemory($result_local,0);

$head2 = $rh55_descr;
$head4 = "PERIODO : ".$mes." / ".$ano;
$head6 = "TODOS";

$where_banco = '';
if($xbanco == 'b' && ( $xconta == 'cc' || $xconta == 't' ) ){
  if($xconta == 'cc'){
    $head6 = "BANCO DO BRASIL - COM CONTA ";
    $where_banco = " and trim(rh44_codban) = '001' and rh02_fpagto = 3 ";
  }else{
    $head6 = "BANCO DO BRASIL - TODOS ";
    $where_banco = " and trim(rh44_codban) = '001' ";
  }
}elseif($xbanco == 'c' && ( $xconta == 'cc' || $xconta == 't' ) ){
  if($xconta == 'cc'){
    $head6 = "CAIXA ECONOMICA FEDERAL - COM CONTA ";
    $where_banco = " and trim(rh44_codban) = '104' and rh02_fpagto = 3 ";
  }else{
    $head6 = "CAIXA ECONOMICA FEDERAL - TODOS ";
    $where_banco = " and trim(rh44_codban) = '104' ";
  }
}elseif($xconta == 'sc'){
  $head6 = "SEM CONTA";
  $where_banco = " and rh02_fpagto <> 3 ";
}


$where_dentista = '';

$where_local = " and rh56_localtrab = $local";


$select_campos = "rh27_rubric,
                  rh27_descr,
                  rh01_regist,
                  z01_nome,
                  lpad(z01_cgccpf,11,'0') as z01_cgccpf,
                  rh37_descr,
                 ";
$group_ordem = "
                order by rh27_rubric, z01_nome
               ";

if($local == 1){
$select_campos = "rh27_rubric,
                  rh27_descr,
                  rh01_regist,
                  z01_nome,
                  lpad(z01_cgccpf,11,'0') as z01_cgccpf,
                  rh37_descr,
                  r70_estrut as quebra,
                  r70_descr  as descr_quebra,
                 ";
  $group_ordem = "
                  order by r70_estrut, rh27_rubric, z01_nome
                 ";
}elseif($local == 2){
$select_campos = "rh27_rubric,
                  rh27_descr,
                  rh01_regist,
                  z01_nome,
                  lpad(z01_cgccpf,11,'0') as z01_cgccpf,
                  rh37_descr,
                  o40_orgao as quebra,
                  o40_descr as descr_quebra,
                 ";
  $group_ordem = "
                  order by o40_orgao,rh27_rubric, z01_nome
                 ";
}elseif($local == 3){
$select_campos = "rh27_rubric,
                  rh27_descr,
                  rh01_regist,
                  z01_nome,
                  lpad(z01_cgccpf,11,'0') as z01_cgccpf,
                  rh37_descr,
                  rh01_clas1 as quebra,
                  case rh01_clas1
                       when 'A' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES E PESSOAL EM ATIVIDADE PEDAGOGICAS DO ENSINO FUNDAMENTAL - (FOLHA-A)'
                       when 'B' then 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA AREA ADMINISTRATIVA DAS ESCOLAS DO ENSINO FUNDAMENTAL - (FOLHA-B)'
                       when 'C' then 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA AREA ADMINISTRATIVA DAS CRECHES - (FOLHA-C)'
                       when 'D' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES DO ENSINO INFANTIL - (FOLHA-D)'
                       when 'E' then 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA UNIDADE ADMINISTRATIVA - (FOLHA-E 10%)'
                       when 'F' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES EM EDUCACAO DE JOVENS E ADULTOS (EJA) - (FOLHA-F)'
                       when 'G' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES DAS CRECHES - (FOLHA-G)'
                  end as descr_quebra,
                 ";
  $group_ordem = "
                  order by rh01_clas1, rh27_rubric, z01_nome
                 ";
}elseif($local == 9){
$select_campos = "rh27_rubric,
                  rh27_descr,
                  rh01_regist,
                  z01_nome,
                  lpad(z01_cgccpf,11,'0') as z01_cgccpf,
                  rh37_descr,
                  case when o40_orgao not in (8 , 9) 
                       then 1
                       else o40_orgao
                  end as quebra,
                  case o40_orgao 
                       when 8 then 'CARGOS EM COMISSAO DA EDUCACAO'
                       when 9 then 'CARGOS EM COMISSAO DA SAUDE'
                       else 'CARGOS EM COMISSAO DE OUTRAS SECRETARIAS'
                  end as descr_quebra,
                 ";
  $group_ordem = "
                  order by quebra, rh27_rubric, z01_nome
                 ";

}elseif($local == 5){
  if($dentista == 'D'){
    $where_dentista = " and rh01_funcao = 14 ";
    $head8 = "DENTISTAS";
  }elseif($dentista == 'O'){
    $where_dentista = " and rh01_funcao <> 14 ";
    $head8 = "OUTROS PROFISSIONAIS ";
  }else{
    $head8 = "TODOS ";
  }


}


$sql_basico = 
"
 select * 
 from ".$arquivo." 
      inner join rhrubricas     on rh27_rubric = ".$sigla."_rubric
      inner join rhpessoal      on ".$sigla."_regist  = rh01_regist 
      inner join cgm            on rh01_numcgm = z01_numcgm 
      inner join rhpessoalmov   on rh01_regist = rh02_regist 
                               and rh02_anousu = ".$sigla."_anousu 
                               and rh02_mesusu = ".$sigla."_mesusu 
      left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes 
                               and rh56_princ  = true  
      left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                               and rh55_instit = rh02_instit
      inner join rhfuncao       on rh37_funcao = rh01_funcao
      inner join rhlota         on r70_codigo  = rh02_lota 
      left  join rhlotaexe      on rh26_codigo = r70_codigo
                               and rh26_anousu = rh02_anousu
      left  join orcorgao       on o40_anousu  = rh26_anousu
                               and o40_orgao   = rh26_orgao
      inner join rhregime       on rh02_codreg = rh30_codreg 
      left  join rhpesbanco     on rh44_seqpes = rh02_seqpes 
      where ".$sigla."_anousu  = $ano  and 
            ".$sigla."_mesusu  = $mes  and 
            ".$sigla."_pd = 2
            $where_banco
            $where_local
            $where_dentista
";

$sql_certo = 
"
select  $select_campos
        ".$sigla."_quant as quant,
        ".$sigla."_valor as valor
 from ($sql_basico) as x
      $group_ordem 
";

//echo $sql_basico;exit;
$res_basico = pg_query($sql_certo);
//db_criatabela($res_basico);exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfont('arial','b',8);
$pdf->setfillcolor(235);
$troca_pag = 1;
$troca     = '0';
$alt       = 4;

$total_func1     = 0;
$total_quant1    = 0;
$total_valor1    = 0;

$total_func2     = 0;
$total_quant2    = 0;
$total_valor2    = 0;

$total_func3     = 0;
$total_quant3    = 0;
$total_valor3    = 0;
$primeiro        = false;

$pre = 0;
$rubrica = 0;

for($x = 0; $x < pg_numrows($res_basico);$x++){
   db_fieldsmemory($res_basico,$x);
     if($rubrica != $rh27_rubric && $troca_pag == 0 ){
       $pdf->setfont('arial','b',7);
       $pdf->cell(120,$alt,"TOTAL DA RUBRICA $rubrica --> ".$total_func2."  FUNCIONÁRIO".($total_func2>1?'S':''),1,0,"R",$pre);
       $pdf->cell(20,$alt,db_formatar($total_quant2,'f'),1,0,"R",$pre);
       $pdf->cell(20,$alt,db_formatar($total_valor2,'f'),1,1,"R",$pre);

       $total_func2     = 0;
       $total_quant2    = 0;
       $total_valor2    = 0;

       $rubrica = $rh27_rubric;
       $pdf->ln(4);
//       if(isset($quebra)){  
//         if(isset($quebra) && $troca == $quebra){
//           $pdf->cell(0,4,$rh27_rubric.' - '.$rh27_descr,0,1,"C",1);
//         }
//       }else{
//         $pdf->cell(0,4,$rh27_rubric.' - '.$rh27_descr,0,1,"C",1);
//       }
 
      // $total_func1     = 0;
      // $total_quant1    = 0;
      // $total_valor1    = 0;

     //  $troca           = $quebra;
       $troca_pag       = 1;
       // $pdf->ln(4);
//        $pdf->cell(60,$alt,$quebra." - ".$descr_quebra,0,1,"L",0);
     }
//       echo "<br>  troca --> $troca  quebra-> $quebra     descr_quebra --> $descr_quebra";
   if(isset($quebra)){  
     if($troca != $quebra){
       $pdf->setfont('arial','b',7);
       if($x > 0){
       $pdf->addpage();
         $pdf->cell(120,$alt,"TOTAL --> ",1,0,"R",$pre);
         $pdf->cell(20,$alt,db_formatar($total_quant1,'f'),1,0,"R",$pre);
         $pdf->cell(20,$alt,db_formatar($total_valor1,'f'),1,1,"R",$pre);
       }
       $total_func1     = 0;
       $total_quant1    = 0;
       $total_valor1    = 0;


       $troca           = $quebra;
       imprime_quebra($quebra, $descr_quebra);       
       $troca_pag       = 1;
//        $pdf->ln(4);
//        if($rubrica == $rh27_rubric){
//          $pdf->cell(60,$alt,$quebra." - ".$descr_quebra,0,1,"L",0);
//        }
     }
   }

//   if ($pdf->gety() > $pdf->h - 30 || $troca_pag != 0 || $rubrica != $rh27_rubric){
   if ($pdf->gety() > $pdf->h - 30 || $troca_pag != 0 ){


      $pdf->addpage();
      $pdf->setfont('arial','b',7);

      $pdf->cell(0,4,$rh27_rubric.' - '.$rh27_descr,0,1,"C",1);
      $pdf->ln(4);

      $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
      $pdf->cell(100,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(20,$alt,'QUANT.',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      if(isset($quebra)){  
        $pdf->cell(60,$alt,$quebra." - ".$descr_quebra,0,1,"L",0);
      }
      $rubrica = $rh27_rubric;
      $troca_pag = 0;
      $pre = 1;
   //   $pdf->ln(4);
   }
   $pre = 0;
   //if($pre == 1){
   //  $pre = 0;
   //}else{
   //  $pre = 1;
   //}
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(100,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($quant,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",$pre);

   $total_func1  += 1;
   $total_quant1 += $quant;
   $total_valor1 += $valor;

   $total_func2  += 1;
   $total_quant2 += $quant;
   $total_valor2 += $valor;

   $total_func3  += 1;
   $total_quant3 += $quant;
   $total_valor3 += $valor;
}
$pdf->setfont('arial','b',7);

//if(isset($quebra)){  
  $pdf->cell(120,$alt,"TOTAL DA RUBRICA $rubrica --> ".$total_func2."  FUNCIONÁRIO".($total_func2>1?'S':''),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($total_quant2,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($total_valor2,'f'),1,1,"R",$pre);
  $pdf->ln(4);
//}

//if(isset($quebra)){  
       $pdf->addpage();
  $pdf->cell(120,$alt,"TOTAL --> ",1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($total_quant1,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($total_valor1,'f'),1,1,"R",$pre);
  $pdf->ln(4);
//}

if(!isset($quebra)){  
  imprime_quebra();
}else{
  $pdf->addpage();

  $pdf->cell(120,$alt,"TOTAL DAS CONSIGNAÇÕES --> ".$total_func3."  FUNCIONÁRIO".($total_func3>1?'S':''),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($total_quant3,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($total_valor3,'f'),1,1,"R",$pre);
  $pdf->ln(4);
}
function imprime_quebra($xquebra = null, $xdescr_quebra = '' ){

  global $pdf, $alt, $total_func3, $total_func3, $pre , $total_quant3, $total_valor3, $arquivo , $sigla, $where_banco, $where_local,
         $ano, $mes, $rubric, $descr, $func1 , $quant1, $pd1, $valor1, $local, $primeiro , $troca_pag;

  $select_campos1= "";
  if($xquebra != null){
    if($local == 1){
      $where_quebra = "and r70_estrut = '$xquebra'";
    }elseif($local == 2){
      $where_quebra = "and o40_orgao = '$xquebra'";
    }elseif($local == 3){
      $where_quebra = "and trim(rh01_clas1) = '$xquebra'";
    }elseif($local == 9){
      $select_campos1= "
                        ,case when o40_orgao not in (8 , 9) 
                             then 1
                             else o40_orgao
                        end as quebra,
                        case o40_orgao 
                             when 8 then 'CARGOS EM COMISSAO DA EDUCACAO'
                             when 9 then 'CARGOS EM COMISSAO DA SAUDE'
                             else 'CARGOS EM COMISSAO DE OUTRAS SECRETARIAS'
                        end as descr_quebra
                 ";
      $where_quebra = "and quebra = '$xquebra'";
    }
  }else{
      $where_quebra = "";
  }

  $sql_basico = 
  "
   select * from 
   (
   select * $select_campos1 
   from ".$arquivo." 
        inner join rhrubricas     on rh27_rubric = ".$sigla."_rubric
        inner join rhpessoal      on ".$sigla."_regist  = rh01_regist 
        inner join cgm            on rh01_numcgm = z01_numcgm 
        inner join rhpessoalmov   on rh01_regist = rh02_regist 
                                 and rh02_anousu = ".$sigla."_anousu 
                                 and rh02_mesusu = ".$sigla."_mesusu 
        left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes 
                                 and rh56_princ  = true  
        left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                                 and rh55_instit = rh02_instit
        inner join rhfuncao       on rh37_funcao = rh01_funcao
        inner join rhlota         on r70_codigo  = rh02_lota 
        left  join rhlotaexe      on rh26_codigo = r70_codigo
                                 and rh26_anousu = rh02_anousu
        left  join orcorgao       on o40_anousu  = rh26_anousu
                                 and o40_orgao   = rh26_orgao
        inner join rhregime       on rh02_codreg = rh30_codreg 
        left  join rhpesbanco     on rh44_seqpes = rh02_seqpes 
        where ".$sigla."_anousu  = $ano  and 
              ".$sigla."_mesusu  = $mes  and 
              ".$sigla."_pd     != 3 
              $where_banco
              $where_local
     ) as we
        where 1 = 1 $where_quebra
  ";
  
  $sql_total = 
  "
  select rh27_rubric as rubric, rh27_descr as descr, ".$sigla."_pd as pd1, count(distinct rh01_regist) as func1 , sum( ".$sigla."_quant) as quant1, sum(".$sigla."_valor) as valor1
  from ($sql_basico) as x
  group by rh27_rubric, rh27_descr,  ".$sigla."_pd
  order by rh27_rubric
  ";
//  echo $sql_total;exit;
  $troca_pag1= 1;
  $total_rub_func1  = 0;
  $total_rub_prov1  = 0;
  $total_rub_desc1  = 0;
  $total_rub_quant1 = 0;
  
  
  $res_total = pg_query($sql_total);

  for($xy = 0; $xy < pg_numrows($res_total);$xy++){
     db_fieldsmemory($res_total,$xy);
     $pdf->setfont('arial','b',7);
     if ($pdf->gety() > $pdf->h - 30 || $troca_pag1!= 0 ){
        $pdf->addpage();
        $pdf->cell(0,6,$xquebra.' - '.$xdescr_quebra,0,1,"C",1);
        $pdf->cell(20,$alt,'RUBRICA',1,0,"C",1);
        $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
        $pdf->cell(20,$alt,'FUNC.',1,0,"C",1);
        $pdf->cell(20,$alt,'QUANT.',1,0,"C",1);
        $pdf->cell(20,$alt,'PROVENTO',1,0,"C",1);
        $pdf->cell(20,$alt,'DESCONTO',1,1,"C",1);
        $troca_pag1= 0;
        $pre = 0;
     //   $pdf->ln(4);
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$rubric,0,0,"C",$pre);
     $pdf->cell(60,$alt,$descr,0,0,"L",$pre);
     $pdf->cell(20,$alt,$func1,0,0,"C",$pre);
     $pdf->cell(20,$alt,db_formatar($quant1,'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar( ($pd1 == 1?$valor1:0),'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar( ($pd1 == 2?$valor1:0),'f'),0,1,"R",$pre);

     $total_rub_func1  += $func1;
     $total_rub_prov1  += ($pd1 == 1?$valor1:0);
     $total_rub_desc1  += ($pd1 == 2?$valor1:0);
     $total_rub_quant1 += $quant1;

  }
  $pdf->ln(2);
  $pdf->setfont('arial','B',8);
  $pdf->cell(100,$alt,'TOTAL','T',0,"R",0);
  $pdf->cell(20,$alt,'','T',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rub_prov1 ,'f'),'T',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rub_desc1 ,'f'),'T',1,"R",0);
  $pdf->cell(100,$alt,'LIQUIDO','T',0,"R",0);
  $pdf->cell(20,$alt,'','T',0,"R",0);
  $pdf->cell(20,$alt,'','T',0,"R",0);
  $pdf->cell(20,$alt,db_formatar( $total_rub_prov1 - $total_rub_desc1 ,'f'),'T',1,"R",0);
  if($primeiro == true){
    $troca_pag = 1 ;
  }

}
///echo "<br><br>   parou";exit;
$pdf->Output();
?>