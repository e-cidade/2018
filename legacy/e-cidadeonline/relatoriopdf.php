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

  //include("libs/db_conecta.php");
  include("libs/db_stdlib.php");
  include("libs/db_sql.php");
  define('FPDF_FONTPATH','font/');
  require("fpdf151/fpdf.php");
  $clquery = new cl_query;
  $nova=false;

  $head1 = "";
  $head2 = "";
  $head3 = "";
  $head4 = "";
  $head5 = "";
  $head6 = "";
  $head7 = "";
  $head8 = "";
  $head9 = "";
  $situacao = "";
  $sqluf = "select * from db_config  inner join db_uf on db12_uf=uf  where codigo = ".db_getsession('DB_instit');
  $resultuf = db_query($sqluf);
  db_fieldsmemory($resultuf,0);
  
  $clquery->sql_query("issplan left join issplaninscr on q20_planilha=q24_planilha","*","","q20_planilha= $planilha");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);
  if($q20_situacao==5){
  	$situacao = " (ANULADA)";
  }
  $matri= array("1"=>"janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
  $mesx= $matri[$q20_mes];
  
  $pdf = new FPDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage("L");
  // monta cabecalho do relatório    
  $pdf->Image('imagens/files/logo_boleto.png',140,3,20);
  $pdf->SetFont('Times','',9);
  $pdf->Text(118,31,$nomeinst);
  $pdf->Text(125,34,$db12_extenso);
  $pdf->SetFont('Courier','B',12);
  $pdf->Text(122,38,"PLANILHA NÚMERO: ".$planilha.$situacao);
  $pdf->SetFont('Courier','B',9);
  $pdf->Text(243,38,"COMPETÊNCIA:".$mesx."/".$q20_ano);
  $pdf->SetFont('Courier','B',10);
  $pdf->setY(41);
  $pdf->setX(5);
  $pdf->SetFillColor(200,200,200);
  $pdf->Cell(286,5,"DADOS DO DECLARANTE",1,1,"C",1);
  $pdf->SetFont('Courier','',9);
  $pdf->setY(47);
  $pdf->setX(5);
  
  $clquery->sql_query("cgm","z01_nome",""," z01_numcgm = $q20_numcgm");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);
 
  $pdf->Cell(154,5,"NOME OU RAZÃO SOCIAL:".$z01_nome,"LTB",0,"L",0);
  $pdf->Cell(52,5,"INSCRIÇÃO MUNICIPAL:".$q24_inscr,"TB",0,"L",0);
  $pdf->Cell(80,5,"CONTATO:".$q20_nomecontri." FONE:".$q20_fonecontri,"TRB",1,"L",0);
  $pdf->SetFont('Courier','B',10);
  
  
  $pdf->Ln(10);
  $pdf->setX(5);
  $pdf->Cell(286,5,"DADOS DOS SERVIÇOS PRESTADOS",1,1,"C",1);
 // $pdf->Ln(2);

  $pdf->setX(5);
  $pdf->SetFont('Courier','B',9);
  $pdf->Cell(30,5,"CNPJ/CPF",1,0,"C",1);
  $pdf->Cell(15,5,"Insc.",1,0,"C",1);
  $pdf->Cell(62,5,"Nome do Tomador",1,0,"C",1);
  $pdf->Cell(13,5,"Nota",1,0,"C",1);
  $pdf->Cell(10,5,"Série",1,0,"C",1);
  $pdf->Cell(7,5,"Dia",1,0,"C",1);
  $pdf->Cell(29,5,"Valor bruto",1,0,"C",1);
  $pdf->Cell(25,5,"Dedução",1,0,"C",1);
  $pdf->Cell(25,5,"Base cálculo",1,0,"C",1);
  $pdf->Cell(10,5,"Aliq",1,0,"C",1);
  $pdf->Cell(25,5,"Imposto",1,0,"C",1);
  $pdf->Cell(10,5,"Ret.",1,0,"C",1);
  $pdf->Cell(25,5,"Valor a pagar",1,1,"C",1);
  $pdf->SetFont('Courier','',9);

 $clquery->sql_query("issplanit left join issplanitinscr on q21_sequencial= q31_issplanit","*","q21_datanota","q21_planilha= $planilha and q21_tipolanc = 2 and q21_status = 1");
 $clquery->sql_record($clquery->sql);
 $linhaspres = $clquery->numrows;

 if($linhaspres>0){
 for($i=0;$i<$clquery->numrows;$i++){
  db_fieldsmemory($clquery->result,$i);
  $pdf->setX(5);
  
  if($q21_tipolanc==1){
    $q21_tipolanc = "Tomado";
  }else{
    $q21_tipolanc = "Prestado";
  }
  if($q21_retido=="f"){
    $q21_retido= "não";
  }else{
    $q21_retido= "sim";
  }
   if(strlen("$q21_nome") > 30){
    $q21_nome  = substr($q21_nome, 0, 30)."..";
  }
 $dia = substr($q21_datanota, 8,2);
 // $dia =  date("d",$q21_datanota);
 //  die($q21_datanota);
  $pdf->Cell(30,5,$q21_cnpj,1,0,"C",0);
  $pdf->Cell(15,5,$q31_inscr,1,0,"C",0);
  $pdf->Cell(62,5,$q21_nome,1,0,"L",0);
  $pdf->Cell(13,5,$q21_nota,1,0,"R",0);
  $pdf->Cell(10,5,$q21_serie,1,0,"R",0);
  $pdf->Cell(7,5,$dia,1,0,"C",0);
  $pdf->Cell(29,5,trim( db_formatar($q21_valorser,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($q21_valordeducao,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($q21_valorbase,'f') ),1,0,"R",0);
  $pdf->Cell(10,5,$q21_aliq."%",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($q21_valorimposto,'f'),1,0,"R",0);
  $pdf->Cell(10,5,$q21_retido,1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($q21_valor,'f'),1,1,"R",0);
  
 // $pdf->setX(5);
 // $pdf->Cell(40,5,$q21_tipolanc,1,0,"C",1);
 // $pdf->Cell(244,5,$q21_servico,1,1,"L",0);
  
  $ny=$pdf->GetY();
  if($ny>=160){
    $nova=true;
  }
  
  @$vb +=$q21_valorbase;
  @$vd +=$q21_valordeducao;
  @$vs += $q21_valorser;
  @$vt += $q21_valor;
  @$vi +=$q21_valorimposto;
  
}


  $pdf->setX(5);
   $pdf->SetFont('Courier','B',9);
 $qP=$i;
  $pdf->Cell(137,5,"QUANTIDADE DE ITENS: ".$i,1,0,"L",0);
  $pdf->Cell(29,5,trim( db_formatar($vs,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($vd,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($vb,'f') ),1,0,"R",0);
  $pdf->Cell(10,5,"",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($vi,'f'),1,0,"R",0);
  $pdf->Cell(10,5,"",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($vt,'f'),1,1,"R",0);
 } 
  
 // $pdf->Cell(220,5,"QUANTIDADE DE ÍTENS: ".$i,1,0,"C",0);
//  $pdf->Cell(24,5,db_formatar(@$vs,'f'),1,0,"R",0);
 // $pdf->Cell(15,5,"",1,0,"C",0);
//  $pdf->Cell(25,5,db_formatar(@$vt,'f'),1,1,"R",0);
   
// ########################## TOMADO ###################################
$vb1 = 0;
$vd1= 0;
$vs1 = 0;
$vt1 = 0;
$vi1 = 0;

$pdf->SetFont('Courier','B',9);
$pdf->Ln(10);
  $pdf->setX(5);
  $pdf->Cell(286,5,"DADOS DOS SERVIÇOS TOMADOS",1,1,"C",1);
 // $pdf->Ln(2);

  $pdf->setX(5);

  $pdf->Cell(30,5,"CNPJ/CPF",1,0,"C",1);
  $pdf->Cell(15,5,"Insc.",1,0,"C",1);
  $pdf->Cell(62,5,"Nome Prestador",1,0,"C",1);
  $pdf->Cell(13,5,"Nota",1,0,"C",1);
  $pdf->Cell(10,5,"Série",1,0,"C",1);
  $pdf->Cell(7,5,"Dia",1,0,"C",1); 
  $pdf->Cell(29,5,"Valor bruto",1,0,"C",1);
  $pdf->Cell(25,5,"Dedução",1,0,"C",1);
  $pdf->Cell(25,5,"Base cálculo",1,0,"C",1);
  $pdf->Cell(10,5,"Aliq",1,0,"C",1);
  $pdf->Cell(25,5,"Imposto",1,0,"C",1);
  $pdf->Cell(10,5,"Ret.",1,0,"C",1);
  $pdf->Cell(25,5,"Valor a pagar",1,1,"C",1);
   $pdf->SetFont('Courier','',9);

 $clquery->sql_query("issplanit left join issplanitinscr on q21_sequencial= q31_issplanit","*","q21_datanota","q21_planilha= $planilha and q21_tipolanc = 1 and q21_status = 1");
 $clquery->sql_record($clquery->sql);
 $linastom = $clquery->numrows;
 if($linastom>0){
 for($i=0;$i<$clquery->numrows;$i++){
  db_fieldsmemory($clquery->result,$i);
  $pdf->setX(5);
  
  if($q21_tipolanc==1){
    $q21_tipolanc = "Tomado";
  }else{
    $q21_tipolanc = "Prestado";
  }
  if($q21_retido=="f"){
    $q21_retido= "não";
  }else{
    $q21_retido= "sim";
  }
  if(strlen("$q21_nome") > 30){
    $q21_nome  = substr($q21_nome, 0, 30)."..";
  }
  
  $dia = substr($q21_datanota, 8,2);  
  $pdf->Cell(30,5,$q21_cnpj,1,0,"C",0);
  $pdf->Cell(15,5,$q31_inscr,1,0,"C",0);
  $pdf->Cell(62,5,$q21_nome,1,0,"L",0);
  $pdf->Cell(13,5,$q21_nota,1,0,"R",0);
  $pdf->Cell(10,5,$q21_serie,1,0,"R",0);
  $pdf->Cell(7,5,$dia,1,0,"C",0);
  $pdf->Cell(29,5,trim( db_formatar($q21_valorser,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($q21_valordeducao,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($q21_valorbase,'f') ),1,0,"R",0);
  $pdf->Cell(10,5,$q21_aliq."%",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($q21_valorimposto,'f'),1,0,"R",0);
  $pdf->Cell(10,5,$q21_retido,1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($q21_valor,'f'),1,1,"R",0);
  
 // $pdf->setX(5);
 // $pdf->Cell(40,5,$q21_tipolanc,1,0,"C",1);
 // $pdf->Cell(244,5,$q21_servico,1,1,"L",0);
  
  $ny=$pdf->GetY();
  if($ny>=160){
    $nova=true;
  }
  
  @$vb1 +=$q21_valorbase;
  @$vd1 +=$q21_valordeducao;
  @$vs1 += $q21_valorser;
  @$vt1 += $q21_valor;
  @$vi1 +=$q21_valorimposto;
  
}

  $pdf->setX(5);
  $pdf->SetFont('Courier','B',9);
  $qT=$i;
  $pdf->Cell(137,5,"QUANTIDADE DE ITENS: ".$i,1,0,"L",0);
  $pdf->Cell(29,5,trim( db_formatar($vs1,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($vd1,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($vb1,'f') ),1,0,"R",0);
  $pdf->Cell(10,5,"",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($vi1,'f'),1,0,"R",0);
  $pdf->Cell(10,5,"",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($vt1,'f'),1,1,"R",0);
 }
//######################################################################



//total
  $qtotal = @$qT + @$qP;
  $vb2 = @$vb + $vb1; 
  $vd2 = @$vd + $vd1;
  $vs2 = @$vs + $vs1;
  $vt2 = @$vt + $vt1;
  $vi2 = @$vi + $vi1; 
  
	
	$pdf->ln(10);
	$pdf->Setx(5);
  $pdf->Cell(137,5,"QUANTIDADE DE ITENS: ".$qtotal,1,0,"L",0);
  $pdf->Cell(29,5,trim( db_formatar($vs2,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($vd2,'f') ),1,0,"R",0);
  $pdf->Cell(25,5,trim( db_formatar($vb2,'f') ),1,0,"R",0);
  $pdf->Cell(10,5,"",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($vi2,'f'),1,0,"R",0);
  $pdf->Cell(10,5,"",1,0,"C",0);
  $pdf->Cell(25,5,db_formatar($vt2,'f'),1,1,"R",0);
  
  
  
  $y=$pdf->GetY();

  $pdf->SetY($y+3);
  $pdf->Setx(5);
//  if(!isset($DB_mens1)){
//  	$DB_mens1 = "";
//  }
  db_mensagem("issqnplan_cad","issqnplan_rod");
  $pdf->SetFont('Courier','',7);
 // $pdf->Cell(140,4,"Data: ".db_formatar(date("Y-m-d"),'d'),0,1,"L",0);
  $pdf->Cell(140,4,"Local e Data: $munic ".db_formatar(date("Y-m-d"),'d'),0,1,"L",0);
  $pdf->MultiCell(260,4,$DB_mens1,0,"L",0);

 
  $pdf->SetY(-26);
  $pdf->Setx(5);
  $pdf->SetFont('Courier','B',9);
  $pdf->Cell(170,5,$DB_mens2,0,0,"L",0);
  $pdf->Cell(110,5,"Página ".$pdf->PageNo()." de {nb} ",0,0,"C",0);

  $pdf->Output();

?>