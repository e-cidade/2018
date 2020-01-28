<?php
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("dbforms/db_classesgenericas.php");

$clcriaabas = new cl_criaabas;

if (!isset($db_opcao)) {
  $db_opcao = 1;
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
  <body class="body-default abas">
    <?php

      $clcriaabas->identifica = array( "liclicita" => "Licitação", "infocomplementar"=>"Informações Complementares", "liclicitem"=>"Itens","liclicitemlote"=>"Lotes");
      $clcriaabas->sizecampo  = array( "liclicita" => "20", "infocomplementar" => "25", "liclicitem"=>"20","liclicitemlote" => "20");
      $clcriaabas->title      = array( "liclicita" => "Licitação", "infocomplementar" => "Outras Informações", "liclicitem"=>"Itens da Licitação","liclicitemlote"=>"Lote de itens");
      $clcriaabas->src        = array( "liclicita" => "lic1_liclicita002.php", "infocomplementar" => "lic1_liclicitainfocomplementar001.php", "liclicitem"=>"lic1_liclicitemalt001.php","liclicitemlote"=>"lic1_liclicitemlote001.php");

      if ($db_opcao==1){
        $clcriaabas->disabled   =  array("liclicitem"=>"true","liclicitemlote"=>"true", "infocomplementar" => "true");
      }

      $clcriaabas->cria_abas();

  	  db_menu();
  	?>
  </body>
</html>