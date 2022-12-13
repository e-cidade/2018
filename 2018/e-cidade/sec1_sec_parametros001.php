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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_sec_parametros_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libdicionario.php"));

db_postmemory($_POST);
$clsec_parametros = new cl_sec_parametros;
$escola           = db_getsession("DB_coddepto");
$db_opcao         = 1;
$db_botao         = true;

if (isset($incluir)) {

  db_inicio_transacao();
  $clsec_parametros->incluir($ed290_sequencial);
  EducacaoSessionManager::registrarDiasManutencaoHistorico($ed290_diasmanutencaohistorico);
  db_fim_transacao();

}

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $clsec_parametros->alterar($ed290_sequencial);
  EducacaoSessionManager::registrarDiasManutencaoHistorico($ed290_diasmanutencaohistorico);
  db_fim_transacao();

}

$result = $clsec_parametros->sql_record($clsec_parametros->sql_query("","*","",""));

if ($clsec_parametros->numrows != 0) {

 db_fieldsmemory($result,0);
 $db_opcao = 2;

} else {
 $db_opcao= 1;
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
  <body class='body-default' >
  	<?php
    	include(modification("forms/db_frmsec_parametros.php"));
      db_menu();
    ?>
  </body>
</html>
<script>
js_tabulacaoforms("form1", "ed290_importcenso", true, 1, "ed290_importcenso", true);
</script>
<?php
if (isset($incluir)) {

  if ($clsec_parametros->erro_status == "0") {

    $clsec_parametros->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clsec_parametros->erro_campo != "") {

      echo "<script> document.form1.".$clsec_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsec_parametros->erro_campo.".focus();</script>";

    }

  } else {
    $clsec_parametros->erro(true,true);
  }
}

if (isset($alterar)) {

  if ($clsec_parametros->erro_status == "0") {

    $clsec_parametros->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clsec_parametros->erro_campo != "") {

      echo "<script> document.form1.".$clsec_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsec_parametros->erro_campo.".focus();</script>";

    };

  } else {

    $clsec_parametros->erro(true,false);
    db_redireciona("sec1_sec_parametros001.php?lMensagem=true");

  }
}
?>