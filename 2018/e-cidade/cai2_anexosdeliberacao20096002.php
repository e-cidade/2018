<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
 * @author $Author: dbigor.cemim $
 * @version $Revision: 1.2 $
 */

require_once "fpdf151/scpdf.php";
require_once "libs/db_libdocumento.php";
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";

/**
 * Quebra a pagina adicionando o numero de paginas
 * @param FPDF $oPdf
 * @param integer $iAltLinha
 */
function quebraPagina($oPdf, $iAltLinha) {

  $oPdf->AddPage();
  return true;
}


$oGet = db_utils::postMemory($_GET);

if (empty($oGet->iConta)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao passar o parametro Conta.');
}

if (empty($oGet->anexo)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao passar o parametro Anexo.');
}

if (empty($oGet->iMes)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao passar o parâmetro Mês.');
}

if (empty($oGet->iAno)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao passar o parâmetro Ano.');
}

$iConta       = $oGet->iConta;
$iTipoAnexo   = $oGet->anexo;
$iMes         = $oGet->iMes;
$iAno         = $oGet->iAno;
$iInstituicao = db_getsession("DB_instit");

$oCompetencia   = new DBCompetencia($iAno, $iMes);
$oContaBancaria = new ContaBancaria($iConta);
$oInstituicao   = new Instituicao($iInstituicao);

$oRelatorio = new RelatorioAnexoDeliberacao($iTipoAnexo, $oContaBancaria, $oCompetencia);
$oRelatorio->setInstituicao($oInstituicao);
$oRelatorio->processar();