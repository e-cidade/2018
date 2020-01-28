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

require_once("fpdf151/pdf.php");
require_once("classes/db_iptuconstr_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_lote_classe.php");

$cllote = new cl_lote;
$cliptuconstr = new cl_iptuconstr;
$cliptuconstr1 = new cl_iptuconstr;
$cliptubase = new cl_iptubase;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$instit = db_getsession("DB_instit");

$head4 = "DÉBITOS ";
$head2 = "Relatório Débitos por Matricula";
if($com_deb == "dv"){
	$head6 = " Somente Débitos Vencidos ";
}elseif($com_deb == "nv"){
	$head6 = " Somente Débitos Não Vencidos ";
}elseif($com_deb == "sd"){
	$head6 = " Somente Sem  Débitos ";
}else{
	$head6 = " Todos os Tipo de Débitos ";
}
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);

$codigo = "";
$where  = "";
$fundo = 0;
if(isset($ordem) && $ordem != ""){
  if($ordem == "matric"){
    $ordem = " order by j01_matric";
    $ordem1 = " MATRÍCULA";
  }elseif($ordem == "nome"){
    $ordem = " order by z01_nome";
    $ordem1 = " NOME";
  }elseif($ordem == "tipodeb"){
    $ordem = " order by arretipo.k00_descr";
    $ordem1 = " TIPO DE DÉBITO";
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
$where = "";
$mais = "";

$sql  = "select iptubase.j01_matric,
                lote2.j34_setor, lote2.j34_quadra, lote2.j34_lote,
                arretipo.k00_descr,
				z01_nome,
				coalesce( (select max(j39_matric)
				             from iptuconstr
							where j39_matric = iptubase.j01_matric) ,0) as j39_matric,
                coalesce( (select sum(j39_area)
				             from iptuconstr
						    where j39_matric = iptubase.j01_matric
				              and j39_dtdemo is null) ,0) as j39_area,

				sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as total\n
		   from iptubase \n ";

if(isset($j34_loteam) && $j34_loteam != ""){
  $sql .=" inner join loteloteam on iptubase.j01_idbql = loteloteam.j34_idbql\n";
  $consta = ($loteam == "t"?"in":" not in");
  $where .= " loteloteam.j34_loteam $consta ($j34_loteam)";
  $mais = " and ";
}
if(isset($j14_comruas) && $j14_comruas != ""){
  $consta = ($qruas == "t"?"in":" not in");
  $sql .=" inner join testada on testada.j36_idbql = iptubase.j01_idbql\n
  	   inner join face on face.j37_face = testada.j36_face\n ";
  $where .= $mais." j37_codigo $consta ($j14_comruas)";
  $mais = " and ";
}
$where_deb = "";
if(isset($debit) && $debit != ""){
  $consta = ($tipos == "t"?"in":" not in");
  $where_deb = " and debitos.k22_tipo $consta ($debit)";
}
if(isset($setor) && $setor != ""){
  $sql .=" inner join lote on lote.j34_idbql = iptubase.j01_idbql \n ";
  $setores = $setor;
  if($setor == ""){
    $setor = "";
    $quadra = "";
  }else{
    $setor1 = $setor;
    $quadra1 = $quadra;
    if(isset($setor) && $setor != ""){

      $chaves  = split(",",$setor);
      $chaves1 = split(",",$quadra);
      $and   = "";
      $setor = "( ";
      for($i=0;$i<sizeof($chaves);$i++){
	      $setor .= $and." (lote.j34_setor = '".$chaves[$i]."' and lote.j34_quadra = '".$chaves1[$i]."')";
	      $and = " or ";
      }
      $setor .= " ) ";
    }
  }
  $where .= $mais . $setor;
  $mais   = " and ";
}
$res = db_query("   select k22_data as ult_data
					from debitos
					where k22_instit = $instit
					order by k22_data desc limit 1");
db_fieldsmemory($res,0);

if($com_deb == "dv"){
	$where .= " $mais k22_dtvenc < '".(date('Y-m-d',db_getsession('DB_datausu')))."'";
	$head6 = " Somente Débitos Vencidos ";
}elseif($com_deb == "nv"){
	$where .= " $mais k22_dtvenc >= '".(date('Y-m-d',db_getsession('DB_datausu')))."'";
	$head6 = " Somente Débitos Não Vencidos ";
}elseif($com_deb == "sd"){
	$where .= " $mais k22_numpre is null ";
	$head6 = " Somente Sem  Débitos ";
}else{

}

$sql .=" left join (select * from debitos
                       where debitos.k22_data   = '$ult_data'
                         and debitos.k22_instit = $instit
                         and debitos.k22_matric is not null $where_deb) as x\n
         on iptubase.j01_matric = x.k22_matric
	 left join proprietario_nome on iptubase.j01_matric = proprietario_nome.j01_matric\n
        left join arretipo on x.k22_tipo = arretipo.k00_tipo\n ";

$sql .=" inner join lote lote2 on lote2.j34_idbql = iptubase.j01_idbql\n

".(trim($where) == ""?"":"where")." $where
group by iptubase.j01_matric, lote2.j34_setor, lote2.j34_quadra, lote2.j34_lote, arretipo.k00_descr,z01_nome $ordem $modo";

$result = db_query($sql);
$numrows = pg_numrows($result);
$matric = "";
$idcons = "";
$area   = "";
$areama   = "";
$areame   = "99999";
$matricula = 0;
$tam = '04';
$vtotal = 0;

if($resumido == 'f'){
  $pdf->SetFont('Arial','B',7);
  $pdf->SetFillColor(235);
  $pdf->Cell(18,$tam,"MATRÍCULA",1,0,"C",1);
  $pdf->Cell(10,$tam,"SETOR",1,0,"C",1);
  $pdf->Cell(15,$tam,"QUADRA",1,0,"C",1);
  $pdf->Cell(15,$tam,"LOTE",1,0,"C",1);
  $pdf->Cell(40,$tam,"TIPO DE DÉBITO",1,0,"L",1);
  $pdf->Cell(75,$tam,"NOME",1,0,"L",1);
  $pdf->Cell(20,$tam,"TOTAL",1,1,"C",1);
  $pdf->SetFont('Arial','',7);
  $pdf->SetFillColor(255);

  for($s=0;$s<$numrows;$s++){
	$fundo = ($fundo == 1) ? 0 : 1;
    db_fieldsmemory($result,$s);
    if ($matricula <> $j01_matric) {
      $matricula = $j01_matric;
      $nome = $z01_nome;
      $matric += 1;
    }else{
      $matricula = "";
      $nome = "";
    }
    $nome = $z01_nome;
    $matricula = $j01_matric;
    $pdf->SetFillColor(225);
    $pdf->Cell(18,$tam,$matricula,1,0,"C",$fundo);
    $pdf->Cell(10,$tam,@$j34_setor,1,0,"C",$fundo);
    $pdf->Cell(15,$tam,@$j34_quadra,1,0,"C",$fundo);
    $pdf->Cell(15,$tam,@$j34_lote,1,0,"C",$fundo);
    $pdf->Cell(40,$tam,(@$k00_descr == ""?" SEM DÉBITOS":$k00_descr),1,0,"L",$fundo);
    $pdf->Cell(75,$tam,$nome,1,0,"L",$fundo);
    $pdf->Cell(20,$tam,db_formatar($total,'f'),1,1,"R",$fundo);
    $vtotal += $total;
    $pdf->SetFont('Arial','',7);
    if ( $pdf->GetY() > $pdf->h - 30 ) { //&& ($s + 1) != $numrows) {
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',7);
      $pdf->SetFillColor(235);
      $pdf->Cell(18,$tam,"MATRÍCULA",1,0,"C",1);
      $pdf->Cell(10,$tam,"SETOR",1,0,"C",1);
      $pdf->Cell(15,$tam,"QUADRA",1,0,"C",1);
      $pdf->Cell(15,$tam,"LOTE",1,0,"C",1);
      $pdf->Cell(40,$tam,"TIPO DE DÉBITO",1,0,"L",1);
      $pdf->Cell(75,$tam,"NOME",1,0,"L",1);
      $pdf->Cell(20,$tam,"TOTAL",1,1,"C",1);
      $pdf->SetFont('Arial','',7);
      $pdf->SetFillColor(255);
    }
  }
}else{ ///////////////////////////////RELATORIO RESUMIDO//////////////////////////////////////////////
 for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    if ($matricula <> $j01_matric) {
      $matric += 1;
      $matricula = $j01_matric;
    }
    $area += $j39_area;
    if($j39_area > $areama){
      $areama = $j39_area;

    }
    if($j39_area < $areame){
      $areame = $j39_area;
    }
	$vtotal += $total;
  }
}
/////////////// propriedades /////////////////////
if($resumido == 'f'){
  $pdf->AddPage(); // adiciona uma pagina
}
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(235);
$pdf->MultiCell(190,05,"Propriedades do Relatório",1,"C",1);
$pdf->Ln(5);
$pdf->SetFillColor(255);
$pdf->SetFont('Arial','',7);
$pdf->MultiCell(190,05,"TOTAL DE MATRÍCULAS: $matric",1,"L");
$pdf->MultiCell(190,05,"TOTAL DOS DÉBITOS:   $vtotal",1,"L");
if(isset($j14_comruas) && $j14_comruas != ""){
  $vir = "";
  $cod = "";
  $result1 = db_query("select j14_nome from ruas where j14_codigo in ($j14_comruas)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j14_nome;
	$vir=", ";
      }
  $consta = ($qruas == "t"?"SOMENTE A(S) RUA(S) -> ":" SEM A(S) RUA(S) -> ");
  $pdf->MultiCell(190,05,$consta.$cod,1,"L");
}elseif(isset($j14_comruas) && $j14_comruas == ""){
  $pdf->MultiCell(190,05,"LOGRADOUROS: TODOS OS LOGRADOUROS SELECIONADOS",1,"L");
}
if(isset($debit) && $debit != ""){
  $vir = "";
  $cod = "";
  $result1 = db_query("select k00_descr from arretipo where k00_tipo in ($debit)");
      for($x  = 0; $x < pg_numrows($result1);$x++){
		db_fieldsmemory($result1,$x);
		$cod .= $vir.$k00_descr;
		$vir=", ";
      }
  $consta = ($tipos == "t"?"SOMENTE O(S) DÉBITO(S) -> ":" SEM O(S) DÉBITOS(S) -> ");
  $pdf->MultiCell(190,05,$consta.$cod,1,"L");
}elseif(isset($debit) && $debit == ""){
  $pdf->MultiCell(190,05,"DÉBITOS: TODOS OS DÉBITOS SELECIONADOS",1,"L");
}
if(isset($j34_loteam) && $j34_loteam != ""){
  $vir = "";
  $cod = "";
  $result1 = db_query("select j34_descr from loteam where j34_loteam in ($j34_loteam)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j34_descr;
	$vir=", ";
      }
  $consta = ($tipos == "t"?"SOMENTE O(S) LOTEAMENTO(S) -> ":" SEM O(S) LOTEAMENTO(S) -> ");
  $pdf->MultiCell(190,05,$consta.$cod,1,"L");
}elseif(isset($j34_loteam) && $j34_loteam == ""){
  $pdf->MultiCell(190,05,"LOTEAMENTOS: TODOS OS LOTEAMENTOS SELECIONADOS",1,"L");
}


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
  $pdf->MultiCell(190,05,"SOMENTE OS SETORES/QUADRAS -> ".$setores,1,"L");
}elseif(isset($setor) && $setor == ""){
  $pdf->MultiCell(190,05,"SETORES/QUADRAS: TODOS SELECIONADOS",1,"L");
}
if(isset($ordem) && $ordem != ""){
  $pdf->MultiCell(190,05,"ORDEM - ORDENADO POR ".$ordem1,1,"L");
}
if(isset($order) && $order != ""){
  $pdf->MultiCell(190,05,"MODO -".$modo1,1,"L");
}
$pdf->Output();