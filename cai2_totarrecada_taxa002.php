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
require("fpdf151/pdf.php");
include("classes/db_iptuconstr_classe.php");
include("classes/db_iptuconstrdemo_classe.php");
db_postmemory($HTTP_SERVER_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$where = " where ";
$total = 0;
$and = "";
$alt = 5;
$borda = 0;
$corfundo = "";


$sql = " select r.k00_numcgm,z01_nome,k00_histtxt,r.k00_receit,r.k00_codsubrec,sum(r.k00_valor)
         from tabdesc 
              inner join recibo r on codsubrec = r.k00_codsubrec 
              inner join cgm on r.k00_numcgm = z01_numcgm 
              left join arrehist h on h.k00_numpre = r.k00_numpre 
         where  1 = 1      ";

if($taxa!=0){
  $sql .= " and k00_codsubrec = $taxa ";
}

if($receita!=0){
  $sql .= " and k07_codigo = $receita ";
}

$sql .="

         and r.k00_numpre in (select distinct r.k00_numpre from recibo r inner join arrepaga a on a.k00_numpre = r.k00_numpre where k00_dtpaga between '$datai' and '$dataf')
         group by r.k00_numcgm,r.k00_receit,z01_nome,r.k00_codsubrec,k00_histtxt 
         order by z01_nome
       ";


//die($sql);
$rsResult = pg_query($sql); 
$numrows  = pg_num_rows($rsResult);
if ($numrows == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem valores a serem listados para o filtro selecionado.');
    exit;
}

$head4 = "Periodo : ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');
$head5 = "Total da receita Processada/Arrecadada";

$pdf = new pdf();
$pdf->SetFillColor(255);
$pdf->Open();
$pdf->AliasNbPages();

for($i = 0; $i < $numrows;$i++){
  db_fieldsmemory($rsResult,$i);

  if ($pdf->gety() > $pdf->h - 30 || $i==0){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',9);
      $pdf->SetFillColor(210);
      $pdf->cell(20,$alt+1,"Numcgm",1,0,"L");
      $pdf->cell(80,$alt+1,"Nome",1,0,"L");
      $pdf->cell(10,$alt+1,"Rec",1,0,"L");
      $pdf->cell(10,$alt+1,"Taxa",1,0,'L');
      $pdf->cell(30,$alt+1,"Valor Arrecadado",1,0,"R");
      $pdf->cell(100,$alt+1,"Histórico Recibo",1,1,"L",0);
   }
   $pdf->setfont('arial','',8);
   
   $pdf->cell(20,$alt+1,$k00_numcgm,0,0,"L");
   $pdf->cell(80,$alt+1,$z01_nome,0,0,"L");
   $pdf->cell(10,$alt+1,$k00_receit ,0,0,"L");
   $pdf->cell(10,$alt+1,$k00_codsubrec,0,0,'L');
   $pdf->cell(30,$alt+1,db_formatar($sum,'f'),0,0,"R");
   $pdf->MultiCell(100,5,"$k00_histtxt");

   $total += $sum;

}
$pdf->setfont('arial','b',8);
$pdf->cell(175,$alt,"TOTAL GERAL : ".db_formatar($total,'f'),'T',0,"R",0);
$pdf->output();
?>