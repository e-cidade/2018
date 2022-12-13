<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
require(modification("libs/db_utils.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));
require_once(modification("fpdf151/PDFDocument.php"));
require_once(modification("fpdf151/PDFTable.php"));
require_once modification("fpdf151/assinatura.php");

$oGet = db_utils::postMemory($_GET);

if (empty($oGet->periodo)) {
   throw new Exception("Per�odo n�o informado.");
}

$o    = new AnexoVIResultadoPrimario(db_getsession("DB_anousu"), AnexoVIResultadoPrimario::CODIGO_RELATORIO, $oGet->periodo);
$o->setInstituicoes(str_replace('-', ',', $oGet->db_selinstit));
$o->emitir();