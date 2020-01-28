<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
 *  $Id: pes1_faixavaloresirrf002.php,v 1.3 2016/01/22 12:20:25 dbrenan Exp $
 */
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");
define("M", "recursoshumanos.pessoal.pes1_faixavaloresirrf.");

$oGet      = db_utils::postMemory($_GET);
$oPost     = db_utils::postMemory($_POST);
$db_opcao  = 2;

$sFonteRedireciona  = "pes1_faixavaloresirrf002.php";

if (!isset($oGet->db149_sequencial)) {
  $db_opcao  = 3;
}

require_once("forms/db_frmfaixavaloresirrf.php");

try {

  if (isset($oPost->db149_descricao)) {

    $oDaoTabelaValores = new cl_db_tabelavalores();
    $oDaoTabelaValores->db149_sequencial = $oPost->db149_sequencial;
    $oDaoTabelaValores->db149_descricao  = $oPost->db149_descricao;
    $oDaoTabelaValores->alterar($oPost->db149_sequencial);

    if ($oDaoTabelaValores->erro_status == '0') {
      throw new DBException(_M(M.'erro_alterar_tabela'));
    }

    db_msgbox(_M(M.'tabela_alterada'));
  }

} catch (Exception $oException) {
  db_msgbox($oException->getMessage());
}
