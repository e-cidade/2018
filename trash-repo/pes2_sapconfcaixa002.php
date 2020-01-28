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

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
//$ano = 2005;
//$mes = 8;
  
  if($db_rec != ''){
    $split_rec = split('-',$db_rec);
    $virg = '';
    $xreceitas = '';
    for($x = 0 ;$x < sizeof($split_rec);$x++){
       $xreceitas .= $virg."'".$split_rec[$x]."'";
       $virg = ", ";
    }
  }
  if($db_ded_rec != ''){
    $split_ded_rec = split('-',$db_ded_rec);
    $virg = '';
    $xded_rec = '';
    for($xx = 0 ;$xx < sizeof($split_ded_rec);$xx++){
       $xded_rec .= $virg."'".$split_ded_rec[$xx]."'";
       $virg = ", ";
    }
  }
  if($db_desp_ext != ''){
    $split_desp_ext = split('-',$db_desp_ext);
    $virg = '';
    $xdesp_ext = '';
    for($xxx = 0 ;$xxx < sizeof($split_desp_ext);$xxx++){
       $xdesp_ext .= $virg."'".$split_desp_ext[$xxx]."'";
       $virg = ", ";
    }
  }
$x_semest  = '';
$x_semest1 = '';
if($folha == 'r14'){
  $arquivo = 'gerfsal'; 
}elseif($folha == 'r35'){
  $arquivo = 'gerfs13';
}elseif($folha == 'r48'){
  $arquivo = 'gerfcom';
  if($semest > 0) {
    $x_semest = " and r48_semest = $semest ";
    $x_semest1 = " and rh40_sequencia = $semest ";
  }
}else{
  $arquivo = 'gerfadi'; 
}

$head3 = "RELATÓRIO DE CONFERÊNCIA MAPA";
$head5 = "PERÍODO : ".$mes." / ".$ano;



  $sql = "

  select recurso,
   empenho,
   des_ext,
   receita,
   ded_receitas,
   total,
   banco,
   total-banco as diferenca
  from
  (
  select recurso,
   empenho,
   des_ext,
   receita,
   ded_receitas,
   (empenho+des_ext)-(receita-ded_receitas) as total,
   banco
  from 
  (
  select recurso,
   round(sum(empenho),2) as empenho, 
   round(sum(des_ext),2) as des_ext, 
   round(sum(receita),2) as receita, 
   round(sum(ded_receitas),2) as ded_receitas, 
   round(sum(banco),2) as banco
  from 
  (
  select rh40_recurso as recurso, 
   provento - desconto as empenho,
   0 as des_ext,
   0 as receita, 
   0 as ded_receitas,
   0 as banco
  from
  (
  select sum(rh40_provento) as provento,
   rh40_recurso, 
   sum(rh40_desconto) as desconto
  from rhempfolha 
  where rh40_mesusu = $mes 
    and rh40_anousu = $ano
    and rh40_siglaarq = '$folha'
    and rh40_instit = ".db_getsession('DB_instit')." 
    $x_semest1
  group by rh40_recurso
  )as x

  union

  select recurso,
   0,
   round(sum(valor),2),
   0,
   0,
   0
  from 
  (
  select ".$folha."_rubric, 
   ".$folha."_valor as valor,
   rh25_recurso as recurso
  from ".$arquivo."
       inner join rhlota   on r70_codigo = to_number(".$folha."_lotac,'9999')
                          and r70_instit = ".$folha."_instit  
       inner join (select distinct rh25_codigo,rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
  where ".$folha."_anousu = $ano 
    and ".$folha."_mesusu = $mes
    and ".$folha."_instit = ".db_getsession("DB_instit")."
    $x_semest
    and ".$folha."_rubric in ($xdesp_ext)
  ) as x
  group by recurso

  union

  select recurso,
   0,
   0, 
   round(sum(valor),2),
   0,
   0
  from 
  (
  select ".$folha."_rubric, 
   ".$folha."_valor as valor,
   rh25_recurso as recurso
  from ".$arquivo." 
       inner join rhlota   on r70_codigo = to_number(".$folha."_lotac,'9999')
                          and r70_instit = ".$folha."_instit  
       inner join (select distinct rh25_codigo,rh25_recurso from rhlotavinc where rh25_anousu = $ano  ) as rhlotavinc on rh25_codigo = r70_codigo
  where ".$folha."_anousu = $ano 
    and ".$folha."_mesusu = $mes
    and ".$folha."_instit = ".db_getsession("DB_instit")."
    $x_semest
    and ".$folha."_rubric in ($xreceitas)
  ) as xx
  group by recurso

  union

  select recurso,
   0,
   0,
   0,
   round(sum(valor),2),
   0
  from 
  (
  select ".$folha."_rubric as r14_rubric, 
   ".$folha."_valor as valor,
   rh25_recurso as recurso
  from $arquivo
       inner join rhlota   on r70_codigo = to_number(".$folha."_lotac,'9999')
                          and r70_instit = ".$folha."_instit  
       inner join (select distinct rh25_codigo,rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
  where ".$folha."_anousu = $ano 
    and ".$folha."_mesusu = $mes
    and ".$folha."_instit = ".db_getsession("DB_instit")."
    $x_semest
  and ".$folha."_rubric in ($xded_rec)
) as x
group by recurso

union

  select rh25_recurso as recurso,
         0,
         0,
         0,
         0,
         round(sum( case 
                     when ".$folha."_pd = 1 then ".$folha."_valor 
                     else ".$folha."_valor * (-1) 
                    end 
                  ),2) as r38_liq  
  from $arquivo
       inner join rhlota   on r70_codigo = to_number(".$folha."_lotac,'9999')
                          and r70_instit = ".$folha."_instit  
       inner join (select distinct rh25_codigo,rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
  where ".$folha."_anousu = $ano 
    and ".$folha."_mesusu = $mes
    and ".$folha."_instit = ".db_getsession("DB_instit")."
    $x_semest
    and ".$folha."_pd  != 3
group by rh25_recurso

) as y
group by recurso

order by recurso
) as yy

) as xyxy
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$orgao = '';
$unidade = '';


$tempenho     = 0;
$tdes_ext     = 0;
$treceita     = 0;
$tded_receitas= 0;
$ttotal       = 0;
$tbanco       = 0;
$tdiferenca   = 0;

$pre          = 0;
$val_fgts     = 0;
$pat1         = 0;
$tot_func     = 0;
$fgts = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','B',8);
      $pdf->cell(15,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(20,$alt,'EMPENHO',1,0,"C",1);
      $pdf->cell(20,$alt,'DESP.EXTRA',1,0,"C",1);
      $pdf->cell(20,$alt,'RECEITA',1,0,"C",1);
      $pdf->cell(20,$alt,'DED.RECEITA',1,0,"C",1);
      $pdf->cell(20,$alt,'TOTAL',1,0,"C",1);
      $pdf->cell(20,$alt,'BANCO',1,0,"C",1);
      $pdf->cell(20,$alt,'DIFERENÇA',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 0)
     $pre = 1;
   else
     $pre = 0;
   $pat1 = $fgts / 100 * 8;
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$recurso,0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($des_ext,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($receita,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($ded_receitas,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($total,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($banco,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($diferenca,'f'),0,1,"R",$pre);
  
   $tempenho      += $empenho     ;
   $tdes_ext      += $des_ext     ;
   $treceita      += $receita     ;
   $tded_receitas += $ded_receitas;
   $ttotal        += $total       ;
   $tbanco        += $banco       ;
   $tdiferenca    += $diferenca   ;
}
   $pdf->setfont('arial','B',7);
   $pdf->cell(15,$alt,'',"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($tempenho,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($tdes_ext,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($treceita,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($tded_receitas,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($ttotal,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($tbanco,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($tdiferenca,'f'),"T",1,"R",0);

$pdf->Output();


?>