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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("model/dbLayoutLinha.model.php"));

$iAnoAtual = date("Y", db_getsession("DB_datausu"));

$iEscola    = db_getsession("DB_coddepto");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
    <? db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js"); ?>
    <? db_app::load("estilos.css, grid.style.css"); ?>
    <style type="text/css">
        #arquivo {
            height: 25px;
        }
    </style>
</head>
<body class="body-default" onLoad="a=1">
<div class="container">
  <form name="form1" method="post" action="edu4_importaralunoscensoprocessamento.php" enctype="multipart/form-data">
    <fieldset>
      <legend>Importação de Informações do CENSO ESCOLAR -> ALUNO</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="ano_opcao">Ano das Informações do Arquivo:</label>
          </td>
          <td>
            <select id="ano_opcao" name="ano_opcao">
              <option value="<?= $iAnoAtual ?>" <?= !empty($ano_opcao) == $iAnoAtual ? "selected" : "" ?>>
                  <?= $iAnoAtual ?>
              </option>
              <option value="<?= $iAnoAtual - 1 ?>" <?= !empty($ano_opcao) == $iAnoAtual - 1 ? "selected" : "" ?>>
                  <?= $iAnoAtual - 1 ?>
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label>Arquivo de Importação do Censo:</label>
          </td>
          <td>
              <?php
              db_input('arquivo', 50, '', true, 'file', 3, "");
              ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="importar" type="submit" id="importar" value="Importar"/>
  </form>
</div>
<?php
db_menu();
?>

<script>
    var oUrl = js_urlToObject(null);

    if (oUrl.sArquivoLog){
        emiteLog();
    }

    function emiteLog() {

        var sMensagem = "Importação concluída com sucesso. Deseja imprimir os logs?";

        if(oUrl.lTemInconsistencia) {
          sMensagem = "Foram encontradas inconsistências no arquivo de importação. Deseja imprimir os logs?";
        }

        if(confirm(sMensagem)){
            var sEndereco = 'edu4_importacaoatualizacaoaluno002.php?sArquivoErro='+oUrl.sArquivoLog;
            var oJanela   = window.open(sEndereco,'', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
            oJanela.moveTo(0,0);
        }
        location.href = "edu4_importaralunoscenso001.php";
    }
</script>

</body>
</html>
