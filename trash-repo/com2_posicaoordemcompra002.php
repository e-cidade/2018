<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/JSON.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_db_depart_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once ("classes/db_empempenho_classe.php");
require_once ("classes/db_matordem_classe.php");

$oDaoInstit     = db_utils::getDao("db_config");//new cl_db_config;
$oDaoDepart     = new cl_db_depart; //db_utils::getDao("db_depart");
$oDaoCgm        = db_utils::getDao("cgm"); //new cl_cgm;
$oDaoEmpempenho = new cl_empempenho; 
$oDaoMatOrdem   = new cl_matordem;

$oGet         = db_utils::postMemory($_GET);

$dDataUsu     = date("d/m/Y", db_getsession("DB_datausu"));
$iInstituicao = db_getsession("DB_instit");

$iOrdem        = $oGet->iOrdem       ;
$iEmpenho      = $oGet->iEmpenho     ;
$sDataInicial  = $oGet->dDataInicial ;
$sDataFinal    = $oGet->dDataFinal   ;
$iFornecedor   = $oGet->iFornecedor  ;
$iOrigem       = $oGet->iOrigem      ;
$iDestino      = $oGet->iDestino     ;
$sDeptoOrigem  = "";
$sDeptoDestino = "";
$sForncedor    = "";
$sDataEmissao  = date("d/m/Y", db_getsession("DB_datausu"));
$aItens        = array();


$sWereEmpenho = "e60_instit = (select codigo from db_config where prefeitura is true limit 1) ";

$sCamposEmpenho  = "distinct m51_codordem as codigo_ordem, ";
$sCamposEmpenho .= "e60_numemp ||'/'|| e60_anousu as codigo_empenho ";

if ($sDataInicial != '') {
  $sWereEmpenho .= " and e60_emiss >= '{$sDataInicial}' ";
}

if ($sDataFinal != '') {
  $sWereEmpenho .= " and e60_emiss <= '{$sDataFinal}' ";
}

if ($iEmpenho != '') {
  $sWereEmpenho .= " and e60_numemp = {$iEmpenho} ";
}
if ($iFornecedor != '') {
  $sWereEmpenho .= " and m51_numcgm = {$iFornecedor} ";
}
if ($iOrigem != '') {
  $sWereEmpenho .= " and m51_deptoorigem = {$iOrigem} ";
}
if (isset($oGet->iDestino) && $iDestino != '') {
  $sWereEmpenho .= " and m51_depto = {$iDestino} ";
}

if ($iOrdem != '') {
  $sWereEmpenho .= " and m51_codordem = {$iOrdem} ";
}

$sSqlEmpempenho = $oDaoEmpempenho->sql_query_codord(null, $sCamposEmpenho, null, $sWereEmpenho);
$rsEmpempenho   = $oDaoEmpempenho->sql_record($sSqlEmpempenho);
$aEmpenhos      = db_utils::getCollectionByRecord($rsEmpempenho);


if ($oDaoEmpempenho->numrows > '0' && $iOrdem == '') {
  
  for ($iOrdens = 0; $iOrdens < $oDaoEmpempenho->numrows; $iOrdens++) {
    
    $oOrdem    = db_utils::fieldsMemory($rsEmpempenho, $iOrdens);
    
    //echo "<br>" . $oOrdem->codigo_ordem;
    
    $sSQlItens = sqlDadosOrdem($oOrdem->codigo_ordem); 
    $rsItens   = db_query($sSQlItens);
    
    if (pg_num_rows($rsItens) == 0) {
      
      continue;
    }
    
    $oDadosRelatorio    = db_utils::getCollectionByRecord($rsItens);
    
    $oDadosQuebra = new stdClass();
    $oDadosQuebra->deptoOridem  = $oDadosRelatorio[0]->deptoorigem;//"origem"; 
    $oDadosQuebra->deptoDestino = $oDadosRelatorio[0]->deptodestino; //"destino";
    $oDadosQuebra->iOrdem       = $oOrdem->codigo_ordem;
    $oDadosQuebra->iEmpenho     = $oOrdem->codigo_empenho;
    $oDadosQuebra->sDataEmissao = $oDadosRelatorio[0]->data;
    
    $oRelatorio = new stdClass();
    $oRelatorio->quebra = $oDadosQuebra;
    $oRelatorio->dados  = $oDadosRelatorio;
    
    $aItens[] = $oRelatorio;
  }
}

if ($iOrdem != '' && $oDaoEmpempenho->numrows > '0') {
  
  $sSQlItens = sqlDadosOrdem($iOrdem);
  $rsItens   = db_query($sSQlItens);
  
  $oItens    = db_utils::getCollectionByRecord($rsItens);
  
  $oDadosQuebra = new stdClass();
  $oDadosQuebra->deptoOridem  =  $oItens[0]->deptoorigem ;//'depto origem'   ;//$oDadosRelatorio[0]->deptoorigem;//"origem";
  $oDadosQuebra->deptoDestino =  $oItens[0]->deptodestino;  //'depto destino'   ;//$oDadosRelatorio[0]->deptodestino; //"destino";
  $oDadosQuebra->iOrdem       =  $iOrdem   ;//$oOrdem->codigo_ordem;
  $oDadosQuebra->iEmpenho     =  $aEmpenhos[0]->codigo_empenho   ;//$oOrdem->codigo_empenho;
  $oDadosQuebra->sDataEmissao =  $oItens[0]->data   ;//$oDadosRelatorio[0]->data;
  
  $oRelatorio = new stdClass();
  $oRelatorio->quebra = $oDadosQuebra;
  $oRelatorio->dados  = $oItens;
  
  $aItens[] = $oRelatorio;
  
}

  if (count($aItens) == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! Verifique os filtros.");
  }

$iAlturalinha = 4;
$iFonte       = 6;
$oPdf         = new PDF("L");

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);

$sDataInicial = implode("/", array_reverse(explode("-",$sDataInicial)));
$sDataFinal   = implode("/", array_reverse(explode("-",$sDataFinal)));


//================================ HEADER DO RELATORIO ======================================
 
$head1   = "RELATÓRIO POSIÇÃO ORDEM COMPRA \n";
$head2   = "\n";

if ($oGet->iOrdem != '') {
  $head2  .= "ORDEM DE COMPRA:    {$iOrdem} \n";
}

$head2  .= "DATA DE EMISSÃO:      {$sDataEmissao} \n";

if ($iEmpenho != '') {
  $head2  .= "EMPENHO:                        {$iEmpenho}  \n";
}

if ($oGet->dDataInicial != '' && $oGet->dDataFinal != '' ) {
  $head3   = "PERÍODO EMISSÃO:      {$sDataInicial}  à  {$sDataFinal} \n";
}

//==========================================================================================

$oPdf->AddPage("L");
$oPdf->setfont('arial', 'b', $iFonte);



//==============================  CABEÇALHO ==============================================
//imprimirCabecalho($oPdf, $iAlturalinha, true);
//========================================================================================


foreach ($aItens as $iItens => $oValores ) {
  
  $sDataEmissao = implode("/", array_reverse(explode("-", $oValores->quebra->sDataEmissao)));
  $oPdf->cell(30 ,  $iAlturalinha, "ORDEM DE COMPRA Nº:"           , "" ,  0, "L", 1);
  $oPdf->cell(20 ,  $iAlturalinha, $oValores->quebra->iOrdem       , "" ,  0, "L", 1);
  $oPdf->cell(25 ,  $iAlturalinha, "DATA DE EMISSÃO:"              , "" ,  0, "L", 1);
  $oPdf->cell(45 ,  $iAlturalinha, $sDataEmissao                   , "" ,  1, "L", 1);
  
  $oPdf->cell(30 ,  $iAlturalinha, "EMPENHO:"                      , "" ,  0, "L", 1);
  $oPdf->cell(90 ,  $iAlturalinha, $oValores->quebra->iEmpenho     , "" ,  1, "L", 1);
  
  $oPdf->cell(30 ,  $iAlturalinha, "DEPTO. ORIGEM:"                , "" ,  0, "L", 1);
  $oPdf->cell(90 ,  $iAlturalinha, $oValores->quebra->deptoOridem  , "" ,  1, "L", 1);
  
  $oPdf->cell(30 ,  $iAlturalinha, "DEPTO. DESTINO:"               , "" ,  0, "L", 1);
  $oPdf->cell(90 ,  $iAlturalinha, $oValores->quebra->deptoDestino , "" ,  1, "L", 1);  
  
  
  //==================================================  CABECALHO ====================================
  
  $oPdf->cell(120,  $iAlturalinha, ""                        , "LTRB" ,  0, "C", 1);
  $oPdf->cell(60 ,  $iAlturalinha, "Quantidade Ordem Compra" , "LTRB" ,  0, "C", 1);
  $oPdf->cell(45 ,  $iAlturalinha, "Quantidade em Estoque"   , "LTRB" ,  0, "C", 1);
  $oPdf->cell(20 ,  $iAlturalinha, ""                        , "LTRB" ,  0, "C", 1);
  $oPdf->cell(35 ,  $iAlturalinha, "Saldo"                   , "LTRB" ,  1, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Item"                    , "LTRB" ,  0, "C", 1);
  $oPdf->cell(45 ,  $iAlturalinha, "Descr. "                 , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Unid."                   , "LTRB" ,  0, "C", 1);
  $oPdf->cell(45 ,  $iAlturalinha, "Fornecedor "             , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Quant."                  , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Quant. Anul."            , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Valor Uni."              , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Valor Total"             , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Quant."                  , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Valor Uni."              , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Valor Total"             , "LTRB" ,  0, "C", 1);
  $oPdf->cell(20 ,  $iAlturalinha, "Prazo"                   , "LTRB" ,  0, "C", 1);
  $oPdf->cell(15 ,  $iAlturalinha, "Quant."                  , "LTRB" ,  0, "C", 1);
  $oPdf->cell(20 ,  $iAlturalinha, "Dias em Atraso"          , "TLRB" ,  1, "C", 1);
  
  
  //==================================================================================================
  
  foreach ($oValores->dados as $iDados => $oDados) {
    
    
    
   /*  
    $fValorUnitarioOrdem  = db_formatar($oDados->valor_unitario_ordem ,'f');
    $fValorTotalOrdem     = db_formatar($oDados->valor_total_ordem    ,'f');
    $fValorUnidadeEstoque = db_formatar(($oDados->quantidade_estoque / $oDados->valor_unitario_ordem),'f'); 
    $fValorTotalEstoque   = db_formatar($oDados->valor_total_estoque  ,'f');
     */

    
    $nValorUnitarioEstoque  = "0";
    $iQuantItemOrdem        = db_formatar($oDados->quantidade_item_ordem ,'f')       ;
    $iQuantItemOrdemAnulado = db_formatar($oDados->quantidade_anulada_ordem ,'f')    ;
    $nValorUnitario         = db_formatar($oDados->valor_unitario_ordem  ,'f')       ;
    $nValorTotalOrdem       = db_formatar((($oDados->quantidade_item_ordem - $oDados->quantidade_anulada_ordem) * $oDados->valor_unitario_ordem),'f');
    
    
    $iQuantEstoque         = $oDados->quantidade_estoque;
    $fValorTotalEstoque    = $oDados->valor_total_estoque;
    if ($iQuantEstoque > 0) {
      
      $nValorUnitarioEstoque = ($fValorTotalEstoque / $iQuantEstoque);
    }
    $fValorTotalEstoque = db_formatar($fValorTotalEstoque, "f");
    
    $sDataDia             = date("d/m/Y", db_getsession('DB_datausu'));
    
    
    $iDiasAtraso          = diasAtraso ($sDataEmissao , $sDataDia, $oDados->prazoentrega);
    $iQuantidadeSaldo     = ($oDados->quantidade_item_ordem - $oDados->quantidade_anulada_ordem - $oDados->quantidade_estoque);
    if ($iQuantidadeSaldo == "") {
      $iQuantidadeSaldo = "0";
    }
    
    $oPdf->cell(15 ,  $iAlturalinha, $oDados->codigo_item              , "TRB"  ,  0, "C", 0);                  
    $oPdf->cell(45 ,  $iAlturalinha, substr($oDados->descricao_item, 0, 30)           , "LTRB" ,  0, "L", 0);               
    $oPdf->cell(15 ,  $iAlturalinha, $oDados->unidade                  , "LTRB" ,  0, "L", 0);                                    
    $oPdf->cell(45 ,  $iAlturalinha, substr($oDados->fornecedor, 0, 30), "LTRB" ,  0, "L", 0);
                       
    $oPdf->cell(15 ,  $iAlturalinha, $iQuantItemOrdem        , "LTRB" ,  0, "C", 0);  //OC. quantidade      
    $oPdf->cell(15 ,  $iAlturalinha, $iQuantItemOrdemAnulado , "LTRB" ,  0, "C", 0);  //OC. qtd Anulada  
    $oPdf->cell(15 ,  $iAlturalinha, $nValorUnitario              , "LTRB" ,  0, "C", 0);  //OC. Vlr Unitario      
    $oPdf->cell(15 ,  $iAlturalinha, $nValorTotalOrdem                 , "LTRB" ,  0, "C", 0);  //OC. Vlr Total  
            
    $oPdf->cell(15 ,  $iAlturalinha, $iQuantEstoque      , "LTRB" ,  0, "C", 0);  //ES. quantidade                            
    $oPdf->cell(15 ,  $iAlturalinha, $nValorUnitarioEstoque             , "LTRB" ,  0, "C", 0);  //ES. vlr Unitario          
    $oPdf->cell(15 ,  $iAlturalinha, $fValorTotalEstoque               , "LTRB" ,  0, "C", 0);  //ES. vlr Total 
         
    $oPdf->cell(20 ,  $iAlturalinha, $oDados->prazoentrega             , "LTRB" ,  0, "C", 0);         
    $oPdf->cell(15 ,  $iAlturalinha, $iQuantidadeSaldo                 , "LTRB" ,  0, "C", 0);                        
    $oPdf->cell(20 ,  $iAlturalinha, $iDiasAtraso                      , "TLB"  ,  1, "C", 0); 
    imprimirSubCabecalho($oPdf, $iAlturalinha, false);    
  }
  $oPdf->Ln($iAlturalinha);
  imprimirCabecalho($oPdf, $iAlturalinha, false);    
}

$oPdf->output();


function imprimirSubCabecalho($oPdf, $iAlturalinha, $lImprime) {

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', 'b', 6);

    if ( !$lImprime ) {
      $oPdf->AddPage("L");
    }
    
    
    $oPdf->cell(120,  $iAlturalinha, ""                        , "LTRB" ,  0, "C", 1);
    $oPdf->cell(60 ,  $iAlturalinha, "Quantidade Ordem Compra" , "LTRB" ,  0, "C", 1);
    $oPdf->cell(45 ,  $iAlturalinha, "Quantidade em Estoque"   , "LTRB" ,  0, "C", 1);
    $oPdf->cell(20 ,  $iAlturalinha, ""                        , "LTRB" ,  0, "C", 1);
    $oPdf->cell(35 ,  $iAlturalinha, "Saldo"                   , "LTRB" ,  1, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Item"                    , "LTRB" ,  0, "C", 1);
    $oPdf->cell(45 ,  $iAlturalinha, "Descr. "                 , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Unid."                   , "LTRB" ,  0, "C", 1);
    $oPdf->cell(45 ,  $iAlturalinha, "Fornecedor "             , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Quant."                  , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Quant. Anul."            , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Valor Uni."              , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Valor Total"             , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Quant."                  , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Valor Uni."              , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Valor Total"             , "LTRB" ,  0, "C", 1);
    $oPdf->cell(20 ,  $iAlturalinha, "Prazo"                   , "LTRB" ,  0, "C", 1);
    $oPdf->cell(15 ,  $iAlturalinha, "Quant."                  , "LTRB" ,  0, "C", 1);
    $oPdf->cell(20 ,  $iAlturalinha, "Dias em Atraso"          , "TLRB" ,  1, "C", 1);    

    $oPdf->setfont('arial','b',6);
  }
}


function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {

	if ( $oPdf->GetY() > $oPdf->h - 40 || $lImprime ) {

		$oPdf->SetFont('arial', 'b', 6);

		if ( !$lImprime ) {
			$oPdf->AddPage("L");
		}

		$oPdf->setfont('arial','b',6);
	}
}


/**
 * funcao que gera o sql para buscar os itens das ordens de compra
 *
 */

function sqlDadosOrdem ($iCodigoOrdem) {

  $sCamposItens  = "ricodmater                        as codigo_item,              ";
  $sCamposItens .= "ricodordem                        as codigo_ordem,             ";
  $sCamposItens .= "rsdescr                           as descricao_item,           ";
  $sCamposItens .= "m61_descr                         as unidade,                  ";
  $sCamposItens .= "z01_nome                          as fornecedor,               ";
  $sCamposItens .= "deptoorigem.descrdepto            as deptoorigem,              ";
  $sCamposItens .= "deptodestino.descrdepto           as deptodestino,             ";
  $sCamposItens .= "m51_prazoent                      as prazoentrega,             ";             

  $sCamposItens .= "rnquantini                        as quantidade_item_ordem,    ";
  $sCamposItens .= "rnvaloranul                       as quantidade_anulada_ordem, ";
  $sCamposItens .= "rnvaloruni                        as valor_unitario_ordem,     ";
  //$sCamposItens .= "rnsaldovalor                      as valor_total_ordem,        ";

  
  $sCamposItens .= "rnsaldoestoque                    as quantidade_estoque,       ";
  $sCamposItens .= "rnvalorestoque                    as valor_total_estoque,    ";
 // $sCamposItens .= "(rnvalorestoque * rnsaldoestoque) as valor_total_estoque,      ";
  
  
  
  $sCamposItens .= "m51_data                          as data,                     ";
  $sCamposItens .= "'quant_saldo'                     as quant_saldo,              ";
  $sCamposItens .= "'dias_atraso'                     as dias_atraso               ";

  $sSQlItens  = "select *  from ( select {$sCamposItens}                                                                ";
  $sSQlItens .= "                   from fc_saldoitensordem({$iCodigoOrdem})                                            ";
  $sSQlItens .= "                  left join matmater               on matmater.m60_codmater   = ricodmater            ";
  //$sSQlItens .= "                  left join pcmater               on pcmater.pc01_codmater   = ricodmater            ";
  $sSQlItens .= "                  left join matunid                on matmater.m60_codmatunid = m61_codmatunid        ";
  $sSQlItens .= "                  left join matordem               on matordem.m51_codordem   = ricodordem            ";
  $sSQlItens .= "                   left join db_depart deptodestino on matordem.m51_depto     = deptodestino.coddepto  ";
  $sSQlItens .= "                   left join db_depart deptoorigem  on matordem.m51_deptoorigem = deptoorigem.coddepto ";
  $sSQlItens .= "                  left join cgm                    on matordem.m51_numcgm     = cgm.z01_numcgm        ";
  $sSQlItens .= "               ) as x                                                                                  ";
  
 // echo $sSQlItens;
  // die();

  return $sSQlItens;

}

function diasAtraso ($sDataInicial , $sDataFinal, $iPrazo) {

  $aDataInicial = explode('/', $sDataInicial);
  $aDataFinal   = explode('/', $sDataFinal);

  $iDiaInicial = $aDataInicial[0];
  $iMesInicial = $aDataInicial[1];
  $iAnoInicial = $aDataInicial[2];

  $iDiaFinal = $aDataFinal[0];
  $iMesFinal = $aDataFinal[1];
  $iAnoFinal = $aDataFinal[2];

  //defino data 1
  $ano1 = $iAnoInicial;
  $mes1 = $iMesInicial;
  $dia1 = $iDiaInicial;

  //defino data 2
  $ano2 = $iAnoFinal;
  $mes2 = $iMesFinal;
  $dia2 = $iDiaFinal;

  //calculo timestam das duas datas
  $timestamp1 = mktime(0 ,  0, 0, $mes1, $dia1, $ano1);
  $timestamp2 = mktime(4 , 12, 0, $mes2, $dia2, $ano2);

  //diminuo a uma data a outra
  $segundos_diferenca = $timestamp1 - $timestamp2;
  //echo $segundos_diferenca;

  //converto segundos em dias
  $dias_diferenca = $segundos_diferenca / (60 * 60 * 24);

  //obtenho o valor absoluto dos dias (tiro o possível sinal negativo)
  $dias_diferenca = abs($dias_diferenca);

  //tiro os decimais aos dias de diferenca
  $dias_diferenca = floor($dias_diferenca);
  $iDiasAtrazo = $iPrazo - $dias_diferenca;

  if ($iDiasAtrazo < 0) {
    $iDiasAtrazo = $iDiasAtrazo * -1;
  } else {
    $iDiasAtrazo = 0;
  }

  return $iDiasAtrazo;
}


?>