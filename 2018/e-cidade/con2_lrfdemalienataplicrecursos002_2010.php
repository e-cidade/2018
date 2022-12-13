<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('libs/db_libcontabilidade.php'));
require_once(modification('libs/db_liborcamento.php'));
require_once(modification('classes/db_db_config_classe.php'));
require_once(modification("dbforms/db_funcoes.php"));

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXIVRREO");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);

try {

  $oRelatorio = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_XI, $iAnoUsu, new Periodo($oGet->periodo));
  $oRelatorio->setInstituicoes($sInstituicoes);
  $oRelatorio->emitir();
} catch (Exception $oErro) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($oErro->getMessage()));
}
