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

//$ano = 2007;
//$mes = 5;


$head2 = "PERIODO : ".$mes." / ".$ano;
$head3 = "RELATÓRIO SECRETARIA DA EDUCAÇÃO";


$sql = "
select *,
        CASE R70_ESTRUT
            WHEN '0613'   THEN 'FUNDEB PROFESSORES'
            WHEN '0602'   THEN 'ORCAMENTO PROPRIO SERVIDORES'
            WHEN '0611'   THEN 'MDE FUNDAMENTAL SERVIDORES'
            WHEN '0605'   THEN 'MDE INFANTIL'
            WHEN '0610'   THEN 'ORCAMENTO PROPRIO PROFESSORES'
            ELSE 'ADMINISTRACAO'
        END AS secretaria
from
(
SELECT R14_REGIST,Z01_NOME,
       R70_ESTRUT,
        SUM(CASE WHEN R14_PD = 1 THEN R14_VALOR ELSE 0 END) AS PROVENTO,
        SUM(CASE WHEN R14_PD = 2 THEN R14_VALOR ELSE 0 END) AS DESCONTO
FROM gerfsal
     INNER JOIN RHPESSOALMOV ON RH02_ANOUSU = r14_anousu
                            AND RH02_MESUSU = r14_mesusu
                            AND RH02_REGIST = R14_REGIST
                            AND RH02_INSTIT = R14_INSTIT
     INNER JOIN RHPESSOAL    ON RH02_REGIST = RH01_REGIST
     INNER JOIN CGM          ON RH01_NUMCGM = Z01_NUMCGM
     INNER JOIN RHLOTA       ON R70_CODIGO  = RH02_LOTA
                            AND R70_INSTIT  = RH02_INSTIT
     LEFT JOIN  RHLOTAEXE    ON R70_CODIGO  = RH26_CODIGO
                            AND RH26_ANOUSU = RH02_ANOUSU
     LEFT  JOIN RHREGIME     ON RH02_CODREG = RH30_CODREG
                            AND RH02_INSTIT = RH30_INSTIT
WHERE RH26_ORGAO = 6
  and r14_anousu = $ano
  and r14_mesusu = $mes
  and r14_instit = 1
  and r14_pd != 3
GROUP BY R14_REGIST,Z01_NOME,r70_estrut
) as x
ORDER BY SECRETARIA,
         z01_nome
"; 

//echo $sql;exit;
$result = pg_exec($sql);
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$xsec = '';
$pdf->setfillcolor(235);
$tot_prov  = 0;
$tot_desc  = 0;
$tot_provg = 0;
$tot_descg = 0;
$total     = 0;
$totalg    = 0;



for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if($xsec != $secretaria){
	 //echo "<br> xsec --> $xsec   tipo --> $tipo ";
 //    $pdf->addpage("L");
 //    $pdf->setfont('arial','b',8);
 //    $pdf->cell(15,$alt,$tipo,0,1,"L",0);
     $troca = 1;
     $xsec = $secretaria;
     if($x != 0 ){
       $pdf->setfont('arial','b',8);
       $pdf->cell(85,$alt,'TOTAL DE FUNCIONARIOS  :  '.$total,"T",0,"L",0);
       $pdf->cell(25,$alt,db_formatar($tot_prov,'f'),"T",0,"R",0);
       $pdf->cell(25,$alt,db_formatar($tot_desc,'f'),"T",0,"R",0);
       $pdf->cell(25,$alt,db_formatar($tot_prov - $tot_desc,'f'),"T",1,"R",0);
       $total = 0;
       $tot_prov  = 0;
       $tot_desc  = 0;
     }
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(70,$alt,'NOME',1,0,"C",1);
      $pdf->cell(25,$alt,'PROVENTOS',1,0,"C",1);
      $pdf->cell(25,$alt,'DESCONTOS',1,0,"C",1);
      $pdf->cell(25,$alt,'LÍQUIDO',1,1,"C",1);
      $pdf->ln(3);
      $pdf->cell(15,$alt,$secretaria,0,1,"L",0);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r14_regist,0,0,"C",$pre);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($provento,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($desconto,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($provento - $desconto,'f'),0,1,"R",$pre);
   $total++;
   $totalg++;
   $tot_prov  += $provento;	
   $tot_desc  += $desconto;
   $tot_provg += $provento;	
   $tot_descg += $desconto;
}
$pdf->setfont('arial','b',8);
$pdf->setfont('arial','b',8);
$pdf->cell(85,$alt,'TOTAL DE FUNCIONARIOS  :  '.$total,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_prov,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_desc,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_prov - $tot_desc,'f'),"T",1,"R",0);
$pdf->ln(2);
$pdf->cell(85,$alt,'TOTAL GERAL  :  '.$totalg,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_provg,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_descg,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_provg - $tot_descg,'f'),"T",1,"R",0);
$pdf->Output();
?>