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

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Repository\BalancoOrcamentario as BalancoOrcamentarioRepository;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("fpdf151/assinatura.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("fpdf151/PDFDocument.php");

$oGet                     = db_utils::postMemory($_GET);
$iAnoUsu                  = db_getsession("DB_anousu");
$iCodigoPeriodo           = $oGet->periodo;
$iCodigoRelatorio         = $oGet->codrel;
$sListaInstituicoes       = $oGet->db_selinstit;
$oBalancoOrcamentarioRepo = BalancoOrcamentarioRepository::getInstance();
$aQuadros = array();

try {

  if (empty($iCodigoPeriodo)) {
    throw new Exception("Período não informado.");
  }

  if (empty($sListaInstituicoes)) {
    throw new Exception("Instituição não informada.");
  }

  if (empty($iCodigoRelatorio)) {
    throw new Exception("Código do relatório não informado.");
  }

  if (empty($oBalancoOrcamentarioRepo)) {
    throw new Exception("Erro ao criar relatório.");
  }

  $oBalancoFinanceiro = $oBalancoOrcamentarioRepo->getBalancoOrcamentario($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

  if (!empty($oGet->lQuadroPrincipal) && $oGet->lQuadroPrincipal === "true") {
    $aQuadros[] = $oBalancoFinanceiro::QUADRO_PRINCIPAL;
  }

  if (!empty($oGet->lQuadroRestosNaoProcessados) && $oGet->lQuadroRestosNaoProcessados === "true") {
    $aQuadros[] = $oBalancoFinanceiro::QUADRO_RESTOS_NAO_PROCESSADOS;
  }

  if (!empty($oGet->lQuadroRestosProcessadosLiquidados) && $oGet->lQuadroRestosProcessadosLiquidados === "true") {
    $aQuadros[] = $oBalancoFinanceiro::QUADRO_RESTOS_PROCESSADOS;
  }

  $oBalancoFinanceiro->setInstituicoes($sListaInstituicoes);
  $oBalancoFinanceiro->setExibirQuadros($aQuadros);
  $oBalancoFinanceiro->emitir();
} catch (Exception $e) {
  db_redireciona("db_erros.php?db_erro=" . $e->getMessage());
}

