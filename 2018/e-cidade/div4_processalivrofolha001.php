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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$anoExercicio = db_getsession('DB_anousu');

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

    <style>
        .inputDefault {
            width: 80px;
        }
    </style>
</head>
<body class="body-default">
<div class="container">

    <fieldset style="width: 550px;">
        <legend class="bold">Implantação do Livro/Folha</legend>
        <table>
            <tr>
                <td class="bold"><label for="exercicio">Ano de Inclusão:</label></td>
                <td><input class="inputDefault" id="exercicio" maxlength="4" onchange="getInformacoesLivroFolha()" /></td>
            </tr>
            <tr>
                <td class="bold"><label for="livro">Livro:</label></td>
                <td>
                    <select id="livro" style="width: 80px;" onchange="pesquisarFolha()">
                        <option value="">Selecione</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="bold"><label for="folha">Folha:</label></td>
                <td>
                    <input class="inputDefault readonly" id="folha" disabled />
                </td>
            </tr>
        </table>
        <div id="ctnLancadorDivida">
        </div>
    </fieldset>
    <p>
        <input type="button" value="Salvar" onclick="processarLivroFolha()" />
    </p>

</div>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<div class="container">

</div>
</body>
</html>
<script type="text/javascript">

  const RPC_PROCESSAMENTO_FOLHA = 'div4_processalivrofolha.RPC.php';

  var input = {
    'exercicio' : new DBInputInteger($('exercicio')),
    'livro' : $('livro'),
    'folha' : new DBInputInteger($('folha'))
  };

  input.exercicio.value = <?php echo $anoExercicio; ?>;
  getInformacoesLivroFolha();

  var lancadorDivida = new DBLancador('lancadorDivida');
  lancadorDivida.setNomeInstancia('lancadorDivida');
  lancadorDivida.setLabelAncora('Dívida:');
  lancadorDivida.setParametrosPesquisa('func_divida.php', ['v01_coddiv', 'z01_nome'], 'retornarNomeCGM=true&pesquisar=true');
  lancadorDivida.setCallbackAncora(
    function () {

      if (input.exercicio.value === '') {
        alert('Informe o exercício para implantação do livro/folha para dívida ativa.');
        db_iframe_func_divida.hide();
      }

      lancadorDivida.oDadosPesquisa.sStringAdicional = 'retornarNomeCGM=true&pesquisar=true&anoinclusao='+input.exercicio.value;
    }
  ) ;
  lancadorDivida.show($('ctnLancadorDivida'));



  function getInformacoesLivroFolha() {

    if (input.exercicio.value.length !== 4) {
      alert('Informe um ano de exercício válido.');
      input.exercicio.value = '';
      return false;
    }

    input.livro.value = '';
    input.folha.value = '';

    AjaxRequest.create(
      RPC_PROCESSAMENTO_FOLHA,
      {'exec' : 'getLivro', 'exercicio' : input.exercicio.value},
      function (retorno, erro) {

        console.log('chegou');
        console.log(retorno);
        if (erro) {
          alert(retorno.mensagem);
          return false;
        }

        input.livro.options.length = 0;

        var optionSelecione = document.createElement('option');
        optionSelecione.innerHTML = 'Selecione';
        optionSelecione.value     = '';
        input.livro.appendChild(optionSelecione);

        retorno.dadosLivro.each(
          function (dadosLivro) {

            var optionLivro = document.createElement('option');
            optionLivro.innerHTML = dadosLivro.livro+" - "+dadosLivro.ano_inclusao;
            optionLivro.value     = dadosLivro.livro+"-"+dadosLivro.ano_inclusao;
            input.livro.appendChild(optionLivro);
          }
        );

      }
    ).execute();
  }

  function pesquisarFolha() {

    if (input.livro.value === '') {
      input.folha.value = '';
    }

    var livroSelecionado = input.livro.value.split('-');

    AjaxRequest.create(
      RPC_PROCESSAMENTO_FOLHA,
      {'exec' : 'getFolha', 'codigoLivro' : livroSelecionado[0], 'anoLivro' : livroSelecionado[1]},
      function (retorno) {
        input.folha.value = retorno.folha;
      }
    ).execute();
  }


  function processarLivroFolha() {

    if (input.exercicio.value === '') {

      alert('Ano de Inclusão é de preenchimento obrigatório.');
      return false;
    }

    if (input.livro.value === '') {

      alert('Livro é de preenchimento obrigatório.');
      return false;
    }

    var dividasSelecionadas = lancadorDivida.getRegistros();
    if (dividasSelecionadas.length === 0) {

      alert('Dívidas é de preenchimento obrigatório.');
      return false;
    }

    pesquisarFolha();

    AjaxRequest.create(
      RPC_PROCESSAMENTO_FOLHA,
      {
        'exec' : 'processarLivroFolha',
        'ano_inclusao' : input.exercicio.value,
        'livro' : input.livro.value,
        'folha' : input.folha.value,
        'dividas' : dividasSelecionadas
      },
      function (retorno, erro) {

        alert(retorno.mensagem);
        if (erro === false) {

          lancadorDivida.clearAll();
          pesquisarFolha();
        }
      }
    ).execute();
  }
</script>