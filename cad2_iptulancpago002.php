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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$where = "";
if($zona!=0 || $zona!="") {
  $where .= "and j34_zona in ($zona)";
}

if ($considerar == "p") {
  $where .= " and j23_tipoim = 'P '";
  $head4="Tipo: PREDIAL";
} elseif ($considerar == "t") {
  $where .= " and j23_tipoim = 'T' ";
  $head4="Tipo: TERRITORIAL";
} else {
  $head4="Tipos: PREDIAL E TERRITORIAL";
}

// Busca Receitas do IPTU
$sSqlReceitas  = "select j18_rterri as j18_receit,0 as oneracao from cfiptu where j18_anousu = $anousu ";
$sSqlReceitas .= "union ";
$sSqlReceitas .= "select j18_rpredi as j18_receit,0 as oneracao from cfiptu where j18_anousu = $anousu ";
$sSqlReceitas .= "union ";
$sSqlReceitas .= "select distinct j23_recorg as j18_receit, j23_recdst as oneracao from iptucalcconfrec where j23_anousu = $anousu and j23_tipo = 1";

$rsReceitas = pg_query($sSqlReceitas);
$iLinhasRec = pg_numrows($rsReceitas);
$aRecIptu   = array(); 
$aRecOnera  = array(); 
$sRecIptu   = "";
$vir        = "";
for($i=0; $i<$iLinhasRec; $i++) {
  db_fieldsmemory($rsReceitas, $i);
  $aRecIptu[$i]  = $j18_receit;
  if($oneracao != 0){
   $aRecOnera[$i] = $oneracao;  
  }
  $sRecIptu .= $vir.$j18_receit.",".$oneracao;
  $vir = ",";
}

$sql="
select j23_anousu,
j21_receit,
k02_descr as descricao,
sum(case when coalesce(j21_valor,0)=0 then 0 else 1 end) as qtd,
round(sum(j23_vlrter), 2) as vlr_venal_terreno,
round(sum(j22_valor),  2) as vlr_venal_predio,
round(sum(j23_vlrter), 2) +
round(sum(j22_valor),  2) as vlr_venal_total,  
round(sum(j21_valor),  2) as tributo,
round(sum(k00_valor),  2) as pagamentos
from (
select j23_anousu, 
j23_matric,
j21_receit,
k02_descr,
j20_numpre,

case 
when j21_receit in ($sRecIptu) then
coalesce(j23_vlrter, 0) 
else
0
end as j23_vlrter,

case 
when j21_receit in ($sRecIptu) then
coalesce(
(select sum(coalesce(j22_valor, 0)) 
from iptucale
where j22_anousu = j23_anousu
and j22_matric = j23_matric), 0) 
else
0
end as j22_valor,

sum(coalesce(j21_valor, 0)) as j21_valor,

coalesce(
(select sum(k00_valor)
from arrepaga  
where k00_numpre = j20_numpre
and k00_receit = j21_receit), 0) as k00_valor

from iptubase
inner join iptucalc  on j23_matric = j01_matric
and j23_anousu = $anousu
inner join iptunump  on j20_anousu = j23_anousu
and j20_matric = j23_matric
left  join iptucalv  on j21_anousu = j23_anousu
and j21_matric = j23_matric
inner join tabrec    on k02_codigo = j21_receit
inner join lote      on j34_idbql  = j01_idbql

where j23_anousu = $anousu 
and j21_valor <> 0 
$where 
group by j23_anousu,
j21_receit,
k02_descr,
j20_numpre,
j23_matric,
j23_vlrter
) as x
group by j23_anousu, j21_receit, k02_descr
order by j21_receit;

";

$result= pg_query($sql) or die($sql);
$linhas= pg_num_rows($result);

$sqlemdia="
select j20_anousu,
j21_receit as receita,
k02_descr as descricao1,
sum(case when coalesce(j21_valor,0)=0 then 0 else 1 end)+0 as qtd_calculada,
sum(emdia)+0 as qtd_emdia,
sum(divida)+0 as qtd_divida,
sum(dividaemdia)+0 as qtd_dividaemdia,
sum(cancelado)+0 as qtd_cancelado,
sum(semorigem)+0 as qtd_semorigem
from	(
select
j20_anousu,
j21_receit,
k02_descr,
sum(coalesce(j21_valor, 0)) as j21_valor,
" . ($anousu == db_getsession("DB_anousu")?
"case 
when (  select k00_numpre
from arrecad
where k00_dtvenc < current_date
and k00_numpre = j20_numpre 
and k00_receit = j21_receit
order by k00_dtvenc
limit 1 ) is null and 
sum(coalesce(j21_valor, 0)) > 0
then 1
else 0
end"
:
"case 
when (  select k00_numpre
from arrepaga
where k00_numpre = j20_numpre 
and k00_receit = j21_receit
order by k00_dtvenc
limit 1 ) is not null and 

( select k10_numpre
from divold
where k10_numpre = j20_numpre 
and k10_receita = j21_receit 
limit 1) is null and 

sum(coalesce(j21_valor, 0)) > 0
then 1
else 0
end"
) . " as emdia,

case 
when ( select k10_numpre
from divold
where k10_numpre = j20_numpre 
and k10_receita = j21_receit 
limit 1) is null and sum(coalesce(j21_valor, 0)) > 0
then 0
else 1
end as divida,

case 
when ( select 1 from 
					(
					select sum ( case when arrecad.k00_numpre  is not null then 1 else 0 end ) as sum_arrecad,
								 sum ( case when arrepaga.k00_numpre is not null then 1 else 0 end ) as sum_arrepaga
					from divold
					inner join divida on k10_coddiv = v01_coddiv 
					left  join arrepaga on v01_numpre = arrepaga.k00_numpre and v01_numpar = arrepaga.k00_numpar
					left  join arrecad  on v01_numpre = arrecad.k00_numpre  and v01_numpar = arrecad.k00_numpar
					where k10_numpre = j20_numpre 
					and k10_receita = j21_receit 
					) as x
					where sum_arrecad = 0 and sum_arrepaga > 0
			)
			is null and sum(coalesce(j21_valor, 0)) > 0
then 0
else 1
end as dividaemdia,

case 
when ( select 1 from 
					(
					select sum ( case when arrepaga.k00_numpre is not null then 1 else 0 end ) as sum_arrepaga,
								 sum ( case when arrecant.k00_numpre is not null then 1 else 0 end ) as sum_arrecant,
								 sum ( case when divold.k10_numpre is not null then 1 else 0 end ) as sum_divold
					from arrecant
					left join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre and arrepaga.k00_receit = arrecant.k00_receit
					left join divold   on divold.k10_numpre = arrecant.k00_numpre and divold.k10_receita = arrecant.k00_receit
					where arrecant.k00_numpre = j20_numpre and arrecant.k00_receit = j21_receit
					) as x
					where sum_arrepaga = 0 and sum_arrecant > 0 and sum_divold = 0
			)
			is null and sum(coalesce(j21_valor, 0)) > 0
then 0
else 1
end as cancelado,

case 
when ( select 1 from 
					(
					select ( select count(*) from arrecad  where arrecad.k00_numpre   = j20_numpre and arrecad.k00_receit   = j21_receit ) as sum_arrecad,
								 ( select count(*) from arrecant where arrecant.k00_numpre  = j20_numpre and arrecant.k00_receit  = j21_receit ) as sum_arrecant,
								 ( select count(*) from arrepaga where arrepaga.k00_numpre  = j20_numpre and arrepaga.k00_receit  = j21_receit ) as sum_arrepaga,
								 ( select count(*) from divold   where divold.k10_numpre    = j20_numpre and divold.k10_receita   = j21_receit ) as sum_divold
					) as x
					where sum_arrecad = 0 and sum_arrepaga = 0 and sum_arrecant = 0 and sum_divold = 0
			)
			is null and sum(coalesce(j21_valor, 0)) > 0
then 0
else 1
end as semorigem

from	(
select	j20_anousu,
j20_numpre,
j21_matric,
j21_receit,
k02_descr,
sum(j21_valor) as j21_valor
from iptubase
inner join iptucalc  on j23_matric = j01_matric
and j23_anousu = $anousu
inner join iptunump  on j20_anousu = j23_anousu
and j20_matric = j23_matric
left  join iptucalv  on j21_anousu = j23_anousu
and j21_matric = j23_matric
inner join tabrec    on k02_codigo = j21_receit
inner join lote      on j34_idbql  = j01_idbql
where    j23_anousu = $anousu
$where
group by	j20_anousu,
j20_numpre,
j21_matric,
j21_receit,
k02_descr
having sum(j21_valor) > 0
) as x
group by 
j20_anousu,
j21_receit,
k02_descr,
j20_numpre
) as x
group by j20_anousu,
j21_receit,
k02_descr
order by j21_receit;
";
//die($sqlemdia);

$resultemdia= pg_query($sqlemdia) or die($sqlemdia);
$linhasemdia= pg_num_rows($resultemdia);

$sqlporparcela="
select
j20_anousu,
j21_receit,
k02_descr,
j21_matric,
" . 
($anousu == db_getsession("DB_anousu")
?
"(	select count(distinct k00_numpar) as k00_numpar
from arrecad
where	k00_dtvenc < current_date and
k00_numpre = j20_numpre and
k00_receit = j21_receit)"
:
"
(	select count(distinct k00_numpar) as k00_numpar
	from arrecad
	left join divold on k00_numpre = k10_numpre and k00_numpar = k10_receita
	where	k00_numpre = j20_numpre and
				k00_receit = j21_receit and k10_numpre is null
)
"
) .
" as k00_numpar,
	( select count(distinct k10_numpar) as k10_numpar
		from divold
		where k10_numpre = j20_numpre and
		k10_receita = j21_receit
	) as k10_numpar
from	(
select	j20_anousu,
j20_numpre,
j21_matric,
j21_receit,
k02_descr,
sum(j21_valor) as j21_valor
from iptubase
inner join iptucalc  on j23_matric = j01_matric
and j23_anousu = $anousu
inner join iptunump  on j20_anousu = j23_anousu
and j20_matric = j23_matric
left  join iptucalv  on j21_anousu = j23_anousu
and j21_matric = j23_matric
inner join tabrec    on k02_codigo = j21_receit
inner join lote      on j34_idbql  = j01_idbql
where    j23_anousu = $anousu
$where
group by	j20_anousu,
j20_numpre,
j21_matric,
j21_receit,
k02_descr
having sum(j21_valor) > 0
) as x
";
//die($sqlporparcela);
$resultporparcela = pg_exec($sqlporparcela) or die($sqlporparcela);

$sqlmaxparcarrecad = "select max(k00_numpar) as max_arrecad from ($sqlporparcela) as x";
$resultmaxparcarrecad = pg_exec($sqlmaxparcarrecad) or die($sqlmaxparcarrecad);
db_fieldsmemory($resultmaxparcarrecad,0);

$sqlmaxparcdivold = "select max(k10_numpar) as max_divold from ($sqlporparcela) as x";
$resultmaxparcdivold = pg_exec($sqlmaxparcdivold) or die($sqlmaxparcdivold);
db_fieldsmemory($resultmaxparcdivold,0);

if ($max_arrecad >= $max_divold) {
  $maxparc=$max_arrecad;
} else {
  $maxparc=$max_divold;
}

$where_zona = "";
if($zona!=0 || $zona!="") {
  $where_zona .= " where j50_zona in ($zona)";
}
$sqlzona=" select * from zonas ".$where_zona;
$resultzona= pg_query($sqlzona);
$linhaszona= pg_num_rows($resultzona);

$head1 = "ESTATÍSTICAS DO IPTU CALCULADO E PAGO ";
$head2 = "Exercício:".$anousu;
if($linhaszona>0){
  $vir="";
  $zonas="";
  for($x = 0;$x < $linhaszona;$x++){
    db_fieldsmemory($resultzona,$x);
    $zonas .= $vir.$j50_descr;
    $vir=",";
  }
  $head3 = "Zona(s):".$zonas;
}

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(180);
$pdf->SetFont('Arial','B',8);
$alt = 6 ; 
$pdf->Cell(190,$alt,"DADOS DO LANÇAMENTO",0,0,"C",1);
$pdf->ln();
$pdf->Cell(190,$alt,"IPTU",0,0,"C",0);
$pdf->ln();
$pdf->Cell(50,$alt,"TIPO",0,0,"L",0);
$pdf->Cell(35,$alt,"QUANTIDADE",0,0,"R",0);
$pdf->Cell(35,$alt,"VALOR VENAL",0,0,"R",0);
$pdf->Cell(35,$alt,"VALOR TRIBUTO",0,0,"R",0);
$pdf->Cell(35,$alt,"PERCENTUAL",0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','',8);
$total_qtd = "";
$total_venal = "";
$total_tributo = "";
// para pegar o total para o percentual
for($i = 0;$i < $linhas;$i++){
  db_fieldsmemory($result,$i);
  if(in_array($j21_receit,$aRecIptu) || in_array($j21_receit,$aRecOnera)){
     $total_qtd += $qtd;
  }
}
// ########################## IPTU - PREDIO E TERRENO ############################
for($i = 0;$i < $linhas;$i++){
  db_fieldsmemory($result,$i);
   if(in_array($j21_receit,$aRecIptu) || in_array($j21_receit,$aRecOnera) ){  
    $percente = ($qtd  * 100)/$total_qtd;
    $percente = round($percente,2);
	
    $pdf->Cell(50,$alt,$j21_receit." - ".$descricao,0,0,"L",0);	
    $pdf->Cell(35,$alt,$qtd,0,0,"R",0);
    $pdf->Cell(35,$alt,db_formatar($vlr_venal_total,"f"),0,0,"R",0);
    $pdf->Cell(35,$alt,db_formatar($tributo,"f"),0,0,"R",0);
    $pdf->Cell(35,$alt,$percente."%",0,0,"R",0);	
    $pdf->ln();
    $total_venal += $vlr_venal_total;
    $total_tributo += $tributo;
  }
}
// total
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,$alt,"Total","T",0,"L",0);
$pdf->Cell(35,$alt,$total_qtd,"T",0,"R",0);
$pdf->Cell(35,$alt,db_formatar($total_venal,"f"),"T",0,"R",0);
$pdf->Cell(35,$alt,db_formatar($total_tributo,"f"),"T",0,"R",0);
$pdf->Cell(35,$alt,"100%","T",0,"R",0);	
$pdf->ln();

if(count($aRecOnera) > 0){
 $pdf->SetFont('Arial','',6);
 $pdf->Cell(120,$alt,"**Estão somadas no total de quantidade as Onerações","0",0,"L",0);
 $pdf->ln();
}

// ########################## TAXAS ############################
$pdf->ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,$alt,"TAXAS",0,0,"C",0);
$pdf->ln();
$pdf->Cell(50,$alt,"TIPO",0,0,"L",0);
$pdf->Cell(35,$alt,"QUANTIDADE",0,0,"R",0);
$pdf->Cell(70,$alt,"VALOR CALCULADO",0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','',8);
for($i = 0;$i < $linhas;$i++){
  db_fieldsmemory($result,$i);
  if(!in_array($j21_receit,$aRecIptu) && !in_array($j21_receit,$aRecOnera)){
    $pdf->Cell(50,$alt,$j21_receit." - ".$descricao,0,0,"L",0);
    $pdf->Cell(35,$alt,$qtd,0,0,"R",0);
    $pdf->Cell(70,$alt,db_formatar($tributo,"f"),0,0,"R",0);
    $pdf->ln();
  }
}

// ########################## DADOS DOS PAGAMENTOS ############################
$pdf->SetFont('Arial','B',8);
$pdf->ln(5);
$pdf->Cell(190,$alt,"DADOS DOS PAGAMENTOS",0,0,"C",1);
$pdf->ln();
$pdf->Cell(50,$alt,"TIPO",0,0,"L",0);
$pdf->Cell(35,$alt,"",0,0,"C",0);
$pdf->Cell(70,$alt,"VALOR PAGO",0,0,"R",0);
$pdf->ln();
$pdf->SetFont('Arial','',8);
$total_pago = "";
for($i = 0;$i < $linhas;$i++){
  db_fieldsmemory($result,$i);
  $pdf->Cell(50,$alt,$j21_receit." - ".$descricao,0,0,"L",0);
  $pdf->Cell(35,$alt,"",0,0,"C",0);
  $pdf->Cell(70,$alt,db_formatar($pagamentos,"f"),0,0,"R",0);
  $pdf->ln();
  $total_pago +=$pagamentos;
}
//total
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,$alt,"Total","T",0,"L",0);
$pdf->Cell(35,$alt,"","T",0,"C",0);
$pdf->Cell(70,$alt,db_formatar($total_pago,"f"),"T",0,"R",0);
$pdf->Cell(35,$alt,"","T",0,"R",0);
$pdf->ln();

$pdf->ln(5);
$pdf->Cell(190,$alt,"DADOS DE INADIMPLÊNCIA",0,0,"C",1);
$pdf->ln();

// segunda linha
$pdf->Cell(45,$alt,"",0,0,"L",1);
$pdf->Cell(25,$alt,"",0,0,"C",0);

$pdf->Cell(60,$alt,"E M  D I A",0,0,"C",1);

$pdf->Cell(60,$alt,"E M  D I V I D A",0,0,"C",0);
$pdf->ln();


$pdf->Cell(45,$alt,"",0,0,"L",1);
$pdf->Cell(25,$alt,"QTD",0,0,"C",0);

$pdf->Cell(20,$alt,"QTD",0,0,"C",1);
$pdf->Cell(20,$alt,"QTD",0,0,"C",1);
$pdf->Cell(20,$alt,"",0,0,"R",1);

$pdf->Cell(20,$alt,"QTD",0,0,"C",0);
$pdf->Cell(20,$alt,"QTD",0,0,"C",0);
$pdf->Cell(20,$alt,"",0,0,"R",0);
$pdf->ln();

$pdf->Cell(45,$alt,"TIPO",0,0,"L",1);
$pdf->Cell(25,$alt,"CALCULADA",0,0,"C",0);

$pdf->Cell(20,$alt,"EM DIA",0,0,"C",1);
$pdf->Cell(20,$alt,"CANC",0,0,"C",1);
$pdf->Cell(20,$alt,"INADIMP **",0,0,"C",1);

$pdf->Cell(20,$alt,"EM DIVIDA",0,0,"C",0);
$pdf->Cell(20,$alt,"DIVIDA EM DIA",0,0,"C",0);
$pdf->Cell(20,$alt,"INADIMP",0,0,"C",0);
$pdf->ln();

$pdf->SetFont('Arial','',8);

for($x = 0;$x < $linhasemdia;$x++){
  db_fieldsmemory($resultemdia,$x);

//	$qtd_emdia				= (int) $qtd_emdia;
//	$qtd_divida				= (int) $qtd_divida;
//	$qtd_dividaemdia	= (int) $qtd_dividaemdia;
//	$qtd_cancelado		= (int) $qtd_cancelado;
//	$qtd_semorigem		= (int) $qtd_semorigem;
  
	if ($qtd_emdia == 0 or (($qtd_calculada - $qtd_cancelado - $qtd_semorigem) == 0)) {
		$percente_emdia = 0;
	} else {
		$percente_emdia = round(((($qtd_emdia * 100)/($qtd_calculada - $qtd_cancelado - $qtd_semorigem))-100)*(-1),2);
	}
  
  $pdf->Cell(45,$alt,$receita." - ".$descricao1,0,0,"L",1);
  $pdf->Cell(25,$alt,trim(db_formatar( $qtd_calculada, 's')),0,0,"R",0);
  
  $pdf->Cell(20,$alt,trim(db_formatar( $qtd_emdia, 's')),0,0,"R",1);

  $pdf->Cell(20,$alt,trim(db_formatar( $qtd_cancelado + $qtd_semorigem + 0, 's')),0,0,"R",0);
  $pdf->Cell(20,$alt,trim(db_formatar( $percente_emdia, 's'))."%",0,0,"R",1);
  
  //$percente_emdiv=($qtd_dividaemdia == 0 or $qtd_divida == 0?0:$qtd_dividaemdia / $qtd_divida * 100);
  if ($qtd_divida == 0 or $qtd_dividaemdia == 0) {
    $percente_emdiv = 0;
  } else {
    $percente_emdiv=round(100 - ($qtd_dividaemdia / $qtd_divida * 100),2);
  }
  $pdf->Cell(20,$alt,trim(db_formatar( $qtd_divida, 's')),0,0,"R",0);
  $pdf->Cell(20,$alt,trim(db_formatar( $qtd_dividaemdia, 's')),0,0,"R",0);
  $pdf->Cell(20,$alt,trim(db_formatar( $percente_emdiv, 's')) . "%",0,0,"R",0);
  $pdf->ln();
  
}

$array_parcs = array();

for ($reg=0; $reg < pg_num_rows($resultporparcela); $reg++) {
  db_fieldsmemory($resultporparcela, $reg);
  
  // arrecad
  if ($k00_numpar > 0) {
    if (isset($array_parcs[$j21_receit][$k00_numpar][0])) {
      $array_parcs[$j21_receit][$k00_numpar][0] += 1;
    } else {
      $array_parcs[$j21_receit][$k00_numpar][0] = 1;
    }
  }
  
  // divold
  if ($k10_numpar > 0) {
    if (isset($array_parcs[$j21_receit][$k10_numpar][1])) {
      $array_parcs[$j21_receit][$k10_numpar][1] += 1;
    } else {
      $array_parcs[$j21_receit][$k10_numpar][1] = 1;
    }
  }
  
}

$pdf->ln(5);
$pdf->SetFont('Arial','',6);
$pdf->Cell(100,$alt,"*  os campos onde são listadas QTD, referem-se a quantidade de matrículas;",0,1,"L",0);
$pdf->Cell(100,$alt,"*  o campo [quantidade em dívida] contém a quantidade total de registros. O valor do campo [quantidade de divida em dia] não foi diminuido deste campo;",0,1,"L",0);
$pdf->Cell(100,$alt,"** o percentual é calculado com base na [quantidade em dia] dividido por ( [quantidade calculada] menos a [quantidade cancelada] );",0,1,"L",0);

$pdf->AddPage("L"); // adiciona uma pagina

$pdf->SetFont('Arial','B',12);
$pdf->ln();
$pdf->Cell(100,$alt,"RELAÇÃO POR QUANTIDADE DE PARCELAS EM ABERTO",0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->ln();
$pdf->ln();

$pdf->Cell(10,$alt,"REC","T",0,"L",0);
$pdf->Cell(40,$alt,"DESCRICAO DA RECEITA","T",0,"L",0);

$totais = array();

$preenche=1;
$x=0;
for ($parc=1; $parc <= $maxparc; $parc++) {
  $pdf->Cell(19,$alt,trim(db_formatar($parc, 's')) . " PARC","TLR",0,"C",$preenche);
  $preenche = ($x++%2==0?0:1);
}
$pdf->Cell(19,$alt,"TOTAL","TLR",0,"C",$preenche);
for ($parc=0; $parc <= $maxparc; $parc++) {
  $totais[0][$parc]=0;
  $totais[1][$parc]=0;
}

$pdf->ln();
$pdf->Cell(50,$alt,"","B",0,"L",0);
$preenche=1;
$x=0;
for ($parc=1; $parc <= $maxparc; $parc++) {
  $pdf->Cell(9,$alt,"DIA","BL",0,"C",$preenche);
  $pdf->Cell(9,$alt,"DIV","B",0,"C",$preenche);
  $pdf->Cell(1,$alt,"","BR",0,"C",$preenche);
  $preenche = ($x++%2==0?0:1);
}
$pdf->Cell(9,$alt,"DIA","BL",0,"C",$preenche);
$pdf->Cell(9,$alt,"DIV","B",0,"C",$preenche);
$pdf->Cell(1,$alt,"","BR",0,"C",$preenche);
$pdf->ln();

foreach($array_parcs as $a => $b[]) {
  
  $sqlreceita = "select k02_descr from tabrec where k02_codigo = $a";
  $resultreceita = pg_exec($sqlreceita) or die($sqlreceita);
  db_fieldsmemory($resultreceita,0);
  
  $pdf->Cell(10,$alt,$a,0,0,"L",0);
  $pdf->Cell(40,$alt,$k02_descr,"LR",0,"L",0);
  
  $x=0;
	$preenche=1;
	$total_dia=0;
	$total_div=0;
  for ($parc=1; $parc <= $maxparc; $parc++) {
    
    $aa=$parc;
    $pdf->Cell(9,$alt,db_formatar((isset($array_parcs[$a][$aa][0])?$array_parcs[$a][$aa][0]:0),'s'),"L",0,"R",$preenche);
    $pdf->Cell(9,$alt,db_formatar((isset($array_parcs[$a][$aa][1])?$array_parcs[$a][$aa][1]:0),'s'),0,0,"R",$preenche);
    $pdf->Cell(1,$alt,"","R",0,"L",$preenche);
    
    $totais[0][$aa] += (isset($array_parcs[$a][$aa][0])?$array_parcs[$a][$aa][0]:0);
    $totais[1][$aa] += (isset($array_parcs[$a][$aa][1])?$array_parcs[$a][$aa][1]:0);

		$total_dia+=(isset($array_parcs[$a][$aa][0])?$array_parcs[$a][$aa][0]:0);
		$total_div+=(isset($array_parcs[$a][$aa][1])?$array_parcs[$a][$aa][1]:0);

    $preenche=($x++%2==0?0:1);

  }
	$pdf->Cell(9,$alt,db_formatar($total_dia,'s'),"L",0,"R",$preenche);
	$pdf->Cell(9,$alt,db_formatar($total_div,'s'),0,0,"R",$preenche);
	$pdf->Cell(1,$alt,"","R",0,"L",$preenche);
  $pdf->ln();
  
}

$pdf->ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(100,$alt,"* Este relatório não considera matrículas com isenção lançada",0,0,"L",0);

$pdf->Output();

?>