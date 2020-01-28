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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

$oGet     = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo();
$clrotulo->label("o124_sequencial");
$clrotulo->label("o124_descricao");
$db_opcao = 1;

$iTipoRelatorio = (isset($oGet->tipo)) ? $oGet->tipo : null;

if (empty($iTipoRelatorio) || !in_array($iTipoRelatorio, array(1, 2))) {
  throw new Exception('Parâmetro do tipo de relatório inválido ou não informado.');
}

if ($iTipoRelatorio == 1) {
  $sTituloTela = "Relatório de Acompanhamento da Receita do Cronograma de Desembolso";
} else if ($iTipoRelatorio == 2) {
  $sTituloTela = "Relatório de Acompanhamento da Despesa do Cronograma de Desembolso";
}
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load(
    "scripts.js,"
    . "prototype.js,"
    . "strings.js,"
    . "AjaxRequest.js,"
    . "datagrid.widget.js,"
    . "datagrid/plugins/DBHint.plugin.js,"
    . "DBHint.widget.js,"
    . "DBViewInstituicao.widget.js,"
    . "DBDownload.widget.js"
  )
  ?>
  <link href="estilos.css"            rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

<div class="container">
  <div style="width: 600px;">
    <table align="center">
      <form name="form1" method="post" action="">
        <tr>
          <td align="center" colspan="3">
            <fieldset>
              <legend>
                <b><?php echo $sTituloTela ?></b>
              </legend>
              <table>
                <tr>
                  <td nowrap>
                    <?
                    db_ancora("<b>Perspectiva:</b>", "js_pesquisao125_cronogramaperspectiva(true);", $db_opcao);
                    ?>
                  </td>
                  <td nowrap>
                    <?
                    $So124_sequencial = "Perspectiva";
                    db_input('o124_sequencial', 10, $Io124_sequencial, true, 'text', $db_opcao, " onchange='js_pesquisao125_cronogramaperspectiva(false);'");
                    ?>
                    <?
                    db_input('o124_descricao', 40, $Io124_descricao, true, 'text', 3, '');
                    db_input('codrel', 40, '', true, 'hidden', 3, '');
                    ?>
                  </td>
                </tr>
                <?php if ($iTipoRelatorio == 2) : ?>
                  <tr>
                    <td>
                      <b>Nível:</b>
                    </td>
                    <td>
                      <?php
                      /* Extensão FiltroCronogramaDesembolso */
                      $aNiveis = array(
                        1 => "Órgão",
                        2 => "Unidade",
                        3 => "Função",
                        4 => "Subfunção",
                        5 => "Programa",
                        6 => "Projeto/Atividade",
                        7 => "Elemento",
                        8 => "Recurso",
                      );
                      db_select("nivel", $aNiveis, true, 1);
                      ?>
                    </td>
                  </tr>
                <?php endif ?>
                <tr>
                  <td align="center" colspan="2">
                    <div id="lista-instituicoes">&nbsp;</div>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td align="center">
            <input  name="emite" id="emite" type="button" value="Visualizar" onclick="js_emite();">
          </td>
        </tr>
        <input id="tipo_relatorio" name="tipo_relatorio" type="hidden" value="<?php echo $iTipoRelatorio ?>">
      </form>
    </table>
  </div>
</div> <!-- .container -->

<?php
db_menu(
  db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit")
);
?>

<script>
  const MENSAGENS         = 'financeiro.orcamento.orc2_acompanhamentocronograma.';
  const RELATORIO_RECEITA = 1;
  const RELATORIO_DESPESA = 2;

  var oViewInstituicao;

  document.observe('dom:loaded', function () {

    oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('lista-instituicoes'));
    oViewInstituicao.show();
  });

  function js_pesquisao125_cronogramaperspectiva(mostra) {

    if (mostra == true) {

      js_OpenJanelaIframe('',
        'db_iframe_cronogramaperspectiva',
        'func_cronogramaperspectiva.php?tipo=2&funcao_js=' +
        'parent.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano',
        'Pesquisa de Perspectiva', true);
    } else {

      if (document.form1.o124_sequencial.value != '') {

        js_OpenJanelaIframe('',
          'db_iframe_cronogramaperspectiva',
          'func_cronogramaperspectiva.php?tipo=2&pesquisa_chave=' +
          document.form1.o124_sequencial.value +
          '&funcao_js=parent.js_mostracronogramaperspectiva',
          'Pesquisa de Perspectiva',
          false);
      } else {

        document.form1.o124_descricao.value = '';
        document.form1.ano.value = ''

      }
    }

  }

  function js_mostracronogramaperspectiva(chave, erro, ano) {

    document.form1.o124_descricao.value = chave;
    if (erro == true) {

      document.form1.o124_sequencial.focus();
      document.form1.o124_sequencial.value = '';

    }

  }

  function js_mostracronogramaperspectiva1(chave1, chave2, chave3) {

    document.form1.o124_sequencial.value = chave1;
    document.form1.o124_descricao.value = chave2;
    db_iframe_cronogramaperspectiva.hide();

  }

  function js_emite() {

    $('emite').disabled = true;

    var iTipoRelatorio = $F('tipo_relatorio');
    var iPerspectiva   = $F('o124_sequencial');
    var aInstituicoes  = oViewInstituicao.getInstituicoesSelecionadas();

    var sUrl        = 'orc2_acompanhamentocronograma.RPC.php';
    var oParametros = {
      "iPerspectiva"  : iPerspectiva,
      "aInstituicoes" : aInstituicoes
    };

    if (iTipoRelatorio == RELATORIO_DESPESA) {

      oParametros.exec = 'emitirRelatorioDespesa';
      oParametros.iNivel = $F('nivel');
    } else if (iTipoRelatorio == RELATORIO_RECEITA) {

      oParametros.exec = 'emitirRelatorioReceita';
    }

    if (empty(aInstituicoes)) {
      alert(_M(MENSAGENS + 'instituicao_obrigatorio'));
      $('emite').disabled = false;
      return;
    }

    if (empty(iPerspectiva)) {

      alert(_M(MENSAGENS + 'perspectiva_obrigatorio'));
      $('emite').disabled = false;
      return;
    }

    new AjaxRequest(sUrl, oParametros,
      function (oRetorno, lErro) {

        if (oRetorno.mensagem) {
          alert(oRetorno.mensagem.urlDecode());
        }

        if (lErro) {
          return;
        }

        var oDownload = new DBDownload();
        oDownload.setHelpMessage('Clique no link abaixo para fazer download do relatório.');
        oDownload.addFile(oRetorno.caminho_relatorio, 'Relatório de Acompanhamento');
        oDownload.show();

      }
    ).setMessage(_M(MENSAGENS + 'aguarde_gerando_relatorio')).execute();

    $('emite').disabled = false;
  }
</script>
</body>
</html>