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
$clrotulo->label('r01_funcao');
$clrotulo->label('r37_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($clas == 1){
  $head7 = 'CLASSIFICAÇÕES : 2 e 8 ';
  $xclas = " and trim(r01_clas1) in ('2','8') ";
}elseif($clas == 2){
  $head7 = 'CLASSIFICAÇÕES : 1 e 9 ';
  $xclas = " and trim(r01_clas1) in ('1','9') ";
}elseif($clas == 3){
  $head7 = 'CLASSIFICAÇÕES : 3, 4,6 e 12 ';
  $xclas = " and trim(r01_clas1) in ('3','4','6','12') ";
}elseif($clas == 4){
  $head7 = 'CLASSIFICAÇÕES : 5, 10 e 11 ';
  $xclas = " and trim(r01_clas1) in ('5','10','11') ";
}elseif($clas == 5){
  $head7 = 'CLASSIFICAÇÕES : 7 ';
  $xclas = " and trim(r01_clas1) in ('7') ";
}else{
  $head7 = 'CLASSIFICAÇÕES : Todas ';
  $xclas = '';
}

$head3 = "RELATÓRIO DE CARGOS";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select rh02_regist as r01_regist,
       z01_nome,
       rh37_descr as r37_descr,
       rh37_vagas as r37_vagas
from rhpessoalmov
     inner join rhpessoal on rh01_regist = rh02_regist
     inner join rhfuncao  on rh01_funcao = rh37_funcao
		                     and rh02_instit = rh37_instit
     left  join rhpesrescisao on rh05_seqpes = rh02_seqpes
     inner join cgm       on rh01_numcgm = z01_numcgm 
where rh02_anousu = $ano 
  and rh02_mesusu = $mes
	and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis is null
  $xclas
order by r37_descr,z01_nome
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários no período de '.$mes.' / '.$ano);

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
      $pdf->cell(60,$alt,'NOME',1,1,"C",1);
      $funcao = '';
      $troca = 0;
   }
   if ( $funcao != $r37_descr ){
      if($funcao != ''){
        $pdf->ln(1);
        $pdf->cell(75,$alt,'Total de cargos  :  '.$func_c,0,0,"L",0);
	$func_c = 0;
	$tot_c  = 0;
      }
      $pdf->setfont('arial','b',9);
      $pdf->ln(10);
      $pdf->cell(100,$alt,$r37_descr.'    Vagas : '.$r37_vagas,0,1,"L",1);
      $funcao = $r37_descr;
   }
   if($funcion == 't'){
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
     $pdf->cell(60,$alt,$z01_nome,0,1,"L",0);
   }
   $func   += 1;
   $func_c += 1;
}
$pdf->ln(1);
$pdf->cell(115,$alt,'Total de cargos  :  '.$func_c,0,0,"L",0);

$pdf->ln(5);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func,0,0,"L",0);

$pdf->Output();
   
?>