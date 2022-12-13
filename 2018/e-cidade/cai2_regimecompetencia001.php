<?php
/**
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <form>
        <fieldset style="width: 400px; margin: 0 auto">
          <legend>Demonstrativo Reconhecimento de Competência</legend>
          <table class="form-container">
            <tr>
              <td>
                <a href="" id="labelCredor">Credor:</a>
              </td>
              <td>
                <?=db_input('z01_numcgm', 10, 1, 1, 'text', 1); ?>
                <?=db_input('z01_nome', 10, 1, 3); ?>
              </td>
            </tr>
            <tr>
              <td>
                <a href="" id="labelContrato">Acordo:</a>
              </td>
              <td>
                <?=db_input('ac16_sequencial', 10, 1, 1, 'text', 1); ?>
                <?=db_input('ac16_resumoobjeto', 10, 1, 3); ?>
              </td>
            </tr>
            <tr>
              <td>
                 <label>Competência:</label>
              </td>
              <td>
                <input type="text" id="competencia" class="field-size2" name="competencia" />
              </td>
            </tr>
          </table>
        </fieldset>

        <input type="button" value="Gerar" onclick="gerarRelatorio()" id="btnGerar">

      </form>
    </div>
  <?php db_menu(); ?>

  <script>

    (function() {

      var oInputData = new MaskedInput($('competencia'), '99/9999', {placeholder:' '});

      var oLookupCredor = new DBLookUp($('labelCredor'), $('z01_numcgm'), $('z01_nome'), {
        'sArquivo': 'func_cgm.php',
        'sObjetoLookUp': 'func_nome',
      });

      var oLookupContrato = new DBLookUp($('labelContrato'), $('ac16_sequencial'), $('ac16_resumoobjeto'), {
        'sArquivo': 'func_acordo.php',
        'sObjetoLookUp': 'db_iframe_acordo',
        'sQueryString': '&iTipoFiltro=4&descricao=true'
      });
    })();

    function gerarRelatorio() {

      var iCredor      = $F('z01_numcgm');
      var iContrato    = $F('ac16_sequencial');
      var sCompetencia = $F('competencia');

      if (sCompetencia.replace(' ', '').length != 7) {
        sCompetencia = '';
      }

      var oAjaxRequest = new AjaxRequest('cai4_regimecompetencia.RPC.php', {exec: 'gerarRelatorio', iCredor: iCredor, iContrato: iContrato, sCompetencia: sCompetencia}, function(arquivo) {

        if (arquivo.erro) {

          alert(arquivo.message);
          return false;
        }

        var oDownload = new DBDownload();
        oDownload.addFile(arquivo.arquivo, 'Regime Competência');
        oDownload.show();
      });

      oAjaxRequest.setMessage('Gerando Relatorio...');
      oAjaxRequest.execute();
    }
  </script>
  </body>
</html>