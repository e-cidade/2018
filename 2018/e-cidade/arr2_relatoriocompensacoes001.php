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

?>
<html>
<head>
  <?php
    db_app::load(array(
      "estilos.css",
      "prototype.js",
      "scripts.js",
      "strings.js",
      "DBLookUp.widget.js",
      "EmissaoRelatorio.js"
    ));
  ?>
</head>
<body class="body-default">
  <div class="container">
    <fieldset>
      <legend>Relatório de Compensações</legend>
      <table>
        <tr>
          <td>
            <label class="bold" for="datainicial">Período de:</label>
          </td>
          <td>
            <?php db_inputdata('datainicial', null, null, null, true, 'text', 1); ?>
            <label class="bold" for="datafinal"> até </label>
            <?php db_inputdata('datafinal', null, null, null, true, 'text', 1); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="z01_numcgm" class="for">
              <a id="cgm_ancora">Nome/Razão Social:</a>
            </label>
          </td>
          <td>
            <input type="text" name="z01_numcgm" id="z01_numcgm">
            <input type="text" name="z01_nome" id="z01_nome">
          </td>
        </tr>
        <tr>
          <td>
            <label for="k00_tipo" class="for">
              <a id="tipodebito_ancora">Tipo de Débito:</a>
            </label>
          </td>
          <td>
            <input type="text" name="k00_tipo" id="k00_tipo">
            <input type="text" name="k00_descr" id="k00_descr">
          </td>
        </tr>
      </table>
    </fieldset>

    <input type="button" name="emitir" id="emitir" value="Emitir" />
  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">

    var oCgm = $("cgm_ancora");
    var oNumCgm =$("z01_numcgm");
    var oNomeCgm =$("z01_nome");
    var oCgmLookup = new DBLookUp(oCgm, oNumCgm, oNomeCgm, {
      "sArquivo": "func_nome.php",
      "sObjetoLookUp": "db_iframe_numcgm",
      "sLabel": "Pesquisar"
    });

    var oTipoDebito = $("tipodebito_ancora");
    var oCodigoTipoDebito = $("k00_tipo");
    var oDescricaoTipoDebito = $("k00_descr");
    var oTipoDebitoLookup = new DBLookUp(oTipoDebito, oCodigoTipoDebito, oDescricaoTipoDebito, {
      "sArquivo": "func_arretipo.php",
      "sObjetoLookUp": "db_iframe_arretipo",
      "sLabel": "Pesquisar"
    });

    $('emitir').addEventListener('click', function() {

      var oParametros = {
        iCgm: $F('z01_numcgm'),
        sDataInicial: $F('datainicial'),
        sDataFinal: $F('datafinal'),
        iTipoDebito: $F('k00_tipo')
      };

      new EmissaoRelatorio("arr2_relatoriocompensacoes002.php", oParametros).open();
    });
  </script>
</body>
</html>