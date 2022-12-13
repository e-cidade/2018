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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "fpdf151/PDFDocument.php";
require_once "libs/db_libdocumento.php";

$oGet = db_utils::postMemory($_GET);

if (empty($oGet->exercicio)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Exerc�cio n�o informado.');
}

if (empty($oGet->tipo)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Tipo n�o informado.');
}

try {

  $oRelatorioRestosPagar = new RelatorioRelacaoRestosPagar($oGet->tipo, $oGet->exercicio);

  $oRelatorioRestosPagar->setInstituicao( new Instituicao(db_getsession("DB_instit")) );
  $oRelatorioRestosPagar->gerar();

} catch(Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $e->getMessage());
}
?>