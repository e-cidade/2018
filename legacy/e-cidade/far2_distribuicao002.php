<?php
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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

global $HTTP_SESSION_VARS;
if (!isset($HTTP_SESSION_VARS["DB_itemmenu_acessado"])) {
  $HTTP_SESSION_VARS["DB_itemmenu_acessado"] = 7068;
}

$oDaoMaterSaude       = db_utils::getDao('far_matersaude');
$oDaoMatmater         = db_utils::getDao('matmater');
$oDaoMatEstoqueIniMei = db_utils::getDao('matestoqueinimei');
$deptno               = explode(",",$listadepart);
$departamento         = "";

if (($opcao == 'com') && ($deptno[0] != null)) {

  if ($materiais=='1') {
    $departamento = " and fa04_i_unidades in ($listadepart)";
  } else {
    $departamento = " and m70_coddepto in ($listadepart)";
  }
}

$order = "";
if ($ordem == "a") {
  $order = " m60_descr ";
} else {
  $order = " m60_codmater ";
}

$unidades_get   = explode(',',$listadepart);
$where_unidades = ' where sd02_i_codigo = ';

for ($c = 0; $c < count($unidades_get) - 1; $c++) {
  $where_unidades .= $unidades_get[$c].' or sd02_i_codigo = ';
}

$where_unidades .= $unidades_get[$c];
///////Select Traz os Codigos dos Remédios
if ($distribu == 'N') {

  if ($materiais == '1') {

    $sql  = "select distinct (m60_codmater) as remedio,m60_descr,m61_descr";
    $sql .= "  from far_retiradaitens";
    $sql .= "       inner join far_matersaude    on far_matersaude.fa01_i_codigo      = far_retiradaitens.fa06_i_matersaude";
    $sql .= "       inner join matmater          on matmater.m60_codmater             = far_matersaude.fa01_i_codmater";
    $sql .= "       inner join matunid           on matunid.m61_codmatunid            = matmater.m60_codmatunid";
    $sql .= "       inner join far_retirada      on fa06_i_retirada                   = fa04_i_codigo";
    $sql .= "       inner join far_retiradarequi on far_retiradarequi.fa07_i_retirada = far_retirada.fa04_i_codigo";
    $sql .= "       inner join unidades          on fa04_i_unidades                   = sd02_i_codigo {$where_unidades} order by {$order}";
  } else {

    $sql = $oDaoMaterSaude->sql_query_atendrequiitem("",
      "distinct m60_codmater as remedio, m60_descr,m61_descr",
      $order,
      "m70_coddepto in($listadepart)");
  }
} else {

  if ($materiais == '1') {

    $sql  = "select distinct (m60_codmater) as remedio,m60_descr,m61_descr";
    $sql .= "  from far_matersaude";
    $sql .= "       inner join matmater on matmater.m60_codmater  = far_matersaude.fa01_i_codmater";
    $sql .= "       inner join matunid  on matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= " order by {$order}";
  } else {

    $sql = $oDaoMatmater->sql_query("",
      "distinct m60_codmater as remedio, m60_descr,m61_descr",
      $order, "");
  }
}
$result = db_query($sql);
$linhas = pg_num_rows($result);
if ($linhas == 0) { ?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
<?php
  exit;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

if ($materiais == '1') {
  $head1 = "Relatorio de Medicamentos";
} else {
  $head1 = "Relatório Geral";
}

$head2 = "  Quebra : Nenhuma";
if ($quebra == "d") {
  $head2 = "  Quebra : Depósito";
}

$head3 = "  Sem Departamento ";
if ($opcao=='com') {
  $head3 = "  Com Departamento ";
}

$head4 = "  Estoque zerado : Não ";
if ($distribu == 'S') {
  $head4 = "  Estoque zerado : Sim ";
}

$cor = "0";
$pdf->setfillcolor(223);
$pdf->setfont('arial','B',9);
$cont  = 0;
$veses = 1;
if ($quebra == "d") {

  if ($deptno[0] == null) {

    $sqld    = "select distinct (fa04_i_unidades) as depcod from far_retirada";
    $resultd = db_query($sqld);
    $linhasd = pg_num_rows($resultd);
    for ($d = 0; $d < $linhasd; $d++) {

      db_fieldsmemory($resultd,$d);
      $deptno[$d] = $depcod;
    }
  }
  $veses = count($deptno);
}

for ($x1 = 0; $x1 < 13; $x1++) {
  $ttmes[$x1] = 0;
}

for ($x = 0; $x < $veses; $x++) {

  if ($quebra == "d") {

    if ($materiais == '1') {
      $departamento = " and fa04_i_unidades = {$deptno[$x]}";
    } else {
      $departamento = " and m70_coddepto = {$deptno[$x]}";
    }
  }

  $total = 0;
  for ($x1 = 0; $x1 < 13; $x1++) {
    $tmes[$x1] = 0;
  }

  $cont = 25;
  for ($i = 0; $i < $linhas; $i++) {

    if ($cont == 25) {

      $tamunid  = 35;
      $tamquant = 45;
      $tammed   = 70;
      $pdf->Addpage("L");
      $pdf->ln(5);
      $pdf->setfont('arial','b',9);
      if ($quebra == "d") {

        $sqldep    = "select descrdepto from db_depart where coddepto = {$deptno[$x]}";
        $resultdep = db_query($sqldep);
        db_fieldsmemory($resultdep,0);
        $pdf->cell(280, 5, "Depósito - {$deptno[$x]} {$descrdepto}", 0, 1, "L", $cor);
        $cont++;
        $tamunid  = 55;
        $tamquant = 55;
        $tammed   = 100;

      }
      
      if ($materiais == '1') {
        $pdf->cell(278, 5, "Relatorio de Medicamentos", 1, 1, "C", $cor);
      } else {
        $pdf->cell(278, 5, "Relatório Geral", 1, 1, "C", $cor);
      }

      $pdf->cell(10, 5, "Cód.", 1, 0, "L", $cor);
      if ($materiais == '1') {
        $pdf->cell(45, 5, "Medicamentos", 1, 0, "L", $cor);
      } else {
        $pdf->cell(45, 5, "Materiais", 1, 0, "L", $cor);
      }

      $pdf->cell(19, 5, "Unid.",     1, 0, "L", $cor);
      $pdf->cell(14, 5, "Jan",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Fev",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Mar",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Abr",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Mai",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Jun",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Jul",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Ago",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Set",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Out",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Nov",       1, 0, "C", $cor);
      $pdf->cell(14, 5, "Dez",       1, 0, "C", $cor);
      $pdf->cell(17, 5, "Total",     1, 0, "C", $cor);
      $pdf->cell(19, 5, "Média Mês", 1, 1, "C", $cor);
      $cont = 0;
    }

    $mes[1]  = 0;
    $mes[2]  = 0;
    $mes[3]  = 0;
    $mes[4]  = 0;
    $mes[5]  = 0;
    $mes[6]  = 0;
    $mes[7]  = 0;
    $mes[8]  = 0;
    $mes[9]  = 0;
    $mes[10] = 0;
    $mes[11] = 0;
    $mes[12] = 0;
    db_fieldsmemory($result,$i);
    $tlinha = 0;
    for ($m = 1; $m < 13; $m++) {

      if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
        $dialimite = 31;
      } elseif ($m == 4 || $m == 6 || $m == 9 || $m == 11) {
        $dialimite = 30;
      } else {
        $dialimite = 28;
      }
      
      $data_inicial = $iAno."-".$m."-01";
      $data_final   = $iAno."-".$m."-".$dialimite;
      if ($materiais == '1') {

        $sql2  = "select sum(fa06_f_quant) as quantidade from far_retiradaitens";
        $sql2 .= "       inner join far_matersaude    on far_matersaude.fa01_i_codigo      = far_retiradaitens.fa06_i_matersaude";
        $sql2 .= "       inner join far_retirada      on far_retirada.fa04_i_codigo        = far_retiradaitens.fa06_i_retirada";
        $sql2 .= "       inner join far_retiradarequi on far_retiradarequi.fa07_i_retirada = far_retirada.fa04_i_codigo";
        $sql2 .= " where fa01_i_codmater = {$remedio} {$departamento}";
        $sql2 .= "   and fa04_d_data between '{$data_inicial}' and '{$data_final}'";
      } else {

        $sWhere  = " m81_tipo = 2 and m60_codmater = {$remedio} {$departamento}";
        $sWhere .= "and m80_data between '{$data_inicial}' and '{$data_final}'";
        $sql2    = $oDaoMatEstoqueIniMei->sql_query_info("", " sum(m82_quant) as quantidade ", "", $sWhere);
      }

      $result2    = db_query($sql2);
      $linhas2    = pg_num_rows($result2);
      db_fieldsmemory($result2, 0);

      $mes[$m]    = $quantidade;
      $tlinha     = $tlinha + $quantidade;
      $tmes[$m]  += $quantidade;
      $ttmes[$m] += $tmes[$m];

    }
    
    if (($tlinha > 0 && $distribu == 'N') || $distribu == 'S') {

      $pdf->setfont('arial', '', 9);
      $pdf->cell(10, 5, $remedio, 1, 0, "L", $cor);
      $nome = substr($m60_descr, 0 ,20);
      $pdf->cell(45, 5, $nome, 1, 0, "L", $cor);
      $pdf->cell(19, 5, substr(str_pad(trim($m61_descr), 25), 0, 8), 1, 0, "L", $cor);

      $num_mes    = 0;
      $calc_media = 0;
      for ($mes1 = 1; $mes1 < 13; $mes1++) {

        $pdf->cell(14, 5, $mes[$mes1], 1, 0, "R", $cor);

        if ($mes[$mes1] > 0) {

          $num_mes++;
          $calc_media += $mes[$mes1];
        }
      }

      if ($num_mes == 0) {
        $num_mes = 1;
      }
      $media = $calc_media / $num_mes;
      $pdf->setfont('arial', 'B', 9);
      $pdf->cell(17, 5, "$calc_media", 1, 0, "R", $cor);
      $pdf->cell(19, 5, "".(number_format($media, 2, ',', '')), 1, 1, "R", $cor);
      $primeiro = 1;
      $cont++;
    }
  }

  $pdf->setfont('arial', 'B', 9);
  if ($materiais == '1') {
    $pdf->cell(278, 8, "Total de Medicamentos", 1, 1, "C", $cor);
  } else {
    $pdf->cell(278, 8, "Total de Materiais", 1, 1, "C", $cor);;
  }

  $pdf->cell(30, 6, "",                   1, 0, "R", $cor);
  $pdf->cell(17, 6, "Jan",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Fev",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Mar",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Abr",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Mai",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Jun",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Jul",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Ago",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Set",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Out",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Nov",                1, 0, "C", $cor);
  $pdf->cell(17, 6, "Dez",                1, 0, "C", $cor);
  $pdf->cell(22, 6, "Total do Ano",       1, 0, "C", $cor);
  $pdf->cell(22, 6, "Média do Ano",       1, 1, "C", $cor);
  $pdf->cell(30, 6, "Total Geral Mensal", 1, 0, "R", $cor);

  $num_mes_total = 0;
  $calc_total    = 0;

  $pdf->setfont('arial', 'B', 7.5);
  for ($mest = 1; $mest < 13; $mest++) {

    $pdf->cell(17, 6, number_format($tmes[$mest], 2, ',', ''), 1, 0, "R", $cor);

    if ($tmes[$mest] > 0) {

      $num_mes_total++;
      $calc_total += $tmes[$mest];
    }
  }

  if ($num_mes_total == 0) {
    $num_mes_total = 1;
  }
  
  $media_total = $calc_total / $num_mes_total;
  $pdf->cell(22, 6, (number_format($calc_total,  0, ',', '')), 1, 0, "R", $cor);
  $pdf->cell(22, 6, (number_format($media_total, 2, ',', '')), 1, 1, "R", $cor);
  $cont = 25; // Faz quebrar a pagina
  if ($quebra == 'd') {

    for ($x1 = 0; $x1 < 13; $x1++) {
      $tmes[$x1]=0;
    }
  }
}
$pdf->Output();
?>