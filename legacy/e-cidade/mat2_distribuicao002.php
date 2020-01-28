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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");

require_once(modification("fpdf151/PDFDocument.php"));

$oGet = db_utils::postMemory($_GET);

try {

  if (!isset($oGet->mes_final)) {
    throw new Exception("O campo mês da Competência Final não foi informado.");
  }

  if (!isset($oGet->ano_final)) {
    throw new Exception("O campo ano da Competência Final não foi informado.");
  }

  if (!isset($oGet->mes_inicial)) {
    throw new Exception("O campo mês da Competência Inicial não foi informado.");
  }

  if (!isset($oGet->ano_inicial)) {
    throw new Exception("O campo ano da Competência Inicial não foi informado.");
  }

  $oCompetenciaInicial = new DBCompetencia($oGet->ano_inicial, $oGet->mes_inicial);
  if ($oCompetenciaInicial->comparar(new DBCompetencia($oGet->ano_final, $oGet->mes_final),DBCompetencia::COMPARACAO_MAIOR)) {
    throw new Exception("O campo Competência Inicial deve ter uma data menor ou igual ao campo Competência Final.");
  };

  $iDiaFinal    = DBDate::getQuantidadeDiasMes($oGet->mes_final, $oGet->ano_final);
  $sDataInicial = "{$oGet->ano_inicial}-{$oGet->mes_inicial}-01";
  $sDataFinal   = "{$oGet->ano_final}-{$oGet->mes_final}-{$iDiaFinal}";

  $iInstituicao = db_getsession("DB_instit");

  $oRelatorioDistribuicao = new RelatorioDeDistribuicao(
    new DBDate($sDataInicial),
    new DBDate($sDataFinal),
    $iInstituicao,
    $oGet->distribuicao_zerada == RelatorioDeDistribuicao::OPCAO_EXIBIR_DISTRIBUICAO_ZERADA
  );

  $oRelatorioDistribuicao->setAgruparGrupoSubGrupo($oGet->agrupar_grupo_subgrupo == RelatorioDeDistribuicao::OPCAO_AGRUPAR_GRUPO_SUBGRUPO);
  $oRelatorioDistribuicao->setGruposSubgrupos($oGet->grupo_subgrupo);
  $oRelatorioDistribuicao->setDepartamentos($oGet->departamentos);
  $oRelatorioDistribuicao->setQuebrarPagina($oGet->quebra_pagina);
  $oRelatorioDistribuicao->emitir();
} catch (Exception $e) {
  db_redireciona("db_erros.php?db_erro=" . $e->getMessage());
}