<?
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
?>
<html>
<head>
  <title>DBSeller</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="container">

  <div class="container">
    <fieldset style="width: 600px;">

      <legend class="bold">Importação de Planilha de Distribuição</legend>

      <table style="width: 100%;">
        <tr>
          <td class="bold"><label for="descricao_planilha">Arquivo:</label></td>
          <td>
            <?php db_input('arquivo', 70, 3, true, 'file', 1); ?>
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="p13_departamento">
              <a id="buscarDepartamento">Almoxarifado:</a>
            </label>
          </td>
          <td>
            <?php
            db_input("m91_codigo", 10, 1, true, 'text', 3);
            db_input("descrdepto", 20, 0, true, 'text');
            ?>
          </td>
        </tr>
      </table>

    </fieldset>

    <input type="button" id="btnImportar" value="Importar" />
  </div>

<?php db_menu(); ?>
<script>
  const PATH_RPC = 'mat4_planilhadistribuicao.RPC.php';

  var oButtonImportar        = $('btnImportar');
  var oCodigoAlmoxarifado    = $('m91_codigo');
  var oDescricaoAlmoxarifado = $('descrdepto');
  var oAncoraAlmoxarifado    = $('buscarDepartamento');

  oButtonImportar.observe('click', importarPlanilha);

  oLookupDepartamento = new DBLookUp(oAncoraAlmoxarifado, oCodigoAlmoxarifado, oDescricaoAlmoxarifado, {
    "sArquivo"              : "func_db_almox.php",
    "sObjetoLookUp"         : "db_iframe_db_almox",
    "sLabel"                : "Pesquisar Departamento"
  });

  function importarPlanilha() {

    if (empty($F('arquivo'))) {

      alert('O campo Arquivo é de preenchimento obrigatório.');
      return;
    }
    if (empty(oCodigoAlmoxarifado.value)) {

      alert('O campo Almoxarifado é de preenchimento obrigatório.');
      return;
    }

    oButtonImportar.disabled = 'disabled';

    var oParametro = {
      exec         : 'importarPlanilha',
      almoxarifado : oCodigoAlmoxarifado.value
    };

    new AjaxRequest(PATH_RPC, oParametro,
      function (oRetorno, lErro) {

        oButtonImportar.disabled = '';

        if (lErro) {

          alert(oRetorno.mensagem.urlDecode());
          return false;
        }

        var oDownload = new DBDownload();
        oDownload.addFile(oRetorno.nome_arquivo.urlDecode(), 'Requisições geradas');
        oDownload.show();

        alert(oRetorno.mensagem.urlDecode());
      }
    ).setMessage('Aguarde, importando planilha...')
     .asynchronous(false)
     .addFileInput($('arquivo'))
     .execute();
  }
</script>
</body>
</html>
