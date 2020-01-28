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

include("classes/db_orcprojativ_classe.php");

$clorcprojativ = new cl_orcprojativ;
$clorcprojativ->rotulo->label();


if($tipo=="0"){
  $tipod = "OPERAÇÕES ESPECIAIS";
  $Rtipod = "Operações";
}else if($tipo=="1"){
  $tipod = "PROJETOS";
  $Rtipod = "Projetos";
}else{
  $tipod = "ATIVIDADES";
  $Rtipod = "Atividades";
}

$result = $clorcprojativ->sql_record($clorcprojativ->sql_query(null,null,"*","o55_projativ"," o55_anousu = ".db_getsession("DB_anousu")." and o55_tipo = $tipo "));

$head3 = "RELATÓRIO DE $tipod";
$head5 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem '.$tipod.' cadastradas.');
   exit;
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
      $pdf->cell(20,$alt,$Rtipod,1,0,"C",1);
      $pdf->cell(60,$alt,$RLo55_descr,1,1,"L",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,db_formatar($o55_projativ,'projativ'),0,0,"C",0);
   $pdf->cell(60,$alt,$o55_descr,0,1,"L",0);
}
$pdf->Output();
?>