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

include('fpdf151/pdf.php');
include("classes/db_sanitario_classe.php");
include("classes/db_saniatividade_classe.php");
include("classes/db_cgm_classe.php");
$clrotulo = new rotulocampo;
$clcgm = new cl_cgm;
$clrotulo->label("q03_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clcgm->rotulo->label();
$clsanitario = new cl_sanitario;
$clsaniatividade = new cl_saniatividade;
$clsaniatividade->rotulo->label();
$clsanitario->rotulo->label();
//db_postmemory($HTTP_SERVER_VARS,2);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(isset($dataini) && $dataini != "--" && $datafim == "--"){
  $data = " y83_dtfim >= '$dataini' ";
}elseif(isset($dataini) && $dataini != "--" && isset($datafim) && $datafim != "--"){
  $data = " y83_dtfim >= '$dataini' and y83_dtfim <= '$datafim' ";
}elseif(isset($dataini) && $dataini == "--" && isset($datafim) && $datafim == "--"){
  $data = " y83_dtfim is not null";
}
$result = $clsaniatividade->sql_record($clsaniatividade->sql_query("",""," distinct y83_codsani,sanitario.*,ativid.*,cgm.* ",""," $data")); 
if($clsaniatividade->numrows > 0){
  $linhas = $clsaniatividade->numrows;
  db_fieldsmemory($result,0);
}else{
  echo "<script>window.opener.alert('Nenhum dado cadastrado para este Período!')</script>";
  echo "<script>window.close()</script>";
  exit;
}
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
for($r=0;$r<$linhas;$r++){
  db_fieldsmemory($result,$r);
  $resultativid = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y80_codsani and $data")); 
  if($clsaniatividade->numrows > 0){
    db_fieldsmemory($resultativid,0);
  }
  $head1 = "ATIVIDADES BAIXADAS";
  $Letra = 'arial';
  $pdf->SetFont($Letra,'',10);
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(200);
  $pdf->Cell(190,5,"ALVARÁ SANITÁRIO: ".$y80_codsani,1,0,"L",1);
  $pdf->Ln(6);
  $pdf->SetFillColor(255);
  $pdf->Cell(100,6,$RLz01_nome.': '.@$z01_nome,1,0,"J",1);
  $pdf->Cell(90,6,$RLz01_cgccpf.': '.@$z01_cgccpf,1,"J",1,30);
  $pdf->Ln(6);
  $pdf->Cell(100,6,$RLj14_nome.': '.@$j14_nome,1,0,"J",1);
  $pdf->Cell(35,6,$RLy80_numero.': '.@$y80_numero,1,"J",1,30);
  $pdf->Cell(55,6,$RLy80_compl.': '.@$y80_compl,1,"J",1,30);
  $pdf->Ln(6);
  $pdf->Cell(70,6,$RLz01_munic.': '.@$z01_munic,1,0,"J",1);
  $pdf->Cell(70,6,$RLj13_descr.': '.@$j13_descr,1,0,"J",1);
  $pdf->Cell(50,6,$RLz01_cep.': '.@$z01_cep,1,0,"J",1);
  $pdf->Ln(6);
  $pdf->Cell(70,6,$RLy80_data.': '.db_formatar(@$y80_data,'d'),1,0,"J",1);
  $pdf->Cell(70,6,$RLy80_obs.': '.substr(@$y80_obs,0,20)."...",1,0,"J",1);
  $pdf->Cell(50,6,$RLy80_area.': '.@$y80_area,1,0,"J",1);
  $pdf->Ln(6);
  $pdf->SetFillColor(200);
  if($clsaniatividade->numrows > 0){
    $pdf->Cell(190,5,"ATIVIDADES: ",1,0,"L",1);
    $pdf->Ln(6);
  }
  $pdf->SetFont($Letra,'',10);
  if($clsaniatividade->numrows > 1){
      $pdf->SetFillColor(200);
      $pdf->Cell(20,6,$RLy83_seq.'',1,0,"C",1);
      $pdf->Cell(45,6,$RLy83_dtini.'',1,0,"C",1);
      $pdf->Cell(45,6,$RLy83_dtfim.'',1,0,"C",1);
      $pdf->Cell(55,6,$RLq03_descr.'',1,0,"C",1);
      $pdf->Cell(25,6,$RLy83_area.'',1,1,"C",1);
    for($i=0;$i<$clsaniatividade->numrows;$i++){
      db_fieldsmemory($resultativid,$i);
      $pdf->Cell(20,6,''.$y83_seq,1,0,"C",0);
      $pdf->Cell(45,6,''.($y83_dtini != ""?db_formatar($y83_dtini,'d'):''),1,0,"C",0);
      $pdf->Cell(45,6,''.($y83_dtfim != ""?db_formatar($y83_dtfim,'d'):''),1,0,"C",0);
      $pdf->Cell(55,6,''.$q03_descr,1,0,"C",0);
      $pdf->Cell(25,6,''.$y83_area,1,1,"C",0);
    }  
  }else{
    if($clsaniatividade->numrows != 0){
      $total = 0;
      $totali = 0;
      $pdf->SetFillColor(200);
      $pdf->Cell(20,6,$RLy83_seq.'',1,0,"C",1);
      $pdf->Cell(45,6,$RLy83_dtini.'',1,0,"C",1);
      $pdf->Cell(45,6,$RLy83_dtfim.'',1,0,"C",1);
      $pdf->Cell(55,6,$RLq03_descr.'',1,0,"C",1);
      $pdf->Cell(25,6,$RLy83_area.'',1,1,"C",1);
      db_fieldsmemory($resultativid,0);
      $pdf->Cell(20,6,''.$y83_seq,1,0,"C",0);
      $pdf->Cell(45,6,''.($y83_dtini != ""?db_formatar($y83_dtini,'d'):''),1,0,"C",0);
      $pdf->Cell(45,6,''.($y83_dtfim != ""?db_formatar($y83_dtfim,'d'):''),1,0,"C",0);
      $pdf->Cell(55,6,''.$q03_descr,1,0,"C",0);
      $pdf->Cell(25,6,''.$y83_area,1,1,"C",0);
    }  
  }
  $pdf->Ln(4);
  if ($pdf->GetY() > 280) {
    $pdf->AddPage();
  }
}
$pdf->output();
?>