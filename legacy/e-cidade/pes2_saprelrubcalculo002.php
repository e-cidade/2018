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
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('rh27_elemen');
$clrotulo->label('rh27_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$sql1 = "select rh27_rubric,
                rh27_descr 
	 from rhrubricas 
	 where rh27_rubric = '$rubrica'
     and rh27_instit = ".db_getsession("DB_instit");
//echo $sql1;exit;
$result1 = pg_query($sql1);
db_fieldsmemory($result1,0);
if (pg_numrows($result1) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Rubrica não cadastrada no período de '.$mes.' / '.$ano);
}

$head3 = strtoupper($rh27_descr);
$head5 = "PERÍODO : ".$mes." / ".$ano;

if($ponto == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14_';
  $head7   = 'PONTO : SALÁRIO';
}elseif($ponto == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48_';
  $head7   = 'PONTO : COMPLEMENTAR';
}elseif($ponto == 'a'){
  $arquivo = 'gerfadi';
  $sigla   = 'r22_';
  $head7   = 'PONTO : ADIANTAMENTO';
}elseif($ponto == 'r'){
  $arquivo = 'gerfres';
  $sigla   = 'r20_';
  $head7   = 'PONTO : RESCISÃO';
}elseif($ponto == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35_';
  $head7   = 'PONTO : 13o. SALÁRIO';
}
$where = "";
if(isset($semest) && $semest != 0){
  $where = " and r48_semest = ".$semest;
  $head7.= "($semest)";
}

$sql = "
select rh01_regist as regist,
       z01_nome as nome,
       ".$sigla."valor as valor,
       ".$sigla."rubric as rubric, 
       rh27_descr as descr_rubric,
       r70_estrut,
       case rh25_recurso       
            when 1  then 'PROPRIO/LIVRE'
            when 1004 then 'PAB'
            when 4510 then 'PAB'
            when 20 then 'MDE'
            when 30 then 'FUNDEF'
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
from ".$arquivo." 
     inner join rhpessoalmov  on rh02_regist = ".$sigla."regist 
                             and rh02_anousu = ".$sigla."anousu 
		                         and rh02_mesusu = ".$sigla."mesusu 
												     and rh02_instit = ".$sigla."instit
     inner join rhpessoal     on rh01_regist = ".$sigla."regist                        
     inner join cgm      on rh01_numcgm = z01_numcgm 
     inner join rhrubricas on rh27_rubric = ".$sigla."rubric 
		                      and rh27_instit = ".$sigla."instit 
     inner join rhlota   on r70_codigo = rh02_lota
		                    and r70_instit = ".$sigla."instit 
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = ".$ano.") as rhlotavinc on rh25_codigo = r70_codigo 
where ".$sigla."anousu = $ano 
  and ".$sigla."mesusu = $mes
	and ".$sigla."instit = ".db_getsession("DB_instit")."
  and ".$sigla."rubric = '$rubrica'
  $where
order by recurso,z01_nome
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem descontos de mensalidadedo sindicato no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$funcion = 0;
$func_c  = 0;
$tot_c   = 0;
$total   = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 6;

////// TOTAL POR RECURSO

/*
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',9);
      $pdf->cell(60,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $creche = '';
      $troca = 0;
   }
   $pdf->setfont('arial','',9);
   $pdf->cell(60,$alt,$recurso,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
   $func   += 1;
   $func_c += 1;
   $tot_c  += $valor;
   $total  += $valor;
}
$pdf->ln(3);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func,0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",0);

*/
///// POR FUNCIONARIO

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(40,$alt,'RECURSO',1,0,"R",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      $quebra = '';
      $troca = 0;
   }
   if ( $quebra != $recurso ){
      if($quebra != ''){
        $pdf->ln(1);
        $pdf->cell(115,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
        $pdf->cell(20,$alt,db_formatar($tot_c,'f'),"T",1,"R",0);
	$func_c = 0;
	$tot_c  = 0;
      }
      $pdf->setfont('arial','b',9);
      $pdf->ln(4);
      $pdf->cell(50,$alt,$recurso,0,1,"L",1);
      $quebra = $recurso;
   }
   if($func == 't'){
      $pdf->setfont('arial','',7);
      $pdf->cell(15,$alt,$regist,0,0,"C",0);
      $pdf->cell(60,$alt,$nome,0,0,"L",0);
      $pdf->cell(40,$alt,$recurso,0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
   }
   $funcion+= 1;
   $func_c += 1;
   $tot_c  += $valor;
   $total  += $valor;
}
$pdf->ln(1);
$pdf->cell(115,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_c,'f'),"T",1,"R",0);

$pdf->ln(3);
$pdf->cell(115,$alt,'Total da Geral  :  '.$funcion,"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>