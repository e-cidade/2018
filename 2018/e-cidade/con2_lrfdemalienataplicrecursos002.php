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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));

$oGet     = db_utils::postMemory($_GET);

$iAnoUsu = db_getsession("DB_anousu");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
</head>
<body class="body-default">
<div class="container">
  <form name="form1" method="post" action="">
    <input type="hidden" id="o116_codrel" value="<?= $oGet->codrel ?>">
    <fieldset>
      <legend>Anexo XI - Demonstrativo da Receita de Alienação de Ativos e Aplicação dos Recursos</legend>
      <table>
        <tr>
          <td colspan="2" id="lista-instituicao">

          </td>
        </tr>
        <tr>
          <td nowrap width="1%">
            <label for="o116_periodo" class="bold">Período:</label>
          </td>
          <td>
            <?php

            $oRelatorio = new relatorioContabil($oGet->codrel);

            $aPeriodos         = $oRelatorio->getPeriodos();
            $aListaPeriodos    = array();
            $aListaPeriodos[0] = "Selecione";

            foreach ($aPeriodos as $oPeriodo) {
              $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
            }

            if ($iAnoUsu < 2010) {

              $aListaPeriodos = array(
                "1B" => "1 º Bimestre", "2B" => "2 º Bimestre", "3B" => "3 º Bimestre",
                "4B" => "4 º Bimestre", "5B" => "5 º Bimestre", "6B" => "6 º Bimestre"
              );
            }

            db_select("o116_periodo", $aListaPeriodos, true, 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_emite();"/>
  </form>
</div>
<script type="text/javascript">

  oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('lista-instituicao'));
  oViewInstituicao.show();

  function js_emite() {

    var sUrl = "con2_lrfdemalienataplicrecursos002_2010.php";

    var iCodigoRelatorio = $F('o116_codrel');

    if (iCodigoRelatorio.length == 0) {
      return alert("Código do Relatório não informado.");
    }

    var oInstituicoes = oViewInstituicao.getInstituicoesSelecionadas().map(function(oItem) {
      return oItem.codigo;
    });

    if (oInstituicoes.length == 0) {
      return alert('Selecione ao menos uma Instituição.');
    }

    if ($F('o116_periodo') == 0) {
      return alert("Campo Período é de preenchimento obrigatório.");
    }

    var oRelatorio = new EmissaoRelatorio(sUrl, {
      db_selinstit : oInstituicoes.join(','),
      periodo      : $F('o116_periodo'),
      relatorio    : iCodigoRelatorio
    });
    oRelatorio.open();
  }
</script>
</body>
</html>