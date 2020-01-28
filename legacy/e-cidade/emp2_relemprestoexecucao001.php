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

db_postmemory($HTTP_POST_VARS);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css"/>
  </head>
  <body class="body-default abas">
    <?php

      $clcriaabas = new cl_criaabas;
      $clcriaabas->scrolling = "yes";

      if (db_getsession("DB_anousu") <= 2007){
        $clcriaabas->identifica = array("g1"=>"Principal","g2"=>"Credor","filtro"=>"Filtro");
        $clcriaabas->title      = array("g1"=>"Principal","g2"=>"Selecionar credores","filtro"=>"Filtro");
        $clcriaabas->src        = array("g1"=>"emp2_relemprestoexecucao011.php","g2"=>"emp2_relemprestocredor001.php","filtro"=>"func_selorcdotacao_aba.php?desdobramento=true");
        $clcriaabas->funcao_js  = array("g1"=>"","g2"=>"","filtro"=>"js_atualizar_instit();");
        $clcriaabas->sizecampo  = array("g1"=>"20","g2"=>"20","filtro"=>"20");
      } else {
        $clcriaabas->identifica = array("g1"=>"Principal","g2"=>"Credor","filtro"=>"Filtro");
        $clcriaabas->title      = array("g1"=>"Principal","g2"=>"Selecionar credores","filtro"=>"Filtro");
        $clcriaabas->src        = array("g1"=>"emp2_relemprestoexecucao011.php","g2"=>"emp2_relempcredor001.php","filtro"=>"func_selorcdotacao_aba.php?desdobramento=true");
        $clcriaabas->funcao_js  = array("g1"=>"","g2"=>"","filtro"=>"js_atualizar_instit();");
        $clcriaabas->sizecampo  = array("g1"=>"20","g2"=>"20","filtro"=>"20");
      }

      $clcriaabas->cria_abas();

      db_menu();
    ?>
    <script type="text/javascript">
      function js_atualizar_instit() {

        var aInstituicoes = iframe_g1.getInstituicoesSelecionadas();
        iframe_filtro.atualizarPaginaParametros(aInstituicoes, true);
      }
    </script>
  </body>
</html>