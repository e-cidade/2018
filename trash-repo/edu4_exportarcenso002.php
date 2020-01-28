<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->ln(5);
$oPdf->addpage('L');
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',7);
$sPonteiro = fopen($arquivo_erro,"r");

$iCor1 = "1";
$iCor2 = "0";
$iCor  = "";
$iCont = 0;
$sHead1 = "CENSO ESCOLAR";
$sHead2 = "Geração de Arquivo de Exportação";

while (!feof($sPonteiro)) {
	
  $iCont++;
  if ($iCor == $iCor1) {
    $iCor = $iCor2;
  } else {
    $iCor = $iCor1;
  }
  
  $sLinha = fgets($sPonteiro,4096);
  $oPdf->multicell(280,4,trim($sLinha),0,"J",$iCor,0);
    
  if ($oPdf->gety() > $oPdf->h - 30) {
    $oPdf->addpage('L');
    $iCor = "";
  }
}

fclose($sPonteiro);
$oPdf->Output();