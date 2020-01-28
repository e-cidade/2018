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

include("libs/db_conecta.php");
require('fpdf151/pdf.php');
db_postmemory($HTTP_GET_VARS);
$result = db_query("select * from db_dae where w04_codigo = $codigo");

if(pg_numrows($result) != 0){
  db_fieldsmemory($result,0);
}else{
	die("Dai não encontrada");
}
$result = db_query("select * from issbase inner join cgm on q02_numcgm = z01_numcgm where q02_inscr = $w04_inscr");
if(pg_numrows($result) != 0){
  db_fieldsmemory($result,0);
}

$resultender = db_query("select * from db_daeend where w05_codigo = $codigo");
if(pg_numrows($resultender) != 0){
  db_fieldsmemory($resultender,0);
}

$resulttomador = db_query("select * from db_daitomador left join db_daitomadorpaga on w08_sequencial=w09_daitomador where w08_dai = $codigo order by w08_mes");
$resultretido = db_query("select * from db_dairetido where w15_dai=$codigo order by w15_mes");

$resultsocios = db_query("select * from db_daesocios where w06_codigo = $codigo");
$resultval = db_query("select * from db_daevalores where w07_codigo = $codigo order by to_number(w07_mes,'99')");
$data = getdate();
$mes  = db_formatar($data['month'],'s',0,2,'e');
$mes1 = db_formatar($data['mon'],'s',0,2,'e');
$dia  = db_formatar($data['mday'],'s',0,2,'e');
$ano  = db_formatar($data['year'],'s',0,2,'e');
$hora = db_formatar($data['hours'],'s',0,2,'e');
$min  = db_formatar($data['minutes'],'s',0,2,'e');
$sec  = db_formatar($data['seconds'],'s',0,2,'e');
$pdf = new PDF(); // abre a classe
$head1 = "DECLARAÇÃO ANUAL DE ISSQN";
$head2 = "DAI NÃO ENVIADA";
$Letra = 'arial';
$pdf->Open(); // abre o relatorio
$pdf->SetFont('arial','B',10);
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->Ln(5);
$pdf->SetFont($Letra,'B',10);
$pdf->Cell(190,6,"INSCRIÇÃO: ".$w04_inscr,"0",0,"L",1);
$pdf->Ln(10);
$pdf->SetFont($Letra,'',8);
$pdf->Cell(110,6,'NOME: '.@$z01_nome,0,0,"J",0);
$pdf->MultiCell(0,6,'CIDADE: '.@$z01_munic,0,"J",0);
$pdf->Cell(110,6,'ENDEREÇO: '.@$w05_rua,0,0,"J",0);
$pdf->MultiCell(0,6,'NÚMERO: '.@$w05_numero,0,"J",0);
$pdf->Cell(110,6,'COMPLEMENTO: '.@$w05_compl,0,0,"J",0);
$pdf->Cell(0,6,'BAIRRO: '.@$w05_bairro,0,0,"J",0);
$pdf->Ln(10);
$pdf->MultiCell(0,6,'','B','',0);
$pdf->Ln(10);
$pdf->SetFont($Letra,'B',10);


if(pg_numrows($resultsocios)==0){
  $pdf->MultiCell(0,6,'SEM SÓCIOS LANÇADOS',0,"J",1);
}else{
  $pdf->Cell(3,1,"SÓCIOS: ",0,0,"L",1);
}
$pdf->Ln(3);
$pdf->SetFont($Letra,'',8);
if(pg_numrows($resultsocios)!=0){
  for($i=0;$i<pg_numrows($resultsocios);$i++){
    db_fieldsmemory($resultsocios,$i);
    $pdf->Cell(50,6,'CNPJ/CPF: '.$w06_cgccpf,1,0,"J",1);
    $pdf->Cell(100,6,'Nome: '.$w06_nome,1,0,"J",1);
    $pdf->MultiCell(0,6,'RG: '.$w06_rg,1,"J",1);
    $pdf->Cell(110,6,'Rua: '.$w06_ender,1,0,"J",0);
    $pdf->Cell(40,6,'Número: '.$w06_numero,1,0,"J",0);
    $pdf->MultiCell(0,6,'Complemento: '.$w06_compl,1,"J",0);
    $pdf->Cell(80,6,'Bairro: '.$w06_bairro,1,0,"J",0);
    $pdf->Cell(30,6,'CEP: '.$w06_cep,1,0,"J",0);
    $pdf->Cell(40,6,'UF: '.$w06_uf,1,0,"J",0);
    $pdf->MultiCell(0,6,'Percentual: '.$w06_percent." %",1,"J",0);
  }  
}

$pdf->MultiCell(0,6,'','B','',0);
$pdf->Ln(10);
$pdf->SetFont($Letra,'B',10);
if(pg_numrows($resultval)==0){
  $pdf->MultiCell(0,6,'SEM VALORES LANÇADOS',0,"J",1);
}else{ 
  $pdf->Cell(3,1,"VALORES: ",0,0,"L",1);
}
$pdf->Ln(3);
$pdf->SetFont($Letra,'',8);
if(pg_numrows($resultval)!=0){
    $pdf->SetFillColor(200);
    $pdf->Cell(25,6,'Mês',1,0,"C",1);
    $pdf->Cell(40,6,'Valor',1,0,"C",1);
    $pdf->Cell(35,6,'Alíquota - %',1,0,"C",1);
    $pdf->Cell(40,6,'Imposto',1,0,"C",1);
    $pdf->Cell(50,6,'Data pagto:',1,1,"C",1);
    $total = 0;
    $totali = 0;
  for($i=0;$i<pg_numrows($resultval);$i++){
    db_fieldsmemory($resultval,$i);
    $pdf->SetFillColor(235);
    $pdf->Cell(25,6,''.db_mes($w07_mes),1,0,"C",0);
    $pdf->Cell(40,6,''.db_formatar($w07_valor,'f'),1,0,"R",0);
    $pdf->Cell(35,6,''.$w07_aliquota." %",1,0,"C",0);
    $pdf->Cell(40,6,''.db_formatar($w07_imposto,'f'),1,0,"R",0);
	    if($w07_dtpaga != ""){
	      $w07_dtpaga = db_formatar($w07_dtpaga,'d');
	    }else{
	      $w07_dtpaga = "Não efetuado";
	    }  
    $pdf->Cell(50,6,''.$w07_dtpaga,1,1,"C",0);
    $total += $w07_valor;
    $totali += $w07_imposto;
  }  
  $pdf->Cell(65,6,'TOTAL: '.db_formatar($total,'f'),1,0,"R",0);
  $pdf->Cell(35,6,'',1,0,"R",0);
  $pdf->Cell(40,6,''.db_formatar($totali,'f'),1,0,"R",0);
  $pdf->Cell(50,6,'',1,1,"R",0);
}

// dai retido

$pdf->MultiCell(0,6,'','B','',0);  //linha
$pdf->Ln(10);
$pdf->SetFont($Letra,'B',10);
$linhasprestador = pg_numrows($resultretido);
if($linhasprestador==0){
  $pdf->MultiCell(0,6,'SEM VALORES NA RETENÇÃO COMO PRESTADOR LANÇADOS',0,"J",1);
}else{ 
  $pdf->Cell(3,1,"RETENÇÃO COMO PRESTADOR: ",0,0,"L",1);
}
$pdf->Ln(3);
$pdf->SetFont($Letra,'',8);
  if($linhasprestador != 0){
    $total = 0;
    $totali = 0;
    $pdf->SetFillColor(200);
    $pdf->Cell(25,6,'Mês',1,0,"C",1);
    $pdf->Cell(40,6,'Valor',1,0,"C",1);
    $pdf->Cell(50,6,'CPF ou CNPJ',1,0,"C",1);
    $pdf->Cell(20,6,'Nota',1,0,"C",1);
    $pdf->Cell(20,6,'Série',1,0,"C",1);
    $pdf->Cell(35,6,'Data',1,1,"C",1);
    for($p=0;$p<$linhasprestador;$p++){
	    db_fieldsmemory($resultretido,$p);
	    $pdf->SetFillColor(235);
	    $pdf->Cell(25,6,''.db_mes($w15_mes),1,0,"C",0);
	    $pdf->Cell(40,6,''.db_formatar($w15_valreceita,'f'),1,0,"R",0);
	    $pdf->Cell(50,6,''.db_cgccpf($w15_cnpj),1,0,"R",0);
	    $pdf->Cell(20,6,''.$w15_nota,1,0,"C",0);
	    $pdf->Cell(20,6,''.$w15_serie,1,0,"C",0);
	    $pdf->Cell(35,6,''.db_formatar($w15_data,'d'),1,1,"C",0);    
	   
	    $total += $w15_valreceita;
    }  
	    $pdf->Cell(65,6,'TOTAL: '.db_formatar(@$total,'f'),1,0,"R",0);
	    $pdf->Cell(125,6,'',1,1,"R",0);
  }  

// dai tomador........
//$pdf->Ln(5);
$pdf->MultiCell(0,6,'','B','',0);  //linha
$pdf->Ln(10);
$pdf->SetFont($Letra,'B',10);
$linhastomador = pg_numrows($resulttomador); 
if($linhastomador==0){
  $pdf->MultiCell(0,6,'SEM VALORES NA RETENÇÃO COMO TOMADOR TOMADOR LANÇADOS',0,"J",1);
}else{ 
  $pdf->Cell(3,1,"RETENÇÃO COMO TOMADOR: ",0,0,"L",1);
}
$pdf->Ln(3);

$pdf->SetFont($Letra,'',8);

  if($linhastomador!= 0){
  	$total = 0;
	$totali = 0; 
	$totalvp= 0;
  	for($t=0;$t<$linhastomador;$t++){
  	    db_fieldsmemory($resulttomador,$t);
	    $pdf->SetFillColor(200);
	    $pdf->Cell(26,6,'Mês: '.db_mes($w08_mes),1,0,"L",1);
	    $pdf->Cell(50,6,'CPF ou CNPJ: '.db_cgccpf($w08_cnpj),1,0,"L",1);
	    $pdf->Cell(114,6,'Nome ou Razão Social: '.$w08_nome,1,1,"L",1);
	    $pdf->SetFillColor(235);
	    $pdf->Cell(114,6,'Serviço: '.$w08_servico,1,0,"L",0);
	    $pdf->Cell(38,6,'Nota: '.$w08_nota,1,0,"L",0);
	    $pdf->Cell(38,6,'Serie: '.$w08_serie,1,1,"L",0);
	    $pdf->Cell(38,6,'Valor: '.db_formatar($w08_valreceita,'f'),1,0,"L",0);
	    $pdf->Cell(38,6,'Alíquota: '.$w08_aliquota." %",1,0,"L",0);
	    $pdf->Cell(38,6,'Imposto: '.db_formatar($w08_imposto,'f'),1,0,"L",0);
	    $pdf->Cell(38,6,'Data do pagto: '.($w09_dtpaga != ''?db_formatar($w09_dtpaga,'d'):'não efetuado'),1,0,"L",0);
	    $pdf->Cell(38,6,'Valor pago: '.($w09_valpago!=''?db_formatar($w09_valpago,'f'):'nâo efetuado'),1,1,"L",0);
	    
	    $total += $w08_valreceita;
	    $totali += $w08_imposto;
	    //if ($w09_valpago!='')
	    $totalvp +=$w09_valpago;
  	}
	    $pdf->Ln(3);
	    $pdf->Cell(63,6,'Valor total: '.db_formatar(@$total,'f'),1,0,"R",0);
	    $pdf->Cell(63,6,'Imposto Total: '.db_formatar(@$totali,'f'),1,0,"R",0);
	    $pdf->Cell(63,6,'Valor pago total: '.db_formatar(@$totalvp,'f'),1,1,"R",0);
		
  }
// ate aquiiiiiiiiiiiii




$pdf->SetFont($Letra,'',9);
$pdf->SetY(270);
$pdf->SetFont($Letra,'',9);
//$pdf->MultiCell(0,6,'DAI NÃO ENVIADA',0,"C",0);

//$pdf->MultiCell(0,6,"DATA DE ENVIO: ".($w04_data != ""?db_formatar($w04_data,'d'):"")." - EXERCÍCIO: ".$w04_ano,0,"C",0);
if ( $pdf->GetY() > 280) {
  $pdf->AddPage();
  $pdf->Ln(40);
}
$pdf->output();
?>