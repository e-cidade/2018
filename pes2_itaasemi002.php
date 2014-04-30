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

$head3 = "RELATÓRIO ASEMI";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = 
       "select r10_regist,
              z01_nome,
              round(r10_valor,2) as ponto,
              round(x.r14_valor,2) as calculo
       from pontofs
            left outer join (select r14_regist,
                                    r14_valor
                             from gerfsal
                             where r14_anousu=$ano
                               and r14_mesusu=$mes
				                       and r14_instit = ".db_getsession("DB_instit")."
                               and r14_rubric='0053'
                            ) as x on r10_regist=x.r14_regist
            inner join rhpessoal on rh01_regist = r14_regist
	    inner join cgm       on rh01_numcgm = z01_numcgm 
       where r10_anousu = $ano
         and r10_mesusu = $mes
				 and r10_instit = ".db_getsession("DB_instit")."
         and r10_rubric='0053'
       order by z01_nome
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
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca      = 1;
$alt        = 4;
$total_func = 0;
$total_desc = 0;
$total_calc = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'CÓDIGO',1,0,"C",1);
      $pdf->cell(70,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(30,$alt,'LANÇADO',1,0,"C",1);
      $pdf->cell(30,$alt,'DESCONTADO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r10_regist,0,0,"C",$pre);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(30,$alt,db_formatar($ponto,'f'),0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($calculo,'f'),0,1,"R",$pre);
   $total_func += 1;
   $total_desc += $ponto;
   $total_calc += $calculo;
}
$pdf->setfont('arial','b',8);
$pdf->cell(85,$alt,'TOTAL GERAL  :  '.$total_func.'   FUNCIONÁRIOS',"T",0,"C",0);
$pdf->cell(30,$alt,db_formatar($total_desc,'f'),"T",0,"R",0);
$pdf->cell(30,$alt,db_formatar($total_calc,'f'),"T",1,"R",0);

$pdf->Output();
   
?>