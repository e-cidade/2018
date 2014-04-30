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

$head3 = "DESCONTO DO SINDICATO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$anomes = $ano.db_formatar($mes,'s','0',2,'e');

$sql = "
SELECT ano,
       mes,
       registro,
       case when z01_nome is null
            then ' FUNCIONÁRIO NÃO CADASTRADO'
	    else z01_nome
       end as nome,
       sum(sind) as sind,
       sum(desconto) as desconto
from 
	(
	select to_number(substr(r54_anomes,1,4),'9999') as ano,
	       to_number(substr(r54_anomes,5,2),'99') as mes,
	       r54_regist as registro,
	       r54_quant1 as sind, 
	       0 as desconto 
	from movrel  
	where r54_anomes = '$anomes' 
	  and r54_codrel = '9000'

	union

	select r14_anousu,
	       r14_mesusu,
	       r14_regist,
	       0,
	       r14_valor
	from gerfsal 
	where r14_anousu = $ano
	  and r14_mesusu = $mes
		and r14_instit = ".db_getsession("DB_instit")."
	  and r14_rubric = '1602'
	) as x
	
	inner JOIN rhpessoalmov on  rh02_regist = registro
                  			  and rh02_anousu = ano
                  			  and rh02_mesusu = mes
                  				and rh02_instit = ".db_getsession("DB_instit")."
  inner join rhpessoal on rh01_regist = rh02_regist												
	inner JOIN cgm       on rh01_numcgm = z01_numcgm

GROUP BY ano,mes,z01_nome,registro
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros para as opçõesescolhidas. Verifique');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$tot_sind = 0;
$tot_desc = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLr01_regist,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(25,$alt,'Sindicato',1,0,"C",1);
      $pdf->cell(25,$alt,'Desconto',1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$registro,0,0,"C",0);
   $pdf->cell(70,$alt,$nome,0,0,"L",0);
   $pdf->cell(25,$alt,db_formatar($sind,'f'),0,0,"R",0);
   $pdf->cell(25,$alt,db_formatar($desconto,'f'),0,1,"R",0);
   $total    += 1;
   $tot_sind += $sind;
   $tot_desc += $desconto;
}
$pdf->setfont('arial','b',8);
$pdf->cell(90,$alt,'TOTAL '.$total,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($tot_sind,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_desc,'f'),"T",1,"R",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>