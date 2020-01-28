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

include("libs/JSON.php");
include("fpdf151/pdf.php");

db_postmemory($_GET);

$objJSON = new Services_JSON();

$aHistoricosNaoEncontrados = db_getsession("aHistoricosNaoEncontrados");
db_destroysession('aHistoricosNaoEncontrados');

$sDataArquivo = implode('/', array_reverse( explode("-",$dataarq) ) );

$head1 = "INCONSISTÊNCIAS DE MOVIMENTOS BANCARIOS ";
$head2 = "";
$head3 = "Nome do Arquivo : ".$nomearq;
$head4 = "Data do Arquivo : ".$sDataArquivo;
$head5 = "Código do Banco : ".$codbanco;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;


foreach($aHistoricosNaoEncontrados as $iCodicoHistorico => $sHistorico) {
  
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(50,$alt,  "Codigo do Movimento Bancário" ,1,0,"C",1);
    $pdf->cell(140,$alt, "Descrição"                    ,1,1,"C",1);
    $troca = 0;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(50,$alt ,$iCodicoHistorico ,0,0,"C",0);
  $pdf->cell(140,$alt,$sHistorico       ,1,1,"C",0);

  $total ++;
}
//exit;

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',1,"L",0);

$pdf->ln();
$pdf->MultiCell( 190, 6,"ERROS PROVAVEIS : ",                                                                             "LRT","L");
$pdf->MultiCell( 190, 6," - Foram encontrados históricos de lancamento não cadastrados no sistema",                       "LR","L");
$pdf->MultiCell( 190, 6,"Para cadastra-los acesse o menu Caixa > Cadastros > Cadastros de Movimento Bancário > Inclusão", "LRB","L");
$pdf->ln();

$pdf->output();

?>