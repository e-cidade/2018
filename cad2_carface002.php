<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

$head4 = "RELATÓRIO DE FACE DE QUADRA";
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);
$codigo = "";
$where  = "";
$comcar = "";
$and = "";
$listadas = "";
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
  $comcar = " j38_caract in ($codigo)";
  $listadas = $codigo;
  $and = " and ";
  }
}
$semcar = "";
if($chaves_caract != ""){
  $semcar = " $and j38_caract not in ($chaves_caract)";
  $and = " and ";
}
if(isset($ordem) && $ordem != ""){
  if($ordem == "codigo"){
    $ordem = " order by j37_codigo";
    $ordem1 = " RUA";
  }elseif($ordem == "setor"){
    $ordem = " order by j37_setor";
    $ordem1 = " SETOR";
  }elseif($ordem == "quadra"){
    $ordem = " order by j37_quadra";
    $ordem1 = " QUADRA";
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
      $or = "";
      $setor = "";
      for($i=0;$i<sizeof($chaves);$i++){
	$setor .= $or." (j37_setor = '".$chaves[$i]."' and j37_quadra = '".$chaves1[$i]."')";
	$or = " or ";
      }
    }
  }
  $setor = " $and (".$setor.")";
  $and = " and ";
}
$sql = "select face.*,j14_nome 
from face 
	inner join carface on j38_face = j37_face 
	inner join caracter on j31_codigo = j38_caract"; 
$testruas = "";
if($ruas != ""){
  if($comcar == "" && $semcar == ""){
    $and = "";
  }else{
    $and = " and ";
  }
  if($temruas == "t"){
    $testruas = $and." j14_codigo in ($ruas)"; 
  }else{
    $testruas = $and." j14_codigo not in ($ruas)"; 
  }
}
$sql .=" inner join ruas on j37_codigo = j14_codigo ";
$where = " $comcar $semcar $testruas $setor";
$where = (trim($where) != ""?" where ".$where:"");
$sql .= $where;
if(isset($j32_grupo) && $j32_grupo != ""){
  $sql = "select c.*,caracter.j31_descr from ($sql) as c left join carface on carface.j38_face = c.j37_face left join caracter on caracter.j31_codigo = carface.j38_caract where caracter.j31_grupo = $j32_grupo";
}
$pontuacao = "";
if(isset($pontini) && $pontini != ""){
  if(isset($pontfim) && $pontfim != ""){
    $pontuacao = " where j31_pontos >= $pontini and j31_pontos <= $pontfim";
    $pontuacao1 = "PONTUAÇÃO MAIOR OU IGUAL À $pontini E MENOR OU IGUAL À $pontfim";
  }else{
    $pontuacao = " where j31_pontos >= $pontini";
    $pontuacao1 = "PONTUAÇÃO MAIOR OU IGUAL À $pontini";
  }
}elseif(isset($pontfim) && $pontfim != ""){
  $pontuacao = " where j31_pontos <= $pontfim";
  $pontuacao1 = " PONTUAÇÃO MENOR OU IGUAL À $pontfim";
}
$sql = "select distinct * 
          from (select * 
                  from (select * 
                          from ($sql) as tudo 
                               inner join (select j38_face, 
                                                  sum(j31_pontos) as j31_pontos 
                                             from carface 
                                                  inner join face on j37_face = j38_face 
                                                  inner join caracter on j38_caract = j31_codigo 
                                            group by j38_face) as pontos 
                                       on tudo.j37_face = pontos.j38_face $pontuacao
                       ) as ordem 
               ) as distincao $ordem $order"; 
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
  $pdf->SetFont('Arial','',9);
  $pdf->SetFillColor(235);
  $pdf->Cell(20,$tam,"SETOR",1,0,"C",1);
  $pdf->Cell(22,$tam,"QUADRA",1,0,"C",1);
  $pdf->Cell(20,$tam,"COD.LOG",1,0,"C",1);
  $pdf->Cell(60,$tam,"LOGRADOURO",1,0,"C",1);
  $pdf->Cell(30,$tam,"LADO",1,0,"C",1);
  if(isset($j32_descr) && $j32_descr != ""){
    $pdf->Cell(20,$tam,"PONTUAÇÃO",1,0,"C",1);
    $pdf->Cell(30,$tam,"GRUPO",1,1,"C",1);
  }else{
    $pdf->Cell(30,$tam,"PONTUAÇÃO",1,1,"C",1);
  }
  $pdf->SetFillColor(255);
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    $pdf->Cell(20,$tam,$j37_setor,1,0,"C",0);
    $pdf->Cell(22,$tam,$j37_quadra,1,0,"C",0);
    $pdf->Cell(20,$tam,$j37_codigo,1,0,"C",0);
    $pdf->Cell(60,$tam,$j14_nome,1,0,"C",0);
    $pdf->Cell(30,$tam,$j37_lado,1,0,"C",0);
    $pdf->Cell(30,$tam,'',1,1,"C",0);
    $pdf->SetFont('Arial','',9);
    if ( $pdf->GetY() > 265 && ($s + 1) != $numrows) {
      $pdf->AddPage();
      $pdf->SetFillColor(235);
      $pdf->Cell(20,$tam,"SETOR",1,0,"C",1);
      $pdf->Cell(22,$tam,"QUADRA",1,0,"C",1);
      $pdf->Cell(20,$tam,"COD.LOG",1,0,"C",1);
      $pdf->Cell(60,$tam,"LOGRADOURO",1,0,"C",1);
      $pdf->Cell(30,$tam,"LADO",1,0,"C",1);
      if(isset($j32_descr) && $j32_descr != ""){
	$pdf->Cell(20,$tam,"PONTUAÇÃO",1,0,"C",1);
	$pdf->Cell(30,$tam,"GRUPO",1,1,"C",1);
      }else{
	$pdf->Cell(30,$tam,"PONTUAÇÃO",1,1,"C",1);
      }
      $pdf->SetFillColor(255);
    }
  }
}
/////////////// propriedades /////////////////////
if($resumido == 'f'){
  $pdf->AddPage(); // adiciona uma pagina
}
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(235);
$pdf->MultiCell(280,05,"Propriedades do Relatório",1,"C",1);
$pdf->Ln(5);
$pdf->SetFillColor(255);
$pdf->SetFont('Arial','',7);
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
$pdf->ln(5);
$pdf->SetFillColor(255);
//include("fpdf151/geraarquivo.php");
$pdf->output();
?>