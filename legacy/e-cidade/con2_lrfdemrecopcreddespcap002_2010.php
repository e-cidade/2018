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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("libs/db_liborcamento.php");


$oGet = db_utils::postMemory($_GET);
$iAno = db_getsession('DB_anousu');

try {

  if (!isset($oGet->periodo) || empty($oGet->periodo) || !is_numeric($oGet->periodo)) {
    throw new ParameterException('Campo Período é de preenchimento obrigatório.');
  }

  if (!isset($oGet->db_selinstit) || empty($oGet->db_selinstit)) {
    throw new ParameterException('Campo Instituições é de preenchimento obrigatório.');
  }

  $aInstituicoes = explode(',', $oGet->db_selinstit);

  foreach($aInstituicoes as $iInstituicao) {

    if(!is_numeric($iInstituicao)) {
      throw new ParameterException('Campo Instituições é de preenchimento obrigatório.');
    }
  }

  $oPeriodo = new Periodo($oGet->periodo);

  $oRelatorio = AnexoRREOFactory::getAnexoRREO(AnexoRREOFactory::ANEXO_IX, $iAno, $oPeriodo);
  $oRelatorio->setInstituicoes($oGet->db_selinstit);
  $oRelatorio->emitir();

} catch (Exception $e) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($e->getMessage()));
}