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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );

$clunidades          = new cl_unidades;
$clagendamentos      = new cl_agendamentos;
$clundmedhorario     = new cl_undmedhorario_ext;
$clsau_config        = new cl_sau_config_ext;
$clsau_upsparalisada = new cl_sau_upsparalisada_ext;

$res_sau_config  = db_query( $clsau_config->sql_query_ext() );
$booProced       = pg_num_rows( $res_sau_config ) > 0 && pg_result($res_sau_config, 0, "s103_c_agendaproc") == "S";

$sd02_c_centralagenda = "N";
$upssolicitante       = db_getsession("DB_coddepto");
$sSqlUnidades         = $clunidades->sql_query($upssolicitante, "sd02_c_centralagenda,descrdepto", null, "");
$result_unidades      = $clunidades->sql_record( $sSqlUnidades );

$oAgendaParametros = loadConfig('sau_parametrosagendamento');

if ($oAgendaParametros != null) {
  $s165_formatocomprovanteagend = $oAgendaParametros->s165_formatocomprovanteagend;
}

if( $clunidades->numrows != 0 ) {
	db_fieldsmemory($result_unidades, 0);
}

if( isset( $chave_diasemana ) && $chave_diasemana != "" ) {

  $sWhereUndMedHorario = "sd30_i_codigo = {$sd30_i_codigo} and sd30_i_diasemana = {$chave_diasemana} ";
  $sSqlUndMedHorario   = $clundmedhorario->sql_query_ext("", "*", "", $sWhereUndMedHorario);
	$result              = $clundmedhorario->sql_record( $sSqlUndMedHorario );

	if( $clundmedhorario->numrows == 0 ) {
		db_msgbox("Profissional não possui agendamento.");
	} else {

		db_fieldsmemory( $result, 0 );
		$agendados = true;
	}
}

$oDataAtual     = new DBDate( date('Y-m-d') );
$db_opcao_cotas = 1;
$oResult        = getCotasAgendamento($upssolicitante, null, null, $oDataAtual->getAno(), $oDataAtual->getMes());

if ($oResult->lStatus != 1) {

  $sd02_i_codigo = $upssolicitante;
  $db_opcao_cotas = 3;
} else {

  $sd02_i_codigo  = "";
  $descrdepto     = "";
}

$db_opcao = 1;
?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load(array(
    "scripts.js",
    "prototype.js",
    "datagrid.widget.js",
    "strings.js",
    "grid.style.css",
    "estilos.css",
    "/widgets/dbautocomplete.widget.js",
  ));
  ?>
</head>
<body>

  <div class="container">
    <?php
    db_menu();
    try {
      new \UnidadeProntoSocorro(db_getsession("DB_coddepto"));
      include(modification("forms/db_frmagendamento.php"));
    } catch(\Exception $e) {
      die("<div class='container'><h2>{$e->getMessage()}</h2></div>");
    }
    ?>
  </div>
  <script>
    js_tabulacaoforms("form1","sd02_i_codigo",true,1,"sd02_i_codigo",true);
  </script>
</body>
</html>
