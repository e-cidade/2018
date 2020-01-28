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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_empautoriza_classe.php");
include("classes/db_empautitem_classe.php");

$clempautoriza = new cl_empautoriza;
$clempautitem = new cl_empautitem;

$clrotulo = new rotulocampo;
$clempautoriza->rotulo->label();
$clrotulo->label("descrdepto");

db_postmemory($HTTP_POST_VARS);

// WHEREFOR é para se vier setada a opção para IMPRIMIR MATERIAIS / SERVIÇOS
$dbwherefor = "";
$dbwhere = "";
$and     = "";

// É obrigatório que as autorizações não sejam anuladas
$dbwherefor = " e54_anulad is null ";
$dbwhere = " e54_anulad is null ";
$and     = " and ";

// Caso tenha escolhido algum fornecedor ele testa se o usuário quer totalização sem o fornecedor escolhido ou com
// o fornecedor escolhido
if(trim($fornecedores)!=""){
  $notin = "";
  if(trim($mostrafornec)=="sem"){
  	$notin = " not ";
  }
  $dbwherefor .= $and." e54_numcgm ".$notin." in ($fornecedores) ";
  $dbwhere .= $and." e54_numcgm ".$notin." in ($fornecedores) ";
  $and = " and ";
}

// Caso tenha escolhido algum tipo de compra ele testa se o usuário quer totalização sem o tipo de compra escolhido
// ou com o fornecedor escolhido
if(trim($tipodecompra)!=""){
  $notin = "";
  if(trim($mostracompra)=="sem"){
  	$notin = " not ";
  }
  $dbwherefor .= $and." e54_codcom ".$notin." in ($tipodecompra) ";
  $dbwhere .= $and." e54_codcom ".$notin." in ($tipodecompra) ";
  $and = " and ";
}

// Instituições escolhidas na aba FILTRO
if(trim($instit)!=""){
  $dbwherefor .= $and." e54_instit in ($instit) ";
  $dbwhere .= $and." e54_instit in ($instit) ";
  $and = " and ";
}

// Unidades escolhidas na aba FILTRO
// ** Como o campo com as unidades vem separadas por um '_' informando:
//   ÓRGAO_UNIDADE, o filtro separa o órgao da unidade, colocando os órgãos no select. OBSERVAR MAIS ABAIXO
if(trim($unidade)!=""){
  $arr_unidades = split(",",$unidade);
  $unidade      = "";
  $nsorgao      = "";
  $vir          = "";
  for($i=0;$i<count($arr_unidades);$i++){
  	$orgunid  = split("_",$arr_unidades[$i]);
  	$nsorgao .= $vir.$orgunid[0];
  	$unidade .= $vir.$orgunid[1];
  	$vir      = ",";
  }
  $dbwherefor .= $and." o58_unidade in ($unidade)";
  $dbwhere .= $and." o58_unidade in ($unidade)";
  $and = " and ";
}

// $orgao é a variável contendo os órgãos escolhidos pelo usuário na aba FILTRO e $nsorgao, é a variável que
// contém os órgãos das unidades selecionadas pelos usuários (CASO ALGUMA TENHA SIDO SELECIONADA)
if(trim($orgao)!="" || isset($nsorgao)){
  if(isset($nsorgao)){
  	if(trim($orgao)!=""){
  	  $orgao .= ",";
  	}
    $orgao .= $nsorgao;
  }
  $dbwherefor .= $and." o58_orgao in ($orgao) ";
  $dbwhere .= $and." o58_orgao in ($orgao) ";
  $and = " and ";
}

// Funções escolhidas pelo usuário na aba FILTRO
if(trim($funcao)!=""){
  $dbwherefor .= $and." o58_funcao in ($funcao) ";
  $dbwhere .= $and." o58_funcao in ($funcao) ";
  $and = " and ";
}

// Sub-funções escolhidas pelo usuário na aba FILTRO
if(trim($subfuncao)!=""){
  $dbwherefor .= $and." o58_subfuncao in ($subfuncao) ";
  $dbwhere .= $and." o58_subfuncao in ($subfuncao) ";
  $and = " and ";
}

// Programas escolhidos pelo usuário na aba FILTRO
if(trim($programa)!=""){
  $dbwherefor .= $and." o58_programa in ($programa) ";
  $dbwhere .= $and." o58_programa in ($programa) ";
  $and = " and ";
}

// Proj/Ativ escolhidos pelo usuário na aba FILTRO
if(trim($projativ)!=""){
  $dbwherefor .= $and." o58_projativ in ($projativ) ";
  $dbwhere .= $and." o58_projativ in ($projativ) ";
  $and = " and ";
}

// Elementos escolhidos pelo usuário na aba FILTRO
if(trim($ele)!=""){
  $dbwherefor .= $and." o58_codele in ($ele) ";
  $dbwhere .= $and." o58_codele in ($ele) ";
  $and = " and ";
}

// Recursos escolhidos pelo usuário na aba FILTRO
if(trim($recurso)!=""){
  $dbwherefor .= $and." o58_codigo in ($recurso) ";
  $dbwhere .= $and." o58_codigo in ($recurso) ";
  $and = " and ";
}

// Departamentos escolhidos pelo usuário na aba FILTRO
if(trim($depart)!=""){
  $dbwherefor .= $and." e54_depto in ($depart) ";
  $dbwhere .= $and." e54_depto in ($depart) ";
  $and = " and ";
}

// Usuários escolhidos pelo usuário na aba FILTRO
if(trim($usuario)!=""){
  $dbwherefor .= $and." e54_login in ($usuario) ";
  $dbwhere .= $and." e54_login in ($usuario) ";
  $and = " and ";
}

$dbwheredata1 = "";
$dbwheredata2 = "";

$periodp = "";

// Período de emissão das autorizações de empenho
if(trim($data1_dia)!="" && trim($data1_mes)!="" && trim($data1_ano)!=""){
  $periodp = "Período posterior a ". $data1_dia.'/'.$data1_mes.'/'.$data1_ano;
  $data1 = $data1_ano.'-'.$data1_mes.'-'.$data1_dia;
  $dbwheredata1 = " e54_emiss >= '".$data1."'";
  $dbwheredata2 = $dbwheredata1;
}

if(trim($data11_dia)!="" && trim($data11_mes)!="" && trim($data11_ano)!=""){
  $data11 = $data11_ano.'-'.$data11_mes.'-'.$data11_dia;
  if($dbwheredata1!=""){
    $periodp = "Período entre ".$data1_dia.'/'.$data1_mes.'/'.$data1_ano." e ".$data11_dia.'/'.$data11_mes.'/'.$data11_ano;
    $dbwheredata1 = " e54_emiss between '".$data1."' and '".$data11."'";
  }else{
    $periodp = "Período anterior a ". $data11_dia.'/'.$data11_mes.'/'.$data11_ano;
    $dbwheredata1 = " e54_emiss <= '".$data11."'";
  }
}

if($dbwheredata1!=""){
  $dbwherefor .= $and.$dbwheredata1;
  $dbwhere .= $and.$dbwheredata1;
  $and = " and ";
}

// Se o filtro for 1 = TODAS, ele não entrará
if($filtro!=1){

  if($filtro!=2){
  	$dbwherefor .= $and." e61_numemp is not null ";
  }
  // Caso seja 2  = Somente autorizações não empenhadas
  if($filtro==2){
  	$dbwherefor .= $and." e61_numemp is null ";
  	$dbwhere .= $and." e61_numemp is null ";
    $and = " and ";
  // Caso seja 3  = Somente autorizações empenhadas (que não poderá ter valor liquidado)
  }else if($filtro== 3){
    $dbwhere .= $and." (round(e60_vlremp,2) - round(e60_vlranu,2) > 0) and round(e60_vlrliq,2) = 0 ";
    $and = " and ";
  // Caso seja 4  = Somente empenhos com saldo geral a pagar
  }else if($filtro== 4){
    $dbwhere .= $and." (round(e60_vlremp,2) - round(e60_vlranu,2) - round(e60_vlrliq,2) > 0) ";
    $and = " and ";
  // Caso seja 5  = Somente empenhos com saldo a pagar, mas liquidados
  }else if($filtro== 5){
    $dbwhere .= $and." (round(e60_vlrliq,2) - round(e60_vlrpag,2) > 0) and round(e60_vlrliq,2) > 0";
    $and = " and ";
  // Caso seja 6  = Somente empenhos com saldo a pagar, mas não liquidados
  }else if($filtro== 6){
  	$dbwhere .= $and." (round(e60_vlrliq,2) - round(e60_vlrpag,2) > 0) and round(e60_vlrliq,2) = 0 ";
    $and = " and ";
  // Caso seja 7  = Somente empenhos com anulação lançada
  }else if($filtro== 7){
  	$dbwhere .= $and." round(e60_vlranu,2) > 0 ";
    $and = " and ";
  // Caso seja 8  = Somente empenhos parcialmente anulados
  }else if($filtro== 8){
  	$dbwhere .= $and." round(e60_vlranu,2) > 0 and round(e60_vlremp,2) <> round(e60_vlranu,2) ";
    $and = " and ";
  // Caso seja 9  = Somente empenhos totalmente anulados
  }else if($filtro== 9){
  	$dbwhere .= $and." round(e60_vlremp,2) = round(e60_vlranu,2) ";
    $and = " and ";
  // Caso seja 10 = Somente empenhos sem anulação
  }else if($filtro==10){
  	$dbwhere .= $and." round(e60_vlranu,2) = 0 ";
    $and = " and ";
  // Caso seja 11 = Somente empenhos parcialmente liquidados
  }else if($filtro==11){
  	$dbwhere .= $and." round(e60_vlrliq,2) > 0 and ((round(e60_vlremp,2) - round(e60_vlranu,2)) <> round(e60_vlrliq,2)) ";
    $and = " and ";
  // Caso seja 12 = Somente empenhos totalmente liquidados
  }else if($filtro==12){
  	$dbwhere .= $and." ((round(e60_vlremp,2) - round(e60_vlranu,2)) = round(e60_vlrliq,2)) ";
    $and = " and ";
  // Caso seja 13 = Somente empenhos sem liquidação
  }else if($filtro==13){
  	$dbwhere .= $and." round(e60_vlrliq,2) = 0 ";
    $and = " and ";
  // Caso seja 14 = Somente empenhos parcialmente pagos
  }else if($filtro==14){
  	$dbwhere .= $and." round(e60_vlrpag,2) > 0 and (round(e60_vlrliq,2) <> round(e60_vlrpag,2)) ";
    $and = " and ";
  // Caso seja 14 = Somente empenhos totalmente pagos
  }else if($filtro==15){
  	$dbwhere .= $and." round(e60_vlrpag,2) > 0 and (round(e60_vlrliq,2) = round(e60_vlrpag,2)) ";
    $and = " and ";
  }
}

$lista = $periodp;

// Caso seja escolhida a opção para mostrar somente autorizações com reserva de saldo
if(isset($soreserva)){
  // Se filtro for 2 = Somente autorizações não empenhadas, será obrigatório ter reserva de saldo, caso contrário
  // se não for uma autorização empenhada, será mantida a obrigatoriedade mas se for empenhada, a reserva poderá
  // ser nula

  $lista .= "\nSomente autorizações com reserva de saldo";

  if($filtro==2){
    $dbwherefor .= $and." round(o80_valor,2) > 0";
    $dbwhere .= $and." round(o80_valor,2) > 0";
  }else{
    $dbwherefor .= $and." ((e61_numemp is null and round(o80_valor,2) > 0) or (e61_numemp is not null)) ";
    $dbwhere .= $and." ((e61_numemp is null and round(o80_valor,2) > 0) or (e61_numemp is not null)) ";

  }
}

// Monta o SQL
$sql_empautoriza = $clempautoriza->sql_query_elementomaterial(
      null,
      "
       e54_autori,
       e55_sequen,
       e60_numemp,
       element.o56_codele   as codelemento,
       element.o56_elemento as elemento,
       element.o56_descr    as descrele,
       desdobr.o56_codele   as coddesdobra,
       desdobr.o56_elemento as desdobra,
       desdobr.o56_descr    as descrdesd,
       sum(round(e55_vltot,2))   as vlraut,
       sum(round(e64_vlremp,2))  as vlremp,
       sum(round(e64_vlranu,2))  as vlranu,
       sum(round(e64_vlrpag,2))  as vlrpag
      ",
      "
       element.o56_codele,
       desdobr.o56_codele
      ",
      $dbwhere.
      "
       group by
       e54_autori,
       e55_sequen,
       e60_numemp,
       element.o56_codele,
       element.o56_elemento,
       element.o56_descr,
       desdobr.o56_codele,
       desdobr.o56_elemento,
       desdobr.o56_descr

      "
    );

// Executa o select
$result_empautoriza = $clempautoriza->sql_record($sql_empautoriza);
$numrows_autorizacoes = $clempautoriza->numrows;

// Testa se veio algum registro
if($numrows_autorizacoes==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registor encontrado.");
}

// Instancia classe PDF
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);

/// Setar altura onde passará para a próxima página
$pdf->SetAutoPageBreak('on',0);
$pdf->line(2,148.5,208,148.5);

$troca = 1;

$alt = 4;

if(isset($mdesdobra)){

  $lista .= "\nListar desdobramentos";
  if(isset($mdesdobra)){
    $lista .= "\nListar materiais / serviços";
  }

}

// $lista .= "\n$periodop";

$head3 = "DETALHAMENTO DE DESPESA POR MATERIAL";
$head5 = $lista;

if(1==2){
  $head7 = "DETALHAMENTO DE DESPESA POR MATERIAL";
}

// Arrays auxiliares
$arr_index  = Array();
$arr_teste  = Array();
$index = 0;            // Variável que testa a quantidade de elementos

//$arr_autori = Array();
$arr_empenh = Array();
$arr_desdob = Array();

$arr_index2 = Array();
$arr_teste2 = Array();
$index2 = 0;

$arr_index3 = Array();
$arr_teste3 = Array();
$index3 = 0;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ATRIBUIÇÃO EM ARRAYS DOS VALORES (autorizados, empenhados, anulados e pagos) RETORNADOS PELO SELECT POR ELEMENTO
// E DESDOBRAMENTO
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

for($x = 0; $x<$numrows_autorizacoes; $x++){
  db_fieldsmemory($result_empautoriza,$x);

  // Testa se o ELEMENTO CORRENTE já passou, se não passou, adiciona 1 na variável $index, se já passou, não entra
  if(!isset($arr_teste[$codelemento])){
  	$arr_index["$index"]       = $codelemento;
  	$arr_teste["$codelemento"] = $codelemento;
  	$index ++;
  }

  // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento) no array $totalelementoaut (Array com a soma
  // dos valores autorizados para esse elemento), ele o setará com 0 (ZERO)
  if(!isset($totalelementoaut["$codelemento"])){
    $totalelementoaut["$codelemento"] = 0;
  }

  // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento) no array $totalelementoemp (Array com a soma
  // dos valores empenhados para esse elemento), ele o setará com 0 (ZERO)
  if(!isset($totalelementoemp["$codelemento"])){
    $totalelementoemp["$codelemento"] = 0;
  }

  // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento) no array $totalelementopag (Array com a soma
  // dos valores pagos para esse elemento), ele o setará com 0 (ZERO)
  if(!isset($totalelementopag["$codelemento"])){
    $totalelementopag["$codelemento"] = 0;
  }

  // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento) no array $totalelementoanu (Array com a soma
  // dos valores anulados para esse elemento), ele o setará com 0 (ZERO)
  if(!isset($totalelementoanu["$codelemento"])){
    $totalelementoanu["$codelemento"] = 0;
  }

  // Soma nos ARRAYS DE VALORES DOS ELEMENTOS o valor retornado pelo select

  $totalelementoaut["$codelemento"] += $vlraut;
  if(!isset($arr_empenh[$e60_numemp])){
    $totalelementoemp["$codelemento"] += $vlremp;
    $totalelementopag["$codelemento"] += $vlrpag;
    $totalelementoanu["$codelemento"] += $vlranu;
    $arr_empenh[$e60_numemp] = $e60_numemp;
  }

  // Se for setada a opção LISTAR DESDOBRAMENTOS
  if(isset($mdesdobra)){

    // Testa se o DESDOBRAMENTO CORRENTE já passou, se não passou, adiciona 1 na variável $index2, se já passou,
    // não entra
    if(!isset($arr_teste2["$codelemento"."_"."$coddesdobra"])){
  	  $arr_index2["$index2"]                         = $codelemento."_".$coddesdobra;
  	  $arr_teste2["$codelemento"."_"."$coddesdobra"] = $codelemento."_".$coddesdobra;
  	  $index2 ++;
    }

    // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento concatenado com o $coddesdobra - Código do
    // desdobramento) no array $totaldesdobraaut (Array com a soma dos valores autorizados para esse desdobramento),
    // ele o setará com 0 (ZERO)
    if(!isset($totaldesdobraaut["$codelemento"."_"."$coddesdobra"])){
      $totaldesdobraaut["$codelemento"."_"."$coddesdobra"] = 0;
    }

    // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento concatenado com o $coddesdobra - Código do
    // desdobramento) no array $totaldesdobraemp (Array com a soma dos valores empenhados para esse desdobramento),
    // ele o setará com 0 (ZERO)
    if(!isset($totaldesdobraemp["$codelemento"."_"."$coddesdobra"])){
  	  $totaldesdobraemp["$codelemento"."_"."$coddesdobra"] = 0;
    }

    // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento concatenado com o $coddesdobra - Código do
    // desdobramento) no array $totaldesdobrapag (Array com a soma dos valores pagos para esse desdobramento), ele
    // o setará com 0 (ZERO)
    if(!isset($totaldesdobrapag["$codelemento"."_"."$coddesdobra"])){
  	  $totaldesdobrapag["$codelemento"."_"."$coddesdobra"] = 0;
    }

    // Se ainda não existe o ÍNDICE ($codelemento - Código do elemento concatenado com o $coddesdobra - Código do
    // desdobramento) no array $totaldesdobraanu (Array com a soma dos valores anulados para esse desdobramento),
    // ele o setará com 0 (ZERO)
    if(!isset($totaldesdobraanu["$codelemento"."_"."$coddesdobra"])){
  	  $totaldesdobraanu["$codelemento"."_"."$coddesdobra"] = 0;
    }

    // Soma nos ARRAYS DE VALORES DOS DESDOBRAMENTOS o valor retornado pelo select
    $totaldesdobraaut["$codelemento"."_"."$coddesdobra"] += $vlraut;
    if(!isset($arr_desdob[$e60_numemp])){
      $totaldesdobraemp["$codelemento"."_"."$coddesdobra"] += $vlremp;
      $totaldesdobrapag["$codelemento"."_"."$coddesdobra"] += $vlrpag;
      $totaldesdobraanu["$codelemento"."_"."$coddesdobra"]  += $vlranu;
      $arr_desdob[$e60_numemp] = $e60_numemp;
    }

  }

  // ARRAYS COM OS ESTRUTURAIS E DESCRIÇÕES DO ELEMENTO E DESDOBRAMENTO CORRENTE
  $descrelemento["$codelemento"] = $descrele;
  $estruelemento["$codelemento"] = $elemento;
  $descrdesdobra["$coddesdobra"] = $descrdesd;
  $estrudesdobra["$coddesdobra"] = $desdobra;

}

// Variáveis que testam se elementos, desdobramentos ou materiais correntes devem ser impressos
$ielemento = true;
$idesdobra = true;
$imaterial = true;

// Variáveis que recebem o valor do elemento, desdobramento ou material que passou anteriormente no LOOP
$mmelemento = "";
$mmdesdobra = "";
$mmmaterial = "";

// Função que imprime os cabeçalhos
function imprimelabels($newpagina,$labmaterial){

  global $alt;
  global $pdf;
  global $mmaterial;
  global $mempenhos;

  if($newpagina==true){

    $pdf->addpage("L");

  }

  $descricao = "Elementos";

  $pdf->setfont('arial','b',8);

  // Cabeçalho dos elementos e desdobramentos
  $pdf->cell(20,$alt,"$descricao",1,0,"C",1);
  $pdf->cell(30,$alt,"Estrutural",1,0,"C",1);
  $pdf->cell(80,$alt,"Descrição" ,1,0,"C",1);
  $pdf->cell(30,$alt,"Autorizado",1,0,"C",1);
  $pdf->cell(30,$alt,"Empenhado" ,1,0,"C",1);
  $pdf->cell(30,$alt,"Anulado"   ,1,0,"C",1);
  $pdf->cell(30,$alt,"Pago"      ,1,0,"C",1);
  $pdf->cell(30,$alt,"Saldo"     ,1,1,"C",1);

  if($labmaterial==true || isset($mmaterial)){
    // Cabeçalho dos materiais
    $pdf->cell(20 ,$alt,""             ,1,0,"C",0);
    $pdf->cell(30 ,$alt,"Cód. Material",1,0,"C",0);
    $pdf->cell(80 ,$alt,"Descrição"    ,1,0,"C",0);
    $pdf->cell(30 ,$alt,"Autorizado"   ,1,0,"C",0);
    $pdf->cell(120,$alt,""             ,1,1,"C",0);

  }

  if(isset($mempenhos)){
	  // Cabeçalho dos empenhos
	  $pdf->cell(20,$alt,"Empenho"   ,1,0,"C",0);
	  $pdf->cell(30,$alt,"Numemp"    ,1,0,"C",0);
	  $pdf->cell(80,$alt,"Fornecedor",1,0,"C",0);
	  //$pdf->cell(30,$alt,"Liquidado" ,1,0,"C",0);
	  $pdf->cell(30,$alt,"Empenhado" ,1,0,"C",0);
	  //$pdf->cell(30,$alt,"Anulado"   ,1,0,"C",0);
	  //$pdf->cell(30,$alt,"Pago"      ,1,0,"C",0);
	  //$pdf->cell(30,$alt,"Saldo"     ,1,1,"C",0);
	  $pdf->cell(120,$alt,"" ,1,1,"C",0);
  }

  return true;
}

// Função que imprime os elementos
function imprimeelemento($codielemento,$estrelemento,$descelemento,$autoelemento,$empeelemento,$anulelemento,$pagoelemento,$saldelemento){

  global $alt;
  global $pdf;
  global $mdesdobra;

  $bordasele = 0;
  $coreselem = 0;
  $negritook = "";

  if(isset($mdesdobra)){
    $pdf->ln(1);
    $bordasele = "T";
    $coreselem = 1;
    $negritook = "b";
  }

  $pdf->setfont('arial',$negritook,7);

  $pdf->cell(20,$alt,$codielemento                 ,$bordasele,0,"C",$coreselem);
  $pdf->cell(30,$alt,$estrelemento                 ,$bordasele,0,"C",$coreselem);
  $pdf->cell(80,$alt,$descelemento                 ,$bordasele,0,"L",$coreselem);
  $pdf->cell(30,$alt,db_formatar($autoelemento,"f"),$bordasele,0,"R",$coreselem);
  $pdf->cell(30,$alt,db_formatar($empeelemento,"f"),$bordasele,0,"R",$coreselem);
  $pdf->cell(30,$alt,db_formatar($anulelemento,"f"),$bordasele,0,"R",$coreselem);
  $pdf->cell(30,$alt,db_formatar($pagoelemento,"f"),$bordasele,0,"R",$coreselem);
  $pdf->cell(30,$alt,db_formatar($saldelemento,"f"),$bordasele,1,"R",$coreselem);

  return true;

}

// Função que imprime os desdobramentos
function imprimedesdobra($codidesdobra,$estrdesdobra,$descdesdobra,$autodesdobra,$empedesdobra,$anuldesdobra,$pagodesdobra,$salddesdobra,$impDesdobramento,$parCodiElemento,$parDescElemento){

  global $alt;
  global $pdf;
  global $mmaterial;

  $bordasdesd = "";
  $parenteses = "";
  $negritodes = "";
  $cordesdobr = "1";
  $tamanho    = 80;

  if(isset($mmaterial)){

    $negritodes = "b";

  }

  $pdf->setfont('arial',$negritodes,7);

  $pdf->cell(20,$alt,$codidesdobra            ,$bordasdesd,0,"C",$cordesdobr);
  $pdf->cell(30,$alt,$estrdesdobra            ,$bordasdesd,0,"C",$cordesdobr);
  $pdf->cell(80,$alt,$descdesdobra            ,$bordasdesd,0,"L",$cordesdobr);

  $pdf->cell(30,$alt,db_formatar($autodesdobra,"f"),$bordasdesd,0,"R",$cordesdobr);
  $pdf->cell(30,$alt,db_formatar($empedesdobra,"f"),$bordasdesd,0,"R",$cordesdobr);
  $pdf->cell(30,$alt,db_formatar($anuldesdobra,"f"),$bordasdesd,0,"R",$cordesdobr);
  $pdf->cell(30,$alt,db_formatar($pagodesdobra,"f"),$bordasdesd,0,"R",$cordesdobr);
  $pdf->cell(30,$alt,db_formatar($salddesdobra,"f"),$bordasdesd,1,"R",$cordesdobr);

  return true;

}

// Função que imprime os materiais
function imprimematerial($codimaterial,$descmaterial,$totamaterial){

  global $alt;
  global $pdf;
  global $mempenhos;

  $negritodes = "";
  if(isset($mempenhos)){

    $negritodes = "b";

  }

  $pdf->setfont('arial',$negritodes,6);

  $pdf->cell( 20,$alt,""                            ,0,0,"C",0);
  $pdf->cell( 30,$alt,$codimaterial                 ,0,0,"C",0);
  $pdf->cell( 80,$alt,$descmaterial                 ,0,0,"L",0);
  $pdf->cell( 30,$alt,db_formatar($totamaterial,"f"),0,0,"R",0);
  $pdf->cell(120,$alt,""                            ,0,1,"R",0);

}

function imprimeempenhos($codiempenho,$numeempenho,$vlorempenho,$credempenho){
  global $alt;
  global $pdf;

  $pdf->setfont('arial',"",6);

  $pdf->cell(20,$alt,$codiempenho,0,0,"C",0);
  $pdf->cell(30,$alt,$numeempenho,0,0,"C",0);
  $pdf->cell(80,$alt,$credempenho,0,0,"L",0);
  //$pdf->cell(30,$alt,db_formatar($liquempenho,"f"),0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($vlorempenho,"f"),0,0,"R",0);
  //$pdf->cell(30,$alt,db_formatar($anulempenho,"f"),0,0,"R",0);
  //$pdf->cell(30,$alt,db_formatar($pagoempenho,"f"),0,0,"R",0);
  //$pdf->cell(30,$alt,db_formatar($saldempenho,"f"),0,1,"R",0);
  $pdf->cell(120,$alt,"",0,1,"R",0);

}

// Inicia a impressão dos dados
for($i=0;$i<$index;$i++){

  // Valores dos ARRAYS
  $celemento = $arr_index["$i"];                      // Código do elemento
  $telemento = $estruelemento["$celemento"];          // Estrutural do elemento
  $delemento = $descrelemento["$celemento"];          // Descrição do elemento
  $aelemento = $totalelementoaut["$celemento"];       // Valor das autorizações que envolvem o elemento
  $eelemento = $totalelementoemp["$celemento"];       // Valor dos empenhos que envolvem o elemento
  $nelemento = $totalelementoanu["$celemento"];       // Valor das anulações que envolvem os elementos
  $pelemento = $totalelementopag["$celemento"];       // Valor dos pagamentos que envolvem os elementos
  $selemento = abs($eelemento-$nelemento-$pelemento); // Valor do saldo dos elementos

  if($pdf->gety() > $pdf->h - 20 || $troca != 0 ){

    $troca = 0;
    $ielemento = true;
    imprimelabels(true,false);
    // Imprime os labels e passa true para mudar de página

  }

  if($ielemento==true || $mmelemento!=$celemento){

  	$ielemento = false;
  	$idesdobra = true;
    $mmelemento = $celemento;

    imprimeelemento($celemento,$telemento,$delemento,$aelemento,$eelemento,$nelemento,$pelemento,$selemento);
    // Imprime os elementos

  }

  if(isset($mdesdobra)){

  	$mmcdesdobra = "";

  	for($i2=0;$i2<$index2;$i2++){

      if($pdf->gety() > $pdf->h - 20){

        imprimelabels(true,false);
        // Imprime os labels caso mude de página no meio do for que imprime os desdobramentos do elemento corrente

        imprimeelemento($celemento,$telemento,$delemento,$aelemento,$eelemento,$nelemento,$pelemento,$selemento);
        // Imprime o desdobramento corrente após mudança de página

       	$idesdobra = true;

      }

      // Busca o ELEMENTO E DESDOBRAMENTO corrente
      $testaelemento = split("_",$arr_index2["$i2"]);

      // Posição 0 - Elemento e posição 1 - Desdobramento
      $mostraelemento = $testaelemento["0"];
      $mostradesdobra = $testaelemento["1"];

      // Testa se elemento corrente é o mesmo encontrado no ARRAY
      if($mostraelemento==$celemento){

        $cdesdobra = $arr_index2["$i2"];                    // Código do desdobramento
        $tdesdobra = $estrudesdobra["$mostradesdobra"];     // Estrutural do desdobramento
        $ddesdobra = $descrdesdobra["$mostradesdobra"];     // Descrição do desdobramento
        $adesdobra = $totaldesdobraaut["$cdesdobra"];       // Valor das autorizações que envolvem o desdobramento
        $edesdobra = $totaldesdobraemp["$cdesdobra"];       // Valor dos empenhos que envolvem o desdobramento
        $pdesdobra = $totaldesdobrapag["$cdesdobra"];       // Valor dos pagamentos que envolvem os desdobramento
        $ndesdobra = $totaldesdobraanu["$cdesdobra"];       // Valor das anulações que envolvem os desdobramento
        $sdesdobra = abs($edesdobra-$ndesdobra-$pdesdobra); // Valor do saldo dos desdobramentos

        // Testa se deve ou não imprimir o desdobramento
        if($mmdesdobra!=$mostradesdobra || $idesdobra==true){

          $wherematerial = $and." e55_codele = ".$mostradesdobra." and o58_codele = ".$celemento;

          imprimedesdobra($mostradesdobra,$tdesdobra,$ddesdobra,$adesdobra,$edesdobra,$ndesdobra,$pdesdobra,$sdesdobra,$idesdobra,$celemento,$delemento);
          // Imprime desdobramento

          $idesdobra  = false;
          $mmdesdobra = $mostradesdobra;

          // Testa se esta setada a opção LISTAR MATERIAIS / SERVIÇOS
          if(isset($mmaterial)){

          	$and = "";

          	if(trim($dbwherefor)!=""){
      	      $and = " and ";
            }

            // Monta um select para buscar somente materiais autorizados com o elemento e desdobramento corrente
          	$sql_materiais = "
            select pc01_codmater,
                   pc01_descrmater,
                   sum(e55_vltot) as e55_vltot
            from empautoriza
                 inner join empautitem    on e54_autori=e55_autori
                 inner join pcmater       on e55_item=pc01_codmater
                 inner join empautidot    on e56_autori=e54_autori and e56_anousu=e54_anousu
                 inner join orcdotacao    on o58_anousu=e56_anousu and e56_coddot=o58_coddot
                 left  join orcreservaaut on o83_autori=e54_autori
                 left  join orcreserva    on o83_autori=e54_autori
                 left  join empempaut     on e61_autori=e54_autori
                 left  join empempenho    on e60_numemp=e61_numemp
            where ".$dbwhere.$wherematerial."
            group by pc01_codmater,pc01_descrmater
            ";

            // Executa o select
            $result_materiais = $clempautoriza->sql_record($sql_materiais);

            // For para imprimir os materiais / serviços
            $numrows_materiais = $clempautoriza->numrows;
            for($i3=0;$i3<$numrows_materiais;$i3++){

              db_fieldsmemory($result_materiais,$i3);

              if($pdf->gety() > $pdf->h - 20){

                imprimelabels(true,true);
                // Imprime os labels se mudar de página

                imprimeelemento($celemento,$telemento,$delemento,$aelemento,$eelemento,$nelemento,$pelemento,$selemento);
                // Imprime o elemento se mudar de página

                imprimedesdobra($mostradesdobra,$tdesdobra,$ddesdobra,$adesdobra,$edesdobra,$ndesdobra,$pdesdobra,$sdesdobra,false,$celemento,$delemento);
                // Imprime o desdobramento se mudar de página

              }

              imprimematerial($pc01_codmater,$pc01_descrmater,$e55_vltot);
              // Imprime o material


		          // Testa se deve imprimir os empenhos
		          if(isset($mempenhos)){
		          	$where_empenhos = "";
		          	if(trim($dbwhere.$wherematerial) != ""){
		          		$where_empenhos = " and ";
		          	}
		          	$where_empenhos.= " e62_item = ".$pc01_codmater;
		            // Monta um select para buscar somente empenhos com o elemento, desdobramento e material corrente
		          	$sql_mostempenhos = "
		            select e60_numemp,
		                   e60_codemp,
		                   e60_anousu,
		                   z01_numcgm,
		                   z01_nome,
		                   sum(e62_vltot) as e62_vltot
		            from empautoriza
		                 inner join empautitem    on e54_autori=e55_autori
		                 inner join empautidot    on e56_autori=e54_autori and e56_anousu=e54_anousu
		                 inner join orcdotacao    on o58_anousu=e56_anousu and e56_coddot=o58_coddot
		                 left  join orcreservaaut on o83_autori=e54_autori
		                 left  join orcreserva    on o80_codres=o83_codres
		                 left  join empempaut     on e61_autori=e54_autori
		                 left  join empempenho    on e60_numemp=e61_numemp
		                 left  join empempitem    on e62_numemp=e60_numemp
		                 left  join cgm           on z01_numcgm=e60_numcgm
		            where ".$dbwhere.$wherematerial.$where_empenhos."
		            group by e60_numemp, e60_codemp, e60_anousu, z01_numcgm, z01_nome
		            order by e60_numemp
		            ";

		            // Executa o select
		            $result_mostempenhos = $clempautoriza->sql_record($sql_mostempenhos);

		            // For para imprimir os empenhos
		            $numrows_mostempenhos = $clempautoriza->numrows;
		            for($i4=0;$i4<$numrows_mostempenhos;$i4++){
		            	db_fieldsmemory($result_mostempenhos, $i4);

		              if($pdf->gety() > $pdf->h - 20){

		                imprimelabels(true,true);
		                // Imprime os labels se mudar de página

		                imprimeelemento($celemento,$telemento,$delemento,$aelemento,$eelemento,$nelemento,$pelemento,$selemento);
		                // Imprime o elemento se mudar de página

		                imprimedesdobra($mostradesdobra,$tdesdobra,$ddesdobra,$adesdobra,$edesdobra,$ndesdobra,$pdesdobra,$sdesdobra,false,$celemento,$delemento);
		                // Imprime o desdobramento se mudar de página

			              imprimematerial($pc01_codmater,$pc01_descrmater,$e55_vltot);
			              // Imprime o material
		              }

		              imprimeempenhos(($e60_codemp." / ".$e60_anousu),$e60_numemp,$e62_vltot,($z01_numcgm." - ".$z01_nome));
		            }

		          }

            }

          }

        }

      }

    }

  }

}
$pdf->Output();
?>