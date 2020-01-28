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

$where = '';
$head6 = 'Todos';
if($ativos == 'i'){
  $where = " and rh30_vinculo = 'I'";
  $head6 = 'Inativos';
}elseif($ativos == 'p'){
  $where = " and rh30_vinculo = 'P'";
  $head6 = 'Pensionistas';
}elseif($ativos == 'a'){
  $where = " and rh30_vinculo = 'A' and rh02_tbprev = 2 ";
  $head6 = 'Ativos';

}

$head2 = "RELATÓRIO PARA O CALCULO ATUARIAL";
$head4 = "ANO / MÊS : ".$ano." / ".db_formatar($mes,'s','0',2,'e');

$sql = " select 
                rh01_regist,
                z01_nome,
                rh37_descr,
			          rh01_sexo, 
			          rh01_nasc,
                base,
		inapen,
			          rh01_admiss
       
from rhpessoal
     inner join cgm          on z01_numcgm  = rh01_numcgm
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $ano
			                      and rh02_mesusu = $mes
                            and rh01_instit = ".db_getsession('DB_instit')."
     left join rhpesrescisao on rh05_seqpes = rh02_seqpes
     inner join rhlota       on r70_codigo  = rh02_lota
                            and r70_instit  = rh02_instit
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg
                            and rh30_instit = rh02_instit
     left join (select r14_regist,
                        sum(case when r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base,
				      sum(case when r14_rubric in ('0800','0801', '0810') then r14_valor else 0 end ) as inapen
                 from gerfsal 
		             where r14_anousu = $ano 
		               and r14_mesusu = $mes
                   and r14_instit = ".db_getsession('DB_instit')."
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh05_seqpes is null $where
order by z01_nome

  ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);exit;
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
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(10,$alt,'SEXO',1,0,"C",1);
      $pdf->cell(60,$alt,'CARGO',1,0,"C",1);
      $pdf->cell(20,$alt,'DT.ADM.',1,0,"C",1);
      $pdf->cell(20,$alt,'DT.NASC',1,0,"C",1);
      $pdf->cell(20,$alt,'DT.CONJ',1,0,"C",1);
      $pdf->cell(20,$alt,'DT.FILHO',1,0,"C",1);
      $pdf->cell(20,$alt,'BASE',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'dd/mm/YYYY') as dtconj
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $rh01_regist 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      db_fieldsmemory($res1,0);
    }else{
      $dtconj = '';
    }
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'dd/mm/YYYY') as cacula
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_regist = $rh01_regist 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      db_fieldsmemory($res3,0);
    }else{
      $cacula = '';
    }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(10,$alt,$rh01_sexo,0,0,"C",$pre);
   $pdf->cell(60,$alt,$rh37_descr,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($rh01_admiss,'d'),0,0,"C",$pre);
   $pdf->cell(20,$alt,db_formatar($rh01_nasc,'d'),0,0,"C",$pre);
   $pdf->cell(20,$alt,$dtconj,0,0,"C",$pre);
   $pdf->cell(20,$alt,$cacula,0,0,"C",$pre);
   $pdf->cell(20,$alt,db_formatar(($base == 0?$inapen:$base),'f'),0,1,"R",$pre);
   $total += 1;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>