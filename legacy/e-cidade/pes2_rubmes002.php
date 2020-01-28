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
$clrotulo->label('r14_rubric');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_regist');
$clrotulo->label('r14_quant');
$clrotulo->label('r14_valor');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql1 = "select rh27_rubric,
                rh27_descr 
	 from rhrubricas where rh27_rubric = '$rubrica'
	                   and rh27_instit = ".db_getsession("DB_instit")." ";
//echo $sql1;exit;
$result1 = pg_query($sql1);
db_fieldsmemory($result1,0);
if (pg_numrows($result1) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Rubrica não cadastrada no período de '.$mes.' / '.$ano);
}

$head2 = "FINANCEIRA POR RUBRICA";
$head4 = "RUBRICA : ".$rubrica." - ".$rh27_descr;
$head6 = "PERÍODO : ".$mes." / ".$ano;


$sql = "
        select r14_rubric,
	       r14_regist,
	       z01_nome,
	       r14_quant,
	       r14_valor 
	from gerfsal 
	     inner join rhpessoalmov on r14_regist = rh02_regist 
	                       and rh02_anousu = r14_anousu 
                         and rh02_mesusu = r14_mesusu 
												 and rh02_instit = r14_instit
	     inner join rhpessoal on r14_regist = rh01_regist 
	     inner join cgm on z01_numcgm = r01_numcgm 
	where r14_rubric = '$rubrica'
	  and r14_anousu = $ano 
	  and r14_mesusu = $mes
		and r14_instit = ".db_getsession("DB_instit")."
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
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$alt   = 4;
$xvalor = 0;
$xquant = 0;
$total = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLr01_regist,1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(15,$alt,'QUANT',1,0,"C",1);
      $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r14_regist,0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(15,$alt,db_formatar($r14_quant,'f'),0,0,"R",0);
   $pdf->cell(25,$alt,db_formatar($r14_valor,'f'),0,1,"R",0);
   $xvalor += $r14_valor;
   $xquant += $r14_quant;
   $total  += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'TOTAL  :  '.$total.'  FUNCIONÁRIOS',"T",0,"C",0);
$pdf->cell(15,$alt,db_formatar($xquant,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($xvalor,'f'),"T",1,"R",0);

$pdf->Output();
   
?>