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
require_once "libs/db_liborcamento.php";
require_once "libs/db_libcontabilidade.php";
require_once "dbforms/db_funcoes.php";

$oGet = db_utils::postMemory($_GET);

try {

  if (empty($oGet->periodo)) {
    throw new Exception("Periodo não informado.");
  }

  $aInstituicoes = InstituicaoRepository::getInstituicoesPorTipo( array(5,6) );

  $aCodigos = array_map(function($oInstiuicao) {
    return $oInstiuicao->getCodigo();
  }, $aInstituicoes);

  $sInstituicoes = implode(',', $aCodigos);

  if (empty($sInstituicoes)) {
    $sInstituicoes = "0";
  }

  $oRelatorio = new AnexoIVDemonstrativoRPPS( db_getsession("DB_anousu"), AnexoIVDemonstrativoRPPS::CODIGO_RELATORIO, $oGet->periodo );
  $oRelatorio->setInstituicoes($sInstituicoes);

  $oRelatorio->emitir();

} catch (Exception $e) {
  db_redireciona("db_erros.php?db_erro=".$e->getMessage());
}
