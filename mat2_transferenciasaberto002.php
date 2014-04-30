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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

/**
 * Tratamentos de parâmetros
 */
$dtInicial        = "1900-01-01";
$dtFinal          = date("Y-m-d", db_getsession("DB_datausu"));

if (!empty($oGet->dtInicial)) {
  $dtInicial = implode("-", array_reverse(explode("/", $oGet->dtInicial)));
}

if (!empty($oGet->dtFinal)) {
  $dtFinal = implode("-", array_reverse(explode("/", $oGet->dtFinal)));
}

$aWhereParametros   = array();
$aWhereParametros[] = "matestoqueini.m80_data >= '{$dtInicial}'";
$aWhereParametros[] = "matestoqueini.m80_data <= '{$dtFinal}'";
$aWhereParametros[] = "b.m80_codigo is null";
$aWhereParametros[] = "matestoqueini.m80_codtipo = 7";

$sCampos  = "distinct m83_matestoqueini,";
$sCampos .= "         db_depart.coddepto as codigo_departamento_origem,";
$sCampos .= "         db_depart.coddepto    ||' - '|| db_depart.descrdepto as departamento_origem,";
$sCampos .= "         a.coddepto            ||' - '|| a.descrdepto as departamento_destino,";
$sCampos .= "         matmater.m60_codmater ||' - '|| matmater.m60_descr as material,";
$sCampos .= "         matestoqueinimei.m82_quant as quantidade,";
$sCampos .= "         matestoqueini.m80_data     as data_lancamento";

$oDaoMatEstoqueTransf   = db_utils::getDao('matestoquetransf');
$sSqlBuscaTransferencia = $oDaoMatEstoqueTransf->sql_query_inill(null,
                                                                 $sCampos,
                                                                 "m83_matestoqueini",
                                                                 implode(" and ", $aWhereParametros));

$rsBuscaTransferencia = $oDaoMatEstoqueTransf->sql_record($sSqlBuscaTransferencia);
$iTotalTransferencias = $oDaoMatEstoqueTransf->numrows;
if ($iTotalTransferencias == 0) {

  $sMsgErro  = "Não foram localizadas transferências pendentes de recebimento.";
  db_redireciona("db_erros.php?fechar=true&db_erro=$sMsgErro");
  exit;
}

$head2 = "Relatório de Transferências em Aberto";
$head4 = "Período: ".db_formatar($dtInicial, "d")." à ".db_formatar($dtFinal, "d");

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->AddPage();
$oPdf->SetFont('arial', '', 7);
$iAltura = 4;

/**
 * Organizo em um array as transferências por departamento
 */
$aTransferencias = array();
for ($iRowTransferencia = 0; $iRowTransferencia < $iTotalTransferencias; $iRowTransferencia++) {

  $oStdTransferencia = db_utils::fieldsMemory($rsBuscaTransferencia, $iRowTransferencia);
  $aTransferencias[$oStdTransferencia->codigo_departamento_origem][] = $oStdTransferencia;
}

/**
 * Percorro o array imprimindo o PDF.
 */
$iCodigoDepartamento = 0;
foreach ($aTransferencias as $iIndiceDepartamento => $aTransferencia) {

  foreach ($aTransferencia as $oTransferencia) {

    if ($oPdf->gety() > $oPdf->h - 30) {

      $oPdf->AddPage();
      $oPdf->SetFont('arial', 'B', 7);
      $oPdf->cell(192, 5, "Departamento de Origem: {$oTransferencia->departamento_origem}", 1, 1, "L", 1);
      montaCabecalho($oPdf, $iAltura);
    }

    if ($iCodigoDepartamento != $iIndiceDepartamento) {

      $iCodigoDepartamento = $iIndiceDepartamento;
      $oPdf->SetFont('arial', 'B', 7);
      $oPdf->cell(192, 5, "Departamento de Origem: {$oTransferencia->departamento_origem}", 1, 1, "L", 1);
      montaCabecalho($oPdf, $iAltura);
    }

    $sDepartamentoDestino = substr($oTransferencia->departamento_destino, 0, 45);
    $sDescricaoMaterial   = substr($oTransferencia->material            , 0, 45);

    $oPdf->cell(20, $iAltura, $oTransferencia->m83_matestoqueini,                 0, 0, "C", 0);
    $oPdf->cell(20, $iAltura, db_formatar($oTransferencia->data_lancamento, "d"), 0, 0, "C", 0);
    $oPdf->cell(66, $iAltura, $sDescricaoMaterial,                                0, 0, "L", 0);
    $oPdf->cell(66, $iAltura, $sDepartamentoDestino,                              0, 0, "L", 0);
    $oPdf->cell(20, $iAltura, $oTransferencia->quantidade,                        0, 1, "R", 0);

  }
}
$oPdf->output();

/**
 * Função que monta o cabeçalho
 * @param PDF $oPdf
 * @param integer $iAltura
 */
function montaCabecalho($oPdf, $iAltura) {

  $oPdf->cell(20, $iAltura, "Lançamento", 1, 0, "C", 1);
  $oPdf->cell(20, $iAltura, "Data", 1, 0, "C", 1);
  $oPdf->cell(66, $iAltura, "Material", 1, 0, "C", 1);
  $oPdf->cell(66, $iAltura, "Departamento Destino", 1, 0, "C", 1);
  $oPdf->cell(20, $iAltura, "Quantidade", 1, 1, "C", 1);
  $oPdf->SetFont('arial', '', 7);
}
?>