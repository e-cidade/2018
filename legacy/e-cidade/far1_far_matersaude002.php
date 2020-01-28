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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ('libs/db_utils.php');
require_once ("classes/db_far_matersaude_classe.php");
require_once ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$clfar_matersaude = new cl_far_matersaude;
$db_opcao         = 22;
$db_botao         = false;


if (isset($alterar)) {

  $lErro = false;
  if ( !empty($fa01_codigobarras) ) {

    $sWhere  = "     fa01_i_codigo <> {$fa01_i_codigo} ";
    $sWhere .= " and fa01_codigobarras = '{$fa01_codigobarras}' ";
    $sSql    = $clfar_matersaude->sql_query_file(null, "1", null, $sWhere);
    $rs      = db_query($sSql);
    if ( $rs && pg_num_rows($rs) > 0 ) {

      db_msgbox("Código de barras ({$fa01_codigobarras}) cadastrado em outro medicamento.");
      $lErro = true;
    }

  }

  if ( !$lErro ) {

    db_inicio_transacao();
    $db_opcao = 2;
    $clfar_matersaude->alterar($fa01_i_codigo);
    db_fim_transacao($clfar_matersaude->erro_status == '0' ? true : false);
  }
} else if(isset($chavepesquisa)) {

   $db_opcao = 2;
   $result  = $clfar_matersaude->sql_record($clfar_matersaude->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
}

$sLegenda = "Alteração de Medicamento";
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
  <body class="body-default" >

    <div class="container">

      <?php
        include("forms/db_frmfar_matersaude.php");
      ?>
    </div>

  </body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<?
if (isset($alterar)) {

  if ($clfar_matersaude->erro_status=="0") {

    $clfar_matersaude->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clfar_matersaude->erro_campo!="") {

      echo "<script> document.form1.".$clfar_matersaude->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfar_matersaude->erro_campo.".focus();</script>";
    }
  } else {
    $clfar_matersaude->erro(true,true);
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","fa01_t_obs",true,1,"fa01_t_obs",true);
</script>