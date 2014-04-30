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

include("fpdf151/pdf2.php");
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('rh27_elemen');
$clrotulo->label('rh27_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
//db_postmemory($HTTP_POST_VARS,2);exit;

//$ano = 2005;
//$mes = 9;
//$matric = 182;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

$sql = "
select cadferia.*,
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
       end as recurso,
                  case when r30_per2i is null 
                       then r30_per1i - 30
		  else r30_per2i - 30 end as data, 
                  case when r30_per2i is null 
                       then r30_per1i
		  else r30_per2i end as gozoi, 
                  case when r30_per2f is null 
                       then r30_per1f
		  else r30_per2f end as gozof, 
		  z01_nome, rh37_descr as r37_descr 
from cadferia 
     inner join rhpessoalmov on rh02_regist = r30_regist
                            and rh02_anousu = r30_anousu
             		            and rh02_mesusu = r30_mesusu
											      and rh02_instit = ".db_getsession("DB_instit")."
     inner join rhpessoal    on rh01_regist = r30_regist                       
     inner join cgm     on rh01_numcgm = z01_numcgm
     inner join rhfuncao on rh01_funcao = rh37_funcao
                         and rh02_instit = rh37_instit 
     inner join rhlota   on r70_codigo = rh02_lota
		                    and r70_instit = rh02_instit 
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc) as rhlotavinc on rh25_codigo = r70_codigo 
where r30_anousu = $ano
  and r30_mesusu = $mes
  and r30_regist = $matric
order by r30_regist, 
         R30_PERAI desc limit 1;
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result); exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF2(); 
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
$head1 = 'DEPARTAMENTO DE PESSOAL';


for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $parag1 = $z01_nome.', abaixo assinado, servidor desta Prefeitura Municipal, exercendo o cargo de '.$r37_descr.' vem mui respeitosamente, requerer a V. Sa., as férias referentes ao período de '.substr($r30_perai,8,2).' de '.db_mes(substr($r30_perai,5,2)).' de '.substr($r30_perai,0,4).' à '.substr($r30_peraf,8,2).' de '.db_mes(substr($r30_peraf,5,2)).' de '.substr($r30_peraf,0,4).' a serem gozadas a partir de '.substr($gozoi,8,2).' de '.db_mes(substr($gozoi,5,2)).' de '.substr($gozoi,0,4).' à '.substr($gozof,8,2).' de '.db_mes(substr($gozof,5,2)).' de '.substr($gozof,0,4).'.';
      $pdf->setfont('arial','',12);
      $pdf->ln(10);
      $pdf->cell(40,5,$recurso,0,1,"L",0);
      $pdf->ln(10);
      $pdf->cell(40,5,'NOME :  '.$z01_nome.'      MATRÍCULA :  '.$r30_regist,0,1,"L",0);
      $pdf->ln(10);
      $pdf->multicell(0,5,'PROVENIENTE : de '.$r30_dias1.' de férias, referente ao período de '.substr($r30_perai,8,2).' de '.db_mes(substr($r30_perai,5,2)).' de '.substr($r30_perai,0,4).' à '.substr($r30_peraf,8,2).' de '.db_mes(substr($r30_peraf,5,2)).' de '.substr($r30_peraf,0,4).' a serem gozadas a partir de '.substr($gozoi,8,2).' de '.db_mes(substr($gozoi,5,2)).' de '.substr($gozoi,0,4).' à '.substr($gozof,8,2).' de '.db_mes(substr($gozof,5,2)).' de '.substr($gozof,0,4).'.',0,"J",0);
      //$pdf->cell(100,5,strtoupper($munic).'-'.strtoupper($uf).', '.substr($data,8,2).' de '.db_mes(substr($data,5,2)).' de '.substr($data,0,4),0,1,"L",0);

$sql1 = 

" select r48_rubric as rubrica,
       case 
         when rh27_pd = 3 then 0 
         else 
           case 
             when r48_pd = 1 then r48_valor 
             else 0 
           end 
       end as Provento,
       case 
         when rh27_pd = 3 then 0 
         else 
           case 
             when r48_pd = 2 then r48_valor 
             else 0 
           end 
       end as Desconto,
       r48_quant as quant, 
       rh27_descr, 
       case 
         when rh27_pd = 3 then 'Base' 
         else 
          case 
            when r48_pd = 1 then 'Provento' 
	          else 'Desconto' 
	        end 
       end as provdesc
 
from gerfcom 
     inner join rhrubricas on rh27_rubric = r48_rubric 
                          and rh27_instit = r48_instit 
where r48_regist = $r30_regist
  and r48_anousu = $ano 
  and r48_mesusu = $mes
	and r48_instit = ".db_getsession("DB_instit")."
  and r48_pd != 3 

";
$result1 = pg_query($sql1);
//db_criatabela($result1);

$tot_prov = 0;
$tot_desc = 0;

     if(pg_numrows($result1) > 0){
      $pdf->setfont('arial','B',10);
      $pdf->ln(10);
       $pdf->cell(100,5,'VALORES : ',0,1,"L",0);
      $pdf->ln(10);
       $pdf->cell(20,5,'CÓDIGO',1,0,"C",1);
       $pdf->cell(70,5,'DESCRIÇÃO',1,0,"C",1);
       $pdf->cell(30,5,'PROVENTO',1,0,"C",1);
       $pdf->cell(30,5,'DESCONTO',1,1,"C",1);
     }
     $pdf->setfont('arial','',10);
     for($xx = 0;$xx < pg_numrows($result1);$xx++){
       db_fieldsmemory($result1,$xx);
       $pdf->cell(20,5,$rubrica,1,0,"C",0);
       $pdf->cell(70,5,$rh27_descr,1,0,"L",0);
       $pdf->cell(30,5,db_formatar($provento,'f'),1,0,"R",0);
       $pdf->cell(30,5,db_formatar($desconto,'f'),1,1,"R",0);
       $tot_prov += $provento;
       $tot_desc += $desconto;
	
     }
     $pdf->setfont('arial','B',10);
     $pdf->cell(20,5,'',1,0,"C",0);
     $pdf->cell(70,5,'TOTAL',1,0,"L",0);
     $pdf->cell(30,5,db_formatar($tot_prov,'f'),1,0,"R",0);
     $pdf->cell(30,5,db_formatar($tot_desc,'f'),1,1,"R",0);
     $pdf->cell(20,5,'',1,0,"C",0);
     $pdf->cell(70,5,'LÍQUIDO',1,0,"L",0);
     $pdf->cell(30,5,db_formatar($tot_prov - $tot_desc,'f'),1,0,"R",0);
     $pdf->cell(30,5,db_formatar(0,'f'),1,1,"R",0);
   }
}
$pdf->Output();
   
?>