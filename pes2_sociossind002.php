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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "SÓCIOS DO SINDICATO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select r53_regist,
       z01_nome, 
       sum(r53_valor) as base 
from gerffx 
     inner join pessoal	 on r01_regist = r53_regist 
                       	and r01_anousu = r53_anousu 
		                  	and r01_mesusu = r53_mesusu 
												and r01_instit = r53_instit
     inner join cgm on r01_numcgm = z01_numcgm 
     inner join rhrubricas on rh27_rubric = r53_rubric 
		                      and rh27_instit = r53_instit
			and rh27_tipo = '1' 
where r53_anousu = $ano 
  and r53_mesusu = $mes 
	and r53_instit = ".db_getsession("DB_instit")."
  and r01_recis is null 
  and r53_regist in (select r90_regist 
                     from pontofx 
		     where r90_rubric = '1600') 
group by r53_regist,
         z01_nome 
order by z01_nome;
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
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
      $pdf->cell(20,$alt,$RLr01_regist,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(25,$alt,'BASE',1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$r53_regist,0,0,"C",0);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(25,$alt,db_formatar($base,'f'),0,1,"R",0);
   $total += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(115,$alt,'TOTAL '.$total,"T",1,"L",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>