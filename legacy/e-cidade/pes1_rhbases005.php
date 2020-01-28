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
require_once(modification("classes/db_bases_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clbases  = new cl_bases;
$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

  $sRegra = '/^[A-Za-z0-9]/';

  if (preg_match($sRegra, $r08_codigo)) {

    db_inicio_transacao();

    $sqlerro = false;
    $anousu  = db_anofolha();
    $mesusu  = db_mesfolha();

    $clbases->incluir($anousu,$mesusu,$r08_codigo,db_getsession("DB_instit"));
    $erro_msg = $clbases->erro_msg;

    if ($clbases->erro_status==0) {
      $sqlerro = true;
    }

    // <!-- ContratosPADRS: tipo de base inserir -->

    db_fim_transacao($sqlerro);
  } else {

    $sqlerro  = true;
    $erro_msg = 'O campo com o Código da Base, deve ser preenchido somente com letras e números!';
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
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <?php
      include(modification("forms/db_frmrhbases.php"));
    ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?php
if (isset($incluir)) {

  if ($sqlerro==true) {

    db_msgbox($erro_msg);
    if ($clbases->erro_campo!="") {

      echo "<script> document.form1.".$clbases->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbases->erro_campo.".focus();</script>";
    };
  } else {

   db_msgbox($erro_msg);
   echo "
    <script>
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhbases.location.href='pes1_rhbases006.php?chavepesquisa=".@$clbases->r08_codigo."&liberaaba=true';
    </script>";
  }
}
?>
