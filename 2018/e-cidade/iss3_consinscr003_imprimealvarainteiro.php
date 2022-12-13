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

include("fpdf151/scpdf.php");
db_postmemory($HTTP_SERVER_VARS);

$sql = " select * from empresa where q02_inscr = $inscricao" ;
$result = pg_exec($sql);
db_fieldsmemory($result,0);
 
$pdf = new SCPDF();
$pdf->Open(); 
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
//$pdf->SetFillColor(24,135,18);
$pdf->SetFont('Arial','B',12);
$coluna = 60;
if ( $q07_perman == 'f' ){
   $linha = 60;
   $pdf->Text($coluna+65,$linha,db_getsession('DB_anousu'));
   $pdf->Text($coluna+40,$linha+90,date('d'));
   $pdf->Text($coluna+55,$linha+90,strtoupper(db_mes( date('m') )));
   $pdf->Text($coluna+97,$linha+90,date('Y'));
}else {
   $linha = 45;
//   $pdf->setfillcolor(255,255,255);
   $pdf->SetLineWidth(1);
   $pdf->RoundedRect(37,0.2,137,195,2,'1234');
   $pdf->SetLineWidth(0.5);
   $pdf->roundedrect(39,2,133,191,2,'1234');
   $pdf->SetLineWidth(0.2);
   $pdf->Image('imagens/files/Brasao.png',43,5,20);
   $pdf->Image('imagens/files/Brasao.jpg',60,30,100);
   $pdf->roundedrect(50,$linha+35,110,40,2,'1234');
   $pdf->setdrawcolor(235);
   $pdf->setxy(65,5);
   $pdf->setfont('Arial','B',13);
   $pdf->Multicell(0,8,'PREFEITURA MUNICIPAL DE SAPIRANGA',"C");
   $pdf->setxy(60,30);
   $pdf->setfont('Arial','IU',13);
   $pdf->Multicell(0,8,'LICENÇA PROVISÓRIA DE ATIVIDADE',"C");
   $pdf->setleftmargin(50);
   $pdf->setrightmargin(50);
   $pdf->sety(50);
   $pdf->SetFont('Arial','',9);
   $pdf->Multicell(0,6,'A Prefeitura Municipal de Sapiranga, através do competente setor, de acordo com a Lei Muncipal n'.chr(186).' 2310/97, concede licença provisória para localização e/ou funcionamento de atividades neste Município, ao contribuinte abaixo identificado.');
   $pdf->SetFont('Arial','B',12);
   $pdf->Text($coluna+20,$linha+103,"Sapiranga, ".date('d')." de ".db_mes( date('m') )." de ".date('Y'));
   $pdf->sety(125);
   $pdf->SetFont('Arial','',9);
   $pdf->Multicell(0,6,'Observação: A presente licença é de caráter provisório, com o prazo de vencimento de 01 ano.');
   $pdf->setfont('arial','',6);
   $pdf->Text(41,165,'..........................................................................................');
   $pdf->text(46,168,'SECRETÁRIO DA IND. COM. E TURISMO');
   $pdf->text(118,165,'.........................................................................................');
   $pdf->text(135,168,'AGENTE FISCAL');
   $pdf->sety(180);
   $pdf->setfont('arial','B',12);
   $pdf->multicell(0,8,'FIXAR EM LUGAR VISÍVEL',1,"C");
//   $pdf->Text($coluna+100,$linha+103, date('y') );
}
$pdf->SetFont('Arial','B',10);
$pdf->Text($coluna,$linha+48,'CCM : '.$q07_ativ.' / '.$q02_inscr);
$pdf->Text($coluna,$linha+52,$z01_nome);
$pdf->Text($coluna,$linha+56,$z01_ender.', '.$z01_numero);
$pdf->Text($coluna,$linha+60,$z01_compl);
$pdf->Text($coluna,$linha+64,$q03_descr);
$pdf->Output();

?>