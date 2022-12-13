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


$where_ati = '';
if($tipo == 'r'){
  
  $xtipo = 'Todas';
  if($ativos != 'i'){
    $xtipo = 'Ativas';
    $where_ati = " and rh27_ativo = '$ativos'";
    if($ativos == 'f'){
      $xtipo = 'Inativas';
    }
  }
  
  if($base != ''){
    $where_ati .= " and r08_codigo = '$base' ";
    $head5 = 'BASE : '.$base.'-'.$descr_base;
  }

  
  $head3 = "RELATÓRIO DE RUBRICAS MARCADAS NAS BASES";
  $head7 = "TIPO : ".$xtipo;
  
  $sql = "
  select r08_codigo,
         r08_descr,
         rh27_rubric,
         rh27_descr
  from  bases
        inner join basesr     on r09_anousu = r08_anousu
                             and r09_mesusu = r08_mesusu 
                             and r09_base   = r08_codigo
                             and r09_instit = r08_instit
        inner join rhrubricas on r09_rubric = rh27_rubric
                             and r09_instit = rh27_instit
  
  where r08_anousu = $ano
    and r08_mesusu = $mes
    and r08_instit = ".db_getsession("DB_instit")."		 
        $where_ati
  order by r09_base, r09_rubric
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
  $base_diferente = '';
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->addpage();
        $pdf->setfont('arial','b',10);
        $pdf->cell(20,$alt,'BASE',1,0,"C",1);
        $pdf->cell(60,$alt,'DESCRIÇÃO DA BASE',"TBL",0,"C",1);
        $pdf->cell(30,$alt,'',"RTB",1,"C",1);
        $pdf->setfont('arial','',7);
        $pdf->cell(30,$alt,'',"LTB",0,"C",1);
        $pdf->cell(20,$alt,'RUBRICA',"TBR",0,"C",1);
        $pdf->cell(60,$alt,'DESCRIÇÃO DA RUBRICA',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($base_diferente != $r08_codigo){
       $pre = 0;
       $pdf->cell(30,$alt,'',0,1,"C",0);
       $pdf->setfont('arial','b',10);
       $pdf->cell(20,$alt,$r08_codigo,0,0,"C",$pre);
       $pdf->cell(60,$alt,$r08_descr,0,0,"L",$pre);
       $pdf->cell(30,$alt,'',0,1,"C",$pre);
       $base_diferente = $r08_codigo;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(30,$alt,'',0,0,"C",$pre);
     $pdf->cell(20,$alt,$rh27_rubric,0,0,"C",$pre);
     $pdf->cell(60,$alt,$rh27_descr,0,1,"L",$pre);
     $total += 1;
  //   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
  //$pdf->cell(20,$alt,'',"T",0,"C",0);
  //$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
}else{
  
  $head3 = "RELATÓRIO DE BASES UTILIZADAS EM FÓRMULAS";
  $head5 = 'BASE : '.$base.'-'.$descr_base;

  $xtipo = 'Todas';
  if($ativos != 'i'){
    $xtipo = 'Ativas';
    $where_ati = " and rh27_ativo = '$ativos'";
    if($ativos == 'f'){
      $xtipo = 'Inativas';
    }
  }
  $head7 = "TIPO : ".$xtipo;
  
  $sql = "
          select rh27_rubric,
                 rh27_descr,
                 case when rh27_form  like '%$base%' then rh27_form  else '' end as form1,
                 case when rh27_form2 like '%$base%' then rh27_form2 else '' end as form2,
                 case when rh27_form3 like '%$base%' then rh27_form3 else '' end as form3

          from rhrubricas
          where 
                rh27_instit = ".db_getsession("DB_instit")."		 
            and (rh27_form  like '%$base%'
             or rh27_form2 like '%$base%'
             or rh27_form3 like '%$base%')
            $where_ati
          order by rh27_rubric
         ";
  //echo $sql ; exit;
  
  $result = pg_exec($sql);
  $xxnum = pg_numrows($result);
  if ($xxnum == 0){
     db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Nenhuma Rubrica com a base '.$base.' em suas fórmulas.');
  
  }
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;
  $base_diferente = '';
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->addpage();
        $pdf->setfont('arial','b',10);
        $pdf->cell(20,$alt,'RUBRICA',1,0,"C",1);
        $pdf->cell(70,$alt,'DESCRIÇÃO DA RUBRICA',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','b',8);
     $pdf->cell(20,$alt,$rh27_rubric,0,0,"C",$pre);
     $pdf->cell(70,$alt,$rh27_descr,0,1,"L",$pre);
     $pdf->setfont('arial','',7);
     if($form1 != ''){
       $pdf->cell(20,$alt,'',0,0,"L",$pre);
       $pdf->cell(70,$alt,'FÓRMULA 1 : '.$form1,0,1,"L",$pre);
     }
     if($form2 != ''){
       $pdf->cell(20,$alt,'',0,0,"L",$pre);
       $pdf->cell(70,$alt,'FÓRMULA 2 : '.$form2,0,1,"L",$pre);
     }
     if($form3 != ''){
       $pdf->cell(20,$alt,'',0,0,"L",$pre);
       $pdf->cell(70,$alt,'FÓRMULA 3 : '.$form3,0,1,"L",$pre);
     }
     $total += 1;
  //   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);

}
$pdf->Output();
     
?>