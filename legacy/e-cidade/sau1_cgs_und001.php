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
require_once("libs/db_stdlibwebseller.php");
require_once("classes/db_cgs_classe.php");
require_once("classes/db_cgs_cartaosus_classe.php");
require_once("classes/db_cgs_und_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcgs              = new cl_cgs;
$clcgs_und          = new cl_cgs_und;
$clcgs_cartaosus    = new cl_cgs_cartaosus;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;

$z01_d_cadast_dia = date("d");
$z01_d_cadast_mes = date("m");
$z01_d_cadast_ano = date("Y");
$z01_d_ultalt_dia = date("d");
$z01_d_ultalt_mes = date("m");
$z01_d_ultalt_ano = date("Y");

if ( isset($incluir) || isset($alterar) ) {

  db_inicio_transacao();

  $lErro         = false;
  $lCNSExistente = false;
  $clcgs->incluir(null);

  if ($clcgs->erro_status == 0) {
    $lErro = true;
  }

  if ( $lObrigarCns && empty($s115_c_cartaosus) ) {
    $lErro = true;
  }

  if(!$lErro && $s115_c_cartaosus != "" ) {

    $sWhere = " s115_c_cartaosus = '{$s115_c_cartaosus}' ";
    $sSql   = $clcgs_cartaosus->sql_query_file(null, '1', null, $sWhere);

    $clcgs_cartaosus->sql_record($sSql);
    if ($clcgs_cartaosus->numrows == 0 ) {

      $clcgs_cartaosus->s115_i_cgs       = $clcgs->z01_i_numcgs;
      $clcgs_cartaosus->s115_c_cartaosus = $s115_c_cartaosus;
      $clcgs_cartaosus->s115_c_tipo      = 'D';
      $clcgs_cartaosus->s115_i_entrada   = 1;
      $clcgs_cartaosus->incluir(null);

      if ( $clcgs_cartaosus->erro_status == "0" ) {

        $lErro = true;
        $clcgs_und->erro_status = $clcgs_cartaosus->erro_status;
        $clcgs_und->erro_msg    = $clcgs_cartaosus->erro_msg;
      }
    } else {
      $lErro         = true;
      $lCNSExistente = true;
    }
  }

  if ( !$lErro ) {

    $clcgs_und->z01_i_cgsund  = $clcgs->z01_i_numcgs;
    $z01_i_cgsund             = $clcgs->z01_i_numcgs;
    $clcgs_und->z01_i_login   = DB_getsession("DB_id_usuario");
    $clcgs_und->z01_b_inativo = isset($z01_b_inativo) ? "true" : "false";
    $clcgs_und->incluir($clcgs->z01_i_numcgs);

    if ($clcgs_und->erro_status == 0) {
      $lErro = true;
    }
  }

  db_fim_transacao($lErro);
  if ($lErro) {
    $z01_i_cgsund = "";
  }

  if ( isset($funcao_js) && $clcgs_und->erro_status != '0' ) {
    echo " <script>".$funcao_js."($clcgs->z01_i_numcgs, '$z01_v_nome');
                     parent.db_iframe_cgs_und.hide();
           </script>";
    exit;
  }
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
<script language='JavaScript' type='text/javascript' src='scripts/classes/saude/validaCNS.js'></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default'>
  <div class="container">
    <?include("forms/db_frmcgs_und.php");?>
  </div>
</body>
</html>
<?php
if ( isset($incluir) ) {

  if ( $clcgs->erro_status == "0" ) {

    $clcgs->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $clcgs->erro_campo != "" ) {

      echo "<script> document.form1.".$clcgs->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs->erro_campo.".focus();</script>";
    }
  } else if ( $clcgs_und->erro_status == "0" ) {

    $clcgs_und->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $clcgs_und->erro_campo != "" ) {

      echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
    }
  } else if ( $clcgs_cartaosus->erro_status == "0" ) {

    $clcgs_cartaosus->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $clcgs_cartaosus->erro_campo != "" ) {

      echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".focus();</script>";
    }
  } else if ($lErro && $lCNSExistente) {

    echo "<script> document.form1.s115_c_cartaosus.style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.s115_c_cartaosus.focus();</script>";

    db_msgBox("Cartão SUS já esta cadastrado no sistema.");
    exit;
  } else {

    $clcgs_und->erro( true, false );
    $z01_i_cgsund = $clcgs->z01_i_numcgs;

    echo "<script> document.form1.z01_i_cgsund.value=$z01_i_cgsund;</script>";

    if (!isset($funcao_js)) {
      ?>
      <script>
        parent.document.formaba.a2.disabled = false;
        parent.document.formaba.a2.style.color = "black";
        parent.iframe_a1.location.href = "sau1_cgs_und002.php?chavepesquisa=<?=$z01_i_cgsund?>";
        parent.iframe_a2.location.href = "sau1_cgs_undoutros002.php?chavepesquisa=<?=$z01_i_cgsund?>&db_value=Incluir&retornacgs=<?=$retornacgs?>&retornanome=<?=$retornanome?>&redireciona=<?=$redireciona?>";
        parent.mo_camada('a2');
        parent.document.formaba.a3.disabled = false;
        parent.document.formaba.a3.style.color = "black";
        parent.iframe_a3.location.href = "sau1_cgs_doc002.php?chavepesquisa=<?=$z01_i_cgsund?>&db_value=Incluir&retornacgs=<?=$retornacgs?>&retornanome=<?=$retornanome?>";
      </script>
    <?php
    }
  }
}
?>