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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

$aExercicios  = array();

for ($iIndice = db_getsession('DB_anousu'); $iIndice >= (db_getsession('DB_anousu') - 10); $iIndice--) {
  $aExercicios[$iIndice] = $iIndice;
}

?>
<html>
  <head>
  <?php db_app::load("estilos.css, scripts.js, strings.js, prototype.js, EmissaoRelatorio.js"); ?>
  </head>
  <body class="body-default">
    <div class="container">
      <fieldset>
        <legend>Lançamentos Tributários</legend>
        <table>
          <tr>
            <td>
              <label class="bold" for="iAnoCalculo">Exercício:</label>
            </td>
            <td>
              <?php db_select('iAnoCalculo', $aExercicios, true, 1, "style='width: 100px'"); ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input type="button" name="imprimir" id="imprimir" value="Imprimir"/>
    </div>
    <?php db_menu(); ?>
    <script type="text/javascript">

      $('imprimir').addEventListener('click', function() {

        var oRelatorio = new EmissaoRelatorio("arr2_lancamentostributarios002.php", { iAnoCalculo : $F('iAnoCalculo')});
        oRelatorio.open();
      });
    </script>
  </body>
</html>
