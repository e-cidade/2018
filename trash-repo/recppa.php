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
$clrotulo->label('r37_funcao');
$clrotulo->label('r37_descr');
$clrotulo->label('r37_vagas');
$clrotulo->label('r37_cbo');
$clrotulo->label('r37_lei');
$clrotulo->label('r37_class');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CADASTRO DE RECEITAS - PPA";
$head4 = "PERÍODO : ".$mes." / ".$ano;

if($ordem == 'a'){
  $xordem = " order by r37_descr ";
  $head6  = "Ordem Alfabética";
}else{
  $xordem = " order by r37_funcao ";
  $head6  = "Ordem Numérica";
}

$tipo = 'R';  /// por recurso

if($tipo = 'R')
{
  $sql = "

select 
       o70_codigo,
       o15_descr,
       sum(case when o27_exercicio = 2006 then o27_valor else 0 end) as prim, 
       sum(case when o27_exercicio = 2007 then o27_valor else 0 end) as segun, 
       sum(case when o27_exercicio = 2008 then o27_valor else 0 end) as terc, 
       sum(case when o27_exercicio = 2009 then o27_valor else 0 end) as quart 
from orcpparec 
     left join orcfontes on o57_codfon = o27_codfon and o57_anousu = ".db_getsession("DB_anousu")."
     left join orcreceita on o70_codfon = o27_codfon and o70_anousu = ".db_getsession("DB_anousu")."
     inner join orctiporec on o15_codigo = o70_codigo
group by
	 o70_codigo,
	 o15_descr
order by o70_codigo;

       ";
  
}else
{
  $sql = "

select o57_fonte,
       o57_descr,
       o70_codigo,
       o15_descr,
       sum(case when o27_exercicio = 2006 then o27_valor else 0 end) as prim, 
       sum(case when o27_exercicio = 2007 then o27_valor else 0 end) as segun, 
       sum(case when o27_exercicio = 2008 then o27_valor else 0 end) as terc, 
       sum(case when o27_exercicio = 2009 then o27_valor else 0 end) as quart 
from orcpparec 
     left join orcfontes on o57_codfon = o27_codfon
     left join orcreceita on o70_codfon = o27_codfon and o70_anousu = 2005
     inner join orctiporec on o15_codigo = o70_codigo
group by o57_fonte,
         o57_descr,
	 o70_codigo,
	 o15_descr
order by o57_fonte;

       ";
}

//echo $sql ; exit;

$result = pg_exec($sql);

//db_criatabela($result);exit;

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$total  = 0;
$troca  = 1;
$alt    = 4;
$valor1 = 0;
$valor2 = 0;
$valor3 = 0;
$valor4 = 0;
if($tipo = 'R'){


for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(22,$alt,'2006',1,0,"R",1);
      $pdf->cell(22,$alt,'2007',1,0,"R",1);
      $pdf->cell(22,$alt,'2008',1,0,"R",1);
      $pdf->cell(22,$alt,'2009',1,1,"R",1);
      $cor = 1;
      $troca = 0;
   }
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;
   $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$o70_codigo,0,0,"L",$cor);
   $pdf->cell(80,$alt,$o15_descr,0,0,"L",$cor);
   $pdf->cell(22,$alt,db_formatar($prim,'f'),0,0,"R",$cor);
   $pdf->cell(22,$alt,db_formatar($segun,'f'),0,0,"R",$cor);
   $pdf->cell(22,$alt,db_formatar($terc,'f'),0,0,"R",$cor);
   $pdf->cell(22,$alt,db_formatar($quart,'f'),0,1,"R",$cor);
   $total ++;
   $valor1 += $e2006;
   $valor2 += $e2007;
   $valor3 += $e2008;
   $valor4 += $e2009;
}
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;
$pdf->setfont('arial','b',8);
$pdf->cell(110,$alt,'TOTAL DE REGISTROS  : '.$total,1,0,"C",0);
$pdf->cell(22,$alt,db_formatar($valor1,'f'),1,0,"R",$cor);
$pdf->cell(22,$alt,db_formatar($valor2,'f'),1,0,"R",$cor);
$pdf->cell(22,$alt,db_formatar($valor3,'f'),1,0,"R",$cor);
$pdf->cell(22,$alt,db_formatar($valor4,'f'),1,1,"R",$cor);



}else{



for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,'ESTRUTURAL',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(80,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(22,$alt,'2006',1,0,"R",1);
      $pdf->cell(22,$alt,'2007',1,0,"R",1);
      $pdf->cell(22,$alt,'2008',1,0,"R",1);
      $pdf->cell(22,$alt,'2009',1,1,"R",1);
      $cor = 1;
      $troca = 0;
   }
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;
   $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$o57_fonte,0,0,"L",$cor);
   $pdf->cell(80,$alt,$o57_descr,0,0,"L",$cor);
   $pdf->cell(80,$alt,$o70_codigo.' - '.$o15_descr,0,0,"L",$cor);
   $pdf->cell(22,$alt,db_formatar($prim,'f'),0,0,"R",$cor);
   $pdf->cell(22,$alt,db_formatar($segun,'f'),0,0,"R",$cor);
   $pdf->cell(22,$alt,db_formatar($terc,'f'),0,0,"R",$cor);
   $pdf->cell(22,$alt,db_formatar($quart,'f'),0,1,"R",$cor);
   $total ++;
   $valor1 += $prim;
   $valor2 += $segun;
   $valor3 += $terc;
   $valor4 += $quart;
}
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,1,0,"C",0);
$pdf->cell(22,$alt,db_formatar($valor1,'f'),1,0,"R",$cor);
$pdf->cell(22,$alt,db_formatar($valor2,'f'),1,0,"R",$cor);
$pdf->cell(22,$alt,db_formatar($valor3,'f'),1,0,"R",$cor);
$pdf->cell(22,$alt,db_formatar($valor4,'f'),1,1,"R",$cor);



}



$pdf->Output();
   
?>