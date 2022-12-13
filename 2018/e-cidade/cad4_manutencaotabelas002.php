<?
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost     = db_utils::postMemory($_POST);
$oGet      = db_utils::postMemory($_GET);

$db_botao  = true;
$lSqlErro  = false;
$db_opcao  = 1;

if (isset($oPost->j121_codarq)) {
	$j121_codarq = $oPost->j121_codarq;
} else {
	$j121_codarq = $oGet->j121_codarq;
}

/**
 * Consulta propriedades dos campos da tabela selecionada
 */
$sSqlConsulta          = " select distinct db_sysarqcamp.*,                                                  ";
$sSqlConsulta         .= "        db_sysarquivo.nomearq,                                                     ";
$sSqlConsulta         .= "        db_sysarquivo.sigla,                                                       ";
$sSqlConsulta         .= "        db_syscampo.tamanho,                                                       ";
$sSqlConsulta         .= "        db_syscampo.nomecam,                                                       ";
$sSqlConsulta         .= "        db_syscampo.conteudo,                                                      ";
$sSqlConsulta         .= "        case                                                                       ";
$sSqlConsulta         .= "          when db_sysprikey.sequen is not null and db_sysarqcamp.codsequencia != 0 ";
$sSqlConsulta         .= "            then '3'                                                               ";
$sSqlConsulta         .= "            else '1'                                                               ";
$sSqlConsulta         .= "          end as db_opcao,                                                         ";
$sSqlConsulta         .= "        case                                                                       ";
$sSqlConsulta         .= "          when db_sysforkey.referen is not null                                    ";
$sSqlConsulta         .= "            then db_sysforkey.referen                                              ";
$sSqlConsulta         .= "            else 0                                                                 ";
$sSqlConsulta         .= "          end as tabela_referencia                                                 ";
$sSqlConsulta         .= "   from db_sysarqcamp                                                              ";
$sSqlConsulta         .= "        inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq    ";
$sSqlConsulta         .= "        inner join db_syscampo   on db_syscampo.codcam   = db_sysarqcamp.codcam    ";
$sSqlConsulta         .= "        left  join db_sysprikey  on db_sysprikey.codarq  = db_sysarqcamp.codarq    ";
$sSqlConsulta         .= "                                and db_sysprikey.codcam  = db_sysarqcamp.codcam    ";
$sSqlConsulta         .= "        left  join db_sysforkey  on db_sysforkey.codarq  = db_sysarqcamp.codarq    ";
$sSqlConsulta         .= "                                and db_sysforkey.codcam  = db_sysarqcamp.codcam    ";
$sSqlConsulta         .= "  where db_sysarqcamp.codarq = {$j121_codarq}                                      ";
$sSqlConsulta         .= "  order by db_sysarqcamp.seqarq                                                    ";
$rsConsulta            = db_query($sSqlConsulta);
$iNumRows              = pg_num_rows($rsConsulta);

$aDadoManutensaoTabela = array();
for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

  $oDado                 = db_utils::fieldsMemory($rsConsulta, $iInd);
  $oDadoManutensaoTabela = new stdClass();
  $oDadoManutensaoTabela->sNomeTabela   = $oDado->nomearq;
  $oDadoManutensaoTabela->sSigla        = $oDado->sigla;
  $oDadoManutensaoTabela->iTamanhoCampo = $oDado->tamanho;
  $oDadoManutensaoTabela->sNomeCampo    = $oDado->nomecam;
  $oDadoManutensaoTabela->sTipoCampo    = $oDado->conteudo;
  $oDadoManutensaoTabela->iOpcao        = $oDado->db_opcao;
  $oDadoManutensaoTabela->sReferencia   = $oDado->tabela_referencia;
  $aDadoManutensaoTabela[]              = $oDadoManutensaoTabela;

  $oDaoTabela = db_utils::getDao($oDadoManutensaoTabela->sNomeTabela);
  $oDaoTabela->rotulo->label($oDadoManutensaoTabela->sNomeCampo);
}

$sSqlPriKey   = "    select nomecam                                                                 ";
$sSqlPriKey  .= "      from db_sysprikey                                                            ";
$sSqlPriKey  .= "           inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam      ";
$sSqlPriKey  .= "     where db_sysprikey.codarq = {$j121_codarq}                                    ";
$sSqlPriKey  .= "  order by db_sysprikey.sequen                                                     ";

$rsPriKey     = db_query($sSqlPriKey);
$iLinhas      = pg_num_rows($rsPriKey);

$aPrikey      = array();
$aChave       = array();
$aPrikeyParam = array();
$aPrikeyChave = array();
for ($iInd = 0; $iInd < $iLinhas; $iInd++ ) {

  $oCampo          = db_utils::fieldsMemory($rsPriKey,$iInd);
  $aPrikey[]       = $oCampo->nomecam;
  $aChave[]        = "chave".$iInd;
  $aPrikeyParam[]  = '$oPost->'.$oCampo->nomecam;
  $aPrikeyChave[]  = "chavepesquisa".$iInd."='+chave".$iInd."+'";
}

$sListaPriKeyLockup   = implode("|",$aPrikey);
$sListaPriKeyChave    = implode("&",$aPrikeyChave);
$sListaPriKeyParam    = implode(",",$aPrikeyParam);
$sListaChave          = implode(",",$aChave);

if (isset($oPost->incluir)) {

  db_inicio_transacao();

  eval('$oDaoTabela->incluir('.$sListaPriKeyParam.');');

	$sMsg = $oDaoTabela->erro_msg;
	if ($oDaoTabela->erro_status == "0") {
		$lSqlErro = true;
	} else {

		$db_opcao = 22;
		$db_botao = false;
	  foreach ( $aDadoManutensaoTabela as $iInd => $sChave ) {
      eval('$'.$sChave->sNomeCampo.' = $oDaoTabela->'.$sChave->sNomeCampo.';');
    }
	}

	db_fim_transacao($lSqlErro);

} else if (isset($oPost->alterar)) {

  db_inicio_transacao();

  eval('$oDaoTabela->alterar('.$sListaPriKeyParam.');');

  $sMsg = $oDaoTabela->erro_msg;
  if ($oDaoTabela->erro_status == "0") {
    $lSqlErro = true;
  } else {

  	$db_opcao = 22;
  	$db_botao = false;
    foreach ( $aDadoManutensaoTabela as $iInd => $sChave ) {
      eval('$'.$sChave->sNomeCampo.' = $oDaoTabela->'.$sChave->sNomeCampo.';');
    }
  }

  db_fim_transacao($lSqlErro);
} else if (isset($oPost->excluir)) {

  db_inicio_transacao();

  eval('$oDaoTabela->excluir('.$sListaPriKeyParam.');');

  $sMsg = $oDaoTabela->erro_msg;
  if ($oDaoTabela->erro_status == "0") {
    $lSqlErro = true;
  } else {

    $db_opcao = 33;
    $db_botao = false;
    foreach ( $aDadoManutensaoTabela as $iInd => $sChave ) {
      eval("$".$sChave->sNomeCampo." = '';");
    }
  }

  db_fim_transacao($lSqlErro);
}

if (isset($oGet->chavepesquisa0)) {

  $db_botao        = false;

	$sSqlPriKey      = " select * from db_sysprikey where codarq = {$j121_codarq} ";
	$rsPriKey        = db_query($sSqlPriKey);
	$iLinhas         = pg_num_rows($rsPriKey);

	$aPrikeyPesquisa = array();
	for ($iInd = 0; $iInd < $iLinhas; $iInd++ ) {
		$aPrikeyPesquisa[] = '$oGet->chavepesquisa'.$iInd;
	}

  $sListaPriKeyPesquisa = implode(",", $aPrikeyPesquisa);

  eval('$sSqlQuery = $oDaoTabela->sql_query_file('.$sListaPriKeyPesquisa.');');

	$rsSqlQuery = $oDaoTabela->sql_record($sSqlQuery);

	if ($oDaoTabela->numrows > 0) {
		db_fieldsmemory($rsSqlQuery,0);
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap;
}

.fildsetprincipal table td:first-child {
  width: 30%;
  white-space: nowrap;
}

<?
  foreach ($aDadoManutensaoTabela as $oManutensaoTabela) {

    if ($oManutensaoTabela->sTipoCampo == 'date') {
      echo ".tabelaprincipal input[id={$oManutensaoTabela->sNomeCampo}] {\n  width: 80%;\n }\n";
    }
  }
?>

.fildsetcampotextarea {
  width: 98%;
}

.fildsetcampotextarea a {
  -moz-user-select: none;
  cursor: pointer
}

.tabelatextarea textarea {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
        include(modification("forms/db_frmmanutencaotabelas.php"));
      ?>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($oPost->incluir) || isset($oPost->alterar) || isset($oPost->excluir)) {

	db_msgbox($sMsg);
  if ($oDaoTabela->erro_status == "0") {

    if ($oDaoTabela->erro_campo != "") {

      echo "<script> document.form1.".$oDaoTabela->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoTabela->erro_campo.".focus();</script>";
    }
  }

  if (isset($oPost->excluir)) {
  	db_redireciona("cad4_manutencaotabelas001.php");
  }
}

if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>