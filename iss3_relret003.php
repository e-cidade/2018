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
//db_postmemory($HTTP_SERVER_VARS);
$sql = "select uf, db12_extenso, logo, munic 
			from db_config  
				inner join db_uf on db12_uf = uf
			where codigo = ".db_getsession("DB_instit");
			
$result = pg_query($sql);
db_fieldsmemory($result,0);

$pdf = new SCPDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->Image("imagens/files/".$logo,137,1,20);
//$this->Image('imagens/files/'.$logo,2,3,30);
$pdf->Ln(20);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(0,4,$db12_extenso,0,"C",0);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,6,'PREFEITURA MUNICIPAL DE '.strtoupper($munic),0,"C",0);
$pdf->Ln(3);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(0,4,'RELATÓRIO DE RETENÇÃO DE ISSQN DA COMPETÊNCIA ___________ / _________________',0,"C",0);
$pdf->Ln(3);
$pdf->SetFillColor(235);
$pdf->Cell(270,6,'DADOS DO TOMADOR DO SERVIÇO',1,1,"C",1);
$pdf->SetFont('Arial','',8);
$pdf->Cell(150,6,'NOME OU RAZÃO SOCIAL:','LTB',0,"L",0);
$pdf->Cell(120,6,'INSCRIÇÃO MUNICIPAL:','TRB',1,"L",0);
//$pdf->Cell(270,6,'Endereço:',1,1,"L",0);
//$pdf->Cell(270,6,'Atividade:',1,1,"L",0);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(270,6,'DADOS DO PRESTADOR DO SERVIÇO',1,1,"C",1);
$pdf->SetFont('Arial','',8);
$pdf->Cell(40,4,'CNPJ','LRT',0,"C",0);
$pdf->Cell(20,4,'Inscrição','LRT',0,"C",0);
$pdf->Cell(70,4,'Nome ou Razão Social','LRT',0,"C",0);
$pdf->Cell(60,4,'Serviço Prestado','LRT',0,"C",0);
$pdf->Cell(20,4,'N'.chr(176).' e Série da','LRT',0,"C",0);
$pdf->Cell(30,4,'Valor da','LRT',0,"C",0);
$pdf->Cell(30,4,'Valor do','LRT',1,"C",0);
$pdf->Cell(40,4,'','LRB',0,"C",0);
$pdf->Cell(20,4,'Municipal *','LRB',0,"C",0);
$pdf->Cell(70,4,'','LRB',0,"C",0);
$pdf->Cell(60,4,'','LRB',0,"C",0);
$pdf->Cell(20,4,'Nota Fiscal','LRB',0,"C",0);
$pdf->Cell(30,4,'Serviço','LRB',0,"C",0);
$pdf->Cell(30,4,'Imposto','LRB',1,"C",0);
for ($i = 0;$i < 14;$i++){
   $pdf->Cell(40,6,'',1,0,"C",0);
   $pdf->Cell(20,6,'',1,0,"C",0);
   $pdf->Cell(70,6,'',1,0,"C",0);
   $pdf->Cell(60,6,'',1,0,"C",0);
   $pdf->Cell(20,6,'',1,0,"C",0);
   $pdf->Cell(30,6,'',1,0,"C",0);
   $pdf->Cell(30,6,'',1,1,"C",0);
}
$pdf->Ln(3);
//$pdf->Cell(270,6,'DADOS DO PAGAMENTO',1,1,"C",1);
$pdf->SetFont('Arial','',8);
//$pdf->Cell(270,6,'Data do Pagamento:','LTB',1,"L",0);
//$pdf->Cell(170,6,'Local do Pagamento(Banco)','TRB',1,"L",0);
//$pdf->Cell(270,6,'N'.chr(176).' do Documento',1,1,"L",0);
$pdf->Ln(3);
$pdf->Cell(270,6,ucwords(strtolower($munic)).', ________ de _______________________ de __________.',0,1,"L",0);
$pdf->Ln(3);
//$pdf->Cell(270,2,'______________________________________________',0,1,"L",0);
//Busca numero de dias para venciemnto 
$sql = "select *  from db_confplan;";
$result = pg_query($sql);
db_fieldsmemory($result,0);

$pdf->Cell(270,3,'Nome e Assinatura do Reponsável pelas Informações',0,1,"L",0);
$pdf->Text(10,190,'O pagamento do imposto deverá ser efetuado até o dia '.$w10_dia.' do mês subseqüente ao da competência e a respectiva guia de recolhimento solicitada junto ao Setor de Tributos, mediante a apresentação deste relatório.');
$pdf->Text(10,195,'* Preenchimento obrigatório apenas para empresas sediadas no Município de '.ucwords(strtolower($munic)).' - '.$uf);
$pdf->Output();
?>