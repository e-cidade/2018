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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oAguaIsencaoTipo = new cl_aguaisencaotipo;
$db_botao = false;
$db_opcao = 33;

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $oAguaIsencaoTipo->excluir($x29_codisencaotipo);
  db_fim_transacao();

} else if (isset($chavepesquisa)) {

  $db_opcao = 3;
  $sSql     = $oAguaIsencaoTipo->sql_query($chavepesquisa);
  $rsDados  = $oAguaIsencaoTipo->sql_record($sSql);
  if (!$rsDados || pg_num_rows($rsDados) === 0) {
    db_redireciona("db_erros.php?db_erro=" . urlencode("Não foi possível encontrar o Tipo de Isenção ({$chavepesquisa})."));
  }

  db_fieldsmemory($rsDados, 0);
  $db_botao = true;
}

$oAguaIsencaoTipo->rotulo->label();
require_once (modification("forms/db_frmaguaisencaotipo.php"));

if (isset($excluir)) {

  if($oAguaIsencaoTipo->erro_status == "0") {
    $oAguaIsencaoTipo->erro(true, false);
  } else {
    $oAguaIsencaoTipo->erro(true, true);
  }
}

if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
