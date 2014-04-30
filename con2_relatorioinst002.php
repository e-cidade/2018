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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
$pdf = new PDF(); 
$pdf->Open(); 
///////instituicoes
if($codigo!=""){
  $sql = "select nomeinst as nome, url as site, * from db_config where codigo = $codigo order by codigo"; 
}else{
  $sql = "select nomeinst as nome, url as site, * from db_config order by codigo"; 
}
$result = pg_exec($sql);
$num = pg_numrows($result);
$pdf->AliasNbPages(); 
$head3 = "RELATÓRIO DE INSTITUIÇÕES";
$linha = 0;
$pdf->ln(2);
$pre = 0;
$larg = 45;
$altlinha = 3.7;
$total_fixo = 0;
$total_geral = 0;
$pagina = 0;
$pdf->AddPage(); 
for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i,true);
if(($i%3 == 0) && ($i!=0)){
  $pdf->AddPage(); 
}
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(220);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell("$larg",$altlinha,"NOME:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($nome),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"ENDEREÇO:",0,0,"L",1);
  $pdf->Multicell(0,$altlinha,strtoupper($ender),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"MUNICÍPIO:",0,0,"L",1);
  $pdf->Multicell(0,$altlinha,strtoupper($munic),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"UF:",0,0,"L",1);
  $pdf->Multicell(0,$altlinha,strtoupper($uf),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"TELEFONE:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($telef),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"E-MAIL:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($email),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"IDENTIFICAÇÃO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($ident),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"TAXA BANCÁRIA:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($tx_banc),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"NÚMERO DO BANCO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($numbanco),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"SITE:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($site),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"LOGO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($logo),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"FIGURA:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($figura),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"DATA DA CONTABILIDADE:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($dtcont),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"DIARIO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($diario),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"PREFEITO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($pref),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"VICE-PREFEITO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($vicepref),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"FAX:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($fax),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"CGC:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($cgc),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"CEP:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($cep),0,"L",$pre);
  $pdf->Cell("$larg",$altlinha,"BAIRRO:",0,0,"L",1);
  $pdf->MultiCell(0,$altlinha,strtoupper($bairro),0,"L",$pre);
  $pdf->Cell(190,4,"","B",1,"C",$pre);
  $pdf->ln(2);
}
$total = $num;
$pdf->SetFont('Arial','B',7);
$pdf->Cell(50,6,"TOTAL : ".$total." Instituição(ções) encontrada(s)",0,0,"L",0);
$pdf->Output();
?>