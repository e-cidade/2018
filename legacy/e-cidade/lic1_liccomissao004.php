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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clliccomissao = new cl_liccomissao;
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

  db_inicio_transacao();
  $sqlerro = false;
  $erro_msg = '';

  $iErroArquivo = $_FILES['l30_arquivo']['error'];
//  $oSplFile = new File($_FILES['l30_arquivo']['name']);
//
//  $aExtensoesProibidas = array('exe', 'php', 'sh', 'bat', 'py');
//  if (in_array($oSplFile->getExtension(), $aExtensoesProibidas)) {
//
//    $sqlerro = true;
//    $erro_msg = 'Não foi possível fazer o envio do arquivo, extensão não permitida.';
//  }

  if ($iErroArquivo !== UPLOAD_ERR_OK && $iErroArquivo !== UPLOAD_ERR_NO_FILE) {

    $sqlerro = true;
    $erro_msg = 'Não foi possível fazer o envio do arquivo.';
  }

  if ($sqlerro == false && $iErroArquivo == UPLOAD_ERR_OK) {

    $iArquivo     = pg_lo_import($_FILES['l30_arquivo']['tmp_name']);
    $sNomeArquivo = File::cutName($_FILES['l30_arquivo']['name'], 100);

    $clliccomissao->l30_arquivo = $iArquivo;
    $clliccomissao->l30_nomearquivo = db_removeAcentuacao($sNomeArquivo);
  }

  if ($sqlerro == false) {

    $clliccomissao->incluir(null);

    $l30_codigo = $clliccomissao->l30_codigo;
    $erro_msg   = $clliccomissao->erro_msg;

    if ($clliccomissao->erro_status == 0) {
      $sqlerro = true;
    }
  }

  $db_opcao = 1;
  $db_botao = true;

  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <?php include(modification("forms/db_frmliccomissao.php")); ?>
  </div>
</body>
</html>
<?php
if (isset($incluir)) {

  if ($sqlerro == true){

    db_msgbox($erro_msg);

    if ($clliccomissao->erro_campo != "") {
      echo "<script> document.form1.".$clliccomissao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clliccomissao->erro_campo.".focus();</script>";
    }
  } else {

   db_msgbox($erro_msg);
   db_redireciona("lic1_liccomissao005.php?liberaaba=true&chavepesquisa=$l30_codigo");
  }
}
