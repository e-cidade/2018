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

ini_set('memory_limit', -1);

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

try {

  if (empty($oGet->iAgrupamento)) {
    throw new ParameterException("Tipo de Agrupamento é de preenchimento obrigatório.");
  }

  if (empty($oGet->sDataInicial)) {
    throw new ParameterException("Data Inicial do Período é de preenchimento obrigatório.");
  }

  if (empty($oGet->sDataFinal)) {
    throw new ParameterException("Data Final do Período é de preenchimento obrigatório.");
  }

  if (!empty($oGet->iReduzido) && !Check::isInt($oGet->iReduzido)) {
    throw new ParameterException("O Campo Conta Contábil deve ser um número inteiro válido.");
  }

  $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
  $oDataInicial = new DBDate($oGet->sDataInicial);
  $oDataFinal   = new DBDate($oGet->sDataFinal);
  $iAgrupamento = $oGet->iAgrupamento;
  $oRelatorio = new RelatorioDisponibilidadeFinanceira($oInstituicao, $oDataInicial, $oDataFinal, $oGet->iAgrupamento);

  if ($oDataInicial->getTimeStamp() > $oDataFinal->getTimeStamp()) {
    throw new ParameterException("A Data Final do Período deve ser maior ou igual a Data Inicial do mesmo.");
  }

  if (!empty($oGet->sRecursos)) {
    $oRelatorio->setRecursos(explode(",", $oGet->sRecursos));
  }

  if (!empty($oGet->iReduzido)) {
    $oRelatorio->setReduzido($oGet->iReduzido);
  }

  if (!empty($oGet->iMostrarLancamentos)) {
    $oRelatorio->setMostrarLancamentos($oGet->iMostrarLancamentos);
  }

  $oRelatorio->emitir();
} catch (Exception $e) {
  db_redireciona("db_erros.php?db_erro=" . urlencode($e->getMessage()));
}