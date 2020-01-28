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
$clrotulo->label('r01_regist');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELATÓRIO PARA VALE TRANSPORTE E REFEITÓRIO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select * from 
(
select rh01_regist as r01_regist,
       z01_nome,
       sum(provento) as provento,
       sum(desconto) as desconto,
       sum(provento)-sum(desconto) as total 
from 
     (  select r14_regist,
               r14_anousu,
	             r14_mesusu, 
	             round(sum(case when r14_pd = 1 then r14_valor else 0 end),2) as provento,
	             0 as desconto 
       from gerfsal 
       where r14_pd != 3 
         and r14_rubric not in ( select r09_rubric from basesr where r09_base = '$base1'
                                                                 and r09_anousu = $ano
                                                                 and r09_mesusu = $mes
                               )
			  and r14_pd = 1 
 			  and r14_anousu = $ano 
			  and r14_mesusu = $mes
				and r14_instit = ".db_getsession("DB_instit")."
			group by r14_regist,r14_anousu,r14_mesusu
			
			union
  		
  	  select r14_regist,
  	         r14_anousu,
		 	       r14_mesusu,
			       0,
			       round(sum(r14_valor),2)
       from gerfsal 
       where r14_rubric in ( select r09_rubric from basesr where r09_base = '$base2'
                                                                 and r09_anousu = $ano
                                                                 and r09_mesusu = $mes
                           )                                  
  	     and r14_anousu = $ano 
  	     and r14_mesusu = $mes
			   and r14_instit = ".db_getsession("DB_instit")."
	     group by r14_regist,r14_anousu,r14_mesusu
       ) as x
	     inner join rhpessoal    on rh01_regist = r14_regist 
       inner join rhpessoalmov on rh02_regist = rh01_regist
	                            and rh02_anousu = r14_anousu
	                            and rh02_mesusu = r14_mesusu
                              and rh02_regist = rh01_regist
			                        and rh02_instit = ".db_getsession("DB_instit")."
	     inner join cgm     on rh01_numcgm = z01_numcgm
group by rh01_regist,z01_nome) as xx
where total <= $valor
order by z01_nome
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem descontas de Creches no período de '.$mes.' / '.$ano);

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

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLr01_regist,1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",0);
   $func   += 1;
}
$pdf->ln(3);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func,"T",0,"L",0);

$pdf->Output();
   
?>