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

include("fpdf151/pdfwebseller.php");
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "CENSO ESCOLAR";
$head2 = "Importação Matrícula INEP";
$pdf->ln(5);
$pdf->addpage('L');
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$ponteiro = fopen($arquivo_erro,"r");
$cor1 = "1";
$cor2 = "0";
$cor = "";
$cont = 0;
while (!feof($ponteiro)){
 $cont++;
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $linha = fgets($ponteiro,2000);
 $pdf->multicell(280,4,trim($linha),0,"J",$cor,0);
 if($cont==1){
  $cabeçalho = trim($linha);
 }
 if($pdf->gety() > $pdf->h - 30){
  $pdf->addpage('L');
  $pdf->multicell(280,4,$cabeçalho,0,"J",1,0);
  $pdf->multicell(280,4,"",0,"J",0,0);
  $cor = "";
 }
}
fclose($ponteiro);
$pdf->Output();
?>