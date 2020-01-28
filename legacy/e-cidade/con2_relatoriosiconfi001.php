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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET);

if(empty($oGet->relatorio)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Código do relatório não especificado.');
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <meta http-equiv="Expires" CONTENT="0"/>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
</head>
  <body class="body-default abas">
    <?php

      $clcriaabas  = new cl_criaabas;

      $clcriaabas->identifica = array("relatorio" => "Relatório",
                                      "parametro" => "Parâmetros"
      );
      $clcriaabas->title      = array("relatorio" => "Relatório",
                                      "parametro"=> "Parâmetros");

      $clcriaabas->src        = array("relatorio" => "con2_relatoriosiconfi011.php?relatorio=" . $oGet->relatorio,
                                      "parametro" => "con4_parametrosrelatorioslegais001.php?c83_codrel=" . $oGet->relatorio
      );

      $clcriaabas->sizecampo  = array("relatorio" => "23",
                                      "parametro" => "23"
      );
      $clcriaabas->cria_abas();

      db_menu();
    ?>
  </body>
</html>