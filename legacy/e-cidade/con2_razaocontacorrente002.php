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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");


// objeto com variaveis passadas por querystring
$oGet = db_utils::postMemory($_GET,0);

// definimos variaveis selecionadas nos filtros
$dDataInicial    = $oGet->dtInicial;
$dDataFinal      = $oGet->dtFinal;
$sDataInicial    = implode("-", array_reverse(explode("/",$dDataInicial)));
$sDataFinal      = implode("-", array_reverse(explode("/",$dDataFinal)));
$iContaCorrente  = $oGet->iContaCorrente;
$sReduzidos      = $oGet->sListaReduzido;
$aDadosRelatorio = array();
$aAtributos      = array();
$iInstit         = db_getsession("DB_instit");

$oContaCorrente  = new ContaCorrente($iContaCorrente);
$sContaCorrente  = $oContaCorrente->getContaCorrente();
$sDescricaoConta = $oContaCorrente->getDescricao();

$sCamposContaCorrenteDetalhe  = " distinct ";
$sCamposContaCorrenteDetalhe .= "c19_contacorrente, ";
$sCamposContaCorrenteDetalhe .= "c19_reduz, ";
$sCamposContaCorrenteDetalhe .= "c19_orcunidadeanousu, ";
$sCamposContaCorrenteDetalhe .= "c19_orcunidadeorgao, ";
$sCamposContaCorrenteDetalhe .= "c19_orcunidadeunidade, ";
$sCamposContaCorrenteDetalhe .= "c19_orcorgaoanousu, ";
$sCamposContaCorrenteDetalhe .= "c19_orcorgaoorgao, ";
$sCamposContaCorrenteDetalhe .= "c19_conplanoreduzanousu, ";
$sCamposContaCorrenteDetalhe .= "c19_acordo, ";
$sCamposContaCorrenteDetalhe .= "c19_instit, ";
$sCamposContaCorrenteDetalhe .= "z01_numcgm, z01_nome, z01_cgccpf, ";
$sCamposContaCorrenteDetalhe .= "db83_descricao, ";
$sCamposContaCorrenteDetalhe .= "db83_bancoagencia, ";
$sCamposContaCorrenteDetalhe .= "db83_conta, ";
$sCamposContaCorrenteDetalhe .= "db83_dvconta, ";
$sCamposContaCorrenteDetalhe .= "db83_identificador, ";
$sCamposContaCorrenteDetalhe .= "db83_codigooperacao, ";
$sCamposContaCorrenteDetalhe .= "db83_tipoconta, ";
$sCamposContaCorrenteDetalhe .= "db83_contaplano,";
$sCamposContaCorrenteDetalhe .= "o15_descr, ";
$sCamposContaCorrenteDetalhe .= "o40_descr, ";
$sCamposContaCorrenteDetalhe .= "o41_descr, c58_descr, ";
$sCamposContaCorrenteDetalhe .= "nomeinst, ";
$sCamposContaCorrenteDetalhe .= "c60_estrut, ";
$sCamposContaCorrenteDetalhe .= "c60_descr, ";
$sCamposContaCorrenteDetalhe .= "ac16_numero, ";
$sCamposContaCorrenteDetalhe .= "ac16_anousu ";

$sWhereContaCorrenteDetalhe  = "     c19_instit = {$iInstit} ";
$sWhereContaCorrenteDetalhe .= " and c19_contacorrente = {$iContaCorrente} ";
$sWhereContaCorrenteDetalhe .= " and (c69_data between '{$sDataInicial}' and '{$sDataFinal}') ";

if ($sReduzidos != "") {
	$sWhereContaCorrenteDetalhe .= " and c19_reduz in ({$sReduzidos}) ";
}

if (!empty($oGet->sListaCredor) && ($iContaCorrente == 19 || $iContaCorrente == 3 )) {
	$sWhereContaCorrenteDetalhe .= " and c19_numcgm in ({$oGet->sListaCredor}) ";
}

if ($oGet->iPrestacaoContas != "" && $iContaCorrente == 19) {
	$sWhereContaCorrenteDetalhe .= " and e45_tipo = {$oGet->iPrestacaoContas} ";
}

require_once "classes/db_contacorrentedetalhe_classe.php";
$oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe;
$sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_fileAtributos(null, $sCamposContaCorrenteDetalhe, "c19_reduz, z01_nome", "$sWhereContaCorrenteDetalhe" );
$rsContaCorrenteDetalhe   = $oDaoContaCorrenteDetalhe->sql_record($sSqlContaCorrenteDetalhe);
$aContacorrenteDetalhe    = db_utils::getColectionByRecord($rsContaCorrenteDetalhe);
if ($oDaoContaCorrenteDetalhe->erro_status == "0") {

  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para os filtros selecionados.");
  exit;
}

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);

$total        = 0;
$troca        = 1;
$iAlturalinha = 4;
$iFonte       = 6;

function getLancamentosContaCorrenteDetalhe($iNumcgm, $dtInicial, $dtFinal, $iFiltroContaCorrente, $iInstituicao, $iReduzido, $iCodOrgao, $iCodUnidade, $iAnousu){

  $sCampos  = " distinct c71_data,   ";
  $sCampos .= "c69_codlan, ";
  $sCampos .= "c53_coddoc, ";
  $sCampos .= "c53_descr,  ";
  $sCampos .= "c28_tipo,   ";
  $sCampos .= "c69_valor   ";

  $sSqlLancamentos  = " select {$sCampos}      ";
  $sSqlLancamentos .= "   from conlancamval " ;
  $sSqlLancamentos .= "        inner join conlancam on conlancam.c70_codlan = conlancamval.c69_codlan ";
  $sSqlLancamentos .= "                           and conlancam.c70_anousu = conlancamval.c69_anousu";
  $sSqlLancamentos .= "        inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamval.c69_codlan ";
  $sSqlLancamentos .= "        inner join conhistdoc on conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc ";
  $sSqlLancamentos .= "        inner join contacorrentedetalheconlancamval on contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen ";
  $sSqlLancamentos .= "        inner join contacorrentedetalhe on contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe";
  $sSqlLancamentos .= "  where c69_data between '{$dtInicial}' and '{$dtFinal}' ";
  $sSqlLancamentos .= "    and c19_contacorrente = {$iFiltroContaCorrente} ";
  $sSqlLancamentos .= "    and c19_reduz = {$iReduzido}  ";
  $sSqlLancamentos .= "    and c19_instit = {$iInstituicao} ";
  if (isset($iNumcgm) && !empty($iNumcgm)) {
    $sSqlLancamentos .= "    and c19_numcgm = {$iNumcgm} ";
  }
  
  if (isset($iCodOrgao) && !empty($iCodOrgao)) {
    $sSqlLancamentos .= "    and c19_orcorgaoanousu = {$iAnousu} ";
    $sSqlLancamentos .= "    and c19_orcorgaoorgao = {$iCodOrgao} ";
  }
  
  if (isset($iCodUnidade) && !empty($iCodUnidade)) {
    $sSqlLancamentos .= "    and c19_orcunidadeunidade = {$iCodUnidade} ";
  }
  
  $sSqlLancamentos .= "  order by c69_codlan, c53_coddoc " ;

  $rsLancamentos    = db_query($sSqlLancamentos);
  $aLancamento      = db_utils::getColectionByRecord($rsLancamentos);

  return $aLancamento;
}
 
function getSaldosIniciais ($dtInicial, $dtFinal, $iContaCorrente, $iReduzido, $iNumcgm, $iOrgao, $iUnidade) {

  $aDataInicial = explode('-', $dtInicial);
  $iDataInicial = mktime(null, null, null,$aDataInicial[1]  ,$aDataInicial[2]  , $aDataInicial[0], null);
  $dDataInicial = date('Y-m-d', $iDataInicial);

  $sCampos = " sum(case when c28_tipo = 'D'
                         then coalesce(c69_valor,0)
                         else 0
                    end ) as debito,
                 sum(case when c28_tipo = 'C'
                          then coalesce(c69_valor,0)
                          else 0
                     end ) as credito ";

  $sWhere  = "     c69_data < '{$dDataInicial}' ";
  $sWhere .= " and c19_contacorrente = {$iContaCorrente} ";
  $sWhere .= " and c19_reduz  = {$iReduzido} ";

  if (isset($iNumcgm) && !empty($iNumcgm)) {
    $sWhere .= " and c19_numcgm = {$iNumcgm} ";
  }
  
  if (isset($iOrgao) && !empty($iOrgao)) {
    $sWhere .= " and c19_orcunidadeorgao   = {$iOrgao} ";
  }
  
  if (isset($iUnidade) && !empty($iUnidade)) {
    $sWhere .= " and c19_orcunidadeunidade = {$iUnidade} ";
  }
  
  $sSql  = " select {$sCampos} ";
  $sSql .= "   from contacorrentedetalhe ";
  $sSql .= "        left join contacorrentedetalheconlancamval on contacorrentedetalhe.c19_sequencial               = contacorrentedetalheconlancamval.c28_contacorrentedetalhe ";
  $sSql .= "        left join conlancamval                     on contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen ";
  $sSql .= "  where {$sWhere} ";
  $sSql .= "  group by c19_reduz, c19_numcgm, c19_orcunidadeorgao, c19_orcunidadeunidade ";
  $rsSaldos = db_query($sSql);

  $oSadosIniciais = new stdClass();
  $oSadosIniciais->nTotalDebito  = db_utils::fieldsMemory($rsSaldos, 0)->debito;
  $oSadosIniciais->nTotalCredito = db_utils::fieldsMemory($rsSaldos, 0)->credito;

  $oDadosImplantacao = buscaDadosImplantacao($iReduzido, $iNumcgm, $iOrgao, $iUnidade);
  $oSadosIniciais->nTotalDebito  += $oDadosImplantacao->debito;
  $oSadosIniciais->nTotalCredito += $oDadosImplantacao->credito;

  return $oSadosIniciais;
}

/**
 * Busca o Saldo da Conta, na implantação
 * @param  integer  $iContaCorrente
 * @param  integer  $iReduzido
 * @param  integer  $iNumcgm
 * @param  integer  $iOrgao
 * @param  integer  $iUnidade
 * @return stdClass
 */
function buscaDadosImplantacao( $iReduzido, $iNumcgm, $iOrgao, $iUnidade) {

  $sWhere  = " c19_reduz  = {$iReduzido} ";
  if (isset($iNumcgm) && !empty($iNumcgm)) {
    $sWhere .= " and c19_numcgm = {$iNumcgm} ";
  }
  if (isset($iOrgao) && !empty($iOrgao)) {
    $sWhere .= " and c19_orcunidadeorgao   = {$iOrgao} ";
  }
  if (isset($iUnidade) && !empty($iUnidade)) {
    $sWhere .= " and c19_orcunidadeunidade = {$iUnidade} ";
  }

  $sCampos        = " sum(c29_debito) as debito, sum(c29_credito) as credito";
  $sSql           = " select {$sCampos}
                        from contacorrente
                             inner join  contacorrentedetalhe on contacorrente.c17_sequencial                =  contacorrentedetalhe.c19_contacorrente
                             inner join  contacorrentesaldo   on contacorrentesaldo.c29_contacorrentedetalhe = contacorrentedetalhe.c19_sequencial
                                                             and contacorrentesaldo.c29_mesusu               = 0
                       Where {$sWhere}";
  $rsResultado          = db_query($sSql);
  $oStdDetalhe          = new stdClass();
  $oStdDetalhe->credito = 0;
  $oStdDetalhe->debito  = 0;

  if (pg_num_rows($rsResultado) > 0) {

    $oDetalhe              = db_utils::fieldsMemory($rsResultado, 0);
    $oStdDetalhe->credito += $oDetalhe->credito;
    $oStdDetalhe->debito  += $oDetalhe->debito;
  }
  
  return $oStdDetalhe;
  
}


$sHeadTipoEvento = "";
if (isset($oGet->iPrestacaoContas) && $oGet->iPrestacaoContas != "") {

  $oDaoEmpPrestaTip = db_utils::getDao("empprestatip");
  $sSqlEmpPrestaTip = $oDaoEmpPrestaTip->sql_query_file($oGet->iPrestacaoContas);
  $rsEmpPrestaTip   = $oDaoEmpPrestaTip->sql_record($sSqlEmpPrestaTip);
  $oStdTipoEvento = db_utils::fieldsMemory($rsEmpPrestaTip, 0);
  $sHeadTipoEvento = "Prestação de Contas: {$oGet->iPrestacaoContas} - {$oStdTipoEvento->e44_descr}";
}

$head1  = "RAZÃO POR CONTA CORRENTE ";
$head2  = "CONTA CORRENTE : {$iContaCorrente} - {$sContaCorrente} - {$sDescricaoConta}  ";
$head4  = $sHeadTipoEvento;
$head6  = "PERÍODO :   {$dDataInicial}  À  {$dDataFinal}     ";

$oPdf->AddPage("P");

if ($oDaoContaCorrenteDetalhe->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para os filtros selecionados.");
}

// saldo inicial de debito e saldo inicial de credito
$iReduzConta        = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_reduz;
$nTotalGeralDebito  = 0;
$nTotalGeralCredito = 0;
$nSaldoFinalConta   = 0;

foreach ($aContacorrenteDetalhe as $oIndiceDados => $oValorDados) {

  imprimirCabecalho($oPdf, $iAlturalinha, false);

  if ($oValorDados->c19_reduz != $iReduzConta){

    $oPdf->setfont('arial','b',$iFonte);
    $oPdf->cell(100,  $iAlturalinha, "",                          "",  0, "R", 0);
    $oPdf->cell(40,  $iAlturalinha, "TOTAIS DAS MOVIMENTAÇÕES: ", "TB",  0, "R", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nTotalGeralDebito , 'f'),  "TB",  0, "R", 1);
    $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nTotalGeralCredito, 'f'), "TB",  1, "R", 1);

    $oPdf->setfont('arial','b',$iFonte);
    $oPdf->cell(100,  $iAlturalinha, "",             "",  0, "R", 0);
    $oPdf->cell(40,  $iAlturalinha, "SALDO FINAL DA CONTA: ", "TB",  0, "R", 1);
    $oPdf->setfont('arial','',6);
    if ($nSaldoInicial > 0){
      $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nSaldoInicial   , 'f'), "TB",  0, "R", 1);
      $oPdf->cell(25 ,  $iAlturalinha,                                  "", "TB",  1, "R", 1);
    }else{
      $oPdf->cell(25 ,  $iAlturalinha,                                  "", "TB",  0, "R", 1);
      $oPdf->cell(25 ,  $iAlturalinha, db_formatar(abs($nSaldoInicial), 'f'), "TB",  1, "R", 1);
    }

    $iReduzConta     = $oValorDados->c19_reduz;
    $nTotalGeralDebito  = 0;
    $nTotalGeralCredito = 0;
    $nSaldoFinalConta   = 0;
  }


  $oSaldosIniciais   = getSaldosIniciais($sDataInicial,
                                         $sDataFinal,
                                         $iContaCorrente,
                                         $iReduzConta,
                                         $oValorDados->z01_numcgm,
                                         $oValorDados->c19_orcunidadeorgao,
                                         $oValorDados->c19_orcunidadeunidade);
  $nSaldoInicial     = ($oSaldosIniciais->nTotalDebito - $oSaldosIniciais->nTotalCredito);
  // escrevemos os atributos
  $aAtributos = ContaCorrente::getAtributos($oValorDados->c19_contacorrente);
  foreach ($aAtributos as $iValor => $oValorAtributos) {

    $sDescricaoAtributo = $oValorDados->$iValor;

    if ($iValor == "c19_reduz") { // validamos o indice para concatenar estrutural e descricao da conta

      $sDescricaoAtributo = $oValorDados->$iValor . " - " . $oValorDados->c60_estrut . " - " . $oValorDados->c60_descr ;
    }

    $oPdf->setfont('arial','',6);
    $oPdf->cell(30,  $iAlturalinha, $oValorAtributos       , "",  0, "L", 0);
    $oPdf->cell(50,  $iAlturalinha, $sDescricaoAtributo  , "",  1, "L", 0);

  }

  $oPdf->setfont('arial','b',6);
  $oPdf->cell(140,  $iAlturalinha, "SALDO INICIAL: " , ""    , 0, "R", 0);
  $oPdf->setfont('arial','',6);

  if ($nSaldoInicial > 0){
    $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nSaldoInicial     , 'f'), "", 1, "R", 0);
  }else{
    $oPdf->cell(25 ,  "",                                                   "", 0, "R", 0);
    $oPdf->cell(25 ,  $iAlturalinha, db_formatar(abs($nSaldoInicial), 'f'), "", 1, "R", 0);
  }

  imprimirCabecalho($oPdf, $iAlturalinha, true);

  // aqui percorremos os varios lançamentos do filtro selecionado por contas e numcgm
  $aLancamentos = getLancamentosContaCorrenteDetalhe($oValorDados->z01_numcgm, 
  		                                               $dtInicial, 
  		                                               $dtFinal, 
  		                                               $oValorDados->c19_contacorrente,
                                                     $oValorDados->c19_instit, 
  		                                               $oValorDados->c19_reduz, 
  		                                               $oValorDados->c19_orcorgaoorgao,
                                                     $oValorDados->c19_orcunidadeunidade, 
  		                                               $oValorDados->c19_orcorgaoanousu);

  $nTotalMovDebito  = "0.00";
  $nTotalMovCredito = "0.00";

  foreach ($aLancamentos as $oLancamentos) {

    $dtLancamento = db_formatar($oLancamentos->c71_data, "d");

    $nValorDebito     = "0.00";
    $nValorCredito    = "0.00";

    switch ($oLancamentos->c28_tipo){

      case "C":
        $nValorCredito = $oLancamentos->c69_valor;
        $nTotalMovCredito += $oLancamentos->c69_valor;
      break;

      case "D":
        $nValorDebito = $oLancamentos->c69_valor;
        $nTotalMovDebito  += $oLancamentos->c69_valor;
      break;
    }

    $oPdf->setfont('arial','',6);
    $oPdf->cell(25,  $iAlturalinha, $dtLancamento                                              , "", 0, "C", 0);
    $oPdf->cell(25,  $iAlturalinha, $oLancamentos->c69_codlan                                  , "", 0, "R", 0);
    $oPdf->cell(90,  $iAlturalinha, $oLancamentos->c53_coddoc . " " .$oLancamentos->c53_descr  , "", 0, "L", 0);
    $oPdf->cell(25,  $iAlturalinha, db_formatar($nValorDebito, "f")                            , "", 0, "R", 0);
    $oPdf->cell(25,  $iAlturalinha, db_formatar($nValorCredito, 'f')                           , "", 1, "R", 0);

    imprimirCabecalho($oPdf, $iAlturalinha, false);

  }

  // calcula novo saldo
  $nSaldoInicial += ($nTotalMovDebito - $nTotalMovCredito);
  $nSaldoFinalConta += $nSaldoInicial;

  // totaliza debito e credito
  $nTotalGeralDebito  += $nTotalMovDebito;
  $nTotalGeralCredito += $nTotalMovCredito;

  //TOTAIS DA MOVIMENTACAO;
  $oPdf->setfont('arial','b',$iFonte);
  $oPdf->cell(100,  $iAlturalinha, "",                          "",  0, "R", 0);
  $oPdf->cell(40,  $iAlturalinha, "TOTAIS DA MOVIMENTAÇÃO: ", "TB",  0, "R", 1);
  $oPdf->setfont('arial','',6);
  $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nTotalMovDebito, 'f'),  "TB",  0, "R", 1);
  $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nTotalMovCredito, 'f'), "TB",  1, "R", 1);

  $oPdf->setfont('arial','b',$iFonte);
  $oPdf->cell(100,  $iAlturalinha, "",             "",  0, "R", 0);
  $oPdf->cell(40,  $iAlturalinha, "SALDO FINAL: ", "TB",  0, "R", 1);
  $oPdf->setfont('arial','',6);
  if ($nSaldoInicial > 0){
    $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nSaldoInicial     , 'f'), "TB",  0, "R", 1);
    $oPdf->cell(25 ,  $iAlturalinha,                                    "", "TB",  1, "R", 1);
  }else{
    $oPdf->cell(25 ,  $iAlturalinha,                                    "", "TB",  0, "R", 1);
    $oPdf->cell(25 ,  $iAlturalinha, db_formatar(abs($nSaldoInicial), 'f'), "TB",  1, "R", 1);
  }
  $oPdf->Ln();
}

$oPdf->Ln();
// RESUMO GERAL
$oPdf->setfont('arial','b',$iFonte);
$oPdf->cell(100,  $iAlturalinha, "",                          "",  0, "R", 0);
$oPdf->cell(40,  $iAlturalinha, "TOTAIS DAS MOVIMENTAÇÕES: ", "TB",  0, "R", 1);
$oPdf->setfont('arial','',6);
$oPdf->cell(25 ,  $iAlturalinha, db_formatar($nTotalGeralDebito , 'f'),  "TB",  0, "R", 1);
$oPdf->cell(25 ,  $iAlturalinha, db_formatar($nTotalGeralCredito, 'f'), "TB",  1, "R", 1);

$oPdf->setfont('arial','b',$iFonte);
$oPdf->cell(100,  $iAlturalinha, "",             "",  0, "R", 0);
$oPdf->cell(40,  $iAlturalinha, "SALDO FINAL DA CONTA: ", "TB",  0, "R", 1);
$oPdf->setfont('arial','',6);
if ($nSaldoFinalConta > 0){
  $oPdf->cell(25 ,  $iAlturalinha, db_formatar($nSaldoFinalConta, 'f'), "TB",  0, "R", 1);
  $oPdf->cell(25 ,  $iAlturalinha,                                  "", "TB",  1, "R", 1);
}else{
  $oPdf->cell(25 ,  $iAlturalinha,                                  "", "TB",  0, "R", 1);
  $oPdf->cell(25 ,  $iAlturalinha, db_formatar(abs($nSaldoFinalConta), 'f'), "TB",  1, "R", 1);
}

$oPdf->output();

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime, $nDebitoInicial = null, $nCreditoInicial = null) {

  if ( $oPdf->GetY() > $oPdf->h - 60 || $lImprime ) {

    $oPdf->SetFont('arial', 'b', 6);

    if ( !$lImprime ) {

      $oPdf->AddPage("P");
    }else{

    $oPdf->setfont('arial','b',6);
    $oPdf->cell(25,  $iAlturalinha, "DATA"             , "TB" , 0, "C", 1);
    $oPdf->cell(25,  $iAlturalinha, "CÓD. LANÇAMENTO"  , "TB" , 0, "C", 1);
    $oPdf->cell(90,  $iAlturalinha, "DOCUMENTO"        , "TB" , 0, "C", 1);
    $oPdf->cell(25,  $iAlturalinha, "DÉBITO"           , "TB" , 0, "C", 1);
    $oPdf->cell(25,  $iAlturalinha, "CRÉDITO"          , "TB" , 1, "C", 1);

    }
  }
}

?>