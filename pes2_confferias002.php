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
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$mes = db_mesfolha();
$ano = db_anofolha();

$where = '';

$where_vinculo = "";
if(isset($atinpen)){
  if($atinpen == "a"){
    $where_vinculo = " and rh30_vinculo = 'A' ";
  }else if($atinpen == "i"){
    $where_vinculo = " and rh30_vinculo = 'I' ";
  }else if($atinpen == "p"){
    $where_vinculo = " and rh30_vinculo = 'P' ";
  }else if($atinpen == "ip"){
    $where_vinculo = " and (rh30_vinculo = 'I' or rh30_vinculo = 'P') ";
  }
}

if($reg != 0){
  $where = ' and rh30_regime = '.$reg;
}

$head3 = "CONFERÊNCIA DE FÉRIAS";
//$head5 = "PERÍODO : ".$mes." / ".$ano;
$inner_join = "";
if ($tipo == "o"){
  "ori=&orf=  for=2,4";
  if(isset($selorg) && $selorg != "") {
	   $where .= " and o40_orgao in ($selorg) ";
     $head7 = "ORGÃOS : $selorg";
  }elseif((isset($orgaoi) && $orgaoi != "" ) && (isset($orgaof) && $orgaof != "")){
	   $where .= " and o40_orgao between $orgaoi and $orgaof ";
     $head7 = "ORGÃOS : $orgaoi A $orgaof";
	}else if(isset($orgaoi) && $orgaoi != ""){
	   $where .= " and o40_orgao >= $orgaoi ";
     $head7 = "ORGÃOS : $orgaoi A 9999";
	}else if(isset($orf) && $orf != ""){
	   $where .= " and o40_orgao <= $orgaof ";
     $head7 = "ORGÃOS : 0 A $orgaof";
	}else{
     $head7 = "ORGÃOS : 0  A 9999";
	}
  $inner_join =  " inner join rhlota     on r70_codigo  = rh02_lota
									                      and r70_instit  = rh02_instit
			             left join  rhlotaexe  on rh26_codigo = r70_codigo 
									                      and rh26_anousu = $ano
		               left join  orcorgao   on o40_orgao   = rh26_orgao 
					                              and o40_anousu  = $ano
			                                  and o40_instit  = rh02_instit "; 
}elseif ($tipo == "l"){
  "lti=&ltf=   flt=0101,0102";
  if(isset($selreg) && $selreg != "") {
	   $where .= " and r70_estrut in ('".str_replace(",","','",$selreg)."') ";
     $head7 = "LOTAÇÃO : $selreg";
  }elseif((isset($lotai) && $lotai != "" ) && (isset($lotaf) && $lotaf != "")){
	   $where .= " and r70_estrut between '$lotai' and '$lotaf' ";
     $head7 = "LOTAÇÃO : ".$lotai." A ".$lotaf;
	}else if(isset($lotai) && $lotai != ""){
	   $where .= " and r70_estrut >= '$lotai' ";
     $head7 = "LOTAÇÃO : $lotai A 9999";
	}else if(isset($lotaf) && $lotaf != ""){
	   $where .= " and r70_estrut <= '$lotaf' ";
     $head7 = "LOTAÇÃO : 0  A $ltf";
	}else{
     $head7 = "LOTAÇÃO : 0  A 9999";
  }
  $inner_join =  " inner join rhlota on r70_codigo = rh02_lota
						                        and r70_instit = rh02_instit";
}elseif ($tipo == "t"){
  "lci=&lcf=   flc=13004,13006 ";
  if(isset($selloc) && $selloc != "" ) {
	   $where .= " and rh55_estrut in ('".str_replace(",","','",$selloc)."') ";
     $head7 = "LOCAL TRAB. : $selloc";
  }elseif((isset($locali) && $locali != "" ) && (isset($localf) && $localf != "")){
	   $where .= " and rh55_estrut between '$locali' and '$localf' ";
     $head7 = "LOCAL TRAB. : $locali A $localf";
	 }else if(isset($locali) && $locali != ""){
	   $where .= " and rh55_estrut >= '$locali' ";
     $head7 = "LOCAL TRAB. : $locali A 0";
	 }else if(isset($localf) && $localf != ""){
	   $where .= " and rh55_estrut <= '$localf' ";
     $head7 = "LOCAL TRAB. : 0 A $localf";
	}else{
     $head7 = "LOCAL TRAB. : 0  A 9999";
	 }
  $inner_join = "  inner join  rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
			                                       and rh56_princ = 't'
                   inner join rhlocaltrab     on rh55_codigo = rh56_localtrab
		                                         and rh55_instit = rh02_instit "; 
}
$where_afastados = "";
if($afastados == "n"){

$where_afastados = " and rh02_regist not in(select r45_regist from afasta where r45_anousu = $ano 
                                                                     and r45_mesusu = $mes 
                                                                     and r45_regist = rh01_regist 
                                                                     and (   r45_regist is null 
                                                                          or r45_regist is not null 
                                                                          and (r45_dtreto is null or r45_dtreto > '".$ano."-".$mes."-01')
                                                                         ))";
}
$sql = "
select *,dias/30 as dias_fer from 
(
select z01_nome ,
       rh01_regist as r01_regist,
       rh01_numcgm as r01_numcgm,
       rh01_admiss as r01_admiss,
       r30_regist,
       r30_perai,
       r30_peraf,
       case when r30_perai is null then rh01_admiss else r30_peraf end as dataini,
       '$data' - (case when r30_peraf is null then rh01_admiss else r30_peraf end) as dias
 from rhpessoalmov 
 inner join rhpessoal    on rh01_regist = rh02_regist
 left outer join cgm     on z01_numcgm = rh01_numcgm 
 left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg
                        and rh30_instit = rhpessoalmov.rh02_instit 
 left join rhpesrescisao on rh05_seqpes = rhpessoalmov.rh02_seqpes
 $inner_join
 left outer join 
   ( select distinct on (r30_regist ) r30_regist,r30_perai,r30_peraf 
     from cadferia 
     where r30_anousu= $ano
       and r30_mesusu= $mes
       and (r30_ndias = r30_dias1 + r30_dias2 + r30_abono)
     order by r30_regist, r30_perai desc ) 
   as cadf on r30_regist = rh01_regist 
 where rh02_anousu = $ano
   and rh02_mesusu = $mes
	 and rh02_instit = ".db_getsession("DB_instit")."
   and rh05_recis is null 
   and rh30_vinculo = 'A'
   $where  
   $where_afastados
 ) as xx 
 where dias > $dias
order by z01_nome
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
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(20,$alt,'ADMISSÃO',1,0,"C",1);
      $pdf->cell(40,$alt,'ULT.PERÍODO GOZADO',1,0,"C",1);
      $pdf->cell(20,$alt,'DIAS',1,0,"C",1);
      $pdf->cell(20,$alt,'MESES',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($r01_admiss,"d"),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($r30_perai,"d"),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($r30_peraf,"d"),0,0,"C",0);
   $pdf->cell(20,$alt,$dias,0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($dias / 30,'f'),0,1,"C",0);
   $total = $total + 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'TOTAL DE FUNCIONÁRIOS --> ',"T",0,"C",0);
$pdf->cell(05,$alt,db_formatar($total,'f'),"T",0,"C",0);
$pdf->cell(90,$alt,'',"T",1,"R",0);

$pdf->Output();
   
?>