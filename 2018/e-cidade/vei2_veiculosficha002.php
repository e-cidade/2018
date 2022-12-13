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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));

$clveiculos       = new cl_veiculos;
$clveicresp       = new cl_veicresp;
$clveicpatri      = new cl_veicpatri;
$clveicretirada   = new cl_veicretirada;
$clveicmanut      = new cl_veicmanut;
$clveicabast      = new cl_veicabast;
$clveicbaixa      = new cl_veicbaixa;
$clveiculoscomb   = new cl_veiculoscomb;
$clveicitensobrig = new cl_veicitensobrig;
$clveicutilizacao = new cl_veicutilizacao;
$clveicmanutitem  = new cl_veicmanutitem;
/*
 * Variaveis de parâmetros passadas por get 
lAbastecimento
lManutencao
lRetirada
lItens 
 */

db_postmemory($_GET);

$clveiculos->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($_SERVER['QUERY_STRING']);

$head3 = "FICHA DO VEÍCULO";

$where      = "ve01_codigo = {$veiculo}";
$descr_ve08 = "";
$descr_ve14 = "";

$sCamposVeiculos  = "distinct on (ve01_codigo) *";
$sCamposVeiculos .= ", ( select array_to_string(array_accum(distinct a.ve40_veiccadcentral||'-'||c.descrdepto),', ')";
$sCamposVeiculos .= "      from veiculos.veiccentral a ";
$sCamposVeiculos .= "           inner join veiccadcentral b on b.ve36_sequencial = a.ve40_veiccadcentral";
$sCamposVeiculos .= "           inner join db_depart c on c.coddepto = b.ve36_coddepto";
$sCamposVeiculos .= "     where a.ve40_veiculos = veiculos.ve01_codigo ) as descr_central";

$sSqlVeiculos    = $clveiculos->sql_query_veiculo(null, $sCamposVeiculos, null, $where);
$result          = $clveiculos->sql_record($sSqlVeiculos);

if ($clveiculos->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$result_resp = $clveicresp->sql_record($clveicresp->sql_query(null, "*", null, "ve02_veiculo = {$veiculo}"));

if ($clveicresp->numrows > 0) {
  db_fieldsmemory($result_resp, 0);
}

$result_patri = $clveicpatri->sql_record($clveicpatri->sql_query(null, "*", null, "ve03_veiculo = {$veiculo}"));

if ($clveicpatri->numrows > 0) {
  db_fieldsmemory($result_patri, 0);
}

$sWhereItensObrig  = "ve09_veiculos = {$veiculo} and ve10_veicitensobrig is null";
$sSqlItensObrig    = $clveicitensobrig->sql_query(null, "distinct(ve08_descr)", null, $sWhereItensObrig);
$result_itensobrig = $clveicitensobrig->sql_record($sSqlItensObrig);
$numrows           = $clveicitensobrig->numrows;

if ($clveicitensobrig->numrows > 0) {

  for ($i = 0; $i < $clveicitensobrig->numrows; $i++) {

    db_fieldsmemory($result_itensobrig, $i);

    if (($numrows - 1) != $i) {
      $descr_ve08 .= $ve08_descr . ", ";
    } else {
      $descr_ve08 .= $ve08_descr;
    }
  }
}

$sSqlUtilizacao    = $clveicutilizacao->sql_query(null, "distinct(ve14_descr)", null, "ve15_veiculos = {$veiculo}");
$result_utilizacao = $clveicutilizacao->sql_record($sSqlUtilizacao);
$numrows           = $clveicutilizacao->numrows;

if ($clveicutilizacao->numrows > 0) {

  for ($i = 0; $i < $clveicutilizacao->numrows; $i++) {

    db_fieldsmemory($result_utilizacao, $i);

    if (($numrows - 1) != $i) {
      $descr_ve14 .= $ve14_descr . ",";
    } else {
      $descr_ve14 .= $ve14_descr;
    }
  }
}

$sSqlCombustivel     = $clveiculoscomb->sql_query(null, "distinct ve06_padrao, ve26_descr", null, "ve06_veiculos = {$veiculo}");
$result_combustiveis = $clveiculoscomb->sql_record($sSqlCombustivel);

if ($clveiculoscomb->numrows > 0) {

  $virgula   = "";
  $vet_comb  = array(array("descr","padrao"));
  $cont_comb = 0;

  for($x = 0; $x < $clveiculoscomb->numrows; $x++) {

    db_fieldsmemory($result_combustiveis, $x);

    $vet_comb["descr"][$cont_comb] = $ve26_descr;
    $padrao = 0;

    if ($ve06_padrao == "t") {
      $padrao = 1;
    }

    $vet_comb["padrao"][$cont_comb] = $padrao;
    $cont_comb++;
  }

  $valor = "";
  for($x = 0; $x < $cont_comb; $x++) {

    if ($vet_comb["padrao"][$x] == 1) {

      $valor = $vet_comb["descr"][$x];
      break;
    }
  }

  $virgula = ", ";
  for($x = 0; $x < $cont_comb; $x++) {

    if ($vet_comb["padrao"][$x] == 0 && $vet_comb["descr"][$x] != "") {
      $valor .= $virgula.$vet_comb["descr"][$x];
    }

    $virgula = ", ";
  }
} else {
  $valor = "Nenhum combustível cadastrado.";
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$alt   = 4;
$total = 0;
$p     = 0;

for($x = 0; $x < $clveiculos->numrows; $x++) {

  db_fieldsmemory($result, $x);

  $pdf->addpage("L");
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Código Veiculo :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_codigo,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Placa :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_placa,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Responsável :',0,0,"R",0); 
  $pdf->setfont('arial','',7);

  if (!isset($ve02_numcgm) || $ve02_numcgm == 0){
    $z01_nome = "NENHUM";
  }

  $pdf->cell(60,$alt,@$ve02_numcgm."-".@$z01_nome,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Tipo :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadtipo."-".$ve20_descr,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Marca :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadmarca."-".$ve21_descr,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Modelo :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadmodelo."-".$ve22_descr,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Cor :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadcor."-".$ve23_descr,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Procedência :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadproced."-".$ve25_descr,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Categoria :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadcateg."-".$ve32_descr,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Nº do Chassi :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_chassi,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Renavam :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_ranavam,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Placa em Número :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_placanum,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Nº do Certificado :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_certif,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Medida Inicial :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_medidaini." ".$ve07_sigla,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Quant. Potência :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_quantpotencia,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Potência :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadpotencia."-".$ve31_descr,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Quant. Capacidade :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_quantcapacidad,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Tipo de Capacidade :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadtipocapacidade."-".$ve24_descr,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Data de Aquisição :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,db_formatar($ve01_dtaquis,'d'),0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Combustível :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$valor,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Ano de Fabricação :',0,0,"R",0); 
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_anofab,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Ano do Modelo :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_anomod,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Categoria CNH Exigida :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_veiccadcategcnh."-".$ve30_descr,0,0,"L",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Municipio :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$ve01_ceplocalidades."-".$cp05_localidades,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Central:',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$descr_central,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Utilização :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$descr_ve14,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Itens Obrigatórios :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$descr_ve08,0,1,"L",0);

  if (isset($ve03_bem) && $ve03_bem != "") {

    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Bem :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(60,$alt,$ve03_bem."-".$t52_descr,0,1,"L",0);
  } else {

    $pdf->setfont('arial','b',8);
    $pdf->cell(90,$alt,'SEM LIGAÇÃO COM O PATRIMONIO',0,1,"L",0);
  }

  $pdf->cell(0,$alt,'','T',1,"R",0);

  $sSqlBaixa    = $clveicbaixa->sql_query(null,"*",null,"ve04_veiculo=$veiculo and ve01_ativo='0'");
  $result_baixa = $clveicbaixa->sql_record($sSqlBaixa);

  if ($clveicbaixa->numrows >0) {

    db_fieldsmemory($result_baixa, 0);

    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Data da Baixa :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(60,$alt,db_formatar($ve04_data,"d"),0,0,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Hora da Baixa :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(60,$alt,$ve04_hora,0,1,"L",0);

    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Motivo :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    $pdf->multicell(0,$alt,$ve04_motivo,0,"L",0);
  } else {

    $pdf->setfont('arial','b',8);
    $pdf->cell(0,$alt,'VEICULO NÃO BAIXADO',0,1,"L",0);
  }

  $pdf->cell(0,$alt,'','T',1,"R",0);
  $pdf->setfont('arial','b',8);

  /* 
   *                                                     RETIRADAS 
   */
  if (isset($lRetirada) ) {
    
    $pdf->cell(90,$alt,'RETIRADAS :',0,1,"L",0);

    $sCamposRetirada  = "distinct ve60_codigo, ve60_datasaida, ve60_horasaida, z01_nome, ve60_medidasaida, ve60_destino";
    $sCamposRetirada .= ", ve60_destino, ve61_codigo, ve61_datadevol, ve61_horadevol, ve61_medidadevol, ve60_coddepto";
    $sCamposRetirada .= ", descrdepto";

    $sSqlRetirada = $clveicretirada->sql_query_info(null, $sCamposRetirada, "ve60_datasaida, ve60_horasaida", "ve60_veiculo = {$veiculo}");
    $result_ret   = $clveicretirada->sql_record($sSqlRetirada);
    $numrows_ret  = $clveicretirada->numrows;
    
    if ($numrows_ret > 0) {
      
      $troca     = 1;
      $p         = 0;
      $total_ret = 0;
      
      for ($w = 0; $w < $numrows_ret; $w++) {
        
        db_fieldsmemory($result_ret, $w);

        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
          
          if($troca == 0 ) {
            $pdf->addpage("L");
          }

          $pdf->setfont('arial','b',8);

          $pdf->cell(15, $alt, "Retirada",      1, 0, "C", 1);
          $pdf->cell(15, $alt, "Data",          1, 0, "C", 1);
          $pdf->cell(10, $alt, "Hora",          1, 0, "C", 1);
          $pdf->cell(55, $alt, "Motorista",     1, 0, "C", 1);
          $pdf->cell(55, $alt, "Depto Retirada",1, 0, "C", 1);
          $pdf->cell(15, $alt, "Medida",        1, 0, "C", 1);
          $pdf->cell(50, $alt, "Destino",       1, 0, "C", 1);
          $pdf->cell(15, $alt, "Devolução",     1, 0, "C", 1);
          $pdf->cell(15, $alt, "Data Dev",      1, 0, "C", 1);
          $pdf->cell(15, $alt, "Hora Dev",      1, 0, "C", 1);
          $pdf->cell(15, $alt, "Medida",        1, 1, "C", 1);

          $troca = 0;
        }

        $pdf->setfont('arial','',7);

        $pdf->cell(15, $alt, $ve60_codigo,                                    0, 0, "C", $p);
        $pdf->cell(15, $alt, db_formatar($ve60_datasaida,"d"),                0, 0, "C", $p);
        $pdf->cell(10, $alt, $ve60_horasaida,                                 0, 0, "C", $p);
        $pdf->cell(55, $alt, substr($z01_nome,0,28),                          0, 0, "L", $p);
        $pdf->cell(55, $alt, $ve60_coddepto . "-" . substr($descrdepto,0,20), 0, 0, "L", $p);
        $pdf->cell(15, $alt, $ve60_medidasaida." ".$ve07_sigla,               0, 0, "C", $p);
        $pdf->cell(50, $alt, substr($ve60_destino,0,28),                      0, 0, "L", $p);
        $pdf->cell(15, $alt, $ve61_codigo,                                    0, 0, "C", $p);
        $pdf->cell(15, $alt, db_formatar($ve61_datadevol,"d"),                0, 0, "C", $p);
        $pdf->cell(15, $alt, $ve61_horadevol,                                 0, 0, "C", $p);
        $pdf->cell(15, $alt, $ve61_medidadevol." ".$ve07_sigla,               0, 1, "C", $p);

        if ($p == 0) {
          $p = 1;
        } else {
          $p = 0;
        }

        $total_ret++;
      }
  
      $pdf->setfont('arial','b',8);
      $pdf->cell(275, $alt, "Total de Retiradas  :" . $total_ret, "T", 1, "L", 0);
    } else {
      $pdf->cell(0, $alt, "Não Existem Retiradas:", "T", 1, "L", 0);
    }
  }

  /* 
   *                                                      ABASTECIMENTOS 
   */
  if (isset($lAbastecimento) ) {

    $pdf->ln();
    $pdf->setfont('arial','b',8);
    $pdf->cell(90, $alt, 'ABASTECIMENTOS :', 0, 1, "L", 0);

    $sSqlAbastecimentos = $clveicabast->sql_query_info(null, "*", "ve70_dtabast, ve70_medida", "ve70_veiculos = {$veiculo}");
    $result_abast       = $clveicabast->sql_record($sSqlAbastecimentos);
    $numrows_abast      = $clveicabast->numrows;

    if ($numrows_abast > 0) {

      $troca          = 1;
      $p              = 0;
      $total_abast    = 0;
      $vlr_totalabast = 0;
      $quant_litros   = 0;
  
      for($w = 0; $w < $numrows_abast; $w++) {

        db_fieldsmemory($result_abast, $w);

        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

          if($troca == 0 ) {
            $pdf->addpage("L");
          }

          $pdf->setfont('arial','b',8);

          $pdf->cell(20, $alt, "Abast.",      1, 0, "C", 1);
          $pdf->cell(50, $alt, "Combustível", 1, 0, "C", 1);
          $pdf->cell(20, $alt, "Data",        1, 0, "C", 1);
          $pdf->cell(20, $alt, "Litros",      1, 0, "C", 1);
          $pdf->cell(20, $alt, "Valor Total", 1, 0, "C", 1);
          $pdf->cell(20, $alt, "Medida",      1, 0, "C", 1);
          $pdf->cell(20, $alt, "Retirada",    1, 0, "C", 1);
          $pdf->cell(20, $alt, "Anulado",     1, 1, "C", 1);

          $troca = 0;
        }
         
        $pdf->setfont('arial','',7);

        $pdf->cell(20, $alt, $ve70_codigo,                   0, 0, "C", $p);
        $pdf->cell(50, $alt, $ve26_descr,                    0, 0, "L", $p);
        $pdf->cell(20, $alt, db_formatar($ve70_dtabast,"d"), 0, 0, "C", $p);
        $pdf->cell(20, $alt, $ve70_litros,                   0, 0, "C", $p);
        $pdf->cell(20, $alt, db_formatar($ve70_valor,"f"),   0, 0, "R", $p);
        $pdf->cell(20, $alt, $ve70_medida." ".$ve07_sigla,   0, 0, "C", $p);
        $pdf->cell(20, $alt, $ve73_veicretirada,             0, 0, "C", $p);
        $pdf->cell(20, $alt, db_formatar($ve74_data,"d"),    0, 1, "C", $p);

        if($ve70_ativo == '1') {

          $quant_litros   += $ve70_litros;
          $vlr_totalabast += $ve70_valor;
        }

        if ($p == 0) {
          $p = 1;
        } else {
          $p = 0;
        }

        $total_abast++;
      }

      $pdf->cell(20, $alt, '',                               'T', 0, "C", $p);
      $pdf->cell(50, $alt, '',                               'T', 0, "L", $p);
      $pdf->cell(20, $alt, 'Total',                          'T', 0, "R", $p);
      $pdf->cell(20, $alt, $quant_litros,                    'T', 0, "C", $p);
      $pdf->cell(20, $alt, db_formatar($vlr_totalabast,"f"), 'T', 0, "R", $p);
      $pdf->cell(20, $alt, $ve70_medida ,                    'T', 0, "C", $p);
      $pdf->cell(20, $alt, '',                               'T', 0, "C", $p);
      $pdf->cell(20, $alt, '',                               'T', 1, "C", $p);
       
      $pdf->setfont('arial','b',8);
      $pdf->cell(190, $alt, "Total de Abastecimentos :" . $total_abast, "T", 1, "L", 0);
    } else {
      $pdf->cell(0, $alt, "Não Existem Abastecimentos", "T", 1, "L", 0);
    }
  }
  
  /*  
   *                                                         MANUTENÇÕES 
   */
  if (isset($lManutencao)) {  
    
    $pdf->ln();
    $pdf->setfont('arial','b',8);
    $pdf->cell(90, $alt, 'MANUTENÇÕES :', 0, 1, "L", 0);
  
    $sCamposVeicmanut = " distinct ve62_codigo, ve28_descr, ve62_dtmanut, ve62_vlrpecas, ve62_vlrmobra, ve62_medida ";
    $sOrdemVeicmanut  = " ve62_dtmanut, ve62_codigo";
    $sSqlVeicmanut    = $clveicmanut->sql_query_info(null, $sCamposVeicmanut, $sOrdemVeicmanut, "ve62_veiculos = {$veiculo}");
  
    $result_manut     = $clveicmanut->sql_record($sSqlVeicmanut);
    $numrows_manut    = $clveicmanut->numrows;
    
    $aManutencao      = array();
    
    if ($numrows_manut > 0) {

      for($w = 0; $w < $numrows_manut;$w++) {
    
        $oManutencao = db_utils::fieldsMemory($result_manut, $w);

        /* 
         *                              Se for solicitado imprimir itens da Manutenção  
         */
        if (isset($lItens)) {
           
          $sCamposManutitem = " ve63_veicmanut, ve63_descr, ve63_quant, ve63_vlruni, ve63_codigo ";
          $sWhereManutitem  = " ve62_veiculos = {$veiculo}  and ve63_veicmanut = {$oManutencao->ve62_codigo} "; 
      
          $sSqlManutitem    = $clveicmanutitem->sql_query_ItensManutencao(null,$sCamposManutitem, "", $sWhereManutitem);
          $rsManutitem      = $clveicmanutitem->sql_record($sSqlManutitem);
      
          $iNumRownsManutitem = $clveicmanutitem->numrows;
          $aItensMant         = array();

          if ($iNumRownsManutitem > 0) {

            for($i = 0; $i < $iNumRownsManutitem; $i++) {

             $oItensMant   = db_utils::fieldsMemory($rsManutitem, $i);
             $aItensMant[] = $oItensMant;
            }

            $oManutencao->lItens = true;
            $oManutencao->aItens = $aItensMant;

          } else {
            $oManutencao->lItens = false;
          }
        }

        $aManutencao[] = $oManutencao; 
      }
    }
    
    /* 
     *                                                 COMEÇA IMPRESÃO  
     */
    // -> Imprime Manutenções 
    if (count($aManutencao) > 0) {
      
      $troca           = 1;
      $p               = 0;
      $total_manut     = 0;
      $nValorPeca	     = 0;
      $nValorMaoObra	 = 0;
      $nValorTotal     = 0;
      
      for($ind = 0; $ind < count($aManutencao); $ind++) {
      
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
           
       	  if($troca == 0 ) {
       	    $pdf->addpage("L");
       	  }

          $pdf->setfont('arial','b',8);

          $pdf->cell(15, $alt, "Manut."           , 1, 0, "C", 1);
          $pdf->cell(85, $alt, "Tipo de Serviço"  , 1, 0, "C", 1);
          $pdf->cell(20, $alt, "Data"             , 1, 0, "C", 1);
          $pdf->cell(20, $alt, "Medida"           , 1, 0, "C", 1);
          $pdf->cell(25, $alt, "Vlr. Mão de Obra" , 1, 0, "C", 1);
          $pdf->cell(25, $alt, "Vlr. em Peças"    , 1, 1, "C", 1);

          $troca = 0;
        }
        
        $pdf->setfont('arial','',7);

        $pdf->cell(15, $alt, $aManutencao[$ind]->ve62_codigo                    , 0, 0, "C", $p);
        $pdf->cell(85, $alt, $aManutencao[$ind]->ve28_descr                     , 0, 0, "L", $p);
        $pdf->cell(20, $alt, db_formatar($aManutencao[$ind]->ve62_dtmanut,"d")  , 0, 0, "C", $p);
        $pdf->cell(20, $alt, $aManutencao[$ind]->ve62_medida." ".$ve07_sigla    , 0, 0, "C", $p);
        $pdf->cell(25, $alt, db_formatar($aManutencao[$ind]->ve62_vlrmobra,"f") , 0, 0, "R", $p);
        $pdf->cell(25, $alt, db_formatar($aManutencao[$ind]->ve62_vlrpecas,"f") , 0, 1, "R", $p);
        
        // -> Imprime Itens de Manutenção
        if (isset($lItens)) {
           
          if($aManutencao[$ind]->lItens == true) {
            
            $iTotalItens = 0;

            for ($iItens = 0; $iItens < count($aManutencao[$ind]->aItens); $iItens++) {
              
              if ($aManutencao[$ind]->lItens == true ) {
                
                if ($pdf->gety() > $pdf->h - 30 ) {
                  
                  $pdf->addpage("L");
                  $pdf->setfont('arial','b',8);

                  $pdf->cell(15, $alt, "Manut."           , 1, 0, "C", 1);
                  $pdf->cell(85, $alt, "Tipo de Serviço"  , 1, 0, "C", 1);
                  $pdf->cell(20, $alt, "Data"             , 1, 0, "C", 1);
                  $pdf->cell(20, $alt, "Medida"           , 1, 0, "C", 1);
                  $pdf->cell(25, $alt, "Vlr. Mão de Obra" , 1, 0, "C", 1);
                  $pdf->cell(25, $alt, "Vlr. em Peças"    , 1, 1, "C", 1);
                  
                  $pdf->setfont('arial','b',8);

                  $pdf->cell(15, $alt, ""                  , 0, 0, "C", $p);
                  $pdf->cell(15, $alt, "Item."             , 0, 0, "C", $p);
                  $pdf->cell(100,$alt, "Descrição do Item" , 0, 0, "C", $p);
                  $pdf->cell(20, $alt, "Quantidade"        , 0, 0, "C", $p);
                  $pdf->cell(40, $alt, "Valor Peça"        , 0, 1, "C", $p);
                }
                
                if ($iItens == 0) {
                  
                  $pdf->setfont('arial','b',8);

                  $pdf->cell(15,  $alt, ""                  , 0, 0, "C", $p);
                  $pdf->cell(15,  $alt, "Item."             , 0, 0, "C", $p);
                  $pdf->cell(100, $alt, "Descrição do Item" , 0, 0, "C", $p);
                  $pdf->cell(20,  $alt, "Quantidade"        , 0, 0, "C", $p);
                  $pdf->cell(40,  $alt, "Valor Peça"        , 0, 1, "C", $p);
                }
              }
              
              $pdf->setfont('arial','',7);

              $pdf->cell(15,  $alt, "",                                                                0, 0 ,"C", $p);
              $pdf->cell(15,  $alt, $aManutencao[$ind]->aItens[$iItens]->ve63_codigo                  ,0, 0 ,"C", $p);
              $pdf->cell(100, $alt, $aManutencao[$ind]->aItens[$iItens]->ve63_descr                   ,0, 0 ,"L", $p);
              $pdf->cell(20,  $alt, $aManutencao[$ind]->aItens[$iItens]->ve63_quant                   ,0, 0 ,"C", $p);
              $pdf->cell(40,  $alt, db_formatar($aManutencao[$ind]->aItens[$iItens]->ve63_vlruni,"f") ,0, 1 ,"R", $p);
              
              $iTotalItens++;
            }
          }
        }

        /* 
         *                                FIM iMPRESSÃO DOS ITENS 
         */
  
        $nValorMaoObra += $aManutencao[$ind]->ve62_vlrmobra;
        $nValorPeca    += $aManutencao[$ind]->ve62_vlrpecas;
         
        if ($p == 0) {
       	  $p = 1;
        } else {
       	  $p = 0;
        }

        $total_manut++;
      }
      
      $nValorTotal = $nValorMaoObra + $nValorPeca;
      
      $pdf->cell(15, $alt, ''                              , 'T', 0, "C", $p);
      $pdf->cell(85, $alt, ''                              , 'T', 0, "L", $p);
      $pdf->cell(20, $alt, ''                              , 'T', 0, "R", $p);
      $pdf->cell(20, $alt, ''                              , 'T', 0, "R", $p);
      $pdf->cell(25, $alt, db_formatar($nValorMaoObra,"f") , 'T', 0, "R", $p);
      $pdf->cell(25, $alt, db_formatar($nValorPeca,"f")    , 'T', 1, "R", $p);
      
      $pdf->cell(15, $alt, '', 'B', 0, "C", $p);
      $pdf->cell(85, $alt, '', 'B', 0, "L", $p);
      $pdf->cell(20, $alt, '', 'B', 0, "R", $p);
      $pdf->cell(20, $alt, '', 'B', 0, "R", $p);

      $pdf->setfont('arial','b',7);

      $pdf->cell(25, $alt, 'Total'                       , 'B', 0, "R", $p);
      $pdf->cell(25, $alt, db_formatar($nValorTotal,"f") , 'B', 1, "C", $p);
      
      $pdf->setfont('arial','b',8);

      $pdf->cell(190, $alt, "Total de Manutenções :" . $total_manut, "T", 1, "L", 0);
    } else {
      $pdf->cell( 0, $alt, "Não Existem Itens ", "T", 1, "L", 0);
    }
  }
}

$pdf->Output();