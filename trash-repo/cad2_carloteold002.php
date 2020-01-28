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

$head4 = "RELAT�RIO DE LOTES";
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);
$codigo = "";
$where  = "";
$comcar = "";
if(isset($relatorio1)){
  if(isset($chaves) && $chaves != ""){
    $chaves = split("#",$chaves);
    for($i=0;$i<sizeof($chaves);$i++){
      if($codigo == ""){
	$codigo .= substr($chaves[$i],0,(strpos($chaves[$i],"-")));
      }else{
	$codigo .= ",".substr($chaves[$i],0,(strpos($chaves[$i],"-")));
      }
    }
  $comcar = " j35_caract in ($codigo)";
  }
  $listadas = $codigo;
}
$semcar = "";
if($chaves_caract != ""){
  $semcar = " and j35_caract not in ($chaves_caract)";
}
if(isset($ordem) && $ordem != ""){
  if($ordem == "area"){
    $ordem = " order by j34_area";
    $ordem1 = " �REA";
  }elseif($ordem == "areacons"){
    $ordem = " order by j34_totcon";
    $ordem1 = " �REA CONSTRU�DA";
  }elseif($ordem == "sql"){
    $ordem = " order by j34_setor,j34_quadra,j34_lote";
    $ordem1 = " SETOR, QUADRA, LOTE";
  }elseif($ordem == "idbql"){
    $ordem = " order by j34_idbql";
    $ordem1 = " IDBQL";
  }elseif($ordem == "lograd"){
    $ordem = " order by j14_nome, j39_numero, j39_compl";
    $ordem1 = " LOGRADOURO";
  }elseif($ordem == "zona"){
    $ordem = " order by j34_zona";
    $ordem1 = " ZONA FISCAL";
  }
}
$modo = "";
if(isset($order) && $order != ""){
  if($order == "asc"){
    $modo = " asc ";
    $modo1 = " ASCENDENTE ";
  }elseif($order == "desc"){
    $modo = " desc ";
    $modo1 = " DESCENDENTE ";
  }
}

$arealote = "";
if(isset($loteini) && $loteini != ""){
  if(isset($lotefim) && $lotefim != ""){
    $arealote = " and j34_area >= $loteini and j34_area <= $lotefim";
    $arealote1 = "�REA MAIOR OU IGUAL � $loteini E MENOR OU IGUAL � $lotefim";
  }else{
    $arealote = " and j34_area >= $loteini";
    $arealote1 = "�REA MAIOR OU IGUAL � $loteini";
  }
}elseif(isset($lotefim) && $lotefim != ""){
  $arealote = " and j34_area <= $lotefim";
  $arealote1 = "�REA MENOR OU IGUAL � $lotefim";
}

$testada = "";
if(isset($testini) && $testini != ""){
  if(isset($testfim) && $testfim != ""){
    $testada = " and j36_testad >= $testini and j36_testad <= $testfim";
    $testada1 = "TESTADA MAIOR OU IGUAL � $testini E MENOR OU IGUAL � $testfim";
  }else{
    $testada = " and j36_testad >= $testini";
    $testada1 = "TESTADA MAIOR OU IGUAL � $testini";
  }
}elseif(isset($testfim) && $testfim != ""){
  $testada = " and j36_testad <= $testfim";
  $testada1 = "TESTADA MENOR OU IGUAL � $testfim";
}

$areacons = "";
if(isset($cini) && $cini != ""){
  if(isset($cfim) && $cfim != ""){
    $areacons = " and j34_totcon >= $cini and j34_totcon <= $cfim";
    $areacons1 = "�REA CONSTRU�DA MAIOR OU IGUAL � $cini E MENOR OU IGUAL � $cfim";
  }else{
    $areacons = " and j34_totcon >= $cini";
    $areacons1 = "�REA CONSTRU�DA MAIOR OU IGUAL � $cini";
  }
}elseif(isset($cfim) && $cfim != ""){
  $areacons = " and j34_totcon <= $cfim";
  $areacons1 = "�REA CONSTRU�DA MENOR OU IGUAL � $cfim";
}

if(isset($setor) && $setor != ""){
  $setores = $setor;
  if($setor == ""){
    $setor = "";
    $quadra = "";
  }else{
    $setor1 = $setor;
    $quadra1 = $quadra;
    if(isset($setor) && $setor != ""){
      $chaves = split(",",$setor);
      $chaves1 = split(",",$quadra);
      $and = "";
      $setor = "";
      for($i=0;$i<sizeof($chaves);$i++){
	$setor .= $and." (j34_setor = '".$chaves[$i]."' and j34_quadra = '".$chaves1[$i]."')";
	$and = " or ";
      }
    }
  }
  $setor = " and (".$setor.")";
}
$sql = "select j34_idbql,j34_setor,j34_quadra,j34_lote,j34_area,j34_totcon,j34_zona,j14_nome,j36_testad,j13_descr 
from lote 
	inner join carlote on j35_idbql=j34_idbql 
	inner join caracter on j31_codigo = j35_caract 
	inner join testada on j36_idbql=j34_idbql 
	inner join bairro on j13_codi = j34_bairro";
$testruas = "";
if($ruas == ""){
  $sql .= " inner join testpri on j36_idbql = j49_idbql ";
}else{
  $and = " and ";
  if($temruas == "t"){
    $testruas = $and." j14_codigo in ($ruas)"; 
  }else{
    $testruas = $and." j14_codigo not in ($ruas)"; 
  }
}
$sql .=" inner join ruas on j36_codigo = j14_codigo ";
$where = " where *$comcar $semcar $testruas $setor $arealote $testada $areacons";
$sql .= $where;
if(isset($j32_grupo) && $j32_grupo != ""){
  $sql = "select c.*,caracter.j31_descr from ($sql) as c left join carlote on carlote.j35_idbql = c.j34_idbql left join caracter on caracter.j31_codigo = carlote.j35_caract where caracter.j31_grupo = $j32_grupo";
}
$pontuacao = "";
if(isset($pontini) && $pontini != ""){
  if(isset($pontfim) && $pontfim != ""){
    $pontuacao = " where j31_pontos >= $pontini and j31_pontos <= $pontfim";
    $pontuacao1 = "PONTUA��O MAIOR OU IGUAL � $pontini E MENOR OU IGUAL � $pontfim";
  }else{
    $pontuacao = " where j31_pontos >= $pontini";
    $pontuacao1 = "PONTUA��O MAIOR OU IGUAL � $pontini";
  }
}elseif(isset($pontfim) && $pontfim != ""){
  $pontuacao = " where j31_pontos <= $pontfim";
  $pontuacao1 = " PONTUA��O MENOR OU IGUAL � $pontfim";
}
$sql = "select ordem.* from (select * from ($sql) as tudo inner join (select j35_idbql, sum(j31_pontos) as j31_pontos from carlote inner join lote on j34_idbql = j35_idbql inner join caracter on j35_caract = j31_codigo group by j35_idbql) as pontos on tudo.j34_idbql = pontos.j35_idbql $pontuacao) as ordem $ordem $order"; 
echo $sql;exit;
$result = pg_exec($sql);
$numrows = pg_numrows($result);
$matric = "";
$idcons = "";
$area   = "";
$areama   = "";
$areame   = "99999";
$matricula = 0;
$tam = '04';
if($resumido == 'f'){
  $pdf->SetFont('Arial','',7);
  $pdf->SetFillColor(235);
  $pdf->Cell(25,$tam,"C�DIGO DO LOTE",1,0,"C",1);
  $pdf->Cell(10,$tam,"SETOR",1,0,"C",1);
  $pdf->Cell(12,$tam,"QUADRA",1,0,"C",1);
  $pdf->Cell(10,$tam,"LOTE",1,0,"C",1);
  $pdf->Cell(20,$tam,"�REA",1,0,"C",1);
  $pdf->Cell(20,$tam,"�REA CONSTR",1,0,"C",1);
  $pdf->Cell(20,$tam,"ZONA FISCAL",1,0,"C",1);
  $pdf->Cell(50,$tam,"RUA",1,0,"C",1);
  $pdf->Cell(30,$tam,"BAIRRO",1,0,"C",1);
  $pdf->Cell(20,$tam,"TESTADA",1,0,"C",1);
  if(isset($j32_descr) && $j32_descr != ""){
    $pdf->Cell(20,$tam,"PONTUA��O",1,0,"C",1);
    $pdf->Cell(30,$tam,"GRUPO",1,1,"C",1);
  }else{
    $pdf->Cell(20,$tam,"PONTUA��O",1,1,"C",1);
  }
  $pdf->SetFillColor(255);
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    if ($matricula <> @$j39_matric) {
      $matricula = @$j39_matric;
      $nome = @$z01_nome;
      $matric += 1; 
    }else{
      $matricula = "";
      $nome = "";
    }
    $pdf->Cell(25,$tam,$j34_idbql,1,0,"C",0);
    $pdf->Cell(10,$tam,$j34_setor,1,0,"C",0);
    $pdf->Cell(12,$tam,$j34_quadra,1,0,"C",0);
    $pdf->Cell(10,$tam,$j34_lote,1,0,"C",0);
    $pdf->Cell(20,$tam,db_formatar($j34_area,'f'),1,0,"R",0);
    $pdf->Cell(20,$tam,db_formatar($j34_totcon,'f'),1,0,"R",0);
    $nome = @$z01_nome;
    $matricula = @$j39_matric;
    $area += @$j39_area;
    if(@$j39_area > $areama){
      $areama = @$j39_area;
    }
    if(@$j39_area < $areame){
      $areame = @$j39_area;
    }
    $pdf->Cell(20,$tam,@$j34_zona,1,0,"C",0);
    $pdf->Cell(50,$tam,@$j14_nome,1,0,"L",0);
    $pdf->Cell(30,$tam,@$j13_descr,1,0,"C",1);
    $pdf->Cell(20,$tam,db_formatar($j36_testad,'f'),1,0,"R",0);
    if(isset($j32_descr) && $j32_descr != ""){
      $pdf->Cell(20,$tam,@$j31_pontos,1,0,"C",1);
      $pdf->Cell(30,$tam,$j32_descr,1,1,"C",1);
    }else{
      $pdf->Cell(20,$tam,@$j31_pontos,1,1,"C",1);
    }
    $pdf->SetFont('Arial','',7);
    if ( $pdf->GetY() > 185 && ($s + 1) != $numrows) {
      $pdf->AddPage('l');
      $pdf->SetFillColor(235);
      $pdf->Cell(25,$tam,"C�DIGO DO LOTE",1,0,"C",1);
      $pdf->Cell(10,$tam,"SETOR",1,0,"C",1);
      $pdf->Cell(12,$tam,"QUADRA",1,0,"C",1);
      $pdf->Cell(10,$tam,"LOTE",1,0,"C",1);
      $pdf->Cell(20,$tam,"�REA",1,0,"C",1);
      $pdf->Cell(20,$tam,"�REA CONSTR",1,0,"C",1);
      $pdf->Cell(20,$tam,"ZONA FISCAL",1,0,"C",1);
      $pdf->Cell(50,$tam,"RUA",1,0,"C",1);
      $pdf->Cell(30,$tam,"BAIRRO",1,0,"C",1);
      $pdf->Cell(20,$tam,"TESTADA",1,0,"C",1);
      if(isset($j32_descr) && $j32_descr != ""){
	$pdf->Cell(20,$tam,"PONTUA��O",1,0,"C",1);
	$pdf->Cell(30,$tam,"GRUPO",1,1,"C",1);
      }else{
	$pdf->Cell(20,$tam,"PONTUA��O",1,1,"C",1);
      }
      $pdf->SetFillColor(255);
    }
  }
}else{///////////////////////////////RELATORIO RESUMIDO//////////////////////////////////////////////
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    if ($matricula <> @$j39_matric) {
      $matric += 1; 
    }
    $area += @$j39_area;
    if(@$j39_area > $areama){
      $areama = @$j39_area;
    }
    if(@$j39_area < $areame){
      $areame = @$j39_area;
    }
  }
}
/////////////// propriedades /////////////////////
if($resumido == 'f'){
  $pdf->AddPage('L'); // adiciona uma pagina
}
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(235);
$pdf->MultiCell(280,05,"Propriedades do Relat�rio",1,"C",1);
$pdf->Ln(5);
$pdf->SetFillColor(255);
$pdf->SetFont('Arial','',7);
$vir = "";
$cod = "";
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
$vir = "";
$cod = "";
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
$result1 = pg_exec("select distinct j31_descr,j31_codigo from carlote inner join caracter on j35_caract=j31_codigo where j31_codigo in ($listadas)");
    for($x=0;$x<pg_numrows($result1);$x++){
      db_fieldsmemory($result1,$x);
      $cod .= $vir.$j31_codigo." - ".$j31_descr;
      $vir=", ";
    }
$pdf->MultiCell(280,05,"CARACTER�STICAS LISTADAS ->  ".$cod,1,"L");
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
$pdf->MultiCell(280,05,"CARACTER�STICAS N�O LISTADAS ->  ".@$cod,1,"L");
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
  $pdf->MultiCell(280,05,"TOTAIS DOS LOTES COM CARACTER�STICA DO GRUPO - $j32_descr ",1,"L");
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
$sql1 = "select j34_setor, j34_quadra, count(j34_idbql) as lote,sum(j34_area) as area,sum(j34_totcon) as areac,sum(j36_testad) as testada from ($sql) as f group by j34_setor, j34_quadra";
$result1 = pg_exec($sql1);
$pdf->MultiCell(180,05,"TOTAIS POR SETOR/QUADRA",1,"C");
$pdf->SetFillColor(235);
$pdf->Cell(30,$tam,"SETOR(ES)",1,0,"C",1);
$pdf->Cell(30,$tam,"QUADRA(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"LOTE(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"�REA",1,0,"C",1);
$pdf->Cell(30,$tam,"�REA CONS",1,0,"C",1);
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

$sql2 = "select j34_setor,count(j34_quadra) as quadra from ($sql1) as s group by j34_setor";
echo $sql2;exit;
$result1 = pg_exec($sql2);
$pdf->MultiCell(150,05,"TOTAIS POR SETOR",1,"C");
$pdf->SetFillColor(235);
$pdf->Cell(30,$tam,"SETOR(ES)",1,0,"C",1);
$pdf->Cell(30,$tam,"QUADRA(S)",1,0,"C",1);
//$pdf->Cell(30,$tam,"LOTE(S)",1,0,"C",1);
$pdf->Cell(30,$tam,"�REA",1,0,"C",1);
$pdf->Cell(30,$tam,"�REA CONS",1,0,"C",1);
$pdf->Cell(30,$tam,"TESTADA(S)",1,1,"C",1);
$pdf->SetFillColor(255);
for($x=0;$x<pg_numrows($result1);$x++){
  db_fieldsmemory($result1,$x);
  $pdf->Cell(30,$tam,"".$j34_setor,1,0,"C",1);
  $pdf->Cell(30,$tam,"".$quadra,1,0,"C",1);
  //$pdf->Cell(30,$tam,"".$lotes,1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($area,'f'),1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($areac,'f'),1,0,"C",1);
  $pdf->Cell(30,$tam,"".db_formatar($testada,'f'),1,1,"C",1);
}
$pdf->ln(5);
include("fpdf151/geraarquivo.php");
?>