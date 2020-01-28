<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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


include("libs/db_liborcamento.php");


// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;

include("fpdf151/pdf.php");
include("libs/db_sql.php");

//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$instit     = str_replace('-',', ',$db_selinstit);
$xinstit    = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in ($instit)");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev ; 
  $xvirg = ', ';
}
$head2 = "TOTAL DO ORCAMENTO - RECEITA";
$head3 = "POR RECURSO";
$head4 = "EXERCICIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$sql = "
select *,receita-despesa as difer from (
select o70_codigo,
       o15_descr,
       sum(case when tipo = 0 then sum else 0 end ) as receita,
       sum(case when tipo = 1 then sum else 0 end ) as despesa
from (
   select 0::int as tipo,o70_codigo,
        sum(substr(fc_receitasaldo(".db_getsession("DB_anousu").",
	                           o70_codrec,
				   1,
				   '".db_getsession("DB_anousu")."-01-01',
  				   '".db_getsession("DB_anousu")."-01-01'),
		   2,15)::float8
		   )
   from orcreceita 
   where o70_anousu = ".db_getsession("DB_anousu")." and o70_instit in ($instit)
   group by o70_codigo,tipo
 union
   select 1::int as tipo,
          o58_codigo,
	  sum(substr(fc_dotacaosaldo(".db_getsession("DB_anousu").",
	                             o58_coddot,
				     1,
				     '".db_getsession("DB_anousu")."-01-01',
				     '".db_getsession("DB_anousu")."-01-01')
	             ,2,15)::float8)
   from orcdotacao
   where o58_anousu = ".db_getsession("DB_anousu")." 
   group by o58_codigo, tipo
) as x
   inner join orctiporec on o70_codigo = o15_codigo
group by o70_codigo,o15_descr
) as x  
order by o70_codigo";
$result = db_query($sql);


//db_criatabela($result);
//exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$tota2 = 0;
$tota3 = 0;

$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);

    $pdf->cell(20,$alt,"Recurso",0,0,"L",0);
    $pdf->cell(80,$alt,"Descrição",0,0,"L",0);
    $pdf->cell(25,$alt,"Receita",0,0,"R",0);
    $pdf->cell(25,$alt,"Despesa",0,0,"R",0);
    $pdf->cell(25,$alt,"Diferença",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',7);
  }

  $pdf->cell(20,$alt,db_formatar($o70_codigo,"recurso"),0,0,"L",0);
  $pdf->cell(80,$alt,$o15_descr,0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($receita,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($despesa,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($difer,'f'),0,1,"R",0);
  $total += $receita;
  $tota2 += $despesa;
  $tota3 += $difer;
}
$pdf->setfont('arial','b',7);
$pdf->ln(3);
$pdf->cell(100,$alt,'T O T A L',0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($total,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($tota2,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($tota3,'f'),0,1,"R",0);

$pdf->Output();

db_query("commit");