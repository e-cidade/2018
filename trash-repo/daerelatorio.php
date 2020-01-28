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

require('fpdf151/pdf.php');
$result = pg_exec("select * from db_dae where w04_codigo = $codigo");
if(pg_numrows($result) != 0){
  db_fieldsmemory($result,0);
}
$result = pg_exec("select * from issbase inner join cgm on q02_numcgm = z01_numcgm where q02_inscr = $w04_inscr");
if(pg_numrows($result) != 0){
  db_fieldsmemory($result,0);
}
$resultender = pg_exec("select * from db_daeend where w05_codigo = $codigo");
if(pg_numrows($resultender) != 0){
  db_fieldsmemory($resultender,0);
}
$resultsocios = pg_exec("select * from db_daesocios where w06_codigo = $codigo");
$resultval = pg_exec("select * from db_daevalores where w07_codigo = $codigo order by to_number(w07_mes,'99')");
$pdf = new PDF(); // abre a classe
$head1 = "DECLARAÇÃO ANUAL DE ISSQN";
$head2 = "EXERCÍCIO: $w04_ano"; 
$head3 = "DATA DE ENVIO: ".($w04_data != ""?db_formatar($w04_data,'d'):"");
$Letra = 'arial';
$pdf->SetFont($Letra,'B',11);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->Ln(10);
$pdf->Cell(3,1,"CONTRIBUINTE: ",0,0,"L",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',10);
$pdf->Cell(80,6,'NOME: '.@$z01_nome,0,0,"J",1);
$pdf->MultiCell(0,6,'CIDADE: '.@$z01_munic,0,"J",1,30);
$pdf->Cell(80,6,'ENDEREÇO: '.@$w05_rua,0,0,"J",1);
$pdf->MultiCell(0,6,'NÚMERO: '.@$w05_numero,0,"J",1,30);
$pdf->Cell(80,6,'COMPLEMENTO: '.@$w05_compl,0,0,"J",1);
$pdf->Cell(80,6,'BAIRRO: '.@$w05_bairro,0,0,"J",1);
$pdf->Ln(10);
$pdf->SetFont($Letra,'B',11);
$pdf->Cell(3,1,"SÓCIOS: ",0,0,"L",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'I',10);
if(pg_numrows($resultsocios)>1){
  for($i=0;$i<pg_numrows($resultsocios);$i++){
    db_fieldsmemory($resultsocios,$i);
    $pdf->Cell(50,6,'CNPJ/CPF: '.$w06_cgccpf,1,0,"J",1);
    $pdf->Cell(100,6,'Nome: '.$w06_nome,1,0,"J",1);
    $pdf->MultiCell(0,6,'RG: '.$w06_rg,1,"J",1);
    $pdf->Cell(110,6,'RUA: '.$w06_ender,1,0,"J",0);
    $pdf->Cell(40,6,'NÚMERO: '.$w06_numero,1,0,"J",0);
    $pdf->MultiCell(0,6,'COMPL: '.$w06_compl,1,"J",0);
    $pdf->Cell(80,6,'BAIRRO: '.$w06_bairro,1,0,"J",0);
    $pdf->Cell(30,6,'CEP: '.$w06_cep,1,0,"J",0);
    $pdf->Cell(40,6,'UF: '.$w06_uf,1,0,"J",0);
    $pdf->MultiCell(0,6,''.$w06_percent." %",1,"J",0);
  }  
}else{
  if(pg_numrows($resultsocios) != 0){
    db_fieldsmemory($resultsocios,0);
    $pdf->Cell(50,6,'CNPJ/CPF: '.$w06_cgccpf,1,0,"J",1);
    $pdf->Cell(100,6,'Nome: '.$w06_nome,1,0,"J",1);
    $pdf->MultiCell(0,6,'RG: '.$w06_rg,1,"J",1);
    $pdf->Cell(110,6,'RUA: '.$w06_ender,1,0,"J",0);
    $pdf->Cell(40,6,'NÚMERO: '.$w06_numero,1,0,"J",0);
    $pdf->MultiCell(0,6,'COMPL: '.$w06_compl,1,"J",0);
    $pdf->Cell(80,6,'BAIRRO: '.$w06_bairro,1,0,"J",0);
    $pdf->Cell(30,6,'CEP: '.$w06_cep,1,0,"J",0);
    $pdf->Cell(40,6,'UF: '.$w06_uf,1,0,"J",0);
    $pdf->MultiCell(0,6,''.$w06_percent." %",1,"J",0);
  }
}
$pdf->MultiCell(0,6,'','B','',0,30);
$pdf->SetFont($Letra,'',11);
$pdf->Ln(10);
$pdf->SetFont($Letra,'B',11);
$pdf->Cell(3,1,"VALORES: ",0,0,"L",0);
$pdf->Ln(3);
$pdf->SetFont($Letra,'',10);
if(pg_numrows($resultval)>1){
    $pdf->SetFillColor(200);
    $pdf->Cell(25,6,'Mês',1,0,"C",1);
    $pdf->Cell(40,6,'Valor',1,0,"C",1);
    $pdf->Cell(35,6,'Aliquota - %',1,0,"C",1);
    $pdf->Cell(40,6,'Imposto',1,0,"C",1);
    $pdf->Cell(50,6,'Data pgto:',1,1,"C",1);
    $total = 0;
    $totali = 0;
  for($i=0;$i<pg_numrows($resultval);$i++){
    db_fieldsmemory($resultval,$i);
    $pdf->SetFillColor(235);
    $pdf->Cell(25,6,''.db_mes($w07_mes),1,0,"C",1);
    $pdf->Cell(40,6,''.db_formatar($w07_valor,'f'),1,0,"R",0);
    $pdf->Cell(35,6,''.$w07_aliquota." %",1,0,"C",1);
    $pdf->Cell(40,6,''.db_formatar($w07_imposto,'f'),1,0,"R",0);
    if($w07_dtpaga != ""){
      $w07_dtpaga = db_formatar($w07_dtpaga,'d');
    }else{
      $w07_dtpaga = "Não efetuado";
    }  
    $pdf->Cell(50,6,''.$w07_dtpaga,1,1,"C",1);
    $total += $w07_valor;
    $totali += $w07_imposto;
  }  
  $pdf->Cell(65,6,''.db_formatar($total,'f'),1,0,"R",0);
  $pdf->Cell(35,6,'',1,0,"R",0);
  $pdf->Cell(40,6,''.db_formatar($totali,'f'),1,0,"R",0);
  $pdf->Cell(50,6,'',1,0,"R",0);
}else{
  if(pg_numrows($resultval) != 0){
    $total = 0;
    $totali = 0;
    $pdf->SetFillColor(200);
    $pdf->Cell(25,6,'Mês',1,0,"C",1);
    $pdf->Cell(40,6,'Valor',1,0,"C",1);
    $pdf->Cell(35,6,'Aliquota - %',1,0,"C",1);
    $pdf->Cell(40,6,'Imposto',1,0,"C",1);
    $pdf->Cell(50,6,'Data pgto:',1,1,"C",1);
    db_fieldsmemory($resultval,0);
    $pdf->SetFillColor(235);
    $pdf->Cell(25,6,''.db_mes($w07_mes),1,0,"C",1);
    $pdf->Cell(40,6,''.db_formatar($w07_valor,'f'),1,0,"R",0);
    $pdf->Cell(35,6,''.$w07_aliquota." %",1,0,"C",1);
    $pdf->Cell(40,6,''.db_formatar($w07_imposto,'f'),1,0,"R",0);
    if($w07_dtpaga != ""){
      $w07_dtpaga = db_formatar($w07_dtpaga,'d');
    }else{
      $w07_dtpaga = "Não efetuado";
    }
    $pdf->Cell(50,6,''.$w07_dtpaga,1,1,"C",1);
    $total += $w07_valor;
    $totali += $w07_imposto;
    $pdf->Cell(65,6,''.db_formatar(@$total,'f'),1,0,"R",0);
    $pdf->Cell(35,6,'',1,0,"R",0);
    $pdf->Cell(40,6,''.db_formatar(@$totali,'f'),1,0,"R",0);
    $pdf->Cell(50,6,'',1,0,"R",0);
  }  
}
$pdf->Ln(4);
if ( $pdf->GetY() > 270) {
  $pdf->AddPage();
  $pdf->Ln(40);
}
$pdf->output();
?>