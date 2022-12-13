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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<html>
<head>
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("AjaxRequest.js");
  db_app::load("EmissaoRelatorio.js");
  db_app::load("estilos.css,grid.style.css");
  ?>
</head>
<body>
  <div class="container">
    <form>
      <fieldset>
        <legend>Relatório de Taxas</legend>

        <table class="form-container">
          <tr>
            <td>
              <label for="grupos">Grupo:</label>
            </td>
            <td>
              <?php
              db_select('grupos', array(''=>'Selecione'), true, 1, 'onchange="popularComboNatureza(event)"');
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="naturezas">Natureza:</label>
            </td>
            <td>
              <?php
              db_select('naturezas', array(''=>'Selecione'), true, 1);
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="statusLancamento">Status do Lançamento:</label>
            </td>
            <td>
              <select id="statusLancamento">
                <option value="0">Todos</option>
                <option value="1">Calculado</option>
                <option value="2">Não Calculado</option>
              </select>
            </td>
          </tr>
        </table>

      </fieldset>

      <input id="btnImprimir" type="button" value="Imprimir" />
    </form>
  </div>
</body>
</html>
<?php db_menu(); ?>
<script>
const MENSAGENS_FIS2_TAXADIVERSOS001 = 'tributario.fiscal.fis2_taxadiversos001.';

buscaGrupos();

/**
 * Busca os grupos criados
 */
function buscaGrupos() {

  AjaxRequest.create(
    'fis1_taxadiversos.RPC.php',
    { exec: 'getGrupos' },
    function(oRetorno, lErro) {

      if(lErro === true) {

        alert(oRetorno.sMessage);
        return;
      }

      $('grupos').length = 0;
      $('grupos').add(new Option('Selecione', ''));
      $('grupos').add(new Option('Todos', 'T'));

      oRetorno.aGrupos.each(function(oGrupo) {
        $('grupos').add(new Option(oGrupo.descricao, oGrupo.codigo));
      });
    }
  ).setMessage('Aguarde, buscando os grupos...')
    .execute();
}

/**
 * Busca as naturezas de acordo com o grupo selecionado
 */
function popularComboNatureza () {

  $('naturezas').length = 0;
  $('naturezas').add(new Option('Selecione', ''));

  if($F('grupos') == '') {

    alert('Selecione um grupo.');
    return;
  }

  AjaxRequest.create(
    'fis1_taxadiversos.RPC.php',
    {
      exec   : 'getNaturezas',
      iGrupo : $F('grupos')
    },
    function (oRetorno) {

      if(oRetorno.lErro) {
        alert(oRetorno.sMessage);
        return;
      }

      oRetorno.aNaturezas.each(function (item, i) {

        option           = document.createElement('option');
        option.value     = item.codigo;
        option.innerHTML = item.descricao.substr(0, 30);

        if(item.descricao.length > 30){
          option.innerHTML += '...';
        }

        $('naturezas').appendChild(option);
      });
    }
  ).setMessage('Buscando naturezas de taxas...').execute();
}

/**
 * Controla o click do botão Imprimir
 */
$('btnImprimir').observe('click', function() {

  if(empty($F('grupos'))) {

    alert(_M(MENSAGENS_FIS2_TAXADIVERSOS001 + 'grupo_nao_selecionado'));
    return;
  }

  if(empty($F('naturezas'))) {

    alert(_M(MENSAGENS_FIS2_TAXADIVERSOS001 + 'natureza_nao_selecionada'));
    return;
  }

  var oEmissaoRelatorio = new EmissaoRelatorio(
    'fis2_taxadiversos002.php',
    {
      'grupo'    : $F('grupos'),
      'natureza' : $F('naturezas'),
      'status'   : $F('statusLancamento')
    }
  );
  oEmissaoRelatorio.open();
});
</script>