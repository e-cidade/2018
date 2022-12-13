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


if (!isset($arqinclude)){
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_utils.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_liborcamento.php");
  include("classes/db_empresto_classe.php");
  include("classes/db_orcparamrel_classe.php");
  include("classes/db_conrelinfo_classe.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_db_config_classe.php");
  include("classes/db_orcparamelemento_classe.php");  
  require_once("model/linhaRelatorioContabil.model.php");
  require_once("model/relatorioContabil.model.php");
  
  $classinatura       = new cl_assinatura;
  $clempresto         = new cl_empresto;
  $orcparamrel        = new cl_orcparamrel;
  $clconrelinfo       = new cl_conrelinfo;
  $cldb_config        = new cl_db_config;
  $clorcparamelemento = new cl_orcparamelemento();
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $anousu  = db_getsession("DB_anousu");
  
}

$tipo_emissao='periodo';

$iCodigoRelatorio = 89;
$oDaoPeriodo      = db_utils::getDao("periodo");
$iCodigoPeriodo   = $periodo;
$sSqlPeriodo      = $oDaoPeriodo->sql_query($periodo);
$oPeriodo         = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0); 
$sSiglaPeriodo    = $oPeriodo->o114_sigla;
$dt_ini= $anousu.'-01-01'; // data inicial do período 
if ($periodo < 17) {
  
  $dt               = data_periodo($anousu, $sSiglaPeriodo);
  $dt_fin= $dt[1]; // data final do período
  $textodt = $dt['texto'];
} else {
  
  $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $oPeriodo->o114_mesfinal, $anousu);
  $dt_fin = "{$anousu}-{$oPeriodo->o114_mesfinal}-$iUltimoDiaMes";
}

// calcula periodo do exercicio anterior para fechar os 12 meses
$anousu_ant  = db_getsession("DB_anousu")-1;
// se o ano atual é bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
$dt = split('-',$dt_fin);  // mktime -- (mes,dia,ano)
//$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1],$dt[2]-365,$dt[0]));

$dt_ini_ant = $anousu_ant."-01-01";
$dt_fin_ant = $anousu_ant."-12-31";

////////////////////////////////////////////////////////////////////

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select munic,nomeinst,nomeinstabrev,db21_tipoinstit from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';

$temprefa   = false;
$temcamara  = false;
$temadmind  = false;
$flag_abrev = false;

for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);

  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
  } else {
       $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ', ';
  if ($db21_tipoinstit == 1) {
    $temprefa=true;
  } elseif ($db21_tipoinstit == 2) {
    $temcamara=true;
  } elseif ($db21_tipoinstit == 5 or $db21_tipoinstit == 7) {
    $temadmind=true;
  }
}
$imprimirrps = true;
$tamanho=30;
if ($flag_abrev == false){
  if (strlen($descr_inst) > 42){
    $descr_inst = substr($descr_inst,0,100);
  }
}

if ($temcamara == true && ($temprefa == true || $temadmind == true)){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODERES EXECUTIVO E LEGISLATIVO";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER LEGISLATIVO";
}

if ($temprefa == true && $temcamara == false && ($temadmind == false || $temadmind == true)){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}

if ($temcamara == true && $temprefa == false && $temadmind == false) {
  
  $head3 = $descr_inst;
  $head4 = "RELATÓRIO DE GESTÃO FISCAL";
  $head5 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
  $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  
} else {
  
  $head3 = "RELATÓRIO DE GESTÃO FISCAL";
  $head4 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
  $head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  
}

$dt3 = split("-",$dt_fin);
if ($dt3[1] == "12") {
  $dt3[1] = 11;
}
$dtInicialAnterior = $anousu_ant."-".($dt3[1]+1)."-01";
$dt1 = split('-',$dtInicialAnterior);
$dt2 = split('-',$dt_fin); 
if ($tipo_emissao == 'periodo') {

  if ($sSiglaPeriodo == "3Q" || $sSiglaPeriodo == "2S"  || $sSiglaPeriodo == "DEZ") {
    $textodt = "JANEIRO/{$anousu} A ".strtoupper(db_mes($dt2[1]))." DE ";
  } else {
    $textodt = strtoupper(db_mes($dt1[1]))."/{$dt1[0]} A ".strtoupper(db_mes($dt2[1]))." DE ";
  }
  if ($temcamara == true && $temprefa == false && $temadmind == false) {
    $head7 = $textodt.db_getsession("DB_anousu");
  } else {
    $head6 = $textodt.db_getsession("DB_anousu");
  }
} else {

  if ($temcamara == true && $temprefa == false && $temadmind == false) {
    $head7 = 'PERÍODO :'.$dt1[2].'/'.$dt1[1].'/'.$dt1[0].' à '.$dt2[2].'/'.$dt2[1].'/'.$dt2[0];
  } else {
    $head6 = 'PERÍODO :'.$dt1[2].'/'.$dt1[1].'/'.$dt1[0].' à '.$dt2[2].'/'.$dt2[1].'/'.$dt2[0];
  }
}

////////////////////////////////////////////////////////////////////////////////////////////

$limite_maximo             = 0;
$limite_prudencial         = 0;
$pessoal_ativo_adicional   = 0;
$pessoal_inativo_adicional = 0;
$outras_despesas           = 0;
$indenizacoes              = 0;
$decorrentes               = 0;
$despesas_anteriores       = 0;
$inativos_vinculados       = 0;

$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(26,str_replace('-',',',$db_selinstit)));

if ($temprefa==true and	$temcamara==true) {
  if ($limite_maximo == 0){
    $limite_maximo=60;
  }
} elseif ($temprefa==true and	$temcamara==false) {
  if ($limite_maximo == 0){
    $limite_maximo=54;
  }
} elseif ($temprefa==false and	$temcamara==true) {
  if ($limite_maximo == 0){
    $limite_maximo=6;
  }
}

if ($limite_prudencial == 0){
  $limite_prudencial=$limite_maximo*95/100;
}

$instituicao = str_replace("-",",",$db_selinstit);
// Ate o bimestre
$m_despesa[7]["exercicio"]     = 0;

//print_r($m_despesa); exit;
$aLinhasRelatorio = array();
for ($iLinha = 1; $iLinha <= 7; $iLinha++) {
   
  $aLinhasRelatorio[$iLinha] = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha);
  $aLinhasRelatorio[$iLinha]->setPeriodo($iCodigoPeriodo);
  $aLinhasRelatorio[$iLinha]->parametro = $aLinhasRelatorio[$iLinha]->getParametros($anousu);
  $aLinhasRelatorio[$iLinha]->exercicio = 0;
  $aLinhasRelatorio[$iLinha]->inscritas = 0;
  $aColunas  = $aLinhasRelatorio[$iLinha]->getValoresSomadosColunas($instituicao, $anousu);
  foreach ($aColunas as $oLinha) {

    $aLinhasRelatorio[$iLinha]->exercicio += @$oLinha->colunas[1]->o117_valor;
    $aLinhasRelatorio[$iLinha]->inscritas += @$oLinha->colunas[2]->o117_valor;
  }
}
$sele_work = 'o58_instit in ('.$instituicao.')';

$rsDespesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);

$dt3 = split("-",$dt_fin);
if ($dt3[1] == "12"){
  $dt3[1] = 11;
}
// Exercicio Atual
$aContas[]         = array();
$aContasAnterior[] = array();
$nTotal = 0;
for ($x = 0; $x < pg_numrows($rsDespesa); $x++) {


  $oDespesa = db_utils::fieldsmemory($rsDespesa, $x);
  $nTotal +=$oDespesa->liquidado_acumulado;
  for ($iLinha = 1; $iLinha <= 7; $iLinha++) {

    if (!isset($aContas[$iLinha])) {
      $aContas[$iLinha] = array();
    }
    $oParametro  = $aLinhasRelatorio[$iLinha]->parametro;
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oDespesa, 2);
      if ($oVerificacao->match) {
        
        $oDespesaValores = clone $oDespesa;
        if ($oVerificacao->exclusao) {
           
          $oDespesaValores->liquidado_acumulado *= -1;
          $oDespesaValores->empenhado_acumulado *= -1;
          $oDespesaValores->anulado_acumulado   *= -1;
        }

        if (!isset($aContas[$iLinha][$oDespesa->o58_elemento])) {
          $aContas[$iLinha][$oDespesa->o58_elemento] = 0;
        }
        $aContas[$iLinha][$oDespesa->o58_elemento] += ($oDespesaValores->liquidado_acumulado);
        $aLinhasRelatorio[$iLinha]->exercicio      += $oDespesaValores->liquidado_acumulado;
        if ($sSiglaPeriodo == "3Q" || $sSiglaPeriodo == "2S" || $sSiglaPeriodo == "DEZ") {

          $aLinhasRelatorio[$iLinha]->inscritas += abs(round($oDespesaValores->empenhado_acumulado -
          $oDespesaValores->anulado_acumulado -
          $oDespesaValores->liquidado_acumulado,2)
          );
        }
      }
    }
  }
}

/*
 * Criado o vetor $aContas para debugar o relatório onde é mostrado o valor de cada elemento
 * para ver o vertor basta descomentar as linhas abaixo
 */


//echo $nTotal;
//echo "<pre>";
//print_r($aContas);
//echo "</pre>";


if ($sSiglaPeriodo != "3Q" && $sSiglaPeriodo != "2S" && $sSiglaPeriodo != "DEZ") {

  $dt_ini_ant = $anousu_ant."-".($dt3[1]+1)."-01";
  $rsDespesaAnterior = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu_ant,$dt_ini_ant,$dt_fin_ant);
  
  for ($x = 0; $x < pg_num_rows($rsDespesaAnterior); $x++) {

    $oDespesaAnterior = db_utils::fieldsmemory($rsDespesaAnterior, $x);
    for ($iLinha = 1; $iLinha <= 7; $iLinha++) {

      if ($iLinha == 2) {
        if (!isset($aContasAnterior[$iLinha])) {
          $aContasAnterior[$iLinha] = array();
        }
      }
      $oParametro  = $aLinhasRelatorio[$iLinha]->parametro;
       
      foreach ($oParametro->contas as $oConta) {
        
        $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oDespesaAnterior, 2);
        $oDespesaValoresAnterior = clone $oDespesaAnterior;
        if ($oVerificacao->match) {
          if ($oVerificacao->exclusao) {

            $oDespesaValoresAnterior->liquidado *= -1;
          }
          if ($iLinha == 2) {
            if (!isset($aContasAnterior[$iLinha][$oDespesaAnterior->o58_elemento])) {
              $aContasAnterior[$iLinha][$oDespesaAnterior->o58_elemento] = 0;
            }
            $aContasAnterior[$iLinha][$oDespesaAnterior->o58_elemento] += $oDespesaValoresAnterior->liquidado;
          }
          $aLinhasRelatorio[$iLinha]->exercicio  += $oDespesaValoresAnterior->liquidado;
        }
      }
    }
  }
}

//echo $nTotal;
//echo "<pre>";
//print_r($aContasAnterior);
//echo "</pre>";
//exit;

for ($x=1;$x<=7;$x++){

  $m_p[$x][2]=0;
  $m_p[$x][3]=0;
}

// exclusão de parametros

// receita corrente liquida
// busca os estruturias que o usuário selecionou nos parametros

$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {

  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit .=$codigo . ($xinstit == $cldb_config->numrows-1 ? "" : ",");
}

duplicaReceitaaCorrenteLiquida($anousu, 81);
//ano corrente
$tx[13][2] = 0; // receita corrente liquida

$total_rcl = 0;

//echo $dt_ini." => ".$dt_fin." => ".$dt_ini_ant." => ".$dt_fin_ant; exit;
$dt_ini_ant = $anousu_ant."-01-01";
$total_rcl += calcula_rcl2($anousu, $dt_ini, $dt_fin, $todasinstit, false, 81);
$total_rcl += calcula_rcl2($anousu_ant, $dt_ini_ant, $dt_fin_ant, $todasinstit, false, 81, $dt_fin);

$tx[13][2]  = $total_rcl;

$tx[0][1]  = 'DESPESA BRUTA COM PESSOAL (I)';
$tx[1][1]  = '   Pessoal Ativo';
$tx[2][1]  = '   Pessoal Inativo e Pensionistas';
$tx[3][1]  = "   Outras Despesas de Pessoal decorrentes de Contratos de Terceirização (§ 1º do art. 18 da LRF)";
$tx[4][1]  = "DESPESAS NÃO COMPUTADAS (§ 1º do art. 19 da LRF) (II)";
$tx[5][1]  = "   Indenizações por Demissão e Incentivos à Demissão Voluntária";
$tx[6][1]  = "   Decorrentes de Decisão Judicial";
$tx[7][1]  = "   Despesas de Exercícios Anteriores";
$tx[8][1]  = "   Inativos e Pensionistas com Recursos Vinculados";
$tx[9][1]  = "REPASSE PREVIDENCIÁRIO AO REGIME PRÓPRIO DE PREVIDENCIA SOCIAL(III)";
$tx[10][1] = "   Contribuições Patronais";
$tx[11][1] = "DESPESA LÍQUIDA COM PESSOAL (III) = (I - II)";
$tx[12][1] = "DESPESA TOTAL COM PESSOAL - DTP(IV) = (IIIa + IIIb)";
$tx[13][1] = "RECEITA CORRENTE LÍQUIDA - RCL (V)";
$tx[14][1] = "% da DESPESA TOTAL COM PESSOAL - DTP sobre a RCL (VI)=(IV/V)*100";
$tx[15][1] = "LIMITE MÁXIMO (incisos I, II e III, art. 20 da LRF) $limite_maximo%";
$tx[16][1] = "LIMITE PRUDENCIAL (parágrafo único, art. 22 da LRF) $limite_prudencial%";

/**
 *
 */
$tx[1][2] = $aLinhasRelatorio[1]->exercicio;
$tx[2][2] = $aLinhasRelatorio[2]->exercicio;
$tx[3][2] = $aLinhasRelatorio[3]->exercicio;

$tx[5][2] = $aLinhasRelatorio[4]->exercicio;
$tx[6][2] = $aLinhasRelatorio[5]->exercicio;
$tx[7][2] = $aLinhasRelatorio[6]->exercicio;
$tx[8][2] = $aLinhasRelatorio[7]->exercicio;

$tx[9][2] = 0;
$tx[10][2]= 0;

// calculos
$tx[0][2]  = abs($tx[1][2]) + abs($tx[2][2]) + abs($tx[3][2]);
$tx[4][2]  = $tx[5][2] + $tx[6][2] + $tx[7][2] + $tx[8][2];
$tx[11][2] = $tx[0][2] - $tx[4][2];
$tx[12][2] = $tx[11][2];

// restos a pagar

$tx[1][3]  = $aLinhasRelatorio[1]->inscritas;
$tx[2][3]  = $aLinhasRelatorio[2]->inscritas;
$tx[3][3]  = $aLinhasRelatorio[3]->inscritas;;

$tx[5][3]  = $aLinhasRelatorio[4]->inscritas;
$tx[6][3]  = $aLinhasRelatorio[5]->inscritas;
$tx[7][3]  = $aLinhasRelatorio[6]->inscritas;
//$tx[8][3]  = $m_despesa[7]["exercicio"];
$tx[8][3]  = $aLinhasRelatorio[7]->inscritas;

$tx[9][3]  = 0;
$tx[10][3] = 0;

$tx[11][3] = 0;  // DESPESA LIQUIDA COM PESSOAL
$tx[12][3] = 0;  // DESPESA TOTAL COM PESSOAL - DTP

$tx[13][3] = 0;  // RCL
$tx[14][3] = 0;
$tx[15][3] = 0;
$tx[16][3] = 0;


$tx[0][3]  = $tx[1][3] + $tx[2][3] + $tx[3][3];
$tx[4][3]  = $tx[5][3] + $tx[6][3] + $tx[7][3] + $tx[8][3];
$tx[11][3] = $tx[0][3] - $tx[4][3];
$tx[12][3] = $tx[11][3];

// totalizacoes

if ($tx[13][2] > 0) {
  //$tx[13][2] = (($tx[11][2] + $tx[11][3]) / $tx[12][2])*100;
  $tx[14][2] = ($tx[11][2]/$tx[13][2])*100;  // TDP/RCL * 100
} else {
  $tx[14][2] = 0;
}

$tx[15][2] = (($tx[13][2] + $tx[13][3]) * $limite_maximo) / 100;
$tx[16][2] = (($tx[13][2] + $tx[13][3]) * $limite_prudencial) / 100;
if (!isset($arqinclude)) {

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt = 4;
  $pagina = 1;

  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $pdf->cell(135,$alt,'RGF - ANEXO I(LRF, art. 55, inciso I, alínea "a")','B',0,"L",0);
  $pdf->cell(60,$alt,'R$ 1,00','B',1,"R",0);

  $pdf->cell(135,$alt,"",'R',0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS EXECUTADAS",'',1,"C",0);

  $pdf->cell(135,$alt,"",'R',0,"C",0);
  $pdf->cell(60,$alt,"(Últimos 12 meses)",'LB',1,"C",0);

  $pdf->cell(135,$alt,"DESPESA COM PESSOAL",'R',0,"C",0);
  $pdf->cell($tamanho,$alt,"",'',0,"C",0);
  if ($imprimirrps == true) {
    $pdf->cell(30,$alt,"INSCRITAS EM",'L',0,"C",0);
  }
  $pdf->ln();

  $pdf->cell(135,$alt,"",'R',0,"C",0);
  $pdf->cell($tamanho,$alt,"LIQUIDADAS(a)",'',0,"C",0);
  if ($imprimirrps == true) {
    $pdf->cell(30,$alt,"RESTOS A PAGAR",'L',0,"C",0);
  }
  $pdf->ln();

  if ($imprimirrps == true) {

    $pdf->cell(135,$alt,"",'R',0,"C",0);
    $pdf->cell(30,$alt,"",'',0,"C",0);
    $pdf->cell(30,$alt,"NAO",'L',0,"C",0);
    $pdf->ln();
  }

  if ($imprimirrps == true) {

    $pdf->cell(135,$alt,"",'R',0,"C",0);
    $pdf->cell(30,$alt,"",'',0,"C",0);
    $pdf->cell(30,$alt,"PROCESSADOS(b)",'L',0,"C",0);
    $pdf->ln();
  }

  $pdf->cell(135,$alt,"",'RB',0,"R",0);
  if ($imprimirrps == true) {

    $pdf->cell(30,$alt,"",'RB',0,"C",0);
    $pdf->cell(30,$alt,"",'B',0,"C",0);
  } else {
    $pdf->cell($tamanho,$alt,"",'B',0,"C",0);
  }
  $pdf->ln();

  $pdf->setfont('arial','',7);

  for ($i = 0; $i <= 8; $i++) {

    $pdf->cell(135, $alt, $tx[$i][1], 'R', 0, "L", 0);
    if (isset($tx[$i][2])) {
      $pdf->cell($tamanho, $alt, db_formatar(abs($tx[$i][2]), 'f'), '', 0, "R", 0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'',0,"R",0);
    }

    if ($imprimirrps == true) {

      $valorimprimir = $tx[$i][3];
      if ($valorimprimir <= 0){
        $valorimprimir *= -1;
      }
      $pdf->cell(30,$alt,db_formatar($valorimprimir,'f'),'L',0,"R",0);
    }

    $pdf->ln();
  }

  for ($i = 11; $i <= 16; $i++) {
    if ($i == 12) {
      if ($imprimirrps == false) {
        continue;
      }
    }

    if ($i == 13) {

      $tamanho = 60;
      $pdf->cell(195,$alt, "", "TB", 1, "C", 0);
      $pdf->cell(135,$alt, "APURAÇÃO DO CUMPRIMENTO DO LIMITE LEGAL","R", 0, "C", 0);
      $pdf->cell( 60,$alt, "VALOR", "TB", 1, "C", 0);
    }
    if ($i == 12 ){

      $pdf->cell(135,$alt,$tx[$i][1],'TBR',0,"L",0);
      if (isset($tx[$i][2])) {
        $pdf->cell(60,$alt,db_formatar(abs($tx[11][2])+abs($tx[11][3]),'f'),'TB',1,"R",0);
      } else {
        $pdf->cell(60,$alt,db_formatar(0,'f'),'TB',1,"R",0);
      }
      continue;
    }
    $pdf->cell(135,$alt,$tx[$i][1],'TBR',0,"L",0);
    if (isset($tx[$i][2])){
      $pdf->cell($tamanho,$alt,db_formatar(abs($tx[$i][2]),'f'),'TB',0,"R",0);
    }else{
      $pdf->cell($tamanho,$alt,db_formatar(0,'f'),'TB',1,"R",0);
    }

    if ($i < 13){
      if ($imprimirrps == true) {
        $valorimprimir = $tx[$i][3];
        if ($valorimprimir <= 0){
          $valorimprimir *= -1;
        }
       $pdf->cell(30,$alt,db_formatar($valorimprimir,'f'),'TBL',0,"R",0);
      }
    }

    $pdf->ln();
  }
 
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false); 
  $oRelatorio->getNotaExplicativa(&$pdf, $periodo, 195);

  $pdf->Ln(5);

  // assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);

  assinaturas(&$pdf,&$classinatura,'GF');

  $pdf->Output();

}

$total_despesa_pessoal_limites = $tx[11][2];
$total_rcl_limites             = $tx[14][2];

$total_despesa_pessoal_limites = $tx[12][2] + $tx[12][3];
$limite_maximo_valor		 = $tx[15][2];
$limite_prudencial_valor = $tx[16][2];

?>