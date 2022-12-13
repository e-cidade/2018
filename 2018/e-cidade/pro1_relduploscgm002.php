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

include("libs/db_sql.php");
include("fpdf151/pdf.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$where = "";
if (@$processados == 'proc') {
  $where = " where z10_proc is true ";
} else if ($processados == 'nproc') {
  $where = " where z10_proc is false ";
}
$sql  = "select z01_numcgm, z01_nome, z10_proc, z10_codigo from cgmcorreto ";
$sql .= "			inner join cgm on cgmcorreto.z10_numcgm = cgm.z01_numcgm ";
$sql .= " $where order by $ordem";

//echo $sql;exit;
$result = pg_exec($sql);
$head1 = "CGM's CORRETOS";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->addpage();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->cell(15,05,"Codigo",1,0,"c",1);
$pdf->cell(15,05,"Numcgm",1,0,"c",1);
$pdf->cell(140,05,"Nome",1,0,"c",1);
$pdf->cell(20,05,"Processado",1,1,"c",1);

$pdf->setfont('arial','',8);
$total = 0;
for ($x=0; $x < pg_numrows($result); $x++) {
  db_fieldsmemory($result,$x);
  if ($pdf->gety() > $pdf->h - 35) {
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(15,05,"Codigo",1,0,"c",1);
    $pdf->cell(15,05,"Numcgm",1,0,"c",1);
    $pdf->cell(140,05,"Nome",1,0,"c",1);
    $pdf->cell(20,05,"Processado",1,1,"c",1);
    $pdf->setfont('arial','',8);
  }
  $processado = ($z10_proc=='t')?"Sim":"Nao";
  $pdf->cell(15,5,$z10_codigo,1,0,"C",0);
  $pdf->cell(15,5,$z01_numcgm,1,0,"C",0);
  $pdf->cell(140,5,$z01_nome,1,0,"L",0);
  $pdf->cell(20,5,$processado,1,1,"C",0);
  $sql  = "select z11_numcgm, z11_nome ".(isset($log)?", z12_log ":"");
  $sql .= "  from cgmerrado ";
  $sql .= "       left  join cgm          on z11_numcgm = z01_numcgm ";
  $sql .= "       inner join cgmcorreto   on z11_codigo = z10_codigo ";
  if(isset($log)) {
    $sql .= "       left  join cgmerradolog on z12_codigo = z11_codigo ";
    $sql .= "                              and z12_numcgm = z11_numcgm ";
  }
  $sql .= " where z11_codigo = $z10_codigo";
  //die($sql);
  $res = pg_exec($sql);
  if (pg_numrows($res) > 0) {
    for ($y=0; $y<pg_numrows($res); $y++) {
      db_fieldsmemory($res,$y);
      $pdf->setX(25);
      $pdf->cell(15,05,$z11_numcgm,1,0,"C",1);
      $pdf->cell(160,05,$z11_nome,1,1,"L",1);
      if (isset($log)) {
        if ($z12_log != "") {
          $pdf->setX(25);
          $pdf->Multicell(175,5,"<<<< LOG's >>>> \n".$z12_log,1,1,"L",0);
        }
      }
    }
  }
  $total += 1;
}
$pdf->cell(190,05,'Total de Registros:   '.$total,1,0,"c",1);
$pdf->Output();
?>