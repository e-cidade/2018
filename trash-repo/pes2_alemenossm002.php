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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELAÇÃO DE FUNCIONÁRIOS COM BRUTO < QUE O MÍNIMO";
$head5 = "PERÍODO : ".$mes." / ".$ano;
$head5 = "MÍNIMO  : ".db_formatar($minimo,'f');

$sql = "
select * from
(
SELECT P.R14_REGIST AS REGIST, 
       Z01_NOME AS NOME,
       r14_lotac,
       P.PROV AS PROV 
FROM 
   (SELECT R14_REGIST, r14_lotac,
           ROUND(SUM(R14_VALOR),2) AS PROV 
    FROM GERFSAL 
    WHERE R14_ANOUSU= $ano AND 
          R14_MESUSU= $mes AND 
					R14_INSTIT= ".db_getsession("DB_instit")." AND
          R14_RUBRIC < 'R950' AND 
          R14_RUBRIC NOT IN ('R928','R918','R919','R920','0032','0097','0060','0184','0185','0290','0293') AND 
          R14_PD = 1 
    GROUP BY R14_REGIST, r14_lotac) AS P 
    INNER JOIN RHPESSOAL 
          ON RH01_REGIST = P.R14_REGIST
    INNER JOIN CGM 
          ON Z01_NUMCGM = Rh01_NUMCGM 
ORDER BY NOME 
) as x
where PROV < $minimo
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Cálculo no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$pre = 1;
$total = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$regist,0,0,"C",0);
   $pdf->cell(60,$alt,$nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($prov,'f'),0,1,"R",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>