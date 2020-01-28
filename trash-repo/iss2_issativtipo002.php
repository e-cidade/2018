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
$clrotulo->label('q85_descr');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
/*
$xordem = '';
if($ordem == 'n'){
  if($tipo_ordem == 'a'){
    $xordem = ' ';
    $head5 = "ORDEM : NUMÉRICA - ASCENDENTE";
  }
  else{
    $xordem = '';
    $head5 = "ORDEM : NUMÉRICA - DESCENDENTE";
  }
}
elseif($ordem == 'a'){
  if($tipo_ordem == 'a'){
    $xordem = '';
    $head5 = "ORDEM : ALFABÉTICA - ASCENDENTE";
  }
  else{
    $xordem = ' m60_descr desc ';
    $head5 = "ORDEM : ALFABÉTICA - DESCENDENTE";
  }
} 
*/
$head3 = " ";

$result =  pg_query("select q02_inscr,z01_nome,q85_descr,q03_descr  from issbase inner join isscalc on q02_inscr = q01_inscr inner join cadcalc on q01_cadcal = q85_codigo inner join tabativ on q07_inscr = q02_inscr inner join ativprinc on q88_inscr = q02_inscr and q07_seq = q88_seq left join tabativbaixa on q11_inscr = q02_inscr and q11_seq = q07_seq inner join ativid on q03_ativ = q07_ativ inner join cgm on z01_numcgm = q02_numcgm where q01_anousu = 2007 and q85_codigo in (2,3) and q11_inscr is null order by z01_nome");


$xxnum = pg_numrows($result);
if ($xxnum == 0){
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

for($x = 0; $x < pg_numrows($result);$x++)
{
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
   {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Inscrição",1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(30,$alt,"$RLq85_descr ",1,0,"C",1);
      $pdf->cell(70,$alt,"Atividade",1,1,"C",1);
      $troca = 0;
      $p = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$q02_inscr,0,0,"C",$p);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$p);
   $pdf->cell(30,$alt,$q85_descr,0,0,"L",$p);
   $pdf->cell(70,$alt,$q03_descr,0,1,"L",$p);
   if ($p==0){
     $p=1;
   }else{
     $p=0;
   }
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>