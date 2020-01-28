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


if (! isset($arqinclude)) {

  require_once ("fpdf151/pdf.php");
  require_once ("fpdf151/assinatura.php");
  require_once ("libs/db_sql.php");
  require_once ("libs/db_libcontabilidade.php");
  require_once ("libs/db_liborcamento.php");
  require_once ("classes/db_orcparamrel_classe.php");
  require_once ("classes/db_conrelinfo_classe.php");
  require_once ("classes/db_db_config_classe.php");
  require_once ("dbforms/db_funcoes.php");
  include_once("classes/db_orcparamelemento_classe.php");
  require_once("libs/db_utils.php");
  require_once("model/linhaRelatorioContabil.model.php");
  require_once("model/relatorioContabil.model.php");
  $classinatura       = new cl_assinatura();
  $orcparamrel        = new cl_orcparamrel();
  $clconrelinfo       = new cl_conrelinfo();
  $cldb_config        = new cl_db_config();
  $clorcparamelemento = new cl_orcparamelemento();

  parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

}
$sTodasInstit = null;
$limite       = 0;
$anousu       = db_getsession("DB_anousu");

$resultinst = db_query("select codigo,munic,db21_tipoinstit from db_config");
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
    $virgula      = ",";
  }
}
$iCodigoRelatorio = 90;
$sTodasInstit     = $instituicao;
if ($instit_rpps != "") {
  $sTodasInstit .= ",".$instit_rpps;
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
$anousu         = db_getsession("DB_anousu");
$oDaoPeriodo    = db_utils::getDao("periodo");
$iCodigoPeriodo = $periodo;
$sSqlPeriodo    = $oDaoPeriodo->sql_query($iCodigoPeriodo);
$sSiglaPeriodo  = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
$dt             = data_periodo($anousu, $sSiglaPeriodo);
$periodo        = $sSiglaPeriodo;

$dt = data_periodo($anousu, $sSiglaPeriodo);
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

if ($sSiglaPeriodo == "1S") {

  $dtini_01 = $anousu . '-01-01';
  $dtfin_01 = $anousu . '-06-30';
  $dtini_02 = $anousu . '-07-01';
  $dtfin_02 = $anousu . '-12-31';

  $aRCL = array();
  $aRCL["primeiroperiodo"]["anterior"] = array("julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril", "maio","junho");
  $aRCL["segundoperiodo"]["anterior"]   = array();
  $aRCL["segundoperiodo"]["atual"]      = array();

  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]      = array();

} else if ($sSiglaPeriodo == "2S") {

  $dtini_01 = $anousu . '-01-01';
  $dtfin_01 = $anousu . '-06-30';
  $dtini_02 = $anousu . '-07-01';
  $dtfin_02 = $anousu . '-12-31';

  $aRCL["primeiroperiodo"]["anterior"] = array("julho", "agosto","setembro","outubro", "novembro","dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril", "maio","junho");

  $aRCL["segundoperiodo"]["anterior"] = array();
  $aRCL["segundoperiodo"]["atual"] = array("janeiro","fevereiro","marco","abril",
                                            "maio","junho","julho","agosto","setembro","novembro","outubro", "dezembro"
                                              );
  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]      = array();

} else {

  $dtini_01 = $anousu . '-01-01';
  $dtfin_01 = $anousu . '-04-30';
  $dtini_02 = $anousu . '-09-01';
  $dtfin_02 = $anousu . '-12-31';

}

if ($sSiglaPeriodo == '1Q') {

  $aRCL["primeiroperiodo"]["anterior"] = array("maio","junho","julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril" );

  $aRCL["segundoperiodo"]["anterior"]   = array();
  $aRCL["segundoperiodo"]["atual"]      = array();

  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]      = array();

} else if ($sSiglaPeriodo == '2Q') {

  $aRCL["primeiroperiodo"]["anterior"] = array("maio","junho","julho","agosto","setembro","novembro","outubro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril" );

  $aRCL["segundoperiodo"]["anterior"] = array("setembro","novembro","outubro", "dezembro");
  $aRCL["segundoperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto");

  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]      = array();
} else if ($sSiglaPeriodo == '3Q') {

  $aRCL["primeiroperiodo"]["anterior"] = array("maio","junho","julho","agosto","setembro","novembro","outubro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril" );

  $aRCL["segundoperiodo"]["anterior"] = array("setembro","novembro","outubro", "dezembro");
  $aRCL["segundoperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto");
  $aRCL["terceiroperiodo"]["anterior"] = array();
  $aRCL["terceiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto",
                                               "setembro","novembro","outubro", "dezembro");
}

/**
 * Criamos os totalizadores
 */
$aTotalizadores = array();

$aTotalizadores[0]["label"]           = "DÍVIDA CONSOLIDADA - DC (I)";
$aTotalizadores[0]["nivel"]           = 0;
$aTotalizadores[0]["saldoanterior"]   = 0;
$aTotalizadores[0]["primeiroperiodo"] = 0;
$aTotalizadores[0]["bordas"]          = "";
$aTotalizadores[0]["segundoperiodo"]  = 0;
$aTotalizadores[0]["terceiroperiodo"] = 0;
$aTotalizadores[0]["imprime"]         = array(
                                              1 => 2
                                              );
$aTotalizadores[0]["soma"]            = array(1,2,3,4,5);

$aTotalizadores[1]["label"]           = "Dívida Contratual";
$aTotalizadores[1]["nivel"]           = 2;
$aTotalizadores[1]["saldoanterior"]   = 0;
$aTotalizadores[1]["primeiroperiodo"] = 0;
$aTotalizadores[1]["bordas"]          = "";
$aTotalizadores[1]["segundoperiodo"]  = 0;
$aTotalizadores[1]["terceiroperiodo"] = 0;
$aTotalizadores[1]["imprime"]         = array(2 => 4,
                                              3 => 4,
                                              4 => 2,
                                              5 => 2
                                              );
$aTotalizadores[1]["soma"]            = array(2,3);

$aTotalizadores[2]["label"]           = "DEDUÇÕES(II)¹";
$aTotalizadores[2]["nivel"]           = 0;
$aTotalizadores[2]["saldoanterior"]   = 0;
$aTotalizadores[2]["primeiroperiodo"] = 0;
$aTotalizadores[2]["segundoperiodo"]  = 0;
$aTotalizadores[2]["bordas"]          = "";
$aTotalizadores[2]["terceiroperiodo"] = 0;
$aTotalizadores[2]["imprime"]         = array(6 => 2,
                                              7 => 2,
                                              8 => 2,
                                              );
$aTotalizadores[2]["soma"]            = array(6,7,8);

$aTotalizadores[3]["label"]           = "DÍVIDA CONSOLIDADA LÍQUIDA - DCL(III) = (I - II)";
$aTotalizadores[3]["nivel"]           = 0;
$aTotalizadores[3]["saldoanterior"]   = 0;
$aTotalizadores[3]["bordas"]          = "TB";
$aTotalizadores[3]["primeiroperiodo"] = 0;
$aTotalizadores[3]["segundoperiodo"]  = 0;
$aTotalizadores[3]["terceiroperiodo"] = 0;
$aTotalizadores[3]["imprime"]         = array();
$aTotalizadores[3]["soma"]            = array();

$aTotalizadores[4]["label"]           = "RECEITA CORRENTE LÍQUIDA - RCL";
$aTotalizadores[4]["nivel"]           = 0;
$aTotalizadores[4]["saldoanterior"]   = 0;
$aTotalizadores[4]["bordas"]          = "TB";
$aTotalizadores[4]["primeiroperiodo"] = 0;
$aTotalizadores[4]["segundoperiodo"]  = 0;
$aTotalizadores[4]["terceiroperiodo"] = 0;
$aTotalizadores[4]["imprime"]         = array();
$aTotalizadores[4]["soma"]            = array();

$aTotalizadores[5]["label"]           = "% DA DC SOBRE A RCL (I/RCL)";
$aTotalizadores[5]["nivel"]           = 0;
$aTotalizadores[5]["saldoanterior"]   = 0;
$aTotalizadores[5]["primeiroperiodo"] = 0;
$aTotalizadores[5]["segundoperiodo"]  = 0;
$aTotalizadores[5]["bordas"]          = "TB";
$aTotalizadores[5]["terceiroperiodo"] = 0;
$aTotalizadores[5]["imprime"]         = array();
$aTotalizadores[5]["soma"]            = array();

$aTotalizadores[6]["label"]           = "% DA DCL SOBRE A RCL (III/RCL)";
$aTotalizadores[6]["nivel"]           = 0;
$aTotalizadores[6]["saldoanterior"]   = 0;
$aTotalizadores[6]["primeiroperiodo"] = 0;
$aTotalizadores[6]["segundoperiodo"]  = 0;
$aTotalizadores[6]["bordas"]          = "TB";
$aTotalizadores[6]["terceiroperiodo"] = 0;
$aTotalizadores[6]["imprime"]         = array();
$aTotalizadores[6]["soma"]            = array();

$aTotalizadores[7]["label"]           = "PARCELAMENTO DE DÍVIDAS (VI) ";
$aTotalizadores[7]["nivel"]           = 0;
$aTotalizadores[7]["saldoanterior"]   = 0;
$aTotalizadores[7]["primeiroperiodo"] = 0;
$aTotalizadores[7]["segundoperiodo"]  = 0;
$aTotalizadores[7]["bordas"]          = "";
$aTotalizadores[7]["terceiroperiodo"] = 0;
$aTotalizadores[7]["imprime"]         = array(10 => 2);
$aTotalizadores[7]["soma"]            = array(10,11,12,13,26);

$aTotalizadores[8]["label"]           = "De Contribuições Sociais";
$aTotalizadores[8]["nivel"]           = 2;
$aTotalizadores[8]["saldoanterior"]   = 0;
$aTotalizadores[8]["primeiroperiodo"] = 0;
$aTotalizadores[8]["segundoperiodo"]  = 0;
$aTotalizadores[8]["bordas"]          = "";
$aTotalizadores[8]["terceiroperiodo"] = 0;
$aTotalizadores[8]["imprime"]         = array(11 => 4,
                                              12 => 4,
                                              13 => 2,
                                              26 => 2
                                             );
$aTotalizadores[8]["soma"]            = array(11,12);



$aTotalizadores[9]["label"]           = "DÍVIDA CONSOLIDADA PREVIDENCIÁRIA (IV)";
$aTotalizadores[9]["nivel"]           = 0;
$aTotalizadores[9]["saldoanterior"]   = 0;
$aTotalizadores[9]["primeiroperiodo"] = 0;
$aTotalizadores[9]["bordas"]          = "";
$aTotalizadores[9]["segundoperiodo"]  = 0;
$aTotalizadores[9]["terceiroperiodo"] = 0;
$aTotalizadores[9]["imprime"]         = array(
                                              19 => 2,
                                              20 => 2
                                              );
$aTotalizadores[9]["soma"]            = array(19, 20);

$aTotalizadores[10]["label"]           = "DEDUÇÕES(V)¹";
$aTotalizadores[10]["nivel"]           = 0;
$aTotalizadores[10]["saldoanterior"]   = 0;
$aTotalizadores[10]["primeiroperiodo"] = 0;
$aTotalizadores[10]["segundoperiodo"]  = 0;
$aTotalizadores[10]["bordas"]          = "";
$aTotalizadores[10]["terceiroperiodo"] = 0;
$aTotalizadores[10]["imprime"]         = array(21 => 2,
                                               22 => 2,
                                               23 => 2,
                                               24 => 2,
                                               25 => 0,
                                              );
$aTotalizadores[10]["soma"]            = array(21, 22, 23, 24);

$aTotalizadores[11]["label"]           = "DÍVIDA CONSOLIDADA LÍQUIDA PREVDENCIÁRIA(VI) = (IV - V)";
$aTotalizadores[11]["nivel"]           = 0;
$aTotalizadores[11]["saldoanterior"]   = 0;
$aTotalizadores[11]["bordas"]          = "TB";
$aTotalizadores[11]["primeiroperiodo"] = 0;
$aTotalizadores[11]["segundoperiodo"]  = 0;
$aTotalizadores[11]["terceiroperiodo"] = 0;
$aTotalizadores[11]["imprime"]         = array();
$aTotalizadores[11]["soma"]            = array();

$aTotalizadores[12]["label"]           = "DIVIDA CONTRATUAL (IV = V + VI + VII + VIII)";
$aTotalizadores[12]["nivel"]           = 0;
$aTotalizadores[12]["saldoanterior"]   = 0;
$aTotalizadores[12]["bordas"]          = "TB";
$aTotalizadores[12]["primeiroperiodo"] = 0;
$aTotalizadores[12]["segundoperiodo"]  = 0;
$aTotalizadores[12]["terceiroperiodo"] = 0;
$aTotalizadores[12]["imprime"]         = array();
$aTotalizadores[12]["soma"]            = array(9,10,11,12,13,14,26,27,28);

$aTotalizadores[13]["label"]           = "DIVIDA COM INSTITUIÇÃO FINANCEIRA (VII)";
$aTotalizadores[13]["nivel"]           = 0;
$aTotalizadores[13]["saldoanterior"]   = 0;
$aTotalizadores[13]["bordas"]          = "TB";
$aTotalizadores[13]["primeiroperiodo"] = 0;
$aTotalizadores[13]["segundoperiodo"]  = 0;
$aTotalizadores[13]["terceiroperiodo"] = 0;
$aTotalizadores[13]["imprime"]         = array(27=>2,
                                               28=>2,
                                               14=>0);
$aTotalizadores[13]["soma"]            = array(27,28);

$aLinhasRelatorio = array();

for ($iLinha = 1; $iLinha <= 28; $iLinha++) {

  $oLinhaRelatorio           = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha);
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  $aLinhasRelatorio[$iLinha]["obj"] = $oLinhaRelatorio;
  $aLinhasRelatorio[$iLinha]["parametro"]            = $oLinhaRelatorio->getParametros($anousu);
  $aLinhasRelatorio[$iLinha]["label"]                = $oLinhaRelatorio->getDescricaoLinha();
  $aLinhasRelatorio[$iLinha]["saldoanterior"]        = 0;
  $aLinhasRelatorio[$iLinha]["primeiroperiodo"] = 0;
  $aLinhasRelatorio[$iLinha]["segundoperiodo"]  = 0;
  $aLinhasRelatorio[$iLinha]["terceiroperiodo"] = 0;

  $aColunas = $oLinhaRelatorio->getValoresSomadosColunas($sTodasInstit, $anousu);
  foreach ($aColunas as $aColunas) {


    $aLinhasRelatorio[$iLinha]["saldoanterior"]   += $aColunas->colunas[1]->o117_valor;
    $aLinhasRelatorio[$iLinha]["primeiroperiodo"] += $aColunas->colunas[2]->o117_valor;
    if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
      $aLinhasRelatorio[$iLinha]["segundoperiodo"]  += $aColunas->colunas[3]->o117_valor;
    }
    if ($sSiglaPeriodo == "3Q") {
      $aLinhasRelatorio[$iLinha]["terceiroperiodo"] += $aColunas->colunas[4]->o117_valor;
    }

    /**
     * Somamos nos totalizadores
     */
    for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {

      if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

        if ($iLinha == 8 || $iLinha == 24) {

          $aTotalizadores[$iTotal]["saldoanterior"]   -= $aLinhasRelatorio[$iLinha]["saldoanterior"];
          $aTotalizadores[$iTotal]["primeiroperiodo"] -= $aLinhasRelatorio[$iLinha]["primeiroperiodo"];
          $aTotalizadores[$iTotal]["segundoperiodo"]  -= $aLinhasRelatorio[$iLinha]["segundoperiodo"];
          $aTotalizadores[$iTotal]["terceiroperiodo"] -= $aLinhasRelatorio[$iLinha]["terceiroperiodo"];
        } else {

          $aTotalizadores[$iTotal]["saldoanterior"]   += $aLinhasRelatorio[$iLinha]["saldoanterior"];
          $aTotalizadores[$iTotal]["primeiroperiodo"] += $aLinhasRelatorio[$iLinha]["primeiroperiodo"];
          $aTotalizadores[$iTotal]["segundoperiodo"]  += $aLinhasRelatorio[$iLinha]["segundoperiodo"];
          $aTotalizadores[$iTotal]["terceiroperiodo"] += $aLinhasRelatorio[$iLinha]["terceiroperiodo"];
        }
      }
    }
  }
}

/**
 * Insufiencia Financiera
 */
$aLinhasRelatorio[99]["label"]           = "INSUFICÊNCIA FINANCEIRA";
$aLinhasRelatorio[99]["saldoanterior"]   = 0;
$aLinhasRelatorio[99]["primeiroperiodo"] = 0;
$aLinhasRelatorio[99]["segundoperiodo"]  = 0;
$aLinhasRelatorio[99]["terceiroperiodo"]  = 0;

if ($instituicao != '') {

  $sele_work  = "c61_instit in ({$instituicao})";
  $rsAnterior = db_planocontassaldo_matriz($anousu, $dtini_01, $dtfin_01, false, $sele_work, "", "true", "false");
  @db_query("drop table work_pl");

  $sele_work     = ' c61_instit in (' . $instituicao . ')';
  $rsPeriodo = db_planocontassaldo_matriz($anousu, $dtini_02, $dtfin_02, false, $sele_work, "", "true", "false");
  @db_query("drop table work_pl");
}


/**
 * Montamos os dados das instituições nao RPPS
 * o for no recordset $rsAnterior buscamos dados do period anterior  e 1 quadrimestre/Semestre
 */
$iLinhasAnterior = pg_num_rows($rsAnterior);
for ($i = 0; $i < $iLinhasAnterior; $i++) {

  $oResultado = db_utils::fieldsMemory($rsAnterior, $i);
  for ($iLinha = 1; $iLinha <= 18; $iLinha++) {

    $oLinhaValores = clone $oResultado;
    $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oLinhaValores, 3);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oLinhaValores->saldo_anterior *= -1;
          $oLinhaValores->saldo_final    *= -1;
        }
        $aLinhasRelatorio[$iLinha]["saldoanterior"]   +=  $oLinhaValores->saldo_anterior;
        $aLinhasRelatorio[$iLinha]["primeiroperiodo"] +=  $oLinhaValores->saldo_final;
        for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {

          if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

            if ($iLinha == 8) {

              $oLinhaValores->saldo_anterior *= -1;
              $oLinhaValores->saldo_final    *= -1;
            }
            $aTotalizadores[$iTotal]["saldoanterior"]   += $oLinhaValores->saldo_anterior;
            $aTotalizadores[$iTotal]["primeiroperiodo"] += $oLinhaValores->saldo_final;
          }
        }
      }
    }
  }

  for ($iLinha = 26; $iLinha <= 28; $iLinha++) {

    $oLinhaValores = clone $oResultado;
    $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oLinhaValores, 3);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oLinhaValores->saldo_anterior *= -1;
          $oLinhaValores->saldo_final    *= -1;
        }
        $aLinhasRelatorio[$iLinha]["saldoanterior"]   +=  $oLinhaValores->saldo_anterior;
        $aLinhasRelatorio[$iLinha]["primeiroperiodo"] +=  $oLinhaValores->saldo_final;
        for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {


          if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

            $aTotalizadores[$iTotal]["saldoanterior"]   += $oLinhaValores->saldo_anterior;
            $aTotalizadores[$iTotal]["primeiroperiodo"] += $oLinhaValores->saldo_final;
          }
        }
      }
    }
  }


}

$iLinhasPeriodo = pg_num_rows($rsPeriodo);
for ($i = 0; $i < $iLinhasPeriodo; $i++) {

  $oResultado = db_utils::fieldsMemory($rsPeriodo, $i);
  for ($iLinha = 1; $iLinha <= 18; $iLinha++) {

    $oLinhaValores = clone $oResultado;
    $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oLinhaValores, 3);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oLinhaValores->saldo_anterior *= -1;
          $oLinhaValores->saldo_final    *= -1;
        }
        if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {

          if ($sSiglaPeriodo == '2S') {
            $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oLinhaValores->saldo_final;
          } else {
            $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oLinhaValores->saldo_anterior;
          }
        }
        if ($sSiglaPeriodo == "3Q") {
          $aLinhasRelatorio[$iLinha]["terceiroperiodo"] +=  $oLinhaValores->saldo_final;
        }
        for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {

          if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

            if ($iLinha == 8) {

              $oLinhaValores->saldo_anterior *= -1;
              $oLinhaValores->saldo_final    *= -1;
            }
            if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
              if ($sSiglaPeriodo == '2S') {
               $aTotalizadores[$iTotal]["segundoperiodo"]   += $oLinhaValores->saldo_final;
              } else {
                $aTotalizadores[$iTotal]["segundoperiodo"]   += $oLinhaValores->saldo_anterior;
              }
            }
            if ($sSiglaPeriodo == "3Q") {
              $aTotalizadores[$iTotal]["terceiroperiodo"] += $oLinhaValores->saldo_final;
            }
          }
        }
      }
    }
  }



  for ($iLinha = 26; $iLinha <= 28; $iLinha++) {

    $oLinhaValores = clone $oResultado;
    $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oLinhaValores, 3);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oLinhaValores->saldo_anterior *= -1;
          $oLinhaValores->saldo_final    *= -1;
        }
        if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {

          if ($sSiglaPeriodo == '2S') {
            $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oLinhaValores->saldo_final;
          } else {
            $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oLinhaValores->saldo_anterior;
          }
        }
        if ($sSiglaPeriodo == "3Q") {
          $aLinhasRelatorio[$iLinha]["terceiroperiodo"] +=  $oLinhaValores->saldo_final;
        }
        for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {

          if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

            if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
              if ($sSiglaPeriodo == '2S') {
               $aTotalizadores[$iTotal]["segundoperiodo"]   += $oLinhaValores->saldo_final;
              } else {
                $aTotalizadores[$iTotal]["segundoperiodo"]   += $oLinhaValores->saldo_anterior;
              }
            }
            if ($sSiglaPeriodo == "3Q") {
              $aTotalizadores[$iTotal]["terceiroperiodo"] += $oLinhaValores->saldo_final;
            }
          }
        }
      }
    }
  }

}


if (trim($instit_rpps) != "" && !isset($arqinclude)) {

  $sele_work = ' c61_instit in (' . $instit_rpps . ')';
  $rsAnteriorRpps = db_planocontassaldo_matriz($anousu, $dtini_01, $dtfin_01, false, $sele_work, "", "true", "false");
  @db_query("drop table work_pl");

  $sele_work     = ' c61_instit in (' . $instit_rpps . ')';
  $rsPeriodoRpps = db_planocontassaldo_matriz($anousu, $dtini_02, $dtfin_02, false, $sele_work, "", "true", "false");
  @db_query("drop table work_pl");

  $iLinhasAnterior = pg_num_rows($rsAnteriorRpps);
  for ($i = 0; $i < $iLinhasAnterior; $i++) {

    $oResultado = db_utils::fieldsMemory($rsAnteriorRpps, $i);
    for ($iLinha = 19; $iLinha <= 25; $iLinha++) {

      $oLinhaValores = clone $oResultado;
      $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
      foreach ($oParametro->contas as $oConta) {

        $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oLinhaValores, 3);
        if ($oVerificacao->match) {

          $oLinhaValores->saldo_anterior = abs($oLinhaValores->saldo_anterior);
          $oLinhaValores->saldo_final    = abs($oLinhaValores->saldo_final);
          if ($oVerificacao->exclusao) {

            $oLinhaValores->saldo_anterior *= -1;
            $oLinhaValores->saldo_final    *= -1;
          }
          $aLinhasRelatorio[$iLinha]["saldoanterior"]   +=  $oLinhaValores->saldo_anterior;
          $aLinhasRelatorio[$iLinha]["primeiroperiodo"] +=  $oLinhaValores->saldo_final;
          for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {

            if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

              if ($iLinha == 24) {

                $oLinhaValores->saldo_anterior *= -1;
                $oLinhaValores->saldo_final    *= -1;
              }
              $aTotalizadores[$iTotal]["saldoanterior"]   += $oLinhaValores->saldo_anterior;
              $aTotalizadores[$iTotal]["primeiroperiodo"] += $oLinhaValores->saldo_final;
            }
          }
        }
      }
    }
  }

  $iLinhasPeriodo = pg_num_rows($rsPeriodoRpps);
  for ($i = 0; $i < $iLinhasPeriodo; $i++) {

    $oResultado = db_utils::fieldsMemory($rsPeriodoRpps, $i);
    for ($iLinha = 19; $iLinha <= 25; $iLinha++) {

      $oLinhaValores = clone $oResultado;
      $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
      foreach ($oParametro->contas as $oConta) {

        $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oLinhaValores, 3);
        if ($oVerificacao->match) {

          $oLinhaValores->saldo_anterior = abs($oLinhaValores->saldo_anterior);
          $oLinhaValores->saldo_final    = abs($oLinhaValores->saldo_final);
          if ($oVerificacao->exclusao) {

            $oLinhaValores->saldo_anterior *= -1;
            $oLinhaValores->saldo_final    *= -1;
          }
          if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
            if ($sSiglaPeriodo == '2S') {
              $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oLinhaValores->saldo_final;
            } else {
              $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oLinhaValores->saldo_anterior;
            }
          }
          if ($sSiglaPeriodo == "3Q") {
            $aLinhasRelatorio[$iLinha]["terceiroperiodo"] +=  $oLinhaValores->saldo_final;
          }
          for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {

            if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {

              if ($iLinha == 24) {

                $oLinhaValores->saldo_anterior *= -1;
                $oLinhaValores->saldo_final    *= -1;
              }
              if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
                if ($sSiglaPeriodo == '2S') {
                  $aTotalizadores[$iTotal]["segundoperiodo"]   += $oLinhaValores->saldo_final;
                } else {
                  $aTotalizadores[$iTotal]["segundoperiodo"]   += $oLinhaValores->saldo_anterior;
                }
              }
              if ($sSiglaPeriodo == "3Q") {
                $aTotalizadores[$iTotal]["terceiroperiodo"] += $oLinhaValores->saldo_final;
              }
            }
          }
        }
      }
    }
  }
}
/*
 * Calculamos a RCL apenas se o relatorio  nao estiver dentro do simplificado,
 * pois a RCL já é calcula.
 */
if (!isset($arqinclude)) {

  duplicaReceitaaCorrenteLiquida(2010, 81);
  $aMesesRCLAnterior = calcula_rcl2($anousu_ant, "{$anousu_ant}-01-01", "{$anousu_ant}-12-31", $sTodasInstit, true, 81);
  $aMesesRCLAtual    = calcula_rcl2($anousu, "{$anousu}-01-01", "{$anousu}-12-31", $sTodasInstit, true, 81);
}

//echo "<pre>";
//print_r($aMesesRCLAtual);
//echo "</pre>";
//exit;
if ($aTotalizadores[2]["saldoanterior"] < 0) {

  $aLinhasRelatorio[99]["saldoanterior"] = abs($aTotalizadores[2]["saldoanterior"]);
  $aTotalizadores[2]["saldoanterior"]    = "-";

}

if ($aTotalizadores[2]["primeiroperiodo"] < 0) {

  $aLinhasRelatorio[99]["primeiroperiodo"] = abs($aTotalizadores[2]["primeiroperiodo"]);
  $aTotalizadores[2]["primeiroperiodo"]    = "0";
}
if ($aTotalizadores[2]["segundoperiodo"] < 0) {

  $aLinhasRelatorio[99]["segundoperiodo"] = abs($aTotalizadores[2]["terceiroperiodo"]);
  $aTotalizadores[2]["segundoperiodo"]    = "0";
}

if ($aTotalizadores[2]["terceiroperiodo"] < 0) {

  $aLinhasRelatorio[99]["terceiroperiodo"] = abs($aTotalizadores[2]["terceiroperiodo"]);
  $aTotalizadores[2]["terceiroperiodo"]    = "0";
}

/**
 * Totalizamos a total da DCL
 */
$aTotalizadores[3]["saldoanterior"]   = $aTotalizadores[0]["saldoanterior"]   - $aTotalizadores[2]["saldoanterior"];
$aTotalizadores[3]["primeiroperiodo"] = $aTotalizadores[0]["primeiroperiodo"] - $aTotalizadores[2]["primeiroperiodo"];
$aTotalizadores[3]["segundoperiodo"]  = $aTotalizadores[0]["segundoperiodo"]  - $aTotalizadores[2]["segundoperiodo"];
$aTotalizadores[3]["terceiroperiodo"] = $aTotalizadores[0]["terceiroperiodo"] - $aTotalizadores[2]["terceiroperiodo"];

/**
 * Totalizamos a RCL =
 */


$aTotalizadores[4]["saldoanterior"]   = array_sum($aMesesRCLAnterior);
$aTotalizadores[4]["primeiroperiodo"] = somaRCLPeriodo($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL,"primeiro");
$aTotalizadores[4]["segundoperiodo"]  = somaRCLPeriodo($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL,"segundo");
$aTotalizadores[4]["terceiroperiodo"] = somaRCLPeriodo($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL,"terceiro");


/**
 * Totalizamos a total da DC /RCL
 */
$aTotalizadores[5]["saldoanterior"]   = ($aTotalizadores[0]["saldoanterior"] /
                                         $aTotalizadores[4]["saldoanterior"])*100;
$aTotalizadores[5]["primeiroperiodo"] = ($aTotalizadores[0]["primeiroperiodo"] /
                                         $aTotalizadores[4]["primeiroperiodo"])*100;
$aTotalizadores[5]["segundoperiodo"]  = @($aTotalizadores[0]["segundoperiodo"] /
                                         $aTotalizadores[4]["segundoperiodo"])*100 ;
$aTotalizadores[5]["terceiroperiodo"] = @($aTotalizadores[0]["terceiroperiodo"] /
                                         $aTotalizadores[4]["terceiroperiodo"])*100 ;

/**
 * Totalizamos a total da DCL /RCL
 */
$aTotalizadores[6]["saldoanterior"]   = ($aTotalizadores[3]["saldoanterior"] /
                                        $aTotalizadores[4]["saldoanterior"])*100;
$aTotalizadores[6]["primeiroperiodo"] = ($aTotalizadores[3]["primeiroperiodo"] /
                                         $aTotalizadores[4]["primeiroperiodo"])*100;
$aTotalizadores[6]["segundoperiodo"]  = @($aTotalizadores[3]["segundoperiodo"] /
                                         $aTotalizadores[4]["segundoperiodo"])*100 ;
$aTotalizadores[6]["terceiroperiodo"] = @($aTotalizadores[3]["terceiroperiodo"] /
                                         $aTotalizadores[4]["terceiroperiodo"])*100 ;


/**
 * Totalizamos a total da DCLP
 */
$aTotalizadores[11]["saldoanterior"]   = $aTotalizadores[9]["saldoanterior"]   - $aTotalizadores[10]["saldoanterior"];
$aTotalizadores[11]["primeiroperiodo"] = $aTotalizadores[9]["primeiroperiodo"] - $aTotalizadores[10]["primeiroperiodo"];
$aTotalizadores[11]["segundoperiodo"]  = $aTotalizadores[9]["segundoperiodo"]  - $aTotalizadores[10]["segundoperiodo"];
$aTotalizadores[11]["terceiroperiodo"] = $aTotalizadores[9]["terceiroperiodo"] - $aTotalizadores[10]["terceiroperiodo"];
$nLimiteSenadoAnexoII = 0;
$nLimiteAlerta        = 0;
if ($sSiglaPeriodo == '1Q' || $sSiglaPeriodo == '1S') {

  $nLimiteSenadoAnexoII = ($aTotalizadores[4]["primeiroperiodo"]*1.2);
  $nLimiteAlerta        = ($aTotalizadores[4]["primeiroperiodo"]*1.08);

} else if ($sSiglaPeriodo == '2Q' || $sSiglaPeriodo == '2S') {

  $nLimiteSenadoAnexoII = ($aTotalizadores[4]["segundoperiodo"]*1.2);
  $nLimiteAlerta        = ($aTotalizadores[4]["segundoperiodo"]*1.08);

} else if ($sSiglaPeriodo == '3Q') {

  $nLimiteSenadoAnexoII = ($aTotalizadores[4]["terceiroperiodo"]*1.2);
  $nLimiteAlerta        = ($aTotalizadores[4]["terceiroperiodo"]*1.08);
}
if (! isset($arqinclude)) {

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', '', 6);
  $alt     = 4;
  $pagina  = 1;
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
  $pdf->cell(73, $alt, "DÍVIDA CONSOLIDADA", 'BR', 0, "C", 0);
  $pdf->cell(28, $alt, "EXERCÍCIO ANTERIOR", 'RB', 0, "C", 0);

  if ($usa_datas == true) {
    $pdf->cell(28 * 3, $alt, "PERIODO " . db_formatar($dtini, 'd') . " à " . db_formatar($dtfin, 'd'), 'B', 1, "C", 0);
  } else {

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, "Até 1º Semestre", 'BR', 0, "C", 0);
      $pdf->cell(42, $alt, "Até 2º Semestre", 'B', 1, "C", 0);
    } else {

      $pdf->cell(28, $alt, "Até 1º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 2º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 3º Quadrimestre", 'B', 1, "C", 0);
    }
  }

  for ($i = 0; $i <= 6; $i++) {

    $aTotalizador          = $aTotalizadores[$i];
    $nSaldoAnterior        = db_formatar($aTotalizador["saldoanterior"], "f");
    $nValorPrimeiroPeriodo = db_formatar($aTotalizador["primeiroperiodo"],"f");
    $nValorSegundoPeriodo  = db_formatar($aTotalizador["segundoperiodo"], "f");
    $nValorTerceiroPeriodo = db_formatar($aTotalizador["terceiroperiodo"], "f");
    if ($i == 2) {
       if ($aLinhasRelatorio[99]["saldoanterior"] > 0) {
         $nSaldoAnterior = "-";
       }
       if ($aLinhasRelatorio[99]["primeiroperiodo"] > 0) {
         $nValorPrimeiroPeriodo = "-";
       }
       if ($aLinhasRelatorio[99]["segundoperiodo"] > 0) {
         $nValorSegundoPeriodo = "-";
       }
       if ($aLinhasRelatorio[99]["terceiroperiodo"] > 0) {
         $nValorTerceiroPeriodo = "-";
       }
    }
    $pdf->cell(73, $alt, str_repeat("  ",$aTotalizador["nivel"]).$aTotalizador["label"], "{$aTotalizador["bordas"]}R",
                                          0, "L", 0);
    $pdf->cell(28, $alt, $nSaldoAnterior, "{$aTotalizador["bordas"]}R", 0, "R", 0);

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, $nValorPrimeiroPeriodo, "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(42, $alt, $nValorSegundoPeriodo,  "{$aTotalizador["bordas"]}L", 1, "R", 0);

    } else {

      $pdf->cell(28, $alt, $nValorPrimeiroPeriodo, "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(28, $alt, $nValorSegundoPeriodo,  "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(28, $alt, $nValorTerceiroPeriodo, "{$aTotalizador["bordas"]}L", 1, "R", 0);
    }

    foreach ($aTotalizador["imprime"] as $iLinha => $iNivel) {

      $pdf->cell(73, $alt, str_repeat("  ",$iNivel).$aLinhasRelatorio[$iLinha]["label"], 'R', 0, "L", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["saldoanterior"], "f"), 'R', 0, "R", 0);

      if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'L', 1, "R", 0);

      } else {

        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), 'L', 1, "R", 0);
      }
    }
  }
  $iTamanhoCelula  = 28;
  $iAumentarCelula = 56;
  if (substr($sSiglaPeriodo,1,1) == 'S') {

    $iTamanhoCelula  = 42;
    $iAumentarCelula = 42;
  }

  $pdf->cell(101+$iAumentarCelula, $alt,"LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL 120%", "RTB",0,"L",0);
  $pdf->cell($iTamanhoCelula,  $alt, db_formatar($nLimiteSenadoAnexoII, "f"), "LTB", 1, "R", 0);

  $sLabelLimiteAlerta = "LIMITE DE ALERTA (inciso III do § 1º do art. 59 da LRF) 108%";
  $pdf->cell(101+$iAumentarCelula, $alt, $sLabelLimiteAlerta, "RTB", 0, "L", 0);
  $pdf->cell($iTamanhoCelula, $alt, db_formatar($nLimiteAlerta, "f"), "LTB", 1, "R", 0);


  $pdf->ln();
  /*
   * Segundo quadro
   */
  $pdf->cell(73, $alt, "DETALHAMENTO DA DÍVIDA CONTRATUAL", 'TBR', 0, "C", 0);
  $pdf->cell(28, $alt, "", 'TRB', 0, "C", 0);
  if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

    $pdf->cell(42, $alt, "", 'TBR', 0, "C", 0);
    $pdf->cell(42, $alt, "", 'TB', 1, "C", 0);
  } else {

    $pdf->cell(28, $alt, "", 'TBR', 0, "C", 0);
    $pdf->cell(28, $alt, "", 'TBR', 0, "C", 0);
    $pdf->cell(28, $alt, "", 'TBL', 1, "C", 0);
  }



  $pdf->cell(73, $alt, $aTotalizadores[12]["label"], 'R', 0, "L", 0);
  $pdf->cell(28, $alt, db_formatar($aTotalizadores[12]["saldoanterior"], "f"), 'R', 0, "R", 0);
  if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

    $pdf->cell(42, $alt, db_formatar($aTotalizadores[12]["primeiroperiodo"], "f"),"R", 0, "R", 0);
    $pdf->cell(42, $alt, db_formatar($aTotalizadores[12]["segundoperiodo"], "f"), "L", 1, "R", 0);

  } else {

    $pdf->cell(28, $alt, db_formatar($aTotalizadores[12]["primeiroperiodo"], "f"), "R", 0, "R", 0);
    $pdf->cell(28, $alt, db_formatar($aTotalizadores[12]["segundoperiodo"], "f"), "R", 0, "R", 0);
    $pdf->cell(28, $alt, db_formatar($aTotalizadores[12]["terceiroperiodo"], "f"), "L", 1, "R", 0);
  }

  $pdf->cell(73, $alt, $aLinhasRelatorio[9]["label"], 'R', 0, "L", 0);
  $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[9]["saldoanterior"], "f"), 'R', 0, "R", 0);
  if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

    $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[9]["primeiroperiodo"], "f"),"R", 0, "R", 0);
    $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[9]["segundoperiodo"], "f"), "L", 1, "R", 0);

  } else {

    $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[9]["primeiroperiodo"], "f"), "R", 0, "R", 0);
    $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[9]["segundoperiodo"], "f"), "R", 0, "R", 0);
    $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[9]["terceiroperiodo"], "f"), "L", 1, "R", 0);
  }


 for ($i = 7; $i <= 8; $i++) {

    $aTotalizador = $aTotalizadores[$i];
    $pdf->cell(73, $alt, str_repeat("  ",$aTotalizador["nivel"]).$aTotalizador["label"], "{$aTotalizador["bordas"]}R",
                                          0, "L", 0);
    $pdf->cell(28, $alt, db_formatar($aTotalizador["saldoanterior"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, db_formatar($aTotalizador["primeiroperiodo"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(42, $alt, db_formatar($aTotalizador["segundoperiodo"], "f"), "{$aTotalizador["bordas"]}L", 1, "R", 0);

    } else {

      $pdf->cell(28, $alt, db_formatar($aTotalizador["primeiroperiodo"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aTotalizador["segundoperiodo"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aTotalizador["terceiroperiodo"], "f"), "{$aTotalizador["bordas"]}L", 1, "R", 0);
    }

    foreach ($aTotalizador["imprime"] as $iLinha => $iNivel) {

      $pdf->cell(73, $alt, str_repeat("  ",$iNivel).$aLinhasRelatorio[$iLinha]["label"], 'R', 0, "L", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["saldoanterior"], "f"), 'R', 0, "R", 0);

      if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'L', 1, "R", 0);

      } else {

        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), 'L', 1, "R", 0);
      }
    }
  }


  /*
   * Linha Divida com instituição financeira
   *
   */
  $pdf->cell(73, $alt, $aTotalizadores[13]["label"], 'R', 0, "L", 0);
  $pdf->cell(28, $alt, db_formatar($aTotalizadores[13]["saldoanterior"], "f"), 'R', 0, "R", 0);
  if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

    $pdf->cell(42, $alt, db_formatar($aTotalizadores[13]["primeiroperiodo"], "f"),"R", 0, "R", 0);
    $pdf->cell(42, $alt, db_formatar($aTotalizadores[13]["segundoperiodo"], "f"), "L", 1, "R", 0);

  } else {

    $pdf->cell(28, $alt, db_formatar($aTotalizadores[13]["primeiroperiodo"], "f"), "R", 0, "R", 0);
    $pdf->cell(28, $alt, db_formatar($aTotalizadores[13]["segundoperiodo"], "f"),  "R", 0, "R", 0);
    $pdf->cell(28, $alt, db_formatar($aTotalizadores[13]["terceiroperiodo"], "f"), "L", 1, "R", 0);
  }
  foreach ($aTotalizadores[13]["imprime"] as $iLinha => $iNivel) {

    $pdf->cell(73, $alt, str_repeat("  ",$iNivel).$aLinhasRelatorio[$iLinha]["label"], 'R', 0, "L", 0);
    $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["saldoanterior"], "f"), 'R', 0, "R", 0);

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
      $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'L', 1, "R", 0);

    } else {

      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'R', 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), 'L', 1, "R", 0);
    }
  }

  $pdf->Line(10, $pdf->GetY(), 195, $pdf->GetY());
  $pdf->Ln();

  /*
   * 3 quadro
   */
  $pdf->cell(73, $alt, "OUTROS VALORES NÃO INTEGRANTES DA DC", 'TBR', 0, "C", 0);
  $pdf->cell(28, $alt, "", 'TRB', 0, "C", 0);
  if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

    $pdf->cell(42, $alt, "", 'TBR', 0, "C", 0);
    $pdf->cell(42, $alt, "", 'TB', 1, "C", 0);
  } else {

    $pdf->cell(28, $alt, "", 'TBR', 0, "C", 0);
    $pdf->cell(28, $alt, "", 'TBR', 0, "C", 0);
    $pdf->cell(28, $alt, "", 'TBL', 1, "C", 0);
  }
  for ($i = 15; $i <= 19; $i++) {

    $iLinha= $i;
    if ($i == 16){
      $iLinha = 99;
    } else if ($i > 16) {
      $iLinha = $i-1;
    }
    $pdf->cell(73, $alt, $aLinhasRelatorio[$iLinha]["label"], "R", 0, "L", 0);
    $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["saldoanterior"], "f"), "R", 0, "R", 0);

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), "R", 0, "R", 0);
      $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"), "L", 1, "R", 0);

    } else {

      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), "R", 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"), "R", 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), "L", 1, "R", 0);
    }
  }
  $pdf->Line(10, $pdf->GetY(), 195, $pdf->GetY());
  $pdf->Ln();

  /**
   * rpps
   */
  $pdf->cell(185, $alt, "REGIME PREVIDENCIÁRIO", 'TB', 1, "C", 0);
  $pdf->cell(73, $alt, "", 'R', 0, "C", 0);
  $pdf->cell(28, $alt, "SALDO DO", 'R', 0, "C", 0);
//  $pdf->cell(73, $alt, "", 'R', 0, "C", 0);
//  $pdf->cell(28, $alt, "SALDO DO", 'R', 0, "C", 0);
  if ($usa_datas == false) {
    $pdf->cell(84, $alt, "SALDO DO EXERCÍCIO  DE $anousu", 'B', 1, "C", 0);
  } else {
    $pdf->cell(84, $alt, "SALDO DO PERIODO ", 'B', 1, "C", 0);
  }
  $pdf->cell(73, $alt, "DÍVIDA CONSOLIDADA PREVIDENCIÁRIA", 'BR', 0, "C", 0);
  $pdf->cell(28, $alt, "EXERCÍCIO ANTERIOR", 'RB', 0, "C", 0);

  if ($usa_datas == true) {
    $pdf->cell(28 * 3, $alt, "PERIODO " . db_formatar($dtini, 'd') . " à " . db_formatar($dtfin, 'd'), 'B', 1, "C", 0);
  } else {

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, "Até 1º Semestre", 'BR', 0, "C", 0);
      $pdf->cell(42, $alt, "Até 2º Semestre", 'B', 1, "C", 0);
    } else {

      $pdf->cell(28, $alt, "Até 1º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 2º Quadrimestre", 'BR', 0, "C", 0);
      $pdf->cell(28, $alt, "Até 3º Quadrimestre", 'B', 1, "C", 0);
    }
  }

  for ($i = 9; $i <= 11; $i++) {

    $aTotalizador = $aTotalizadores[$i];
    $pdf->cell(73, $alt, str_repeat("  ",$aTotalizador["nivel"]).$aTotalizador["label"], "{$aTotalizador["bordas"]}R",
                                          0, "L", 0);
    $pdf->cell(28, $alt, db_formatar($aTotalizador["saldoanterior"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);

    if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

      $pdf->cell(42, $alt, db_formatar($aTotalizador["primeiroperiodo"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(42, $alt, db_formatar($aTotalizador["segundoperiodo"], "f"), "{$aTotalizador["bordas"]}L", 1, "R", 0);

    } else {

      $pdf->cell(28, $alt, db_formatar($aTotalizador["primeiroperiodo"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aTotalizador["segundoperiodo"], "f"), "{$aTotalizador["bordas"]}R", 0, "R", 0);
      $pdf->cell(28, $alt, db_formatar($aTotalizador["terceiroperiodo"], "f"), "{$aTotalizador["bordas"]}L", 1, "R", 0);
    }

    foreach ($aTotalizador["imprime"] as $iLinha => $iNivel) {

      $pdf->cell(73, $alt, str_repeat("  ",$iNivel).$aLinhasRelatorio[$iLinha]["label"], 'R', 0, "L", 0);
      $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["saldoanterior"], "f"), 'R', 0, "R", 0);

      if ($sSiglaPeriodo == "1S" || $sSiglaPeriodo == "2S") {

        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'L', 1, "R", 0);

      } else {

        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), 'L', 1, "R", 0);
      }
    }
  }

  $oRelatorio = new relatorioContabil($iCodigoRelatorio);
  $oRelatorio->getNotaExplicativa($pdf,$iCodigoPeriodo);
  $pdf->ln();
  // assinaturas
  assinaturas($pdf, $classinatura, 'GF');
  $pdf->Output();
}

/**
 * variaveis para o Simplificado
 */
switch ($sSiglaPeriodo) {

  case '1S':

    $nTotalDividaII = $aTotalizadores[3]["primeiroperiodo"];
    $nPercentualRCL = $aTotalizadores[6]["primeiroperiodo"];
    $nValorSenado   = $aTotalizadores[7]["primeiroperiodo"];
  break;

  case '1Q':

    $nTotalDividaII = $aTotalizadores[3]["primeiroperiodo"];
    $nPercentualRCL = $aTotalizadores[6]["primeiroperiodo"];
    $nValorSenado   = $aTotalizadores[7]["primeiroperiodo"];
  break;

  case '2S':

    $nTotalDividaII = $aTotalizadores[3]["segundoperiodo"];
    $nPercentualRCL = $aTotalizadores[6]["segundoperiodo"];
    $nValorSenado   = $aTotalizadores[7]["segundoperiodo"];
  break;

  case '2Q':

    $nTotalDividaII = $aTotalizadores[3]["segundoperiodo"];
    $nPercentualRCL = $aTotalizadores[6]["segundoperiodo"];
    $nValorSenado   = $aTotalizadores[7]["segundoperiodo"];
  break;

  case '3Q':

    $nTotalDividaII = $aTotalizadores[3]["terceiroperiodo"];
    $nPercentualRCL = $aTotalizadores[6]["terceiroperiodo"];
    $nValorSenado   = $aTotalizadores[7]["terceiroperiodo"];
  break;
}
function somaRCLPeriodo($aRCLAtual, $aRCLAnterior, $aRCL, $sSiglaPeriodo){

    $nValorPrimeiroPeriodo = 0;
    foreach ($aRCL["{$sSiglaPeriodo}periodo"]["anterior"] as $mes) {
      if (isset($aRCLAnterior[$mes])) {
          $nValorPrimeiroPeriodo += $aRCLAnterior[$mes];
      }
    }

     foreach ($aRCL["{$sSiglaPeriodo}periodo"]["atual"] as $mes) {
      if (isset($aRCLAtual[$mes])) {
          $nValorPrimeiroPeriodo += $aRCLAtual[$mes];
      }
    }
    return $nValorPrimeiroPeriodo;
}
?>