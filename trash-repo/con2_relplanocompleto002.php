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

include("classes/db_conplano_classe.php");
include("fpdf151/pdf.php");

//db_postmemory($HTTP_SERVER_VARS,2);

$head1 = "RELATÓRIO PLANO DE CONTAS ";

//db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clconplano = new cl_conplano;
$clconplano->rotulo->label("c60_codcon");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");
$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");

$sql = $clconplano->sql_query("","","*","c60_estrut"," c60_estrut like '$c60_estrut%' and c60_anousu=".db_getsession("DB_anousu"));

$result = pg_exec($sql);
//db_criatabela($result);
$num = pg_numrows($result);
$linha = 60;
$pre = 0;
$total = 0;
$pagina = 0;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$preenc = 1;
$troca = 0;
for($i=0;$i<$num;$i++) {
   db_fieldsmemory($result,$i);
   if($pdf->gety() > $pdf->h - 30 || $troca == 0){
      $troca = 1;
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(25,4,$RLc60_estrut,1,0,"C",1);
      $pdf->Cell(10,4,$RLc60_codcon,1,0,"C",1);
      $pdf->Cell(100,4,$RLc60_descr,1,1,"C",1);
      $pdf->Ln(3);
   }
   if($preenc == 1){
     $preenc = 0;
   }else {
     $preenc = 1;
   }
   $pdf->SetFont('Arial','',7);
   $pdf->Cell(25,4,$c60_estrut,0,0,"C",$preenc);
   $pdf->Cell(10,4,$c60_codcon,0,0,"C",$preenc);
   $pdf->Cell(100,4,$c60_descr,0,1,"L",$preenc);
}

$pdf->Output();

?>