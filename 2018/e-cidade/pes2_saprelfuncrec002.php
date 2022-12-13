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
//$ano = 2005;
//$mes = 11;


$head3 = "RELATÓRIO DE FUNCIONÁRIOS COM RECURSO";
$head5 = "PERÍODO : ".$mes." / ".$ano;


if($tipo_rel=="a"){
$sql = "

select rh02_regist as r01_regist,
       z01_nome,
       o15_codigo,
       case when substr(r70_estrut,10,2) = '03' 
			then 'FUNDEB 60%' 
            else 
			case when  substr(r70_estrut,10,2) = '04' 
				 then 'FUNDEB 40%'
	             else o15_descr
	        end
       end as recurso,
       o55_descr,
       rh25_projativ,
       o41_descr
from rhpessoalmov
     inner join rhpessoal on rh01_regist = rh02_regist
     inner join cgm       on rh01_numcgm = z01_numcgm 
     inner join rhlota    on r70_codigo = rh02_lota
		                     and r70_instit = rh02_instit 
     left join rhpesrescisao on rh05_seqpes    = rhpessoalmov.rh02_seqpes
     left join  rhlotavinc on r70_codigo = rh25_codigo 
		                      and rh25_anousu = $ano
     left join orcprojativ on rh25_projativ = o55_projativ
                       			  and o55_anousu = rh02_anousu
     left join rhlotaexe   on r70_codigo = rh26_codigo
                          and rh26_anousu = rh02_anousu
     inner join orcunidade on o41_anousu = rh26_anousu 
                  			  and rh26_unidade = o41_unidade
                  			  and rh26_orgao   = o41_orgao
     left  join orctiporec on rh25_recurso = o15_codigo

where rh02_anousu = $ano 
  and rh02_mesusu = $mes 
	and rh02_instit = ".db_getsession("DB_instit")."
	and rh05_recis is null 
order by z01_nome;
       ";
//echo $sql ; exit;
}else if($tipo_rel=="s"){
$sql = "

select count(rh02_regist) as r01_regist,
       o15_codigo,
       case when substr(r70_estrut,10,2) = '03' 
			      then 'FUNDEF 60%' 
            else 
							case when  substr(r70_estrut,10,2) = '04' 
							   then 'FUNDEF 40%'
	               else o15_descr
	            end
       end as recurso
from rhpessoalmov
     inner join rhpessoal on rh01_regist = rh02_regist
     inner join cgm       on rh01_numcgm = z01_numcgm 
     inner join rhlota    on r70_codigo = rh02_lota
		                     and r70_instit = rh02_instit 
     left join rhpesrescisao on rh05_seqpes    = rhpessoalmov.rh02_seqpes
     left join  rhlotavinc on r70_codigo = rh25_codigo 
		                      and rh25_anousu = $ano
     left join orcprojativ on rh25_projativ = o55_projativ
                       			  and o55_anousu = rh02_anousu
     left join rhlotaexe   on r70_codigo = rh26_codigo
                          and rh26_anousu = rh02_anousu
     inner join orcunidade on o41_anousu = rh26_anousu 
                  			  and rh26_unidade = o41_unidade
                  			  and rh26_orgao   = o41_orgao
     left  join orctiporec on rh25_recurso = o15_codigo

where rh02_anousu = $ano 
  and rh02_mesusu = $mes 
	and rh02_instit = ".db_getsession("DB_instit")."
	and rh05_recis is null 

group by o15_codigo,recurso 
";
//echo $sql ; exit;
}

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
$total = 0;
$funcion = 0;
$totalregist = 0;
if ($tipo_rel=="a"){
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	$pdf->addpage("L");
	$pdf->setfont('arial','b',8);
	$pdf->cell(20,$alt,'MATRÍCULA',1,0,"C",1);
	$pdf->cell(60,$alt,'NOME',1,0,"C",1);
	$pdf->cell(50,$alt,'RECURSO',1,0,"C",1);
	$pdf->cell(60,$alt,'ATIVIDADE',1,0,"C",1);
	$pdf->cell(60,$alt,'UNIDADE',1,1,"C",1);
	$troca = 0;
	$pre = 1;
     }
     if($pre == 1){
	$pre = 0;
     }else{
	$pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$r01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(50,$alt,$o15_codigo.'-'.$recurso,0,0,"L",$pre);
     $pdf->cell(60,$alt,$rh25_projativ.'-'.$o55_descr,0,0,"L",$pre);
     $pdf->cell(60,$alt,$o41_descr,0,1,"L",$pre);
     $funcion += 1;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(150,$alt,'TOTAL '.$funcion.' FUNCIONÁRIOS',"T",0,"L",0);
}else if($tipo_rel=="s"){
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	$pdf->addpage("L");
	$pdf->setfont('arial','b',8);
	$pdf->cell(60,$alt,'RECURSO',1,0,"C",1);
	$pdf->cell(60,$alt,'QUANT. REGISTROS',1,1,"C",1);
	$troca = 0;
	$pre = 1;
     }
     if($pre == 1){
	$pre = 0;
     }else{
	$pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(60,$alt,$o15_codigo.'-'.$recurso,0,0,"L",$pre);
     $pdf->cell(60,$alt,$r01_regist,0,1,"C",$pre);
     $funcion += 1;
     $totalregist += $r01_regist;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(60,$alt,'TOTAL DE RECURSOS: '.$funcion ,"T",0,"L",0);
  $pdf->cell(60,$alt,'TOTAL DE REGISTROS: '.$totalregist ,"T",0,"R",0);
}

$pdf->Output();
   
?>