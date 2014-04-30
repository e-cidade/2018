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

require_once ("libs/db_stdlib.php");
require_once ("fpdf151/pdf.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$sStringPeríodo = "";
$aWhere         = array();
$aWhere[]       = " tf26_i_codigo = 2"; // só pedidos encerrados

if (!empty($oGet->dtInicial)) {
	
  $oDataInicial   = new DBDate($oGet->dtInicial);
  $aWhere[]       = " tf01_d_datasistema >= '" . $oDataInicial->convertTo(DBDate::DATA_EN) . "'";   
  $sStringPeríodo = "Período de: {$oGet->dtInicial}";
}

if (!empty($oGet->dtFim)) {
	
  $oDataFinal      = new DBDate($oGet->dtFim);
  $aWhere[]        = " tf01_d_datasistema <= '" . $oDataFinal->convertTo(DBDate::DATA_EN) . "'";
  $sStringPeríodo .= " até: {$oGet->dtFim}";
}

if (!empty($oGet->aCGS)) {
  $aWhere[] = " tf01_i_cgsund in({$oGet->aCGS}) ";	
}

if (!empty($oGet->aPedido)) {  
  $aWhere[] = " tf01_i_codigo in({$oGet->aPedido}) ";
}

$sCampos  = "tf01_i_cgsund, z01_v_nome, tf01_i_codigo, tf01_d_datapreferencia, ";
$sCampos .= "(select s115_c_cartaosus ";
$sCampos .= "   from cgs_cartaosus ";
$sCampos .= "  where s115_i_cgs = tf01_i_cgsund "; 
$sCampos .= "    and s115_c_cartaosus is not null";
$sCampos .= "  limit 1) as cartaosus";

$oDaoTfd = new cl_tfd_pedidotfd();
$sSqlTfd = $oDaoTfd->sql_query(null, $sCampos, "z01_v_nome", implode(" and ", $aWhere));
$rsTfd   = $oDaoTfd->sql_record($sSqlTfd);

if ($oDaoTfd->numrows == 0) {
  
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi localizado nenhum pedido para os filtros selecionados.');
  exit;
}

$aPedidos = db_utils::getCollectionByRecord($rsTfd);

$head1  = "Por Pedido";
if (!empty($sStringPeríodo)) {
	$head2 = $sStringPeríodo;
}

$oPdf = new PDF("P", 'mm', 'A4');
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 20);
$oPdf->SetRightMargin(10);
$oPdf->SetFillColor(225);

$lPrimeiraPagina = true;
$iTotalRegistro  = 0;

foreach ($aPedidos as $oPedido) {
  
  if ($lPrimeiraPagina ) {
    
  	setHeader($oPdf);
    $lPrimeiraPagina = false;
  }
  
  $oPdf->SetFont("arial", '', 8);
  
  $sDtPreferencia = db_formatar($oPedido->tf01_d_datapreferencia, "d"); 
  
  $oPdf->Cell(15,  4, $oPedido->tf01_i_cgsund, 'TRB', 0, 'R');
  $oPdf->Cell(100, 4, $oPedido->z01_v_nome,    'TRB', 0, 'L');
  $oPdf->Cell(30,  4, $oPedido->cartaosus,     'TRB', 0, 'C');
  $oPdf->Cell(15,  4, $oPedido->tf01_i_codigo, 'TLB', 0, 'C');
  $oPdf->Cell(30,  4, $sDtPreferencia,         'TLB', 1, 'C');

  $iTotalRegistro ++;
}
$oPdf->SetFont("arial", 'b', 9);
$oPdf->Cell(170,  4, "Total de pedidos: ", 1, 0, 'R');
$oPdf->Cell(20,   4, $iTotalRegistro,      1, 1, 'R');


/**
 * Monta o cabeçalho
 * @param PDF $oPdf
 */
function setHeader(PDF $oPdf) {
	
  $oPdf->AddPage();
  $oPdf->SetFont("arial", 'b', 9);
  $oPdf->Cell(15,  4, "CGS",                 1, 0, 'C', 1);
  $oPdf->Cell(100, 4, "Nome",                1, 0, 'C', 1);
  $oPdf->Cell(30,  4, "Cartão SUS",          1, 0, 'C', 1);
  $oPdf->Cell(15,  4, "Pedido",              1, 0, 'C', 1);
  $oPdf->Cell(30,  4, "Data de Preferência", 1, 1, 'C', 1);
}

$oPdf->Output();