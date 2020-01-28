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

$tipo_mesini = 1;
$tipo_mesfim = 1;

include("fpdf151/pdf.php");
include("libs/db_sql.php");
//require("libs/db_stdlib.php");
include("classes/db_orcppaval_classe.php");
include("classes/db_orcppalei_classe.php");
$clorcppaval = new cl_orcppaval;
$clorcppalei = new cl_orcppalei;

db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);exit;

$anosql = Array();
$index  = 0;


if(!isset($lei)){
  db_redireciona('db_erros.php?fechar=true&db_erro=Lei não informada.');
}

$where_envialei = " where o23_codleippa=$lei ";

if (isset($impzero) && @$impzero == "n"){
     $flag_impzero = false;
}

if (isset($impzero) && @$impzero == "s"){
     $flag_impzero = true;
}

$result_periodo = $clorcppalei->sql_record($clorcppalei->sql_query_file($lei,"o21_anoini,o21_anofim"));
if($clorcppalei->numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Lei $lei não encontrada.");
}

db_fieldsmemory($result_periodo,0);

$modelo_ldo = false;
if (isset($exercicio)&& $exercicio > 0 ){
    $anosql['0'] = $exercicio;
    $modelo_ldo = true;
    $anosql['1'] = $exercicio;
    $anosql['2'] = $exercicio;
    $anosql['3'] = $exercicio;

}else {  
  for($i=$o21_anoini;$i<=$o21_anofim;$i++){
    $anosql[$index] = $i;
    $index++;
  }
}
if($nivel == 1){
   $tipo = 'ÓRGÃO';
   $sele1 = 'o23_orgao as codigo,o40_descr as descr';
   $grupo = 'o23_orgao,o40_descr';
}elseif($nivel == 2){
   $tipo  = 'UNIDADE';
   $sele1 = "o23_orgao,o40_descr,o23_unidade,lpad(o23_orgao,2,'0')||lpad(o23_unidade,2,'0') as codigo,o41_descr as descr";
   $grupo = 'o23_orgao,o40_descr,o23_unidade,o41_descr';
}elseif($nivel == 3){
   $tipo  = 'FUNÇÃO';
   $sele1 = 'o23_funcao as codigo,o52_descr as descr';
   $grupo = 'o23_funcao,o52_descr';
}elseif($nivel == 4){
   $tipo  = 'SUBFUNÇÃO';
   $sele1 = 'o23_subfuncao as codigo,o53_descr as descr';
   $grupo = 'o23_subfuncao,o53_descr';
}elseif($nivel == 5){
   $tipo  = 'PROGRAMA';
   $sele1 = 'o23_programa as codigo,o54_descr as descr';
   $grupo = 'o23_programa,o54_descr';
}elseif($nivel == 6){
   $tipo  = 'PROJ/ATIV';
   $sele1 = 'o23_acao as codigo,o55_descr as descr';
   $grupo = 'o23_acao,o55_descr';
}elseif($nivel == 7){
   $tipo  = 'ELEMENTO';
   $sele1 = '';
   $grupo = '';
}elseif($nivel == 8){
   $tipo  = 'RECURSO';
   $sele1 = 'o26_codigo as codigo,o15_descr as descr';
   $grupo = 'o26_codigo,o15_descr';
}   

if ($modelo_ldo==true){
  $head2 = "TOTALIZAÇÃO DA LDO ";
  $head3 = "EXERCICIO : $exercicio ";
  $head5 = "TOTAL        : ".$tipo;


} else {
  $head2 = "TOTALIZAÇÃO DO PPA";
  $head5 = "TOTAL        : ".$tipo;
}
//$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$sele_work = ' 1=1 ';


$sql = "
select codigo,descr,sum(prim) as prim,sum(segun) as segun,sum(terc) as terc,sum(quart) as quart,sum(prim+segun+terc+quart) as total
from
(select $sele1,o24_exercicio, 
       sum(case when o24_exercicio = ".$anosql[0]." then o24_valor else 0 end) as prim, 
       sum(case when o24_exercicio = ".$anosql[1]." then o24_valor else 0 end) as segun, 
       sum(case when o24_exercicio = ".$anosql[2]." then o24_valor else 0 end) as terc, 
       sum(case when o24_exercicio = ".$anosql[3]." then o24_valor else 0 end) as quart
from orcppa 
     inner join orcppaval  on o23_codppa = o24_codppa
     inner join orcppatiporec on o24_codseqppa = o26_codseqppa
     inner join orcorgao   on o23_orgao  = o40_orgao 
                          and o40_anousu = o23_anoexe
     inner join orcunidade on o23_unidade = o41_unidade 
     			and o23_orgao = o41_orgao 
			and o41_anousu = o23_anoexe
     inner join orcfuncao  on o23_funcao = o52_funcao
     inner join orcsubfuncao on o23_subfuncao = o53_subfuncao
     inner join orcprograma  on o23_programa = o54_programa
     			and o54_anousu = o23_anoexe
     inner join orcprojativ  on o23_acao = o55_projativ
     			and o55_anousu = o23_anoexe
     inner join orctiporec   on o15_codigo = o26_codigo
$where_envialei
group by $grupo,o24_exercicio) as x
group by codigo,descr
order by codigo
";

if ($modelo_ldo == true ){
  $sql = "
 select codigo,descr,sum(prim) as prim,sum(prim) as total
 from
 (select $sele1,o24_exercicio, 
        sum(case when o24_exercicio = ".$anosql[0]." then o24_valor else 0 end) as prim 
 from orcppa 
      inner join orcppaval  on o23_codppa = o24_codppa
      inner join orcppatiporec on o24_codseqppa = o26_codseqppa
      inner join orcorgao   on o23_orgao  = o40_orgao 
                           and o40_anousu = o23_anoexe
      inner join orcunidade on o23_unidade = o41_unidade 
      			and o23_orgao = o41_orgao 
 			and o41_anousu = o23_anoexe
      inner join orcfuncao  on o23_funcao = o52_funcao
      inner join orcsubfuncao on o23_subfuncao = o53_subfuncao
      inner join orcprograma  on o23_programa = o54_programa
      			and o54_anousu = o23_anoexe
      inner join orcprojativ  on o23_acao = o55_projativ
      			and o55_anousu = o23_anoexe
      inner join orctiporec   on o15_codigo = o26_codigo
     $where_envialei
 group by $grupo,o24_exercicio) as x
 group by codigo,descr
 order by codigo
 ";


}  
$result = pg_query($sql);
//echo $sql;
//db_criatabela($result);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$xtotal  = 0;
$troca  = 1;
$alt    = 4;
$valor1 = 0;
$valor2 = 0;
$valor3 = 0;
$valor4 = 0;
$valor5 = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,'CÓDIGO',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(22,$alt,$anosql[0],1,0,"R",1);
      if ($modelo_ldo==false){
         $pdf->cell(22,$alt,$anosql[1],1,0,"R",1);
         $pdf->cell(22,$alt,$anosql[2],1,0,"R",1);
         $pdf->cell(22,$alt,$anosql[3],1,0,"R",1);
      }
      $pdf->cell(22,$alt,'TOTAL',1,1,"R",1);
      $cor = 1;
      $troca = 0;
   }
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;
    
   $pdf->setfont('arial','',7);

   if (!$flag_impzero){ 
        if ($modelo_ldo == false ){
             if ($prim == 0 && $segun == 0 && $terc == 0 && $quart == 0 && $total == 0){ 
                       continue;
             }
        } else {
             if ($prim == 0){
                  continue;
             }

             if ($total == 0){
                  continue;
             }
        }
   }
   
   $pdf->cell(30,$alt,$codigo,0,0,"L",$cor);
   $pdf->cell(80,$alt,$descr,0,0,"L",$cor);
   $pdf->cell(22,$alt,db_formatar($prim,'f'),0,0,"R",$cor);

   if ($modelo_ldo ==false ){
        $pdf->cell(22,$alt,db_formatar($segun,'f'),0,0,"R",$cor);
        $pdf->cell(22,$alt,db_formatar($terc,'f'),0,0,"R",$cor);
        $pdf->cell(22,$alt,db_formatar($quart,'f'),0,0,"R",$cor);
   }
        
   $pdf->cell(22,$alt,db_formatar($total,'f'),0,1,"R",$cor);

   $xtotal ++;
   $valor1 += $prim;
   if ($modelo_ldo==false){
     $valor2 += $segun;
     $valor3 += $terc;
     $valor4 += $quart;
   }
   $valor5 += $total;
}
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;

$pdf->setfont('arial','b',8);
$pdf->cell(110,$alt,'TOTAL DE REGISTROS  : '.$xtotal,1,0,"C",0);

if (!$flag_impzero){ 
     if ($modelo_ldo == false){
          if ($valor1 == 0 && $valor2 == 0 && $valor3 == 0 && $valor4 == 0 && $valor5 == 0){
               continue;
          }
     } else {
          if ($valor1 == 0){
               continue;
          }

          if ($valor5 == 0){ 
               continue;
          }
     }
}

$pdf->cell(22,$alt,db_formatar($valor1,'f'),1,0,"R",$cor);

if ($modelo_ldo ==false){
     $pdf->cell(22,$alt,db_formatar($valor2,'f'),1,0,"R",$cor);
     $pdf->cell(22,$alt,db_formatar($valor3,'f'),1,0,"R",$cor);
     $pdf->cell(22,$alt,db_formatar($valor4,'f'),1,0,"R",$cor);
}

$pdf->cell(22,$alt,db_formatar($valor5,'f'),1,1,"R",$cor);

$pdf->output();
?>