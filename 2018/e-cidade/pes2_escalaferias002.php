<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_sql.php"));

$oGet = db_utils::postMemory($_GET);

/**
 * Periodo de calculo da folha 
 */
$iAno = db_anofolha();
$iMes = db_mesfolha();

/**
 * Filtros
 */   
$sTipoRelatorio    = $oGet->sTipoRelatorio;
$sTipoFiltro       = $oGet->sTipoFiltro;
$sTipoOrdem        = $oGet->sTipoOrdem;
$lImprimeAfastados = $oGet->lImprimeAfastados == 'true' ? true : false;

$sSelecionados     = '';
$sWhereSelecao     = ''; 
$iIntervaloInicial = 0;
$iIntervaloFinal   = 0; 
$iRegime           = 0;

/**
 * Data do perido das ferias 
 */
$sDataPesquisa = implode("-", array_reverse(explode("/",$oGet->periodo))); 

/**
 * Intervalo inicial 
 * tipo de filtro, intervalo
 */
if ( !empty($oGet->iIntervaloInicial) ) {
  $iIntervaloInicial = $oGet->iIntervaloInicial;
}

/**
 * Intervalo final 
 * tipo de filtro, intervalo
 */
if ( !empty($oGet->iIntervaloFinal) ) {
  $iIntervaloFinal = $oGet->iIntervaloFinal;
}

/**
 * string com os codigos dos registros pelo tipo do relatorio 
 */
if ( !empty($oGet->sSelecionados) ) {
  $sSelecionados = $oGet->sSelecionados;
}

if ( !empty($oGet->iRegime) ) {
  $iRegime = $oGet->iRegime;
}

/**
 * Periodo da pessoalmov 
 */
$sWhereFuncionarios  = "       rh02_anousu = $iAno ";
$sWhereFuncionarios .= "   and rh02_mesusu = $iMes ";

/**
 * SELECAO
 * procura na tabela selecao o campo r44_where e adiciona no where dos funcionarios
 */
if ( !empty($oGet->iSelecao) && (int) $oGet->iSelecao > 0 ) {

  $oDaoSelecao = db_utils::getDao('selecao');
  $sSqlSelecao = $oDaoSelecao->sql_query_file((int) $oGet->iSelecao);
  $rsSelecao   = $oDaoSelecao->sql_record($sSqlSelecao);

  if ( $oDaoSelecao->numrows > 0 ) {
    
    $oSelecao = db_utils::fieldsMemory($rsSelecao, 0);
    $sWhereFuncionarios .= " and {$oSelecao->r44_where} ";
  }
}

/**
 * INSTITUICAO
 */   
$sWhereFuncionarios .= " and rh02_instit = " . db_getsession("DB_instit") . " ";

/**
 * Codigo do regime 
 * 1 - Estatutário
 * 2 - CLT
 * 3 - Extra Quadro
 */
if ( !empty($iRegime) ) {
  $sWhereFuncionarios .= " and rh30_regime = {$iRegime}";
}

/**
 * AFASTAMENTO
 * Procura funcioionarios que nao possuam afastamento em aberto
 */
$sWhereFuncionarios .= " and not exists(select true                      ";                   
$sWhereFuncionarios .= "                  from afasta                    ";
$sWhereFuncionarios .= "                 where r45_regist = rh01_regist  ";
$sWhereFuncionarios .= "                   and r45_anousu = rh02_anousu and r45_mesusu = rh02_mesusu  ";
$sWhereFuncionarios .= "                   and r45_dtreto is null)       ";

/**
 * AFASTAMENTO
 * usuario marcou imprime afastados: nao, no formulario
 * Nao busca servidores com afastamento na data atual, DB_datausu 
 */
if ( !$lImprimeAfastados ) {

  $sWhereFuncionarios .= " and not exists( select true                                                                ";                   
  $sWhereFuncionarios .= "                   from afasta                                                              ";
  $sWhereFuncionarios .= "                  where r45_regist = rh01_regist                                            ";
$sWhereFuncionarios .= "                   and r45_anousu = rh02_anousu and r45_mesusu = rh02_mesusu  ";
  $sWhereFuncionarios .= "                    and fc_getsession('DB_datausu')::date between r45_dtafas and r45_dtreto ";
  $sWhereFuncionarios .= "               )                                                                            ";
}

/**
 * ADMISSAO
 * Busca com data de admisao menor ou igual ao periodo informado 
 */
$sWhereFuncionarios .= " and rh01_admiss::date <= '{$sDataPesquisa}' ";

/**
 * RESCISAO
 * Servidores sem rescisao ou com data de rescisao maior ou igual a data do perido informado
 */
$sWhereFuncionarios .= " and not exists( select true                                   ";
$sWhereFuncionarios .= "                   from rhpesrescisao                          ";
$sWhereFuncionarios .= "                  where rh05_seqpes = rh02_seqpes              ";
$sWhereFuncionarios .= "                    and rh05_recis::date <= '{$sDataPesquisa}' ";
$sWhereFuncionarios .= "               )                                               ";

/**
 * Busca apenas servidores ativos 
 * A - Ativo, I - Inativo, P - Pensionista
 */
$sWhereFuncionarios .= " and rh30_vinculo = 'A' ";

/**
 * Campos de acordo com o tipo de relatorio escolhido no formulario
 * 
 * @var $sTituloAgrupador         - Titulo do agrupador   
 * @var $sCampoTipoRelatorio      - campo por tipo de relatorio, usado no where, com in() ou between
 * @var $sCampoAgrupadorCodigo    - campo do codigo do agrupador
 * @var $sCampoAgrupadorDescricao - campo da descricao do agrupador 
 */
switch ( $sTipoRelatorio ) {
  
  /**
   * Tipo de relatorio por ORGAO  
   */
  case 'orgao' :

    $sTituloAgrupador         = 'Orgão';
    $sCampoTipoRelatorio      = 'o40_orgao';
    $sCampoAgrupadorCodigo    = "o40_orgao";
    $sCampoAgrupadorDescricao = "o40_descr";

  break;

  /**
   * Tipo de relatorio por LOCACAO 
   */
  case 'lotacao' :

    $sTituloAgrupador         = 'Lotação';
    $sCampoTipoRelatorio      = 'rh02_lota';
    $sCampoAgrupadorCodigo    = 'r70_estrut';
    $sCampoAgrupadorDescricao = 'r70_descr';

  break; 

  /**
   * Tipo de relatorio por LOCAIS DE TRABALHO 
   */
  case 'locaistrabalho' :

    $sTituloAgrupador         = 'Local de trabalho';
    $sCampoTipoRelatorio      = 'rh56_localtrab';
    $sCampoAgrupadorCodigo    = "rh55_estrut";
    $sCampoAgrupadorDescricao = "rh55_descr";

  break;

  /**
   * Tipo de relatorio por MATRICULA 
   */
  case 'matricula' :

    $sTituloAgrupador         = 'Matrícula';
    $sCampoTipoRelatorio      = 'rh01_regist';
    $sCampoAgrupadorCodigo    = "rh01_regist";
    $sCampoAgrupadorDescricao = "z01_nome";

  break;

  /**
   * Tipo de relatorio GERAL 
   */
  default :

    $sTituloAgrupador         = null;
    $sCampoTipoRelatorio      = null;
    $sCampoAgrupadorCodigo    = "0";
    $sCampoAgrupadorDescricao = "''::text";

  break;
}

/**
 * Monta where por tipo de filtro, selecionado ou intervado de acordo com o tipo do relatorio
 * $sTipoRelatorio: lotacao, orgao, matricula ou local de trabalho 
 */
if ( !empty($sCampoTipoRelatorio) ) {

  /**
   * Tipo do filtro, selecionado ou intervado 
   */
  switch ($sTipoFiltro) {
    
    case 'intervalo' :
      $sWhereFuncionarios .= " and {$sCampoTipoRelatorio} between {$iIntervaloInicial} and {$iIntervaloFinal} ";
    break;

    case 'selecionado' :
      $sWhereFuncionarios .= " and {$sCampoTipoRelatorio} in($sSelecionados) ";
    break;
  }
}

/**
 * ORDENACAO - numerica
 * 1º - Codigo agrupador, 2º - descricao agrupador, 3º - matricula, 4º - nome funcionario  
 */
$sOrdemFuncionarios = " 6, 7, 1, 2";

/**
 * ORDENACAO - alfabetica  
 * 1º - Descricao agrupador, 2º codigo agrupador, 3º - nome funcionario, 4º - matricula
 */
if ( $sTipoOrdem == 'alfabetica' ) {
  $sOrdemFuncionarios = " 7, 6, 2, 1 ";
}

/**
 * Campos dos funcionarios
 */
$sCamposFuncionarios  = " rh01_regist, ";
$sCamposFuncionarios .= " z01_nome,    ";
$sCamposFuncionarios .= " rh02_hrsmen, ";
$sCamposFuncionarios .= " rh01_admiss, ";
$sCamposFuncionarios .= " rh37_descr,  ";
$sCamposFuncionarios .= " {$sCampoAgrupadorCodigo}    as agrupador_codigo,   "; 
$sCamposFuncionarios .= " {$sCampoAgrupadorDescricao} as agrupador_descricao "; 

/**
 * Monta sql dos funcionarios 
 */
$sSqlFuncionarios  = "select distinct {$sCamposFuncionarios}                   ";
$sSqlFuncionarios .= "  from rhpessoalmov                                      ";
$sSqlFuncionarios .= "       inner join rhpessoal on rh01_regist = rh02_regist ";

/**
 * Cargo
 */   
$sSqlFuncionarios .= " inner join rhfuncao  on rh02_funcao = rh37_funcao ";
$sSqlFuncionarios .= "                     and rh02_instit = rh37_instit ";

/**
 * CGM
 */   
$sSqlFuncionarios .= " inner join cgm on rh01_numcgm = z01_numcgm  ";

/**
 * Regime 
 */
$sSqlFuncionarios .= " inner join rhregime  on rh30_codreg = rh02_codreg "; 
$sSqlFuncionarios .= "                     and rh30_instit = rh02_instit ";

/**
 * Lotacao
 */
$sSqlFuncionarios .= " left join rhlota     on rh02_lota   = r70_codigo   ";
$sSqlFuncionarios .= " left join rhlotaexe  on rh26_codigo = rh02_lota    ";
$sSqlFuncionarios .= "                     and rh26_anousu = rh02_anousu  ";

/**
 * Orgao 
 */
$sSqlFuncionarios .= " left join orcorgao  on o40_orgao  = rh26_orgao   ";
$sSqlFuncionarios .= "                    and o40_anousu = rh26_anousu  ";

/**
 * Locais de trabalho 
 */
$sSqlFuncionarios .= " left join rhpeslocaltrab  on rh56_seqpes    = rh02_seqpes  ";
$sSqlFuncionarios .= "                          and rh56_princ     = 't'          "; 
$sSqlFuncionarios .= " left join rhlocaltrab     on rh56_localtrab = rh55_codigo  ";

/**
 * Where 
 */
$sSqlFuncionarios .= " where {$sWhereFuncionarios} ";

/**
 * Subquery
 */   
$sSqlFuncionarios = " select * from ({$sSqlFuncionarios}) as subquery order by $sOrdemFuncionarios";
$rsFuncionarios   = db_query($sSqlFuncionarios);

/**
 * Erro na query 
 */
if ( !$rsFuncionarios ) {

  $sMensagemErro = 'Erro ao buscar funcionarios: ' . pg_last_error();
  db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode($sMensagemErro) );
  exit;
}

/**
 * Nenhum registro encontrado 
 */
if ( pg_num_rows($rsFuncionarios) == 0 ) {

  $sMensagemErro = 'Nenhum funcionário encontrado com os filtros utilizados.';
  db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode($sMensagemErro) );
  exit;
}

/**
 * Criamos um array com os agrupadores e seus funcionarios..
 * teremos um array com a seguinte estrutura:
 * Agrupador -> Funcionarios -> Dados das Férias abertas
 */
$aAgrupador         = array();
$iTotalFuncionarios = pg_num_rows($rsFuncionarios); 

for ($i = 0; $i < $iTotalFuncionarios; $i++) {

  $oDados = db_utils::fieldsMemory($rsFuncionarios, $i);

  if ( !isset($aAgrupador[$oDados->agrupador_codigo]) ) {
    
    $aAgrupador[$oDados->agrupador_codigo] = new stdClass();
    $aAgrupador[$oDados->agrupador_codigo]->sAgrupadorDescricao = $oDados->agrupador_descricao;
    $aAgrupador[$oDados->agrupador_codigo]->funcionarios        = array();   
  }

  $oFuncionario                       = new stdClass();
  $oFuncionario->matricula            = $oDados->rh01_regist;
  $oFuncionario->nome                 = $oDados->z01_nome;
  $oFuncionario->dataadmissao         = db_formatar($oDados->rh01_admiss, "d");
  $oFuncionario->periodogozadoinicial = '';
  $oFuncionario->periodogozadofinal   = '';
  $oFuncionario->periodoaquisitivoini = '';
  $oFuncionario->periodoaquisitivofim = '';
  $oFuncionario->periodosvencidos     = array();
  
  /**
   * Último período de férias gozados pelo funcionário
   */
  $sSqlUltimoPeriodoGozado  = "SELECT distinct r30_regist,"; 
  $sSqlUltimoPeriodoGozado .= "       r30_perai, ";
  $sSqlUltimoPeriodoGozado .= "       r30_peraf,";
  $sSqlUltimoPeriodoGozado .= "       r30_per1i,";
  $sSqlUltimoPeriodoGozado .= "       r30_per1f,";
  $sSqlUltimoPeriodoGozado .= "       r30_per2i,";
  $sSqlUltimoPeriodoGozado .= "       r30_per2f,";
  $sSqlUltimoPeriodoGozado .= "       r30_dias1,";
  $sSqlUltimoPeriodoGozado .= "       r30_dias2,";
  $sSqlUltimoPeriodoGozado .= "       r30_ndias,";
  $sSqlUltimoPeriodoGozado .= "       r30_abono ";
  $sSqlUltimoPeriodoGozado .= "  from cadferia ";
  $sSqlUltimoPeriodoGozado .= " where r30_anousu = {$iAno}";
  $sSqlUltimoPeriodoGozado .= "   and r30_mesusu = {$iMes} ";
  $sSqlUltimoPeriodoGozado .= "   and r30_regist = {$oDados->rh01_regist}";
  $sSqlUltimoPeriodoGozado .= " order by r30_perai desc limit 1";

  $rsULtimoPeriodoGozado    = db_query($sSqlUltimoPeriodoGozado);
  $iTemFerias               = pg_num_rows($rsULtimoPeriodoGozado); 

  if ($iTemFerias > 0) {
    
    $oDadosPeriodoGozado                = db_utils::fieldsMemory($rsULtimoPeriodoGozado, 0);
    $oFuncionario->periodogozadoinicial = db_formatar($oDadosPeriodoGozado->r30_per1i, "d");
    $oFuncionario->periodogozadofinal   = db_formatar($oDadosPeriodoGozado->r30_per1f, "d");
    $oFuncionario->periodoaquisitivoini = $oDadosPeriodoGozado->r30_perai;
    $oFuncionario->periodoaquisitivofim = $oDadosPeriodoGozado->r30_peraf;

    if ($oDadosPeriodoGozado->r30_per2f != "") {

      $oFuncionario->periodogozadoinicial = db_formatar($oDadosPeriodoGozado->r30_per2i, "d");
      $oFuncionario->periodogozadofinal   = db_formatar($oDadosPeriodoGozado->r30_per2f, "d");  
    }
  }
  
  /**
   * Verificamos quais as ferias que estão em aberto funcionario.
   * caso o usuario não possui ferias com dias em gozo, devemos calcular o primeiro periodo das ferias 
   * acrescentando 1 ano na data de admissao; 
   */
  $sSqlFeriasCadastradas  = "SELECT distinct r30_regist,"; 
  $sSqlFeriasCadastradas .= "       r30_perai, ";
  $sSqlFeriasCadastradas .= "       r30_peraf,";
  $sSqlFeriasCadastradas .= "       r30_per1i,";
  $sSqlFeriasCadastradas .= "       r30_per1f,";
  $sSqlFeriasCadastradas .= "       r30_per2i,";
  $sSqlFeriasCadastradas .= "       r30_per2f,";
  $sSqlFeriasCadastradas .= "       r30_dias1,";
  $sSqlFeriasCadastradas .= "       r30_dias2,";
  $sSqlFeriasCadastradas .= "       r30_ndias, ";
  $sSqlFeriasCadastradas .= "       coalesce(r30_dias1,0)+coalesce(r30_dias2,0) as diasgozados, ";
  $sSqlFeriasCadastradas .= "       r30_abono";
  $sSqlFeriasCadastradas .= "  from cadferia ";
  $sSqlFeriasCadastradas .= " where coalesce(r30_dias1,0)+coalesce(r30_dias2,0) < r30_ndias ";
  $sSqlFeriasCadastradas .= "   and r30_peraf <= '{$sDataPesquisa}'"; 
  $sSqlFeriasCadastradas .= "   and r30_anousu = {$iAno}";
  $sSqlFeriasCadastradas .= "   and r30_mesusu = {$iMes} ";
  $sSqlFeriasCadastradas .= "   and r30_regist = {$oDados->rh01_regist}";
  $sSqlFeriasCadastradas .= " order by r30_perai asc";
  
  $nUltimaData = '';

  if ($iTemFerias == 0) {

     $sDataInicial = $oDados->rh01_admiss;

  } else {
    
    /**
     * periodos de ferias ainda nao gozados completamentes. 
     */
    $rsFeriasVencidas     = db_query($sSqlFeriasCadastradas);
    $iTotalFeriasVencidas = pg_num_rows($rsFeriasVencidas); 
    if ($iTotalFeriasVencidas > 0) {
      
      for ($iFerias = 0; $iFerias < $iTotalFeriasVencidas; $iFerias++) {

        $oDadosFerias = db_utils::fieldsMemory($rsFeriasVencidas, $iFerias);
        $oPeriodo = new stdClass();
        $oPeriodo->diasgozo    = $oDadosFerias->r30_ndias;
        $oPeriodo->diasgozados = $oDadosFerias->diasgozados;
        $oPeriodo->datainicial = $oDadosFerias->r30_perai; 
        $oPeriodo->datafinal   = $oDadosFerias->r30_peraf; 
        $oPeriodo->diasabono   = $oDadosFerias->r30_abono; 
        $aDataFinal  = explode("-", $oPeriodo->datafinal);
        $sDataLimite = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[2]-30, $aDataFinal[0]+1)); 
        $oPeriodo->limite      = $sDataLimite; 
        $oFuncionario->periodosvencidos[] = $oPeriodo;
      }
    }
  }

  if ($oFuncionario->periodoaquisitivofim != "") {

    $aDataFinal  = explode("-", $oFuncionario->periodoaquisitivofim);
    $sDataLimite = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[2]+1, $aDataFinal[0])); 
    $sDataInicial = $oFuncionario->periodoaquisitivofim;     
  }

  /**
   * Criamos os novos periodos aquisivos...
   */
  $lTemFeriasVencidas = true;

  while ($lTemFeriasVencidas) {
        
    $oPeriodo = new stdClass();
    $oPeriodo->diasgozo    = 30;
    $oPeriodo->diasgozados = '';
    $oPeriodo->datainicial  = $sDataInicial;
    $aDataInicial   = explode("-", $sDataInicial);
    $sUltimaData    = date("Y-m-d", mktime(0, 0, 0, $aDataInicial[1]+12, $aDataInicial[2]-1, $aDataInicial[0]));
    $oPeriodo->datafinal   = $sUltimaData;
    $aDataFinal   = explode("-", $oPeriodo->datafinal);
    $sDataLimite = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1]+12, $aDataFinal[2]-30, $aDataFinal[0]));
    $oPeriodo->limite      = $sDataLimite;
    $oPeriodo->diasabono   = '';
    if (db_strtotime($oPeriodo->datafinal) >= db_strtotime($sDataPesquisa)) {
      $lTemFeriasVencidas = false;
    } else {
      $oFuncionario->periodosvencidos[] = $oPeriodo;
    }      
    $aDataFinal   = explode("-", $sUltimaData);
    $sDataInicial = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[2]+1, $aDataFinal[0]));;
  }

  $aAgrupador[$oDados->agrupador_codigo]->funcionarios[] = $oFuncionario;
}

/**
 * Array com as descricoes usadas no header do pdf7
 */
$aDescricoes = array(
  'numerica'       => 'Numérica',
  'alfabetica'     => 'Alfabética',
  'selecionado'    => 'Selecionado',
  'intervalo'      => 'Intervalo',
  'geral'          => 'Geral',
  'orgao'          => 'Orgão',
  'lotacao'        => 'Lotação',
  'matricula'      => 'Matrícula',
  'locaistrabalho' => 'Locais de Trabalho'
);

/**
 * Monta PDF 
 */
$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);;
$pdf->setfillcolor(235);
$imprime_cabecalho = true;
$iAlt = 4;
$sFonte = "Arial";
$head1 = "";
$head2 = "ESCALA DE FÉRIAS";
$head3 = "Período: ".$iMes." / ".$iAno;
$head4 = "Períodos Vencidos até: {$oGet->periodo}";
$head5 = "Tipo de Relatório: {$aDescricoes[ $sTipoRelatorio ]}";
$head6 = "Tipo de Filtro: {$aDescricoes[ $sTipoFiltro ]}";

if ( $sTipoRelatorio != 'geral' ) {
  $head6 = "Tipo de Filtro: {$aDescricoes[ $sTipoFiltro ]}";
}

$head8 = "Tipo de Ordem: {$aDescricoes[ $sTipoOrdem ]}";
$head7 = "Imprime Afastados: " . ($lImprimeAfastados ? 'Sim' : 'Não');

foreach ($aAgrupador as $iAgrupador => $oAgrupador) {
  
  $iTotalSemFerias = 0;
  $iTotalFuncionariosFeriasVencidas = 0;

  foreach ($oAgrupador->funcionarios as $oFuncionario) {

    if (count($oFuncionario->periodosvencidos) == 0) {
      $iTotalSemFerias++;    
    }
  }

  if ($iTotalSemFerias == count($oAgrupador->funcionarios)) {
    continue;
  }

  $pdf->AddPage();

  if ( !empty($iAgrupador)  ) {

    $pdf->setfont($sFonte, 'b', 10);  
    $pdf->cell(0, $iAlt, "{$sTituloAgrupador}: {$iAgrupador} - {$oAgrupador->sAgrupadorDescricao}", 0, 1, "L");
  }
  $pdf->setfont($sFonte, 'b', 8);
  $pdf->cell(50, $iAlt, 'Funcionário', "TBR", 0, "C", 1);
  $pdf->cell(50, $iAlt, 'Período Aquisitivo', 1, 0, "C", 1);
  $pdf->cell(15, $iAlt, 'Gozados', 1, 0, "C", 1);
  $pdf->cell(15, $iAlt, 'A Gozar', 1, 0, "C", 1);
  $pdf->cell(20, $iAlt, 'Limite', "TBL", 0, "C", 1);
  $pdf->cell(50, $iAlt, 'Programação', 1, 0, "C", 1);
  $pdf->cell(15, $iAlt, 'Abono', 1, 0, "C", 1);
  $pdf->cell(60, $iAlt, 'Assinatura', "TBL", 1, "C", 1);
  $pdf->setfont($sFonte, '', 7);

  foreach ($oAgrupador->funcionarios as $oFuncionario) {
    
    if (count($oFuncionario->periodosvencidos) == 0) {
      continue;
    }    

    $iTotalFuncionariosFeriasVencidas++;

    $iAlturaOcupadoFuncionario = count($oFuncionario->periodosvencidos);

    if ($iAlturaOcupadoFuncionario < 2) {
      $iAlturaOcupadoFuncionario = 2;
    }

    if ($pdf->GetY() > $pdf->h - (15 +($iAlturaOcupadoFuncionario * $iAlt))) {
        
      $pdf->AddPage();

      if ( !empty($iAgrupador)  ) {

        $pdf->setfont($sFonte, 'b', 10);  
        $pdf->cell(0, $iAlt, "{$sTituloAgrupador} {$iAgrupador} - {$oAgrupador->sAgrupadorDescricao}", 0, 1, "L");
      }
      $pdf->setfont($sFonte, 'b', 8);
      $pdf->cell(50, $iAlt, 'Funcionário', "TBR", 0, "C", 1);
      $pdf->cell(50, $iAlt, 'Período Aquisitivo', 1, 0, "C", 1);
      $pdf->cell(15, $iAlt, 'Gozados', 1, 0, "C", 1);
      $pdf->cell(15, $iAlt, 'A Gozar', 1, 0, "C", 1);
      $pdf->cell(20, $iAlt, 'Limite', "TBL", 0, "C", 1);
      $pdf->cell(50, $iAlt, 'Programação', 1, 0, "C", 1);
      $pdf->cell(15, $iAlt, 'Abono', 1, 0, "C", 1);
      $pdf->cell(60, $iAlt, 'Assinatura', "TBL", 1, "C", 1);
      $pdf->setfont($sFonte, '', 7);
    }

    $iAltura = $pdf->getY(); 
    $pdf->cell(50, $iAlt, substr(($oFuncionario->matricula." - ".$oFuncionario->nome),0, 33), "TR", 1, "L");
    $sPeriodoGozado =  "Último Gozo: {$oFuncionario->periodogozadoinicial} a {$oFuncionario->periodogozadofinal}";
    $pdf->cell(50, $iAlt, $sPeriodoGozado, "R", 1, "C");
    $iAlturaGozo   = $pdf->getY();
    $pdf->setxy(60, $iAltura);

    foreach ($oFuncionario->periodosvencidos as $oPeriodo) {
      
      $pdf->setx(60);
      $sPeriodoAquisitivo = db_formatar($oPeriodo->datainicial, "d")." - ".db_formatar($oPeriodo->datafinal, "d");
      $pdf->cell(50, $iAlt, $sPeriodoAquisitivo, "LR", 0, "C");
      $pdf->cell(15, $iAlt, $oPeriodo->diasgozados, "LR", 0, "C");
      $pdf->cell(15, $iAlt, ($oPeriodo->diasgozo - $oPeriodo->diasgozados - $oPeriodo->diasabono), "LR", 0, "C");
      $pdf->cell(20, $iAlt, db_formatar($oPeriodo->limite, "d"), "LR", 0, "C");
      $pdf->cell(50, $iAlt, "____/____/_______ a ____/____/______", "LR", 0, "C");
      $pdf->cell(15, $iAlt, $oPeriodo->diasabono, "LR", 0, "C");
      $pdf->cell(60, $iAlt, "", "L", 0, "C");
      $pdf->ln();
    }

    $iAlturaLinha = $pdf->GetY(); 

    if ($iAlturaGozo > $pdf->GetY()) {
      
      $pdf->ln();
      $iAlturaLinha = $iAlturaGozo;
      $pdf->Line(110, $iAltura, 110, $iAlturaGozo);
      $pdf->Line(125, $iAltura, 125, $iAlturaGozo);
      $pdf->Line(140, $iAltura, 140, $iAlturaGozo);
      $pdf->Line(160, $iAltura, 160, $iAlturaGozo);
      $pdf->Line(210, $iAltura, 210, $iAlturaGozo);
      $pdf->Line(225, $iAltura, 225, $iAlturaGozo);
    }

    $pdf->Line(10, $iAlturaLinha, 285, $iAlturaLinha);
    $pdf->Line(10, $iAltura, 285, $iAltura);
    
    $pdf->ln();
  }

  $pdf->setfont($sFonte, 'b', 8);
  $pdf->cell(0, $iAlt, "Total de Funcionários: " . $iTotalFuncionariosFeriasVencidas, '0', 0, "L", 0);
  $pdf->setfont($sFonte, '', 7);
}

$pdf->Output();