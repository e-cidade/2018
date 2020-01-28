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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//$ano = 2007;
//$mes = 10;


$head2 = "PERIODO : ".$mes." / ".$ano;
$head3 = "RELAÇÃO DE FUNCIONÁRIOS PARA CÁLCULO DO IMPACTO";


$sql = "
         select r53_regist,
                max(rh02_lota) as rh02_lota,
                max(z01_nome)  as z01_nome,
                max(r70_descr) as r70_descr,
                sum(r53_valor) as r53_valor
         from gerffx 
              inner join rhpessoal    on r53_regist = rh01_regist
              inner join cgm          on rh01_numcgm = z01_numcgm
              inner join rhpessoalmov on rh01_regist = rh02_regist and rh02_instit = " . db_getsession("DB_instit") . "
                                                                   and rh02_anousu = $ano and rh02_mesusu = $mes
              inner join rhlota       on rh02_lota = r70_codigo
              left join rhpesrescisao on rh02_seqpes = rh05_seqpes
              inner join rhlotaexe    on r70_codigo = rh26_codigo and rh26_anousu = $ano
              where r53_anousu = $ano and r53_mesusu = $mes and
              r53_pd = 1 and rh05_seqpes is null and r53_rubric not in ('0160','0279','0390','0113')
              and r53_instit = " . db_getsession("DB_instit") . "
         group by r53_regist
         order by r70_descr, z01_nome
       ";

//echo $sql;exit;


$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$xsec = '';
$pdf->setfillcolor(235);
db_fieldsmemory($result,1);
$lotacao = $rh02_lota;
$total_lotacao = 0;
$total_func = 0;
$total_geral = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($rh02_lota != $lotacao){

//     $pdf->addpage("L");
     $pdf->setfont('arial','b',8);
     $pdf->cell(160,$alt,'TOTAL POR LOTACAO :  '.$total_func,"T",0,"L",0);
     $pdf->cell(25,$alt,db_formatar($total_lotacao,'f'),"T",1,"L",0);
     $pdf->ln(2);
     $total_lotacao = 0;
     $total_func = 0;
   }

   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(18,$alt,'MATRICULA',1,0,"C",1);
      $pdf->cell(75,$alt,'FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(65,$alt,'LOTACAO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTOS',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','b',7);
   $pdf->cell(18,$alt,$r53_regist,0,0,"L",$pre);
   $pdf->cell(75,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(65,$alt,$rh02_lota.' - '.$r70_descr,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($r53_valor,'f'),0,1,"R",$pre);
   $total++;
   $total_func++;
   $lotacao = $rh02_lota;
   $total_lotacao = ($total_lotacao + $r53_valor);
   $total_geral = ($total_geral + $r53_valor);
}
$pdf->setfont('arial','b',8);
$pdf->cell(160,$alt,'TOTAL GERAL :  '.$total,"T",0,"L",0);
$pdf->cell(25,$alt,db_formatar($total_geral,'f'),"T",1,"R",0);
$pdf->Output();
?>