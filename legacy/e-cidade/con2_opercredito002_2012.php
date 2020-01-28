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

  require_once("fpdf151/pdf.php");
  require_once("fpdf151/assinatura.php");
  require_once("libs/db_sql.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_libcontabilidade.php");
  require_once("libs/db_liborcamento.php");
  require_once("classes/db_orcparamrel_classe.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_orcparamrelopcre_classe.php");
  require_once("classes/db_conrelinfo_classe.php");
  require_once("classes/db_conrelvalor_classe.php");
  require_once("classes/db_orcparamrelopcre_classe.php");
  require_once("classes/db_orcparamelemento_classe.php");
  require_once("libs/db_utils.php");
  require_once("model/linhaRelatorioContabil.model.php");
  require_once("model/relatorioContabil.model.php");

  $classinatura = new cl_assinatura;
  $orcparamrel  = new cl_orcparamrel;
  $clconrelinfo      = new cl_conrelinfo;
  $clconrelvalor     = new cl_conrelvalor;
  $oOrcParamRelopcre = new cl_orcparamrelopcre;
  $clorcparamelemento = new cl_orcparamelemento();

  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

}

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,munic,nomeinst,nomeinstabrev from db_config");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
$nTotalRcl = 0;
$sInstituicoes = "";


//******************************************************************
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
  }else{
       $descr_inst .= $xvirg.$nomeinst;
  }

  $sInstituicoes .= $xvirg.$codigo;
  $xvirg = ', ';
}

if ($flag_abrev == false){
  if (strlen($descr_inst) > 42){
     $descr_inst = substr($descr_inst,0,100);
   }
}
$anousu           = db_getsession("DB_anousu");
$anousu_ant       = $anousu - 1;
$oDaoPeriodo      = db_utils::getDao("periodo");
$iCodigoPeriodo   = $periodo;
$sSqlPeriodo      = $oDaoPeriodo->sql_query($periodo);
$sSiglaPeriodo    = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
$dt               = data_periodo($anousu,$sSiglaPeriodo);
$periodo          = $sSiglaPeriodo;
$iCodigoRelatorio = 92;
$dt               = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
$dt_ini_plano     = $dt[0];  // data inicial do periodo
$dt_ini           = "{$anousu}-01-01";  // data inicial do periodo PADRÃO
$dt_fim           = $dt[1];  // data final do período
$dt = split('-',$dt_fim);  // mktime -- (mes,caddia,ano)
$dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1]+1,"01",$anousu_ant));
$dt_fim_ant = $anousu_ant.'-12-31';
$head2 = "MUNICÍPIO DE ".$munic;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DAS OPERAÇÕES DE CREDITO";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$period = '';
$descr_periodo = '';



//
//echo "Sigla: " .$periodo;
//die();
if ($periodo=="1Q") {

  $period           = "JANEIRO A ABRIL DE {$anousu}";
  $descr_periodo    = "Quadrimestre";
  $aRCL["anterior"] = array("maio","junho","julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["atual"]    = array("janeiro","fevereiro","marco","abril" );

} else if($periodo == "2Q") {

  $period           = "JANEIRO A AGOSTO DE {$anousu}";
  $descr_periodo    = "Quadrimestre";
  $aRCL["anterior"] = array();
  $aRCL["anterior"] = array("setembro","novembro","outubro", "dezembro");
  $aRCL["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto");
  $dt_ini           = "{$anousu}-05-01";  // data inicial do periodo

} else if ($periodo == "3Q") {

  $period           = "JANEIRO A DEZEMBRO DE {$anousu}";
  $descr_periodo    = "Quadrimestre";
  $aRCL["anterior"] = array();
  $aRCL["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto",
                            "setembro","novembro","outubro", "dezembro");
  $dt_ini           = "{$anousu}-09-01";  // data inicial do periodo
} elseif ($periodo == "1S") {

  $period           = "JANEIRO A JUNHO DE {$anousu}";
  $descr_periodo    = "Semestre";
  $aRCL["anterior"] = array("julho","agosto","setembro","outubro","novembro", "dezembro");
  $aRCL["atual"]    = array("janeiro","fevereiro","marco","abril", "maio","junho");
} elseif($periodo == "2S") {

  $period           = "JANEIRO A DEZEMBRO DE {$anousu}";
  $descr_periodo    = "Semestre";
  $aRCL["anterior"] = array();
  $aRCL["atual"]    = array("janeiro","fevereiro","marco","abril","maio","junho","julho","agosto",
                            "setembro","novembro","outubro", "dezembro");
  $dt_ini           = "{$anousu}-07-01";  // data inicial do periodo
}

$head6 = "$period";

$soma_interna     = 0;
$soma_externa     = 0;
$soma_antecipacao = 0;

/**
 * Parametros
 */
for ($i = 1; $i <= 20; $i++) {

  $aParametros[$i]              = new linhaRelatorioContabil($iCodigoRelatorio, $i);

  $aParametros[$i]->nobimestre  = 0;
  $aParametros[$i]->atebimestre = 0;
  $aParametros[$i]->parametros  = $aParametros[$i]->getParametros($anousu);
  $aParametros[$i]->setPeriodo($iCodigoPeriodo);
  $aParametros[$i]->linhas       = array();
  $aColunas = $aParametros[$i]->getValoresColunas(null, null, $sInstituicoes, $anousu);
  foreach ($aColunas as $oColuna) {

    switch ($i) {

      case 10 :
        $oLinha              = new stdClass();
        $oLinha->descricao   = $oColuna->colunas[0]->o117_valor;
        $oLinha->nobimestre  = $oColuna->colunas[1]->o117_valor;
        $oLinha->atebimestre = $oColuna->colunas[2]->o117_valor;
        $aParametros[$i]->linhas[] = $oLinha;
        break;

      case 8:

        if (in_array($iCodigoPeriodo, array(14,15,16))) {

          if (isset($oColuna->colunas[0])) {
            $aParametros[$i]->nobimestre  +=$oColuna->colunas[0]->o117_valor;
          }
          if (isset($oColuna->colunas[2])) {
            $aParametros[$i]->atebimestre  +=$oColuna->colunas[2]->o117_valor;
          }
        }
        break;
      default:
        if (isset($oColuna->colunas[0])) {
          $aParametros[$i]->nobimestre  +=$oColuna->colunas[0]->o117_valor;
        }
        if (isset($oColuna->colunas[0])) {
          $aParametros[$i]->atebimestre  +=$oColuna->colunas[1]->o117_valor;
        }
        break;
    }
  }
}
$where            = "  c61_instit in ({$sInstituicoes})   ";

// *********************************************************************************************************************
/**
 * Faz a pesquisa
 */
$rsDadosRelatorio = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fim,false,$where);
@db_query("drop table work_pl");
$iNumRows = pg_num_rows($rsDadosRelatorio);
for ($iParam = 1; $iParam <= 18; $iParam++) {

  if ($iParam == 10) {
    continue;
  }

  for ($i = 0; $i < $iNumRows; $i++) {

    $oResultado = db_utils::fieldsMemory($rsDadosRelatorio, $i);
    $oParametro = $aParametros[$iParam]->parametros;
    foreach ($oParametro->contas as $oConta) {

      $oVerificacao    = $aParametros[$iParam]->match($oConta, $oParametro->orcamento, $oResultado, 3);
      if ($oVerificacao->match) {

        if ($oVerificacao->exclusao) {

          $oResultado->saldo_anterior *= -1;
          $oResultado->saldo_final    *= -1;

        }
        $aParametros[$iParam]->nobimestre  += $oResultado->saldo_final - $oResultado->saldo_anterior;
        $aParametros[$iParam]->atebimestre += $oResultado->saldo_final;
      }
    }
  }
}

/**
 * Linhas do relatorio
 */
$aLinhasRelatorio              = array();
$aLinhasRelatorio[0]->label  = "SUJEITAS AO LIMITE PARA FINS DE CONTRATAÇÃO (I)";
$aLinhasRelatorio[1]->label  = "    Mobiliária";
$aLinhasRelatorio[2]->label  = "       Interna";
$aLinhasRelatorio[3]->label  = "       Externa";
$aLinhasRelatorio[4]->label  = "    Contratual";
$aLinhasRelatorio[5]->label  = "        Interna";
$aLinhasRelatorio[6]->label  = "            Abertura de Crédito";
$aLinhasRelatorio[7]->label  = "            Aquisição Financiada de Bens e Arrendamento Mercantil Financeiro";
$aLinhasRelatorio[8]->label  = "                Derivadas de PPP";
$aLinhasRelatorio[9]->label  = "                Demais Aquisições Financiadas";
$aLinhasRelatorio[10]->label = "            Antecipação de Receita";
$aLinhasRelatorio[11]->label = "                Pela Venda a Termo de Bens e Serviços";
$aLinhasRelatorio[12]->label = "                Demais Antecipações de Receita";
$aLinhasRelatorio[13]->label = "            Assunção, Reconhecimento e Confissão de Dívidas (LRF, art. 29, § 1º)";
$aLinhasRelatorio[14]->label = "            Outras Operações de Crédito";
$aLinhasRelatorio[15]->label = "        Externa";
$aLinhasRelatorio[15]->linhas = array();
$aLinhasRelatorio[16]->label = "NÃO SUJEITAS AO LIMITE PARA FINS DE CONTRATAÇÃO (II)";
$aLinhasRelatorio[17]->label = "    Parcelamentos de Dívidas";
$aLinhasRelatorio[18]->label = "        De Tributos";
$aLinhasRelatorio[19]->label = "        De Contribuições Sociais";
$aLinhasRelatorio[20]->label = "            Previdenciárias";
$aLinhasRelatorio[21]->label = "            Demais Contribuições Sociais";
$aLinhasRelatorio[22]->label = "        Do FGTS";
$aLinhasRelatorio[23]->label = "    Melhoria da Administração de Receitas e da Gestão Fiscal, Financeira e Patrimonial ";
$aLinhasRelatorio[24]->label = "    Programa de Iluminação Pública - RELUZ ";
$aLinhasRelatorio[25]->label = "    Amparadas pelo art 9-N da Resolução nº2.827/01, do CMN ";
$aLinhasRelatorio[26]->label = "RECEITA CORRENTE LÍQUIDA - RCL ";
$aLinhasRelatorio[27]->label = "OPERAÇÕES VEDADAS (III)";
$aLinhasRelatorio['27.1']->label = "    Do Período de Referência (III)";
$aLinhasRelatorio['27.2']->label = "    De Períodos Anteriores ao de Referência";


$aLinhasRelatorio[28]->label = "TOTAL CONSIDERADO PARA FINS DA APURAÇÃO DO CUMPRIMENTO DO LIMITE (IV) = (Ia + III)";
$aLinhasRelatorio[29]->label = "LIMITE GERAL DEFINIDO POR RESOLUÇÃO DO SENADO FED. PARA AS OPERAÇÕES DE CRÉDITO ";
$aLinhasRelatorio[29]->label.= "INTERNAS E EXTERNAS";
$aLinhasRelatorio['29.1']->label = "LIMITE DE ALERTA (inciso III do §1º do art. 59 da LRF) - 14,4%";
$aLinhasRelatorio[30]->label = "OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA ORÇAMENTÁRIA";
$aLinhasRelatorio[31]->label = "LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FED. PARA AS OP. DE CRED POR ";
$aLinhasRelatorio[31]->label.= "ANTECIPAÇÃO DA REC. ORÇAMENTÁRIA";
$aLinhasRelatorio[32]->label = "TOTAL CONSIDERADO PARA CONTRATAÇÃO DE NOVAS OPERAÇÕES DE CRÉDITO (V) = (IV + IIa)";

//echo "<pre>";
//var_dump($aLinhasRelatorio);
//echo "<br>";


foreach ($aLinhasRelatorio as $oObjDeclarar ) {

  $oObjDeclarar->atebimestre = 0;
  $oObjDeclarar->nobimestre  = 0;

}

/**
 * calculamos os totais, e vinculamos o valores encontrado nos parametros e
 * vinculamos as linhas do relatorio
 */
/*
 * Grupo Mobiliaria
 */
$aLinhasRelatorio[2]->nobimestre  = $aParametros[1]->nobimestre;
$aLinhasRelatorio[2]->atebimestre = $aParametros[1]->atebimestre;
$aLinhasRelatorio[3]->nobimestre  = $aParametros[2]->nobimestre;
$aLinhasRelatorio[3]->atebimestre = $aParametros[2]->atebimestre;

$aLinhasRelatorio[1]->nobimestre  = $aLinhasRelatorio[3]->nobimestre  + $aLinhasRelatorio[2]->nobimestre;
$aLinhasRelatorio[1]->atebimestre = $aLinhasRelatorio[3]->atebimestre + $aLinhasRelatorio[2]->atebimestre;

/*
 * Grupo Contratual
 */
$aLinhasRelatorio[6]->nobimestre   = $aParametros[3]->nobimestre ;
$aLinhasRelatorio[6]->atebimestre  = $aParametros[3]->atebimestre;
$aLinhasRelatorio[8]->nobimestre   = $aParametros[4]->nobimestre;
$aLinhasRelatorio[8]->atebimestre  = $aParametros[4]->atebimestre;
$aLinhasRelatorio[9]->nobimestre   = $aParametros[5]->nobimestre;
$aLinhasRelatorio[9]->atebimestre  = $aParametros[5]->atebimestre;
$aLinhasRelatorio[7]->nobimestre   = $aLinhasRelatorio[8]->nobimestre  + $aLinhasRelatorio[9]->nobimestre;
$aLinhasRelatorio[7]->atebimestre  = $aLinhasRelatorio[8]->atebimestre + $aLinhasRelatorio[9]->atebimestre;

$aLinhasRelatorio[11]->nobimestre  = $aParametros[6]->nobimestre;
$aLinhasRelatorio[11]->atebimestre = $aParametros[6]->atebimestre;
$aLinhasRelatorio[12]->nobimestre  = $aParametros[7]->nobimestre;
$aLinhasRelatorio[12]->atebimestre = $aParametros[7]->atebimestre;
$aLinhasRelatorio[10]->nobimestre  = $aLinhasRelatorio[11]->nobimestre  + $aLinhasRelatorio[12]->nobimestre;
$aLinhasRelatorio[10]->atebimestre = $aLinhasRelatorio[11]->atebimestre + $aLinhasRelatorio[12]->atebimestre;

$aLinhasRelatorio[13]->nobimestre  = $aParametros[8]->nobimestre;
$aLinhasRelatorio[13]->atebimestre = $aParametros[8]->atebimestre;
$aLinhasRelatorio[14]->nobimestre  = $aParametros[9]->nobimestre;
$aLinhasRelatorio[14]->atebimestre = $aParametros[9]->atebimestre;
$aLinhasRelatorio[5]->nobimestre   = $aLinhasRelatorio[6]->nobimestre  + $aLinhasRelatorio[7]->nobimestre  +
                                     $aLinhasRelatorio[13]->nobimestre + $aLinhasRelatorio[14]->nobimestre +
                                     $aLinhasRelatorio[10]->nobimestre;

$aLinhasRelatorio[5]->atebimestre  = $aLinhasRelatorio[6]->atebimestre  + $aLinhasRelatorio[7]->atebimestre  +
                                     $aLinhasRelatorio[13]->atebimestre + $aLinhasRelatorio[14]->atebimestre +
                                     $aLinhasRelatorio[10]->atebimestre;


/*
 * Calculamos o total da linha 16, onde é a soma de todos os estruturais cadastrados para ela.
 */
$aLinhasRelatorio[15]->linhas = $aParametros[10]->linhas;
foreach ($aParametros[10]->linhas as $aParametroItem) {

  $aLinhasRelatorio[15]->nobimestre  += $aParametroItem->nobimestre;
  $aLinhasRelatorio[15]->atebimestre += $aParametroItem->atebimestre;

}

$aLinhasRelatorio[4]->nobimestre  = $aLinhasRelatorio[5]->nobimestre  + $aLinhasRelatorio[15]->nobimestre;
$aLinhasRelatorio[4]->atebimestre = $aLinhasRelatorio[5]->atebimestre + $aLinhasRelatorio[15]->atebimestre;
$aLinhasRelatorio[0]->nobimestre  = $aLinhasRelatorio[4]->nobimestre  + $aLinhasRelatorio[1]->nobimestre;
$aLinhasRelatorio[0]->atebimestre = $aLinhasRelatorio[4]->atebimestre + $aLinhasRelatorio[1]->atebimestre;
/*
 * Grupo nao sujeitas
 */
$aLinhasRelatorio[18]->nobimestre  = $aParametros[11]->nobimestre;
$aLinhasRelatorio[18]->atebimestre = $aParametros[11]->atebimestre;
$aLinhasRelatorio[20]->nobimestre  = $aParametros[12]->nobimestre;
$aLinhasRelatorio[20]->atebimestre = $aParametros[12]->atebimestre;
$aLinhasRelatorio[21]->nobimestre  = $aParametros[13]->nobimestre;
$aLinhasRelatorio[21]->atebimestre = $aParametros[13]->atebimestre;
$aLinhasRelatorio[19]->nobimestre  = $aLinhasRelatorio[20]->nobimestre+$aLinhasRelatorio[21]->nobimestre;
$aLinhasRelatorio[19]->atebimestre = $aLinhasRelatorio[20]->atebimestre+$aLinhasRelatorio[21]->atebimestre;
$aLinhasRelatorio[22]->nobimestre  = $aParametros[14]->nobimestre;
$aLinhasRelatorio[22]->atebimestre = $aParametros[14]->atebimestre;
$aLinhasRelatorio[23]->nobimestre  = $aParametros[15]->nobimestre;
$aLinhasRelatorio[23]->atebimestre = $aParametros[15]->atebimestre;
$aLinhasRelatorio[24]->nobimestre  = $aParametros[16]->nobimestre;
$aLinhasRelatorio[24]->atebimestre = $aParametros[16]->atebimestre;
$aLinhasRelatorio[25]->nobimestre  = $aParametros[17]->nobimestre;
$aLinhasRelatorio[25]->atebimestre = $aParametros[17]->atebimestre;
$aLinhasRelatorio[17]->nobimestre  = $aLinhasRelatorio[18]->nobimestre + $aLinhasRelatorio[19]->nobimestre +
                                     $aLinhasRelatorio[22]->nobimestre;
$aLinhasRelatorio[17]->atebimestre = $aLinhasRelatorio[18]->atebimestre + $aLinhasRelatorio[19]->atebimestre +
                                     $aLinhasRelatorio[22]->atebimestre;
$aLinhasRelatorio[16]->nobimestre  = $aLinhasRelatorio[17]->nobimestre + $aLinhasRelatorio[23]->nobimestre +
                                     $aLinhasRelatorio[24]->nobimestre + $aLinhasRelatorio[25]->nobimestre;
$aLinhasRelatorio[16]->atebimestre = $aLinhasRelatorio[17]->atebimestre + $aLinhasRelatorio[23]->atebimestre +
                                     $aLinhasRelatorio[24]->atebimestre + $aLinhasRelatorio[25]->atebimestre;
$nTotalOperacoesCredito  = 0;
$nTotalInternas          = 0;
$nTotalExternas          = 0;
$nValorAntecipacao       = 0;
$nValorInternoExterno    = 0;
$nPercValorInterno       = 0;
$nPercValorAntecipacao   = 0;

$aOperacaoCredito["interna"]     = array();//linhas operacoes internas
$aOperacaoCredito["externa"]     = array();//linhas operacoes externas
$aOperacaoCredito["antecipacao"] = array();//linhas operacoes antecipadas;
$sTodasInstit = null;

$rsInstit =  db_query("select codigo from db_config");
for ($xinstit=0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {
  db_fieldsmemory($rsInstit, $xinstit);
  $sTodasInstit .= $codigo . ($xinstit==pg_num_rows($rsInstit)-1?"":",");
}

if (!isset($arqinclude)) {

  duplicaReceitaaCorrenteLiquida($anousu, 81);

  $aMesesRCLAnterior = calcula_rcl2($anousu_ant, "{$anousu_ant}-01-01", "{$anousu_ant}-12-31", $sTodasInstit, true, 81);
  $aMesesRCLAtual    = calcula_rcl2($anousu, "{$anousu}-01-01", "{$anousu}-12-31", $sTodasInstit, true, 81);
}
$nTotalRcl = somaRCLPeriodoAnexo3($aMesesRCLAtual, $aMesesRCLAnterior, $aRCL);

$aLinhasRelatorio['27.1']->nobimestre  = $aParametros[19]->nobimestre;
$aLinhasRelatorio['27.1']->atebimestre = round( ( ( $aParametros[19]->nobimestre / $nTotalRcl ) * 100 ), 2);

$aLinhasRelatorio['27.2']->nobimestre  = $aParametros[20]->nobimestre;
$aLinhasRelatorio['27.2']->atebimestre = round( ( ( $aParametros[20]->nobimestre / $nTotalRcl ) * 100 ), 2);

// Inclui elemento no exercício anterior com base no atual;

$aLinhasRelatorio[26]->nobimestre  = $nTotalRcl;
$aLinhasRelatorio[26]->atebimestre = "-";

$aLinhasRelatorio[27]->nobimestre  = $aLinhasRelatorio['27.1']->nobimestre   + $aLinhasRelatorio['27.2']->nobimestre;
$aLinhasRelatorio[27]->atebimestre = $aLinhasRelatorio['27.1']->atebimestre  + $aLinhasRelatorio['27.2']->atebimestre;

$aLinhasRelatorio[28]->nobimestre  = $aLinhasRelatorio[0]->atebimestre+$aLinhasRelatorio['27.1']->nobimestre;
$aLinhasRelatorio[28]->atebimestre = @round( (($aLinhasRelatorio[28]->nobimestre)/ $nTotalRcl)*100,2);

$aLinhasRelatorio[29]->nobimestre  = $nTotalRcl*0.16;
$aLinhasRelatorio[29]->atebimestre = 16;

$aLinhasRelatorio[30]->nobimestre  = $aLinhasRelatorio[10]->atebimestre;
$aLinhasRelatorio[30]->atebimestre = @round( (($aLinhasRelatorio[30]->nobimestre)/$nTotalRcl)*100,2);

$aLinhasRelatorio[31]->nobimestre  = $nTotalRcl* 0.07;;
$aLinhasRelatorio[31]->atebimestre = 7;

$aLinhasRelatorio['29.1']->nobimestre  = round(($nTotalRcl *14.4) / 100, 2);
$aLinhasRelatorio['29.1']->atebimestre = 14.4;

$aLinhasRelatorio[32]->nobimestre  = $aLinhasRelatorio[16]->atebimestre  + $aLinhasRelatorio[28]->nobimestre;
$aLinhasRelatorio[32]->atebimestre = round( ( ($aLinhasRelatorio[16]->atebimestre  + $aLinhasRelatorio[28]->nobimestre)/$nTotalRcl)*100, 2);

if (!isset($arqinclude)) {

//totais das variaveis

}

$nValorInternoExterno = 7;
if ($nValorInternoExterno == 0) {
  $nValorInternoExterno = '< % >';
}else{

  $nPercValorInterno     = db_formatar(($nTotalRcl*$nValorInternoExterno)/100,"f");
  $nValorInternoExterno .= '%';
}

if ($nValorAntecipacao == 0) {
   $nValorAntecipacao = '< % >';
}else{
  $nPercValorAntecipacao  = db_formatar(($nTotalRcl*$nValorAntecipacao)/100,"f");
  $nValorAntecipacao     .= '%';
}
//linhas do relatório;
if (!isset($arqinclude)) {

  $pdf = new PDF("P", "mm", "A4");
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
  $pdf->cell(165,$alt,'RGF - ANEXO IV (LRF, art. 55, inciso I, alínea "d" e inciso III alínea "c")','B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);
  $pdf->cell(100,$alt,"",'RT',0,"C",0);
  $pdf->cell(90,$alt,"VALOR REALIZADO",'LTB',1,"C",0);
  $pdf->cell(100,$alt,"OPERAÇÕES DE CRÉDITO",'R',0,"C",0);
  $pdf->cell(45,$alt,"No ".$descr_periodo,'LT',0,"C",0);
  $pdf->cell(45,$alt,"Até o ".$descr_periodo,'LT',1,"C",0);
  $pdf->cell(100,$alt,"",'RB',0,"C",0);
  $pdf->cell(45,$alt,"de Referência",'LB',0,"C",0);
  $pdf->cell(45,$alt,"de Referência (a)",'LB',1,"C",0);

  /*
   * Inicio do Corpo do relatorio
   */
  $pdf->setfont('arial','',7);
  for ($i = 0; $i < 26; $i++) {

    $sBorda = '';
    if ($i == 16) {
      $sBorda = "T";
    }
    $pdf->cell(100,$alt,$aLinhasRelatorio[$i]->label,"R{$sBorda}",0,"L");
    $pdf->cell(45,$alt,db_formatar($aLinhasRelatorio[$i]->nobimestre,"f"),"R{$sBorda}",0,"R");
    $pdf->cell(45,$alt,db_formatar($aLinhasRelatorio[$i]->atebimestre,"f"),"L{$sBorda}",1,"R");
    if ($i == 15) {
      foreach ($aLinhasRelatorio[15]->linhas as $oItem) {


        $pdf->cell(100,$alt,"            ".$oItem->descricao,"R",0,"L");
        $pdf->cell(45,$alt,db_formatar($oItem->nobimestre,"f"),"R",0,"R");
        $pdf->cell(45,$alt,db_formatar($oItem->atebimestre,"f"),"L",1,"R");

      }
    }
  }
  $pdf->cell(190,$alt,"","TB",1,"L");
  $pdf->setfont('arial','b',7);
  $pdf->cell(130,$alt,"OUTRAS OPERAÇÕES DE CRÉDITO NÃO SUJEITAS AO LIMITE","TBR",0,"C");
  $pdf->cell(30,$alt,"VALOR","TBL",0,"C");
  $pdf->cell(30,$alt,"% SOBRE RCL","TBL",1,"C");

  // for ($i = 26; $i <= 31; $i++) {

  $aLinhasMaiores27 = array_slice($aLinhasRelatorio, 26, 9, true);
//  var_dump($aLinhasMaiores27);
  foreach ($aLinhasMaiores27 as $oLinha) {

    $pdf->setfont('arial','',6);
    $pdf->cell(130,$alt,$oLinha->label,"TBR",0,"L");
    $pdf->setfont('arial','',7);
    if (!isset($oLinha->nobimestre)){
      echo $i;
      exit;
    }
    $pdf->cell(30,$alt,db_formatar($oLinha->nobimestre,"f"),"TBR",0,"R");
    if ($i == 26) {
      $pdf->cell(30,$alt, $oLinha->atebimestre,"TBL",1,"C");
    } else {
      $pdf->cell(30,$alt, db_formatar($oLinha->atebimestre,'f'),"TBL",1,"R");
    }
  }

  $pdf->cell(190,$alt,"","TB",1,"L");
  $pdf->setfont('arial','',6);
  $pdf->cell(130,$alt,$aLinhasRelatorio[32]->label,"TBR",0,"L");
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt, db_formatar($aLinhasRelatorio[32]->nobimestre,"f"),"TBR",0,"R");
  $pdf->cell(30,$alt, db_formatar($aLinhasRelatorio[32]->atebimestre,"f"),"TBL",1,"R");

  $pdf->ln();
  // ----------------------------------------------------------------
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa($pdf, $iCodigoPeriodo);

  $pdf->Ln(5);

  // assinaturas
  $pdf->setfont('arial','',5);
  $pdf->ln(20);

  assinaturas($pdf,$classinatura,'GF');

  $pdf->Output();
}

$total_operacoes_credito      = 0;
$perc_total_operacoes_credito = 0;

$total_operacoes_credito = $nTotalExternas+$nTotalInternas;
if ($nTotalRcl > 0){
  $perc_total_operacoes_credito	= (($nTotalExternas + $nTotalInternas) / $nTotalRcl)*100;
}

$total_antecipacao_receita = 0;
$perc_antecipacao_receita  = 0;

$total_antecipacao_receita	= $nTotalOperacoesCredito ;
if ($nTotalRcl > 0){
  $perc_antecipacao_receita	= (($nTotalOperacoesCredito)/$nTotalRcl)*100;
}

// Variaveis do relatorio
$valor_int_ext     = 0;
$valor_antecipacao = 0;
// Fim das Variaveis
$perc_limite_senado_int_ext     = 0;
$perc_limite_senado_antecipacao = 0;

if ($valor_int_ext > 0){
  $perc_limite_senado_int_ext = ($valor_int_ext);
  $limite_senado_int_ext      = (($nTotalRcl*$valor_int_ext)/100);
} else {
  $limite_senado_int_ext = 0;
}

if ($valor_antecipacao > 0){
  $perc_limite_senado_antecipacao = ($valor_antecipacao);
  $limite_senado_antecipacao      = (($nTotalRcl*$valor_antecipacao)/100);
} else {
  $limite_senado_antecipacao = 0;
}
function somaRCLPeriodoAnexo3($aRCLAtual, $aRCLAnterior, $aRCL) {

  $nValorPrimeiroPeriodo = 0;
  foreach ($aRCL["anterior"] as $mes) {

    if (isset($aRCLAnterior[$mes])) {
       $nValorPrimeiroPeriodo += $aRCLAnterior[$mes];
    }
  }

  foreach ($aRCL["atual"] as $mes) {

    if (isset($aRCLAtual[$mes])) {
      $nValorPrimeiroPeriodo += $aRCLAtual[$mes];
    }
  }
  return $nValorPrimeiroPeriodo;
}

?>