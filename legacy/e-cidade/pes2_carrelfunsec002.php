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

$head3 = "RELATÓRIO CADASTRAL";
$head5 = "MÊS  : ".$ano." / ".db_mes($mes,1) ;

$sql = "
select z01_nome, 
       rh01_regist, 
       rh01_nasc, 
       rh02_lota, 
       r70_estrut as o40_orgao,
       r70_descr as o40_descr,
       rh01_admiss,
       rh03_padrao,
       rh37_descr,
       rh30_regime,
       rh30_vinculo
from rhpessoalmov 
     inner join rhpessoal     on rh01_regist = rh02_regist 
     inner join cgm            on z01_numcgm = rh01_numcgm 
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes 
     inner join rhlota         on r70_codigo = rh02_lota
		                          and r70_instit = rh02_instit
     inner join rhfuncao       on rh37_funcao = rh01_funcao
		                          and rh37_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
		                          and rh30_instit = rh02_instit
     left  join rhpespadrao    on rh02_seqpes = rh03_seqpes
where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis is null 
  and r70_codigo between $lotaini and $lotafin
order by r70_estrut,z01_nome;
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cálculos para o período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca      = 1;
$alt        = 4;
$total_fun  = 0;
$xorgao     = 0;
$imp_cab = true;
$total_sec = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 35 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(15,$alt,'ADMISSAO',1,0,"C",1);
      $pdf->cell(50,$alt,'FUNCAO',1,0,"C",1);
      $pdf->cell(30,$alt,'PADRAO',1,0,"C",1);
      $pdf->cell(10,$alt,'REG.',1,0,"C",1);
      $pdf->cell(10,$alt,'VINC',1,1,"C",1);
      $troca = 0;
      $imp_cab = true;
   }
   if($xorgao != $o40_orgao || $imp_cab == true){
     if($xorgao != $o40_orgao && $xorgao != 0){
       $pdf->cell(190,$alt,'Total de funcionários '.$total_sec,0,1,"R",0);
       $total_sec = 0;
     }
     $antes = $xorgao;
     $xorgao = $o40_orgao;
     if($quebra == "t" && $antes != $o40_orgao && $antes != 0){
       $troca = 1;
       $x --;
       continue;
     }
     $pdf->setfont('arial','b',8);
     $pdf->cell(0,$alt,'SECRETARIA :  '.$o40_orgao.' - '.$o40_descr,0,1,"L",0);
     $pre = 1;
     $imp_cab = false;
   }
   $total_sec ++;
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(15,$alt,db_formatar($rh01_admiss,'d'),0,0,"R",$pre);
   $pdf->cell(50,$alt,$rh37_descr,0,0,"L",$pre);
   $pdf->cell(30,$alt,$rh03_padrao,0,0,"L",$pre);
   $pdf->cell(10,$alt,$rh30_regime,0,0,"L",$pre);
   $pdf->cell(10,$alt,$rh30_vinculo,0,1,"L",$pre);
   $total_fun   += 1;
}
$pdf->cell(190,$alt,'Total de funcionários '.$total_sec,"T",1,"R",0);
$pdf->setfont('arial','b',8);
$pdf->cell(115,$alt,'TOTAL :  '.$total_fun.' FUNCIONÁRIOS ',0,0,"C",0);

$pdf->Output();
   
?>