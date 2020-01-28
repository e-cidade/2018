<?
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
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitaminuta_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clliclicita       = new cl_liclicita;
$clliclicitaminuta = new cl_liclicitaminuta;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

$aExtensoesBloqueadas = array('exe', 'com', 'bat', 'sh','php');

if (isset($oPost->incluir)) {

  db_inicio_transacao();

  if ( isset($_FILES['l43_arquivo']['name']) && $_FILES['l43_arquivo']['name'] != "" ) {

    $ext = array_reverse( explode('.',$_FILES['l43_arquivo']['name']));
    $ext = strtolower(trim($ext[0]));

    if(in_array($ext, $aExtensoesBloqueadas)) {

      $sqlerro  = true;
      $erro_msg = "O arquivo selecionado é inválido!";
    }

    $oidgrava     = db_geraArquivoOid("l43_arquivo", "", 1, $conn);
    try {
      $sNomeArquivo = File::cutName($_FILES['l43_arquivo']['name'], 50);
    } catch (Exception $oErro) {

      $sqlerro = true;
      $erro_msg = 'O arquivo selecionado tem uma extensão inválida.';
    }
  } else {

    $sqlerro  = true;
    $erro_msg = "Informe um arquivo!";
  }

  if (!$sqlerro) {

    $clliclicitaminuta->l43_arquivo   = $oidgrava;
    $clliclicitaminuta->l43_arqnome   = $sNomeArquivo;
    $clliclicitaminuta->l43_liclicita = $oPost->l20_codigo;
    $clliclicitaminuta->incluir(null);
    $erro_msg = $clliclicitaminuta->erro_msg;
    if ($clliclicitaminuta->erro_status == 0) {
      $sqlerro = true;
    }
  }

  db_fim_transacao($sqlerro);
} else if (isset($oPost->alterar)) {

  db_inicio_transacao();

  $clliclicitaminuta->excluir($oPost->l43_sequencial);
  $erro_msg = $clliclicitaminuta->erro_msg;

  if ($clliclicitaminuta->erro_status == 0 ) {
    $sqlerro = true;
  } else {

    if ( isset($_FILES['l43_arquivo']['name']) && $_FILES['l43_arquivo']['name'] != "" ) {

      $ext = array_reverse( explode('.',$_FILES['l43_arquivo']['name']));
      $ext = strtolower(trim($ext[0]));

      if(in_array($ext, $aExtensoesBloqueadas)) {

        $sqlerro  = true;
        $erro_msg = "O arquivo selecionado é inválido!";
      }

      $oidgrava     = db_geraArquivoOid("l43_arquivo", "", 1, $conn);
      try {
        $sNomeArquivo = File::cutName($_FILES['l43_arquivo']['name'], 50);
      } catch (Exception $oErro) {

        $sqlerro = true;
        $erro_msg = 'O arquivo selecionado tem uma extensão inválida.';
      }
    } else {

      $sqlerro  = true;
      $erro_msg = "Informe um arquivo!";
    }

    if (!$sqlerro) {

      $clliclicitaminuta->l43_arquivo   = $oidgrava;
      $clliclicitaminuta->l43_arqnome   = $sNomeArquivo;
      $clliclicitaminuta->l43_liclicita = $oPost->l20_codigo;
      $clliclicitaminuta->incluir(null);
      $erro_msg = $clliclicitaminuta->erro_msg;
      if ($clliclicitaminuta->erro_status == 0) {
        $sqlerro = true;
      }
    }
  }

  db_fim_transacao($sqlerro);
} else if (isset($oPost->excluir)) {

  db_inicio_transacao();

  $clliclicitaminuta->excluir($oPost->l43_sequencial);
  $erro_msg = $clliclicitaminuta->erro_msg;
  if($clliclicitaminuta->erro_status==0){
    $sqlerro=true;
  }

  db_fim_transacao($sqlerro);
} else if (isset($oPost->opcao)) {

  $result = $clliclicita->sql_record($clliclicitaminuta->sql_query($oPost->l43_sequencial));

  if ($clliclicita->numrows) {
    db_fieldsmemory($result,0);
    $l43_arquivo = $l43_arqnome;
  }

  $result = $clliclicita->sql_record($clliclicita->sql_query($l43_liclicita));
  if ($clliclicita->numrows > 0) {
    db_fieldsmemory($result,0);
    $db_botao = true;
  }
}

if(isset($oGet->chavepesquisa)){

  $db_opcao = 1;
  $result   = $clliclicita->sql_record($clliclicita->sql_query($oGet->chavepesquisa));
  if ($clliclicita->numrows > 0) {
    db_fieldsmemory($result,0);
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
        include(modification("forms/db_frmliclicitaminuta.php"));
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
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
  db_msgbox($erro_msg);
}

if ($db_opcao == 22) {
  echo "<script>js_pesquisa();</script>";
}
?>
