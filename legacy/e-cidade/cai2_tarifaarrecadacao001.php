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
require_once(modification('libs/db_utils.php'));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAncora.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

  <fieldset style="width: 500px;">
    <legend class="bold">Tarifas de Arrecadação</legend>
    <table style="width: 100%;" border="0">
      <tr>
        <td class="bold" style="width: 80px"><label for="dataInicial">Data Inicial:</label></td>
        <td style="width: 130px"><input id="dataInicial" /></td>
        <td class="bold" style="width: 80px"><label for="dataFinal">Data Final:</label></td>
        <td><input id="dataFinal" /></td>
      </tr>
      <tr>
        <td>
          <label for="codigoBanco">
          <?php
          db_ancora('Banco:', 'pesquisarBanco(true)', 1);
          ?>
          </label>
        </td>
        <td colspan="3">
          <?php
          $ScodigoBanco = 'Banco';
          db_input('codigoBanco', '8', 1, true, 'text', 1, "onchange=pesquisarBanco(false)");
          db_input('descricaoBanco', '40', 0, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <br/>
  <input type="button" value="Emitir" onclick="emitir()">

</div>
<?php
db_menu( db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit") );
?>
</body>
</html>

<script>

  var input = {
    'dataInicial'    : $('dataInicial'),
    'dataFinal'      : $('dataFinal'),
    'codigoBanco'    : $('codigoBanco'),
    'descricaoBanco' : $('descricaoBanco')
  };

  new DBInputDate(input.dataInicial);
  new DBInputDate(input.dataFinal);

  function emitir() {

    if (input.dataInicial.value.trim() === '') {
      input.dataInicial.focus();
      return alert('Data Inicial é de preenchimento obrigatório.');
    }

    if (input.dataFinal.value.trim() === '') {
      input.dataFinal.focus();
      return alert('Data Final é de preenchimento obrigatório.');
    }

    if (js_comparadata(input.dataInicial.value, input.dataFinal.value, '>')) {

      input.dataInicial.focus();
      return alert('Data Inicial deve ser menor que a Data Final.');
    }

    if (input.codigoBanco.value.trim() === '') {
      input.codigoBanco.focus();
      return alert('Banco é de preenchimento obrigatório.');
    }

    var parametros = [
      "dataInicial="+input.dataInicial.value,
      "dataFinal="+input.dataFinal.value,
      "codigoBanco="+input.codigoBanco.value
    ];

    var arquivoImpressao = 'cai2_tarifaarrecadacao002.php?';
    arquivoImpressao += parametros.join('&');
    window.open(arquivoImpressao);

  }

  /**
   * Pesquisa os bancos cadastrados no sistema
   * @param abrirJanela
   * @returns {boolean}
   */
  function pesquisarBanco(abrirJanela) {

    if (input.codigoBanco.value.trim() === '' && abrirJanela === false) {
      input.descricaoBanco.value = '';
      return false;
    }

    var caminhoLookup = 'func_db_bancos.php?funcao_js=parent.preencherDadosBanco|db90_codban|db90_descr';
    if ( ! abrirJanela) {
      caminhoLookup = 'func_db_bancos.php?funcao_js=parent.completarDadosBanco&pesquisa_chave='+input.codigoBanco.value;
    }
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_db_bancos', caminhoLookup, 'Pesquisar Bancos', abrirJanela);

  }

  /**
   * Preenche os dados nos inputs de acordo com a seleção do usuário na âncora
   * @param codigo
   * @param descricao
   */
  function preencherDadosBanco(codigo, descricao) {

    input.codigoBanco.value = codigo;
    input.descricaoBanco.value = descricao;
    db_iframe_db_bancos.hide();
  }

  /**
   * Completa os dados do banco com o que o usuário preencheu em tela
   * @param descricao
   * @param erro
   */
  function completarDadosBanco(descricao, erro) {

    input.descricaoBanco.value = descricao;
    if (erro) {
      input.codigoBanco.value = '';
    }
  }
</script>