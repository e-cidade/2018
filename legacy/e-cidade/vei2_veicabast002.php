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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("classes/db_veicabast_classe.php"));
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_SERVER);

function imprimir_cabecalho($pdf,$alt){

  $pdf->AddPage("L");
  $pdf->SetFont('Arial','B',8);

  $pdf->cell(44,$alt,"Veículo",        1,0,"L",1);
  $pdf->cell(15,$alt,"Placa",          1,0,"C",1);
  $pdf->cell(15,$alt,"Ano",            1,0,"C",1);
  $pdf->cell(34,$alt,"Observações",    1,0,"L",1);
  $pdf->cell(22,$alt,"Combústivel",    1,0,"L",1);
  $pdf->cell(15,$alt,"Data",           1,0,"C",1);
  $pdf->cell(10,$alt,"Hora",           1,0,"C",1);
  $pdf->cell(22,$alt,"Medida Inicial", 1,0,"C",1);
  $pdf->cell(22,$alt,"Medida Final",   1,0,"C",1);
  $pdf->cell(22,$alt,"Medida Rodada",  1,0,"C",1);
  $pdf->cell(22,$alt,"Qtde. Comb.",    1,0,"C",1);
  $pdf->cell(22,$alt,"Valor Comb.",    1,0,"C",1);
  $pdf->cell(22,$alt,"Cons. Médio",    1,1,"C",1);
  $pdf->cell(44,$alt,"Marca/Modelo",   1,0,"C",1);
  $pdf->cell(44,$alt,"Tipo",    1,1,"C",1);

  $pdf->SetFont('Arial','',8);
}

$clveicabast = new cl_veicabast;

$periodo = "";
$quebrar = "";
$dbwhere = "";
$and     = " and ";

if (isset($ve70_dataini) && trim($ve70_dataini) != ""){
  $dbwhere .= "ve70_dtabast between '$ve70_dataini' and '$ve70_datafin' ";
  $periodo  = db_formatar($ve70_dataini,"d")." a ".db_formatar($ve70_datafin,"d");
}


$iCoddepto = null;
if ( isset($idCentral) && trim($idCentral)!=0){

  if ($dbwhere != ""){
    $dbwhere .= $and;
  }
  $dbwhere .= "ve40_veiccadcentral = {$idCentral} ";
  $sQuery 	= "SELECT coddepto
						     FROM veiccadcentral
																	   INNER JOIN db_depart ON ve36_coddepto = coddepto
  							WHERE ve36_sequencial = {$idCentral} ";
  $resQuery = db_query($sQuery);
  if(pg_num_rows($resQuery)>0){
    $rowQuery = pg_fetch_object($resQuery);
    $iCoddepto = $rowQuery->coddepto;
  }
}

if (isset($ve01_codigo) && trim($ve01_codigo)!=""){
  if ($dbwhere != ""){
    $dbwhere .= $and;
  }
  $dbwhere .= "ve01_codigo in (".$ve01_codigo.") ";
}

if (isset($ve01_veiccadtipo) && trim($ve01_veiccadtipo) != ""){

  if ($dbwhere != ""){
    $dbwhere .= $and;
  }
  $dbwhere .= "ve01_veiccadtipo=".$ve01_veiccadtipo;
}

if (isset($ve01_veiccadmarca) && trim($ve01_veiccadmarca) != ""){
  if ($dbwhere != ""){
    $dbwhere .= $and;
  }

  $dbwhere .= "ve01_veiccadmarca=".$ve01_veiccadmarca;
}

if (isset($ve01_veiccadmodelo) && trim($ve01_veiccadmodelo) != ""){
  if ($dbwhere != ""){
    $dbwhere .= $and;
  }

  $dbwhere .= "ve01_veiccadmodelo=".$ve01_veiccadmodelo;
}

if (isset($ve06_veiccadcomb) && trim($ve06_veiccadcomb) != ""){
  if ($dbwhere != ""){
    $dbwhere .= $and;
  }

  $dbwhere .= "ve06_veiccadcomb=".$ve06_veiccadcomb;
}

switch($situacao){
  case 0:
    $head6 = "Todos os Abastecimentos";
    break;
  case 1:
    if ($dbwhere != ""){
      $dbwhere .= $and;
    }
    $head6    = "Somente Ativos";
    $dbwhere .= " ve70_ativo = 1 ";
    break;
  case 2:
    if ($dbwhere != ""){
      $dbwhere .= $and;
    }
    $head6    = "Somente Anulados";
    $dbwhere .= " ve70_ativo = 0 ";
    break;
}

$cod_quebra = " ";

if (isset($quebrar_por) && trim($quebrar_por)!=""){
  if ($quebrar_por == "V"){   // Agrupar por veiculos
    $cod_quebra= "ve22_descr";
    $quebrar = "Agrupado por Veiculos";

  }

  if ($quebrar_por == "T"){   // Tipo
    $cod_quebra = "ve01_veiccadtipo";
    $quebrar = "Agrupado por Tipo de Veiculo";
  }

  if ($quebrar_por == "M"){   // Marca
    $cod_quebra = "ve01_veiccadmarca";
    $quebrar = "Agrupado por Marca";
  }

  if ($quebrar_por == "O"){   // Modelo
    $cod_quebra = "ve01_veiccadmodelo";
    $quebrar = "Agrupado por Modelo";
  }
  if ($quebrar_por == "C"){   // central
    $cod_quebra = "ve40_veiccadcentral";
    $quebrar = "Agrupado por Central de Veículo";

  }
}
if(isset($quebrar_por) && ($quebrar_por == "C")){
  $ordem = "ve40_veiccadcentral, ve70_dtabast";
} else{
  $ordem = "ve01_codigo, ve70_dtabast, ve70_codigo";
}

$sSqlBuscaAbastecimentos = $clveicabast->sql_query_abast(null,"",$ordem,$dbwhere,$iCoddepto);
//die($sSqlBuscaAbastecimentos);
$result  = $clveicabast->sql_record($sSqlBuscaAbastecimentos);
$numrows = $clveicabast->numrows;

if ($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Não foram encontrados registros para filtro(s) informado(s).");
}

$head2 = "RELATÓRIO DE ABASTECIMENTO";

if ($periodo != ""){
  $head3 = "PERIODO: ".$periodo;
}elseif($idCentral!=0){

  $sQuery = "select descrdepto from veiccadcentral inner join db_depart on ve36_coddepto = coddepto where ve36_sequencial = $idCentral";
  $resQuery = db_query($sQuery);
  if(pg_num_rows($resQuery)>0){
    $rowQuery = pg_fetch_object($resQuery);
    $head3 = "CENTRAL: ".$rowQuery->descrdepto;
  }
}

if ($quebrar!= ""){
  $head4 = $quebrar;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFillColor(235);
$pdf->lMargin = 3;
$pdf->SetFont('Arial','B',8);
//quebra de página
$cod_veiculo=0;
$cod_veiccadtipo=0;
$cod_veiccadmarca=0;
$cod_veiccadmodelo=0;
$cod_veiccadcentral=0;
$quebrapagina=false;
$total_reg=0;
$total=0;
$total_medidarodada  = 0;
$total_litros        = 0;
$total_consumo_medio = 0;
$total_geral_medidarodada = 0;
$total_geral_litros       = 0;
$total_geral_consumo_medio= 0;
$conta=0;
$mostra=false;
//
$troca = 1;
$p     = 0;
$alt   = 4;

$aCombustiveisVeiculos     = array();
$aCombustiveisTipoVeiculos = array();
//Controle de para evitar soma duplicada
$aCod_Abast 					= array();
$aCod_Abast_Impresso  = array();

for($x = 0; $x < $numrows; $x++){

  db_fieldsmemory($result,$x);
  //verifico se já existe o código do abastecimento no array se não adiciono ele.
  //  if (array_search($ve70_codigo,$aCod_Abast) === false){
  //  	$aCod_Abast[] = $ve70_codigo;
  //  }

  $medidarodadas = 0;
  $consumo_medio = 0;
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    if ($quebrar!= "" && $quebrar_por == "C"){
      $head5 = "Central: ".$descrdepto;
    }
    imprimir_cabecalho($pdf,$alt);
    $troca = 0;
  }
  $pdf->SetFont('Arial','',8);

  if ($cod_veiculo != $ve01_codigo){
    if ($cod_veiculo == "0"){
      $mostra=false;
    }else{
      $mostra=true;
    }
  }
  //Este if mais externo verifica se o abastecimento ja existe ai não entra.
  if (array_search($ve70_codigo,$aCod_Abast) === false){
    $aCod_Abast[] = $ve70_codigo;
    if (array_key_exists($ve01_codigo,$aCombustiveisVeiculos) && array_key_exists($ve26_descr,$aCombustiveisVeiculos[$ve01_codigo])) {
      $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_combustivel'] += $ve70_litros;
      $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_valor']       += $ve70_valor;
      $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_kmrodados']   += $medida_rodada;
      if($ve70_litros > 0 ) {
        $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_consumo_med'] += ( $medida_rodada/$ve70_litros );
      }else{
        $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_consumo_med'] += 0;
      }
    }else{
      $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_combustivel'] = $ve70_litros;
      $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_valor']       = $ve70_valor;
      $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_kmrodados']   = $medida_rodada;
      if($ve70_litros > 0 ) {
        $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_consumo_med'] = ( $medida_rodada/$ve70_litros );
      }else{
        $aCombustiveisVeiculos[$ve01_codigo][$ve26_descr]['total_consumo_med'] = 0;
      }

    }
    if(    array_key_exists($ve01_veiccadtipo,$aCombustiveisTipoVeiculos)
      && array_key_exists($ve20_descr,      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo])
      && array_key_exists($ve26_descr,      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr] ) ) {
      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_combustivel'] 	+= $ve70_litros;
      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_valor'] 				+= $ve70_valor;
      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_kmrodados']   	+= $medida_rodada;
      if($ve70_litros > 0 ) {
        $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_consumo_med'] += ( $medida_rodada/$ve70_litros );
      }else{
        $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_consumo_med'] += 0;
      }
    }else{
      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_combustivel'] = $ve70_litros;
      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_valor'] = $ve70_valor;
      $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_kmrodados']   = $medida_rodada;
      if($ve70_litros > 0 ) {
        $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_consumo_med'] = ( $medida_rodada/$ve70_litros );
      }else{
        $aCombustiveisTipoVeiculos[$ve01_veiccadtipo][$ve20_descr][$ve26_descr]['total_consumo_med'] = 0;
      }
    }
  }


  //quebra de página
  if (isset($quebrar_por)){

    //por veiculo
    if ($quebrar_por == "V"){
      if ($cod_veiculo != $ve01_codigo){
        if ($cod_veiculo == "0"){
          $quebrapagina=false;
        }else{
          $quebrapagina=true;
        }
      }else{
        $quebrapagina=false;
      }
    }

    //por tipo

    if ($quebrar_por == "T"){
      if ($cod_veiccadtipo != $ve01_veiccadtipo){
        if ($cod_veiccadtipo == "0"){
          $quebrapagina=false;
        }else{
          $quebrapagina=true;
        }
      }else{
        $quebrapagina=false;
      }

    }

    //por marca

    if ($quebrar_por == "M"){
      if ($cod_veiccadmarca != $ve01_veiccadmarca){
        if ($cod_veiccadmarca == "0"){
          $quebrapagina=false;
        }else{
          $quebrapagina=true;
        }
      }else{
        $quebrapagina=false;
      }
    }

    //por modelo
    if ($quebrar_por == "O"){
      if ($cod_veiccadmodelo != $ve01_veiccadmodelo){
        if ($cod_veiccadmodelo == "0"){
          $quebrapagina=false;
        }else{
          $quebrapagina=true;
        }
      }else{
        $quebrapagina=false;
      }
    }
    //por central
    if ($quebrar_por == "C"){

      if ($cod_veiccadcentral != $ve40_veiccadcentral){
        if ($cod_veiccadcentral == "0"){
          $quebrapagina=false;
        }else{
          $quebrapagina=true;
        }
      }else{
        $quebrapagina=false;
      }
      $cod_veiccadcentral = $ve40_veiccadcentral;
    }

    //quebra página
    if ($quebrapagina==true){
      $quebrapagina==false;
      $naosoma=true;
      $total_geral_comb=0;
      $total_geral_valor=0;
      $total_geral_km=0;
      $total_geral_consumo=0;
      $pdf->cell(287,$alt,"",0,1,"L",$p);
      $imprime="TOTAL DO VEÍCULO";
      foreach ($aCombustiveisVeiculos[$cod_veiculo] as $chave => $valor) {
        $total_geral_comb    += $valor['total_combustivel'];
        $total_geral_valor    += $valor['total_valor'];
        $total_geral_km      += $valor['total_kmrodados'];
        $total_geral_consumo += $valor['total_consumo_med'];

        $pdf->cell(44,$alt,"$imprime",0,0,"C",$p);
        $pdf->cell(42,$alt,"",0,0,"R",$p);
        $pdf->cell(22,$alt,"",0,0,"R",$p);
        $pdf->cell(22,$alt,"$chave",0,0,"R",$p);
        $pdf->cell(25,$alt,"",0,0,"R",$p);
        $pdf->cell(22,$alt,"",0,0,"R",$p);
        $pdf->cell(22,$alt,"",0,0,"R",$p);

        if ($tipoabastecimento == 2){
          if($valor['total_kmrodados']==0){
            $valor['total_consumo_med'] = 0;
          }else{
            $valor['total_consumo_med'] = $valor['total_combustivel'] / $valor['total_kmrodados'];
          }
        }else{
          if($valor['total_combustivel']==0){
            $valor['total_consumo_med'] = 0;
          }else{
            $valor['total_consumo_med'] =  $valor['total_kmrodados'] / $valor['total_combustivel'] ;
          }
        }

        $pdf->cell(22,$alt,db_formatar($valor['total_kmrodados'],"f")." ".$ve07_sigla,0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($valor['total_combustivel'],"f"),0,0,"R",$p);
        //Total Valor
        $pdf->cell(22,$alt,db_formatar($valor['total_valor'],"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($valor['total_consumo_med'],"f"),0,1,"R",$p);
        $imprime="";
      }

      if ($tipoabastecimento == 2){
        if ($total_geral_km > 0) {
          $total_geral_consumo = $total_geral_comb / $total_geral_km;
        }
      }else{
        if ($total_geral_comb > 0) {
          $total_geral_consumo = $total_geral_km / $total_geral_comb ;
        }
      }
      $pdf->cell(199,$alt,"",0,0,"R",$p);
      $pdf->cell(22,$alt,db_formatar($total_geral_km,"f")." ".$ve07_sigla,0,0,"R",$p);
      $pdf->cell(22,$alt,db_formatar($total_geral_comb,"f"),0,0,"R",$p);
      //Total Valor
      $pdf->cell(22,$alt,db_formatar($total_geral_valor,"f"),0,0,"R",$p);
      $pdf->cell(22,$alt,db_formatar($total_geral_consumo,"f"),0,1,"R",$p);

      $total_medidarodada  = 0;
      $total_litros        = 0;
      $total_consumo_medio = 0;
      $total_reg=0;

      if ($quebrar!= "" && $quebrar_por == "C"){
        $head5 = "Central: ".$descrdepto;
      }

      imprimir_cabecalho($pdf,$alt);
    }

  }


  //geral (todos veiculos)
  if ($mostra==true){

    $pdf->cell(287,$alt,"",0,1,"L",$p);
    $mostra==false;
    $imprime="TOTAL DO VEÍCULO";
    $total_geral_comb=0;
    $total_geral_valor=0;
    $total_geral_km=0;
    $total_geral_consumo=0;
    foreach ($aCombustiveisVeiculos[$cod_veiculo] as $chave => $valor) {

      $total_geral_comb    += $valor['total_combustivel'];
      $total_geral_valor   += $valor['total_valor'];
      $total_geral_km      += $valor['total_kmrodados'];
      $total_geral_consumo += $valor['total_consumo_med'];

      $pdf->cell(44,$alt,"$imprime",0,0,"R",$p);
      $pdf->cell(42,$alt,"",0,0,"R",$p);
      $pdf->cell(22,$alt,"",0,0,"R",$p);
      $pdf->cell(22,$alt,"$chave",0,0,"L",$p);
      $pdf->cell(25,$alt,"",0,0,"R",$p);
      $pdf->cell(22,$alt,"",0,0,"R",$p);
      $pdf->cell(22,$alt,"",0,0,"R",$p);

      if ($tipoabastecimento == 2){
        if ($valor['total_kmrodados'] > 0){
          $valor['total_consumo_med'] = $valor['total_combustivel'] / $valor['total_kmrodados'];
        }
      }else{
        if ($valor['total_combustivel'] > 0){
          $valor['total_consumo_med'] =  $valor['total_kmrodados'] / $valor['total_combustivel'] ;
        }
      }


      //$pdf->cell(22,$alt,db_formatar($valor['total_kmrodados'],"f")." ".$ve07_sigla,0,0,"R",$p);
      $pdf->cell(22,$alt,db_formatar($valor['total_kmrodados'],"f")." ".$tiposigla,0,0,"R",$p);
      $pdf->cell(22,$alt,db_formatar($valor['total_combustivel'],"f"),0,0,"R",$p);
      //Total Valor
      $pdf->cell(22,$alt,db_formatar($valor['total_valor'],"f"),0,0,"R",$p);
      $pdf->cell(22,$alt,db_formatar($valor['total_consumo_med'],"f"),0,1,"R",$p);
      $imprime="";

    }
    $pdf->cell(199,$alt,"",0,0,"R",$p);

    if ($tipoabastecimento == 2){
      if($total_geral_km > 0){
        $total_geral_consumo = $total_geral_comb / $total_geral_km;
      }
    }else{
      if($total_geral_comb > 0){
        $total_geral_consumo = $total_geral_km / $total_geral_comb ;
      }
    }

    $pdf->cell(22,$alt,db_formatar($total_geral_km,"f")." ".$tiposigla,0,0,"R",$p);
    $pdf->cell(22,$alt,db_formatar($total_geral_comb,"f"),0,0,"R",$p);
    $pdf->cell(22,$alt,db_formatar($total_geral_valor,"f"),0,0,"R",$p);
    $pdf->cell(22,$alt,db_formatar($total_geral_consumo,"f"),0,1,"R",$p);


  }
  if ($ve01_veictipoabast == 2){
    if ($medida_rodada > 0 ){
      $consumo_medio = $ve70_litros/$medida_rodada;
    }
  }else{
    if($ve70_litros > 0 ) {
      $consumo_medio = $medida_rodada/$ve70_litros;
    }
  }
  //if($quebrar_por == "C")
  //Este if mais externo verifica se o abastecimento ja existe ai não entra.
  if (array_search($ve70_codigo,$aCod_Abast_Impresso) === false || isset($quebrar_por) == "C"){
    $aCod_Abast_Impresso[] = $ve70_codigo;


    $ve60_destino = substr($ve60_destino, 0, 17);

    if ($cod_veiculo != $ve01_codigo){
      if($listar_por==0){
        $pdf->cell(287,$alt,"",0,1,"L",1);
        $pdf->cell(44,$alt,substr($ve21_descr,0,30),0,0,"L",$p);
        $pdf->cell(15,$alt,$ve01_placa,0,0,"R",$p);
        $pdf->cell(15,$alt,$ve01_anofab,0,0,"R",$p);
        $pdf->cell(34,$alt,$ve60_destino, 0,0,"L",$p);
        $pdf->cell(22,$alt,substr($ve26_descr,0,10),0,0,"L",$p);
        $pdf->cell(15,$alt,db_formatar($ve70_dtabast,"d"),0,0,"R",$p);
        $pdf->cell(10,$alt,$ve70_hora,0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($medida_retirada,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($medida_devolucao,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($medida_rodada,"f")." ".$ve07_sigla,0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($ve70_litros,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($ve70_valor,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($consumo_medio,"f"),0,1,"R",$p);
      }else if($listar_por==1){
        //$pdf->cell(287,$alt,"",0,1,"L",1);
        $pdf->cell(44,$alt,substr($ve21_descr,0,30),0,0,"L",$p);
        $pdf->cell(15,$alt,$ve01_placa,0,0,"R",$p);
        $pdf->cell(15,$alt,$ve01_anofab,0,0,"R",$p);
        $pdf->cell(15,$alt,"",0,1,"R",$p);

      }
      $pdf->cell(55,$alt,substr($ve22_descr,0,30),0,0,"L",$p);
      $pdf->cell(55,$alt,substr($ve20_descr,0,30),0,1,"L",$p);


    }
    else {
      if($listar_por==0){
        $pdf->cell(74,$alt,"",0,0,"L",$p);
        $pdf->cell(34,$alt,substr($ve60_destino,0,25),0,0,"L",$p);
        $pdf->cell(22,$alt,substr($ve26_descr,0,10),0,0,"L",$p);
        $pdf->cell(15,$alt,db_formatar($ve70_dtabast,"d"),0,0,"R",$p);
        $pdf->cell(10,$alt,$ve70_hora,0,0,"C",$p);
        $pdf->cell(22,$alt,db_formatar($medida_retirada,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($medida_devolucao,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($medida_rodada,"f")." ".$ve07_sigla,0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($ve70_litros,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($ve70_valor,"f"),0,0,"R",$p);
        $pdf->cell(22,$alt,db_formatar($consumo_medio,"f"),0,1,"R",$p);
      }
    }
  }
  $mostra=false;
  $total_medidarodada += $medida_rodada;
  $total_litros       += $ve70_litros;
  $total_consumo_medio+= $consumo_medio;

  $total_geral_medidarodada += $medida_rodada;
  $total_geral_litros       += $ve70_litros;
  $total_geral_consumo_medio+= $consumo_medio;

  $cod_veiculo       = $ve01_codigo;
  $cod_veiccadtipo   = $ve01_veiccadtipo;
  $cod_veiccadmarca  = $ve01_veiccadmarca;
  $cod_veiccadmodelo = $ve01_veiccadmodelo;

  $tiposigla = $ve07_sigla;
  $tipoabastecimento = $ve01_veictipoabast;
  $total_reg++;
  $total++;

}


$pdf->cell(287,$alt,"",0,1,"L",$p);
$imprime	=	"TOTAL DO VEÍCULO";
$total_geral_comb			=	0;
$total_geral_valor		=	0;
$total_geral_km				=	0;
$total_geral_consumo	=	0;
foreach ($aCombustiveisVeiculos[$ve01_codigo] as $chave => $valor) {
  $total_geral_comb    += $valor['total_combustivel'];
  $total_geral_valor   += $valor['total_valor'];
  $total_geral_km      += $valor['total_kmrodados'];
  $total_geral_consumo += $valor['total_consumo_med'];

  if ($tipoabastecimento == 2) {

    if ($valor['total_kmrodados'] > 0) {
      $valor['total_consumo_med'] = $valor['total_combustivel'] / $valor['total_kmrodados'];
    }

  } else {

    if ($valor['total_combustivel'] > 0) {
      $valor['total_consumo_med'] =  $valor['total_kmrodados'] / $valor['total_combustivel'] ;
    }

  }

  $pdf->cell(44,$alt,"$imprime",0,0,"R",$p);
  $pdf->cell(42,$alt,"",0,0,"R",$p);
  $pdf->cell(22,$alt,"",0,0,"R",$p);
  $pdf->cell(22,$alt,"$chave ",0,0,"L",$p);
  $pdf->cell(25,$alt,"",0,0,"R",$p);
  $pdf->cell(22,$alt,"",0,0,"R",$p);
  $pdf->cell(22,$alt,"",0,0,"R",$p);
  $pdf->cell(22,$alt,db_formatar($valor['total_kmrodados'],"f")." ".$ve07_sigla,0,0,"R",$p);
  $pdf->cell(22,$alt,db_formatar($valor['total_combustivel'],"f"),0,0,"R",$p);
  //Total Valor
  $pdf->cell(22,$alt,db_formatar($valor['total_valor'],"f"),0,0,"R",$p);
  $pdf->cell(22,$alt,db_formatar($valor['total_consumo_med'],"f"),0,1,"R",$p);

  $imprime="";

}
if ($tipoabastecimento == 2){
  if ($total_geral_km > 0 ){
    $total_geral_consumo = $total_geral_comb/$total_geral_km ;
  }
}else{
  if ($total_geral_comb > 0) {
    $total_geral_consumo = $total_geral_km / $total_geral_comb;
  }
}

$pdf->cell(199,$alt,"",0,0,"R",$p);
$pdf->cell(22,$alt,db_formatar($total_geral_km,"f")." ".$ve07_sigla,0,0,"R",$p);
$pdf->cell(22,$alt,db_formatar($total_geral_comb,"f"),0,0,"R",$p);
//Total geral valor
$pdf->cell(22,$alt,db_formatar($total_geral_valor,"f"),0,0,"R",$p);
$pdf->cell(22,$alt,db_formatar($total_geral_consumo,"f"),0,1,"R",$p);
$pdf->cell(287,$alt,"",0,1,"L",1);

$imprime="COMBUSTÍVEL QUANT./VALORES TOTAIS";
$pdf->cell(287,$alt,"",0,1,"L",0);
$pdf->cell(60,$alt,"$imprime",0,1,"L",1);
$pdf->cell(60,$alt,"Combustível",0,0,"C",0);
$pdf->cell(45,$alt,"Quantidade",0,0,"C",0);
$pdf->cell(40,$alt,"Valores",0,1,"C",0);

$aTotaisPorComb = array();


foreach ($aCombustiveisTipoVeiculos as $chave => $valor){

  foreach ($valor as $chave1 => $valor1){

    foreach ($valor1 as $chave2 => $valor2){

      //var_dump($chave1);exit;
      if (isset($aTotaisPorComb[$chave2]['total_combustivel'])) {
        $aTotaisPorComb[$chave2]['total_combustivel'] += $valor2['total_combustivel'];
        $aTotaisPorComb[$chave2]['total_valor']       += $valor2['total_valor'];
      }else{
        $aTotaisPorComb[$chave2]['total_combustivel'] = 0;
        $aTotaisPorComb[$chave2]['total_valor']       = 0;
        $aTotaisPorComb[$chave2]['total_combustivel'] += $valor2['total_combustivel'];
        $aTotaisPorComb[$chave2]['total_valor']       += $valor2['total_valor'];
      }

    }
  }
}

$fTotalValor = 0;
$fTotalCombustivel = 0;
foreach ($aTotaisPorComb as $key=>$value){
  $pdf->cell(70,$alt,"",0,1,"R",0);
  $pdf->cell(60,$alt,$key,0,0,"L",1);
  $pdf->cell(45,$alt,db_formatar($value['total_combustivel'],'f'),0,0,"C",0);
  $pdf->cell(40,$alt,db_formatar($value['total_valor'],'f'),0,1,"C",0);

  $fTotalCombustivel += $value['total_combustivel'];
  $fTotalValor += $value['total_valor'];
}
$pdf->cell(70,$alt,"",0,1,"R",0);
$pdf->cell(60,$alt,"TOTAL",0,0,"L",1);
$pdf->cell(45,$alt,db_formatar($fTotalCombustivel,'f'),0,0,"C",0);
$pdf->cell(40,$alt,db_formatar($fTotalValor,'f'),0,1,"C",0);

$pdf->cell(287,$alt,"",0,1,"L",0);

$pdf->cell(287,$alt,"",0,1,"L",1);

$imprime="MÉDIA GERAL POR TIPO DE VEÍCULO";
$total_tipo_geral_comb    = 0;
$total_tipo_geral_km      = 0;
$total_tipo_geral_consumo = 0;
$nomeComb="";
$nomeTipo="";
$pdf->cell(287,$alt,"",0,1,"L",0);
$pdf->cell(55,$alt,"$imprime",0,1,"R",1);
$pdf->cell(60,$alt,"Tipo de veículo",0,0,"C",0);
$pdf->cell(45,$alt,"Combústivel",0,0,"C",0);
$pdf->cell(40,$alt,"Comsumo médio",0,1,"C",0);

foreach ($aCombustiveisTipoVeiculos as $chave => $valor){

  foreach ($valor as $chaveNome => $valorNome){

    $nomeTipo=$chaveNome;
    $pdf->cell(60,$alt,"$nomeTipo",0,1,"L",1);

    foreach ($valorNome as $chaveComb => $valorComb){
      $nomeComb=$chaveComb;
      $pdf->cell(75,$alt,"",0,0,"R",0);
      $pdf->cell(30,$alt,"$nomeComb",0,0,"L",0);
      $total_tipo_geral_comb    = $valorComb['total_combustivel'];
      $total_tipo_geral_km      = $valorComb['total_kmrodados'];

      if ($total_tipo_geral_comb > 0) {
        $total_tipo_geral_comb=($total_tipo_geral_km/$total_tipo_geral_comb);
      }

      $pdf->cell(25,$alt,db_formatar($total_tipo_geral_comb ,"f"),0,1,"R",$p);
      $imprime="";

    }
  }
}



$pdf->Output();
?>