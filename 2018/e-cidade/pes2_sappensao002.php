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

$head3 = "RELATÓRIO DE PENSÃO JUDICIAL";
$head5 = "PERÍODO : ".$mes." / ".$ano;
$sql = "

select distinct regist,
       substr(nome_func,1,35) as nome_func,
       substr(nome_pens,1,35) as nome_pens,
       valor,
       valorcom,
       valor13 ,
       valorres,
       r70_estrut,recurso
from (
select rh02_regist as regist,
       a.z01_nome as nome_func,
       b.z01_nome as nome_pens,
       r52_valor+r52_valfer  as valor,
       r52_valcom as valorcom,
       r52_val13  as valor13 ,
       r52_valres as valorres,
       r70_estrut,
       o15_descr as recurso
from pensao
     inner join rhpessoalmov  on rh02_regist = r52_regist
                        and rh02_anousu = r52_anousu
                        and rh02_mesusu = r52_mesusu
                        and rh02_instit = ".db_getsession("DB_instit")."
     inner join rhpessoal on rh01_regist = rh02_regist
     inner join cgm a    on rh01_numcgm = a.z01_numcgm
     inner join cgm b    on r52_numcgm = b.z01_numcgm
     inner join rhlota   on r70_codigo = rh02_lota
                                    and r70_instit = rh02_instit
     left join (select distinct rh25_codigo,rh25_projativ, rh25_recurso from rhlotavinc where rh25_anousu = $ano ) as rhlotavinc on rh25_codigo = r70_codigo
     left join orctiporec on rh25_recurso = o15_codigo
where r52_anousu = $ano
  and r52_mesusu = $mes
  and (r52_valor+r52_valfer  > 0
  or  r52_valcom > 0
  or  r52_val13  > 0
  or  r52_valres > 0)
order by recurso,a.z01_nome,b.z01_nome ) as foo
order by recurso,nome_func,nome_pens
       ";
//echo $sql ; 

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe movimentação no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$func   = 0;
$func_c = 0;
$tot_1  = 0;
$tot_2  = 0;
$tot_3  = 0;
$total1 = 0;
$total2 = 0;
$total3 = 0;
$total4 = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 6;

////// TOTAL POR RECURSO

/*
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',9);
      $pdf->cell(60,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $creche = '';
      $troca = 0;
   }
   $pdf->setfont('arial','',9);
   $pdf->cell(60,$alt,$recurso,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
   $func   += 1;
   $func_c += 1;
   $tot_c  += $valor;
   $total  += $valor;
}
$pdf->ln(3);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",0);

*/
///// POR FUNCIONARIO

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME PENSIONISTA',1,0,"C",1);
      $pdf->cell(20,$alt,'SALÁRIO',1,0,"R",1);
      $pdf->cell(20,$alt,'COMPL.',1,0,"R",1);
      $pdf->cell(20,$alt,'13o. SAL.',1,0,"C",1);
      $pdf->cell(20,$alt,'Rescisão',1,1,"C",1);
      $quebra = '';
      $troca = 0;
   }
   if ( $quebra != $recurso ){
      if($quebra != ''){
        $pdf->ln(1);
        $pdf->cell(135,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
        $pdf->cell(20,$alt,db_formatar($tot_1,'f'),"T",0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_2,'f'),"T",0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_3,'f'),"T",0,"R",0);
        $pdf->cell(20,$alt,db_formatar($tot_4,'f'),"T",1,"R",0);
	$func_c = 0;
	$tot_1  = 0;
	$tot_2  = 0;
	$tot_3  = 0;
	$tot_4  = 0;
      }
      $pdf->setfont('arial','b',9);
      $pdf->ln(4);
      $pdf->cell(50,$alt,$recurso,0,1,"L",1);
      $quebra = $recurso;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$regist,0,0,"C",0);
   $pdf->cell(60,$alt,$nome_func,0,0,"L",0);
   $pdf->cell(60,$alt,$nome_pens,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($valorcom,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($valor13,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($valorres,'f'),0,1,"R",0);
   $func   += 1;
   $func_c += 1;
   $tot_1  += $valor;
   $tot_2  += $valorcom;
   $tot_3  += $valor13;
   $tot_4  += $valorres;
   $total1 += $valor;
   $total2 += $valorcom;
   $total3 += $valor13;
   $total4 += $valorres;
}
$pdf->ln(1);
$pdf->cell(135,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_1,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_2,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_3,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_4,'f'),"T",1,"R",0);

$pdf->ln(3);
$pdf->cell(135,$alt,'Total da Geral  :  '.$func,"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($total1,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($total2,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($total3,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($total4,'f'),"T",1,"R",0);

$pdf->Output();
   
?>