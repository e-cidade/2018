<?php
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
require_once ("fpdf151/assinatura.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_liborcamento.php");
require_once ("libs/db_libcontabilidade.php");

db_app::import("contabilidade.relatorios.AnexoSeguridadeSocial");

$clAssinatura     = new cl_assinatura;
$oGet             = db_utils::postMemory($_GET);
$sInstituicao     = str_replace("-", ",", $oGet->db_selinstit);
$iCodigoRelatorio = 120;
$iAnoUsu          = db_getsession("DB_anousu");

/**
 * Monta Sql para buscar as Instituições
 */
$sSqlInstit   = "select codigo,nomeinst, munic from db_config where codigo in ({$sInstituicao}) ";

$rsInstit     = db_query($sSqlInstit);
$oInstit      = db_utils::getColectionByRecord($rsInstit);

$sCodInstit   = "";
$sNomeInstit  = "";
$sVirgula     = "";
/**
 * Monta uma String com as instituições recebidas
 */
foreach ($oInstit as $key => $value) {
  
  $sNomeInstit .= $sVirgula.$value->nomeinst;
  $sCodInstit  .= "{$sVirgula}{$value->codigo}"; 
  $sVirgula     = ", ";
}

$sConsolidadas = "";
if ($oGet->lConsolidado == 1) {
  $sConsolidadas = " - Consolidadas";
}

/**
 * Monta Cabeçalho
 */
$head1 = "MUNICÍPIO DE {$oInstit[0]->munic}";
$head2 = "Demonstrativo do Orçamento da Seguridade Social {$sConsolidadas}";
$head3 = "Leio Orçamentária Anual de  {$iAnoUsu}";
$head4 = "INSTITUIÇÕES : ".substr($sNomeInstit, 0, 120);

if ($oGet->iOrigemFase != 1) {
  
  $sOrigemTipo = "";
  switch ($oGet->iOrigemFase) {
    
    case 2:

      $sOrigemTipo = "Empenhado";
      break;
    case 3:
      
      $sOrigemTipo = "Liquidado";
      break;
    case 4:
      
      $sOrigemTipo = "Pago";
      break;
  }
  $head5 = "Valor {$sOrigemTipo} ";
}

if ($oGet->iOrigemFase != 1) {
  
  $oDaoPeriodo      = db_utils::getDao("periodo");
  $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($oGet->iPeriodo);
  $rsPeriodo        = db_query($sSqlDadosPeriodo);
  $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0);
  
  $head6 = "Periodo: JANEIRO a {$oDadosPerido->o114_descricao}";
}



/**
 * Busca os Balancetes 
 */
$oRelatorio    = new AnexoSeguridadeSocial($iAnoUsu, $iCodigoRelatorio, $oGet->iPeriodo);
$oRelatorio->setOrigem($oGet->iOrigemFase);
$oRelatorio->setInstituicoes($sInstituicao);
$oRelatorio->setUsuario(db_getsession("DB_id_usuario"));

$aDadosRelatorio = $oRelatorio->getDados();


// Variável de controle para primeira Página
$lPrimeiraPagina = true;

$oPdf  = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages();
$oPdf->setfont('arial', 'b', 8);
$oPdf->setleftmargin(10);
/**
 * Variaveis para controle do PDF
 */
$iAlt            = 4; // Altura das Linhas  
$lPrimeiraPagina = true;

/**
 * Variáveis Totalizadoras
 */
$nTotalOperacoesEspeciais = "";
$nTotalProjetos           = "";
$nTotalAtividades         = "";
$nTotalTotal              = "";

foreach ($aDadosRelatorio as $iInd => $oFuncoes) {
  
  $sEstrutural = "";
  /**
   * Imprime Cabeçalho 
   */
 
  escreveCabecalho($oPdf);
  /**
   * Imprime Funções
   */
  
  if ($oFuncoes->nTotal == 0) {
    continue;
  }
  
  $sEstrutural = $oFuncoes->codigoFuncao;
  
  $oPdf->setfont('arial', "B",6);
  $oPdf->cell(25,  $iAlt, $sEstrutural,                                   "R",  0, "L");
  $oPdf->cell(133, $iAlt, substr($oFuncoes->descr, 0, 100),               "LR", 0, "L");
  $oPdf->cell(30,  $iAlt, db_formatar($oFuncoes->nProjeto,'f'),           "LR", 0, "R");
  $oPdf->cell(30,  $iAlt, db_formatar($oFuncoes->nAtividade,'f'),         "LR", 0, "R");
  $oPdf->cell(30,  $iAlt, db_formatar($oFuncoes->nOperacaoEspecial,'f'),  "LR", 0, "R");
  $oPdf->cell(30,  $iAlt, db_formatar($oFuncoes->nTotal,'f'),             "L",  1, "R");
  
  /**
   * Somatório das Colunas
   */
  $nTotalOperacoesEspeciais += $oFuncoes->nOperacaoEspecial;
  $nTotalProjetos           += $oFuncoes->nProjeto;
  $nTotalAtividades         += $oFuncoes->nAtividade;
  $nTotalTotal              += $oFuncoes->nTotal;
  
  foreach ($oFuncoes->subfuncao as $iIndSubFuncao => $oSubFuncao) {
    
    if ($oSubFuncao->nTotal == 0) {
      continue;
    }
    escreveCabecalho($oPdf);
  /**
   * Imprime SubFunções
   */
    $sEstrutural = $oFuncoes->codigoFuncao.".".$oSubFuncao->codigoSubFuncao;
    $oPdf->setfont('arial', "B",6);
    $oPdf->cell(25,  $iAlt, $sEstrutural,                                    "R",  0, "L");
    $oPdf->cell(133, $iAlt, "   ".substr($oSubFuncao->descr, 0, 100),        "LR", 0, "L");
    $oPdf->cell(30,  $iAlt, db_formatar($oSubFuncao->nProjeto,'f'),          "LR", 0, "R");
    $oPdf->cell(30,  $iAlt, db_formatar($oSubFuncao->nAtividade,'f'),        "LR", 0, "R");
    $oPdf->cell(30,  $iAlt, db_formatar($oSubFuncao->nOperacaoEspecial,'f'), "LR", 0, "R");
    $oPdf->cell(30,  $iAlt, db_formatar($oSubFuncao->nTotal,'f'),            "L",  1, "R");
    
    foreach ($oSubFuncao->projetos as $iIndProjeto => $oProjeto) {
      
      /**
       * Se a linha não tiver valor, não imprime a linha
       */
      if ($oProjeto->nTotal != 0) {

        escreveCabecalho($oPdf);
        /**
         * Imprime Projetos
         */
        $iEstruturaProjeto = str_pad($oProjeto->codigoProjeto, 4, "0", STR_PAD_LEFT);
        $sEstrutural       = $oFuncoes->codigoFuncao.".".$oSubFuncao->codigoSubFuncao.".".$iEstruturaProjeto;
        $oPdf->setfont('arial', "",6);
        $oPdf->cell(25,  $iAlt, $sEstrutural,                                  "R", 0, "L");
        $oPdf->cell(133, $iAlt, "      ".substr($oProjeto->descr, 0, 100),     "LR", 0, "L");
        $oPdf->cell(30,  $iAlt, db_formatar($oProjeto->nProjeto,'f'),          "LR", 0, "R");
        $oPdf->cell(30,  $iAlt, db_formatar($oProjeto->nAtividade,'f'),        "LR", 0, "R");
        $oPdf->cell(30,  $iAlt, db_formatar($oProjeto->nOperacaoEspecial,'f'), "LR", 0, "R");
        $oPdf->cell(30,  $iAlt, db_formatar($oProjeto->nTotal,'f'),            "L", 1, "R");
      }
    }
  }
}

/**
 * Imprime Somatório
 */
$oPdf->setfont('arial', "B",6);
$oPdf->cell(158, $iAlt, "Total",       "TB", 0, "R");
$oPdf->cell(30,  $iAlt, db_formatar($nTotalProjetos,'f'),           1, 0, "R");
$oPdf->cell(30,  $iAlt, db_formatar($nTotalAtividades,'f'),         1, 0, "R");
$oPdf->cell(30,  $iAlt, db_formatar($nTotalOperacoesEspeciais,'f'), 1, 0, "R");
$oPdf->cell(30,  $iAlt, db_formatar($nTotalTotal,'f'),              "BTL", 1, "R");

$oRelatorio->getNotaExplicativa($oPdf,$oGet->iPeriodo);
$oPdf->Output();

function escreveCabecalho ($oPdf) {

  global $lPrimeiraPagina, $iAlt;
  if ($oPdf->gety() > $oPdf->h - 25 || $lPrimeiraPagina) {

    $lPrimeiraPagina = false;
    $oPdf->line(10, $oPdf->getY(), 288, $oPdf->getY());
    $oPdf->addpage("L");
    $oPdf->setfont('arial','B',8);
    $oPdf->cell(25,  $iAlt, "Código",                    "BRT",  0, "C", 0);
    $oPdf->cell(133, $iAlt, "Função/SubFunção/Projetos", "BT",   0, "C", 0);
    $oPdf->cell(30, $iAlt,  "Projetos",                  "BLRT", 0, "C", 0);
    $oPdf->cell(30, $iAlt,  "Atividades",                "BLRT", 0, "C", 0);
    $oPdf->cell(30,  $iAlt, "Operações Especiais",       "BLRT", 0, "C", 0);
    $oPdf->cell(30, $iAlt,  "Total",                     "BT",   0, "C", 0);
    
    $oPdf->ln();
  } 
}
?>