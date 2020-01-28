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
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('rh01_admiss');
$clrotulo->label('rh37_descr');
$clrotulo->label('rh30_descr');
$clrotulo->label('r70_descr');
$clrotulo->label('h13_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//$mes = 4;
//$ano = 2005;

$head3 = "FUNCIONÁRIOS POR TIPO DE CONTRATO";
$head5 = "PERÍODO : ".$mes." / ".$ano;

if($ordem == 'a'){
  $xordem = " order by z01_nome ";
  $head6  = "Ordem Alfabética";
}else{
  $xordem = " order by rh01_regist ";
  $head6  = "Ordem Numérica";
}
$sql = "
select
       rh01_regist,
       z01_nome,
       rh01_admiss,
       rh37_descr,
       rh30_descr,
       r70_descr,
       h13_descr
       
       
from rhpessoalmov 
     inner join rhpessoal on rh02_regist = rh01_regist
     inner join cgm on z01_numcgm = rh01_numcgm
     inner join rhfuncao on rh37_funcao = rh01_funcao
		                    and rh37_instit = rh02_instit
     inner join rhlota   on r70_codigo = rh02_lota
		                    and r70_instit = rh02_instit
     left  join rhpesrescisao on rh05_seqpes = rh02_seqpes
     inner join rhregime on rh30_codreg = rh02_codreg
		                    and rh30_instit = rh02_instit 
     inner join tpcontra on h13_codigo = rh02_tpcont
where rh02_anousu = $ano
  and rh02_mesusu = $mes
	and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_seqpes is null
$xordem
       ";

//die($sql);
$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no período de '.$mes.' / '.$ano);

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
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'MATRÍCULA',1,0,"C",1);
      $pdf->cell(55,$alt,'NOME',1,0,"C",1);
      $pdf->cell(55,$alt,'CARGO',1,0,"C",1);
      $pdf->cell(40,$alt,'REGIME',1,0,"C",1);
      $pdf->cell(55,$alt,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(15,$alt,'ADMISSÃO',1,0,"C",1);
      $pdf->cell(40,$alt,'TIPO CONTRATO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
      $pre = 0;
   }else{
     $pre = 1;
   }

   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(55,$alt,substr($z01_nome,0,30),0,0,"L",$pre);
   $pdf->cell(55,$alt,substr($rh37_descr,0,30),0,0,"L",$pre);
   $pdf->cell(40,$alt,substr($rh30_descr,0,20),0,0,"L",$pre);
   $pdf->cell(55,$alt,substr($r70_descr,0,30),0,0,"L",$pre);
   $pdf->cell(15,$alt,db_formatar($rh01_admiss,"d"),0,0,"L",$pre);
   $pdf->cell(40,$alt,substr($h13_descr,0,20),0,1,"L",$pre);
   
   $total += 1;
   
}
$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'TOTAL  :  '.$total.'   FUNCIONÁRIOS',"T",1,"C",0);

$pdf->Output();
   
?>