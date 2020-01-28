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

 define('FPDF_FONTPATH','fpdf151/font/');
require("fpdf151/fpdf.php");
$pdf = new fpdf("P","mm","Legal");
$pdf->open();
$pdf->AddPage();
$pdf->SetAutoPageBreak("on",0);
//$pdf->s
$pdf->setx(30);
$pdf->sety(2);
//$y = 25;
//$x = 84;
$y       = 0;
$x       = 0;
$vinicio = $_GET["vini"];
$vfim    = $_GET["vfim"];
$pag     = 0;

for ($i = $vinicio;$i <= $vfim;$i++){
    if ($pag == 12){
        $pag = 0;
        $y   = 0;
        $pdf->AddPage();
    }    
    $pdf->SetFont("Times","b",12);
    $pdf->Text(55,10+$y,"Protocolo");
    $pdf->text(23,15+$y,"Nº:");
    $pdf->SetFont("Times","b",12);
    $pdf->text(30,15+$y,$i);
    $pdf->line(28,15+$y,48,15+$y);
    $pdf->SetFont("Times","b",12);
    $pdf->Text(48,15+$y,"/".date("y"));
    $pdf->SetFont("Times","b",12);
    $pdf->Text(23,21+$y,"Data:____/____/____");
    $pdf->Text(23,27+$y,"Ass:________________________________");
//segunda etiqueta
    $pdf->SetFont("Times","b",12);
    $pdf->Text(155,10+$y,"Protocolo");
    $pdf->text(120,15+$y,"Nº:");
    $pdf->SetFont("Times","b",12);
    $pdf->text(127,15+$y,$i);
    $pdf->line(127,15+$y,147,15+$y);
    $pdf->SetFont("Times","b",12);
    $pdf->Text(147,15+$y,"/".date("y"));
    $pdf->SetFont("Times","b",12);
    $pdf->Text(120,21+$y,"Data:____/____/____");
    $pdf->Text(120,27+$y,"Ass:________________________________");
$y += 30; 
$pag++;   
}
$pdf->Output();
?>