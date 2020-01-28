<?php
/**
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_db_configarquivos_classe.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cldb_config         = new cl_db_config;
$cldb_configarquivos = new cl_db_configarquivos;

if (isset($oPost->btnSalvar)) {

  $lErro = false;

  db_inicio_transacao();

   /*
    * tratamento para imagem MARCADAGUA
    * localiza o arquivo salva no banco e move da pasta tmp para a pasta imagens
    */
  if (isset($oPost->namefilemarcadagua) && !empty($oPost->namefilemarcadagua)) {

    $sMarcadaguaName = 'marcadagua'.$_FILES['uploadfilemarcadagua']['name'];
    try {

	    /**
	     * Verifica o tipo de arquivo.
	     */
    	if (!empty($_FILES['uploadfilemarcadagua']["type"])) {

	    	$sMimeType = $_FILES['uploadfilemarcadagua']["type"];
	      if ($sMimeType != 'image/jpeg' && $sMimeType != 'image/png') {
	        throw new Exception("Arquivo inválido!");
	      }

	    	/*
	    	 * verifica se ja existe uma imagem.
	    	 */
		    if (isset($oPost->db21_imgmarcadaguaold) && !empty($oPost->db21_imgmarcadaguaold)) {
		      db_geraArquivoOid(null, $oPost->db21_imgmarcadaguaold, 3, $conn);
		    }

	      unlink($oPost->namefilemarcadagua);
	      $iOidGravaMarcadagua             = db_geraArquivoOid("uploadfilemarcadagua", null, 1, $conn);
	      $cldb_config->db21_imgmarcadagua = $iOidGravaMarcadagua;
    	}
    } catch (Exception $ex) {

      $lErro = true;
      $cldb_config->erro_msg = $ex->getMessage();
    }
  }

  /*
   * tratamento para imagem LOGO
   * localiza o arquivo salva no banco e move da pasta tmp para a pasta imagens
   */
  if (isset($oPost->namefilelogo) && !empty($oPost->namefilelogo)) {

    $sLogoName = 'logo'.$_FILES['uploadfilelogo']['name'];
    try {
    	/**
       * Verifica o tipo de arquivo.
       */
      if (!empty($_FILES['uploadfilelogo']["type"])) {

	      $sMimeType = $_FILES['uploadfilelogo']["type"];
	      if ($sMimeType != 'image/jpeg' && $sMimeType != 'image/png') {
	        throw new Exception("Arquivo inválido!");
	      }

	      $iOidGravaLogo = db_geraArquivoOid ("uploadfilelogo", null, 1, $conn);
	      $sTmpFile      = $_FILES['uploadfilelogo']["tmp_name"];
	      $sDestino      = "imagens/files/".$sLogoName;
          $sDestinoAgata = "imagens/files/agata" . $sLogoName;

          if (!copy($sTmpFile, $sDestinoAgata) || !move_uploaded_file($sTmpFile, $sDestino)) {
	        throw new Exception("Erro ao mover arquivo {$oPost->namefilelogo}!");
	      }

	      unlink($oPost->namefilelogo);
	      $cldb_config->logo = $sLogoName;
      }
    } catch (Exception $ex) {

    	$lErro = true;
      $cldb_config->erro_msg = $ex->getMessage();
    }
  }

   /*
   *tratamento para imagem FIGURA
   * localiza o arquivo salva no banco e move da pasta tmp para a pasta imagens
   */
  if (isset($oPost->namefilefigura) && !empty($oPost->namefilefigura)) {

    $sFiguraName = 'figura'.$_FILES['uploadfilefigura']['name'];
    try {

      /**
       * Verifica o tipo de arquivo.
       */
      if (!empty($_FILES['uploadfilefigura']["type"])) {

        $sMimeType = $_FILES['uploadfilefigura']["type"];
        if ($sMimeType != 'image/jpeg' && $sMimeType != 'image/png') {
          throw new Exception("Arquivo inválido!");
        }

	      $iOidGravaFigura = db_geraArquivoOid ("uploadfilefigura", null, 1, $conn);
	      $sTmpFile      = $_FILES['uploadfilefigura']["tmp_name"];
	      $sDestino      = "imagens/files/".$sFiguraName;

	      if (!move_uploaded_file($sTmpFile, $sDestino)) {
	        throw new Exception("Erro ao mover arquivo {$oPost->namefilefigura}!");
	      }

	      unlink($oPost->namefilefigura);
	      $cldb_config->figura = $sFiguraName;
      }
    } catch (Exception $ex) {

      $lErro = true;
      $cldb_config->erro_msg = $ex->getMessage();
    }
  }

  if (!$lErro) {

    $cldb_config->alterar($oPost->codigo);
    if ($cldb_config->erro_status == 0) {
      $lErro = true;
    }

    if (!$lErro) {

      if (isset($iOidGravaLogo) && !empty($iOidGravaLogo)) {

	      $sWhere               = "db38_instit = {$oPost->codigo} and db38_tipo = 1";
	      $sSqlDbConfigArquivos = $cldb_configarquivos->sql_query_file(null, "*", null, $sWhere);
	      $rsDbConfigArquivos   = $cldb_configarquivos->sql_record($sSqlDbConfigArquivos);

	      if ($cldb_configarquivos->numrows > 0) {

	        $oConfigArquivos = db_utils::fieldsMemory($rsDbConfigArquivos, 0);
	        $cldb_configarquivos->excluir($oConfigArquivos->db38_sequencial);
	        if ( $cldb_configarquivos->erro_status == 0) {

	          $lErro                 = true;
	          $cldb_config->erro_msg = $cldb_configarquivos->erro_msg;
	        }
	      }

	      $cldb_configarquivos->db38_arquivo = $iOidGravaLogo;
	      $cldb_configarquivos->db38_instit  = $oPost->codigo;
	      $cldb_configarquivos->db38_tipo    = 1;
	      $cldb_configarquivos->incluir(null);
	      if ($cldb_configarquivos->erro_status == 0) {

	        $lErro                 = true;
	        $cldb_config->erro_msg = $cldb_configarquivos->erro_msg;
	      }
      }
    }

    if (!$lErro) {

      if (isset($iOidGravaFigura) && !empty($iOidGravaFigura)) {

	      $sWhere               = "db38_instit = {$oPost->codigo} and db38_tipo = 2";
	      $sSqlDbConfigArquivos = $cldb_configarquivos->sql_query_file(null, "*", null, $sWhere);
	      $rsDbConfigArquivos   = $cldb_configarquivos->sql_record($sSqlDbConfigArquivos);

	      if ($cldb_configarquivos->numrows > 0) {

	        $oConfigArquivos = db_utils::fieldsMemory($rsDbConfigArquivos, 0);
	        $cldb_configarquivos->excluir($oConfigArquivos->db38_sequencial);

	        if ( $cldb_configarquivos->erro_status == 0) {

	          $lErro                 = true;
	          $cldb_config->erro_msg = $cldb_configarquivos->erro_msg;
	        }
	      }

	      $cldb_configarquivos->db38_arquivo = $iOidGravaFigura;
	      $cldb_configarquivos->db38_instit  = $oPost->codigo;
	      $cldb_configarquivos->db38_tipo    = 2;
	      $cldb_configarquivos->incluir(null);
	      if ($cldb_configarquivos->erro_status == 0) {

	        $lErro                 = true;
	        $cldb_config->erro_msg = $cldb_configarquivos->erro_msg;
	      }
      }
    }
  }

  db_fim_transacao($lErro);
}

if (isset($oGet->codigo)) {

   $db_opcao              = 2;
   $db_botao              = true;

   $sSrcPreviewLogo       = '';
   $sSrcPreviewFigura     = '';
   $sSrcPreviewMarcaDAgua = '';
   $db21_imgmarcadaguaold = '';

   $sCampos               = "logo, figura, db21_imgmarcadagua";
   $sWhere                = "codigo = {$oGet->codigo}";
   $sSqlDbConfig          = $cldb_config->sql_query_file(null, $sCampos, null, $sWhere);
   $rsSqlDbConfig         = $cldb_config->sql_record($sSqlDbConfig);
   if ($cldb_config->numrows > 0) {
     db_fieldsmemory($result, 0);

     $oDbConfig = db_utils::fieldsMemory($rsSqlDbConfig, 0);

     if (!empty($oDbConfig->logo)) {
       $sSrcPreviewLogo = "imagens/files/".$oDbConfig->logo;
     }

     if (!empty($oDbConfig->figura)) {
       $sSrcPreviewFigura = "imagens/files/".$oDbConfig->figura;
     }

     if (!empty($oDbConfig->db21_imgmarcadagua)) {
     	 $db21_imgmarcadaguaold = $oDbConfig->db21_imgmarcadagua;
     }
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css,grid.style.css");
?>
<style>
td {
  white-space: nowrap;
}

.formgeral fieldset table table td:first-child {
  width: 220px;
  white-space: nowrap;
}
</style>
</head>
<body class="body-default" onload="js_previewMarcaDAgua();">
  <div class="container">
    <?php
      require_once(modification("forms/db_frmdb_imagens.php"));
    ?>
  </div>
</body>
</html>
<?php
if (isset($oPost->btnSalvar)) {
	db_msgbox($cldb_config->erro_msg);
}
?>
