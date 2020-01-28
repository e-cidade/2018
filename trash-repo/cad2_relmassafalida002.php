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
include("classes/db_iptuconstr_classe.php");
include("classes/db_iptubase_classe.php");
$cliptuconstr = new cl_iptuconstr;
$cliptuconstr1 = new cl_iptuconstr;
$cliptubase = new cl_iptubase;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

///////////////////////////////////////////////////////////////////////

$head4 = "RELATÓRIO DE MASSA FALIDA";
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',9);
$tam = '05';
$dtini =  $dtini_ano."-".$dtini_mes."-".$dtini_dia;
$dtfim =  $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;
$where = "";
if(isset($dtini_dia) && $dtini_dia != "" && $dtfim_dia == ""){
  $where = " where j58_data >= '$dtini' ";
}elseif(isset($dtini_dia) && $dtini_dia != "" && isset($dtfim_dia) && $dtfim_dia != ""){
  $where = " where j58_data >= '$dtini' and j58_data <= '$dtfim' ";
}elseif(isset($dtini_dia) && $dtini_dia == "" && isset($dtfim_dia) && $dtfim_dia != ""){
  $where = " where j58_data <= '$dtfim'";
}
if($resumido == 't'){
  $sql = "select j58_numcgm,count(x.totmat) as totmat from 
  		(select count(j59_matric) as totmat,j59_codigo 
			from massamat group by j59_matric,j59_codigo) as x 
			inner join massafalida on x.j59_codigo = j58_codigo group by totmat,j58_numcgm";
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);
  $pdf->Cell(50,$tam,"CGM",1,0,"C",1);
  $pdf->Cell(70,$tam,"QUANTIDADE DE MATRÍCULAS",1,1,"C",1);
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    $pdf->Cell(50,$tam,$j58_numcgm,1,0,"C");
    $pdf->Cell(70,$tam,$totmat,1,1,"C");
    if ($pdf->gety() > $pdf->h -45) {
      $pdf->Cell(50,$tam,"CGM",1,0,"C",1);
      $pdf->Cell(70,$tam,"QUANTIDADE DE MATRÍCULAS",1,1,"C",1);
    }
  }
  $pdf->SetFont('Arial','I',7);
  $pdf->Cell(120,$tam,"TOTAL DE REGISTROS: $s",1,1,"L",1);
}else{
  $pdf->SetFont('Arial','B',9);
  $sql = "select j01_matric,z01_numcgm,z01_nome,j58_data from massamat
                        inner join massafalida on j58_codigo = j59_codigo 
			inner join proprietario_nome on j59_matric = j01_matric $where order by $ordem $modo";
  $result = pg_exec($sql);
  $numrows = pg_numrows($result);
  $pdf->Cell(35,$tam,"CGM",1,0,"C",1);
  $pdf->Cell(35,$tam,"MATRÍCULA",1,0,"C",1);
  $pdf->Cell(100,$tam,"NOME/RAZÃO SOCIAL",1,0,"C",1);
  $pdf->Cell(20,$tam,"DATA",1,1,"C",1);
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    $pdf->Cell(35,$tam,$z01_numcgm,1,0,"C");
    $pdf->Cell(35,$tam,$j01_matric,1,0,"C");
    $pdf->Cell(100,$tam,$z01_nome,1,0,"L");
    $pdf->Cell(20,$tam,db_formatar($j58_data,'d'),1,1,"L");
    if ($pdf->gety() > $pdf->h -45) {
      $pdf->addpage();
      $pdf->Cell(35,$tam,"CGM",1,0,"C",1);
      $pdf->Cell(35,$tam,"MATRÍCULA",1,0,"C",1);
      $pdf->Cell(100,$tam,"NOME/RAZÃO SOCIAL",1,0,"C",1);
      $pdf->Cell(20,$tam,"DATA",1,1,"C",1);
    }
  }
  $pdf->SetFont('Arial','I',7);
  $pdf->Cell(190,$tam,"TOTAL DE REGISTROS: $s",1,1,"L",1);
}
/////////////// propriedades /////////////////////
/*
if($resumido == 'f'){
  $pdf->AddPage('L'); // adiciona uma pagina
}
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(235);
$pdf->MultiCell(280,05,"Propriedades do Relatório",1,"C",1);
$pdf->Ln(5);
$pdf->SetFillColor(255);
$pdf->SetFont('Arial','',7);
if(isset($ruas) && !empty($ruas) && $temruas == "t"){
  $vir = "";
  $rua = "";
  $result1 = pg_exec("select j14_nome from ruas where j14_codigo in ($ruas)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j14_nome;
	$vir=", ";
      }
  $pdf->MultiCell(280,05,"SOMENTE A(S) RUA(S) ->  ".$cod,1,"L");
}
if(isset($ruas) && $ruas != "" && $temruas == "f"){
  $vir = "";
  $rua = "";
  $result1 = pg_exec("select j14_nome from ruas where j14_codigo in ($ruas)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j14_nome;
	$vir=", ";
      }
  $pdf->MultiCell(280,05,"EXCETO A(S) RUA(S) ->  ".$cod,1,"L");
}
if(isset($ruas) && $ruas == ""){
  $pdf->MultiCell(280,05,"LOGRADOUROS SELECIONADOS: TODOS",1,"L");
}
$vir = "";
$cod = "";
if($listadas != ""){
  $result1 = pg_exec("select distinct j31_descr,j31_codigo from carlote inner join caracter on j35_caract=j31_codigo where j31_codigo in ($listadas)");
  if(pg_numrows($result1) > 0){
    for($x=0;$x<pg_numrows($result1);$x++){
      db_fieldsmemory($result1,$x);
      $cod .= $vir.$j31_codigo." - ".$j31_descr;
      $vir=", ";
    }
    $pdf->MultiCell(280,05,"CARACTERÍSTICAS LISTADAS ->  ".$cod,1,"L");
  }
}else{
  $pdf->MultiCell(280,05,"CARACTERÍSTICAS LISTADAS -> TODAS ",1,"L");
}
$vir = "";
$cod = "";
if(isset($chaves_caract) && $chaves_caract != ""){
  $result1 = pg_exec("select distinct j31_descr,j31_codigo from carlote inner join caracter on j35_caract=j31_codigo  where j31_codigo in ($chaves_caract)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j31_codigo." - ".$j31_descr;
	$vir=",";
      }
}else{
  $cod = "";
}
$pdf->MultiCell(280,05,"CARACTERÍSTICAS NÃO LISTADAS ->  ".@$cod,1,"L");
if(isset($setores) && $setores != ""){
  if(isset($setor) && $setor != ""){
    $chaves = split(",",$setores);
    $chaves1 = split(",",$quadra);
    $and = "";
    $setores = "";
    for($i=0;$i<sizeof($chaves);$i++){
      $setores .= $and.$chaves[$i]."/".$chaves1[$i];
      $and = " - ";
    }
  }
  $pdf->MultiCell(280,05,"SOMENTE OS SETORES/QUADRAS -> ".$setores,1,"L");
}
if(isset($j32_grupo) && $j32_grupo != ""){
  $pdf->MultiCell(280,05,"TOTAIS DOS LOTES COM CARACTERÍSTICA DO GRUPO - $j32_descr ",1,"L");
}
if(isset($loteini) && $loteini != "" && $lotefim == ""){
  $pdf->MultiCell(280,05,$arealote1,1,"L");
}elseif(isset($loteini) && $loteini != "" && isset($lotefim) && $lotefim != ""){
  $pdf->MultiCell(280,05,$arealote1,1,"L");
}
if(isset($areacons1) && $areacons1 != ""){
  $pdf->MultiCell(280,05,$areacons1,1,"L");
}
if(isset($testada1) && $testada1 != ""){
  $pdf->MultiCell(280,05,$testada1,1,"L");
}
if(isset($pontuacao1) && $pontuacao1 != ""){
  $pdf->MultiCell(280,05,"".$pontuacao1,1,"L");
}
$pdf->Ln(5);
if(isset($ordem) && $ordem != ""){
  $pdf->MultiCell(280,05,"ORDEM - ORDENADO POR ".$ordem1,0,"L");
}
if(isset($order) && $order != ""){
  $pdf->MultiCell(280,05,"MODO -".$modo1,0,"L");
}
$result1 = pg_exec("select j34_setor,count(j34_quadra) as quadra,count(j34_lote) as lotes,sum(j34_area) as area,sum(j34_totcon) as areac,sum(j36_testad) as testada from ($sql) as f group by j34_setor");
$pdf->MultiCell(180,05,"TOTAIS POR SETOR",1,"C");
$pdf->SetFillColor(235);
$pdf->Cell(30,$tam,"SETOR(ES)",1,0,"C",1);
$pdf->Cell(30,$tam,"QUADRA(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"LOTE(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"ÁREA",1,0,"C",1);
$pdf->Cell(30,$tam,"ÁREA CONS",1,0,"C",1);
$pdf->Cell(30,$tam,"TESTADA(S)",1,1,"C",1);
$pdf->SetFillColor(255);
for($x=0;$x<pg_numrows($result1);$x++){
  db_fieldsmemory($result1,$x);
  $pdf->Cell(30,$tam,"".$j34_setor,1,0,"C",1);
  $pdf->Cell(30,$tam,"".$quadra,1,0,"C",1);
  $pdf->Cell(30,$tam,"".$lotes,1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($area,'f'),1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($areac,'f'),1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($testada,'f'),1,1,"C",1);
}
$pdf->ln(5);
$result1 = pg_exec("select j34_setor,j34_quadra,count(j34_lote) as lotes,sum(j34_area) as area,sum(j34_totcon) as areac,sum(j36_testad) as testada from ($sql) as f group by j34_setor, j34_quadra");
$pdf->MultiCell(180,05,"TOTAIS POR SETOR/QUADRA",1,"C");
$pdf->SetFillColor(235);
$pdf->Cell(30,$tam,"SETOR(ES)",1,0,"C",1);
$pdf->Cell(30,$tam,"QUADRA(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"LOTE(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"ÁREA",1,0,"C",1);
$pdf->Cell(30,$tam,"ÁREA CONS",1,0,"C",1);
$pdf->Cell(30,$tam,"TESTADA(S)",1,1,"C",1);
$pdf->SetFillColor(255);
for($x=0;$x<pg_numrows($result1);$x++){
  db_fieldsmemory($result1,$x);
  $pdf->Cell(30,$tam,"".$j34_setor,1,0,"C",1);
  $pdf->Cell(30,$tam,"".$j34_quadra,1,0,"C",1);
  $pdf->Cell(30,$tam,"".$lotes,1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($area,'f'),1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($areac,'f'),1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($testada,'f'),1,1,"C",1);
}
//include("fpdf151/geraarquivo.php");
*/
$pdf->output();
?>