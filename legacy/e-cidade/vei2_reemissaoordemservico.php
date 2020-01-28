<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form id="formReemissaoOrdemServico">
    <?php db_input('codigo_manutencao', 10, false, true, 'hidden'); ?>
    <fieldset>
      <legend><strong>Reemissão de Ordem de Serviço</strong></legend>
      <table>
        <tr>
          <td class="bold">
            <label for="ordem_servico">
              <?php db_ancora('Ordem de Serviço:', 'buscarOrdemServico(true)', 2, null, 'ordem_servico_codigo_ancora'); ?>
            </label>
          </td>
          <td>
            <?php
            db_input('ordem_servico', 10, false, true, 'text', 1, 'onChange="buscarOrdemServico(false)"');
            db_input('descricao', 20, false, true);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id="btnReemitir" onClick="reemitirOrdemServico();" value="Emitir" />
  </form>
</div>
<script>

  /**
   * Realiza e reemissão da ordem de serviço de manutenção.
   */
  function reemitirOrdemServico() {

    if ($F('ordem_servico') == "" && $F('codigo_manutencao') != '') {

      alert('Campo Ordem de Serviço é de preenchimento obrigatório.');
      return false;
    }

    var sUrl = "vei4_manutencaoordemservico002.php?iCodigoManutencao=" + $F('codigo_manutencao');
    jan = window.open(sUrl,'','width=' + (screen.availWidth - 5 ) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

  /**
   * Busca uma ordem de serviço.
   */
  function buscarOrdemServico(lMostrar) {

    var sQuerySring = 'funcao_js=parent.retornoOrdemServico|ve62_codigo|ve62_numero|ve62_descr';
    var sArquivo    = 'func_veicmanut.php';
    var sTituloTela = 'Pesquisar Ordem de Serviço de Manutenção';

    if (!lMostrar) {
      sQuerySring = 'pesquisa_chave_numero=' + $F('ordem_servico') + '&funcao_js=parent.retornoOrdemServico';
    }

    js_OpenJanelaIframe('', 'db_iframe_veicmanut', sArquivo + '?' + sQuerySring, sTituloTela, lMostrar);
  }

  /**
   * Preenche as informações da busca de ordem de serviço.
   * @param {int}     iCodigo       Código da manutenção do Veículo.
   * @param {string}  sOrdemServico Número da Ordem de Serviço da Manutenção do Veículo.
   * @param {string}  sDescricao    Descrição da Manutenção do Veículo referente a Ordem de Serviço.
   * @param {boolean} lErro         Se houve erro na busca.
   */
  function retornoOrdemServico(iCodigo, sOrdemServico, sDescricao, lErro) {

    db_iframe_veicmanut.hide();
    $('btnReemitir').disabled    = lErro || iCodigo == "";
    $('codigo_manutencao').value = iCodigo;
    $('ordem_servico').value     = sOrdemServico;
    $('descricao').value         = sDescricao;
  }

  $('btnReemitir').disabled = true;
  $('codigo_manutencao').value = '';
  $('ordem_servico').value     = '';
  $('descricao').value         = '';
  buscarOrdemServico(true);
</script>
<?php db_menu(); ?>
</body>
</html>
