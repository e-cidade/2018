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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matmaterconteudomaterial_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$oDaoConteudoMaterial = new cl_matmaterconteudomaterial;
$db_opcao             = 22;
$db_botao             = false;
$sPosScripts          = "";

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoConteudoMaterial->alterar($m08_codigo);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoConteudoMaterial->erro_msg . '");' . "\n";

  if ($oDaoConteudoMaterial->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoConteudoMaterial->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoConteudoMaterial->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoConteudoMaterial->erro_campo}.focus();";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
} else if(isset($chavepesquisa)) {

  $sCampos  = " m08_codigo,   ";
  $sCampos .= " m08_matmater, ";
  $sCampos .= " m60_descr,    ";
  $sCampos .= " a.m61_descr            as m08_unidade_material, ";
  $sCampos .= " matunid.m61_codmatunid as m08_unidade,          ";
  $sCampos .= " m08_quantidade ";

  $db_opcao = 2;
  $db_botao = true;
  $result   = $oDaoConteudoMaterial->sql_record( $oDaoConteudoMaterial->sql_query($chavepesquisa, $sCampos) );

  db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "m08_matmater", true, 1, "m08_matmater", true);';

include("forms/db_frmmatmaterconteudomaterial.php");
?>
