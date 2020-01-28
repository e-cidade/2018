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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("libs/db_libpostgres.php"));

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <div id="documentos_evento">
        <div class="subcontainer">
          <form name="form2" method="post" action="cai2_relcredito006.php" target="_blank">
            <fieldset>
              <legend>Relat�rio de Cr�dito</legend>
            <table>
              <tr>
                <td>
                  <label for="tipo_origem" class="bold">Origem:</label>
                </td>
                <td>
                  <?php
                    $aTiposOrigem = array("numcgm" => "Numcgm", "matric" => "Matricula", "inscr" => "Inscri��o");
                    db_select("tipo_origem", $aTiposOrigem, true, null);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="ordenacao" class="bold">Ordena��o:</label>
                </td>
                <td>
                  <?php
                    db_select("ordenador", array("id" => "Identificador","nome" => "Nome"), true, null);
                    db_select("ordenacao", array("asc" => "Ascendente","desc" => "Descendente"), true, null);
                  ?>
                </td>
              </tr>
            </table>
            </fieldset>
            <input type="submit" id="pesquisar" value="Gerar Relat�rio" />
          </form>
        </div>
      </div>
    </div>
  </body>
</html>