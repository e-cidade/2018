<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

define('EXECUCAO_CONTRATO_FINANCEIRA', 1);
define('EXECUCAO_FINANCEIRA_EMPENHO', 2);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oDaoAcordo = new cl_acordo();
$oDaoAcordo->rotulo->label();

$sLegend = "Mapa de Execução";
if ($oGet->execucao == EXECUCAO_FINANCEIRA_EMPENHO) {
  $sLegend = "Mapa de Execução Financeira";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
</head>
<body style="background-color: #CCCCCC; margin-top: 20px;">
  <div class="container">

    <fieldset>

      <legend><strong><?php echo $sLegend; ?></strong></legend>

      <table>

        <tr>
          <td title="<?php echo $Tac16_sequencial; ?>">
            <?php db_ancora($Lac16_sequencial, "js_pesquisarAcordo(true);", 1); ?>
          </td>
          <td>
            <?php
              db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text', 1," onchange='js_pesquisarAcordo(false);' ");
              db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto,true,'text',3);
            ?>
          </td>
        </tr>

      </table>

    </fieldset>

    <input id="btnGerarRelatorio" type="button" value="Gerar" onClick="return js_gerarRelatorio();" disabled />

  </div>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

const MENSAGENS = 'patrimonial.contratos.con4_mapaexecucao001.';
var oGet = js_urlToObject();

function js_gerarRelatorio() {

  var iAcordo = $('ac16_sequencial').value;

  if (empty(iAcordo)) {
    return alert(_M(MENSAGENS + 'acordo_nao_selecionado'));
  }

  if (oGet.execucao == 1) {

    var sParametros = 'width=' + document.body.clientWidth + ', height=' + document.body.clientHeight + 'location=0';
    window.open('con4_mapaexecucao002.php?execucao=1&acordo=' + iAcordo, '', sParametros).moveTo(0, 0);
  } else {

    var sParametros = 'width=' + document.body.clientWidth + ', height=' + document.body.clientHeight + 'location=0';
    window.open('con4_mapaexecucao003.php?execucao=2&acordo=' + iAcordo, '', sParametros).moveTo(0, 0);
  }

}

function js_pesquisarAcordo(lMostrar) {

  var sTituloJanela = 'Pesquisar Acordos';
  var sQueryString = '?execucao='+oGet.execucao+'&funcao_js=parent.js_pesquisarAcordo.retornoAncora|ac16_sequencial|ac16_resumoobjeto';

  /**
   * Altera querystring quando nao for para exibir grid dos acordos
   */
  if (!lMostrar) {

    var iAcordo = $('ac16_sequencial').value;

    if (empty(iAcordo)) {

      $('ac16_sequencial').value = "";
      $('ac16_resumoobjeto').value = "";
      $('btnGerarRelatorio').disabled = true;
      return false;
    }

    sQueryString = '?execucao='+oGet.execucao+'&descricao=true&pesquisa_chave=' + iAcordo + '&funcao_js=parent.js_pesquisarAcordo.retornoInput';
  }

  /**
   * Iframe paara pesquisar acordo
   */
  js_OpenJanelaIframe('top.corpo', 'db_iframe_acordo', 'func_acordomapaexecucao.php' + sQueryString, sTituloJanela, lMostrar);

  /**
   * Funcao de retorno da pesquisa pelo ancora
   */
  js_pesquisarAcordo.retornoAncora = function(iAcordo, sDescricao) {

    $('ac16_sequencial').value = iAcordo;
    $('ac16_resumoobjeto').value = sDescricao;
    $('btnGerarRelatorio').disabled = false;
    db_iframe_acordo.hide();
  }

  /**
   * Funcao de retorno quando pesquisa é pelo input
   */
  js_pesquisarAcordo.retornoInput = function(sDescricao, lErro) {

    $('ac16_resumoobjeto').value = sDescricao;
    $('btnGerarRelatorio').disabled = false;
    if (lErro) {

      $('btnGerarRelatorio').disabled = true;
      $('ac16_sequencial').value = '';
      $('ac16_sequencial').focus();
    }
  }
}
</script>
