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
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitaedital_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clliclicita       = new cl_liclicita;
$clliclicitaedital = new cl_liclicitaedital;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if (isset($incluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();

    try {
      $nomearq = File::cutName($_FILES["arquivoedital"]["name"], 50);
    } catch (Exception $oErro) {

      $sqlerro = true;
      $erro_msg = 'O arquivo selecionado tem uma extensão inválida.';
    }

    if (!empty($_FILES["arquivoedital"]["name"])) {

      $oidgrava = db_geraArquivoOid("arquivoedital", "", 1, $conn);

      if ($sqlerro == false) {

        $clliclicitaedital->l27_arquivo   = $oidgrava;
        $clliclicitaedital->l27_arqnome   = trim($nomearq);
        $clliclicitaedital->l27_liclicita = $l20_codigo;
        $clliclicitaedital->incluir(null);

        $erro_msg = $clliclicitaedital->erro_msg;
        if ($clliclicitaedital->erro_status == 0) {
          $sqlerro = true;
        }
      }
    } else {

      $erro_msg = "Arquivo do edital não informado!";
      $sqlerro  = true;
    }


    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {

  if ($sqlerro == false) {

    db_inicio_transacao();

    $clliclicitaedital->excluir($l27_sequencial);
    $erro_msg = $clliclicitaedital->erro_msg;
    if ($clliclicitaedital->erro_status == 0) {
      $sqlerro = true;
    } else {

      try {
        $nomearq = File::cutName($_FILES["arquivoedital"]["name"], 50);
      } catch (Exception $oErro) {

        $sqlerro = true;
        $erro_msg = 'O arquivo selecionado tem uma extensão inválida.';
      }

      if (!empty($_FILES["arquivoedital"]["name"])) {

        $oidgrava = db_geraArquivoOid("arquivoedital", "", 1, $conn);

        if ($sqlerro == false) {

          $clliclicitaedital->l27_arquivo   = $oidgrava;
          $clliclicitaedital->l27_arqnome   = trim($nomearq);
          $clliclicitaedital->l27_liclicita = $l20_codigo;
          $clliclicitaedital->incluir(null);

          $erro_msg = $clliclicitaedital->erro_msg;
          if ($clliclicitaedital->erro_status == 0) {
            $sqlerro = true;
          }
        }
      } else {

        $erro_msg = "Arquivo do edital não informado!";
        $sqlerro  = true;
      }
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();

    $clliclicitaedital->excluir($l27_sequencial);
    $erro_msg = $clliclicitaedital->erro_msg;
    if ($clliclicitaedital->erro_status == 0) {
      $sqlerro = true;
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($opcao)) {

  $result = $clliclicita->sql_record($clliclicitaedital->sql_query($l27_sequencial));
  if ($clliclicita->numrows > 0) {

    db_fieldsmemory($result, 0);
    $arquivoedital = $l27_arqnome;
  }

  $result = $clliclicita->sql_record($clliclicita->sql_query($l27_liclicita));
  if ($clliclicita->numrows > 0) {

     db_fieldsmemory($result, 0);
     $db_botao = true;
  }
}

if (isset($chavepesquisa)) {

  $db_opcao = 1;
  $result   = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa));
  if ($clliclicita->numrows > 0) {

    db_fieldsmemory($result, 0);
     $db_botao = true;
  }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" style="padding-top:25px;">
  <tr>
    <td>
    <center>
      <?
        include(modification("forms/db_frmliclicitaeditalalt.php"));
      ?>
    </center>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {

  if (!empty($erro_msg)) {
    db_msgbox($erro_msg);
  }
}

if ($db_opcao == 22) {
  echo "<script>js_pesquisa();</script>";
}
?>
