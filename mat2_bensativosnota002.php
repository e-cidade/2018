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

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("model/ordemCompra.model.php");

$oGet = db_utils::postmemory($_GET);
if (!isset($oGet->iCodigoNota) || trim($oGet->iCodigoNota) == '') {
  
  $sErro = "Nota não informada.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErro}");
}

$oOrdemCompra = new ordemCompra(null);
$aBensAtivos  = $oOrdemCompra->getBensAtivoNota($oGet->iCodigoNota);
if (count($aBensAtivos) > 0) {
  
  $head3        = "Bem(ns) ativo(s) no patrimônio referente(s) a nota {$oGet->iCodigoNota}";
  $oPdf         = new PDF();
  $oPdf->Open();
  $oPdf->AddPage();
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $iAlturaLinha = 4;
  $oPdf->Cell(30, $iAlturaLinha, 'EMPENHO', 1, 0, "C", 1);
  $oPdf->Cell(30, $iAlturaLinha, 'PLACA', 1, 0, "C", 1);
  $oPdf->Cell(130, $iAlturaLinha, 'DESCRIÇÃO', 1, 1, "L", 1);
  
  foreach ($aBensAtivos as $oBemAtivo) {
    
    $oPdf->setfillcolor(255);
    $oPdf->setfont('arial', 'b', 7);
    $oPdf->Cell(30, $iAlturaLinha, $oBemAtivo->sEmpenho.'/'.$oBemAtivo->iAnoEmpenho, 1, 0, "C", 1);
    $oPdf->Cell(30, $iAlturaLinha, $oBemAtivo->sPlaca.$oBemAtivo->iPlacaSeq, 1, 0, "C", 1);
    $oPdf->Cell(130, $iAlturaLinha, $oBemAtivo->sDescricaoBem, 1, 1, "L", 1);
  }
  $oPdf->Output();
} else {
  
  $sErro = "Não foram encontrados bens ativos para a nota.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErro}");
}