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


$where_ati = '';
$xtipo = 'Todos';

if($mes == 1){
  $mesant = 12;
  $anoant = $ano-1;
}else{
  $mesant = $mes-1;
  $anoant = $ano;
}

if($vantagem == 't'){
  $xtipo = 'Trienio';
  $opcao_sql = "substr(db_fxxx(rh02_regist, $ano    , $mes   , rh02_instit),144,11)::float as atual,
                substr(db_fxxx(rh02_regist, $anoant , $mesant, rh02_instit),144,11)::float as anterior,
                case when rh01_trienio is null then rh01_admiss else rh01_trienio end as data_base ";
}elseif($vantagem == 'q'){
  $xtipo = 'Quinquenio';
  $opcao_sql = "substr(db_fxxx(rh02_regist, $ano   , $mes    , rh02_instit),177,11)::float as atual,
                substr(db_fxxx(rh02_regist, $anoant, $mesant , rh02_instit),177,11)::flat as anterior,
                case when rh01_progres is null then rh01_admiss else rh01_progres end as data_base";
}elseif($vantagem == 'a'){
  $xtipo = 'Anuienio';
  $opcao_sql = "substr(db_fxxx(rh02_regist, $ano   , $mes    , rh02_instit),133,11)::float as atual,
                substr(db_fxxx(rh02_regist, $anoant, $mesant , rh02_instit),133,11)::float as anterior,
                case when rh01_progres is null then rh01_admiss else rh01_progres end as data_base";
}

$xwhere = '';
if($maior > 0){
  $xwhere = " and atual > $maior ";
}  
  
$head3 = "SERVIDORES COM AUMENTO DE VANTAGEM NO MES";
$head5 = "TIPO : ".$xtipo;

$sql = "
select * from 
(
select rh01_regist, 
       z01_nome, 
       $opcao_sql
from rhpessoal 
     inner join cgm           on rh01_numcgm = z01_numcgm
     inner join rhpessoalmov  on rh01_regist = rh02_regist
     left  join rhpesrescisao on rh02_seqpes = rh05_seqpes  
     left  join rhregime      on rh30_codreg = rh02_codreg
                             and rh30_instit = rh02_instit
where rh02_anousu  = $ano
  and rh02_mesusu  = $mes
  and rh02_instit  = ".db_getsession("DB_instit")."		
  and rh05_seqpes is null
  and rh30_regime  = '1'
  and rh30_vinculo = 'A'
) as x
where anterior <> atual
$xwhere
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
$alt = 4;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'DATA BASE',1,0,"C",1);
      $pdf->cell(20,$alt,'ANTERIOR',1,0,"C",1);
      $pdf->cell(20,$alt,'ATUAL',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($data_base,'d'),0,0,"C",$pre);
   $pdf->cell(20,$alt,$anterior,0,0,"C",$pre);
   $pdf->cell(20,$alt,$atual,0,1,"C",$pre);
   $total += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>