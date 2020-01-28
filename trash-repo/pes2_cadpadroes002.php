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
include("classes/db_rhcadregime_classe.php");
$clrhcadregime = new cl_rhcadregime;
$clrotulo = new rotulocampo;
$clrotulo->label('r02_regime');
$clrotulo->label('r02_descr');
$clrotulo->label('r02_codigo');
$clrotulo->label('r02_hrssem');
$clrotulo->label('r02_hrsmen');
$clrotulo->label('r02_tipo');
$clrotulo->label('r02_form');
$clrotulo->label('r02_valor');
$clrotulo->label('rh52_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CADASTRO DE PADRÕES";
$head4 = "PERÍODO : ".$mes." / ".$ano;

if($regime == 'c'){
  $head6 = 'REGIME : CLT ';
  $where = " and r02_regime = 2 ";
}elseif($regime == 'e'){
  $head6 = 'REGIME : ESTATUTÁRIO ';
  $where = " and r02_regime = 1 ";
}elseif($regime == 'e'){
  $head6 = 'REGIME : FUNÇÃO EM COMISSÃO ';
  $where = " and r02_regime = 3 ";
}else{
  $head6 = 'REGIME : TODOS ';
  $where = " ";
}
if($ordem == 'a'){
  $xordem = " order by r02_descr ";
  $head6  = "Ordem Alfabética";
}else{
  $xordem = " order by r02_codigo ";
  $head6  = "Ordem Numérica";
}
$sql = "
        select *
	from padroes 
	where r02_anousu = $ano 
	  and r02_mesusu = $mes 
      and r02_instit = ".db_getsession("DB_instit")."
	  $where 
	$xordem
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
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLr02_regime,1,0,"C",1);
      $pdf->cell(40,$alt,$RLrh52_descr,1,0,"C",1);
      $pdf->cell(70,$alt,$RLr02_descr,1,0,"C",1);
      $pdf->cell(30,$alt,$RLr02_valor,1,0,"C",1);
      $pdf->cell(30,$alt,$RLr02_hrssem,1,0,"R",1);
      $pdf->cell(30,$alt,$RLr02_hrsmen,1,0,"R",1);
      $pdf->cell(30,$alt,$RLr02_tipo,1,0,"R",1);
      $pdf->cell(30,$alt,$RLr02_form,1,1,"R",1);
//      $total = 0;
      $troca = 0;
   }

   $result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file($r02_regime));
   db_fieldsmemory($result_regime, 0);

   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$r02_regime,0,0,"C",0);
   $pdf->cell(40,$alt,$rh52_descr,0,0,"L",0);
   $pdf->cell(70,$alt,$r02_descr,0,0,"L",0);
   $pdf->cell(30,$alt,db_formatar($r02_valor,'f'),0,0,"L",0);
   $pdf->cell(30,$alt,$r02_hrssem,0,0,"L",0);
   $pdf->cell(30,$alt,$r02_hrsmen,0,0,"L",0);
   $pdf->cell(30,$alt,$r02_tipo,0,0,"L",0);
   $pdf->cell(30,$alt,$r02_form,0,1,"L",0);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);

$pdf->Output();
   
?>