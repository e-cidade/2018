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
require_once("dbforms/db_funcoes.php");

require_once("classes/db_cgmcorreto_classe.php");
require_once("classes/db_cgmerrado_classe.php");
require_once("classes/db_cgm_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

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

      db_inicio_transacao();

      $clcgmcorreto = new cl_cgmcorreto;
      $clcgmerrado = new cl_cgmerrado;
      $clcgm = new cl_cgm;

      $clcgmcorreto->z10_numcgm = $principal;
      $clcgmcorreto->z10_data   = date("Y-m-d",db_getsession("DB_datausu"));
      $clcgmcorreto->z10_hora   = date("H:m");
      $clcgmcorreto->z10_login  = db_getsession("DB_id_usuario");
      $clcgmcorreto->z10_instit = db_getsession("DB_instit");
      $clcgmcorreto->z10_proc   = 'false';
      $clcgmcorreto->incluir(0);
      $erro = false;

      if ($clcgmcorreto->erro_status == '1') {

        $sec = split("XX",$segundo);
        for ($i = 0; $i < sizeof($sec); $i++) {

          if ($sec[$i] == $principal) {
            continue;
          }

          $res = $clcgm->sql_record( $clcgm->sql_query($sec[$i], 'z01_nome') );
          db_fieldsmemory($res, 0, 0);

          $clcgmerrado->z11_nome = pg_escape_string($z01_nome);
          $clcgmerrado->incluir($clcgmcorreto->z10_codigo, $sec[$i]);

          if ($clcgmerrado->erro_status == '0') {

          	$erro_msg = $clcgmerrado->erro_msg;
          	$erro = true;
          	break;
          }
        }

      } else {

        $erro_msg = $clcgmcorreto->erro_msg;
        $erro = true;
      }

      db_fim_transacao($erro);
    ?>
  </body>
</html>
<?php
  if ($erro == true) {
    db_msgbox($erro_msg);
  }
?>