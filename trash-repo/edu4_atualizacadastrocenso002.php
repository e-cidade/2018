<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$oPdf = new Pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "CENSO ESCOLAR";
$head2 = "Importação de Informações do Censo Escolar";
$oPdf->ln(5);
$oPdf->addpage('L');
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',7);
$pPonteiro = fopen($sArquivoErro,"r");
$lCor      = false;
$iCont     = 0;
while (!feof($pPonteiro)) {
	
  $iCont++;
  if ($lCor == true) {
    $lCor = false;
  } else {
    $lCor = true;
  }
  
  $sLinha = fgets($pPonteiro,2000);
  $oPdf->multicell(280,4,trim($sLinha),0,"J",$lCor,0);
  
  if ($iCont == 1) {
    $sCabecalho = trim($sLinha);
 }
 
 if ($oPdf->gety() > $oPdf->h - 30) {
 	
   $oPdf->addpage('L');
   $oPdf->multicell(280,4,$sCabecalho,0,"J",1,0);
   $oPdf->multicell(280,4,"",0,"J",0,0);
   $lCor = "";
   
 }
}
fclose($pPonteiro);
$oPdf->Output();
?>