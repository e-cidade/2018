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
$clrotulo->label('r08_codigo');
$clrotulo->label('r08_descr');
$clrotulo->label('r08_calqua');
$clrotulo->label('r08_mesant');
$clrotulo->label('r08_pfixo');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CADASTRO DE BASES";
$head4 = "PERÍODO : ".$mes." / ".$ano;

if($ordem == 'a'){
  $xordem = " order by r08_descr ";
  $head6  = "Ordem Alfabética";
}else{
  $xordem = " order by r08_codigo ";
  $head6  = "Ordem Numérica";
}
$sql = "
        select *
	from bases 
	where r08_anousu = $ano 
	  and r08_mesusu = $mes and r08_instit = ".db_getsession("DB_instit")."
 
	$xordem
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
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
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLr08_codigo,1,0,"C",1);
      $pdf->cell(60,$alt,$RLr08_descr,1,0,"C",1);
      $pdf->cell(20,$alt,$RLr08_calqua,1,0,"C",1);
      $pdf->cell(20,$alt,$RLr08_mesant,1,0,"R",1);
      $pdf->cell(20,$alt,$RLr08_pfixo,1,1,"R",1);
//      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$r08_codigo,0,0,"C",0);
   $pdf->cell(60,$alt,$r08_descr,0,0,"L",0);
   $pdf->cell(20,$alt,$r08_calqua,0,0,"L",0);
   $pdf->cell(30,$alt,$r08_mesant,0,0,"L",0);
   $pdf->cell(30,$alt,$r08_pfixo,0,1,"L",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);

$pdf->Output();
   
?>