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


if (! isset($arqinclude)) {
  
  include ("fpdf151/pdf.php");
  include ("fpdf151/assinatura.php");
  include ("libs/db_sql.php");
  include ("libs/db_libcontabilidade.php");
  include ("libs/db_liborcamento.php");
  include ("classes/db_orcparamrel_classe.php");
  include ("classes/db_conrelinfo_classe.php");
  include ("classes/db_db_config_classe.php");
  include ("dbforms/db_funcoes.php");
  include_once("classes/db_orcparamelemento_classe.php");
  require_once("libs/db_utils.php");
  $classinatura = new cl_assinatura();
  $orcparamrel = new cl_orcparamrel();
  $clconrelinfo = new cl_conrelinfo();
  $cldb_config = new cl_db_config();
  $clorcparamelemento = new cl_orcparamelemento();
  
  parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

}
$sTodasInstit = null;
$limite = 0;

$valor_insuficiencia1 = 0;
$valor_outras_obrigacoes1 = 0;
$valor_obrigacoes_nao_dc_prev1 = 0;

$valor_insuficiencia2 = 0;
$valor_outras_obrigacoes2 = 0;
$valor_obrigacoes_nao_dc_prev2 = 0;

$valor_insuficiencia3 = 0;
$valor_outras_obrigacoes3 = 0;
$valor_obrigacoes_nao_dc_prev3 = 0;

$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(29, str_replace('-', ',', $db_selinstit)));
if ($clconrelinfo->numrows > 0) {
  for($x = 0; $x < $clconrelinfo->numrows; $x ++) {
    db_fieldsmemory($res, $x);
    if ($c83_codigo == 360) {
      $limite = $c83_informacao;
    }
  }
}

$dbwhere = "";
if ($periodo == "3Q") {
  $dbwhere = "'1Q','2Q','3Q'";
}

if (trim($dbwhere) == "") {
  $dbwhere = $periodo;
}

if ($periodo == "3Q") {
  $sql_valores = $clconrelinfo->sql_query_valores2(29, str_replace("-", ",", $db_selinstit), $dbwhere);
} else {
  $sql_valores = $clconrelinfo->sql_query_valores(29, str_replace("-", ",", $db_selinstit), $dbwhere);
}

$res = $clconrelinfo->sql_record($sql_valores);
if ($clconrelinfo->numrows > 0) {
  for($x = 0; $x < $clconrelinfo->numrows; $x ++) {
    db_fieldsmemory($res, $x);
    if ($c83_codigo == 361) {
      if (! isset($c83_periodo)) {
        if ($periodo == "1Q") {
          $valor_insuficiencia1 = $c83_informacao;
        }
        
        if ($periodo == "2Q" || $periodo == "1S") {
          $valor_insuficiencia2 = $c83_informacao;
        }
        
        if ($periodo == "3Q" || $periodo == "2S") {
          $valor_insuficiencia3 = $c83_informacao;
        }
      } else {
        if ($c83_periodo == "1Q") {
          $valor_insuficiencia1 = $c83_informacao;
        }
        
        if ($c83_periodo == "2Q" || $c83_periodo == "1S") {
          $valor_insuficiencia2 = $c83_informacao;
        }
        
        if ($c83_periodo == "3Q" || $c83_periodo == "2S") {
          $valor_insuficiencia3 = $c83_informacao;
        }
      }
    }
    
    if ($c83_codigo == 362) {
      if (! isset($c83_periodo)) {
        if ($periodo == "1Q") {
          $valor_outras_obrigacoes1 = $c83_informacao;
        }
        
        if ($periodo == "2Q" || $periodo == "1S") {
          $valor_outras_obrigacoes2 = $c83_informacao;
        }
        
        if ($periodo == "3Q" || $periodo == "2S") {
          $valor_outras_obrigacoes3 = $c83_informacao;
        }
      } else {
        if ($c83_periodo == "1Q") {
          $valor_outras_obrigacoes1 = $c83_informacao;
        }
        
        if ($c83_periodo == "2Q" || $c83_periodo == "1S") {
          $valor_outras_obrigacoes2 = $c83_informacao;
        }
        
        if ($c83_periodo == "3Q" || $c83_periodo == "2S") {
          $valor_outras_obrigacoes3 = $c83_informacao;
        }
      }
    }
    
    if ($c83_codigo == 363) {
      if (! isset($c83_periodo)) {
        if ($periodo == "1Q") {
          $valor_obrigacoes_nao_dc_prev1 = $c83_informacao;
        }
        
        if ($periodo == "2Q" || $periodo == "1S") {
          $valor_obrigacoes_nao_dc_prev2 = $c83_informacao;
        }
        
        if ($periodo == "3Q" || $periodo == "2S") {
          $valor_obrigacoes_nao_dc_prev3 = $c83_informacao;
        }
      } else {
        if ($c83_periodo == "1Q") {
          $valor_obrigacoes_nao_dc_prev1 = $c83_informacao;
        }
        
        if ($c83_periodo == "2Q" || $c83_periodo == "1S") {
          $valor_obrigacoes_nao_dc_prev2 = $c83_informacao;
        }
        
        if ($c83_periodo == "3Q" || $c83_periodo == "2S") {
          $valor_obrigacoes_nao_dc_prev3 = $c83_informacao;
        }
      }
    }
  }
}

$sSelInstit   = str_replace('-', ', ', $db_selinstit);
$anousu       = db_getsession("DB_anousu");
$xinstit      = split("-", $db_selinstit);

$valor_outras_obrigacoes_ex_anterior           = $clconrelinfo->getValorVariavel(475, $sSelInstit,'1Q');
$valor_obrigacoes_nao_integ_dcprev_ex_anterior = $clconrelinfo->getValorVariavel(476, $sSelInstit,'1Q');

$resultinst = pg_exec("select codigo,munic,db21_tipoinstit from db_config where codigo in (" . str_replace('-', ', ', $db_selinstit) . ") ");
$numrowsinstit = pg_num_rows($resultinst);

$instit_rpps = "";
$instituicao = "";
$virg_rpps   = "";
$virgula     = "";

for($x = 0; $x < $numrowsinstit; $x ++) {
  db_fieldsmemory($resultinst, $x);
  
  if ($db21_tipoinstit == 5 || $db21_tipoinstit == 6) { // RPPS
    $instit_rpps .= $virg_rpps . $codigo;
    $virg_rpps = ",";
  } else {
    $instituicao .= $virgula . $codigo;
    $virgula = ",";
  }
}
$head2 = "MUNICÍPIO DE " . strtoupper($munic);
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DA DIVIDA CONSOLIDADA LIQUIDA";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

// verifica se foi informada datas iniciais e finais
$usa_datas = false;
if (strlen($dtini) > 5 && strlen($dtfin) > 5) {
  $usa_datas = true;
}

$dt = data_periodo($anousu, $periodo);
$dt_ini = split("-", $dt [0]);
$dt_fin = split("-", $dt [1]);

$period = strtoupper(db_mes("01")) . " A " . strtoupper(db_mes($dt_fin [1])) . " DE " . $anousu;

if ($usa_datas == false) {
  $head6 = $period;
} else {
  $head6 = db_formatar($dtini, 'd') . " à " . db_formatar($dtfin, 'd');
}

// datas fixas para os quadrimestres
$anousu_ant = ($anousu) - 1;
$dtini_ant = '';
$dtfin_ant = $anousu_ant . "-12-31";

if ($periodo == "1S") {
  
  $dtini_01 = $anousu . '-01-01';
  $dtfin_01 = $anousu . '-06-30';
  $dtini_02 = $anousu . '-07-01';
  $dtfin_02 = $anousu . '-12-31';

} else if ($periodo == "2S") {
  
  $dtini_01 = $anousu . '-01-01';
  $dtfin_01 = $anousu . '-06-30';
  $dtini_02 = $anousu . '-07-01';
  $dtfin_02 = $anousu . '-12-31';

} else {
  
  $dtini_01 = $anousu . '-01-01';
  $dtfin_01 = $anousu . '-04-30';
  $dtini_02 = $anousu . '-09-01';
  $dtfin_02 = $anousu . '-12-31';

}

if ($usa_datas == true) {
  $dtini_01 = $dtini;
  $dtfin_01 = $dtfin;
}

if ($usa_datas == true) {
  $dt = split('-', $dtfin); // mktime -- (mes,dia,ano)
  $dtini_ant = date('Y-m-d', mktime(0, 0, 0, $dt [1], $dt [2] - 364, $dt [0]));
}

//************************
// paramentos do relatório
//************************
//iInstituicao = str_replace("-", ",", $db_selinstit);


$parametro [1] ['estrut']  = '';
$parametro [2] ['estrut']  = '';
$parametro [3] ['estrut']  = '';
$parametro [4] ['estrut']  = '';
$parametro [5] ['estrut']  = '';
$parametro [6] ['estrut']  = '';
$parametro [7] ['estrut']  = '';
$parametro [8] ['estrut']  = '';
$parametro [9] ['estrut']  = '';
$parametro [10] ['estrut'] = '';
$parametro [11] ['estrut'] = '';
$parametro [12] ['estrut'] = '';
$parametro [13] ['estrut'] = '';
$parametro [14] ['estrut'] = '';
if ($instituicao != '') {
  
  $parametro [1] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '1', "f", $instituicao, $anousu);
  $parametro [2] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '2', "f", $instituicao, $anousu);
  $parametro [3] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '3', "f", $instituicao, $anousu);
  $parametro [4] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '4', "f", $instituicao, $anousu);
  $parametro [5] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '5', "f", $instituicao, $anousu);
  $parametro [6] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '6', "f", $instituicao, $anousu);
  $parametro [7] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '7', "f", $instituicao, $anousu);
  $parametro [8] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '8', "f", $instituicao, $anousu);
  $parametro [9] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '9', "f", $instituicao, $anousu);
  $parametro [10] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '10', "f", $instituicao, $anousu);
  $parametro [11] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '11', "f", $instituicao, $anousu);
  $parametro [12] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '12', "f", $instituicao, $anousu);
  $parametro [13] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '13', "f", $instituicao, $anousu);
  $parametro [14] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '14', "f", $instituicao, $anousu);
  $aOrcParametro = array_merge(
                               (array)$parametro [1] ["estrut"],
                               (array)$parametro [2] ["estrut"],
                               (array)$parametro [3] ["estrut"], 
                               (array)$parametro [4] ["estrut"], 
                               (array)$parametro [5] ["estrut"], 
                               (array)$parametro [6] ["estrut"], 
                               (array)$parametro [7] ["estrut"], 
                               (array)$parametro [8] ["estrut"], 
                               (array)$parametro [9] ["estrut"], 
                               (array)$parametro [10] ["estrut"], 
                               (array)$parametro [11] ["estrut"], 
                               (array)$parametro [12] ["estrut"], 
                               (array)$parametro [13] ["estrut"], 
                               (array)$parametro [14] ["estrut"]
                              );

}

if (trim($instit_rpps) != "") {
  
  $parametro [15] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '15', "f", $instit_rpps, $anousu);
  $parametro [16] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '16', "f", $instit_rpps, $anousu);
  $parametro [17] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '17', "f", $instit_rpps, $anousu);
  $parametro [18] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '18', "f", $instit_rpps, $anousu);
  $parametro [19] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '19', "f", $instit_rpps, $anousu);
  $parametro [20] ['estrut'] = $orcparamrel->sql_parametro_instit('29', '20', "f", $instit_rpps, $anousu);

}



if (trim($instit_rpps) != "") {
  $aOrcParametro_rpps = array_merge(
                                    $parametro [15] ["estrut"], 
                                    $parametro [16] ["estrut"], 
                                    $parametro [17] ["estrut"], 
                                    $parametro [18] ["estrut"], 
                                    $parametro [19] ["estrut"], 
                                    $parametro [20] ["estrut"]
                                   );
}

for($linha = 1; $linha <= 20; $linha ++) {
  $parametro [$linha] ['ano']   = 0;
  $parametro [$linha] ['quad1'] = 0;
  $parametro [$linha] ['quad2'] = 0;
  $parametro [$linha] ['quad3'] = 0;
}
if ($instituicao != '') {
  $sele_work = "c61_instit in ({$instituicao})";
  $result_01 = db_planocontassaldo_matriz($anousu, $dtini_01, $dtfin_01, false, $sele_work, "", "true", "false", "", $aOrcParametro);
  @pg_exec("drop table work_pl");
  
  //$sele_work = ' c61_instit in (' . $instituicao . ')';
  $result_02 = db_planocontassaldo_matriz($anousu, $dtini_02, $dtfin_02, false, $sele_work, "", "true", "false", "", $aOrcParametro);
  @pg_exec("drop table work_pl");
}
if (trim($instit_rpps) != "") {
  $sele_work = ' c61_instit in (' . $instit_rpps . ')';
  $result_01_rpps = db_planocontassaldo_matriz($anousu, $dtini_01, $dtfin_01, false, $sele_work, "", "true", "false", "", $aOrcParametro_rpps);
  @pg_exec("drop table work_pl");
  
  $sele_work = ' c61_instit in (' . $instit_rpps . ')';
  $result_02_rpps = db_planocontassaldo_matriz($anousu, $dtini_02, $dtfin_02, false, $sele_work, "", "true", "false", "", $aOrcParametro_rpps);
  @pg_exec("drop table work_pl");
}

//db_criatabela($result_02_rpps); exit;


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//************************
//definição de variaveis
//**********************-*
$texto [1] ['txt']  = "DIVÍDA CONSOLIDADA - DC (I)";
$texto [2] ['txt']  = "  Dívida Mobiliária";
$texto [3] ['txt']  = "  Dívida Contratual";
$texto [4] ['txt']  = "    Dívida Contratual de PPP";
$texto [5] ['txt']  = "    Demais Dívidas Contratuais";
$texto [6] ['txt']  = "  Precatórios posteriores a 05/05/2000 (inclusive)";
$texto [7] ['txt']  = "  Operações de Crédito inferiores a 12 meses";
$texto [8] ['txt']  = "  Parcelamento de Dívidas";
$texto [9] ['txt']  = "    De Tributos";
$texto [10] ['txt'] = "    De Contribuições Sociais";
$texto [11] ['txt'] = "      Previdenciárias";
$texto [12] ['txt'] = "      Demais Contribuições Sociais";
$texto [13] ['txt'] = "    Do FGTS";
$texto [14] ['txt'] = "  Outras Dívidas";
$texto [15] ['txt'] = "DEDUÇÕES(II)";
$texto [16] ['txt'] = "  Ativo Disponível";
$texto [17] ['txt'] = "  Haveres Financeiros";
$texto [18] ['txt'] = "  (-) Restos a Pagar Processados";
$texto [19] ['txt'] = "OBRIGAÇÕES NÃO INTEGRANTES DA DC";
$texto [20] ['txt'] = "  Precatórios anteriores a 05/05/2000";
$texto [21] ['txt'] = "  Insuficiência Financeira";
$texto [22] ['txt'] = "  Outras Obrigações";
$texto [23] ['txt'] = "DÍVIDA CONSOLIDADA LÍQUIDA(DCL)(III)=(I - II)";
$texto [24] ['txt'] = "RECEITA CORRENTE LÍQUIDA - RCL";
$texto [25] ['txt'] = "% da DC sobre a RCL    (I/RCL)";
$texto [26] ['txt'] = "% da DCL sobre a RCL (III/RCL)";
$texto [27] ['txt'] = "LIMITE DEF. POR RESOL. Nº 40/01 DO SEN. FED. - ";

// REGIME PREVIDENCIARIO
$texto [28] ["txt"] = "DÍVIDA CONSOLIDADA PREVIDENCIÁRIA (IV)";
$texto [29] ["txt"] = "  Passivo Atuarial";
$texto [30] ["txt"] = "  Demais Dívidas";
$texto [31] ["txt"] = "DEDUÇÕES(V)";
$texto [32] ["txt"] = "  Ativo Disponível";
$texto [33] ["txt"] = "  Investimentos";
$texto [34] ["txt"] = "  Haveres Financeiros";
$texto [35] ["txt"] = "  (-) Restos a Pagar Processados";
$texto [36] ["txt"] = "OBRIGAÇÕES NÃO INTEGRANTES DA DC";
$texto [37] ["txt"] = "DÍVIDA CONSOLIDADA LÍQ. PREVIDENCIÁRIA(VI)=(IV - V)";

for($linha = 1; $linha <= 37; $linha ++) {
  
  $texto [$linha] ['ano'] = 0;
  $texto [$linha] ['quad1'] = 0;
  $texto [$linha] ['quad2'] = 0;
  $texto [$linha] ['quad3'] = 0;

}
if ($instituicao != '') {
  // exercicio + primeiro quadrimestre ou seleção por periodo informado
  for($i = 0; $i < pg_num_rows($result_01); $i ++) {
    db_fieldsmemory($result_01, $i);
    
    for($linha = 1; $linha <= 14; $linha ++) {
      
      $instit = $c61_instit;
      $v_elementos = array (
        
                      $estrutural, 
                      $instit 
      );
      $flag_contar = false;
       for($xx = 0; $xx < count($parametro[$linha]["estrut"]); $xx ++) {
          if ($estrutural == $parametro [$linha] ["estrut"] [$xx] [0]) {
            $flag_contar = true;
            break;
          }
        }
      
      if ($flag_contar == true) {
        $parametro [$linha] ['ano'] += $saldo_anterior;
        $parametro [$linha] ['quad1'] += $saldo_final;
      }
    
    }
  
  }
  
  // segundo quadrimestre e terceiro quadrimestre
  for($i = 0; $i < pg_numrows($result_02); $i ++) {
    db_fieldsmemory($result_02, $i);
    
    for($linha = 1; $linha <= 14; $linha ++) {
      
      $instit = $c61_instit;
      $v_elementos = array (
        
                      $estrutural, 
                      $instit 
      );
      $flag_contar = false;
      for($xx = 0; $xx < count($parametro [$linha] ["estrut"]); $xx ++) {
          if ($estrutural == $parametro [$linha] ["estrut"] [$xx] [0]) {
            $flag_contar = true;
            break;
          }
        }
      
      if ($flag_contar == true) {
        $parametro [$linha] ['quad2'] += $saldo_anterior;
        $parametro [$linha] ['quad3'] += $saldo_final;
      }
    
    }
  }
}
if (trim($instit_rpps) != "") {
  // exercicio + primeiro quadrimestre ou seleção por periodo informado
  for($i = 0; $i < pg_numrows($result_01_rpps); $i ++) {
    db_fieldsmemory($result_01_rpps, $i);
    
    for($linha = 15; $linha <= 20; $linha ++) {
      
      
      $instit = $c61_instit;
      $v_elementos = array (
        
                      $estrutural, 
                      $instit 
      );
      
      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos, $parametro [$linha] ["estrut"])) {
          $flag_contar = true;
        }
      } else {
        for($xx = 0; $xx < count($parametro [$linha] ["estrut"]); $xx ++) {
          if ($estrutural == $parametro [$linha] ["estrut"] [$xx] [0]) {
            $flag_contar = true;
            break;
          }
        }
      }
      
      if ($flag_contar == true) {
        $parametro [$linha] ['ano'] += $saldo_anterior;
        $parametro [$linha] ['quad1'] += $saldo_final;
      }
    
    }
  
  }
  
  // segundo quadrimestre e terceiro quadrimestre
  for($i = 0; $i < pg_numrows($result_02_rpps); $i ++) {
    db_fieldsmemory($result_02_rpps, $i);
    
    for($linha = 15; $linha <= 20; $linha ++) {
      
      $instit = $c61_instit;
      $v_elementos = array (
        
                      $estrutural, 
                      $instit 
      );
      
      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos, $parametro [$linha] ["estrut"])) {
          $flag_contar = true;
        }
      } else {
        
        for($xx = 0; $xx < count($parametro [$linha] ["estrut"]); $xx ++) {
          if ($estrutural == $parametro [$linha] ["estrut"] [$xx] [0]) {
            $flag_contar = true;
            break;
          }
        }
      }
      
      if ($flag_contar == true) {
        $parametro [$linha] ['quad2'] += $saldo_anterior;
        $parametro [$linha] ['quad3'] += $saldo_final;
      }
    
    }
  }
}

/////////// atribuição de valores

if ($periodo == "1Q") {
  
  $texto [21] ["quad1"] = $valor_insuficiencia1;
  $texto [22] ["quad1"] = $valor_outras_obrigacoes1;
  $texto [36] ["quad1"] = $valor_obrigacoes_nao_dc_prev1;
  
} else if ($periodo == "2Q" || $periodo == "1S") {
  
  $texto [21] ["quad2"] = $valor_insuficiencia2;
  $texto [22] ["quad2"] = $valor_outras_obrigacoes2;
  $texto [36] ["quad2"] = $valor_obrigacoes_nao_dc_prev2;
  
} else if ($periodo == "3Q" || $periodo == "2S") {
  
  if ($periodo == "3Q") {
    
    $texto [21] ["quad1"] = $valor_insuficiencia1;
    $texto [22] ["quad1"] = $valor_outras_obrigacoes1;
    $texto [36] ["quad1"] = $valor_obrigacoes_nao_dc_prev1;
    
    $texto [21] ["quad2"] = $valor_insuficiencia2;
    $texto [22] ["quad2"] = $valor_outras_obrigacoes2;
    $texto [36] ["quad2"] = $valor_obrigacoes_nao_dc_prev2;
  }
  
  $texto [21] ["quad3"] = $valor_insuficiencia3;
  $texto [22] ["quad3"] = $valor_outras_obrigacoes3;
  $texto [36] ["quad3"] = $valor_obrigacoes_nao_dc_prev3;
}

$texto [22] ["ano"] = $valor_outras_obrigacoes_ex_anterior;
$texto [36] ["ano"] = $valor_obrigacoes_nao_integ_dcprev_ex_anterior;

for($i = 1; $i <= 4; $i ++) {
  $pp = array (
    
                  '1' => 'ano', 
                  '2' => 'quad1', 
                  '3' => 'quad2', 
                  '4' => 'quad3' 
  );
  // atribuições diretas
  $texto [2] [$pp [$i]] = $parametro [1] [$pp [$i]]; //div mobiliaria
  

  $texto [4] [$pp [$i]]  = $parametro [2] [$pp [$i]]; //div contratual de PPP
  $texto [5] [$pp [$i]]  = $parametro [3] [$pp [$i]]; //demais div. contratuais
  $texto [3] [$pp [$i]]  = $texto [4] [$pp [$i]] + $texto [5] [$pp [$i]]; //div contratual
  $texto [6] [$pp [$i]]  = $parametro [4] [$pp [$i]]; //precatorios
  $texto [7] [$pp [$i]]  = $parametro [5] [$pp [$i]]; //operações
  $texto [9] [$pp [$i]]  = $parametro [6] [$pp [$i]]; //tributos
  $texto [11] [$pp [$i]] = $parametro [7] [$pp [$i]];
  $texto [12] [$pp [$i]] = $parametro [8] [$pp [$i]];
  $texto [13] [$pp [$i]] = $parametro [9] [$pp [$i]]; //fgts
  $texto [14] [$pp [$i]] = $parametro [10] [$pp [$i]]; //rpps
  $texto [16] [$pp [$i]] = $parametro [11] [$pp [$i]]; //outras dividas
  $texto [17] [$pp [$i]] = $parametro [12] [$pp [$i]]; //ativos
  $texto [18] [$pp [$i]] = $parametro [13] [$pp [$i]]; //haveres
  $texto [20] [$pp [$i]] = $parametro [14] [$pp [$i]]; //insuf
  

  // totalizadores
  $texto [10] [$pp [$i]] = $texto [11] [$pp [$i]] + $texto [12] [$pp [$i]];
  $texto [8] [$pp [$i]] = $texto [9] [$pp [$i]] + $texto [10] [$pp [$i]] + $texto [13] [$pp [$i]];
  $texto [1] [$pp [$i]] = $texto [2] [$pp [$i]] + $texto [3] [$pp [$i]] + $texto [6] [$pp [$i]] + $texto [7] [$pp [$i]] + $texto [8] [$pp [$i]] + $texto [14] [$pp [$i]];
  
  if (($texto [16] [$pp [$i]] + $texto [17] [$pp [$i]]) < abs($texto [18] [$pp [$i]])) {
    
    $texto [15] [$pp [$i]] = - 1;
    $texto[21][$pp[$i]] += abs($texto [16] [$pp [$i]] + $texto [17] [$pp [$i]]-$texto [18] [$pp [$i]]);
  } else {
    $texto [15] [$pp [$i]] = $texto [16] [$pp [$i]] + $texto [17] [$pp [$i]] - $texto [18] [$pp [$i]];
  }
  
  $texto [19] [$pp [$i]] = $texto [20] [$pp [$i]] + $texto [21] [$pp [$i]] + $texto [22] [$pp [$i]];
  $texto[21][$pp[$i]] = abs($texto[21][$pp[$i]]);
  
  // totalizador
  $texto [23] [$pp [$i]] = $texto [1] [$pp [$i]] - $texto [15] [$pp [$i]];

}

// REGIME PREVIDENCIARIO
for($i = 1; $i <= 4; $i ++) {
  $pp = array (
    
                  '1' => 'ano', 
                  '2' => 'quad1', 
                  '3' => 'quad2', 
                  '4' => 'quad3' 
  );
  
  $texto [29] [$pp [$i]] = $parametro [15] [$pp [$i]]; // Passivo Atuarial
  $texto [30] [$pp [$i]] = $parametro [16] [$pp [$i]]; // Demais Dividas
  $texto [28] [$pp [$i]] = $texto [29] [$pp [$i]] + $texto [30] [$pp [$i]]; // Divida Consolidada Previdenciaria
  

  $texto [32] [$pp [$i]] = $parametro [17] [$pp [$i]]; // Ativo Disponivel
  $texto [33] [$pp [$i]] = $parametro [18] [$pp [$i]]; // Investimentos
  $texto [34] [$pp [$i]] = $parametro [19] [$pp [$i]]; // Haveres Financeiros
  $texto [35] [$pp [$i]] = $parametro [20] [$pp [$i]]; // Restos a Pagar Processados
  

  if (($texto [32] [$pp [$i]] + $texto [33] [$pp [$i]] + $texto [34] [$pp [$i]]) < abs($texto [35] [$pp [$i]])) {
    
    $texto [31] [$pp [$i]]  = -1;
    $texto [37] [$pp [$i]]  = $texto [28] [$pp [$i]]; // DIVIDA CONSOLIDADA LIQUIDA PREVIDENCIARIA
    $texto [36] [$pp [$i]] += $texto [32] [$pp [$i]] + $texto [33] [$pp [$i]] + $texto [34] [$pp [$i]] - $texto [35] [$pp [$i]];
    $texto [36] [$pp [$i]] = abs($texto [36] [$pp [$i]]);
    
  } else {
    
    $texto [31] [$pp [$i]] = $texto [32] [$pp [$i]] + $texto [33] [$pp [$i]] + $texto [34] [$pp [$i]] - $texto [35] [$pp [$i]]; // DEDUCOES
    $texto [37] [$pp [$i]] = $texto [28] [$pp [$i]] - $texto [31] [$pp [$i]]; // DIVIDA CONSOLIDADA LIQUIDA PREVIDENCIARIA
    
  }
}

// receita corrente liquida


$todasinstit = "";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null, "codigo"));
for($xinstit = 0; $xinstit < $cldb_config->numrows; $xinstit ++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit .= $codigo . ($xinstit == $cldb_config->numrows - 1 ? "" : ",");
}

// saldo do exercicio anterior


$mes = $dt_ini [1];
if ($mes == 12) {
  $mes = 11;
} else {
  $mes += 1;
}
duplicaReceitaaCorrenteLiquida($anousu, 81);
$data = $anousu_ant . $mes . "-01";

if ($anousu_ant > 2007) {
  $aValoresRCL   = calcula_rcl2($anousu_ant, $anousu_ant . '-01-01', $anousu_ant . '-12-31', $todasinstit, true, 81, $data);
  $total_rcl_ant = array_sum($aValoresRCL);
} else {
  $total_rcl_ant = calcula_rcl($anousu_ant, $anousu_ant . '-01-01', $anousu_ant . '-12-31', $todasinstit, false);
}

$texto [24] ['ano'] = $total_rcl_ant;

if ($usa_datas == false) {
  
  
  $sTodasInstit = null;
  $rsInstit =  pg_query("select codigo from db_config");
  for ($xinstit=0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {
  
    db_fieldsmemory($rsInstit, $xinstit);
    $sTodasInstit .= $codigo . ($xinstit==pg_num_rows($rsInstit)-1?"":",");
  }
  $iExercAnt  = (db_getsession('DB_anousu')-1);
  $sCodParRel = "59";
  
  // Exclui elementos referente ao exercício anterior;
  // Inclui elemento no exercício anterior com base no atual;
//ano corrente
  $matriz = true; // retorna matriz dos meses
  $matriz_rcl = calcula_rcl2($anousu_ant, $anousu_ant . '-01-01', $anousu_ant . '-12-31', $todasinstit, $matriz, 81, $data);
  
  // primeiro quadrimestre
  $total_rcl = calcula_rcl2($anousu, $anousu . '-01-01', $anousu . '-04-30', $todasinstit, false, 81);
  //	echo "1: $total_rcl<br>";
  $texto [24] ['quad1'] = $total_rcl + $matriz_rcl ['maio'] + $matriz_rcl ['junho'] + $matriz_rcl ['julho'] + $matriz_rcl ['agosto'] + $matriz_rcl ['setembro'] + $matriz_rcl ['outubro'] + $matriz_rcl ['novembro'] + $matriz_rcl ['dezembro'];
  //	echo "2: maio: " . $matriz_rcl['maio'] . " - junho: " . $matriz_rcl['junho'] . " - julho: " . $matriz_rcl['julho'] . " - agosto: " . $matriz_rcl['agosto'] . " - setembro: " . $matriz_rcl['setembro'] . " - outubro: " . $matriz_rcl['outubro'] . " - novembro: " . $matriz_rcl['novembro'] . " - dezembro: " . $matriz_rcl['dezembro'] . "<br>";
  //	exit;
  

  $sDataFinal = "{$anousu}-08-31";
  // segundo quadrimestre
  if ($periodo == "1S") {
    $sDataFinal = "{$anousu}-06-30";
  }
  $total_rcl = calcula_rcl2($anousu, $anousu . '-01-01', $sDataFinal, $todasinstit, false, 81);
  if ($periodo != "1S") {
    $texto [24] ['quad2'] = $total_rcl + $matriz_rcl ['setembro'] + $matriz_rcl ['outubro'] + $matriz_rcl ['novembro'] + $matriz_rcl ['dezembro'];
  } else {
    $texto [24] ['quad2'] = $total_rcl + $matriz_rcl ["julho"] + $matriz_rcl ["agosto"] + $matriz_rcl ['setembro'] + $matriz_rcl ['outubro'] + $matriz_rcl ['novembro'] + $matriz_rcl ['dezembro'];
  }
  
  // terceiro quadrimestre
  $total_rcl = calcula_rcl2($anousu, $anousu . '-01-01', $anousu . '-12-31', $todasinstit, false, 81);
  $texto [24] ['quad3'] = $total_rcl;

} else {
  
  if ($anousu_ant > 2007) {
    $total_rcl = calcula_rcl2($anousu_ant, $dtini_ant, $dtfin_ant, $todasinstit, false, 81, $data);
  } else {
    $total_rcl = calcula_rcl($anousu_ant, $dtini_ant, $dtfin_ant, $todasinstit, false);
  }
  
  $texto [24] ['quad1'] = $total_rcl;
  
  $total_rcl = calcula_rcl2($anousu, $anousu . '-01-01', $dtfin, $todasinstit, false, 27);
  $texto [24] ['quad1'] += $total_rcl;

}

// calculo dos limites
@$texto [25] ['ano'] = $texto [1] ['ano'] * 100 / $texto [24] ['ano'];
@$texto [26] ['ano'] = $texto [23] ['ano'] * 100 / $texto [24] ['ano'];

@$texto [25] ['quad1'] = $texto [1] ['quad1'] * 100 / $texto [24] ['quad1'];
@$texto [26] ['quad1'] = $texto [23] ['quad1'] * 100 / $texto [24] ['quad1'];

@$texto [25] ['quad2'] = $texto [1] ['quad2'] * 100 / $texto [24] ['quad2'];
@$texto [26] ['quad2'] = $texto [23] ['quad2'] * 100 / $texto [24] ['quad2'];

@$texto [25] ['quad3'] = $texto [1] ['quad3'] * 100 / $texto [24] ['quad3'];
@$texto [26] ['quad3'] = $texto [23] ['quad3'] * 100 / $texto [24] ['quad3'];

// limite
@$texto [27] ['ano'] = $texto [24] ['ano'] * $limite / 100;
@$texto [27] ['quad1'] = $texto [24] ['quad1'] * $limite / 100;
@$texto [27] ['quad2'] = $texto [24] ['quad2'] * $limite / 100;
@$texto [27] ['quad3'] = $texto [24] ['quad3'] * $limite / 100;

// se o perido é primeiro quadrimestre, os outros quadrimestre são zerados
if ($usa_datas == false) {
  if ($periodo == '1Q') {
    for($linha = 1; $linha <= 37; $linha ++) {
      $texto [$linha] ['quad2'] = 0;
      $texto [$linha] ['quad3'] = 0;
    }
  } elseif ($periodo == '2Q') {
    for($linha = 1; $linha <= 37; $linha ++) {
      $texto [$linha] ['quad3'] = 0;
    }
  }
}
/*
echo "<pre>";
print_r($texto);
echo "</pre>";
exit;
*/
// --


$pcol = array (
  
                1 => 'ano', 
                2 => 'quad1', 
                3 => 'quad2', 
                4 => 'quad3' 
);

if (! isset($arqinclude)) {
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', '', 7);
  $alt = 4;
  $pagina = 1;
  
  $pdf->addpage();
  $pdf->ln();
  
  $pdf->cell(110, $alt, 'RGF - ANEXO II(LRF, art. 55, inciso I, alínea "b")', 'B', 0, "L", 0);
  $pdf->cell(75, $alt, 'R$ 1,00', 'B', 1, "R", 0);
  
  $pdf->cell(73, $alt, "", 'R', 0, "C", 0);
  $pdf->cell(28, $alt, "SALDO DO", 'R', 0, "C", 0);
  if ($usa_datas == false) {
    $pdf->cell(84, $alt, "SALDO DO EXERCÍCIO  DE $anousu", 'B', 1, "C", 0);
  } else {
    $pdf->cell(84, $alt, "SALDO DO PERIODO ", 'B', 1, "C", 0);
  }
  $pdf->cell(73, $alt, "CÁLCULO DA DÍVIDA CONSOLIDADA LÍQUIDA", 'BR', 0, "C", 0);
  $pdf->cell(28, $alt, "EXERCÍCIO ANTERIOR", 'RB', 0, "C", 0);
  
  if ($usa_datas == true) {
    $pdf->cell(28 * 3, $alt, "PERIODO " . db_formatar($dtini, 'd') . " à " . db_formatar($dtfin, 'd'), 'B', 1, "C", 0);
  } else {
    if ($periodo == "1S" || $periodo == "2S") {
      $pdf->cell(42, $alt, "Até 1º Semestre", 'BR', 0, "C", 0);
      $pdf->cell(42, $alt, "Até 2º Semestre", 'B', 1, "C", 0);
    } else {
      $pdf->cell(28, $alt, "Até 1º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 2º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 3º Quadrimestre", 'B', 1, "C", 0);
    }
  }
  
  // testar se deducoes foram negativas, jogar para insuficiencia...
  

  for($linha = 1; $linha <= 22; $linha ++) {
    
    $pdf->cell(73, $alt, $texto [$linha] ['txt'], 'R', 0, "L", 0);
    if ($linha == 15 && $texto [$linha] ['ano'] == -1) {
      $pdf->cell(28, $alt, "-",'R', 0, "R", 0);
    } else {
      $pdf->cell(28, $alt, db_formatar($texto [$linha] ['ano'], 'f'), 'R', 0, "R", 0);
    }
    if ($usa_datas == true) {
      if ($linha == 15) {
        if ($texto [$linha] ["quad1"] == - 1) {
          $pdf->cell(28 * 3, $alt, "-", '', 0, "C", 0);
        } else {
          $pdf->cell(28 * 3, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), '', 0, "R", 0);
        }
      } else {
        $pdf->cell(28 * 3, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), '', 0, "R", 0);
      }
    } else {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 15) {
          if ($texto [$linha] ["quad2"] == - 1) {
            $pdf->cell(42, $alt, "-", 'R', 0, "C", 0);
          } else {
            $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), 'R', 0, "R", 0);
          }
        } else {
          $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), 'R', 0, "R", 0);
        }
        if ($periodo == "2S") {
          if ($linha == 15) {
            if ($texto [$linha] ["quad3"] == - 1) {
              $pdf->cell(42, $alt, "-", '', 0, "C", 0);
            } else {
              $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), '', 0, "R", 0);
            }
          } else {
            $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), '', 0, "R", 0);
          }
        } else {
          $pdf->cell(42, $alt, db_formatar("0.00", 'f'), '', 0, "R", 0);
        }
      } else {
        if ($linha == 15) {
          if ($texto [$linha] ["quad1"] == - 1) {
            $pdf->cell(28, $alt, "-", 'R', 0, "C", 0);
          } else {
            $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), 'R', 0, "R", 0);
          }
          
          if ($texto [$linha] ["quad2"] == - 1) {
            $pdf->cell(28, $alt, "-", 'R', 0, "C", 0);
          } else {
            $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), 'R', 0, "R", 0);
          }
          
          if ($texto [$linha] ["quad3"] == - 1) {
            $pdf->cell(28, $alt, "-", '', 0, "C", 0);
          } else {
            $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), '', 0, "R", 0);
          }
        } else {
          $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), 'R', 0, "R", 0);
          $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), 'R', 0, "R", 0);
          $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), '', 0, "R", 0);
        }
      }
    }
    
    $pdf->Ln();
  
  }
  
  // inprime bloco abaixo
  for($linha = 23; $linha <= 27; $linha ++) {
    
    if ($linha == 27) {
      $texto [$linha] ["txt"] .= $limite . "%";
    }
    
    $pdf->cell(73, $alt, $texto [$linha] ['txt'], 'TBR', 0, "L", 0);
    $pdf->cell(28, $alt, db_formatar($texto [$linha] ['ano'], 'f'), 'TBR', 0, "R", 0);
    
    if ($usa_datas == true) {
      $pdf->cell(28 * 3, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), 'TB', 0, "R", 0);
    } else {
      if ($periodo == "1S" || $periodo == "2S") {
        $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), 'TBR', 0, "R", 0);
        if ($periodo == "2S") {
          $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), 'TB', 0, "R", 0);
        } else {
          $pdf->cell(42, $alt, db_formatar("0.00", 'f'), 'TB', 0, "R", 0);
        }
      } else {
        $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), 'TBR', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), 'TBR', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), 'TB', 0, "R", 0);
      }
    }
    $pdf->Ln();
  }
  
  $pdf->Ln();
  $pdf->cell(185, $alt, "REGIME PREVIDENCIÁRIO", 'TB', 1, "C", 0);
  $pdf->cell(73, $alt, "CÁLCULO DA DÍVIDA CONSOLIDADA ", "R", 0, "C", 0);
  $pdf->cell(28, $alt, "SALDO DO", 'R', 0, "C", 0);
  if ($usa_datas == false) {
    $pdf->cell(84, $alt, "SALDO DO EXERCÍCIO  DE $anousu", 'B', 1, "C", 0);
  } else {
    $pdf->cell(84, $alt, "SALDO DO PERIODO ", 'B', 1, "C", 0);
  }
  $pdf->cell(73, $alt, "LÍQUIDA PREVIDENCIÁRIA", 'BR', 0, "C", 0);
  $pdf->cell(28, $alt, "EXERCÍCIO ANTERIOR", 'RB', 0, "C", 0);
  
  if ($usa_datas == true) {
    $pdf->cell(28 * 3, $alt, "PERIODO " . db_formatar($dtini, 'd') . " à " . db_formatar($dtfin, 'd'), 'B', 1, "C", 0);
  } else {
    if ($periodo == "1S" || $periodo == "2S") {
      $pdf->cell(42, $alt, "Até 1º Semestre", 'BR', 0, "C", 0);
      $pdf->cell(42, $alt, "Até 2º Semestre", 'B', 1, "C", 0);
    } else {
      $pdf->cell(28, $alt, "Até 1º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 2º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 3º Quadrimestre", 'B', 1, "C", 0);
    }
  }
  
  // REGIME PREVIDENCIARIO
  for($linha = 28; $linha <= 37; $linha ++) {
    $borda = "";
    if ($linha == 37 || $linha == 36) {
      $borda = "B";
    }
    
    $pdf->cell(73, $alt, $texto [$linha] ['txt'], $borda . 'R', 0, "L", 0);
    if ($linha == 31 || $linha == 37) {

      //if ($texto [$linha] ["quad1"] < 0 || $texto [$linha] ["quad2"] < 0 || $texto [$linha] ["quad3"] < 0) {
      if ($texto[$linha]['ano'] < 0) {
        $pdf->cell(28, $alt, "-", $borda . 'R', 0, "R", 0);
      } else {
        $pdf->cell(28, $alt, db_formatar($texto [$linha] ['ano'], 'f'), $borda . 'R', 0, "R", 0);
      }
    } else {
      $pdf->cell(28, $alt, db_formatar($texto [$linha] ['ano'], 'f'), $borda . 'R', 0, "R", 0);
    }
    
    if ($usa_datas == true) {
      if ($linha == 31 || $linha == 37) {
        if ($texto [$linha] ["quad1"] < 0) {
          $pdf->cell(28 * 3, $alt, "-", $borda, 0, "C", 0);
        } else {
          $pdf->cell(28 * 3, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), $borda, 0, "R", 0);
        }
      } else {
        $pdf->cell(28 * 3, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), $borda, 0, "R", 0);
      }
    } else {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 31 || $linha == 37) {
          if ($texto [$linha] ["quad2"] < 0) {
            $pdf->cell(42, $alt, "-", $borda . 'R', 0, "C", 0);
          } else {
            $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), $borda . 'R', 0, "R", 0);
          }
        } else {
          $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), $borda . 'R', 0, "R", 0);
        }
        
        if ($periodo == "2S") {
          if ($linha == 31 || $linha == 37) {
            if ($texto [$linha] ["quad3"] < 0) {
              $pdf->cell(42, $alt, "-", $borda, 0, "C", 0);
            } else {
              $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), $borda, 0, "R", 0);
            }
          } else {
            $pdf->cell(42, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), $borda, 0, "R", 0);
          }
        } else {
          $pdf->cell(42, $alt, db_formatar("0.00", 'f'), $borda, 0, "R", 0);
        }
      } else {
        if ($linha == 31 || $linha == 37) {
          if ($texto [$linha] ["quad1"] < 0) {
            $pdf->cell(28, $alt, "-", $borda . 'R', 0, "C", 0);
          } else {
            $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), $borda . 'R', 0, "R", 0);
          }
          
          if ($texto [$linha] ["quad2"] < 0) {
            $pdf->cell(28, $alt, "-", $borda . 'R', 0, "C", 0);
          } else {
            $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), $borda . 'R', 0, "R", 0);
          }
          
          if ($texto [$linha] ["quad3"] < 0) {
            $pdf->cell(28, $alt, "-", $borda, 0, "C", 0);
          } else {
            $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), $borda, 0, "R", 0);
          }
        } else {
          $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad1'], 'f'), $borda . 'R', 0, "R", 0);
          $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad2'], 'f'), $borda . 'R', 0, "R", 0);
          $pdf->cell(28, $alt, db_formatar($texto [$linha] ['quad3'], 'f'), $borda, 0, "R", 0);
        }
      }
    }
    
    $pdf->Ln();
  }
  
  $pdf->Ln();
  
  $linhas = 20;
  
  if ($trajetoria == "S") {
    $pdf->setfont('arial', '', 6);
    $pdf->cell(185, $alt, "TRAJETÓRIA DE AJUSTE DA DÍVIDA CONSOLIDADA LÍQUIDA EM CADA EXERCÍCIO FINANCEIRO", "TB", 1, "C", 0);
    
    for($linha = 1; $linha <= 16; $linha ++) {
      $var_info [$linha] [$pcol [1]] = 2000 + $linha;
      $var_info [$linha] [$pcol [2]] = 0;
      $var_info [$linha] [$pcol [3]] = 0;
      $var_info [$linha] [$pcol [4]] = 0;
    }
    
    $pdf->cell(39, ($alt * 3), "Exercício Financeiro", 'BR', 0, "C", 0);
    for($linha = 1; $linha <= 4; $linha ++) {
      if ($linha == 4) {
        $pdf->cell(29, $alt, $var_info [$linha] [$pcol [1]], 'B', 1, "C", 0); // ano
      } else {
        $pdf->cell(39, $alt, $var_info [$linha] [$pcol [1]], 'BR', 0, "C", 0); // ano
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 1; $linha <= 4; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($var_info [$linha] [$pcol [1]] != 2001) {
          if ($linha == 4) {
            $pdf->cell(29, $alt, "Semestre", 'B', 1, "C", 0);
          } else {
            $pdf->cell(39, $alt, "Semestre", 'BR', 0, "C", 0);
          }
        } else {
          $pdf->cell(39, $alt, "Semestre", 'BR', 0, "C", 0);
        }
      } else {
        if ($var_info [$linha] [$pcol [1]] != 2001) {
          if ($linha == 4) {
            $pdf->cell(29, $alt, "Quadrimestre", 'B', 1, "C", 0);
          } else {
            $pdf->cell(39, $alt, "Quadrimestre", 'BR', 0, "C", 0);
          }
        } else {
          $pdf->cell(39, $alt, "3º Quadrimestre", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 1; $linha <= 4; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 4) {
          $pdf->cell(15, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(14, $alt, "2º", 'B', 1, "C", 0);
        } else {
          if ($var_info [$linha] [$pcol [1]] != 2001) {
            $pdf->cell(20, $alt, "1º", 'BR', 0, "C", 0);
            $pdf->cell(19, $alt, "2º", 'BR', 0, "C", 0);
          } else {
            $pdf->cell(20, $alt, "DCL", 'BR', 0, "C", 0);
            $pdf->cell(19, $alt, "Excedente", 'B', 0, "C", 0);
          }
        }
      } else {
        if ($linha == 4) {
          $pdf->cell(10, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(10, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(9, $alt, "3º", 'B', 1, "C", 0);
        } else {
          if ($var_info [$linha] [$pcol [1]] != 2001) {
            $pdf->cell(13, $alt, "1º", 'BR', 0, "C", 0);
            $pdf->cell(13, $alt, "2º", 'BR', 0, "C", 0);
            $pdf->cell(13, $alt, "3º", 'BR', 0, "C", 0);
          } else {
            $pdf->cell(13, $alt, "DCL", 'BR', 0, "C", 0);
            $pdf->cell(13, $alt, "Excedente", 'BR', 0, "C", 0);
            $pdf->cell(13, $alt, "Redutor", 'BR', 0, "C", 0);
          }
        }
      }
    }
    
    $pdf->cell(39, $alt, "% da DCL sobre a RCL", 'BR', 0, "C", 0);
    
    for($linha = 1; $linha <= 4; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 4) {
          $pdf->cell(15, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(14, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(20, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(19, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      } else {
        if ($linha == 4) {
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0); // quad1
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(9, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% Limite de Endividamento", 'BR', 0, "C", 0);
    for($linha = 1; $linha <= 4; $linha ++) {
      if ($linha == 4) {
        $pdf->cell(29, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'B', 1, "R", 0);
      } else {
        $pdf->cell(39, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
      }
    }
    
    $pdf->cell(185, $alt, "", "TB", 1, "C", 0);
    
    // 2005 a 2008
    $pdf->cell(39, ($alt * 3), "Exercício Financeiro", 'BR', 0, "C", 0);
    for($linha = 5; $linha <= 8; $linha ++) {
      if ($linha == 8) {
        $pdf->cell(29, $alt, $var_info [$linha] [$pcol [1]], 'B', 1, "C", 0); // ano
      } else {
        $pdf->cell(39, $alt, $var_info [$linha] [$pcol [1]], 'BR', 0, "C", 0); // ano
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 5; $linha <= 8; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 8) {
          $pdf->cell(29, $alt, "Semestre", 'B', 1, "C", 0);
        } else {
          $pdf->cell(39, $alt, "Semestre", 'BR', 0, "C", 0);
        }
      } else {
        if ($linha == 8) {
          $pdf->cell(29, $alt, "Quadrimestre", 'B', 1, "C", 0);
        } else {
          $pdf->cell(39, $alt, "Quadrimestre", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 5; $linha <= 8; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 8) {
          $pdf->cell(15, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(14, $alt, "2º", 'B', 1, "C", 0);
        } else {
          $pdf->cell(20, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(19, $alt, "2º", 'BR', 0, "C", 0);
        }
      } else {
        if ($linha == 8) {
          $pdf->cell(10, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(10, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(9, $alt, "3º", 'B', 1, "C", 0);
        } else {
          $pdf->cell(13, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(13, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(13, $alt, "3º", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% da DCL sobre a RCL", 'BR', 0, "C", 0);
    
    for($linha = 5; $linha <= 8; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 8) {
          $pdf->cell(15, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(14, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(20, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(19, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      } else {
        if ($linha == 8) {
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0); // quad1
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(9, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% Limite de Endividamento", 'BR', 0, "C", 0);
    for($linha = 5; $linha <= 8; $linha ++) {
      if ($linha == 8) {
        $pdf->cell(29, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'B', 1, "R", 0);
      } else {
        $pdf->cell(39, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
      }
    }
    
    if ($pdf->getY() > $pdf->h - 20) {
      $pdf->AddPage();
      $pdf->cell(185, $alt, "", "TB", 1, "C", 0);
    }
    
    // 2009 a 2012
    $pdf->cell(39, ($alt * 3), "Exercício Financeiro", 'BR', 0, "C", 0);
    for($linha = 9; $linha <= 12; $linha ++) {
      if ($linha == 12) {
        $pdf->cell(29, $alt, $var_info [$linha] [$pcol [1]], 'B', 1, "C", 0); // ano
      } else {
        $pdf->cell(39, $alt, $var_info [$linha] [$pcol [1]], 'BR', 0, "C", 0); // ano
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 9; $linha <= 12; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 12) {
          $pdf->cell(29, $alt, "Semestre", 'B', 1, "C", 0);
        } else {
          $pdf->cell(39, $alt, "Semestre", 'BR', 0, "C", 0);
        }
      } else {
        if ($linha == 12) {
          $pdf->cell(29, $alt, "Quadrimestre", 'B', 1, "C", 0);
        } else {
          $pdf->cell(39, $alt, "Quadrimestre", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 9; $linha <= 12; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 12) {
          $pdf->cell(15, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(14, $alt, "2º", 'B', 1, "C", 0);
        } else {
          $pdf->cell(20, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(19, $alt, "2º", 'BR', 0, "C", 0);
        }
      } else {
        if ($linha == 12) {
          $pdf->cell(10, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(10, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(9, $alt, "3º", 'B', 1, "C", 0);
        } else {
          $pdf->cell(13, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(13, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(13, $alt, "3º", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% da DCL sobre a RCL", 'BR', 0, "C", 0);
    
    for($linha = 9; $linha <= 12; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 12) {
          $pdf->cell(15, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(14, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(20, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(19, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      } else {
        if ($linha == 12) {
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0); // quad1
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(9, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% Limite de Endividamento", 'BR', 0, "C", 0);
    for($linha = 9; $linha <= 12; $linha ++) {
      if ($linha == 12) {
        $pdf->cell(29, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'B', 1, "R", 0);
      } else {
        $pdf->cell(39, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
      }
    }
    
    $pdf->cell(185, $alt, "", "TB", 1, "C", 0);
    
    // 2013 a 2016
    $pdf->cell(39, ($alt * 3), "Exercício Financeiro", 'BR', 0, "C", 0);
    for($linha = 13; $linha <= 16; $linha ++) {
      if ($linha == 16) {
        $pdf->cell(29, $alt, $var_info [$linha] [$pcol [1]], 'B', 1, "C", 0); // ano
      } else {
        $pdf->cell(39, $alt, $var_info [$linha] [$pcol [1]], 'BR', 0, "C", 0); // ano
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 13; $linha <= 16; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 16) {
          $pdf->cell(29, $alt, "Semestre", 'B', 1, "C", 0);
        } else {
          $pdf->cell(39, $alt, "Semestre", 'BR', 0, "C", 0);
        }
      } else {
        if ($linha == 16) {
          $pdf->cell(29, $alt, "Quadrimestre", 'B', 1, "C", 0);
        } else {
          $pdf->cell(39, $alt, "Quadrimestre", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->setX(49);
    
    for($linha = 13; $linha <= 16; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 16) {
          $pdf->cell(15, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(14, $alt, "2º", 'B', 1, "C", 0);
        } else {
          $pdf->cell(20, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(19, $alt, "2º", 'BR', 0, "C", 0);
        }
      } else {
        if ($linha == 16) {
          $pdf->cell(10, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(10, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(9, $alt, "3º", 'B', 1, "C", 0);
        } else {
          $pdf->cell(13, $alt, "1º", 'BR', 0, "C", 0);
          $pdf->cell(13, $alt, "2º", 'BR', 0, "C", 0);
          $pdf->cell(13, $alt, "3º", 'BR', 0, "C", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% da DCL sobre a RCL", 'BR', 0, "C", 0);
    
    for($linha = 13; $linha <= 16; $linha ++) {
      if ($periodo == "1S" || $periodo == "2S") {
        if ($linha == 16) {
          $pdf->cell(15, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(14, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(20, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(19, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      } else {
        if ($linha == 16) {
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0); // quad1
          $pdf->cell(10, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0); // quad2
          $pdf->cell(9, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'B', 1, "R", 0); // quad3
        } else {
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [3]], "f"), 'BR', 0, "R", 0);
          $pdf->cell(13, $alt, db_formatar($var_info [$linha] [$pcol [4]], "f"), 'BR', 0, "R", 0);
        }
      }
    }
    
    $pdf->cell(39, $alt, "% Limite de Endividamento", 'BR', 0, "C", 0);
    for($linha = 13; $linha <= 16; $linha ++) {
      if ($linha == 16) {
        $pdf->cell(29, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'B', 1, "R", 0);
      } else {
        $pdf->cell(39, $alt, db_formatar($var_info [$linha] [$pcol [2]], "f"), 'BR', 0, "R", 0);
      }
    }
    
    $linhas = 30;
    
    $pdf->setfont("arial", "I", 6);
    $pdf->multicell(185, $alt, "     Se o saldo apurado for negativo, ou seja, se o total do Ativo Disponível mais os Haveres Financeiros for menor que Restos a Pagar Processados, não deverá ser informado nessa linha, mas sim na linha da \"Insuficiência Financeira\", das Obrigações não integrantes da Dívida Consolidada - DC. Assim quando o cálculo de DEDUÇÕES (II) for negativo, colocar um \"-\" (traço) nessa linha.", 0, "J");
    $pdf->cell(185, $alt, "NOTA:", 0, 1, "L", 0);
    $pdf->multicell(185, $alt, "     O excedente em relação ao limite apurado ao final do exercício de 2001 deverá ser reduzido, no mínimo, à proporção de 1/15 (um quinze avos) a cada exercício financeiro. O valor da redução anual, 1/15 (um quinze avos) do excedente, é apresentado na coluna Redutor.", 0, "J");
    
    $pdf->Ln();
  }
  
  notasExplicativas(&$pdf, 29, "{$periodo}", 185);
  
  $pdf->ln($linhas);
  // assinaturas
  

  assinaturas(&$pdf, &$classinatura, 'GF');
  
  $pdf->Output();

}

$limite_senado = 0;
if ($periodo == "1Q") {
  $total_divida = $texto [23] ['quad1'];
  $percdclsobrercl = $texto [26] ['quad1'];
  $limite_divida = $texto [27] ['quad1'];
} elseif ($periodo == "2Q" || $periodo == "1S") {
  $total_divida = $texto [23] ['quad2'];
  $percdclsobrercl = $texto [26] ['quad2'];
  $limite_divida = $texto [27] ['quad2'];
} elseif ($periodo == "3Q" || $periodo == "2S") {
  $total_divida = $texto [23] ['quad3'];
  $percdclsobrercl = $texto [26] ['quad3'];
  $limite_divida = $texto [27] ['quad3'];
}

$limite_senado = $limite;
?>