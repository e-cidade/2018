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

$head3 = "RELATÓRIO DE FUNCIONÁRIOS SEM CONTA";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select r38_regist,
       z01_nome, 
       rh25_projativ,
       rh26_orgao,
       rh26_unidade,
       r38_liq as valor,
       r70_estrut, 
       case rh25_recurso       
            when 1  then 'PROPRIO/LIVRE'
            when 1004 then 'PAB'
            when 4510 then 'PAB'
            when 20 then 'MDE'
            when 31 then 'FUNDEB'
            when 1049 then 'PACS'
            when 4530 then 'PACS'
            when 40 then 'FMS/PROPRIOS'
            when 1058 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 4710 then 'VIGILANCIA EPIDEMIOLOGICA'
            when 50 then 'FAPS'
            when 1155 then 'FARM. POPULAR'
            when 4840 then 'FARM. POPULAR'
       else 'SEM RECURSO'
       end as recurso
from folha 
     inner join cgm on r38_numcgm = z01_numcgm 
     inner join rhlota on r70_codigo = to_number(r38_lotac,'9999')
		                  and r70_instit = ".db_getsession("DB_instit")."
     inner join rhlotaexe on rh26_codigo = r70_codigo and rh26_anousu = $ano
     inner join (select distinct rh25_codigo, rh25_recurso, rh25_projativ from rhlotavinc where rh25_anousu = $ano) as rhlotavinc on rh25_codigo = r70_codigo 
where trim(r38_banco) = ''
order by recurso
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários sem conta no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$func   = 0;
$func_c = 0;
$tot_c  = 0;
$total  = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'UNID.ORÇ.',1,0,"C",1);
      $pdf->cell(20,$alt,'PROJ.ATIV.',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $rec = '';
      $troca = 0;
   }
   if ( $rec != $recurso ){
      if($rec != ''){
        $pdf->ln(1);
        $pdf->cell(30,$alt,'Total do Recurso  :  ',"T",0,"L",0);
        $pdf->cell(45,$alt,$func_c,"T",0,"L",0);
        $pdf->cell(40,$alt,'',"T",0,"L",0);
        $pdf->cell(20,$alt,db_formatar($tot_c,'f'),"T",1,"R",0);
	$func_c = 0;
	$tot_c  = 0;
      }
      $pdf->setfont('arial','b',9);
      $pdf->ln(4);
      $pdf->cell(50,$alt,$recurso,0,1,"L",1);
      $pdf->ln(2);
      $rec = $recurso;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r38_regist,0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($rh26_orgao,'orgao').db_formatar($rh26_unidade,'orgao'),0,0,"C",0);
   $pdf->cell(20,$alt,$rh25_projativ,0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
   $func   += 1;
   $func_c += 1;
   $tot_c  += $valor;
   $total  += $valor;
}
$pdf->ln(1);
$pdf->cell(30,$alt,'Total do Recurso  :  ',"T",0,"L",0);
$pdf->cell(45,$alt,$func_c,"T",0,"L",0);
$pdf->cell(40,$alt,'',"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_c,'f'),"T",1,"R",0);

$pdf->ln(3);
$pdf->cell(30,$alt,'Total da Geral  :  ',"T",0,"L",0);
$pdf->cell(45,$alt,$func,"T",0,"L",0);
$pdf->cell(40,$alt,'',"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>