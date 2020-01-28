<?php
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

require_once(modification('fpdf151/pdf.php'));
require_once(modification("classes/db_sanitario_classe.php"));
require_once(modification("classes/db_saniatividade_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_parfiscal_classe.php"));
$clrotulo = new rotulocampo;
$clcgm = new cl_cgm;
$clparfiscal = new cl_parfiscal;
$clrotulo->label("q03_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clcgm->rotulo->label();
$clsanitario = new cl_sanitario;
$clsaniatividade = new cl_saniatividade;
$clsaniatividade->rotulo->label();
$clsanitario->rotulo->label();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//print_r($HTTP_SERVER_VARS['QUERY_STRING']);
//exit;

$result_param = $clparfiscal->sql_record($clparfiscal->sql_query_file( db_getsession('DB_instit') ));
if ($clparfiscal->numrows>0){
  db_fieldsmemory($result_param,0);
}
if (isset($y32_sanidepto)&&$y32_sanidepto=='1'){
  $where=" y80_depto =".db_getsession("DB_coddepto");
}else{
  $where = " 1=1 ";
}

if($baixados == "bai"){
  $cabopc = "SOMENTE BAIXADOS";
  $where .= " and y81_codsani is not null ";
}else if ($baixados == "nao"){
  $cabopc = "SOMENTE NÃO BAIXADOS";
  $where .= " and y81_codsani is null ";
}else{
  $cabopc = "";
}


if(isset($y80_codsani) && $y80_codsani != ""){
  $where .= " and y80_codsani = $y80_codsani ";
}
if(isset($y80_numcgm) && $y80_numcgm != ""){
  $where .= " and y80_numcgm = $y80_numcgm ";
}
if(isset($dataini) && $dataini != "--" && $datafim == "--"){
  $where .= " and y80_data >= '$dataini' ";
}
if(isset($dataini) && $dataini != "--" && isset($datafim) && $datafim != "--"){
  $where .= " and y80_data >= '$dataini' and y80_data <= '$datafim' ";
}
if(isset($ativ) && (trim($ativ)!="") ){
  $where .= " and y83_ativ = $ativ ";
}
if(isset($rua) && (trim($rua)!="") ){
  $where .= " and  y80_codrua = $rua ";
}
if(isset($bairro) && (trim($bairro)!="") ){
  $where .= " and y80_codbairro = $bairro ";
}
if(isset($mes) && (trim($mes)!="") ){
  $where .= " and extract (month from y83_dtini) = $mes ";
}
if ($selativ == "pri"){
  $cabativ = "ATIVIDADE:  PRINCIPAL";
  $where .= " and y83_ativprinc is true";
}else{
  $cabativ = "ATIVIDADE:  TODAS";
}

switch ($ordem){
  case "y80_codsani":
    $cabordem = "ORDEM:  ALVARÁ ";
    break;
  case "y80_numcgm":
    $cabordem = "ORDEM:  CGM ";
    break;
  case "q03_descr":
    $cabordem = "ORDEM:  ATIVIDADE ";
    break;
  case "y80_data":
    $cabordem = "ORDEM:  DATA DE LIBERAÇÃO ";
    break;

}

$result = $clsanitario->sql_record($clsanitario->sql_querysani("","distinct y80_codsani, 
																																						z01_nome, 
																																					  y80_numcgm,	
																																						z01_cgccpf, 
																																					  q03_descr,	
																																						j14_nome, 
																																						y80_numero, 
																																						y80_compl, 
																																						z01_munic, 
																																						j13_descr, 
																																						z01_cep, 
																																						y80_data, 
																																						y83_dtini, 
																																						y80_obs, 
																																						y80_area,
																																						case when	y81_codsani is null then  'Sim' 
																																							else 'Não'  end as colbaix ","$ordem","$where"));


if($clsanitario->numrows > 0){
  $linhas = $clsanitario->numrows;
  db_fieldsmemory($result,0);
}else{
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum dado cadastrado para o filtro selecionado !');
  exit;
}


$head2 = "CONSULTA BIC ALVARÁ";
$head4 = $cabordem;
$head5 = $cabativ;
$head6 = "DATA: ".db_formatar($dataini,'d')." à ".db_formatar($datafim,'d');
$head7 = $cabopc;


//---------------------//
// RELATORIO ANALITICO //
//---------------------//

if ($tipo=="ana"){
  $pdf = new PDF(); // abre a classe
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas

  $head3 = "TIPO:  ANALÍTICO";

  $pdf->AddPage(); // adiciona uma pagina
  for($r=0;$r<$linhas;$r++){
    db_fieldsmemory($result,$r);
    $resultativid = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y80_codsani and $where"));

    if($clsaniatividade->numrows > 0){
      db_fieldsmemory($resultativid,0);
    }

    $Letra = 'arial';
    $pdf->SetFont($Letra,'',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(200);
    $pdf->Cell(190,5,"ALVARÁ SANITÁRIO: ".$y80_codsani,1,0,"L",1);
    $pdf->Ln(6);
    $pdf->SetFillColor(255);
    $pdf->Cell(140,6,$RLz01_nome.': '.@$z01_nome,1,0,"J",1);
    $pdf->Cell(50,6,$RLz01_cgccpf.': '.@$z01_cgccpf,1,"J",1,30);
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
      $pdf->Cell(55,6,$RLq03_descr.'',1,0,"J",1);
      $pdf->Cell(25,6,$RLy83_area.'',1,1,"C",1);
      for($i=0;$i<$clsaniatividade->numrows;$i++){
        db_fieldsmemory($resultativid,$i);
        $pdf->Cell(20,6,''.$y83_seq,1,0,"C",0);
        $pdf->Cell(45,6,''.($y83_dtini != ""?db_formatar($y83_dtini,'d'):''),1,0,"C",0);
        $pdf->Cell(45,6,''.($y83_dtfim != ""?db_formatar($y83_dtfim,'d'):''),1,0,"C",0);
        $pdf->Cell(55,6,''.substr($q03_descr,0,22),1,0,"L",0);
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
        $pdf->Cell(55,6,''.substr($q03_descr,0,22),1,0,"L",0);
        $pdf->Cell(25,6,''.$y83_area,1,1,"C",0);
      }
    }
    $pdf->Ln(4);
    if ($pdf->GetY() > 280) {
      $pdf->AddPage();
    }
  }
}elseif($tipo=="sim"){
  //---------------------//
  // RELATORIO SINTETICO //
  //---------------------//
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;
  for($x = 0; $x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);

    $head3 = "TIPO:  SINTÉTICO";

    $pontos = strlen($q03_descr) > 50 ? " ..." : '';
    $q03_descr = substr($q03_descr, 0, 50).$pontos;

	 if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
     $pdf->addpage("L");
     $pdf->setfont('arial','b',8);
     $pdf->cell(20,$alt,"Alvara",1,0,"C",1);
     $pdf->cell(25,$alt,$RLy80_numcgm,1,0,"C",1);
     if($baixados == "tod"){
       $pdf->cell(75,$alt,$RLz01_nome,1,0,"C",1);
       $pdf->cell(75,$alt,$RLq03_descr,1,0,"C",1);
       $pdf->Cell(27,$alt,$RLy80_data,1,0,"C",1);
       $pdf->Cell(38,$alt,$RLy83_dtini,1,0,"C",1);
       $pdf->Cell(20,$alt,"Baixado",1,1,"C",1);
       $troca = 0;
     }else{
       $pdf->cell(85,$alt,$RLz01_nome,1,0,"C",1);
       $pdf->cell(85,$alt,$RLq03_descr,1,0,"C",1);
       $pdf->Cell(27,$alt,$RLy80_data,1,0,"C",1);
       $pdf->Cell(38,$alt,$RLy83_dtini,1,1,"C",1);
       $troca = 0;
     }

   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$y80_codsani,0,0,"C",0);
   $pdf->cell(25,$alt,$y80_numcgm,0,0,"C",0);
	 if($baixados == "tod"){
     $pdf->cell(75,$alt,$z01_nome,0,0,"L",0);
     $pdf->cell(75,$alt,$q03_descr,0,0,"L",0);
     $pdf->Cell(27,$alt,db_formatar($y80_data,'d'),0,0,"L",0);
     $pdf->Cell(38,$alt,db_formatar($y83_dtini,'d'),0,0,"L",0);
     $pdf->cell(20,$alt,$colbaix,0,1,"C",0);
     $total ++;
   }else{
     $pdf->cell(85,$alt,$z01_nome,0,0,"L",0);
     $pdf->cell(85,$alt,$q03_descr,0,0,"L",0);
     $pdf->Cell(27,$alt,db_formatar($y80_data,'d'),0,0,"L",0);
     $pdf->Cell(38,$alt,db_formatar($y83_dtini,'d'),0,1,"L",0);
     $total ++;
   }
}
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
}
$pdf->output();
?>