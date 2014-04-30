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
require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");

require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

require_once("model/aberturaRegistroPreco.model.php");
require_once("model/estimativaRegistroPreco.model.php");
require_once("model/compilacaoRegistroPreco.model.php");
require_once("model/ItemEstimativa.model.php");

db_postmemory($_POST);

$oEstimativaRegistroPreco  = new estimativaRegistroPreco($pc10_numero);
$oAberturaRegistroPreco    = new aberturaRegistroPreco($oEstimativaRegistroPreco->getCodigoAbertura());
$aCompilacoesRegistroPreco = $oAberturaRegistroPreco->getCompilacoes(true);

$aCodigoCompilacoesRegistroPreco = array();

if ($aCompilacoesRegistroPreco) {
	foreach ($aCompilacoesRegistroPreco as $oCompilacaoRegistroPreco) {
		$aCodigoCompilacoesRegistroPreco[] = $oCompilacaoRegistroPreco->getCodigoSolicitacao();
	}
}

$head1 = "Abertura de Registro de Preço:\n";
$head2 = $oAberturaRegistroPreco->getCodigoSolicitacao();
$head3 = "Compilação de Registro de Preço:\n";
$head4 = implode(", ", $aCodigoCompilacoesRegistroPreco);
$head5 = "Estimativa de Registro de Preço:\n";
$head6 = $oEstimativaRegistroPreco->getCodigoSolicitacao();
$head7 = "Departamento:\n";
$head8 = $oEstimativaRegistroPreco->getDescricaoDepartamento();

$aDados = $oEstimativaRegistroPreco->getItens();

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->Setfillcolor(235);
$oPdf->AddPage("L");

$oPdf->setfont('arial','b',8);

//Cabeçalhos
escreverCabecalho ( $oPdf );

$oPdf->setfont('arial','',8);

$iSequencial=1;

$nQtdSaldo      = 0;
$nQtdTotal      = 0;
$nQtdCedida     = 0;
$nQtdRecebida   = 0;
$nQtdSolicitada = 0;
$nQtdEmpenhada  = 0;
$nQtdeExecedido = 0;
$iAlt					  = 5;
foreach ($aDados as $oIt) {
	
  $oQtdDisponiveis       = $oIt->getMovimentacao();
  
  $nQtdSaldo      += $oQtdDisponiveis->saldo;      // R
  $nQtdTotal      += $oQtdDisponiveis->quantidade; // R
  $nQtdCedida     += $oQtdDisponiveis->cedidas;    // R
  $nQtdRecebida   += $oQtdDisponiveis->recebidas;  // R
  $nQtdSolicitada += $oQtdDisponiveis->solicitada; // R
  $nQtdEmpenhada  += $oQtdDisponiveis->empenhada;  // R
  $nQtdeExecedido += $oQtdDisponiveis->execedente; // R*/

  $sDescricao = substr ( urldecode( $oIt->getDescricaoMaterial() ), 0, 50 );
  
  $oPdf->cell(10, $iAlt, $iSequencial , 1, 0, "C", false);
  $oPdf->cell(20, $iAlt, $oIt->getCodigoMaterial()    , 1, 0, "C", false);
  $oPdf->cell(90, $iAlt, $sDescricao,   1, 0, "L", false);
  $oPdf->cell(20, $iAlt, $oIt->getUnidade()   , 1, 0, "R", false);
  $oPdf->cell(20, $iAlt, db_formatar($oIt->getQuantidade(),        'f'), 1, 0, "R", false);
  
  $oPdf->cell(20, $iAlt, db_formatar($oQtdDisponiveis->cedidas,    'f'), 1, 0, "R", false);
  $oPdf->cell(20, $iAlt, db_formatar($oQtdDisponiveis->recebidas,  'f'), 1, 0, "R", false);
  $oPdf->cell(20, $iAlt, db_formatar($oQtdDisponiveis->solicitada, 'f'), 1, 0, "R", false);
  $oPdf->cell(20, $iAlt, db_formatar($oQtdDisponiveis->empenhada,  'f'), 1, 0, "R", false);
  $oPdf->cell(20, $iAlt, db_formatar($oQtdDisponiveis->execedente, 'f'), 1, 0, "R", false);
  $oPdf->cell(20, $iAlt, db_formatar($oQtdDisponiveis->saldo,      'f'), 1, 0, "R", false);
  
  $oPdf->ln();
  
  if( ($iSequencial % 29) == 0){
  	escreverCabecalho( $oPdf );
  }
  
  $iSequencial++;
}

$oPdf->setfont('arial','b',8);
$oPdf->cell(140, $iAlt, 'TOTAL'  , 1, 0, "C", true);

$oPdf->cell(20, $iAlt, db_formatar($nQtdTotal,      'f'), 1, 0, "R", true);
$oPdf->cell(20, $iAlt, db_formatar($nQtdCedida,     'f'), 1, 0, "R", true);
$oPdf->cell(20, $iAlt, db_formatar($nQtdRecebida,   'f'), 1, 0, "R", true);
$oPdf->cell(20, $iAlt, db_formatar($nQtdSolicitada, 'f'), 1, 0, "R", true);
$oPdf->cell(20, $iAlt, db_formatar($nQtdEmpenhada,  'f'), 1, 0, "R", true);
$oPdf->cell(20, $iAlt, db_formatar($nQtdeExecedido, 'f'), 1, 0, "R", true);
$oPdf->cell(20, $iAlt, db_formatar($nQtdSaldo,      'f'), 1, 0, "R", true);

$oPdf->ln();


$oPdf->Output();

function escreverCabecalho ( &$oPdf ){
	
	$iAlt = 5;
	$oPdf->cell(10, $iAlt, 'Seq'       , 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Código'    , 1, 0, "C", true);
	$oPdf->cell(90, $iAlt, 'Descrição' , 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Unidade'   , 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Quantidade', 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Cedida'    , 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Recebida'  , 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Solicitada', 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Empenhada ', 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Exec.'     , 1, 0, "C", true);
	$oPdf->cell(20, $iAlt, 'Saldo '    , 1, 0, "C", true);
	
	$oPdf->ln();
	
}