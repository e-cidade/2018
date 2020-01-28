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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "RELATÓRIO DO ISSQN PAGO";
$head4 = "Exercício do ISS: ".$anousu;
$head5 = "Período Pagamento: ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');
$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','B',11);

/////// pagto issqn fixo
$sql  = "select k00_receit as receita, ";
$sql .= "       k02_drecei as descricao, ";
$sql .= "       sum(k00_valor) as valor ";
$sql .= "  from arrepaga ";
$sql .= "       inner join isscalc on q01_numpre = k00_numpre ";
$sql .= "       left  join tabrec  on k02_codigo = k00_receit ";
$sql .= " where q01_anousu = {$anousu} ";
$sql .= "   and q01_cadcal = 2 "; // CADCALC 2 = ISS FIXO
$sql .= "   and k00_dtpaga between '{$datai}' and '{$dataf}' ";
$sql .= "   and not exists(
              select 1 from abatimentoutilizacaodestino
                inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao
                inner join abatimento on k125_sequencial = k157_abatimento
                where k170_numpre = arrepaga.k00_numpre
                  and k170_numpar = arrepaga.k00_numpar
                  and k125_tipoabatimento = 3
            )";
$sql .= "group by k00_receit, ";
$sql .= "         k02_drecei  ";
$sql .= "order by k00_receit  ";

$result = db_query($sql);
$num = pg_numrows($result);
$linha = 0;
$pdf->ln(2);
$pre = 0;
$total_fixo = 0;
$total_geral = 0;
$pagina = 0;
$imposto = 'ISSQN FIXO';
$borda   = "LRT";
$pdf->SetFont('Arial','B',9);
$pdf->Cell(50,6,"IMPOSTO/TAXA",1,0,"C",1);
$pdf->Cell(15,6,"RECEITA",1,0,"C",1);
$pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
$pdf->Cell(30,6,"VALOR PAGO",1,1,"C",1);
$pdf->SetFont('Arial','B',9);

for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i);

  if($linha++>45){
    $linha = 0;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,6,"IMPOSTO/TAXA",1,0,"C",1);
    $pdf->Cell(15,6,"RECEITA",1,0,"C",1);
    $pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
    $pdf->Cell(30,6,"VALOR PAGO",1,1,"C",1);
    $pagina = $pdf->PageNo();
  }

  $pdf->SetFont('Arial','',7);
  $pdf->cell(50,4,$imposto,$borda,0,"L",$pre);
  $pdf->cell(15,4,$receita,1,0,"R",$pre);
  $pdf->cell(80,4,$descricao,1,0,"L",$pre);
  $pdf->cell(30,4,db_formatar($valor,'f'),1,1,"R",$pre);
  $total_fixo  += $valor;
  $total_geral += $valor;
  $imposto = "";
  $borda   = "LR";
}
if($num>0) {
  //$pdf->Ln(5);
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(145,6,"TOTAL : ",1,0,"L",0);
  $pdf->Cell(30,6,db_formatar($total_fixo,'f'),1,1,"R",0);
}


////// pagto issqn variavel
$sql  = "select k00_receit as receita, ";
$sql .= "       k02_drecei as descricao, ";
$sql .= "       round(sum(k00_valor), 2) as valor ";
$sql .= "  from issvar ";
$sql .= "       inner join arreinscr  on arreinscr.k00_numpre = q05_numpre ";
$sql .= "       inner join arrepaga   on arrepaga.k00_numpre = q05_numpre ";
$sql .= "                            and arrepaga.k00_numpar = q05_numpar ";
$sql .= "       left  join tabrec     on k02_codigo = k00_receit ";
$sql .= " where q05_ano = {$anousu} ";
$sql .= "   and k00_dtpaga between '{$datai}' and '{$dataf}' ";
$sql .= "   and exists (select 1 ";
$sql .= "                 from arrecant ";
$sql .= "                where arrecant.k00_numpre = q05_numpre ";
$sql .= "                  and arrecant.k00_numpar = q05_numpar) ";
$sql .= "   and case ";
$sql .= "         when q05_vlrinf is null and q05_valor >= 0 then ";
$sql .= "           q05_valor ";
$sql .= "         else ";
$sql .= "           q05_vlrinf ";
$sql .= "       end >= 0 ";
$sql .= "   and not exists(
              select 1 from abatimentoutilizacaodestino
                inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao
                inner join abatimento on k125_sequencial = k157_abatimento
                where k170_numpre = arrepaga.k00_numpre
                  and k170_numpar = arrepaga.k00_numpar
                  and k125_tipoabatimento = 3
            )";
$sql .= "group by k00_receit, ";
$sql .= "         k02_drecei  ";
$sql .= "order by k00_receit  ";


$result = db_query($sql);
$num = pg_numrows($result);
$pdf->SetFont('Arial','B',9);
$imposto = "ISSQN VARIÁVEL";
$borda   = "LRT";
$pre = 0;
$total_variavel = 0;
$pagina = 0;
for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i);

  if($linha++>45){
    $linha = 0;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,6,"IMPOSTO/TAXA",1,0,"C",1);
    $pdf->Cell(15,6,"RECEITA",1,0,"C",1);
    $pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
    $pdf->Cell(30,6,"VALOR PAGO",1,1,"C",1);
    $pagina = $pdf->PageNo();
  }

  $pdf->SetFont('Arial','',7);
  $pdf->cell(50,4,$imposto,$borda,0,"L",$pre);
  $pdf->cell(15,4,$receita,1,0,"R",$pre);
  $pdf->cell(80,4,$descricao,1,0,"L",$pre);
  $pdf->cell(30,4,db_formatar($valor,'f'),1,1,"R",$pre);
  $total_variavel += $valor;
  $total_geral += $valor;
  $imposto = "";
  $borda   = "LR";
}
if($num>0) {
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(145,6,"TOTAL : ",1,0,"L",0);
  $pdf->Cell(30,6,db_formatar($total_variavel,'f'),1,1,"R",0);
}


////// pagto alvara
$sql  = "select k00_receit as receita, ";
$sql .= "       k02_drecei as descricao, ";
$sql .= "       sum(k00_valor) as valor ";
$sql .= "  from arrepaga ";
$sql .= "       inner join isscalc on q01_numpre = k00_numpre ";
$sql .= "       left  join tabrec  on k02_codigo = k00_receit ";
$sql .= " where q01_anousu = {$anousu} ";
$sql .= "   and q01_cadcal = 1 "; // CADCALC 1 = ALVARA
$sql .= "   and k00_dtpaga between '{$datai}' and '{$dataf}' ";
$sql .= "   and not exists(
              select 1 from abatimentoutilizacaodestino
                inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao
                inner join abatimento on k125_sequencial = k157_abatimento
                where k170_numpre = arrepaga.k00_numpre
                  and k170_numpar = arrepaga.k00_numpar
                  and k125_tipoabatimento = 3
            )";
$sql .= "group by k00_receit, ";
$sql .= "         k02_drecei  ";
$sql .= "order by k00_receit  ";

$result = db_query($sql);
$num = pg_numrows($result);
$pdf->SetFont('Arial','B',9);
$imposto = "ALVARÁ";
$borda = "LRT";
$pre = 0;
$total_alvara = 0;
$pagina = 0;

for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i);

  if($linha++>45){
    $linha = 0;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,6,"IMPOSTO/TAXA",1,0,"C",1);
    $pdf->Cell(15,6,"RECEITA",1,0,"C",1);
    $pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
    $pdf->Cell(30,6,"VALOR PAGO",1,1,"C",1);
    $pagina = $pdf->PageNo();
  }

  $pdf->SetFont('Arial','',7);
  $pdf->cell(50,4,$imposto,$borda,0,"L",$pre);
  $pdf->cell(15,4,$receita,1,0,"R",$pre);
  $pdf->cell(80,4,$descricao,1,0,"L",$pre);
  $pdf->cell(30,4,db_formatar($valor,'f'),1,1,"R",$pre);
  $total_alvara += $valor;
  $total_geral  += $valor;
  $imposto = "";
  $borda   = "LR";
}
if($num>0) {
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(145,6,"TOTAL : ",1,0,"L",0);
  $pdf->Cell(30,6,db_formatar($total_alvara,'f'),1,1,"R",0);
}



////// pagto vistorias
$sql  = "select k00_receit, ";
$sql .= "       k02_drecei as descricao, ";
$sql .= "       sum(k00_valor) as valor ";
$sql .= "  from vistorianumpre ";
$sql .= "       inner join arrepaga  on k00_numpre  = y69_numpre ";
$sql .= "       inner join vistorias on y70_codvist = y69_codvist ";
$sql .= "       left  join tabrec    on k02_codigo  = k00_receit ";
$sql .= " where k00_dtpaga between '{$datai}' and '{$dataf}' ";
$sql .= "   and extract(year from y70_data) = {$anousu} ";
$sql .= "   and not exists(
              select 1 from abatimentoutilizacaodestino
                inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao
                inner join abatimento on k125_sequencial = k157_abatimento
                where k170_numpre = arrepaga.k00_numpre
                  and k170_numpar = arrepaga.k00_numpar
                  and k125_tipoabatimento = 3
            )";
$sql .= "group by k00_receit, ";
$sql .= "         k02_drecei  ";
$sql .= "order by k00_receit  ";

$result = db_query($sql);
$num = pg_numrows($result);
$pdf->SetFont('Arial','B',9);
$imposto = "VISTORIAS";
$borda = "LRT";
$pre = 0;
$total_vistorias = 0;
$pagina = 0;

for($i=0;$i<$num;$i++) {
  db_fieldsmemory($result,$i);

  if($linha++>45){
    $linha = 0;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(50,6,"IMPOSTO/TAXA",1,0,"C",1);
    $pdf->Cell(15,6,"RECEITA",1,0,"C",1);
    $pdf->Cell(80,6,"DESCRIÇÃO",1,0,"C",1);
    $pdf->Cell(30,6,"VALOR PAGO",1,1,"C",1);
    $pagina = $pdf->PageNo();
  }

  $pdf->SetFont('Arial','',7);
  $pdf->cell(50,4,$imposto,$borda,0,"L",$pre);
  $pdf->cell(15,4,$receita,1,0,"R",$pre);
  $pdf->cell(80,4,$descricao,1,0,"L",$pre);
  $pdf->cell(30,4,db_formatar($valor,'f'),1,1,"R",$pre);
  $total_vistorias += $valor;
  $total_geral     += $valor;
  $imposto = "";
  $borda   = "LR";
}
if($num>0) {
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(145,6,"TOTAL : ",1,0,"L",0);
  $pdf->Cell(30,6,db_formatar($total_vistorias,'f'),1,1,"R",0);
}

$pdf->ln(3);
$pdf->Cell(145,6,"TOTAL GERAL : ",1,0,"L",0);
$pdf->Cell(30,6,db_formatar($total_geral,'f'),1,1,"R",0);


$data = array(
    'ISSQN FIXO    ' => $total_fixo,
    'ISSQN VARIAVEL' => $total_variavel,
    'ALVARÁ        ' => $total_alvara,
    'VISTORIAS     ' => $total_vistorias);

$pdf->setfont('Arial',"B",12);
$pdf->ln(8);
$pdf->multicell(0,6,'GRÁFICO COMPARATIVO',0,"C",0,0);
$pdf->ln(8);
$pdf->SetX(50);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$col4=array(100,255,100);
$pdf->PieChart(130, 100, $data, '%l - %v - (%p)', array($col1,$col2,$col3,$col4));

$pdf->Output();

?>