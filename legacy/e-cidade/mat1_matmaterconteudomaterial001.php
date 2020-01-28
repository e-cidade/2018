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

db_postmemory($_POST);

$oDaoConteudoMaterial = new cl_matmaterconteudomaterial;
$db_opcao             = 1;
$db_botao             = true;
$sPosScripts          = "";

if (isset($incluir)) {

  $sMensagem = '';
  try {

    if (empty($m08_matmater)) {
      throw new ParameterException("Código do Medicamento deve ser preenchido.");
    }
    $sSqlValida = $oDaoConteudoMaterial->sql_query_file(null, "1", null, "m08_matmater = {$m08_matmater} ");
    $rsValida   = db_query($sSqlValida);

    $lMaterialIncluso = false;
    if ($rsValida && pg_num_rows($rsValida) > 0) {

      throw new BusinessException("Material {$m60_descr} já possui conteúdo cadastrado.");
    }

    db_inicio_transacao();
    $oDaoConteudoMaterial->incluir(null);
    if ($oDaoConteudoMaterial->erro_status == '0') {
      throw new BusinessException($oDaoConteudoMaterial->erro_msg);
    }
    db_fim_transacao(false);
    $sMensagem = $oDaoConteudoMaterial->erro_msg;
  } catch (Exception $oErro) {

    $sMensagem                         = $oErro->getMessage();
    $oDaoConteudoMaterial->erro_status = true;
    db_fim_transacao(true);

  }
  $sPosScripts .= 'alert("' .$sMensagem . '");' . "\n";

    if ($oDaoConteudoMaterial->erro_status == '0') {

     $db_botao = true;
     $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoConteudoMaterial->erro_campo != "") {

       $sPosScripts .= "document.form1.{$oDaoConteudoMaterial->erro_campo}.classList.add('form-error');\n";
       $sPosScripts .= "document.form1.{$oDaoConteudoMaterial->erro_campo}.focus();\n";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
}


$sPosScripts .=  'js_tabulacaoforms("form1", "m08_matmater", true, 1, "m08_matmater", true);';
include("forms/db_frmmatmaterconteudomaterial.php");

