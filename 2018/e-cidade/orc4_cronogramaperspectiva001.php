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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cronogramaperspectiva_classe.php");
include("classes/db_ppaversao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcronogramaperspectiva = new cl_cronogramaperspectiva;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){

  $lSqlErro = false;
  db_inicio_transacao();

  $oDaoPPAIntegracao = new cl_ppaintegracao();
  $sSqlPPA           = $oDaoPPAIntegracao->sql_query_file(null,
                                                          "o123_ano",
                                                           null,
                                                           "o123_ppaversao={$o119_ppaversao}
                                                           and o123_tipointegracao = 1"
                                                          );
  $rsPPA = $oDaoPPAIntegracao->sql_record($sSqlPPA);

  if ($oDaoPPAIntegracao->numrows == 0) {

    $clcronogramaperspectiva->erro_status = "0";
    $clcronogramaperspectiva->erro_msg    = "Não foram encontradas informações sobre a perspectiva do ppa informada.";
    $lSqlErro = true;
  }

  if (!$lSqlErro) {

    $oPPaIntegracao = db_utils::fieldsMemory($rsPPA, 0);

    $clcronogramaperspectiva->o124_ppaversao = $o119_ppaversao;
    $clcronogramaperspectiva->o124_idusuario = db_getsession("DB_id_usuario");
    $clcronogramaperspectiva->o124_situacao  = 1;
    $clcronogramaperspectiva->o124_ano       = $oPPaIntegracao->o123_ano;
    $clcronogramaperspectiva->incluir(null);

    if ($clcronogramaperspectiva->erro_status == 0) {
      $lSqlErro = true;
    }
  }

  db_fim_transacao($lSqlErro);
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
  <body class="body-default" >
  	<?php
  	  include "forms/db_frmcronogramaperspectiva.php";
      db_menu();
    ?>
  </body>
</html>
<script>
  js_tabulacaoforms("form1","o124_ppaversao",true,1,"o124_ppaversao",true);
</script>
<?php
if(isset($incluir)){
  if($clcronogramaperspectiva->erro_status=="0"){
    $clcronogramaperspectiva->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcronogramaperspectiva->erro_campo!=""){
      echo "<script> document.form1.".$clcronogramaperspectiva->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcronogramaperspectiva->erro_campo.".focus();</script>";
    }
  }else{
    $clcronogramaperspectiva->erro(true,true);
  }
}
?>