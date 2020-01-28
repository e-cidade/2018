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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("AjaxRequest.js");
  db_app::load("widgets/DBLancador.widget.js, widgets/DBAncora.widget.js");
  ?>
</head>
<body>

<div id="ctnJornada" class="container">

  <fieldset style="width:600px;">
    <legend>Grade de Efetividade</legend>

    <table align="center">
      <tr>
        <td align="right">
          <strong>Competência:</strong>
        </td>
        <td>
          <?
          $anocompetencia = db_anofolha();
          $mescompetencia = db_mesfolha();
          db_input('anocompetencia', 4, 1, true, 'text', 1);
          db_input('mescompetencia', 2, 1, true, 'text', 1);
          ?>
        </td>
      </tr>

      <tr>
        <td align="right">
          <strong>Filtro:</strong>
        </td>
        <td>
          <?
          $aFiltro = array('' => 'Nenhum', 'lotacao' => 'Lotação', 'matricula' => 'Matrícula');
          db_select('filtro', $aFiltro, true, 1, 'onchange="js_mudaTela()"');
          ?>
        </td>
      </tr>
    </table>

    <div id="divLancadorLotacao"  style="display:none;"></div>
    <div id="divLancadorServidor" style="display:none;"></div>

  </fieldset>

  <input type="button" name="gerar" id="gerar" value="Gerar Grade" onclick="js_gerarGrade()" />
</div>
</body>

<?php db_menu(); ?>

<script>
  js_criarLancadorLotacao();
  js_criarLancadorServidor();

  function js_mudaTela() {

    $('divLancadorLotacao') .style.display = 'none';
    $('divLancadorServidor').style.display = 'none';

    if ($F('filtro') != '') {

      if ($F('filtro') == 'matricula') {

        $('divLancadorServidor').style.display = 'inline';

      } else {

        $('divLancadorLotacao').style.display = 'inline';

      }

    }
  }

  function js_gerarGrade() {

    var sFiltro         = $F('filtro');
    var iAnoCompetencia = $F('anocompetencia');
    var iMesCompetencia = $F('mescompetencia');

    var sParametros = '';
    var sVirgula       = "";

    if (sFiltro == 'matricula') {

      aServidoresSelecionados = oLancadorServidor.getRegistros();

      aServidoresSelecionados.each(function (oServidor, iIndice) {
        sParametros += sVirgula+oServidor.sCodigo;
        sVirgula = ",";
      });

    } else if (sFiltro == 'lotacao') {

      aLotacoesSelecionadas = oLancadorLotacao.getRegistros();

      aLotacoesSelecionadas.each(function (oLotacao, iIndice) {
        sParametros += sVirgula+oLotacao.sCodigo;
        sVirgula = ",";
      });

    }

    if (iAnoCompetencia == '') {
      alert('Selecione o exercício de competência para a elaboração da grade.');
      return false;
    }

    if (iMesCompetencia == '') {
      alert('Selecione o mês de competência para a elaboração da grade.');
      return false;
    }

    if (sFiltro != '' && sParametros == '') {
      alert('Selecione registros para a elaboração da grade.');
      return false;
    }

    jan = window.open('rec2_consultagradeefetividade002.php?filtro='+sFiltro+
      '&anocompetencia='+iAnoCompetencia+
      '&mescompetencia='+iMesCompetencia+
      '&parametros='+sParametros, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  }

  function js_criarLancadorLotacao() {

    oLancadorLotacao = new DBLancador("oLancadorLotacao");
    oLancadorLotacao.setNomeInstancia("oLancadorLotacao");
    oLancadorLotacao.setLabelAncora("Lotacao: ");
    oLancadorLotacao.setTextoFieldset("Lotações Selecionadas");
    oLancadorLotacao.setParametrosPesquisa("func_rhlota.php", ['r70_codigo', 'r70_descr']);
    oLancadorLotacao.setGridHeight("400px");
    oLancadorLotacao.show($("divLancadorLotacao"));

  }

  function js_criarLancadorServidor() {

    oLancadorServidor = new DBLancador("oLancadorServidor");
    oLancadorServidor.setNomeInstancia("oLancadorServidor");
    oLancadorServidor.setLabelAncora("Servidor: ");
    oLancadorServidor.setTextoFieldset("Servidores Selecionados");
    oLancadorServidor.setParametrosPesquisa("func_rhpessoal.php", ['rh01_regist', 'z01_nome']);
    oLancadorServidor.setGridHeight("400px");
    oLancadorServidor.show($("divLancadorServidor"));

  }
</script>
</html>