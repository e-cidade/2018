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


if (!isset($arqinclude)) {
  
  require_once("fpdf151/pdf.php");
  require_once("fpdf151/assinatura.php");
  require_once("libs/db_sql.php");
  require_once("libs/db_libcontabilidade.php");
  require_once("libs/db_liborcamento.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_orcparamrel_classe.php");
  require_once("classes/db_db_config_classe.php");
  require_once("classes/db_conrelinfo_classe.php");
  require_once("classes/db_conrelvalor_classe.php");
  require_once("libs/db_utils.php"); 
  require_once("model/linhaRelatorioContabil.model.php");
  require_once("model/relatorioContabil.model.php");
  $clconrelinfo  = new cl_conrelinfo;
  $clconrelvalor = new cl_conrelvalor;
  $classinatura  = new cl_assinatura;
  $cldb_config   = new cl_db_config;
  $orcparamrel   = new cl_orcparamrel;
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
}


$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select munic from db_config where prefeitura is true");
$lTem1Quad = false;
$lTem2Quad = false;
$lTem3Quad = false;
db_fieldsmemory($resultinst,0);

$head2 = "MUNICÍPIO DE ".strtoupper($munic);
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DAS GARANTIAS E CONTRA GARANTIAS DE VALORES";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";


$anousu       = db_getsession("DB_anousu");
$anousu_ant   = $anousu-1;
$anousu         = db_getsession("DB_anousu");
$oDaoPeriodo    = db_utils::getDao("periodo");
if (!isset($arqinclude)) {
	
  $iCodigoPeriodo = $periodo;
  $sSqlPeriodo    = $oDaoPeriodo->sql_query($iCodigoPeriodo); 
  $sSiglaPeriodo  = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla; 
  $dt             = data_periodo($anousu, $sSiglaPeriodo);
	$periodo        = $sSiglaPeriodo;
	//$dt           = data_periodo($anousu, $periodo); // no dbforms/db_funcoes.php
	$dt_ini       = $dt[0]; // data inicial do período
	$dt_fim       = $dt[1]; // data final do período
	$dDataFim1Q   = "{$anousu}-04-30";
	$dDataFim2Q   = "{$anousu}-08-31";
	$dDataFim3Q   = "{$anousu}-12-21";
	$dt_ini_ant   = $anousu_ant."-01-01";
	$dt_fim_ant   = $anousu_ant."-12-31";
	$mes_ini      = "JANEIRO";
	$dt           = split("-",$dt_fim);
	$mes_fim      = db_mes($dt[1],1);
	$head6        = $mes_ini." A ".$mes_fim." DE ".$anousu;

}


//*****************************************************************
/*
 * definimos os valores por perido. 
 */
$sTodasInstit  = null;
$nLimiteSenado = 0.32;
$nLimiteAlerta = 28.8;
$anousu        = db_getsession("DB_anousu");

$resultinst = db_query("select codigo,munic,db21_tipoinstit from db_config");
$numrowsinstit = pg_num_rows($resultinst);
$virgula     = "";
for($x = 0; $x < $numrowsinstit; $x ++) {

  db_fieldsmemory($resultinst, $x);
  $sTodasInstit .= $virgula.$codigo;
  $virgula = ",";
    
}
$sDtInicial01     = "{$anousu}-01-01";
$sDtFinal01       = "{$anousu}-04-30";
$sDtInicial02     = "{$anousu}-09-01";
$sDtFinal02       = "{$anousu}-12-31";
$iCodigoRelatorio = 91;
if ($sSiglaPeriodo == "1Q") {
  
  $lTem1Quad = true;
  $lTem2Quad = false;
  $lTem3Quad = false;
  
  $aRCL["primeiroperiodo"]["anterior"] = array("maio","junho","julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril" );
  
  $aRCL["segundoperiodo"]["anterior"]   = array();  
  $aRCL["segundoperiodo"]["atual"]      = array();
    
  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]      = array();
} else if ($sSiglaPeriodo == "2Q") {
  
  $lTem1Quad = true;
  $lTem2Quad = true;
  $lTem3Quad = false;
  
  $aRCL["primeiroperiodo"]["anterior"] = array("maio","junho","julho","agosto","setembro","novembro","outubro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril" );
  
  $aRCL["segundoperiodo"]["anterior"] = array("setembro","novembro","outubro", "dezembro");
  $aRCL["segundoperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto");
  
  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]      = array();
  
} else if ($sSiglaPeriodo == "3Q") {
  
  $lTem1Quad = true;
  $lTem2Quad = true;
  $lTem3Quad = true;
  $aRCL["primeiroperiodo"]["anterior"] = array("maio","junho","julho","agosto","setembro","novembro","outubro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril" );
  
  $aRCL["segundoperiodo"]["anterior"] = array("setembro","novembro","outubro", "dezembro");
  $aRCL["segundoperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto");
  $aRCL["terceiroperiodo"]["anterior"] = array();
  $aRCL["terceiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto",
                                               "setembro","novembro","outubro", "dezembro");
  
} else if ($sSiglaPeriodo == "1S"){
  
  $lTem1Quad    = true;
  $lTem2Quad    = false;
  $lTem3Quad    = false;
  $sDtFinal01   = "{$anousu}-06-30";
  $sDtInicial02 = "{$anousu}-07-01";
  $sDtFinal02   = "{$anousu}-12-31";
  $aRCL["primeiroperiodo"]["anterior"] = array("julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril", "maio","junho");
  $aRCL["segundoperiodo"]["anterior"]   = array();  
  $aRCL["segundoperiodo"]["atual"]      = array();
    
  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]     = array(); 
  
} else if ($sSiglaPeriodo == "2S") {
  
  $lTem1Quad    = true;
  $lTem2Quad    = true;
  $lTem3Quad    = false;
  $sDtFinal01   = "{$anousu}-06-30";
  $sDtInicial02 = "{$anousu}-07-01";
  $sDtFinal02   = "{$anousu}-12-31";
  
  $aRCL["primeiroperiodo"]["anterior"] = array("julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["primeiroperiodo"]["atual"]    = array("janeiro","fevereiro","marco","abril", "maio","junho");
  
  $aRCL["segundoperiodo"]["anterior"] = array(); 
  $aRCL["segundoperiodo"]["atual"] = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto",
                                               "setembro","novembro","outubro", "dezembro");
  $aRCL["terceiroperiodo"]["anterior"]  = array();
  $aRCL["terceiroperiodo"]["atual"]    = array();
}

/**
 * Criamos os totalizadores 
 */
$aTotalizadores = array();

$iIndice = 0;

$aTotalizadores[$iIndice]["label"]           = "EXTERNAS (I)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array(1 => 2, 2 => 2);
$aTotalizadores[$iIndice]["soma"]            = array(1,2);

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "INTERNAS (II)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array(3 => 2, 4 => 2);
$aTotalizadores[$iIndice]["soma"]            = array(3,4);

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "TOTAL GARANTIAS CONCEDIDAS (III) = (I)+(II)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "T";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array();
$aTotalizadores[$iIndice]["soma"]            = array();

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "RECEITA CORRENTE LÍQUIDA - RCL (IV)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "T";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array();
$aTotalizadores[$iIndice]["soma"]            = array();

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = " % DO TOTAL DAS GARANTIAS SOBRE A RCL";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "T";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array();
$aTotalizadores[$iIndice]["soma"]            = array();

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "LIMITE DE.F POR RESOLUÇÃO DO SENADO FEDERAL 32%";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "TB";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array();
$aTotalizadores[$iIndice]["soma"]            = array();

/*
 * quadro novo, limite de alerta
 */
$iIndice++; 
$aTotalizadores[$iIndice]["label"]           = "LIMITE DE ALERTA (inciso III do §1º do art. 59 da LRF) 28,8%";
$aTotalizadores[$iIndice]["nivel"]           = 0;
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "TB";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array();
$aTotalizadores[$iIndice]["soma"]            = array();

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "EXTERNAS (V)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "T";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array(5 => 2, 6 => 2);
$aTotalizadores[$iIndice]["soma"]            = array(5,6);

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "INTERNAS (VI)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array(7 => 2, 8 => 2);
$aTotalizadores[$iIndice]["soma"]            = array(7,8);

$iIndice++;
$aTotalizadores[$iIndice]["label"]           = "TOTAL DAS CONTRAGARANTIAS RECEBIDAS (VII) = (V+VI)";  
$aTotalizadores[$iIndice]["nivel"]           = 0;  
$aTotalizadores[$iIndice]["saldoanterior"]   = 0;
$aTotalizadores[$iIndice]["primeiroperiodo"] = 0;
$aTotalizadores[$iIndice]["bordas"]          = "TB";
$aTotalizadores[$iIndice]["segundoperiodo"]  = 0;
$aTotalizadores[$iIndice]["terceiroperiodo"] = 0;
$aTotalizadores[$iIndice]["imprime"]         = array( );
$aTotalizadores[$iIndice]["soma"]            = array();

$sMedidasPreventivas = "MEDIDAS CORRETIVAS\n";

for ($iLinha = 1; $iLinha <= 9; $iLinha++) {
  
  $oLinhaRelatorio           = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha); 
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  $aLinhasRelatorio[$iLinha]["obj"]             = $oLinhaRelatorio;
  $aLinhasRelatorio[$iLinha]["parametro"]       = $oLinhaRelatorio->getParametros($anousu);
  $aLinhasRelatorio[$iLinha]["label"]           = $oLinhaRelatorio->getDescricaoLinha();
  $aLinhasRelatorio[$iLinha]["saldoanterior"]   = 0;
  $aLinhasRelatorio[$iLinha]["primeiroperiodo"] = 0;
  $aLinhasRelatorio[$iLinha]["segundoperiodo"]  = 0;
  $aLinhasRelatorio[$iLinha]["terceiroperiodo"] = 0;
  
  if ($iLinha != 9) {
    
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
          
          $aTotalizadores[$iTotal]["saldoanterior"]   += $aLinhasRelatorio[$iLinha]["saldoanterior"];
          $aTotalizadores[$iTotal]["primeiroperiodo"] += $aLinhasRelatorio[$iLinha]["primeiroperiodo"];
          $aTotalizadores[$iTotal]["segundoperiodo"]  += $aLinhasRelatorio[$iLinha]["segundoperiodo"];
          $aTotalizadores[$iTotal]["terceiroperiodo"] += $aLinhasRelatorio[$iLinha]["terceiroperiodo"];
        }
      }
    }
  }
}


$aColunas       = $aLinhasRelatorio[9]["obj"]->getValoresColunas();
$iLinhasMedidas = 0;

foreach ($aColunas as $oColunas) {
  
  if (isset($oColunas->colunas[0]->o117_valor)){
    
    if ($iLinhasMedidas > 0) { 
      $sMedidasPreventivas .= "\n".$oColunas->colunas[0]->o117_valor;
    } else {
      $sMedidasPreventivas .= $oColunas->colunas[0]->o117_valor;
    }
  }
  $iLinhasMedidas++;
}
$where      = "  c61_instit in ($sTodasInstit)"; 
$rsAnterior = db_planocontassaldo_matriz($anousu, $sDtInicial01, $sDtFinal01, false, $where,"", "true", "false");
@db_query("drop table work_pl");
$iLinhasAnterior = pg_num_rows($rsAnterior); 

for ($i = 0; $i < $iLinhasAnterior; $i++) {

  $oResultado = db_utils::fieldsMemory($rsAnterior, $i);
  for ($iLinha = 1; $iLinha <= 8; $iLinha++) {
    
    $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
    foreach ($oParametro->contas as $oConta) {
      
      $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oResultado, 3);
      if ($oVerificacao->match) {    

        if ($oVerificacao->exclusao) {
          
          $oResultado->saldo_anterior *= -1;  
          $oResultado->saldo_final    *= -1;  
        }
        $aLinhasRelatorio[$iLinha]["saldoanterior"]   +=  $oResultado->saldo_anterior;
        $aLinhasRelatorio[$iLinha]["primeiroperiodo"] +=  $oResultado->saldo_final;
        for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {
      
          if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {
            
            $aTotalizadores[$iTotal]["saldoanterior"]   += $oResultado->saldo_anterior;
            $aTotalizadores[$iTotal]["primeiroperiodo"] += $oResultado->saldo_final;
          }
        }
      }
    }
  }
}

$rsPeriodo = db_planocontassaldo_matriz($anousu, $sDtInicial01, $sDtFinal01, false, $where,"", "true", "false");
@db_query("drop table work_pl");
$iLinhasPeriodo = pg_num_rows($rsPeriodo); 

for ($i = 0; $i < $iLinhasPeriodo; $i++) {

  $oResultado = db_utils::fieldsMemory($rsPeriodo, $i);
  for ($iLinha = 1; $iLinha <= 8; $iLinha++) {
    
    $oParametro  = $aLinhasRelatorio[$iLinha]["parametro"];
    foreach ($oParametro->contas as $oConta) {
      
      $oVerificacao    = $aLinhasRelatorio[$iLinha]["obj"]->match($oConta, $oParametro->orcamento, $oResultado, 3);
      if ($oVerificacao->match) {    

        if ($oVerificacao->exclusao) {
          
          $oResultado->saldo_anterior *= -1;  
          $oResultado->saldo_final    *= -1;  
        }
        if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
          
          if ($sSiglaPeriodo == '2S') {
            $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oResultado->saldo_final;
          } else {
            $aLinhasRelatorio[$iLinha]["segundoperiodo"]   +=  $oResultado->saldo_anterior;
          }
        }
        if ($sSiglaPeriodo == "3Q") {
          $aLinhasRelatorio[$iLinha]["terceiroperiodo"] +=  $oResultado->saldo_final;
        }
        for ( $iTotal = 0; $iTotal < count($aTotalizadores); $iTotal++) {
      
          if (in_array($iLinha, $aTotalizadores[$iTotal]["soma"])) {
            
            if ($sSiglaPeriodo == "2S" || $sSiglaPeriodo == "2Q" || $sSiglaPeriodo == "3Q") {
              if ($sSiglaPeriodo == '2S') {
               $aTotalizadores[$iTotal]["segundoperiodo"]   += $oResultado->saldo_final;
              } else {
                $aTotalizadores[$iTotal]["segundoperiodo"]   += $oResultado->saldo_anterior;
              }
            }
            if ($sSiglaPeriodo == "3Q") {
              $aTotalizadores[$iTotal]["terceiroperiodo"] += $oResultado->saldo_final;
            }
          }
        }
      }
    }
  }
}
$perc_limite_senado = 32;
if (!isset($arqinclude)) {
  
  $aMesesRCLAnterior  = calcula_rcl2($anousu_ant, "{$anousu_ant}-01-01", "{$anousu_ant}-12-31", $sTodasInstit, true, 81);
  $aMesesRCLAtual     = calcula_rcl2($anousu, "{$anousu}-01-01", "{$anousu}-12-31", $sTodasInstit, true, 81);
}
$aTotalizadores[2]["saldoanterior"]   = $aTotalizadores[0]["saldoanterior"]   + $aTotalizadores[1]["saldoanterior"];
$aTotalizadores[2]["primeiroperiodo"] = $aTotalizadores[0]["primeiroperiodo"] + $aTotalizadores[1]["primeiroperiodo"];
$aTotalizadores[2]["segundoperiodo"]  = $aTotalizadores[0]["segundoperiodo"]  + $aTotalizadores[1]["segundoperiodo"];
$aTotalizadores[2]["terceiroperiodo"] = $aTotalizadores[0]["terceiroperiodo"] + $aTotalizadores[1]["terceiroperiodo"];


$aTotalizadores[8]["saldoanterior"]   = $aTotalizadores[6]["saldoanterior"]   + $aTotalizadores[7]["saldoanterior"];
$aTotalizadores[8]["primeiroperiodo"] = $aTotalizadores[6]["primeiroperiodo"] + $aTotalizadores[7]["primeiroperiodo"];
$aTotalizadores[8]["segundoperiodo"]  = $aTotalizadores[6]["segundoperiodo"]  + $aTotalizadores[7]["segundoperiodo"];
$aTotalizadores[8]["terceiroperiodo"] = $aTotalizadores[6]["terceiroperiodo"] + $aTotalizadores[7]["terceiroperiodo"];

$aTotalizadores[3]["saldoanterior"]   = array_sum($aMesesRCLAnterior); 
$aTotalizadores[3]["primeiroperiodo"] = somaRCLPeriodoGarantias($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL,"primeiro"); 
$aTotalizadores[3]["segundoperiodo"]  = somaRCLPeriodoGarantias($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL,"segundo"); 
$aTotalizadores[3]["terceiroperiodo"] = somaRCLPeriodoGarantias($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL,"terceiro");


$aTotalizadores[4]["saldoanterior"]   = @($aTotalizadores[2]["saldoanterior"]   / $aTotalizadores[3]["saldoanterior"])  * 100;    
$aTotalizadores[4]["primeiroperiodo"] = @($aTotalizadores[2]["primeiroperiodo"] / $aTotalizadores[3]["primeiroperiodo"] * 100);
$aTotalizadores[4]["segundoperiodo"]  = @($aTotalizadores[2]["segundoperiodo"]  / $aTotalizadores[3]["segundoperiodo"]) * 100;
$aTotalizadores[4]["terceiroperiodo"] = @($aTotalizadores[2]["terceiroperiodo"] / $aTotalizadores[3]["terceiroperiodo"])* 100;
                                        
$aTotalizadores[5]["saldoanterior"]   = $aTotalizadores[3]["saldoanterior"]   * $nLimiteSenado;    
$aTotalizadores[5]["primeiroperiodo"] = $aTotalizadores[3]["primeiroperiodo"] * $nLimiteSenado;
$aTotalizadores[5]["segundoperiodo"]  = $aTotalizadores[3]["segundoperiodo"]  * $nLimiteSenado;
$aTotalizadores[5]["terceiroperiodo"] = $aTotalizadores[3]["terceiroperiodo"] * $nLimiteSenado; 

$aTotalizadores[6]["saldoanterior"]   = ($aTotalizadores[3]["saldoanterior"]   * $nLimiteAlerta) / 100;
$aTotalizadores[6]["primeiroperiodo"] = ($aTotalizadores[3]["primeiroperiodo"] * $nLimiteAlerta) / 100;
$aTotalizadores[6]["segundoperiodo"]  = ($aTotalizadores[3]["segundoperiodo"]  * $nLimiteAlerta) / 100;
$aTotalizadores[6]["terceiroperiodo"] = ($aTotalizadores[3]["terceiroperiodo"] * $nLimiteAlerta) / 100;

if (!isset($arqinclude)){
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt = 4;
  $pdf->addpage();
  $pdf->ln();
  $pdf->setfont('arial','b',7);
  $pdf->cell(110,$alt," RGF - ANEXO III (LRF, art. 55, inciso I, alínea \"c\" e art. 40, § 1º)",'B',0,"L",0);
  $pdf->cell(75,$alt,'R$ 1,00','B',1,"R",0);
  
  $pdf->cell(73,$alt,"",'R',0,"C",0);
  $pdf->cell(28,$alt,"SALDO DO",'R',0,"C",0);
  $pdf->cell(84,$alt,"SALDO DO EXERCÍCIO  DE ".db_getsession("DB_anousu"),'B',1,"C",0);
  
  $pdf->cell(73,$alt,"GARANTIAS CONCEDIDAS",'BR',0,"C",0);
  $pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,"Até 1º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 2º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 3º Quadrimestre",'B',1,"C",0);
  } else {
    $pdf->cell(42,$alt,"Até 1º Semestre",'BR',0,"C",0);
    $pdf->cell(42,$alt,"Até 2º Semestre",'B',1,"C",0);
  }
  
  $pdf->setfont('arial','',6);
  for ($i = 0; $i <= 6; $i++) {
    
    $aTotalizador          = $aTotalizadores[$i];
    $nSaldoAnterior        = db_formatar($aTotalizador["saldoanterior"], "f");
    $nValorPrimeiroPeriodo = db_formatar($aTotalizador["primeiroperiodo"],"f"); 
    $nValorSegundoPeriodo  = db_formatar($aTotalizador["segundoperiodo"], "f"); 
    $nValorTerceiroPeriodo = db_formatar($aTotalizador["terceiroperiodo"], "f");
    $pdf->cell(73, $alt, str_repeat("  ",$aTotalizador["nivel"]).$aTotalizador["label"], "{$aTotalizador["bordas"]}R",
                                          0, "L", 0);
    $pdf->cell(28, $alt, $nSaldoAnterior, "{$aTotalizador["bordas"]}R", 0, "R", 0);
    
    if ($periodo == "1S" || $periodo == "2S") {
  
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
        
      if ($periodo == "1S" || $periodo == "2S") {
      
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'L', 1, "R", 0);
          
      } else {
          
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), 'L', 1, "R", 0);
      }
    }
  }
  
  $pdf->cell(185,$alt,"",'',1,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(73,$alt,"",'TR',0,"C",0);
  $pdf->cell(28,$alt,"SALDO DO",'TR',0,"C",0);
  $pdf->cell(84,$alt,"SALDO DO EXERCÍCIO  DE ".db_getsession("DB_anousu"),'TB',1,"C",0);
  
  $pdf->cell(73,$alt,"CONTRAGARANTIAS RECEBIDAS",'BR',0,"C",0);
  $pdf->cell(28,$alt,"EXERCÍCIO ANTERIOR",'RB',0,"C",0);
  
  if($periodo!="1S"&&$periodo!="2S") {
    $pdf->cell(28,$alt,"Até 1º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 2º Quadrimestre",'BR',0,"C",0);
    $pdf->cell(28,$alt,"Até 3º Quadrimestre",'B',1,"C",0);
  }else {
    $pdf->cell(42,$alt,"Até 1º Semestre",'BR',0,"C",0);
    $pdf->cell(42,$alt,"Até 2º Semestre",'B',1,"C",0);
  }
  $pdf->setfont('arial','',6);
  
  for ($i = 7; $i <= 8; $i++) {
    
    $aTotalizador          = $aTotalizadores[$i];
    $nSaldoAnterior        = db_formatar($aTotalizador["saldoanterior"], "f");
    $nValorPrimeiroPeriodo = db_formatar($aTotalizador["primeiroperiodo"],"f"); 
    $nValorSegundoPeriodo  = db_formatar($aTotalizador["segundoperiodo"], "f"); 
    $nValorTerceiroPeriodo = db_formatar($aTotalizador["terceiroperiodo"], "f");
    $pdf->cell(73, $alt, str_repeat("  ",$aTotalizador["nivel"]).$aTotalizador["label"], "{$aTotalizador["bordas"]}R",
                                          0, "L", 0);
    $pdf->cell(28, $alt, $nSaldoAnterior, "{$aTotalizador["bordas"]}R", 0, "R", 0);
    
    if ($periodo == "1S" || $periodo == "2S") {
  
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
        
      if ($periodo == "1S" || $periodo == "2S") {
      
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(42, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'L', 1, "R", 0);
          
      } else {
          
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["primeiroperiodo"], "f"), 'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["segundoperiodo"], "f"),  'R', 0, "R", 0);
        $pdf->cell(28, $alt, db_formatar($aLinhasRelatorio[$iLinha]["terceiroperiodo"], "f"), 'L', 1, "R", 0);
      }
    }
  }
  $pdf->MultiCell(185, $alt, $sMedidasPreventivas, 'TB', 'J', 0, 0);
  $pdf->Ln();
  $oRelatorio  = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa(&$pdf, $iCodigoPeriodo, 185);
  // assinaturas
  $pdf->Ln(25);
  assinaturas(&$pdf,&$classinatura,'GF');
  $pdf->Output();
  
}
/**
 * variaveis para o Simplificado
 */
switch ($sSiglaPeriodo) { 
  
  case '1S':
    
    $nTotalGarantias   = $aTotalizadores[2]["primeiroperiodo"];
    $nPercentualRCLIII = $aTotalizadores[4]["primeiroperiodo"];
    $nValorSenadoIII   = $aTotalizadores[5]["primeiroperiodo"];
  break;
  
  case '1Q':
    
    $nTotalGarantias   = $aTotalizadores[2]["primeiroperiodo"];
    $nPercentualRCLIII = $aTotalizadores[4]["primeiroperiodo"];
    $nValorSenadoIII   = $aTotalizadores[5]["primeiroperiodo"]; 
  break;
  
  case '2S':
    
    $nTotalGarantias   = $aTotalizadores[2]["segundoperiodo"];
    $nPercentualRCLIII = $aTotalizadores[4]["segundoperiodo"];
    $nValorSenadoIII   = $aTotalizadores[5]["segundoperiodo"]; 
  break;
  
  case '2Q':
    
    $nTotalGarantias   = $aTotalizadores[2]["segundoperiodo"];
    $nPercentualRCLIII = $aTotalizadores[4]["segundoperiodo"];
    $nValorSenadoIII   = $aTotalizadores[5]["segundoperiodo"]; 
  break;
  
  case '3Q':
    
    $nTotalGarantias   = $aTotalizadores[2]["terceiroperiodo"];
    $nPercentualRCLIII = $aTotalizadores[4]["terceiroperiodo"];
    $nValorSenadoIII   = $aTotalizadores[5]["terceiroperiodo"]; 
  break;
}
  function somaRCLPeriodoGarantias($aRCLAtual, $aRCLAnterior, $aRCL, $periodo) {
    
    $nValorPrimeiroPeriodo = 0;
    foreach ($aRCL["{$periodo}periodo"]["anterior"] as $mes) {
      if (isset($aRCLAnterior[$mes])) {
          $nValorPrimeiroPeriodo += $aRCLAnterior[$mes];
      }
    }
    
     foreach ($aRCL["{$periodo}periodo"]["atual"] as $mes) {
      if (isset($aRCLAtual[$mes])) {
          $nValorPrimeiroPeriodo += $aRCLAtual[$mes];
      }
    }
    return $nValorPrimeiroPeriodo;
  }
?>