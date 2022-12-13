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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("classes/materialestoque.model.php");


$oGet = db_utils::postMemory($_GET);

$dtInicial          = $oGet->dtInicial    ;
$dtFinal            = $oGet->dtFinal      ;
$iMaterial          = $oGet->iMaterial    ;
$aAlmoxarifado      = explode(",", $oGet->aAlmoxarifado);
$aListaAlmoxarifado = array();

if ( isset($dtInicial) && empty($dtInicial) ) {
  $dtInicial = "1950-01-01";
}
if ( isset($dtFinal) && empty($dtFinal) ) {
  $dtFinal = "2050-01-01";
}

//monta lista de objetos almoxarifado
if ( isset($oGet->aAlmoxarifado) && !empty($oGet->aAlmoxarifado)) {
  
  foreach ($aAlmoxarifado as $iIndAlmoxarifado => $iAlmoxarifado) {
    $aListaAlmoxarifado[] = new Almoxarifado($iAlmoxarifado);
  }
} else {
  $aListaAlmoxarifado = Almoxarifado::getListaAlmoxarifados();
}



try {
  
  $aDados           = array();
  $oItem            = new Item($iMaterial);
  $oDataInicial     = new DBDate($dtInicial);
  $oDataFinal       = new DBDate($dtFinal);
  $oControleEstoque = new ControleEstoque();
  
  $oControleEstoque->adicionarItem($oItem);
  $oControleEstoque->setPeriodo($oDataInicial, $oDataFinal);
  
  foreach ($aListaAlmoxarifado as $oDadosAlmoxarifados) { // adicionamos os almoxarifados
    
    $iCodigoAlmoxarifado = $oDadosAlmoxarifados->getCodigoAlmoxarifado();  
    $oMovimentacaoItem   = new MovimentacaoItem($oItem);
    $oMaterialEstoque    = new materialEstoque($oItem->getCodigo());
    $oControleEstoque->adicionarAlmoxarifado($oDadosAlmoxarifados);
    $oMaterialEstoque->setCodDepto($oDadosAlmoxarifados->getCodigo());
    $oSaldoAnteriorItem = $oControleEstoque->getSaldoAnteriorDoItem($oMovimentacaoItem, $oDadosAlmoxarifados);
    
    $sAlmoxarifado  = $oDadosAlmoxarifados->getCodigo() . " - " . $oDadosAlmoxarifados->getNomeDepartamento();
    $sMaterial      = $oItem->getCodigo() . " - " . $oItem->getNome();
    $sGrupo         = $oItem->getGrupo()->getCodigo() . " - " . $oItem->getGrupo()->getDescricao();
    $iEstoqueMinimo = $oMaterialEstoque->getEstoqueMinimo();
    $iEstoqueMaximo = $oMaterialEstoque->getEstoqueMaximo();
    $iPontoPedido   = $oMaterialEstoque->getPontoPedido();
    $sUnidade       = $oItem->getUnidade()->getDescricao();
    $nSaldoAnterior = $oSaldoAnteriorItem->getValorAnterior();
    $iQuantidade    = $oSaldoAnteriorItem->getQuantidadeAnterior();
    $nPrecoMedio    = $oMaterialEstoque->getPrecoMedio();
    $nValorTotal    = ($iQuantidade * $nPrecoMedio);
    
    $oDadosPrincipais = new stdClass();  
    $oDadosPrincipais->sAlmoxarifado   = $sAlmoxarifado;
    $oDadosPrincipais->sMaterial       = $sMaterial;
    $oDadosPrincipais->sSubGrupo       = $sGrupo;
    $oDadosPrincipais->iEstoqueMinimo  = $iEstoqueMinimo;
    $oDadosPrincipais->iEstoqueMaximo  = $iEstoqueMaximo;
    $oDadosPrincipais->sUnidade        = $sUnidade;
    $oDadosPrincipais->iSaldoAnterior  = db_formatar($nSaldoAnterior, 'f');
    $oDadosPrincipais->iQuantidade     = $iQuantidade;
    $oDadosPrincipais->nPrecoMedio     = db_formatar($nPrecoMedio, 'f');
    $oDadosPrincipais->nValorTotal     = db_formatar($nValorTotal, 'f');
    $oDadosPrincipais->iPontoPedido    = $iPontoPedido;
    $oMovimentos = getMovimentosItem( $oItem->getCodigo(), $oDadosAlmoxarifados->getCodigo(), $dtInicial, $dtFinal, $iQuantidade);
    
    $oDadosPrincipais->aDadosMovimento = $oMovimentos->aDadosMovimentacao;
    $oDadosPrincipais->oTotalPeriodo   = $oMovimentos->oTotalPeriodo;
    
    $oDadosPrincipais->oTotalGeral     = getTotalGeral( $oItem->getCodigo(), $oDadosAlmoxarifados->getCodigo(), $oDataInicial, $nPrecoMedio);
    
    if ( !empty($oDadosPrincipais->aDadosMovimento )) {
      
      $aDados[] = $oDadosPrincipais;
    }
    
  }

} catch (Exception $eException){


  $sErroMsg  = $eException->getMessage();
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}


$oPdf = new PDF("L");

$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);

$iAlturalinha = 4;
$iFonte       = 6;
$sHead3       = "";


if ( isset($oGet->dtInicial) && !empty($oGet->dtInicial) && isset($oGet->dtFinal) && !empty($oGet->dtFinal) ) {
  $sHead3 = db_formatar($oGet->dtInicial , "d") . " até " . db_formatar($oGet->dtFinal, "d");
}

$head2  = "FICHA FINANCEIRA DE ESTOQUE ";
$head4  = $sHead3;

$oPdf->AddPage("L");


if ( empty($aDados) ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado");
}

foreach ($aDados as $iDados => $oDadosPrincipal) {

  /*
   * percorre o array com os dados da movimentacao
   */
  if ( count($oDadosPrincipal->aDadosMovimento) > 0 ) {

    imprimirAlmoxarifado($oPdf, $iAlturalinha, true, $oDadosPrincipal);
    imprimirCabecalho($oPdf, $iAlturalinha, true);
    $oPdf->SetFont('arial', '', 6);
    
    $nTotalSaida    = 0;
    $nTtotalEntrada = 0;
    
    foreach ($oDadosPrincipal->aDadosMovimento as $iIndiceMovimento => $oDadosMovimento) {
        
      $iCorLinha = 0;
      if ( $oDadosMovimento->iTipoMovimento == 1 ) {
        $iCorLinha = 1;
      } 
      
      $oPdf->cell(80,  $iAlturalinha, "{$oDadosMovimento->sDestino   }"  , "TBR" , 0, "L", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->sData      }"  , "LTBR", 0, "C", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->sEmpenho   }"  , "TBL" , 0, "L", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->iOc        }"  , "TBL" , 0, "R", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->sMovimento }"  , "TBLR", 0, "L", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->sProcPag   }"  , "TBL" , 0, "L", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->iQuantidade}"  , "TBL" , 0, "R", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->nValorUni  }"  , "TBL" , 0, "R", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->nValorTotal}"  , "TBL" , 0, "R", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->nSaldoQuant}"  , "TBL" , 0, "R", $iCorLinha);
      $oPdf->cell(20,  $iAlturalinha, "{$oDadosMovimento->nSaldoValor}"  , "LTB" , 1, "R", $iCorLinha);
      
      imprimirCabecalho($oPdf, $iAlturalinha, false);
    }
    imprimirTotalizador($oPdf, $iAlturalinha, $oDadosPrincipal->oTotalPeriodo, $oDadosPrincipal->oTotalGeral);
  }
  $oPdf->Ln(1);
}

$oPdf->output();

function imprimirTotalizador($oPdf, $iAlturalinha, $oValoresPeriodo, $oTotalGeral){
  
  $oPdf->Ln(2);
  $oPdf->setfont('arial','b',6);
  
  $oPdf->cell(90,  $iAlturalinha, "Movimentação Geral"      , "TRL" , 0, "C", 1);
  $oPdf->cell(90,  $iAlturalinha, "Movimentação no Período" , "TRL" , 1, "C", 1);
  
  $oPdf->cell(60,  $iAlturalinha, "Quantidade"              , "BL" , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, "Valor"                   , "BR" , 0, "R", 1);
  $oPdf->cell(60,  $iAlturalinha, "Quantidade"              , "BL" , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, "Valor"                   , "BR" , 1, "R", 1);
  
  $oPdf->cell(30,  $iAlturalinha, "Entrada:"                        , "LT" , 0, "C", 1);
  $oPdf->setfont('arial','',6);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nQuantidadeEntrada  , "T" , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nValorEntrada       , "T" , 0, "R", 1);
  
  $oPdf->cell(30,  $iAlturalinha, ""                                          , "TL" , 0, "C", 1);
  $oPdf->cell(30,  $iAlturalinha, $oValoresPeriodo->nQuantidadeEntradaPeriodo , "T" , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, $oValoresPeriodo->nValorEntradaPeriodo      , "TR" , 1, "R", 1);  
  
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(30,  $iAlturalinha, "Saída:"                        , "L" , 0, "C", 1);
  $oPdf->setfont('arial','',6);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nQuantidadeSaida  , "" , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nValorSaida       , "" , 0, "R", 1);
  
  $oPdf->cell(30,  $iAlturalinha, ""                                        , "L" , 0, "C", 1);
  $oPdf->cell(30,  $iAlturalinha, $oValoresPeriodo->nQuantidadeSaidaPeriodo , ""  , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, $oValoresPeriodo->nValorSaidaPeriodo      , "R" , 1, "R", 1);  
  
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(30,  $iAlturalinha, "Saldo:"                       , "L" , 0, "C", 1);
  $oPdf->setfont('arial','',6);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nQuantidadeSaldo , ""  , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nValorSaldo      , ""  , 0, "R", 1);
  
  $oPdf->cell(30,  $iAlturalinha, "" , "L" , 0, "C", 1);
  $oPdf->cell(30,  $iAlturalinha, "" , ""  , 0, "R", 1);
  $oPdf->cell(30,  $iAlturalinha, "" , "R" , 1, "R", 1);  
  
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(60,  $iAlturalinha, "Preço Médio:"            , "LB" , 0, "R", 1);
  $oPdf->setfont('arial','',6);
  $oPdf->cell(30,  $iAlturalinha, $oTotalGeral->nPrecoMedio , "RB" , 0, "R", 1);
  
  $oPdf->cell(90,  $iAlturalinha, ""              , "LRB" , 1, "R", 1);
  
}

function imprimirAlmoxarifado($oPdf, $iAlturalinha, $lImprime, $oValores){
  
    $oPdf->setfont('arial','b',6);
    
    $oPdf->cell(20,  $iAlturalinha, "Almoxarifado:"              , "TB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(260, $iAlturalinha, "{$oValores->sAlmoxarifado}" , "TB" , 1, "L", 1);
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Material: "            , "TB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(70,  $iAlturalinha, "{$oValores->sMaterial}", "TB" , 0, "L", 1);
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Subgrupo: "            , "LTB", 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(170,  $iAlturalinha, "{$oValores->sSubGrupo}", "TB" , 1, "L", 1);
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Estoque Mínimo: "           , "TB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(20,  $iAlturalinha, "{$oValores->iEstoqueMinimo}", "TB" , 0, "L", 1);
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Estoque Máximo: "           , "LTB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(30,  $iAlturalinha, "{$oValores->iEstoqueMaximo}", "TB" , 0, "L", 1);

    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Ponto de Pedido: "                  , "LTB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(20,  $iAlturalinha, "{$oValores->iPontoPedido}"      , "TB" , 0, "L", 1);    
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Unidade: "                  , "LTB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(130,  $iAlturalinha, "{$oValores->sUnidade}"      , "TB" , 1, "L", 1);    
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Saldo Anterior: "           , "TB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(20,  $iAlturalinha, "{$oValores->iSaldoAnterior}", "TB" , 0, "L", 1);
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Quantidade: "               , "LTB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(30,  $iAlturalinha, "{$oValores->iQuantidade}"   , "TB" , 0, "L", 1);
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Preço Médio: "             , "LTB", 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(20,  $iAlturalinha, "{$oValores->nPrecoMedio}"  , "TB", 0, "L", 1);
    
    $oPdf->setfont('arial','b',6);
    $oPdf->cell(20,  $iAlturalinha, "Valor Total: "             , "LTB" , 0, "L", 1);
    $oPdf->setfont('arial','',6);
    $oPdf->cell(130,  $iAlturalinha, "{$oValores->nValorTotal}"  , "TB" , 1, "L", 1);    
    $oPdf->ln(2);
  
}

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {
  
  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {
    
    $oPdf->SetFont('arial', 'b', 6);
    
    if ( !$lImprime ) {
    	
      $oPdf->AddPage("L");
    }

    $oPdf->setfont('arial','b',6);
    
    $oPdf->cell(80,  $iAlturalinha, "Destino"       , "TBR" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Data"          , "LTBR", 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Empenho"       , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Ordem de Compra"           , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Movimento"     , "TBLR", 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Processo Pag"     , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Quantidade"        , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Valor Unitário"    , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Valor Total"  , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Saldo Quantidade"  , "TBL" , 0, "C", 1);
    $oPdf->cell(20,  $iAlturalinha, "Saldo Valor"   , "LTB" , 1, "C", 1);
    $oPdf->setfont('arial','',6);
  }
}
/**
 * criada funcao que retorna query, sera necessaria para termos o total geral, onde as datas serao diferentes das passadas na tela
 * @param unknown $iMaterial
 * @param unknown $iDepartamento
 * @param unknown $dtInicial
 * @param unknown $dtFinal
 * @return string
 */

function getQueryMovimento ($iMaterial, $iDepartamento, $dtInicial, $dtFinal){
  
  $sSqlMovimento  = "      select  matestoque.* ";
  $sSqlMovimento .= "                        ,m81_tipo,empnota.e69_numero ";
  $sSqlMovimento .= "                        ,m89_valorunitario   as  valor_unitario ";
  $sSqlMovimento .= "             , case
                                      when
                                        destino.coddepto is not null
                                      then destino.coddepto || ' - ' || destino.descrdepto
                                      when
                                        destino.coddepto is null
                                      then  db_depart.coddepto  || ' - ' || db_depart.descrdepto
                                    end as destino ";
  
  $sSqlMovimento .= "             ,case
                                     when
                                       e03_sequencial is not null
                                     then e03_numeroprocesso
                                     when
                                       e04_sequencial is not null
                                     then e04_numeroprocesso
                                   end as processopagamento ";
  
  $sSqlMovimento .= "             ,case ";
  $sSqlMovimento .= "                when  ";
  $sSqlMovimento .= "                  matrequi.m40_codigo is not null  ";
  $sSqlMovimento .= "                then 'R.Q ' || matrequi.m40_codigo ";
  $sSqlMovimento .= "                when  ";
  $sSqlMovimento .= "                  matrequi.m40_codigo is null and empnota.e69_numero is not null and m81_codtipo <> 5 ";
  $sSqlMovimento .= "                then 'N.F ' || empnota.e69_numero ";
  $sSqlMovimento .= "                when  ";
  $sSqlMovimento .= "                  m81_codtipo = 3  ";
  $sSqlMovimento .= "                then 'Entrada Manual'
                                     when
                                       m81_codtipo = 5
                                     then 'Saída Manual'
                                     when
                                       m81_codtipo = 19
                                     then 'Anulação de O.C' ";
  $sSqlMovimento .= "              end  as movimento ";
  
  $sSqlMovimento .= "             ,matordemitem. m52_codordem as ordemcompra";
  
  $sSqlMovimento .= "             ,empempenho. e60_codemp  || '/' || empempenho.e60_anousu as empenho    ";
  
  $sSqlMovimento .= "             ,matestoqueinimei.m82_quant      ";
  $sSqlMovimento .= "             ,db_depart.descrdepto            ";
  $sSqlMovimento .= "             ,matestoqueini.m80_data          ";
  $sSqlMovimento .= "             ,matestoquetipo.m81_codtipo      ";
  $sSqlMovimento .= "             ,matestoquetipo.m81_descr        ";
  
  $sSqlMovimento .= "         from matestoque                  ";
  $sSqlMovimento .= "   inner join matestoqueitem     on matestoqueitem.m71_codmatestoque    = matestoque.m70_codigo              ";
  $sSqlMovimento .= "   inner join matestoqueinimei   on matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc
                        inner join matestoqueinimeipm on m82_codigo                          = m89_matestoqueinimei   ";
  $sSqlMovimento .= "   inner join matestoqueini      on matestoqueini.m80_codigo            = matestoqueinimei.m82_matestoqueini ";
  $sSqlMovimento .= "   inner join matestoquetipo     on matestoquetipo.m81_codtipo          = matestoqueini.m80_codtipo          ";
  $sSqlMovimento .= "   inner join db_depart          on db_depart.coddepto                  = matestoqueini.m80_coddepto         ";
  
  
  $sSqlMovimento .= "    left join matestoqueinimeiari on matestoqueinimei.m82_codigo               = matestoqueinimeiari.m49_codmatestoqueinimei ";
  $sSqlMovimento .= "    left join atendrequiitem      on matestoqueinimeiari.m49_codatendrequiitem = atendrequiitem.m43_codigo ";
  $sSqlMovimento .= "    left join matrequiitem        on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo ";
  $sSqlMovimento .= "    left join matrequi            on matrequiitem.m41_codmatrequi = matrequi.m40_codigo        ";
  $sSqlMovimento .= "    left join db_depart destino   on destino.coddepto = matrequi. m40_depto                    ";
  
  $sSqlMovimento .= "    left join matestoqueitemoc    on matestoqueitem.m71_codlanc = matestoqueitemoc.m73_codmatestoqueitem ";
  $sSqlMovimento .= "    left join matordemitem        on matestoqueitemoc.m73_codmatordemitem = matordemitem.m52_codlanc ";
  $sSqlMovimento .= "    left join matestoqueitemnota  on matestoqueitem.m71_codlanc = matestoqueitemnota.m74_codmatestoqueitem ";
  $sSqlMovimento .= "    left join empnotaord on matordemitem.m52_codordem = empnotaord.m72_codordem";
  $sSqlMovimento .= "    left join empnota             on  empnotaord.m72_codnota= empnota.e69_codnota ";
  $sSqlMovimento .= "    left join empnotaprocesso     on empnota.e69_codnota = empnotaprocesso.e04_empnota ";
  $sSqlMovimento .= "    left join pagordemnota        on empnota.e69_codnota = pagordemnota.e71_codnota ";
  $sSqlMovimento .= "    left join pagordemprocesso    on pagordemnota.e71_codord = pagordemprocesso.e03_pagordem";
  $sSqlMovimento .= "    left join empempenho          on empnota.e69_numemp = empempenho.e60_numemp ";
  $sSqlMovimento .= "   where matestoque.m70_codmatmater = {$iMaterial}  ";
  $sSqlMovimento .= "     and db_depart.coddepto         = {$iDepartamento} ";
  $sSqlMovimento .= "     and m80_data between '{$dtInicial}' and '{$dtFinal}' ";
  $sSqlMovimento .= "   order by m80_data, m80_hora";
  
  return $sSqlMovimento;
}


/**
 * funcao para retornar os movimentos do item pelo almoxarifado e a data
 * @param integer $iMaterial
 * @param integer $iDepartamento
 * @param date $dtInicial
 * @param date $dtFinal
 * @return array:stdClass
 */
function getMovimentosItem( $iMaterial, $iDepartamento, $dtInicial, $dtFinal, $nSaldoAnterior){

  $aDadosMovimentacao = array();
  $oDadosRetorno = new stdClass();
  $oDadosRetorno->aDadosMovimentacao = array() ;
  $oDadosRetorno->oTotalPeriodo      = null;
  
  $sSqlMovimento  = getQueryMovimento($iMaterial, $iDepartamento, $dtInicial, $dtFinal);
  $rsMovimentos   = db_query($sSqlMovimento);
  if ( pg_num_rows($rsMovimentos) > 0 ) {

    /**
     * percorre os movimentos
     */
    $nSaldoInicial             = $nSaldoAnterior;
    $nQuantidadeEntradaPeriodo = 0;
    $nQuantidadeSaidaPeriodo   = 0;
    
    $nValorTotalEntrada = 0;
    $nValorTotalSaida   = 0;
    
    for ( $iMov = 0; $iMov < pg_num_rows($rsMovimentos); $iMov++) {

      $oDados = db_utils::fieldsMemory($rsMovimentos, $iMov);

      $sDestino       = $oDados->destino;
      $sData          = db_formatar($oDados->m80_data, 'd');
      $sEmpenho       = $oDados->empenho;
      $iOc            = $oDados->ordemcompra;
      $sMovimento     = $oDados->movimento;
      $sProcPag       = $oDados->processopagamento;
      
      $iQuantidade    = $oDados->m82_quant;
      $nValorUni      = $oDados->valor_unitario;
      $nValorTotal    = $nValorUni * $iQuantidade;
      
        if ($oDados->m81_tipo == 1) {
        
          $nSaldoInicial             += $oDados->m82_quant;
          $nQuantidadeEntradaPeriodo += $oDados->m82_quant;
          $nValorTotalEntrada        += $nQuantidadeEntradaPeriodo * $nValorUni;
          
        } else if ($oDados->m81_tipo == 2) {
        
          $nSaldoInicial           -= $oDados->m82_quant;
          $nQuantidadeSaidaPeriodo += $oDados->m82_quant;
          $nValorTotalSaida        += $nQuantidadeSaidaPeriodo * $nValorUni;
        }      
        
      $nSaldoQuant  = $nSaldoInicial;
      
      $nSaldoValor  = $nValorUni * $nSaldoQuant;
      
      $iTipoMovimento = $oDados->m81_tipo;
      
      $oDadosMovimento = new stdClass();                  // dados das movimentacoes
      $oDadosMovimento->sDestino       = $sDestino   ;
      $oDadosMovimento->sData          = $sData      ;
      $oDadosMovimento->sEmpenho       = $sEmpenho   ;
      $oDadosMovimento->iOc            = $iOc        ;
      $oDadosMovimento->sMovimento     = $sMovimento ;
      $oDadosMovimento->sProcPag       = $sProcPag   ;
      $oDadosMovimento->iQuantidade    = $iQuantidade;
      $oDadosMovimento->nValorUni      = db_formatar($nValorUni, 'f')  ;
      $oDadosMovimento->nValorTotal    = db_formatar($nValorTotal, 'f');
      $oDadosMovimento->nSaldoQuant    = $nSaldoQuant;
      $oDadosMovimento->nSaldoValor    = db_formatar($nSaldoValor, 'f');
      $oDadosMovimento->iTipoMovimento = $iTipoMovimento;
      
      $aDadosMovimentacao[]            = $oDadosMovimento;
    }
    
      $oTotalizador  = new stdClass();
      $oTotalizador->nQuantidadeEntradaPeriodo = $nQuantidadeEntradaPeriodo;
      $oTotalizador->nValorEntradaPeriodo      = db_formatar($nValorTotalEntrada, 'f');
      $oTotalizador->nQuantidadeSaidaPeriodo   = $nQuantidadeSaidaPeriodo;
      $oTotalizador->nValorSaidaPeriodo        = db_formatar($nValorTotalSaida, 'f');
      
      $oDadosRetorno->aDadosMovimentacao = $aDadosMovimentacao ;
      $oDadosRetorno->oTotalPeriodo      = $oTotalizador;
  }
  return $oDadosRetorno;
}

/**
 * funcao que retorna objeto com valores totais geral, nao somente totalizando o periodo passado pela tela
 * @param unknown $iMaterial
 * @param unknown $iDepartamento
 * @param DBDate $oDataInicial
 * @param unknown $nPrecoMedio
 * @return stdClass
 */
function  getTotalGeral($iMaterial, $iDepartamento, DBDate $oDataInicial, $nPrecoMedio){

  $nQuantidadeEntrada = 0;
  $nValorEntrada      = 0;
  $nQuantidadeSaida   = 0;
  $nValorSaida        = 0;
  $nQuantidadeSaldo   = 0;
  $nValorSaldo        = 0;
  $dtInicial          = $oDataInicial->getAno() . "-01-01";
  $dtFinal            = date('Y-m-d', db_getsession("DB_datausu"));

  $sSqlTotais   = getQueryMovimento ($iMaterial, $iDepartamento, $dtInicial, $dtFinal);

  $rsTotalGeral = db_query($sSqlTotais);
  if (pg_num_rows($rsTotalGeral) > 0 ) {

    for ( $iTotal = 0; $iTotal < pg_num_rows($rsTotalGeral); $iTotal++) {

      $oValores = db_utils::fieldsMemory($rsTotalGeral, $iTotal);

      switch ($oValores->m81_tipo) {
         
        case 1 : //entradas
           
          $nQuantidadeEntrada += $oValores->m82_quant;
          $nValorEntrada      += $nQuantidadeEntrada * $oValores->valor_unitario;
           
          break;
           
        case 2 : // saidas
           
          $nQuantidadeSaida += $oValores->m82_quant;
          $nValorSaida      += $nQuantidadeSaida * $oValores->valor_unitario;
           
          break;
      }
    }
    $nQuantidadeSaldo = $nQuantidadeEntrada - $nQuantidadeSaida;
    $nValorSaldo      = $nValorEntrada - $nValorSaida;
  }

  $oTotalGeral = new stdClass();
  $oTotalGeral->nQuantidadeEntrada = $nQuantidadeEntrada;
  $oTotalGeral->nValorEntrada      = db_formatar($nValorEntrada     , 'f');
  $oTotalGeral->nQuantidadeSaida   = $nQuantidadeSaida  ;
  $oTotalGeral->nValorSaida        = db_formatar($nValorSaida       , 'f');
  $oTotalGeral->nQuantidadeSaldo   = $nQuantidadeSaldo  ;
  $oTotalGeral->nValorSaldo        = db_formatar($nValorSaldo       , 'f');
  $oTotalGeral->nPrecoMedio        = db_formatar($nPrecoMedio       , 'f');

  return $oTotalGeral;
}

?>