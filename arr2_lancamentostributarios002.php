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
require_once("std/DBNumber.php");

/**
 * Variaveis do header do PDF 
 */

$oGet         = db_utils::postMemory($_GET);

$oDaoIptuCalv = db_utils::getDao('iptucalv');
$rsIptuCalv   = $oDaoIptuCalv->sql_record($oDaoIptuCalv->sql_queryValoresCalculoIptu($oGet->iAnoCalculo));
$aCalculoIptu = db_utils::getCollectionByRecord($rsIptuCalv);

$oDaoIssCalc  = db_utils::getDao('isscalc');
$rsIssCalc    = $oDaoIssCalc->sql_record($oDaoIssCalc->sql_queryIssqnVistorias($oGet->iAnoCalculo));
$aCalculoIssqn = db_utils::getCollectionByRecord($rsIssCalc);

$head3 = "Relatório de Lançamentos Tributários";
$head5 = "Exercício dos Lançamentos: $oGet->iAnoCalculo";
$head6 = "Data de Geração: " . date('d/m/Y', db_getsession('DB_datausu'));
$head7 = '';

$oPdf = new PDF('L');
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->AddPage();
$oPdf->SetFillColor(235);

/**
 * Escreve titulo do IPTU  
 */
$oPdf->Setfont('Arial', 'b', 9);
$oPdf->cell( largura(0), 6, "IPTU {$oGet->iAnoCalculo}", 1, 0, 'L', 1);
$oPdf->ln(6);

/**
 * Escreve colunas de cabecalhos do IPTU
 */
headerIPTU($oPdf);

/**
 * linhas do IPTU 
 */

foreach ($aCalculoIptu as $oCalculoIptu) {
  
  linhaIPTU($oPdf, array($oCalculoIptu->codigo_receita,
                         $oCalculoIptu->descricao_receita, 
                         $oCalculoIptu->quantidade,
                         db_formatar($oCalculoIptu->valor_calculado, 'f'),
                         db_formatar($oCalculoIptu->valor_isento, 'f'),
                         db_formatar($oCalculoIptu->valor_importado, 'f'),
                         db_formatar($oCalculoIptu->valor_pago, 'f'),
                         db_formatar($oCalculoIptu->valor_cancelado, 'f'),
                         db_formatar($oCalculoIptu->valor_a_pagar, 'f')));
  
}

/**
 * Espaco entre as duas tabelas 
 */
$oPdf->ln(10);

/**
 * Escreve titulo do ISSQN  
 */
$oPdf->Setfont('Arial', 'b', 9);
$oPdf->cell( largura(0), 6, "ISSQN / Vistorias {$oGet->iAnoCalculo}", 1, 0, 'L', 1);
$oPdf->ln(6);

/**
 * Escreve colunas de cabecalhos do ISSQN  
 */
headerISSQN($oPdf);

/**
 * Linhas do ISSQN 
 */

foreach ($aCalculoIssqn as $oCalculoIssqn) {
  
  linhaISSQN($oPdf, array($oCalculoIssqn->tipodebito,
                          $oCalculoIssqn->codigo_receita,
                          $oCalculoIssqn->receita,
                          $oCalculoIssqn->quantidade, 
                          db_formatar($oCalculoIssqn->valor_calculado, 'f'),
                          db_formatar($oCalculoIssqn->valor_importado, 'f'),
                          db_formatar($oCalculoIssqn->valor_pago, 'f'), 
                          db_formatar($oCalculoIssqn->valor_cancelado, 'f'), 
                          db_formatar($oCalculoIssqn->valor_a_pagar, 'f')));
  
}


/**
 * Manda para o browser o pdf 
 */
$oPdf->Output();


/**
 * Calcula a largura da linha pela porcentagem 
 * 
 * @param float $nPorcentagem 
 * @access public
 * @return integer
 */
function largura($nPorcentagem = 0) {

  $iColuna = 0;
  $iTotalLinha = 280;

  if ( $nPorcentagem == 0 ) {
    return $iTotalLinha;
  }

  $iColuna = $nPorcentagem / 100 * $iTotalLinha;
  $iColuna = round($iColuna, 2);

  return $iColuna;
}

/**
 * Escreve cabecalho do IPTU
 * 
 * @param object $oPdf 
 * @access public
 * @return void
 */
function headerIPTU($oPdf) {

  $oPdf->Setfont('Arial', 'b', 8);
  $oPdf->cell( largura( 5), 5, 'Receita',        1, 0, 'C');
  $oPdf->cell( largura(15), 5, 'Descrição',      1, 0, 'C');
  $oPdf->cell( largura( 8), 5, 'Quantidade',     1, 0, 'C');
  $oPdf->cell( largura(12), 5, 'Vlr Calculado',  1, 0, 'C');
  $oPdf->cell( largura(12), 5, 'Vlr Isento',     1, 0, 'C');
  $oPdf->cell( largura(12), 5, 'Vlr Importado',  1, 0, 'C');
  $oPdf->cell( largura(12), 5, 'Vlr Pago/Bruto', 1, 0, 'C');
  $oPdf->cell( largura(12), 5, 'Vlr Cancelado',  1, 0, 'C');
  $oPdf->cell( largura(12), 5, 'Vlr a pagar',    1, 0, 'C');
  $oPdf->ln();
}

/**
 * Escreve linhas de IPTU 
 * 
 * @param object $oPdf 
 * @param array $aLinha 
 * @access public
 * @return void
 */
function linhaIPTU($oPdf, $aLinha) {

  $oPdf->Setfont('Arial', '', 8);
  $oPdf->cell( largura( 5), 5, $aLinha[0],  1, 0, 'L');
  $oPdf->cell( largura(15), 5, $aLinha[1],  1, 0, 'L');
  $oPdf->cell( largura( 8), 5, $aLinha[2],  1, 0, 'R');
  $oPdf->cell( largura(12), 5, $aLinha[3],  1, 0, 'R');
  $oPdf->cell( largura(12), 5, $aLinha[4],  1, 0, 'R');
  $oPdf->cell( largura(12), 5, $aLinha[5],  1, 0, 'R');
  $oPdf->cell( largura(12), 5, $aLinha[6],  1, 0, 'R');
  $oPdf->cell( largura(12), 5, $aLinha[7],  1, 0, 'R');
  $oPdf->cell( largura(12), 5, $aLinha[8],  1, 0, 'R');
  $oPdf->ln();
}

/**
 * Escreve cabecalho do ISSQN 
 * 
 * @param object $oPdf 
 * @access public
 * @return void
 */
function headerISSQN($oPdf) {

  $oPdf->Setfont('Arial', 'b', 8);
  $oPdf->cell( largura(18), 5, 'Tipo',          1, 0, 'C');
  $oPdf->cell( largura( 5), 5, 'Receita',       1, 0, 'C');
  $oPdf->cell( largura(15), 5, 'Descrição',     1, 0, 'C');
  $oPdf->cell( largura( 7), 5, 'Quantidade',    1, 0, 'C');
  $oPdf->cell( largura(11) , 5, 'Vlr Calculado', 1, 0, 'C');
  $oPdf->cell( largura(11) , 5, 'Vlr Importado', 1, 0, 'C');
  $oPdf->cell( largura(11) , 5, 'Vlr Pago',      1, 0, 'C');
  $oPdf->cell( largura(11) , 5, 'Vlr Cancelado', 1, 0, 'C');
  $oPdf->cell( largura(11) , 5, 'Vlr a pagar',   1, 0, 'C');
  $oPdf->ln();
}

/**
 * Escreve linhas do ISSQN 
 * 
 * @param object $oPdf 
 * @param array $aLinha 
 * @access public
 * @return void
 */
function linhaISSQN($oPdf, $aLinha) {
  
  $oPdf->Setfont('Arial', '', 8);
  $oPdf->cell( largura(18), 5, $aLinha[0],  1, 0, 'L');
  $oPdf->cell( largura( 5), 5, $aLinha[1],  1, 0,  'L');
  $oPdf->cell( largura(15), 5, $aLinha[2],  1, 0, 'L');
  $oPdf->cell( largura( 7), 5, $aLinha[3],  1, 0, 'R');
  $oPdf->cell( largura(11), 5, $aLinha[4],  1, 0, 'R');
  $oPdf->cell( largura(11), 5, $aLinha[5],  1, 0, 'R');
  $oPdf->cell( largura(11), 5, $aLinha[6],  1, 0, 'R');
  $oPdf->cell( largura(11), 5, $aLinha[7],  1, 0, 'R');
  $oPdf->cell( largura(11), 5, $aLinha[8],  1, 0, 'R');
  $oPdf->ln();
}