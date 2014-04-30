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
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_tipo');
$clrotulo->label('k02_descr');
$clrotulo->label('k02_drecei');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$sql = "";
$info = "Todas Receitas";

if($listar=="t") {
  $sql .= "select * from(";
}

if($listar == "o"||$listar=="t") {
  if($listar == "o"){
    $info = "Orçamentárias";
  }
$sql .= "select distinct * 
from (
select tabrec.*
  from tabrec
  left join taborc    on taborc.k02_codigo = tabrec.k02_codigo
  and taborc.k02_anousu = ".db_getsession("DB_anousu")."

  where taborc.k02_codigo is null 
  and tabrec.k02_tipo = 'O'
  and ( tabrec.k02_limite is null or  tabrec.k02_limite > '".db_getsession("DB_anousu")."-01-01' )
  union all
  select tabrec.*
  from tabrec
  left join taborc    on taborc.k02_codigo = tabrec.k02_recjur
  and taborc.k02_anousu = ".db_getsession("DB_anousu")."
  where taborc.k02_codigo is null
  and tabrec.k02_tipo = 'O'
  and ( tabrec.k02_limite is null or  tabrec.k02_limite > '".db_getsession("DB_anousu")."-01-01' )
  union all
  select tabrec.*
  from tabrec
  left join taborc    on taborc.k02_codigo = tabrec.k02_recmul
  and taborc.k02_anousu = ".db_getsession("DB_anousu")."
  where taborc.k02_codigo is null 
  and tabrec.k02_tipo = 'O'
  and ( tabrec.k02_limite is null or  tabrec.k02_limite > '".db_getsession("DB_anousu")."-01-01' )
  ) as x
  " ;

  if($listar != "t"){
$sql .=" order by k02_codigo ";
  }
}
if($listar=="t") {
  $sql .= "  union   ";
}
if($listar == "e"||$listar=="t") {
  if($listar == "e"){
    $info = "Extra-orçamentárias";
  }
$sql .= "select distinct * 
from (
select tabrec.*
from tabrec
left join tabplan   on tabplan.k02_codigo = tabrec.k02_codigo
and tabplan.k02_anousu = ".db_getsession("DB_anousu")."

where tabplan.k02_codigo is null 
and tabrec.k02_tipo = 'E'
and ( tabrec.k02_limite is null or  tabrec.k02_limite > '".db_getsession("DB_anousu")."-01-01' )
union all
select tabrec.*
from tabrec
left join tabplan   on tabplan.k02_codigo = tabrec.k02_recjur
and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
where tabplan.k02_codigo is null
and tabrec.k02_tipo = 'E'
and ( tabrec.k02_limite is null or  tabrec.k02_limite > '".db_getsession("DB_anousu")."-01-01' )
union all
select tabrec.*
from tabrec
left join tabplan   on tabplan.k02_codigo = tabrec.k02_recmul
and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
where tabplan.k02_codigo is null 
and tabrec.k02_tipo = 'E'
and ( tabrec.k02_limite is null or  tabrec.k02_limite > '".db_getsession("DB_anousu")."-01-01' )
) as x
";

  if($listar != "t"){
$sql .=" order by k02_codigo ";
  }
}

if($listar=="t") {
  $sql .= ") as tabrec";
}
  if($listar == "t"){
$sql .=" order by k02_codigo ";
  }

$head3 = "Verifica Receitas Tesouraria";
$head5 = "Listar: $info";

$result = pg_exec($sql);
$numrows = pg_numrows($result);
if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
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
$totalO = 0;
$totalE = 0;
for($x = 0; $x < $numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLk02_codigo,1,0,"C",1);
      $pdf->cell(30,$alt,$RLk02_tipo,1,0,"C",1); 
      $pdf->cell(50,$alt,$RLk02_descr,1,0,"C",1); 
      $pdf->cell(80,$alt,$RLk02_drecei,1,1,"C",1);       
      $troca = 0;
   }
   $tipo = "";
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$k02_codigo,0,0,"C",0);
   if ($k02_tipo=="O"){
     $tipo = "Orcamentária";
     $totalO++;
   }else if ($k02_tipo=="E"){
     $tipo = "Extra-orçamentária";
     $totalE++;
   }
   $pdf->cell(30,$alt,$tipo,0,0,"C",0);
   $pdf->cell(50,$alt,$k02_descr,0,0,"L",0);
   $pdf->cell(80,$alt,$k02_drecei,0,1,"L",0);
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(180,$alt,'RECEITAS ORÇAMENTÁRIAS: '.$totalO,"T",1,"R",0);
$pdf->cell(180,$alt,'RECEITAS EXTRA-ORÇAMENTÁRIAS: '.$totalE,"T",1,"R",0);
$pdf->cell(180,$alt,'TOTAL DE REGISTROS: '.$total,"T",1,"R",0);
$pdf->Output();
?>