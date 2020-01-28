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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/ParameterException.php");
require_once("dbforms/db_funcoes.php");
require_once("model/patrimonio/Inventario.model.php");
require_once("model/patrimonio/InventarioBem.model.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemCedente.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once("model/configuracao/DBDepartamento.model.php");
require_once("model/configuracao/DBDivisaoDepartamento.model.php");
require_once("model/CgmFactory.model.php");
require_once("std/db_stdClass.php");
require_once("fpdf151/pdf.php");

$iAnoSessao = db_getsession("DB_anousu");
$oGet       = db_utils::postMemory($_GET);


/* configuramos as variaveis para ficar mais facil o tratamento das mesmas */
$oGet->lQuebraPagina == 't' ? $oGet->lQuebraPagina = true : $oGet->lQuebraPagina = false;
$oGet->lParametro    == 't' ? $oGet->lParametro    = true : $oGet->lParametro    = false;
$oGet->sOrdem = str_replace("coddepto"  , "depart_origem.coddepto", $oGet->sOrdem);
$oGet->sOrdem = str_replace("t30_codigo", "div_origem.t30_codigo" , $oGet->sOrdem);

$iMovFinanceira = $oGet->iMovimentacaoFinanceira;
$lMovFinanceira = false;
$sModelo        = "Modelo 1";
if ($iMovFinanceira == 1) {
  
  $sModelo = "Modelo 2";
  $lMovFinanceira = true;
}

$sCampos  = " distinct                                                          "; 
$sCampos .= " bens.t52_bem                       as codigo_bem,                 ";
$sCampos .= " (select t41_placa from bensplaca where bensplaca.t41_bem = bens.t52_bem order by t41_data desc limit 1) as placa_bem, ";
$sCampos .= " bens.t52_descr                     as descricao_bem,              ";
$sCampos .= " depart_origem.coddepto             as codigo_departamento_origem, ";
$sCampos .= " depart_origem.descrdepto           as departamento_origem,        ";
$sCampos .= " div_origem.t30_descr               as divisao_origem,             ";
$sCampos .= " inventariobem.t77_valordepreciavel as valor_depreciavel,          ";
$sCampos .= " inventariobem.t77_valorresidual    as valor_residual,             ";
$sCampos .= " depart_destino.descrdepto          as departamento_destino,       ";
$sCampos .= " div_destino.t30_descr              as divisao_destino,            ";
$sCampos .= " situabens.t70_descr                as situacao,                   ";

if ($oGet->lParametro) {
	$sCampos .= "orcorgao.o40_orgao   as codigo_orgao,";
	$sCampos .= "orcorgao.o40_descr   as descricao_orgao,";
	$sCampos .= "orcunidade.o41_descr as descricao_unidade,";
}
$sCampos .= "inventariobem.t77_vidautil as vida_util";

$sCamposOrdem = "";
if (!empty($oGet->sOrdem)) {
  $sCamposOrdem = "{$oGet->sOrdem} {$oGet->sTipoOrdem}";
}

$sWhereBens      = "inventariobem.t77_inventario = {$oGet->iCodigoInventario}";
$oDaoBens        = db_utils::getDao("bens");
$sSqlBuscaBens   = $oDaoBens->sql_query_dados_bem_inventario(null, $sCampos, $sCamposOrdem, $sWhereBens);
$rsBuscaBens     = $oDaoBens->sql_record($sSqlBuscaBens);
$iTotalRegistros = $oDaoBens->numrows;

if ($iTotalRegistros == 0) {
  
  $sMsg = _M('patrimonial.patrimonio.pat3_relatoriomanutencaoinventario002.nenhum_registro_encontrado');
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

$oInventario = new Inventario($oGet->iCodigoInventario);
switch ($oInventario->getSituacao()) {

  case 1:
    $sSituacao = "Ativo";
    break;
  case 2:
    $sSituacao = "Anulado";
    break;
  case 3:
  	$sSituacao = "Processado";
  	break;
  default:
    $sSituacao = "";
}
$iExercicio = $oInventario->getExercicio();
$head2 = "MANUTENÇÃO DE INVENTÁRIO";
$head3 = "Inventário: {$oGet->iCodigoInventario}";
$head4 = "Situação: {$sSituacao}";
$head5 = "Exercício: {$iExercicio}";
$head6 = "Modelo: {$sModelo} ";
$head7 = "Total de Registros: {$iTotalRegistros}";


$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);

$iAltura                     = 4;
$lAdicionaPagina             = true;
$nTotalRelatorio             = 0;
$nTotalRelatorioLaco         = 0;
$iWidthDescricao             = 60;
$iWidthValores               = 20;
$iContador                   = 0;
$iTotalRegistrosPagina       = 0;
$iTotalRegistrosGeral        = 0;
$iCodigoOrgaoAnterior        = 0;
$iCodigoDepartamentoAnterior = 0;
$iTamanho = 60;

for ($iRowBem = 0; $iRowBem < $iTotalRegistros; $iRowBem++) {


  $oStdBem = db_utils::fieldsMemory($rsBuscaBens, $iRowBem);

  /* Configuramos as variaveis para imprimir no relatorio */
  
  $sDivisaoOrigem  = $oStdBem->departamento_origem;
  $sDivisaoDestino = $oStdBem->departamento_destino;
  $sPosicaoOrigem  = "L";
  $sPosicaoDestino = "L";

  $nDepreciavel  = $oStdBem->valor_depreciavel;
  $nResidual     = $oStdBem->valor_residual;
  $nValorAtual   = $nDepreciavel +  $nResidual; 
  
  $iSubstr       = 33;
  $iTamanhoValor = 60;
  /*
   * @todo o 9.99 devera se trocado pelo valor Depreciável vindo da query
   */
  
  if ($lMovFinanceira) {
    
    $sDivisaoOrigem  = db_formatar($nValorAtual,"f") ;
    $sDivisaoDestino = db_formatar($nResidual, "f");
    $nValorAtual     = $nDepreciavel;
    
    
    $sPosicaoOrigem  = "R";
    $sPosicaoDestino = "R";
    $iTamanhoValor   = 30;
    $iWidthValores   = 30;
    $iWidthDescricao = 100;
    $iSubstr         = 55;
  }
  
  if (!empty($oStdBem->divisao_origem) && !$lMovFinanceira) {
    
    $sDivisaoOrigem .= " - {$oStdBem->divisao_origem}";
  	
  }
  
  
  
  if (!empty($oStdBem->divisao_destino) && !$lMovFinanceira) {
    
  	$sDivisaoDestino .= " - {$oStdBem->divisao_destino}";
  }
  
  /* Configuracoes por parametro */
  $iCodigoDepartamento         = $oStdBem->codigo_departamento_origem;
  $lQuebraOrgao                = false;
  $lQuebraDepartamento         = true;
  $iCodigoOrgao                = null;
  if ($oGet->lParametro) {

    $iWidthDescricao      = 80;
  	$iWidthValores        = 30;
  	$iCodigoOrgao         = $oStdBem->codigo_orgao;
  	$lQuebraOrgao         = true;
  	$lQuebraDepartamento  = false;
  }

  if ($oGet->lQuebraPagina && $lQuebraOrgao || $oPdf->gety() > $oPdf->h - 40 || $lAdicionaPagina) {

    if ($iCodigoOrgaoAnterior != $iCodigoOrgao) {

      imprimirRodape($oPdf, $iAltura, $nTotalRelatorioLaco, $iTotalRegistrosPagina);
      imprimeCabecalho($oPdf, $iAltura, $oGet->lParametro, $lMovFinanceira);
      $lAdicionaPagina = false;
      $iTotalRegistrosPagina = 0;
    }
  }

  if ($oGet->lQuebraPagina && $lQuebraDepartamento || $oPdf->gety() > $oPdf->h - 40 || $lAdicionaPagina) {

    if ($iCodigoDepartamentoAnterior != $iCodigoDepartamento) {

      imprimirRodape($oPdf, $iAltura, $nTotalRelatorioLaco, $iTotalRegistrosPagina);
      imprimeCabecalho($oPdf, $iAltura, $oGet->lParametro, $lMovFinanceira);
      $lAdicionaPagina = false;
      $iTotalRegistrosPagina = 0;
    }
  }
  
  $sDescricaoBem = substr($oStdBem->descricao_bem , 0, $iSubstr);
  //$iMovFinanceira
  $oPdf->cell(20, $iAltura, $oStdBem->placa_bem, 0, 0, "C", 0);
  $oPdf->cell(20, $iAltura, $oStdBem->codigo_bem, 0, 0, "C", 0);
  $oPdf->cell($iWidthDescricao, $iAltura,$sDescricaoBem , 0, 0, "L", 0);
  
  if ($oGet->lParametro) {
    
    $oPdf->cell(80, $iAltura, substr("{$oStdBem->descricao_orgao} - {$oStdBem->descricao_unidade}", 0, 40), 0, 0, "L", 0);
  } else {

    $oPdf->cell($iTamanhoValor, $iAltura, substr($sDivisaoOrigem  ,  0, 33), 0, 0, $sPosicaoOrigem, 0);
    $oPdf->cell($iTamanhoValor, $iAltura, substr($sDivisaoDestino ,  0, 33), 0, 0, $sPosicaoDestino, 0);
  }
  $oPdf->cell($iWidthValores, $iAltura, db_formatar($nValorAtual, "f"), 0, 0, "R", 0);
  $oPdf->cell($iWidthValores, $iAltura, $oStdBem->situacao,  0, 0, "L", 0);
  $oPdf->cell(20, $iAltura, $oStdBem->vida_util , 0, 1, "C", 0);

  if ($oGet->lParametro) {

    $oPdf->cell(140, $iAltura, substr($sDivisaoOrigem,  0, 33),  0, 0, $sPosicaoOrigem, 0);
    $oPdf->cell(140, $iAltura, substr($sDivisaoDestino, 0, 33), 0, 1, $sPosicaoDestino, 0);
  }

  /* Variaveis de Controle */
  $iContador++;
  $iTotalRegistrosPagina++;
  $iTotalRegistrosGeral++;
  
  //$nTotalRelatorioLaco += $oStdBem->valor_depreciavel;
  $nTotalRelatorioLaco += $nValorAtual;
  $iCodigoDepartamentoAnterior = $oStdBem->codigo_departamento_origem;
	$iCodigoOrgaoAnterior        = $iCodigoOrgao;

  if ($iTotalRegistrosGeral == $iTotalRegistros) {
    imprimirRodape($oPdf, $iAltura, $nTotalRelatorioLaco, $iTotalRegistrosPagina);
  }
}

$oPdf->Output();

function imprimeCabecalho($oPdf, $iAltura, $lParametro, $lMovFinanceira = false) {

  $iWidthDescricao = 60;
  $iWidthValores   = 20;
  $iTamanhoDpto    = 60;
  
  $sDptoOrigem  = "Departamento/Divisão - Origem";
  $sDptoDestino = "Departamento/Divisão - Destino";
  $sValorAtual  = "Valor Atual";
  
  if ($lMovFinanceira) {
    
    //$sDptoOrigem  = "Valor Depreciável";
    //$sDptoDestino = "Valor Residual";
    
    $sDptoOrigem  = "Valor Atual";
    $sDptoDestino = "Valor Residual";
    $iWidthValores = 30;
    $sValorAtual  = "Valor Depreciável";
    
    $iTamanhoDpto = 30;
    $iWidthDescricao = 100;
    
  }
  
  if ($lParametro) {

    $iWidthDescricao = 80;
    $iWidthValores   = 30;
  }
  $oPdf->setfont('Arial', 'b', 8);
  $oPdf->addPage();
  $oPdf->cell(20, $iAltura, "Placa",  1, 0, "C", 1);
  $oPdf->cell(20, $iAltura, "Código", 1, 0, "C", 1);
  $oPdf->cell($iWidthDescricao, $iAltura, "Descrição", 1, 0, "C", 1);

  if ($lParametro) {
    $oPdf->cell(80, $iAltura, "Órgão/Unidade", 1, 0, "C", 1);
  } else {

    $oPdf->cell($iTamanhoDpto, $iAltura, $sDptoOrigem,  1, 0, "C", 1);
    $oPdf->cell($iTamanhoDpto, $iAltura, $sDptoDestino, 1, 0, "C", 1);
  }
  $oPdf->cell($iWidthValores, $iAltura, $sValorAtual, 1, 0, "C", 1);
  $oPdf->cell($iWidthValores, $iAltura, "Situação",    1, 0, "C", 1);
  $oPdf->cell(20, $iAltura, "Vida Útil", 1, 1, "C", 1);

  if ($lParametro) {

    $oPdf->cell(140, $iAltura, $sDptoOrigem,  1, 0, "C", 1);
    $oPdf->cell(140, $iAltura, $sDptoDestino, 1, 1, "C", 1);
  }
  $oPdf->setfont('Arial', '', 8);
}

function imprimirRodape($oPdf, $iAltura, $nValor, $iTotalRegistros) {

  $nValor = db_formatar($nValor, "f");
  $oPdf->setfont('Arial', 'b', 8);
  $oPdf->cell(230, $iAltura, "Total: {$nValor}", 0, 0, "R", 1);
  $oPdf->cell(50, $iAltura, "Registros: {$iTotalRegistros}", 0, 1, "R", 1);
  $oPdf->setfont('Arial', '', 8);
}
?>