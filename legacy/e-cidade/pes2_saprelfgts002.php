<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$head3 = "RELATÓRIO DO FGTS";
$head5 = "PERÍODO : ".$mes." / ".$ano;

if($ordem == 'n'){
  $ordem = ' order by r01_regist ';
}else{
  $ordem = ' order by z01_nome ';
}

$sql = "

select r01_regist,
       z01_nome,
       r01_lotac,
       r01_padrao,
       round(sum(inss),2) as fgts
from 

(
select r14_regist as r01_regist,
       z01_nome,
       r14_lotac as r01_lotac,
       RH03_PADRAO as r01_padrao,
       case when r14_rubric = 'R991' then r14_valor else 0 end as inss
from gerfsal 
     inner join rhpessoalmov on rh02_anousu = r14_anousu 
                            and rh02_mesusu = r14_mesusu 
                            and rh02_regist = r14_regist
                            and rh02_instit = r14_instit
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm          on z01_numcgm  = rh01_numcgm 
     left join rhpespadrao   on rh03_seqpes = rh02_seqpes
where r14_anousu = $ano 
  and r14_mesusu = $mes
  and r14_instit = ".db_getsession("DB_instit")."
  and r14_rubric in ('R991')

union all

select r48_regist as r01_regist,
       z01_nome,
       r48_lotac as r01_lotac,
       RH03_PADRAO as r01_padrao,
       case when r48_rubric = 'R991' then r48_valor else 0 end as inss
from gerfcom
     inner join rhpessoalmov on rh02_anousu = r48_anousu 
                            and rh02_mesusu = r48_mesusu 
                            and rh02_regist = r48_regist
                            and rh02_instit = r48_instit
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm          on z01_numcgm  = rh01_numcgm 
     left join rhpespadrao   on rh03_seqpes = rh02_seqpes
where r48_anousu = $ano 
  and r48_mesusu = $mes
  and r48_instit = ".db_getsession("DB_instit")."
  and r48_rubric in ('R991')
						     
union all

select r20_regist as r01_regist,
       z01_nome,
       r20_lotac as r01_lotac,
       RH03_PADRAO as r01_padrao,
       case when r20_rubric = 'R991' then r20_valor else 0 end as inss
from gerfres
     inner join rhpessoalmov on rh02_anousu = r20_anousu 
                            and rh02_mesusu = r20_mesusu 
                            and rh02_regist = r20_regist
                            and rh02_instit = r20_instit
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm          on z01_numcgm  = rh01_numcgm 
     left join rhpespadrao   on rh03_seqpes = rh02_seqpes
where r20_anousu = $ano 
  and r20_mesusu = $mes
  and r20_instit = ".db_getsession("DB_instit")."
  and r20_rubric in ('R991')
						     
union all

select r35_regist as r01_regist,
       z01_nome,
       r35_lotac as r01_lotac,
       RH03_PADRAO as r01_padrao,
       case when r35_rubric = 'R991' then r35_valor else 0 end as inss
from gerfs13
     inner join rhpessoalmov on rh02_anousu = r35_anousu 
                            and rh02_mesusu = r35_mesusu 
                            and rh02_regist = r35_regist
                            and rh02_instit = r35_instit
     inner join rhpessoal    on rh01_regist = rh02_regist
     inner join cgm          on z01_numcgm  = rh01_numcgm 
     left join rhpespadrao   on rh03_seqpes = rh02_seqpes
where r35_anousu = $ano 
  and r35_mesusu = $mes
  and r35_instit = ".db_getsession("DB_instit")."
  and r35_rubric in ('R991')
						     
) as x
group by r01_regist,
         z01_nome,
         r01_lotac,
         r01_padrao
$ordem

       ";
//echo $sql ; exit;

$result = db_query($sql);
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

$pre          = 0;
$val_fgts     = 0;
$pat1         = 0;
$tot_func     = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','B',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'8% FGTS',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 0)
     $pre = 1;
   else
     $pre = 0;
   $pat1 = $fgts / 100 * 8;
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($pat1,'f'),0,1,"R",$pre);
   $val_fgts     += $pat1;
   $tot_func     += 1;
  
}
   $pdf->setfont('arial','B',8);
   $pdf->cell(75,$alt,'TOTAL :  '.$tot_func.'   FUNCIONÁRIOS',0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($val_fgts,'f'),0,1,"R",0);

$pdf->Output();
   
?>