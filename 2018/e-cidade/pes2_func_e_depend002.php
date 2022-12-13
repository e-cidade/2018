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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CADASTRO DE FUNCIONÁRIOS E DEPENDENTES";
$head4 = "PERÍODO : ".$mes." / ".$ano;

$where = ""; 
$and = " and ";

//$info = '';
//$info1 = '';

if (isset($rh01_regist)&&$rh01_regist!=""){
  $where .= $and." rh02_regist = $rh01_regist  ";
  $and = " and ";
  $info = "Matrícula: $rh01_regist";
}
if (isset($r13_codigo)&&$r13_codigo!=""){
  $where .= $and." r70_codigo = $r13_codigo  ";
  $and = " and ";
  $info1 = "Lotação: $r13_codigo $r13_descr";
}

$head5 = @$info;
$head6 = @$info1;
$sql = "
        select * 
	from rhpessoalmov
       inner join rhpessoal    on rh01_regist = rh02_regist       
	     inner join cgm          on rh01_numcgm = z01_numcgm
       left  join rhlota       on r70_codigo  = rh02_lota
                              and r70_instit  = rh02_instit
       left join rhpesrescisao on rh05_seqpes = rh02_seqpes 
       
	where rh02_anousu = $ano 
   	and rh02_mesusu = $mes
    and rh02_instit = ".db_getsession('DB_instit')."
	  and rh05_recis is null $where
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
      $pdf->cell(20,$alt,'MATRÍCULA',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'NASCIMENTO',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$rh01_regist,0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($rh01_nasc,'d'),0,1,"C",0);
   $sql_dep = "select * from rhdepend 
               where rh31_regist = $rh01_regist"; 
   $res_dep = pg_query($sql_dep);
     
   for($yy = 0;$yy < pg_numrows($res_dep);$yy++){
      db_fieldsmemory($res_dep,$yy);
      if($yy == 0)
        $pdf->cell(40,$alt,'DEPENDENTES : ',0,0,"L",0);
      else
        $pdf->cell(40,$alt,'',0,0,"C",0);
      $pdf->cell(60,$alt,$rh31_nome,0,0,"L",0);
      $pdf->cell(20,$alt,$rh31_gparen,0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($rh31_dtnasc,'d'),0,1,"C",0);
   }
   $pdf->cell(0,$alt,'','T',1,"C",0);
   
}

//$pdf->setfont('arial','b',8);
//$pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>