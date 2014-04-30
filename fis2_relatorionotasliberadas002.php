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



/***************************************************************/
//
//          fis2_relatorionotasliberadas002.php
//
//    Descrição: Consulta Notas liberadas por inscrição e período
//    Criado por: Francis Jeziorowski
//    Data de Criação: 27/07/2005  
//    Última Modificação: 28/07/2005
//    Modificado por: Francis Jeziorowski
//
/**************************************************************/

include('fpdf151/pdf.php');
include("classes/db_aidof_classe.php");
include("classes/db_issbase_classe.php");

$inscricao = str_replace('',"",$inscricao);

function condicao($dataini,$datafim,$inscricao){

$condicao="";

if(isset($dataini) && $dataini != "--" && $datafim == "--"){
  $condicao = "y08_dtlanc >= '$dataini' ";
  if (isset($inscricao) and !empty($inscricao)) $condicao.= "AND y08_inscr = $inscricao";
}
elseif(isset($dataini) && $dataini != "--" && isset($datafim) && $datafim != "--"){
  $condicao = "y08_dtlanc >= '$dataini' and y08_dtlanc <= '$datafim'";
  if (isset($inscricao) and !empty($inscricao)) $condicao.= "AND y08_inscr = $inscricao";
}
else{
  if (isset($inscricao) and !empty($inscricao)) $condicao.= "y08_inscr = $inscricao";
  else $condicao = "";
}
 
 return $condicao; 

}


$claidof = new cl_aidof;
$claidof->rotulo->label();

$clissbase = new cl_issbase;
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");

/*Incluindo Cabeçalho Relatório*/
 $head3 = "NOTAS FISCAIS LIBERADAS";

/*Inclui data no cabeçalho caso 'setado' um período*/
if(isset($dataini) && $dataini != "--" && $datafim == "--"){
  $data = " y08_dtlanc >= '$dataini' ";
  $head5 = "Período :".db_formatar(@$dataini,'d')." - ".db_formatar(@$datafim,'d')."";
}elseif(isset($dataini) && $dataini != "--" && isset($datafim) && $datafim != "--"){
  $data = " y08_dtlanc >= '$dataini' and y08_dtlanc <= '$datafim'";
  $head5 = "Período :".db_formatar(@$dataini,'d')." - ".db_formatar(@$datafim,'d')."";
}elseif(isset($dataini) && $dataini == "--" && isset($datafim) && $datafim == "--"){
  $data = "";
  $head5 = "";
}

$condicao = condicao($dataini,$datafim,$inscricao);

$result = $claidof->sql_record($claidof->sql_query("","*","cgm.z01_nome,y08_dtlanc",$condicao)); 

if($claidof->numrows > 0){
  $linhas = $claidof->numrows;
  db_fieldsmemory($result,0);
}else{
  echo "<script>window.opener.alert('Não existem registros com os filtros selecionados!')</script>";
  echo "<script>window.close()</script>";
  exit;
}

 if (isset($inscricao) and !empty($inscricao)) $head7 = "$inscricao - $z01_nome";
 

 /*Instancia um novo PDF*/
 $pdf = new PDF();     // abre a classe
 $pdf->Open();         // abre o relatorio
 $pdf->AliasNbPages(); // gera alias para as paginas
 $pdf->AddPage();      // adiciona uma pagina

 
 ///////////////////////////////////////////////////
 /*Layout relatório - Contribuinte selecionado    */
 ///////////////////////////////////////////////////
 if (isset($inscricao) and !empty($inscricao)) {
  
 /*Cabecalho Corpo Relatório*/
 $pdf->SetFont('Arial','b',8);
 $pdf->SetFillColor(235);
 $pdf->Cell(25,6,'Código Liberação',1,0,"J",1);
 $pdf->Cell(80,6,'Gráfica',1,0,"J",1);
 $pdf->Cell(20,6,'Nota Inicial',1,0,"J",1);
 $pdf->Cell(20,6,'Nota Final',1,0,"J",1);
 $pdf->Cell(20,6,'QTD. Liberada',1,0,"J",1);
 $pdf->Cell(25,6,'Data Liberação',1,0,"J",1);
 $pdf->Ln(8);
 
for($r=0;$r<$linhas;$r++){

  db_fieldsmemory($result,$r);
  $resultaidof = $claidof->sql_record($claidof->sql_query("","y08_codigo, y08_numcgm, y08_notain, y08_notafi, y08_quantlib, y08_dtlanc, c.z01_nome as grafica, cgm.z01_nome as contrib",""," y08_codigo = $y08_codigo")); 
  if($claidof->numrows > 0){
    db_fieldsmemory($resultaidof,0);
  }
    
 /*Corpo Relatório*/
 $pdf->SetFont('Arial','',8);
 $pdf->SetFillColor(255);
 $pdf->Cell(25,6,$y08_codigo,0,0,"J",1);
 $pdf->Cell(80,6,$grafica,0,0,"J",1);
 $pdf->Cell(20,6,$y08_notain,0,0,"J",1);
 $pdf->Cell(20,6,$y08_notafi,0,0,"J",1);
 $pdf->Cell(20,6,$y08_quantlib,0,0,"J",1);
 $pdf->Cell(25,6,db_formatar(@$y08_dtlanc,'d'),0,0,"J",1);
 $pdf->Ln(6);
  
 /*Adiciona nova página*/
 if ($pdf->GetY() > 260) {
   $pdf->AddPage();
   /*Cabecalho Corpo Relatório*/
   $pdf->SetFont('Arial','b',8);
   $pdf->SetFillColor(235);
   $pdf->Cell(25,6,'Código Liberação',1,0,"J",1);
   $pdf->Cell(80,6,'Gráfica',1,0,"J",1);
   $pdf->Cell(20,6,'Nota Inicial',1,0,"J",1);
   $pdf->Cell(20,6,'Nota Final',1,0,"J",1);
   $pdf->Cell(20,6,'QTD. Liberada',1,0,"J",1);
   $pdf->Cell(25,6,'Data Liberação',1,0,"J",1);
   $pdf->Ln(8);
   
 }

}
 
}
 
 ////////////////////////////////////////////////////
 /*Layout relatório - Contribuinte não selecionado */
 ////////////////////////////////////////////////////
 else{

 /*Cabecalho Corpo Relatório*/
 $pdf->SetFont('Arial','b',6);
 $pdf->SetFillColor(235);
 $pdf->Cell(20,6,'Código Liberação',1,0,"J",1);
 $pdf->Cell(55,6,'Contribuinte',1,0,"J",1);
 $pdf->Cell(55,6,'Gráfica',1,0,"J",1);
 $pdf->Cell(15,6,'Nota Inicial',1,0,"J",1);
 $pdf->Cell(15,6,'Nota Final',1,0,"J",1);
 $pdf->Cell(15,6,'Qtd. Liberada',1,0,"J",1);
 $pdf->Cell(15,6,'Dt. Liberação',1,0,"J",1);
 $pdf->Ln(8);
 
for($r=0;$r<$linhas;$r++){

  db_fieldsmemory($result,$r);
  $resultaidof = $claidof->sql_record($claidof->sql_query("","y08_codigo, y08_numcgm, y08_notain, y08_notafi, y08_quantlib, y08_dtlanc, c.z01_nome as grafica, cgm.z01_nome as contrib",""," y08_codigo = $y08_codigo")); 
  if($claidof->numrows > 0){
    db_fieldsmemory($resultaidof,0);
  }
    
 /*Corpo Relatório*/
 $pdf->SetFont('Arial','',6);
 $pdf->SetFillColor(255);
 $pdf->Cell(20,6,$y08_codigo,0,0,"J",1);
 $pdf->Cell(55,6,$contrib,0,0,"J",1);
 $pdf->Cell(55,6,$grafica,0,0,"J",1);
 $pdf->Cell(15,6,$y08_notain,0,0,"J",1);
 $pdf->Cell(15,6,$y08_notafi,0,0,"J",1);
 $pdf->Cell(15,6,$y08_quantlib,0,0,"J",1);
 $pdf->Cell(10,6,db_formatar(@$y08_dtlanc,'d'),0,0,"J",1);
 $pdf->Ln(6);
  
 /*Adiciona nova página*/
 if ($pdf->GetY() > 260) {
   $pdf->AddPage();
   
   /*Cabecalho Corpo Relatório*/
   $pdf->SetFont('Arial','b',6);
   $pdf->SetFillColor(235);
   $pdf->Cell(20,6,'Código Liberação',1,0,"J",1);
   $pdf->Cell(55,6,'Contribuinte',1,0,"J",1);
   $pdf->Cell(55,6,'Gráfica',1,0,"J",1);
   $pdf->Cell(15,6,'Nota Inicial',1,0,"J",1);
   $pdf->Cell(15,6,'Nota Final',1,0,"J",1);
   $pdf->Cell(15,6,'Qtd. Liberada',1,0,"J",1);
   $pdf->Cell(15,6,'Dt. Liberação',1,0,"J",1);
   $pdf->Ln(8);
   
 }

}
 
}


$pdf->output();


?>