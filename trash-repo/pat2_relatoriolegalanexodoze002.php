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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");

$oGet          = db_utils::postMemory($_GET);
$iInstituicao  = db_getsession("DB_instit");
$dtDataInicial = date("d-m-Y",strtotime($oGet->dtDataInicial));
$dtDataFinal   = date("d-m-Y",strtotime($oGet->dtDataFinal));

list($iAnoInicial, $iMesInicial, $iDiaInicial) = explode('-', $oGet->dtDataInicial);

/**
 * Realiza consulta das contas e seus respectivos itens 
 * Filtro com uma data m�nima  e m�xima, o per�odo de aquisi��o do Bem, e pelo anousu a Conta
 */
$oDaoBens = db_utils::getDao('bens');
$sCampos  = " c60_codcon, c60_estrut, c60_descr";
$sCampos .= ", sum(case when t52_dtaqu between '{$oGet->dtDataInicial}' and '{$oGet->dtDataFinal}'";
$sCampos .= " then t52_valaqu else 0 end) as total_aquisicao, c60_anousu";

$sWhere   = "     t52_dtaqu  <= '{$oGet->dtDataFinal}' ";
$sWhere  .= " and c60_anousu = {$iAnoInicial}";
$sWhere  .= " and t52_instit = {$iInstituicao}";
$sOrder   = " c60_estrut ";
$sGroup   = " group by c60_codcon,c60_estrut,c60_descr,c60_anousu";

$sSqlBuscaBens = $oDaoBens->sql_query_bensContasAnexo(null, $sCampos, $sOrder,$sWhere.$sGroup);
$rsBuscaBens   = $oDaoBens->sql_record($sSqlBuscaBens);
$iTotalBens    = $oDaoBens->numrows;
unset($oDaoBens);


if ($iTotalBens == 0) {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_relatoriolegalanexodoze002.nenhum_bem_cadastrado');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}
//Array das contas
$aContas = array();

/**
 * Para cada Acumulado de entrada, agrupado por conta, verificar o saldo anterior e gravar em um array
 */
for ($iRowBens = 0; $iRowBens < $iTotalBens; $iRowBens++) {
  
  $oDadoBem = db_utils::fieldsMemory($rsBuscaBens, $iRowBens);
  
  $oStdBem                 = new stdClass();
  $oStdBem->sEstrutural    = $oDadoBem->c60_estrut;
  $oStdBem->sDescricao     = $oDadoBem->c60_descr;
  $oStdBem->iCodigoConta   = $oDadoBem->c60_codcon;
  $oStdBem->nTotalEntrada  = $oDadoBem->total_aquisicao;
  $oStdBem->nSaldoAnterior = 0;
  $oStdBem->nTotalSaidas   = 0;
  $oStdBem->nSaldoGeral    = $oDadoBem->total_aquisicao;

  /**
   * Verifica Saldo acumulado anterior da conta 
   */
  $iCodigoConta         = $oDadoBem->c60_codcon;
  $oDaoBens             = db_utils::getDao('bens');
  $sWhere               = " t52_dtaqu < '{$oGet->dtDataInicial}' and c60_codcon = {$iCodigoConta}";
  $sWhere              .= "  and c60_anousu = {$iAnoInicial}";
  $sWhere              .= "  and (t55_baixa is null or t55_baixa > '{$oGet->dtDataInicial}') ";
  $sWhere               .= " and t52_instit = {$iInstituicao}";
  $sCamposSaldoInicial  = "sum(t52_valaqu) as total_aquisicao";
  $sSqlBuscaBens        = $oDaoBens->sql_query_bensContasAnexo(null, $sCamposSaldoInicial, $sOrder,$sWhere.$sGroup);
  $rsBuscaBensSaldo     = $oDaoBens->sql_record($sSqlBuscaBens);

  
  
  $oStdBem->nSaldoAnterior = 0;
  if ($oDaoBens->numrows == 1) {
    
    $oContaSaldoAnterior     = db_utils::fieldsMemory($rsBuscaBensSaldo, 0);
    $oStdBem->nSaldoAnterior = $oContaSaldoAnterior->total_aquisicao;
  }
  
  /**
   * Verifica Sa�das acumuladas dos bens, no periodo selecionado
   */
  $sWhere                     = "     t55_baixa between '{$oGet->dtDataInicial}' and '{$oGet->dtDataFinal}' ";
  $sWhere                    .= " and c60_anousu = {$iAnoInicial} and c60_codcon = {$iCodigoConta} ";
  $sWhere                    .= " and t55_codbem is not null";
  $sWhere                    .= " and t52_instit = {$iInstituicao}";
  $sCamposSaldoSaida          = "sum(t52_valaqu) as total_aquisicao";
  $sSqlBuscaBensSaidaPeriodo  = $oDaoBens->sql_query_bensContasAnexo(null, $sCamposSaldoSaida, $sOrder,$sWhere.$sGroup);
  $rsBuscaBensSaidaPeriodo    = $oDaoBens->sql_record($sSqlBuscaBensSaidaPeriodo);
  
  $oStdBem->nTotalSaidas = 0;
  if ($oDaoBens->numrows == 1) {
  
    $oContaBensSaidaPeriodo = db_utils::fieldsMemory($rsBuscaBensSaidaPeriodo, 0);
    $oStdBem->nTotalSaidas  = $oContaBensSaidaPeriodo->total_aquisicao;
  }
  
  $oStdBem->nSaldoGeral = ($oStdBem->nSaldoAnterior + $oStdBem->nTotalEntrada) - $oStdBem->nTotalSaidas;
  $aContas[] = $oStdBem;
}


/** Dados da institui��o para preenchimento do pdf */

$oDaoInstit      = db_utils::getDao("db_config");//new cl_db_config;
$oDaoDepart      = db_utils::getDao("db_depart");//new cl_db_depart;

$sCamposInstit   = "nomeinst, ";
$sCamposInstit  .= "munic||' - '||uf as municipio ";
$sSqlInstituicao = $oDaoInstit->sql_query(null, $sCamposInstit, null, "codigo = {$iInstituicao}");
$rsInstit        = $oDaoInstit->sql_record($sSqlInstituicao);
$sInstituicao    = db_utils::fieldsMemory($rsInstit, 0)->nomeinst;
$sMunicipio      = db_utils::fieldsMemory($rsInstit, 0)->municipio;

$sSqlDepart      = $oDaoDepart->sql_query_file(db_getsession("DB_coddepto"));
$rsDepart        = $oDaoDepart->sql_record($sSqlDepart);
$sDepartamento   = db_utils::fieldsMemory($rsDepart, 0)->descrdepto;
$iDepartamento   = db_getsession("DB_coddepto");

/* Modifica data para imprimir no padr�o do relat�rio*/
$dtDataInicial = str_replace("-","/",$dtDataInicial);
$dtDataFinal   = str_replace("-","/",$dtDataFinal);

//Seta propriedades iniciais do PDF
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);

$head1 = "               RELAT�RIO LEGAL MODELO 12";
$head2 = "BENS PATRIMONIAIS - DEMONSTRATIVO DA MOVIMENTA��O";
$head3 = "PER�ODO: {$dtDataInicial} at� {$dtDataFinal}";
$head4 = "\nOrg�o / Entidade: {$sInstituicao}";
$head5 = "Munic�pio: {$sMunicipio}";
$head6 = "Unidade de Controle: {$sDepartamento}";

$iRodape      = 0;
$iAlturalinha = 4;
$iFonte       = 6;
$lImprime     = true;


$oStdTotal = new stdClass();
$oStdTotal->nSaldoAnterior = 0;
$oStdTotal->nTotalEntrada  = 0;
$oStdTotal->nTotalSaidas   = 0;
$oStdTotal->nSaldoGeral    = 0;

foreach ($aContas as $iIndiceContas => $oConta) {

  if ( $oPdf->GetY() > $oPdf->h - 32 || $lImprime ) {
    
    if (!$lImprime) {
      
      imprimirRodape($oPdf, $iAlturalinha, $oStdTotal);
      $oStdTotal->nSaldoAnterior = 0;
      $oStdTotal->nTotalEntrada  = 0;
      $oStdTotal->nTotalSaidas   = 0;
      $oStdTotal->nSaldoGeral    = 0;
      
    }
    imprimirCabecalho($oPdf, $iAlturalinha, $dtDataInicial, $dtDataFinal);
    $lImprime = false;
  }
  
  $oPdf->setfont('arial','',6);
  $oPdf->cell(30 ,  $iAlturalinha, $oConta->sEstrutural                     , 1, 0, "C", 0); // classificacao        || codigo plano contas
  $oPdf->cell(60 ,  $iAlturalinha, substr($oConta->sDescricao ,0 , 40)      , 1, 0, "L", 0); // classificacao        || interpreta��o
  $oPdf->cell(50 ,  $iAlturalinha, db_formatar($oConta->nSaldoAnterior,"f") , 1, 0, "R", 0); // saldo anterior       || __/__/__ (R$)
  $oPdf->cell(45 ,  $iAlturalinha, db_formatar($oConta->nTotalEntrada,"f")  , 1, 0, "R", 0); // movimentacao periodo || Entradas
  $oPdf->cell(45 ,  $iAlturalinha, db_formatar($oConta->nTotalSaidas,"f")   , 1, 0, "R", 0); // movimentacao periodo || Saidas
  $oPdf->cell(50 ,  $iAlturalinha, db_formatar($oConta->nSaldoGeral,"f")    , 1, 1, "R", 0); // saldo em             || __/__/ (R$)
  
  $oStdTotal->nSaldoAnterior += $oConta->nSaldoAnterior;
  $oStdTotal->nTotalEntrada  += $oConta->nTotalEntrada;
  $oStdTotal->nTotalSaidas   += $oConta->nTotalSaidas;
  $oStdTotal->nSaldoGeral    += $oConta->nSaldoGeral;
  
}

  imprimirRodape($oPdf, $iAlturalinha, $oStdTotal);

  //========  RODAPE FINAL COM ASSINATURAS  FIXO:
  $oPdf->setfont('arial','b', 6);
  $oPdf->Ln();
  $oPdf->cell(90,  $iAlturalinha, "Elaborado por" , "LBTR",  0, "C", 0);
  $oPdf->cell(90,  $iAlturalinha, "Conferido por" , "LBTR",  0, "C", 0);
  $oPdf->cell(70,  $iAlturalinha, "Visto"         , "LBTR",  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, "Data"          , "LBTR",  1, "C", 0);
  
  $oPdf->cell(90,  $iAlturalinha, "Nome", "LR",  0, "L", 0);
  $oPdf->cell(90,  $iAlturalinha, ""    , "R" ,  0, "C", 0);
  $oPdf->cell(70,  $iAlturalinha, ""    , "R" ,  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, ""    , "R" ,  1, "C", 0);
  
  $oPdf->cell(90,  $iAlturalinha, "Matr�cula", "LR",  0, "L", 0);
  $oPdf->cell(90,  $iAlturalinha, ""         , "R" ,  0, "C", 0);
  $oPdf->cell(70,  $iAlturalinha, ""         , "R" ,  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, ""         , "R" ,  1, "C", 0);
  
  $oPdf->cell(90,  $iAlturalinha, "Assinatura", "LRB",  0, "L", 0);
  $oPdf->cell(90,  $iAlturalinha, ""          , "RB" ,  0, "C", 0);
  $oPdf->cell(70,  $iAlturalinha, ""          , "RB" ,  0, "C", 0);
  $oPdf->cell(30,  $iAlturalinha, ""          , "RB" ,  1, "C", 0);
  
  $oPdf->cell(280,  $iAlturalinha, "Correspondente ao modelo IGF/65" , "",  0, "R", 0);  

function imprimirCabecalho ($oPdf, $iAlturalinha,$dtDataInicial, $dtDataFinal ) {
  
    $oPdf->AddPage("L");
    $oPdf->SetFont('arial', 'b', 6);
    //Primeira linha cabe�alho
    $oPdf->setfont('arial','b',8);
    $oPdf->cell(90 ,  $iAlturalinha, "Classifica��o"           , "LTR" ,  0, "C", 1); // classificacao
    $oPdf->cell(50 ,  $iAlturalinha, "Saldo Anterior em"       , "LTR" ,  0, "C", 1); // saldo anterior
    $oPdf->cell(90 ,  $iAlturalinha, "Movimenta��o Per�odo"    , "LTR" ,  0, "C", 1); // movimentacao periodo
    $oPdf->cell(50 ,  $iAlturalinha, "Saldo em "               , "LTR" ,  1, "C", 1); // saldo em
    
    //Segunda Linha cabe�alho

    $oPdf->setfont('arial','b',8);
    $oPdf->cell(30 ,  $iAlturalinha, "C�digo Plano Contas"  , "LBTR" ,  0, "C", 1); // classificacao        || codigo plano contas
    $oPdf->cell(60 ,  $iAlturalinha, "Interpreta��o"        , "LBTR" ,  0, "C", 1); // classificacao        || interpreta��o
    $oPdf->cell(50 ,  $iAlturalinha, "{$dtDataInicial}(R$)" , "LBR"  ,  0, "C", 1); // saldo anterior       || __/__/__ (R$)
    $oPdf->cell(45 ,  $iAlturalinha, "Entradas"             , "LBTR" ,  0, "C", 1); // movimentacao periodo || Entradas
    $oPdf->cell(45 ,  $iAlturalinha, "Sa�das"               , "LBTR" ,  0, "C", 1); // movimentacao periodo || Saidas
    $oPdf->cell(50 ,  $iAlturalinha, "{$dtDataFinal}(R$)"   , "LBR"  ,  1, "C", 1); // saldo em             || __/__/ (R$)
    
}

//=========  RODAP� COM TOTAL POR PAGINA
function imprimirRodape($oPdf,$iAlturalinha, $oStdTotal) {

  $oPdf->setfont('arial','b', 6);
  $oPdf->cell(90 , $iAlturalinha, "TOTAL"                            , "LTBR" , 0, "R", 1);
  $oPdf->cell(50 , $iAlturalinha, db_formatar($oStdTotal->nSaldoAnterior, "f") , "LBT"  , 0, "R", 0);
  $oPdf->cell(45 , $iAlturalinha, db_formatar($oStdTotal->nTotalEntrada , "f") , "LBT"  , 0, "R", 0);
  $oPdf->cell(45 , $iAlturalinha, db_formatar($oStdTotal->nTotalSaidas  , "f") , "LBRT" , 0, "R", 0);
  $oPdf->cell(50 , $iAlturalinha, db_formatar($oStdTotal->nSaldoGeral   , "f") , "TBR"  , 1, "R", 0);
}  
$oPdf->Output();
?>