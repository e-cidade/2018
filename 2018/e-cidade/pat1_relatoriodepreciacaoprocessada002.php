<?php
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_benshistoricocalculo_classe.php");
$oGet = db_utils::postMemory($_GET);

$iInstituicaoSessao   = db_getsession("DB_instit");
$oDaoHistoricoCalculo = new cl_benshistoricocalculo();
$sCamposHistorico     = "db_usuarios.id_usuario, db_usuarios.login, ";
$sCamposHistorico    .= "t57_mes, t57_datacalculo, t57_processado, t57_ativo, ";
$sCamposHistorico    .= "case when t57_tipoprocessamento = 1 ";
$sCamposHistorico    .= "     then 'Automático' else 'Manual' end as tipoprocessamento ";
$sWhereHistorico      = "     t57_ano = {$oGet->iAno}";
$sWhereHistorico     .= " and t57_tipocalculo = 1";
$sWhereHistorico     .= " and t57_instituicao = {$iInstituicaoSessao}";
$sOrderHistorico      = "t57_mes, t57_sequencial";
$sSqlHistoricoCalculo = $oDaoHistoricoCalculo->sql_query(null, $sCamposHistorico, $sOrderHistorico, $sWhereHistorico);
$rsHistoricoCalculo   = $oDaoHistoricoCalculo->sql_record($sSqlHistoricoCalculo);

if ($oDaoHistoricoCalculo->numrows == 0) {

  $oVariaveis      = new stdClass();
  $oVariaveis->iAno = $oGet->iAno;
  $sMsg = _M('patrimonial.patrimonio.pat1_relatoriodepreciacaoprocessada002.nenhum_calculo_encontrado', $oVariaveis);
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}

$aCalculosEncontrados = db_utils::getCollectionByRecord($rsHistoricoCalculo);

$head2 = "Relatório de Depreciações Processadas";
$head3 = "Ano: {$oGet->iAno}";
$oPdf  = new PDF();
$oPdf->Open();
$oPdf->addPage();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$iAltura       = 4;
$lPrimeiroLaco = true;

$aMesesParaImprimir     = array("1"  => "Janeiro",
              						  		"2"  => "Fevereiro",
              						  		"3"  => "Março",
              						  		"4"  => "Abril",
              						  		"5"  => "Maio",
              						  		"6"  => "Junho",
              						  		"7"  => "Julho",
              						  		"8"  => "Agosto",
                                "9"  => "Setembro",
                                "10" => "Outubro",
                                "11" => "Novembro",
                              	"12" => "Dezembro");
$aMesesComProcessamento = array();
foreach ($aCalculosEncontrados as $oRegistro) {

  $oProcessamento                            = new stdClass();
  $oProcessamento->dataprocessamento         = implode("/", array_reverse(explode("-", $oRegistro->t57_datacalculo)));
  $oProcessamento->usuario                   = $oRegistro->id_usuario ." - ".$oRegistro->login;
  $oProcessamento->mes                       = $aMesesParaImprimir[$oRegistro->t57_mes];
  $oProcessamento->tipodepreciacao           = $oRegistro->tipoprocessamento;
  $oProcessamento->situacao                  = $oRegistro->t57_processado == "f" ? "Desprocessado" : "Processado";
  $aMesesComProcessamento[$oRegistro->t57_mes][] = $oProcessamento;

}

/**
 * Percorremos o array de retorno para imprimir os registros no PDF.
 */
foreach($aMesesParaImprimir as $iMes => $sNomeMes) {

  if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiroLaco) {

    imprimeCabecalho($oPdf, $iAltura);
    $lPrimeiroLaco = false;
  }

  $oPdf->setfont("arial", "", 8);
  $oPdf->cell(38, $iAltura, "{$sNomeMes}", 0, 0, "L", 0);
  if (!isset($aMesesComProcessamento[$iMes])) {

    $oPdf->cell(38, $iAltura, '', 0, 0, "C", 0);
    $oPdf->cell(38, $iAltura, '', 0, 0, "L", 0);
    $oPdf->cell(38, $iAltura, 'Não Processado', 0, 0, "L", 0);
    $oPdf->cell(38, $iAltura, '', 0, 1, "L", 0);
    continue;
  }

  $iProcessamentosNoMes = 0;
  foreach ($aMesesComProcessamento[$iMes] as $oRegistro) {

    if ($iProcessamentosNoMes > 0) {
      $oPdf->cell(38, $iAltura, "", 0, 0, "L", 0);
    }
    $oPdf->cell(38, $iAltura, $oRegistro->dataprocessamento, 0, 0, "C", 0);
    $oPdf->cell(38, $iAltura, $oRegistro->tipodepreciacao, 0, 0, "L", 0);
    $oPdf->cell(38, $iAltura, $oRegistro->situacao, 0, 0, "L", 0);
    $oPdf->cell(38, $iAltura, $oRegistro->usuario,  0, 1, "L", 0);
    $iProcessamentosNoMes++;
  }
}
$oPdf->Output();
/**
 * Função para imprimir o cabecalho
 * @param FPDF $oPdf
 * @param integer $iAltura
 */
function imprimeCabecalho($oPdf, $iAltura) {

  $oPdf->setfont("arial", "b", 8);
  $oPdf->cell(38, $iAltura, "Mês", 1, 0, "C", 1);
  $oPdf->cell(38, $iAltura, "Data", 1, 0, "C", 1);
  $oPdf->cell(38, $iAltura, "Tipo de Depreciação", 1, 0, "C", 1);
  $oPdf->cell(38, $iAltura, "Situação", 1, 0, "C", 1);
  $oPdf->cell(38, $iAltura, "Usuário", 1, 1, "C", 1);
}