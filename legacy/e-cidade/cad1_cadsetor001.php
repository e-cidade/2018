<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

$clsetor  = new cl_setor;
$clcfiptu = new cl_cfiptu;
$db_opcao = 1;
$db_botao = true;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Incluir") {

  db_inicio_transacao();

  $sqlerro = false;

  if(!isset($j30_codi) || $j30_codi == "") {

  	$result_seq = db_query("select nextval('setor_j30_codi_seq')as j30_codi");
  	db_fieldsmemory($result_seq,0);
  }

  $j30_codi = str_pad($j30_codi, 4, "0", STR_PAD_LEFT);
  $clsetor->j30_codi = $j30_codi;

  $result_param = $clcfiptu->sql_record($clcfiptu->sql_query(db_getsession("DB_anousu"),"j18_formatsetor"));

  if ($clcfiptu->numrows > 0) {
    db_fieldsmemory($result_param, 0);
  }

  $clsetor->incluir($j30_codi, @$j18_formatsetor);

  if($clsetor->erro_status == 0) {

    $erro_msg = $clsetor->erro_msg;
  	$sqlerro  = true;
  }

  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
	<?php
	include(modification("forms/db_frmsetor.php"));
  db_menu();
  ?>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {

  if($clsetor->erro_status == "0") {

    $clsetor->erro(true,false);
    
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clsetor->erro_campo != "") {

      echo "<script> document.form1.".$clsetor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsetor->erro_campo.".focus();</script>";
    }
  } else {
    $clsetor->erro(true,true);
  }
}