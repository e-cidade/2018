<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_auto_classe.php");
require_once("classes/db_levanta_classe.php");
require_once("classes/db_autolevanta_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);


$clauto        = new cl_auto;
$cllevanta     = new cl_levanta;
$clautolevanta = new cl_autolevanta;

$db_opcao    = 2;
$db_botao    = true;

global $y50_codauto;
global $y39_codandam;

if ( (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar" ) {

  try {

    if (!empty($y60_codlev) && !empty($y50_codauto)) {

      $sWhere        = " y117_auto = $y50_codauto and y117_levanta = $y60_codlev";
      $sSql          = $clautolevanta->sql_query_file(null, "*", null, $sWhere);
      $rsAutoLevanta = $clautolevanta->sql_record($sSql);

      if ($clautolevanta->numrows >= 1) {
        throw new Exception("Levantamento já cadastrado neste Auto de Infração!");
      }
    }

    $clautolevanta->y117_auto    = $y50_codauto;
    $clautolevanta->y117_levanta = $y60_codlev;
    $clautolevanta->alterar($y117_sequencial);

    $erro = $clautolevanta->erro_msg;
    if ( $clautolevanta->erro_status == 0 ) {
      $sqlerro = true;
    }
    db_fim_transacao();

  } catch (Exception $oErro) {

    db_fim_transacao(true);
    $clautolevanta->erro_status = 0;
    $clautolevanta->erro_msg    = $oErro->getMessage();
  }

} else if (isset($chavepesquisa) && isset($chavepesquisa1)) {

  $sWhere = " y117_auto = $chavepesquisa and y117_levanta = $chavepesquisa1 ";

  $db_opcao = 2;
  $result   = $clautolevanta->sql_record($clautolevanta->sql_query("", "*", "", $sWhere));
  db_fieldsmemory($result,0);

  $rsLevanta = $cllevanta->sql_record($cllevanta->sql_query_pesquisa(null, "*", null, "y60_codlev=$chavepesquisa1"));
  db_fieldsmemory($rsLevanta,0);

  $db_botao = true;
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
<body>
  <div class="container">
    <?php
      include("forms/db_frmautolevanta.php");
    ?>
  </div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){

  if($clautolevanta->erro_status=="0"){

    $clautolevanta->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautolevanta->erro_campo!=""){

      if ($clautolevanta->erro_campo == 'y117_levanta') {
        $clautolevanta->erro_campo = "y60_codlev";
      }

      echo "<script> document.form1.".$clautolevanta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautolevanta->erro_campo.".focus();</script>";
    }
  }else{

    $clautolevanta->erro(true,false);
    echo "<script>parent.iframe_autolevanta.location.href='fis1_autolevanta001.php?y50_codauto=$y50_codauto';</script>";
  }
}
?>