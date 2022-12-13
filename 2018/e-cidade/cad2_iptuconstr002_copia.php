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
$head4 = "CARACTER�STICAS DE CONTRU��ES";
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);
$codigo = "";
$where  = "";
if(isset($relatorio1)){
	// $chaves = s�o as caracteristicas da aba com as caracteristicas
	if(isset($chaves) && $chaves != ""){
    $chaves = split("#",$chaves);
    for($i=0;$i<sizeof($chaves);$i++){
      // concatena na $codigo o c�digo das caracteristicas selecionadas
      if($codigo == ""){
	$codigo .= substr($chaves[$i],0,(strpos($chaves[$i],"-")));
      }else{
	$codigo .= ",".substr($chaves[$i],0,(strpos($chaves[$i],"-")));
      }
    }
  $where = " j48_caract in ($codigo)";
  }
  //   $listadas= caracteristicas
  $listadas = $codigo;
}
$ncaract = "";

// $chaves_caract = s�o as caracteristicas da aba sem caracteristicas
if(isset($chaves_caract) && $chaves_caract != ""){
  $sql_not = "select j48_matric, j48_idcons from (
						 select x.j48_matric as j48_matric,
						 	x.j48_idcons as j48_idcons,
							y.j48_matric as matric_notin,
							y.j48_idcons as idcons_notin from 
											  (select distinct j48_matric,
											  		   j48_idcons from carconstr
											   where j48_caract in ($listadas)) as x
										   	   left join
											  (select distinct j48_matric,
											  	           j48_idcons from carconstr
											   where j48_caract in ($chaves_caract)) as y
											   on x.j48_matric = y.j48_matric and x.j48_idcons = y.j48_idcons) as z
				where matric_notin is null
";
}
//ordenar por 
if(isset($ordem) && $ordem != ""){
  if($ordem == "matric"){
    $ordem = " order by j39_matric, j39_idcons";
    $ordem1 = " MATR�CULA";
  }elseif($ordem == "nome"){
    $ordem = " order by z01_nome";
    $ordem1 = " NOME";
  }elseif($ordem == "ano"){
    $ordem = " order by j39_ano";
    $ordem1 = " ANO DA CONSTRU��O";
  }elseif($ordem == "area"){
    $ordem = " order by j39_area";
    $ordem1 = " �REA";
  }elseif($ordem == "dtlan"){
    $ordem = " order by j39_dtlan";
    $ordem1 = " DATA DE LAN�AMENTO DA CONSTRU��O";
  }elseif($ordem == "lograd"){
    $ordem = " order by j14_nome, j39_numero, j39_compl";
    $ordem1 = " LOGRADOURO";
  }elseif($ordem == "sql"){
    $ordem = " order by j34_setor, j34_quadra, j34_lote";
    $ordem1 = " SETOR/QUADRA/LOTE";
  }
}
//modo
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
//grupo
$grupo = "";
if(isset($j32_grupo) && $j32_grupo != ""){
  $grupo = " $j32_grupo";
}
// data de inclus�o
if(isset($j39_dtlan_dia) && $j39_dtlan_dia != ""){
  $j39_dtlan = $j39_dtlan_ano."-".$j39_dtlan_mes."-".$j39_dtlan_dia;
}

if(@$j39_dtlanfim_dia != ""){
  $j39_dtlanfim = $j39_dtlanfim_ano."-".$j39_dtlanfim_mes."-".$j39_dtlanfim_dia;
}else{
	$j39_dtlanfim ="--";
}
//habite
if(isset($habite) && $habite != ""){
  $and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == ""?"":"and");
  if($habite == "th"){
    $habite = "";
    $habite1 = "CONTRU��ES COM HABITE-SE E SEM HABITE-SE";
  }elseif($habite == "ch"){
    $habite = " $and j39_habite is not null ";
    $habite1 = "CONTRU��ES COM HABITE-SE";
  }elseif($habite == "sh"){
    $habite = " $and j39_habite is null ";
    $habite1 = "CONTRU��ES SEM HABITE-SE";
  }
}else{
  $habite = "";
}
//*******************************
$sql_ruas = "";

if(isset($setor) && $setor != ""){
  $setores = $setor;
  $and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == "" && $habite == ""?"":"and");
    
  if($setor == ""){
    $setor = "";
    $quadra = "";
  }else{
    $setor1 = $setor;
    $quadra1 = $quadra;
    $sql_ruas = " inner join iptubase on iptubase.j01_matric = j39_matric inner join testada on iptubase.j01_idbql = j36_idbql inner join face on j36_face = j37_face ";
    if(isset($setor) && $setor != ""){
      $chaves = split(",",$setor);
      $chaves1 = split(",",$quadra);
      $and = "";
      $setor = "";
      for($i=0;$i<sizeof($chaves);$i++){
	$setor .= $and." (j37_setor = '".$chaves[$i]."' and j37_quadra = '".$chaves1[$i]."')";
	$and = " or ";
      }
    }
    if(isset($j14_comruas) && $j14_comruas != "" && empty($j14_semruas)){
	$setor .= " and j39_codigo in ($j14_comruas)";
    }
    if(isset($j14_semruas) && $j14_semruas != "" && empty($j14_comruas)){
	$setor .= " and j39_codigo not in ($j14_semruas)";
    }
    if(isset($j14_semruas) && $j14_semruas != "" && isset($j14_comruas) && $j14_comruas != ""){
  	$sql_ruas = " select j39_matric,j39_idcons from (
                                                   select x.j39_matric,
						   	  x.j39_idcons,
							  y.j39_matric as matric_notrua,
							  y.j39_idcons as idcons_notrua from 
							  		 (select distinct j39_matric,
									 		  j39_idcons from iptubase 
										inner join iptuconstr 
											  on iptubase.j01_matric = j39_matric 
										inner join testada 
											  on iptubase.j01_idbql = j36_idbql 
										inner join face 
											  on j36_face = j37_face
									  where $setor and j39_codigo in ($j14_comruas) ) as x
										left join 
							  		 (select distinct j39_matric,
									 		  j39_idcons from iptubase 
										inner join iptuconstr 
											  on iptubase.j01_matric = j39_matric 
										inner join testada 
											  on iptubase.j01_idbql = j36_idbql 
										inner join face 
											  on j36_face = j37_face
									  where $setor and j39_codigo not in ($j14_semruas)) as y
						  on x.j39_matric = y.j39_matric and x.j39_idcons = y.j39_idcons) as z
		  where matric_notrua is not null";
	
    }
  }
}
if(isset($j14_comruas) && $j14_comruas != "" && empty($setor) && empty($j14_semruas)){
    $sql_ruas = " inner join iptubase on iptubase.j01_matric = j39_matric inner join testada on iptubase.j01_idbql = j36_idbql inner join face on j36_face = j37_face";
    $and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == "" && $habite == ""?"":"and");
    $setor = " $and j39_codigo in ($j14_comruas)";
}
if(isset($j14_semruas) && $j14_semruas != "" && empty($setor) && empty($j14_comruas)){
    $sql_ruas= " inner join iptubase on iptubase.j01_matric = j39_matric inner join testada on iptubase.j01_idbql = j36_idbql inner join face on j36_face = j37_face";
    $and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == "" && $habite == ""?"":"and");
    $setor = " $and j39_codigo not in ($j14_semruas)";
}
if(isset($j14_comruas) && $j14_comruas != "" && empty($setor) && isset($j14_semruas) && $j14_semruas != ""){
  $sql_ruas1 = " select j39_matric,j39_idcons from (
                                                   select x.j39_matric,
						   	  x.j39_idcons,
							  y.j39_matric as matric_notrua,
							  y.j39_idcons as idcons_notrua from 
							  		 (select distinct j39_matric,
									 		  j39_idcons from iptubase 
										inner join iptuconstr 
											  on iptubase.j01_matric = j39_matric 
										inner join testada 
											  on iptubase.j01_idbql = j36_idbql 
										inner join face 
											  on j36_face = j37_face
									  where j39_codigo in ($j14_comruas)) as x
										left join 
							  		 (select distinct j39_matric,
									 		  j39_idcons from iptubase 
										inner join iptuconstr 
											  on iptubase.j01_matric = j39_matric 
										inner join testada 
											  on iptubase.j01_idbql = j36_idbql 
										inner join face 
											  on j36_face = j37_face
									  where j39_codigo not in($j14_semruas)) as y
						  on x.j39_matric = y.j39_matric and x.j39_idcons = y.j39_idcons) as z
		  where matric_notrua is not null";
  $sql_ruas = "inner join ($sql_ruas1) as o on iptuconstr.j39_matric = o.j39_matric and iptuconstr.j39_idcons = o.j39_idcons"; 
}
if(isset($demo) && $demo != ""){
  $and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == "" && $habite == "" && $setor == ""?"":"and");
  if($demo == "tc"){
    $demo = "";
    $demo1 = "CONTRU��ES DEMOLIDAS E N�O DEMOLIDAS";
  }elseif($demo == "d"){
    $demo = " $and j39_dtdemo is not null ";
    $demo1 = "CONTRU��ES DEMOLIDAS";
  }elseif($demo == "nd"){
    $demo = " $and j39_dtdemo is null ";
    $demo1 = "CONTRU��ES N�O DEMOLIDAS";
  }
}
if(isset($origem) && $origem != ""){
  $and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == "" && $habite == "" && $setor == "" && $demo == ""?"":"and");
  if($origem == "to"){
    $origem = "";
    $origem1 = "CONTRU��ES AMPLIADAS E CONSTRU��ES NOVAS";
  }elseif($origem == "sn"){
    $origem = " $and j39_idaument = 0 ";
    $origem1 = "CONSTRU��ES NOVAS";
  }elseif($origem == "sa"){
    $origem = " $and coalesce(j39_idaument,0) <> 0 ";
    $origem1 = "CONTRU��ES AMPLIADAS";
  }
}
$data = "";
$and = ($areaini == "" && $areafim == "" && $anoini == "" && $anofim == "" && $habite != "" && $demo != "" && $origem != "" && $setor == ""?"":"and");
if(isset($j39_dtlan) && $j39_dtlan != "--" && $j39_dtlanfim == "--"){
  $data = " $and j39_dtlan >= '$j39_dtlan' ";
  $data1 = "DATA DE LAN�AMENTO MAIOR OU IGUAL � ".db_formatar($j39_dtlan,'d')." ";
}elseif(isset($j39_dtlan) && $j39_dtlan != "--" && isset($j39_dtlanfim) && $j39_dtlanfim != "--"){
  $data = " $and j39_dtlan >= '$j39_dtlan' and j39_dtlan <= '$j39_dtlanfim' ";
  $data2 = "DATA DE LAN�AMENTO MAIOR OU IGUAL � ".db_formatar($j39_dtlan,'d')." E MENOR OU IGUAL � ".db_formatar($j39_dtlanfim,'d').""; 
}elseif(isset($j39_dtlan) && $j39_dtlan == "--" && isset($j39_dtlanfim) && $j39_dtlanfim == "--"){
  $data = " $and j39_dtlan is not null";
}
if(isset($chaves_caract) && $chaves_caract != ""){
//  die ("111");

  $sql = "select iptuconstr.*, x.*, iptubase.*, testada.*, face.*, proprietario_nome.z01_nome from iptuconstr inner join ($sql_not) as p on iptuconstr.j39_matric = p.j48_matric and iptuconstr.j39_idcons = p.j48_idcons $sql_ruas inner join proprietario_nome on proprietario_nome.j01_matric = j48_matric ".($areaini != "" || $areafim != "" || $habite != "" || $data != "" || $anoini !="" || $anofim != "" || $setor != "" || $demo != "" || $origem != ""?"where":"")." ".($areaini != ""?" j39_area >= $areaini":"")." ".($areafim != "" && $areaini != ""?"and":"")." ".($areafim != ""?" j39_area <= $areafim and":"")." ".(($anoini != "" || $anofim != "") && ($areaini != "" || $areafim != "")?"and":"")." ".($anoini != ""?" j39_ano >= $anoini":"")." ".($anofim != "" && $anoini != ""?"and":"")." ".($anofim != ""?" j39_ano <= $anofim":"")." $habite $setor $demo $origem $data";
}else{
//  die ("222");
  $sql = "select distinct on (iptuconstr.j39_matric,iptuconstr.j39_idcons)
                 iptuconstr.*, x.*, iptubase.*, testada.*, face.*, proprietario_nome.z01_nome from iptuconstr 
                 inner join (select distinct j48_matric, j48_idcons from carconstr where $where) as x on iptuconstr.j39_matric = x.j48_matric and iptuconstr.j39_idcons = x.j48_idcons $sql_ruas inner join proprietario_nome on proprietario_nome.j01_matric = j48_matric ".($areaini != "" || $areafim != "" || $habite != "" || $data != "" || $anoini !="" || $anofim != ""  || $setor != "" || $demo != "" || $origem != ""?"where":"")." ".($areaini != ""?" j39_area >= $areaini":"")." ".($areafim != "" && $areaini != ""?"and":"")." ".($areafim != ""?" j39_area <= $areafim and ":"")."  ".(($anoini != "" || $anofim != "") && ($areaini != "" || $areafim != "")?"and":"")." ".($anoini != ""?" j39_ano >= $anoini":"")." ".($anofim != "" && $anoini != ""?"and":"")." ".($anofim != ""?" j39_ano <= $anofim":"")." $habite $setor $demo $origem $data";
}
if(isset($grupo) && $grupo != ""){
	
  $sql = "select c.*,caracter.j31_descr from ($sql) as c left join carconstr on carconstr.j48_matric = c.j39_matric and carconstr.j48_idcons = c.j39_idcons left join caracter on j31_codigo = j48_caract where j31_grupo = $grupo";
}

$sql = "select d.*, ruas.j14_nome, lote.j34_setor, lote.j34_quadra, j34_lote from ($sql) as d inner join ruas on j39_codigo = j14_codigo inner join lote on j01_idbql = j34_idbql $ordem $order";
$pontuacao = "";
if(isset($pontini) && $pontini != ""){
  if(isset($pontfim) && $pontfim != ""){
    $pontuacao = " where j31_pontos >= $pontini and j31_pontos <= $pontfim";
    $pontuacao1 = " PONTUA��O MAIOR OU IGUAL � $pontini E MENOR OU IGUAL � $pontfim";
  }else{
    $pontuacao = " where j31_pontos >= $pontini";
    $pontuacao1 = " PONTUA��O MAIOR OU IGUAL � $pontini";
  }
}elseif(isset($pontfim) && $pontfim != ""){
  $pontuacao = " where j31_pontos <= $pontfim";
  $pontuacao1 = " PONTUA��O MENOR OU IGUAL � $pontfim";
}
//die($sql);
$sql = "select * from ($sql) as tudo
              inner join (select j48_matric, j48_idcons, sum(j31_pontos) as j31_pontos from iptuconstr 
              inner join carconstr on j39_matric = j48_matric 
                                  and j39_idcons = j48_idcons 
              inner join caracter on j48_caract = j31_codigo group by j48_matric, j48_idcons) as pontos on tudo.j48_matric = pontos.j48_matric and tudo.j48_idcons = pontos.j48_idcons $pontuacao";

//echo $sql;exit;

$result  = pg_exec($sql) or die($sql);
$numrows = pg_numrows($result);
if($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem registros para o filtro selecionado.');
   exit;
}
//die("fasdfh aldhf lakshf".$numrows);
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
  $pdf->Cell(12,$tam,"MATRIC",1,0,"C",1);
  $pdf->Cell(65,$tam,"NOME",1,0,"L",1);
  $pdf->Cell(10,$tam,"CONST",1,0,"C",1);
  $pdf->Cell(10,$tam,"ANO",1,0,"C",1);
  $pdf->Cell(12,$tam,"�REA",1,0,"C",1);
  $pdf->Cell(15,$tam,"DATA LANC.",1,0,"C",1);
  $pdf->Cell(15,$tam,"HABITE-SE",1,0,"C",1);
  if(isset($j32_descr) && $j32_descr == ""){
    $pdf->Cell(40,$tam,"PONTUA��O",1,0,"C",1);
  }else{
    $pdf->Cell(30,$tam,(@$j32_descr ==""?"SEM GRUPOS":@$j32_descr),1,0,"C",1);
    $pdf->Cell(10,$tam,"PONT",1,0,"C",1);
  }
  $pdf->Cell(40,$tam,"LOGRADOURO",1,0,"C",1);
  $pdf->Cell(15,$tam,"N�MERO",1,0,"C",1);
  $pdf->Cell(15,$tam,"COMPL",1,0,"C",1);
  $pdf->Cell(15,$tam,"SQL",1,1,"C",1);
  $pdf->SetFillColor(255);
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    if ($matricula <> $j39_matric) {
      $matricula = $j39_matric;
      $nome = $z01_nome;
      $matric += 1; 
    }else{
      $matricula = "";
      $nome = "";
    }
    $pdf->Cell(12,$tam,$matricula,1,0,"C",0);
    $pdf->Cell(65,$tam,$nome,1,0,"L",0);
    $pdf->Cell(10,$tam,$j39_idcons,1,0,"C",0);
    $pdf->Cell(10,$tam,$j39_ano,1,0,"C",0);
    $pdf->Cell(12,$tam,db_formatar($j39_area,'f'),1,0,"R",0);
    $pdf->Cell(15,$tam,($j39_dtlan != ""?db_formatar($j39_dtlan,'d'):""),1,0,"C",0);
    $pdf->Cell(15,$tam,($j39_habite != ""?db_formatar($j39_habite,'d'):""),1,0,"C",0);
    $nome = $z01_nome;
    $matricula = $j39_matric;
    $area += $j39_area;
    if($j39_area > $areama){
      $areama = $j39_area;
    }
    if($j39_area < $areame){
      $areame = $j39_area;
    }
    $pdf->SetFont('Arial','',5);
    if(isset($j32_descr) && $j32_descr == ""){
      $pdf->Cell(40,$tam,@$j31_pontos,1,0,"C",0);
    }else{
      $pdf->Cell(30,$tam,@$j31_descr,1,0,"L",0);
      $pdf->Cell(10,$tam,@$j31_pontos,1,0,"C",0);
    }
    $pdf->Cell(40,$tam,@$j14_nome,1,0,"L",0);
    $pdf->Cell(15,$tam,@$j39_numero,1,0,"L",0);
    $pdf->Cell(15,$tam,@$j39_compl,1,0,"L",0);
    $pdf->Cell(15,$tam,@$j34_setor . "/" . @$j34_quadra . "/" . @$j34_lote,1,1,"L",0);
    $pdf->SetFont('Arial','',7);
    if ( $pdf->GetY() > 185 && ($s + 1) != $numrows) {
      $pdf->AddPage('l');
      $pdf->SetFillColor(235);
      $pdf->Cell(12,$tam,"MATRIC",1,0,"C",1);
      $pdf->Cell(65,$tam,"NOME",1,0,"L",1);
      $pdf->Cell(10,$tam,"CONST",1,0,"C",1);
      $pdf->Cell(10,$tam,"ANO",1,0,"C",1);
      $pdf->Cell(12,$tam,"�REA",1,0,"C",1);
      $pdf->Cell(15,$tam,"DATA LANC.",1,0,"C",1);
      $pdf->Cell(15,$tam,"HABITE-SE",1,0,"C",1);
      if(isset($j32_descr) && $j32_descr == ""){
        $pdf->Cell(40,$tam,"PONTUA��O",1,0,"C",1);
      }else{
        $pdf->Cell(30,$tam,(@$j32_descr ==""?"SEM GRUPOS":@$j32_descr),1,0,"C",1);
        $pdf->Cell(10,$tam,"PONT",1,0,"C",1);
      }
      $pdf->Cell(40,$tam,"LOGRADOURO",1,0,"C",1);
      $pdf->Cell(15,$tam,"N�MERO",1,0,"C",1);
      $pdf->Cell(15,$tam,"COMPL",1,0,"C",1);
      $pdf->Cell(15,$tam,"SQL",1,1,"C",1);
      $pdf->SetFillColor(255);
    }
  }
}else{///////////////////////////////RELATORIO RESUMIDO//////////////////////////////////////////////
  for($s=0;$s<$numrows;$s++){
    db_fieldsmemory($result,$s);
    if ($matricula <> $j39_matric) {
      $matric += 1; 
    }
    $area += $j39_area;
    if($j39_area > $areama){
      $areama = $j39_area;
    }
    if($j39_area < $areame){
      $areame = $j39_area;
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
if(isset($j14_comruas) && $j14_comruas != "" && empty($j14_semruas)){
  $vir = "";
  $rua = "";
  $result1 = pg_exec("select j14_nome from ruas where j14_codigo in ($j14_comruas)");
  for($x=0;$x<pg_numrows($result1);$x++){
  	db_fieldsmemory($result1,$x);
	  $cod .= $vir.$j14_nome;
	  $vir=", ";
  }
  $pdf->MultiCell(280,05,"SOMENTE A(S) RUA(S) ->  ".$cod,1,"L");
}
if(isset($j14_semruas) && $j14_semruas != "" && empty($j14_comruas)){
  $vir = "";
  $rua = "";
  $result1 = pg_exec("select j14_nome from ruas where j14_codigo in ($j14_semruas)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j14_nome;
	$vir=", ";
      }
  $pdf->MultiCell(280,05,"EXCETO A(S) RUA(S) ->  ".$cod,1,"L");
}
if(isset($j14_comruas) && $j14_comruas == "" && isset($j14_semruas) && $j14_semruas == "" ){
  $pdf->MultiCell(280,05,"LOGRADOUROS SELECIONADOS: TODOS",1,"L");
}
$vir = "";
$cod = "";
$result1 = pg_exec("select distinct j31_descr,j31_codigo from carconstr inner join caracter on j48_caract=j31_codigo where j31_codigo in ($listadas)");
    for($x=0;$x<pg_numrows($result1);$x++){
      db_fieldsmemory($result1,$x);
      $cod .= $vir.$j31_codigo." - ".$j31_descr;
      $vir=", ";
    }
$pdf->MultiCell(280,05,"CARACTER�STICAS LISTADAS ->  ".$cod,1,"L");
$vir = "";
$cod = "";
if(isset($chaves_caract) && $chaves_caract != ""){
  $result1 = pg_exec("select distinct j31_descr,j31_codigo from carconstr inner join caracter on j48_caract=j31_codigo  where j31_codigo in ($chaves_caract)");
      for($x=0;$x<pg_numrows($result1);$x++){
	db_fieldsmemory($result1,$x);
	$cod .= $vir.$j31_codigo." - ".$j31_descr;
	$vir=",";
      }
}else{
  $cod = "";
}
$pdf->MultiCell(280,05,"CARACTER�STICAS N�O LISTADAS ->  ".@$cod,1,"L");
$pdf->Ln(5);
if(isset($ordem) && $ordem != ""){
  $pdf->MultiCell(280,05,"ORDEM - ORDENADO POR ".$ordem1,0,"L");
}
if(isset($order) && $order != ""){
  $pdf->MultiCell(280,05,"MODO -".$modo1,0,"L");
}
if(isset($habite1) && $habite1 != ""){
  $pdf->MultiCell(280,05,"HABITE-SE - ".$habite1,0,"L");
}
if(isset($demo) && $demo != ""){
  $pdf->MultiCell(280,05,"DEMOLI��ES - ".$demo1,"0");
}
if(isset($origem) && $origem != ""){
  $pdf->MultiCell(280,05,"ORIGEM - ".$origem1,0,"L");
}
if(isset($pontuacao1) && $pontuacao1 != ""){
  $pdf->MultiCell(280,05,"".$pontuacao1,0,"L");
}

if(isset($j39_dtlan) && $j39_dtlan != "--" && $j39_dtlanfim == "--"){
  $pdf->MultiCell(280,05,"".@$data1,0,"L");
}elseif(isset($j39_dtlan) && $j39_dtlan != "--" && isset($j39_dtlanfim) && $j39_dtlanfim != "--"){
  $pdf->MultiCell(280,05,"".$data2,0,"L");
}elseif(isset($j39_dtlan) && $j39_dtlan == "" && isset($j39_dtlanfim) && $j39_dtlanfim != ""){
  $data3 = "DATA DE LAN�AMENTO MENOR OU IGUAL � ".db_formatar($j39_dtlanfim,'d').""; 
  $pdf->MultiCell(280,05,"".$data3,0,"L");
}
if(isset($areaini) && $areaini != "" && $areafim == ""){
$pdf->MultiCell(280,05,"CONSTRU��ES COM �REA MAIOR QUE ".$areaini." M",0,"L");
}elseif(isset($areaini) && $areaini != "" && isset($areafim) && $areafim != ""){
$pdf->MultiCell(280,05,"CONSTRU��ES COM �REA MAIOR OU IGUAL � ".$areaini." M E MENOR OU IGUAL � ".$areafim." M",0,"L");
}
if(isset($anoini) && $anoini != "" && $anofim == ""){
$pdf->MultiCell(280,05,"CONSTRU��ES APARTIR DO ANO MAIOR QUE ".$anoini."",0,"L");
}elseif(isset($anoini) && $anoini != "" && isset($anofim) && $anofim != ""){
$pdf->MultiCell(280,05,"CONSTRU��ES APARTIR DO ANO ".$anoini." � ".$anofim."",0,"L");
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
  $pdf->MultiCell(280,05,"SOMENTE OS SETORES/QUADRAS -> ".$setores,0,"L");
}
$pdf->Ln(5);
$pdf->SetFillColor(235);
$pdf->Cell(50,05,"�REA M�NIMA CONSTRU�DA",1,0,"C",1);
$pdf->Cell(50,05,"�REA M�XIMA CONSTRU�DA",1,0,"C",1);
$pdf->Cell(60,05,"M�DIA DE �REA CONSTRU�DA",1,1,"C",1);
$pdf->Cell(50,05,"".db_formatar($areame,'f'),1,0,"C");
$pdf->Cell(50,05,"".db_formatar($areama,'f'),1,0,"C");
$pdf->Cell(60,05,"".db_formatar((($area>0?$area/$s:$area)),'f'),1,1,"C");
$pdf->Ln(5);
if(isset($grupo) && $grupo != ""){
  $sql1 = "select j31_descr,count(j39_matric) as quant,sum(j39_area) as area1 from ($sql) as gp group by j31_descr";
  $pdf->Cell(160,05,"TOTAIS DAS CONSTRU��ES COM CARACTER�STICA DO GRUPO - $j32_descr ",1,1,"C",1);

  $res = g_exec($sql1);
  $pdf->Cell(80,05,(isset($grupo) && $grupo != ""?"DESCRI��O DA CARACTER�STICA":"TOTAL"),1,0,"C",1);
  $pdf->Cell(40,05,"QUANTIDADE",1,0,"C",1);
  $pdf->Cell(40,05,"�REA ",1,1,"C",1);
  for($i=0;$i<pg_numrows($res);$i++){
    db_fieldsmemory($res,$i);
    $pdf->Cell(80,05,"".$j31_descr,1,0,"C");
    $pdf->Cell(40,05,"".$quant,1,0,"C");
    $pdf->Cell(40,05,"".db_formatar($area1,'f'),1,1,"C");
  }
}
$pdf->Cell(80,05,"MATR�CULA(S) - ".$matric,1,0,"C",1);
$pdf->Cell(40,05,"CONSTRU��ES  - ".$s,1,0,"C",1);
$pdf->Cell(40,05,"�REA TOTAL   -".db_formatar($area,'f'),1,0,"C",1);
include("fpdf151/geraarquivo.php");
?>