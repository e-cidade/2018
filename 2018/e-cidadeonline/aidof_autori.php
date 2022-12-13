<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include('fpdf151/pdfdbpref.php');
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$pdf = new PDF1(); // abre a classe
$Letra = 'arial';
$pdf->SetFont($Letra,'B',11);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);

$pdf->SetFillColor(235);

$pdf->Ln(3);
$pdf->SetFont($Letra,'BI',14);
$pdf->MultiCell(0,6,'Autorização para impressão de documentos fiscais- AIDOF ',0,"C",0);
$pdf->SetFont($Letra,'B',12);
$pdf->Ln(3);
$pdf->MultiCell(0,6,'EMPRESA SOLICITANTE :',0,"J",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',10);
$result = db_query("select * from cgm inner join issbase on q02_numcgm = z01_numcgm where q02_inscr = $inscricao");
if(pg_numrows($result) > 0){
  db_fieldsmemory($result,0);
}
$pdf->MultiCell(0,6,'Nome : '.@$z01_nome,0,"J",0);
$pdf->MultiCell(0,6,'CNPJ : '.@$z01_cgccpf,0,"J",0);
$pdf->MultiCell(0,6,'Endereço : '.@$z01_ender,0,"J",0);
$pdf->MultiCell(0,6,'Número : '.@$z01_numero,0,"J",0);
$pdf->MultiCell(0,6,'Complemento : '.@$z01_compl,0,"J",0);
$pdf->MultiCell(0,6,'Bairro : '.@$z01_bairro,0,"J",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'B',12);
$pdf->Ln(3);
$pdf->MultiCell(0,6,'GRÁFICA :',0,"J",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',10);
$result = db_query("select * from cgm where z01_numcgm = $grafica");
if(pg_numrows($result) > 0){
  db_fieldsmemory($result,0);
}
$pdf->MultiCell(0,6,'Nome : '.@$z01_nome,0,"J",0);
$pdf->MultiCell(0,6,'CNPJ : '.@$z01_cgccpf,0,"J",0);
$pdf->MultiCell(0,6,'Endereço : '.@$z01_ender,0,"J",0);
$pdf->MultiCell(0,6,'Número : '.@$z01_numero,0,"J",0);
$pdf->MultiCell(0,6,'Complemento : '.@$z01_compl,0,"J",0);
$pdf->MultiCell(0,6,'Bairro : '.@$z01_bairro,0,"J",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'B',14);
$pdf->Ln(3);
$pdf->MultiCell(0,6,'DADOS AIDOF :',0,"J",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',10);
$result = db_query("select * from aidof where y08_codigo = $codigo");
if(pg_numrows($result) > 0){
  db_fieldsmemory($result,0);
}
$pdf->MultiCell(0,6,'codigo : '.@$codigo ,0,"J",0);
$pdf->MultiCell(0,6,'Tipo de Nota : '.@$nota,0,"J",0);
$pdf->MultiCell(0,6,'Quantidade de Notas autorizadas : '.(@$y08_quantlib),0,"J",0);
$pdf->MultiCell(0,6,'Nota Inicial : '.@$y08_notain.' até Nota Final : '.@$y08_notafi,0,"J",0);
$pdf->MultiCell(0,6,'Observações : '.@$y08_obs,0,"L",0);
$pdf->Ln(3);
$pdf->output();
?>