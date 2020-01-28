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
$clrotulo->label('r01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_funcao');
$clrotulo->label('r37_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$head3 = "RELAT�RIO DE HORAS EXTRAS";
$head5 = "PER�ODO : ".$mes." / ".$ano;

$sql = "
select r01_regist,
       z01_nome,
       r70_estrut,
       case substr(r70_estrut,2,2)
            when '01' then 'GABINETE'
	    when '02' then 'SECRETARIA DE ADMINISTRA��O'
	    when '03' then 'SECRETARIA DA FAZENDA'
	    when '04' then 'SECRETARIA DE PLANEJAMENTO URBANO'
	    when '05' then 'SECRETARIA DE OBRAS'
	    when '06' then 'SECRETARIA DE EDUCA��O E DESPORTO'
	    when '07' then 'SECRETARIA DA SA�DE'
	    when '08' then 'SECRETARIA DA ASSIT�NCIA SOCIAL'
	    when '09' then 'SECRETARIA DE TRANSPORTE E TR�NSITO'
	    when '10' then 'SECRETARIA DA IND�STRIA E COM�RCIO'
	    when '11' then 'SECRETARIA DA AGRICULTURA'
	    when '12' then 'SECRETARIA DA CULTURA E TURISMO'
       end as sec,
       round(sum(v50),2) as v50,
       round(sum(q50),2) as q50,
       round(sum(v100),2) as v100,
       round(sum(q100),2) as q100
from
(
select r01_regist,z01_nome,r01_lotac,r70_estrut,
       case when r14_rubric = '0010' then r14_quant else 0 end as q50, 
       case when r14_rubric = '0010' then r14_valor else 0 end as v50, 
       case when r14_rubric = '0011' then r14_quant else 0 end as q100, 
       case when r14_rubric = '0011' then r14_valor else 0 end as v100 
from gerfsal 
     inner join pessoal on r01_regist = r14_regist
                       and r01_anousu = r14_anousu
            		       and r01_mesusu = r14_mesusu
											 and r01_instit = r14_instit
     inner join cgm     on r01_numcgm = z01_numcgm
     inner join rhlota  on to_number(r01_lotac,'9999') = r70_codigo
		                   and r70_instit = r14_instit
where r14_rubric in ('0010','0011') 
  and r14_anousu = $ano 
  and r14_mesusu = $mes
	and r14_instit = ".db_getsession("DB_instit")."
) as x

group by r01_regist,
         z01_nome,
	 sec,
	 r70_estrut
order by sec,z01_nome
";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem horas extras no per�odo de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$func   = 0;
$func_c = 0;
$tot_c  = 0;
$total  = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total_q50   = 0;
$total_v50   = 0;
$total_q100  = 0;
$total_v100  = 0;

$totalg_q50  = 0;
$totalg_v50  = 0;
$totalg_q100 = 0;
$totalg_v100 = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      if($funcion == 't'){
        $pdf->cell(15,$alt,'MATR�C.',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      }else{
        $pdf->cell(75,$alt,'SECRETARIA',1,0,"C",1);
      }
      $pdf->cell(20,$alt,'Quant.50%',1,0,"C",1);
      $pdf->cell(20,$alt,'Valor 50%',1,0,"C",1);
      $pdf->cell(20,$alt,'Quant.100%',1,0,"C",1);
      $pdf->cell(20,$alt,'Valor 100%',1,0,"C",1);
      $pdf->cell(20,$alt,'Total Quant.',1,0,"C",1);
      $pdf->cell(20,$alt,'Total Valor',1,1,"C",1);
      $funcao = '';
      $troca = 0;
   }
   if ( $funcao != $sec ){
      if($funcao != ''){
        $pdf->ln(1);
        $pdf->cell(75,$alt,'Total da Secretaria :  '.$func_c.'   funcion�rios',0,0,"L",0);
        $pdf->cell(20,$alt,db_formatar($total_q50,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($total_v50,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($total_q100,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($total_v100,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($total_q50 + $total_q100,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($total_v50 + $total_v100,'f'),0,1,"R",0);
	$func_c = 0;
	$tot_c  = 0;
        $total_q50   = 0;
        $total_v50   = 0;
        $total_q100  = 0;
        $total_v100  = 0;
      }
      $pdf->setfont('arial','b',9);
      $pdf->ln(10);
      $pdf->cell(100,$alt,$sec,0,1,"L",1);
      $funcao = $sec;
   }
   if($funcion == 't'){
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($q50,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($v50,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($q100,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($v100,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($q50 + $q100,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,db_formatar($v50 + $v100,'f'),0,1,"R",0);
   }
   $func   += 1;
   $func_c += 1;
   $total_q50   += $q50;
   $total_v50   += $v50;
   $total_q100  += $q100;
   $total_v100  += $v100;

   $totalg_q50  += $q50;
   $totalg_v50  += $v50;
   $totalg_q100 += $q100;
   $totalg_v100 += $v100;
}
$pdf->ln(1);
$pdf->cell(75,$alt,'Total da Secretaria  :  '.$func_c.'   funcion�rios',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_q50,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_v50,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_q100,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_v100,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_q50 + $total_q100,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_v50 + $total_v100,'f'),0,1,"R",0);

$pdf->ln(5);
$pdf->cell(75,$alt,'Total da Geral  :  '.$func.'   funcion�rios',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($totalg_q50,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totalg_v50,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totalg_q100,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totalg_v100,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totalg_q50 + $totalg_q100,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($totalg_v50 + $totalg_v100,'f'),0,1,"R",0);

$pdf->Output();
   
?>