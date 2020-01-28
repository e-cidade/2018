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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$Lcc31_classificacaocredores = null;
$oRotulo = new rotulocampo();
$oRotulo->label("cc31_classificacaocredores");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
    /**
     * Aqui faz a alinhamento na mão para que o botão do lançador não quebre de linha.
     */
    #ctnLancadorListaClassificacao #divacoes, 
    #ctnLancadorRecurso #divacoes {
      white-space: nowrap;
    }

    /**
     * Deixando o tamanho do campo descrição dos lançadores 'Credor' e 'Recurso' com tamanho maior 
     * para que se alinhem com a grid.
     */
    #txtDescricaooLancadorFornecedor, #txtDescricaooLancadorRecurso {
      width: 437px;
    }
  </style>
</head>
<body>
<div class="container">
  <div id="ctnGlobalAbas">
    <div id="containerFiltrosBasicos">
      <fieldset style="width: 670px;">
        <legend class="bold">Relatório de Lista de Classificação de Credores</legend>
          <table style="width: 100%">
            <tr>
              <td class="bold" style="width:100px;" nowrap="nowrap"><label for="exercicio">Exercício do Empenho:</label></td>
              <td>
                <?php
                $Sexercicio = "Exercício do Empenho";
                db_input("exercicio", 10, 1, true, 'text', 1);
                ?>
              </td>
            </tr>
            <tr>
              <td class="bold"><label for='data_vencimento_inicial'>Data de Vencimento:</label></td>
              <td class="bold">
                <?php
                db_inputdata('data_vencimento_inicial', null, null, null, true, 'text', 1);
                echo " <label for='data_vencimento_final'>até</label> ";
                db_inputdata('data_vencimento_final', null, null, null, true, 'text', 1);
                ?>
              </td>
            </tr>

            <tr>
              <td class="bold"><label for="situacao">Situação:</label></td>
              <td>
                <?php
                $aOpcoes = array(0 => "Todos", RelatorioEmpenhoClassificacaoCredores::SITUACAO_PAGOS => "Pagos", RelatorioEmpenhoClassificacaoCredores::SITUACAO_APAGAR => "A Pagar");
                db_select('situacao_pagamento', $aOpcoes, true, 1);
                ?>
              </td>
            </tr>

            <tr>
              <td colspan="2">
                <div id="ctnLancadorListaClassificacao"></div>
              </td>
            </tr>

          </table>
        </div>

      <!-- CONTAINER FILTRO CREDOR -->
      <div id="containerAbaFiltroCredor" style="width: 670px;">
        <div id="ctnLancadorCredorEmpenho"></div>
      </div>

      <!-- CONTAINER FILTRO RECURSO -->
      <div id="containerAbaRecurso" style="width: 670px;">
        <div id="ctnLancadorRecurso"></div>
      </div>
    </div>
  </fieldset>
  <p>
    <input type="button" id="btnEmitir" value="Emitir" />
  </p>

</div>

<?php db_menu(); ?>
</body>
</html>

<script type="text/javascript">

  var oAbas = new DBAbas($('ctnGlobalAbas'));
  oAbas.adicionarAba('Principal', $('containerFiltrosBasicos'));
  oAbas.adicionarAba('Credores', $('containerAbaFiltroCredor'));
  oAbas.adicionarAba('Recursos', $('containerAbaRecurso'));

  var oLancadorLista, oLancadorFornecedor, oLancadorRecurso;
  var oInputExercicio          = $('exercicio');
  var oInputDataInicial        = $('data_vencimento_inicial');
  var oInputDataFinal          = $('data_vencimento_final');
  var oInputSituacaoPagamento  = $('situacao_pagamento');
  oInputSituacaoPagamento.style.width = '140px';
  oInputExercicio.maxLength = 4;


  oLancadorLista = new DBLancador('oLancadorLista');
  oLancadorLista.setNomeInstancia('oLancadorLista');
  oLancadorLista.setLabelAncora('Lista de Classificação de Credores:');
  oLancadorLista.setParametrosPesquisa('func_classificacaocredores.php', ['cc30_codigo','cc30_descricao']);
  oLancadorLista.setTextoFieldset("Lista de Classificação de Credores");
  oLancadorLista.setTituloJanela("Pesquisa de Lista de Classificação de Credores");
  oLancadorLista.setGridHeight(250);
  oLancadorLista.show($('ctnLancadorListaClassificacao'));

  oLancadorFornecedor = new DBLancador('oLancadorFornecedor');
  oLancadorFornecedor.setNomeInstancia('oLancadorFornecedor');
  oLancadorFornecedor.setLabelAncora('Credor:');
  oLancadorFornecedor.setParametrosPesquisa('func_cgm_empenho.php', ['e60_numcgm','z01_nome']);
  oLancadorFornecedor.setTextoFieldset("Credores");
  oLancadorFornecedor.setTituloJanela("Pesquisa de Credores");
  oLancadorFornecedor.show($('ctnLancadorCredorEmpenho'));

  oLancadorRecurso = new DBLancador('oLancadorRecurso');
  oLancadorRecurso.setNomeInstancia('oLancadorRecurso');
  oLancadorRecurso.setLabelAncora('Recurso:');
  oLancadorRecurso.setParametrosPesquisa('func_orctiporec.php', ['o15_codigo','o15_descr']);
  oLancadorRecurso.setTextoFieldset("Recursos");
  oLancadorRecurso.setTituloJanela("Pesquisa de Recursos");
  oLancadorRecurso.show($('ctnLancadorRecurso'));


  $('btnEmitir').observe(
    'click',
    function() {

      if (js_comparadata(oInputDataInicial.value, oInputDataFinal.value, '>')) {

        alert('A Data de Vencimento Inicial não pode ser maior que a Data de Vencimento Final.');
        return false;
      }

      var aListas = [], aCredor= [], aRecurso =[];
      oLancadorLista.getRegistros().each(
        function (oLista) {
          aListas.push(oLista.sCodigo);
        }
      );

      oLancadorFornecedor.getRegistros().each(
        function (oCredor) {
          aCredor.push(oCredor.sCodigo);
        }
      );

      oLancadorRecurso.getRegistros().each(
        function (oRecurso) {
          aRecurso.push(oRecurso.sCodigo);
        }
      );

      var sPathRelatorio = "emp2_empenhoslistaclassificacao002.php?";
      sPathRelatorio += "&exercicio="+oInputExercicio.value;
      sPathRelatorio += "&data_inicial="+oInputDataInicial.value;
      sPathRelatorio += "&data_final="+oInputDataFinal.value;
      sPathRelatorio += "&situacao_pagamento="+oInputSituacaoPagamento.value;
      sPathRelatorio += "&listas="+aListas.implode(',');
      sPathRelatorio += "&credores="+aCredor.implode(',');
      sPathRelatorio += "&recursos="+aRecurso.implode(',');

      var oJanela = window.open(
        sPathRelatorio,
        '',
        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      oJanela.moveTo(0,0);
    }
  );
</script>