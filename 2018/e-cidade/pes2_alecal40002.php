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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CADASTRO DE $percentual% DO LIQUIDO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

// B039 marcada as seguintes Rubricas que não pode entrar no calculo dos 40% --> 'R928','0032','0097','R918','R919','R920','0110','0060' 
// São rubricas de provento

$sql_base = "select r09_rubric from basesr where r09_base   = 'B039'  
                                             and r09_anousu = ".db_anofolha()."
                                  					 and r09_mesusu = ".db_mesfolha();

$result_base = pg_exec($sql_base);

$numrows_base = pg_numrows($result_base);
$sel_base = "'";
for($i=0; $i<$numrows_base; $i++){
   db_fieldsmemory($result_base, $i);
     if($i > 0){
        $sel_base .= ",'"; 
     } 
     $sel_base .= $r09_rubric."'"; 
   
}

 $sql = "
select * from 
(
SELECT P.R14_REGIST AS REGIST, 
       Z01_NOME AS NOME, 
       P.PROV AS PROV, 
       D.DESCO AS DESCO, 
       (P.PROV - D.DESCO) AS LIQ, 
       ROUND(((P.PROV - D.DESCO)/100*$percentual),2) AS PERC, 
         CASE WHEN U.USMA >= ((P.PROV - D.DESCO)/100*$percentual)
              THEN '*'
              ELSE ' '
         END AS UA , 
         COALESCE(U.USMA,0) AS USMA, 
         CASE WHEN S.SIND >= ((P.PROV - D.DESCO)/100*$percentual)
              THEN '*'
              ELSE ' ' 
         END AS SA , 
         COALESCE(S.SIND,0) AS SIND 
FROM 
   (SELECT R14_REGIST, 
           ROUND(SUM(R14_VALOR),2) AS PROV
    FROM GERFSAL 
    WHERE R14_anousu=$ano AND 
          R14_mesusu=$mes AND
					R14_INSTIT = ".db_getsession("DB_instit")." AND
          R14_pd != 3 AND 
          R14_RUBRIC NOT IN ($sel_base) AND 
          R14_PD = 1 
    GROUP BY R14_REGIST) AS P 
    INNER JOIN RHPESSOALMOV 
          ON RH02_REGIST = P.R14_REGIST AND 
             RH02_anousu = $ano AND 
             RH02_mesusu = $mes AND
						 RH02_INSTIT = ".db_getsession("DB_instit")."
		INNER JOIN RHPESSOAL
		      ON RH01_REGIST = RH02_REGIST
    INNER JOIN CGM 
          ON Z01_NUMCGM = RH01_NUMCGM 
    LEFT OUTER JOIN 
   (SELECT R14_REGIST, 
           ROUND(SUM(R14_VALOR),2) AS DESCO 
    FROM GERFSAL 
    WHERE R14_anousu=$ano AND 
          R14_mesusu=$mes AND 
					R14_INSTIT = ".db_getsession("DB_instit")." AND
          R14_pd != 3 AND 
          R14_RUBRIC NOT IN ('0720','0561') AND 
          R14_PD = 2 
    GROUP BY R14_REGIST) AS D ON D.R14_REGIST = P.R14_REGIST 
    LEFT OUTER JOIN 
   (SELECT R14_REGIST, 
           ROUND(SUM(R14_VALOR),2) AS USMA 
    FROM GERFSAL 
    WHERE R14_anousu=$ano AND 
          R14_mesusu=$mes AND 
					R14_INSTIT = ".db_getsession("DB_instit")." AND
          R14_RUBRIC = ('0720') 
    GROUP BY R14_REGIST) AS U ON U.R14_REGIST = P.R14_REGIST 
    LEFT OUTER JOIN 
   (SELECT R14_REGIST, 
           ROUND(SUM(R14_VALOR),2) AS SIND 
    FROM GERFSAL 
    WHERE R14_anousu=$ano AND  
          R14_mesusu=$mes AND 
					R14_INSTIT = ".db_getsession("DB_instit")." AND
          R14_RUBRIC = ('0561') 
    GROUP BY R14_REGIST) AS S ON S.R14_REGIST = P.R14_REGIST 
ORDER BY NOME
) as xxxx
where ua = '*' or sa = '*';

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
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$alt,'DESCONTOS',1,0,"C",1);
      $pdf->cell(20,$alt,'LÍQUIDO',1,0,"C",1);
      $pdf->cell(20,$alt,$percentual.'%',1,0,"C",1);
      $pdf->cell(20,$alt,'USMA',1,0,"C",1);
      $pdf->cell(20,$alt,'SIND',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$regist,0,0,"C",0);
   $pdf->cell(60,$alt,$nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($prov,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($desco,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($liq,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($perc,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($usma,'f').$ua,0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($sind,'f').$sa,0,1,"R",0);
}
//$pdf->setfont('arial','b',8);
//$pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>