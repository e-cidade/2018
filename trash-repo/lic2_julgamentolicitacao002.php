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

/**
*
* @author 
* @revision $Author: dbandrio.costa $
* @version $Revision: 1.1 $
*/
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet                    = db_utils::postMemory($_GET);
$oDaoJulgamentoLicitacao = db_utils::getDao('liclicita');

$sCampos  = " pc92_hora, pc92_datajulgamento, pc92_usuario, nome as usuario, ";
$sCampos .= " case when pc92_ativo = true then 'Ativo' else 'Cancelado' end as status, "; 
$sCampos .= " fornecedor.z01_nome as fornecedor, pc01_descrmater, pc93_valorunitario, pc93_pontuacao ";
$sOrdem   = " pc92_sequencial";
$sWhere   = " l20_codigo = {$oGet->l20_codigo}";

$sSqlJulgamentoLicitacao = $oDaoJulgamentoLicitacao->sql_query_julgamento_licitacao(null, $sCampos, $sOrdem, $sWhere);
$rsJulgamentoLicitacao   = $oDaoJulgamentoLicitacao->sql_record($sSqlJulgamentoLicitacao);

if ($oDaoJulgamentoLicitacao->numrows == 0) {

  $sErroMsg  = "Não a histórico de julgamento para a Licitação nº: {$oGet->l20_codigo} ";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}

$aDadosJulgamentoLicitacao = db_utils::getCollectionByRecord($rsJulgamentoLicitacao, true);

$head1 = "Dados do Julgamento Licitação";
$head2 = "Licitação: {$oGet->l20_codigo}";

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);

$iHeigth             = 4;
$iWidth              = 100;
$lPrimeiroLaco       = true;


foreach ($aDadosJulgamentoLicitacao as $oJulgamentoLicitacao) {
  
  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {
  
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  }
  
  $sUsuario    = str_pad($oJulgamentoLicitacao->pc92_usuario, 3, " ", STR_PAD_RIGHT);
  $sUsuario   .= " - {$oJulgamentoLicitacao->usuario}";
  $sUsuario    = substr($sUsuario, 0, 31);
  $sFornecedor = substr($oJulgamentoLicitacao->fornecedor, 0, 37);
  $sMaterial   = substr($oJulgamentoLicitacao->pc01_descrmater, 0, 42);
   
  $oPdf->setfont('arial', '', 8);
  
  $oPdf->Cell(15,  $iHeigth, $oJulgamentoLicitacao->pc92_hora,                             1, 0, "C");
  $oPdf->Cell(20,  $iHeigth, $oJulgamentoLicitacao->pc92_datajulgamento,                   1, 0, "C");
  $oPdf->Cell(50,  $iHeigth, $sUsuario,                                                    1, 0, "F");
  $oPdf->Cell(60,  $iHeigth, $sFornecedor,                                                 1, 0, "F");
  $oPdf->Cell(65,  $iHeigth, $sMaterial,                                                   1, 0, "F");
  $oPdf->Cell(26,  $iHeigth, db_formatar($oJulgamentoLicitacao->pc93_valorunitario, 'f'),  1, 0, "R");
  $oPdf->Cell(25,  $iHeigth, $oJulgamentoLicitacao->pc93_pontuacao,                        1, 0, "R");
  $oPdf->Cell(17,  $iHeigth, $oJulgamentoLicitacao->status,                                1, 1, "R");
}

/**
* Insere o cabeçalho do relatório de acordo com o tipo: (Sintético, Analítico ou Acumulado)
* @param object $oPdf
* @param integer $iHeigth Altura da linha
*/
function setHeader($oPdf, $iHeigth) {

  $oPdf->setfont('arial', 'b', 9);
  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->Cell(15,  $iHeigth, "Hora",        "TBR", 0, "C", 1);
  $oPdf->Cell(20,  $iHeigth, "Julgada",     "LTB", 0, "C", 1);
  $oPdf->Cell(50,  $iHeigth, "Usuário",     "LTB", 0, "C", 1);
  $oPdf->Cell(60,  $iHeigth, "Fornecedor",  "LTB", 0, "C", 1);
  $oPdf->Cell(65,  $iHeigth, "Item",        "LTB", 0, "C", 1);
  $oPdf->Cell(26,  $iHeigth, "Vlr Unit.",   "LTB", 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Pontuação",   "TBL", 0, "C", 1);
  $oPdf->Cell(17,  $iHeigth, "Status",      "TBL", 1, "C", 1);

}

$oPdf->Output();