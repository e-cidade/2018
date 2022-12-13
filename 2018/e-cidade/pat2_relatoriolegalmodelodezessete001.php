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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="relatorioLegal" id="relatorioLegal" method="post" action="pat2_relatoriolegalmodelodezessete002.php" target="relatorioModeloXVII">
        <fieldset>
          <legend>Modelo XVII - Bens Patrimoniais - Termo de Baixa Definitiva</legend>
          <table>
            <tr>
              <td width="1%"><label class="bold" for="mes" id="lbl_mes">Mês:</label></td>
              <td><?php

                  $aMeses = array();
                  for ($iMes = 1; $iMes <= 12; $iMes++) {
                    $aMeses[$iMes] = DBDate::getMesExtenso($iMes);
                  }

                  db_select("mes", $aMeses, true, 1);
                ?></td>
            <tr>
              <td><label class="bold" for="ano" id="lbl_ano">Ano:</label></td>
              <td><?php
                  $Sano = "Ano";
                  db_input("ano", 4, 1, true, 'text', 1, '', '', '', '', 4);
                ?></td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend>Considerações</legend>
                  <textarea name="consideracoes" id="consideracoes" cols="60" rows="5"></textarea>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" value="Gerar" id="gerar"/>
      </form>
    </div>
    <script type="text/javascript">

      (function(exports) {

        var oAno = $("ano"),
            oMes = $("mes"),
            oConsideracoes = $("consideracoes"),
            oFormulario = $('relatorioLegal');

        const MENSAGENS = "patrimonial.patrimonio.pat2_relatoriolegalmodelodezessete.";

        $('gerar').observe('click', function() {

          if (empty(oAno.value)) {
            return alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Ano"}) );
          }

          if (oAno.value.length < 4 || (new Number(oAno.value)) == 0) {
            return alert( _M(MENSAGENS + "ano_invalido") );
          }

          oWindow = window.open('', 'relatorioModeloXVII','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          oWindow.moveTo(0,0);
          oFormulario.submit();
        });

      })(this);

    </script>
  </body>
  <?php db_menu(); ?>
</html>