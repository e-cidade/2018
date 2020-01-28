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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("libs/db_app.utils.php"));

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  $aLibs = array();
  $aLibs[] = "scripts.js";
  $aLibs[] = "prototype.js";
  $aLibs[] = "strings.js";
  $aLibs[] = "estilos.css";
  $aLibs[] = "datagrid.widget.js";
  $aLibs[] = "strings.js";
  $aLibs[] = "grid.style.css";
  $aLibs[] = "estilos.css";
  $aLibs[] = "DBLancador.widget.js";
  $aLibs[] = "DBAncora.widget.js";
  $aLibs[] = "dbtextField.widget.js";
  $aLibs[] = "DBToogle.widget.js";
  $aLibs[] = "DBLookUp.widget.js";
  $aLibs[] = "EmissaoRelatorio.js";
  db_app::load(implode(',', $aLibs));
  ?>
</head>

<body class="body-default">
<div class="container">
  <form name="form1">
    <fieldset style="width: 560px;">
      <legend>Disponibilidade Financeira</legend>

      <table border="0">
        <tr>
          <td><label for="data_inicial" class="bold">Período:</label></td>
          <td>
            <?php
            db_inputdata("data_inicial", "", "", "", true, "text", 1, "");
            echo "<b>a</b>";
            db_inputdata("data_final", "", "", "", true, "text", 1, "");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="reduzido_codigo" class="bold">
              <a id="reduzido_ancora">Conta Contábil:</a>
            </label>
          </td>
          <td>
            <input type="text" name="c61_reduz" id="c61_reduz">
            <input type="text" name="c60_descr" id="c60_descr">
          </td>
        </tr>
        <tr>
          <td id="lancador_recurso" colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Opções de Visualização</legend>
              <table>
                <tr>
                  <td><label for="agrupamento" class="bold">Agrupar por:</label></td>
                  <td>
                    <?php
                    $aOpcoes = array( RelatorioDisponibilidadeFinanceira::AGRUPAMENTO_CARACTERISTICA_PECULIAR => "Recurso/Característica Peculiar",
                                      RelatorioDisponibilidadeFinanceira::AGRUPAMENTO_RECURSO => "Recurso");
                    db_select("agrupamento", $aOpcoes, true, 1);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><label for="agrupamento" class="bold">Exibir Lançamentos:</label></td>
                  <td>
                    <?php
                    $aOpcoesLancamentos = array( RelatorioDisponibilidadeFinanceira::MOSTRAR_LANCAMENTOS_SIM => "Sim",
                                                 RelatorioDisponibilidadeFinanceira::MOSTRAR_LANCAMENTOS_NAO => "Não");
                    db_select("lancamentos", $aOpcoesLancamentos, true, 1);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
  </form>
  <input name="lProcessar" id="lProcessar" onclick="js_validaFiltros();" type="button" style="margin-top: 10px;" value="Emitir" />
</div>
<?php
db_menu();
?>
</body>
</html>

<script type="text/javascript">

  var oAncoraReduzido = $("reduzido_ancora");
  var oCodigoReduzido = $("c61_reduz");
  var oDescricaoReduzido = $("c60_descr");

  var aCamposRecurso = new Array("o15_codigo", "o15_descr");
  var oDBLancadorRecurso = new DBLancador('oDBLancadorRecurso');
  oDBLancadorRecurso.setNomeInstancia('oDBLancadorRecurso');
  oDBLancadorRecurso.setTextoFieldset("Adicionar Recursos");
  oDBLancadorRecurso.setLabelAncora('Recurso:');
  oDBLancadorRecurso.setGridHeight(200);
  oDBLancadorRecurso.setCallbackAncora(validaLancadorRecurso);
  oDBLancadorRecurso.show($('lancador_recurso'));

  function validaLancadorRecurso() {

    if(oCodigoReduzido.value == '') {

      alert('Informe o campo Conta Contábil antes de selecionar o campo Recurso.');
      oDBLancadorRecurso.oElementos.oInputCodigo.value = "";
      return false;
    }
    oDBLancadorRecurso.setParametrosPesquisa('func_contacorrentedetalherecurso.php',
      aCamposRecurso,
      'iReduzido=' + oCodigoReduzido.value);

    return true;
  }

  function retornoLookUp() {
    oDBLancadorRecurso.clearAll();
  }

  var oReduzidoLookup = new DBLookUp(oAncoraReduzido, oCodigoReduzido, oDescricaoReduzido, {
    "sArquivo"      : "func_conplanoRazaoContaCorrente.php",
    "sObjetoLookUp" : "db_iframe_conplano",
    "sLabel"        : "Pesquisar Conta Contábil",
    "fCallBack"     :  retornoLookUp
  });

  function js_validaFiltros() {
    
    var dtInicial      = $("data_inicial").value;
    var dtFinal        = $("data_final").value;
    var iContaContabil = oCodigoReduzido.value;
    var sListaRecurso  = "";
    var iAgrupamento   = $("agrupamento").value;
    var iLancamentos   = $("lancamentos").value;

    oDBLancadorRecurso.getRegistros().each(function (oDado, iIndice) {

      if(!empty(sListaRecurso)) {
        sListaRecurso += ",";
      }
      sListaRecurso += oDado.sCodigo;
    });

    var sUrl = "con2_disponibilidadefinanceira002.php";

    if (dtInicial == "") {

      alert("O campo Data Inicial do Período é de preenchimento obrigatório.");
      return false;
    }

    if (dtFinal == "") {

      alert("O campo Data Final do Período é de preenchimento obrigatório.");
      return false;
    }

    if (dtInicial.getDate() > dtFinal.getDate()) {

      alert("A Data Final do Período deve ser maior ou igual a Data Inicial do mesmo.");
      return false;
    }

    if (dtInicial.getDate().getYear() != dtFinal.getDate().getYear()) {

      alert("A Data Inicial e Final do Período devem estar dentro do mesmo exercício.");
      return false;
    }

    if (iContaContabil == "") {

      alert('O campo Conta Contábil é de preenchimento obrigatório.');
      return false;
    }

    oParametros = {
      sDataInicial : dtInicial,
      sDataFinal   : dtFinal,
      iReduzido    : iContaContabil,
      sRecursos    : sListaRecurso,
      iAgrupamento : iAgrupamento,
      iMostrarLancamentos: iLancamentos
    };

    new EmissaoRelatorio(sUrl, oParametros).open();
  }
</script>
